<?php
/**
 * Master Utility Suite Builder for Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * 
 * Houses 10/10 Prestigious, Mobile-First, Repository Self-Contained Renderers:
 * 1. Automated Central Library (/library/)
 * 2. Digital e-Resources & Academic Repositories (/e-resources/)
 * 3. Statutory Anti-Ragging Cell & Zero Tolerance Monitoring (/anti-ragging-cell/)
 * 4. Official Public Tenders & Procurement (/tender/)
 * 5. Centralized Institutional Help Desk (/help-desk/)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shared Styles for Utility Suite
 */
function mvc_render_utility_suite_styles() {
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #5c0000;
            --mvc-gold: #D4AF37;
            --mvc-gold-light: #FBF6E9;
            --mvc-slate: #1E293B;
            --mvc-slate-light: #64748B;
            --mvc-border: #E2E8F0;
            --mvc-bg-alt: #F8FAFC;
        }

        /* 100% Full-Width Stretched Canvas */
        body.page-template-template-library #page,
        body.page-template-template-library .site-content,
        body.page-template-template-library .ast-container,
        body.page-template-template-e-resources #page,
        body.page-template-template-e-resources .site-content,
        body.page-template-template-e-resources .ast-container,
        body.page-template-template-anti-ragging #page,
        body.page-template-template-anti-ragging .site-content,
        body.page-template-template-anti-ragging .ast-container,
        body.page-template-template-tender #page,
        body.page-template-template-tender .site-content,
        body.page-template-template-tender .ast-container,
        body.page-template-template-help-desk #page,
        body.page-template-template-help-desk .site-content,
        body.page-template-template-help-desk .ast-container {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .mvc-util-root {
            background: #F8FAFC;
            color: var(--mvc-slate);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            width: 100% !important;
            box-sizing: border-box;
            line-height: 1.6;
        }

        .mvc-util-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            box-sizing: border-box;
        }

        /* Hero Banner */
        .mvc-util-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #0d3b66 100%);
            color: #FFFFFF;
            padding: clamp(36px, 5vw, 60px) 20px clamp(44px, 6vw, 70px);
            position: relative;
            overflow: hidden;
            border-bottom: 4px solid var(--mvc-gold);
        }

        .mvc-util-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 85% 20%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 15% 85%, rgba(128, 0, 0, 0.25) 0%, transparent 55%);
            pointer-events: none;
        }

        .mvc-util-hero-inner {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        .mvc-util-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 14px;
            font-weight: 500;
        }

        .mvc-util-breadcrumb a {
            color: var(--mvc-gold);
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-util-breadcrumb a:hover {
            color: #FFFFFF;
            text-decoration: underline;
        }

        .mvc-util-hero-title {
            font-size: clamp(24px, 4vw, 38px);
            font-weight: 800;
            line-height: 1.25;
            margin: 0 0 10px;
            color: #FFFFFF;
            letter-spacing: -0.5px;
        }

        .mvc-util-hero-subtitle {
            font-size: clamp(14px, 1.8vw, 16.5px);
            color: var(--mvc-gold-light);
            font-weight: 600;
            margin: 0 0 16px;
        }

        .mvc-util-hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.85);
            max-width: 860px;
            line-height: 1.65;
            margin: 0 0 24px;
        }

        .mvc-util-hero-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .mvc-util-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #FFFFFF;
        }

        .mvc-util-pill-gold {
            background: rgba(212, 175, 55, 0.2);
            border-color: var(--mvc-gold);
            color: #FFF9E6;
        }

        /* Section Layout */
        .mvc-util-main {
            padding: 40px 0 80px;
        }

        .mvc-util-card {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.04);
            padding: clamp(24px, 4vw, 40px);
            margin-bottom: 36px;
            box-sizing: border-box;
        }

        .mvc-util-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #F1F5F9;
        }

        .mvc-util-section-title {
            font-size: clamp(20px, 2.5vw, 26px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mvc-util-badge {
            background: var(--mvc-gold-light);
            color: #855d0a;
            border: 1px solid rgba(212, 175, 55, 0.4);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        /* Stats Grid */
        .mvc-util-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 36px;
        }

        .mvc-util-stat-box {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 24px 20px;
            border-left: 5px solid var(--mvc-navy);
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            transition: all 0.25s ease;
        }

        .mvc-util-stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 33, 71, 0.08);
            border-left-color: var(--mvc-gold);
        }

        .mvc-util-stat-num {
            font-size: 32px;
            font-weight: 800;
            color: var(--mvc-navy);
            line-height: 1;
            margin-bottom: 6px;
        }

        .mvc-util-stat-label {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-slate);
            margin-bottom: 4px;
        }

        .mvc-util-stat-desc {
            font-size: 12px;
            color: var(--mvc-slate-light);
            line-height: 1.4;
            margin: 0;
        }

        /* Buttons & Triggers */
        .mvc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
            box-sizing: border-box;
            letter-spacing: 0.2px;
        }

        .mvc-btn-navy {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-dark) 100%);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.2);
        }

        .mvc-btn-navy:hover {
            background: var(--mvc-navy-dark);
            color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 33, 71, 0.3);
        }

        .mvc-btn-gold {
            background: linear-gradient(135deg, #E5C158 0%, var(--mvc-gold) 100%);
            color: #00152e;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.25);
        }

        .mvc-btn-gold:hover {
            background: #b8911b;
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        .mvc-btn-maroon {
            background: linear-gradient(135deg, #990000 0%, var(--mvc-maroon) 100%);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.25);
        }

        .mvc-btn-maroon:hover {
            background: var(--mvc-maroon-dark);
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        .mvc-btn-outline {
            background: transparent;
            border: 1.5px solid var(--mvc-border);
            color: var(--mvc-slate);
        }

        .mvc-btn-outline:hover {
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
            background: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Tables & Responsive Auto-Cards */
        .mvc-util-table-wrap {
            overflow-x: auto;
            border: 1px solid var(--mvc-border);
            border-radius: 10px;
            background: #FFFFFF;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .mvc-util-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .mvc-util-table thead tr {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        .mvc-util-table th {
            padding: 14px 18px;
            font-weight: 700;
            font-size: 13.5px;
            letter-spacing: 0.3px;
        }

        .mvc-util-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--mvc-border);
            color: var(--mvc-slate);
        }

        .mvc-util-table tbody tr:last-child td {
            border-bottom: none;
        }

        .mvc-util-table tbody tr:hover td {
            background: var(--mvc-bg-alt);
        }

        @media (max-width: 767px) {
            .mvc-util-table thead {
                display: none;
            }

            .mvc-util-table, .mvc-util-table tbody, .mvc-util-table tr, .mvc-util-table td {
                display: block;
                width: 100% !important;
                box-sizing: border-box;
            }

            .mvc-util-table tr {
                background: #FFFFFF !important;
                border: 1.5px solid var(--mvc-border) !important;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .mvc-util-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border: none;
                border-bottom: 1px dashed var(--mvc-border);
                font-size: 13.5px;
                text-align: right;
            }

            .mvc-util-table td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--mvc-navy);
                text-align: left;
                padding-right: 12px;
                flex-shrink: 0;
            }

            .mvc-util-table td:last-child {
                border-bottom: none;
                padding-top: 14px;
                justify-content: flex-end;
            }
        }

        /* In-Charge Profile Card */
        .mvc-incharge-card {
            background: linear-gradient(135deg, #FFFFFF 0%, var(--mvc-bg-alt) 100%);
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            margin-bottom: 32px;
        }

        @media (max-width: 767px) {
            .mvc-incharge-card {
                flex-direction: column;
                text-align: center;
            }
        }

        .mvc-incharge-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--mvc-gold);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            background: #FFFFFF;
            flex-shrink: 0;
        }

        .mvc-incharge-info h3 {
            font-size: 20px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-incharge-info .mvc-role {
            font-size: 14px;
            font-weight: 700;
            color: var(--mvc-maroon);
            margin: 0 0 6px;
        }

        .mvc-incharge-info p {
            font-size: 13.5px;
            color: var(--mvc-slate-light);
            margin: 0;
            line-height: 1.5;
        }

        /* Interactive Dossier Modal */
        .mvc-dossier-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 15, 35, 0.75);
            backdrop-filter: blur(6px);
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .mvc-dossier-dialog {
            background: #FFFFFF;
            border-radius: 16px;
            width: 100%;
            max-width: 960px;
            height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.4);
            border: 2px solid var(--mvc-gold);
        }

        .mvc-dossier-header {
            background: var(--mvc-navy);
            color: #FFFFFF;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mvc-dossier-header h3 {
            font-size: 17px;
            font-weight: 800;
            color: #FFFFFF;
            margin: 0;
        }

        .mvc-dossier-close {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #FFFFFF;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .mvc-dossier-close:hover {
            background: var(--mvc-maroon);
        }

        .mvc-dossier-frame {
            flex-grow: 1;
            width: 100%;
            border: none;
            background: #F1F5F9;
        }

        /* -------------------------------------------------------------
           PAGINATION DECK (10/10 MASTERPIECE)
           ------------------------------------------------------------- */
        .mvc-pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 14px 20px;
            margin-top: 24px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
        }

        .mvc-pagination-info {
            font-size: 13.5px;
            color: #64748B;
            font-weight: 600;
        }

        .mvc-pagination-info strong {
            color: var(--mvc-navy);
            font-weight: 700;
        }

        .mvc-pagination-controls {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .mvc-pag-btn,
        .mvc-pag-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: var(--mvc-navy);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
        }

        .mvc-pag-btn:hover:not(:disabled),
        .mvc-pag-num:hover:not(.is-active) {
            background: #EEF2F6;
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
            transform: translateY(-1px);
        }

        .mvc-pag-num.is-active {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-dark) 100%);
            border-color: var(--mvc-gold);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.25);
        }

        .mvc-pag-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: #F1F5F9;
            border-color: #E2E8F0;
            color: #94A3B8;
            transform: none !important;
        }

        @media (max-width: 640px) {
            .mvc-pagination-bar {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 16px;
                gap: 12px;
            }
            .mvc-pagination-controls {
                justify-content: center;
                width: 100%;
            }
            .mvc-pag-btn, .mvc-pag-num {
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                font-size: 13px;
            }
        }
    </style>
    <?php
}

/**
 * 1. LIBRARY PAGE RENDERER (/library/)
 */
