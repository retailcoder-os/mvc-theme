<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Our Administration Page Builder - Minimal, Beautiful & Authoritative Roster
 * Displays authentic collegiate administrative leadership & statutory officer roster.
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

    // Authentic Principal photo
    $principal_img = $site_url . '/wp-content/uploads/live_imported/leader_GOV_1773653028_GOV_1769493095_WhatsApp Image 2026-01-26 at 12.38.38 PM-Photoroom-277x300.webp';
    if (!file_exists(ABSPATH . 'wp-content/uploads/live_imported/leader_GOV_1773653028_GOV_1769493095_WhatsApp Image 2026-01-26 at 12.38.38 PM-Photoroom-277x300.webp')) {
        $principal_img = $site_url . '/wp-content/uploads/governing-body/GOV_1773653028_GOV_1769493095_WhatsApp Image 2026-01-26 at 12.38.38 PM-Photoroom.png';
    }

    // 12 Authentic Collegiate Statutory Administrative Portfolios
    $roster = [
        [
            'id'          => 'adm-1',
            'role'        => 'Principal & Head of Institution',
            'name'        => 'Prof. (Dr.) Krishna Kant Singh',
            'desig'       => 'Principal & Institutional Head',
            'dept'        => 'Collegiate Executive Secretariat',
            'category'    => 'academic',
            'badge'       => 'Apex Executive',
            'scope'       => 'Presiding over collegiate academic standards, university accord, financial oversight, IQAC, and institutional governance.',
            'office'      => 'Principal’s Chamber, Administrative Block',
            'email'       => 'principalmvcbuxar@gmail.com',
            'phone'       => '06183-295055',
            'photo'       => $principal_img,
        ],
        [
            'id'          => 'adm-2',
            'role'        => 'Public Relations Officer (PRO) & Grievance Convener',
            'name'        => 'Dr. Saikat Debnath',
            'desig'       => 'Assistant Professor & PRO',
            'dept'        => 'Media Cell & Student Grievance Redressal',
            'category'    => 'discipline',
            'badge'       => 'PRO & Grievance',
            'scope'       => 'Institutional public affairs, print/digital media coverage, press releases, and student grievance enquiry tracking.',
            'office'      => 'Media Secretariat, Main Building',
            'email'       => 'sktdebnath@gmail.com',
            'phone'       => '06183-295055',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764230345-300x300.webp',
        ],
        [
            'id'          => 'adm-3',
            'role'        => 'Convener, Anti-Ragging Cell & ELC Nodal Officer',
            'name'        => 'Dr. Ras Bihari Sharma',
            'desig'       => 'Assistant Professor, Department of History',
            'dept'        => 'Anti-Ragging Squad & Electoral Literacy Club',
            'category'    => 'discipline',
            'badge'       => 'Vigilance & ELC',
            'scope'       => 'Zero-tolerance anti-ragging compliance under UGC norms, campus vigil squad, and voter awareness literacy campaigns.',
            'office'      => 'Anti-Ragging Cell, Ground Floor',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '1800-180-5522',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764235604-300x300.webp',
        ],
        [
            'id'          => 'adm-4',
            'role'        => 'Associate NCC Officer (ANO) & Convener, Library',
            'name'        => 'Dr. Yogershi Rajpoot',
            'desig'       => 'Assistant Professor & ANO',
            'dept'        => 'NCC Company & Central Library Committee',
            'category'    => 'youth',
            'badge'       => 'NCC & Library',
            'scope'       => 'Defense training of NCC cadet company, parade drills, B/C cert preparation, and automated library learning resources.',
            'office'      => 'NCC Pavilion & Central Library',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764231326-300x300.webp',
        ],
        [
            'id'          => 'adm-5',
            'role'        => 'Programme Officer, National Service Scheme (NSS)',
            'name'        => 'Dr. Birendra Prasad',
            'desig'       => 'Assistant Professor & NSS Programme Officer',
            'dept'        => 'National Service Scheme (NSS Unit)',
            'category'    => 'youth',
            'badge'       => 'Community Extension',
            'scope'       => 'Social welfare camps, health checkups, environmental cleanliness drives, disaster relief, and youth leadership training.',
            'office'      => 'NSS Office, Campus Quad',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764589048-300x300.webp',
        ],
        [
            'id'          => 'adm-6',
            'role'        => 'Sports Coordinator & Physical Education In-Charge',
            'name'        => 'Dr. Awanish Kumar Pandey',
            'desig'       => 'Assistant Professor & Sports In-Charge',
            'dept'        => 'Sports Cell & Athletics Board',
            'category'    => 'youth',
            'badge'       => 'Athletics & Games',
            'scope'       => 'Inter-collegiate tournament coordination, athletic championships, yoga training, indoor sports hall, and field upkeep.',
            'office'      => 'Sports Complex & Gymnasium',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764588422-300x300.webp',
        ],
        [
            'id'          => 'adm-7',
            'role'        => 'Convener, Career & Counselling Cell',
            'name'        => 'Dr. Arvind Verma',
            'desig'       => 'Assistant Professor',
            'dept'        => 'Career Counselling & Placement Assistance',
            'category'    => 'welfare',
            'badge'       => 'Career & Placement',
            'scope'       => 'Competitive exam workshops (BPSC/UGC-NET), personality mentoring, corporate recruitment fairs, and vocational training.',
            'office'      => 'Career Counselling Room, 1st Floor',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764589583-300x300.webp',
        ],
        [
            'id'          => 'adm-8',
            'role'        => 'Presiding Officer, Gender Sensitisation & ICC',
            'name'        => 'Dr. Archana Pandey',
            'desig'       => 'Assistant Professor',
            'dept'        => 'Internal Complaints Committee (ICC)',
            'category'    => 'welfare',
            'badge'       => 'ICC & Gender Equity',
            'scope'       => 'Statutory prevention of sexual harassment on campus, female scholar welfare, counselling, and gender awareness symposia.',
            'office'      => 'Girls Common Room Wing',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1770702343-300x300.webp',
        ],
        [
            'id'          => 'adm-9',
            'role'        => 'Convener, Equal Opportunity Cell',
            'name'        => 'Prof. (Dr.) Rajesh Kumar',
            'desig'       => 'Professor',
            'dept'        => 'Equal Opportunity & Inclusion Directorate',
            'category'    => 'welfare',
            'badge'       => 'Inclusion & Equity',
            'scope'       => 'Affirmative action, fee waiver schemes, social equity enforcement, and support infrastructure for SC/ST/OBC scholars.',
            'office'      => 'Equal Opportunity Desk',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764230560-300x300.webp',
        ],
        [
            'id'          => 'adm-10',
            'role'        => 'Coordinator, Alumni Association Liaison',
            'name'        => 'Sri Arvind Kumar',
            'desig'       => 'Assistant Professor & Coordinator',
            'dept'        => 'Alumni Association Network',
            'category'    => 'services',
            'badge'       => 'Alumni Relations',
            'scope'       => 'Alumni registration portal, annual alumni conventions, collegiate mentorship network, and developmental partnerships.',
            'office'      => 'Alumni Liaison Office',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '',
            'photo'       => $site_url . '/wp-content/uploads/live_imported/images/Profile_1764307053-300x300.webp',
        ],
        [
            'id'          => 'adm-11',
            'role'        => 'Bursar & Accounts Directorate',
            'name'        => 'Finance & Accounts Office',
            'desig'       => 'Statutory Accounts In-Charge / Bursar',
            'dept'        => 'Treasury & Accounts Wing',
            'category'    => 'services',
            'badge'       => 'Financial Administration',
            'scope'       => 'Institutional fee collection, university remittance, salary disbursement, UGC development grants, and statutory audits.',
            'office'      => 'Accounts Department, Admin Block',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '06183-295055',
            'photo'       => '',
            'icon'        => '💰',
        ],
        [
            'id'          => 'adm-12',
            'role'        => 'Public Information Officer (PIO / RTI)',
            'name'        => 'RTI Transparency Directorate',
            'desig'       => 'Statutory Public Information Officer',
            'dept'        => 'Right to Information Cell',
            'category'    => 'services',
            'badge'       => 'RTI Transparency',
            'scope'       => 'Statutory compliance under RTI Act 2005, timely institutional disclosures, and transparent public information tracking.',
            'office'      => 'Administrative Secretariat',
            'email'       => 'mvcollegebuxar@gmail.com',
            'phone'       => '06183-295055',
            'photo'       => '',
            'icon'        => '⚖️',
        ],
    ];

    ob_start();
    ?>
    <style>
        :root {
            --mvc-navy: #002147;
            --mvc-navy-dark: #00152e;
            --mvc-maroon: #800000;
            --mvc-maroon-dark: #5c0000;
            --mvc-gold: #D4AF37;
            --mvc-gold-light: #FEF3C7;
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
            box-sizing: border-box !important;
        }

        /* 1. HERO HEADER (Minimal, Sleek) */
        .mvc-admin-hero {
            background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, #0a3366 100%);
            color: #FFFFFF;
            padding: clamp(35px, 4.5vw, 56px) clamp(20px, 4vw, 48px);
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 14px;
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
            margin-bottom: 12px;
        }

        .mvc-admin-hero h1 {
            font-size: clamp(24px, 3.5vw, 36px);
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 20px;
            letter-spacing: -0.5px;
        }

        /* Hero KPI Strip */
        .mvc-admin-kpi-ribbon {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .mvc-admin-kpi-item {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 10px;
            padding: 12px 16px;
            backdrop-filter: blur(4px);
        }

        .mvc-admin-kpi-title {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
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
            margin: 32px auto 0;
            padding: 0 20px;
            box-sizing: border-box;
        }

        /* 3. PRINCIPAL SPOTLIGHT CARD */
        .mvc-principal-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            padding: clamp(20px, 3vw, 32px);
            box-shadow: 0 6px 20px rgba(0, 33, 71, 0.05);
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 28px;
            align-items: center;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .mvc-principal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, var(--mvc-maroon) 0%, var(--mvc-gold) 100%);
        }

        .mvc-principal-photo-box {
            position: relative;
            width: 140px;
            height: 140px;
            flex-shrink: 0;
        }

        .mvc-principal-photo {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #FFFFFF;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.15);
            display: block;
            background: #F1F5F9;
        }

        .mvc-principal-status-dot {
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 20px;
            height: 20px;
            background: #10B981;
            border: 3px solid #FFFFFF;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .mvc-principal-details {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .mvc-principal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 800;
            color: var(--mvc-maroon);
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .mvc-principal-name {
            font-size: clamp(20px, 2.5vw, 26px);
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0;
            line-height: 1.25;
        }

        .mvc-principal-role {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            margin: 0 0 6px;
        }

        .mvc-principal-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 13px;
            color: var(--mvc-muted);
            margin-bottom: 12px;
        }

        .mvc-principal-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .mvc-principal-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .mvc-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .mvc-btn-primary {
            background: var(--mvc-maroon);
            color: #FFFFFF !important;
            box-shadow: 0 2px 8px rgba(128, 0, 0, 0.2);
        }

        .mvc-btn-primary:hover {
            background: var(--mvc-maroon-dark);
            transform: translateY(-1.5px);
        }

        .mvc-btn-secondary {
            background: #EFF6FF;
            color: var(--mvc-navy) !important;
            border: 1px solid #BFDBFE;
        }

        .mvc-btn-secondary:hover {
            background: #DBEAFE;
            transform: translateY(-1.5px);
        }

        /* 4. ROSTER TOOLBAR */
        .mvc-roster-toolbar {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .mvc-roster-search-box {
            position: relative;
            flex: 1;
            min-width: 240px;
        }

        .mvc-roster-search-box input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border: 1.5px solid var(--mvc-border);
            border-radius: 8px;
            font-size: 13.5px;
            outline: none;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }

        .mvc-roster-search-box input:focus {
            border-color: var(--mvc-navy);
        }

        .mvc-roster-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            pointer-events: none;
        }

        .mvc-roster-filter-pills {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .mvc-filter-btn {
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            color: #475569;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mvc-filter-btn:hover {
            border-color: var(--mvc-navy);
            color: var(--mvc-navy);
        }

        .mvc-filter-btn.is-active {
            background: var(--mvc-navy);
            color: #FFFFFF;
            border-color: var(--mvc-navy);
        }

        .mvc-view-switcher {
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
            font-size: 12px;
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

        /* 5. ROSTER CARDS GRID */
        .mvc-roster-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 36px;
        }

        .mvc-officer-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .mvc-officer-card:hover {
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 33, 71, 0.08);
        }

        .mvc-officer-card-top {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 14px;
        }

        .mvc-officer-avatar {
            width: 58px;
            height: 58px;
            min-width: 58px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--mvc-border);
            background: #F1F5F9;
        }

        .mvc-officer-avatar-icon {
            width: 58px;
            height: 58px;
            min-width: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
            border: 1.5px solid #BFDBFE;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .mvc-officer-badge {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--mvc-maroon);
            background: rgba(128, 0, 0, 0.06);
            padding: 2px 8px;
            border-radius: 4px;
            margin-bottom: 4px;
        }

        .mvc-officer-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 2px;
            line-height: 1.3;
        }

        .mvc-officer-role {
            font-size: 12.5px;
            font-weight: 600;
            color: #64748B;
            margin: 0;
            line-height: 1.35;
        }

        .mvc-officer-scope {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            margin: 0 0 16px;
            border-top: 1px solid #F1F5F9;
            padding-top: 10px;
        }

        .mvc-officer-meta-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            color: #64748B;
            border-top: 1px dashed var(--mvc-border);
            padding-top: 10px;
            margin-top: auto;
        }

        /* 6. TABULAR ROSTER VIEW */
        .mvc-roster-table-wrap {
            display: none;
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            margin-bottom: 36px;
        }

        .mvc-roster-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .mvc-roster-table th {
            background: #002147;
            color: #FFFFFF;
            text-align: left;
            padding: 12px 16px;
            font-weight: 700;
            font-size: 12.5px;
            letter-spacing: 0.4px;
        }

        .mvc-roster-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #F1F5F9;
            color: #334155;
            vertical-align: middle;
        }

        .mvc-roster-table tr:hover td {
            background: #F8FAFC;
        }

        /* 7. MINISTERIAL STAFF SUMMARY BANNER */
        .mvc-staff-banner {
            background: linear-gradient(135deg, #FAF9F6 0%, #FFFFFF 100%);
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 22px 28px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .mvc-staff-banner-text h3 {
            font-size: 17px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 4px;
        }

        .mvc-staff-banner-text p {
            margin: 0;
            font-size: 13.5px;
            color: #64748B;
        }

        /* 8. GOVERNANCE GATEWAY STRIP */
        .mvc-gateway-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .mvc-gw-card {
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

        .mvc-gw-card:hover {
            border-color: var(--mvc-navy);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 33, 71, 0.08);
        }

        .mvc-gw-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin-bottom: 4px;
        }

        .mvc-gw-desc {
            font-size: 12px;
            color: #64748B;
            margin: 0 0 10px;
            line-height: 1.4;
        }

        .mvc-gw-link {
            font-size: 12px;
            font-weight: 700;
            color: var(--mvc-maroon);
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mvc-roster-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-admin-kpi-ribbon {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-gateway-strip {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-principal-card {
                grid-template-columns: 1fr;
                text-align: center;
                justify-items: center;
            }
            .mvc-principal-actions {
                justify-content: center;
            }
            .mvc-principal-meta-row {
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .mvc-roster-grid {
                grid-template-columns: 1fr;
            }
            .mvc-admin-kpi-ribbon {
                grid-template-columns: 1fr;
            }
            .mvc-gateway-strip {
                grid-template-columns: 1fr;
            }
            .mvc-roster-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .mvc-view-switcher {
                align-self: flex-start;
            }
        }
    </style>

    <div class="mvc-admin-wrapper">
        <!-- 1. HERO HEADER (Minimal, Sleek) -->
        <header class="mvc-admin-hero">
            <div class="mvc-admin-hero-inner">
                <nav class="mvc-admin-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/our-administration/'); ?>">Executive Leadership</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">College Administration</span>
                </nav>

                <div class="mvc-admin-badge-pill">
                    🏛️ Maharshi Vishwamitra Mahavidyalaya, Buxar
                </div>

                <h1>College Administration &amp; Governance Roster</h1>

                <div class="mvc-admin-kpi-ribbon">
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">Head of Institution</div>
                        <div class="mvc-admin-kpi-val">Prof. (Dr.) Krishna Kant Singh</div>
                    </div>
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">Statutory Framework</div>
                        <div class="mvc-admin-kpi-val">UGC 2(f) &amp; 12(B) Permanent</div>
                    </div>
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">University Accord</div>
                        <div class="mvc-admin-kpi-val">Constituent Unit, VKSU Ara</div>
                    </div>
                    <div class="mvc-admin-kpi-item">
                        <div class="mvc-admin-kpi-title">Administrative Portfolios</div>
                        <div class="mvc-admin-kpi-val">12 Officers &bull; 15 Committees</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-admin-container" id="admin-main">

            <!-- PRINCIPAL SPOTLIGHT CARD -->
            <section class="mvc-principal-card">
                <div class="mvc-principal-photo-box">
                    <img src="<?php echo esc_url($principal_img); ?>" alt="Prof. (Dr.) Krishna Kant Singh" class="mvc-principal-photo">
                    <span class="mvc-principal-status-dot" title="Active Institutional Principal"></span>
                </div>
                <div class="mvc-principal-details">
                    <div class="mvc-principal-badge">⭐ Institutional Head &bull; Principal</div>
                    <h2 class="mvc-principal-name">Prof. (Dr.) Krishna Kant Singh</h2>
                    <div class="mvc-principal-role">
                        Principal, M.V. College, Buxar &bull; Chairperson, Academic Council &amp; IQAC
                    </div>
                    <div class="mvc-principal-meta-row">
                        <span class="mvc-principal-meta-item">
                            🏛️ <strong>Secretariat:</strong> Administrative Block, Charitravan, Buxar – 802101
                        </span>
                        <span class="mvc-principal-meta-item">
                            📞 <strong>Direct:</strong> 06183-295055
                        </span>
                        <span class="mvc-principal-meta-item">
                            ✉️ <strong>Email:</strong> principalmvcbuxar@gmail.com
                        </span>
                    </div>
                    <div class="mvc-principal-actions">
                        <a href="<?php echo esc_url($site_url . '/principal-desk/'); ?>" class="mvc-btn mvc-btn-primary">
                            Principal's Desk &rarr;
                        </a>
                        <a href="<?php echo esc_url($site_url . '/succession-list/'); ?>" class="mvc-btn mvc-btn-secondary">
                            Succession List &rarr;
                        </a>
                        <a href="<?php echo esc_url($site_url . '/university-administration/'); ?>" class="mvc-btn mvc-btn-secondary">
                            VKSU Administration &rarr;
                        </a>
                    </div>
                </div>
            </section>

            <!-- ROSTER TOOLBAR & CONTROLS -->
            <section class="mvc-roster-toolbar">
                <div class="mvc-roster-search-box">
                    <svg class="mvc-roster-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="mvcAdminSearch" placeholder="Filter by officer name, role, or wing..." onkeyup="mvcFilterAdminRoster();">
                </div>

                <div class="mvc-roster-filter-pills">
                    <button type="button" class="mvc-filter-btn is-active" onclick="mvcSetAdminCategory('all', this);">All Officers (12)</button>
                    <button type="button" class="mvc-filter-btn" onclick="mvcSetAdminCategory('discipline', this);">Discipline &amp; Vigilance</button>
                    <button type="button" class="mvc-filter-btn" onclick="mvcSetAdminCategory('youth', this);">Youth &amp; Sports</button>
                    <button type="button" class="mvc-filter-btn" onclick="mvcSetAdminCategory('welfare', this);">Welfare &amp; Career</button>
                    <button type="button" class="mvc-filter-btn" onclick="mvcSetAdminCategory('services', this);">Treasury &amp; Services</button>
                </div>

                <div class="mvc-view-switcher">
                    <button type="button" class="mvc-view-btn is-active" id="mvcBtnCards" onclick="mvcSwitchAdminView('grid');">
                        📇 Cards
                    </button>
                    <button type="button" class="mvc-view-btn" id="mvcBtnTable" onclick="mvcSwitchAdminView('table');">
                        📋 Table
                    </button>
                </div>
            </section>

            <!-- 5. ROSTER CARDS GRID VIEW -->
            <section class="mvc-roster-grid" id="mvcRosterGrid">
                <?php foreach ($roster as $item): ?>
                    <article class="mvc-officer-card" data-category="<?php echo esc_attr($item['category']); ?>" data-keywords="<?php echo esc_attr(strtolower($item['name'] . ' ' . $item['role'] . ' ' . $item['dept'] . ' ' . $item['scope'])); ?>">
                        <div>
                            <div class="mvc-officer-card-top">
                                <?php if (!empty($item['photo'])): ?>
                                    <img src="<?php echo esc_url($item['photo']); ?>" alt="<?php echo esc_attr($item['name']); ?>" class="mvc-officer-avatar" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($item['name']); ?>&background=002147&color=ffffff';">
                                <?php else: ?>
                                    <div class="mvc-officer-avatar-icon">
                                        <?php echo esc_html($item['icon'] ?? '🏛️'); ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <span class="mvc-officer-badge"><?php echo esc_html($item['badge']); ?></span>
                                    <h3 class="mvc-officer-name"><?php echo esc_html($item['name']); ?></h3>
                                    <div class="mvc-officer-role"><?php echo esc_html($item['role']); ?></div>
                                </div>
                            </div>
                            <p class="mvc-officer-scope"><?php echo esc_html($item['scope']); ?></p>
                        </div>
                        <div class="mvc-officer-meta-bar">
                            <span>📍 <?php echo esc_html($item['office']); ?></span>
                            <?php if (!empty($item['phone'])): ?>
                                <span>📞 <?php echo esc_html($item['phone']); ?></span>
                            <?php elseif (!empty($item['email'])): ?>
                                <span>✉️ <?php echo esc_html($item['email']); ?></span>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <!-- 6. ROSTER TABULAR VIEW -->
            <section class="mvc-roster-table-wrap" id="mvcRosterTable">
                <table class="mvc-roster-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 28%;">Statutory Portfolio / Role</th>
                            <th style="width: 24%;">Designated Officer</th>
                            <th style="width: 24%;">Scope of Responsibility</th>
                            <th>Secretariat / Chamber</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1; foreach ($roster as $item): ?>
                            <tr class="mvc-table-row" data-category="<?php echo esc_attr($item['category']); ?>" data-keywords="<?php echo esc_attr(strtolower($item['name'] . ' ' . $item['role'] . ' ' . $item['dept'] . ' ' . $item['scope'])); ?>">
                                <td><strong><?php echo $sno++; ?></strong></td>
                                <td>
                                    <div style="font-weight: 800; color: var(--mvc-navy);"><?php echo esc_html($item['role']); ?></div>
                                    <span class="mvc-officer-badge" style="margin-top: 3px;"><?php echo esc_html($item['badge']); ?></span>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--mvc-navy);"><?php echo esc_html($item['name']); ?></div>
                                    <div style="font-size: 12px; color: #64748B;"><?php echo esc_html($item['desig']); ?></div>
                                </td>
                                <td style="font-size: 12.5px; line-height: 1.4; color: #475569;">
                                    <?php echo esc_html($item['scope']); ?>
                                </td>
                                <td style="font-size: 12.5px; color: #64748B;">
                                    <div>📍 <?php echo esc_html($item['office']); ?></div>
                                    <?php if (!empty($item['phone'])): ?>
                                        <div style="margin-top: 2px;">📞 <?php echo esc_html($item['phone']); ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- 7. MINISTERIAL STAFF ROSTER LINK BANNER -->
            <section class="mvc-staff-banner">
                <div class="mvc-staff-banner-text">
                    <h3>👥 Ministerial Staff &amp; Administrative Wing Directory</h3>
                    <p>24 sanctioned non-teaching staff members oversee daily college logistics, academic records, laboratories, and student support.</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url($site_url . '/staff-directory/'); ?>" class="mvc-btn mvc-btn-primary">
                        View Staff Directory &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/committees-cells/'); ?>" class="mvc-btn mvc-btn-secondary">
                        View All 15 Committees &rarr;
                    </a>
                </div>
            </section>

            <!-- 8. GOVERNANCE GATEWAY STRIP -->
            <section class="mvc-gateway-strip">
                <a href="<?php echo esc_url($site_url . '/principal-desk/'); ?>" class="mvc-gw-card">
                    <div>
                        <div class="mvc-gw-title">Principal's Desk</div>
                        <p class="mvc-gw-desc">Institutional mission, leadership welcome address, and collegiate messages.</p>
                    </div>
                    <span class="mvc-gw-link">Visit Desk &rarr;</span>
                </a>
                <a href="<?php echo esc_url($site_url . '/succession-list/'); ?>" class="mvc-gw-card">
                    <div>
                        <div class="mvc-gw-title">Succession List</div>
                        <p class="mvc-gw-desc">Official chronological register of institutional Principals since 1958.</p>
                    </div>
                    <span class="mvc-gw-link">View Succession &rarr;</span>
                </a>
                <a href="<?php echo esc_url($site_url . '/university-administration/'); ?>" class="mvc-gw-card">
                    <div>
                        <div class="mvc-gw-title">University Administration</div>
                        <p class="mvc-gw-desc">VKSU Vice-Chancellor, Registrar, Controller of Exams, and Deans.</p>
                    </div>
                    <span class="mvc-gw-link">VKSU Officers &rarr;</span>
                </a>
                <a href="<?php echo esc_url($site_url . '/committees-cells/'); ?>" class="mvc-gw-card">
                    <div>
                        <div class="mvc-gw-title">Statutory Committees</div>
                        <p class="mvc-gw-desc">15 constituent collegiate committees including IQAC, Anti-Ragging, and BOS.</p>
                    </div>
                    <span class="mvc-gw-link">All 15 Committees &rarr;</span>
                </a>
            </section>

        </main>
    </div>

    <!-- JavaScript Interactive Filter & View Switcher -->
    <script>
    var mvcCurrentAdminCat = 'all';

    function mvcFilterAdminRoster() {
        var input = document.getElementById('mvcAdminSearch');
        var query = input ? input.value.toLowerCase().trim() : '';
        
        var cards = document.querySelectorAll('#mvcRosterGrid .mvc-officer-card');
        cards.forEach(function(card) {
            var cat = card.getAttribute('data-category') || '';
            var keywords = card.getAttribute('data-keywords') || '';
            var matchesCat = (mvcCurrentAdminCat === 'all' || cat === mvcCurrentAdminCat);
            var matchesQuery = (!query || keywords.indexOf(query) !== -1);
            card.style.display = (matchesCat && matchesQuery) ? 'flex' : 'none';
        });

        var rows = document.querySelectorAll('#mvcRosterTable .mvc-table-row');
        rows.forEach(function(row) {
            var cat = row.getAttribute('data-category') || '';
            var keywords = row.getAttribute('data-keywords') || '';
            var matchesCat = (mvcCurrentAdminCat === 'all' || cat === mvcCurrentAdminCat);
            var matchesQuery = (!query || keywords.indexOf(query) !== -1);
            row.style.display = (matchesCat && matchesQuery) ? '' : 'none';
        });
    }

    function mvcSetAdminCategory(cat, btn) {
        mvcCurrentAdminCat = cat;
        var buttons = document.querySelectorAll('.mvc-roster-filter-pills .mvc-filter-btn');
        buttons.forEach(function(b) { b.classList.remove('is-active'); });
        if (btn) btn.classList.add('is-active');
        mvcFilterAdminRoster();
    }

    function mvcSwitchAdminView(view) {
        var grid = document.getElementById('mvcRosterGrid');
        var table = document.getElementById('mvcRosterTable');
        var btnCards = document.getElementById('mvcBtnCards');
        var btnTable = document.getElementById('mvcBtnTable');

        if (view === 'table') {
            if (grid) grid.style.display = 'none';
            if (table) table.style.display = 'block';
            if (btnCards) btnCards.classList.remove('is-active');
            if (btnTable) btnTable.classList.add('is-active');
        } else {
            if (grid) grid.style.display = 'grid';
            if (table) table.style.display = 'none';
            if (btnCards) btnCards.classList.add('is-active');
            if (btnTable) btnTable.classList.remove('is-active');
        }
    }
    </script>
    <?php
    return ob_get_clean();
}
