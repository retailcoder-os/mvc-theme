<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya, Buxar - Best-in-Class Prestigious Institutional Homepage
 * 100% Dynamic Architecture • Zero Hardcoding • ACF Free Edition Compatible
 *
 * Major Sections:
 * 1. HERO SECTION: Cinematic Architectural Panoramas (Grand Full-Bleed Panorama, Regal Bilingual Typography, Executive Action Hub, Docked Stat Bar)
 * 2. LIVE ANNOUNCEMENT TICKER: Continuous Smooth Edge-to-Edge Marquee with 1-Click PDF Access
 * 3. ABOUT US & SIDDHASHRAM HERITAGE: Collegiate Narrative, 4 Institutional Pillars & 2x2 Metric Stage
 * 4. INSTITUTIONAL GOVERNANCE: Full-Width Executive Boardroom Pavilion (Spacious 420px Dignitary Cards for Desktop & Mobile)
 * 5. ACADEMIC FACULTIES & DEPARTMENTS: Master Faculty Guild (Clean, No Emojis, Bespoke SVGs, Accurate B.A./B.Sc./M.A./M.Sc. Degrees)
 * 6. ANNOUNCEMENTS HUB: Students & Officials Boards (Dual Action: 1-Click PDF + Circular Memo) + Events Switcher
 * 7. FAST-TRACK STUDENT SERVICES: 8 Clean Glassmorphism Utility Portals
 * 8. PRESS RELEASES: Newspaper Clippings Grid with Direct Zoom & Official Gazette View
 * 9. CAMPUS MULTIMEDIA: Photo Albums + Video Showcase
 * 10. TESTIMONIALS: Verified Student Quotes with 5-Star Ratings & Program Badges
 */

if (!defined('ABSPATH')) {
    exit;
}