function mvc_render_library_page() {
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 294;
    $hero_title = mvc_get_page_meta($post_id, 'library_hero_title', 'Automated Central Library & Information Center');
    $hero_subtitle = mvc_get_page_meta($post_id, 'library_hero_subtitle', 'स्वचालित केंद्रीय ग्रंथालय एवं ज्ञान संसाधन केंद्र');
    $hero_desc = mvc_get_page_meta($post_id, 'library_hero_desc', 'The intellectual heart of Maharshi Vishwamitra Mahavidyalaya, providing comprehensive print volumes, computerized circulation, digital e-consortium carrels, and INFLIBNET access for undergraduate and postgraduate scholars.');
    $opac_url = mvc_get_page_meta($post_id, 'library_opac_url', 'https://mvc.autolib.org/');
    $nlist_url = mvc_get_page_meta($post_id, 'library_nlist_url', 'https://nlist.inflibnet.ac.in/');
    
    $default_stats = [
        ['num' => '15,000+', 'label' => 'Print Volumes & Titles', 'desc' => 'Textbooks, reference books, and rare regional chronicles.'],
        ['num' => '6,000+', 'label' => 'Peer-Reviewed e-Journals', 'desc' => 'National and international titles via INFLIBNET N-LIST.'],
        ['num' => '1,99,500+', 'label' => 'Full-Text e-Books', 'desc' => 'Digital scholarly textbooks across Arts, Science & Commerce.'],
        ['num' => '150+', 'label' => 'Reading Hall Capacity', 'desc' => 'Quiet study carrels, faculty zone, and digital terminal desks.']
    ];
    $stats = mvc_get_page_array($post_id, 'library_stats', $default_stats);
    ?>
    <div class="mvc-util-root">
        <!-- Hero Banner -->
        <header class="mvc-util-hero">
            <div class="mvc-util-hero-inner">
                <nav class="mvc-util-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <span>Automated Central Library</span>
                </nav>
                <h1 class="mvc-util-hero-title"><?php echo esc_html($hero_title); ?></h1>

                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">🏛 Established 11 June 1958</span>
                    <span class="mvc-util-pill">📖 15,000+ Print Volumes</span>
                    <!-- <span class="mvc-util-pill">🌐 INFLIBNET N-LIST Member</span>
                    <span class="mvc-util-pill">💻 AutoLib Integrated System</span> -->
                </div>
            </div>
        </header>

        <main class="mvc-util-main">
            <div class="mvc-util-container">
                <!-- Direct Access Action Hub -->
                <!-- <div style="background: linear-gradient(135deg, #00152e 0%, var(--mvc-navy) 100%); border-radius: 14px; padding: 24px 28px; margin-bottom: 36px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px; box-shadow: 0 8px 24px rgba(0, 33, 71, 0.12); border-left: 6px solid var(--mvc-gold);">
                    <div>
                        <h3 style="color:#FFFFFF; margin:0 0 6px; font-size:20px; font-weight:800;">Central Library Automation System (OPAC)</h3>
                        <p style="color:rgba(255,255,255,0.85); margin:0; font-size:14px;">Search online book catalogs, circulation records, and track holding availability remotely.</p>
                    </div>
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <a href="<?php echo esc_url($opac_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-gold">
                            <span>Open AutoLib OPAC &rarr;</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </a>
                        <a href="<?php echo esc_url($nlist_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-maroon">
                            <span>N-LIST e-Consortium &rarr;</span>
                        </a>
                    </div>
                </div> -->

                <!-- Stats Grid -->
                <div class="mvc-util-stats-grid">
                    <?php foreach ($stats as $st): ?>
                        <div class="mvc-util-stat-box">
                            <div class="mvc-util-stat-num"><?php echo esc_html($st['num']); ?></div>
                            <div class="mvc-util-stat-label"><?php echo esc_html($st['label']); ?></div>
                            <p class="mvc-util-stat-desc"><?php echo esc_html($st['desc']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- In-Charge Profile Block -->
                <div class="mvc-incharge-card">
                    <img src="<?php echo esc_url(home_url('/wp-content/uploads/faculty-images/Profile_1764231326.png')); ?>" alt="Dr. Yogershi Rajpoot" class="mvc-incharge-avatar" onerror="this.src='https://ui-avatars.com/api/?name=Dr+Yogershi+Rajpoot&background=002147&color=fff';" />
                    <div class="mvc-incharge-info">
                        <h3>Dr. Yogershi Rajpoot</h3>
                        <div class="mvc-role">Convener & Member, Library Advisory Committee</div>
                        <p>
                            Department of Political Science, Maharshi Vishwamitra Mahavidyalaya, Buxar<br />
                            <strong>Official Email:</strong> yogirshi@gmail.com &bull; <strong>Chamber:</strong> Central Library Advisory Desk
                        </p>
                    </div>
                </div>

                <!-- About & Infrastructure -->
                <!-- <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">Institutional Overview & Reading Ecosystem</h2>
                        <span class="mvc-util-badge">Criterion 4.2 Mandate</span>
                    </div>
                    <p style="font-size:15px; color:var(--mvc-slate); line-height:1.7; margin-bottom:24px;">
                        The Central Library is an integral part of the academic infrastructure of M.V. College, Buxar, serving as a center for knowledge, learning, and research. It provides students and faculty members with access to a wide range of educational resources that support academic excellence, intellectual curiosity, and competitive examination preparation.
                    </p>
                    <p style="font-size:15px; color:var(--mvc-slate); line-height:1.7; margin-bottom:32px;">
                        A peaceful and conducive atmosphere is provided across spacious reading rooms. The library collection includes encyclopedias, dictionaries, historical gazetteers, government reports, competitive periodicals (Pratiyogita Darpan, Yojana, Kurukshetra), and daily newspapers in Hindi and English.
                    </p>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:20px;">
                        <div style="background:var(--mvc-bg-alt); padding:22px; border-radius:10px; border-left:4px solid var(--mvc-navy);">
                            <h4 style="margin:0 0 8px; color:var(--mvc-navy); font-size:16px;">Automated Circulation (AutoLib)</h4>
                            <p style="margin:0; font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6;">
                                Equipped with AutoLib integrated library software. Barcoded student identity cards enable fast check-outs, returns, and digital tracking.
                            </p>
                        </div>
                        <div style="background:var(--mvc-bg-alt); padding:22px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                            <h4 style="margin:0 0 8px; color:var(--mvc-maroon); font-size:16px;">Book Bank for SC/ST & Meritorious Scholars</h4>
                            <p style="margin:0; font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6;">
                                Subsidized book bank scheme providing complete subject textbook kits for underprivileged and meritorious scholars for the full semester duration.
                            </p>
                        </div>
                        <div style="background:var(--mvc-bg-alt); padding:22px; border-radius:10px; border-left:4px solid var(--mvc-gold);">
                            <h4 style="margin:0 0 8px; color:#96720d; font-size:16px;">INFLIBNET N-LIST & Digital Terminals</h4>
                            <p style="margin:0; font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6;">
                                10 dedicated computer terminals with broadband Wi-Fi, allowing access to international e-journals, Shodhganga theses, and national digital repositories.
                            </p>
                        </div>
                    </div>
                </div> -->

                <!-- Operating Hours & Borrowing Entitlement Table -->
                <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">Library Timings & Borrowing Regulations</h2>
                        <span class="mvc-util-badge">Circulation Rules</span>
                    </div>

                    <div class="mvc-util-table-wrap">
                        <table class="mvc-util-table">
                            <thead>
                                <tr>
                                    <th>Stakeholder Category</th>
                                    <th>Max Books Issued</th>
                                    <th>Borrowing Period</th>
                                    <th>Overdue Fine</th>
                                    <th>Renewal Policy</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Category"><strong>Undergraduate Scholars (FYUGP NEP)</strong></td>
                                    <td data-label="Max Books">2 Books</td>
                                    <td data-label="Borrowing Period">14 Days</td>
                                    <td data-label="Overdue Fine">₹1.00 / day / volume</td>
                                    <td data-label="Renewal">1-Time Renewal if unreserved</td>
                                </tr>
                                <tr>
                                    <td data-label="Category"><strong>Postgraduate Scholars (M.A. CBCS)</strong></td>
                                    <td data-label="Max Books">3 Books</td>
                                    <td data-label="Borrowing Period">21 Days</td>
                                    <td data-label="Overdue Fine">₹1.00 / day / volume</td>
                                    <td data-label="Renewal">1-Time Renewal if unreserved</td>
                                </tr>
                                <tr>
                                    <td data-label="Category"><strong>Faculty & Teaching Staff</strong></td>
                                    <td data-label="Max Books">5 Books</td>
                                    <td data-label="Borrowing Period">30 Days</td>
                                    <td data-label="Overdue Fine">Nil (Annual Return)</td>
                                    <td data-label="Renewal">Renewable upon verification</td>
                                </tr>
                                <tr>
                                    <td data-label="Category"><strong>Non-Teaching Staff Members</strong></td>
                                    <td data-label="Max Books">2 Books</td>
                                    <td data-label="Borrowing Period">14 Days</td>
                                    <td data-label="Overdue Fine">₹1.00 / day / volume</td>
                                    <td data-label="Renewal">Renewable upon request</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top:20px; background:#FAF9F6; border:1px solid rgba(212,175,55,0.4); border-radius:8px; padding:16px 20px;">
                        <strong style="color:var(--mvc-navy); font-size:14px;">⏱ Library Operational Timings:</strong>
                        <span style="font-size:13.5px; color:var(--mvc-slate); margin-left:8px;">
                            Monday to Saturday: 10:00 AM – 5:00 PM &bull; Reading Room open till 5:30 PM &bull; Closed on Sundays and gazetted holidays.
                        </span>
                    </div>
                </div>

                <!-- Library Advisory Committee -->
                <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">Library Advisory Committee (2026-2027)</h2>
                        <span class="mvc-util-badge">Governance Body</span>
                    </div>

                    <div class="mvc-util-table-wrap">
                        <table class="mvc-util-table">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Sr. No.</th>
                                    <th>Committee Member</th>
                                    <th>Designation / Department</th>
                                    <!-- <th>Role in Committee</th> -->
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Sr. No.">1</td>
                                    <td data-label="Member"><strong>Prof. (Dr.) Krishna Kant Singh</strong></td>
                                    <td data-label="Designation">Principal, M.V. College, Buxar</td>
                                    <!-- <td data-label="Role"><span class="mvc-util-badge" style="background:#ecfdf5; color:#065f46;">Chairman</span></td> -->
                                    <td data-label="Contact">principalmvcbuxar@gmail.com</td>
                                </tr>
                                <tr>
                                    <td data-label="Sr. No.">2</td>
                                    <td data-label="Member"><strong>Dr. Yogershi Rajpoot</strong></td>
                                    <td data-label="Designation">Assistant Professor (Pol. Science)</td>
                                    <!-- <td data-label="Role">Convener</td> -->
                                    <td data-label="Contact">yogirshi@gmail.com</td>
                                </tr>
                                <tr>
                                    <td data-label="Sr. No.">3</td>
                                    <td data-label="Member"><strong>Senior Faculty Nominees</strong></td>
                                    <td data-label="Designation">Arts, Science & Commerce Heads</td>
                                    <!-- <td data-label="Role">Members</td> -->
                                    <td data-label="Contact"><?php echo esc_html(mvc_get_global_setting('library_email', 'mvcollegebuxar@gmail.com')); ?></td>
                                </tr>
                                <tr>
                                    <td data-label="Sr. No.">4</td>
                                    <td data-label="Member"><strong>Library In-Charge / Assistant</strong></td>
                                    <td data-label="Designation">Library Staff</td>
                                    <!-- <td data-label="Role">Member Secretary</td> -->
                                    <td data-label="Contact">mvcollegebuxar@gmail.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php
}

/**
 * 2. E-RESOURCES PAGE RENDERER (/e-resources/)
 */
function mvc_render_e_resources_page() {
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 295;
    $hero_title = mvc_get_page_meta($post_id, 'e_resources_hero_title', 'Institutional Digital e-Resources & Academic Repositories');
    $hero_subtitle = mvc_get_page_meta($post_id, 'e_resources_hero_subtitle', 'डिजिटल ई-संसाधन एवं राष्ट्रीय ज्ञान भंडार');
    $hero_desc = mvc_get_page_meta($post_id, 'e_resources_hero_desc', 'Direct access to official Ministry of Education, UGC, and INFLIBNET digital repositories, peer-reviewed journals, doctoral theses, and open-access courseware for M.V. College faculty and scholars.');
    
    $default_resources = [
        [
            'title'       => 'SWAYAM Online Courses',
            'desc'        => 'Study Webs of Active-Learning for Young Aspiring Minds. 2,000+ interactive courses with college credit transfer by Ministry of Education, Govt. of India.',
            'sponsor'     => 'Ministry of Education (MoE), GoI',
            'url'         => 'https://swayam.gov.in/',
            'badge'       => 'Credit Transfer MOOCs',
            'icon'        => '🎓'
        ],
        [
            'title'       => 'e-PG Pathshala',
            'desc'        => 'Curriculum-based, interactive e-content for 70+ postgraduate subjects across Humanities, Social Sciences, Natural Sciences, and Languages.',
            'sponsor'     => 'UGC & INFLIBNET Centre',
            'url'         => 'https://epgp.inflibnet.ac.in/',
            'badge'       => 'PG Digital Curricula',
            'icon'        => '📚'
        ],
        [
            'title'       => 'e-ShodhSindhu',
            'desc'        => 'Consortium for Higher Education Electronic Resources offering current and archival access to 10,000+ peer-reviewed e-journals and citation databases.',
            'sponsor'     => 'MoE & INFLIBNET',
            'url'         => 'https://ess.inflibnet.ac.in/index.php',
            'badge'       => 'Scholarly e-Journals',
            'icon'        => '🔬'
        ],
        [
            'title'       => 'Shodhganga (Indian Theses Repository)',
            'desc'        => 'National digital repository of 5,00,000+ full-text Ph.D. theses and research dissertations submitted to accredited Indian universities.',
            'sponsor'     => 'INFLIBNET Centre',
            'url'         => 'https://shodhganga.inflibnet.ac.in/',
            'badge'       => 'Doctoral Theses',
            'icon'        => '📜'
        ],
        [
            'title'       => 'Shodh Shuddhi (Plagiarism Detection)',
            'desc'        => 'Academic integrity software service providing access to Turnitin/Ouriginal to facilitate authentic, plagiarism-free research publications.',
            'sponsor'     => 'INFLIBNET Centre / UGC',
            'url'         => 'https://shodhshuddhi.inflibnet.ac.in/',
            'badge'       => 'Research Integrity',
            'icon'        => '🛡️'
        ],
        [
            'title'       => 'Vidwan Database',
            'desc'        => 'Premier database of profiles of scientists, researchers, and faculty members in top academic and research institutions across India.',
            'sponsor'     => 'INFLIBNET Centre',
            'url'         => 'https://vidwan.inflibnet.ac.in/',
            'badge'       => 'Faculty Profiles',
            'icon'        => '👨‍🏫'
        ],
        [
            'title'       => 'Saksham (UGC Portal)',
            'desc'        => 'Centralized digital portal for gender sensitization, women empowerment, safety, and grievance monitoring on college campuses.',
            'sponsor'     => 'University Grants Commission (UGC)',
            'url'         => 'https://saksham.ugc.ac.in/Home',
            'badge'       => 'Women Safety & Equality',
            'icon'        => '⚖️'
        ],
        [
            'title'       => 'National Digital Library of India (NDLI)',
            'desc'        => 'Multilingual virtual repository containing 7+ crore educational assets including textbooks, articles, audios, and video lectures developed by IIT Kharagpur.',
            'sponsor'     => 'Ministry of Education & IIT KGP',
            'url'         => 'https://ndl.iitkgp.ac.in/',
            'badge'       => '7+ Crore Digital Assets',
            'icon'        => '🌐'
        ],
        [
            'title'       => 'e-Adhyayan (e-Books for PG)',
            'desc'        => '700+ curated electronic books mapped directly to postgraduate university curricula under the e-PG Pathshala digital initiative.',
            'sponsor'     => 'INFLIBNET Centre',
            'url'         => 'https://ebooks.inflibnet.ac.in/eadhyayan/',
            'badge'       => '700+ PG e-Books',
            'icon'        => '📖'
        ],
        [
            'title'       => 'INFLIBNET N-LIST Consortium',
            'desc'        => 'College consortium access to 6,000+ electronic journals and 1,99,500+ electronic books for degree colleges.',
            'sponsor'     => 'UGC-INFONET',
            'url'         => 'https://nlist.inflibnet.ac.in/',
            'badge'       => 'Degree College Portal',
            'icon'        => '🏛️'
        ],
        [
            'title'       => 'Coursera for Campus',
            'desc'        => 'World-class online certifications in software technology, data analysis, business management, and professional skill enhancement.',
            'sponsor'     => 'Global Academic Partner',
            'url'         => 'https://www.coursera.org/',
            'badge'       => 'Skill Development',
            'icon'        => '💻'
        ]
    ];
    $resources = mvc_get_page_array($post_id, 'e_resources_list', $default_resources);

    $default_open_access = [
        [
            'title' => 'DOAJ (Directory of Open Access Journals) &rarr;',
            'desc'  => 'Over 19,000 peer-reviewed journals across all academic disciplines.',
            'url'   => 'https://doaj.org/',
            'color' => 'var(--mvc-navy)'
        ],
        [
            'title' => 'arXiv.org (Cornell University) &rarr;',
            'desc'  => '2+ million scholarly preprints in Physics, Mathematics, and Computer Science.',
            'url'   => 'https://arxiv.org/',
            'color' => 'var(--mvc-maroon)'
        ],
        [
            'title' => 'Project Gutenberg &rarr;',
            'desc'  => '70,000+ free digital eBooks of world classic literature and philosophy.',
            'url'   => 'https://www.gutenberg.org/',
            'color' => '#96720d'
        ],
        [
            'title' => 'MIT OpenCourseWare (OCW) &rarr;',
            'desc'  => 'Open digital publication of virtually all MIT course content and video lectures.',
            'url'   => 'https://ocw.mit.edu/',
            'color' => 'var(--mvc-navy)'
        ]
    ];
    $open_access = mvc_get_page_array($post_id, 'e_resources_open_access', $default_open_access);
    ?>
    <div class="mvc-util-root">
        <!-- Hero Banner -->
        <header class="mvc-util-hero">
            <div class="mvc-util-hero-inner">
                <nav class="mvc-util-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <span>e-Resources</span>
                </nav>
                <h1 class="mvc-util-hero-title"><?php echo esc_html($hero_title); ?></h1>

                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">🌐 11 National Gateways</span>
                    <span class="mvc-util-pill">📖 Open Access Repositories</span>
                    <span class="mvc-util-pill">🎓 24×7 Off-Campus Access</span>
                </div>
            </div>
        </header>

        <main class="mvc-util-main">
            <div class="mvc-util-container">
                <div class="mvc-util-section-header">
                    <h2 class="mvc-util-section-title">Government & Statutory Digital Learning Repositories</h2>
                    <span class="mvc-util-badge">National Portals</span>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:24px; margin-bottom:48px;">
                    <?php foreach ($resources as $res): ?>
                        <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:12px; padding:26px; box-shadow:0 4px 14px rgba(0,33,71,0.04); display:flex; flex-direction:column; justify-content:space-between; transition:all 0.25s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.borderColor='var(--mvc-navy)'; this.style.boxShadow='0 10px 24px rgba(0,33,71,0.08)';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--mvc-border)'; this.style.boxShadow='0 4px 14px rgba(0,33,71,0.04)';">
                            <div>
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
                                    <span style="font-size:32px;"><?php echo esc_html($res['icon']); ?></span>
                                    <!-- <span class="mvc-util-badge"><?php echo esc_html($res['badge']); ?></span> -->
                                </div>
                                <h3 style="font-size:18px; font-weight:800; color:var(--mvc-navy); margin:0 0 6px;">
                                    <?php echo esc_html($res['title']); ?>
                                </h3>
                                <div style="font-size:12.5px; font-weight:700; color:var(--mvc-maroon); margin-bottom:12px;">
                                    <?php echo esc_html($res['sponsor']); ?>
                                </div>
                                <p style="font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6; margin:0 0 20px;">
                                    <?php echo esc_html($res['desc']); ?>
                                </p>
                            </div>
                            <div style="border-top:1px solid var(--mvc-border); padding-top:16px;">
                                <a href="<?php echo esc_url($res['url']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-navy" style="width:100%;">
                                    <span>Launch Digital Portal</span>
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Open Access Repositories Directory -->
                <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">Discipline-Wise Open Access Research Networks</h2>
                        <span class="mvc-util-badge">Global Open Repositories</span>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px;">
                        <?php foreach ($open_access as $oa): ?>
                            <a href="<?php echo esc_url($oa['url']); ?>" target="_blank" rel="noopener" style="text-decoration:none; background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid <?php echo esc_attr($oa['color']); ?>; display:block; transition:all 0.2s;" onmouseover="this.style.background='#FFFFFF'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.06)'" onmouseout="this.style.background='var(--mvc-bg-alt)'; this.style.boxShadow='none'">
                                <h4 style="margin:0 0 6px; color:<?php echo esc_attr($oa['color']); ?>; font-size:16px;"><?php echo esc_html($oa['title']); ?></h4>
                                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light); line-height:1.5;"><?php echo esc_html($oa['desc']); ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php
}

