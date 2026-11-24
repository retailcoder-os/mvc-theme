<?php
/**
 * MV College Universal Creative Page Preloader Engine
 * 
 * Prestigious 10/10 animated page loader for both public frontend and
 * Modern Staff Command Center / Scholar Portal.
 * 
 * Features:
 * - High-resolution institutional emblem with breathing golden aura
 * - Concentric orbiting celestial rings & orbiting spark
 * - Bilingual typography (Hindi & English) with college motto
 * - Sleek metallic indeterminate progress shimmer bar
 * - Seamless fade & blur dissolve transition on load
 * - Non-blocking fail-safe timeout guard (max 2s)
 * - 100% responsive, zero layout shift, accessibility aware
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper to fetch authentic college logo URL with multiple local fallbacks.
 *
 * @return string
 */
function mvc_get_preloader_logo_url() {
    $logo_url = '';

    if (function_exists('get_field')) {
        $cf_logo = get_field('college_logo', 209);
        if (!empty($cf_logo)) {
            $logo_url = is_array($cf_logo) ? ($cf_logo['url'] ?? '') : $cf_logo;
        }
    }

    if (empty($logo_url)) {
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        }
    }

    if (empty($logo_url)) {
        $local_settings = ABSPATH . 'wp-content/uploads/settings/website-logo.png';
        if (file_exists($local_settings)) {
            $logo_url = home_url('/wp-content/uploads/settings/website-logo.png');
        } else {
            $logo_url = home_url('/wp-content/uploads/2026/10/mv-college-website-logo.png');
        }
    }

    return $logo_url;
}

/**
 * Render Public Site Preloader on wp_body_open
 */
