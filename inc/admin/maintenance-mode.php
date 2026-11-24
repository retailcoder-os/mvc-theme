<?php
/**
 * M.V. College, Buxar (Maharshi Vishwamitra Mahavidyalaya)
 * Public Website Maintenance Mode Engine & Institutional Guard (10/10 Standard)
 *
 * When Maintenance Mode is active:
 * 1. Only the home page is accessible to public (non-logged-in / non-admin) visitors.
 * 2. Any attempt by public visitors to access internal pages/posts/taxonomies automatically redirects to the homepage.
 * 3. A prominent, high-visibility institutional maintenance alert banner is displayed at the top of the header.
 * 4. Administrators retain full access to preview, navigate, and manage all pages.
 * 5. Instant 1-click toggle switch available in Command Center, Dedicated Admin Page, and WP Admin Bar.
 *
 * @package Astra_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Default Maintenance Mode Settings
 *
 * @return array
 */
function mvc_get_maintenance_defaults() {
    return array(
        'enabled'        => false,
        'notice_title'   => 'Scheduled Website Maintenance in Progress',
        'notice_message' => 'Maharshi Vishwamitra Mahavidyalaya portal is currently undergoing scheduled system updates and maintenance. During this window, only the home page is available for public viewing. All academic departments, notices, student portal, and online services will resume shortly. We apologize for any inconvenience caused.',
        'helpline_phone' => '06183-796317',
        'helpline_email' => 'mvcollegebuxar@gmail.com',
        'show_badge'     => true,
        'badge_text'     => 'Public Access Restricted',
        'updated_at'     => '',
        'updated_by'     => '',
    );
}

/**
 * 2. Get Current Maintenance Settings
 *
 * @return array
 */
function mvc_get_maintenance_settings() {
    $defaults = mvc_get_maintenance_defaults();
    $settings = get_option('mvc_maintenance_settings', array());
    if (!is_array($settings)) {
        $settings = array();
    }
    return wp_parse_args($settings, $defaults);
}

/**
 * 3. Check if Maintenance Mode is Currently Active
 *
 * @return bool
 */
function mvc_is_maintenance_mode_active() {
    // Fast path: direct boolean option check
    $quick = get_option('mvc_maintenance_mode', null);
    if ($quick !== null) {
        return ($quick === 'yes' || $quick === true || $quick === '1' || $quick === 1);
    }
    $settings = mvc_get_maintenance_settings();
    return !empty($settings['enabled']);
}

/**
 * 4. Check if Current User is Authorized to Bypass Maintenance Mode
 *
 * @return bool
 */
function mvc_can_bypass_maintenance() {
    if (!is_user_logged_in()) {
        return false;
    }
    return current_user_can('manage_options')
        || current_user_can('mvc_access_admin')
        || current_user_can('edit_posts')
        || is_super_admin();
}

/**
 * 5. Check if the Current HTTP Request is Strictly the Home Page
 *
 * @return bool
 */
