<?php
/**
 * Institutional Portal Login, Registration & Authentication Suite Builder
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * 
 * Provides a 10/10 Prestigious, Mobile-First Authentication Gateway:
 * - Tab 1: Unified Institutional Sign In (Admin, HODs, Content Managers, Students, Alumni)
 * - Tab 2: New Student Registration & Verification (Degree, Dept, Roll No, Session)
 * - Tab 3: Alumni Network Registration (Graduation Year, Degree, Profession)
 * - Tab 4: Secure Password Recovery Flow
 * - Logged-in State: Dynamic VIP Scholar / Staff Dashboard Card
 * - Master Dual-Wing Institutional Gateway with balanced left brand wing & right auth wing
 * - Grounding Shelf: 5 Public Direct Access & Statutory Services
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ========================================================
   1. ENSURE STUDENT & ALUMNI ROLES ARE REGISTERED
   ======================================================== */
function mvc_ensure_portal_roles() {
    if (!get_role('student')) {
        add_role('student', __('Student / Scholar', 'mvc-child'), array(
            'read'         => true,
            'upload_files' => false,
            'edit_posts'   => false,
            'delete_posts' => false,
        ));
    }
    if (!get_role('alumni_member')) {
        add_role('alumni_member', __('Alumni Member', 'mvc-child'), array(
            'read'         => true,
            'upload_files' => false,
            'edit_posts'   => false,
            'delete_posts' => false,
        ));
    }
}
add_action('init', 'mvc_ensure_portal_roles');

/* ========================================================
   2. HANDLE PORTAL AUTHENTICATION & REGISTRATION POST ACTIONS
   ======================================================== */
function mvc_handle_portal_auth_actions() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['mvc_auth_action'])) {
        return;
    }

    $action = sanitize_text_field($_POST['mvc_auth_action']);

    // ACTION A: SIGN IN
    if ($action === 'login') {
        if (!isset($_POST['mvc_login_nonce']) || !wp_verify_nonce($_POST['mvc_login_nonce'], 'mvc_login_action')) {
            wp_safe_redirect(add_query_arg('auth_err', 'nonce_failed', home_url('/login/')));
            exit;
        }

        $creds = array(
            'user_login'    => sanitize_text_field($_POST['log']),
            'user_password' => $_POST['pwd'],
            'remember'      => !empty($_POST['rememberme']),
        );

        $user = wp_signon($creds, is_ssl());

        if (is_wp_error($user)) {
            $err_code = $user->get_error_code();
            wp_safe_redirect(add_query_arg('auth_err', urlencode($err_code), home_url('/login/')));
            exit;
        }

        // Determine intelligent post-login redirect based on user role
        $roles = (array) $user->roles;
        $redirect_to = home_url('/');

        if (in_array('administrator', $roles) || in_array('content_manager', $roles) || in_array('department_head', $roles) || in_array('department_editor', $roles) || in_array('notice_manager', $roles) || in_array('naac_editor', $roles) || in_array('grievance_officer', $roles)) {
            $mode = get_user_meta($user->ID, 'mvc_admin_mode', true) ?: 'legacy';
            if ($mode === 'modern') {
                $redirect_to = admin_url('admin.php?page=mvc-command-center');
            } else {
                $redirect_to = admin_url();
            }
        } elseif (in_array('student', $roles)) {
            $redirect_to = home_url('/portal/');
        } elseif (in_array('alumni_member', $roles) || in_array('alumni', $roles)) {
            $redirect_to = home_url('/portal/');
        }

        if (!empty($_POST['redirect_to'])) {
            $custom_redirect = esc_url_raw($_POST['redirect_to']);
            if (wp_validate_redirect($custom_redirect)) {
                $redirect_to = $custom_redirect;
            }
        }

        wp_safe_redirect($redirect_to);
        exit;
    }

    // ACTION B: STUDENT REGISTRATION
    if ($action === 'register_student') {
        if (!isset($_POST['mvc_student_reg_nonce']) || !wp_verify_nonce($_POST['mvc_student_reg_nonce'], 'mvc_student_reg_action')) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'nonce_failed', 'tab' => 'student'), home_url('/login/')));
            exit;
        }

        // Honeypot anti-spam check
        if (!empty($_POST['mvc_website_hp'])) {
            wp_safe_redirect(home_url('/login/'));
            exit;
        }

        $full_name  = sanitize_text_field($_POST['std_full_name']);
        $roll_no    = sanitize_text_field($_POST['std_roll_no']);
        $degree     = sanitize_text_field($_POST['std_degree']);
        $department = sanitize_text_field($_POST['std_department']);
        $session    = sanitize_text_field($_POST['std_session']);
        $phone      = sanitize_text_field($_POST['std_phone']);
        $email      = sanitize_email($_POST['std_email']);
        $pwd        = $_POST['std_password'];
        $pwd_conf   = $_POST['std_password_confirm'];

        if (empty($full_name) || empty($roll_no) || empty($email) || empty($pwd)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'empty_fields', 'tab' => 'student'), home_url('/login/')));
            exit;
        }

        if ($pwd !== $pwd_conf) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'pwd_mismatch', 'tab' => 'student'), home_url('/login/')));
            exit;
        }

        if (strlen($pwd) < 6) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'pwd_too_short', 'tab' => 'student'), home_url('/login/')));
            exit;
        }

        if (email_exists($email)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'email_taken', 'tab' => 'student'), home_url('/login/')));
            exit;
        }

        // Generate clean username from roll number or email
        $clean_roll = preg_replace('/[^a-zA-Z0-9]/', '', $roll_no);
        $username = 'std_' . strtolower($clean_roll);
        if (username_exists($username)) {
            $username = 'std_' . strtolower($clean_roll) . '_' . wp_rand(100, 999);
        }

        $userdata = array(
            'user_login'   => $username,
            'user_email'   => $email,
            'user_pass'    => $pwd,
            'display_name' => $full_name,
            'first_name'   => $full_name,
            'role'         => 'student',
        );

        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => urlencode($user_id->get_error_code()), 'tab' => 'student'), home_url('/login/')));
            exit;
        }

        // Save student scholar metadata
        update_user_meta($user_id, 'mvc_student_roll', $roll_no);
        update_user_meta($user_id, 'mvc_student_degree', $degree);
        update_user_meta($user_id, 'mvc_student_department', $department);
        update_user_meta($user_id, 'mvc_student_session', $session);
        update_user_meta($user_id, 'mvc_student_phone', $phone);
        update_user_meta($user_id, 'mvc_registered_via', 'portal_signup');

        // Automatically log in the registered student
        wp_set_current_user($user_id, $username);
        wp_set_auth_cookie($user_id, true);
        do_action('wp_login', $username, get_userdata($user_id));

        wp_safe_redirect(add_query_arg('auth_msg', 'student_created', home_url('/login/')));
        exit;
    }

    // ACTION C: ALUMNI REGISTRATION
    if ($action === 'register_alumni') {
        if (!isset($_POST['mvc_alumni_reg_nonce']) || !wp_verify_nonce($_POST['mvc_alumni_reg_nonce'], 'mvc_alumni_reg_action')) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'nonce_failed', 'tab' => 'alumni'), home_url('/login/')));
            exit;
        }

        // Honeypot anti-spam check
        if (!empty($_POST['mvc_website_hp'])) {
            wp_safe_redirect(home_url('/login/'));
            exit;
        }

        $full_name    = sanitize_text_field($_POST['alm_full_name']);
        $passing_year = sanitize_text_field($_POST['alm_passing_year']);
        $degree       = sanitize_text_field($_POST['alm_degree']);
        $department   = sanitize_text_field($_POST['alm_department']);
        $profession   = sanitize_text_field($_POST['alm_profession']);
        $location     = sanitize_text_field($_POST['alm_location']);
        $phone        = sanitize_text_field($_POST['alm_phone']);
        $email        = sanitize_email($_POST['alm_email']);
        $pwd          = $_POST['alm_password'];
        $pwd_conf     = $_POST['alm_password_confirm'];

        if (empty($full_name) || empty($passing_year) || empty($email) || empty($pwd)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'empty_fields', 'tab' => 'alumni'), home_url('/login/')));
            exit;
        }

        if ($pwd !== $pwd_conf) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'pwd_mismatch', 'tab' => 'alumni'), home_url('/login/')));
            exit;
        }

        if (strlen($pwd) < 6) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'pwd_too_short', 'tab' => 'alumni'), home_url('/login/')));
            exit;
        }

        if (email_exists($email)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'email_taken', 'tab' => 'alumni'), home_url('/login/')));
            exit;
        }

        $email_parts = explode('@', $email);
        $clean_prefix = preg_replace('/[^a-zA-Z0-9]/', '', $email_parts[0]);
        $username = 'alm_' . strtolower(substr($clean_prefix, 0, 10)) . '_' . $passing_year;
        if (username_exists($username)) {
            $username = 'alm_' . strtolower(substr($clean_prefix, 0, 10)) . '_' . wp_rand(100, 999);
        }

        $userdata = array(
            'user_login'   => $username,
            'user_email'   => $email,
            'user_pass'    => $pwd,
            'display_name' => $full_name,
            'first_name'   => $full_name,
            'role'         => 'alumni_member',
        );

        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => urlencode($user_id->get_error_code()), 'tab' => 'alumni'), home_url('/login/')));
            exit;
        }

        // Save alumni metadata
        update_user_meta($user_id, 'mvc_alumni_degree', $degree);
        update_user_meta($user_id, 'mvc_alumni_department', $department);
        update_user_meta($user_id, 'mvc_alumni_passing_year', $passing_year);
        update_user_meta($user_id, 'mvc_alumni_profession', $profession);
        update_user_meta($user_id, 'mvc_alumni_location', $location);
        update_user_meta($user_id, 'mvc_alumni_phone', $phone);
        update_user_meta($user_id, 'mvc_registered_via', 'alumni_network');

        // Automatically log in the registered alumnus
        wp_set_current_user($user_id, $username);
        wp_set_auth_cookie($user_id, true);
        do_action('wp_login', $username, get_userdata($user_id));

        wp_safe_redirect(add_query_arg('auth_msg', 'alumni_created', home_url('/login/')));
        exit;
    }

    // ACTION D: PASSWORD RESET REQUEST
    if ($action === 'lost_password') {
        if (!isset($_POST['mvc_lost_pwd_nonce']) || !wp_verify_nonce($_POST['mvc_lost_pwd_nonce'], 'mvc_lost_pwd_action')) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'nonce_failed', 'tab' => 'reset'), home_url('/login/')));
            exit;
        }

        $user_login = sanitize_text_field($_POST['user_login']);
        if (empty($user_login)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => 'empty_username', 'tab' => 'reset'), home_url('/login/')));
            exit;
        }

        $result = retrieve_password($user_login);
        if (is_wp_error($result)) {
            wp_safe_redirect(add_query_arg(array('auth_err' => urlencode($result->get_error_code()), 'tab' => 'reset'), home_url('/login/')));
            exit;
        }

        wp_safe_redirect(add_query_arg(array('auth_msg' => 'reset_link_sent', 'tab' => 'reset'), home_url('/login/')));
        exit;
    }
}
add_action('template_redirect', 'mvc_handle_portal_auth_actions', 5);

