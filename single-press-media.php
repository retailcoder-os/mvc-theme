<?php
/**
 * Single Template for 'press-media' Custom Post Type
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * 
 * 10/10 Creative Standard & Dual Access Architecture:
 * 1. Grand Full-Width Institutional Hero Banner with Royal Navy gradient, gold trim, and breadcrumbs.
 * 2. Shared Media Suite Pill Navigation (Photo Gallery | Video Gallery | Press & Media).
 * 3. 100% Full-Width Expansive Fluid Layout with 2-Column Desktop Grid.
 * 4. High-Resolution Newspaper Clipping Viewer with Zoom, Direct Raw Access, Download, and Print.
 * 5. Institutional Verification & Statutory Authority Block.
 * 6. Full-Width Related Press Releases Roster.
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post;
$current_post_id = get_the_ID() ?: get_queried_object_id();
if (!$current_post_id && isset($post->ID)) {
    $current_post_id = $post->ID;
}

get_header();

$post_id   = $current_post_id ?: get_the_ID();
$title     = get_the_title($post_id);
$raw_date  = get_field('date', $post_id) ?: get_post_meta($post_id, 'press_date', true);
$pub       = get_post_meta($post_id, 'press_publication', true) ?: (get_field('publication', $post_id) ?: 'Print Media & News Coverage');
$year      = get_post_meta($post_id, 'press_year', true) ?: get_the_date('Y', $post_id);

// Date formatting
$disp_date = '';
if (!empty($raw_date)) {
    $ts = strtotime($raw_date);
    $disp_date = $ts ? date('d F Y', $ts) : $raw_date;
} else {
    $disp_date = get_the_date('d F Y', $post_id);
}

// Clipping Image Resolution
$thumb_id  = get_post_thumbnail_id($post_id);
$thumb_url = $thumb_id ? wp_get_attachment_url($thumb_id) : get_the_post_thumbnail_url($post_id, 'full');
if (!$thumb_url) {
    $attachments = get_attached_media('image', $post_id);
    if (!empty($attachments)) {
        $first_att = reset($attachments);
        $thumb_url = wp_get_attachment_url($first_att->ID);
    }
}
if (!$thumb_url) {
    $raw_press_url = get_post_meta($post_id, 'press_image_url', true) ?: get_post_meta($post_id, '_press_image_url', true);
    if (!empty($raw_press_url)) {
        $thumb_url = function_exists('mvc_resolve_media_image_url') ? mvc_resolve_media_image_url($raw_press_url) : $raw_press_url;
    }
}

// College Logo for verification seal
$college_logo = function_exists('get_field') ? get_field('college_logo', 209) : null;
$logo_url = '';
if (is_array($college_logo) && !empty($college_logo['url'])) {
    $logo_url = $college_logo['url'];
} else {
    $logo_url = home_url('/wp-content/uploads/2026/10/mv-college-website-logo.png');
}

// Print Media Suite shared styles if available
if (function_exists('mvc_print_media_suite_styles')) {
    mvc_print_media_suite_styles();
}
?>

<div class="mvc-press-single-wrapper">
    <style id="mvc-single-press-styles">
    /* Universal Full-Width Canvas & Spacing Tokens */
    .mvc-press-single-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
        background: #f8fafc;
        width: 100% !important;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* 1. Grand Full-Width Media Hero Header */
    .mvc-single-press-hero {
        background: linear-gradient(135deg, #00152e 0%, #002147 60%, #0b3464 100%);
        color: #ffffff;
        padding: 48px 0 38px;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid #D4AF37;
        width: 100% !important;
    }
    .mvc-single-press-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 85% 20%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 10% 80%, rgba(128, 0, 0, 0.3) 0%, transparent 60%);
        pointer-events: none;
    }
    .mvc-single-press-hero-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto;
        padding-left: clamp(16px, 2.5vw, 40px) !important;
        padding-right: clamp(16px, 2.5vw, 40px) !important;
        box-sizing: border-box;
        position: relative;
        z-index: 2;
    }
    .mvc-single-press-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .mvc-single-press-breadcrumbs a {
        color: #FFD54F;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .mvc-single-press-breadcrumbs a:hover {
        color: #ffffff;
        text-decoration: underline;
    }
    .mvc-single-press-breadcrumbs .mvc-sep {
        color: rgba(255, 255, 255, 0.4);
        font-size: 11px;
    }
    .mvc-single-press-breadcrumbs .mvc-current {
        color: #ffffff;
        font-weight: 500;
    }

    .mvc-single-press-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .mvc-press-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #800000;
        color: #ffffff;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border: 1px solid rgba(212, 175, 55, 0.4);
    }
    .mvc-press-hero-date {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.12);
        color: #FFD54F;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .mvc-press-hero-source {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(0, 0, 0, 0.25);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .mvc-press-hero-title {
        font-size: clamp(24px, 2.5vw, 36px);
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 12px;
        line-height: 1.32;
        letter-spacing: -0.01em;
    }
    .mvc-press-hero-subtitle {
        font-size: 14.5px;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        max-width: 900px;
        line-height: 1.55;
    }

    /* 2. Full-Width Workspace Layout */
    .mvc-press-single-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 36px auto 70px;
        padding-left: clamp(16px, 2.5vw, 40px) !important;
        padding-right: clamp(16px, 2.5vw, 40px) !important;
        box-sizing: border-box !important;
    }

    .mvc-press-layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 32px;
        align-items: start;
    }

    /* 3. Main Viewer Card (Left Column) */
    .mvc-press-main-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 33, 71, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .mvc-press-card-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 12px;
    }
    .mvc-press-card-caption {
        font-size: 13.5px;
        font-weight: 700;
        color: #002147;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mvc-press-card-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mvc-ctrl-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .mvc-ctrl-btn:hover {
        background: #002147;
        color: #ffffff;
        border-color: #002147;
    }

    /* Scanned Clipping Display Frame */
    .mvc-press-clipping-box {
        padding: 30px;
        background: #111827;
        text-align: center;
        position: relative;
        min-height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mvc-press-clipping-img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.65);
        display: inline-block;
        transition: transform 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .mvc-press-clipping-img:hover {
        transform: scale(1.01);
    }

    /* Dual Action & Download Toolbar */
    .mvc-press-action-bar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 12px;
        padding: 18px 24px;
        background: #0f172a;
        border-top: 1px solid #1e293b;
        flex-wrap: wrap;
    }
    .mvc-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
    }
    .mvc-btn-view-full {
        background: #D4AF37;
        color: #00152e !important;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }
    .mvc-btn-view-full:hover {
        background: #FFD54F;
        transform: translateY(-2px);
    }
    .mvc-btn-download {
        background: #800000;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.3);
    }
    .mvc-btn-download:hover {
        background: #5c0000;
        transform: translateY(-2px);
    }
    .mvc-btn-print {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .mvc-btn-print:hover {
        background: rgba(255, 255, 255, 0.22);
    }
    .mvc-btn-back {
        background: transparent;
        color: #94a3b8 !important;
        border: 1px solid rgba(255, 255, 255, 0.15);
        margin-left: auto;
    }
    .mvc-btn-back:hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.08);
    }

    /* Article Body Transcript */
    .mvc-press-body-section {
        padding: 32px 36px 36px;
        border-top: 1px solid #f1f5f9;
    }
    .mvc-press-body-label {
        font-size: 13px;
        font-weight: 800;
        color: #800000;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mvc-press-body-text {
        font-size: 16px;
        line-height: 1.85;
        color: #334155;
    }
    .mvc-press-body-text p {
        margin: 0 0 16px;
    }

    /* 4. Verification & Authority Sidebar (Right Column) */
    .mvc-press-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .mvc-sidebar-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 33, 71, 0.06);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .mvc-sidebar-header {
        background: #002147;
        color: #ffffff;
        padding: 16px 20px;
        border-bottom: 3px solid #D4AF37;
    }
    .mvc-sidebar-header h3 {
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mvc-sidebar-body {
        padding: 20px;
    }

    /* Official College Seal Block */
    .mvc-verify-seal-block {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 18px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 16px;
    }
    .mvc-verify-seal-img {
        width: 52px;
        height: auto;
        flex-shrink: 0;
    }
    .mvc-verify-seal-info {
        flex: 1;
    }
    .mvc-verify-inst-name {
        font-size: 13px;
        font-weight: 800;
        color: #002147;
        line-height: 1.3;
    }
    .mvc-verify-inst-sub {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
    }

    /* Metadata Specification Rows */
    .mvc-meta-spec-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .mvc-meta-spec-row {
        display: flex;
        flex-direction: column;
        gap: 2px;
        font-size: 12.5px;
    }
    .mvc-meta-spec-label {
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    .mvc-meta-spec-value {
        font-weight: 600;
        color: #002147;
        line-height: 1.4;
    }
    .mvc-meta-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #DCFCE7;
        color: #166534;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        width: fit-content;
        margin-top: 2px;
    }

    /* Quick Channel Navigator in Sidebar */
    .mvc-sidebar-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .mvc-sidebar-link-item a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #1e293b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .mvc-sidebar-link-item a:hover {
        background: #002147;
        color: #ffffff;
        border-color: #002147;
        transform: translateX(3px);
    }
    .mvc-sidebar-link-item a:hover .mvc-s-arrow {
        color: #FFD54F;
    }
    .mvc-s-arrow {
        color: #94a3b8;
        font-size: 14px;
        transition: color 0.2s ease;
    }

    /* Helpdesk Box */
    .mvc-sidebar-helpdesk {
        background: linear-gradient(135deg, #FAF9F6 0%, #F1F5F9 100%);
        border: 1px dashed #CBD5E1;
        border-radius: 10px;
        padding: 16px;
    }
    .mvc-helpdesk-title {
        font-size: 12px;
        font-weight: 800;
        color: #800000;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    .mvc-helpdesk-text {
        font-size: 12px;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 10px;
    }
    .mvc-helpdesk-contact {
        font-size: 12.5px;
        font-weight: 700;
        color: #002147;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-helpdesk-contact:hover {
        text-decoration: underline;
    }

    /* 5. Full-Width Related Press Releases Section */
    .mvc-related-press-section {
        margin-top: 55px;
        border-top: 2px solid #e2e8f0;
        padding-top: 36px;
    }
    .mvc-related-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .mvc-related-title {
        font-size: 22px;
        font-weight: 800;
        color: #002147;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .mvc-related-view-all {
        font-size: 13.5px;
        font-weight: 700;
        color: #800000;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: transform 0.2s ease;
    }
    .mvc-related-view-all:hover {
        text-decoration: underline;
        transform: translateX(3px);
    }

    .mvc-related-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }
    .mvc-related-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .mvc-related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 33, 71, 0.12);
        border-color: #800000;
    }
    .mvc-related-thumb-box {
        height: 180px;
        overflow: hidden;
        background: #0f172a;
        position: relative;
    }
    .mvc-related-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }
    .mvc-related-card:hover .mvc-related-thumb-img {
        transform: scale(1.06);
    }
    .mvc-related-badge-date {
        position: absolute;
        bottom: 8px;
        left: 8px;
        background: rgba(0, 33, 71, 0.85);
        color: #FFD54F;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        backdrop-filter: blur(4px);
    }
    .mvc-related-card-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .mvc-related-source-tag {
        font-size: 11px;
        font-weight: 700;
        color: #800000;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    .mvc-related-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #002147;
        margin: 0 0 14px;
        line-height: 1.45;
        flex: 1;
    }
    .mvc-related-card-title a {
        color: inherit;
        text-decoration: none;
    }
    .mvc-related-card-title a:hover {
        color: #800000;
    }
    .mvc-related-read-btn {
        font-size: 12.5px;
        font-weight: 700;
        color: #800000;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: auto;
    }
    .mvc-related-read-btn:hover {
        text-decoration: underline;
    }

    /* 6. Responsive Breakpoints */
    @media (max-width: 1200px) {
        .mvc-related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 992px) {
        .mvc-press-layout-grid {
            grid-template-columns: 1fr;
        }
        .mvc-btn-back {
            margin-left: 0;
            width: 100%;
            justify-content: center;
        }
    }
    @media (max-width: 650px) {
        .mvc-single-press-hero {
            padding: 35px 0 28px;
        }
        .mvc-press-card-topbar {
            flex-direction: column;
            align-items: flex-start;
        }
        .mvc-press-clipping-box {
            padding: 14px;
            min-height: 260px;
        }
        .mvc-press-action-bar {
            padding: 14px;
        }
        .mvc-action-btn {
            width: 100%;
            justify-content: center;
        }
        .mvc-press-body-section {
            padding: 22px 18px;
        }
        .mvc-related-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <!-- 1. GRAND FULL-WIDTH MEDIA HERO HEADER -->
    <header class="mvc-single-press-hero">
        <div class="mvc-single-press-hero-container">
            <nav class="mvc-single-press-breadcrumbs" aria-label="Breadcrumb Navigation">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                <span class="mvc-sep" aria-hidden="true">/</span>
                <a href="<?php echo esc_url(home_url('/media/')); ?>">Media &amp; Press Coverage</a>
                <span class="mvc-sep" aria-hidden="true">/</span>
                <span class="mvc-current" aria-current="page"><?php echo esc_html(wp_trim_words($title, 8)); ?></span>
            </nav>

            <div class="mvc-single-press-badges">
                <span class="mvc-press-hero-badge">
                    <span aria-hidden="true">📰</span>
                    <span>Official Press Release &amp; Newspaper Clipping</span>
                </span>
                <span class="mvc-press-hero-date">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span><?php echo esc_html($disp_date); ?></span>
                </span>
                <span class="mvc-press-hero-source">
                    <span>Source: <?php echo esc_html($pub); ?></span>
                </span>
            </div>

            <h1 class="mvc-press-hero-title"><?php echo esc_html($title); ?></h1>
            <p class="mvc-press-hero-subtitle">
                Official newspaper clipping and public press coverage archived by Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar). Constituent unit of Veer Kunwar Singh University, Ara.
            </p>
        </div>
    </header>

    <!-- 2. UNIFIED MEDIA SUITE PILL SWITCHER -->
    <?php 
    if (function_exists('mvc_render_media_suite_nav')) {
        mvc_render_media_suite_nav('press-media');
    }
    ?>

    <!-- 3. 100% FULL-WIDTH EXPANSIVE CONTENT WORKSPACE -->
    <main class="mvc-press-single-container" id="main-content">
        <div class="mvc-press-layout-grid">
            
            <!-- LEFT COLUMN: CLIPPING VIEWER & FULL CONTENT -->
            <div class="mvc-press-main-col">
                <article class="mvc-press-main-card">
                    
                    <!-- Top Action / Verification Bar -->
                    <div class="mvc-press-card-topbar">
                        <div class="mvc-press-card-caption">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            <span>Official Scanned Newspaper Release</span>
                        </div>
                        <div class="mvc-press-card-controls">
                            <?php if (!empty($thumb_url)) : ?>
                                <a href="<?php echo esc_url($thumb_url); ?>" target="_blank" rel="noopener" class="mvc-ctrl-btn" title="Open full resolution in new tab">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    <span>Raw Scan ↗</span>
                                </a>
                            <?php endif; ?>
                            <button type="button" class="mvc-ctrl-btn" onclick="window.print()" title="Print this press coverage">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                <span>Print</span>
                            </button>
                        </div>
                    </div>

                    <!-- Scanned Newspaper Clipping Display Frame -->
                    <?php if (!empty($thumb_url)) : ?>
                        <div class="mvc-press-clipping-box" id="mvcClippingFrame">
                            <a href="<?php echo esc_url($thumb_url); ?>" target="_blank" rel="noopener" title="Click to view raw high-resolution scan">
                                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($title); ?>" class="mvc-press-clipping-img" loading="eager">
                            </a>
                        </div>

                        <!-- Dual Action Toolbar -->
                        <div class="mvc-press-action-bar">
                            <a href="<?php echo esc_url($thumb_url); ?>" target="_blank" rel="noopener" class="mvc-action-btn mvc-btn-view-full" title="Open High Resolution Scanned Clipping in New Tab">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                                <span>View High-Resolution Scan ↗</span>
                            </a>
                            <a href="<?php echo esc_url($thumb_url); ?>" download class="mvc-action-btn mvc-btn-download" title="Download High-Resolution Image File">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                                <span>Download Scanned Clipping</span>
                            </a>
                            <button type="button" class="mvc-action-btn mvc-btn-print" onclick="window.print()" title="Print Press Coverage Document">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                <span>Print</span>
                            </button>
                            <a href="<?php echo esc_url(home_url('/media/')); ?>" class="mvc-action-btn mvc-btn-back" title="Return to Media Coverage Directory">
                                <span>&larr; All Press Releases</span>
                            </a>
                        </div>
                    <?php else : ?>
                        <div style="padding: 50px 20px; text-align: center; color: #64748b; background: #f8fafc;">
                            <p style="font-size: 16px; margin: 0 0 10px;">📰 High-resolution clipping scan archive is currently being digitalized.</p>
                            <a href="<?php echo esc_url(home_url('/media/')); ?>" class="mvc-action-btn mvc-btn-view-full">&larr; Return to All Media Releases</a>
                        </div>
                    <?php endif; ?>

                    <!-- Editorial Content / Transcript Body -->
                    <?php
                    $raw_content = get_the_content(null, false, $post_id);
                    if (!empty($raw_content)) :
                        ?>
                        <div class="mvc-press-body-section">
                            <div class="mvc-press-body-label">
                                <span aria-hidden="true">📜</span>
                                <span>Press Release Summary &amp; Transcript</span>
                            </div>
                            <div class="mvc-press-body-text">
                                <?php echo wp_kses_post(apply_filters('the_content', $raw_content)); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </article>
            </div>

            <!-- RIGHT COLUMN: INSTITUTIONAL VERIFICATION & STATUTORY METADATA -->
            <aside class="mvc-press-sidebar">
                
                <!-- Institutional Verification Card -->
                <div class="mvc-sidebar-card">
                    <div class="mvc-sidebar-header">
                        <h3>
                            <span aria-hidden="true">🏛️</span>
                            <span>Institutional Verification</span>
                        </h3>
                    </div>
                    <div class="mvc-sidebar-body">
                        
                        <div class="mvc-verify-seal-block">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="College Crest" class="mvc-verify-seal-img" width="52" height="52">
                            <div class="mvc-verify-seal-info">
                                <div class="mvc-verify-inst-name">MAHARSHI VISHWAMITRA MAHAVIDYALAYA</div>
                                <div class="mvc-verify-inst-sub">Buxar – 802101, Bihar (India)</div>
                            </div>
                        </div>

                        <div class="mvc-meta-spec-list">
                            <div class="mvc-meta-spec-row">
                                <span class="mvc-meta-spec-label">Archive Record ID</span>
                                <span class="mvc-meta-spec-value" style="font-family: monospace; font-size: 13px;">MVC/PR/<?php echo esc_html($post_id); ?></span>
                            </div>
                            <div class="mvc-meta-spec-row">
                                <span class="mvc-meta-spec-label">Verification Status</span>
                                <span class="mvc-meta-status-pill">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    <span>Verified Media Record</span>
                                </span>
                            </div>
                            <div class="mvc-meta-spec-row">
                                <span class="mvc-meta-spec-label">Publication / Media Channel</span>
                                <span class="mvc-meta-spec-value"><?php echo esc_html($pub); ?></span>
                            </div>
                            <div class="mvc-meta-spec-row">
                                <span class="mvc-meta-spec-label">Publication Date</span>
                                <span class="mvc-meta-spec-value"><?php echo esc_html($disp_date); ?></span>
                            </div>
                            <div class="mvc-meta-spec-row">
                                <span class="mvc-meta-spec-label">Constituent University</span>
                                <span class="mvc-meta-spec-value">Veer Kunwar Singh University, Ara</span>
                            </div>
                            <div class="mvc-meta-spec-row">
                                <span class="mvc-meta-spec-label">Accreditations &amp; Code</span>
                                <span class="mvc-meta-spec-value">AISHE: C-27158 • UGC 2(f) &amp; 12(B)</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Media Suite Navigation Card -->
                <div class="mvc-sidebar-card">
                    <div class="mvc-sidebar-header">
                        <h3>
                            <span aria-hidden="true">📂</span>
                            <span>Media Channels</span>
                        </h3>
                    </div>
                    <div class="mvc-sidebar-body">
                        <ul class="mvc-sidebar-links">
                            <li class="mvc-sidebar-link-item">
                                <a href="<?php echo esc_url(home_url('/media/')); ?>">
                                    <span>📰 Press &amp; Media Releases</span>
                                    <span class="mvc-s-arrow">→</span>
                                </a>
                            </li>
                            <li class="mvc-sidebar-link-item">
                                <a href="<?php echo esc_url(home_url('/photo-gallery/')); ?>">
                                    <span>📸 Official Photo Gallery</span>
                                    <span class="mvc-s-arrow">→</span>
                                </a>
                            </li>
                            <li class="mvc-sidebar-link-item">
                                <a href="<?php echo esc_url(home_url('/video-gallery/')); ?>">
                                    <span>🎥 Campus Video Gallery</span>
                                    <span class="mvc-s-arrow">→</span>
                                </a>
                            </li>
                            <li class="mvc-sidebar-link-item">
                                <a href="<?php echo esc_url(home_url('/notices/')); ?>">
                                    <span>📢 Official Notices &amp; Circulars</span>
                                    <span class="mvc-s-arrow">→</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Media Inquiries Helpdesk -->
                <div class="mvc-sidebar-helpdesk">
                    <div class="mvc-helpdesk-title">Press &amp; Media Inquiries</div>
                    <div class="mvc-helpdesk-text">
                        For official institutional statements, event coverage permissions, or press inquiries, contact the Public Relations desk.
                    </div>
                    <a href="tel:06183796317" class="mvc-helpdesk-contact">
                        <span>📞 Helpline: <strong>06183-796317</strong></span>
                    </a>
                </div>

            </aside>

        </div>

        <!-- 5. FULL-WIDTH RELATED PRESS RELEASES ROSTER -->
        <?php
        $related_query = new WP_Query(array(
            'post_type'      => 'press-media',
            'posts_per_page' => 4,
            'post__not_in'   => array($post_id),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));

        if ($related_query->have_posts()) :
            ?>
            <section class="mvc-related-press-section" aria-label="Recent Press Releases">
                <div class="mvc-related-header">
                    <h2 class="mvc-related-title">
                        <span aria-hidden="true">🗞️</span>
                        <span>Recent Press &amp; Newspaper Coverage</span>
                    </h2>
                    <a href="<?php echo esc_url(home_url('/media/')); ?>" class="mvc-related-view-all">
                        <span>View All Media Releases</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                <div class="mvc-related-grid">
                    <?php
                    while ($related_query->have_posts()) : $related_query->the_post();
                        $r_id = get_the_ID();
                        $r_date = get_field('date', $r_id) ?: get_post_meta($r_id, 'press_date', true);
                        $r_disp = !empty($r_date) ? (strtotime($r_date) ? date('d M Y', strtotime($r_date)) : $r_date) : get_the_date('d M Y', $r_id);
                        $r_pub  = get_post_meta($r_id, 'press_publication', true) ?: 'Print Media';
                        
                        $r_img  = get_the_post_thumbnail_url($r_id, 'medium_large');
                        if (!$r_img) {
                            $r_raw = get_post_meta($r_id, 'press_image_url', true);
                            if ($r_raw) {
                                $r_img = function_exists('mvc_resolve_media_image_url') ? mvc_resolve_media_image_url($r_raw) : $r_raw;
                            }
                        }
                        if (!$r_img) {
                            $r_img = home_url('/wp-content/uploads/2026/10/mv-college-website-logo.png');
                        }
                        ?>
                        <article class="mvc-related-card">
                            <div class="mvc-related-thumb-box">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                    <img src="<?php echo esc_url($r_img); ?>" alt="<?php the_title_attribute(); ?>" class="mvc-related-thumb-img" loading="lazy">
                                </a>
                                <span class="mvc-related-badge-date"><?php echo esc_html($r_disp); ?></span>
                            </div>
                            <div class="mvc-related-card-body">
                                <span class="mvc-related-source-tag"><?php echo esc_html($r_pub); ?></span>
                                <h3 class="mvc-related-card-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <a href="<?php the_permalink(); ?>" class="mvc-related-read-btn">
                                    <span>Read Clipping</span>
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </section>
        <?php endif; ?>

    </main>

</div>

<?php
get_footer();
