<?php
/**
 * MV College Audit Log Engine
 * 
 * Provides synchronous, immutable audit logging for all institutional state changes.
 * Enforces a strict 30-day retention policy (continuous rolling purge).
 * Captures all changes across posts, pages, CPTs, metadata, users, roles,
 * media, taxonomies, settings, menus, and authentication events.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create custom audit log table if not existing.
 */
function mvc_install_audit_log_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mvc_audit_log';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table_name} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        created_at DATETIME NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        user_role VARCHAR(40) NOT NULL DEFAULT '',
        action VARCHAR(60) NOT NULL,
        object_type VARCHAR(40) NOT NULL DEFAULT '',
        object_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
        ip VARCHAR(45) NOT NULL DEFAULT '',
        details LONGTEXT NULL,
        PRIMARY KEY  (id),
        KEY created_at (created_at),
        KEY user_id (user_id),
        KEY action (action),
        KEY object (object_type, object_id)
    ) {$charset_collate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

/**
 * Write an entry to the institutional audit log.
 * 
 * Synchronous, fail-safe (never throws), sanitized, and protected against recursion.
 *
 * @param string $action      Action key (e.g. login_success, post_updated, etc.)
 * @param string $object_type Target object type ('post', 'user', 'setting', etc.)
 * @param int    $object_id   Target object ID
 * @param array  $details     Contextual key-value pairs (IDs, changed fields; no secrets)
 * @return bool
 */
function mvc_audit($action, $object_type = '', $object_id = 0, $details = array()) {
    global $wpdb;

    // Recursion guard to prevent infinite loops during option/post saves
    static $is_auditing = false;
    if ($is_auditing) {
        return false;
    }

    $is_auditing = true;

    try {
        $user_id   = get_current_user_id();
        $user_role = '';
        if ($user_id) {
            $user = get_userdata($user_id);
            if ($user && !empty($user->roles)) {
                $user_role = reset($user->roles);
            }
        }

        // Determine IP address safely
        $ip = '';
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
        }

        // Clean details: ensure no passwords or secrets are ever recorded
        $clean_details = array();
        if (is_array($details)) {
            foreach ($details as $k => $v) {
                if (in_array(strtolower($k), array('password', 'user_pass', 'pass', 'hash', 'secret', 'token', 'key', 'auth_key', 'nonce'), true)) {
                    continue;
                }
                $clean_details[$k] = is_scalar($v) ? (string) $v : $v;
            }
        }

        $table_name = $wpdb->prefix . 'mvc_audit_log';
        $result = $wpdb->insert(
            $table_name,
            array(
                'created_at'  => current_time('mysql'),
                'user_id'     => (int) $user_id,
                'user_role'   => sanitize_key($user_role),
                'action'      => sanitize_key($action),
                'object_type' => sanitize_key($object_type),
                'object_id'   => (int) $object_id,
                'ip'          => $ip,
                'details'     => !empty($clean_details) ? wp_json_encode($clean_details) : null,
            ),
            array('%s', '%d', '%s', '%s', '%s', '%d', '%s', '%s')
        );

        $is_auditing = false;
        return $result !== false;
    } catch (\Throwable $e) {
        $is_auditing = false;
        error_log('[MVC Audit Failure] ' . $e->getMessage());
        return false;
    }
}

/**
 * Retrieve audit log rows with filters and pagination.
 *
 * @param array $args Filter arguments
 * @return array Array with 'items' and 'total'
 */
