<?php
/**
 * MV College My Portal Bootstrap
 * 
 * Configures rewrite rules, query vars, template guards, and shortcode [mvc_portal].
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register rewrite rules and query variables for /portal/
 */
add_action('init', 'mvc_register_portal_rewrites');
function mvc_register_portal_rewrites() {
    add_rewrite_rule('^portal/([^/]+)/?([^/]*)/?$', 'index.php?pagename=portal&mvc_view=$matches[1]&mvc_ref=$matches[2]', 'top');
}

add_filter('query_vars', 'mvc_register_portal_query_vars');
function mvc_register_portal_query_vars($vars) {
    $vars[] = 'mvc_view';
    $vars[] = 'mvc_ref';
    return $vars;
}

/**
 * Template Redirect Guard on /portal/ (Priority 1)
 */
add_action('template_redirect', 'mvc_portal_access_guard', 1);
function mvc_portal_access_guard() {
    if (!is_page('portal')) {
        return;
    }

    // 1. Prevent caching completely (S9)
    nocache_headers();
    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }

    // 2. Logged-out visitor -> Redirect to Single Sign-On (/login) with redirect_to
    if (!is_user_logged_in()) {
        $dest = add_query_arg('redirect_to', urlencode($_SERVER['REQUEST_URI'] ?? '/portal/'), home_url('/login/'));
        wp_safe_redirect($dest);
        exit;
    }

    // 3. Logged-in user without mvc_portal_access (e.g. staff roles)
    if (!current_user_can('mvc_portal_access')) {
        if (current_user_can('mvc_access_admin')) {
            // Staff member clicked portal link, show helpful 403 screen with link to Command Center
            status_header(403);
            wp_die(
                '<h2>Staff Administrative Access</h2>
                <p>You are logged in with administrative staff credentials. The Scholar Portal is reserved for enrolled students and alumni.</p>
                <p><a href="' . esc_url(admin_url('admin.php?page=mvc-command-center')) . '" style="display:inline-block; background:#002147; color:#fff; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:600;">Return to Staff Command Center &rarr;</a></p>',
                __('Portal Access Restricted', 'mvc-college'),
                array('response' => 403)
            );
        } else {
            status_header(403);
            wp_die(__('Access denied. Your account does not have permission to view this portal.', 'mvc-college'), 403);
        }
    }
}

/**
 * Enqueue Portal Assets on /portal/
 */
add_action('wp_enqueue_scripts', 'mvc_enqueue_portal_assets');
function mvc_enqueue_portal_assets() {
    if (is_page('portal')) {
        $base_url = get_stylesheet_directory_uri() . '/inc/admin/portal/assets';
        wp_enqueue_style('mvc-portal-app-css', $base_url . '/portal.css', array('dashicons'), '1.1.0');
    }
}

/**
 * Register shortcode [mvc_portal]
 */
add_shortcode('mvc_portal', 'mvc_render_portal_shortcode');
function mvc_render_portal_shortcode() {
    require_once dirname(__FILE__) . '/portal-engine.php';
    return mvc_run_portal_app();
}

/**
 * Ensure portal WordPress page exists in database
 */
function mvc_ensure_portal_page_exists() {
    $portal_page = get_page_by_path('portal');
    if (!$portal_page) {
        $page_id = wp_insert_post(array(
            'post_title'     => 'My Portal | M.V. College Buxar',
            'post_name'      => 'portal',
            'post_content'   => '[mvc_portal]',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
        ));
        flush_rewrite_rules();
        return $page_id;
    }
    return $portal_page->ID;
}
add_action('init', 'mvc_ensure_portal_page_exists', 20);
