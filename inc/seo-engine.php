<?php
/**
 * RetailCoder StoreFront [RC:StoreFront] Master 10/10 SEO & Structured Data Engine
 *
 * Implements comprehensive JSON-LD Schema (CollegeOrUniversity, EducationalOrganization,
 * BreadcrumbList, WebSite, NewsArticle), high-intent meta keywords, OpenGraph/Twitter Cards,
 * and search engine crawling optimization.
 *
 * All structured data entities, canonical URLs, and sitemaps resolve dynamically to the
 * official institutional production domain (https://www.mvcollegebuxar.ac.in) or administrator-configured
 * canonical URL, eliminating hardcoded local development domains.
 *
 * @package Astra_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Canonical Production Site URL for Structured Data, SEO & Crawlers.
 * Prioritizes Global Settings ('canonical_site_url'), auto-resolves staging to authentic production,
 * and falls back cleanly to the active domain in live environments.
 *
 * @param string $path Path to append.
 * @return string Fully qualified canonical URL.
 */
function mvc_get_canonical_site_url($path = '/') {
    // 1. Check Global Settings (ACF Option or mvc_global_settings)
    $canonical = mvc_get_global_setting('canonical_site_url');
    if (empty($canonical)) {
        $canonical = mvc_get_global_setting('production_site_url');
    }

    // 2. If running on local staging (.local, localhost, 127.0.0.1, or CLI), use official institutional production domain
    if (empty($canonical)) {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $home = home_url();
        if (
            strpos($host, '.local') !== false || 
            strpos($host, 'localhost') !== false || 
            strpos($host, '127.0.0.1') !== false ||
            strpos($home, '.local') !== false ||
            strpos($home, 'localhost') !== false ||
            strpos($home, '127.0.0.1') !== false
        ) {
            $canonical = 'https://www.mvcollegebuxar.ac.in';
        } else {
            $canonical = $home;
        }
    }

    $canonical = rtrim($canonical, '/');
    $path = '/' . ltrim($path, '/');
    return ($path === '/') ? $canonical . '/' : $canonical . $path;
}

/**
 * Convert any internal or staging URL to the Canonical Production Domain for Schema/Metadata.
 *
 * @param string $url Internal or local URL.
 * @return string Canonicalized production URL.
 */
function mvc_to_canonical_url($url) {
    if (empty($url)) {
        return mvc_get_canonical_site_url('/');
    }
    $local_home = rtrim(home_url(), '/');
    $canonical_home = rtrim(mvc_get_canonical_site_url('/'), '/');
    if ($local_home !== $canonical_home) {
        return str_replace($local_home, $canonical_home, $url);
    }
    return $url;
}

/**
 * 1. Output Master JSON-LD Structured Data in wp_head
 */
