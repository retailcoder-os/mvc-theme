<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * NAAC Accreditation & Quality Assurance Suite - Dedicated Admin Dashboard
 * 
 * Provides centralized configuration and management for:
 * 1. 10-Chapter NAAC Suite Matrix & Metadata
 * 2. Primary Certified PDF Documents & Physical File Health
 * 3. IQAC Executive Council & Advisory Leadership Directory
 * 4. Accreditation Document Vault & Media Library Integration
 * 
 * Standard: 10/10 Institutional Aesthetic, ACF Free Compatible, Zero Live-Site Hotlinking.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Top-Level Admin Menu & Submenus for NAAC Suite
 */
add_action('admin_menu', 'mvc_naac_register_admin_menu');
function mvc_naac_register_admin_menu() {
    $parent_slug = 'mvc-naac-suite';

    add_menu_page(
        'NAAC & IQAC Quality Suite',
        'NAAC & IQAC Suite',
        'edit_pages',
        $parent_slug,
        'mvc_naac_render_admin_dashboard',
        'dashicons-awards',
        29
    );

    add_submenu_page(
        $parent_slug,
        'NAAC Suite Dashboard',
        'Dashboard Overview',
        'edit_pages',
        $parent_slug,
        'mvc_naac_render_admin_dashboard'
    );

    add_submenu_page(
        $parent_slug,
        '10-Chapter Configuration Matrix',
        '10 Chapters Matrix',
        'edit_pages',
        'mvc-naac-chapters',
        'mvc_naac_render_admin_chapters'
    );

    add_submenu_page(
        $parent_slug,
        'IQAC Executive Council Directory',
        'IQAC Council Directory',
        'edit_pages',
        'mvc-naac-council',
        'mvc_naac_render_admin_council'
    );

    add_submenu_page(
        $parent_slug,
        'Certified Document Vault',
        'Document Vault',
        'edit_pages',
        'mvc-naac-vault',
        'mvc_naac_render_admin_vault'
    );
}

/**
 * Enqueue Media Uploader & Admin Assets for NAAC Dashboard
 */
add_action('admin_enqueue_scripts', 'mvc_naac_admin_assets');
function mvc_naac_admin_assets($hook) {
    if (strpos($hook, 'mvc-naac') === false) {
        return;
    }

    wp_enqueue_media();
}

/**
 * Handle POST Submissions (Save Chapters, Save Council, Reset to Benchmark)
 */
add_action('admin_init', 'mvc_naac_handle_admin_submissions');
function mvc_naac_handle_admin_submissions() {
    if (!current_user_can('edit_pages')) {
        return;
    }

    // 1. Save 10 Chapters Matrix
    if (isset($_POST['mvc_naac_action']) && $_POST['mvc_naac_action'] === 'save_chapters') {
        check_admin_referer('mvc_naac_save_chapters_nonce', 'mvc_naac_nonce');
        
        $submitted_chapters = $_POST['chapters'] ?? [];
        $cleaned_chapters   = [];

        if (is_array($submitted_chapters)) {
            foreach ($submitted_chapters as $slug => $data) {
                $clean_slug = sanitize_title($slug);
                $cleaned_chapters[$clean_slug] = [
                    'title'     => sanitize_text_field($data['title'] ?? ''),
                    'title_hi'  => sanitize_text_field($data['title_hi'] ?? ''),
                    'badge'     => sanitize_text_field($data['badge'] ?? ''),
                    'code'      => sanitize_text_field($data['code'] ?? ''),
                    'pdf_file'  => sanitize_text_field($data['pdf_file'] ?? ''),
                    'pdf_title' => sanitize_text_field($data['pdf_title'] ?? ''),
                    'filesize'  => sanitize_text_field($data['filesize'] ?? ''),
                    'authority' => sanitize_text_field($data['authority'] ?? '')
                ];
            }
        }

        update_option('mvc_naac_chapters_settings', $cleaned_chapters);
        wp_safe_redirect(add_query_arg(['page' => 'mvc-naac-chapters', 'saved' => 'chapters'], admin_url('admin.php')));
        exit;
    }

    // 2. Save IQAC Executive Council
    if (isset($_POST['mvc_naac_action']) && $_POST['mvc_naac_action'] === 'save_council') {
        check_admin_referer('mvc_naac_save_council_nonce', 'mvc_naac_nonce');

        $submitted_council = $_POST['council'] ?? [];
        $cleaned_council   = [];

        if (is_array($submitted_council)) {
            foreach ($submitted_council as $role_key => $data) {
                $clean_key = sanitize_key($role_key);
                $cleaned_council[$clean_key] = [
                    'role'        => sanitize_text_field($data['role'] ?? ''),
                    'name'        => sanitize_text_field($data['name'] ?? ''),
                    'post'        => sanitize_text_field($data['post'] ?? ''),
                    'office'      => sanitize_text_field($data['office'] ?? ''),
                    'phone'       => sanitize_text_field($data['phone'] ?? ''),
                    'email'       => sanitize_email($data['email'] ?? ''),
                    'photo'       => esc_url_raw($data['photo'] ?? ''),
                    'badge_class' => sanitize_html_class($data['badge_class'] ?? 'mvc-iqac-role-coordinator'),
                    'faculty_id'  => intval($data['faculty_id'] ?? 0)
                ];
            }
        }

        // Also handle Senior Faculty Advisory nominations
        $submitted_advisors = $_POST['advisors'] ?? [];
        if (is_array($submitted_advisors)) {
            $cleaned_council['advisors'] = [];
            foreach ($submitted_advisors as $idx => $adv) {
                $cleaned_council['advisors'][] = [
                    'name'   => sanitize_text_field($adv['name'] ?? ''),
                    'dept'   => sanitize_text_field($adv['dept'] ?? ''),
                    'focus'  => sanitize_text_field($adv['focus'] ?? ''),
                    'photo'  => esc_url_raw($adv['photo'] ?? '')
                ];
            }
        }

        update_option('mvc_naac_council_settings', $cleaned_council);
        wp_safe_redirect(add_query_arg(['page' => 'mvc-naac-council', 'saved' => 'council'], admin_url('admin.php')));
        exit;
    }

    // 3. Reset to Authentic Benchmark
    if (isset($_POST['mvc_naac_action']) && $_POST['mvc_naac_action'] === 'reset_benchmark') {
        check_admin_referer('mvc_naac_reset_benchmark_nonce', 'mvc_naac_nonce');

        delete_option('mvc_naac_chapters_settings');
        delete_option('mvc_naac_council_settings');

        $redirect_page = sanitize_key($_POST['current_page'] ?? 'mvc-naac-suite');
        wp_safe_redirect(add_query_arg(['page' => $redirect_page, 'reset' => '1'], admin_url('admin.php')));
        exit;
    }
}

