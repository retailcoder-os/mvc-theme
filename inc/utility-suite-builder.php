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
                <div class="mvc-util-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></div>
                <p class="mvc-util-hero-desc">
                    <?php echo esc_html($hero_desc); ?>
                </p>
                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">🏛 Established 11 June 1958</span>
                    <span class="mvc-util-pill">📖 15,000+ Print Volumes</span>
                    <span class="mvc-util-pill">🌐 INFLIBNET N-LIST Member</span>
                    <span class="mvc-util-pill">💻 AutoLib Integrated System</span>
                </div>
            </div>
        </header>

        <main class="mvc-util-main">
            <div class="mvc-util-container">
                <!-- Direct Access Action Hub -->
                <div style="background: linear-gradient(135deg, #00152e 0%, var(--mvc-navy) 100%); border-radius: 14px; padding: 24px 28px; margin-bottom: 36px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px; box-shadow: 0 8px 24px rgba(0, 33, 71, 0.12); border-left: 6px solid var(--mvc-gold);">
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
                </div>

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
                <div class="mvc-util-card">
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
                </div>

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
                                    <th>Role in Committee</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Sr. No.">1</td>
                                    <td data-label="Member"><strong>Prof. (Dr.) Krishna Kant Singh</strong></td>
                                    <td data-label="Designation">Principal, M.V. College, Buxar</td>
                                    <td data-label="Role"><span class="mvc-util-badge" style="background:#ecfdf5; color:#065f46;">Chairman</span></td>
                                    <td data-label="Contact">principalmvcbuxar@gmail.com</td>
                                </tr>
                                <tr>
                                    <td data-label="Sr. No.">2</td>
                                    <td data-label="Member"><strong>Dr. Yogershi Rajpoot</strong></td>
                                    <td data-label="Designation">Assistant Professor (Pol. Science)</td>
                                    <td data-label="Role">Convener</td>
                                    <td data-label="Contact">yogirshi@gmail.com</td>
                                </tr>
                                <tr>
                                    <td data-label="Sr. No.">3</td>
                                    <td data-label="Member"><strong>Senior Faculty Nominees</strong></td>
                                    <td data-label="Designation">Arts, Science & Commerce Heads</td>
                                    <td data-label="Role">Members</td>
                                    <td data-label="Contact"><?php echo esc_html(mvc_get_global_setting('library_email', 'mvcollegebuxar@gmail.com')); ?></td>
                                </tr>
                                <tr>
                                    <td data-label="Sr. No.">4</td>
                                    <td data-label="Member"><strong>Library In-Charge / Assistant</strong></td>
                                    <td data-label="Designation">Library Staff</td>
                                    <td data-label="Role">Member Secretary</td>
                                    <td data-label="Contact">mvcollegebuxar@gmail.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Campus Library Image -->
                <?php if (file_exists(ABSPATH . 'wp-content/uploads/library/library2.jpg')): ?>
                    <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:14px; overflow:hidden; box-shadow:0 4px 18px rgba(0,33,71,0.05); margin-bottom:36px;">
                        <img src="<?php echo esc_url(home_url('/wp-content/uploads/library/library2.jpg')); ?>" alt="M.V. College Central Library Reading Hall" style="width:100%; height:auto; max-height:480px; object-fit:cover; display:block;" />
                        <div style="padding:14px 20px; font-size:13px; color:var(--mvc-slate-light); background:#FAF9F6; border-top:1px solid var(--mvc-border); text-align:center;">
                            Photo: Central Library & Stacks Section, Maharshi Vishwamitra Mahavidyalaya, Buxar
                        </div>
                    </div>
                <?php endif; ?>
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
                <div class="mvc-util-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></div>
                <p class="mvc-util-hero-desc">
                    <?php echo esc_html($hero_desc); ?>
                </p>
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
                                    <span class="mvc-util-badge"><?php echo esc_html($res['badge']); ?></span>
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
                <div class="mvc-util-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></div>
                <p class="mvc-util-hero-desc">
                    <?php echo esc_html($hero_desc); ?>
                </p>
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
                <div class="mvc-util-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></div>
                <p class="mvc-util-hero-desc">
                    <?php echo esc_html($hero_desc); ?>
                </p>
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

                <!-- Terms & Conditions for Bidders -->
                <div class="mvc-util-card">
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title">General Instructions & Submission Guidelines for Bidders</h2>
                        <span class="mvc-util-badge">Procurement Rules</span>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:20px; font-size:13.5px; color:var(--mvc-slate); line-height:1.6;">
                        <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px;">
                            <strong style="color:var(--mvc-navy); font-size:15px; display:block; margin-bottom:6px;">1. Two-Bid Sealing Protocol</strong>
                            Bidders must submit technical and financial evaluations in separate sealed envelopes clearly marked "Technical Bid" and "Financial Bid", placed inside an outer master envelope referencing the tender number.
                        </div>
                        <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px;">
                            <strong style="color:var(--mvc-navy); font-size:15px; display:block; margin-bottom:6px;">2. Mandatory Statutory Credentials</strong>
                            Vendors must enclose certified GST Registration, permanent PAN Card, audited balance sheets of the preceding three financial years, and non-blacklisting affidavits.
                        </div>
                        <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px;">
                            <strong style="color:var(--mvc-navy); font-size:15px; display:block; margin-bottom:6px;">3. Submission Venue & Authority</strong>
                            All physical bids must be delivered via registered/speed post or deposited in the Purchase Committee Tender Box at: <em>Office of the Principal, Maharshi Vishwamitra Mahavidyalaya, Charitravan, Buxar (Bihar) - 802101</em>.
                        </div>
                    </div>
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
 */
