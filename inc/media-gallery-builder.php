<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Media & Gallery Suite Builder Engine (10/10 Creative Standard)
 * 
 * Supports:
 * - Photo Gallery (/photo-gallery/)
 * - Video Gallery (/video-gallery/)
 * - Press & Media Coverage (/press-media/, /media/, /media-gallery/)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Robust Local Asset URL Resolver (100% Offline & Locally Stored)
 * Guarantees zero dependency on live server; all assets are served from local filesystem.
 */
function mvc_resolve_media_image_url($raw_url) {
    $fallback_local_logo = home_url('/wp-content/uploads/live_imported/images/website-logo.png');

    if (empty($raw_url)) {
        return $fallback_local_logo;
    }

    // Clean query parameters
    $clean_url = strtok($raw_url, '?');
    $basename = basename($clean_url);

    // 1. If path in raw_url directly exists on local disk
    $clean_rel = ltrim(parse_url($raw_url, PHP_URL_PATH) ?? '', '/');
    if ($clean_rel && file_exists(ABSPATH . $clean_rel)) {
        return home_url('/' . $clean_rel);
    }

    // 2. Direct check in media/images
    $local_media_path = ABSPATH . 'wp-content/uploads/media/images/' . $basename;
    if (file_exists($local_media_path)) {
        return home_url('/wp-content/uploads/media/images/' . $basename);
    }

    // 3. Direct check in live_imported/images
    $local_images_path = ABSPATH . 'wp-content/uploads/live_imported/images/' . $basename;
    if (file_exists($local_images_path)) {
        return home_url('/wp-content/uploads/live_imported/images/' . $basename);
    }

    // 4. Direct check in live_imported
    $local_parent_path = ABSPATH . 'wp-content/uploads/live_imported/' . $basename;
    if (file_exists($local_parent_path)) {
        return home_url('/wp-content/uploads/live_imported/' . $basename);
    }

    // 5. Return local fallback logo rather than depending on external host
    return $fallback_local_logo;
}

/**
 * Shared Media Suite Navigation Component
 * Dual-Mode: Interactive Desktop Pills + Native Touch Mobile Ribbon & Dropdown
 */
