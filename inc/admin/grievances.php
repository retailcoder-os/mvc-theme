<?php
/**
 * MV College Grievance Redressal Cell & Private File Vault
 * 
 * Secure complaint management, atomic reference generator, timeline events,
 * SLA monitoring, and air-gapped private attachment storage.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Install the grievance events custom table.
 */
function mvc_install_grievance_tables() {
    global $wpdb;
    $table = $wpdb->prefix . 'mvc_grievance_events';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        grievance_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME NOT NULL,
        actor_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        event_type VARCHAR(30) NOT NULL,
        visibility VARCHAR(10) NOT NULL DEFAULT 'public',
        body LONGTEXT NULL,
        PRIMARY KEY  (id),
        KEY grievance_id (grievance_id, created_at)
    ) {$charset};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    // Ensure private storage directory and protection exist
    mvc_ensure_private_storage_directory();
}

/**
 * Ensure wp-content/mvc-private directory exists with web access strictly blocked.
 */
function mvc_ensure_private_storage_directory() {
    $dir = WP_CONTENT_DIR . '/mvc-private/grievances';
    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
    }

    // Apache / LiteSpeed protection
    $htaccess = WP_CONTENT_DIR . '/mvc-private/.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Order deny,allow\nDeny from all\n");
    }

    // IIS protection
    $web_config = WP_CONTENT_DIR . '/mvc-private/web.config';
    if (!file_exists($web_config)) {
        file_put_contents($web_config, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<configuration>\n  <system.webServer>\n    <authorization>\n      <deny users=\"*\" />\n    </authorization>\n  </system.webServer>\n</configuration>");
    }

    // Index fallback
    $index = WP_CONTENT_DIR . '/mvc-private/index.php';
    if (!file_exists($index)) {
        file_put_contents($index, "<?php // Silence is golden\nexit;\n");
    }
}

/**
 * Single gatekeeper deciding if a user is authorized to view a grievance.
 *
 * @param int $user_id
 * @param int $grievance_id
 * @return bool
 */
function mvc_grievance_user_can_view($user_id, $grievance_id) {
    if (!$user_id || !$grievance_id) {
        return false;
    }

    $post = get_post($grievance_id);
    if (!$post || $post->post_type !== 'grievance') {
        return false;
    }

    $user = get_userdata($user_id);
    if (!$user) {
        return false;
    }

    // 1. Author (Student or Submitter) can always view own grievance
    if ((int) $post->post_author === (int) $user_id) {
        return true;
    }

    $sub_email = get_post_meta($grievance_id, '_mvc_grv_submitter_email', true);
    if (!empty($sub_email) && strtolower($user->user_email) === strtolower($sub_email)) {
        return true;
    }

    // 2. Department Head: Can view if the grievance belongs to their assigned department
    if (in_array('department_head', (array) $user->roles, true)) {
        $term_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
        if ($term_id && has_term($term_id, 'dept_link', $post)) {
            return true;
        }
        return false;
    }

    // 3. Institutional Staff Handlers (Administrators, Content Managers, Grievance Officers)
    if (user_can($user, 'mvc_handle_grievances') || in_array('administrator', (array) $user->roles, true)) {
        $is_restricted = (int) get_post_meta($grievance_id, '_mvc_grv_restricted', true);
        if ($is_restricted === 1) {
            // Restricted item: Administrator only (A4)
            return in_array('administrator', $user->roles, true);
        }
        return true;
    }

    return false;
}

/**
 * Gatekeeper deciding if a user is authorized to manage, resolve, or comment on a grievance.
 *
 * @param int $user_id
 * @param int $grievance_id
 * @return bool
 */
function mvc_grievance_user_can_manage($user_id, $grievance_id) {
    if (!$user_id || !$grievance_id) {
        return false;
    }

    $post = get_post($grievance_id);
    if (!$post || $post->post_type !== 'grievance') {
        return false;
    }

    $user = get_userdata($user_id);
    if (!$user) {
        return false;
    }

    // Super Admin & Principal: full management
    if (in_array('administrator', (array) $user->roles, true) || user_can($user, 'manage_options')) {
        return true;
    }

    // Content Manager / Grievance Officer: management of non-restricted items
    if (user_can($user, 'mvc_handle_grievances')) {
        $is_restricted = (int) get_post_meta($grievance_id, '_mvc_grv_restricted', true);
        if ($is_restricted === 1) {
            return false;
        }
        return true;
    }

    // Department Head: can manage if assigned to their department
    if (in_array('department_head', (array) $user->roles, true)) {
        $term_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
        return ($term_id && has_term($term_id, 'dept_link', $post));
    }

    return false;
}