function mvc_render_help_desk_page($override_form_id = null) {
    global $mvc_shortcode_context;
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 850;
    $hero_title = mvc_get_page_meta($post_id, 'help_desk_hero_title', 'Centralized Institutional Help Desk & Student Support Center');
    $hero_subtitle = mvc_get_page_meta($post_id, 'help_desk_hero_subtitle', 'केंद्रीय संस्थागत सहायता केंद्र एवं छात्र मार्गदर्शन प्रकोष्ठ');
    $hero_desc = mvc_get_page_meta($post_id, 'help_desk_hero_desc', 'Comprehensive single-window guidance for academic inquiries, admissions, examination verification, scholarships, and campus services at Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar).');
    $hotline_phone = mvc_get_global_setting('contact_phone', '06183-796317');
    $secretariat_email = mvc_get_global_setting('principal_email', 'principalmvcbuxar@gmail.com');

    $default_wings = [
        [
            'wing'     => 'Office of the Principal & Central Secretariat',
            'scope'    => 'Administrative governance, official character verification, bonafide certificates, and institutional clearances.',
            'incharge' => 'Principal’s Secretariat Desk',
            'phone'    => '06183-796317 / +91 6183 796317',
            'email'    => 'principalmvcbuxar@gmail.com',
            'chamber'  => 'Ground Floor, Administrative Block',
            'link'     => '/our-administration/',
            'icon'     => '🏛️'
        ],
        [
            'wing'     => 'Central Admissions & Enrolment Cell',
            'scope'    => 'Four-Year Undergraduate (FYUGP NEP) and Postgraduate (CBCS) admission counseling, subject choice verification, and quota allocations.',
            'incharge' => 'Convener, Admission Committee',
            'phone'    => '06183-222035',
            'email'    => mvc_get_global_setting('admission_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Room 102, Academic Wing',
            'link'     => '/admission-procedure/',
            'icon'     => '🎓'
        ],
        [
            'wing'     => 'University Examination & Tabulation Wing',
            'scope'    => 'VKSU semester exams, Continuous Internal Assessment (CIA), admit card resolution, and marksheet tabulation.',
            'incharge' => 'Controller of Examinations',
            'phone'    => '06183-796317',
            'email'    => mvc_get_global_setting('examination_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Room 105, Confidential Examination Wing',
            'link'     => '/result/',
            'icon'     => '📝'
        ],
        [
            'wing'     => 'Central Library & e-Learning Help Desk',
            'scope'    => 'AutoLib library card activation, book renewals, INFLIBNET N-LIST proxy credentials, and reading hall assistance.',
            'incharge' => 'Dr. Yogershi Rajpoot (Convener)',
            'phone'    => '06183-222035',
            'email'    => mvc_get_global_setting('library_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Central Library Stacks Desk',
            'link'     => '/library/',
            'icon'     => '📖'
        ],
        [
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
            'wing'     => 'Statutory Student Grievance & Ombudsman',
            'scope'    => 'UGC-compliant confidential grievance resolution relating to admissions, evaluations, amenities, and campus discipline.',
            'incharge' => 'Dr. Saikat Debnath (Convener)',
            'phone'    => '06183-796317',
            'email'    => mvc_get_global_setting('grievance_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Grievance Cell Chamber',
            'link'     => '/student-grievance/',
            'icon'     => '⚖️'
        ],
        [
            'wing'     => 'Anti-Ragging Vigilance Cell',
            'scope'    => 'Immediate response to ragging, student harassment, psychological intimidation, and mandatory affidavit guidance.',
            'incharge' => 'Dr. Ras Bihari Sharma (Convener)',
            'phone'    => '1800-180-5522 (24×7 Hotline)',
            'email'    => mvc_get_global_setting('antiragging_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Anti-Ragging Cell Room',
            'link'     => '/anti-ragging-cell/',
            'icon'     => '🚨'
        ],
        [
            'wing'     => 'Internal Complaints Committee (ICC & Women Desk)',
            'scope'    => 'Prevention of sexual harassment, gender equity counseling, Girls Common Room support, and female scholar safety.',
            'incharge' => 'Presiding Officer, ICC',
            'phone'    => '06183-796317',
            'email'    => mvc_get_global_setting('icc_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Girls Common Room Complex',
            'link'     => '/committees-cells/',
            'icon'     => '🛡️'
        ],
        [
            'wing'     => 'Public Information Office (Right to Information)',
            'scope'    => 'RTI Act 2005 documentation requests, statutory disclosures, and public institutional records.',
            'incharge' => 'Public Information Officer (PIO)',
            'phone'    => '06183-222035',
            'email'    => mvc_get_global_setting('pio_email', 'mvcollegebuxar@gmail.com'),
            'chamber'  => 'Administrative Secretariat',
            'link'     => '/contact-us/',
            'icon'     => '📄'
        ]
    ];
    $wings = mvc_get_page_array($post_id, 'help_desk_wings', $default_wings);
    ?>
    <div class="mvc-util-root">
        <!-- Hero Banner -->
        <header class="mvc-util-hero">
            <div class="mvc-util-hero-inner">
                <nav class="mvc-util-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <span>Institutional Help Desk</span>
                </nav>
                <h1 class="mvc-util-hero-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-util-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></div>
                <p class="mvc-util-hero-desc">
                    <?php echo esc_html($hero_desc); ?>
                </p>
                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">⚡ Rapid Response Desks</span>
                    <span class="mvc-util-pill">🕒 Mon–Sat: 10:00 AM – 5:30 PM</span>
                    <span class="mvc-util-pill">📍 Charitravan, Buxar</span>
                </div>
            </div>
        </header>

        <main class="mvc-util-main">
            <div class="mvc-util-container">
                <!-- Urgent Helpline Bar -->
                <div style="background:linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 100%); border-radius:14px; padding:24px 28px; margin-bottom:36px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:18px; border-left:6px solid var(--mvc-gold); box-shadow:0 6px 20px rgba(0,33,71,0.12);">
                    <div>
                        <h3 style="color:#FFFFFF; margin:0 0 4px; font-size:20px; font-weight:800;">Emergency Central Campus Helpline</h3>
                        <p style="color:rgba(255,255,255,0.85); margin:0; font-size:14px;">Direct access to the Principal's Secretariat & Central Administrative Control Room.</p>
                    </div>
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $hotline_phone)); ?>" class="mvc-btn mvc-btn-gold">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <span>Call: <?php echo esc_html($hotline_phone); ?></span>
                        </a>
                        <a href="mailto:<?php echo esc_attr($secretariat_email); ?>" class="mvc-btn mvc-btn-maroon">
                            <span>Email Secretariat &rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- 9-Division Helpdesk Directory -->
                <div class="mvc-util-section-header">
                    <h2 class="mvc-util-section-title">Departmental Help Desk Directory (9 Divisions)</h2>
                    <span class="mvc-util-badge">Academic &amp; Administrative Services</span>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:24px; margin-bottom:48px;">
                    <?php foreach ($wings as $w): ?>
                        <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:12px; padding:26px; box-shadow:0 4px 14px rgba(0,33,71,0.04); display:flex; flex-direction:column; justify-content:space-between; transition:all 0.25s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.borderColor='var(--mvc-navy)'; this.style.boxShadow='0 8px 24px rgba(0,33,71,0.08)';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--mvc-border)'; this.style.boxShadow='0 4px 14px rgba(0,33,71,0.04)';">
                            <div>
                                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                                    <span style="font-size:28px;"><?php echo esc_html($w['icon']); ?></span>
                                    <h3 style="font-size:17.5px; font-weight:800; color:var(--mvc-navy); margin:0;">
                                        <?php echo esc_html($w['wing']); ?>
                                    </h3>
                                </div>
                                <p style="font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6; margin:0 0 18px;">
                                    <?php echo esc_html($w['scope']); ?>
                                </p>
                                <div style="background:var(--mvc-bg-alt); padding:14px; border-radius:8px; font-size:13px; margin-bottom:18px;">
                                    <div style="margin-bottom:4px;"><strong>Officer In-Charge:</strong> <?php echo esc_html($w['incharge']); ?></div>
                                    <div style="margin-bottom:4px;"><strong>Chamber / Desk:</strong> <?php echo esc_html($w['chamber']); ?></div>
                                    <div style="margin-bottom:4px;"><strong>Phone / Ext.:</strong> <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $w['phone'])); ?>" style="color:var(--mvc-navy); text-decoration:none; font-weight:600;"><?php echo esc_html($w['phone']); ?></a></div>
                                    <div><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($w['email']); ?>" style="color:var(--mvc-maroon); text-decoration:none;"><?php echo esc_html($w['email']); ?></a></div>
                                </div>
                            </div>
                            <div style="border-top:1px solid var(--mvc-border); padding-top:14px;">
                                <a href="<?php echo esc_url(home_url($w['link'])); ?>" class="mvc-btn mvc-btn-outline" style="width:100%; font-size:13px;">
                                    <span>Visit Department Portal &rarr;</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Online Inquiry Form Wrapper -->
                <div class="mvc-util-card">
                    <?php
                    $form_title = mvc_get_page_meta($post_id, 'form_box_title', 'Submit Digital Helpdesk Inquiry');
                    $form_sub = mvc_get_page_meta($post_id, 'form_box_subtitle', 'Have an academic, verification, or administrative inquiry? Fill in the details below and your ticket will be routed directly to the designated department officer:');
                    $instructions = mvc_get_page_meta($post_id, 'form_instructions', '');
                    $helpline_phone = mvc_get_page_meta($post_id, 'form_helpline_phone', '');
                    $helpline_email = mvc_get_page_meta($post_id, 'form_helpline_email', '');

                    $custom_shortcode = mvc_get_page_meta($post_id, 'form_custom_shortcode', '');
                    $meta_wpforms_id = intval(mvc_get_page_meta($post_id, 'form_wpforms_id', 0));
                    
                    $resolved_form_id = 843;
                    if (!empty($override_form_id)) {
                        $resolved_form_id = intval($override_form_id);
                    } elseif (!empty($mvc_shortcode_context['form_id'])) {
                        $resolved_form_id = intval($mvc_shortcode_context['form_id']);
                    }
                    if ($meta_wpforms_id > 0) {
                        $resolved_form_id = $meta_wpforms_id;
                    }
                    ?>
                    <div class="mvc-util-section-header">
                        <h2 class="mvc-util-section-title"><?php echo esc_html($form_title); ?></h2>
                        <span class="mvc-util-badge">Single Window Portal</span>
                    </div>
                    <p style="font-size:14.5px; color:var(--mvc-slate-light); line-height:1.6; margin-bottom:24px;">
                        <?php echo esc_html($form_sub); ?>
                    </p>

                    <?php if (!empty($helpline_phone) || !empty($helpline_email)): ?>
                        <div style="margin: -10px 0 20px; display: flex; gap: 12px; flex-wrap: wrap;">
                            <?php if (!empty($helpline_phone)): ?>
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(0,33,71,0.06); padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; color: var(--mvc-navy);">
                                    📞 Helpdesk Line: <?php echo esc_html($helpline_phone); ?>
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($helpline_email)): ?>
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(128,0,0,0.06); padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; color: var(--mvc-maroon);">
                                    ✉️ Helpdesk Email: <?php echo esc_html($helpline_email); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($instructions)): ?>
                        <div style="background: #FFFBEB; border-left: 4px solid #D4AF37; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 13.5px; color: #92400E; line-height: 1.6;">
                            <strong>📋 Helpdesk Notice:</strong><br>
                            <?php echo nl2br(esc_html($instructions)); ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (!empty($custom_shortcode)) {
                        echo do_shortcode($custom_shortcode);
                    } else {
                        echo do_shortcode('[wpforms id="' . $resolved_form_id . '"]');
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>
    <?php
}


