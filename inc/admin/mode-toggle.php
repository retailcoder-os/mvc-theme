<?php
/**
 * MV College Dual-Mode Administration Switcher
 * 
 * Provides per-user Legacy (native WordPress) vs Modern (Command Center) toggle.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get current user's administrative UI mode ('modern' or 'legacy').
 *
 * @param int|null $user_id
 * @return string
 */
function mvc_get_user_admin_mode($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return 'modern';
    }
    $mode = get_user_meta($user_id, 'mvc_admin_mode', true);
    return ($mode === 'legacy') ? 'legacy' : 'modern';
}

/**
 * Handle mode toggle requests via GET with nonce verification.
 */
add_action('admin_init', 'mvc_handle_mode_switch_action');
function mvc_handle_mode_switch_action() {
    if (!isset($_GET['mvc_toggle_mode']) || !current_user_can('mvc_access_admin')) {
        return;
    }

    check_admin_referer('mvc_toggle_mode_nonce');

    $target  = sanitize_key($_GET['mvc_toggle_mode']);
    $user_id = get_current_user_id();

    if (in_array($target, array('modern', 'legacy'), true)) {
        update_user_meta($user_id, 'mvc_admin_mode', $target);
        mvc_audit('mode_switched', 'user', $user_id, array('mode' => $target));
    }

    if ($target === 'modern') {
        $dest = admin_url('admin.php?page=mvc-command-center&switched=modern');
    } else {
        $dest = admin_url('index.php?switched=legacy');
    }

    wp_safe_redirect($dest);
    exit;
}

/**
 * In Modern mode, redirect /wp-admin/index.php to Command Center.
 */
add_action('load-index.php', 'mvc_redirect_to_command_center_if_modern');
function mvc_redirect_to_command_center_if_modern() {
    if (!current_user_can('mvc_access_admin')) {
        return;
    }

    if (wp_doing_ajax() || (defined('DOING_CRON') && DOING_CRON)) {
        return;
    }

    if (isset($_GET['page']) || isset($_GET['action'])) {
        return;
    }

    if (mvc_get_user_admin_mode() === 'modern') {
        wp_safe_redirect(admin_url('admin.php?page=mvc-command-center'));
        exit;
    }
}

/**
 * Inject context-aware toggle into native WordPress Admin Bar.
 * - When on Modern Command Center: offers switch to Classic WP.
 * - When on any Classic WP screen (edit, add, list): offers prominent return to Modern CMS Hub.
 */
add_action('admin_bar_menu', 'mvc_admin_bar_mode_switcher_node', 999);
function mvc_admin_bar_mode_switcher_node($wp_admin_bar) {
    if (!is_user_logged_in() || !current_user_can('mvc_access_admin')) {
        return;
    }

    $current_user = wp_get_current_user();
    $roles        = (array) $current_user->roles;
    $is_hod       = in_array('department_head', $roles, true) || in_array('department_editor', $roles, true);
    $return_url   = $is_hod 
        ? admin_url('admin.php?page=mvc-command-center&section=my-department') 
        : admin_url('admin.php?page=mvc-command-center');

    // Detect if current screen is the Modern Command Center page
    $current_page      = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';
    $is_command_center = in_array($current_page, array('mvc-command-center', 'mvcollege-command-center'), true);

    $current_mode = mvc_get_user_admin_mode();

    if ($is_command_center) {
        // CASE 1: Currently on the Modern Command Center page -> Offer switch to Classic WP
        $toggle_url = wp_nonce_url(admin_url('index.php?mvc_toggle_mode=legacy'), 'mvc_toggle_mode_nonce');
        $wp_admin_bar->add_node(array(
            'id'    => 'mvc-mode-toggle',
            'title' => '<span class="ab-icon dashicons dashicons-wordpress" style="font-family:dashicons; margin-top:2px;"></span><span class="ab-label">' . __('Switch to Classic WP', 'mvc-college') . '</span>',
            'href'  => $toggle_url,
            'meta'  => array(
                'title' => __('Switch default dashboard to classic native WordPress admin layout', 'mvc-college')
            )
        ));
    } else {
        // CASE 2: Currently on ANY standard Classic WP screen (post editor, list tables, options)
        // User is ALREADY in Classic WP -> Show high-visibility Return to Modern CMS Hub
        $wp_admin_bar->add_node(array(
            'id'    => 'mvc-mode-toggle',
            'title' => '<span class="ab-icon dashicons dashicons-shield" style="font-family:dashicons; margin-top:2px; color:#D4AF37;"></span><span class="ab-label" style="font-weight:700; color:#FFE082;">' . __('✨ Return to Modern CMS Hub', 'mvc-college') . '</span>',
            'href'  => $return_url,
            'meta'  => array(
                'title' => __('Return to M.V. College Executive Command Center', 'mvc-college')
            )
        ));

        // Sub-menu Option 1: Return to Modern Command Center
        $wp_admin_bar->add_node(array(
            'parent' => 'mvc-mode-toggle',
            'id'     => 'mvc-sub-return-modern',
            'title'  => __('🏛️ Open Command Center Desk', 'mvc-college'),
            'href'   => $return_url,
        ));

        // Sub-menu Option 2: Default Mode preference toggle
        if ($current_mode === 'modern') {
            $toggle_legacy_url = wp_nonce_url(admin_url('index.php?mvc_toggle_mode=legacy'), 'mvc_toggle_mode_nonce');
            $wp_admin_bar->add_node(array(
                'parent' => 'mvc-mode-toggle',
                'id'     => 'mvc-sub-toggle-legacy',
                'title'  => __('⚙️ Set Classic WP as Default View', 'mvc-college'),
                'href'   => $toggle_legacy_url,
            ));
        } else {
            $toggle_modern_url = wp_nonce_url(admin_url('admin.php?page=mvc-command-center&mvc_toggle_mode=modern'), 'mvc_toggle_mode_nonce');
            $wp_admin_bar->add_node(array(
                'parent' => 'mvc-mode-toggle',
                'id'     => 'mvc-sub-toggle-modern',
                'title'  => __('✨ Set Modern CMS as Default View', 'mvc-college'),
                'href'   => $toggle_modern_url,
            ));
        }
    }
}