/**
 * Shared Admin Header Component
 */
function mvc_naac_render_admin_header($active_tab = 'overview') {
    ?>
    <div class="mvc-admin-wrap">
        <style>
            :root {
                --mvc-adm-navy: #002147;
                --mvc-adm-navy-dark: #00152e;
                --mvc-adm-maroon: #800000;
                --mvc-adm-gold: #d4af37;
                --mvc-adm-gold-light: #fef9e7;
                --mvc-adm-bg: #f8fafc;
                --mvc-adm-card: #ffffff;
                --mvc-adm-border: #e2e8f0;
                --mvc-adm-text: #1e293b;
                --mvc-adm-muted: #64748b;
                --mvc-adm-success: #10b981;
            }

            .mvc-admin-wrap {
                margin: 20px 20px 40px 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                color: var(--mvc-adm-text);
            }

            /* Header Hero */
            .mvc-adm-hero {
                background: linear-gradient(135deg, var(--mvc-adm-navy) 0%, var(--mvc-adm-navy-dark) 70%, var(--mvc-adm-maroon) 100%);
                color: #ffffff;
                border-radius: 12px;
                padding: 28px 32px;
                box-shadow: 0 10px 25px -5px rgba(0, 33, 71, 0.2);
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                margin-bottom: 24px;
                border-bottom: 3.5px solid var(--mvc-adm-gold);
            }

            .mvc-adm-hero-info {
                display: flex;
                align-items: center;
                gap: 18px;
            }

            .mvc-adm-crest-badge {
                width: 64px;
                height: 64px;
                background: rgba(255, 255, 255, 0.1);
                border: 2px solid var(--mvc-adm-gold);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }

            .mvc-adm-title-box h1 {
                margin: 0 0 6px 0;
                font-size: 24px;
                font-weight: 800;
                color: #ffffff;
                letter-spacing: -0.02em;
            }

            .mvc-adm-subtitle {
                margin: 0;
                font-size: 13.5px;
                color: #cbd5e1;
                font-weight: 500;
            }

            .mvc-adm-hero-actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .mvc-adm-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 18px;
                font-size: 13px;
                font-weight: 700;
                border-radius: 8px;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                border: none;
            }

            .mvc-adm-btn-gold {
                background: linear-gradient(135deg, #d4af37 0%, #b48200 100%);
                color: #002147;
                box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
            }
            .mvc-adm-btn-gold:hover {
                background: linear-gradient(135deg, #e5c04b 0%, #c89300 100%);
                color: #00152e;
                transform: translateY(-1px);
            }

            .mvc-adm-btn-outline {
                background: rgba(255, 255, 255, 0.12);
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.25);
            }
            .mvc-adm-btn-outline:hover {
                background: rgba(255, 255, 255, 0.22);
                color: #ffffff;
            }

            .mvc-adm-btn-danger {
                background: #ef4444;
                color: #ffffff;
            }
            .mvc-adm-btn-danger:hover {
                background: #dc2626;
            }

            /* Admin Navigation Tabs */
            .mvc-adm-tabs {
                display: flex;
                gap: 6px;
                margin-bottom: 24px;
                border-bottom: 2px solid var(--mvc-adm-border);
                padding-bottom: 0;
            }

            .mvc-adm-tab-item {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 20px;
                font-size: 14px;
                font-weight: 700;
                color: var(--mvc-adm-muted);
                text-decoration: none;
                border-bottom: 3px solid transparent;
                margin-bottom: -2px;
                transition: all 0.2s ease;
                border-radius: 6px 6px 0 0;
            }

            .mvc-adm-tab-item:hover {
                color: var(--mvc-adm-navy);
                background: #f1f5f9;
            }

            .mvc-adm-tab-item.active {
                color: var(--mvc-adm-navy);
                border-bottom-color: var(--mvc-adm-gold);
                background: #ffffff;
            }

            /* Stat Cards Grid */
            .mvc-adm-stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }

            .mvc-adm-stat-card {
                background: #ffffff;
                border: 1px solid var(--mvc-adm-border);
                border-radius: 10px;
                padding: 18px 20px;
                display: flex;
                align-items: center;
                gap: 16px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            }

            .mvc-adm-stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 10px;
                background: #eff6ff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                color: var(--mvc-adm-navy);
            }

            .mvc-adm-stat-val {
                font-size: 22px;
                font-weight: 800;
                color: var(--mvc-adm-navy);
                line-height: 1.2;
            }

            .mvc-adm-stat-label {
                font-size: 12px;
                font-weight: 600;
                color: var(--mvc-adm-muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            /* Content Cards */
            .mvc-adm-card {
                background: #ffffff;
                border: 1px solid var(--mvc-adm-border);
                border-radius: 12px;
                padding: 24px 28px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.03);
                margin-bottom: 24px;
            }

            .mvc-adm-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
                padding-bottom: 14px;
                border-bottom: 1px solid var(--mvc-adm-border);
            }

            .mvc-adm-card-title {
                font-size: 17px;
                font-weight: 800;
                color: var(--mvc-adm-navy);
                margin: 0;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .mvc-adm-badge-active {
                background: #ecfdf5;
                color: #065f46;
                font-size: 11.5px;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 20px;
                border: 1px solid #a7f3d0;
            }

            /* Tables */
            .mvc-adm-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            .mvc-adm-table th {
                background: #f8fafc;
                color: var(--mvc-adm-navy);
                font-size: 12.5px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                padding: 12px 14px;
                text-align: left;
                border-bottom: 2px solid var(--mvc-adm-border);
            }

            .mvc-adm-table td {
                padding: 13px 14px;
                font-size: 13.5px;
                border-bottom: 1px solid var(--mvc-adm-border);
                vertical-align: middle;
            }

            .mvc-adm-table tr:hover td {
                background: #fafaf9;
            }

            /* Forms & Inputs */
            .mvc-adm-form-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 18px;
                margin-bottom: 20px;
            }

            .mvc-adm-form-group {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .mvc-adm-form-group label {
                font-size: 12.5px;
                font-weight: 700;
                color: var(--mvc-adm-navy);
            }

            .mvc-adm-form-group input,
            .mvc-adm-form-group select,
            .mvc-adm-form-group textarea {
                padding: 9px 12px;
                font-size: 13.5px;
                border: 1px solid #cbd5e1;
                border-radius: 6px;
                background: #ffffff;
                color: #1e293b;
                transition: border-color 0.15s ease;
            }

            .mvc-adm-form-group input:focus,
            .mvc-adm-form-group select:focus,
            .mvc-adm-form-group textarea:focus {
                border-color: var(--mvc-adm-navy);
                outline: none;
                box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
            }

            .mvc-adm-chapter-accordion {
                border: 1px solid var(--mvc-adm-border);
                border-radius: 10px;
                margin-bottom: 16px;
                overflow: hidden;
                background: #ffffff;
            }

            .mvc-adm-chapter-head {
                background: #f8fafc;
                padding: 14px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
                font-weight: 700;
                font-size: 14.5px;
                color: var(--mvc-adm-navy);
                border-bottom: 1px solid var(--mvc-adm-border);
            }

            .mvc-adm-chapter-body {
                padding: 20px;
                background: #ffffff;
            }

            .mvc-adm-photo-preview {
                width: 60px;
                height: 60px;
                border-radius: 8px;
                object-fit: cover;
                border: 1px solid var(--mvc-adm-border);
                background: #f1f5f9;
            }

            .mvc-adm-uploader-row {
                display: flex;
                align-items: center;
                gap: 12px;
            }
        </style>

        <!-- TOP HERO -->
        <header class="mvc-adm-hero">
            <div class="mvc-adm-info-wrap">
                <div class="mvc-adm-hero-info">
                    <div class="mvc-adm-crest-badge">🏛️</div>
                    <div class="mvc-adm-title-box">
                        <h1>NAAC &amp; IQAC Institutional Quality Command Center</h1>
                        <p class="mvc-adm-subtitle">
                            Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar) • Accredited Statutory Quality Management Suite
                        </p>
                    </div>
                </div>
            </div>
            <div class="mvc-adm-hero-actions">
                <a href="<?php echo esc_url(home_url('/iqac/')); ?>" target="_blank" rel="noopener" class="mvc-adm-btn mvc-adm-btn-gold">
                    🌐 Open IQAC Portal ↗
                </a>
                <a href="<?php echo esc_url(home_url('/aishe/')); ?>" target="_blank" rel="noopener" class="mvc-adm-btn mvc-adm-btn-outline">
                    📜 View AISHE Chapter ↗
                </a>
            </div>
        </header>

        <!-- NOTICES -->
        <?php if (isset($_GET['saved'])): ?>
            <div class="notice notice-success is-dismissible" style="border-left-color: #10b981; padding: 12px 18px; margin-bottom: 20px;">
                <p><strong>Success!</strong> NAAC <?php echo esc_html(ucfirst($_GET['saved'])); ?> configuration successfully updated and live on front-end.</p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['reset'])): ?>
            <div class="notice notice-info is-dismissible" style="border-left-color: var(--mvc-adm-gold); padding: 12px 18px; margin-bottom: 20px;">
                <p><strong>Benchmark Restored:</strong> NAAC Suite restored to 100% authentic benchmark institutional defaults.</p>
            </div>
        <?php endif; ?>

        <!-- NAVIGATION TABS -->
        <nav class="mvc-adm-tabs">
            <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-suite')); ?>" class="mvc-adm-tab-item <?php echo ($active_tab === 'overview') ? 'active' : ''; ?>">
                📊 Dashboard Overview
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-chapters')); ?>" class="mvc-adm-tab-item <?php echo ($active_tab === 'chapters') ? 'active' : ''; ?>">
                🗂️ 10 Chapters Matrix
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-council')); ?>" class="mvc-adm-tab-item <?php echo ($active_tab === 'council') ? 'active' : ''; ?>">
                👥 IQAC Council Directory
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-vault')); ?>" class="mvc-adm-tab-item <?php echo ($active_tab === 'vault') ? 'active' : ''; ?>">
                🔒 Certified Document Vault
            </a>
        </nav>
    <?php
}

