<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Institutional Global Settings & Configuration Engine
 *
 * Ensures /wp-admin/admin.php?page=college-global-settings is 100% accessible,
 * fully functional, and synchronizes all institutional identity, contact, email,
 * telecommunication, and maintenance settings across the entire portal.
 *
 * Fully compatible with ACF (Free & Pro), native WordPress options, and theme builders.
 *
 * @package Astra_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Register Global Settings Admin Menu
 */
function mvc_register_global_settings_menu() {
    global $admin_page_hooks;

    // Register top-level menu page if not already registered
    if (empty($admin_page_hooks['college-global-settings'])) {
        add_menu_page(
            __('College Global Settings', 'mvc-child'),
            __('Global Settings', 'mvc-child'),
            'edit_posts', // Accessible to Administrators, Content Managers, and authorized staff
            'college-global-settings',
            'mvc_render_global_settings_page',
            'dashicons-admin-site-alt3',
            30
        );

        // Ensure the primary sub-item reads clearly
        add_submenu_page(
            'college-global-settings',
            __('Institutional Global Settings', 'mvc-child'),
            __('🏛️ Identity & Contacts', 'mvc-child'),
            'edit_posts',
            'college-global-settings',
            'mvc_render_global_settings_page'
        );
    }
}
add_action('admin_menu', 'mvc_register_global_settings_menu', 10);

/**
 * 2. Handle Settings Save Action
 */
function mvc_handle_global_settings_save() {
    if (!isset($_POST['mvc_save_global_settings'])) {
        return;
    }

    if (!current_user_can('edit_posts') && !current_user_can('manage_options')) {
        wp_die(__('You do not have permission to modify college settings.', 'mvc-child'));
    }

    check_admin_referer('mvc_save_global_settings_action', 'mvc_global_settings_nonce');

    $text_fields = array(
        'college_name'               => 'sanitize_text_field',
        'college_name_hi'            => 'sanitize_text_field',
        'college_tagline'            => 'sanitize_text_field',
        'campus_address'             => 'sanitize_textarea_field',
        'office_hours'               => 'sanitize_text_field',
        'counter_hours'              => 'sanitize_text_field',
        'contact_phone'              => 'sanitize_text_field',
        'college_phone'              => 'sanitize_text_field',
        'alt_phone'                  => 'sanitize_text_field',
        'college_email'              => 'sanitize_email',
        'contact_email'              => 'sanitize_email',
        'principal_email'            => 'sanitize_email',
        'admission_email'            => 'sanitize_email',
        'examination_email'          => 'sanitize_email',
        'library_email'              => 'sanitize_email',
        'accounts_email'             => 'sanitize_email',
        'grievance_email'            => 'sanitize_email',
        'antiragging_email'          => 'sanitize_email',
        'icc_email'                  => 'sanitize_email',
        'pio_email'                  => 'sanitize_email',
        'proctor_email'              => 'sanitize_email',
        'studentrep_email'           => 'sanitize_email',
        'alumni_email'               => 'sanitize_email',
        'canonical_site_url'         => 'esc_url_raw',
        'production_site_url'        => 'esc_url_raw',
        'maintenance_notice_title'   => 'sanitize_text_field',
        'maintenance_notice_message' => 'sanitize_textarea_field',
    );

    $settings = get_option('mvc_global_settings', array());
    if (!is_array($settings)) {
        $settings = array();
    }

    foreach ($text_fields as $field_key => $sanitizer) {
        if (isset($_POST[$field_key])) {
            $raw_val = wp_unslash($_POST[$field_key]);
            $clean_val = call_user_func($sanitizer, $raw_val);
            $settings[$field_key] = $clean_val;

            // 1. Update ACF Option format (options_{field})
            update_option('options_' . $field_key, $clean_val);
            // 2. Update Direct individual option (mvc_{field})
            update_option('mvc_' . $field_key, $clean_val);
        }
    }

    // Maintenance Mode Toggle
    $maint_enabled = !empty($_POST['maintenance_mode_enabled']) ? 1 : 0;
    $settings['maintenance_mode_enabled'] = $maint_enabled;
    update_option('options_maintenance_mode_enabled', $maint_enabled);
    update_option('mvc_maintenance_mode_enabled', $maint_enabled);

    if (function_exists('mvc_set_maintenance_mode')) {
        mvc_set_maintenance_mode((bool) $maint_enabled, array(
            'notice_title'   => $settings['maintenance_notice_title'] ?? '',
            'notice_message' => $settings['maintenance_notice_message'] ?? '',
        ));
    }

    // Consolidated global settings array
    update_option('mvc_global_settings', $settings);

    // Audit Logging
    if (function_exists('mvc_audit')) {
        mvc_audit('global_settings_updated', 'settings', 0, array(
            'updated_by' => wp_get_current_user()->user_login,
            'time'       => current_time('mysql'),
        ));
    }

    // Redirect back with success message
    $redirect_url = add_query_arg(array('page' => 'college-global-settings', 'updated' => '1'), admin_url('admin.php'));
    wp_safe_redirect($redirect_url);
    exit;
}
add_action('admin_init', 'mvc_handle_global_settings_save');