/**
 * Generate unique grievance / enquiry reference number atomically (e.g. MVC/GRV/2026/0001 or MVC/ENQ/2026/0001).
 *
 * @param string $prefix GRV or ENQ
 * @return string|WP_Error
 */
function mvc_generate_grievance_ref($prefix = 'GRV') {
    global $wpdb;

    $lock = $wpdb->get_var("SELECT GET_LOCK('mvc_grv_counter', 5)");
    if (!$lock) {
        return new WP_Error('mvc_lock_error', __('Could not acquire grievance sequence lock.', 'mvc-college'));
    }

    try {
        $current = (int) get_option('mvc_grv_counter', 0) + 1;
        update_option('mvc_grv_counter', $current);
        $wpdb->query("SELECT RELEASE_LOCK('mvc_grv_counter')");

        $clean_p = preg_replace('/[^A-Z0-9]/', '', strtoupper($prefix)) ?: 'GRV';
        $year = gmdate('Y');
        return sprintf('MVC/%s/%s/%04d', $clean_p, $year, $current);
    } catch (\Throwable $e) {
        $wpdb->query("SELECT RELEASE_LOCK('mvc_grv_counter')");
        return new WP_Error('mvc_seq_error', $e->getMessage());
    }
}

/**
 * Submit a student grievance with attachments.
 *
 * @param int   $student_id
 * @param array $data Form fields (category, subject, description, files)
 * @return array|WP_Error Array with id and ref on success
 */
function mvc_submit_grievance($student_id, $data) {
    if (!current_user_can('mvc_submit_grievance')) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to submit a grievance.', 'mvc-college'));
    }

    // Rate limiting: 5 submissions per student per day
    $today_key = 'mvc_grv_day_' . (int) $student_id . '_' . gmdate('Ymd');
    $day_count = (int) get_transient($today_key);
    if ($day_count >= 5) {
        return new WP_Error(
            'mvc_submission_limit',
            __('Submission limit reached: you may submit a maximum of 5 grievances per calendar day.', 'mvc-college')
        );
    }

    $category = sanitize_text_field(trim($data['category'] ?? 'Academic'));
    $subject  = sanitize_text_field(trim($data['subject'] ?? ''));
    $desc     = sanitize_textarea_field(trim($data['description'] ?? ''));

    if (empty($subject) || strlen($subject) > 150) {
        return new WP_Error('mvc_invalid_subject', __('Please provide a subject between 5 and 150 characters.', 'mvc-college'));
    }

    if (empty($desc) || strlen($desc) > 3000) {
        return new WP_Error('mvc_invalid_description', __('Please provide a description of the issue (maximum 3,000 characters).', 'mvc-college'));
    }

    $valid_categories = array(
        'Academic',
        'Examination',
        'Fee or Scholarship',
        'Library or Facilities',
        'Harassment or Conduct',
        'Administration',
        'Other'
    );
    if (!in_array($category, $valid_categories, true)) {
        $category = 'Academic';
    }

    // Generate atomic reference number
    $ref_no = mvc_generate_grievance_ref();
    if (is_wp_error($ref_no)) {
        return $ref_no;
    }

    // Create private post
    $post_id = wp_insert_post(array(
        'post_type'    => 'grievance',
        'post_title'   => $subject,
        'post_content' => $desc,
        'post_status'  => 'publish', // status taxonomy controls workflow state
        'post_author'  => $student_id,
    ), true);

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    // Set taxonomy status to 'logged'
    wp_set_object_terms($post_id, 'logged', 'grievance-status', false);

    // Calculate due date (created + sla_days, default 15)
    $settings = get_option('mvc_settings', array());
    $sla_days = isset($settings['sla_days']) ? (int) $settings['sla_days'] : 15;
    $due_date = gmdate('Y-m-d H:i:s', strtotime("+{$sla_days} days"));

    // Set meta
    $is_restricted = ($category === 'Administration') ? 1 : 0;
    update_post_meta($post_id, '_mvc_grv_ref', $ref_no);
    update_post_meta($post_id, '_mvc_grv_category', $category);
    update_post_meta($post_id, '_mvc_grv_due', $due_date);
    update_post_meta($post_id, '_mvc_grv_restricted', $is_restricted);
    update_post_meta($post_id, '_mvc_grv_anonymous', 0); // B4

    // Handle uploaded files (up to 3 attachments)
    $attachments = array();
    if (!empty($_FILES['attachments'])) {
        $files = $_FILES['attachments'];
        $uploaded = mvc_store_private_attachments($post_id, $files);
        if (!empty($uploaded)) {
            $attachments = $uploaded;
            update_post_meta($post_id, '_mvc_grv_attachments', $attachments);
        }
    }

    // Record initial logged event in custom table
    mvc_add_grievance_event(
        $post_id,
        $student_id,
        'logged',
        'public',
        __('Official grievance submitted by scholar.', 'mvc-college')
    );

    // Update rate limit counter
    set_transient($today_key, $day_count + 1, DAY_IN_SECONDS);

    // Audit log
    mvc_audit('grievance_created', 'grievance', $post_id, array(
        'ref'        => $ref_no,
        'category'   => $category,
        'restricted' => $is_restricted,
    ));

    // Dispatch email notifications
    do_action('mvc_grievance_logged', $post_id, $ref_no, $student_id, $is_restricted);

    return array(
        'id'  => $post_id,
        'ref' => $ref_no,
    );
}

