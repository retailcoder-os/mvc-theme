<?php
/**
 * MV College REST API Architecture (mvc/v1)
 * 
 * Secure, granular REST API endpoints for Command Center and My Portal.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', 'mvc_register_all_rest_routes');
function mvc_register_all_rest_routes() {
    $namespace = 'mvc/v1';

    // 1. /me - Profile
    register_rest_route($namespace, '/me', array(
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'mvc_rest_get_me',
            'permission_callback' => function() {
                return current_user_can('mvc_portal_access') || current_user_can('mvc_access_admin');
            },
        ),
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'mvc_rest_update_me',
            'permission_callback' => function() {
                return current_user_can('mvc_portal_access') || current_user_can('mvc_access_admin');
            },
            'args' => array(
                'phone' => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'city' => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'profession' => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'directory_optin' => array(
                    'type'              => 'array',
                    'items'             => array('type' => 'string'),
                ),
            ),
        ),
    ));

    // 2. /search - Command Palette
    register_rest_route($namespace, '/search', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_search',
        'permission_callback' => function() {
            return current_user_can('mvc_access_admin') || current_user_can('manage_options');
        },
        'args' => array(
            'q' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    // 3. /review-queue
    register_rest_route($namespace, '/review-queue', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_review_queue',
        'permission_callback' => function() {
            return current_user_can('mvc_review_content') || current_user_can('manage_options');
        },
    ));

    // 4. /content/{id}/submit
    register_rest_route($namespace, '/content/(?P<id>\d+)/submit', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_content_submit',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('edit_post', $id);
        },
    ));

    // 5. /content/{id}/approve
    register_rest_route($namespace, '/content/(?P<id>\d+)/approve', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_content_approve',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('mvc_review_content') && current_user_can('publish_post', $id);
        },
    ));

    // 6. /content/{id}/reject
    register_rest_route($namespace, '/content/(?P<id>\d+)/reject', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_content_reject',
        'permission_callback' => function() {
            return current_user_can('mvc_review_content');
        },
        'args' => array(
            'reason' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
            ),
        ),
    ));

    // 7. /content/{id}/request-change
    register_rest_route($namespace, '/content/(?P<id>\d+)/request-change', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_content_request_change',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('read_post', $id) && mvc_is_user_dept_scoped();
        },
        'args' => array(
            'notes' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
            ),
        ),
    ));

    // 8. /content/{id}/status (Unpublish / Trash)
    register_rest_route($namespace, '/content/(?P<id>\d+)/status', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_content_status',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('edit_post', $id);
        },
        'args' => array(
            'status' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ),
        ),
    ));

    // 9. /notices - Quick Publish
    register_rest_route($namespace, '/notices', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_quick_notice',
        'permission_callback' => function() {
            return current_user_can('edit_notices');
        },
    ));

    // 10. /grievances - List & Create
    register_rest_route($namespace, '/grievances', array(
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'mvc_rest_get_grievances',
            'permission_callback' => function() {
                return current_user_can('mvc_handle_grievances') || current_user_can('mvc_submit_grievance');
            },
        ),
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'mvc_rest_create_grievance',
            'permission_callback' => function() {
                return current_user_can('mvc_submit_grievance');
            },
        ),
    ));

    // 11. /grievances/{id} - Detail
    register_rest_route($namespace, '/grievances/(?P<id>\d+)', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_grievance_detail',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return mvc_grievance_user_can_view(get_current_user_id(), $id);
        },
    ));

    // 12. /grievances/{id}/status, /assign, /note, /outcome
    register_rest_route($namespace, '/grievances/(?P<id>\d+)/status', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_grievance_status',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('mvc_handle_grievances') && mvc_grievance_user_can_view(get_current_user_id(), $id);
        },
        'args' => array(
            'status' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ),
        ),
    ));

    register_rest_route($namespace, '/grievances/(?P<id>\d+)/assign', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_grievance_assign',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('mvc_handle_grievances') && mvc_grievance_user_can_view(get_current_user_id(), $id);
        },
        'args' => array(
            'assignee_id' => array(
                'required' => true,
                'type'     => 'integer',
            ),
        ),
    ));

    register_rest_route($namespace, '/grievances/(?P<id>\d+)/note', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_grievance_add_note',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('mvc_handle_grievances') && mvc_grievance_user_can_view(get_current_user_id(), $id);
        },
        'args' => array(
            'note' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
            ),
            'visibility' => array(
                'type'              => 'string',
                'default'           => 'public',
                'sanitize_callback' => 'sanitize_key',
            ),
        ),
    ));

    register_rest_route($namespace, '/grievances/(?P<id>\d+)/outcome', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_grievance_outcome',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return current_user_can('mvc_handle_grievances') && mvc_grievance_user_can_view(get_current_user_id(), $id);
        },
        'args' => array(
            'outcome' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
            ),
        ),
    ));

    // 12b. /grievances/{id}/action - Comprehensive Action & Resolution Dispatch
    register_rest_route($namespace, '/grievances/(?P<id>\d+)/action', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_grievance_action',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            return function_exists('mvc_grievance_user_can_manage') ? mvc_grievance_user_can_manage(get_current_user_id(), $id) : current_user_can('mvc_handle_grievances');
        },
    ));

    // 13. /grievances/{id}/reopen
    register_rest_route($namespace, '/grievances/(?P<id>\d+)/reopen', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_grievance_reopen',
        'permission_callback' => function($request) {
            $id = (int) $request['id'];
            $post = get_post($id);
            return $post && (int) $post->post_author === get_current_user_id();
        },
        'args' => array(
            'reason' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
            ),
        ),
    ));

    // 14. /accounts - List & Create
    register_rest_route($namespace, '/accounts', array(
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'mvc_rest_get_accounts',
            'permission_callback' => function() {
                return current_user_can('mvc_manage_accounts') || current_user_can('manage_options');
            },
        ),
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'mvc_rest_create_student',
            'permission_callback' => function() {
                return current_user_can('mvc_manage_accounts') || current_user_can('manage_options');
            },
        ),
    ));

    // 15. /accounts/{id}/approve, /reject, /suspend, /reactivate
    $account_actions = array('approve', 'reject', 'suspend', 'reactivate');
    foreach ($account_actions as $action) {
        register_rest_route($namespace, "/accounts/(?P<id>\d+)/{$action}", array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'mvc_rest_account_action',
            'permission_callback' => function($request) {
                $id = (int) $request['id'];
                return (current_user_can('mvc_manage_accounts') || current_user_can('manage_options')) && mvc_can_manage_user(get_current_user_id(), $id);
            },
        ));
    }

    // 16. /users/{id}/role - Administrator only
    register_rest_route($namespace, '/users/(?P<id>\d+)/role', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_update_user_role',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_roles');
        },
        'args' => array(
            'role' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_key',
            ),
        ),
    ));

    // 17. /departments/{term}/head - Administrator only
    register_rest_route($namespace, '/departments/(?P<term>\d+)/head', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_assign_dept_head',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_roles');
        },
        'args' => array(
            'user_id' => array(
                'required' => true,
                'type'     => 'integer',
            ),
            'replace' => array(
                'type'    => 'boolean',
                'default' => false,
            ),
        ),
    ));

    // 18. /directory - Alumni Directory
    register_rest_route($namespace, '/directory', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_directory',
        'permission_callback' => function() {
            return current_user_can('mvc_view_directory');
        },
        'args' => array(
            'q' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'batch' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    // 19. /reports/{name}
    register_rest_route($namespace, '/reports/(?P<name>[a-zA-Z0-9_-]+)', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_report',
        'permission_callback' => function() {
            return current_user_can('mvc_view_reports');
        },
    ));

    // 20. /audit - Administrator only
    register_rest_route($namespace, '/audit', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_audit',
        'permission_callback' => function() {
            return current_user_can('mvc_view_audit') || current_user_can('manage_options');
        },
    ));

    // 20b. /audit/purge - Administrator only
    register_rest_route($namespace, '/audit/purge', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_purge_audit',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_settings') || current_user_can('manage_options');
        },
    ));

    // 21. /settings - Administrator only
    register_rest_route($namespace, '/settings', array(
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'mvc_rest_get_settings',
            'permission_callback' => function() {
                return current_user_can('mvc_manage_settings') || current_user_can('manage_options');
            },
        ),
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'mvc_rest_update_settings',
            'permission_callback' => function() {
                return current_user_can('mvc_manage_settings') || current_user_can('manage_options');
            },
        ),
    ));

    // 21b. /settings/toggle-update-lock - RetailCoder StoreFront Update Shield Toggle
    register_rest_route($namespace, '/settings/toggle-update-lock', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_toggle_update_lock',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_settings') || current_user_can('manage_options');
        },
    ));

    // 21c. /settings/toggle-maintenance-mode - Public Website Maintenance Mode Switch
    register_rest_route($namespace, '/settings/toggle-maintenance-mode', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_toggle_maintenance_mode',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_settings') || current_user_can('manage_options');
        },
    ));

    // 22. /content-list - Universal Content List for Modern CMS
    register_rest_route($namespace, '/content-list', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_content_list',
        'permission_callback' => function() {
            return current_user_can('mvc_access_admin') || current_user_can('edit_posts') || current_user_can('manage_options');
        },
        'args'                => array(
            'type'   => array('type' => 'string', 'default' => 'notice'),
            'search' => array('type' => 'string', 'default' => ''),
            'paged'  => array('type' => 'integer', 'default' => 1),
            'status' => array('type' => 'string', 'default' => 'any'),
        ),
    ));

    // 23. /notifications - Role-Scoped Institutional Notifications
    register_rest_route($namespace, '/notifications', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_notifications',
        'permission_callback' => function() {
            return current_user_can('mvc_access_admin') || current_user_can('manage_options');
        },
    ));

    // 24. Command Center Universal Aliases
    register_rest_route($namespace, '/review-action', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_handle_review_action',
        'permission_callback' => function() {
            return current_user_can('mvc_review_content') || current_user_can('manage_options');
        },
    ));

    register_rest_route($namespace, '/quick-notice', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_quick_notice',
        'permission_callback' => function() {
            return current_user_can('edit_notices') || current_user_can('manage_options');
        },
    ));

    register_rest_route($namespace, '/account-action', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_handle_account_action',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_accounts') || current_user_can('manage_options');
        },
    ));

    register_rest_route($namespace, '/audit-logs', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'mvc_rest_get_audit',
        'permission_callback' => function() {
            return current_user_can('mvc_view_audit') || current_user_can('manage_options');
        },
    ));

    register_rest_route($namespace, '/settings/update-lock', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_toggle_update_lock',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_settings') || current_user_can('manage_options');
        },
    ));

    register_rest_route($namespace, '/settings/maintenance-mode', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'mvc_rest_toggle_maintenance_mode',
        'permission_callback' => function() {
            return current_user_can('mvc_manage_settings') || current_user_can('manage_options');
        },
    ));
}

function mvc_rest_get_notifications($request) {
    if (!function_exists('mvc_get_role_notifications')) {
        return rest_ensure_response(array());
    }
    $notifs = mvc_get_role_notifications(get_current_user_id());
    return rest_ensure_response($notifs);
}

/* ========================================================
   REST CONTROLLER IMPLEMENTATIONS
   ======================================================== */

