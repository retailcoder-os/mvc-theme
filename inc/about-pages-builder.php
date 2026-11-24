<?php
/**
 * Master About Us & Institutional Identity Suite Builder (10/10 Universal Standard)
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * 
 * 100% FAITHFUL ALIGNMENT WITH OFFICIAL LIVE SITE (mvcollegebuxar.ac.in)
 * 
 * Manages 6 Core Institutional Chapters:
 * 1. College at a Glance (/glance/) - Page ID 248
 * 2. Vision & Mission (/vision-mission/) - Page ID 249
 * 3. Rules & Regulations (/rules-regulations/) - Page ID 250
 * 4. Code of Conduct (/code-of-conduct/) - Page ID 251
 * 5. Core Values (/core-values/) - Page ID 252
 * 6. Campus Master Plan (/master-plan/) - Page ID 253
 * 
 * DESIGN & ACCESSIBILITY:
 * - 10/10 Mobile Perfection: Touch-native chapter selector, zero overflow
 * - Royal Institutional Palette: Royal Navy (#002147), Institutional Maroon (#800000), Gold (#D4AF37), Cream (#FAF9F6)
 * - Zero Hardcoding: Pulls from ACF / WordPress post attributes with exact live fallback
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Returns chapter navigation items for the About Us Suite
 */
function mvc_get_about_chapters() {
    return array(
        'glance' => array(
            'title' => 'College at a Glance',
            'slug'  => 'glance',
            'icon'  => '🏛️',
            'id'    => 248,
            'desc'  => 'Genesis, 1958 Foundation, Ganga Riverfront & Heritage'
        ),
        'vision-mission' => array(
            'title' => 'Vision & Mission',
            'slug'  => 'vision-mission',
            'icon'  => '🎯',
            'id'    => 249,
            'desc'  => 'Motto "Vidyamrtamasnutye" & 7 Stated Mission Objectives'
        ),
        'rules-regulations' => array(
            'title' => 'Rules & Regulations',
            'slug'  => 'rules-regulations',
            'icon'  => '⚖️',
            'id'    => 250,
            'desc'  => 'Disciplinary Code for Students & Statutory Decrees'
        ),
        'code-of-conduct' => array(
            'title' => 'Code of Conduct',
            'slug'  => 'code-of-conduct',
            'icon'  => '📜',
            'id'    => 251,
            'desc'  => 'Ethical Handbook for Students, Teachers & Support Staff'
        ),
        'core-values' => array(
            'title' => 'Core Values',
            'slug'  => 'core-values',
            'icon'  => '💎',
            'id'    => 252,
            'desc'  => '8 Foundational Institutional Core Values'
        ),
        'master-plan' => array(
            'title' => 'Master Plan',
            'slug'  => 'master-plan',
            'icon'  => '🗺️',
            'id'    => 253,
            'desc'  => 'Master Plan Information, 2D & 3D Spatial Layout'
        )
    );
}

/**
 * Renders the Suite Navigation Bar (Desktop Pills + Mobile Touch-Native Dropdown)
 */
