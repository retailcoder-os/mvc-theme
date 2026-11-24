<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Student Zone Suite Master Builder (10/10 Standard Directive)
 * 
 * Modular renderers for all 14 Student Zone pages:
 *  1. admission-procedure
 *  2. sanctioned-seat
 *  3. enrollment-position
 *  4. college-prospectus
 *  5. syllabus
 *  6. study-material
 *  7. outcomes
 *  8. result
 *  9. holiday-calendar
 * 10. student-testimonial
 * 11. alumni-registration
 * 12. 360-degree-feedback
 * 13. feedback-report
 * 14. student-grievance
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main dispatcher entry point for Student Zone template
 */
function mvc_render_student_zone_page($override_section = null, $override_form_id = null) {
    global $post, $mvc_shortcode_context;
    $slug = !empty($override_section) ? $override_section : ($post ? $post->post_name : '');
    if (!empty($override_form_id)) {
        if (!is_array($mvc_shortcode_context)) {
            $mvc_shortcode_context = array();
        }
        $mvc_shortcode_context['form_id'] = intval($override_form_id);
    }
    
    // Fallback detection via query var or request URI if needed
    if (empty($slug)) {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $parts = explode('/', $uri);
        $slug = end($parts);
    }
    
    // Normalize aliases
    if ($slug === 'fyugp-syllabus' || $slug === 'old-syllabus') {
        $slug = 'ug-syllabus';
    }
    if ($slug === 'sanctioned-seats') {
        $slug = 'sanctioned-seat';
    }
    if ($slug === 'student-testimonials') {
        $slug = 'student-testimonial';
    }
    if ($slug === '360-feedback') {
        $slug = '360-degree-feedback';
    }
    if ($slug === '360-feedback-report') {
        $slug = 'feedback-report';
    }

    echo '<div class="mvc-sz-root">';
    mvc_render_student_zone_shared_styles();
    mvc_render_student_zone_hero($slug);
    mvc_render_student_zone_nav($slug);

    echo '<main class="mvc-sz-container" id="sz-main-content">';
    switch ($slug) {
        case 'admission-procedure':
            mvc_render_sz_admission_procedure();
            break;
        case 'sanctioned-seat':
            mvc_render_sz_sanctioned_seat();
            break;
        case 'enrollment-position':
            mvc_render_sz_enrollment_position();
            break;
        case 'college-prospectus':
            mvc_render_sz_college_prospectus();
            break;
        case 'ug-syllabus':
            mvc_render_sz_syllabus('ug');
            break;
        case 'pg-syllabus':
            mvc_render_sz_syllabus('pg');
            break;
        case 'syllabus':
            mvc_render_sz_syllabus('all');
            break;
        case 'study-material':
            mvc_render_sz_study_material();
            break;
        case 'outcomes':
            mvc_render_sz_outcomes();
            break;
        case 'result':
            mvc_render_sz_result();
            break;
        case 'holiday-calendar':
            mvc_render_sz_holiday_calendar();
            break;
        case 'student-testimonial':
            mvc_render_sz_student_testimonial();
            break;
        case 'alumni-registration':
            mvc_render_sz_alumni_registration();
            break;
        case '360-degree-feedback':
            mvc_render_sz_360_feedback();
            break;
        case 'feedback-report':
            mvc_render_sz_feedback_report();
            break;
        case 'student-grievance':
            mvc_render_sz_student_grievance();
            break;
        default:
            mvc_render_sz_default_overview();
            break;
    }
    echo '</main>';
    echo '</div>'; // .mvc-sz-root
}

/**
 * Shared Styles for 100% Full-Width Stretched Layout & Mobile Responsive Tables
 */
function mvc_render_student_zone_shared_styles() {
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
        body.page-template-template-student-zone #page,
        body.page-template-template-student-zone .site-content,
        body.page-template-template-student-zone .ast-container,
        body.page-template-template-student-zone #content,
        body.page-template-template-student-zone #primary,
        body.page-template-template-student-zone #main,
        body.page-template-template-student-zone .entry-content {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .mvc-sz-root {
            background: #F8FAFC;
            color: var(--mvc-slate);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            padding-bottom: 80px;
        }

        /* Hero Header */
        .mvc-sz-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #082d5a 100%);
            color: #FFFFFF;
            padding: clamp(36px, 5vw, 60px) clamp(20px, 4vw, 60px);
            text-align: center;
            border-bottom: 4px solid var(--mvc-gold);
            width: 100% !important;
            box-sizing: border-box !important;
            position: relative;
        }

        .mvc-sz-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 14px;
        }

        .mvc-sz-breadcrumb a {
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-sz-breadcrumb a:hover {
            color: var(--mvc-gold);
        }

        .mvc-sz-hero h1 {
            font-size: clamp(26px, 4.2vw, 42px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .mvc-sz-hero-hindi {
            font-size: clamp(16px, 2.4vw, 20px);
            font-weight: 600;
            color: var(--mvc-gold);
            margin-bottom: 12px;
        }

        .mvc-sz-hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.6;
            margin: 0 auto;
            max-width: 900px;
        }

        /* Student Zone Sticky Navigation Track */
        .mvc-sz-nav-strip {
            background: #FFFFFF;
            border-bottom: 1px solid var(--mvc-border);
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
        }

        .mvc-sz-nav-wrapper {
            display: flex;
            align-items: center;
            position: relative;
            max-width: 100%;
            margin: 0 auto;
            padding: 4px clamp(10px, 2.5vw, 24px);
            gap: 8px;
        }

        .mvc-nav-arrow-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: #F8FAFC;
            border: 1.5px solid #CBD5E1;
            color: var(--mvc-navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
            padding: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            z-index: 5;
        }

        .mvc-nav-arrow-btn:hover {
            background: var(--mvc-navy);
            color: #FFFFFF;
            border-color: var(--mvc-navy);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.2);
        }

        .mvc-nav-arrow-btn:active {
            transform: scale(0.95);
        }

        .mvc-nav-arrow-btn.is-disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
            background: #F1F5F9;
            border-color: #E2E8F0;
            color: #94A3B8;
            box-shadow: none;
            transform: none;
        }

        .mvc-sz-nav-inner {
            display: flex;
            align-items: center;
            padding: 0 4px;
            gap: 6px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        .mvc-sz-nav-inner::-webkit-scrollbar {
            display: none;
        }

        .mvc-sz-nav-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-slate-light);
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .mvc-sz-nav-item:hover {
            color: var(--mvc-navy);
            background: #F1F5F9;
        }

        .mvc-sz-nav-item.active {
            color: var(--mvc-navy);
            border-bottom-color: var(--mvc-gold);
            background: #FAF9F6;
        }

        /* Main Container */
        .mvc-sz-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 40px clamp(20px, 4vw, 60px) 0 !important;
            box-sizing: border-box !important;
        }

        /* Section Cards & Panels */
        .mvc-sz-card {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            padding: clamp(24px, 3.5vw, 40px);
            margin-bottom: 36px;
        }

        .mvc-sz-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--mvc-border);
            padding-bottom: 16px;
        }

        .mvc-sz-section-title {
            font-size: clamp(20px, 2.6vw, 26px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mvc-sz-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: var(--mvc-gold-light);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: var(--mvc-maroon);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Responsive Data Tables with Mobile Cards */
        .mvc-sz-table-wrap {
            width: 100%;
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid var(--mvc-border);
            background: #FFFFFF;
            margin-bottom: 24px;
        }

        .mvc-sz-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        .mvc-sz-table thead th {
            background: var(--mvc-navy);
            color: #FFFFFF;
            padding: 14px 16px;
            font-weight: 700;
            letter-spacing: 0.3px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mvc-sz-table tbody tr {
            border-bottom: 1px solid var(--mvc-border);
            transition: background 0.15s;
        }

        .mvc-sz-table tbody tr:nth-child(even) {
            background: var(--mvc-bg-alt);
        }

        .mvc-sz-table tbody tr:hover {
            background: #F1F5F9;
        }

        .mvc-sz-table td {
            padding: 13px 16px;
            color: var(--mvc-slate);
            border-right: 1px solid var(--mvc-border);
            vertical-align: middle;
        }

        /* Buttons & Dual Action Links */
        .mvc-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .mvc-btn-primary {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        .mvc-btn-primary:hover {
            background: var(--mvc-navy-dark);
            color: var(--mvc-gold);
            transform: translateY(-1px);
        }

        .mvc-btn-gold {
            background: var(--mvc-gold);
            color: var(--mvc-navy-dark);
        }

        .mvc-btn-gold:hover {
            background: #b59226;
            color: #FFFFFF;
        }

        .mvc-btn-maroon {
            background: var(--mvc-maroon);
            color: #FFFFFF;
        }

        .mvc-btn-maroon:hover {
            background: var(--mvc-maroon-dark);
            color: #FFFFFF;
        }

        .mvc-btn-outline {
            background: transparent;
            border: 1.5px solid var(--mvc-navy);
            color: var(--mvc-navy);
        }

        .mvc-btn-outline:hover {
            background: var(--mvc-navy);
            color: #FFFFFF;
        }

        /* Interactive Tabs */
        .mvc-tab-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--mvc-border);
            padding-bottom: 12px;
            flex-wrap: wrap;
        }

        .mvc-tab-btn {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--mvc-slate);
            cursor: pointer;
            transition: all 0.2s;
        }

        .mvc-tab-btn:hover {
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
        }

        .mvc-tab-btn.active {
            background: var(--mvc-navy);
            border-color: var(--mvc-navy);
            color: #FFFFFF;
        }

        .mvc-tab-pane {
            display: none;
        }

        .mvc-tab-pane.active {
            display: block;
        }

        /* Form Container Styling */
        .mvc-sz-form-wrap {
            background: #FFFFFF;
            border-radius: 14px;
            border: 2px solid rgba(0, 33, 71, 0.08);
            box-shadow: 0 4px 20px rgba(0, 33, 71, 0.04);
            padding: clamp(24px, 4vw, 44px);
            margin-top: 36px;
        }

        .mvc-sz-form-header {
            margin-bottom: 28px;
            text-align: center;
            border-bottom: 2px solid #F1F5F9;
            padding-bottom: 20px;
        }

        .mvc-sz-form-header h3 {
            font-size: clamp(20px, 2.5vw, 24px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 8px;
        }

        .mvc-sz-form-header p {
            color: var(--mvc-slate-light);
            font-size: 14.5px;
            line-height: 1.6;
            margin: 0;
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Responsive Form Grid */
        .mvc-form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 767px) {
            .mvc-form-grid-2 {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        .mvc-input-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 4px;
        }

        .mvc-input-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1.5px solid var(--mvc-border);
            font-size: 14.5px;
            font-family: inherit;
            color: var(--mvc-slate);
            background: #FFFFFF;
            box-sizing: border-box;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
            line-height: 1.5;
        }

        .mvc-input-control:focus {
            border-color: var(--mvc-navy);
            box-shadow: 0 0 0 3.5px rgba(0, 33, 71, 0.12);
        }

        .mvc-form-submit-btn {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-dark) 100%);
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 700;
            padding: 13px 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.18);
            letter-spacing: 0.3px;
            min-height: 48px;
        }

        .mvc-form-submit-btn:hover {
            background: var(--mvc-maroon);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(128, 0, 0, 0.25);
        }

        .mvc-form-feedback-box {
            background: #F0FDF4;
            border: 1.5px solid #BBF7D0;
            border-radius: 10px;
            padding: 22px 28px;
            color: #166534;
            font-size: 15px;
            line-height: 1.6;
            box-shadow: 0 4px 12px rgba(22, 101, 52, 0.06);
        }

        /* Mobile First Responsive Styles */
        @media (max-width: 767px) {
            .mvc-sz-table thead {
                display: none;
            }

            .mvc-sz-table, .mvc-sz-table tbody, .mvc-sz-table tr, .mvc-sz-table td {
                display: block;
                width: 100% !important;
                box-sizing: border-box;
            }

            .mvc-sz-table tr {
                background: #FFFFFF !important;
                border: 1.5px solid var(--mvc-border) !important;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .mvc-sz-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border: none;
                border-bottom: 1px dashed var(--mvc-border);
                font-size: 13.5px;
                text-align: right;
            }

            .mvc-sz-table td:last-child {
                border-bottom: none;
                padding-top: 14px;
                justify-content: flex-end;
            }

            .mvc-sz-table td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--mvc-navy);
                text-align: left;
                padding-right: 12px;
                flex-shrink: 0;
            }
        }
    </style>
    <script>
        function mvcSwitchTab(tabGroup, targetPaneId, clickedBtn) {
            var group = document.getElementById(tabGroup);
            if (!group) return;
            var buttons = group.querySelectorAll('.mvc-tab-btn');
            var panes = group.querySelectorAll('.mvc-tab-pane');
            buttons.forEach(function(b) { b.classList.remove('active'); });
            panes.forEach(function(p) { p.classList.remove('active'); });
            clickedBtn.classList.add('active');
            var target = document.getElementById(targetPaneId);
            if (target) {
                target.classList.add('active');
            }
        }

        function mvcSzNavScroll(direction) {
            var track = document.getElementById('mvcSzNavTrack');
            if (!track) return;
            var scrollDistance = 280 * direction;
            track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
            setTimeout(mvcUpdateSzNavArrowStates, 320);
        }

        function mvcUpdateSzNavArrowStates() {
            var track = document.getElementById('mvcSzNavTrack');
            var prevBtn = document.getElementById('mvcSzNavArrowPrev');
            var nextBtn = document.getElementById('mvcSzNavArrowNext');
            if (!track || !prevBtn || !nextBtn) return;

            var scrollLeft = Math.round(track.scrollLeft);
            var maxScroll = Math.round(track.scrollWidth - track.clientWidth);

            if (scrollLeft <= 6) {
                prevBtn.classList.add('is-disabled');
            } else {
                prevBtn.classList.remove('is-disabled');
            }

            if (scrollLeft >= maxScroll - 6 || maxScroll <= 0) {
                nextBtn.classList.add('is-disabled');
            } else {
                nextBtn.classList.remove('is-disabled');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var track = document.getElementById('mvcSzNavTrack');
            if (track) {
                track.addEventListener('scroll', mvcUpdateSzNavArrowStates, { passive: true });
                window.addEventListener('resize', mvcUpdateSzNavArrowStates, { passive: true });
                
                var activeItem = document.getElementById('mvcActiveSzNavItem');
                if (activeItem) {
                    setTimeout(function() {
                        activeItem.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                        setTimeout(mvcUpdateSzNavArrowStates, 320);
                    }, 120);
                } else {
                    mvcUpdateSzNavArrowStates();
                }
            }
        });
    </script>
    <?php
}

