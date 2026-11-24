<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Notices & Official Circulars Repository Master Builder
 * Universal 10/10 Standard Directive Implementation
 * 
 * Features:
 * - Dual Action Principle: Immediate Direct PDF Access + Creative Gazette Memo View
 * - 100% Dynamic from 'notice'/'notices' CPT & ACF Free 'pdf_file'
 * - Instant Client-Side Search & Multi-Category Live Filtering
 * - Dual Presentation Modes: Modern Cards Grid & Official Gazette Ledger Table
 * - Authority Verification & Letterhead Gazette Protocol
 * - Mobile-First Responsive Architecture (Zero Horizontal Scroll)
 * - 100% Repository Self-Contained Assets (Zero Remote Dependencies)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main dispatcher entry point for Notices page
 */
function mvc_render_notices_page() {
    echo '<div class="mvc-notices-root">';
    mvc_render_notices_styles();
    mvc_render_notices_hero();
    
    echo '<main class="mvc-notices-container" id="notices-main-content">';
    mvc_render_notices_toolbar();
    mvc_render_notices_roster();
    mvc_render_notices_protocol();
    echo '</main>';
    
    mvc_render_notices_scripts();
    echo '</div>'; // .mvc-notices-root
}

/**
 * Shortcode handler for [mvc_notices_board]
 */
function mvc_render_notices_board_shortcode() {
    return mvc_cache_output('mvc_cached_notices_page_html', function() {
        ob_start();
        mvc_render_notices_page();
        return ob_get_clean();
    }, 43200);
}
add_shortcode('mvc_notices_board', 'mvc_render_notices_board_shortcode');

/**
 * Shared CSS Styles for Notices Repository
 */