function mvc_render_homepage() {
    return mvc_cache_output('mvc_cached_homepage_html', function() {
        ob_start();

    // Helper: Format ACF date_picker (YYYYMMDD to 'd M Y')
    $format_acf_date = function($date_raw, $post_id = null) {
        if (!empty($date_raw)) {
            $ts = strtotime($date_raw);
            if ($ts) return date('d M Y', $ts);
            $d = DateTime::createFromFormat('Ymd', $date_raw);
            if ($d) return $d->format('d M Y');
        }
        return $post_id ? get_the_date('d M Y', $post_id) : date('d M Y');
    };

    // Helper: Extract image URL from ACF image field (array/id/url) or featured image
    $get_img_url = function($acf_val, $post_id = null) {
        if (is_array($acf_val) && !empty($acf_val['url'])) return $acf_val['url'];
        if (is_string($acf_val) && !empty($acf_val) && $acf_val !== '0') return $acf_val;
        if (is_numeric($acf_val) && (int)$acf_val > 0) return wp_get_attachment_url((int)$acf_val);
        if ($post_id && has_post_thumbnail($post_id)) return get_the_post_thumbnail_url($post_id, 'full');
        return '';
    };

    // Helper: Resolve PDF URL
    $resolve_pdf_url = function($post_id) {
        $pdf = get_field('pdf_file', $post_id);
        $url = '';
        if (is_array($pdf) && !empty($pdf['url'])) $url = $pdf['url'];
        elseif (is_numeric($pdf) && (int)$pdf > 0) $url = wp_get_attachment_url((int)$pdf);
        elseif (is_string($pdf) && !empty($pdf) && $pdf !== '0') $url = $pdf;
        if (empty($url)) {
            $att_id = get_post_meta($post_id, '_pdf_attachment_id', true);
            if ($att_id) $url = wp_get_attachment_url((int)$att_id);
        }
        if (empty($url)) $url = get_post_meta($post_id, '_pdf_url', true);
        return $url;
    };

    // Helper: Extract YouTube 11-char Video ID
    $get_yt_id = function($url) {
        if (empty($url)) return '';
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return $m[1];
        }
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', trim($url))) {
            return trim($url);
        }
        return '';
    };

    // Global settings from Page ID 209
    $global_about = function_exists('get_field') ? get_field('about_us_excerpt', 209) : '';
    ?>
    <div class="mvc-portal-root">
        <style>
        /* ========================================================
           FULL-WIDTH EDGE-TO-EDGE CANVAS OVERRIDES FOR HOMEPAGE
           (Breaks out of Astra theme's 1200px container restriction)
           ======================================================== */
        body.home #content.site-content,
        body.page-template-template-home #content.site-content {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        body.home #content .ast-container,
        body.page-template-template-home #content .ast-container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        body.home #primary,
        body.page-template-template-home #primary,
        body.home #main,
        body.page-template-template-home #main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* ========================================================
           DESIGN SYSTEM (2026 PREMIER UNIVERSITY STANDARD)
           ======================================================== */
        :root {
            --mvc-navy-deep: #000B1A;
            --mvc-navy: #001A38;
            --mvc-navy-light: #002D5E;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #580000;
            --mvc-maroon-light: #9E1B1B;
            --mvc-gold: #D4AF37;
            --mvc-gold-light: #F7DF8C;
            --mvc-gold-dark: #A68218;
            --mvc-bg-body: #F8FAFC;
            --mvc-bg-card: #FFFFFF;
            --mvc-text-dark: #0F172A;
            --mvc-text-muted: #64748B;
            --mvc-border: #E2E8F0;
            --mvc-shadow-sm: 0 2px 8px rgba(0, 26, 56, 0.04);
            --mvc-shadow-md: 0 10px 28px -4px rgba(0, 26, 56, 0.08);
            --mvc-shadow-lg: 0 24px 48px -8px rgba(0, 26, 56, 0.14);
            --mvc-radius: 12px;
            --mvc-radius-lg: 18px;
            --mvc-transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mvc-portal-root {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--mvc-text-dark);
            background: var(--mvc-bg-body);
            line-height: 1.65;
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
        }

        .mvc-portal-root *, .mvc-portal-root *::before, .mvc-portal-root *::after {
            box-sizing: border-box;
        }

        .mvc-container {
            max-width: 100% !important;
            margin: 0 auto;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        /* Section Headings */
        .mvc-section-head {
            text-align: center;
            max-width: 820px;
            margin: 0 auto 48px;
        }
        .mvc-section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(128, 0, 0, 0.08);
            color: var(--mvc-maroon);
            padding: 6px 18px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 14px;
            border: 1px solid rgba(128, 0, 0, 0.2);
        }
        .mvc-section-badge.mvc-badge-gold {
            background: rgba(212, 175, 55, 0.14);
            color: var(--mvc-gold-dark);
            border-color: rgba(212, 175, 55, 0.38);
        }
        .mvc-section-title {
            font-size: clamp(28px, 3.2vw, 40px);
            font-weight: 800;
            color: var(--mvc-navy);
            line-height: 1.25;
            margin: 0 0 14px;
            letter-spacing: -0.5px;
        }
        .mvc-section-title.mvc-light {
            color: #ffffff;
        }
        .mvc-section-desc {
            font-size: 16px;
            color: var(--mvc-text-muted);
            line-height: 1.65;
            margin: 0;
        }
        .mvc-section-desc.mvc-light {
            color: rgba(255, 255, 255, 0.88);
        }

        /* Buttons */
        .mvc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 30px;
            font-size: 14.5px;
            font-weight: 700;
            border-radius: 999px;
            text-decoration: none;
            transition: var(--mvc-transition);
            cursor: pointer;
            border: none;
            line-height: 1;
            white-space: nowrap;
            max-width: 100%;
            box-sizing: border-box;
        }
        .mvc-btn-primary {
            background: linear-gradient(135deg, var(--mvc-maroon) 0%, var(--mvc-maroon-dark) 100%);
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(128, 0, 0, 0.3);
        }
        .mvc-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 26px rgba(128, 0, 0, 0.45);
            background: linear-gradient(135deg, var(--mvc-maroon-light) 0%, var(--mvc-maroon) 100%);
        }
        .mvc-btn-gold {
            background: linear-gradient(135deg, #F7DF8C 0%, #D4AF37 50%, #997521 100%);
            color: var(--mvc-navy-deep) !important;
            font-weight: 800;
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.35);
        }
        .mvc-btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(212, 175, 55, 0.55);
        }
        .mvc-btn-glass {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }
        .mvc-btn-glass:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: #ffffff;
            transform: translateY(-2px);
        }

        /* ========================================================
           1. BEST-IN-CLASS HERO: IMAGE BANNER CAROUSEL & COMMAND DECK
           ======================================================== */
        .mvc-hero-banner-sec {
            position: relative;
            width: 100%;
            background: #000B1A;
            overflow: hidden;
        }

        /* 1.1 Edge-to-Edge Banner Carousel Viewport (Below Text Section) */
        .mvc-hero-carousel-wrap {
            position: relative;
            width: 100%;
            overflow: hidden;
            background: #000814;
            border-bottom: 1px solid rgba(212, 175, 55, 0.35);
        }

        .mvc-hero-slider-track {
            display: flex;
            width: 100%;
            transition: transform 0.65s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: transform;
        }

        .mvc-hero-slide-item {
            flex: 0 0 100%;
            width: 100%;
            position: relative;
            line-height: 0;
            overflow: hidden;
        }

        .mvc-hero-slide-link {
            display: block;
            width: 100%;
            text-decoration: none;
            position: relative;
        }

        .mvc-hero-slide-picture {
            display: block;
            width: 100%;
        }

        .mvc-hero-slide-img {
            display: block;
            width: 100%;
            height: auto;
            aspect-ratio: 1500 / 500;
            object-fit: cover;
            object-position: center;
            max-height: 580px;
            transition: transform 6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .mvc-hero-slide-item.active .mvc-hero-slide-img {
            transform: scale(1.015);
        }

        /* Minimalist Subtle Vignette at bottom edge */
        .mvc-hero-slide-edge-shade {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 38px;
            background: linear-gradient(to top, rgba(0, 11, 26, 0.45) 0%, transparent 100%);
            pointer-events: none;
        }

        /* 1.2 Carousel Floating Navigation Arrows (Sleek, Compact & Theme-Proof) */
        .mvc-hero-nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            min-height: 38px !important;
            max-width: 38px !important;
            max-height: 38px !important;
            border-radius: 50% !important;
            background: rgba(0, 16, 38, 0.65) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1px solid rgba(212, 175, 55, 0.5) !important;
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35) !important;
            transition: all 0.25s cubic-bezier(0.25, 1, 0.5, 1) !important;
            padding: 0 !important;
            margin: 0 !important;
            line-height: 1 !important;
            outline: none !important;
            -webkit-appearance: none !important;
        }
        .mvc-hero-nav-arrow:hover {
            background: var(--mvc-maroon, #800000) !important;
            border-color: var(--mvc-gold, #D4AF37) !important;
            color: #ffffff !important;
            transform: translateY(-50%) scale(1.08) !important;
            box-shadow: 0 6px 18px rgba(212, 175, 55, 0.45) !important;
        }
        .mvc-hero-nav-prev {
            left: 14px;
        }
        .mvc-hero-nav-next {
            right: 14px;
        }
        .mvc-hero-nav-arrow svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            stroke-width: 2.4 !important;
            stroke: #ffffff !important;
            display: block !important;
            margin: 0 auto !important;
            transition: transform 0.2s ease;
        }
        .mvc-hero-nav-prev:hover svg {
            transform: translateX(-1.5px);
        }
        .mvc-hero-nav-next:hover svg {
            transform: translateX(1.5px);
        }

        /* 1.3 Bottom Floating Indicator Bar (Hidden per user directive) */
        .mvc-hero-indicators-deck,
        .mvc-hero-dots-wrap {
            display: none !important;
        }

        .mvc-hero-dots-wrap {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .mvc-hero-dot-btn {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.38);
            border: none;
            cursor: pointer;
            padding: 0;
            transition: all 0.35s ease;
        }
        .mvc-hero-dot-btn:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: scale(1.2);
        }
        .mvc-hero-dot-btn.active {
            width: 28px;
            background: var(--mvc-gold);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.8);
        }

        .mvc-hero-counter-tag {
            font-size: 12px;
            font-weight: 800;
            color: var(--mvc-gold-light);
            letter-spacing: 1px;
            padding-left: 8px;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            white-space: nowrap;
        }

        .mvc-hero-pause-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.75);
            cursor: pointer;
            padding: 2px 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }
        .mvc-hero-pause-btn:hover {
            color: var(--mvc-gold);
        }
        .mvc-hero-pause-btn svg {
            width: 14px;
            height: 14px;
        }

        /* 1.4 Institutional Fast-Track Command Deck (Above Slider) */
        .mvc-hero-command-deck {
            position: relative;
            z-index: 5;
            background: linear-gradient(180deg, #00122B 0%, #000B1A 100%);
            border-top: 1px solid rgba(212, 175, 55, 0.25);
            border-bottom: 2px solid var(--mvc-gold);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.45);
        }

        .mvc-hero-command-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 28px;
            align-items: center;
            padding: 22px 0 18px;
        }

        .mvc-hero-auth-badge {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .mvc-hero-auth-laurel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--mvc-gold);
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .mvc-hero-auth-radar {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22C55E;
            box-shadow: 0 0 10px #22C55E;
            animation: mvcRadarBlink 2s infinite;
        }
        .mvc-hero-auth-h1 {
            font-size: clamp(20px, 2.2vw, 29px);
            font-weight: 900;
            color: #ffffff;
            margin: 0;
            line-height: 1.22;
            letter-spacing: -0.4px;
        }
        .mvc-hero-auth-sub {
            font-size: clamp(13px, 1.3vw, 15px);
            color: var(--mvc-gold-light);
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .mvc-hero-auth-sub-dot {
            opacity: 0.5;
        }

        .mvc-hero-fast-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .mvc-fast-action-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }
        .mvc-fast-action-primary {
            background: linear-gradient(135deg, #B48200 0%, #D4AF37 50%, #F3E5AB 100%);
            color: #001228 !important;
            border: 1px solid rgba(255, 255, 255, 0.4);
            font-weight: 800;
        }
        .mvc-fast-action-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
            background: linear-gradient(135deg, #D4AF37 0%, #F3E5AB 100%);
        }
        .mvc-fast-action-secondary {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
            font-weight: 700;
        }
        .mvc-fast-action-secondary:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: var(--mvc-gold);
            color: var(--mvc-gold-light) !important;
            transform: translateY(-2px);
        }
        .mvc-fast-action-icon {
            font-size: 20px;
            line-height: 1;
            flex-shrink: 0;
        }
        .mvc-fast-action-text {
            display: flex;
            flex-direction: column;
        }
        .mvc-fast-action-title {
            font-size: 13.5px;
            font-weight: 800;
            line-height: 1.2;
        }
        .mvc-fast-action-hint {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 2px;
            font-weight: 500;
        }

        /* 1.5 Docked Institutional Stat Bar */
        .mvc-hero-stat-dock {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 16px 0;
            background: rgba(0, 8, 20, 0.6);
        }
        .mvc-hero-stats-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            text-align: center;
        }
        .mvc-stat-dock-item {
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .mvc-stat-dock-item:last-child {
            border-right: none;
        }
        .mvc-stat-dock-num {
            font-size: clamp(22px, 2.2vw, 28px);
            font-weight: 900;
            color: var(--mvc-gold);
            line-height: 1;
            font-family: var(--mvc-font-heading);
        }
        .mvc-stat-dock-label {
            text-align: left;
        }
        .mvc-stat-dock-label-top {
            font-size: 12px;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .mvc-stat-dock-label-sub {
            font-size: 11px;
            color: #94A3B8;
            margin-top: 2px;
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .mvc-hero-command-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                text-align: center;
            }
            .mvc-hero-auth-badge {
                align-items: center;
            }
            .mvc-hero-auth-sub {
                justify-content: center;
            }
            .mvc-hero-stats-strip {
                grid-template-columns: repeat(2, 1fr);
                row-gap: 16px;
            }
            .mvc-stat-dock-item:nth-child(2) {
                border-right: none;
            }
        }

        @media (max-width: 640px) {
            /* 1. Sleek Compact Command Deck */
            .mvc-hero-command-grid {
                padding: 14px 0 12px !important;
                gap: 14px !important;
            }
            .mvc-hero-auth-badge {
                gap: 6px !important;
                align-items: center !important;
                text-align: center !important;
            }
            .mvc-hero-auth-laurel {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                flex-wrap: wrap !important;
                gap: 6px !important;
                font-size: 10px !important;
                letter-spacing: 0.6px !important;
                padding: 3px 10px !important;
                background: rgba(212, 175, 55, 0.1) !important;
                border: 1px solid rgba(212, 175, 55, 0.25) !important;
                border-radius: 999px !important;
                margin: 0 auto !important;
                line-height: 1.3 !important;
                text-align: center !important;
            }
            .mvc-hero-auth-radar {
                width: 7px !important;
                height: 7px !important;
                flex-shrink: 0 !important;
            }
            .mvc-hero-auth-h1 {
                font-size: 18px !important;
                font-weight: 900 !important;
                letter-spacing: -0.2px !important;
                line-height: 1.25 !important;
                margin: 0 !important;
            }
            .mvc-hero-auth-sub {
                font-size: 11px !important;
                gap: 4px 6px !important;
                line-height: 1.35 !important;
                justify-content: center !important;
                margin: 0 !important;
            }

            /* 2. Fast-Track Action Cards: 2x2 App Launcher Matrix (Zero Trimming - Accommodated via Font Size) */
            .mvc-hero-fast-actions {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
            }
            .mvc-fast-action-card {
                padding: 8px 8px !important;
                gap: 8px !important;
                border-radius: 8px !important;
                min-height: auto !important;
                box-sizing: border-box !important;
                align-items: center !important;
            }
            .mvc-fast-action-icon {
                font-size: 16px !important;
                line-height: 1 !important;
                flex-shrink: 0 !important;
            }
            .mvc-fast-action-text {
                min-width: 0 !important;
                text-align: left !important;
                flex: 1 1 auto !important;
            }
            .mvc-fast-action-title {
                font-size: 11px !important;
                font-weight: 800 !important;
                line-height: 1.25 !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
                word-break: normal !important;
            }
            .mvc-fast-action-hint {
                font-size: 9px !important;
                line-height: 1.2 !important;
                margin-top: 2px !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
                display: block !important;
            }

            /* 3. Docked Institutional Stats: 2x2 Micro Badge Matrix (Zero Trimming - Accommodated via Font Size) */
            .mvc-hero-stat-dock {
                padding: 8px 0 10px !important;
                background: rgba(0, 8, 20, 0.75) !important;
            }
            .mvc-hero-stats-strip {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 6px !important;
            }
            .mvc-stat-dock-item {
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                background: rgba(255, 255, 255, 0.035) !important;
                border-radius: 7px !important;
                padding: 6px 8px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                gap: 6px !important;
                text-align: left !important;
            }
            .mvc-stat-dock-item:nth-child(2),
            .mvc-stat-dock-item:last-child {
                border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
                padding-bottom: 6px !important;
            }
            .mvc-stat-dock-num {
                font-size: 15px !important;
                line-height: 1 !important;
                font-weight: 900 !important;
                color: var(--mvc-gold) !important;
                flex-shrink: 0 !important;
            }
            .mvc-stat-dock-label {
                text-align: left !important;
                min-width: 0 !important;
                flex: 1 1 auto !important;
            }
            .mvc-stat-dock-label-top {
                font-size: 9.5px !important;
                line-height: 1.2 !important;
                font-weight: 800 !important;
                color: #ffffff !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
                text-transform: uppercase !important;
            }
            .mvc-stat-dock-label-sub {
                font-size: 8px !important;
                line-height: 1.15 !important;
                color: #94A3B8 !important;
                margin-top: 1px !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
                display: block !important;
            }

            /* 4. Carousel Floating Navigation Arrows & Indicators */
            .mvc-hero-nav-arrow {
                width: 32px !important;
                height: 32px !important;
                min-width: 32px !important;
                min-height: 32px !important;
                max-width: 32px !important;
                max-height: 32px !important;
            }
            .mvc-hero-nav-arrow svg {
                width: 13px !important;
                height: 13px !important;
                max-width: 13px !important;
                max-height: 13px !important;
            }
            .mvc-hero-nav-prev { left: 6px; }
            .mvc-hero-nav-next { right: 6px; }
            .mvc-hero-indicators-deck,
            .mvc-hero-dots-wrap {
                display: none !important;
            }

            /* 5. Mobile Banner Sizing (Expanded height to eliminate small appearance) */
            .mvc-hero-carousel-wrap {
                min-height: clamp(210px, 58vw, 290px) !important;
            }
            .mvc-hero-slider-track {
                height: 100% !important;
            }
            .mvc-hero-slide-item {
                height: clamp(210px, 58vw, 290px) !important;
            }
            .mvc-hero-slide-link,
            .mvc-hero-slide-picture {
                display: block !important;
                width: 100% !important;
                height: 100% !important;
            }
            .mvc-hero-slide-img {
                width: 100% !important;
                height: 100% !important;
                min-height: clamp(210px, 58vw, 290px) !important;
                max-height: 320px !important;
                aspect-ratio: auto !important;
                object-fit: cover !important;
                object-position: center center !important;
            }
        }

        @media (max-width: 400px) {
            .mvc-hero-auth-h1 {
                font-size: 16px !important;
            }
            .mvc-hero-auth-sub {
                font-size: 10px !important;
            }
            .mvc-hero-auth-laurel {
                font-size: 9px !important;
                padding: 2px 8px !important;
            }
            .mvc-fast-action-card {
                padding: 6px 6px !important;
                gap: 6px !important;
            }
            .mvc-fast-action-icon {
                font-size: 14px !important;
            }
            .mvc-fast-action-title {
                font-size: 10px !important;
                line-height: 1.2 !important;
            }
            .mvc-fast-action-hint {
                font-size: 8px !important;
                line-height: 1.15 !important;
            }
            .mvc-stat-dock-item {
                padding: 5px 6px !important;
                gap: 5px !important;
            }
            .mvc-stat-dock-num {
                font-size: 13.5px !important;
            }
            .mvc-stat-dock-label-top {
                font-size: 8.5px !important;
                line-height: 1.15 !important;
            }
            .mvc-stat-dock-label-sub {
                font-size: 7.5px !important;
                line-height: 1.1 !important;
            }
        }

        @media (max-width: 340px) {
            .mvc-fast-action-title {
                font-size: 9px !important;
            }
            .mvc-fast-action-hint {
                font-size: 7.5px !important;
            }
            .mvc-stat-dock-num {
                font-size: 12px !important;
            }
            .mvc-stat-dock-label-top {
                font-size: 7.5px !important;
            }
            .mvc-stat-dock-label-sub {
                font-size: 6.8px !important;
            }
        }

        /* ========================================================
           2. LIVE CONTINUOUS TICKER RIBBON (EDGE-TO-EDGE)
           ======================================================== */
        .mvc-live-ticker-ribbon {
            background: var(--mvc-navy-deep);
            border-bottom: 1px solid var(--mvc-border);
            padding: 12px 0;
            color: #ffffff;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            overflow: hidden;
            position: relative;
            z-index: 5;
            width: 100%;
        }
        .mvc-ticker-tag-badge {
            background: var(--mvc-maroon);
            color: var(--mvc-gold);
            font-size: 11.5px;
            font-weight: 800;
            padding: 6px 18px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-shrink: 0;
            margin-right: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mvc-ticker-track-wrap {
            overflow: hidden;
            white-space: nowrap;
            flex-grow: 1;
            min-width: 0;
            width: 100%;
        }
        .mvc-ticker-track-inner {
            display: inline-block;
            white-space: nowrap;
            animation: mvcScrollTicker 36s linear infinite;
        }
        .mvc-ticker-track-inner:hover {
            animation-play-state: paused;
        }
        @keyframes mvcScrollTicker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .mvc-ticker-item-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            margin-right: 40px;
            transition: color 0.2s ease;
        }
        .mvc-ticker-item-link:hover {
            color: var(--mvc-gold);
            text-decoration: underline;
        }

        /* ========================================================
           3. ABOUT US: EXPANSIVE HERITAGE & INSTITUTIONAL PILLARS
           ======================================================== */
        .mvc-about-sec {
            padding: 95px 0;
            background: #ffffff;
            width: 100%;
            overflow: hidden;
            box-sizing: border-box;
        }
        .mvc-about-split-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 55px;
            align-items: center;
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }
        .mvc-about-split-grid > * {
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
        }
        .mvc-about-narrative-text p {
            font-size: 16px;
            color: #334155;
            line-height: 1.75;
            margin-bottom: 20px;
            word-break: break-word;
        }
        .mvc-about-pillars-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin: 32px 0 38px;
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }
        .mvc-about-pillars-grid > * {
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
        }
        .mvc-pillar-box {
            background: var(--mvc-bg-body);
            border: 1px solid var(--mvc-border);
            border-radius: var(--mvc-radius);
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 14px;
            font-weight: 700;
            color: var(--mvc-navy);
            transition: var(--mvc-transition);
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
            word-break: break-word;
        }
        .mvc-pillar-box:hover {
            border-color: var(--mvc-maroon);
            transform: translateX(4px);
            background: #ffffff;
            box-shadow: var(--mvc-shadow-sm);
        }
        .mvc-pillar-box svg {
            color: var(--mvc-maroon);
            flex-shrink: 0;
        }

        .mvc-about-metrics-stage {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-deep) 100%);
            border-radius: var(--mvc-radius-lg);
            padding: 44px 38px;
            color: #ffffff;
            box-shadow: var(--mvc-shadow-lg);
            border: 2px solid rgba(212, 175, 55, 0.35);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }
        .mvc-about-metrics-stage::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 100% 0%, rgba(212, 175, 55, 0.2) 0%, transparent 60%);
            pointer-events: none;
        }
        .mvc-metrics-grid-2x2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            position: relative;
            z-index: 2;
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }
        .mvc-metric-unit {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: var(--mvc-radius);
            padding: 24px 18px;
            text-align: center;
            transition: var(--mvc-transition);
            min-width: 0;
            box-sizing: border-box;
        }
        .mvc-metric-unit:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(212, 175, 55, 0.5);
            transform: translateY(-4px);
        }
        .mvc-metric-num {
            font-size: clamp(26px, 3.2vw, 40px);
            font-weight: 800;
            color: var(--mvc-gold);
            line-height: 1.1;
            margin-bottom: 8px;
            letter-spacing: -1px;
            word-break: break-word;
        }
        .mvc-metric-label {
            font-size: 13.5px;
            color: #CBD5E1;
            font-weight: 600;
            margin: 0;
            line-height: 1.35;
        }

        /* ========================================================
           4. INSTITUTIONAL GOVERNANCE: WORLD-CLASS BOARDROOM PAVILION
           (Full-Width Desktop & Responsive 3-Card Architecture)
           ======================================================== */
        .mvc-governance-master-sec {
            padding: 95px 0;
            background: linear-gradient(180deg, #F8FAFC 0%, #EFF4F9 100%);
            border-top: 1px solid var(--mvc-border);
            border-bottom: 1px solid var(--mvc-border);
            width: 100%;
        }
        .mvc-governance-executive-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 36px;
            align-items: stretch;
        }

        .mvc-dignitary-master-card {
            background: #ffffff;
            border-radius: var(--mvc-radius-lg);
            border: 1px solid rgba(0, 26, 56, 0.08);
            box-shadow: var(--mvc-shadow-md);
            padding: 42px 32px 34px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: var(--mvc-transition);
        }
        .mvc-dignitary-master-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--mvc-shadow-lg);
            border-color: rgba(212, 175, 55, 0.5);
        }
        .mvc-dignitary-top-ribbon {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--mvc-maroon) 0%, var(--mvc-gold) 50%, var(--mvc-navy) 100%);
        }
        .mvc-dignitary-hierarchy-pill {
            display: inline-block;
            background: rgba(0, 26, 56, 0.05);
            color: var(--mvc-navy);
            border: 1px solid rgba(0, 26, 56, 0.12);
            padding: 5px 16px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .mvc-dignitary-portrait-stage {
            width: 154px;
            height: 154px;
            margin: 0 auto 24px;
            border-radius: 50%;
            padding: 6px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.6) 0%, rgba(128, 0, 0, 0.3) 50%, rgba(0, 26, 56, 0.4) 100%);
            box-shadow: 0 12px 28px rgba(0, 26, 56, 0.18);
            position: relative;
        }
        .mvc-dignitary-portrait-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            background: #E2E8F0;
            border: 3px solid #ffffff;
        }
        .mvc-dignitary-portrait-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .mvc-dignitary-master-card:hover .mvc-dignitary-portrait-inner img {
            transform: scale(1.05);
        }

        .mvc-dignitary-name {
            font-size: 20px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 8px;
            line-height: 1.3;
        }
        .mvc-dignitary-office-badge {
            display: inline-block;
            background: rgba(128, 0, 0, 0.07);
            color: var(--mvc-maroon);
            border: 1px solid rgba(128, 0, 0, 0.18);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.4px;
            margin-bottom: 18px;
            line-height: 1.35;
        }
        .mvc-dignitary-mandate-text {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin: 0 0 24px;
        }
        .mvc-dignitary-action-anchor {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 20px;
            background: var(--mvc-bg-body);
            border: 1px solid var(--mvc-border);
            border-radius: 999px;
            color: var(--mvc-navy);
            font-size: 13.5px;
            font-weight: 800;
            text-decoration: none;
            transition: var(--mvc-transition);
        }
        .mvc-dignitary-action-anchor:hover {
            background: var(--mvc-navy);
            color: #ffffff !important;
            border-color: var(--mvc-navy);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 26, 56, 0.2);
        }
        .mvc-dignitary-action-anchor.mvc-anchor-primary {
            background: linear-gradient(135deg, var(--mvc-maroon) 0%, var(--mvc-maroon-dark) 100%);
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 14px rgba(128, 0, 0, 0.25);
        }
        .mvc-dignitary-action-anchor.mvc-anchor-primary:hover {
            background: linear-gradient(135deg, var(--mvc-maroon-light) 0%, var(--mvc-maroon) 100%);
            box-shadow: 0 8px 20px rgba(128, 0, 0, 0.4);
        }

        /* ========================================================
           5. ACADEMIC FACULTIES & 15 DEPARTMENTS: MASTER FACULTY GUILD
           (Clean, Dignified, No Emojis, Accurate Academic Programs)
           ======================================================== */
        .mvc-faculties-master-sec {
            padding: 95px 0;
            background: #ffffff;
            width: 100%;
        }
        .mvc-faculty-switcher-deck {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 44px;
            flex-wrap: wrap;
        }
        .mvc-faculty-tab-btn {
            background: var(--mvc-bg-body);
            color: #334155;
            border: 1px solid var(--mvc-border);
            padding: 10px 24px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--mvc-transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .mvc-faculty-tab-btn:hover {
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
            transform: translateY(-2px);
        }
        .mvc-faculty-tab-btn.active {
            background: var(--mvc-navy);
            color: #ffffff;
            border-color: var(--mvc-navy);
            box-shadow: 0 6px 18px rgba(0, 26, 56, 0.22);
        }
        .mvc-faculty-tab-btn.active .mvc-tab-count-pill {
            background: var(--mvc-gold);
            color: var(--mvc-navy-deep);
        }
        .mvc-tab-count-pill {
            background: rgba(0, 26, 56, 0.08);
            color: #475569;
            font-size: 11.5px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 999px;
            transition: var(--mvc-transition);
        }

        /* Master Faculty Cards Grid (3 Columns on Desktop) */
        .mvc-faculty-cards-matrix {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .mvc-academic-guild-card {
            background: #ffffff;
            border: 1px solid var(--mvc-border);
            border-radius: var(--mvc-radius);
            padding: 30px 26px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--mvc-shadow-sm);
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
            transition: var(--mvc-transition);
        }
        .mvc-academic-guild-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--mvc-border);
            transition: background 0.3s ease;
        }
        .mvc-academic-guild-card:hover {
            transform: translateY(-6px);
            border-color: rgba(212, 175, 55, 0.5);
            box-shadow: var(--mvc-shadow-md);
        }
        .mvc-academic-guild-card:hover::before {
            background: linear-gradient(90deg, var(--mvc-maroon), var(--mvc-gold));
        }

        .mvc-guild-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }
        .mvc-guild-heraldic-glyph {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(0, 26, 56, 0.04);
            border: 1px solid rgba(0, 26, 56, 0.08);
            color: var(--mvc-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: var(--mvc-transition);
        }
        .mvc-academic-guild-card:hover .mvc-guild-heraldic-glyph {
            background: var(--mvc-maroon);
            color: #ffffff;
            border-color: var(--mvc-maroon);
            transform: scale(1.08);
        }
        .mvc-guild-faculty-tag {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--mvc-maroon);
            background: rgba(128, 0, 0, 0.06);
            padding: 4px 12px;
            border-radius: 6px;
            border: 1px solid rgba(128, 0, 0, 0.12);
        }

        .mvc-guild-dept-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
            line-height: 1.35;
        }
        .mvc-guild-hi-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #64748B;
            margin: 0 0 14px;
            display: block;
        }
        .mvc-guild-degrees-bar {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--mvc-navy);
            background: var(--mvc-bg-body);
            border: 1px solid var(--mvc-border);
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 14px;
        }
        .mvc-guild-focus-desc {
            font-size: 13.5px;
            color: #64748B;
            line-height: 1.55;
            margin: 0 0 20px;
        }
        .mvc-guild-portal-link {
            padding-top: 14px;
            border-top: 1px dashed var(--mvc-border);
            font-size: 13.5px;
            font-weight: 800;
            color: var(--mvc-maroon);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: color 0.2s ease;
        }
        .mvc-academic-guild-card:hover .mvc-guild-portal-link {
            color: var(--mvc-navy);
        }

        /* ========================================================
           6. ANNOUNCEMENTS, EXAMINATION & EVENTS HUB (DUAL ACTION)
           ======================================================== */
        .mvc-announcements-sec {
            padding: 95px 0;
            background: linear-gradient(135deg, #010A14 0%, #001A38 50%, #150005 100%);
            color: #ffffff;
            position: relative;
            width: 100%;
        }
        .mvc-announcements-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }
        .mvc-board-panel {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: var(--mvc-radius-lg);
            padding: 26px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }
        .mvc-board-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid rgba(212, 175, 55, 0.4);
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .mvc-board-title {
            font-size: 17.5px;
            font-weight: 800;
            color: var(--mvc-gold);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.3px;
        }
        .mvc-board-badge-count {
            background: rgba(255, 255, 255, 0.15);
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            color: #ffffff;
        }
        .mvc-board-list {
            height: 430px;
            overflow-y: auto;
            padding-right: 8px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .mvc-board-list::-webkit-scrollbar { width: 5px; }
        .mvc-board-list::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.35); border-radius: 4px; }

        .mvc-notice-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--mvc-radius);
            padding: 14px;
            transition: var(--mvc-transition);
        }
        .mvc-notice-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(212, 175, 55, 0.4);
            transform: translateX(4px);
        }
        .mvc-notice-meta-line {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }
        .mvc-notice-date-pill {
            font-size: 11px;
            color: #CBD5E1;
            font-weight: 700;
        }
        .mvc-badge-pinned-gold {
            background: var(--mvc-gold);
            color: var(--mvc-navy-deep);
            font-size: 9.5px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }
        .mvc-badge-new-pulse {
            background: #EF4444;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 3px;
            animation: mvcRadarBlink 1.5s infinite;
        }
        .mvc-badge-tender-status {
            background: rgba(16, 185, 129, 0.18);
            color: #6EE7B7;
            border: 1px solid rgba(16, 185, 129, 0.4);
            font-size: 9.5px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 3px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .mvc-tender-scope {
            font-size: 11.5px;
            color: #94A3B8;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .mvc-notice-headline {
            font-size: 13.5px;
            font-weight: 700;
            line-height: 1.45;
            margin: 0 0 10px;
        }
        .mvc-notice-headline a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .mvc-notice-headline a:hover {
            color: var(--mvc-gold-light);
            text-decoration: underline;
        }
        .mvc-notice-actions-dual {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mvc-dual-btn-pdf {
            background: rgba(212, 175, 55, 0.2);
            color: var(--mvc-gold-light);
            border: 1px solid rgba(212, 175, 55, 0.4);
            font-size: 11.5px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 4px;
            text-decoration: none;
            transition: var(--mvc-transition);
        }
        .mvc-dual-btn-pdf:hover {
            background: var(--mvc-gold);
            color: var(--mvc-navy-deep);
        }
        .mvc-dual-btn-memo {
            color: #94A3B8;
            font-size: 11.5px;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .mvc-dual-btn-memo:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .mvc-events-nav-tabs {
            display: flex;
            gap: 6px;
            margin-bottom: 14px;
        }
        .mvc-event-tab-btn {
            background: rgba(255, 255, 255, 0.1);
            color: #CBD5E1;
            border: none;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--mvc-transition);
        }
        .mvc-event-tab-btn.active {
            background: var(--mvc-gold);
            color: var(--mvc-navy-deep);
            font-weight: 800;
        }
        .mvc-event-card-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--mvc-radius);
            padding: 14px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: var(--mvc-transition);
        }
        .mvc-event-card-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(212, 175, 55, 0.4);
            transform: translateX(4px);
        }
        .mvc-event-cal-tile {
            width: 52px;
            height: 52px;
            background: rgba(212, 175, 55, 0.14);
            border: 1px solid rgba(212, 175, 55, 0.35);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            text-align: center;
            line-height: 1.1;
        }
        .mvc-event-cal-tile.mvc-cal-upcoming {
            background: rgba(212, 175, 55, 0.24);
            border-color: #D4AF37;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.25);
        }
        .mvc-badge-status-upcoming {
            background: rgba(212, 175, 55, 0.2);
            color: #F6E05E;
            border: 1px solid rgba(212, 175, 55, 0.5);
            font-size: 9.5px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 3px;
            letter-spacing: 0.4px;
        }
        .mvc-badge-status-concluded {
            background: rgba(148, 163, 184, 0.15);
            color: #94A3B8;
            border: 1px solid rgba(148, 163, 184, 0.25);
            font-size: 9.5px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 3px;
            letter-spacing: 0.3px;
        }
        .mvc-cal-month {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--mvc-gold-light);
            letter-spacing: 0.6px;
        }
        .mvc-cal-day {
            font-size: 18px;
            font-weight: 900;
            color: #ffffff;
            margin-top: 2px;
        }
        .mvc-event-details {
            flex-grow: 1;
        }
        .mvc-event-date-stamp {
            font-size: 11px;
            color: var(--mvc-gold);
            font-weight: 700;
            margin-bottom: 4px;
        }
        .mvc-event-title-text {
            font-size: 13.5px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 6px;
            line-height: 1.4;
        }
        .mvc-event-report-link {
            color: var(--mvc-gold-light);
            font-size: 11.5px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: var(--mvc-transition);
        }
        .mvc-event-report-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        /* Board Footer CTA */
        .mvc-board-view-all-cta {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px dashed rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--mvc-gold-light);
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            transition: var(--mvc-transition);
        }
        .mvc-board-view-all-cta:hover {
            color: #ffffff;
            text-decoration: underline;
            transform: translateX(2px);
        }

        /* Announcements Section Footer Action Row */
        .mvc-announcements-footer-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin-top: 48px;
            flex-wrap: wrap;
        }

        /* ========================================================
           7. STUDENT SERVICES & FAST-TRACK PORTALS
           ======================================================== */
        .mvc-services-sec {
            padding: 95px 0;
            background: var(--mvc-bg-body);
            width: 100%;
        }
        .mvc-services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        .mvc-service-card {
            background: #ffffff;
            border: 1px solid var(--mvc-border);
            border-radius: var(--mvc-radius);
            padding: 26px 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--mvc-shadow-sm);
            text-decoration: none;
            color: inherit;
            transition: var(--mvc-transition);
        }
        .mvc-service-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--mvc-shadow-md);
            border-color: var(--mvc-maroon);
        }
        .mvc-service-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(0, 26, 56, 0.05);
            color: var(--mvc-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            transition: var(--mvc-transition);
        }
        .mvc-service-card:hover .mvc-service-icon-box {
            background: var(--mvc-maroon);
            color: #ffffff;
            transform: scale(1.08);
        }
        .mvc-service-card-title {
            font-size: 16.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px;
            line-height: 1.3;
        }
        .mvc-service-card-desc {
            font-size: 13px;
            color: var(--mvc-text-muted);
            margin: 0 0 16px;
            line-height: 1.5;
        }
        .mvc-service-card-link {
            font-size: 12.5px;
            font-weight: 800;
            color: var(--mvc-maroon);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ========================================================
           8. PRESS RELEASES & SCANNED CLIPPINGS
           ======================================================== */
        .mvc-press-sec {
            padding: 95px 0;
            background: #ffffff;
            width: 100%;
        }
        .mvc-press-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        .mvc-press-card {
            background: #ffffff;
            border: 1px solid var(--mvc-border);
            border-radius: var(--mvc-radius);
            overflow: hidden;
            box-shadow: var(--mvc-shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: var(--mvc-transition);
        }
        .mvc-press-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--mvc-shadow-md);
            border-color: var(--mvc-maroon);
        }
        .mvc-press-clipping-frame {
            height: 185px;
            background: #000E24;
            overflow: hidden;
            position: relative;
        }
        .mvc-press-clipping-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .mvc-press-card:hover .mvc-press-clipping-frame img {
            transform: scale(1.06);
        }
        .mvc-press-card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }
        .mvc-press-date-line {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--mvc-gold-dark);
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .mvc-press-card-title {
            font-size: 14.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 16px;
            line-height: 1.4;
        }
        .mvc-press-actions-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px dashed var(--mvc-border);
            font-size: 12.5px;
            font-weight: 800;
        }

        /* ========================================================
           9. CAMPUS VIDEO SPOTLIGHT & YOUTUBE THEATER (10/10)
           ======================================================== */
        .mvc-multimedia-sec {
            padding: 95px 0;
            background: linear-gradient(135deg, #000E22 0%, #001A38 50%, #000B1A 100%);
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        .mvc-multimedia-sec::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -80px;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .mvc-badge-yt {
            background: rgba(239, 68, 68, 0.15);
            color: #FCA5A5;
            border-color: rgba(239, 68, 68, 0.35);
        }
        .mvc-live-dot-red {
            width: 8px;
            height: 8px;
            background: #EF4444;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 10px #EF4444;
            animation: mvcRadarBlink 1.4s infinite;
        }

        /* YouTube Channel Official Strip */
        .mvc-yt-channel-banner {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: var(--mvc-radius-lg);
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 36px;
            flex-wrap: wrap;
        }
        .mvc-yt-channel-meta {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .mvc-yt-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 0, 0, 0.12);
            border: 1px solid rgba(255, 0, 0, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .mvc-yt-channel-title {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mvc-yt-verified-check {
            color: #38BDF8;
            font-size: 13px;
        }
        .mvc-yt-channel-sub {
            font-size: 12.5px;
            color: #94A3B8;
            margin-top: 2px;
        }
        .mvc-yt-subscribe-btn {
            background: #CC0000;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 800;
            padding: 10px 22px;
            border-radius: 999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 18px rgba(204, 0, 0, 0.4);
            transition: var(--mvc-transition);
        }
        .mvc-yt-subscribe-btn:hover {
            background: #E60000;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(204, 0, 0, 0.6);
        }

        /* Theatrical Cinema Layout */
        .mvc-cinema-theater-grid {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 28px;
            align-items: stretch;
        }
        .mvc-cinema-screen-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--mvc-radius-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
        }
        .mvc-cinema-viewport {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #000000;
            overflow: hidden;
        }
        .mvc-cinema-viewport img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .mvc-cinema-overlay-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 10, 25, 0.75) 100%);
            pointer-events: none;
        }
        .mvc-cinema-play-orb {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #CC0000;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 0 35px rgba(204, 0, 0, 0.7);
            cursor: pointer;
            transition: var(--mvc-transition);
            z-index: 2;
        }
        .mvc-cinema-play-orb:hover {
            transform: translate(-50%, -50%) scale(1.15);
            background: #E60000;
            box-shadow: 0 0 50px rgba(255, 0, 0, 0.9);
        }
        .mvc-cinema-now-playing-pill {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--mvc-gold);
            color: var(--mvc-navy-deep);
            font-size: 11px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 999px;
            letter-spacing: 0.8px;
            z-index: 2;
        }
        .mvc-cinema-iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        .mvc-cinema-meta-info {
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }
        .mvc-cinema-tag-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 12px;
            color: var(--mvc-gold-light);
            font-weight: 700;
        }
        .mvc-cinema-cat-tag {
            background: rgba(212, 175, 55, 0.18);
            color: var(--mvc-gold-light);
            border: 1px solid rgba(212, 175, 55, 0.4);
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .mvc-cinema-title {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 10px;
            line-height: 1.35;
        }
        .mvc-cinema-desc {
            font-size: 14px;
            color: #CBD5E1;
            margin: 0 0 20px;
            line-height: 1.6;
        }
        .mvc-cinema-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Playlist Queue Rail */
        .mvc-cinema-playlist-panel {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--mvc-radius-lg);
            padding: 22px;
            display: flex;
            flex-direction: column;
        }
        .mvc-playlist-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 16px;
        }
        .mvc-playlist-heading {
            font-size: 15px;
            font-weight: 800;
            color: var(--mvc-gold);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mvc-playlist-count-pill {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
        }
        .mvc-playlist-scroll-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
            max-height: 520px;
            padding-right: 4px;
        }
        .mvc-playlist-scroll-list::-webkit-scrollbar { width: 4px; }
        .mvc-playlist-scroll-list::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.3); border-radius: 4px; }

        .mvc-playlist-item-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--mvc-radius);
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: var(--mvc-transition);
            text-align: left;
            width: 100%;
        }
        .mvc-playlist-item-card:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(212, 175, 55, 0.4);
            transform: translateX(4px);
        }
        .mvc-playlist-item-card.active {
            background: rgba(212, 175, 55, 0.14);
            border-color: var(--mvc-gold);
        }
        .mvc-playlist-thumb-box {
            position: relative;
            width: 100px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            background: #000000;
        }
        .mvc-playlist-thumb-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .mvc-playlist-thumb-play-tag {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: background 0.2s ease;
        }
        .mvc-playlist-item-card:hover .mvc-playlist-thumb-play-tag {
            background: rgba(204, 0, 0, 0.7);
        }
        .mvc-playlist-item-title {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 4px;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .mvc-playlist-item-cat {
            font-size: 11px;
            color: var(--mvc-gold);
            font-weight: 600;
        }

        /* ========================================================
           10. VERIFIED STUDENT TESTIMONIALS (COMPACT & SLEEK 10/10)
           ======================================================== */
        .mvc-testimonials-sec {
            padding: 46px 0 36px;
            background: #FAF9F6;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        .mvc-testi-head {
            text-align: center;
            margin-bottom: 20px;
        }
        .mvc-testi-head .mvc-section-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 4px 0 6px;
            letter-spacing: -0.3px;
        }
        .mvc-testi-head .mvc-section-desc {
            font-size: 13.5px;
            color: #475569;
            max-width: 620px;
            margin: 0 auto;
            line-height: 1.5;
        }
        .mvc-testi-slider-wrapper {
            position: relative;
            width: 100%;
            margin-top: 6px;
        }
        .mvc-testi-viewport {
            width: 100%;
            overflow: hidden;
            padding: 10px 4px 14px;
        }
        .mvc-testi-track {
            display: flex;
            gap: 20px;
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }
        .mvc-testi-slide {
            flex: 0 0 calc((100% - 40px) / 3); /* Exactly 3 cards in 1 row on desktop */
            min-width: 0;
            display: flex;
            box-sizing: border-box;
        }
        .mvc-testi-card {
            background: #ffffff;
            border: 1px solid rgba(0, 33, 71, 0.08);
            border-top: 3px solid transparent;
            border-radius: 12px;
            padding: 18px 18px 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 3px 12px rgba(0, 26, 56, 0.04);
            position: relative;
            transition: var(--mvc-transition);
            width: 100%;
        }
        .mvc-testi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 26, 56, 0.09);
            border-top-color: var(--mvc-gold);
        }
        .mvc-testi-quote-watermark {
            position: absolute;
            top: 8px;
            right: 12px;
            font-family: Georgia, serif;
            font-size: 42px;
            line-height: 1;
            color: rgba(212, 175, 55, 0.15);
            pointer-events: none;
        }
        .mvc-testi-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .mvc-testi-stars-row {
            color: #F59E0B;
            font-size: 13px;
            letter-spacing: 1.5px;
        }
        .mvc-testi-verified-badge {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .mvc-testi-quote-text {
            font-size: 13.5px;
            color: #334155;
            line-height: 1.55;
            margin: 0 0 12px;
            font-style: italic;
            position: relative;
            z-index: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 60px;
        }
        .mvc-testi-author-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 10px;
            border-top: 1px dashed #E2E8F0;
        }
        .mvc-testi-avatar-frame {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: #E2E8F0;
            border: 2px solid var(--mvc-gold);
            box-shadow: 0 2px 6px rgba(0, 26, 56, 0.08);
            flex-shrink: 0;
        }
        .mvc-testi-avatar-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .mvc-testi-name {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            line-height: 1.2;
        }
        .mvc-testi-course-pill {
            display: inline-block;
            background: rgba(0, 33, 71, 0.06);
            color: var(--mvc-maroon);
            font-size: 10.5px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            margin-top: 2px;
        }
        .mvc-testi-batch-sub {
            font-size: 10.5px;
            color: var(--mvc-text-muted);
            margin-top: 1px;
        }

        /* Flanking Side Floating Arrow Buttons (Compact) */
        button.mvc-testi-arrow-btn,
        .mvc-testi-arrow-btn {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 10 !important;
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
            border-radius: 50% !important;
            background: #ffffff !important;
            border: 1.5px solid #002147 !important;
            color: #002147 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.14) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            padding: 0 !important;
            margin: 0 !important;
            outline: none !important;
        }
        button.mvc-testi-arrow-btn.mvc-prev,
        .mvc-testi-arrow-btn.mvc-prev {
            left: -16px !important;
        }
        button.mvc-testi-arrow-btn.mvc-next,
        .mvc-testi-arrow-btn.mvc-next {
            right: -16px !important;
        }
        button.mvc-testi-arrow-btn svg,
        .mvc-testi-arrow-btn svg {
            width: 18px !important;
            height: 18px !important;
            fill: #002147 !important;
            color: #002147 !important;
            display: block !important;
            transition: fill 0.25s ease !important;
            pointer-events: none !important;
        }
        button.mvc-testi-arrow-btn svg path,
        .mvc-testi-arrow-btn svg path {
            fill: #002147 !important;
            transition: fill 0.25s ease !important;
        }
        button.mvc-testi-arrow-btn:hover,
        .mvc-testi-arrow-btn:hover {
            background: #002147 !important;
            border-color: #002147 !important;
            transform: translateY(-50%) scale(1.08) !important;
            box-shadow: 0 8px 18px rgba(0, 33, 71, 0.22) !important;
        }
        button.mvc-testi-arrow-btn:hover svg,
        .mvc-testi-arrow-btn:hover svg,
        button.mvc-testi-arrow-btn:hover svg path,
        .mvc-testi-arrow-btn:hover svg path {
            fill: #D4AF37 !important;
            color: #D4AF37 !important;
        }

        /* Clean Pagination Dots Row Below Cards */
        .mvc-testi-dots-row {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 7px !important;
            margin: 12px auto 14px !important;
        }
        .mvc-testi-dot {
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            background: #CBD5E1 !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            padding: 0 !important;
            margin: 0 !important;
            outline: none !important;
        }
        .mvc-testi-dot.active {
            background: var(--mvc-maroon) !important;
            width: 22px !important;
            border-radius: 999px !important;
        }
        .mvc-testi-dot:hover:not(.active) {
            background: #94A3B8 !important;
        }

        .mvc-testi-cta-wrap {
            text-align: center;
            margin-top: 0;
        }
        .mvc-testi-cta-wrap .mvc-btn {
            padding: 8px 18px;
            font-size: 13px;
        }

        @media (max-width: 992px) {
            .mvc-testi-slide { flex: 0 0 calc((100% - 20px) / 2); } /* 2 columns on tablet */
            button.mvc-testi-arrow-btn.mvc-prev, .mvc-testi-arrow-btn.mvc-prev { left: -10px !important; }
            button.mvc-testi-arrow-btn.mvc-next, .mvc-testi-arrow-btn.mvc-next { right: -10px !important; }
        }
        @media (max-width: 640px) {
            .mvc-testi-slide { flex: 0 0 100%; } /* 1 column on mobile */
            button.mvc-testi-arrow-btn, .mvc-testi-arrow-btn {
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                min-height: 36px !important;
                background: rgba(255, 255, 255, 0.94) !important;
                backdrop-filter: blur(8px) !important;
            }
            button.mvc-testi-arrow-btn.mvc-prev, .mvc-testi-arrow-btn.mvc-prev { left: 4px !important; }
            button.mvc-testi-arrow-btn.mvc-next, .mvc-testi-arrow-btn.mvc-next { right: 4px !important; }
        }

        /* ========================================================
           11. IMPORTANT WEBSITES (महत्वपूर्ण वेबसाइटें 10/10 1-ROW SLIDER)
           ======================================================== */
        .mvc-important-web-sec {
            padding: 46px 0 52px;
            background: #ffffff;
            border-top: 1px solid rgba(0, 33, 71, 0.08);
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        .mvc-important-web-head {
            text-align: center;
            margin-bottom: 26px;
        }
        .mvc-important-web-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 4px 0 8px;
            letter-spacing: -0.3px;
        }
        .mvc-divider-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 4px;
        }
        .mvc-web-divider {
            max-width: 180px;
            height: auto;
            display: inline-block;
        }
        .mvc-web-slider-wrapper {
            position: relative;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 54px;
            box-sizing: border-box;
        }
        .mvc-web-viewport {
            width: 100%;
            overflow: hidden;
            padding: 12px 4px 16px;
        }
        .mvc-web-track {
            display: flex;
            gap: 20px;
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
            flex-wrap: nowrap;
            align-items: center;
        }
        .mvc-web-slide {
            flex: 0 0 calc((100% - (20px * 5)) / 6); /* Exactly 6 logos in 1 single row on desktop */
            min-width: 0;
            box-sizing: border-box;
            display: flex;
        }
        .mvc-partner-logo-item {
            background: #ffffff;
            border: 1px solid rgba(0, 33, 71, 0.08);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 84px;
            width: 100%;
            box-sizing: border-box;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(0, 26, 56, 0.04);
            position: relative;
        }
        .mvc-partner-logo-item img {
            max-height: 56px;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            transition: transform 0.3s ease;
        }
        .mvc-partner-logo-item:hover {
            transform: translateY(-4px);
            border-color: var(--mvc-gold);
            box-shadow: 0 8px 24px rgba(0, 33, 71, 0.12);
        }
        .mvc-partner-logo-item:hover img {
            transform: scale(1.08);
        }

        /* Sleek Flanking Circular Floating Arrows */
        button.mvc-web-arrow-btn,
        .mvc-web-arrow-btn {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 10 !important;
            width: 38px !important;
            height: 38px !important;
            min-width: 38px !important;
            min-height: 38px !important;
            border-radius: 50% !important;
            background: #ffffff !important;
            border: 1.5px solid #002147 !important;
            color: #002147 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.12) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            padding: 0 !important;
            margin: 0 !important;
            outline: none !important;
        }
        button.mvc-web-arrow-btn.mvc-prev,
        .mvc-web-arrow-btn.mvc-prev {
            left: 4px !important;
        }
        button.mvc-web-arrow-btn.mvc-next,
        .mvc-web-arrow-btn.mvc-next {
            right: 4px !important;
        }
        button.mvc-web-arrow-btn svg,
        .mvc-web-arrow-btn svg {
            width: 20px !important;
            height: 20px !important;
            fill: #002147 !important;
            color: #002147 !important;
            display: block !important;
            transition: fill 0.25s ease !important;
            pointer-events: none !important;
        }
        button.mvc-web-arrow-btn svg path,
        .mvc-web-arrow-btn svg path {
            fill: #002147 !important;
            transition: fill 0.25s ease !important;
        }
        button.mvc-web-arrow-btn:hover,
        .mvc-web-arrow-btn:hover {
            background: #002147 !important;
            border-color: #002147 !important;
            transform: translateY(-50%) scale(1.08) !important;
            box-shadow: 0 8px 20px rgba(0, 33, 71, 0.22) !important;
        }
        button.mvc-web-arrow-btn:hover svg,
        .mvc-web-arrow-btn:hover svg,
        button.mvc-web-arrow-btn:hover svg path,
        .mvc-web-arrow-btn:hover svg path {
            fill: #D4AF37 !important;
            color: #D4AF37 !important;
        }

        @media (max-width: 1199px) {
            .mvc-web-slide { flex: 0 0 calc((100% - (18px * 4)) / 5); } /* 5 logos on laptop */
            .mvc-web-track { gap: 18px; }
        }
        @media (max-width: 991px) {
            .mvc-web-slide { flex: 0 0 calc((100% - (16px * 3)) / 4); } /* 4 logos on tablet */
            .mvc-web-track { gap: 16px; }
        }
        @media (max-width: 767px) {
            .mvc-important-web-sec { padding: 36px 0 40px; }
            .mvc-important-web-title { font-size: 22px; }
            .mvc-web-slider-wrapper { padding: 0 38px; }
            .mvc-web-track { gap: 12px; }
            .mvc-web-slide { flex: 0 0 calc((100% - (12px * 2)) / 3); } /* 3 logos on mobile */
            .mvc-partner-logo-item { height: 74px; padding: 8px 10px; }
            .mvc-partner-logo-item img { max-height: 46px; }
            button.mvc-web-arrow-btn, .mvc-web-arrow-btn {
                width: 32px !important;
                height: 32px !important;
                min-width: 32px !important;
                min-height: 32px !important;
            }
            button.mvc-web-arrow-btn.mvc-prev, .mvc-web-arrow-btn.mvc-prev { left: 0px !important; }
            button.mvc-web-arrow-btn.mvc-next, .mvc-web-arrow-btn.mvc-next { right: 0px !important; }
        }
        @media (max-width: 480px) {
            .mvc-web-slider-wrapper { padding: 0 32px; }
            .mvc-web-slide { flex: 0 0 calc((100% - 10px) / 2); } /* 2 logos on small mobile */
            .mvc-web-track { gap: 10px; }
        }

        /* ========================================================
           RESPONSIVE BREAKPOINTS (360px - 1200px)
           ======================================================== */
        @media (max-width: 1200px) {
            .mvc-faculty-cards-matrix { grid-template-columns: repeat(2, 1fr); }
            .mvc-press-cards-grid { grid-template-columns: repeat(2, 1fr); }
            .mvc-services-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 992px) {
            .mvc-about-split-grid { grid-template-columns: 1fr; gap: 36px; }
            .mvc-about-sec { padding: 65px 0; }
            .mvc-about-metrics-stage { padding: 36px 28px; }
            .mvc-metrics-grid-2x2 { gap: 18px; }
            .mvc-governance-executive-grid { grid-template-columns: 1fr; gap: 28px; }
            .mvc-announcements-grid { grid-template-columns: 1fr; }
            .mvc-cinema-theater-grid { grid-template-columns: 1fr; }
            .mvc-testi-cards-grid { grid-template-columns: 1fr; }
            .mvc-hero-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .mvc-stat-pill-col { border-right: none; }
        }
        @media (max-width: 768px) {
            .mvc-hero-cinema-sec { padding: 60px 0 160px; min-height: 70vh; }
            .mvc-faculty-cards-matrix { grid-template-columns: 1fr; }
            .mvc-press-cards-grid { grid-template-columns: 1fr; }
            .mvc-services-grid { grid-template-columns: 1fr; }
            .mvc-about-sec { padding: 48px 0; }
            .mvc-about-pillars-grid { grid-template-columns: 1fr; gap: 12px; margin: 24px 0 28px; width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; }
            .mvc-pillar-box { padding: 14px 16px; font-size: 13.5px; width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; word-break: break-word; }
            .mvc-about-metrics-stage { padding: 26px 18px; border-radius: var(--mvc-radius); }
            .mvc-metrics-grid-2x2 { gap: 12px; }
            .mvc-metric-unit { padding: 16px 10px; }
            .mvc-metric-num { font-size: clamp(22px, 5.5vw, 30px); }
            .mvc-metric-label { font-size: 12px; }
            .mvc-btn {
                max-width: 100% !important;
                white-space: normal !important;
                text-align: center !important;
                padding: 12px 18px !important;
                line-height: 1.35 !important;
                box-sizing: border-box !important;
            }
            .mvc-about-sec .mvc-btn {
                display: flex !important;
                width: 100% !important;
                justify-content: center !important;
            }
        }
        @media (max-width: 640px) {
            .mvc-btn {
                padding: 11px 16px !important;
                font-size: 13.5px !important;
            }
        }
        @media (max-width: 480px) {
            .mvc-gallery-grid-mini { grid-template-columns: 1fr; }
            .mvc-about-sec { padding: 36px 0; }
            .mvc-about-metrics-stage { padding: 18px 12px; }
            .mvc-metrics-grid-2x2 { gap: 8px; }
            .mvc-metric-unit { padding: 12px 6px; }
            .mvc-metric-num { font-size: 20px; letter-spacing: 0; }
            .mvc-metric-label { font-size: 11px; }
            .mvc-pillar-box { padding: 12px 14px; font-size: 13px; gap: 10px; }
        }
        @media (max-width: 360px) {
            .mvc-metrics-grid-2x2 { grid-template-columns: 1fr; }
        }
        </style>

        <!-- ========================================================
             1. BEST-IN-CLASS HERO: IMAGE BANNER CAROUSEL & COMMAND DECK
             ======================================================== -->
        <section class="mvc-hero-banner-sec" id="mvcHeroSec" aria-label="Maharshi Vishwamitra Mahavidyalaya Hero Banner Carousel">
            
            <?php
            // Pull high-res authentic slider images dynamically
            $slider_query = new WP_Query(array(
                'post_type'      => 'slider',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC'
            ));

            $slides_data = array();
            if ($slider_query->have_posts()) :
                while ($slider_query->have_posts()) : $slider_query->the_post();
                    $s_id    = get_the_ID();
                    $s_title = get_the_title();
                    $s_tag   = get_field('slide_tagline') ?: '';
                    $s_btn   = get_field('slide_btn_text') ?: '';
                    $s_url   = get_field('slide_btn_url') ?: '';
                    
                    $s_img = $get_img_url(get_field('slide_image'), $s_id);
                    if (!$s_img) $s_img = get_the_post_thumbnail_url($s_id, 'full');
                    if (!$s_img) $s_img = '/wp-content/uploads/sliders/slider-1.webp';
                    
                    // Derive clean WebP and fallback image paths
                    $webp_url = preg_replace('/\.(png|jpe?g)$/i', '.webp', $s_img);
                    $png_url  = preg_replace('/\.webp$/i', '.png', $s_img);

                    // Strictly strip any host/protocol so images load root-relatively across all domains/live links/tunnels
                    $webp_url = preg_replace('#^https?://[^/]+#i', '', $webp_url);
                    $png_url  = preg_replace('#^https?://[^/]+#i', '', $png_url);
                    if (strpos($webp_url, '/') !== 0) {
                        $webp_url = '/' . ltrim($webp_url, '/');
                    }
                    if (strpos($png_url, '/') !== 0) {
                        $png_url = '/' . ltrim($png_url, '/');
                    }

                    $slides_data[] = array(
                        'id'       => $s_id,
                        'title'    => $s_title,
                        'tag'      => $s_tag,
                        'img'      => $png_url,
                        'webp'     => $webp_url,
                        'url'      => $s_url,
                        'btn_text' => $s_btn
                    );
                endwhile;
                wp_reset_postdata();
            endif;

            // Safe fallback if database has no slides
            if (empty($slides_data)) {
                $fallback_sliders = array(
                    array('file' => 'slider-1', 'title' => 'Maharshi Vishwamitra Mahavidyalaya - Main Campus Banner'),
                    array('file' => 'slider-7', 'title' => 'International Yoga Day & Fitness Campus Celebration'),
                    array('file' => 'slider-5', 'title' => 'Three-Day College Sports & Athletics Tournament'),
                    array('file' => 'slider-8', 'title' => 'National Academic Seminar & Research Conclave'),
                    array('file' => 'slider-6', 'title' => 'Main Academic Quadrangle & Administrative Secretariat'),
                    array('file' => 'slider-2', 'title' => 'Advanced Science Laboratories & Empirical Research'),
                    array('file' => 'slider-4', 'title' => 'Centenary Convocation & Cultural Amphitheatre'),
                    array('file' => 'slider-3', 'title' => 'Automated Central Library & Digital Knowledge Repository'),
                    array('file' => 'slider-9', 'title' => 'National Festivals & Patriotic Celebrations')
                );
                foreach ($fallback_sliders as $fb) {
                    $slides_data[] = array(
                        'id'       => 0,
                        'title'    => $fb['title'],
                        'tag'      => 'Constituent Unit of VKSU Ara',
                        'img'      => '/wp-content/uploads/sliders/' . $fb['file'] . '.png',
                        'webp'     => '/wp-content/uploads/sliders/' . $fb['file'] . '.webp',
                        'url'      => '',
                        'btn_text' => ''
                    );
                }
            }
            ?>

            <!-- Stately Institutional Command Deck & Fast-Track Bar (ABOVE SLIDER) -->
            <div class="mvc-hero-command-deck">
                <div class="mvc-container">
                    <div class="mvc-hero-command-grid">
                        
                        <!-- Left: Accredited Identity & Authority Badge -->
                        <div class="mvc-hero-auth-badge">
                            <div class="mvc-hero-auth-laurel">
                                <span class="mvc-hero-auth-radar"></span>
                                <span>ESTD. 1958 &bull; UGC 2(f) &amp; 12(B) RECOGNIZED &bull; AICTE APPROVED &bull; AISHE: C-27158</span>
                            </div>
                            <h1 class="mvc-hero-auth-h1">
                                MAHARSHI VISHWAMITRA MAHAVIDYALAYA
                            </h1>
                            <p class="mvc-hero-auth-sub">
                                <span>महर्षि विश्वामित्र महाविद्यालय, बक्सर</span>
                                <span class="mvc-hero-auth-sub-dot">&bull;</span>
                                <span>Constituent Unit of Veer Kunwar Singh University, Ara (Bihar)</span>
                            </p>
                        </div>

                        <!-- Right: Fast-Track Action Launchpad (Dual Action & Immediate Navigation) -->
                        <div class="mvc-hero-fast-actions">
                            <a href="https://mvcollegebuxarmanage.com/" target="_blank" rel="noopener" class="mvc-fast-action-card mvc-fast-action-primary">
                                <span class="mvc-fast-action-icon">🎓</span>
                                <div class="mvc-fast-action-text">
                                    <span class="mvc-fast-action-title">Admissions 2026-27</span>
                                    <span class="mvc-fast-action-hint">Apply Online Portal &rarr;</span>
                                </div>
                            </a>
                            <a href="#mvcAcademicFaculties" class="mvc-fast-action-card mvc-fast-action-secondary">
                                <span class="mvc-fast-action-icon">🏛️</span>
                                <div class="mvc-fast-action-text">
                                    <span class="mvc-fast-action-title">15 Faculties</span>
                                    <span class="mvc-fast-action-hint">Explore Disciplines &darr;</span>
                                </div>
                            </a>
                            <a href="#mvcAnnouncements" class="mvc-fast-action-card mvc-fast-action-secondary">
                                <span class="mvc-fast-action-icon">⚡</span>
                                <div class="mvc-fast-action-text">
                                    <span class="mvc-fast-action-title">Notices &amp; Events</span>
                                    <span class="mvc-fast-action-hint">Student Notices &amp; Tenders &rarr;</span>
                                </div>
                            </a>
                            <a href="#mvcServices" class="mvc-fast-action-card mvc-fast-action-secondary">
                                <span class="mvc-fast-action-icon">📋</span>
                                <div class="mvc-fast-action-text">
                                    <span class="mvc-fast-action-title">Student Help Desk</span>
                                    <span class="mvc-fast-action-hint">Support &amp; Redressal &rarr;</span>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Docked Institutional Metric Strip -->
                <div class="mvc-hero-stat-dock">
                    <div class="mvc-container">
                        <div class="mvc-hero-stats-strip">
                            <div class="mvc-stat-dock-item">
                                <div class="mvc-stat-dock-num">1958</div>
                                <div class="mvc-stat-dock-label">
                                    <div class="mvc-stat-dock-label-top">Foundation Year</div>
                                    <!-- <div class="mvc-stat-dock-label-sub">Siddhashram Heritage</div> -->
                                </div>
                            </div>
                            <div class="mvc-stat-dock-item">
                                <div class="mvc-stat-dock-num">15</div>
                                <div class="mvc-stat-dock-label">
                                    <div class="mvc-stat-dock-label-top">Departments</div>
                                    <!-- <div class="mvc-stat-dock-label-sub">Honours &amp; PG Degree</div> -->
                                </div>
                            </div>
                            <div class="mvc-stat-dock-item">
                                <div class="mvc-stat-dock-num">10,000+</div>
                                <div class="mvc-stat-dock-label">
                                    <div class="mvc-stat-dock-label-top">Active Scholars</div>
                                    <!-- <div class="mvc-stat-dock-label-sub">Co-Ed Campus Community</div> -->
                                </div>
                            </div>
                            <div class="mvc-stat-dock-item">
                                <div class="mvc-stat-dock-num">VKSU Ara</div>
                                <div class="mvc-stat-dock-label">
                                    <div class="mvc-stat-dock-label-top">Constituent Unit</div>
                                    <!-- <div class="mvc-stat-dock-label-sub">Govt. of Bihar Affiliation</div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Edge-to-Edge Image Banner Carousel Viewport (BELOW TEXT SECTION) -->
            <div class="mvc-hero-carousel-wrap" id="mvcHeroCarouselWrap" role="region" aria-roledescription="carousel" aria-label="Campus Highlights & Banner Slider">
                <div class="mvc-hero-slider-track" id="mvcHeroSliderTrack">
                    <?php foreach ($slides_data as $idx => $sd) : 
                        $is_first = ($idx === 0);
                        $slide_link = !empty($sd['url']) ? $sd['url'] : '';
                    ?>
                        <div class="mvc-hero-slide-item <?php echo $is_first ? 'active' : ''; ?>" data-slide-index="<?php echo $idx; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr($sd['title'] ?: 'Slide ' . ($idx + 1)); ?>">
                            <?php if ($slide_link) : ?>
                                <a href="<?php echo esc_url($slide_link); ?>" class="mvc-hero-slide-link" target="_blank" rel="noopener">
                            <?php else : ?>
                                <div class="mvc-hero-slide-link">
                            <?php endif; ?>

                            <picture class="mvc-hero-slide-picture">
                                <source srcset="<?php echo esc_url($sd['webp']); ?>" type="image/webp">
                                <img src="<?php echo esc_url($sd['img']); ?>" 
                                     alt="<?php echo esc_attr($sd['title'] ?: 'Maharshi Vishwamitra Mahavidyalaya Banner ' . ($idx + 1)); ?>" 
                                     class="mvc-hero-slide-img" 
                                     width="1500" 
                                     height="500" 
                                     fetchpriority="<?php echo ($is_first ? 'high' : 'auto'); ?>" 
                                     loading="eager" 
                                     decoding="<?php echo ($is_first ? 'sync' : 'async'); ?>"
                                     onerror="this.onerror=null; this.src='/wp-content/uploads/sliders/slider-1.png';">
                            </picture>

                            <div class="mvc-hero-slide-edge-shade"></div>

                            <?php if ($slide_link) : ?>
                                </a>
                            <?php else : ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Floating Navigation Arrows -->
                <button type="button" class="mvc-hero-nav-arrow mvc-hero-nav-prev" id="mvcHeroPrevBtn" aria-label="Previous Banner Slide" onclick="mvcPrevHeroSlide()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button type="button" class="mvc-hero-nav-arrow mvc-hero-nav-next" id="mvcHeroNextBtn" aria-label="Next Banner Slide" onclick="mvcNextHeroSlide()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <!-- Floating Indicators Deck with Progress Dots, Counter & Pause Toggle (Hidden per directive) -->
                <div class="mvc-hero-indicators-deck" style="display: none !important;">
                    <div class="mvc-hero-dots-wrap" id="mvcHeroDotsWrap">
                        <?php foreach ($slides_data as $idx => $sd) : ?>
                            <button type="button" class="mvc-hero-dot-btn <?php echo ($idx === 0 ? 'active' : ''); ?>" data-slide-index="<?php echo $idx; ?>" aria-label="Go to banner <?php echo ($idx + 1); ?>: <?php echo esc_attr($sd['title']); ?>" onclick="mvcGoHeroSlide(<?php echo $idx; ?>)"></button>
                        <?php endforeach; ?>
                    </div>
                    <span class="mvc-hero-counter-tag" id="mvcHeroCounterTag">
                        01 / <?php echo sprintf('%02d', count($slides_data)); ?>
                    </span>
                    <button type="button" class="mvc-hero-pause-btn" id="mvcHeroPauseBtn" aria-label="Pause Auto Slider" title="Pause Auto Play">
                        <svg class="mvc-pause-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        <svg class="mvc-play-icon" style="display: none;" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </button>
                </div>
            </div>

        </section>

        <!-- ========================================================
             2. LIVE CONTINUOUS TICKER RIBBON (EDGE-TO-EDGE)
             ======================================================== -->
        <!-- <div class="mvc-live-ticker-ribbon">
            <div class="mvc-container" style="display: flex; align-items: center;">
                <div class="mvc-ticker-tag-badge">
                    <span>⚡ Live Notices</span>
                </div>
                <div class="mvc-ticker-track-wrap">
                    <div class="mvc-ticker-track-inner">
                        <?php
                        $ticker_query = new WP_Query(array(
                            'post_type'      => 'notice',
                            'posts_per_page' => 10,
                            'post_status'    => 'publish',
                            'orderby'        => 'date',
                            'order'          => 'DESC'
                        ));

                        if ($ticker_query->have_posts()) :
                            while ($ticker_query->have_posts()) : $ticker_query->the_post();
                                $t_pdf = $resolve_pdf_url(get_the_ID());
                                $t_link = $t_pdf ?: get_permalink();
                                ?>
                                <a href="<?php echo esc_url($t_link); ?>" target="_blank" rel="noopener" class="mvc-ticker-item-link">
                                    &bull; <?php the_title(); ?> [<?php echo $t_pdf ? '📄 1-Click PDF' : 'Details'; ?>]
                                </a>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- ========================================================
             3. ABOUT US: EXPANSIVE HERITAGE & INSTITUTIONAL PILLARS
             ======================================================== -->
        <section class="mvc-about-sec" id="mvcAbout">
            <div class="mvc-container">
                <div class="mvc-about-split-grid">
                    
                    <!-- Left: Narrative -->
                    <div>
                        <!-- <span class="mvc-section-badge">Heritage &amp; History</span> -->
                        <h2 class="mvc-section-title">Rooted in Siddhashram's Wisdom, Leading Modern Higher Education</h2>
                        <div class="mvc-about-narrative-text">
                            <?php if (!empty($global_about)) : ?>
                                <p><?php echo wp_kses_post($global_about); ?></p>
                            <?php else : ?>
                                <p>Buxar owns its importance as a historical place almost exclusively to its colossal Vishwamitra Ashram, which still stands majestically. </br>
                                    Historically celebrated as Siddhashram, this sacred town witnessed a remarkable outburst of intellectual and spiritual activities during the Vedic period.</p>
                                <p>Established in 1958, Maharshi Vishwamitra Mahavidyalaya upholds this rich legacy as a premier constituent unit of Veer Kunwar Singh University, Ara. </br>
                                    The college offers comprehensive Bachelor's and Master's degree programs across Arts, Science, and Vocational disciplines with modern pedagogy, dedicated faculty, and state-of-the-art facilities.</p>
                            <?php endif; ?>
                        </div>

                        <!-- 4 Key Pillars -->
                        <!-- <div class="mvc-about-pillars-grid">
                            <div class="mvc-pillar-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                                <span>VKSU Constituent College</span>
                            </div>
                            <div class="mvc-pillar-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                                <span>CBCS Curriculum Standard</span>
                            </div>
                            <div class="mvc-pillar-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Modern Science Laboratories</span>
                            </div>
                            <div class="mvc-pillar-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/></svg>
                                <span>INFLIBNET Digital Library</span>
                            </div>
                        </div> -->

                        <div>
                            <a href="/glance/" class="mvc-btn mvc-btn-primary">
                                Read Complete College History &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Right: Metrics Showcase Stage -->
                    <div>
                        <div class="mvc-about-metrics-stage">
                            <div class="mvc-metrics-grid-2x2">
                                <div class="mvc-metric-unit">
                                    <div class="mvc-metric-num">1958</div>
                                    <p class="mvc-metric-label">Year of Foundation</p>
                                </div>
                                <div class="mvc-metric-unit">
                                    <div class="mvc-metric-num">VKSU</div>
                                    <p class="mvc-metric-label">Constituent College</p>
                                </div>
                                <div class="mvc-metric-unit">
                                    <div class="mvc-metric-num">CBCS</div>
                                    <p class="mvc-metric-label">Curriculum Standard</p>
                                </div>
                                <div class="mvc-metric-unit">
                                    <div class="mvc-metric-num">Modern</div>
                                    <p class="mvc-metric-label">Science Labs</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ========================================================
             4. INSTITUTIONAL GOVERNANCE: WORLD-CLASS BOARDROOM PAVILION
             (Re-designed to Stately 10/10 Desktop & Responsive Architecture)
             ======================================================== -->
        <section class="mvc-governance-master-sec" id="mvcAdministration">
            <div class="mvc-container">
                <div class="mvc-section-head">
                    <!-- <span class="mvc-section-badge mvc-badge-gold">Patronage &amp; Governance</span> -->
                    <h2 class="mvc-section-title">Institutional Leadership &amp; Administration</h2>
                    <!-- <p class="mvc-section-desc">Distinguished constitutional patrons, university leadership, and academic administrators guiding Maharshi Vishwamitra Mahavidyalaya toward national excellence.</p> -->
                </div>

                <div class="mvc-governance-executive-grid">
                    <?php
                    $lead_query = new WP_Query(array(
                        'post_type'      => 'leadership',
                        'posts_per_page' => 3,
                        'post_status'    => 'publish',
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC'
                    ));

                    if ($lead_query->have_posts()) :
                        while ($lead_query->have_posts()) : $lead_query->the_post();
                            $l_id = get_the_ID();
                            $role = get_field('designation_role', $l_id) ?: 'Administration';
                            $photo = $get_img_url(get_field('profile_photo', $l_id), $l_id);
                            if (!$photo) $photo = '/wp-content/uploads/live_imported/leader_principal.png';
                            
                            $hierarchy_label = 'GOVERNANCE';
                            $desc_text = '';
                            $desk_url = '#';
                            $action_label = 'Official Profile &rarr;';
                            $is_primary_btn = false;

                            if (stripos($role, 'chancellor') !== false && stripos($role, 'vice') === false) {
                                $desk_url = 'https://governor.bih.nic.in/';
                                $action_label = 'Raj Bhavan Portal &nearr;';
                            } elseif (stripos($role, 'vice') !== false) {
                                $desk_url = 'https://vksu.ac.in/';
                                $action_label = 'VKSU Portal &nearr;';
                            } else {
                                $desk_url = '/principal-desk';
                                $action_label = 'Principal\'s Desk &rarr;';
                                $is_primary_btn = true;
                            }
                            ?>
                            <article class="mvc-dignitary-master-card">
                                <div class="mvc-dignitary-top-ribbon"></div>
                                <div>
                                    <!-- <span class="mvc-dignitary-hierarchy-pill"><?php echo esc_html($hierarchy_label); ?></span> -->
                                    <div class="mvc-dignitary-portrait-stage">
                                        <div class="mvc-dignitary-portrait-inner">
                                            <img src="<?php echo esc_url($photo); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                        </div>
                                    </div>
                                    <h3 class="mvc-dignitary-name"><?php the_title(); ?></h3>
                                    <span class="mvc-dignitary-office-badge"><?php echo esc_html($role); ?></span>
                                    <!-- <p class="mvc-dignitary-mandate-text"><?php echo esc_html($desc_text); ?></p> -->
                                </div>
                                <div>
                                    <a href="<?php echo esc_url($desk_url); ?>" <?php echo (strpos($desk_url, 'http') === 0 ? 'target="_blank" rel="noopener"' : ''); ?> class="mvc-dignitary-action-anchor <?php echo ($is_primary_btn ? 'mvc-anchor-primary' : ''); ?>">
                                        <?php echo $action_label; ?>
                                    </a>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </section>

        <!-- ========================================================
             5. ACADEMIC FACULTIES & 15 DEPARTMENTS: MASTER FACULTY GUILD
             (Clean, Dignified, No Emojis, Accurate Academic Programs)
             ======================================================== -->
        <section class="mvc-faculties-master-sec" id="mvcAcademicFaculties">
            <div class="mvc-container">
                <div class="mvc-section-head">
                    <!-- <span class="mvc-section-badge">Academic Faculties</span> -->
                    <h2 class="mvc-section-title">Academic Departments</h2>
                    <!-- <p class="mvc-section-desc">Rigorous undergraduate and postgraduate education across Humanities, Social Sciences, Pure Sciences and Research disciplines under UGC-recognized 4-Year CBCS framework.</p> -->
                </div>

                <!-- Master Faculty Switcher -->
                <div class="mvc-faculty-switcher-deck">
                    <button type="button" class="mvc-faculty-tab-btn active" onclick="mvcFilterFaculties('all', this)">
                        <span>All Faculties</span>
                        <span class="mvc-tab-count-pill">15</span>
                    </button>
                    <button type="button" class="mvc-faculty-tab-btn" onclick="mvcFilterFaculties('humanities', this)">
                        <span>Faculty of Humanities</span>
                        <span class="mvc-tab-count-pill">4</span>
                    </button>
                    <button type="button" class="mvc-faculty-tab-btn" onclick="mvcFilterFaculties('social-science', this)">
                        <span>Faculty of Social Sciences</span>
                        <span class="mvc-tab-count-pill">4</span>
                    </button>
                    <button type="button" class="mvc-faculty-tab-btn" onclick="mvcFilterFaculties('science', this)">
                        <span>Faculty of Sciences</span>
                        <span class="mvc-tab-count-pill">5</span>
                    </button>
                    <button type="button" class="mvc-faculty-tab-btn" onclick="mvcFilterFaculties('vocational', this)">
                        <span>Vocational &amp; Professional</span>
                        <span class="mvc-tab-count-pill">2</span>
                    </button>
                </div>

                <!-- 15 Academic Guild Cards Matrix -->
                <div class="mvc-faculty-cards-matrix" id="mvcFacultyMatrix">
                    <?php
                    $faculty_departments = array(
                        // 1. HUMANITIES (4 Sanctioned Departments)
                        array(
                            'slug' => 'hindi',
                            'title' => 'PG Department of Hindi',
                            'hi' => 'हिन्दी विभाग',
                            'faculty' => 'Humanities',
                            'group' => 'humanities',
                            'degrees' => 'B.A. (Honours) &bull; M.A.',
                            'focus' => 'Classical literature, medieval Bhakti poetry, modern prose, and Hindi linguistics.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>'
                        ),
                        array(
                            'slug' => 'english',
                            'title' => 'Department of English',
                            'hi' => 'अंग्रेजी विभाग',
                            'faculty' => 'Humanities',
                            'group' => 'humanities',
                            'degrees' => 'B.A. (Honours)',
                            'focus' => 'British literature, Indian writing in English, literary criticism, and linguistics.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><path d="M8 7h8"></path><path d="M8 11h6"></path></svg>'
                        ),
                        array(
                            'slug' => 'sanskrit',
                            'title' => 'Department of Sanskrit',
                            'hi' => 'संस्कृत विभाग',
                            'faculty' => 'Humanities',
                            'group' => 'humanities',
                            'degrees' => 'B.A. (Honours)',
                            'focus' => 'Vedic philosophy, Paninian grammar, classical poetics, and ancient epics.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>'
                        ),
                        array(
                            'slug' => 'philosophy',
                            'title' => 'Department of Philosophy',
                            'hi' => 'दर्शनशास्त्र विभाग',
                            'faculty' => 'Humanities',
                            'group' => 'humanities',
                            'degrees' => 'B.A. (Honours)',
                            'focus' => 'Indian Darshana systems, Western metaphysics, epistemology, and ethical systems.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 6a6 6 0 0 1 6 6c0 3.314-2.686 6-6 6"></path><path d="M12 8v8"></path></svg>'
                        ),

                        // 2. SOCIAL SCIENCES (4 Sanctioned Departments)
                        array(
                            'slug' => 'history',
                            'title' => 'PG Department of History',
                            'hi' => 'इतिहास विभाग',
                            'faculty' => 'Social Sciences',
                            'group' => 'social-science',
                            'degrees' => 'B.A. (Honours) &bull; M.A.',
                            'focus' => 'Ancient Indian civilization, medieval statecraft, modern freedom movement, and regional history.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M3 7h18"></path><path d="M6 7v14"></path><path d="M10 7v14"></path><path d="M14 7v14"></path><path d="M18 7v14"></path><path d="M12 3l9 4H3l9-4z"></path></svg>'
                        ),
                        array(
                            'slug' => 'political-science',
                            'title' => 'PG Department of Political Science',
                            'hi' => 'राजनीति विज्ञान विभाग',
                            'faculty' => 'Social Sciences',
                            'group' => 'social-science',
                            'degrees' => 'B.A. (Honours) &bull; M.A.',
                            'focus' => 'Indian constitution, political theory, international relations, and public administration.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"></path><path d="M6 7l6-3 6 3"></path><path d="M3 13l3-6 3 6c0 1.657-1.343 3-3 3s-3-1.343-3-3z"></path><path d="M15 13l3-6 3 6c0 1.657-1.343 3-3 3s-3-1.343-3-3z"></path><path d="M5 21h14"></path></svg>'
                        ),
                        array(
                            'slug' => 'economics',
                            'title' => 'Department of Economics',
                            'hi' => 'अर्थशास्त्र विभाग',
                            'faculty' => 'Social Sciences',
                            'group' => 'social-science',
                            'degrees' => 'B.A. (Honours)',
                            'focus' => 'Micro & macro economics, public finance, developmental economics, and Indian agricultural policy.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line><path d="M4 20h16"></path><polyline points="4 14 10 8 15 13 21 6"></polyline></svg>'
                        ),
                        array(
                            'slug' => 'psychology',
                            'title' => 'PG Department of Psychology',
                            'hi' => 'मनोविज्ञान विभाग',
                            'faculty' => 'Social Sciences',
                            'group' => 'social-science',
                            'degrees' => 'B.A. (Honours) &bull; M.A.',
                            'focus' => 'Cognitive psychology, clinical counseling, psychometrics, and organizational behavior.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.54z"></path><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.54z"></path></svg>'
                        ),

                        // 3. PURE & APPLIED SCIENCES (5 Sanctioned Departments)
                        array(
                            'slug' => 'physics',
                            'title' => 'Department of Physics',
                            'hi' => 'भौतिक विज्ञान विभाग',
                            'faculty' => 'Sciences',
                            'group' => 'science',
                            'degrees' => 'B.Sc. (Honours)',
                            'focus' => 'Classical mechanics, quantum physics, electromagnetism, optics, and solid-state electronics.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(30 12 12)"></ellipse><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(-30 12 12)"></ellipse></svg>'
                        ),
                        array(
                            'slug' => 'chemistry',
                            'title' => 'Department of Chemistry',
                            'hi' => 'रसायन विज्ञान विभाग',
                            'faculty' => 'Sciences',
                            'group' => 'science',
                            'degrees' => 'B.Sc. (Honours)',
                            'focus' => 'Organic synthesis, thermodynamics, analytical coordination chemistry, and spectroscopic research.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31L4.62 19.3A2 2 0 0 0 6.38 22h11.24a2 2 0 0 0 1.76-2.7L14 9.31V2h-4z"></path><path d="M8.5 2h7"></path><path d="M7 16h10"></path></svg>'
                        ),
                        array(
                            'slug' => 'mathematics',
                            'title' => 'Department of Mathematics',
                            'hi' => 'गणित विभाग',
                            'faculty' => 'Sciences',
                            'group' => 'science',
                            'degrees' => 'B.A. / B.Sc. (Honours)',
                            'focus' => 'Abstract algebra, real analysis, differential calculus, topology, and computational math.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16"></path><path d="M12 3v18"></path><path d="M4 17h16"></path><circle cx="6" cy="12" r="2"></circle><circle cx="18" cy="12" r="2"></circle></svg>'
                        ),
                        array(
                            'slug' => 'botany',
                            'title' => 'Department of Botany',
                            'hi' => 'वनस्पति विज्ञान विभाग',
                            'faculty' => 'Sciences',
                            'group' => 'science',
                            'degrees' => 'B.Sc. (Honours)',
                            'focus' => 'Plant morphology, cellular physiology, biodiversity conservation, ethnobotany, and genetics.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-9"></path><path d="M12 13a6 6 0 0 0 6-6c0-3.3-2.7-6-6-6s-6 2.7-6 6a6 6 0 0 0 6 6z"></path><path d="M12 13l-4 4"></path><path d="M12 16l4 3"></path></svg>'
                        ),
                        array(
                            'slug' => 'zoology',
                            'title' => 'Department of Zoology',
                            'hi' => 'जंतु विज्ञान विभाग',
                            'faculty' => 'Sciences',
                            'group' => 'science',
                            'degrees' => 'B.Sc. (Honours)',
                            'focus' => 'Comparative animal anatomy, molecular biology, immunology, ecology, and developmental biology.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a4 4 0 0 0-4 4v1H6a3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h2v1a4 4 0 0 0 8 0v-1h2a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3h-2V6a4 4 0 0 0-4-4z"></path><circle cx="10" cy="7" r="1"></circle><circle cx="14" cy="7" r="1"></circle></svg>'
                        ),

                        // 4. VOCATIONAL & PROFESSIONAL (2 Sanctioned Departments)
                        array(
                            'slug' => 'computer-applications',
                            'title' => 'Department of Computer Applications (BCA)',
                            'hi' => 'कंप्यूटर अनुप्रयोग विभाग (BCA)',
                            'faculty' => 'Vocational & Professional',
                            'group' => 'vocational',
                            'degrees' => 'B.C.A. (Honours) &bull; 3-Yr Professional',
                            'focus' => 'Software engineering, database architecture, full-stack web systems, cloud computing, and AI essentials.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>'
                        ),
                        array(
                            'slug' => 'business-management',
                            'title' => 'Department of Business Management (BBA)',
                            'hi' => 'व्यावसायिक प्रबंधन विभाग (BBA)',
                            'faculty' => 'Vocational & Professional',
                            'group' => 'vocational',
                            'degrees' => 'B.B.A. (Honours) &bull; 3-Yr Professional',
                            'focus' => 'Corporate finance, managerial marketing, human resources, organizational analytics, and entrepreneurship.',
                            'svg' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>'
                        ),
                    );

                    foreach ($faculty_departments as $fd) :
                        ?>
                        <a href="/department/<?php echo esc_attr($fd['slug']); ?>" class="mvc-academic-guild-card" data-group="<?php echo esc_attr($fd['group']); ?>">
                            <div>
                                <div class="mvc-guild-card-top">
                                    <div class="mvc-guild-heraldic-glyph">
                                        <?php echo $fd['svg']; ?>
                                    </div>
                                    <span class="mvc-guild-faculty-tag"><?php echo esc_html($fd['faculty']); ?></span>
                                </div>
                                <h3 class="mvc-guild-dept-title"><?php echo esc_html($fd['title']); ?></h3>
                                <span class="mvc-guild-hi-title"><?php echo esc_html($fd['hi']); ?></span>
                                <div class="mvc-guild-degrees-bar">
                                    <?php echo wp_kses_post($fd['degrees']); ?>
                                </div>
                                <!-- <p class="mvc-guild-focus-desc"><?php echo esc_html($fd['focus']); ?></p> -->
                            </div>
                            <div class="mvc-guild-portal-link">
                                <!-- <span>Academic Portal &amp; Faculty</span> -->
                                <span>View Details &rarr;</span>
                            </div>
                        </a>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </section>

        <!-- ========================================================
             6. NOTICE, EVENTS & TENDER HUB
             ======================================================== -->
        <section class="mvc-announcements-sec" id="mvcAnnouncements">
            <div class="mvc-container">
                <div class="mvc-section-head">
                    <!-- <span class="mvc-section-badge mvc-badge-gold">Live Campus Feeds</span> -->
                    <h2 class="mvc-section-title mvc-light">Notice, Events & Tenders</h2>
                    <!-- <p class="mvc-section-desc mvc-light">Official student notices, live academic events, and institutional procurement tenders.</p> -->
                </div>

                <div class="mvc-announcements-grid">

                    <!-- Board 1: Notice for Students -->
                    <div class="mvc-board-panel">
                        <div class="mvc-board-header">
                            <h3 class="mvc-board-title">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                                Notice for Students
                            </h3>
                            <span class="mvc-board-badge-count">Live Board</span>
                        </div>
                        <div class="mvc-board-list">
                            <?php
                            $s_query = new WP_Query(array(
                                'post_type'      => 'notice',
                                'posts_per_page' => 12,
                                'post_status'    => 'publish',
                                'tax_query'      => array(
                                    array('taxonomy' => 'audience', 'field' => 'slug', 'terms' => array('students', 'student'))
                                )
                            ));

                            $s_posts = $s_query->posts;
                            if (!empty($s_posts)) {
                                usort($s_posts, function($a, $b) {
                                    $pin_a = (int) get_field('pin_to_homepage', $a->ID);
                                    $pin_b = (int) get_field('pin_to_homepage', $b->ID);
                                    if ($pin_a !== $pin_b) return $pin_b <=> $pin_a;
                                    $d_a = get_field('date_picker', $a->ID) ?: get_the_date('Ymd', $a->ID);
                                    $d_b = get_field('date_picker', $b->ID) ?: get_the_date('Ymd', $b->ID);
                                    return strcmp($d_b, $d_a);
                                });

                                foreach ($s_posts as $p) :
                                    setup_postdata($p);
                                    $raw_d = get_field('date_picker', $p->ID);
                                    $disp_d = $format_acf_date($raw_d, $p->ID);
                                    $is_new = get_field('new_badge', $p->ID);
                                    $is_pin = get_field('pin_to_homepage', $p->ID);
                                    $pdf_url = $resolve_pdf_url($p->ID);
                                    ?>
                                    <div class="mvc-notice-item">
                                        <div class="mvc-notice-meta-line">
                                            <span class="mvc-notice-date-pill"><?php echo esc_html($disp_d); ?></span>
                                            <?php if ($is_pin) : ?><span class="mvc-badge-pinned-gold">PINNED</span><?php endif; ?>
                                            <?php if ($is_new) : ?><span class="mvc-badge-new-pulse">NEW</span><?php endif; ?>
                                        </div>
                                        <p class="mvc-notice-headline">
                                            <?php if ($pdf_url) : ?>
                                                <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" title="Click to open official PDF directly">
                                                    <?php echo esc_html(get_the_title($p->ID)); ?>
                                                </a>
                                            <?php else : ?>
                                                <a href="<?php echo esc_url(get_permalink($p->ID)); ?>">
                                                    <?php echo esc_html(get_the_title($p->ID)); ?>
                                                </a>
                                            <?php endif; ?>
                                        </p>
                                        <div class="mvc-notice-actions-dual">
                                            <?php if ($pdf_url) : ?>
                                                <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-dual-btn-pdf">
                                                    📄 1-Click PDF &rarr;
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>" class="mvc-dual-btn-memo">
                                                View Details &rarr;
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                                wp_reset_postdata();
                            } else {
                                echo '<p style="color:#94A3B8; font-size:13.5px; padding:20px; text-align:center;">No student circulars found.</p>';
                            }
                            ?>
                        </div>
                        <a href="<?php echo esc_url(home_url('/notices/')); ?>" class="mvc-board-view-all-cta">
                            View All Student Notices &rarr;
                        </a>
                    </div>

                    <!-- Board 2: Campus Events -->
                    <div class="mvc-board-panel">
                        <div class="mvc-board-header">
                            <h3 class="mvc-board-title">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                                Campus Events
                            </h3>
                            <span class="mvc-board-badge-count">Activities</span>
                        </div>

                        <?php
                        // Fetch all published events
                        $all_events = get_posts(array(
                            'post_type'      => 'event',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                            'orderby'        => 'date',
                            'order'          => 'DESC'
                        ));

                        $count_upcoming = 0;
                        $count_completed = 0;
                        $upcoming_events = array();
                        $completed_events = array();

                        if (!empty($all_events)) {
                            foreach ($all_events as $ev) {
                                $terms = wp_get_object_terms($ev->ID, 'event-status');
                                $st = (!empty($terms) && !is_wp_error($terms)) ? strtolower($terms[0]->slug) : 'completed';
                                if ($st === 'upcoming') {
                                    $count_upcoming++;
                                    $upcoming_events[] = $ev;
                                } else {
                                    $count_completed++;
                                    $completed_events[] = $ev;
                                }
                            }
                        }

                        // Order: Upcoming milestones first, followed by concluded chronologically
                        $sorted_events = array_merge($upcoming_events, $completed_events);
                        ?>

                        <!-- Switcher Tabs -->
                        <div class="mvc-events-nav-tabs" role="tablist" aria-label="Filter events by status">
                            <button type="button" class="mvc-event-tab-btn active" data-filter="all" onclick="mvcFilterEvents('all', this)">All Events</button>
                            <button type="button" class="mvc-event-tab-btn" data-filter="upcoming" onclick="mvcFilterEvents('upcoming', this)">Upcoming (<?php echo (int) $count_upcoming; ?>)</button>
                            <button type="button" class="mvc-event-tab-btn" data-filter="completed" onclick="mvcFilterEvents('completed', this)">Concluded (<?php echo (int) $count_completed; ?>)</button>
                        </div>

                        <!-- Events List -->
                        <div id="mvcEventsContainer" class="mvc-board-list">
                            <?php
                            if (!empty($sorted_events)) :
                                foreach ($sorted_events as $ev) :
                                    $e_id = $ev->ID;
                                    $terms = wp_get_object_terms($e_id, 'event-status');
                                    $ev_status = (!empty($terms) && !is_wp_error($terms)) ? strtolower($terms[0]->slug) : 'completed';
                                    $is_upcoming = ($ev_status === 'upcoming');

                                    $raw_dates = get_field('event_dates', $e_id) ?: get_post_meta($e_id, 'event_dates', true);
                                    if (empty($raw_dates)) {
                                        $raw_dates = get_the_date('d M Y', $e_id);
                                    }

                                    // Extract Day & Month for prestigious academic calendar badge
                                    $cal_day = '01';
                                    $cal_month = 'EVT';
                                    if (preg_match('/(\d{1,2})(?:-\d{1,2})?\s+([A-Za-z]+)/', $raw_dates, $m)) {
                                        $cal_day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                                        $cal_month = strtoupper(substr($m[2], 0, 3));
                                    } elseif (preg_match('/([A-Za-z]+)\s+(\d{4})/', $raw_dates, $m)) {
                                        $cal_day = '01';
                                        $cal_month = strtoupper(substr($m[1], 0, 3));
                                    } else {
                                        $cal_day = get_the_date('d', $e_id);
                                        $cal_month = strtoupper(get_the_date('M', $e_id));
                                    }
                                    ?>
                                    <div class="mvc-event-card-item" data-status="<?php echo esc_attr($ev_status); ?>">
                                        <div class="mvc-event-cal-tile <?php echo $is_upcoming ? 'mvc-cal-upcoming' : ''; ?>">
                                            <span class="mvc-cal-month"><?php echo esc_html($cal_month); ?></span>
                                            <span class="mvc-cal-day"><?php echo esc_html($cal_day); ?></span>
                                        </div>
                                        <div class="mvc-event-details">
                                            <div class="mvc-event-date-stamp">
                                                📅 <?php echo esc_html($raw_dates); ?>
                                                <?php if ($is_upcoming) : ?>
                                                    &bull; <span class="mvc-badge-status-upcoming">UPCOMING</span>
                                                <?php else : ?>
                                                    &bull; <span class="mvc-badge-status-concluded">CONCLUDED</span>
                                                <?php endif; ?>
                                            </div>
                                            <h4 class="mvc-event-title-text">
                                                <a href="<?php echo esc_url(get_permalink($e_id)); ?>" style="color:#ffffff; text-decoration:none;">
                                                    <?php echo esc_html(get_the_title($e_id)); ?>
                                                </a>
                                            </h4>
                                            <a href="<?php echo esc_url(get_permalink($e_id)); ?>" class="mvc-event-report-link">
                                                <?php echo $is_upcoming ? 'View Schedule &amp; Details &rarr;' : 'View Event &rarr;'; ?>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                            else :
                                echo '<p style="color:#94A3B8; font-size:13px; padding:20px; text-align:center;">No event records found.</p>';
                            endif;
                            ?>
                            <div id="mvcNoEventsNotice" style="display: none; color:#94A3B8; font-size:13px; padding:30px 20px; text-align:center;">
                                No events found in this category.
                            </div>
                        </div>

                        <a href="<?php echo esc_url(home_url('/events/')); ?>" class="mvc-board-view-all-cta">
                            View All Campus Events &rarr;
                        </a>
                    </div>

                    <!-- Board 3: Tenders & Procurement -->
                    <div class="mvc-board-panel">
                        <div class="mvc-board-header">
                            <h3 class="mvc-board-title">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                                Tenders &amp; Procurement
                            </h3>
                            <span class="mvc-board-badge-count">Procurement</span>
                        </div>
                        <div class="mvc-board-list">
                            <?php
                            $default_tenders_board = [
                                [
                                    'id'          => 'tender-1789373092',
                                    'title'       => 'Tender Notice Publication For Website Development',
                                    'date'        => 'Sep 14, 2026',
                                    'ref'         => 'MVC/TEND/WEB/2026-01',
                                    'file_name'   => 'Tender_1789373092.pdf',
                                    'file_size'   => '117 KB',
                                    'category'    => 'ICT Infrastructure',
                                    'status'      => 'Archived / Implemented'
                                ],
                                [
                                    'id'          => 'tender-1778740080',
                                    'title'       => 'Tender Notice Publication For Conference Hall Refurbishment',
                                    'date'        => 'May 14, 2026',
                                    'ref'         => 'MVC/TEND/CIVIL/2026-04',
                                    'file_name'   => 'Tender_1778740080.pdf',
                                    'file_size'   => '97 KB',
                                    'category'    => 'Civil Works & Acoustic',
                                    'status'      => 'Completed'
                                ],
                                [
                                    'id'          => 'tender-1778740147',
                                    'title'       => 'Tender Notice Publication For Automated Smart I.D. Card System',
                                    'date'        => 'May 14, 2026',
                                    'ref'         => 'MVC/TEND/ID/2026-03',
                                    'file_name'   => 'Tender_1778740147.pdf',
                                    'file_size'   => '74 KB',
                                    'category'    => 'Student Automation',
                                    'status'      => 'Completed'
                                ],
                                [
                                    'id'          => 'tender-1775723074',
                                    'title'       => 'Tender Notice Publication For Soundless Heavy Generator',
                                    'date'        => 'Apr 09, 2026',
                                    'ref'         => 'MVC/TEND/ELEC/2026-02',
                                    'file_name'   => 'Tender_1775723074.pdf',
                                    'file_size'   => '83 KB',
                                    'category'    => 'Power Backup & Genset',
                                    'status'      => 'Installed'
                                ],
                                [
                                    'id'          => 'tender-1775723113',
                                    'title'       => 'Tender Notice Publication For Heavy Duty Photo state Machine',
                                    'date'        => 'Apr 09, 2026',
                                    'ref'         => 'MVC/TEND/EXAM/2026-01',
                                    'file_name'   => 'Tender_1775723113.pdf',
                                    'file_size'   => '83 KB',
                                    'category'    => 'Examination Equipment',
                                    'status'      => 'Installed'
                                ],
                                [
                                    'id'          => 'tender-1768460142',
                                    'title'       => 'Tender Notice For Installation of Steel Made Name Plate of Maharshi Vishwamitra Mahavidyalaya',
                                    'date'        => 'Jan 15, 2026',
                                    'ref'         => 'MVC/TEND/SIGN/2026-01',
                                    'file_name'   => 'Tender_1768460142.pdf',
                                    'file_size'   => '77 KB',
                                    'category'    => 'Campus Signage',
                                    'status'      => 'Completed'
                                ]
                            ];

                            $board_tenders = function_exists('mvc_get_page_array') ? mvc_get_page_array(293, 'tenders_list', $default_tenders_board) : $default_tenders_board;

                            if (!empty($board_tenders)) :
                                foreach ($board_tenders as $t) :
                                    $local_pdf = !empty($t['file_name']) ? home_url('/wp-content/uploads/tender/' . $t['file_name']) : (!empty($t['pdf_url']) ? $t['pdf_url'] : home_url('/tender/'));
                                    $t_size = !empty($t['file_size']) ? $t['file_size'] : 'PDF';
                                    $t_ref = !empty($t['ref']) ? $t['ref'] : 'TENDER';
                                    $t_cat = !empty($t['category']) ? $t['category'] : 'Procurement';
                                    $t_status = !empty($t['status']) ? $t['status'] : 'Published';
                                    ?>
                                    <div class="mvc-notice-item mvc-tender-item">
                                        <div class="mvc-notice-meta-line">
                                            <span class="mvc-notice-date-pill"><?php echo esc_html($t['date']); ?></span>
                                            <span class="mvc-badge-pinned-gold"><?php echo esc_html($t_ref); ?></span>
                                            <span class="mvc-badge-tender-status"><?php echo esc_html($t_status); ?></span>
                                        </div>
                                        <p class="mvc-notice-headline">
                                            <a href="<?php echo esc_url($local_pdf); ?>" target="_blank" rel="noopener" title="<?php echo esc_attr($t['title']); ?>">
                                                <?php echo esc_html($t['title']); ?>
                                            </a>
                                        </p>
                                        <div class="mvc-tender-scope">
                                            <span>📂</span> <span><?php echo esc_html($t_cat); ?></span>
                                        </div>
                                        <div class="mvc-notice-actions-dual">
                                            <a href="<?php echo esc_url($local_pdf); ?>" target="_blank" rel="noopener" class="mvc-dual-btn-pdf" title="Download Official Tender PDF">
                                                📄 1-Click PDF (<?php echo esc_html($t_size); ?>) &rarr;
                                            </a>
                                            <a href="<?php echo esc_url(home_url('/tender/')); ?>" class="mvc-dual-btn-memo" title="Open Tenders &amp; Procurement Suite">
                                                Full Details &rarr;
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                            else :
                                echo '<p style="color:#94A3B8; font-size:13px; padding:20px; text-align:center;">No active tender notices found.</p>';
                            endif;
                            ?>
                        </div>
                        <a href="<?php echo esc_url(home_url('/tender/')); ?>" class="mvc-board-view-all-cta">
                            View All Public Tenders &rarr;
                        </a>
                    </div>

                </div>

                <!-- Section Footer Action Row -->
                <div class="mvc-announcements-footer-row">
                    <a href="<?php echo esc_url(home_url('/notices/')); ?>" class="mvc-btn mvc-btn-gold">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                        View All Student Notices &rarr;
                    </a>
                    <a href="<?php echo esc_url(home_url('/events/')); ?>" class="mvc-btn mvc-btn-outline" style="border-color: rgba(212, 175, 55, 0.4); color: #ffffff;">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                        View All Campus Events &rarr;
                    </a>
                    <a href="<?php echo esc_url(home_url('/tender/')); ?>" class="mvc-btn mvc-btn-outline" style="border-color: rgba(212, 175, 55, 0.4); color: #ffffff;">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                        View All Public Tenders &rarr;
                    </a>
                </div>
            </div>
        </section>

        <!-- ========================================================
             7. STUDENT FAST-TRACK SERVICES & UTILITY PORTALS
             ======================================================== -->
        <section class="mvc-services-sec" id="mvcServices">
            <div class="mvc-container">
                <div class="mvc-section-head">
                    <!-- <span class="mvc-section-badge">Student Utilities</span> -->
                    <h2 class="mvc-section-title">Academic &amp; Administrative Services</h2>
                    <!-- <p class="mvc-section-desc">Single-window digital portals for online admissions, university fee payments, examinations, library e-resources, and student grievance support.</p> -->
                </div>

                <div class="mvc-services-grid">
                    <?php
                    $services_list = array(
                        array('title' => 'Online Admissions Portal', 'desc' => 'Direct link to student application and CBCS enrollment verification.', 'link' => 'https://mvcollegebuxarmanage.com/', 'ext' => true, 'svg' => '<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>'),
                        array('title' => 'CBCS Syllabi & Ordinances', 'desc' => 'Download 4-Year Undergraduate & PG course structures and grading patterns.', 'link' => '/syllabus', 'ext' => false, 'svg' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>'),
                        array('title' => 'Central Library & OPAC', 'desc' => 'Browse 15,000+ text books, UGC INFLIBNET N-LIST e-journals and archives.', 'link' => '/library', 'ext' => false, 'svg' => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'),
                        array('title' => 'Anti-Ragging Cell & Helpline', 'desc' => 'UGC anti-ragging compliance, campus safety squad, and immediate contact details.', 'link' => '/anti-ragging-cell', 'ext' => false, 'svg' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'),
                        array('title' => 'Student Grievance Cell', 'desc' => 'Statutory committee composition, UGC regulations, and official redressal contacts.', 'link' => '/student-grievance', 'ext' => false, 'svg' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'),
                        array('title' => 'Internal Quality Assurance (IQAC)', 'desc' => 'NAAC accreditation documents, SSR reports, and institutional improvement plans.', 'link' => '/iqac', 'ext' => false, 'svg' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14M22 4L12 14.01l-3-3"/>'),
                        array('title' => 'National Service Scheme (NSS)', 'desc' => 'Community service, blood donation camps, environmental campaigns, and leadership.', 'link' => '/nss', 'ext' => false, 'svg' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>'),
                        array('title' => 'National Cadet Corps (NCC)', 'desc' => 'Discipline, armed forces training, republic day parade selection, and national camps.', 'link' => '/ncc', 'ext' => false, 'svg' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>')
                    );

                    foreach ($services_list as $svc) :
                        ?>
                        <a href="<?php echo esc_url($svc['link']); ?>" <?php echo ($svc['ext'] ? 'target="_blank" rel="noopener"' : ''); ?> class="mvc-service-card">
                            <div>
                                <div class="mvc-service-icon-box">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <?php echo $svc['svg']; ?>
                                    </svg>
                                </div>
                                <h3 class="mvc-service-card-title"><?php echo esc_html($svc['title']); ?></h3>
                                <p class="mvc-service-card-desc"><?php echo esc_html($svc['desc']); ?></p>
                            </div>
                            <div class="mvc-service-card-link">
                                <span>Access Portal &rarr;</span>
                            </div>
                        </a>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </section>

        <!-- ========================================================
             8. PRESS RELEASES & SCANNED CLIPPINGS
             ======================================================== -->
        <section class="mvc-press-sec" id="mvcPress">
            <div class="mvc-container">
                <div class="mvc-section-head">
                    <!-- <span class="mvc-section-badge">Media Coverage</span> -->
                    <h2 class="mvc-section-title">Press Releases &amp; Newspaper Clippings</h2>
                    <!-- <p class="mvc-section-desc">Media reports, university gazette features, and news clippings of academic, cultural and sports milestones at Maharshi Vishwamitra Mahavidyalaya.</p> -->
                </div>

                <div class="mvc-press-cards-grid">
                    <?php
                    $press_query = new WP_Query(array(
                        'post_type'      => array('press-media', 'press_release'),
                        'posts_per_page' => 4,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC'
                    ));

                    if ($press_query->have_posts()) :
                        while ($press_query->have_posts()) : $press_query->the_post();
                            $pr_id = get_the_ID();
                            $raw_date = get_field('date', $pr_id) ?: get_field('release_date', $pr_id);
                            $pr_date = $format_acf_date($raw_date, $pr_id);
                            $clip_img = get_post_meta($pr_id, 'press_image_url', true) ?: get_post_meta($pr_id, '_press_image_url', true);
                            if (!$clip_img) {
                                $clip_img = $get_img_url(get_field('newspaper_clipping', $pr_id) ?: get_field('clipping', $pr_id), $pr_id);
                            }
                            if (!$clip_img && has_post_thumbnail($pr_id)) {
                                $clip_img = get_the_post_thumbnail_url($pr_id, 'large');
                            }
                            if (function_exists('mvc_resolve_media_image_url') && !empty($clip_img)) {
                                $clip_img = mvc_resolve_media_image_url($clip_img);
                            }
                            ?>
                            <article class="mvc-press-card">
                                <div class="mvc-press-clipping-frame">
                                    <img src="<?php echo esc_url($clip_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                </div>
                                <div class="mvc-press-card-body">
                                    <div>
                                        <div class="mvc-press-date-line">📰 <?php echo esc_html($pr_date); ?></div>
                                        <h3 class="mvc-press-card-title"><?php the_title(); ?></h3>
                                    </div>
                                    <div class="mvc-press-actions-row">
                                        <a href="<?php echo esc_url($clip_img); ?>" target="_blank" rel="noopener" style="color:var(--mvc-maroon); text-decoration:none;">
                                            Zoom Clipping &nearr;
                                        </a>
                                        <a href="<?php the_permalink(); ?>" style="color:var(--mvc-navy); text-decoration:none;">
                                            Full Report &rarr;
                                        </a>
                                    </div>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p style="text-align:center; color:#64748B; padding:30px; grid-column:1/-1;">No press releases currently available.</p>';
                    endif;
                    ?>
                </div>

                <div style="text-align: center; margin-top: 42px;">
                    <a href="<?php echo esc_url(home_url('/media/')); ?>" class="mvc-btn mvc-btn-primary">
                        Browse All Press Clippings Archive &rarr;
                    </a>
                </div>
            </div>
        </section>

        <!-- ========================================================
             9. OFFICIAL YOUTUBE BROADCASTS & VIDEO DOCUMENTARIES (10/10)
             ======================================================== -->
        <?php
        $yt_channel_url = 'https://www.youtube.com/channel/UChAoUFiLfqO61Q22MTWd0ag';
        if (function_exists('get_field')) {
            $custom_ch = get_field('college_youtube_channel', 209);
            if (!empty($custom_ch)) $yt_channel_url = $custom_ch;
        }

        $v_query = new WP_Query(array(
            'post_type'      => 'video',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));

        $videos_list = array();
        if ($v_query->have_posts()) {
            while ($v_query->have_posts()) {
                $v_query->the_post();
                $vid_id = get_the_ID();
                $v_url = get_field('youtube_url', $vid_id) ?: get_post_meta($vid_id, 'youtube_url', true);
                if (empty($v_url)) $v_url = 'https://www.youtube.com/watch?v=l0HpVZ33c_g';
                $yt_code = $get_yt_id($v_url);
                if (empty($yt_code)) $yt_code = 'l0HpVZ33c_g';

                $v_cat = get_field('video_category', $vid_id) ?: (get_post_meta($vid_id, 'video_category', true) ?: 'Campus Event');
                $v_desc = get_the_content() ?: (get_post_meta($vid_id, 'video_description', true) ?: 'Official broadcast from Maharshi Vishwamitra Mahavidyalaya (Maharshi Vishwamitra Mahavidyalaya, Buxar).');

                $videos_list[] = array(
                    'id'       => $vid_id,
                    'title'    => get_the_title(),
                    'url'      => $v_url,
                    'yt_id'    => $yt_code,
                    'thumb'    => 'https://img.youtube.com/vi/' . $yt_code . '/hqdefault.jpg',
                    'cat'      => $v_cat,
                    'desc'     => wp_strip_all_tags($v_desc)
                );
            }
            wp_reset_postdata();
        }

        if (empty($videos_list)) {
            $videos_list[] = array(
                'id'       => 0,
                'title'    => 'Maharshi Vishwamitra Mahavidyalaya Khel Mahotsav 2026: Day 3 Grand Finale & Trophy Ceremony',
                'url'      => 'https://www.youtube.com/watch?v=l0HpVZ33c_g',
                'yt_id'    => 'l0HpVZ33c_g',
                'thumb'    => 'https://img.youtube.com/vi/l0HpVZ33c_g/hqdefault.jpg',
                'cat'      => 'Sports & Athletics',
                'desc'     => 'Live coverage of Day 3 of the Annual Khel Mahotsav sports festival featuring championship matches and felicitation ceremony on the Maharshi Vishwamitra Mahavidyalaya grounds.'
            );
        }

        $feat_v = $videos_list[0];
        ?>
        <section class="mvc-multimedia-sec" id="mvcMultimedia">
            <div class="mvc-container">
                <div class="mvc-section-head">
                    <!-- <span class="mvc-section-badge mvc-badge-yt"> -->
                        <!-- <span class="mvc-live-dot-red"></span>
                        Official YouTube Broadcasts &bull; @mvcollegebuxar
                    </span> -->
                    <h2 class="mvc-section-title mvc-light">Campus Life in Motion &amp; Video Documentaries</h2>
                    <!-- <p class="mvc-section-desc mvc-light">Official sports meets, convocations, university seminars, and cultural festivals broadcast live from Maharshi Vishwamitra Mahavidyalaya, Buxar.</p> -->
                </div>

                <!-- YouTube Channel Official Badge / Subscribe Strip -->
                <div class="mvc-yt-channel-banner">
                    <div class="mvc-yt-channel-meta">
                        <div class="mvc-yt-avatar">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="#FF0000"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </div>
                        <div>
                            <div class="mvc-yt-channel-title">
                                Maharshi Vishwamitra Mahavidyalaya (Maharshi Vishwamitra Mahavidyalaya, Buxar)
                                <span class="mvc-yt-verified-check" title="Verified Institutional Channel">&#10004;</span>
                            </div>
                            <div class="mvc-yt-channel-sub">Official Collegiate Broadcast Channel &bull; Buxar, Bihar &bull; Constituent Unit of VKSU Ara</div>
                        </div>
                    </div>
                    <a href="<?php echo esc_url($yt_channel_url); ?>" target="_blank" rel="noopener" class="mvc-yt-subscribe-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        Subscribe on YouTube &nearr;
                    </a>
                </div>

                <!-- Theatrical Cinema Stage: Main Feature Screen + Playlist Rail -->
                <div class="mvc-cinema-theater-grid">
                    <!-- Left: Main Feature Screen -->
                    <div class="mvc-cinema-screen-box">
                        <div class="mvc-cinema-viewport" id="mvcMainCinemaViewport">
                            <img id="mvcCinemaPoster" src="<?php echo esc_url($feat_v['thumb']); ?>" alt="<?php echo esc_attr($feat_v['title']); ?>" loading="lazy">
                            <div class="mvc-cinema-overlay-scrim"></div>
                            <button type="button" class="mvc-cinema-play-orb" id="mvcCinemaPlayBtn" onclick="mvcPlayCurrentCinema()" aria-label="Play Video Broadcast">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><polygon points="6 3 20 12 6 21 6 3"/></svg>
                            </button>
                            <span class="mvc-cinema-now-playing-pill" id="mvcNowPlayingBadge">NOW FEATURED</span>
                        </div>
                        <div class="mvc-cinema-meta-info">
                            <div>
                                <div class="mvc-cinema-tag-row">
                                    <span class="mvc-cinema-cat-tag" id="mvcCinemaCategory"><?php echo esc_html($feat_v['cat']); ?></span>
                                    
                                </div>
                                <h3 class="mvc-cinema-title" id="mvcCinemaTitle"><?php echo esc_html($feat_v['title']); ?></h3>
                                <p class="mvc-cinema-desc" id="mvcCinemaDesc"><?php echo esc_html($feat_v['desc']); ?></p>
                            </div>
                            <div class="mvc-cinema-actions">
                                <button type="button" class="mvc-btn mvc-btn-gold" onclick="mvcPlayCurrentCinema()">
                                    ▶ Play Broadcast Now
                                </button>
                                <a id="mvcCinemaDirectLink" href="<?php echo esc_url($feat_v['url']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-outline" style="border-color: rgba(255,255,255,0.3); color:#ffffff;">
                                    Open on YouTube &nearr;
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Interactive Playlist Queue Rail -->
                    <div class="mvc-cinema-playlist-panel">
                        <div class="mvc-playlist-header">
                            <h4 class="mvc-playlist-heading">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zm-8-2l6-4-6-4v8z"/></svg>
                                Broadcast Playlist
                            </h4>
                            <span class="mvc-playlist-count-pill"><?php echo count($videos_list); ?> Videos</span>
                        </div>
                        <div class="mvc-playlist-scroll-list">
                            <?php foreach ($videos_list as $v_idx => $v_item) : ?>
                                <button type="button" class="mvc-playlist-item-card <?php echo ($v_idx === 0 ? 'active' : ''); ?>" onclick="mvcSelectCinemaVideo(<?php echo $v_idx; ?>)">
                                    <div class="mvc-playlist-thumb-box">
                                        <img src="<?php echo esc_url($v_item['thumb']); ?>" alt="<?php echo esc_attr($v_item['title']); ?>" loading="lazy">
                                        <div class="mvc-playlist-thumb-play-tag">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="6 3 20 12 6 21 6 3"/></svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mvc-playlist-item-title"><?php echo esc_html($v_item['title']); ?></h5>
                                        <span class="mvc-playlist-item-cat"><?php echo esc_html($v_item['cat']); ?></span>
                                    </div>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================
             10. VERIFIED SCHOLARS & STUDENT TESTIMONIALS (10/10)
             ======================================================== -->
        <?php
        $testi_query = new WP_Query(array(
            'post_type'      => 'testimonial',
            'posts_per_page' => 12,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'ASC'
        ));

        // Hidden for the time being per user request
        if (false && $testi_query->have_posts()) :
        ?>
        <section class="mvc-testimonials-sec" id="mvcTestimonials">
            <div class="mvc-container">
                <div class="mvc-section-head mvc-testi-head">
                    <!-- <span class="mvc-section-badge">Voices of Excellence</span> -->
                    <h2 class="mvc-section-title">What Our Scholars Say About Us</h2>
                    <!-- <p class="mvc-section-desc">Inspiring reflections from undergraduate and postgraduate scholars whose intellect and career trajectories were shaped at Maharshi Vishwamitra Mahavidyalaya.</p> -->
                </div>

                <div class="mvc-testi-slider-wrapper" id="mvcTestiSliderWrapper">
                    <!-- Flanking Left Arrow Button -->
                    <button type="button" class="mvc-testi-arrow-btn mvc-prev" id="mvcTestiPrevBtn" aria-label="Previous Testimonials" onclick="mvcSlideTesti('prev')">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="#002147">
                            <path fill="#002147" d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>
                    </button>

                    <div class="mvc-testi-viewport" id="mvcTestiViewport">
                        <div class="mvc-testi-track" id="mvcTestiTrack">
                            <?php
                            while ($testi_query->have_posts()) : $testi_query->the_post();
                                $tid = get_the_ID();
                                $s_name = get_field('student_name', $tid) ?: get_the_title($tid);
                                $s_name = preg_replace('/^Testimonial\s*-\s*/i', '', $s_name);
                                $course_name = get_field('course', $tid) ?: (get_post_meta($tid, 'course', true) ?: 'M.A. Scholar');
                                $s_quote = get_field('quote', $tid) ?: (get_post_meta($tid, 'quote', true) ?: get_the_content());
                                $photo_val = get_field('photo', $tid) ?: get_post_meta($tid, 'photo', true);
                                $student_photo = $get_img_url($photo_val, $tid);
                                if (!$student_photo) $student_photo = home_url('/wp-content/uploads/live_imported/testimonial_TEST_1765883669.jpg');
                                $stars = (int) (get_field('rating_stars', $tid) ?: 5);
                                $stars_str = str_repeat('★', max(1, min(5, $stars)));
                                ?>
                                <div class="mvc-testi-slide">
                                    <div class="mvc-testi-card">
                                        <span class="mvc-testi-quote-watermark">&ldquo;</span>
                                        <div>
                                            <div class="mvc-testi-header-row">
                                                <div class="mvc-testi-stars-row"><?php echo esc_html($stars_str); ?></div>
                                                <span class="mvc-testi-verified-badge">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                                                    Verified Scholar
                                                </span>
                                            </div>
                                            <blockquote class="mvc-testi-quote-text">
                                                &ldquo;<?php echo esc_html(wp_strip_all_tags($s_quote)); ?>&rdquo;
                                            </blockquote>
                                        </div>
                                        <div class="mvc-testi-author-bar">
                                            <div class="mvc-testi-avatar-frame">
                                                <img src="<?php echo esc_url($student_photo); ?>" alt="<?php echo esc_attr($s_name); ?>" loading="lazy">
                                            </div>
                                            <div>
                                                <h4 class="mvc-testi-name"><?php echo esc_html($s_name); ?></h4>
                                                <span class="mvc-testi-course-pill">🎓 <?php echo esc_html($course_name); ?></span>
                                                <div class="mvc-testi-batch-sub">Maharshi Vishwamitra Mahavidyalaya</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>

                    <!-- Flanking Right Arrow Button -->
                    <button type="button" class="mvc-testi-arrow-btn mvc-next" id="mvcTestiNextBtn" aria-label="Next Testimonials" onclick="mvcSlideTesti('next')">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="#002147">
                            <path fill="#002147" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                        </svg>
                    </button>
                </div>

                <!-- Clean Centered Pagination Dots Indicator Below Cards -->
                <div class="mvc-testi-dots-row" id="mvcTestiDotsRow"></div>

                <div class="mvc-testi-cta-wrap">
                    <a href="https://www.youtube.com/channel/UChAoUFiLfqO61Q22MTWd0ag" target="_blank" rel="noopener" class="mvc-btn mvc-btn-primary">
                        Watch Student &amp; Alumni Stories on YouTube &nearr;
                    </a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- ========================================================
             11. IMPORTANT WEBSITES (महत्वपूर्ण वेबसाइटें 10/10 1-ROW SLIDER)
             ======================================================== -->
        <section class="mvc-important-web-sec" id="mvcImportantWebsites">
            <div class="mvc-container">
                <div class="mvc-important-web-head">
                    <h2 class="mvc-important-web-title">Important Websites</h2>
                    <div class="mvc-divider-wrap">
                        <img src="/wp-content/uploads/importantwebsite/divider.png" alt="divider" class="mvc-web-divider" loading="lazy">
                    </div>
                </div>

                <div class="mvc-web-slider-wrapper" id="mvcWebSliderWrapper">
                    <!-- Flanking Left Arrow Button -->
                    <button type="button" class="mvc-web-arrow-btn mvc-prev" id="mvcWebPrevBtn" aria-label="Previous Websites" onclick="mvcSlideWeb('prev')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#002147">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>
                    </button>

                    <div class="mvc-web-viewport" id="mvcWebViewport">
                        <div class="mvc-web-track" id="mvcWebTrack">
                            <?php
                            $important_websites = array(
                                array('title' => 'Ministry of Education (Govt. of India)', 'url' => 'https://www.education.gov.in/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763920091.jpg'),
                                array('title' => 'Veer Kunwar Singh University (VKSU), Ara', 'url' => 'https://vksu.ac.in/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763920204.png'),
                                array('title' => 'National Assessment and Accreditation Council (NAAC)', 'url' => 'http://naac.gov.in/index.php/en/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763920233.png'),
                                array('title' => 'One Nation One Subscription (ONOS)', 'url' => 'https://onos.gov.in/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763920362.png'),
                                array('title' => 'All India Survey on Higher Education (AISHE)', 'url' => 'https://aishe.gov.in/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763921253.png'),
                                array('title' => 'National Institutional Ranking Framework (NIRF)', 'url' => 'https://www.nirfindia.org/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763921296.png'),
                                array('title' => 'University Grants Commission (UGC)', 'url' => 'https://www.ugc.gov.in/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763921364.png'),
                                array('title' => 'All India Council for Technical Education (AICTE)', 'url' => 'https://www.aicte-india.org/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763919943.jpg'),
                                array('title' => 'INFLIBNET Centre (UGC)', 'url' => 'https://www.inflibnet.ac.in/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763920036.jpg'),
                                array('title' => 'Malaviya Mission Teacher Training Programme (MMTTC)', 'url' => 'https://mmc.ugc.ac.in/Home/DefaultPage', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763921455.png'),
                                array('title' => "Raj Bhavan Bihar (Hon'ble Governor Secretariat)", 'url' => 'https://governor.bih.nic.in/', 'img' => '/wp-content/uploads/importantwebsite/WEB_1763920061.jpg')
                            );
                            foreach ($important_websites as $web) : ?>
                                <div class="mvc-web-slide">
                                    <a href="<?php echo esc_url($web['url']); ?>" target="_blank" rel="noopener" class="mvc-partner-logo-item" title="<?php echo esc_attr($web['title']); ?>">
                                        <img src="<?php echo esc_url($web['img']); ?>" alt="<?php echo esc_attr($web['title']); ?>" loading="lazy">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Flanking Right Arrow Button -->
                    <button type="button" class="mvc-web-arrow-btn mvc-next" id="mvcWebNextBtn" aria-label="Next Websites" onclick="mvcSlideWeb('next')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#002147">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

    </div>

    <!-- ========================================================
         CLIENT INTERACTIVITY & CONTROLLER SCRIPT
         ======================================================== -->
    <script>
    (function() {
        // ========================================================
        // 1. Image Banner Carousel Controller (Autoplay, Touch, Nav)
        // ========================================================
        var heroTrack = document.getElementById('mvcHeroSliderTrack');
        var heroWrap = document.getElementById('mvcHeroCarouselWrap');
        var heroSlides = document.querySelectorAll('.mvc-hero-slide-item');
        var heroDots = document.querySelectorAll('.mvc-hero-dot-btn');
        var heroCounter = document.getElementById('mvcHeroCounterTag');
        var heroPrevBtn = document.getElementById('mvcHeroPrevBtn');
        var heroNextBtn = document.getElementById('mvcHeroNextBtn');
        var heroPauseBtn = document.getElementById('mvcHeroPauseBtn');
        
        var curHeroIdx = 0;
        var totalHeroSlides = heroSlides.length;
        var isHeroPaused = false;
        var isHeroManualPause = false;
        var heroInterval = null;
        var heroDelay = 5000; // 5.0 seconds per slide

        // Preload all banner slides immediately into browser cache to prevent blank/delayed loading
        var bannerWebpUrls = <?php echo json_encode(array_map(function($s) { return $s['webp']; }, $slides_data)); ?>;
        if (Array.isArray(bannerWebpUrls)) {
            bannerWebpUrls.forEach(function(u) {
                if (u) {
                    var pre = new Image();
                    pre.src = u;
                }
            });
        }

        window.mvcGoHeroSlide = function(newIdx) {
            if (totalHeroSlides === 0) return;
            if (newIdx < 0) newIdx = totalHeroSlides - 1;
            if (newIdx >= totalHeroSlides) newIdx = 0;
            curHeroIdx = newIdx;

            if (heroTrack && heroWrap) {
                var slideWidth = heroWrap.getBoundingClientRect().width || heroWrap.offsetWidth || window.innerWidth;
                heroTrack.style.transform = 'translate3d(-' + (curHeroIdx * slideWidth) + 'px, 0, 0)';
            } else if (heroTrack) {
                heroTrack.style.transform = 'translateX(-' + (curHeroIdx * 100) + '%)';
            }

            heroSlides.forEach(function(s, i) {
                if (i === curHeroIdx) s.classList.add('active');
                else s.classList.remove('active');
            });

            heroDots.forEach(function(d, i) {
                if (i === curHeroIdx) d.classList.add('active');
                else d.classList.remove('active');
            });

            if (heroCounter) {
                var curPad = (curHeroIdx + 1 < 10 ? '0' : '') + (curHeroIdx + 1);
                var totPad = (totalHeroSlides < 10 ? '0' : '') + totalHeroSlides;
                heroCounter.textContent = curPad + ' / ' + totPad;
            }
        };

        window.mvcNextHeroSlide = function() {
            window.mvcGoHeroSlide(curHeroIdx + 1);
        };

        window.mvcPrevHeroSlide = function() {
            window.mvcGoHeroSlide(curHeroIdx - 1);
        };

        function startHeroAutoplay() {
            if (heroInterval) clearInterval(heroInterval);
            heroInterval = setInterval(function() {
                if (!isHeroPaused && !isHeroManualPause) {
                    window.mvcNextHeroSlide();
                }
            }, heroDelay);
        }

        if (totalHeroSlides > 0) {
            window.addEventListener('resize', function() {
                window.mvcGoHeroSlide(curHeroIdx);
            });

            if (heroPrevBtn) {
                heroPrevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.mvcPrevHeroSlide();
                });
            }
            if (heroNextBtn) {
                heroNextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.mvcNextHeroSlide();
                });
            }

            heroDots.forEach(function(d) {
                d.addEventListener('click', function(e) {
                    e.preventDefault();
                    var idx = parseInt(this.getAttribute('data-slide-index'), 10);
                    window.mvcGoHeroSlide(idx);
                });
            });

            if (heroPauseBtn) {
                heroPauseBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    isHeroManualPause = !isHeroManualPause;
                    var pauseIcon = heroPauseBtn.querySelector('.mvc-pause-icon');
                    var playIcon = heroPauseBtn.querySelector('.mvc-play-icon');
                    if (isHeroManualPause) {
                        if (pauseIcon) pauseIcon.style.display = 'none';
                        if (playIcon) playIcon.style.display = 'block';
                        heroPauseBtn.setAttribute('title', 'Resume Auto Play');
                        heroPauseBtn.setAttribute('aria-label', 'Resume Auto Play');
                    } else {
                        if (pauseIcon) pauseIcon.style.display = 'block';
                        if (playIcon) playIcon.style.display = 'none';
                        heroPauseBtn.setAttribute('title', 'Pause Auto Play');
                        heroPauseBtn.setAttribute('aria-label', 'Pause Auto Play');
                    }
                });
            }

            if (heroWrap) {
                // Pause on desktop mouse hover, resume on mouse leave
                heroWrap.addEventListener('mouseenter', function() {
                    isHeroPaused = true;
                });
                heroWrap.addEventListener('mouseleave', function() {
                    isHeroPaused = false;
                });

                // Mobile Touch Swipe Handlers
                var heroTouchStartX = 0;
                var heroTouchStartY = 0;
                var heroTouchEndX = 0;
                var heroTouchEndY = 0;

                heroWrap.addEventListener('touchstart', function(e) {
                    heroTouchStartX = e.changedTouches[0].screenX;
                    heroTouchStartY = e.changedTouches[0].screenY;
                }, { passive: true });

                heroWrap.addEventListener('touchend', function(e) {
                    heroTouchEndX = e.changedTouches[0].screenX;
                    heroTouchEndY = e.changedTouches[0].screenY;
                    var diffX = heroTouchStartX - heroTouchEndX;
                    var diffY = heroTouchStartY - heroTouchEndY;

                    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 40) {
                        if (diffX > 0) {
                            window.mvcNextHeroSlide(); // Swipe left
                        } else {
                            window.mvcPrevHeroSlide(); // Swipe right
                        }
                    }
                }, { passive: true });
            }

            // Keyboard navigation when focused/in viewport
            document.addEventListener('keydown', function(e) {
                if (document.activeElement && (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA')) return;
                var wrapRect = heroWrap ? heroWrap.getBoundingClientRect() : null;
                if (wrapRect && wrapRect.top < window.innerHeight && wrapRect.bottom > 0) {
                    if (e.key === 'ArrowLeft') {
                        window.mvcPrevHeroSlide();
                    } else if (e.key === 'ArrowRight') {
                        window.mvcNextHeroSlide();
                    }
                }
            });

            // Initial calculation
            setTimeout(function() {
                window.mvcGoHeroSlide(0);
            }, 50);

            startHeroAutoplay();
        }

        // Faculty Matrix Filter Switcher
        window.mvcFilterFaculties = function(group, btn) {
            var tabs = document.querySelectorAll('.mvc-faculty-tab-btn');
            tabs.forEach(function(t) { t.classList.remove('active'); });
            btn.classList.add('active');

            var cards = document.querySelectorAll('#mvcFacultyMatrix .mvc-academic-guild-card');
            cards.forEach(function(card) {
                if (group === 'all' || card.getAttribute('data-group') === group) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        };

        // Events Tab Switcher & Dynamic Filter
        window.mvcFilterEvents = function(type, btn) {
            var btns = document.querySelectorAll('.mvc-event-tab-btn');
            btns.forEach(function(b) { b.classList.remove('active'); });
            if (btn) {
                btn.classList.add('active');
            } else {
                var targetBtn = document.querySelector('.mvc-event-tab-btn[data-filter="' + type + '"]');
                if (targetBtn) targetBtn.classList.add('active');
            }

            var container = document.getElementById('mvcEventsContainer');
            if (!container) return;

            var cards = container.querySelectorAll('.mvc-event-card-item');
            var visibleCount = 0;

            cards.forEach(function(card) {
                var status = card.getAttribute('data-status');
                if (type === 'all' || status === type) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            var emptyNotice = document.getElementById('mvcNoEventsNotice');
            if (emptyNotice) {
                emptyNotice.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
        };

        // Backwards compatibility for callers
        window.mvcSwitchEventTab = function(type, btn) {
            window.mvcFilterEvents(type, btn);
        };

        // YouTube Cinema Theater Controller (100% Dynamic)
        var cinemaVideos = <?php echo json_encode($videos_list); ?>;
        var currentCinemaIdx = 0;
        var isCinemaPlaying = false;

        window.mvcSelectCinemaVideo = function(idx) {
            if (!cinemaVideos || !cinemaVideos[idx]) return;
            currentCinemaIdx = idx;
            var v = cinemaVideos[idx];

            var poster = document.getElementById('mvcCinemaPoster');
            var title = document.getElementById('mvcCinemaTitle');
            var desc = document.getElementById('mvcCinemaDesc');
            var cat = document.getElementById('mvcCinemaCategory');
            var directLink = document.getElementById('mvcCinemaDirectLink');
            var viewport = document.getElementById('mvcMainCinemaViewport');

            if (poster) poster.src = v.thumb;
            if (title) title.textContent = v.title;
            if (desc) desc.textContent = v.desc;
            if (cat) cat.textContent = v.cat;
            if (directLink) directLink.href = v.url;

            var items = document.querySelectorAll('.mvc-playlist-item-card');
            items.forEach(function(el, i) {
                if (i === idx) el.classList.add('active');
                else el.classList.remove('active');
            });

            if (isCinemaPlaying && viewport) {
                viewport.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + encodeURIComponent(v.yt_id) + '?autoplay=1&rel=0&modestbranding=1" title="' + encodeURIComponent(v.title) + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="mvc-cinema-iframe"></iframe>';
            }
        };

        window.mvcPlayCurrentCinema = function() {
            var v = cinemaVideos[currentCinemaIdx];
            if (!v) return;
            var viewport = document.getElementById('mvcMainCinemaViewport');
            if (viewport) {
                viewport.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + encodeURIComponent(v.yt_id) + '?autoplay=1&rel=0&modestbranding=1" title="' + encodeURIComponent(v.title) + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="mvc-cinema-iframe"></iframe>';
                isCinemaPlaying = true;
            }
        };

        // 1-Row 3-Column Testimonial Slider Controller
        var testiTrack = document.getElementById('mvcTestiTrack');
        var testiSlides = document.querySelectorAll('.mvc-testi-slide');
        var testiDotsRow = document.getElementById('mvcTestiDotsRow');
        var curTestiIdx = 0;
        var totalTesti = testiSlides.length;

        function getVisibleTestiCount() {
            if (window.innerWidth <= 640) return 1;
            if (window.innerWidth <= 992) return 2;
            return 3;
        }

        function getMaxTestiIdx() {
            var vis = getVisibleTestiCount();
            return Math.max(0, totalTesti - vis);
        }

        function buildTestiDots() {
            if (!testiDotsRow) return;
            testiDotsRow.innerHTML = '';
            var maxIdx = getMaxTestiIdx();
            for (var i = 0; i <= maxIdx; i++) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'mvc-testi-dot' + (i === curTestiIdx ? ' active' : '');
                dot.setAttribute('aria-label', 'Go to testimonial slide ' + (i + 1));
                (function(index) {
                    dot.addEventListener('click', function() {
                        curTestiIdx = index;
                        updateTestiSlider();
                    });
                })(i);
                testiDotsRow.appendChild(dot);
            }
        }

        function updateTestiSlider() {
            if (!testiTrack || totalTesti === 0) return;
            var maxIdx = getMaxTestiIdx();
            if (curTestiIdx > maxIdx) curTestiIdx = maxIdx;
            if (curTestiIdx < 0) curTestiIdx = 0;

            var targetSlide = testiSlides[curTestiIdx];
            var firstSlide = testiSlides[0];
            if (targetSlide && firstSlide) {
                var offset = targetSlide.offsetLeft - firstSlide.offsetLeft;
                testiTrack.style.transform = 'translateX(-' + offset + 'px)';
            }

            if (testiDotsRow) {
                var dots = testiDotsRow.querySelectorAll('.mvc-testi-dot');
                dots.forEach(function(d, i) {
                    if (i === curTestiIdx) d.classList.add('active');
                    else d.classList.remove('active');
                });
            }
        }

        window.mvcSlideTesti = function(dir) {
            var maxIdx = getMaxTestiIdx();
            if (dir === 'next') {
                if (curTestiIdx >= maxIdx) {
                    curTestiIdx = 0;
                } else {
                    curTestiIdx++;
                }
            } else if (dir === 'prev') {
                if (curTestiIdx <= 0) {
                    curTestiIdx = maxIdx;
                } else {
                    curTestiIdx--;
                }
            }
            updateTestiSlider();
        };

        if (totalTesti > 0) {
            buildTestiDots();
            updateTestiSlider();

            window.addEventListener('resize', function() {
                buildTestiDots();
                updateTestiSlider();
            });

            // Auto-advance testimonials every 7.5 seconds
            var testiTimer = setInterval(function() {
                window.mvcSlideTesti('next');
            }, 7500);

            var sliderWrapper = document.getElementById('mvcTestiSliderWrapper');
            if (sliderWrapper) {
                sliderWrapper.addEventListener('mouseenter', function() {
                    clearInterval(testiTimer);
                });
                sliderWrapper.addEventListener('mouseleave', function() {
                    clearInterval(testiTimer);
                    testiTimer = setInterval(function() {
                        window.mvcSlideTesti('next');
                    }, 7500);
                });

                // Touch swipe support for mobile
                var touchStartX = 0;
                var touchEndX = 0;
                sliderWrapper.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                sliderWrapper.addEventListener('touchend', function(e) {
                    touchEndX = e.changedTouches[0].screenX;
                    var diff = touchStartX - touchEndX;
                    if (diff > 45) {
                        window.mvcSlideTesti('next');
                    } else if (diff < -45) {
                        window.mvcSlideTesti('prev');
                    }
                }, { passive: true });
            }
        }

        // ========================================================
        // 11. Important Websites 1-Row Carousel Slider
        // ========================================================
        var webTrack = document.getElementById('mvcWebTrack');
        var webSlides = document.querySelectorAll('.mvc-web-slide');
        var curWebIdx = 0;
        var totalWeb = webSlides.length;

        function getVisibleWebCount() {
            var w = window.innerWidth;
            if (w >= 1200) return 6;
            if (w >= 992) return 5;
            if (w >= 768) return 4;
            if (w >= 480) return 3;
            return 2;
        }

        function getMaxWebIdx() {
            var visible = getVisibleWebCount();
            return Math.max(0, totalWeb - visible);
        }

        function updateWebSlider() {
            if (!webTrack || totalWeb === 0) return;
            var maxIdx = getMaxWebIdx();
            if (curWebIdx > maxIdx) curWebIdx = maxIdx;
            if (curWebIdx < 0) curWebIdx = 0;

            var targetSlide = webSlides[curWebIdx];
            var firstSlide = webSlides[0];
            if (targetSlide && firstSlide) {
                var offset = targetSlide.offsetLeft - firstSlide.offsetLeft;
                webTrack.style.transform = 'translateX(-' + offset + 'px)';
            }
        }

        window.mvcSlideWeb = function(dir) {
            var maxIdx = getMaxWebIdx();
            if (dir === 'next') {
                if (curWebIdx >= maxIdx) {
                    curWebIdx = 0;
                } else {
                    curWebIdx++;
                }
            } else if (dir === 'prev') {
                if (curWebIdx <= 0) {
                    curWebIdx = maxIdx;
                } else {
                    curWebIdx--;
                }
            }
            updateWebSlider();
        };

        if (totalWeb > 0) {
            updateWebSlider();
            window.addEventListener('resize', updateWebSlider);

            // Auto-advance partner logos every 3.5 seconds
            var webTimer = setInterval(function() {
                window.mvcSlideWeb('next');
            }, 3500);

            var webWrapper = document.getElementById('mvcWebSliderWrapper');
            if (webWrapper) {
                webWrapper.addEventListener('mouseenter', function() {
                    clearInterval(webTimer);
                });
                webWrapper.addEventListener('mouseleave', function() {
                    clearInterval(webTimer);
                    webTimer = setInterval(function() {
                        window.mvcSlideWeb('next');
                    }, 3500);
                });

                // Touch swipe support for mobile
                var touchWebStartX = 0;
                webWrapper.addEventListener('touchstart', function(e) {
                    touchWebStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                webWrapper.addEventListener('touchend', function(e) {
                    var diff = touchWebStartX - e.changedTouches[0].screenX;
                    if (diff > 40) {
                        window.mvcSlideWeb('next');
                    } else if (diff < -40) {
                        window.mvcSlideWeb('prev');
                    }
                }, { passive: true });
            }
        }
    })();
    </script>
    <?php
        return ob_get_clean();
    }, 43200);
}
add_shortcode('mvc_homepage', 'mvc_render_homepage');