/**
 * 3. ANTI-RAGGING CELL PAGE RENDERER (/anti-ragging-cell/)
 */
function mvc_render_anti_ragging_page() {
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 296;
    $hero_title = mvc_get_page_meta($post_id, 'anti_ragging_hero_title', 'Statutory Anti-Ragging Cell & Zero Tolerance Monitoring');
    $hero_subtitle = mvc_get_page_meta($post_id, 'anti_ragging_hero_subtitle', 'रैगिंग निरोधक प्रकोष्ठ एवं वैधानिक सतर्कता समिति');
    $hero_desc = mvc_get_page_meta($post_id, 'anti_ragging_hero_desc', 'Maharshi Vishwamitra Mahavidyalaya enforces a strict Zero Tolerance Policy against any form of ragging or harassment, in full compliance with University Grants Commission (UGC) Regulations, 2009 and the Supreme Court of India directives.');
    $helpline = mvc_get_page_meta($post_id, 'anti_ragging_helpline', '1800-180-5522');
    $portal_url = mvc_get_page_meta($post_id, 'anti_ragging_portal_url', 'https://www.antiragging.in/');
    
    $convener_name = mvc_get_page_meta($post_id, 'anti_ragging_convener_name', 'Dr. Ras Bihari Sharma');
    $convener_role = mvc_get_page_meta($post_id, 'anti_ragging_convener_role', 'Convener & Member, Anti-Ragging Cell & Vigilance Squad');
    $convener_email = mvc_get_page_meta($post_id, 'anti_ragging_convener_email', mvc_get_global_setting('antiragging_email', 'mvcollegebuxar@gmail.com'));
    $convener_chamber = mvc_get_page_meta($post_id, 'anti_ragging_convener_chamber', 'Anti-Ragging Cell, Ground Floor');
    $convener_dept = mvc_get_page_meta($post_id, 'anti_ragging_convener_dept', 'Department of History, Maharshi Vishwamitra Mahavidyalaya, Buxar');

    $default_committee = [
        ['member' => 'Prof. (Dr.) Krishna Kant Singh', 'capacity' => 'Principal, M.V. College, Buxar', 'role' => 'Chairman', 'helpline' => '06183-796317'],
        ['member' => 'Dr. Ras Bihari Sharma', 'capacity' => 'Assistant Professor, History Department', 'role' => 'Convener', 'helpline' => $convener_email],
        ['member' => 'Nominee of District Administration', 'capacity' => 'Sub-Divisional Magistrate (SDM), Buxar', 'role' => 'Civil Authority', 'helpline' => 'sdm-buxar-bih@nic.in'],
        ['member' => 'Station House Officer (SHO)', 'capacity' => 'Town Police Station, Buxar', 'role' => 'Police Liaison', 'helpline' => 'sho-buxar-bih@nic.in'],
        ['member' => 'Senior Faculty Nominees', 'capacity' => 'Proctorial Board & Women Cell', 'role' => 'Vigilance Squad', 'helpline' => mvc_get_global_setting('proctor_email', 'mvcollegebuxar@gmail.com')],
        ['member' => 'Elected / Nominated Student Representatives', 'capacity' => 'Meritorious Fresher & Senior Scholars', 'role' => 'Student Monitors', 'helpline' => mvc_get_global_setting('studentrep_email', mvc_get_principal_email())]
    ];
    $committee = mvc_get_page_array($post_id, 'anti_ragging_committee', $default_committee);
    ?>
    <div class="mvc-util-root">
        <!-- Hero Banner -->
        <header class="mvc-util-hero" style="background: linear-gradient(135deg, #3d0000 0%, var(--mvc-maroon) 50%, var(--mvc-navy) 100%);">
            <div class="mvc-util-hero-inner">
                <nav class="mvc-util-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <span>Anti-Ragging Cell</span>
                </nav>
                <h1 class="mvc-util-hero-title"><?php echo esc_html($hero_title); ?></h1>

                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">⚖️ UGC Regulations 2009 Mandate</span>
                    <span class="mvc-util-pill">🚫 Zero Tolerance Campus</span>
                    <span class="mvc-util-pill">🔒 100% Confidential Reporting</span>
                </div>
            </div>
        </header>

        <main class="mvc-util-main">
            <div class="mvc-util-container">
                <!-- 24x7 Emergency Helplines Box -->
                <div style="background: #FFF1F2; border: 2px solid #FDA4AF; border-radius: 14px; padding: 26px 30px; margin-bottom: 36px; box-shadow: 0 4px 18px rgba(225, 29, 72, 0.08);">
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
                        <div>
                            <div style="display:inline-flex; align-items:center; gap:6px; background:#E11D48; color:#FFFFFF; font-size:11.5px; font-weight:800; padding:3px 10px; border-radius:20px; text-transform:uppercase; margin-bottom:8px;">
                                🚨 24×7 Emergency Hotlines
                            </div>
                            <h3 style="color:#881337; margin:0 0 6px; font-size:22px; font-weight:800;">Need Immediate Assistance or Facing Ragging?</h3>
                            <p style="color:#9F1239; margin:0; font-size:14px;">National UGC Toll-Free Hotline & College Emergency Contacts are active 24 hours a day, 365 days a year.</p>
                        </div>
                        <div style="display:flex; gap:12px; flex-wrap:wrap;">
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $helpline)); ?>" class="mvc-btn" style="background:#E11D48; color:#FFFFFF;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span>Toll-Free: <?php echo esc_html($helpline); ?></span>
                            </a>
                            <a href="<?php echo esc_url($portal_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-navy">
                                <span>UGC Affidavit Portal &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- In-Charge Profile Card -->
                <div class="mvc-incharge-card">
                    <img src="<?php echo esc_url(home_url('/wp-content/uploads/faculty-images/Profile_1764235604.png')); ?>" alt="<?php echo esc_attr($convener_name); ?>" class="mvc-incharge-avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($convener_name); ?>&background=800000&color=fff';" />
                    <div class="mvc-incharge-info">
                        <h3><?php echo esc_html($convener_name); ?></h3>
                        <div class="mvc-role"><?php echo esc_html($convener_role); ?></div>
                        <p>
                            <?php echo esc_html($convener_dept); ?><br />
                            <strong>Helpline Email:</strong> <?php echo esc_html($convener_email); ?> &bull; <strong>Chamber:</strong> <?php echo esc_html($convener_chamber); ?>
                        </p>
                    </div>
                </div>

                <!-- Policy Overview & Definitions -->
                <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">Institutional Anti-Ragging Policy & Mandate</h2>
                        <span class="mvc-util-badge">Statutory Framework</span>
                    </div>

                    <p style="font-size:15px; color:var(--mvc-slate); line-height:1.7; margin-bottom:20px;">
                        The Anti-Ragging Cell is dedicated to ensuring a secure, respectful, and inclusive environment for all students within the College. The Cell works actively to prevent any form of ragging and to promote a culture of mutual trust, cooperation, and dignity among students.
                    </p>
                    <p style="font-size:15px; color:var(--mvc-slate); line-height:1.7; margin-bottom:28px;">
                        To create awareness and prevent ragging-related incidents, the Cell organizes orientation programmes, awareness campaigns, and interactive sessions that educate students about anti-ragging regulations and ethical conduct on campus. All complaints are handled with strict confidentiality, fairness, and prompt attention.
                    </p>

                    <h3 style="font-size:18px; font-weight:800; color:var(--mvc-navy); margin:0 0 16px;">What Constitutes Ragging under UGC Regulations?</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:18px; margin-bottom:32px;">
                        <div style="background:var(--mvc-bg-alt); padding:18px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                            <strong style="color:var(--mvc-maroon); display:block; margin-bottom:4px;">1. Verbal & Mental Abuse</strong>
                            <span style="font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">Taunting, shouting, using derogatory nicknames, or embarrassing freshers based on dialect or appearance.</span>
                        </div>
                        <div style="background:var(--mvc-bg-alt); padding:18px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                            <strong style="color:var(--mvc-maroon); display:block; margin-bottom:4px;">2. Physical Assault & Coercion</strong>
                            <span style="font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">Physical force, confinement, compelling students to perform menial tasks, or restricting their free movement.</span>
                        </div>
                        <div style="background:var(--mvc-bg-alt); padding:18px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                            <strong style="color:var(--mvc-maroon); display:block; margin-bottom:4px;">3. Financial Extortion</strong>
                            <span style="font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">Demanding unlawful funds, forcing freshers to pay for senior canteen meals, travel, or supplies.</span>
                        </div>
                        <div style="background:var(--mvc-bg-alt); padding:18px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                            <strong style="color:var(--mvc-maroon); display:block; margin-bottom:4px;">4. Cyber & Digital Harassment</strong>
                            <span style="font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">Intimidating messages, non-consensual photography, trolling on social media, or unauthorized circulation of student contacts.</span>
                        </div>
                    </div>

                    <h3 style="font-size:18px; font-weight:800; color:var(--mvc-navy); margin:0 0 16px;">Penal Sanctions & Punishments for Ragging Offenders</h3>
                    <div style="background:#FFF8F8; border:1px solid #FECDD3; border-radius:10px; padding:20px; font-size:14px; color:#881337; line-height:1.65;">
                        Depending on the severity of the offence, convicted individuals face:
                        <ul style="margin:8px 0 0 20px; padding:0;">
                            <li>Immediate suspension from attending academic classes and institutional privileges.</li>
                            <li>Withholding or withdrawing scholarship, fellowship, and fee concession benefits.</li>
                            <li>Debarring from appearing in University semester and internal CIA examinations.</li>
                            <li>Expulsion from the College and rustication for periods ranging from 1 to 4 semesters.</li>
                            <li>Immediate lodging of a First Information Report (FIR) with the Buxar Police Station under criminal provisions of IPC.</li>
                        </ul>
                    </div>
                </div>

                <!-- Statutory Anti-Ragging Committee -->
                <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">Statutory Anti-Ragging Committee (2026-2027)</h2>
                        <span class="mvc-util-badge">UGC Compliance Matrix</span>
                    </div>

                    <div class="mvc-util-table-wrap">
                        <table class="mvc-util-table">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Sr. No.</th>
                                    <th>Committee Member</th>
                                    <th>Institutional Capacity / Representation</th>
                                    <th>Role in Cell</th>
                                    <th>Official Helpline</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($committee as $idx => $cm): ?>
                                    <tr>
                                        <td data-label="Sr. No."><?php echo esc_html($idx + 1); ?></td>
                                        <td data-label="Member"><strong><?php echo esc_html($cm['member']); ?></strong></td>
                                        <td data-label="Capacity"><?php echo esc_html($cm['capacity']); ?></td>
                                        <td data-label="Role">
                                            <?php if (!empty($cm['role']) && stripos($cm['role'], 'Chairman') !== false): ?>
                                                <span class="mvc-util-badge" style="background:#ecfdf5; color:#065f46;"><?php echo esc_html($cm['role']); ?></span>
                                            <?php else: ?>
                                                <?php echo esc_html($cm['role']); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Helpline"><?php echo esc_html($cm['helpline']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Anti-Ragging Cell Banner Graphic -->
                <?php if (file_exists(ABSPATH . 'wp-content/uploads/anti_ragging_cell/anti-ragging1.png')): ?>
                    <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:14px; overflow:hidden; box-shadow:0 4px 18px rgba(0,33,71,0.05); margin-bottom:36px;">
                        <img src="<?php echo esc_url(home_url('/wp-content/uploads/anti_ragging_cell/anti-ragging1.png')); ?>" alt="Anti-Ragging Awareness Banner" style="width:100%; height:auto; max-height:480px; object-fit:cover; display:block;" />
                        <div style="padding:14px 20px; font-size:13px; color:var(--mvc-slate-light); background:#FAF9F6; border-top:1px solid var(--mvc-border); text-align:center;">
                            Maharshi Vishwamitra Mahavidyalaya Anti-Ragging Vigilance Notice & Mandatory Affidavit Notification
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <?php
}

