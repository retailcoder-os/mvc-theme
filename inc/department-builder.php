<?php
/**
 * M.V. College, Buxar - Universal Department Engine & Page Builder
 * 10/10 Standard: Mobile-First, Single Unified Navigation, Zero Redundant Pills, 100% Dynamic
 * 
 * Features:
 * - Single Unified Tab Bar: Sticky, touch-scrollable, mobile-optimized (Zero duplicate pills)
 * - Compact High-Prestige Hero: Crisp royal navy & maroon with bilingual Devanagari insignia
 * - Streamlined Metrics Strip: Minimalist, responsive 4-stat bar taking minimal vertical height
 * - 100% Dynamic & Admin Editable: Reads live DB & ACF Free fields without hardcoding
 * - Instant Tab Pane Switching: Fast, focused, clean mobile UX with URL hash synchronization
 * - Elite Faculty Scholar Directory: HOD pinned first with royal gold crown ribbon, executive monograms
 * - Modern Document Vault: 1-click local PDF downloads with mobile swipe hints
 * - 10/10 SEO: Single <h1> per page, microdata breadcrumbs, Schema.org JSON-LD structured data
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Format academic establishment text (e.g. "UG1958 &PG2007." -> "UG: 1958 • PG: 2007")
 */
function mvc_clean_academic_year($raw) {
    if (empty($raw)) return 'As per VKSU Norms';
    $clean = trim($raw, " .\t\n\r\0\x0B");
    $clean = preg_replace('/UG\s*(\d+)/i', 'UG: $1', $clean);
    $clean = preg_replace('/PG\s*(\d+)/i', 'PG: $1', $clean);
    $clean = str_replace('&', ' • ', $clean);
    return $clean;
}

/**
 * Format sanctioned intake seats (e.g. "UG125 &PG60" -> "UG: 125 Seats • PG: 60 Seats")
 */
function mvc_clean_sanctioned_seats($raw) {
    if (empty($raw)) return 'As per University Sanction';
    $clean = trim($raw, " .\t\n\r\0\x0B");
    $clean = preg_replace('/UG\s*(\d+)/i', 'UG: $1 Seats', $clean);
    $clean = preg_replace('/PG\s*(\d+)/i', 'PG: $1 Seats', $clean);
    $clean = str_replace('&', ' • ', $clean);
    return $clean;
}

/**
 * Generate 2-3 letter executive monogram initials from a name (e.g. "Dr. Amarjeet Kumar Singh" -> "AKS")
 */
function mvc_get_faculty_monogram($name) {
    $clean_name = preg_replace('/^(Prof\.|Dr\.|Sri|Smt\.|Shri|Md\.|Prof\.\s*\(Dr\.\))\s+/i', '', trim($name));
    $words = preg_split('/\s+/', $clean_name);
    $initials = '';
    foreach ($words as $w) {
        if (!empty($w)) {
            $char = mb_substr($w, 0, 1);
            if (preg_match('/[A-Za-z]/', $char)) {
                $initials .= strtoupper($char);
            }
        }
        if (strlen($initials) >= 3) break;
    }
    return !empty($initials) ? $initials : 'FAC';
}

/**
 * Map department slugs to authentic Hindi Devanagari titles
 */
function mvc_get_department_hindi_name($slug) {
    $map = array(
        'hindi'                 => 'स्नातकोत्तर हिंदी विभाग',
        'english'               => 'अंग्रेजी विभाग',
        'sanskrit'              => 'संस्कृत विभाग',
        'philosophy'            => 'दर्शनशास्त्र विभाग',
        'history'               => 'स्नातकोत्तर इतिहास विभाग',
        'political-science'     => 'स्नातकोत्तर राजनीति विज्ञान विभाग',
        'economics'             => 'अर्थशास्त्र विभाग',
        'psychology'            => 'स्नातकोत्तर मनोविज्ञान विभाग',
        'physics'               => 'भौतिकी विभाग',
        'chemistry'             => 'रसायन विज्ञान विभाग',
        'mathematics'           => 'गणित विभाग',
        'botany'                => 'वनस्पति विज्ञान विभाग',
        'zoology'               => 'जंतु विज्ञान विभाग',
        'computer-applications' => 'कंप्यूटर अनुप्रयोग विभाग (BCA)',
        'business-management'   => 'व्यवसाय प्रबंधन विभाग (BBA)'
    );
    return isset($map[$slug]) ? $map[$slug] : 'शैक्षणिक विभाग';
}

/**
 * Intelligently parse, clean, and structure raw department about text into an editorial showcase.
 * Filters out raw table dumps and awkward line breaks, generating a vision quote, lead statement, and balanced paragraphs.
 */
function mvc_clean_and_structure_about($raw_html, $dept_title, $est_year = '') {
    if (empty($raw_html)) {
        return array(
            'quote' => 'Dedicated to academic excellence, intellectual inquiry, and ethical leadership under the National Education Policy (NEP 2020) and CBCS curriculum of Veer Kunwar Singh University (VKSU), Ara.',
            'lead' => 'Welcome to the ' . esc_html($dept_title) . ' at Maharshi Vishwamitra Mahavidyalaya, Buxar. Our department provides comprehensive undergraduate education, combining theoretical depth with active mentorship and modern pedagogical practices.',
            'paragraphs' => array(
                'Following the Choice Based Credit System (CBCS) and National Education Policy (NEP 2020) framework, students engage in systematic coursework, tutorials, and seminars designed to build conceptual clarity and professional competencies.'
            )
        );
    }

    // Strip outer container
    $content = preg_replace('#^<div class="mvc-dept-prose-extracted">\s*#i', '', $raw_html);
    $content = preg_replace('#\s*</div>$#i', '', $content);

    // Remove trailing raw metadata dumps (e.g., Course Establishment Year, Teacher Sanctioned Seat, etc.)
    $meta_strip_patterns = array(
        '#(Course\s+Establishment\s+Year|Teacher\s+Sanctioned\s+Seat|Student\s+Sanctioned\s+Seat|Establishment\s+Year|Sanctioned\s+Seat).*$#is',
        '#(पाठ्यक्रम\s+स्थापना\s+वर्ष|शिक्षक\s+स्वीकृत\s+सीट|छात्र\s+स्वीकृत\s+सीट).*$#is'
    );
    foreach ($meta_strip_patterns as $pat) {
        $content = preg_replace($pat, '', $content);
    }

    // Clean inline breaks that divide sentences awkwardly (never use closing php tag in regex or comments)
    $content = preg_replace('#<br[^>]*>\s*#i', ' ', $content);

    // Normalize whitespace
    $content = preg_replace('#[ \t]+#', ' ', $content);

    // Split paragraphs
    $split = preg_split('#</p>\s*<p>|\n\s*\n#i', $content);
    $clean_paras = array();
    foreach ($split as $p) {
        $p = strip_tags($p);
        $p = trim($p);
        if (!empty($p) && mb_strlen($p) > 25) {
            $clean_paras[] = $p;
        }
    }

    $protect_abbr = function($str) {
        return preg_replace_callback('/(M\.V\.|Dr\.|Prof\.|Smt\.|Shri|U\.G\.|P\.G\.|i\.e\.|e\.g\.)/i', function($m) {
            return str_replace('.', '__DOT__', $m[0]);
        }, $str);
    };
    $unprotect_abbr = function($str) {
        return str_replace('__DOT__', '.', $str);
    };

    // If all text ended up in a single paragraph, split by sentence boundaries for reading elegance
    if (count($clean_paras) === 1 && mb_strlen($clean_paras[0]) > 250) {
        $single = $clean_paras[0];
        $protected = $protect_abbr($single);
        $raw_sentences = preg_split('/(?<=[.!?])\s+/u', $protected);
        $sentences = array_map($unprotect_abbr, $raw_sentences);
        if (count($sentences) >= 3) {
            $chunk1 = array_slice($sentences, 0, 2);
            $chunk2 = array_slice($sentences, 2, 2);
            $chunk3 = array_slice($sentences, 4);

            $clean_paras = array();
            if (!empty($chunk1)) $clean_paras[] = implode(' ', $chunk1);
            if (!empty($chunk2)) $clean_paras[] = implode(' ', $chunk2);
            if (!empty($chunk3)) $clean_paras[] = implode(' ', $chunk3);
        }
    }

    // Extract quote
    $quote = '';
    if (!empty($clean_paras)) {
        $first_para = $clean_paras[0];
        $protected = $protect_abbr($first_para);
        $raw_sentences = preg_split('/(?<=[.!?])\s+/u', $protected);
        $sentences = array_map($unprotect_abbr, $raw_sentences);
        if (count($sentences) > 1 && mb_strlen($sentences[1]) > 35 && mb_strlen($sentences[1]) < 240) {
            $quote = trim($sentences[1]);
        } elseif (!empty($sentences[0]) && mb_strlen($sentences[0]) < 240) {
            $quote = trim($sentences[0]);
        }
    }

    if (empty($quote)) {
        $quote = 'Fostering intellectual inquiry, conceptual mastery, and ethical citizenship under the NEP 2020 curriculum of Veer Kunwar Singh University (VKSU), Ara.';
    }

    $lead = !empty($clean_paras) ? array_shift($clean_paras) : '';

    return array(
        'quote'      => $quote,
        'lead'       => $lead,
        'paragraphs' => $clean_paras
    );
}

/**
 * Master Department Page Renderer (Single Unified Nav & Mobile-First Redesign)
 */
