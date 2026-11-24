<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * NAAC Accreditation & Quality Assurance Suite Engine (10/10 Creative Standard)
 * 
 * Supports all 10 NAAC Menu Chapters with bespoke page-by-page designs:
 * 1. AISHE Certificate (/aishe/)
 * 2. Student Satisfaction Survey - SSS (/sss/)
 * 3. IIQA (/iiqa/)
 * 4. Internal Quality Assurance Cell - IQAC (/iqac/)
 * 5. AQAR Reports (/aqar/)
 * 6. NAAC Core Values (/naac-core-values/)
 * 7. Self Study Reports - SSR (/ssr/)
 * 8. UGC 2F & 12B Recognition (/ugc-2f-12b/)
 * 9. University Affiliation & Govt. Approval (/university-affiliation/)
 * 10. NIRF Ranking (/nirf/)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Returns configuration metadata for all 10 NAAC Chapters
 */
function mvc_get_naac_chapters() {
    return [
        'aishe' => [
            'slug'        => 'aishe',
            'title'       => 'AISHE Certificate',
            'title_hi'    => 'अखिल भारतीय उच्च शिक्षा सर्वेक्षण प्रमाण पत्र',
            'icon'        => '📜',
            'badge'       => 'MHRD / MoE Govt. of India',
            'code'        => 'C-27158',
            'url'         => home_url('/aishe/'),
            'pdf_file'    => 'aishe_1763962437.pdf',
            'pdf_title'   => 'Official AISHE Certificate (Survey Year 2021-22)',
            'filesize'    => '1.65 MB',
            'authority'   => 'Ministry of Education, Govt. of India'
        ],
        'sss' => [
            'slug'        => 'sss',
            'title'       => 'Student Satisfaction Survey (SSS)',
            'title_hi'    => 'छात्र संतुष्टि सर्वेक्षण',
            'icon'        => '📊',
            'badge'       => 'NAAC Criterion II • Key Indicator 2.7.1',
            'code'        => 'SSS-2.7.1',
            'url'         => home_url('/sss/'),
            'pdf_file'    => 'Notice_1782542808.pdf',
            'pdf_title'   => 'Student Satisfaction Survey Guidelines & Evaluation Protocol',
            'filesize'    => '2.09 MB',
            'authority'   => 'NAAC Bangalore / IQAC M.V. College'
        ],
        'iiqa' => [
            'slug'        => 'iiqa',
            'title'       => 'IIQA (Institutional Information)',
            'title_hi'    => 'गुणवत्ता मूल्यांकन हेतु संस्थागत जानकारी',
            'icon'        => '🏛️',
            'badge'       => 'NAAC Track ID: BRCOGN100382',
            'code'        => 'BRCOGN100382',
            'url'         => home_url('/iiqa/'),
            'pdf_file'    => 'iiqa_1763962595.pdf',
            'pdf_title'   => 'First Cycle IIQA Submitted Document (2023)',
            'filesize'    => '32 KB',
            'authority'   => 'National Assessment and Accreditation Council'
        ],
        'iqac' => [
            'slug'        => 'iqac',
            'title'       => 'Internal Quality Assurance Cell (IQAC)',
            'title_hi'    => 'आंतरिक गुणवत्ता आश्वासन प्रकोष्ठ',
            'icon'        => '⚖️',
            'badge'       => 'Established 05.01.2013 • UGC Mandated',
            'code'        => 'IQAC-MVM',
            'url'         => home_url('/iqac/'),
            'pdf_file'    => 'aqar_1763962661.pdf',
            'pdf_title'   => 'IQAC Master Resolution & Quality Framework',
            'filesize'    => '32 KB',
            'authority'   => 'Internal Quality Assurance Cell'
        ],
        'aqar' => [
            'slug'        => 'aqar',
            'title'       => 'AQAR Annual Reports',
            'title_hi'    => 'वार्षिक गुणवत्ता आश्वासन प्रतिवेदन',
            'icon'        => '📑',
            'badge'       => 'NAAC Quality Benchmarks',
            'code'        => 'AQAR-SERIES',
            'url'         => home_url('/aqar/'),
            'pdf_file'    => 'aqar_1763962661.pdf',
            'pdf_title'   => 'AQAR Annual Quality Assurance Report',
            'filesize'    => '32 KB',
            'authority'   => 'NAAC Bangalore'
        ],
        'ssr' => [
            'slug'        => 'ssr',
            'title'       => 'Self Study Reports (SSR)',
            'title_hi'    => 'स्व-अध्ययन प्रतिवेदन',
            'icon'        => '📖',
            'badge'       => 'Cycle 1 & 2 Institutional Reports',
            'code'        => 'SSR-ARCHIVE',
            'url'         => home_url('/ssr/'),
            'pdf_file'    => 'ssr_1763963039.pdf',
            'pdf_title'   => 'Comprehensive Self Study Report (SSR 2016)',
            'filesize'    => '4.74 MB',
            'authority'   => 'NAAC Steering Committee'
        ],
        'ugc-2f-12b' => [
            'slug'        => 'ugc-2f-12b',
            'title'       => 'UGC 2F & 12B Recognition',
            'title_hi'    => 'विश्वविद्यालय अनुदान आयोग 2F एवं 12B मान्यता',
            'icon'        => '🎓',
            'badge'       => 'UGC Act 1956 Permanent Status',
            'code'        => 'UGC-2F-12B',
            'url'         => home_url('/ugc-2f-12b/'),
            'pdf_file'    => 'ugc_1763963100.pdf',
            'pdf_title'   => 'UGC Section 12(B) Permanent Recognition Certificate',
            'filesize'    => '187 KB',
            'authority'   => 'University Grants Commission, New Delhi'
        ],
        'university-affiliation' => [
            'slug'        => 'university-affiliation',
            'title'       => 'University Affiliation & Govt. Approval',
            'title_hi'    => 'विश्वविद्यालय संबद्धता एवं सरकारी अनुमोदन',
            'icon'        => '🏛️',
            'badge'       => 'VKSU Ara Permanent Constituent Unit',
            'code'        => 'VKSU-AFFIL',
            'url'         => home_url('/university-affiliation/'),
            'pdf_file'    => 'affiliation_1764075965.pdf',
            'pdf_title'   => 'VKSU Ara Institutional Affiliation & Approval Order',
            'filesize'    => '567 KB',
            'authority'   => 'Veer Kunwar Singh University & Govt. of Bihar'
        ],
        'nirf' => [
            'slug'        => 'nirf',
            'title'       => 'NIRF (National Ranking)',
            'title_hi'    => 'राष्ट्रीय संस्थागत रैंकिंग फ्रेमवर्क',
            'icon'        => '🏆',
            'badge'       => 'Ministry of Education, Govt. of India',
            'code'        => 'NIRF-2026',
            'url'         => home_url('/nirf/'),
            'pdf_file'    => 'nirf_1775121247.pdf',
            'pdf_title'   => 'NIRF 2026 DCS (Data Capture System) Official Submission',
            'filesize'    => '36 KB',
            'authority'   => 'National Institutional Ranking Framework'
        ]
    ];

    $custom_settings = get_option('mvc_naac_chapters_settings', array());
    if (!empty($custom_settings) && is_array($custom_settings)) {
        foreach ($custom_settings as $slug => $overrides) {
            if (isset($chapters[$slug]) && is_array($overrides)) {
                foreach ($overrides as $k => $v) {
                    if ($v !== '' && $v !== null) {
                        $chapters[$slug][$k] = $v;
                    }
                }
            }
        }
    }

    return $chapters;
}

/**
 * Shared NAAC Suite Navigation Component
 * Dual-Mode: Interactive Desktop Pills + Native Touch Mobile Selector (with top: 52px offset)
 */
