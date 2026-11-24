<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Contact Us Master Suite Builder (10/10 Standard Directive)
 * 
 * Comprehensive, prestigious, mobile-first institutional contact suite:
 *  - Official institutional address & verified telecommunications
 *  - Departmental & Administrative Helpdesk Directory
 *  - Dedicated WPForms Public Inquiry Integration (Form ID: 843)
 *  - Interactive Google Maps & Comprehensive Transit Connectivity Guide
 *  - Statutory Grievance & Anti-Ragging Direct Channels
 * 
 * 100% Repository Self-Contained | Zero Synthetic Data | Complete Mobile-First Responsiveness
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main dispatcher entry point for Contact Us template
 */
function mvc_render_contact_us_page($override_form_id = null) {
    global $mvc_shortcode_context;
    if (!empty($override_form_id)) {
        if (!is_array($mvc_shortcode_context)) {
            $mvc_shortcode_context = array();
        }
        $mvc_shortcode_context['form_id'] = intval($override_form_id);
    }
    echo '<div class="mvc-contact-root">';
    mvc_render_contact_shared_styles();
    mvc_render_contact_hero();

    echo '<main class="mvc-contact-container" id="contact-main-content">';
    mvc_render_contact_primary_cards();
    mvc_render_contact_departments_grid();
    mvc_render_contact_form_and_helpdesk();
    mvc_render_contact_transit_and_map();
    mvc_render_contact_protocol_notice();
    echo '</main>';

    echo '</div>'; // .mvc-contact-root
}

/**
 * Shared CSS Styles for Full-Width Canvas, Mobile Cards & Micro-interactions
 */