/**
 * Store private attachments in wp-content/mvc-private/grievances/{id}/.
 *
 * @param int   $grievance_id
 * @param array $files $_FILES array
 * @return array List of stored attachment records
 */
function mvc_store_private_attachments($grievance_id, $files) {
    $target_dir = WP_CONTENT_DIR . '/mvc-private/grievances/' . (int) $grievance_id . '/';
    if (!file_exists($target_dir)) {
        wp_mkdir_p($target_dir);
    }

    $saved = array();
    $allowed_mimes = array(
        'application/pdf' => 'pdf',
        'image/jpeg'      => 'jpg',
        'image/png'       => 'png',
    );

    // Normalize multiple file uploads
    $file_count = is_array($files['name']) ? count($files['name']) : 1;
    $limit = min(3, $file_count);

    for ($i = 0; $i < $limit; $i++) {
        $orig_name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
        $tmp_name  = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
        $size      = is_array($files['size']) ? $files['size'][$i] : $files['size'];
        $error     = is_array($files['error']) ? $files['error'][$i] : $files['error'];

        if ($error !== UPLOAD_ERR_OK || !is_uploaded_file($tmp_name)) {
            continue;
        }

        // Max 5 MB per file
        if ($size > 5 * 1024 * 1024) {
            continue;
        }

        // Validate real MIME type from content
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $real_mime = finfo_file($finfo, $tmp_name);
        finfo_close($finfo);

        if (!isset($allowed_mimes[$real_mime])) {
            continue;
        }

        $ext = $allowed_mimes[$real_mime];
        $stored_filename = wp_generate_password(24, false) . '.' . $ext;
        $dest_path = $target_dir . $stored_filename;

        if (move_uploaded_file($tmp_name, $dest_path)) {
            $att_id = wp_generate_password(12, false);
            $saved[$att_id] = array(
                'id'        => $att_id,
                'orig_name' => sanitize_file_name($orig_name),
                'filename'  => $stored_filename,
                'size'      => $size,
                'mime'      => $real_mime,
                'path'      => $dest_path,
            );
        }
    }

    return $saved;
}

/**
 * Add a timeline event to the grievance events table.
 *
 * @param int    $grievance_id
 * @param int    $actor_id
 * @param string $event_type  logged, status, assigned, note, outcome, reopened, attachment
 * @param string $visibility  public or internal
 * @param string $body
 * @return int|false
 */
function mvc_add_grievance_event($grievance_id, $actor_id, $event_type, $visibility = 'public', $body = '') {
    global $wpdb;
    $table = $wpdb->prefix . 'mvc_grievance_events';

    $result = $wpdb->insert(
        $table,
        array(
            'grievance_id' => (int) $grievance_id,
            'created_at'   => current_time('mysql'),
            'actor_id'     => (int) $actor_id,
            'event_type'   => sanitize_key($event_type),
            'visibility'   => ($visibility === 'internal') ? 'internal' : 'public',
            'body'         => sanitize_textarea_field($body),
        ),
        array('%d', '%s', '%d', '%s', '%s', '%s')
    );

    return $result ? $wpdb->insert_id : false;
}

/**
 * Get timeline events for a grievance respecting visibility rules.
 *
 * @param int $grievance_id
 * @param int $user_id
 * @return array
 */