// 1. /me
function mvc_rest_get_me($request) {
    $uid  = get_current_user_id();
    $user = get_userdata($uid);

    $dept_id = (int) get_user_meta($uid, 'mvc_dept_term_id', true);
    $dept_term = $dept_id ? get_term($dept_id, 'dept_link') : null;

    $data = array(
        'id'           => $uid,
        'username'     => $user->user_login,
        'display_name' => $user->display_name,
        'email'        => $user->user_email,
        'roles'        => $user->roles,
        'status'       => get_user_meta($uid, 'mvc_account_status', true) ?: 'active',
        'phone'        => get_user_meta($uid, 'mvc_phone', true) ?: '',
        'department'   => $dept_term ? $dept_term->name : '',
        'dept_term_id' => $dept_id,
        'admin_mode'   => get_user_meta($uid, 'mvc_admin_mode', true) ?: 'modern',
    );

    if (in_array('student', $user->roles, true)) {
        $data['reg_no']  = get_user_meta($uid, 'mvc_reg_no', true) ?: '';
        $data['session'] = get_user_meta($uid, 'mvc_session', true) ?: '';
        $data['program'] = get_user_meta($uid, 'mvc_program', true) ?: '';
    }

    if (in_array('alumni_member', $user->roles, true)) {
        $data['batch_year']       = get_user_meta($uid, 'mvc_batch_year', true) ?: '';
        $data['city']             = get_user_meta($uid, 'mvc_city', true) ?: '';
        $data['profession']       = get_user_meta($uid, 'mvc_profession', true) ?: '';
        $data['directory_optin']  = get_user_meta($uid, 'mvc_directory_optin', true) ?: array('name', 'batch');
    }

    return rest_ensure_response($data);
}

function mvc_rest_update_me($request) {
    $uid = get_current_user_id();

    if ($request->has_param('phone')) {
        update_user_meta($uid, 'mvc_phone', sanitize_text_field($request['phone']));
    }
    if ($request->has_param('city')) {
        update_user_meta($uid, 'mvc_city', sanitize_text_field($request['city']));
    }
    if ($request->has_param('profession')) {
        update_user_meta($uid, 'mvc_profession', sanitize_text_field($request['profession']));
    }
    if ($request->has_param('directory_optin') && is_array($request['directory_optin'])) {
        $clean_optin = array_map('sanitize_key', $request['directory_optin']);
        // Strip sensitive fields
        $clean_optin = array_diff($clean_optin, array('email', 'phone'));
        update_user_meta($uid, 'mvc_directory_optin', $clean_optin);
    }

    return mvc_rest_get_me($request);
}

// 2. /search
function mvc_rest_search($request) {
    $query = sanitize_text_field($request['q']);
    if (strlen($query) < 2) {
        return new WP_Error('mvc_short_query', __('Search query must be at least 2 characters.', 'mvc-college'), array('status' => 400));
    }

    $search_types = mvc_scoped_types();
    if (current_user_can('mvc_access_admin') || current_user_can('manage_options') || current_user_can('mvc_review_content')) {
        $search_types = array(
            'notice',
            'press-media',
            'event',
            'faculty',
            'study_material',
            'research',
            'department',
            'slider',
            'leadership',
            'calendar_milestone',
            'calendar_gazette',
            'committee',
            'staff_member',
            'page',
            'post',
        );
    }

    $posts = get_posts(array(
        's'              => $query,
        'post_type'      => $search_types,
        'posts_per_page' => 20,
        'post_status'    => array('publish', 'pending', 'draft'),
    ));

    $results = array();
    foreach ($posts as $p) {
        if (current_user_can('edit_post', $p->ID)) {
            $results[] = array(
                'id'        => $p->ID,
                'title'     => $p->post_title,
                'type'      => $p->post_type,
                'status'    => $p->post_status,
                'edit_url'  => get_edit_post_link($p->ID, 'raw'),
            );
        }
    }

    return rest_ensure_response($results);
}

// 3. /review-queue
function mvc_rest_get_review_queue($request) {
    $page = max(1, (int) ($request['page'] ?? 1));
    $per_page = min(100, max(1, (int) ($request['per_page'] ?? 50)));
    $term_id = (int) ($request['term_id'] ?? 0);

    $data = mvc_get_review_queue(array(
        'page'     => $page,
        'per_page' => $per_page,
        'term_id'  => $term_id,
    ));

    $response = rest_ensure_response($data['items']);
    $response->header('X-WP-Total', $data['total']);
    $response->header('X-WP-TotalPages', $data['total_pages']);
    return $response;
}

