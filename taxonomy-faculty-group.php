<?php
/**
 * Taxonomy Template: Faculty Group (Humanities, Science, Social Science, Vocational)
 * Displays all departments belonging to this academic faculty group (10/10 Standard).
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$current_term = get_queried_object();
$term_name = $current_term ? $current_term->name : 'Faculty Group';
$term_slug = $current_term ? $current_term->slug : '';
$term_desc = $current_term ? $current_term->description : '';

// Query all departments under this faculty group
$depts_query = new WP_Query(array(
    'post_type'      => 'department',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
    'tax_query'      => array(
        array(
            'taxonomy' => 'faculty-group',
            'field'    => 'slug',
            'terms'    => array($term_slug)
        )
    )
));

$faculty_icons = array(
    'humanities'     => '📖',
    'science'        => '🔬',
    'social-science' => '🌍',
    'vocational'     => '💻'
);
$group_icon = isset($faculty_icons[$term_slug]) ? $faculty_icons[$term_slug] : '🏛️';

$faculty_hi_names = array(
    'humanities'     => 'मानविकी संकाय',
    'science'        => 'विज्ञान संकाय',
    'social-science' => 'सामाजिक विज्ञान संकाय',
    'vocational'     => 'व्यावसायिक एवं अनुप्रयुक्त संकाय'
);
$group_hi = isset($faculty_hi_names[$term_slug]) ? $faculty_hi_names[$term_slug] : 'शैक्षणिक संकाय';

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
?>

<div id="primary" class="content-area mvc-full-width-content" style="width: 100%; margin: 0; padding: 0;">
    <main id="main" class="site-main mvc-faculty-group-page">
        <style>
        :root {
            --mvc-navy-royal: #002147;
            --mvc-navy-dark: #001226;
            --mvc-maroon-crimson: #800000;
            --mvc-gold-primary: #D4AF37;
            --mvc-gold-light: #FBF3D5;
            --mvc-border-light: #E2E8F0;
            --mvc-radius-pill: 50px;
            --mvc-radius-card: 14px;
            --mvc-radius-inner: 10px;
            --mvc-ease: cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mvc-fg-hero {
            background: linear-gradient(135deg, #001226 0%, #002147 45%, #420000 85%, #660000 100%);
            color: #ffffff;
            padding: 45px 0 36px;
            position: relative;
            box-shadow: 0 4px 24px rgba(0, 16, 36, 0.22);
        }
        .mvc-fg-container {
            max-width: 100% !important;
            margin: 0 auto;
            padding-left: clamp(16px, 2.5vw, 40px) !important;
            padding-right: clamp(16px, 2.5vw, 40px) !important;
            width: 100% !important;
            box-sizing: border-box;
        }
        .mvc-fg-breadcrumbs {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }
        .mvc-fg-breadcrumbs a {
            color: var(--mvc-gold-primary);
            text-decoration: none;
            font-weight: 500;
        }
        .mvc-fg-breadcrumbs a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .mvc-fg-breadcrumbs .mvc-sep {
            color: rgba(255, 255, 255, 0.35);
            font-size: 11px;
        }
        .mvc-fg-insignia-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .mvc-fg-badge-pill {
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
        .mvc-fg-hindi-badge {
            background: rgba(212, 175, 55, 0.22);
            border: 1px solid rgba(212, 175, 55, 0.45);
            color: #ffffff;
            padding: 3px 14px;
            border-radius: var(--mvc-radius-pill);
            font-size: 13px;
            font-weight: 700;
        }

        .mvc-fg-title {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 10px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        .mvc-fg-lead {
            font-size: clamp(14px, 1.5vw, 16px);
            color: rgba(255, 255, 255, 0.88);
            max-width: 860px;
            margin: 0 0 20px;
            line-height: 1.68;
        }

        .mvc-fg-actions-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
        }
        .mvc-fg-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--mvc-radius-pill);
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }
        .mvc-fg-back-btn:hover {
            background: var(--mvc-gold-primary);
            color: #001226;
            border-color: var(--mvc-gold-primary);
        }

        .mvc-fg-content-sec {
            padding: 45px 0 75px;
            background: #F8FAFC;
            min-height: 480px;
        }
        .mvc-fg-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        /* 10/10 Department Card in Faculty Group */
        .mvc-fg-dept-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: var(--mvc-radius-card);
            padding: 24px;
            box-shadow: 0 4px 18px rgba(0, 33, 71, 0.06);
            transition: all 0.28s var(--mvc-ease);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .mvc-fg-dept-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--mvc-navy-royal);
            transition: background 0.25s ease;
        }
        .mvc-fg-dept-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 175, 55, 0.45);
            box-shadow: 0 16px 36px rgba(0, 33, 71, 0.12);
        }
        .mvc-fg-dept-card:hover::before {
            background: linear-gradient(90deg, var(--mvc-maroon-crimson) 0%, var(--mvc-gold-primary) 100%);
        }

        .mvc-fg-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }
        .mvc-fg-glyph-box {
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
        }
        .mvc-fg-hi-tag {
            font-size: 12px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            background: rgba(128, 0, 0, 0.05);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .mvc-fg-card-title {
            font-size: 19.5px;
            font-weight: 800;
            color: var(--mvc-navy-royal);
            margin: 0 0 10px;
            line-height: 1.35;
        }
        .mvc-fg-card-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }
        .mvc-fg-card-title a:hover {
            color: var(--mvc-maroon-crimson);
        }

        .mvc-fg-card-specs {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            font-size: 12px;
        }
        .mvc-fg-spec-chip {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 3px 9px;
            border-radius: 6px;
            font-weight: 600;
            color: #475569;
        }

        .mvc-fg-card-desc {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.6;
            margin: 0 0 18px;
        }

        .mvc-fg-card-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 14px;
            border-top: 1px solid #F1F5F9;
            margin-top: auto;
        }
        .mvc-fg-btn-primary {
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
        .mvc-fg-btn-primary:hover {
            background: var(--mvc-maroon-crimson);
            color: #ffffff;
            transform: translateX(2px);
        }
        .mvc-fg-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--mvc-maroon-crimson);
            text-decoration: none;
        }
        .mvc-fg-btn-secondary:hover {
            color: var(--mvc-navy-royal);
            text-decoration: underline;
        }

        @media (max-width: 1024px) {
            .mvc-fg-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .mvc-fg-grid { grid-template-columns: 1fr; }
            .mvc-fg-hero { padding: 30px 0 24px; }
            .mvc-fg-title { font-size: 26px; }
        }
        </style>

        <!-- Hero Header -->
        <header class="mvc-fg-hero">
            <div class="mvc-fg-container">
                <nav class="mvc-fg-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span class="mvc-sep">/</span>
                    <a href="<?php echo esc_url(home_url('/department-details/')); ?>">Academic Disciplines</a>
                    <span class="mvc-sep">/</span>
                    <span style="color: #ffffff; font-weight: 700;">Faculty of <?php echo esc_html($term_name); ?></span>
                </nav>

                <div class="mvc-fg-insignia-row">
                    <span class="mvc-fg-badge-pill">
                        🏛️ Academic Faculty Division &bull; VKSU Ara
                    </span>
                    <span class="mvc-fg-hindi-badge">
                        <?php echo esc_html($group_hi); ?>
                    </span>
                </div>

                <h1 class="mvc-fg-title">
                    <?php echo esc_html($group_icon); ?> Faculty of <?php echo esc_html($term_name); ?>
                </h1>

                <p class="mvc-fg-lead">
                    <?php 
                    if (!empty($term_desc)) {
                        echo esc_html($term_desc);
                    } else {
                        echo "Governing comprehensive undergraduate and postgraduate departmental studies under the 4-Year CBCS framework of NEP 2020 at Maharshi Vishwamitra Mahavidyalaya.";
                    }
                    ?>
                </p>

                <div class="mvc-fg-actions-bar">
                    <span style="font-size: 13.5px; font-weight: 700; color: rgba(255,255,255,0.9);">
                        🏛️ <?php echo esc_html($depts_query->found_posts); ?> Academic Departments in this Faculty
                    </span>
                    <a href="<?php echo esc_url(home_url('/department-details/')); ?>" class="mvc-fg-back-btn">
                        &larr; View All 15 Departments Directory
                    </a>
                </div>
            </div>
        </header>

        <!-- Departments Grid -->
        <section class="mvc-fg-content-sec">
            <div class="mvc-fg-container">
                <?php if ($depts_query->have_posts()) : ?>
                    <div class="mvc-fg-grid">
                        <?php
                        while ($depts_query->have_posts()) : $depts_query->the_post();
                            $d_id = get_the_ID();
                            $d_title = get_the_title();
                            $d_slug = get_post_field('post_name', $d_id);
                            $d_permalink = get_permalink($d_id);
                            $d_hi = function_exists('mvc_get_department_hindi_name') ? mvc_get_department_hindi_name($d_slug) : '';
                            $d_glyph = isset($dept_glyphs[$d_slug]) ? $dept_glyphs[$d_slug] : '🏛️';

                            $est = function_exists('mvc_clean_academic_year') ? mvc_clean_academic_year(get_field('establishment_year', $d_id)) : get_field('establishment_year', $d_id);
                            $seats = function_exists('mvc_clean_sanctioned_seats') ? mvc_clean_sanctioned_seats(get_field('sanctioned_seats', $d_id)) : get_field('sanctioned_seats', $d_id);
                            $about = get_field('about_text', $d_id);
                            $short_desc = !empty($about) ? wp_trim_words(wp_strip_all_tags($about), 22) : 'Committed to academic excellence, research depth, and student mentorship under NEP 2020.';
                            ?>
                            <article class="mvc-fg-dept-card">
                                <div>
                                    <div class="mvc-fg-card-head">
                                        <div class="mvc-fg-glyph-box" aria-hidden="true">
                                            <?php echo esc_html($d_glyph); ?>
                                        </div>
                                        <?php if (!empty($d_hi)) : ?>
                                            <span class="mvc-fg-hi-tag"><?php echo esc_html($d_hi); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <h2 class="mvc-fg-card-title">
                                        <a href="<?php echo esc_url($d_permalink); ?>"><?php echo esc_html($d_title); ?></a>
                                    </h2>

                                    <div class="mvc-fg-card-specs">
                                        <?php if (!empty($est)) : ?>
                                            <span class="mvc-fg-spec-chip">📅 Est. <?php echo esc_html($est); ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($seats)) : ?>
                                            <span class="mvc-fg-spec-chip">🎓 <?php echo esc_html($seats); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="mvc-fg-card-desc"><?php echo esc_html($short_desc); ?></p>
                                </div>

                                <div class="mvc-fg-card-actions">
                                    <a href="<?php echo esc_url($d_permalink); ?>" class="mvc-fg-btn-primary">
                                        Department Portal &rarr;
                                    </a>
                                    <a href="<?php echo esc_url($d_permalink . '#pane-syllabus'); ?>" class="mvc-fg-btn-secondary">
                                        Syllabus &amp; Roster &nearr;
                                    </a>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                <?php else : ?>
                    <p style="text-align: center; color: #64748B;">No departments found for this faculty group.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>
</div>

<?php
get_footer();
