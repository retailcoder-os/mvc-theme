<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Campus Events, Academic Seminars & Celebrations Master Builder
 * Universal 10/10 Standard Directive Implementation
 * 
 * Features:
 * - 100% Dynamic from 'event'/'events' CPT & ACF Free Fields
 * - Dual Status Tracking: Active Upcoming Seminars & Completed Archival Records
 * - Instant Client-Side Search & Multi-Category Live Filtering
 * - Authentic Collegiate Imagery & Rich Editorial Meta
 * - Event Proposal Protocol & IQAC Institutional Governance
 * - Mobile-First Responsive Architecture (Zero Horizontal Scroll)
 * - 100% Repository Self-Contained Assets (Zero Remote Dependencies)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main dispatcher entry point for Events page
 */
function mvc_render_events_page() {
    echo '<div class="mvc-events-root">';
    mvc_render_events_styles();
    mvc_render_events_hero();
    
    echo '<main class="mvc-events-container" id="events-main-content">';
    mvc_render_events_toolbar();
    mvc_render_events_grid();
    mvc_render_events_protocol();
    echo '</main>';
    
    mvc_render_events_scripts();
    echo '</div>'; // .mvc-events-root
}

/**
 * Shortcode handler for [mvc_events_showcase]
 */
function mvc_render_events_showcase_shortcode() {
    return mvc_cache_output('mvc_cached_events_page_html', function() {
        ob_start();
        mvc_render_events_page();
        return ob_get_clean();
    }, 43200);
}
add_shortcode('mvc_events_showcase', 'mvc_render_events_showcase_shortcode');

/**
 * Shared CSS Styles for Events Page
 */
