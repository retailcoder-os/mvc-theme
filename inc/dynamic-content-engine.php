<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Master Dynamic Content Engine (Universal 10/10 Standard)
 * 
 * Provides centralized, fail-safe dynamic data access for all 63 pages.
 * Resolution Hierarchy:
 *   1. Custom Post Meta (`get_post_meta()`)
 *   2. Custom Options API (`get_option()`)
 *   3. Native ACF Free Fields (`get_field()`)
 *   4. Authentic Institutional Benchmark Fallback (Zero live dependency, zero crashes)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Universal Getter for Principal Official Email
 *
 * @return string
 */
function mvc_get_principal_email() {
    // 1. Direct Page Meta from Principal Desk (Page ID 256)
    $email = get_post_meta(256, 'principal_email', true);
    if (!empty($email) && is_email($email)) {
        return $email;
    }
    // 2. Global Option
    $opt = get_option('mvc_principal_email');
    if (!empty($opt) && is_email($opt)) {
        return $opt;
    }
    // 3. Fallback to official institutional principal address
    return 'mvcollegebuxar@gmail.com';
}

/**
 * Universal Getter for Scalar Page Metadata (Strings, Numbers, URLs)
 *
 * @param int    $post_id  The WordPress Page / Post ID
 * @param string $key      The meta key
 * @param mixed  $fallback The authentic institutional default fallback
 * @return mixed
 */
function mvc_get_page_meta($post_id, $key, $fallback = '') {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return $fallback;
    }

    // 1. Direct Post Meta
    $val = get_post_meta($post_id, $key, true);
    if ($val !== '' && $val !== false && $val !== null) {
        return $val;
    }

    // 2. Page-Scoped Option Storage (mvc_page_{id}_{key})
    $opt_key = 'mvc_page_' . $post_id . '_' . $key;
    $opt_val = get_option($opt_key, null);
    if ($opt_val !== null && $opt_val !== '') {
        return $opt_val;
    }

    // 3. Global Option Storage (mvc_global_settings)
    $global_opts = get_option('mvc_global_settings', array());
    if (is_array($global_opts) && isset($global_opts[$key]) && $global_opts[$key] !== '') {
        return $global_opts[$key];
    }

    // 4. ACF Free field check if available
    if (function_exists('get_field')) {
        $acf_val = get_field($key, $post_id);
        if ($acf_val !== '' && $acf_val !== false && $acf_val !== null) {
            return $acf_val;
        }
    }

    // 5. Authentic Benchmark Fallback
    return $fallback;
}

/**
 * Universal Getter for Structured Data Arrays (Rosters, Lists, Repeater Cards)
 *
 * @param int    $post_id        The WordPress Page / Post ID
 * @param string $key            The meta key
 * @param array  $fallback_array The authentic institutional default array
 * @return array
 */
function mvc_get_page_array($post_id, $key, $fallback_array = array()) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return $fallback_array;
    }

    $val = get_post_meta($post_id, $key, true);
    if (!empty($val)) {
        if (is_array($val)) {
            return $val;
        }
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }
    }

    // Option fallback
    $opt_val = get_option('mvc_page_' . $post_id . '_' . $key, null);
    if (!empty($opt_val)) {
        if (is_array($opt_val)) {
            return $opt_val;
        }
        if (is_string($opt_val)) {
            $decoded = json_decode($opt_val, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
    }

    return $fallback_array;
}

/**
 * Universal Getter for Global Settings
 *
 * @param string $key      The setting key
 * @param mixed  $fallback The default fallback
 * @return mixed
 */
function mvc_get_global_setting($key, $fallback = '') {
    // 1. Check ACF Options page first (e.g. college-global-settings)
    if (function_exists('get_field')) {
        $acf_val = get_field($key, 'option');
        if (!empty($acf_val)) {
            return $acf_val;
        }
    }

    // 2. Check dedicated global settings array
    $options = get_option('mvc_global_settings', array());
    if (is_array($options) && isset($options[$key]) && $options[$key] !== '') {
        return $options[$key];
    }

    // 3. Check direct individual option
    $direct_opt = get_option('mvc_' . $key, null);
    if ($direct_opt !== null && $direct_opt !== '') {
        return $direct_opt;
    }

    // 4. If fallback explicitly provided and non-empty, return it
    if (!empty($fallback)) {
        return $fallback;
    }

    // 5. Dynamic fallback for email keys: use Principal email
    if (strpos($key, 'email') !== false) {
        if ($key === 'principal_email') {
            return mvc_get_principal_email();
        }
        $p_email = mvc_get_principal_email();
        if (!empty($p_email)) {
            return $p_email;
        }
    }

    // Standard Authentic Benchmarks
    $benchmarks = array(
        'college_name'       => get_option('blogname') ?: 'Maharshi Vishwamitra Mahavidyalaya, Buxar',
        'college_name_hi'    => 'महर्षि विश्वामित्र महाविद्यालय, बक्सर',
        'aishe_code'          => 'C-27158',
        'college_phone'      => '06183-222035',
        'college_email'      => 'mvcollegebuxar@gmail.com',
        'principal_email'    => mvc_get_principal_email(),
        'college_address'    => 'Charitravan, Buxar – 802101, Bihar (India)',
        'anti_ragging_phone' => '1800-180-5522',
        'designed_by_name'   => 'RetailCoder',
        'designed_by_url'    => 'https://retailcoder.com',
        'managed_by_name'    => 'Swadeshi Store',
        'managed_by_url'     => 'https://swadeshihost.com',
        'total_visitors'     => (int) get_option('mvc_total_visitors', 166150),
        'fb_url'             => 'https://www.facebook.com/mvcollegebuxar',
        'tw_url'             => 'https://twitter.com/mvcollegebuxar',
        'ig_url'             => 'https://www.instagram.com/mvcollegebuxar',
        'yt_url'             => 'https://www.youtube.com/channel/UChAoUFiLfqO61Q22MTWd0ag',
        'li_url'             => 'https://www.linkedin.com/school/mvcollegebuxar'
    );

    return $benchmarks[$key] ?? $fallback;
}

/**
 * Universal Setter for Page Metadata
 */
function mvc_set_page_meta($post_id, $key, $value) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return false;
    }

    if (is_array($value)) {
        return update_post_meta($post_id, $key, $value);
    }

    $sanitized = wp_kses_post($value);
    return update_post_meta($post_id, $key, $sanitized);
}

/**
 * Universal Setter for Global Settings
 */
function mvc_set_global_setting($key, $value) {
    $options = get_option('mvc_global_settings', array());
    if (!is_array($options)) {
        $options = array();
    }
    $options[$key] = is_array($value) ? $value : sanitize_text_field($value);
    return update_option('mvc_global_settings', $options);
}
