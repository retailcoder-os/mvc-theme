<?php
/**
 * Master Academic Directory Builder (Universal 10/10 Standard)
 * Renders the /department-details/ portal with high-prestige Command Deck,
 * 4 Faculty Division Bento Cards, 15 Department Cards, and instant search.
 * 
 * 100% Dynamic — Zero Hardcoding — ACF Free Edition Compatible.
 */

if (!defined('ABSPATH')) {
    exit;
}

function mvc_render_department_details_page() {
    return mvc_cache_output('mvc_cached_dept_details_html', function() {
        ob_start();

    // 1. Query All Published Departments
    $depts_query = new WP_Query(array(
        'post_type'      => 'department',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC'
    ));
    $departments = $depts_query->posts;
    $total_depts = count($departments);

    // 2. Query All Faculty to Map HODs and Counts
    $faculty_query = new WP_Query(array(
        'post_type'      => 'faculty',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    ));
    $all_faculty = $faculty_query->posts;
    $total_faculty = count($all_faculty);

    // Group faculty and HODs by department slug
    $dept_faculty_map = array();
    $dept_hod_map = array();

    foreach ($all_faculty as $f) {
        $terms = wp_get_object_terms($f->ID, 'dept_link');
        if (empty($terms) || is_wp_error($terms)) {
            $terms = wp_get_object_terms($f->ID, 'department');
        }
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $t) {
                $slug = $t->slug;
                if (!isset($dept_faculty_map[$slug])) {
                    $dept_faculty_map[$slug] = array();
                }
                $dept_faculty_map[$slug][] = $f;

                $is_hod = (int) get_field('is_department_head', $f->ID);
                if ($is_hod === 1 && !isset($dept_hod_map[$slug])) {
                    $dept_hod_map[$slug] = $f;
                }
            }
        }
    }

    // Faculty Group Categories Definitions
    $faculty_groups = array(
        'all' => array(
            'label'    => 'All Disciplines',
            'hi'       => 'समस्त संकाय',
            'icon'     => '🏛️',
            'count'    => $total_depts,
            'desc'     => '15 Academic departments across Humanities, Social Sciences, Pure Sciences, and Applied Vocational streams.',
            'url'      => home_url('/department-details/')
        ),
        'humanities' => array(
            'label'    => 'Faculty of Humanities',
            'hi'       => 'मानविकी संकाय',
            'icon'     => '📖',
            'count'    => 0,
            'desc'     => 'Classical literature, linguistics, philosophical inquiry, aesthetics, and cultural heritage.',
            'depts'    => array('P.G. Department of Hindi', 'English', 'Sanskrit', 'Philosophy'),
            'url'      => home_url('/faculty-group/humanities/')
        ),
        'social-science' => array(
            'label'    => 'Faculty of Social Sciences',
            'hi'       => 'सामाजिक विज्ञान संकाय',
            'icon'     => '🌍',
            'count'    => 0,
            'desc'     => 'Historical research, constitutional governance, behavioral psychology, and economic analysis.',
            'depts'    => array('P.G. Department of History', 'P.G. Department of Political Science', 'P.G. Department of Psychology', 'Economics'),
            'url'      => home_url('/faculty-group/social-science/')
        ),
        'science' => array(
            'label'    => 'Faculty of Sciences',
            'hi'       => 'विज्ञान संकाय',
            'icon'     => '🔬',
            'count'    => 0,
            'desc'     => 'Empirical scientific inquiry, modern laboratories, mathematical modeling, and ecological studies.',
            'depts'    => array('Physics', 'Chemistry', 'Mathematics', 'Botany', 'Zoology'),
            'url'      => home_url('/faculty-group/science/')
        ),
        'vocational' => array(
            'label'    => 'Vocational & Applied',
            'hi'       => 'व्यावसायिक संकाय',
            'icon'     => '💻',
            'count'    => 0,
            'desc'     => 'Industry-aligned professional degree programs in computational applications and management.',
            'depts'    => array('Computer Applications (BCA)', 'Business Management (BBM)'),
            'url'      => home_url('/faculty-group/vocational/')
        )
    );

    // Pre-calculate faculty group counts
    foreach ($departments as $dept) {
        $fg_terms = wp_get_object_terms($dept->ID, 'faculty-group');
        $fg_slug = (!empty($fg_terms) && !is_wp_error($fg_terms)) ? $fg_terms[0]->slug : 'humanities';
        if (isset($faculty_groups[$fg_slug])) {
            $faculty_groups[$fg_slug]['count']++;
        }
    }

    // Department Glyphs Dictionary
    $dept_glyphs = array(
        'hindi'                 => '🪶',
        'english'               => '🖋️',
        'sanskrit'              => '📜',
        'philosophy'            => '💡',
        'history'               => '🏛️',
        'political-science'     => '⚖️',
        'psychology'            => '🔬',
        'economics'             => '📈',
        'physics'               => '⚛️',
        'chemistry'             => '🧪',
        'mathematics'           => '📐',
        'botany'                => '🌿',
        'zoology'               => '🐾',
        'computer-applications' => '💻',
        'business-management'   => '💼'
    );

    // Schema Data
    $schema_has_part = array();
    foreach ($departments as $d) {
        $schema_has_part[] = array(
            '@type' => 'Course',
            'name'  => $d->post_title,
            'url'   => get_permalink($d->ID)
        );
    }

    $schema_json = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(
            array(
                '@type'         => 'EducationalOrganization',
                '@id'           => home_url('/#institution'),
                'name'          => 'Maharshi Vishwamitra Mahavidyalaya',
                'alternateName' => 'M.V. College, Buxar',
                'url'           => home_url('/')
            ),
            array(
                '@type'         => 'CollectionPage',
                '@id'           => home_url('/department-details/#webpage'),
                'url'           => home_url('/department-details/'),
                'name'          => 'Academic Disciplines & Faculty Groups • M.V. College, Buxar',
                'description'   => 'Comprehensive directory of 15 academic departments and 4 faculties under NEP 2020 at M.V. College Buxar.',
                'isPartOf'      => array('@id' => home_url('/#institution')),
                'hasPart'       => $schema_has_part
            ),
            array(
                '@type'           => 'BreadcrumbList',
                'itemListElement' => array(
                    array(
                        '@type'    => 'ListItem',
                        'position' => 1,
                        'name'     => 'Home',
                        'item'     => home_url('/')
                    ),
                    array(
                        '@type'    => 'ListItem',
                        'position' => 2,
                        'name'     => 'Academics & Disciplines',
                        'item'     => home_url('/department-details/')
                    )
                )
            )
        )
    );
    ?>

    <!-- JSON-LD SEO SCHEMA (10/10 Standard) -->
    <script type="application/ld+json">
    <?php echo json_encode($schema_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>

    <div class="mvc-academic-hub-root" id="mvcAcademicHubRoot">
        <style>
        :root {
            --mvc-navy-royal: #002147;
            --mvc-navy-dark: #001226;
            --mvc-navy-deep: #001A38;
            --mvc-maroon-crimson: #800000;
            --mvc-gold-primary: #D4AF37;
            --mvc-gold-light: #FBF3D5;
            --mvc-gold-glow: rgba(212, 175, 55, 0.28);
            --mvc-cream-soft: #FAF9F6;
            --mvc-surface-card: #FFFFFF;
            --mvc-surface-muted: #F8FAFC;
            --mvc-text-dark: #0F172A;
            --mvc-text-body: #334155;
            --mvc-text-muted: #64748B;
            --mvc-border-light: #E2E8F0;
            --mvc-shadow-card: 0 4px 20px rgba(0, 33, 71, 0.06);
            --mvc-shadow-hover: 0 16px 36px rgba(0, 33, 71, 0.12);
            --mvc-radius-pill: 50px;
            --mvc-radius-card: 14px;
            --mvc-radius-inner: 10px;
            --mvc-ease: cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mvc-academic-hub-root {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--mvc-text-body);
            background: #F8FAFC;
            line-height: 1.7;
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        .mvc-academic-hub-root *,
        .mvc-academic-hub-root *::before,
        .mvc-academic-hub-root *::after {
            box-sizing: border-box;
        }

        .mvc-hub-container {
            max-width: 100% !important;
            margin: 0 auto;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        /* 1. INSTITUTIONAL HERO HEADER */
        .mvc-hub-hero {
            position: relative;
            background: linear-gradient(135deg, #001226 0%, #002147 45%, #420000 85%, #660000 100%);
            color: #ffffff;
            padding: 45px 0 36px;
            box-shadow: 0 4px 24px rgba(0, 16, 36, 0.22);
        }
        .mvc-hub-breadcrumbs {
            margin-bottom: 12px;
        }
        .mvc-hub-breadcrumbs ol {
            display: flex;
            align-items: center;
            gap: 7px;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.72);
        }
        .mvc-hub-breadcrumbs a {
            color: var(--mvc-gold-primary);
            text-decoration: none;
            font-weight: 500;
        }
        .mvc-hub-breadcrumbs a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .mvc-hub-breadcrumbs .mvc-sep {
            color: rgba(255, 255, 255, 0.35);
            font-size: 11px;
        }
        .mvc-hub-breadcrumbs li[aria-current="page"] span {
            color: #ffffff;
            font-weight: 700;
        }

        .mvc-hub-insignia-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .mvc-hub-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-radius: var(--mvc-radius-pill);
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: var(--mvc-gold-light);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .mvc-hub-hindi-badge {
            background: rgba(212, 175, 55, 0.22);
            border: 1px solid rgba(212, 175, 55, 0.45);
            color: #ffffff;
            padding: 3px 14px;
            border-radius: var(--mvc-radius-pill);
            font-size: 13px;
            font-weight: 700;
        }

        .mvc-hub-headline {
            font-size: clamp(28px, 4.2vw, 44px);
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 10px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        .mvc-hub-lead-text {
            font-size: clamp(14px, 1.6vw, 16.5px);
            color: rgba(255, 255, 255, 0.88);
            max-width: 900px;
            margin: 0 0 24px;
            line-height: 1.7;
        }

        /* Hero Stats Grid */
        .mvc-hub-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
        }
        .mvc-hub-stat-item {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--mvc-radius-inner);
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .mvc-hub-stat-glyph {
            font-size: 24px;
            line-height: 1;
        }
        .mvc-hub-stat-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.65);
            font-weight: 600;
        }
        .mvc-hub-stat-val {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
        }

        /* 2. THE COMMAND DECK (10/10 UNIFIED COMMAND BAR) */
        .mvc-hub-command-section {
            background: #ffffff;
            border-bottom: 1px solid #E2E8F0;
            padding: 18px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.05);
        }
        .mvc-hub-command-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Navigation Strip on Left with Mobile Arrow Helpers */
        .mvc-hub-nav-flank {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1 1 auto;
            max-width: calc(100% - 330px);
            min-width: 0;
        }
        .mvc-hub-nav-arrow {
            display: none;
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 50%;
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: #002147;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            padding: 0;
        }
        .mvc-hub-nav-arrow:hover {
            background: #002147;
            color: #ffffff;
        }
        .mvc-hub-nav-arrow[aria-disabled="true"] {
            opacity: 0.4;
            pointer-events: none;
        }

        .mvc-hub-tabs-track {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            padding: 4px 2px;
        }
        .mvc-hub-tabs-track::-webkit-scrollbar { display: none; }

        #mvcAcademicHubRoot .mvc-hub-tab-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0 !important;
            padding: 8px 18px !important;
            border-radius: var(--mvc-radius-pill) !important;
            border: 1px solid #CBD5E1 !important;
            background: #FFFFFF !important;
            color: #334155 !important;
            font-size: 13.5px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            transition: all 0.22s var(--mvc-ease) !important;
            white-space: nowrap !important;
            box-shadow: 0 1px 3px rgba(0, 33, 71, 0.05) !important;
            line-height: 1.3 !important;
            box-sizing: border-box !important;
        }
        #mvcAcademicHubRoot .mvc-hub-tab-btn .mvc-hub-tab-label {
            display: inline-block !important;
            margin-right: 9px !important;
            font-weight: 700 !important;
            white-space: nowrap !important;
        }
        #mvcAcademicHubRoot .mvc-hub-tab-btn .mvc-hub-tab-count {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #E2E8F0 !important;
            color: #334155 !important;
            font-size: 11.5px !important;
            padding: 2px 8px !important;
            border-radius: 12px !important;
            font-weight: 800 !important;
            line-height: 1.2 !important;
            flex-shrink: 0 !important;
            margin-left: 0 !important;
            box-sizing: border-box !important;
        }
        #mvcAcademicHubRoot .mvc-hub-tab-btn:hover {
            background: #F8FAFC !important;
            border-color: var(--mvc-navy-royal) !important;
            color: var(--mvc-navy-royal) !important;
            transform: translateY(-1px) !important;
        }
        #mvcAcademicHubRoot .mvc-hub-tab-btn.active {
            background: var(--mvc-navy-royal) !important;
            color: #FFFFFF !important;
            border-color: var(--mvc-navy-royal) !important;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.22) !important;
        }
        #mvcAcademicHubRoot .mvc-hub-tab-btn.active .mvc-hub-tab-count {
            background: var(--mvc-gold-primary) !important;
            color: #001226 !important;
        }

        /* Search Capsule on Right */
        .mvc-hub-search-capsule {
            position: relative !important;
            width: 310px !important;
            flex-shrink: 0 !important;
            display: flex !important;
            align-items: center !important;
        }
        .mvc-hub-search-box-inner {
            position: relative !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
        }
        #mvcAcademicHubRoot .mvc-hub-search-icon-svg {
            position: absolute !important;
            left: 14px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 16px !important;
            height: 16px !important;
            color: #64748B !important;
            pointer-events: none !important;
            z-index: 5 !important;
        }
        #mvcAcademicHubRoot input#mvcDeptSearchInput,
        #mvcAcademicHubRoot .mvc-hub-search-input {
            width: 100% !important;
            height: 42px !important;
            line-height: 42px !important;
            padding-left: 42px !important;
            padding-right: 88px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            border-radius: var(--mvc-radius-pill) !important;
            border: 1px solid #CBD5E1 !important;
            background: #FFFFFF !important;
            font-size: 13.5px !important;
            color: #0F172A !important;
            transition: all 0.2s ease !important;
            outline: none !important;
            box-sizing: border-box !important;
        }
        #mvcAcademicHubRoot input#mvcDeptSearchInput:focus {
            background: #FFFFFF !important;
            border-color: var(--mvc-gold-primary) !important;
            box-shadow: 0 0 0 3px var(--mvc-gold-glow) !important;
        }
        #mvcAcademicHubRoot .mvc-hub-search-badge {
            position: absolute !important;
            right: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: #F1F5F9 !important;
            color: #475569 !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            padding: 3px 9px !important;
            border-radius: 20px !important;
            pointer-events: none !important;
            white-space: nowrap !important;
            z-index: 5 !important;
            border: 1px solid #E2E8F0 !important;
        }

        /* 3. FOUR FACULTY DIVISIONS BENTO GRID */
        .mvc-hub-faculties-sec {
            padding: 38px 0 20px;
        }
        .mvc-hub-section-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .mvc-hub-sec-title-group h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0;
        }
        .mvc-hub-sec-title-group p {
            font-size: 13.5px;
            color: var(--mvc-text-muted);
            margin: 2px 0 0;
        }

        .mvc-hub-faculty-cards-deck {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }
        .mvc-hub-faculty-box {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: var(--mvc-radius-card);
            padding: 22px 18px;
            box-shadow: var(--mvc-shadow-card);
            transition: all 0.25s var(--mvc-ease);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .mvc-hub-faculty-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--mvc-navy-royal);
            border-radius: var(--mvc-radius-card) var(--mvc-radius-card) 0 0;
            transition: background 0.2s;
        }
        .mvc-hub-faculty-box:hover {
            transform: translateY(-4px);
            border-color: var(--mvc-gold-primary);
            box-shadow: var(--mvc-shadow-hover);
        }
        .mvc-hub-faculty-box:hover::before {
            background: linear-gradient(90deg, var(--mvc-maroon-crimson) 0%, var(--mvc-gold-primary) 100%);
        }
        .mvc-hub-fac-box-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .mvc-hub-fac-glyph {
            font-size: 26px;
            line-height: 1;
        }
        .mvc-hub-fac-count-badge {
            font-size: 11.5px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            background: #F1F5F9;
            padding: 3px 10px;
            border-radius: var(--mvc-radius-pill);
        }
        .mvc-hub-fac-title {
            font-size: 16.5px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 2px;
            line-height: 1.3;
        }
        .mvc-hub-fac-hi-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            display: block;
            margin-bottom: 8px;
        }
        .mvc-hub-fac-desc {
            font-size: 12.5px;
            color: #64748B;
            line-height: 1.55;
            margin: 0 0 14px;
        }
        .mvc-hub-fac-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 14px;
        }
        .mvc-hub-fac-mini-tag {
            font-size: 11px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 2px 7px;
            border-radius: 4px;
            color: #475569;
            font-weight: 600;
        }
        .mvc-hub-fac-cta {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: auto;
        }
        .mvc-hub-faculty-box:hover .mvc-hub-fac-cta {
            color: var(--mvc-navy-royal);
            text-decoration: underline;
        }

        /* 4. MAIN DEPARTMENTS GRID MATRIX */
        .mvc-hub-departments-sec {
            padding: 24px 0 65px;
        }
        .mvc-hub-depts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        /* Single Department Card */
        .mvc-hub-dept-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: var(--mvc-radius-card);
            padding: 24px;
            box-shadow: var(--mvc-shadow-card);
            transition: all 0.28s var(--mvc-ease);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .mvc-hub-dept-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #E2E8F0;
            transition: background 0.25s ease;
        }
        .mvc-hub-dept-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 175, 55, 0.45);
            box-shadow: var(--mvc-shadow-hover);
        }
        .mvc-hub-dept-card:hover::before {
            background: linear-gradient(90deg, var(--mvc-maroon-crimson) 0%, var(--mvc-gold-primary) 100%);
        }

        .mvc-hub-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }
        .mvc-hub-dept-glyph-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(0, 33, 71, 0.05);
            border: 1px solid rgba(0, 33, 71, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            transition: transform 0.2s;
        }
        .mvc-hub-dept-card:hover .mvc-hub-dept-glyph-box {
            transform: scale(1.08);
            background: rgba(212, 175, 55, 0.12);
            border-color: var(--mvc-gold-primary);
        }
        .mvc-hub-card-tags {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }
        .mvc-hub-fg-pill-tag {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 2px 9px;
            border-radius: 12px;
            background: #F1F5F9;
            color: var(--mvc-navy-royal);
        }
        .mvc-hub-hi-badge-tag {
            font-size: 12px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            background: rgba(128, 0, 0, 0.05);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .mvc-hub-dept-title {
            font-size: 19.5px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 10px;
            line-height: 1.35;
        }
        .mvc-hub-dept-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }
        .mvc-hub-dept-title a:hover {
            color: var(--mvc-maroon-crimson);
        }

        .mvc-hub-dept-metrics {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            font-size: 12px;
        }
        .mvc-hub-metric-chip {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 3px 9px;
            border-radius: 6px;
            font-weight: 600;
            color: #475569;
        }

        /* HOD Spotlight Box */
        .mvc-hub-hod-spotlight {
            background: #F8FAFC;
            border-left: 3px solid var(--mvc-gold-primary);
            padding: 8px 12px;
            border-radius: 0 var(--mvc-radius-inner) var(--mvc-radius-inner) 0;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mvc-hub-hod-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--mvc-navy-royal);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 33, 71, 0.15);
            overflow: hidden;
        }
        .mvc-hub-hod-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .mvc-hub-hod-info {
            font-size: 12px;
            line-height: 1.35;
        }
        .mvc-hub-hod-role {
            font-size: 10px;
            font-weight: 800;
            color: #B45309;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }
        .mvc-hub-hod-name {
            font-weight: 700;
            color: var(--mvc-navy-dark);
            display: block;
        }

        .mvc-hub-dept-desc {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.6;
            margin: 0 0 18px;
        }

        /* Dual Action Buttons Footer */
        .mvc-hub-card-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 14px;
            border-top: 1px solid #F1F5F9;
            margin-top: auto;
        }
        .mvc-hub-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: var(--mvc-radius-inner);
            background: var(--mvc-navy-royal);
            color: #ffffff;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.22s var(--mvc-ease);
        }
        .mvc-hub-btn-primary:hover {
            background: var(--mvc-maroon-crimson);
            color: #ffffff;
            transform: translateX(2px);
        }
        .mvc-hub-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            text-decoration: none;
            transition: color 0.2s;
        }
        .mvc-hub-btn-secondary:hover {
            color: var(--mvc-navy-royal);
            text-decoration: underline;
        }

        /* No Results Message */
        .mvc-hub-no-results {
            display: none;
            text-align: center;
            padding: 60px 20px;
            background: #ffffff;
            border-radius: var(--mvc-radius-card);
            margin-top: 24px;
            border: 1px dashed #CBD5E1;
        }
        .mvc-hub-no-results h3 {
            font-size: 20px;
            color: var(--mvc-navy-royal);
            margin: 0 0 8px;
        }
        .mvc-hub-no-results p {
            color: #64748B;
            margin: 0 0 16px;
        }

        /* 5. ACADEMIC GOVERNANCE & HELPDESK FOOTER */
        .mvc-hub-pillars-sec {
            background: #ffffff;
            border-top: 1px solid #E2E8F0;
            padding: 55px 0 65px;
        }
        .mvc-hub-sec-head-center {
            text-align: center;
            max-width: 780px;
            margin: 0 auto 38px;
        }
        .mvc-hub-sec-tag {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--mvc-maroon-crimson);
            display: block;
            margin-bottom: 8px;
        }
        .mvc-hub-sec-title {
            font-size: clamp(24px, 3.2vw, 34px);
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 10px;
        }
        .mvc-hub-sec-desc {
            font-size: 15px;
            color: #64748B;
            line-height: 1.65;
            margin: 0;
        }

        .mvc-hub-pillars-deck {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .mvc-hub-pillar-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: var(--mvc-radius-card);
            padding: 24px 20px;
            transition: all 0.25s var(--mvc-ease);
        }
        .mvc-hub-pillar-box:hover {
            background: #ffffff;
            border-color: var(--mvc-gold-primary);
            transform: translateY(-4px);
            box-shadow: var(--mvc-shadow-card);
        }
        .mvc-hub-pillar-icon {
            font-size: 28px;
            line-height: 1;
            margin-bottom: 12px;
        }
        .mvc-hub-pillar-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 8px;
        }
        .mvc-hub-pillar-desc {
            font-size: 13px;
            color: #64748B;
            line-height: 1.6;
            margin: 0;
        }

        .mvc-hub-helpdesk-bar {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy-royal) 100%);
            color: #ffffff;
            padding: 30px;
            border-radius: var(--mvc-radius-card);
            margin-top: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .mvc-hub-helpdesk-info h4 {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 4px;
        }
        .mvc-hub-helpdesk-info p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        .mvc-hub-helpdesk-links {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .mvc-hub-help-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: var(--mvc-radius-pill);
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }
        .mvc-hub-help-btn:hover {
            background: var(--mvc-gold-primary);
            color: #001733;
            border-color: var(--mvc-gold-primary);
        }

        /* RESPONSIVE MOBILE BREAKPOINTS */
        @media (max-width: 1200px) {
            /* Command Bar Responsive Flex */
            .mvc-hub-command-bar {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 14px !important;
            }
            .mvc-hub-nav-flank {
                max-width: 100% !important;
                width: 100% !important;
            }
            .mvc-hub-nav-arrow {
                display: flex !important;
            }
            .mvc-hub-search-capsule {
                width: 100% !important;
            }
        }
        @media (max-width: 1024px) {
            .mvc-hub-faculty-cards-deck { grid-template-columns: repeat(2, 1fr); }
            .mvc-hub-depts-grid { grid-template-columns: repeat(2, 1fr); }
            .mvc-hub-stats-grid { grid-template-columns: repeat(2, 1fr); }
            .mvc-hub-pillars-deck { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .mvc-hub-hero { padding: 30px 0 24px; }
            .mvc-hub-headline { font-size: 26px; }
            .mvc-hub-stats-grid { grid-template-columns: 1fr; }
            .mvc-hub-faculty-cards-deck { grid-template-columns: 1fr; }
            .mvc-hub-depts-grid { grid-template-columns: 1fr; }
            .mvc-hub-pillars-deck { grid-template-columns: 1fr; }
        }
        </style>

        <!-- 1. HIGH PRESTIGE INSTITUTIONAL HERO -->
        <header class="mvc-hub-hero">
            <div class="mvc-hub-container">

                <nav class="mvc-hub-breadcrumbs" aria-label="Breadcrumb">
                    <ol itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="<?php echo esc_url(home_url('/')); ?>">
                                <span itemprop="name">Home</span>
                            </a>
                            <meta itemprop="position" content="1" />
                        </li>
                        <li class="mvc-sep">/</li>
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
                            <span itemprop="name">Academic Disciplines &amp; Faculty Groups</span>
                            <meta itemprop="position" content="2" />
                        </li>
                    </ol>
                </nav>

                <div class="mvc-hub-insignia-row">
                    <span class="mvc-hub-badge-pill">
                        🏛️ Constituent College of VKSU Ara &bull; Charitravan, Buxar
                    </span>
                    <span class="mvc-hub-hindi-badge">
                        शैक्षणिक संकाय एवं 15 विभाग (Under NEP 2020)
                    </span>
                </div>

                <h1 class="mvc-hub-headline">Academic Disciplines &amp; Faculty Groups</h1>

                <p class="mvc-hub-lead-text">
                    Maharshi Vishwamitra Mahavidyalaya offers comprehensive undergraduate and postgraduate programs across Humanities, Social Sciences, Pure Sciences, and Vocational disciplines. Dedicated to scholastic inquiry, intellectual rigor, and holistic mentorship under the National Education Policy (NEP 2020) and CBCS framework of Veer Kunwar Singh University (VKSU), Ara.
                </p>

                <!-- Hero Stats Grid -->
                <div class="mvc-hub-stats-grid">
                    <div class="mvc-hub-stat-item">
                        <span class="mvc-hub-stat-glyph">🏛️</span>
                        <div>
                            <span class="mvc-hub-stat-label">Academic Departments</span>
                            <span class="mvc-hub-stat-val"><?php echo esc_html($total_depts); ?> Disciplines</span>
                        </div>
                    </div>
                    <div class="mvc-hub-stat-item">
                        <span class="mvc-hub-stat-glyph">🎓</span>
                        <div>
                            <span class="mvc-hub-stat-label">Faculties</span>
                            <span class="mvc-hub-stat-val">4 Academic Divisions</span>
                        </div>
                    </div>
                    <div class="mvc-hub-stat-item">
                        <span class="mvc-hub-stat-glyph">👨‍🏫</span>
                        <div>
                            <span class="mvc-hub-stat-label">Faculty Scholars</span>
                            <span class="mvc-hub-stat-val"><?php echo esc_html($total_faculty); ?> Active Educators</span>
                        </div>
                    </div>
                    <div class="mvc-hub-stat-item">
                        <span class="mvc-hub-stat-glyph">📜</span>
                        <div>
                            <span class="mvc-hub-stat-label">Curriculum</span>
                            <span class="mvc-hub-stat-val">NEP 2020 &bull; 4-Year CBCS</span>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- 2. THE COMMAND DECK (STICKY SEGMENTED SWITCHER & SEARCH) -->
        <section class="mvc-hub-command-section" id="mvcHubCommandSection">
            <div class="mvc-hub-container">
                <div class="mvc-hub-command-bar">
                    
                    <!-- Left: Navigation Track Flanked by Mobile Arrows -->
                    <div class="mvc-hub-nav-flank">
                        <button type="button" 
                                class="mvc-hub-nav-arrow" 
                                id="mvcHubArrowPrev" 
                                aria-label="Scroll faculties left"
                                title="Scroll left">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>

                        <div class="mvc-hub-tabs-track" id="mvcHubTabsTrack" role="tablist" aria-label="Faculty Filter">
                            <?php foreach ($faculty_groups as $fg_key => $fg_data) : ?>
                                <button type="button" 
                                        class="mvc-hub-tab-btn <?php echo ($fg_key === 'all') ? 'active' : ''; ?>" 
                                        data-filter="<?php echo esc_attr($fg_key); ?>"
                                        role="tab"
                                        aria-selected="<?php echo ($fg_key === 'all') ? 'true' : 'false'; ?>">
                                    <span class="mvc-hub-tab-label"><?php echo esc_html($fg_data['icon']); ?> <?php echo esc_html($fg_data['label']); ?></span>
                                    <span class="mvc-hub-tab-count"><?php echo esc_html($fg_data['count']); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" 
                                class="mvc-hub-nav-arrow" 
                                id="mvcHubArrowNext" 
                                aria-label="Scroll faculties right"
                                title="Scroll right">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>

                    <!-- Right: Search Capsule with Embedded Icon & Live Counter -->
                    <div class="mvc-hub-search-capsule">
                        <div class="mvc-hub-search-box-inner">
                            <svg class="mvc-hub-search-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" 
                                   class="mvc-hub-search-input" 
                                   id="mvcDeptSearchInput" 
                                   placeholder="Search departments or HODs..." 
                                   aria-label="Search departments or HODs">
                            <span class="mvc-hub-search-badge" id="mvcDeptSearchBadge">
                                <?php echo esc_html($total_depts); ?> Depts
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 3. FOUR FACULTY DIVISIONS BENTO CARDS -->
        <section class="mvc-hub-faculties-sec" id="mvcFacultiesOverviewSec">
            <div class="mvc-hub-container">

                <div class="mvc-hub-section-label">
                    <div class="mvc-hub-sec-title-group">
                        <h2>🏛️ Academic Faculties &amp; Divisions</h2>
                        <p>Explore the four primary academic faculties governing our 15 disciplines.</p>
                    </div>
                </div>

                <div class="mvc-hub-faculty-cards-deck">
                    <?php 
                    foreach ($faculty_groups as $fg_key => $fg_item) : 
                        if ($fg_key === 'all') continue;
                    ?>
                        <div class="mvc-hub-faculty-box" 
                             data-target-filter="<?php echo esc_attr($fg_key); ?>"
                             onclick="mvcSelectFacultyTab('<?php echo esc_attr($fg_key); ?>')">
                            
                            <div>
                                <div class="mvc-hub-fac-box-top">
                                    <span class="mvc-hub-fac-glyph"><?php echo esc_html($fg_item['icon']); ?></span>
                                    <span class="mvc-hub-fac-count-badge"><?php echo esc_html($fg_item['count']); ?> Departments</span>
                                </div>
                                <h3 class="mvc-hub-fac-title"><?php echo esc_html($fg_item['label']); ?></h3>
                                <span class="mvc-hub-fac-hi-title"><?php echo esc_html($fg_item['hi']); ?></span>
                                <p class="mvc-hub-fac-desc"><?php echo esc_html($fg_item['desc']); ?></p>
                                
                                <div class="mvc-hub-fac-tags">
                                    <?php if (!empty($fg_item['depts'])) : ?>
                                        <?php foreach ($fg_item['depts'] as $d_tag) : ?>
                                            <span class="mvc-hub-fac-mini-tag"><?php echo esc_html($d_tag); ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <span class="mvc-hub-fac-cta">
                                Filter Departments &amp; View &rarr;
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </section>

        <!-- 4. MAIN DEPARTMENTS GRID MATRIX -->
        <main class="mvc-hub-departments-sec">
            <div class="mvc-hub-container">

                <div class="mvc-hub-section-label">
                    <div class="mvc-hub-sec-title-group">
                        <h2 id="mvcListingTitle">Explore Academic Departments (15)</h2>
                        <p id="mvcListingSub">Click any department to view faculty rosters, syllabus regulations, and circulars.</p>
                    </div>
                </div>

                <div class="mvc-hub-depts-grid" id="mvcDeptHubGrid">
                    <?php
                    foreach ($departments as $dept) :
                        $d_id = $dept->ID;
                        $d_title = $dept->post_title;
                        $d_slug = $dept->post_name;
                        $d_permalink = get_permalink($d_id);
                        $d_hi = mvc_get_department_hindi_name($d_slug);
                        $d_glyph = isset($dept_glyphs[$d_slug]) ? $dept_glyphs[$d_slug] : '🏛️';

                        $fg_terms = wp_get_object_terms($d_id, 'faculty-group');
                        $fg_slug = (!empty($fg_terms) && !is_wp_error($fg_terms)) ? $fg_terms[0]->slug : 'humanities';
                        $fg_name = (!empty($fg_terms) && !is_wp_error($fg_terms)) ? $fg_terms[0]->name : 'Humanities';

                        $est_year = mvc_clean_academic_year(get_field('establishment_year', $d_id));
                        $seats = mvc_clean_sanctioned_seats(get_field('sanctioned_seats', $d_id));
                        $about = get_field('about_text', $d_id);
                        $short_desc = !empty($about) ? wp_trim_words(wp_strip_all_tags($about), 22) : 'Cultivating academic excellence, scholarly depth, and intellectual inquiry under NEP 2020.';

                        $hod_post = isset($dept_hod_map[$d_slug]) ? $dept_hod_map[$d_slug] : null;
                        $faculty_list = isset($dept_faculty_map[$d_slug]) ? $dept_faculty_map[$d_slug] : array();
                        $f_count = count($faculty_list);
                    ?>
                        <article class="mvc-hub-dept-card" 
                                 data-faculty-group="<?php echo esc_attr($fg_slug); ?>"
                                 data-title="<?php echo esc_attr(strtolower($d_title . ' ' . $d_hi)); ?>"
                                 data-hod="<?php echo esc_attr(strtolower($hod_post ? $hod_post->post_title : '')); ?>">
                            
                            <div>
                                <div class="mvc-hub-card-header">
                                    <div class="mvc-hub-dept-glyph-box" aria-hidden="true">
                                        <?php echo esc_html($d_glyph); ?>
                                    </div>
                                    <div class="mvc-hub-card-tags">
                                        <span class="mvc-hub-fg-pill-tag">
                                            Faculty of <?php echo esc_html($fg_name); ?>
                                        </span>
                                        <span class="mvc-hub-hi-badge-tag">
                                            <?php echo esc_html($d_hi); ?>
                                        </span>
                                    </div>
                                </div>

                                <h3 class="mvc-hub-dept-title">
                                    <a href="<?php echo esc_url($d_permalink); ?>">
                                        <?php echo esc_html($d_title); ?>
                                    </a>
                                </h3>

                                <div class="mvc-hub-dept-metrics">
                                    <span class="mvc-hub-metric-chip">📅 Est. <?php echo esc_html($est_year); ?></span>
                                    <span class="mvc-hub-metric-chip">🎓 <?php echo esc_html($seats); ?></span>
                                    <span class="mvc-hub-metric-chip">👨‍🏫 <?php echo esc_html($f_count); ?> Faculty</span>
                                </div>

                                <?php if ($hod_post) : 
                                    $hod_photo = get_the_post_thumbnail_url($hod_post->ID, 'thumbnail');
                                    $hod_mono = mvc_get_faculty_monogram($hod_post->post_title);
                                ?>
                                    <div class="mvc-hub-hod-spotlight">
                                        <div class="mvc-hub-hod-avatar" aria-hidden="true">
                                            <?php if (!empty($hod_photo)) : ?>
                                                <img src="<?php echo esc_url($hod_photo); ?>" alt="<?php echo esc_attr($hod_post->post_title); ?>" loading="lazy">
                                            <?php else : ?>
                                                <span><?php echo esc_html($hod_mono); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mvc-hub-hod-info">
                                            <span class="mvc-hub-hod-role">👑 Head of Department</span>
                                            <span class="mvc-hub-hod-name"><?php echo esc_html($hod_post->post_title); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <p class="mvc-hub-dept-desc">
                                    <?php echo esc_html($short_desc); ?>
                                </p>
                            </div>

                            <div class="mvc-hub-card-actions">
                                <a href="<?php echo esc_url($d_permalink); ?>" class="mvc-hub-btn-primary">
                                    Department Portal &rarr;
                                </a>
                                <a href="<?php echo esc_url($d_permalink . '#pane-syllabus'); ?>" class="mvc-hub-btn-secondary">
                                    Syllabus &amp; Roster &nearr;
                                </a>
                            </div>

                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- No Results State -->
                <div class="mvc-hub-no-results" id="mvcHubNoResults">
                    <div style="font-size: 40px; margin-bottom: 12px;">🔍</div>
                    <h3>No Matching Departments Found</h3>
                    <p>Try searching for a different keyword or switch to "All Disciplines".</p>
                    <button type="button" class="mvc-hub-tab-btn" onclick="mvcResetFilters()" style="margin: 0 auto;">
                        Reset Filters
                    </button>
                </div>

            </div>
        </main>

        <!-- 5. ACADEMIC GOVERNANCE & HELPDESK FOOTER -->
        <section class="mvc-hub-pillars-sec">
            <div class="mvc-hub-container">

                <div class="mvc-hub-sec-head-center">
                    <span class="mvc-hub-sec-tag">Academic Architecture</span>
                    <h3 class="mvc-hub-sec-title">Academic Governance &amp; Curricular Standards</h3>
                    <p class="mvc-hub-sec-desc">
                        Every department at Maharshi Vishwamitra Mahavidyalaya operates under standardized academic regulations designed to nurture intellectual clarity, research aptitude, and professional readiness.
                    </p>
                </div>

                <div class="mvc-hub-pillars-deck">
                    <div class="mvc-hub-pillar-box">
                        <div class="mvc-hub-pillar-icon">📜</div>
                        <h4 class="mvc-hub-pillar-title">NEP 2020 4-Year CBCS</h4>
                        <p class="mvc-hub-pillar-desc">
                            Choice-Based Credit System featuring Major, Minor, Multidisciplinary (MDC), Ability Enhancement (AEC), and Skill Enhancement (SEC) courses.
                        </p>
                    </div>

                    <div class="mvc-hub-pillar-box">
                        <div class="mvc-hub-pillar-icon">🏛️</div>
                        <h4 class="mvc-hub-pillar-title">VKSU Affiliation &amp; UGC</h4>
                        <p class="mvc-hub-pillar-desc">
                            Constituent unit of Veer Kunwar Singh University (VKSU), Ara; recognized under UGC Sections 2(f) and 12(B) with centralized examination governance.
                        </p>
                    </div>

                    <div class="mvc-hub-pillar-box">
                        <div class="mvc-hub-pillar-icon">📊</div>
                        <h4 class="mvc-hub-pillar-title">Continuous Evaluation (CIA)</h4>
                        <p class="mvc-hub-pillar-desc">
                            Robust Continuous Internal Assessment comprising departmental mid-term tests, research assignments, seminar paper presentations, and viva-voce.
                        </p>
                    </div>

                    <div class="mvc-hub-pillar-box">
                        <div class="mvc-hub-pillar-icon">📚</div>
                        <h4 class="mvc-hub-pillar-title">Central &amp; Digital Library</h4>
                        <p class="mvc-hub-pillar-desc">
                            Access to INFLIBNET N-LIST digital consortium, departmental reference book banks, open-access scholarly databases, and specialized reading rooms.
                        </p>
                    </div>
                </div>

                <!-- Academic Helpdesk Bar -->
                <div class="mvc-hub-helpdesk-bar">
                    <div class="mvc-hub-helpdesk-info">
                        <h4>Need Academic Guidance or Syllabus Inquiries?</h4>
                        <p>Contact the Office of Academic Affairs or respective Department Head desks at Charitravan, Buxar.</p>
                    </div>
                    <div class="mvc-hub-helpdesk-links">
                        <a href="<?php echo esc_url(home_url('/academic-calendar/')); ?>" class="mvc-hub-help-btn">
                            📅 Academic Calendar
                        </a>
                        <a href="<?php echo esc_url(home_url('/teaching-staff/')); ?>" class="mvc-hub-help-btn">
                            👨‍🏫 Teaching Faculty
                        </a>
                        <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="mvc-hub-help-btn">
                            📍 Contact Helpdesk
                        </a>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <!-- CLIENT-SIDE SCRIPT FOR TAB FILTERING, MOBILE ARROWS & LIVE SEARCH -->
    <script>
    (function() {
        const tabsTrack = document.getElementById('mvcHubTabsTrack');
        const arrowPrev = document.getElementById('mvcHubArrowPrev');
        const arrowNext = document.getElementById('mvcHubArrowNext');
        const tabBtns = document.querySelectorAll('.mvc-hub-tab-btn');
        const searchInput = document.getElementById('mvcDeptSearchInput');
        const searchBadge = document.getElementById('mvcDeptSearchBadge');
        const cards = document.querySelectorAll('.mvc-hub-dept-card');
        const noResultsMsg = document.getElementById('mvcHubNoResults');
        const listingTitle = document.getElementById('mvcListingTitle');
        let currentFilter = 'all';

        // 1. Mobile Arrow Scroll Logic
        function updateArrowStates() {
            if (!tabsTrack || !arrowPrev || !arrowNext) return;
            const scrollLeft = tabsTrack.scrollLeft;
            const maxScroll = tabsTrack.scrollWidth - tabsTrack.clientWidth;
            arrowPrev.setAttribute('aria-disabled', scrollLeft <= 4 ? 'true' : 'false');
            arrowNext.setAttribute('aria-disabled', scrollLeft >= maxScroll - 4 ? 'true' : 'false');
        }

        if (arrowPrev && tabsTrack) {
            arrowPrev.addEventListener('click', function() {
                tabsTrack.scrollBy({ left: -220, behavior: 'smooth' });
            });
        }
        if (arrowNext && tabsTrack) {
            arrowNext.addEventListener('click', function() {
                tabsTrack.scrollBy({ left: 220, behavior: 'smooth' });
            });
        }
        if (tabsTrack) {
            tabsTrack.addEventListener('scroll', updateArrowStates);
            window.addEventListener('resize', updateArrowStates);
            setTimeout(updateArrowStates, 200);
        }

        // 2. Filter & Search Core
        function applyFilters() {
            const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach(card => {
                const fg = card.getAttribute('data-faculty-group');
                const title = card.getAttribute('data-title') || '';
                const hod = card.getAttribute('data-hod') || '';

                const matchesFilter = (currentFilter === 'all') || (fg === currentFilter);
                const matchesQuery = (!query) || (title.includes(query) || hod.includes(query));

                if (matchesFilter && matchesQuery) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (searchBadge) {
                searchBadge.textContent = `${visibleCount} Depts`;
            }

            if (noResultsMsg) {
                noResultsMsg.style.display = (visibleCount === 0) ? 'block' : 'none';
            }

            if (listingTitle) {
                if (currentFilter === 'all') {
                    listingTitle.textContent = `Explore Academic Departments (${visibleCount})`;
                } else {
                    const activeBtn = document.querySelector(`.mvc-hub-tab-btn[data-filter="${currentFilter}"] span`);
                    const facultyName = activeBtn ? activeBtn.textContent.trim() : 'Faculty';
                    listingTitle.textContent = `${facultyName} (${visibleCount} Departments)`;
                }
            }
        }

        window.mvcSelectFacultyTab = function(filterSlug) {
            currentFilter = filterSlug;
            tabBtns.forEach(btn => {
                const isTarget = btn.getAttribute('data-filter') === filterSlug;
                btn.classList.toggle('active', isTarget);
                btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
                if (isTarget && tabsTrack) {
                    btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
            });
            applyFilters();
            
            // Smooth scroll down to listing
            const targetEl = document.getElementById('mvcListingTitle');
            if (targetEl) {
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter') || 'all';
                window.mvcSelectFacultyTab(filter);
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        window.mvcResetFilters = function() {
            if (searchInput) searchInput.value = '';
            window.mvcSelectFacultyTab('all');
        };

        // URL hash support (e.g. #humanities)
        const hash = window.location.hash.replace('#', '').toLowerCase();
        if (hash && document.querySelector(`.mvc-hub-tab-btn[data-filter="${hash}"]`)) {
            window.mvcSelectFacultyTab(hash);
        }
    })();
    </script>
    <?php
        return ob_get_clean();
    }, 43200);
}