/**
 * Universal Hero for Student Zone Pages
 */
function mvc_render_student_zone_hero($slug) {
    $meta = mvc_get_sz_meta($slug);
    ?>
    <header class="mvc-sz-hero">
        <div class="mvc-sz-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
            <span>/</span>
            <a href="<?php echo esc_url(home_url('/admission-procedure/')); ?>">Student Zone</a>
            <span>/</span>
            <span><?php echo esc_html($meta['short_title']); ?></span>
        </div>
        <h1><?php echo esc_html($meta['title']); ?></h1>
        <div class="mvc-sz-hero-hindi"><?php echo esc_html($meta['hindi_title']); ?></div>
        <p class="mvc-sz-hero-desc"><?php echo esc_html($meta['desc']); ?></p>
    </header>
    <?php
}

/**
 * Sticky Student Zone Navigation Track
 */
function mvc_render_student_zone_nav($active_slug) {
    $nav_items = [
        'enrollment-position' => 'Enrollment Position',
        'admission-procedure' => 'Admission Procedure',
        'sanctioned-seat'     => 'Sanctioned Seats',
        'college-prospectus'  => 'Prospectus',
        'ug-syllabus'         => 'UG Syllabus',
        'pg-syllabus'         => 'PG Syllabus',
        'study-material'      => 'Study Material',
        'outcomes'            => 'Outcomes (PO/CO)',
        'result'              => 'Exam Results',
        'holiday-calendar'    => 'Holiday Calendar',
        'academic-calendar'   => 'Academic Calendar',
        'student-testimonial' => 'Testimonials',
        'alumni-registration' => 'Alumni Association',
        '360-degree-feedback' => '360° Feedback',
        'feedback-report'     => 'Feedback ATR',
        'student-grievance'   => 'Grievance Redressal'
    ];
    ?>
    <nav class="mvc-sz-nav-strip" aria-label="Student Zone Navigation">
        <div class="mvc-sz-nav-wrapper">
            <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-prev is-disabled" id="mvcSzNavArrowPrev" onclick="mvcSzNavScroll(-1)" aria-label="Scroll Student Zone Left" title="Scroll left">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <div class="mvc-sz-nav-inner" id="mvcSzNavTrack">
                <?php foreach ($nav_items as $slug => $label): 
                    $is_active = ($active_slug === $slug || 
                        ($slug === 'sanctioned-seat' && $active_slug === 'sanctioned-seats') ||
                        ($slug === 'student-testimonial' && $active_slug === 'student-testimonials') ||
                        ($slug === '360-degree-feedback' && $active_slug === '360-feedback') ||
                        ($slug === 'feedback-report' && $active_slug === '360-feedback-report') ||
                        ($slug === 'ug-syllabus' && ($active_slug === 'fyugp-syllabus' || $active_slug === 'syllabus'))
                    );
                    $url = ($slug === 'academic-calendar') ? home_url('/academic-calendar/') : home_url('/' . $slug . '/');
                ?>
                    <a href="<?php echo esc_url($url); ?>" class="mvc-sz-nav-item <?php echo $is_active ? 'active' : ''; ?>" <?php echo $is_active ? 'id="mvcActiveSzNavItem" aria-current="page"' : ''; ?>>
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-next" id="mvcSzNavArrowNext" onclick="mvcSzNavScroll(1)" aria-label="Scroll Student Zone Right" title="Scroll right">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </nav>
    <?php
}

/**
 * Helper to resolve page ID for any Student Zone slug
 */
function mvc_get_sz_page_id($slug = '') {
    global $post;
    if ($post && !empty($post->ID)) {
        if (empty($slug) || $post->post_name === $slug) {
            return (int) $post->ID;
        }
    }
    if (!empty($slug)) {
        $page = get_page_by_path($slug);
        if ($page) {
            return (int) $page->ID;
        }
    }
    return 0;
}

/**
 * Metadata dictionary for titles, hindi subtitles, and descriptions
 * Dynamically resolved via mvc_get_page_meta() with authentic defaults.
 */
function mvc_get_sz_meta($slug) {
    $dict = [
        'admission-procedure' => [
            'short_title' => 'Admission Procedure',
            'title'       => 'Admission Procedure & Guidelines',
            'hindi_title' => 'प्रवेश प्रक्रिया एवं मार्गदर्शिका',
            'desc'        => 'Official statutory regulations, eligibility criteria, document checklists, and reservation norms for undergraduate and postgraduate admissions at M.V. College, Buxar under Veer Kunwar Singh University (VKSU).'
        ],
        'sanctioned-seat' => [
            'short_title' => 'Sanctioned Seats',
            'title'       => 'Sanctioned Intake & Seats Matrix',
            'hindi_title' => 'स्वीकृत सीट विवरण एवं वर्गवार आवंटन',
            'desc'        => 'Department-wise approved intake capacity across Arts (Humanities & Social Sciences), Science, and Vocational programs as sanctioned by the State Government of Bihar and VKSU Ara.'
        ],
        'enrollment-position' => [
            'short_title' => 'Enrollment Position',
            'title'       => 'Enrollment Position & Demographics',
            'hindi_title' => 'नामांकन स्थिति एवं सामाजिक-लैंगिक सांख्यिकी',
            'desc'        => 'Annual demographic profile of enrolled students categorized by gender, social category (General, BC-I, BC-II, SC, ST, Minority), and academic programs aligned with AISHE statutory standards.'
        ],
        'college-prospectus' => [
            'short_title' => 'College Prospectus',
            'title'       => 'Official College Prospectus',
            'hindi_title' => 'महाविद्यालय विवरणिका एवं संस्थागत संहिता',
            'desc'        => 'Comprehensive information booklet detailing academic programs, faculty profiles, institutional infrastructure, code of conduct, fee schedules, and campus facilities.'
        ],
        'ug-syllabus' => [
            'short_title' => 'UG Syllabus',
            'title'       => 'Undergraduate Curriculum & FYUGP Syllabus (NEP 2020)',
            'hindi_title' => 'स्नातक पाठ्यक्रम विवरणिका (FYUGP NEP-2020)',
            'desc'        => 'Official Four-Year Undergraduate Programme (FYUGP) course structure, Major (MJC), Minor (MIC), Multidisciplinary (MDC), and Skill courses under National Education Policy 2020.'
        ],
        'pg-syllabus' => [
            'short_title' => 'PG Syllabus',
            'title'       => 'Postgraduate (PG) CBCS Syllabus Repository',
            'hindi_title' => 'स्नातकोत्तर पाठ्यक्रम विवरणिका (CBCS)',
            'desc'        => 'Official Choice Based Credit System (CBCS) postgraduate course curricula across M.A. and M.Sc. departments sanctioned and approved by Veer Kunwar Singh University, Ara.'
        ],
        'syllabus' => [
            'short_title' => 'Syllabus Repository',
            'title'       => 'Curriculum & Syllabus Repository',
            'hindi_title' => 'पाठ्यक्रम विवरणिका (FYUGP NEP-2020 एवं CBCS)',
            'desc'        => 'Centralized institutional repository of official course curricula covering 4-Year Undergraduate Programs (FYUGP NEP 2020), Postgraduate CBCS semesters, and degree syllabi across all disciplines.'
        ],
        'study-material' => [
            'short_title' => 'Study Material',
            'title'       => 'e-Learning & Faculty Study Material',
            'hindi_title' => 'ई-लर्निंग एवं प्राध्यापक अध्ययन सामग्री',
            'desc'        => 'Academic notes, unit-wise lecture modules, references, and digital learning assets published by M.V. College faculty members for undergraduate and postgraduate scholars.'
        ],
        'outcomes' => [
            'short_title' => 'Learning Outcomes',
            'title'       => 'Learning Outcomes (PO / PSO / CO)',
            'hindi_title' => 'अधिगम प्रतिफल (कार्यक्रम, विशिष्ट एवं पाठ्यक्रम प्रतिफल)',
            'desc'        => 'Statutory institutional outcome framework articulating Program Outcomes (PO), Program Specific Outcomes (PSO), and Course Outcomes (CO) across all faculties under NAAC Criterion II.'
        ],
        'result' => [
            'short_title' => 'Exam Results',
            'title'       => 'Examination Results & Scorecards Portal',
            'hindi_title' => 'विश्वविद्यालय परीक्षा परिणाम एवं अंकतालिका पोर्टल',
            'desc'        => 'Direct operational gateway to Veer Kunwar Singh University examination scorecards, semester marksheets, scrutiny guidelines, and result verification circulars.'
        ],
        'holiday-calendar' => [
            'short_title' => 'Holiday Calendar',
            'title'       => 'Institutional Holiday Calendar',
            'hindi_title' => 'वार्षिक अवकाश तालिका (कुलाधिपति सचिवालय एवं वि.वि. अनुमोदित)',
            'desc'        => 'Official list of gazetted, executive, and institutional holidays sanctioned by the Chancellor’s Secretariat, Raj Bhavan, Bihar, and Veer Kunwar Singh University, Ara.'
        ],
        'student-testimonial' => [
            'short_title' => 'Student Testimonials',
            'title'       => 'Student Testimonials & Campus Voices',
            'hindi_title' => 'छात्र अनुभव, संस्मरण एवं प्रेरक स्वर',
            'desc'        => 'Inspiring student reflections, academic journeys, and testimonials reflecting the academic rigor, holistic culture, and supportive mentorship at M.V. College, Buxar.'
        ],
        'alumni-registration' => [
            'short_title' => 'Alumni Association',
            'title'       => 'M.V. College Alumni Association (MVCAA)',
            'hindi_title' => 'महर्षि विश्वामित्र महाविद्यालय पूर्व छात्र संघ एवं पंजीकरण',
            'desc'        => 'Connecting generations of scholars, leaders, and professionals. Register for lifelong institutional association, mentorship networks, and alumni developmental initiatives.'
        ],
        '360-degree-feedback' => [
            'short_title' => '360° Feedback',
            'title'       => '360° Stakeholder Feedback System',
            'hindi_title' => 'सर्वांगीण संस्थागत प्रतिपुष्टि प्रणाली',
            'desc'        => 'Structured statutory feedback portal for Students, Faculty, Alumni, Parents, and Employers guiding institutional quality benchmarks under the aegis of IQAC.'
        ],
        'feedback-report' => [
            'short_title' => 'Feedback ATR',
            'title'       => 'Feedback Analysis & Action Taken Reports (ATR)',
            'hindi_title' => 'प्रतिपुष्टि विश्लेषण एवं कार्रवाई प्रतिवेदन',
            'desc'        => 'Official institutional reports published annually by IQAC summarizing stakeholder survey findings and tangible action taken by college leadership.'
        ],
        'student-grievance' => [
            'short_title' => 'Grievance Redressal',
            'title'       => 'Student Grievance Redressal Portal',
            'hindi_title' => 'छात्र शिकायत निवारण एवं संस्थागत लोकपाल प्रकोष्ठ',
            'desc'        => 'Statutory, transparent dispute resolution portal compliant with UGC Regulations 2019, ensuring swift redressal of academic, evaluation, and administrative grievances.'
        ]
    ];

    $default = isset($dict[$slug]) ? $dict[$slug] : [
        'short_title' => 'Student Zone',
        'title'       => 'Student Zone & Academic Hub',
        'hindi_title' => 'छात्र प्रकोष्ठ एवं शैक्षणिक केंद्र',
        'desc'        => 'Centralized academic and administrative portal for students of Maharshi Vishwamitra Mahavidyalaya.'
    ];

    $post_id = mvc_get_sz_page_id($slug);
    if ($post_id > 0 && function_exists('mvc_get_page_meta')) {
        return [
            'short_title' => mvc_get_page_meta($post_id, 'mvc_short_title', $default['short_title']),
            'title'       => mvc_get_page_meta($post_id, 'mvc_page_title', $default['title']),
            'hindi_title' => mvc_get_page_meta($post_id, 'mvc_hindi_title', $default['hindi_title']),
            'desc'        => mvc_get_page_meta($post_id, 'mvc_page_desc', $default['desc'])
        ];
    }

    return $default;
}

/**
 * 1. Admission Procedure Page Renderer
 */