function mvc_is_homepage_request() {
    // Search, feed, trackbacks, and author enumeration are never treated as the front page
    if (is_search() || is_feed() || is_trackback() || is_author()) {
        return false;
    }

    // Standard WordPress conditional checks
    if (is_front_page() || is_home()) {
        return true;
    }

    // Robust URL path comparison (supports subdirectories like /demo/)
    $home_path = trim(wp_parse_url(home_url('/'), PHP_URL_PATH) ?? '', '/');
    $req_path  = trim(wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');

    return ($req_path === $home_path);
}

/**
 * 6. Set Maintenance Mode State and Synchronize Options & Caches
 *
 * @param bool  $enabled
 * @param array $args
 * @return bool
 */
function mvc_set_maintenance_mode($enabled, $args = array()) {
    $current    = mvc_get_maintenance_settings();
    $was_active = !empty($current['enabled']);
    $new_active = (bool) $enabled;

    $current['enabled']    = $new_active;
    $current['updated_at'] = current_time('mysql');
    $current['updated_by'] = is_user_logged_in() ? wp_get_current_user()->user_login : 'system';

    if (isset($args['notice_title'])) {
        $current['notice_title'] = sanitize_text_field($args['notice_title']);
    }
    if (isset($args['notice_message'])) {
        $current['notice_message'] = sanitize_textarea_field($args['notice_message']);
    }
    if (isset($args['helpline_phone'])) {
        $current['helpline_phone'] = sanitize_text_field($args['helpline_phone']);
    }
    if (isset($args['helpline_email'])) {
        $current['helpline_email'] = sanitize_email($args['helpline_email']);
    }
    if (isset($args['show_badge'])) {
        $current['show_badge'] = (bool) $args['show_badge'];
    }
    if (isset($args['badge_text'])) {
        $current['badge_text'] = sanitize_text_field($args['badge_text']);
    }

    update_option('mvc_maintenance_settings', $current);
    update_option('mvc_maintenance_mode', $new_active ? 'yes' : 'no');

    // Purge static and transient caches so the public gets immediate effect
    if (function_exists('mvc_purge_all_caches_deep')) {
        mvc_purge_all_caches_deep();
    }

    // Institutional Audit Logging
    if (function_exists('mvc_audit')) {
        mvc_audit(
            $new_active ? 'maintenance_mode_enabled' : 'maintenance_mode_disabled',
            'system',
            0,
            array(
                'previous_state' => $was_active,
                'new_state'      => $new_active,
                'actor'          => $current['updated_by'],
                'timestamp'      => $current['updated_at'],
            )
        );
    }

    return true;
}

/**
 * 7. Enforce Public Access Restriction on template_redirect
 *
 * When active, allows public visitors to ONLY access the home page.
 * All other routes redirect safely to home_url('/?maintenance_redirect=1').
 */
function mvc_enforce_maintenance_mode_access() {
    if (!mvc_is_maintenance_mode_active()) {
        return;
    }

    // 1. Allow background system processes
    if ((defined('DOING_CRON') && DOING_CRON) || wp_doing_ajax() || (defined('WP_CLI') && WP_CLI)) {
        return;
    }

    // 2. Allow administrative and staff users full site access
    if (mvc_can_bypass_maintenance()) {
        return;
    }

    // 3. Allow standard WP admin and login interfaces so staff can sign in
    global $pagenow;
    if (is_admin() || $pagenow === 'wp-login.php') {
        return;
    }

    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, 'wp-login.php') !== false || strpos($uri, 'wp-admin') !== false) {
        return;
    }

    // 4. Allow dedicated institutional login routes
    if (function_exists('is_page') && (is_page('login') || is_page('portal-login'))) {
        return;
    }

    // 5. Allow Home Page strictly (The Home Page remains 100% available to public)
    if (mvc_is_homepage_request()) {
        return;
    }

    // 6. Public visitor attempting to view any internal page: Safe Redirect to Home Page
    nocache_headers();
    $redirect_url = add_query_arg('maintenance_redirect', '1', home_url('/'));
    wp_safe_redirect($redirect_url, 302);
    exit;
}
add_action('template_redirect', 'mvc_enforce_maintenance_mode_access', 0);

/**
 * 8. Render the Institutional Maintenance Banner on Top
 *
 * Displayed at the very top of header.php (Tier 0).
 */
