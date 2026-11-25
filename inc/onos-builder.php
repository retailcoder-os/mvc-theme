<?php
/**
 * ONE NATION ONE SUBSCRIPTION (ONOS) SUITE BUILDER
 * 
 * Official National Higher Education Digital Knowledge Network Gateway
 * Ministry of Education (Department of Higher Education), Government of India & INFLIBNET Centre (UGC)
 * Maharshi Vishwamitra Mahavidyalaya, Buxar (Veer Kunwar Singh University)
 * 
 * Institutional Nodal Officer: Dr. Ravi Thakur, Assistant Professor, Department of Chemistry
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

    // Verified Major International Academic Publishers under ONOS Phase-1
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
            'id'          => 'ams',
            'name'        => 'American Mathematical Society (AMS)',
            'category'    => 'science',
            'journals'    => 'Pure & Applied Mathematical Research Journals',
            'flagship'    => 'Journal of the AMS, Bulletin of the AMS, Mathematics of Computation',
            'disciplines' => 'Mathematics, Statistics, Algebra, Geometry, Applied Analysis',
            'url'         => 'https://www.ams.org/journals',
            'icon'        => '📐',
            'badge'       => 'Mathematics Core',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'emerald',
            'name'        => 'Emerald Publishing',
            'category'    => 'humanities',
            'journals'    => '300+ Peer-Reviewed Management Journals',
            'flagship'    => 'Journal of Documentation, European Journal of Marketing',
            'disciplines' => 'Management, Commerce, Public Policy, Information Science',
            'url'         => 'https://www.emerald.com/insight/',
            'icon'        => '📊',
            'badge'       => 'Management & Policy',
            'access'      => 'Campus IP & INFED SSO'
        ),
        array(
            'id'          => 'bmj',
            'name'        => 'BMJ Journals',
            'category'    => 'science',
            'journals'    => '60+ Leading Medical & Life Sciences Journals',
            'flagship'    => 'The BMJ (British Medical Journal), Thorax, Gut, Medical Ethics',
            'disciplines' => 'Life Sciences, Public Health, Environmental Health, Clinical Biology',
            'url'         => 'https://journals.bmj.com/',
            'icon'        => '🩺',
            'badge'       => 'Medical & Life Sci',
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
            'name'        => 'Official ONOS Central Portal',
            'category'    => 'consortia',
            'journals'    => 'Unified National Search Engine',
            'flagship'    => 'Department of Higher Education, Ministry of Education, Govt. of India',
            'disciplines' => 'Central One-Stop Search Across All 30+ Participating Global Publishers',
            'url'         => 'https://www.onos.gov.in/',
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
               Crisp, Official, Institutional Navy & Gold Architecture
               ======================================================== */
            :root {
                --mvc-onos-navy: #002147;
                --mvc-onos-navy-dark: #001229;
                --mvc-onos-gold: #D4AF37;
                --mvc-onos-gold-dark: #B8860B;
                --mvc-onos-gold-light: #FEF08A;
                --mvc-onos-saffron: #EA580C;
                --mvc-onos-green: #15803D;
                --mvc-onos-slate-50: #F8FAFC;
                --mvc-onos-slate-100: #F1F5F9;
                --mvc-onos-slate-200: #E2E8F0;
                --mvc-onos-slate-300: #CBD5E1;
                --mvc-onos-slate-600: #475569;
                --mvc-onos-slate-700: #334155;
                --mvc-onos-slate-800: #1E293B;
            }

            .mvc-onos-suite {
                font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Outfit", "Segoe UI", Roboto, sans-serif;
                background-color: #F8FAFC;
                color: var(--mvc-onos-slate-800);
                line-height: 1.6;
                padding-bottom: 70px;
            }

            /* Container */
            .mvc-onos-container {
                max-width: 1240px;
                margin: 0 auto;
                padding: 0 20px;
                box-sizing: border-box;
            }

            /* Breadcrumb */
            .mvc-onos-breadcrumb {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: #94A3B8;
                margin-bottom: 16px;
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
                background: linear-gradient(135deg, #001229 0%, #002147 60%, #001838 100%);
                color: #FFFFFF;
                padding: 42px 20px 48px;
                border-bottom: 4px solid var(--mvc-onos-gold);
                box-shadow: 0 10px 30px rgba(0, 33, 71, 0.2);
            }

            .mvc-onos-hero-inner {
                max-width: 1240px;
                margin: 0 auto;
            }

            .mvc-onos-emblem-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(212, 175, 55, 0.4);
                padding: 5px 14px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 700;
                color: #FEF08A;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 14px;
            }

            .mvc-onos-hindi-kicker {
                font-size: 15px;
                font-weight: 700;
                color: var(--mvc-onos-gold);
                margin-bottom: 6px;
            }

            .mvc-onos-hero-title {
                font-size: 36px;
                font-weight: 900;
                line-height: 1.2;
                margin: 0 0 14px;
                color: #FFFFFF;
                letter-spacing: -0.5px;
            }

            .mvc-onos-hero-desc {
                font-size: 15.5px;
                color: #CBD5E1;
                max-width: 960px;
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
                font-size: 13.5px;
                padding: 11px 22px;
                border-radius: 8px;
                text-decoration: none !important;
                border: 1px solid #FFE082;
                box-shadow: 0 4px 14px rgba(212, 175, 55, 0.3);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .mvc-onos-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(212, 175, 55, 0.45);
            }

            .mvc-onos-btn-secondary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(255, 255, 255, 0.12);
                color: #FFFFFF !important;
                font-weight: 700;
                font-size: 13.5px;
                padding: 11px 20px;
                border-radius: 8px;
                text-decoration: none !important;
                border: 1px solid rgba(255, 255, 255, 0.25);
                transition: background 0.2s ease, transform 0.2s ease;
            }

            .mvc-onos-btn-secondary:hover {
                background: rgba(255, 255, 255, 0.22);
                transform: translateY(-2px);
            }

            /* Key Metrics Bar */
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
                box-shadow: 0 6px 18px rgba(0, 33, 71, 0.05);
                transition: transform 0.2s ease;
            }

            .mvc-onos-kpi-card:hover {
                transform: translateY(-2px);
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
                font-size: 23px;
                font-weight: 900;
                color: var(--mvc-onos-navy);
                line-height: 1.1;
                margin-bottom: 2px;
            }

            .mvc-onos-kpi-lbl {
                font-size: 12px;
                font-weight: 700;
                color: var(--mvc-onos-slate-600);
                text-transform: uppercase;
                letter-spacing: 0.4px;
            }

            /* Official Factsheet Grid */
            .mvc-onos-factsheet {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 14px;
                padding: 28px 32px;
                margin-bottom: 35px;
                box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            }

            .mvc-onos-fact-title {
                font-size: 18px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 18px;
                display: flex;
                align-items: center;
                gap: 8px;
                border-bottom: 2px solid var(--mvc-onos-slate-100);
                padding-bottom: 12px;
            }

            .mvc-onos-fact-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 14px 28px;
                font-size: 13.5px;
            }

            .mvc-onos-fact-row {
                display: flex;
                align-items: baseline;
                justify-content: space-between;
                border-bottom: 1px dashed var(--mvc-onos-slate-200);
                padding-bottom: 8px;
            }

            .mvc-onos-fact-lbl {
                color: var(--mvc-onos-slate-600);
                font-weight: 600;
            }

            .mvc-onos-fact-val {
                color: var(--mvc-onos-navy);
                font-weight: 800;
                text-align: right;
            }

            /* Nodal Officer Section */
            .mvc-onos-nodal-section {
                background: linear-gradient(135deg, #001A3A 0%, #002B5C 100%);
                border: 2px solid var(--mvc-onos-gold);
                border-radius: 14px;
                padding: 26px 30px;
                color: #FFFFFF;
                box-shadow: 0 10px 28px rgba(0, 33, 71, 0.14);
                margin-bottom: 40px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 24px;
                flex-wrap: wrap;
            }

            .mvc-onos-nodal-left {
                display: flex;
                align-items: center;
                gap: 20px;
                flex: 1;
                min-width: 300px;
            }

            .mvc-onos-nodal-avatar {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                background: rgba(212, 175, 55, 0.2);
                border: 2.5px solid var(--mvc-onos-gold);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 34px;
                flex-shrink: 0;
            }

            .mvc-onos-nodal-badge {
                display: inline-block;
                background: rgba(212, 175, 55, 0.2);
                border: 1px solid rgba(212, 175, 55, 0.4);
                color: #FEF08A;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 2px 10px;
                border-radius: 20px;
                margin-bottom: 4px;
            }

            .mvc-onos-nodal-name {
                font-size: 22px;
                font-weight: 900;
                color: #FFFFFF;
                margin: 0 0 2px;
            }

            .mvc-onos-nodal-title {
                font-size: 14px;
                color: #E2E8F0;
                font-weight: 600;
            }

            .mvc-onos-nodal-dept {
                font-size: 12.5px;
                color: #CBD5E1;
            }

            .mvc-onos-nodal-right {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 10px;
            }

            .mvc-onos-nodal-mandate {
                font-size: 12px;
                color: #CBD5E1;
                text-align: right;
                max-width: 360px;
            }

            .mvc-onos-btn-nodal-email {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                background: var(--mvc-onos-gold);
                color: #002147 !important;
                font-weight: 800;
                font-size: 13px;
                padding: 9px 18px;
                border-radius: 7px;
                text-decoration: none !important;
                border: 1px solid #FFE082;
                transition: transform 0.2s, background 0.2s;
            }

            .mvc-onos-btn-nodal-email:hover {
                background: #FFE082;
                transform: translateY(-2px);
            }

            /* Section Headings */
            .mvc-onos-section-head {
                text-align: center;
                margin: 0 auto 28px;
                max-width: 800px;
            }

            .mvc-onos-section-kicker {
                font-size: 12px;
                font-weight: 800;
                color: #B8860B;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                margin-bottom: 5px;
                display: block;
            }

            .mvc-onos-section-title {
                font-size: 26px;
                font-weight: 900;
                color: var(--mvc-onos-navy);
                margin: 0 0 8px;
                letter-spacing: -0.3px;
            }

            .mvc-onos-section-desc {
                font-size: 14.5px;
                color: var(--mvc-onos-slate-600);
                line-height: 1.6;
                margin: 0;
            }

            /* Interactive Filter Panel */
            .mvc-onos-filter-card {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 12px;
                padding: 18px 22px;
                margin-bottom: 24px;
                box-shadow: 0 4px 12px rgba(0, 33, 71, 0.04);
            }

            .mvc-onos-search-row {
                margin-bottom: 14px;
            }

            .mvc-onos-search-box {
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
                height: 44px !important;
                padding: 0 14px 0 42px !important;
                border: 1.5px solid var(--mvc-onos-slate-300) !important;
                border-radius: 8px !important;
                font-size: 14px !important;
                outline: none !important;
                color: var(--mvc-onos-slate-800) !important;
                background: #FFFFFF !important;
                box-sizing: border-box !important;
                margin: 0 !important;
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
                padding: 6px 14px;
                border-radius: 20px;
                font-size: 12px;
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
            }

            /* Publishers Grid */
            .mvc-onos-pub-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
                gap: 20px;
                margin-bottom: 45px;
            }

            .mvc-onos-pub-card {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 12px;
                padding: 22px;
                box-shadow: 0 4px 12px rgba(0, 33, 71, 0.04);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                transition: transform 0.2s ease, border-color 0.2s ease;
            }

            .mvc-onos-pub-card:hover {
                transform: translateY(-2px);
                border-color: var(--mvc-onos-gold);
            }

            .mvc-onos-pub-header {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 12px;
            }

            .mvc-onos-pub-icon-wrap {
                width: 44px;
                height: 44px;
                border-radius: 8px;
                background: #F8FAFC;
                border: 1px solid var(--mvc-onos-slate-200);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                flex-shrink: 0;
            }

            .mvc-onos-pub-name {
                font-size: 16px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 2px;
            }

            .mvc-onos-pub-badge {
                display: inline-block;
                background: #FEF9C3;
                color: #854D0E;
                font-size: 10.5px;
                font-weight: 800;
                padding: 1px 7px;
                border-radius: 4px;
                border: 1px solid #FDE047;
                text-transform: uppercase;
            }

            .mvc-onos-pub-meta-box {
                background: #F8FAFC;
                border-left: 3px solid var(--mvc-onos-navy);
                border-radius: 0 6px 6px 0;
                padding: 9px 12px;
                margin-bottom: 12px;
                font-size: 12.5px;
            }

            .mvc-onos-pub-meta-row {
                margin-bottom: 3px;
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
                margin-bottom: 16px;
            }

            .mvc-onos-pub-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                padding-top: 12px;
                border-top: 1px solid var(--mvc-onos-slate-200);
                margin-top: auto;
            }

            .mvc-onos-pub-access-tag {
                font-size: 11px;
                font-weight: 700;
                color: #065F46;
                background: #D1FAE5;
                padding: 2px 7px;
                border-radius: 4px;
                border: 1px solid #A7F3D0;
            }

            .mvc-onos-pub-link-btn {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                background: var(--mvc-onos-navy);
                color: #FFFFFF !important;
                font-size: 12px;
                font-weight: 700;
                padding: 6px 13px;
                border-radius: 6px;
                text-decoration: none !important;
                transition: background 0.2s;
            }

            .mvc-onos-pub-link-btn:hover {
                background: #002C5F;
            }

            /* Empty state for search */
            .mvc-onos-empty-state {
                display: none;
                text-align: center;
                padding: 40px 20px;
                background: #FFFFFF;
                border: 2px dashed var(--mvc-onos-slate-300);
                border-radius: 12px;
                margin-bottom: 35px;
            }

            /* Two Official Access Modes */
            .mvc-onos-modes-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                margin-bottom: 45px;
            }

            .mvc-onos-mode-card {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 12px;
                padding: 24px;
                box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
                border-top: 4px solid var(--mvc-onos-navy);
            }

            .mvc-onos-mode-card.gold-top {
                border-top-color: var(--mvc-onos-gold);
            }

            .mvc-onos-mode-number {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                background: var(--mvc-onos-navy);
                color: var(--mvc-onos-gold);
                font-size: 13px;
                font-weight: 800;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 14px;
            }

            .mvc-onos-mode-card.gold-top .mvc-onos-mode-number {
                background: var(--mvc-onos-gold);
                color: #002147;
            }

            .mvc-onos-mode-title {
                font-size: 17px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 8px;
            }

            .mvc-onos-mode-desc {
                font-size: 13px;
                color: var(--mvc-onos-slate-600);
                line-height: 1.55;
                margin-bottom: 14px;
            }

            .mvc-onos-mode-steps {
                list-style: none;
                padding: 0;
                margin: 0;
                font-size: 12.5px;
                color: var(--mvc-onos-slate-700);
            }

            .mvc-onos-mode-steps li {
                position: relative;
                padding-left: 18px;
                margin-bottom: 7px;
                line-height: 1.4;
            }

            .mvc-onos-mode-steps li::before {
                content: '✓';
                position: absolute;
                left: 0;
                color: var(--mvc-onos-green);
                font-weight: 800;
            }

            /* Guidelines */
            .mvc-onos-guide-banner {
                background: #FFFBEB;
                border: 1.5px solid #FDE68A;
                border-radius: 12px;
                padding: 22px 26px;
                margin-bottom: 40px;
                display: flex;
                align-items: flex-start;
                gap: 18px;
            }

            .mvc-onos-guide-icon {
                font-size: 28px;
                flex-shrink: 0;
            }

            .mvc-onos-guide-body h3 {
                font-size: 16px;
                font-weight: 800;
                color: #92400E;
                margin: 0 0 6px;
            }

            .mvc-onos-guide-body p {
                font-size: 13px;
                color: #78350F;
                line-height: 1.6;
                margin: 0 0 10px;
            }

            .mvc-onos-guide-body ul {
                margin: 0;
                padding-left: 16px;
                font-size: 12.5px;
                color: #78350F;
                line-height: 1.55;
            }

            /* Related Academic Gateways */
            .mvc-onos-related-bar {
                background: #FFFFFF;
                border: 1px solid var(--mvc-onos-slate-200);
                border-radius: 12px;
                padding: 22px 28px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                flex-wrap: wrap;
            }

            .mvc-onos-related-left h4 {
                font-size: 16px;
                font-weight: 800;
                color: var(--mvc-onos-navy);
                margin: 0 0 3px;
            }

            .mvc-onos-related-left p {
                font-size: 13px;
                color: var(--mvc-onos-slate-600);
                margin: 0;
            }

            .mvc-onos-related-links {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .mvc-onos-related-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: var(--mvc-onos-slate-100);
                color: var(--mvc-onos-navy) !important;
                font-size: 12.5px;
                font-weight: 700;
                padding: 8px 16px;
                border-radius: 7px;
                border: 1px solid var(--mvc-onos-slate-300);
                text-decoration: none !important;
                transition: all 0.2s ease;
            }

            .mvc-onos-related-btn:hover {
                background: var(--mvc-onos-navy);
                color: #FFFFFF !important;
                border-color: var(--mvc-onos-navy);
            }

            .mvc-onos-related-btn.primary {
                background: var(--mvc-onos-navy);
                color: #FFFFFF !important;
                border-color: var(--mvc-onos-navy);
            }

            /* Responsive */
            @media (max-width: 992px) {
                .mvc-onos-kpi-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .mvc-onos-fact-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-modes-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-nodal-right {
                    align-items: flex-start;
                }
                .mvc-onos-nodal-mandate {
                    text-align: left;
                }
            }

            @media (max-width: 640px) {
                .mvc-onos-hero {
                    padding: 30px 16px 36px;
                }
                .mvc-onos-hero-title {
                    font-size: 26px;
                }
                .mvc-onos-kpi-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-pub-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-onos-nodal-left {
                    flex-direction: column;
                    text-align: center;
                }
                .mvc-onos-nodal-right {
                    text-align: center;
                    width: 100%;
                }
                .mvc-onos-nodal-mandate {
                    text-align: center;
                }
                .mvc-onos-btn-nodal-email {
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

                <!-- National Sovereign Emblem Badge -->
                <div class="mvc-onos-emblem-badge">
                    <span>🇮🇳 Ministry of Education, Govt. of India &bull; INFLIBNET Centre</span>
                </div>

                <!-- Title Cluster -->
                <div class="mvc-onos-hindi-kicker">
                    एक राष्ट्र, एक सदस्यता &bull; One Nation One Subscription (ONOS)
                </div>
                <h1 class="mvc-onos-hero-title">
                    National Higher Education Research Knowledge Network
                </h1>
                <p class="mvc-onos-hero-desc">
                    Approved by the Union Cabinet on 25 November 2024 and launched nationwide on 1 January 2025, <strong>One Nation One Subscription (ONOS)</strong> is a ₹6,000 Crore flagship initiative by the Ministry of Education, Government of India, coordinated through the INFLIBNET Centre. It provides centralized, zero-cost national access to over 13,000+ international peer-reviewed journals from 30 major global publishers across 6,500+ government higher education institutions, benefiting 1.8 crore students, researchers, and faculty members.
                </p>

                <!-- Action Triggers -->
                <div class="mvc-onos-hero-actions">
                    <a href="https://www.onos.gov.in/" target="_blank" rel="noopener noreferrer" class="mvc-onos-btn-primary">
                        <span>🌐</span> Search Official National Portal (onos.gov.in) &nearr;
                    </a>
                    <a href="#mvcOnosCatalog" class="mvc-onos-btn-secondary">
                        <span>🔍</span> Browse Participating Publishers Catalog ↓
                    </a>
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
                            <div class="mvc-onos-kpi-val">6,500+</div>
                            <div class="mvc-onos-kpi-lbl">Institutions in National Grid</div>
                        </div>
                    </div>

                    <div class="mvc-onos-kpi-card">
                        <div class="mvc-onos-kpi-icon mvc-onos-kpi-green">₹0</div>
                        <div>
                            <div class="mvc-onos-kpi-val">100% Free</div>
                            <div class="mvc-onos-kpi-lbl">Zero Paywall for Scholars</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. OFFICIAL SCHEME FACTSHEET MATRIX -->
            <section class="mvc-onos-factsheet" aria-label="ONOS Scheme Factsheet">
                <h2 class="mvc-onos-fact-title">
                    <span>📋</span> Official ONOS Scheme Factsheet &amp; Implementation Parameters
                </h2>
                <div class="mvc-onos-fact-grid">
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Official Name</span>
                        <span class="mvc-onos-fact-val">One Nation One Subscription (ONOS) / एक राष्ट्र, एक सदस्यता</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Union Cabinet Approval</span>
                        <span class="mvc-onos-fact-val">25 November 2024</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Nationwide Launch</span>
                        <span class="mvc-onos-fact-val">1 January 2025</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Phase-1 Implementation</span>
                        <span class="mvc-onos-fact-val">2025 – 2027 (3-Year Block)</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Central Financial Outlay</span>
                        <span class="mvc-onos-fact-val">₹6,000 Crore (Centrally Funded)</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Nodal Ministry</span>
                        <span class="mvc-onos-fact-val">Dept. of Higher Education, Ministry of Education, Govt. of India</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Central Executing Agency</span>
                        <span class="mvc-onos-fact-val">INFLIBNET Centre, Gandhinagar (Autonomous IUC of UGC)</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Official National Portal</span>
                        <span class="mvc-onos-fact-val"><a href="https://www.onos.gov.in" target="_blank" rel="noopener" style="color:var(--mvc-onos-navy);">https://www.onos.gov.in ↗</a></span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Target Beneficiaries</span>
                        <span class="mvc-onos-fact-val">1.8 Crore Scholars in 6,500+ Govt HEIs &amp; R&amp;D Labs</span>
                    </div>
                    <div class="mvc-onos-fact-row">
                        <span class="mvc-onos-fact-lbl">Cost to Students &amp; Faculty</span>
                        <span class="mvc-onos-fact-val" style="color:var(--mvc-onos-green);">₹0 (100% Free / No Paywalls)</span>
                    </div>
                </div>
            </section>

            <!-- 4. INSTITUTIONAL NODAL OFFICER SPOTLIGHT -->
            <section class="mvc-onos-nodal-section" aria-label="Designated ONOS Nodal Desk">
                <div class="mvc-onos-nodal-left">
                    <div class="mvc-onos-nodal-avatar">👨‍🔬</div>
                    <div>
                        <div class="mvc-onos-nodal-badge">
                            <span>🏛️ Designated Institutional Nodal Officer (ONOS)</span>
                        </div>
                        <h3 class="mvc-onos-nodal-name">Dr. Ravi Thakur</h3>
                        <div class="mvc-onos-nodal-title">
                            Assistant Professor, Department of Chemistry
                        </div>
                        <div class="mvc-onos-nodal-dept">
                            Maharshi Vishwamitra Mahavidyalaya, Buxar &bull; Constituent of VKSU Ara
                        </div>
                        <div style="font-size: 12px; color: #94A3B8; margin-top: 3px;">
                            Desk: Science Block, Chemistry Research Wing / Central Library Research Cell
                        </div>
                    </div>
                </div>

                <div class="mvc-onos-nodal-right">
                    <div class="mvc-onos-nodal-mandate">
                        Officially appointed to assist enrolled students and faculty with IP access whitelisting, literature retrieval, Mendeley citations, and off-campus INFED federated login.
                    </div>
                    <a href="mailto:mvcollegebuxar@gmail.com?subject=ONOS%20Scholar%20Inquiry%20-%20Attn%20Dr.%20Ravi%20Thakur" class="mvc-onos-btn-nodal-email">
                        <span>✉️</span> Email Nodal Officer
                    </a>
                </div>
            </section>

            

            <!-- 6. TWO SANCTIONED ACCESS MODES -->
            <section class="mvc-onos-modes-section" aria-label="Access Protocols">
                <div class="mvc-onos-section-head">
                    <span class="mvc-onos-section-kicker">User Authentication Protocol</span>
                    <h2 class="mvc-onos-section-title">How Scholars Access ONOS Literature</h2>
                    <p class="mvc-onos-section-desc">
                        Access to participating journal articles is available through two standardized methods:
                    </p>
                </div>

                <div class="mvc-onos-modes-grid">
                    <!-- Mode 1: Campus IP -->
                    <div class="mvc-onos-mode-card">
                        <div class="mvc-onos-mode-number">1</div>
                        <h3 class="mvc-onos-mode-title">On-Campus IP Recognition (Automatic)</h3>
                        <p class="mvc-onos-mode-desc">
                            Direct, automated access when connected to the official college Wi-Fi or Central Library computer lab.
                        </p>
                        <ul class="mvc-onos-mode-steps">
                            <li>Connect your device to <strong>MVC Campus Wi-Fi</strong> or library local network.</li>
                            <li>Navigate directly to any participating publisher site (e.g. <code>sciencedirect.com</code> or <code>springer.com</code>).</li>
                            <li>The publisher will recognize the institutional IP range and unlock full-text PDF downloads without a password.</li>
                        </ul>
                    </div>

                    <!-- Mode 2: Remote Access via INFED -->
                    <div class="mvc-onos-mode-card gold-top">
                        <div class="mvc-onos-mode-number">2</div>
                        <h3 class="mvc-onos-mode-title">Off-Campus Remote Access via INFED (SSO)</h3>
                        <p class="mvc-onos-mode-desc">
                            24&times;7 remote access from home, hostel, or field research locations worldwide.
                        </p>
                        <ul class="mvc-onos-mode-steps">
                            <li>Visit the target journal website or official national gateway at <code>onos.gov.in</code>.</li>
                            <li>Click <strong>"Institutional Login"</strong> and choose the national identity federation: <strong>INFLIBNET / INFED</strong>.</li>
                            <li>Search and select <strong>"Maharshi Vishwamitra Mahavidyalaya, Buxar"</strong> and authenticate with your institutional credentials.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 7. ACADEMIC INTEGRITY & FAIR USE -->
            <section class="mvc-onos-guide-banner" aria-label="ONOS Fair Use Protocol">
                <div class="mvc-onos-guide-icon">⚖️</div>
                <div class="mvc-onos-guide-body">
                    <h3>National Academic Integrity &amp; Fair-Use Regulations</h3>
                    <p>
                        Journals provided under One Nation One Subscription are governed by international copyright laws and Government of India site licenses:
                    </p>
                    <ul>
                        <li><strong>Permitted Scholarly Use:</strong> Viewing, reading, downloading, and printing single copies of peer-reviewed articles strictly for individual academic coursework, dissertation preparation, empirical research, and classroom teaching.</li>
                        <li><strong>Prohibited Misuse:</strong> Automated systematic downloading, bulk web-scraping using software robots, sharing credentials with non-institutional users, or commercial re-distribution.</li>
                    </ul>
                </div>
            </section>

            <!-- 8. RELATED ACADEMIC GATEWAYS BAR -->
            <section class="mvc-onos-related-bar" aria-label="Related Academic Gateways">
                <div class="mvc-onos-related-left">
                    <h4>Connected Institutional Academic Portals</h4>
                    <p>Access companion scholarly repositories, automated library catalog, and faculty profiles.</p>
                </div>
                <div class="mvc-onos-related-links">
                    <a href="https://www.onos.gov.in/" target="_blank" rel="noopener noreferrer" class="mvc-onos-related-btn primary">
                        <span>🌐</span> Official ONOS Portal (onos.gov.in) ↗
                    </a>
                    <a href="<?php echo esc_url(home_url('/library/')); ?>" class="mvc-onos-related-btn">
                        <span>📚</span> Central Digital Library &rarr;
                    </a>
                    <a href="<?php echo esc_url(home_url('/research/')); ?>" class="mvc-onos-related-btn">
                        <span>🔬</span> Research &amp; Publications &rarr;
                    </a>
                </div>
            </section>
        </div>

        <!-- 9. CLIENT ENGINE JAVASCRIPT -->
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

            // Run on DOM ready
            document.addEventListener('DOMContentLoaded', function() {
                mvcFilterPublishers();
            });
        </script>
    </div>
    <?php
}
}
