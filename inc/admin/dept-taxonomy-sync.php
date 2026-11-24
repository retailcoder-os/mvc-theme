<?php
/**
 * Dynamic Department & Faculty Taxonomy Synchronization Engine
 * 
 * - Eliminates duplicate/confusing taxonomy submenus in WP-Admin.
 * - Under "Departments": All Departments, Add New Department, Faculty Groups (Disciplines).
 * - Under "Faculty": All Faculty, Add New Faculty.
 * - Dynamic 1-to-1 Sync: Department posts automatically sync with dept_link terms.
 * - Dynamic Inheritance: Faculty members dynamically inherit their Faculty Group from their assigned Department.
 * - 100% Dynamic & Configurable • Zero Hardcoded mappings.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Ensure clean, uncluttered admin menus for Departments and Faculty.
 */
add_action('admin_menu', 'mvc_streamline_dept_faculty_menus', 999);
function mvc_streamline_dept_faculty_menus() {
    // Remove duplicate/confusing taxonomy submenus from Faculty menu
    remove_submenu_page('edit.php?post_type=faculty', 'edit-tags.php?taxonomy=faculty-group&amp;post_type=faculty');
    remove_submenu_page('edit.php?post_type=faculty', 'edit-tags.php?taxonomy=dept_link&amp;post_type=faculty');
    remove_submenu_page('edit.php?post_type=faculty', 'edit-tags.php?taxonomy=faculty-group');
    remove_submenu_page('edit.php?post_type=faculty', 'edit-tags.php?taxonomy=dept_link');

    // Remove duplicate "Departments" taxonomy submenu from Departments menu
    remove_submenu_page('edit.php?post_type=department', 'edit-tags.php?taxonomy=dept_link&amp;post_type=department');
    remove_submenu_page('edit.php?post_type=department', 'edit-tags.php?taxonomy=dept_link');
}

/**
 * 2. Dynamic Synchronization: When a Department post is saved, sync with dept_link term.
 */
add_action('save_post_department', 'mvc_sync_department_post_to_taxonomy', 20, 2);
function mvc_sync_department_post_to_taxonomy($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!is_object($post) || $post->post_status === 'trash' || $post->post_status === 'auto-draft') return;

    $dept_slug = $post->post_name;
    if (empty($dept_slug)) {
        $dept_slug = sanitize_title($post->post_title);
    }
    $dept_title = $post->post_title;
    $clean_name = preg_replace('/^Department of\s+/i', '', $dept_title);

    // Check if dept_link term exists by slug
    $existing_term = get_term_by('slug', $dept_slug, 'dept_link');
    if (!$existing_term) {
        $insert = wp_insert_term($clean_name, 'dept_link', array(
            'slug'        => $dept_slug,
            'description' => sprintf(__('Academic Department: %s', 'mvc-college'), $dept_title),
        ));
        if (!is_wp_error($insert)) {
            $term_id = $insert['term_id'];
            update_term_meta($term_id, 'mvc_department_post_id', $post_id);
            update_post_meta($post_id, 'mvc_dept_term_id', $term_id);
        }
    } else {
        $term_id = $existing_term->term_id;
        update_term_meta($term_id, 'mvc_department_post_id', $post_id);
        update_post_meta($post_id, 'mvc_dept_term_id', $term_id);
    }

    // Dynamic cascade: If department's faculty-group changed, update all faculty members under this department
    $fg_terms = wp_get_object_terms($post_id, 'faculty-group', array('fields' => 'ids'));
    if (!empty($fg_terms) && !is_wp_error($fg_terms) && !empty($term_id)) {
        $faculty_posts = get_posts(array(
            'post_type'      => 'faculty',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'dept_link',
                    'field'    => 'term_id',
                    'terms'    => $term_id,
                ),
            ),
        ));
        foreach ($faculty_posts as $f_id) {
            wp_set_object_terms($f_id, $fg_terms, 'faculty-group', false);
        }
    }
}

/**
 * 3. Dynamic Inheritance: When a Faculty scholar is saved, inherit Faculty Group from Department.
 */
add_action('save_post_faculty', 'mvc_inherit_faculty_group_from_dept', 20, 2);
function mvc_inherit_faculty_group_from_dept($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!is_object($post) || $post->post_status === 'trash' || $post->post_status === 'auto-draft') return;

    // Get assigned department term(s)
    $dept_terms = wp_get_object_terms($post_id, 'dept_link');
    if (empty($dept_terms) || is_wp_error($dept_terms)) {
        return;
    }

    $assigned_dept_term = $dept_terms[0];
    
    // Find matching department post dynamically
    $dept_post_id = (int) get_term_meta($assigned_dept_term->term_id, 'mvc_department_post_id', true);
    if (!$dept_post_id) {
        $dept_post = get_page_by_path($assigned_dept_term->slug, OBJECT, 'department');
        if ($dept_post) {
            $dept_post_id = $dept_post->ID;
            update_term_meta($assigned_dept_term->term_id, 'mvc_department_post_id', $dept_post_id);
        }
    }

    if ($dept_post_id) {
        // Query the faculty-group of the department dynamically
        $dept_fg_ids = wp_get_object_terms($dept_post_id, 'faculty-group', array('fields' => 'ids'));
        if (!empty($dept_fg_ids) && !is_wp_error($dept_fg_ids)) {
            // Automatically set faculty-group on faculty scholar
            wp_set_object_terms($post_id, $dept_fg_ids, 'faculty-group', false);
        }
    }
}