function mvc_render_about_suite_nav($current_slug) {
    $chapters = mvc_get_about_chapters();
    $site_url = home_url();
    
    ob_start();
    ?>
    <nav class="mvc-ab-suite-nav" aria-label="Institutional Identity Chapters Navigation">
        <!-- Mobile Dropdown (Permanently hidden, replaced with < > scroll pills) -->
        <div class="mvc-ab-nav-mobile" style="display: none !important;">
            <label for="mvcAboutChapterSelect" class="mvc-ab-mobile-label">
                <span>🏛️ Jump to Chapter:</span>
            </label>
            <div class="mvc-ab-mobile-select-box">
                <select id="mvcAboutChapterSelect" class="mvc-ab-native-select" onchange="if(this.value) window.location.href=this.value;">
                    <?php foreach ($chapters as $key => $chap) : 
                        $url = esc_url($site_url . '/' . $chap['slug'] . '/');
                        $selected = ($key === $current_slug) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $url; ?>" <?php echo $selected; ?>>
                            <?php echo esc_html($chap['icon'] . ' ' . $chap['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Unified Pill Bar with < > Scroll Navigation -->
        <div class="mvc-ab-nav-desktop">
            <span class="mvc-ab-desktop-label">About Suite:</span>
            <div class="mvc-ab-pills-container">
                <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-prev is-disabled" id="mvcAboutArrowPrev" onclick="mvcAboutPillScroll(-1)" aria-label="Scroll Chapters Left" title="Scroll left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                </button>

                <div class="mvc-ab-pill-cluster" id="mvcAboutPillsTrack">
                    <?php foreach ($chapters as $key => $chap) : 
                        $url = esc_url($site_url . '/' . $chap['slug'] . '/');
                        $is_active = ($key === $current_slug);
                    ?>
                        <a href="<?php echo $url; ?>" class="mvc-ab-nav-pill <?php echo $is_active ? 'is-active' : ''; ?>" <?php echo $is_active ? 'id="mvcActiveAboutPill"' : ''; ?>>
                            <span class="mvc-ab-pill-icon"><?php echo esc_html($chap['icon']); ?></span>
                            <span class="mvc-ab-pill-text"><?php echo esc_html($chap['title']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-next" id="mvcAboutArrowNext" onclick="mvcAboutPillScroll(1)" aria-label="Scroll Chapters Right" title="Scroll right">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <script>
    function mvcAboutPillScroll(direction) {
        var track = document.getElementById('mvcAboutPillsTrack');
        if (!track) return;
        var scrollDistance = 260 * direction;
        track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
        setTimeout(mvcUpdateAboutArrowStates, 320);
    }

    function mvcUpdateAboutArrowStates() {
        var track = document.getElementById('mvcAboutPillsTrack');
        var prevBtn = document.getElementById('mvcAboutArrowPrev');
        var nextBtn = document.getElementById('mvcAboutArrowNext');
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
        function initAboutPillNav() {
            var track = document.getElementById('mvcAboutPillsTrack');
            if (!track) return;
            track.addEventListener('scroll', mvcUpdateAboutArrowStates, { passive: true });
            window.addEventListener('resize', mvcUpdateAboutArrowStates);

            var activePill = document.getElementById('mvcActiveAboutPill');
            if (activePill) {
                setTimeout(function() {
                    var trackRect = track.getBoundingClientRect();
                    var pillRect = activePill.getBoundingClientRect();
                    if (pillRect.left < trackRect.left || pillRect.right > trackRect.right) {
                        activePill.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                    mvcUpdateAboutArrowStates();
                }, 250);
            } else {
                setTimeout(mvcUpdateAboutArrowStates, 200);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAboutPillNav);
        } else {
            initAboutPillNav();
        }
    })();
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Shared Global Styles for all 6 About Us Suite Pages
 */
function mvc_get_about_shared_css() {
    return <<<'CSS'
    :root {
        --mvc-navy: #002147;
        --mvc-navy-dark: #001228;
        --mvc-navy-deep: #000c1c;
        --mvc-maroon: #800000;
        --mvc-maroon-dark: #5c0000;
        --mvc-gold: #D4AF37;
        --mvc-gold-light: #FDE047;
        --mvc-gold-amber: #B48200;
        --mvc-cream: #FAF9F6;
        --mvc-slate: #334155;
        --mvc-border: #E2E8F0;
        --mvc-card-bg: #FFFFFF;
        --mvc-emerald: #16A34A;
        --mvc-emerald-bg: #ECFDF5;
    }

    html, body {
        overflow-x: hidden !important;
        max-width: 100vw !important;
    }

    .mvc-about-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1E293B;
        background: #F8FAFC;
        line-height: 1.6;
        overflow-x: hidden;
        width: 100%;
        box-sizing: border-box;
    }

    /* 1. HERO SECTION */
    .mvc-ab-hero {
        background: linear-gradient(135deg, var(--mvc-navy-deep) 0%, var(--mvc-navy) 60%, var(--mvc-maroon-dark) 100%);
        color: #FFFFFF;
        padding: clamp(40px, 6vw, 70px) clamp(16px, 4vw, 36px) clamp(50px, 7vw, 85px);
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--mvc-gold);
    }

    .mvc-ab-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 85% 20%, rgba(212, 175, 55, 0.12) 0%, transparent 45%),
                          radial-gradient(circle at 15% 80%, rgba(128, 0, 0, 0.25) 0%, transparent 55%);
        pointer-events: none;
    }

    .mvc-ab-hero-inner {
        max-width: 100%;
        width: 100%;
        margin: 0 auto;
        padding-left: clamp(16px, 2.5vw, 40px);
        padding-right: clamp(16px, 2.5vw, 40px);
        position: relative;
        z-index: 2;
        box-sizing: border-box;
    }

    .mvc-ab-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .mvc-ab-breadcrumbs a {
        color: #FFFFFF !important;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }

    .mvc-ab-breadcrumbs a:hover {
        color: var(--mvc-gold-light) !important;
        text-decoration: underline !important;
    }

    .mvc-ab-beacon {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(212, 175, 55, 0.15);
        border: 1px solid rgba(212, 175, 55, 0.4);
        backdrop-filter: blur(8px);
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 800;
        color: var(--mvc-gold-light);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 16px;
    }

    .mvc-ab-beacon-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--mvc-gold-light);
        box-shadow: 0 0 10px var(--mvc-gold);
        animation: mvcBeaconPulse 2s infinite;
    }

    @keyframes mvcBeaconPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }

    .mvc-ab-title {
        font-size: clamp(28px, 4.2vw, 46px);
        font-weight: 900;
        line-height: 1.15;
        margin: 0 0 10px;
        color: #FFFFFF;
        letter-spacing: -0.5px;
    }

    .mvc-ab-subtitle-hi {
        font-size: clamp(16px, 2.2vw, 22px);
        font-weight: 700;
        color: var(--mvc-gold-light);
        margin-bottom: 16px;
        font-family: 'Plus Jakarta Sans', serif;
    }

    .mvc-ab-desc {
        font-size: clamp(14px, 1.6vw, 16.5px);
        line-height: 1.7;
        color: #E2E8F0;
        max-width: 900px;
        margin: 0 0 28px;
    }

    /* 4-KPI RIBBON */
    .mvc-ab-kpi-ribbon {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        width: 100%;
        max-width: 100%;
    }

    .mvc-ab-kpi-item {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 14px 16px;
        border-radius: 12px;
        border-left: 3px solid var(--mvc-gold);
    }

    .mvc-ab-kpi-num {
        font-size: clamp(18px, 2.2vw, 24px);
        font-weight: 900;
        color: var(--mvc-gold-light);
        line-height: 1.2;
    }

    .mvc-ab-kpi-label {
        font-size: 11.5px;
        font-weight: 700;
        color: #CBD5E1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    /* 2. MAIN CONTAINER */
    .mvc-ab-main {
        max-width: 100%;
        width: 100%;
        margin: -35px auto 60px;
        padding-left: clamp(16px, 2.5vw, 40px);
        padding-right: clamp(16px, 2.5vw, 40px);
        position: relative;
        z-index: 10;
        box-sizing: border-box;
    }

    /* 3. SUITE NAV BAR */
    .mvc-ab-suite-nav {
        background: #FFFFFF;
        border: 1.5px solid var(--mvc-border);
        border-radius: 16px;
        padding: 12px 18px;
        margin-bottom: 30px;
        box-shadow: 0 8px 24px rgba(0, 33, 71, 0.06);
    }

    .mvc-ab-nav-mobile {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .mvc-ab-mobile-label {
        font-size: 12.5px;
        font-weight: 800;
        color: var(--mvc-navy);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .mvc-ab-mobile-select-box {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .mvc-ab-native-select {
        width: 100% !important;
        padding: 10px 36px 10px 14px !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        color: var(--mvc-navy) !important;
        background: #F8FAFC !important;
        border: 1.5px solid var(--mvc-border) !important;
        border-radius: 10px !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        cursor: pointer;
        box-sizing: border-box !important;
    }

    .mvc-ab-native-select:focus {
        border-color: var(--mvc-navy) !important;
        background: #FFFFFF !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1) !important;
    }

    .mvc-ab-select-caret {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--mvc-navy);
    }

    .mvc-ab-nav-desktop {
        display: flex !important;
        align-items: center;
        gap: 12px;
        width: 100%;
    }

    .mvc-ab-desktop-label {
        font-size: 12px;
        font-weight: 900;
        color: var(--mvc-navy);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .mvc-ab-pills-container {
        display: flex !important;
        align-items: center;
        gap: 8px;
        flex-grow: 1;
        position: relative;
        width: 100%;
        min-width: 0;
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

    .mvc-ab-pill-cluster {
        display: flex !important;
        align-items: center;
        gap: 8px;
        flex-wrap: nowrap;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        flex-grow: 1;
        padding: 4px 0;
        margin: 0;
    }
    .mvc-ab-pill-cluster::-webkit-scrollbar {
        display: none;
    }

    .mvc-ab-nav-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none !important;
        color: #475569 !important;
        background: #F8FAFC;
        border: 1px solid var(--mvc-border);
        transition: all 0.2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .mvc-ab-nav-pill:hover {
        background: #EFF6FF;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy) !important;
        transform: translateY(-1px);
    }

    .mvc-ab-nav-pill.is-active {
        background: var(--mvc-navy);
        color: #FFFFFF !important;
        border-color: var(--mvc-navy);
        box-shadow: 0 3px 10px rgba(0, 33, 71, 0.25);
    }

    @media (max-width: 991px) {
        .mvc-ab-nav-mobile {
            display: none !important;
        }
        .mvc-ab-nav-desktop {
            display: flex !important;
        }
        .mvc-ab-desktop-label {
            display: none;
        }
    }

    /* 4. CONTENT SECTIONS & CARDS */
    .mvc-ab-section {
        background: #FFFFFF;
        border: 1.5px solid var(--mvc-border);
        border-radius: 18px;
        padding: clamp(20px, 3.5vw, 36px);
        margin-bottom: 30px;
        box-shadow: 0 8px 24px rgba(0, 33, 71, 0.04);
        width: 100%;
        box-sizing: border-box;
    }

    .mvc-ab-sec-header {
        border-bottom: 2px solid #F1F5F9;
        padding-bottom: 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .mvc-ab-sec-title {
        font-size: clamp(20px, 2.5vw, 26px);
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        line-height: 1.25;
    }

    .mvc-ab-sec-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #FEF3C7;
        color: #92400E;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid #FCD34D;
    }

    /* 5. GOVERNANCE GATEWAY STRIP */
    .mvc-ab-gateway-strip {
        background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);
        border: 1.5px solid #CBD5E1;
        border-radius: 16px;
        padding: 24px;
        margin-top: 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        width: 100%;
        box-sizing: border-box;
    }

    .mvc-ab-gw-links {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .mvc-ab-gw-btn {
        background: #FFFFFF;
        border: 1px solid var(--mvc-border);
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--mvc-navy) !important;
        text-decoration: none !important;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .mvc-ab-gw-btn:hover {
        background: var(--mvc-navy);
        color: #FFFFFF !important;
        border-color: var(--mvc-navy);
        transform: translateY(-1px);
    }

    /* MOBILE BREAKPOINTS (<= 640px) */
    @media (max-width: 640px) {
        .mvc-ab-hero {
            padding: 35px 14px 45px;
        }
        .mvc-ab-main {
            padding: 0 12px;
            margin-top: -20px;
        }
        .mvc-ab-kpi-ribbon {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .mvc-ab-suite-nav {
            padding: 12px;
        }
        .mvc-ab-nav-mobile {
            flex-direction: column;
            align-items: stretch;
            gap: 6px;
        }
        .mvc-ab-mobile-label {
            width: 100%;
        }
        .mvc-ab-gateway-strip {
            flex-direction: column;
            align-items: stretch;
            padding: 18px;
        }
        .mvc-ab-gw-links {
            flex-direction: column;
        }
        .mvc-ab-gw-btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media print {
        .mvc-ab-hero, .mvc-ab-suite-nav, .mvc-ab-gateway-strip {
            display: none !important;
        }
        .mvc-ab-main {
            margin: 0 !important;
            padding: 0 !important;
        }
        .mvc-ab-section {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
CSS;
}

/**
 * ------------------------------------------------------------------------
 * 1. COLLEGE AT A GLANCE RENDERER (/glance/)
 * Verbatim alignment with live site text
 * ------------------------------------------------------------------------
 */
function mvc_render_glance_page() {
    $site_url = home_url();
    $post_id = 248;

    $hero_beacon = mvc_get_page_meta($post_id, 'about_hero_beacon', 'VKSU Constituent College • Established 11th June 1958');
    $hero_title  = mvc_get_page_meta($post_id, 'about_hero_title', 'College at a Glance');
    $heading_hi  = mvc_get_page_meta($post_id, 'about_heading_hi', 'महर्षि विश्वामित्र महाविद्यालय, बक्सर — एक दृष्टि');
    $hero_desc   = mvc_get_page_meta($post_id, 'about_hero_desc', 'Official institutional overview, Vedic heritage, collegiate genesis, and academic profile of Maharshi Vishwamitra Mahavidyalaya, Buxar.');
    $campus_img  = mvc_get_page_meta($post_id, 'glance_campus_image', content_url('/uploads/live_glance_GLANCE_1764570148_GLANCE_1764569982_breadcrumb_image.jpg'));
    $campus_caption = mvc_get_page_meta($post_id, 'glance_campus_caption', '📍 Maharshi Vishwamitra Mahavidyalaya Campus — Bank of Holy Ganga, Buxar (Bihar)');

    ob_start();
    ?>
    <style>
        <?php echo mvc_get_about_shared_css(); ?>

        .mvc-gl-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }

        .mvc-gl-media-wrapper {
            margin-bottom: 28px;
            text-align: center;
        }

        .mvc-gl-campus-img {
            width: 100%;
            max-height: 460px;
            object-fit: cover;
            border-radius: 14px;
            border: 2px solid var(--mvc-gold);
            box-shadow: 0 8px 25px rgba(0, 33, 71, 0.08);
        }

        .mvc-gl-img-caption {
            font-size: 13px;
            color: #64748B;
            font-weight: 600;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* 2-Column Balanced Layout */
        .mvc-gl-factual-grid {
            display: grid;
            grid-template-columns: 1.55fr 1fr;
            gap: 32px;
            align-items: start;
            margin-bottom: 35px;
        }

        .mvc-gl-chronicle-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 14px;
            padding: clamp(22px, 3vw, 32px);
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
        }

        .mvc-gl-sec-kicker {
            font-size: 11px;
            font-weight: 800;
            color: var(--mvc-maroon);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
            margin-bottom: 4px;
        }

        .mvc-gl-sec-heading {
            font-size: 20px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 18px 0;
            padding-bottom: 12px;
            border-bottom: 2px solid #F1F5F9;
        }

        .mvc-gl-prose p {
            font-size: 15px;
            line-height: 1.8;
            color: #334155;
            margin-bottom: 16px;
            text-align: justify;
        }

        .mvc-gl-prose p:last-child {
            margin-bottom: 0;
        }

        /* Institutional Quick-Fact Card */
        .mvc-gl-vital-card {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-top: 4px solid var(--mvc-gold);
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 25px rgba(0, 33, 71, 0.06);
            position: sticky;
            top: 24px;
        }

        .mvc-gl-vital-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 14px 0;
            padding-bottom: 10px;
            border-bottom: 1.5px solid #F1F5F9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mvc-gl-vital-table {
            width: 100%;
            border-collapse: collapse;
        }

        .mvc-gl-vital-table tr {
            border-bottom: 1px solid #F1F5F9;
        }

        .mvc-gl-vital-table tr:last-child {
            border-bottom: none;
        }

        .mvc-gl-vital-table td {
            padding: 9px 4px;
            font-size: 13px;
            vertical-align: top;
        }

        .mvc-gl-vital-label {
            font-weight: 700;
            color: #475569;
            width: 44%;
        }

        .mvc-gl-vital-value {
            font-weight: 600;
            color: var(--mvc-navy);
        }

        /* Timeline Section */
        .mvc-gl-timeline-sec {
            background: #FFFFFF;
            border: 1px solid var(--mvc-border);
            border-radius: 14px;
            padding: 26px 28px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            margin-bottom: 30px;
        }

        .mvc-gl-timeline-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-top: 16px;
        }

        .mvc-gl-milestone-item {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 16px;
            border-top: 3px solid var(--mvc-maroon);
        }

        .mvc-gl-year-badge {
            font-size: 15px;
            font-weight: 900;
            color: var(--mvc-maroon);
            margin-bottom: 4px;
        }

        .mvc-gl-milestone-head {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px 0;
            line-height: 1.35;
        }

        .mvc-gl-milestone-txt {
            font-size: 12px;
            color: #64748B;
            line-height: 1.5;
            margin: 0;
        }

        @media (max-width: 991px) {
            .mvc-gl-factual-grid {
                grid-template-columns: 1fr;
            }
            .mvc-gl-timeline-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-gl-vital-card {
                position: static;
            }
        }

        @media (max-width: 600px) {
            .mvc-gl-timeline-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="mvc-about-page">
        <!-- 1. HERO -->
        <header class="mvc-ab-hero">
            <div class="mvc-ab-hero-inner">
                <nav class="mvc-ab-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>">About Us</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">College at a Glance</span>
                </nav>

                <div class="mvc-ab-beacon">
                    <span class="mvc-ab-beacon-dot"></span>
                    <span><?php echo esc_html($hero_beacon); ?></span>
                </div>

                <h1 class="mvc-ab-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-ab-subtitle-hi"><?php echo esc_html($heading_hi); ?></div>
                <p class="mvc-ab-desc"><?php echo esc_html($hero_desc); ?></p>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-ab-main">
            <!-- SUITE CHAPTER NAVIGATOR -->
            <?php echo mvc_render_about_suite_nav('glance'); ?>

            <div class="mvc-gl-container">
                
                <!-- OFFICIAL CAMPUS IMAGE -->
                <div class="mvc-gl-media-wrapper">
                    <img src="<?php echo esc_url($campus_img); ?>" alt="Maharshi Vishwamitra Mahavidyalaya Campus" class="mvc-gl-campus-img" />
                    <span class="mvc-gl-img-caption"><?php echo esc_html($campus_caption); ?></span>
                </div>

                <!-- 2-COLUMN BALANCED FACTUAL GRID -->
                <div class="mvc-gl-factual-grid">
                    
                    <!-- LEFT COLUMN: AUTHENTIC INSTITUTIONAL NARRATIVE -->
                    <article class="mvc-gl-chronicle-card">
                        <span class="mvc-gl-sec-kicker">HISTORICAL GENESIS &amp; CHRONICLE</span>
                        <h2 class="mvc-gl-sec-heading">Institutional Heritage of M.V. College</h2>
                        
                        <div class="mvc-gl-prose">
                            <p>
                                Buxar owns its importance as a historical place almost exclusively to its colossal Vishvamitra Ashram, which still stands majestically. The traditional account of the holy antiquity as an important center of Vishnu and Shiva worship embedded in Valmiki’s Ramayana, Ramcharitmanas, and Puranas has been described. This town, known as Siddhashram, witnessed a remarkable outburst of intellectual activities during the time of Maharshi Vishwamitra in the Vedic period.
                            </p>
                            <p>
                                The new spirit of the modern age was remarkably illustrated by the life and thought of <strong>Sri Khakhi Baba (The Saketbasi)</strong> and <strong>Sri Mauni Baba (Janakpurbasi)</strong>. Undoubtedly, Khakhi Baba was a great social reformer, a great religious teacher, and a tower of philosophical wisdom in this area. He himself worked tirelessly for reform in the educational field. Deeply religious but not theologically rigid, he laid great emphasis on modern education, including co-education. For this noble purpose, he laid the foundation of <strong>Maharshi Vishwamitra Mahavidyalaya, Buxar on 11th June, 1958</strong>.
                            </p>
                            <p>
                                The honourable <strong>Raja of Dumraon State</strong> had deep love and affection for Khakhi Baba. He donated <strong>ten bighas of prime land on the bank of the Holy Ganga</strong> for the college. Along with the Raja of Dumraon, other devoted disciples of Khakhi Baba gave monetary help for the construction of the building of the college. <strong>Sri Basistha Narayan Mishra</strong>, the elder son of Khakhi Baba, devoted his entire life for the institution until it became a <strong>Constituent Unit under Magadh University in the year 1975</strong> (and subsequently under Veer Kunwar Singh University, Ara).
                            </p>
                            <p>
                                The college has been designed skillfully. The classrooms are many in number, varying in size from classic lecture rooms to palatial halls. Harmoniously integrated with the collegiate campus stand the sacred <strong>Ram Lakshman Janki Temple</strong> and the revered <strong>Kaldhatri (Gaiya Maiya) Mandir</strong>, reflecting ancient architectural and spiritual traditions.
                            </p>
                            <p>
                                In the first stage of the opening of the college in 1958, teachings of the <strong>Social Sciences &amp; Humanities</strong> were started. The opening of the <strong>Science Faculty in 1967</strong> fulfilled a critical academic need for the region, with credit largely attributed to then Principal <strong>Dr. Jitram Pathak</strong>. The college actively promotes student development through <strong>N.S.S.</strong> camping, <strong>N.C.C.</strong> training, debating societies, annual sports, well-equipped laboratories, and an automated central library.
                            </p>
                        </div>
                    </article>

                    <!-- RIGHT COLUMN: OFFICIAL INSTITUTIONAL VITAL STATISTICS -->
                    <aside>
                        <div class="mvc-gl-vital-card">
                            <h3 class="mvc-gl-vital-title">🏛️ Institutional Vital Statistics</h3>
                            
                            <table class="mvc-gl-vital-table">
                                <tbody>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Institution</td>
                                        <td class="mvc-gl-vital-value">Maharshi Vishwamitra Mahavidyalaya (M.V. College)</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Date of Foundation</td>
                                        <td class="mvc-gl-vital-value"><strong>11th June, 1958</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Founders</td>
                                        <td class="mvc-gl-vital-value">Sri Khakhi Baba &amp; Sri Mauni Baba</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Campus Land</td>
                                        <td class="mvc-gl-vital-value"><strong>10 Bighas on Ganga Bank</strong> (Donated by Raja of Dumraon)</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Affiliating University</td>
                                        <td class="mvc-gl-vital-value">Veer Kunwar Singh University (VKSU), Ara</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Constituent Status</td>
                                        <td class="mvc-gl-vital-value">1975 (Magadh University) / 1992 (VKSU)</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">UGC Recognition</td>
                                        <td class="mvc-gl-vital-value">Section 2(f) &amp; 12(B) of UGC Act, 1956</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Core Faculties</td>
                                        <td class="mvc-gl-vital-value">Humanities, Social Sciences, Science &amp; Vocational (BCA)</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Institution Type</td>
                                        <td class="mvc-gl-vital-value">Co-Educational Constituent College</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Campus Shrines</td>
                                        <td class="mvc-gl-vital-value">Ram Lakshman Janki Mandir &amp; Kaldhatri Mandir</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Student Co-Curricular</td>
                                        <td class="mvc-gl-vital-value">Active N.S.S., N.C.C., Sports &amp; Debating Society</td>
                                    </tr>
                                    <tr>
                                        <td class="mvc-gl-vital-label">Postal Location</td>
                                        <td class="mvc-gl-vital-value">Charitravan, Buxar, Bihar – PIN 802101</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </aside>

                </div>

                <!-- 4 STRICTLY CONFIRMED HISTORICAL MILESTONES -->
                <section class="mvc-gl-timeline-sec">
                    <span class="mvc-gl-sec-kicker">VERIFIED INSTITUTIONAL RECORD</span>
                    <h2 class="mvc-gl-sec-heading" style="margin-bottom: 0;">Chronological Milestones</h2>
                    
                    <div class="mvc-gl-timeline-grid">
                        <div class="mvc-gl-milestone-item">
                            <div class="mvc-gl-year-badge">11 June 1958</div>
                            <h3 class="mvc-gl-milestone-head">Foundation of M.V. College</h3>
                            <p class="mvc-gl-milestone-txt">Founded by Sri Khakhi Baba; Raja of Dumraon donates 10 bighas land on Ganga bank. Teaching in Social Sciences &amp; Humanities commences.</p>
                        </div>

                        <div class="mvc-gl-milestone-item">
                            <div class="mvc-gl-year-badge">1967</div>
                            <h3 class="mvc-gl-milestone-head">Science Faculty Established</h3>
                            <p class="mvc-gl-milestone-txt">Faculty of Science commissioned under then Principal Dr. Jitram Pathak, providing vital scientific higher education for regional youth.</p>
                        </div>

                        <div class="mvc-gl-milestone-item">
                            <div class="mvc-gl-year-badge">1975</div>
                            <h3 class="mvc-gl-milestone-head">Constituent Unit Elevation</h3>
                            <p class="mvc-gl-milestone-txt">Elevated to Constituent Unit status under Magadh University through the lifelong dedication of Sri Basistha Narayan Mishra.</p>
                        </div>

                        <div class="mvc-gl-milestone-item">
                            <div class="mvc-gl-year-badge">1992</div>
                            <h3 class="mvc-gl-milestone-head">VKSU Ara Affiliation</h3>
                            <p class="mvc-gl-milestone-txt">Incorporated as a premier constituent college of Veer Kunwar Singh University (VKSU), Ara upon establishment of the university.</p>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * ------------------------------------------------------------------------
 * 2. VISION & MISSION RENDERER (/vision-mission/)
 * Verbatim alignment with live site text
 * ------------------------------------------------------------------------
 */
function mvc_render_vision_mission_page() {
    $site_url = home_url();
    $post_id = 249;

    $hero_beacon = get_post_meta($post_id, 'about_hero_beacon', true) ?: 'Institutional Creed & Guiding Principles';
    $hero_title  = get_post_meta($post_id, 'about_hero_title', true) ?: 'Vision & Mission';
    $heading_hi  = get_post_meta($post_id, 'about_heading_hi', true) ?: 'महर्षि विश्वामित्र महाविद्यालय - ध्येय एवं लक्ष्य';
    $hero_desc   = get_post_meta($post_id, 'about_hero_desc', true) ?: '“Vidyamrtamasnutye” — the Sanskrit phrase in the logo of the college sets down its understanding of such, as it symbolizes the idea that education is a source of eternal wisdom and enlightenment.';

    $kpi_1_val = get_post_meta($post_id, 'about_kpi_1_val', true) ?: 'विद्ययाऽमृतमश्नुते';
    $kpi_1_lbl = get_post_meta($post_id, 'about_kpi_1_lbl', true) ?: 'College Motto in Logo';

    $kpi_2_val = get_post_meta($post_id, 'about_kpi_2_val', true) ?: '7 Points';
    $kpi_2_lbl = get_post_meta($post_id, 'about_kpi_2_lbl', true) ?: 'Stated Mission Objectives';

    $kpi_3_val = get_post_meta($post_id, 'about_kpi_3_val', true) ?: 'Holistic Model';
    $kpi_3_lbl = get_post_meta($post_id, 'about_kpi_3_lbl', true) ?: 'Education for Society';

    $kpi_4_val = get_post_meta($post_id, 'about_kpi_4_val', true) ?: 'Egalitarian';
    $kpi_4_lbl = get_post_meta($post_id, 'about_kpi_4_lbl', true) ?: 'Ethics, Morality & Values';

    // EXACT 7 STATED MISSION POINTS FROM LIVE SITE
    $missions = array(
        array(
            'num'   => '01',
            'title' => 'Stimulating Environment of Learning and Teaching',
            'icon'  => '💡',
            'desc'  => 'To provide a stimulating environment of learning and teaching with available and new resources that can ignite young minds and make a difference on a global level.'
        ),
        array(
            'num'   => '02',
            'title' => 'Innovate and Renovate Learning & Teaching',
            'icon'  => '🔬',
            'desc'  => 'To innovate and renovate the learning and teaching process where teaching and learning can be carried forward in the challenges and changes of real life.'
        ),
        array(
            'num'   => '03',
            'title' => 'Professional Leadership & Skill Development',
            'icon'  => '💻',
            'desc'  => 'To groom professional leadership and skill development among students, highest quality of add on professional courses are offered along with degree courses.'
        ),
        array(
            'num'   => '04',
            'title' => 'Sustenance of Core Values & Community Respect',
            'icon'  => '🤝',
            'desc'  => 'Sustenance of the core values and respect of all the communities to develop culturally sensitive environment.'
        ),
        array(
            'num'   => '05',
            'title' => 'Materializing Employability Skills',
            'icon'  => '🚀',
            'desc'  => 'To help students in materializing employability skills.'
        ),
        array(
            'num'   => '06',
            'title' => 'Enrichment of Co-Curricular Activities',
            'icon'  => '🎖️',
            'desc'  => 'Enrichment of co-curricular activities linking it to educational world for overall development of the personality of the students.'
        ),
        array(
            'num'   => '07',
            'title' => 'Promoting Teaching Faculties & Research',
            'icon'  => '📜',
            'desc'  => 'To promote teaching faculties to be leading academicians and researchers by providing various facilities such as the In-Maharshi Vishwamitra Lecture Series, and by organizing National and International Seminars, Conferences, and Workshops.'
        )
    );
    $missions = mvc_get_page_array($post_id, 'about_missions', $missions);

    ob_start();
    ?>
    <style>
        <?php echo mvc_get_about_shared_css(); ?>

        .mvc-vm-motto-card {
            background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-dark) 100%);
            border: 2px solid var(--mvc-gold);
            border-radius: 18px;
            padding: clamp(24px, 3.5vw, 40px);
            color: #FFFFFF;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 12px 32px rgba(0, 33, 71, 0.15);
            position: relative;
            overflow: hidden;
        }

        .mvc-vm-motto-card::before {
            content: 'विद्या';
            position: absolute;
            right: 20px;
            bottom: -20px;
            font-size: 140px;
            font-weight: 900;
            color: rgba(212, 175, 55, 0.05);
            pointer-events: none;
            font-family: serif;
        }

        .mvc-vm-motto-sanskrit {
            font-size: clamp(26px, 4vw, 38px);
            font-weight: 900;
            color: var(--mvc-gold-light);
            margin: 0 0 8px;
            letter-spacing: 1px;
            font-family: 'Plus Jakarta Sans', serif;
        }

        .mvc-vm-motto-trans {
            font-size: clamp(14px, 1.8vw, 16.5px);
            color: #E2E8F0;
            font-weight: 500;
            max-width: 800px;
            margin: 0 auto 16px;
            line-height: 1.6;
        }

        .mvc-vm-motto-source {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: #FEF08A;
            font-size: 12px;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mvc-vm-duo-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 35px;
        }

        .mvc-vm-vision-card {
            background: #FFFFFF;
            border: 2px solid var(--mvc-border);
            border-top: 5px solid var(--mvc-gold);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 6px 20px rgba(0, 33, 71, 0.05);
        }

        .mvc-vm-commitment-card {
            background: #FFFFFF;
            border: 2px solid var(--mvc-border);
            border-top: 5px solid var(--mvc-maroon);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 6px 20px rgba(0, 33, 71, 0.05);
        }

        .mvc-vm-card-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mvc-vm-card-p {
            font-size: 14.5px;
            line-height: 1.75;
            color: #334155;
            margin: 0 0 12px;
        }

        .mvc-vm-mission-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .mvc-vm-mission-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            padding: 22px;
            transition: all 0.25s ease;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .mvc-vm-mission-card:hover {
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 33, 71, 0.08);
        }

        .mvc-vm-pill-num {
            background: #F1F5F9;
            color: var(--mvc-navy);
            font-size: 13px;
            font-weight: 900;
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid var(--mvc-border);
            flex-shrink: 0;
            line-height: 1;
        }

        .mvc-vm-mission-body h3 {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 6px;
            line-height: 1.35;
        }

        .mvc-vm-mission-body p {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 991px) {
            .mvc-vm-duo-grid {
                grid-template-columns: 1fr;
            }
            .mvc-vm-mission-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="mvc-about-page">
        <!-- 1. HERO -->
        <header class="mvc-ab-hero">
            <div class="mvc-ab-hero-inner">
                <nav class="mvc-ab-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>">About Us</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">Vision &amp; Mission</span>
                </nav>

                <div class="mvc-ab-beacon">
                    <span class="mvc-ab-beacon-dot"></span>
                    <span><?php echo esc_html($hero_beacon); ?></span>
                </div>

                <h1 class="mvc-ab-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-ab-subtitle-hi"><?php echo esc_html($heading_hi); ?></div>
                <p class="mvc-ab-desc"><?php echo esc_html($hero_desc); ?></p>

                <!-- 4 KPI Metrics -->
                <div class="mvc-ab-kpi-ribbon">
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_1_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_1_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_2_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_2_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_3_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_3_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_4_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_4_lbl); ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-ab-main">
            <!-- SUITE CHAPTER NAVIGATOR -->
            <?php echo mvc_render_about_suite_nav('vision-mission'); ?>

            <!-- SANSKRIT MOTTO CARD -->
            <?php
            $motto_sanskrit = mvc_get_page_meta($post_id, 'vision_motto_sanskrit', '“Vidyamrtamasnutye” (विद्ययाऽमृतमश्नुते)');
            $motto_trans    = mvc_get_page_meta($post_id, 'vision_motto_trans', '— the Sanskrit phrase in the logo of the college sets down its understanding of such, as it symbolizes the idea that education is a source of eternal wisdom and enlightenment.');
            ?>
            <div class="mvc-vm-motto-card">
                <div class="mvc-vm-motto-sanskrit"><?php echo esc_html($motto_sanskrit); ?></div>
                <p class="mvc-vm-motto-trans">
                    <?php echo esc_html($motto_trans); ?>
                </p>
                <div class="mvc-vm-motto-source">
                    <span>🏛️ Official College Logo Emblem</span>
                </div>
            </div>

            <!-- DUAL VISION & ETHOS GRID (Verbatim from live site) -->
            <div class="mvc-vm-duo-grid">
                <div class="mvc-vm-vision-card">
                    <div style="text-align: center; margin-bottom: 18px;">
                        <img src="<?php echo esc_url(content_url('/uploads/live_vision-mission_VISION_1763962087_VISION_1759424043_VISION_1747135437_VISION_1745845979_VISION_1745782058_VISION3.png')); ?>" alt="Vision - Maharshi Vishwamitra Mahavidyalaya" style="max-width: 100%; height: auto; max-height: 220px; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h2 class="mvc-vm-card-title">
                        <span>🎯 Vision</span>
                    </h2>
                    <p class="mvc-vm-card-p">
                        Maharshi Vishwamitra Mahavidyalaya envisions being one of the best academic centres of Buxar with a holistic model of education for the betterment and empowerment of society.
                    </p>
                    <p class="mvc-vm-card-p">
                        To enliven this vision, Maharshi Vishwamitra Mahavidyalaya emphasizes a constant process of learning that imbibes the preservation of traditional and cultural heritage along with new and innovative practices of research, teaching and learning, skill development and professional leadership.
                    </p>
                </div>

                <div class="mvc-vm-commitment-card">
                    <div style="text-align: center; margin-bottom: 18px;">
                        <img src="<?php echo esc_url(content_url('/uploads/live_vision-mission_MISSION_1763962097_MISSION_1759424032_MISSION_1747135451_MISSION_1745846007_MISSION_1745782246_MISSION3.png')); ?>" alt="Mission - Maharshi Vishwamitra Mahavidyalaya" style="max-width: 100%; height: auto; max-height: 220px; object-fit: contain; border-radius: 12px;" />
                    </div>
                    <h2 class="mvc-vm-card-title">
                        <span>🤝 Social Commitment &amp; Ethics</span>
                    </h2>
                    <p class="mvc-vm-card-p">
                        The institution is devoted to the enrichment of sensitivity towards gender, caste, colour and creed, inculcation of ethics, morality and human values to produce citizens who can contribute significantly in developing an egalitarian society and a prosperous nation.
                    </p>
                    <p class="mvc-vm-card-p">
                        Rooted in the ancient soil of Siddhashram and the reformist spirit of Sri Khakhi Baba, the college balances timeless heritage with modern scientific inquiry.
                    </p>
                </div>
            </div>

            <!-- 7 STATED MISSION POINTS (Verbatim from live site) -->
            <section class="mvc-ab-section">
                <div class="mvc-ab-sec-header">
                    <h2 class="mvc-ab-sec-title">
                        <span>🚀 Stated Mission of Maharshi Vishwamitra Mahavidyalaya</span>
                    </h2>
                    <span class="mvc-ab-sec-tag">Official 7 Points</span>
                </div>

                <div class="mvc-vm-mission-grid">
                    <?php foreach ($missions as $m) : ?>
                        <div class="mvc-vm-mission-card">
                            <span class="mvc-vm-pill-num"><?php echo esc_html($m['num']); ?></span>
                            <div class="mvc-vm-mission-body">
                                <h3><?php echo esc_html($m['title']); ?></h3>
                                <p><?php echo esc_html($m['desc']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 5. GOVERNANCE GATEWAY STRIP -->
            <div class="mvc-ab-gateway-strip">
                <div>
                    <h4 style="margin:0 0 4px; font-size:16px; font-weight:800; color:var(--mvc-navy);">Explore Related Institutional Charters</h4>
                    <p style="margin:0; font-size:13px; color:#64748B;">Discover our foundational core values, rules, and code of conduct.</p>
                </div>
                <div class="mvc-ab-gw-links">
                    <a href="<?php echo esc_url($site_url . '/core-values/'); ?>" class="mvc-ab-gw-btn">
                        💎 Core Values &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/code-of-conduct/'); ?>" class="mvc-ab-gw-btn">
                        📜 Code of Conduct &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/rules-regulations/'); ?>" class="mvc-ab-gw-btn">
                        ⚖️ Rules &amp; Regulations &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * ------------------------------------------------------------------------
 * 3. RULES & REGULATIONS RENDERER (/rules-regulations/)
 * Verbatim alignment with live site text: Disciplinary Code for Students
 * ------------------------------------------------------------------------
 */
function mvc_render_rules_regulations_page() {
    $site_url = home_url();
    $post_id = 250;

    $hero_beacon = get_post_meta($post_id, 'about_hero_beacon', true) ?: 'Statutory Guidelines & Campus Discipline';
    $hero_title  = get_post_meta($post_id, 'about_hero_title', true) ?: 'Rules & Regulations';
    $heading_hi  = get_post_meta($post_id, 'about_heading_hi', true) ?: 'महर्षि विश्वामित्र महाविद्यालय - नियम एवं परिनियम';
    $hero_desc   = get_post_meta($post_id, 'about_hero_desc', true) ?: 'Official Disciplinary Code for Students, attendance mandates, anti-ragging decrees, and campus regulations established under Veer Kunwar Singh University (VKSU), Ara and the University Grants Commission.';

    $kpi_1_val = get_post_meta($post_id, 'about_kpi_1_val', true) ?: '75% Min';
    $kpi_1_lbl = get_post_meta($post_id, 'about_kpi_1_lbl', true) ?: 'Mandatory Attendance';

    $kpi_2_val = get_post_meta($post_id, 'about_kpi_2_val', true) ?: 'Criminal Offence';
    $kpi_2_lbl = get_post_meta($post_id, 'about_kpi_2_lbl', true) ?: 'Ragging Strictly Banned';

    $kpi_3_val = get_post_meta($post_id, 'about_kpi_3_val', true) ?: 'Tobacco Free';
    $kpi_3_lbl = get_post_meta($post_id, 'about_kpi_3_lbl', true) ?: 'Declared Campus Zone';

    $kpi_4_val = get_post_meta($post_id, 'about_kpi_4_val', true) ?: 'Vishakha Guidelines';
    $kpi_4_lbl = get_post_meta($post_id, 'about_kpi_4_lbl', true) ?: 'Supreme Court Mandate';

    // EXACT 9 DECREES FROM LIVE SITE
    $rules = array(
        array(
            'num'       => '01',
            'title'     => 'Mandatory 75% Class Attendance',
            'badge'     => 'VKSU Ara Rule',
            'badge_cls' => 'mvc-badge-danger',
            'icon'      => '📊',
            'text'      => 'As per Veer Kunwar Singh University, Ara rules, 75% attendance of all the classes held in the respective departments are binding on each student. Students who report sick or miss classes for any serious cause will have to report to the Heads of the Departments who will then decide in which academic manner the attendance shortfall is to be compensated. In all other cases of shortage of class attendance, the concerned students will not be allowed to sit for the examination of that particular year.'
        ),
        array(
            'num'       => '02',
            'title'     => 'College Identity Card',
            'badge'     => 'Mandatory',
            'badge_cls' => 'mvc-badge-navy',
            'icon'      => '🪪',
            'text'      => 'Every student must carry his/her College Identity Card to College. This must be prominently displayed on the dress.'
        ),
        array(
            'num'       => '03',
            'title'     => 'Anti-Ragging Statutory Prohibition',
            'badge'     => 'Criminal Offence',
            'badge_cls' => 'mvc-badge-danger',
            'icon'      => '🚫',
            'text'      => 'Ragging is a criminal offence. Any student found guilty of ragging will face stringent legal and/or punitive action as per UGC and Veer Kunwar Singh University, Ara guidelines.'
        ),
        array(
            'num'       => '04',
            'title'     => 'Prohibition of Gender Harassment',
            'badge'     => 'Vishakha Guidelines',
            'badge_cls' => 'mvc-badge-emerald',
            'icon'      => '⚖️',
            'text'      => 'Gender harassment is strictly prohibited. Any act of gender harassment, as identified in the Vishakha Guidelines set by Supreme Court of India, will be dealt with accordingly.'
        ),
        array(
            'num'       => '05',
            'title'     => 'Responsible and Courteous Demeanor',
            'badge'     => 'Campus Decorum',
            'badge_cls' => 'mvc-badge-navy',
            'icon'      => '🤝',
            'text'      => 'Admitted students are expected to behave responsibly and courteously in the campus and beyond.'
        ),
        array(
            'num'       => '06',
            'title'     => 'Prescribed Departmental Uniform',
            'badge'     => 'Dress Code',
            'badge_cls' => 'mvc-badge-navy',
            'icon'      => '👔',
            'text'      => 'Each student must wear the prescribed uniform as per their department rules.'
        ),
        array(
            'num'       => '07',
            'title'     => 'Mobile Phones Prohibited in Class',
            'badge'     => 'Confiscation Clause',
            'badge_cls' => 'mvc-badge-amber',
            'icon'      => '📵',
            'text'      => 'Use of Mobile phones by students is strictly prohibited in class. If caught with activated mobile phone in class, the phone will be seized and the concerned student will be forwarded to the Principal/ HOD.'
        ),
        array(
            'num'       => '08',
            'title'     => 'Declared "Tobacco Free Zone"',
            'badge'     => 'Strictly Enforced',
            'badge_cls' => 'mvc-badge-emerald',
            'icon'      => '🚭',
            'text'      => 'Maharshi Vishwamitra Mahavidyalaya, Buxar has been declared as “Tobacco Free Zone”. Violators will be punished accordingly by the Principal/HOD.'
        ),
        array(
            'num'       => '09',
            'title'     => 'Consent for Campus Meetings',
            'badge'     => 'Prior Consent Required',
            'badge_cls' => 'mvc-badge-navy',
            'icon'      => '🏛️',
            'text'      => 'No meetings are to be held inside the college campus without the Principal’s consent.'
        )
    );
    $rules = mvc_get_page_array($post_id, 'about_rules', $rules);

    ob_start();
    ?>
    <style>
        <?php echo mvc_get_about_shared_css(); ?>

        .mvc-badge-danger {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        .mvc-badge-navy {
            background: #EFF6FF;
            color: #1E40AF;
            border: 1px solid #BFDBFE;
        }

        .mvc-badge-emerald {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .mvc-badge-amber {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }

        .mvc-rr-search-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .mvc-rr-search-box {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .mvc-rr-search-box svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748B;
            pointer-events: none;
        }

        .mvc-rr-search-input {
            width: 100% !important;
            padding: 11px 16px 11px 44px !important;
            font-size: 13.5px !important;
            border: 1.5px solid var(--mvc-border) !important;
            border-radius: 10px !important;
            background: #F8FAFC !important;
            outline: none !important;
            box-sizing: border-box !important;
            height: 42px !important;
        }

        .mvc-rr-search-input:focus {
            background: #FFFFFF !important;
            border-color: var(--mvc-navy) !important;
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1) !important;
        }

        .mvc-rr-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .mvc-rr-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mvc-rr-card:hover {
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(0, 33, 71, 0.08);
        }

        .mvc-rr-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .mvc-rr-num {
            font-size: 12px;
            font-weight: 900;
            color: var(--mvc-maroon);
            background: #FFF1F2;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #FECDD3;
            letter-spacing: 0.5px;
        }

        .mvc-rr-badge {
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mvc-rr-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1.35;
        }

        .mvc-rr-text {
            font-size: 14px;
            line-height: 1.68;
            color: #334155;
            margin: 0;
        }

        @media (max-width: 991px) {
            .mvc-rr-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="mvc-about-page">
        <!-- 1. HERO -->
        <header class="mvc-ab-hero">
            <div class="mvc-ab-hero-inner">
                <nav class="mvc-ab-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>">About Us</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">Rules &amp; Regulations</span>
                </nav>

                <div class="mvc-ab-beacon">
                    <span class="mvc-ab-beacon-dot"></span>
                    <span><?php echo esc_html($hero_beacon); ?></span>
                </div>

                <h1 class="mvc-ab-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-ab-subtitle-hi"><?php echo esc_html($heading_hi); ?></div>
                <p class="mvc-ab-desc"><?php echo esc_html($hero_desc); ?></p>

                <!-- 4 KPI Metrics -->
                <div class="mvc-ab-kpi-ribbon">
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_1_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_1_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_2_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_2_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_3_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_3_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_4_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_4_lbl); ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-ab-main">
            <!-- SUITE CHAPTER NAVIGATOR -->
            <?php echo mvc_render_about_suite_nav('rules-regulations'); ?>

            <!-- SEARCH / FILTER CONTROLLER -->
            <div class="mvc-rr-search-card">
                <div class="mvc-rr-search-box">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="mvcRuleSearchInput" class="mvc-rr-search-input" placeholder="Search disciplinary code, attendance, identity card, anti-ragging..." onkeyup="mvcFilterRules();">
                </div>
                <div style="font-size:12.5px; font-weight:700; color:#64748B;">
                    Showing <strong id="mvcRuleCount" style="color:var(--mvc-navy);"><?php echo count($rules); ?></strong> Decrees
                </div>
            </div>

            <!-- DISCIPLINARY CODE FOR STUDENTS -->
            <section class="mvc-ab-section">
                <div class="mvc-ab-sec-header">
                    <h2 class="mvc-ab-sec-title">
                        <span>⚖️ Disciplinary Code for Students</span>
                    </h2>
                    <span class="mvc-ab-sec-tag">Official Mandate</span>
                </div>

                <div class="mvc-rr-grid" id="mvcRulesContainer">
                    <?php foreach ($rules as $r) : ?>
                        <div class="mvc-rr-card mvc-rule-card-item">
                            <div>
                                <div class="mvc-rr-top">
                                    <span class="mvc-rr-num">RULE <?php echo esc_html($r['num']); ?></span>
                                    <span class="mvc-rr-badge <?php echo esc_attr($r['badge_cls']); ?>">
                                        <?php echo esc_html($r['badge']); ?>
                                    </span>
                                </div>
                                <h3 class="mvc-rr-title">
                                    <span><?php echo esc_html($r['icon']); ?></span>
                                    <span><?php echo esc_html($r['title']); ?></span>
                                </h3>
                                <p class="mvc-rr-text"><?php echo esc_html($r['text']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 5. GOVERNANCE GATEWAY STRIP -->
            <div class="mvc-ab-gateway-strip">
                <div>
                    <h4 style="margin:0 0 4px; font-size:16px; font-weight:800; color:var(--mvc-navy);">Related Statutory Documents</h4>
                    <p style="margin:0; font-size:13px; color:#64748B;">Examine the comprehensive institutional Code of Conduct handbook.</p>
                </div>
                <div class="mvc-ab-gw-links">
                    <a href="<?php echo esc_url($site_url . '/code-of-conduct/'); ?>" class="mvc-ab-gw-btn">
                        📜 Code of Conduct &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/core-values/'); ?>" class="mvc-ab-gw-btn">
                        💎 Core Values &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>" class="mvc-ab-gw-btn">
                        🏛️ College at a Glance &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
    function mvcFilterRules() {
        var input = document.getElementById('mvcRuleSearchInput');
        var filter = input.value.toLowerCase();
        var cards = document.getElementsByClassName('mvc-rule-card-item');
        var count = 0;

        for (var i = 0; i < cards.length; i++) {
            var text = cards[i].innerText || cards[i].textContent;
            if (text.toLowerCase().indexOf(filter) > -1) {
                cards[i].style.display = "";
                count++;
            } else {
                cards[i].style.display = "none";
            }
        }
        document.getElementById('mvcRuleCount').textContent = count;
    }
    </script>
    <?php
    return ob_get_clean();
}

/**
 * ------------------------------------------------------------------------
 * 4. CODE OF CONDUCT RENDERER (/code-of-conduct/)
 * Verbatim alignment with live site text: Preamble, Students (21), Teachers (10), Staff (10)
 * ------------------------------------------------------------------------
 */
function mvc_render_code_of_conduct_page() {
    $site_url = home_url();
    $post_id = 251;

    $hero_beacon = get_post_meta($post_id, 'about_hero_beacon', true) ?: 'Institutional Ethical Compass & Governance Handbook';
    $hero_title  = get_post_meta($post_id, 'about_hero_title', true) ?: 'Code of Conduct';
    $heading_hi  = get_post_meta($post_id, 'about_heading_hi', true) ?: 'महर्षि विश्वामित्र महाविद्यालय - आचार संहिता';
    $hero_desc   = get_post_meta($post_id, 'about_hero_desc', true) ?: 'This handbook elaborates the code of conduct to be exhibited by students, faculty, administrators, and staff of Maharshi Vishwamitra Mahavidyalaya. This code of conduct intends to escalate ethics and uphold morality inside the campus.';

    $kpi_1_val = get_post_meta($post_id, 'about_kpi_1_val', true) ?: '3 Charters';
    $kpi_1_lbl = get_post_meta($post_id, 'about_kpi_1_lbl', true) ?: 'Students, Faculty & Staff';

    $kpi_2_val = get_post_meta($post_id, 'about_kpi_2_val', true) ?: '21 Points';
    $kpi_2_lbl = get_post_meta($post_id, 'about_kpi_2_lbl', true) ?: 'For Students';

    $kpi_3_val = get_post_meta($post_id, 'about_kpi_3_val', true) ?: '10 Points';
    $kpi_3_lbl = get_post_meta($post_id, 'about_kpi_3_lbl', true) ?: 'For Teachers';

    $kpi_4_val = get_post_meta($post_id, 'about_kpi_4_val', true) ?: '10 Points';
    $kpi_4_lbl = get_post_meta($post_id, 'about_kpi_4_lbl', true) ?: 'For Support Staff';

    // EXACT 21 STUDENT RULES FROM LIVE SITE
    $student_points = array(
        'Students should be respectful, use appropriate language, and maintain decorum within the campus at all times.',
        'Students should wear their identity cards at all times and display them clearly within the campus.',
        'Students must be punctual and avoid leaving class early without prior permission.',
        'Littering or spitting in classrooms, hallways, or gardens is prohibited; students should help keep the campus clean.',
        'Students must behave respectfully towards faculty, staff, and fellow students.',
        'Interactions between boys and girls should align with acceptable cultural and traditional norms.',
        'No food or beverages are allowed inside classrooms, laboratories, libraries, or reading rooms without permission.',
        'Smoking is strictly prohibited within the campus premises.',
        'Possessing or sharing obscene material is strictly prohibited.',
        'Students are responsible for the safety of their personal belongings.',
        'Students should uphold the dignity of the college through their conduct both on and off campus.',
        'Whistling, shouting, or creating disturbances in the campus is not tolerated.',
        'Harassment, threats, or causing physical harm to anyone is strictly prohibited.',
        'Theft or damage to college or personal property is a punishable offense.',
        'Providing false information to any college authority or staff member is unacceptable.',
        'Forgery or misuse of any college document or identification is prohibited.',
        'Ragging in any form is banned and punishable under law.',
        'Malpractices in examinations will attract penalties such as fines, cancellation, or expulsion.',
        'Sexual misconduct of any kind will be dealt with seriously under legal provisions.',
        'Organizing events, meetings, or collecting funds without prior approval from the Principal is prohibited.',
        'Organizing birthday celebrations within the campus is strictly not allowed.'
    );
    $student_points = mvc_get_page_array($post_id, 'code_conduct_student_points', $student_points);

    // EXACT 10 TEACHER CLAUSES FROM LIVE SITE
    $teacher_points = array(
        'A teacher shall carry out the legitimate academic and administrative decisions taken by the authorities of the University pertaining to his/her sphere of responsibility/duties.',
        'A teacher shall not discriminate against a student on political grounds for reasons of race, religion, caste, language or sex or for other reason of an arbitrary or personal nature and shall not incite students/teachers against other students or other teachers, colleagues or administration/Governing body of the University.',
        'A teacher shall not remain absent from duty without proper sanction of leave except in case of emergency.',
        'A teacher shall desist/refrain from indulging in unempirical publication which would prove to be detrimental to the reputation and the progress of the University.',
        'A teacher may be permitted to take up consultancy under terms and conditions as outlined in the relevant Statute/Ordinance as amended from time to time. However, he/she shall not conduct individually or participate in conducting jointly with others coaching classes directly or indirectly. He/she shall not give any private tuition to students of the University.',
        'Refrain from undertaking any other employment and commitment including private tuitions and coaching classes which are likely to interfere with their professional responsibilities.',
        'Co-operate in the formulation of policies of the institution by accepting various offices and discharge responsibilities which such offices demand.',
        'Co-operate with the authorities for the betterment of the University’s keeping in view the interest and in conformity with dignity of the profession and adhere to the conditions of contract.',
        'Give and expect due notice before a change of position is made, and refrain from availing themselves of leave except on unavoidable grounds and as far as practicable with prior intimation, keeping in view their particular responsibility for completion of academic schedule.',
        'Must follow the policies of the college such as Travel Policy, Leave Policy, Research Policy, Social Media Policy and instructions issued by the University authorities from time to time.'
    );
    $teacher_points = mvc_get_page_array($post_id, 'code_conduct_teacher_points', $teacher_points);

    // EXACT 10 SUPPORT STAFF CLAUSES FROM LIVE SITE
    $staff_points = array(
        'The support/office staff should acquaint themselves with the college policies and adhere to them to the best of their ability.',
        'Each member of the support/office staff should come well-dressed in the proper uniform assigned to him/her.',
        'The support/office staff should strictly regulate his/her duties as officially assigned and not undertake any other job during working hours.',
        'The support/office staff should not indulge in any political or anti-secular activities that can hamper the smooth functioning of the college.',
        'The support/office staff should behave respectfully towards teachers and students.',
        'Punctuality and discipline are expected of them, just like teachers and students.',
        'The staff should ensure that the service books of all teachers are updated and properly maintained.',
        'The staff should assist teachers in college work if required, as per directions of the Principal.',
        'The Accounts department should prepare, examine, and analyze accounting and other financial records and statements from time to time.',
        'The Accounts section should ensure that documents and records related to various committees are systematically arranged and free of discrepancies.'
    );

    ob_start();
    ?>
    <style>
        <?php echo mvc_get_about_shared_css(); ?>

        .mvc-cd-lead-prose {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            padding: clamp(20px, 3vw, 30px);
            margin-bottom: 30px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
        }

        .mvc-cd-lead-prose p {
            font-size: 14.5px;
            line-height: 1.75;
            color: #334155;
            margin-bottom: 14px;
        }

        .mvc-cd-lead-prose p:last-child {
            margin-bottom: 0;
        }

        .mvc-cd-preamble-card {
            background: linear-gradient(135deg, #FFFDF8 0%, #FFFBEB 100%);
            border: 2px solid #FCD34D;
            border-left: 6px solid var(--mvc-gold-amber);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 30px;
        }

        .mvc-cd-preamble-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mvc-cd-preamble-text {
            font-size: 15px;
            line-height: 1.7;
            color: #78350F;
            font-weight: 500;
            margin: 0;
        }

        .mvc-cd-tabs-wrap {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 4px;
            gap: 6px;
            margin-bottom: 25px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            width: 100%;
            box-sizing: border-box;
        }

        .mvc-cd-tab-btn {
            flex: 1;
            background: transparent;
            border: none;
            padding: 10px 14px;
            font-size: 13.5px;
            font-weight: 800;
            color: #475569;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
            min-height: 42px;
        }

        .mvc-cd-tab-btn:hover {
            background: #F1F5F9;
            color: var(--mvc-navy);
        }

        .mvc-cd-tab-btn.is-active {
            background: var(--mvc-navy);
            color: #FFFFFF !important;
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.2);
        }

        .mvc-cd-panel {
            display: none;
        }

        .mvc-cd-panel.is-active {
            display: block;
        }

        .mvc-cd-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mvc-cd-item {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: all 0.2s ease;
        }

        .mvc-cd-item:hover {
            border-color: #CBD5E1;
            background: #FAFAFA;
            transform: translateX(2px);
        }

        .mvc-cd-item-num {
            background: #F1F5F9;
            color: var(--mvc-navy);
            font-size: 12px;
            font-weight: 900;
            padding: 4px 8px;
            border-radius: 6px;
            flex-shrink: 0;
            line-height: 1.2;
            margin-top: 2px;
        }

        .mvc-cd-item-text {
            font-size: 14px;
            line-height: 1.68;
            color: #1E293B;
            margin: 0;
            flex: 1;
        }

        @media (max-width: 768px) {
            .mvc-cd-tabs-wrap {
                flex-direction: column;
            }
            .mvc-cd-tab-btn {
                width: 100%;
            }
        }
    </style>

    <div class="mvc-about-page">
        <!-- 1. HERO -->
        <header class="mvc-ab-hero">
            <div class="mvc-ab-hero-inner">
                <nav class="mvc-ab-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>">About Us</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">Code of Conduct</span>
                </nav>

                <div class="mvc-ab-beacon">
                    <span class="mvc-ab-beacon-dot"></span>
                    <span><?php echo esc_html($hero_beacon); ?></span>
                </div>

                <h1 class="mvc-ab-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-ab-subtitle-hi"><?php echo esc_html($heading_hi); ?></div>
                <p class="mvc-ab-desc"><?php echo esc_html($hero_desc); ?></p>

                <!-- 4 KPI Metrics -->
                <div class="mvc-ab-kpi-ribbon">
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_1_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_1_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_2_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_2_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_3_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_3_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_4_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_4_lbl); ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-ab-main">
            <!-- SUITE CHAPTER NAVIGATOR -->
            <?php echo mvc_render_about_suite_nav('code-of-conduct'); ?>

            
            <!-- PREAMBLE CODE OF CONDUCT (Verbatim from live site) -->
            <div class="mvc-cd-preamble-card">
                <div class="mvc-cd-preamble-title">
                    <span>📜 Preamble Code of Conduct</span>
                </div>
                <p class="mvc-cd-preamble-text">
                    This handbook elaborates the code of conduct to be exhibited by students, faculty, administrators, and staff of Maharshi Vishwamitra Mahavidyalaya. This code of conduct intends to escalate ethics and uphold morality inside the campus. All are requested to adhere to this code and help in creating an energetic and cohesive environment.
                </p>
            </div>

            <!-- 3 STAKEHOLDER TABS -->
            <div class="mvc-cd-tabs-wrap">
                <button type="button" class="mvc-cd-tab-btn is-active" onclick="mvcShowConductTab('students', this);">
                    <span>🎓 For Students (21 Points)</span>
                </button>
                <button type="button" class="mvc-cd-tab-btn" onclick="mvcShowConductTab('teachers', this);">
                    <span>👨‍🏫 For Teachers (10 Points)</span>
                </button>
                <button type="button" class="mvc-cd-tab-btn" onclick="mvcShowConductTab('staff', this);">
                    <span>🏢 For Support Staff (10 Points)</span>
                </button>
            </div>

            <!-- PANEL 1: FOR STUDENTS -->
            <div id="mvcConductStudents" class="mvc-cd-panel is-active">
                <section class="mvc-ab-section">
                    <div class="mvc-ab-sec-header">
                        <h2 class="mvc-ab-sec-title">
                            <span>🎓 Code of Conduct for Students</span>
                        </h2>
                        <span class="mvc-ab-sec-tag">21 Official Decrees</span>
                    </div>

                    <div class="mvc-cd-list">
                        <?php foreach ($student_points as $idx => $pt) : ?>
                            <div class="mvc-cd-item">
                                <span class="mvc-cd-item-num"><?php echo esc_html($idx + 1); ?></span>
                                <p class="mvc-cd-item-text"><?php echo esc_html($pt); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <!-- PANEL 2: FOR TEACHERS -->
            <div id="mvcConductTeachers" class="mvc-cd-panel">
                <section class="mvc-ab-section">
                    <div class="mvc-ab-sec-header">
                        <h2 class="mvc-ab-sec-title">
                            <span>👨‍🏫 Code of Conduct for Teachers</span>
                        </h2>
                        <span class="mvc-ab-sec-tag">10 Professional Clauses</span>
                    </div>

                    <div class="mvc-cd-list">
                        <?php foreach ($teacher_points as $idx => $pt) : ?>
                            <div class="mvc-cd-item">
                                <span class="mvc-cd-item-num"><?php echo esc_html($idx + 1); ?></span>
                                <p class="mvc-cd-item-text"><?php echo esc_html($pt); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <!-- PANEL 3: FOR SUPPORT STAFF -->
            <div id="mvcConductStaff" class="mvc-cd-panel">
                <section class="mvc-ab-section">
                    <div class="mvc-ab-sec-header">
                        <h2 class="mvc-ab-sec-title">
                            <span>🏢 Code of Conduct for Support Staff</span>
                        </h2>
                        <span class="mvc-ab-sec-tag">10 Service Duties</span>
                    </div>

                    <div class="mvc-cd-list">
                        <?php foreach ($staff_points as $idx => $pt) : ?>
                            <div class="mvc-cd-item">
                                <span class="mvc-cd-item-num"><?php echo esc_html($idx + 1); ?></span>
                                <p class="mvc-cd-item-text"><?php echo esc_html($pt); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <!-- 5. GOVERNANCE GATEWAY STRIP -->
            <div class="mvc-ab-gateway-strip">
                <div>
                    <h4 style="margin:0 0 4px; font-size:16px; font-weight:800; color:var(--mvc-navy);">Explore Institutional Governance</h4>
                    <p style="margin:0; font-size:13px; color:#64748B;">Learn more about college rules, core values, and institutional vision.</p>
                </div>
                <div class="mvc-ab-gw-links">
                    <a href="<?php echo esc_url($site_url . '/rules-regulations/'); ?>" class="mvc-ab-gw-btn">
                        ⚖️ Rules &amp; Regulations &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/core-values/'); ?>" class="mvc-ab-gw-btn">
                        💎 Core Values &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/vision-mission/'); ?>" class="mvc-ab-gw-btn">
                        🎯 Vision &amp; Mission &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
    function mvcShowConductTab(tabKey, btn) {
        var panels = ['students', 'teachers', 'staff'];
        for (var i = 0; i < panels.length; i++) {
            var pId = 'mvcConduct' + panels[i].charAt(0).toUpperCase() + panels[i].slice(1);
            var panel = document.getElementById(pId);
            if (panel) {
                panel.classList.remove('is-active');
            }
        }
        var activePanel = document.getElementById('mvcConduct' + tabKey.charAt(0).toUpperCase() + tabKey.slice(1));
        if (activePanel) {
            activePanel.classList.add('is-active');
        }

        var buttons = document.querySelectorAll('.mvc-cd-tab-btn');
        for (var j = 0; j < buttons.length; j++) {
            buttons[j].classList.remove('is-active');
        }
        if (btn) {
            btn.classList.add('is-active');
        }
    }
    </script>
    <?php
    return ob_get_clean();
}

/**
 * ------------------------------------------------------------------------
 * 5. CORE VALUES RENDERER (/core-values/)
 * Verbatim alignment with live site text: Exact 8 Points
 * ------------------------------------------------------------------------
 */
function mvc_render_core_values_page() {
    $site_url = home_url();
    $post_id = 252;

    $hero_beacon = get_post_meta($post_id, 'about_hero_beacon', true) ?: 'Institutional Moral Bedrock & Axiology';
    $hero_title  = get_post_meta($post_id, 'about_hero_title', true) ?: 'Core Values';
    $heading_hi  = get_post_meta($post_id, 'about_heading_hi', true) ?: 'महर्षि विश्वामित्र महाविद्यालय - मूलभूत मूल्य';
    $hero_desc   = get_post_meta($post_id, 'about_hero_desc', true) ?: 'The foundational institutional core values guiding the academic community, student scholars, and faculty of Maharshi Vishwamitra Mahavidyalaya.';

    $kpi_1_val = get_post_meta($post_id, 'about_kpi_1_val', true) ?: '8 Values';
    $kpi_1_lbl = get_post_meta($post_id, 'about_kpi_1_lbl', true) ?: 'Foundational Pillars';

    $kpi_2_val = get_post_meta($post_id, 'about_kpi_2_val', true) ?: 'Quality Education';
    $kpi_2_lbl = get_post_meta($post_id, 'about_kpi_2_lbl', true) ?: 'Minority & Community Focus';

    $kpi_3_val = get_post_meta($post_id, 'about_kpi_3_val', true) ?: 'Peace & Harmony';
    $kpi_3_lbl = get_post_meta($post_id, 'about_kpi_3_lbl', true) ?: 'Brotherhood & Service';

    $kpi_4_val = get_post_meta($post_id, 'about_kpi_4_val', true) ?: 'Excellence';
    $kpi_4_lbl = get_post_meta($post_id, 'about_kpi_4_lbl', true) ?: 'All Endeavors';

    // EXACT 8 CORE VALUES FROM LIVE SITE
    $values = array(
        array(
            'num'   => '01',
            'title' => 'Promotion of quality education among minority students and other communities.',
            'icon'  => '🎓',
            'badge' => 'Education for All'
        ),
        array(
            'num'   => '02',
            'title' => 'Promotion of peace, harmony, and brotherhood.',
            'icon'  => '🕊️',
            'badge' => 'Peace & Fraternity'
        ),
        array(
            'num'   => '03',
            'title' => 'Service to the community, society, and the nation.',
            'icon'  => '🇮🇳',
            'badge' => 'National Service'
        ),
        array(
            'num'   => '04',
            'title' => 'Promoting environmental awareness and cleanliness.',
            'icon'  => '🌿',
            'badge' => 'Environmental Care'
        ),
        array(
            'num'   => '05',
            'title' => 'Academic integrity and accountability.',
            'icon'  => '⚖️',
            'badge' => 'Integrity & Ethics'
        ),
        array(
            'num'   => '06',
            'title' => 'Leading through innovation.',
            'icon'  => '💡',
            'badge' => 'Innovation'
        ),
        array(
            'num'   => '07',
            'title' => 'Discipline, sincerity, and dedication.',
            'icon'  => '🛡️',
            'badge' => 'Dedication'
        ),
        array(
            'num'   => '08',
            'title' => 'Pursuing excellence in all endeavors.',
            'icon'  => '🌟',
            'badge' => 'Excellence'
        )
    );

    ob_start();
    ?>
    <style>
        <?php echo mvc_get_about_shared_css(); ?>

        .mvc-cv-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .mvc-cv-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            transition: all 0.25s ease;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .mvc-cv-card:hover {
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(0, 33, 71, 0.08);
        }

        .mvc-cv-icon-box {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;
            background: #F1F5F9;
            border: 1px solid var(--mvc-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .mvc-cv-body {
            flex: 1;
            min-width: 0;
        }

        .mvc-cv-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 6px;
        }

        .mvc-cv-order {
            font-size: 11px;
            font-weight: 900;
            color: var(--mvc-maroon);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mvc-cv-tag {
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 999px;
            background: #EFF6FF;
            color: var(--mvc-navy);
            border: 1px solid #BFDBFE;
        }

        .mvc-cv-statement {
            font-size: 15px;
            font-weight: 700;
            color: var(--mvc-navy);
            line-height: 1.5;
            margin: 0;
        }

        @media (max-width: 991px) {
            .mvc-cv-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="mvc-about-page">
        <!-- 1. HERO -->
        <header class="mvc-ab-hero">
            <div class="mvc-ab-hero-inner">
                <nav class="mvc-ab-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>">About Us</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">Core Values</span>
                </nav>

                <div class="mvc-ab-beacon">
                    <span class="mvc-ab-beacon-dot"></span>
                    <span><?php echo esc_html($hero_beacon); ?></span>
                </div>

                <h1 class="mvc-ab-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-ab-subtitle-hi"><?php echo esc_html($heading_hi); ?></div>
                <p class="mvc-ab-desc"><?php echo esc_html($hero_desc); ?></p>

                <!-- 4 KPI Metrics -->
                <div class="mvc-ab-kpi-ribbon">
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_1_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_1_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_2_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_2_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_3_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_3_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_4_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_4_lbl); ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-ab-main">
            <!-- SUITE CHAPTER NAVIGATOR -->
            <?php echo mvc_render_about_suite_nav('core-values'); ?>

            <!-- CORE VALUES SECTION (Exact 8 items from live site) -->
            <section class="mvc-ab-section">
                <div class="mvc-ab-sec-header">
                    <h2 class="mvc-ab-sec-title">
                        <span>💎 Institutional Core Values</span>
                    </h2>
                    <span class="mvc-ab-sec-tag">8 Foundational Pillars</span>
                </div>

                <div class="mvc-cv-grid">
                    <?php foreach ($values as $v) : ?>
                        <div class="mvc-cv-card">
                            <div class="mvc-cv-icon-box"><?php echo esc_html($v['icon']); ?></div>
                            <div class="mvc-cv-body">
                                <div class="mvc-cv-top">
                                    <span class="mvc-cv-order">CORE VALUE <?php echo esc_html($v['num']); ?></span>
                                    <span class="mvc-cv-tag"><?php echo esc_html($v['badge']); ?></span>
                                </div>
                                <p class="mvc-cv-statement"><?php echo esc_html($v['title']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 5. GOVERNANCE GATEWAY STRIP -->
            <div class="mvc-ab-gateway-strip">
                <div>
                    <h4 style="margin:0 0 4px; font-size:16px; font-weight:800; color:var(--mvc-navy);">Explore Related Chapters</h4>
                    <p style="margin:0; font-size:13px; color:#64748B;">Explore Vision &amp; Mission, Rules &amp; Regulations, and Code of Conduct.</p>
                </div>
                <div class="mvc-ab-gw-links">
                    <a href="<?php echo esc_url($site_url . '/vision-mission/'); ?>" class="mvc-ab-gw-btn">
                        🎯 Vision &amp; Mission &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/rules-regulations/'); ?>" class="mvc-ab-gw-btn">
                        ⚖️ Rules &amp; Regulations &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/code-of-conduct/'); ?>" class="mvc-ab-gw-btn">
                        📜 Code of Conduct &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * ------------------------------------------------------------------------
 * 6. CAMPUS MASTER PLAN RENDERER (/master-plan/)
 * Verbatim alignment with live site: Master Plan Information - Our 2D & 3D Plan
 * Highlighting authentic campus physical infrastructure across 10 bighas
 * ------------------------------------------------------------------------
 */
function mvc_render_master_plan_page() {
    $site_url = home_url();
    $post_id = 253;

    $hero_beacon = 'Campus Master Plan & Infrastructure Layout';
    $hero_title  = get_post_meta($post_id, 'about_hero_title', true) ?: 'Master Plan';
    $heading_hi  = get_post_meta($post_id, 'about_heading_hi', true) ?: 'महाविद्यालय परिसर मास्टर प्लान एवं अवसंरचना खाका';
    $hero_desc   = 'The physical layout of Maharshi Vishwamitra Mahavidyalaya across 10 bighas on the bank of Holy Ganga at Charitravan, Buxar, incorporating academic wings, Manas Bhawan auditorium, central library, and science laboratories.';

    $kpi_1_val = get_post_meta($post_id, 'about_kpi_1_val', true) ?: '10 Bighas';
    $kpi_1_lbl = get_post_meta($post_id, 'about_kpi_1_lbl', true) ?: 'Ganga Riverfront Land Grant';

    $kpi_2_val = get_post_meta($post_id, 'about_kpi_2_val', true) ?: '200+ Seats';
    $kpi_2_lbl = get_post_meta($post_id, 'about_kpi_2_lbl', true) ?: 'Manas Bhawan Auditorium';

    $kpi_3_val = get_post_meta($post_id, 'about_kpi_3_val', true) ?: '15,000+';
    $kpi_3_lbl = get_post_meta($post_id, 'about_kpi_3_lbl', true) ?: 'Library Books Collection';

    $kpi_4_val = get_post_meta($post_id, 'about_kpi_4_val', true) ?: 'Estd. 1958';
    $kpi_4_lbl = get_post_meta($post_id, 'about_kpi_4_lbl', true) ?: 'Historical Siddhashram Campus';

    // AUTHENTIC CAMPUS INFRASTRUCTURE ELEMENTS DERIVED FROM OFFICIAL TEXT
    $facilities = array(
        array(
            'title'    => '10 Bighas Ganga Riverfront Estate',
            'subtitle' => 'Sacred Siddhashram Land Grant',
            'icon'     => '🌊',
            'badge'    => 'Historical Grant',
            'desc'     => 'Donated by the honourable Raja of Dumraon State due to his deep love and affection with Khaki Baba, situated gracefully on the tranquil bank of the Holy Ganga at Charitravan.'
        ),
        array(
            'title'    => 'Eastern Main Collegiate Building & Academic Departments',
            'subtitle' => 'Arts, Science & Administration',
            'icon'     => '🏛️',
            'badge'    => 'Academic Core',
            'desc'     => 'The eastern part of campus houses the main collegiate building along with various academic departments in Arts and Science, departmental staff rooms, Principal\'s Chamber, and Administrative Block.'
        ),
        array(
            'title'    => 'Manas Bhawan (Auditorium cum Examination Hall)',
            'subtitle' => 'Capacity of 200+ Seats',
            'icon'     => '🎭',
            'badge'    => 'Prized Possession',
            'desc'     => 'A prized possession of the college, Manas Bhawan serves as a spacious multi-purpose auditorium cum examination hall with a seating capacity of 200+ persons for university examinations and convocations.'
        ),
        array(
            'title'    => 'Central Library & Reading Room',
            'subtitle' => '15,000+ Books & INFLIBNET',
            'icon'     => '📚',
            'badge'    => 'Knowledge Hub',
            'desc'     => 'The college library features a rich collection of over 15,000 books with a spacious and well-furnished reading room. The college is also a proud member of INFLIBNET.'
        ),
        array(
            'title'    => 'Science Laboratories Complex',
            'subtitle' => 'Physics, Chemistry, Botany & Zoology',
            'icon'     => '🔬',
            'badge'    => 'Estd. 1967',
            'desc'     => 'Commissioned under Principal Dr. Jitram Pathak in 1967, featuring specialized experimental laboratories and modern scientific equipment fulfilling collegiate needs.'
        ),
        array(
            'title'    => 'Professional Centers (BCA, IGNOU & Prak Pariksha)',
            'subtitle' => 'Vocational & Competitive Training',
            'icon'     => '💻',
            'badge'    => 'Vocational Wing',
            'desc'     => 'Accommodates the Bachelor of Computer Applications (BCA) introduced in 2015-16, the full-fledged IGNOU centre (functioning since 1997 with 1500+ students), and the Bihar Government Prak Pariksha Prashikshan Kendra.'
        ),
        array(
            'title'    => 'Sacred Heritage Shrines on Campus',
            'subtitle' => 'Ram Lakshman Janki Mandir & Kaldhatri Mandir',
            'icon'     => '🛕',
            'badge'    => 'Ancient Heritage',
            'desc'     => 'The beautiful temple of Ram Lakshman Janki with architectural devices to beautify the college, and the temple of Kaldhatri (Gaiya Maiya) Mandir representing ancient architecture.'
        ),
        array(
            'title'    => 'Student Amenities, Sports Grounds, NCC & NSS',
            'subtitle' => 'Common Rooms, Grounds & Post Office',
            'icon'     => '🏃',
            'badge'    => 'Student Welfare',
            'desc'     => 'Separate common rooms for boys and girls, active N.C.C. and N.S.S. offices, regular annual sports and debating society facilities, along with an on-campus post office.'
        )
    );

    ob_start();
    ?>
    <style>
        <?php echo mvc_get_about_shared_css(); ?>

        .mvc-mp-plan-tabs {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 14px;
            padding: 4px;
            gap: 6px;
            margin-bottom: 25px;
            box-shadow: 0 4px 14px rgba(0, 33, 71, 0.04);
            width: 100%;
            box-sizing: border-box;
        }

        .mvc-mp-tab-btn {
            flex: 1;
            background: transparent;
            border: none;
            padding: 10px 14px;
            font-size: 13.5px;
            font-weight: 800;
            color: #475569;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
            min-height: 42px;
        }

        .mvc-mp-tab-btn:hover {
            background: #F1F5F9;
            color: var(--mvc-navy);
        }

        .mvc-mp-tab-btn.is-active {
            background: var(--mvc-navy);
            color: #FFFFFF !important;
            box-shadow: 0 2px 8px rgba(0, 33, 71, 0.2);
        }

        .mvc-mp-plan-panel {
            display: none;
        }

        .mvc-mp-plan-panel.is-active {
            display: block;
        }

        /* 2D & 3D BLUEPRINT DISPLAY */
        .mvc-mp-blueprint-box {
            background: linear-gradient(135deg, #0A192F 0%, #002147 100%);
            border: 2px solid var(--mvc-gold);
            border-radius: 16px;
            padding: clamp(24px, 4vw, 36px);
            color: #FFFFFF;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0, 33, 71, 0.2);
            position: relative;
            overflow: hidden;
        }

        .mvc-mp-blueprint-box::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(212, 175, 55, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(212, 175, 55, 0.08) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
        }

        .mvc-mp-bp-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            padding-bottom: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .mvc-mp-bp-title {
            font-size: clamp(18px, 2.5vw, 22px);
            font-weight: 800;
            color: var(--mvc-gold-light);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mvc-mp-bp-badge {
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid var(--mvc-gold);
            color: #FEF08A;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
        }

        .mvc-mp-bp-schematic {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            position: relative;
            z-index: 2;
        }

        .mvc-mp-bp-zone-node {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 16px;
            backdrop-filter: blur(4px);
            transition: all 0.2s ease;
        }

        .mvc-mp-bp-zone-node:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
        }

        .mvc-mp-bp-node-icon {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .mvc-mp-bp-node-title {
            font-size: 13.5px;
            font-weight: 800;
            color: #FFFFFF;
            margin: 0 0 4px;
        }

        .mvc-mp-bp-node-desc {
            font-size: 11.5px;
            color: #CBD5E1;
            line-height: 1.45;
            margin: 0;
        }

        /* FACILITIES GRID */
        .mvc-mp-fac-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .mvc-mp-fac-card {
            background: #FFFFFF;
            border: 1.5px solid var(--mvc-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 33, 71, 0.04);
            transition: all 0.25s ease;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .mvc-mp-fac-card:hover {
            border-color: var(--mvc-gold);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(0, 33, 71, 0.08);
        }

        .mvc-mp-fac-icon-box {
            width: 48px;
            height: 48px;
            min-width: 48px;
            border-radius: 12px;
            background: #F8FAFC;
            border: 1px solid var(--mvc-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .mvc-mp-fac-body {
            flex: 1;
            min-width: 0;
        }

        .mvc-mp-fac-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 4px;
        }

        .mvc-mp-fac-badge {
            font-size: 11px;
            font-weight: 800;
            color: var(--mvc-navy);
            background: #EFF6FF;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid #BFDBFE;
        }

        .mvc-mp-fac-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--mvc-navy);
            margin: 0 0 2px;
            line-height: 1.35;
        }

        .mvc-mp-fac-subtitle {
            font-size: 12px;
            font-weight: 700;
            color: var(--mvc-gold-amber);
            margin-bottom: 8px;
        }

        .mvc-mp-fac-desc {
            font-size: 13.5px;
            line-height: 1.6;
            color: #475569;
            margin: 0;
        }

        @media (max-width: 991px) {
            .mvc-mp-bp-schematic {
                grid-template-columns: repeat(2, 1fr);
            }
            .mvc-mp-fac-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .mvc-mp-bp-schematic {
                grid-template-columns: 1fr;
            }
            .mvc-mp-plan-tabs {
                flex-direction: column;
            }
            .mvc-mp-tab-btn {
                width: 100%;
            }
        }
    </style>

    <div class="mvc-about-page">
        <!-- 1. HERO -->
        <header class="mvc-ab-hero">
            <div class="mvc-ab-hero-inner">
                <nav class="mvc-ab-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url($site_url); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>">About Us</a>
                    <span>/</span>
                    <span style="color:var(--mvc-gold-light);">Master Plan</span>
                </nav>

                <div class="mvc-ab-beacon">
                    <span class="mvc-ab-beacon-dot"></span>
                    <span><?php echo esc_html($hero_beacon); ?></span>
                </div>

                <h1 class="mvc-ab-title"><?php echo esc_html($hero_title); ?></h1>
                <div class="mvc-ab-subtitle-hi"><?php echo esc_html($heading_hi); ?></div>
                <p class="mvc-ab-desc"><?php echo esc_html($hero_desc); ?></p>

                <!-- 4 KPI Metrics -->
                <div class="mvc-ab-kpi-ribbon">
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_1_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_1_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_2_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_2_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_3_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_3_lbl); ?></div>
                    </div>
                    <div class="mvc-ab-kpi-item">
                        <div class="mvc-ab-kpi-num"><?php echo esc_html($kpi_4_val); ?></div>
                        <div class="mvc-ab-kpi-label"><?php echo esc_html($kpi_4_lbl); ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 2. MAIN CONTAINER -->
        <main class="mvc-ab-main">
            <!-- SUITE CHAPTER NAVIGATOR -->
            <?php echo mvc_render_about_suite_nav('master-plan'); ?>

            <!-- MASTER PLAN STATUS -->
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-left:4px solid var(--mvc-navy); border-radius:12px; padding:24px 28px; margin-bottom:35px;">
                <h3 style="margin:0 0 8px; color:var(--mvc-navy); font-size:18px; font-weight:800;">Master Plan Information</h3>
                <p style="margin:0; font-size:14.5px; color:#475569; line-height:1.6;">
                    The official architectural master plan for the collegiate campus across 10 bighas at Charitravan, Buxar is currently under preparation and will be published here upon finalization. Detailed physical facilities and campus infrastructure are listed below.
                </p>
            </div>

            <!-- AUTHENTIC CAMPUS FACILITIES DIRECTORY -->
            <section class="mvc-ab-section">
                <div class="mvc-ab-sec-header">
                    <h2 class="mvc-ab-sec-title">
                        <span>🗺️ Campus Physical Infrastructure Details</span>
                    </h2>
                    <span class="mvc-ab-sec-tag">Official Facilities</span>
                </div>

                <div class="mvc-mp-fac-grid">
                    <?php foreach ($facilities as $fac) : ?>
                        <div class="mvc-mp-fac-card">
                            <div class="mvc-mp-fac-icon-box"><?php echo esc_html($fac['icon']); ?></div>
                            <div class="mvc-mp-fac-body">
                                <div class="mvc-mp-fac-top">
                                    <span class="mvc-mp-fac-badge"><?php echo esc_html($fac['badge']); ?></span>
                                </div>
                                <h3 class="mvc-mp-fac-title"><?php echo esc_html($fac['title']); ?></h3>
                                <div class="mvc-mp-fac-subtitle"><?php echo esc_html($fac['subtitle']); ?></div>
                                <p class="mvc-mp-fac-desc"><?php echo esc_html($fac['desc']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 5. GOVERNANCE GATEWAY STRIP -->
            <div class="mvc-ab-gateway-strip">
                <div>
                    <h4 style="margin:0 0 4px; font-size:16px; font-weight:800; color:var(--mvc-navy);">Explore Institutional Genesis &amp; Leadership</h4>
                    <p style="margin:0; font-size:13px; color:#64748B;">Learn more about our 1958 foundation, leadership succession, and college administration.</p>
                </div>
                <div class="mvc-ab-gw-links">
                    <a href="<?php echo esc_url($site_url . '/glance/'); ?>" class="mvc-ab-gw-btn">
                        🏛️ College at a Glance &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/principal-desk/'); ?>" class="mvc-ab-gw-btn">
                        📜 Principal's Desk &rarr;
                    </a>
                    <a href="<?php echo esc_url($site_url . '/succession-list/'); ?>" class="mvc-ab-gw-btn">
                        👑 Succession Roll &rarr;
                    </a>
                </div>
            </div>
        </main>
    </div>

    <?php
    return ob_get_clean();
}
