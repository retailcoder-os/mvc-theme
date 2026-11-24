<?php
/**
 * MV College Access Guard & Security Hardening
 * 
 * Strict boundary enforcement between wp-admin and /portal/.
 * Rate limiting, enumeration blocking, session rules, and authentication gates.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ========================================================
   1. BLOCK WP-ADMIN FOR PORTAL ROLES
   ======================================================== */
add_action('admin_init', 'mvc_block_wp_admin_for_portal_users');
function mvc_block_wp_admin_for_portal_users() {
    if (wp_doing_ajax() || (defined('DOING_CRON') && DOING_CRON)) {
        return;
    }

    // Allow admin-post.php and admin-ajax.php for downloads and handlers
    $script_name = isset($_SERVER['SCRIPT_NAME']) ? basename($_SERVER['SCRIPT_NAME']) : '';
    if (in_array($script_name, array('admin-post.php', 'admin-ajax.php'), true)) {
        return;
    }

    if (!is_user_logged_in()) {
        return;
    }

    $user_id = get_current_user_id();
    if (!current_user_can('mvc_access_admin')) {
        // Portal role attempting wp-admin access
        $req = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        mvc_audit('admin_blocked', 'user', $user_id, array('requested_url' => $req));

        wp_safe_redirect(home_url('/portal/'));
        exit;
    }
}

/* ========================================================
   2. HIDE ADMIN BAR FOR NON-STAFF
   ======================================================== */
add_filter('show_admin_bar', 'mvc_filter_admin_bar_visibility');
function mvc_filter_admin_bar_visibility($show) {
    if (!is_user_logged_in()) {
        return false;
    }
    return current_user_can('mvc_access_admin') ? $show : false;
}

/* ========================================================
   3. LOGIN REDIRECT HARDENING
   ======================================================== */
add_filter('login_redirect', 'mvc_hardened_login_redirect', 99, 3);
function mvc_hardened_login_redirect($redirect_to, $requested_redirect_to, $user) {
    if (is_wp_error($user) || !is_a($user, 'WP_User')) {
        return $redirect_to;
    }

    // Record login success in audit log
    mvc_audit('login_success', 'user', $user->ID, array('login' => $user->user_login));

    // Portal roles always go to /portal/
    if (in_array('student', $user->roles, true) || in_array('alumni_member', $user->roles, true)) {
        if (!empty($requested_redirect_to) && strpos($requested_redirect_to, '/portal') !== false) {
            return $requested_redirect_to;
        }
        return home_url('/portal/');
    }

    // Staff roles: check Modern vs Legacy mode
    if (user_can($user, 'mvc_access_admin')) {
        $mode = get_user_meta($user->ID, 'mvc_admin_mode', true) ?: 'modern';
        if ($mode === 'modern') {
            return admin_url('admin.php?page=mvc-command-center');
        }
        return admin_url();
    }

    return $redirect_to;
}

/* ========================================================
   4. ACCOUNT STATUS GATE
   ======================================================== */
add_filter('wp_authenticate_user', 'mvc_verify_account_status_at_login', 99, 2);
function mvc_verify_account_status_at_login($user, $password) {
    if (is_wp_error($user)) {
        return $user;
    }

    // Administrators are exempt from status check
    if (in_array('administrator', $user->roles, true)) {
        return $user;
    }

    $status = get_user_meta($user->ID, 'mvc_account_status', true);

    // If status is not active (pending, suspended, rejected, or missing)
    if ($status !== 'active') {
        return new WP_Error(
            'mvc_account_inactive',
            __('Your account is not active. Please contact the college office.', 'mvc-college')
        );
    }

    return $user;
}

/* ========================================================
   5. GENERIC LOGIN ERRORS
   ======================================================== */
add_filter('login_errors', 'mvc_generic_login_errors');
function mvc_generic_login_errors($error) {
    if (!empty($error)) {
        // If it was already our custom neutral status message, show it
        if (strpos($error, 'Your account is not active') !== false) {
            return $error;
        }
        return __('<strong>Error:</strong> Invalid login credentials. Please verify your username and password.', 'mvc-college');
    }
    return $error;
}

