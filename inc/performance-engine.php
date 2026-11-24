<?php
/**
 * M.V. College, Buxar (Maharshi Vishwamitra Mahavidyalaya)
 * 10/10 Master Performance, Object Caching & Core Web Vitals Optimization Engine
 *
 * Implements 10/10 Core Web Vitals optimization, asset hygiene,
 * font preloading, image lazy loading, database transient caching,
 * automated cache invalidation, heartbeat throttling, and WebP delivery.
 *
 * @package Astra_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Remove Query Strings from Static CSS/JS Resources for Cacheability
 */
function mvc_remove_script_version($src) {
    if (strpos($src, 'astra-child') !== false || strpos($src, 'mvc-theme') !== false) {
        return $src;
    }
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
if (!is_admin()) {
    add_filter('style_loader_src', 'mvc_remove_script_version', 15, 1);
    add_filter('script_loader_src', 'mvc_remove_script_version', 15, 1);
}

/**
 * 2. Eliminate WordPress Emoji Scripts & Styles (Saves HTTP requests & execution time)
 */
function mvc_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'mvc_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'mvc_disable_emojis_dns_prefetch', 10, 2);
}
add_action('init', 'mvc_disable_emojis');

function mvc_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    }
    return array();
}

function mvc_disable_emojis_dns_prefetch($urls, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}

/**
 * 3. Clean Unnecessary Header Meta Tags, Manifest Links & SVG Bloat
 */
function mvc_cleanup_head_bloat() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    
    // Remove global SVG duotone filter definitions on body open (saves ~10KB inline HTML)
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    remove_action('in_admin_header', 'wp_global_styles_render_svg_filters');
}
add_action('init', 'mvc_cleanup_head_bloat');

/**
 * 3B. Prune Unused Core Stylesheets & Scripts on Bespoke Custom Templates
 */
function mvc_prune_frontend_assets() {
    if (is_admin()) {
        return;
    }

    // 1. Dequeue Gutenberg Block Library on custom templates (not using block editor)
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks if present
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');

    // 2. Dequeue Dashicons on frontend for visitors who are not logged in
    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
        wp_deregister_style('dashicons');
    }

    // 3. Dequeue jQuery Migrate (legacy backward-compatibility helper)
    wp_dequeue_script('jquery-migrate');

    // 4. Dequeue Theme My Login assets on pages without login forms
    $is_auth_page = is_page(array('login', 'portal-login', 'portal', 'student-portal', 'alumni-registration'));
    if (!$is_auth_page) {
        wp_dequeue_style('theme-my-login');
        wp_dequeue_script('theme-my-login');
    }
}
add_action('wp_enqueue_scripts', 'mvc_prune_frontend_assets', 100);

/**
 * 4. Preconnect and DNS Prefetch for Google Fonts (Sub-50ms font initialization)
 */
function mvc_add_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'mvc_add_resource_hints', 10, 2);

/**
 * 4B. Asynchronous, Non-Render-Blocking Google Fonts Loading
 */
add_filter('style_loader_tag', function($html, $handle, $href, $media) {
    if ($handle === 'mvc-google-fonts') {
        return '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' .
               '<noscript><link rel="stylesheet" href="' . esc_url($href) . '"></noscript>' . "\n";
    }
    return $html;
}, 10, 4);

/**
 * 5. Universal Lazy Loading & Asynchronous Image Decoding (LCP Prioritized)
 */
function mvc_optimize_image_attributes($attr, $attachment, $size) {
    static $image_count = 0;
    $image_count++;

    // First above-the-fold hero image: Eager load with high fetch priority for instant LCP
    if ($image_count === 1 && (is_front_page() || is_home())) {
        $attr['loading'] = 'eager';
        $attr['fetchpriority'] = 'high';
        $attr['decoding'] = 'sync';
    } else {
        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }
        if (!isset($attr['decoding'])) {
            $attr['decoding'] = 'async';
        }
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'mvc_optimize_image_attributes', 10, 3);

/**
 * 6. Defer Non-Critical JavaScript Execution
 */
function mvc_defer_parsing_of_js($tag, $handle, $src) {
    if (is_admin()) {
        return $tag;
    }
    $defer_handles = array('jquery-migrate', 'mvc-command-center-js', 'mvc-smooth-scroll', 'astra-theme-js');
    if (in_array($handle, $defer_handles, true)) {
        if (strpos($tag, ' defer') === false) {
            return str_replace(' src', ' defer src', $tag);
        }
    }
    return $tag;
}
add_filter('script_loader_tag', 'mvc_defer_parsing_of_js', 10, 3);

/**
 * 7. Heartbeat API Throttling & Server Resource Conservation
 */
