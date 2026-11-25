<?php
/**
 * MV College Staff Command Center Bootstrap
 * 
 * Registers the Command Center menu, enqueues self-contained assets,
 * and renders the authenticated shell.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'mvc_register_command_center_menu');
function mvc_register_command_center_menu() {
    // Hidden from sidebar menu (parent_slug = null) while fully accessible via topbar toggle
    add_submenu_page(
        null,
        __('Command Center • M.V. College', 'mvc-college'),
        __('Command Center', 'mvc-college'),
        'mvc_access_admin',
        'mvc-command-center',
        'mvc_render_command_center_shell'
    );
    // Backward compatibility alias for 'mvcollege-command-center'
    add_submenu_page(
        null,
        __('Command Center • M.V. College', 'mvc-college'),
        __('Command Center', 'mvc-college'),
        'mvc_access_admin',
        'mvcollege-command-center',
        'mvc_render_command_center_shell'
    );
}

/**
 * Enforce clean application body class for fullscreen takeover.
 */
add_filter('admin_body_class', 'mvc_command_center_admin_body_class');
function mvc_command_center_admin_body_class($classes) {
    if (isset($_GET['page']) && in_array($_GET['page'], array('mvc-command-center', 'mvcollege-command-center'), true)) {
        $classes .= ' mvc-command-center-active toplevel_page_mvc-command-center admin_page_mvc-command-center';
    }
    return $classes;
}

