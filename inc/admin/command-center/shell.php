<?php
/**
 * MV College Staff Command Center Shell (v2.0 Complete Modern CMS)
 * 
 * Master layout rendering modern full-screen topbar, comprehensive sidebar
 * with all CMS options, interactive CMS content hub, and role-based panels.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

$user = wp_get_current_user();
$role = !empty($user->roles) ? reset($user->roles) : 'staff';
$is_admin = in_array('administrator', $user->roles, true);
$is_cm    = in_array('content_manager', $user->roles, true);
$is_hod   = in_array('department_head', $user->roles, true);

$dept_id = (int) get_user_meta($user->ID, 'mvc_dept_term_id', true);
$dept_term = $dept_id ? get_term($dept_id, 'dept_link') : null;
$dept_name = $dept_term ? $dept_term->name : 'Unassigned';

$legacy_url = wp_nonce_url(admin_url('index.php?mvc_toggle_mode=legacy'), 'mvc_toggle_mode_nonce');
$logout_url = wp_logout_url(home_url('/login/'));
$profile_url = admin_url('profile.php');
$portal_url = home_url('/portal/');
$site_url = home_url('/');

// User Initials for Avatar
$user_display = $user->display_name ?: $user->user_login;
$parts = explode(' ', trim($user_display));
$initials = strtoupper(substr($parts[0] ?? 'A', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
if (empty($initials)) {
    $initials = 'MV';
}

// Post type counts
$counts = array(
    'notice'             => (int) (wp_count_posts('notice')->publish ?? 0),
    'slider'             => (int) (wp_count_posts('slider')->publish ?? 0),
    'leadership'         => (int) (wp_count_posts('leadership')->publish ?? 0),
    'calendar_milestone' => (int) (wp_count_posts('calendar_milestone')->publish ?? 0),
    'calendar_gazette'   => (int) (wp_count_posts('calendar_gazette')->publish ?? 0),
    'faculty'            => (int) (wp_count_posts('faculty')->publish ?? 0),
    'department'         => (int) (wp_count_posts('department')->publish ?? 0),
    'event'              => (int) (wp_count_posts('event')->publish ?? 0),
    'press_media'        => (int) ((wp_count_posts('press-media')->publish ?? 0) ?: (wp_count_posts('press_media')->publish ?? 0)),
    'press-media'        => (int) ((wp_count_posts('press-media')->publish ?? 0) ?: (wp_count_posts('press_media')->publish ?? 0)),
    'study_material'     => (int) (wp_count_posts('study_material')->publish ?? 0),
    'research'           => (int) (wp_count_posts('research')->publish ?? 0),
    'committee'          => (int) (wp_count_posts('committee')->publish ?? 0),
    'staff_member'       => (int) (wp_count_posts('staff_member')->publish ?? 0),
    'page'               => (int) (wp_count_posts('page')->publish ?? 0),
    'post'               => (int) (wp_count_posts('post')->publish ?? 0),
);
// Render Modern Executive Command Center Preloader
if (function_exists('mvc_render_command_center_preloader')) {
    mvc_render_command_center_preloader();
}
?>

<div class="mvc-cc-wrapper" id="mvcCommandCenterApp">
    <!-- Backdrop for Mobile Drawer -->
    <div class="mvc-mobile-backdrop" id="mvcMobileBackdrop"></div>

    <!-- 1. Master Modern Topbar (Full Width) -->
    <header class="mvc-cc-topbar">
        <!-- Left: Mobile Toggle, Desktop Collapse & Institutional Brand -->
        <div class="mvc-topbar-left">
            <!-- Mobile Hamburger Toggle (Touch target 44px) -->
            <button type="button" class="mvc-topbar-btn mvc-hamburger-btn" id="mvcMobileMenuToggle" aria-label="Toggle Navigation Drawer" title="Open Menu">
                <span class="dashicons dashicons-menu"></span>
            </button>

            <!-- Desktop Sidebar Collapse Toggle -->
            <button type="button" class="mvc-topbar-btn mvc-desktop-collapse-btn" id="mvcDesktopCollapseToggle" aria-label="Collapse Sidebar" title="Collapse Sidebar (Ctrl+[)">
                <span class="dashicons dashicons-menu-alt3"></span>
            </button>

            <!-- Institutional Crest & Title -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-command-center')); ?>" class="mvc-cc-brand" style="text-decoration:none;">
                <div class="mvc-cc-crest">🏛️</div>
                <div class="mvc-brand-text">
                    <div class="mvc-cc-title">
                        <span>M.V. COLLEGE BUXAR</span>
                        <span class="mvc-brand-pill">CMS HUB</span>
                    </div>
                    <div class="mvc-cc-subtitle">Executive Command Center • Constituent of V.K.S.U. Ara</div>
                </div>
            </a>

            <!-- Live Status Pill -->
            <div class="mvc-live-status-pill d-none-mobile">
                <span class="mvc-pulse-dot"></span>
                <span>Session 2026-27 Active</span>
            </div>
        </div>

        <!-- Center: Interactive Command Search / Spotlight -->
        <div class="mvc-topbar-center">
            <button type="button" class="mvc-spotlight-btn" id="mvcCommandPaletteTrigger" title="Quick Search all CMS content and actions (Ctrl+K)">
                <span class="mvc-spotlight-icon">🔍</span>
                <span class="mvc-spotlight-text">Search all CMS options, notices, faculty, pages...</span>
                <kbd class="mvc-kbd">Ctrl+K</kbd>
            </button>
        </div>

        <!-- Right: Modern Actions, Quick Site, Notifications, Theme, User Pill -->
        <div class="mvc-topbar-right">
            <!-- View Live Site / Public Portal -->
            <a href="<?php echo esc_url($site_url); ?>" class="mvc-topbar-action-btn" target="_blank" rel="noopener" title="Open Public Website">
                <span class="dashicons dashicons-admin-site-alt3"></span>
                <span class="btn-label d-none-mobile">Live Site</span>
                <span class="dashicons dashicons-external" style="font-size:12px; width:12px; height:12px;"></span>
            </a>

            <!-- Notification Bell with Popover Tray -->
            <div class="mvc-popover-wrapper" id="mvcNotificationWrapper">
                <button type="button" class="mvc-topbar-action-btn mvc-icon-btn" id="mvcNotificationBellBtn" title="Institutional Notifications &amp; Alerts" aria-label="Notifications" aria-haspopup="true" aria-expanded="false">
                    <span class="dashicons dashicons-bell"></span>
                    <span class="mvc-badge-counter" id="mvcNotificationBadge" style="display:none;">0</span>
                </button>

                <!-- Notification Dropdown Tray -->
                <div class="mvc-popover-tray mvc-notification-tray-pro" id="mvcNotificationTray" style="display:none;">
                    <div class="mvc-tray-header">
                        <div class="mvc-tray-header-title">
                            <span class="mvc-tray-crest-icon">🔔</span>
                            <div>
                                <h4>Institutional Alerts Hub</h4>
                                <div class="mvc-tray-subtitle" id="mvcTrayHeaderSubtitle">Active collegiate notices &amp; alerts</div>
                            </div>
                        </div>
                        <div class="mvc-tray-header-actions">
                            <span class="mvc-tray-count" id="mvcTrayHeaderCount">0 New</span>
                            <button type="button" class="mvc-tray-mark-read-btn" id="mvcMarkAllReadBtn" title="Mark all alerts as read">
                                ✓ Mark All Read
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filter Sub-bar -->
                    <div class="mvc-tray-filter-bar">
                        <button type="button" class="mvc-filter-pill active" data-filter="all">All Alerts</button>
                        <button type="button" class="mvc-filter-pill" data-filter="unread">Unread (<span id="mvcFilterUnreadCount">0</span>)</button>
                        <button type="button" class="mvc-filter-pill" data-filter="department">Department</button>
                    </div>

                    <div class="mvc-tray-body" id="mvcNotificationTrayList">
                        <div class="mvc-tray-empty">
                            <div style="font-size:32px; margin-bottom:8px;">🏛️✨</div>
                            <strong>All Institutional Systems Optimal</strong>
                            <div style="font-size:12px; color:var(--mvc-text-muted); margin-top:4px;">No urgent circulars or pending reviews require your attention.</div>
                        </div>
                    </div>

                    <!-- Tray Footer -->
                    <div class="mvc-tray-footer">
                        <button type="button" class="mvc-tray-refresh-btn" id="mvcRefreshNotifsBtn" title="Check for new notifications">
                            <span class="dashicons dashicons-update" style="font-size:13px; width:13px; height:13px; line-height:13px;"></span> Refresh Alerts
                        </button>
                        <a href="#" class="mvc-tray-dest-link" id="mvcTrayFooterLink">Operations Desk &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Dark / Light Theme Toggle -->
            <button type="button" class="mvc-topbar-action-btn mvc-icon-btn" id="mvcThemeToggleBtn" title="Toggle Dark / Light Theme" aria-label="Toggle Theme">
                <span class="mvc-theme-icon">🌓</span>
            </button>

            <!-- Switch to Classic WP Admin -->
            <a href="<?php echo esc_url($legacy_url); ?>" class="mvc-btn mvc-btn-classic" title="Switch to Classic Native WordPress Dashboard">
                <span class="dashicons dashicons-wordpress" style="font-size:15px; width:15px; height:15px;"></span>
                <span class="btn-label d-none-mobile">Classic WP</span>
            </a>

            <!-- User Profile Dropdown Pill -->
            <div class="mvc-popover-wrapper" id="mvcUserProfileWrapper">
                <button type="button" class="mvc-user-pill-btn" id="mvcUserProfileBtn" aria-label="User Account Menu">
                    <div class="mvc-avatar-ring">
                        <span class="mvc-avatar-text"><?php echo esc_html($initials); ?></span>
                    </div>
                    <div class="mvc-user-meta d-none-mobile">
                        <span class="mvc-user-name"><?php echo esc_html($user_display); ?></span>
                        <span class="mvc-user-role-tag"><?php echo esc_html(strtoupper(str_replace('_', ' ', $role))); ?></span>
                    </div>
                    <span class="dashicons dashicons-arrow-down-alt2 mvc-caret-icon d-none-mobile"></span>
                </button>

                <!-- Profile Dropdown Menu -->
                <div class="mvc-popover-tray mvc-profile-tray" id="mvcProfileDropdown" style="display:none;">
                    <div class="mvc-profile-tray-header">
                        <strong class="d-block text-white"><?php echo esc_html($user_display); ?></strong>
                        <span class="text-muted" style="font-size:12px;"><?php echo esc_html($user->user_email); ?></span>
                        <div style="margin-top:4px;">
                            <span class="mvc-badge mvc-badge-publish" style="font-size:10px;"><?php echo esc_html(strtoupper(str_replace('_', ' ', $role))); ?></span>
                            <?php if ($dept_name && $dept_name !== 'Unassigned') : ?>
                                <span class="mvc-badge mvc-badge-draft" style="font-size:10px; margin-left:4px;"><?php echo esc_html($dept_name); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mvc-tray-menu">
                        <a href="<?php echo esc_url($profile_url); ?>" class="mvc-tray-item">
                            <span class="dashicons dashicons-admin-users"></span> My WordPress Profile
                        </a>
                        <a href="<?php echo esc_url($portal_url); ?>" class="mvc-tray-item" target="_blank">
                            <span class="dashicons dashicons-welcome-learn-more"></span> Scholar Portal View
                        </a>
                        <a href="<?php echo esc_url($legacy_url); ?>" class="mvc-tray-item">
                            <span class="dashicons dashicons-wordpress"></span> Switch to Classic WP Admin
                        </a>
                        <div class="mvc-tray-divider"></div>
                        <a href="<?php echo esc_url($logout_url); ?>" class="mvc-tray-item mvc-tray-danger">
                            <span class="dashicons dashicons-exit"></span> Sign Out Securely
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 2. Master Workspace Grid (Sidebar + Content Surface) -->
    <div class="mvc-cc-body">
        <!-- MASTER MODERN SIDEBAR (With ALL CMS Options) -->
        <aside class="mvc-cc-sidebar" id="mvcMasterSidebar">
            <!-- Mobile Drawer Close Header (Visible on Mobile) -->
            <div class="mvc-drawer-header d-flex-mobile">
                <div class="d-flex align-items-center gap-8">
                    <span style="font-size:20px;">🏛️</span>
                    <div>
                        <div style="font-weight:700; color:#FFF; font-size:14px;">M.V. COLLEGE CMS</div>
                        <div style="font-size:11px; color:#D4AF37;">Executive Dashboard</div>
                    </div>
                </div>
                <button type="button" class="mvc-drawer-close-btn" id="mvcMobileDrawerClose" aria-label="Close Drawer">
                    <span class="dashicons dashicons-no-alt"></span>
                </button>
            </div>

            <!-- Quick Sidebar Filter -->
            <div class="mvc-sidebar-search-box">
                <span class="dashicons dashicons-search" style="font-size:14px; width:14px; height:14px; color:var(--mvc-text-muted);"></span>
                <input type="text" id="mvcSidebarFilterInput" placeholder="Filter menu items..." autocomplete="off">
            </div>

            <!-- Scrollable Nav Groups Container -->
            <div class="mvc-sidebar-scroll">
                <!-- GROUP 1: CORE OPERATIONS -->
                <div class="mvc-nav-group">
                    <div class="mvc-nav-group-title">CORE OPERATIONS</div>
                    
                    <?php if ($is_admin || $is_cm) : ?>
                        <div class="mvc-cc-nav-item active" data-section="overview">
                            <span class="mvc-nav-icon dashicons dashicons-dashboard"></span>
                            <span class="mvc-nav-text">Executive Overview</span>
                        </div>

                        <div class="mvc-cc-nav-item" data-section="cms-hub">
                            <span class="mvc-nav-icon dashicons dashicons-grid-view"></span>
                            <span class="mvc-nav-text">CMS Content Hub</span>
                            <span class="mvc-nav-badge mvc-badge-gold">ALL</span>
                        </div>

                        <div class="mvc-cc-nav-item" data-section="review-queue">
                            <span class="mvc-nav-icon dashicons dashicons-clipboard"></span>
                            <span class="mvc-nav-text">Review Queue</span>
                            <span class="mvc-nav-badge" id="mvcNavReviewBadge" style="display:none;">0</span>
                        </div>

                        <div class="mvc-cc-nav-item" data-section="quick-publish">
                            <span class="mvc-nav-icon dashicons dashicons-megaphone"></span>
                            <span class="mvc-nav-text">Quick Dispatch Notice</span>
                            <span class="mvc-nav-badge mvc-badge-accent">⚡ FAST</span>
                        </div>
                    <?php elseif ($is_hod) : ?>
                        <div class="mvc-cc-nav-item active" data-section="my-department">
                            <span class="mvc-nav-icon dashicons dashicons-welcome-learn-more"></span>
                            <span class="mvc-nav-text">My Department (<?php echo esc_html($dept_name); ?>)</span>
                        </div>
                        <div class="mvc-cc-nav-item" data-section="quick-publish">
                            <span class="mvc-nav-icon dashicons dashicons-megaphone"></span>
                            <span class="mvc-nav-text">Submit Notice</span>
                        </div>
                        <div class="mvc-cc-nav-item" data-section="my-submissions">
                            <span class="mvc-nav-icon dashicons dashicons-text-page"></span>
                            <span class="mvc-nav-text">My Submissions</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- GROUP 2: CAMPUS CONTENT & PUBLISHING (ALL CMS CONTENT) -->
                <?php if ($is_admin || $is_cm) : ?>
                <div class="mvc-nav-group">
                    <div class="mvc-nav-group-title">CONTENT &amp; PUBLISHING</div>

                    <!-- Notices Submenu -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="notice">
                            <span class="mvc-nav-icon dashicons dashicons-bell"></span>
                            <span class="mvc-nav-text">Institutional Notices</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['notice']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <button type="button" class="mvc-nav-add-btn" onclick="document.querySelector('[data-section=quick-publish]').click();" title="Quick Publish Notice">+ Add</button>
                        </div>
                    </div>

                    <!-- Hero Slides -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="slider">
                            <span class="mvc-nav-icon dashicons dashicons-images-alt2"></span>
                            <span class="mvc-nav-text">Hero Slider Banners</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['slider']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=slider')); ?>" class="mvc-nav-add-btn" title="Add New Slide">+ Add</a>
                        </div>
                    </div>

                    <!-- Leadership -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="leadership">
                            <span class="mvc-nav-icon dashicons dashicons-businessman"></span>
                            <span class="mvc-nav-text">Leadership &amp; Principal</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['leadership']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=leadership')); ?>" class="mvc-nav-add-btn" title="Add New Leader">+ Add</a>
                        </div>
                    </div>

                    <!-- Academic Milestones -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="calendar_milestone">
                            <span class="mvc-nav-icon dashicons dashicons-awards"></span>
                            <span class="mvc-nav-text">Academic Milestones</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['calendar_milestone']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=calendar_milestone')); ?>" class="mvc-nav-add-btn" title="Add New Milestone">+ Add</a>
                        </div>
                    </div>

                    <!-- Academic Gazettes -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="calendar_gazette">
                            <span class="mvc-nav-icon dashicons dashicons-media-document"></span>
                            <span class="mvc-nav-text">Academic Gazettes</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['calendar_gazette']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=calendar_gazette')); ?>" class="mvc-nav-add-btn" title="Add New Gazette">+ Add</a>
                        </div>
                    </div>

                    <!-- Press Media -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="press-media">
                            <span class="mvc-nav-icon dashicons dashicons-media-interactive"></span>
                            <span class="mvc-nav-text">Press Releases &amp; Media</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['press-media'] ?? $counts['press_media'] ?? 0); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=press-media')); ?>" class="mvc-nav-add-btn" title="Add Press Clipping">+ Add</a>
                        </div>
                    </div>

                    <!-- Campus Events -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="event">
                            <span class="mvc-nav-icon dashicons dashicons-calendar-alt"></span>
                            <span class="mvc-nav-text">Events &amp; Seminars</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['event']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=event')); ?>" class="mvc-nav-add-btn" title="Add New Event">+ Add</a>
                        </div>
                    </div>

                    <!-- Institutional Pages -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="page">
                            <span class="mvc-nav-icon dashicons dashicons-admin-page"></span>
                            <span class="mvc-nav-text">Institutional Pages</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['page']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=page')); ?>" class="mvc-nav-add-btn" title="Add New Page">+ Add</a>
                        </div>
                    </div>

                    <!-- Media Asset Vault -->
                    <a href="<?php echo esc_url(admin_url('upload.php')); ?>" class="mvc-cc-nav-item">
                        <span class="mvc-nav-icon dashicons dashicons-admin-media"></span>
                        <span class="mvc-nav-text">Media Asset Vault</span>
                    </a>

                    <!-- Photo & Video Galleries -->
                    <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=gallery_category')); ?>" class="mvc-cc-nav-item">
                        <span class="mvc-nav-icon dashicons dashicons-format-gallery"></span>
                        <span class="mvc-nav-text">Galleries &amp; Categories</span>
                    </a>
                </div>
                <?php endif; ?>

                <!-- GROUP 3: ACADEMIC & FACULTY -->
                <div class="mvc-nav-group">
                    <div class="mvc-nav-group-title">ACADEMIC &amp; FACULTY</div>

                    <?php if ($is_admin) : ?>
                        <!-- Departments & HODs (Lock C1) -->
                        <div class="mvc-cc-nav-item" data-section="departments">
                            <span class="mvc-nav-icon dashicons dashicons-building"></span>
                            <span class="mvc-nav-text">Departments &amp; HODs</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['department']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Faculty Directory -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="faculty">
                            <span class="mvc-nav-icon dashicons dashicons-id-alt"></span>
                            <span class="mvc-nav-text">Faculty Directory</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['faculty']); ?></span>
                        </div>
                        <?php if ($is_admin || $is_cm) : ?>
                            <div class="mvc-nav-quick-actions">
                                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=faculty')); ?>" class="mvc-nav-add-btn" title="Add New Faculty">+ Add</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Study Materials -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="study_material">
                            <span class="mvc-nav-icon dashicons dashicons-book"></span>
                            <span class="mvc-nav-text">Study Materials &amp; Notes</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['study_material']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=study_material')); ?>" class="mvc-nav-add-btn" title="Add Study Material">+ Add</a>
                        </div>
                    </div>

                    <!-- Research & Publications -->
                    <div class="mvc-nav-item-has-submenu">
                        <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="research">
                            <span class="mvc-nav-icon dashicons dashicons-welcome-learn-more"></span>
                            <span class="mvc-nav-text">Research &amp; Publications</span>
                            <span class="mvc-nav-count"><?php echo esc_html($counts['research']); ?></span>
                        </div>
                        <div class="mvc-nav-quick-actions">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=research')); ?>" class="mvc-nav-add-btn" title="Add Research Publication">+ Add</a>
                        </div>
                    </div>

                    <?php if ($is_admin) : ?>
                        <!-- Committees -->
                        <div class="mvc-nav-item-has-submenu">
                            <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="committee">
                                <span class="mvc-nav-icon dashicons dashicons-groups"></span>
                                <span class="mvc-nav-text">Committees &amp; Cells</span>
                                <span class="mvc-nav-count"><?php echo esc_html($counts['committee']); ?></span>
                            </div>
                            <div class="mvc-nav-quick-actions">
                                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=committee')); ?>" class="mvc-nav-add-btn" title="Add Committee">+ Add</a>
                            </div>
                        </div>

                        <!-- Non-Teaching Staff -->
                        <div class="mvc-nav-item-has-submenu">
                            <div class="mvc-cc-nav-item mvc-nav-cpt" data-section="content-desk" data-cpt="staff_member">
                                <span class="mvc-nav-icon dashicons dashicons-networking"></span>
                                <span class="mvc-nav-text">Non-Teaching Staff</span>
                                <span class="mvc-nav-count"><?php echo esc_html($counts['staff_member']); ?></span>
                            </div>
                            <div class="mvc-nav-quick-actions">
                                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=staff_member')); ?>" class="mvc-nav-add-btn" title="Add Staff Member">+ Add</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- GROUP 4: STUDENT LIFE & GRIEVANCES -->
                <?php if ($is_admin || $is_cm) : ?>
                <div class="mvc-nav-group">
                    <div class="mvc-nav-group-title">STUDENT LIFE &amp; GRIEVANCES</div>

                    <div class="mvc-cc-nav-item" data-section="grievances">
                        <span class="mvc-nav-icon dashicons dashicons-shield-alt"></span>
                        <span class="mvc-nav-text">Grievance Redressal Cell</span>
                        <span class="mvc-nav-badge" id="mvcNavGrievanceBadge" style="display:none;">0</span>
                    </div>

                    <div class="mvc-cc-nav-item" data-section="accounts">
                        <span class="mvc-nav-icon dashicons dashicons-admin-users"></span>
                        <span class="mvc-nav-text">Scholar &amp; Alumni Accounts</span>
                        <span class="mvc-nav-badge" id="mvcNavAccountsBadge" style="display:none;">0</span>
                    </div>

                    <a href="<?php echo esc_url($portal_url); ?>" class="mvc-cc-nav-item" target="_blank">
                        <span class="mvc-nav-icon dashicons dashicons-welcome-view-site"></span>
                        <span class="mvc-nav-text">Scholar Portal Gateway</span>
                        <span class="dashicons dashicons-external" style="font-size:12px; margin-left:auto;"></span>
                    </a>
                </div>
                <?php endif; ?>

                <!-- GROUP 5: ACCREDITATION & AUDIT -->
                <?php if ($is_admin || $is_cm) : ?>
                <div class="mvc-nav-group">
                    <div class="mvc-nav-group-title">ACCREDITATION &amp; GOVERNANCE</div>

                    <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-suite')); ?>" class="mvc-cc-nav-item">
                        <span class="mvc-nav-icon dashicons dashicons-star-filled" style="color:var(--mvc-gold);"></span>
                        <span class="mvc-nav-text">NAAC &amp; IQAC Quality Suite</span>
                        <span class="mvc-nav-badge mvc-badge-gold">A++</span>
                    </a>

                    <?php if ($is_admin) : ?>
                        <div class="mvc-cc-nav-item" data-section="audit-log">
                            <span class="mvc-nav-icon dashicons dashicons-list-view"></span>
                            <span class="mvc-nav-text">Institutional Audit Log</span>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- GROUP 6: SYSTEM & CONFIGURATION -->
                <?php if ($is_admin) : ?>
                <div class="mvc-nav-group">
                    <div class="mvc-nav-group-title">SYSTEM &amp; SETTINGS</div>

                    <a href="<?php echo esc_url(admin_url('users.php')); ?>" class="mvc-cc-nav-item">
                        <span class="mvc-nav-icon dashicons dashicons-groups"></span>
                        <span class="mvc-nav-text">User Accounts &amp; Roles</span>
                    </a>

                    <a href="<?php echo esc_url(admin_url('admin.php?page=college-global-settings')); ?>" class="mvc-cc-nav-item">
                        <span class="mvc-nav-icon dashicons dashicons-admin-site-alt3"></span>
                        <span class="mvc-nav-text">College Global Settings</span>
                    </a>

                    <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-maintenance-mode')); ?>" class="mvc-cc-nav-item">
                        <span class="mvc-nav-icon dashicons dashicons-hammer"></span>
                        <span class="mvc-nav-text">Maintenance Mode Switch</span>
                        <?php if (function_exists('mvc_is_maintenance_mode_active') && mvc_is_maintenance_mode_active()) : ?>
                            <span class="mvc-badge mvc-badge-warning" style="margin-left:auto; font-size:10px; padding:2px 6px;">ON</span>
                        <?php endif; ?>
                    </a>

                    <div class="mvc-cc-nav-item" data-section="settings">
                        <span class="mvc-nav-icon dashicons dashicons-admin-generic"></span>
                        <span class="mvc-nav-text">Command Center Settings</span>
                    </div>

                    <a href="<?php echo esc_url($legacy_url); ?>" class="mvc-cc-nav-item">
                        <span class="mvc-nav-icon dashicons dashicons-wordpress"></span>
                        <span class="mvc-nav-text">Switch to Classic WP</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar Footer -->
            <div class="mvc-sidebar-footer" style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-top:1px solid var(--mvc-border);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/retailcoder-logo.png'); ?>" alt="RetailCoder StoreFront" width="30" height="30" style="border-radius:6px; object-fit:contain; background:#ffffff; padding:2px; box-shadow:0 1px 4px rgba(0,0,0,0.12); flex-shrink:0;">
                    <div class="mvc-footer-info">
                        <div style="font-weight:700; color:var(--mvc-text); font-size:12px; display:flex; align-items:center; gap:5px;">
                            <span>RetailCoder StoreFront</span>
                            <span style="font-size:9px; background:linear-gradient(135deg, #D4AF37, #B48200); color:#001A38; font-weight:800; padding:1px 5px; border-radius:3px;">RC:StoreFront</span>
                        </div>
                        <div style="font-size:11px; color:var(--mvc-text-muted);"><a href="https://retailcoder.com" target="_blank" rel="noopener" style="color:var(--mvc-text-muted); text-decoration:none;">retailcoder.com ↗</a></div>
                    </div>
                </div>
                <a href="<?php echo esc_url($logout_url); ?>" class="mvc-sidebar-logout-btn" title="Sign Out">
                    <span class="dashicons dashicons-exit"></span>
                </a>
            </div>
        </aside>

        <!-- MAIN SURFACE CONTENT PANES -->
        <main class="mvc-cc-content">
            <!-- PANE 1: OVERVIEW -->
            <div id="mvc-section-overview" class="mvc-section-pane" style="display:none;">
                <!-- Modern Welcome Banner -->
                <div class="mvc-hero-welcome-card">
                    <div class="mvc-welcome-text">
                        <div class="mvc-welcome-badge">🏛️ MAHARSHI VISHWAMITRA MAHAVIDYALAYA, BUXAR</div>
                        <h2 class="mvc-welcome-title">Welcome, <?php echo esc_html($user_display); ?></h2>
                        <p class="mvc-welcome-desc">
                            Real-time Executive Operations, Publishing Controls &amp; Accreditation Surface. 
                            Managing 15 academic departments, verified scholar rosters, and statutory compliances.
                        </p>
                    </div>
                    <div class="mvc-welcome-meta">
                        <div class="mvc-meta-pill">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <span><?php echo esc_html(date_i18n('l, j F Y')); ?></span>
                        </div>
                        <div class="mvc-meta-pill">
                            <span class="dashicons dashicons-clock"></span>
                            <span>IST: <?php echo esc_html(wp_date('g:i A')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- 4 Top KPI Metric Cards -->
                <div class="mvc-metric-grid">
                    <div class="mvc-metric-card">
                        <div class="mvc-metric-top">
                            <span class="mvc-metric-label">Pending Reviews</span>
                            <span class="mvc-metric-icon mvc-icon-amber">📬</span>
                        </div>
                        <div class="mvc-metric-val" id="mvcMetricReviewCount">0</div>
                        <div class="mvc-metric-foot">
                            <span class="mvc-foot-pill">Department Submissions</span>
                            <a href="#review-queue" onclick="document.querySelector('[data-section=review-queue]').click();" class="mvc-metric-link">Review &rarr;</a>
                        </div>
                    </div>

                    <div class="mvc-metric-card">
                        <div class="mvc-metric-top">
                            <span class="mvc-metric-label">Active Grievances</span>
                            <span class="mvc-metric-icon mvc-icon-red">⚖️</span>
                        </div>
                        <div class="mvc-metric-val" id="mvcMetricGrievanceCount">0</div>
                        <div class="mvc-metric-foot">
                            <span class="mvc-foot-pill">Redressal Desk</span>
                            <a href="#grievances" onclick="document.querySelector('[data-section=grievances]').click();" class="mvc-metric-link">Inspect &rarr;</a>
                        </div>
                    </div>

                    <div class="mvc-metric-card">
                        <div class="mvc-metric-top">
                            <span class="mvc-metric-label">Pending Accounts</span>
                            <span class="mvc-metric-icon mvc-icon-blue">👥</span>
                        </div>
                        <div class="mvc-metric-val" id="mvcMetricPendingAccounts">0</div>
                        <div class="mvc-metric-foot">
                            <span class="mvc-foot-pill">Scholar &amp; Alumni</span>
                            <a href="#accounts" onclick="document.querySelector('[data-section=accounts]').click();" class="mvc-metric-link">Verify &rarr;</a>
                        </div>
                    </div>

                    <div class="mvc-metric-card">
                        <div class="mvc-metric-top">
                            <span class="mvc-metric-label">Resolution SLA Target</span>
                            <span class="mvc-metric-icon mvc-icon-green">⏱️</span>
                        </div>
                        <div class="mvc-metric-val" style="color:var(--mvc-success);">15 Days</div>
                        <div class="mvc-metric-foot">
                            <span class="mvc-foot-pill">Statutory Benchmark</span>
                            <span style="color:var(--mvc-success); font-weight:600; font-size:12px;">100% Compliant</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Bar -->
                <div class="mvc-card mvc-quick-actions-bar">
                    <div class="mvc-card-header">
                        <h3 class="mvc-card-title">⚡ Quick Action Institutional Shortcuts</h3>
                        <span class="text-muted" style="font-size:13px;">Instant administrative operations</span>
                    </div>
                    <div class="mvc-shortcuts-grid">
                        <button type="button" class="mvc-shortcut-card" onclick="document.querySelector('[data-section=quick-publish]').click();">
                            <span class="mvc-shortcut-icon">📢</span>
                            <div class="mvc-shortcut-text">
                                <strong>Quick Dispatch Notice</strong>
                                <span>Publish gazette circular with automatic memo</span>
                            </div>
                        </button>

                        <button type="button" class="mvc-shortcut-card" onclick="document.querySelector('[data-section=cms-hub]').click();">
                            <span class="mvc-shortcut-icon">🎨</span>
                            <div class="mvc-shortcut-text">
                                <strong>CMS Content Hub</strong>
                                <span>Browse &amp; manage all 14 collegiate post types</span>
                            </div>
                        </button>

                        <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-naac-suite')); ?>" class="mvc-shortcut-card">
                            <span class="mvc-shortcut-icon">🏆</span>
                            <div class="mvc-shortcut-text">
                                <strong>NAAC Quality Suite</strong>
                                <span>Inspect criteria, SSR documentation &amp; scores</span>
                            </div>
                        </a>

                        <a href="<?php echo esc_url($portal_url); ?>" class="mvc-shortcut-card" target="_blank" rel="noopener">
                            <span class="mvc-shortcut-icon">🌐</span>
                            <div class="mvc-shortcut-text">
                                <strong>View Public Portal</strong>
                                <span>Open scholar portal &amp; front-end website</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- CMS Content Snapshot Quick Grid -->
                <div class="mvc-card">
                    <div class="mvc-card-header">
                        <div class="d-flex align-items-center gap-8">
                            <h3 class="mvc-card-title">📊 Published Collegiate Content Snapshot</h3>
                            <span class="mvc-badge mvc-badge-publish" style="font-size:11px;">LIVE</span>
                        </div>
                        <button type="button" class="mvc-btn mvc-btn-outline" style="font-size:12px; padding:4px 10px;" onclick="document.querySelector('[data-section=cms-hub]').click();">
                            View All CMS Modules &rarr;
                        </button>
                    </div>
                    <div class="mvc-snapshot-grid">
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="notice" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">📢</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['notice']); ?></span>
                            <span class="mvc-snapshot-name">Notices</span>
                        </div>
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="faculty" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">👨‍🏫</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['faculty']); ?></span>
                            <span class="mvc-snapshot-name">Faculty</span>
                        </div>
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="department" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">🏛️</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['department']); ?></span>
                            <span class="mvc-snapshot-name">Departments</span>
                        </div>
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="calendar_gazette" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">📜</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['calendar_gazette']); ?></span>
                            <span class="mvc-snapshot-name">Gazettes</span>
                        </div>
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="event" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">📅</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['event']); ?></span>
                            <span class="mvc-snapshot-name">Events</span>
                        </div>
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="press-media" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">📰</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['press-media'] ?? $counts['press_media'] ?? 0); ?></span>
                            <span class="mvc-snapshot-name">Press Clippings</span>
                        </div>
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="slider" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">🖼️</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['slider']); ?></span>
                            <span class="mvc-snapshot-name">Hero Slides</span>
                        </div>
                        <div class="mvc-snapshot-item mvc-open-desk-btn" data-cpt="page" role="button" tabindex="0">
                            <span class="mvc-snapshot-icon">📄</span>
                            <span class="mvc-snapshot-count"><?php echo esc_html($counts['page']); ?></span>
                            <span class="mvc-snapshot-name">Pages</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANE 2: CMS CONTENT HUB (The Master Launchpad for ALL CMS Options!) -->
            <div id="mvc-section-cms-hub" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <h2 class="mvc-cc-heading">🎨 Institutional CMS Content Management Suite</h2>
                        <div class="mvc-cc-desc">Centralized executive hub for all 14 collegiate custom post types, media assets, and institutional archives.</div>
                    </div>
                    <div class="d-flex gap-8 align-items-center">
                        <input type="text" id="mvcCmsHubFilter" class="mvc-input" placeholder="Filter CMS content cards..." style="max-width:260px; padding:8px 12px; font-size:13px;">
                    </div>
                </div>

                <div class="mvc-cms-hub-grid" id="mvcCmsHubCardsContainer">
                    <!-- 1. Notices -->
                    <div class="mvc-cms-card" data-keywords="notices circulars memo orders">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-amber">📢</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['notice']); ?> Live</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Institutional Notices</h3>
                        <p class="mvc-cms-card-desc">Official notifications, circulars, examination schedules, and certified memos.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="notice">Browse All</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=notice')); ?>" class="mvc-btn mvc-btn-primary">+ Add Notice</a>
                        </div>
                    </div>

                    <!-- 2. Hero Slides -->
                    <div class="mvc-cms-card" data-keywords="slider slides hero banner homepage">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-blue">🖼️</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['slider']); ?> Slides</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Hero Slider Banners</h3>
                        <p class="mvc-cms-card-desc">Homepage showcase sliders with responsive banners, action buttons, and headings.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="slider">Browse Slides</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=slider')); ?>" class="mvc-btn mvc-btn-primary">+ Add Slide</a>
                        </div>
                    </div>

                    <!-- 3. Leadership -->
                    <div class="mvc-cms-card" data-keywords="leadership principal director chancellor registrar">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-gold">🌟</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['leadership']); ?> Profiles</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Leadership &amp; Administration</h3>
                        <p class="mvc-cms-card-desc">Profiles of Principal, Vice-Chancellor, and administrative officers.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="leadership">Browse All</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=leadership')); ?>" class="mvc-btn mvc-btn-primary">+ Add Leader</a>
                        </div>
                    </div>

                    <!-- 4. Academic Milestones -->
                    <div class="mvc-cms-card" data-keywords="milestones history timeline achievements">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-purple">🏆</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['calendar_milestone']); ?> Entries</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Academic Milestones</h3>
                        <p class="mvc-cms-card-desc">Historical timeline from 1958 foundation, university affiliations, and accreditation.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="calendar_milestone">Browse All</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=calendar_milestone')); ?>" class="mvc-btn mvc-btn-primary">+ Add Milestone</a>
                        </div>
                    </div>

                    <!-- 5. Academic Gazettes -->
                    <div class="mvc-cms-card" data-keywords="gazettes syllabus curriculum orders regulations">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-red">📜</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['calendar_gazette']); ?> Gazettes</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Academic Gazettes &amp; Syllabi</h3>
                        <p class="mvc-cms-card-desc">Official syllabi documents, curriculum PDFs, academic calendars, and university gazettes.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="calendar_gazette">Browse All</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=calendar_gazette')); ?>" class="mvc-btn mvc-btn-primary">+ Add Gazette</a>
                        </div>
                    </div>

                    <!-- 6. Faculty Directory -->
                    <div class="mvc-cms-card" data-keywords="faculty professors teachers staff hod">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-green">👨‍🏫</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['faculty']); ?> Faculty</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Faculty Directory &amp; Roster</h3>
                        <p class="mvc-cms-card-desc">Professors, associate professors, research supervisors, and department faculty members.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="faculty">Browse Faculty</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=faculty')); ?>" class="mvc-btn mvc-btn-primary">+ Add Faculty</a>
                        </div>
                    </div>

                    <!-- 7. Academic Departments -->
                    <div class="mvc-cms-card" data-keywords="departments hod branches arts science commerce">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-blue">🏛️</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['department']); ?> Departments</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Academic Departments</h3>
                        <p class="mvc-cms-card-desc">All 15 undergraduate &amp; postgraduate departments with dedicated 1-to-1 HOD locks.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="department">Browse All</button>
                            <button type="button" class="mvc-btn mvc-btn-primary" onclick="document.querySelector('[data-section=departments]').click();">Manage HODs</button>
                        </div>
                    </div>

                    <!-- 8. Campus Events -->
                    <div class="mvc-cms-card" data-keywords="events seminars workshops conferences fests">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-amber">📅</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['event']); ?> Events</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Campus Events &amp; Seminars</h3>
                        <p class="mvc-cms-card-desc">Institutional calendar for workshops, sports tournaments, seminars, and convocations.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="event">Browse Events</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=event')); ?>" class="mvc-btn mvc-btn-primary">+ Add Event</a>
                        </div>
                    </div>

                    <!-- 9. Press Media -->
                    <div class="mvc-cms-card" data-keywords="press media news newspaper clippings coverage">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-purple">📰</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['press-media'] ?? $counts['press_media'] ?? 0); ?> Clippings</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Press Media &amp; Clippings</h3>
                        <p class="mvc-cms-card-desc">Newspaper releases, media coverage scans, print articles, and official briefings.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="press-media">Browse Clippings</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=press-media')); ?>" class="mvc-btn mvc-btn-primary">+ Add Clipping</a>
                        </div>
                    </div>

                    <!-- 10. Institutional Pages -->
                    <div class="mvc-cms-card" data-keywords="pages static about rti admission rules">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-blue">📄</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['page']); ?> Pages</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Institutional Static Pages</h3>
                        <p class="mvc-cms-card-desc">Core institutional web pages: About Us, Admissions, RTI Act, Code of Conduct, Campus.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="page">Browse Pages</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=page')); ?>" class="mvc-btn mvc-btn-primary">+ Add Page</a>
                        </div>
                    </div>

                    <!-- 11. Study Materials -->
                    <div class="mvc-cms-card" data-keywords="study materials notes lecture pdf downloads">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-green">📚</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['study_material']); ?> Files</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Study Materials &amp; E-Notes</h3>
                        <p class="mvc-cms-card-desc">Departmental lecture notes, syllabi modules, and e-learning resources for scholars.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="study_material">Browse All</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=study_material')); ?>" class="mvc-btn mvc-btn-primary">+ Add Material</a>
                        </div>
                    </div>

                    <!-- 11B. Research & Publications -->
                    <div class="mvc-cms-card" data-keywords="research publications journals papers articles scopus ugc care books patents">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-blue">🔬</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['research']); ?> Pubs</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Researches &amp; Publications</h3>
                        <p class="mvc-cms-card-desc">Faculty research papers, UGC-CARE / Scopus journals, books, grants, and conference proceedings.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="research">Browse All</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=research')); ?>" class="mvc-btn mvc-btn-primary">+ Add Research</a>
                        </div>
                    </div>

                    <!-- 12. Committees & Cells -->
                    <div class="mvc-cms-card" data-keywords="committees anti-ragging icc cells statutory">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-gold">🤝</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['committee']); ?> Cells</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Committees &amp; Academic Cells</h3>
                        <p class="mvc-cms-card-desc">Statutory Anti-Ragging Cell, Internal Complaints Committee (ICC), Discipline Committee.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="committee">Browse Cells</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=committee')); ?>" class="mvc-btn mvc-btn-primary">+ Add Cell</a>
                        </div>
                    </div>

                    <!-- 13. Non-Teaching Staff -->
                    <div class="mvc-cms-card" data-keywords="staff non-teaching clerical technical support">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-purple">👥</div>
                            <span class="mvc-badge mvc-badge-publish"><?php echo esc_html($counts['staff_member']); ?> Staff</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Non-Teaching Staff Roster</h3>
                        <p class="mvc-cms-card-desc">Clerical, technical, laboratory assistants, and collegiate support cadres.</p>
                        <div class="mvc-cms-card-actions">
                            <button type="button" class="mvc-btn mvc-btn-outline mvc-open-desk-btn" data-cpt="staff_member">Browse Staff</button>
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=staff_member')); ?>" class="mvc-btn mvc-btn-primary">+ Add Member</a>
                        </div>
                    </div>

                    <!-- 14. Media Asset Vault -->
                    <div class="mvc-cms-card" data-keywords="media upload images pdf library documents">
                        <div class="mvc-cms-card-top">
                            <div class="mvc-cms-card-icon mvc-icon-blue">📁</div>
                            <span class="mvc-badge mvc-badge-publish">Asset Vault</span>
                        </div>
                        <h3 class="mvc-cms-card-title">Media &amp; File Asset Vault</h3>
                        <p class="mvc-cms-card-desc">Central repository for collegiate photographs, certified circular scans, and document attachments.</p>
                        <div class="mvc-cms-card-actions">
                            <a href="<?php echo esc_url(admin_url('upload.php')); ?>" class="mvc-btn mvc-btn-outline">Open Library</a>
                            <a href="<?php echo esc_url(admin_url('media-new.php')); ?>" class="mvc-btn mvc-btn-primary">+ Upload File</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANE 3: REVIEW QUEUE -->
            <div id="mvc-section-review-queue" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <h2 class="mvc-cc-heading">📬 Editorial Review Queue</h2>
                        <div class="mvc-cc-desc">Submissions from departmental heads requiring editorial certification before going live.</div>
                    </div>
                </div>

                <div class="mvc-card">
                    <div class="mvc-table-responsive">
                        <table class="mvc-table">
                            <thead>
                                <tr>
                                    <th>Item Title</th>
                                    <th>Type</th>
                                    <th>Department</th>
                                    <th>Author</th>
                                    <th style="text-align:right;">Editorial Actions</th>
                                </tr>
                            </thead>
                            <tbody id="mvcReviewQueueList">
                                <tr><td colspan="5" style="text-align:center; padding:30px;">Loading review queue...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PANE 4: QUICK PUBLISH NOTICE -->
            <div id="mvc-section-quick-publish" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <h2 class="mvc-cc-heading">⚡ Quick Dispatch Notice Desk</h2>
                        <div class="mvc-cc-desc">Publish official gazette circulars with automated memo formatting and instant validation.</div>
                    </div>
                </div>

                <div class="mvc-card" style="max-width:820px;">
                    <form id="mvcQuickNoticeForm" style="padding:24px; display:flex; flex-direction:column; gap:18px;">
                        <div>
                            <label style="display:block; font-weight:600; margin-bottom:6px;">Notice Headline / Title *</label>
                            <input type="text" id="mvcNoticeTitle" required placeholder="e.g. Schedule for UG Semester-I Examination Form Submission" class="mvc-input">
                        </div>

                        <div class="mvc-form-grid-2col" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <label style="display:block; font-weight:600; margin-bottom:6px;">Official Memo / Dispatch Number *</label>
                                <input type="text" id="mvcNoticeMemo" required placeholder="e.g. MVC/NOT/2026/088" class="mvc-input">
                            </div>
                            <div>
                                <label style="display:block; font-weight:600; margin-bottom:6px;">Primary Audience</label>
                                <select id="mvcNoticeAudience" class="mvc-input">
                                    <option value="Students">Students &amp; Scholars</option>
                                    <option value="General">General Public</option>
                                    <option value="Officials">Officials &amp; Administration</option>
                                    <option value="Examination">Examination Division</option>
                                    <option value="Alumni">Alumni Network</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label style="display:block; font-weight:600; margin-bottom:6px;">Notice Body Text (minimum 20 characters) *</label>
                            <textarea id="mvcNoticeContent" rows="7" required placeholder="Enter official gazette order text..." class="mvc-input"></textarea>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; padding-top:12px; border-top:1px solid var(--mvc-border);">
                            <div style="font-size:12.5px; color:var(--mvc-text-muted);">
                                🔒 Automatically signed by <strong><?php echo esc_html($user_display); ?></strong>
                            </div>
                            <button type="submit" class="mvc-btn mvc-btn-primary" style="padding:10px 24px;">
                                🚀 Publish / Submit Notice
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- PANE 5: GRIEVANCE & ENQUIRIES DESK -->
            <div id="mvc-section-grievances" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <h2 class="mvc-cc-heading">⚖️ Grievance Redressal &amp; Inquiries Operations Desk</h2>
                        <div class="mvc-cc-desc">Centralized tracking, department assignment, investigation timeline, and certified resolution dispatch.</div>
                    </div>
                    <div>
                        <a href="<?php echo esc_url(admin_url('edit.php?post_type=grievance')); ?>" class="mvc-btn mvc-btn-secondary" style="font-size:12.5px;">
                            🏛️ Open in Classic Admin &rarr;
                        </a>
                    </div>
                </div>

                <!-- Grievance Filters Bar -->
                <div class="mvc-card" style="margin-bottom:18px; padding:16px 20px;">
                    <div style="display:flex; flex-wrap:wrap; gap:14px; align-items:center; justify-content:space-between;">
                        <div style="display:flex; flex-wrap:wrap; gap:8px; align-items:center;" id="mvcGrvStatusPills">
                            <button type="button" class="mvc-btn mvc-btn-primary mvc-grv-filter-pill active" data-status="all" style="padding:5px 12px; font-size:12px;">All Cases</button>
                            <button type="button" class="mvc-btn mvc-btn-secondary mvc-grv-filter-pill" data-status="logged" style="padding:5px 12px; font-size:12px;">Logged</button>
                            <button type="button" class="mvc-btn mvc-btn-secondary mvc-grv-filter-pill" data-status="in-review" style="padding:5px 12px; font-size:12px;">Under Review</button>
                            <button type="button" class="mvc-btn mvc-btn-secondary mvc-grv-filter-pill" data-status="action_taken" style="padding:5px 12px; font-size:12px;">In Progress</button>
                            <button type="button" class="mvc-btn mvc-btn-secondary mvc-grv-filter-pill" data-status="resolved" style="padding:5px 12px; font-size:12px;">Resolved</button>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                            <select id="mvcGrvDeptFilter" class="mvc-select" style="min-width:180px; font-size:12.5px; padding:6px 12px; border-radius:6px; border:1px solid var(--mvc-border);">
                                <option value="">-- All Departments --</option>
                                <?php
                                $all_depts_list = get_terms(array('taxonomy' => 'dept_link', 'hide_empty' => false, 'orderby' => 'name'));
                                if (!empty($all_depts_list) && !is_wp_error($all_depts_list)) {
                                    foreach ($all_depts_list as $d) {
                                        printf('<option value="%d">%s</option>', (int) $d->term_id, esc_html($d->name));
                                    }
                                }
                                ?>
                            </select>
                            <input type="text" id="mvcGrvSearchInput" placeholder="Search by Ref, Submitter, Subject..." style="font-size:12.5px; padding:6px 14px; border-radius:6px; border:1px solid var(--mvc-border); min-width:220px;">
                        </div>
                    </div>
                </div>

                <div class="mvc-card">
                    <div class="mvc-table-responsive">
                        <table class="mvc-table">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Submitter</th>
                                    <th>Subject / Category</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>SLA Target</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="mvcGrievancesList">
                                <tr><td colspan="7" style="text-align:center; padding:30px;">Loading grievances &amp; enquiries...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PANE 6: SCHOLAR ACCOUNTS -->
            <div id="mvc-section-accounts" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <h2 class="mvc-cc-heading">👥 Scholar &amp; Alumni Accounts Desk</h2>
                        <div class="mvc-cc-desc">Verify enrollments, approve alumni registrations, and manage account lifecycle statuses.</div>
                    </div>
                </div>

                <div class="mvc-card">
                    <div class="mvc-table-responsive">
                        <table class="mvc-table">
                            <thead>
                                <tr>
                                    <th>Name &amp; Email</th>
                                    <th>Username / Roll</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th style="text-align:right;">Lifecycle Actions</th>
                                </tr>
                            </thead>
                            <tbody id="mvcAccountsList">
                                <tr><td colspan="5" style="text-align:center; padding:30px;">Loading accounts...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PANE 7: DEPARTMENTS & HEADS (C1 LOCK) -->
            <div id="mvc-section-departments" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <h2 class="mvc-cc-heading">🏛️ Departments &amp; Heads of Department (C1 Concurrency Lock)</h2>
                        <div class="mvc-cc-desc">Strict 1-to-1 Head of Department assignments enforced across all 15 institutional branches.</div>
                    </div>
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=department')); ?>" class="mvc-btn mvc-btn-primary">
                        + Manage Department Pages
                    </a>
                </div>

                <div class="mvc-card">
                    <div style="padding:24px;">
                        <div class="d-flex align-items-center gap-12" style="margin-bottom:16px;">
                            <span style="font-size:32px;">🔒</span>
                            <div>
                                <h4 style="margin:0 0 4px; font-size:16px;">One-to-One Atomic Concurrency Lock Active</h4>
                                <!-- <p style="margin:0; font-size:13.5px; color:var(--mvc-text-muted);">
                                    All 15 academic departments have unique <code>dept_link</code> taxonomy term IDs. 
                                    Department head changes use atomic MySQL named locks (<code>GET_LOCK</code>) to eliminate multiple head assignments.
                                </p> -->
                            </div>
                        </div>

                        <div class="mvc-dept-tags-grid">
                            <span class="mvc-dept-pill">Physics</span>
                            <span class="mvc-dept-pill">Chemistry</span>
                            <span class="mvc-dept-pill">Mathematics</span>
                            <span class="mvc-dept-pill">Botany</span>
                            <span class="mvc-dept-pill">Zoology</span>
                            <span class="mvc-dept-pill">History</span>
                            <span class="mvc-dept-pill">Political Science</span>
                            <span class="mvc-dept-pill">Economics</span>
                            <span class="mvc-dept-pill">Philosophy</span>
                            <span class="mvc-dept-pill">Psychology</span>
                            <span class="mvc-dept-pill">Hindi</span>
                            <span class="mvc-dept-pill">English</span>
                            <span class="mvc-dept-pill">Sanskrit</span>
                            <span class="mvc-dept-pill">Computer Applications (BCA)</span>
                            <span class="mvc-dept-pill">Business Administration (BBA)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANE 8: AUDIT LOG -->
            <!-- PANE 8: AUDIT LOG (Universal Change Capture & 30-Day Retention) -->
            <div id="mvc-section-audit-log" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
                            <h2 class="mvc-cc-heading" style="margin:0;">📜 Institutional Security &amp; Operations Audit Log</h2>
                            <span class="mvc-badge mvc-badge-publish" style="font-size:12px; font-weight:700;">🛡️ 30-Day Rolling Retention</span>
                        </div>
                        <div class="mvc-cc-desc">Comprehensive immutable record capturing all changes (content, users, metadata, media, taxonomies, settings, security). Records older than 30 days are automatically purged.</div>
                    </div>
                </div>

                <!-- Audit Toolbar -->
                <div class="mvc-card" style="margin-bottom:16px; padding:16px 20px;">
                    <div class="mvc-audit-toolbar-box" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; flex:1;">
                            <input type="text" id="mvcAuditSearch" class="mvc-input" placeholder="Search by action, keyword, IP, or details..." style="max-width:300px; min-height:40px;">
                            <select id="mvcAuditFilter" class="mvc-select" style="max-width:230px; min-height:40px;">
                                <option value="">All Changes &amp; Events</option>
                                <option value="content">Content (Posts/Pages/Notices)</option>
                                <option value="meta">Custom Fields &amp; Metadata</option>
                                <option value="users">Users, Roles &amp; Accounts</option>
                                <option value="media">Media &amp; Attachments</option>
                                <option value="taxonomies">Taxonomies &amp; Departments</option>
                                <option value="settings">Settings &amp; Options</option>
                                <option value="security">Security &amp; Logins</option>
                            </select>
                            <button type="button" id="mvcAuditRefreshBtn" class="mvc-btn mvc-btn-secondary" style="min-height:40px;">Refresh 🔄</button>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <button type="button" id="mvcAuditPurgeBtn" class="mvc-btn mvc-btn-secondary" style="border:1px solid #cbd5e1; min-height:40px; color:#800000; font-weight:700;">Purge &gt; 30 Days 🧹</button>
                            <a href="<?php echo esc_url(admin_url('admin-post.php?action=mvc_export_audit')); ?>" class="mvc-btn mvc-btn-primary" style="min-height:40px;">Export CSV 📥</a>
                        </div>
                    </div>
                </div>

                <div class="mvc-card">
                    <div class="mvc-table-responsive">
                        <table class="mvc-table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                    <th>Actor</th>
                                    <th>Target</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody id="mvcAuditLogList">
                                <tr><td colspan="5" style="text-align:center; padding:30px;">Loading audit logs...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PANE 9: MY DEPARTMENT (HOD OPERATIONS COMMAND DECK) -->
            <div id="mvc-section-my-department" class="mvc-section-pane" style="display:none;">
                <?php
                // Resolve authenticated HOD's department
                $hod_dept_term_id = (int) get_user_meta($user->ID, 'mvc_dept_term_id', true);
                if (!$hod_dept_term_id) {
                    $assigned_p = (int) get_user_meta($user->ID, 'mvc_assigned_department', true);
                    if ($assigned_p) {
                        $terms = wp_get_object_terms($assigned_p, 'dept_link');
                        if (!empty($terms) && !is_wp_error($terms)) {
                            $hod_dept_term_id = $terms[0]->term_id;
                        }
                    }
                }
                $hod_dept_term = $hod_dept_term_id ? get_term($hod_dept_term_id, 'dept_link') : null;
                $hod_dept_slug = ($hod_dept_term && !is_wp_error($hod_dept_term)) ? $hod_dept_term->slug : ($is_hod ? 'physics' : 'physics');
                $hod_dept_name = ($hod_dept_term && !is_wp_error($hod_dept_term)) ? $hod_dept_term->name : 'Department of Physics';
                $dept_name     = $hod_dept_name;

                // Locate Department CPT Post
                $dept_post = get_page_by_path($hod_dept_slug, OBJECT, 'department');
                $dept_post_id = $dept_post ? $dept_post->ID : 0;

                // Formatted Institutional Meta
                $raw_est = $dept_post_id ? get_field('establishment_year', $dept_post_id) : 'UG1970';
                $clean_est = function_exists('mvc_clean_academic_year') ? mvc_clean_academic_year($raw_est) : $raw_est;

                $raw_seats = $dept_post_id ? get_field('sanctioned_seats', $dept_post_id) : 'UG200';
                $clean_seats = function_exists('mvc_clean_sanctioned_seats') ? mvc_clean_sanctioned_seats($raw_seats) : $raw_seats;

                $fg_terms = $dept_post_id ? wp_get_object_terms($dept_post_id, 'faculty-group') : array();
                $faculty_group_name = (!empty($fg_terms) && !is_wp_error($fg_terms)) ? $fg_terms[0]->name : 'Faculty of Science';

                $hindi_name = function_exists('mvc_get_department_hindi_name') ? mvc_get_department_hindi_name($hod_dept_slug) : 'शैक्षणिक विभाग';

                $dept_permalink = $dept_post_id ? get_permalink($dept_post_id) : home_url('/department/' . $hod_dept_slug . '/');
                $dept_edit_url  = $dept_post_id ? admin_url('post.php?post=' . $dept_post_id . '&action=edit') : admin_url('edit.php?post_type=department');
                $new_notice_url = admin_url('post-new.php?post_type=notice');
                $new_faculty_url= admin_url('post-new.php?post_type=faculty');
                $new_study_url  = admin_url('post-new.php?post_type=study_material');

                // Query Faculty Scholars for this Department
                $hod_faculty_posts = get_posts(array(
                    'post_type'      => 'faculty',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'dept_link',
                            'field'    => 'slug',
                            'terms'    => $hod_dept_slug,
                        ),
                    ),
                    'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
                ));
                $hod_faculty_count = count($hod_faculty_posts);

                // Query Department Notices
                $hod_notices_posts = get_posts(array(
                    'post_type'      => 'notice',
                    'posts_per_page' => 5,
                    'post_status'    => array('publish', 'pending', 'draft'),
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'dept_link',
                            'field'    => 'slug',
                            'terms'    => $hod_dept_slug,
                        ),
                    ),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));
                $hod_notices_count = count($hod_notices_posts);

                // Query Department Study Materials
                $hod_study_posts = get_posts(array(
                    'post_type'      => 'study_material',
                    'posts_per_page' => 5,
                    'post_status'    => array('publish', 'pending', 'draft'),
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'dept_link',
                            'field'    => 'slug',
                            'terms'    => $hod_dept_slug,
                        ),
                    ),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));
                $hod_study_count = count($hod_study_posts);

                // Compliance Audit Points
                $has_about = $dept_post_id ? !empty(get_field('about_text', $dept_post_id)) : false;
                $has_seats = $dept_post_id ? !empty(get_field('sanctioned_seats', $dept_post_id)) : false;
                $has_est   = $dept_post_id ? !empty(get_field('establishment_year', $dept_post_id)) : false;
                $has_syl   = $dept_post_id ? !empty(get_field('syllabus', $dept_post_id)) : false;
                $has_co    = $dept_post_id ? !empty(get_field('outcomes', $dept_post_id)) : false;
                $has_sm    = $dept_post_id ? !empty(get_field('study_material', $dept_post_id)) : false;

                $compliance_points = ($has_about ? 1 : 0) + ($has_seats ? 1 : 0) + ($has_est ? 1 : 0) + ($has_syl ? 1 : 0) + ($has_co ? 1 : 0) + ($hod_faculty_count > 0 ? 1 : 0);
                $compliance_pct = round(($compliance_points / 6) * 100);
                ?>

                <div id="mvcMyDeptSummary" class="mvc-hod-deck-rendered mvc-hod-dashboard-root">
                    <!-- 1. EXECUTIVE DOSSIER HERO BANNER -->
                    <div class="mvc-hod-hero-dossier" style="background: linear-gradient(135deg, #00152e 0%, #002147 55%, #083b77 100%); border: 1px solid rgba(212, 175, 55, 0.45); border-radius: 16px; padding: 28px 32px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0, 33, 71, 0.25); color: #ffffff; position: relative; overflow: hidden;">
                        <!-- Subtle Gold Accent Glow -->
                        <div style="position: absolute; top: -50px; right: -50px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(212,175,55,0.18) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

                        <div class="mvc-hod-hero-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; flex-wrap: wrap; position: relative; z-index: 2;">
                            <div class="mvc-hod-hero-title-block" style="display: flex; gap: 20px; align-items: center; max-width: 820px;">
                                <div style="width: 64px; height: 64px; border-radius: 14px; background: rgba(212, 175, 55, 0.15); border: 2px solid #D4AF37; display: flex; align-items: center; justify-content: center; font-size: 30px; flex-shrink: 0; box-shadow: 0 4px 14px rgba(0,0,0,0.3);">
                                    🏛️
                                </div>
                                <div>
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                                        <span style="font-size: 11.5px; font-weight: 800; letter-spacing: 1px; color: #D4AF37; text-transform: uppercase;">M.V. COLLEGE BUXAR • ACADEMIC COMMAND DECK</span>
                                        <span style="background: #10B981; color: #ffffff; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                            <span style="width: 6px; height: 6px; background: #ffffff; border-radius: 50%; display: inline-block;"></span> Active Scoped HOD
                                        </span>
                                    </div>
                                    <h1 class="mvc-hod-hero-title" style="margin: 0 0 6px 0; font-size: 26px; font-weight: 800; color: #FFFFFF; font-family: var(--mvc-font-heading); display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                        <span><?php echo esc_html($dept_name); ?></span>
                                        <span style="font-size: 18px; font-weight: 600; color: #FFE082;">(<?php echo esc_html($hindi_name); ?>)</span>
                                    </h1>
                                    <p style="margin: 0 0 12px 0; font-size: 13.5px; color: #E2E8F0; line-height: 1.5;">
                                        Executive Department Operations &amp; Scholarly Governance Workspace. Direct row-level control over faculty rosters, circulars, NEP-2020 syllabus outlines, and student study notes.
                                    </p>

                                    <!-- Pill Badges -->
                                    <div class="mvc-hod-hero-badges" style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <span style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #F8FAFC; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                                            🔬 <?php echo esc_html($faculty_group_name); ?>
                                        </span>
                                        <span style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #F8FAFC; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                                            📅 <?php echo esc_html($clean_est); ?>
                                        </span>
                                        <span style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #F8FAFC; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                                            🎓 <?php echo esc_html($clean_seats); ?>
                                        </span>
                                        <span style="background: rgba(212,175,55,0.18); border: 1px solid rgba(212,175,55,0.5); color: #FFE082; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                            📜 NEP-2020 &amp; CBCS Aligned
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Top CTAs -->
                            <div class="mvc-hod-hero-cta-group" style="display: flex; gap: 10px; align-items: center; flex-shrink: 0;">
                                <a href="<?php echo esc_url($dept_permalink); ?>" target="_blank" rel="noopener" class="mvc-btn" style="background: rgba(255,255,255,0.12); color: #FFFFFF; border: 1px solid rgba(255,255,255,0.3); font-weight: 700; font-size: 13px; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;">
                                    <span>🌐 View Public Portal</span>
                                    <span style="font-size: 14px;">↗</span>
                                </a>
                                <a href="<?php echo esc_url($dept_edit_url); ?>" class="mvc-btn" style="background: linear-gradient(135deg, #D4AF37, #B48200); color: #001A38; border: none; font-weight: 800; font-size: 13px; padding: 10px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(212,175,55,0.3); transition: 0.2s;">
                                    <span>✏️ Edit Department Profile</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 2. 4-KPI METRIC BENTO GRID -->
                    <div class="mvc-metric-grid" style="margin-bottom: 24px;">
                        <!-- KPI 1: Faculty Scholars -->
                        <div class="mvc-metric-card" style="border-top: 3px solid #002147;">
                            <div class="mvc-metric-top">
                                <span class="mvc-metric-label">Faculty Scholars</span>
                                <span class="mvc-metric-icon mvc-icon-blue">👨‍🏫</span>
                            </div>
                            <div class="mvc-metric-val"><?php echo (int) $hod_faculty_count; ?></div>
                            <div class="mvc-metric-foot">
                                <span class="mvc-foot-pill">Active Research Educators</span>
                                <a href="<?php echo esc_url(admin_url('edit.php?post_type=faculty')); ?>" class="mvc-metric-link">Manage Roster &rarr;</a>
                            </div>
                        </div>

                        <!-- KPI 2: Circulars & Notices -->
                        <div class="mvc-metric-card" style="border-top: 3px solid #800000;">
                            <div class="mvc-metric-top">
                                <span class="mvc-metric-label">Dept Circulars</span>
                                <span class="mvc-metric-icon mvc-icon-amber">📢</span>
                            </div>
                            <div class="mvc-metric-val"><?php echo (int) $hod_notices_count; ?></div>
                            <div class="mvc-metric-foot">
                                <span class="mvc-foot-pill">Exam &amp; Lab Notices</span>
                                <a href="<?php echo esc_url($new_notice_url); ?>" class="mvc-metric-link">+ New Notice &rarr;</a>
                            </div>
                        </div>

                        <!-- KPI 3: Study Materials & Notes -->
                        <div class="mvc-metric-card" style="border-top: 3px solid #10B981;">
                            <div class="mvc-metric-top">
                                <span class="mvc-metric-label">Study Notes Vault</span>
                                <span class="mvc-metric-icon mvc-icon-green">📚</span>
                            </div>
                            <div class="mvc-metric-val"><?php echo (int) $hod_study_count; ?></div>
                            <div class="mvc-metric-foot">
                                <span class="mvc-foot-pill">Digital Lecture Resources</span>
                                <a href="<?php echo esc_url($new_study_url); ?>" class="mvc-metric-link">+ Add Materials &rarr;</a>
                            </div>
                        </div>

                        <!-- KPI 4: Sanctioned Capacity -->
                        <div class="mvc-metric-card" style="border-top: 3px solid #D4AF37;">
                            <div class="mvc-metric-top">
                                <span class="mvc-metric-label">Intake Capacity</span>
                                <span class="mvc-metric-icon mvc-icon-gold">🎯</span>
                            </div>
                            <div class="mvc-metric-val" style="font-size: 26px;"><?php echo esc_html(str_replace(' Seats', '', $clean_seats)); ?></div>
                            <div class="mvc-metric-foot">
                                <span class="mvc-foot-pill">University Approved Intake</span>
                                <a href="<?php echo esc_url(home_url('/department-details/')); ?>" target="_blank" rel="noopener" class="mvc-metric-link">Seat Matrix &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. HIGH-PRIORITY QUICK ACTION DISPATCH STATION (4 CARDS) -->
                    <div style="margin-bottom: 24px;">
                        <div style="font-size: 15px; font-weight: 800; color: var(--mvc-navy); font-family: var(--mvc-font-heading); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <span>⚡ Executive Department Operations &amp; Quick Dispatch</span>
                        </div>
                        <div class="mvc-shortcuts-grid" style="padding: 0; gap: 16px;">
                            <!-- Action 1: Draft Department Notice -->
                            <a href="<?php echo esc_url($new_notice_url); ?>" class="mvc-shortcut-card" style="padding: 18px 20px; border-left: 4px solid var(--mvc-navy); background: var(--mvc-card-bg);">
                                <div class="mvc-shortcut-icon" style="background: rgba(0, 33, 71, 0.08); font-size: 26px; width: 48px; height: 48px;">📢</div>
                                <div class="mvc-shortcut-text">
                                    <strong style="font-size: 14.5px;">Publish Department Circular</strong>
                                    <span style="font-size: 12px; color: var(--mvc-text-muted); line-height: 1.4; margin-top: 2px;">Issue internal exam schedules, practical viva alerts, lab instructions, or departmental seminars directly to students.</span>
                                </div>
                            </a>

                            <!-- Action 2: Add Faculty Scholar -->
                            <a href="<?php echo esc_url($new_faculty_url); ?>" class="mvc-shortcut-card" style="padding: 18px 20px; border-left: 4px solid #10B981; background: var(--mvc-card-bg);">
                                <div class="mvc-shortcut-icon" style="background: rgba(16, 185, 129, 0.1); font-size: 26px; width: 48px; height: 48px;">👨‍🏫</div>
                                <div class="mvc-shortcut-text">
                                    <strong style="font-size: 14.5px;">Add Faculty Scholar</strong>
                                    <span style="font-size: 12px; color: var(--mvc-text-muted); line-height: 1.4; margin-top: 2px;">Add new educators, update scholarly qualifications, designations, research papers, and designate HOD ribbons.</span>
                                </div>
                            </a>

                            <!-- Action 3: Upload Study Materials -->
                            <a href="<?php echo esc_url($new_study_url); ?>" class="mvc-shortcut-card" style="padding: 18px 20px; border-left: 4px solid #3B82F6; background: var(--mvc-card-bg);">
                                <div class="mvc-shortcut-icon" style="background: rgba(59, 130, 246, 0.1); font-size: 26px; width: 48px; height: 48px;">📚</div>
                                <div class="mvc-shortcut-text">
                                    <strong style="font-size: 14.5px;">Upload Course Materials</strong>
                                    <span style="font-size: 12px; color: var(--mvc-text-muted); line-height: 1.4; margin-top: 2px;">Publish lecture notes, reference PDFs, semester question banks, and laboratory manuals into the digital vault.</span>
                                </div>
                            </a>

                            <!-- Action 4: Edit Department Profile & Syllabus -->
                            <a href="<?php echo esc_url($dept_edit_url); ?>" class="mvc-shortcut-card" style="padding: 18px 20px; border-left: 4px solid var(--mvc-gold); background: var(--mvc-card-bg);">
                                <div class="mvc-shortcut-icon" style="background: rgba(212, 175, 55, 0.15); font-size: 26px; width: 48px; height: 48px;">📖</div>
                                <div class="mvc-shortcut-text">
                                    <strong style="font-size: 14.5px;">Curriculum &amp; NAAC Outcomes</strong>
                                    <span style="font-size: 12px; color: var(--mvc-text-muted); line-height: 1.4; margin-top: 2px;">Update Program Outcomes (POs), Course Outcomes (COs), foundational ethos story, and official VKSU syllabi.</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 4. TWO-COLUMN SPLIT: FACULTY ROSTER (LEFT) & NOTICES STREAM (RIGHT) -->
                    <div class="mvc-hod-split-grid" style="display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(0, 1fr); gap: 24px; margin-bottom: 24px;">
                        <!-- LEFT COLUMN: FACULTY SCHOLARS ROSTER -->
                        <div class="mvc-card" style="padding: 22px; display: flex; flex-direction: column;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--mvc-border);">
                                <div>
                                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: var(--mvc-navy); font-family: var(--mvc-font-heading);">
                                        👨‍🏫 Department Faculty Roster (<?php echo (int) $hod_faculty_count; ?>)
                                    </h3>
                                    <div style="font-size: 12px; color: var(--mvc-text-muted); margin-top: 2px;">Faculty educators registered under <?php echo esc_html($dept_name); ?></div>
                                </div>
                                <a href="<?php echo esc_url($new_faculty_url); ?>" class="mvc-btn mvc-btn-primary" style="font-size: 12px; padding: 6px 12px; font-weight: 700; border-radius: 6px; text-decoration: none;">
                                    + Add Scholar
                                </a>
                            </div>

                            <?php if (!empty($hod_faculty_posts)) : ?>
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    <?php foreach ($hod_faculty_posts as $fac_item) : 
                                        $f_id = $fac_item->ID;
                                        $desig = get_field('designation', $f_id) ?: get_post_meta($f_id, 'designation', true) ?: 'Faculty Scholar';
                                        $qual  = get_field('qualifications', $f_id) ?: get_post_meta($f_id, 'qualifications', true) ?: 'Academic Scholar';
                                        $is_head = get_field('is_department_head', $f_id) || get_post_meta($f_id, 'is_department_head', true);
                                        $f_thumb = get_the_post_thumbnail_url($f_id, 'thumbnail');
                                        $monogram = function_exists('mvc_get_faculty_monogram') ? mvc_get_faculty_monogram($fac_item->post_title) : 'DR';
                                        $edit_fac_url = admin_url('post.php?post=' . $f_id . '&action=edit');
                                    ?>
                                        <div class="mvc-hod-roster-row" style="display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 12px 14px; border: 1px solid var(--mvc-border); border-radius: 8px; background: var(--mvc-bg-subtle, #F8FAFC); transition: 0.2s;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <?php if ($f_thumb) : ?>
                                                    <img src="<?php echo esc_url($f_thumb); ?>" alt="<?php echo esc_attr($fac_item->post_title); ?>" width="44" height="44" style="border-radius: 50%; object-fit: cover; border: 2px solid <?php echo $is_head ? '#D4AF37' : 'var(--mvc-border)'; ?>;">
                                                <?php else : ?>
                                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--mvc-navy); color: #FFE082; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; border: 2px solid <?php echo $is_head ? '#D4AF37' : '#334E68'; ?>;">
                                                        <?php echo esc_html($monogram); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div style="font-weight: 700; font-size: 14px; color: var(--mvc-text); display: flex; align-items: center; gap: 6px;">
                                                        <span><?php echo esc_html($fac_item->post_title); ?></span>
                                                        <?php if ($is_head) : ?>
                                                            <span style="background: linear-gradient(135deg, #D4AF37, #B48200); color: #001A38; font-size: 9.5px; font-weight: 800; padding: 1px 6px; border-radius: 4px;">👑 HOD</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div style="font-size: 12px; color: var(--mvc-text-muted); margin-top: 1px;">
                                                        <?php echo esc_html($desig); ?> • <span style="color: var(--mvc-navy); font-weight: 600;"><?php echo esc_html($qual); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="<?php echo esc_url($edit_fac_url); ?>" class="mvc-btn mvc-btn-outline" style="font-size: 11.5px; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-weight: 600; flex-shrink: 0;">
                                                ✏️ Edit
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div style="text-align: center; padding: 30px 16px; color: var(--mvc-text-muted);">
                                    <div style="font-size: 32px; margin-bottom: 8px;">👨‍🏫</div>
                                    <div style="font-weight: 700; color: var(--mvc-text); margin-bottom: 4px;">No faculty scholars tagged to <?php echo esc_html($dept_name); ?></div>
                                    <div style="font-size: 12.5px; margin-bottom: 14px;">Add your department's educators and researchers to feature them on the public portal.</div>
                                    <a href="<?php echo esc_url($new_faculty_url); ?>" class="mvc-btn mvc-btn-primary" style="font-size: 12.5px; text-decoration: none; font-weight: 700;">+ Add Faculty Member</a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- RIGHT COLUMN: RECENT NOTICES & CIRCULARS STREAM -->
                        <div class="mvc-card" style="padding: 22px; display: flex; flex-direction: column;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--mvc-border);">
                                <div>
                                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: var(--mvc-navy); font-family: var(--mvc-font-heading);">
                                        📢 Recent Department Circulars (<?php echo (int) $hod_notices_count; ?>)
                                    </h3>
                                    <div style="font-size: 12px; color: var(--mvc-text-muted); margin-top: 2px;">Official notices and schedules issued for students</div>
                                </div>
                                <a href="<?php echo esc_url($new_notice_url); ?>" class="mvc-btn mvc-btn-primary" style="font-size: 12px; padding: 6px 12px; font-weight: 700; border-radius: 6px; text-decoration: none;">
                                    + Draft Notice
                                </a>
                            </div>

                            <?php if (!empty($hod_notices_posts)) : ?>
                                <div style="display: flex; flex-direction: column; gap: 10px;">
                                    <?php foreach ($hod_notices_posts as $n_item) : 
                                        $n_id = $n_item->ID;
                                        $n_date = get_the_date('j M Y', $n_id);
                                        $pdf_file = get_field('pdf_file', $n_id);
                                        $edit_n_url = admin_url('post.php?post=' . $n_id . '&action=edit');
                                    ?>
                                        <div class="mvc-hod-notice-row" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; border: 1px solid var(--mvc-border); border-radius: 8px; background: var(--mvc-bg-subtle, #F8FAFC);">
                                            <div style="min-width: 0;">
                                                <div style="font-size: 11.5px; color: var(--mvc-text-muted); margin-bottom: 2px;">
                                                    📅 <?php echo esc_html($n_date); ?>
                                                </div>
                                                <div style="font-weight: 700; font-size: 13.5px; color: var(--mvc-text); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                                    <?php echo esc_html($n_item->post_title); ?>
                                                </div>
                                                <?php if ($pdf_file) : ?>
                                                    <span style="display: inline-block; font-size: 11px; color: #10B981; font-weight: 600; margin-top: 2px;">
                                                        📎 Scanned Memo Attached
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <a href="<?php echo esc_url($edit_n_url); ?>" class="mvc-btn mvc-btn-outline" style="font-size: 11.5px; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-weight: 600; flex-shrink: 0;">
                                                ✏️ Edit
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div style="text-align: center; padding: 30px 16px; color: var(--mvc-text-muted); background: var(--mvc-bg-subtle, #F8FAFC); border-radius: 8px; border: 1px dashed var(--mvc-border);">
                                    <div style="font-size: 32px; margin-bottom: 8px;">📢</div>
                                    <div style="font-weight: 700; color: var(--mvc-text); margin-bottom: 4px;">No departmental notices posted yet</div>
                                    <div style="font-size: 12.5px; margin-bottom: 14px;">Publish an internal CIA examination schedule, practical viva dates, or departmental seminar notification.</div>
                                    <a href="<?php echo esc_url($new_notice_url); ?>" class="mvc-btn mvc-btn-primary" style="font-size: 12.5px; text-decoration: none; font-weight: 700;">+ Post Department Notice</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 5. DEPARTMENT PROFILE & NAAC CRITERION II COMPLIANCE HEALTH CHECK -->
                    <div class="mvc-card" style="padding: 24px; margin-bottom: 24px; border-left: 4px solid var(--mvc-navy);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: var(--mvc-navy); font-family: var(--mvc-font-heading); display: flex; align-items: center; gap: 8px;">
                                    <span>🏆 Department Profile Completeness &amp; NAAC Criterion II Health Check</span>
                                </h3>
                                <div style="font-size: 12.5px; color: var(--mvc-text-muted); margin-top: 2px;">
                                    Statutory accreditation disclosures for Veer Kunwar Singh University (VKSU) &amp; NAAC SSR inspection.
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="text-align: right;">
                                    <span style="font-size: 18px; font-weight: 800; color: <?php echo $compliance_pct >= 80 ? '#10B981' : '#F59E0B'; ?>;"><?php echo $compliance_pct; ?>% Complete</span>
                                </div>
                                <a href="<?php echo esc_url($dept_edit_url); ?>" class="mvc-btn mvc-btn-primary" style="font-size: 12.5px; padding: 8px 14px; font-weight: 700; border-radius: 6px; text-decoration: none;">
                                    ✏️ Update Department Records
                                </a>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div style="width: 100%; height: 8px; background: var(--mvc-border); border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                            <div style="width: <?php echo $compliance_pct; ?>%; height: 100%; background: linear-gradient(90deg, #10B981, #059669); border-radius: 10px; transition: width 0.4s ease;"></div>
                        </div>

                        <!-- 6-Point Checklist Grid -->
                        <div class="mvc-hod-naac-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px;">
                            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--mvc-bg-subtle, #F8FAFC); border: 1px solid var(--mvc-border); border-radius: 8px;">
                                <span style="font-size: 18px;"><?php echo $has_about ? '✅' : '⚠️'; ?></span>
                                <div>
                                    <div style="font-weight: 700; font-size: 13px; color: var(--mvc-text);">Foundations &amp; Scholarly Ethos</div>
                                    <div style="font-size: 11.5px; color: var(--mvc-text-muted);"><?php echo $has_about ? 'Configured in ACF' : 'Pending Description Prose'; ?></div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--mvc-bg-subtle, #F8FAFC); border: 1px solid var(--mvc-border); border-radius: 8px;">
                                <span style="font-size: 18px;"><?php echo $has_seats ? '✅' : '⚠️'; ?></span>
                                <div>
                                    <div style="font-weight: 700; font-size: 13px; color: var(--mvc-text);">Sanctioned Seat Capacity</div>
                                    <div style="font-size: 11.5px; color: var(--mvc-text-muted);"><?php echo $has_seats ? esc_html($clean_seats) : 'Pending Seat Allocation'; ?></div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--mvc-bg-subtle, #F8FAFC); border: 1px solid var(--mvc-border); border-radius: 8px;">
                                <span style="font-size: 18px;"><?php echo $has_est ? '✅' : '⚠️'; ?></span>
                                <div>
                                    <div style="font-weight: 700; font-size: 13px; color: var(--mvc-text);">Establishment Record</div>
                                    <div style="font-size: 11.5px; color: var(--mvc-text-muted);"><?php echo $has_est ? esc_html($clean_est) : 'Pending Year'; ?></div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--mvc-bg-subtle, #F8FAFC); border: 1px solid var(--mvc-border); border-radius: 8px;">
                                <span style="font-size: 18px;"><?php echo $has_syl ? '✅' : '⚠️'; ?></span>
                                <div>
                                    <div style="font-weight: 700; font-size: 13px; color: var(--mvc-text);">NEP 2020 Course Syllabus</div>
                                    <div style="font-size: 11.5px; color: var(--mvc-text-muted);"><?php echo $has_syl ? 'Official Syllabus Linked' : 'Pending Syllabus Attachment'; ?></div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--mvc-bg-subtle, #F8FAFC); border: 1px solid var(--mvc-border); border-radius: 8px;">
                                <span style="font-size: 18px;"><?php echo $has_co ? '✅' : '⚠️'; ?></span>
                                <div>
                                    <div style="font-weight: 700; font-size: 13px; color: var(--mvc-text);">Learning Outcomes (POs / COs)</div>
                                    <div style="font-size: 11.5px; color: var(--mvc-text-muted);"><?php echo $has_co ? 'NAAC Criterion II Aligned' : 'Pending Outcomes Articulation'; ?></div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--mvc-bg-subtle, #F8FAFC); border: 1px solid var(--mvc-border); border-radius: 8px;">
                                <span style="font-size: 18px;"><?php echo $hod_faculty_count > 0 ? '✅' : '⚠️'; ?></span>
                                <div>
                                    <div style="font-weight: 700; font-size: 13px; color: var(--mvc-text);">Faculty Roster &amp; Head Tag</div>
                                    <div style="font-size: 11.5px; color: var(--mvc-text-muted);"><?php echo $hod_faculty_count > 0 ? $hod_faculty_count . ' Faculty Scholars Registered' : 'Zero Faculty Tagged'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. STRICT SECURITY & TENANT ISOLATION AUDIT SEAL -->
                    <div style="background: rgba(0, 33, 71, 0.03); border: 1px solid var(--mvc-border); border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 20px;">🛡️</span>
                            <span style="font-size: 12.5px; color: var(--mvc-text-muted);">
                                <strong>Strict Multi-Tenant Row-Level Isolation Active:</strong> Authenticated under Department Head authority for <strong><?php echo esc_html($dept_name); ?></strong>. Content creation, faculty updates, and student circulars are securely scoped to this collegiate branch pursuant to VKSU Governance Directives.
                            </span>
                        </div>
                        <a href="<?php echo esc_url($dept_permalink); ?>" target="_blank" rel="noopener" style="font-size: 12px; font-weight: 700; color: var(--mvc-navy); text-decoration: none; flex-shrink: 0;">
                            Public Verification ↗
                        </a>
                    </div>
                </div>
            </div>

            <!-- PANE 10: SYSTEM SETTINGS -->
            <div id="mvc-section-settings" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header">
                    <div>
                        <h2 class="mvc-cc-heading">⚙️ Command Center &amp; Portal Settings</h2>
                        <div class="mvc-cc-desc">Configure grievance resolution SLA targets, audit log retention, and theme update protection.</div>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:20px; max-width:760px;">
                    <!-- Card 1: RetailCoder StoreFront [RC:StoreFront] Shield & Theme Protection -->
                    <div class="mvc-card" style="padding:24px; border-left:4px solid #002147;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid var(--mvc-border);">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/retailcoder-logo.png'); ?>" alt="RetailCoder StoreFront" width="36" height="36" style="border-radius:8px; object-fit:contain; background:#ffffff; padding:3px; box-shadow:0 2px 8px rgba(0,0,0,0.12);">
                                <div>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <h3 style="margin:0; font-size:16px; color:var(--mvc-navy); font-weight:700;">RetailCoder StoreFront [RC:StoreFront] Shield</h3>
                                        <span style="font-size:10px; background:linear-gradient(135deg, #D4AF37, #B48200); color:#001A38; font-weight:800; padding:2px 6px; border-radius:4px;">THEME ENGINE</span>
                                    </div>
                                    <div style="font-size:12px; color:var(--mvc-text-muted); margin-top:2px;">
                                        Enterprise Educational Portal &amp; Management Framework • <a href="https://retailcoder.com" target="_blank" rel="noopener" style="color:var(--mvc-primary); text-decoration:none; font-weight:600;">retailcoder.com ↗</a>
                                    </div>
                                </div>
                            </div>
                            <span id="mvcUpdateLockBadge" class="mvc-badge <?php echo mvc_is_update_locked() ? 'mvc-badge-publish' : 'mvc-badge-warning'; ?>" style="font-size:11.5px; font-weight:700;">
                                <?php echo mvc_is_update_locked() ? '🔒 Safe Mode (Updates Locked)' : '⚠️ Maintenance Mode (Updates Allowed)'; ?>
                            </span>
                        </div>

                        <!-- Update Lock Toggle Row -->
                        <div style="background:var(--mvc-bg-subtle, #F8FAFC); border:1px solid var(--mvc-border); border-radius:10px; padding:18px; margin-bottom:16px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:20px;">
                                <div>
                                    <div style="font-weight:700; font-size:14px; color:var(--mvc-text); margin-bottom:4px;">
                                        Lock Theme &amp; System Updates
                                    </div>
                                    <div style="font-size:12.5px; color:var(--mvc-text-muted); line-height:1.45;">
                                        Freezes Astra parent theme and child theme updates to prevent upstream changes from breaking customized institutional templates, responsive layouts, and academic workflows. Toggle OFF only during scheduled maintenance.
                                    </div>
                                </div>
                                <div style="flex-shrink:0;">
                                    <label class="mvc-switch" style="position:relative; display:inline-block; width:52px; height:28px; cursor:pointer;">
                                        <input type="checkbox" id="mvcUpdateLockCheckbox" <?php checked(mvc_is_update_locked(), true); ?> style="opacity:0; width:0; height:0;">
                                        <span class="mvc-slider" id="mvcUpdateLockSlider" style="position:absolute; top:0; left:0; right:0; bottom:0; background-color:<?php echo mvc_is_update_locked() ? '#002147' : '#CBD5E1'; ?>; transition:0.3s; border-radius:28px;">
                                            <span class="mvc-knob" id="mvcUpdateLockKnob" style="position:absolute; height:20px; width:20px; left:<?php echo mvc_is_update_locked() ? '28px' : '4px'; ?>; bottom:4px; background-color:#ffffff; transition:0.3s; border-radius:50%; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Specification Grid -->
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; font-size:12px; color:var(--mvc-text-muted);">
                            <div style="background:var(--mvc-surface); border:1px solid var(--mvc-border); padding:10px 12px; border-radius:6px;">
                                <div style="color:#64748B; font-size:11px;">Theme Name</div>
                                <div style="font-weight:700; color:var(--mvc-text); margin-top:2px;">RetailCoder StoreFront [RC:StoreFront]</div>
                            </div>
                            <div style="background:var(--mvc-surface); border:1px solid var(--mvc-border); padding:10px 12px; border-radius:6px;">
                                <div style="color:#64748B; font-size:11px;">Parent Template</div>
                                <div style="font-weight:700; color:var(--mvc-text); margin-top:2px;">Astra (Shielded)</div>
                            </div>
                            <div style="background:var(--mvc-surface); border:1px solid var(--mvc-border); padding:10px 12px; border-radius:6px;">
                                <div style="color:#64748B; font-size:11px;">Developer &amp; Vendor</div>
                                <div style="font-weight:700; color:var(--mvc-text); margin-top:2px;"><a href="https://retailcoder.com" target="_blank" rel="noopener" style="color:var(--mvc-primary); text-decoration:none;">RetailCoder ↗</a></div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 1b: Public Website Maintenance Mode Switch -->
                    <?php 
                    $maint_active = function_exists('mvc_is_maintenance_mode_active') && mvc_is_maintenance_mode_active();
                    ?>
                    <div class="mvc-card" style="padding:24px; border-left:4px solid #B45309;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid var(--mvc-border);">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:36px; height:36px; border-radius:8px; background:rgba(217,119,6,0.14); border:2px solid #D97706; display:flex; align-items:center; justify-content:center; color:#D97706; flex-shrink:0;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <h3 style="margin:0; font-size:16px; color:var(--mvc-navy); font-weight:700;">Public Website Maintenance Mode</h3>
                                        <span style="font-size:10px; background:linear-gradient(135deg, #B45309, #78350F); color:#ffffff; font-weight:800; padding:2px 6px; border-radius:4px;">ACCESS GUARD</span>
                                    </div>
                                    <div style="font-size:12px; color:var(--mvc-text-muted); margin-top:2px;">
                                        Restricts public visitors strictly to Home Page • Top maintenance banner active
                                    </div>
                                </div>
                            </div>
                            <span id="mvcMaintenanceModeBadge" class="mvc-badge <?php echo $maint_active ? 'mvc-badge-warning' : 'mvc-badge-publish'; ?>" style="font-size:11.5px; font-weight:700;">
                                <?php echo $maint_active ? '⚠️ Maintenance Active (Home Page Only)' : '🟢 Site Live &amp; Operational'; ?>
                            </span>
                        </div>

                        <!-- Maintenance Mode Toggle Row -->
                        <div style="background:var(--mvc-bg-subtle, #F8FAFC); border:1px solid var(--mvc-border); border-radius:10px; padding:18px; margin-bottom:16px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:20px;">
                                <div>
                                    <div style="font-weight:700; font-size:14px; color:var(--mvc-text); margin-bottom:4px;">
                                        Lock Public Access to Home Page Only
                                    </div>
                                    <div style="font-size:12.5px; color:var(--mvc-text-muted); line-height:1.45;">
                                        When switched ON, only the homepage is accessible to public visitors. Any attempt to navigate to departments, notices, events, or contact pages is automatically redirected to the homepage where a prominent maintenance notice is displayed. Administrators retain full access.
                                    </div>
                                </div>
                                <div style="flex-shrink:0;">
                                    <label class="mvc-switch" style="position:relative; display:inline-block; width:52px; height:28px; cursor:pointer;">
                                        <input type="checkbox" id="mvcMaintenanceModeCheckbox" <?php checked($maint_active, true); ?> style="opacity:0; width:0; height:0;">
                                        <span class="mvc-slider" id="mvcMaintenanceModeSlider" style="position:absolute; top:0; left:0; right:0; bottom:0; background-color:<?php echo $maint_active ? '#B45309' : '#CBD5E1'; ?>; transition:0.3s; border-radius:28px;">
                                            <span class="mvc-knob" id="mvcMaintenanceModeKnob" style="position:absolute; height:20px; width:20px; left:<?php echo $maint_active ? '28px' : '4px'; ?>; bottom:4px; background-color:#ffffff; transition:0.3s; border-radius:50%; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Links and Info -->
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; font-size:12.5px;">
                            <div style="color:var(--mvc-text-muted);">
                                Need to customize notice heading, message, or helpline?
                            </div>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-maintenance-mode')); ?>" class="mvc-btn mvc-btn-secondary" style="font-size:12px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:6px; background:#002147; color:#ffffff;">
                                ⚙️ Configure Banner Text &amp; Contacts &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Operational Institutional Parameters -->
                    <div class="mvc-card" style="padding:24px;">
                        <h3 style="margin:0 0 16px 0; font-size:15px; color:var(--mvc-navy); font-weight:700;">Operational Institutional Parameters</h3>
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:1px solid var(--mvc-border);">
                                <div>
                                    <strong>Grievance Resolution SLA Target</strong>
                                    <div style="font-size:12.5px; color:var(--mvc-text-muted);">Statutory maximum turnaround time</div>
                                </div>
                                <span class="mvc-badge mvc-badge-publish" style="font-size:12px;">15 Working Days</span>
                            </div>

                            <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:1px solid var(--mvc-border);">
                                <div>
                                    <strong>Audit Log Retention Period</strong>
                                    <div style="font-size:12.5px; color:var(--mvc-text-muted);">Chronological record storage (continuous rolling purge)</div>
                                </div>
                                <span class="mvc-badge mvc-badge-publish" style="font-size:12px; font-weight:700;">30 Days (Strict Rolling Purge)</span>
                            </div>

                            <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:12px; border-bottom:1px solid var(--mvc-border);">
                                <div>
                                    <strong>Front-End Scholar Portal</strong>
                                    <div style="font-size:12.5px; color:var(--mvc-text-muted);">Dedicated gateway route</div>
                                </div>
                                <code>/portal/</code>
                            </div>

                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <strong>Single Sign-On Authentication Gateway</strong>
                                    <div style="font-size:12.5px; color:var(--mvc-text-muted);">Unified portal login</div>
                                </div>
                                <code>/login/</code>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Institutional Email & SMTP Gateway Configuration (Zoho Optimized) -->
                    <?php
                    $is_zoho_active = function_exists('is_plugin_active') && is_plugin_active('zoho-mail/zohoMail.php');
                    $is_smtp_active = function_exists('is_plugin_active') && is_plugin_active('wp-mail-smtp/wp_mail_smtp.php');
                    ?>
                    <div class="mvc-card" style="padding:24px; border-left:4px solid var(--mvc-gold, #D4AF37);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; padding-bottom:14px; border-bottom:1px solid var(--mvc-border);">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:38px; height:38px; border-radius:8px; background:linear-gradient(135deg, #002147 0%, #800000 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-size:18px;">
                                    ✉️
                                </div>
                                <div>
                                    <h3 style="margin:0; font-size:16px; color:var(--mvc-navy); font-weight:700;">Institutional Email &amp; Zoho Gateway</h3>
                                    <div style="font-size:12px; color:var(--mvc-text-muted); margin-top:2px;">
                                        Outgoing Mail Gateway &bull; Delivers OTP Verification, Grievance Alerts &amp; Contact Notices
                                    </div>
                                </div>
                            </div>
                            <span class="mvc-badge <?php echo ($is_zoho_active || $is_smtp_active) ? 'mvc-badge-publish' : 'mvc-badge-warning'; ?>" style="font-size:11.5px; font-weight:700;">
                                <?php 
                                    if ($is_zoho_active) {
                                        echo '⚡ Zoho Mail Official API Active';
                                    } elseif ($is_smtp_active) {
                                        echo '⚡ WP Mail SMTP (Zoho Mode) Active';
                                    } else {
                                        echo 'Native PHP Mail';
                                    }
                                ?>
                            </span>
                        </div>

                        <div style="background:var(--mvc-bg-subtle, #F8FAFC); border:1px solid var(--mvc-border); border-radius:10px; padding:18px; margin-bottom:16px;">
                            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:16px; font-size:12.5px;">
                                <div>
                                    <div style="color:#64748B; font-size:11px; text-transform:uppercase; font-weight:700;">Active From Address</div>
                                    <div style="font-weight:700; color:var(--mvc-navy); margin-top:2px;">
                                        <?php echo esc_html(mvc_get_principal_email()); ?>
                                    </div>
                                </div>
                                <div>
                                    <div style="color:#64748B; font-size:11px; text-transform:uppercase; font-weight:700;">Active Outgoing Engine</div>
                                    <div style="font-weight:700; color:var(--mvc-text); margin-top:2px;">
                                        <?php 
                                            if ($is_zoho_active) {
                                                echo 'Zoho Mail REST API (HTTPS)';
                                            } elseif ($is_smtp_active) {
                                                $smtp_opt = get_option('wp_mail_smtp', array());
                                                $mailer = $smtp_opt['mail']['mailer'] ?? 'Default / SMTP';
                                                echo 'Zoho SMTP (' . esc_html(ucfirst($mailer)) . ')';
                                            } else {
                                                echo 'Default PHP Mail';
                                            }
                                        ?>
                                    </div>
                                    <div style="font-size:11px; color:#166534; font-weight:600; margin-top:3px;">
                                        ⚡ Fallback: smtp.zoho.in:587 (RC:Echo Via MV College)
                                    </div>
                                </div>
                                <div>
                                    <div style="color:#64748B; font-size:11px; text-transform:uppercase; font-weight:700;">Deliverability Status</div>
                                    <div style="font-weight:700; color:#166534; margin-top:2px;">
                                        ✓ Operational (Ready for Live Dispatch)
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                                <?php if ($is_zoho_active): ?>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=zmail-integ-settings')); ?>" class="mvc-btn mvc-btn-primary" style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px; font-size:13px; font-weight:700; text-decoration:none; border-radius:6px; background:#002147; color:#ffffff;">
                                        ⚙️ Configure Zoho Mail Account (OAuth/API) &rarr;
                                    </a>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=zmail-send-mail')); ?>" class="mvc-btn" style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; font-size:13px; font-weight:600; text-decoration:none; border-radius:6px; background:#ffffff; color:#002147; border:1px solid #CBD5E1;">
                                        🧪 Zoho Mail Test Tool &rarr;
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=wp-mail-smtp')); ?>" class="mvc-btn mvc-btn-primary" style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px; font-size:13px; font-weight:700; text-decoration:none; border-radius:6px; background:#002147; color:#ffffff;">
                                        ⚙️ Configure Zoho SMTP Settings &rarr;
                                    </a>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=wp-mail-smtp&tab=test')); ?>" class="mvc-btn" style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; font-size:13px; font-weight:600; text-decoration:none; border-radius:6px; background:#ffffff; color:#002147; border:1px solid #CBD5E1;">
                                        🧪 Send Official Test Email &rarr;
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Zoho Mail Reference Strip -->
                            <div style="margin-top:14px; background:#EFF6FF; border:1px solid #BFDBFE; border-radius:8px; padding:12px 14px; font-size:12px; color:#1E40AF; line-height:1.55;">
                                <strong>ℹ️ Zoho Mail Outgoing Configuration Reference:</strong><br>
                                &bull; <strong>Zoho India (<code>.in</code>):</strong> Host: <code>smtppro.zoho.in</code> (Workplace) or <code>smtp.zoho.in</code> | Port: <code>465</code> (SSL) or <code>587</code> (TLS)<br>
                                &bull; <strong>Zoho Global (<code>.com</code>):</strong> Host: <code>smtppro.zoho.com</code> (Workplace) or <code>smtp.zoho.com</code> | Port: <code>465</code> (SSL) or <code>587</code> (TLS)<br>
                                &bull; <strong>Password:</strong> Generate a dedicated <strong>Zoho App Password</strong> from <a href="https://accounts.zoho.com" target="_blank" rel="noopener" style="color:#1D4ED8; font-weight:600; text-decoration:underline;">accounts.zoho.com</a> (Security &rarr; App Passwords).
                            </div>
                        </div>

                        <!-- Direct In-Hub Test Dispatch Form -->
                        <div style="border-top:1px solid var(--mvc-border); padding-top:16px;">
                            <div style="font-size:13px; font-weight:700; color:var(--mvc-navy); margin-bottom:6px;">
                                🚀 Instant Email Deliverability Test
                            </div>
                            <div style="font-size:12px; color:var(--mvc-text-muted); margin-bottom:12px;">
                                Enter any destination inbox (e.g. <code><?php echo esc_html(mvc_get_principal_email()); ?></code>) to test end-to-end email transmission instantly:
                            </div>
                            <form id="mvc_admin_test_mail_form" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                                <input type="email" id="mvc_test_mail_recipient" class="mvc-input" value="<?php echo esc_attr(mvc_get_principal_email()); ?>" placeholder="admin@domain.com" style="min-width:260px; height:38px; padding:0 12px; font-size:13px; border:1px solid #CBD5E1; border-radius:6px;" required>
                                <button type="submit" id="mvc_test_mail_submit" class="mvc-btn" style="height:38px; padding:0 16px; font-size:13px; font-weight:700; background:#166534; color:#ffffff; border:none; border-radius:6px; cursor:pointer;">
                                    Send Live Test Email
                                </button>
                                <span id="mvc_test_mail_feedback" style="font-size:12.5px; font-weight:600;"></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANE 11: UNIVERSAL CONTENT DESK (Modern In-Hub Content Management) -->
            <div id="mvc-section-content-desk" class="mvc-section-pane" style="display:none;">
                <div class="mvc-cc-header mvc-content-desk-header">
                    <div>
                        <div class="d-flex align-items-center gap-8" style="margin-bottom: 6px;">
                            <button type="button" class="mvc-btn mvc-btn-outline" id="mvcContentDeskBackBtn" style="padding: 4px 10px; font-size: 12px; cursor: pointer;">&larr; CMS Hub</button>
                            <span class="mvc-badge mvc-badge-accent" id="mvcDeskCptSlugBadge" style="text-transform: uppercase; font-size: 11px;">notice</span>
                        </div>
                        <h2 class="mvc-cc-heading" id="mvcDeskCptTitle">📢 Institutional Notices Desk</h2>
                        <div class="mvc-cc-desc" id="mvcDeskCptDesc">Browse, filter, search, and manage all published and draft collegiate records.</div>
                    </div>
                    <div class="d-flex gap-8 align-items-center">
                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=notice')); ?>" id="mvcDeskAddBtn" class="mvc-btn mvc-btn-primary" style="text-decoration:none;">
                            <span class="dashicons dashicons-plus-alt2" style="font-size:16px; width:16px; height:16px;"></span>
                            <span>+ Add <span id="mvcDeskAddLabel">Notice</span></span>
                        </a>
                    </div>
                </div>

                <!-- Controls & Filter Toolbar -->
                <div class="mvc-card mvc-content-desk-toolbar" style="padding: 16px 20px; margin-bottom: 20px;">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-16">
                        <!-- Search Box -->
                        <div class="d-flex align-items-center gap-8" style="flex: 1; max-width: 380px; min-width: 240px;">
                            <div style="position: relative; width: 100%;">
                                <span class="dashicons dashicons-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--mvc-text-muted); font-size: 16px; width: 16px; height: 16px;"></span>
                                <input type="text" id="mvcContentDeskSearch" class="mvc-input" placeholder="Search by title, memo #, or keyword..." style="padding-left: 34px; width: 100%;">
                            </div>
                        </div>

                        <!-- Status Filter Pills -->
                        <div class="d-flex align-items-center gap-8 flex-wrap" id="mvcDeskStatusFilters">
                            <button type="button" class="mvc-filter-pill active" data-status="all">All (<span id="mvcDeskCountAll">0</span>)</button>
                            <button type="button" class="mvc-filter-pill" data-status="publish">Published (<span id="mvcDeskCountPublish">0</span>)</button>
                            <button type="button" class="mvc-filter-pill" data-status="draft">Drafts (<span id="mvcDeskCountDraft">0</span>)</button>
                            <button type="button" class="mvc-filter-pill" data-status="pending">Pending (<span id="mvcDeskCountPending">0</span>)</button>
                        </div>

                        <!-- Refresh Button -->
                        <div>
                            <button type="button" class="mvc-btn mvc-btn-outline" id="mvcDeskRefreshBtn" title="Refresh records">
                                <span class="dashicons dashicons-update"></span>
                                <span class="btn-label d-none-mobile">Refresh</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content Table Card -->
                <div class="mvc-card">
                    <div class="mvc-table-responsive">
                        <table class="mvc-table" id="mvcContentDeskTable">
                            <thead>
                                <tr>
                                    <th style="min-width: 300px;">Title &amp; Reference</th>
                                    <th>Department / Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th style="text-align:right; min-width: 170px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="mvcContentDeskTableBody">
                                <tr>
                                    <td colspan="5" style="text-align:center; padding: 40px; color: var(--mvc-text-muted);">
                                        <span class="dashicons dashicons-update mvc-spin" style="font-size:24px; width:24px; height:24px;"></span>
                                        <div style="margin-top: 8px;">Loading records from modern repository...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Footer -->
                    <div class="d-flex align-items-center justify-content-between mvc-content-desk-pagination" style="padding: 16px 20px; border-top: 1px solid var(--mvc-border); font-size: 13px; color: var(--mvc-text-muted);" id="mvcContentDeskPaginationBar">
                        <div id="mvcContentDeskPageInfo">Showing 0 of 0 records</div>
                        <div class="d-flex gap-8 align-items-center">
                            <button type="button" class="mvc-btn mvc-btn-outline" id="mvcContentDeskPrevBtn" disabled>&larr; Prev</button>
                            <span id="mvcContentDeskCurrentPage" style="font-weight: 600; color: var(--mvc-text); padding: 0 6px;">Page 1 of 1</span>
                            <button type="button" class="mvc-btn mvc-btn-outline" id="mvcContentDeskNextBtn" disabled>Next &rarr;</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Command Center Bottom Institutional Footer -->
            <footer class="mvc-cc-content-footer" style="margin-top: 40px; padding: 24px 0 12px; border-top: 1px solid var(--mvc-border); text-align: center; font-size: 12.5px; color: var(--mvc-text-muted);">
                <p style="margin: 0; line-height: 1.6;">
                    &copy; 2026 <strong>Maharshi Vishwamitra Mahavidyalaya, Buxar</strong>. All Rights Reserved.
                    <span style="margin: 0 8px; opacity: 0.5;">&bull;</span>
                    Designed by <a href="https://retailcoder.com" target="_blank" rel="noopener" style="color: var(--mvc-gold); font-weight: 600; text-decoration: none;">RetailCoder</a>
                    <span style="margin: 0 8px; opacity: 0.5;">&bull;</span>
                    Managed by <a href="https://swadeshihost.com" target="_blank" rel="noopener" style="color: var(--mvc-gold); font-weight: 600; text-decoration: none;">Swadeshi Store</a>
                </p>
            </footer>
        </main>
    </div>

    <!-- COMMAND PALETTE MODAL (Ctrl + K) -->
    <div class="mvc-palette-backdrop" id="mvcPaletteBackdrop" style="display:none;">
        <div class="mvc-palette-modal">
            <div class="mvc-palette-search-bar">
                <span class="mvc-palette-search-icon">🔍</span>
                <input type="text" id="mvcPaletteInput" placeholder="Search all CMS content, notices, pages, scholars..." autocomplete="off">
                <kbd class="mvc-kbd">ESC</kbd>
            </div>
            <div class="mvc-palette-results" id="mvcPaletteResults">
                <!-- Dynamically populated -->
            </div>
            <div class="mvc-palette-footer">
                <span>Use <kbd>↑</kbd> <kbd>↓</kbd> to navigate, <kbd>↵</kbd> to select, <kbd>ESC</kbd> to close</span>
            </div>
        </div>
    </div>

    <!-- GRIEVANCE & ENQUIRIES INVESTIGATION & RESOLUTION MODAL -->
    <div class="mvc-palette-backdrop" id="mvcGrievanceBackdrop" style="display:none; align-items:flex-start; padding:40px 16px; overflow-y:auto; z-index:999999;">
        <div class="mvc-card" id="mvcGrievanceModal" style="width:100%; max-width:880px; margin:auto; background:#ffffff; border-radius:12px; box-shadow:0 12px 40px rgba(0,33,71,0.25); overflow:hidden; border:1px solid #CBD5E1;">
            <div style="background:linear-gradient(135deg, #002147 0%, #800000 100%); padding:20px 24px; color:#ffffff; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:11.5px; font-weight:800; color:#D4AF37; text-transform:uppercase; letter-spacing:0.5px;">Institutional Case Dossier</div>
                    <h3 id="mvcModalRef" style="margin:2px 0 0; font-size:19px; font-weight:800; color:#ffffff; font-family:monospace;"></h3>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <span id="mvcModalStatusBadge" class="mvc-badge" style="font-size:12px; font-weight:800; padding:4px 12px;"></span>
                    <button type="button" id="mvcCloseGrvModalBtn" style="background:none; border:none; color:#ffffff; font-size:24px; cursor:pointer; line-height:1;">&times;</button>
                </div>
            </div>

            <div style="padding:24px;">
                <!-- Submitter Info Bar -->
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:14px; margin-bottom:20px; font-size:13px; display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:10px;">
                    <div><strong>Submitter:</strong> <span id="mvcModalSubmitterName"></span></div>
                    <div><strong>Email:</strong> <span id="mvcModalSubmitterEmail"></span></div>
                    <div><strong>Phone:</strong> <span id="mvcModalSubmitterPhone"></span></div>
                    <div><strong>Department:</strong> <span id="mvcModalDeptName"></span></div>
                    <div><strong>Source Portal:</strong> <span id="mvcModalFormTitle"></span></div>
                    <div><strong>Date Logged:</strong> <span id="mvcModalCreated"></span></div>
                </div>

                <!-- Tabs: Dossier, Timeline, Take Action -->
                <div style="display:flex; border-bottom:2px solid #E2E8F0; margin-bottom:18px; gap:8px;">
                    <button type="button" class="mvc-tab-btn active" data-tab="dossier" style="background:none; border:none; padding:8px 16px; font-weight:700; font-size:13px; cursor:pointer; border-bottom:2px solid #002147; margin-bottom:-2px; color:#002147;">📋 Case Dossier</button>
                    <button type="button" class="mvc-tab-btn" data-tab="timeline" style="background:none; border:none; padding:8px 16px; font-weight:700; font-size:13px; cursor:pointer; color:#64748B;">📜 Investigation Timeline</button>
                    <button type="button" class="mvc-tab-btn" data-tab="action" style="background:none; border:none; padding:8px 16px; font-weight:700; font-size:13px; cursor:pointer; color:#64748B;">✍️ Take Action &amp; Resolve</button>
                </div>

                <!-- Tab 1: Dossier -->
                <div id="mvcTabContentDossier" class="mvc-modal-tab-content">
                    <div style="margin-bottom:16px;">
                        <h4 style="margin:0 0 6px; font-size:14px; color:#002147;">Subject / Matter:</h4>
                        <div id="mvcModalSubject" style="font-size:14px; font-weight:700; color:#1E293B;"></div>
                    </div>
                    <div style="margin-bottom:16px;">
                        <h4 style="margin:0 0 6px; font-size:14px; color:#002147;">Submitted Statement / Narrative:</h4>
                        <div id="mvcModalDescription" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:6px; padding:12px; font-size:13px; line-height:1.6; white-space:pre-wrap;"></div>
                    </div>
                    <div>
                        <h4 style="margin:0 0 8px; font-size:14px; color:#002147;">Form Submission Fields:</h4>
                        <div id="mvcModalFieldsTable" style="max-height:220px; overflow-y:auto; border:1px solid #E2E8F0; border-radius:6px;"></div>
                    </div>
                </div>

                <!-- Tab 2: Timeline -->
                <div id="mvcTabContentTimeline" class="mvc-modal-tab-content" style="display:none;">
                    <div id="mvcModalTimelineList" style="max-height:380px; overflow-y:auto; padding-right:8px;"></div>
                </div>

                <!-- Tab 3: Action Desk -->
                <div id="mvcTabContentAction" class="mvc-modal-tab-content" style="display:none;">
                    <div style="background:#FAF9F6; border:1px solid #D4AF37; border-radius:8px; padding:18px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                            <div>
                                <label style="display:block; font-size:12px; font-weight:700; color:#1E293B; margin-bottom:4px;">Update Status:</label>
                                <select id="mvcActionStatusSelect" class="mvc-select" style="width:100%; padding:8px; font-size:13px;">
                                    <option value="logged">LOGGED (Fresh Submission)</option>
                                    <option value="in-review">UNDER REVIEW (Assigned to Committee / Dept)</option>
                                    <option value="action_taken">IN PROGRESS (Action Initiated)</option>
                                    <option value="resolved">RESOLVED (Outcome Certified)</option>
                                    <option value="closed">CLOSED (Dossier Archived)</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block; font-size:12px; font-weight:700; color:#1E293B; margin-bottom:4px;">Assign Department:</label>
                                <select id="mvcActionDeptSelect" class="mvc-select" style="width:100%; padding:8px; font-size:13px;">
                                    <option value="0">-- General / Central Administration --</option>
                                    <?php
                                    if (!empty($all_depts_list) && !is_wp_error($all_depts_list)) {
                                        foreach ($all_depts_list as $d) {
                                            printf('<option value="%d">%s</option>', (int) $d->term_id, esc_html($d->name));
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom:14px;">
                            <label style="display:block; font-size:12px; font-weight:700; color:#1E293B; margin-bottom:4px;">Add Investigation Comment / Note:</label>
                            <textarea id="mvcActionNoteText" rows="3" style="width:100%; padding:8px; font-size:12.5px; border:1px solid #CBD5E1; border-radius:6px; box-sizing:border-box;" placeholder="Enter official committee findings, inquiry queries, or updates..."></textarea>
                            <div style="margin-top:6px; font-size:12px; display:flex; gap:16px;">
                                <label style="cursor:pointer; color:#166534; font-weight:700;">
                                    <input type="radio" name="mvc_modal_vis" value="public" checked> 🟢 Public Update (Emailed to Raiser &amp; Visible Online)
                                </label>
                                <label style="cursor:pointer; color:#B91C1C; font-weight:700;">
                                    <input type="radio" name="mvc_modal_vis" value="internal"> 🔒 Internal Staff Note (Confidential)
                                </label>
                            </div>
                        </div>

                        <div style="margin-bottom:16px;">
                            <label style="display:block; font-size:12px; font-weight:700; color:#1E293B; margin-bottom:4px;">Certified Redressal Outcome (Mandatory if marking Resolved):</label>
                            <textarea id="mvcActionOutcomeText" rows="3" style="width:100%; padding:8px; font-size:12.5px; border:1px solid #CBD5E1; border-radius:6px; box-sizing:border-box;" placeholder="Enter certified redressal outcome approved by the Principal / Committee..."></textarea>
                        </div>

                        <button type="button" id="mvcSubmitCaseActionBtn" class="mvc-btn mvc-btn-primary" style="width:100%; padding:10px; font-weight:800; font-size:13.5px; display:flex; justify-content:center; align-items:center; gap:8px;">
                            💾 Save Action &amp; Dispatch Submitter Notification
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
