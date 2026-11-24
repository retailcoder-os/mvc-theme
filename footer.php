<?php
/**
 * Grand Institutional Footer for Maharshi Vishwamitra Mahavidyalaya, Buxar
 * RetailCoder StoreFront [RC:StoreFront] Theme
 * Universal Directives: Royal Navy (#002147), Institutional Maroon (#800000), Metallic Gold (#D4AF37)
 * Powered by RetailCoder (https://retailcoder.com)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

astra_content_bottom();
?>
		</div> <!-- .ast-container -->
	</div> <!-- #content -->
<?php
astra_content_after();

// Fetch dynamic metadata from Post 209 or defaults
$raw_phone       = function_exists('get_field') && get_field('college_phone', 209) ? get_field('college_phone', 209) : '06183-222035';
if (preg_match('/(061\d{2}[-\s]?\d{5,6})/', $raw_phone, $m)) {
    $college_phone = $m[1];
} else {
    $college_phone = '06183-222035';
}
$college_email   = function_exists('get_field') && get_field('college_email', 209) ? get_field('college_email', 209) : 'mvcollegebuxar@gmail.com';
$college_address = function_exists('get_field') && get_field('college_address', 209) ? get_field('college_address', 209) : 'Charitravan, Buxar – 802101, Bihar (India)';
$college_logo    = function_exists('get_field') ? get_field('college_logo', 209) : false;
$logo_url        = (is_array($college_logo) && !empty($college_logo['url'])) ? $college_logo['url'] : '/wp-content/uploads/2026/10/mv-college-website-logo.png';

$fb_url = function_exists('get_field') && get_field('facebook_url', 209) ? get_field('facebook_url', 209) : 'https://www.facebook.com/mvcollegebuxar';
$tw_url = function_exists('get_field') && get_field('twitter_url', 209) ? get_field('twitter_url', 209) : 'https://twitter.com/mvcollegebuxar';
$ig_url = function_exists('get_field') && get_field('instagram_url', 209) ? get_field('instagram_url', 209) : 'https://www.instagram.com/mvcollegebuxar';
$yt_url = function_exists('get_field') && get_field('youtube_url', 209) ? get_field('youtube_url', 209) : 'https://www.youtube.com/channel/UChAoUFiLfqO61Q22MTWd0ag';
$li_url = function_exists('get_field') && get_field('linkedin_url', 209) ? get_field('linkedin_url', 209) : 'https://www.linkedin.com/school/mvcollegebuxar';

// Real visitor stats from tracking engine
$total_visitors     = (int) get_option('mvc_total_visitors', 166150);
$today_visitors     = (int) get_option('mvc_today_visitors', 1);
$yesterday_visitors = (int) get_option('mvc_yesterday_visitors', 0);
$active_transient   = get_transient('mvc_real_active_visitors');
$active_count       = (is_array($active_transient) && count($active_transient) > 0) ? count($active_transient) : 1;
?>

<footer class="mvc-grand-footer" id="mvcSiteFooter" itemscope itemtype="https://schema.org/CollegeOrUniversity">
    
    <!-- ========================================================
         MAIN GRAND 4-COLUMN FOOTER MATRIX
         ======================================================== -->
    <div class="mvc-footer-main">
        <div class="mvc-container">
            <div class="mvc-footer-grid">
                
                <!-- Col 1: Institutional Heritage, Seal & Vision -->
                <div class="mvc-footer-col mvc-col-brand">
                    <div class="mvc-footer-brand-header">
                        <div class="mvc-footer-seal-ring">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="Maharshi Vishwamitra Mahavidyalaya Official Crest" class="mvc-footer-seal-img" loading="lazy" itemprop="logo">
                        </div>
                        <div class="mvc-footer-titles">
                            <div class="mvc-title-lockup mvc-footer-lockup">
                                <h3 class="mvc-footer-inst-name" itemprop="name">MAHARSHI VISHWAMITRA MAHAVIDYALAYA</h3>
                                <div class="mvc-footer-inst-hindi">महर्षि विश्वामित्र महाविद्यालय, बक्सर</div>
                            </div>
                            <span class="mvc-footer-affil-sub">A Constituent Unit of Veer Kunwar Singh University, Ara &bull; Estd. 1958 &bull; AISHE: C-27158</span>
                        </div>
                    </div>

                    <p class="mvc-footer-heritage-text" itemprop="description">
                        Established in 1958 in the historic Siddhashram of Buxar, Maharshi Vishwamitra Mahavidyalaya is a premier constituent college fostering multidisciplinary academic distinction, research mentorship, and holistic student growth under NEP-2020.
                    </p>

                    <div class="mvc-footer-motto-badge">
                        <span class="mvc-motto-icon">📜</span>
                        <div class="mvc-motto-text">
                            <strong>विद्यया अमृतमश्नुते</strong>
                            <small>Through Knowledge One Attains Immortality</small>
                        </div>
                    </div>

                    <div class="mvc-footer-accred-tags">
                        <span class="mvc-accred-tag">🏛️ VKSU Constituent</span>
                        <span class="mvc-accred-tag">⭐ UGC 2(f) &amp; 12(B)</span>
                        <span class="mvc-accred-tag">📋 AISHE: C-27158</span>
                        <span class="mvc-accred-tag">📅 Estd. 1958</span>
                    </div>
                </div>

                <!-- Col 2: Academic Portals -->
                <div class="mvc-footer-col">
                    <h4 class="mvc-footer-col-title">
                        <span>Academic Portals</span>
                        <span class="mvc-title-bar"></span>
                    </h4>
                    <ul class="mvc-footer-nav-list">
                        <li class="mvc-footer-nav-item">
                            <a href="/admission-procedure/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Undergraduate &amp; PG Programs</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">NEP-2020</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/our-administration/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Faculty &amp; Staff Directories</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">Directory</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/library/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Central Digital Library &amp; OPAC</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">e-Library</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/academic-calendar/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Academic Calendar &amp; Schedules</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">2024–25</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/research/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Research, ONOS &amp; Publications</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">Journals</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/sanctioned-teachers/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Sanctioned Faculty Posts</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">UGC Matrix</span> -->
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Statutory Governance & Cells -->
                <div class="mvc-footer-col">
                    <h4 class="mvc-footer-col-title">
                        <span>Governance &amp; Cells</span>
                        <span class="mvc-title-bar"></span>
                    </h4>
                    <ul class="mvc-footer-nav-list">
                        <li class="mvc-footer-nav-item">
                            <a href="/iqac/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Internal Quality Assurance (IQAC)</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">NAAC</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/anti-ragging-cell/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Statutory Anti-Ragging Cell</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">24×7 Active</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/student-grievance/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Student Grievance Redressal</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">Online Portal</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/committees-cells/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Statutory Committees &amp; Cells</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">Committees</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/facility/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Campus Facilities &amp; Amenities</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">23+ Wings</span> -->
                            </a>
                        </li>
                        <li class="mvc-footer-nav-item">
                            <a href="/media/" class="mvc-footer-nav-link">
                                <span class="mvc-footer-nav-link-left">
                                    <span class="mvc-nav-arrow-wrap">
                                        <svg class="mvc-nav-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                    </span>
                                    <span class="mvc-nav-text">Press &amp; Media Coverage</span>
                                </span>
                                <!-- <span class="mvc-nav-badge">Press Archive</span> -->
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Campus Headquarters & Helplines -->
                <div class="mvc-footer-col mvc-col-contact">
                    <h4 class="mvc-footer-col-title">
                        <span>Campus Headquarters</span>
                        <span class="mvc-title-bar"></span>
                    </h4>
                    
                    <ul class="mvc-footer-contact-list">
                        <li>
                            <div class="mvc-contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            </div>
                            <div class="mvc-contact-text">
                                <span class="mvc-contact-label">Campus Address:</span>
                                <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <?php echo esc_html($college_address); ?>
                                </address>
                            </div>
                        </li>
                        <li>
                            <div class="mvc-contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            </div>
                            <div class="mvc-contact-text">
                                <span class="mvc-contact-label">Telephone / Helplines:</span>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $college_phone)); ?>" itemprop="telephone">
                                    <?php echo esc_html($college_phone); ?>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="mvc-contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            </div>
                            <div class="mvc-contact-text">
                                <span class="mvc-contact-label">Official Email:</span>
                                <a href="mailto:<?php echo esc_attr($college_email); ?>" itemprop="email"><?php echo esc_html($college_email); ?></a>
                            </div>
                        </li>
                        <li>
                            <div class="mvc-contact-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                            </div>
                            <div class="mvc-contact-text">
                                <span class="mvc-contact-label">Administrative Hours:</span>
                                <span>Mon – Sat: 10:00 AM – 05:00 PM</span>
                            </div>
                        </li>
                    </ul>

                    <div class="mvc-footer-action-buttons">
                        <a href="https://maps.google.com/?q=Maharshi+Vishwamitra+Mahavidyalaya+Buxar" target="_blank" rel="noopener" class="mvc-footer-action-btn mvc-maps-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            <span>Open in Google Maps &nearr;</span>
                        </a>
                        <a href="/help-desk/" class="mvc-footer-action-btn mvc-emergency-btn">
                            <span class="mvc-pulse-dot"></span>
                            <span>24×7 Help Desk &amp; Hotline</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ========================================================
         LIVE REAL-TIME FOOTFALL & DIGITAL PORTAL METRICS
         ======================================================== -->
    <div class="mvc-footer-analytics-bar">
        <div class="mvc-container">
            <div class="mvc-analytics-inner">
                <div class="mvc-analytics-header">
                    <h5 class="mvc-analytics-title">Live Footfall</h5>
                </div>
                <div class="mvc-analytics-grid">
                    <div class="mvc-metric-card">
                        <div class="mvc-metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        </div>
                        <div class="mvc-metric-data">
                            <div class="mvc-metric-val"><?php echo number_format($total_visitors); ?>+</div>
                            <div class="mvc-metric-label">Total Footfall</div>
                        </div>
                    </div>
                    <div class="mvc-metric-card">
                        <div class="mvc-metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
                        </div>
                        <div class="mvc-metric-data">
                            <div class="mvc-metric-val"><?php echo number_format($today_visitors); ?></div>
                            <div class="mvc-metric-label">Today's Campus Visits</div>
                        </div>
                    </div>
                    <div class="mvc-metric-card">
                        <div class="mvc-metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                        </div>
                        <div class="mvc-metric-data">
                            <div class="mvc-metric-val"><?php echo number_format($yesterday_visitors); ?></div>
                            <div class="mvc-metric-label">Yesterday's Recorded Access</div>
                        </div>
                    </div>
                    <div class="mvc-metric-card mvc-live-card">
                        <div class="mvc-metric-icon">
                            <span class="mvc-live-beacon"></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                        </div>
                        <div class="mvc-metric-data">
                            <div class="mvc-metric-val"><?php echo number_format($active_count); ?></div>
                            <div class="mvc-metric-label">Active Scholars Online Now</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================
         BOTTOM LEGAL, COPYRIGHT & SOCIAL BAR (1 LINE DESKTOP, BEAUTIFUL MOBILE)
         ======================================================== -->
    <div class="mvc-footer-bottom">
        <div class="mvc-container">
            <div class="mvc-bottom-flex">
                <div class="mvc-bottom-copyright">
                    <span class="mvc-inst-copy">&copy; <?php echo date('Y'); ?> <strong>Maharshi Vishwamitra Mahavidyalaya, Buxar</strong></span>
                    <span class="mvc-sep mvc-sep-credits" aria-hidden="true">&bull;</span>
                    <span class="mvc-dev-inline">Designed by <a href="https://retailcoder.com" target="_blank" rel="noopener" class="mvc-credit-link">RetailCoder</a></span>
                    <span class="mvc-sep" aria-hidden="true">&bull;</span>
                    <span class="mvc-dev-inline">Managed by <a href="https://swadeshihost.com" target="_blank" rel="noopener" class="mvc-credit-link">Swadeshi Store</a></span>
                </div>

                <div class="mvc-bottom-legal-links">
                    <a href="/vision-mission/">Vision &amp; Mission</a>
                    <span class="mvc-sep">&bull;</span>
                    <a href="/rules-regulations/">Rules</a>
                    <span class="mvc-sep">&bull;</span>
                    <a href="/code-of-conduct/">Code of Conduct</a>
                    <span class="mvc-sep">&bull;</span>
                    <a href="/student-grievance/">RTI Cell</a>
                    <span class="mvc-sep">&bull;</span>
                    <a href="/tender/">Tenders</a>
                </div>

                <div class="mvc-bottom-social-bar">
                    <div class="mvc-social-icons">
                        <a href="<?php echo esc_url($yt_url); ?>" target="_blank" rel="noopener" class="mvc-social-icon mvc-yt" aria-label="Official YouTube Channel" title="YouTube">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        <a href="<?php echo esc_url($fb_url); ?>" target="_blank" rel="noopener" class="mvc-social-icon mvc-fb" aria-label="Facebook Page" title="Facebook">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="<?php echo esc_url($tw_url); ?>" target="_blank" rel="noopener" class="mvc-social-icon mvc-tw" aria-label="X / Twitter Handle" title="X (Twitter)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="<?php echo esc_url($ig_url); ?>" target="_blank" rel="noopener" class="mvc-social-icon mvc-ig" aria-label="Instagram Profile" title="Instagram">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="<?php echo esc_url($li_url); ?>" target="_blank" rel="noopener" class="mvc-social-icon mvc-li" aria-label="LinkedIn Institution" title="LinkedIn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</footer>

<!-- Self-Contained 10/10 Footer Design System Styles -->
<style id="mvcGrandFooterStyles">
/* ========================================================
   GRAND INSTITUTIONAL FOOTER STYLES (10/10 STANDARD)
   ======================================================== */