/**
 * 6. ONE NATION ONE SUBSCRIPTION (ONOS) PAGE RENDERER (/onos/)
 */
function mvc_render_onos_page() {
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 1343;
    $hero_title = "One Nation One Subscription (ONOS)";
    $hero_subtitle = "एक राष्ट्र, एक सदस्यता — राष्ट्रीय उच्च शिक्षा डिजिटल ज्ञान नेटवर्क";
    $hero_desc = "Maharshi Vishwamitra Mahavidyalaya has official access to One Nation One Subscription (ONOS), a landmark initiative by the Ministry of Education, Government of India, providing unified access to top global research journals and digital scholarly databases.";

    ?>
    <div class="mvc-util-page">
        <!-- Hero Header -->
        <header class="mvc-util-hero">
            <div class="mvc-util-hero-inner">
                <nav class="mvc-util-breadcrumb" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="mvc-util-bc-sep">/</span>
                    <a href="<?php echo esc_url(home_url('/library/')); ?>">Central Library</a>
                    <span class="mvc-util-bc-sep">/</span>
                    <span class="mvc-util-bc-curr">ONOS Portal</span>
                </nav>
                <div class="mvc-util-hero-icon-box">
                    <span>🌐</span>
                </div>
                <h1 class="mvc-util-hero-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-util-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></div>
                <p class="mvc-util-hero-desc">
                    <?php echo esc_html($hero_desc); ?>
                </p>
                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">🏛 Ministry of Education (MoE) Initiative</span>
                    <span class="mvc-util-pill">📚 Global Peer-Reviewed Journals</span>
                    <span class="mvc-util-pill">🔬 Nodal Officer: Dr. Ravi Thakur</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mvc-util-main">
            <div class="mvc-util-content-card">
                <!-- Nodal Officer Spotlight Card -->
                <div style="background: linear-gradient(135deg, #002147 0%, #00152e 100%); color: #FFFFFF; border-radius: 14px; padding: 26px 30px; margin-bottom: 35px; border: 2px solid var(--mvc-gold); box-shadow: 0 10px 25px rgba(0,33,71,0.15); display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 20px; flex: 1; min-width: 280px;">
                        <div style="width: 72px; height: 72px; min-width: 72px; border-radius: 50%; background: rgba(212,175,55,0.2); border: 2.5px solid var(--mvc-gold); display: flex; align-items: center; justify-content: center; font-size: 32px;">
                            👨‍🔬
                        </div>
                        <div>
                            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: rgba(212,175,55,0.25); color: #FEF08A; padding: 2px 10px; border-radius: 999px; border: 1px solid rgba(212,175,55,0.4); display: inline-block; margin-bottom: 6px;">
                                Designated Institutional Nodal Officer
                            </span>
                            <h3 style="margin: 0 0 4px; font-size: 22px; font-weight: 800; color: #FFFFFF;">Dr. Ravi Thakur</h3>
                            <div style="font-size: 14px; color: #E2E8F0; font-weight: 600;">
                                Assistant Professor, Department of Chemistry
                            </div>
                            <div style="font-size: 12.5px; color: #CBD5E1; margin-top: 4px;">
                                Maharshi Vishwamitra Mahavidyalaya, Buxar (Bihar)
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right; min-width: 200px;">
                        <div style="font-size: 12px; color: #CBD5E1; margin-bottom: 8px;">For scholar access, registration &amp; queries:</div>
                        <a href="mailto:mvcollegebuxar@gmail.com?subject=ONOS%20Access%20Request%20-%20Attn%20Dr.%20Ravi%20Thakur" class="mvc-btn-download" style="background: var(--mvc-gold); color: #002147; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 8px; text-decoration: none;">
                            <span>✉️ Contact Nodal Officer</span>
                        </a>
                    </div>
                </div>

                <!-- About ONOS Section -->
                <div style="margin-bottom: 35px;">
                    <h2 style="font-size: 22px; font-weight: 800; color: var(--mvc-navy); margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <span>📖 About One Nation One Subscription (ONOS)</span>
                    </h2>
                    <p style="font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 16px;">
                        <strong>One Nation One Subscription (ONOS)</strong> is a national digital research initiative spearheaded by the Ministry of Education, Government of India. Under ONOS, the central government procures national site licenses from premier global academic publishers, granting all faculty, researchers, and students in government higher education institutions seamless, direct access to international peer-reviewed journals and digital databases.
                    </p>
                    <p style="font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 16px;">
                        As a constituent college of Veer Kunwar Singh University (VKSU), Maharshi Vishwamitra Mahavidyalaya is fully integrated into the ONOS network, enabling undergraduate scholars, postgraduate students, and faculty members to access high-impact research materials across all disciplines free of charge.
                    </p>
                </div>

                <!-- 4 Feature Highlights Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 40px;">
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                        <div style="font-size: 28px; margin-bottom: 10px;">🌐</div>
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--mvc-navy); margin: 0 0 6px;">Global Publisher Access</h4>
                        <p style="font-size: 13.5px; color: #64748B; margin: 0; line-height: 1.5;">Access scholarly publications from Elsevier (ScienceDirect), Springer Nature, Wiley, Taylor &amp; Francis, IEEE, and more.</p>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                        <div style="font-size: 28px; margin-bottom: 10px;">🎓</div>
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--mvc-navy); margin: 0 0 6px;">Free for All Scholars</h4>
                        <p style="font-size: 13.5px; color: #64748B; margin: 0; line-height: 1.5;">No paywalls or subscription charges for enrolled students, research scholars, and teaching faculty of the college.</p>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                        <div style="font-size: 28px; margin-bottom: 10px;">💻</div>
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--mvc-navy); margin: 0 0 6px;">Campus &amp; Remote Access</h4>
                        <p style="font-size: 13.5px; color: #64748B; margin: 0; line-height: 1.5;">Seamless access via campus high-speed Wi-Fi, computer labs, central library terminals, and authorized remote logins.</p>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                        <div style="font-size: 28px; margin-bottom: 10px;">🔬</div>
                        <h4 style="font-size: 16px; font-weight: 800; color: var(--mvc-navy); margin: 0 0 6px;">Multidisciplinary Coverage</h4>
                        <p style="font-size: 13.5px; color: #64748B; margin: 0; line-height: 1.5;">Extensive literature across Chemistry, Physics, Botany, Zoology, Mathematics, History, Economics, Literature, and Social Sciences.</p>
                    </div>
                </div>

                <!-- Access Guidelines & Steps -->
                <div style="background: #FEF9C3; border: 1px solid #FDE047; border-radius: 12px; padding: 22px 26px; margin-bottom: 30px;">
                    <h3 style="font-size: 17px; font-weight: 800; color: #854D0E; margin: 0 0 8px;">
                        📋 Guidelines for Accessing ONOS at Maharshi Vishwamitra Mahavidyalaya
                    </h3>
                    <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #713F12; line-height: 1.7;">
                        <li><strong>On-Campus Access:</strong> Connect to the college Wi-Fi or visit the Automated Central Library digital terminal section to access participating journals directly.</li>
                        <li><strong>User Orientation:</strong> Regular orientation sessions are conducted by Nodal Officer Dr. Ravi Thakur to assist students in literature reviews and citation indexing.</li>
                        <li><strong>Research Inquiries:</strong> Contact the Department of Chemistry or email the college administration for credential issuance and scholarly guidance.</li>
                    </ul>
                </div>

                <!-- Related Links Bar -->
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url(home_url('/library/')); ?>" class="mvc-btn-download" style="background: var(--mvc-navy); color: #fff;">
                        📚 Automated Central Library &rarr;
                    </a>
                    <a href="<?php echo esc_url(home_url('/research/')); ?>" class="mvc-btn-download" style="background: #F1F5F9; color: var(--mvc-navy); border: 1px solid #CBD5E1;">
                        🔬 Research &amp; Publications &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>
    <?php
}