function mvc_get_audit_logs($args = array()) {
    global $wpdb;
    $table = $wpdb->prefix . 'mvc_audit_log';

    $defaults = array(
        'page'         => 1,
        'per_page'     => 50,
        'user_id'      => 0,
        'action'       => '',
        'action_group' => '',
        'object_type'  => '',
        'date_from'    => '',
        'date_to'      => '',
        'search'       => '',
    );
    $params = wp_parse_args($args, $defaults);

    $page     = max(1, (int) $params['page']);
    $per_page = min(100, max(1, (int) $params['per_page']));
    $offset   = ($page - 1) * $per_page;

    $where   = array("1=1");
    $values  = array();

    if (!empty($params['user_id'])) {
        $where[]  = "user_id = %d";
        $values[] = (int) $params['user_id'];
    }

    if (!empty($params['action'])) {
        $where[]  = "action = %s";
        $values[] = sanitize_key($params['action']);
    }

    if (!empty($params['action_group'])) {
        switch ($params['action_group']) {
            case 'content':
                $where[] = "(action LIKE 'post_%' OR action LIKE 'content_%')";
                break;
            case 'meta':
                $where[] = "action LIKE 'postmeta_%'";
                break;
            case 'users':
                $where[] = "(action LIKE 'user_%' OR action LIKE 'account_%' OR action LIKE 'role_%')";
                break;
            case 'media':
                $where[] = "action LIKE 'media_%'";
                break;
            case 'taxonomies':
                $where[] = "action LIKE 'term_%'";
                break;
            case 'settings':
                $where[] = "(action LIKE 'setting_%' OR action = 'settings_changed')";
                break;
            case 'security':
                $where[] = "(action LIKE 'login_%' OR action = 'logout' OR action LIKE 'auth_%' OR action = 'admin_blocked' OR action = 'login_lockout')";
                break;
        }
    }

    if (!empty($params['object_type'])) {
        $where[]  = "object_type = %s";
        $values[] = sanitize_key($params['object_type']);
    }

    if (!empty($params['date_from'])) {
        $where[]  = "created_at >= %s";
        $values[] = sanitize_text_field($params['date_from']) . ' 00:00:00';
    }

    if (!empty($params['date_to'])) {
        $where[]  = "created_at <= %s";
        $values[] = sanitize_text_field($params['date_to']) . ' 23:59:59';
    }

    if (!empty($params['search'])) {
        $where[]  = "(action LIKE %s OR details LIKE %s OR ip LIKE %s OR object_type LIKE %s)";
        $like     = '%' . $wpdb->esc_like(sanitize_text_field($params['search'])) . '%';
        $values[] = $like;
        $values[] = $like;
        $values[] = $like;
        $values[] = $like;
    }

    $where_sql = implode(' AND ', $where);

    // Count query
    $count_sql = "SELECT COUNT(*) FROM {$table} WHERE {$where_sql}";
    if (!empty($values)) {
        $count_sql = $wpdb->prepare($count_sql, $values);
    }
    $total = (int) $wpdb->get_var($count_sql);

    // Data query
    $data_sql = "SELECT * FROM {$table} WHERE {$where_sql} ORDER BY created_at DESC LIMIT %d OFFSET %d";
    $query_values = $values;
    $query_values[] = $per_page;
    $query_values[] = $offset;

    $items = $wpdb->get_results($wpdb->prepare($data_sql, $query_values), ARRAY_A);

    // Format items with display user name
    if (!empty($items)) {
        foreach ($items as &$item) {
            $u = (int) $item['user_id'] ? get_userdata((int) $item['user_id']) : null;
            $item['user_name'] = $u ? $u->display_name : ($item['user_id'] ? 'User #' . $item['user_id'] : 'System / Guest');
            $item['details_parsed'] = !empty($item['details']) ? json_decode($item['details'], true) : array();
        }
        unset($item);
    }

    return array(
        'items'       => $items ?: array(),
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $per_page,
        'total_pages' => ceil($total / $per_page),
    );
}

/**
 * Export audit logs to CSV with security audit trail.
 */