add_action('admin_enqueue_scripts', 'mvc_enqueue_command_center_assets');
function mvc_enqueue_command_center_assets($hook) {
    if (strpos($hook, 'mvc-command-center') === false && strpos($hook, 'mvcollege-command-center') === false) {
        return;
    }

    $ver = '2.1.0';
    $base_url = get_stylesheet_directory_uri() . '/inc/admin/command-center/assets';

    // Google Fonts for 10/10 Executive Typography
    wp_enqueue_style(
        'mvc-cc-fonts',
        'https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_style('mvc-command-center-css', $base_url . '/command-center.css', array('dashicons', 'mvc-cc-fonts'), $ver);
    wp_enqueue_script('mvc-command-center-js', $base_url . '/command-center.js', array('wp-api-fetch'), $ver, true);

    $current_user = wp_get_current_user();
    $user_role = !empty($current_user->roles) ? reset($current_user->roles) : 'staff';
    $dept_id = (int) get_user_meta($current_user->ID, 'mvc_dept_term_id', true);
    $dept_term = $dept_id ? get_term($dept_id, 'dept_link') : null;

    // Real-time CPT Counts for All CMS Options
    $post_counts = array(
        'notice'             => (int) (wp_count_posts('notice')->publish ?? 0),
        'slider'             => (int) (wp_count_posts('slider')->publish ?? 0),
        'leadership'         => (int) (wp_count_posts('leadership')->publish ?? 0),
        'calendar_milestone' => (int) (wp_count_posts('calendar_milestone')->publish ?? 0),
        'calendar_gazette'   => (int) (wp_count_posts('calendar_gazette')->publish ?? 0),
        'faculty'            => (int) (wp_count_posts('faculty')->publish ?? 0),
        'department'         => (int) (wp_count_posts('department')->publish ?? 0),
        'event'              => (int) (wp_count_posts('event')->publish ?? 0),
        'press_media'        => (int) ((wp_count_posts('press-media')->publish ?? 0) ?: (wp_count_posts('press_media')->publish ?? 0)),
        'press-media'        => (int) ((wp_count_posts('press-media')->publish ?? 0) ?: (wp_count_posts('press_media')->publish ?? 0)),
        'study_material'     => (int) (wp_count_posts('study_material')->publish ?? 0),
        'research'           => (int) (wp_count_posts('research')->publish ?? 0),
        'committee'          => (int) (wp_count_posts('committee')->publish ?? 0),
        'staff_member'       => (int) (wp_count_posts('staff_member')->publish ?? 0),
        'page'               => (int) (wp_count_posts('page')->publish ?? 0),
        'post'               => (int) (wp_count_posts('post')->publish ?? 0),
    );

    // Pending review count
    $pending_query = new WP_Query(array(
        'post_status'    => 'pending',
        'post_type'      => array('notice', 'event', 'faculty', 'study_material', 'research', 'department'),
        'posts_per_page' => 1,
        'fields'         => 'ids'
    ));
    $pending_reviews_count = (int) $pending_query->found_posts;

    // Active grievances
    $grievance_query = new WP_Query(array(
        'post_type'      => 'grievance',
        'post_status'    => array('logged', 'in-review', 'publish', 'draft'),
        'posts_per_page' => 1,
        'fields'         => 'ids'
    ));
    $active_grievances_count = (int) $grievance_query->found_posts;

    // Pending scholar accounts
    $pending_users = get_users(array(
        'meta_key'   => 'mvc_account_status',
        'meta_value' => 'pending',
        'fields'     => 'ID'
    ));
    $pending_accounts_count = is_array($pending_users) ? count($pending_users) : 0;

    wp_localize_script('mvc-command-center-js', 'MVC_DATA', array(
        'root'        => esc_url_raw(rest_url()),
        'nonce'       => wp_create_nonce('wp_rest'),
        'user'        => array(
            'id'           => $current_user->ID,
            'name'         => $current_user->display_name,
            'login'        => $current_user->user_login,
            'role'         => $user_role,
            'dept_name'    => $dept_term ? $dept_term->name : '',
            'dept_term_id' => $dept_id,
        ),
        'counts'      => $post_counts,
        'notifications' => function_exists('mvc_get_role_notifications') ? mvc_get_role_notifications($current_user->ID) : array(),
        'metrics'     => array(
            'pending_reviews'   => $pending_reviews_count,
            'active_grievances' => $active_grievances_count,
            'pending_accounts'  => $pending_accounts_count,
        ),
        'urls'        => array(
            'notices_all'       => admin_url('edit.php?post_type=notice'),
            'notices_add'       => admin_url('post-new.php?post_type=notice'),
            'notices_cats'      => admin_url('edit-tags.php?taxonomy=notice_category&post_type=notice'),
            'sliders_all'       => admin_url('edit.php?post_type=slider'),
            'sliders_add'       => admin_url('post-new.php?post_type=slider'),
            'leadership_all'    => admin_url('edit.php?post_type=leadership'),
            'leadership_add'    => admin_url('post-new.php?post_type=leadership'),
            'milestones_all'    => admin_url('edit.php?post_type=calendar_milestone'),
            'milestones_add'    => admin_url('post-new.php?post_type=calendar_milestone'),
            'gazettes_all'      => admin_url('edit.php?post_type=calendar_gazette'),
            'gazettes_add'      => admin_url('post-new.php?post_type=calendar_gazette'),
            'faculty_all'       => admin_url('edit.php?post_type=faculty'),
            'faculty_add'       => admin_url('post-new.php?post_type=faculty'),
            'departments_all'   => admin_url('edit.php?post_type=department'),
            'departments_add'   => admin_url('post-new.php?post_type=department'),
            'events_all'        => admin_url('edit.php?post_type=event'),
            'events_add'        => admin_url('post-new.php?post_type=event'),
            'press_all'         => admin_url('edit.php?post_type=press-media'),
            'press_add'         => admin_url('post-new.php?post_type=press-media'),
            'pages_all'         => admin_url('edit.php?post_type=page'),
            'pages_add'         => admin_url('post-new.php?post_type=page'),
            'posts_all'         => admin_url('edit.php'),
            'posts_add'         => admin_url('post-new.php'),
            'media_all'         => admin_url('upload.php'),
            'media_add'         => admin_url('media-new.php'),
            'galleries_all'     => admin_url('edit-tags.php?taxonomy=gallery_category'),
            'study_all'         => admin_url('edit.php?post_type=study_material'),
            'study_add'         => admin_url('post-new.php?post_type=study_material'),
            'research_all'      => admin_url('edit.php?post_type=research'),
            'research_add'      => admin_url('post-new.php?post_type=research'),
            'committees_all'    => admin_url('edit.php?post_type=committee'),
            'committees_add'    => admin_url('post-new.php?post_type=committee'),
            'staff_all'         => admin_url('edit.php?post_type=staff_member'),
            'staff_add'         => admin_url('post-new.php?post_type=staff_member'),
            'users_all'         => admin_url('users.php'),
            'users_add'         => admin_url('user-new.php'),
            'global_settings'   => admin_url('admin.php?page=college-global-settings'),
            'naac_suite'        => admin_url('admin.php?page=mvc-naac-suite'),
            'profile'           => admin_url('profile.php'),
            'portal'            => home_url('/portal/'),
            'site'              => home_url('/'),
            'logout'            => wp_logout_url(home_url('/login/')),
            'legacy'            => wp_nonce_url(admin_url('index.php?mvc_toggle_mode=legacy'), 'mvc_toggle_mode_nonce'),
        ),
        'admin_url'   => admin_url(),
        'site_url'    => home_url(),
        'ajax_url'    => admin_url('admin-ajax.php'),
        'admin_nonce' => wp_create_nonce('mvc_admin_nonce'),
    ));
}

function mvc_render_command_center_shell() {
    require_once dirname(__FILE__) . '/shell.php';
}

/**
 * Live SMTP Deliverability Test Dispatcher
 */
add_action('wp_ajax_mvc_admin_send_test_email', 'mvc_ajax_handle_admin_test_email');
function mvc_ajax_handle_admin_test_email() {
    check_ajax_referer('mvc_admin_nonce', 'security');

    if (!current_user_can('manage_options') && !current_user_can('mvc_access_admin')) {
        wp_send_json_error(array('message' => 'Unauthorized request.'));
    }

    $email = isset($_POST['recipient']) ? sanitize_email($_POST['recipient']) : '';
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid recipient email address.'));
    }

    $college_name = mvc_get_global_setting('college_name', 'Maharshi Vishwamitra Mahavidyalaya, Buxar');
    $subject = sprintf('⚡ [%s] Outgoing SMTP Deliverability Test Successful', 'M.V. College');

    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: #F8FAFC; margin: 0; padding: 24px; color: #1E293B; }
            .box { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 10px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 14px rgba(0,33,71,0.06); }
            .hdr { background: linear-gradient(135deg, #002147 0%, #800000 100%); padding: 24px; text-align: center; color: #ffffff; }
            .cnt { padding: 28px 24px; }
            .badge { display: inline-block; background: #DCFCE7; color: #166534; font-weight: 800; font-size: 13px; padding: 6px 14px; border-radius: 20px; margin-bottom: 16px; border: 1px solid #86EFAC; }
            .meta { background: #F1F5F9; border-radius: 6px; padding: 14px; font-size: 12.5px; color: #475569; line-height: 1.6; margin-top: 20px; }
            .ftr { background: #F8FAFC; padding: 14px; text-align: center; font-size: 12px; color: #94A3B8; border-top: 1px solid #E2E8F0; }
        </style>
    </head>
    <body>
        <div class="box">
            <div class="hdr">
                <h2 style="margin:0; font-size:20px; font-weight:800;">🏛️ ' . esc_html($college_name) . '</h2>
                <p style="margin:4px 0 0; font-size:12px; color:#D4AF37;">Institutional Operations Command Center</p>
            </div>
            <div class="cnt">
                <div class="badge">✓ OUTGOING SMTP PIPELINE OPERATIONAL</div>
                <h3 style="margin:0 0 10px 0; color:#002147; font-size:17px;">Email Transport Verification Confirmed</h3>
                <p style="font-size:14px; color:#475569; line-height:1.5; margin:0 0 16px 0;">
                    This transmission confirms that your collegiate SMTP gateway is communicating properly with the mail server.
                    Transactional messages (One-Time Password codes, student grievance resolution notices, and admissions alerts) are successfully delivering to external inboxes.
                </p>
                <div class="meta">
                    <strong>Transmission Details:</strong><br>
                    &bull; Recipient Destination: <code>' . esc_html($email) . '</code><br>
                    &bull; Outgoing Mailer Engine: <code>' . esc_html(ucfirst(get_option('wp_mail_smtp')['mail']['mailer'] ?? 'WP Mail SMTP')) . '</code><br>
                    &bull; Timestamp: <code>' . current_time('mysql') . ' (IST)</code>
                </div>
            </div>
            <div class="ftr">
                Maharshi Vishwamitra Mahavidyalaya, Buxar (Bihar) &bull; Estd. 1958
            </div>
        </div>
    </body>
    </html>';

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $via_fallback = false;
    $sent = false;

    try {
        $sent = wp_mail($email, $subject, $message, $headers);
    } catch (\Throwable $e) {
        $sent = false;
    }

    if (!$sent && function_exists('mvc_send_smtp_fallback_email')) {
        $sent = mvc_send_smtp_fallback_email($email, $subject, $message);
        $via_fallback = true;
    }

    if ($sent) {
        $provider_label = $via_fallback ? ' (via Zoho SMTP Fallback - RC:Echo Via MV College)' : '';
        wp_send_json_success(array(
            'message' => '✓ Live test email dispatched successfully to ' . esc_html($email) . $provider_label . '! Check your inbox.'
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Failed to dispatch email. Please click "Configure SMTP Server Settings" to verify your SMTP host, port, or password.'
        ));
    }
}