function mvc_render_department_page($post_or_slug = null) {
    ob_start();

    // 1. Resolve Department Post
    $dept_post = null;
    if ($post_or_slug instanceof WP_Post) {
        $dept_post = $post_or_slug;
    } elseif (is_numeric($post_or_slug) && (int)$post_or_slug > 0) {
        $dept_post = get_post($post_or_slug);
    } elseif (is_string($post_or_slug) && !empty($post_or_slug)) {
        $found = get_page_by_path($post_or_slug, OBJECT, 'department');
        if (!$found) {
            $found = get_page_by_title($post_or_slug, OBJECT, 'department');
        }
        $dept_post = $found;
    } elseif (is_singular('department')) {
        global $post;
        $dept_post = $post;
    }

    if (!$dept_post) {
        $depts = get_posts(array(
            'post_type'      => 'department',
            'posts_per_page' => 1,
            'name'           => 'hindi',
            'post_status'    => 'publish'
        ));
        if (!empty($depts)) {
            $dept_post = $depts[0];
        } else {
            $depts = get_posts(array(
                'post_type'      => 'department',
                'posts_per_page' => 1,
                'post_status'    => 'publish'
            ));
            if (!empty($depts)) $dept_post = $depts[0];
        }
    }

    $dept_id = $dept_post ? $dept_post->ID : 0;
    $dept_title = $dept_post ? $dept_post->post_title : 'Academic Department';
    $dept_slug = $dept_post ? $dept_post->post_name : 'hindi';
    $dept_permalink = $dept_id ? get_permalink($dept_id) : home_url('/department/' . $dept_slug . '/');
    $dept_hindi_title = mvc_get_department_hindi_name($dept_slug);

    // Faculty Group Term & URL
    $group_terms = $dept_id ? wp_get_object_terms($dept_id, 'faculty-group') : array();
    $faculty_group = (!empty($group_terms) && !is_wp_error($group_terms)) ? $group_terms[0]->name : 'Humanities';
    $faculty_group_slug = (!empty($group_terms) && !is_wp_error($group_terms)) ? $group_terms[0]->slug : 'humanities';
    $faculty_group_url = (!empty($group_terms) && !is_wp_error($group_terms)) ? get_term_link($group_terms[0]) : home_url('/faculty-group/' . $faculty_group_slug . '/');

    // Department Taxonomy Term
    $dept_terms = $dept_id ? wp_get_object_terms($dept_id, 'dept_link') : array();
    if (empty($dept_terms) || is_wp_error($dept_terms)) {
        $dept_terms = $dept_id ? wp_get_object_terms($dept_id, 'department') : array();
    }
    $dept_term_slug = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? $dept_terms[0]->slug : $dept_slug;
    $dept_tax = taxonomy_exists('dept_link') ? 'dept_link' : 'department';

    // ACF Fields
    $about_text = $dept_id ? get_field('about_text', $dept_id) : '';
    $establishment_year_raw = $dept_id ? get_field('establishment_year', $dept_id) : '';
    $sanctioned_seats_raw = $dept_id ? get_field('sanctioned_seats', $dept_id) : '';
    $syllabus_content = $dept_id ? get_field('syllabus', $dept_id) : '';
    $study_material_content = $dept_id ? get_field('study_material', $dept_id) : '';
    $outcomes_content = $dept_id ? get_field('outcomes', $dept_id) : '';

    $formatted_est_year = mvc_clean_academic_year($establishment_year_raw);
    $formatted_seats = mvc_clean_sanctioned_seats($sanctioned_seats_raw);
    $overview_data = mvc_clean_and_structure_about($about_text, $dept_title, $formatted_est_year);

    // 2. Query Real Faculty Roster
    $faculty_query = new WP_Query(array(
        'post_type'      => 'faculty',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => $dept_tax,
                'field'    => 'slug',
                'terms'    => array_unique(array($dept_slug, $dept_term_slug))
            )
        )
    ));
    $faculty_posts = $faculty_query->posts;

    // Sort: HOD first
    $hod_post = null;
    if (!empty($faculty_posts)) {
        usort($faculty_posts, function($a, $b) {
            $hod_a = (int) get_field('is_department_head', $a->ID);
            $hod_b = (int) get_field('is_department_head', $b->ID);
            if ($hod_a !== $hod_b) {
                return $hod_b <=> $hod_a;
            }
            return strcmp($a->post_title, $b->post_title);
        });

        if ((int) get_field('is_department_head', $faculty_posts[0]->ID) === 1) {
            $hod_post = $faculty_posts[0];
        }
    }
    $faculty_count = count($faculty_posts);

    // 3. Query Real Departmental Notices
    $dept_notices = new WP_Query(array(
        'post_type'      => 'notice',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => $dept_tax,
                'field'    => 'slug',
                'terms'    => array_unique(array($dept_slug, $dept_term_slug))
            )
        )
    ));
    $notices_count = $dept_notices->found_posts;

    // 4. Query Real Departmental Events & Activities
    $dept_events_query = new WP_Query(array(
        'post_type'      => 'event',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => $dept_tax,
                'field'    => 'slug',
                'terms'    => array_unique(array($dept_slug, $dept_term_slug))
            )
        )
    ));

    // 5. Query Real Departmental Photo Galleries (via dept_link or title keyword)
    $dept_galleries_query = new WP_Query(array(
        'post_type'      => 'gallery-cat',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => $dept_tax,
                'field'    => 'slug',
                'terms'    => array_unique(array($dept_slug, $dept_term_slug))
            )
        )
    ));
    $dept_galleries = $dept_galleries_query->posts;

    if (empty($dept_galleries)) {
        $kw_clean = trim(str_ireplace(array('department of', 'p.g. department of', 'post graduate department of', 'p.g.', 'department'), '', $dept_title));
        $keywords = array_filter(array_unique(array($kw_clean, $dept_slug)));
        if ($dept_slug === 'hindi' || strtolower($kw_clean) === 'hindi') {
            $keywords[] = 'हिंदी';
            $keywords[] = 'राजभाषा';
        }

        global $wpdb;
        $like_clauses = array();
        foreach ($keywords as $kw) {
            if (!empty($kw) && mb_strlen($kw) >= 3) {
                $like_clauses[] = $wpdb->prepare("post_title LIKE %s", '%' . $wpdb->esc_like($kw) . '%');
            }
        }
        if (!empty($like_clauses)) {
            $sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'gallery-cat' AND post_status = 'publish' AND (" . implode(' OR ', $like_clauses) . ") ORDER BY post_date DESC LIMIT 6";
            $matched_ids = $wpdb->get_col($sql);
            if (!empty($matched_ids)) {
                $dept_galleries = get_posts(array(
                    'post_type'   => 'gallery-cat',
                    'post__in'    => $matched_ids,
                    'orderby'     => 'post__in',
                    'numberposts' => 6
                ));
            }
        }
    }
    $activities_count = (is_array($dept_galleries) ? count($dept_galleries) : 0) + $dept_events_query->found_posts;
    $has_activities = ($activities_count > 0);

    // Dynamic Navigation Registry (Merged Section Anchor Bar)
    $tabs = array();

    $tabs['overview'] = array(
        'id'    => 'overview',
        'label' => 'Overview',
        'icon'  => '🏛️',
        'count' => ''
    );

    $tabs['faculty'] = array(
        'id'    => 'faculty',
        'label' => 'Faculty Roster',
        'icon'  => '👨‍🏫',
        'count' => $faculty_count > 0 ? (string)$faculty_count : ''
    );

    if (!empty($syllabus_content) && trim(strip_tags($syllabus_content)) !== '') {
        $tabs['syllabus'] = array(
            'id'    => 'syllabus',
            'label' => 'Syllabus & Regulations',
            'icon'  => '📚',
            'count' => ''
        );
    }

    if (!empty($study_material_content) && trim(strip_tags($study_material_content)) !== '') {
        $tabs['study-material'] = array(
            'id'    => 'study-material',
            'label' => 'Study Material',
            'icon'  => '🎓',
            'count' => ''
        );
    }

    if (!empty($outcomes_content) && trim(strip_tags($outcomes_content)) !== '') {
        $tabs['outcomes'] = array(
            'id'    => 'outcomes',
            'label' => 'Learning Outcomes',
            'icon'  => '🎯',
            'count' => ''
        );
    }

    $tabs['activities'] = array(
        'id'    => 'activities',
        'label' => 'Department Activity',
        'icon'  => '📸',
        'count' => $activities_count > 0 ? (string)$activities_count : ''
    );

    $tabs['notices'] = array(
        'id'    => 'notices',
        'label' => 'Dept Notice Board',
        'icon'  => '📢',
        'count' => $notices_count > 0 ? (string)$notices_count : ''
    );

    $tabs['desk'] = array(
        'id'    => 'desk',
        'label' => 'Department Desk',
        'icon'  => '📍',
        'count' => ''
    );

    // Date formatter helper
    $format_acf_date = function($date_raw, $p_id = null) {
        if (!empty($date_raw)) {
            $ts = strtotime($date_raw);
            if ($ts) return date('d M Y', $ts);
            $d = DateTime::createFromFormat('Ymd', $date_raw);
            if ($d) return $d->format('d M Y');
        }
        return $p_id ? get_the_date('d M Y', $p_id) : date('d M Y');
    };

    // Prepare JSON-LD Schema Data
    $schema_faculty = array();
    foreach ($faculty_posts as $fp) {
        $schema_faculty[] = array(
            '@type'    => 'Person',
            'name'     => $fp->post_title,
            'jobTitle' => get_field('designation', $fp->ID) ?: 'Faculty Scholar',
            'email'    => get_field('email', $fp->ID) ?: ''
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
                'url'           => home_url('/'),
                'address'       => array(
                    '@type'           => 'PostalAddress',
                    'streetAddress'   => 'Charitravan',
                    'addressLocality' => 'Buxar',
                    'addressRegion'   => 'Bihar',
                    'postalCode'      => '802101',
                    'addressCountry'  => 'IN'
                )
            ),
            array(
                '@type'              => 'Department',
                '@id'                => esc_url($dept_permalink) . '#department',
                'name'               => esc_attr($dept_title),
                'alternateName'      => esc_attr($dept_hindi_title),
                'url'                => esc_url($dept_permalink),
                'parentOrganization' => array(
                    '@id' => home_url('/#institution')
                ),
                'description'        => esc_attr(wp_strip_all_tags(substr($about_text ?: $dept_title, 0, 240))),
                'member'             => $schema_faculty
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
                        'name'     => 'Academics',
                        'item'     => home_url('/department-details/')
                    ),
                    array(
                        '@type'    => 'ListItem',
                        'position' => 3,
                        'name'     => 'Faculty of ' . $faculty_group,
                        'item'     => esc_url($faculty_group_url)
                    ),
                    array(
                        '@type'    => 'ListItem',
                        'position' => 4,
                        'name'     => $dept_title,
                        'item'     => esc_url($dept_permalink)
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

    <div class="mvc-dept-showcase-root" id="mvcDeptShowcaseRoot">
        <style>
        /* ========================================================
           MOBILE-FIRST CLEAN ACADEMIC DESIGN SYSTEM
           Palette: Royal Navy, Institutional Maroon, Metallic Gold
           ======================================================== */
        :root {
            --mvc-navy-royal: #002147;
            --mvc-navy-dark: #001733;
            --mvc-maroon-crimson: #800000;
            --mvc-gold-primary: #D4AF37;
            --mvc-gold-light: #FBF3D5;
            --mvc-cream-soft: #FAF9F6;
            --mvc-surface-card: #FFFFFF;
            --mvc-surface-muted: #F8FAFC;
            --mvc-text-dark: #0F172A;
            --mvc-text-body: #334155;
            --mvc-text-muted: #64748B;
            --mvc-border-light: #E2E8F0;
            --mvc-shadow-card: 0 4px 18px rgba(0, 33, 71, 0.06);
            --mvc-shadow-hover: 0 14px 32px rgba(0, 33, 71, 0.12);
            --mvc-radius-pill: 50px;
            --mvc-radius-card: 14px;
            --mvc-radius-inner: 8px;
            --mvc-ease: cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mvc-dept-showcase-root {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--mvc-text-body);
            background: #FAFAFC;
            line-height: 1.7;
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        .mvc-dept-showcase-root *,
        .mvc-dept-showcase-root *::before,
        .mvc-dept-showcase-root *::after {
            box-sizing: border-box;
        }

        .mvc-portal-container {
            max-width: 100% !important;
            margin: 0 auto;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        /* 1. COMPACT HIGH-PRESTIGE HERO */
        .mvc-compact-hero {
            position: relative;
            background: linear-gradient(135deg, #001226 0%, #002147 45%, #420000 85%, #660000 100%);
            color: #ffffff;
            padding: 38px 0 28px;
            box-shadow: 0 4px 20px rgba(0, 16, 36, 0.2);
        }

        /* Semantic Breadcrumbs */
        .mvc-hero-breadcrumbs {
            margin-bottom: 12px;
        }
        .mvc-hero-breadcrumbs ol {
            display: flex;
            align-items: center;
            gap: 7px;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.72);
        }
        .mvc-hero-breadcrumbs a {
            color: var(--mvc-gold-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .mvc-hero-breadcrumbs a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .mvc-hero-breadcrumbs .mvc-sep {
            color: rgba(255, 255, 255, 0.35);
            font-size: 11px;
        }
        .mvc-hero-breadcrumbs li[aria-current="page"] span {
            color: #ffffff;
            font-weight: 700;
        }

        /* Insignia Row */
        .mvc-hero-insignia-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .mvc-hero-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
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
        .mvc-hero-hindi-badge {
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.45);
            color: #ffffff;
            padding: 3px 12px;
            border-radius: var(--mvc-radius-pill);
            font-size: 13px;
            font-weight: 700;
        }

        /* Single H1 Title */
        .mvc-hero-headline {
            font-size: clamp(26px, 3.8vw, 42px);
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 10px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .mvc-hero-lead-text {
            font-size: clamp(14px, 1.6vw, 16px);
            color: rgba(255, 255, 255, 0.88);
            max-width: 860px;
            margin: 0 0 20px;
            line-height: 1.65;
        }

        /* Streamlined Metrics Ribbon (Minimal Height, Mobile First) */
        .mvc-metrics-ribbon {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .mvc-metric-pill-item {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.25s;
        }
        .mvc-metric-pill-item:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(212, 175, 55, 0.5);
            transform: translateY(-2px);
        }
        .mvc-metric-mini-icon {
            font-size: 20px;
            flex-shrink: 0;
        }
        .mvc-metric-mini-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .mvc-metric-mini-label {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.2;
        }
        .mvc-metric-mini-val {
            font-size: 13.5px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.35;
        }

        /* 2. THE SINGLE UNIFIED STICKY NAVIGATION BAR WITH MOBILE < > BUTTONS */
        .mvc-single-nav-bar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #ffffff;
            border-bottom: 2px solid var(--mvc-border-light);
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.05);
            padding: 0;
            width: 100%;
        }
        .mvc-nav-wrapper-flex {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            width: 100%;
            padding: 8px 0;
        }
        .mvc-single-nav-scroll {
            display: flex;
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
            gap: 8px;
            padding: 4px 0;
            scroll-behavior: smooth;
            flex-grow: 1;
        }
        .mvc-single-nav-scroll::-webkit-scrollbar {
            display: none;
        }
        .mvc-nav-arrow-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #CBD5E1;
            color: var(--mvc-navy-royal);
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.1);
            cursor: pointer;
            transition: all 0.22s var(--mvc-ease);
            flex-shrink: 0;
            padding: 0;
            outline: none;
        }
        .mvc-nav-arrow-btn:hover {
            background: var(--mvc-navy-royal);
            color: var(--mvc-gold-primary);
            border-color: var(--mvc-navy-royal);
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.25);
        }
        .mvc-nav-arrow-btn[aria-disabled="true"] {
            opacity: 0.3;
            cursor: default;
            pointer-events: none;
            box-shadow: none;
        }
        .mvc-nav-tab-btn {
            background: transparent;
            border: 1px solid transparent;
            padding: 10px 18px;
            border-radius: var(--mvc-radius-pill);
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.22s var(--mvc-ease);
            text-decoration: none;
            font-family: inherit;
            flex-shrink: 0;
        }
        .mvc-nav-tab-btn:hover {
            color: var(--mvc-maroon-crimson);
            background: #FFF5F5;
        }
        .mvc-nav-tab-btn.active {
            background: var(--mvc-maroon-crimson);
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(128, 0, 0, 0.22);
            font-weight: 700;
        }
        .mvc-tab-count-bubble {
            font-size: 11px;
            font-weight: 800;
            padding: 1px 7px;
            border-radius: 10px;
            background: #E2E8F0;
            color: #334155;
            transition: all 0.2s;
        }
        .mvc-nav-tab-btn.active .mvc-tab-count-bubble {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* 3. MERGED PORTAL BODY & SECTIONS */
        .mvc-portal-body {
            padding: 35px 0 65px;
            min-height: 480px;
        }
        .mvc-dept-section {
            margin-bottom: 52px;
            scroll-margin-top: 80px;
        }

        /* Pane Header */
        .mvc-pane-head-box {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--mvc-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .mvc-pane-title-txt {
            font-size: clamp(20px, 2.5vw, 26px);
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 4px;
        }
        .mvc-pane-sub-txt {
            font-size: 13.5px;
            color: var(--mvc-text-muted);
            margin: 0;
        }

        /* ========================================================
           TAB 1: 10/10 CREATIVE ACADEMIC VISION & HERITAGE
           ======================================================== */
        .mvc-overview-bento-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 28px;
            align-items: start;
        }

        /* Editorial Prose Card */
        .mvc-about-prose-card {
            background: var(--mvc-surface-card);
            border: 1px solid var(--mvc-border-light);
            border-radius: var(--mvc-radius-card);
            padding: 32px 30px;
            box-shadow: var(--mvc-shadow-card);
        }
        .mvc-editorial-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #F1F5F9;
        }
        .mvc-editorial-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0;
        }
        .mvc-editorial-badge {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 10px;
            border-radius: 12px;
            background: var(--mvc-gold-light);
            color: #78350F;
            border: 1px solid rgba(212, 175, 55, 0.3);
            white-space: nowrap;
        }

        .mvc-prose-lead {
            font-size: 16.5px;
            line-height: 1.85;
            color: #1E293B;
            font-weight: 500;
            margin-bottom: 18px;
        }
        .mvc-prose-paragraph {
            font-size: 15px;
            line-height: 1.8;
            color: #475569;
            margin-bottom: 16px;
        }

        /* Right Column: Institutional Dossier Sidebar */
        .mvc-dossier-sidebar {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        /* Executive HOD Chair Card */
        .mvc-hod-card-box {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-top: 5px solid var(--mvc-maroon-crimson);
            border-radius: var(--mvc-radius-card);
            box-shadow: var(--mvc-shadow-card);
            overflow: hidden;
            text-align: center;
            transition: all 0.25s var(--mvc-ease);
        }
        .mvc-hod-card-box:hover {
            border-top-color: var(--mvc-gold-primary);
            box-shadow: var(--mvc-shadow-hover);
        }
        .mvc-hod-card-inner {
            padding: 24px 22px;
        }
        .mvc-hod-ribbon-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: linear-gradient(135deg, var(--mvc-gold-primary) 0%, #B48200 100%);
            color: #001733;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 14px;
            border-radius: var(--mvc-radius-pill);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            box-shadow: 0 2px 6px rgba(212, 175, 55, 0.25);
        }
        .mvc-hod-avatar-frame {
            width: 112px;
            height: 112px;
            border-radius: 50%;
            margin: 0 auto 14px;
            padding: 3.5px;
            background: linear-gradient(135deg, var(--mvc-gold-primary) 0%, var(--mvc-navy-royal) 100%);
            box-shadow: 0 6px 18px rgba(0, 33, 71, 0.15);
        }
        .mvc-hod-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }
        .mvc-hod-monogram-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #002d62 0%, #001733 100%);
            color: var(--mvc-gold-primary);
            font-size: 30px;
            font-weight: 800;
            font-family: Georgia, serif;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(212, 175, 55, 0.4);
        }
        .mvc-hod-name-txt {
            font-size: 18.5px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 4px;
        }
        .mvc-hod-desig-txt {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            margin: 0 0 6px;
        }
        .mvc-hod-qual-badge {
            display: inline-block;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            color: #475569;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .mvc-hod-actions-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 4px;
        }
        .mvc-hod-email-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            background: var(--mvc-navy-royal);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }
        .mvc-hod-email-btn:hover {
            background: var(--mvc-maroon-crimson);
            color: #ffffff;
            transform: translateY(-1px);
        }
        .mvc-hod-roster-link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: var(--mvc-navy-royal);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .mvc-hod-roster-link-btn:hover {
            background: #E2E8F0;
            color: var(--mvc-maroon-crimson);
        }

        /* Department Factsheet Card */
        .mvc-factsheet-card {
            background: #ffffff;
            border: 1px solid var(--mvc-border-light);
            border-radius: var(--mvc-radius-card);
            padding: 22px;
            box-shadow: var(--mvc-shadow-card);
        }
        .mvc-factsheet-head {
            font-size: 14.5px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin: 0 0 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mvc-factsheet-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .mvc-factsheet-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 6px;
            background: #F8FAFC;
            border: 1px solid #EDF2F7;
            font-size: 12.5px;
        }
        .mvc-factsheet-key {
            color: #64748B;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .mvc-factsheet-val {
            color: #0F172A;
            font-weight: 700;
            text-align: right;
        }

        /* TAB 2: FACULTY ROSTER */
        .mvc-faculty-cards-flow {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 22px;
        }
        .mvc-scholar-card-item {
            background: #ffffff;
            border: 1px solid var(--mvc-border-light);
            border-radius: var(--mvc-radius-card);
            padding: 26px 20px 22px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: var(--mvc-shadow-card);
            transition: all 0.25s var(--mvc-ease);
            position: relative;
        }
        .mvc-scholar-card-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--mvc-shadow-hover);
            border-color: #cbd5e1;
        }
        .mvc-scholar-avatar-ring {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            margin: 4px auto 12px;
            padding: 3px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.45) 0%, rgba(0, 33, 71, 0.2) 100%);
        }
        .mvc-scholar-avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }
        .mvc-scholar-monogram-box {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--mvc-navy-royal);
            color: var(--mvc-gold-primary);
            font-size: 28px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mvc-scholar-name-txt {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 3px;
            line-height: 1.35;
        }
        .mvc-scholar-desig-txt {
            font-size: 13px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            margin: 0 0 6px;
        }
        .mvc-scholar-qual-tag {
            font-size: 11.5px;
            color: #64748B;
            background: #F1F5F9;
            padding: 3px 10px;
            border-radius: 10px;
            margin-bottom: 12px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .mvc-scholar-mail-cta {
            font-size: 12px;
            color: var(--mvc-navy-royal);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            background: #F8FAFC;
            border: 1px solid var(--mvc-border-light);
            transition: all 0.2s;
            margin-top: auto;
            word-break: break-all;
        }
        .mvc-scholar-mail-cta:hover {
            background: var(--mvc-navy-royal);
            color: #ffffff;
            border-color: var(--mvc-navy-royal);
        }

        /* TABLES & DOCUMENT VAULT */
        .mvc-doc-vault-box {
            background: #ffffff;
            border: 1px solid var(--mvc-border-light);
            border-radius: var(--mvc-radius-card);
            padding: 24px;
            box-shadow: var(--mvc-shadow-card);
        }
        .mvc-advisory-note-bar {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-left: 4px solid var(--mvc-gold-primary);
            border-radius: 6px;
            padding: 12px 16px;
            font-size: 13.5px;
            color: #78350F;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }
        .mvc-table-touch-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            border: 1px solid #E2E8F0;
        }
        .mvc-table-touch-container table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
            margin: 0 !important;
        }
        .mvc-table-touch-container th {
            background: linear-gradient(135deg, var(--mvc-navy-royal) 0%, #1e3a5f 100%) !important;
            color: #ffffff !important;
            padding: 12px 16px !important;
            font-weight: 700 !important;
            border: none !important;
            white-space: nowrap;
        }
        .mvc-table-touch-container td {
            padding: 12px 16px !important;
            border-top: 1px solid #E2E8F0 !important;
            vertical-align: middle !important;
            color: #334155 !important;
        }
        .mvc-table-touch-container tr:nth-child(even) td {
            background: #F8FAFC !important;
        }
        .mvc-table-touch-container tr:hover td {
            background: #FFFDF5 !important;
        }
        .mvc-btn-direct-dl {
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 6px 14px !important;
            border-radius: 6px !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            background: var(--mvc-maroon-crimson) !important;
            color: #ffffff !important;
            text-decoration: none !important;
            box-shadow: 0 2px 6px rgba(128, 0, 0, 0.2) !important;
            transition: all 0.2s !important;
            white-space: nowrap !important;
            border: none !important;
        }
        .mvc-btn-direct-dl:hover {
            background: var(--mvc-navy-royal) !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
        }

        /* TAB 6: NOTICES FEED */
        .mvc-notices-feed-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .mvc-notice-feed-row {
            background: #ffffff;
            border: 1px solid var(--mvc-border-light);
            border-left: 4px solid var(--mvc-maroon-crimson);
            border-radius: 8px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        /* TAB 6: ACTIVITIES & GALLERIES GRID */
        .mvc-activities-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 22px;
        }
        .mvc-activity-card {
            background: #ffffff;
            border: 1px solid var(--mvc-border-light);
            border-radius: var(--mvc-radius-card);
            overflow: hidden;
            box-shadow: var(--mvc-shadow-card);
            transition: all 0.25s var(--mvc-ease);
            display: flex;
            flex-direction: column;
        }
        .mvc-activity-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--mvc-shadow-hover);
            border-color: #cbd5e1;
        }
        .mvc-activity-img-box {
            position: relative;
            width: 100%;
            height: 200px;
            background: #001733;
            overflow: hidden;
        }
        .mvc-activity-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.35s var(--mvc-ease);
        }
        .mvc-activity-card:hover .mvc-activity-thumb {
            transform: scale(1.05);
        }
        .mvc-activity-badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 23, 51, 0.88);
            backdrop-filter: blur(4px);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .mvc-activity-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .mvc-activity-meta-tag {
            font-size: 12px;
            color: var(--mvc-maroon-crimson);
            font-weight: 700;
            margin-bottom: 6px;
            display: block;
        }
        .mvc-activity-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 8px;
            line-height: 1.4;
        }
        .mvc-activity-title a {
            color: inherit;
            text-decoration: none;
        }
        .mvc-activity-title a:hover {
            color: var(--mvc-maroon-crimson);
        }
        .mvc-activity-desc {
            font-size: 13px;
            color: var(--mvc-text-muted);
            line-height: 1.6;
            margin: 0 0 16px;
            flex-grow: 1;
        }
        .mvc-activity-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--mvc-navy-royal);
            text-decoration: none;
            transition: color 0.2s;
            margin-top: auto;
        }
        .mvc-activity-link:hover {
            color: var(--mvc-maroon-crimson);
            text-decoration: underline;
        }

        /* TAB 7: CAMPUS DESK CARDS */
        .mvc-campus-desk-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .mvc-campus-card-box {
            background: #ffffff;
            border: 1px solid var(--mvc-border-light);
            border-radius: var(--mvc-radius-card);
            padding: 24px;
            box-shadow: var(--mvc-shadow-card);
        }
        .mvc-campus-card-box h4 {
            font-size: 16.5px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 8px 0 6px;
        }
        .mvc-campus-card-box p {
            font-size: 13px;
            color: var(--mvc-text-muted);
            margin: 0 0 12px;
            line-height: 1.55;
        }
        .mvc-campus-card-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 13px;
        }
        .mvc-campus-card-box li {
            padding: 4px 0;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .mvc-campus-card-box a {
            color: var(--mvc-maroon-crimson);
            text-decoration: none;
            font-weight: 700;
        }

        /* MOBILE FIRST BREAKPOINTS */
        @media (max-width: 1024px) {
            .mvc-metrics-ribbon {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-overview-bento-grid {
                grid-template-columns: 1fr;
            }
            .mvc-campus-desk-cards {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 768px) {
            .mvc-compact-hero {
                padding: 28px 0 22px;
            }
            .mvc-hero-headline {
                font-size: 25px;
            }
            .mvc-metrics-ribbon {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            .mvc-metric-pill-item {
                padding: 8px 12px;
            }
            .mvc-nav-wrapper-flex {
                gap: 6px;
            }
            .mvc-nav-arrow-btn {
                width: 32px;
                height: 32px;
                min-width: 32px;
            }
            .mvc-nav-tab-btn {
                padding: 8px 14px;
                font-size: 13px;
            }
            .mvc-about-prose-card {
                padding: 22px 18px;
            }
            .mvc-faculty-cards-flow {
                grid-template-columns: 1fr;
            }
            .mvc-activities-cards-grid {
                grid-template-columns: 1fr;
            }
        }
        </style>

        <!-- 1. COMPACT HIGH-PRESTIGE HERO (NO REDUNDANT ACTION PILLS) -->
        <header class="mvc-compact-hero">
            <div class="mvc-portal-container">

                <!-- Semantic Microdata Breadcrumbs -->
                <nav class="mvc-hero-breadcrumbs" aria-label="Breadcrumb">
                    <ol itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="<?php echo esc_url(home_url('/')); ?>">
                                <span itemprop="name">Home</span>
                            </a>
                            <meta itemprop="position" content="1" />
                        </li>
                        <li class="mvc-sep">/</li>
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="<?php echo esc_url(home_url('/department-details/')); ?>">
                                <span itemprop="name">Academics</span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>
                        <li class="mvc-sep">/</li>
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="<?php echo esc_url($faculty_group_url); ?>">
                                <span itemprop="name">Faculty of <?php echo esc_html($faculty_group); ?></span>
                            </a>
                            <meta itemprop="position" content="3" />
                        </li>
                        <li class="mvc-sep">/</li>
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
                            <span itemprop="name"><?php echo esc_html($dept_title); ?></span>
                            <meta itemprop="position" content="4" />
                        </li>
                    </ol>
                </nav>

                <!-- Insignia Badges -->
                <div class="mvc-hero-insignia-row">
                    <span class="mvc-hero-badge-pill">
                        🏛️ Faculty of <?php echo esc_html($faculty_group); ?> &bull; VKSU Ara
                    </span>
                    <span class="mvc-hero-hindi-badge">
                        <?php echo esc_html($dept_hindi_title); ?>
                    </span>
                </div>

                <!-- Single H1 Headline for 10/10 SEO -->
                <h1 class="mvc-hero-headline"><?php echo esc_html($dept_title); ?></h1>

                <p class="mvc-hero-lead-text">
                    Dedicated to academic rigor, intellectual inquiry, and holistic student mentorship under the National Education Policy (NEP 2020) and CBCS curriculum of Veer Kunwar Singh University (VKSU), Ara.
                </p>

                <!-- Streamlined Metrics Ribbon (Minimal Height, Zero Extra Buttons) -->
                <div class="mvc-metrics-ribbon">
                    <div class="mvc-metric-pill-item">
                        <span class="mvc-metric-mini-icon">📅</span>
                        <div class="mvc-metric-mini-text">
                            <span class="mvc-metric-mini-label">Establishment</span>
                            <span class="mvc-metric-mini-val"><?php echo esc_html($formatted_est_year); ?></span>
                        </div>
                    </div>

                    <div class="mvc-metric-pill-item">
                        <span class="mvc-metric-mini-icon">🎓</span>
                        <div class="mvc-metric-mini-text">
                            <span class="mvc-metric-mini-label">Sanctioned Seats</span>
                            <span class="mvc-metric-mini-val"><?php echo esc_html($formatted_seats); ?></span>
                        </div>
                    </div>

                    <div class="mvc-metric-pill-item">
                        <span class="mvc-metric-mini-icon">👨‍🏫</span>
                        <div class="mvc-metric-mini-text">
                            <span class="mvc-metric-mini-label">Faculty Strength</span>
                            <span class="mvc-metric-mini-val"><?php echo esc_html($faculty_count); ?> Active Educators</span>
                        </div>
                    </div>

                    <div class="mvc-metric-pill-item">
                        <span class="mvc-metric-mini-icon">📜</span>
                        <div class="mvc-metric-mini-text">
                            <span class="mvc-metric-mini-label">Curriculum</span>
                            <span class="mvc-metric-mini-val">NEP 2020 &bull; CBCS</span>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- 2. THE SINGLE UNIFIED STICKY NAVIGATION BAR WITH MOBILE < > BUTTONS -->
        <nav class="mvc-single-nav-bar" id="mvcUnifiedNavBar" aria-label="Department Navigation">
            <div class="mvc-portal-container mvc-nav-wrapper-flex">
                
                <!-- Scroll Left Arrow Button -->
                <button type="button" 
                        class="mvc-nav-arrow-btn mvc-arrow-prev" 
                        id="mvcNavArrowPrev" 
                        aria-label="Scroll tabs left" 
                        title="Scroll left"
                        aria-disabled="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                <!-- Scrollable Navigation Track -->
                <div class="mvc-single-nav-scroll" id="mvcNavScrollTrack" role="tablist">
                    <?php 
                    $first_btn = true;
                    foreach ($tabs as $tid => $tinfo) : 
                    ?>
                        <a href="#section-<?php echo esc_attr($tid); ?>" 
                           class="mvc-nav-tab-btn <?php echo $first_btn ? 'active' : ''; ?>" 
                           id="tab-btn-<?php echo esc_attr($tid); ?>"
                           data-target="section-<?php echo esc_attr($tid); ?>"
                           role="tab"
                           aria-selected="<?php echo $first_btn ? 'true' : 'false'; ?>">
                            <span><?php echo esc_html($tinfo['icon']); ?> <?php echo esc_html($tinfo['label']); ?></span>
                            <?php if (!empty($tinfo['count'])) : ?>
                                <span class="mvc-tab-count-bubble"><?php echo esc_html($tinfo['count']); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php 
                        $first_btn = false;
                    endforeach; 
                    ?>
                </div>

                <!-- Scroll Right Arrow Button -->
                <button type="button" 
                        class="mvc-nav-arrow-btn mvc-arrow-next" 
                        id="mvcNavArrowNext" 
                        aria-label="Scroll tabs right" 
                        title="Scroll right"
                        aria-disabled="false">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

            </div>
        </nav>

        <!-- 3. MAIN PORTAL BODY -->
        <main class="mvc-portal-body" id="mvcPortalBody">
            <div class="mvc-portal-container">

                <!-- 1. OVERVIEW -->
                <section class="mvc-dept-section" id="section-overview">
                    <div class="mvc-overview-bento-grid">
                        
                        <!-- Left: Department Overview Story -->
                        <div class="mvc-about-prose-card">
                            <div class="mvc-editorial-header">
                                <h3 class="mvc-editorial-title">🏛️ Department Overview</h3>
                                <span class="mvc-editorial-badge">Est. <?php echo esc_html($formatted_est_year); ?></span>
                            </div>

                            <div class="mvc-prose-body">
                                <?php if (!empty($overview_data['lead'])) : ?>
                                    <p class="mvc-prose-lead"><?php echo esc_html($overview_data['lead']); ?></p>
                                <?php endif; ?>

                                <?php foreach ($overview_data['paragraphs'] as $p_text) : ?>
                                    <p class="mvc-prose-paragraph"><?php echo esc_html($p_text); ?></p>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Right: Institutional Dossier Sidebar -->
                        <aside class="mvc-dossier-sidebar">
                            
                            <!-- Executive HOD Card -->
                            <?php if ($hod_post) : 
                                $hod_id = $hod_post->ID;
                                $hod_name = $hod_post->post_title;
                                $hod_desig = get_field('designation', $hod_id) ?: 'Assistant Professor & Head';
                                $hod_qual = get_field('qualification', $hod_id) ?: 'M.A., Ph.D.';
                                $hod_email = get_field('email', $hod_id) ?: '';
                                $hod_photo = get_the_post_thumbnail_url($hod_id, 'medium');
                                $hod_mono = mvc_get_faculty_monogram($hod_name);
                            ?>
                                <div class="mvc-hod-card-box">
                                    <div class="mvc-hod-card-inner">
                                        <div class="mvc-hod-ribbon-tag">👑 Head of Department (HOD)</div>
                                        
                                        <div class="mvc-hod-avatar-frame">
                                            <?php if (!empty($hod_photo)) : ?>
                                                <img src="<?php echo esc_url($hod_photo); ?>" alt="<?php echo esc_attr($hod_name); ?>" class="mvc-hod-img" loading="lazy">
                                            <?php else : ?>
                                                <div class="mvc-hod-monogram-circle" aria-hidden="true">
                                                    <span><?php echo esc_html($hod_mono); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <h3 class="mvc-hod-name-txt"><?php echo esc_html($hod_name); ?></h3>
                                        <div class="mvc-hod-desig-txt"><?php echo esc_html($hod_desig); ?></div>
                                        <div class="mvc-hod-qual-badge"><?php echo esc_html($hod_qual); ?></div>

                                        <div class="mvc-hod-actions-group">
                                            <?php if (!empty($hod_email)) : ?>
                                                <a href="mailto:<?php echo esc_attr($hod_email); ?>" class="mvc-hod-email-btn" title="Contact HOD">
                                                    ✉️ Contact Department Head
                                                </a>
                                            <?php endif; ?>
                                            <a href="#section-faculty" class="mvc-hod-roster-link-btn" id="mvcHodToRosterBtn">
                                                👨‍🏫 View Full Faculty Roster &darr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Department Specifications Factsheet -->
                            <div class="mvc-factsheet-card">
                                <h4 class="mvc-factsheet-head">📋 Department Dossier</h4>
                                <ul class="mvc-factsheet-list">
                                    <li class="mvc-factsheet-item">
                                        <span class="mvc-factsheet-key">🏛️ Faculty</span>
                                        <span class="mvc-factsheet-val">Faculty of <?php echo esc_html($faculty_group); ?></span>
                                    </li>
                                    <li class="mvc-factsheet-item">
                                        <span class="mvc-factsheet-key">📅 Established</span>
                                        <span class="mvc-factsheet-val"><?php echo esc_html($formatted_est_year); ?></span>
                                    </li>
                                    <li class="mvc-factsheet-item">
                                        <span class="mvc-factsheet-key">👥 Intake Quota</span>
                                        <span class="mvc-factsheet-val"><?php echo esc_html($formatted_seats); ?></span>
                                    </li>
                                    <li class="mvc-factsheet-item">
                                        <span class="mvc-factsheet-key">📜 Framework</span>
                                        <span class="mvc-factsheet-val">NEP 2020 &bull; CBCS</span>
                                    </li>
                                    <li class="mvc-factsheet-item">
                                        <span class="mvc-factsheet-key">🌐 Instruction</span>
                                        <span class="mvc-factsheet-val">Bilingual (English &amp; Hindi)</span>
                                    </li>
                                    <li class="mvc-factsheet-item">
                                        <span class="mvc-factsheet-key">🎓 University</span>
                                        <span class="mvc-factsheet-val">VKSU Ara (UGC Approved)</span>
                                    </li>
                                </ul>
                            </div>

                        </aside>

                    </div>
                </section>

                <!-- 2. FACULTY ROSTER -->
                <section class="mvc-dept-section" id="section-faculty">
                    <div class="mvc-pane-head-box">
                        <div>
                            <h2 class="mvc-pane-title-txt">👨‍🏫 Faculty Members</h2>
                            <p class="mvc-pane-sub-txt">Distinguished professors, scholars, and academic mentors of <?php echo esc_html($dept_title); ?></p>
                        </div>
                        <span class="mvc-hero-badge-pill" style="color: var(--mvc-navy-royal); border-color: #cbd5e1; background: #ffffff;">
                            <?php echo esc_html($faculty_count); ?> Active Educators
                        </span>
                    </div>

                    <?php if (!empty($faculty_posts)) : ?>
                        <div class="mvc-faculty-cards-flow">
                            <?php
                            foreach ($faculty_posts as $fp) :
                                $f_id = $fp->ID;
                                $f_name = $fp->post_title;
                                $f_desig = get_field('designation', $f_id) ?: 'Assistant Professor';
                                $f_qual = get_field('qualification', $f_id) ?: 'M.A., Ph.D.';
                                $f_email = get_field('email', $f_id) ?: '';
                                $is_hod = (bool) get_field('is_department_head', $f_id);
                                $f_photo = get_the_post_thumbnail_url($f_id, 'medium');
                                $f_mono = mvc_get_faculty_monogram($f_name);
                                ?>
                                <article class="mvc-scholar-card-item" itemscope itemtype="https://schema.org/Person">
                                    <?php if ($is_hod) : ?>
                                        <div class="mvc-hod-ribbon-tag" style="margin-bottom: 8px;">👑 HOD</div>
                                    <?php endif; ?>

                                    <div class="mvc-scholar-avatar-ring">
                                        <?php if (!empty($f_photo)) : ?>
                                            <img src="<?php echo esc_url($f_photo); ?>" alt="<?php echo esc_attr($f_name); ?>" class="mvc-scholar-avatar-img" itemprop="image" loading="lazy">
                                        <?php else : ?>
                                            <div class="mvc-scholar-monogram-box" aria-hidden="true"><?php echo esc_html($f_mono); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="mvc-scholar-name-txt" itemprop="name"><?php echo esc_html($f_name); ?></h3>
                                    <div class="mvc-scholar-desig-txt" itemprop="jobTitle"><?php echo esc_html($f_desig); ?></div>
                                    <div class="mvc-scholar-qual-tag"><?php echo esc_html($f_qual); ?></div>

                                    <?php if (!empty($f_email)) : ?>
                                        <a href="mailto:<?php echo esc_attr($f_email); ?>" class="mvc-scholar-mail-cta" itemprop="email" title="Send Official Email">
                                            ✉️ <?php echo esc_html($f_email); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php 
                                    $cv_url = get_post_meta($f_id, 'cv_pdf', true) ?: get_post_meta($f_id, 'faculty_cv', true);
                                    if (empty($cv_url)) {
                                        $cv_url = home_url('/research/');
                                        $cv_lbl = '📄 Academic CV / Profile &rarr;';
                                    } else {
                                        $cv_lbl = '📄 Download CV (PDF) &rarr;';
                                    }
                                    ?>
                                    <a href="<?php echo esc_url($cv_url); ?>" class="mvc-scholar-cv-btn" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; justify-content:center; gap:6px; width:100%; padding:7px 12px; margin-top:8px; font-size:12px; font-weight:700; color:var(--mvc-navy-royal); background:#F8FAFC; border:1px solid #CBD5E1; border-radius:6px; text-decoration:none;">
                                        <?php echo esc_html($cv_lbl); ?>
                                    </a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="mvc-advisory-note-bar">
                            <span>ℹ️</span>
                            <div>Faculty details for <?php echo esc_html($dept_title); ?> are currently being updated by the Academic Administration.</div>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- 3. SYLLABUS & CURRICULUM -->
                <?php if (isset($tabs['syllabus'])) : ?>
                    <section class="mvc-dept-section" id="section-syllabus">
                        <div class="mvc-pane-head-box">
                            <div>
                                <h2 class="mvc-pane-title-txt">📚 Syllabus &amp; Academic Regulations</h2>
                                <p class="mvc-pane-sub-txt">Curriculum prescribed by Veer Kunwar Singh University (VKSU), Ara under NEP 2020</p>
                            </div>
                        </div>

                        <div class="mvc-doc-vault-box">
                            <div class="mvc-advisory-note-bar">
                                <span>ℹ️</span>
                                <div>
                                    <strong>Four Year Undergraduate Program (FYUGP) &bull; NEP 2020:</strong> Click any document link or download button to view the official university curriculum scheme.
                                </div>
                            </div>

                            <div class="mvc-table-touch-container">
                                <?php echo wp_kses_post($syllabus_content); ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- 4. STUDY MATERIAL -->
                <?php if (isset($tabs['study-material'])) : ?>
                    <section class="mvc-dept-section" id="section-study-material">
                        <div class="mvc-pane-head-box">
                            <div>
                                <h2 class="mvc-pane-title-txt">🎓 Study Material &amp; E-Resources</h2>
                                <p class="mvc-pane-sub-txt">Faculty-curated reading modules, lecture references, and course materials for students</p>
                            </div>
                        </div>

                        <div class="mvc-doc-vault-box">
                            <div class="mvc-advisory-note-bar">
                                <span>💡</span>
                                <div>
                                    <strong>E-Learning Notice:</strong> Academic notes provided below are curated by departmental educators for reference study and exam preparation.
                                </div>
                            </div>

                            <div class="mvc-table-touch-container">
                                <?php echo wp_kses_post($study_material_content); ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- 5. LEARNING OUTCOMES -->
                <?php if (isset($tabs['outcomes'])) : ?>
                    <section class="mvc-dept-section" id="section-outcomes">
                        <div class="mvc-pane-head-box">
                            <div>
                                <h2 class="mvc-pane-title-txt">🎯 Course &amp; Program Outcomes (CO / PO)</h2>
                                <p class="mvc-pane-sub-txt">Outcome-Based Education (OBE) Framework defining learning benchmarks and graduate attributes</p>
                            </div>
                        </div>

                        <div class="mvc-doc-vault-box">
                            <div class="mvc-advisory-note-bar">
                                <span>📊</span>
                                <div>
                                    <strong>Outcome-Based Education (OBE):</strong> Program Outcomes (PO), Program Specific Outcomes (PSO), and Course Outcomes (CO) aligned with NAAC &amp; UGC standards.
                                </div>
                            </div>

                            <div class="mvc-table-touch-container">
                                <?php echo wp_kses_post($outcomes_content); ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- 6. DEPARTMENT ACTIVITIES & GALLERY -->
                <section class="mvc-dept-section" id="section-activities">
                    <div class="mvc-pane-head-box">
                        <div>
                            <h2 class="mvc-pane-title-txt">📸 Department Activities &amp; Gallery</h2>
                            <p class="mvc-pane-sub-txt">Academic workshops, student seminars, guest lectures, and photographic documentation for <?php echo esc_html($dept_title); ?></p>
                        </div>
                        <?php if ($has_activities) : ?>
                            <span class="mvc-hero-badge-pill" style="color: var(--mvc-navy-royal); border-color: #cbd5e1; background: #ffffff;">
                                <?php echo esc_html($activities_count); ?> Records
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($has_activities) : ?>
                        <div class="mvc-activities-cards-grid">
                            <?php 
                            // 1. Render Department Photo Galleries
                            if (!empty($dept_galleries)) :
                                foreach ($dept_galleries as $gal) :
                                    $gid = $gal->ID;
                                    $g_title = $gal->post_title;
                                    $g_year = get_post_meta($gid, 'gallery_year', true) ?: date('Y', strtotime($gal->post_date));
                                    $g_count = intval(get_post_meta($gid, 'gallery_photo_count', true)) ?: 1;
                                    $g_cover = get_post_meta($gid, 'gallery_cover_url', true);
                                    if (!$g_cover && has_post_thumbnail($gid)) {
                                        $g_cover = get_the_post_thumbnail_url($gid, 'large');
                                    }
                                    if (!$g_cover) {
                                        $g_cover = home_url('/wp-content/themes/astra-child/screenshot.png');
                                    }
                                    $g_desc = get_post_meta($gid, 'gallery_description', true) ?: wp_trim_words($gal->post_content, 22);
                            ?>
                                    <article class="mvc-activity-card">
                                        <div class="mvc-activity-img-box">
                                            <img src="<?php echo esc_url($g_cover); ?>" alt="<?php echo esc_attr($g_title); ?>" class="mvc-activity-thumb" loading="lazy">
                                            <span class="mvc-activity-badge">📸 <?php echo esc_html($g_count); ?> Photos &bull; <?php echo esc_html($g_year); ?></span>
                                        </div>
                                        <div class="mvc-activity-body">
                                            <span class="mvc-activity-meta-tag">🏛️ Photo Archive</span>
                                            <h3 class="mvc-activity-title"><?php echo esc_html($g_title); ?></h3>
                                            <?php if (!empty($g_desc)) : ?>
                                                <p class="mvc-activity-desc"><?php echo esc_html($g_desc); ?></p>
                                            <?php endif; ?>
                                            <a href="<?php echo esc_url(home_url('/photo-gallery/')); ?>" class="mvc-activity-link">
                                                Explore Full Album &rarr;
                                            </a>
                                        </div>
                                    </article>
                            <?php 
                                endforeach;
                            endif;

                            // 2. Render Department Events
                            if ($dept_events_query->have_posts()) :
                                while ($dept_events_query->have_posts()) : $dept_events_query->the_post();
                                    $eid = get_the_ID();
                                    $e_title = get_the_title();
                                    $e_venue = get_post_meta($eid, 'event_venue', true) ?: 'M.V. College Campus';
                                    $e_date = get_post_meta($eid, 'from_date', true) ?: get_the_date('d M Y');
                                    $e_cat = get_post_meta($eid, 'event_category_label', true) ?: 'Academic Seminar';
                                    $e_img = get_the_post_thumbnail_url($eid, 'medium_large') ?: get_post_meta($eid, 'event_image_url', true);
                            ?>
                                    <article class="mvc-activity-card">
                                        <?php if (!empty($e_img)) : ?>
                                            <div class="mvc-activity-img-box">
                                                <img src="<?php echo esc_url($e_img); ?>" alt="<?php echo esc_attr($e_title); ?>" class="mvc-activity-thumb" loading="lazy">
                                                <span class="mvc-activity-badge">🎙️ <?php echo esc_html($e_cat); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="mvc-activity-body">
                                            <span class="mvc-activity-meta-tag">📅 <?php echo esc_html($e_date); ?> &bull; 📍 <?php echo esc_html($e_venue); ?></span>
                                            <h3 class="mvc-activity-title"><a href="<?php the_permalink(); ?>"><?php echo esc_html($e_title); ?></a></h3>
                                            <a href="<?php the_permalink(); ?>" class="mvc-activity-link">
                                                View Event Details &rarr;
                                            </a>
                                        </div>
                                    </article>
                            <?php 
                                endwhile;
                                wp_reset_postdata();
                            endif; 
                            ?>
                        </div>
                    <?php else : ?>
                        <div class="mvc-advisory-note-bar" style="background:#F8FAFC; border-color:#CBD5E1; color:#334155; margin-bottom: 20px;">
                            <span>📸</span>
                            <div style="flex-grow:1;">
                                Departmental photo galleries and activity records for <?php echo esc_html($dept_title); ?> are documented regularly. You can also view all college-wide photo albums in the central media gallery.
                            </div>
                            <a href="<?php echo esc_url(home_url('/photo-gallery/')); ?>" class="mvc-btn-direct-dl" style="white-space:nowrap; background:var(--mvc-navy-royal); margin-left:12px;">
                                Visit Photo Gallery &rarr;
                            </a>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- 7. DEPT NOTICE BOARD -->
                <section class="mvc-dept-section" id="section-notices">
                    <div class="mvc-pane-head-box">
                        <div>
                            <h2 class="mvc-pane-title-txt">📢 Department Notice Board</h2>
                            <p class="mvc-pane-sub-txt">Official departmental notifications, class circulars, and test schedules for <?php echo esc_html($dept_title); ?></p>
                        </div>
                        <?php if ($notices_count > 0) : ?>
                            <span class="mvc-hero-badge-pill" style="color: var(--mvc-navy-royal); border-color: #cbd5e1; background: #ffffff;">
                                <?php echo esc_html($notices_count); ?> Active Circulars
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($dept_notices->have_posts()) : ?>
                        <div class="mvc-notices-feed-stack">
                            <?php
                            while ($dept_notices->have_posts()) : $dept_notices->the_post();
                                $raw_d = get_field('date_picker');
                                $disp_d = $format_acf_date($raw_d, get_the_ID());
                                $pdf = get_field('pdf_file');
                                $pdf_url = '';
                                if (is_array($pdf) && !empty($pdf['url'])) $pdf_url = $pdf['url'];
                                elseif (is_numeric($pdf) && (int)$pdf > 0) $pdf_url = wp_get_attachment_url((int)$pdf);
                                elseif (is_string($pdf) && !empty($pdf) && $pdf !== '0') $pdf_url = $pdf;
                                if (empty($pdf_url)) {
                                    $att_id = get_post_meta(get_the_ID(), '_pdf_attachment_id', true);
                                    if ($att_id) $pdf_url = wp_get_attachment_url((int)$att_id);
                                }
                                if (empty($pdf_url)) {
                                    $pdf_url = get_post_meta(get_the_ID(), '_pdf_url', true);
                                }
                                ?>
                                <div class="mvc-notice-feed-row">
                                    <div>
                                        <h3 style="font-size: 15.5px; font-weight: 700; color: var(--mvc-navy-royal); margin: 0 0 6px;">
                                            <?php if ($pdf_url) : ?>
                                                <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" style="color: inherit; text-decoration: none;">
                                                    <?php the_title(); ?>
                                                </a>
                                            <?php else : ?>
                                                <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;">
                                                    <?php the_title(); ?>
                                                </a>
                                            <?php endif; ?>
                                        </h3>
                                        <span style="font-size: 12px; color: #64748B;">📅 <?php echo esc_html($disp_d); ?></span>
                                    </div>
                                    <div style="display: flex; gap: 8px;">
                                        <?php if ($pdf_url) : ?>
                                            <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-btn-direct-dl">
                                                📄 Download PDF &rarr;
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php the_permalink(); ?>" class="mvc-btn-direct-dl" style="background: var(--mvc-navy-royal);">
                                            Details &rarr;
                                        </a>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php else : ?>
                        <div class="mvc-advisory-note-bar" style="background:#F8FAFC; border-color:#CBD5E1; color:#334155; margin-bottom:20px;">
                            <span>ℹ️</span>
                            <div>No exclusive departmental circulars active at present for <?php echo esc_html($dept_title); ?>. Recent institutional notices from the Central Notice Board are displayed below.</div>
                        </div>
                        <?php
                        $college_notices = new WP_Query(array(
                            'post_type'      => array('notice', 'notices'),
                            'posts_per_page' => 4,
                            'post_status'    => 'publish',
                            'orderby'        => 'date',
                            'order'          => 'DESC'
                        ));
                        if ($college_notices->have_posts()) :
                        ?>
                            <div class="mvc-notices-feed-stack">
                                <?php
                                while ($college_notices->have_posts()) : $college_notices->the_post();
                                    $raw_d = get_field('date_picker');
                                    $disp_d = $format_acf_date($raw_d, get_the_ID());
                                    $pdf = get_field('pdf_file');
                                    $pdf_url = '';
                                    if (is_array($pdf) && !empty($pdf['url'])) $pdf_url = $pdf['url'];
                                    elseif (is_numeric($pdf) && (int)$pdf > 0) $pdf_url = wp_get_attachment_url((int)$pdf);
                                    elseif (is_string($pdf) && !empty($pdf) && $pdf !== '0') $pdf_url = $pdf;
                                    if (empty($pdf_url)) $pdf_url = get_post_meta(get_the_ID(), '_pdf_url', true);
                                    ?>
                                    <div class="mvc-notice-feed-row">
                                        <div>
                                            <h3 style="font-size: 15px; font-weight: 700; color: var(--mvc-navy-royal); margin: 0 0 6px;">
                                                <?php if ($pdf_url) : ?>
                                                    <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" style="color: inherit; text-decoration: none;">
                                                        <?php the_title(); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;">
                                                        <?php the_title(); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </h3>
                                            <span style="font-size: 12px; color: #64748B;">📅 <?php echo esc_html($disp_d); ?></span>
                                        </div>
                                        <div style="display: flex; gap: 8px;">
                                            <?php if ($pdf_url) : ?>
                                                <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener" class="mvc-btn-direct-dl">
                                                    📄 Download PDF &rarr;
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>" class="mvc-btn-direct-dl" style="background: var(--mvc-navy-royal);">
                                                Details &rarr;
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        <?php endif; ?>
                        <div style="margin-top: 20px; text-align: center;">
                            <a href="<?php echo esc_url(home_url('/notices/')); ?>" class="mvc-btn-direct-dl" style="background: var(--mvc-navy-royal); padding: 10px 22px; font-size: 14px;">
                                📢 View All Central Circulars &amp; Notices &rarr;
                            </a>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- 8. CAMPUS DESK -->
                <section class="mvc-dept-section" id="section-desk">
                    <div class="mvc-pane-head-box">
                        <div>
                            <h2 class="mvc-pane-title-txt">📍 Department Desk &amp; Campus Helpdesk</h2>
                            <p class="mvc-pane-sub-txt">Office hours, campus block location, and student welfare support</p>
                        </div>
                    </div>

                    <div class="mvc-campus-desk-cards">
                        <div class="mvc-campus-card-box">
                            <span style="font-size: 26px;">📍</span>
                            <h4>Departmental Office</h4>
                            <p>Located in the Academic Block at M.V. College campus, welcoming students during official academic working hours.</p>
                            <ul>
                                <li>🕒 <strong>Hours:</strong> Mon – Sat: 10:00 AM – 4:00 PM</li>
                                <li>📍 <strong>Campus:</strong> Charitravan, Buxar – 802101 (Bihar)</li>
                                <?php $dynamic_office_email = mvc_get_global_setting('college_email') ?: mvc_get_global_setting('principal_email', get_option('admin_email')); ?>
                                <li>✉️ <strong>Office:</strong> <a href="mailto:<?php echo esc_attr($dynamic_office_email); ?>"><?php echo esc_html($dynamic_office_email); ?></a></li>
                            </ul>
                        </div>

                        <div class="mvc-campus-card-box">
                            <span style="font-size: 26px;">🎓</span>
                            <h4>University &amp; Examinations</h4>
                            <p>Affiliated to Veer Kunwar Singh University (VKSU), Ara for curriculum, semester registrations, and examinations.</p>
                            <ul>
                                <li>🏛️ <strong>Affiliation:</strong> VKSU Ara (UGC Approved)</li>
                                <li>📝 <strong>Portal:</strong> <a href="https://vksuexams.com/" target="_blank" rel="noopener">VKSU Examination Portal &rarr;</a></li>
                                <li>📢 <strong>Notice Board:</strong> <a href="<?php echo esc_url(home_url('/notices/')); ?>">Central Notice Board &rarr;</a></li>
                            </ul>
                        </div>

                        <div class="mvc-campus-card-box">
                            <span style="font-size: 26px;">🛡️</span>
                            <h4>Student Welfare &amp; IQAC</h4>
                            <p>Committed to holistic student support, continuous academic quality improvement, and grievance redressal.</p>
                            <ul>
                                <li>⚖️ <strong>Grievance:</strong> <a href="<?php echo esc_url(home_url('/student-grievance/')); ?>">Grievance Desk &rarr;</a></li>
                                <li>📖 <strong>Library:</strong> Central College Library Access</li>
                                <li>💡 <strong>Mentorship:</strong> Departmental Student Advising</li>
                            </ul>
                        </div>
                    </div>
                </section>

            </div>
        </main>

        <!-- 4. PURE VANILLA JS ANCHOR NAVIGATION & SCROLLSPY CONTROLLER -->
        <script>
        (function() {
            function initUnifiedDeptNav() {
                var navBar = document.getElementById('mvcUnifiedNavBar');
                if (!navBar) return;

                var navLinks = navBar.querySelectorAll('.mvc-nav-tab-btn');
                var sections = document.querySelectorAll('.mvc-dept-section');
                var scrollTrack = document.getElementById('mvcNavScrollTrack');
                var prevBtn = document.getElementById('mvcNavArrowPrev');
                var nextBtn = document.getElementById('mvcNavArrowNext');

                // Dynamic Arrow State Updater
                function updateNavArrowStates() {
                    if (!scrollTrack || !prevBtn || !nextBtn) return;
                    var canScrollLeft = scrollTrack.scrollLeft > 6;
                    var canScrollRight = (scrollTrack.scrollLeft + scrollTrack.clientWidth) < (scrollTrack.scrollWidth - 6);

                    prevBtn.style.opacity = canScrollLeft ? '1' : '0.3';
                    prevBtn.style.pointerEvents = canScrollLeft ? 'auto' : 'none';
                    prevBtn.setAttribute('aria-disabled', !canScrollLeft);

                    nextBtn.style.opacity = canScrollRight ? '1' : '0.3';
                    nextBtn.style.pointerEvents = canScrollRight ? 'auto' : 'none';
                    nextBtn.setAttribute('aria-disabled', !canScrollRight);
                }

                if (prevBtn && scrollTrack) {
                    prevBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        scrollTrack.scrollBy({ left: -220, behavior: 'smooth' });
                    });
                }

                if (nextBtn && scrollTrack) {
                    nextBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        scrollTrack.scrollBy({ left: 220, behavior: 'smooth' });
                    });
                }

                if (scrollTrack) {
                    scrollTrack.addEventListener('scroll', updateNavArrowStates);
                    window.addEventListener('resize', updateNavArrowStates);
                    setTimeout(updateNavArrowStates, 120);
                }

                // Smooth scroll to section on navigation click
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        var targetId = this.getAttribute('data-target');
                        var targetEl = document.getElementById(targetId);
                        if (!targetEl) return;
                        e.preventDefault();

                        var navHeight = navBar.offsetHeight || 60;
                        var targetTop = targetEl.getBoundingClientRect().top + window.pageYOffset - navHeight - 15;
                        window.scrollTo({ top: targetTop, behavior: 'smooth' });

                        navLinks.forEach(function(btn) { 
                            btn.classList.remove('active'); 
                            btn.setAttribute('aria-selected', 'false'); 
                        });
                        this.classList.add('active');
                        this.setAttribute('aria-selected', 'true');

                        var cleanId = targetId.replace('section-', '');
                        if (window.history && window.history.replaceState) {
                            window.history.replaceState(null, null, '#' + cleanId);
                        }
                    });
                });

                // HOD Card Quick Link to Roster Section
                var hodRosterBtn = document.getElementById('mvcHodToRosterBtn');
                if (hodRosterBtn) {
                    hodRosterBtn.addEventListener('click', function(e) {
                        var facultySec = document.getElementById('section-faculty');
                        if (facultySec) {
                            e.preventDefault();
                            var navHeight = navBar.offsetHeight || 60;
                            var targetTop = facultySec.getBoundingClientRect().top + window.pageYOffset - navHeight - 15;
                            window.scrollTo({ top: targetTop, behavior: 'smooth' });
                            var facBtn = document.getElementById('tab-btn-faculty');
                            if (facBtn) {
                                navLinks.forEach(function(btn) { btn.classList.remove('active'); });
                                facBtn.classList.add('active');
                            }
                        }
                    });
                }

                // ScrollSpy via IntersectionObserver
                if ('IntersectionObserver' in window && sections.length > 0) {
                    var observer = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            if (entry.isIntersecting) {
                                var secId = entry.target.id;
                                var tid = secId.replace('section-', '');
                                var activeBtn = document.getElementById('tab-btn-' + tid);
                                if (activeBtn) {
                                    navLinks.forEach(function(btn) { 
                                        btn.classList.remove('active'); 
                                        btn.setAttribute('aria-selected', 'false'); 
                                    });
                                    activeBtn.classList.add('active');
                                    activeBtn.setAttribute('aria-selected', 'true');
                                    if (activeBtn.scrollIntoView) {
                                        activeBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                                    }
                                }
                            }
                        });
                    }, { rootMargin: '-80px 0px -60% 0px', threshold: 0 });

                    sections.forEach(function(sec) {
                        observer.observe(sec);
                    });
                }

                // Initial hash support (e.g. #faculty, #notices)
                var currentHash = window.location.hash.replace('#', '');
                if (currentHash) {
                    var targetSection = document.getElementById('section-' + currentHash);
                    if (targetSection) {
                        setTimeout(function() {
                            var navHeight = navBar.offsetHeight || 60;
                            var targetTop = targetSection.getBoundingClientRect().top + window.pageYOffset - navHeight - 15;
                            window.scrollTo({ top: targetTop, behavior: 'smooth' });
                            var hashBtn = document.getElementById('tab-btn-' + currentHash);
                            if (hashBtn) {
                                navLinks.forEach(function(btn) { btn.classList.remove('active'); });
                                hashBtn.classList.add('active');
                            }
                        }, 300);
                    }
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initUnifiedDeptNav);
            } else {
                initUnifiedDeptNav();
            }
        })();
        </script>
    </div>

    <?php
    return ob_get_clean();
}

/**
 * Shortcode handler: [mvc_department slug="hindi"] or [mvc_department id="224"]
 */
function mvc_department_shortcode($atts) {
    $a = shortcode_atts(array(
        'slug' => '',
        'id'   => 0
    ), $atts);

    $param = !empty($a['slug']) ? $a['slug'] : (!empty($a['id']) ? intval($a['id']) : null);
    return mvc_render_department_page($param);
}
add_shortcode('mvc_department', 'mvc_department_shortcode');
