<?php
/**
 * M.V. College, Buxar (Maharshi Vishwamitra Mahavidyalaya)
 * Universal Caching Control & Live Edit Mode Switcher (10/10 Standard)
 *
 * Provides a 1-click persistent toggle in the WordPress Admin Bar and Admin Hub
 * allowing administrators and editors to pause/resume all static and transient caching.
 *
 * @package Astra_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Get Current Global Caching State ('enabled' or 'disabled')
 */
function mvc_get_caching_state() {
    return get_option('mvc_caching_state', 'enabled');
}

/**
 * 2. Check if Caching is Active for Current Request
 */
function mvc_is_caching_active() {
    // 1. Check global toggle
    if (mvc_get_caching_state() === 'disabled') {
        return false;
    }

    // 2. Check per-request bypass parameter for logged-in users (?nocache=1 or ?cache=bypass)
    if (is_user_logged_in() && (isset($_GET['nocache']) || (isset($_GET['cache']) && $_GET['cache'] === 'bypass'))) {
        return false;
    }

    return true;
}

/**
 * 3. Set Global Caching State and Synchronize Server Config
 */
function mvc_set_caching_state($target_state) {
    if (!in_array($target_state, array('enabled', 'disabled'), true)) {
        return false;
    }

    $old_state = mvc_get_caching_state();
    update_option('mvc_caching_state', $target_state);

    // Synchronize WP Super Cache configuration
    $cache_config_file = WP_CONTENT_DIR . '/wp-cache-config.php';
    if (file_exists($cache_config_file)) {
        $cfg = file_get_contents($cache_config_file);
        if ($target_state === 'disabled') {
            $cfg = preg_replace('/\$cache_enabled\s*=\s*true;/i', '$cache_enabled = false;', $cfg);
        } else {
            $cfg = preg_replace('/\$cache_enabled\s*=\s*false;/i', '$cache_enabled = true;', $cfg);
        }
        @file_put_contents($cache_config_file, $cfg);
    }

    // Flush all transient and static caches
    mvc_purge_all_caches_deep();

    // Log in audit log if available
    if (function_exists('mvc_audit')) {
        mvc_audit('cache_state_changed', 'system', 0, array(
            'old_state' => $old_state,
            'new_state' => $target_state,
            'user'      => wp_get_current_user()->user_login
        ));
    }

    return true;
}

/**
 * 4. Deep Purge of All Static HTML & Transient Caches
 */
function mvc_purge_all_caches_deep() {
    // 1. Purge Theme Transient Caches
    if (function_exists('mvc_flush_all_performance_caches')) {
        mvc_flush_all_performance_caches();
    }

    // 2. Clear WP Super Cache Directory
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }

    $supercache_dir = WP_CONTENT_DIR . '/cache/supercache';
    if (is_dir($supercache_dir)) {
        mvc_recursive_delete_dir($supercache_dir);
    }
}

/**
 * Helper to recursively clear a directory
 */