/* ========================================================
   6. RATE LIMITING & LOCKOUT PROTECTION (5 FAILURES / 15 MIN)
   ======================================================== */
add_action('wp_login_failed', 'mvc_track_failed_login');
function mvc_track_failed_login($username) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    $ip_key   = 'mvc_fail_ip_' . md5($ip);
    $user_key = 'mvc_fail_user_' . md5(sanitize_user($username));

    $ip_fails   = (int) get_transient($ip_key) + 1;
    $user_fails = (int) get_transient($user_key) + 1;

    set_transient($ip_key, $ip_fails, 15 * MINUTE_IN_SECONDS);
    set_transient($user_key, $user_fails, 15 * MINUTE_IN_SECONDS);

    // Failure threshold: 25 in local testing, 5 in production
    $is_local = in_array($ip, array('127.0.0.1', '::1', 'localhost'), true) || (defined('WP_DEBUG') && WP_DEBUG);
    $threshold = $is_local ? (int) apply_filters('mvc_login_failure_threshold_local', 25) : (int) apply_filters('mvc_login_failure_threshold', 5);

    if ($ip_fails >= $threshold || $user_fails >= $threshold) {
        $lock_key = 'mvc_lock_' . md5($ip);
        if (!get_transient($lock_key)) {
            set_transient($lock_key, 1, 15 * MINUTE_IN_SECONDS);
            mvc_audit('login_lockout', 'system', 0, array(
                'ip'       => $ip,
                'username' => sanitize_user($username),
                'reason'   => "{$threshold} failed attempts within 15 minutes",
            ));
        }
    }
}

add_filter('authenticate', 'mvc_check_rate_limit_lockout', 20, 3);
function mvc_check_rate_limit_lockout($user, $username, $password) {
    if (empty($username)) {
        return $user;
    }

    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    $lock_key = 'mvc_lock_' . md5($ip);

    if (get_transient($lock_key)) {
        return new WP_Error(
            'mvc_rate_limited',
            __('Too many failed login attempts. For security, access is locked for 15 minutes.', 'mvc-college')
        );
    }

    return $user;
}

add_action('wp_login', 'mvc_clear_failed_login_counters', 10, 2);
function mvc_clear_failed_login_counters($user_login, $user) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    delete_transient('mvc_fail_ip_' . md5($ip));
    delete_transient('mvc_fail_user_' . md5(sanitize_user($user_login)));
    delete_transient('mvc_lock_' . md5($ip));
}

/* ========================================================
   7. USER ENUMERATION & SITEMAP PROTECTION
   ======================================================== */
add_action('template_redirect', 'mvc_block_author_enumeration');
function mvc_block_author_enumeration() {
    // 1. Block ?author=N enumeration
    if (isset($_GET['author'])) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }

    // 2. Block author archive pages
    if (is_author()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
    }
}

// Block /wp/v2/users REST endpoint for logged-out requests
add_filter('rest_endpoints', 'mvc_block_rest_user_enumeration');
function mvc_block_rest_user_enumeration($endpoints) {
    if (!is_user_logged_in()) {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
    }
    return $endpoints;
}

// Remove users provider from native WordPress XML sitemap
add_filter('wp_sitemaps_add_provider', 'mvc_remove_user_sitemap_provider', 10, 2);
function mvc_remove_user_sitemap_provider($provider, $name) {
    if ($name === 'users') {
        return false;
    }
    return $provider;
}

/* ========================================================
   8. DISABLE XML-RPC
   ======================================================== */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', '__return_empty_array');

/* ========================================================
   9. SESSION EXPIRATION & DESTRUCTION
   ======================================================== */
add_filter('auth_cookie_expiration', 'mvc_custom_auth_cookie_expiration', 99, 3);
function mvc_custom_auth_cookie_expiration($expiration, $user_id, $remember) {
    $user = get_userdata($user_id);
    if (!$user) {
        return $expiration;
    }

    // Staff roles: 12 hours max, no remember-me
    if (user_can($user, 'mvc_access_admin')) {
        return 12 * HOUR_IN_SECONDS;
    }

    // Portal roles: 24 hours, or 7 days with remember-me
    if ($remember) {
        return 7 * DAY_IN_SECONDS;
    }
    return 24 * HOUR_IN_SECONDS;
}

