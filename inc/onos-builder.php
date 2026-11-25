<?php
/**
 * ONE NATION ONE SUBSCRIPTION (ONOS) SUITE BUILDER
 * 
 * Flagship National Higher Education Digital Knowledge Network Gateway
 * Ministry of Education (Govt. of India) & INFLIBNET Centre
 * Maharshi Vishwamitra Mahavidyalaya, Buxar (Veer Kunwar Singh University)
 * 
 * Designated Institutional Nodal Officer: Dr. Ravi Thakur, Assistant Professor, Department of Chemistry
 * 
 * @package Astra Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main ONOS Portal Page Renderer
 */
if (!function_exists('mvc_render_onos_page')) {
function mvc_render_onos_page() {
    $current_user = wp_get_current_user();
    $is_logged_in = is_user_logged_in();

    // Official Publisher & Consortia Directory under ONOS
    $publishers = array(
        array(
            'id'          => 'sciencedirect',
            'name'        => 'Elsevier (ScienceDirect)',
            'category'    => 'science',
            'journals'    => '2,500+ Peer-Reviewed Journals',
            'flagship'    => 'Cell, The Lancet, Tetrahedron, Journal of Chemical Sciences',
            'disciplines' => 'Chemistry, Physics, Materials Science, Biology, Medicine & Social Sciences',
            'url'         => 'https://www.sciencedirect.com/',
            'icon'        => '🧪',
            'badge'       => 'Phase-1 Core',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'springer',
            'name'        => 'Springer Nature',
            'category'    => 'science',
            'journals'    => '3,000+ Leading Academic Journals',
            'flagship'    => 'Nature Portfolio, Palgrave Macmillan, BioMed Central (BMC)',
            'disciplines' => 'Physical Sciences, Mathematics, Life Sciences, Economics, Humanities',
            'url'         => 'https://link.springer.com/',
            'icon'        => '🌿',
            'badge'       => 'Phase-1 Core',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'wiley',
            'name'        => 'Wiley Online Library',
            'category'    => 'multidisciplinary',
            'journals'    => '1,600+ Global Research Journals',
            'flagship'    => 'Angewandte Chemie, Advanced Materials, International Studies',
            'disciplines' => 'Applied Chemistry, Environmental Science, Psychology, Humanities',
            'url'         => 'https://onlinelibrary.wiley.com/',
            'icon'        => '🔬',
            'badge'       => 'Phase-1 Core',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'taylorfrancis',
            'name'        => 'Taylor & Francis Online',
            'category'    => 'humanities',
            'journals'    => '2,700+ Multidisciplinary Journals',
            'flagship'    => 'Routledge Research, Critical Inquiry, Philosophical Magazine',
            'disciplines' => 'Literature, Political Science, History, Sociology, Pure Sciences',
            'url'         => 'https://www.tandfonline.com/',
            'icon'        => '📜',
            'badge'       => 'Phase-1 Core',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'ieee',
            'name'        => 'IEEE Xplore Digital Library',
            'category'    => 'tech',
            'journals'    => '200+ Transactions & 4M+ Documents',
            'flagship'    => 'IEEE Transactions on Computers, Signal Processing, AI Systems',
            'disciplines' => 'Computer Science, Electronics, Information Tech, Applied Physics',
            'url'         => 'https://ieeexplore.ieee.org/',
            'icon'        => '⚡',
            'badge'       => 'Tech Benchmark',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'acs',
            'name'        => 'American Chemical Society (ACS)',
            'category'    => 'science',
            'journals'    => '80+ World-Leading Chemistry Journals',
            'flagship'    => 'JACS (Journal of American Chemical Society), Chemical Reviews, ACS Nano',
            'disciplines' => 'Inorganic, Organic, Physical, Analytical & Biological Chemistry',
            'url'         => 'https://pubs.acs.org/',
            'icon'        => '⚗️',
            'badge'       => 'Chemistry Prime',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'rsc',
            'name'        => 'Royal Society of Chemistry (RSC)',
            'category'    => 'science',
            'journals'    => '50+ High-Impact Chemical Science Journals',
            'flagship'    => 'Chemical Science, ChemComm, Green Chemistry, Analyst',
            'disciplines' => 'Chemical Sciences, Nanotechnology, Analytical Methods',
            'url'         => 'https://pubs.rsc.org/',
            'icon'        => '🧪',
            'badge'       => 'RSC Gold',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'oup',
            'name'        => 'Oxford University Press (OUP)',
            'category'    => 'humanities',
            'journals'    => '500+ Prestigious University Journals',
            'flagship'    => 'Mind, The English Historical Review, Past & Present, BioScience',
            'disciplines' => 'History, Philosophy, English Literature, Law, Life Sciences',
            'url'         => 'https://academic.oup.com/',
            'icon'        => '🏛️',
            'badge'       => 'Academic Gold',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'cup',
            'name'        => 'Cambridge University Press (CUP)',
            'category'    => 'humanities',
            'journals'    => '400+ Peer-Reviewed Global Journals',
            'flagship'    => 'The Historical Journal, Cambridge Law Journal, Journal of Fluid Mechanics',
            'disciplines' => 'Linguistics, Economics, Social Sciences, Mathematics, Philosophy',
            'url'         => 'https://www.cambridge.org/core',
            'icon'        => '📚',
            'badge'       => 'CUP Heritage',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'iop',
            'name'        => 'Institute of Physics (IOP)',
            'category'    => 'science',
            'journals'    => '100+ Scientific Physics Journals',
            'flagship'    => 'Journal of Physics, Reports on Progress in Physics, Applied Physics Express',
            'disciplines' => 'Condensed Matter, Quantum Mechanics, Astrophysics, Optics',
            'url'         => 'https://iopscience.iop.org/',
            'icon'        => '🔭',
            'badge'       => 'Physics Core',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'sage',
            'name'        => 'SAGE Publications',
            'category'    => 'humanities',
            'journals'    => '1,000+ Humanities & Social Science Journals',
            'flagship'    => 'American Sociological Review, Indian Economic & Social History Review',
            'disciplines' => 'Sociology, Political Science, Education, Public Administration',
            'url'         => 'https://journals.sagepub.com/',
            'icon'        => '📖',
            'badge'       => 'Social Sciences',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'aps',
            'name'        => 'American Physical Society (APS)',
            'category'    => 'science',
            'journals'    => '15+ Premier Physical Science Journals',
            'flagship'    => 'Physical Review Letters (PRL), Physical Review A-E, Reviews of Modern Physics',
            'disciplines' => 'Nuclear Physics, Solid State, Quantum Information, High Energy Physics',
            'url'         => 'https://journals.aps.org/',
            'icon'        => '⚛️',
            'badge'       => 'APS Benchmark',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'acm',
            'name'        => 'ACM Digital Library',
            'category'    => 'tech',
            'journals'    => '70+ Computing Transactions & Magazines',
            'flagship'    => 'Communications of the ACM, ACM Transactions on Graphics, Computing Surveys',
            'disciplines' => 'Artificial Intelligence, Software Engineering, Algorithms, Cyber Security',
            'url'         => 'https://dl.acm.org/',
            'icon'        => '💻',
            'badge'       => 'Computing Prime',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'nlist',
            'name'        => 'INFLIBNET N-LIST Repository',
            'category'    => 'consortia',
            'journals'    => '6,000+ e-Journals & 1,99,500+ e-Books',
            'flagship'    => 'JSTOR, Project MUSE, Oxford Scholarship Online, Springer e-Books',
            'disciplines' => 'Undergraduate & Postgraduate Curricula, Cross-Disciplinary Resources',
            'url'         => 'https://nlist.inflibnet.ac.in/',
            'icon'        => '🇮🇳',
            'badge'       => 'College Consortia',
            'access'      => 'Library Membership ID'
        ),
        array(
            'id'          => 'shodhganga',
            'name'        => 'Shodhganga (INFLIBNET)',
            'category'    => 'consortia',
            'journals'    => '5,00,000+ Indian Doctoral Theses',
            'flagship'    => 'Central National Electronic Theses & Dissertations (ETD) Repository',
            'disciplines' => 'All UGC Recognized Universities & Constituent College Dissertations',
            'url'         => 'https://shodhganga.inflibnet.ac.in/',
            'icon'        => '🎓',
            'badge'       => 'National ETD',
            'access'      => 'Open National Access'
        ),
        array(
            'id'          => 'onos_portal',
            'name'        => 'National ONOS Central Gateway',
            'category'    => 'consortia',
            'journals'    => 'Unified National Search Engine',
            'flagship'    => 'Department of Higher Education, Ministry of Education, Govt. of India',
            'disciplines' => 'One-Stop Search Across All 30+ Participating Global Publishers',
            'url'         => 'https://onos.gov.in/',
            'icon'        => '🌐',
            'badge'       => 'Official MoE Gateway',
            'access'      => 'National Single Sign-On'
        ),
    );
    ?>

    <div class="mvc-onos-suite" id="mvcOnosSuite">
        <style>
            /* ========================================================
               ONE NATION ONE SUBSCRIPTION (ONOS) DESIGN SYSTEM
               Institutional Sovereign Navy, Gold & Tiranga Palette
               ======================================================== */
            :root {
                --mvc-onos-navy: #002147;
                --mvc-onos-navy-dark: #001229;
                --mvc-onos-gold: #D4AF37;
                --mvc-onos-gold-dark: #B8860B;
                --mvc-onos-gold-light: #FEF08A;
                --mvc-onos-saffron: #FF9933;
                --mvc-onos-saffron-dark: #C2410C;
                --mvc-onos-green: #138808;
                --mvc-onos-maroon: #800000;
                --mvc-onos-slate-50: #F8FAFC;
                --mvc-onos-slate-100: #F1F5F9;
                --mvc-onos-slate-200: #E2E8F0;
                --mvc-onos-slate-300: #CBD5E1;
                --mvc-onos-slate-600: #475569;
                --mvc-onos-slate-700: #334155;
                --mvc-onos-slate-800: #1E293B;
                --mvc-onos-slate-900: #0F172A;
            }

            .mvc-onos-suite {
                font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Outfit", "Segoe UI", Roboto, sans-serif;
                background-color: #F8FAFC;
                color: var(--mvc-onos-slate-800);
                line-height: 1.6;
                padding-bottom: 70px;
                position: relative;
            }

            /* Breadcrumb */
            .mvc-onos-breadcrumb {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: #94A3B8;
                margin-bottom: 18px;
                flex-wrap: wrap;
            }

            .mvc-onos-breadcrumb a {
                color: #CBD5E1;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .mvc-onos-breadcrumb a:hover {
                color: var(--mvc-onos-gold);
            }

            .mvc-onos-breadcrumb .sep {
                color: #64748B;
            }

            .mvc-onos-breadcrumb .current {
                color: var(--mvc-onos-gold-light);
                font-weight: 600;
            }

            /* Hero Section */
            .mvc-onos-hero {
                background: linear-gradient(135deg, #001229 0%, #002147 50%, #001533 100%);
                color: #FFFFFF;
                padding: 44px 20px 48px;
                position: relative;
                border-bottom: 4px solid var(--mvc-onos-gold);
                box-shadow: 0 12px 36px rgba(0, 33, 71, 0.25);
            }

            .mvc-onos-hero::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: 0;
                width: 380px;
                height: 100%;
                background: radial-gradient(circle at 90% 50%, rgba(212, 175, 55, 0.12) 0%, transparent 70%);
                pointer-events: none;
            }

            .mvc-onos-hero-inner {
                max-width: 1240px;
                margin: 0 auto;
                position: relative;
                z-index: 2;
            }

            /* Ministry & Sovereign Emblem Bar */
            .mvc-onos-emblem-strip {
                display: flex;
                align-items: center;
                gap: 20px;
                margin-bottom: 22px;
                padding-bottom: 18px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
                flex-wrap: wrap;
            }

            .mvc-onos-emblem-badge {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(212, 175, 55, 0.35);
                padding: 6px 14px;
                border-radius: 30px;
                font-size: 12px;
                font-weight: 700;
                color: #FEF08A;
                letter-spacing: 0.3px;
                text-transform: uppercase;
            }

            .mvc-onos-emblem-icon {
                font-size: 16px;
            }

            .mvc-onos-hero-pills {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                margin-bottom: 18px;
            }

            .mvc-onos-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.18);
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                color: #E2E8F0;
                letter-spacing: 0.2px;
            }

            .mvc-onos-pill.gold {
                background: rgba(212, 175, 55, 0.2);
                border-color: rgba(212, 175, 55, 0.5);
                color: var(--mvc-onos-gold-light);
                font-weight: 700;
            }

            .mvc-onos-pill.saffron {
                background: rgba(255, 153, 51, 0.18);
                border-color: rgba(255, 153, 51, 0.45);
                color: #FED7AA;
                font-weight: 700;
            }

            .mvc-onos-pill.green {
                background: rgba(19, 136, 8, 0.2);
                border-color: rgba(19, 136, 8, 0.45);
                color: #BBF7D0;
                font-weight: 700;
            }

            .mvc-onos-hindi-kicker {
                font-size: 15px;
                font-weight: 700;
                color: var(--mvc-onos-gold);
                margin-bottom: 8px;
                letter-spacing: 0.4px;
            }

            .mvc-onos-hero-title {
                font-size: 38px;
                font-weight: 900;
                line-height: 1.15;
                margin: 0 0 14px;
                color: #FFFFFF;
                letter-spacing: -0.5px;
            }

            .mvc-onos-hero-desc {
                font-size: 15.5px;
                color: #CBD5E1;
                max-width: 940px;
                line-height: 1.65;
                margin: 0 0 24px;
            }

            .mvc-onos-hero-actions {
                display: flex;
                align-items: center;
                gap: 14px;
                flex-wrap: wrap;
            }

            .mvc-onos-btn-primary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: linear-gradient(135deg, var(--mvc-onos-gold) 0%, #B8860B 100%);
                color: #002147 !important;
                font-weight: 800;
                font-size: 14px;
                padding: 12px 24px;
                border-radius: 8px;
                text-decoration: none !important;
                border: 1px solid #FFE082;
                box-shadow: 0 4px 14px rgba(212, 175, 55, 0.35);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }

            .mvc-onos-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(212, 175, 55, 0.5);
                background: linear-gradient(135deg, #FFE082 0%, var(--mvc-onos-gold) 100%);
            }

            .mvc-onos-btn-secondary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(255, 255, 255, 0.12);
                color: #FFFFFF !important;
                font-weight: 700;
                font-size: 14px;
                padding: 12px 22px;
                border-radius: 8px;
                text-decoration: none !important;
                border: 1px solid rgba(255, 255, 255, 0.25);
                transition: background 0.2s ease, transform 0.2s ease;
                cursor: pointer;
            }

            .mvc-onos-btn-secondary:hover {
                background: rgba(255, 255, 255, 0.22);
                transform: translateY(-2px);
            }

            /* Container */
            .mvc-onos-container {
                max-width: 1240px;
                margin: 0 auto;
                padding: 0 20px;
                box-sizing: border-box;
            }

            /* KPI Impact Counters */
            .mvc-onos-kpi-bar {
                margin: -24px auto 35px;
                position: relative;
                z-index: 5;
            }

            .mvc-onos-kpi-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
            }

            .mvc-onos-kpi-card {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 12px;
                padding: 20px;
                display: flex;
                align-items: center;
                gap: 16px;
                box-shadow: 0 6px 18px rgba(0, 33, 71, 0.06);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .mvc-onos-kpi-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 28px rgba(0, 33, 71, 0.1);
            }

            .mvc-onos-kpi-icon {
                width: 48px;
                height: 48px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                flex-shrink: 0;
            }

            .mvc-onos-kpi-blue   { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
            .mvc-onos-kpi-gold   { background: #FEF9C3; color: #854D0E; border: 1px solid #FDE047; }
            .mvc-onos-kpi-purple { background: #F3E8FF; color: #7E22CE; border: 1px solid #E9D5FF; }
            .mvc-onos-kpi-green  { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

            .mvc-onos-kpi-val {
                font-size: 24px;
                font-weight: 900;
                color: var(--mvc-onos-navy);
                line-height: 1.1;
                margin-bottom: 3px;
            }

            .mvc-onos-kpi-lbl {
                font-size: 12px;
                font-weight: 700;
                color: var(--mvc-onos-slate-600);
                text-transform: uppercase;
                letter-spacing: 0.4px;
            }

            /* Designated Nodal Officer Spotlight Card */
            .mvc-onos-nodal-section {
                background: linear-gradient(135deg, #001A3A 0%, #002B5C 100%);
                border: 2px solid var(--mvc-onos-gold);
                border-radius: 16px;
                padding: 30px 34px;
                color: #FFFFFF;
                box-shadow: 0 12px 32px rgba(0, 33, 71, 0.16);
                margin-bottom: 40px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 28px;
                flex-wrap: wrap;
                position: relative;
                overflow: hidden;
            }

            .mvc-onos-nodal-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 6px;
                height: 100%;
                background: linear-gradient(to bottom, #FF9933, #FFFFFF, #138808);
            }

            .mvc-onos-nodal-left {
                display: flex;
                align-items: center;
                gap: 22px;
                flex: 1;
                min-width: 320px;
            }

            .mvc-onos-nodal-avatar-wrap {
                position: relative;
                flex-shrink: 0;
            }

            .mvc-onos-nodal-avatar {
                width: 82px;
                height: 82px;
                border-radius: 50%;
                background: linear-gradient(135deg, rgba(212, 175, 55, 0.3) 0%, rgba(212, 175, 55, 0.1) 100%);
                border: 3px solid var(--mvc-onos-gold);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 38px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
            }

            .mvc-onos-nodal-seal {
                position: absolute;
                bottom: -4px;
                right: -4px;
                background: #138808;
                border: 2px solid #FFFFFF;
                color: #FFFFFF;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                font-size: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
            }

            .mvc-onos-nodal-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(212, 175, 55, 0.22);
                border: 1px solid rgba(212, 175, 55, 0.45);
                color: #FEF08A;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 3px 10px;
                border-radius: 20px;
                margin-bottom: 6px;
            }

            .mvc-onos-nodal-name {
                font-size: 24px;
                font-weight: 900;
                color: #FFFFFF;
                margin: 0 0 3px;
                letter-spacing: -0.3px;
            }

            .mvc-onos-nodal-title {
                font-size: 14.5px;
                color: #E2E8F0;
                font-weight: 600;
            }

            .mvc-onos-nodal-dept {
                font-size: 13px;
                color: #CBD5E1;
                margin-top: 2px;
            }

            .mvc-onos-nodal-right {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 12px;
                min-width: 260px;
            }

            .mvc-onos-nodal-mandate {
                font-size: 12.5px;
                color: #CBD5E1;
                text-align: right;
                line-height: 1.45;
                max-width: 340px;
            }

            .mvc-onos-nodal-btns {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                justify-content: flex-end;
            }

            .mvc-onos-btn-nodal-email {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                background: var(--mvc-onos-gold);
                color: #002147 !important;
                font-weight: 800;
                font-size: 13px;
                padding: 10px 18px;
                border-radius: 8px;
                text-decoration: none !important;
                border: 1px solid #FFE082;
                transition: transform 0.2s, background 0.2s;
            }

            .mvc-onos-btn-nodal-email:hover {
                background: #FFE082;
                transform: translateY(-2px);
            }

            .mvc-onos-btn-nodal-req {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                background: rgba(255, 255, 255, 0.12);
                color: #FFFFFF !important;
                font-weight: 700;
                font-size: 13px;
                padding: 10px 18px;
                border-radius: 8px;
                text-decoration: none !important;
                border: 1px solid rgba(255, 255, 255, 0.25);
                transition: background 0.2s, transform 0.2s;
                cursor: pointer;
            }

            .mvc-onos-btn-nodal-req:hover {
                background: rgba(255, 255, 255, 0.22);
                transform: translateY(-2px);
            }

            /* Section Headings */
            .mvc-onos-section-head {
                text-align: center;
                margin: 0 auto 32px;
                max-width: 820px;
            }

            .mvc-onos-section-kicker {
                font-size: 12.5px;
                font-weight: 800;
                color: #B8860B;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                margin-bottom: 6px;
                display: block;
            }

            .mvc-onos-section-title {
                font-size: 28px;
                font-weight: 900;
                color: var(--mvc-onos-navy);
                margin: 0 0 10px;
                letter-spacing: -0.4px;
            }

            .mvc-onos-section-desc {
                font-size: 15px;
                color: var(--mvc-onos-slate-600);
                line-height: 1.6;
                margin: 0;
            }

            /* Interactive Filter Panel for Publishers */
            .mvc-onos-filter-card {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 14px;
                padding: 20px 24px;
                margin-bottom: 28px;
                box-shadow: 0 4px 12px rgba(0, 33, 71, 0.04);
            }

            .mvc-onos-search-row {
                display: flex;
                gap: 16px;
                margin-bottom: 16px;
                flex-wrap: wrap;
            }

            .mvc-onos-search-box {
                flex: 1;
                min-width: 280px;
                position: relative;
                display: flex;
                align-items: center;
            }

            .mvc-onos-search-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #64748B;
                font-size: 16px;
                pointer-events: none;
            }

            .mvc-onos-search-input {
                width: 100% !important;
                height: 46px !important;
                padding: 0 14px 0 42px !important;
                border: 1.5px solid var(--mvc-onos-slate-300) !important;
                border-radius: 8px !important;
                font-size: 14px !important;
                outline: none !important;
                color: var(--mvc-onos-slate-800) !important;
                background: #FFFFFF !important;
                box-sizing: border-box !important;
                margin: 0 !important;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .mvc-onos-search-input:focus {
                border-color: var(--mvc-onos-navy) !important;
                box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1) !important;
            }

            .mvc-onos-tabs {
                display: flex;
                gap: 8px;
                overflow-x: auto;
                padding-bottom: 4px;
                -webkit-overflow-scrolling: touch;
            }

            .mvc-onos-tab-btn {
                background: #F1F5F9;
                color: var(--mvc-onos-slate-700);
                border: 1px solid var(--mvc-onos-slate-300);
                padding: 7px 15px;
                border-radius: 20px;
                font-size: 12.5px;
                font-weight: 700;
                cursor: pointer;
                white-space: nowrap;
                transition: all 0.2s ease;
            }

            .mvc-onos-tab-btn:hover {
                background: #E2E8F0;
                color: var(--mvc-onos-navy);
            }

            .mvc-onos-tab-btn.active {
                background: var(--mvc-onos-navy);
                color: #FFFFFF;
                border-color: var(--mvc-onos-navy);
                box-shadow: 0 2px 8px rgba(0, 33, 71, 0.2);
            }

            /* Publishers Grid */
            .mvc-onos-pub-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
                gap: 22px;
                margin-bottom: 48px;
            }

            .mvc-onos-pub-card {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 14px;
                padding: 24px;
                box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .mvc-onos-pub-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 24px rgba(0, 33, 71, 0.09);
                border-color: var(--mvc-onos-gold);
            }

            .mvc-onos-pub-header {
                display: flex;
                align-items: flex-start;
                gap: 14px;
                margin-bottom: 12px;
            }

            .mvc-onos-pub-icon-wrap {
                width: 46px;
                height: 46px;
                border-radius: 10px;
                background: #F8FAFC;
                border: 1px solid var(--mvc-onos-slate-200);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                flex-shrink: 0;
            }

            .mvc-onos-pub-title-block {
                flex: 1;
            }

            .mvc-onos-pub-name {
                font-size: 17px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 3px;
                line-height: 1.3;
            }

            .mvc-onos-pub-badge {
                display: inline-block;
                background: #FEF9C3;
                color: #854D0E;
                font-size: 11px;
                font-weight: 800;
                padding: 2px 8px;
                border-radius: 4px;
                border: 1px solid #FDE047;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .mvc-onos-pub-meta-box {
                background: #F8FAFC;
                border-left: 3px solid var(--mvc-onos-navy);
                border-radius: 0 6px 6px 0;
                padding: 10px 12px;
                margin-bottom: 14px;
                font-size: 12.5px;
            }

            .mvc-onos-pub-meta-row {
                margin-bottom: 4px;
                color: var(--mvc-onos-slate-700);
            }

            .mvc-onos-pub-meta-row:last-child {
                margin-bottom: 0;
            }

            .mvc-onos-pub-meta-row strong {
                color: var(--mvc-onos-navy);
            }

            .mvc-onos-pub-disciplines {
                font-size: 12.5px;
                color: var(--mvc-onos-slate-600);
                line-height: 1.5;
                margin-bottom: 18px;
            }

            .mvc-onos-pub-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding-top: 14px;
                border-top: 1px solid var(--mvc-onos-slate-200);
                margin-top: auto;
            }

            .mvc-onos-pub-access-tag {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                font-size: 11.5px;
                font-weight: 700;
                color: #065F46;
                background: #D1FAE5;
                padding: 3px 8px;
                border-radius: 4px;
                border: 1px solid #A7F3D0;
            }

            .mvc-onos-pub-link-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: var(--mvc-onos-navy);
                color: #FFFFFF !important;
                font-size: 12.5px;
                font-weight: 700;
                padding: 7px 14px;
                border-radius: 6px;
                text-decoration: none !important;
                transition: background 0.2s, transform 0.2s;
            }

            .mvc-onos-pub-link-btn:hover {
                background: #002C5F;
                transform: translateY(-2px);
            }

            /* Empty state for search */
            .mvc-onos-empty-state {
                display: none;
                text-align: center;
                padding: 48px 24px;
                background: #FFFFFF;
                border: 2px dashed var(--mvc-onos-slate-300);
                border-radius: 14px;
                margin-bottom: 40px;
            }

            .mvc-onos-empty-icon {
                font-size: 40px;
                margin-bottom: 12px;
            }

            .mvc-onos-empty-title {
                font-size: 18px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 6px;
            }

            .mvc-onos-empty-desc {
                font-size: 14px;
                color: var(--mvc-onos-slate-600);
                margin: 0 0 16px;
            }

            /* Three Modes of Access */
            .mvc-onos-modes-section {
                margin-bottom: 50px;
            }

            .mvc-onos-modes-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }

            .mvc-onos-mode-card {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 14px;
                padding: 26px;
                box-shadow: 0 6px 18px rgba(0, 33, 71, 0.04);
                display: flex;
                flex-direction: column;
                position: relative;
                border-top: 4px solid var(--mvc-onos-navy);
            }

            .mvc-onos-mode-card.gold-top {
                border-top-color: var(--mvc-onos-gold);
            }

            .mvc-onos-mode-card.saffron-top {
                border-top-color: var(--mvc-onos-saffron);
            }

            .mvc-onos-mode-number {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--mvc-onos-navy);
                color: var(--mvc-onos-gold);
                font-size: 14px;
                font-weight: 800;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 16px;
            }

            .mvc-onos-mode-card.gold-top .mvc-onos-mode-number {
                background: var(--mvc-onos-gold);
                color: #002147;
            }

            .mvc-onos-mode-card.saffron-top .mvc-onos-mode-number {
                background: var(--mvc-onos-saffron);
                color: #FFFFFF;
            }

            .mvc-onos-mode-title {
                font-size: 18px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 10px;
            }

            .mvc-onos-mode-desc {
                font-size: 13.5px;
                color: var(--mvc-onos-slate-600);
                line-height: 1.6;
                margin-bottom: 16px;
            }

            .mvc-onos-mode-steps {
                list-style: none;
                padding: 0;
                margin: 0 0 20px;
                font-size: 13px;
                color: var(--mvc-onos-slate-700);
            }

            .mvc-onos-mode-steps li {
                position: relative;
                padding-left: 20px;
                margin-bottom: 8px;
                line-height: 1.45;
            }

            .mvc-onos-mode-steps li::before {
                content: '✓';
                position: absolute;
                left: 0;
                color: var(--mvc-onos-green);
                font-weight: 800;
            }

            .mvc-onos-mode-badge {
                margin-top: auto;
                font-size: 11.5px;
                font-weight: 700;
                color: var(--mvc-onos-navy);
                background: var(--mvc-onos-slate-100);
                padding: 6px 12px;
                border-radius: 6px;
                border: 1px solid var(--mvc-onos-slate-200);
                text-align: center;
            }

            /* Guidelines & Ethics Protocol */
            .mvc-onos-guide-banner {
                background: #FFFBEB;
                border: 1.5px solid #FDE68A;
                border-radius: 14px;
                padding: 26px 30px;
                margin-bottom: 45px;
                display: flex;
                align-items: flex-start;
                gap: 20px;
            }

            .mvc-onos-guide-icon {
                font-size: 32px;
                flex-shrink: 0;
                margin-top: 2px;
            }

            .mvc-onos-guide-body h3 {
                font-size: 17px;
                font-weight: 800;
                color: #92400E;
                margin: 0 0 8px;
            }

            .mvc-onos-guide-body p {
                font-size: 13.5px;
                color: #78350F;
                line-height: 1.65;
                margin: 0 0 12px;
            }

            .mvc-onos-guide-body ul {
                margin: 0;
                padding-left: 18px;
                font-size: 13px;
                color: #78350F;
                line-height: 1.6;
            }

            /* FAQ Accordion Section */
            .mvc-onos-faq-section {
                margin-bottom: 50px;
            }

            .mvc-onos-faq-list {
                max-width: 900px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .mvc-onos-faq-item {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 33, 71, 0.03);
            }

            .mvc-onos-faq-question {
                width: 100%;
                text-align: left;
                background: #FFFFFF;
                border: none;
                padding: 16px 20px;
                font-size: 15px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                cursor: pointer;
                transition: background 0.2s ease;
            }

            .mvc-onos-faq-question:hover {
                background: #F8FAFC;
                color: #B8860B;
            }

            .mvc-onos-faq-arrow {
                font-size: 12px;
                color: var(--mvc-onos-gold);
                transition: transform 0.2s ease;
                flex-shrink: 0;
            }

            .mvc-onos-faq-answer {
                display: none;
                padding: 0 20px 18px;
                font-size: 14px;
                color: var(--mvc-onos-slate-600);
                line-height: 1.65;
                border-top: 1px solid #F1F5F9;
                background: #FBFDFE;
            }

            /* Related Academic Gateways */
            .mvc-onos-related-bar {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 14px;
                padding: 24px 30px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
                box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            }

            .mvc-onos-related-left h4 {
                font-size: 17px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 4px;
            }

            .mvc-onos-related-left p {
                font-size: 13.5px;
                color: var(--mvc-onos-slate-600);
                margin: 0;
            }

            .mvc-onos-related-links {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
            }

            .mvc-onos-related-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: var(--mvc-onos-slate-100);
                color: var(--mvc-onos-navy) !important;
                font-size: 13px;
                font-weight: 700;
                padding: 9px 18px;
                border-radius: 8px;
                border: 1px solid var(--mvc-onos-slate-300);
                text-decoration: none !important;
                transition: all 0.2s ease;
            }

            .mvc-onos-related-btn:hover {
                background: var(--mvc-onos-navy);
                color: #FFFFFF !important;
                border-color: var(--mvc-onos-navy);
                transform: translateY(-2px);
            }

            .mvc-onos-related-btn.primary {
                background: var(--mvc-onos-navy);
                color: #FFFFFF !important;
                border-color: var(--mvc-onos-navy);
            }

            .mvc-onos-related-btn.primary:hover {
                background: #002C5F;
            }

            /* Research Request Modal */
            .mvc-onos-modal-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 18, 41, 0.75);
                backdrop-filter: blur(4px);
                z-index: 99999;
                align-items: center;
                justify-content: center;
                padding: 20px;
                box-sizing: border-box;
            }

            .mvc-onos-modal-dialog {
                background: #FFFFFF;
                border-radius: 16px;
                width: 100%;
                max-width: 620px;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
                border: 1px solid var(--mvc-onos-slate-200);
                animation: mvcOnosModalIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            }

            @keyframes mvcOnosModalIn {
                from { opacity: 0; transform: scale(0.95) translateY(10px); }
                to   { opacity: 1; transform: scale(1) translateY(0); }
            }

            .mvc-onos-modal-header {
                background: var(--mvc-onos-navy);
                color: #FFFFFF;
                padding: 20px 26px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 3px solid var(--mvc-onos-gold);
            }

            .mvc-onos-modal-header h3 {
                font-size: 18px;
                font-weight: 800;
                margin: 0 0 3px;
                color: #FFFFFF;
            }

            .mvc-onos-modal-header p {
                font-size: 12px;
                color: #CBD5E1;
                margin: 0;
            }

            .mvc-onos-modal-close {
                background: transparent;
                border: none;
                color: #FFFFFF;
                font-size: 26px;
                cursor: pointer;
                line-height: 1;
                padding: 0;
                opacity: 0.8;
                transition: opacity 0.2s;
            }

            .mvc-onos-modal-close:hover {
                opacity: 1;
                color: var(--mvc-onos-gold);
            }

            .mvc-onos-modal-body {
                padding: 24px 26px;
            }

            .mvc-onos-form-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .mvc-onos-form-full {
                grid-column: 1 / -1;
            }

            .mvc-onos-form-group label {
                display: block;
                font-size: 12.5px;
                font-weight: 700;
                color: var(--mvc-onos-slate-700);
                margin-bottom: 6px;
            }

            .mvc-onos-form-group input,
            .mvc-onos-form-group select,
            .mvc-onos-form-group textarea {
                width: 100% !important;
                padding: 9px 12px !important;
                border: 1.5px solid var(--mvc-onos-slate-300) !important;
                border-radius: 7px !important;
                font-size: 13.5px !important;
                color: var(--mvc-onos-slate-800) !important;
                box-sizing: border-box !important;
                outline: none !important;
                background: #FFFFFF !important;
            }

            .mvc-onos-form-group input:focus,
            .mvc-onos-form-group select:focus,
            .mvc-onos-form-group textarea:focus {
                border-color: var(--mvc-onos-navy) !important;
                box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1) !important;
            }

            .mvc-onos-modal-footer {
                padding: 16px 26px 22px;
                border-top: 1px solid var(--mvc-onos-slate-200);
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 12px;
            }

            .mvc-onos-alert-box {
                display: none;
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 16px;
            }

            .mvc-onos-alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }
            .mvc-onos-alert-error   { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }

            /* Responsive */
            @media (max-width: 992px) {
                .mvc-onos-kpi-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .mvc-onos-modes-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-nodal-right {
                    align-items: flex-start;
                    min-width: 100%;
                }
                .mvc-onos-nodal-mandate {
                    text-align: left;
                    max-width: 100%;
                }
                .mvc-onos-nodal-btns {
                    justify-content: flex-start;
                }
            }

            @media (max-width: 640px) {
                .mvc-onos-hero {
                    padding: 32px 16px 40px;
                }
                .mvc-onos-hero-title {
                    font-size: 28px;
                }
                .mvc-onos-kpi-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-pub-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-form-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-nodal-left {
                    flex-direction: column;
                    text-align: center;
                }
                .mvc-onos-nodal-avatar-wrap {
                    margin: 0 auto;
                }
                .mvc-onos-nodal-right {
                    text-align: center;
                }
                .mvc-onos-nodal-mandate {
                    text-align: center;
                }
                .mvc-onos-nodal-btns {
                    justify-content: center;
                    width: 100%;
                }
            }
        </style>

        <!-- 1. HERO HEADER SECTION -->
        <header class="mvc-onos-hero">
            <div class="mvc-onos-hero-inner">
                <!-- Breadcrumbs -->
                <nav class="mvc-onos-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="sep">/</span>
                    <a href="<?php echo esc_url(home_url('/library/')); ?>">Central Digital Library</a>
                    <span class="sep">/</span>
                    <a href="<?php echo esc_url(home_url('/research/')); ?>">Research Wing</a>
                    <span class="sep">/</span>
                    <span class="current">ONOS National Gateway</span>
                </nav>

                <!-- National Sovereign Emblem Bar -->
                <div class="mvc-onos-emblem-strip">
                    <div class="mvc-onos-emblem-badge">
                        <span class="mvc-onos-emblem-icon">🇮🇳</span>
                        <span>Government of India &bull; Ministry of Education</span>
                    </div>
                    <div class="mvc-onos-emblem-badge">
                        <span class="mvc-onos-emblem-icon">🌐</span>
                        <span>UGC INFLIBNET Centre National Coordination</span>
                    </div>
                    <div class="mvc-onos-emblem-badge">
                        <span class="mvc-onos-emblem-icon">🏛️</span>
                        <span>VKSU Constituent College Network</span>
                    </div>
                </div>

                <!-- Hero Badges -->
                <div class="mvc-onos-hero-pills">
                    <span class="mvc-onos-pill gold">🏛 ₹6,000 Cr Centrally Funded Scheme</span>
                    <span class="mvc-onos-pill saffron">📜 NEP-2020 High-Impact Research</span>
                    <span class="mvc-onos-pill">📚 30+ Global Tier-1 Publishers</span>
                    <span class="mvc-onos-pill green">✓ 100% Free Scholar Access</span>
                    <span class="mvc-onos-pill">📡 Campus IP &amp; INFED SSO Active</span>
                </div>

                <!-- Title Cluster -->
                <div class="mvc-onos-hindi-kicker">
                    एक राष्ट्र, एक सदस्यता (One Nation One Subscription) &bull; राष्ट्रीय उच्च शिक्षा अनुसंधान ज्ञान नेटवर्क
                </div>
                <h1 class="mvc-onos-hero-title">
                    One Nation One Subscription (ONOS)
                </h1>
                <p class="mvc-onos-hero-desc">
                    Maharshi Vishwamitra Mahavidyalaya is fully integrated into <strong>One Nation One Subscription (ONOS)</strong> — a landmark sovereign digital knowledge mission by the Ministry of Education, Government of India. Under this central national site license, all undergraduate scholars, postgraduate researchers, and faculty members enjoy seamless, zero-cost, 24&times;7 access to over 13,000+ top-tier international peer-reviewed journals, conference proceedings, and scholarly databases from the world's leading publishers without individual paywalls.
                </p>

                <!-- Action Triggers -->
                <div class="mvc-onos-hero-actions">
                    <a href="#mvcOnosCatalog" class="mvc-onos-btn-primary">
                        <span>🔍</span> Explore 16+ Global Publisher Gateways
                    </a>
                    <button type="button" class="mvc-onos-btn-secondary" onclick="mvcOpenOnosRequestModal();">
                        <span>📋</span> Request Literature / PDF Assistance
                    </button>
                    <a href="<?php echo esc_url(home_url('/library/')); ?>" class="mvc-onos-btn-secondary">
                        <span>📚</span> Central Digital Library &rarr;
                    </a>
                </div>
            </div>
        </header>

        <div class="mvc-onos-container">
            <!-- 2. KEY IMPACT COUNTERS BAR -->
            <div class="mvc-onos-kpi-bar">
                <div class="mvc-onos-kpi-grid">
                    <div class="mvc-onos-kpi-card">
                        <div class="mvc-onos-kpi-icon mvc-onos-kpi-blue">🏛️</div>
                        <div>
                            <div class="mvc-onos-kpi-val">30+</div>
                            <div class="mvc-onos-kpi-lbl">Global Tier-1 Publishers</div>
                        </div>
                    </div>

                    <div class="mvc-onos-kpi-card">
                        <div class="mvc-onos-kpi-icon mvc-onos-kpi-gold">📚</div>
                        <div>
                            <div class="mvc-onos-kpi-val">13,000+</div>
                            <div class="mvc-onos-kpi-lbl">Peer-Reviewed e-Journals</div>
                        </div>
                    </div>

                    <div class="mvc-onos-kpi-card">
                        <div class="mvc-onos-kpi-icon mvc-onos-kpi-purple">🌐</div>
                        <div>
                            <div class="mvc-onos-kpi-val">6,300+</div>
                            <div class="mvc-onos-kpi-lbl">Institutions in National Grid</div>
                        </div>
                    </div>

                    <div class="mvc-onos-kpi-card">
                        <div class="mvc-onos-kpi-icon mvc-onos-kpi-green">₹0</div>
                        <div>
                            <div class="mvc-onos-kpi-val">100% Free</div>
                            <div class="mvc-onos-kpi-lbl">Zero Paywall for MVC Scholars</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. DESIGNATED INSTITUTIONAL NODAL OFFICER SPOTLIGHT -->
            <section class="mvc-onos-nodal-section" aria-label="Designated ONOS Nodal Desk">
                <div class="mvc-onos-nodal-left">
                    <div class="mvc-onos-nodal-avatar-wrap">
                        <div class="mvc-onos-nodal-avatar">👨‍🔬</div>
                        <div class="mvc-onos-nodal-seal" title="Officially Appointed Nodal Officer">✓</div>
                    </div>
                    <div>
                        <div class="mvc-onos-nodal-badge">
                            <span>🏛️ Officially Designated Institutional Nodal Officer</span>
                        </div>
                        <h2 class="mvc-onos-nodal-name">Dr. Ravi Thakur</h2>
                        <div class="mvc-onos-nodal-title">
                            Assistant Professor, Department of Chemistry
                        </div>
                        <div class="mvc-onos-nodal-dept">
                            Maharshi Vishwamitra Mahavidyalaya, Buxar &bull; Constituent of VKSU Ara
                        </div>
                        <div style="font-size: 12px; color: #94A3B8; margin-top: 4px;">
                            Office: Science Block, Chemistry Research Wing / Central Library Desk
                        </div>
                    </div>
                </div>

                <div class="mvc-onos-nodal-right">
                    <div class="mvc-onos-nodal-mandate">
                        Mandated by the Principal to facilitate institutional scholar registration, IP range verification, Mendeley citation indexing, and remote INFED federated access.
                    </div>
                    <div class="mvc-onos-nodal-btns">
                        <a href="mailto:mvcollegebuxar@gmail.com?subject=ONOS%20Scholar%20Inquiry%20-%20Attn%20Dr.%20Ravi%20Thakur" class="mvc-onos-btn-nodal-email">
                            <span>✉️</span> Email Nodal Officer
                        </a>
                        <button type="button" class="mvc-onos-btn-nodal-req" onclick="mvcOpenOnosRequestModal();">
                            <span>📋</span> Request Research Help
                        </button>
                    </div>
                </div>
            </section>

            <!-- 4. PARTICIPATING GLOBAL PUBLISHERS DIRECTORY & CATALOG -->
            <section id="mvcOnosCatalog" class="mvc-onos-catalog-section" aria-label="Global Publishers Directory">
                <div class="mvc-onos-section-head">
                    <span class="mvc-onos-section-kicker">International Academic Publisher Network</span>
                    <h2 class="mvc-onos-section-title">Participating Global Publishers &amp; Digital Databases</h2>
                    <p class="mvc-onos-section-desc">
                        Explore major international publishers licensed under the Government of India One Nation One Subscription framework. Click any gateway to search, read, and download peer-reviewed literature across Pure Sciences, Computing, Humanities, and Social Sciences.
                    </p>
                </div>

                <!-- Interactive Search & Discipline Filter Bar -->
                <div class="mvc-onos-filter-card">
                    <div class="mvc-onos-search-row">
                        <div class="mvc-onos-search-box">
                            <span class="mvc-onos-search-icon">🔍</span>
                            <input type="text" id="mvcOnosSearchInput" class="mvc-onos-search-input" placeholder="Search publishers by name, subject (e.g. Chemistry, Physics, IEEE, Springer, History)..." oninput="mvcFilterPublishers();" aria-label="Search participating publishers">
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="mvc-onos-tabs">
                        <button type="button" class="mvc-onos-tab-btn active" data-cat="all" onclick="mvcSelectPubTab('all', this);">
                            All Gateways (<?php echo count($publishers); ?>)
                        </button>
                        <button type="button" class="mvc-onos-tab-btn" data-cat="science" onclick="mvcSelectPubTab('science', this);">
                            🔬 Chemical &amp; Physical Sciences
                        </button>
                        <button type="button" class="mvc-onos-tab-btn" data-cat="tech" onclick="mvcSelectPubTab('tech', this);">
                            ⚡ Technology &amp; Computing
                        </button>
                        <button type="button" class="mvc-onos-tab-btn" data-cat="humanities" onclick="mvcSelectPubTab('humanities', this);">
                            📜 Humanities, Law &amp; Social Sciences
                        </button>
                        <button type="button" class="mvc-onos-tab-btn" data-cat="multidisciplinary" onclick="mvcSelectPubTab('multidisciplinary', this);">
                            🌐 Multidisciplinary
                        </button>
                        <button type="button" class="mvc-onos-tab-btn" data-cat="consortia" onclick="mvcSelectPubTab('consortia', this);">
                            🇮🇳 National Consortia (N-LIST &amp; Shodhganga)
                        </button>
                    </div>
                </div>

                <!-- Empty State for Filters -->
                <div class="mvc-onos-empty-state" id="mvcOnosEmptyState">
                    <div class="mvc-onos-empty-icon">🔍</div>
                    <h3 class="mvc-onos-empty-title">No Matching Gateways Found</h3>
                    <p class="mvc-onos-empty-desc">No participating publishers match your keyword search or discipline filter.</p>
                    <button type="button" class="mvc-onos-btn-primary" onclick="mvcResetPubFilters();" style="display:inline-flex;">
                        <span>↺</span> Reset Publisher Filters
                    </button>
                </div>

                <!-- Publishers Grid -->
                <div class="mvc-onos-pub-grid" id="mvcOnosPubGrid">
                    <?php foreach ($publishers as $pub): 
                        $search_blob = strtolower($pub['name'] . ' ' . $pub['flagship'] . ' ' . $pub['disciplines'] . ' ' . $pub['journals']);
                    ?>
                        <article class="mvc-onos-pub-card" 
                                 data-cat="<?php echo esc_attr($pub['category']); ?>" 
                                 data-keywords="<?php echo esc_attr($search_blob); ?>"
                                 id="pub-<?php echo esc_attr($pub['id']); ?>">
                            <div>
                                <div class="mvc-onos-pub-header">
                                    <div class="mvc-onos-pub-icon-wrap"><?php echo $pub['icon']; ?></div>
                                    <div class="mvc-onos-pub-title-block">
                                        <h3 class="mvc-onos-pub-name"><?php echo esc_html($pub['name']); ?></h3>
                                        <span class="mvc-onos-pub-badge"><?php echo esc_html($pub['badge']); ?></span>
                                    </div>
                                </div>

                                <div class="mvc-onos-pub-meta-box">
                                    <div class="mvc-onos-pub-meta-row">
                                        <strong>Coverage:</strong> <?php echo esc_html($pub['journals']); ?>
                                    </div>
                                    <div class="mvc-onos-pub-meta-row">
                                        <strong>Notable:</strong> <?php echo esc_html($pub['flagship']); ?>
                                    </div>
                                </div>

                                <div class="mvc-onos-pub-disciplines">
                                    <strong>Key Disciplines:</strong> <?php echo esc_html($pub['disciplines']); ?>
                                </div>
                            </div>

                            <div class="mvc-onos-pub-footer">
                                <span class="mvc-onos-pub-access-tag">
                                    <span>✓</span> <?php echo esc_html($pub['access']); ?>
                                </span>
                                <a href="<?php echo esc_url($pub['url']); ?>" target="_blank" rel="noopener noreferrer" class="mvc-onos-pub-link-btn" title="Open official <?php echo esc_attr($pub['name']); ?> portal">
                                    <span>Access Portal</span> &nearr;
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 5. THREE ACCESS MODES (STEP-BY-STEP USER GUIDE) -->
            <section class="mvc-onos-modes-section" aria-label="Access Protocols">
                <div class="mvc-onos-section-head">
                    <span class="mvc-onos-section-kicker">User Enablement Protocol</span>
                    <h2 class="mvc-onos-section-title">How to Access ONOS at Maharshi Vishwamitra Mahavidyalaya</h2>
                    <p class="mvc-onos-section-desc">
                        Scholars and faculty members can retrieve full-text articles through three sanctioned access protocols:
                    </p>
                </div>

                <div class="mvc-onos-modes-grid">
                    <!-- Mode 1: Campus IP -->
                    <div class="mvc-onos-mode-card">
                        <div class="mvc-onos-mode-number">1</div>
                        <h3 class="mvc-onos-mode-title">Campus Wi-Fi &amp; IP Network</h3>
                        <p class="mvc-onos-mode-desc">
                            Direct, automated institutional access without individual logins whenever connected to the college network.
                        </p>
                        <ul class="mvc-onos-mode-steps">
                            <li>Connect your device to <strong>MVC Campus High-Speed Wi-Fi</strong> or library LAN.</li>
                            <li>Navigate directly to any participating publisher site (e.g., <code>sciencedirect.com</code> or <code>springer.com</code>).</li>
                            <li>The banner will automatically show: <em>"Access provided by Maharshi Vishwamitra Mahavidyalaya / ONOS"</em>.</li>
                            <li>Read and download complete full-text PDF manuscripts instantly.</li>
                        </ul>
                        <div class="mvc-onos-mode-badge">
                            📍 Available across all academic wings &amp; labs
                        </div>
                    </div>

                    <!-- Mode 2: Remote / Off-Campus INFED -->
                    <div class="mvc-onos-mode-card gold-top">
                        <div class="mvc-onos-mode-number">2</div>
                        <h3 class="mvc-onos-mode-title">Remote Access via INFED / SSO</h3>
                        <p class="mvc-onos-mode-desc">
                            Access international peer-reviewed journals from home, research field trips, or anywhere in the world.
                        </p>
                        <ul class="mvc-onos-mode-steps">
                            <li>Visit the target journal website or official <code>onos.gov.in</code>.</li>
                            <li>Click on <strong>"Institutional Login"</strong> or <strong>"Log In via your Institution"</strong>.</li>
                            <li>Select the Indian national federation: <strong>INFLIBNET / INFED</strong>.</li>
                            <li>Search and select <strong>"Maharshi Vishwamitra Mahavidyalaya, Buxar"</strong>.</li>
                            <li>Sign in with your verified institutional Google workspace / college scholar email.</li>
                        </ul>
                        <div class="mvc-onos-mode-badge" style="background:#FEF9C3; border-color:#FDE047; color:#854D0E;">
                            🌐 24&times;7 Remote Scholar Access Active
                        </div>
                    </div>

                    <!-- Mode 3: Central Library Terminals -->
                    <div class="mvc-onos-mode-card saffron-top">
                        <div class="mvc-onos-mode-number">3</div>
                        <h3 class="mvc-onos-mode-title">Central Library Research Terminals</h3>
                        <p class="mvc-onos-mode-desc">
                            Dedicated desktop computing terminals with dedicated gigabit lease lines, Mendeley software, and printing facilities.
                        </p>
                        <ul class="mvc-onos-mode-steps">
                            <li>Visit the <strong>Automated Central Digital Library &amp; e-Resource Centre</strong>.</li>
                            <li>Log in to any of the 20+ dedicated research terminals using your student/faculty ID.</li>
                            <li>Staff librarians and Nodal Officer Dr. Ravi Thakur provide hands-on assistance for Boolean literature search.</li>
                            <li>High-speed local printing, citation downloads, and Mendeley/Zotero exports available.</li>
                        </ul>
                        <div class="mvc-onos-mode-badge" style="background:#FFF7ED; border-color:#FFEDD5; color:#C2410C;">
                            📖 Library Hours: Mon – Sat 10:00 AM – 05:00 PM
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. GUIDELINES & FAIR USE PROTOCOL -->
            <section class="mvc-onos-guide-banner" aria-label="ONOS Fair Use Protocol">
                <div class="mvc-onos-guide-icon">⚖️</div>
                <div class="mvc-onos-guide-body">
                    <h3>National Academic Integrity &amp; Fair-Use Regulations</h3>
                    <p>
                        Electronic journals and databases provided under One Nation One Subscription are governed by international copyright laws and Government of India Ministry of Education license agreements. All members of Maharshi Vishwamitra Mahavidyalaya must adhere to these statutory fair-use standards:
                    </p>
                    <ul>
                        <li><strong>Permitted Scholarly Use:</strong> Viewing, downloading, and printing single copies of peer-reviewed articles strictly for individual academic coursework, dissertation preparation, empirical research, and classroom teaching.</li>
                        <li><strong>Prohibited Misuse:</strong> Automated systematic downloading, bulk web-scraping using software robots, sharing credentials with non-institutional users, or commercial re-distribution of copyrighted manuscripts.</li>
                        <li><strong>Plagiarism Threshold:</strong> Under UGC Regulations 2018, all research theses and papers citing ONOS literature must undergo mandatory similarity screening (&lt;10% threshold) through the college Central Research Cell.</li>
                    </ul>
                </div>
            </section>

            <!-- 7. FREQUENTLY ASKED QUESTIONS (FAQ ACCORDION) -->
            <section class="mvc-onos-faq-section" aria-label="Frequently Asked Questions">
                <div class="mvc-onos-section-head">
                    <span class="mvc-onos-section-kicker">Clarifications &amp; Guidance</span>
                    <h2 class="mvc-onos-section-title">Frequently Asked Questions</h2>
                    <p class="mvc-onos-section-desc">
                        Common queries regarding One Nation One Subscription eligibility, literature retrieval, and credentials at MVC Buxar.
                    </p>
                </div>

                <div class="mvc-onos-faq-list">
                    <div class="mvc-onos-faq-item">
                        <button type="button" class="mvc-onos-faq-question" onclick="mvcToggleFaq(this);">
                            <span>1. Who is eligible to use ONOS at Maharshi Vishwamitra Mahavidyalaya?</span>
                            <span class="mvc-onos-faq-arrow">▼</span>
                        </button>
                        <div class="mvc-onos-faq-answer">
                            All enrolled Four-Year FYUGP NEP undergraduate students, postgraduate scholars, permanent teaching faculty, ad-hoc guest professors, and administrative research personnel of Maharshi Vishwamitra Mahavidyalaya are fully entitled to free access under the college's constituent affiliation with Veer Kunwar Singh University (VKSU).
                        </div>
                    </div>

                    <div class="mvc-onos-faq-item">
                        <button type="button" class="mvc-onos-faq-question" onclick="mvcToggleFaq(this);">
                            <span>2. Is there any subscription or download fee charged to students?</span>
                            <span class="mvc-onos-faq-arrow">▼</span>
                        </button>
                        <div class="mvc-onos-faq-answer">
                            No. One Nation One Subscription is 100% centrally funded by the Government of India through the Ministry of Education. Neither the college nor the publishers charge any subscription fee, paywall unlocks, or article processing charges (APCs) to students and faculty.
                        </div>
                    </div>

                    <div class="mvc-onos-faq-item">
                        <button type="button" class="mvc-onos-faq-question" onclick="mvcToggleFaq(this);">
                            <span>3. How is ONOS different from INFLIBNET N-LIST?</span>
                            <span class="mvc-onos-faq-arrow">▼</span>
                        </button>
                        <div class="mvc-onos-faq-answer">
                            INFLIBNET N-LIST provides access to college-focused e-books (1,99,500+) and select e-journals primarily for undergraduate colleges. ONOS is a comprehensive national site license scheme covering tier-1 international research journals (Elsevier, Springer Nature, Wiley, IEEE, ACS, RSC, OUP, CUP) that were previously accessible only at central universities and IITs. Both platforms complement each other at MVC Buxar.
                        </div>
                    </div>

                    <div class="mvc-onos-faq-item">
                        <button type="button" class="mvc-onos-faq-question" onclick="mvcToggleFaq(this);">
                            <span>4. What should I do if a paper on ScienceDirect or Springer shows a paywall on campus?</span>
                            <span class="mvc-onos-faq-arrow">▼</span>
                        </button>
                        <div class="mvc-onos-faq-answer">
                            Ensure your device is connected to the official college Wi-Fi and not your private mobile hotspot. If an IP renewal issue occurs, click "Request Literature / PDF Assistance" on this page, or contact Nodal Officer Dr. Ravi Thakur at the Department of Chemistry. The paper will be retrieved for you via the Central Library console within 24 hours.
                        </div>
                    </div>

                    <div class="mvc-onos-faq-item">
                        <button type="button" class="mvc-onos-faq-question" onclick="mvcToggleFaq(this);">
                            <span>5. Can undergraduate students cite ONOS journals in their NEP seminar projects?</span>
                            <span class="mvc-onos-faq-arrow">▼</span>
                        </button>
                        <div class="mvc-onos-faq-answer">
                            Yes, absolutely! The National Education Policy (NEP 2020) curriculum actively encourages empirical scientific inquiry, secondary literature reviews, and research methodology for 3rd and 4th-year FYUGP degree students. Citing peer-reviewed ONOS journals significantly improves student project evaluation scores.
                        </div>
                    </div>

                    <div class="mvc-onos-faq-item">
                        <button type="button" class="mvc-onos-faq-question" onclick="mvcToggleFaq(this);">
                            <span>6. How can faculty members organize an ONOS orientation session for their department?</span>
                            <span class="mvc-onos-faq-arrow">▼</span>
                        </button>
                        <div class="mvc-onos-faq-answer">
                            Department Heads (HODs) can send an official note or email to Nodal Officer Dr. Ravi Thakur (mvcollegebuxar@gmail.com) requesting an orientation workshop. Special training on advanced Boolean search, citation management with Mendeley, and predatory journal avoidance is regularly arranged in the Central Library Audio-Visual Hall.
                        </div>
                    </div>
                </div>
            </section>

            <!-- 8. RELATED ACADEMIC GATEWAYS BAR -->
            <section class="mvc-onos-related-bar" aria-label="Related Academic Gateways">
                <div class="mvc-onos-related-left">
                    <h4>Connected Institutional Academic Portals</h4>
                    <p>Access companion scholarly repositories, automated catalog OPAC, and departmental faculty profiles.</p>
                </div>
                <div class="mvc-onos-related-links">
                    <a href="<?php echo esc_url(home_url('/library/')); ?>" class="mvc-onos-related-btn primary">
                        <span>📚</span> Central Digital Library &amp; OPAC &rarr;
                    </a>
                    <a href="<?php echo esc_url(home_url('/research/')); ?>" class="mvc-onos-related-btn">
                        <span>🔬</span> Research &amp; Publications Portal &rarr;
                    </a>
                    <a href="<?php echo esc_url(home_url('/teaching-staff/')); ?>" class="mvc-onos-related-btn">
                        <span>👨‍🏫</span> Teaching Faculty Directory &rarr;
                    </a>
                </div>
            </section>
        </div>

        <!-- 9. SCHOLAR LITERATURE REQUEST MODAL -->
        <div class="mvc-onos-modal-overlay" id="mvcOnosRequestModal" onclick="if(event.target === this) mvcCloseOnosRequestModal();" role="dialog" aria-modal="true" aria-labelledby="mvcOnosModalTitle">
            <div class="mvc-onos-modal-dialog">
                <div class="mvc-onos-modal-header">
                    <div>
                        <h3 id="mvcOnosModalTitle">Request Full-Text Literature / Article Assistance</h3>
                        <p>ONOS Institutional Nodal Desk &bull; Dr. Ravi Thakur &amp; Central Library</p>
                    </div>
                    <button type="button" class="mvc-onos-modal-close" onclick="mvcCloseOnosRequestModal();" aria-label="Close modal">&times;</button>
                </div>

                <div class="mvc-onos-modal-body">
                    <div id="mvcOnosModalAlert" class="mvc-onos-alert-box"></div>

                    <form id="mvcOnosRequestForm" onsubmit="mvcSubmitOnosRequest(event);">
                        <div class="mvc-onos-form-grid">
                            <div class="mvc-onos-form-group">
                                <label for="onos_req_name">Scholar / Faculty Name <span style="color:#DC2626;">*</span></label>
                                <input type="text" id="onos_req_name" name="requester_name" value="<?php echo esc_attr($current_user->display_name); ?>" placeholder="e.g. Ramesh Kumar" required>
                            </div>

                            <div class="mvc-onos-form-group">
                                <label for="onos_req_email">Institutional / Contact Email <span style="color:#DC2626;">*</span></label>
                                <input type="email" id="onos_req_email" name="requester_email" value="<?php echo esc_attr($current_user->user_email); ?>" placeholder="e.g. scholar@gmail.com" required>
                            </div>

                            <div class="mvc-onos-form-group">
                                <label for="onos_req_role">Academic Designation / Program <span style="color:#DC2626;">*</span></label>
                                <select id="onos_req_role" name="requester_role" required>
                                    <option value="UG Student (FYUGP NEP)">UG Student (Four-Year FYUGP NEP)</option>
                                    <option value="Postgraduate (P.G.) Scholar">Postgraduate (P.G.) Scholar</option>
                                    <option value="Teaching Faculty / Professor">Teaching Faculty / Professor</option>
                                    <option value="Research Scholar / Ph.D.">Research Scholar / Ph.D.</option>
                                    <option value="Department Head (HOD)">Department Head (HOD)</option>
                                </select>
                            </div>

                            <div class="mvc-onos-form-group">
                                <label for="onos_req_dept">Academic Department <span style="color:#DC2626;">*</span></label>
                                <select id="onos_req_dept" name="requester_dept" required>
                                    <option value="Chemistry">Department of Chemistry</option>
                                    <option value="Physics">Department of Physics</option>
                                    <option value="Mathematics">Department of Mathematics</option>
                                    <option value="Botany">Department of Botany</option>
                                    <option value="Zoology">Department of Zoology</option>
                                    <option value="History">Department of History</option>
                                    <option value="Political Science">Department of Political Science</option>
                                    <option value="Economics">Department of Economics</option>
                                    <option value="Hindi">Department of Hindi</option>
                                    <option value="English">Department of English</option>
                                    <option value="Philosophy">Department of Philosophy</option>
                                    <option value="Sanskrit">Department of Sanskrit</option>
                                    <option value="Urdu">Department of Urdu</option>
                                    <option value="Psychology">Department of Psychology</option>
                                    <option value="Geography">Department of Geography</option>
                                    <option value="Commerce & Vocational">Commerce / BCA / Vocational</option>
                                </select>
                            </div>

                            <div class="mvc-onos-form-full mvc-onos-form-group">
                                <label for="onos_article_title">Paper / Manuscript Title or Research Topic <span style="color:#DC2626;">*</span></label>
                                <input type="text" id="onos_article_title" name="article_title" placeholder="e.g. Synthesis of Transition Metal Catalysts in Water..." required>
                            </div>

                            <div class="mvc-onos-form-full mvc-onos-form-group">
                                <label for="onos_article_doi">DOI / Article URL / Journal &amp; Publisher Details</label>
                                <input type="text" id="onos_article_doi" name="article_doi" placeholder="e.g. https://doi.org/10.1016/j.tet.2024.133456 or Elsevier ScienceDirect">
                            </div>

                            <div class="mvc-onos-form-full mvc-onos-form-group">
                                <label for="onos_req_notes">Additional Research Notes / Specific Volume or Issue Needed</label>
                                <textarea id="onos_req_notes" name="notes" rows="3" placeholder="Mention volume, issue numbers, or specific chapters needed for your coursework or dissertation..."></textarea>
                            </div>
                        </div>

                        <div class="mvc-onos-modal-footer">
                            <button type="button" class="mvc-onos-btn-secondary" style="color:var(--mvc-onos-slate-700)!important;" onclick="mvcCloseOnosRequestModal();">Cancel</button>
                            <button type="submit" class="mvc-onos-btn-primary" id="mvcBtnSubmitOnos">
                                <span>🚀</span> Submit Request to Nodal Desk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 10. CLIENT ENGINE JAVASCRIPT -->
        <script>
            var mvcActivePubCategory = 'all';

            // Publisher Filter Engine
            function mvcFilterPublishers() {
                var searchInput = document.getElementById('mvcOnosSearchInput');
                var searchVal = (searchInput ? searchInput.value || '' : '').toLowerCase().trim();

                var cards = document.querySelectorAll('.mvc-onos-pub-card');
                var visibleCount = 0;

                cards.forEach(function(card) {
                    var cat = card.getAttribute('data-cat');
                    var keywords = card.getAttribute('data-keywords') || '';

                    var matchCategory = (mvcActivePubCategory === 'all' || cat === mvcActivePubCategory);
                    var matchSearch = (searchVal === '' || keywords.indexOf(searchVal) !== -1);

                    if (matchCategory && matchSearch) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                var emptyEl = document.getElementById('mvcOnosEmptyState');
                var gridEl = document.getElementById('mvcOnosPubGrid');

                if (visibleCount === 0) {
                    if (emptyEl) emptyEl.style.display = 'block';
                    if (gridEl) gridEl.style.display = 'none';
                } else {
                    if (emptyEl) emptyEl.style.display = 'none';
                    if (gridEl) gridEl.style.display = 'grid';
                }
            }

            // Tab Selection
            function mvcSelectPubTab(category, btn) {
                mvcActivePubCategory = category;
                var btns = document.querySelectorAll('.mvc-onos-tab-btn');
                btns.forEach(function(b) { b.classList.remove('active'); });
                if (btn) btn.classList.add('active');
                mvcFilterPublishers();
            }

            // Reset Publisher Filters
            function mvcResetPubFilters() {
                var searchInput = document.getElementById('mvcOnosSearchInput');
                if (searchInput) searchInput.value = '';

                var allTab = document.querySelector('.mvc-onos-tab-btn[data-cat="all"]');
                mvcSelectPubTab('all', allTab);
            }

            // FAQ Accordion Toggle
            function mvcToggleFaq(btn) {
                var answer = btn.nextElementSibling;
                var arrow = btn.querySelector('.mvc-onos-faq-arrow');
                if (!answer) return;

                var isVisible = answer.style.display === 'block';
                if (isVisible) {
                    answer.style.display = 'none';
                    if (arrow) arrow.textContent = '▼';
                } else {
                    answer.style.display = 'block';
                    if (arrow) arrow.textContent = '▲';
                }
            }

            // Modal Handlers
            function mvcOpenOnosRequestModal() {
                var modal = document.getElementById('mvcOnosRequestModal');
                if (modal) {
                    modal.style.display = 'flex';
                    var alertBox = document.getElementById('mvcOnosModalAlert');
                    if (alertBox) alertBox.style.display = 'none';
                }
            }

            function mvcCloseOnosRequestModal() {
                var modal = document.getElementById('mvcOnosRequestModal');
                if (modal) modal.style.display = 'none';
            }

            // Literature Request Form Handler
            function mvcSubmitOnosRequest(e) {
                e.preventDefault();
                var form = document.getElementById('mvcOnosRequestForm');
                var submitBtn = document.getElementById('mvcBtnSubmitOnos');
                var alertBox = document.getElementById('mvcOnosModalAlert');

                if (!form || !submitBtn) return;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>⏳</span> Transmitting to Nodal Desk...';

                // Collect request details
                var name    = document.getElementById('onos_req_name').value.trim();
                var email   = document.getElementById('onos_req_email').value.trim();
                var role    = document.getElementById('onos_req_role').value;
                var dept    = document.getElementById('onos_req_dept').value;
                var title   = document.getElementById('onos_article_title').value.trim();
                var doi     = document.getElementById('onos_article_doi').value.trim();
                var notes   = document.getElementById('onos_req_notes').value.trim();

                // Format mailto fallback for direct communication
                var mailSubject = encodeURIComponent('ONOS Literature Retrieval Request: ' + title);
                var mailBody = encodeURIComponent(
                    'Dear Dr. Ravi Thakur (Institutional Nodal Officer, ONOS),\n\n' +
                    'I am requesting full-text literature retrieval assistance under the ONOS framework:\n\n' +
                    '• Requester Name: ' + name + '\n' +
                    '• Designation/Role: ' + role + '\n' +
                    '• Department: ' + dept + '\n' +
                    '• Contact Email: ' + email + '\n\n' +
                    '• Article Title: ' + title + '\n' +
                    '• DOI / Publisher Link: ' + doi + '\n' +
                    '• Additional Notes: ' + notes + '\n\n' +
                    'Thank you,\n' + name + '\nMaharshi Vishwamitra Mahavidyalaya, Buxar'
                );

                setTimeout(function() {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>🚀</span> Submit Request to Nodal Desk';

                    alertBox.className = 'mvc-onos-alert-box mvc-onos-alert-success';
                    alertBox.innerHTML = '<strong>Request Logged Successfully!</strong><br>Opening your institutional email to dispatch your literature request directly to Nodal Officer Dr. Ravi Thakur...';
                    alertBox.style.display = 'block';

                    // Trigger client email dispatch
                    window.location.href = 'mailto:mvcollegebuxar@gmail.com?subject=' + mailSubject + '&body=' + mailBody;

                    setTimeout(function() {
                        mvcCloseOnosRequestModal();
                        form.reset();
                    }, 2500);
                }, 800);
            }

            // Run verification on DOM load
            document.addEventListener('DOMContentLoaded', function() {
                mvcFilterPublishers();
            });
        </script>
    </div>
    <?php
}
}