/**
 * Shared Admin Footer Component
 */
function mvc_naac_render_admin_footer() {
    ?>
    </div><!-- /.mvc-admin-wrap -->
    <script>
    jQuery(document).ready(function($) {
        // Universal WordPress Media Library Handler for Images & PDFs
        $('.mvc-media-upload-btn').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetInput = $('#' + button.data('target'));
            var targetPreview = $('#' + button.data('preview'));
            var mediaType = button.data('type') || 'image';

            var frame = wp.media({
                title: button.data('title') || 'Select or Upload Document/Image',
                button: { text: 'Use Selected Media' },
                multiple: false,
                library: { type: mediaType }
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                targetInput.val(attachment.url);
                if (targetPreview.length && attachment.url) {
                    targetPreview.attr('src', attachment.url).show();
                }
            });

            frame.open();
        });

        // 1-Click Benchmark Reset confirmation
        $('.mvc-reset-benchmark-form').on('submit', function(e) {
            if (!confirm('Are you sure you want to reset NAAC configurations to official authentic benchmark defaults? Custom titles and paths will revert.')) {
                e.preventDefault();
            }
        });
    });
    </script>
    <?php
}

/**
 * 1. TAB: Dashboard Overview Page (mvc-naac-suite)
 */
function mvc_naac_render_admin_dashboard() {
    mvc_naac_render_admin_header('overview');
    $chapters = mvc_get_naac_chapters();
    $doc_dir = get_stylesheet_directory() . '/../../uploads/live_imported/docs/';
    $total_ch = count($chapters);
    $primary_pdf_count = 0;
    foreach ($chapters as $ch) {
        if (!empty($ch['pdf'])) $primary_pdf_count++;
    }
    ?>
    <!-- STAT TILES -->
    <div class="mvc-adm-stats-grid">
        <div class="mvc-adm-stat-card">
            <div class="mvc-adm-stat-icon">🎓</div>
            <div>
                <div class="mvc-adm-stat-val"><?php echo esc_html($total_ch . ' / ' . $total_ch); ?></div>
                <div class="mvc-adm-stat-label">Chapters Online</div>
            </div>
        </div>
        <div class="mvc-adm-stat-card">
            <div class="mvc-adm-stat-icon">📜</div>
            <div>
                <div class="mvc-adm-stat-val"><?php echo esc_html($primary_pdf_count); ?></div>
                <div class="mvc-adm-stat-label">Master Primary PDFs</div>
            </div>
        </div>
        <div class="mvc-adm-stat-card">
            <div class="mvc-adm-stat-icon">📊</div>
            <div>
                <div class="mvc-adm-stat-val"><?php echo esc_html($total_ch); ?></div>
                <div class="mvc-adm-stat-label">Full-Width Mobile Modules</div>
            </div>
        </div>
        <div class="mvc-adm-stat-card">
            <div class="mvc-adm-stat-icon">⚖️</div>
            <div>
                <div class="mvc-adm-stat-val">100%</div>
                <div class="mvc-adm-stat-label">Repository Self-Contained</div>
            </div>
        </div>
    </div>

    <!-- CHAPTER STATUS MATRIX TABLE -->
    <div class="mvc-adm-card">
        <div class="mvc-adm-card-header">
            <h2 class="mvc-adm-card-title">
                <span>📑</span> NAAC 10-Chapter Quality Suite Status &amp; Document Health
            </h2>
            <span class="mvc-adm-badge-active">● 100% Fully Self-Contained</span>
        </div>

        <table class="mvc-adm-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Ch.</th>
                    <th>Chapter &amp; Institutional Scope</th>
                    <th>Accreditation Track / Code</th>
                    <th>Master Primary Document</th>
                    <th>File Health</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $ch_num = 1;
                foreach ($chapters as $slug => $c): 
                    $pdf_path = $doc_dir . $c['pdf_file'];
                    $file_exists = file_exists($pdf_path);
                    $file_size = $file_exists ? round(filesize($pdf_path) / 1024, 1) . ' KB' : $c['filesize'];
                ?>
                <tr>
                    <td><strong><?php echo sprintf('%02d', $ch_num++); ?></strong></td>
                    <td>
                        <span style="font-size: 16px; margin-right: 6px;"><?php echo esc_html($c['icon']); ?></span>
                        <strong><?php echo esc_html($c['title']); ?></strong>
                        <div style="font-size: 11.5px; color: var(--mvc-adm-muted); margin-top: 2px;">
                            <?php echo esc_html($c['title_hi']); ?>
                        </div>
                    </td>
                    <td>
                        <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 12px; color: var(--mvc-adm-navy); font-weight: 700;">
                            <?php echo esc_html($c['code']); ?>
                        </code>
                        <div style="font-size: 11px; color: var(--mvc-adm-muted); margin-top: 2px;">
                            <?php echo esc_html($c['badge']); ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #1e293b;"><?php echo esc_html($c['pdf_title']); ?></div>
                        <div style="font-size: 11.5px; color: var(--mvc-adm-muted); font-family: monospace;">
                            <?php echo esc_html($c['pdf_file']); ?> (<?php echo esc_html($file_size); ?>)
                        </div>
                    </td>
                    <td>
                        <?php if ($file_exists): ?>
                            <span style="color: #059669; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <span style="display:inline-block; width:8px; height:8px; background:#10b981; border-radius:50%;"></span>
                                Local Verified
                            </span>
                        <?php else: ?>
                            <span style="color: #d97706; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <span style="display:inline-block; width:8px; height:8px; background:#f59e0b; border-radius:50%;"></span>
                                Custom / External
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;">
                        <a href="<?php echo esc_url($c['url']); ?>" target="_blank" rel="noopener" class="mvc-adm-btn mvc-adm-btn-outline" style="padding: 5px 10px; font-size: 12px; color: var(--mvc-adm-navy); border-color: #cbd5e1; background: #f8fafc;">
                            Visit ↗
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-chapters#' . $slug)); ?>" class="mvc-adm-btn mvc-adm-btn-outline" style="padding: 5px 10px; font-size: 12px; color: var(--mvc-adm-navy); border-color: #cbd5e1; background: #f8fafc;">
                            Configure ⚙️
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- BENCHMARK CONTROL BAR -->
    <div class="mvc-adm-card" style="background: #fafaf9; border-left: 4px solid var(--mvc-adm-gold); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 style="margin: 0 0 4px; font-size: 15px; font-weight: 800; color: var(--mvc-adm-navy);">
                Authentic Benchmark Governance &amp; Recovery
            </h3>
            <p style="margin: 0; font-size: 12.5px; color: var(--mvc-adm-muted);">
                Restore all 10 chapters, documents, and council member records to verified live collegiate records.
            </p>
        </div>
        <form method="post" class="mvc-reset-benchmark-form" style="margin: 0;">
            <?php wp_nonce_field('mvc_naac_reset_benchmark_nonce', 'mvc_naac_nonce'); ?>
            <input type="hidden" name="mvc_naac_action" value="reset_benchmark">
            <input type="hidden" name="current_page" value="mvc-naac-suite">
            <button type="submit" class="mvc-adm-btn mvc-adm-btn-danger" style="padding: 8px 16px;">
                🔄 Reset to Official Benchmark
            </button>
        </form>
    </div>

    <?php
    mvc_naac_render_admin_footer();
}