function mvc_render_media_suite_nav($active_slug = 'photo-gallery') {
    // Dynamic CPT metrics calculation
    $video_obj = wp_count_posts('video');
    $video_cnt = $video_obj ? (int)$video_obj->publish : 0;
    
    $press_obj = wp_count_posts('press-media');
    $press_cnt = $press_obj ? (int)$press_obj->publish : 0;

    $photo_cnt_cache = mvc_get_transient_or_execute('mvc_cached_photo_gallery_count', function() {
        $count = 0;
        $albums = get_posts([
            'post_type'      => 'gallery-cat',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids'
        ]);
        foreach ($albums as $aid) {
            $raw_json = get_post_meta($aid, 'gallery_images_json', true);
            if (!empty($raw_json)) {
                $dec = json_decode($raw_json, true);
                if (is_array($dec)) {
                    $count += count($dec);
                    continue;
                }
            }
            $count += 1;
        }
        return $count > 0 ? $count : 400;
    }, 86400);

    $nav_items = [
        'photo-gallery' => [
            'label' => 'Photo Gallery',
            'hindi' => 'फोटो गैलरी',
            'url'   => home_url('/photo-gallery/'),
            'icon'  => '📷',
            'count' => ($photo_cnt_cache > 0 ? $photo_cnt_cache : '400+') . ' Photos',
            'desc'  => 'Event archives & campus life'
        ],
        'video-gallery' => [
            'label' => 'Video Gallery',
            'hindi' => 'वीडियो गैलरी',
            'url'   => home_url('/video-gallery/'),
            'icon'  => '🎥',
            'count' => ($video_cnt > 0 ? $video_cnt : '35') . ' Videos',
            'desc'  => 'Official YouTube broadcasts'
        ],
        'press-media' => [
            'label' => 'Press & Media',
            'hindi' => 'प्रेस एवं मीडिया',
            'url'   => home_url('/media/'),
            'icon'  => '📰',
            'count' => ($press_cnt > 0 ? $press_cnt : '40+') . ' Clippings',
            'desc'  => 'Newspaper press coverage'
        ]
    ];
    ?>
    <nav class="mvc-media-suite-nav" aria-label="Media Suite Navigation">
        <div class="mvc-media-nav-inner">
            <!-- Mobile Select Dropdown (Hidden, unified with < > pills) -->
            <div class="mvc-media-mobile-select-wrap" style="display: none !important;">
                <label for="mvcMediaNavSelect" class="mvc-media-mobile-label">Select Media Channel:</label>
                <div class="mvc-select-custom-wrapper">
                    <select id="mvcMediaNavSelect" class="mvc-media-mobile-select" onchange="if(this.value) window.location.href=this.value;">
                        <?php foreach ($nav_items as $slug => $item): ?>
                            <option value="<?php echo esc_url($item['url']); ?>" <?php selected($active_slug, $slug); ?>>
                                <?php echo esc_html($item['icon'] . ' ' . $item['label'] . ' (' . $item['hindi'] . ') - ' . $item['count']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Unified Pill Switcher with < > Scroll Navigation -->
            <div class="mvc-media-pills-container">
                <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-prev is-disabled" id="mvcMediaArrowPrev" onclick="mvcMediaPillScroll(-1)" aria-label="Scroll Media Left" title="Scroll left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                </button>

                <ul class="mvc-media-pills" id="mvcMediaPillsTrack">
                    <?php foreach ($nav_items as $slug => $item): 
                        $is_active = ($active_slug === $slug);
                    ?>
                        <li class="mvc-media-pill-item <?php echo $is_active ? 'is-active' : ''; ?>" <?php echo $is_active ? 'id="mvcActiveMediaPill"' : ''; ?>>
                            <a href="<?php echo esc_url($item['url']); ?>" class="mvc-media-pill-link" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
                                <span class="mvc-pill-icon"><?php echo esc_html($item['icon']); ?></span>
                                <span class="mvc-pill-meta">
                                    <span class="mvc-pill-title"><?php echo esc_html($item['label']); ?></span>
                                    <span class="mvc-pill-sub"><?php echo esc_html($item['hindi']); ?> • <strong class="mvc-pill-badge"><?php echo esc_html($item['count']); ?></strong></span>
                                </span>
                                <?php if ($is_active): ?>
                                    <span class="mvc-pill-active-dot" aria-hidden="true"></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-next" id="mvcMediaArrowNext" onclick="mvcMediaPillScroll(1)" aria-label="Scroll Media Right" title="Scroll right">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <script>
    function mvcMediaPillScroll(direction) {
        var track = document.getElementById('mvcMediaPillsTrack');
        if (!track) return;
        var scrollDistance = 260 * direction;
        track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
        setTimeout(mvcUpdateMediaArrowStates, 320);
    }

    function mvcUpdateMediaArrowStates() {
        var track = document.getElementById('mvcMediaPillsTrack');
        var prevBtn = document.getElementById('mvcMediaArrowPrev');
        var nextBtn = document.getElementById('mvcMediaArrowNext');
        if (!track || !prevBtn || !nextBtn) return;

        var scrollLeft = Math.round(track.scrollLeft);
        var maxScroll = Math.round(track.scrollWidth - track.clientWidth);

        if (scrollLeft <= 6) {
            prevBtn.classList.add('is-disabled');
        } else {
            prevBtn.classList.remove('is-disabled');
        }

        if (scrollLeft >= maxScroll - 6 || maxScroll <= 0) {
            nextBtn.classList.add('is-disabled');
        } else {
            nextBtn.classList.remove('is-disabled');
        }
    }

    (function() {
        function initMediaPillNav() {
            var track = document.getElementById('mvcMediaPillsTrack');
            if (!track) return;
            track.addEventListener('scroll', mvcUpdateMediaArrowStates, { passive: true });
            window.addEventListener('resize', mvcUpdateMediaArrowStates);

            var activePill = document.getElementById('mvcActiveMediaPill');
            if (activePill) {
                setTimeout(function() {
                    var trackRect = track.getBoundingClientRect();
                    var pillRect = activePill.getBoundingClientRect();
                    if (pillRect.left < trackRect.left || pillRect.right > trackRect.right) {
                        activePill.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                    mvcUpdateMediaArrowStates();
                }, 250);
            } else {
                setTimeout(mvcUpdateMediaArrowStates, 200);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMediaPillNav);
        } else {
            initMediaPillNav();
        }
    })();
    </script>
    <?php
}

/**
 * Shared Unified Media Styling Engine (10/10 Royal Aesthetics)
 */
function mvc_print_media_suite_styles() {
    ?>
    <style id="mvc-media-suite-core-css">
    :root {
        --mvc-navy: #002147;
        --mvc-navy-dark: #00152e;
        --mvc-navy-light: #0b3464;
        --mvc-maroon: #800000;
        --mvc-maroon-dark: #5c0000;
        --mvc-gold: #D4AF37;
        --mvc-gold-light: #f5e6be;
        --mvc-gold-dark: #b48200;
        --mvc-cream: #FAF9F6;
        --mvc-slate: #475569;
        --mvc-slate-light: #f1f5f9;
        --mvc-border: #e2e8f0;
        --mvc-shadow-subtle: 0 4px 20px rgba(0, 33, 71, 0.06);
        --mvc-shadow-elevated: 0 12px 35px rgba(0, 33, 71, 0.12);
        --mvc-radius: 12px;
        --mvc-transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Outer Wrapper */
    .mvc-media-page-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
        background: #f8fafc;
        min-height: 100vh;
        width: 100%;
        overflow-x: hidden;
    }

    /* HERO SECTION */
    .mvc-media-hero {
        background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 60%, var(--mvc-navy-light) 100%);
        color: #ffffff;
        padding: 55px 20px 45px;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--mvc-gold);
    }
    .mvc-media-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 85% 20%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 10% 80%, rgba(128, 0, 0, 0.3) 0%, transparent 60%);
        pointer-events: none;
    }
    .mvc-media-hero-container {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 auto;
        padding-left: clamp(16px, 2.5vw, 40px) !important;
        padding-right: clamp(16px, 2.5vw, 40px) !important;
        box-sizing: border-box;
        position: relative;
        z-index: 2;
    }
    .mvc-media-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .mvc-media-breadcrumbs a {
        color: var(--mvc-gold-light);
        text-decoration: none;
        transition: var(--mvc-transition);
    }
    .mvc-media-breadcrumbs a:hover {
        color: #ffffff;
        text-decoration: underline;
    }
    .mvc-media-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(212, 175, 55, 0.18);
        border: 1px solid rgba(212, 175, 55, 0.4);
        color: var(--mvc-gold-light);
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 14px;
        backdrop-filter: blur(8px);
    }
    .mvc-media-tag-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--mvc-gold);
        box-shadow: 0 0 8px var(--mvc-gold);
    }
    .mvc-media-hero-title {
        font-size: clamp(26px, 4vw, 42px);
        font-weight: 800;
        line-height: 1.2;
        margin: 0 0 10px;
        color: #ffffff;
        letter-spacing: -0.02em;
    }
    .mvc-media-hero-subtitle-hi {
        font-size: clamp(17px, 2.2vw, 22px);
        color: var(--mvc-gold-light);
        font-weight: 600;
        margin: 0 0 14px;
    }
    .mvc-media-hero-desc {
        font-size: 15.5px;
        line-height: 1.6;
        color: #cbd5e1;
        max-width: 820px;
        margin: 0 0 24px;
    }

    /* STATS STRIP */
    .mvc-media-stats-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-top: 25px;
        padding-top: 22px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
    }
    .mvc-stat-pill {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        backdrop-filter: blur(6px);
    }
    .mvc-stat-pill-icon {
        font-size: 24px;
    }
    .mvc-stat-num {
        display: block;
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1;
    }
    .mvc-stat-label {
        display: block;
        font-size: 12px;
        color: #94a3b8;
        margin-top: 3px;
        font-weight: 500;
    }

    /* MEDIA SUITE NAV */
    .mvc-media-suite-nav {
        background: #ffffff;
        border-bottom: 1px solid var(--mvc-border);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        position: sticky;
        top: 56px; /* Sits cleanly below sticky main navbar */
        z-index: 90; /* Stacks below header navbar and dropdowns */
        padding: 10px 20px;
    }
    .mvc-media-nav-inner {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 auto;
        padding-left: clamp(16px, 2.5vw, 40px) !important;
        padding-right: clamp(16px, 2.5vw, 40px) !important;
        box-sizing: border-box;
    }
    .mvc-media-mobile-select-wrap {
        display: none;
    }
    .mvc-media-pills-container {
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        width: 100%;
    }
    .mvc-nav-arrow-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: var(--mvc-navy);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        flex-shrink: 0;
        padding: 0;
        box-shadow: 0 2px 6px rgba(0, 33, 71, 0.06);
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        z-index: 2;
    }
    .mvc-nav-arrow-btn:hover {
        background: var(--mvc-navy);
        color: #ffffff;
        border-color: var(--mvc-navy);
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(0, 33, 71, 0.22);
    }
    .mvc-nav-arrow-btn:active {
        transform: scale(0.94);
    }
    .mvc-nav-arrow-btn.is-disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #94a3b8;
        box-shadow: none;
        transform: none;
    }
    .mvc-media-pills {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 4px 0;
        gap: 12px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        flex-grow: 1;
    }
    .mvc-media-pills::-webkit-scrollbar {
        display: none;
    }
    .mvc-media-pill-item {
        flex: 1;
        min-width: 220px;
    }
    .mvc-media-pill-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        color: #334155;
        transition: var(--mvc-transition);
        position: relative;
    }
    .mvc-media-pill-link:hover {
        background: #ffffff;
        border-color: var(--mvc-navy);
        box-shadow: var(--mvc-shadow-subtle);
        transform: translateY(-1px);
    }
    .mvc-media-pill-item.is-active .mvc-media-pill-link {
        background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-light) 100%);
        border-color: var(--mvc-navy);
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(0, 33, 71, 0.2);
    }
    .mvc-pill-icon {
        font-size: 22px;
        line-height: 1;
    }
    .mvc-pill-meta {
        display: flex;
        flex-direction: column;
    }
    .mvc-pill-title {
        font-size: 14.5px;
        font-weight: 700;
        line-height: 1.2;
    }
    .mvc-pill-sub {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }
    .mvc-media-pill-item.is-active .mvc-pill-sub {
        color: var(--mvc-gold-light);
    }
    .mvc-pill-badge {
        font-weight: 600;
    }
    .mvc-pill-active-dot {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--mvc-gold);
        box-shadow: 0 0 6px var(--mvc-gold);
    }

    /* MAIN CONTENT WRAPPER */
    .mvc-media-body-container {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 auto;
        padding: 35px clamp(16px, 2.5vw, 40px) 80px !important;
        box-sizing: border-box;
    }

    /* FILTER & SEARCH BAR */
    .mvc-filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--mvc-border);
        border-radius: var(--mvc-radius);
        padding: 16px 20px;
        margin-bottom: 30px;
        box-shadow: var(--mvc-shadow-subtle);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .mvc-filter-top-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }
    .mvc-search-box {
        position: relative;
        flex: 1;
        min-width: 260px;
        max-width: 450px;
    }
    .mvc-search-input {
        width: 100%;
        padding: 10px 16px 10px 40px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: var(--mvc-transition);
        background: #f8fafc;
    }
    .mvc-search-input:focus {
        border-color: var(--mvc-navy);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
    }
    .mvc-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
    }
    .mvc-result-counter {
        font-size: 13.5px;
        color: #64748b;
        font-weight: 600;
    }
    .mvc-result-counter strong {
        color: var(--mvc-navy);
    }
    .mvc-filter-tabs-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .mvc-filter-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--mvc-navy);
        margin-right: 4px;
    }
    .mvc-filter-btn {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: var(--mvc-transition);
    }
    .mvc-filter-btn:hover {
        background: #e2e8f0;
        color: var(--mvc-navy);
    }
    .mvc-filter-btn.is-active {
        background: var(--mvc-maroon);
        border-color: var(--mvc-maroon);
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(128, 0, 0, 0.25);
    }

    /* ----------------------------------------------------
       PHOTO GALLERY GRID
       ---------------------------------------------------- */
    .mvc-photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }
    .mvc-album-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        overflow: hidden;
        box-shadow: var(--mvc-shadow-subtle);
        transition: var(--mvc-transition);
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .mvc-album-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--mvc-shadow-elevated);
        border-color: #cbd5e1;
    }
    .mvc-album-media-wrap {
        position: relative;
        height: 230px;
        background: #0f172a;
        overflow: hidden;
        cursor: pointer;
    }
    .mvc-album-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        display: block;
    }
    .mvc-album-card:hover .mvc-album-img {
        transform: scale(1.08);
    }
    .mvc-album-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 21, 46, 0.85) 0%, rgba(0, 21, 46, 0.2) 60%, transparent 100%);
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        padding: 16px;
        pointer-events: none;
    }
    .mvc-album-badge-year {
        background: var(--mvc-gold);
        color: var(--mvc-navy-dark);
        font-size: 11.5px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 4px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .mvc-album-badge-count {
        background: rgba(0, 0, 0, 0.75);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .mvc-album-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .mvc-album-cat-tag {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--mvc-maroon);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .mvc-album-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--mvc-navy);
        line-height: 1.4;
        margin: 0 0 8px;
    }
    .mvc-album-title-en {
        font-size: 13px;
        color: #64748b;
        margin: 0 0 12px;
        line-height: 1.4;
    }
    .mvc-album-desc {
        font-size: 13.5px;
        color: #475569;
        line-height: 1.5;
        margin: 0 0 18px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .mvc-album-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }
    .mvc-btn-view-lightbox {
        flex: 1;
        background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-light) 100%);
        color: #ffffff;
        border: none;
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: var(--mvc-transition);
        text-decoration: none;
    }
    .mvc-btn-view-lightbox:hover {
        background: linear-gradient(135deg, var(--mvc-maroon) 0%, var(--mvc-maroon-dark) 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
    }
    .mvc-btn-direct-access {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #475569;
        padding: 9px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--mvc-transition);
    }
    .mvc-btn-direct-access:hover {
        background: #ffffff;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy);
    }

    /* ----------------------------------------------------
       VIDEO GALLERY SPECIFIC
       ---------------------------------------------------- */
    .mvc-featured-cinema-stage {
        background: #00152e;
        border-radius: var(--mvc-radius);
        padding: 24px;
        margin-bottom: 35px;
        box-shadow: var(--mvc-shadow-elevated);
        border: 1px solid rgba(212, 175, 55, 0.3);
    }
    .mvc-cinema-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .mvc-cinema-tag {
        background: var(--mvc-maroon);
        color: #ffffff;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-cinema-tag::before {
        content: '●';
        color: #ef4444;
        animation: mvc-pulse-dot 1.5s infinite;
    }
    @keyframes mvc-pulse-dot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
    .mvc-cinema-yt-link {
        color: var(--mvc-gold-light);
        font-size: 13px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--mvc-transition);
    }
    .mvc-cinema-yt-link:hover {
        color: #ffffff;
        text-decoration: underline;
    }
    .mvc-cinema-player-frame {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        overflow: hidden;
        border-radius: 8px;
        background: #000000;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    .mvc-cinema-player-frame iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
    .mvc-cinema-meta-info {
        margin-top: 18px;
        color: #ffffff;
    }
    .mvc-cinema-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 6px;
        color: #ffffff;
    }
    .mvc-cinema-desc {
        font-size: 14px;
        color: #94a3b8;
        margin: 0;
    }

    .mvc-video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }
    .mvc-video-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        overflow: hidden;
        box-shadow: var(--mvc-shadow-subtle);
        transition: var(--mvc-transition);
        display: flex;
        flex-direction: column;
    }
    .mvc-video-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--mvc-shadow-elevated);
        border-color: #cbd5e1;
    }
    .mvc-video-thumb-wrap {
        position: relative;
        height: 190px;
        background: #0f172a;
        overflow: hidden;
        cursor: pointer;
    }
    .mvc-video-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .mvc-video-card:hover .mvc-video-thumb-img {
        transform: scale(1.06);
    }
    .mvc-video-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 54px;
        height: 54px;
        background: rgba(220, 38, 38, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.5);
        transition: var(--mvc-transition);
        pointer-events: none;
    }
    .mvc-video-play-btn svg {
        width: 22px;
        height: 22px;
        margin-left: 3px;
        fill: currentColor;
    }
    .mvc-video-card:hover .mvc-video-play-btn {
        transform: translate(-50%, -50%) scale(1.15);
        background: #ef4444;
    }
    .mvc-video-badge-overlay {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.8);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .mvc-video-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .mvc-video-category-tag {
        font-size: 11px;
        font-weight: 700;
        color: var(--mvc-maroon);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .mvc-video-card-title {
        font-size: 15.5px;
        font-weight: 700;
        color: var(--mvc-navy);
        line-height: 1.4;
        margin: 0 0 14px;
        flex: 1;
    }
    .mvc-video-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }
    .mvc-btn-play-modal {
        flex: 1;
        background: var(--mvc-navy);
        color: #ffffff;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: var(--mvc-transition);
    }
    .mvc-btn-play-modal:hover {
        background: var(--mvc-maroon);
    }
    .mvc-btn-yt-direct {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: var(--mvc-transition);
    }
    .mvc-btn-yt-direct:hover {
        background: #dc2626;
        color: #ffffff;
    }

    /* ----------------------------------------------------
       PRESS & MEDIA SPECIFIC
       ---------------------------------------------------- */
    .mvc-press-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 25px;
    }
    .mvc-press-clipping-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        overflow: hidden;
        box-shadow: var(--mvc-shadow-subtle);
        transition: var(--mvc-transition);
        display: flex;
        flex-direction: column;
    }
    .mvc-press-clipping-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--mvc-shadow-elevated);
        border-color: #cbd5e1;
    }
    .mvc-press-scan-wrap {
        position: relative;
        height: 220px;
        background: #f1f5f9;
        overflow: hidden;
        cursor: pointer;
        border-bottom: 1px solid #e2e8f0;
    }
    .mvc-press-scan-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.5s ease;
    }
    .mvc-press-clipping-card:hover .mvc-press-scan-img {
        transform: scale(1.05);
    }
    .mvc-press-badge-ribbon {
        position: absolute;
        top: 12px;
        left: 12px;
        background: var(--mvc-maroon);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
    .mvc-press-year-tag {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 33, 71, 0.85);
        color: var(--mvc-gold-light);
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        backdrop-filter: blur(4px);
    }
    .mvc-press-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .mvc-press-meta-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
    }
    .mvc-press-pub-source {
        font-weight: 600;
        color: var(--mvc-navy);
    }
    .mvc-press-headline {
        font-size: 16px;
        font-weight: 700;
        color: var(--mvc-navy);
        line-height: 1.45;
        margin: 0 0 14px;
        flex: 1;
    }
    .mvc-press-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }
    .mvc-btn-press-inspect {
        flex: 1;
        background: var(--mvc-navy);
        color: #ffffff;
        border: none;
        padding: 9px 12px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: var(--mvc-transition);
        text-decoration: none;
    }
    .mvc-btn-press-inspect:hover {
        background: var(--mvc-maroon);
        color: #ffffff;
    }
    .mvc-btn-press-direct {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #475569;
        padding: 9px 12px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: var(--mvc-transition);
    }
    .mvc-btn-press-direct:hover {
        background: #ffffff;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy);
    }

    /* ----------------------------------------------------
       INTERACTIVE LIGHTBOX & VIDEO MODAL (VANILLA JS)
       ---------------------------------------------------- */
    .mvc-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 15, 33, 0.94);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
        padding: 20px;
        backdrop-filter: blur(10px);
    }
    .mvc-modal-overlay.is-open {
        opacity: 1;
        visibility: visible;
    }
    .mvc-modal-container {
        position: relative;
        max-width: 1050px;
        width: 100%;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        background: #0f172a;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
        overflow: hidden;
    }
    .mvc-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        background: rgba(0, 21, 46, 0.9);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }
    .mvc-modal-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--mvc-gold-light);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 80%;
    }
    .mvc-modal-close-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #ffffff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--mvc-transition);
    }
    .mvc-modal-close-btn:hover {
        background: #ef4444;
    }
    .mvc-modal-content-area {
        position: relative;
        flex: 1;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        background: #050b14;
    }
    .mvc-lightbox-main-img {
        max-height: 72vh;
        max-width: 100%;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
    }
    .mvc-lightbox-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 33, 71, 0.85);
        color: #ffffff;
        border: 1px solid rgba(212, 175, 55, 0.4);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: var(--mvc-transition);
        z-index: 10;
    }
    .mvc-lightbox-nav-btn:hover {
        background: var(--mvc-maroon);
        border-color: #ffffff;
        transform: translateY(-50%) scale(1.1);
    }
    .mvc-lightbox-btn-prev { left: 16px; }
    .mvc-lightbox-btn-next { right: 16px; }

    .mvc-modal-footer {
        padding: 12px 20px;
        background: rgba(0, 21, 46, 0.9);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #cbd5e1;
        font-size: 13px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .mvc-modal-counter {
        font-weight: 600;
        color: var(--mvc-gold-light);
    }
    .mvc-modal-direct-link {
        color: #ffffff;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.15);
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px;
        transition: var(--mvc-transition);
    }
    .mvc-modal-direct-link:hover {
        background: var(--mvc-gold);
        color: var(--mvc-navy-dark);
    }

    /* Video Modal Container */
    .mvc-video-modal-container {
        max-width: 900px;
    }
    .mvc-video-modal-iframe-wrap {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        width: 100%;
        background: #000000;
    }
    .mvc-video-modal-iframe-wrap iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* CTA BANNER */
    .mvc-media-cta-banner {
        margin-top: 55px;
        background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-maroon) 100%);
        border-radius: var(--mvc-radius);
        padding: 35px 30px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        flex-wrap: wrap;
        border: 1px solid rgba(212, 175, 55, 0.3);
        box-shadow: var(--mvc-shadow-elevated);
    }
    .mvc-cta-content h3 {
        font-size: 22px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 8px;
    }
    .mvc-cta-content p {
        font-size: 14.5px;
        color: #cbd5e1;
        margin: 0;
        max-width: 650px;
    }
    .mvc-cta-btn-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .mvc-cta-btn-gold {
        background: var(--mvc-gold);
        color: var(--mvc-navy-dark);
        font-weight: 700;
        padding: 11px 22px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        transition: var(--mvc-transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-cta-btn-gold:hover {
        background: #ffffff;
        color: var(--mvc-navy);
        transform: translateY(-2px);
    }

    /* RESPONSIVE BREAKPOINTS */
    @media (max-width: 991px) {
        .mvc-media-suite-nav {
            top: 52px;
            padding: 8px 12px;
        }
        .mvc-media-mobile-select-wrap {
            display: none !important;
        }
        .mvc-media-pills-container {
            display: flex !important;
            align-items: center;
            gap: 6px;
            width: 100%;
        }
        .mvc-nav-arrow-btn {
            display: inline-flex !important;
            width: 34px;
            height: 34px;
            min-width: 34px;
        }
        .mvc-media-pills {
            display: flex !important;
            gap: 8px;
        }
        .mvc-photo-grid, .mvc-press-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 640px) {
        .mvc-media-hero {
            padding: 35px 16px 30px;
        }
        .mvc-media-hero-title {
            font-size: 24px;
        }
        .mvc-media-hero-subtitle-hi {
            font-size: 16px;
        }
        .mvc-media-hero-desc {
            font-size: 14px;
        }
        .mvc-media-body-container {
            padding: 20px 14px 60px;
        }
        .mvc-photo-grid, .mvc-video-grid, .mvc-press-grid {
            grid-template-columns: 1fr;
        }
        .mvc-search-box {
            max-width: 100%;
        }
        .mvc-media-cta-banner {
            padding: 24px 18px;
        }
    }

    /* -------------------------------------------------------------
       PAGINATION DECK (10/10 MASTERPIECE)
       ------------------------------------------------------------- */
    .mvc-pagination-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        background: #FFFFFF;
        border: 1px solid var(--mvc-border);
        border-radius: var(--mvc-radius);
        padding: 14px 20px;
        margin-top: 36px;
        box-shadow: var(--mvc-shadow-subtle);
    }

    .mvc-pagination-info {
        font-size: 13.5px;
        color: #64748B;
        font-weight: 600;
    }

    .mvc-pagination-info strong {
        color: var(--mvc-navy);
        font-weight: 700;
    }

    .mvc-pagination-controls {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .mvc-pag-btn,
    .mvc-pag-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 700;
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        color: var(--mvc-navy);
        cursor: pointer;
        transition: var(--mvc-transition);
        user-select: none;
    }

    .mvc-pag-btn:hover:not(:disabled),
    .mvc-pag-num:hover:not(.is-active) {
        background: #EEF2F6;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy);
        transform: translateY(-1px);
    }

    .mvc-pag-num.is-active {
        background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-light) 100%);
        border-color: var(--mvc-gold);
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(0, 33, 71, 0.25);
    }

    .mvc-pag-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #F1F5F9;
        border-color: #E2E8F0;
        color: #94A3B8;
        transform: none !important;
    }

    @media (max-width: 640px) {
        .mvc-pagination-bar {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 16px;
            gap: 12px;
        }
        .mvc-pagination-controls {
            justify-content: center;
            width: 100%;
        }
        .mvc-pag-btn, .mvc-pag-num {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            font-size: 13px;
        }
    }
    </style>
    <?php
}