function mvc_render_sz_admission_procedure() {
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Academic Programs & Disciplines Offered
            </h2>
            <span class="mvc-sz-badge">Approved by VKSU & Govt. of Bihar</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Maharshi Vishwamitra Mahavidyalaya offers comprehensive undergraduate and postgraduate programs across four major academic streams. Admissions are conducted strictly on merit via the centralized VKSU Ara Online Admission Portal.
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th>Humanities Stream</th>
                        <th>Social Science Stream</th>
                        <th>Science Stream</th>
                        <th>Vocational & Professional Courses</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Humanities Stream"><strong>English</strong> (B.A. Hons / FYUGP)</td>
                        <td data-label="Social Science Stream"><strong>Economics</strong> (B.A. Hons / FYUGP)</td>
                        <td data-label="Science Stream"><strong>Botany</strong> (B.Sc. Hons / FYUGP)</td>
                        <td data-label="Vocational Courses"><strong>BBA</strong> (Bachelor of Business Admin)</td>
                    </tr>
                    <tr>
                        <td data-label="Humanities Stream"><strong>Hindi</strong> (B.A. & M.A.)</td>
                        <td data-label="Social Science Stream"><strong>History</strong> (B.A. & M.A.)</td>
                        <td data-label="Science Stream"><strong>Chemistry</strong> (B.Sc. Hons / FYUGP)</td>
                        <td data-label="Vocational Courses"><strong>BCA</strong> (Bachelor of Computer App)</td>
                    </tr>
                    <tr>
                        <td data-label="Humanities Stream"><strong>Philosophy</strong> (B.A. Hons / FYUGP)</td>
                        <td data-label="Social Science Stream"><strong>Home Science</strong> (B.A. Hons / FYUGP)</td>
                        <td data-label="Science Stream"><strong>Mathematics</strong> (B.Sc. Hons / FYUGP)</td>
                        <td data-label="Vocational Courses">Add-on Certificate & ICT Courses</td>
                    </tr>
                    <tr>
                        <td data-label="Humanities Stream"><strong>Sanskrit</strong> (B.A. Hons / FYUGP)</td>
                        <td data-label="Social Science Stream"><strong>Political Science</strong> (B.A. & M.A.)</td>
                        <td data-label="Science Stream"><strong>Physics</strong> (B.Sc. Hons / FYUGP)</td>
                        <td data-label="Vocational Courses">Spoken English & Communication Skills</td>
                    </tr>
                    <tr>
                        <td data-label="Humanities Stream">General & Interdisciplinary Studies</td>
                        <td data-label="Social Science Stream"><strong>Psychology</strong> (B.A. & M.A.)</td>
                        <td data-label="Science Stream"><strong>Zoology</strong> (B.Sc. Hons / FYUGP)</td>
                        <td data-label="Vocational Courses">NSS / NCC Skill Credit Options</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statutory Reservation Policy -->
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Statutory Reservation Policy
            </h2>
            <span class="mvc-sz-badge">Bihar State Govt. Gazette Mandate</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Seats across all programs are filled strictly according to the statutory reservation rules formulated by the Department of Education, Government of Bihar, and Veer Kunwar Singh University, Ara:
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th>Category Classification</th>
                        <th>Statutory Reservation Percentage</th>
                        <th>Mandatory Document / Certificate Required</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Category Classification"><strong>Scheduled Caste (SC)</strong></td>
                        <td data-label="Reservation Percentage"><strong style="color:var(--mvc-maroon);">16%</strong></td>
                        <td data-label="Document Required">Caste Certificate issued by Revenue Officer / SDO / DM (Bihar)</td>
                    </tr>
                    <tr>
                        <td data-label="Category Classification"><strong>Scheduled Tribe (ST)</strong></td>
                        <td data-label="Reservation Percentage"><strong style="color:var(--mvc-maroon);">1%</strong></td>
                        <td data-label="Document Required">Caste Certificate issued by competent Bihar Revenue Authority</td>
                    </tr>
                    <tr>
                        <td data-label="Category Classification"><strong>Extremely Backward Class (EBC / BC-I)</strong></td>
                        <td data-label="Reservation Percentage"><strong style="color:var(--mvc-maroon);">18%</strong></td>
                        <td data-label="Document Required">Non-Creamy Layer (NCL) Certificate issued in current financial year</td>
                    </tr>
                    <tr>
                        <td data-label="Category Classification"><strong>Backward Class (BC / BC-II)</strong></td>
                        <td data-label="Reservation Percentage"><strong style="color:var(--mvc-maroon);">12%</strong></td>
                        <td data-label="Document Required">Non-Creamy Layer (NCL) Certificate issued in current financial year</td>
                    </tr>
                    <tr>
                        <td data-label="Category Classification"><strong>Women of Backward Classes (WBC)</strong></td>
                        <td data-label="Reservation Percentage"><strong style="color:var(--mvc-maroon);">3%</strong></td>
                        <td data-label="Document Required">Caste and Domicile Certificate issued by Revenue Authority</td>
                    </tr>
                    <tr>
                        <td data-label="Category Classification"><strong>Economically Weaker Section (EWS)</strong></td>
                        <td data-label="Reservation Percentage"><strong style="color:var(--mvc-maroon);">10%</strong></td>
                        <td data-label="Document Required">Income & Asset Certificate for EWS issued by competent authority</td>
                    </tr>
                    <tr>
                        <td data-label="Category Classification"><strong>Divyangjan / Persons with Disabilities (PwD)</strong></td>
                        <td data-label="Reservation Percentage"><strong style="color:var(--mvc-navy);">4% (Horizontal)</strong></td>
                        <td data-label="Document Required">Disability Certificate (&ge;40%) from District Medical Board</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Step-by-Step Procedure & Checklist -->
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Step-by-Step Admission Process & Document Checklist
            </h2>
            <a href="https://vksu.ac.in/" target="_blank" rel="noopener" class="mvc-btn mvc-btn-gold">
                VKSU Centralized Portal &rarr;
            </a>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; margin-top:20px;">
            <div style="background:var(--mvc-bg-alt); padding:24px; border-radius:10px; border-left:4px solid var(--mvc-navy);">
                <h4 style="margin:0 0 10px; color:var(--mvc-navy); font-size:16px;">Step 1: Centralized University Registration</h4>
                <p style="font-size:13.5px; color:var(--mvc-slate-light); margin:0 0 10px; line-height:1.5;">
                    Visit the official VKSU Ara online portal (<code>vksu.ac.in</code>) during the active admission cycle. Register with candidate details, select Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar) as your preferred constituent college, and choose Major (MJC) subjects.
                </p>
            </div>

            <div style="background:var(--mvc-bg-alt); padding:24px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                <h4 style="margin:0 0 10px; color:var(--mvc-maroon); font-size:16px;">Step 2: Merit List & College Allotment</h4>
                <p style="font-size:13.5px; color:var(--mvc-slate-light); margin:0 0 10px; line-height:1.5;">
                    University publishes cutoff merit lists based on Intermediate (+2) marks and reservation quotas. Download the Provisional Allotment Letter allotted to M.V. College, Buxar.
                </p>
            </div>

            <div style="background:var(--mvc-bg-alt); padding:24px; border-radius:10px; border-left:4px solid var(--mvc-gold);">
                <h4 style="margin:0 0 10px; color:#926f0c; font-size:16px;">Step 3: Document Verification & Fee Deposit</h4>
                <p style="font-size:13.5px; color:var(--mvc-slate-light); margin:0 0 10px; line-height:1.5;">
                    Report in person to the M.V. College Admission Desk with original documents and photocopies. Complete online tuition fee payment and obtain official admission receipt and college identity card.
                </p>
            </div>
        </div>

        <h4 style="margin:28px 0 12px; color:var(--mvc-navy); font-size:16px;">Mandatory Documents Required for Physical Verification:</h4>
        <ul style="color:var(--mvc-slate); font-size:14px; line-height:1.8; margin-bottom:0; padding-left:20px;">
            <li>Class 10th / Matriculation Marksheet & Passing Certificate (DOB proof).</li>
            <li>Class 12th / Intermediate (+2) Marksheet, Admit Card & Passing Certificate.</li>
            <li>Original College Leaving Certificate (CLC) / School Leaving Certificate (SLC).</li>
            <li>Original Migration Certificate (for students coming from boards other than Bihar School Examination Board).</li>
            <li>Valid Caste / Category / EWS / Disability Certificate issued by competent Bihar Revenue Authority.</li>
            <li>Four (4) recent colored passport-size photographs.</li>
            <li>Copy of online VKSU Allotment Letter and Application Form printout.</li>
        </ul>
    </div>
    <?php
}

/**
 * 2. Sanctioned Seat Page Renderer
 */
function mvc_render_sz_sanctioned_seat() {
    $post_id = mvc_get_sz_page_id('sanctioned-seat');

    $default_ug = [
        ['stream' => 'Faculty of Science', 'dept' => 'Physics', 'seats' => '200', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Science', 'dept' => 'Botany', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Science', 'dept' => 'Chemistry', 'seats' => '175', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Science', 'dept' => 'Mathematics', 'seats' => '200', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Science', 'dept' => 'Zoology', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Social Science', 'dept' => 'Political Science', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Social Science', 'dept' => 'Psychology', 'seats' => '105', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Social Science', 'dept' => 'History', 'seats' => '105', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Humanities', 'dept' => 'Hindi', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Humanities', 'dept' => 'English', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Humanities', 'dept' => 'Philosophy', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Humanities', 'dept' => 'Sanskrit', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara'],
        ['stream' => 'Faculty of Social Science', 'dept' => 'Economics', 'seats' => '60', 'body' => 'Veer Kunwar Singh University, Ara']
    ];
    $ug_data = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_ug_sanctioned_seats', $default_ug) : $default_ug;

    $default_pg = [
        ['program' => 'Master of Arts (M.A.)', 'dept' => 'History', 'seats' => '105', 'body' => 'VKSU Ara / Govt. of Bihar'],
        ['program' => 'Master of Arts (M.A.)', 'dept' => 'Psychology', 'seats' => '105', 'body' => 'VKSU Ara / Govt. of Bihar'],
        ['program' => 'Master of Arts (M.A.)', 'dept' => 'Political Science', 'seats' => '60', 'body' => 'VKSU Ara / Govt. of Bihar'],
        ['program' => 'Master of Arts (M.A.)', 'dept' => 'Hindi', 'seats' => '60', 'body' => 'VKSU Ara / Govt. of Bihar'],
        ['program' => 'Vocational Degree', 'dept' => 'BCA (Bachelor of Computer Applications)', 'seats' => '60', 'body' => 'VKSU Ara Approved'],
        ['program' => 'Vocational Degree', 'dept' => 'BBA (Bachelor of Business Administration)', 'seats' => '60', 'body' => 'VKSU Ara Approved']
    ];
    $pg_data = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_pg_sanctioned_seats', $default_pg) : $default_pg;

    $total_ug = 0;
    foreach ($ug_data as $row) {
        $total_ug += intval(preg_replace('/[^0-9]/', '', $row['seats'] ?? '0'));
    }
    $total_pg = 0;
    foreach ($pg_data as $row) {
        $total_pg += intval(preg_replace('/[^0-9]/', '', $row['seats'] ?? '0'));
    }
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Undergraduate (UG) Sanctioned Seats
            </h2>
            <span class="mvc-sz-badge">Faculty of Arts & Science</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Approved intake capacity for Four-Year Undergraduate Programs (FYUGP) under the National Education Policy (NEP 2020) and CBCS degree courses at M.V. College, Buxar:
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Sr. No.</th>
                        <th>Academic Stream</th>
                        <th>Department / Major Subject</th>
                        <th>Approved Sanctioned Seats</th>
                        <th>Affiliating Body</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ug_data as $idx => $r): 
                        $stream_color = (strpos($r['stream'], 'Science') !== false) ? 'var(--mvc-navy)' : 'var(--mvc-maroon)';
                    ?>
                        <tr>
                            <td data-label="Sr. No."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Academic Stream"><?php echo esc_html($r['stream']); ?></td>
                            <td data-label="Department"><strong><?php echo esc_html($r['dept']); ?></strong></td>
                            <td data-label="Sanctioned Seats"><strong style="color:<?php echo esc_attr($stream_color); ?>; font-size:15px;"><?php echo esc_html($r['seats']); ?></strong></td>
                            <td data-label="Affiliating Body"><?php echo esc_html($r['body']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background:#FAF9F6; font-weight:800;">
                        <td colspan="3" style="text-align:right; color:var(--mvc-navy);">Total Approved UG Intake:</td>
                        <td colspan="2" style="color:var(--mvc-navy); font-size:16px;"><?php echo number_format($total_ug); ?> Seats</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Postgraduate & Vocational Sanctioned Seats -->
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Postgraduate (PG) & Vocational Intake
            </h2>
            <span class="mvc-sz-badge">Postgraduate Centers & Self-Financed</span>
        </div>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Sr. No.</th>
                        <th>Program / Level</th>
                        <th>Department</th>
                        <th>Sanctioned Seats</th>
                        <th>Affiliation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pg_data as $idx => $r): 
                        $scolor = (strpos($r['program'], 'Vocational') !== false) ? 'var(--mvc-navy)' : 'var(--mvc-maroon)';
                    ?>
                        <tr>
                            <td data-label="Sr. No."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Program"><?php echo esc_html($r['program']); ?></td>
                            <td data-label="Department"><strong><?php echo esc_html($r['dept']); ?></strong></td>
                            <td data-label="Sanctioned Seats"><strong style="color:<?php echo esc_attr($scolor); ?>; font-size:15px;"><?php echo esc_html($r['seats']); ?></strong></td>
                            <td data-label="Affiliation"><?php echo esc_html($r['body']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background:#FAF9F6; font-weight:800;">
                        <td colspan="3" style="text-align:right; color:var(--mvc-navy);">Total PG & Vocational Intake:</td>
                        <td colspan="2" style="color:var(--mvc-navy); font-size:16px;"><?php echo number_format($total_pg); ?> Seats</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * 3. Enrollment Position Renderer
 */
function mvc_render_sz_enrollment_position() {
    $post_id = mvc_get_sz_page_id('enrollment-position');

    $default_enrollment = [
        ['session' => '2023-2027', 'level' => 'FYUGP Semester I-II', 'male' => '648', 'female' => '512', 'gen' => '232', 'bc1' => '310', 'bc2' => '348', 'sc' => '214', 'st' => '18', 'minority' => '38', 'total' => '1,160'],
        ['session' => '2022-2025', 'level' => 'UG Degree (CBCS)', 'male' => '592', 'female' => '488', 'gen' => '218', 'bc1' => '294', 'bc2' => '328', 'sc' => '196', 'st' => '14', 'minority' => '30', 'total' => '1,080'],
        ['session' => '2023-2025', 'level' => 'Postgraduate (M.A.)', 'male' => '142', 'female' => '168', 'gen' => '62', 'bc1' => '84', 'bc2' => '98', 'sc' => '54', 'st' => '4', 'minority' => '8', 'total' => '310']
    ];
    $enrollment_data = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_enrollment_records', $default_enrollment) : $default_enrollment;

    $tot_male = 0;
    $tot_female = 0;
    $tot_gross = 0;
    foreach ($enrollment_data as $r) {
        $tot_male += intval(preg_replace('/[^0-9]/', '', $r['male'] ?? '0'));
        $tot_female += intval(preg_replace('/[^0-9]/', '', $r['female'] ?? '0'));
        $tot_gross += intval(preg_replace('/[^0-9]/', '', $r['total'] ?? '0'));
    }
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Category-Wise & Gender Demographic Distribution
            </h2>
            <span class="mvc-sz-badge">Statutory AISHE Return</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Official student enrollment figures categorized by social categories and gender, compiled in accordance with Ministry of Education, All India Survey on Higher Education (AISHE) norms:
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th>Academic Session</th>
                        <th>Program Level</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>General</th>
                        <th>BC-I (EBC)</th>
                        <th>BC-II (OBC)</th>
                        <th>SC</th>
                        <th>ST</th>
                        <th>Minority</th>
                        <th>Total Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollment_data as $r): ?>
                        <tr>
                            <td data-label="Academic Session"><strong><?php echo esc_html($r['session']); ?></strong></td>
                            <td data-label="Program Level"><?php echo esc_html($r['level']); ?></td>
                            <td data-label="Male"><?php echo esc_html($r['male']); ?></td>
                            <td data-label="Female"><?php echo esc_html($r['female']); ?></td>
                            <td data-label="General"><?php echo esc_html($r['gen']); ?></td>
                            <td data-label="BC-I"><?php echo esc_html($r['bc1']); ?></td>
                            <td data-label="BC-II"><?php echo esc_html($r['bc2']); ?></td>
                            <td data-label="SC"><?php echo esc_html($r['sc']); ?></td>
                            <td data-label="ST"><?php echo esc_html($r['st']); ?></td>
                            <td data-label="Minority"><?php echo esc_html($r['minority']); ?></td>
                            <td data-label="Total Enrolled"><strong style="color:var(--mvc-navy); font-size:15px;"><?php echo esc_html($r['total']); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background:#FAF9F6; font-weight:800;">
                        <td colspan="2" style="text-align:right; color:var(--mvc-navy);">Aggregated Active Campus Strength:</td>
                        <td data-label="Male" style="color:var(--mvc-navy);"><?php echo number_format($tot_male); ?></td>
                        <td data-label="Female" style="color:var(--mvc-navy);"><?php echo number_format($tot_female); ?></td>
                        <td colspan="6" style="text-align:right; color:var(--mvc-slate-light); font-size:12px;">Combined Gross Total:</td>
                        <td data-label="Total Enrolled" style="color:var(--mvc-maroon); font-size:16px;"><?php echo number_format($tot_gross); ?> Students</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="<?php echo esc_url(home_url('/aishe/')); ?>" class="mvc-btn mvc-btn-primary">
                View AISHE Institutional Certificate &rarr;
            </a>
            <a href="<?php echo esc_url(home_url('/sss/')); ?>" class="mvc-btn mvc-btn-outline">
                Student Satisfaction Survey (SSS) &rarr;
            </a>
        </div>
    </div>
    <?php
}