.mvc-container {
    width: 100%;
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 20px;
    box-sizing: border-box;
}

.mvc-grand-footer {
    background: #001226;
    color: #CBD5E1;
    position: relative;
    font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", "Outfit", "Segoe UI", Roboto, sans-serif;
    border-top: 4px solid #800000;
    box-shadow: 0 -10px 40px rgba(0, 18, 38, 0.4);
    line-height: 1.6;
}

/* Main Footer Body */
.mvc-footer-main {
    padding: 60px 0 45px;
    background: radial-gradient(circle at 10% 20%, rgba(128, 0, 0, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(0, 33, 71, 0.3) 0%, transparent 45%),
                #00142B;
}
.mvc-footer-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr 1fr 1.15fr;
    gap: 36px;
    align-items: start;
}

/* Col 1: Brand & Heritage */
.mvc-footer-brand-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 18px;
}
.mvc-footer-seal-ring {
    width: 66px;
    height: 66px;
    border-radius: 50%;
    background: #ffffff;
    border: 2.5px solid #D4AF37;
    box-shadow: 0 4px 16px rgba(212, 175, 55, 0.25);
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4px;
}
.mvc-footer-seal-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}
.mvc-footer-lockup {
    display: inline-flex;
    flex-direction: column;
    width: fit-content;
    max-width: 100%;
}
.mvc-footer-inst-name {
    color: #ffffff;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.25;
    margin: 0 0 3px;
    letter-spacing: 0.4px;
    white-space: nowrap;
    width: 100%;
}
.mvc-footer-inst-hindi {
    color: #D4AF37;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.25;
    margin-bottom: 4px;
    font-family: 'Noto Sans Devanagari', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    display: block;
    width: 100%;
    box-sizing: border-box;
    white-space: nowrap;
}
@media (max-width: 768px) {
    .mvc-footer-lockup {
        width: 100%;
    }
    .mvc-footer-inst-name,
    .mvc-footer-inst-hindi {
        white-space: normal;
    }
}
.mvc-footer-affil-sub {
    color: #94A3B8;
    font-size: 11.5px;
    line-height: 1.35;
    display: block;
}
.mvc-footer-heritage-text {
    font-size: 13.5px;
    line-height: 1.65;
    color: #94A3B8;
    margin-bottom: 18px;
    text-align: justify;
}
.mvc-footer-motto-badge {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(212, 175, 55, 0.08);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-left: 3px solid #D4AF37;
    padding: 10px 14px;
    border-radius: 6px;
    margin-bottom: 18px;
}
.mvc-motto-icon {
    font-size: 20px;
    line-height: 1;
}
.mvc-motto-text strong {
    display: block;
    color: #D4AF37;
    font-size: 13.5px;
    letter-spacing: 0.5px;
}
.mvc-motto-text small {
    display: block;
    color: #CBD5E1;
    font-size: 11.5px;
    font-style: italic;
}
.mvc-footer-accred-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.mvc-accred-tag {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #CBD5E1;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    letter-spacing: 0.2px;
}