// 4. /content/{id}/submit
function mvc_rest_content_submit($request) {
    $id = (int) $request['id'];
    $result = mvc_workflow_submit_for_review($id);
    if (is_wp_error($result)) {
        return new WP_Error($result->get_error_code(), $result->get_error_message(), array('status' => 422));
    }
    return rest_ensure_response(array('success' => true, 'id' => $id, 'status' => 'pending'));
}

// 5. /content/{id}/approve
function mvc_rest_content_approve($request) {
    $id = (int) $request['id'];
    $result = mvc_workflow_approve($id);
    if (is_wp_error($result)) {
        return new WP_Error($result->get_error_code(), $result->get_error_message(), array('status' => 422));
    }
    return rest_ensure_response(array('success' => true, 'id' => $id, 'status' => 'publish'));
}

// 6. /content/{id}/reject
function mvc_rest_content_reject($request) {
    $id = (int) $request['id'];
    $reason = $request['reason'];
    $result = mvc_workflow_reject($id, $reason);
    if (is_wp_error($result)) {
        return new WP_Error($result->get_error_code(), $result->get_error_message(), array('status' => 422));
    }
    return rest_ensure_response(array('success' => true, 'id' => $id, 'status' => 'draft', 'reason' => $reason));
}

// 7. /content/{id}/request-change
function mvc_rest_content_request_change($request) {
    $id = (int) $request['id'];
    $notes = $request['notes'];
    $result = mvc_workflow_request_change($id, $notes);
    if (is_wp_error($result)) {
        return new WP_Error($result->get_error_code(), $result->get_error_message(), array('status' => 422));
    }
    return rest_ensure_response(array('success' => true, 'id' => $id));
}

// 7b. Universal Review Action Dispatcher
function mvc_rest_handle_review_action($request) {
    $post_id = (int) ($request['post_id'] ?? 0);
    $action  = sanitize_key($request['review_action'] ?? '');
    $reason  = sanitize_textarea_field($request['reason'] ?? '');

    if (!$post_id || !get_post($post_id)) {
        return new WP_Error('mvc_invalid_post', __('Target post not found.', 'mvc-college'), array('status' => 404));
    }

    if ($action === 'approve') {
        $sub_req = new WP_REST_Request('POST', "/mvc/v1/content/{$post_id}/approve");
        $sub_req->set_url_params(array('id' => $post_id));
        return mvc_rest_content_approve($sub_req);
    } elseif ($action === 'reject') {
        $sub_req = new WP_REST_Request('POST', "/mvc/v1/content/{$post_id}/reject");
        $sub_req->set_url_params(array('id' => $post_id));
        $sub_req->set_param('reason', $reason ?: __('Revisions required before publication.', 'mvc-college'));
        return mvc_rest_content_reject($sub_req);
    }

    return new WP_Error('mvc_invalid_action', __('Invalid review action requested.', 'mvc-college'), array('status' => 400));
}

// 8. /content/{id}/status
function mvc_rest_content_status($request) {
    $id = (int) $request['id'];
    $target_status = $request['status'];

    if (!in_array($target_status, array('draft', 'trash'), true)) {
        return new WP_Error('mvc_invalid_status', __('Invalid target status specified.', 'mvc-college'), array('status' => 400));
    }

    if ($target_status === 'trash') {
        wp_trash_post($id);
        return rest_ensure_response(array('success' => true, 'id' => $id, 'status' => 'trash'));
    }

    wp_update_post(array('ID' => $id, 'post_status' => 'draft'));
    return rest_ensure_response(array('success' => true, 'id' => $id, 'status' => 'draft'));
}

// 9. /notices - Quick Publish
function mvc_rest_quick_notice($request) {
    $title         = sanitize_text_field(trim($request['title'] ?? ''));
    $memo_no       = sanitize_text_field(trim($request['memo_no'] ?? ''));
    $content       = wp_kses_post(trim($request['content'] ?? ''));
    $audience      = sanitize_text_field(trim($request['audience'] ?? 'Students'));
    $dept_id       = !empty($request['dept_id']) && $request['dept_id'] !== 'all' ? (int) $request['dept_id'] : 0;
    $is_pin        = !empty($request['pinned']) ? 1 : 0;
    $file_url      = esc_url_raw(trim($request['file_url'] ?? ''));
    $attachment_id = !empty($request['attachment_id']) ? (int) $request['attachment_id'] : 0;

    if (empty($title)) {
        return new WP_Error('mvc_missing_title', __('Notice headline is required.', 'mvc-college'), array('status' => 400));
    }

    // Auto-generate official memo number if omitted
    if (empty($memo_no)) {
        $memo_no = 'MVC/NOT/' . current_time('Y') . '/' . wp_rand(101, 999);
    }

    $user_id = get_current_user_id();
    $status = (current_user_can('publish_notices') || current_user_can('manage_options')) ? 'publish' : 'pending';

    $post_id = wp_insert_post(array(
        'post_type'    => 'notice',
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => $status,
        'post_author'  => $user_id,
    ), true);

    if (is_wp_error($post_id)) {
        return new WP_Error($post_id->get_error_code(), $post_id->get_error_message(), array('status' => 422));
    }

    update_post_meta($post_id, 'memo_number', $memo_no);
    update_post_meta($post_id, 'date_picker', current_time('Ymd'));
    update_post_meta($post_id, 'pin_to_homepage', $is_pin);
    update_post_meta($post_id, 'new_badge', 1);

    if (!empty($file_url)) {
        update_post_meta($post_id, '_pdf_url', $file_url);
        update_post_meta($post_id, 'pdf_file', $file_url);
    }
    if ($attachment_id > 0) {
        update_post_meta($post_id, '_pdf_attachment_id', $attachment_id);
        update_post_meta($post_id, 'pdf_file', $attachment_id);
    }

    if (function_exists('update_field')) {
        update_field('memo_number', $memo_no, $post_id);
        update_field('date_picker', current_time('Ymd'), $post_id);
        update_field('pin_to_homepage', $is_pin, $post_id);
        update_field('new_badge', 1, $post_id);
        if ($attachment_id > 0) {
            update_field('pdf_file', $attachment_id, $post_id);
        } elseif (!empty($file_url)) {
            update_field('pdf_file', $file_url, $post_id);
        }
    }

    // Set audience taxonomy
    if (!empty($audience)) {
        wp_set_object_terms($post_id, $audience, 'audience', false);
    }

    // Set department taxonomy
    if ($dept_id > 0) {
        wp_set_object_terms($post_id, array($dept_id), 'dept_link', false);
    } elseif (mvc_is_user_dept_scoped($user_id)) {
        $scoped_dept = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
        if ($scoped_dept) {
            wp_set_object_terms($post_id, array($scoped_dept), 'dept_link', false);
        }
    }

    // Flush any notice caches
    delete_transient('mvc_latest_notices_home');
    delete_transient('mvc_notices_cache');

    return rest_ensure_response(array(
        'success' => true,
        'id'      => $post_id,
        'title'   => $title,
        'status'  => $status,
        'url'     => get_permalink($post_id),
        'message' => __('Notice published successfully.', 'mvc-college')
    ));
}

