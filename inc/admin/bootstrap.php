<?php
/**
 * MV College Role-Based Dashboards & Portal Bootstrap Engine
 * 
 * Version 1.0.0 • Strict 5-Role Institutional Model
 * 
 * Surface 1: Staff Command Center (/wp-admin/)
 * Surface 2: Scholar Portal (/portal/)
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Define Architecture Constants
if (!defined('MVC_ROLES_VERSION')) {
    define('MVC_ROLES_VERSION', '1.2.0');
}
if (!defined('MVC_DB_VERSION')) {
    define('MVC_DB_VERSION', '1.0.0');
}
if (!defined('MVC_ADMIN_DIR')) {
    define('MVC_ADMIN_DIR', trailingslashit(dirname(__FILE__)));
}
if (!defined('MVC_ADMIN_URL')) {
    define('MVC_ADMIN_URL', trailingslashit(get_stylesheet_directory_uri() . '/inc/admin'));
}

// 2. Load Core Foundations in Strict Dependency Order
require_once MVC_ADMIN_DIR . 'audit-log.php';
require_once MVC_ADMIN_DIR . 'roles-caps.php';
require_once MVC_ADMIN_DIR . 'access-guard.php';
require_once MVC_ADMIN_DIR . 'dept-isolation.php';
require_once MVC_ADMIN_DIR . 'dept-taxonomy-sync.php';
require_once MVC_ADMIN_DIR . 'workflow.php';
require_once MVC_ADMIN_DIR . 'notifications.php';
require_once MVC_ADMIN_DIR . 'grievances.php';
require_once MVC_ADMIN_DIR . 'classic-grievances.php';
require_once MVC_ADMIN_DIR . 'accounts.php';
require_once MVC_ADMIN_DIR . 'mode-toggle.php';
require_once MVC_ADMIN_DIR . 'update-lock.php';
require_once MVC_ADMIN_DIR . 'maintenance-mode.php';

// 3. Load REST API Subsystem
require_once MVC_ADMIN_DIR . 'rest/rest-bootstrap.php';

// 4. Load Staff Command Center
require_once MVC_ADMIN_DIR . 'command-center/command-center-bootstrap.php';

// 5. Load Public Scholar Portal
require_once MVC_ADMIN_DIR . 'portal/portal-bootstrap.php';

// 6. Load WP-CLI Suite
if (defined('WP_CLI') && WP_CLI) {
    require_once MVC_ADMIN_DIR . 'cli.php';
}

/**
 * Initialize Database Tables and Initial Options on Theme Setup.
 */
function mvc_bootstrap_database_init() {
    if (get_option('mvc_db_version') !== MVC_DB_VERSION) {
        mvc_install_audit_log_table();
        mvc_install_grievance_tables();
        update_option('mvc_db_version', MVC_DB_VERSION);

        // Initialize default options
        if (!get_option('mvc_settings')) {
            update_option('mvc_settings', array(
                'sla_days'               => 15,
                'audit_retention_months' => 24,
                'reunion_form_id'        => 0,
                'portal_page_id'         => 0,
            ));
        }
    }
}
add_action('after_setup_theme', 'mvc_bootstrap_database_init');
add_action('admin_init', 'mvc_bootstrap_database_init');