/**
 * 7. RESEARCH & PUBLICATIONS PAGE RENDERER (/research/)
 */
function mvc_render_research_page() {
    mvc_render_utility_suite_styles();
    $post_id = get_the_ID() ?: 1344;
    $hero_title = "Research & Publications";
    $hero_subtitle = "शोध, नवाचार एवं संकाय प्रकाशन प्रकोष्ठ";
    $hero_desc = "Fostering academic inquiry, faculty research monographs, peer-reviewed journal papers, and multidisciplinary investigation across Humanities, Social Sciences, and Sciences at Maharshi Vishwamitra Mahavidyalaya, Buxar.";

    ?>
    <div class="mvc-util-page">
        <!-- Hero Header -->
        <header class="mvc-util-hero">
            <div class="mvc-util-hero-inner">
                <nav class="mvc-util-breadcrumb" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="mvc-util-bc-sep">/</span>
                    <a href="<?php echo esc_url(home_url('/department-details/')); ?>">Academics</a>
                    <span class="mvc-util-bc-sep">/</span>
                    <span class="mvc-util-bc-curr">Research &amp; Publications</span>
                </nav>
                <div class="mvc-util-hero-icon-box">
                    <span>🔬</span>
                </div>
                <h1 class="mvc-util-hero-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-util-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></div>
                <p class="mvc-util-hero-desc">
                    <?php echo esc_html($hero_desc); ?>
                </p>
                <div class="mvc-util-hero-pills">
                    <span class="mvc-util-pill mvc-util-pill-gold">🏛 UGC Recognized Research</span>
                    <span class="mvc-util-pill">📖 Peer-Reviewed Publications</span>
                    <span class="mvc-util-pill">🌐 ONOS &amp; INFLIBNET N-LIST Access</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mvc-util-main">
            <div class="mvc-util-content-card">
                <!-- Research Mission -->
                <div style="margin-bottom: 35px;">
                    <h2 style="font-size: 22px; font-weight: 800; color: var(--mvc-navy); margin-bottom: 14px;">
                        Institutional Research Framework
                    </h2>
                    <p style="font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 16px;">
                        Maharshi Vishwamitra Mahavidyalaya actively encourages intellectual curiosity and scholarly research among its faculty and students. Situated in the historic Siddhashram region of Buxar, the college provides a stimulating environment for academic study spanning literature, history, social change, pure sciences, and applied technologies.
                    </p>
                </div>

                <!-- Digital Research Networks (ONOS & INFLIBNET) -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 40px;">
                    <div style="background: linear-gradient(135deg, #002147 0%, #00152e 100%); color: #fff; border-radius: 14px; padding: 24px; border: 1.5px solid var(--mvc-gold);">
                        <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #FEF08A; margin-bottom: 6px;">National Subscription</div>
                        <h3 style="margin: 0 0 8px; font-size: 20px; color: #fff;">One Nation One Subscription (ONOS)</h3>
                        <p style="font-size: 13.5px; color: #CBD5E1; line-height: 1.6; margin-bottom: 16px;">
                            Centrally funded access to premier international publishers (Elsevier, Springer, Wiley, IEEE). Nodal Officer: <strong>Dr. Ravi Thakur</strong>, Dept of Chemistry.
                        </p>
                        <a href="<?php echo esc_url(home_url('/onos/')); ?>" style="display: inline-flex; align-items: center; gap: 6px; background: var(--mvc-gold); color: #002147; padding: 7px 16px; border-radius: 6px; font-size: 13px; font-weight: 800; text-decoration: none;">
                            View ONOS Details &rarr;
                        </a>
                    </div>

                    <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 24px;">
                        <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--mvc-navy); margin-bottom: 6px;">UGC INFLIBNET Consortium</div>
                        <h3 style="margin: 0 0 8px; font-size: 20px; color: var(--mvc-navy);">INFLIBNET N-LIST Repository</h3>
                        <p style="font-size: 13.5px; color: #64748B; line-height: 1.6; margin-bottom: 16px;">
                            Over 6,000+ e-journals and 1,99,500+ e-books accessible to faculty and enrolled students via the Automated Central Library portal.
                        </p>
                        <a href="<?php echo esc_url(home_url('/library/')); ?>" style="display: inline-flex; align-items: center; gap: 6px; background: var(--mvc-navy); color: #fff; padding: 7px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none;">
                            Library Resources &rarr;
                        </a>
                    </div>
                </div>

                <!-- Research & Publications Guidelines -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--mvc-navy); margin: 0 0 10px;">
                        Faculty Publications &amp; Academic Contributions
                    </h3>
                    <p style="font-size: 14.5px; color: #475569; line-height: 1.6; margin-bottom: 14px;">
                        The teaching faculty of Maharshi Vishwamitra Mahavidyalaya consistently contributes to national and international peer-reviewed journals, books, and conference proceedings. Detailed research portfolios and individual publication lists are maintained in the respective department sections and faculty curriculum vitae.
                    </p>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="<?php echo esc_url(home_url('/teaching-staff/')); ?>" class="mvc-btn-download" style="background: var(--mvc-navy); color: #fff;">
                            👨‍🏫 View Faculty Directory &amp; CVs &rarr;
                        </a>
                        <a href="<?php echo esc_url(home_url('/department-details/')); ?>" class="mvc-btn-download" style="background: #F1F5F9; color: var(--mvc-navy); border: 1px solid #CBD5E1;">
                            🏛 Academic Departments &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php
}