/* Section Headings */
.mvc-footer-col-title {
    position: relative;
    font-size: 15px;
    font-weight: 800;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 0 0 20px;
    padding-bottom: 10px;
    display: block;
}
.mvc-title-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 36px;
    height: 3px;
    background: #D4AF37;
    border-radius: 999px;
}

/* Streamlined Nav Link Lists */
.mvc-footer-nav-list {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.mvc-footer-nav-item {
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
}
.mvc-footer-nav-link {
    color: #CBD5E1 !important;
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    line-height: 1.35;
}
.mvc-footer-nav-link-left {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 0;
}
.mvc-nav-arrow-wrap {
    width: 20px;
    height: 20px;
    border-radius: 5px;
    background: rgba(212, 175, 55, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.25s ease;
}
.mvc-nav-arrow {
    color: #D4AF37;
    transition: transform 0.25s ease;
}
.mvc-nav-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: 0.1px;
}
.mvc-nav-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 999px;
    background: rgba(212, 175, 55, 0.12);
    color: #D4AF37;
    border: 1px solid rgba(212, 175, 55, 0.25);
    white-space: nowrap;
    letter-spacing: 0.3px;
    flex-shrink: 0;
    transition: all 0.25s ease;
}
.mvc-footer-nav-link:hover {
    color: #FFFFFF !important;
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(212, 175, 55, 0.35);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
}
.mvc-footer-nav-link:hover .mvc-nav-arrow-wrap {
    background: #D4AF37;
}
.mvc-footer-nav-link:hover .mvc-nav-arrow {
    color: #00142B;
    transform: translateX(1px);
}
.mvc-footer-nav-link:hover .mvc-nav-badge {
    background: #800000;
    color: #FFFFFF;
    border-color: #800000;
}