/**
 * 3. Render Prestigious 10/10 Institutional Global Settings Page
 */
function mvc_render_global_settings_page() {
    if (!current_user_can('edit_posts') && !current_user_can('manage_options')) {
        wp_die(__('You do not have permission to access College Global Settings.', 'mvc-child'));
    }

    $is_saved = !empty($_GET['updated']) && $_GET['updated'] === '1';

    // Helper to get current setting value
    $val = function($key, $default = '') {
        if (function_exists('mvc_get_global_setting')) {
            return mvc_get_global_setting($key, $default);
        }
        $settings = get_option('mvc_global_settings', array());
        if (is_array($settings) && isset($settings[$key])) {
            return $settings[$key];
        }
        return get_option('mvc_' . $key, get_option('options_' . $key, $default));
    };

    $maint_enabled = (bool) $val('maintenance_mode_enabled', false);
    ?>
    <div class="wrap mvc-gs-wrap">
        <style>
            :root {
                --mvc-gs-navy: #002147;
                --mvc-gs-navy-dark: #001229;
                --mvc-gs-gold: #D4AF37;
                --mvc-gs-gold-light: #fef08a;
                --mvc-gs-maroon: #800000;
                --mvc-gs-slate-50: #f8fafc;
                --mvc-gs-slate-100: #f1f5f9;
                --mvc-gs-slate-200: #e2e8f0;
                --mvc-gs-slate-600: #475569;
                --mvc-gs-slate-700: #334155;
                --mvc-gs-slate-800: #1e293b;
            }

            .mvc-gs-wrap {
                font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
                margin: 20px 20px 40px 2px;
                color: var(--mvc-gs-slate-800);
            }

            .mvc-gs-header {
                background: linear-gradient(135deg, var(--mvc-gs-navy-dark) 0%, var(--mvc-gs-navy) 65%, #052c5c 100%);
                color: #ffffff;
                padding: 28px 32px;
                border-radius: 12px;
                margin-bottom: 24px;
                box-shadow: 0 10px 25px rgba(0, 33, 71, 0.12);
                border-left: 5px solid var(--mvc-gs-gold);
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 16px;
            }

            .mvc-gs-title-area h1 {
                color: #ffffff;
                font-size: 24px;
                font-weight: 800;
                margin: 0 0 6px;
                letter-spacing: -0.3px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .mvc-gs-title-area p {
                color: #cbd5e1;
                font-size: 13.5px;
                margin: 0;
                line-height: 1.5;
            }

            .mvc-gs-quick-links {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .mvc-gs-quick-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 14px;
                border-radius: 6px;
                font-size: 12.5px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.2s ease;
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: #ffffff;
                background: rgba(255, 255, 255, 0.08);
            }

            .mvc-gs-quick-btn:hover {
                background: var(--mvc-gs-gold);
                color: var(--mvc-gs-navy-dark);
                border-color: var(--mvc-gs-gold);
            }

            .mvc-gs-alert-success {
                background: #dcfce7;
                border: 1.5px solid #86efac;
                color: #14532d;
                padding: 14px 20px;
                border-radius: 8px;
                margin-bottom: 24px;
                font-size: 14px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 12px rgba(22, 101, 52, 0.08);
            }

            .mvc-gs-card {
                background: #ffffff;
                border: 1px solid var(--mvc-gs-slate-200);
                border-radius: 12px;
                box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
                margin-bottom: 24px;
                overflow: hidden;
            }

            .mvc-gs-card-header {
                padding: 16px 24px;
                background: #f8fafc;
                border-bottom: 1px solid var(--mvc-gs-slate-200);
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .mvc-gs-card-header h2 {
                margin: 0;
                font-size: 16px;
                font-weight: 800;
                color: var(--mvc-gs-navy);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .mvc-gs-card-body {
                padding: 24px;
            }

            .mvc-gs-grid-2 {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
                gap: 20px;
            }

            .mvc-gs-grid-3 {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
            }

            .mvc-gs-field-group {
                margin-bottom: 18px;
            }

            .mvc-gs-field-group:last-child {
                margin-bottom: 0;
            }

            .mvc-gs-label {
                display: block;
                font-size: 13px;
                font-weight: 700;
                color: var(--mvc-gs-slate-800);
                margin-bottom: 6px;
            }

            .mvc-gs-label .desc {
                font-weight: 400;
                color: var(--mvc-gs-slate-600);
                font-size: 11.5px;
                display: block;
                margin-top: 2px;
            }

            .mvc-gs-input, .mvc-gs-textarea {
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                padding: 10px 14px;
                font-size: 13.5px;
                border: 1.5px solid var(--mvc-gs-slate-200);
                border-radius: 6px;
                background: #ffffff;
                color: var(--mvc-gs-slate-800);
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .mvc-gs-input:focus, .mvc-gs-textarea:focus {
                border-color: var(--mvc-gs-navy);
                box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.12);
            }

            .mvc-gs-textarea {
                min-height: 80px;
                line-height: 1.5;
            }

            .mvc-gs-switch-box {
                display: flex;
                align-items: flex-start;
                gap: 14px;
                background: #fffbeb;
                border: 1.5px solid #fde68a;
                padding: 18px 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .mvc-gs-switch-box.is-active {
                background: #fef2f2;
                border-color: #fca5a5;
            }

            .mvc-gs-switch-box input[type="checkbox"] {
                width: 20px;
                height: 20px;
                margin-top: 2px;
                cursor: pointer;
            }

            .mvc-gs-switch-label {
                font-size: 14px;
                font-weight: 800;
                color: #92400e;
                cursor: pointer;
            }

            .mvc-gs-switch-box.is-active .mvc-gs-switch-label {
                color: #991b1b;
            }

            .mvc-gs-switch-desc {
                font-size: 12.5px;
                color: var(--mvc-gs-slate-600);
                margin-top: 4px;
                line-height: 1.5;
            }

            .mvc-gs-footer-bar {
                position: sticky;
                bottom: 20px;
                background: #ffffff;
                border: 1px solid var(--mvc-gs-slate-200);
                padding: 16px 24px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 10px 30px rgba(0, 33, 71, 0.1);
                z-index: 99;
                margin-top: 30px;
            }

            .mvc-gs-save-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: linear-gradient(135deg, var(--mvc-gs-navy) 0%, #0d3868 100%);
                color: #ffffff;
                font-size: 14px;
                font-weight: 800;
                padding: 12px 28px;
                border-radius: 6px;
                border: 1px solid var(--mvc-gs-gold);
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 4px 12px rgba(0, 33, 71, 0.2);
            }

            .mvc-gs-save-btn:hover {
                background: linear-gradient(135deg, var(--mvc-gs-maroon) 0%, #991b1b 100%);
                transform: translateY(-1px);
            }
        </style>

        <!-- Header -->
        <div class="mvc-gs-header">
            <div class="mvc-gs-title-area">
                <h1>
                    <span>🏛️</span>
                    <span>College Global Settings</span>
                </h1>
                <p>
                    Institutional master identity, communications directory, academic helplines, and maintenance controls.
                </p>
            </div>
            <div class="mvc-gs-quick-links">
                <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-command-center')); ?>" class="mvc-gs-quick-btn">
                    <span>⚡</span> Command Center
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-maintenance-mode')); ?>" class="mvc-gs-quick-btn">
                    <span>🛠️</span> Maintenance Mode Desk
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=zmail-integ-settings')); ?>" class="mvc-gs-quick-btn">
                    <span>📧</span> Zoho SMTP Gateway
                </a>
            </div>
        </div>

        <?php if ($is_saved): ?>
            <div class="mvc-gs-alert-success">
                <span>✅</span>
                <span>Institutional Global Settings saved successfully. Changes are synchronized across all public builders, headers, footers, and API endpoints.</span>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field('mvc_save_global_settings_action', 'mvc_global_settings_nonce'); ?>
            <input type="hidden" name="mvc_save_global_settings" value="1">

            <!-- 1. Institutional Identity -->
            <div class="mvc-gs-card">
                <div class="mvc-gs-card-header">
                    <h2><span>🏛️</span> 1. Institutional Identity &amp; Campus Accreditation</h2>
                </div>
                <div class="mvc-gs-card-body">
                    <div class="mvc-gs-grid-2">
                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="college_name">
                                Official College Name (English)
                                <span class="desc">Displayed across header mastheads, official letters, and SEO metatags.</span>
                            </label>
                            <input type="text" id="college_name" name="college_name" class="mvc-gs-input" value="<?php echo esc_attr($val('college_name', 'Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)')); ?>" required>
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="college_name_hi">
                                Official College Name (Hindi / Devnagari)
                                <span class="desc">Displayed in bilingual banners and state university documentation.</span>
                            </label>
                            <input type="text" id="college_name_hi" name="college_name_hi" class="mvc-gs-input" value="<?php echo esc_attr($val('college_name_hi', 'महर्षि विश्वामित्र महाविद्यालय, बक्सर')); ?>">
                        </div>
                    </div>

                    <div class="mvc-gs-field-group" style="margin-top: 16px;">
                        <label class="mvc-gs-label" for="college_tagline">
                            Statutory Affiliation &amp; Establishment Subtitle
                            <span class="desc">Constituent status, university affiliation, and founding year.</span>
                        </label>
                        <input type="text" id="college_tagline" name="college_tagline" class="mvc-gs-input" value="<?php echo esc_attr($val('college_tagline', 'A Premier Constituent Unit of Veer Kunwar Singh University, Ara • Estd. 1958')); ?>">
                    </div>

                    <div class="mvc-gs-grid-2" style="margin-top: 16px;">
                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="campus_address">
                                Official Campus Address
                                <span class="desc">Statutory postal address including district and PIN code.</span>
                            </label>
                            <textarea id="campus_address" name="campus_address" class="mvc-gs-textarea"><?php echo esc_textarea($val('campus_address', 'Charitravan, Buxar – 802101 (Bihar)')); ?></textarea>
                        </div>

                        <div>
                            <div class="mvc-gs-field-group">
                                <label class="mvc-gs-label" for="office_hours">
                                    Administrative Working Hours
                                    <span class="desc">Standard college secretariat operational timings.</span>
                                </label>
                                <input type="text" id="office_hours" name="office_hours" class="mvc-gs-input" value="<?php echo esc_attr($val('office_hours', 'Mon – Sat: 10:00 AM – 05:30 PM')); ?>">
                            </div>

                            <div class="mvc-gs-field-group" style="margin-top: 14px;">
                                <label class="mvc-gs-label" for="counter_hours">
                                    Student Fee &amp; Document Counter Timings
                                    <span class="desc">Operational window for student fees, marksheets, and certifications.</span>
                                </label>
                                <input type="text" id="counter_hours" name="counter_hours" class="mvc-gs-input" value="<?php echo esc_attr($val('counter_hours', '10:30 AM – 03:30 PM')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Telecommunications & Phone Helplines -->
            <div class="mvc-gs-card">
                <div class="mvc-gs-card-header">
                    <h2><span>📞</span> 2. Telecommunications &amp; Campus Helplines</h2>
                </div>
                <div class="mvc-gs-card-body">
                    <div class="mvc-gs-grid-3">
                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="contact_phone">
                                Primary Campus Contact Number
                                <span class="desc">Published on homepage header &amp; Contact Us suite.</span>
                            </label>
                            <input type="text" id="contact_phone" name="contact_phone" class="mvc-gs-input" value="<?php echo esc_attr($val('contact_phone', '06183-796317')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="college_phone">
                                Administrative Office Landline
                                <span class="desc">Official landline telephone for general inquiries.</span>
                            </label>
                            <input type="text" id="college_phone" name="college_phone" class="mvc-gs-input" value="<?php echo esc_attr($val('college_phone', '06183-222035')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="alt_phone">
                                Alternative / Urgent Helpline
                                <span class="desc">Secondary line for emergency or student assistance.</span>
                            </label>
                            <input type="text" id="alt_phone" name="alt_phone" class="mvc-gs-input" value="<?php echo esc_attr($val('alt_phone', '+91 6183 796317')); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Official Email Directory -->
            <div class="mvc-gs-card">
                <div class="mvc-gs-card-header">
                    <h2><span>📧</span> 3. Official Institutional Email Directory</h2>
                </div>
                <div class="mvc-gs-card-body">
                    <p style="font-size: 13px; color: var(--mvc-gs-slate-600); margin: 0 0 20px;">
                        These emails are dynamically referenced across the Contact Us page, Helpdesk Directory, Department details, and notification dispatchers.
                    </p>

                    <div class="mvc-gs-grid-2">
                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="college_email">General Campus Inquiries Email</label>
                            <input type="email" id="college_email" name="college_email" class="mvc-gs-input" value="<?php echo esc_attr($val('college_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="principal_email">Office of the Principal (Secretariat)</label>
                            <input type="email" id="principal_email" name="principal_email" class="mvc-gs-input" value="<?php echo esc_attr($val('principal_email', 'principalmvcbuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="admission_email">Admission Cell (UG/PG Counseling)</label>
                            <input type="email" id="admission_email" name="admission_email" class="mvc-gs-input" value="<?php echo esc_attr($val('admission_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="examination_email">Examination &amp; Marksheet Tabulation</label>
                            <input type="email" id="examination_email" name="examination_email" class="mvc-gs-input" value="<?php echo esc_attr($val('examination_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="library_email">Central Library &amp; ONOS Desk</label>
                            <input type="email" id="library_email" name="library_email" class="mvc-gs-input" value="<?php echo esc_attr($val('library_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="accounts_email">Accounts, Bursar &amp; Finance Wing</label>
                            <input type="email" id="accounts_email" name="accounts_email" class="mvc-gs-input" value="<?php echo esc_attr($val('accounts_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="grievance_email">Student Grievance Redressal Cell</label>
                            <input type="email" id="grievance_email" name="grievance_email" class="mvc-gs-input" value="<?php echo esc_attr($val('grievance_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="antiragging_email">Anti-Ragging Committee &amp; Squad</label>
                            <input type="email" id="antiragging_email" name="antiragging_email" class="mvc-gs-input" value="<?php echo esc_attr($val('antiragging_email', 'antiraggingmvcbuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="icc_email">Internal Complaint Committee (ICC Women Cell)</label>
                            <input type="email" id="icc_email" name="icc_email" class="mvc-gs-input" value="<?php echo esc_attr($val('icc_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="pio_email">Right to Information (RTI / PIO Desk)</label>
                            <input type="email" id="pio_email" name="pio_email" class="mvc-gs-input" value="<?php echo esc_attr($val('pio_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="proctor_email">Proctorial Board (Campus Vigilance)</label>
                            <input type="email" id="proctor_email" name="proctor_email" class="mvc-gs-input" value="<?php echo esc_attr($val('proctor_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="alumni_email">Alumni Association &amp; Relations</label>
                            <input type="email" id="alumni_email" name="alumni_email" class="mvc-gs-input" value="<?php echo esc_attr($val('alumni_email', 'mvcollegebuxar@gmail.com')); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. System Maintenance & Online Status -->
            <div class="mvc-gs-card">
                <div class="mvc-gs-card-header">
                    <h2><span>🛠️</span> 4. System Maintenance Mode &amp; Safety Guard</h2>
                </div>
                <div class="mvc-gs-card-body">
                    <div class="mvc-gs-switch-box <?php echo $maint_enabled ? 'is-active' : ''; ?>">
                        <input type="checkbox" id="maintenance_mode_enabled" name="maintenance_mode_enabled" value="1" <?php checked($maint_enabled); ?>>
                        <div>
                            <label for="maintenance_mode_enabled" class="mvc-gs-switch-label">
                                <?php echo $maint_enabled ? '⚠️ Maintenance Mode is Currently ACTIVE' : 'Enable Scheduled Institutional Maintenance Mode'; ?>
                            </label>
                            <div class="mvc-gs-switch-desc">
                                When active, all non-administrative visitors will see an official maintenance notice across all secondary pages, preserving the homepage. Administrators, Content Managers, and HODs maintain full authenticated access.
                            </div>
                        </div>
                    </div>

                    <div class="mvc-gs-field-group">
                        <label class="mvc-gs-label" for="maintenance_notice_title">Maintenance Notice Heading</label>
                        <input type="text" id="maintenance_notice_title" name="maintenance_notice_title" class="mvc-gs-input" value="<?php echo esc_attr($val('maintenance_notice_title', 'Scheduled Website Maintenance in Progress')); ?>">
                    </div>

                    <div class="mvc-gs-field-group" style="margin-top: 14px;">
                        <label class="mvc-gs-label" for="maintenance_notice_message">Maintenance Public Message</label>
                        <textarea id="maintenance_notice_message" name="maintenance_notice_message" class="mvc-gs-textarea"><?php echo esc_textarea($val('maintenance_notice_message', 'Maharshi Vishwamitra Mahavidyalaya portal is currently undergoing scheduled system updates and maintenance. During this window, only the home page is available for public viewing. All academic departments, notices, student portal, and online services will resume shortly. We apologize for any inconvenience caused.')); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 5. SEO & Canonical Routing -->
            <div class="mvc-gs-card">
                <div class="mvc-gs-card-header">
                    <h2><span>🌐</span> 5. Production URL &amp; Canonical SEO Routing</h2>
                </div>
                <div class="mvc-gs-card-body">
                    <div class="mvc-gs-grid-2">
                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="production_site_url">
                                Production Domain URL
                                <span class="desc">The authoritative live production URL (e.g. https://mvcollegebuxar.ac.in).</span>
                            </label>
                            <input type="url" id="production_site_url" name="production_site_url" class="mvc-gs-input" value="<?php echo esc_attr($val('production_site_url', home_url())); ?>">
                        </div>

                        <div class="mvc-gs-field-group">
                            <label class="mvc-gs-label" for="canonical_site_url">
                                Canonical Base URL
                                <span class="desc">Base URL output in SEO canonical headers and Schema.org graphs.</span>
                            </label>
                            <input type="url" id="canonical_site_url" name="canonical_site_url" class="mvc-gs-input" value="<?php echo esc_attr($val('canonical_site_url', home_url())); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Save Bar -->
            <div class="mvc-gs-footer-bar">
                <div style="font-size: 13px; color: var(--mvc-gs-slate-600); font-weight: 600;">
                    🏛️ Maharshi Vishwamitra Mahavidyalaya &bull; Centralized Institutional Configuration
                </div>
                <button type="submit" class="mvc-gs-save-btn">
                    <span>💾</span>
                    <span>Save Institutional Settings</span>
                </button>
            </div>
        </form>
    </div>
    <?php
}