add_action('wp_body_open', 'mvc_render_public_page_loader', 1);
function mvc_render_public_page_loader() {
    // Only on public frontend, not on legacy admin screens
    if (is_admin()) {
        return;
    }

    $logo_url = mvc_get_preloader_logo_url();
    $is_portal = is_page('portal') || is_page('login') || is_page('portal-login');
    $status_text = $is_portal ? 'Securing Scholar Gateway...' : 'Loading Official Institutional Gateway...';
    $badge_text  = $is_portal ? 'Scholar & Alumni Portal' : 'Official Institutional Portal';
    ?>
    <!-- ==============================================================
         M.V. COLLEGE BUXAR: PRESTIGIOUS 10/10 PAGE PRELOADER
         ============================================================== -->
    <div id="mvc-page-loader" class="mvc-loader-overlay" role="status" aria-live="polite" aria-label="Loading Page">
        <div class="mvc-loader-backdrop"></div>
        <div class="mvc-loader-container">
            
            <!-- Concentric Emblem Orbit Aura -->
            <div class="mvc-loader-emblem-wrap">
                <div class="mvc-loader-ring mvc-loader-ring-outer" aria-hidden="true"></div>
                <div class="mvc-loader-ring mvc-loader-ring-middle" aria-hidden="true"></div>
                <div class="mvc-loader-ring mvc-loader-ring-inner" aria-hidden="true"></div>
                <div class="mvc-loader-orbit-spark" aria-hidden="true"></div>
                
                <div class="mvc-loader-crest-shield">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Maharshi Vishwamitra Mahavidyalaya Crest" class="mvc-loader-crest-img" width="96" height="96" loading="eager">
                </div>
            </div>

            <!-- Institutional Identity & Bilingual Titles -->
            <div class="mvc-loader-brand">
                <div class="mvc-loader-motto">विद्ययाऽमृतमश्नुते</div>
                <div class="mvc-title-lockup mvc-loader-lockup">
                    <h1 class="mvc-loader-hi-title">महर्षि विश्वामित्र महाविद्यालय</h1>
                    <div class="mvc-loader-en-title">MAHARSHI VISHWAMITRA MAHAVIDYALAYA, BUXAR</div>
                </div>
                <div class="mvc-loader-meta">Estd. 1958 • Constituent Unit of V.K.S. University • UGC 2(f) & 12(B) Recognized</div>
            </div>

            <!-- Elegant Metallic Gold Progress Indicator -->
            <div class="mvc-loader-progress-wrap" aria-hidden="true">
                <div class="mvc-loader-progress-track">
                    <div class="mvc-loader-progress-shimmer"></div>
                </div>
            </div>

            <!-- Dynamic Live Status Pill -->
            <div class="mvc-loader-status-pill">
                <span class="mvc-loader-status-pulse"></span>
                <span class="mvc-loader-status-label"><?php echo esc_html($status_text); ?></span>
            </div>

            <div class="mvc-loader-badge"><?php echo esc_html($badge_text); ?></div>
        </div>
    </div>

    <!-- Inline Critical CSS & Fast Dismiss Script for Zero FOUC -->
    <style id="mvc-preloader-critical-css">
        .mvc-loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(ellipse at 50% 35%, #00224d 0%, #001736 50%, #010a18 100%);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.45s ease, transform 0.45s ease, filter 0.45s ease;
            overflow: hidden;
            pointer-events: all;
        }

        .mvc-loader-overlay.mvc-loader-dissolved {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transform: scale(1.025);
            filter: blur(5px);
        }

        .mvc-loader-backdrop {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 50% 35%, rgba(212, 175, 55, 0.12) 0%, transparent 60%),
                radial-gradient(rgba(212, 175, 55, 0.06) 1px, transparent 1px);
            background-size: 100% 100%, 28px 28px;
            pointer-events: none;
        }

        .mvc-loader-container {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 30px 24px;
            max-width: 580px;
            width: 90%;
            margin: 0 auto;
        }

        /* 1. Concentric Emblem Orbit */
        .mvc-loader-emblem-wrap {
            position: relative;
            width: 154px;
            height: 154px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .mvc-loader-ring {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            box-sizing: border-box;
        }

        .mvc-loader-ring-outer {
            width: 154px;
            height: 154px;
            border: 2px dashed rgba(212, 175, 55, 0.55);
            animation: mvcLoaderSpin 14s linear infinite;
        }

        .mvc-loader-ring-middle {
            width: 134px;
            height: 134px;
            border: 1.5px solid transparent;
            border-top-color: #D4AF37;
            border-bottom-color: #800000;
            box-shadow: 0 0 16px rgba(212, 175, 55, 0.3);
            animation: mvcLoaderSpinReverse 8s linear infinite;
        }

        .mvc-loader-ring-inner {
            width: 118px;
            height: 118px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: radial-gradient(circle, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
        }

        /* Orbiting Spark */
        .mvc-loader-orbit-spark {
            position: absolute;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #FFDF00;
            box-shadow: 0 0 12px 3px #FFDF00, 0 0 20px 6px rgba(212, 175, 55, 0.6);
            top: 72px;
            left: 72px;
            animation: mvcLoaderOrbit 2.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        /* Crest Shield */
        .mvc-loader-crest-shield {
            position: relative;
            z-index: 3;
            width: 92px;
            height: 92px;
            border-radius: 50%;
            background: #ffffff;
            border: 2.5px solid #D4AF37;
            box-shadow: 
                0 0 32px rgba(212, 175, 55, 0.45),
                0 8px 24px rgba(0, 0, 0, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            box-sizing: border-box;
            animation: mvcLoaderBreathe 3s ease-in-out infinite;
        }

        .mvc-loader-crest-img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }

        /* 2. Institutional Brand Typography */
        .mvc-loader-brand {
            margin-bottom: 22px;
        }

        .mvc-loader-motto {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #D4AF37;
            text-transform: uppercase;
            margin-bottom: 6px;
            text-shadow: 0 0 12px rgba(212, 175, 55, 0.4);
        }

        .mvc-loader-lockup {
            display: inline-flex;
            flex-direction: column;
            width: fit-content;
            max-width: 100%;
            margin: 0 auto;
        }

        .mvc-loader-hi-title {
            font-size: clamp(20px, 4.5vw, 26px);
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 6px 0;
            line-height: 1.25;
            letter-spacing: 0.2px;
            font-family: 'Noto Sans Devanagari', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
            display: block;
            width: 100%;
            box-sizing: border-box;
            white-space: nowrap;
        }

        .mvc-loader-en-title {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(11px, 2.5vw, 13px);
            font-weight: 700;
            letter-spacing: 2px;
            color: #F8E7B4;
            text-transform: uppercase;
            margin-bottom: 8px;
            white-space: nowrap;
            width: 100%;
        }

        .mvc-loader-meta {
            font-size: 11.5px;
            color: #94A3B8;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* 3. Progress Bar Track & Shimmer */
        .mvc-loader-progress-wrap {
            width: 240px;
            margin-bottom: 18px;
        }

        .mvc-loader-progress-track {
            position: relative;
            width: 100%;
            height: 4px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.12);
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.4);
        }

        .mvc-loader-progress-shimmer {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 45%;
            border-radius: 99px;
            background: linear-gradient(90deg, #D4AF37 0%, #FFD700 50%, #800000 100%);
            box-shadow: 0 0 14px #FFD700;
            animation: mvcLoaderShimmer 1.8s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        }

        /* 4. Live Status Pill */
        .mvc-loader-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(212, 175, 55, 0.25);
            backdrop-filter: blur(8px);
            margin-bottom: 12px;
        }

        .mvc-loader-status-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10B981;
            box-shadow: 0 0 8px #10B981;
            animation: mvcLoaderPulseDot 1.4s ease-in-out infinite;
        }

        .mvc-loader-status-label {
            font-size: 11.5px;
            font-weight: 600;
            color: #E2E8F0;
            letter-spacing: 0.3px;
        }

        .mvc-loader-badge {
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(212, 175, 55, 0.7);
            font-weight: 700;
        }

        /* Keyframe Animations */
        @keyframes mvcLoaderSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes mvcLoaderSpinReverse {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(0deg); }
        }

        @keyframes mvcLoaderOrbit {
            0% { transform: rotate(0deg) translateY(-76px) rotate(0deg); }
            100% { transform: rotate(360deg) translateY(-76px) rotate(-360deg); }
        }

        @keyframes mvcLoaderBreathe {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 28px rgba(212, 175, 55, 0.35), 0 8px 24px rgba(0, 0, 0, 0.55);
            }
            50% {
                transform: scale(1.045);
                box-shadow: 0 0 42px rgba(212, 175, 55, 0.6), 0 12px 30px rgba(0, 0, 0, 0.7);
            }
        }

        @keyframes mvcLoaderShimmer {
            0% { left: -45%; width: 30%; }
            50% { left: 40%; width: 50%; }
            100% { left: 105%; width: 35%; }
        }

        @keyframes mvcLoaderPulseDot {
            0%, 100% { transform: scale(0.85); opacity: 0.5; }
            50% { transform: scale(1.25); opacity: 1; }
        }

        @media (max-width: 600px) {
            .mvc-loader-emblem-wrap {
                width: 130px;
                height: 130px;
                margin-bottom: 20px;
            }
            .mvc-loader-ring-outer { width: 130px; height: 130px; }
            .mvc-loader-ring-middle { width: 114px; height: 114px; }
            .mvc-loader-ring-inner { width: 98px; height: 98px; }
            .mvc-loader-crest-shield { width: 78px; height: 78px; }
            .mvc-loader-orbit-spark { top: 60px; left: 60px; animation-name: mvcLoaderOrbitMobile; }
            .mvc-loader-progress-wrap { width: 200px; }
        }

        @keyframes mvcLoaderOrbitMobile {
            0% { transform: rotate(0deg) translateY(-64px) rotate(0deg); }
            100% { transform: rotate(360deg) translateY(-64px) rotate(-360deg); }
        }

        @media (prefers-reduced-motion: reduce) {
            .mvc-loader-ring-outer,
            .mvc-loader-ring-middle,
            .mvc-loader-orbit-spark,
            .mvc-loader-crest-shield,
            .mvc-loader-progress-shimmer {
                animation: none !important;
            }
        }
    </style>

    <script id="mvc-preloader-controller">
        (function() {
            var loader = document.getElementById('mvc-page-loader');
            if (!loader) return;

            var startTime = performance.now();
            var minDisplayTime = 380; // Minimum milliseconds to ensure luxury visual presence
            var maxTimeout = 2200;    // Hard fail-safe ceiling to never block user
            var isDissolved = false;

            function dissolveLoader() {
                if (isDissolved) return;
                isDissolved = true;

                var elapsed = performance.now() - startTime;
                var remaining = Math.max(0, minDisplayTime - elapsed);

                setTimeout(function() {
                    loader.classList.add('mvc-loader-dissolved');
                    setTimeout(function() {
                        if (loader && loader.parentNode) {
                            loader.style.display = 'none';
                        }
                    }, 500);
                }, remaining);
            }

            // 1. Primary dismissal trigger on window load complete
            if (document.readyState === 'complete') {
                dissolveLoader();
            } else {
                window.addEventListener('load', dissolveLoader);
            }

            // 2. Hard fail-safe guard
            setTimeout(dissolveLoader, maxTimeout);
        })();
    </script>
    <?php
}