function mvc_render_maintenance_notice_banner() {
    if (!mvc_is_maintenance_mode_active()) {
        return;
    }

    $settings   = mvc_get_maintenance_settings();
    $title      = !empty($settings['notice_title']) ? $settings['notice_title'] : 'Scheduled Website Maintenance in Progress';
    $message    = !empty($settings['notice_message']) ? $settings['notice_message'] : 'Maharshi Vishwamitra Mahavidyalaya portal is currently undergoing scheduled updates. During this period, only the homepage is accessible for public viewing. All other sections and online services will resume shortly.';
    $helpline   = !empty($settings['helpline_phone']) ? $settings['helpline_phone'] : '06183-796317';
    $email      = !empty($settings['helpline_email']) ? $settings['helpline_email'] : 'mvcollegebuxar@gmail.com';
    $show_badge = !empty($settings['show_badge']);
    $badge_text = !empty($settings['badge_text']) ? $settings['badge_text'] : 'Public Access Restricted';
    $is_admin   = current_user_can('manage_options');
    $was_redir  = isset($_GET['maintenance_redirect']);
    ?>
    <aside id="mvcMaintenanceTopBanner" class="mvc-maintenance-banner <?php echo $is_admin ? 'mvc-maint-admin-mode' : ''; ?>" role="alert" aria-live="assertive">
        <style id="mvc-maint-banner-css">
            .mvc-maintenance-banner {
                width: 100%;
                background: linear-gradient(135deg, #7A1C1C 0%, #521111 60%, #3B0A0A 100%);
                color: #ffffff;
                border-bottom: 3px solid #D4AF37;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.28);
                position: relative;
                z-index: 999999;
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                padding: 10px 0;
            }
            .mvc-maintenance-banner.mvc-maint-admin-mode {
                background: linear-gradient(135deg, #7A1C1C 0%, #4A0E17 70%, #1E293B 100%);
                border-bottom: 3px solid #F59E0B;
            }
            .mvc-maintenance-flex {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
                max-width: 1320px;
                margin: 0 auto;
                padding: 0 20px;
            }
            .mvc-maint-left {
                display: flex;
                align-items: center;
                gap: 14px;
                flex: 1 1 680px;
            }
            .mvc-maint-pulse-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 50%;
                background: rgba(255, 213, 79, 0.18);
                border: 2px solid #FFD54F;
                color: #FFD54F;
                flex-shrink: 0;
                animation: mvcPulseGlow 2.4s infinite;
            }
            @keyframes mvcPulseGlow {
                0% { box-shadow: 0 0 0 0 rgba(255, 213, 79, 0.55); }
                70% { box-shadow: 0 0 0 10px rgba(255, 213, 79, 0); }
                100% { box-shadow: 0 0 0 0 rgba(255, 213, 79, 0); }
            }
            .mvc-maint-content {
                flex: 1;
            }
            .mvc-maint-title-row {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
                margin-bottom: 3px;
            }
            .mvc-maint-title {
                font-size: 15px;
                font-weight: 800;
                letter-spacing: 0.2px;
                color: #FFF8E1;
                line-height: 1.25;
            }
            .mvc-maint-badge {
                display: inline-block;
                background: #FFD54F;
                color: #4A0E17;
                font-size: 10.5px;
                font-weight: 800;
                padding: 2px 7px;
                border-radius: 4px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .mvc-maint-badge-admin {
                background: #38BDF8;
                color: #082F49;
            }
            .mvc-maint-text {
                font-size: 12.8px;
                line-height: 1.45;
                color: #F8FAFC;
                margin: 0;
                opacity: 0.95;
            }
            .mvc-maint-redirect-notice {
                display: inline-block;
                background: rgba(254, 240, 138, 0.18);
                border-left: 3px solid #FACC15;
                padding: 4px 10px;
                margin-top: 5px;
                border-radius: 0 4px 4px 0;
                font-size: 11.8px;
                color: #FEF08A;
                font-weight: 600;
            }
            .mvc-maint-right {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
                flex-shrink: 0;
            }
            .mvc-maint-chip {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 5px 12px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 700;
                text-decoration: none !important;
                transition: all 0.2s ease;
                background: rgba(255, 255, 255, 0.12);
                color: #ffffff !important;
                border: 1px solid rgba(255, 255, 255, 0.25);
            }
            .mvc-maint-chip:hover {
                background: #ffffff;
                color: #7A1C1C !important;
                transform: translateY(-1px);
            }
            .mvc-maint-chip-phone {
                background: rgba(255, 213, 79, 0.2);
                color: #FFD54F !important;
                border-color: rgba(255, 213, 79, 0.4);
            }
            .mvc-maint-chip-phone:hover {
                background: #FFD54F;
                color: #002147 !important;
            }
            .mvc-maint-chip-admin {
                background: #FFD54F;
                color: #002147 !important;
                font-weight: 800;
                border-color: #FFD54F;
            }
            .mvc-maint-chip-admin:hover {
                background: #ffffff;
                color: #7A1C1C !important;
            }
            @media (max-width: 900px) {
                .mvc-maintenance-flex {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 10px;
                }
                .mvc-maint-left {
                    flex: 1 1 100%;
                    width: 100%;
                }
                .mvc-maint-right {
                    width: 100%;
                    justify-content: flex-start;
                }
            }
            @media (max-width: 600px) {
                .mvc-maint-pulse-icon {
                    width: 36px;
                    height: 36px;
                }
                .mvc-maint-title {
                    font-size: 13.5px;
                }
                .mvc-maint-text {
                    font-size: 11.8px;
                }
                .mvc-maint-chip {
                    font-size: 11px;
                    padding: 4px 9px;
                }
            }
        </style>
        <div class="mvc-container mvc-maintenance-flex">
            <div class="mvc-maint-left">
                <span class="mvc-maint-pulse-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </span>
                <div class="mvc-maint-content">
                    <div class="mvc-maint-title-row">
                        <strong class="mvc-maint-title"><?php echo esc_html($title); ?></strong>
                        <?php if ($show_badge && !empty($badge_text)) : ?>
                            <span class="mvc-maint-badge"><?php echo esc_html($badge_text); ?></span>
                        <?php endif; ?>
                        <?php if ($is_admin) : ?>
                            <span class="mvc-maint-badge mvc-maint-badge-admin">Admin Preview</span>
                        <?php endif; ?>
                    </div>
                    <p class="mvc-maint-text"><?php echo esc_html($message); ?></p>
                    <?php if ($was_redir) : ?>
                        <div class="mvc-maint-redirect-notice">
                            ℹ️ Note: You have been redirected to the homepage because internal pages and portals are temporarily offline during maintenance.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mvc-maint-right">
                <?php if (!empty($helpline)) : ?>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $helpline)); ?>" class="mvc-maint-chip mvc-maint-chip-phone" title="Campus Central Helpline">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Helpline: <?php echo esc_html($helpline); ?></span>
                    </a>
                <?php endif; ?>
                <?php if (!empty($email)) : ?>
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="mvc-maint-chip" title="Official Institutional Email">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span><?php echo esc_html($email); ?></span>
                    </a>
                <?php endif; ?>
                <?php if ($is_admin) : ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-maintenance-mode')); ?>" class="mvc-maint-chip mvc-maint-chip-admin" title="Manage Maintenance Mode Settings">
                        ⚙️ Manage Switch
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </aside>
    <?php
}