/* ========================================================
   3. MASTER RENDER FUNCTION: mvc_render_portal_login_page()
   ======================================================== */
function mvc_render_portal_login_page() {
    $current_user = wp_get_current_user();
    $is_logged_in = is_user_logged_in();

    // Auto-redirect if redirect_to parameter is specified and user is already logged in
    if ($is_logged_in && !empty($_GET['redirect_to'])) {
        $custom_dest = esc_url_raw($_GET['redirect_to']);
        if (wp_validate_redirect($custom_dest)) {
            wp_safe_redirect($custom_dest);
            exit;
        }
    }

    // Check for error or success notices
    $auth_err = isset($_GET['auth_err']) ? sanitize_text_field($_GET['auth_err']) : '';
    $auth_msg = isset($_GET['auth_msg']) ? sanitize_text_field($_GET['auth_msg']) : '';
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'signin';

    // Logo URL fallback
    $logo_url = home_url('/wp-content/uploads/settings/website-logo.png');
    if (!file_exists(ABSPATH . 'wp-content/uploads/settings/website-logo.png')) {
        $logo_url = home_url('/wp-content/uploads/2026/10/mv-college-website-logo.png');
    }
    ?>
    <div class="mvc-portal-wrapper">
        <style id="mvc-portal-input-shield-css">
            /* Full-Width Shell with Harmonious Proportioned Content Containers */
            .mvc-portal-wrapper {
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .mvc-portal-hero {
                width: 100% !important;
            }
            .mvc-portal-hero-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 36px clamp(16px, 2.5vw, 40px) 44px !important;
                box-sizing: border-box !important;
            }
            .mvc-portal-body-section {
                width: 100% !important;
                padding: 40px 0 70px 0 !important;
                box-sizing: border-box !important;
            }
            .mvc-portal-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 0 clamp(16px, 2.5vw, 40px) !important;
                box-sizing: border-box !important;
            }
            .mvc-portal-layout-unified {
                width: 100% !important;
                box-sizing: border-box !important;
            }
            /* Master Dual-Wing Authentication Card */
            .mvc-portal-master-gateway {
                display: grid !important;
                grid-template-columns: minmax(360px, 420px) 1fr !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto 48px auto !important;
                background: #FFFFFF !important;
                border-radius: 16px !important;
                border: 1px solid #CBD5E1 !important;
                border-top: 5px solid #D4AF37 !important;
                box-shadow: 0 16px 45px rgba(0, 33, 71, 0.1) !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
            }
            @media (max-width: 900px) {
                .mvc-portal-master-gateway {
                    grid-template-columns: 1fr !important;
                }
            }
            .mvc-portal-services-shelf {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                box-sizing: border-box !important;
            }

            /* Absolute guarantee against theme CSS overrides */
            .mvc-portal-wrapper .mvc-input-wrap {
                position: relative !important;
                display: flex !important;
                align-items: center !important;
                width: 100% !important;
            }
            .mvc-portal-wrapper .mvc-input-icon {
                position: absolute !important;
                left: 14px !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 20px !important;
                height: 20px !important;
                color: #64748B !important;
                pointer-events: none !important;
                z-index: 5 !important;
                line-height: 1 !important;
            }
            .mvc-portal-wrapper .mvc-input-icon svg {
                width: 18px !important;
                height: 18px !important;
                display: block !important;
            }
            /* Input fields wrapped with an icon MUST have 48px left padding */
            .mvc-portal-wrapper .mvc-input-wrap input.mvc-input,
            .mvc-portal-wrapper .mvc-input-wrap input[type="text"],
            .mvc-portal-wrapper .mvc-input-wrap input[type="password"],
            .mvc-portal-wrapper .mvc-input-wrap input[type="email"],
            .mvc-portal-wrapper .mvc-input-wrap input[type="tel"] {
                padding-left: 48px !important;
                padding-right: 16px !important;
                height: 48px !important;
                font-size: 14px !important;
                box-sizing: border-box !important;
                background-color: #F8FAFC !important;
                border: 1.5px solid #CBD5E1 !important;
                border-radius: 9px !important;
            }
            /* Password fields with visibility toggle on right */
            .mvc-portal-wrapper .mvc-input-wrap input.mvc-pwd-input,
            .mvc-portal-wrapper .mvc-input-wrap input[type="password"].mvc-pwd-input {
                padding-left: 48px !important;
                padding-right: 50px !important;
            }
            /* Password Toggle Button */
            .mvc-portal-wrapper .mvc-pwd-toggle {
                position: absolute !important;
                right: 6px !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                width: 38px !important;
                height: 38px !important;
                background: transparent !important;
                border: none !important;
                cursor: pointer !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 6px !important;
                z-index: 6 !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
            }
            .mvc-portal-wrapper .mvc-pwd-toggle:hover {
                background: rgba(0, 33, 71, 0.06) !important;
            }
            /* Regular inputs without icon */
            .mvc-portal-wrapper .mvc-form-group > input.mvc-input:not(.mvc-pwd-input) {
                padding-left: 14px !important;
                padding-right: 14px !important;
                height: 48px !important;
                font-size: 14px !important;
                box-sizing: border-box !important;
                background-color: #F8FAFC !important;
                border: 1.5px solid #CBD5E1 !important;
                border-radius: 9px !important;
            }
            .mvc-portal-wrapper .mvc-select {
                padding-left: 14px !important;
                height: 48px !important;
                border-radius: 9px !important;
                border: 1.5px solid #CBD5E1 !important;
                background-color: #F8FAFC !important;
                box-sizing: border-box !important;
            }
            .mvc-portal-wrapper input.mvc-input:focus,
            .mvc-portal-wrapper select.mvc-select:focus,
            .mvc-portal-wrapper input[type="text"]:focus,
            .mvc-portal-wrapper input[type="password"]:focus,
            .mvc-portal-wrapper input[type="email"]:focus {
                background-color: #FFFFFF !important;
                border-color: #D4AF37 !important;
                box-shadow: 0 0 0 3.5px rgba(212, 175, 55, 0.22) !important;
                outline: none !important;
            }
            @media (max-width: 600px) {
                .mvc-portal-wrapper .mvc-input-wrap input.mvc-input,
                .mvc-portal-wrapper .mvc-input-wrap input[type="text"],
                .mvc-portal-wrapper .mvc-input-wrap input[type="password"],
                .mvc-portal-wrapper .mvc-input-wrap input[type="email"],
                .mvc-portal-wrapper .mvc-form-group > input.mvc-input,
                .mvc-portal-wrapper .mvc-select {
                    font-size: 16px !important;
                    height: 48px !important;
                }
            }
        </style>
        
        <!-- ==========================================
             1. MAJESTIC INSTITUTIONAL HERO HEADER
             ========================================== -->
        <header class="mvc-portal-hero">
            <div class="mvc-portal-hero-container">
                <nav class="mvc-portal-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="mvc-bread-sep">›</span>
                    <span>Institutional Portal</span>
                    <span class="mvc-bread-sep">›</span>
                    <span class="mvc-bread-current">Authentication Gateway</span>
                </nav>

                <div class="mvc-portal-hero-badge-strip">
                    <span class="mvc-hero-pill mvc-pill-gold">🏛️ VKSU Constituent College</span>
                    <span class="mvc-hero-pill mvc-pill-shield">🛡️ UGC 2(f) &amp; 12(B) Recognized</span>
                    <span class="mvc-hero-pill mvc-pill-ssl">🔒 256-Bit SSL Encrypted Portal</span>
                </div>

                <div class="mvc-portal-title-cluster">
                    <div class="mvc-portal-crest-box">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="M.V. College Crest" class="mvc-portal-hero-logo" width="76" height="80">
                    </div>
                    <div class="mvc-portal-hero-text">
                        <h1 class="mvc-portal-main-heading">Official Portal Login &amp; Scholar Gateway</h1>
                        <p class="mvc-portal-sub-heading">महर्षि विश्वामित्र महाविद्यालय, बक्सर — आधिकारिक संस्थागत पोर्टल एवं छात्र-शिक्षक सेवा केंद्र</p>
                        <p class="mvc-portal-tagline">Centralized Single Sign-On (SSO) authentication for Faculty, Academic Staff, Enrolled Scholars, and Alumni Community.</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- ==========================================
             2. UNIFIED INSTITUTIONAL PORTAL BODY
             ========================================== -->
        <section class="mvc-portal-body-section">
            <div class="mvc-portal-container">
                
                <?php if ($is_logged_in) : ?>
                    <!-- ==========================================
                         LOGGED-IN VIP SCHOLAR / STAFF DASHBOARD CARD
                         ========================================== -->
                    <div class="mvc-portal-loggedin-card">
                        <div class="mvc-loggedin-header">
                            <div class="mvc-loggedin-avatar">
                                <?php echo get_avatar($current_user->ID, 72); ?>
                                <span class="mvc-avatar-status-badge">●</span>
                            </div>
                            <div class="mvc-loggedin-details">
                                <span class="mvc-session-tag">CURRENT ACTIVE SESSION</span>
                                <h2 class="mvc-loggedin-name"><?php echo esc_html($current_user->display_name); ?></h2>
                                <p class="mvc-loggedin-meta">
                                    <span><strong>Username:</strong> <?php echo esc_html($current_user->user_login); ?></span>
                                    <span>•</span>
                                    <span><strong>Email:</strong> <?php echo esc_html($current_user->user_email); ?></span>
                                    <span>•</span>
                                    <span><strong>Role:</strong> <span class="mvc-role-pill"><?php echo esc_html(ucwords(str_replace('_', ' ', implode(', ', $current_user->roles)))); ?></span></span>
                                </p>
                            </div>
                        </div>

                        <div class="mvc-loggedin-actions">
                            <?php
                            $user_roles = (array) $current_user->roles;
                            if (in_array('student', $user_roles)) {
                                $dash_url = home_url('/portal/');
                                $dash_title = 'Enter Student Portal Dashboard';
                            } elseif (in_array('alumni_member', $user_roles) || in_array('alumni', $user_roles)) {
                                $dash_url = home_url('/portal/');
                                $dash_title = 'Enter Alumni Portal Dashboard';
                            } elseif (in_array('department_head', $user_roles)) {
                                $dash_url = admin_url('admin.php?page=mvc-command-center&section=my-department');
                                $dash_title = 'Open HOD Department Dashboard';
                            } elseif (in_array('content_manager', $user_roles)) {
                                $dash_url = admin_url('admin.php?page=mvc-command-center&section=review-queue');
                                $dash_title = 'Open Review Desk';
                            } else {
                                $dash_url = admin_url('admin.php?page=mvc-command-center');
                                $dash_title = 'Enter Executive Command Center';
                            }
                            ?>
                            <a href="<?php echo esc_url($dash_url); ?>" class="mvc-btn mvc-btn-maroon" style="font-weight:700; font-size:15px; padding:12px 24px;">
                                <span><?php echo esc_html($dash_title); ?></span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-8.29-1.42 1.42L16.86 11H5v2z"/></svg>
                            </a>

                            <a href="<?php echo esc_url(home_url('/')); ?>" class="mvc-btn mvc-btn-outline">
                                <span>Return to College Homepage</span>
                            </a>

                            <a href="<?php echo esc_url(wp_logout_url(home_url('/login/'))); ?>" class="mvc-btn mvc-btn-logout">
                                <span>Sign Out / Switch Account</span>
                            </a>
                        </div>
                    </div>

                <?php else : ?>

                    <!-- ==========================================
                         NOT LOGGED IN: MASTER DUAL-WING GATEWAY
                         ========================================== -->
                    <div class="mvc-portal-layout-unified">
                        
                        <!-- DYNAMIC ALERT NOTICES -->
                        <?php if (!empty($auth_err)) : ?>
                            <div class="mvc-portal-alert mvc-alert-error" role="alert">
                                <div class="mvc-alert-icon">⚠️</div>
                                <div class="mvc-alert-content">
                                    <strong>Authentication Notice:</strong>
                                    <?php
                                    if ($auth_err === 'invalid_username' || $auth_err === 'incorrect_password' || $auth_err === 'invalid_email') {
                                        echo 'Invalid portal credentials. Please check your username/email and password.';
                                    } elseif ($auth_err === 'empty_fields') {
                                        echo 'Please fill in all required statutory fields to proceed.';
                                    } elseif ($auth_err === 'pwd_mismatch') {
                                        echo 'The chosen passwords do not match. Please re-enter your password.';
                                    } elseif ($auth_err === 'pwd_too_short') {
                                        echo 'Password must be at least 6 characters long for security compliance.';
                                    } elseif ($auth_err === 'email_taken') {
                                        echo 'An institutional account is already registered with this email address. Please sign in or reset your password.';
                                    } elseif ($auth_err === 'nonce_failed') {
                                        echo 'Security token validation expired. Please refresh the page and try again.';
                                    } elseif ($auth_err === 'mvc_rate_limited') {
                                        echo 'Too many consecutive failed login attempts detected. For institutional portal security, access has been temporarily locked for 15 minutes. Please wait before retrying or use the "Reset Portal Password" tab below.';
                                    } else {
                                        echo 'Error: ' . esc_html(str_replace('_', ' ', $auth_err)) . '. Please contact the College Help Desk if this persists.';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($auth_msg)) : ?>
                            <div class="mvc-portal-alert mvc-alert-success" role="alert">
                                <div class="mvc-alert-icon">✅</div>
                                <div class="mvc-alert-content">
                                    <?php
                                    if ($auth_msg === 'student_created') {
                                        echo '<strong>Congratulations!</strong> Your official Student Scholar account has been created and authenticated. You now have portal access.';
                                    } elseif ($auth_msg === 'alumni_created') {
                                        echo '<strong>Welcome Alumnus!</strong> Your registration in the M.V. College Alumni Network has been confirmed.';
                                    } elseif ($auth_msg === 'reset_link_sent') {
                                        echo '<strong>Dispatch Successful:</strong> If an account matching those credentials exists, a secure password reset link has been dispatched to your email.';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- MASTER DUAL-WING GATEWAY CARD -->
                        <div class="mvc-portal-master-gateway">
                            
                            <!-- LEFT WING: INSTITUTIONAL BRAND, SECURITY & HELP DESK -->
                            <aside class="mvc-portal-brand-wing">
                                <div class="mvc-brand-wing-inner">
                                    
                                    <!-- Institutional Crest & Identity -->
                                    <div class="mvc-brand-wing-header">
                                        <div class="mvc-brand-wing-crest-box">
                                            <img src="<?php echo esc_url($logo_url); ?>" alt="M.V. College Crest" class="mvc-brand-wing-logo" width="68" height="72">
                                        </div>
                                        <div class="mvc-brand-wing-title-group">
                                            <span class="mvc-wing-kicker">VKSU CONSTITUENT COLLEGE</span>
                                            <div class="mvc-title-lockup mvc-wing-lockup">
                                                <h2 class="mvc-wing-college-name">Maharshi Vishwamitra Mahavidyalaya</h2>
                                                <p class="mvc-wing-college-hindi">महर्षि विश्वामित्र महाविद्यालय, बक्सर</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SSO Notice Banner -->
                                    <div class="mvc-wing-sso-badge">
                                        <span class="mvc-sso-dot"></span>
                                        <span>Single Sign-On (SSO) Portal</span>
                                    </div>

                                    <!-- 5 Authorized Roles Matrix -->
                                    <div class="mvc-wing-roles-card">
                                        <span class="mvc-wing-sec-title">AUTHORIZED ACCESS ROLES</span>
                                        <div class="mvc-wing-roles-grid">
                                            <div class="mvc-wing-role-item" title="Principal &amp; System Administration">
                                                <span class="mvc-w-role-icon">🏛️</span>
                                                <span class="mvc-w-role-txt">Admin</span>
                                            </div>
                                            <div class="mvc-wing-role-item" title="15 Academic Department Heads">
                                                <span class="mvc-w-role-icon">🎓</span>
                                                <span class="mvc-w-role-txt">Dept Head</span>
                                            </div>
                                            <div class="mvc-wing-role-item" title="Office, Notices, NAAC &amp; Grievance Staff">
                                                <span class="mvc-w-role-icon">📋</span>
                                                <span class="mvc-w-role-txt">Content Mgr</span>
                                            </div>
                                            <div class="mvc-wing-role-item" title="Enrolled UG &amp; PG Scholars">
                                                <span class="mvc-w-role-icon">🧑‍🎓</span>
                                                <span class="mvc-w-role-txt">Student</span>
                                            </div>
                                            <div class="mvc-wing-role-item" title="Alumni Network Members">
                                                <span class="mvc-w-role-icon">🌐</span>
                                                <span class="mvc-w-role-txt">Alumni</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Statutory Cybersecurity Advisory -->
                                    <div class="mvc-wing-advisory-box">
                                        <div class="mvc-wing-advisory-head">
                                            <span class="mvc-advisory-shield">🛡️</span>
                                            <span class="mvc-advisory-title">STATUTORY ACCESS WARNING</span>
                                        </div>
                                        <p class="mvc-wing-advisory-text">Access is restricted strictly to verified faculty, administrative officers, and enrolled students. All authentication requests, IP addresses, and session timestamps are audited under Section 43 &amp; 66 of the Information Technology Act.</p>
                                    </div>

                                    <!-- IT Help Desk & Examination Support Dialer -->
                                    <div class="mvc-wing-helpdesk-box">
                                        <span class="mvc-wing-sec-title">EXAMINATION &amp; IT HELP DESK</span>
                                        <div class="mvc-wing-contact-list">
                                            <a href="tel:06183796317" class="mvc-wing-contact-chip">
                                                <span class="mvc-w-icon">📞</span>
                                                <div class="mvc-w-chip-info">
                                                    <span class="mvc-w-chip-sub">Helpline (Mon–Sat 10am–4pm)</span>
                                                    <span class="mvc-w-chip-val">06183-796317</span>
                                                </div>
                                            </a>
                                            <a href="mailto:mvcollegebuxar@gmail.com" class="mvc-wing-contact-chip">
                                                <span class="mvc-w-icon">✉️</span>
                                                <div class="mvc-w-chip-info">
                                                    <span class="mvc-w-chip-sub">Official Support Email</span>
                                                    <span class="mvc-w-chip-val">mvcollegebuxar@gmail.com</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </aside>

                            <!-- RIGHT WING: INTERACTIVE AUTHENTICATION SUITE -->
                            <main class="mvc-portal-auth-wing">
                                <div class="mvc-portal-card">
                                    
                                    <!-- NAVIGATION TABS -->
                                    <div class="mvc-portal-nav-tabs" role="tablist">
                                        <button type="button" class="mvc-tab-btn <?php echo ($active_tab === 'signin') ? 'active' : ''; ?>" data-target="tab-signin" role="tab" aria-selected="<?php echo ($active_tab === 'signin') ? 'true' : 'false'; ?>">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                                            <span class="mvc-tab-text-full">Sign In (लॉगिन)</span>
                                            <span class="mvc-tab-text-mob">Sign In</span>
                                        </button>

                                        <button type="button" class="mvc-tab-btn <?php echo ($active_tab === 'student') ? 'active' : ''; ?>" data-target="tab-student" role="tab" aria-selected="<?php echo ($active_tab === 'student') ? 'true' : 'false'; ?>">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                                            <span class="mvc-tab-text-full">Student Sign-Up</span>
                                            <span class="mvc-tab-text-mob">Student</span>
                                        </button>

                                        <button type="button" class="mvc-tab-btn <?php echo ($active_tab === 'alumni') ? 'active' : ''; ?>" data-target="tab-alumni" role="tab" aria-selected="<?php echo ($active_tab === 'alumni') ? 'true' : 'false'; ?>">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                            <span class="mvc-tab-text-full">Alumni Network</span>
                                            <span class="mvc-tab-text-mob">Alumni</span>
                                        </button>

                                        <button type="button" class="mvc-tab-btn <?php echo ($active_tab === 'reset') ? 'active' : ''; ?>" data-target="tab-reset" role="tab" aria-selected="<?php echo ($active_tab === 'reset') ? 'true' : 'false'; ?>">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                            <span class="mvc-tab-text-full">Forgot Password?</span>
                                            <span class="mvc-tab-text-mob">Reset</span>
                                        </button>
                                    </div>

                                    <!-- TAB PANELS CONTAINER -->
                                    <div class="mvc-portal-tab-content">
                                        
                                        <!-- ==========================================
                                             TAB 1: INSTITUTIONAL SIGN IN
                                             ========================================== -->
                                        <div class="mvc-tab-panel <?php echo ($active_tab === 'signin') ? 'active' : ''; ?>" id="tab-signin" role="tabpanel">
                                            <div class="mvc-tab-header">
                                                <h2 class="mvc-tab-title">Sign In to Institutional Portal</h2>
                                                <p class="mvc-tab-desc">Enter your verified college credentials to access your administrative, departmental, or academic account.</p>
                                            </div>

                                            <!-- Multi-Role Unified Access Badges (Quick Reference) -->
                                            <div class="mvc-portal-roles-guide">
                                                <span class="mvc-guide-label">Accepted Roles:</span>
                                                <span class="mvc-role-chip" title="Principal / System Administration">🏛️ Admin</span>
                                                <span class="mvc-role-chip" title="15 Academic Department Heads">🎓 Dept Head</span>
                                                <span class="mvc-role-chip" title="Office, Notices, NAAC, Media">📋 Content Manager</span>
                                                <span class="mvc-role-chip" title="Enrolled Undergrad / Postgrad Students">🧑‍🎓 Student</span>
                                                <span class="mvc-role-chip" title="Registered Alumni Members">🌐 Alumni</span>
                                            </div>

                                            <form method="post" action="<?php echo esc_url(home_url('/login/')); ?>" class="mvc-auth-form" id="mvcLoginForm">
                                                <?php wp_nonce_field('mvc_login_action', 'mvc_login_nonce'); ?>
                                                <input type="hidden" name="mvc_auth_action" value="login">

                                                <div class="mvc-form-group">
                                                    <label for="mvc_user_login" class="mvc-label">
                                                        <span>Username or Official Email</span>
                                                        <span class="mvc-req">*</span>
                                                    </label>
                                                    <div class="mvc-input-wrap">
                                                        <span class="mvc-input-icon">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                        </span>
                                                        <input type="text" id="mvc_user_login" name="log" class="mvc-input" placeholder="e.g. admin / dept_botany / std_202401" required autocomplete="username">
                                                    </div>
                                                </div>

                                                <div class="mvc-form-group">
                                                    <div class="mvc-label-row">
                                                        <label for="mvc_user_pwd" class="mvc-label">
                                                            <span>Password (पासवर्ड)</span>
                                                            <span class="mvc-req">*</span>
                                                        </label>
                                                        <a href="#reset" class="mvc-inline-link mvc-switch-tab" data-target="tab-reset">Forgot Password?</a>
                                                    </div>
                                                    <div class="mvc-input-wrap">
                                                        <span class="mvc-input-icon">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                        </span>
                                                        <input type="password" id="mvc_user_pwd" name="pwd" class="mvc-input mvc-pwd-input" placeholder="Enter your confidential password" required autocomplete="current-password">
                                                        <button type="button" class="mvc-pwd-toggle" aria-label="Toggle password visibility">
                                                            <svg class="mvc-eye-svg mvc-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                            <svg class="mvc-eye-svg mvc-eye-closed" style="display:none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-utility-row">
                                                    <label class="mvc-checkbox-label">
                                                        <input type="checkbox" name="rememberme" value="forever" class="mvc-checkbox">
                                                        <span class="mvc-checkbox-custom"></span>
                                                        <span>Remember this device for 14 days</span>
                                                    </label>
                                                </div>

                                                <div class="mvc-form-submit-row">
                                                    <button type="submit" class="mvc-submit-btn mvc-btn-gold">
                                                        <span>Sign In to Official Portal →</span>
                                                    </button>
                                                </div>
                                            </form>

                                            <div class="mvc-tab-footer-callout">
                                                <span>Not yet registered?</span>
                                                <a href="#student" class="mvc-callout-link mvc-switch-tab" data-target="tab-student">Create Student Account</a>
                                                <span>or</span>
                                                <a href="#alumni" class="mvc-callout-link mvc-switch-tab" data-target="tab-alumni">Join Alumni Network</a>
                                            </div>
                                        </div>

                                        <!-- ==========================================
                                             TAB 2: STUDENT SIGN-UP / REGISTRATION
                                             ========================================== -->
                                        <div class="mvc-tab-panel <?php echo ($active_tab === 'student') ? 'active' : ''; ?>" id="tab-student" role="tabpanel">
                                            <div class="mvc-tab-header">
                                                <h2 class="mvc-tab-title">New Student Portal Registration</h2>
                                                <p class="mvc-tab-desc">For currently enrolled undergraduate and postgraduate scholars of M.V. College, Buxar.</p>
                                            </div>

                                            <form method="post" action="<?php echo esc_url(home_url('/login/')); ?>" class="mvc-auth-form" id="mvcStudentForm">
                                                <?php wp_nonce_field('mvc_student_reg_action', 'mvc_student_reg_nonce'); ?>
                                                <input type="hidden" name="mvc_auth_action" value="register_student">
                                                <!-- Anti-spam Honeypot -->
                                                <input type="text" name="mvc_website_hp" style="display:none !important;" tabindex="-1" autocomplete="off">

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="std_full_name" class="mvc-label">Full Name of Student <span class="mvc-req">*</span></label>
                                                        <input type="text" id="std_full_name" name="std_full_name" class="mvc-input" placeholder="e.g. Ramesh Kumar Singh" required>
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="std_roll_no" class="mvc-label">University Roll / Reg. No. <span class="mvc-req">*</span></label>
                                                        <input type="text" id="std_roll_no" name="std_roll_no" class="mvc-input" placeholder="e.g. 2410105001" required>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="std_degree" class="mvc-label">Degree Program <span class="mvc-req">*</span></label>
                                                        <select id="std_degree" name="std_degree" class="mvc-select" required>
                                                            <option value="">-- Select Degree --</option>
                                                            <option value="B.A. Honours">B.A. (Honours)</option>
                                                            <option value="B.Sc. Honours">B.Sc. (Honours)</option>
                                                            <option value="B.C.A. Vocational">B.C.A. (Computer Applications)</option>
                                                            <option value="B.B.A. Vocational">B.B.A. (Business Management)</option>
                                                            <option value="M.A. Postgrad">M.A. (Postgraduate)</option>
                                                            <option value="M.Sc. Postgrad">M.Sc. (Postgraduate)</option>
                                                        </select>
                                                    </div>

                                                    <div class="mvc-form-group">
                                                        <label for="std_department" class="mvc-label">Major Department <span class="mvc-req">*</span></label>
                                                        <select id="std_department" name="std_department" class="mvc-select" required>
                                                            <option value="">-- Select Department --</option>
                                                            <option value="Botany">Botany (वनस्पति विज्ञान)</option>
                                                            <option value="Chemistry">Chemistry (रसायन विज्ञान)</option>
                                                            <option value="Computer Applications">Computer Applications (BCA)</option>
                                                            <option value="Business Management">Business Management (BBA)</option>
                                                            <option value="Economics">Economics (अर्थशास्त्र)</option>
                                                            <option value="English">English (अंग्रेजी)</option>
                                                            <option value="Hindi">Hindi (हिन्दी)</option>
                                                            <option value="History">History (इतिहास)</option>
                                                            <option value="Mathematics">Mathematics (गणित)</option>
                                                            <option value="Philosophy">Philosophy (दर्शनशास्त्र)</option>
                                                            <option value="Physics">Physics (भौतिक विज्ञान)</option>
                                                            <option value="Political Science">Political Science (राजनीति विज्ञान)</option>
                                                            <option value="Psychology">Psychology (मनोविज्ञान)</option>
                                                            <option value="Sanskrit">Sanskrit (संस्कृत)</option>
                                                            <option value="Zoology">Zoology (प्राणी विज्ञान)</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="std_session" class="mvc-label">Academic Session <span class="mvc-req">*</span></label>
                                                        <select id="std_session" name="std_session" class="mvc-select" required>
                                                            <option value="2026-2030">2026–2030 (4-Yr FYUGP)</option>
                                                            <option value="2025-2029">2025–2029 (4-Yr FYUGP)</option>
                                                            <option value="2024-2028" selected>2024–2028 (4-Yr FYUGP)</option>
                                                            <option value="2023-2027">2023–2027 (4-Yr FYUGP)</option>
                                                            <option value="2024-2026">2024–2026 (Postgraduate)</option>
                                                            <option value="2023-2025">2023–2025 (Postgraduate)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="std_phone" class="mvc-label">Mobile Number <span class="mvc-req">*</span></label>
                                                        <input type="tel" id="std_phone" name="std_phone" class="mvc-input" placeholder="e.g. 9876543210" required pattern="[0-9]{10}">
                                                    </div>
                                                </div>

                                                <div class="mvc-form-group">
                                                    <label for="std_email" class="mvc-label">Official / Active Email Address <span class="mvc-req">*</span></label>
                                                    <input type="email" id="std_email" name="std_email" class="mvc-input" placeholder="e.g. student.name@gmail.com" required>
                                                    <span class="mvc-field-help">Your portal activation receipts and examination alerts will be sent here.</span>
                                                </div>

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="std_password" class="mvc-label">Create Password <span class="mvc-req">*</span></label>
                                                        <div class="mvc-input-wrap">
                                                            <span class="mvc-input-icon">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                            </span>
                                                            <input type="password" id="std_password" name="std_password" class="mvc-input mvc-pwd-input mvc-pwd-meter" placeholder="Min. 6 characters" required minlength="6">
                                                            <button type="button" class="mvc-pwd-toggle" aria-label="Toggle password visibility">
                                                                <svg class="mvc-eye-svg mvc-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                <svg class="mvc-eye-svg mvc-eye-closed" style="display:none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                                            </button>
                                                        </div>
                                                        <div class="mvc-pwd-strength-container" id="std_pwd_strength">
                                                            <div class="mvc-strength-bars">
                                                                <span class="mvc-str-bar"></span>
                                                                <span class="mvc-str-bar"></span>
                                                                <span class="mvc-str-bar"></span>
                                                                <span class="mvc-str-bar"></span>
                                                            </div>
                                                            <span class="mvc-strength-text">Password Strength</span>
                                                        </div>
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="std_password_confirm" class="mvc-label">Confirm Password <span class="mvc-req">*</span></label>
                                                        <div class="mvc-input-wrap">
                                                            <span class="mvc-input-icon">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                            </span>
                                                            <input type="password" id="std_password_confirm" name="std_password_confirm" class="mvc-input mvc-pwd-input" placeholder="Re-enter password" required minlength="6">
                                                            <button type="button" class="mvc-pwd-toggle" aria-label="Toggle password visibility">
                                                                <svg class="mvc-eye-svg mvc-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                <svg class="mvc-eye-svg mvc-eye-closed" style="display:none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-utility-row">
                                                    <label class="mvc-checkbox-label">
                                                        <input type="checkbox" name="statutory_undertaking" value="1" required class="mvc-checkbox">
                                                        <span class="mvc-checkbox-custom"></span>
                                                        <span>I solemnly confirm that I am a bonafide student of M.V. College, Buxar and accept all institutional rules.</span>
                                                    </label>
                                                </div>

                                                <div class="mvc-form-submit-row">
                                                    <button type="submit" class="mvc-submit-btn mvc-btn-gold">
                                                        <span>Create Student Portal Account →</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- ==========================================
                                             TAB 3: ALUMNI NETWORK REGISTRATION
                                             ========================================== -->
                                        <div class="mvc-tab-panel <?php echo ($active_tab === 'alumni') ? 'active' : ''; ?>" id="tab-alumni" role="tabpanel">
                                            <div class="mvc-tab-header">
                                                <h2 class="mvc-tab-title">M.V. College Alumni Association Enrollment</h2>
                                                <p class="mvc-tab-desc">Connect with your alma mater, mentor junior scholars, and stay engaged with collegiate legacy networks.</p>
                                            </div>

                                            <form method="post" action="<?php echo esc_url(home_url('/login/')); ?>" class="mvc-auth-form" id="mvcAlumniForm">
                                                <?php wp_nonce_field('mvc_alumni_reg_action', 'mvc_alumni_reg_nonce'); ?>
                                                <input type="hidden" name="mvc_auth_action" value="register_alumni">
                                                <!-- Anti-spam Honeypot -->
                                                <input type="text" name="mvc_website_hp" style="display:none !important;" tabindex="-1" autocomplete="off">

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="alm_full_name" class="mvc-label">Full Name of Alumnus <span class="mvc-req">*</span></label>
                                                        <input type="text" id="alm_full_name" name="alm_full_name" class="mvc-input" placeholder="e.g. Dr. Anand Mohan Prasad" required>
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="alm_passing_year" class="mvc-label">Year of Passing / Graduation <span class="mvc-req">*</span></label>
                                                        <select id="alm_passing_year" name="alm_passing_year" class="mvc-select" required>
                                                            <option value="">-- Select Year --</option>
                                                            <?php
                                                            for ($yr = 2025; $yr >= 1958; $yr--) {
                                                                echo '<option value="' . esc_attr($yr) . '">' . esc_html($yr) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="alm_degree" class="mvc-label">Degree Completed <span class="mvc-req">*</span></label>
                                                        <select id="alm_degree" name="alm_degree" class="mvc-select" required>
                                                            <option value="">-- Select Degree --</option>
                                                            <option value="B.A. Honours">B.A. (Honours)</option>
                                                            <option value="B.Sc. Honours">B.Sc. (Honours)</option>
                                                            <option value="B.C.A. Vocational">B.C.A. (Computer Applications)</option>
                                                            <option value="B.B.A. Vocational">B.B.A. (Business Management)</option>
                                                            <option value="M.A. Postgrad">M.A. (Postgraduate)</option>
                                                            <option value="M.Sc. Postgrad">M.Sc. (Postgraduate)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="alm_department" class="mvc-label">Department Studied <span class="mvc-req">*</span></label>
                                                        <select id="alm_department" name="alm_department" class="mvc-select" required>
                                                            <option value="">-- Select Department --</option>
                                                            <option value="Botany">Botany</option>
                                                            <option value="Chemistry">Chemistry</option>
                                                            <option value="Computer Applications">Computer Applications</option>
                                                            <option value="Business Management">Business Management</option>
                                                            <option value="Economics">Economics</option>
                                                            <option value="English">English</option>
                                                            <option value="Hindi">Hindi</option>
                                                            <option value="History">History</option>
                                                            <option value="Mathematics">Mathematics</option>
                                                            <option value="Philosophy">Philosophy</option>
                                                            <option value="Physics">Physics</option>
                                                            <option value="Political Science">Political Science</option>
                                                            <option value="Psychology">Psychology</option>
                                                            <option value="Sanskrit">Sanskrit</option>
                                                            <option value="Zoology">Zoology</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="alm_profession" class="mvc-label">Current Profession / Title</label>
                                                        <input type="text" id="alm_profession" name="alm_profession" class="mvc-input" placeholder="e.g. Assistant Professor, IAS, Software Engineer">
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="alm_location" class="mvc-label">Current City / Country</label>
                                                        <input type="text" id="alm_location" name="alm_location" class="mvc-input" placeholder="e.g. Patna, New Delhi, Bengaluru">
                                                    </div>
                                                </div>

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="alm_phone" class="mvc-label">Mobile Number <span class="mvc-req">*</span></label>
                                                        <input type="tel" id="alm_phone" name="alm_phone" class="mvc-input" placeholder="e.g. 9876543210" required>
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="alm_email" class="mvc-label">Official / Personal Email <span class="mvc-req">*</span></label>
                                                        <input type="email" id="alm_email" name="alm_email" class="mvc-input" placeholder="e.g. alumni@gmail.com" required>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-row mvc-grid-2">
                                                    <div class="mvc-form-group">
                                                        <label for="alm_password" class="mvc-label">Create Portal Password <span class="mvc-req">*</span></label>
                                                        <div class="mvc-input-wrap">
                                                            <span class="mvc-input-icon">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                            </span>
                                                            <input type="password" id="alm_password" name="alm_password" class="mvc-input mvc-pwd-input mvc-pwd-meter" placeholder="Min. 6 characters" required minlength="6">
                                                            <button type="button" class="mvc-pwd-toggle" aria-label="Toggle password visibility">
                                                                <svg class="mvc-eye-svg mvc-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                <svg class="mvc-eye-svg mvc-eye-closed" style="display:none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                                            </button>
                                                        </div>
                                                        <div class="mvc-pwd-strength-container" id="alm_pwd_strength">
                                                            <div class="mvc-strength-bars">
                                                                <span class="mvc-str-bar"></span>
                                                                <span class="mvc-str-bar"></span>
                                                                <span class="mvc-str-bar"></span>
                                                                <span class="mvc-str-bar"></span>
                                                            </div>
                                                            <span class="mvc-strength-text">Password Strength</span>
                                                        </div>
                                                    </div>
                                                    <div class="mvc-form-group">
                                                        <label for="alm_password_confirm" class="mvc-label">Confirm Password <span class="mvc-req">*</span></label>
                                                        <div class="mvc-input-wrap">
                                                            <span class="mvc-input-icon">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                            </span>
                                                            <input type="password" id="alm_password_confirm" name="alm_password_confirm" class="mvc-input mvc-pwd-input" placeholder="Re-enter password" required minlength="6">
                                                            <button type="button" class="mvc-pwd-toggle" aria-label="Toggle password visibility">
                                                                <svg class="mvc-eye-svg mvc-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                <svg class="mvc-eye-svg mvc-eye-closed" style="display:none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mvc-form-submit-row">
                                                    <button type="submit" class="mvc-submit-btn mvc-btn-gold">
                                                        <span>Register in Alumni Network →</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- ==========================================
                                             TAB 4: PASSWORD RECOVERY / RESET
                                             ========================================== -->
                                        <div class="mvc-tab-panel <?php echo ($active_tab === 'reset') ? 'active' : ''; ?>" id="tab-reset" role="tabpanel">
                                            <div class="mvc-tab-header">
                                                <h2 class="mvc-tab-title">Portal Password Recovery</h2>
                                                <p class="mvc-tab-desc">Forgot your portal password? Enter your registered email address or username. A secure reset authorization link will be generated.</p>
                                            </div>

                                            <form method="post" action="<?php echo esc_url(home_url('/login/')); ?>" class="mvc-auth-form" id="mvcLostPasswordForm">
                                                <?php wp_nonce_field('mvc_lost_pwd_action', 'mvc_lost_pwd_nonce'); ?>
                                                <input type="hidden" name="mvc_auth_action" value="lost_password">

                                                <div class="mvc-form-group">
                                                    <label for="mvc_reset_login" class="mvc-label">
                                                        <span>Registered Username or Email</span>
                                                        <span class="mvc-req">*</span>
                                                    </label>
                                                    <div class="mvc-input-wrap">
                                                        <span class="mvc-input-icon">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                                        </span>
                                                        <input type="text" id="mvc_reset_login" name="user_login" class="mvc-input" placeholder="Enter username or official email address" required autocomplete="username">
                                                    </div>
                                                </div>

                                                <div class="mvc-form-submit-row">
                                                    <button type="submit" class="mvc-submit-btn mvc-btn-maroon">
                                                        <span>Send Password Reset Link →</span>
                                                    </button>
                                                </div>
                                            </form>

                                            <div class="mvc-tab-footer-callout">
                                                <span>Remembered your password?</span>
                                                <a href="#signin" class="mvc-callout-link mvc-switch-tab" data-target="tab-signin">Return to Sign In</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </main>

                        </div>

                        <!-- ==========================================
                             GROUNDING SHELF: 5 PUBLIC DIRECT ACCESS SERVICES
                             ========================================== -->
                        <div class="mvc-portal-services-shelf">
                            <div class="mvc-services-shelf-header">
                                <span class="mvc-shelf-kicker">DIRECT CITIZEN &amp; SCHOLAR ACCESS</span>
                                <h3 class="mvc-shelf-title">Public Institutional Services</h3>
                                <p class="mvc-shelf-desc">Instant direct access to statutory university resources, results, admissions, and library catalogs without requiring login.</p>
                            </div>

                            <div class="mvc-services-grid">
                                <a href="https://mvcollegebuxarmanage.com/" target="_blank" rel="noopener" class="mvc-service-card">
                                    <div class="mvc-service-icon-box">🎓</div>
                                    <div class="mvc-service-body">
                                        <span class="mvc-service-badge">ADMISSION 2026–27</span>
                                        <h4 class="mvc-service-name">Online Admission Portal</h4>
                                        <p class="mvc-service-info">Direct application gateway for undergraduate &amp; postgraduate courses.</p>
                                    </div>
                                    <span class="mvc-service-arrow">↗</span>
                                </a>

                                <a href="<?php echo esc_url(home_url('/result/')); ?>" class="mvc-service-card">
                                    <div class="mvc-service-icon-box">📑</div>
                                    <div class="mvc-service-body">
                                        <span class="mvc-service-badge">VKSU ARRAH</span>
                                        <h4 class="mvc-service-name">Examination Results</h4>
                                        <p class="mvc-service-info">Official semester marks, marksheets, and university grade cards.</p>
                                    </div>
                                    <span class="mvc-service-arrow">→</span>
                                </a>

                                <a href="<?php echo esc_url(home_url('/student-grievance/')); ?>" class="mvc-service-card">
                                    <div class="mvc-service-icon-box">⚖️</div>
                                    <div class="mvc-service-body">
                                        <span class="mvc-service-badge">STATUTORY CELL</span>
                                        <h4 class="mvc-service-name">Grievance Redressal</h4>
                                        <p class="mvc-service-info">Lodge official academic or campus complaints online directly.</p>
                                    </div>
                                    <span class="mvc-service-arrow">→</span>
                                </a>

                                <a href="<?php echo esc_url(home_url('/library/')); ?>" class="mvc-service-card">
                                    <div class="mvc-service-icon-box">📚</div>
                                    <div class="mvc-service-body">
                                        <span class="mvc-service-badge">OPAC CATALOG</span>
                                        <h4 class="mvc-service-name">Central Library &amp; OPAC</h4>
                                        <p class="mvc-service-info">Search 45,000+ textbooks, academic journals, and digital books.</p>
                                    </div>
                                    <span class="mvc-service-arrow">→</span>
                                </a>

                                <a href="<?php echo esc_url(home_url('/anti-ragging-cell/')); ?>" class="mvc-service-card">
                                    <div class="mvc-service-icon-box">🛡️</div>
                                    <div class="mvc-service-body">
                                        <span class="mvc-service-badge">UGC COMPLIANCE</span>
                                        <h4 class="mvc-service-name">Anti-Ragging Cell</h4>
                                        <p class="mvc-service-info">Mandatory statutory regulations, committee roster, &amp; affidavits.</p>
                                    </div>
                                    <span class="mvc-service-arrow">→</span>
                                </a>
                            </div>
                        </div>

                    </div>

                <?php endif; ?>

            </div>
        </section>

    </div>

    <!-- CLIENT-SIDE TAB SWITCHING & PASSWORD TOGGLE LOGIC -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Tab Switching Logic
        var tabBtns = document.querySelectorAll('.mvc-tab-btn');
        var tabPanels = document.querySelectorAll('.mvc-tab-panel');
        var switchLinks = document.querySelectorAll('.mvc-switch-tab');

        function activateTab(targetId) {
            tabBtns.forEach(function(b) {
                if (b.getAttribute('data-target') === targetId) {
                    b.classList.add('active');
                    b.setAttribute('aria-selected', 'true');
                } else {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                }
            });

            tabPanels.forEach(function(p) {
                if (p.id === targetId) {
                    p.classList.add('active');
                } else {
                    p.classList.remove('active');
                }
            });
        }

        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var target = this.getAttribute('data-target');
                activateTab(target);
            });
        });

        switchLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var target = this.getAttribute('data-target');
                activateTab(target);
            });
        });

        // Hash-based automatic tab activation on page load
        if (window.location.hash) {
            var hash = window.location.hash.replace('#', '');
            if (hash === 'student') activateTab('tab-student');
            if (hash === 'alumni') activateTab('tab-alumni');
            if (hash === 'reset') activateTab('tab-reset');
            if (hash === 'signin') activateTab('tab-signin');
        }

        // 2. Vector SVG Password Visibility Toggle
        var pwdToggles = document.querySelectorAll('.mvc-pwd-toggle');
        pwdToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                var wrap = this.closest('.mvc-input-wrap');
                var input = wrap ? wrap.querySelector('.mvc-pwd-input') : null;
                var eyeOpen = this.querySelector('.mvc-eye-open');
                var eyeClosed = this.querySelector('.mvc-eye-closed');
                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        if (eyeOpen) eyeOpen.style.display = 'none';
                        if (eyeClosed) eyeClosed.style.display = 'block';
                    } else {
                        input.type = 'password';
                        if (eyeOpen) eyeOpen.style.display = 'block';
                        if (eyeClosed) eyeClosed.style.display = 'none';
                    }
                }
            });
        });

        // 3. Live Password Strength Meter
        function evaluateStrength(val) {
            if (!val || val.length === 0) return 0;
            var score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            return score;
        }

        function updateMeter(inputEl, containerId) {
            var cont = document.getElementById(containerId);
            if (!cont) return;
            var bars = cont.querySelectorAll('.mvc-str-bar');
            var textEl = cont.querySelector('.mvc-strength-text');
            var score = evaluateStrength(inputEl.value);

            var labels = ['Too Short', 'Weak', 'Fair', 'Good', 'Strong'];
            var colors = ['#CBD5E1', '#EF4444', '#F59E0B', '#10B981', '#059669'];

            bars.forEach(function(bar, idx) {
                if (idx < score) {
                    bar.style.backgroundColor = colors[score];
                } else {
                    bar.style.backgroundColor = '#E2E8F0';
                }
            });

            if (inputEl.value.length === 0) {
                textEl.textContent = 'Password Strength';
                textEl.style.color = '#94A3B8';
            } else {
                textEl.textContent = 'Security: ' + labels[score];
                textEl.style.color = colors[score];
            }
        }

        var stdPwd = document.getElementById('std_password');
        if (stdPwd) {
            stdPwd.addEventListener('input', function() {
                updateMeter(this, 'std_pwd_strength');
            });
        }

        var almPwd = document.getElementById('alm_password');
        if (almPwd) {
            almPwd.addEventListener('input', function() {
                updateMeter(this, 'alm_pwd_strength');
            });
        }
    });
    </script>
    <?php
}
