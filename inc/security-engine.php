<?php
/**
 * RetailCoder StoreFront [RC:StoreFront] Hardened Security & Anti-Bot Defense Engine
 *
 * Implements HTTP security headers, XML-RPC suppression, user enumeration protection,
 * generic login security, anti-bot honeypot shields, and malicious request filtering.
 *
 * @package Astra_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Inject Modern HTTP Security Headers
 */
function mvc_send_security_headers() {
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
    }
}
add_action('send_headers', 'mvc_send_security_headers');

/**
 * 2. Completely Disable XML-RPC (Blocks brute-force amplification attacks)
 */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});

/**
 * 3. Prevent User Enumeration (Blocks bot scanning for usernames)
 */
function mvc_block_user_enumeration() {
    if (!is_admin() && isset($_REQUEST['author'])) {
        wp_safe_redirect(home_url(), 301);
        exit;
    }
}
add_action('init', 'mvc_block_user_enumeration');

// Restrict REST API user endpoint to authenticated requests
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in() && strpos($_SERVER['REQUEST_URI'] ?? '', '/wp/v2/users') !== false) {
        return new WP_Error('rest_forbidden', __('Access to user directory is restricted to authenticated users.', 'mvc-child'), array('status' => 401));
    }
    return $result;
});

/**
 * 4. Obfuscate Login Errors (Do not reveal valid usernames to attackers)
 */
add_filter('login_errors', function() {
    return 'Invalid login credentials. Please check your username and password or contact administrator.';
});

/**
 * 5. Universal Anti-Bot Honeypot & Timestamp Gate Generator
 *
 * Real users do not fill the hidden honeypot field and take > 2 seconds to complete a form.
 */
function mvc_render_honeypot_fields() {
    $now = time();
    $nonce = wp_create_nonce('mvc_bot_shield_' . $now);
    ?>
    <div style="position: absolute !important; left: -9999px !important; top: -9999px !important; opacity: 0 !important; height: 0 !important; width: 0 !important; pointer-events: none !important;" aria-hidden="true">
        <label for="mvc_website_url_hp">Leave this empty if you are human</label>
        <input type="text" name="mvc_website_url_hp" id="mvc_website_url_hp" value="" tabindex="-1" autocomplete="off">
        <input type="hidden" name="mvc_submit_ts" value="<?php echo esc_attr($now); ?>">
        <input type="hidden" name="mvc_bot_nonce" value="<?php echo esc_attr($nonce); ?>">
    </div>
    <?php
}

/**
 * Validate anti-bot submission
 *
 * @param array $data Input POST data
 * @return bool True if clean human, False if detected bot
 */
function mvc_is_clean_human_submission($data = null) {
    if ($data === null) {
        $data = $_POST;
    }

    // 1. Check honeypot field
    if (!empty($data['mvc_website_url_hp'])) {
        error_log('[SECURITY BOT BLOCKED] Honeypot field filled from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        return false;
    }

    // 2. Check submission speed (under 1.5 seconds is automated bot)
    if (isset($data['mvc_submit_ts'])) {
        $submitted_ts = (int) $data['mvc_submit_ts'];
        $elapsed = time() - $submitted_ts;
        if ($elapsed < 2) {
            error_log('[SECURITY BOT BLOCKED] Submission too fast (' . $elapsed . 's) from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
            return false;
        }
    }

    return true;
}

/**
 * 6. Malicious Query String Inspection (Lightweight Pre-WAF)
 */
function mvc_filter_malicious_requests() {
    $query_string = $_SERVER['QUERY_STRING'] ?? '';
    if (empty($query_string)) {
        return;
    }

    $suspicious_patterns = array(
        'union+select',
        'union%20select',
        'base64_decode',
        'concat(',
        '<script',
        '%3Cscript',
        'eval(',
        '/etc/passwd'
    );

    $lower_qs = strtolower($query_string);
    foreach ($suspicious_patterns as $pattern) {
        if (strpos($lower_qs, $pattern) !== false) {
            wp_die(
                '<h1>403 Forbidden - Security Shield Active</h1><p>Your request has been flagged by the Institutional Security Firewall.</p>',
                'Forbidden',
                array('response' => 403)
            );
        }
    }
}
add_action('init', 'mvc_filter_malicious_requests', 1);