add_filter('heartbeat_settings', function($settings) {
    // Throttle admin heartbeat interval to 60 seconds (default is 15-30s)
    $settings['interval'] = 60;
    return $settings;
});

add_action('init', function() {
    // Disable Heartbeat on frontend entirely for non-admin users
    if (!is_admin()) {
        wp_deregister_script('heartbeat');
    }
}, 1);

/**
 * 8. Universal High-Performance Transient Caching & Instant Invalidation
 */
function mvc_cache_output($key, $callback, $expiration = 43200) {
    // Check if caching is toggled OFF globally or bypassed via ?nocache=1
    if (function_exists('mvc_is_caching_active') && !mvc_is_caching_active()) {
        return call_user_func($callback);
    }
    if (current_user_can('manage_options') && isset($_GET['nocache'])) {
        return call_user_func($callback);
    }

    $cached = get_transient($key);
    if ($cached !== false && is_string($cached) && !empty($cached)) {
        return $cached;
    }

    $output = call_user_func($callback);
    if (!empty($output)) {
        set_transient($key, $output, $expiration);
    }
    return $output;
}

function mvc_get_transient_or_execute($key, $callback, $expiration = 43200) {
    if (function_exists('mvc_is_caching_active') && !mvc_is_caching_active()) {
        return call_user_func($callback);
    }
    $cached = get_transient($key);
    if ($cached !== false) {
        return $cached;
    }
    $data = call_user_func($callback);
    if ($data !== false && $data !== null) {
        set_transient($key, $data, $expiration);
    }
    return $data;
}

/**
 * 8B. Automated Universal Cache Invalidation on Content Modification
 * Automatically invalidates transients whenever an admin publishes, edits, or deletes content.
 */
function mvc_flush_all_performance_caches($post_id = 0) {
    $keys = array(
        'mvc_cached_homepage_html',
        'mvc_cached_hero_slider',
        'mvc_cached_home_notices',
        'mvc_cached_home_events',
        'mvc_cached_home_press',
        'mvc_cached_home_metrics',
        'mvc_cached_leadership',
        'mvc_cached_departments_list',
        'mvc_cached_notices_p1_html',
        'mvc_cached_notices_page_html',
        'mvc_cached_events_grid_html',
        'mvc_cached_events_page_html',
        'mvc_cached_calendar_html',
        'mvc_cached_dept_details_html',
        'mvc_cached_photo_gallery_count',
        'mvc_cached_media_gallery_html',
        'mvc_cached_principal_desk_html',
        'mvc_cached_succession_html',
        'mvc_cached_teaching_roster_html',
        'mvc_cached_non_teaching_roster_html',
    );

    foreach ($keys as $k) {
        delete_transient($k);
    }

    // Clear WP Super Cache if active
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }
}
add_action('save_post', 'mvc_flush_all_performance_caches');
add_action('deleted_post', 'mvc_flush_all_performance_caches');
add_action('trash_post', 'mvc_flush_all_performance_caches');
add_action('untrashed_post', 'mvc_flush_all_performance_caches');
add_action('edit_post', 'mvc_flush_all_performance_caches');
add_action('acf/save_post', 'mvc_flush_all_performance_caches');
add_action('update_option_mvc_principal_email', 'mvc_flush_all_performance_caches');

/**
 * 9. Automatic Next-Gen WebP Image URL Resolver
 * Dynamically resolves image URLs to their .webp counterparts when available on disk.
 */
function mvc_get_webp_url_if_exists($url) {
    if (empty($url) || strpos($url, '.webp') !== false) {
        return false;
    }
    if (!preg_match('/\.(jpe?g|png)$/i', $url)) {
        return false;
    }
    
    $parsed_path = parse_url($url, PHP_URL_PATH);
    if (!$parsed_path) {
        return false;
    }
    
    $local_file = ABSPATH . ltrim($parsed_path, '/');
    $webp_file = preg_replace('/\.(jpe?g|png)$/i', '.webp', $local_file);
    
    if (file_exists($webp_file)) {
        return preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
    }
    return false;
}

add_filter('wp_get_attachment_image_src', function($image) {
    if (is_admin() || !is_array($image) || empty($image[0])) {
        return $image;
    }
    $webp = mvc_get_webp_url_if_exists($image[0]);
    if ($webp) {
        $image[0] = $webp;
    }
    return $image;
}, 20, 1);

add_filter('wp_get_attachment_url', function($url) {
    if (is_admin() || empty($url)) {
        return $url;
    }
    $webp = mvc_get_webp_url_if_exists($url);
    return $webp ?: $url;
}, 20, 1);