function mvc_export_audit_csv($args = array()) {
    if (!current_user_can('mvc_view_audit') && !current_user_can('manage_options')) {
        wp_die(__('Unauthorized access to audit export.', 'mvc-college'), 403);
    }

    // Limit export to max 5,000 rows
    $args['page'] = 1;
    $args['per_page'] = 5000;
    $data = mvc_get_audit_logs($args);

    // Log the export action itself
    mvc_audit('export', 'audit_log', 0, array(
        'count'  => count($data['items']),
        'format' => 'csv',
    ));

    $filename = 'mvc-audit-log-' . gmdate('Y-m-d-His') . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    // BOM for Excel UTF-8 compatibility
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    fputcsv($output, array('ID', 'Timestamp', 'User ID', 'User Name', 'Role', 'Action', 'Object Type', 'Object ID', 'IP Address', 'Details'));

    foreach ($data['items'] as $row) {
        fputcsv($output, array(
            $row['id'],
            $row['created_at'],
            $row['user_id'],
            $row['user_name'],
            $row['user_role'],
            $row['action'],
            $row['object_type'],
            $row['object_id'],
            $row['ip'],
            $row['details'] ?: '',
        ));
    }

    fclose($output);
    exit;
}

/**
 * Strict 30-Day Retention Policy Cleanup.
 * Automatically purges any audit log records older than 30 days.
 *
 * @return int Number of purged rows.
 */
function mvc_audit_retention_cleanup() {
    global $wpdb;
    $table = $wpdb->prefix . 'mvc_audit_log';

    // Strict 30-day retention window (per user directive)
    $retention_days = 30;
    $cutoff = gmdate('Y-m-d H:i:s', strtotime("-{$retention_days} days"));

    $deleted = $wpdb->query(
        $wpdb->prepare("DELETE FROM {$table} WHERE created_at < %s", $cutoff)
    );

    if ($deleted && $deleted > 0) {
        // Insert retention record directly to avoid recursion
        $wpdb->insert(
            $table,
            array(
                'created_at'  => current_time('mysql'),
                'user_id'     => 0,
                'user_role'   => 'system',
                'action'      => 'audit_retention_purged',
                'object_type' => 'system',
                'object_id'   => 0,
                'ip'          => '127.0.0.1',
                'details'     => wp_json_encode(array(
                    'deleted_records' => (int) $deleted,
                    'retention_days'  => $retention_days,
                    'cutoff_date'     => $cutoff,
                )),
            ),
            array('%s', '%d', '%s', '%s', '%s', '%d', '%s', '%s')
        );
    }

    update_option('mvc_last_audit_purge', time());
    return (int) $deleted;
}
add_action('mvc_daily', 'mvc_audit_retention_cleanup');

// Continuous rolling auto-purge check on admin requests (throttled to max once every 6 hours)
add_action('init', function() {
    if (is_admin() || wp_doing_cron()) {
        $last_purge = (int) get_option('mvc_last_audit_purge', 0);
        if ((time() - $last_purge) > (6 * HOUR_IN_SECONDS)) {
            mvc_audit_retention_cleanup();
        }
    }
});

/* ==========================================================================
   UNIVERSAL CHANGE CAPTURE ENGINE ("Capture All Changes, All Things")
   ========================================================================== */

/**
 * 1. Watch Post & Page & CPT Updates (Title changes, status transitions, content)
 */
add_action('post_updated', 'mvc_audit_watch_post_updated', 10, 3);
function mvc_audit_watch_post_updated($post_id, $post_after, $post_before) {
    if (!$post_after || !$post_before) return;
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;
    if ($post_after->post_status === 'auto-draft' || $post_after->post_type === 'nav_menu_item') return;

    $post_type = $post_after->post_type;
    $title     = $post_after->post_title ?: ('#' . $post_id);

    // Status transition (e.g. draft -> publish, publish -> trash)
    if ($post_before->post_status !== $post_after->post_status) {
        mvc_audit('post_status_changed', $post_type, $post_id, array(
            'title'      => $title,
            'old_status' => $post_before->post_status,
            'new_status' => $post_after->post_status,
        ));
        return;
    }

    // Title change
    if ($post_before->post_title !== $post_after->post_title) {
        mvc_audit('post_title_changed', $post_type, $post_id, array(
            'old_title' => $post_before->post_title,
            'new_title' => $post_after->post_title,
            'status'    => $post_after->post_status,
        ));
        return;
    }

    // General post update
    mvc_audit('post_updated', $post_type, $post_id, array(
        'title'  => $title,
        'status' => $post_after->post_status,
    ));
}