// 10. /grievances - List & Create
function mvc_rest_get_grievances($request) {
    $uid  = get_current_user_id();
    $user = get_userdata($uid);

    $page     = max(1, (int) ($request['page'] ?? 1));
    $per_page = min(100, max(1, (int) ($request['per_page'] ?? 50)));

    $query_args = array(
        'post_type'      => 'grievance',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    // RBAC: If Student: only own grievances
    if (in_array('student', (array) $user->roles, true) && !user_can($user, 'mvc_handle_grievances')) {
        $query_args['author'] = $uid;
    } elseif (in_array('department_head', (array) $user->roles, true)) {
        // Department Head: strictly scoped to own department
        $dept_term_id = (int) get_user_meta($uid, 'mvc_dept_term_id', true);
        if (!$dept_term_id) {
            $query_args['post__in'] = array(0);
        } else {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'dept_link',
                    'field'    => 'term_id',
                    'terms'    => array($dept_term_id),
                ),
            );
        }
    } else {
        // Staff handler: if Content Manager (not Admin), exclude restricted grievances (A4)
        if (!in_array('administrator', (array) $user->roles, true)) {
            $query_args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key'     => '_mvc_grv_restricted',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key'     => '_mvc_grv_restricted',
                    'value'   => '1',
                    'compare' => '!=',
                ),
            );
        }

        // Optional department filter for Admin
        if (!empty($request['dept_id'])) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'dept_link',
                    'field'    => 'term_id',
                    'terms'    => array((int) $request['dept_id']),
                ),
            );
        }
    }

    // Optional status filter
    if (!empty($request['status']) && $request['status'] !== 'all') {
        $status_tax_query = array(
            'taxonomy' => 'grievance-status',
            'field'    => 'slug',
            'terms'    => array(sanitize_key($request['status'])),
        );
        if (isset($query_args['tax_query'])) {
            $query_args['tax_query'][] = $status_tax_query;
        } else {
            $query_args['tax_query'] = array($status_tax_query);
        }
    }

    // Search query
    if (!empty($request['search'])) {
        $query_args['s'] = sanitize_text_field($request['search']);
    }

    $q = new WP_Query($query_args);
    $items = array();

    if ($q->have_posts()) {
        foreach ($q->posts as $p) {
            $ref = get_post_meta($p->ID, '_mvc_grv_ref', true);
            $cat = get_post_meta($p->ID, '_mvc_grv_category', true);
            $due          = get_post_meta($p->ID, '_mvc_grv_due', true);
            $sub_name     = get_post_meta($p->ID, '_mvc_grv_submitter_name', true) ?: 'Citizen / Scholar';
            $sub_email    = get_post_meta($p->ID, '_mvc_grv_submitter_email', true);
            $sub_phone    = get_post_meta($p->ID, '_mvc_grv_submitter_phone', true);
            $form_title   = get_post_meta($p->ID, '_mvc_grv_form_title', true) ?: 'Collegiate Portal';
            $dept_terms   = wp_get_object_terms($p->ID, 'dept_link');
            $dept_name    = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? $dept_terms[0]->name : 'Central / General';
            $dept_id      = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? (int) $dept_terms[0]->term_id : 0;
            $status_terms = wp_get_object_terms($p->ID, 'grievance-status');
            $status       = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';

            $items[] = array(
                'id'              => $p->ID,
                'ref'             => $ref,
                'subject'         => $p->post_title,
                'category'        => $cat,
                'status'          => $status,
                'created_at'      => get_the_date('d M Y, h:i A', $p->ID),
                'due_date'        => $due ? gmdate('d M Y', strtotime($due)) : '15 Working Days',
                'is_overdue'      => (strtotime($due) < time() && !in_array($status, array('resolved', 'closed'), true)),
                'submitter_name'  => $sub_name,
                'submitter_email' => $sub_email,
                'submitter_phone' => $sub_phone,
                'form_title'      => $form_title,
                'dept_name'       => $dept_name,
                'dept_id'         => $dept_id,
            );
        }
    }

    $response = rest_ensure_response($items);
    $response->header('X-WP-Total', $q->found_posts);
    $response->header('X-WP-TotalPages', $q->max_num_pages);
    return $response;
}

function mvc_rest_create_grievance($request) {
    $uid = get_current_user_id();
    $result = mvc_submit_grievance($uid, array(
        'category'    => $request['category'] ?? 'Academic',
        'subject'     => $request['subject'] ?? '',
        'description' => $request['description'] ?? '',
    ));

    if (is_wp_error($result)) {
        $code = ($result->get_error_code() === 'mvc_submission_limit') ? 429 : 422;
        return new WP_Error($result->get_error_code(), $result->get_error_message(), array('status' => $code));
    }

    return rest_ensure_response($result);
}

// 11. /grievances/{id} - Detail
function mvc_rest_get_grievance_detail($request) {
    $id = (int) $request['id'];
    $uid = get_current_user_id();
    $post = get_post($id);

    if (!$post || $post->post_type !== 'grievance') {
        return new WP_Error('mvc_not_found', __('Grievance record not found.', 'mvc-college'), array('status' => 404));
    }

    $status_terms = wp_get_object_terms($id, 'grievance-status');
    $status = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';

    $timeline = mvc_get_grievance_timeline($id, $uid);
    $attachments = get_post_meta($id, '_mvc_grv_attachments', true) ?: array();
    $clean_attachments = array();

    foreach ($attachments as $att_id => $att) {
        $download_url = wp_nonce_url(
            admin_url('admin-post.php?action=mvc_download&file=' . $att_id . '&grv=' . $id),
            'mvc_download_' . $att_id
        );
        $clean_attachments[] = array(
            'id'       => $att_id,
            'filename' => $att['orig_name'],
            'size'     => size_format($att['size']),
            'url'      => $download_url,
        );
    }

    $dept_terms  = wp_get_object_terms($id, 'dept_link');
    $dept_name   = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? $dept_terms[0]->name : 'Central / Unassigned';
    $dept_id     = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? (int) $dept_terms[0]->term_id : 0;
    $fields_json = get_post_meta($id, '_mvc_grv_fields_data', true);
    $fields_data = !empty($fields_json) ? json_decode($fields_json, true) : array();

    $data = array(
        'id'              => $post->ID,
        'ref'             => get_post_meta($id, '_mvc_grv_ref', true) ?: ('MVC-CASE-' . $id),
        'subject'         => $post->post_title,
        'description'     => $post->post_content,
        'category'        => get_post_meta($id, '_mvc_grv_category', true) ?: 'General',
        'status'          => $status,
        'outcome'         => get_post_meta($id, '_mvc_grv_outcome', true) ?: '',
        'due_date'        => get_post_meta($id, '_mvc_grv_due', true),
        'created_at'      => get_the_date('d M Y, h:i A', $post->ID),
        'submitter_name'  => get_post_meta($id, '_mvc_grv_submitter_name', true) ?: 'Citizen / Scholar',
        'submitter_email' => get_post_meta($id, '_mvc_grv_submitter_email', true),
        'submitter_phone' => get_post_meta($id, '_mvc_grv_submitter_phone', true),
        'form_title'      => get_post_meta($id, '_mvc_grv_form_title', true) ?: 'Collegiate Portal',
        'dept_name'       => $dept_name,
        'dept_id'         => $dept_id,
        'fields_data'     => $fields_data,
        'timeline'        => $timeline,
        'attachments'     => $clean_attachments,
    );

    return rest_ensure_response($data);
}

// 12. /grievances/{id}/status, /assign, /note, /outcome
function mvc_rest_grievance_status($request) {
    $id = (int) $request['id'];
    $target_status = sanitize_key($request['status']);

    $allowed = array('logged', 'in-review', 'resolved', 'closed');
    if (!in_array($target_status, $allowed, true)) {
        return new WP_Error('mvc_invalid_status', __('Invalid status value.', 'mvc-college'), array('status' => 400));
    }

    wp_set_object_terms($id, array($target_status), 'grievance-status', false);

    $uid = get_current_user_id();
    mvc_add_grievance_event($id, $uid, 'status', 'public', sprintf('Status updated to: %s', strtoupper($target_status)));

    $ref = get_post_meta($id, '_mvc_grv_ref', true);
    $post = get_post($id);
    mvc_audit('grievance_status', 'grievance', $id, array('ref' => $ref, 'status' => $target_status));

    do_action('mvc_grievance_status_changed', $id, $ref, $target_status, $post->post_author);

    return rest_ensure_response(array('success' => true, 'id' => $id, 'status' => $target_status));
}