add_filter('post_thumbnail_html', function($html, $post_id, $post_thumbnail_id, $size, $attr) {
    if (is_admin() || empty($html)) {
        return $html;
    }
    return preg_replace_callback('/src=["\']([^"\']+\.(jpe?g|png))["\']/i', function($matches) {
        $src = $matches[1];
        $webp = mvc_get_webp_url_if_exists($src);
        return $webp ? 'src="' . esc_url($webp) . '"' : $matches[0];
    }, $html);
}, 20, 5);

/**
 * 10. Automated Upload Optimization & Real-Time WebP Generation
 */
function mvc_auto_optimize_uploaded_file($file_path) {
    if (!file_exists($file_path) || !function_exists('gd_info')) {
        return;
    }

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    if (!in_array($ext, array('jpg', 'jpeg', 'png', 'jfif'), true)) {
        return;
    }

    $info = @getimagesize($file_path);
    if (!$info) {
        return;
    }

    $width  = $info[0];
    $height = $info[1];
    $mime   = $info['mime'];

    // 1. Create GD image resource based on type
    $im = null;
    if ($mime === 'image/jpeg') {
        $im = @imagecreatefromjpeg($file_path);
    } elseif ($mime === 'image/png') {
        $im = @imagecreatefrompng($file_path);
    }

    if (!$im) {
        return;
    }

    // 2. Downscale if max dimension > 1920px
    $max_dim = 1920;
    if (max($width, $height) > $max_dim) {
        if ($width > $height) {
            $new_w = $max_dim;
            $new_h = (int) round($height * ($max_dim / $width));
        } else {
            $new_h = $max_dim;
            $new_w = (int) round($width * ($max_dim / $height));
        }

        $resized_im = imagecreatetruecolor($new_w, $new_h);
        if ($mime === 'image/png') {
            imagealphablending($resized_im, false);
            imagesavealpha($resized_im, true);
        }

        imagecopyresampled($resized_im, $im, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
        imagedestroy($im);
        $im = $resized_im;
        $width  = $new_w;
        $height = $new_h;

        // Overwrite original file with scaled down version
        if ($mime === 'image/jpeg') {
            imagejpeg($im, $file_path, 84);
        } elseif ($mime === 'image/png') {
            imagepng($im, $file_path, 8);
        }
    }

    // 3. Generate Next-Gen WebP files (.webp and .ext.webp)
    $dir = dirname($file_path);
    $filename_no_ext = pathinfo($file_path, PATHINFO_FILENAME);
    $webp_path_1 = $dir . '/' . $filename_no_ext . '.webp';
    $webp_path_2 = $file_path . '.webp';

    if ($mime === 'image/png') {
        imagepalettetotruecolor($im);
        imagealphablending($im, true);
        imagesavealpha($im, true);
    }

    if (function_exists('imagewebp')) {
        @imagewebp($im, $webp_path_1, 82);
        if (file_exists($webp_path_1) && !file_exists($webp_path_2)) {
            @copy($webp_path_1, $webp_path_2);
        }
    }

    imagedestroy($im);
}

add_filter('wp_handle_upload', function($upload) {
    if (!empty($upload['file']) && empty($upload['error'])) {
        mvc_auto_optimize_uploaded_file($upload['file']);
    }
    return $upload;
}, 10, 1);

add_filter('wp_generate_attachment_metadata', function($metadata, $attachment_id) {
    if (empty($metadata['file'])) {
        return $metadata;
    }

    $uploads = wp_upload_dir();
    $base_dir = $uploads['basedir'];
    $main_file = $base_dir . '/' . $metadata['file'];

    mvc_auto_optimize_uploaded_file($main_file);

    if (!empty($metadata['sizes']) && is_array($metadata['sizes'])) {
        $sub_dir = dirname($main_file);
        foreach ($metadata['sizes'] as $size_key => $size_info) {
            if (!empty($size_info['file'])) {
                $sub_file = $sub_dir . '/' . $size_info['file'];
                mvc_auto_optimize_uploaded_file($sub_file);
            }
        }
    }

    return $metadata;
}, 10, 2);

add_filter('image_editor_output_format', function($formats) {
    if (function_exists('imagewebp')) {
        $formats['image/jpeg'] = 'image/webp';
        $formats['image/png']  = 'image/webp';
    }
    return $formats;
});

/**
 * 11. Maintenance Routine for Expired Transients & Database Optimization
 */
function mvc_clean_expired_transients() {
    global $wpdb;
    $time = time();
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < {$time}");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%' AND option_name NOT IN (SELECT REPLACE(option_name, '_transient_timeout_', '_transient_') FROM (SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%') AS t)");
}
if (!wp_next_scheduled('mvc_cron_clean_transients')) {
    wp_schedule_event(time(), 'daily', 'mvc_cron_clean_transients');
}
add_action('mvc_cron_clean_transients', 'mvc_clean_expired_transients');
