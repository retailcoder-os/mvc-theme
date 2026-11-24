<?php
/**
 * RetailCoder StoreFront [RC:StoreFront] Update Shield & Theme Protection Engine
 * 
 * Version 1.0.0
 * Author: RetailCoder (https://retailcoder.com)
 * 
 * Prevents inadvertent parent theme (Astra) or dependency updates from
 * overwriting custom templates, modifying responsive hooks, or breaking
 * institutional layouts. Provides an instant toggle in Command Center Settings.
 * 
 * @package RetailCoder_StoreFront
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if the RetailCoder StoreFront update lock is active.
 *
 * Defaults to true (Safe Mode Locked).
 *
 * @return bool True if updates are frozen/locked, false if unlocked for maintenance.
 */
function mvc_is_update_locked() {
    $settings = get_option('mvc_settings', array());
    if (!isset($settings['lock_updates'])) {
        return true; // Safe default: Locked
    }
    return (bool) $settings['lock_updates'];
}

/**
 * Filter WordPress theme update transients.
 * Blinds WordPress update checks for Astra and RetailCoder StoreFront child theme.
 *
 * @param object $transient Update transient
 * @return object Modified transient
 */
function mvc_shield_filter_theme_updates($transient) {
    if (!is_object($transient) || !mvc_is_update_locked()) {
        return $transient;
    }

    $protected_slugs = array('astra', 'astra-child', 'mvc-theme', 'retailcoder-storefront', 'retailcoder-edu');

    foreach ($protected_slugs as $slug) {
        if (isset($transient->response[$slug])) {
            unset($transient->response[$slug]);
        }
    }

    return $transient;
}
add_filter('site_transient_update_themes', 'mvc_shield_filter_theme_updates');
add_filter('transient_update_themes', 'mvc_shield_filter_theme_updates');
add_filter('pre_set_site_transient_update_themes', 'mvc_shield_filter_theme_updates');

/**
 * Prevent background auto-updates for themes when lock is active.
 *
 * @param bool $update Whether to update the theme.
 * @param object $item Theme update object.
 * @return bool
 */
function mvc_shield_auto_update_theme($update, $item) {
    if (mvc_is_update_locked()) {
        return false;
    }
    return $update;
}
add_filter('auto_update_theme', 'mvc_shield_auto_update_theme', 99, 2);

/**
 * Prevent background auto-updates for core and plugins when lock is active.
 *
 * @param bool $update
 * @return bool
 */
function mvc_shield_auto_update_plugins($update, $item) {
    if (mvc_is_update_locked()) {
        return false;
    }
    return $update;
}
add_filter('auto_update_plugin', 'mvc_shield_auto_update_plugins', 99, 2);

function mvc_shield_auto_update_core($update, $item) {
    if (mvc_is_update_locked()) {
        return false;
    }
    return $update;
}
add_filter('auto_update_core', 'mvc_shield_auto_update_core', 99, 2);

/**
 * Hard abort if an upgrader attempts to overwrite Astra or RetailCoder StoreFront while locked.
 *
 * @param bool|WP_Error $return
 * @param array $hook_extra Upgrader parameters
 * @return bool|WP_Error
 */
function mvc_shield_pre_install_guard($return, $hook_extra) {
    if (!mvc_is_update_locked()) {
        return $return;
    }

    if (isset($hook_extra['theme'])) {
        $theme_slug = $hook_extra['theme'];
        if (in_array($theme_slug, array('astra', 'astra-child', 'mvc-theme', 'retailcoder-storefront', 'retailcoder-edu'), true)) {
            return new WP_Error(
                'rc_storefront_theme_locked',
                __('RetailCoder StoreFront [RC:StoreFront] Shield: Theme updates are locked to protect customized institutional templates. Toggle OFF the update lock in Command Center Settings if you wish to perform maintenance.', 'retailcoder-storefront')
            );
        }
    }

    return $return;
}
add_filter('upgrader_pre_install', 'mvc_shield_pre_install_guard', 10, 2);

