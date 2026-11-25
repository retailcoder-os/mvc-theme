<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya - Research & Publications Suite
 * 
 * Comprehensive Research Engine providing:
 * 1. Dedicated CPT 'research' querying and public portal rendering
 * 2. Role-based publishing authority for Administrator, Content Manager, and HODs (Department Heads)
 * 3. Multi-faceted real-time filtering (by Category, Academic Department, Publication Year, and Search)
 * 4. Dual presentation modes: Responsive Card Grid and Academic Bibliography (APA) View
 * 5. National Consortia Hubs: One Nation One Subscription (ONOS) & INFLIBNET N-LIST Integration
 * 6. Interactive Executive Publishing Drawer / Modal for authorized faculty and administrators
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Determine whether the current user has publishing authority for research.
 *
 * @param int|WP_User|null $user
 * @return bool
 */
function mvc_user_can_publish_research($user = null) {
    if (!$user) {
        $user = wp_get_current_user();
    } elseif (is_numeric($user)) {
        $user = get_userdata($user);
    }

    if (!$user || !($user instanceof WP_User) || !$user->exists()) {
        return false;
    }

    // Administrators and users with manage_options
    if (in_array('administrator', (array) $user->roles, true) || user_can($user, 'manage_options')) {
        return true;
    }

    // Content Managers
    if (in_array('content_manager', (array) $user->roles, true) || user_can($user, 'mvc_review_content')) {
        return true;
    }

    // Department Heads (HODs)
    if (in_array('department_head', (array) $user->roles, true) || user_can($user, 'publish_researches')) {
        return true;
    }

    return false;
}

/**
 * 2. Get user's assigned academic department term (if scoped).
 *
 * @param int $user_id
 * @return WP_Term|null
 */
function mvc_get_user_assigned_dept_term($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return null;
    }
    $dept_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
    if ($dept_id > 0) {
        $term = get_term($dept_id, 'dept_link');
        if ($term && !is_wp_error($term)) {
            return $term;
        }
    }
    return null;
}

/**
 * 3. AJAX Handler: Publish or Create Research Article
 */
function mvc_handle_ajax_publish_research() {
    check_ajax_referer('mvc_publish_research_nonce', 'security');

    if (!is_user_logged_in() || !mvc_user_can_publish_research()) {
        wp_send_json_error(array('message' => __('You do not have administrative permission to publish research publications.', 'mvc-college')));
    }

    $current_user = wp_get_current_user();
    $is_admin = in_array('administrator', (array) $current_user->roles, true) || current_user_can('manage_options');
    $is_cm    = in_array('content_manager', (array) $current_user->roles, true);
    $is_hod   = in_array('department_head', (array) $current_user->roles, true);

    $title = isset($_POST['research_title']) ? sanitize_text_field(wp_unslash($_POST['research_title'])) : '';
    if (empty($title)) {
        wp_send_json_error(array('message' => __('Research title is required.', 'mvc-college')));
    }

    $authors = isset($_POST['research_authors']) ? sanitize_text_field(wp_unslash($_POST['research_authors'])) : '';
    if (empty($authors)) {
        $authors = $current_user->display_name;
    }

    // Determine Department
    $dept_term_id = 0;
    if ($is_hod && !($is_admin || $is_cm)) {
        // Force HOD to their assigned department
        $hod_term = mvc_get_user_assigned_dept_term($current_user->ID);
        if ($hod_term) {
            $dept_term_id = $hod_term->term_id;
        } else {
            $dept_term_id = isset($_POST['dept_term_id']) ? (int) $_POST['dept_term_id'] : 0;
        }
    } else {
        $dept_term_id = isset($_POST['dept_term_id']) ? (int) $_POST['dept_term_id'] : 0;
    }

    $category_slug = isset($_POST['research_type']) ? sanitize_key($_POST['research_type']) : 'journal-article';
    $journal       = isset($_POST['research_journal']) ? sanitize_text_field(wp_unslash($_POST['research_journal'])) : '';
    $issn_isbn     = isset($_POST['research_issn_isbn']) ? sanitize_text_field(wp_unslash($_POST['research_issn_isbn'])) : '';
    $volume_issue  = isset($_POST['research_volume_issue']) ? sanitize_text_field(wp_unslash($_POST['research_volume_issue'])) : '';
    $year          = isset($_POST['research_year']) ? sanitize_text_field(wp_unslash($_POST['research_year'])) : date('Y');
    $doi           = isset($_POST['research_doi']) ? esc_url_raw(wp_unslash($_POST['research_doi'])) : '';
    $pdf_url       = isset($_POST['research_pdf_url']) ? esc_url_raw(wp_unslash($_POST['research_pdf_url'])) : '';
    $indexing      = isset($_POST['research_indexing']) ? sanitize_text_field(wp_unslash($_POST['research_indexing'])) : 'UGC CARE / Peer-Reviewed';
    $impact_factor = isset($_POST['research_impact_factor']) ? sanitize_text_field(wp_unslash($_POST['research_impact_factor'])) : '';
    $grant_amount  = isset($_POST['research_grant_amount']) ? sanitize_text_field(wp_unslash($_POST['research_grant_amount'])) : '';
    $abstract      = isset($_POST['research_abstract']) ? wp_kses_post(wp_unslash($_POST['research_abstract'])) : '';

    // Insert Research Post
    $post_data = array(
        'post_title'   => $title,
        'post_content' => $abstract,
        'post_status'  => 'publish',
        'post_type'    => 'research',
        'post_author'  => $current_user->ID,
    );

    $post_id = wp_insert_post($post_data);
    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => $post_id->get_error_message()));
    }

    // Save Meta
    update_post_meta($post_id, 'research_authors', $authors);
    update_post_meta($post_id, 'research_journal', $journal);
    update_post_meta($post_id, 'research_issn_isbn', $issn_isbn);
    update_post_meta($post_id, 'research_volume_issue', $volume_issue);
    update_post_meta($post_id, 'research_year', $year);
    update_post_meta($post_id, 'research_doi', $doi);
    update_post_meta($post_id, 'research_pdf_url', $pdf_url);
    update_post_meta($post_id, 'research_indexing', $indexing);
    update_post_meta($post_id, 'research_impact_factor', $impact_factor);
    update_post_meta($post_id, 'research_grant_amount', $grant_amount);

    // Assign Taxonomies
    if ($dept_term_id > 0) {
        wp_set_object_terms($post_id, array((int) $dept_term_id), 'dept_link', false);
    }
    if (!empty($category_slug)) {
        wp_set_object_terms($post_id, array($category_slug), 'research_type', false);
    }

    // Flush Transients & Caches
    if (function_exists('mvc_flush_all_performance_caches')) {
        mvc_flush_all_performance_caches($post_id);
    }
    delete_transient('mvc_cached_research_page_html');

    $dept_name = 'Multidisciplinary';
    if ($dept_term_id > 0) {
        $dt = get_term($dept_term_id, 'dept_link');
        if ($dt && !is_wp_error($dt)) {
            $dept_name = $dt->name;
        }
    }

    wp_send_json_success(array(
        'message'   => __('Research publication successfully published and live on the institutional portal!', 'mvc-college'),
        'post_id'   => $post_id,
        'title'     => $title,
        'authors'   => $authors,
        'dept'      => $dept_name,
        'year'      => $year,
        'type'      => $category_slug,
    ));
}
add_action('wp_ajax_mvc_ajax_publish_research', 'mvc_handle_ajax_publish_research');

/**
 * 4. Helper: Get Human-Readable Research Category Meta
 */
function mvc_get_research_type_meta($slug = '') {
    $types = array(
        'journal-article' => array(
            'label'   => 'UGC-CARE / Scopus Journal',
            'badge'   => 'Journal Article',
            'color'   => '#0284c7',
            'bg'      => '#e0f2fe',
            'icon'    => '📖',
        ),
        'conference-paper' => array(
            'label'   => 'Conference Proceeding',
            'badge'   => 'Conference Paper',
            'color'   => '#7c3aed',
            'bg'      => '#f3e8ff',
            'icon'    => '🎤',
        ),
        'book-chapter' => array(
            'label'   => 'Book / Scholarly Monograph',
            'badge'   => 'Book &amp; Chapter',
            'color'   => '#b45309',
            'bg'      => '#fef3c7',
            'icon'    => '📚',
        ),
        'funded-project' => array(
            'label'   => 'Funded Research Grant (UGC/ICSSR/CSIR)',
            'badge'   => 'Funded Project',
            'color'   => '#059669',
            'bg'      => '#d1fae5',
            'icon'    => '💰',
        ),
        'phd-dissertation' => array(
            'label'   => 'Doctoral Thesis &amp; Guidance',
            'badge'   => 'Ph.D. Dissertation',
            'color'   => '#dc2626',
            'bg'      => '#fee2e2',
            'icon'    => '🎓',
        ),
        'patent-innovation' => array(
            'label'   => 'Patent &amp; IPR Innovation',
            'badge'   => 'Patent / IPR',
            'color'   => '#0f766e',
            'bg'      => '#ccfbf1',
            'icon'    => '💡',
        ),
        'policy-brief' => array(
            'label'   => 'Institutional Policy Brief',
            'badge'   => 'Policy Brief',
            'color'   => '#475569',
            'bg'      => '#f1f5f9',
            'icon'    => '📑',
        ),
    );

    if (isset($types[$slug])) {
        return $types[$slug];
    }

    return array(
        'label'   => 'Research Publication',
        'badge'   => 'Research Work',
        'color'   => '#002147',
        'bg'      => '#e2e8f0',
        'icon'    => '🔬',
    );
}

/**
 * 5. Main Research & Publications Page Renderer (/research/)
 */