/**
 * 9. Persistent Admin Bar Indicator & Quick Navigation Node
 */
function mvc_admin_bar_maintenance_indicator($wp_admin_bar) {
    if (!is_user_logged_in() || !current_user_can('manage_options')) {
        return;
    }

    $active = mvc_is_maintenance_mode_active();

    if ($active) {
        $wp_admin_bar->add_node(array(
            'id'    => 'mvc-maintenance-status',
            'title' => '<span style="background:#B45309; color:#ffffff; font-weight:800; padding:2px 8px; border-radius:12px; font-size:11px; display:inline-flex; align-items:center; gap:5px; box-shadow:0 0 8px rgba(180,83,9,0.7);"><span style="width:7px; height:7px; border-radius:50%; background:#FFD54F;"></span>⚠️ Maintenance Mode: ON</span>',
            'href'  => admin_url('admin.php?page=mvc-maintenance-mode'),
            'meta'  => array('title' => 'Maintenance Mode is ACTIVE: Public is restricted to homepage only. Click to manage.'),
        ));
    } else {
        $wp_admin_bar->add_node(array(
            'id'    => 'mvc-maintenance-status',
            'title' => '<span style="color:#94A3B8; font-size:11px; display:inline-flex; align-items:center; gap:4px;">🛠️ Maintenance: OFF</span>',
            'href'  => admin_url('admin.php?page=mvc-maintenance-mode'),
            'meta'  => array('title' => 'Maintenance Mode is OFF (Site is fully live). Click to configure.'),
        ));
    }
}
add_action('admin_bar_menu', 'mvc_admin_bar_maintenance_indicator', 100);

/**
 * 10. Register Dedicated Admin Menu Items
 */
function mvc_register_maintenance_admin_menus() {
    // 1. Submenu under College Global Settings
    add_submenu_page(
        'college-global-settings',
        __('Maintenance Mode Switch & Guard', 'mvc-child'),
        __('🛠️ Maintenance Mode', 'mvc-child'),
        'manage_options',
        'mvc-maintenance-mode',
        'mvc_render_maintenance_admin_page'
    );

    // 2. Also register under standard Settings menu for native WP discovery
    add_options_page(
        __('Maintenance Mode Switch & Guard', 'mvc-child'),
        __('🛠️ Maintenance Mode', 'mvc-child'),
        'manage_options',
        'mvc-maintenance-mode',
        'mvc_render_maintenance_admin_page'
    );
}
add_action('admin_menu', 'mvc_register_maintenance_admin_menus', 35);

/**
 * 11. Handle Admin Form Submissions and Quick Toggle Actions
 */