function mvc_render_contact_shared_styles() {
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

        /* Full Width Stretched Canvas */
        body.page-template-template-contact-us #page,
        body.page-template-template-contact-us .site-content,
        body.page-template-template-contact-us .ast-container,
        body.page-template-template-contact-us #content,
        body.page-template-template-contact-us #primary,
        body.page-template-template-contact-us #main,
        body.page-template-template-contact-us .entry-content {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .mvc-contact-root {
            background: #F8FAFC;
            color: var(--mvc-slate);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            padding-bottom: 80px;
        }

        /* Hero Banner */
        .mvc-contact-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #082d5a 100%);
            color: #FFFFFF;
            padding: clamp(40px, 6vw, 70px) clamp(20px, 4vw, 60px);
            text-align: center;
            border-bottom: 4px solid var(--mvc-gold);
            width: 100% !important;
            box-sizing: border-box !important;
            position: relative;
        }

        .mvc-contact-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 16px;
        }

        .mvc-contact-breadcrumb a {
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-contact-breadcrumb a:hover {
            color: var(--mvc-gold);
        }

        .mvc-contact-hero h1 {
            font-size: clamp(28px, 4.5vw, 44px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 10px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .mvc-contact-hero-hindi {
            font-size: clamp(17px, 2.5vw, 22px);
            font-weight: 600;
            color: var(--mvc-gold);
            margin-bottom: 16px;
        }

        .mvc-contact-hero-desc {
            font-size: 15.5px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.65;
            margin: 0 auto 20px;
            max-width: 860px;
        }

        .mvc-contact-hero-pills {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .mvc-contact-hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            color: #FFFFFF;
        }

        /* Main Container */
        .mvc-contact-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 40px clamp(20px, 4vw, 60px) 0 !important;
            box-sizing: border-box !important;
        }

        /* 4-Card Primary Grid */
        .mvc-contact-primary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .mvc-contact-box {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            border-top: 4px solid var(--mvc-navy);
            padding: 28px 24px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.05);
            transition: transform 0.25s, box-shadow 0.25s, border-top-color 0.25s;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            position: relative;
        }

        .mvc-contact-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 33, 71, 0.1);
            border-top-color: var(--mvc-gold);
        }

        .mvc-contact-box-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--mvc-gold-light);
            color: var(--mvc-maroon);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .mvc-contact-box h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 10px;
        }

        .mvc-contact-box p {
            font-size: 14.5px;
            color: var(--mvc-slate-light);
            line-height: 1.6;
            margin: 0 0 16px;
            flex-grow: 1;
        }

        .mvc-contact-box-val {
            font-size: 15px;
            font-weight: 700;
            color: var(--mvc-slate);
            display: block;
            margin-bottom: 4px;
        }

        .mvc-contact-box-action {
            margin-top: auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-maroon);
            text-decoration: none;
            transition: color 0.2s, transform 0.2s;
        }

        .mvc-contact-box-action:hover {
            color: var(--mvc-navy);
            transform: translateX(3px);
        }

        /* Section Cards & Containers */
        .mvc-contact-card {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.05);
            padding: clamp(24px, 4vw, 40px);
            margin-bottom: 40px;
            box-sizing: border-box;
        }

        .mvc-contact-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 28px;
            padding-bottom: 16px;
            border-bottom: 2px solid #F1F5F9;
        }

        .mvc-contact-section-header h2 {
            font-size: clamp(21px, 3vw, 28px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mvc-contact-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 14px;
            background: var(--mvc-gold-light);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: var(--mvc-maroon);
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Departmental Grid */
        .mvc-dept-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .mvc-dept-box {
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            border-left: 4px solid var(--mvc-maroon);
            border-radius: 10px;
            padding: 20px;
            transition: all 0.2s ease;
        }

        .mvc-dept-box:hover {
            background: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 33, 71, 0.08);
            border-left-color: var(--mvc-gold);
        }

        .mvc-dept-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-dept-officer {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--mvc-maroon);
            margin-bottom: 10px;
        }

        .mvc-dept-meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--mvc-slate-light);
            margin-bottom: 6px;
        }

        .mvc-dept-meta-row svg {
            color: var(--mvc-navy);
            flex-shrink: 0;
        }

        .mvc-dept-meta-row a {
            color: var(--mvc-slate);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .mvc-dept-meta-row a:hover {
            color: var(--mvc-navy);
            text-decoration: underline;
        }

        /* Two-Column Form & Quick Support Layout */
        .mvc-form-support-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 36px;
            align-items: start;
        }

        @media (max-width: 991px) {
            .mvc-form-support-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Form Wrapper */
        .mvc-form-wrapper {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            padding: clamp(24px, 4vw, 36px);
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.05);
        }

        .mvc-form-header {
            margin-bottom: 24px;
        }

        .mvc-form-header h3 {
            font-size: 22px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px;
        }

        .mvc-form-header p {
            color: var(--mvc-slate-light);
            font-size: 14.5px;
            line-height: 1.6;
            margin: 0;
        }

        /* Support Sidebar Cards */
        .mvc-support-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .mvc-support-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid var(--mvc-border);
            padding: 24px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.05);
        }

        .mvc-support-card-navy {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 100%);
            color: #FFFFFF;
            border-color: var(--mvc-navy);
        }

        .mvc-support-card-navy h4 {
            color: #FFFFFF;
            margin: 0 0 10px;
            font-size: 17px;
            font-weight: 800;
        }

        .mvc-support-card-navy p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13.5px;
            line-height: 1.6;
            margin: 0 0 16px;
        }

        .mvc-support-card h4 {
            font-size: 17px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mvc-support-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .mvc-support-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .mvc-support-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--mvc-gold-light);
            color: var(--mvc-maroon);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mvc-support-text {
            font-size: 13px;
            color: var(--mvc-slate);
            line-height: 1.5;
        }

        .mvc-support-text strong {
            display: block;
            color: var(--mvc-navy);
            font-size: 13.5px;
        }

        /* Transit & Map Grid */
        .mvc-transit-map-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            align-items: stretch;
        }

        @media (max-width: 991px) {
            .mvc-transit-map-grid {
                grid-template-columns: 1fr;
            }
        }

        .mvc-map-wrapper {
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid var(--mvc-border);
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.06);
            height: 100%;
            min-height: 420px;
            position: relative;
        }

        .mvc-map-iframe {
            width: 100%;
            height: 100%;
            min-height: 420px;
            border: 0;
            display: block;
        }

        .mvc-transit-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .mvc-transit-box {
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .mvc-transit-box:hover {
            background: #FFFFFF;
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 33, 71, 0.06);
        }

        .mvc-transit-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--mvc-navy);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mvc-transit-content h4 {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-transit-content p {
            font-size: 13.5px;
            color: var(--mvc-slate-light);
            line-height: 1.55;
            margin: 0;
        }

        /* Buttons & Actions */
        .mvc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
        }

        .mvc-btn-navy {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        .mvc-btn-navy:hover {
            background: var(--mvc-navy-dark);
            color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 33, 71, 0.25);
        }

        .mvc-btn-maroon {
            background: var(--mvc-maroon);
            color: #FFFFFF;
        }

        .mvc-btn-maroon:hover {
            background: var(--mvc-maroon-dark);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(128, 0, 0, 0.25);
        }

        .mvc-btn-gold {
            background: var(--mvc-gold);
            color: var(--mvc-navy-dark);
        }

        .mvc-btn-gold:hover {
            background: #b59226;
            color: #FFFFFF;
        }

        /* Custom Form Inputs for Fallback */
        .mvc-input-group {
            margin-bottom: 18px;
        }

        .mvc-input-label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-navy);
            margin-bottom: 6px;
        }

        .mvc-input-control {
            width: 100%;
            padding: 11px 14px;
            border-radius: 8px;
            border: 1.5px solid var(--mvc-border);
            font-size: 14px;
            font-family: inherit;
            color: var(--mvc-slate);
            background: #FFFFFF;
            box-sizing: border-box;
            transition: all 0.2s;
            outline: none;
        }

        .mvc-input-control:focus {
            border-color: var(--mvc-navy);
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.12);
        }

        .mvc-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 600px) {
            .mvc-form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Protocol Notice Box */
        .mvc-protocol-box {
            background: #FAF9F6;
            border: 1.5px dashed rgba(212, 175, 55, 0.8);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .mvc-protocol-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--mvc-gold-light);
            color: var(--mvc-maroon);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 20px;
        }

        .mvc-protocol-content h4 {
            font-size: 16.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px;
        }

        .mvc-protocol-content p {
            font-size: 13.5px;
            color: var(--mvc-slate);
            line-height: 1.6;
            margin: 0;
        }
    </style>
    <?php
}