/**
 * 2. TAB: 10 Chapters Matrix Configuration (mvc-naac-chapters)
 */
function mvc_naac_render_admin_chapters() {
    mvc_naac_render_admin_header('chapters');
    $chapters = mvc_get_naac_chapters();
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-chapters')); ?>">
        <?php wp_nonce_field('mvc_naac_save_chapters_nonce', 'mvc_naac_nonce'); ?>
        <input type="hidden" name="mvc_naac_action" value="save_chapters">

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--mvc-adm-navy);">
                    10-Chapter Accreditation Matrix &amp; Document Assignment
                </h2>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--mvc-adm-muted);">
                    Customize titles, official Hindi designations, accreditation badge subtitles, and master PDF documents for each chapter.
                </p>
            </div>
            <button type="submit" class="mvc-adm-btn mvc-adm-btn-gold" style="font-size: 14px; padding: 10px 22px;">
                💾 Save All Chapter Settings
            </button>
        </div>

        <?php 
        $i = 1;
        foreach ($chapters as $slug => $c): 
        ?>
        <div class="mvc-adm-chapter-accordion" id="<?php echo esc_attr($slug); ?>">
            <div class="mvc-adm-chapter-head">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 18px;"><?php echo esc_html($c['icon']); ?></span>
                    <span style="font-weight: 800;"><?php echo sprintf('Chapter %02d: %s', $i++, esc_html($c['title'])); ?></span>
                    <span style="font-size: 12px; color: var(--mvc-adm-muted);">(<code>/<?php echo esc_html($slug); ?>/</code>)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="mvc-adm-badge-active"><?php echo esc_html($c['code']); ?></span>
                    <a href="<?php echo esc_url($c['url']); ?>" target="_blank" rel="noopener" class="mvc-adm-btn mvc-adm-btn-outline" style="padding: 4px 8px; font-size: 11px; color: var(--mvc-adm-navy); background: #ffffff; border-color: #cbd5e1;">
                        View Page ↗
                    </a>
                </div>
            </div>
            <div class="mvc-adm-chapter-body">
                <div class="mvc-adm-form-grid">
                    <div class="mvc-adm-form-group">
                        <label>English Chapter Title</label>
                        <input type="text" name="chapters[<?php echo esc_attr($slug); ?>][title]" value="<?php echo esc_attr($c['title']); ?>" required>
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Hindi Institutional Title</label>
                        <input type="text" name="chapters[<?php echo esc_attr($slug); ?>][title_hi]" value="<?php echo esc_attr($c['title_hi']); ?>">
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Accreditation Badge / Subtitle</label>
                        <input type="text" name="chapters[<?php echo esc_attr($slug); ?>][badge]" value="<?php echo esc_attr($c['badge']); ?>">
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Track ID / Institutional Code</label>
                        <input type="text" name="chapters[<?php echo esc_attr($slug); ?>][code]" value="<?php echo esc_attr($c['code']); ?>">
                    </div>
                </div>

                <div class="mvc-adm-form-grid">
                    <div class="mvc-adm-form-group" style="grid-column: span 2;">
                        <label>Primary Document Title (Front-End Presentation)</label>
                        <input type="text" name="chapters[<?php echo esc_attr($slug); ?>][pdf_title]" value="<?php echo esc_attr($c['pdf_title']); ?>" required>
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Issuing Authority / Statutory Body</label>
                        <input type="text" name="chapters[<?php echo esc_attr($slug); ?>][authority]" value="<?php echo esc_attr($c['authority']); ?>">
                    </div>
                </div>

                <div class="mvc-adm-form-grid">
                    <div class="mvc-adm-form-group" style="grid-column: span 2;">
                        <label>Primary PDF File Name / URL</label>
                        <div class="mvc-adm-uploader-row">
                            <input type="text" id="pdf_<?php echo esc_attr($slug); ?>" name="chapters[<?php echo esc_attr($slug); ?>][pdf_file]" value="<?php echo esc_attr($c['pdf_file']); ?>" style="flex: 1;" required>
                            <button type="button" class="mvc-adm-btn mvc-adm-btn-outline mvc-media-upload-btn" data-target="pdf_<?php echo esc_attr($slug); ?>" data-type="application/pdf" data-title="Choose Master Accreditation PDF" style="color: var(--mvc-adm-navy); border-color: #cbd5e1; background: #f8fafc;">
                                📁 Choose / Upload PDF
                            </button>
                        </div>
                        <span style="font-size: 11px; color: var(--mvc-adm-muted);">Enter relative filename (e.g. <code>Notice_1782542808.pdf</code>) or full Media URL.</span>
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Display File Size</label>
                        <input type="text" name="chapters[<?php echo esc_attr($slug); ?>][filesize]" value="<?php echo esc_attr($c['filesize']); ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div style="text-align: right; margin-top: 24px;">
            <button type="submit" class="mvc-adm-btn mvc-adm-btn-gold" style="font-size: 15px; padding: 12px 28px;">
                💾 Save All Chapter Settings
            </button>
        </div>
    </form>
    <?php
    mvc_naac_render_admin_footer();
}