/**
 * 2. Watch New Post Creation
 */
add_action('wp_after_insert_post', 'mvc_audit_watch_post_created', 10, 4);
function mvc_audit_watch_post_created($post_id, $post, $update, $post_before) {
    if ($update || !$post) return;
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;
    if ($post->post_status === 'auto-draft' || $post->post_type === 'nav_menu_item') return;

    mvc_audit('post_created', $post->post_type, $post_id, array(
        'title'  => $post->post_title ?: ('#' . $post_id),
        'status' => $post->post_status,
    ));
}

/**
 * 3. Watch Trashing, Untrashing, and Permanent Deletion
 */
add_action('trashed_post', 'mvc_audit_watch_post_trashed');
function mvc_audit_watch_post_trashed($post_id) {
    $post = get_post($post_id);
    if (!$post || $post->post_type === 'nav_menu_item') return;
    mvc_audit('post_trashed', $post->post_type, $post_id, array(
        'title' => $post->post_title,
    ));
}

add_action('untrashed_post', 'mvc_audit_watch_post_restored');
function mvc_audit_watch_post_restored($post_id) {
    $post = get_post($post_id);
    if (!$post || $post->post_type === 'nav_menu_item') return;
    mvc_audit('post_restored', $post->post_type, $post_id, array(
        'title' => $post->post_title,
    ));
}

add_action('before_delete_post', 'mvc_audit_watch_post_deleted');
function mvc_audit_watch_post_deleted($post_id) {
    if (wp_is_post_revision($post_id)) return;
    $post = get_post($post_id);
    if (!$post || $post->post_type === 'nav_menu_item') return;
    mvc_audit('post_deleted', $post->post_type, $post_id, array(
        'title' => $post->post_title,
    ));
}

/**
 * 4. Watch Post Meta Changes (Custom Fields, ACF, Memo Numbers, PDFs)
 */
add_action('updated_post_meta', 'mvc_audit_watch_postmeta_updated', 10, 4);
function mvc_audit_watch_postmeta_updated($meta_id, $post_id, $meta_key, $meta_value) {
    if (!is_admin() && !defined('DOING_AJAX') && !defined('REST_REQUEST')) return;

    // Filter noisy internal WordPress keys
    $ignored_prefixes = array(
        '_edit_lock', '_edit_last', '_encloseme', '_pingme', 
        '_wp_old_slug', '_wp_page_template', '_wp_trash_meta_', '_oembed_'
    );
    foreach ($ignored_prefixes as $prefix) {
        if (str_starts_with($meta_key, $prefix)) return;
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type === 'revision' || $post->post_type === 'nav_menu_item') return;

    $val_str = is_scalar($meta_value) ? (string) $meta_value : '[complex data]';
    if (strlen($val_str) > 100) {
        $val_str = substr($val_str, 0, 97) . '...';
    }

    mvc_audit('postmeta_updated', $post->post_type, $post_id, array(
        'meta_key' => $meta_key,
        'value'    => $val_str,
        'title'    => $post->post_title,
    ));
}

add_action('deleted_post_meta', 'mvc_audit_watch_postmeta_deleted', 10, 3);
function mvc_audit_watch_postmeta_deleted($meta_ids, $post_id, $meta_key) {
    if (!is_admin() && !defined('DOING_AJAX') && !defined('REST_REQUEST')) return;
    if (str_starts_with($meta_key, '_edit_') || str_starts_with($meta_key, '_wp_trash_')) return;
    $post = get_post($post_id);
    if (!$post || $post->post_type === 'revision') return;

    mvc_audit('postmeta_deleted', $post->post_type, $post_id, array(
        'meta_key' => $meta_key,
        'title'    => $post->post_title,
    ));
}

/**
 * 5. Watch User Accounts & Role Changes
 */
