<?php
/**
 * Single Template for 'event' Custom Post Type
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * 10/10 Standard Directive - 100% Full Width Expansive Design
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$post_id = get_the_ID();
$title   = get_the_title();
$edates  = get_field('event_dates', $post_id) ?: get_the_date('d F Y', $post_id);
$desc    = get_field('description', $post_id);
$thumb   = get_the_post_thumbnail_url($post_id, 'full');
if (empty($thumb)) {
    $thumb = get_post_meta($post_id, 'event_image_url', true);
}
$brochure_pdf = get_post_meta($post_id, 'event_brochure_pdf', true) ?: get_field('event_brochure_pdf', $post_id);

$terms = wp_get_object_terms($post_id, 'event-status');
$status_label = (!empty($terms) && !is_wp_error($terms)) ? ucfirst($terms[0]->name) : 'Completed';
$is_upcoming = (strtolower($status_label) === 'upcoming');

// College Logo
$college_logo = function_exists('get_field') ? get_field('college_logo', 209) : null;
$logo_url = (is_array($college_logo) && !empty($college_logo['url'])) ? $college_logo['url'] : home_url('/wp-content/uploads/2026/10/mv-college-website-logo.png');
?>

<div class="mvc-event-single-wrapper">
    <style id="mvc-single-event-styles">
    .mvc-event-single-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
        background: #f8fafc;
        width: 100% !important;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* 1. Grand Full-Width Event Hero */
    .mvc-single-event-hero {
        background: linear-gradient(135deg, #00152e 0%, #002147 60%, #4a0000 100%);
        color: #ffffff;
        padding: 48px 0 38px;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid #D4AF37;
        width: 100% !important;
    }
    .mvc-single-event-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 85% 20%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 10% 80%, rgba(128, 0, 0, 0.35) 0%, transparent 60%);
        pointer-events: none;
    }
    .mvc-single-event-hero-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto;
        padding-left: clamp(16px, 2.5vw, 40px) !important;
        padding-right: clamp(16px, 2.5vw, 40px) !important;
        box-sizing: border-box;
        position: relative;
        z-index: 2;
    }
    .mvc-event-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .mvc-event-breadcrumbs a {
        color: #FFD54F;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    .mvc-event-breadcrumbs a:hover {
        color: #ffffff;
        text-decoration: underline;
    }
    .mvc-event-breadcrumbs .mvc-sep {
        color: rgba(255, 255, 255, 0.4);
        font-size: 11px;
    }
    .mvc-event-breadcrumbs .mvc-current {
        color: #ffffff;
        font-weight: 500;
    }

    .mvc-event-hero-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .mvc-badge-status-pill {
        background: <?php echo $is_upcoming ? '#2563eb' : '#16a34a'; ?>;
        color: #ffffff;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }
    .mvc-badge-date-pill {
        background: #800000;
        color: #FFD54F;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid rgba(212, 175, 55, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-event-hero-title {
        font-size: clamp(24px, 2.5vw, 36px);
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 10px;
        line-height: 1.32;
    }

    /* 2. Full-Width Workspace Layout */
    .mvc-event-single-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 36px auto 70px;
        padding-left: clamp(16px, 2.5vw, 40px) !important;
        padding-right: clamp(16px, 2.5vw, 40px) !important;
        box-sizing: border-box !important;
    }
    .mvc-event-layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 32px;
        align-items: start;
    }

    /* Left Card */
    .mvc-event-main-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 33, 71, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .mvc-event-image-box {
        width: 100%;
        max-height: 520px;
        overflow: hidden;
        background: #0f172a;
        position: relative;
    }
    .mvc-event-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .mvc-event-body-text {
        padding: 36px;
        font-size: 16.5px;
        line-height: 1.85;
        color: #334155;
    }
    .mvc-event-body-text p {
        margin: 0 0 16px;
    }

    .mvc-event-footer-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 36px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 12px;
    }
    .mvc-event-footer-bar a {
        color: #800000;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-event-footer-bar a:hover {
        text-decoration: underline;
    }

    /* Right Sidebar */
    .mvc-event-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .mvc-event-sidebar-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 33, 71, 0.06);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .mvc-event-sidebar-header {
        background: #002147;
        color: #ffffff;
        padding: 16px 20px;
        border-bottom: 3px solid #D4AF37;
    }
    .mvc-event-sidebar-header h3 {
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mvc-event-sidebar-body {
        padding: 20px;
    }

    .mvc-event-spec-row {
        display: flex;
        flex-direction: column;
        gap: 2px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 12px;
        font-size: 13px;
    }
    .mvc-event-spec-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .mvc-event-spec-label {
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        font-size: 11px;
    }
    .mvc-event-spec-value {
        font-weight: 600;
        color: #002147;
    }

    /* Related Events */
    .mvc-related-events-sec {
        margin-top: 55px;
        border-top: 2px solid #e2e8f0;
        padding-top: 36px;
    }
    .mvc-related-events-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-top: 20px;
    }
    .mvc-related-event-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
    }
    .mvc-related-event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,33,71,0.12);
        border-color: #800000;
    }

    @media (max-width: 992px) {
        .mvc-event-layout-grid {
            grid-template-columns: 1fr;
        }
        .mvc-related-events-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <!-- 1. GRAND FULL-WIDTH EVENT HERO -->
    <header class="mvc-single-event-hero">
        <div class="mvc-single-event-hero-container">
            <nav class="mvc-event-breadcrumbs" aria-label="Breadcrumb Navigation">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                <span class="mvc-sep" aria-hidden="true">/</span>
                <a href="<?php echo esc_url(home_url('/events/')); ?>">Events &amp; Activities</a>
                <span class="mvc-sep" aria-hidden="true">/</span>
                <span class="mvc-current" aria-current="page"><?php echo esc_html(wp_trim_words($title, 8)); ?></span>
            </nav>

            <div class="mvc-event-hero-badges">
                <span class="mvc-badge-status-pill"><?php echo esc_html($status_label); ?></span>
                <span class="mvc-badge-date-pill">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span><?php echo esc_html($edates); ?></span>
                </span>
            </div>

            <h1 class="mvc-event-hero-title"><?php echo esc_html($title); ?></h1>
        </div>
    </header>

    <!-- 2. FULL-WIDTH WORKSPACE -->
    <main class="mvc-event-single-container" id="main-content">
        <div class="mvc-event-layout-grid">
            
            <div class="mvc-event-main-col">
                <article class="mvc-event-main-card">
                    <?php if (!empty($thumb)) : ?>
                        <div class="mvc-event-image-box">
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="mvc-event-body-text">
                        <?php if (!empty($desc)) : ?>
                            <?php echo wp_kses_post(wpautop($desc)); ?>
                        <?php else : ?>
                            <?php echo wp_kses_post(apply_filters('the_content', get_the_content())); ?>
                        <?php endif; ?>
                    </div>

                    <div class="mvc-event-footer-bar">
                        <a href="<?php echo esc_url(home_url('/')); ?>">&larr; Return to Homepage</a>
                        <a href="<?php echo esc_url(home_url('/events/')); ?>">View All Campus Events &rarr;</a>
                    </div>
                </article>
            </div>

            <aside class="mvc-event-sidebar">
                <div class="mvc-event-sidebar-card">
                    <div class="mvc-event-sidebar-header">
                        <h3>
                            <span aria-hidden="true">📅</span>
                            <span>Event Information</span>
                        </h3>
                    </div>
                    <div class="mvc-event-sidebar-body">
                        <div class="mvc-event-spec-row">
                            <span class="mvc-event-spec-label">Event Status</span>
                            <span class="mvc-event-spec-value"><?php echo esc_html($status_label); ?></span>
                        </div>
                        <div class="mvc-event-spec-row">
                            <span class="mvc-event-spec-label">Scheduled Date / Duration</span>
                            <span class="mvc-event-spec-value"><?php echo esc_html($edates); ?></span>
                        </div>
                        <div class="mvc-event-spec-row">
                            <span class="mvc-event-spec-label">Campus Venue</span>
                            <span class="mvc-event-spec-value">Maharshi Vishwamitra Mahavidyalaya Campus, Charitravan, Buxar</span>
                        </div>
                        <div class="mvc-event-spec-row">
                            <span class="mvc-event-spec-label">Organized By</span>
                            <span class="mvc-event-spec-value">Maharshi Vishwamitra Mahavidyalaya (VKSU Unit)</span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($brochure_pdf)) : ?>
                <div class="mvc-event-sidebar-card" style="border: 2px solid #D4AF37; background: #FFFDF5;">
                    <div class="mvc-event-sidebar-header" style="background: linear-gradient(135deg, #800000 0%, #4D0008 100%);">
                        <h3>
                            <span aria-hidden="true">📄</span>
                            <span>Official Document / Brochure</span>
                        </h3>
                    </div>
                    <div class="mvc-event-sidebar-body" style="text-align: center;">
                        <p style="font-size: 13px; color: #475569; margin: 0 0 14px 0; line-height: 1.5;">Official circular, schedule, or brochure document published by the organizing department.</p>
                        <a href="<?php echo esc_url($brochure_pdf); ?>" target="_blank" rel="noopener" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #002147; color: #ffffff; padding: 11px 16px; border-radius: 8px; font-weight: 700; font-size: 13.5px; text-decoration: none; box-shadow: 0 4px 12px rgba(0,33,71,0.2); transition: all 0.2s ease;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Download Brochure PDF</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mvc-event-sidebar-card">
                    <div class="mvc-event-sidebar-header">
                        <h3>
                            <span aria-hidden="true">🔗</span>
                            <span>Quick Actions</span>
                        </h3>
                    </div>
                    <div class="mvc-event-sidebar-body" style="display:flex; flex-direction:column; gap:8px;">
                        <a href="<?php echo esc_url(home_url('/photo-gallery/')); ?>" style="display:block; padding:10px 14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; color:#002147; text-decoration:none; font-weight:600; font-size:13px;">📸 Photo Gallery Archives &rarr;</a>
                        <a href="<?php echo esc_url(home_url('/notices/')); ?>" style="display:block; padding:10px 14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; color:#002147; text-decoration:none; font-weight:600; font-size:13px;">📢 Official Circulars &rarr;</a>
                    </div>
                </div>
            </aside>

        </div>

        <!-- RELATED EVENTS -->
        <?php
        $related_events = new WP_Query(array(
            'post_type'      => 'event',
            'posts_per_page' => 3,
            'post__not_in'   => array($post_id),
            'post_status'    => array('publish', 'future'),
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));

        if ($related_events->have_posts()) :
            ?>
            <section class="mvc-related-events-sec">
                <h2 style="font-size:22px; font-weight:800; color:#002147; margin:0 0 16px;">More Campus Events &amp; Activities</h2>
                <div class="mvc-related-events-grid">
                    <?php
                    while ($related_events->have_posts()) : $related_events->the_post();
                        $rel_id = get_the_ID();
                        $rel_thumb = get_the_post_thumbnail_url($rel_id, 'medium_large');
                        if (empty($rel_thumb)) {
                            $rel_thumb = get_post_meta($rel_id, 'event_image_url', true);
                        }
                        $rel_dates = get_field('event_dates', $rel_id) ?: get_the_date('d M Y', $rel_id);
                        ?>
                        <article class="mvc-related-event-card">
                            <?php if ($rel_thumb) : ?>
                                <div style="height:170px; overflow:hidden;">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url($rel_thumb); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; height:100%; object-fit:cover;">
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div style="padding:16px; flex:1; display:flex; flex-direction:column;">
                                <span style="font-size:12px; color:#800000; font-weight:700; margin-bottom:6px;">📅 <?php echo esc_html($rel_dates); ?></span>
                                <h3 style="font-size:15px; font-weight:700; color:#002147; margin:0 0 12px; line-height:1.4;">
                                    <a href="<?php the_permalink(); ?>" style="color:inherit; text-decoration:none;"><?php the_title(); ?></a>
                                </h3>
                                <a href="<?php the_permalink(); ?>" style="font-size:12.5px; font-weight:700; color:#800000; text-decoration:none; margin-top:auto;">View Event &rarr;</a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </section>
        <?php endif; ?>

    </main>

</div>

<?php
get_footer();
