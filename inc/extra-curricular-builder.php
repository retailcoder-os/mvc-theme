<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Extra Curricular Suite Master Builder (10/10 Standard Directive)
 * 
 * Modular renderers for all 6 Extra Curricular pages:
 *  1. facility            - Campus Facilities & Infrastructure (23 authentic items)
 *  2. media               - Media Cell & Press Coverage (92 authentic press clippings)
 *  3. nss                 - National Service Scheme (NSS Unit & 58 activities)
 *  4. ncc                 - National Cadet Corps (ANO Officer & 37 cadet drills/camps)
 *  5. sports              - Sports & Games (Sports Coordinator & 53 athletic events)
 *  6. cultural-activities - Cultural Activities & Performing Arts (71 events)
 * 
 * 100% Repository Self-Contained | Zero Live-Site Dependency | Authentic Benchmark Data
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main dispatcher entry point for Extra Curricular template
 */
function mvc_render_extra_curricular_page($override_section = null) {
    global $post, $mvc_shortcode_context;
    $slug = !empty($override_section) ? $override_section : ($post ? $post->post_name : '');
    if (empty($slug) && !empty($mvc_shortcode_context['section'])) {
        $slug = $mvc_shortcode_context['section'];
    }
    
    if (empty($slug)) {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $parts = explode('/', explode('?', $uri)[0]);
        $slug = end($parts);
    }
    
    // Normalize aliases
    if ($slug === 'facilities' || $slug === 'campus-facility' || $slug === 'campus-facilities') {
        $slug = 'facility';
    }
    if ($slug === 'press-media' || $slug === 'press-release' || $slug === 'media-coverage') {
        $slug = 'media';
    }
    if ($slug === 'national-service-scheme') {
        $slug = 'nss';
    }
    if ($slug === 'national-cadet-corps') {
        $slug = 'ncc';
    }
    if ($slug === 'sports-games' || $slug === 'games') {
        $slug = 'sports';
    }
    if ($slug === 'cultural' || $slug === 'cultural-activity') {
        $slug = 'cultural-activities';
    }

    echo '<div class="mvc-ec-root">';
    mvc_render_ec_shared_styles();
    mvc_render_ec_hero($slug);
    mvc_render_ec_nav($slug);

    echo '<main class="mvc-ec-container" id="ec-main-content">';
    switch ($slug) {
        case 'facility':
            mvc_render_ec_facility();
            break;
        case 'media':
            mvc_render_ec_media();
            break;
        case 'nss':
            mvc_render_ec_nss();
            break;
        case 'ncc':
            mvc_render_ec_ncc();
            break;
        case 'sports':
            mvc_render_ec_sports();
            break;
        case 'cultural-activities':
            mvc_render_ec_cultural();
            break;
        default:
            mvc_render_ec_facility();
            break;
    }
    echo '</main>';
    
    // Global Lightbox Modal for all image galleries
    mvc_render_ec_lightbox_modal();
    
    echo '</div>'; // .mvc-ec-root
}

/**
 * Metadata dictionary for Extra Curricular pages
 */
function mvc_get_ec_meta($slug) {
    $slug_id_map = [
        'facility'            => 286,
        'media'               => 287,
        'nss'                 => 288,
        'ncc'                 => 289,
        'sports'              => 290,
        'cultural-activities' => 291
    ];
    $post_id = $slug_id_map[$slug] ?? 0;
    if (!$post_id) {
        $p = get_page_by_path($slug);
        if ($p) $post_id = $p->ID;
    }

    $meta = [
        'facility' => [
            'title'       => 'Facilities and Infrastructure Details',
            'short_title' => 'Campus Facility',
            'hindi_title' => 'महाविद्यालय की भौतिक एवं शैक्षणिक सुविधाएं',
            'desc'        => 'Explore the comprehensive 3.068-acre academic infrastructure of Maharshi Vishwamitra Mahavidyalaya, Buxar, including smart classrooms, central library, scientific laboratories, sports complexes, and digital learning centers.'
        ],
        'media' => [
            'title'       => 'Media Cell & Institutional Press Coverage',
            'short_title' => 'Media & Press',
            'hindi_title' => 'मीडिया प्रकोष्ठ एवं समाचार विज्ञप्ति',
            'desc'        => 'The Media Cell serves as the central voice and archive of M.V. College, documenting institutional milestones, academic breakthroughs, youth festivals, and national achievements across leading print and digital media.'
        ],
        'nss' => [
            'title'       => 'National Service Scheme (NSS Unit)',
            'short_title' => 'NSS',
            'hindi_title' => 'राष्ट्रीय सेवा योजना (एनएसएस इकाई)',
            'desc'        => 'Fostering the spirit of selfless community service, civic awareness, rural leadership, and nation-building among college youth under the motto "Not Me, But You" (नाहं कर्ता, त्वं कर्ता).'
        ],
        'ncc' => [
            'title'       => 'National Cadet Corps (NCC Army Wing)',
            'short_title' => 'NCC',
            'hindi_title' => 'राष्ट्रीय कैडेट कोर (एनसीसी)',
            'desc'        => 'Grooming disciplined, patriotic, and courageous youth through rigorous military drill, small arms weapon training, adventure camps, national integration, and leadership values under the Associate NCC Officer (ANO).'
        ],
        'sports' => [
            'title'       => 'Department of Sports & Physical Education',
            'short_title' => 'Sports & Games',
            'hindi_title' => 'क्रीड़ा एवं शारीरिक शिक्षा विभाग',
            'desc'        => 'Cultivating athletic excellence, sportsmanship, and physical wellness through state-of-the-art badminton and volleyball courts, modern gymnasium, cricket tournaments, and annual 3-day sports championships.'
        ],
        'cultural-activities' => [
            'title'       => 'Cultural Activities & Performing Arts Council',
            'short_title' => 'Cultural Activities',
            'hindi_title' => 'सांस्कृतिक गतिविधियां एवं कला परिषद',
            'desc'        => 'Celebrating India’s rich artistic heritage and creative expression through classical music, folk dance, theatre, debate competitions, literary symposiums, and national youth cultural festivals.'
        ]
    ];
    $data = $meta[$slug] ?? $meta['facility'];
    if ($post_id) {
        $data['title']       = mvc_get_page_meta($post_id, 'ec_hero_title', $data['title']);
        $data['short_title'] = mvc_get_page_meta($post_id, 'ec_short_title', $data['short_title']);
        $data['hindi_title'] = mvc_get_page_meta($post_id, 'ec_hindi_title', $data['hindi_title']);
        $data['desc']        = mvc_get_page_meta($post_id, 'ec_hero_desc', $data['desc']);
    }
    return $data;
}

/**
 * Shared CSS Styles for Full-Width Canvas, Mobile-First Cards & Interactive Elements
 */