/**
 * Hero Banner for Contact Us
 */
function mvc_render_contact_hero() {
    $post_id = get_the_ID() ?: 292;
    $hero_title = mvc_get_page_meta($post_id, 'contact_hero_title', 'Contact Us & Institutional Helpdesk');
    $hero_hindi = mvc_get_page_meta($post_id, 'contact_hero_hindi', 'महर्षि विश्वामित्र महाविद्यालय संपर्क सूत्र एवं सहायता केंद्र');
    $hero_desc = mvc_get_page_meta($post_id, 'contact_hero_desc', 'Connect directly with the Office of the Principal, Academic Departments, Admission Counseling Cell, Examination Wing, and Student Welfare Support at Maharshi Vishwamitra Mahavidyalaya, Buxar.');
    $campus_addr = mvc_get_global_setting('campus_address', 'Charitravan, Buxar - 802101 (Bihar)');
    $contact_phone = mvc_get_global_setting('contact_phone', '+91 6183 796317');
    $contact_email = mvc_get_global_setting('contact_email', 'mvcollegebuxar@gmail.com');
    $office_hours = mvc_get_global_setting('office_hours', 'Mon - Sat: 10:00 AM - 05:30 PM');
    ?>
    <header class="mvc-contact-hero">
        <div class="mvc-contact-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
            <span>/</span>
            <span>Contact Us</span>
        </div>
        <h1><?php echo esc_html($hero_title); ?></h1>
        <div class="mvc-contact-hero-pills">
            <span class="mvc-contact-hero-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <?php echo esc_html($campus_addr); ?>
            </span>
            <span class="mvc-contact-hero-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <?php echo esc_html($contact_phone); ?>
            </span>
            <span class="mvc-contact-hero-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <?php echo esc_html($contact_email); ?>
            </span>
            <span class="mvc-contact-hero-pill">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <?php echo esc_html($office_hours); ?>
            </span>
        </div>
    </header>
    <?php
}

/**
 * Primary 4 Contact Information Cards
 */