/**
 * 3. TAB: IQAC Council Directory Management (mvc-naac-council)
 */
function mvc_naac_render_admin_council() {
    mvc_naac_render_admin_header('council');

    $chairperson = mvc_get_naac_council_member('chairperson');
    $coordinator = mvc_get_naac_council_member('coordinator');
    $patron      = mvc_get_naac_council_member('patron');
    $ciqa        = mvc_get_naac_council_member('ciqa');

    $exec_members = [
        'chairperson' => ['label' => 'Chairperson • IQAC', 'data' => $chairperson, 'icon' => '⚖️'],
        'coordinator' => ['label' => 'Co-ordinator • IQAC', 'data' => $coordinator, 'icon' => '🧪'],
        'patron'      => ['label' => 'Head of Institution • Patron', 'data' => $patron, 'icon' => '🏛️'],
        'ciqa'        => ['label' => 'CIQA Coordinator • Academic Liaison', 'data' => $ciqa, 'icon' => '📚'],
    ];

    // Senior Faculty Advisory default records
    $council_opts = get_option('mvc_naac_council_settings', []);
    $advisors = $council_opts['advisors'] ?? [
        [
            'name'  => 'Prof. (Dr.) Rajesh Kumar',
            'dept'  => 'Department of Economics',
            'focus' => 'Academic Planning, Curriculum Enrichment & Socio-Economic Audits',
            'photo' => home_url('/wp-content/uploads/live_imported/images/Profile_1764230560.png')
        ],
        [
            'name'  => 'Dr. Mahendra Pratap Singh',
            'dept'  => 'Department of History',
            'focus' => 'Institutional Archives, Heritage Studies & Community Outreach',
            'photo' => home_url('/wp-content/uploads/live_imported/images/Profile_1764229945.png')
        ],
        [
            'name'  => 'Dr. Saikat Debnath',
            'dept'  => 'Department of Physics',
            'focus' => 'Science Laboratory ICT, Experimental Rigor & NEP CBCS Implementation',
            'photo' => home_url('/wp-content/uploads/live_imported/images/Profile_1764230345.png')
        ],
        [
            'name'  => 'Dr. Sujit Kumar Yadav',
            'dept'  => 'Department of Psychology',
            'focus' => 'Student Mentorship, Psychological Counseling & Feedback Analysis',
            'photo' => home_url('/wp-content/uploads/live_imported/images/Profile_1767677034.png')
        ]
    ];
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-council')); ?>">
        <?php wp_nonce_field('mvc_naac_save_council_nonce', 'mvc_naac_nonce'); ?>
        <input type="hidden" name="mvc_naac_action" value="save_council">

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--mvc-adm-navy);">
                    IQAC Executive Leadership &amp; Council Directory
                </h2>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--mvc-adm-muted);">
                    Manage the authentic officers and committee members displayed on the Internal Quality Assurance Cell (<code>/iqac/</code>) portal.
                </p>
            </div>
            <button type="submit" class="mvc-adm-btn mvc-adm-btn-gold" style="font-size: 14px; padding: 10px 22px;">
                💾 Save IQAC Council Directory
            </button>
        </div>

        <!-- 4 MAIN EXECUTIVE OFFICERS -->
        <div class="mvc-adm-card">
            <div class="mvc-adm-card-header">
                <h3 class="mvc-adm-card-title">
                    <span>👑</span> 4 Statutory IQAC Executive Directorate Officers
                </h3>
                <span class="mvc-adm-badge-active">Displayed on /iqac/ Page</span>
            </div>

            <?php foreach ($exec_members as $key => $officer): 
                $d = $officer['data'];
            ?>
            <div style="background: #f8fafc; border: 1px solid var(--mvc-adm-border); border-radius: 10px; padding: 20px; margin-bottom: 18px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px dashed var(--mvc-adm-border); padding-bottom: 10px;">
                    <div style="font-weight: 800; font-size: 15px; color: var(--mvc-adm-navy);">
                        <span style="margin-right: 6px;"><?php echo esc_html($officer['icon']); ?></span>
                        <?php echo esc_html($officer['label']); ?>
                    </div>
                    <code style="font-size: 11px; background: #ffffff; padding: 3px 8px; border-radius: 4px; border: 1px solid var(--mvc-adm-border);">
                        Role Key: <?php echo esc_html($key); ?>
                    </code>
                </div>

                <div class="mvc-adm-form-grid">
                    <div class="mvc-adm-form-group">
                        <label>Honorific &amp; Full Name</label>
                        <input type="text" name="council[<?php echo esc_attr($key); ?>][name]" value="<?php echo esc_attr($d['name']); ?>" required>
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Academic Post / Institutional Role</label>
                        <input type="text" name="council[<?php echo esc_attr($key); ?>][post]" value="<?php echo esc_attr($d['post']); ?>" required>
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Office Chamber / Room</label>
                        <input type="text" name="council[<?php echo esc_attr($key); ?>][office]" value="<?php echo esc_attr($d['office']); ?>">
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Official Phone</label>
                        <input type="text" name="council[<?php echo esc_attr($key); ?>][phone]" value="<?php echo esc_attr($d['phone']); ?>">
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Official Email</label>
                        <input type="email" name="council[<?php echo esc_attr($key); ?>][email]" value="<?php echo esc_attr($d['email']); ?>">
                    </div>
                    <div class="mvc-adm-form-group">
                        <label>Badge Role Title</label>
                        <input type="text" name="council[<?php echo esc_attr($key); ?>][role]" value="<?php echo esc_attr($d['role']); ?>">
                    </div>
                </div>

                <!-- PHOTO UPLOADER ROW -->
                <div class="mvc-adm-form-group" style="margin-top: 10px;">
                    <label>Officer Profile Photograph</label>
                    <div class="mvc-adm-uploader-row">
                        <img id="preview_<?php echo esc_attr($key); ?>" src="<?php echo esc_url($d['photo']); ?>" class="mvc-adm-photo-preview" alt="Preview">
                        <input type="text" id="photo_<?php echo esc_attr($key); ?>" name="council[<?php echo esc_attr($key); ?>][photo]" value="<?php echo esc_attr($d['photo']); ?>" style="flex: 1;">
                        <button type="button" class="mvc-adm-btn mvc-adm-btn-outline mvc-media-upload-btn" data-target="photo_<?php echo esc_attr($key); ?>" data-preview="preview_<?php echo esc_attr($key); ?>" data-type="image" data-title="Choose Officer Photo" style="color: var(--mvc-adm-navy); border-color: #cbd5e1; background: #ffffff;">
                            📷 Choose / Upload Photo
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- SENIOR FACULTY QUALITY ADVISORY COUNCIL -->
        <div class="mvc-adm-card">
            <div class="mvc-adm-card-header">
                <h3 class="mvc-adm-card-title">
                    <span>🏛️</span> Senior Faculty Quality Advisory Council
                </h3>
                <span class="mvc-adm-badge-active">4 Faculty Leads</span>
            </div>

            <div class="mvc-adm-form-grid">
                <?php foreach ($advisors as $idx => $adv): ?>
                <div style="background: #f8fafc; border: 1px solid var(--mvc-adm-border); border-radius: 8px; padding: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <img id="adv_preview_<?php echo esc_attr($idx); ?>" src="<?php echo esc_url($adv['photo']); ?>" class="mvc-adm-photo-preview" style="border-radius: 50%; width: 50px; height: 50px;" alt="Photo">
                        <div>
                            <input type="text" name="advisors[<?php echo esc_attr($idx); ?>][name]" value="<?php echo esc_attr($adv['name']); ?>" style="font-weight: 700; width: 100%; margin-bottom: 4px;" placeholder="Faculty Name">
                            <input type="text" name="advisors[<?php echo esc_attr($idx); ?>][dept]" value="<?php echo esc_attr($adv['dept']); ?>" style="font-size: 12px; width: 100%;" placeholder="Department">
                        </div>
                    </div>
                    <div class="mvc-adm-form-group">
                        <label style="font-size: 11px;">Quality Mandate &amp; Criteria Focus</label>
                        <textarea name="advisors[<?php echo esc_attr($idx); ?>][focus]" rows="2" style="font-size: 12px;"><?php echo esc_textarea($adv['focus']); ?></textarea>
                    </div>
                    <div class="mvc-adm-uploader-row" style="margin-top: 8px;">
                        <input type="text" id="adv_photo_<?php echo esc_attr($idx); ?>" name="advisors[<?php echo esc_attr($idx); ?>][photo]" value="<?php echo esc_attr($adv['photo']); ?>" style="font-size: 11px; flex: 1;">
                        <button type="button" class="mvc-adm-btn mvc-adm-btn-outline mvc-media-upload-btn" data-target="adv_photo_<?php echo esc_attr($idx); ?>" data-preview="adv_preview_<?php echo esc_attr($idx); ?>" data-type="image" data-title="Choose Faculty Photo" style="padding: 4px 8px; font-size: 11px; color: var(--mvc-adm-navy); background: #ffffff; border-color: #cbd5e1;">
                            📷 Change
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="text-align: right; margin-top: 24px;">
            <button type="submit" class="mvc-adm-btn mvc-adm-btn-gold" style="font-size: 15px; padding: 12px 28px;">
                💾 Save IQAC Council Directory
            </button>
        </div>
    </form>
    <?php
    mvc_naac_render_admin_footer();
}