function mvc_get_grievance_timeline($grievance_id, $user_id = 0) {
    global $wpdb;
    $table = $wpdb->prefix . 'mvc_grievance_events';

    if ($user_id && !mvc_grievance_user_can_view($user_id, $grievance_id)) {
        return array();
    }

    $post = get_post($grievance_id);
    if (!$post) {
        return array();
    }

    $is_student_author = ($user_id && (int) $post->post_author === (int) $user_id);

    // If guest or student author, retrieve ONLY public events (G3)
    if (!$user_id || ($is_student_author && !current_user_can('mvc_handle_grievances'))) {
        $sql = $wpdb->prepare("SELECT * FROM {$table} WHERE grievance_id = %d AND visibility = 'public' ORDER BY created_at ASC", $grievance_id);
    } else {
        $sql = $wpdb->prepare("SELECT * FROM {$table} WHERE grievance_id = %d ORDER BY created_at ASC", $grievance_id);
    }

    $events = $wpdb->get_results($sql, ARRAY_A);
    if (!empty($events)) {
        foreach ($events as &$ev) {
            $actor = (int) $ev['actor_id'] ? get_userdata((int) $ev['actor_id']) : null;
            $ev['actor_name'] = $actor ? $actor->display_name : 'Staff Member';
        }
        unset($ev);
    }

    return $events ?: array();
}

/**
 * Handle secure file download via admin-post.php?action=mvc_download.
 */
add_action('admin_post_mvc_download', 'mvc_handle_secure_grievance_download');
function mvc_handle_secure_grievance_download() {
    $file_id = isset($_GET['file']) ? sanitize_key($_GET['file']) : '';
    $grv_id  = isset($_GET['grv']) ? (int) $_GET['grv'] : 0;

    check_admin_referer('mvc_download_' . $file_id);

    $user_id = get_current_user_id();
    if (!mvc_grievance_user_can_view($user_id, $grv_id)) {
        status_header(404);
        wp_die(__('Document not found.', 'mvc-college'), 404);
    }

    $attachments = get_post_meta($grv_id, '_mvc_grv_attachments', true);
    if (empty($attachments) || !isset($attachments[$file_id])) {
        status_header(404);
        wp_die(__('Attachment not found.', 'mvc-college'), 404);
    }

    $att = $attachments[$file_id];
    $filepath = $att['path'];

    if (!file_exists($filepath) || !is_readable($filepath)) {
        status_header(404);
        wp_die(__('Physical file not found on server.', 'mvc-college'), 404);
    }

    // Log the download action
    mvc_audit('grievance_download', 'grievance', $grv_id, array(
        'file_id'   => $file_id,
        'orig_name' => $att['orig_name'],
    ));

    // Stream download with security headers
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $att['mime']);
    header('Content-Disposition: inline; filename="' . esc_attr($att['orig_name']) . '"');
    header('Content-Length: ' . filesize($filepath));
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    readfile($filepath);
    exit;
}

/**
 * Daily SLA monitoring cron: checks for overdue grievances and emails digest.
 */
function mvc_check_grievance_slas() {
    $now = current_time('mysql');
    $overdue_query = new WP_Query(array(
        'post_type'      => 'grievance',
        'posts_per_page' => 50,
        'tax_query'      => array(
            array(
                'taxonomy' => 'grievance-status',
                'field'    => 'slug',
                'terms'    => array('logged', 'in-review'),
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => '_mvc_grv_due',
                'value'   => $now,
                'compare' => '<',
                'type'    => 'DATETIME',
            ),
        ),
    ));

    if ($overdue_query->have_posts()) {
        $count = $overdue_query->found_posts;
        $admin_emails = mvc_get_staff_notification_emails(true);

        if (!empty($admin_emails)) {
            $subject = sprintf('[SLA Alert] %d Grievances Overdue for Resolution', $count);
            $body = '
                <h3 style="color:#B71C1C; margin-top:0;">Grievance SLA Compliance Digest</h3>
                <p>The following grievances have exceeded their targeted 15-day resolution SLA:</p>
                <ul>';
            foreach ($overdue_query->posts as $p) {
                $ref = get_post_meta($p->ID, '_mvc_grv_ref', true);
                $body .= '<li><strong>' . esc_html($ref) . '</strong> - ' . esc_html($p->post_title) . '</li>';
            }
            $body .= '</ul>
                <p><a href="' . esc_url(admin_url('admin.php?page=mvc-command-center&section=grievances&filter=overdue')) . '" class="mvc-btn-link">View Overdue Items &rarr;</a></p>
            ';
            mvc_send_notification($admin_emails, $subject, $body, 'grievance_overdue');
        }
    }
}
add_action('mvc_daily', 'mvc_check_grievance_slas');