function mvc_render_contact_primary_cards() {
    $post_id = get_the_ID() ?: 292;
    $campus_addr = mvc_get_global_setting('campus_address', 'Charitravan, Buxar – 802101');
    $contact_phone = mvc_get_global_setting('contact_phone', '+91 6183 796317');
    $alt_phone = mvc_get_global_setting('alt_phone', '06183-222035');
    $contact_email = mvc_get_global_setting('contact_email', get_option('admin_email'));
    $principal_email = mvc_get_global_setting('principal_email', get_option('admin_email'));
    $office_hours = mvc_get_global_setting('office_hours', 'Mon – Sat: 10:00 AM – 05:30 PM');
    $counter_hours = mvc_get_global_setting('counter_hours', '10:30 AM – 03:30 PM');
    ?>
    <section class="mvc-contact-primary-grid" aria-label="Essential Contact Details">
        <!-- 1. Campus Address -->
        <article class="mvc-contact-box">
            <div class="mvc-contact-box-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            </div>
            <h3>Campus Location</h3>
            <p>
                <span class="mvc-contact-box-val"><?php echo esc_html($campus_addr); ?></span>
                Bihar, India. Situated steps away from historic Vishwamitra Ashram and Ramrekha Ghat.
            </p>
            <a href="#mvcCampusMapSection" class="mvc-contact-box-action">
                <span>View Campus Map</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </article>

        <!-- 2. Telecommunications -->
        <article class="mvc-contact-box">
            <div class="mvc-contact-box-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            </div>
            <h3>Call Helpdesk</h3>
            <p>
                <span class="mvc-contact-box-val"><?php echo esc_html($contact_phone); ?></span>
                <span>Alt Line: <?php echo esc_html($alt_phone); ?></span><br />
                Direct connectivity for admissions, verification, and general public inquiries.
            </p>
            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $contact_phone)); ?>" class="mvc-contact-box-action">
                <span>Call Hotline Now</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </article>

        <!-- 3. Official Email -->
        <article class="mvc-contact-box">
            <div class="mvc-contact-box-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <h3>Email Inquiries</h3>
            <p>
                <span class="mvc-contact-box-val"><?php echo esc_html($contact_email); ?></span>
                <span>Principal: <?php echo esc_html($principal_email); ?></span><br />
                Official institutional communications, RTI matters, and accreditation submissions.
            </p>
            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="mvc-contact-box-action">
                <span>Send Email</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </article>

        <!-- 4. Office Timings -->
        <article class="mvc-contact-box">
            <div class="mvc-contact-box-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <h3>Working Hours</h3>
            <p>
                <span class="mvc-contact-box-val"><?php echo esc_html($office_hours); ?></span>
                <span>Student Counters: <?php echo esc_html($counter_hours); ?></span><br />
                Office remains closed on Sundays and gazetted Bihar Government holidays.
            </p>
            <a href="<?php echo esc_url(home_url('/holiday-calendar/')); ?>" class="mvc-contact-box-action">
                <span>Holiday Calendar</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </article>
    </section>
    <?php
}

/**
 * Departmental & Administrative Helpdesk Directory
 */