/**
 * 4. TAB: Certified Document Vault & Media Library Explorer (mvc-naac-vault)
 */
function mvc_naac_render_admin_vault() {
    mvc_naac_render_admin_header('vault');
    $doc_dir = get_stylesheet_directory() . '/../../uploads/live_imported/docs/';
    $chapters = mvc_get_naac_chapters();

    // Map files to chapters
    $file_map = [];
    foreach ($chapters as $slug => $c) {
        $file_map[$c['pdf_file']] = $c;
    }

    $all_docs = [];
    if (file_exists($doc_dir) && is_dir($doc_dir)) {
        $scanned = scandir($doc_dir);
        foreach ($scanned as $f) {
            if ($f === '.' || $f === '..') continue;
            if (pathinfo($f, PATHINFO_EXTENSION) === 'pdf') {
                $p = $doc_dir . $f;
                $all_docs[] = [
                    'name'     => $f,
                    'size'     => round(filesize($p) / 1024, 1) . ' KB',
                    'mtime'    => date('d M Y, H:i', filemtime($p)),
                    'url'      => home_url('/wp-content/uploads/live_imported/docs/' . $f),
                    'assigned' => $file_map[$f] ?? null
                ];
            }
        }
    }
    ?>
    <div class="mvc-adm-card">
        <div class="mvc-adm-card-header">
            <div>
                <h2 class="mvc-adm-card-title">
                    <span>🔒</span> Certified Accreditation Document Vault
                </h2>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--mvc-adm-muted);">
                    Physically verified, self-contained institutional records stored in local repository (<code>wp-content/uploads/live_imported/docs/</code>).
                </p>
            </div>
            <button type="button" class="mvc-adm-btn mvc-adm-btn-gold mvc-media-upload-btn" data-type="application/pdf" data-title="Upload Document to Vault">
                ➕ Upload Document to Vault
            </button>
        </div>

        <table class="mvc-adm-table">
            <thead>
                <tr>
                    <th>Official Document Filename</th>
                    <th>Linked Accreditation Chapter</th>
                    <th>File Size</th>
                    <th>Last Verified</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($all_docs)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--mvc-adm-muted); padding: 30px;">
                            No PDF files found in vault directory.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($all_docs as $doc): ?>
                    <tr>
                        <td>
                            <span style="font-size: 15px; margin-right: 6px;">📄</span>
                            <strong><?php echo esc_html($doc['name']); ?></strong>
                            <div style="font-size: 11px; color: var(--mvc-adm-muted); margin-top: 2px;">
                                Local Path: <code>/wp-content/uploads/live_imported/docs/<?php echo esc_html($doc['name']); ?></code>
                            </div>
                        </td>
                        <td>
                            <?php if ($doc['assigned']): ?>
                                <span class="mvc-adm-badge-active" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <?php echo esc_html($doc['assigned']['icon']); ?>
                                    <?php echo esc_html($doc['assigned']['title']); ?>
                                </span>
                            <?php else: ?>
                                <span style="font-size: 12px; color: var(--mvc-adm-muted);">General Archive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <code><?php echo esc_html($doc['size']); ?></code>
                        </td>
                        <td style="font-size: 12px; color: var(--mvc-adm-muted);">
                            <?php echo esc_html($doc['mtime']); ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo esc_url($doc['url']); ?>" target="_blank" rel="noopener" class="mvc-adm-btn mvc-adm-btn-gold" style="padding: 4px 10px; font-size: 11.5px;">
                                Preview PDF ↗
                            </a>
                            <button type="button" class="mvc-adm-btn mvc-adm-btn-outline" onclick="navigator.clipboard.writeText('<?php echo esc_url($doc['url']); ?>'); alert('Copied PDF URL to clipboard: <?php echo esc_url($doc['url']); ?>');" style="padding: 4px 10px; font-size: 11.5px; color: var(--mvc-adm-navy); background: #ffffff; border-color: #cbd5e1;">
                                📋 Copy URL
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
    mvc_naac_render_admin_footer();
}