if (!function_exists('mvc_render_research_page')) {
function mvc_render_research_page() {
    $current_user = wp_get_current_user();
    $can_publish  = is_user_logged_in() && mvc_user_can_publish_research($current_user);
    $user_roles   = (array) $current_user->roles;
    $is_admin     = in_array('administrator', $user_roles, true) || current_user_can('manage_options');
    $is_cm        = in_array('content_manager', $user_roles, true);
    $is_hod       = in_array('department_head', $user_roles, true);

    // HOD Assigned Dept
    $assigned_dept_term = null;
    if ($is_hod) {
        $assigned_dept_term = mvc_get_user_assigned_dept_term($current_user->ID);
    }

    // Role Label for Banner
    $role_label = 'Academic Researcher';
    if ($is_admin) {
        $role_label = 'Principal / Institutional Administrator';
    } elseif ($is_cm) {
        $role_label = 'Editorial Content Manager';
    } elseif ($is_hod) {
        $dept_name = $assigned_dept_term ? $assigned_dept_term->name : 'Collegiate Department';
        $role_label = 'Head of Department (HOD) — ' . $dept_name;
    }

    // All Departments for Filter Dropdown
    $all_depts = get_terms(array(
        'taxonomy'   => 'dept_link',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));

    // Fetch all published research papers
    $researches = get_posts(array(
        'post_type'      => 'research',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    $total_researches = count($researches);
    $journal_count = 0;
    $scopus_count = 0;
    $depts_active_map = array();

    foreach ($researches as $r) {
        $t_terms = wp_get_object_terms($r->ID, 'research_type');
        $t_slug = (!empty($t_terms) && !is_wp_error($t_terms)) ? $t_terms[0]->slug : 'journal-article';
        if ($t_slug === 'journal-article') {
            $journal_count++;
        }
        $idx = get_post_meta($r->ID, 'research_indexing', true);
        if (stripos($idx, 'scopus') !== false || stripos($idx, 'care') !== false || stripos($idx, 'web of science') !== false) {
            $scopus_count++;
        }
        $d_terms = wp_get_object_terms($r->ID, 'dept_link');
        if (!empty($d_terms) && !is_wp_error($d_terms)) {
            $depts_active_map[$d_terms[0]->term_id] = true;
        }
    }

    $active_depts_count = count($depts_active_map);
    $scopus_pct = $total_researches > 0 ? round(($scopus_count / $total_researches) * 100) . '%' : 'UGC CARE';

    $ajax_nonce = wp_create_nonce('mvc_publish_research_nonce');
    ?>

    <div class="mvc-research-suite" id="mvcResearchSuite">
        <style>
            /* ========================================================
               RESEARCH & PUBLICATIONS COMPREHENSIVE DESIGN SUITE
               ======================================================== */
            :root {
                --mvc-res-navy: #002147;
                --mvc-res-navy-dark: #001229;
                --mvc-res-navy-light: #0d3868;
                --mvc-res-gold: #D4AF37;
                --mvc-res-gold-light: #fef08a;
                --mvc-res-gold-accent: #b45309;
                --mvc-res-maroon: #800000;
                --mvc-res-slate-50: #f8fafc;
                --mvc-res-slate-100: #f1f5f9;
                --mvc-res-slate-200: #e2e8f0;
                --mvc-res-slate-300: #cbd5e1;
                --mvc-res-slate-600: #475569;
                --mvc-res-slate-700: #334155;
                --mvc-res-slate-800: #1e293b;
                --mvc-res-slate-900: #0f172a;
            }

            .mvc-research-suite {
                font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                color: var(--mvc-res-slate-800);
                background: #f8fafc;
                line-height: 1.6;
                padding-bottom: 70px;
            }

            .mvc-research-suite *, .mvc-research-suite *::before, .mvc-research-suite *::after {
                box-sizing: border-box;
            }

            /* 1. HERO SECTION */
            .mvc-res-hero {
                position: relative;
                background: linear-gradient(135deg, #001229 0%, #002147 60%, #052c5c 100%);
                color: #ffffff;
                padding: 55px 24px 60px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 18, 41, 0.25);
            }

            .mvc-res-hero::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: 
                    radial-gradient(rgba(212, 175, 55, 0.12) 1px, transparent 1px),
                    radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                background-size: 32px 32px, 16px 16px;
                background-position: 0 0, 16px 16px;
                opacity: 0.6;
                pointer-events: none;
            }

            .mvc-res-hero-inner {
                max-width: 1240px;
                margin: 0 auto;
                position: relative;
                z-index: 2;
            }

            .mvc-res-breadcrumb {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: #94a3b8;
                margin-bottom: 24px;
                flex-wrap: wrap;
            }

            .mvc-res-breadcrumb a {
                color: #cbd5e1;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .mvc-res-breadcrumb a:hover {
                color: var(--mvc-res-gold);
            }

            .mvc-res-breadcrumb .sep {
                color: #64748b;
                font-size: 11px;
            }

            .mvc-res-breadcrumb .current {
                color: var(--mvc-res-gold);
                font-weight: 700;
            }

            .mvc-res-hero-badges {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 18px;
            }

            .mvc-res-pill-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 12px;
                border-radius: 9999px;
                font-size: 11.5px;
                font-weight: 700;
                letter-spacing: 0.3px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.18);
                color: #e2e8f0;
                backdrop-filter: blur(6px);
            }

            .mvc-res-pill-badge.gold {
                background: rgba(212, 175, 55, 0.18);
                border-color: rgba(212, 175, 55, 0.45);
                color: #fef08a;
            }

            .mvc-res-hero-title-cluster {
                margin-bottom: 24px;
            }

            .mvc-res-hindi-kicker {
                font-family: 'Noto Sans Devanagari', sans-serif;
                font-size: 19px;
                font-weight: 700;
                color: var(--mvc-res-gold);
                margin-bottom: 6px;
                letter-spacing: 0.5px;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }

            .mvc-res-main-title {
                font-size: 38px;
                font-weight: 800;
                color: #ffffff;
                line-height: 1.2;
                margin: 0 0 14px;
                letter-spacing: -0.5px;
            }

            .mvc-res-hero-desc {
                font-size: 16px;
                color: #cbd5e1;
                max-width: 880px;
                line-height: 1.7;
                margin: 0;
            }

            /* 2. EXECUTIVE AUTHORIZED PUBLISHING BAR */
            .mvc-res-auth-strip {
                background: #0f172a;
                border-bottom: 2px solid var(--mvc-res-gold);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            }

            .mvc-res-auth-inner {
                max-width: 1240px;
                margin: 0 auto;
                padding: 16px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 16px;
            }

            .mvc-res-auth-left {
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .mvc-res-auth-avatar {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--mvc-res-gold) 0%, #b45309 100%);
                color: #001229;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 16px;
                box-shadow: 0 0 12px rgba(212, 175, 55, 0.4);
            }

            .mvc-res-auth-info {
                display: flex;
                flex-direction: column;
            }

            .mvc-res-auth-name {
                font-size: 14.5px;
                font-weight: 700;
                color: #ffffff;
            }

            .mvc-res-auth-role {
                font-size: 12px;
                color: var(--mvc-res-gold);
                font-weight: 600;
            }

            .mvc-res-auth-actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .mvc-btn-publish-trigger {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: linear-gradient(135deg, #d4af37 0%, #b8860b 100%);
                color: #001229;
                font-weight: 800;
                font-size: 13.5px;
                padding: 9px 20px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
                box-shadow: 0 4px 14px rgba(212, 175, 55, 0.35);
            }

            .mvc-btn-publish-trigger:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 18px rgba(212, 175, 55, 0.5);
            }

            .mvc-btn-admin-desk {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(255, 255, 255, 0.1);
                color: #e2e8f0;
                font-weight: 600;
                font-size: 13px;
                padding: 9px 16px;
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                text-decoration: none;
                transition: background 0.2s ease, border-color 0.2s ease;
            }

            .mvc-btn-admin-desk:hover {
                background: rgba(255, 255, 255, 0.18);
                color: #ffffff;
                border-color: rgba(255, 255, 255, 0.4);
            }

            /* 3. KPI METRICS COUNTERS */
            .mvc-res-kpi-container {
                max-width: 1240px;
                margin: 24px auto 30px;
                padding: 0 24px;
                position: relative;
                z-index: 5;
            }

            .mvc-res-kpi-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
            }

            .mvc-res-kpi-card {
                background: #ffffff;
                border: 1px solid var(--mvc-res-slate-200);
                border-radius: 12px;
                padding: 20px 22px;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
                display: flex;
                align-items: center;
                gap: 16px;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .mvc-res-kpi-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 22px rgba(0, 33, 71, 0.09);
                border-color: var(--mvc-res-gold);
            }

            .mvc-res-kpi-icon-box {
                width: 48px;
                height: 48px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                flex-shrink: 0;
            }

            .mvc-res-kpi-blue { background: #e0f2fe; color: #0284c7; }
            .mvc-res-kpi-gold { background: #fef3c7; color: #b45309; }
            .mvc-res-kpi-purple { background: #f3e8ff; color: #7c3aed; }
            .mvc-res-kpi-green { background: #d1fae5; color: #059669; }

            .mvc-res-kpi-content {
                display: flex;
                flex-direction: column;
            }

            .mvc-res-kpi-number {
                font-size: 26px;
                font-weight: 800;
                color: var(--mvc-res-navy);
                line-height: 1.1;
            }

            .mvc-res-kpi-label {
                font-size: 12.5px;
                color: var(--mvc-res-slate-600);
                font-weight: 600;
                margin-top: 3px;
            }

            /* 4. MAIN CONTAINER & CONTROLS */
            .mvc-res-main-container {
                max-width: 1240px;
                margin: 0 auto;
                padding: 0 24px;
            }

            .mvc-res-controls-panel {
                background: #ffffff;
                border: 1px solid var(--mvc-res-slate-200);
                border-radius: 14px;
                padding: 22px 24px;
                margin-bottom: 28px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            }

            .mvc-res-search-row {
                display: flex;
                gap: 14px;
                margin-bottom: 18px;
                flex-wrap: wrap;
                align-items: stretch;
            }

            .mvc-res-search-box {
                flex: 1;
                min-width: 280px;
                position: relative;
                display: flex;
                align-items: center;
            }

            .mvc-res-search-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #64748b;
                font-size: 16px;
                pointer-events: none;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            }

            .mvc-res-search-input {
                width: 100% !important;
                height: 46px !important;
                min-height: 46px !important;
                line-height: 46px !important;
                padding: 0 14px 0 42px !important;
                border: 1.5px solid var(--mvc-res-slate-300) !important;
                border-radius: 8px !important;
                font-size: 14px !important;
                color: var(--mvc-res-slate-800) !important;
                background: #ffffff !important;
                outline: none !important;
                box-sizing: border-box !important;
                margin: 0 !important;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .mvc-res-search-input:focus {
                border-color: var(--mvc-res-navy) !important;
                box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1) !important;
            }

            .mvc-res-filter-dropdowns {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
                align-items: center;
            }

            .mvc-res-select {
                height: 46px !important;
                min-height: 46px !important;
                padding: 0 34px 0 14px !important;
                margin: 0 !important;
                box-sizing: border-box !important;
                border: 1.5px solid var(--mvc-res-slate-300) !important;
                border-radius: 8px !important;
                font-size: 13.5px !important;
                font-weight: 600 !important;
                color: var(--mvc-res-slate-700) !important;
                background: #ffffff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23475569'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 10px center !important;
                background-size: 14px !important;
                appearance: none !important;
                -webkit-appearance: none !important;
                outline: none !important;
                cursor: pointer !important;
            }

            .mvc-res-select:focus {
                border-color: var(--mvc-res-navy) !important;
                box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1) !important;
            }

            /* Category Filter Tabs */
            .mvc-res-type-tabs {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                padding-top: 14px;
                border-top: 1px solid var(--mvc-res-slate-200);
            }

            .mvc-res-tab-btn {
                background: #f8fafc;
                border: 1px solid var(--mvc-res-slate-300);
                color: var(--mvc-res-slate-700);
                padding: 7px 14px;
                border-radius: 6px;
                font-size: 12.5px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.18s ease;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .mvc-res-tab-btn:hover {
                background: #f1f5f9;
                color: var(--mvc-res-navy);
                border-color: #94a3b8;
            }

            .mvc-res-tab-btn.active {
                background: var(--mvc-res-navy);
                color: #ffffff;
                border-color: var(--mvc-res-navy);
                box-shadow: 0 2px 8px rgba(0, 33, 71, 0.25);
            }

            .mvc-res-tab-btn .count-badge {
                font-size: 10px;
                padding: 1px 6px;
                border-radius: 999px;
                background: rgba(0, 0, 0, 0.08);
                font-weight: 800;
            }

            .mvc-res-tab-btn.active .count-badge {
                background: var(--mvc-res-gold);
                color: var(--mvc-res-navy);
            }

            /* View Switcher & Counter */
            .mvc-res-meta-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
                padding: 0 4px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .mvc-res-count-statement {
                font-size: 14px;
                font-weight: 600;
                color: var(--mvc-res-slate-600);
            }

            .mvc-res-count-statement strong {
                color: var(--mvc-res-navy);
            }

            .mvc-res-view-toggle {
                display: flex;
                align-items: center;
                background: #e2e8f0;
                padding: 3px;
                border-radius: 8px;
            }

            .mvc-res-toggle-btn {
                background: transparent;
                border: none;
                padding: 5px 12px;
                font-size: 12px;
                font-weight: 700;
                color: var(--mvc-res-slate-600);
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.15s ease;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .mvc-res-toggle-btn.active {
                background: #ffffff;
                color: var(--mvc-res-navy);
                box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            }

            /* 5. PUBLICATION CARDS GRID VIEW */
            .mvc-res-cards-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
                gap: 22px;
            }

            .mvc-res-card {
                background: #ffffff;
                border: 1px solid var(--mvc-res-slate-200);
                border-radius: 14px;
                padding: 24px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
                position: relative;
            }

            .mvc-res-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 24px rgba(0, 33, 71, 0.08);
                border-color: #94a3b8;
            }

            .mvc-res-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                margin-bottom: 12px;
                flex-wrap: wrap;
            }

            .mvc-res-type-pill {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                font-size: 11px;
                font-weight: 800;
                padding: 3px 9px;
                border-radius: 4px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .mvc-res-dept-pill {
                font-size: 11.5px;
                font-weight: 700;
                color: var(--mvc-res-slate-600);
                background: var(--mvc-res-slate-100);
                padding: 3px 8px;
                border-radius: 4px;
                border: 1px solid var(--mvc-res-slate-200);
            }

            .mvc-res-card-title {
                font-size: 17px;
                font-weight: 800;
                color: var(--mvc-res-navy);
                line-height: 1.4;
                margin: 0 0 10px;
            }

            .mvc-res-card-authors {
                font-size: 13px;
                font-weight: 600;
                color: var(--mvc-res-slate-700);
                margin-bottom: 8px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .mvc-res-card-citation {
                font-size: 12.5px;
                color: var(--mvc-res-slate-600);
                line-height: 1.5;
                margin-bottom: 14px;
                background: #f8fafc;
                padding: 8px 12px;
                border-radius: 6px;
                border-left: 3px solid var(--mvc-res-gold);
            }

            .mvc-res-card-badges {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                margin-bottom: 14px;
            }

            .mvc-res-index-badge {
                font-size: 10.5px;
                font-weight: 700;
                padding: 2px 7px;
                border-radius: 4px;
                background: #ecfdf5;
                color: #047857;
                border: 1px solid #a7f3d0;
            }

            .mvc-res-if-badge {
                font-size: 10.5px;
                font-weight: 700;
                padding: 2px 7px;
                border-radius: 4px;
                background: #fef3c7;
                color: #92400e;
                border: 1px solid #fde68a;
            }

            .mvc-res-card-abstract-box {
                margin-bottom: 16px;
            }

            .mvc-res-abstract-toggle {
                font-size: 12px;
                font-weight: 700;
                color: var(--mvc-res-navy);
                background: transparent;
                border: none;
                padding: 0;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .mvc-res-abstract-toggle:hover {
                text-decoration: underline;
                color: var(--mvc-res-gold-accent);
            }

            .mvc-res-abstract-content {
                display: none;
                margin-top: 8px;
                font-size: 12.5px;
                color: var(--mvc-res-slate-600);
                line-height: 1.6;
                padding: 10px 12px;
                background: #f1f5f9;
                border-radius: 6px;
            }

            .mvc-res-card-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-top: 14px;
                border-top: 1px solid var(--mvc-res-slate-200);
                gap: 10px;
                flex-wrap: wrap;
            }

            .mvc-res-action-btn {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                font-size: 12px;
                font-weight: 700;
                padding: 6px 12px;
                border-radius: 6px;
                text-decoration: none;
                transition: all 0.15s ease;
            }

            .mvc-res-btn-doi {
                background: var(--mvc-res-navy);
                color: #ffffff;
            }

            .mvc-res-btn-doi:hover {
                background: var(--mvc-res-navy-light);
                color: #ffffff;
            }

            .mvc-res-btn-pdf {
                background: #fee2e2;
                color: #b91c1c;
                border: 1px solid #fca5a5;
            }

            .mvc-res-btn-pdf:hover {
                background: #fecaca;
                color: #991b1b;
            }

            .mvc-res-btn-cite {
                background: var(--mvc-res-slate-100);
                color: var(--mvc-res-slate-700);
                border: 1px solid var(--mvc-res-slate-300);
                cursor: pointer;
            }

            .mvc-res-btn-cite:hover {
                background: #e2e8f0;
                color: var(--mvc-res-navy);
            }

            /* 6. BIBLIOGRAPHY / LIST VIEW */
            .mvc-res-bib-list {
                display: none;
                flex-direction: column;
                gap: 14px;
            }

            .mvc-res-bib-item {
                background: #ffffff;
                border: 1px solid var(--mvc-res-slate-200);
                border-radius: 10px;
                padding: 16px 20px;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            }

            .mvc-res-bib-citation-text {
                font-size: 13.5px;
                line-height: 1.6;
                color: var(--mvc-res-slate-800);
            }

            .mvc-res-bib-citation-text strong {
                color: var(--mvc-res-navy);
            }

            /* 7. DIGITAL RESEARCH CONSORTIA WINGS (ONOS & INFLIBNET) */
            .mvc-res-consortia-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
                gap: 20px;
                margin-top: 50px;
            }

            .mvc-res-consortia-card {
                border-radius: 14px;
                padding: 26px;
                position: relative;
                overflow: hidden;
            }

            .mvc-res-card-onos {
                background: linear-gradient(135deg, #001229 0%, #002147 100%);
                color: #ffffff;
                border: 1.5px solid var(--mvc-res-gold);
            }

            .mvc-res-card-inflibnet {
                background: #ffffff;
                color: var(--mvc-res-slate-800);
                border: 1.5px solid var(--mvc-res-slate-300);
            }

            .mvc-res-consortia-kicker {
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 6px;
            }

            .mvc-res-card-onos .mvc-res-consortia-kicker { color: var(--mvc-res-gold-light); }
            .mvc-res-card-inflibnet .mvc-res-consortia-kicker { color: var(--mvc-res-navy); }

            .mvc-res-consortia-title {
                font-size: 20px;
                font-weight: 800;
                margin: 0 0 10px;
            }

            .mvc-res-consortia-desc {
                font-size: 13.5px;
                line-height: 1.6;
                margin-bottom: 18px;
            }

            .mvc-res-card-onos .mvc-res-consortia-desc { color: #cbd5e1; }
            .mvc-res-card-inflibnet .mvc-res-consortia-desc { color: var(--mvc-res-slate-600); }

            .mvc-res-consortia-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 18px;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 800;
                text-decoration: none;
            }

            .mvc-res-card-onos .mvc-res-consortia-btn {
                background: var(--mvc-res-gold);
                color: #001229;
            }

            .mvc-res-card-inflibnet .mvc-res-consortia-btn {
                background: var(--mvc-res-navy);
                color: #ffffff;
            }

            /* 8. RESEARCH ETHICS & PLAGIARISM PROTOCOL */
            .mvc-res-policy-banner {
                background: #ffffff;
                border: 1px solid var(--mvc-res-slate-200);
                border-radius: 14px;
                padding: 26px;
                margin-top: 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
            }

            .mvc-res-policy-content {
                max-width: 820px;
            }

            .mvc-res-policy-title {
                font-size: 18px;
                font-weight: 800;
                color: var(--mvc-res-navy);
                margin: 0 0 6px;
            }

            .mvc-res-policy-desc {
                font-size: 13.5px;
                color: var(--mvc-res-slate-600);
                line-height: 1.6;
                margin: 0;
            }

            /* 9. PUBLISH RESEARCH MODAL */
            .mvc-res-modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 18, 41, 0.7);
                backdrop-filter: blur(4px);
                z-index: 99999;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .mvc-res-modal-dialog {
                background: #ffffff;
                width: 100%;
                max-width: 760px;
                border-radius: 16px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                display: flex;
                flex-direction: column;
                max-height: 90vh;
                animation: mvcModalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            }

            @keyframes mvcModalPop {
                from { transform: scale(0.96); opacity: 0; }
                to { transform: scale(1); opacity: 1; }
            }

            .mvc-res-modal-header {
                background: linear-gradient(135deg, #001229 0%, #002147 100%);
                color: #ffffff;
                padding: 20px 26px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-bottom: 2px solid var(--mvc-res-gold);
            }

            .mvc-res-modal-header h3 {
                margin: 0;
                font-size: 18px;
                font-weight: 800;
                color: #ffffff;
            }

            .mvc-res-modal-header p {
                margin: 2px 0 0;
                font-size: 12px;
                color: #cbd5e1;
            }

            .mvc-res-modal-close {
                background: rgba(255, 255, 255, 0.15);
                border: none;
                color: #ffffff;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                font-size: 16px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.2s;
            }

            .mvc-res-modal-close:hover {
                background: rgba(255, 255, 255, 0.3);
            }

            .mvc-res-modal-body {
                padding: 24px 26px;
                overflow-y: auto;
            }

            .mvc-res-form-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .mvc-res-form-full {
                grid-column: 1 / -1;
            }

            .mvc-res-form-group {
                display: flex;
                flex-direction: column;
            }

            .mvc-res-label {
                font-size: 12.5px;
                font-weight: 700;
                color: var(--mvc-res-slate-700);
                margin-bottom: 6px;
            }

            .mvc-res-label .req {
                color: #dc2626;
            }

            .mvc-res-input, .mvc-res-textarea, .mvc-res-form-select {
                padding: 10px 12px;
                border: 1.5px solid var(--mvc-res-slate-300);
                border-radius: 8px;
                font-size: 13.5px;
                color: var(--mvc-res-slate-800);
                background: #ffffff;
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .mvc-res-input:focus, .mvc-res-textarea:focus, .mvc-res-form-select:focus {
                border-color: var(--mvc-res-navy);
                box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
            }

            .mvc-res-modal-footer {
                padding: 16px 26px;
                background: #f8fafc;
                border-top: 1px solid var(--mvc-res-slate-200);
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 12px;
            }

            .mvc-btn-modal-cancel {
                background: #ffffff;
                border: 1px solid var(--mvc-res-slate-300);
                color: var(--mvc-res-slate-700);
                padding: 9px 18px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
            }

            .mvc-btn-modal-submit {
                background: linear-gradient(135deg, #002147 0%, #001229 100%);
                border: 1px solid var(--mvc-res-gold);
                color: var(--mvc-res-gold-light);
                padding: 9px 22px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 800;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .mvc-res-alert-box {
                display: none;
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 13.5px;
                font-weight: 600;
                margin-bottom: 16px;
            }

            .mvc-res-alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
            .mvc-res-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

            /* Enhanced Zero-Data & Filter Empty State */
            .mvc-res-empty-state {
                text-align: center;
                padding: 56px 28px;
                background: #ffffff;
                border: 2px dashed rgba(212, 175, 55, 0.45);
                border-radius: 18px;
                box-shadow: 0 10px 30px rgba(0, 33, 71, 0.04);
                max-width: 900px;
                margin: 0 auto 35px;
            }

            .mvc-res-empty-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 5px 14px;
                background: rgba(212, 175, 55, 0.12);
                border: 1px solid rgba(212, 175, 55, 0.35);
                border-radius: 20px;
                font-size: 12px;
                font-weight: 700;
                color: #854d0e;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                margin-bottom: 20px;
            }

            .mvc-res-empty-badge-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: #D4AF37;
                display: inline-block;
            }

            .mvc-res-empty-icon-wrap {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                background: linear-gradient(135deg, rgba(0, 33, 71, 0.06), rgba(212, 175, 55, 0.15));
                border: 1.5px solid rgba(212, 175, 55, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 16px;
                font-size: 34px;
            }

            .mvc-res-empty-title {
                font-size: 22px;
                font-weight: 800;
                color: var(--mvc-res-navy);
                margin: 0 0 10px;
                letter-spacing: -0.3px;
            }

            .mvc-res-empty-desc {
                font-size: 15px;
                color: var(--mvc-res-slate-600);
                line-height: 1.6;
                max-width: 680px;
                margin: 0 auto 24px;
            }

            .mvc-res-empty-actions {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 14px;
                flex-wrap: wrap;
                margin-bottom: 32px;
            }

            .mvc-res-empty-admin-link {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                padding: 10px 18px;
                border-radius: 8px;
                background: #f1f5f9;
                color: #0f172a !important;
                font-size: 13.5px;
                font-weight: 700;
                text-decoration: none !important;
                border: 1px solid #cbd5e1;
                transition: all 0.2s ease;
            }

            .mvc-res-empty-admin-link:hover {
                background: #e2e8f0;
                border-color: #94a3b8;
                transform: translateY(-2px);
            }

            .mvc-res-empty-guide {
                border-top: 1px solid var(--mvc-res-slate-200);
                padding-top: 26px;
                margin-top: 8px;
                text-align: left;
            }

            .mvc-res-guide-heading {
                font-size: 13.5px;
                font-weight: 800;
                color: var(--mvc-res-navy);
                text-align: center;
                margin: 0 0 18px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .mvc-res-guide-steps {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 16px;
            }

            .mvc-res-guide-step {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                background: #f8fafc;
                border: 1px solid var(--mvc-res-slate-200);
                border-radius: 10px;
                padding: 14px 16px;
            }

            .mvc-res-step-badge {
                width: 26px;
                height: 26px;
                border-radius: 50%;
                background: var(--mvc-res-navy);
                color: var(--mvc-res-gold);
                font-size: 12px;
                font-weight: 800;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .mvc-res-step-body strong {
                display: block;
                font-size: 13px;
                color: var(--mvc-res-navy);
                margin-bottom: 3px;
            }

            .mvc-res-step-body p {
                font-size: 12px;
                color: var(--mvc-res-slate-600);
                margin: 0;
                line-height: 1.45;
            }

            @media (max-width: 768px) {
                .mvc-res-guide-steps {
                    grid-template-columns: 1fr;
                }
            }

            /* Responsive Adjustments */
            @media (max-width: 992px) {
                .mvc-res-kpi-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .mvc-res-main-title {
                    font-size: 30px;
                }
            }

            @media (max-width: 640px) {
                .mvc-res-kpi-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-res-cards-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-res-form-grid {
                    grid-template-columns: 1fr;
                }
                .mvc-res-hero {
                    padding: 40px 16px;
                }
                .mvc-res-main-title {
                    font-size: 24px;
                }
                .mvc-res-hindi-kicker {
                    font-size: 16px;
                }
                .mvc-res-main-container, .mvc-res-kpi-container {
                    padding: 0 16px;
                }
                .mvc-res-filter-dropdowns {
                    width: 100%;
                }
                .mvc-res-select {
                    width: 100%;
                }
            }
        </style>

        <!-- 1. HERO SECTION -->
        <header class="mvc-res-hero">
            <div class="mvc-res-hero-inner">
                <nav class="mvc-res-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="sep">/</span>
                    <a href="<?php echo esc_url(home_url('/department-details/')); ?>">Academics</a>
                    <span class="sep">/</span>
                    <span class="current">Research &amp; Publications</span>
                </nav>

                <div class="mvc-res-hero-badges">
                    <span class="mvc-res-pill-badge gold">🏛 UGC 2(f) &amp; 12(B) Recognized</span>
                    <span class="mvc-res-pill-badge">📜 NEP-2020 Multidisciplinary Framework</span>
                    <span class="mvc-res-pill-badge">🌐 ONOS &amp; INFLIBNET N-LIST Access</span>
                    <span class="mvc-res-pill-badge">🔬 Central Research &amp; Ethics Cell</span>
                </div>

                <div class="mvc-res-hero-title-cluster">
                    <div class="mvc-res-hindi-kicker">शोध, नवाचार एवं संकाय प्रकाशन प्रकोष्ठ</div>
                    <h1 class="mvc-res-main-title">Research, Innovation &amp; Scholarly Publications</h1>
                    <!-- <p class="mvc-res-hero-desc">
                        Advancing collegiate academic distinction, empirical research inquiry, and peer-reviewed scholarly publications across Humanities, Social Sciences, Pure Sciences, and Vocational Disciplines at Maharshi Vishwamitra Mahavidyalaya, Buxar (Veer Kunwar Singh University).
                    </p> -->
                </div>
            </div>
        </header>

        <!-- 2. EXECUTIVE AUTHORIZED PUBLISHING STRIP -->
        <?php if ($can_publish): ?>
            <section class="mvc-res-auth-strip" aria-label="Authorized Scholar Desk">
                <div class="mvc-res-auth-inner">
                    <div class="mvc-res-auth-left">
                        <div class="mvc-res-auth-avatar">
                            <?php 
                            $uname = $current_user->display_name ?: $current_user->user_login;
                            echo esc_html(strtoupper(substr($uname, 0, 1)));
                            ?>
                        </div>
                        <div class="mvc-res-auth-info">
                            <span class="mvc-res-auth-name">
                                Welcome, <?php echo esc_html($uname); ?>
                            </span>
                            <span class="mvc-res-auth-role">
                                <?php echo esc_html($role_label); ?> &bull; Authorized Publishing Desk
                            </span>
                        </div>
                    </div>
                    <div class="mvc-res-auth-actions">
                        <button type="button" class="mvc-btn-publish-trigger" onclick="mvcOpenPublishModal();">
                            <span>➕</span> Publish New Research Paper
                        </button>
                        <a href="<?php echo esc_url(admin_url('index.php')); ?>" class="mvc-btn-admin-desk" target="_blank" rel="noopener">
                            <span>🏛</span> Command Center ↗
                        </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- 3. KPI METRICS COUNTER BAR -->
        <div class="mvc-res-kpi-container">
            <div class="mvc-res-kpi-grid">
                <div class="mvc-res-kpi-card">
                    <div class="mvc-res-kpi-icon-box mvc-res-kpi-blue">📖</div>
                    <div class="mvc-res-kpi-content">
                        <span class="mvc-res-kpi-number" id="mvcKpiTotalCount"><?php echo (int) $total_researches; ?></span>
                        <span class="mvc-res-kpi-label">Scholarly Works Published</span>
                    </div>
                </div>

                <div class="mvc-res-kpi-card">
                    <div class="mvc-res-kpi-icon-box mvc-res-kpi-gold">🏆</div>
                    <div class="mvc-res-kpi-content">
                        <span class="mvc-res-kpi-number"><?php echo $total_researches > 0 ? esc_html($scopus_pct) : 'UGC CARE'; ?></span>
                        <span class="mvc-res-kpi-label"><?php echo $total_researches > 0 ? 'Scopus / CARE Indexed' : 'Quality Benchmark'; ?></span>
                    </div>
                </div>

                <div class="mvc-res-kpi-card">
                    <div class="mvc-res-kpi-icon-box mvc-res-kpi-purple">🏛</div>
                    <div class="mvc-res-kpi-content">
                        <span class="mvc-res-kpi-number"><?php echo $total_researches > 0 ? (int) $active_depts_count : '16'; ?></span>
                        <span class="mvc-res-kpi-label"><?php echo $total_researches > 0 ? 'Contributing Departments' : 'Academic Faculties'; ?></span>
                    </div>
                </div>

                <div class="mvc-res-kpi-card">
                    <div class="mvc-res-kpi-icon-box mvc-res-kpi-green">🌐</div>
                    <div class="mvc-res-kpi-content">
                        <span class="mvc-res-kpi-number">6,000+</span>
                        <span class="mvc-res-kpi-label">e-Journals via ONOS/N-LIST</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. CONTROLS, SEARCH, AND FILTERS -->
        <main class="mvc-res-main-container">
            <div class="mvc-res-controls-panel">
                <div class="mvc-res-search-row">
                    <div class="mvc-res-search-box">
                        <span class="mvc-res-search-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </span>
                        <input type="text" id="mvcResSearchInput" class="mvc-res-search-input" placeholder="Search by paper title, author name, journal, ISSN, or keywords..." oninput="mvcFilterResearch();" aria-label="Search research publications">
                    </div>

                    <div class="mvc-res-filter-dropdowns">
                        <!-- Department Dropdown -->
                        <select id="mvcResDeptSelect" class="mvc-res-select" onchange="mvcFilterResearch();" aria-label="Filter by department">
                            <option value="all">All Departments (16)</option>
                            <?php foreach ($all_depts as $dept): ?>
                                <option value="<?php echo esc_attr($dept->slug); ?>">
                                    <?php echo esc_html($dept->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Year Dropdown -->
                        <select id="mvcResYearSelect" class="mvc-res-select" onchange="mvcFilterResearch();" aria-label="Filter by publication year">
                            <option value="all">All Years</option>
                            <option value="2026">2026 (Current Academic Year)</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="prior">2022 &amp; Earlier</option>
                        </select>
                    </div>
                </div>

                <!-- Category Tabs -->
                <div class="mvc-res-type-tabs" role="tablist" aria-label="Filter by research category">
                    <button type="button" class="mvc-res-tab-btn active" data-type="all" onclick="mvcSelectTypeFilter('all', this);">
                        All Categories <span class="count-badge"><?php echo (int) $total_researches; ?></span>
                    </button>
                    <button type="button" class="mvc-res-tab-btn" data-type="journal-article" onclick="mvcSelectTypeFilter('journal-article', this);">
                        📖 UGC-CARE / Scopus Journals
                    </button>
                    <button type="button" class="mvc-res-tab-btn" data-type="conference-paper" onclick="mvcSelectTypeFilter('conference-paper', this);">
                        🎤 Conference Proceedings
                    </button>
                    <button type="button" class="mvc-res-tab-btn" data-type="book-chapter" onclick="mvcSelectTypeFilter('book-chapter', this);">
                        📚 Books &amp; Chapters
                    </button>
                    <button type="button" class="mvc-res-tab-btn" data-type="funded-project" onclick="mvcSelectTypeFilter('funded-project', this);">
                        💰 Funded Projects
                    </button>
                    <button type="button" class="mvc-res-tab-btn" data-type="phd-dissertation" onclick="mvcSelectTypeFilter('phd-dissertation', this);">
                        🎓 Ph.D. Dissertations &amp; Patents
                    </button>
                </div>
            </div>

            <!-- Meta bar: count & view toggle -->
            <div class="mvc-res-meta-bar">
                <div class="mvc-res-count-statement" id="mvcResCountStatement">
                    Showing <strong id="mvcResVisibleCount"><?php echo (int) $total_researches; ?></strong> scholarly records
                </div>

                <div class="mvc-res-view-toggle">
                    <button type="button" class="mvc-res-toggle-btn active" id="mvcBtnCardView" onclick="mvcSetViewMode('grid');">
                        <span>▦</span> Card Grid
                    </button>
                    <button type="button" class="mvc-res-toggle-btn" id="mvcBtnBibView" onclick="mvcSetViewMode('bib');">
                        <span>☰</span> APA Bibliography
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div class="mvc-res-empty-state" id="mvcResEmptyState" style="<?php echo $total_researches === 0 ? 'display: block;' : 'display: none;'; ?>">
                <div class="mvc-res-empty-badge">
                    <span class="mvc-res-empty-badge-dot"></span>
                    <span>Institutional Scholarly Archive</span>
                </div>

                <div class="mvc-res-empty-icon-wrap">
                    <span class="mvc-res-empty-icon">🔬</span>
                </div>

                <h3 class="mvc-res-empty-title" id="mvcResEmptyTitle">
                    <?php echo $total_researches === 0 ? 'Institutional Research Repository is Ready' : 'No Research Publications Found'; ?>
                </h3>

                <p class="mvc-res-empty-desc" id="mvcResEmptyDesc">
                    <?php if ($total_researches === 0): ?>
                        The Maharshi Vishwamitra Mahavidyalaya scholarly repository is open for archiving peer-reviewed articles, UGC-CARE indexed papers, faculty monographs, conference proceedings, patents, and funded research grants. Department Heads (HODs), faculty researchers, and administrators can publish scholarly works directly to the public portal.
                    <?php else: ?>
                        No publications match your selected department, publication category, or search keywords.
                    <?php endif; ?>
                </p>

                <div class="mvc-res-empty-actions" id="mvcResEmptyActions">
                    <?php if ($total_researches === 0): ?>
                        <?php if ($can_publish): ?>
                            <button type="button" class="mvc-btn-publish-trigger" onclick="mvcOpenPublishModal();" style="display:inline-flex;">
                                <span>➕</span> Publish First Research Paper
                            </button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=research')); ?>" class="mvc-res-empty-admin-link">
                                <span>🏛️</span> Publish via WP Admin Desk &rarr;
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url(home_url('/portal/')); ?>" class="mvc-btn-publish-trigger" style="display:inline-flex; text-decoration:none;">
                                <span>🔐</span> Scholar Portal Login to Submit &rarr;
                            </a>
                            <a href="<?php echo esc_url(home_url('/library/')); ?>" class="mvc-res-empty-admin-link">
                                <span>📖</span> Central Library &amp; ONOS Gateway &rarr;
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button type="button" class="mvc-btn-publish-trigger" onclick="mvcResetFilters();" style="display:inline-flex;">
                            <span>↺</span> Reset All Filters
                        </button>
                    <?php endif; ?>
                </div>

                <?php if ($total_researches === 0): ?>
                    <div class="mvc-res-empty-guide" id="mvcResEmptyGuide">
                        <h4 class="mvc-res-guide-heading">Fast 3-Step Publishing Workflow for Faculty &amp; HODs</h4>
                        <div class="mvc-res-guide-steps">
                            <div class="mvc-res-guide-step">
                                <div class="mvc-res-step-badge">1</div>
                                <div class="mvc-res-step-body">
                                    <strong>Authenticate</strong>
                                    <p>Sign in using institutional credentials (HOD, Content Manager, or Principal).</p>
                                </div>
                            </div>
                            <div class="mvc-res-guide-step">
                                <div class="mvc-res-step-badge">2</div>
                                <div class="mvc-res-step-body">
                                    <strong>Submit Scholarly Dossier</strong>
                                    <p>Enter title, authors, DOI, ISSN, UGC-CARE / Scopus indexing, and attach PDF.</p>
                                </div>
                            </div>
                            <div class="mvc-res-guide-step">
                                <div class="mvc-res-step-badge">3</div>
                                <div class="mvc-res-step-body">
                                    <strong>Public Indexing &amp; Citation</strong>
                                    <p>Instantly indexed with live APA citations, department filters, and open-access downloads.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 5. PUBLICATION CARDS GRID -->
            <div class="mvc-res-cards-grid" id="mvcResCardsGrid" style="<?php echo $total_researches === 0 ? 'display: none;' : 'display: grid;'; ?>">
                <?php
                if (!empty($researches)):
                    foreach ($researches as $r):
                        $t_terms   = wp_get_object_terms($r->ID, 'research_type');
                        $t_slug    = (!empty($t_terms) && !is_wp_error($t_terms)) ? $t_terms[0]->slug : 'journal-article';
                        $t_meta    = mvc_get_research_type_meta($t_slug);

                        $d_terms   = wp_get_object_terms($r->ID, 'dept_link');
                        $d_name    = (!empty($d_terms) && !is_wp_error($d_terms)) ? $d_terms[0]->name : 'Multidisciplinary';
                        $d_slug    = (!empty($d_terms) && !is_wp_error($d_terms)) ? $d_terms[0]->slug : 'multidisciplinary';

                        $authors   = get_post_meta($r->ID, 'research_authors', true) ?: 'Faculty Researcher';
                        $journal   = get_post_meta($r->ID, 'research_journal', true) ?: '';
                        $issn      = get_post_meta($r->ID, 'research_issn_isbn', true) ?: '';
                        $volume    = get_post_meta($r->ID, 'research_volume_issue', true) ?: '';
                        $year      = get_post_meta($r->ID, 'research_year', true) ?: get_the_date('Y', $r->ID);
                        $doi       = get_post_meta($r->ID, 'research_doi', true) ?: '';
                        $pdf       = get_post_meta($r->ID, 'research_pdf_url', true) ?: '';
                        $indexing  = get_post_meta($r->ID, 'research_indexing', true) ?: 'UGC CARE / Peer-Reviewed';
                        $if_val    = get_post_meta($r->ID, 'research_impact_factor', true) ?: '';
                        $grant     = get_post_meta($r->ID, 'research_grant_amount', true) ?: '';
                        $abstract  = trim($r->post_content);

                        // Search blob
                        $search_blob = strtolower($r->post_title . ' ' . $authors . ' ' . $journal . ' ' . $d_name . ' ' . $issn . ' ' . $indexing . ' ' . $year);
                        ?>
                        <article class="mvc-res-card" 
                                 data-type="<?php echo esc_attr($t_slug); ?>"
                                 data-dept="<?php echo esc_attr($d_slug); ?>"
                                 data-year="<?php echo esc_attr($year); ?>"
                                 data-keywords="<?php echo esc_attr($search_blob); ?>"
                                 data-id="<?php echo esc_attr($r->ID); ?>">
                            <div>
                                <div class="mvc-res-card-header">
                                    <span class="mvc-res-type-pill" style="background: <?php echo esc_attr($t_meta['bg']); ?>; color: <?php echo esc_attr($t_meta['color']); ?>;">
                                        <?php echo esc_html($t_meta['icon'] . ' ' . $t_meta['badge']); ?>
                                    </span>
                                    <span class="mvc-res-dept-pill"><?php echo esc_html($d_name); ?></span>
                                </div>

                                <h3 class="mvc-res-card-title"><?php echo esc_html($r->post_title); ?></h3>

                                <div class="mvc-res-card-authors">
                                    <span>👤</span>
                                    <span><?php echo esc_html($authors); ?></span>
                                </div>

                                <?php if (!empty($journal)): ?>
                                    <div class="mvc-res-card-citation">
                                        <em><?php echo esc_html($journal); ?></em>
                                        <?php if (!empty($volume)): ?>, <?php echo esc_html($volume); ?><?php endif; ?>
                                        (<?php echo esc_html($year); ?>).
                                        <?php if (!empty($issn)): ?> &bull; <strong>ISSN/ISBN:</strong> <?php echo esc_html($issn); ?><?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mvc-res-card-badges">
                                    <?php if (!empty($indexing)): ?>
                                        <span class="mvc-res-index-badge">✓ <?php echo esc_html($indexing); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($if_val)): ?>
                                        <span class="mvc-res-if-badge">⭐ IF: <?php echo esc_html($if_val); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($grant)): ?>
                                        <span class="mvc-res-index-badge" style="background:#fef3c7; color:#b45309; border-color:#fde68a;">
                                            Grant: <?php echo esc_html($grant); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($abstract)): ?>
                                    <div class="mvc-res-card-abstract-box">
                                        <button type="button" class="mvc-res-abstract-toggle" onclick="mvcToggleAbstract(this);">
                                            <span>▶</span> Read Abstract &amp; Key Findings
                                        </button>
                                        <div class="mvc-res-abstract-content">
                                            <?php echo wp_kses_post(wpautop($abstract)); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mvc-res-card-footer">
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <?php if (!empty($doi)): ?>
                                        <a href="<?php echo esc_url($doi); ?>" target="_blank" rel="noopener noreferrer" class="mvc-res-action-btn mvc-res-btn-doi" title="Open via DOI / Publisher">
                                            <span>🔗</span> View Paper ↗
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($pdf)): ?>
                                        <a href="<?php echo esc_url($pdf); ?>" target="_blank" rel="noopener noreferrer" class="mvc-res-action-btn mvc-res-btn-pdf" title="Download Full-Text PDF">
                                            <span>📄</span> PDF
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <button type="button" class="mvc-res-action-btn mvc-res-btn-cite" onclick="mvcCopyCitation(this, <?php echo esc_attr($r->ID); ?>);" title="Copy APA Citation">
                                    <span>📋</span> Cite
                                </button>
                            </div>
                        </article>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>

            <!-- 6. APA BIBLIOGRAPHY LIST VIEW -->
            <div class="mvc-res-bib-list" id="mvcResBibList" style="display: none;">
                <?php
                if (!empty($researches)):
                    foreach ($researches as $r):
                        $authors   = get_post_meta($r->ID, 'research_authors', true) ?: 'Faculty Researcher';
                        $journal   = get_post_meta($r->ID, 'research_journal', true) ?: '';
                        $volume    = get_post_meta($r->ID, 'research_volume_issue', true) ?: '';
                        $year      = get_post_meta($r->ID, 'research_year', true) ?: get_the_date('Y', $r->ID);
                        $doi       = get_post_meta($r->ID, 'research_doi', true) ?: '';

                        $t_terms   = wp_get_object_terms($r->ID, 'research_type');
                        $t_slug    = (!empty($t_terms) && !is_wp_error($t_terms)) ? $t_terms[0]->slug : 'journal-article';
                        $d_terms   = wp_get_object_terms($r->ID, 'dept_link');
                        $d_slug    = (!empty($d_terms) && !is_wp_error($d_terms)) ? $d_terms[0]->slug : 'multidisciplinary';

                        $search_blob = strtolower($r->post_title . ' ' . $authors . ' ' . $journal . ' ' . $year);

                        // Formatted APA String
                        $apa_citation = esc_html($authors) . ' (' . esc_html($year) . '). ' . esc_html($r->post_title) . '. ';
                        if (!empty($journal)) {
                            $apa_citation .= '<em>' . esc_html($journal) . '</em>';
                            if (!empty($volume)) {
                                $apa_citation .= ', ' . esc_html($volume);
                            }
                            $apa_citation .= '. ';
                        }
                        if (!empty($doi)) {
                            $apa_citation .= '<a href="' . esc_url($doi) . '" target="_blank" rel="noopener">' . esc_html($doi) . '</a>';
                        }
                        ?>
                        <div class="mvc-res-bib-item"
                             data-type="<?php echo esc_attr($t_slug); ?>"
                             data-dept="<?php echo esc_attr($d_slug); ?>"
                             data-year="<?php echo esc_attr($year); ?>"
                             data-keywords="<?php echo esc_attr($search_blob); ?>"
                             data-id="<?php echo esc_attr($r->ID); ?>">
                            <div class="mvc-res-bib-citation-text" id="mvcBibText-<?php echo (int) $r->ID; ?>">
                                <?php echo $apa_citation; ?>
                            </div>
                            <button type="button" class="mvc-res-action-btn mvc-res-btn-cite" onclick="mvcCopyCitation(this, <?php echo esc_attr($r->ID); ?>);" style="flex-shrink:0;">
                                <span>📋</span> Copy
                            </button>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>

            <!-- 7. DIGITAL RESEARCH CONSORTIA WINGS -->
            <section class="mvc-res-consortia-grid" aria-label="Digital Research Networks">
                <div class="mvc-res-consortia-card mvc-res-card-onos">
                    <div class="mvc-res-consortia-kicker">Government of India National Initiative</div>
                    <h3 class="mvc-res-consortia-title">One Nation One Subscription (ONOS)</h3>
                    <p class="mvc-res-consortia-desc">
                        Centrally funded access to premier international publishers including Elsevier, Springer Nature, Wiley, IEEE, and Taylor &amp; Francis for faculty and research scholars.
                        <br><br>
                        <strong>Nodal Officer:</strong> Dr. Ravi Thakur, Department of Chemistry.
                    </p>
                    <a href="<?php echo esc_url(home_url('/onos/')); ?>" class="mvc-res-consortia-btn">
                        Explore ONOS Portals &rarr;
                    </a>
                </div>

                <div class="mvc-res-consortia-card mvc-res-card-inflibnet">
                    <div class="mvc-res-consortia-kicker">UGC INFLIBNET Central Consortia</div>
                    <h3 class="mvc-res-consortia-title">INFLIBNET N-LIST Repository</h3>
                    <p class="mvc-res-consortia-desc">
                        Remote 24x7 automated access to 6,000+ peer-reviewed e-journals and 1,99,500+ academic e-books across humanities, science, and social sciences via the college library.
                        <br><br>
                        <strong>Custodian:</strong> Automated Central Library &amp; e-Resource Center.
                    </p>
                    <a href="<?php echo esc_url(home_url('/library/')); ?>" class="mvc-res-consortia-btn">
                        Digital Library Gateway &rarr;
                    </a>
                </div>
            </section>

            <!-- 8. RESEARCH ETHICS & PLAGIARISM PROTOCOL -->
            <section class="mvc-res-policy-banner" aria-label="Research Ethics and Integrity Policy">
                <div class="mvc-res-policy-content">
                    <h3 class="mvc-res-policy-title">Institutional Research Ethics &amp; Anti-Plagiarism Protocol</h3>
                    <p class="mvc-res-policy-desc">
                        All research manuscripts, theses, and seminar papers published under Maharshi Vishwamitra Mahavidyalaya adhere strictly to UGC Academic Integrity Regulations (2018). Faculty manuscripts undergo mandatory similarity screening (&lt;10% threshold) using approved plagiarism software prior to submission for publication or institutional funding.
                    </p>
                </div>
                <div>
                    <a href="<?php echo esc_url(home_url('/teaching-staff/')); ?>" class="mvc-res-action-btn mvc-res-btn-doi" style="padding:10px 18px; font-size:13px;">
                        👨‍🏫 Faculty Directories &amp; CVs &rarr;
                    </a>
                </div>
            </section>
        </main>

        <!-- 9. EXECUTIVE PUBLISHING MODAL (FOR ADMIN, CM, HOD) -->
        <?php if ($can_publish): ?>
            <div class="mvc-res-modal-overlay" id="mvcPublishModal" onclick="if(event.target === this) mvcClosePublishModal();" role="dialog" aria-modal="true" aria-labelledby="mvcModalHeading">
                <div class="mvc-res-modal-dialog">
                    <div class="mvc-res-modal-header">
                        <div>
                            <h3 id="mvcModalHeading">Publish New Research Publication</h3>
                            <p>Authorized Executive Submission Desk &bull; <?php echo esc_html($role_label); ?></p>
                        </div>
                        <button type="button" class="mvc-res-modal-close" onclick="mvcClosePublishModal();" aria-label="Close modal">&times;</button>
                    </div>

                    <div class="mvc-res-modal-body">
                        <div class="mvc-res-alert-box" id="mvcModalAlert"></div>

                        <form id="mvcPublishResearchForm" onsubmit="mvcSubmitResearchForm(event);">
                            <input type="hidden" name="action" value="mvc_ajax_publish_research">
                            <input type="hidden" name="security" value="<?php echo esc_attr($ajax_nonce); ?>">

                            <div class="mvc-res-form-grid">
                                <!-- Title -->
                                <div class="mvc-res-form-group mvc-res-form-full">
                                    <label class="mvc-res-label" for="res_title">Paper / Project Title <span class="req">*</span></label>
                                    <input type="text" id="res_title" name="research_title" class="mvc-res-input" placeholder="e.g. Synthesis, Characterization and Catalytic Study of Novel Transition Metal Complexes" required>
                                </div>

                                <!-- Authors -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_authors">Author(s) / Lead Investigator <span class="req">*</span></label>
                                    <input type="text" id="res_authors" name="research_authors" class="mvc-res-input" value="<?php echo esc_attr($current_user->display_name); ?>" placeholder="e.g. Dr. Ravi Thakur, Dr. R. K. Singh" required>
                                </div>

                                <!-- Department -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_dept">Academic Department <span class="req">*</span></label>
                                    <?php if ($is_hod && !($is_admin || $is_cm)): ?>
                                        <select id="res_dept" name="dept_term_id" class="mvc-res-form-select" style="background:#f1f5f9; font-weight:700;">
                                            <?php if ($assigned_dept_term): ?>
                                                <option value="<?php echo esc_attr($assigned_dept_term->term_id); ?>" selected>
                                                    👑 <?php echo esc_html($assigned_dept_term->name); ?> (Assigned Department)
                                                </option>
                                            <?php else: ?>
                                                <option value="42">Chemistry</option>
                                            <?php endif; ?>
                                        </select>
                                    <?php else: ?>
                                        <select id="res_dept" name="dept_term_id" class="mvc-res-form-select" required>
                                            <option value="">-- Select Department --</option>
                                            <?php foreach ($all_depts as $dept): ?>
                                                <option value="<?php echo esc_attr($dept->term_id); ?>">
                                                    <?php echo esc_html($dept->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>

                                <!-- Category -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_type">Research Category <span class="req">*</span></label>
                                    <select id="res_type" name="research_type" class="mvc-res-form-select" required>
                                        <option value="journal-article">📖 Peer-Reviewed / UGC-CARE Journal</option>
                                        <option value="conference-paper">🎤 International / National Conference Proceeding</option>
                                        <option value="book-chapter">📚 Book / Authored Chapter</option>
                                        <option value="funded-project">💰 Sponsored Research Project (UGC/ICSSR/CSIR)</option>
                                        <option value="phd-dissertation">🎓 Ph.D. Dissertation / Doctoral Guidance</option>
                                        <option value="patent-innovation">💡 Patent / IPR Innovation</option>
                                        <option value="policy-brief">📑 Institutional Policy Document</option>
                                    </select>
                                </div>

                                <!-- Year -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_year">Publication / Grant Year <span class="req">*</span></label>
                                    <input type="text" id="res_year" name="research_year" class="mvc-res-input" value="<?php echo esc_attr(date('Y')); ?>" placeholder="e.g. 2026" required>
                                </div>

                                <!-- Journal / Publisher -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_journal">Journal / Publisher / Funding Agency</label>
                                    <input type="text" id="res_journal" name="research_journal" class="mvc-res-input" placeholder="e.g. Journal of Chemical Sciences (Springer Nature)">
                                </div>

                                <!-- ISSN / ISBN -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_issn">ISSN / ISBN / Grant Ref No.</label>
                                    <input type="text" id="res_issn" name="research_issn_isbn" class="mvc-res-input" placeholder="e.g. ISSN: 0974-3626 / F.No. 42-12/2026">
                                </div>

                                <!-- Volume & Issue -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_volume">Volume, Issue &amp; Page Numbers</label>
                                    <input type="text" id="res_volume" name="research_volume_issue" class="mvc-res-input" placeholder="e.g. Vol. 138, No. 4, pp. 245–259">
                                </div>

                                <!-- Indexing Status -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_indexing">Indexing Recognition</label>
                                    <input type="text" id="res_indexing" name="research_indexing" class="mvc-res-input" value="UGC CARE List I &bull; Scopus" placeholder="e.g. UGC CARE Group I, Scopus, Web of Science">
                                </div>

                                <!-- Impact Factor -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_impact">Impact Factor (IF) or Grant Amount</label>
                                    <input type="text" id="res_impact" name="research_impact_factor" class="mvc-res-input" placeholder="e.g. 3.85 or ₹5,50,000 (ICSSR)">
                                </div>

                                <!-- DOI / External Link -->
                                <div class="mvc-res-form-group">
                                    <label class="mvc-res-label" for="res_doi">DOI / External Publisher URL</label>
                                    <input type="url" id="res_doi" name="research_doi" class="mvc-res-input" placeholder="https://doi.org/10.1007/s12039-026-02100-x">
                                </div>

                                <!-- Full-text PDF Link -->
                                <div class="mvc-res-form-group mvc-res-form-full">
                                    <label class="mvc-res-label" for="res_pdf">Full-Text PDF URL (Media Library or Direct Link)</label>
                                    <input type="url" id="res_pdf" name="research_pdf_url" class="mvc-res-input" placeholder="https://mvc.content/uploads/research-paper.pdf">
                                </div>

                                <!-- Abstract -->
                                <div class="mvc-res-form-group mvc-res-form-full">
                                    <label class="mvc-res-label" for="res_abstract">Abstract / Executive Summary</label>
                                    <textarea id="res_abstract" name="research_abstract" class="mvc-res-textarea" rows="4" placeholder="Brief methodology, empirical findings, and academic contributions..."></textarea>
                                </div>
                            </div>

                            <div class="mvc-res-modal-footer">
                                <button type="button" class="mvc-btn-modal-cancel" onclick="mvcClosePublishModal();">Cancel</button>
                                <button type="submit" class="mvc-btn-modal-submit" id="mvcBtnSubmitResearch">
                                    <span>🚀</span> Publish to Public Portal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 10. CLIENT ENGINE JAVASCRIPT -->
        <script>
            var mvcActiveTypeFilter = 'all';
            var mvcActiveViewMode = 'grid';

            // Filter Engine
            function mvcFilterResearch() {
                var searchVal = (document.getElementById('mvcResSearchInput').value || '').toLowerCase().trim();
                var deptVal   = document.getElementById('mvcResDeptSelect').value;
                var yearVal   = document.getElementById('mvcResYearSelect').value;

                var cards = document.querySelectorAll('.mvc-res-card');
                var bibItems = document.querySelectorAll('.mvc-res-bib-item');
                var visibleCount = 0;

                cards.forEach(function(card, idx) {
                    var bibItem = bibItems[idx];
                    var type = card.getAttribute('data-type');
                    var dept = card.getAttribute('data-dept');
                    var year = card.getAttribute('data-year');
                    var keywords = card.getAttribute('data-keywords') || '';

                    var matchType = (mvcActiveTypeFilter === 'all' || type === mvcActiveTypeFilter);
                    var matchDept = (deptVal === 'all' || dept === deptVal);
                    
                    var matchYear = true;
                    if (yearVal !== 'all') {
                        if (yearVal === 'prior') {
                            matchYear = (parseInt(year, 10) <= 2022);
                        } else {
                            matchYear = (year === yearVal);
                        }
                    }

                    var matchSearch = (searchVal === '' || keywords.indexOf(searchVal) !== -1);

                    var isVisible = matchType && matchDept && matchYear && matchSearch;

                    if (isVisible) {
                        card.style.display = 'flex';
                        if (bibItem) bibItem.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                        if (bibItem) bibItem.style.display = 'none';
                    }
                });

                // Update counter
                var counterEl = document.getElementById('mvcResVisibleCount');
                if (counterEl) counterEl.textContent = visibleCount;

                // Handle empty state
                var emptyEl = document.getElementById('mvcResEmptyState');
                var emptyTitle = document.getElementById('mvcResEmptyTitle');
                var emptyDesc = document.getElementById('mvcResEmptyDesc');
                var emptyActions = document.getElementById('mvcResEmptyActions');
                var emptyGuide = document.getElementById('mvcResEmptyGuide');
                var cardsGrid = document.getElementById('mvcResCardsGrid');
                var bibList = document.getElementById('mvcResBibList');

                if (cards.length === 0) {
                    // 0 total database records: keep the premier institutional onboarding empty state
                    if (emptyEl) emptyEl.style.display = 'block';
                    if (cardsGrid) cardsGrid.style.display = 'none';
                    if (bibList) bibList.style.display = 'none';
                    if (emptyGuide) emptyGuide.style.display = 'block';
                } else if (visibleCount === 0) {
                    // Filters returned 0 records: show filter-specific empty state
                    if (emptyTitle) emptyTitle.textContent = 'No Research Publications Found';
                    if (emptyDesc) emptyDesc.textContent = 'No publications match your selected department, publication category, or search keywords.';
                    if (emptyActions) {
                        emptyActions.innerHTML = '<button type="button" class="mvc-btn-publish-trigger" onclick="mvcResetFilters();" style="display:inline-flex;"><span>↺</span> Reset All Filters</button>';
                    }
                    if (emptyGuide) emptyGuide.style.display = 'none';
                    if (emptyEl) emptyEl.style.display = 'block';
                    if (cardsGrid) cardsGrid.style.display = 'none';
                    if (bibList) bibList.style.display = 'none';
                } else {
                    if (emptyEl) emptyEl.style.display = 'none';
                    if (mvcActiveViewMode === 'grid') {
                        if (cardsGrid) cardsGrid.style.display = 'grid';
                        if (bibList) bibList.style.display = 'none';
                    } else {
                        if (cardsGrid) cardsGrid.style.display = 'none';
                        if (bibList) bibList.style.display = 'flex';
                    }
                }
            }

            // Auto-run filter verification when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                mvcFilterResearch();
            });

            // Category Tab Selection
            function mvcSelectTypeFilter(type, btn) {
                mvcActiveTypeFilter = type;
                var btns = document.querySelectorAll('.mvc-res-tab-btn');
                btns.forEach(function(b) { b.classList.remove('active'); });
                if (btn) btn.classList.add('active');
                mvcFilterResearch();
            }

            // Reset All Filters
            function mvcResetFilters() {
                var search = document.getElementById('mvcResSearchInput');
                if (search) search.value = '';
                var dept = document.getElementById('mvcResDeptSelect');
                if (dept) dept.value = 'all';
                var yr = document.getElementById('mvcResYearSelect');
                if (yr) yr.value = 'all';

                var allTab = document.querySelector('.mvc-res-tab-btn[data-type="all"]');
                mvcSelectTypeFilter('all', allTab);
            }

            // View Mode Switcher
            function mvcSetViewMode(mode) {
                mvcActiveViewMode = mode;
                var btnGrid = document.getElementById('mvcBtnCardView');
                var btnBib  = document.getElementById('mvcBtnBibView');
                var cardsGrid = document.getElementById('mvcResCardsGrid');
                var bibList   = document.getElementById('mvcResBibList');

                if (mode === 'grid') {
                    if (btnGrid) btnGrid.classList.add('active');
                    if (btnBib)  btnBib.classList.remove('active');
                    if (cardsGrid) cardsGrid.style.display = 'grid';
                    if (bibList)   bibList.style.display = 'none';
                } else {
                    if (btnGrid) btnGrid.classList.remove('active');
                    if (btnBib)  btnBib.classList.add('active');
                    if (cardsGrid) cardsGrid.style.display = 'none';
                    if (bibList)   bibList.style.display = 'flex';
                }
                mvcFilterResearch();
            }

            // Toggle Abstract Accordion
            function mvcToggleAbstract(btn) {
                var content = btn.nextElementSibling;
                if (!content) return;
                var isShown = content.style.display === 'block';
                content.style.display = isShown ? 'none' : 'block';
                btn.querySelector('span').textContent = isShown ? '▶' : '▼';
            }

            // Copy Citation to Clipboard
            function mvcCopyCitation(btn, id) {
                var bibEl = document.getElementById('mvcBibText-' + id);
                var text = bibEl ? bibEl.innerText || bibEl.textContent : '';
                if (!text) {
                    var card = document.querySelector('.mvc-res-card[data-id="' + id + '"]');
                    if (card) {
                        var title = card.querySelector('.mvc-res-card-title').textContent.trim();
                        var authors = card.querySelector('.mvc-res-card-authors').textContent.trim();
                        text = authors + '. ' + title + '.';
                    }
                }

                if (navigator.clipboard && text) {
                    navigator.clipboard.writeText(text).then(function() {
                        var originalHtml = btn.innerHTML;
                        btn.innerHTML = '<span>✓</span> Copied!';
                        btn.style.background = '#d1fae5';
                        btn.style.color = '#065f46';
                        setTimeout(function() {
                            btn.innerHTML = originalHtml;
                            btn.style.background = '';
                            btn.style.color = '';
                        }, 2200);
                    });
                }
            }

            // Modal Handlers
            function mvcOpenPublishModal() {
                var modal = document.getElementById('mvcPublishModal');
                if (modal) {
                    modal.style.display = 'flex';
                    var alertBox = document.getElementById('mvcModalAlert');
                    if (alertBox) alertBox.style.display = 'none';
                }
            }

            function mvcClosePublishModal() {
                var modal = document.getElementById('mvcPublishModal');
                if (modal) modal.style.display = 'none';
            }

            // Submit Research Form via AJAX
            function mvcSubmitResearchForm(e) {
                e.preventDefault();
                var form = document.getElementById('mvcPublishResearchForm');
                var submitBtn = document.getElementById('mvcBtnSubmitResearch');
                var alertBox = document.getElementById('mvcModalAlert');

                if (!form || !submitBtn) return;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>⏳</span> Publishing to Portal...';

                var formData = new FormData(form);

                fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>🚀</span> Publish to Public Portal';

                    if (data && data.success) {
                        alertBox.className = 'mvc-res-alert-box mvc-res-alert-success';
                        alertBox.textContent = data.data.message || 'Published successfully!';
                        alertBox.style.display = 'block';

                        // Update KPI count
                        var kpiCount = document.getElementById('mvcKpiTotalCount');
                        if (kpiCount) {
                            var current = parseInt(kpiCount.textContent, 10) || 0;
                            kpiCount.textContent = current + 1;
                        }

                        // Reset form
                        form.reset();

                        // Close modal after 1.5s and reload page to reflect newly seeded database record
                        setTimeout(function() {
                            mvcClosePublishModal();
                            window.location.reload();
                        }, 1400);
                    } else {
                        alertBox.className = 'mvc-res-alert-box mvc-res-alert-error';
                        alertBox.textContent = (data && data.data && data.data.message) ? data.data.message : 'Error publishing research. Please verify all fields.';
                        alertBox.style.display = 'block';
                    }
                })
                .catch(function(err) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>🚀</span> Publish to Public Portal';
                    alertBox.className = 'mvc-res-alert-box mvc-res-alert-error';
                    alertBox.textContent = 'Server communication error: ' + err.message;
                    alertBox.style.display = 'block';
                });
            }
        </script>
    </div>
    <?php
}
}