function mvc_render_ec_shared_styles() {
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
        body.page-template-template-extra-curricular #page,
        body.page-template-template-extra-curricular .site-content,
        body.page-template-template-extra-curricular .ast-container,
        body.page-template-template-extra-curricular #content,
        body.page-template-template-extra-curricular #primary,
        body.page-template-template-extra-curricular #main,
        body.page-template-template-extra-curricular .entry-content {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .mvc-ec-root {
            background: #F8FAFC;
            color: var(--mvc-slate);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            padding-bottom: 80px;
        }

        /* Hero Banner */
        .mvc-ec-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #082d5a 100%);
            color: #FFFFFF;
            padding: clamp(36px, 5vw, 60px) clamp(20px, 4vw, 60px);
            text-align: center;
            border-bottom: 4px solid var(--mvc-gold);
            width: 100% !important;
            box-sizing: border-box !important;
            position: relative;
        }

        .mvc-ec-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 14px;
        }

        .mvc-ec-breadcrumb a {
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-ec-breadcrumb a:hover {
            color: var(--mvc-gold);
        }

        .mvc-ec-hero h1 {
            font-size: clamp(26px, 4.2vw, 42px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .mvc-ec-hero-hindi {
            font-size: clamp(16px, 2.4vw, 20px);
            font-weight: 600;
            color: var(--mvc-gold);
            margin-bottom: 12px;
        }

        .mvc-ec-hero-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.6;
            margin: 0 auto;
            max-width: 900px;
        }

        /* Sticky Navigation Bar with < > scroll buttons */
        .mvc-ec-nav-strip {
            background: #FFFFFF;
            border-bottom: 1px solid var(--mvc-border);
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
        }

        .mvc-ec-nav-wrapper {
            display: flex;
            align-items: center;
            position: relative;
            max-width: 100%;
            margin: 0 auto;
            padding: 4px clamp(10px, 2.5vw, 24px);
            gap: 8px;
        }

        .mvc-ec-nav-arrow-btn {
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

        .mvc-ec-nav-arrow-btn:hover {
            background: var(--mvc-navy);
            color: #FFFFFF;
            border-color: var(--mvc-navy);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.2);
        }

        .mvc-ec-nav-arrow-btn:active {
            transform: scale(0.95);
        }

        .mvc-ec-nav-arrow-btn.is-disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
            background: #F1F5F9;
            border-color: #E2E8F0;
            color: #94A3B8;
            box-shadow: none;
            transform: none;
        }

        .mvc-ec-nav-inner {
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

        .mvc-ec-nav-inner::-webkit-scrollbar {
            display: none;
        }

        .mvc-ec-nav-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 700;
            color: var(--mvc-slate-light);
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .mvc-ec-nav-item:hover {
            color: var(--mvc-navy);
            background: #F1F5F9;
        }

        .mvc-ec-nav-item.active {
            color: var(--mvc-navy);
            border-bottom-color: var(--mvc-gold);
            background: #FAF9F6;
        }

        /* Main Container */
        .mvc-ec-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 40px clamp(20px, 4vw, 60px) 0 !important;
            box-sizing: border-box !important;
        }

        /* Section Cards & Panels */
        .mvc-ec-card {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.05);
            padding: clamp(20px, 3.5vw, 36px);
            margin-bottom: 32px;
            box-sizing: border-box;
        }

        .mvc-ec-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 2px solid #F1F5F9;
        }

        .mvc-ec-section-title h2 {
            font-size: clamp(20px, 2.8vw, 26px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mvc-ec-badge {
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

        /* Officer / Coordinator Hero Split Card */
        .mvc-ec-officer-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 32px;
            align-items: stretch;
            margin-bottom: 32px;
        }

        @media (max-width: 991px) {
            .mvc-ec-officer-grid {
                grid-template-columns: 1fr;
            }
        }

        .mvc-ec-officer-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-top: 5px solid var(--mvc-maroon);
            border-radius: 14px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.06);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .mvc-ec-dept-logo {
            height: 90px;
            width: auto;
            max-width: 140px;
            object-fit: contain;
            margin-bottom: 16px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));
        }

        .mvc-ec-officer-photo {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #FFFFFF;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.15);
            margin-bottom: 14px;
        }

        .mvc-ec-officer-name {
            font-size: 19px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-ec-officer-email {
            font-size: 13.5px;
            color: var(--mvc-slate-light);
            margin: 0 0 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .mvc-ec-officer-designation {
            background: linear-gradient(135deg, var(--mvc-maroon) 0%, #3c0b0b 100%);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        .mvc-ec-officer-narrative {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid var(--mvc-border);
            padding: 28px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .mvc-ec-officer-narrative h3 {
            font-size: 22px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 14px;
            border-left: 4px solid var(--mvc-gold);
            padding-left: 12px;
        }

        .mvc-ec-officer-narrative p {
            font-size: 15px;
            line-height: 1.7;
            color: #334155;
            margin-bottom: 16px;
        }

        /* Stats Counter Row */
        .mvc-ec-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .mvc-ec-stat-box {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .mvc-ec-stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 33, 71, 0.08);
            border-color: var(--mvc-gold);
        }

        .mvc-ec-stat-num {
            font-size: 30px;
            font-weight: 800;
            color: var(--mvc-navy);
            line-height: 1;
            margin-bottom: 6px;
        }

        .mvc-ec-stat-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--mvc-slate-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Interactive Filter Pills & Search Box */
        .mvc-ec-filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 24px;
            background: #F1F5F9;
            padding: 12px 16px;
            border-radius: 12px;
        }

        .mvc-ec-pills {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .mvc-ec-pill {
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid var(--mvc-border);
            background: #FFFFFF;
            color: var(--mvc-slate);
            transition: all 0.2s;
        }

        .mvc-ec-pill:hover {
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
        }

        .mvc-ec-pill.active {
            background: var(--mvc-navy);
            color: #FFFFFF;
            border-color: var(--mvc-navy);
        }

        .mvc-ec-search-box {
            position: relative;
            min-width: 240px;
        }

        .mvc-ec-search-box input {
            width: 100%;
            padding: 8px 12px 8px 34px;
            border-radius: 8px;
            border: 1px solid var(--mvc-border);
            font-size: 13.5px;
            outline: none;
            box-sizing: border-box;
            background: #FFFFFF;
        }

        .mvc-ec-search-box input:focus {
            border-color: var(--mvc-navy);
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
        }

        .mvc-ec-search-box svg {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mvc-slate-light);
            pointer-events: none;
        }

        /* Responsive Data Tables with Mobile Cards */
        .mvc-ec-table-wrap {
            width: 100%;
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid var(--mvc-border);
            background: #FFFFFF;
            margin-bottom: 24px;
        }

        .mvc-ec-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        .mvc-ec-table thead th {
            background: var(--mvc-navy);
            color: #FFFFFF;
            padding: 14px 18px;
            font-weight: 700;
            letter-spacing: 0.3px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mvc-ec-table tbody tr {
            border-bottom: 1px solid var(--mvc-border);
            transition: background 0.15s;
        }

        .mvc-ec-table tbody tr:nth-child(even) {
            background: var(--mvc-bg-alt);
        }

        .mvc-ec-table tbody tr:hover {
            background: #F1F5F9;
        }

        .mvc-ec-table td {
            padding: 13px 18px;
            color: var(--mvc-slate);
            border-right: 1px solid var(--mvc-border);
            vertical-align: middle;
        }

        /* Buttons & Badges */
        .mvc-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
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
            transform: translateY(-1px);
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

        /* Photo Gallery Grid */
        .mvc-ec-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .mvc-ec-gallery-card {
            background: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--mvc-border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
            position: relative;
        }

        .mvc-ec-gallery-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 33, 71, 0.12);
            border-color: var(--mvc-gold);
        }

        .mvc-ec-gallery-thumb-wrap {
            position: relative;
            padding-top: 66.6%; /* 3:2 ratio */
            overflow: hidden;
            background: #E2E8F0;
            cursor: pointer;
        }

        .mvc-ec-gallery-thumb {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .mvc-ec-gallery-card:hover .mvc-ec-gallery-thumb {
            transform: scale(1.06);
        }

        .mvc-ec-gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 33, 71, 0.4);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s ease;
            color: #FFFFFF;
            font-size: 14px;
            font-weight: 700;
            gap: 6px;
        }

        .mvc-ec-gallery-card:hover .mvc-ec-gallery-overlay {
            opacity: 1;
        }

        .mvc-ec-gallery-info {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .mvc-ec-gallery-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--mvc-slate);
            margin: 0 0 8px;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .mvc-ec-gallery-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--mvc-slate-light);
            margin-top: auto;
            padding-top: 8px;
            border-top: 1px solid #F1F5F9;
        }

        .mvc-ec-gallery-date {
            font-weight: 600;
            color: var(--mvc-maroon);
        }

        /* Lightbox Modal */
        .mvc-ec-lightbox {
            position: fixed;
            inset: 0;
            background: rgba(0, 15, 35, 0.88);
            backdrop-filter: blur(8px);
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .mvc-ec-lightbox.active {
            display: flex;
            opacity: 1;
        }

        .mvc-ec-lightbox-content {
            background: #FFFFFF;
            border-radius: 14px;
            max-width: 900px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            position: relative;
            animation: mvcModalScale 0.25s ease;
        }

        @keyframes mvcModalScale {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .mvc-ec-lightbox-img-wrap {
            position: relative;
            background: #001020;
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 70vh;
            overflow: hidden;
        }

        .mvc-ec-lightbox-img {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
        }

        .mvc-ec-lightbox-footer {
            padding: 16px 20px;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .mvc-ec-lightbox-caption {
            font-size: 15px;
            font-weight: 700;
            color: var(--mvc-navy);
            margin: 0;
        }

        .mvc-ec-lightbox-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            background: rgba(0, 0, 0, 0.6);
            color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            z-index: 10;
            transition: background 0.2s;
        }

        .mvc-ec-lightbox-close:hover {
            background: var(--mvc-maroon);
        }

        /* Mobile First Responsive Styles */
        @media (max-width: 767px) {
            .mvc-ec-table thead {
                display: none;
            }

            .mvc-ec-table, .mvc-ec-table tbody, .mvc-ec-table tr, .mvc-ec-table td {
                display: block;
                width: 100% !important;
                box-sizing: border-box;
            }

            .mvc-ec-table tr {
                background: #FFFFFF !important;
                border: 1.5px solid var(--mvc-border) !important;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .mvc-ec-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border: none;
                border-bottom: 1px dashed var(--mvc-border);
                font-size: 13.5px;
                text-align: right;
            }

            .mvc-ec-table td:last-child {
                border-bottom: none;
                padding-top: 14px;
                justify-content: flex-end;
            }

            .mvc-ec-table td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--mvc-navy);
                text-align: left;
                padding-right: 12px;
                flex-shrink: 0;
            }

            .mvc-ec-filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .mvc-ec-search-box {
                width: 100%;
            }
        }
    </style>

    <script>
        function mvcEcNavScroll(direction) {
            var track = document.getElementById('mvcEcNavTrack');
            if (!track) return;
            var scrollDistance = 280 * direction;
            track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
            setTimeout(mvcUpdateEcNavArrowStates, 320);
        }

        function mvcUpdateEcNavArrowStates() {
            var track = document.getElementById('mvcEcNavTrack');
            var prevBtn = document.getElementById('mvcEcNavArrowPrev');
            var nextBtn = document.getElementById('mvcEcNavArrowNext');
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

        /* Lightbox Functions */
        function mvcOpenLightbox(src, caption, date) {
            var modal = document.getElementById('mvcEcLightbox');
            var img = document.getElementById('mvcEcLightboxImg');
            var capEl = document.getElementById('mvcEcLightboxCaption');
            var directBtn = document.getElementById('mvcEcLightboxDirect');
            if (!modal || !img) return;

            img.src = src;
            if (capEl) capEl.textContent = caption || 'Institutional Media Item';
            if (directBtn) directBtn.href = src;

            modal.style.display = 'flex';
            setTimeout(function() { modal.classList.add('active'); }, 10);
            document.body.style.overflow = 'hidden';
        }

        function mvcCloseLightbox() {
            var modal = document.getElementById('mvcEcLightbox');
            if (!modal) return;
            modal.classList.remove('active');
            setTimeout(function() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 250);
        }

        /* Client-side Live Filter */
        function mvcFilterGrid(filterCat, btn) {
            var parent = btn.closest('.mvc-ec-filter-bar');
            if (parent) {
                var pills = parent.querySelectorAll('.mvc-ec-pill');
                pills.forEach(function(p) { p.classList.remove('active'); });
                btn.classList.add('active');
            }

            var grid = document.querySelector('.mvc-ec-filterable-items');
            if (!grid) return;
            var items = grid.querySelectorAll('.mvc-ec-filterable-item');

            items.forEach(function(item) {
                var itemCat = item.getAttribute('data-category') || '';
                if (filterCat === 'all' || itemCat === filterCat) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function mvcSearchGrid(query) {
            var q = query.trim().toLowerCase();
            var grid = document.querySelector('.mvc-ec-filterable-items');
            if (!grid) return;
            var items = grid.querySelectorAll('.mvc-ec-filterable-item');

            items.forEach(function(item) {
                var text = (item.textContent || '').toLowerCase();
                if (!q || text.indexOf(q) !== -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var track = document.getElementById('mvcEcNavTrack');
            if (track) {
                track.addEventListener('scroll', mvcUpdateEcNavArrowStates, { passive: true });
                window.addEventListener('resize', mvcUpdateEcNavArrowStates, { passive: true });

                var activeItem = document.getElementById('mvcActiveEcNavItem');
                if (activeItem) {
                    setTimeout(function() {
                        activeItem.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                        setTimeout(mvcUpdateEcNavArrowStates, 320);
                    }, 120);
                } else {
                    mvcUpdateEcNavArrowStates();
                }
            }

            // Keyboard shortcut Escape for Lightbox
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    mvcCloseLightbox();
                }
            });
        });
    </script>
    <?php
}

/**
 * Universal Hero Banner for Extra Curricular Suite
 */
function mvc_render_ec_hero($slug) {
    $meta = mvc_get_ec_meta($slug);
    ?>
    <header class="mvc-ec-hero">
        <div class="mvc-ec-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
            <span>/</span>
            <a href="<?php echo esc_url(home_url('/facility/')); ?>">Extra Curricular</a>
            <span>/</span>
            <span><?php echo esc_html($meta['short_title']); ?></span>
        </div>
        <h1><?php echo esc_html($meta['title']); ?></h1>
        <div class="mvc-ec-hero-hindi"><?php echo esc_html($meta['hindi_title']); ?></div>
        <p class="mvc-ec-hero-desc"><?php echo esc_html($meta['desc']); ?></p>
    </header>
    <?php
}

/**
 * Sticky Swipeable Navigation Track with < > Scroll Buttons
 */
function mvc_render_ec_nav($active_slug) {
    $nav_items = [
        'facility'            => 'Campus Facility',
        'media'               => 'Media & Press',
        'nss'                 => 'NSS (Service Scheme)',
        'ncc'                 => 'NCC (Cadet Corps)',
        'sports'              => 'Sports & Games',
        'cultural-activities' => 'Cultural Activities'
    ];
    ?>
    <nav class="mvc-ec-nav-strip" aria-label="Extra Curricular Navigation">
        <div class="mvc-ec-nav-wrapper">
            <button type="button" class="mvc-ec-nav-arrow-btn is-disabled" id="mvcEcNavArrowPrev" onclick="mvcEcNavScroll(-1)" aria-label="Scroll Extra Curricular Menu Left" title="Scroll left">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <div class="mvc-ec-nav-inner" id="mvcEcNavTrack">
                <?php foreach ($nav_items as $slug => $label): 
                    $is_active = ($active_slug === $slug);
                    $url = home_url('/' . $slug . '/');
                ?>
                    <a href="<?php echo esc_url($url); ?>" class="mvc-ec-nav-item <?php echo $is_active ? 'active' : ''; ?>" <?php echo $is_active ? 'id="mvcActiveEcNavItem" aria-current="page"' : ''; ?>>
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <button type="button" class="mvc-ec-nav-arrow-btn" id="mvcEcNavArrowNext" onclick="mvcEcNavScroll(1)" aria-label="Scroll Extra Curricular Menu Right" title="Scroll right">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </nav>
    <?php
}

/**
 * Global Reusable Lightbox Modal
 */
function mvc_render_ec_lightbox_modal() {
    ?>
    <div id="mvcEcLightbox" class="mvc-ec-lightbox" onclick="if(event.target === this) mvcCloseLightbox();" role="dialog" aria-modal="true" aria-label="Image Preview">
        <div class="mvc-ec-lightbox-content">
            <button type="button" class="mvc-ec-lightbox-close" onclick="mvcCloseLightbox()" aria-label="Close Preview">&times;</button>
            <div class="mvc-ec-lightbox-img-wrap">
                <img id="mvcEcLightboxImg" src="" alt="Full view preview" class="mvc-ec-lightbox-img" />
            </div>
            <div class="mvc-ec-lightbox-footer">
                <h4 id="mvcEcLightboxCaption" class="mvc-ec-lightbox-caption">Institutional Media Item</h4>
                <a id="mvcEcLightboxDirect" href="#" target="_blank" rel="noopener" class="mvc-btn mvc-btn-navy">
                    <span>Open High-Res</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 1. FACILITY RENDERER
 * 23 Authentic Campus Facilities with Quantities, Categories, and Stat Counters
 */
function mvc_get_ec_facilities_data() {
    $default = [
        [
            'sr_no' => 1,
            'name' => 'Well Equipped Interactive Smart Classroom',
            'quantity' => '03',
            'category' => 'ICT & Digital Infrastructure',
            'icon' => 'desktop',
        ],
        [
            'sr_no' => 2,
            'name' => 'Reading Room in Library',
            'quantity' => '01',
            'category' => 'Library & Learning Resources',
            'icon' => 'book-reader',
        ],
        [
            'sr_no' => 3,
            'name' => 'Cycle Stand',
            'quantity' => 'Yes',
            'category' => 'Safety & Campus Amenities',
            'icon' => 'shield-alt',
        ],
        [
            'sr_no' => 4,
            'name' => 'Motor Bike Stand',
            'quantity' => 'Yes',
            'category' => 'Safety & Campus Amenities',
            'icon' => 'shield-alt',
        ],
        [
            'sr_no' => 5,
            'name' => 'Green & Clean Campus & well - furnished Building',
            'quantity' => '3.068Acre',
            'category' => 'Institutional Infrastructure',
            'icon' => 'university',
        ],
        [
            'sr_no' => 6,
            'name' => 'Ramp Hand Rail',
            'quantity' => 'All Building',
            'category' => 'Safety & Campus Amenities',
            'icon' => 'shield-alt',
        ],
        [
            'sr_no' => 7,
            'name' => 'Seminar Hall with ICT Enabled',
            'quantity' => '01',
            'category' => 'ICT & Digital Infrastructure',
            'icon' => 'desktop',
        ],
        [
            'sr_no' => 8,
            'name' => 'Badminaton & Volley Ball Court',
            'quantity' => '01',
            'category' => 'Sports & Physical Fitness',
            'icon' => 'trophy',
        ],
        [
            'sr_no' => 9,
            'name' => 'Canteen Cum Utlity Centre',
            'quantity' => '01',
            'category' => 'Institutional Infrastructure',
            'icon' => 'university',
        ],
        [
            'sr_no' => 10,
            'name' => 'Fire Extinguisher (BC & ABC Type)',
            'quantity' => 'All Building',
            'category' => 'Safety & Campus Amenities',
            'icon' => 'shield-alt',
        ],
        [
            'sr_no' => 11,
            'name' => 'IP CCTV Cammera Security System',
            'quantity' => 'Yes',
            'category' => 'Safety & Campus Amenities',
            'icon' => 'shield-alt',
        ],
        [
            'sr_no' => 12,
            'name' => 'Free Wifi Internet Facilities',
            'quantity' => 'up to 15 MBPS',
            'category' => 'ICT & Digital Infrastructure',
            'icon' => 'desktop',
        ],
        [
            'sr_no' => 13,
            'name' => 'R.O Water Plant with Chiller',
            'quantity' => '04',
            'category' => 'Safety & Campus Amenities',
            'icon' => 'shield-alt',
        ],
        [
            'sr_no' => 14,
            'name' => 'Gymnasium',
            'quantity' => '01',
            'category' => 'Sports & Physical Fitness',
            'icon' => 'trophy',
        ],
        [
            'sr_no' => 15,
            'name' => 'Soundless Power Generator (55kva)',
            'quantity' => '01',
            'category' => 'Safety & Campus Amenities',
            'icon' => 'shield-alt',
        ],
        [
            'sr_no' => 16,
            'name' => 'IQAC Cum NAAC Chamber',
            'quantity' => '01',
            'category' => 'Institutional Infrastructure',
            'icon' => 'university',
        ],
        [
            'sr_no' => 17,
            'name' => 'Girl\'s Common Room with Changing Cabin',
            'quantity' => '01',
            'category' => 'Institutional Infrastructure',
            'icon' => 'university',
        ],
        [
            'sr_no' => 18,
            'name' => 'Well Furnished Classroom - 15',
            'quantity' => 'N/A',
            'category' => 'Institutional Infrastructure',
            'icon' => 'university',
        ],
        [
            'sr_no' => 19,
            'name' => 'Fully Automated Central Library',
            'quantity' => '01',
            'category' => 'Library & Learning Resources',
            'icon' => 'book-reader',
        ],
        [
            'sr_no' => 20,
            'name' => 'Laboratory (Physics, Chemistry, Botany, Zoology, & Psychology)',
            'quantity' => '05',
            'category' => 'Laboratories & Research',
            'icon' => 'flask',
        ],
        [
            'sr_no' => 21,
            'name' => 'BCA Coumuter Lab',
            'quantity' => '01',
            'category' => 'Laboratories & Research',
            'icon' => 'flask',
        ],
        [
            'sr_no' => 22,
            'name' => 'Well Equipped Swayam Lab',
            'quantity' => 'N/A',
            'category' => 'ICT & Digital Infrastructure',
            'icon' => 'desktop',
        ],
        [
            'sr_no' => 23,
            'name' => 'Language Lab 20+1 Console',
            'quantity' => '01',
            'category' => 'ICT & Digital Infrastructure',
            'icon' => 'desktop',
        ],
    ];
    return mvc_get_page_array(286, 'ec_facilities_data', $default);
}

function mvc_render_ec_facility() {
    $facilities = mvc_get_ec_facilities_data();
    $total_facilities = count($facilities);

    $cat_counts = [];
    foreach ($facilities as $f) {
        $c = $f['category'] ?? 'Other';
        $cat_counts[$c] = ($cat_counts[$c] ?? 0) + 1;
    }
    ?>
    <!-- Overview Metric Cards -->
    <div class="mvc-ec-stats-grid">
        <div class="mvc-ec-stat-box">
            <div class="mvc-ec-stat-num"><?php echo esc_html($total_facilities); ?></div>
            <div class="mvc-ec-stat-label">Total Facilities</div>
        </div>
        <div class="mvc-ec-stat-box">
            <div class="mvc-ec-stat-num">3.068</div>
            <div class="mvc-ec-stat-label">Acres Green Campus</div>
        </div>
        <div class="mvc-ec-stat-box">
            <div class="mvc-ec-stat-num"><?php echo sprintf('%02d', $cat_counts['ICT & Digital Infrastructure'] ?? 3); ?></div>
            <div class="mvc-ec-stat-label">Interactive Smart Classrooms</div>
        </div>
        <div class="mvc-ec-stat-box">
            <div class="mvc-ec-stat-num"><?php echo sprintf('%02d', $cat_counts['Laboratories & Research'] ?? 5); ?></div>
            <div class="mvc-ec-stat-label">Scientific Laboratories</div>
        </div>
        <div class="mvc-ec-stat-box">
            <div class="mvc-ec-stat-num">24×7</div>
            <div class="mvc-ec-stat-label">IP CCTV Security</div>
        </div>
    </div>

    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>🏢 Campus Infrastructure & Learning Resources</span>
            </h2>
            <span class="mvc-ec-badge">Authentic Audit Inventory</span>
        </div>

        <!-- Filter & Search Bar -->
        <div class="mvc-ec-filter-bar">
            <div class="mvc-ec-pills">
                <button type="button" class="mvc-ec-pill active" onclick="mvcFilterGrid('all', this)">All (<?php echo esc_html($total_facilities); ?>)</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('ICT & Digital Infrastructure', this)">ICT & Digital (<?php echo esc_html($cat_counts['ICT & Digital Infrastructure'] ?? 0); ?>)</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('Library & Learning Resources', this)">Library (<?php echo esc_html($cat_counts['Library & Learning Resources'] ?? 0); ?>)</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('Laboratories & Research', this)">Laboratories (<?php echo esc_html($cat_counts['Laboratories & Research'] ?? 0); ?>)</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('Sports & Physical Fitness', this)">Sports & Fitness (<?php echo esc_html($cat_counts['Sports & Physical Fitness'] ?? 0); ?>)</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('Safety & Campus Amenities', this)">Safety & Utilities (<?php echo esc_html($cat_counts['Safety & Campus Amenities'] ?? 0); ?>)</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('Institutional Infrastructure', this)">Institutional (<?php echo esc_html($cat_counts['Institutional Infrastructure'] ?? 0); ?>)</button>
            </div>
            <div class="mvc-ec-search-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Search facilities..." oninput="mvcSearchGrid(this.value)" aria-label="Search facilities" />
            </div>
        </div>

        <!-- Responsive Facility Table -->
        <div class="mvc-ec-table-wrap">
            <table class="mvc-ec-table">
                <thead>
                    <tr>
                        <th style="width: 70px; text-align: center;">Sr No</th>
                        <th>Facility Name</th>
                        <th style="width: 240px;">Category</th>
                        <th style="width: 160px; text-align: center;">Quantity / Remarks</th>
                        <th style="width: 140px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody class="mvc-ec-filterable-items">
                    <?php foreach ($facilities as $fac): ?>
                        <tr class="mvc-ec-filterable-item" data-category="<?php echo esc_attr($fac['category']); ?>">
                            <td data-label="Sr No" style="text-align: center; font-weight: 700; color: var(--mvc-navy);">
                                <?php echo sprintf('%02d', $fac['sr_no']); ?>
                            </td>
                            <td data-label="Facility Name" style="font-weight: 600; font-size: 14.5px;">
                                <?php echo esc_html($fac['name']); ?>
                            </td>
                            <td data-label="Category">
                                <span class="mvc-ec-badge" style="font-size: 11.5px; padding: 3px 8px;">
                                    <?php echo esc_html($fac['category']); ?>
                                </span>
                            </td>
                            <td data-label="Quantity / Remarks" style="text-align: center; font-weight: 700; color: var(--mvc-maroon);">
                                <?php echo esc_html($fac['quantity']); ?>
                            </td>
                            <td data-label="Status" style="text-align: center;">
                                <span style="display: inline-flex; align-items: center; gap: 4px; color: #166534; font-weight: 700; font-size: 13px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Operational
                                </span>
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
 * 2. MEDIA RENDERER
 * Media Cell Coordinator Card, Committee Members, Institutional Mission & 92 Press Clippings
 */
function mvc_get_ec_media_data() {
    $default = [
        [
            'src' => '/uploads/media/images/media_1766033758.jpeg',
            'title' => 'Media Members',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766034132.jpeg',
            'title' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766034150.jpeg',
            'title' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766034169.jpeg',
            'title' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766171625.jpeg',
            'title' => 'literature-beyond-aesthetic-appreciation',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766389103.jpeg',
            'title' => 'Discussion on Career and Artificial Intelligence',
            'date' => '11 December 2005',
        ],
        [
            'src' => '/uploads/media/images/media_1767690493.jpeg',
            'title' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690507.jpeg',
            'title' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769060566.jpeg',
            'title' => 'Grand Three-Day Sports Competition Organized at M.V. College',
            'date' => '22 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769060585.jpeg',
            'title' => 'Grand Three-Day Sports Competition Organized at M.V. College',
            'date' => '22 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769060598.jpeg',
            'title' => 'Grand Three-Day Sports Competition Organized at M.V. College',
            'date' => '22 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769752525.jpeg',
            'title' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769752541.jpeg',
            'title' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769752557.jpeg',
            'title' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846382.jpeg',
            'title' => 'खेल महोत्सव Day - 2',
            'date' => '30 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846847.jpeg',
            'title' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846902.jpeg',
            'title' => 'खेल महोत्सव Day - 2',
            'date' => '30 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1770269406.jpeg',
            'title' => 'खेल महोत्सव Day - 3',
            'date' => '31 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650097.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650119.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650135.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650165.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650192.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650205.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650225.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1786947951.jpeg',
            'title' => 'National Seminar on NEP 2020 Completing 6 years',
            'date' => '05 August 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1786948015.jpeg',
            'title' => 'National Seminar on NEP 2020 Completing 6 years',
            'date' => '05 August 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1786948054.jpeg',
            'title' => 'National Seminar on NEP 2020 Completing 6 years',
            'date' => '05 August 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1786948093.jpeg',
            'title' => 'National Seminar on NEP 2020 Completing 6 years',
            'date' => '05 August 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1764142083.jpeg',
            'title' => 'Kabaddi Pratiyogita',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765363697.jpg',
            'title' => 'वर्षा जल संचय को लेकर नेहरू युवा केंद्र ने किया वेबिनार',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765428756.jpg',
            'title' => 'Awareness Against Terrorism',
            'date' => '22 May 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1766033815.jpeg',
            'title' => 'परीक्षा को लेकर अहम बैठक',
            'date' => '28 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766033855.jpeg',
            'title' => 'परीक्षा को लेकर अहम बैठक',
            'date' => '28 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766171930.png',
            'title' => '“साहित्य केवल सौंदर्यबोध का माध्यम नहीं”',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766389160.jpeg',
            'title' => 'Discussion on Career and Artificial Intelligence',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1767690533.jpeg',
            'title' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846728.jpeg',
            'title' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1764142236.jpg',
            'title' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766389196.jpeg',
            'title' => 'Discussion on Career and Artificial Intelligence',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765360541.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1764142493.jpg',
            'title' => 'Sports Festival Scheduled at Maharshi Vishwamitra College',
            'date' => '01 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766034214.jpeg',
            'title' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1778736370.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736393.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736412.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736428.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736451.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736504.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736521.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736562.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736640.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736655.jpeg',
            'title' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1764142784.jpg',
            'title' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142690.jpg',
            'title' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142955.jpg',
            'title' => 'Back to Class – Build Your Future',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143117.jpg',
            'title' => 'Yoga: A Way of Discipline',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143145.jpg',
            'title' => 'Yoga: A Way of Discipline',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143225.jpg',
            'title' => 'Internship Completed by MV College',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143317.jpg',
            'title' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143627.jpg',
            'title' => 'In Memory',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143718.jpg',
            'title' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143822.jpg',
            'title' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765351751.jpg',
            'title' => 'शिक्षा में अनुसंधान पर विशेष ज़ोर',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765352588.jpg',
            'title' => 'भारतीय शिक्षण मंडल की महत्वपूर्ण बैठक',
            'date' => '20 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1764144257.jpg',
            'title' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765353427.jpg',
            'title' => 'जनसंख्या में भारत का विश्व में दूसरा स्थान',
            'date' => '12 July 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1765360128.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1764144355.jpg',
            'title' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765353551.jpg',
            'title' => 'भारतीय मूल्यों पर आधारित उच्च शिक्षा',
            'date' => '28 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765360218.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1764144416.jpg',
            'title' => 'Yoga: A Way of Discipline',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765353840.jpg',
            'title' => 'Rashtriya Sahara',
            'date' => '22 June 2022',
        ],
        [
            'src' => '/uploads/media/images/media_1765354479.jpg',
            'title' => 'नेहरू युवा केंद्र द्वारा जिला युवा सम्मेलन आयोजित',
            'date' => '10 April 2024',
        ],
        [
            'src' => '/uploads/media/images/media_1765361636.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765361822.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765361863.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765361904.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765364072.jpg',
            'title' => 'सिद्धाश्रम साहित्य परिषद्',
            'date' => '05 July 2022',
        ],
        [
            'src' => '/uploads/media/images/media_1765364369.jpg',
            'title' => 'स्वस्थ जीवन का संकल्प',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765364627.jpg',
            'title' => 'स्वस्थ जीवन का संकल्प',
            'date' => '06 January 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765365084.jpg',
            'title' => 'Awareness Against Terrorism',
            'date' => '22 May 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1765431011.jpg',
            'title' => 'news',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431345.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431175.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431046.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765429704.jpg',
            'title' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765431085.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431448.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431484.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431122.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431308.jpg',
            'title' => 'News',
            'date' => '11 December 2025',
        ],
    ];
    return mvc_get_page_array(287, 'ec_media_data', $default);
}

function mvc_render_ec_media() {
    $press_items = mvc_get_ec_media_data();
    $upload_base = home_url('/wp-content');
    ?>
    <!-- Media Cell Coordinator Split Card -->
    <div class="mvc-ec-officer-grid">
        <div class="mvc-ec-officer-card">
            <img src="<?php echo esc_url($upload_base . '/uploads/media/media.png'); ?>" alt="Media Cell Crest" class="mvc-ec-dept-logo" />
            <img src="<?php echo esc_url($upload_base . '/uploads/faculty-images/Profile_1764589583.png'); ?>" alt="Dr. Arvind Verma" class="mvc-ec-officer-photo" />
            <h3 class="mvc-ec-officer-name">Dr. Arvind Verma</h3>
            <div class="mvc-ec-officer-email">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>abhivemm@gmail.com</span>
            </div>
            <div class="mvc-ec-officer-designation">Co-ordinator, Media Cell</div>
        </div>

        <div class="mvc-ec-officer-narrative">
            <h3>About Media Cell</h3>
            <p>
                The Media Cell of Maharshi Vishwamitra Mahavidyalaya, Buxar serves as an important platform for documenting and promoting the diverse activities and achievements of the College. It plays a key role in communicating institutional events, academic accomplishments, cultural programmes, sports activities, and community outreach initiatives to a wider audience.
            </p>
            <p>
                The Cell is responsible for collecting, organizing, and disseminating information through photographs, videos, reports, newsletters, and digital media content. By effectively covering significant events and milestones, it helps preserve the institutional legacy and showcase the vibrant life of the campus.
            </p>
            <p>
                The Media Cell encourages creativity, innovation, and active student participation in media-related activities. Students gain opportunities to develop skills in communication, photography, videography, content writing, event reporting, and digital media management. Through accurate and timely communication, the Media Cell strengthens the College’s visibility and reputation.
            </p>
        </div>
    </div>

    <!-- Media Committee Table -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>👥 Media Cell Committee</span>
            </h2>
            <span class="mvc-ec-badge">Executive Governance</span>
        </div>
        <div class="mvc-ec-table-wrap">
            <table class="mvc-ec-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Sr No</th>
                        <th>Member Name</th>
                        <th>Designation</th>
                        <th>Official Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Sr No" style="text-align: center; font-weight: 700;">01</td>
                        <td data-label="Member Name" style="font-weight: 700; color: var(--mvc-navy);">Prof. (Dr.) Krishna Kant Singh</td>
                        <td data-label="Designation"><span class="mvc-ec-badge">Chairperson</span></td>
                        <td data-label="Official Email">kksingh074@gmail.com</td>
                    </tr>
                    <tr>
                        <td data-label="Sr No" style="text-align: center; font-weight: 700;">02</td>
                        <td data-label="Member Name" style="font-weight: 700; color: var(--mvc-navy);">Dr. Arvind Verma</td>
                        <td data-label="Designation"><span class="mvc-ec-badge">Co-ordinator</span></td>
                        <td data-label="Official Email">abhivemm@gmail.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Press Coverage & Media Clippings Gallery -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>📰 Press Coverage & News Clippings</span>
            </h2>
            <span class="mvc-ec-badge"><?php echo count($press_items); ?> Published Clippings</span>
        </div>

        <div class="mvc-ec-filter-bar">
            <div class="mvc-ec-pills">
                <button type="button" class="mvc-ec-pill active" onclick="mvcFilterGrid('all', this)">All Years (<?php echo count($press_items); ?>)</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('2026', this)">2026</button>
                <button type="button" class="mvc-ec-pill" onclick="mvcFilterGrid('2025', this)">2025</button>
            </div>
            <div class="mvc-ec-search-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Search press clippings..." oninput="mvcSearchGrid(this.value)" aria-label="Search press clippings" />
            </div>
        </div>

        <div class="mvc-ec-gallery-grid mvc-ec-filterable-items">
            <?php foreach ($press_items as $item): 
                $img_url = home_url('/wp-content' . $item['src']);
                $year = strpos($item['date'], '2026') !== false ? '2026' : (strpos($item['date'], '2025') !== false ? '2025' : 'archive');
            ?>
                <div class="mvc-ec-gallery-card mvc-ec-filterable-item" data-category="<?php echo esc_attr($year); ?>">
                    <div class="mvc-ec-gallery-thumb-wrap" onclick="mvcOpenLightbox('<?php echo esc_url($img_url); ?>', '<?php echo esc_js($item['title']); ?>', '<?php echo esc_js($item['date']); ?>')">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="mvc-ec-gallery-thumb" loading="lazy" />
                        <div class="mvc-ec-gallery-overlay">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                            <span>Read Clipping</span>
                        </div>
                    </div>
                    <div class="mvc-ec-gallery-info">
                        <h4 class="mvc-ec-gallery-title"><?php echo esc_html($item['title']); ?></h4>
                        <div class="mvc-ec-gallery-meta">
                            <span class="mvc-ec-gallery-date"><?php echo esc_html($item['date'] ?: 'Official Release'); ?></span>
                            <a href="<?php echo esc_url($img_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-outline" style="padding: 4px 8px; font-size: 11.5px;">
                                Direct View ↗
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * 3. NSS RENDERER
 * Programme Officer Card, Committee Members, Institutional Narrative & 58 Activities
 */
function mvc_get_ec_nss_data() {
    $default = [
        [
            'src' => '/uploads/image_gallery/gallery_69784a5ea57d7.jpeg',
            'caption' => 'Previous',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_69784a32e6aa7.jpeg',
            'caption' => 'National Service Scheme Members',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6948f6dd1d11c.jpeg',
            'caption' => 'Multi Purpose Sports',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_693937cc90bcb.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_693960b3554c7.jpg',
            'caption' => 'Awareness Against Terrorism',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_693a4cf4de0ea.jpg',
            'caption' => 'World Population Day Awareness Program',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_693a55fd3bf2f.jpg',
            'caption' => 'Tobacco Day',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6948f3eccb96b.jpg',
            'caption' => 'विकसित भारत युवा संसद - 2026',
            'date' => '',
        ],
        [
            'src' => '/uploads/media/images/media_1764143117.jpg',
            'caption' => 'National Service Scheme Press Release',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1764143145.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144416.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142784.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143225.jpg',
            'caption' => 'Internship Completed by MV College',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143718.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143822.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142493.jpg',
            'caption' => 'Sports Festival Scheduled at Maharshi Vishwamitra College',
            'date' => '01 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142690.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143317.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142955.jpg',
            'caption' => 'Back to Class – Build Your Future',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143627.jpg',
            'caption' => 'In Memory',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144257.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144355.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765351751.jpg',
            'caption' => 'शिक्षा में अनुसंधान पर विशेष ज़ोर',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765352588.jpg',
            'caption' => 'भारतीय शिक्षण मंडल की महत्वपूर्ण बैठक',
            'date' => '20 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353427.jpg',
            'caption' => 'जनसंख्या में भारत का विश्व में दूसरा स्थान',
            'date' => '12 July 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1765353551.jpg',
            'caption' => 'भारतीय मूल्यों पर आधारित उच्च शिक्षा',
            'date' => '28 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353840.jpg',
            'caption' => 'Rashtriya Sahara',
            'date' => '22 June 2022',
        ],
        [
            'src' => '/uploads/media/images/media_1765354479.jpg',
            'caption' => 'नेहरू युवा केंद्र द्वारा जिला युवा सम्मेलन आयोजित',
            'date' => '10 April 2024',
        ],
        [
            'src' => '/uploads/media/images/media_1765431011.jpg',
            'caption' => 'news',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431046.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431085.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431122.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431175.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431308.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431345.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431448.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431484.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765360128.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765360218.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765360541.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765361636.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765361822.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765361863.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765361904.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765429704.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765363697.jpg',
            'caption' => 'वर्षा जल संचय को लेकर नेहरू युवा केंद्र ने किया वेबिनार',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765364369.jpg',
            'caption' => 'स्वस्थ जीवन का संकल्प',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765364627.jpg',
            'caption' => 'स्वस्थ जीवन का संकल्प',
            'date' => '06 January 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765365084.jpg',
            'caption' => 'Awareness Against Terrorism',
            'date' => '22 May 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1765428756.jpg',
            'caption' => 'Awareness Against Terrorism',
            'date' => '22 May 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1766034132.jpeg',
            'caption' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766034150.jpeg',
            'caption' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766034169.jpeg',
            'caption' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766034214.jpeg',
            'caption' => 'विकसित भारत युवा संसद महोत्सव संपन्न',
            'date' => '08 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766171930.png',
            'caption' => '“साहित्य केवल सौंदर्यबोध का माध्यम नहीं”',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1767690493.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690507.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690533.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
    ];
    return mvc_get_page_array(288, 'ec_nss_data', $default);
}

function mvc_render_ec_nss() {
    $nss_items = mvc_get_ec_nss_data();
    $upload_base = home_url('/wp-content');
    ?>
    <!-- NSS Coordinator Split Card -->
    <div class="mvc-ec-officer-grid">
        <div class="mvc-ec-officer-card">
            <img src="<?php echo esc_url($upload_base . '/uploads/nss/NSS_1764658519_NSS_1757936647_NSS_1747047941_nsslogo.png'); ?>" alt="NSS Emblem" class="mvc-ec-dept-logo" />
            <img src="<?php echo esc_url($upload_base . '/uploads/faculty-images/Profile_1764589048.png'); ?>" alt="Dr. Birendra Prasad" class="mvc-ec-officer-photo" />
            <h3 class="mvc-ec-officer-name">Dr. Birendra Prasad</h3>
            <div class="mvc-ec-officer-email">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>mvcollegebuxar@gmail.com</span>
            </div>
            <div class="mvc-ec-officer-designation">Programme Officer, National Service Scheme</div>
        </div>

        <div class="mvc-ec-officer-narrative">
            <h3>About National Service Scheme (NSS Unit)</h3>
            <p>
                The NSS Unit of Maharshi Vishwamitra Mahavidyalaya is very active in conducting social activities of National & International importance. The College NSS team regularly visits surrounding areas and villages where people are awakened and made aware of various social, moral, and ethical principles of life.
            </p>
            <p>
                The students are motivated by way of special lectures so as to instil moral and ethical values in them. The voluntary organizations NSS have made an annual plan for the social activities under the guidance of our <strong>Dynamic and Innovative Principal</strong> in the outset of the academic session.
            </p>
            <p>
                The Volunteers of NSS take out rallies and spread awareness about AIDS and other social evils, organize special residential camps in different rural areas, execute cleanliness drives (Swachhata Abhiyan), tree plantation initiatives, and disaster management relief camps.
            </p>
        </div>
    </div>

    <!-- NSS Governance Table -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>👥 NSS Unit Leadership</span>
            </h2>
            <span class="mvc-ec-badge">Programme Direction</span>
        </div>
        <div class="mvc-ec-table-wrap">
            <table class="mvc-ec-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Sr No</th>
                        <th>Member Name</th>
                        <th>Designation</th>
                        <th>Contact Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Sr No" style="text-align: center; font-weight: 700;">01</td>
                        <td data-label="Member Name" style="font-weight: 700; color: var(--mvc-navy);">Dr. Birendra Prasad</td>
                        <td data-label="Designation"><span class="mvc-ec-badge">Programme Officer</span></td>
                        <td data-label="Contact Email">mvcollegebuxar@gmail.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- NSS Activities Photo Gallery -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>📸 NSS Community Camps & Event Gallery</span>
            </h2>
            <span class="mvc-ec-badge"><?php echo count($nss_items); ?> Documented Events</span>
        </div>

        <div class="mvc-ec-gallery-grid">
            <?php foreach ($nss_items as $item): 
                $img_url = home_url('/wp-content' . $item['src']);
            ?>
                <div class="mvc-ec-gallery-card">
                    <div class="mvc-ec-gallery-thumb-wrap" onclick="mvcOpenLightbox('<?php echo esc_url($img_url); ?>', '<?php echo esc_js($item['caption']); ?>', '<?php echo esc_js($item['date']); ?>')">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item['caption']); ?>" class="mvc-ec-gallery-thumb" loading="lazy" />
                        <div class="mvc-ec-gallery-overlay">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span>Zoom Photo</span>
                        </div>
                    </div>
                    <div class="mvc-ec-gallery-info">
                        <h4 class="mvc-ec-gallery-title"><?php echo esc_html($item['caption']); ?></h4>
                        <div class="mvc-ec-gallery-meta">
                            <span class="mvc-ec-gallery-date"><?php echo esc_html($item['date'] ?: 'NSS Fieldwork'); ?></span>
                            <a href="<?php echo esc_url($img_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-outline" style="padding: 4px 8px; font-size: 11.5px;">
                                View ↗
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * 4. NCC RENDERER
 * ANO Officer Card, Tri-Services Military Charter & 37 Cadet Drills/Camps
 */
function mvc_get_ec_ncc_data() {
    $default = [
        [
            'src' => '/uploads/image_gallery/gallery_6948f6dd1d11c.jpeg',
            'caption' => 'Previous',
            'date' => '',
        ],
        [
            'src' => '/uploads/media/images/media_1764143117.jpg',
            'caption' => 'National Cadet Corps (NCC) Press Release',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1764143145.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144416.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142784.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143225.jpg',
            'caption' => 'Internship Completed by MV College',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143718.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143822.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142493.jpg',
            'caption' => 'Sports Festival Scheduled at Maharshi Vishwamitra College',
            'date' => '01 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142690.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143317.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142955.jpg',
            'caption' => 'Back to Class – Build Your Future',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143627.jpg',
            'caption' => 'In Memory',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144257.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144355.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765351751.jpg',
            'caption' => 'शिक्षा में अनुसंधान पर विशेष ज़ोर',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765352588.jpg',
            'caption' => 'भारतीय शिक्षण मंडल की महत्वपूर्ण बैठक',
            'date' => '20 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353427.jpg',
            'caption' => 'जनसंख्या में भारत का विश्व में दूसरा स्थान',
            'date' => '12 July 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1765353551.jpg',
            'caption' => 'भारतीय मूल्यों पर आधारित उच्च शिक्षा',
            'date' => '28 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353840.jpg',
            'caption' => 'Rashtriya Sahara',
            'date' => '22 June 2022',
        ],
        [
            'src' => '/uploads/media/images/media_1765354479.jpg',
            'caption' => 'नेहरू युवा केंद्र द्वारा जिला युवा सम्मेलन आयोजित',
            'date' => '10 April 2024',
        ],
        [
            'src' => '/uploads/media/images/media_1765431011.jpg',
            'caption' => 'news',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431046.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431085.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431122.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431175.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431308.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431345.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431448.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431484.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765429704.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765363697.jpg',
            'caption' => 'वर्षा जल संचय को लेकर नेहरू युवा केंद्र ने किया वेबिनार',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765364369.jpg',
            'caption' => 'स्वस्थ जीवन का संकल्प',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766171930.png',
            'caption' => '“साहित्य केवल सौंदर्यबोध का माध्यम नहीं”',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1767690493.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690507.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690533.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
    ];
    return mvc_get_page_array(289, 'ec_ncc_data', $default);
}

function mvc_render_ec_ncc() {
    $ncc_items = mvc_get_ec_ncc_data();
    $upload_base = home_url('/wp-content');
    ?>
    <!-- NCC Coordinator Split Card -->
    <div class="mvc-ec-officer-grid">
        <div class="mvc-ec-officer-card">
            <img src="<?php echo esc_url($upload_base . '/uploads/ncc/NCC_1764658971_ncc-logo.jpg'); ?>" alt="NCC Crest" class="mvc-ec-dept-logo" />
            <img src="<?php echo esc_url($upload_base . '/uploads/faculty-images/Profile_1764231326.png'); ?>" alt="Dr. Yogershi Rajpoot" class="mvc-ec-officer-photo" />
            <h3 class="mvc-ec-officer-name">Dr. Yogershi Rajpoot</h3>
            <div class="mvc-ec-officer-email">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>yogirshi@gmail.com</span>
            </div>
            <div class="mvc-ec-officer-designation">ANO, National Cadet Corps (NCC)</div>
        </div>

        <div class="mvc-ec-officer-narrative">
            <h3>About National Cadet Corps (NCC)</h3>
            <p>
                The NCC is an Indian military cadet corps with its Headquarters at New Delhi, India. It is a Tri-Services Organization, comprising the Army, Navy and Air Force, engaged in grooming the youth of the country into disciplined and patriotic citizens.
            </p>
            <p>
                The National Cadet Corps is a voluntary organization which recruits cadets from high schools, colleges, and universities all over India. The Cadets are given basic military training in small arms and parades.
            </p>
            <p>
                Over the years, the NCC cadets of Maharshi Vishwamitra Mahavidyalaya, Buxar have performed well, particularly in promotion of human values, discipline, spirit of unity, and national integration, actively taking part in Republic Day camps, Combined Annual Training Camps (CATC), shooting competitions, and adventure expeditions.
            </p>
        </div>
    </div>

    <!-- NCC Committee Table -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>👥 NCC Unit Command</span>
            </h2>
            <span class="mvc-ec-badge">Military Wing Command</span>
        </div>
        <div class="mvc-ec-table-wrap">
            <table class="mvc-ec-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Sr No</th>
                        <th>Member Name</th>
                        <th>Designation</th>
                        <th>Official Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Sr No" style="text-align: center; font-weight: 700;">01</td>
                        <td data-label="Member Name" style="font-weight: 700; color: var(--mvc-navy);">Dr. Yogershi Rajpoot</td>
                        <td data-label="Designation"><span class="mvc-ec-badge">ANO (Associate NCC Officer)</span></td>
                        <td data-label="Official Email">yogirshi@gmail.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- NCC Cadet Activities Gallery -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>🎖️ Cadet Training, Parades & Camp Gallery</span>
            </h2>
            <span class="mvc-ec-badge"><?php echo count($ncc_items); ?> Documented Events</span>
        </div>

        <div class="mvc-ec-gallery-grid">
            <?php foreach ($ncc_items as $item): 
                $img_url = home_url('/wp-content' . $item['src']);
            ?>
                <div class="mvc-ec-gallery-card">
                    <div class="mvc-ec-gallery-thumb-wrap" onclick="mvcOpenLightbox('<?php echo esc_url($img_url); ?>', '<?php echo esc_js($item['caption']); ?>', '<?php echo esc_js($item['date']); ?>')">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item['caption']); ?>" class="mvc-ec-gallery-thumb" loading="lazy" />
                        <div class="mvc-ec-gallery-overlay">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span>Zoom Photo</span>
                        </div>
                    </div>
                    <div class="mvc-ec-gallery-info">
                        <h4 class="mvc-ec-gallery-title"><?php echo esc_html($item['caption']); ?></h4>
                        <div class="mvc-ec-gallery-meta">
                            <span class="mvc-ec-gallery-date"><?php echo esc_html($item['date'] ?: 'Cadet Training'); ?></span>
                            <a href="<?php echo esc_url($img_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-outline" style="padding: 4px 8px; font-size: 11.5px;">
                                View ↗
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * 5. SPORTS RENDERER
 * Sports Coordinator Card, Mission, Facilities Inventory & 53 Athletic Events
 */
function mvc_get_ec_sports_data() {
    $default = [
        [
            'src' => '/uploads/image_gallery/gallery_69836ae9ef7bb.JPG',
            'caption' => 'Previous',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6948f6dd1d11c.jpeg',
            'caption' => 'Sport Members',
            'date' => '31 January 2026',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_694388eae6b2a.jpeg',
            'caption' => 'Badminton Practice Session',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_695e2100e70d3.jpeg',
            'caption' => 'क्रिकेट प्रतियोगिता 2025–26',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_696156a90ce8b.jpeg',
            'caption' => 'Inter College Cricket Final Match Runner-Up Team',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_697db087a0359.jpeg',
            'caption' => 'खेल महोत्सव-2026',
            'date' => '',
        ],
        [
            'src' => '/uploads/media/images/media_1764143117.jpg',
            'caption' => 'Sports Press Release',
            'date' => '31 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1764143145.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144416.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142784.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143225.jpg',
            'caption' => 'Internship Completed by MV College',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143718.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143822.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142493.jpg',
            'caption' => 'Sports Festival Scheduled at Maharshi Vishwamitra College',
            'date' => '01 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142690.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143317.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142955.jpg',
            'caption' => 'Back to Class – Build Your Future',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143627.jpg',
            'caption' => 'In Memory',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144257.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144355.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765351751.jpg',
            'caption' => 'शिक्षा में अनुसंधान पर विशेष ज़ोर',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765352588.jpg',
            'caption' => 'भारतीय शिक्षण मंडल की महत्वपूर्ण बैठक',
            'date' => '20 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353427.jpg',
            'caption' => 'जनसंख्या में भारत का विश्व में दूसरा स्थान',
            'date' => '12 July 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1765353551.jpg',
            'caption' => 'भारतीय मूल्यों पर आधारित उच्च शिक्षा',
            'date' => '28 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353840.jpg',
            'caption' => 'Rashtriya Sahara',
            'date' => '22 June 2022',
        ],
        [
            'src' => '/uploads/media/images/media_1765354479.jpg',
            'caption' => 'नेहरू युवा केंद्र द्वारा जिला युवा सम्मेलन आयोजित',
            'date' => '10 April 2024',
        ],
        [
            'src' => '/uploads/media/images/media_1765431011.jpg',
            'caption' => 'news',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431046.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431085.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431122.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431175.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431308.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431345.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431448.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431484.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765429704.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765363697.jpg',
            'caption' => 'वर्षा जल संचय को लेकर नेहरू युवा केंद्र ने किया वेबिनार',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765364369.jpg',
            'caption' => 'स्वस्थ जीवन का संकल्प',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766171930.png',
            'caption' => '“साहित्य केवल सौंदर्यबोध का माध्यम नहीं”',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1767690493.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690507.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690533.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769060566.jpeg',
            'caption' => 'Grand Three-Day Sports Competition Organized at M.V. College',
            'date' => '22 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769060585.jpeg',
            'caption' => 'Grand Three-Day Sports Competition Organized at M.V. College',
            'date' => '22 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769060598.jpeg',
            'caption' => 'Grand Three-Day Sports Competition Organized at M.V. College',
            'date' => '22 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769752525.jpeg',
            'caption' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769752541.jpeg',
            'caption' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769752557.jpeg',
            'caption' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846382.jpeg',
            'caption' => 'खेल महोत्सव Day - 2',
            'date' => '30 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846728.jpeg',
            'caption' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846847.jpeg',
            'caption' => 'खेल महोत्सव Day - 1',
            'date' => '29 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1769846902.jpeg',
            'caption' => 'खेल महोत्सव Day - 2',
            'date' => '30 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1770269406.jpeg',
            'caption' => 'खेल महोत्सव Day - 3',
            'date' => '31 January 2026',
        ],
    ];
    return mvc_get_page_array(290, 'ec_sports_data', $default);
}

function mvc_render_ec_sports() {
    $sports_items = mvc_get_ec_sports_data();
    $upload_base = home_url('/wp-content');
    ?>
    <!-- Sports Coordinator Split Card -->
    <div class="mvc-ec-officer-grid">
        <div class="mvc-ec-officer-card">
            <img src="<?php echo esc_url($upload_base . '/uploads/sports/SPORTS_1764659021_sports.png'); ?>" alt="Sports Emblem" class="mvc-ec-dept-logo" />
            <img src="<?php echo esc_url($upload_base . '/uploads/faculty-images/Profile_1764588422.png'); ?>" alt="Dr. Awanish Kumar Pandey" class="mvc-ec-officer-photo" />
            <h3 class="mvc-ec-officer-name">Dr. Awanish Kumar Pandey</h3>
            <div class="mvc-ec-officer-email">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>mvcollegebuxar@gmail.com</span>
            </div>
            <div class="mvc-ec-officer-designation">Sports Coordinator, Sports & Games</div>
        </div>

        <div class="mvc-ec-officer-narrative">
            <h3>About Sports & Physical Education</h3>
            <p>
                At Maharshi Vishwamitra Mahavidyalaya, Buxar, we believe that sports and physical activity are essential components of a well-rounded education. Our mission is to promote a culture of fitness and well-being among our students, providing ample opportunities to engage in various competitive and recreational disciplines.
            </p>
            <p>
                We foster teamwork, leadership, resilience, and high sportsmanship among our student-athletes. The Department actively supports skill development in badminton, volleyball, cricket, athletics, yoga, and martial arts, encouraging a healthy balance between rigorous academic pursuits and physical excellence.
            </p>
            <p>
                The college regularly hosts inter-departmental tournaments, inter-college championships, and the flagship <strong>Annual Three-Day Sports Festival (खेल महोत्सव)</strong>, drawing talent from across the state.
            </p>
        </div>
    </div>

    <!-- Sports Committee Table -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>👥 Sports Committee</span>
            </h2>
            <span class="mvc-ec-badge">Athletic Council</span>
        </div>
        <div class="mvc-ec-table-wrap">
            <table class="mvc-ec-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Sr No</th>
                        <th>Member Name</th>
                        <th>Designation</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Sr No" style="text-align: center; font-weight: 700;">01</td>
                        <td data-label="Member Name" style="font-weight: 700; color: var(--mvc-navy);">Dr. Awanish Kumar Pandey</td>
                        <td data-label="Designation"><span class="mvc-ec-badge">Sports Coordinator</span></td>
                        <td data-label="Email">mvcollegebuxar@gmail.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sports Event Photo Gallery -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>🏆 Sports Championships & Athletic Meets</span>
            </h2>
            <span class="mvc-ec-badge"><?php echo count($sports_items); ?> Documented Events</span>
        </div>

        <div class="mvc-ec-gallery-grid">
            <?php foreach ($sports_items as $item): 
                $img_url = home_url('/wp-content' . $item['src']);
            ?>
                <div class="mvc-ec-gallery-card">
                    <div class="mvc-ec-gallery-thumb-wrap" onclick="mvcOpenLightbox('<?php echo esc_url($img_url); ?>', '<?php echo esc_js($item['caption']); ?>', '<?php echo esc_js($item['date']); ?>')">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item['caption']); ?>" class="mvc-ec-gallery-thumb" loading="lazy" />
                        <div class="mvc-ec-gallery-overlay">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span>Zoom Photo</span>
                        </div>
                    </div>
                    <div class="mvc-ec-gallery-info">
                        <h4 class="mvc-ec-gallery-title"><?php echo esc_html($item['caption']); ?></h4>
                        <div class="mvc-ec-gallery-meta">
                            <span class="mvc-ec-gallery-date"><?php echo esc_html($item['date'] ?: 'Sports Championship'); ?></span>
                            <a href="<?php echo esc_url($img_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-outline" style="padding: 4px 8px; font-size: 11.5px;">
                                View ↗
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * 6. CULTURAL ACTIVITIES RENDERER
 * Cultural Coordinator Card, Arts Council Charter, International Seminar & 71 Events
 */
function mvc_get_ec_cultural_data() {
    $default = [
        [
            'src' => '/uploads/image_gallery/gallery_6a040d03045e5.jpg',
            'caption' => 'Previous',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_69231f97bac9c.jpg',
            'caption' => 'Cultural Activities Members',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_692320d740388.jpg',
            'caption' => 'Class Room',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6923209f30af9.jpg',
            'caption' => 'Yoga Day',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6923fb0e05309.jpg',
            'caption' => 'Independence Day',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6925991a1a0e0.jpeg',
            'caption' => 'Inter College Kabaddi Competition',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_692688f76ce23.jpeg',
            'caption' => 'National Cadet Corps (NCC)',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_69268d402d6ef.jpeg',
            'caption' => 'Condolences on Maharaj Chandravijay Singh Death',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6948f6dd1d11c.jpeg',
            'caption' => 'Multi Purpose Sports',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_69394a79f224b.jpg',
            'caption' => 'जिला युवा उत्सव 2022–23',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_695b6f5c6ebe2.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6961560158d3d.jpeg',
            'caption' => 'Optical Sensor For Medical Life',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_69a290db44a13.jpeg',
            'caption' => 'Science Day Celebration',
            'date' => '',
        ],
        [
            'src' => '/uploads/image_gallery/gallery_6a040bce1bb43.png',
            'caption' => 'Two Day International Seminar on Indian Knowledge Tradition',
            'date' => '',
        ],
        [
            'src' => '/uploads/media/images/media_1764143117.jpg',
            'caption' => 'Cultural Activities Press Release',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1764143145.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144416.jpg',
            'caption' => 'Yoga: A Way of Discipline',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142083.jpeg',
            'caption' => 'Kabaddi Pratiyogita',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142236.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142784.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143225.jpg',
            'caption' => 'Internship Completed by MV College',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143718.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143822.jpg',
            'caption' => 'Internship Program',
            'date' => '09 October 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142493.jpg',
            'caption' => 'Sports Festival Scheduled at Maharshi Vishwamitra College',
            'date' => '01 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142690.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143317.jpg',
            'caption' => 'Stress & Suicide',
            'date' => '19 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764142955.jpg',
            'caption' => 'Back to Class – Build Your Future',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764143627.jpg',
            'caption' => 'In Memory',
            'date' => '26 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144257.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1764144355.jpg',
            'caption' => 'Knowledge & Values',
            'date' => '20 November 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765351751.jpg',
            'caption' => 'शिक्षा में अनुसंधान पर विशेष ज़ोर',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765352588.jpg',
            'caption' => 'भारतीय शिक्षण मंडल की महत्वपूर्ण बैठक',
            'date' => '20 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353427.jpg',
            'caption' => 'जनसंख्या में भारत का विश्व में दूसरा स्थान',
            'date' => '12 July 2018',
        ],
        [
            'src' => '/uploads/media/images/media_1765353551.jpg',
            'caption' => 'भारतीय मूल्यों पर आधारित उच्च शिक्षा',
            'date' => '28 November 2020',
        ],
        [
            'src' => '/uploads/media/images/media_1765353840.jpg',
            'caption' => 'Rashtriya Sahara',
            'date' => '22 June 2022',
        ],
        [
            'src' => '/uploads/media/images/media_1765354479.jpg',
            'caption' => 'नेहरू युवा केंद्र द्वारा जिला युवा सम्मेलन आयोजित',
            'date' => '10 April 2024',
        ],
        [
            'src' => '/uploads/media/images/media_1765431011.jpg',
            'caption' => 'news',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431046.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431085.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431122.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431175.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431308.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431345.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431448.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765431484.jpg',
            'caption' => 'News',
            'date' => '11 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765429704.jpg',
            'caption' => 'विशेष आवासीय शिविर (Special Residential Camp)',
            'date' => '25 December 2017',
        ],
        [
            'src' => '/uploads/media/images/media_1765363697.jpg',
            'caption' => 'वर्षा जल संचय को लेकर नेहरू युवा केंद्र ने किया वेबिनार',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1765364072.jpg',
            'caption' => 'सिद्धाश्रम साहित्य परिषद्',
            'date' => '05 July 2022',
        ],
        [
            'src' => '/uploads/media/images/media_1765364369.jpg',
            'caption' => 'स्वस्थ जीवन का संकल्प',
            'date' => '10 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766171625.jpeg',
            'caption' => 'literature-beyond-aesthetic-appreciation',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1766171930.png',
            'caption' => '“साहित्य केवल सौंदर्यबोध का माध्यम नहीं”',
            'date' => '19 December 2025',
        ],
        [
            'src' => '/uploads/media/images/media_1767690493.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690507.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1767690533.jpeg',
            'caption' => 'Official Website Inauguration',
            'date' => '05 January 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650097.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650119.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650135.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650165.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650192.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650205.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778650225.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736370.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736393.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736412.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736428.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736451.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736504.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736521.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736562.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736640.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
        [
            'src' => '/uploads/media/images/media_1778736655.jpeg',
            'caption' => 'Two-Day International Seminar on Indian Knowledge Tradition',
            'date' => '12 May 2026',
        ],
    ];
    return mvc_get_page_array(291, 'ec_cultural_data', $default);
}

function mvc_render_ec_cultural() {
    $cultural_items = mvc_get_ec_cultural_data();
    $upload_base = home_url('/wp-content');
    ?>
    <!-- Cultural Coordinator Split Card -->
    <div class="mvc-ec-officer-grid">
        <div class="mvc-ec-officer-card">
            <img src="<?php echo esc_url($upload_base . '/uploads/cultural/cultural-logo.png'); ?>" alt="Cultural Emblem" class="mvc-ec-dept-logo" />
            <img src="<?php echo esc_url($upload_base . '/uploads/faculty-images/Profile_1764589583.png'); ?>" alt="Dr. Arvind Verma" class="mvc-ec-officer-photo" />
            <h3 class="mvc-ec-officer-name">Dr. Arvind Verma</h3>
            <div class="mvc-ec-officer-email">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>abhivemm@gmail.com</span>
            </div>
            <div class="mvc-ec-officer-designation">Co-ordinator, Cultural Activities</div>
        </div>

        <div class="mvc-ec-officer-narrative">
            <h3>About Cultural Activities</h3>
            <p>
                Welcome to the vibrant world of cultural activities at Maharshi Vishwamitra Mahavidyalaya, Buxar. We are committed to celebrating diversity, nurturing creativity, and fostering a deep appreciation for the arts and culture among our students.
            </p>
            <p>
                Our mission is to create a platform for artistic expression and creativity, encouraging the exploration of classical music, folk dance, fine arts, theatrical performance, debate, and literary traditions. We provide opportunities for students to showcase their talents and connect with the larger community.
            </p>
            <p>
                The College proudly organizes high-impact national and international academic symposiums, including the <strong>Two-Day International Seminar on Indian Knowledge Tradition</strong>, Siddhashram Sahitya Parishad assemblies, youth parliaments, and annual foundation celebrations.
            </p>
        </div>
    </div>

    <!-- Cultural Committee Table -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>👥 Cultural Council</span>
            </h2>
            <span class="mvc-ec-badge">Artistic Governance</span>
        </div>
        <div class="mvc-ec-table-wrap">
            <table class="mvc-ec-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Sr No</th>
                        <th>Member Name</th>
                        <th>Designation</th>
                        <th>Official Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Sr No" style="text-align: center; font-weight: 700;">01</td>
                        <td data-label="Member Name" style="font-weight: 700; color: var(--mvc-navy);">Dr. Arvind Verma</td>
                        <td data-label="Designation"><span class="mvc-ec-badge">Co-ordinator</span></td>
                        <td data-label="Official Email">abhivemm@gmail.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cultural Events Photo Gallery -->
    <div class="mvc-ec-card">
        <div class="mvc-ec-section-title">
            <h2>
                <span>🎭 Festivals, Seminars & Cultural Gallery</span>
            </h2>
            <span class="mvc-ec-badge"><?php echo count($cultural_items); ?> Documented Events</span>
        </div>

        <div class="mvc-ec-gallery-grid">
            <?php foreach ($cultural_items as $item): 
                $img_url = home_url('/wp-content' . $item['src']);
            ?>
                <div class="mvc-ec-gallery-card">
                    <div class="mvc-ec-gallery-thumb-wrap" onclick="mvcOpenLightbox('<?php echo esc_url($img_url); ?>', '<?php echo esc_js($item['caption']); ?>', '<?php echo esc_js($item['date']); ?>')">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($item['caption']); ?>" class="mvc-ec-gallery-thumb" loading="lazy" />
                        <div class="mvc-ec-gallery-overlay">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span>Zoom Photo</span>
                        </div>
                    </div>
                    <div class="mvc-ec-gallery-info">
                        <h4 class="mvc-ec-gallery-title"><?php echo esc_html($item['caption']); ?></h4>
                        <div class="mvc-ec-gallery-meta">
                            <span class="mvc-ec-gallery-date"><?php echo esc_html($item['date'] ?: 'Cultural Event'); ?></span>
                            <a href="<?php echo esc_url($img_url); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-outline" style="padding: 4px 8px; font-size: 11.5px;">
                                View ↗
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
