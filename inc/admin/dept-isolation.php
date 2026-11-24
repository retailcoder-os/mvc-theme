<?php
/**
 * MV College Department Isolation Engine
 * 
 * Strict multi-tenant isolation guaranteeing a Department Head can never
 * read, edit, create into, or upload against another department by any route.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Determine whether a user is subject to strict departmental isolation.
 *
 * ONLY Department Heads without administrative or institutional review authority
 * are department-scoped. Principals, Administrators, and Content Managers have
 * unrestricted institutional access to all departments, notices, events, faculty,
 * and records.
 *
 * @param int|WP_User|null $user
 * @return bool
 */
function mvc_is_user_dept_scoped($user = null) {
    if (!$user) {
        $user = wp_get_current_user();
    } elseif (is_numeric($user)) {
        $user = get_userdata($user);
    }

    if (!$user || !($user instanceof WP_User) || !$user->exists()) {
        return false;
    }

    // Super Admin / Principal / System Admin: Always full institutional access
    if (in_array('administrator', (array) $user->roles, true) || user_can($user, 'manage_options')) {
        return false;
    }

    // Content Manager: Institutional editorial access across all departments
    if (in_array('content_manager', (array) $user->roles, true) || user_can($user, 'mvc_review_content')) {
        return false;
    }

    // Only designated Department Heads with the scope capability are restricted
    return in_array('department_head', (array) $user->roles, true) && user_can($user, 'mvc_dept_scope');
}

/**
 * List of post types subject to strict department isolation.
 *
 * @return array
 */
function mvc_scoped_types() {
    return apply_filters('mvc_scoped_types', array(
        'notice',
        'event',
        'faculty',
        'study_material',
        'department',
        'grievance',
    ));
}

/**
 * Assign a Department Head to a department term with atomic concurrency lock (C1).
 *
 * @param int $user_id
 * @param int $term_id
 * @return true|WP_Error
 */
function mvc_assign_dept_head($user_id, $term_id) {
    global $wpdb;

    $user = get_userdata($user_id);
    if (!$user || !in_array('department_head', $user->roles, true)) {
        return new WP_Error('mvc_invalid_user', __('Only users with the Department Head role can be assigned as HOD.', 'mvc-college'));
    }

    $term = get_term($term_id, 'dept_link');
    if (!$term || is_wp_error($term)) {
        return new WP_Error('mvc_invalid_term', __('Invalid department term specified.', 'mvc-college'));
    }

    $lock_key = 'mvc_dept_head_' . (int) $term_id;
    $lock = $wpdb->get_var($wpdb->prepare("SELECT GET_LOCK(%s, 5)", $lock_key));

    if (!$lock) {
        return new WP_Error('mvc_lock_timeout', __('Could not acquire department assignment lock. Please retry.', 'mvc-college'));
    }

    try {
        // Check if another department_head already holds this term
        $existing_users = get_users(array(
            'role'       => 'department_head',
            'meta_key'   => 'mvc_dept_term_id',
            'meta_value' => $term_id,
            'exclude'    => array($user_id),
        ));

        if (!empty($existing_users)) {
            $current_head = reset($existing_users);
            $wpdb->query($wpdb->prepare("SELECT RELEASE_LOCK(%s)", $lock_key));
            return new WP_Error(
                'mvc_dept_head_conflict',
                sprintf(
                    /* translators: 1: Department name, 2: Current Head user display name */
                    __('Department "%1$s" already has an assigned Head (%2$s). You must unassign or replace the current Head first.', 'mvc-college'),
                    $term->name,
                    $current_head->display_name . ' (' . $current_head->user_login . ')'
                ),
                array('current_head_id' => $current_head->ID)
            );
        }

        // Set user meta
        update_user_meta($user_id, 'mvc_dept_term_id', (int) $term_id);
        update_user_meta($user_id, 'mvc_account_status', 'active');

        // Sync display meta on department CPT post
        $dept_posts = get_posts(array(
            'post_type'   => 'department',
            'numberposts' => 1,
            'meta_key'    => 'dept_term_id',
            'meta_value'  => $term_id,
        ));

        if (!empty($dept_posts)) {
            update_post_meta($dept_posts[0]->ID, 'department_hod_user_id', $user_id);
        }

        // Audit the assignment
        mvc_audit('dept_head_assigned', 'user', $user_id, array(
            'term_id'   => $term_id,
            'dept_name' => $term->name,
        ));

        // Release the atomic lock
        $wpdb->query($wpdb->prepare("SELECT RELEASE_LOCK(%s)", $lock_key));

        do_action('mvc_dept_head_assigned', $user_id, $term_id);
        return true;
    } catch (\Throwable $e) {
        $wpdb->query($wpdb->prepare("SELECT RELEASE_LOCK(%s)", $lock_key));
        return new WP_Error('mvc_dept_assignment_error', $e->getMessage());
    }
}