/**
 * 6. WP ADMIN INTEGRATION: Custom Meta Box for Research CPT
 */
function mvc_add_research_meta_boxes() {
    add_meta_box(
        'mvc_research_details_meta',
        __('📖 Scholarly Publication & Dossier Details', 'mvc-college'),
        'mvc_render_research_admin_meta_box',
        'research',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mvc_add_research_meta_boxes');

function mvc_render_research_admin_meta_box($post) {
    wp_nonce_field('mvc_save_research_meta_action', 'mvc_research_meta_nonce');

    $authors       = get_post_meta($post->ID, 'research_authors', true);
    $journal       = get_post_meta($post->ID, 'research_journal', true);
    $volume        = get_post_meta($post->ID, 'research_volume_issue', true);
    $year          = get_post_meta($post->ID, 'research_year', true) ?: date('Y');
    $issn          = get_post_meta($post->ID, 'research_issn_isbn', true);
    $doi           = get_post_meta($post->ID, 'research_doi', true);
    $pdf_url       = get_post_meta($post->ID, 'research_pdf_url', true);
    $indexing      = get_post_meta($post->ID, 'research_indexing', true) ?: 'UGC CARE List I &bull; Scopus';
    $impact_factor = get_post_meta($post->ID, 'research_impact_factor', true);

    $current_user = wp_get_current_user();
    ?>
    <style>
        .mvc-admin-meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; padding: 12px 6px; }
        .mvc-admin-meta-full { grid-column: 1 / -1; }
        .mvc-admin-meta-group label { display: block; font-weight: 700; margin-bottom: 6px; color: #1e293b; font-size: 13px; }
        .mvc-admin-meta-group input[type="text"],
        .mvc-admin-meta-group input[type="url"] { width: 100%; padding: 8px 12px; border: 1.5px solid #cbd5e1; border-radius: 6px; font-size: 13.5px; }
        .mvc-admin-meta-group input:focus { border-color: #002147; box-shadow: 0 0 0 2px rgba(0,33,71,0.1); outline: none; }
        .mvc-admin-meta-hint { font-size: 11.5px; color: #64748b; margin-top: 4px; }
    </style>
    <div class="mvc-admin-meta-grid">
        <div class="mvc-admin-meta-group">
            <label for="research_authors">Author(s) / Lead Investigator <span style="color:#dc2626;">*</span></label>
            <input type="text" id="research_authors" name="research_authors" value="<?php echo esc_attr($authors ?: $current_user->display_name); ?>" placeholder="e.g. Dr. Ravi Thakur, Dr. Rakesh Tiwari" required>
            <div class="mvc-admin-meta-hint">Primary and contributing scholarly authors</div>
        </div>
        <div class="mvc-admin-meta-group">
            <label for="research_year">Publication / Award Year <span style="color:#dc2626;">*</span></label>
            <input type="text" id="research_year" name="research_year" value="<?php echo esc_attr($year); ?>" placeholder="e.g. 2026" required>
            <div class="mvc-admin-meta-hint">Calendar year of official publication</div>
        </div>
        <div class="mvc-admin-meta-group">
            <label for="research_journal">Journal / Publisher / Funding Agency</label>
            <input type="text" id="research_journal" name="research_journal" value="<?php echo esc_attr($journal); ?>" placeholder="e.g. Journal of Chemical Sciences (Springer Nature)">
            <div class="mvc-admin-meta-hint">Name of academic journal, university press, or funding body</div>
        </div>
        <div class="mvc-admin-meta-group">
            <label for="research_volume_issue">Volume, Issue &amp; Page Numbers</label>
            <input type="text" id="research_volume_issue" name="research_volume_issue" value="<?php echo esc_attr($volume); ?>" placeholder="e.g. Vol. 138, Issue 3, pp. 240–258">
            <div class="mvc-admin-meta-hint">Bibliographic issue citation</div>
        </div>
        <div class="mvc-admin-meta-group">
            <label for="research_issn_isbn">ISSN / ISBN / Grant Sanction Ref</label>
            <input type="text" id="research_issn_isbn" name="research_issn_isbn" value="<?php echo esc_attr($issn); ?>" placeholder="e.g. 0974-3626 / ISBN 978-0-19-948123-4">
            <div class="mvc-admin-meta-hint">Standard serial number or grant sanction memo number</div>
        </div>
        <div class="mvc-admin-meta-group">
            <label for="research_indexing">Indexing Recognition Status</label>
            <input type="text" id="research_indexing" name="research_indexing" value="<?php echo esc_attr($indexing); ?>" placeholder="e.g. UGC CARE Group I, Scopus Indexed, Web of Science">
            <div class="mvc-admin-meta-hint">Accreditation index or database listing</div>
        </div>
        <div class="mvc-admin-meta-group">
            <label for="research_impact_factor">Impact Factor (IF) or Grant Amount</label>
            <input type="text" id="research_impact_factor" name="research_impact_factor" value="<?php echo esc_attr($impact_factor); ?>" placeholder="e.g. 3.42 or ₹7,20,000 (UGC MRP)">
            <div class="mvc-admin-meta-hint">Thomson Reuters/Clarivate IF, or total sanction amount</div>
        </div>
        <div class="mvc-admin-meta-group">
            <label for="research_doi">DOI / External Publisher URL</label>
            <input type="url" id="research_doi" name="research_doi" value="<?php echo esc_url($doi); ?>" placeholder="https://doi.org/10.1007/s12039-026-02100-x">
            <div class="mvc-admin-meta-hint">Permanent Digital Object Identifier link</div>
        </div>
        <div class="mvc-admin-meta-group mvc-admin-meta-full">
            <label for="research_pdf_url">Full-Text PDF Document URL</label>
            <div style="display:flex; gap:10px; align-items:center;">
                <input type="url" id="research_pdf_url" name="research_pdf_url" value="<?php echo esc_url($pdf_url); ?>" placeholder="https://demo.retailcoder.com/wp-content/uploads/research-paper.pdf" style="flex:1;">
                <button type="button" class="button button-secondary" id="mvcBtnUploadPdf" style="height:36px;">📁 Choose from Media</button>
            </div>
            <div class="mvc-admin-meta-hint">Upload or link full-text research manuscript PDF</div>
        </div>
    </div>
    <script>
    jQuery(document).ready(function($){
        $('#mvcBtnUploadPdf').on('click', function(e){
            e.preventDefault();
            var frame = wp.media({
                title: 'Select or Upload Research PDF',
                button: { text: 'Attach PDF' },
                multiple: false
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#research_pdf_url').val(attachment.url);
            });
            frame.open();
        });
    });
    </script>
    <?php
}

function mvc_save_research_meta_box_data($post_id) {
    if (!isset($_POST['mvc_research_meta_nonce']) || !wp_verify_nonce($_POST['mvc_research_meta_nonce'], 'mvc_save_research_meta_action')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_researches', $post_id)) {
        return;
    }

    $fields = array(
        'research_authors'       => 'sanitize_text_field',
        'research_journal'       => 'sanitize_text_field',
        'research_volume_issue'  => 'sanitize_text_field',
        'research_year'          => 'sanitize_text_field',
        'research_issn_isbn'     => 'sanitize_text_field',
        'research_indexing'      => 'sanitize_text_field',
        'research_impact_factor' => 'sanitize_text_field',
        'research_doi'           => 'esc_url_raw',
        'research_pdf_url'       => 'esc_url_raw',
    );

    foreach ($fields as $key => $sanitizer) {
        if (isset($_POST[$key])) {
            $val = call_user_func($sanitizer, wp_unslash($_POST[$key]));
            update_post_meta($post_id, $key, $val);
        }
    }

    // Flush performance caches
    if (function_exists('mvc_flush_all_performance_caches')) {
        mvc_flush_all_performance_caches($post_id);
    }
    delete_transient('mvc_cached_research_page_html');
}
add_action('save_post_research', 'mvc_save_research_meta_box_data');

/**
 * 7. WP ADMIN COLUMNS: Custom columns on edit.php?post_type=research
 */
function mvc_research_admin_columns($columns) {
    $new_cols = array();
    $new_cols['cb']          = $columns['cb'];
    $new_cols['title']       = __('Paper / Project Title', 'mvc-college');
    $new_cols['authors']     = __('Author(s)', 'mvc-college');
    $new_cols['dept']        = __('Academic Department', 'mvc-college');
    $new_cols['category']    = __('Category', 'mvc-college');
    $new_cols['journal']     = __('Journal / Publisher', 'mvc-college');
    $new_cols['year']        = __('Year', 'mvc-college');
    $new_cols['date']        = __('Published Date', 'mvc-college');
    return $new_cols;
}
add_filter('manage_research_posts_columns', 'mvc_research_admin_columns');

function mvc_research_admin_custom_column($column, $post_id) {
    if ($column === 'authors') {
        echo esc_html(get_post_meta($post_id, 'research_authors', true) ?: '—');
    } elseif ($column === 'dept') {
        $terms = wp_get_object_terms($post_id, 'dept_link');
        echo !empty($terms) && !is_wp_error($terms) ? esc_html($terms[0]->name) : '—';
    } elseif ($column === 'category') {
        $terms = wp_get_object_terms($post_id, 'research_type');
        echo !empty($terms) && !is_wp_error($terms) ? esc_html($terms[0]->name) : '—';
    } elseif ($column === 'journal') {
        echo esc_html(get_post_meta($post_id, 'research_journal', true) ?: '—');
    } elseif ($column === 'year') {
        echo esc_html(get_post_meta($post_id, 'research_year', true) ?: '—');
    }
}
add_action('manage_research_posts_custom_column', 'mvc_research_admin_custom_column', 10, 2);