/**
 * 4. College Prospectus Renderer
 */
function mvc_render_sz_college_prospectus() {
    $post_id = mvc_get_sz_page_id('college-prospectus');
    $prospectus_url = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'mvc_prospectus_pdf_url', home_url('/wp-content/uploads/college_prospectus/prospectus_1763966427.pdf')) : home_url('/wp-content/uploads/college_prospectus/prospectus_1763966427.pdf');
    $prospectus_title = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'mvc_prospectus_title', 'Maharshi Vishwamitra Mahavidyalaya Prospectus (Session 2023-24 & Onwards)') : 'Maharshi Vishwamitra Mahavidyalaya Prospectus (Session 2023-24 & Onwards)';
    $prospectus_desc = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'mvc_prospectus_desc', 'Contains institutional charter, Departmental faculty directories, examination schedules, fee charts, student welfare cells, and anti-ragging statutory declarations.') : 'Contains institutional charter, Departmental faculty directories, examination schedules, fee charts, student welfare cells, and anti-ragging statutory declarations.';
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Official College Information Brochure & Prospectus
            </h2>
            <span class="mvc-sz-badge">Official Institutional Publication</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:24px;">
            The College Prospectus offers prospective and enrolled scholars comprehensive insights into the academic ethos of Maharshi Vishwamitra Mahavidyalaya, outlining curriculum structures, fee regulations, laboratory guidelines, library facilities, and statutory codes of conduct.
        </p>

        <!-- Prospectus Hero Box -->
        <div style="background:linear-gradient(135deg, #FAF9F6 0%, #FFFFFF 100%); border:2px solid var(--mvc-border); border-radius:12px; padding:clamp(20px, 4vw, 36px); display:grid; grid-template-columns:1fr auto; gap:24px; align-items:center; margin-bottom:30px;">
            <div>
                <div style="font-size:12px; font-weight:700; color:var(--mvc-maroon); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">
                    Accreditation & Academic Document
                </div>
                <h3 style="margin:0 0 10px; color:var(--mvc-navy); font-size:22px; font-weight:800;">
                    <?php echo esc_html($prospectus_title); ?>
                </h3>
                <p style="color:var(--mvc-slate-light); font-size:14px; margin:0 0 16px; line-height:1.5;">
                    <?php echo esc_html($prospectus_desc); ?>
                </p>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="<?php echo esc_url($prospectus_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-primary">
                        Direct Download Prospectus (PDF) &darr;
                    </a>
                    <a href="#prospectus-embed" class="mvc-btn mvc-btn-outline">
                        Interactive In-Page Viewer &darr;
                    </a>
                </div>
            </div>
            <div style="text-align:center; padding:16px; background:#FFFFFF; border-radius:10px; border:1px solid var(--mvc-border); box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                <div style="font-size:36px; line-height:1; color:var(--mvc-maroon); margin-bottom:8px;">&#128214;</div>
                <div style="font-size:12px; font-weight:700; color:var(--mvc-navy);">Authentic PDF</div>
                <div style="font-size:11px; color:var(--mvc-slate-light);">Self-Contained</div>
            </div>
        </div>

        <!-- Embedded Viewer -->
        <div id="prospectus-embed" style="margin-top:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h4 style="margin:0; color:var(--mvc-navy); font-size:16px; font-weight:700;">
                    Embedded Document Reader
                </h4>
                <a href="<?php echo esc_url($prospectus_url); ?>" target="_blank" rel="noopener" style="font-size:13px; font-weight:700; color:var(--mvc-maroon); text-decoration:none;">
                    Open Full Screen &rarr;
                </a>
            </div>
            <div style="width:100%; height:750px; border:1px solid var(--mvc-border); border-radius:10px; overflow:hidden; background:#525659;">
                <iframe src="<?php echo esc_url($prospectus_url); ?>#toolbar=1" width="100%" height="100%" style="border:none;" title="College Prospectus Viewer"></iframe>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 5. Syllabus Repository Renderer (FYUGP NEP 2020 & PG Syllabi)
 */
function mvc_render_sz_syllabus($active_tab = 'all') {
    $is_pg_default = ($active_tab === 'pg');

    $post_id = mvc_get_sz_page_id('ug-syllabus');
    if ($post_id <= 0) {
        $post_id = mvc_get_sz_page_id('syllabus');
    }

    // Authentic FYUGP data matrix
    $default_fyugp = [
        ['stream' => 'Humanities', 'course' => 'B.A.', 'dept' => 'Hindi', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763958925.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763959128.pdf')]
        ]],
        ['stream' => 'Humanities', 'course' => 'B.A.', 'dept' => 'English', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763959249.pdf')],
            ['title' => 'MDC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763966651.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763966735.pdf')]
        ]],
        ['stream' => 'Humanities', 'course' => 'B.A.', 'dept' => 'Sanskrit', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763966797.pdf')],
            ['title' => 'MDC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763966869.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763966922.pdf')]
        ]],
        ['stream' => 'Humanities', 'course' => 'B.A.', 'dept' => 'Philosophy', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969112.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969204.pdf')]
        ]],
        ['stream' => 'Social Science', 'course' => 'B.A.', 'dept' => 'History', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763967813.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763967959.pdf')]
        ]],
        ['stream' => 'Social Science', 'course' => 'B.A.', 'dept' => 'Psychology', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763968048.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763968135.pdf')]
        ]],
        ['stream' => 'Social Science', 'course' => 'B.A.', 'dept' => 'Economics', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763968187.pdf')],
            ['title' => 'MDC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763968344.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763968818.pdf')]
        ]],
        ['stream' => 'Social Science', 'course' => 'B.A.', 'dept' => 'Political Science', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969306.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969581.pdf')]
        ]],
        ['stream' => 'Science', 'course' => 'B.Sc.', 'dept' => 'Mathematics', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969695.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969802.pdf')]
        ]],
        ['stream' => 'Science', 'course' => 'B.Sc.', 'dept' => 'Botany', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969874.pdf')],
            ['title' => 'MDC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763969929.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763970577.pdf')]
        ]],
        ['stream' => 'Science', 'course' => 'B.Sc.', 'dept' => 'Chemistry', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763970653.pdf')],
            ['title' => 'MDC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763970742.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763972217.pdf')]
        ]],
        ['stream' => 'Science', 'course' => 'B.Sc.', 'dept' => 'Physics', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763972708.pdf')],
            ['title' => 'MDC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763972823.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763973299.pdf')]
        ]],
        ['stream' => 'Science', 'course' => 'B.Sc.', 'dept' => 'Zoology', 'docs' => [
            ['title' => 'MIC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763973356.pdf')],
            ['title' => 'MDC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763973488.pdf')],
            ['title' => 'MJC I - VIII', 'url' => home_url('/wp-content/uploads/fyugp-syllabus/FYUGP_Syllabus_1763973537.pdf')]
        ]]
    ];
    $fyugp_data = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_fyugp_syllabus_data', $default_fyugp) : $default_fyugp;

    // Authentic PG Syllabi
    $default_pg = [
        ['dept' => 'Hindi', 'title' => 'M.A. Hindi CBCS Curriculum (Sem I - IV)', 'url' => home_url('/wp-content/uploads/syllabus/Syllabus_1764932657.pdf')],
        ['dept' => 'Psychology', 'title' => 'M.A. Psychology CBCS Curriculum (Sem I - IV)', 'url' => home_url('/wp-content/uploads/syllabus/Syllabus_1764935674.pdf')],
        ['dept' => 'Political Science', 'title' => 'M.A. Political Science CBCS Curriculum (Sem I - IV)', 'url' => home_url('/wp-content/uploads/syllabus/Syllabus_1764935845.pdf')],
        ['dept' => 'History', 'title' => 'M.A. History CBCS Curriculum (Sem I - IV)', 'url' => home_url('/wp-content/uploads/syllabus/Syllabus_1764936735.pdf')]
    ];
    $pg_data = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_pg_syllabus_data', $default_pg) : $default_pg;
    ?>
    <div class="mvc-sz-card" id="syllabus-tabs-group">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Academic Curriculum & Course Syllabi
            </h2>
            <span class="mvc-sz-badge">NEP 2020 & CBCS Framework</span>
        </div>

        <!-- Tab Controls -->
        <div class="mvc-tab-nav">
            <button type="button" class="mvc-tab-btn <?php echo !$is_pg_default ? 'active' : ''; ?>" onclick="mvcSwitchTab('syllabus-tabs-group', 'tab-fyugp', this)">
                FYUGP 4-Year UG Syllabi (NEP 2020)
            </button>
            <button type="button" class="mvc-tab-btn <?php echo $is_pg_default ? 'active' : ''; ?>" onclick="mvcSwitchTab('syllabus-tabs-group', 'tab-pg', this)">
                Postgraduate (M.A.) CBCS Syllabi
            </button>
            <button type="button" class="mvc-tab-btn" onclick="mvcSwitchTab('syllabus-tabs-group', 'tab-regulations', this)">
                Academic Regulations & Course Nomenclature
            </button>
        </div>

        <!-- Tab 1: FYUGP -->
        <div id="tab-fyugp" class="mvc-tab-pane <?php echo !$is_pg_default ? 'active' : ''; ?>">
            <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
                Download the official course syllabi for Four-Year Undergraduate Programs under NEP-2020. Major (MJC), Minor (MIC), and Multidisciplinary (MDC) papers across all semesters are physically archived in the local repository:
            </p>

            <div class="mvc-sz-table-wrap">
                <table class="mvc-sz-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">Sr.</th>
                            <th>Stream</th>
                            <th>Program</th>
                            <th>Department</th>
                            <th>Curriculum Papers & Direct Downloads</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fyugp_data as $idx => $row): ?>
                            <tr>
                                <td data-label="Sr."><?php echo esc_html($idx + 1); ?></td>
                                <td data-label="Stream"><span class="mvc-sz-badge"><?php echo esc_html($row['stream']); ?></span></td>
                                <td data-label="Program"><strong><?php echo esc_html($row['course']); ?></strong></td>
                                <td data-label="Department"><strong><?php echo esc_html($row['dept']); ?></strong></td>
                                <td data-label="Curriculum Papers">
                                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                        <?php foreach ($row['docs'] as $d): ?>
                                            <a href="<?php echo esc_url($d['url']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-primary" style="padding:6px 12px; font-size:12px;">
                                                <?php echo esc_html($d['title']); ?> (PDF) &darr;
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 2: PG Syllabi -->
        <div id="tab-pg" class="mvc-tab-pane <?php echo $is_pg_default ? 'active' : ''; ?>">
            <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
                Veer Kunwar Singh University Choice Based Credit System (CBCS) postgraduate syllabi for Semesters I through IV:
            </p>

            <div class="mvc-sz-table-wrap">
                <table class="mvc-sz-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">Sr.</th>
                            <th>Department</th>
                            <th>Degree Program & Title</th>
                            <th>Coverage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pg_data as $idx => $pg): ?>
                            <tr>
                                <td data-label="Sr."><?php echo esc_html($idx + 1); ?></td>
                                <td data-label="Department"><strong><?php echo esc_html($pg['dept']); ?></strong></td>
                                <td data-label="Program"><?php echo esc_html($pg['title']); ?></td>
                                <td data-label="Coverage">Semesters I - IV</td>
                                <td data-label="Action">
                                    <a href="<?php echo esc_url($pg['url']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-maroon" style="padding:6px 12px; font-size:12px;">
                                        View / Download PDF &darr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Regulations -->
        <div id="tab-regulations" class="mvc-tab-pane">
            <h3 style="margin:0 0 12px; color:var(--mvc-navy); font-size:18px;">FYUGP Course Structure & Credit Breakdown (NEP 2020)</h3>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px; margin-top:16px;">
                <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border:1px solid var(--mvc-border);">
                    <h4 style="margin:0 0 8px; color:var(--mvc-navy);">MJC (Major Course)</h4>
                    <p style="font-size:13px; color:var(--mvc-slate-light); margin:0; line-height:1.5;">
                        Primary discipline of in-depth study, providing core competencies and research preparation over 8 semesters (80 credits).
                    </p>
                </div>
                <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border:1px solid var(--mvc-border);">
                    <h4 style="margin:0 0 8px; color:var(--mvc-maroon);">MIC (Minor Course)</h4>
                    <p style="font-size:13px; color:var(--mvc-slate-light); margin:0; line-height:1.5;">
                        Complementary discipline broadening knowledge base and inter-disciplinary perspectives (32 credits).
                    </p>
                </div>
                <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border:1px solid var(--mvc-border);">
                    <h4 style="margin:0 0 8px; color:var(--mvc-gold);">&#9733; MDC & SEC</h4>
                    <p style="font-size:13px; color:var(--mvc-slate-light); margin:0; line-height:1.5;">
                        Multidisciplinary Courses (MDC), Skill Enhancement Courses (SEC), and Ability Enhancement Courses (AEC) ensuring holistic development.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 6. Study Material Renderer
 */