/* Contact List */
.mvc-footer-contact-list {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 0 20px !important;
}
.mvc-footer-contact-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px !important;
    padding: 0 !important;
}
.mvc-contact-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(212, 175, 55, 0.1);
    color: #D4AF37;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}
.mvc-contact-text {
    flex: 1;
    font-size: 13px;
    color: #94A3B8;
}
.mvc-contact-label {
    display: block;
    color: #D4AF37;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.mvc-contact-text address {
    font-style: normal;
    color: #CBD5E1;
    line-height: 1.4;
    margin: 0;
}
.mvc-contact-text a {
    color: #ffffff !important;
    text-decoration: none !important;
    font-weight: 600;
    transition: color 0.2s;
}
.mvc-contact-text a:hover {
    color: #D4AF37 !important;
    text-decoration: underline !important;
}

/* Action Buttons */
.mvc-footer-action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.mvc-footer-action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
}
.mvc-maps-btn {
    background: #002147;
    color: #ffffff !important;
    border: 1px solid rgba(212, 175, 55, 0.35);
}
.mvc-maps-btn:hover {
    background: #002c5f;
    border-color: #D4AF37;
    color: #D4AF37 !important;
    transform: translateY(-2px);
}
.mvc-emergency-btn {
    background: #800000;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.mvc-emergency-btn:hover {
    background: #990000;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(128, 0, 0, 0.5);
}
.mvc-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22C55E;
    box-shadow: 0 0 0 rgba(34, 197, 94, 0.4);
    animation: mvcPulseBeacon 1.8s infinite;
}