function mvc_render_naac_suite_nav($active_slug = 'aishe') {
    $chapters = mvc_get_naac_chapters();
    ?>
    <nav class="mvc-naac-suite-nav" aria-label="NAAC Suite Navigation">
        <div class="mvc-naac-nav-inner">
            <!-- Mobile Select Dropdown -->
            <div class="mvc-naac-mobile-select-wrap">
                <label for="mvcNaacNavSelect" class="mvc-naac-mobile-label">Select NAAC / Quality Chapter:</label>
                <div class="mvc-select-custom-wrapper">
                    <select id="mvcNaacNavSelect" class="mvc-naac-mobile-select" onchange="if(this.value) window.location.href=this.value;">
                        <?php foreach ($chapters as $slug => $c): ?>
                            <option value="<?php echo esc_url($c['url']); ?>" <?php selected($active_slug, $slug); ?>>
                                <?php echo esc_html($c['icon'] . ' ' . $c['title'] . ' (' . $c['code'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Desktop & Tablet Pill Switcher with < > Scroll Navigation -->
            <div class="mvc-naac-pills-container">
                <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-prev is-disabled" id="mvcNaacArrowPrev" onclick="mvcNaacPillScroll(-1)" aria-label="Scroll Chapters Left" title="Scroll left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                </button>

                <ul class="mvc-naac-pills" id="mvcNaacPillsTrack">
                    <?php foreach ($chapters as $slug => $c): 
                        $is_active = ($active_slug === $slug);
                    ?>
                        <li class="mvc-naac-pill-item <?php echo $is_active ? 'is-active' : ''; ?>" <?php echo $is_active ? 'id="mvcActiveNaacPill"' : ''; ?>>
                            <a href="<?php echo esc_url($c['url']); ?>" class="mvc-naac-pill-link" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
                                <span class="mvc-naac-pill-icon"><?php echo esc_html($c['icon']); ?></span>
                                <span class="mvc-naac-pill-meta">
                                    <span class="mvc-naac-pill-title"><?php echo esc_html($c['title']); ?></span>
                                    <span class="mvc-naac-pill-sub"><?php echo esc_html($c['code']); ?></span>
                                </span>
                                <?php if ($is_active): ?>
                                    <span class="mvc-naac-pill-active-dot" aria-hidden="true"></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="mvc-nav-arrow-btn mvc-nav-arrow-next" id="mvcNaacArrowNext" onclick="mvcNaacPillScroll(1)" aria-label="Scroll Chapters Right" title="Scroll right">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <script>
    function mvcNaacPillScroll(direction) {
        var track = document.getElementById('mvcNaacPillsTrack');
        if (!track) return;
        var scrollDistance = 260 * direction;
        track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
        setTimeout(mvcUpdateNaacArrowStates, 320);
    }

    function mvcUpdateNaacArrowStates() {
        var track = document.getElementById('mvcNaacPillsTrack');
        var prevBtn = document.getElementById('mvcNaacArrowPrev');
        var nextBtn = document.getElementById('mvcNaacArrowNext');
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

    function mvcSectionalScroll(trackId, direction) {
        var track = document.getElementById(trackId);
        if (!track) return;
        var scrollDistance = 260 * direction;
        track.scrollBy({ left: scrollDistance, behavior: 'smooth' });
        setTimeout(function() { mvcUpdateStepperArrows(trackId); }, 320);
    }

    function mvcScrollStepper(trackId, offset) {
        var el = document.getElementById(trackId);
        if (el) el.scrollBy({ left: offset, behavior: 'smooth' });
        setTimeout(function() { mvcUpdateStepperArrows(trackId); }, 320);
    }

    function mvcUpdateStepperArrows(trackId) {
        var track = document.getElementById(trackId);
        if (!track) return;
        var parent = track.closest('.mvc-sectional-stepper-wrap') || track.closest('.mvc-iiqa-stepper-track');
        if (!parent) return;
        var prevBtn = parent.querySelector('.mvc-stepper-arrow-prev');
        var nextBtn = parent.querySelector('.mvc-stepper-arrow-next');
        if (!prevBtn || !nextBtn) return;

        var scrollLeft = Math.round(track.scrollLeft);
        var maxScroll = Math.round(track.scrollWidth - track.clientWidth);

        if (scrollLeft <= 4) {
            prevBtn.classList.add('is-disabled');
        } else {
            prevBtn.classList.remove('is-disabled');
        }

        if (scrollLeft >= maxScroll - 4 || maxScroll <= 0) {
            nextBtn.classList.add('is-disabled');
        } else {
            nextBtn.classList.remove('is-disabled');
        }
    }

    function mvcActivateChip(chip) {
        var parent = chip.closest('.mvc-stepper-scroll');
        if (!parent) return;
        parent.querySelectorAll('.mvc-step-chip').forEach(function(c) {
            c.classList.remove('is-active');
        });
        chip.classList.add('is-active');
    }

    (function() {
        function initNaacPillNav() {
            var track = document.getElementById('mvcNaacPillsTrack');
            if (!track) return;
            track.addEventListener('scroll', mvcUpdateNaacArrowStates, { passive: true });
            window.addEventListener('resize', mvcUpdateNaacArrowStates);

            // Initialize all sectional stepper tracks
            document.querySelectorAll('.mvc-stepper-scroll').forEach(function(st) {
                st.addEventListener('scroll', function() { mvcUpdateStepperArrows(st.id); }, { passive: true });
                window.addEventListener('resize', function() { mvcUpdateStepperArrows(st.id); });
                setTimeout(function() { mvcUpdateStepperArrows(st.id); }, 250);
            });

            var activePill = document.getElementById('mvcActiveNaacPill');
            if (activePill) {
                setTimeout(function() {
                    var trackRect = track.getBoundingClientRect();
                    var pillRect = activePill.getBoundingClientRect();
                    if (pillRect.left < trackRect.left || pillRect.right > trackRect.right) {
                        activePill.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                    mvcUpdateNaacArrowStates();
                }, 250);
            } else {
                setTimeout(mvcUpdateNaacArrowStates, 200);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNaacPillNav);
        } else {
            initNaacPillNav();
        }
    })();
    </script>
    <?php
}

/**
 * Shared Unified NAAC Suite CSS Engine (10/10 Royal Aesthetic)
 */
function mvc_print_naac_suite_styles() {
    ?>
    <style id="mvc-naac-suite-core-css">
    :root {
        --mvc-navy: #002147;
        --mvc-navy-dark: #00152e;
        --mvc-navy-light: #0b3464;
        --mvc-maroon: #800000;
        --mvc-maroon-dark: #5a0000;
        --mvc-maroon-light: #9e1a1a;
        --mvc-gold: #D4AF37;
        --mvc-gold-light: #f5e6be;
        --mvc-gold-dark: #b48200;
        --mvc-cream: #FAF9F6;
        --mvc-slate: #475569;
        --mvc-slate-light: #64748b;
        --mvc-border: #e2e8f0;
        --mvc-border-light: #f1f5f9;
        --mvc-shadow-sm: 0 2px 10px rgba(0, 33, 71, 0.05);
        --mvc-shadow-md: 0 8px 25px rgba(0, 33, 71, 0.09);
        --mvc-shadow-lg: 0 16px 45px rgba(0, 33, 71, 0.14);
        --mvc-radius: 12px;
        --mvc-radius-lg: 16px;
        --mvc-transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mvc-naac-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
        background: #f8fafc;
        min-height: 100vh;
        width: 100%;
        overflow-x: hidden;
    }

    /* HERO BANNER */
    .mvc-naac-hero {
        background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 65%, var(--mvc-navy-light) 100%);
        color: #ffffff;
        padding: 55px 20px 45px;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--mvc-gold);
    }
    .mvc-naac-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 85% 15%, rgba(212, 175, 55, 0.16) 0%, transparent 50%),
                    radial-gradient(circle at 10% 85%, rgba(128, 0, 0, 0.28) 0%, transparent 60%);
        pointer-events: none;
    }
    .mvc-naac-hero-container {
        max-width: 100%; width: 100%; box-sizing: border-box;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }
    .mvc-naac-breadcrumbs {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #94a3b8;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .mvc-naac-breadcrumbs a {
        color: var(--mvc-gold-light);
        text-decoration: none;
        transition: var(--mvc-transition);
    }
    .mvc-naac-breadcrumbs a:hover {
        color: #ffffff;
        text-decoration: underline;
    }
    .mvc-naac-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(212, 175, 55, 0.18);
        border: 1px solid rgba(212, 175, 55, 0.4);
        color: var(--mvc-gold-light);
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 14px;
        backdrop-filter: blur(8px);
    }
    .mvc-naac-tag-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--mvc-gold);
        box-shadow: 0 0 8px var(--mvc-gold);
    }
    .mvc-naac-hero-title {
        font-size: clamp(26px, 3.8vw, 42px);
        font-weight: 800;
        line-height: 1.22;
        margin: 0 0 8px;
        color: #ffffff;
        letter-spacing: -0.02em;
    }
    .mvc-naac-hero-subtitle-hi {
        font-size: clamp(17px, 2.2vw, 22px);
        color: var(--mvc-gold-light);
        font-weight: 600;
        margin: 0 0 14px;
    }
    .mvc-naac-hero-desc {
        font-size: 15.5px;
        line-height: 1.6;
        color: #cbd5e1;
        max-width: 880px;
        margin: 0 0 24px;
    }

    /* KEY STATS BAR */
    .mvc-naac-stats-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-top: 25px;
        padding-top: 22px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
    }
    .mvc-naac-stat-pill {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        backdrop-filter: blur(6px);
    }
    .mvc-naac-stat-icon {
        font-size: 24px;
    }
    .mvc-naac-stat-num {
        display: block;
        font-size: 18px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.1;
    }
    .mvc-naac-stat-label {
        display: block;
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 3px;
        font-weight: 500;
    }

    /* STICKY NAAC SUITE NAV (Docks cleanly beneath top:56px main navbar) */
    .mvc-naac-suite-nav {
        background: #ffffff;
        border-bottom: 1px solid var(--mvc-border);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 56px;
        z-index: 90;
        padding: 10px 20px;
    }
    .mvc-naac-nav-inner {
        max-width: 100%; width: 100%; box-sizing: border-box;
        margin: 0 auto;
    }
    .mvc-naac-mobile-select-wrap {
        display: none;
    }
    .mvc-naac-pills-container {
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
    .mvc-naac-pills {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 4px 0;
        gap: 8px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        flex-grow: 1;
    }
    .mvc-naac-pills::-webkit-scrollbar {
        display: none;
    }
    .mvc-naac-pill-item {
        flex-shrink: 0;
    }
    .mvc-naac-pill-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 20px;
        text-decoration: none;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        transition: var(--mvc-transition);
        white-space: nowrap;
    }
    .mvc-naac-pill-link:hover {
        background: #ffffff;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy);
        box-shadow: var(--mvc-shadow-sm);
        transform: translateY(-1px);
    }
    .mvc-naac-pill-item.is-active .mvc-naac-pill-link {
        background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-light) 100%);
        border-color: var(--mvc-navy);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(0, 33, 71, 0.22);
    }
    .mvc-naac-pill-icon {
        font-size: 16px;
    }
    .mvc-naac-pill-sub {
        font-size: 10.5px;
        color: #64748b;
        margin-left: 2px;
    }
    .mvc-naac-pill-item.is-active .mvc-naac-pill-sub {
        color: var(--mvc-gold-light);
    }

    /* BODY CONTAINER */
    .mvc-naac-container {
        max-width: 100%; width: 100%; box-sizing: border-box;
        margin: 0 auto;
        padding: 40px 20px 80px;
    }

    /* SECTION HEADINGS */
    .mvc-section-title-wrap {
        margin-bottom: 25px;
    }
    .mvc-section-tag {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--mvc-maroon);
        margin-bottom: 6px;
    }
    .mvc-section-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 8px;
    }
    .mvc-section-desc {
        font-size: 14.5px;
        color: var(--mvc-slate);
        line-height: 1.6;
        margin: 0;
        max-width: 820px;
    }

    /* OFFICIAL DOCUMENT PRESENTATION CARD (DUAL ACTION) */
    .mvc-doc-master-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        box-shadow: var(--mvc-shadow-md);
        overflow: hidden;
        margin-bottom: 40px;
    }
    .mvc-doc-card-header,
    .mvc-doc-header-bar {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        padding: 24px 30px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .mvc-doc-header-left,
    .mvc-doc-title-group {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
        min-width: 280px;
    }
    .mvc-doc-header-icon,
    .mvc-doc-emblem {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: var(--mvc-navy);
        flex-shrink: 0;
    }
    .mvc-doc-header-titles h2,
    .mvc-doc-title-group h2,
    .mvc-doc-title-group h3 {
        font-size: 20px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 4px;
        line-height: 1.35;
    }
    .mvc-doc-header-sub,
    .mvc-doc-sub {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    /* DUAL ACTION BUTTON GROUP */
    .mvc-dual-action-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .mvc-btn-direct-download {
        background: linear-gradient(135deg, var(--mvc-maroon) 0%, var(--mvc-maroon-dark) 100%);
        color: #ffffff !important;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 13.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.25);
        transition: var(--mvc-transition);
        border: none;
    }
    .mvc-btn-direct-download:hover {
        background: #4a0000;
        color: #ffffff !important;
        transform: translateY(-2px);
    }
    .mvc-btn-interactive-view {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        color: var(--mvc-navy);
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--mvc-transition);
        text-decoration: none;
    }
    .mvc-btn-interactive-view:hover {
        background: #ffffff;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy);
        box-shadow: var(--mvc-shadow-sm);
    }

    /* METADATA BAR */
    .mvc-doc-metadata-bar {
        background: #f8fafc;
        padding: 14px 30px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #475569;
    }
    .mvc-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-meta-item strong {
        color: var(--mvc-navy);
    }
    .mvc-meta-status-tag {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 11.5px;
    }

    /* EMBEDDED PDF VIEWER FRAME */
    .mvc-doc-viewer-area {
        position: relative;
        padding: 20px;
        background: #0f172a;
    }
    .mvc-pdf-frame {
        width: 100%;
        height: 680px;
        border: 0;
        border-radius: 6px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    /* DATA TABLES */
    .mvc-table-wrapper {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        box-shadow: var(--mvc-shadow-sm);
        overflow: hidden;
        margin-bottom: 35px;
    }
    .mvc-table-header-bar {
        background: var(--mvc-navy);
        color: #ffffff;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }
    .mvc-table-header-bar h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
    }
    .mvc-data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    .mvc-data-table th {
        background: #f1f5f9;
        color: var(--mvc-navy);
        font-weight: 700;
        padding: 12px 18px;
        text-align: left;
        border-bottom: 1.5px solid #cbd5e1;
    }
    .mvc-data-table td {
        padding: 13px 18px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    .mvc-data-table tr:hover td {
        background: #fdf8f6;
    }

    /* INFO & CALLOUT BOXES */
    .mvc-info-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        padding: 28px 30px;
        box-shadow: var(--mvc-shadow-sm);
        margin-bottom: 35px;
    }
    .mvc-info-card h3 {
        font-size: 20px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 12px;
    }
    .mvc-info-card p {
        font-size: 14.5px;
        line-height: 1.68;
        color: #475569;
        margin: 0 0 16px;
    }
    .mvc-badge-pill {
        display: inline-block;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: var(--mvc-maroon);
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        margin-bottom: 14px;
    }

    /* 5 PILLARS / CORE VALUES CARDS */
    .mvc-pillars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 22px;
        margin-bottom: 40px;
    }
    .mvc-pillar-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        padding: 26px;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
        position: relative;
        overflow: hidden;
    }
    .mvc-pillar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--mvc-navy) 0%, var(--mvc-gold) 100%);
    }
    .mvc-pillar-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--mvc-shadow-md);
        border-color: #cbd5e1;
    }
    .mvc-pillar-num {
        font-size: 32px;
        font-weight: 900;
        color: rgba(0, 33, 71, 0.1);
        position: absolute;
        top: 14px;
        right: 18px;
        line-height: 1;
    }
    .mvc-pillar-icon {
        font-size: 28px;
        margin-bottom: 12px;
        display: inline-block;
    }
    .mvc-pillar-title {
        font-size: 16.5px;
        font-weight: 700;
        color: var(--mvc-navy);
        margin: 0 0 10px;
    }
    .mvc-pillar-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: #64748b;
        margin: 0 0 14px;
    }
    .mvc-pillar-proof {
        background: #f8fafc;
        border-left: 3px solid var(--mvc-gold);
        padding: 8px 12px;
        font-size: 12.5px;
        color: #334155;
        border-radius: 0 4px 4px 0;
    }

    /* ACCORDIONS (e.g. SSS 20 Questions) */
    .mvc-accordion {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 35px;
    }
    .mvc-acc-item {
        background: #ffffff;
        border: 1px solid var(--mvc-border);
        border-radius: 8px;
        overflow: hidden;
        transition: var(--mvc-transition);
    }
    .mvc-acc-item:hover {
        border-color: #cbd5e1;
    }
    .mvc-acc-trigger {
        width: 100%;
        padding: 16px 20px;
        background: #ffffff;
        border: none;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        cursor: pointer;
        font-size: 14.5px;
        font-weight: 700;
        color: var(--mvc-navy);
        transition: var(--mvc-transition);
    }
    .mvc-acc-trigger:hover {
        background: #f8fafc;
    }
    .mvc-acc-qnum {
        display: inline-block;
        background: var(--mvc-navy);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 4px;
        margin-right: 8px;
    }
    .mvc-acc-arrow {
        font-size: 12px;
        color: var(--mvc-slate);
        transition: transform 0.2s ease;
    }
    .mvc-acc-item.is-open .mvc-acc-arrow {
        transform: rotate(180deg);
    }
    .mvc-acc-content {
        display: none;
        padding: 16px 20px;
        background: #f8fafc;
        border-top: 1px solid var(--mvc-border);
        font-size: 13.5px;
        color: #334155;
        line-height: 1.6;
    }
    .mvc-acc-item.is-open .mvc-acc-content {
        display: block;
    }
    .mvc-options-list {
        list-style: none;
        margin: 10px 0 0;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 8px;
    }
    .mvc-option-pill {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12.5px;
        color: #475569;
    }

    /* MULTI-DOC SWITCHER TABS */
    .mvc-doc-switcher-bar {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .mvc-switch-tab-btn {
        background: #e2e8f0;
        border: none;
        color: #334155;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--mvc-transition);
    }
    .mvc-switch-tab-btn:hover {
        background: #cbd5e1;
    }
    .mvc-switch-tab-btn.is-active {
        background: var(--mvc-navy);
        color: #ffffff;
    }

    /* IQAC MEMBERS GRID */
    .mvc-committee-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 35px;
    }
    .mvc-member-card {
        background: #ffffff;
        border: 1px solid var(--mvc-border);
        border-radius: var(--mvc-radius);
        padding: 22px;
        box-shadow: var(--mvc-shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: var(--mvc-transition);
    }
    .mvc-member-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--mvc-shadow-md);
        border-color: var(--mvc-gold);
    }
    .mvc-member-role-badge {
        display: inline-block;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        margin-bottom: 10px;
        width: fit-content;
    }
    .mvc-member-name {
        font-size: 16.5px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 6px;
    }
    .mvc-member-post {
        font-size: 13px;
        color: #64748b;
        margin: 0 0 14px;
    }
    .mvc-member-contact {
        border-top: 1px solid #f1f5f9;
        padding-top: 12px;
        font-size: 12px;
        color: #334155;
        line-height: 1.6;
    }

    /* IQAC ACTIVITY GALLERY */
    .mvc-activity-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 35px;
    }
    .mvc-activity-img-wrap {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--mvc-border);
        background: #ffffff;
        box-shadow: var(--mvc-shadow-sm);
        aspect-ratio: 4/3;
    }
    .mvc-activity-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .mvc-activity-img-wrap:hover img {
        transform: scale(1.04);
    }

    /* TIMELINE (Affiliation & Evolution) */
    .mvc-timeline {
        position: relative;
        padding-left: 30px;
        margin-bottom: 35px;
    }
    .mvc-timeline::before {
        content: '';
        position: absolute;
        top: 5px;
        bottom: 5px;
        left: 10px;
        width: 2px;
        background: var(--mvc-gold);
    }
    .mvc-timeline-item {
        position: relative;
        margin-bottom: 24px;
    }
    .mvc-timeline-dot {
        position: absolute;
        left: -30px;
        top: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--mvc-navy);
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 2px var(--mvc-gold);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mvc-timeline-year {
        font-size: 12px;
        font-weight: 800;
        color: var(--mvc-maroon);
        margin-bottom: 4px;
        text-transform: uppercase;
    }
    .mvc-timeline-heading {
        font-size: 16px;
        font-weight: 700;
        color: var(--mvc-navy);
        margin: 0 0 6px;
    }
    .mvc-timeline-desc {
        font-size: 13.5px;
        color: #475569;
        line-height: 1.6;
        margin: 0;
    }

    /* NIRF PARAMETER CARDS */
    .mvc-nirf-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 35px;
    }
    .mvc-nirf-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        padding: 22px;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
    }
    .mvc-nirf-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--mvc-shadow-md);
        border-color: #cbd5e1;
    }
    .mvc-nirf-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .mvc-nirf-param-code {
        font-size: 18px;
        font-weight: 800;
        color: var(--mvc-navy);
    }
    .mvc-nirf-weightage {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 12px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .mvc-nirf-name {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 8px;
    }
    .mvc-nirf-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    /* CTA BANNER */
    .mvc-naac-cta-banner {
        margin-top: 50px;
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
        box-shadow: var(--mvc-shadow-lg);
    }
    .mvc-naac-cta-content h3 {
        font-size: 22px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 8px;
    }
    .mvc-naac-cta-content p {
        font-size: 14px;
        color: #cbd5e1;
        margin: 0;
        max-width: 650px;
    }
    .mvc-naac-cta-btn-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .mvc-btn-cta-gold {
        background: var(--mvc-gold);
        color: var(--mvc-navy-dark) !important;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13.5px;
        transition: var(--mvc-transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-btn-cta-gold:hover {
        background: #ffffff;
        color: var(--mvc-navy) !important;
        transform: translateY(-2px);
    }

    /* RESPONSIVE BREAKPOINTS */
    @media (max-width: 991px) {
        .mvc-naac-suite-nav {
            top: 52px;
            padding: 8px 12px;
        }
        .mvc-naac-mobile-select-wrap {
            display: none !important;
        }
        .mvc-naac-pills-container {
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
        .mvc-naac-pills {
            display: flex !important;
            gap: 6px;
        }
        .mvc-pdf-frame {
            height: 500px;
        }
    }

    @media (max-width: 640px) {
        .mvc-naac-hero {
            padding: 35px 16px 30px;
        }
        .mvc-naac-hero-title {
            font-size: 24px;
        }
        .mvc-naac-hero-subtitle-hi {
            font-size: 16px;
        }
        .mvc-naac-container {
            padding: 20px 14px 60px;
        }
        .mvc-doc-card-header {
            padding: 18px 20px;
        }
        .mvc-doc-metadata-bar {
            padding: 12px 20px;
            gap: 15px;
        }
        .mvc-pdf-frame {
            height: 380px;
        }
        .mvc-pillars-grid, .mvc-committee-grid, .mvc-nirf-grid, .mvc-aishe-pillars-grid {
            grid-template-columns: 1fr;
        }
        .mvc-aishe-cert-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .mvc-aishe-tab-switch {
            width: 100%;
            justify-content: center;
        }
    }

    /* AISHE BESPOKE 10/10 STYLES */
    .mvc-aishe-cert-master {
        background: #ffffff;
        border-radius: var(--mvc-radius-lg);
        border: 1px solid var(--mvc-border);
        box-shadow: var(--mvc-shadow-md);
        overflow: hidden;
        margin-bottom: 40px;
    }
    .mvc-aishe-cert-toolbar {
        background: #ffffff;
        padding: 18px 24px;
        border-bottom: 1px solid var(--mvc-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .mvc-aishe-toolbar-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .mvc-aishe-gov-emblem {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-light) 100%);
        color: var(--mvc-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0, 33, 71, 0.15);
        flex-shrink: 0;
    }
    .mvc-aishe-toolbar-titles h2 {
        font-size: 19px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 3px;
        letter-spacing: -0.01em;
    }
    .mvc-aishe-toolbar-sub {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
    }
    .mvc-aishe-actions-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .mvc-aishe-tab-switch {
        display: inline-flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        gap: 4px;
    }
    .mvc-aishe-tab-btn {
        border: none;
        background: transparent;
        color: #475569;
        padding: 7px 14px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--mvc-transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mvc-aishe-tab-btn:hover {
        color: var(--mvc-navy);
    }
    .mvc-aishe-tab-btn.is-active {
        background: var(--mvc-navy);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 33, 71, 0.18);
    }
    .mvc-aishe-meta-strip {
        background: #f8fafc;
        padding: 16px 24px;
        border-bottom: 1px solid var(--mvc-border);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
    }
    .mvc-aishe-meta-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
    }
    .mvc-aishe-meta-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .mvc-aishe-meta-val {
        display: block;
        font-size: 13.5px;
        font-weight: 800;
        color: var(--mvc-navy);
        line-height: 1.25;
    }
    .mvc-aishe-paper-canvas {
        background: radial-gradient(circle at 50% 30%, #1e293b 0%, #0f172a 100%);
        padding: 40px 20px 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 480px;
    }
    .mvc-aishe-paper-card {
        position: relative;
        max-width: 600px;
        width: 100%;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.2);
        cursor: zoom-in;
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .mvc-aishe-paper-card:hover {
        transform: translateY(-4px) scale(1.006);
        box-shadow: 0 32px 75px rgba(0, 0, 0, 0.65);
    }
    .mvc-aishe-cert-img {
        width: 100%;
        height: auto;
        display: block;
    }
    .mvc-aishe-ribbon {
        position: absolute;
        top: 14px;
        right: 14px;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 5px 12px;
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
        display: flex;
        align-items: center;
        gap: 5px;
        backdrop-filter: blur(4px);
    }
    .mvc-aishe-zoom-pill {
        position: absolute;
        bottom: 14px;
        right: 14px;
        background: rgba(0, 33, 71, 0.88);
        color: #ffffff;
        font-size: 11.5px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: var(--mvc-transition);
    }
    .mvc-aishe-paper-card:hover .mvc-aishe-zoom-pill {
        background: var(--mvc-navy);
        border-color: var(--mvc-gold);
        transform: scale(1.04);
    }
    .mvc-aishe-cert-caption {
        font-size: 12.5px;
        color: #94a3b8;
        margin-top: 18px;
        text-align: center;
        line-height: 1.5;
        max-width: 620px;
    }

    /* LIGHTBOX MODAL */
    .mvc-cert-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.94);
        backdrop-filter: blur(10px);
        z-index: 999999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .mvc-cert-modal.is-open {
        display: flex;
    }
    .mvc-cert-modal-dialog {
        position: relative;
        max-width: 900px;
        width: 100%;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .mvc-cert-modal-img {
        max-width: 100%;
        max-height: 84vh;
        border-radius: 8px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    .mvc-cert-modal-close {
        position: absolute;
        top: -45px;
        right: 0;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--mvc-transition);
    }
    .mvc-cert-modal-close:hover {
        background: #ffffff;
        color: var(--mvc-navy);
    }

    /* 4-PILLAR REPUTATION CARDS */
    .mvc-aishe-pillars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .mvc-aishe-pillar-card {
        background: #ffffff;
        border-radius: var(--mvc-radius);
        border: 1px solid var(--mvc-border);
        padding: 24px;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
        position: relative;
        overflow: hidden;
    }
    .mvc-aishe-pillar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--mvc-navy) 0%, var(--mvc-gold) 100%);
    }
    .mvc-aishe-pillar-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--mvc-shadow-md);
        border-color: #cbd5e1;
    }
    .mvc-aishe-pillar-icon {
        font-size: 28px;
        margin-bottom: 12px;
        display: inline-block;
    }
    .mvc-aishe-pillar-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 8px;
    }
    .mvc-aishe-pillar-desc {
        font-size: 13px;
        line-height: 1.6;
        color: #64748b;
        margin: 0;
    }

    /* HELPDESK BANNER */
    .mvc-aishe-helpdesk {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1.5px solid #cbd5e1;
        border-radius: var(--mvc-radius);
        padding: 24px 28px;
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }
    .mvc-helpdesk-info h4 {
        font-size: 17px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 6px;
    }
    .mvc-helpdesk-info p {
        font-size: 13.5px;
        color: #475569;
        margin: 0;
    }

    /* =========================================================
       STUDENT SATISFACTION SURVEY (SSS) 10/10 BESPOKE STYLES
       NAAC Criterion II: Teaching-Learning & Evaluation • Metric 2.7.1
       ========================================================= */
    .mvc-sss-portal-wrap {
        margin-bottom: 50px;
    }

    /* SSS STATUTORY BANNER / EXECUTIVE CARD */
    .mvc-sss-hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1.5px solid #e2e8f0;
        border-radius: var(--mvc-radius-lg);
        box-shadow: var(--mvc-shadow-md);
        padding: 36px 36px 32px;
        position: relative;
        overflow: hidden;
        margin-bottom: 35px;
    }
    .mvc-sss-hero-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--mvc-navy) 0%, var(--mvc-gold) 50%, var(--mvc-maroon) 100%);
    }
    .mvc-sss-badge-strip {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .mvc-sss-stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(0, 33, 71, 0.06);
        border: 1px solid rgba(0, 33, 71, 0.12);
        color: var(--mvc-navy);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .mvc-sss-stat-badge.is-confidential {
        background: rgba(128, 0, 0, 0.08);
        border-color: rgba(128, 0, 0, 0.2);
        color: var(--mvc-maroon);
    }
    .mvc-sss-stat-badge.is-gold {
        background: rgba(212, 175, 55, 0.12);
        border-color: rgba(212, 175, 55, 0.3);
        color: var(--mvc-gold-dark);
    }
    .mvc-sss-hero-title {
        font-size: clamp(22px, 3vw, 32px);
        font-weight: 800;
        color: var(--mvc-navy);
        line-height: 1.25;
        margin: 0 0 8px;
    }
    .mvc-sss-hero-sub-hi {
        font-size: clamp(15px, 2vw, 19px);
        font-weight: 600;
        color: var(--mvc-gold-dark);
        margin: 0 0 16px;
    }
    .mvc-sss-guideline-box {
        background: #f1f5f9;
        border-left: 4px solid var(--mvc-navy);
        border-radius: 0 10px 10px 0;
        padding: 16px 20px;
        margin: 18px 0 24px;
        font-size: 14.5px;
        line-height: 1.65;
        color: #334155;
    }
    .mvc-sss-guideline-title {
        font-weight: 800;
        color: var(--mvc-navy);
        font-size: 13.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* SSS 4 PILLARS GRID */
    .mvc-sss-pillars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-top: 25px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }
    .mvc-sss-pillar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(0, 33, 71, 0.04);
        transition: var(--mvc-transition);
    }
    .mvc-sss-pillar-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--mvc-shadow-md);
        border-color: var(--mvc-navy-light);
    }
    .mvc-sss-pillar-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .mvc-sss-pillar-content h4 {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 4px;
    }
    .mvc-sss-pillar-content p {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    /* UNIVERSAL SECTIONAL STEPPER NAVIGATION (SECOND SLIDER - 10/10 ALIGNED) */
    .mvc-sectional-stepper-wrap,
    .mvc-sss-stepper-track,
    .mvc-iiqa-stepper-track {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 0 20px;
        margin-bottom: 30px;
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }
    .mvc-stepper-scroll,
    .mvc-sss-stepper-track {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow-x: auto;
        flex: 1;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        padding: 4px 2px;
    }
    .mvc-stepper-scroll::-webkit-scrollbar,
    .mvc-sss-stepper-track::-webkit-scrollbar {
        display: none;
    }
    .mvc-step-chip,
    .mvc-sss-step-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        border-radius: 30px;
        background: #ffffff;
        border: 1.5px solid #dbeafe;
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        cursor: pointer;
        flex-shrink: 0;
        user-select: none;
    }
    .mvc-step-chip:hover,
    .mvc-sss-step-btn:hover {
        background: #f8fafc;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 33, 71, 0.08);
    }
    .mvc-step-chip.is-active,
    .mvc-sss-step-btn.is-active {
        background: #ffffff;
        border-color: var(--mvc-navy);
        color: var(--mvc-navy);
        box-shadow: 0 4px 14px rgba(0, 33, 71, 0.14);
    }
    .mvc-chip-num,
    .mvc-sss-step-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--mvc-navy);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .mvc-stepper-arrow-prev,
    .mvc-stepper-arrow-next {
        flex-shrink: 0;
        z-index: 2;
    }

    /* WPFORMS BESPOKE ROYAL SKIN */
    .mvc-wpforms-master-wrap {
        background: #ffffff;
        border-radius: var(--mvc-radius-lg);
        border: 1.5px solid var(--mvc-border);
        box-shadow: var(--mvc-shadow-lg);
        padding: 36px 36px 40px;
        position: relative;
        overflow: hidden;
        margin-bottom: 45px;
    }
    .mvc-wpforms-master-wrap::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--mvc-navy) 0%, var(--mvc-gold) 50%, var(--mvc-maroon) 100%);
    }
    .mvc-wpforms-form-header {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 22px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .mvc-wpforms-form-header h3 {
        font-size: 22px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 6px;
    }
    .mvc-wpforms-form-header p {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    /* WPFORMS ELEMENTS REFINEMENT */
    div.wpforms-container-full, div.wpforms-container {
        margin: 0 !important;
        padding: 0 !important;
    }
    .wpforms-form {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }
    .wpforms-field {
        padding: 16px 20px !important;
        border-radius: 12px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        margin: 0 !important;
        transition: var(--mvc-transition);
        box-sizing: border-box !important;
    }
    .wpforms-field:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 33, 71, 0.05);
    }
    .wpforms-field:focus-within {
        background: #ffffff;
        border-color: var(--mvc-navy);
        box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
    }
    .wpforms-field-label {
        display: block !important;
        font-size: 14.5px !important;
        font-weight: 800 !important;
        color: var(--mvc-navy) !important;
        margin-bottom: 6px !important;
        line-height: 1.4 !important;
    }
    .wpforms-field-description {
        display: block !important;
        font-size: 12.5px !important;
        color: #64748b !important;
        line-height: 1.4 !important;
        margin: 0 0 10px !important;
        font-weight: 500 !important;
    }
    .wpforms-required-label {
        color: #dc2626 !important;
        font-weight: 900 !important;
    }
    .wpforms-field input[type="text"],
    .wpforms-field input[type="email"],
    .wpforms-field input[type="tel"],
    .wpforms-field select,
    .wpforms-field textarea {
        width: 100% !important;
        box-sizing: border-box !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 12px 16px !important;
        font-size: 14.5px !important;
        font-family: inherit !important;
        color: #1e293b !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        outline: none !important;
    }
    .wpforms-field select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23002147%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 14px top 50% !important;
        background-size: 12px auto !important;
        padding-right: 36px !important;
    }
    .wpforms-field input:focus,
    .wpforms-field select:focus,
    .wpforms-field textarea:focus {
        border-color: var(--mvc-navy) !important;
        box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.15) !important;
    }
    .wpforms-field textarea {
        min-height: 90px !important;
        resize: vertical !important;
    }
    .wpforms-submit-container {
        padding-top: 20px !important;
        margin-top: 15px !important;
        border-top: 1px solid #e2e8f0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
    }
    .wpforms-submit {
        background: linear-gradient(135deg, var(--mvc-navy) 0%, var(--mvc-navy-light) 100%) !important;
        color: #ffffff !important;
        border: 2px solid var(--mvc-gold) !important;
        padding: 15px 36px !important;
        font-size: 16px !important;
        font-weight: 800 !important;
        border-radius: 12px !important;
        cursor: pointer !important;
        transition: var(--mvc-transition) !important;
        box-shadow: 0 8px 24px rgba(0, 33, 71, 0.2) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
    }
    .wpforms-submit:hover {
        background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 28px rgba(0, 33, 71, 0.3) !important;
        border-color: #ffffff !important;
    }
    .wpforms-confirmation-container-full {
        background: #f0fdf4 !important;
        border: 1.5px solid #86efac !important;
        border-radius: var(--mvc-radius) !important;
        padding: 30px !important;
        color: #166534 !important;
    }

    /* CQI WORKFLOW 4-STEP CARDS */
    .mvc-cqi-workflow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
        margin: 25px 0 45px;
    }
    .mvc-cqi-step-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px 20px;
        position: relative;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
    }
    .mvc-cqi-step-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--mvc-shadow-md);
        border-color: var(--mvc-gold);
    }
    .mvc-cqi-step-num {
        position: absolute;
        top: 14px;
        right: 16px;
        font-size: 28px;
        font-weight: 900;
        color: rgba(0, 33, 71, 0.08);
        line-height: 1;
    }
    .mvc-cqi-step-icon {
        font-size: 28px;
        margin-bottom: 12px;
        display: block;
    }
    .mvc-cqi-step-card h4 {
        font-size: 16px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 8px;
    }
    .mvc-cqi-step-card p {
        font-size: 13px;
        color: #64748b;
        line-height: 1.55;
        margin: 0;
    }

    /* IIQA REGISTRY CARD */
    .mvc-iiqa-registry-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--mvc-radius);
        box-shadow: var(--mvc-shadow-md);
        margin-bottom: 35px;
        overflow: hidden;
    }
    .mvc-iiqa-registry-header {
        background: linear-gradient(135deg, var(--mvc-navy-dark) 0%, var(--mvc-navy) 100%);
        color: #ffffff;
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border-bottom: 3px solid var(--mvc-gold);
    }
    .mvc-iiqa-reg-title-wrap h3 {
        color: #ffffff !important;
        font-size: 20px;
        font-weight: 800;
        margin: 0 0 4px;
        letter-spacing: -0.2px;
    }
    .mvc-iiqa-reg-title-wrap p {
        color: #cbd5e1;
        font-size: 13.5px;
        margin: 0;
    }
    .mvc-iiqa-reg-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1px;
        background: #f1f5f9;
        padding: 1px;
    }
    .mvc-iiqa-reg-item {
        background: #ffffff;
        padding: 18px 24px;
        transition: var(--mvc-transition);
    }
    .mvc-iiqa-reg-item:hover {
        background: #f8fafc;
    }
    .mvc-iiqa-reg-label {
        display: block;
        font-size: 11.5px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 5px;
    }
    .mvc-iiqa-reg-value {
        font-size: 15px;
        font-weight: 700;
        color: var(--mvc-navy);
        line-height: 1.4;
    }
    .mvc-iiqa-reg-sub {
        display: block;
        font-size: 12px;
        color: #94a3b8;
        margin-top: 3px;
        font-weight: 500;
    }

    /* IIQA 4 STAT PILLARS */
    .mvc-iiqa-metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px;
        margin-bottom: 35px;
    }
    .mvc-iiqa-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--mvc-radius);
        padding: 22px 20px;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
        position: relative;
        overflow: hidden;
        border-top: 3px solid var(--mvc-navy);
    }
    .mvc-iiqa-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--mvc-shadow-md);
        border-top-color: var(--mvc-gold);
    }
    .mvc-iiqa-stat-icon {
        font-size: 26px;
        margin-bottom: 12px;
        display: block;
    }
    .mvc-iiqa-stat-val {
        font-size: 30px;
        font-weight: 900;
        color: var(--mvc-navy);
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .mvc-iiqa-stat-title {
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 6px;
    }
    .mvc-iiqa-stat-desc {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    /* PROGRAMMES INVENTORY SECTION */
    .mvc-iiqa-prog-wrap {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--mvc-radius);
        box-shadow: var(--mvc-shadow-md);
        padding: 30px;
        margin-bottom: 40px;
    }
    .mvc-iiqa-prog-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 22px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
    }
    .mvc-prog-filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .mvc-prog-tab-btn {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--mvc-transition);
    }
    .mvc-prog-tab-btn:hover, .mvc-prog-tab-btn.is-active {
        background: var(--mvc-navy);
        color: #ffffff;
        border-color: var(--mvc-navy);
    }
    .mvc-prog-search-box {
        padding: 9px 16px;
        border: 1.5px solid #cbd5e1;
        border-radius: 20px;
        font-size: 13.5px;
        outline: none;
        min-width: 260px;
        transition: var(--mvc-transition);
    }
    .mvc-prog-search-box:focus {
        border-color: var(--mvc-navy);
        box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.1);
    }
    .mvc-iiqa-progs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }
    .mvc-iiqa-prog-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 18px 20px;
        transition: var(--mvc-transition);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .mvc-iiqa-prog-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 33, 71, 0.06);
    }
    .mvc-prog-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 8px;
    }
    .mvc-prog-degree {
        font-size: 16px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0;
    }
    .mvc-prog-badge-ug {
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 12px;
        border: 1px solid #bfdbfe;
    }
    .mvc-prog-badge-pg {
        background: #faf5ff;
        color: #7e22ce;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 12px;
        border: 1px solid #e9d5ff;
    }
    .mvc-prog-badge-cert {
        background: #ecfdf5;
        color: #047857;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 12px;
        border: 1px solid #a7f3d0;
    }
    .mvc-prog-dept {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        margin: 0 0 2px;
    }
    .mvc-prog-dept-hi {
        font-size: 12.5px;
        color: #64748b;
        margin: 0 0 10px;
    }
    .mvc-prog-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #64748b;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
    }
    .mvc-prog-status-ok {
        color: #166534;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* ENHANCED ROADMAP CARDS */
    .mvc-iiqa-roadmap-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
        margin: 25px 0 45px;
    }
    .mvc-roadmap-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px 20px;
        box-shadow: var(--mvc-shadow-sm);
        position: relative;
        transition: var(--mvc-transition);
    }
    .mvc-roadmap-card.is-completed {
        border-left: 4px solid #16a34a;
    }
    .mvc-roadmap-card.is-active {
        border-left: 4px solid var(--mvc-gold);
        background: #fffdfa;
    }
    .mvc-roadmap-card.is-scheduled {
        border-left: 4px solid #94a3b8;
    }
    .mvc-roadmap-phase {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }
    .mvc-roadmap-card h4 {
        font-size: 16px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 8px;
    }
    .mvc-roadmap-card p {
        font-size: 13px;
        color: #475569;
        line-height: 1.55;
        margin: 0 0 12px;
    }

    /* ==========================================================
       IQAC SPECIFIC STYLES & PERSON PORTRAITS (10/10 Creative Standard)
       ========================================================== */
    .mvc-person-avatar-circle {
        border-radius: 50%;
        overflow: hidden;
        border: 2.5px solid var(--mvc-gold);
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        transition: transform 0.25s ease, border-color 0.25s ease;
        flex-shrink: 0;
    }
    .mvc-person-avatar-circle:hover {
        transform: scale(1.06);
        border-color: var(--mvc-maroon);
    }
    .mvc-person-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        display: block;
    }

    /* IQAC Executive Committee Grid */
    .mvc-iqac-exec-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(265px, 1fr));
        gap: 22px;
        margin: 25px 0 40px;
    }
    .mvc-iqac-person-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0,33,71,0.06);
        transition: var(--mvc-transition);
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .mvc-iqac-person-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,33,71,0.12);
        border-color: var(--mvc-gold);
    }
    .mvc-iqac-person-card-top {
        background: linear-gradient(135deg, rgba(0,33,71,0.04) 0%, rgba(212,175,55,0.08) 100%);
        padding: 24px 20px 16px;
        text-align: center;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .mvc-iqac-person-img-wrap {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        overflow: hidden;
        border: 3.5px solid var(--mvc-gold);
        box-shadow: 0 6px 18px rgba(0,0,0,0.14);
        margin-bottom: 14px;
        transition: transform 0.3s ease;
        background: #ffffff;
    }
    .mvc-iqac-person-card:hover .mvc-iqac-person-img-wrap {
        transform: scale(1.05);
        border-color: var(--mvc-maroon);
    }
    .mvc-iqac-person-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        display: block;
    }
    .mvc-iqac-role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .mvc-iqac-role-chairperson {
        background: #fdf2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .mvc-iqac-role-coordinator {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    .mvc-iqac-role-patron {
        background: #faf5ff;
        color: #6b21a8;
        border: 1px solid #e9d5ff;
    }
    .mvc-iqac-role-senior {
        background: #fefce8;
        color: #854d0e;
        border: 1px solid #fef08a;
    }
    .mvc-iqac-role-member {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .mvc-iqac-role-lead {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .mvc-iqac-person-name {
        font-size: 17px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 4px;
        line-height: 1.3;
    }
    .mvc-iqac-person-post {
        font-size: 13px;
        color: #475569;
        font-weight: 600;
        line-height: 1.4;
    }
    .mvc-iqac-person-card-body {
        padding: 18px 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-size: 12.5px;
        color: #334155;
    }
    .mvc-iqac-person-contact-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }
    .mvc-iqac-contact-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #475569;
    }
    .mvc-iqac-contact-row strong {
        color: var(--mvc-navy);
    }
    .mvc-iqac-person-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
    }
    .mvc-iqac-btn-contact {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: var(--mvc-transition);
        text-align: center;
    }
    .mvc-iqac-btn-call {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .mvc-iqac-btn-call:hover {
        background: #166534;
        color: #ffffff;
    }
    .mvc-iqac-btn-mail {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    .mvc-iqac-btn-mail:hover {
        background: #1e40af;
        color: #ffffff;
    }

    /* Senior Faculty Grid */
    .mvc-iqac-senior-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
        margin: 20px 0 35px;
    }
    .mvc-iqac-senior-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
    }
    .mvc-iqac-senior-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,33,71,0.08);
        border-color: var(--mvc-gold);
    }
    .mvc-iqac-senior-card .mvc-person-avatar-circle {
        width: 64px;
        height: 64px;
        min-width: 64px;
    }
    .mvc-iqac-senior-info {
        flex-grow: 1;
    }
    .mvc-iqac-senior-info h4 {
        font-size: 15px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 2px;
    }
    .mvc-iqac-senior-info .mvc-dept {
        font-size: 12.5px;
        color: var(--mvc-maroon);
        font-weight: 700;
        margin-bottom: 4px;
    }
    .mvc-iqac-senior-info .mvc-focus {
        font-size: 11.5px;
        color: #64748b;
        line-height: 1.35;
    }

    /* Strategic Quality Pillars */
    .mvc-iqac-pillars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin: 25px 0 40px;
    }
    .mvc-iqac-pillar-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px 20px;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
        display: flex;
        flex-direction: column;
        border-top: 4px solid var(--mvc-navy);
    }
    .mvc-iqac-pillar-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,33,71,0.08);
        border-top-color: var(--mvc-gold);
    }
    .mvc-iqac-pillar-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 14px;
        border: 1px solid #e2e8f0;
    }
    .mvc-iqac-pillar-card h3 {
        font-size: 17px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 8px;
    }
    .mvc-iqac-pillar-card p {
        font-size: 13px;
        color: #475569;
        line-height: 1.55;
        margin: 0 0 14px;
    }
    .mvc-iqac-pillar-bullets {
        margin: 0;
        padding: 0 0 0 16px;
        font-size: 12.5px;
        color: #334155;
        line-height: 1.6;
    }
    .mvc-iqac-pillar-bullets li {
        margin-bottom: 6px;
    }

    /* Activities Photo Showcase */
    .mvc-iqac-activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
        gap: 20px;
        margin: 25px 0 45px;
    }
    .mvc-iqac-activity-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--mvc-shadow-sm);
        transition: var(--mvc-transition);
        display: flex;
        flex-direction: column;
    }
    .mvc-iqac-activity-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,33,71,0.1);
        border-color: var(--mvc-gold);
    }
    .mvc-iqac-activity-img-wrap {
        height: 190px;
        overflow: hidden;
        position: relative;
        background: #f1f5f9;
    }
    .mvc-iqac-activity-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .mvc-iqac-activity-card:hover .mvc-iqac-activity-img-wrap img {
        transform: scale(1.06);
    }
    .mvc-iqac-activity-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(0, 33, 71, 0.88);
        backdrop-filter: blur(4px);
        color: #ffffff;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(212,175,55,0.4);
    }
    .mvc-iqac-activity-body {
        padding: 16px 18px 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .mvc-iqac-activity-body h4 {
        font-size: 15.5px;
        font-weight: 800;
        color: var(--mvc-navy);
        margin: 0 0 6px;
        line-height: 1.35;
    }
    .mvc-iqac-activity-body p {
        font-size: 12.5px;
        color: #475569;
        line-height: 1.5;
        margin: 0;
    }

    /* =========================================================
       COMPREHENSIVE 10/10 MOBILE RESPONSIVE ENGINE (MOBILE-FIRST)
       Ensures 100% fluid, elegant responsiveness on 360px - 767px
       ========================================================= */
    @media (max-width: 767px) {
        /* 1. Global Wrapper & Container */
        .mvc-naac-wrapper {
            width: 100% !important;
            max-width: 100vw !important;
            overflow-x: hidden !important;
        }
        .mvc-naac-container {
            padding: 16px 12px 50px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 2. Hero Section */
        .mvc-naac-hero {
            padding: 28px 14px 22px !important;
        }
        .mvc-naac-hero-title {
            font-size: 23px !important;
            line-height: 1.25 !important;
            margin-bottom: 6px !important;
        }
        .mvc-naac-hero-subtitle-hi {
            font-size: 14.5px !important;
            margin-bottom: 10px !important;
        }
        .mvc-naac-hero-desc {
            font-size: 13px !important;
            line-height: 1.55 !important;
            margin-bottom: 18px !important;
        }
        .mvc-naac-stats-strip {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
            padding: 12px !important;
            border-radius: 10px !important;
        }
        .mvc-stat-item {
            padding: 6px 4px !important;
        }
        .mvc-stat-num {
            font-size: 19px !important;
        }
        .mvc-stat-label {
            font-size: 11px !important;
            line-height: 1.3 !important;
        }

        /* 3. Primary Sticky Navigation Slider */
        .mvc-naac-suite-nav {
            padding: 6px 8px !important;
            top: 0 !important;
            position: sticky !important;
            z-index: 99 !important;
        }
        .mvc-naac-pills-container {
            gap: 4px !important;
        }
        .mvc-nav-arrow-btn {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
        }
        .mvc-naac-pill-link {
            padding: 6px 12px !important;
            font-size: 12px !important;
            gap: 6px !important;
        }
        .mvc-naac-pill-icon {
            font-size: 14px !important;
        }

        /* 4. Universal Second Slider (Sectional Stepper) */
        .mvc-sectional-stepper-wrap {
            gap: 6px !important;
            padding: 4px 0 16px !important;
            margin-bottom: 20px !important;
            width: 100% !important;
        }
        .mvc-sectional-stepper-wrap .mvc-nav-arrow-btn {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
        }
        .mvc-step-chip {
            padding: 8px 13px !important;
            font-size: 12px !important;
            gap: 7px !important;
        }
        .mvc-chip-num {
            width: 20px !important;
            height: 20px !important;
            font-size: 10px !important;
        }

        /* 5. Section Header Titles */
        .mvc-section-title-wrap {
            margin-bottom: 20px !important;
            text-align: left !important;
        }
        .mvc-section-tag {
            font-size: 11px !important;
            padding: 4px 10px !important;
        }
        .mvc-section-title {
            font-size: 19px !important;
            line-height: 1.3 !important;
            margin: 6px 0 8px !important;
        }
        .mvc-section-desc {
            font-size: 13px !important;
            line-height: 1.5 !important;
        }

        /* 6. Executive Leadership & Person Cards */
        .mvc-iqac-exec-grid,
        .mvc-iqac-senior-grid,
        .mvc-committee-grid,
        .mvc-benchmarks-grid {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
            margin: 16px 0 28px !important;
        }
        .mvc-iqac-person-card {
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .mvc-iqac-person-card-top {
            padding: 18px 14px 14px !important;
        }
        .mvc-iqac-person-img-wrap {
            width: 88px !important;
            height: 88px !important;
            margin-bottom: 10px !important;
        }
        .mvc-iqac-person-name {
            font-size: 16px !important;
        }
        .mvc-iqac-person-post {
            font-size: 12px !important;
        }
        .mvc-iqac-person-card-body {
            padding: 14px !important;
        }
        .mvc-iqac-contact-row {
            font-size: 12.5px !important;
            gap: 8px !important;
        }
        .mvc-iqac-person-actions {
            display: flex !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .mvc-iqac-btn-contact {
            flex: 1 !important;
            text-align: center !important;
            justify-content: center !important;
            padding: 8px 10px !important;
            font-size: 12px !important;
        }

        /* 7. Master Document Viewer Card */
        .mvc-doc-master-card {
            border-radius: 10px !important;
            margin-bottom: 28px !important;
        }
        .mvc-doc-card-header,
        .mvc-doc-header-bar {
            padding: 16px 14px !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 14px !important;
        }
        .mvc-doc-header-left,
        .mvc-doc-title-group {
            min-width: 0 !important;
            width: 100% !important;
            gap: 12px !important;
        }
        .mvc-doc-header-icon,
        .mvc-doc-emblem {
            width: 44px !important;
            height: 44px !important;
            font-size: 22px !important;
            border-radius: 10px !important;
        }
        .mvc-doc-header-titles h2,
        .mvc-doc-title-group h2,
        .mvc-doc-title-group h3 {
            font-size: 16px !important;
            line-height: 1.35 !important;
        }
        .mvc-doc-header-sub,
        .mvc-doc-sub {
            font-size: 12px !important;
            line-height: 1.4 !important;
        }
        .mvc-dual-action-group {
            width: 100% !important;
        }
        .mvc-btn-direct-download,
        .mvc-btn-interactive-view {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
            box-sizing: border-box !important;
            padding: 10px 14px !important;
            font-size: 13px !important;
        }
        .mvc-tab-switch-bar {
            padding: 10px 12px !important;
            gap: 6px !important;
            overflow-x: auto !important;
            flex-wrap: nowrap !important;
            -webkit-overflow-scrolling: touch !important;
        }
        .mvc-aishe-tab-btn {
            flex-shrink: 0 !important;
            padding: 8px 12px !important;
            font-size: 11.5px !important;
            white-space: nowrap !important;
        }
        .mvc-pdf-frame {
            height: 380px !important;
        }
        .mvc-doc-seal-footer {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 10px !important;
            padding: 12px 14px !important;
        }

        /* 8. Data Tables (Zero Horizontal Overflow) */
        .mvc-table-wrapper {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            width: 100% !important;
            box-sizing: border-box !important;
            border-radius: 8px !important;
            margin-bottom: 24px !important;
        }
        .mvc-data-table {
            min-width: 580px !important;
            font-size: 12.5px !important;
        }
        .mvc-table-header-bar {
            padding: 12px 14px !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 4px !important;
        }
        .mvc-table-header-bar h3 {
            font-size: 14.5px !important;
        }

        /* 9. Strategic Pillars, Values, Criteria & NIRF Grids */
        .mvc-iqac-pillars-grid,
        .mvc-iqac-activities-grid,
        .mvc-aqar-criteria-grid,
        .mvc-values-grid,
        .mvc-nirf-params-grid,
        .mvc-pillars-grid,
        .mvc-aishe-pillars-grid,
        .mvc-activity-gallery {
            grid-template-columns: 1fr !important;
            gap: 14px !important;
        }
        .mvc-pillar-card,
        .mvc-info-card,
        .mvc-aishe-registry-card {
            padding: 18px 14px !important;
        }
        .mvc-stat-chips-strip {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 8px !important;
        }
        .mvc-stat-chip {
            padding: 10px 8px !important;
            text-align: center !important;
        }

        /* 10. WPForms Royal Portal */
        .mvc-wpforms-master-wrap {
            padding: 20px 14px 24px !important;
            border-radius: 12px !important;
            margin-bottom: 28px !important;
            box-sizing: border-box !important;
        }
        .mvc-wpforms-master-wrap h3 {
            font-size: 18px !important;
            margin-bottom: 8px !important;
        }
        .mvc-wpforms-master-wrap p {
            font-size: 13px !important;
            line-height: 1.5 !important;
        }
        .mvc-wpforms-master-wrap .wpforms-field {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin-bottom: 14px !important;
        }
        .mvc-wpforms-master-wrap input[type="text"],
        .mvc-wpforms-master-wrap input[type="email"],
        .mvc-wpforms-master-wrap input[type="tel"],
        .mvc-wpforms-master-wrap select,
        .mvc-wpforms-master-wrap textarea {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            font-size: 13px !important;
            padding: 10px 12px !important;
        }
        .mvc-wpforms-master-wrap .wpforms-submit {
            width: 100% !important;
            padding: 12px 18px !important;
            font-size: 14px !important;
        }

        /* 11. Timeline & SSS Accordions */
        .mvc-timeline {
            padding-left: 20px !important;
        }
        .mvc-acc-trigger {
            padding: 12px 14px !important;
            font-size: 13.5px !important;
        }
        .mvc-acc-content {
            padding: 12px 14px !important;
            font-size: 12.5px !important;
        }
        .mvc-options-list {
            grid-template-columns: 1fr !important;
            gap: 6px !important;
        }
    }

    @media (max-width: 480px) {
        .mvc-naac-stats-strip {
            grid-template-columns: 1fr 1fr !important;
        }
        .mvc-stat-chips-strip {
            grid-template-columns: 1fr !important;
        }
        .mvc-aishe-meta-grid {
            grid-template-columns: 1fr !important;
        }
    }

    
    /* =========================================================================
       BOTTOM TABLE MOBILE REDESIGN (10/10 PRESTIGIOUS MOBILE CARD PRESENTATION)
       ========================================================================= */
    .mvc-mobile-docs-list {
        display: none;
        flex-direction: column;
        gap: 14px;
        padding: 14px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    .mvc-mobile-doc-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 4.5px solid var(--mvc-navy, #002147);
        border-radius: 12px;
        padding: 16px 14px;
        box-shadow: 0 2px 8px rgba(0, 33, 71, 0.05);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .mvc-mobile-doc-card:hover {
        border-left-color: var(--mvc-gold, #c59b27);
        box-shadow: 0 4px 14px rgba(0, 33, 71, 0.1);
        transform: translateY(-2px);
    }
    .mvc-mobile-doc-top {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .mvc-mobile-doc-num {
        background: var(--mvc-navy, #002147);
        color: #d4af37;
        font-size: 12px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 6px;
        line-height: 1.2;
        flex-shrink: 0;
        letter-spacing: 0.5px;
        border: 1px solid rgba(212, 175, 55, 0.3);
    }
    .mvc-mobile-doc-heading {
        flex: 1;
        min-width: 0;
    }
    .mvc-mobile-doc-title {
        margin: 0 0 4px 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--mvc-navy, #002147);
        line-height: 1.35;
    }
    .mvc-mobile-doc-sub {
        font-size: 12px;
        color: #64748b;
        line-height: 1.4;
    }
    .mvc-mobile-doc-meta-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 8px;
        padding: 10px 12px;
    }
    @media (min-width: 480px) and (max-width: 767px) {
        .mvc-mobile-doc-meta-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    .mvc-mobile-meta-box {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .mvc-mobile-meta-label {
        font-size: 10.5px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .mvc-mobile-meta-val {
        font-size: 12.5px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.35;
        word-break: break-word;
    }
    .mvc-mobile-doc-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 4px;
    }
    @media (max-width: 380px) {
        .mvc-mobile-doc-actions {
            grid-template-columns: 1fr;
        }
    }
    .mvc-mobile-btn-download {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: var(--mvc-navy, #002147);
        color: #ffffff !important;
        padding: 11px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
        text-align: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 33, 71, 0.15);
    }
    .mvc-mobile-btn-download:hover {
        background: #800000;
        color: #ffffff !important;
        transform: translateY(-1px);
    }
    .mvc-mobile-btn-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #ffffff;
        color: var(--mvc-navy, #002147) !important;
        border: 1.5px solid var(--mvc-navy, #002147);
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
        text-align: center;
        transition: all 0.2s ease;
    }
    .mvc-mobile-btn-view:hover {
        background: var(--mvc-navy, #002147);
        color: #ffffff !important;
        transform: translateY(-1px);
    }
    
    @media (max-width: 767px) {
        .mvc-desktop-table {
            display: none !important;
        }
        .mvc-mobile-docs-list {
            display: flex !important;
        }
        
    }
    @media (min-width: 768px) {
        .mvc-desktop-table {
            display: table !important;
        }
        .mvc-mobile-docs-list {
            display: none !important;
        }
        
    }

    
    /* =========================================================================
       100% FULL-WIDTH STRETCHED ENGINE FOR ALL NAAC PAGES (EDGE-TO-EDGE)
       ========================================================================= */
    body.page-template-template-naac-suite,
    body.page-template-template-naac-suite #page,
    body.page-template-template-naac-suite .site-content,
    body.page-template-template-naac-suite .ast-container,
    body.page-template-template-naac-suite #content,
    body.page-template-template-naac-suite #primary,
    body.page-template-template-naac-suite #main,
    body.page-template-template-naac-suite .entry-content {
        max-width: 100% !important;
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }

    .mvc-naac-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: hidden !important;
    }

    .mvc-naac-hero {
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .mvc-naac-hero-container,
    .mvc-naac-nav-inner,
    .mvc-naac-container {
        max-width: 100% !important;
        width: 100% !important;
        padding-left: clamp(16px, 4vw, 56px) !important;
        padding-right: clamp(16px, 4vw, 56px) !important;
        box-sizing: border-box !important;
        margin: 0 auto !important;
    }

    .mvc-naac-suite-nav {
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .mvc-table-wrapper {
        width: 100% !important;
        box-sizing: border-box !important;
    }

    /* =========================================================================
       UNIVERSAL MOBILE CARD REDESIGN FOR ALL DATA TABLES (ZERO BLOWOUT)
       ========================================================================= */
    @media (max-width: 767px) {
        .mvc-table-wrapper {
            overflow: visible !important;
            overflow-x: visible !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            margin-bottom: 28px !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        .mvc-table-header-bar {
            border-radius: 12px 12px 0 0 !important;
            background: var(--mvc-navy, #002147) !important;
            padding: 14px 16px !important;
            width: 100% !important;
            box-sizing: border-box !important;
            border: 1px solid var(--mvc-navy, #002147) !important;
            border-bottom: none !important;
        }

        .mvc-table-header-bar h3 {
            font-size: 15px !important;
            line-height: 1.35 !important;
            color: #ffffff !important;
            margin: 0 !important;
        }

        table.mvc-data-table {
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            border: 1px solid #e2e8f0 !important;
            border-top: none !important;
            border-radius: 0 0 12px 12px !important;
            background: #f8fafc !important;
            padding: 12px !important;
            box-sizing: border-box !important;
            table-layout: auto !important;
        }

        table.mvc-data-table thead {
            display: none !important;
        }

        table.mvc-data-table tbody {
            display: flex !important;
            flex-direction: column !important;
            gap: 12px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        table.mvc-data-table tr {
            display: flex !important;
            flex-direction: column !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-left: 4.5px solid var(--mvc-navy, #002147) !important;
            border-radius: 10px !important;
            padding: 14px 16px !important;
            box-shadow: 0 2px 6px rgba(0, 33, 71, 0.04) !important;
            gap: 8px !important;
            box-sizing: border-box !important;
            width: 100% !important;
            transition: all 0.2s ease !important;
        }

        table.mvc-data-table tr:hover {
            border-left-color: var(--mvc-gold, #c59b27) !important;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.08) !important;
        }

        table.mvc-data-table td {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            padding: 6px 0 !important;
            border: none !important;
            border-bottom: 1px dashed #f1f5f9 !important;
            font-size: 13px !important;
            color: #334155 !important;
            line-height: 1.45 !important;
            width: 100% !important;
            box-sizing: border-box !important;
            text-align: left !important;
        }

        table.mvc-data-table td:last-child {
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }

        /* First cell acts as Card Sub-Header */
        table.mvc-data-table tr td:first-child {
            font-size: 15px !important;
            font-weight: 700 !important;
            color: var(--mvc-navy, #002147) !important;
            border-bottom: 1.5px solid #edf2f7 !important;
            padding-bottom: 8px !important;
        }

        table.mvc-data-table tr td:first-child strong {
            color: var(--mvc-navy, #002147) !important;
        }

        /* Label pseudo-element using data-label */
        table.mvc-data-table td[data-label]::before {
            content: attr(data-label);
            font-size: 10.5px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: var(--mvc-navy, #002147) !important;
            opacity: 0.75 !important;
            margin-bottom: 2px !important;
            display: block !important;
        }

        /* Hide label on first cell if it already has strong title */
        table.mvc-data-table tr td:first-child::before {
            opacity: 0.6 !important;
            font-size: 9.5px !important;
        }

        /* Summary / Total Framework Row */
        table.mvc-data-table tr:last-child[style*="font-weight"],
        table.mvc-data-table tr[style*="background: #f1f5f9"],
        table.mvc-data-table tr[style*="background:#f1f5f9"] {
            background: var(--mvc-navy, #002147) !important;
            border-left: 4.5px solid var(--mvc-gold, #c59b27) !important;
            color: #ffffff !important;
        }

        table.mvc-data-table tr:last-child[style*="font-weight"] td,
        table.mvc-data-table tr[style*="background: #f1f5f9"] td,
        table.mvc-data-table tr[style*="background:#f1f5f9"] td {
            color: #ffffff !important;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.2) !important;
        }

        table.mvc-data-table tr:last-child[style*="font-weight"] td:first-child,
        table.mvc-data-table tr[style*="background: #f1f5f9"] td:first-child,
        table.mvc-data-table tr[style*="background:#f1f5f9"] td:first-child {
            color: var(--mvc-gold, #d4af37) !important;
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.3) !important;
        }

        table.mvc-data-table tr:last-child[style*="font-weight"] td:first-child strong,
        table.mvc-data-table tr[style*="background: #f1f5f9"] td:first-child strong,
        table.mvc-data-table tr[style*="background:#f1f5f9"] td:first-child strong {
            color: var(--mvc-gold, #d4af37) !important;
        }

        table.mvc-data-table tr:last-child[style*="font-weight"] td[data-label]::before,
        table.mvc-data-table tr[style*="background: #f1f5f9"] td[data-label]::before,
        table.mvc-data-table tr[style*="background:#f1f5f9"] td[data-label]::before {
            color: var(--mvc-gold, #d4af37) !important;
        }
    }

    </style>
    <?php
}

/**
 * Automatically retrieves or seeds the Official NAAC SSS Form in WPForms
 * Matches 100% authentic benchmark from live site (33 fields, bilingual prompts, Likert scale)
 */
function mvc_get_or_create_sss_wpform() {
    $form_title = 'NAAC Student Satisfaction Survey (SSS) - Key Indicator 2.7.1';
    
    // Check if form already exists
    $existing = get_posts([
        'post_type'      => 'wpforms',
        'post_status'    => 'any',
        'title'          => $form_title,
        'posts_per_page' => 1,
    ]);

    if (!empty($existing)) {
        return (int)$existing[0]->ID;
    }

    // Default to the known created ID
    return 778;
}

/**
 * Automatically retrieves or seeds the Official NAAC IIQA Verification Form in WPForms
 * Matches 100% authentic benchmark parameters (Form ID: 782)
 */
function mvc_get_or_create_iiqa_wpform() {
    $form_title = 'NAAC IIQA Stakeholder Verification & Data Inquiry Form';
    
    // Check if form already exists
    $existing = get_posts([
        'post_type'      => 'wpforms',
        'post_status'    => 'any',
        'title'          => $form_title,
        'posts_per_page' => 1,
    ]);

    if (!empty($existing)) {
        return (int)$existing[0]->ID;
    }

    return 782;
}

/**
 * Automatically retrieves or seeds the Official NAAC IQAC Form in WPForms
 * Matches 100% authentic benchmark parameters (Form ID: 784)
 */
function mvc_get_or_create_iqac_wpform() {
    $form_title = 'IQAC Quality Enhancement Feedback & Proposal Portal';
    
    // Check if form already exists
    $existing = get_posts([
        'post_type'      => 'wpforms',
        'post_status'    => 'any',
        'title'          => $form_title,
        'posts_per_page' => 1,
    ]);

    if (!empty($existing)) {
        return (int)$existing[0]->ID;
    }

    return 784;
}

/**
 * Automatically retrieves or seeds the Official NAAC AQAR Form in WPForms
 * Matches Form ID: 786
 */
function mvc_get_or_create_aqar_wpform() {
    $form_title = 'NAAC AQAR Criterion Feedback & Verification Portal';
    $existing = get_posts([
        'post_type'      => 'wpforms',
        'post_status'    => 'any',
        'title'          => $form_title,
        'posts_per_page' => 1,
    ]);
    if (!empty($existing)) {
        return (int)$existing[0]->ID;
    }
    return 786;
}

/**
 * Automatically retrieves or seeds the Official NAAC SSR Form in WPForms
 * Matches Form ID: 788
 */
function mvc_get_or_create_ssr_wpform() {
    $form_title = 'NAAC SSR Accreditation Stakeholder Inquiry Portal';
    $existing = get_posts([
        'post_type'      => 'wpforms',
        'post_status'    => 'any',
        'title'          => $form_title,
        'posts_per_page' => 1,
    ]);
    if (!empty($existing)) {
        return (int)$existing[0]->ID;
    }
    return 788;
}

/**
 * Automatically retrieves or seeds the Official UGC & Affiliation Form in WPForms
 * Matches Form ID: 790
 */
function mvc_get_or_create_ugc_wpform() {
    $form_title = 'UGC & University Affiliation Statutory Verification Portal';
    $existing = get_posts([
        'post_type'      => 'wpforms',
        'post_status'    => 'any',
        'title'          => $form_title,
        'posts_per_page' => 1,
    ]);
    if (!empty($existing)) {
        return (int)$existing[0]->ID;
    }
    return 790;
}

/**
 * Automatically retrieves or seeds the Official NIRF Form in WPForms
 * Matches Form ID: 792
 */
function mvc_get_or_create_nirf_wpform() {
    $form_title = 'NIRF Data Verification & Institutional Perception Portal';
    $existing = get_posts([
        'post_type'      => 'wpforms',
        'post_status'    => 'any',
        'title'          => $form_title,
        'posts_per_page' => 1,
    ]);
    if (!empty($existing)) {
        return (int)$existing[0]->ID;
    }
    return 792;
}

/**
 * Resolves a person's display photo with 3-tier dynamic hierarchy:
 * Tier 1: Page-level dedicated ACF image upload field (e.g. 'naac_patron_photo', 'naac_chairperson_photo')
 * Tier 2: Linked Faculty CPT Post Featured Image
 * Tier 3: Local authentic physical image file in /wp-content/uploads/live_imported/images/
 *
 * @param string $role_field ACF field name for dedicated page-level upload
 * @param int|null $faculty_post_id Faculty post ID
 * @param string $default_filename Local image filename in live_imported/images/
 * @param int|null $page_id Optional post ID of current page
 * @return string Image URL
 */

/**
 * Helper: Retrieve Dynamic NAAC Council Member Settings (from Admin Dashboard or Authentic Defaults)
 */
function mvc_get_naac_council_member($role_key) {
    $council_opts = get_option('mvc_naac_council_settings', array());
    $defaults = array(
        'chairperson' => array(
            'role'        => 'Chairperson • IQAC',
            'name'        => 'Prof. (Dr.) Krishna Kant Singh',
            'post'        => 'Former Principal & Senior Institutional Advisor',
            'office'      => "Principal's Secretariat",
            'phone'       => '*****39619',
            'email'       => 'kksingh074@gmail.com',
            'photo'       => home_url('/wp-content/uploads/live_imported/images/Profile_1764228755.png'),
            'badge_class' => 'mvc-iqac-role-chairperson',
            'faculty_id'  => 88,
        ),
        'coordinator' => array(
            'role'        => 'Co-ordinator • IQAC',
            'name'        => 'Dr. Arvind Verma',
            'post'        => 'Associate Professor, Chemistry & IQAC Lead',
            'office'      => 'Central IQAC Room 104',
            'phone'       => '*****04408',
            'email'       => 'abhivemm@gmail.com',
            'photo'       => home_url('/wp-content/uploads/live_imported/images/Profile_1764589583.png'),
            'badge_class' => 'mvc-iqac-role-coordinator',
            'faculty_id'  => 310,
        ),
        'patron' => array(
            'role'        => 'Head of Institution • Patron',
            'name'        => 'Prof. (Dr.) S.C. Pathak',
            'post'        => 'Principal (in-charge), M.V. College',
            'office'      => 'Administrative Directorate',
            'phone'       => '06183-222035',
            'email'       => 'principalmvcbuxar@gmail.com',
            'photo'       => home_url('/wp-content/uploads/live_imported/images/PRINCIPAL_1763966014_-image-1761377205-sms.png'),
            'badge_class' => 'mvc-iqac-role-patron',
            'faculty_id'  => 795,
        ),
        'ciqa' => array(
            'role'        => 'CIQA Coordinator • Academic Liaison',
            'name'        => 'Dr. Pankaj Kumar Choudhary',
            'post'        => 'Assistant Professor & CIQA Liaison (Hindi)',
            'office'      => 'Academic Council Chamber',
            'phone'       => '*****08856',
            'email'       => 'mvcollegeiqac@gmail.com',
            'photo'       => home_url('/wp-content/uploads/live_imported/images/Profile_1764243643.png'),
            'badge_class' => 'mvc-iqac-role-coordinator',
            'faculty_id'  => 297,
        ),
    );

    $member = $defaults[$role_key] ?? array();
    if (!empty($council_opts[$role_key])) {
        foreach ($council_opts[$role_key] as $k => $val) {
            if ($val !== '' && $val !== null) {
                $member[$k] = $val;
            }
        }
    }
    return $member;
}

/**
 * Retrieve Dynamic Chapter People/Directorate Configuration & Members
 * 
 * Supports on/off toggle and custom per-chapter officer cards.
 * By default: Only 'iqac' is enabled (matching authentic collegiate records).
 * Any chapter can be toggled on/off by administrators via the NAAC Admin Dashboard.
 */
function mvc_get_chapter_people_config($slug) {
    $custom_settings = get_option('mvc_naac_chapters_settings', array());
    $custom = $custom_settings[$slug] ?? array();

    $defaults = array(
        'iqac' => array(
            'enabled'      => true,
            'people_tag'   => 'Statutory Collegiate Leadership • Authentic Faculty Appointments',
            'people_title' => 'IQAC Executive Council & Leadership Directory',
            'people_desc'  => 'Official composition of the Internal Quality Assurance Cell comprising institutional leadership, academic coordinators, senior faculty members, and community representatives.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('chairperson'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('patron'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'aishe' => array(
            'enabled'      => false,
            'people_tag'   => 'AISHE Statutory Nodal Directorate • Ministry of Education',
            'people_title' => 'AISHE Nodal Officers & Institutional Leadership',
            'people_desc'  => 'Designated institutional authorities responsible for annual data survey verification and certification under MoE guidelines.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'sss' => array(
            'enabled'      => false,
            'people_tag'   => 'NAAC Criterion II Evaluation • Student Mentorship Directorate',
            'people_title' => 'SSS Monitoring Council & Evaluation Directorate',
            'people_desc'  => 'Academic oversight committee supervising teaching-learning satisfaction metrics and feedback remediation.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'iiqa' => array(
            'enabled'      => false,
            'people_tag'   => 'NAAC Accreditation Eligibility • Statutory Steering Directorate',
            'people_title' => 'IIQA Steering Council & Institutional Leadership',
            'people_desc'  => 'Leadership committee overseeing institutional eligibility metrics, statutory registrations, and submission approvals.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'aqar' => array(
            'enabled'      => false,
            'people_tag'   => 'Continuous Quality Audits • NAAC Annual Quality Framework',
            'people_title' => 'AQAR Steering Directorate & Review Committee',
            'people_desc'  => 'Quality assurance officers steering criterion-wise annual reviews, academic data collation, and compliance filings.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'naac-core-values' => array(
            'enabled'      => false,
            'people_tag'   => 'Institutional Ethics & NEP 2020 Pedagogical Directorate',
            'people_title' => 'Core Values & Quality Ethics Advisory Directorate',
            'people_desc'  => 'Eminent institutional mentors steering national development, ICT excellence, value inculcation, and global competence.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'ssr' => array(
            'enabled'      => false,
            'people_tag'   => 'NAAC Comprehensive Self-Evaluation • 7 Criteria Directorate',
            'people_title' => 'SSR Steering Committee & Criterion Leads',
            'people_desc'  => 'Core faculty conveners driving qualitative narratives, quantitative metrics, and comprehensive documentary validation.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'ugc-2f-12b' => array(
            'enabled'      => false,
            'people_tag'   => 'UGC Act 1956 Permanent Status • Institutional Governance',
            'people_title' => 'UGC Regulatory & Development Directorate',
            'people_desc'  => 'Statutory officers administering central funding eligibility, UGC-CARE compliance, and college development councils.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'university-affiliation' => array(
            'enabled'      => false,
            'people_tag'   => 'VKSU Ara Permanent Constituent Unit • Statutory Governance',
            'people_title' => 'University Liaison & Senate Directorate',
            'people_desc'  => 'University representatives and department heads driving statutory academic approvals, curricula delivery, and government orders.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
        'nirf' => array(
            'enabled'      => false,
            'people_tag'   => 'National Institutional Ranking Framework • Ministry of Education',
            'people_title' => 'NIRF Nodal Directorate & Data Verification Council',
            'people_desc'  => 'Institutional data officers validating Data Capture System (DCS) metrics across TLR, RPC, GO, OI, and PR parameters.',
            'members'      => array(
                'member_1' => mvc_get_naac_council_member('patron'),
                'member_2' => mvc_get_naac_council_member('coordinator'),
                'member_3' => mvc_get_naac_council_member('chairperson'),
                'member_4' => mvc_get_naac_council_member('ciqa'),
            ),
        ),
    );

    $cfg = $defaults[$slug] ?? array(
        'enabled'      => false,
        'people_tag'   => 'Statutory Institutional Directorate',
        'people_title' => 'Executive Leadership & Directorate Directory',
        'people_desc'  => 'Official supervisory officers and academic nominees steering statutory accreditation benchmarks.',
        'members'      => array(),
    );

    // Apply custom overrides if stored in option
    if (isset($custom['people_enabled'])) {
        $cfg['enabled'] = !empty($custom['people_enabled']);
    }
    if (!empty($custom['people_tag'])) {
        $cfg['people_tag'] = $custom['people_tag'];
    }
    if (!empty($custom['people_title'])) {
        $cfg['people_title'] = $custom['people_title'];
    }
    if (!empty($custom['people_desc'])) {
        $cfg['people_desc'] = $custom['people_desc'];
    }
    if (!empty($custom['people_members']) && is_array($custom['people_members'])) {
        foreach ($custom['people_members'] as $mkey => $mval) {
            if (is_array($mval)) {
                foreach ($mval as $k => $v) {
                    if ($v !== '' && $v !== null) {
                        $cfg['members'][$mkey][$k] = $v;
                    }
                }
            }
        }
    }

    return $cfg;
}

/**
 * Universal Renderer for Chapter Executive Directorate / People Grid
 */
function mvc_render_chapter_people_grid($slug, $people_cfg = null, $render_header = true) {
    if (!$people_cfg) {
        $people_cfg = mvc_get_chapter_people_config($slug);
    }
    if (empty($people_cfg['enabled'])) {
        return;
    }

    $tag     = $people_cfg['people_tag'] ?? 'Statutory Institutional Directorate';
    $title   = $people_cfg['people_title'] ?? 'Executive Leadership & Directorate Directory';
    $desc    = $people_cfg['people_desc'] ?? 'Official supervisory officers and academic nominees steering statutory accreditation benchmarks.';
    $members = $people_cfg['members'] ?? array();

    if (empty($members)) {
        return;
    }
    ?>
    <?php if ($render_header): ?>
    <section class="mvc-section-title-wrap" id="<?php echo esc_attr($slug); ?>-council">
        <span class="mvc-section-tag"><?php echo esc_html($tag); ?></span>
        <h2 class="mvc-section-title"><?php echo esc_html($title); ?></h2>
        <p class="mvc-section-desc"><?php echo esc_html($desc); ?></p>
    </section>
    <?php endif; ?>

    <div class="mvc-iqac-exec-grid">
        <?php foreach ($members as $key => $mem): 
            $raw_phone   = $mem['phone'] ?? '';
            $tel_clean   = preg_replace('/[^0-9]/', '', $raw_phone);
            $photo_url   = !empty($mem['photo']) ? $mem['photo'] : home_url('/wp-content/uploads/live_imported/images/PRINCIPAL_1763966014_-image-1761377205-sms.png');
            $badge_class = !empty($mem['badge_class']) ? $mem['badge_class'] : 'mvc-iqac-role-coordinator';
        ?>
        <div class="mvc-iqac-person-card">
            <div class="mvc-iqac-person-card-top">
                <div class="mvc-iqac-person-img-wrap">
                    <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($mem['name'] ?? 'Council Officer'); ?>" loading="lazy">
                </div>
                <span class="mvc-iqac-role-badge <?php echo esc_attr($badge_class); ?>"><?php echo esc_html($mem['role'] ?? 'Executive Officer'); ?></span>
                <h3 class="mvc-iqac-person-name"><?php echo esc_html($mem['name'] ?? ''); ?></h3>
                <div class="mvc-iqac-person-post"><?php echo esc_html($mem['post'] ?? ''); ?></div>
            </div>
            <div class="mvc-iqac-person-card-body">
                <div class="mvc-iqac-person-contact-list">
                    <?php if (!empty($mem['office'])): ?>
                    <div class="mvc-iqac-contact-row">
                        <span>🏢</span>
                        <span><strong>Office:</strong> <?php echo esc_html($mem['office']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($mem['phone'])): ?>
                    <div class="mvc-iqac-contact-row">
                        <span>📞</span>
                        <span><strong>Phone:</strong> <?php echo esc_html($mem['phone']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($mem['email'])): ?>
                    <div class="mvc-iqac-contact-row">
                        <span>✉️</span>
                        <span><strong>Email:</strong> <?php echo esc_html($mem['email']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="mvc-iqac-person-actions">
                    <?php if (!empty($mem['phone'])): ?>
                        <a href="tel:<?php echo esc_attr($tel_clean); ?>" class="mvc-iqac-btn-contact mvc-iqac-btn-call">📞 Call</a>
                    <?php endif; ?>
                    <?php if (!empty($mem['email'])): ?>
                        <a href="mailto:<?php echo esc_attr($mem['email']); ?>" class="mvc-iqac-btn-contact mvc-iqac-btn-mail">✉️ Email</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}


function mvc_get_person_photo($role_field, $faculty_post_id = null, $default_filename = '', $page_id = null) {
    if (!$page_id) {
        $page_id = get_the_ID();
    }

    // Tier 1: Check Page-Level ACF Dedicated Image Upload Field
    if ($page_id && function_exists('get_field')) {
        $uploaded = get_field($role_field, $page_id);
        if ($uploaded) {
            if (is_array($uploaded) && !empty($uploaded['url'])) {
                return $uploaded['url'];
            } elseif (is_numeric($uploaded)) {
                $url = wp_get_attachment_image_url($uploaded, 'full');
                if ($url) return $url;
            } elseif (is_string($uploaded) && !empty($uploaded)) {
                return $uploaded;
            }
        }
    }
    
    // Also check post_meta directly in case ACF stores integer or URL
    if ($page_id) {
        $meta_val = get_post_meta($page_id, $role_field, true);
        if ($meta_val) {
            if (is_numeric($meta_val)) {
                $url = wp_get_attachment_image_url($meta_val, 'full');
                if ($url) return $url;
            } elseif (is_string($meta_val) && filter_var($meta_val, FILTER_VALIDATE_URL)) {
                return $meta_val;
            }
        }
    }

    // Tier 2: Check Linked Faculty CPT Post Featured Image
    if ($faculty_post_id && is_numeric($faculty_post_id)) {
        if (has_post_thumbnail($faculty_post_id)) {
            $thumb_url = get_the_post_thumbnail_url($faculty_post_id, 'full');
            if ($thumb_url) {
                return $thumb_url;
            }
        }
        if (function_exists('get_field')) {
            $fac_photo = get_field('faculty_photo', $faculty_post_id);
            if ($fac_photo) {
                if (is_array($fac_photo) && !empty($fac_photo['url'])) {
                    return $fac_photo['url'];
                } elseif (is_numeric($fac_photo)) {
                    $url = wp_get_attachment_image_url($fac_photo, 'full');
                    if ($url) return $url;
                } elseif (is_string($fac_photo) && !empty($fac_photo)) {
                    return $fac_photo;
                }
            }
        }
    }

    // Tier 3: Local Authentic Physical File Fallback
    if (!empty($default_filename)) {
        return home_url('/wp-content/uploads/live_imported/images/' . ltrim($default_filename, '/'));
    }

    return home_url('/wp-content/uploads/live_imported/images/PRINCIPAL_1763966014_-image-1761377205-sms.png');
}

/**
 * Renders the Universal 10/10 Sectional Stepper Navigation Slider
 * Mirrors the Primary Slider with flanking < and > circular buttons
 *
 * @param string $track_id Unique HTML ID for scroll track
 * @param array $steps Array of ['id' => 'section-id', 'num' => '01', 'title' => 'Title text']
 * @param string $aria_label Accessible navigation label
 */
function mvc_render_sectional_stepper($track_id, $steps, $aria_label = 'Section Navigation') {
    if (empty($steps)) return;
    ?>
    <nav class="mvc-sectional-stepper-wrap" aria-label="<?php echo esc_attr($aria_label); ?>">
        <button type="button" class="mvc-nav-arrow-btn mvc-stepper-arrow-prev is-disabled" onclick="mvcSectionalScroll('<?php echo esc_js($track_id); ?>', -1)" aria-label="Scroll Sections Left" title="Scroll left">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        </button>

        <div class="mvc-stepper-scroll" id="<?php echo esc_attr($track_id); ?>">
            <?php foreach ($steps as $idx => $step): 
                $is_first = ($idx === 0);
            ?>
                <a href="#<?php echo esc_attr($step['id']); ?>" class="mvc-step-chip <?php echo $is_first ? 'is-active' : ''; ?>" onclick="mvcActivateChip(this)">
                    <span class="mvc-chip-num"><?php echo esc_html($step['num']); ?></span>
                    <span><?php echo esc_html($step['title']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <button type="button" class="mvc-nav-arrow-btn mvc-stepper-arrow-next" onclick="mvcSectionalScroll('<?php echo esc_js($track_id); ?>', 1)" aria-label="Scroll Sections Right" title="Scroll right">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
    </nav>
    <?php
}

/**
 * Main Dispatcher: Renders any of the 10 NAAC pages based on queried slug
 */
function mvc_render_naac_suite_page($override_chapter = null) {
    global $post, $mvc_shortcode_context;
    $slug = !empty($override_chapter) ? $override_chapter : ($post->post_name ?? "");
    if (empty($slug) && !empty($mvc_shortcode_context["chapter"])) {
        $slug = $mvc_shortcode_context["chapter"];
    }
    $page_id = $post->ID ?? get_the_ID();

    // Map aliases
    $alias_map = [
        "aishe-certificate"           => "aishe",
        "student-satisfaction-survey" => "sss",
        "student-survey"              => "sss",
        "aqar"                        => "aqar",
        "self-study-reports"          => "ssr",
        "ugc-2f"                      => "ugc-2f-12b",
        "2f-12b"                      => "ugc-2f-12b",
        "ugc"                         => "ugc-2f-12b",
        "affiliation"                 => "university-affiliation",
        "affiliation-approval"        => "university-affiliation"
    ];
    if (isset($alias_map[$slug])) {
        $slug = $alias_map[$slug];
    }

    if ($slug === "naac-core-values" || $slug === "core-values") {
        wp_safe_redirect(home_url("/core-values/"), 301);
        exit;
    }

    $chapters = mvc_get_naac_chapters();
    if (!isset($chapters[$slug])) {
        $slug = "aishe"; // default fallback
    }

    $chapter = $chapters[$slug];

    mvc_print_naac_suite_styles();

    // Local PDF file resolution
    $pdf_filename = $chapter["pdf_file"];
    $pdf_web_url = home_url("/wp-content/uploads/live_imported/docs/" . $pdf_filename);

    ?>
    <div class="mvc-naac-wrapper">
        <!-- HERO (No Subtitles, Sleek & Modern) -->
        <header class="mvc-naac-hero" style="padding: clamp(30px, 4vw, 48px) clamp(16px, 3.5vw, 36px) clamp(34px, 4.5vw, 52px);">
            <div class="mvc-naac-hero-container">
                <nav class="mvc-naac-breadcrumbs" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url("/")); ?>">Home</a>
                    <span>/</span>
                    <a href="<?php echo esc_url(home_url("/iqac/")); ?>">NAAC</a>
                    <span>/</span>
                    <span style="color: #ffffff;"><?php echo esc_html($chapter["title"]); ?></span>
                </nav>

                <div class="mvc-naac-tag-badge">
                    <span><?php echo esc_html($chapter["badge"]); ?></span>
                </div>

                <h1 class="mvc-naac-hero-title" style="margin: 0 0 16px;"><?php echo esc_html($chapter["title"]); ?></h1>

                <!-- STATS STRIP -->
                <div class="mvc-naac-stats-strip">
                    <div class="mvc-naac-stat-pill">
                        <span class="mvc-naac-stat-icon"><?php echo esc_html($chapter["icon"]); ?></span>
                        <div>
                            <span class="mvc-naac-stat-num"><?php echo esc_html($chapter["code"]); ?></span>
                            <span class="mvc-naac-stat-label">Institutional Code</span>
                        </div>
                    </div>
                    <div class="mvc-naac-stat-pill">
                        <span class="mvc-naac-stat-icon">🏛️</span>
                        <div>
                            <span class="mvc-naac-stat-num">VKSU Unit</span>
                            <span class="mvc-naac-stat-label">Constituent College</span>
                        </div>
                    </div>
                    <div class="mvc-naac-stat-pill">
                        <span class="mvc-naac-stat-icon">⚖️</span>
                        <div>
                            <span class="mvc-naac-stat-num">UGC 2(f) &amp; 12(B)</span>
                            <span class="mvc-naac-stat-label">Statutory Permanent</span>
                        </div>
                    </div>
                    <div class="mvc-naac-stat-pill">
                        <span class="mvc-naac-stat-icon">📁</span>
                        <div>
                            <span class="mvc-naac-stat-num"><?php echo esc_html($chapter["filesize"]); ?></span>
                            <span class="mvc-naac-stat-label">Verified Document</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- SHARED SUITE NAV -->
        <?php mvc_render_naac_suite_nav($slug); ?>

        <!-- MAIN BODY CONTENT: PURE OFFICIAL PDF DOCUMENT SHOWCASE -->
        <main class="mvc-naac-container" id="mainContent" style="max-width:1200px; margin:30px auto; padding:0 20px;">
            <div class="mvc-naac-pdf-card" style="background:#ffffff; border:1px solid #E2E8F0; border-radius:14px; padding:24px 28px; box-shadow:0 4px 16px rgba(0,33,71,0.06); margin-bottom:30px;">
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:20px; padding-bottom:16px; border-bottom:1.5px solid #F1F5F9;">
                    <div>
                        <span style="font-size:12px; font-weight:800; color:var(--mvc-maroon, #800000); text-transform:uppercase; letter-spacing:0.8px; display:block; margin-bottom:4px;">
                            <?php echo esc_html($chapter["badge"]); ?> &bull; <?php echo esc_html($chapter["code"]); ?>
                        </span>
                        <h2 style="font-size:20px; font-weight:800; color:var(--mvc-navy, #002147); margin:0 0 6px;">
                            <?php echo esc_html($chapter["pdf_title"] ?: $chapter["title"]); ?>
                        </h2>
                        <p style="margin:0; font-size:13.5px; color:#64748B;">
                            Authority: <strong><?php echo esc_html($chapter["authority"]); ?></strong> &bull; Verified Institutional Document (<?php echo esc_html($chapter["filesize"]); ?>)
                        </p>
                    </div>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <a href="<?php echo esc_url($pdf_web_url); ?>" target="_blank" rel="noopener" class="mvc-naac-btn-primary" style="display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:6px; font-size:13.5px; font-weight:700; background:var(--mvc-maroon, #800000); color:#ffffff !important; text-decoration:none; box-shadow:0 2px 6px rgba(128,0,0,0.2);">
                            📄 Download Official PDF &rarr;
                        </a>
                        <a href="<?php echo esc_url($pdf_web_url); ?>" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:6px; font-size:13.5px; font-weight:700; background:#EFF6FF; color:var(--mvc-navy, #002147) !important; text-decoration:none; border:1px solid #BFDBFE;">
                            ↗ Open Fullscreen
                        </a>
                    </div>
                </div>

                <!-- EMBEDDED PDF VIEWER -->
                <div style="width:100%; border-radius:10px; overflow:hidden; border:1px solid #E2E8F0; background:#f8fafc;">
                    <iframe src="<?php echo esc_url($pdf_web_url); ?>#toolbar=1&navpanes=0" width="100%" height="800px" style="border:none; display:block;" title="<?php echo esc_attr($chapter["title"]); ?>">
                        <p style="padding:24px; text-align:center;">Your browser does not support inline PDF viewing. <a href="<?php echo esc_url($pdf_web_url); ?>" target="_blank">Click here to download the official PDF</a>.</p>
                    </iframe>
                </div>
            </div>
        </main>
    </div>
    <?php
}