/**
 * Display locked status in Themes list (/wp-admin/themes.php) for auto-updates.
 *
 * @param string $html Existing HTML
 * @param string $stylesheet Theme stylesheet
 * @param WP_Theme $theme Theme object
 * @return string
 */
function mvc_shield_theme_auto_update_html($html, $stylesheet, $theme) {
    if (in_array($stylesheet, array('astra', 'astra-child', 'mvc-theme'), true) && mvc_is_update_locked()) {
        $icon_url = get_stylesheet_directory_uri() . '/assets/images/retailcoder-logo.png';
        return sprintf(
            '<span style="display:inline-flex; align-items:center; gap:5px; color:#002147; font-weight:600;"><img src="%s" width="16" height="16" style="vertical-align:middle;"> %s <span style="font-size:11px; background:#D4AF37; color:#001A38; padding:1px 5px; border-radius:3px; font-weight:800;">RC:StoreFront</span></span>',
            esc_url($icon_url),
            esc_html__('Updates Locked (Safe Mode)', 'retailcoder-storefront')
        );
    }
    return $html;
}
add_filter('theme_auto_update_setting_html', 'mvc_shield_theme_auto_update_html', 10, 3);

/**
 * Display administrative notice on themes.php and update-core.php when locked.
 */
function mvc_shield_admin_notices() {
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->id, array('themes', 'update-core'), true)) {
        return;
    }

    $is_locked = mvc_is_update_locked();
    $icon_url = get_stylesheet_directory_uri() . '/assets/images/retailcoder-logo.png';
    $settings_url = admin_url('admin.php?page=mvc-command-center#settings');

    if ($is_locked) {
        ?>
        <div class="notice notice-info" style="border-left:4px solid #002147; background:#F8FAFC; padding:12px 16px; margin:15px 0; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="<?php echo esc_url($icon_url); ?>" alt="RetailCoder" width="32" height="32" style="background:#ffffff; padding:2px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <div>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <strong style="color:#002147; font-size:14px;">RetailCoder StoreFront [RC:StoreFront] Shield Active</strong>
                        <span style="font-size:10px; background:#10B981; color:#ffffff; font-weight:700; padding:2px 6px; border-radius:10px; text-transform:uppercase;">Safe Mode</span>
                    </div>
                    <div style="font-size:12.5px; color:#475569; margin-top:2px;">
                        Theme and dependency updates are locked to protect customized institutional templates, styling, and workflows.
                    </div>
                </div>
            </div>
            <div>
                <a href="<?php echo esc_url($settings_url); ?>" class="button button-secondary" style="font-size:12px; font-weight:600; border-color:#CBD5E1;">
                    Toggle in Command Center Settings →
                </a>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="notice notice-warning" style="border-left:4px solid #F59E0B; background:#FFFBEB; padding:12px 16px; margin:15px 0; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="<?php echo esc_url($icon_url); ?>" alt="RetailCoder" width="32" height="32" style="background:#ffffff; padding:2px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <div>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <strong style="color:#92400E; font-size:14px;">⚠️ RetailCoder StoreFront [RC:StoreFront] Maintenance Mode</strong>
                        <span style="font-size:10px; background:#F59E0B; color:#ffffff; font-weight:700; padding:2px 6px; border-radius:10px; text-transform:uppercase;">Updates Allowed</span>
                    </div>
                    <div style="font-size:12.5px; color:#78350F; margin-top:2px;">
                        Theme and dependency updates are currently UNLOCKED. Remember to re-lock after maintenance.
                    </div>
                </div>
            </div>
            <div>
                <a href="<?php echo esc_url($settings_url); ?>" class="button button-primary" style="font-size:12px; font-weight:600; background:#002147; border-color:#002147;">
                    Re-lock Updates →
                </a>
            </div>
        </div>
        <?php
    }
}
add_action('admin_notices', 'mvc_shield_admin_notices');