function mvc_recursive_delete_dir($dir) {
    if (!is_dir($dir)) return;
    $items = @scandir($dir);
    if (!is_array($items)) return;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            mvc_recursive_delete_dir($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}

/**
 * 5. Handle Caching Action Dispatcher (Toggle & Flush)
 */
add_action('init', 'mvc_handle_cache_action_dispatch');
function mvc_handle_cache_action_dispatch() {
    if (!isset($_GET['mvc_cache_action'])) {
        return;
    }

    if (!is_user_logged_in() || (!current_user_can('manage_options') && !current_user_can('edit_posts'))) {
        return;
    }

    check_admin_referer('mvc_cache_action_nonce');

    $action = sanitize_key($_GET['mvc_cache_action']);
    $redirect_url = remove_query_arg(array('mvc_cache_action', '_wpnonce'), wp_get_referer() ?: home_url('/'));

    if ($action === 'toggle_on') {
        mvc_set_caching_state('enabled');
        $redirect_url = add_query_arg('mvc_cache_msg', 'enabled', $redirect_url);
    } elseif ($action === 'toggle_off') {
        mvc_set_caching_state('disabled');
        $redirect_url = add_query_arg('mvc_cache_msg', 'disabled', $redirect_url);
    } elseif ($action === 'purge_all') {
        mvc_purge_all_caches_deep();
        $redirect_url = add_query_arg('mvc_cache_msg', 'purged', $redirect_url);
    }

    wp_safe_redirect($redirect_url);
    exit;
}

/**
 * 6. Inject High-Visibility Cache Control Node into WordPress Admin Bar
 */
add_action('admin_bar_menu', 'mvc_admin_bar_caching_control_node', 80);
function mvc_admin_bar_caching_control_node($wp_admin_bar) {
    if (!is_user_logged_in() || (!current_user_can('manage_options') && !current_user_can('edit_posts'))) {
        return;
    }

    $state = mvc_get_caching_state();
    $is_active = ($state === 'enabled');

    // Build Action URLs with Nonce
    $current_req = $_SERVER['REQUEST_URI'] ?? '/';
    $base_action_url = add_query_arg('_wpnonce', wp_create_nonce('mvc_cache_action_nonce'), home_url($current_req));

    $toggle_on_url  = add_query_arg('mvc_cache_action', 'toggle_on', $base_action_url);
    $toggle_off_url = add_query_arg('mvc_cache_action', 'toggle_off', $base_action_url);
    $purge_url      = add_query_arg('mvc_cache_action', 'purge_all', $base_action_url);

    // Bypassed URL
    $bypass_url = add_query_arg('nocache', '1', home_url($current_req));

    // Colors & Badges
    if ($is_active) {
        $badge_style = 'background: #10B981; color: #FFFFFF; font-weight: 700; padding: 2px 10px; border-radius: 12px; font-size: 11px; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);';
        $title_html  = '<span style="' . $badge_style . '">⚡ Cache: ACTIVE</span>';
    } else {
        $badge_style = 'background: #EF4444; color: #FFFFFF; font-weight: 700; padding: 2px 10px; border-radius: 12px; font-size: 11px; display: inline-flex; align-items: center; gap: 5px; animation: mvcPulse 1.8s infinite; box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);';
        $title_html  = '<span style="' . $badge_style . '">🛑 Cache: PAUSED</span>';
    }

    // 1. Parent Node
    $wp_admin_bar->add_node(array(
        'id'    => 'mvc-cache-toggle',
        'title' => $title_html,
        'href'  => $is_active ? $toggle_off_url : $toggle_on_url,
        'meta'  => array(
            'title' => $is_active ? __('Click to PAUSE caching for real-time live editing', 'mvc-child') : __('Click to ACTIVATE high-speed caching', 'mvc-child')
        )
    ));

    // 2. Sub-Item: Toggle State Action
    if ($is_active) {
        $wp_admin_bar->add_node(array(
            'id'     => 'mvc-cache-toggle-action',
            'parent' => 'mvc-cache-toggle',
            'title'  => '⏸️ ' . __('Turn Caching OFF (Live Editing Mode)', 'mvc-child'),
            'href'   => $toggle_off_url,
            'meta'   => array('title' => __('Disable all static and transient caching for live updates', 'mvc-child'))
        ));
    } else {
        $wp_admin_bar->add_node(array(
            'id'     => 'mvc-cache-toggle-action',
            'parent' => 'mvc-cache-toggle',
            'title'  => '▶️ ' . __('Turn Caching ON (High-Speed Mode)', 'mvc-child'),
            'href'   => $toggle_on_url,
            'meta'   => array('title' => __('Activate sub-10ms caching for maximum speed', 'mvc-child'))
        ));
    }

    // 3. Sub-Item: Purge All Caches
    $wp_admin_bar->add_node(array(
        'id'     => 'mvc-cache-purge-action',
        'parent' => 'mvc-cache-toggle',
        'title'  => '🔄 ' . __('Flush All Static & Transient Caches', 'mvc-child'),
        'href'   => $purge_url,
        'meta'   => array('title' => __('Wipe all cached HTML and transient buffers immediately', 'mvc-child'))
    ));

    // 4. Sub-Item: View Fresh Uncached Page
    $wp_admin_bar->add_node(array(
        'id'     => 'mvc-cache-bypass-action',
        'parent' => 'mvc-cache-toggle',
        'title'  => '⚡ ' . __('Open Fresh Live View (?nocache=1)', 'mvc-child'),
        'href'   => $bypass_url,
        'meta'   => array('title' => __('Load this page bypassing all cache buffers', 'mvc-child'))
    ));
}

/**
 * 7. Admin & Frontend Notification Toast upon Caching Action
 */
add_action('wp_head', 'mvc_render_cache_toast_banner');
add_action('admin_head', 'mvc_render_cache_toast_banner');
function mvc_render_cache_toast_banner() {
    if (!is_user_logged_in() || empty($_GET['mvc_cache_msg'])) {
        return;
    }

    $msg_type = sanitize_key($_GET['mvc_cache_msg']);
    $title = '';
    $desc = '';
    $bg = '#002147';
    $border = '#D4AF37';

    if ($msg_type === 'enabled') {
        $title = '⚡ High-Speed Caching Activated';
        $desc = 'Static HTML and transient database caching are now ACTIVE. Sub-10ms delivery enabled.';
        $bg = '#065F46';
        $border = '#10B981';
    } elseif ($msg_type === 'disabled') {
        $title = '🛑 Caching Paused (Live Edit Mode)';
        $desc = 'Caching has been turned OFF. All pages now execute fresh real-time database queries.';
        $bg = '#991B1B';
        $border = '#EF4444';
    } elseif ($msg_type === 'purged') {
        $title = '🔄 Cache Flushed Successfully';
        $desc = 'All static HTML pages and transient database buffers were purged.';
        $bg = '#1E3A8A';
        $border = '#60A5FA';
    }

    if (!$title) return;
    ?>
    <div id="mvcCacheToast" style="position: fixed; bottom: 24px; right: 24px; z-index: 999999; background: <?php echo $bg; ?>; color: #FFFFFF; border: 2px solid <?php echo $border; ?>; border-radius: 12px; padding: 16px 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); max-width: 420px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; display: flex; align-items: flex-start; gap: 12px; animation: mvcSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
        <div style="font-size: 22px; line-height: 1;">⚡</div>
        <div style="flex: 1;">
            <div style="font-weight: 700; font-size: 14px; margin-bottom: 4px;"><?php echo esc_html($title); ?></div>
            <div style="font-size: 12px; opacity: 0.9; line-height: 1.4;"><?php echo esc_html($desc); ?></div>
        </div>
        <button type="button" onclick="document.getElementById('mvcCacheToast').style.display='none';" style="background: none; border: none; color: #FFFFFF; font-size: 18px; cursor: pointer; opacity: 0.7; padding: 0; line-height: 1;">&times;</button>
    </div>
    <script>
        setTimeout(function() {
            var t = document.getElementById('mvcCacheToast');
            if (t) {
                t.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                t.style.opacity = '0';
                t.style.transform = 'translateY(20px)';
                setTimeout(function() { t.remove(); }, 400);
            }
        }, 5000);
    </script>
    <style>
        @keyframes mvcSlideIn {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes mvcPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.75; }
        }
    </style>
    <?php
}
