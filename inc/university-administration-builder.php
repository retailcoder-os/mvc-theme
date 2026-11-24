<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * University Administration Page Builder - 10/10 Standard (Beautiful & Simple)
 * Strictly synchronized with official Veer Kunwar Singh University (VKSU) benchmark data:
 * - https://vksu.ac.in/home/officers
 * - https://vksu.ac.in/home/dean_faculties
 * - https://vksu.ac.in/home/pg
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render University Administration Page
 */
function mvc_render_university_administration_page($override_form_id = null) {
    global $mvc_shortcode_context;
    $site_url = home_url();

    // Authentic portraits from local uploads
    $governor_img = $site_url . '/wp-content/uploads/governing-body/GOV_1775040359_GOV_1773653129_Lt. Gen. Syed Ata Hasnain-Photoroom-Photoroom.png';
    $vc_img       = $site_url . '/wp-content/uploads/vksu/vc.jpg';

    // Fallback if local vksu vc.jpg not present
    if (!file_exists(ABSPATH . 'wp-content/uploads/vksu/vc.jpg')) {
        $vc_img = $site_url . '/wp-content/uploads/governing-body/GOV_1775040375_GOV_1773652806_GOV_1767416782_GOV_1763905952_GOV_1763207314_-image-1761377173-sm-Photoroom-Photoroom.png';
    }

    // 1. Authentic Statutory Administrative Officers (from https://vksu.ac.in/home/officers)
    $officers = [
        [
            'name'        => 'Prof. Dr. Ram Krishna Thakur',
            'designation' => 'Registrar',
            'dept'        => 'University Central Secretariat',
            'img'         => $site_url . '/wp-content/uploads/vksu/registrar.jpg',
            'desc'        => 'Custodian of university records, common seal, statutes, and academic administration.'
        ],
        [
            'name'        => 'Dr. Lal Babu Singh',
            'designation' => 'Controller of Examinations (CoE)',
            'dept'        => 'Examination Directorate',
            'img'         => $site_url . '/wp-content/uploads/vksu/lalbabu.jpeg',
            'desc'        => 'Conduct of degree examinations, semester tabulation, admit cards, and degrees.'
        ],
        [
            'name'        => 'Dr. Rakesh Kumar Ranjan',
            'designation' => 'Proctor',
            'dept'        => 'Proctorial Board',
            'img'         => '',
            'desc'        => 'Oversees campus discipline, code of conduct, and statutory anti-ragging security.'
        ],
        [
            'name'        => 'Prof. (Dr.) Narendra Pratap Palit',
            'designation' => 'Coordinator, College Development Council (CCDC)',
            'dept'        => 'College Development Council',
            'img'         => $site_url . '/wp-content/uploads/vksu/ccdc.jpeg',
            'desc'        => 'Academic planning, UGC coordination, and infrastructure development of constituent units.'
        ],
        [
            'name'        => 'Sri Arun Prasad Sriwastava',
            'designation' => 'Financial Adviser',
            'dept'        => 'Finance & Accounts Directorate',
            'img'         => $site_url . '/wp-content/uploads/vksu/favksu.jpg',
            'desc'        => 'Advises on statutory fiscal policies, state budgetary grants, and financial governance.'
        ],
        [
            'name'        => 'Shri Shailesh Kumar Singh',
            'designation' => 'Finance Officer',
            'dept'        => 'Finance Department',
            'img'         => $site_url . '/wp-content/uploads/vksu/fovksu.jpg',
            'desc'        => 'Management of university treasury, accounts auditing, and collegiate salary disbursements.'
        ],
        [
            'name'        => 'Dr. Shambhu Sharam Sharma',
            'designation' => 'Inspector of Colleges (Arts & Commerce)',
            'dept'        => 'Affiliation & Academic Standards',
            'img'         => '',
            'desc'        => 'Inspection and regulatory compliance for affiliated and constituent collegiate programs.'
        ],
        [
            'name'        => 'Dr. Anil Kumar Sinha',
            'designation' => 'Nodal Officer, National Knowledge Network (NKN)',
            'dept'        => 'IT & Digital Infrastructure',
            'img'         => $site_url . '/wp-content/uploads/vksu/nkn.jpg',
            'desc'        => 'High-speed broadband network management and e-governance infrastructure.'
        ],
        [
            'name'        => 'Dr. Anuj Rajak',
            'designation' => 'Public Information Officer (PIO)',
            'dept'        => 'RTI & Information Wing',
            'img'         => $site_url . '/wp-content/uploads/vksu/pio.jpeg',
            'desc'        => 'Statutory implementation of Right to Information (RTI) Act, 2005.'
        ],
        [
            'name'        => 'Shri Navneet Singh',
            'designation' => 'Assistant Registrar',
            'dept'        => 'Administrative Wing',
            'img'         => '',
            'desc'        => 'Supports general administration, university establishment, and service records.'
        ],
        [
            'name'        => 'Dr. Sadhna Rawat',
            'designation' => 'Programme Coordinator, N.S.S.',
            'dept'        => 'National Service Scheme',
            'img'         => '',
            'desc'        => 'Directs youth outreach, social service camps, and community development.'
        ],
        [
            'name'        => 'Md. Wakaru Zama',
            'designation' => 'Nodal Officer (Kanya Utthan)',
            'dept'        => 'Chief Minister Kanya Utthan Yojana',
            'img'         => '',
            'desc'        => 'Facilitates state government incentive schemes for female graduate scholars.'
        ],
        [
            'name'        => 'Dr. Om Prakash Dubey',
            'designation' => 'Nodal Officer (GeM Portal)',
            'dept'        => 'Procurement Cell',
            'img'         => '',
            'desc'        => 'Government e-Marketplace (GeM) public procurement and institutional tenders.'
        ],
        [
            'name'        => 'Sri Vivekanand Pathak',
            'designation' => 'Legal Advisor',
            'dept'        => 'Legal Cell',
            'img'         => '',
            'desc'        => 'Legal counsel and liaison for Hon’ble High Court and judicial proceedings.'
        ]
    ];

    // 2. Authentic Deans of Faculties (from https://vksu.ac.in/home/dean_faculties)
    $deans = [
        [
            'sr'      => 1,
            'name'    => 'Prof. (Dr.) Sheo Prasad Singh',
            'faculty' => 'Dean, Faculty of Science',
            'phone'   => '*****92112',
            'email'   => 'deanscience@vksu.ac.in',
            'desc'    => 'Oversees Physics, Chemistry, Botany, Zoology, Mathematics, and MCA postgraduate research.'
        ],
        [
            'sr'      => 2,
            'name'    => 'Prof. (Dr.) Awadh Bihari Singh',
            'faculty' => 'Dean, Faculty of Social Science',
            'phone'   => '*****63994',
            'email'   => 'deansocial@vksu.ac.in',
            'desc'    => 'Directs History, Political Science, Economics, Sociology, Psychology, and Public Administration.'
        ],
        [
            'sr'      => 3,
            'name'    => 'Prof. (Dr.) M.N. Pandey',
            'faculty' => 'Dean, Faculty of Commerce',
            'phone'   => '*****61029',
            'email'   => 'deancommerce@vksu.ac.in',
            'desc'    => 'Heads Commerce studies, MBA management programs, and financial research.'
        ],
        [
            'sr'      => 4,
            'name'    => 'Dr. Shambhu Sharam Sharma',
            'faculty' => 'Dean, Faculty of Humanities',
            'phone'   => '*****03911',
            'email'   => 'deanhumanities@vksu.ac.in',
            'desc'    => 'Coordinates Hindi, English, Sanskrit, Bhojpuri, Philosophy, Urdu & Persian departments.'
        ]
    ];

    // 3. Authentic P.G. Department Heads (from https://vksu.ac.in/home/pg)
    $pg_departments = [
        ['sr' => 1,  'dept' => 'Physics',               'hod' => 'Prof. Binay Kumar Mishra',         'phone' => 'Contact via Office'],
        ['sr' => 2,  'dept' => 'Chemistry',             'hod' => 'Dr. Abhay Kumar Singh Bhoshala',  'phone' => 'Contact via Office'],
        ['sr' => 3,  'dept' => 'Botany',                 'hod' => 'Prof. Ahmad Masood',              'phone' => 'Contact via Office'],
        ['sr' => 4,  'dept' => 'Zoology',                'hod' => 'Prof. Farida Bano',               'phone' => 'Contact via Office'],
        ['sr' => 5,  'dept' => 'Mathematics',            'hod' => 'Dr. Guru Dayal Singh',            'phone' => 'Contact via Office'],
        ['sr' => 6,  'dept' => 'Hindi',                  'hod' => 'Prof. Mritunjay Singh',           'phone' => 'Contact via Office'],
        ['sr' => 7,  'dept' => 'English',                'hod' => 'Prof. Dr. Nikhil Kumar',          'phone' => 'Contact via Office'],
        ['sr' => 8,  'dept' => 'Sanskrit',               'hod' => 'Dr. Anuj Rajak',                  'phone' => 'Contact via Office'],
        ['sr' => 9,  'dept' => 'Urdu & Persian',         'hod' => 'Prof. Iqbal Ahmad',               'phone' => 'Contact via Office'],
        ['sr' => 10, 'dept' => 'Political Science',      'hod' => 'Prof. Kundan Kumar Singh',        'phone' => 'Contact via Office'],
        ['sr' => 11, 'dept' => 'Public Administration',  'hod' => 'Prof. Ramesh Kumar Singh',        'phone' => 'Contact via Office'],
        ['sr' => 12, 'dept' => 'History',                'hod' => 'Prof. Deepak Prakash Vardhan',    'phone' => 'Contact via Office'],
        ['sr' => 13, 'dept' => 'Psychology',             'hod' => 'Dr. Latika Verma',                'phone' => 'Contact via Office'],
        ['sr' => 14, 'dept' => 'Sociology',              'hod' => 'Dr. Awadh Bihari Singh',          'phone' => 'Contact via Office'],
        ['sr' => 15, 'dept' => 'Geography',              'hod' => 'Prof. Lalit Sagar',               'phone' => 'Contact via Office'],
        ['sr' => 16, 'dept' => 'Prakrit & Jainology',    'hod' => 'Prof. D.N. Choudhary',            'phone' => 'Contact via Office'],
        ['sr' => 17, 'dept' => 'Philosophy',             'hod' => 'Dr. Shambhu Shankar Sharma',      'phone' => 'Contact via Office'],
        ['sr' => 18, 'dept' => 'Economics',              'hod' => 'Prof. Dhirendra Kumar Singh',     'phone' => 'Contact via Office'],
        ['sr' => 19, 'dept' => 'Bhojpuri',               'hod' => 'Prof. Diwakar Pandey',            'phone' => 'Contact via Office'],
        ['sr' => 20, 'dept' => 'Commerce',               'hod' => 'Prof. Dr. Sanjay Kumar Singh',    'phone' => 'Contact via Office'],
        ['sr' => 21, 'dept' => 'Director (MBA)',         'hod' => 'Prof. Sanjay Kumar Singh',        'phone' => 'Contact via Office'],
        ['sr' => 22, 'dept' => 'Director (MCA)',         'hod' => 'Dr. Anil Kumar Singh',            'phone' => 'Contact via Office'],
        ['sr' => 23, 'dept' => 'Incharge (B.Ed.)',       'hod' => 'Prof. K.K. Singh',                'phone' => 'Contact via Office'],
        ['sr' => 24, 'dept' => 'Ancient Indian History (AI & AS)', 'hod' => 'Prof. Arvind Kumar Singh', 'phone' => 'Contact via Office']
    ];

    // Dynamic Database Integration with Authentic Fallbacks
    $officers = mvc_get_page_array(806, 'vksu_officers_data', $officers);
    $deans    = mvc_get_page_array(806, 'vksu_deans_data', $deans);
    $pg_depts = mvc_get_page_array(806, 'vksu_pg_depts_data', $pg_depts);

    $governor_img   = mvc_get_page_meta(806, 'vksu_governor_img', $governor_img);
    $governor_name  = mvc_get_page_meta(806, 'vksu_governor_name', 'His Excellency the Governor of Bihar');
    $governor_title = mvc_get_page_meta(806, 'vksu_governor_title', 'Ex-Officio Chancellor of State Universities');

    $vc_img   = mvc_get_page_meta(806, 'vksu_vc_img', $vc_img);
    $vc_name  = mvc_get_page_meta(806, 'vksu_vc_name', 'Prof. Shailendra Kumar Chaturvedi');
    $vc_title = mvc_get_page_meta(806, 'vksu_vc_title', 'Hon’ble Vice-Chancellor • VKSU, Ara');

    ob_start();
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-gold: #D4AF37;
            --mvc-slate: #0F172A;
            --mvc-muted: #64748B;
            --mvc-border: #E2E8F0;
        }

        /* Breakout to 100% Full-Width Stretched */
        body.page-template-template-university-administration #page,
        body.page-template-template-university-administration .site-content,
        body.page-template-template-university-administration .ast-container,
        body.page-template-template-university-administration #content,
        body.page-template-template-university-administration #primary,
        body.page-template-template-university-administration #main,
        body.page-template-template-university-administration .entry-content {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .mvc-univ-wrapper {
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
        .mvc-univ-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #0a3366 100%);
            color: #FFFFFF;
            padding: clamp(40px, 5vw, 64px) clamp(20px, 4vw, 60px);
            text-align: center;
            border-bottom: 4px solid var(--mvc-gold);
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .mvc-univ-hero-inner {
            max-width: 100%;
            margin: 0 auto;
        }

        .mvc-univ-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 16px;
        }

        .mvc-univ-breadcrumb a {
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.2s;
        }

        .mvc-univ-breadcrumb a:hover {
            color: var(--mvc-gold);
        }

        .mvc-univ-hero h1 {
            font-size: clamp(24px, 3.8vw, 38px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .mvc-univ-hero-hindi {
            font-size: clamp(15px, 2.2vw, 19px);
            font-weight: 600;
            color: var(--mvc-gold);
            margin-bottom: 12px;
        }

        .mvc-univ-hero-desc {
            font-size: 14.5px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin: 0 auto;
            max-width: 900px;
        }

        /* Container - 100% Full Width */
        .mvc-univ-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 40px clamp(20px, 4vw, 60px) 0 !important;
            box-sizing: border-box !important;
        }

        /* Apex Governance: Chancellor & Vice Chancellor */
        .mvc-apex-stack {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(480px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .mvc-apex-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.05);
            display: grid;
            grid-template-columns: 140px 1fr;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .mvc-apex-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0, 33, 71, 0.08);
        }

        .mvc-apex-photo-col {
            background: linear-gradient(180deg, #F1F5F9 0%, #E2E8F0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-right: 1px solid var(--mvc-border);
        }

        .mvc-apex-photo-wrap {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.15);
            background: #FFFFFF;
        }

        .mvc-apex-photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .mvc-apex-info-col {
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .mvc-apex-tag {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--mvc-maroon);
            margin-bottom: 4px;
        }

        .mvc-apex-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-apex-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-slate);
            margin-bottom: 8px;
        }

        .mvc-apex-meta {
            font-size: 12.5px;
            color: var(--mvc-muted);
            line-height: 1.5;
        }

        .mvc-apex-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--mvc-navy);
            text-decoration: none;
            margin-top: 8px;
        }

        .mvc-apex-link:hover {
            color: var(--mvc-maroon);
        }

        /* Benchmark Tab Switcher (Officers, Deans, PG Depts) */
        .mvc-vksu-tabs-nav {
            display: flex;
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 6px;
            gap: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.04);
            overflow-x: auto;
        }

        .mvc-vksu-tab-btn {
            flex: 1;
            min-width: 200px;
            background: transparent;
            border: none;
            padding: 13px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
        }

        .mvc-vksu-tab-btn:hover {
            background: #F1F5F9;
            color: var(--mvc-navy);
        }

        .mvc-vksu-tab-btn.active {
            background: var(--mvc-navy);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.2);
        }

        .mvc-tab-badge {
            background: rgba(255, 255, 255, 0.25);
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 999px;
            color: inherit;
        }

        .mvc-vksu-tab-btn:not(.active) .mvc-tab-badge {
            background: #E2E8F0;
            color: #475569;
        }

        /* Tab Content Section */
        .mvc-vksu-panel {
            display: none;
        }

        .mvc-vksu-panel.active {
            display: block;
        }

        .mvc-panel-intro {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .mvc-panel-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-panel-sub {
            font-size: 13.5px;
            color: var(--mvc-muted);
            margin: 0;
        }

        .mvc-panel-ref {
            font-size: 12px;
            color: var(--mvc-maroon);
            font-weight: 700;
            background: #FEE2E2;
            padding: 4px 10px;
            border-radius: 6px;
        }

        /* 1. Officers Grid */
        .mvc-officers-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .mvc-officer-item-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 33, 71, 0.03);
            display: flex;
            gap: 16px;
            align-items: flex-start;
            transition: all 0.2s ease;
        }

        .mvc-officer-item-card:hover {
            border-color: var(--mvc-navy);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 33, 71, 0.07);
        }

        .mvc-officer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            background: #E2E8F0;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--mvc-navy);
            font-size: 20px;
        }

        .mvc-officer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .mvc-officer-details {
            flex: 1;
        }

        .mvc-officer-desig {
            font-size: 12px;
            font-weight: 800;
            color: var(--mvc-maroon);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .mvc-officer-h3 {
            font-size: 15px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
            line-height: 1.3;
        }

        .mvc-officer-wing {
            font-size: 12.5px;
            color: #475569;
            margin-bottom: 6px;
        }

        .mvc-officer-role-desc {
            font-size: 12px;
            color: var(--mvc-muted);
            line-height: 1.45;
        }

        /* 2. Deans Grid */
        .mvc-deans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .mvc-dean-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 33, 71, 0.03);
            border-top: 4px solid var(--mvc-navy);
            transition: all 0.2s ease;
        }

        .mvc-dean-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 33, 71, 0.08);
        }

        .mvc-dean-faculty {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--mvc-maroon);
            margin-bottom: 8px;
        }

        .mvc-dean-name {
            font-size: 17px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 10px;
        }

        .mvc-dean-desc {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .mvc-dean-contact {
            background: #F8FAFC;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 12.5px;
            color: var(--mvc-muted);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .mvc-dean-contact strong {
            color: var(--mvc-slate);
        }

        /* 3. P.G. Departments Table */
        .mvc-pg-table-wrap {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 33, 71, 0.04);
            margin-bottom: 40px;
        }

        .mvc-pg-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13.5px;
        }

        .mvc-pg-table thead th {
            background: #F8FAFC;
            color: var(--mvc-navy);
            font-weight: 800;
            padding: 14px 18px;
            text-align: left;
            border-bottom: 2px solid var(--mvc-border);
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mvc-pg-table tbody td {
            padding: 12px 18px;
            border-bottom: 1px solid #F1F5F9;
            color: #334155;
            vertical-align: middle;
        }

        .mvc-pg-table tbody tr:hover {
            background: #F8FAFC;
        }

        .mvc-pg-table tbody tr:last-child td {
            border-bottom: none;
        }

        .mvc-dept-badge {
            display: inline-block;
            background: #E0E7FF;
            color: #3730A3;
            font-weight: 700;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 6px;
        }

        /* Gateways Deck */
        .mvc-gateways-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-top: 30px;
        }

        .mvc-gateway-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 12px;
            padding: 18px 20px;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .mvc-gateway-card:hover {
            border-color: var(--mvc-navy);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 33, 71, 0.06);
        }

        .mvc-gateway-title {
            font-size: 14.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-gateway-desc {
            font-size: 12.5px;
            color: #64748B;
            line-height: 1.45;
            margin-bottom: 12px;
        }

        .mvc-gateway-arrow {
            font-size: 12px;
            font-weight: 800;
            color: var(--mvc-navy);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .mvc-gateway-card:hover .mvc-gateway-arrow {
            color: var(--mvc-maroon);
        }

        /* Mobile Optimization */
        @media (max-width: 820px) {
            .mvc-apex-stack {
                grid-template-columns: 1fr;
            }
            .mvc-apex-card {
                grid-template-columns: 1fr;
            }
            .mvc-apex-photo-col {
                border-right: none;
                border-bottom: 1px solid var(--mvc-border);
                padding: 16px;
            }
            .mvc-pg-table thead {
                display: none;
            }
            .mvc-pg-table, .mvc-pg-table tbody, .mvc-pg-table tr, .mvc-pg-table td {
                display: block;
                width: 100%;
            }
            .mvc-pg-table tr {
                border-bottom: 1px solid var(--mvc-border);
                padding: 12px;
            }
            .mvc-pg-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 6px 0;
                border-bottom: 1px dashed #F1F5F9;
                font-size: 13px;
                text-align: right;
            }
            .mvc-pg-table td:last-child {
                border-bottom: none;
            }
            .mvc-pg-table td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--mvc-navy);
                text-align: left;
                font-size: 12px;
                text-transform: uppercase;
            }
        }
    </style>

    <div class="mvc-univ-wrapper">
        <!-- Hero Banner -->
        <header class="mvc-univ-hero">
            <div class="mvc-univ-hero-inner">
                <nav class="mvc-univ-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>">Administration</a>
                    <span>/</span>
                    <span style="color: var(--mvc-gold);">University Administration</span>
                </nav>

                <h1 style="margin: 0 0 16px;">Veer Kunwar Singh University (VKSU) Administration</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mvc-univ-container">
            
            <!-- Apex University Governance (Chancellor & Vice Chancellor) -->
            <div class="mvc-apex-stack">
                
                <!-- Chancellor -->
                <article class="mvc-apex-card" itemscope itemtype="https://schema.org/Person">
                    <div class="mvc-apex-photo-col">
                        <div class="mvc-apex-photo-wrap">
                            <img src="<?php echo esc_url($governor_img); ?>" alt="His Excellency the Governor of Bihar" itemprop="image" loading="lazy">
                        </div>
                    </div>
                    <div class="mvc-apex-info-col">
                        <div class="mvc-apex-tag">Visitor &amp; Constitutional Head</div>
                        <h2 class="mvc-apex-name" itemprop="name"><?php echo esc_html($governor_name); ?></h2>
                        <div class="mvc-apex-title" itemprop="jobTitle"><?php echo esc_html($governor_title); ?></div>
                        <div class="mvc-apex-meta">
                            🏛️ Raj Bhavan, Bihar Lok Bhavan, Patna – 800 022<br>
                            📞 Secretariat: 0612-2786100
                        </div>
                        <a href="https://governor.bih.nic.in" target="_blank" rel="noopener" class="mvc-apex-link">
                            Visit Raj Bhavan Secretariat ↗
                        </a>
                    </div>
                </article>

                <!-- Vice-Chancellor -->
                <article class="mvc-apex-card" itemscope itemtype="https://schema.org/Person">
                    <div class="mvc-apex-photo-col">
                        <div class="mvc-apex-photo-wrap">
                            <img src="<?php echo esc_url($vc_img); ?>" alt="<?php echo esc_attr($vc_name); ?>" itemprop="image" loading="lazy">
                        </div>
                    </div>
                    <div class="mvc-apex-info-col">
                        <div class="mvc-apex-tag">Academic &amp; Executive Head</div>
                        <h2 class="mvc-apex-name" itemprop="name"><?php echo esc_html($vc_name); ?></h2>
                        <div class="mvc-apex-title" itemprop="jobTitle"><?php echo esc_html($vc_title); ?></div>
                        <div class="mvc-apex-meta">
                            🏛️ Central Secretariat, Katira, Ara – 802301<br>
                            📞 06182-239369 &bull; 🌐 vksu.ac.in
                        </div>
                        <a href="https://vksu.ac.in/home/vc" target="_blank" rel="noopener" class="mvc-apex-link">
                            Visit Vice-Chancellor’s Desk ↗
                        </a>
                    </div>
                </article>

            </div>

            <!-- Benchmark Tabs Navigation (Officers, Deans, PG Departments) -->
            <nav class="mvc-vksu-tabs-nav" aria-label="VKSU Administration Branches">
                <button type="button" class="mvc-vksu-tab-btn active" onclick="mvcSwitchVksuTab(this, 'officers')">
                    🏛️ Administrative Officers <span class="mvc-tab-badge"><?php echo count($officers); ?></span>
                </button>
                <button type="button" class="mvc-vksu-tab-btn" onclick="mvcSwitchVksuTab(this, 'deans')">
                    🎓 Deans of Faculties <span class="mvc-tab-badge"><?php echo count($deans); ?></span>
                </button>
                <button type="button" class="mvc-vksu-tab-btn" onclick="mvcSwitchVksuTab(this, 'pg')">
                    📚 P.G. Departments &amp; HODs <span class="mvc-tab-badge"><?php echo count($pg_departments); ?></span>
                </button>
            </nav>

            <!-- TAB 1: Statutory Administrative Officers (https://vksu.ac.in/home/officers) -->
            <section class="mvc-vksu-panel active" id="panel-officers">
                <div class="mvc-panel-intro">
                    <div>
                        <h2 class="mvc-panel-title">Statutory Administrative Officers of VKSU</h2>
                        <p class="mvc-panel-sub">Key executive officers administering university governance, examinations, discipline, and finance</p>
                    </div>
                    <div class="mvc-panel-ref">Source: vksu.ac.in/home/officers</div>
                </div>

                <div class="mvc-officers-card-grid">
                    <?php foreach ($officers as $off): ?>
                        <div class="mvc-officer-item-card">
                            <div class="mvc-officer-avatar">
                                <?php if (!empty($off['img'])): ?>
                                    <img src="<?php echo esc_url($off['img']); ?>" alt="<?php echo esc_attr($off['name']); ?>" loading="lazy">
                                <?php else: ?>
                                    <span><?php echo esc_html(substr($off['name'], 0, 1)); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="mvc-officer-details">
                                <div class="mvc-officer-desig"><?php echo esc_html($off['designation']); ?></div>
                                <h3 class="mvc-officer-h3"><?php echo esc_html($off['name']); ?></h3>
                                <div class="mvc-officer-wing">📍 <?php echo esc_html($off['dept']); ?></div>
                                <div class="mvc-officer-role-desc"><?php echo esc_html($off['desc']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- TAB 2: Deans of Faculties (https://vksu.ac.in/home/dean_faculties) -->
            <section class="mvc-vksu-panel" id="panel-deans">
                <div class="mvc-panel-intro">
                    <div>
                        <h2 class="mvc-panel-title">Deans of Faculties</h2>
                        <p class="mvc-panel-sub">Academic leadership directing doctoral research, curricula, and pedagogy across faculties</p>
                    </div>
                    <div class="mvc-panel-ref">Source: vksu.ac.in/home/dean_faculties</div>
                </div>

                <div class="mvc-deans-grid">
                    <?php foreach ($deans as $d): ?>
                        <div class="mvc-dean-card">
                            <div class="mvc-dean-faculty"><?php echo esc_html($d['faculty']); ?></div>
                            <h3 class="mvc-dean-name"><?php echo esc_html($d['name']); ?></h3>
                            <p class="mvc-dean-desc"><?php echo esc_html($d['desc']); ?></p>
                            <div class="mvc-dean-contact">
                                <div><strong>📞 Mobile:</strong> <?php echo esc_html($d['phone']); ?></div>
                                <div><strong>✉️ Email:</strong> <?php echo esc_html($d['email']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- TAB 3: P.G. Departments & HODs (https://vksu.ac.in/home/pg) -->
            <section class="mvc-vksu-panel" id="panel-pg">
                <div class="mvc-panel-intro">
                    <div>
                        <h2 class="mvc-panel-title">Postgraduate (P.G.) Departments &amp; Heads</h2>
                        <p class="mvc-panel-sub">University P.G. departments conducting Master’s degrees and doctoral research programs</p>
                    </div>
                    <div class="mvc-panel-ref">Source: vksu.ac.in/home/pg</div>
                </div>

                <div class="mvc-pg-table-wrap">
                    <table class="mvc-pg-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">S.N.</th>
                                <th>Department Name</th>
                                <th>Head of Department (HOD)</th>
                                <th>Contact / Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pg_departments as $pg): ?>
                                <tr>
                                    <td data-label="S.N."><?php echo esc_html($pg['sr']); ?></td>
                                    <td data-label="Department">
                                        <strong><?php echo esc_html($pg['dept']); ?></strong>
                                    </td>
                                    <td data-label="HOD Name"><?php echo esc_html($pg['hod']); ?></td>
                                    <td data-label="Contact"><?php echo esc_html($pg['phone']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Official University Gateways -->
            <div class="mvc-panel-intro" style="margin-top: 20px;">
                <div>
                    <h2 class="mvc-panel-title">Official University Gateways</h2>
                    <p class="mvc-panel-sub">Direct access to VKSU university examination portals, state secretariats, and collegiate links</p>
                </div>
            </div>

            <div class="mvc-gateways-grid">
                <a href="https://vksu.ac.in" target="_blank" rel="noopener" class="mvc-gateway-card">
                    <div>
                        <div class="mvc-gateway-title">VKSU Main Portal</div>
                        <p class="mvc-gateway-desc">Official website of Veer Kunwar Singh University, Ara.</p>
                    </div>
                    <span class="mvc-gateway-arrow">Visit vksu.ac.in ↗</span>
                </a>

                <a href="https://vksu.ac.in/examination" target="_blank" rel="noopener" class="mvc-gateway-card">
                    <div>
                        <div class="mvc-gateway-title">Examinations &amp; Results</div>
                        <p class="mvc-gateway-desc">Semester routines, admit cards, and degree tabulation.</p>
                    </div>
                    <span class="mvc-gateway-arrow">Check Results ↗</span>
                </a>

                <a href="https://governor.bih.nic.in" target="_blank" rel="noopener" class="mvc-gateway-card">
                    <div>
                        <div class="mvc-gateway-title">Raj Bhavan Patna</div>
                        <p class="mvc-gateway-desc">Chancellor's Secretariat and statutory orders for universities.</p>
                    </div>
                    <span class="mvc-gateway-arrow">Visit Raj Bhavan ↗</span>
                </a>

                <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>" class="mvc-gateway-card">
                    <div>
                        <div class="mvc-gateway-title">College Administration</div>
                        <p class="mvc-gateway-desc">Return to M.V. College leadership and administrative offices.</p>
                    </div>
                    <span class="mvc-gateway-arrow">Our Administration &rarr;</span>
                </a>
            </div>

            

        </main>
    </div>

    <script>
        function mvcSwitchVksuTab(btn, targetPanelId) {
            // Update button states
            var buttons = document.querySelectorAll('.mvc-vksu-tab-btn');
            buttons.forEach(function(b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            // Update panels
            var panels = document.querySelectorAll('.mvc-vksu-panel');
            panels.forEach(function(p) {
                p.classList.remove('active');
            });
            var target = document.getElementById('panel-' + targetPanelId);
            if (target) {
                target.classList.add('active');
            }
        }
    </script>
    <?php
    return ob_get_clean();
}