add_action('user_register', 'mvc_audit_watch_user_register', 10, 1);
function mvc_audit_watch_user_register($user_id) {
    $u = get_userdata($user_id);
    if (!$u) return;
    mvc_audit('user_registered', 'user', $user_id, array(
        'login' => $u->user_login,
        'email' => $u->user_email,
        'role'  => reset($u->roles),
    ));
}

add_action('profile_update', 'mvc_audit_watch_profile_update', 10, 2);
function mvc_audit_watch_profile_update($user_id, $old_data) {
    $u = get_userdata($user_id);
    if (!$u) return;
    mvc_audit('user_profile_updated', 'user', $user_id, array(
        'login'        => $u->user_login,
        'display_name' => $u->display_name,
    ));
}

add_action('set_user_role', 'mvc_audit_watch_role_change', 10, 3);
function mvc_audit_watch_role_change($user_id, $role, $old_roles) {
    $u = get_userdata($user_id);
    if (!$u) return;
    mvc_audit('user_role_changed', 'user', $user_id, array(
        'login'     => $u->user_login,
        'old_roles' => $old_roles,
        'new_role'  => $role,
    ));
}

add_action('delete_user', 'mvc_audit_watch_delete_user', 10, 3);
function mvc_audit_watch_delete_user($user_id, $reassign, $user) {
    if (!$user) return;
    mvc_audit('user_deleted', 'user', $user_id, array(
        'login' => $user->user_login,
        'email' => $user->user_email,
    ));
}

add_action('password_reset', 'mvc_audit_watch_password_reset', 10, 2);
function mvc_audit_watch_password_reset($user, $new_pass) {
    if (!$user) return;
    mvc_audit('user_password_reset', 'user', $user->ID, array(
        'login' => $user->user_login,
    ));
}

/**
 * 6. Watch Taxonomies & Categories (Departments, Audiences, Categories, Tags)
 */
add_action('created_term', 'mvc_audit_watch_term_created', 10, 3);
function mvc_audit_watch_term_created($term_id, $tt_id, $taxonomy) {
    $term = get_term($term_id, $taxonomy);
    if (!$term || is_wp_error($term)) return;
    mvc_audit('term_created', 'taxonomy_' . $taxonomy, $term_id, array(
        'taxonomy' => $taxonomy,
        'name'     => $term->name,
        'slug'     => $term->slug,
    ));
}

add_action('edited_term', 'mvc_audit_watch_term_edited', 10, 3);
function mvc_audit_watch_term_edited($term_id, $tt_id, $taxonomy) {
    $term = get_term($term_id, $taxonomy);
    if (!$term || is_wp_error($term)) return;
    mvc_audit('term_updated', 'taxonomy_' . $taxonomy, $term_id, array(
        'taxonomy' => $taxonomy,
        'name'     => $term->name,
        'slug'     => $term->slug,
    ));
}

add_action('delete_term', 'mvc_audit_watch_term_deleted', 10, 4);
function mvc_audit_watch_term_deleted($term_id, $tt_id, $taxonomy, $deleted_term) {
    $name = is_object($deleted_term) ? $deleted_term->name : ('#' . $term_id);
    mvc_audit('term_deleted', 'taxonomy_' . $taxonomy, $term_id, array(
        'taxonomy' => $taxonomy,
        'name'     => $name,
    ));
}

/**
 * 7. Watch Media & Attachments
 */
add_action('add_attachment', 'mvc_audit_watch_attachment_added');
function mvc_audit_watch_attachment_added($post_id) {
    $file = get_attached_file($post_id);
    mvc_audit('media_uploaded', 'attachment', $post_id, array(
        'filename'  => $file ? basename($file) : '',
        'mime_type' => get_post_mime_type($post_id),
    ));
}

add_action('delete_attachment', 'mvc_audit_watch_attachment_deleted');
function mvc_audit_watch_attachment_deleted($post_id) {
    $file = get_attached_file($post_id);
    mvc_audit('media_deleted', 'attachment', $post_id, array(
        'filename' => $file ? basename($file) : ('#' . $post_id),
    ));
}

