<?php
/**
 * Single Template for 'notice' Custom Post Type
 * Creative, Prestigious & Interactive Institutional Circular Portal
 * Features:
 * - Direct PDF Rendering & Embed Viewer
 * - One-Click Direct Raw PDF Download / Open in New Window
 * - Official College Gazette / Letterhead Memo UI
 * - Print & Share Utilities
 * - Related Circulars Roster
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_post_id = get_the_ID() ?: get_queried_object_id();
$notice_post = get_post($current_post_id);
if ($notice_post) {
    setup_postdata($notice_post);
}

get_header();

$title = get_the_title($current_post_id);
$raw_date = get_field('date_picker', $current_post_id);

$disp_date = '';
if (!empty($raw_date)) {
    $ts = strtotime($raw_date);
    $disp_date = $ts ? date('d F Y', $ts) : $raw_date;
} else {
    $disp_date = get_the_date('d F Y', $current_post_id);
}

// Robust PDF URL Resolution
$pdf = get_field('pdf_file', $current_post_id);
$pdf_url = '';
if (is_array($pdf) && !empty($pdf['url'])) {
    $pdf_url = $pdf['url'];
} elseif (is_numeric($pdf) && (int)$pdf > 0) {
    $pdf_url = wp_get_attachment_url((int)$pdf);
} elseif (is_string($pdf) && !empty($pdf) && $pdf !== '0') {
    $pdf_url = $pdf;
}
if (empty($pdf_url)) {
    $att_id = get_post_meta($current_post_id, '_pdf_attachment_id', true);
    if ($att_id) {
        $pdf_url = wp_get_attachment_url((int)$att_id);
    }
}
if (empty($pdf_url)) {
    $pdf_url = get_post_meta($current_post_id, '_pdf_url', true);
}

// Audience taxonomy
$aud_terms = wp_get_object_terms($current_post_id, 'audience');
$aud_slug = (!empty($aud_terms) && !is_wp_error($aud_terms)) ? $aud_terms[0]->slug : 'students';
$aud_name = (!empty($aud_terms) && !is_wp_error($aud_terms)) ? $aud_terms[0]->name : 'Students';
$is_official = (stripos($aud_name, 'official') !== false);

// Official Memo Reference
$ref_no = 'MVC/NOT/2026/' . str_pad($current_post_id, 4, '0', STR_PAD_LEFT);
$content = get_the_content(null, false, $current_post_id);

// College Logo
$college_logo = get_field('college_logo', 209);
$logo_url = '';
if (is_array($college_logo) && !empty($college_logo['url'])) {
    $logo_url = $college_logo['url'];
} elseif (is_string($college_logo) && !empty($college_logo)) {
    $logo_url = $college_logo;
} else {
    $logo_url = '/wp-content/uploads/2026/10/mv-college-website-logo.png';
}
?>

<div class="mvc-notice-portal-wrapper">
    <style>
    :root {
        --mvc-navy: #002147;
        --mvc-maroon: #800000;
        --mvc-maroon-dark: #580000;
        --mvc-gold: #D4AF37;
        --mvc-gold-dark: #B48200;
        --mvc-cream: #FAF9F6;
        --mvc-border: #E2E8F0;
        --mvc-shadow: 0 10px 30px rgba(0, 33, 71, 0.08);
    }

    .mvc-notice-portal-wrapper {
        background: #F1F5F9;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1E293B;
        padding: 36px 0 80px;
        min-height: 100vh;
        width: 100% !important;
        overflow-x: hidden;
    }
    .mvc-notice-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto;
        padding-left: clamp(16px, 2.5vw, 40px) !important;
        padding-right: clamp(16px, 2.5vw, 40px) !important;
        box-sizing: border-box !important;
    }

    /* Breadcrumbs */
    .mvc-portal-breadcrumbs {
        font-size: 13.5px;
        color: #64748B;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .mvc-portal-breadcrumbs a {
        color: var(--mvc-maroon);
        text-decoration: none;
        font-weight: 600;
    }
    .mvc-portal-breadcrumbs a:hover {
        text-decoration: underline;
    }

    /* Top Notice Banner Card */
    .mvc-notice-hero-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px 36px;
        box-shadow: var(--mvc-shadow);
        border: 1px solid var(--mvc-border);
        margin-bottom: 28px;
        border-top: 5px solid <?php echo $is_official ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
    }
    .mvc-notice-meta-tags {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .mvc-pill-audience {
        background: <?php echo $is_official ? 'var(--mvc-maroon)' : 'var(--mvc-navy)'; ?>;
        color: #ffffff;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-pill-ref {
        background: #EEF2F6;
        color: #334155;
        font-family: 'Courier New', Courier, monospace;
        font-size: 12.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 6px;
        border: 1px solid #CBD5E1;
    }
    .mvc-pill-date {
        background: #FEF3C7;
        color: #92400E;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .mvc-pill-active {
        background: #DCFCE7;
        color: #166534;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .mvc-portal-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--mvc-navy);
        line-height: 1.45;
        margin: 0 0 22px;
    }

    /* Quick Action Toolbar */
    .mvc-actions-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 18px;
        border-top: 1px dashed var(--mvc-border);
    }
    .mvc-btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.25s ease;
        border: none;
    }
    .mvc-btn-primary-pdf {
        background: var(--mvc-maroon);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(128, 0, 0, 0.25);
    }
    .mvc-btn-primary-pdf:hover {
        background: var(--mvc-maroon-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(128, 0, 0, 0.35);
    }
    .mvc-btn-secondary {
        background: #F8FAFC;
        color: #334155 !important;
        border: 1px solid #CBD5E1;
    }
    .mvc-btn-secondary:hover {
        background: #E2E8F0;
        color: var(--mvc-navy) !important;
    }
    .mvc-btn-print {
        background: var(--mvc-navy);
        color: #ffffff !important;
    }
    .mvc-btn-print:hover {
        background: #00142D;
    }

    /* DIRECT PDF INTERACTIVE VIEWER BOX */
    .mvc-pdf-viewer-section {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--mvc-shadow);
        border: 1px solid var(--mvc-border);
        margin-bottom: 35px;
    }
    .mvc-pdf-viewer-bar {
        background: linear-gradient(135deg, var(--mvc-navy) 0%, #001630 100%);
        color: #ffffff;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .mvc-pdf-bar-title {
        font-size: 13.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #F8FAFC;
    }
    .mvc-pdf-bar-title svg {
        color: var(--mvc-gold);
    }
    .mvc-pdf-bar-link {
        color: var(--mvc-gold);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.1);
        padding: 5px 12px;
        border-radius: 6px;
        transition: background 0.2s ease;
    }
    .mvc-pdf-bar-link:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }
    .mvc-pdf-embed-container {
        width: 100%;
        background: #525659;
        position: relative;
    }
    .mvc-pdf-iframe {
        width: 100%;
        height: 840px;
        border: none;
        display: block;
    }
    .mvc-pdf-mobile-callout {
        background: #FEF3C7;
        color: #78350F;
        padding: 12px 20px;
        font-size: 13.5px;
        text-align: center;
        border-top: 1px solid #FDE68A;
        display: none;
    }
    @media (max-width: 768px) {
        .mvc-pdf-iframe {
            height: 520px;
        }
        .mvc-pdf-mobile-callout {
            display: block;
        }
    }

    /* CREATIVE OFFICIAL NOTIFICATION LETTERHEAD MEMO */
    .mvc-letterhead-card {
        background: var(--mvc-cream);
        border: 2px solid #D6D3D1;
        border-radius: 12px;
        padding: 44px 48px;
        box-shadow: var(--mvc-shadow);
        position: relative;
        margin-bottom: 35px;
        background-image: radial-gradient(rgba(0, 33, 71, 0.02) 1px, transparent 0);
        background-size: 24px 24px;
    }
    .mvc-letterhead-card::before {
        content: "";
        position: absolute;
        top: 6px;
        left: 6px;
        right: 6px;
        bottom: 6px;
        border: 1px solid #E7E5E4;
        border-radius: 8px;
        pointer-events: none;
    }
    .mvc-lh-header {
        text-align: center;
        border-bottom: 2px double var(--mvc-maroon);
        padding-bottom: 20px;
        margin-bottom: 25px;
        position: relative;
    }
    .mvc-lh-logo {
        width: 75px;
        height: auto;
        margin-bottom: 8px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }
    .mvc-lh-lockup {
        display: table;
        width: auto;
        max-width: 100%;
        margin: 0 auto 4px auto;
    }
    .mvc-lh-inst-en {
        font-size: 22px;
        font-weight: 800;
        color: var(--mvc-maroon);
        letter-spacing: 0.5px;
        margin: 0 0 2px;
        text-transform: uppercase;
        white-space: nowrap;
        width: 100%;
    }
    .mvc-lh-inst-hi {
        font-size: 19px;
        font-weight: 700;
        color: var(--mvc-navy);
        margin: 0 0 4px;
        font-family: 'Noto Sans Devanagari', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        display: block;
        width: 100%;
        box-sizing: border-box;
        white-space: nowrap;
    }
    @media (max-width: 768px) {
        .mvc-lh-lockup {
            width: 100%;
        }
        .mvc-lh-inst-en {
            white-space: normal;
        }
    }
    .mvc-lh-inst-sub {
        font-size: 13px;
        color: #57534E;
        font-weight: 600;
    }
    .mvc-lh-inst-addr {
        font-size: 12px;
        color: #78716C;
        margin-top: 2px;
    }

    .mvc-lh-dispatch-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        font-size: 13.5px;
        font-weight: 700;
        color: #292524;
        padding: 8px 12px;
        background: #E7E5E4;
        border-radius: 6px;
    }
    .mvc-lh-order-banner {
        text-align: center;
        margin: 20px 0;
    }
    .mvc-lh-order-banner span {
        display: inline-block;
        background: var(--mvc-maroon);
        color: #ffffff;
        padding: 6px 20px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .mvc-lh-subject-box {
        margin: 20px 0 22px;
        padding: 14px 18px;
        background: #ffffff;
        border-left: 4px solid var(--mvc-navy);
        border-radius: 4px;
        font-size: 15px;
        font-weight: 700;
        color: var(--mvc-navy);
        line-height: 1.5;
    }
    .mvc-lh-subject-label {
        font-size: 12px;
        text-transform: uppercase;
        color: var(--mvc-maroon);
        display: block;
        margin-bottom: 2px;
    }

    .mvc-lh-body-text {
        font-size: 15.5px;
        line-height: 1.8;
        color: #292524;
        margin-bottom: 35px;
    }
    .mvc-lh-body-text p {
        margin-bottom: 16px;
    }

    .mvc-lh-signatory-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 35px;
        padding-top: 20px;
        border-top: 1px dashed #D6D3D1;
    }
    .mvc-lh-copies-box {
        font-size: 12.5px;
        color: #57534E;
        line-height: 1.6;
        max-width: 55%;
    }
    .mvc-lh-sign-box {
        text-align: right;
    }
    .mvc-lh-sign-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--mvc-maroon);
        margin-bottom: 2px;
    }
    .mvc-lh-sign-name {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--mvc-navy);
    }
    .mvc-lh-sign-desig {
        font-size: 12.5px;
        color: #44403C;
        font-weight: 600;
    }
    .mvc-lh-seal-badge {
        display: inline-block;
        margin-top: 8px;
        padding: 3px 10px;
        background: #FEE2E2;
        border: 1px dashed var(--mvc-maroon);
        border-radius: 4px;
        color: var(--mvc-maroon);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* RELATED CIRCULARS ROSTER */
    .mvc-related-notices-sec {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: var(--mvc-shadow);
        border: 1px solid var(--mvc-border);
        margin-bottom: 30px;
    }
    .mvc-sec-heading {
        font-size: 18px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mvc-related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .mvc-related-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
    }
    .mvc-related-card:hover {
        border-color: var(--mvc-maroon);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .mvc-related-date {
        font-size: 12px;
        color: #64748B;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .mvc-related-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--mvc-navy);
        line-height: 1.45;
        margin: 0 0 12px;
        text-decoration: none;
    }
    .mvc-related-card:hover .mvc-related-title {
        color: var(--mvc-maroon);
    }
    .mvc-related-btn {
        font-size: 12px;
        font-weight: 700;
        color: var(--mvc-maroon);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Bottom Navigation Bar */
    .mvc-portal-nav-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid var(--mvc-border);
    }
    .mvc-portal-nav-footer a {
        color: var(--mvc-navy);
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .mvc-portal-nav-footer a:hover {
        color: var(--mvc-maroon);
    }

    /* PRINT STYLESHEET */
    @media print {
        header, footer, .mvc-portal-breadcrumbs, .mvc-actions-toolbar, 
        .mvc-pdf-viewer-section, .mvc-related-notices-sec, .mvc-portal-nav-footer {
            display: none !important;
        }
        body, .mvc-notice-portal-wrapper {
            background: #ffffff !important;
            padding: 0 !important;
        }
        .mvc-letterhead-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            background: none !important;
        }
        .mvc-letterhead-card::before {
            display: none !important;
        }
    }
    @media (max-width: 900px) {
        .mvc-related-grid {
            grid-template-columns: 1fr;
        }
        .mvc-letterhead-card {
            padding: 24px 20px;
        }
        .mvc-lh-signatory-row {
            flex-direction: column;
            gap: 24px;
            align-items: flex-start;
        }
        .mvc-lh-copies-box {
            max-width: 100%;
        }
        .mvc-lh-sign-box {
            text-align: left;
            width: 100%;
        }
    }
    </style>

    <div class="mvc-notice-container">

        <!-- Breadcrumb Navigation -->
        <nav class="mvc-portal-breadcrumbs" aria-label="Breadcrumb">
            <a href="/">Home</a> &rsaquo;
            <a href="/#mvcAnnouncements">Announcements Hub</a> &rsaquo;
            <span><?php echo esc_html(wp_trim_words($title, 6)); ?></span>
        </nav>

        <!-- TOP NOTICE BANNER CARD -->
        <header class="mvc-notice-hero-card">
            
            <div class="mvc-notice-meta-tags">
                <span class="mvc-pill-audience"><?php echo esc_html($is_official ? '🏛️ Officials & Faculty Circular' : '🎓 Student Notification'); ?></span>
                <span class="mvc-pill-ref">MEMO: <?php echo esc_html($ref_no); ?></span>
                <span class="mvc-pill-date">📅 Issued: <?php echo esc_html($disp_date); ?></span>
                <span class="mvc-pill-active">🟢 Active Notice</span>
            </div>

            <h1 class="mvc-portal-title"><?php echo esc_html($title); ?></h1>

            <!-- QUICK ACTION TOOLBAR -->
            <div class="mvc-actions-toolbar">
                <?php if (!empty($pdf_url)) : ?>
                    <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-btn-action mvc-btn-primary-pdf">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
                        Open Official PDF Directly (New Window)
                    </a>
                    
                    <a href="<?php echo esc_url($pdf_url); ?>" download class="mvc-btn-action mvc-btn-secondary">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                        Download PDF
                    </a>
                <?php endif; ?>

                <button type="button" onclick="window.print()" class="mvc-btn-action mvc-btn-print">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                    Print Circular
                </button>

                <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Notification URL copied to clipboard!');" class="mvc-btn-action mvc-btn-secondary">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                    Copy Link
                </button>
            </div>

        </header>

        <!-- DIRECT PDF INTERACTIVE VIEWER -->
        <?php if (!empty($pdf_url)) : ?>
            <section class="mvc-pdf-viewer-section" aria-label="Official PDF Document Preview">
                <div class="mvc-pdf-viewer-bar">
                    <div class="mvc-pdf-bar-title">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5z"/></svg>
                        <span>Official Scanned / University Notification Document</span>
                    </div>
                    <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-pdf-bar-link">
                        ⛶ Open Raw Document in Fullscreen &rarr;
                    </a>
                </div>

                <div class="mvc-pdf-embed-container">
                    <iframe src="<?php echo esc_url($pdf_url); ?>#toolbar=1&navpanes=0&scrollbar=1" class="mvc-pdf-iframe" title="<?php echo esc_attr($title); ?>">
                        <p style="padding: 30px; text-align: center; color: #fff;">
                            Your browser does not support embedded PDF viewing. 
                            <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" style="color: #D4AF37; text-decoration: underline;">Click here to open and download the official PDF file directly &rarr;</a>
                        </p>
                    </iframe>
                </div>

                <div class="mvc-pdf-mobile-callout">
                    📱 On Mobile or Tablet? <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" style="color: #800000; font-weight: 800; text-decoration: underline;">Tap here to open full PDF document directly</a>.
                </div>
            </section>
        <?php endif; ?>

        <!-- CREATIVE OFFICIAL INSTITUTIONAL NOTIFICATION LETTERHEAD MEMO -->
        <article class="mvc-letterhead-card">
            
            <header class="mvc-lh-header">
                <?php if (!empty($logo_url)) : ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="MV College Crest" class="mvc-lh-logo">
                <?php endif; ?>
                <div class="mvc-title-lockup mvc-lh-lockup">
                    <h2 class="mvc-lh-inst-en">Maharshi Vishwamitra Mahavidyalaya, Buxar</h2>
                    <div class="mvc-lh-inst-hi">महर्षि विश्वामित्र महाविद्यालय, बक्सर</div>
                </div>
                <div class="mvc-lh-inst-sub">A Premier Constituent Unit of Veer Kunwar Singh University, Ara (Bihar) &bull; UGC 2(f) &amp; 12(B) Recognized &bull; AISHE: C-27158</div>
                <div class="mvc-lh-inst-addr">Charitravan, Buxar - 802101 &bull; Phone: 0612-2672236 &bull; Email: mvcollegebuxar@gmail.com</div>
            </header>

            <div class="mvc-lh-dispatch-row">
                <span>MEMO REF: <?php echo esc_html($ref_no); ?></span>
                <span>DATED: <?php echo esc_html($disp_date); ?></span>
            </div>

            <div class="mvc-lh-order-banner">
                <span>OFFICE OF THE PRINCIPAL / विश्वविद्यालय कार्यालय आदेश</span>
            </div>

            <div class="mvc-lh-subject-box">
                <span class="mvc-lh-subject-label">Subject / विषय:</span>
                <?php echo esc_html($title); ?>
            </div>

            <!-- Content Area -->
            <div class="mvc-lh-body-text">
                <?php
                if (!empty($content)) {
                    echo wp_kses_post($content);
                } else {
                    ?>
                    <p>This is to inform all students, faculty members, and administrative staff that in pursuance of University regulations and institutional academic guidelines, the notification regarding <strong><?php echo esc_html($title); ?></strong> has been formally issued on <strong><?php echo esc_html($disp_date); ?></strong>.</p>
                    <p>All concerned individuals are hereby advised to take cognisance of the schedules, instructions, and directives provided in the official document linked above and ensure prompt compliance within the stipulated deadlines.</p>
                    <p>For any clarifications regarding this circular, students may contact their respective Department Heads or the college administrative counter during working hours.</p>
                    <?php
                }
                ?>
            </div>

            <!-- Signatory Footer -->
            <footer class="mvc-lh-signatory-row">
                <div class="mvc-lh-copies-box">
                    <strong>Copy forwarded for information &amp; necessary action to:</strong><br>
                    1. Notice Board (College &amp; Departments)<br>
                    2. All Heads of Departments (HODs) &amp; Faculty Members<br>
                    3. Examination Section &amp; IQAC Cell<br>
                    4. College Official Website Archive / Guard File
                </div>

                <div class="mvc-lh-sign-box">
                    <div class="mvc-lh-sign-title">By Order of the Principal</div>
                    <div class="mvc-lh-sign-name">Prof. (Dr.) Krishna Kant Singh</div>
                    <div class="mvc-lh-sign-desig">Principal, M.V. College, Buxar</div>
                    <span class="mvc-lh-seal-badge">Official Seal &amp; Authority</span>
                </div>
            </footer>

        </article>

        <!-- RECENT & RELATED CIRCULARS ROSTER -->
        <?php
        $related_notices = new WP_Query(array(
            'post_type'      => 'notice',
            'posts_per_page' => 3,
            'post__not_in'   => array($current_post_id),
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'audience',
                    'field'    => 'slug',
                    'terms'    => array($aud_slug)
                )
            )
        ));

        if ($related_notices->have_posts()) :
            ?>
            <section class="mvc-related-notices-sec">
                <h3 class="mvc-sec-heading">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Recent Circulars for <?php echo esc_html($aud_name); ?>
                </h3>
                <div class="mvc-related-grid">
                    <?php
                    while ($related_notices->have_posts()) : $related_notices->the_post();
                        $r_id = get_the_ID();
                        $r_date_raw = get_field('date_picker', $r_id);
                        $r_disp_date = $r_date_raw ? (strtotime($r_date_raw) ? date('d M Y', strtotime($r_date_raw)) : $r_date_raw) : get_the_date('d M Y', $r_id);
                        
                        $r_pdf = get_field('pdf_file', $r_id);
                        $r_pdf_url = '';
                        if (is_array($r_pdf) && !empty($r_pdf['url'])) $r_pdf_url = $r_pdf['url'];
                        elseif (is_numeric($r_pdf) && (int)$r_pdf > 0) $r_pdf_url = wp_get_attachment_url((int)$r_pdf);
                        elseif (is_string($r_pdf) && !empty($r_pdf) && $r_pdf !== '0') $r_pdf_url = $r_pdf;
                        if (empty($r_pdf_url)) {
                            $r_att_id = get_post_meta($r_id, '_pdf_attachment_id', true);
                            if ($r_att_id) $r_pdf_url = wp_get_attachment_url((int)$r_att_id);
                        }
                        if (empty($r_pdf_url)) $r_pdf_url = get_post_meta($r_id, '_pdf_url', true);
                        ?>
                        <div class="mvc-related-card">
                            <div>
                                <div class="mvc-related-date">📅 <?php echo esc_html($r_disp_date); ?></div>
                                <a href="<?php echo esc_url($r_pdf_url ?: get_permalink($r_id)); ?>" <?php echo $r_pdf_url ? 'target="_blank" rel="noopener"' : ''; ?> class="mvc-related-title">
                                    <?php the_title(); ?>
                                </a>
                            </div>
                            <div style="display: flex; gap: 8px; margin-top: 10px;">
                                <?php if ($r_pdf_url) : ?>
                                    <a href="<?php echo esc_url($r_pdf_url); ?>" target="_blank" rel="noopener" class="mvc-related-btn">
                                        📄 PDF Direct &rarr;
                                    </a>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="mvc-related-btn" style="color: var(--mvc-navy);">
                                    Details &rarr;
                                </a>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- BOTTOM PORTAL NAVIGATION -->
        <nav class="mvc-portal-nav-footer">
            <a href="/">&larr; Return to College Homepage</a>
            <a href="/#mvcAnnouncements">View All Announcements &rarr;</a>
            <a href="https://mvcollegebuxarmanage.com/" target="_blank" rel="noopener">Online Student Portal &rarr;</a>
        </nav>

    </div>
</div>

<?php
get_footer();