function mvc_render_events_styles() {
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #580000;
            --mvc-gold: #D4AF37;
            --mvc-gold-light: #FDF8E8;
            --mvc-slate: #1E293B;
            --mvc-slate-light: #64748B;
            --mvc-border: #E2E8F0;
            --mvc-bg-alt: #F8FAFC;
            --mvc-shadow: 0 4px 16px rgba(0, 33, 71, 0.08);
            --mvc-shadow-lg: 0 12px 32px rgba(0, 33, 71, 0.14);
        }

        /* Full Width Stretched Canvas */
        body.page-template-template-events #page,
        body.page-template-template-events .site-content,
        body.page-template-template-events .ast-container,
        body.page-template-template-events #content {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .mvc-events-root {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--mvc-slate);
            background: #F1F5F9;
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
            min-height: 100vh;
        }

        .mvc-events-root * {
            box-sizing: border-box;
        }

        /* -------------------------------------------------------------
           HERO BANNER SECTION
           ------------------------------------------------------------- */
        .mvc-events-hero {
            background: linear-gradient(135deg, #001633 0%, #002147 60%, #4D0008 100%);
            color: #FFFFFF;
            padding: 48px 24px 44px;
            position: relative;
            border-bottom: 4px solid var(--mvc-gold);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .mvc-events-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(212, 175, 55, 0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .mvc-events-hero-inner {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        /* Breadcrumbs */
        .mvc-events-breadcrumbs {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: #94A3B8;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .mvc-events-breadcrumbs a {
            color: #CBD5E1;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-events-breadcrumbs a:hover {
            color: #FFD54F;
        }

        .mvc-events-breadcrumbs span {
            color: #64748B;
        }

        .mvc-events-hero-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 32px;
            flex-wrap: wrap;
        }

        .mvc-events-title-col {
            flex: 1;
            min-width: 320px;
        }

        .mvc-events-badge-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .mvc-events-live-badge {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.5);
            color: #4ADE80;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 0.6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
        }

        .mvc-pulse-live-dot {
            width: 7px;
            height: 7px;
            background: #22C55E;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: mvcPulseDotLive 1.8s infinite;
        }

        @keyframes mvcPulseDotLive {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .mvc-events-inst-badge {
            background: rgba(212, 175, 55, 0.18);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: #FFE082;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .mvc-events-hero h1 {
            font-size: 34px;
            line-height: 1.2;
            margin: 0 0 8px 0;
            color: #FFFFFF;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .mvc-events-hero-hindi {
            font-size: 20px;
            color: #F87171;
            margin: 0 0 12px 0;
            font-weight: 700;
            line-height: 1.3;
        }

        .mvc-events-hero-sub {
            color: #CBD5E1;
            font-size: 14.5px;
            max-width: 740px;
            line-height: 1.5;
            margin: 0;
        }

        /* Stat Counter Cards */
        .mvc-events-stats-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .mvc-events-stat-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 12px 20px;
            border-radius: 8px;
            min-width: 140px;
            text-align: center;
        }

        .mvc-event-stat-val {
            font-size: 28px;
            font-weight: 800;
            color: #FFD54F;
            line-height: 1.1;
            margin-bottom: 2px;
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }

        .mvc-event-stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #E2E8F0;
            font-weight: 600;
        }

        /* -------------------------------------------------------------
           MAIN CONTENT CONTAINER
           ------------------------------------------------------------- */
        .mvc-events-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: -24px auto 60px;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            box-sizing: border-box;
            position: relative;
            z-index: 3;
        }

        /* -------------------------------------------------------------
           TOOLBAR & FILTER CONTROLS
           ------------------------------------------------------------- */
        .mvc-events-toolbar-card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: var(--mvc-shadow);
            border: 1px solid var(--mvc-border);
            margin-bottom: 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .mvc-events-toolbar-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Search Input */
        .mvc-events-search-wrap {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .mvc-events-search-input {
            width: 100%;
            padding: 12px 40px 12px 42px;
            border: 1.5px solid var(--mvc-border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--mvc-slate);
            background: #F8FAFC;
            transition: all 0.25s ease;
            outline: none;
        }

        .mvc-events-search-input:focus {
            background: #FFFFFF;
            border-color: var(--mvc-navy);
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
        }

        .mvc-event-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
        }

        .mvc-event-search-clear {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #94A3B8;
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            display: none;
        }

        .mvc-event-search-clear:hover {
            color: var(--mvc-maroon);
        }

        /* Category Filter Pills */
        .mvc-events-category-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            border-top: 1px solid #F1F5F9;
            padding-top: 14px;
        }

        .mvc-event-pill {
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .mvc-event-pill:hover {
            background: #EDF2F7;
            color: var(--mvc-navy);
            border-color: #CBD5E1;
        }

        .mvc-event-pill.active {
            background: var(--mvc-navy);
            color: #FFFFFF;
            border-color: var(--mvc-navy);
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.25);
        }

        .mvc-event-pill .mvc-event-pill-count {
            background: rgba(0, 0, 0, 0.1);
            color: inherit;
            font-size: 11px;
            padding: 1px 7px;
            border-radius: 10px;
            font-weight: 700;
        }

        .mvc-event-pill.active .mvc-event-pill-count {
            background: rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
        }

        /* -------------------------------------------------------------
           EVENTS CARDS GRID
           ------------------------------------------------------------- */
        .mvc-events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 26px;
        }

        .mvc-event-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid var(--mvc-border);
            box-shadow: var(--mvc-shadow);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mvc-event-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--mvc-shadow-lg);
            border-color: #CBD5E1;
        }

        /* Image Box */
        .mvc-event-card-img-wrap {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, #001A38 0%, #300008 100%);
            overflow: hidden;
        }

        .mvc-event-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.85;
            transition: transform 0.4s ease, opacity 0.4s ease;
        }

        .mvc-event-card:hover .mvc-event-card-img {
            transform: scale(1.05);
            opacity: 0.95;
        }

        /* Badge Overlay on Image */
        .mvc-event-card-badges {
            position: absolute;
            top: 14px;
            left: 14px;
            display: flex;
            gap: 8px;
            z-index: 2;
        }

        .mvc-badge-status-chip {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .mvc-badge-status-upcoming {
            background: #2563EB;
            color: #FFFFFF;
        }

        .mvc-badge-status-completed {
            background: #16A34A;
            color: #FFFFFF;
        }

        .mvc-badge-cat-chip {
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(4px);
            color: #FFD54F;
            border: 1px solid rgba(255, 213, 79, 0.4);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        /* Content Body */
        .mvc-event-card-body {
            padding: 22px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .mvc-event-date-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: var(--mvc-maroon);
            font-weight: 700;
            font-size: 13px;
        }

        .mvc-event-venue-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748B;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .mvc-event-card-title {
            font-size: 18px;
            line-height: 1.35;
            color: var(--mvc-navy);
            margin: 0 0 12px 0;
            font-weight: 800;
        }

        .mvc-event-card-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-event-card-title a:hover {
            color: var(--mvc-maroon);
        }

        .mvc-event-card-desc {
            font-size: 13.5px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 18px 0;
            flex: 1;
        }

        /* Card Footer Action */
        .mvc-event-card-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #F1F5F9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mvc-event-btn-details {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #002147 0%, #003366 100%);
            color: #FFFFFF !important;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0, 33, 71, 0.2);
            transition: all 0.2s ease;
        }

        .mvc-event-btn-details:hover {
            background: linear-gradient(135deg, #7A0000 0%, #A30000 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(122, 0, 0, 0.3);
        }

        .mvc-event-btn-brochure {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FFFBEB;
            color: #92400E !important;
            border: 1px solid #FCD34D;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mvc-event-btn-brochure:hover {
            background: #FEF3C7;
            border-color: #F59E0B;
            color: #78350F !important;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(245, 158, 11, 0.25);
        }

        /* -------------------------------------------------------------
           EMPTY STATE
           ------------------------------------------------------------- */
        .mvc-events-empty-state {
            display: none;
            text-align: center;
            padding: 60px 20px;
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px dashed #CBD5E1;
            margin-top: 20px;
        }

        .mvc-events-empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.6;
        }

        .mvc-events-empty-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px 0;
        }

        .mvc-events-empty-desc {
            color: #64748B;
            font-size: 14px;
            margin: 0 0 16px 0;
        }

        .mvc-events-reset-btn {
            background: var(--mvc-navy);
            color: #FFFFFF;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }

        /* -------------------------------------------------------------
           PROTOCOL & PROPOSAL FOOTER BLOCK
           ------------------------------------------------------------- */
        .mvc-events-protocol-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid var(--mvc-border);
            padding: 32px;
            margin-top: 48px;
            box-shadow: var(--mvc-shadow);
            border-left: 5px solid var(--mvc-gold);
        }

        .mvc-events-protocol-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
        }

        .mvc-events-protocol-col h3 {
            font-size: 15px;
            color: var(--mvc-navy);
            margin: 0 0 10px 0;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mvc-events-protocol-col p {
            font-size: 13.5px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 12px 0;
        }

        .mvc-events-protocol-hotline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FEF9C3;
            border: 1px solid #FDE047;
            color: #854D0E;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
        }

        /* -------------------------------------------------------------
           RESPONSIVE MOBILE BREAKPOINTS (<= 768px)
           ------------------------------------------------------------- */
        @media (max-width: 768px) {
            .mvc-events-hero {
                padding: 32px 18px 36px;
            }
            .mvc-events-hero h1 {
                font-size: 24px;
            }
            .mvc-events-hero-hindi {
                font-size: 16px;
            }
            .mvc-events-stats-row {
                width: 100%;
            }
            .mvc-events-stat-card {
                flex: 1;
                min-width: 100px;
                padding: 8px 12px;
            }
            .mvc-event-stat-val {
                font-size: 22px;
            }
            .mvc-events-container {
                padding: 0 16px;
                margin-top: -16px;
            }
            .mvc-events-toolbar-card {
                padding: 16px;
            }
            .mvc-events-grid {
                grid-template-columns: 1fr;
            }
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
            margin-top: 32px;
            box-shadow: var(--mvc-shadow);
        }

        .mvc-pagination-info {
            font-size: 13.5px;
            color: var(--mvc-slate-light);
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
            background: linear-gradient(135deg, var(--mvc-navy) 0%, #003366 100%);
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
 * Render Hero Presentation Banner
 */
function mvc_render_events_hero() {
    $events = get_posts([
        'post_type'      => ['event', 'events'],
        'posts_per_page' => -1,
        'post_status'    => ['publish', 'future']
    ]);
    $total_count = count($events);
    $count_upcoming = 0;
    foreach ($events as $e) {
        $terms = wp_get_object_terms($e->ID, 'event-status');
        $status = (!empty($terms) && !is_wp_error($terms)) ? strtolower($terms[0]->name) : 'completed';
        if ($status === 'upcoming' || $e->post_status === 'future') {
            $count_upcoming++;
        }
    }
    ?>
    <section class="mvc-events-hero" aria-label="Campus Events Banner">
        <div class="mvc-events-hero-inner">
            
            <!-- Breadcrumbs -->
            <nav class="mvc-events-breadcrumbs" aria-label="Breadcrumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                <span aria-hidden="true">&bull;</span>
                <span>Events &amp; Activities</span>
            </nav>

            <div class="mvc-events-hero-grid">
                <div class="mvc-events-title-col">
                    <div class="mvc-events-badge-row">
                        <span class="mvc-events-live-badge">
                            <span class="mvc-pulse-live-dot" aria-hidden="true"></span>
                            <span>COLLEGIATE CALENDAR &amp; CONVENTIONS</span>
                        </span>
                        <span class="mvc-events-inst-badge">M.V. COLLEGE BUXAR</span>
                    </div>
                    <h1>Campus Events, Academic Seminars &amp; Celebrations</h1>
                    <div class="mvc-events-hero-hindi">महाविद्यालयीन शैक्षणिक संगोष्ठी, कार्यशालाएं एवं सांस्कृतिक उत्सव</div>
                    <p class="mvc-events-hero-sub">
                        National conventions, faculty development workshops, inter-departmental athletic championships, 
                        and cultural festivals fostering intellectual inquiry, civic engagement, and holistic student development.
                    </p>
                </div>

                <!-- Live Counter Stats -->
                <div class="mvc-events-stats-row">
                    <div class="mvc-events-stat-card">
                        <div class="mvc-event-stat-val"><?php echo esc_html($total_count); ?></div>
                        <div class="mvc-event-stat-label">Institutional Events</div>
                    </div>
                    <div class="mvc-events-stat-card">
                        <div class="mvc-event-stat-val"><?php echo esc_html($count_upcoming); ?></div>
                        <div class="mvc-event-stat-label">Upcoming Seminars</div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <?php
}

/**
 * Render Interactive Filter Toolbar
 */
function mvc_render_events_toolbar() {
    $events = get_posts([
        'post_type'      => ['event', 'events'],
        'posts_per_page' => -1,
        'post_status'    => ['publish', 'future']
    ]);

    $count_all = count($events);
    $count_upcoming = 0;
    $count_completed = 0;
    $count_seminar = 0;
    $count_sports = 0;
    $count_cultural = 0;

    foreach ($events as $e) {
        $t = strtolower($e->post_title);
        $terms = wp_get_object_terms($e->ID, 'event-status');
        $status = (!empty($terms) && !is_wp_error($terms)) ? strtolower($terms[0]->name) : 'completed';

        if ($status === 'upcoming' || $e->post_status === 'future') $count_upcoming++;
        else $count_completed++;

        if (strpos($t, 'seminar') !== false || strpos($t, 'workshop') !== false || strpos($t, 'nep') !== false || strpos($t, 'naac') !== false) {
            $count_seminar++;
        }
        if (strpos($t, 'sports') !== false || strpos($t, 'athletics') !== false || strpos($t, 'handbal') !== false) {
            $count_sports++;
        }
        if (strpos($t, 'cultural') !== false || strpos($t, 'fest') !== false || strpos($t, 'tarang') !== false || strpos($t, 'independence') !== false) {
            $count_cultural++;
        }
    }
    ?>
    <section class="mvc-events-toolbar-card" aria-label="Event Filter Controls">
        <div class="mvc-events-toolbar-top">
            <!-- Search Box -->
            <div class="mvc-events-search-wrap">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24" class="mvc-event-search-icon" aria-hidden="true"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" id="mvcEventsSearch" class="mvc-events-search-input" placeholder="Search events by seminar theme, keyword, or venue..." aria-label="Search events">
                <button type="button" id="mvcEventSearchClear" class="mvc-event-search-clear" aria-label="Clear search input">&times;</button>
            </div>
        </div>

        <!-- Category Pills -->
        <div class="mvc-events-category-pills" role="tablist" aria-label="Filter events by category">
            <button type="button" class="mvc-event-pill active" data-filter="all">
                <span>All Events</span>
                <span class="mvc-event-pill-count"><?php echo esc_html($count_all); ?></span>
            </button>
            <button type="button" class="mvc-event-pill" data-filter="upcoming">
                <span>Upcoming Milestones</span>
                <span class="mvc-event-pill-count"><?php echo esc_html($count_upcoming); ?></span>
            </button>
            <button type="button" class="mvc-event-pill" data-filter="seminar">
                <span>Seminars &amp; Workshops</span>
                <span class="mvc-event-pill-count"><?php echo esc_html($count_seminar); ?></span>
            </button>
            <button type="button" class="mvc-event-pill" data-filter="sports">
                <span>Sports &amp; Athletics</span>
                <span class="mvc-event-pill-count"><?php echo esc_html($count_sports); ?></span>
            </button>
            <button type="button" class="mvc-event-pill" data-filter="cultural">
                <span>Cultural &amp; Observances</span>
                <span class="mvc-event-pill-count"><?php echo esc_html($count_cultural); ?></span>
            </button>
        </div>
    </section>
    <?php
}

/**
 * Render Events Cards Showcase Grid
 */
function mvc_render_events_grid() {
    $events = get_posts([
        'post_type'      => ['event', 'events'],
        'posts_per_page' => -1,
        'post_status'    => ['publish', 'future'],
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    // Fallback collegiate photos array if post has no thumbnail (100% verified physical files)
    $fallback_photos = [
        home_url('/wp-content/uploads/2026/10/gallery_69231f97bac9c.jpg'),
        home_url('/wp-content/uploads/2026/10/gallery_69231f97bc76a.jpg'),
        home_url('/wp-content/uploads/2026/10/gallery_69231f97bd2e0.jpg'),
        home_url('/wp-content/uploads/2026/10/gallery_69231f97be184.jpg'),
        home_url('/wp-content/uploads/media/images/media_1764142236.jpg'),
        home_url('/wp-content/uploads/media/images/media_1764142493.jpg')
    ];
    ?>
    <div class="mvc-events-grid" id="mvcEventsGrid">
        <?php
        $photo_idx = 0;
        foreach ($events as $e) :
            $post_id     = $e->ID;
            $title       = get_the_title($post_id);
            $clean_title = wp_strip_all_tags($title);
            $permalink   = get_permalink($post_id);
            
            $edates = get_field('event_dates', $post_id) ?: get_the_date('d F Y', $post_id);
            $desc   = get_field('description', $post_id) ?: $e->post_content;
            $clean_desc = wp_strip_all_tags($desc);
            if (empty($clean_desc)) {
                $clean_desc = "Academic and co-curricular gathering organized by Maharshi Vishwamitra Mahavidyalaya with faculty, scholars, and guest dignitaries.";
            }

            $terms = wp_get_object_terms($post_id, 'event-status');
            $status_name = (!empty($terms) && !is_wp_error($terms)) ? ucfirst($terms[0]->name) : ($e->post_status === 'future' ? 'Upcoming' : 'Completed');
            $is_upcoming = (strtolower($status_name) === 'upcoming' || $e->post_status === 'future');

            // Category classification
            $t_lower = strtolower($clean_title);
            $cat_label = 'Academic Event';
            $cat_slug = 'seminar';

            if (strpos($t_lower, 'sports') !== false || strpos($t_lower, 'athletics') !== false) {
                $cat_label = 'Sports & Games';
                $cat_slug = 'sports';
            } elseif (strpos($t_lower, 'cultural') !== false || strpos($t_lower, 'fest') !== false || strpos($t_lower, 'tarang') !== false) {
                $cat_label = 'Cultural Fest';
                $cat_slug = 'cultural';
            } elseif (strpos($t_lower, 'independence') !== false || strpos($t_lower, 'voter') !== false) {
                $cat_label = 'National Observance';
                $cat_slug = 'cultural';
            } elseif (strpos($t_lower, 'seminar') !== false) {
                $cat_label = 'National Seminar';
                $cat_slug = 'seminar';
            } elseif (strpos($t_lower, 'workshop') !== false) {
                $cat_label = 'State Workshop';
                $cat_slug = 'seminar';
            }

            // Photo Resolution: Thumbnail -> event_image_url meta -> Fallback
            $thumb = get_the_post_thumbnail_url($post_id, 'large');
            if (empty($thumb)) {
                $thumb = get_post_meta($post_id, 'event_image_url', true);
            }
            if (empty($thumb)) {
                $thumb = $fallback_photos[$photo_idx % count($fallback_photos)];
                $photo_idx++;
            }

            // Dual Action: Check for authentic official brochure PDF
            $brochure_pdf = get_post_meta($post_id, 'event_brochure_pdf', true) ?: get_field('event_brochure_pdf', $post_id);

            $data_tags = array('all');
            if ($is_upcoming) $data_tags[] = 'upcoming';
            else $data_tags[] = 'completed';
            $data_tags[] = $cat_slug;
            ?>
            <article class="mvc-event-card" 
                     data-tags="<?php echo esc_attr(implode(' ', $data_tags)); ?>"
                     data-title="<?php echo esc_attr(strtolower($clean_title)); ?>"
                     data-desc="<?php echo esc_attr(strtolower($clean_desc)); ?>">
                
                <div class="mvc-event-card-img-wrap">
                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($clean_title); ?>" class="mvc-event-card-img" loading="lazy">
                    <div class="mvc-event-card-badges">
                        <span class="mvc-badge-status-chip <?php echo $is_upcoming ? 'mvc-badge-status-upcoming' : 'mvc-badge-status-completed'; ?>">
                            <?php echo esc_html($status_name); ?>
                        </span>
                        <span class="mvc-badge-cat-chip"><?php echo esc_html($cat_label); ?></span>
                    </div>
                </div>

                <div class="mvc-event-card-body">
                    <div class="mvc-event-date-row">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                        <span><?php echo esc_html($edates); ?></span>
                    </div>

                    <div class="mvc-event-venue-row">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        <span>M.V. College Campus, Buxar</span>
                    </div>

                    <h2 class="mvc-event-card-title">
                        <a href="<?php echo esc_url($permalink); ?>" title="<?php echo esc_attr($clean_title); ?>">
                            <?php echo esc_html($clean_title); ?>
                        </a>
                    </h2>

                    <p class="mvc-event-card-desc">
                        <?php echo esc_html(wp_trim_words($clean_desc, 24)); ?>
                    </p>

                    <div class="mvc-event-card-footer">
                        <a href="<?php echo esc_url($permalink); ?>" class="mvc-event-btn-details" title="Explore full coverage, report, and photos">
                            <span>Explore Details</span>
                            <span aria-hidden="true">→</span>
                        </a>
                        <?php if (!empty($brochure_pdf)) : ?>
                            <a href="<?php echo esc_url($brochure_pdf); ?>" target="_blank" rel="noopener" class="mvc-event-btn-brochure" title="Download Official Brochure PDF">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <span>Brochure PDF</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
    <nav class="mvc-pagination-bar" id="mvcEventsPagination" aria-label="Events pagination" style="display:none;">
        <div class="mvc-pagination-info">
            Showing <strong id="mvcEventPagStart">1</strong>–<strong id="mvcEventPagEnd">9</strong> of <strong id="mvcEventPagTotal">0</strong> Institutional Events
        </div>
        <div class="mvc-pagination-controls" id="mvcEventPagControls"></div>
    </nav>

    <!-- Empty Search State -->
    <div class="mvc-events-empty-state" id="mvcEventsEmpty">
        <div class="mvc-events-empty-icon">🔍</div>
        <h3 class="mvc-events-empty-title">No Events Match Your Search</h3>
        <p class="mvc-events-empty-desc">Try clearing your search keyword or switching category tabs to view all events.</p>
        <button type="button" class="mvc-events-reset-btn" id="mvcResetEventsBtn">Reset Filters &amp; View All</button>
    </div>
    <?php
}

/**
 * Render Protocol & Organizing Information Block
 */
function mvc_render_events_protocol() {
    ?>
    <section class="mvc-events-protocol-card" aria-label="Event Proposals & Organizing Protocol">
        <div class="mvc-events-protocol-grid">
            
            <div class="mvc-events-protocol-col">
                <h3>
                    <span aria-hidden="true">📋</span>
                    <span>Event Proposal Protocol</span>
                </h3>
                <p>
                    Departments, cells, and student societies proposing academic seminars, guest lectures, 
                    or cultural competitions must submit detailed proposals with estimated budgets through the 
                    Internal Quality Assurance Cell (IQAC).
                </p>
                <p>
                    Final approval rests with the Academic Council and the Office of the Principal.
                </p>
            </div>

            <div class="mvc-events-protocol-col">
                <h3>
                    <span aria-hidden="true">🏆</span>
                    <span>Flagship Campus Festivities</span>
                </h3>
                <p>
                    <strong>Annual Cultural Fest "TARANG"</strong>: Three days of literary, theatrical, and musical arts.<br>
                    <strong>Annual Athletics Tournament</strong>: Track and field championships across all academic faculties.
                </p>
                <p>
                    <strong>National Observances</strong>: Republic Day, Independence Day, National Youth Day, and Constitution Day.
                </p>
            </div>

            <div class="mvc-events-protocol-col">
                <h3>
                    <span aria-hidden="true">📞</span>
                    <span>Event Desk &amp; Inquiries</span>
                </h3>
                <p>
                    For inquiries regarding delegate registration, seminar submissions, or venue booking:
                </p>
                <p>
                    <a href="tel:06183796317" class="mvc-events-protocol-hotline">
                        <span aria-hidden="true">📞</span>
                        <span>06183-796317</span>
                    </a>
                </p>
                <p style="font-size: 12px; color: #64748B;">
                    Direct Communications: <a href="mailto:mvcollegebuxar@gmail.com" style="color: #002147; font-weight: 600;">mvcollegebuxar@gmail.com</a>
                </p>
            </div>

        </div>
    </section>
    <?php
}

/**
 * Client-Side Filtering Scripts
 */
function mvc_render_events_scripts() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput  = document.getElementById('mvcEventsSearch');
        var searchClear  = document.getElementById('mvcEventSearchClear');
        var catPills     = document.querySelectorAll('.mvc-event-pill');
        var eventsGrid   = document.getElementById('mvcEventsGrid');
        var emptyState   = document.getElementById('mvcEventsEmpty');
        var resetBtn     = document.getElementById('mvcResetEventsBtn');

        var currentCategory = 'all';
        var currentQuery    = '';
        var pageSize        = 9;
        var currentPage     = 1;
        var matchedCards    = [];

        // 1. Pagination Slice & UI Renderer
        function renderEventsPage() {
            var totalMatches = matchedCards.length;
            var totalPages = Math.ceil(totalMatches / pageSize) || 1;
            if (currentPage > totalPages) currentPage = 1;
            if (currentPage < 1) currentPage = 1;

            var startIndex = (currentPage - 1) * pageSize;
            var endIndex = Math.min(startIndex + pageSize, totalMatches);

            // Hide all cards initially
            var allCards = document.querySelectorAll('.mvc-event-card');
            allCards.forEach(function(c) { c.style.display = 'none'; });

            // Display only active slice
            for (var i = startIndex; i < endIndex; i++) {
                if (matchedCards[i]) matchedCards[i].style.display = '';
            }

            // Update Pagination Bar
            var pagBar      = document.getElementById('mvcEventsPagination');
            var pagStart    = document.getElementById('mvcEventPagStart');
            var pagEnd      = document.getElementById('mvcEventPagEnd');
            var pagTotal    = document.getElementById('mvcEventPagTotal');
            var pagControls = document.getElementById('mvcEventPagControls');

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
                        // Prev Button
                        html += '<button type="button" class="mvc-pag-btn mvc-pag-prev" ' + (currentPage === 1 ? 'disabled' : '') + ' data-page="' + (currentPage - 1) + '" aria-label="Previous Page">&#9664; Prev</button>';
                        
                        // Page Numbers with Intelligent Windowing
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

                        // Next Button
                        html += '<button type="button" class="mvc-pag-btn mvc-pag-next" ' + (currentPage === totalPages ? 'disabled' : '') + ' data-page="' + (currentPage + 1) + '" aria-label="Next Page">Next &#9654;</button>';
                        pagControls.innerHTML = html;

                        // Attach Page Click Handlers
                        pagControls.querySelectorAll('button[data-page]').forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                var targetPage = parseInt(this.getAttribute('data-page'), 10);
                                if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages && targetPage !== currentPage) {
                                    currentPage = targetPage;
                                    renderEventsPage();
                                    var mainContent = document.getElementById('events-main-content') || document.querySelector('.mvc-events-container');
                                    if (mainContent) {
                                        var topPos = mainContent.getBoundingClientRect().top + window.pageYOffset - 90;
                                        window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                                    }
                                }
                            });
                        });
                    }
                }
            }
        }

        // 2. Filter Logic
        function filterEvents(resetPage) {
            if (resetPage !== false) {
                currentPage = 1;
            }
            var cards = document.querySelectorAll('.mvc-event-card');
            matchedCards = [];

            cards.forEach(function(card) {
                var tags  = card.getAttribute('data-tags') || '';
                var title = card.getAttribute('data-title') || '';
                var desc  = card.getAttribute('data-desc') || '';

                var matchCat = (currentCategory === 'all') || tags.indexOf(currentCategory) !== -1;
                var matchQuery = true;
                if (currentQuery.length > 0) {
                    matchQuery = (title.indexOf(currentQuery) !== -1) || (desc.indexOf(currentQuery) !== -1);
                }

                if (matchCat && matchQuery) {
                    matchedCards.push(card);
                }
            });

            if (matchedCards.length === 0) {
                if (emptyState) emptyState.style.display = 'block';
                if (eventsGrid) eventsGrid.style.display = 'none';
                var pagBar = document.getElementById('mvcEventsPagination');
                if (pagBar) pagBar.style.display = 'none';
            } else {
                if (emptyState) emptyState.style.display = 'none';
                if (eventsGrid) eventsGrid.style.display = 'grid';
                renderEventsPage();
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentQuery = this.value.trim().toLowerCase();
                if (searchClear) searchClear.style.display = currentQuery.length > 0 ? 'block' : 'none';
                filterEvents(true);
            });
        }

        if (searchClear && searchInput) {
            searchClear.addEventListener('click', function() {
                searchInput.value = '';
                currentQuery = '';
                this.style.display = 'none';
                searchInput.focus();
                filterEvents(true);
            });
        }

        catPills.forEach(function(pill) {
            pill.addEventListener('click', function() {
                catPills.forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                currentCategory = this.getAttribute('data-filter') || 'all';
                filterEvents(true);
            });
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (searchClear) searchClear.style.display = 'none';
                currentQuery = '';
                currentCategory = 'all';
                catPills.forEach(function(p) {
                    if (p.getAttribute('data-filter') === 'all') p.classList.add('active');
                    else p.classList.remove('active');
                });
                filterEvents(true);
            });
        }

        // 3. Initial Load Execution
        filterEvents(true);
    });
    </script>
    <?php
}