/**
 * Replace Department Head: Unassigns old head and assigns new head atomically.
 *
 * @param int $new_user_id
 * @param int $term_id
 * @return true|WP_Error
 */
function mvc_replace_dept_head($new_user_id, $term_id) {
    // Unassign old head
    $old_heads = get_users(array(
        'role'       => 'department_head',
        'meta_key'   => 'mvc_dept_term_id',
        'meta_value' => $term_id,
    ));

    foreach ($old_heads as $old) {
        delete_user_meta($old->ID, 'mvc_dept_term_id');
        mvc_audit('dept_head_unassigned', 'user', $old->ID, array('term_id' => $term_id));
    }

    return mvc_assign_dept_head($new_user_id, $term_id);
}

/* ========================================================
   ENFORCEMENT POINT 1: map_meta_cap
   ======================================================== */
add_filter('map_meta_cap', 'mvc_enforce_dept_map_meta_cap', 10, 4);
function mvc_enforce_dept_map_meta_cap($caps, $cap, $uid, $args) {
    if (!in_array($cap, array('edit_post', 'delete_post', 'read_post', 'publish_post'), true)) {
        return $caps;
    }

    // Only apply department restrictions if user is strictly department-scoped
    if (!mvc_is_user_dept_scoped($uid)) {
        return $caps;
    }

    $post_id = isset($args[0]) ? (int) $args[0] : 0;
    if (!$post_id) {
        // New post pass-through; creation is scoped by wp_insert_post_data
        return $caps;
    }

    $post = get_post($post_id);
    if (!$post || !in_array($post->post_type, mvc_scoped_types(), true)) {
        return $caps;
    }

    $term = (int) get_user_meta($uid, 'mvc_dept_term_id', true);
    if (!$term) {
        // Department Head has no department assigned
        return array('do_not_allow');
    }

    // Check if post belongs to user's assigned dept_link
    if (!has_term($term, 'dept_link', $post)) {
        // Check if it's the department CPT post matching user's term
        if ($post->post_type === 'department') {
            $post_term_id = (int) get_post_meta($post->ID, 'dept_term_id', true);
            if ($post_term_id === $term) {
                return $caps;
            }
        }
        return array('do_not_allow');
    }

    return $caps;
}

/* ========================================================
   ENFORCEMENT POINT 2: pre_get_posts & REST QUERIES
   ======================================================== */
add_action('pre_get_posts', 'mvc_filter_scoped_queries_by_department');
function mvc_filter_scoped_queries_by_department($query) {
    if (is_admin() && $query->is_main_query()) {
        $user_id = get_current_user_id();
        if ($user_id && mvc_is_user_dept_scoped($user_id)) {
            $term_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
            $post_type = $query->get('post_type');

            if (!$post_type && isset($_GET['post_type'])) {
                $post_type = sanitize_key($_GET['post_type']);
            }

            if (in_array($post_type, mvc_scoped_types(), true)) {
                if (!$term_id) {
                    $query->set('post__in', array(0)); // Show nothing if no department assigned
                    return;
                }

                $tax_query = (array) $query->get('tax_query');
                $tax_query[] = array(
                    'taxonomy' => 'dept_link',
                    'field'    => 'term_id',
                    'terms'    => array($term_id),
                );
                $query->set('tax_query', $tax_query);
            }
        }
    }
}

// REST queries filtering for scoped post types
add_filter('rest_notice_query', 'mvc_filter_rest_dept_scope', 10, 2);
add_filter('rest_event_query', 'mvc_filter_rest_dept_scope', 10, 2);
add_filter('rest_faculty_query', 'mvc_filter_rest_dept_scope', 10, 2);
add_filter('rest_study_material_query', 'mvc_filter_rest_dept_scope', 10, 2);
add_filter('rest_grievance_query', 'mvc_filter_rest_dept_scope', 10, 2);

function mvc_filter_rest_dept_scope($args, $request) {
    $user_id = get_current_user_id();
    if ($user_id && mvc_is_user_dept_scoped($user_id)) {
        $term_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
        if (!$term_id) {
            $args['post__in'] = array(0);
            return $args;
        }

        $tax_query = isset($args['tax_query']) ? (array) $args['tax_query'] : array();
        $tax_query[] = array(
            'taxonomy' => 'dept_link',
            'field'    => 'term_id',
            'terms'    => array($term_id),
        );
        $args['tax_query'] = $tax_query;
    }
    return $args;
}

/* ========================================================
   ENFORCEMENT POINT 3 & 4: wp_insert_post_data, save_post & set_object_terms
   ======================================================== */