/**
 * 4. TENDER PAGE RENDERER (/tender/)
 */
function mvc_render_tender_page() {
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 293;
    $hero_title = mvc_get_page_meta($post_id, 'tender_hero_title', 'Official Public Tenders, Quotations & E-Procurement');
    $hero_subtitle = mvc_get_page_meta($post_id, 'tender_hero_subtitle', 'सार्वजनिक निविदा, कोटेशन एवं ई-प्रोक्योरमेंट पोर्टल');
    $hero_desc = mvc_get_page_meta($post_id, 'tender_hero_desc', 'Official tender notices, procurement schedules, technical specifications, and bidding documentation published by the Purchase & Finance Committee of Maharshi Vishwamitra Mahavidyalaya, Buxar.');

    $default_tenders = [
        [
            'id'          => 'tender-1789373092',
            'title'       => 'Tender Notice Publication For Website Development',
            'date'        => 'Sep 14, 2026',
            'ref'         => 'MVC/TEND/WEB/2026-01',
            'file_name'   => 'Tender_1789373092.pdf',
            'file_size'   => '117 KB',
            'category'    => 'ICT Infrastructure & Web Modernization',
            'status'      => 'Archived / Implemented'
        ],
        [
            'id'          => 'tender-1778740080',
            'title'       => 'Tender Notice Publication For Conference Hall Refurbishment',
            'date'        => 'May 14, 2026',
            'ref'         => 'MVC/TEND/CIVIL/2026-04',
            'file_name'   => 'Tender_1778740080.pdf',
            'file_size'   => '97 KB',
            'category'    => 'Civil Works & Acoustic Interior',
            'status'      => 'Completed'
        ],
        [
            'id'          => 'tender-1778740147',
            'title'       => 'Tender Notice Publication For Automated Smart I.D. Card System',
            'date'        => 'May 14, 2026',
            'ref'         => 'MVC/TEND/ID/2026-03',
            'file_name'   => 'Tender_1778740147.pdf',
            'file_size'   => '74 KB',
            'category'    => 'Student Automation & Identity Cards',
            'status'      => 'Completed'
        ],
        [
            'id'          => 'tender-1775723074',
            'title'       => 'Tender Notice Publication For Soundless Heavy Generator',
            'date'        => 'Apr 09, 2026',
            'ref'         => 'MVC/TEND/ELEC/2026-02',
            'file_name'   => 'Tender_1775723074.pdf',
            'file_size'   => '83 KB',
            'category'    => 'Campus Power Backup & Genset',
            'status'      => 'Installed'
        ],
        [
            'id'          => 'tender-1775723113',
            'title'       => 'Tender Notice Publication For Heavy Duty Photo state Machine',
            'date'        => 'Apr 09, 2026',
            'ref'         => 'MVC/TEND/EXAM/2026-01',
            'file_name'   => 'Tender_1775723113.pdf',
            'file_size'   => '83 KB',
            'category'    => 'Examination Equipment & Photocopier',
            'status'      => 'Installed'
        ],
        [
            'id'          => 'tender-1768460142',
            'title'       => 'Tender Notice For Installation of Steel Made Name Plate of M.V. College',
            'date'        => 'Jan 15, 2026',
            'ref'         => 'MVC/TEND/SIGN/2026-01',
            'file_name'   => 'Tender_1768460142.pdf',
            'file_size'   => '77 KB',
            'category'    => 'Institutional Branding & Campus Signage',
            'status'      => 'Completed'
        ]
    ];
    $tenders = mvc_get_page_array($post_id, 'tenders_list', $default_tenders);
    ?>
    <div class="mvc-util-root">
        <!-- Hero Banner -->
        <header class="mvc-util-hero">
            <div class="mvc-util-hero-inner">
                <nav class="mvc-util-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <span>Tenders & Quotations</span>
                </nav>
                <h1 class="mvc-util-hero-title"><?php echo esc_html($hero_title); ?></h1>

                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">📑 Public Procurement Transparency</span>
                    <span class="mvc-util-pill">📥 Direct PDF Downloads</span>
                    <span class="mvc-util-pill">👁 In-Page Dossier Viewer</span>
                </div>
            </div>
        </header>

        <main class="mvc-util-main">
            <div class="mvc-util-container">
                <!-- Tenders Directory Card -->
                <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">Official Public Tenders Directory</h2>
                        <span class="mvc-util-badge">Authentic Institutional Gazettes</span>
                    </div>
                    <p style="font-size:14.5px; color:var(--mvc-slate-light); line-height:1.6; margin-bottom:24px;">
                        Interested vendors, contractors, and service providers can download complete technical specifications, terms of contract, and submission envelopes below. Every tender provides both direct PDF download and instant in-page dossier review:
                    </p>

                    <div class="mvc-util-table-wrap">
                        <table class="mvc-util-table">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Sr. No.</th>
                                    <th>Publication Date</th>
                                    <th>Tender Notice Title & Category</th>
                                    <th>Reference Memo</th>
                                    <th>Procurement Status</th>
                                    <th style="text-align:right;">Official Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tenders as $idx => $t): 
                                    $local_pdf_url = home_url('/wp-content/uploads/tender/' . $t['file_name']);
                                ?>
                                    <tr>
                                        <td data-label="Sr. No."><strong><?php echo esc_html($idx + 1); ?></strong></td>
                                        <td data-label="Published Date">
                                            <span style="font-weight:700; color:var(--mvc-navy);"><?php echo esc_html($t['date']); ?></span>
                                        </td>
                                        <td data-label="Title & Scope">
                                            <strong style="color:var(--mvc-navy); font-size:14.5px; display:block; margin-bottom:3px;">
                                                <?php echo esc_html($t['title']); ?>
                                            </strong>
                                            <span style="font-size:12px; color:var(--mvc-slate-light); background:var(--mvc-bg-alt); padding:2px 8px; border-radius:4px; border:1px solid var(--mvc-border);">
                                                <?php echo esc_html($t['category']); ?>
                                            </span>
                                        </td>
                                        <td data-label="Reference Memo">
                                            <code style="font-size:12px; background:#F1F5F9; padding:3px 6px; border-radius:4px; color:var(--mvc-slate);"><?php echo esc_html($t['ref']); ?></code>
                                        </td>
                                        <td data-label="Status">
                                            <span class="mvc-util-badge" style="background:#ecfdf5; color:#065f46; font-size:11px;">
                                                <?php echo esc_html($t['status']); ?>
                                            </span>
                                        </td>
                                        <td data-label="Actions" style="text-align:right;">
                                            <div style="display:inline-flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                                                <a href="<?php echo esc_url($local_pdf_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-navy" style="padding:6px 12px; font-size:12px;" title="Download Official Tender PDF (<?php echo esc_attr($t['file_size']); ?>)">
                                                    <span>Download PDF (<?php echo esc_html($t['file_size']); ?>)</span>
                                                </a>
                                                <button type="button" class="mvc-btn mvc-btn-outline" style="padding:6px 12px; font-size:12px;" onclick="mvcOpenTenderModal('<?php echo esc_js($t['title']); ?>', '<?php echo esc_url($local_pdf_url); ?>')">
                                                    <span>Details & Dossier &rarr;</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
                    <nav class="mvc-pagination-bar" id="mvcTenderPagination" aria-label="Tenders pagination" style="display:none;">
                        <div class="mvc-pagination-info">
                            Showing <strong id="mvcTenderPagStart">1</strong>–<strong id="mvcTenderPagEnd">10</strong> of <strong id="mvcTenderPagTotal">0</strong> Public Tenders
                        </div>
                        <div class="mvc-pagination-controls" id="mvcTenderPagControls"></div>
                    </nav>
                </div>
            </div>
        </main>

        <!-- Interactive Dossier Viewer Modal -->
        <div id="mvcTenderModal" class="mvc-dossier-overlay" onclick="if(event.target===this) mvcCloseTenderModal();">
            <div class="mvc-dossier-dialog">
                <div class="mvc-dossier-header">
                    <h3 id="mvcTenderModalTitle">Tender Notice Dossier Viewer</h3>
                    <button type="button" class="mvc-dossier-close" onclick="mvcCloseTenderModal();" aria-label="Close Modal">&times;</button>
                </div>
                <iframe id="mvcTenderFrame" class="mvc-dossier-frame" src="about:blank" title="Tender Document Viewer"></iframe>
            </div>
        </div>

        <script>
            function mvcOpenTenderModal(title, url) {
                var modal = document.getElementById('mvcTenderModal');
                var frame = document.getElementById('mvcTenderFrame');
                var titleEl = document.getElementById('mvcTenderModalTitle');
                if (!modal || !frame) return;

                titleEl.textContent = title;
                frame.src = url;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function mvcCloseTenderModal() {
                var modal = document.getElementById('mvcTenderModal');
                var frame = document.getElementById('mvcTenderFrame');
                if (!modal || !frame) return;

                modal.style.display = 'none';
                frame.src = 'about:blank';
                document.body.style.overflow = 'auto';
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    mvcCloseTenderModal();
                }
            });

            // Client-Side Table Pagination Engine (10/10 Standard)
            (function() {
                var pageSize = 10;
                var currentPage = 1;
                var rows = Array.from(document.querySelectorAll('.mvc-util-table tbody tr'));
                if (rows.length === 0) return;

                function renderTendersPage() {
                    var totalMatches = rows.length;
                    var totalPages = Math.ceil(totalMatches / pageSize) || 1;
                    if (currentPage > totalPages) currentPage = 1;
                    if (currentPage < 1) currentPage = 1;

                    var startIndex = (currentPage - 1) * pageSize;
                    var endIndex = Math.min(startIndex + pageSize, totalMatches);

                    rows.forEach(function(r) { r.style.display = 'none'; });
                    for (var i = startIndex; i < endIndex; i++) {
                        if (rows[i]) rows[i].style.display = '';
                    }

                    var pagBar = document.getElementById('mvcTenderPagination');
                    var pagStart = document.getElementById('mvcTenderPagStart');
                    var pagEnd = document.getElementById('mvcTenderPagEnd');
                    var pagTotal = document.getElementById('mvcTenderPagTotal');
                    var pagControls = document.getElementById('mvcTenderPagControls');

                    if (pagBar) {
                        if (totalMatches <= pageSize) {
                            pagBar.style.display = 'none';
                        } else {
                            pagBar.style.display = 'flex';
                            if (pagStart) pagStart.textContent = (totalMatches > 0) ? (startIndex + 1) : 0;
                            if (pagEnd) pagEnd.textContent = endIndex;
                            if (pagTotal) pagTotal.textContent = totalMatches;

                            if (pagControls) {
                                var html = '';
                                html += '<button type="button" class="mvc-pag-btn mvc-pag-prev" ' + (currentPage === 1 ? 'disabled' : '') + ' data-page="' + (currentPage - 1) + '" aria-label="Previous Page">&#9664; Prev</button>';

                                for (var p = 1; p <= totalPages; p++) {
                                    if (totalPages <= 7 || p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                                        html += '<button type="button" class="mvc-pag-num ' + (p === currentPage ? 'is-active' : '') + '" data-page="' + p + '">' + p + '</button>';
                                    } else if (p === 2 && currentPage > 3) {
                                        html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                        p = currentPage - 2;
                                    } else if (p === currentPage + 2 && p < totalPages) {
                                        html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                        p = totalPages - 1;
                                    }
                                }

                                html += '<button type="button" class="mvc-pag-btn mvc-pag-next" ' + (currentPage === totalPages ? 'disabled' : '') + ' data-page="' + (currentPage + 1) + '" aria-label="Next Page">Next &#9654;</button>';
                                pagControls.innerHTML = html;

                                pagControls.querySelectorAll('button[data-page]').forEach(function(btn) {
                                    btn.addEventListener('click', function() {
                                        var targetPage = parseInt(this.getAttribute('data-page'), 10);
                                        if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages && targetPage !== currentPage) {
                                            currentPage = targetPage;
                                            renderTendersPage();
                                            var tableWrap = document.querySelector('.mvc-util-table-wrap');
                                            if (tableWrap) {
                                                var topPos = tableWrap.getBoundingClientRect().top + window.pageYOffset - 90;
                                                window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                                            }
                                        }
                                    });
                                });
                            }
                        }
                    }
                }

                renderTendersPage();
            })();
        </script>
    </div>
    <?php
}