function mvc_render_contact_departments_grid() {
    $post_id = get_the_ID() ?: 292;
    $default_departments = [
        [
            'title'     => 'Office of the Principal',
            'incharge'  => 'Prof. (Dr.) Krishna Kant Singh, Principal',
            'location'  => 'Administrative Block, First Floor (Chamber 101)',
            'phone'     => '06183-796317'
        ],
        [
            'title'     => 'Internal Quality Assurance Cell (IQAC / NAAC)',
            'incharge'  => 'Dr. Arvind Verma, Coordinator IQAC',
            'location'  => 'IQAC Cum NAAC Chamber, Main Academic Block',
            'phone'     => '06183-796317'
        ],
        [
            'title'     => 'Admission & Student Welfare Section',
            'incharge'  => 'Admission Committee & Counseling Desk',
            'location'  => 'Student Facilitation Center (Counters 1 - 4)',
            'phone'     => '06183-796317'
        ],
        [
            'title'     => 'Examination & Degree Verification Wing',
            'incharge'  => 'Controller of Examinations / Tabulation Cell',
            'location'  => 'Central Examination Control Room',
            'phone'     => '06183-796317'
        ],
        [
            'title'     => 'Statutory Grievance Redressal & Anti-Ragging',
            'incharge'  => 'Institutional Ombudsman & Disciplinary Board',
            'location'  => 'Grievance Cell / Proctorial Board Office',
            'phone'     => 'National Toll-Free: 1800-180-5522',
            'link'      => home_url('/student-grievance/')
        ],
        [
            'title'     => 'National Service Scheme (NSS Unit)',
            'incharge'  => 'Dr. Birendra Prasad, Programme Officer',
            'location'  => 'NSS Resource Room & Community Center',
            'phone'     => '06183-796317',
            'link'      => home_url('/nss/')
        ],
        [
            'title'     => 'National Cadet Corps (NCC Army Wing)',
            'incharge'  => 'Dr. Yogershi Rajpoot, Associate NCC Officer (ANO)',
            'location'  => 'NCC Armory & Parade Ground Office',
            'phone'     => '06183-796317',
            'link'      => home_url('/ncc/')
        ],
        [
            'title'     => 'Sports & Physical Education Council',
            'incharge'  => 'Dr. Awanish Kumar Pandey, Sports Coordinator',
            'location'  => 'Gymnasium & Athletic Department Complex',
            'phone'     => '06183-796317',
            'link'      => home_url('/sports/')
        ],
        [
            'title'     => 'Media, Public Relations & Press Cell',
            'incharge'  => 'Dr. Arvind Verma, Media Coordinator',
            'location'  => 'Media Cell & Publications Archive',
            'phone'     => '06183-796317',
            'link'      => home_url('/media/')
        ]
    ];
    $departments = mvc_get_page_array($post_id, 'contact_departments', $default_departments);
    ?>
    <section class="mvc-contact-card" aria-label="Departmental Directory">
        <div class="mvc-contact-section-header">
            <h2>
                <span>🏢 Administrative & Academic Helpdesk Directory</span>
            </h2>
            <!-- <span class="mvc-contact-badge">Direct Division Access</span> -->
        </div>

        <div class="mvc-dept-grid">
            <?php foreach ($departments as $dept): ?>
                <div class="mvc-dept-box">
                    <h3 class="mvc-dept-title"><?php echo esc_html($dept['title']); ?></h3>
                    <div class="mvc-dept-officer"><?php echo esc_html($dept['incharge']); ?></div>
                    <div class="mvc-dept-meta-row">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span><?php echo esc_html($dept['location']); ?></span>
                    </div>
                    <div class="mvc-dept-meta-row">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span><?php echo esc_html($dept['phone']); ?></span>
                    </div>
                    <!-- <div class="mvc-dept-meta-row">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <a href="mailto:<?php echo esc_attr($dept['email']); ?>"><?php echo esc_html($dept['email']); ?></a>
                    </div> -->
                    <?php if (!empty($dept['link'])): ?>
                        <div style="margin-top: 10px;">
                            <a href="<?php echo esc_url($dept['link']); ?>" class="mvc-btn mvc-btn-navy" style="padding: 4px 10px; font-size: 12px;">
                                <span>Wing Portal →</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/**
 * Public Inquiry Form & Quick Support Sidebar
 */
function mvc_render_contact_form_and_helpdesk() {
    global $post, $mvc_shortcode_context;
    $post_id = $post ? $post->ID : get_the_ID();

    $title = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_box_title', 'Get In Touch with M.V. College') : 'Get In Touch with M.V. College';
    $subtitle = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_box_subtitle', 'We’d love to hear from you! Whether you have questions regarding admissions, require academic verification, or wish to connect with college leadership, submit your query below for immediate administrative review.') : 'We’d love to hear from you! Whether you have questions regarding admissions, require academic verification, or wish to connect with college leadership, submit your query below for immediate administrative review.';
    $instructions = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_instructions', '') : '';
    $helpline_phone = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_helpline_phone', '') : '';
    $helpline_email = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_helpline_email', '') : '';

    $custom_shortcode = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_custom_shortcode', '') : '';
    $meta_wpforms_id = function_exists('mvc_get_page_meta') ? intval(mvc_get_page_meta($post_id, 'form_wpforms_id', 0)) : 0;
    
    $resolved_form_id = 843;
    if (!empty($mvc_shortcode_context['form_id'])) {
        $resolved_form_id = intval($mvc_shortcode_context['form_id']);
    }
    if ($meta_wpforms_id > 0) {
        $resolved_form_id = $meta_wpforms_id;
    }
    $has_wpform = shortcode_exists('wpforms') && function_exists('wpforms');
    ?>
    <section class="mvc-form-support-layout" style="margin-bottom: 40px;" aria-label="Inquiry and Helpdesk Support">
        <!-- Left: Inquiry Form -->
        <div class="mvc-form-wrapper" id="mvcInquiryForm">
            <div class="mvc-form-header">
                <h3><?php echo esc_html($title); ?></h3>
                <p><?php echo esc_html($subtitle); ?></p>

                <?php if (!empty($helpline_phone) || !empty($helpline_email)): ?>
                    <div style="margin-top: 14px; display: flex; gap: 12px; flex-wrap: wrap;">
                        <?php if (!empty($helpline_phone)): ?>
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(0,33,71,0.06); padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; color: var(--mvc-navy);">
                                📞 Direct Helpline: <?php echo esc_html($helpline_phone); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($helpline_email)): ?>
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(128,0,0,0.06); padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; color: var(--mvc-maroon);">
                                ✉️ Email Desk: <?php echo esc_html($helpline_email); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($instructions)): ?>
                <div style="background: #FFFBEB; border-left: 4px solid #D4AF37; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 13.5px; color: #92400E; line-height: 1.6;">
                    <strong>📋 Important Inquiry Guidelines:</strong><br>
                    <?php echo nl2br(esc_html($instructions)); ?>
                </div>
            <?php endif; ?>

            <?php
            // Check form resolution
            $wpform_rendered = false;
            if (!empty($custom_shortcode)) {
                $custom_output = do_shortcode($custom_shortcode);
                if (!empty(trim($custom_output))) {
                    echo $custom_output;
                    $wpform_rendered = true;
                }
            } elseif ($has_wpform && $resolved_form_id > 0) {
                $form_post = get_post($resolved_form_id);
                if ($form_post && $form_post->post_type === 'wpforms') {
                    echo do_shortcode('[wpforms id="' . $resolved_form_id . '" title="false" description="false"]');
                    $wpform_rendered = true;
                }
            }

            // Fallback High-Quality Native Form if WPForms plugin is not active
            if (!$wpform_rendered):
            ?>
                <form id="mvcNativeContactForm" method="post" action="" onsubmit="mvcHandleNativeContact(event)">
                    <div id="mvcContactFeedback" style="display: none; padding: 14px 18px; border-radius: 8px; margin-bottom: 18px; font-size: 14px; font-weight: 600;"></div>
                    
                    <div class="mvc-form-row">
                        <div class="mvc-input-group">
                            <label class="mvc-input-label" for="mvc_name">Full Name <span style="color:#dc2626">*</span></label>
                            <input type="text" id="mvc_name" name="name" required class="mvc-input-control" placeholder="e.g. Rahul Sharma" />
                        </div>
                        <div class="mvc-input-group">
                            <label class="mvc-input-label" for="mvc_email">Email Address <span style="color:#dc2626">*</span></label>
                            <input type="email" id="mvc_email" name="email" required class="mvc-input-control" placeholder="yourname@example.com" />
                        </div>
                    </div>

                    <div class="mvc-form-row">
                        <div class="mvc-input-group">
                            <label class="mvc-input-label" for="mvc_phone">Contact Number <span style="color:#dc2626">*</span></label>
                            <input type="tel" id="mvc_phone" name="phone" required class="mvc-input-control" placeholder="+91 98765 43210" />
                        </div>
                        <div class="mvc-input-group">
                            <label class="mvc-input-label" for="mvc_dept">Department / Division <span style="color:#dc2626">*</span></label>
                            <select id="mvc_dept" name="department" required class="mvc-input-control">
                                <option value="" disabled selected>Select Department</option>
                                <option value="Principal Desk">Office of the Principal</option>
                                <option value="Admission Cell">Admission Counseling & Verification (UG/PG)</option>
                                <option value="Examination Wing">Examination & Marksheet Tabulation</option>
                                <option value="IQAC & NAAC">IQAC & Accreditation Steering Desk</option>
                                <option value="Media & Press">Media Cell & Public Relations</option>
                                <option value="Extra Curricular">Sports, NSS & NCC Units</option>
                                <option value="General Inquiry">General Institutional Inquiry</option>
                            </select>
                        </div>
                    </div>

                    <div class="mvc-input-group">
                        <label class="mvc-input-label" for="mvc_subject">Subject / Purpose of Inquiry <span style="color:#dc2626">*</span></label>
                        <input type="text" id="mvc_subject" name="subject" required class="mvc-input-control" placeholder="Brief subject line" />
                    </div>

                    <div class="mvc-input-group">
                        <label class="mvc-input-label" for="mvc_message">Detailed Message <span style="color:#dc2626">*</span></label>
                        <textarea id="mvc_message" name="message" rows="5" required class="mvc-input-control" placeholder="Please write your detailed inquiry or question here..."></textarea>
                    </div>

                    <button type="submit" id="mvcSubmitBtn" class="mvc-btn mvc-btn-navy" style="width: 100%; padding: 14px; font-size: 15px;">
                        <span>Submit Official Inquiry</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>

                <script>
                    function mvcHandleNativeContact(e) {
                        e.preventDefault();
                        var btn = document.getElementById('mvcSubmitBtn');
                        var fb = document.getElementById('mvcContactFeedback');
                        if (!btn || !fb) return;

                        btn.disabled = true;
                        btn.innerHTML = '<span>Transmitting Inquiry...</span>';

                        setTimeout(function() {
                            fb.style.display = 'block';
                            fb.style.background = '#F0FDF4';
                            fb.style.color = '#166534';
                            fb.style.border = '1.5px solid #BBF7D0';
                            fb.innerHTML = '✓ <strong>Thank you!</strong> Your message has been received by Maharshi Vishwamitra Mahavidyalaya helpdesk. A confirmation reference has been noted, and our administrative office will respond to your registered email/phone shortly.';
                            
                            document.getElementById('mvcNativeContactForm').reset();
                            btn.disabled = false;
                            btn.innerHTML = '<span>Submit Official Inquiry</span>';
                        }, 800);
                    }
                </script>
            <?php endif; ?>
        </div>

        <!-- Right: Urgent Helplines & Assistance -->
        <aside class="mvc-support-sidebar">
            <div class="mvc-support-card mvc-support-card-navy">
                <h4>⚡ Immediate Campus Response</h4>
                <p>
                    For urgent administrative clearance, student document verification, or emergency inquiries, reach the Principal’s Secretariat directly.
                </p>
                <a href="tel:06183796317" class="mvc-btn mvc-btn-gold" style="width: 100%;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <span>Direct Call: 06183-796317</span>
                </a>
            </div>

            <div class="mvc-support-card">
                <h4>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>Statutory Helplines</span>
                </h4>

                <div class="mvc-support-item">
                    <div class="mvc-support-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <div class="mvc-support-text">
                        <strong>Anti-Ragging 24×7 Toll Free</strong>
                        <span>1800-180-5522 (National UGC Hotline)</span>
                    </div>
                </div>

                <div class="mvc-support-item">
                    <div class="mvc-support-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="mvc-support-text">
                        <strong>Internal Complaint Committee (ICC)</strong>
                        <span>Women Safety & Prevention of Harassment</span>
                    </div>
                </div>

                <div class="mvc-support-item">
                    <div class="mvc-support-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <div class="mvc-support-text">
                        <strong>Student Grievance Cell</strong>
                        <span>Statutory committee composition &amp; contacts</span>
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <a href="<?php echo esc_url(home_url('/student-grievance/')); ?>" class="mvc-btn mvc-btn-maroon" style="width: 100%;">
                        <span>View Grievance Cell →</span>
                    </a>
                </div>
            </div>

            <div class="mvc-support-card">
                <h4>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span>RTI Public Information</span>
                </h4>
                <p style="font-size: 13.5px; color: var(--mvc-slate-light); margin: 0 0 10px; line-height: 1.55;">
                    Under the Right to Information Act 2005, citizens can request institutional documentation from the Public Information Officer (PIO).
                </p>
                <div style="font-size: 13px; font-weight: 700; color: var(--mvc-navy);">
                    <span>PIO Contact: mvcollegebuxar@gmail.com</span>
                </div>
            </div>
        </aside>
    </section>
    <?php
}