function mvc_handle_maintenance_admin_actions() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle Quick 1-Click Toggle via GET request
    if (isset($_GET['mvc_quick_toggle_maintenance'])) {
        check_admin_referer('mvc_toggle_maint_nonce');
        $target = sanitize_key($_GET['mvc_quick_toggle_maintenance']);
        $new_state = ($target === 'on' || $target === '1');
        mvc_set_maintenance_mode($new_state);

        $redirect = add_query_arg('mvc_maint_updated', $new_state ? 'enabled' : 'disabled', remove_query_arg(array('mvc_quick_toggle_maintenance', '_wpnonce')));
        wp_safe_redirect($redirect);
        exit;
    }

    // Handle Full Settings Save via POST request
    if (isset($_POST['mvc_save_maintenance_settings'])) {
        check_admin_referer('mvc_save_maintenance_nonce');

        $enabled  = !empty($_POST['mvc_maintenance_enabled']);
        $title    = isset($_POST['mvc_notice_title']) ? sanitize_text_field($_POST['mvc_notice_title']) : '';
        $message  = isset($_POST['mvc_notice_message']) ? sanitize_textarea_field($_POST['mvc_notice_message']) : '';
        $helpline = isset($_POST['mvc_helpline_phone']) ? sanitize_text_field($_POST['mvc_helpline_phone']) : '';
        $email    = isset($_POST['mvc_helpline_email']) ? sanitize_email($_POST['mvc_helpline_email']) : '';
        $badge    = !empty($_POST['mvc_show_badge']);
        $btext    = isset($_POST['mvc_badge_text']) ? sanitize_text_field($_POST['mvc_badge_text']) : 'Public Access Restricted';

        mvc_set_maintenance_mode($enabled, array(
            'notice_title'   => $title,
            'notice_message' => $message,
            'helpline_phone' => $helpline,
            'helpline_email' => $email,
            'show_badge'     => $badge,
            'badge_text'     => $btext,
        ));

        $redirect = add_query_arg('mvc_maint_updated', 'saved', remove_query_arg(array('mvc_save_maintenance_settings', '_wpnonce')));
        wp_safe_redirect($redirect);
        exit;
    }
}
add_action('admin_init', 'mvc_handle_maintenance_admin_actions');

/**
 * 12. Render the Dedicated Maintenance Mode Administration Page
 */