/**
 * Destroy all active sessions for a user upon suspension.
 *
 * @param int $user_id
 */
function mvc_destroy_user_sessions($user_id) {
    if (class_exists('WP_Session_Tokens')) {
        $manager = WP_Session_Tokens::get_instance($user_id);
        $manager->destroy_all();
    }
}

/* ========================================================
   10. STAFF PASSWORD POLICY (MINIMUM 12 CHARACTERS)
   ======================================================== */
add_action('user_profile_update_errors', 'mvc_enforce_staff_password_strength', 10, 3);
add_action('validate_password_reset', 'mvc_enforce_staff_password_strength_reset', 10, 2);

function mvc_enforce_staff_password_strength($errors, $update, $user) {
    if (!empty($_POST['pass1'])) {
        $user_obj = isset($user->ID) ? get_userdata($user->ID) : null;
        if ($user_obj && user_can($user_obj, 'mvc_access_admin')) {
            $pass = (string) $_POST['pass1'];
            if (strlen($pass) < 12) {
                $errors->add('mvc_weak_password', __('Staff accounts require a password of at least 12 characters.', 'mvc-college'));
            }
        }
    }
}

function mvc_enforce_staff_password_strength_reset($errors, $user) {
    if (!empty($_POST['pass1']) && is_a($user, 'WP_User')) {
        if (user_can($user, 'mvc_access_admin')) {
            $pass = (string) $_POST['pass1'];
            if (strlen($pass) < 12) {
                $errors->add('mvc_weak_password', __('Staff accounts require a password of at least 12 characters.', 'mvc-college'));
            }
        }
    }
}

/* ========================================================
   11. NATIVE SCREENS PRIVACY & MENU HYGIENE
   ======================================================== */
add_action('admin_menu', 'mvc_curate_legacy_staff_menus', 999);
function mvc_curate_legacy_staff_menus() {
    $user = wp_get_current_user();
    if (!$user || in_array('administrator', $user->roles, true)) {
        return;
    }

    // Non-admins with admin access (content_manager, department_head)
    if (user_can($user, 'mvc_access_admin')) {
        // Remove menu items that do not belong to their capability set
        if (!current_user_can('manage_options')) {
            remove_menu_page('options-general.php');
            remove_menu_page('plugins.php');
            remove_menu_page('themes.php');
            remove_menu_page('tools.php');
            remove_menu_page('edit-comments.php');
        }
        if (!current_user_can('list_users')) {
            remove_menu_page('users.php');
            // Allow Profile edit
            add_menu_page(__('My Profile', 'mvc-college'), __('My Profile', 'mvc-college'), 'read', 'profile.php', '', 'dashicons-admin-users', 70);
        }

        // Department Heads: remove root posts, pages
        if (in_array('department_head', $user->roles, true)) {
            remove_menu_page('edit.php');
            remove_menu_page('edit.php?post_type=page');
        }

        // Add direct link to Grievances for legacy mode since CPT has show_ui => false
        if (current_user_can('mvc_handle_grievances')) {
            add_menu_page(
                __('Grievances Cell', 'mvc-college'),
                __('Grievances', 'mvc-college'),
                'mvc_handle_grievances',
                'admin.php?page=mvc-command-center&section=grievances',
                '',
                'dashicons-clipboard',
                28
            );
        }
    }
}

/* ========================================================
   12. PORTAL NOINDEX & ROBOTS.TXT
   ======================================================== */
add_filter('wp_robots', 'mvc_portal_robots_noindex');
function mvc_portal_robots_noindex($robots) {
    if (is_page('portal') || (function_exists('is_portal_request') && is_portal_request())) {
        $robots['noindex']  = true;
        $robots['nofollow'] = true;
    }
    return $robots;
}

add_filter('robots_txt', 'mvc_disallow_portal_robots_txt');
function mvc_disallow_portal_robots_txt($output) {
    $output .= "\nDisallow: /portal/\n";
    $output .= "Disallow: /login\n";
    return $output;
}