function mvc_rest_grievance_assign($request) {
    $id = (int) $request['id'];
    $assignee_id = (int) $request['assignee_id'];

    $assignee = get_userdata($assignee_id);
    if (!$assignee || !user_can($assignee, 'mvc_handle_grievances')) {
        return new WP_Error('mvc_invalid_assignee', __('Assignee must have grievance handling privileges.', 'mvc-college'), array('status' => 400));
    }

    update_post_meta($id, '_mvc_grv_assignee', $assignee_id);
    wp_set_object_terms($id, array('in-review'), 'grievance-status', false);

    $uid = get_current_user_id();
    mvc_add_grievance_event($id, $uid, 'assigned', 'public', sprintf('Grievance assigned to %s for investigation.', $assignee->display_name));

    return rest_ensure_response(array('success' => true, 'assignee' => $assignee->display_name));
}

function mvc_rest_grievance_add_note($request) {
    $id   = (int) $request['id'];
    $note = $request['note'];
    $vis  = ($request['visibility'] === 'internal') ? 'internal' : 'public';
    $uid  = get_current_user_id();

    mvc_add_grievance_event($id, $uid, 'note', $vis, $note);
    return rest_ensure_response(array('success' => true));
}

function mvc_rest_grievance_outcome($request) {
    $id      = (int) $request['id'];
    $outcome = trim($request['outcome']);

    if (strlen($outcome) < 20) {
        return new WP_Error('mvc_invalid_outcome', __('A detailed written resolution outcome of at least 20 characters is required.', 'mvc-college'), array('status' => 422));
    }

    update_post_meta($id, '_mvc_grv_outcome', $outcome);
    wp_set_object_terms($id, array('resolved'), 'grievance-status', false);

    $uid = get_current_user_id();
    mvc_add_grievance_event($id, $uid, 'outcome', 'public', $outcome);

    $ref = get_post_meta($id, '_mvc_grv_ref', true);
    $post = get_post($id);
    do_action('mvc_grievance_status_changed', $id, $ref, 'resolved', $post->post_author);

    return rest_ensure_response(array('success' => true, 'status' => 'resolved'));
}