/**
 * 5. HELP DESK PAGE RENDERER (/help-desk/)
 * Redesigned Minimal & Modern Institutional Single-Window Support Suite
 */
function mvc_render_help_desk_page($override_form_id = null) {
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 850;
    $hero_title = 'Central Institutional Help Desk';
    $hotline_phone = mvc_get_global_setting('contact_phone', '06183-796317');
    $secretariat_email = mvc_get_global_setting('principal_email', 'principalmvcbuxar@gmail.com');

    $desks = [
        [
            'id'       => 'admissions',
            'cat'      => 'academic',
            'cat_name' => 'Academic & Admissions',
            'wing'     => 'Central Admissions & Enrolment Cell',
            'scope'    => 'Four-Year Undergraduate (FYUGP NEP) & PG admission counseling, subject combination verification, and quota allocations.',
            'incharge' => 'Convener, Admission Committee',
            'phone'    => '06183-222035',
            'email'    => mvc_get_global_setting('admission_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Room 102, Academic Wing',
            'link'     => '/admission-procedure/',
            'icon'     => '🎓'
        ],
        [
            'id'       => 'examination',
            'cat'      => 'academic',
            'cat_name' => 'Academic & Admissions',
            'wing'     => 'University Examination & Tabulation Wing',
            'scope'    => 'VKSU semester exams, Continuous Internal Assessment (CIA), admit card resolution, and provisional marksheet tabulation.',
            'incharge' => 'Controller of Examinations',
            'phone'    => '06183-796317',
            'email'    => mvc_get_global_setting('examination_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Room 105, Confidential Exam Wing',
            'link'     => '/result/',
            'icon'     => '📝'
        ],
        [
            'id'       => 'library',
            'cat'      => 'academic',
            'cat_name' => 'Academic & Admissions',
            'wing'     => 'Central Library & e-Learning Help Desk',
            'scope'    => 'AutoLib library cards, digital catalog OPAC, book renewals, INFLIBNET N-LIST proxy access, and reading room support.',
            'incharge' => 'Dr. Yogershi Rajpoot (Convener)',
            'phone'    => '06183-222035',
            'email'    => mvc_get_global_setting('library_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Central Library Stacks Desk',
            'link'     => '/library/',
            'icon'     => '📖'
        ],
        [
            'id'       => 'scholarships',
            'cat'      => 'welfare',
            'cat_name' => 'Finance & Welfare',
            'wing'     => 'Scholarships & Accounts Counter',
            'scope'    => 'National Scholarship Portal (NSP), Bihar Post-Matric Scholarship (PMS), Medhasoft disbursement, fee receipts, and refunds.',
            'incharge' => 'Accounts Officer & Bursar',
            'phone'    => '06183-222035',
            'email'    => mvc_get_global_setting('accounts_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Counters 1 to 4, Main Office Hall',
            'link'     => '/contact-us/',
            'icon'     => '💳'
        ],
        [
            'id'       => 'grievance',
            'cat'      => 'welfare',
            'cat_name' => 'Finance & Welfare',
            'wing'     => 'Statutory Student Grievance & Ombudsman',
            'scope'    => 'UGC-compliant confidential grievance resolution for admissions, continuous evaluations, student amenities, and campus discipline.',
            'incharge' => 'Dr. Saikat Debnath (Convener)',
            'phone'    => '06183-796317',
            'email'    => mvc_get_global_setting('grievance_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Grievance Cell Chamber',
            'link'     => '/student-grievance/',
            'icon'     => '⚖️'
        ],
        [
            'id'       => 'antiragging',
            'cat'      => 'welfare',
            'cat_name' => 'Finance & Welfare',
            'wing'     => 'Anti-Ragging Vigilance Cell',
            'scope'    => 'Zero-tolerance student protection against ragging, harassment, or intimidation; mandatory anti-ragging affidavit submission.',
            'incharge' => 'Dr. Ras Bihari Sharma (Convener)',
            'phone'    => '1800-180-5522',
            'email'    => mvc_get_global_setting('antiragging_email', 'antiraggingmvcbuxar@gmail.com'),
            'chamber'  => 'Anti-Ragging Cell Room',
            'link'     => '/anti-ragging-cell/',
            'icon'     => '🚨'
        ],
        [
            'id'       => 'principal',
            'cat'      => 'governance',
            'cat_name' => 'Governance & RTI',
            'wing'     => 'Office of the Principal & Central Secretariat',
            'scope'    => 'Executive collegiate governance, bonafide certificates, official character verification, institutional clearances, and VC liaison.',
            'incharge' => 'Principal’s Secretariat Desk',
            'phone'    => '06183-796317',
            'email'    => $secretariat_email,
            'chamber'  => 'Ground Floor, Administrative Block',
            'link'     => '/principal-desk/',
            'icon'     => '🏛️'
        ],
        [
            'id'       => 'icc',
            'cat'      => 'governance',
            'cat_name' => 'Governance & RTI',
            'wing'     => 'Internal Complaints Committee (ICC & Women Desk)',
            'scope'    => 'Prevention of sexual harassment of women, gender equity assistance, Girls Common Room support, and female scholar safety.',
            'incharge' => 'Presiding Officer, ICC',
            'phone'    => '06183-796317',
            'email'    => mvc_get_global_setting('icc_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Girls Common Room Complex',
            'link'     => '/committees-cells/',
            'icon'     => '🛡️'
        ],
        [
            'id'       => 'rti',
            'cat'      => 'governance',
            'cat_name' => 'Governance & RTI',
            'wing'     => 'Public Information Office (Right to Information)',
            'scope'    => 'RTI Act 2005 statutory documentation requests, proactive disclosures, and public institutional records processing.',
            'incharge' => 'Public Information Officer (PIO)',
            'phone'    => '06183-222035',
            'email'    => mvc_get_global_setting('pio_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Administrative Secretariat',
            'link'     => '/contact-us/',
            'icon'     => '📄'
        ]
    ];
    ?>
    <style>
        /* ========================================================
           HELP DESK SUITE REDESIGN - SLEEK, MINIMAL & FUNCTIONAL
           ======================================================== */
        .mvc-hd-root {
            background: #F8FAFC;
            color: var(--mvc-navy);
            width: 100%;
            box-sizing: border-box;
            padding-bottom: 70px;
        }

        /* 1. HERO HEADER */
        .mvc-hd-hero {
            background: linear-gradient(135deg, #00152e 0%, #002147 65%, #082d5a 100%);
            color: #FFFFFF;
            padding: clamp(32px, 4vw, 50px) 20px clamp(40px, 5vw, 60px);
            border-bottom: 4px solid var(--mvc-gold);
            position: relative;
        }

        .mvc-hd-hero-inner {
            max-width: 1360px;
            margin: 0 auto;
        }

        .mvc-hd-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .mvc-hd-breadcrumb a {
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .mvc-hd-breadcrumb a:hover {
            color: var(--mvc-gold);
        }

        .mvc-hd-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 24px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        /* 4 KPI Metric Badges */
        .mvc-hd-kpis {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            max-width: 1000px;
        }

        .mvc-hd-kpi-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 12px 16px;
        }

        .mvc-hd-kpi-val {
            font-size: 15px;
            font-weight: 800;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .mvc-hd-kpi-label {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* 2. MAIN CONTAINER */
        .mvc-hd-container {
            max-width: 1360px;
            margin: -24px auto 0;
            padding: 0 20px;
            box-sizing: border-box;
            position: relative;
            z-index: 10;
        }

        /* EMERGENCY HOTLINE STRIP */
        .mvc-hd-hotline-bar {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-left: 6px solid #DC2626;
            border-radius: 14px;
            padding: 18px 24px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            box-shadow: 0 6px 20px rgba(0, 33, 71, 0.06);
        }

        .mvc-hd-hotline-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .mvc-hd-hotline-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #FEE2E2;
            color: #DC2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .mvc-hd-hotline-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 2px;
        }

        .mvc-hd-hotline-sub {
            font-size: 13px;
            color: #64748B;
            margin: 0;
        }

        .mvc-hd-hotline-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .mvc-hd-btn-hotline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #DC2626;
            color: #FFFFFF !important;
            font-size: 13px;
            font-weight: 700;
            padding: 9px 16px;
            border-radius: 8px;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .mvc-hd-btn-hotline:hover {
            background: #B91C1C;
            transform: translateY(-1px);
        }

        .mvc-hd-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--mvc-navy);
            color: #FFFFFF !important;
            font-size: 13px;
            font-weight: 700;
            padding: 9px 16px;
            border-radius: 8px;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .mvc-hd-btn-secondary:hover {
            background: #00152e;
            transform: translateY(-1px);
        }

        /* 3. TOOLBAR & FILTER CONTROLS */
        .mvc-hd-toolbar {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.03);
            flex-wrap: wrap;
        }

        .mvc-hd-search-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .mvc-hd-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
        }

        .mvc-hd-search-input {
            width: 100% !important;
            height: 44px !important;
            padding: 0 14px 0 40px !important;
            font-size: 14px !important;
            border: 1.5px solid var(--mvc-border) !important;
            border-radius: 10px !important;
            outline: none !important;
            box-sizing: border-box !important;
            background: #F8FAFC !important;
            color: var(--mvc-navy) !important;
            transition: all 0.2s ease !important;
        }

        .mvc-hd-search-input:focus {
            background: #FFFFFF !important;
            border-color: var(--mvc-navy) !important;
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1) !important;
        }

        /* Desktop Category Pills */
        .mvc-hd-pills {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .mvc-hd-pill-btn {
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            color: #475569;
            font-size: 12.5px;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            line-height: normal;
        }

        .mvc-hd-pill-btn:hover {
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
            background: #FFFFFF;
        }

        .mvc-hd-pill-btn.is-active {
            background: var(--mvc-navy);
            color: #FFFFFF !important;
            border-color: var(--mvc-navy);
        }

        /* Mobile Dropdown (Hidden on Desktop) */
        .mvc-hd-mobile-dropdown-wrap {
            display: none;
            width: 100%;
        }

        /* 4. 9 SERVICE CARDS GRID */
        .mvc-hd-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 20px;
            margin-bottom: 36px;
        }

        .mvc-hd-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 10px rgba(0, 33, 71, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s ease;
            box-sizing: border-box;
        }

        .mvc-hd-card:hover {
            border-color: var(--mvc-navy);
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 33, 71, 0.08);
        }

        .mvc-hd-card-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 12px;
        }

        .mvc-hd-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #EFF6FF;
            border: 1px solid #DBEAFE;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .mvc-hd-card-meta {
            flex: 1;
            min-width: 0;
        }

        .mvc-hd-badge-tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 7px;
            border-radius: 4px;
            background: #F1F5F9;
            color: #475569;
            margin-bottom: 4px;
        }

        .mvc-hd-card-title {
            font-size: 16.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            line-height: 1.3;
        }

        .mvc-hd-card-desc {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.55;
            margin: 0 0 16px;
        }

        .mvc-hd-card-info-box {
            background: #F8FAFC;
            border: 1px solid #F1F5F9;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 12.5px;
        }

        .mvc-hd-info-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            color: #334155;
        }

        .mvc-hd-info-row:last-child {
            margin-bottom: 0;
        }

        .mvc-hd-info-label {
            font-weight: 700;
            color: var(--mvc-navy);
            min-width: 90px;
            flex-shrink: 0;
        }

        .mvc-hd-card-footer {
            border-top: 1px solid #F1F5F9;
            padding-top: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .mvc-hd-btn-call {
            flex: 1;
            min-width: 100px;
            background: #EFF6FF;
            color: #1D4ED8 !important;
            border: 1px solid #BFDBFE;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .mvc-hd-btn-call:hover {
            background: #DBEAFE;
            color: #1E40AF !important;
        }

        .mvc-hd-btn-email {
            flex: 1;
            min-width: 100px;
            background: #FEF2F2;
            color: #991B1B !important;
            border: 1px solid #FECACA;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .mvc-hd-btn-email:hover {
            background: #FEE2E2;
            color: #7F1D1D !important;
        }

        .mvc-hd-btn-portal {
            width: 100%;
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            color: var(--mvc-navy) !important;
            font-size: 12.5px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .mvc-hd-btn-portal:hover {
            border-color: var(--mvc-navy);
            background: #F8FAFC;
        }

        /* 5. RESOLUTION ESCALATION MATRIX */
        .mvc-hd-matrix-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 36px;
            box-shadow: 0 2px 10px rgba(0, 33, 71, 0.03);
        }

        .mvc-hd-matrix-header {
            margin-bottom: 20px;
        }

        .mvc-hd-matrix-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-hd-matrix-sub {
            font-size: 13.5px;
            color: #64748B;
            margin: 0;
        }

        .mvc-hd-steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .mvc-hd-step-item {
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            border-radius: 10px;
            padding: 16px;
            position: relative;
        }

        .mvc-hd-step-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--mvc-navy);
            color: #FFFFFF;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .mvc-hd-step-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-hd-step-desc {
            font-size: 12.5px;
            color: #64748B;
            line-height: 1.5;
            margin: 0;
        }

        /* 6. BOTTOM GATEWAYS STRIP */
        .mvc-hd-gateways {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .mvc-hd-gw-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 16px;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mvc-hd-gw-card:hover {
            border-color: var(--mvc-navy);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 33, 71, 0.06);
        }

        .mvc-hd-gw-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin-bottom: 3px;
        }

        .mvc-hd-gw-sub {
            font-size: 12px;
            color: #64748B;
            margin: 0;
        }

        .mvc-hd-gw-arrow {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--mvc-maroon);
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* RESPONSIVE BREAKPOINTS */
        @media (max-width: 900px) {
            .mvc-hd-kpis {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-hd-steps-grid {
                grid-template-columns: 1fr;
            }
            .mvc-hd-gateways {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .mvc-hd-pills {
                display: none !important;
            }
            .mvc-hd-mobile-dropdown-wrap {
                display: block !important;
            }
            .mvc-hd-grid {
                grid-template-columns: 1fr;
            }
            .mvc-hd-hotline-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .mvc-hd-hotline-actions {
                width: 100%;
            }
            .mvc-hd-btn-hotline, .mvc-hd-btn-secondary {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 540px) {
            .mvc-hd-kpis {
                grid-template-columns: 1fr;
            }
            .mvc-hd-gateways {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="mvc-hd-root">
        <!-- 1. HERO HEADER -->
        <header class="mvc-hd-hero">
            <div class="mvc-hd-hero-inner">
                <nav class="mvc-hd-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url(home_url('/contact-us/')); ?>">Support</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">Help Desk</span>
                </nav>

                <h1><?php echo esc_html($hero_title); ?></h1>

                <!-- 4 KPI Metrics -->
                <div class="mvc-hd-kpis">
                    <div class="mvc-hd-kpi-card">
                        <div class="mvc-hd-kpi-val">🏛️ 09 Desks</div>
                        <div class="mvc-hd-kpi-label">Support Divisions</div>
                    </div>
                    <div class="mvc-hd-kpi-card">
                        <div class="mvc-hd-kpi-val">🕒 10 AM – 5 PM</div>
                        <div class="mvc-hd-kpi-label">Official Timings (Mon–Sat)</div>
                    </div>
                    <div class="mvc-hd-kpi-card">
                        <div class="mvc-hd-kpi-val">⚡ Single-Window</div>
                        <div class="mvc-hd-kpi-label">Rapid Redressal System</div>
                    </div>
                    <div class="mvc-hd-kpi-card">
                        <div class="mvc-hd-kpi-val">📞 <?php echo esc_html($hotline_phone); ?></div>
                        <div class="mvc-hd-kpi-label">Central Secretariat</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-hd-container">
            <!-- EMERGENCY HOTLINE STRIP -->
            <section class="mvc-hd-hotline-bar">
                <div class="mvc-hd-hotline-info">
                    <div class="mvc-hd-hotline-icon">🚨</div>
                    <div>
                        <div class="mvc-hd-hotline-title">Urgent Campus Assistance &amp; Anti-Ragging Hotline</div>
                        <p class="mvc-hd-hotline-sub">Immediate response from the Principal’s Secretariat &amp; 24×7 National Vigilance Council.</p>
                    </div>
                </div>
                <div class="mvc-hd-hotline-actions">
                    <a href="tel:18001805522" class="mvc-hd-btn-hotline">
                        <span>📞 1800-180-5522 (Toll-Free)</span>
                    </a>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $hotline_phone)); ?>" class="mvc-hd-btn-secondary">
                        <span>Call Campus: <?php echo esc_html($hotline_phone); ?></span>
                    </a>
                    <a href="mailto:<?php echo esc_attr($secretariat_email); ?>" class="mvc-hd-btn-secondary" style="background:var(--mvc-maroon);">
                        <span>Email Secretariat</span>
                    </a>
                </div>
            </section>

            <!-- 3. TOOLBAR (SEARCH + FILTER) -->
            <section class="mvc-hd-toolbar">
                <div class="mvc-hd-search-wrap">
                    <svg class="mvc-hd-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="mvcHdSearchInput" class="mvc-hd-search-input" placeholder="Search desks by title, in-charge, service, or room..." onkeyup="mvcFilterHelpDesk();">
                </div>

                <!-- Desktop Category Pills -->
                <div class="mvc-hd-pills">
                    <button type="button" class="mvc-hd-pill-btn is-active" data-cat="all" onclick="mvcSetHelpDeskCategory('all', this);">
                        <span>All Desks (9)</span>
                    </button>
                    <button type="button" class="mvc-hd-pill-btn" data-cat="academic" onclick="mvcSetHelpDeskCategory('academic', this);">
                        <span>🎓 Academic &amp; Admissions (3)</span>
                    </button>
                    <button type="button" class="mvc-hd-pill-btn" data-cat="welfare" onclick="mvcSetHelpDeskCategory('welfare', this);">
                        <span>💳 Finance &amp; Welfare (3)</span>
                    </button>
                    <button type="button" class="mvc-hd-pill-btn" data-cat="governance" onclick="mvcSetHelpDeskCategory('governance', this);">
                        <span>🏛️ Governance &amp; RTI (3)</span>
                    </button>
                </div>

                <!-- Mobile Custom Dropdown Selector -->
                <div class="mvc-hd-mobile-dropdown-wrap">
                    <div class="mvc-custom-dropdown" id="mvcHdMobileCatDropdown">
                        <button type="button" class="mvc-dropdown-trigger" onclick="mvcToggleDropdown('mvcHdMobileCatDropdown');" aria-haspopup="listbox" aria-expanded="false">
                            <span class="mvc-dropdown-current">
                                <span class="mvc-dropdown-icon">📂</span>
                                <span class="mvc-dropdown-label">All Desks</span>
                                <span class="mvc-dropdown-count">(9)</span>
                            </span>
                            <svg class="mvc-dropdown-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="mvc-dropdown-menu" role="listbox">
                            <div class="mvc-dropdown-item is-active" data-value="all" onclick="mvcSelectHdCategory('all', this);">
                                <div class="mvc-dd-item-left">
                                    <span class="mvc-dd-icon">📂</span>
                                    <span class="mvc-dd-name">All Support Desks</span>
                                </div>
                                <span class="mvc-dd-pill">9</span>
                            </div>
                            <div class="mvc-dropdown-item" data-value="academic" onclick="mvcSelectHdCategory('academic', this);">
                                <div class="mvc-dd-item-left">
                                    <span class="mvc-dd-icon">🎓</span>
                                    <span class="mvc-dd-name">Academic &amp; Admissions</span>
                                </div>
                                <span class="mvc-dd-pill">3</span>
                            </div>
                            <div class="mvc-dropdown-item" data-value="welfare" onclick="mvcSelectHdCategory('welfare', this);">
                                <div class="mvc-dd-item-left">
                                    <span class="mvc-dd-icon">💳</span>
                                    <span class="mvc-dd-name">Finance &amp; Welfare</span>
                                </div>
                                <span class="mvc-dd-pill">3</span>
                            </div>
                            <div class="mvc-dropdown-item" data-value="governance" onclick="mvcSelectHdCategory('governance', this);">
                                <div class="mvc-dd-item-left">
                                    <span class="mvc-dd-icon">🏛️</span>
                                    <span class="mvc-dd-name">Governance &amp; RTI</span>
                                </div>
                                <span class="mvc-dd-pill">3</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. 9 SERVICE CARDS GRID -->
            <section class="mvc-hd-grid" id="mvcHdCardsContainer">
                <?php foreach ($desks as $d): ?>
                    <article class="mvc-hd-card" data-cat="<?php echo esc_attr($d['cat']); ?>" data-keywords="<?php echo esc_attr(strtolower($d['wing'] . ' ' . $d['scope'] . ' ' . $d['incharge'] . ' ' . $d['chamber'] . ' ' . $d['cat_name'])); ?>">
                        <div>
                            <div class="mvc-hd-card-header">
                                <div class="mvc-hd-card-icon"><?php echo esc_html($d['icon']); ?></div>
                                <div class="mvc-hd-card-meta">
                                    <span class="mvc-hd-badge-tag"><?php echo esc_html($d['cat_name']); ?></span>
                                    <h2 class="mvc-hd-card-title"><?php echo esc_html($d['wing']); ?></h2>
                                </div>
                            </div>

                            <p class="mvc-hd-card-desc"><?php echo esc_html($d['scope']); ?></p>

                            <div class="mvc-hd-card-info-box">
                                <div class="mvc-hd-info-row">
                                    <span class="mvc-hd-info-label">Officer In-Charge:</span>
                                    <span style="font-weight: 700; color: var(--mvc-navy);"><?php echo esc_html($d['incharge']); ?></span>
                                </div>
                                <div class="mvc-hd-info-row">
                                    <span class="mvc-hd-info-label">Desk / Chamber:</span>
                                    <span><?php echo esc_html($d['chamber']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mvc-hd-card-footer">
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $d['phone'])); ?>" class="mvc-hd-btn-call" title="Call <?php echo esc_attr($d['wing']); ?>">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <span>Call Desk</span>
                            </a>
                            <a href="mailto:<?php echo esc_attr($d['email']); ?>" class="mvc-hd-btn-email" title="Email <?php echo esc_attr($d['wing']); ?>">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <span>Email Officer</span>
                            </a>
                            <a href="<?php echo esc_url(home_url($d['link'])); ?>" class="mvc-hd-btn-portal">
                                <span>Visit Division Portal &rarr;</span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <!-- 5. INSTITUTIONAL ESCALATION MATRIX -->
            <section class="mvc-hd-matrix-card">
                <div class="mvc-hd-matrix-header">
                    <h2 class="mvc-hd-matrix-title">Institutional Inquiry Resolution Matrix</h2>
                    <p class="mvc-hd-matrix-sub">If an inquiry or service request requires further review, follow the three-tiered redressal protocol:</p>
                </div>
                <div class="mvc-hd-steps-grid">
                    <div class="mvc-hd-step-item">
                        <div class="mvc-hd-step-num">1</div>
                        <h3 class="mvc-hd-step-title">Primary Desk In-Charge</h3>
                        <p class="mvc-hd-step-desc">Direct contact with designated cell convener or counter clerk for instant resolution, certificate issuance, or routine guidance.</p>
                    </div>
                    <div class="mvc-hd-step-item">
                        <div class="mvc-hd-step-num">2</div>
                        <h3 class="mvc-hd-step-title">Grievance Redressal Cell</h3>
                        <p class="mvc-hd-step-desc">For unresolved administrative issues, formal submissions are reviewed by the Statutory Grievance Cell within 7 business days.</p>
                    </div>
                    <div class="mvc-hd-step-item">
                        <div class="mvc-hd-step-num">3</div>
                        <h3 class="mvc-hd-step-title">Principal &amp; University Liaison</h3>
                        <p class="mvc-hd-step-desc">Final institutional appellate authority presided over by the Principal, with escalation to Veer Kunwar Singh University (VKSU) if statutory.</p>
                    </div>
                </div>
            </section>

            <!-- 6. BOTTOM GOVERNANCE GATEWAYS -->
            <section class="mvc-hd-gateways">
                <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="mvc-hd-gw-card">
                    <div>
                        <div class="mvc-hd-gw-title">Campus Directory</div>
                        <p class="mvc-hd-gw-sub">Postal address, campus map, and departmental telephone extensions.</p>
                    </div>
                    <span class="mvc-hd-gw-arrow">Contact Details &rarr;</span>
                </a>
                <a href="<?php echo esc_url(home_url('/our-administration/')); ?>" class="mvc-hd-gw-card">
                    <div>
                        <div class="mvc-hd-gw-title">Staff Directories</div>
                        <p class="mvc-hd-gw-sub">Teaching faculty and non-teaching ministerial staff rosters.</p>
                    </div>
                    <span class="mvc-hd-gw-arrow">View Roster &rarr;</span>
                </a>
                <a href="<?php echo esc_url(home_url('/student-grievance/')); ?>" class="mvc-hd-gw-card">
                    <div>
                        <div class="mvc-hd-gw-title">Student Grievance</div>
                        <p class="mvc-hd-gw-sub">Statutory portal for confidential academic and welfare complaints.</p>
                    </div>
                    <span class="mvc-hd-gw-arrow">File Grievance &rarr;</span>
                </a>
                <a href="<?php echo esc_url(home_url('/anti-ragging-cell/')); ?>" class="mvc-hd-gw-card">
                    <div>
                        <div class="mvc-hd-gw-title">Anti-Ragging Cell</div>
                        <p class="mvc-hd-gw-sub">Zero-tolerance policy guidelines, committee, and UGC affidavits.</p>
                    </div>
                    <span class="mvc-hd-gw-arrow">Anti-Ragging &rarr;</span>
                </a>
            </section>
        </main>
    </div>

    <!-- Client-side Fast Filter JavaScript -->
    <script>
    var mvcHdActiveCat = 'all';

    function mvcSetHelpDeskCategory(cat, btn) {
        mvcHdActiveCat = cat;
        var pills = document.querySelectorAll('.mvc-hd-pill-btn');
        pills.forEach(function(p) { p.classList.remove('is-active'); });
        if (btn) btn.classList.add('is-active');

        // Sync mobile dropdown
        var ddItem = document.querySelector('#mvcHdMobileCatDropdown .mvc-dropdown-item[data-value="' + cat + '"]');
        if (ddItem) {
            var menu = ddItem.closest('.mvc-dropdown-menu');
            if (menu) {
                menu.querySelectorAll('.mvc-dropdown-item').forEach(function(el) { el.classList.remove('is-active'); });
                ddItem.classList.add('is-active');
            }
            var dd = document.getElementById('mvcHdMobileCatDropdown');
            if (dd) {
                var currentLabel = dd.querySelector('.mvc-dropdown-label');
                var currentIcon = dd.querySelector('.mvc-dropdown-icon');
                var currentCount = dd.querySelector('.mvc-dropdown-count');
                var itemIcon = ddItem.querySelector('.mvc-dd-icon');
                var itemName = ddItem.querySelector('.mvc-dd-name');
                var itemCount = ddItem.querySelector('.mvc-dd-pill');
                if (currentLabel && itemName) currentLabel.textContent = itemName.textContent;
                if (currentIcon && itemIcon) currentIcon.textContent = itemIcon.textContent;
                if (currentCount && itemCount) currentCount.textContent = '(' + itemCount.textContent + ')';
            }
        }
        mvcFilterHelpDesk();
    }

    function mvcSelectHdCategory(cat, itemEl) {
        mvcHdActiveCat = cat;
        var menu = itemEl.closest('.mvc-dropdown-menu');
        if (menu) {
            menu.querySelectorAll('.mvc-dropdown-item').forEach(function(el) { el.classList.remove('is-active'); });
            itemEl.classList.add('is-active');
        }
        var dd = itemEl.closest('.mvc-custom-dropdown');
        if (dd) {
            var currentLabel = dd.querySelector('.mvc-dropdown-label');
            var currentIcon = dd.querySelector('.mvc-dropdown-icon');
            var currentCount = dd.querySelector('.mvc-dropdown-count');
            var itemIcon = itemEl.querySelector('.mvc-dd-icon');
            var itemName = itemEl.querySelector('.mvc-dd-name');
            var itemCount = itemEl.querySelector('.mvc-dd-pill');
            if (currentLabel && itemName) currentLabel.textContent = itemName.textContent;
            if (currentIcon && itemIcon) currentIcon.textContent = itemIcon.textContent;
            if (currentCount && itemCount) currentCount.textContent = '(' + itemCount.textContent + ')';
            dd.classList.remove('is-open');
            var btn = dd.querySelector('.mvc-dropdown-trigger');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        }

        // Sync desktop pills
        var pills = document.querySelectorAll('.mvc-hd-pill-btn');
        pills.forEach(function(p) {
            p.classList.remove('is-active');
            if (p.getAttribute('data-cat') === cat) p.classList.add('is-active');
        });

        mvcFilterHelpDesk();
    }

    function mvcFilterHelpDesk() {
        var input = document.getElementById('mvcHdSearchInput');
        var query = input ? input.value.toLowerCase().trim() : '';
        var cards = document.querySelectorAll('.mvc-hd-card');

        cards.forEach(function(card) {
            var cat = card.getAttribute('data-cat') || '';
            var kw = card.getAttribute('data-keywords') || '';
            var matchesCat = (mvcHdActiveCat === 'all' || cat === mvcHdActiveCat);
            var matchesQuery = (!query || kw.indexOf(query) !== -1);

            if (matchesCat && matchesQuery) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
    </script>
    <?php
}


/**
 * 6. ONE NATION ONE SUBSCRIPTION (ONOS) PAGE RENDERER (/onos/)
 * Handled comprehensively by inc/onos-builder.php
 */
if (!function_exists('mvc_render_onos_page')) {
    function mvc_render_onos_page() {
        if (file_exists(get_stylesheet_directory() . '/inc/onos-builder.php')) {
            require_once get_stylesheet_directory() . '/inc/onos-builder.php';
            mvc_render_onos_page();
        }
    }
}

/**
 * 7. RESEARCH & PUBLICATIONS PAGE RENDERER (/research/)
 * Handled comprehensively by inc/research-builder.php
 */
