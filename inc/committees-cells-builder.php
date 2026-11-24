<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Committees & Cells Page Builder - 10/10 Standard (Beautiful & Simple)
 * Redesigned from original website benchmark (mvcollegebuxar.ac.in/committees-cells)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Committees & Cells Page
 */
function mvc_render_committees_cells_page($override_form_id = null) {
    global $mvc_shortcode_context;
    $site_url = home_url();

    // Query all 15 authentic statutory committees from WordPress
    $committees_query = new WP_Query([
        'post_type'      => 'committee',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'orderby'        => 'title',
        'order'          => 'ASC'
    ]);

    $committees = [];
    if ($committees_query->have_posts()) {
        while ($committees_query->have_posts()) {
            $committees_query->the_post();
            $cid = get_the_ID();
            $slug = get_post_field('post_name', $cid);

            $hindi_title = get_post_meta($cid, 'committee_hindi_title', true);
            $head        = get_post_meta($cid, 'committee_head', true);
            $email       = get_post_meta($cid, 'committee_email', true);
            $desc        = get_post_meta($cid, 'committee_description', true);
            $members_raw = get_post_meta($cid, 'members_details', true);

            // PDFs
            $notif_pdf   = get_post_meta($cid, 'notification_pdf', true);
            $minutes_pdf = get_post_meta($cid, 'meeting_minutes_pdf', true);
            $atr_pdf     = get_post_meta($cid, 'atr_pdf', true);

            // Format members into structured table rows
            $members = [];
            if ($members_raw) {
                $lines = explode("\n", strip_tags($members_raw));
                $sr = 1;
                foreach ($lines as $line) {
                    $line = trim($line, " \t\n\r\0\x0B•-–*");
                    if (empty($line)) continue;

                    $parts = array_map('trim', explode('—', str_replace(['-', '|', ':'], '—', $line)));
                    $m_name = $parts[0] ?? $line;
                    $m_role = $parts[1] ?? 'Executive Member';
                    $default_college_email = mvc_get_global_setting('college_email', get_option('admin_email'));
                    $m_email = $email ?: $default_college_email;

                    if (stripos($m_role, 'Convener') !== false || stripos($m_role, 'Chair') !== false || stripos($m_role, 'Officer') !== false) {
                        $m_email = $email ?: $default_college_email;
                    }

                    $members[] = [
                        'sr'          => $sr++,
                        'name'        => $m_name,
                        'designation' => $m_role,
                        'email'       => $m_email
                    ];
                }
            }

            // Fallback members if empty
            if (empty($members)) {
                $default_email = mvc_get_global_setting('college_email', get_option('admin_email'));
                $members = [
                    ['sr' => 1, 'name' => $head ?: 'Prof. (Dr.) Krishna Kant Singh', 'designation' => 'Chairperson / Head', 'email' => $email ?: $default_email],
                    ['sr' => 2, 'name' => 'Dr. Ras Bihari Sharma', 'designation' => 'Senior Faculty Representative', 'email' => $default_email],
                    ['sr' => 3, 'name' => 'Dr. Arvind Verma', 'designation' => 'Academic Member', 'email' => $default_email]
                ];
            }

            $committees[] = [
                'id'          => $cid,
                'slug'        => $slug,
                'title'       => get_the_title(),
                'hindi_title' => $hindi_title ?: '',
                'head'        => $head ?: 'Institutional Committee Head',
                'email'       => $email ?: mvc_get_global_setting('college_email', get_option('admin_email')),
                'desc'        => $desc ?: 'Statutory collegiate committee established under university and regulatory statutes for institutional excellence and student welfare.',
                'notif_pdf'   => $notif_pdf ?: '',
                'minutes_pdf' => $minutes_pdf ?: '',
                'atr_pdf'     => $atr_pdf ?: '',
                'members'     => $members
            ];
        }
        wp_reset_postdata();
    }

    // Default active committee is IQAC if exists, otherwise first
    $active_slug = $committees[0]['slug'] ?? '';
    foreach ($committees as $c) {
        if ($c['slug'] === 'internal-quality-assurance-cell-iqac' || stripos($c['title'], 'IQAC') !== false) {
            $active_slug = $c['slug'];
            break;
        }
    }

    ob_start();
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-gold: #D4AF37;
            --mvc-gold-light: #FFF9E6;
            --mvc-slate: #0F172A;
            --mvc-muted: #64748B;
            --mvc-border: #E2E8F0;
            --mvc-bg-alt: #F8FAFC;
        }

        /* Breakout to 100% Full-Width Stretched */
        body.page-template-template-committees-cells #page,
        body.page-template-template-committees-cells .site-content,
        body.page-template-template-committees-cells .ast-container,
        body.page-template-template-committees-cells #content,
        body.page-template-template-committees-cells #primary,
        body.page-template-template-committees-cells #main,
        body.page-template-template-committees-cells .entry-content {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .mvc-comm-wrapper {
            background: #F8FAFC;
            color: var(--mvc-slate);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            padding-bottom: 70px;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        /* Hero Banner */
        .mvc-comm-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #0a3366 100%);
            color: #FFFFFF;
            padding: clamp(40px, 5vw, 64px) clamp(20px, 4vw, 60px);
            text-align: center;
            border-bottom: 4px solid var(--mvc-gold);
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .mvc-comm-hero-inner {
            max-width: 100%;
            margin: 0 auto;
        }

        .mvc-comm-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 16px;
        }

        .mvc-comm-breadcrumb a {
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-comm-breadcrumb a:hover {
            color: var(--mvc-gold);
        }

        .mvc-comm-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .mvc-comm-hero-hindi {
            font-size: clamp(16px, 2.4vw, 20px);
            font-weight: 600;
            color: var(--mvc-gold);
            margin-bottom: 12px;
        }

        .mvc-comm-hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.6;
            margin: 0 auto;
            max-width: 900px;
        }

        /* Container - 100% Full Width */
        .mvc-comm-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 40px clamp(20px, 4vw, 60px) 0 !important;
            box-sizing: border-box !important;
        }

        /* Mobile Dropdown Selector (visible <= 900px) */
        .mvc-comm-mobile-select-wrap {
            display: none;
            margin-bottom: 24px;
        }

        .mvc-comm-mobile-select {
            width: 100%;
            padding: 14px 16px;
            border-radius: 10px;
            border: 2px solid var(--mvc-navy);
            background: #FFFFFF;
            font-size: 15px;
            font-weight: 700;
            color: var(--mvc-navy);
            outline: none;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.06);
        }

        /* Main Master-Detail Grid - Full Canvas Stretched */
        .mvc-comm-grid {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 32px;
            width: 100%;
            align-items: flex-start;
        }

        /* Left Column: Committees List */
        .mvc-comm-sidebar {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            position: sticky;
            top: 24px;
        }

        .mvc-comm-sidebar-header {
            background: var(--mvc-navy);
            color: #FFFFFF;
            padding: 18px 20px;
            font-size: 15px;
            font-weight: 800;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mvc-comm-sidebar-count {
            background: var(--mvc-gold);
            color: var(--mvc-navy-dark);
            font-size: 12px;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 999px;
        }

        .mvc-comm-list {
            list-style: none;
            margin: 0;
            padding: 0;
            max-height: 720px;
            overflow-y: auto;
        }

        .mvc-comm-item-btn {
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
            border-bottom: 1px solid #F1F5F9;
            padding: 14px 20px;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .mvc-comm-item-btn:hover {
            background: #F8FAFC;
            color: var(--mvc-navy);
            padding-left: 24px;
        }

        .mvc-comm-item-btn.active {
            background: #F0F4F8;
            color: var(--mvc-navy);
            font-weight: 800;
            border-left: 4px solid var(--mvc-navy);
            padding-left: 18px;
        }

        .mvc-comm-item-btn .mvc-bullet {
            font-size: 11px;
            color: var(--mvc-gold-dark);
        }

        /* Right Column: Active Dossier */
        .mvc-comm-content {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 33, 71, 0.05);
            overflow: hidden;
        }

        /* Dossier Header */
        .mvc-dossier-header {
            background: linear-gradient(135deg, #F8FAFC 0%, #FFFFFF 100%);
            border-bottom: 2px solid #F1F5F9;
            padding: 28px 30px 22px;
        }

        .mvc-dossier-title {
            font-size: clamp(20px, 2.5vw, 26px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px;
        }

        .mvc-dossier-hindi {
            font-size: 15px;
            font-weight: 600;
            color: var(--mvc-maroon);
            margin-bottom: 14px;
        }

        .mvc-dossier-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 13.5px;
            color: #475569;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px dashed var(--mvc-border);
        }

        .mvc-dossier-meta strong {
            color: var(--mvc-navy);
        }

        .mvc-dossier-desc {
            font-size: 14px;
            line-height: 1.65;
            color: #334155;
            margin: 0;
        }

        /* Dossier Tabs (Original Website Benchmark) */
        .mvc-comm-tabs-nav {
            display: flex;
            background: #F1F5F9;
            border-bottom: 2px solid var(--mvc-navy);
            overflow-x: auto;
        }

        .mvc-comm-tab-trigger {
            background: transparent;
            border: none;
            padding: 14px 22px;
            font-size: 13.5px;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .mvc-comm-tab-trigger:hover {
            color: var(--mvc-navy);
            background: rgba(0, 33, 71, 0.04);
        }

        .mvc-comm-tab-trigger.active {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        /* Tab Content Panel */
        .mvc-comm-panel {
            padding: 24px 28px 32px;
            display: none;
        }

        .mvc-comm-panel.active {
            display: block;
        }

        /* Clean Authentic Table (Original Benchmark) */
        .mvc-comm-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
            font-size: 13.5px;
            border: 1px solid var(--mvc-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .mvc-comm-table thead th {
            background: #F8FAFC;
            color: var(--mvc-navy);
            font-weight: 800;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 2px solid var(--mvc-border);
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mvc-comm-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F5F9;
            color: #334155;
            vertical-align: middle;
        }

        .mvc-comm-table tbody tr:last-child td {
            border-bottom: none;
        }

        .mvc-comm-table tbody tr:hover {
            background: #F8FAFC;
        }

        .mvc-btn-download {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FFFFFF;
            color: var(--mvc-navy);
            border: 1.5px solid var(--mvc-navy);
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mvc-btn-download:hover {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        /* Mobile Optimization */
        @media (max-width: 900px) {
            .mvc-comm-grid {
                grid-template-columns: 1fr;
            }
            .mvc-comm-sidebar {
                display: none;
            }
            .mvc-comm-mobile-select-wrap {
                display: block;
            }
            .mvc-dossier-header {
                padding: 20px;
            }
            .mvc-comm-panel {
                padding: 18px;
            }
            .mvc-comm-table thead {
                display: none;
            }
            .mvc-comm-table, .mvc-comm-table tbody, .mvc-comm-table tr, .mvc-comm-table td {
                display: block;
                width: 100%;
            }
            .mvc-comm-table tr {
                background: #FFFFFF;
                border: 1px solid var(--mvc-border);
                border-radius: 8px;
                margin-bottom: 14px;
                padding: 12px;
            }
            .mvc-comm-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px dashed #F1F5F9;
                font-size: 13px;
                text-align: right;
            }
            .mvc-comm-table td:last-child {
                border-bottom: none;
                padding-top: 10px;
                justify-content: flex-end;
            }
            .mvc-comm-table td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--mvc-navy);
                text-align: left;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.4px;
            }
        }
    </style>

    <div class="mvc-comm-wrapper">
        <!-- Hero Banner -->
        <header class="mvc-comm-hero">
            <div class="mvc-comm-hero-inner">
                <nav class="mvc-comm-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>">Administration</a>
                    <span>/</span>
                    <span style="color: var(--mvc-gold);">Committees &amp; Cells</span>
                </nav>

                <h1>Statutory Committees &amp; Cells</h1>
                <div class="mvc-comm-hero-hindi">सांविधिक समितियां एवं प्रकोष्ठ — महर्षि विश्वामित्र महाविद्यालय</div>
                <p class="mvc-comm-hero-desc">
                    Participatory governance framework safeguarding academic standards, anti-ragging vigilance, student grievance redressal, quality assurance, and community extension.
                </p>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mvc-comm-container">

            <!-- Mobile Selector -->
            <div class="mvc-comm-mobile-select-wrap">
                <label for="mvcCommSelect" style="display:block; font-size:12px; font-weight:800; text-transform:uppercase; color:var(--mvc-navy); margin-bottom:6px;">Select Committee / Cell:</label>
                <select id="mvcCommSelect" class="mvc-comm-mobile-select" onchange="mvcSelectCommittee(this.value)">
                    <?php foreach ($committees as $c): ?>
                        <option value="<?php echo esc_attr($c['slug']); ?>" <?php selected($c['slug'], $active_slug); ?>>
                            <?php echo esc_html($c['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Master-Detail Layout -->
            <div class="mvc-comm-grid">
                
                <!-- Left Sidebar: Committee Navigation -->
                <aside class="mvc-comm-sidebar" aria-label="Committees Navigation">
                    <div class="mvc-comm-sidebar-header">
                        <span>Statutory Bodies</span>
                        <span class="mvc-comm-sidebar-count"><?php echo count($committees); ?> Active</span>
                    </div>
                    <ul class="mvc-comm-list">
                        <?php foreach ($committees as $c): ?>
                            <li>
                                <button type="button" 
                                        class="mvc-comm-item-btn <?php echo ($c['slug'] === $active_slug) ? 'active' : ''; ?>" 
                                        data-slug="<?php echo esc_attr($c['slug']); ?>"
                                        onclick="mvcSelectCommittee('<?php echo esc_attr($c['slug']); ?>')">
                                    <span><?php echo esc_html($c['title']); ?></span>
                                    <span class="mvc-bullet">&rarr;</span>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>

                <!-- Right Column: Active Dossier -->
                <section class="mvc-comm-content" id="mvcActiveDossier">
                    
                    <?php foreach ($committees as $c): ?>
                        <div class="mvc-committee-dossier-wrap" id="dossier-<?php echo esc_attr($c['slug']); ?>" style="<?php echo ($c['slug'] === $active_slug) ? 'display:block;' : 'display:none;'; ?>">
                            
                            <!-- Header Area -->
                            <div class="mvc-dossier-header">
                                <h2 class="mvc-dossier-title"><?php echo esc_html($c['title']); ?></h2>
                                <?php if (!empty($c['hindi_title'])): ?>
                                    <div class="mvc-dossier-hindi"><?php echo esc_html($c['hindi_title']); ?></div>
                                <?php endif; ?>

                                <div class="mvc-dossier-meta">
                                    <div><strong>Convener / Head:</strong> <?php echo esc_html($c['head']); ?></div>
                                    <div><strong>Contact:</strong> <?php echo esc_html($c['email']); ?></div>
                                </div>

                                <p class="mvc-dossier-desc"><?php echo esc_html($c['desc']); ?></p>
                            </div>

                            <!-- Statutory Committee Members Table -->
                            <div class="mvc-comm-members-wrapper" style="margin-top: 15px;">
                                <h4 style="font-size: 15px; font-weight: 800; color: var(--mvc-navy); margin: 0 0 10px; display: flex; align-items: center; gap: 8px;">
                                    <span>👥 Committee Composition &amp; Members</span>
                                    <span style="font-size: 12px; font-weight: 700; background: #EEF2F6; color: #475569; padding: 2px 8px; border-radius: 999px;"><?php echo count($c['members']); ?> Members</span>
                                </h4>
                                <table class="mvc-comm-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px; text-align: center;">Sr No</th>
                                            <th>Member Name</th>
                                            <th>Designation / Institutional Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($c['members'] as $m): ?>
                                            <tr>
                                                <td data-label="Sr No" style="text-align: center;"><?php echo esc_html($m['sr']); ?></td>
                                                <td data-label="Member Name"><strong><?php echo esc_html($m['name']); ?></strong></td>
                                                <td data-label="Designation"><?php echo esc_html($m['designation']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </section>
            </div>

        </main>
    </div>

    <!-- Client Script for Instant Switching -->
    <script>
        function mvcSelectCommittee(slug) {
            // Update dropdown
            var select = document.getElementById('mvcCommSelect');
            if (select && select.value !== slug) {
                select.value = slug;
            }

            // Update sidebar buttons
            var buttons = document.querySelectorAll('.mvc-comm-item-btn');
            buttons.forEach(function(btn) {
                if (btn.getAttribute('data-slug') === slug) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Show dossier
            var dossiers = document.querySelectorAll('.mvc-committee-dossier-wrap');
            dossiers.forEach(function(d) {
                d.style.display = 'none';
            });
            var target = document.getElementById('dossier-' + slug);
            if (target) {
                target.style.display = 'block';
            }

            // Scroll gently on mobile
            if (window.innerWidth <= 900) {
                var dossierSec = document.getElementById('mvcActiveDossier');
                if (dossierSec) {
                    dossierSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        }

        function mvcSwitchTab(triggerBtn, panelId) {
            var parent = triggerBtn.closest('.mvc-committee-dossier-wrap');
            if (!parent) return;

            var triggers = parent.querySelectorAll('.mvc-comm-tab-trigger');
            triggers.forEach(function(btn) {
                btn.classList.remove('active');
            });
            triggerBtn.classList.add('active');

            var panels = parent.querySelectorAll('.mvc-comm-panel');
            panels.forEach(function(panel) {
                panel.classList.remove('active');
            });
            var activePanel = document.getElementById('tab-' + panelId);
            if (activePanel) {
                activePanel.classList.add('active');
            }
        }
    </script>
    <?php
    return ob_get_clean();
}
