<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Our Administration Page Builder - Master College Staff & Faculty Directories
 * Dynamically queries and showcases Teaching Faculty (grouped by Department) 
 * and Non-Teaching Staff (grouped by Cadre/Wing).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper to generate monogram initials if photo is unavailable
 */
if (!function_exists('mvc_get_admin_monogram')) {
    function mvc_get_admin_monogram($name) {
        $clean = preg_replace('/^(Prof\.|Dr\.|Sri|Smt\.|Shri|Md\.|Prof\.\s*\(Dr\.\))\s+/i', '', trim($name));
        $words = preg_split('/\s+/', $clean);
        $init = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $char = mb_substr($w, 0, 1);
                if (preg_match('/[A-Za-z]/', $char)) {
                    $init .= strtoupper($char);
                }
            }
            if (strlen($init) >= 2) break;
        }
        return !empty($init) ? $init : 'MV';
    }
}

/**
 * Render Our Administration Page
 */
function mvc_render_our_administration_page($override_form_id = null) {
    global $mvc_shortcode_context;
    $site_url = home_url();

    // 1. DYNAMIC QUERY: ALL TEACHING FACULTY (CPT: faculty)
    $faculty_query = new WP_Query([
        'post_type'      => 'faculty',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
    ]);

    $faculty_by_dept = [];
    $total_faculty = 0;
    $total_phd = 0;
    $total_hod = 0;

    // Standard Department Meta Map for Slugs & Icons
    $dept_meta_map = [
        'Hindi'                       => ['icon' => '📖', 'slug' => 'hindi', 'title' => 'P.G. Department of Hindi'],
        'English'                     => ['icon' => '🖋️', 'slug' => 'english', 'title' => 'Department of English'],
        'Sanskrit'                   => ['icon' => '📜', 'slug' => 'sanskrit', 'title' => 'Department of Sanskrit'],
        'Philosophy'                 => ['icon' => '⚖️', 'slug' => 'philosophy', 'title' => 'Department of Philosophy'],
        'History'                    => ['icon' => '🏛️', 'slug' => 'history', 'title' => 'P.G. Department of History'],
        'Political Science'          => ['icon' => '🌐', 'slug' => 'political-science', 'title' => 'P.G. Department of Political Science'],
        'Psychology'                 => ['icon' => '🧠', 'slug' => 'psychology', 'title' => 'P.G. Department of Psychology'],
        'Economics'                  => ['icon' => '📈', 'slug' => 'economics', 'title' => 'Department of Economics'],
        'Mathematics'                => ['icon' => '📐', 'slug' => 'mathematics', 'title' => 'Department of Mathematics'],
        'Physics'                    => ['icon' => '⚡', 'slug' => 'physics', 'title' => 'Department of Physics'],
        'Chemistry'                  => ['icon' => '🧪', 'slug' => 'chemistry', 'title' => 'Department of Chemistry'],
        'Botany'                     => ['icon' => '🌿', 'slug' => 'botany', 'title' => 'Department of Botany'],
        'Zoology'                    => ['icon' => '🔬', 'slug' => 'zoology', 'title' => 'Department of Zoology'],
        'Computer Applications (BCA)'=> ['icon' => '💻', 'slug' => 'computer-applications', 'title' => 'Department of Computer Applications (BCA)'],
        'Business Management (BBA)'  => ['icon' => '📊', 'slug' => 'business-management', 'title' => 'Department of Business Management (BBA)'],
        'General Administration'     => ['icon' => '🎓', 'slug' => 'our-administration', 'title' => 'Collegiate Executive Administration'],
    ];

    if ($faculty_query->have_posts()) {
        while ($faculty_query->have_posts()) {
            $faculty_query->the_post();
            $fid = get_the_ID();
            $total_faculty++;

            $terms = wp_get_post_terms($fid, 'dept_link');
            $dept_name = !empty($terms) ? trim($terms[0]->name) : 'General Administration';

            // Clean up Department Names
            if ($dept_name === 'BBA') $dept_name = 'Business Management (BBA)';
            if ($dept_name === 'BCA') $dept_name = 'Computer Applications (BCA)';

            $desig = get_post_meta($fid, 'designation', true) ?: get_post_meta($fid, 'faculty_designation', true) ?: 'Assistant Professor';
            $qual  = get_post_meta($fid, 'qualification', true) ?: get_post_meta($fid, 'faculty_qualification', true) ?: 'Ph.D.';
            $email = get_post_meta($fid, 'email', true) ?: get_post_meta($fid, 'faculty_email', true) ?: 'mvcollegebuxar@gmail.com';
            $phone = get_post_meta($fid, 'faculty_phone', true) ?: '';
            $nature= get_post_meta($fid, 'faculty_nature', true) ?: 'Permanent';
            $is_hod= (get_post_meta($fid, 'is_department_head', true) == '1');

            if ($is_hod) $total_hod++;
            if (stripos($qual, 'Ph.D') !== false || stripos($qual, 'PhD') !== false) $total_phd++;

            $photo = get_the_post_thumbnail_url($fid, 'medium');
            if (empty($photo)) {
                $img_url = get_post_meta($fid, 'image_url', true);
                if (!empty($img_url) && stripos($img_url, 'dummy-img') === false) {
                    $photo = $img_url;
                }
            }

            $faculty_by_dept[$dept_name][] = [
                'id'       => $fid,
                'name'     => get_the_title(),
                'desig'    => $desig,
                'qual'     => ($qual === 'NA' || empty($qual)) ? 'Post Graduate' : $qual,
                'email'    => $email,
                'phone'    => $phone,
                'is_hod'   => $is_hod,
                'nature'   => $nature,
                'photo'    => $photo,
                'monogram' => mvc_get_admin_monogram(get_the_title())
            ];
        }
        wp_reset_postdata();
    }

    // Sort departments alphabetically with General Admin first if present
    ksort($faculty_by_dept);

    // 2. DYNAMIC QUERY: ALL NON-TEACHING STAFF (CPT: staff_member)
    $staff_query = new WP_Query([
        'post_type'      => 'staff_member',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
    ]);

    $staff_by_cadre = [
        'ministerial' => [
            'title' => 'Ministerial & Office Administration Cadre',
            'desc'  => 'Accounts, Admissions, University Examination Tabulation, Records & General Secretariat',
            'icon'  => '🏛️',
            'badge' => 'Ministerial Cadre',
            'members' => []
        ],
        'technical' => [
            'title' => 'Technical, IT & Digital Operations Cadre',
            'desc'  => 'Central Digital IT Network, Online Admission Portal, Central Store & Data Terminals',
            'icon'  => '💻',
            'badge' => 'Technical Cadre',
            'members' => []
        ],
        'laboratory' => [
            'title' => 'Science Laboratories & Academic Support Cadre',
            'desc'  => 'Physics, Chemistry, Botany, Zoology Laboratories & Scientific Practical Stations',
            'icon'  => '🔬',
            'badge' => 'Lab Support',
            'members' => []
        ],
        'logistics' => [
            'title' => 'Campus Watch, Ward, Logistics & Maintenance Cadre',
            'desc'  => 'Campus Watch & Ward, Security Gates, Cycle Stand, Logistics & Environmental Sanitation',
            'icon'  => '🛡️',
            'badge' => 'Campus Logistics',
            'members' => []
        ],
    ];

    $total_staff = 0;

    if ($staff_query->have_posts()) {
        while ($staff_query->have_posts()) {
            $staff_query->the_post();
            $sid = get_the_ID();
            $total_staff++;

            $desig = get_post_meta($sid, 'staff_designation', true) ?: get_post_meta($sid, 'designation', true) ?: 'Staff Member';
            $wing  = get_post_meta($sid, 'staff_wing', true) ?: 'Administrative Office';
            $qual  = get_post_meta($sid, 'staff_qualification', true) ?: get_post_meta($sid, 'qualification', true) ?: 'Graduate';
            $nature= get_post_meta($sid, 'staff_nature', true) ?: 'Permanent';

            $photo = get_the_post_thumbnail_url($sid, 'medium');
            if (empty($photo)) {
                $img_url = get_post_meta($sid, 'image_url', true);
                if (!empty($img_url) && stripos($img_url, 'dummy-img') === false) {
                    $photo = $img_url;
                }
            }

            // Map staff member into 4 primary cadres
            $desig_lower = strtolower($desig);
            $wing_lower  = strtolower($wing);

            if (stripos($desig_lower, 'clerk') !== false || stripos($desig_lower, 'l.d.c') !== false || stripos($desig_lower, 'l.d.a') !== false || stripos($wing_lower, 'accounts') !== false || stripos($wing_lower, 'admission') !== false || stripos($wing_lower, 'office') !== false || stripos($wing_lower, 'dispatch') !== false || stripos($wing_lower, 'registration') !== false || stripos($wing_lower, 'examination') !== false) {
                $target_cadre = 'ministerial';
            } elseif (stripos($desig_lower, 'computer') !== false || stripos($desig_lower, 'operator') !== false || stripos($desig_lower, 'store') !== false || stripos($wing_lower, 'portal') !== false || stripos($wing_lower, 'it wing') !== false || stripos($wing_lower, 'digital') !== false) {
                $target_cadre = 'technical';
            } elseif (stripos($desig_lower, 'lab') !== false || stripos($wing_lower, 'laborator') !== false || stripos($wing_lower, 'science') !== false) {
                $target_cadre = 'laboratory';
            } else {
                $target_cadre = 'logistics';
            }

            $staff_by_cadre[$target_cadre]['members'][] = [
                'id'       => $sid,
                'name'     => get_the_title(),
                'desig'    => $desig,
                'wing'     => $wing,
                'qual'     => $qual,
                'nature'   => $nature,
                'photo'    => $photo,
                'monogram' => mvc_get_admin_monogram(get_the_title())
            ];
        }
        wp_reset_postdata();
    }

    ob_start();
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #5c0000;
            --mvc-gold: #D4AF37;
            --mvc-gold-light: #FFF9E6;
            --mvc-gold-amber: #B48200;
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

        .mvc-admin-root {
            background: #F8FAFC;
            color: var(--mvc-slate);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            padding-bottom: 70px;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 1. HERO HEADER (Minimal, Sleek) */
        .mvc-admin-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #0a3366 100%);
            color: #FFFFFF;
            padding: clamp(32px, 4vw, 52px) clamp(20px, 4vw, 48px);
            position: relative;
            border-bottom: 3.5px solid var(--mvc-gold);
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .mvc-admin-hero-inner {
            max-width: 1240px;
            margin: 0 auto;
        }

        .mvc-admin-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
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

        .mvc-admin-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid var(--mvc-gold);
            color: #FEF08A;
            font-size: 11.5px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 10px;
        }

        .mvc-admin-hero h1 {
            font-size: clamp(24px, 3.5vw, 36px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 18px;
            letter-spacing: -0.5px;
        }

        /* Hero KPI Strip */
        .mvc-admin-kpi-ribbon {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .mvc-admin-kpi-item {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 10px;
            padding: 10px 14px;
            backdrop-filter: blur(4px);
        }

        .mvc-admin-kpi-title {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            font-weight: 700;
        }

        .mvc-admin-kpi-val {
            font-size: 14.5px;
            font-weight: 800;
            color: #FFFFFF;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 2. MAIN CONTAINER */
        .mvc-admin-container {
            max-width: 1240px;
            margin: 28px auto 0;
            padding: 0 20px;
            box-sizing: border-box;
        }

        /* 3. MASTER DIRECTORY SEGMENTED TABS (Creative & Modern) */
        .mvc-dir-switcher {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 5px;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            margin-bottom: 26px;
        }

        .mvc-dir-tab-btn {
            flex: 1;
            background: transparent;
            border: none;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 800;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .mvc-dir-tab-btn:hover {
            background: #F1F5F9;
            color: var(--mvc-navy);
        }

        .mvc-dir-tab-btn.is-active {
            background: var(--mvc-navy);
            color: #FFFFFF !important;
            box-shadow: 0 2px 10px rgba(0, 33, 71, 0.25);
        }

        .mvc-dir-tab-badge {
            background: rgba(255, 255, 255, 0.2);
            color: inherit;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .mvc-dir-tab-btn:not(.is-active) .mvc-dir-tab-badge {
            background: #E2E8F0;
            color: #475569;
        }

        /* 4. TOOLBAR & SEARCH */
        .mvc-filter-toolbar {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .mvc-search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .mvc-search-input-wrap input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border: 1.5px solid var(--mvc-border);
            border-radius: 8px;
            font-size: 13.5px;
            outline: none;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }

        .mvc-search-input-wrap input:focus {
            border-color: var(--mvc-navy);
        }

        .mvc-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
        }

        .mvc-view-toggle {
            display: inline-flex;
            background: #F1F5F9;
            border-radius: 8px;
            padding: 3px;
            border: 1px solid var(--mvc-border);
        }

        .mvc-view-btn {
            background: transparent;
            border: none;
            padding: 6px 12px;
            font-size: 12.5px;
            font-weight: 700;
            color: #64748B;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .mvc-view-btn.is-active {
            background: #FFFFFF;
            color: var(--mvc-navy);
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }

        /* 5. DEPARTMENT ACCORDION / SECTION */
        .mvc-dept-block {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .mvc-dept-block:hover {
            border-color: #CBD5E1;
        }

        .mvc-dept-header {
            padding: 18px 24px;
            background: linear-gradient(135deg, #FFFFFF 0%, #FAF9F6 100%);
            border-bottom: 1.5px solid var(--mvc-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .mvc-dept-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mvc-dept-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #EFF6FF;
            color: var(--mvc-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 1px solid #BFDBFE;
            flex-shrink: 0;
        }

        .mvc-dept-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            letter-spacing: -0.3px;
        }

        .mvc-dept-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .mvc-dept-count-pill {
            font-size: 11.5px;
            font-weight: 800;
            color: var(--mvc-maroon);
            background: rgba(128, 0, 0, 0.07);
            border: 1px solid rgba(128, 0, 0, 0.15);
            padding: 3px 10px;
            border-radius: 999px;
            text-transform: uppercase;
        }

        .mvc-dept-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
            color: var(--mvc-navy);
            text-decoration: none !important;
            padding: 4px 10px;
            border-radius: 6px;
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            transition: all 0.2s ease;
        }

        .mvc-dept-link-btn:hover {
            border-color: var(--mvc-navy);
            background: #EFF6FF;
            color: var(--mvc-navy) !important;
        }

        /* 6. FACULTY CARDS GRID */
        .mvc-faculty-grid {
            padding: 20px 24px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .mvc-fac-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
            position: relative;
        }

        .mvc-fac-card:hover {
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 33, 71, 0.07);
        }

        .mvc-fac-card.is-hod {
            border-color: var(--mvc-gold);
            background: linear-gradient(135deg, #FFFDF7 0%, #FFFFFF 100%);
            box-shadow: 0 2px 10px rgba(212, 175, 55, 0.12);
        }

        .mvc-fac-photo-box {
            position: relative;
            width: 68px;
            height: 68px;
            flex-shrink: 0;
        }

        .mvc-fac-photo {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--mvc-border);
            background: #F1F5F9;
            display: block;
        }

        .mvc-fac-card.is-hod .mvc-fac-photo {
            border-color: var(--mvc-gold);
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25);
        }

        .mvc-fac-monogram {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #002147 0%, #00152e 100%);
            color: var(--mvc-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            border: 2px solid var(--mvc-border);
            letter-spacing: 0.5px;
        }

        .mvc-fac-card.is-hod .mvc-fac-monogram {
            border-color: var(--mvc-gold);
        }

        .mvc-fac-info {
            flex: 1;
            min-width: 0;
        }

        .mvc-hod-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(212, 175, 55, 0.18);
            border: 1px solid var(--mvc-gold);
            color: #92400E;
            font-size: 10px;
            font-weight: 800;
            padding: 1px 7px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 3px;
        }

        .mvc-fac-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mvc-fac-desig {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            margin: 0 0 6px;
        }

        .mvc-fac-tags {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .mvc-fac-qual-pill {
            font-size: 10.5px;
            font-weight: 700;
            background: #F1F5F9;
            color: #334155;
            padding: 2px 7px;
            border-radius: 4px;
            border: 1px solid #E2E8F0;
        }

        .mvc-fac-nature-pill {
            font-size: 10.5px;
            font-weight: 700;
            background: #ECFDF5;
            color: #065F46;
            padding: 2px 7px;
            border-radius: 4px;
            border: 1px solid #A7F3D0;
        }

        /* 7. COMPACT TABULAR VIEW */
        .mvc-table-container {
            display: none;
            padding: 0 24px 20px;
            overflow-x: auto;
        }

        .mvc-admin-dir-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .mvc-admin-dir-table th {
            background: #F8FAFC;
            color: #475569;
            padding: 10px 14px;
            text-align: left;
            font-weight: 700;
            border-bottom: 1.5px solid var(--mvc-border);
            font-size: 12px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .mvc-admin-dir-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
            color: #334155;
        }

        .mvc-admin-dir-table tr:hover td {
            background: #F8FAFC;
        }

        /* 8. QUICK JUMP DEPARTMENT PILLS BAR */
        .mvc-dept-pill-track {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding-bottom: 8px;
            margin-bottom: 22px;
            scrollbar-width: thin;
        }

        .mvc-dept-pill-btn {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .mvc-dept-pill-btn:hover {
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
            background: #EFF6FF;
        }

        .mvc-dept-pill-btn.is-active {
            background: var(--mvc-navy);
            color: #FFFFFF !important;
            border-color: var(--mvc-navy);
        }

        /* 9. BOTTOM GATEWAYS STRIP */
        .mvc-gw-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-top: 36px;
        }

        .mvc-gw-btn-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 16px;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mvc-gw-btn-card:hover {
            border-color: var(--mvc-navy);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 33, 71, 0.08);
        }

        .mvc-gw-title {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin-bottom: 4px;
        }

        .mvc-gw-sub {
            font-size: 12px;
            color: #64748B;
            margin: 0 0 10px;
            line-height: 1.4;
        }

        .mvc-gw-arrow {
            font-size: 12px;
            font-weight: 700;
            color: var(--mvc-maroon);
        }

        /* RESPONSIVE BREAKPOINTS */
        @media (max-width: 991px) {
            .mvc-faculty-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-admin-kpi-ribbon {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-gw-bar {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .mvc-faculty-grid {
                grid-template-columns: 1fr;
                padding: 16px;
            }
            .mvc-admin-kpi-ribbon {
                grid-template-columns: 1fr;
            }
            .mvc-gw-bar {
                grid-template-columns: 1fr;
            }
            .mvc-dir-switcher {
                flex-direction: column;
            }
            .mvc-dir-tab-btn {
                width: 100%;
            }
            .mvc-filter-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .mvc-view-toggle {
                align-self: flex-start;
            }
            .mvc-dept-header {
                padding: 14px 16px;
            }
            .mvc-table-container {
                padding: 0 16px 16px;
            }
        }
    </style>

    <div class="mvc-admin-root">
        <!-- 1. HERO HEADER -->
        <header class="mvc-admin-hero">
            <div class="mvc-admin-hero-inner">
                <nav class="mvc-admin-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>">Administration</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">Staff Directories</span>
                </nav>

                <h1>College Staff &amp; Faculty Directories</h1>

                <!-- 4 KPI Metrics -->
                <div class="mvc-admin-kpi-ribbon">
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">Teaching Faculty</div>
                        <div class="mvc-admin-kpi-val"><?php echo esc_html($total_faculty); ?> Appointed Scholars</div>
                    </div>
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">Academic Departments</div>
                        <div class="mvc-admin-kpi-val"><?php echo count($faculty_by_dept); ?> Collegiate Faculties</div>
                    </div>
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">Non-Teaching Staff</div>
                        <div class="mvc-admin-kpi-val"><?php echo esc_html($total_staff); ?> Ministerial Staff</div>
                    </div>
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">Statutory Affiliation</div>
                        <div class="mvc-admin-kpi-val">Constituent Unit &bull; VKSU Ara</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-admin-container" id="admin-main">

            <!-- MASTER DIRECTORY SEGMENTED TABS -->
            <div class="mvc-dir-switcher">
                <button type="button" class="mvc-dir-tab-btn is-active" id="mvcTabBtnFaculty" onclick="mvcSwitchDirectoryView('faculty');">
                    <span>🎓 Academic Teaching Faculty</span>
                    <span class="mvc-dir-tab-badge"><?php echo esc_html($total_faculty); ?></span>
                </button>
                <button type="button" class="mvc-dir-tab-btn" id="mvcTabBtnStaff" onclick="mvcSwitchDirectoryView('staff');">
                    <span>👥 Non-Teaching Administrative Staff</span>
                    <span class="mvc-dir-tab-badge"><?php echo esc_html($total_staff); ?></span>
                </button>
            </div>

            <!-- TOOLBAR & CONTROLS -->
            <section class="mvc-filter-toolbar">
                <div class="mvc-search-input-wrap">
                    <svg class="mvc-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="mvcStaffSearchInput" placeholder="Search by name, department, role, or qualification..." onkeyup="mvcFilterMasterDirectory();">
                </div>

                <div class="mvc-view-toggle">
                    <button type="button" class="mvc-view-btn is-active" id="mvcBtnCardMode" onclick="mvcToggleCardTableMode('cards');">
                        📇 Cards View
                    </button>
                    <button type="button" class="mvc-view-btn" id="mvcBtnTableMode" onclick="mvcToggleCardTableMode('table');">
                        📋 Tabular View
                    </button>
                </div>
            </section>

            <!-- ========================================================
                 TAB 1: TEACHING FACULTY (GROUPED BY DEPARTMENT)
                 ======================================================== -->
            <div id="mvcTeachingFacultyView">
                <!-- Department Fast-Jump Pills -->
                <div class="mvc-dept-pill-track">
                    <button type="button" class="mvc-dept-pill-btn is-active" onclick="mvcFilterFacultyByDept('all', this);">
                        <span>All (<?php echo count($faculty_by_dept); ?>)</span>
                    </button>
                    <?php foreach ($faculty_by_dept as $dname => $fmembers): 
                        $icon = $dept_meta_map[$dname]['icon'] ?? '📚';
                    ?>
                        <button type="button" class="mvc-dept-pill-btn" onclick="mvcFilterFacultyByDept('<?php echo esc_attr(sanitize_title($dname)); ?>', this);">
                            <span><?php echo esc_html($icon); ?> <?php echo esc_html($dname); ?></span>
                            <span style="font-size:10.5px; opacity:0.8;">(<?php echo count($fmembers); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Departmental Sections -->
                <?php foreach ($faculty_by_dept as $dept_name => $members): 
                    $dept_meta = $dept_meta_map[$dept_name] ?? ['icon' => '📚', 'slug' => 'department', 'title' => $dept_name];
                    $dept_slug = sanitize_title($dept_name);
                ?>
                    <section class="mvc-dept-block" id="dept-<?php echo esc_attr($dept_slug); ?>" data-dept="<?php echo esc_attr($dept_slug); ?>">
                        <div class="mvc-dept-header">
                            <div class="mvc-dept-title-wrap">
                                <div class="mvc-dept-icon-box">
                                    <?php echo esc_html($dept_meta['icon']); ?>
                                </div>
                                <div>
                                    <h2 class="mvc-dept-name"><?php echo esc_html($dept_meta['title']); ?></h2>
                                </div>
                            </div>
                            <div class="mvc-dept-badges">
                                <span class="mvc-dept-count-pill"><?php echo count($members); ?> Faculty Members</span>
                                <?php if ($dept_meta['slug'] !== 'our-administration'): ?>
                                    <a href="<?php echo esc_url($site_url . '/department/' . $dept_meta['slug'] . '/'); ?>" class="mvc-dept-link-btn">
                                        <span>Dept Portal &rarr;</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- CARDS VIEW -->
                        <div class="mvc-faculty-grid mvc-fac-grid-view">
                            <?php foreach ($members as $mem): 
                                $is_hod = $mem['is_hod'];
                            ?>
                                <article class="mvc-fac-card <?php echo $is_hod ? 'is-hod' : ''; ?>" data-keywords="<?php echo esc_attr(strtolower($mem['name'] . ' ' . $mem['desig'] . ' ' . $mem['qual'] . ' ' . $dept_name)); ?>">
                                    <div class="mvc-fac-photo-box">
                                        <?php if (!empty($mem['photo'])): ?>
                                            <img src="<?php echo esc_url($mem['photo']); ?>" alt="<?php echo esc_attr($mem['name']); ?>" class="mvc-fac-photo" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($mem['name']); ?>&background=002147&color=ffffff';">
                                        <?php else: ?>
                                            <div class="mvc-fac-monogram"><?php echo esc_html($mem['monogram']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mvc-fac-info">
                                        <?php if ($is_hod): ?>
                                            <span class="mvc-hod-badge">👑 Head of Department (HOD)</span>
                                        <?php endif; ?>
                                        <h3 class="mvc-fac-name" title="<?php echo esc_attr($mem['name']); ?>"><?php echo esc_html($mem['name']); ?></h3>
                                        <div class="mvc-fac-desig"><?php echo esc_html($mem['desig']); ?></div>
                                        <div class="mvc-fac-tags">
                                            <span class="mvc-fac-qual-pill"><?php echo esc_html($mem['qual']); ?></span>
                                            <span class="mvc-fac-nature-pill"><?php echo esc_html($mem['nature']); ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                        <!-- COMPACT TABLE VIEW -->
                        <div class="mvc-table-container mvc-fac-table-view">
                            <table class="mvc-admin-dir-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th style="width: 32%;">Faculty Name &amp; Designation</th>
                                        <th style="width: 25%;">Highest Qualification</th>
                                        <th style="width: 18%;">Cadre / Status</th>
                                        <th>Official Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr = 1; foreach ($members as $mem): ?>
                                        <tr class="mvc-dir-tr" data-keywords="<?php echo esc_attr(strtolower($mem['name'] . ' ' . $mem['desig'] . ' ' . $mem['qual'] . ' ' . $dept_name)); ?>">
                                            <td><?php echo $sr++; ?></td>
                                            <td>
                                                <div style="font-weight: 800; color: var(--mvc-navy);">
                                                    <?php echo esc_html($mem['name']); ?>
                                                    <?php if ($mem['is_hod']): ?>
                                                        <span class="mvc-hod-badge" style="margin-left: 6px;">HOD</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div style="font-size: 12px; color: #64748B;"><?php echo esc_html($mem['desig']); ?></div>
                                            </td>
                                            <td>
                                                <span class="mvc-fac-qual-pill"><?php echo esc_html($mem['qual']); ?></span>
                                            </td>
                                            <td>
                                                <span class="mvc-fac-nature-pill"><?php echo esc_html($mem['nature']); ?></span>
                                            </td>
                                            <td style="font-size: 12px; color: #64748B;">
                                                <?php echo esc_html($mem['email']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>

            <!-- ========================================================
                 TAB 2: NON-TEACHING STAFF (GROUPED BY CADRE / WING)
                 ======================================================== -->
            <div id="mvcNonTeachingStaffView" style="display: none;">
                <!-- Cadre Fast-Jump Pills -->
                <div class="mvc-dept-pill-track">
                    <button type="button" class="mvc-dept-pill-btn is-active" onclick="mvcFilterStaffByCadre('all', this);">
                        <span>All Cadres (<?php echo count($staff_by_cadre); ?>)</span>
                    </button>
                    <?php foreach ($staff_by_cadre as $ckey => $cdata): ?>
                        <button type="button" class="mvc-dept-pill-btn" onclick="mvcFilterStaffByCadre('<?php echo esc_attr($ckey); ?>', this);">
                            <span><?php echo esc_html($cdata['icon']); ?> <?php echo esc_html($cdata['badge']); ?></span>
                            <span style="font-size:10.5px; opacity:0.8;">(<?php echo count($cdata['members']); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Cadre Sections -->
                <?php foreach ($staff_by_cadre as $ckey => $cadre): 
                    if (empty($cadre['members'])) continue;
                ?>
                    <section class="mvc-dept-block mvc-cadre-block" id="cadre-<?php echo esc_attr($ckey); ?>" data-cadre="<?php echo esc_attr($ckey); ?>">
                        <div class="mvc-dept-header">
                            <div class="mvc-dept-title-wrap">
                                <div class="mvc-dept-icon-box" style="background:#FEF3C7; color:#92400E; border-color:#FDE68A;">
                                    <?php echo esc_html($cadre['icon']); ?>
                                </div>
                                <div>
                                    <h2 class="mvc-dept-name"><?php echo esc_html($cadre['title']); ?></h2>
                                    <div style="font-size:12px; color:#64748B; margin-top:2px;"><?php echo esc_html($cadre['desc']); ?></div>
                                </div>
                            </div>
                            <div class="mvc-dept-badges">
                                <span class="mvc-dept-count-pill" style="background:rgba(0,33,71,0.07); color:var(--mvc-navy); border-color:rgba(0,33,71,0.15);">
                                    <?php echo count($cadre['members']); ?> Staff Members
                                </span>
                            </div>
                        </div>

                        <!-- CARDS VIEW -->
                        <div class="mvc-faculty-grid mvc-fac-grid-view">
                            <?php foreach ($cadre['members'] as $smem): ?>
                                <article class="mvc-fac-card" data-keywords="<?php echo esc_attr(strtolower($smem['name'] . ' ' . $smem['desig'] . ' ' . $smem['wing'] . ' ' . $smem['qual'])); ?>">
                                    <div class="mvc-fac-photo-box">
                                        <?php if (!empty($smem['photo'])): ?>
                                            <img src="<?php echo esc_url($smem['photo']); ?>" alt="<?php echo esc_attr($smem['name']); ?>" class="mvc-fac-photo" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($smem['name']); ?>&background=800000&color=ffffff';">
                                        <?php else: ?>
                                            <div class="mvc-fac-monogram" style="background:linear-gradient(135deg, #800000 0%, #5c0000 100%); color:#FEF3C7;">
                                                <?php echo esc_html($smem['monogram']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mvc-fac-info">
                                        <span class="mvc-hod-badge" style="background:#F1F5F9; border-color:#CBD5E1; color:#334155;">
                                            <?php echo esc_html($smem['wing']); ?>
                                        </span>
                                        <h3 class="mvc-fac-name" title="<?php echo esc_attr($smem['name']); ?>"><?php echo esc_html($smem['name']); ?></h3>
                                        <div class="mvc-fac-desig"><?php echo esc_html($smem['desig']); ?></div>
                                        <div class="mvc-fac-tags">
                                            <span class="mvc-fac-qual-pill"><?php echo esc_html($smem['qual']); ?></span>
                                            <span class="mvc-fac-nature-pill"><?php echo esc_html($smem['nature']); ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                        <!-- COMPACT TABLE VIEW -->
                        <div class="mvc-table-container mvc-fac-table-view">
                            <table class="mvc-admin-dir-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th style="width: 30%;">Staff Member &amp; Designation</th>
                                        <th style="width: 25%;">Assigned Department / Wing</th>
                                        <th style="width: 22%;">Highest Qualification</th>
                                        <th>Cadre Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr = 1; foreach ($cadre['members'] as $smem): ?>
                                        <tr class="mvc-dir-tr" data-keywords="<?php echo esc_attr(strtolower($smem['name'] . ' ' . $smem['desig'] . ' ' . $smem['wing'] . ' ' . $smem['qual'])); ?>">
                                            <td><?php echo $sr++; ?></td>
                                            <td>
                                                <div style="font-weight: 800; color: var(--mvc-navy);"><?php echo esc_html($smem['name']); ?></div>
                                                <div style="font-size: 12px; color: #64748B;"><?php echo esc_html($smem['desig']); ?></div>
                                            </td>
                                            <td>
                                                <div style="font-size: 12.5px; font-weight: 600; color: #475569;"><?php echo esc_html($smem['wing']); ?></div>
                                            </td>
                                            <td>
                                                <span class="mvc-fac-qual-pill"><?php echo esc_html($smem['qual']); ?></span>
                                            </td>
                                            <td>
                                                <span class="mvc-fac-nature-pill"><?php echo esc_html($smem['nature']); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>

            <!-- 9. BOTTOM GOVERNANCE GATEWAYS -->
            <section class="mvc-gw-bar">
                <a href="<?php echo esc_url($site_url . '/principal-desk/'); ?>" class="mvc-gw-btn-card">
                    <div>
                        <div class="mvc-gw-title">Principal's Desk</div>
                        <p class="mvc-gw-sub">Institutional mission, leadership welcome address, and collegiate messages.</p>
                    </div>
                    <span class="mvc-gw-arrow">Visit Desk &rarr;</span>
                </a>
                <a href="<?php echo esc_url($site_url . '/succession-list/'); ?>" class="mvc-gw-btn-card">
                    <div>
                        <div class="mvc-gw-title">Succession List</div>
                        <p class="mvc-gw-sub">Chronological register of institutional Principals since 1958.</p>
                    </div>
                    <span class="mvc-gw-arrow">View Succession &rarr;</span>
                </a>
                <a href="<?php echo esc_url($site_url . '/committees-cells/'); ?>" class="mvc-gw-btn-card">
                    <div>
                        <div class="mvc-gw-title">Statutory Committees</div>
                        <p class="mvc-gw-sub">15 official constituent committees including IQAC, Anti-Ragging, and BOS.</p>
                    </div>
                    <span class="mvc-gw-arrow">View All 15 &rarr;</span>
                </a>
                <a href="<?php echo esc_url($site_url . '/university-administration/'); ?>" class="mvc-gw-btn-card">
                    <div>
                        <div class="mvc-gw-title">University Administration</div>
                        <p class="mvc-gw-sub">VKSU Ara officers, Vice-Chancellor, Registrar, and statutory deans.</p>
                    </div>
                    <span class="mvc-gw-arrow">VKSU Governance &rarr;</span>
                </a>
            </section>

        </main>
    </div>

    <!-- Interactive JavaScript -->
    <script>
    var mvcActiveDirectory = 'faculty';
    var mvcActiveCardTable = 'cards';
    var mvcActiveDept = 'all';
    var mvcActiveCadre = 'all';

    function mvcSwitchDirectoryView(tab) {
        mvcActiveDirectory = tab;
        var btnFaculty = document.getElementById('mvcTabBtnFaculty');
        var btnStaff = document.getElementById('mvcTabBtnStaff');
        var viewFaculty = document.getElementById('mvcTeachingFacultyView');
        var viewStaff = document.getElementById('mvcNonTeachingStaffView');

        if (tab === 'staff') {
            if (btnFaculty) btnFaculty.classList.remove('is-active');
            if (btnStaff) btnStaff.classList.add('is-active');
            if (viewFaculty) viewFaculty.style.display = 'none';
            if (viewStaff) viewStaff.style.display = 'block';
        } else {
            if (btnFaculty) btnFaculty.classList.add('is-active');
            if (btnStaff) btnStaff.classList.remove('is-active');
            if (viewFaculty) viewFaculty.style.display = 'block';
            if (viewStaff) viewStaff.style.display = 'none';
        }
        mvcFilterMasterDirectory();
    }

    function mvcToggleCardTableMode(mode) {
        mvcActiveCardTable = mode;
        var btnCards = document.getElementById('mvcBtnCardMode');
        var btnTable = document.getElementById('mvcBtnTableMode');

        if (btnCards && btnTable) {
            if (mode === 'table') {
                btnCards.classList.remove('is-active');
                btnTable.classList.add('is-active');
                document.querySelectorAll('.mvc-fac-grid-view').forEach(function(el) { el.style.display = 'none'; });
                document.querySelectorAll('.mvc-fac-table-view').forEach(function(el) { el.style.display = 'block'; });
            } else {
                btnCards.classList.add('is-active');
                btnTable.classList.remove('is-active');
                document.querySelectorAll('.mvc-fac-grid-view').forEach(function(el) { el.style.display = 'grid'; });
                document.querySelectorAll('.mvc-fac-table-view').forEach(function(el) { el.style.display = 'none'; });
            }
        }
    }

    function mvcFilterFacultyByDept(dept, btn) {
        mvcActiveDept = dept;
        var buttons = document.querySelectorAll('#mvcTeachingFacultyView .mvc-dept-pill-btn');
        buttons.forEach(function(b) { b.classList.remove('is-active'); });
        if (btn) btn.classList.add('is-active');
        mvcFilterMasterDirectory();
    }

    function mvcFilterStaffByCadre(cadre, btn) {
        mvcActiveCadre = cadre;
        var buttons = document.querySelectorAll('#mvcNonTeachingStaffView .mvc-dept-pill-btn');
        buttons.forEach(function(b) { b.classList.remove('is-active'); });
        if (btn) btn.classList.add('is-active');
        mvcFilterMasterDirectory();
    }

    function mvcFilterMasterDirectory() {
        var input = document.getElementById('mvcStaffSearchInput');
        var query = input ? input.value.toLowerCase().trim() : '';

        if (mvcActiveDirectory === 'faculty') {
            var deptBlocks = document.querySelectorAll('#mvcTeachingFacultyView .mvc-dept-block');
            deptBlocks.forEach(function(block) {
                var blockDept = block.getAttribute('data-dept') || '';
                var matchesDept = (mvcActiveDept === 'all' || blockDept === mvcActiveDept);
                
                var cards = block.querySelectorAll('.mvc-fac-card');
                var trs = block.querySelectorAll('.mvc-dir-tr');
                var visibleCount = 0;

                cards.forEach(function(c) {
                    var kw = c.getAttribute('data-keywords') || '';
                    var matchesQuery = (!query || kw.indexOf(query) !== -1);
                    if (matchesDept && matchesQuery) {
                        c.style.display = 'flex';
                        visibleCount++;
                    } else {
                        c.style.display = 'none';
                    }
                });

                trs.forEach(function(tr) {
                    var kw = tr.getAttribute('data-keywords') || '';
                    var matchesQuery = (!query || kw.indexOf(query) !== -1);
                    if (matchesDept && matchesQuery) {
                        tr.style.display = '';
                    } else {
                        tr.style.display = 'none';
                    }
                });

                block.style.display = (matchesDept && visibleCount > 0) ? 'block' : 'none';
            });
        } else {
            var cadreBlocks = document.querySelectorAll('#mvcNonTeachingStaffView .mvc-cadre-block');
            cadreBlocks.forEach(function(block) {
                var blockCadre = block.getAttribute('data-cadre') || '';
                var matchesCadre = (mvcActiveCadre === 'all' || blockCadre === mvcActiveCadre);

                var cards = block.querySelectorAll('.mvc-fac-card');
                var trs = block.querySelectorAll('.mvc-dir-tr');
                var visibleCount = 0;

                cards.forEach(function(c) {
                    var kw = c.getAttribute('data-keywords') || '';
                    var matchesQuery = (!query || kw.indexOf(query) !== -1);
                    if (matchesCadre && matchesQuery) {
                        c.style.display = 'flex';
                        visibleCount++;
                    } else {
                        c.style.display = 'none';
                    }
                });

                trs.forEach(function(tr) {
                    var kw = tr.getAttribute('data-keywords') || '';
                    var matchesQuery = (!query || kw.indexOf(query) !== -1);
                    if (matchesCadre && matchesQuery) {
                        tr.style.display = '';
                    } else {
                        tr.style.display = 'none';
                    }
                });

                block.style.display = (matchesCadre && visibleCount > 0) ? 'block' : 'none';
            });
        }
    }
    </script>
    <?php
    return ob_get_clean();
}