function mvc_output_master_json_ld_schema() {
    $site_url = mvc_get_canonical_site_url('/');
    $college_name = mvc_get_global_setting('college_name', 'Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)');
    $college_name_hi = mvc_get_global_setting('college_name_hi', 'महर्षि विश्वामित्र महाविद्यालय, बक्सर');
    $phone = mvc_get_global_setting('college_phone', '06183-222035');
    $email = mvc_get_global_setting('college_email', mvc_get_principal_email());
    $logo_url = mvc_to_canonical_url(get_stylesheet_directory_uri() . '/assets/images/retailcoder-logo.png');
    $hero_image = mvc_to_canonical_url(get_stylesheet_directory_uri() . '/assets/images/mv-college-hero.jpg');

    // 1. Core Educational Organization / CollegeOrUniversity Schema
    $college_schema = array(
        '@context'            => 'https://schema.org',
        '@type'               => array('CollegeOrUniversity', 'EducationalOrganization'),
        '@id'                 => $site_url . '#college',
        'name'                => $college_name,
        'alternateName'       => array(
            'M.V. College, Buxar',
            'MV College Buxar',
            'Maharshi Vishwamitra College',
            $college_name_hi
        ),
        'url'                 => $site_url,
        'logo'                => $logo_url,
        'image'               => $hero_image,
        'description'         => 'Premier constituent degree college of Veer Kunwar Singh University (VKSU), Ara. Established on 11 June 1958, providing undergraduate and postgraduate programs across Arts, Science, Commerce, and Vocational streams in Buxar, Bihar.',
        'foundingDate'        => '1958-06-11',
        'parentOrganization'  => array(
            '@type' => 'CollegeOrUniversity',
            'name'  => 'Veer Kunwar Singh University (VKSU), Ara',
            'url'   => 'https://vksu.ac.in/'
        ),
        'address'             => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Charitravan, River Ganga Front',
            'addressLocality' => 'Buxar',
            'addressRegion'   => 'Bihar',
            'postalCode'      => '802101',
            'addressCountry'  => 'IN'
        ),
        'geo'                 => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => 25.5684,
            'longitude' => 83.9777
        ),
        'telephone'           => $phone,
        'email'               => $email,
        'sameAs'              => array(
            'https://www.facebook.com/mvcollegebuxar',
            'https://twitter.com/mvcollegebuxar',
            'https://www.youtube.com/@mvcollegebuxar'
        ),
        'hasCredential'       => array(
            'UGC Recognized & AICTE Approved Institution',
            'UGC Section 2(f) and 12(B) Recognition',
            'AISHE Institutional Code C-27158',
            'Constituent Unit of VKSU Ara'
        )
    );

    // 2. WebSite Schema (Homepage Search Box)
    $website_schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        '@id'             => $site_url . '#website',
        'url'             => $site_url,
        'name'            => $college_name,
        'publisher'       => array(
            '@id' => $site_url . '#college'
        ),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => $site_url . '?s={search_term_string}',
            'query-input' => 'required name=search_term_string'
        )
    );

    echo "\n<!-- Maharshi Vishwamitra Mahavidyalaya - 10/10 Master Institutional Schema (JSON-LD) -->\n";
    echo '<script type="application/ld+json">' . json_encode($college_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

    if (is_front_page() || is_home()) {
        echo '<script type="application/ld+json">' . json_encode($website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }

    // 3. BreadcrumbList Schema on All Interior Pages
    if (!is_front_page() && !is_home()) {
        global $post;
        $breadcrumbs = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Home',
                    'item'     => $site_url
                )
            )
        );

        $pos = 2;
        if (is_page()) {
            if ($post && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs['itemListElement'][] = array(
                    '@type'    => 'ListItem',
                    'position' => $pos++,
                    'name'     => get_the_title($parent_id),
                    'item'     => mvc_to_canonical_url(get_permalink($parent_id))
                );
            }
            $breadcrumbs['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $pos,
                'name'     => get_the_title(),
                'item'     => mvc_to_canonical_url(get_permalink())
            );
        } elseif (is_singular('department')) {
            $breadcrumbs['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => 'Departments',
                'item'     => mvc_get_canonical_site_url('/department-details/')
            );
            $breadcrumbs['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $pos,
                'name'     => get_the_title(),
                'item'     => mvc_to_canonical_url(get_permalink())
            );
        } elseif (is_singular('notice')) {
            $breadcrumbs['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => 'Notices',
                'item'     => mvc_get_canonical_site_url('/notices/')
            );
            $breadcrumbs['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $pos,
                'name'     => get_the_title(),
                'item'     => mvc_to_canonical_url(get_permalink())
            );
        }

        echo '<script type="application/ld+json">' . json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }

    // 4. Single Notice NewsArticle Schema
    if (is_singular('notice')) {
        global $post;
        $memo = get_post_meta($post->ID, 'memo_number', true) ?: ('MVC/NOT/' . $post->ID);
        $notice_schema = array(
            '@context'         => 'https://schema.org',
            '@type'            => 'NewsArticle',
            'mainEntityOfPage' => mvc_to_canonical_url(get_permalink($post->ID)),
            'headline'         => get_the_title($post->ID),
            'description'      => wp_strip_all_tags(get_the_excerpt($post->ID)),
            'datePublished'    => get_the_date('c', $post->ID),
            'dateModified'     => get_the_modified_date('c', $post->ID),
            'publisher'        => array(
                '@id' => $site_url . '#college'
            ),
            'author'           => array(
                '@type' => 'EducationalOrganization',
                'name'  => $college_name
            ),
            'identifier'       => $memo
        );
        echo '<script type="application/ld+json">' . json_encode($notice_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'mvc_output_master_json_ld_schema', 2);

/**
 * 2. Universal High-Intent Meta Keywords & Language Declaration
 */
function mvc_output_high_intent_seo_meta_tags() {
    global $post;

    // Master Educational Keyword Pool
    $global_keywords = array(
        'M.V. College Buxar',
        'MV College Buxar',
        'Maharshi Vishwamitra Mahavidyalaya',
        'महर्षि विश्वामित्र महाविद्यालय बक्सर',
        'VKSU Constituent College Buxar',
        'Veer Kunwar Singh University Ara',
        'Best Degree College in Buxar',
        'UG Admission Buxar Bihar 2026',
        'FYUGP NEP 2020 Syllabus VKSU',
        'MV College Merit List',
        'MV College Exam Results',
        'MV College Notice Circular',
        'UGC Recognized College Bihar',
        'UGC 2f 12b Recognized College'
    );

    $page_keywords = '';
    if (is_singular() && $post) {
        $meta_kw = get_post_meta($post->ID, 'mvc_seo_keywords', true);
        $yoast_kw = get_post_meta($post->ID, '_yoast_wpseo_focuskw', true);
        if (!empty($meta_kw)) {
            $page_keywords = $meta_kw;
        } elseif (!empty($yoast_kw)) {
            $page_keywords = $yoast_kw;
        }
    }

    $all_kw = !empty($page_keywords) ? ($page_keywords . ', ' . implode(', ', array_slice($global_keywords, 0, 8))) : implode(', ', $global_keywords);

    echo "\n<!-- Maharshi Vishwamitra Mahavidyalaya - 10/10 SEO Meta Keywords & Geo Tags -->\n";
    echo '<meta name="keywords" content="' . esc_attr($all_kw) . '">' . "\n";
    echo '<meta name="geo.region" content="IN-BR">' . "\n";
    echo '<meta name="geo.placename" content="Buxar, Bihar">' . "\n";
    echo '<meta name="geo.position" content="25.5684;83.9777">' . "\n";
    echo '<meta name="ICBM" content="25.5684, 83.9777">' . "\n";

    // Social OpenGraph Alternate Locale
    echo '<meta property="og:locale" content="en_IN">' . "\n";
    echo '<meta property="og:locale:alternate" content="hi_IN">' . "\n";
}
add_action('wp_head', 'mvc_output_high_intent_seo_meta_tags', 3);

/**
 * 3. Enhanced Robots.txt with Search Engine Crawl Optimization
 */
add_filter('robots_txt', function($output, $public) {
    if ('0' === (string) $public) {
        return $output;
    }

    $sitemap_url = mvc_get_canonical_site_url('/sitemap_index.xml');

    $robots = "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Disallow: /wp-admin/\n";
    $robots .= "Disallow: /wp-includes/\n";
    $robots .= "Disallow: /trackback/\n";
    $robots .= "Disallow: /*?s=\n";
    $robots .= "Disallow: /*?author=\n";
    $robots .= "Allow: /wp-admin/admin-ajax.php\n\n";

    $robots .= "User-agent: Googlebot\n";
    $robots .= "Allow: /\n\n";

    $robots .= "User-agent: Bingbot\n";
    $robots .= "Allow: /\n\n";

    $robots .= "Sitemap: " . esc_url($sitemap_url) . "\n";

    return $robots;
}, 99, 2);