/* Analytics & Footfall Counter Bar */
.mvc-footer-analytics-bar {
    background: #000c1a;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 28px 0;
}
.mvc-analytics-header {
    text-align: center;
    margin-bottom: 20px;
}
.mvc-analytics-title {
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #D4AF37;
}
.mvc-analytics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}
.mvc-metric-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.3s ease;
}
.mvc-metric-card:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(212, 175, 55, 0.4);
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}
.mvc-metric-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(0, 33, 71, 0.85);
    border: 1px solid rgba(212, 175, 55, 0.25);
    color: #D4AF37;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
}
.mvc-metric-val {
    font-size: 22px;
    font-weight: 900;
    color: #ffffff;
    line-height: 1.1;
    font-family: -apple-system, BlinkMacSystemFont, "Plus Jakarta Sans", sans-serif;
    letter-spacing: -0.5px;
}
.mvc-metric-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #94A3B8;
    margin-top: 3px;
}
.mvc-live-card {
    border-color: rgba(34, 197, 94, 0.3);
}
.mvc-live-beacon {
    position: absolute;
    top: -3px;
    right: -3px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #22C55E;
    box-shadow: 0 0 0 rgba(34, 197, 94, 0.4);
    animation: mvcPulseBeacon 1.8s infinite;
}
@keyframes mvcPulseBeacon {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

/* Bottom Legal Strip */
.mvc-footer-bottom {
    background: #000814;
    padding: 14px 0;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
}
.mvc-bottom-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: nowrap;
    width: 100%;
    box-sizing: border-box;
}
.mvc-bottom-copyright {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #94A3B8;
    white-space: nowrap;
    flex-shrink: 0;
}
.mvc-inst-copy {
    display: inline;
}
.mvc-bottom-copyright strong {
    color: #ffffff;
    font-weight: 600;
}
.mvc-dev-inline {
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}
.mvc-dev-inline a.mvc-credit-link {
    color: #D4AF37 !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.2s ease;
    border-bottom: 1px dotted rgba(212, 175, 55, 0.4);
    margin-left: 2px;
}
.mvc-dev-inline a.mvc-credit-link:hover {
    color: #FFFFFF !important;
    border-bottom-color: #FFFFFF;
}
.mvc-bottom-legal-links {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    white-space: nowrap;
}
.mvc-bottom-legal-links a {
    color: #94A3B8 !important;
    font-size: 11px;
    text-decoration: none !important;
    transition: color 0.2s;
}
.mvc-bottom-legal-links a:hover {
    color: #D4AF37 !important;
}
.mvc-sep {
    color: #475569;
    font-size: 9px;
}
.mvc-bottom-social-bar {
    display: flex;
    align-items: center;
    flex-shrink: 0;
    white-space: nowrap;
}
.mvc-social-icons {
    display: flex;
    align-items: center;
    gap: 6px;
}
.mvc-social-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #CBD5E1 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    transition: all 0.25s ease;
}
.mvc-social-icon:hover {
    transform: translateY(-2px);
    color: #ffffff !important;
}
.mvc-social-icon.mvc-yt:hover { background: #FF0000; border-color: #FF0000; box-shadow: 0 4px 14px rgba(255, 0, 0, 0.4); }
.mvc-social-icon.mvc-fb:hover { background: #1877F2; border-color: #1877F2; box-shadow: 0 4px 14px rgba(24, 119, 242, 0.4); }
.mvc-social-icon.mvc-tw:hover { background: #000000; border-color: #D4AF37; box-shadow: 0 4px 14px rgba(212, 175, 55, 0.3); }
.mvc-social-icon.mvc-ig:hover { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); border-color: transparent; }
.mvc-social-icon.mvc-li:hover { background: #0A66C2; border-color: #0A66C2; box-shadow: 0 4px 14px rgba(10, 102, 194, 0.4); }

/* Astra Native Scroll to Top Button */
#ast-scroll-top,
.ast-scroll-top-icon {
    position: fixed !important;
    bottom: 28px !important;
    right: 28px !important;
    z-index: 9999 !important;
    width: 48px !important;
    height: 48px !important;
    min-width: 48px !important;
    min-height: 48px !important;
    border-radius: 50% !important;
    background-color: #002147 !important;
    background: #002147 !important;
    border: 2px solid #D4AF37 !important;
    color: #ffffff !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    padding: 0 !important;
    line-height: 1 !important;
    outline: none !important;
}
#ast-scroll-top:hover,
.ast-scroll-top-icon:hover {
    background-color: #800000 !important;
    background: #800000 !important;
    border-color: #ffffff !important;
    transform: translateY(-4px) scale(1.08) !important;
    box-shadow: 0 10px 25px rgba(128, 0, 0, 0.5) !important;
}
#ast-scroll-top .ast-icon,
#ast-scroll-top .ast-icon svg,
#ast-scroll-top .ast-arrow-svg,
.ast-scroll-top-icon .ast-icon svg {
    fill: #D4AF37 !important;
    color: #D4AF37 !important;
    width: 22px !important;
    height: 22px !important;
    display: block !important;
    transition: all 0.3s ease !important;
}
#ast-scroll-top:hover .ast-icon,
#ast-scroll-top:hover .ast-icon svg,
#ast-scroll-top:hover .ast-arrow-svg,
.ast-scroll-top-icon:hover .ast-icon svg {
    fill: #ffffff !important;
    color: #ffffff !important;
}

/* Responsive Mobile-First Breakpoints */
@media (max-width: 1200px) {
    .mvc-footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
    .mvc-analytics-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
}

@media (max-width: 992px) {
    .mvc-footer-bottom {
        padding: 24px 16px 36px;
    }
    .mvc-bottom-flex {
        flex-direction: column;
        text-align: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .mvc-bottom-copyright {
        display: block !important;
        width: 100%;
        text-align: center;
        font-size: 12px;
        line-height: 1.6;
        white-space: normal !important;
    }
    .mvc-inst-copy {
        display: block;
        margin-bottom: 3px;
        color: #E2E8F0;
    }
    .mvc-sep-credits {
        display: none !important;
    }
    .mvc-dev-inline {
        display: inline-block !important;
        font-size: 11.5px;
        color: #94A3B8;
    }
    .mvc-bottom-legal-links {
        display: flex !important;
        flex-wrap: wrap !important;
        justify-content: center;
        align-items: center;
        gap: 6px 10px;
        width: 100%;
        white-space: normal !important;
    }
    .mvc-bottom-legal-links a {
        font-size: 12px !important;
        padding: 3px 6px;
        display: inline-block;
    }
    .mvc-bottom-social-bar {
        display: flex !important;
        justify-content: center;
        width: 100%;
        margin-top: 4px;
    }
    .mvc-social-icons {
        justify-content: center;
        gap: 12px;
    }
    .mvc-social-icon {
        width: 36px !important;
        height: 36px !important;
    }
    .mvc-social-icon svg {
        width: 16px !important;
        height: 16px !important;
    }
}

@media (max-width: 768px) {
    .mvc-footer-main {
        padding: 44px 0 32px;
    }
    .mvc-footer-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .mvc-footer-nav-link {
        padding: 9px 12px;
    }
    .mvc-footer-nav-link-left {
        gap: 10px;
    }
    .mvc-nav-text {
        white-space: normal;
    }
    .mvc-analytics-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .mvc-metric-card {
        padding: 12px 14px;
        gap: 10px;
    }
    .mvc-metric-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
    }
    .mvc-metric-icon svg {
        width: 18px;
        height: 18px;
    }
    .mvc-metric-val {
        font-size: 18px;
    }
    .mvc-metric-label {
        font-size: 11px;
    }
    #ast-scroll-top,
    .ast-scroll-top-icon {
        bottom: 20px !important;
        right: 20px !important;
        width: 42px !important;
        height: 42px !important;
        min-width: 42px !important;
        min-height: 42px !important;
    }
}

@media (max-width: 480px) {
    .mvc-analytics-grid {
        grid-template-columns: 1fr;
    }
}
</style>

	<?php astra_footer_after(); ?>
	<?php wp_footer(); ?>
</body>
</html>
