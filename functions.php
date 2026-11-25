<?php
/**
 * RetailCoder StoreFront [RC:StoreFront] Theme functions and definitions
 * https://retailcoder.com
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/* ==========================================
   1. ENQUEUE PARENT & CHILD STYLES
   ========================================== */
function astra_child_enqueue_styles() {
    wp_enqueue_style( 'mvc-google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&family=Noto+Sans+Devanagari:wght@600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );
    $child_style_path = get_stylesheet_directory() . '/style.css';
    $child_ver = file_exists( $child_style_path ) ? filemtime( $child_style_path ) : wp_get_theme()->get('Version');
    wp_enqueue_style( 'astra-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style', 'mvc-google-fonts'), $child_ver );

    // Universal Smooth Scroll & Anchor Navigation Engine
    $scroll_js_path = get_stylesheet_directory() . '/assets/js/mvc-smooth-scroll.js';
    $scroll_ver = file_exists( $scroll_js_path ) ? filemtime( $scroll_js_path ) : '1.0.0';
    wp_enqueue_script( 'mvc-smooth-scroll', get_stylesheet_directory_uri() . '/assets/js/mvc-smooth-scroll.js', array(), $scroll_ver, true );
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles', 15 );

/* ==========================================
   2. REGISTER CUSTOM MEGA MENU
   ========================================== */
function mvc_register_menus() {
    register_nav_menus(array(
        'primary-mega-menu' => __('Header Mega Menu', 'mvc-child'),
        'mobile-menu'       => __('Mobile Slide-out Menu', 'mvc-child')
    ));
}
add_action('after_setup_theme', 'mvc_register_menus');

/* ==========================================
   3. ACF GLOBAL OPTIONS PAGE (Header/Footer Data)
   ========================================== */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'    => 'College Global Settings',
        'menu_title'    => 'Global Settings',
        'menu_slug'     => 'college-global-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'icon_url'      => 'dashicons-admin-site-alt3',
        'position'      => 30
    ));
}

// Add Email & SMTP Gateway Shortcut Submenu (Zoho Compatible)
add_action('admin_menu', function() {
    $target_page = 'admin.php?page=wp-mail-smtp';
    if (function_exists('is_plugin_active') && is_plugin_active('zoho-mail/zohoMail.php')) {
        $target_page = 'admin.php?page=zmail-integ-settings';
    }
    add_submenu_page(
        'college-global-settings',
        __('Institutional Email & Zoho SMTP Gateway', 'mvc-child'),
        __('📧 Email & Zoho SMTP Gateway', 'mvc-child'),
        'manage_options',
        $target_page
    );
}, 99);

/**
 * Partially mask mobile numbers (Item 24: e.g. 9431839619 -> *****39619)
 */
function mvc_mask_phone($phone) {
    if (empty($phone)) return '';
    $clean = trim($phone);
    if (preg_match('/([6-9]\d{4})(\d{5})$/', preg_replace('/[^\d]/', '', $clean), $matches)) {
        return '*****' . $matches[2];
    }
    return $phone;
}

/* ==========================================
   4. REAL VISITOR COUNTER 
   ========================================== */