/**
 * Shared Client-Side Vanilla JS Lightbox and Filter Controller
 */
function mvc_print_media_suite_scripts() {
    ?>
    <script id="mvc-media-suite-core-js">
    (function() {
        'use strict';

        // 1. FILTERING ENGINE
        const searchInput = document.getElementById('mvcMediaSearch');
        const filterBtns = document.querySelectorAll('.mvc-filter-btn');
        const countDisplay = document.getElementById('mvcFilterCount');
        const cards = document.querySelectorAll('.mvc-filterable-card');

        let activeYear = 'all';
        let activeCategory = 'all';
        let searchQuery = '';
        let pageSize = 9;
        let currentPage = 1;
        let matchedCards = [];

        function renderMediaPage() {
            const totalMatches = matchedCards.length;
            const totalPages = Math.ceil(totalMatches / pageSize) || 1;
            if (currentPage > totalPages) currentPage = 1;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = Math.min(startIndex + pageSize, totalMatches);

            cards.forEach(c => c.style.display = 'none');

            for (let i = startIndex; i < endIndex; i++) {
                if (matchedCards[i]) matchedCards[i].style.display = '';
            }

            const pagBar = document.getElementById('mvcMediaPagination');
            const pagStart = document.getElementById('mvcMediaPagStart');
            const pagEnd = document.getElementById('mvcMediaPagEnd');
            const pagTotal = document.getElementById('mvcMediaPagTotal');
            const pagControls = document.getElementById('mvcMediaPagControls');

            if (pagBar) {
                if (totalMatches <= pageSize) {
                    pagBar.style.display = 'none';
                } else {
                    pagBar.style.display = 'flex';
                    if (pagStart) pagStart.textContent = (totalMatches > 0) ? (startIndex + 1) : 0;
                    if (pagEnd) pagEnd.textContent = endIndex;
                    if (pagTotal) pagTotal.textContent = totalMatches;

                    if (pagControls) {
                        let html = '';
                        html += '<button type="button" class="mvc-pag-btn mvc-pag-prev" ' + (currentPage === 1 ? 'disabled' : '') + ' data-page="' + (currentPage - 1) + '" aria-label="Previous Page">&#9664; Prev</button>';

                        for (let p = 1; p <= totalPages; p++) {
                            if (totalPages <= 7 || p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                                html += '<button type="button" class="mvc-pag-num ' + (p === currentPage ? 'is-active' : '') + '" data-page="' + p + '">' + p + '</button>';
                            } else if (p === 2 && currentPage > 3) {
                                html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                p = currentPage - 2;
                            } else if (p === currentPage + 2 && p < totalPages) {
                                html += '<span class="mvc-pag-ellipsis" style="padding: 0 4px; color: #94A3B8;">&hellip;</span>';
                                p = totalPages - 1;
                            }
                        }

                        html += '<button type="button" class="mvc-pag-btn mvc-pag-next" ' + (currentPage === totalPages ? 'disabled' : '') + ' data-page="' + (currentPage + 1) + '" aria-label="Next Page">Next &#9654;</button>';
                        pagControls.innerHTML = html;

                        pagControls.querySelectorAll('button[data-page]').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const targetPage = parseInt(this.getAttribute('data-page'), 10);
                                if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages && targetPage !== currentPage) {
                                    currentPage = targetPage;
                                    renderMediaPage();
                                    const mainContent = document.getElementById('mainContent') || document.querySelector('.mvc-media-body-container');
                                    if (mainContent) {
                                        const topPos = mainContent.getBoundingClientRect().top + window.pageYOffset - 90;
                                        window.scrollTo({ top: Math.max(0, topPos), behavior: 'smooth' });
                                    }
                                }
                            });
                        });
                    }
                }
            }
        }

        function updateFilter(resetPage) {
            if (resetPage !== false) {
                currentPage = 1;
            }
            matchedCards = [];
            const query = searchQuery.toLowerCase().trim();

            cards.forEach(card => {
                const cardYear = card.getAttribute('data-year') || '';
                const cardCat = card.getAttribute('data-category') || '';
                const cardText = card.textContent.toLowerCase();

                const matchYear = (activeYear === 'all' || cardYear === activeYear);
                const matchCat = (activeCategory === 'all' || cardCat.toLowerCase().indexOf(activeCategory.toLowerCase()) !== -1);
                const matchSearch = (!query || cardText.indexOf(query) !== -1);

                if (matchYear && matchCat && matchSearch) {
                    matchedCards.push(card);
                }
            });

            if (countDisplay) {
                countDisplay.textContent = matchedCards.length;
            }

            renderMediaPage();
        }

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                searchQuery = e.target.value;
                updateFilter(true);
            });
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filterType = this.getAttribute('data-filter-type');
                const filterVal = this.getAttribute('data-filter-val');

                // Toggle active class inside same button group
                const parentGroup = this.closest('.mvc-filter-group') || this.parentElement;
                parentGroup.querySelectorAll('.mvc-filter-btn').forEach(b => b.classList.remove('is-active'));
                this.classList.add('is-active');

                if (filterType === 'year') {
                    activeYear = filterVal;
                } else if (filterType === 'category') {
                    activeCategory = filterVal;
                }

                updateFilter(true);
            });
        });

        // Initial Load Execution
        updateFilter(true);

        // 2. PHOTO & PRESS LIGHTBOX ENGINE
        const lightboxModal = document.getElementById('mvcLightboxModal');
        const lightboxImg = document.getElementById('mvcLightboxImg');
        const lightboxTitle = document.getElementById('mvcLightboxTitle');
        const lightboxCounter = document.getElementById('mvcLightboxCounter');
        const lightboxDirect = document.getElementById('mvcLightboxDirect');
        const prevBtn = document.getElementById('mvcLightboxPrev');
        const nextBtn = document.getElementById('mvcLightboxNext');

        let currentAlbumImages = [];
        let currentImageIndex = 0;
        let currentAlbumTitle = '';

        window.mvcOpenLightbox = function(images, index, albumTitle) {
            if (!lightboxModal || !images || images.length === 0) return;
            currentAlbumImages = images;
            currentImageIndex = index || 0;
            currentAlbumTitle = albumTitle || 'Photo Gallery Preview';
            renderCurrentLightboxImage();
            lightboxModal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        };

        function renderCurrentLightboxImage() {
            if (!currentAlbumImages[currentImageIndex]) return;
            const src = currentAlbumImages[currentImageIndex];
            if (lightboxImg) lightboxImg.src = src;
            if (lightboxTitle) lightboxTitle.textContent = currentAlbumTitle;
            if (lightboxCounter) lightboxCounter.textContent = 'Photo ' + (currentImageIndex + 1) + ' of ' + currentAlbumImages.length;
            if (lightboxDirect) lightboxDirect.href = src;

            if (prevBtn) prevBtn.style.display = (currentAlbumImages.length > 1) ? 'flex' : 'none';
            if (nextBtn) nextBtn.style.display = (currentAlbumImages.length > 1) ? 'flex' : 'none';
        }

        window.mvcCloseLightbox = function() {
            if (lightboxModal) {
                lightboxModal.classList.remove('is-open');
                document.body.style.overflow = '';
            }
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : currentAlbumImages.length - 1;
                renderCurrentLightboxImage();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                currentImageIndex = (currentImageIndex < currentAlbumImages.length - 1) ? currentImageIndex + 1 : 0;
                renderCurrentLightboxImage();
            });
        }

        // 3. VIDEO CINEMA MODAL ENGINE
        const videoModal = document.getElementById('mvcVideoModal');
        const videoIframe = document.getElementById('mvcVideoIframe');
        const videoTitle = document.getElementById('mvcVideoModalTitle');
        const videoDirect = document.getElementById('mvcVideoDirect');

        window.mvcOpenVideoModal = function(ytId, title) {
            if (!videoModal || !videoIframe) return;
            const embedUrl = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1&rel=0';
            videoIframe.src = embedUrl;
            if (videoTitle) videoTitle.textContent = title || 'Official Video Broadcast';
            if (videoDirect) videoDirect.href = 'https://www.youtube.com/watch?v=' + ytId;
            videoModal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        };

        window.mvcCloseVideoModal = function() {
            if (videoModal && videoIframe) {
                videoIframe.src = '';
                videoModal.classList.remove('is-open');
                document.body.style.overflow = '';
            }
        };

        // Keyboard navigation (Esc to close, Left/Right arrow keys)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                mvcCloseLightbox();
                mvcCloseVideoModal();
            } else if (lightboxModal && lightboxModal.classList.contains('is-open')) {
                if (e.key === 'ArrowLeft' && prevBtn) prevBtn.click();
                if (e.key === 'ArrowRight' && nextBtn) nextBtn.click();
            }
        });

    })();
    </script>
    <?php
}