/**
 * 8. Watch Navigation Menus
 */
add_action('wp_update_nav_menu', 'mvc_audit_watch_menu_updated', 10, 2);
function mvc_audit_watch_menu_updated($menu_id, $menu_data = array()) {
    mvc_audit('menu_updated', 'nav_menu', $menu_id, array(
        'name' => !empty($menu_data['menu-name']) ? $menu_data['menu-name'] : ('#' . $menu_id),
    ));
}

add_action('wp_create_nav_menu', 'mvc_audit_watch_menu_created', 10, 2);
function mvc_audit_watch_menu_created($term_id, $menu_data = array()) {
    mvc_audit('menu_created', 'nav_menu', $term_id, array(
        'name' => !empty($menu_data['menu-name']) ? $menu_data['menu-name'] : ('#' . $term_id),
    ));
}

add_action('wp_delete_nav_menu', 'mvc_audit_watch_menu_deleted', 10, 1);
function mvc_audit_watch_menu_deleted($term_id) {
    mvc_audit('menu_deleted', 'nav_menu', $term_id);
}

/**
 * 9. Watch Institutional Settings & Core Configuration Options
 */
add_action('updated_option', 'mvc_audit_watch_option_updated', 10, 3);
function mvc_audit_watch_option_updated($option, $old_value, $value) {
    if (!is_admin() && !defined('DOING_AJAX') && !defined('REST_REQUEST')) return;

    // Ignore dynamic visitor counters & cache keys
    $ignored_options = array(
        'mvc_total_visitors', 'mvc_today_visitors', 'mvc_yesterday_visitors', 
        'mvc_counter_date', 'mvc_last_audit_purge', 'mvc_real_active_visitors'
    );
    if (in_array($option, $ignored_options, true) || str_starts_with($option, 'mvc_cached_')) {
        return;
    }

    // Whitelist institutional options of high administrative relevance
    $whitelisted = array(
        'blogname', 'blogdescription', 'siteurl', 'home', 'admin_email', 
        'users_can_register', 'default_role', 'permalink_structure', 
        'active_plugins', 'stylesheet', 'template', 'WPLANG', 'timezone_string',
        'mvc_settings'
    );

    $is_whitelisted = in_array($option, $whitelisted, true) || str_starts_with($option, 'mvc_');
    if (!$is_whitelisted) return;

    $old_summary = is_scalar($old_value) ? (string) $old_value : '[configuration]';
    $new_summary = is_scalar($value) ? (string) $value : '[configuration]';

    if (strlen($old_summary) > 80) $old_summary = substr($old_summary, 0, 77) . '...';
    if (strlen($new_summary) > 80) $new_summary = substr($new_summary, 0, 77) . '...';

    mvc_audit('setting_updated', 'option', 0, array(
        'option'    => $option,
        'old_value' => $old_summary,
        'new_value' => $new_summary,
    ));
}

/**
 * 10. Watch Authentication & Security Events
 */
add_action('wp_login', 'mvc_audit_watch_login_success', 10, 2);
function mvc_audit_watch_login_success($user_login, $user) {
    static $logged_in_once = false;
    if ($logged_in_once) return;
    $logged_in_once = true;

    $role = ($user && !empty($user->roles)) ? reset($user->roles) : '';
    mvc_audit('login_success', 'user', $user ? $user->ID : 0, array(
        'login' => $user_login,
        'role'  => $role,
    ));
}

add_action('wp_login_failed', 'mvc_audit_watch_login_failed', 10, 1);
function mvc_audit_watch_login_failed($username) {
    mvc_audit('login_failed', 'security', 0, array(
        'attempted_username' => sanitize_text_field($username),
    ));
}

add_action('wp_logout', 'mvc_audit_watch_logout', 10, 1);
function mvc_audit_watch_logout($user_id) {
    mvc_audit('logout', 'user', (int) $user_id);
}