function mvc_track_visitors() {
    if (is_admin()) return;
    
    // 1. Track Total, Today, and Yesterday (via Cookies)
    $session_key = 'mvc_visited_today';
    if (!isset($_COOKIE[$session_key])) {
        $total = get_option('mvc_total_visitors', 166150);
        update_option('mvc_total_visitors', $total + 1);
        
        $today_date = date('Y-m-d');
        $stored_date = get_option('mvc_counter_date', '');
        
        if ($today_date !== $stored_date) {
            $yesterday = get_option('mvc_today_visitors', 0);
            update_option('mvc_yesterday_visitors', $yesterday);
            update_option('mvc_today_visitors', 1);
            update_option('mvc_counter_date', $today_date);
        } else {
            $today = get_option('mvc_today_visitors', 0);
            update_option('mvc_today_visitors', $today + 1);
        }
        setcookie($session_key, '1', time() + 43200, '/');
    }

    // 2. Track Real Active Visitors (Throttled to 1 write per 3 minutes per visitor to eliminate DB thrashing)
    $ping_key = 'mvc_active_ping';
    if (!isset($_COOKIE[$ping_key])) {
        $ip = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $active_visitors = get_transient('mvc_real_active_visitors');
        if (!is_array($active_visitors)) {
            $active_visitors = array();
        }
        
        $current_time = time();
        // Clean up visitors inactive for > 5 minutes (300 seconds)
        foreach ($active_visitors as $visitor_ip => $timestamp) {
            if ($current_time - $timestamp > 300) {
                unset($active_visitors[$visitor_ip]);
            }
        }
        // Log current visitor
        $active_visitors[$ip] = $current_time;
        set_transient('mvc_real_active_visitors', $active_visitors, 300);
        setcookie($ping_key, '1', time() + 180, '/');
    }
}
add_action('wp', 'mvc_track_visitors');

function mvc_display_visitor_counter() {
    $total = get_option('mvc_total_visitors', 166150);
    $today = get_option('mvc_today_visitors', 1);
    $yesterday = get_option('mvc_yesterday_visitors', 0);
    
    // Count real active IPs
    $active_visitors = get_transient('mvc_real_active_visitors');
    $active = is_array($active_visitors) ? count($active_visitors) : 1;
    
    echo "<ul class='mvc-visitor-stats'>";
    echo "<li>Total Visitors: <strong>{$total}</strong></li>";
    echo "<li>Today's Visitors: <strong>{$today}</strong></li>";
    echo "<li>Yesterday's Visitors: <strong>{$yesterday}</strong></li>";
    echo "<li>Active Visitors: <strong><span style='color:#B71C1C;'>{$active}</span></strong></li>";
    echo "</ul>";
}

