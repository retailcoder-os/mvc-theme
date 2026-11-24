<?php
/**
 * Custom Header for M.V. College, Buxar (RetailCoder StoreFront [RC:StoreFront] Theme)
 * https://retailcoder.com
 * Universal 10/10 Standard Directive Implementation
 * 100% Repository Self-Contained | Zero Remote Dependencies
 * 
 * Architecture:
 * 1. Tier 1: Top Utility & Contact Strip (Helpline, Email, Location + 9 Direct Utility Links + Golden Portal Login)
 * 2. Tier 2: Grand Institutional Identity Bar (High-Res Seal, Bilingual Title, VKSU Affiliation, UGC/NAAC Badges, Admission Pill)
 * 3. Tier 3: Dedicated Full-Width Notice & Circular Ribbon (100% Screen Width Marquee, Pause/Play Controls, Direct PDFs)
 * 4. Tier 4: Bottom Sticky Navigation Bar (Desktop Sticky Nav, 4-Column Mega-Menus, Expandable Search, Off-Canvas Mobile Drawer)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!DOCTYPE html>
<?php astra_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php astra_head_top(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
<?php astra_head_bottom(); ?>
<style id="mvc-bilingual-sync-css">
.mvc-title-lockup {
    display: table !important;
    width: auto !important;
    max-width: 100% !important;
}
.mvc-title-lockup .mvc-site-title {
    display: block !important;
    white-space: nowrap !important;
    width: 100% !important;
    font-size: 15px !important;
    font-weight: 800 !important;
    letter-spacing: 0.3px !important;
    line-height: 1.15 !important;
    color: #002147 !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    margin: 0 !important;
}
.mvc-title-lockup .mvc-hindi-title {
    display: block !important;
    width: 100% !important;
    margin: 2px 0 4px 0 !important;
    color: #7A1C1C !important;
    font-weight: 700 !important;
    font-size: 29px !important;
    line-height: 1.2 !important;
    font-family: 'Noto Sans Devanagari', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    box-sizing: border-box !important;
    white-space: nowrap !important;
    letter-spacing: normal !important;
}
@media (max-width: 768px) {
    .mvc-title-lockup {
        display: block !important;
        width: 100% !important;
    }
    .mvc-title-lockup .mvc-site-title {
        white-space: normal !important;
        font-size: 11px !important;
    }
    .mvc-title-lockup .mvc-hindi-title {
        font-size: 22px !important;
        width: 100% !important;
        letter-spacing: normal !important;
        white-space: normal !important;
    }
}
</style>
</head>
<body <?php astra_schema_body(); ?> <?php body_class(); ?>>
<?php astra_body_top(); wp_body_open(); ?>
<div <?php echo wp_kses_post( astra_attr( 'site', array( 'id' => 'page', 'class' => 'hfeed site' ) ) ); ?>>
	<?php astra_header_before(); ?>
	
	<header id="masthead" class="mvc-custom-header" role="banner">
		
		<!-- ==============================================================
		     TIER 0: INSTITUTIONAL MAINTENANCE NOTIFICATION BAR
		     ============================================================== -->
		<?php 
		if (function_exists('mvc_render_maintenance_notice_banner')) {
			mvc_render_maintenance_notice_banner();
		}
		?>
		
		<!-- ==============================================================
		     TIER 1: TOP UTILITY & CONTACT STRIP (Helpline, Email & Utilities)
		     ============================================================== -->
		<div class="mvc-top-bar" id="mvcTopBar">
			<div class="mvc-container mvc-top-flex">
				
				<!-- Left: Official Institutional Contacts (Phone & Email with Visible SVGs) -->
				<div class="mvc-top-contacts">
					<a href="tel:06183796317" class="mvc-top-contact-link mvc-utility-call-btn" title="Campus Central Helpline: 06183-796317">
						<svg class="mvc-top-svg-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#FFD54F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						<span class="mvc-desk-call-text">Helpline: <strong>06183-796317</strong></span>
						<span class="mvc-mob-call-text"><strong>06183-796317</strong></span>
					</a>
					<span class="mvc-contact-sep" aria-hidden="true">•</span>
					<a href="mailto:mvcollegebuxar@gmail.com" class="mvc-top-contact-link mvc-utility-email-btn" title="Official Institutional Email: mvcollegebuxar@gmail.com">
						<svg class="mvc-top-svg-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#FFD54F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
						<span class="mvc-desk-email-text">mvcollegebuxar@gmail.com</span>
						<span class="mvc-mob-email-text">mvcollegebuxar@gmail.com</span>
					</a>
					<span class="mvc-contact-sep mvc-hide-mobile" aria-hidden="true">•</span>
					<span class="mvc-top-location mvc-hide-mobile">
						<svg class="mvc-top-svg-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#FFD54F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
						<span>Buxar, Bihar</span>
					</span>
				</div>

				<!-- Right: Direct Access Utilities Suite & Golden Portal Login -->
				<div class="mvc-utility">
					<ul class="mvc-utility-links" aria-label="Utility Quick Links">
						
						<li>
							<a href="<?php echo esc_url(home_url('/library')); ?>" title="Automated Central Library">
								<span class="mvc-u-icon" aria-hidden="true">📚</span>
								<span>Library</span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url(home_url('/e-resources')); ?>" title="Digital E-Resources &amp; Repositories">
								<span class="mvc-u-icon" aria-hidden="true">🌐</span>
								<span>e-Resources</span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url(home_url('/tender')); ?>" title="Official Public Tenders &amp; Procurement">
								<span class="mvc-u-icon" aria-hidden="true">📋</span>
								<span>Tenders</span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url(home_url('/help-desk')); ?>" title="Centralized Student Help Desk">
								<span class="mvc-u-icon" aria-hidden="true">💬</span>
								<span>Help Desk</span>
							</a>
						</li>
						<!-- High-Prestige Golden Portal Login / Dashboard Button -->
						<li class="mvc-utility-login-item">
							<?php if (is_user_logged_in()) : 
								$curr_u = wp_get_current_user();
								$u_roles = (array) $curr_u->roles;
								if (in_array('student', $u_roles)) {
									$dash_url = home_url('/portal/');
									$dash_label = 'Student Dashboard';
								} elseif (in_array('alumni_member', $u_roles) || in_array('alumni', $u_roles)) {
									$dash_url = home_url('/portal/');
									$dash_label = 'Alumni Dashboard';
								} elseif (in_array('department_head', $u_roles)) {
									$dash_url = admin_url('admin.php?page=mvc-command-center&section=my-department');
									$dash_label = 'HOD Dashboard';
								} elseif (in_array('content_manager', $u_roles)) {
									$dash_url = admin_url('admin.php?page=mvc-command-center&section=review-queue');
									$dash_label = 'Review Desk';
								} else {
									$dash_url = admin_url('admin.php?page=mvc-command-center');
									$dash_label = 'Command Center';
								}
							?>
								<a href="<?php echo esc_url($dash_url); ?>" class="mvc-utility-login-btn mvc-utility-dash-btn" title="Logged in as <?php echo esc_attr($curr_u->display_name); ?> • Click to open Dashboard">
									<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
									<span><?php echo esc_html($dash_label); ?></span>
									<span class="mvc-login-arrow" aria-hidden="true">→</span>
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url(home_url('/login')); ?>" class="mvc-utility-login-btn" title="Official Faculty &amp; Student Portal Login">
									<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
									<span>Portal Login</span>
									<span class="mvc-login-arrow" aria-hidden="true">→</span>
								</a>
							<?php endif; ?>
						</li>
					</ul>
				</div>

				<!-- Mobile Quick Actions in Top Bar (Online Admission + Portal Login / Dashboard) -->
				<div class="mvc-mobile-top-actions">
					<?php if (is_user_logged_in()) : 
						$m_user = wp_get_current_user();
						$m_roles = (array) $m_user->roles;
						$m_dash = (in_array('student', $m_roles) || in_array('alumni_member', $m_roles)) ? home_url('/portal/') : admin_url('admin.php?page=mvc-command-center');
					?>
						<a href="<?php echo esc_url($m_dash); ?>" class="mvc-mob-top-login mvc-mob-top-dash" title="Go to Dashboard" aria-label="Dashboard">
							<svg class="mvc-top-svg-icon" width="11" height="11" viewBox="0 0 24 24" fill="#FFE082" aria-hidden="true"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
							<span>Dashboard</span>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url(home_url('/login')); ?>" class="mvc-mob-top-login" title="Portal Login" aria-label="Portal Login">
							<svg class="mvc-top-svg-icon" width="11" height="11" viewBox="0 0 24 24" fill="#FFE082" aria-hidden="true"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
							<span>Login</span>
						</a>
					<?php endif; ?>
				</div>

			</div>
		</div>

		<!-- ==============================================================
		     TIER 2: GRAND INSTITUTIONAL IDENTITY BAR (Official Crest & Badges)
		     ============================================================== -->
		<div class="mvc-middle-bar">
			<div class="mvc-container mvc-middle-flex">
				
				<!-- College Seal & Identity Block -->
				<div class="mvc-logo-section">
					<?php 
					$logo_url = '';
					if (function_exists('get_field')) {
						$cf_logo = get_field('college_logo', 209);
						if (!empty($cf_logo) && is_array($cf_logo) && !empty($cf_logo['url'])) {
							$logo_url = $cf_logo['url'];
						}
					}
					if (empty($logo_url)) {
						$custom_logo_id = get_theme_mod('custom_logo');
						if ($custom_logo_id) {
							$logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
						}
					}
					if (empty($logo_url)) {
						$local_logo = ABSPATH . 'wp-content/uploads/settings/website-logo.png';
						if (file_exists($local_logo)) {
							$logo_url = '/wp-content/uploads/settings/website-logo.png';
						} else {
							$logo_url = '/wp-content/uploads/2026/10/mv-college-website-logo.png';
						}
					}
					$logo_url = wp_make_link_relative($logo_url);
					?>
					<a href="<?php echo esc_url(home_url('/')); ?>" class="mvc-logo-link" aria-label="Maharshi Vishwamitra Mahavidyalaya, Buxar Home">
						<img src="<?php echo esc_url($logo_url); ?>" alt="Maharshi Vishwamitra Mahavidyalaya Buxar Crest" class="mvc-college-logo" width="92" height="96" loading="eager">
					</a>
					<div class="mvc-college-names">
						<div class="mvc-title-lockup">
							<?php if (is_front_page()) : ?>
								<h1 class="mvc-site-title">MAHARSHI VISHWAMITRA MAHAVIDYALAYA</h1>
							<?php else : ?>
								<div class="mvc-site-title" role="heading" aria-level="1">MAHARSHI VISHWAMITRA MAHAVIDYALAYA</div>
							<?php endif; ?>
							<div class="mvc-hindi-title">महर्षि विश्वामित्र महाविद्यालय</div>
						</div>
						<div class="mvc-address-row">
							<span class="mvc-address">
								<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="mvc-pin-icon" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
								Charitravan, Buxar – 802101 (Bihar)
							</span>
							<span class="mvc-badge-pill mvc-est-pill">Est. 1958</span>
							<span class="mvc-badge-pill mvc-aishe-pill">AISHE: C-27158</span>
						</div>
					</div>
				</div>

				<!-- Right Side: Statutory Affiliation, Accreditation & Admission Action -->
				<div class="mvc-affiliation-section">
					<div class="mvc-affil-text">
						A Constituent Unit of <strong>Veer Kunwar Singh University, Ara (Bihar)</strong>
					</div>
					<div class="mvc-accreditation-badges">
						<span class="mvc-badge mvc-badge-ugc">
							<span class="mvc-badge-icon" aria-hidden="true">🏛️</span>
							<span>UGC 2(f) &amp; 12(B) RECOGNIZED</span>
						</span>
						<span class="mvc-badge mvc-badge-aicte">
							<span class="mvc-badge-icon" aria-hidden="true">⚖️</span>
							<span>AICTE APPROVED</span>
						</span>
					</div>
					<!-- <div class="mvc-header-actions-row">
						<a href="tel:06183796317" class="mvc-helpline-pill" title="Campus Help Desk Phone">
							<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
							<span>Helpline: <strong>06183-796317</strong></span>
						</a>
						<a href="https://mvcollegebuxarmanage.com/" target="_blank" rel="noopener" class="mvc-apply-pill" title="Open Online Admission Portal">
							<span class="mvc-pulse-beacon" aria-hidden="true"></span>
							<span>Online Admission Portal ↗</span>
						</a>
					</div> -->
				</div>

			</div>
		</div>

		<!-- ==============================================================
		     TIER 3: DEDICATED 100% FULL-WIDTH NOTICE & CIRCULAR MARQUEE RIBBON
		     (Over 85-90% of Screen Width Dedicated Exclusively to Circulars)
		     ============================================================== -->
		<div class="mvc-notice-ribbon" id="mvcNoticeRibbon">
			<div class="mvc-container mvc-ribbon-flex">
				
				<!-- Chamfered Announcement Badge -->
				<div class="mvc-ticker-badge mvc-ribbon-badge">
					<span class="mvc-ticker-icon" aria-hidden="true">📢</span>
					<span class="mvc-pulse-dot" aria-hidden="true"></span>
					<span class="mvc-ticker-label mvc-desk-ticker-label">LATEST CIRCULARS &amp; NOTICES</span>
					<span class="mvc-ticker-label mvc-mob-ticker-label">NOTICES</span>
				</div>

				<!-- 100% Full-Width Notice Marquee Track with Pause-on-Hover -->
				<div class="mvc-ticker mvc-ribbon-ticker" id="mvcNoticeTicker" aria-label="Official announcements and notices" title="Pause on hover to click">
					<div class="mvc-ticker-track" id="mvcTickerTrack">
						<?php
						$latest_notices = new WP_Query(array( 
							'post_type'      => array('notice', 'notices'), 
							'posts_per_page' => 8,
							'post_status'    => 'publish',
							'orderby'        => 'date',
							'order'          => 'DESC'
						));

						if ($latest_notices->have_posts()) :
							while ($latest_notices->have_posts()) : $latest_notices->the_post();
								$pdf_val = get_field('pdf_file');
								$pdf_url = '';
								if (is_array($pdf_val) && !empty($pdf_val['url'])) $pdf_url = $pdf_val['url'];
								elseif (is_numeric($pdf_val) && (int)$pdf_val > 0) $pdf_url = wp_get_attachment_url((int)$pdf_val);
								elseif (is_string($pdf_val) && !empty($pdf_val) && $pdf_val !== '0') $pdf_url = $pdf_val;
								if (empty($pdf_url)) $pdf_url = get_post_meta(get_the_ID(), '_pdf_url', true);
								
								$link = $pdf_url ? esc_url($pdf_url) : get_permalink();
								$is_pdf = !empty($pdf_url);
								$date_str = get_the_date('d M Y');
								?>
								<a href="<?php echo $link; ?>" target="_blank" rel="noopener" class="mvc-ticker-link" title="<?php echo esc_attr(get_the_title()); ?>">
									<span class="mvc-ticker-date"><?php echo esc_html($date_str); ?></span>
									<span class="mvc-ticker-text"><?php echo esc_html(wp_strip_all_tags(get_the_title())); ?></span>
									<?php if ($is_pdf): ?>
										<span class="mvc-ticker-tag">PDF ↗</span>
									<?php endif; ?>
								</a>
								<span class="mvc-ticker-sep" aria-hidden="true">✦</span>
								<?php
							endwhile;
							wp_reset_postdata();
						else:
							?>
							<a href="<?php echo esc_url(home_url('/')); ?>" class="mvc-ticker-link">
								<span class="mvc-ticker-text">Welcome to Maharshi Vishwamitra Mahavidyalaya, Buxar</span>
							</a>
							<span class="mvc-ticker-sep" aria-hidden="true">✦</span>
							<?php
						endif;
						?>
						<!-- Permanent Direct Action Announcement -->
						<a href="https://mvcollegebuxarmanage.com/" target="_blank" rel="noopener" class="mvc-ticker-link mvc-ticker-priority">
							<span class="mvc-ticker-pill">ADMISSIONS</span>
							<span class="mvc-ticker-text">Online Admission Portal &amp; Semester Registration Active</span>
							<span class="mvc-ticker-tag">Apply ↗</span>
						</a>
						<span class="mvc-ticker-sep" aria-hidden="true">✦</span>
					</div>
				</div>

				<!-- Right Ribbon Actions: Pause/Play + Direct All Notices Archive Link -->
				<div class="mvc-ribbon-actions">
					<button class="mvc-ticker-ctrl-btn mvc-ribbon-ctrl" id="mvcTickerToggleBtn" type="button" aria-label="Pause or resume ticker" title="Pause / Play Ticker">
						<span class="mvc-ctrl-icon mvc-pause-icon" aria-hidden="true">⏸</span>
						<span class="mvc-ctrl-icon mvc-play-icon" aria-hidden="true" style="display:none;">▶</span>
					</button>
					<a href="<?php echo esc_url(home_url('/notices')); ?>" class="mvc-ribbon-all-btn" title="View All Official Notices &amp; Circulars">
						<span class="mvc-desk-all-text">All Notices</span>
						<span class="mvc-mob-all-text">All</span>
						<span class="mvc-all-arrow" aria-hidden="true">→</span>
					</a>
				</div>

			</div>
		</div>

		<!-- ==============================================================
		     TIER 4: BOTTOM NAVIGATION BAR (Sticky Navbar, Mega-Menu & Drawer)
		     ============================================================== -->
		<div class="mvc-nav-bar" id="mvcStickyNavBar">
			<div class="mvc-container mvc-nav-flex">
				
				<!-- Mobile Menu Toggle Button -->
				<button class="mvc-mobile-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="Open Navigation Menu">
					<span class="mvc-hamburger-lines" aria-hidden="true">
						<span class="mvc-line mvc-line-1"></span>
						<span class="mvc-line mvc-line-2"></span>
						<span class="mvc-line mvc-line-3"></span>
					</span>
					<span class="mvc-toggle-text">MENU</span>
				</button>

				<!-- Mobile Direct Admission Call-to-Action Pill -->
				<a href="https://mvcollegebuxarmanage.com/" target="_blank" rel="noopener" class="mvc-mobile-nav-admission" title="Online Admission Portal">
					<span class="mvc-pulse-beacon" aria-hidden="true"></span>
					<span>Online Admission ↗</span>
				</a>
				
				<!-- Mobile Quick Search Trigger -->
				<button class="mvc-mobile-search-toggle" id="mvcMobileSearchTrigger" aria-label="Toggle Search Form">
					<svg width="18" height="18" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/></svg>
				</button>
				
				<!-- Backdrop Blur Overlay -->
				<div class="mvc-side-pane-overlay" id="mvcNavOverlay" aria-hidden="true"></div>

				<!-- Main Navigation & Off-Canvas Mobile Drawer -->
				<nav id="site-navigation" class="mvc-main-navigation" role="navigation" aria-label="Main Navigation">
					
					<!-- Off-Canvas Drawer Header (Mobile Only) -->
					<div class="mvc-pane-header">
						<div class="mvc-pane-brand">
							<img src="<?php echo esc_url($logo_url); ?>" alt="College Logo" width="38" height="40" class="mvc-pane-logo">
							<div class="mvc-pane-title-group">
								<div class="mvc-pane-title">MAHARSHI VISHWAMITRA MAHAVIDYALAYA</div>
								<div class="mvc-pane-subtitle">BUXAR &bull; EST. 1958</div>
							</div>
						</div>
						<button class="mvc-close-pane" id="mvcCloseNavBtn" type="button" aria-label="Close navigation drawer" title="Close navigation menu">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
						</button>
					</div>

					<!-- Mobile Drawer Embedded Search Form -->
					<div class="mvc-drawer-search">
						<form role="search" method="get" class="mvc-drawer-search-form" action="<?php echo esc_url(home_url('/')); ?>">
							<input type="search" class="mvc-drawer-search-input" placeholder="Search notices, courses, faculty..." name="s" required>
							<button type="submit" class="mvc-drawer-search-btn" aria-label="Submit search">
								<svg width="15" height="15" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/></svg>
							</button>
						</form>
					</div>

					<!-- Primary WordPress Menu -->
					<?php
					wp_nav_menu(array(
						'theme_location' => 'primary-mega-menu',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'mvc-mega-menu',
						'container'      => false,
						'fallback_cb'    => false,
					));
					?>

					<!-- Off-Canvas Drawer Footer (Mobile Only: Complete Utility Suite) -->
					<div class="mvc-drawer-footer">
						<div class="mvc-drawer-section-heading">DIRECT ACCESS &amp; UTILITIES</div>
						<div class="mvc-drawer-utilities-grid">
							<a href="<?php echo esc_url(home_url('/onos/')); ?>" class="mvc-drawer-util-link">ONOS</a>
							<a href="<?php echo esc_url(home_url('/research/')); ?>" class="mvc-drawer-util-link">Research</a>
							<a href="<?php echo esc_url(home_url('/nirf')); ?>" class="mvc-drawer-util-link">NIRF</a>
							<a href="<?php echo esc_url(home_url('/library')); ?>" class="mvc-drawer-util-link">Library</a>
							<a href="<?php echo esc_url(home_url('/e-resources')); ?>" class="mvc-drawer-util-link">e-Resources</a>
							<a href="<?php echo esc_url(home_url('/anti-ragging-cell')); ?>" class="mvc-drawer-util-link">Anti-Ragging</a>
							<a href="<?php echo esc_url(home_url('/tender')); ?>" class="mvc-drawer-util-link">Tenders</a>
							<a href="<?php echo esc_url(home_url('/help-desk')); ?>" class="mvc-drawer-util-link">Help Desk</a>
						</div>
						<div class="mvc-drawer-login-wrap">
							<?php if (is_user_logged_in()) : 
								$d_user = wp_get_current_user();
								$d_roles = (array) $d_user->roles;
								$d_dash = (in_array('student', $d_roles) || in_array('alumni_member', $d_roles)) ? home_url('/portal/') : admin_url('admin.php?page=mvc-command-center');
								$d_label = in_array('student', $d_roles) ? 'Student Dashboard' : (in_array('alumni_member', $d_roles) ? 'Alumni Dashboard' : 'Administration Dashboard');
							?>
								<a href="<?php echo esc_url($d_dash); ?>" class="mvc-drawer-portal-btn mvc-drawer-dash-btn">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
									<span><?php echo esc_html($d_label); ?> (<?php echo esc_html($d_user->display_name); ?>)</span>
									<span class="mvc-drawer-arrow" aria-hidden="true">→</span>
								</a>
								<div style="text-align:center; margin-top:8px;">
									<a href="<?php echo esc_url(wp_logout_url(home_url('/login/'))); ?>" style="color:#FFE082; font-size:12px; text-decoration:underline;">Sign Out / Log Out</a>
								</div>
							<?php else : ?>
								<a href="<?php echo esc_url(home_url('/login')); ?>" class="mvc-drawer-portal-btn">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
									<span>Official Portal Login</span>
								</a>
							<?php endif; ?>
						</div>
						<div class="mvc-drawer-contact-block">
							<a href="tel:06183796317" class="mvc-drawer-contact-item">
								<span>📞 06183-796317</span>
							</a>
							<a href="mailto:mvcollegebuxar@gmail.com" class="mvc-drawer-contact-item">
								<span>✉️ mvcollegebuxar@gmail.com</span>
							</a>
						</div>
						<div class="mvc-drawer-bottom-close-wrap">
							<button type="button" class="mvc-drawer-bottom-close-btn" id="mvcBottomCloseNavBtn" aria-label="Close navigation menu">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
								<span>Close Menu</span>
							</button>
						</div>
					</div>

				</nav>

				<!-- Desktop Expandable Search Form -->
				<div class="mvc-nav-search">
					<form role="search" method="get" class="mvc-search-form" action="<?php echo esc_url(home_url('/')); ?>">
						<input type="search" class="mvc-search-input" placeholder="Search college..." name="s" required aria-label="Search college website">
						<button type="submit" class="mvc-search-btn" aria-label="Submit Search">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 512 512" aria-hidden="true"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/></svg>
						</button>
					</form>
				</div>

			</div>
		</div>

		<!-- Mobile Search Overlay Form (Toggled by mobile search button) -->
		<div class="mvc-mobile-search-bar" id="mvcMobileSearchBar" aria-hidden="true">
			<div class="mvc-container">
				<form role="search" method="get" class="mvc-mobile-search-inline" action="<?php echo esc_url(home_url('/')); ?>">
					<input type="search" class="mvc-mobile-search-field" placeholder="Search notices, departments, documents..." name="s" required>
					<button type="submit" class="mvc-mobile-search-submit" aria-label="Search">Search</button>
					<button type="button" class="mvc-mobile-search-close" id="mvcCloseMobileSearch" aria-label="Close search">&times;</button>
				</form>
			</div>
		</div>
		
		<!-- Interactive Navigation Script -->
		<script>
		document.addEventListener('DOMContentLoaded', function() {
			var toggleBtn       = document.querySelector('.mvc-mobile-toggle');
			var closeBtn        = document.getElementById('mvcCloseNavBtn');
			var bottomCloseBtn  = document.getElementById('mvcBottomCloseNavBtn');
			var navPane         = document.getElementById('site-navigation');
			var overlay         = document.getElementById('mvcNavOverlay');
			var mobileSearchTrg = document.getElementById('mvcMobileSearchTrigger');
			var mobileSearchBar = document.getElementById('mvcMobileSearchBar');
			var mobileSearchCls = document.getElementById('mvcCloseMobileSearch');
			var stickyNavBar    = document.getElementById('mvcStickyNavBar');

			// 1. Off-Canvas Drawer Toggle
			function toggleDrawer(show) {
				if (!navPane) return;
				if (show) {
					navPane.classList.add('pane-open');
					if (overlay) overlay.classList.add('pane-open');
					if (toggleBtn) {
						toggleBtn.setAttribute('aria-expanded', 'true');
						toggleBtn.classList.add('is-active');
					}
					document.body.classList.add('mvc-drawer-active');
				} else {
					navPane.classList.remove('pane-open');
					if (overlay) overlay.classList.remove('pane-open');
					if (toggleBtn) {
						toggleBtn.setAttribute('aria-expanded', 'false');
						toggleBtn.classList.remove('is-active');
					}
					document.body.classList.remove('mvc-drawer-active');
				}
			}

			if (toggleBtn) {
				toggleBtn.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					var isOpen = navPane ? navPane.classList.contains('pane-open') : false;
					toggleDrawer(!isOpen);
				});
			}

			if (closeBtn) {
				closeBtn.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					toggleDrawer(false);
				});
			}

			if (bottomCloseBtn) {
				bottomCloseBtn.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					toggleDrawer(false);
				});
			}

			if (overlay) {
				overlay.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					toggleDrawer(false);
				});
			}

			// Tap outside drawer to close
			document.addEventListener('click', function(e) {
				if (navPane && navPane.classList.contains('pane-open')) {
					if (!navPane.contains(e.target) && (!toggleBtn || !toggleBtn.contains(e.target))) {
						toggleDrawer(false);
					}
				}
			});

			// Close on Escape key
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape') {
					toggleDrawer(false);
					if (mobileSearchBar) mobileSearchBar.classList.remove('active');
				}
			});

			// 2. Mobile Search Bar Toggle
			if (mobileSearchTrg && mobileSearchBar) {
				mobileSearchTrg.addEventListener('click', function(e) {
					e.preventDefault();
					mobileSearchBar.classList.toggle('active');
					if (mobileSearchBar.classList.contains('active')) {
						var inp = mobileSearchBar.querySelector('.mvc-mobile-search-field');
						if (inp) inp.focus();
					}
				});
			}
			if (mobileSearchCls && mobileSearchBar) {
				mobileSearchCls.addEventListener('click', function(e) {
					e.preventDefault();
					mobileSearchBar.classList.remove('active');
				});
			}

			// 3. Mobile Accordion Toggle for Sub-menus
			var parentItems = document.querySelectorAll('.mvc-mega-menu > li.menu-item-has-children, .mvc-mega-menu > li.mvc-has-mega-menu');
			parentItems.forEach(function(item) {
				// Inject toggle button for mobile if not already present
				if (!item.querySelector('.mvc-mobile-arrow')) {
					var arrowBtn = document.createElement('button');
					arrowBtn.className = 'mvc-mobile-arrow';
					arrowBtn.setAttribute('type', 'button');
					arrowBtn.setAttribute('aria-label', 'Toggle sub-menu');
					arrowBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>';
					
					var anchor = item.querySelector(':scope > a');
					if (anchor) {
						anchor.parentNode.insertBefore(arrowBtn, anchor.nextSibling);
					}

					arrowBtn.addEventListener('click', function(e) {
						e.preventDefault();
						e.stopPropagation();
						var willOpen = !item.classList.contains('mobile-expanded');
						if (willOpen) {
							parentItems.forEach(function(other) {
								if (other !== item) {
									other.classList.remove('mobile-expanded');
									var otherArrow = other.querySelector('.mvc-mobile-arrow');
									if (otherArrow) otherArrow.classList.remove('arrow-rotated');
								}
							});
						}
						item.classList.toggle('mobile-expanded', willOpen);
						arrowBtn.classList.toggle('arrow-rotated', willOpen);
					});
				}

				// Also allow clicking hash links on mobile
				var topLink = item.querySelector(':scope > a');
				if (topLink) {
					topLink.addEventListener('click', function(e) {
						if (window.innerWidth <= 1100 && (this.getAttribute('href') === '#' || this.getAttribute('href') === '')) {
							e.preventDefault();
							var willOpen = !item.classList.contains('mobile-expanded');
							if (willOpen) {
								parentItems.forEach(function(other) {
									if (other !== item) {
										other.classList.remove('mobile-expanded');
										var otherArrow = other.querySelector('.mvc-mobile-arrow');
										if (otherArrow) otherArrow.classList.remove('arrow-rotated');
									}
								});
							}
							item.classList.toggle('mobile-expanded', willOpen);
							var arrow = item.querySelector('.mvc-mobile-arrow');
							if (arrow) arrow.classList.toggle('arrow-rotated', willOpen);
						}
					});
				}
			});

			// 4. Scroll Sticky Shadow Elevation
			window.addEventListener('scroll', function() {
				if (!stickyNavBar) return;
				if (window.scrollY > 80) {
					stickyNavBar.classList.add('mvc-nav-scrolled');
				} else {
					stickyNavBar.classList.remove('mvc-nav-scrolled');
				}
			}, { passive: true });

			// 5. Interactive Ticker Pause/Play Toggle
			var tickerTrack = document.getElementById('mvcTickerTrack');
			var tickerBtn   = document.getElementById('mvcTickerToggleBtn');
			if (tickerBtn && tickerTrack) {
				tickerBtn.addEventListener('click', function(e) {
					e.preventDefault();
					var isPaused = tickerTrack.classList.toggle('mvc-ticker-paused');
					var pauseIcon = tickerBtn.querySelector('.mvc-pause-icon');
					var playIcon  = tickerBtn.querySelector('.mvc-play-icon');
					if (isPaused) {
						tickerTrack.style.animationPlayState = 'paused';
						if (pauseIcon) pauseIcon.style.display = 'none';
						if (playIcon) playIcon.style.display = 'inline-block';
						tickerBtn.setAttribute('aria-label', 'Resume ticker animation');
						tickerBtn.setAttribute('title', 'Play Ticker');
					} else {
						tickerTrack.style.animationPlayState = 'running';
						if (pauseIcon) pauseIcon.style.display = 'inline-block';
						if (playIcon) playIcon.style.display = 'none';
						tickerBtn.setAttribute('aria-label', 'Pause ticker animation');
						tickerBtn.setAttribute('title', 'Pause Ticker');
					}
				});
			}
		});
		</script>

	</header>
	
	<?php astra_header_after(); astra_content_before(); ?>
	<div id="content" class="site-content">
		<div class="ast-container">
		<?php astra_content_top(); ?>