// 12b. /grievances/{id}/action - Comprehensive Action & Resolution Dispatch
function mvc_rest_grievance_action($request) {
    $id = (int) $request['id'];
    $uid = get_current_user_id();

    if (function_exists('mvc_grievance_user_can_manage') && !mvc_grievance_user_can_manage($uid, $id)) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to manage this case dossier.', 'mvc-college'), array('status' => 403));
    }

    $post = get_post($id);
    if (!$post || $post->post_type !== 'grievance') {
        return new WP_Error('mvc_not_found', __('Case record not found.', 'mvc-college'), array('status' => 404));
    }

    $ref_no      = get_post_meta($id, '_mvc_grv_ref', true) ?: ('MVC-CASE-' . $id);
    $sub_name    = get_post_meta($id, '_mvc_grv_submitter_name', true) ?: 'Scholar / Citizen';
    $sub_email   = get_post_meta($id, '_mvc_grv_submitter_email', true);
    $form_title  = get_post_meta($id, '_mvc_grv_form_title', true) ?: 'Collegiate Portal';
    $target_mail = !empty($sub_email) ? $sub_email : ($post->post_author ? get_userdata($post->post_author)->user_email : '');

    $notification_notes = array();
    $has_public_update  = false;

    // 1. Process Status Update
    if (!empty($request['status'])) {
        $target_status = sanitize_key($request['status']);
        $current_terms = wp_get_object_terms($id, 'grievance-status');
        $old_status    = (!empty($current_terms) && !is_wp_error($current_terms)) ? $current_terms[0]->slug : '';

        if ($target_status !== $old_status) {
            wp_set_object_terms($id, array($target_status), 'grievance-status', false);
            mvc_add_grievance_event(
                $id,
                $uid,
                'status',
                'public',
                sprintf('Official status updated from %s to %s.', strtoupper($old_status ?: 'LOGGED'), strtoupper($target_status))
            );
            $has_public_update = true;
            $notification_notes[] = sprintf('Status updated to: <strong>%s</strong>', strtoupper($target_status));
        }
    }

    // 2. Process Department Routing
    if (isset($request['dept_id'])) {
        $dept_term_id = (int) $request['dept_id'];
        $is_dept_scoped = function_exists('mvc_is_user_dept_scoped') && mvc_is_user_dept_scoped(wp_get_current_user());

        if (!$is_dept_scoped) {
            if ($dept_term_id > 0) {
                wp_set_object_terms($id, array($dept_term_id), 'dept_link', false);
                $dterm = get_term($dept_term_id, 'dept_link');
                if ($dterm && !is_wp_error($dterm)) {
                    update_post_meta($id, '_mvc_grv_department', $dterm->name);
                    mvc_add_grievance_event(
                        $id,
                        $uid,
                        'assigned',
                        'public',
                        sprintf('Case officially assigned to the Department of %s.', $dterm->name)
                    );
                    $notification_notes[] = sprintf('Case routed to: <strong>Department of %s</strong>', esc_html($dterm->name));
                    $has_public_update = true;
                }
            } else {
                wp_set_object_terms($id, array(), 'dept_link', false);
                delete_post_meta($id, '_mvc_grv_department');
            }
        }
    }

    // 3. Process Note
    if (!empty($request['note'])) {
        $note_text = sanitize_textarea_field(trim($request['note']));
        $vis       = (!empty($request['visibility']) && $request['visibility'] === 'internal') ? 'internal' : 'public';

        if (!empty($note_text)) {
            mvc_add_grievance_event($id, $uid, 'note', $vis, $note_text);
            if ($vis === 'public') {
                $has_public_update = true;
                $notification_notes[] = '<strong>Official Committee Remarks:</strong><br>' . nl2br(esc_html($note_text));
            }
        }
    }

    // 4. Process Certified Outcome
    if (!empty($request['outcome'])) {
        $outcome_text = sanitize_textarea_field(trim($request['outcome']));
        if (strlen($outcome_text) >= 10) {
            update_post_meta($id, '_mvc_grv_outcome', $outcome_text);
            wp_set_object_terms($id, array('resolved'), 'grievance-status', false);

            mvc_add_grievance_event(
                $id,
                $uid,
                'outcome',
                'public',
                sprintf("Certified Redressal Outcome recorded:\n%s", $outcome_text)
            );
            $has_public_update = true;
            $notification_notes[] = '<strong>🏛️ Certified Resolution Outcome:</strong><br>' . nl2br(esc_html($outcome_text));
        }
    }

    // 5. Send Email to Submitter via Zoho SMTP Fallback
    if ($has_public_update && !empty($target_mail) && is_email($target_mail)) {
        $tracking_url = home_url('/track/?ref=' . rawurlencode($ref_no) . '&email=' . rawurlencode($target_mail));
        $admin_email  = get_option('admin_email') ?: 'mvcollegebuxar@gmail.com';
        $college_name = function_exists('mvc_get_global_setting') ? mvc_get_global_setting('college_name', 'Maharshi Vishwamitra Mahavidyalaya, Buxar') : 'Maharshi Vishwamitra Mahavidyalaya, Buxar';

        $subject = sprintf('[%s] Official Case Update — Ref: %s', 'M.V. College, Buxar', $ref_no);
        $notes_html = implode('<hr style="border:0; border-top:1px dashed #CBD5E1; margin:14px 0;">', $notification_notes);

        $body_html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . esc_html($subject) . '</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 24px; color: #1E293B; line-height: 1.6; }
                .card { max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,33,71,0.08); border: 1px solid #E2E8F0; }
                .header { background: linear-gradient(135deg, #002147 0%, #800000 100%); padding: 26px 24px; text-align: center; color: #ffffff; }
                .header h1 { margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 0.5px; }
                .header p { margin: 6px 0 0; font-size: 13px; color: #D4AF37; }
                .content { padding: 30px 26px; }
                .ref-badge { display: inline-block; background: #EFF6FF; color: #1E40AF; font-weight: 800; font-size: 13px; padding: 6px 14px; border-radius: 6px; border: 1px solid #BFDBFE; font-family: monospace; }
                .notes-card { background: #FAF9F6; border-left: 4px solid #D4AF37; padding: 18px 20px; border-radius: 0 8px 8px 0; font-size: 14px; color: #1E293B; margin: 20px 0; line-height: 1.6; }
                .btn { display: inline-block; background: linear-gradient(135deg, #002147 0%, #800000 100%); color: #ffffff !important; font-weight: 800; font-size: 14px; text-decoration: none; padding: 12px 28px; border-radius: 8px; margin: 20px 0 10px; }
                .footer { padding: 18px 24px; background: #F8FAFC; text-align: center; font-size: 12px; color: #94A3B8; border-top: 1px solid #E2E8F0; }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="header">
                    <h1>🏛️ ' . esc_html($college_name) . '</h1>
                    <p>Official Grievance Redressal &amp; Inquiry Operations Cell</p>
                </div>
                <div class="content">
                    <div style="margin-bottom:14px;">
                        <span class="ref-badge">Case Reference: ' . esc_html($ref_no) . '</span>
                    </div>
                    <h2 style="margin:0 0 12px 0; color:#002147; font-size:18px;">Official Status &amp; Investigation Update</h2>
                    <p style="margin:0 0 14px 0; font-size:14px; color:#334155;">
                        Dear <strong>' . esc_html($sub_name) . '</strong>,
                    </p>
                    <p style="margin:0 0 16px 0; font-size:14px; color:#475569;">
                        An official action or resolution update has been recorded regarding your submission to the <strong>' . esc_html($form_title) . '</strong> by the institutional authority committee.
                    </p>

                    <div class="notes-card">
                        ' . $notes_html . '
                    </div>

                    <div style="text-align:center;">
                        <a href="' . esc_url($tracking_url) . '" class="btn">
                            🔎 View Live Dossier &amp; Full Timeline &rarr;
                        </a>
                    </div>

                    <p style="margin:20px 0 0 0; font-size:13px; color:#64748B;">
                        You can reply directly to this email or visit your online tracking portal above to review all historical milestones and provide additional information to the committee.
                    </p>
                </div>
                <div class="footer">
                    <strong>Maharshi Vishwamitra Mahavidyalaya, Buxar</strong><br>
                    Secretariat of Grievance Redressal &amp; Student Inquiries &bull; Helpline: 06183-796317<br>
                    Official Admin Contact: ' . esc_html($admin_email) . '
                </div>
            </div>
        </body>
        </html>';

        if (function_exists('mvc_dispatch_smtp_email')) {
            mvc_dispatch_smtp_email($target_mail, $subject, $body_html, array(), $admin_email);
        }
    }

    return rest_ensure_response(array(
        'success' => true,
        'id'      => $id,
        'message' => __('Case dossier updated and notification dispatched successfully.', 'mvc-college')
    ));
}

// 13. /grievances/{id}/reopen
function mvc_rest_grievance_reopen($request) {
    $id     = (int) $request['id'];
    $reason = trim($request['reason']);

    // Check if resolved and within 15 days
    $events = mvc_get_grievance_timeline($id, get_current_user_id());
    $resolved_time = 0;
    foreach ($events as $ev) {
        if ($ev['event_type'] === 'outcome') {
            $resolved_time = strtotime($ev['created_at']);
        }
    }

    if (!$resolved_time || (time() - $resolved_time > 15 * DAY_IN_SECONDS)) {
        return new WP_Error('mvc_reopen_expired', __('Grievances can only be reopened within 15 days of resolution.', 'mvc-college'), array('status' => 422));
    }

    wp_set_object_terms($id, array('in-review'), 'grievance-status', false);

    $uid = get_current_user_id();
    mvc_add_grievance_event($id, $uid, 'reopened', 'public', sprintf('Grievance reopened by scholar: %s', $reason));

    return rest_ensure_response(array('success' => true, 'status' => 'in-review'));
}

// 14. /accounts - List & Create
function mvc_rest_get_accounts($request) {
    $role   = sanitize_key($request['role'] ?? '');
    $status = sanitize_key($request['status'] ?? '');
    $search = sanitize_text_field($request['search'] ?? '');

    $args = array(
        'number' => min(50, max(1, (int) ($request['per_page'] ?? 20))),
        'paged'  => max(1, (int) ($request['page'] ?? 1)),
    );

    if ($role) {
        $args['role'] = $role;
    } else {
        $args['role__in'] = array('student', 'alumni_member', 'department_head', 'content_manager');
    }

    if ($status) {
        $args['meta_key']   = 'mvc_account_status';
        $args['meta_value'] = $status;
    }

    if ($search) {
        $args['search'] = '*' . $search . '*';
    }

    $uq = new WP_User_Query($args);
    $items = array();

    foreach ($uq->get_results() as $u) {
        $items[] = array(
            'id'           => $u->ID,
            'username'     => $u->user_login,
            'name'         => $u->display_name,
            'email'        => $u->user_email,
            'role'         => reset($u->roles),
            'status'       => get_user_meta($u->ID, 'mvc_account_status', true) ?: 'active',
            'registered'   => $u->user_registered,
        );
    }

    $response = rest_ensure_response($items);
    $response->header('X-WP-Total', $uq->get_total());
    return $response;
}

function mvc_rest_create_student($request) {
    $data = array(
        'name'            => $request['name'],
        'email'           => $request['email'],
        'reg_no'          => $request['reg_no'],
        'session'         => $request['session'],
        'program'         => $request['program'],
        'dept_term_id'    => (int) $request['dept_term_id'],
    );

    $user_id = mvc_create_student_account($data);
    if (is_wp_error($user_id)) {
        return new WP_Error($user_id->get_error_code(), $user_id->get_error_message(), array('status' => 422));
    }

    return rest_ensure_response(array('success' => true, 'id' => $user_id));
}

// 15. /accounts/{id}/action
function mvc_rest_account_action($request) {
    $id = (int) $request['id'];
    $route = $request->get_route();

    if (strpos($route, '/approve') !== false) {
        $res = mvc_approve_account($id);
    } elseif (strpos($route, '/reject') !== false) {
        $res = mvc_reject_account($id);
    } elseif (strpos($route, '/suspend') !== false) {
        $res = mvc_suspend_account($id);
    } elseif (strpos($route, '/reactivate') !== false) {
        $res = mvc_reactivate_account($id);
    } else {
        return new WP_Error('mvc_invalid_action', 'Unknown action', array('status' => 400));
    }

    if (is_wp_error($res)) {
        return new WP_Error($res->get_error_code(), $res->get_error_message(), array('status' => 422));
    }

    return rest_ensure_response(array('success' => true, 'id' => $id));
}

// 15b. Universal Account Action Dispatcher
function mvc_rest_handle_account_action($request) {
    $user_id = (int) ($request['user_id'] ?? 0);
    $action  = sanitize_key($request['account_action'] ?? '');

    if (!$user_id || !get_userdata($user_id)) {
        return new WP_Error('mvc_invalid_user', __('Target user not found.', 'mvc-college'), array('status' => 404));
    }

    if ($action === 'delete') {
        if (!current_user_can('delete_users') && !current_user_can('manage_options')) {
            return new WP_Error('mvc_forbidden', __('You do not have permission to delete users.', 'mvc-college'), array('status' => 403));
        }
        require_once(ABSPATH . 'wp-admin/includes/user.php');
        $deleted = wp_delete_user($user_id);
        if ($deleted) {
            return rest_ensure_response(array('success' => true, 'message' => __('User deleted successfully.', 'mvc-college')));
        }
        return new WP_Error('mvc_delete_failed', __('Failed to delete user.', 'mvc-college'), array('status' => 500));
    }

    $sub_req = new WP_REST_Request('POST', "/mvc/v1/accounts/{$user_id}/{$action}");
    $sub_req->set_url_params(array('id' => $user_id));
    $route_map = array(
        'approve'    => "/mvc/v1/accounts/{$user_id}/approve",
        'reject'     => "/mvc/v1/accounts/{$user_id}/reject",
        'suspend'    => "/mvc/v1/accounts/{$user_id}/suspend",
        'reactivate' => "/mvc/v1/accounts/{$user_id}/reactivate",
    );
    if (isset($route_map[$action])) {
        $sub_req->set_route($route_map[$action]);
        return mvc_rest_account_action($sub_req);
    }

    return new WP_Error('mvc_invalid_action', __('Invalid account action requested.', 'mvc-college'), array('status' => 400));
}

// 16. /users/{id}/role - Administrator only
function mvc_rest_update_user_role($request) {
    $id = (int) $request['id'];
    $role = $request['role'];

    $valid = array('administrator', 'content_manager', 'department_head', 'student', 'alumni_member');
    if (!in_array($role, $valid, true)) {
        return new WP_Error('mvc_invalid_role', __('Invalid institutional role.', 'mvc-college'), array('status' => 400));
    }

    $user = get_userdata($id);
    if (!$user) {
        return new WP_Error('mvc_user_not_found', __('User does not exist.', 'mvc-college'), array('status' => 404));
    }

    $old_role = reset($user->roles);
    $user->set_role($role);

    mvc_audit('role_changed', 'user', $id, array('old_role' => $old_role, 'new_role' => $role));
    return rest_ensure_response(array('success' => true, 'id' => $id, 'role' => $role));
}

// 17. /departments/{term}/head - Administrator only
function mvc_rest_assign_dept_head($request) {
    $term_id = (int) $request['term'];
    $user_id = (int) $request['user_id'];
    $replace = !empty($request['replace']);

    if ($replace) {
        $res = mvc_replace_dept_head($user_id, $term_id);
    } else {
        $res = mvc_assign_dept_head($user_id, $term_id);
    }

    if (is_wp_error($res)) {
        $status = ($res->get_error_code() === 'mvc_dept_head_conflict') ? 409 : 422;
        return new WP_Error($res->get_error_code(), $res->get_error_message(), array('status' => $status));
    }

    return rest_ensure_response(array('success' => true, 'term_id' => $term_id, 'user_id' => $user_id));
}

// 18. /directory - Alumni Directory
function mvc_rest_get_directory($request) {
    $search = sanitize_text_field($request['q'] ?? '');
    $batch  = sanitize_text_field($request['batch'] ?? '');
    $page   = max(1, (int) ($request['page'] ?? 1));

    if (!empty($search) && strlen($search) < 2) {
        return new WP_Error('mvc_short_query', __('Directory search query must be at least 2 characters.', 'mvc-college'), array('status' => 400));
    }

    $args = array(
        'role'       => 'alumni_member',
        'number'     => 24,
        'paged'      => $page,
        'meta_query' => array(
            array(
                'key'     => 'mvc_account_status',
                'value'   => 'active',
                'compare' => '=',
            ),
        ),
    );

    if ($batch) {
        $args['meta_query'][] = array(
            'key'     => 'mvc_batch_year',
            'value'   => $batch,
            'compare' => '=',
        );
    }

    if ($search) {
        $args['search'] = '*' . $search . '*';
    }

    $uq = new WP_User_Query($args);
    $items = array();

    foreach ($uq->get_results() as $u) {
        $optin = get_user_meta($u->ID, 'mvc_directory_optin', true) ?: array();
        if (empty($optin)) {
            continue; // Not opted into directory
        }

        $row = array('id' => $u->ID);
        if (in_array('name', $optin, true)) {
            $row['name'] = $u->display_name;
        }
        if (in_array('batch', $optin, true)) {
            $row['batch_year'] = get_user_meta($u->ID, 'mvc_batch_year', true);
        }
        if (in_array('department', $optin, true)) {
            $dt_id = (int) get_user_meta($u->ID, 'mvc_dept_term_id', true);
            $dt = $dt_id ? get_term($dt_id, 'dept_link') : null;
            $row['department'] = $dt ? $dt->name : '';
        }
        if (in_array('city', $optin, true)) {
            $row['city'] = get_user_meta($u->ID, 'mvc_city', true);
        }
        if (in_array('profession', $optin, true)) {
            $row['profession'] = get_user_meta($u->ID, 'mvc_profession', true);
        }

        // NEVER expose email or phone
        $items[] = $row;
    }

    $response = rest_ensure_response($items);
    $response->header('X-WP-Total', $uq->get_total());
    return $response;
}

// 19. /reports/{name}
function mvc_rest_get_report($request) {
    $name = sanitize_key($request['name']);
    $data = array();

    if ($name === 'publishing') {
        // 14-day publishing trend
        global $wpdb;
        $trend = $wpdb->get_results(
            "SELECT DATE(post_date) as pub_date, COUNT(*) as count 
             FROM {$wpdb->posts} 
             WHERE post_status = 'publish' AND post_type IN ('notice', 'event') AND post_date >= DATE_SUB(NOW(), INTERVAL 14 DAY)
             GROUP BY DATE(post_date) ORDER BY pub_date ASC",
            ARRAY_A
        );
        $data = array('trend' => $trend ?: array());
    } elseif ($name === 'content-health') {
        // Missing PDFs, missing dates
        global $wpdb;
        $notices_without_memo = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts} p
             LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'memo_number'
             WHERE p.post_type = 'notice' AND p.post_status = 'publish' AND (pm.meta_value IS NULL OR pm.meta_value = '')"
        );
        $data = array('notices_missing_memo' => (int) $notices_without_memo);
    }

    return rest_ensure_response($data);
}

// 20. /audit - Administrator only
function mvc_rest_get_audit($request) {
    $args = array(
        'page'         => max(1, (int) ($request['page'] ?? 1)),
        'per_page'     => min(100, max(1, (int) ($request['per_page'] ?? 50))),
        'user_id'      => (int) ($request['user_id'] ?? 0),
        'action'       => sanitize_key($request['action'] ?? ''),
        'action_group' => sanitize_key($request['action_group'] ?? ''),
        'object_type'  => sanitize_key($request['object_type'] ?? ''),
        'search'       => sanitize_text_field($request['search'] ?? ''),
    );

    $data = mvc_get_audit_logs($args);

    $response = rest_ensure_response($data['items']);
    $response->header('X-WP-Total', $data['total']);
    $response->header('X-WP-TotalPages', $data['total_pages']);
    return $response;
}

// 20b. /audit/purge - Administrator only
function mvc_rest_purge_audit($request) {
    $deleted = mvc_audit_retention_cleanup();
    return rest_ensure_response(array(
        'success'         => true,
        'deleted_records' => (int) $deleted,
        'retention_days'  => 30,
        'message'         => sprintf('Retention policy enforced: %d records older than 30 days purged.', (int) $deleted),
    ));
}

// 21. /settings - Administrator only
function mvc_rest_get_settings($request) {
    $settings = get_option('mvc_settings', array(
        'sla_days'             => 15,
        'audit_retention_days' => 30,
        'lock_updates'         => true,
        'reunion_form_id'      => 0,
        'portal_page_id'       => 0,
    ));
    // Ensure lock_updates is populated from state
    if (!isset($settings['lock_updates'])) {
        $settings['lock_updates'] = function_exists('mvc_is_update_locked') ? mvc_is_update_locked() : true;
    }
    return rest_ensure_response($settings);
}

function mvc_rest_update_settings($request) {
    $settings = get_option('mvc_settings', array());

    if ($request->has_param('sla_days')) {
        $settings['sla_days'] = max(1, (int) $request['sla_days']);
    }
    if ($request->has_param('audit_retention_days')) {
        $settings['audit_retention_days'] = max(1, (int) $request['audit_retention_days']);
    }
    if ($request->has_param('lock_updates')) {
        $prev_locked = isset($settings['lock_updates']) ? (bool) $settings['lock_updates'] : true;
        $settings['lock_updates'] = (bool) $request['lock_updates'];
        if ($prev_locked !== $settings['lock_updates']) {
            mvc_audit(
                $settings['lock_updates'] ? 'update_protection_locked' : 'update_protection_unlocked',
                'settings',
                0,
                array(
                    'locked' => $settings['lock_updates'],
                    'actor'  => wp_get_current_user()->user_login,
                )
            );
        }
    }
    if ($request->has_param('reunion_form_id')) {
        $settings['reunion_form_id'] = (int) $request['reunion_form_id'];
    }

    update_option('mvc_settings', $settings);
    mvc_audit('settings_changed', 'settings', 0, $settings);

    return rest_ensure_response($settings);
}

// 21b. /settings/toggle-update-lock - RetailCoder StoreFront Update Shield Toggle
function mvc_rest_toggle_update_lock($request) {
    $settings = get_option('mvc_settings', array());
    $current = isset($settings['lock_updates']) ? (bool) $settings['lock_updates'] : true;

    if ($request->has_param('locked')) {
        $new_state = (bool) $request['locked'];
    } else {
        $new_state = !$current;
    }

    $settings['lock_updates'] = $new_state;
    update_option('mvc_settings', $settings);

    mvc_audit(
        $new_state ? 'update_protection_locked' : 'update_protection_unlocked',
        'settings',
        0,
        array(
            'locked'         => $new_state,
            'previous_state' => $current,
            'actor'          => wp_get_current_user()->user_login,
        )
    );

    return rest_ensure_response(array(
        'success' => true,
        'locked'  => $new_state,
        'message' => $new_state
            ? 'RetailCoder StoreFront [RC:StoreFront] Shield active: Theme & system updates are safely locked.'
            : 'Maintenance Mode: Theme & system updates are temporarily unlocked.',
    ));
}

// 21c. /settings/toggle-maintenance-mode Controller
function mvc_rest_toggle_maintenance_mode($request) {
    if (!function_exists('mvc_is_maintenance_mode_active')) {
        return new WP_Error('unavailable', 'Maintenance mode module not loaded.', array('status' => 500));
    }

    $current = mvc_is_maintenance_mode_active();
    if ($request->has_param('enabled')) {
        $new_state = (bool) $request['enabled'];
    } else {
        $new_state = !$current;
    }

    mvc_set_maintenance_mode($new_state);

    return rest_ensure_response(array(
        'success' => true,
        'enabled' => $new_state,
        'message' => $new_state
            ? 'Maintenance Mode ENABLED: Only the homepage is accessible to the public, and the maintenance banner is active.'
            : 'Maintenance Mode DISABLED: The website is fully open and accessible to the public.',
    ));
}

// 22. /content-list Controller
function mvc_rest_get_content_list($request) {
    $post_type = sanitize_key($request->get_param('type') ?: 'notice');
    $search    = sanitize_text_field($request->get_param('search') ?: '');
    $paged     = max(1, (int) $request->get_param('paged'));
    $status    = sanitize_key($request->get_param('status') ?: 'any');

    // Canonicalize press_media alias to registered CPT press-media
    if ($post_type === 'press_media') {
        $post_type = 'press-media';
    }

    $labels_map = array(
        'notice'             => 'Institutional Notices',
        'slider'             => 'Hero Slider Banners',
        'leadership'         => 'Leadership Profiles',
        'calendar_milestone' => 'Academic Milestones',
        'calendar_gazette'   => 'Academic Gazettes',
        'faculty'            => 'Faculty Directory',
        'department'         => 'Academic Departments',
        'event'              => 'Campus Events & Seminars',
        'press-media'        => 'Press Media & Clippings',
        'press_media'        => 'Press Media & Clippings',
        'study_material'     => 'Study Materials & Notes',
        'research'           => 'Researches & Publications',
        'committee'          => 'Committees & Cells',
        'staff_member'       => 'Non-Teaching Staff',
        'page'               => 'Institutional Pages',
        'post'               => 'Articles & Posts',
    );

    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => 20,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    if ($status && $status !== 'any') {
        $args['post_status'] = $status;
    } else {
        $args['post_status'] = array('publish', 'draft', 'pending');
    }

    if (!empty($search)) {
        $args['s'] = $search;
    }

    // Role-based scope for department heads
    if (mvc_is_user_dept_scoped()) {
        $dept_term_id = (int) get_user_meta(get_current_user_id(), 'mvc_dept_term_id', true);
        if ($dept_term_id && in_array($post_type, array('notice', 'event', 'faculty', 'study_material', 'research', 'department'), true)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'dept_link',
                    'field'    => 'term_id',
                    'terms'    => $dept_term_id,
                ),
            );
        }
    }

    $q = new WP_Query($args);
    $items = array();

    foreach ($q->posts as $p) {
        $memo_no = get_post_meta($p->ID, 'memo_no', true) ?: get_post_meta($p->ID, '_mvc_memo_no', true);
        $audience = get_post_meta($p->ID, 'audience', true) ?: 'General';
        
        $dept_terms = wp_get_object_terms($p->ID, 'dept_link');
        $dept_name = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? $dept_terms[0]->name : 'Institutional';

        $author = get_userdata($p->post_author);
        $author_name = $author ? $author->display_name : 'Staff';

        // Check for attached PDF or media
        $has_pdf = false;
        $pdf_id = get_post_meta($p->ID, 'certified_pdf', true) ?: get_post_meta($p->ID, '_mvc_pdf_id', true);
        if ($pdf_id) {
            $has_pdf = true;
        }

        // Extract secondary metadata depending on CPT
        $subtitle = '';
        if ($p->post_type === 'faculty' || $p->post_type === 'leadership' || $p->post_type === 'staff_member') {
            $subtitle = get_post_meta($p->ID, 'designation', true) ?: get_post_meta($p->ID, 'faculty_designation', true);
        } elseif ($p->post_type === 'event') {
            $subtitle = get_post_meta($p->ID, 'event_date', true) ?: get_post_meta($p->ID, 'start_date', true);
        } elseif ($p->post_type === 'calendar_milestone') {
            $subtitle = get_post_meta($p->ID, 'milestone_year', true) ?: get_post_meta($p->ID, 'year', true);
        } elseif ($p->post_type === 'press-media' || $p->post_type === 'press_media') {
            $raw_d = get_post_meta($p->ID, 'date', true) ?: get_post_meta($p->ID, 'release_date', true);
            $pub = get_post_meta($p->ID, 'press_publication', true) ?: 'Regional Daily Press';
            if (!empty($raw_d)) {
                $subtitle = '📰 ' . $pub . ' • ' . date_i18n('d M Y', strtotime($raw_d));
            } else {
                $subtitle = '📰 ' . $pub;
            }
        }

        $items[] = array(
            'id'          => $p->ID,
            'title'       => $p->post_title ?: '(Untitled)',
            'post_type'   => $p->post_type,
            'status'      => $p->post_status,
            'date'        => get_the_date('M d, Y', $p->ID),
            'memo_no'     => $memo_no ?: '',
            'subtitle'    => $subtitle,
            'audience'    => $audience,
            'department'  => $dept_name,
            'author'      => $author_name,
            'has_pdf'     => $has_pdf,
            'view_url'    => get_permalink($p->ID),
            'edit_url'    => get_edit_post_link($p->ID, 'raw'),
            'thumb'       => get_the_post_thumbnail_url($p->ID, 'thumbnail') ?: '',
        );
    }

    $counts_obj = wp_count_posts($post_type);
    $counts_arr = array(
        'publish' => (int) ($counts_obj->publish ?? 0),
        'draft'   => (int) ($counts_obj->draft ?? 0),
        'pending' => (int) ($counts_obj->pending ?? 0),
        'trash'   => (int) ($counts_obj->trash ?? 0),
        'all'     => (int) (($counts_obj->publish ?? 0) + ($counts_obj->draft ?? 0) + ($counts_obj->pending ?? 0)),
    );

    return rest_ensure_response(array(
        'items'            => $items,
        'total'            => (int) $q->found_posts,
        'total_pages'      => (int) $q->max_num_pages,
        'paged'            => $paged,
        'post_type'        => $post_type,
        'post_type_label'  => $labels_map[$post_type] ?? ucfirst($post_type),
        'counts'           => $counts_arr,
    ));
}