function mvc_render_maintenance_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'mvc-child'));
    }

    $settings  = mvc_get_maintenance_settings();
    $is_active = !empty($settings['enabled']);
    $quick_nonce = wp_create_nonce('mvc_toggle_maint_nonce');
    ?>
    <div class="wrap" style="max-width:1080px; margin:24px auto;">
        
        <!-- Header Banner -->
        <div style="background:#002147; border-radius:12px; padding:28px 32px; color:#ffffff; margin-bottom:24px; box-shadow:0 8px 24px rgba(0,33,71,0.15); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
            <div style="display:flex; align-items:center; gap:16px;">
                <div style="width:52px; height:52px; border-radius:12px; background:rgba(255,213,79,0.18); border:2px solid #FFD54F; display:flex; align-items:center; justify-content:center; color:#FFD54F; flex-shrink:0;">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <div>
                    <h1 style="color:#ffffff; font-size:24px; font-weight:800; margin:0 0 4px 0; font-family:'Plus Jakarta Sans', sans-serif;">
                        Website Maintenance Mode &amp; Public Access Switch
                    </h1>
                    <div style="color:#CBD5E1; font-size:13.5px;">
                        Institutional Guard: Restrict public access to the Home Page only while displaying a top maintenance notice.
                    </div>
                </div>
            </div>

            <!-- Current Status Pill -->
            <div style="text-align:right;">
                <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.8px; color:#94A3B8; margin-bottom:4px; font-weight:700;">CURRENT STATE</div>
                <?php if ($is_active) : ?>
                    <span style="display:inline-flex; align-items:center; gap:6px; background:#DC2626; color:#ffffff; padding:6px 16px; border-radius:999px; font-weight:800; font-size:13px; box-shadow:0 0 14px rgba(220,38,38,0.5);">
                        <span style="width:8px; height:8px; border-radius:50%; background:#ffffff; animation:mvcDotBlink 1.2s infinite;"></span>
                        MAINTENANCE MODE ACTIVE
                    </span>
                <?php else : ?>
                    <span style="display:inline-flex; align-items:center; gap:6px; background:#16A34A; color:#ffffff; padding:6px 16px; border-radius:999px; font-weight:800; font-size:13px; box-shadow:0 0 14px rgba(22,163,74,0.3);">
                        <span style="width:8px; height:8px; border-radius:50%; background:#ffffff;"></span>
                        SITE LIVE &amp; OPERATIONAL
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_GET['mvc_maint_updated'])) : ?>
            <div class="notice notice-success is-dismissible" style="border-left-color:#16A34A; padding:12px 16px; margin-bottom:20px; border-radius:6px;">
                <p style="margin:0; font-size:14px; font-weight:700; color:#15803D;">
                    <?php 
                    if ($_GET['mvc_maint_updated'] === 'enabled') {
                        echo '⚠️ Maintenance Mode is now ENABLED. Only the home page is accessible to public visitors, and the top maintenance banner is active.';
                    } elseif ($_GET['mvc_maint_updated'] === 'disabled') {
                        echo '✅ Maintenance Mode is now DISABLED. All pages and portals are fully accessible to the public.';
                    } else {
                        echo '✅ Maintenance mode settings saved successfully and all system caches have been purged.';
                    }
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Quick Action Card -->
        <div style="background:#ffffff; border:1px solid #E2E8F0; border-radius:12px; padding:24px 28px; margin-bottom:24px; box-shadow:0 4px 12px rgba(0,0,0,0.03); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
            <div>
                <h3 style="margin:0 0 6px 0; font-size:16px; color:#0F172A; font-weight:700;">
                    Instant 1-Click Switch
                </h3>
                <p style="margin:0; font-size:13px; color:#64748B; max-width:620px; line-height:1.5;">
                    Toggle this switch to immediately lock or unlock public access. When switched ON, any visitor who navigates to departments, notices, events, or contact pages is automatically redirected to the Home Page where the maintenance notice is displayed.
                </p>
            </div>
            <div>
                <?php if ($is_active) : ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-maintenance-mode&mvc_quick_toggle_maintenance=off&_wpnonce=' . $quick_nonce)); ?>" class="button button-primary button-large" style="background:#16A34A; border-color:#16A34A; color:#ffffff; font-weight:700; font-size:14px; padding:6px 22px; height:auto; box-shadow:0 4px 10px rgba(22,163,74,0.3);">
                        🟢 Turn OFF Maintenance (Make Site Live)
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-maintenance-mode&mvc_quick_toggle_maintenance=on&_wpnonce=' . $quick_nonce)); ?>" class="button button-primary button-large" style="background:#B45309; border-color:#B45309; color:#ffffff; font-weight:700; font-size:14px; padding:6px 22px; height:auto; box-shadow:0 4px 10px rgba(180,83,9,0.3);">
                        ⚠️ Turn ON Maintenance Mode
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Settings Form & Live Preview Grid -->
        <form method="post" action="" style="display:flex; flex-direction:column; gap:24px;">
            <?php wp_nonce_field('mvc_save_maintenance_nonce'); ?>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
                
                <!-- Left Column: Form Controls -->
                <div style="background:#ffffff; border:1px solid #E2E8F0; border-radius:12px; padding:28px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
                    <h3 style="margin:0 0 20px 0; font-size:17px; color:#0F172A; font-weight:800; border-bottom:1px solid #E2E8F0; padding-bottom:12px;">
                        ⚙️ Maintenance Mode Configuration
                    </h3>

                    <!-- Toggle Checkbox Row -->
                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:16px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <div style="font-weight:700; font-size:14px; color:#0F172A;">Enable Maintenance Mode</div>
                            <div style="font-size:12px; color:#64748B;">Restricts public visitors strictly to Home Page</div>
                        </div>
                        <label class="switch" style="position:relative; display:inline-block; width:52px; height:28px;">
                            <input type="checkbox" name="mvc_maintenance_enabled" id="mvc_maint_enabled_input" value="1" <?php checked($is_active, true); ?> style="opacity:0; width:0; height:0;">
                            <span class="slider round" id="mvc_maint_slider_ui" style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:<?php echo $is_active ? '#B45309' : '#CBD5E1'; ?>; transition:.3s; border-radius:28px;">
                                <span class="knob" id="mvc_maint_knob_ui" style="position:absolute; height:20px; width:20px; left:<?php echo $is_active ? '28px' : '4px'; ?>; bottom:4px; background-color:white; transition:.3s; border-radius:50%; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
                            </span>
                        </label>
                    </div>

                    <!-- Notice Title -->
                    <div style="margin-bottom:18px;">
                        <label for="mvc_notice_title" style="display:block; font-weight:700; font-size:13.5px; color:#1E293B; margin-bottom:6px;">
                            Notice Title / Heading
                        </label>
                        <input type="text" name="mvc_notice_title" id="mvc_notice_title" value="<?php echo esc_attr($settings['notice_title']); ?>" class="regular-text" style="width:100%; border-radius:6px; padding:8px 12px; font-size:13.5px;" placeholder="Scheduled Website Maintenance in Progress">
                    </div>

                    <!-- Notice Message -->
                    <div style="margin-bottom:18px;">
                        <label for="mvc_notice_message" style="display:block; font-weight:700; font-size:13.5px; color:#1E293B; margin-bottom:6px;">
                            Maintenance Notice Text (Displayed on Top Banner)
                        </label>
                        <textarea name="mvc_notice_message" id="mvc_notice_message" rows="4" style="width:100%; border-radius:6px; padding:10px 12px; font-size:13px; line-height:1.5;" placeholder="Enter the message visitors will read on the banner..."><?php echo esc_textarea($settings['notice_message']); ?></textarea>
                    </div>

                    <!-- Campus Helpline & Email Grid -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:18px;">
                        <div>
                            <label for="mvc_helpline_phone" style="display:block; font-weight:700; font-size:12.5px; color:#1E293B; margin-bottom:5px;">
                                Campus Helpline Phone
                            </label>
                            <input type="text" name="mvc_helpline_phone" id="mvc_helpline_phone" value="<?php echo esc_attr($settings['helpline_phone']); ?>" style="width:100%; border-radius:6px; padding:7px 10px; font-size:13px;" placeholder="06183-796317">
                        </div>
                        <div>
                            <label for="mvc_helpline_email" style="display:block; font-weight:700; font-size:12.5px; color:#1E293B; margin-bottom:5px;">
                                Support Email
                            </label>
                            <input type="email" name="mvc_helpline_email" id="mvc_helpline_email" value="<?php echo esc_attr($settings['helpline_email']); ?>" style="width:100%; border-radius:6px; padding:7px 10px; font-size:13px;" placeholder="mvcollegebuxar@gmail.com">
                        </div>
                    </div>

                    <!-- Badge Controls -->
                    <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:14px; margin-bottom:20px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:700; font-size:13px; color:#1E293B;">
                            <input type="checkbox" name="mvc_show_badge" id="mvc_show_badge" value="1" <?php checked(!empty($settings['show_badge']), true); ?>>
                            Show Alert Pill / Badge on Banner
                        </label>
                        <div style="margin-top:8px;">
                            <input type="text" name="mvc_badge_text" id="mvc_badge_text" value="<?php echo esc_attr($settings['badge_text']); ?>" style="width:100%; border-radius:6px; padding:6px 10px; font-size:12px;" placeholder="Public Access Restricted">
                        </div>
                    </div>

                    <button type="submit" name="mvc_save_maintenance_settings" value="1" class="button button-primary button-large" style="width:100%; background:#002147; border-color:#002147; font-weight:700; font-size:14px; padding:8px 0; height:auto; box-shadow:0 4px 12px rgba(0,33,71,0.2);">
                        💾 Save Maintenance Configuration
                    </button>
                </div>

                <!-- Right Column: Live Front-End Banner Preview -->
                <div style="display:flex; flex-direction:column; gap:20px;">
                    
                    <div style="background:#ffffff; border:1px solid #E2E8F0; border-radius:12px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                            <h3 style="margin:0; font-size:16px; color:#0F172A; font-weight:800;">
                                👁️ Live Banner Preview
                            </h3>
                            <span style="font-size:11px; background:#E2E8F0; color:#475569; font-weight:700; padding:2px 8px; border-radius:4px;">
                                FRONT-END SIMULATION
                            </span>
                        </div>
                        <div style="font-size:12.5px; color:#64748B; margin-bottom:16px;">
                            This is an exact visual simulation of how the maintenance notice bar renders at the top of the header on the front end:
                        </div>

                        <!-- Preview Container -->
                        <div id="mvc_preview_container" style="border-radius:8px; overflow:hidden; box-shadow:0 4px 14px rgba(0,0,0,0.18);">
                            <div style="background:linear-gradient(135deg, #7A1C1C 0%, #521111 60%, #3B0A0A 100%); color:#ffffff; border-bottom:3px solid #D4AF37; padding:12px 16px;">
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div style="width:36px; height:36px; border-radius:50%; background:rgba(255,213,79,0.18); border:2px solid #FFD54F; display:flex; align-items:center; justify-content:center; color:#FFD54F; flex-shrink:0;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                        </svg>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:2px;">
                                            <strong id="mvc_prev_title" style="font-size:14px; color:#FFF8E1; font-weight:800;">
                                                <?php echo esc_html($settings['notice_title']); ?>
                                            </strong>
                                            <span id="mvc_prev_badge" style="background:#FFD54F; color:#4A0E17; font-size:9.5px; font-weight:800; padding:1px 6px; border-radius:3px; text-transform:uppercase;">
                                                <?php echo esc_html($settings['badge_text']); ?>
                                            </span>
                                        </div>
                                        <div id="mvc_prev_message" style="font-size:12px; color:#F8FAFC; line-height:1.4; opacity:0.95;">
                                            <?php echo esc_html($settings['notice_message']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top:10px; padding-top:8px; border-top:1px solid rgba(255,255,255,0.15); display:flex; gap:8px; flex-wrap:wrap;">
                                    <span id="mvc_prev_helpline" style="background:rgba(255,213,79,0.2); color:#FFD54F; border:1px solid rgba(255,213,79,0.4); border-radius:999px; padding:3px 10px; font-size:11px; font-weight:700;">
                                        📞 Helpline: <?php echo esc_html($settings['helpline_phone']); ?>
                                    </span>
                                    <span id="mvc_prev_email" style="background:rgba(255,255,255,0.12); color:#ffffff; border:1px solid rgba(255,255,255,0.25); border-radius:999px; padding:3px 10px; font-size:11px; font-weight:700;">
                                        ✉️ <?php echo esc_html($settings['helpline_email']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operational Rule Card -->
                    <div style="background:#ffffff; border:1px solid #E2E8F0; border-radius:12px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
                        <h4 style="margin:0 0 12px 0; font-size:14.5px; color:#002147; font-weight:800; display:flex; align-items:center; gap:6px;">
                            <span>🛡️</span> Security &amp; Access Enforcement Rules
                        </h4>
                        <ul style="margin:0; padding-left:18px; font-size:12.5px; color:#475569; line-height:1.6;">
                            <li><strong>Public Visitors:</strong> Can ONLY view the Homepage. Any direct access or navigation to internal URLs (e.g. <code>/contact-us/</code>, <code>/notices/</code>, <code>/departments/</code>) is intercepted with HTTP 302 and returned to the Homepage.</li>
                            <li><strong>Administrators &amp; Staff:</strong> Retain full bypass privileges to browse, test, and edit all pages, posts, and portals seamlessly.</li>
                            <li><strong>Login &amp; Auth Protection:</strong> <code>/wp-login.php</code> and administrative routes remain unblocked so staff can always authenticate safely.</li>
                            <li><strong>Automatic Cache Purging:</strong> Every state change flushes static caches, transients, and rewrite locks immediately.</li>
                        </ul>
                    </div>

                </div>

            </div>
        </form>

        <script>
            // Live Preview Synchronization
            (function() {
                var titleIn   = document.getElementById('mvc_notice_title');
                var msgIn     = document.getElementById('mvc_notice_message');
                var phoneIn   = document.getElementById('mvc_helpline_phone');
                var emailIn   = document.getElementById('mvc_helpline_email');
                var badgeIn   = document.getElementById('mvc_badge_text');
                var badgeShow = document.getElementById('mvc_show_badge');

                var prevTitle    = document.getElementById('mvc_prev_title');
                var prevMsg      = document.getElementById('mvc_prev_message');
                var prevHelpline = document.getElementById('mvc_prev_helpline');
                var prevEmail    = document.getElementById('mvc_prev_email');
                var prevBadge    = document.getElementById('mvc_prev_badge');

                var toggleIn = document.getElementById('mvc_maint_enabled_input');
                var slider   = document.getElementById('mvc_maint_slider_ui');
                var knob     = document.getElementById('mvc_maint_knob_ui');

                if (titleIn && prevTitle) {
                    titleIn.addEventListener('input', function() {
                        prevTitle.textContent = this.value || 'Scheduled Website Maintenance in Progress';
                    });
                }
                if (msgIn && prevMsg) {
                    msgIn.addEventListener('input', function() {
                        prevMsg.textContent = this.value || 'Maharshi Vishwamitra Mahavidyalaya portal is currently undergoing scheduled updates.';
                    });
                }
                if (phoneIn && prevHelpline) {
                    phoneIn.addEventListener('input', function() {
                        prevHelpline.textContent = '📞 Helpline: ' + (this.value || '06183-796317');
                    });
                }
                if (emailIn && prevEmail) {
                    emailIn.addEventListener('input', function() {
                        prevEmail.textContent = '✉️ ' + (this.value || 'mvcollegebuxar@gmail.com');
                    });
                }
                if (badgeIn && prevBadge) {
                    badgeIn.addEventListener('input', function() {
                        prevBadge.textContent = this.value || 'Public Access Restricted';
                    });
                }
                if (badgeShow && prevBadge) {
                    badgeShow.addEventListener('change', function() {
                        prevBadge.style.display = this.checked ? 'inline-block' : 'none';
                    });
                }
                if (toggleIn && slider && knob) {
                    toggleIn.addEventListener('change', function() {
                        slider.style.backgroundColor = this.checked ? '#B45309' : '#CBD5E1';
                        knob.style.left = this.checked ? '28px' : '4px';
                    });
                }
            })();
        </script>
    </div>
    <?php
}