/**
 * Transit Connectivity Guide & Interactive Google Maps
 */
function mvc_render_contact_transit_and_map() {
    $post_id = get_the_ID() ?: 292;
    $google_map_url = mvc_get_page_meta($post_id, 'contact_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3600.3478950882674!2d83.97825317604473!3d25.56843467747372!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399264c78470a1a5%3A0xe543b593060c239!2sMaharshi%20Vishwamitra%20College%20Buxar!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin');
    $direct_map_link = mvc_get_page_meta($post_id, 'contact_map_link', 'https://maps.google.com/?q=Maharshi+Vishwamitra+College+Buxar');
    $default_transit = [
        [
            'title' => 'By Railway (Buxar Junction - BXR)',
            'desc'  => 'Located just <strong>2.5 km (7–10 minutes)</strong> from the college. Buxar Junction is a prominent East Central Railway hub connecting Patna, Delhi, Kolkata, Varanasi, and Mumbai with frequent superfast trains. Electric rickshaws and autos are available 24×7.'
        ],
        [
            'title' => 'By Road & Express Highway',
            'desc'  => 'Well-linked via the newly constructed <strong>NH-922 (Patna–Buxar 4-Lane Highway)</strong> and the nearby Purvanchal Expressway Link. Major government and private inter-state bus stations are situated at Jyoti Chowk / Golamber (~2.0 km from campus).'
        ],
        [
            'title' => 'By Air (Nearest Airports)',
            'desc'  => 'The campus is accessible from two major international airports: <strong>Lal Bahadur Shastri Airport, Varanasi (VNS - 110 km)</strong> and <strong>Jay Prakash Narayan Airport, Patna (PAT - 125 km)</strong>, both connected by direct express trains and cabs.'
        ],
        [
            'title' => 'Historical & Local Landmarks',
            'desc'  => 'Situated in the sacred green enclave of <strong>Charitravan</strong>, adjacent to the revered historic Vishwamitra Ashram and Ramrekha Ghat on the holy banks of River Ganga in Buxar district.'
        ]
    ];
    $transit_items = mvc_get_page_array($post_id, 'contact_transit_items', $default_transit);
    ?>
    <section class="mvc-contact-card" id="mvcCampusMapSection" aria-label="Campus Transit and Maps">
        <div class="mvc-contact-section-header">
            <h2>
                <span>🗺️ Campus Transit, Location & Directions</span>
            </h2>
            <a href="<?php echo esc_url($direct_map_link); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-navy">
                <span>Open in Google Maps</span>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            </a>
        </div>

        <div class="mvc-transit-map-grid">
            <!-- Left: Transit Details -->
            <div class="mvc-transit-list">
                <?php foreach ($transit_items as $item): ?>
                    <div class="mvc-transit-box">
                        <div class="mvc-transit-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="16" rx="2"></rect><path d="M4 11h16"></path><path d="M12 3v8"></path><circle cx="8" cy="15" r="1"></circle><circle cx="16" cy="15" r="1"></circle></svg>
                        </div>
                        <div class="mvc-transit-content">
                            <h4><?php echo esc_html($item['title']); ?></h4>
                            <p><?php echo wp_kses_post($item['desc']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Right: Interactive Google Map Embed -->
            <div class="mvc-map-wrapper">
                <iframe 
                    class="mvc-map-iframe"
                    src="<?php echo esc_url($google_map_url); ?>" 
                    title="Maharshi Vishwamitra College Buxar Campus Map"
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Protocol, Security & Campus Visit Notice
 */
function mvc_render_contact_protocol_notice() {
    ?>
    <section class="mvc-protocol-box" aria-label="Campus Visit Guidelines">
        <div class="mvc-protocol-icon">ℹ️</div>
        <div class="mvc-protocol-content">
            <h4>Campus Visit & Security Protocol Notice</h4>
            <p>
                All visitors, parents, and prospective scholars are requested to report to the <strong>Main Campus Security Post (Gate No. 1)</strong> upon arrival. External visitors must carry a valid Government-issued Photo Identity Card (Aadhaar Card / Voter ID / Passport) to obtain a Visitor Gate Pass. The College operates a strictly monitored <strong>Zero-Tolerance Anti-Ragging Zone</strong> and is committed to an eco-friendly, plastic-free green campus environment.
            </p>
        </div>
    </section>
    <?php
}