function mvc_render_notices_styles() {
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #580000;
            --mvc-maroon-light: #9B111E;
            --mvc-gold: #D4AF37;
            --mvc-gold-light: #FDF8E8;
            --mvc-gold-metallic: linear-gradient(135deg, #FFE082 0%, #D4AF37 50%, #B8860B 100%);
            --mvc-slate: #1E293B;
            --mvc-slate-light: #64748B;
            --mvc-border: #E2E8F0;
            --mvc-bg-alt: #F8FAFC;
            --mvc-shadow: 0 4px 16px rgba(0, 33, 71, 0.08);
            --mvc-shadow-lg: 0 12px 32px rgba(0, 33, 71, 0.14);
        }

        /* Full Width Stretched Canvas */
        body.page-template-template-notices #page,
        body.page-template-template-notices .site-content,
        body.page-template-template-notices .ast-container,
        body.page-template-template-notices #content {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .mvc-notices-root {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--mvc-slate);
            background: #F1F5F9;
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
            min-height: 100vh;
        }

        .mvc-notices-root * {
            box-sizing: border-box;
        }

        /* -------------------------------------------------------------
           HERO BANNER SECTION
           ------------------------------------------------------------- */
        .mvc-notices-hero {
            background: linear-gradient(135deg, #001633 0%, #002147 60%, #4D0008 100%);
            color: #FFFFFF;
            padding: 48px 24px 44px;
            position: relative;
            border-bottom: 4px solid var(--mvc-gold);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .mvc-notices-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(212, 175, 55, 0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .mvc-notices-hero-inner {
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
        .mvc-notices-breadcrumbs {
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

        .mvc-notices-breadcrumbs a {
            color: #CBD5E1;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-notices-breadcrumbs a:hover {
            color: #FFD54F;
        }

        .mvc-notices-breadcrumbs span {
            color: #64748B;
        }

        .mvc-notices-hero-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 32px;
            flex-wrap: wrap;
        }

        .mvc-notices-title-col {
            flex: 1;
            min-width: 320px;
        }

        .mvc-notices-badge-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .mvc-notices-live-badge {
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

        .mvc-live-dot {
            width: 7px;
            height: 7px;
            background: #22C55E;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: mvcPulseLive 1.8s infinite;
        }

        @keyframes mvcPulseLive {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .mvc-notices-inst-badge {
            background: rgba(212, 175, 55, 0.18);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: #FFE082;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .mvc-notices-hero h1 {
            font-size: 34px;
            line-height: 1.2;
            margin: 0 0 8px 0;
            color: #FFFFFF;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .mvc-notices-hero-hindi {
            font-size: 20px;
            color: #F87171;
            margin: 0 0 12px 0;
            font-weight: 700;
            line-height: 1.3;
        }

        .mvc-notices-hero-sub {
            color: #CBD5E1;
            font-size: 14.5px;
            max-width: 720px;
            line-height: 1.5;
            margin: 0;
        }

        /* Stat Counter Cards */
        .mvc-notices-stats-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .mvc-notices-stat-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 12px 20px;
            border-radius: 8px;
            min-width: 140px;
            text-align: center;
        }

        .mvc-stat-val {
            font-size: 28px;
            font-weight: 800;
            color: #FFD54F;
            line-height: 1.1;
            margin-bottom: 2px;
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }

        .mvc-stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #E2E8F0;
            font-weight: 600;
        }

        /* -------------------------------------------------------------
           MAIN CONTENT CONTAINER
           ------------------------------------------------------------- */
        .mvc-notices-container {
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
        .mvc-notices-toolbar-card {
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

        .mvc-toolbar-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Search Input */
        .mvc-notices-search-wrap {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .mvc-notices-search-input {
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

        .mvc-notices-search-input:focus {
            background: #FFFFFF;
            border-color: var(--mvc-navy);
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
        }

        .mvc-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
        }

        .mvc-search-clear {
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

        .mvc-search-clear:hover {
            color: var(--mvc-maroon);
        }

        /* View Toggle (Cards vs Gazette Table) */
        .mvc-view-toggles {
            display: inline-flex;
            background: #F1F5F9;
            border-radius: 6px;
            padding: 3px;
            border: 1px solid var(--mvc-border);
        }

        .mvc-view-btn {
            background: transparent;
            border: none;
            padding: 8px 14px;
            font-size: 12.5px;
            font-weight: 700;
            color: #64748B;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .mvc-view-btn.active {
            background: var(--mvc-navy);
            color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(0, 33, 71, 0.2);
        }

        /* Category Filter Pills */
        .mvc-notices-category-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            border-top: 1px solid #F1F5F9;
            padding-top: 14px;
        }

        .mvc-cat-pill {
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

        .mvc-cat-pill:hover {
            background: #EDF2F7;
            color: var(--mvc-navy);
            border-color: #CBD5E1;
        }

        .mvc-cat-pill.active {
            background: var(--mvc-maroon);
            color: #FFFFFF;
            border-color: var(--mvc-maroon);
            box-shadow: 0 2px 8px rgba(128, 0, 0, 0.25);
        }

        .mvc-cat-pill .mvc-pill-count {
            background: rgba(0, 0, 0, 0.1);
            color: inherit;
            font-size: 11px;
            padding: 1px 7px;
            border-radius: 10px;
            font-weight: 700;
        }

        .mvc-cat-pill.active .mvc-pill-count {
            background: rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
        }

        /* Live Results Summary Bar */
        .mvc-results-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            font-size: 13px;
            color: #64748B;
            font-weight: 600;
        }

        .mvc-results-count strong {
            color: var(--mvc-navy);
        }

        /* -------------------------------------------------------------
           VIEW 1: NOTICES CARDS GRID
           ------------------------------------------------------------- */
        .mvc-notices-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
        }

        .mvc-notice-card {
            background: #FFFFFF;
            border-radius: 10px;
            border: 1px solid var(--mvc-border);
            box-shadow: var(--mvc-shadow);
            padding: 22px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .mvc-notice-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--mvc-shadow-lg);
            border-color: #CBD5E1;
        }

        .mvc-notice-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--mvc-maroon);
            transition: width 0.2s ease, background 0.2s ease;
        }

        .mvc-notice-card.notice-type-official::before {
            background: var(--mvc-navy);
        }

        .mvc-notice-card:hover::before {
            width: 6px;
            background: var(--mvc-gold);
        }

        /* Card Header */
        .mvc-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .mvc-card-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .mvc-notice-date-badge {
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: #334155;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .mvc-notice-tag {
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .mvc-notice-tag-students {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .mvc-notice-tag-officials {
            background: #EFF6FF;
            color: #1E40AF;
            border: 1px solid #BFDBFE;
        }

        .mvc-notice-memo-ref {
            font-size: 11px;
            color: #94A3B8;
            font-family: monospace;
            font-weight: 600;
        }

        /* Notice Title */
        .mvc-card-title {
            font-size: 16px;
            line-height: 1.4;
            color: var(--mvc-navy);
            margin: 0 0 16px 0;
            font-weight: 700;
            flex: 1;
        }

        .mvc-card-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-card-title a:hover {
            color: var(--mvc-maroon);
        }

        /* Dual Action Button Suite */
        .mvc-card-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid #F1F5F9;
            flex-wrap: wrap;
        }

        /* Direct PDF Action */
        .mvc-action-direct-pdf {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #7A0000 0%, #A30000 100%);
            color: #FFFFFF !important;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(122, 0, 0, 0.2);
            transition: all 0.2s ease;
        }

        .mvc-action-direct-pdf:hover {
            background: linear-gradient(135deg, #9B111E 0%, #C00000 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(122, 0, 0, 0.35);
        }

        /* Creative Detailed View */
        .mvc-action-view-details {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: var(--mvc-navy);
            text-decoration: none;
            font-size: 12.5px;
            font-weight: 700;
            padding: 8px 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .mvc-action-view-details:hover {
            background: #F8FAFC;
            color: var(--mvc-maroon);
            transform: translateX(3px);
        }

        /* -------------------------------------------------------------
           VIEW 2: OFFICIAL GAZETTE LEDGER TABLE
           ------------------------------------------------------------- */
        .mvc-notices-table-wrap {
            display: none; /* toggled via JS */
            background: #FFFFFF;
            border-radius: 10px;
            border: 1px solid var(--mvc-border);
            box-shadow: var(--mvc-shadow);
            overflow-x: auto;
        }

        .mvc-gazette-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            text-align: left;
            margin: 0;
        }

        .mvc-gazette-table thead th {
            background: var(--mvc-navy);
            color: #FFFFFF;
            padding: 14px 16px;
            font-weight: 700;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--mvc-gold);
            white-space: nowrap;
        }

        .mvc-gazette-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
            color: #334155;
        }

        .mvc-gazette-table tbody tr:hover td {
            background: #FFFDF7;
        }

        .mvc-table-title-link {
            color: var(--mvc-navy);
            font-weight: 700;
            text-decoration: none;
            display: block;
            margin-bottom: 4px;
            transition: color 0.2s;
        }

        .mvc-table-title-link:hover {
            color: var(--mvc-maroon);
        }

        .mvc-table-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        /* -------------------------------------------------------------
           EMPTY SEARCH STATE
           ------------------------------------------------------------- */
        .mvc-notices-empty-state {
            display: none;
            text-align: center;
            padding: 60px 20px;
            background: #FFFFFF;
            border-radius: 10px;
            border: 1px dashed #CBD5E1;
            margin-top: 20px;
        }

        .mvc-empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.6;
        }

        .mvc-empty-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px 0;
        }

        .mvc-empty-desc {
            color: #64748B;
            font-size: 14px;
            margin: 0 0 16px 0;
        }

        .mvc-empty-reset-btn {
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
           SIGNATORY & PROTOCOL FOOTER BLOCK
           ------------------------------------------------------------- */
        .mvc-notices-protocol-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid var(--mvc-border);
            padding: 32px;
            margin-top: 48px;
            box-shadow: var(--mvc-shadow);
            border-left: 5px solid var(--mvc-navy);
        }

        .mvc-protocol-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
        }

        .mvc-protocol-col h3 {
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

        .mvc-protocol-col p {
            font-size: 13.5px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 12px 0;
        }

        .mvc-protocol-hotline {
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
            .mvc-notices-hero {
                padding: 32px 18px 36px;
            }
            .mvc-notices-hero h1 {
                font-size: 24px;
            }
            .mvc-notices-hero-hindi {
                font-size: 16px;
            }
            .mvc-notices-stats-row {
                width: 100%;
            }
            .mvc-notices-stat-card {
                flex: 1;
                min-width: 100px;
                padding: 8px 12px;
            }
            .mvc-stat-val {
                font-size: 22px;
            }
            .mvc-notices-container {
                padding: 0 16px;
                margin-top: -16px;
            }
            .mvc-notices-toolbar-card {
                padding: 16px;
            }
            .mvc-notices-grid {
                grid-template-columns: 1fr;
            }
            .mvc-view-toggles {
                display: none; /* Force card view on mobile screens */
            }
            .mvc-card-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .mvc-action-direct-pdf,
            .mvc-action-view-details {
                text-align: center;
                justify-content: center;
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
function mvc_render_notices_hero() {
    $notices_count = intval(wp_count_posts('notice')->publish ?? 0);
    if (!$notices_count) {
        $notices_count = intval(wp_count_posts('notices')->publish ?? 0);
    }
    ?>
    <section class="mvc-notices-hero" aria-label="Notices Repository Banner">
        <div class="mvc-notices-hero-inner">
            
            <!-- Breadcrumbs -->
            <nav class="mvc-notices-breadcrumbs" aria-label="Breadcrumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                <span aria-hidden="true">&bull;</span>
                <span>Notices &amp; Circulars</span>
            </nav>

            <div class="mvc-notices-hero-grid">
                <div class="mvc-notices-title-col">
                    <div class="mvc-notices-badge-row">
                        <span class="mvc-notices-live-badge">
                            <span class="mvc-live-dot" aria-hidden="true"></span>
                            <span>OFFICIAL GAZETTE REPOSITORY</span>
                        </span>
                        <span class="mvc-notices-inst-badge">VKSU ARA CONSTITUENT UNIT</span>
                    </div>
                    <h1>Official Notices &amp; Administrative Circulars</h1>
                </div>

                <!-- Live Counter Stats -->
                <div class="mvc-notices-stats-row">
                    <div class="mvc-notices-stat-card">
                        <div class="mvc-stat-val"><?php echo esc_html($notices_count); ?></div>
                        <div class="mvc-stat-label">Published Notices</div>
                    </div>
                    <div class="mvc-notices-stat-card">
                        <div class="mvc-stat-val">100%</div>
                        <div class="mvc-stat-label">Direct PDF Access</div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <?php
}

/**
 * Render Interactive Toolbar & Live Filter Controls
 */
function mvc_render_notices_toolbar() {
    $notices = get_posts([
        'post_type'      => ['notice', 'notices'],
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    ]);
    
    $count_all = count($notices);
    $count_students = 0;
    $count_officials = 0;
    $count_exams = 0;
    $count_admission = 0;

    foreach ($notices as $n) {
        $t = strtolower($n->post_title);
        $terms = wp_get_object_terms($n->ID, 'audience');
        $slugs = !is_wp_error($terms) ? wp_list_pluck($terms, 'slug') : [];

        if (in_array('officials', $slugs) || strpos($t, 'faculty') !== false || strpos($t, 'meeting') !== false || strpos($t, 'office order') !== false) {
            $count_officials++;
        } else {
            $count_students++;
        }

        if (strpos($t, 'exam') !== false || strpos($t, 'परीक्षा') !== false || strpos($t, 'schedule') !== false) {
            $count_exams++;
        }
        if (strpos($t, 'admission') !== false || strpos($t, 'registration') !== false || strpos($t, 'marks sheet') !== false || strpos($t, 'नामांकन') !== false) {
            $count_admission++;
        }
    }
    ?>
    <section class="mvc-notices-toolbar-card" aria-label="Circular Filter & Search Controls">
        <div class="mvc-toolbar-top-row">
            
            <!-- Real-Time Search Box -->
            <div class="mvc-notices-search-wrap">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24" class="mvc-search-icon" aria-hidden="true"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" id="mvcNoticesSearch" class="mvc-notices-search-input" placeholder="Search notices by keyword, exam, subject, or memo number..." aria-label="Search circulars">
                <button type="button" id="mvcSearchClear" class="mvc-search-clear" aria-label="Clear search input">&times;</button>
            </div>

            <!-- View Toggles (Cards vs Gazette Table) -->
            <div class="mvc-view-toggles" role="group" aria-label="Presentation Layout">
                <button type="button" class="mvc-view-btn active" id="mvcToggleCards" title="Card Grid View">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 11h5V5H4v6zm0 7h5v-5H4v5zm6 0h5v-5h-5v5zm6 0h5v-5h-5v5zm-6-7h5V5h-5v6zm6-6v6h5V5h-5z"/></svg>
                    <span>Card View</span>
                </button>
                <button type="button" class="mvc-view-btn" id="mvcToggleTable" title="Gazette Ledger Table View">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg>
                    <span>Gazette Table</span>
                </button>
            </div>

        </div>

        <!-- Category Filter Pills -->
        <div class="mvc-notices-category-pills" role="tablist" aria-label="Filter by Category">
            <button type="button" class="mvc-cat-pill active" data-filter="all">
                <span>All Circulars</span>
                <span class="mvc-pill-count"><?php echo esc_html($count_all); ?></span>
            </button>
            <button type="button" class="mvc-cat-pill" data-filter="students">
                <span>Students</span>
                <span class="mvc-pill-count"><?php echo esc_html($count_students); ?></span>
            </button>
            <button type="button" class="mvc-cat-pill" data-filter="officials">
                <span>Faculty &amp; Officials</span>
                <span class="mvc-pill-count"><?php echo esc_html($count_officials); ?></span>
            </button>
            <button type="button" class="mvc-cat-pill" data-filter="exam">
                <span>Examinations</span>
                <span class="mvc-pill-count"><?php echo esc_html($count_exams); ?></span>
            </button>
            <button type="button" class="mvc-cat-pill" data-filter="admission">
                <span>Admissions &amp; CBCS</span>
                <span class="mvc-pill-count"><?php echo esc_html($count_admission); ?></span>
            </button>
        </div>
    </section>
    <?php
}

/**
 * Render Complete Dynamic Notices Roster (Cards & Table)
 */
function mvc_render_notices_roster() {
    $notices = get_posts([
        'post_type'      => ['notice', 'notices'],
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    if (empty($notices)) {
        echo '<div class="mvc-notices-empty-state" style="display:block;">';
        echo '<div class="mvc-empty-icon">📢</div>';
        echo '<h3 class="mvc-empty-title">No Circulars Published Currently</h3>';
        echo '<p class="mvc-empty-desc">Check back soon for university announcements and administrative orders.</p>';
        echo '</div>';
        return;
    }
    ?>
    <!-- Live Results Summary -->
    <div class="mvc-results-meta">
        <div class="mvc-results-count">
            Showing <strong id="mvcVisibleCount"><?php echo count($notices); ?></strong> of <strong><?php echo count($notices); ?></strong> Official Circulars
        </div>
        <div class="mvc-sort-label">
            Sorted by: <strong>Newest First</strong>
        </div>
    </div>

    <!-- VIEW 1: CARDS GRID -->
    <div class="mvc-notices-grid" id="mvcNoticesGrid">
        <?php
        foreach ($notices as $n) :
            $post_id   = $n->ID;
            $title     = get_the_title($post_id);
            $clean_title = wp_strip_all_tags($title);
            $date_str  = get_the_date('d M Y', $post_id);
            $permalink = get_permalink($post_id);
            
            // Resolve PDF URL
            $pdf = get_field('pdf_file', $post_id);
            $pdf_url = '';
            if (is_array($pdf) && !empty($pdf['url'])) $pdf_url = $pdf['url'];
            elseif (is_numeric($pdf) && (int)$pdf > 0) $pdf_url = wp_get_attachment_url((int)$pdf);
            elseif (is_string($pdf) && !empty($pdf) && $pdf !== '0') $pdf_url = $pdf;
            if (empty($pdf_url)) $pdf_url = get_post_meta($post_id, '_pdf_url', true);
            
            // Audience
            $terms = wp_get_object_terms($post_id, 'audience');
            $aud_slug = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->slug : 'students';
            $aud_name = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Students';
            
            // Check keywords for filter tags
            $t_lower = strtolower($clean_title);
            $is_exam = (strpos($t_lower, 'exam') !== false || strpos($t_lower, 'परीक्षा') !== false || strpos($t_lower, 'schedule') !== false);
            $is_admission = (strpos($t_lower, 'admission') !== false || strpos($t_lower, 'registration') !== false || strpos($t_lower, 'marks sheet') !== false || strpos($t_lower, 'नामांकन') !== false);

            $data_filter_tags = array();
            $data_filter_tags[] = 'all';
            $data_filter_tags[] = $aud_slug;
            if ($is_exam) $data_filter_tags[] = 'exam';
            if ($is_admission) $data_filter_tags[] = 'admission';

            $memo_ref = 'MVC/NOT/2026/' . str_pad($post_id, 4, '0', STR_PAD_LEFT);
            $direct_link = $pdf_url ? esc_url($pdf_url) : $permalink;
            $is_pdf_available = !empty($pdf_url);
            ?>
            <article class="mvc-notice-card notice-type-<?php echo esc_attr($aud_slug); ?>" 
                     data-tags="<?php echo esc_attr(implode(' ', $data_filter_tags)); ?>"
                     data-title="<?php echo esc_attr(strtolower($clean_title)); ?>"
                     data-memo="<?php echo esc_attr(strtolower($memo_ref)); ?>">
                
                <div class="mvc-card-top">
                    <div class="mvc-card-meta">
                        <span class="mvc-notice-date-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                            <span><?php echo esc_html($date_str); ?></span>
                        </span>
                        <span class="mvc-notice-tag mvc-notice-tag-<?php echo esc_attr($aud_slug); ?>">
                            <?php echo esc_html($aud_name); ?>
                        </span>
                    </div>
                    <span class="mvc-notice-memo-ref"><?php echo esc_html($memo_ref); ?></span>
                </div>

                <!-- Circular Title with Direct Access Click -->
                <h2 class="mvc-card-title">
                    <a href="<?php echo $direct_link; ?>" <?php if ($is_pdf_available) echo 'target="_blank" rel="noopener"'; ?> title="View <?php echo esc_attr($clean_title); ?>">
                        <?php echo esc_html($clean_title); ?>
                    </a>
                </h2>

                <!-- DUAL ACTION PRINCIPLE: Direct PDF Access + Creative Detailed View -->
                <div class="mvc-card-actions">
                    <?php if ($is_pdf_available) : ?>
                        <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-action-direct-pdf" title="Download or open verified PDF document">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                            <span>Direct PDF ↗</span>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url($permalink); ?>" class="mvc-action-direct-pdf" title="View Circular">
                            <span>Open Circular →</span>
                        </a>
                    <?php endif; ?>

                    <!-- Secondary Action: Rich Gazette Details Memo -->
                    <a href="<?php echo esc_url($permalink); ?>" class="mvc-action-view-details" title="View official letterhead gazette and authority signatories">
                        <span>Details &amp; Memo</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

            </article>
        <?php endforeach; ?>
    </div>

    <!-- VIEW 2: OFFICIAL GAZETTE LEDGER TABLE -->
    <div class="mvc-notices-table-wrap" id="mvcNoticesTableWrap">
        <table class="mvc-gazette-table" id="mvcGazetteTable" aria-label="Official College Notices Ledger">
            <thead>
                <tr>
                    <th style="width: 60px;">Sr.</th>
                    <th style="width: 120px;">Date</th>
                    <th style="width: 160px;">Memo Ref</th>
                    <th>Circular Subject / Notification Title</th>
                    <th style="width: 120px;">Category</th>
                    <th style="width: 190px;">Dual Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sr = 1;
                foreach ($notices as $n) :
                    $post_id   = $n->ID;
                    $title     = get_the_title($post_id);
                    $clean_title = wp_strip_all_tags($title);
                    $date_str  = get_the_date('d M Y', $post_id);
                    $permalink = get_permalink($post_id);

                    // Resolve PDF URL
                    $pdf = get_field('pdf_file', $post_id);
                    $pdf_url = '';
                    if (is_array($pdf) && !empty($pdf['url'])) $pdf_url = $pdf['url'];
                    elseif (is_numeric($pdf) && (int)$pdf > 0) $pdf_url = wp_get_attachment_url((int)$pdf);
                    elseif (is_string($pdf) && !empty($pdf) && $pdf !== '0') $pdf_url = $pdf;
                    if (empty($pdf_url)) $pdf_url = get_post_meta($post_id, '_pdf_url', true);

                    // Audience
                    $terms = wp_get_object_terms($post_id, 'audience');
                    $aud_slug = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->slug : 'students';
                    $aud_name = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : 'Students';

                    $t_lower = strtolower($clean_title);
                    $is_exam = (strpos($t_lower, 'exam') !== false || strpos($t_lower, 'परीक्षा') !== false || strpos($t_lower, 'schedule') !== false);
                    $is_admission = (strpos($t_lower, 'admission') !== false || strpos($t_lower, 'registration') !== false || strpos($t_lower, 'marks sheet') !== false);

                    $data_filter_tags = array();
                    $data_filter_tags[] = 'all';
                    $data_filter_tags[] = $aud_slug;
                    if ($is_exam) $data_filter_tags[] = 'exam';
                    if ($is_admission) $data_filter_tags[] = 'admission';

                    $memo_ref = 'MVC/NOT/2026/' . str_pad($post_id, 4, '0', STR_PAD_LEFT);
                    $direct_link = $pdf_url ? esc_url($pdf_url) : $permalink;
                    $is_pdf_available = !empty($pdf_url);
                    ?>
                    <tr class="mvc-table-row" 
                        data-tags="<?php echo esc_attr(implode(' ', $data_filter_tags)); ?>"
                        data-title="<?php echo esc_attr(strtolower($clean_title)); ?>"
                        data-memo="<?php echo esc_attr(strtolower($memo_ref)); ?>">
                        <td><strong><?php echo $sr++; ?></strong></td>
                        <td style="white-space: nowrap; color: #475569;"><?php echo esc_html($date_str); ?></td>
                        <td><code style="font-size: 11px; color: #002147; background: #F1F5F9; padding: 2px 6px; border-radius: 3px;"><?php echo esc_html($memo_ref); ?></code></td>
                        <td>
                            <a href="<?php echo $direct_link; ?>" <?php if ($is_pdf_available) echo 'target="_blank" rel="noopener"'; ?> class="mvc-table-title-link">
                                <?php echo esc_html($clean_title); ?>
                            </a>
                        </td>
                        <td>
                            <span class="mvc-notice-tag mvc-notice-tag-<?php echo esc_attr($aud_slug); ?>">
                                <?php echo esc_html($aud_name); ?>
                            </span>
                        </td>
                        <td>
                            <div class="mvc-table-actions">
                                <?php if ($is_pdf_available) : ?>
                                    <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-action-direct-pdf" style="padding: 4px 10px; font-size: 11.5px;">
                                        PDF ↗
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo esc_url($permalink); ?>" class="mvc-action-view-details" style="padding: 4px 8px; font-size: 11.5px;">
                                    Details →
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
    <nav class="mvc-pagination-bar" id="mvcNoticesPagination" aria-label="Notices pagination" style="display:none;">
        <div class="mvc-pagination-info">
            Showing <strong id="mvcPagStart">1</strong>–<strong id="mvcPagEnd">9</strong> of <strong id="mvcPagTotal">0</strong> Circulars
        </div>
        <div class="mvc-pagination-controls" id="mvcPagControls"></div>
    </nav>

    <!-- Empty Search State -->
    <div class="mvc-notices-empty-state" id="mvcNoticesEmpty">
        <div class="mvc-empty-icon">🔍</div>
        <h3 class="mvc-empty-title">No Matching Circulars Found</h3>
        <p class="mvc-empty-desc">We couldn't find any circulars matching your search query or selected category filter.</p>
        <button type="button" class="mvc-empty-reset-btn" id="mvcResetFiltersBtn">Reset Filters &amp; View All</button>
    </div>
    <?php
}

/**
 * Render Official Verification & Signatory Protocol Card
 */
function mvc_render_notices_protocol() {
    ?>
    <section class="mvc-notices-protocol-card" aria-label="Official Verification & Administrative Information">
        <div class="mvc-protocol-grid">
            
            <div class="mvc-protocol-col">
                <h3>
                    <span aria-hidden="true">🏛️</span>
                    <span>Institutional Gazette Protocol</span>
                </h3>
                <p>
                    All circulars and notifications published on this portal are official instruments issued under the 
                    statutory authority of Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar) and Veer Kunwar Singh University, Ara.
                </p>
                <p>
                    Affixed with official reference dispatch numbers and certified digital letterheads.
                </p>
            </div>

            <div class="mvc-protocol-col">
                <h3>
                    <span aria-hidden="true">✍️</span>
                    <span>Issuing Authorities</span>
                </h3>
                <p>
                    <strong>Office of the Principal</strong><br>
                    Prof. (Dr.) S. N. Singh, Principal<br>
                    Maharshi Vishwamitra Mahavidyalaya, Buxar
                </p>
                <p>
                    <strong>Convener, Examination &amp; Circulars Board</strong><br>
                    Charitravan Campus, Buxar – 802101 (Bihar)
                </p>
            </div>

            <div class="mvc-protocol-col">
                <h3>
                    <span aria-hidden="true">📞</span>
                    <span>Notice Board Helpdesk</span>
                </h3>
                <p>
                    For clarification regarding internal assessments, practical schedules, or admission notices, contact the campus office:
                </p>
                <p>
                    <a href="tel:06183796317" class="mvc-protocol-hotline">
                        <span aria-hidden="true">📞</span>
                        <span>06183-796317</span>
                    </a>
                </p>
                <p style="font-size: 12px; color: #64748B;">
                    Official Inquiries: <a href="mailto:mvcollegebuxar@gmail.com" style="color: #002147; font-weight: 600;">mvcollegebuxar@gmail.com</a>
                </p>
            </div>

        </div>
    </section>
    <?php
}

/**
 * Client-Side Filtering & Interactive Scripts
 */
function mvc_render_notices_scripts() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput   = document.getElementById('mvcNoticesSearch');
        var searchClear   = document.getElementById('mvcSearchClear');
        var catPills      = document.querySelectorAll('.mvc-cat-pill');
        var cardsGrid     = document.getElementById('mvcNoticesGrid');
        var tableWrap     = document.getElementById('mvcNoticesTableWrap');
        var toggleCards   = document.getElementById('mvcToggleCards');
        var toggleTable   = document.getElementById('mvcToggleTable');
        var visibleCount  = document.getElementById('mvcVisibleCount');
        var emptyState    = document.getElementById('mvcNoticesEmpty');
        var resetBtn      = document.getElementById('mvcResetFiltersBtn');

        var currentCategory = 'all';
        var currentQuery    = '';
        var pageSize        = 9;
        var currentPage     = 1;
        var matchedCards    = [];
        var matchedRows     = [];

        // 1. Pagination Slice & UI Renderer
        function renderNoticesPage() {
            var totalMatches = matchedCards.length;
            var totalPages = Math.ceil(totalMatches / pageSize) || 1;
            if (currentPage > totalPages) currentPage = 1;
            if (currentPage < 1) currentPage = 1;

            var startIndex = (currentPage - 1) * pageSize;
            var endIndex = Math.min(startIndex + pageSize, totalMatches);

            // Hide all cards/rows initially
            var allCards = document.querySelectorAll('.mvc-notice-card');
            var allRows  = document.querySelectorAll('.mvc-table-row');
            allCards.forEach(function(c) { c.style.display = 'none'; });
            allRows.forEach(function(r) { r.style.display = 'none'; });

            // Display only active slice
            for (var i = startIndex; i < endIndex; i++) {
                if (matchedCards[i]) matchedCards[i].style.display = '';
                if (matchedRows[i]) matchedRows[i].style.display = '';
            }

            // Update Pagination Bar
            var pagBar      = document.getElementById('mvcNoticesPagination');
            var pagStart    = document.getElementById('mvcPagStart');
            var pagEnd      = document.getElementById('mvcPagEnd');
            var pagTotal    = document.getElementById('mvcPagTotal');
            var pagControls = document.getElementById('mvcPagControls');

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
                                    renderNoticesPage();
                                    var mainContent = document.getElementById('notices-main-content') || document.querySelector('.mvc-notices-container');
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
        function filterNotices(resetPage) {
            if (resetPage !== false) {
                currentPage = 1;
            }
            var cards = document.querySelectorAll('.mvc-notice-card');
            var rows  = document.querySelectorAll('.mvc-table-row');
            matchedCards = [];
            matchedRows  = [];

            cards.forEach(function(card, idx) {
                var row = rows[idx];
                var tags  = card.getAttribute('data-tags') || '';
                var title = card.getAttribute('data-title') || '';
                var memo  = card.getAttribute('data-memo') || '';

                var matchCat = (currentCategory === 'all') || tags.indexOf(currentCategory) !== -1;
                var matchQuery = true;
                if (currentQuery.length > 0) {
                    matchQuery = (title.indexOf(currentQuery) !== -1) || (memo.indexOf(currentQuery) !== -1);
                }

                if (matchCat && matchQuery) {
                    matchedCards.push(card);
                    if (row) matchedRows.push(row);
                }
            });

            if (visibleCount) {
                visibleCount.textContent = matchedCards.length;
            }

            if (matchedCards.length === 0) {
                if (emptyState) emptyState.style.display = 'block';
                if (cardsGrid) cardsGrid.style.display = 'none';
                if (tableWrap) tableWrap.style.display = 'none';
                var pagBar = document.getElementById('mvcNoticesPagination');
                if (pagBar) pagBar.style.display = 'none';
            } else {
                if (emptyState) emptyState.style.display = 'none';
                if (toggleCards && toggleCards.classList.contains('active')) {
                    if (cardsGrid) cardsGrid.style.display = 'grid';
                    if (tableWrap) tableWrap.style.display = 'none';
                } else {
                    if (cardsGrid) cardsGrid.style.display = 'none';
                    if (tableWrap) tableWrap.style.display = 'block';
                }
                renderNoticesPage();
            }
        }

        // 2. Search Box Events
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentQuery = this.value.trim().toLowerCase();
                if (searchClear) {
                    searchClear.style.display = currentQuery.length > 0 ? 'block' : 'none';
                }
                filterNotices();
            });
        }

        if (searchClear && searchInput) {
            searchClear.addEventListener('click', function() {
                searchInput.value = '';
                currentQuery = '';
                this.style.display = 'none';
                searchInput.focus();
                filterNotices();
            });
        }

        // 3. Category Pill Clicks
        catPills.forEach(function(pill) {
            pill.addEventListener('click', function() {
                catPills.forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                currentCategory = this.getAttribute('data-filter') || 'all';
                filterNotices();
            });
        });

        // 4. View Toggles (Cards vs Table)
        if (toggleCards && toggleTable) {
            toggleCards.addEventListener('click', function() {
                toggleCards.classList.add('active');
                toggleTable.classList.remove('active');
                if (emptyState && emptyState.style.display !== 'block') {
                    if (cardsGrid) cardsGrid.style.display = 'grid';
                    if (tableWrap) tableWrap.style.display = 'none';
                    renderNoticesPage();
                }
            });

            toggleTable.addEventListener('click', function() {
                toggleTable.classList.add('active');
                toggleCards.classList.remove('active');
                if (emptyState && emptyState.style.display !== 'block') {
                    if (cardsGrid) cardsGrid.style.display = 'none';
                    if (tableWrap) tableWrap.style.display = 'block';
                    renderNoticesPage();
                }
            });
        }

        // 5. Reset Filters
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
                filterNotices(true);
            });
        }

        // 6. Initial Load Execution
        filterNotices(true);
    });
    </script>
    <?php
}