/**
 * Render Creative Modern Preloader in Command Center Shell
 */
function mvc_render_command_center_preloader() {
    $logo_url = mvc_get_preloader_logo_url();
    ?>
    <div id="mvc-cc-page-loader" class="mvc-cc-loader-overlay" role="status" aria-live="polite" aria-label="Loading Command Center">
        <div class="mvc-loader-backdrop"></div>
        <div class="mvc-loader-container">
            <!-- Concentric Emblem Orbit Aura -->
            <div class="mvc-loader-emblem-wrap">
                <div class="mvc-loader-ring mvc-loader-ring-outer" aria-hidden="true"></div>
                <div class="mvc-loader-ring mvc-loader-ring-middle" aria-hidden="true"></div>
                <div class="mvc-loader-ring mvc-loader-ring-inner" aria-hidden="true"></div>
                <div class="mvc-loader-orbit-spark" aria-hidden="true"></div>
                
                <div class="mvc-loader-crest-shield">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="M.V. College Crest" class="mvc-loader-crest-img" width="92" height="92" loading="eager">
                </div>
            </div>

            <!-- Identity -->
            <div class="mvc-loader-brand">
                <div class="mvc-loader-motto">विद्ययाऽमृतमश्नुते</div>
                <div class="mvc-title-lockup mvc-loader-lockup">
                    <h2 class="mvc-loader-hi-title">महर्षि विश्वामित्र महाविद्यालय</h2>
                    <div class="mvc-loader-en-title">STAFF COMMAND CENTER • MODERN CMS</div>
                </div>
                <div class="mvc-loader-meta">Role-Based Intelligence • Unified Institutional Content Desk</div>
            </div>

            <!-- Progress Track -->
            <div class="mvc-loader-progress-wrap" aria-hidden="true">
                <div class="mvc-loader-progress-track">
                    <div class="mvc-loader-progress-shimmer"></div>
                </div>
            </div>

            <!-- Live Status -->
            <div class="mvc-loader-status-pill">
                <span class="mvc-loader-status-pulse"></span>
                <span class="mvc-loader-status-label" id="mvcCcLoaderStatus">Initializing Command Center Workspace...</span>
            </div>

            <div class="mvc-loader-badge">Secure Administrative Environment</div>
        </div>
    </div>
    <style>
        .mvc-cc-loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 99999999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(ellipse at 50% 35%, #001f45 0%, #001229 55%, #000814 100%);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.45s ease, transform 0.45s ease, filter 0.45s ease;
            overflow: hidden;
            pointer-events: all;
        }
        .mvc-cc-loader-overlay.mvc-loader-dissolved {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transform: scale(1.025);
            filter: blur(5px);
        }
    </style>
    <script>
        (function() {
            var ccLoader = document.getElementById('mvc-cc-page-loader');
            if (!ccLoader) return;

            var ccStart = performance.now();
            var minTime = 420;
            var maxCeiling = 2000;
            var dissolved = false;

            window.mvcDismissCcLoader = function(customLabel) {
                if (dissolved) return;
                dissolved = true;

                if (customLabel) {
                    var lbl = document.getElementById('mvcCcLoaderStatus');
                    if (lbl) lbl.textContent = customLabel;
                }

                var elapsed = performance.now() - ccStart;
                var rem = Math.max(0, minTime - elapsed);

                setTimeout(function() {
                    ccLoader.classList.add('mvc-loader-dissolved');
                    setTimeout(function() {
                        if (ccLoader && ccLoader.parentNode) {
                            ccLoader.style.display = 'none';
                        }
                    }, 500);
                }, rem);
            };

            // Fail-safe auto dismiss
            setTimeout(function() {
                window.mvcDismissCcLoader('Workspace Ready');
            }, maxCeiling);
        })();
    </script>
    <?php
}