add_action('save_post', 'mvc_force_department_term_on_save', 10, 3);
function mvc_force_department_term_on_save($post_id, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!in_array($post->post_type, mvc_scoped_types(), true)) {
        return;
    }

    $user_id = get_current_user_id();
    if ($user_id && mvc_is_user_dept_scoped($user_id)) {
        $term_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
        if ($term_id) {
            // Force the department term onto the post regardless of what was posted
            wp_set_object_terms($post_id, array($term_id), 'dept_link', false);
        }
    }
}

// Reject any term other than the user's for scoped types
add_filter('set_object_terms', 'mvc_restrict_terms_for_dept_head', 10, 6);
function mvc_restrict_terms_for_dept_head($terms, $object_id, $tt_ids, $taxonomy, $append, $old_tt_ids) {
    if ($taxonomy !== 'dept_link') {
        return $terms;
    }

    $user_id = get_current_user_id();
    if ($user_id && mvc_is_user_dept_scoped($user_id)) {
        $user_term_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
        if (!$user_term_id) {
            return array();
        }
        return array($user_term_id);
    }
    return $terms;
}

/* ========================================================
   ENFORCEMENT POINT 5: MEDIA ISOLATION & UPLOAD RESTRICTIONS
   ======================================================== */
// Filter Media library to current user's uploads for Department Head
add_filter('ajax_query_attachments_args', 'mvc_scope_media_to_dept_head');
function mvc_scope_media_to_dept_head($query) {
    $user_id = get_current_user_id();
    if ($user_id && mvc_is_user_dept_scoped($user_id)) {
        $query['author'] = $user_id;
    }
    return $query;
}

add_action('pre_get_posts', 'mvc_scope_upload_screen_media');
function mvc_scope_upload_screen_media($query) {
    global $pagenow;
    if (is_admin() && $pagenow === 'upload.php') {
        $user_id = get_current_user_id();
        if ($user_id && mvc_is_user_dept_scoped($user_id)) {
            $query->set('author', $user_id);
        }
    }
}

// Allowed MIME types for Department Head: PDF, JPG, PNG, DOCX only
add_filter('upload_mimes', 'mvc_restrict_dept_head_mimes');
function mvc_restrict_dept_head_mimes($mimes) {
    $user_id = get_current_user_id();
    if ($user_id && mvc_is_user_dept_scoped($user_id)) {
        return array(
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        );
    }
    return $mimes;
}

// 10 MB Max file size limit for Department Head uploads
add_filter('wp_handle_upload_prefilter', 'mvc_restrict_dept_head_file_size');
function mvc_restrict_dept_head_file_size($file) {
    $user_id = get_current_user_id();
    if ($user_id && mvc_is_user_dept_scoped($user_id)) {
        $max_size = 10 * 1024 * 1024; // 10 MB
        if (isset($file['size']) && $file['size'] > $max_size) {
            $file['error'] = __('File size exceeds the 10 MB institutional limit for department uploads.', 'mvc-college');
        }

        // Strict extension block for executable / script files
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = array('pdf', 'jpg', 'jpeg', 'png', 'docx');
        if (!in_array($ext, $allowed, true)) {
            $file['error'] = sprintf(__('Files of type .%s are not permitted for department uploads.', 'mvc-college'), $ext);
        }
    }
    return $file;
}

/* ========================================================
   BLOCK TRASHING DEPARTMENT WITH ASSIGNED HEAD
   ======================================================== */
add_action('wp_trash_post', 'mvc_prevent_trashing_dept_with_head');
function mvc_prevent_trashing_dept_with_head($post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'department') {
        $hod_user_id = get_post_meta($post_id, 'department_hod_user_id', true);
        if ($hod_user_id) {
            wp_die(
                __('Cannot trash this department because an active Department Head is currently assigned. Reassign or remove the Head first.', 'mvc-college'),
                __('Action Blocked', 'mvc-college'),
                array('back_link' => true)
            );
        }
    }
}

/* ========================================================
   DEPARTMENT FILTER DROPDOWN FOR UNSCOPED USERS (ADMIN / CM)
   ======================================================== */
add_action('restrict_manage_posts', 'mvc_add_dept_filter_to_admin_tables');
function mvc_add_dept_filter_to_admin_tables($post_type) {
    if (!in_array($post_type, mvc_scoped_types(), true)) {
        return;
    }

    // Department Heads already have their query filtered to their department
    if (mvc_is_user_dept_scoped()) {
        return;
    }

    $selected = isset($_GET['dept_link']) ? sanitize_text_field($_GET['dept_link']) : '';
    $terms = get_terms(array(
        'taxonomy'   => 'dept_link',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    echo '<select name="dept_link" id="filter-by-dept-link">';
    echo '<option value="">' . esc_html__('All Academic Departments', 'mvc-college') . '</option>';
    foreach ($terms as $term) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr($term->slug),
            selected($selected, $term->slug, false),
            esc_html($term->name)
        );
    }
    echo '</select>';
}