/**
 * 1. RENDER PHOTO GALLERY PAGE (/photo-gallery/)
 */
function mvc_render_photo_gallery_page() {
    mvc_print_media_suite_styles();

    // Query all photo album posts (gallery-cat)
    $albums_query = new WP_Query([
        'post_type'      => 'gallery-cat',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    $total_albums = $albums_query->found_posts;
    $total_photos = 0;
    $albums_data = [];
    $all_years = [];
    $all_cats = [];

    if ($albums_query->have_posts()) {
        while ($albums_query->have_posts()) {
            $albums_query->the_post();
            $pid = get_the_ID();

            $year = get_post_meta($pid, 'gallery_year', true) ?: '2026';
            $cat = get_post_meta($pid, 'gallery_category', true) ?: 'Campus Event';
            $en_title = get_post_meta($pid, 'gallery_title_en', true) ?: '';
            $photo_count = intval(get_post_meta($pid, 'gallery_photo_count', true)) ?: 1;
            $cover_url = get_post_meta($pid, 'gallery_cover_url', true);
            $raw_json = get_post_meta($pid, 'gallery_images_json', true);
            $desc = get_post_meta($pid, 'gallery_description', true);

            // Cover fallback
            if (!$cover_url && has_post_thumbnail($pid)) {
                $cover_url = get_the_post_thumbnail_url($pid, 'large');
            }
            $cover_url = mvc_resolve_media_image_url($cover_url);

            // Images array
            $images = [];
            if (!empty($raw_json)) {
                $decoded = json_decode($raw_json, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $img) {
                        $images[] = mvc_resolve_media_image_url($img);
                    }
                }
            }
            if (empty($images)) {
                $images = [$cover_url];
            }

            $total_photos += count($images);
            $all_years[$year] = true;
            $all_cats[$cat] = true;

            $albums_data[] = [
                'id'          => $pid,
                'title'       => get_the_title(),
                'title_en'    => $en_title,
                'year'        => $year,
                'category'    => $cat,
                'count'       => count($images),
                'cover_url'   => $cover_url,
                'images'      => $images,
                'desc'        => $desc ?: get_the_excerpt()
            ];
        }
        wp_reset_postdata();
    }
    krsort($all_years);
    ksort($all_cats);
    ?>
    <div class="mvc-media-page-wrapper">
        <!-- HERO -->
        <header class="mvc-media-hero">
            <div class="mvc-media-hero-container">
                <nav class="mvc-media-breadcrumbs" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url(home_url('/about-us/')); ?>">About Us</a>
                    <span>/</span>
                    <span style="color: #ffffff;">Photo Gallery</span>
                </nav>

                <div class="mvc-media-tag-badge">
                    <span>महाविद्यालयीन छायाचित्र दीर्घा • OFFICIAL PHOTOGRAPHIC REPOSITORY</span>
                </div>

                <h1 class="mvc-media-hero-title">Photo Gallery & Campus Moments</h1>

                <!-- STATS STRIP -->
                <div class="mvc-media-stats-strip">
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">📷</span>
                        <div>
                            <span class="mvc-stat-num"><?php echo esc_html($total_photos); ?>+</span>
                            <span class="mvc-stat-label">Archived Photos</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🗂️</span>
                        <div>
                            <span class="mvc-stat-num"><?php echo esc_html(count($albums_data)); ?></span>
                            <span class="mvc-stat-label">Official Albums</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🏆</span>
                        <div>
                            <span class="mvc-stat-num">2017–2026</span>
                            <span class="mvc-stat-label">Years Documented</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">💎</span>
                        <div>
                            <span class="mvc-stat-num">100% HD</span>
                            <span class="mvc-stat-label">Direct High-Res Access</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- SHARED SUITE NAV -->
        <?php mvc_render_media_suite_nav('photo-gallery'); ?>

        <!-- MAIN BODY -->
        <main class="mvc-media-body-container" id="mainContent">
            <!-- FILTER TOOLBAR -->
            <section class="mvc-filter-toolbar" aria-label="Gallery Filters">
                <div class="mvc-filter-top-row">
                    <div class="mvc-search-box">
                        <span class="mvc-search-icon">🔍</span>
                        <input type="text" id="mvcMediaSearch" class="mvc-search-input" placeholder="Search albums, sports, seminars, celebrations..." aria-label="Search photo albums">
                    </div>
                    <div class="mvc-result-counter">
                        Showing <strong id="mvcFilterCount"><?php echo count($albums_data); ?></strong> Albums
                    </div>
                </div>

                <!-- Year Filter Tabs -->
                <div class="mvc-filter-tabs-row mvc-filter-group">
                    <span class="mvc-filter-label">Filter by Year:</span>
                    <button type="button" class="mvc-filter-btn is-active" data-filter-type="year" data-filter-val="all">All Years</button>
                    <?php foreach (array_keys($all_years) as $y): ?>
                        <button type="button" class="mvc-filter-btn" data-filter-type="year" data-filter-val="<?php echo esc_attr($y); ?>"><?php echo esc_html($y); ?></button>
                    <?php endforeach; ?>
                </div>

                <!-- Category Filter Tabs -->
                <div class="mvc-filter-tabs-row mvc-filter-group">
                    <span class="mvc-filter-label">Category:</span>
                    <button type="button" class="mvc-filter-btn is-active" data-filter-type="category" data-filter-val="all">All Categories</button>
                    <?php foreach (array_keys($all_cats) as $c): ?>
                        <button type="button" class="mvc-filter-btn" data-filter-type="category" data-filter-val="<?php echo esc_attr($c); ?>"><?php echo esc_html($c); ?></button>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ALBUM GRID -->
            <section class="mvc-photo-grid" aria-label="Photo Albums Grid">
                <?php foreach ($albums_data as $album): 
                    $images_json = esc_attr(json_encode($album['images']));
                    $album_title_esc = esc_attr($album['title']);
                ?>
                    <article class="mvc-album-card mvc-filterable-card" data-year="<?php echo esc_attr($album['year']); ?>" data-category="<?php echo esc_attr($album['category']); ?>">
                        <!-- Image Container with Dual Action -->
                        <div class="mvc-album-media-wrap" onclick="mvcOpenLightbox(<?php echo $images_json; ?>, 0, '<?php echo $album_title_esc; ?>');" title="Click to open album lightbox">
                            <img src="<?php echo esc_url($album['cover_url']); ?>" alt="<?php echo esc_attr($album['title']); ?>" class="mvc-album-img" loading="lazy">
                            <div class="mvc-album-overlay">
                                <span class="mvc-album-badge-year">Year <?php echo esc_html($album['year']); ?></span>
                                <span class="mvc-album-badge-count">📷 <?php echo esc_html($album['count']); ?> Photos</span>
                            </div>
                        </div>

                        <div class="mvc-album-body">
                            <span class="mvc-album-cat-tag"><?php echo esc_html($album['category']); ?></span>
                            <h2 class="mvc-album-title"><?php echo esc_html($album['title']); ?></h2>
                            <?php if (!empty($album['title_en'])): ?>
                                <div class="mvc-album-title-en"><?php echo esc_html($album['title_en']); ?></div>
                            <?php endif; ?>
                            <p class="mvc-album-desc"><?php echo esc_html($album['desc']); ?></p>

                            <!-- Dual Action Principle -->
                            <div class="mvc-album-actions">
                                <button type="button" class="mvc-btn-view-lightbox" onclick="mvcOpenLightbox(<?php echo $images_json; ?>, 0, '<?php echo $album_title_esc; ?>');">
                                    <span>🔍 View Photos (<?php echo esc_html($album['count']); ?>)</span>
                                </button>
                                <a href="<?php echo esc_url($album['cover_url']); ?>" target="_blank" rel="noopener" class="mvc-btn-direct-access" title="Open full-resolution cover image in new tab">
                                    <span>Full-Res ↗</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
            <nav class="mvc-pagination-bar" id="mvcMediaPagination" aria-label="Photo albums pagination" style="display:none;">
                <div class="mvc-pagination-info">
                    Showing <strong id="mvcMediaPagStart">1</strong>–<strong id="mvcMediaPagEnd">9</strong> of <strong id="mvcMediaPagTotal">0</strong> Photo Albums
                </div>
                <div class="mvc-pagination-controls" id="mvcMediaPagControls"></div>
            </nav>

            <!-- CALL TO ACTION BANNER -->
            <aside class="mvc-media-cta-banner">
                <div class="mvc-cta-content">
                    <h3>Looking for Video Coverage or Press Clippings?</h3>
                    <p>Maharshi Vishwamitra Mahavidyalaya maintains comprehensive audiovisual recordings and print newspaper archives for all collegiate milestones.</p>
                </div>
                <div class="mvc-cta-btn-group">
                    <a href="<?php echo esc_url(home_url('/video-gallery/')); ?>" class="mvc-cta-btn-gold">🎥 Explore Video Gallery</a>
                    <a href="<?php echo esc_url(home_url('/media/')); ?>" class="mvc-cta-btn-gold" style="background: rgba(255,255,255,0.2); color: #ffffff;">📰 View Press Clippings</a>
                </div>
            </aside>
        </main>
    </div>

    <!-- SHARED LIGHTBOX MODAL DIALOG -->
    <div id="mvcLightboxModal" class="mvc-modal-overlay" onclick="if(event.target === this) mvcCloseLightbox();" role="dialog" aria-modal="true" aria-label="Photo Lightbox">
        <div class="mvc-modal-container">
            <header class="mvc-modal-header">
                <h3 id="mvcLightboxTitle" class="mvc-modal-title">Album Preview</h3>
                <button type="button" class="mvc-modal-close-btn" onclick="mvcCloseLightbox();" aria-label="Close dialog">✕</button>
            </header>
            <div class="mvc-modal-content-area">
                <button type="button" id="mvcLightboxPrev" class="mvc-lightbox-nav-btn mvc-lightbox-btn-prev" aria-label="Previous photo">❮</button>
                <img id="mvcLightboxImg" src="" alt="Album photo in full resolution" class="mvc-lightbox-main-img">
                <button type="button" id="mvcLightboxNext" class="mvc-lightbox-nav-btn mvc-lightbox-btn-next" aria-label="Next photo">❯</button>
            </div>
            <footer class="mvc-modal-footer">
                <span id="mvcLightboxCounter" class="mvc-modal-counter">Photo 1 of 1</span>
                <a id="mvcLightboxDirect" href="" target="_blank" rel="noopener" class="mvc-modal-direct-link">Open Original File ↗</a>
            </footer>
        </div>
    </div>

    <?php
    mvc_print_media_suite_scripts();
}

/**
 * 2. RENDER VIDEO GALLERY PAGE (/video-gallery/)
 */
function mvc_render_video_gallery_page() {
    mvc_print_media_suite_styles();

    $videos_query = new WP_Query([
        'post_type'      => 'video',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    $videos_data = [];
    $featured_video = null;
    $all_cats = [];
    $all_years = [];

    if ($videos_query->have_posts()) {
        while ($videos_query->have_posts()) {
            $videos_query->the_post();
            $vid_id = get_the_ID();
            $url = get_post_meta($vid_id, 'youtube_url', true);
            $cat = get_post_meta($vid_id, 'video_category', true) ?: 'Campus Event';
            $year = get_post_meta($vid_id, 'video_year', true) ?: '2026';
            $is_feat = get_post_meta($vid_id, 'featured_video', true);

            $yt_id = '';
            if (preg_match('/(?:embed\/|v=|youtu\.be\/)([\w-]{11})/', $url, $m)) {
                $yt_id = $m[1];
            }

            if ($yt_id) {
                $item = [
                    'id'       => $vid_id,
                    'title'    => get_the_title(),
                    'yt_id'    => $yt_id,
                    'url'      => "https://www.youtube.com/watch?v={$yt_id}",
                    'thumb'    => "https://img.youtube.com/vi/{$yt_id}/hqdefault.jpg",
                    'category' => $cat,
                    'year'     => $year,
                    'featured' => $is_feat
                ];
                $videos_data[] = $item;
                $all_cats[$cat] = true;
                $all_years[$year] = true;

                if ($is_feat && !$featured_video) {
                    $featured_video = $item;
                }
            }
        }
        wp_reset_postdata();
    }

    if (!$featured_video && !empty($videos_data)) {
        $featured_video = $videos_data[0];
    }
    krsort($all_years);
    ksort($all_cats);
    ?>
    <div class="mvc-media-page-wrapper">
        <!-- HERO -->
        <header class="mvc-media-hero">
            <div class="mvc-media-hero-container">
                <nav class="mvc-media-breadcrumbs" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url(home_url('/about-us/')); ?>">About Us</a>
                    <span>/</span>
                    <span style="color: #ffffff;">Video Gallery</span>
                </nav>

                <div class="mvc-media-tag-badge">
                    <span>आधिकारिक दृश्य-श्रव्य दीर्घा • OFFICIAL VIDEO ARCHIVE</span>
                </div>

                <h1 class="mvc-media-hero-title">Video Gallery & Broadcast Media</h1>

                <!-- STATS STRIP -->
                <div class="mvc-media-stats-strip">
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🎥</span>
                        <div>
                            <span class="mvc-stat-num"><?php echo count($videos_data); ?></span>
                            <span class="mvc-stat-label">Official Broadcasts</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🏅</span>
                        <div>
                            <span class="mvc-stat-num">Khel Mahotsav</span>
                            <span class="mvc-stat-label">Championship Highlights</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">📺</span>
                        <div>
                            <span class="mvc-stat-num">YouTube HD</span>
                            <span class="mvc-stat-label">Cinema Quality Stream</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🔔</span>
                        <div>
                            <span class="mvc-stat-num">MV College</span>
                            <span class="mvc-stat-label">Official Channel</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- SHARED SUITE NAV -->
        <?php mvc_render_media_suite_nav('video-gallery'); ?>

        <!-- MAIN BODY -->
        <main class="mvc-media-body-container" id="mainContent">
            <!-- FEATURED CINEMA STAGE -->
            <?php if ($featured_video): ?>
                <section class="mvc-featured-cinema-stage" aria-label="Featured College Video">
                    <div class="mvc-cinema-header">
                        <span class="mvc-cinema-tag">Featured Broadcast</span>
                        <a href="https://www.youtube.com/results?search_query=mv+college+buxar" target="_blank" rel="noopener" class="mvc-cinema-yt-link">
                            <span>Watch on YouTube Channel ↗</span>
                        </a>
                    </div>
                    <div class="mvc-cinema-player-frame">
                        <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($featured_video['yt_id']); ?>?rel=0" title="<?php echo esc_attr($featured_video['title']); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="mvc-cinema-meta-info">
                        <h2 class="mvc-cinema-title"><?php echo esc_html($featured_video['title']); ?></h2>
                        <p class="mvc-cinema-desc">Category: <?php echo esc_html($featured_video['category']); ?> • Session: <?php echo esc_html($featured_video['year']); ?> • Maharshi Vishwamitra Mahavidyalaya, Buxar</p>
                    </div>
                </section>
            <?php endif; ?>

            <!-- FILTER TOOLBAR -->
            <section class="mvc-filter-toolbar" aria-label="Video Filters">
                <div class="mvc-filter-top-row">
                    <div class="mvc-search-box">
                        <span class="mvc-search-icon">🔍</span>
                        <input type="text" id="mvcMediaSearch" class="mvc-search-input" placeholder="Search videos, sports, day 2, day 3, seminars..." aria-label="Search video archive">
                    </div>
                    <div class="mvc-result-counter">
                        Showing <strong id="mvcFilterCount"><?php echo count($videos_data); ?></strong> Videos
                    </div>
                </div>

                <!-- Year Filter Tabs -->
                <div class="mvc-filter-tabs-row mvc-filter-group">
                    <span class="mvc-filter-label">Filter by Year:</span>
                    <button type="button" class="mvc-filter-btn is-active" data-filter-type="year" data-filter-val="all">All Years</button>
                    <?php foreach (array_keys($all_years) as $y): ?>
                        <button type="button" class="mvc-filter-btn" data-filter-type="year" data-filter-val="<?php echo esc_attr($y); ?>"><?php echo esc_html($y); ?></button>
                    <?php endforeach; ?>
                </div>

                <!-- Category Filter Tabs -->
                <div class="mvc-filter-tabs-row mvc-filter-group">
                    <span class="mvc-filter-label">Category:</span>
                    <button type="button" class="mvc-filter-btn is-active" data-filter-type="category" data-filter-val="all">All Categories</button>
                    <?php foreach (array_keys($all_cats) as $c): ?>
                        <button type="button" class="mvc-filter-btn" data-filter-type="category" data-filter-val="<?php echo esc_attr($c); ?>"><?php echo esc_html($c); ?></button>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- VIDEO GRID -->
            <section class="mvc-video-grid" aria-label="Video Archives Grid">
                <?php foreach ($videos_data as $vid): 
                    $title_esc = esc_attr($vid['title']);
                ?>
                    <article class="mvc-video-card mvc-filterable-card" data-year="<?php echo esc_attr($vid['year']); ?>" data-category="<?php echo esc_attr($vid['category']); ?>">
                        <!-- Thumbnail with play trigger -->
                        <div class="mvc-video-thumb-wrap" onclick="mvcOpenVideoModal('<?php echo esc_attr($vid['yt_id']); ?>', '<?php echo $title_esc; ?>');" title="Play video">
                            <img src="<?php echo esc_url($vid['thumb']); ?>" alt="<?php echo esc_attr($vid['title']); ?>" class="mvc-video-thumb-img" loading="lazy">
                            <div class="mvc-video-play-btn" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <span class="mvc-video-badge-overlay"><?php echo esc_html($vid['year']); ?></span>
                        </div>

                        <div class="mvc-video-body">
                            <span class="mvc-video-category-tag"><?php echo esc_html($vid['category']); ?></span>
                            <h3 class="mvc-video-card-title"><?php echo esc_html($vid['title']); ?></h3>

                            <!-- Dual Action -->
                            <div class="mvc-video-actions">
                                <button type="button" class="mvc-btn-play-modal" onclick="mvcOpenVideoModal('<?php echo esc_attr($vid['yt_id']); ?>', '<?php echo $title_esc; ?>');">
                                    <span>▶ Play Modal</span>
                                </button>
                                <a href="<?php echo esc_url($vid['url']); ?>" target="_blank" rel="noopener" class="mvc-btn-yt-direct" title="Watch directly on YouTube">
                                    <span>YouTube ↗</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
            <nav class="mvc-pagination-bar" id="mvcMediaPagination" aria-label="Video broadcasts pagination" style="display:none;">
                <div class="mvc-pagination-info">
                    Showing <strong id="mvcMediaPagStart">1</strong>–<strong id="mvcMediaPagEnd">9</strong> of <strong id="mvcMediaPagTotal">0</strong> Video Broadcasts
                </div>
                <div class="mvc-pagination-controls" id="mvcMediaPagControls"></div>
            </nav>

            <!-- CALL TO ACTION BANNER -->
            <aside class="mvc-media-cta-banner">
                <div class="mvc-cta-content">
                    <h3>Explore High-Resolution Photographic Galleries</h3>
                    <p>Looking for still photographs from college events? Browse over 420+ high-definition photo albums from our campus repositories.</p>
                </div>
                <div class="mvc-cta-btn-group">
                    <a href="<?php echo esc_url(home_url('/photo-gallery/')); ?>" class="mvc-cta-btn-gold">📷 Open Photo Gallery</a>
                    <a href="<?php echo esc_url(home_url('/media/')); ?>" class="mvc-cta-btn-gold" style="background: rgba(255,255,255,0.2); color: #ffffff;">📰 Press Releases</a>
                </div>
            </aside>
        </main>
    </div>

    <!-- SHARED VIDEO MODAL DIALOG -->
    <div id="mvcVideoModal" class="mvc-modal-overlay" onclick="if(event.target === this) mvcCloseVideoModal();" role="dialog" aria-modal="true" aria-label="Video Player Dialog">
        <div class="mvc-modal-container mvc-video-modal-container">
            <header class="mvc-modal-header">
                <h3 id="mvcVideoModalTitle" class="mvc-modal-title">College Broadcast Player</h3>
                <button type="button" class="mvc-modal-close-btn" onclick="mvcCloseVideoModal();" aria-label="Close dialog">✕</button>
            </header>
            <div class="mvc-video-modal-iframe-wrap">
                <iframe id="mvcVideoIframe" src="" title="Video Player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <footer class="mvc-modal-footer">
                <span class="mvc-modal-counter">Maharshi Vishwamitra Mahavidyalaya Media Stream</span>
                <a id="mvcVideoDirect" href="" target="_blank" rel="noopener" class="mvc-modal-direct-link">Watch on YouTube App ↗</a>
            </footer>
        </div>
    </div>

    <?php
    mvc_print_media_suite_scripts();
}

/**
 * 3. RENDER PRESS & MEDIA PAGE (/press-media/, /media/, /media-gallery/)
 */
function mvc_render_press_media_page() {
    mvc_print_media_suite_styles();

    $press_query = new WP_Query([
        'post_type'      => 'press-media',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    $press_data = [];
    $all_years = [];

    if ($press_query->have_posts()) {
        while ($press_query->have_posts()) {
            $press_query->the_post();
            $pid = get_the_ID();

            $year = get_post_meta($pid, 'press_year', true);
            if (!$year) {
                $year = get_the_date('Y', $pid) ?: '2025';
            }
            $img_url = get_post_meta($pid, 'press_image_url', true);
            if (!$img_url && has_post_thumbnail($pid)) {
                $img_url = get_the_post_thumbnail_url($pid, 'large');
            }
            $img_url = mvc_resolve_media_image_url($img_url);

            $pub = get_post_meta($pid, 'press_publication', true) ?: 'Regional Daily Press';
            $date_str = get_the_date('d M Y', $pid);

            $press_data[] = [
                'id'          => $pid,
                'title'       => get_the_title(),
                'year'        => $year,
                'img_url'     => $img_url,
                'date'        => $date_str,
                'publication' => $pub,
                'permalink'   => get_permalink($pid)
            ];
            $all_years[$year] = true;
        }
        wp_reset_postdata();
    }
    krsort($all_years);
    ?>
    <div class="mvc-media-page-wrapper">
        <!-- HERO -->
        <header class="mvc-media-hero">
            <div class="mvc-media-hero-container">
                <nav class="mvc-media-breadcrumbs" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url(home_url('/about-us/')); ?>">About Us</a>
                    <span>/</span>
                    <span style="color: #ffffff;">Press & Media Coverage</span>
                </nav>

                <div class="mvc-media-tag-badge">
                    <span>प्रेस एवं समाचार विज्ञप्ति • VERIFIED MEDIA CLIPPINGS</span>
                </div>

                <h1 class="mvc-media-hero-title">Press & Media Coverage</h1>

                <!-- STATS STRIP -->
                <div class="mvc-media-stats-strip">
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">📰</span>
                        <div>
                            <span class="mvc-stat-num"><?php echo count($press_data); ?></span>
                            <span class="mvc-stat-label">Verified Clippings</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🗞️</span>
                        <div>
                            <span class="mvc-stat-num">Leading Dailies</span>
                            <span class="mvc-stat-label">Jagran • Prabhat • Hindustan</span>
                        </div>
                    </div>
                    <?php
                    $period_str = 'Archival Coverage';
                    if (!empty($all_years)) {
                        $min_y = min(array_keys($all_years));
                        $max_y = max(array_keys($all_years));
                        $period_str = ($min_y === $max_y) ? $min_y : "{$min_y}–{$max_y}";
                    }
                    ?>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🗓️</span>
                        <div>
                            <span class="mvc-stat-num"><?php echo esc_html($period_str); ?></span>
                            <span class="mvc-stat-label">Coverage Period</span>
                        </div>
                    </div>
                    <div class="mvc-stat-pill">
                        <span class="mvc-stat-pill-icon">🔍</span>
                        <div>
                            <span class="mvc-stat-num">Full Zoom</span>
                            <span class="mvc-stat-label">Interactive Modal Reader</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- SHARED SUITE NAV -->
        <?php mvc_render_media_suite_nav('press-media'); ?>

        <!-- MAIN BODY -->
        <main class="mvc-media-body-container" id="mainContent">
            <!-- FILTER TOOLBAR -->
            <section class="mvc-filter-toolbar" aria-label="Press Release Filters">
                <div class="mvc-filter-top-row">
                    <div class="mvc-search-box">
                        <span class="mvc-search-icon">🔍</span>
                        <input type="text" id="mvcMediaSearch" class="mvc-search-input" placeholder="Search press clippings, headlines, yoga, sports..." aria-label="Search newspaper clippings">
                    </div>
                    <div class="mvc-result-counter">
                        Showing <strong id="mvcFilterCount"><?php echo count($press_data); ?></strong> Clippings
                    </div>
                </div>

                <!-- Year Filter Tabs -->
                <div class="mvc-filter-tabs-row mvc-filter-group">
                    <span class="mvc-filter-label">Filter by Year:</span>
                    <button type="button" class="mvc-filter-btn is-active" data-filter-type="year" data-filter-val="all">All Years</button>
                    <?php foreach (array_keys($all_years) as $y): ?>
                        <button type="button" class="mvc-filter-btn" data-filter-type="year" data-filter-val="<?php echo esc_attr($y); ?>"><?php echo esc_html($y); ?></button>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- PRESS CLIPPINGS GRID -->
            <section class="mvc-press-grid" aria-label="Press Clippings Grid">
                <?php foreach ($press_data as $press): 
                    $img_json = esc_attr(json_encode([$press['img_url']]));
                    $title_esc = esc_attr($press['title']);
                ?>
                    <article class="mvc-press-clipping-card mvc-filterable-card" data-year="<?php echo esc_attr($press['year']); ?>" data-category="<?php echo esc_attr($press['publication']); ?>">
                        <!-- Scan Wrap -->
                        <div class="mvc-press-scan-wrap" onclick="mvcOpenLightbox(<?php echo $img_json; ?>, 0, '<?php echo $title_esc; ?>');" title="Click to zoom clipping">
                            <img src="<?php echo esc_url($press['img_url']); ?>" alt="<?php echo esc_attr($press['title']); ?>" class="mvc-press-scan-img" loading="lazy">
                            <span class="mvc-press-badge-ribbon">Press Scan</span>
                            <span class="mvc-press-year-tag"><?php echo esc_html($press['year']); ?></span>
                        </div>

                        <div class="mvc-press-body">
                            <div class="mvc-press-meta-bar">
                                <span class="mvc-press-pub-source"><?php echo esc_html($press['publication']); ?></span>
                                <span class="mvc-press-pub-date"><?php echo esc_html($press['date']); ?></span>
                            </div>

                            <h2 class="mvc-press-headline"><?php echo esc_html($press['title']); ?></h2>

                            <!-- Dual Action Principle -->
                            <div class="mvc-press-actions">
                                <button type="button" class="mvc-btn-press-inspect" onclick="mvcOpenLightbox(<?php echo $img_json; ?>, 0, '<?php echo $title_esc; ?>');">
                                    <span>🔍 Inspect Clipping</span>
                                </button>
                                <a href="<?php echo esc_url($press['img_url']); ?>" target="_blank" rel="noopener" class="mvc-btn-press-direct" title="Open full high-resolution newspaper scan">
                                    <span>Full Scan ↗</span>
                                </a>
                                <a href="<?php echo esc_url($press['permalink']); ?>" class="mvc-btn-press-direct" title="View details and memo">
                                    <span>Details →</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <!-- Pagination Deck (10/10 Interactive Masterpiece) -->
            <nav class="mvc-pagination-bar" id="mvcMediaPagination" aria-label="Press clippings pagination" style="display:none;">
                <div class="mvc-pagination-info">
                    Showing <strong id="mvcMediaPagStart">1</strong>–<strong id="mvcMediaPagEnd">9</strong> of <strong id="mvcMediaPagTotal">0</strong> Newspaper Clippings
                </div>
                <div class="mvc-pagination-controls" id="mvcMediaPagControls"></div>
            </nav>

            <!-- CALL TO ACTION BANNER -->
            <aside class="mvc-media-cta-banner">
                <div class="mvc-cta-content">
                    <h3>Explore High-Definition Photographs & Video Recordings</h3>
                    <p>Discover complete album archives of athletic championships, academic conferences, and cultural celebrations at M.V. College.</p>
                </div>
                <div class="mvc-cta-btn-group">
                    <a href="<?php echo esc_url(home_url('/photo-gallery/')); ?>" class="mvc-cta-btn-gold">📷 Photo Gallery</a>
                    <a href="<?php echo esc_url(home_url('/video-gallery/')); ?>" class="mvc-cta-btn-gold" style="background: rgba(255,255,255,0.2); color: #ffffff;">🎥 Video Gallery</a>
                </div>
            </aside>
        </main>
    </div>

    <!-- SHARED LIGHTBOX MODAL DIALOG -->
    <div id="mvcLightboxModal" class="mvc-modal-overlay" onclick="if(event.target === this) mvcCloseLightbox();" role="dialog" aria-modal="true" aria-label="Press Clipping Lightbox">
        <div class="mvc-modal-container">
            <header class="mvc-modal-header">
                <h3 id="mvcLightboxTitle" class="mvc-modal-title">Newspaper Clipping Viewer</h3>
                <button type="button" class="mvc-modal-close-btn" onclick="mvcCloseLightbox();" aria-label="Close dialog">✕</button>
            </header>
            <div class="mvc-modal-content-area">
                <button type="button" id="mvcLightboxPrev" class="mvc-lightbox-nav-btn mvc-lightbox-btn-prev" aria-label="Previous clipping" style="display:none;">❮</button>
                <img id="mvcLightboxImg" src="" alt="Newspaper clipping high resolution" class="mvc-lightbox-main-img">
                <button type="button" id="mvcLightboxNext" class="mvc-lightbox-nav-btn mvc-lightbox-btn-next" aria-label="Next clipping" style="display:none;">❯</button>
            </div>
            <footer class="mvc-modal-footer">
                <span id="mvcLightboxCounter" class="mvc-modal-counter">Verified Media Archive</span>
                <a id="mvcLightboxDirect" href="" target="_blank" rel="noopener" class="mvc-modal-direct-link">Open Original File ↗</a>
            </footer>
        </div>
    </div>

    <?php
    mvc_print_media_suite_scripts();
}