/**
 * Styling for the Admin Bar Mode Switcher Pill.
 */
add_action('admin_head', 'mvc_admin_bar_mode_switcher_styles');
function mvc_admin_bar_mode_switcher_styles() {
    ?>
    <style id="mvc-admin-mode-switcher-css">
        #wpadminbar #wp-admin-bar-mvc-mode-toggle > .ab-item {
            background: rgba(212, 175, 55, 0.16) !important;
            border: 1px solid rgba(212, 175, 55, 0.35) !important;
            border-radius: 4px;
            margin-top: 2px;
            height: 28px;
            line-height: 26px;
            padding: 0 10px !important;
            transition: all 0.2s ease;
        }
        #wpadminbar #wp-admin-bar-mvc-mode-toggle:hover > .ab-item {
            background: #D4AF37 !important;
            border-color: #D4AF37 !important;
            color: #001A38 !important;
        }
        #wpadminbar #wp-admin-bar-mvc-mode-toggle:hover .ab-label,
        #wpadminbar #wp-admin-bar-mvc-mode-toggle:hover .ab-icon {
            color: #001A38 !important;
        }
        #wpadminbar #wp-admin-bar-mvc-mode-toggle .ab-sub-wrapper {
            background: #00152e !important;
            border: 1px solid rgba(212, 175, 55, 0.3) !important;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3) !important;
        }
        #wpadminbar #wp-admin-bar-mvc-mode-toggle .ab-sub-wrapper a {
            color: #E2E8F0 !important;
        }
        #wpadminbar #wp-admin-bar-mvc-mode-toggle .ab-sub-wrapper a:hover {
            color: #FFE082 !important;
            background: rgba(212, 175, 55, 0.15) !important;
        }
    </style>
    <?php
}

/**
 * Render contextual "Return to Modern Hub" banner at the top of Classic WordPress post edit screens.
 */
add_action('edit_form_top', 'mvc_render_editor_return_banner');
function mvc_render_editor_return_banner($post) {
    if (!current_user_can('mvc_access_admin')) {
        return;
    }

    $roles      = (array) wp_get_current_user()->roles;
    $is_hod     = in_array('department_head', $roles, true) || in_array('department_editor', $roles, true);
    $is_modern  = (mvc_get_user_admin_mode() === 'modern');

    // Display banner for modern mode users and all departmental heads
    if (!$is_modern && !$is_hod) {
        return;
    }
    $return_url = $is_hod 
        ? admin_url('admin.php?page=mvc-command-center&section=my-department') 
        : admin_url('admin.php?page=mvc-command-center');

    $post_type = get_post_type($post);
    $type_obj  = get_post_type_object($post_type);
    $label     = $type_obj ? $type_obj->labels->singular_name : 'Record';
    ?>
    <div class="mvc-editor-return-bar" style="margin: 12px 0 18px 0; padding: 12px 18px; background: linear-gradient(135deg, #00152e 0%, #002147 100%); border: 1px solid rgba(212, 175, 55, 0.45); border-radius: 8px; display: flex; justify-content: space-between; align-items: center; color: #FFFFFF; box-shadow: 0 4px 12px rgba(0,33,71,0.18);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 20px;">🏛️</span>
            <div>
                <div style="font-size: 13.5px; font-weight: 700; color: #FFFFFF;">
                    Editing <?php echo esc_html($label); ?> in Classic WordPress Editor
                </div>
                <div style="font-size: 11.5px; color: #CBD5E1; margin-top: 1px;">
                    All modifications save directly to the institutional database. You can return to the Command Center at any time.
                </div>
            </div>
        </div>
        <a href="<?php echo esc_url($return_url); ?>" class="button button-primary" style="background: linear-gradient(135deg, #D4AF37, #B48200); border: none; color: #001A38; font-weight: 800; font-size: 12.5px; padding: 6px 16px; height: auto; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 3px 8px rgba(212,175,55,0.35); flex-shrink: 0;">
            <span>&larr; Return to Modern Hub</span>
        </a>
    </div>
    <?php
}