/* ==========================================
   4B. MASTER DYNAMIC CONTENT ENGINE
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/dynamic-content-engine.php')) {
    require_once get_stylesheet_directory() . '/inc/dynamic-content-engine.php';
}

/* ==========================================
   5. CPT & ACF FREE SETUP
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/cpt-and-acf-setup.php')) {
    require_once get_stylesheet_directory() . '/inc/cpt-and-acf-setup.php';
}

/* ==========================================
   6. HOMEPAGE BUILDER & SHORTCODE
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/homepage-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/homepage-builder.php';
}

/* ==========================================
   7. DEPARTMENT BUILDER & SHORTCODE
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/department-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/department-builder.php';
}

/* ==========================================
   8. MASTER ACADEMIC DIRECTORY BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/department-details-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/department-details-builder.php';
}

/* ==========================================
   9. ROLE-BASED DASHBOARDS & SCHOLAR PORTAL ENGINE (10/10 STANDARD)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/admin/bootstrap.php')) {
    require_once get_stylesheet_directory() . '/inc/admin/bootstrap.php';
}

/* ==========================================
   10. ACADEMIC CALENDAR BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/academic-calendar-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/academic-calendar-builder.php';
}

/* ==========================================
   11. MASTER STAFF DIRECTORY BUILDER (TEACHING & NON-TEACHING)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/staff-directory-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/staff-directory-builder.php';
}

/* ==========================================
   12. INSTITUTIONAL ADMINISTRATION BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/administration-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/administration-builder.php';
}

/* ==========================================
   12B. UNIVERSITY ADMINISTRATION BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/university-administration-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/university-administration-builder.php';
}

/* ==========================================
   12C. STATUTORY COMMITTEES & CELLS BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/committees-cells-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/committees-cells-builder.php';
}

/* ==========================================
   13. SANCTIONED POSTS & CADRE STRENGTH BUILDER (TEACHERS & NON-TEACHERS)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/sanctioned-posts-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/sanctioned-posts-builder.php';
}

/* ==========================================
   14. PRINCIPAL'S DESK BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/principal-desk-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/principal-desk-builder.php';
}

/* ==========================================
   15. PRINCIPAL'S SUCCESSION LIST BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/succession-list-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/succession-list-builder.php';
}

/* ==========================================
   16. ABOUT US & INSTITUTIONAL IDENTITY SUITE BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/about-pages-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/about-pages-builder.php';
}

/* ==========================================
   17. MEDIA & GALLERY SUITE BUILDER (PHOTO, VIDEO, PRESS/MEDIA)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/media-gallery-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/media-gallery-builder.php';
}

/* ==========================================
   18. NAAC QUALITY & ACCREDITATION SUITE BUILDER & ADMIN DASHBOARD
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/naac-suite-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/naac-suite-builder.php';
}
if (file_exists(get_stylesheet_directory() . '/inc/naac-admin-dashboard.php')) {
    require_once get_stylesheet_directory() . '/inc/naac-admin-dashboard.php';
}

/* ==========================================
   18B. STUDENT ZONE & ACADEMIC RESOURCES SUITE BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/student-zone-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/student-zone-builder.php';
}

/* ==========================================
   18C. EXTRA CURRICULAR SUITE MASTER BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/extra-curricular-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/extra-curricular-builder.php';
}

/* ==========================================
   18D. CONTACT US MASTER SUITE BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/contact-us-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/contact-us-builder.php';
}

/* ==========================================
   18E. MASTER UTILITY SUITE BUILDER (LIBRARY, E-RESOURCES, ANTI-RAGGING, TENDER, HELP DESK)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/utility-suite-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/utility-suite-builder.php';
}

/* ==========================================
   18E-2. RESEARCH & PUBLICATIONS ENGINE & REPOSITORY BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/research-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/research-builder.php';
}

/* ==========================================
   18E-3. ONE NATION ONE SUBSCRIPTION (ONOS) NATIONAL GATEWAY BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/onos-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/onos-builder.php';
}

/* ==========================================
   18F. NOTICES & OFFICIAL CIRCULARS MASTER REPOSITORY BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/notices-page-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/notices-page-builder.php';
}

/* ==========================================
   18G. CAMPUS EVENTS & ACTIVITIES MASTER BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/events-page-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/events-page-builder.php';
}

/* ==========================================
   18H. INSTITUTIONAL PORTAL LOGIN & AUTHENTICATION BUILDER
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/portal-login-builder.php')) {
    require_once get_stylesheet_directory() . '/inc/portal-login-builder.php';
}



/* ==========================================
   18J. MASTER DYNAMIC SHORTCODES REGISTRY (ALL 63 PAGES)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/shortcodes-registry.php')) {
    require_once get_stylesheet_directory() . '/inc/shortcodes-registry.php';
}

/* ==========================================
   18K. UNIVERSAL CREATIVE PAGE PRELOADER (10/10 STANDARD)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/preloader.php')) {
    require_once get_stylesheet_directory() . '/inc/preloader.php';
}

/* ==========================================
   18L. 10/10 PERFORMANCE & CORE WEB VITALS OPTIMIZATION ENGINE
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/performance-engine.php')) {
    require_once get_stylesheet_directory() . '/inc/performance-engine.php';
}
if (file_exists(get_stylesheet_directory() . '/inc/cache-control-engine.php')) {
    require_once get_stylesheet_directory() . '/inc/cache-control-engine.php';
}

/* ==========================================
   18M. HARDENED SECURITY & ANTI-BOT DEFENSE ENGINE
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/security-engine.php')) {
    require_once get_stylesheet_directory() . '/inc/security-engine.php';
}

/* ==========================================
   18M2. CENTRALIZED MAIL & FALLBACK ENGINE (ALL FORMS & OTP)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/mail-fallback-engine.php')) {
    require_once get_stylesheet_directory() . '/inc/mail-fallback-engine.php';
}

/* ==========================================
   18N. UNIVERSAL EMAIL OTP VALIDATION ENGINE (ALL FORMS)
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/email-otp-engine.php')) {
    require_once get_stylesheet_directory() . '/inc/email-otp-engine.php';
}

/* ==========================================
   18N2. UNIVERSAL GRIEVANCE & ENQUIRY TRACKING GATEWAY
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/tracking-portal.php')) {
    require_once get_stylesheet_directory() . '/inc/tracking-portal.php';
}

/* ==========================================
   18O. 10/10 MASTER SEO, SCHEMA & CRAWL OPTIMIZATION ENGINE
   ========================================== */