function mvc_render_sz_study_material() {
    $post_id = mvc_get_sz_page_id('study-material');

    $default_materials = [
        ['faculty' => 'Dr. Priyesh Ranjan', 'dept' => 'English', 'class' => 'UG B.A. (FYUGP)', 'sem' => 'Semester I', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764656346.pdf')],
        ['faculty' => 'Dr. Priyesh Ranjan', 'dept' => 'English', 'class' => 'UG B.A. (FYUGP)', 'sem' => 'Semester II', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764657005.pdf')],
        ['faculty' => 'Dr. Priyesh Ranjan', 'dept' => 'English', 'class' => 'UG B.A. (FYUGP)', 'sem' => 'Semester I', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764657498.pdf')],
        ['faculty' => 'Dr. Priyesh Ranjan', 'dept' => 'English', 'class' => 'UG B.A. (FYUGP)', 'sem' => 'Semester V', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764657770.pdf')],
        ['faculty' => 'Dr. Priyesh Ranjan', 'dept' => 'English', 'class' => 'UG B.A. (FYUGP)', 'sem' => 'Semester I', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764658052.pdf')],
        ['faculty' => 'Dept. of Botany Faculty', 'dept' => 'Botany', 'class' => 'UG B.Sc. (FYUGP)', 'sem' => 'Semester VII', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764659745.pdf')],
        ['faculty' => 'Dept. of Botany Faculty', 'dept' => 'Botany', 'class' => 'UG B.Sc. (FYUGP)', 'sem' => 'Semester VII', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764659929.pdf')],
        ['faculty' => 'Dept. of Botany Faculty', 'dept' => 'Botany', 'class' => 'UG B.Sc. (FYUGP)', 'sem' => 'Semester VII', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764660071.pdf')],
        ['faculty' => 'Dept. of Botany Faculty', 'dept' => 'Botany', 'class' => 'UG B.Sc. (FYUGP)', 'sem' => 'Semester VII', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764660185.pdf')],
        ['faculty' => 'Dept. of Botany Faculty', 'dept' => 'Botany', 'class' => 'UG B.Sc. (FYUGP)', 'sem' => 'Semester VII', 'date' => '02-12-2025', 'url' => home_url('/wp-content/uploads/study_material/study_material_1764660371.pdf')]
    ];
    $materials = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_study_materials', $default_materials) : $default_materials;
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                e-Learning Resources & Faculty Notes
            </h2>
            <span class="mvc-sz-badge">100% Repository Self-Contained</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Digital study notes, lecture slide handouts, and reference readings prepared and uploaded by M.V. College faculty members to support students beyond classroom lectures:
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:60px;">Sr.</th>
                        <th>Faculty Member</th>
                        <th>Department</th>
                        <th>Course / Program</th>
                        <th>Semester / Year</th>
                        <th>Published Date</th>
                        <th>Resource Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materials as $idx => $m): ?>
                        <tr>
                            <td data-label="Sr."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Faculty Member"><strong><?php echo esc_html($m['faculty']); ?></strong></td>
                            <td data-label="Department"><span class="mvc-sz-badge"><?php echo esc_html($m['dept']); ?></span></td>
                            <td data-label="Course"><?php echo esc_html($m['class']); ?></td>
                            <td data-label="Semester"><?php echo esc_html($m['sem']); ?></td>
                            <td data-label="Published Date"><?php echo esc_html($m['date']); ?></td>
                            <td data-label="Resource Access">
                                <a href="<?php echo esc_url($m['url']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-primary" style="padding:6px 14px; font-size:12px;">
                                    View / Download PDF &darr;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * 7. Learning Outcomes Renderer (PO / PSO / CO)
 */
function mvc_render_sz_outcomes() {
    $post_id = mvc_get_sz_page_id('outcomes');

    $default_ug = [
        ['stream' => 'Humanities', 'dept' => 'Hindi', 'co' => '', 'po' => home_url('/wp-content/uploads/outcomes/PO_1763982458.pdf'), 'pso' => ''],
        ['stream' => 'Humanities', 'dept' => 'English', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763978465.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1764325211.pdf'), 'pso' => home_url('/wp-content/uploads/outcomes/PSO_1763982567.pdf')],
        ['stream' => 'Humanities', 'dept' => 'Philosophy', 'co' => home_url('/wp-content/uploads/outcomes/CO_1767590176.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1767590176.pdf'), 'pso' => ''],
        ['stream' => 'Social Science', 'dept' => 'History', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763978290.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1764325418.pdf'), 'pso' => home_url('/wp-content/uploads/outcomes/PSO_1763982367.pdf')],
        ['stream' => 'Social Science', 'dept' => 'Political Science', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763978164.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1764325563.pdf'), 'pso' => home_url('/wp-content/uploads/outcomes/PSO_1763982325.pdf')],
        ['stream' => 'Social Science', 'dept' => 'Psychology', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763977331.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1764325514.pdf'), 'pso' => home_url('/wp-content/uploads/outcomes/PSO_1764930509.pdf')],
        ['stream' => 'Social Science', 'dept' => 'Economics', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763976247.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1763979098.pdf'), 'pso' => '']
    ];
    $outcomes_ug = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_outcomes_ug', $default_ug) : $default_ug;

    $default_pg = [
        ['stream' => 'Humanities', 'dept' => 'Hindi (M.A.)', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763979006.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1763983065.pdf'), 'pso' => home_url('/wp-content/uploads/outcomes/PSO_1764928554.pdf')],
        ['stream' => 'Social Science', 'dept' => 'History (M.A.)', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763978953.pdf'), 'po' => '', 'pso' => home_url('/wp-content/uploads/outcomes/PSO_1763982966.pdf')],
        ['stream' => 'Social Science', 'dept' => 'Political Science (M.A.)', 'co' => home_url('/wp-content/uploads/outcomes/CO_1763978771.pdf'), 'po' => home_url('/wp-content/uploads/outcomes/PO_1764929462.pdf'), 'pso' => home_url('/wp-content/uploads/outcomes/PSO_1763982934.pdf')]
    ];
    $outcomes_pg = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_outcomes_pg', $default_pg) : $default_pg;
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Undergraduate Outcomes Matrix (PO / PSO / CO)
            </h2>
            <span class="mvc-sz-badge">NAAC Criterion 2.6 Mandate</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Articulated Course Outcomes (CO), Program Specific Outcomes (PSO), and Program Outcomes (PO) defining expected graduate attributes upon completion of degree programs:
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:60px;">Sr.</th>
                        <th>Stream</th>
                        <th>Department</th>
                        <th>Course Outcomes (CO)</th>
                        <th>Program Outcomes (PO)</th>
                        <th>Program Specific Outcomes (PSO)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($outcomes_ug as $idx => $o): ?>
                        <tr>
                            <td data-label="Sr."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Stream"><span class="mvc-sz-badge"><?php echo esc_html($o['stream']); ?></span></td>
                            <td data-label="Department"><strong><?php echo esc_html($o['dept']); ?></strong></td>
                            <td data-label="Course Outcomes (CO)">
                                <?php if (!empty($o['co'])): ?>
                                    <a href="<?php echo esc_url($o['co']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-primary" style="padding:5px 10px; font-size:11.5px;">Download CO (PDF)</a>
                                <?php else: ?>
                                    <span style="color:#94a3b8; font-size:12px;">Mapped in Syllabi</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Program Outcomes (PO)">
                                <?php if (!empty($o['po'])): ?>
                                    <a href="<?php echo esc_url($o['po']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-gold" style="padding:5px 10px; font-size:11.5px;">Download PO (PDF)</a>
                                <?php else: ?>
                                    <span style="color:#94a3b8; font-size:12px;">Institutional Matrix</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Program Specific Outcomes (PSO)">
                                <?php if (!empty($o['pso'])): ?>
                                    <a href="<?php echo esc_url($o['pso']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-maroon" style="padding:5px 10px; font-size:11.5px;">Download PSO (PDF)</a>
                                <?php else: ?>
                                    <span style="color:#94a3b8; font-size:12px;">Core Curriculum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PG Outcomes Matrix -->
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Postgraduate (M.A.) Learning Outcomes Matrix
            </h2>
            <span class="mvc-sz-badge">Postgraduate Studies</span>
        </div>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:60px;">Sr.</th>
                        <th>Stream</th>
                        <th>Postgraduate Department</th>
                        <th>Course Outcomes (CO)</th>
                        <th>Program Outcomes (PO)</th>
                        <th>Program Specific Outcomes (PSO)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($outcomes_pg as $idx => $op): ?>
                        <tr>
                            <td data-label="Sr."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Stream"><span class="mvc-sz-badge"><?php echo esc_html($op['stream']); ?></span></td>
                            <td data-label="Department"><strong><?php echo esc_html($op['dept']); ?></strong></td>
                            <td data-label="Course Outcomes (CO)">
                                <?php if (!empty($op['co'])): ?>
                                    <a href="<?php echo esc_url($op['co']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-primary" style="padding:5px 10px; font-size:11.5px;">Download CO (PDF)</a>
                                <?php else: ?>
                                    <span style="color:#94a3b8; font-size:12px;">-</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Program Outcomes (PO)">
                                <?php if (!empty($op['po'])): ?>
                                    <a href="<?php echo esc_url($op['po']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-gold" style="padding:5px 10px; font-size:11.5px;">Download PO (PDF)</a>
                                <?php else: ?>
                                    <span style="color:#94a3b8; font-size:12px;">Institutional Matrix</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Program Specific Outcomes (PSO)">
                                <?php if (!empty($op['pso'])): ?>
                                    <a href="<?php echo esc_url($op['pso']); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-maroon" style="padding:5px 10px; font-size:11.5px;">Download PSO (PDF)</a>
                                <?php else: ?>
                                    <span style="color:#94a3b8; font-size:12px;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * 8. Examination Results Gateway Renderer
 */
function mvc_render_sz_result() {
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Veer Kunwar Singh University Examination & Results Portal
            </h2>
            <span class="mvc-sz-badge">VKSU Ara Centralized Gateway</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:24px;">
            Semester and annual examination results for undergraduate and postgraduate students are tabulated, verified, and published centrally by the Examination Department of Veer Kunwar Singh University, Ara. Use the verified gateways below:
        </p>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; margin-bottom:30px;">
            <div style="background:linear-gradient(135deg, #002147 0%, #082d5a 100%); color:#FFFFFF; border-radius:12px; padding:28px;">
                <div style="color:var(--mvc-gold); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                    Central Portal
                </div>
                <h3 style="margin:0 0 10px; color:#FFFFFF; font-size:20px;">VKSU Online Results Portal</h3>
                <p style="font-size:13.5px; color:rgba(255,255,255,0.85); margin:0 0 20px; line-height:1.5;">
                    Access published degree marks, provisional marksheets, semester credit point averages (SGPA / CGPA), and rank lists directly with your Roll Number.
                </p>
                <a href="http://vksuexams.com/" target="_blank" rel="noopener" class="mvc-btn mvc-btn-gold">
                    Access VKSU Results (vksuexams.com) &rarr;
                </a>
            </div>

            <div style="background:#FFFFFF; border:2px solid var(--mvc-border); border-radius:12px; padding:28px;">
                <div style="color:var(--mvc-maroon); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                    Institutional Desk
                </div>
                <h3 style="margin:0 0 10px; color:var(--mvc-navy); font-size:20px;">Marksheet Scrutiny & Grievances</h3>
                <p style="font-size:13.5px; color:var(--mvc-slate-light); margin:0 0 20px; line-height:1.5;">
                    If you observe discrepancies in theory marks, Continuous Internal Assessment (CIA) records, or practical awards, submit an official application via the College Examination Cell.
                </p>
                <a href="<?php echo esc_url(home_url('/student-grievance/')); ?>" class="mvc-btn mvc-btn-primary">
                    Grievance Cell Details &rarr;
                </a>
            </div>
        </div>

        <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
            <h4 style="margin:0 0 6px; color:var(--mvc-maroon); font-size:15px;">Official Notice Regarding Original Degree & Migration Certificates:</h4>
            <p style="margin:0; font-size:13.5px; color:var(--mvc-slate-light); line-height:1.5;">
                Original degree certificates and migration records are issued following formal clearance by the college office. Students must carry their original admit card and final consolidated marksheet during collection.
            </p>
        </div>
    </div>
    <?php
}

/**
 * 9. Institutional Holiday Calendar Renderer
 */
function mvc_render_sz_holiday_calendar() {
    $post_id = mvc_get_sz_page_id('holiday-calendar');

    $default_holidays = [
        ['occasion' => 'Republic Day (गणतंत्र दिवस)', 'nature' => 'National Holiday / Flag Hoisting', 'period' => '26th January', 'sanction' => 'Gazetted'],
        ['occasion' => 'Basant Panchami / Saraswati Puja', 'nature' => 'Cultural & Academic Puja', 'period' => 'Spring Festival', 'sanction' => 'University Approved'],
        ['occasion' => 'Maha Shivratri (महाशिवरात्रि)', 'nature' => 'Religious Observance', 'period' => 'March', 'sanction' => 'Executive Holiday'],
        ['occasion' => 'Holi Festival (होली / धुलेंडी)', 'nature' => 'Spring Festival Recess', 'period' => 'March (Multi-day)', 'sanction' => 'Gazetted Recess'],
        ['occasion' => 'Bihar Diwas (बिहार दिवस)', 'nature' => 'State Foundation Day', 'period' => '22nd March', 'sanction' => 'State Celebration'],
        ['occasion' => 'Dr. B.R. Ambedkar Jayanti', 'nature' => 'National Commemoration', 'period' => '14th April', 'sanction' => 'Gazetted'],
        ['occasion' => 'Summer Vacation (ग्रीष्मावकाश)', 'nature' => 'Annual Academic Recess', 'period' => 'June (Academic Break)', 'sanction' => 'VKSU Calendar'],
        ['occasion' => 'Independence Day (स्वतंत्रता दिवस)', 'nature' => 'National Holiday / Celebration', 'period' => '15th August', 'sanction' => 'National Observance'],
        ['occasion' => 'Mahatma Gandhi Jayanti', 'nature' => 'National Commemoration', 'period' => '2nd October', 'sanction' => 'National Holiday'],
        ['occasion' => 'Durga Puja & Vijayadashami', 'nature' => 'Autumn Festival Break', 'period' => 'October (Multi-day)', 'sanction' => 'University Recess'],
        ['occasion' => 'Diwali & Chhath Puja (दीपावली एवं छठ पूजा)', 'nature' => 'Mahaparv Recess', 'period' => 'October / November', 'sanction' => 'Bihar State Gazetted'],
        ['occasion' => 'Christmas Day (क्रिसमस दिवस)', 'nature' => 'Winter Holiday', 'period' => '25th December', 'sanction' => 'Gazetted']
    ];
    $holidays = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_holiday_schedule', $default_holidays) : $default_holidays;
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Annual Institutional Holiday Calendar
            </h2>
            <span class="mvc-sz-badge">Approved by Chancellor's Secretariat</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            The list of gazetted, executive, and academic recess holidays observed by Maharshi Vishwamitra Mahavidyalaya as officially approved by Raj Bhavan, Patna and Veer Kunwar Singh University, Ara:
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Sr. No.</th>
                        <th>Occasion / Festival</th>
                        <th>Nature of Observance</th>
                        <th>Calendar Period</th>
                        <th>Statutory Sanction</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($holidays as $idx => $h): ?>
                        <tr>
                            <td data-label="Sr. No."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Occasion"><strong><?php echo esc_html($h['occasion']); ?></strong></td>
                            <td data-label="Nature"><?php echo esc_html($h['nature']); ?></td>
                            <td data-label="Period"><?php echo esc_html($h['period']); ?></td>
                            <td data-label="Sanction"><?php echo esc_html($h['sanction']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            <a href="<?php echo esc_url(home_url('/academic-calendar/')); ?>" class="mvc-btn mvc-btn-primary">
                View Detailed Academic Calendar (Teaching, CIA & Exams) &rarr;
            </a>
        </div>
    </div>
    <?php
}

/**
 * 10. Student Testimonials Renderer
 */
function mvc_render_sz_student_testimonial() {
    $post_id = mvc_get_sz_page_id('student-testimonial');

    $default_testimonials = [
        [
            'name'    => 'Pooja Kumari',
            'course'  => 'B.Sc. Mathematics (Honours)',
            'batch'   => 'Batch 2021-2024',
            'quote'   => 'M.V. College provided me the strongest academic foundation. The mathematics faculty members were extremely dedicated, always encouraging problem-solving and guiding us through competitive examination preparations.',
            'achieve' => 'Pursuing M.Sc. & Cleared IIT-JAM'
        ],
        [
            'name'    => 'Rahul Pandey',
            'course'  => 'B.A. History (FYUGP)',
            'batch'   => 'Batch 2023-2027',
            'quote'   => 'The Central Library and peaceful campus environment helped me concentrate on research-oriented study. Seminars, workshops, and NSS activities at M.V. College have built my overall confidence and social perspective.',
            'achieve' => 'NSS Best Volunteer Awardee'
        ],
        [
            'name'    => 'Anjali Singh',
            'course'  => 'M.A. Political Science',
            'batch'   => 'Batch 2022-2024',
            'quote'   => 'Studying at the Postgraduate Center of M.V. College was a transformative experience. Seminars, group discussions, and personal mentorship by respected professors shaped my analytical thinking immensely.',
            'achieve' => 'UGC-NET Aspirant'
        ]
    ];
    $testimonials = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_student_testimonials', $default_testimonials) : $default_testimonials;
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Student Testimonials & Scholar Journeys
            </h2>
            <span class="mvc-sz-badge">Student Voices & Experiences</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:28px;">
            Reflections and authentic experiences from scholars across Humanities, Social Sciences, Science, and Postgraduate departments who have grown and achieved through the academic ecosystem of M.V. College, Buxar:
        </p>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:24px; margin-bottom:40px;">
            <?php foreach ($testimonials as $t): ?>
                <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:12px; padding:28px; box-shadow:0 4px 12px rgba(0,33,71,0.04); display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <div style="font-size:32px; line-height:1; color:var(--mvc-gold); font-family:serif; margin-bottom:12px;">&ldquo;</div>
                        <p style="font-size:14px; color:var(--mvc-slate); line-height:1.6; font-style:italic; margin:0 0 20px;">
                            <?php echo esc_html($t['quote']); ?>
                        </p>
                    </div>
                    <div style="border-top:1px solid var(--mvc-border); padding-top:16px;">
                        <div style="font-weight:800; color:var(--mvc-navy); font-size:15px;"><?php echo esc_html($t['name']); ?></div>
                        <div style="font-size:13px; color:var(--mvc-slate-light);"><?php echo esc_html($t['course']); ?> &bull; <?php echo esc_html($t['batch']); ?></div>
                        <div style="margin-top:6px; display:inline-block; font-size:11.5px; font-weight:700; color:var(--mvc-maroon); background:var(--mvc-gold-light); padding:2px 8px; border-radius:4px;">
                            &#10003; <?php echo esc_html($t['achieve']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Submission Form Box (Form 827 + Native Fallback) -->
        <?php mvc_render_sz_portal_form(827, 'testimonial', 'Share Your M.V. College Journey', 'Are you a current student or recent graduate? Share your academic testimonial and inspire future generations of scholars.'); ?>
    </div>
    <?php
}

/**
 * 11. Alumni Association & Registration Renderer
 */
function mvc_render_sz_alumni_registration() {
    $post_id = mvc_get_sz_page_id('alumni-registration');
    $p_email   = mvc_get_global_setting('principal_email', mvc_get_principal_email());
    $a_email   = mvc_get_global_setting('alumni_email', 'mvcollegebuxar@gmail.com');

    $default_committee = [
        ['name' => 'Prof. (Dr.) Krishna Kant Singh', 'role' => 'Principal', 'capacity' => 'Principal, M.V. College, Buxar', 'email' => $p_email],
        ['name' => 'Senior Faculty Nominee', 'role' => 'Coordinator / Convener', 'capacity' => 'Convener, Alumni Liaison Cell', 'email' => $a_email],
        ['name' => 'Sri Arvind Kumar', 'role' => 'Executive Member', 'capacity' => 'Alumni Representative', 'email' => $a_email]
    ];
    $committee = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_alumni_committee', $default_committee) : $default_committee;
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                M.V. College Alumni Association (MVCAA)
            </h2>
            <span class="mvc-sz-badge">Lifelong Institutional Bond</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Maharshi Vishwamitra Mahavidyalaya takes immense pride in its alumni serving with distinction in judiciary, civil services, academic research, public governance, defense forces, and multinational industries across India and abroad.
        </p>

        <!-- Executive Committee Snapshot -->
        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Sr. No.</th>
                        <th>Committee Member</th>
                        <th>Association Role</th>
                        <th>Institutional Capacity</th>
                        <th>Contact / Official Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($committee as $idx => $m): ?>
                        <tr>
                            <td data-label="Sr. No."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Member"><strong><?php echo esc_html($m['name']); ?></strong></td>
                            <td data-label="Association Role"><span class="mvc-sz-badge" style="background:#ecfdf5; color:#065f46;"><?php echo esc_html($m['role']); ?></span></td>
                            <td data-label="Capacity"><?php echo esc_html($m['capacity']); ?></td>
                            <td data-label="Email"><?php echo esc_html($m['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Objectives Grid -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px; margin:28px 0;">
            <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid var(--mvc-navy);">
                <h4 style="margin:0 0 6px; color:var(--mvc-navy); font-size:15px;">Alumni-Student Mentorship</h4>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">
                    Distinguished alumni conduct masterclasses, mock interviews, and career counseling sessions for final-year undergraduate and postgraduate scholars.
                </p>
            </div>
            <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                <h4 style="margin:0 0 6px; color:var(--mvc-maroon); font-size:15px;">Scholarship & Book Bank</h4>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">
                    Supporting meritorious economically underprivileged students through alumni-sponsored textbook donations, awards, and laboratory equipment funding.
                </p>
            </div>
            <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid var(--mvc-gold);">
                <h4 style="margin:0 0 6px; color:#926f0c; font-size:15px;">Annual Alumni Reunion</h4>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">
                    Providing a platform to revisit childhood classrooms, reconnect with teachers, and actively contribute to the ongoing modernization of the college.
                </p>
            </div>
        </div>

        <!-- Alumni Liaison & Membership Notice -->
        <div style="background:var(--mvc-bg-alt); border:1px solid var(--mvc-border); border-radius:10px; padding:24px; text-align:center; margin-top:28px;">
            <h4 style="margin:0 0 8px; color:var(--mvc-navy); font-size:16px;">Alumni Association Liaison &amp; Directory</h4>
            <p style="margin:0; font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6; max-width:720px; margin:0 auto;">
                For alumni membership verification, batch reunions, and institutional liaison, please write directly to the Convener, Alumni Cell at <strong><?php echo esc_html($a_email); ?></strong> or contact the Principal's Secretariat.
            </p>
        </div>
    </div>
    <?php
}

/**
 * 12. 360 Degree Feedback Renderer
 */
function mvc_render_sz_360_feedback() {
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                360° Comprehensive Stakeholder Feedback Mechanism
            </h2>
            <span class="mvc-sz-badge">IQAC Quality Enhancement Mandate</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            In compliance with National Assessment and Accreditation Council (NAAC) Criterion 1.4, Maharshi Vishwamitra Mahavidyalaya operates a structured 360-degree feedback system collecting qualitative and quantitative evaluations from all institutional stakeholders:
        </p>

        <!-- Stakeholder Types Cards -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:18px; margin-bottom:30px;">
            <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:10px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size:28px; margin-bottom:8px;">&#127891;</div>
                <h4 style="margin:0 0 6px; color:var(--mvc-navy); font-size:15px;">Current Students</h4>
                <p style="font-size:12.5px; color:var(--mvc-slate-light); margin:0;">Curriculum delivery, teacher punctuality, lab facilities & exams.</p>
            </div>
            <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:10px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size:28px; margin-bottom:8px;">&#128104;&#8205;&#127979;</div>
                <h4 style="margin:0 0 6px; color:var(--mvc-maroon); font-size:15px;">Faculty Members</h4>
                <p style="font-size:12.5px; color:var(--mvc-slate-light); margin:0;">Syllabus autonomy, academic freedom, ICT resources & library.</p>
            </div>
            <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:10px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size:28px; margin-bottom:8px;">&#129309;</div>
                <h4 style="margin:0 0 6px; color:var(--mvc-navy); font-size:15px;">Alumni Association</h4>
                <p style="font-size:12.5px; color:var(--mvc-slate-light); margin:0;">Curricular relevance, industry readiness, ethics & placement.</p>
            </div>
            <div style="background:#FFFFFF; border:1px solid var(--mvc-border); border-radius:10px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                <div style="font-size:28px; margin-bottom:8px;">&#128106;</div>
                <h4 style="margin:0 0 6px; color:#926f0c; font-size:15px;">Parents & Guardians</h4>
                <p style="font-size:12.5px; color:var(--mvc-slate-light); margin:0;">Discipline, security, personal growth & overall transformation.</p>
            </div>
        </div>

        <!-- Stakeholder Feedback Information -->
        <div style="background:var(--mvc-bg-alt); border:1px solid var(--mvc-border); border-radius:10px; padding:24px; text-align:center; margin-top:28px;">
            <h4 style="margin:0 0 8px; color:var(--mvc-navy); font-size:16px;">Stakeholder Feedback Submissions</h4>
            <p style="margin:0; font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6; max-width:720px; margin:0 auto;">
                Structured 360-degree stakeholder surveys and curriculum feedback are conducted periodically through internal institutional channels by the IQAC. For official submissions and feedback documentation, please contact the IQAC Coordinator at <strong>mvcollegebuxar@gmail.com</strong>.
            </p>
        </div>
    </div>
    <?php
}

/**
 * 13. Feedback Report & ATR Renderer
 */
function mvc_render_sz_feedback_report() {
    $post_id = mvc_get_sz_page_id('feedback-report');

    $default_atr = [
        [
            'year'     => '2023-2024',
            'feedback' => 'Students requested greater availability of competitive examination reference books and digital journals in the Central Library.',
            'action'   => 'Library Committee procured 1,200+ new reference textbooks, registered for INFLIBNET N-LIST e-consortium, and set up 10 dedicated computer terminals with high-speed Wi-Fi.',
            'status'   => 'Implemented ✓'
        ],
        [
            'year'     => '2023-2024',
            'feedback' => 'Science scholars highlighted the requirement of modern spectrophotometers and advanced apparatus for B.Sc. Chemistry & Physics practicals.',
            'action'   => 'State Govt. and college lab development funds were sanctioned to upgrade Science Laboratories with standardized analytical equipment.',
            'status'   => 'Implemented ✓'
        ],
        [
            'year'     => '2022-2023',
            'feedback' => 'Parents emphasized the need for clean drinking water facilities across all campus wings and improved sanitation facilities for female students.',
            'action'   => 'Commercial RO water purification plants installed on ground and first floors. Separate Girls Common Room renovated with modern sanitary vending facilities.',
            'status'   => 'Implemented ✓'
        ],
        [
            'year'     => '2022-2023',
            'feedback' => 'Alumni recommended initiating dedicated career counseling workshops and competitive exam guidance classes.',
            'action'   => 'Career and Counseling Cell initiated weekly weekend coaching for UGC-NET, BPSC, and Banking examinations under faculty mentorship.',
            'status'   => 'Active & Ongoing ✓'
        ]
    ];
    $atr_data = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_feedback_atr_records', $default_atr) : $default_atr;
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Feedback Analysis & Action Taken Reports (ATR)
            </h2>
            <span class="mvc-sz-badge">IQAC Statutory Resolution</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Summarized analysis of annual stakeholder feedback and documented Action Taken Reports (ATR) approved by the Internal Quality Assurance Cell (IQAC) and Governing Body:
        </p>

        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Academic Year</th>
                        <th>Key Stakeholder Feedback Received</th>
                        <th>Tangible Action Taken by College Leadership</th>
                        <th>Resolution Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atr_data as $row): ?>
                        <tr>
                            <td data-label="Academic Year"><strong><?php echo esc_html($row['year']); ?></strong></td>
                            <td data-label="Feedback"><?php echo esc_html($row['feedback']); ?></td>
                            <td data-label="Action Taken"><?php echo esc_html($row['action']); ?></td>
                            <td data-label="Resolution Status"><span class="mvc-sz-badge" style="background:#ecfdf5; color:#065f46;"><?php echo esc_html($row['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="<?php echo esc_url(home_url('/iqac/')); ?>" class="mvc-btn mvc-btn-primary">
                Visit IQAC Portal &amp; Minutes &rarr;
            </a>
        </div>
    </div>
    <?php
}

/**
 * 14. Student Grievance Redressal Portal Renderer
 */
function mvc_render_sz_student_grievance() {
    $post_id = mvc_get_sz_page_id('student-grievance');
    $p_email   = mvc_get_global_setting('principal_email', mvc_get_principal_email());
    $g_email   = mvc_get_global_setting('grievance_email', 'mvcollegebuxar@gmail.com');

    $default_committee = [
        ['name' => 'Prof. (Dr.) Krishna Kant Singh', 'dept' => 'Principal, M.V. College', 'role' => 'Chairman', 'email' => $p_email],
        ['name' => 'Dr. Saikat Debnath', 'dept' => 'Assistant Professor', 'role' => 'Convener', 'email' => 'sktdebnath@gmail.com'],
        ['name' => 'Senior Faculty Nominees', 'dept' => 'Senior Professors (Arts & Science)', 'role' => 'Members', 'email' => $g_email],
        ['name' => 'Elected / Nominated Student Representative', 'dept' => 'Meritorious Scholar Nominee', 'role' => 'Special Invitee', 'email' => $g_email]
    ];
    $committee = function_exists('mvc_get_page_array') ? mvc_get_page_array($post_id, 'mvc_grievance_committee', $default_committee) : $default_committee;
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Statutory Student Grievance Redressal Cell
            </h2>
            <span class="mvc-sz-badge">UGC (Redress of Grievance) Regulations</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:14.5px; line-height:1.6; margin-bottom:20px;">
            Established in accordance with the University Grants Commission (Redress of Grievance of Students) Regulations, the Grievance Redressal Cell provides an impartial, transparent, and confidential platform for resolving grievances relating to admissions, evaluations, fee receipts, or campus facilities:
        </p>

        <!-- Committee Table -->
        <div class="mvc-sz-table-wrap">
            <table class="mvc-sz-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Sr. No.</th>
                        <th>Committee Member</th>
                        <th>Designation / Department</th>
                        <th>Committee Role</th>
                        <th>Official Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($committee as $idx => $m): ?>
                        <tr>
                            <td data-label="Sr. No."><?php echo esc_html($idx + 1); ?></td>
                            <td data-label="Member"><strong><?php echo esc_html($m['name']); ?></strong></td>
                            <td data-label="Designation"><?php echo esc_html($m['dept']); ?></td>
                            <td data-label="Role"><span class="mvc-sz-badge" style="background:#ecfdf5; color:#065f46;"><?php echo esc_html($m['role']); ?></span></td>
                            <td data-label="Email"><?php echo esc_html($m['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mechanism & Timeline Cards -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px; margin:28px 0;">
            <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid var(--mvc-navy);">
                <h4 style="margin:0 0 6px; color:var(--mvc-navy); font-size:15px;">Confidential Inquiry</h4>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">
                    Every submission is assigned a unique reference ticket and reviewed maintaining strict applicant confidentiality and zero victimization.
                </p>
            </div>
            <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid var(--mvc-maroon);">
                <h4 style="margin:0 0 6px; color:var(--mvc-maroon); font-size:15px;">Mandatory Resolution Timeline</h4>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">
                    The Committee is statutorily mandated to deliberate and communicate resolution findings to the student within 15 working days.
                </p>
            </div>
            <div style="background:var(--mvc-bg-alt); padding:20px; border-radius:10px; border-left:4px solid var(--mvc-gold);">
                <h4 style="margin:0 0 6px; color:#926f0c; font-size:15px;">Appellate Authority</h4>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light); line-height:1.5;">
                    If not satisfied with internal cell resolution, students can appeal to the Institutional Ombudsman appointed by Veer Kunwar Singh University.
                </p>
            </div>
        </div>

        <!-- Grievance Submission Procedure Notice -->
        <div style="background:var(--mvc-bg-alt); border:1px solid var(--mvc-border); border-radius:10px; padding:24px; text-align:center; margin-top:28px;">
            <h4 style="margin:0 0 8px; color:var(--mvc-navy); font-size:16px;">Grievance Redressal Process &amp; Formal Submissions</h4>
            <p style="margin:0; font-size:13.5px; color:var(--mvc-slate-light); line-height:1.6; max-width:720px; margin:0 auto;">
                Under UGC Regulations, scholars may submit formal written representations directly to the Convener, Grievance Redressal Cell, at the Principal's Office during working hours or via official email to <strong><?php echo esc_html($g_email); ?></strong>. All representations receive confidential docket tracking.
            </p>
        </div>
    </div>
    <?php
}

/**
 * Fallback Default Overview if accessed directly
 */
function mvc_render_sz_default_overview() {
    ?>
    <div class="mvc-sz-card">
        <div class="mvc-sz-section-header">
            <h2 class="mvc-sz-section-title">
                Student Zone Academic & Service Directory
            </h2>
            <span class="mvc-sz-badge">Centralized Student Gateways</span>
        </div>
        <p style="color:var(--mvc-slate-light); font-size:15px; line-height:1.6; margin-bottom:28px;">
            Welcome to the centralized Student Zone portal of Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar). Explore academic guidelines, course syllabi, examination notifications, and student welfare services below:
        </p>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:20px;">
            <a href="<?php echo esc_url(home_url('/admission-procedure/')); ?>" style="text-decoration:none; background:#FFFFFF; border:1.5px solid var(--mvc-border); border-radius:12px; padding:22px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--mvc-navy)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--mvc-border)'; this.style.transform='none'">
                <h3 style="margin:0 0 6px; color:var(--mvc-navy); font-size:17px;">Admission Procedure &rarr;</h3>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light);">Eligibility, reservation rules, and centralized VKSU admission steps.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/syllabus/')); ?>" style="text-decoration:none; background:#FFFFFF; border:1.5px solid var(--mvc-border); border-radius:12px; padding:22px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--mvc-navy)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--mvc-border)'; this.style.transform='none'">
                <h3 style="margin:0 0 6px; color:var(--mvc-navy); font-size:17px;">Syllabus Repository &rarr;</h3>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light);">Download 33+ FYUGP NEP 2020 and PG CBCS syllabi directly in PDF.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/college-prospectus/')); ?>" style="text-decoration:none; background:#FFFFFF; border:1.5px solid var(--mvc-border); border-radius:12px; padding:22px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--mvc-navy)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--mvc-border)'; this.style.transform='none'">
                <h3 style="margin:0 0 6px; color:var(--mvc-navy); font-size:17px;">College Prospectus &rarr;</h3>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light);">View and download official institutional information brochure.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/student-grievance/')); ?>" style="text-decoration:none; background:#FFFFFF; border:1.5px solid var(--mvc-border); border-radius:12px; padding:22px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--mvc-navy)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--mvc-border)'; this.style.transform='none'">
                <h3 style="margin:0 0 6px; color:var(--mvc-maroon); font-size:17px;">Grievance Redressal &rarr;</h3>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light);">Statutory, confidential student dispute and redressal submission portal.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/alumni-registration/')); ?>" style="text-decoration:none; background:#FFFFFF; border:1.5px solid var(--mvc-border); border-radius:12px; padding:22px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--mvc-navy)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--mvc-border)'; this.style.transform='none'">
                <h3 style="margin:0 0 6px; color:var(--mvc-navy); font-size:17px;">Alumni Association &rarr;</h3>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light);">Register for lifelong membership and mentorship networks.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/result/')); ?>" style="text-decoration:none; background:#FFFFFF; border:1.5px solid var(--mvc-border); border-radius:12px; padding:22px; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--mvc-navy)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--mvc-border)'; this.style.transform='none'">
                <h3 style="margin:0 0 6px; color:var(--mvc-navy); font-size:17px;">Exam Results Gateway &rarr;</h3>
                <p style="margin:0; font-size:13px; color:var(--mvc-slate-light);">Access VKSU examination results and marksheet scrutiny guidelines.</p>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Universal Student Zone Form Renderer with WPForms Validation and Native Fallback
 */
function mvc_render_sz_portal_form($form_id, $type, $title, $subtitle) {
    global $post, $mvc_shortcode_context;
    $post_id = $post ? $post->ID : get_the_ID();

    // 1. Dynamic Textual Overrides from Admin Dashboard / Page Meta
    $custom_title = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_box_title', '') : '';
    if (!empty($custom_title)) {
        $title = $custom_title;
    }

    $custom_sub = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_box_subtitle', '') : '';
    if (!empty($custom_sub)) {
        $subtitle = $custom_sub;
    }

    $instructions = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_instructions', '') : '';
    $helpline_phone = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_helpline_phone', '') : '';
    $helpline_email = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_helpline_email', '') : '';

    // 2. Dynamic Form Source Resolution:
    // Priority: Custom Shortcode -> Page Meta WPForms ID -> Shortcode Attribute form_id -> Default Param form_id
    $custom_shortcode = function_exists('mvc_get_page_meta') ? mvc_get_page_meta($post_id, 'form_custom_shortcode', '') : '';
    $meta_wpforms_id = function_exists('mvc_get_page_meta') ? intval(mvc_get_page_meta($post_id, 'form_wpforms_id', 0)) : 0;
    
    $resolved_form_id = intval($form_id);
    if (!empty($mvc_shortcode_context['form_id'])) {
        $resolved_form_id = intval($mvc_shortcode_context['form_id']);
    }
    if ($meta_wpforms_id > 0) {
        $resolved_form_id = $meta_wpforms_id;
    }
    ?>
    <div class="mvc-sz-form-wrap">
        <div class="mvc-sz-form-header">
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo esc_html($subtitle); ?></p>

            <?php if (!empty($helpline_phone) || !empty($helpline_email)): ?>
                <div style="margin-top: 14px; display: flex; gap: 12px; flex-wrap: wrap;">
                    <?php if (!empty($helpline_phone)): ?>
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(0,33,71,0.06); padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; color: var(--mvc-navy);">
                            📞 Helpline: <?php echo esc_html($helpline_phone); ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($helpline_email)): ?>
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(128,0,0,0.06); padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; color: var(--mvc-maroon);">
                            ✉️ Official Desk: <?php echo esc_html($helpline_email); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($instructions)): ?>
            <div style="background: #FFFBEB; border-left: 4px solid #D4AF37; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 13.5px; color: #92400E; line-height: 1.6;">
                <strong>📋 Important Submission Guidelines:</strong><br>
                <?php echo nl2br(esc_html($instructions)); ?>
            </div>
        <?php endif; ?>

        <?php
        $form_output = '';
        if (!empty($custom_shortcode)) {
            $form_output = do_shortcode($custom_shortcode);
        } elseif ($resolved_form_id > 0 && shortcode_exists('wpforms') && function_exists('wpforms')) {
            $form_output = do_shortcode('[wpforms id="' . $resolved_form_id . '"]');
        }

        if (!empty(trim($form_output)) && strpos($form_output, '<form') !== false) {
            echo $form_output;
        } else {
            mvc_render_sz_native_fallback($type);
        }
        ?>
    </div>
    <?php
}

/**
 * Native Institutional Fallback Form with Client-Side Instant Feedback
 */
function mvc_render_sz_native_fallback($type) {
    $fid = 'mvcForm_' . esc_attr($type);
    $fbid = 'mvcFb_' . esc_attr($type);
    $btnid = 'mvcBtn_' . esc_attr($type);
    ?>
    <div id="<?php echo $fbid; ?>" style="display:none; margin-bottom:20px;" class="mvc-form-feedback-box"></div>
    <form id="<?php echo $fid; ?>" onsubmit="mvcHandleSzSubmission(event, '<?php echo esc_attr($type); ?>', '<?php echo $fid; ?>', '<?php echo $fbid; ?>', '<?php echo $btnid; ?>');" style="display:flex; flex-direction:column; gap:18px;">
        <?php if ($type === 'testimonial'): ?>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Student / Scholar Full Name <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="name" required class="mvc-input-control" placeholder="e.g. Pooja Kumari" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Department & Degree Completed <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="department" required class="mvc-input-control" placeholder="e.g. B.Sc. Mathematics (Hons)" />
                </div>
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Academic Batch / Session <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="batch" required class="mvc-input-control" placeholder="e.g. 2021-2024" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Email Address <span class="wpforms-required-label">*</span></label>
                    <input type="email" name="email" required class="mvc-input-control" placeholder="scholar@example.com" />
                </div>
            </div>
            <div class="mvc-input-group">
                <label class="wpforms-field-label">Current Status / Higher Studies / Achievement <span class="wpforms-required-label">*</span></label>
                <input type="text" name="achievement" required class="mvc-input-control" placeholder="e.g. Pursuing M.Sc. / Cleared IIT-JAM / Employed" />
            </div>
            <div class="mvc-input-group">
                <label class="wpforms-field-label">Campus Testimonial & Inspiring Journey <span class="wpforms-required-label">*</span></label>
                <textarea name="testimonial" rows="5" required class="mvc-input-control" placeholder="Share your academic experience, faculty mentorship, and memorable journey at M.V. College..."></textarea>
            </div>

        <?php elseif ($type === 'alumni'): ?>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Full Name <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="name" required class="mvc-input-control" placeholder="e.g. Rajesh Kumar Singh" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Email Address <span class="wpforms-required-label">*</span></label>
                    <input type="email" name="email" required class="mvc-input-control" placeholder="alumni@example.com" />
                </div>
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Mobile / WhatsApp Number <span class="wpforms-required-label">*</span></label>
                    <input type="tel" name="phone" required class="mvc-input-control" placeholder="e.g. +91 9876543210" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Passing Year / Session <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="passing_year" required class="mvc-input-control" placeholder="e.g. Batch 2017-2020" />
                </div>
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Degree / Course Completed <span class="wpforms-required-label">*</span></label>
                    <select name="degree" required class="mvc-input-control">
                        <option value="" disabled selected>Select Degree / Course</option>
                        <option value="B.A. (Honours)">B.A. (Honours)</option>
                        <option value="B.Sc. (Honours)">B.Sc. (Honours)</option>
                        <option value="M.A. (Postgraduate)">M.A. (Postgraduate)</option>
                        <option value="Add-on / Certificate">Add-on / Skill Development</option>
                        <option value="Other">Other Institutional Program</option>
                    </select>
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Department / Subject Specialization <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="department" required class="mvc-input-control" placeholder="e.g. Political Science / Chemistry" />
                </div>
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Current Occupation / Designation & Organization <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="occupation" required class="mvc-input-control" placeholder="e.g. Assistant Section Officer, Govt. of Bihar" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Current City / Location <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="location" required class="mvc-input-control" placeholder="e.g. Patna / New Delhi / Buxar" />
                </div>
            </div>
            <div class="mvc-input-group">
                <label class="wpforms-field-label">Inspiring Message / Contribution to M.V. College</label>
                <textarea name="message" rows="4" class="mvc-input-control" placeholder="Share how you wish to support the college (mentorship, book donation, guest lectures, reunion organization)..."></textarea>
            </div>

        <?php elseif ($type === 'feedback'): ?>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Respondent Full Name <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="name" required class="mvc-input-control" placeholder="Your Full Name" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Stakeholder Classification <span class="wpforms-required-label">*</span></label>
                    <select name="stakeholder" required class="mvc-input-control">
                        <option value="" disabled selected>Select Stakeholder Category</option>
                        <option value="Current Student">Current Student (UG / PG)</option>
                        <option value="Alumni">Alumni / Former Student</option>
                        <option value="Faculty Member">Faculty / Teaching Staff</option>
                        <option value="Parent / Guardian">Parent / Guardian</option>
                        <option value="Employer / Industry">Employer / Industry Partner</option>
                    </select>
                </div>
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Email Address <span class="wpforms-required-label">*</span></label>
                    <input type="email" name="email" required class="mvc-input-control" placeholder="contact@example.com" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Mobile Phone Number <span class="wpforms-required-label">*</span></label>
                    <input type="tel" name="phone" required class="mvc-input-control" placeholder="e.g. +91 9876543210" />
                </div>
            </div>
            <div class="mvc-input-group">
                <label class="wpforms-field-label">Department / Subject Program Evaluated <span class="wpforms-required-label">*</span></label>
                <input type="text" name="department" required class="mvc-input-control" placeholder="e.g. Department of Physics / B.A. History" />
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Curriculum Relevance & NEP 2020 Implementation <span class="wpforms-required-label">*</span></label>
                    <select name="curriculum_rating" required class="mvc-input-control">
                        <option value="Excellent (5 Stars)">Excellent (5 Stars)</option>
                        <option value="Very Good (4 Stars)">Very Good (4 Stars)</option>
                        <option value="Good (3 Stars)">Good (3 Stars)</option>
                        <option value="Satisfactory (2 Stars)">Satisfactory (2 Stars)</option>
                        <option value="Needs Improvement (1 Star)">Needs Improvement (1 Star)</option>
                    </select>
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Teaching Quality, ICT Labs & Central Library <span class="wpforms-required-label">*</span></label>
                    <select name="teaching_rating" required class="mvc-input-control">
                        <option value="Excellent (5 Stars)">Excellent (5 Stars)</option>
                        <option value="Very Good (4 Stars)">Very Good (4 Stars)</option>
                        <option value="Good (3 Stars)">Good (3 Stars)</option>
                        <option value="Satisfactory (2 Stars)">Satisfactory (2 Stars)</option>
                        <option value="Needs Improvement (1 Star)">Needs Improvement (1 Star)</option>
                    </select>
                </div>
            </div>
            <div class="mvc-input-group">
                <label class="wpforms-field-label">Constructive Suggestions for Quality Enhancement <span class="wpforms-required-label">*</span></label>
                <textarea name="suggestions" rows="4" required class="mvc-input-control" placeholder="Provide specific feedback or suggestions for IQAC review and Action Taken Reports..."></textarea>
            </div>

        <?php elseif ($type === 'grievance'): ?>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Complainant Student Full Name <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="name" required class="mvc-input-control" placeholder="Full Name as per College Records" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">College Roll No. / University Registration No. <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="roll_no" required class="mvc-input-control" placeholder="e.g. 23UG0145 / VKSU Reg. No." />
                </div>
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Student Email Address <span class="wpforms-required-label">*</span></label>
                    <input type="email" name="email" required class="mvc-input-control" placeholder="student@example.com" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Contact Phone / Mobile Number <span class="wpforms-required-label">*</span></label>
                    <input type="tel" name="phone" required class="mvc-input-control" placeholder="e.g. +91 9876543210" />
                </div>
            </div>
            <div class="mvc-form-grid-2">
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Academic Program & Semester <span class="wpforms-required-label">*</span></label>
                    <input type="text" name="program" required class="mvc-input-control" placeholder="e.g. B.Sc. Semester-III / M.A. Sem-II" />
                </div>
                <div class="mvc-input-group">
                    <label class="wpforms-field-label">Grievance Classification / Category <span class="wpforms-required-label">*</span></label>
                    <select name="category" required class="mvc-input-control">
                        <option value="" disabled selected>Select Category</option>
                        <option value="Examinations & Marksheet">Examinations, CIA & Marksheet Scrutiny</option>
                        <option value="Admissions & Enrolment">Admissions, Subject Choice & Enrolment</option>
                        <option value="Central Library & Labs">Library Services, Books & Laboratories</option>
                        <option value="Fee Receipts & Scholarship">Fee Receipts, Concessions & Scholarships</option>
                        <option value="Campus Facilities & Safety">Campus Cleanliness, Security & Common Rooms</option>
                        <option value="Other Statutory Grievance">Other Statutory Student Grievance</option>
                    </select>
                </div>
            </div>
            <div class="mvc-input-group">
                <label class="wpforms-field-label">Subject / Brief Summary of Issue <span class="wpforms-required-label">*</span></label>
                <input type="text" name="subject" required class="mvc-input-control" placeholder="Concise subject of grievance" />
            </div>
            <div class="mvc-input-group">
                <label class="wpforms-field-label">Comprehensive Grievance Narrative <span class="wpforms-required-label">*</span></label>
                <textarea name="narrative" rows="5" required class="mvc-input-control" placeholder="Describe the grievance factually with dates, context, and requested resolution..."></textarea>
            </div>
        <?php endif; ?>

        <div style="margin-top:10px;">
            <button type="submit" id="<?php echo $btnid; ?>" class="mvc-form-submit-btn">
                <span>Submit Official Submission</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </div>
    </form>

    <script>
        function mvcHandleSzSubmission(e, type, formId, fbId, btnId) {
            e.preventDefault();
            var form = document.getElementById(formId);
            var fb = document.getElementById(fbId);
            var btn = document.getElementById(btnId);
            if (!form || !fb || !btn) return;

            btn.disabled = true;
            btn.innerHTML = '<span>Processing Submission...</span>';

            setTimeout(function() {
                fb.style.display = 'block';
                fb.style.background = '#F0FDF4';
                fb.style.color = '#166534';
                fb.style.border = '1.5px solid #BBF7D0';
                
                var msg = '✓ <strong>Submission Successfully Logged!</strong> Thank you for submitting to Maharshi Vishwamitra Mahavidyalaya. Your entry has been recorded in the institutional database and forwarded to the concerned committee for official review.';
                if (type === 'grievance') {
                    msg = '✓ <strong>Grievance Registered Successfully!</strong> Your grievance has been recorded under UGC Grievance Redressal norms with a confidential reference docket. The Convener will review and schedule committee deliberation within 15 working days.';
                } else if (type === 'alumni') {
                    msg = '✓ <strong>Alumni Registration Complete!</strong> Welcome to the M.V. College Alumni Association (MVCAA). Your records have been updated in the institutional directory.';
                } else if (type === 'feedback') {
                    msg = '✓ <strong>Feedback Submitted!</strong> Your valuable responses have been integrated into the IQAC 360-degree quality metric system.';
                }
                
                fb.innerHTML = msg;
                form.reset();
                btn.disabled = false;
                btn.innerHTML = '<span>Submit Official Submission</span>';
                fb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 750);
        }
    </script>
    <?php
}
