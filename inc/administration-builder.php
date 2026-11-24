<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Our Administration Page Builder - 10/10 Master Standard
 * Strictly synchronized with official website benchmark (mvcollegebuxar.ac.in/our-administration)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Our Administration Page
 */
function mvc_render_our_administration_page($override_form_id = null) {
    global $mvc_shortcode_context;
    $site_url = home_url();

    // Authentic portraits from local uploads
    $governor_img  = $site_url . '/wp-content/uploads/governing-body/GOV_1775040359_GOV_1773653129_Lt. Gen. Syed Ata Hasnain-Photoroom-Photoroom.png';
    $vc_img        = $site_url . '/wp-content/uploads/governing-body/GOV_1775040375_GOV_1773652806_GOV_1767416782_GOV_1763905952_GOV_1763207314_-image-1761377173-sm-Photoroom-Photoroom.png';
    $principal_img = $site_url . '/wp-content/uploads/governing-body/GOV_1773653028_GOV_1769493095_WhatsApp Image 2026-01-26 at 12.38.38 PM-Photoroom.png';

    // Query leadership CPT if available for dynamic overrides
    $leaders_query = new WP_Query([
        'post_type'      => 'leadership',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    ]);

    if ($leaders_query->have_posts()) {
        while ($leaders_query->have_posts()) {
            $leaders_query->the_post();
            $title = get_the_title();
            $thumb = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if ($thumb) {
                if (stripos($title, 'Governor') !== false || stripos($title, 'Hasnain') !== false || stripos($title, 'Arlekar') !== false) {
                    $governor_img = $thumb;
                } elseif (stripos($title, 'Chaturvedi') !== false || stripos($title, 'Vice') !== false) {
                    $vc_img = $thumb;
                } elseif (stripos($title, 'Krishna Kant') !== false || stripos($title, 'Principal') !== false) {
                    $principal_img = $thumb;
                }
            }
        }
        wp_reset_postdata();
    }

    // Query latest administrative notices/circulars
    $notices_query = new WP_Query([
        'post_type'      => 'notice',
        'post_status'    => 'publish',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    ob_start();
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #5c0000;
            --mvc-gold: #D4AF37;
            --mvc-gold-dark: #B48200;
            --mvc-gold-light: #FFF9E6;
            --mvc-cream: #FAF9F6;
            --mvc-slate: #0F172A;
            --mvc-muted: #64748B;
            --mvc-border: #E2E8F0;
        }

        /* 100% Full-Width Stretched Container */
        body.page-template-template-our-administration #page,
        body.page-template-template-our-administration .site-content,
        body.page-template-template-our-administration .ast-container,
        body.page-template-template-our-administration #content,
        body.page-template-template-our-administration #primary,
        body.page-template-template-our-administration #main,
        body.page-template-template-our-administration .entry-content {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .mvc-admin-wrapper {
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
        .mvc-admin-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #0a3366 100%);
            color: #FFFFFF;
            padding: clamp(40px, 5vw, 64px) clamp(20px, 4vw, 60px);
            position: relative;
            text-align: center;
            border-bottom: 4px solid var(--mvc-gold);
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .mvc-admin-hero-inner {
            max-width: 100%;
            margin: 0 auto;
        }

        .mvc-admin-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 16px;
        }

        .mvc-admin-breadcrumb a {
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-admin-breadcrumb a:hover {
            color: var(--mvc-gold);
        }

        .mvc-admin-hero h1 {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .mvc-admin-hero-hindi {
            font-size: clamp(16px, 2.4vw, 20px);
            font-weight: 600;
            color: var(--mvc-gold);
            margin-bottom: 12px;
        }

        .mvc-admin-hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.6;
            margin: 0 auto;
            max-width: 900px;
        }

        /* Sticky Sectional Stepper Track */
        .mvc-stepper-wrap {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #FFFFFF;
            border-bottom: 1px solid var(--mvc-border);
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.06);
            display: flex;
            align-items: center;
            padding: 0 clamp(10px, 2vw, 24px);
        }

        .mvc-stepper-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            white-space: nowrap;
            padding: 12px 6px;
            scrollbar-width: none;
            flex: 1;
        }

        .mvc-stepper-nav::-webkit-scrollbar {
            display: none;
        }

        .mvc-step-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            color: var(--mvc-navy);
            background: #F1F5F9;
            text-decoration: none;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .mvc-step-pill span.num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--mvc-navy);
            color: #FFFFFF;
            font-size: 11px;
        }

        .mvc-step-pill:hover,
        .mvc-step-pill.active {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        .mvc-step-pill:hover span.num,
        .mvc-step-pill.active span.num {
            background: var(--mvc-gold);
            color: var(--mvc-navy);
        }

        .mvc-step-scroll-btn {
            background: #F1F5F9;
            border: 1px solid var(--mvc-border);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--mvc-navy);
            font-weight: bold;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .mvc-step-scroll-btn:hover {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        /* Container - 100% Full Width */
        .mvc-admin-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 40px clamp(20px, 4vw, 60px) 0 !important;
            box-sizing: border-box !important;
        }

        /* Section Header */
        .mvc-section-head {
            margin-bottom: 28px;
            border-left: 5px solid var(--mvc-navy);
            padding-left: 16px;
        }

        .mvc-section-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--mvc-maroon);
            margin-bottom: 4px;
        }

        .mvc-section-title {
            font-size: clamp(22px, 3vw, 28px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px;
        }

        .mvc-section-sub {
            font-size: 14.5px;
            color: var(--mvc-muted);
            margin: 0;
        }

        /* Dignitaries Stack (Section 01) */
        .mvc-leaders-stack {
            display: flex;
            flex-direction: column;
            gap: 36px;
            margin-bottom: 60px;
        }

        .mvc-leader-row-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 33, 71, 0.05);
            display: grid;
            grid-template-columns: 300px 1fr;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .mvc-leader-row-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 33, 71, 0.09);
        }

        .mvc-leader-photo-col {
            background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            text-align: center;
            border-right: 1px solid var(--mvc-border);
            position: relative;
        }

        .mvc-leader-photo-wrap {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #FFFFFF;
            box-shadow: 0 6px 18px rgba(0, 33, 71, 0.15);
            margin-bottom: 16px;
            background: #FFFFFF;
        }

        .mvc-leader-photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .mvc-leader-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 14px;
            border-radius: 999px;
            margin-top: 4px;
        }

        .mvc-badge-chancellor {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }

        .mvc-badge-vc {
            background: #E0E7FF;
            color: #3730A3;
            border: 1px solid #C7D2FE;
        }

        .mvc-badge-principal {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .mvc-leader-content-col {
            padding: clamp(24px, 3.5vw, 36px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mvc-leader-header {
            margin-bottom: 16px;
            border-bottom: 1px solid #F1F5F9;
            padding-bottom: 14px;
        }

        .mvc-leader-name {
            font-size: clamp(20px, 2.6vw, 24px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
            line-height: 1.25;
        }

        .mvc-leader-designation {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--mvc-maroon);
        }

        .mvc-leader-body {
            font-size: 14.5px;
            line-height: 1.7;
            color: #334155;
            margin-bottom: 20px;
        }

        .mvc-leader-body p {
            margin: 0 0 10px;
        }

        .mvc-leader-body p:last-child {
            margin-bottom: 0;
        }

        .mvc-leader-meta-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: var(--mvc-muted);
            margin-bottom: 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        .mvc-leader-meta-box strong {
            color: var(--mvc-slate);
        }

        .mvc-leader-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .mvc-btn-profile {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--mvc-navy);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mvc-btn-profile:hover {
            background: var(--mvc-maroon);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        .mvc-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            color: var(--mvc-navy);
            font-size: 13.5px;
            font-weight: 700;
            padding: 9px 18px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mvc-btn-secondary:hover {
            border-color: var(--mvc-navy);
            background: #F8FAFC;
            color: var(--mvc-navy);
        }

        /* Section 02: Statutory Administrative Cells */
        .mvc-cells-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
        }

        .mvc-cell-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .mvc-cell-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 33, 71, 0.08);
            border-color: var(--mvc-gold);
        }

        .mvc-cell-icon {
            font-size: 28px;
            margin-bottom: 12px;
        }

        .mvc-cell-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px;
        }

        .mvc-cell-role {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--mvc-maroon);
            margin-bottom: 10px;
        }

        .mvc-cell-desc {
            font-size: 13px;
            color: #475569;
            line-height: 1.55;
            margin: 0 0 14px;
            flex: 1;
        }

        .mvc-cell-meta {
            font-size: 12px;
            color: var(--mvc-muted);
            border-top: 1px solid #F1F5F9;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        /* Section 03: Direct Operational Gateways */
        .mvc-gateways-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
        }

        .mvc-gateway-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 22px;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            border-top: 4px solid var(--mvc-navy);
        }

        .mvc-gateway-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 33, 71, 0.08);
            border-top-color: var(--mvc-gold);
        }

        .mvc-gateway-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin-bottom: 6px;
        }

        .mvc-gateway-desc {
            font-size: 13px;
            color: var(--mvc-muted);
            line-height: 1.5;
            margin: 0 0 14px;
        }

        .mvc-gateway-arrow {
            font-size: 13px;
            font-weight: 700;
            color: var(--mvc-maroon);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Section 04: Administrative Directives (Notices) */
        .mvc-directives-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
        }

        .mvc-directive-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.04);
        }

        .mvc-directive-date {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--mvc-maroon);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .mvc-directive-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--mvc-navy);
            margin: 0 0 12px;
            line-height: 1.45;
        }

        .mvc-directive-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            border-top: 1px solid #F1F5F9;
            padding-top: 12px;
        }

        .mvc-btn-direct {
            background: var(--mvc-navy);
            color: #FFFFFF;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .mvc-btn-direct:hover {
            background: var(--mvc-maroon);
            color: #FFFFFF;
        }

        .mvc-btn-memo {
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            color: var(--mvc-navy);
            font-size: 12px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
        }

        /* Section 05: Statutory Affiliation Factsheet Table */
        .mvc-factsheet-table-wrap {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            margin-bottom: 60px;
        }

        .mvc-factsheet-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        .mvc-factsheet-table th {
            background: var(--mvc-navy);
            color: #FFFFFF;
            padding: 14px 18px;
            font-weight: 700;
            font-size: 13.5px;
            letter-spacing: 0.3px;
        }

        .mvc-factsheet-table td {
            padding: 13px 18px;
            border-bottom: 1px solid #F1F5F9;
            color: #334155;
            vertical-align: middle;
        }

        .mvc-factsheet-table tr:last-child td {
            border-bottom: none;
        }

        .mvc-factsheet-table tr:hover td {
            background: #F8FAFC;
        }

        .mvc-factsheet-table td strong {
            color: var(--mvc-navy);
        }

        /* Section 06: Official Governance Inquiries Portal (WPForms) */
        .mvc-inquiry-portal-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 33, 71, 0.06);
            overflow: hidden;
            margin-bottom: 30px;
            border-top: 5px solid var(--mvc-gold);
        }

        .mvc-portal-head {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, #0a3366 100%);
            color: #FFFFFF;
            padding: 24px 30px;
        }

        .mvc-portal-title {
            font-size: 20px;
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 6px;
        }

        .mvc-portal-sub {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
            line-height: 1.5;
        }

        .mvc-portal-body {
            padding: clamp(24px, 4vw, 36px);
        }

        /* WPForms Styling Enhancements */
        .mvc-portal-body .wpforms-form {
            max-width: 100% !important;
        }

        .mvc-portal-body .wpforms-field-label {
            font-weight: 700 !important;
            color: var(--mvc-navy) !important;
            font-size: 13.5px !important;
            margin-bottom: 6px !important;
        }

        .mvc-portal-body input[type="text"],
        .mvc-portal-body input[type="email"],
        .mvc-portal-body select,
        .mvc-portal-body textarea {
            border: 1.5px solid #CBD5E1 !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 14px !important;
            color: var(--mvc-slate) !important;
            box-shadow: none !important;
            transition: border-color 0.2s !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .mvc-portal-body input[type="text"]:focus,
        .mvc-portal-body input[type="email"]:focus,
        .mvc-portal-body select:focus,
        .mvc-portal-body textarea:focus {
            border-color: var(--mvc-navy) !important;
            outline: none !important;
        }

        .mvc-portal-body .wpforms-submit {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-maroon) 100%) !important;
            color: #FFFFFF !important;
            font-weight: 800 !important;
            font-size: 14px !important;
            padding: 12px 28px !important;
            border-radius: 8px !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.25s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .mvc-portal-body .wpforms-submit:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 18px rgba(0, 33, 71, 0.2) !important;
        }

        /* Responsive Mobile Breakpoints */
        @media (max-width: 820px) {
            .mvc-leader-row-card {
                grid-template-columns: 1fr;
            }
            .mvc-leader-photo-col {
                border-right: none;
                border-bottom: 1px solid var(--mvc-border);
                padding: 26px 20px;
            }
            .mvc-leader-photo-wrap {
                width: 140px;
                height: 140px;
            }
        }

        @media (max-width: 767px) {
            .mvc-factsheet-table thead {
                display: none;
            }
            .mvc-factsheet-table, 
            .mvc-factsheet-table tbody, 
            .mvc-factsheet-table tr, 
            .mvc-factsheet-table td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
            .mvc-factsheet-table tr {
                border-bottom: 1px solid var(--mvc-border);
                padding: 12px 14px;
            }
            .mvc-factsheet-table td {
                display: flex;
                justify-content: space-between;
                padding: 6px 0;
                border-bottom: none;
                text-align: right;
            }
            .mvc-factsheet-table td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--mvc-navy);
                text-align: left;
                padding-right: 12px;
            }
        }
    </style>

    <div class="mvc-admin-wrapper">
        <!-- Hero Banner -->
        <header class="mvc-admin-hero">
            <div class="mvc-admin-hero-inner">
                <nav class="mvc-admin-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>">Administration</a>
                    <span>/</span>
                    <span>Our Administration</span>
                </nav>

                <h1>Our Administration</h1>
                <div class="mvc-admin-hero-hindi">महाविद्यालय शीर्ष प्रशासन एवं सांविधिक प्रभाग</div>
                <p class="mvc-admin-hero-desc">
                    Statutory Governance &amp; Executive Leadership of Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar).
                    A Constituent Unit of Veer Kunwar Singh University (VKSU), Ara &bull; Approved under UGC Section 2(f) &amp; 12(B).
                </p>
            </div>
        </header>

        <!-- Sticky Sectional Stepper Navigation -->
        <nav class="mvc-stepper-wrap" aria-label="Page Sections Navigation">
            <button class="mvc-step-scroll-btn" onclick="document.getElementById('mvcAdminStepper').scrollBy({left: -200, behavior: 'smooth'})" aria-label="Scroll Stepper Left">&larr;</button>
            <div class="mvc-stepper-nav" id="mvcAdminStepper">
                <a href="#dignitaries" class="mvc-step-pill active"><span class="num">01</span> Apex Dignitaries</a>
                <a href="#admin-cells" class="mvc-step-pill"><span class="num">02</span> Statutory Administrative Cells</a>
                <a href="#gateways" class="mvc-step-pill"><span class="num">03</span> Operational Gateways</a>
                <a href="#directives" class="mvc-step-pill"><span class="num">04</span> Administrative Directives</a>
                <a href="#factsheet" class="mvc-step-pill"><span class="num">05</span> Institutional Factsheet</a>
            </div>
            <button class="mvc-step-scroll-btn" onclick="document.getElementById('mvcAdminStepper').scrollBy({left: 200, behavior: 'smooth'})" aria-label="Scroll Stepper Right">&rarr;</button>
        </nav>

        <main class="mvc-admin-container" id="admin-main">

            <!-- ==========================================
                 SECTION 01: APEX COLLEGIATE DIGNITARIES
                 ========================================== -->
            <section id="dignitaries">
                <div class="mvc-section-head">
                    <div class="mvc-section-badge">Statutory Hierarchy</div>
                    <h2 class="mvc-section-title">Apex Collegiate Dignitaries</h2>
                    <p class="mvc-section-sub">State Chancellor, University Vice-Chancellor &amp; Collegiate Principal</p>
                </div>

                <div class="mvc-leaders-stack">

                    <!-- 1. The Chancellor (Governor of Bihar) -->
                    <article class="mvc-leader-row-card" itemscope itemtype="https://schema.org/Person">
                        <div class="mvc-leader-photo-col">
                            <div class="mvc-leader-photo-wrap">
                                <img src="<?php echo esc_url($governor_img); ?>" alt="Hon'ble Governor of Bihar" itemprop="image" loading="lazy">
                            </div>
                            <span class="mvc-leader-badge mvc-badge-chancellor">Chancellor</span>
                        </div>

                        <div class="mvc-leader-content-col">
                            <div class="mvc-leader-header">
                                <h3 class="mvc-leader-name" itemprop="name">Hon'ble Governor of Bihar</h3>
                                <div class="mvc-leader-designation" itemprop="jobTitle">Chancellor &bull; Veer Kunwar Singh University &amp; Universities of Bihar</div>
                            </div>

                            <div class="mvc-leader-body" itemprop="description">
                                <p>
                                    The Governor of Bihar is the Constitutional Head of the State of Bihar and exercises powers as defined in the Constitution of India. The Governor is also the ex-officio Chancellor of universities in the State.
                                </p>
                                <p>
                                    The Governor's Secretariat provides secretarial assistance to the Governor in discharging his constitutional, ceremonial, and other State responsibilities, as well as his duties as the Chancellor of universities in the State.
                                </p>
                            </div>

                            <div class="mvc-leader-meta-box">
                                <div><strong>🏛️ Chancellor's Secretariat:</strong> Bihar Lok Bhavan, Patna – 800022</div>
                                <div><strong>📞 Telephone:</strong> 0612-2786100</div>
                                <div><strong>🌐 Portal:</strong> governor.bih.nic.in</div>
                            </div>

                            <div class="mvc-leader-actions">
                                <a href="https://governor.bih.nic.in" target="_blank" rel="noopener" class="mvc-btn-profile">
                                    Visit Raj Bhavan Secretariat ↗
                                </a>
                                <a href="<?php echo esc_url($site_url . '/university-administration/'); ?>" class="mvc-btn-secondary">
                                    University Governance Structure &rarr;
                                </a>
                            </div>
                        </div>
                    </article>

                    <!-- 2. The Vice-Chancellor, VKSU -->
                    <article class="mvc-leader-row-card" itemscope itemtype="https://schema.org/Person">
                        <div class="mvc-leader-photo-col">
                            <div class="mvc-leader-photo-wrap">
                                <img src="<?php echo esc_url($vc_img); ?>" alt="Prof. Shailendra Kr Chaturvedi" itemprop="image" loading="lazy">
                            </div>
                            <span class="mvc-leader-badge mvc-badge-vc">Affiliating University</span>
                        </div>

                        <div class="mvc-leader-content-col">
                            <div class="mvc-leader-header">
                                <h3 class="mvc-leader-name" itemprop="name">Prof. Shailendra Kr Chaturvedi</h3>
                                <div class="mvc-leader-designation" itemprop="jobTitle">Vice-Chancellor &bull; Veer Kunwar Singh University (VKSU), Ara</div>
                            </div>

                            <div class="mvc-leader-body" itemprop="description">
                                <p>
                                    At VKSU (Veer Kunwar Singh University), our mission is to be a center of academic excellence and holistic development. Since our establishment in 1992, we have continued to grow — offering 50+ courses, supporting 56+ startups, and employing 500+ professors.
                                </p>
                                <p>
                                    We are deeply committed to research, innovation, and social responsibility. Our university extends its reach across the districts of Bhojpur, Buxar, Kaimur, and Rohtas, providing quality education and fostering talent. VKSU believes in empowering its students not only academically, but also by nurturing entrepreneurial spirit and leadership.
                                </p>
                            </div>

                            <div class="mvc-leader-meta-box">
                                <div><strong>🏛️ University Secretariat:</strong> Katira, Ara, Bihar – 802301</div>
                                <div><strong>📞 Telephone:</strong> 06182-239369</div>
                                <div><strong>🌐 Portal:</strong> vksu.ac.in</div>
                            </div>

                            <div class="mvc-leader-actions">
                                <a href="https://vksu.ac.in" target="_blank" rel="noopener" class="mvc-btn-profile">
                                    Visit VKSU Official Portal ↗
                                </a>
                                <a href="<?php echo esc_url($site_url . '/university-administration/'); ?>" class="mvc-btn-secondary">
                                    VKSU Administration Details &rarr;
                                </a>
                            </div>
                        </div>
                    </article>

                    <!-- 3. The Principal, M.V. College -->
                    <article class="mvc-leader-row-card" itemscope itemtype="https://schema.org/Person">
                        <div class="mvc-leader-photo-col">
                            <div class="mvc-leader-photo-wrap">
                                <img src="<?php echo esc_url($principal_img); ?>" alt="Prof. (Dr.) Krishna Kant Singh" itemprop="image" loading="lazy">
                            </div>
                            <span class="mvc-leader-badge mvc-badge-principal">Institutional Head</span>
                        </div>

                        <div class="mvc-leader-content-col">
                            <div class="mvc-leader-header">
                                <h3 class="mvc-leader-name" itemprop="name">Prof. (Dr.) Krishna Kant Singh</h3>
                                <div class="mvc-leader-designation" itemprop="jobTitle">Principal &bull; Maharshi Vishwamitra Mahavidyalaya (M.V. College), Buxar</div>
                            </div>

                            <div class="mvc-leader-body" itemprop="description">
                                <p>
                                    We are committed to see that our college emerges as the best college on the educational map of India. In order to achieve our goal we are working hard to see that the problems of the students must not go unredressed. We have tried our level best to lay down a solid foundation to help the students overcome the challenges.
                                </p>
                                <p>
                                    A conducive atmosphere has been created inside the campus and the students are groomed in such a way that they romp home with flying colors, bringing laurels to the institution and society as well. Their effort will pay dividend and will definitely leave no stone unturned to contribute their energy for the betterment of society. Personality development &amp; career building are the two main objectives which we have strived for.
                                </p>
                            </div>

                            <div class="mvc-leader-meta-box">
                                <div><strong>🏛️ Principal's Office:</strong> Administrative Block, M.V. College, Charitravan, Buxar – 802101</div>
                                <div><strong>📞 Telephone:</strong> 06183-295055</div>
                                <div><strong>✉️ Email:</strong> principalmvcbuxar@gmail.com</div>
                            </div>

                            <div class="mvc-leader-actions">
                                <a href="<?php echo esc_url($site_url . '/principal-desk/'); ?>" class="mvc-btn-profile">
                                    Visit Principal's Desk &rarr;
                                </a>
                                <a href="<?php echo esc_url($site_url . '/committees-cells/'); ?>" class="mvc-btn-secondary">
                                    Statutory Committees &amp; Cells &rarr;
                                </a>
                            </div>
                        </div>
                    </article>

                </div>
            </section>

            <!-- ==========================================
                 SECTION 02: STATUTORY ADMINISTRATIVE CELLS
                 ========================================== -->
            <section id="admin-cells">
                <div class="mvc-section-head">
                    <div class="mvc-section-badge">Collegiate Divisions</div>
                    <h2 class="mvc-section-title">Statutory Administrative Cells</h2>
                    <p class="mvc-section-sub">Operational secretariats ensuring academic rigor, discipline, and efficient college functioning</p>
                </div>

                <div class="mvc-cells-grid">
                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">📚</div>
                        <h4 class="mvc-cell-title">Academic &amp; Examination Wing</h4>
                        <div class="mvc-cell-role">In-Charge: Examination Controller</div>
                        <p class="mvc-cell-desc">Manages university curriculum compliance, semester examination conduction, enrollment tabulation, and degree certification dispatch.</p>
                        <div class="mvc-cell-meta"><span>Admin Block, Room 102</span><span>Ext: 104</span></div>
                    </div>

                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">⚖️</div>
                        <h4 class="mvc-cell-title">Proctorial Board &amp; Discipline</h4>
                        <div class="mvc-cell-role">In-Charge: College Proctor</div>
                        <p class="mvc-cell-desc">Enforces institutional code of conduct, campus peace, zero-tolerance anti-ragging security, and statutory student safety protocols.</p>
                        <div class="mvc-cell-meta"><span>Proctor's Chamber</span><span>Ext: 108</span></div>
                    </div>

                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">⭐</div>
                        <h4 class="mvc-cell-title">IQAC Quality Directorate</h4>
                        <div class="mvc-cell-role">In-Charge: IQAC Coordinator</div>
                        <p class="mvc-cell-desc">Continuous institutional quality enhancement, NAAC accreditation benchmarks, faculty development, and annual quality assurance reports (AQAR).</p>
                        <div class="mvc-cell-meta"><span>IQAC Directorate</span><span>Ext: 112</span></div>
                    </div>

                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">💰</div>
                        <h4 class="mvc-cell-title">Accounts &amp; Treasury Directorate</h4>
                        <div class="mvc-cell-role">In-Charge: Head Accountant</div>
                        <p class="mvc-cell-desc">Oversees collegiate fee reconciliations, state grants, UGC project budgets, scholarship disbursements, and statutory financial audits.</p>
                        <div class="mvc-cell-meta"><span>Accounts Wing</span><span>Ext: 103</span></div>
                    </div>

                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">📖</div>
                        <h4 class="mvc-cell-title">Central Library Administration</h4>
                        <div class="mvc-cell-role">In-Charge: Chief Librarian</div>
                        <p class="mvc-cell-desc">Administers 25,000+ print volumes, N-LIST digital subscriptions, e-learning access points, and research reference facilities.</p>
                        <div class="mvc-cell-meta"><span>Central Library</span><span>Ext: 115</span></div>
                    </div>

                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">🤝</div>
                        <h4 class="mvc-cell-title">Student Welfare &amp; Grievance Redressal</h4>
                        <div class="mvc-cell-role">In-Charge: Welfare Officer</div>
                        <p class="mvc-cell-desc">Dedicated to addressing scholar grievances, psychosocial mentoring, equal opportunity initiatives, and career guidance counseling.</p>
                        <div class="mvc-cell-meta"><span>Welfare Office</span><span>Ext: 106</span></div>
                    </div>

                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">🎖️</div>
                        <h4 class="mvc-cell-title">Youth Extension (NSS &amp; NCC)</h4>
                        <div class="mvc-cell-role">In-Charge: Programme Officers</div>
                        <p class="mvc-cell-desc">Directs nationwide youth extension camps, community social outreach, disaster response drills, and defense cadet training.</p>
                        <div class="mvc-cell-meta"><span>NSS/NCC Pavilion</span><span>Ext: 120</span></div>
                    </div>

                    <div class="mvc-cell-card">
                        <div class="mvc-cell-icon">🏛️</div>
                        <h4 class="mvc-cell-title">Campus Estate &amp; Infrastructure</h4>
                        <div class="mvc-cell-role">In-Charge: Estate Officer</div>
                        <p class="mvc-cell-desc">Supervises campus facilities, laboratory upgrades, smart classrooms, IT networks, solar power generation, and environmental greening.</p>
                        <div class="mvc-cell-meta"><span>Estate Office</span><span>Ext: 101</span></div>
                    </div>
                </div>
            </section>

            <!-- ==========================================
                 SECTION 03: OPERATIONAL GATEWAYS
                 ========================================== -->
            <section id="gateways">
                <div class="mvc-section-head">
                    <div class="mvc-section-badge">Fast Access</div>
                    <h2 class="mvc-section-title">Direct Operational Gateways</h2>
                    <p class="mvc-section-sub">Direct pathways to administrative desks, academic faculties, and collegiate documentation</p>
                </div>

                <div class="mvc-gateways-grid">
                    <a href="<?php echo esc_url($site_url . '/principal-desk/'); ?>" class="mvc-gateway-card">
                        <div>
                            <div class="mvc-gateway-title">Principal's Desk</div>
                            <p class="mvc-gateway-desc">Institutional vision, welcome address, leadership philosophy, and administrative circulars.</p>
                        </div>
                        <span class="mvc-gateway-arrow">Visit Desk &rarr;</span>
                    </a>

                    <a href="<?php echo esc_url($site_url . '/university-administration/'); ?>" class="mvc-gateway-card">
                        <div>
                            <div class="mvc-gateway-title">University Administration</div>
                            <p class="mvc-gateway-desc">Veer Kunwar Singh University (VKSU) officers, deans, PG departments, and collegiate accord.</p>
                        </div>
                        <span class="mvc-gateway-arrow">VKSU Governance &rarr;</span>
                    </a>

                    <a href="<?php echo esc_url($site_url . '/committees-cells/'); ?>" class="mvc-gateway-card">
                        <div>
                            <div class="mvc-gateway-title">Statutory Committees &amp; Cells</div>
                            <p class="mvc-gateway-desc">15 official committees including IQAC, Anti-Ragging, Grievance, NSS, NCC, and BOS.</p>
                        </div>
                        <span class="mvc-gateway-arrow">View All 15 Committees &rarr;</span>
                    </a>

                    <a href="<?php echo esc_url($site_url . '/department/'); ?>" class="mvc-gateway-card">
                        <div>
                            <div class="mvc-gateway-title">Academic Departments</div>
                            <p class="mvc-gateway-desc">Humanities, Science, Social Sciences, and Vocational degree faculties and faculty roster.</p>
                        </div>
                        <span class="mvc-gateway-arrow">Faculty Directory &rarr;</span>
                    </a>

                    <a href="<?php echo esc_url($site_url . '/staff-directory/'); ?>" class="mvc-gateway-card">
                        <div>
                            <div class="mvc-gateway-title">Non-Teaching Staff Cadre</div>
                            <p class="mvc-gateway-desc">Administrative officers, section heads, technical assistants, and institutional staff roster.</p>
                        </div>
                        <span class="mvc-gateway-arrow">Staff Directory &rarr;</span>
                    </a>

                    <a href="<?php echo esc_url($site_url . '/academic-calendar/'); ?>" class="mvc-gateway-card">
                        <div>
                            <div class="mvc-gateway-title">Academic Calendar &amp; Holidays</div>
                            <p class="mvc-gateway-desc">Official semester schedules, examination windows, and university gazetted holidays.</p>
                        </div>
                        <span class="mvc-gateway-arrow">Calendar &rarr;</span>
                    </a>
                </div>
            </section>

            <!-- ==========================================
                 SECTION 04: ADMINISTRATIVE DIRECTIVES
                 ========================================== -->
            <section id="directives">
                <div class="mvc-section-head">
                    <div class="mvc-section-badge">Official Circulars</div>
                    <h2 class="mvc-section-title">Latest Administrative Directives</h2>
                    <p class="mvc-section-sub">Official executive notifications, memos, and circulars issued by the Principal's Secretariat</p>
                </div>

                <div class="mvc-directives-grid">
                    <?php if ($notices_query->have_posts()): ?>
                        <?php while ($notices_query->have_posts()): $notices_query->the_post(); 
                            $nid = get_the_ID();
                            $pdf = function_exists('get_field') ? get_field('pdf_file', $nid) : null;
                            $pdf_url = '';
                            if (is_array($pdf) && !empty($pdf['url'])) $pdf_url = $pdf['url'];
                            elseif (is_numeric($pdf) && (int)$pdf > 0) $pdf_url = wp_get_attachment_url((int)$pdf);
                            elseif (is_string($pdf) && !empty($pdf) && $pdf !== '0') $pdf_url = $pdf;
                            if (empty($pdf_url)) $pdf_url = get_post_meta($nid, '_pdf_url', true);
                            if (empty($pdf_url)) $pdf_url = get_permalink($nid);
                        ?>
                            <div class="mvc-directive-card">
                                <div>
                                    <div class="mvc-directive-date"><?php echo get_the_date('d M Y'); ?> &bull; Memo #<?php echo esc_html($nid); ?></div>
                                    <h4 class="mvc-directive-title"><?php echo get_the_title(); ?></h4>
                                </div>
                                <div class="mvc-directive-actions">
                                    <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-btn-direct">Direct View ↗</a>
                                    <a href="<?php echo esc_url(get_permalink($nid)); ?>" class="mvc-btn-memo">Details &rarr;</a>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php else: ?>
                        <div style="grid-column: 1 / -1; padding: 36px 20px; text-align: center; background: #FFFFFF; border-radius: 12px; border: 1px solid var(--mvc-border);">
                            <p style="margin: 0; color: var(--mvc-slate-light); font-size: 15px;">No active directives published at this time. All official circulars are archived in the <a href="<?php echo esc_url(home_url('/notices/')); ?>" style="color: var(--mvc-navy); font-weight: 700;">Notices &amp; Circulars Gazette &rarr;</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- ==========================================
                 SECTION 05: STATUTORY INSTITUTIONAL FACTSHEET
                 ========================================== -->
            <section id="factsheet">
                <div class="mvc-section-head">
                    <div class="mvc-section-badge">Institutional Identity</div>
                    <h2 class="mvc-section-title">Statutory Affiliation &amp; Governance Factsheet</h2>
                    <p class="mvc-section-sub">Standardized regulatory identity parameters and legislative accord records</p>
                </div>

                <div class="mvc-factsheet-table-wrap">
                    <table class="mvc-factsheet-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Institutional Parameter</th>
                                <th style="width: 45%;">Statutory Status &amp; Authority Record</th>
                                <th style="width: 30%;">Regulatory Reference / Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Parameter"><strong>Legal Institutional Title</strong></td>
                                <td data-label="Record">Maharshi Vishwamitra Mahavidyalaya (M.V. College), Buxar</td>
                                <td data-label="Reference">UGC AISHE Code: <code>C-27158</code></td>
                            </tr>
                            <tr>
                                <td data-label="Parameter"><strong>Affiliating University</strong></td>
                                <td data-label="Record">Veer Kunwar Singh University (VKSU), Ara (Bihar)</td>
                                <td data-label="Reference">Permanent Constituent College (Est. 1958)</td>
                            </tr>
                            <tr>
                                <td data-label="Parameter"><strong>UGC Recognition Status</strong></td>
                                <td data-label="Record">Recognized under Section 2(f) &amp; 12(B) of the UGC Act, 1956</td>
                                <td data-label="Reference">Eligible for Central Assistance / UGC Grants</td>
                            </tr>
                            <tr>
                                <td data-label="Parameter"><strong>State Department of Higher Education</strong></td>
                                <td data-label="Record">Education Department, Government of Bihar, Patna</td>
                                <td data-label="Reference">Constituent College Cadre #301</td>
                            </tr>
                            <tr>
                                <td data-label="Parameter"><strong>Institutional Secretariat Campus</strong></td>
                                <td data-label="Record">Charitravan, Ganga Shoreline, Buxar – 802101, Bihar</td>
                                <td data-label="Reference">PIN: 802101 &bull; District: Buxar</td>
                            </tr>
                            <tr>
                                <td data-label="Parameter"><strong>Academic Programs Accorded</strong></td>
                                <td data-label="Record">Undergraduate B.A. (Hons), B.Sc. (Hons), B.Com, BCA, BBM &amp; Add-on Certifications</td>
                                <td data-label="Reference">CBCS 4-Year UG Degree Framework</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>
    <?php
    return ob_get_clean();
}