if (file_exists(get_stylesheet_directory() . '/inc/seo-engine.php')) {
    require_once get_stylesheet_directory() . '/inc/seo-engine.php';
}


add_filter('template_include', function($template) {
    if (is_page('login') || is_page('portal-login')) {
        $custom_tpl = get_stylesheet_directory() . '/template-login.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('portal')) {
        $custom_tpl = get_stylesheet_directory() . '/template-portal.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('photo-gallery') || is_page('gallery')) {
        $custom_tpl = get_stylesheet_directory() . '/template-photo-gallery.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('video-gallery') || is_page('videos')) {
        $custom_tpl = get_stylesheet_directory() . '/template-video-gallery.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('media') || is_page('press-media') || is_page('media-gallery') || is_page('press-release')) {
        $custom_tpl = get_stylesheet_directory() . '/template-press-media.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('principal-desk')) {
        $custom_tpl = get_stylesheet_directory() . '/template-principal-desk.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('succession-list') || is_page('principal-succession')) {
        $custom_tpl = get_stylesheet_directory() . '/template-succession-list.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('glance') || is_page('college-at-a-glance')) {
        $custom_tpl = get_stylesheet_directory() . '/template-glance.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('vision-mission')) {
        $custom_tpl = get_stylesheet_directory() . '/template-vision-mission.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('rules-regulations')) {
        $custom_tpl = get_stylesheet_directory() . '/template-rules-regulations.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('code-of-conduct')) {
        $custom_tpl = get_stylesheet_directory() . '/template-code-of-conduct.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('core-values')) {
        $custom_tpl = get_stylesheet_directory() . '/template-core-values.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }
    if (is_page('master-plan')) {
        $custom_tpl = get_stylesheet_directory() . '/template-master-plan.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }

    // NAAC 10-Chapter Suite Routing
    $naac_slugs = [
        'aishe', 'aishe-certificate',
        'sss', 'student-satisfaction-survey', 'student-survey',
        'iiqa',
        'iqac',
        'aqar',
        'naac-core-values',
        'ssr', 'self-study-reports',
        'ugc-2f-12b', 'ugc-2f', '2f-12b', 'ugc',
        'university-affiliation', 'affiliation',
        'nirf'
    ];
    foreach ($naac_slugs as $n_slug) {
        if (is_page($n_slug)) {
            $custom_tpl = get_stylesheet_directory() . '/template-naac-suite.php';
            if (file_exists($custom_tpl)) {
                return $custom_tpl;
            }
        }
    }

    // Administration Suite Routing
    if (is_page('our-administration') || is_page('administration')) {
        $custom_tpl = get_stylesheet_directory() . '/template-our-administration.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }
    if (is_page('university-administration')) {
        $custom_tpl = get_stylesheet_directory() . '/template-university-administration.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }
    if (is_page('committees-cells') || is_page('committees') || is_page('cells')) {
        $custom_tpl = get_stylesheet_directory() . '/template-committees-cells.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }

    // Student Zone & Academic Resources Suite Routing
    $sz_slugs = [
        'admission-procedure', 'sanctioned-seat', 'sanctioned-seats',
        'enrollment-position', 'college-prospectus',
        'syllabus', 'ug-syllabus', 'pg-syllabus', 'fyugp-syllabus', 'old-syllabus',
        'study-material', 'outcomes', 'result', 'holiday-calendar',
        'student-testimonial', 'student-testimonials',
        'alumni-registration', '360-degree-feedback', '360-feedback',
        'feedback-report', '360-feedback-report', 'student-grievance'
    ];
    foreach ($sz_slugs as $sz_slug) {
        if (is_page($sz_slug)) {
            $custom_tpl = get_stylesheet_directory() . '/template-student-zone.php';
            if (file_exists($custom_tpl)) {
                return $custom_tpl;
            }
        }
    }

    // Extra Curricular Suite Routing
    $ec_slugs = [
        'facility', 'facilities', 'campus-facility',
        'media',
        'nss', 'national-service-scheme',
        'ncc', 'national-cadet-corps',
        'sports', 'sports-games',
        'cultural-activities', 'cultural', 'cultural-activity'
    ];
    foreach ($ec_slugs as $ec_slug) {
        if (is_page($ec_slug)) {
            $custom_tpl = get_stylesheet_directory() . '/template-extra-curricular.php';
            if (file_exists($custom_tpl)) {
                return $custom_tpl;
            }
        }
    }

    // Contact Us Suite Routing
    if (is_page('contact-us') || is_page('contact')) {
        $custom_tpl = get_stylesheet_directory() . '/template-contact-us.php';
        if (file_exists($custom_tpl)) {
            return $custom_tpl;
        }
    }

    // Utility Suite Routing (Library, e-Resources, Anti-Ragging, Tender, Help Desk)
    if (is_page('library')) {
        $custom_tpl = get_stylesheet_directory() . '/template-library.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }
    if (is_page('e-resources') || is_page('eresources')) {
        $custom_tpl = get_stylesheet_directory() . '/template-e-resources.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }
    if (is_page('anti-ragging-cell') || is_page('anti-ragging')) {
        $custom_tpl = get_stylesheet_directory() . '/template-anti-ragging.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }
    if (is_page('tender') || is_page('tenders')) {
        $custom_tpl = get_stylesheet_directory() . '/template-tender.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }
    if (is_page('help-desk') || is_page('helpdesk')) {
        $custom_tpl = get_stylesheet_directory() . '/template-help-desk.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }

    // Notices & Official Circulars Routing
    if (is_page('notices') || is_page('notice-board') || is_page('circulars')) {
        $custom_tpl = get_stylesheet_directory() . '/template-notices.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }

    // Campus Events & Activities Routing
    if (is_page('events') || is_page('college-events') || is_page('activities')) {
        $custom_tpl = get_stylesheet_directory() . '/template-events.php';
        if (file_exists($custom_tpl)) return $custom_tpl;
    }

    return $template;
}, 99);

/* ==========================================
   19. FACULTY GROUP & ROUTE ALIASES REDIRECTS
   ========================================== */
function mvc_handle_faculty_group_redirects() {
    $uri = trim($_SERVER['REQUEST_URI'] ?? '', '/');
    // Remove any query strings
    $uri_parts = explode('?', $uri);
    $clean_uri = $uri_parts[0];

    // Redirect /contact to canonical /contact-us/
    if ($clean_uri === 'contact') {
        wp_safe_redirect(home_url('/contact-us/'), 301);
        exit;
    }

    // Redirect /principal-succession to /succession-list
    if ($clean_uri === 'principal-succession') {
        wp_safe_redirect(home_url('/succession-list/'), 301);
        exit;
    }

    // Redirect NAAC aliases to canonical routes
    if ($clean_uri === 'ugc-2f' || $clean_uri === '2f-12b') {
        wp_safe_redirect(home_url('/ugc-2f-12b/'), 301);
        exit;
    }
    if ($clean_uri === 'affiliation') {
        wp_safe_redirect(home_url('/university-affiliation/'), 301);
        exit;
    }
    if ($clean_uri === 'self-study-report' || $clean_uri === 'self-study-reports') {
        wp_safe_redirect(home_url('/ssr/'), 301);
        exit;
    }
    if ($clean_uri === 'administration') {
        wp_safe_redirect(home_url('/our-administration/'), 301);
        exit;
    }

    // Redirect Student Zone aliases to canonical routes
    if ($clean_uri === 'sanctioned-seats') {
        wp_safe_redirect(home_url('/sanctioned-seat/'), 301);
        exit;
    }
    if ($clean_uri === 'student-testimonials') {
        wp_safe_redirect(home_url('/student-testimonial/'), 301);
        exit;
    }
    if ($clean_uri === '360-feedback') {
        wp_safe_redirect(home_url('/360-degree-feedback/'), 301);
        exit;
    }
    if ($clean_uri === '360-feedback-report') {
        wp_safe_redirect(home_url('/feedback-report/'), 301);
        exit;
    }

    // Redirect Extra Curricular aliases to canonical routes
    if ($clean_uri === 'facilities' || $clean_uri === 'campus-facility') {
        wp_safe_redirect(home_url('/facility/'), 301);
        exit;
    }
    if ($clean_uri === 'cultural' || $clean_uri === 'cultural-activity') {
        wp_safe_redirect(home_url('/cultural-activities/'), 301);
        exit;
    }
    if ($clean_uri === 'sports-games') {
        wp_safe_redirect(home_url('/sports/'), 301);
        exit;
    }

    // Redirect Utility Suite aliases to canonical routes
    if ($clean_uri === 'tenders') {
        wp_safe_redirect(home_url('/tender/'), 301);
        exit;
    }
    if ($clean_uri === 'anti-ragging') {
        wp_safe_redirect(home_url('/anti-ragging-cell/'), 301);
        exit;
    }
    if ($clean_uri === 'helpdesk') {
        wp_safe_redirect(home_url('/help-desk/'), 301);
        exit;
    }
    if ($clean_uri === 'eresources') {
        wp_safe_redirect(home_url('/e-resources/'), 301);
        exit;
    }
    if ($clean_uri === 'notice-board' || $clean_uri === 'circulars') {
        wp_safe_redirect(home_url('/notices/'), 301);
        exit;
    }
    if ($clean_uri === 'college-events' || $clean_uri === 'activities') {
        wp_safe_redirect(home_url('/events/'), 301);
        exit;
    }

    if (preg_match('#^department-details/([^/]+)$#i', $clean_uri, $m)) {
        $slug = sanitize_title($m[1]);
        $aliases = array(
            'social-sciences' => 'social-science',
            'sciences'        => 'science',
            'commerce'        => 'vocational',
            'vocational-studies' => 'vocational'
        );
        if (isset($aliases[$slug])) {
            $slug = $aliases[$slug];
        }

        $term = get_term_by('slug', $slug, 'faculty-group');
        if ($term && !is_wp_error($term)) {
            $term_link = get_term_link($term);
            if ($term_link && !is_wp_error($term_link)) {
                wp_safe_redirect($term_link, 301);
                exit;
            }
        }
    }
}
add_action('template_redirect', 'mvc_handle_faculty_group_redirects', 1);

add_action('template_redirect', 'mvc_canonical_slug_redirects', 1);
function mvc_canonical_slug_redirects() {
    $uri  = trim($_SERVER['REQUEST_URI'] ?? '', '/');
    $path = strtok($uri, '?');

    $redirects = array(
        'press-media'              => '/media/',
        'self-study-report'        => '/ssr/',
        'grievance-redressal-cell' => '/student-grievance/',
        'grievance-redressal'      => '/student-grievance/',
        'grievance-cell'           => '/student-grievance/',
        'academic-departments'     => '/department-details/',
        'departments'              => '/department-details/',
        'department/urdu'          => '/faculty-group/humanities/',
        'department/geography'     => '/faculty-group/social-science/',
    );

    if (isset($redirects[$path])) {
        wp_safe_redirect(home_url($redirects[$path]), 301);
        exit;
    }
}

/* ==========================================
   15. FORCE FULL-WIDTH STRETCHED LAYOUT ACROSS ALL PAGES (NO CONTAINER BOXING)
   ========================================== */
add_filter('astra_get_content_layout', function($layout) {
    return 'page-builder';
}, 99);

add_filter('astra_page_layout', function($layout) {
    return 'no-sidebar';
}, 99);

add_action('wp_head', function() {
    ?>
    <style id="mvc-universal-full-width-framework">
        /* 1. Universal Layout Shell Breakout (Astra & WordPress Core) */
        @media (min-width: 1px) {
            html, body, #page, #content, .site-content, .ast-container, #primary, #main, .site-main, .entry-content,
            .ast-plain-container, .ast-page-builder-template, .ast-no-sidebar, .ast-separate-container,
            .site-content .ast-container, body.page .site-content .ast-container, body.page #content .ast-container,
            body.page-template-template-login-php .site-content .ast-container,
            body.page-template-template-login-php #primary,
            body.page-template-template-login-php #main {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        #primary, #main {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 2. Full-Width Background Section Bands */
        .mvc-custom-header,
        .mvc-portal-hero,
        .mvc-portal-wrapper,
        .mvc-portal-body-section,
        .mvc-grand-footer,
        .mvc-top-bar,
        .mvc-nav-strip,
        .mvc-ab-hero,
        .mvc-notices-hero,
        .mvc-events-hero,
        .mvc-hub-hero,
        .mvc-util-hero,
        .mvc-sc-hero {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* 3. Universal 100% Full-Width Fluid Containers with Subtle Responsive Margins */
        .mvc-container,
        .mvc-portal-container,
        .mvc-portal-hero-container,
        .mvc-portal-layout-unified,
        .mvc-portal-services-shelf,
        .mvc-ab-hero-inner,
        .mvc-ab-main,
        .mvc-gl-container,
        .mvc-vm-container,
        .mvc-rr-container,
        .mvc-cc-container,
        .mvc-cv-container,
        .mvc-mp-container,
        .mvc-admin-container,
        .mvc-univ-container,
        .mvc-comm-container,
        .mvc-desk-container,
        .mvc-sc-container,
        .mvc-sc-main,
        .mvc-sc-hero-inner,
        .mvc-staff-container,
        .mvc-sp-container,
        .mvc-dept-container,
        .mvc-dept-hub-container,
        .mvc-dept-hero-container,
        .mvc-hub-container,
        .mvc-calendar-container,
        .mvc-naac-container,
        .mvc-naac-hero-container,
        .mvc-sz-container,
        .mvc-sz-hero-container,
        .mvc-nt-container,
        .mvc-notices-container,
        .mvc-notices-hero-inner,
        .mvc-ev-container,
        .mvc-events-container,
        .mvc-events-hero-inner,
        .mvc-contact-container,
        .mvc-gallery-container,
        .mvc-media-hero-container,
        .mvc-media-nav-inner,
        .mvc-media-body-container,
        .mvc-util-container,
        .mvc-util-hero-inner,
        .mvc-footer-container,
        .mvc-fg-container,
        .mvc-web-slider-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            margin-left: auto !important;
            margin-right: auto !important;
            box-sizing: border-box !important;
        }

        /* 4. Prevent Double Horizontal Padding on Nested Structural Children */
        .mvc-ab-main .mvc-gl-container,
        .mvc-ab-main .mvc-vm-container,
        .mvc-ab-main .mvc-rr-container,
        .mvc-ab-main .mvc-cc-container,
        .mvc-ab-main .mvc-cv-container,
        .mvc-ab-main .mvc-mp-container,
        .mvc-portal-container .mvc-portal-layout-unified,
        .mvc-portal-layout-unified .mvc-portal-master-gateway,
        .mvc-notices-container .mvc-nt-body-layout,
        .mvc-events-container .mvc-ev-body-layout,
        .mvc-hub-container .mvc-hub-main-layout {
            padding-left: 0 !important;
            padding-right: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* 5. Master Portal Dual-Wing Gateway */
        .mvc-portal-master-gateway {
            display: grid !important;
            grid-template-columns: minmax(360px, 420px) 1fr !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto 48px auto !important;
            box-sizing: border-box !important;
        }
        @media (max-width: 900px) {
            .mvc-portal-master-gateway {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    <?php
}, 999);