/**
 * MV College Staff Command Center JavaScript Engine (v2.0 Complete Modern CMS)
 * Vanilla ES6 • Uses wp.apiFetch with REST Nonce Authentication
 * Mobile-First Drawer • Command Palette (Ctrl+K) • Popovers & Live Filtering
 */

(function () {
    'use strict';

    const apiFetch = window.wp ? window.wp.apiFetch : null;
    if (!apiFetch) {
        console.error('[MVC Command Center] wp.apiFetch is not available.');
        return;
    }

    // Configure apiFetch middleware with localized nonce
    if (window.MVC_DATA && window.MVC_DATA.nonce) {
        apiFetch.use(apiFetch.createNonceMiddleware(window.MVC_DATA.nonce));
    }

    const state = {
        activeSection: 'overview',
        user: window.MVC_DATA ? window.MVC_DATA.user : {},
        counts: window.MVC_DATA ? (window.MVC_DATA.counts || {}) : {},
        metrics: window.MVC_DATA ? (window.MVC_DATA.metrics || {}) : {},
        urls: window.MVC_DATA ? (window.MVC_DATA.urls || {}) : {},
        notifications: window.MVC_DATA ? (window.MVC_DATA.notifications || []) : [],
        readNotifs: JSON.parse(localStorage.getItem('mvc_read_notifs_v3') || '[]'),
        activeNotifFilter: 'all',
        theme: localStorage.getItem('mvc_theme') || 'light',
        sidebarCollapsed: localStorage.getItem('mvc_sidebar_collapsed') === 'true',
    };

    const deskState = {
        cpt: 'notice',
        page: 1,
        per_page: 20,
        search: '',
        status: 'all',
        totalPages: 1,
        totalRecords: 0,
        isLoading: false
    };

    const cptMeta = {
        notice: { title: '📢 Institutional Notices Desk', desc: 'Browse, filter, view, and manage all notifications, examination schedules, and certified memos.', addLabel: 'Notice' },
        slider: { title: '🖼️ Hero Slider Banners Desk', desc: 'Manage collegiate homepage banners, captions, and call-to-action buttons.', addLabel: 'Slide' },
        leadership: { title: '🌟 Leadership & Administration Desk', desc: 'Manage institutional leadership profiles, principals and executive officers.', addLabel: 'Leader' },
        calendar_milestone: { title: '🏆 Academic Milestones Desk', desc: 'Manage historical timeline entries, foundation history, and accreditation achievements.', addLabel: 'Milestone' },
        calendar_gazette: { title: '📜 Academic Gazettes Desk', desc: 'Manage syllabus documents, curriculum regulations, and university gazettes.', addLabel: 'Gazette' },
        faculty: { title: '👨‍🏫 Faculty Directory Desk', desc: 'Manage academic faculty directory, professor profiles, designations, and departments.', addLabel: 'Faculty' },
        department: { title: '🏛️ Academic Departments Desk', desc: 'Manage university-affiliated academic departments, codes, and faculty rosters.', addLabel: 'Department' },
        event: { title: '📅 Campus Events & Seminars Desk', desc: 'Manage academic seminars, sports meets, workshops, and institutional ceremonies.', addLabel: 'Event' },
        'press-media': { title: '📰 Press Media & Clippings Desk', desc: 'Manage newspaper coverage clippings, press releases, and print media archives.', addLabel: 'Clipping' },
        press_media: { title: '📰 Press Media & Clippings Desk', desc: 'Manage newspaper coverage clippings, press releases, and print media archives.', addLabel: 'Clipping' },
        page: { title: '📄 Institutional Pages Desk', desc: 'Manage core institutional static pages, statutory disclosures, and campus overviews.', addLabel: 'Page' },
        study_material: { title: '📚 Study Materials & Notes Desk', desc: 'Manage departmental lecture notes, e-learning resources, and study modules.', addLabel: 'Material' },
        research: { title: '🔬 Research & Publications Desk', desc: 'Manage scholarly journals, faculty papers, book chapters, and research grants.', addLabel: 'Research' },
        committee: { title: '🤝 Committees & Academic Cells Desk', desc: 'Manage statutory committees, Anti-Ragging Cell, ICC, and member rosters.', addLabel: 'Committee' },
        staff_member: { title: '👥 Non-Teaching Staff Roster Desk', desc: 'Manage clerical, technical, laboratory assistants, and administrative cadres.', addLabel: 'Staff Member' },
        post: { title: '✍️ Articles & Campus Posts Desk', desc: 'Manage collegiate articles, student blog posts, and campus news stories.', addLabel: 'Post' }
    };

    // Initialize DOM
    document.addEventListener('DOMContentLoaded', () => {
        applyTheme(state.theme);
        applySidebarCollapsed(state.sidebarCollapsed);
        initLayoutControls();
        initNavigation();
        initPopovers();
        initNotifications();
        initFilters();
        initCommandPalette();
        initGlobalListeners();
        initUpdateLockToggle();
        initMaintenanceModeToggle();
        initGrievanceDeskEvents();

        // Initial metric badge render from localized data
        renderInitialMetrics();

        // Determine initial section from URL param or default
        const urlParams = new URLSearchParams(window.location.search);
        const sectionParam = urlParams.get('section');
        const cptParam = urlParams.get('cpt');
        if (sectionParam === 'content-desk' && cptParam) {
            openContentDesk(cptParam);
        } else if (sectionParam) {
            switchSection(sectionParam);
        } else {
            if (state.user.role === 'department_head') {
                switchSection('my-department');
            } else if (state.user.role === 'content_manager') {
                switchSection('review-queue');
            } else {
                switchSection('overview');
            }
        }

        // Dismiss modern dashboard preloader with smooth transition
        if (typeof window.mvcDismissCcLoader === 'function') {
            window.mvcDismissCcLoader('Workspace Ready');
        }
    });

    // Theme Management
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        const app = document.getElementById('mvcCommandCenterApp');
        if (app) app.setAttribute('data-theme', theme);
        localStorage.setItem('mvc_theme', theme);
    }

    // Sidebar Collapse
    function applySidebarCollapsed(collapsed) {
        const app = document.getElementById('mvcCommandCenterApp');
        if (app) {
            if (collapsed) {
                app.classList.add('mvc-sidebar-collapsed');
            } else {
                app.classList.remove('mvc-sidebar-collapsed');
            }
        }
        localStorage.setItem('mvc_sidebar_collapsed', collapsed ? 'true' : 'false');
    }

    // Layout Controls (Mobile Drawer & Desktop Collapse)
    function initLayoutControls() {
        const app = document.getElementById('mvcCommandCenterApp');
        const mobileToggle = document.getElementById('mvcMobileMenuToggle');
        const mobileClose = document.getElementById('mvcMobileDrawerClose');
        const mobileBackdrop = document.getElementById('mvcMobileBackdrop');
        const desktopCollapse = document.getElementById('mvcDesktopCollapseToggle');

        // Mobile drawer open
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                if (app) app.classList.add('mvc-drawer-open');
            });
        }

        // Mobile drawer close
        const closeDrawer = () => {
            if (app) app.classList.remove('mvc-drawer-open');
        };

        if (mobileClose) mobileClose.addEventListener('click', closeDrawer);
        if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeDrawer);

        // Desktop collapse toggle
        if (desktopCollapse) {
            desktopCollapse.addEventListener('click', () => {
                state.sidebarCollapsed = !state.sidebarCollapsed;
                applySidebarCollapsed(state.sidebarCollapsed);
            });
        }

        // Keyboard shortcut Ctrl+[ to toggle sidebar
        window.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === '[') {
                e.preventDefault();
                state.sidebarCollapsed = !state.sidebarCollapsed;
                applySidebarCollapsed(state.sidebarCollapsed);
            }
        });
    }

    // Popovers (Notifications & Profile Dropdown)
    function initPopovers() {
        const profileBtn = document.getElementById('mvcUserProfileBtn');
        const profileDropdown = document.getElementById('mvcProfileDropdown');
        const profileWrap = document.getElementById('mvcUserProfileWrapper');
        const themeBtn = document.getElementById('mvcThemeToggleBtn');
        const notifTray = document.getElementById('mvcNotificationTray');
        const notifWrap = document.getElementById('mvcNotificationWrapper');

        // Profile Toggle
        if (profileBtn && profileDropdown) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (notifTray) notifTray.style.display = 'none';
                profileDropdown.style.display = profileDropdown.style.display === 'none' ? 'block' : 'none';
            });
        }

        // Theme Toggle
        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                const nextTheme = state.theme === 'light' ? 'dark' : 'light';
                state.theme = nextTheme;
                applyTheme(nextTheme);
            });
        }

        // Click outside closes popovers
        document.addEventListener('click', (e) => {
            if (notifWrap && !notifWrap.contains(e.target) && notifTray) {
                notifTray.style.display = 'none';
            }
            if (profileWrap && !profileWrap.contains(e.target) && profileDropdown) {
                profileDropdown.style.display = 'none';
            }
        });
    }

    // Comprehensive 10/10 Notifications System
    function initNotifications() {
        const notifBtn = document.getElementById('mvcNotificationBellBtn');
        const notifTray = document.getElementById('mvcNotificationTray');
        const markAllBtn = document.getElementById('mvcMarkAllReadBtn');
        const refreshBtn = document.getElementById('mvcRefreshNotifsBtn');
        const footerLink = document.getElementById('mvcTrayFooterLink');

        if (!notifBtn || !notifTray) return;

        // Configure footer destination link based on role
        if (footerLink) {
            const isHod = state.user.role === 'department_head' || state.user.role === 'department_editor';
            if (isHod) {
                footerLink.innerText = 'My Department Desk →';
                footerLink.onclick = (e) => {
                    e.preventDefault();
                    notifTray.style.display = 'none';
                    const target = document.querySelector('[data-section="my-department"]');
                    if (target) target.click();
                };
            } else if (state.user.role === 'administrator') {
                footerLink.innerText = 'Review Queue Desk →';
                footerLink.onclick = (e) => {
                    e.preventDefault();
                    notifTray.style.display = 'none';
                    const target = document.querySelector('[data-section="review-queue"]');
                    if (target) target.click();
                };
            } else {
                footerLink.innerText = 'Content Desk →';
                footerLink.onclick = (e) => {
                    e.preventDefault();
                    notifTray.style.display = 'none';
                    const target = document.querySelector('[data-section="content-desk"]');
                    if (target) target.click();
                };
            }
        }

        // Notification Bell Click Toggle
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const profileDropdown = document.getElementById('mvcProfileDropdown');
            if (profileDropdown) profileDropdown.style.display = 'none';
            const isOpen = notifTray.style.display === 'block';
            notifTray.style.display = isOpen ? 'none' : 'block';
            notifBtn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        });

        // Mark All Read Button
        if (markAllBtn) {
            markAllBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (!state.notifications || state.notifications.length === 0) return;
                state.notifications.forEach(n => {
                    if (!state.readNotifs.includes(n.id)) {
                        state.readNotifs.push(n.id);
                    }
                });
                localStorage.setItem('mvc_read_notifs_v3', JSON.stringify(state.readNotifs));
                renderNotificationTray();
                showToast('All alerts marked as read ✓');
            });
        }

        // Filter Pills
        const filterPills = notifTray.querySelectorAll('.mvc-filter-pill');
        filterPills.forEach(pill => {
            pill.addEventListener('click', (e) => {
                e.stopPropagation();
                filterPills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                state.activeNotifFilter = pill.getAttribute('data-filter') || 'all';
                renderNotificationTray();
            });
        });

        // Refresh Alerts Button
        if (refreshBtn) {
            refreshBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                refreshBtn.disabled = true;
                refreshBtn.innerHTML = '<span class="dashicons dashicons-update mvc-spin"></span> Syncing...';
                try {
                    const freshNotifs = await apiFetch({ path: '/mvc/v1/notifications' });
                    if (Array.isArray(freshNotifs)) {
                        state.notifications = freshNotifs;
                    }
                    renderNotificationTray();
                    showToast('Notifications refreshed from server.');
                } catch (err) {
                    showToast('Failed to refresh alerts: ' + (err.message || 'Error'), 'danger');
                } finally {
                    refreshBtn.disabled = false;
                    refreshBtn.innerHTML = '<span class="dashicons dashicons-update" style="font-size:13px; width:13px; height:13px; line-height:13px;"></span> Refresh Alerts';
                }
            });
        }

        // Initial render
        renderNotificationTray();
    }

    // Render Notification Tray and Update Badge Counters
    function renderNotificationTray() {
        const notifBtn = document.getElementById('mvcNotificationBellBtn');
        const notifTray = document.getElementById('mvcNotificationTray');
        const topBadge = document.getElementById('mvcNotificationBadge');
        const trayCount = document.getElementById('mvcTrayHeaderCount');
        const traySubtitle = document.getElementById('mvcTrayHeaderSubtitle');
        const trayList = document.getElementById('mvcNotificationTrayList');
        const unreadFilterCount = document.getElementById('mvcFilterUnreadCount');

        const all = state.notifications || [];
        const unreadItems = all.filter(n => n.unread && !state.readNotifs.includes(n.id));
        const unreadCount = unreadItems.length;

        // Update Topbar Bell Badge
        if (topBadge) {
            if (unreadCount > 0) {
                topBadge.innerText = unreadCount;
                topBadge.style.display = 'block';
                topBadge.classList.add('mvc-badge-pulse');
                if (notifBtn) notifBtn.classList.add('mvc-bell-has-unread');
            } else {
                topBadge.style.display = 'none';
                topBadge.classList.remove('mvc-badge-pulse');
                if (notifBtn) notifBtn.classList.remove('mvc-bell-has-unread');
            }
        }

        if (unreadFilterCount) {
            unreadFilterCount.innerText = unreadCount;
        }

        if (trayCount) {
            trayCount.innerText = unreadCount > 0 ? (unreadCount + ' New') : 'All Caught Up';
            trayCount.style.background = unreadCount > 0 ? 'var(--mvc-gold)' : '#10B981';
            trayCount.style.color = unreadCount > 0 ? 'var(--mvc-navy-dark)' : '#FFFFFF';
        }

        if (traySubtitle) {
            traySubtitle.innerText = unreadCount > 0 
                ? (unreadCount + ' unread notification' + (unreadCount === 1 ? '' : 's') + ' requiring attention')
                : 'All collegiate systems operating smoothly';
        }

        if (!trayList) return;

        // Apply active filter
        let filtered = all;
        if (state.activeNotifFilter === 'unread') {
            filtered = all.filter(n => n.unread && !state.readNotifs.includes(n.id));
        } else if (state.activeNotifFilter === 'department') {
            filtered = all.filter(n => ['notice', 'faculty', 'compliance'].includes(n.type));
        }

        if (filtered.length === 0) {
            const emptyMsg = state.activeNotifFilter === 'unread'
                ? 'No unread notifications. You are completely caught up!'
                : 'No alerts match current filter criteria.';
            trayList.innerHTML = `
                <div class="mvc-tray-empty">
                    <div style="font-size:32px; margin-bottom:8px;">🏛️✨</div>
                    <strong style="color:var(--mvc-navy); font-size:14px;">All Institutional Systems Optimal</strong>
                    <div style="font-size:12px; color:var(--mvc-text-muted); margin-top:4px;">${escapeHtml(emptyMsg)}</div>
                </div>
            `;
            return;
        }

        let html = '';
        filtered.forEach(n => {
            const isUnread = n.unread && !state.readNotifs.includes(n.id);
            const badgeBg = n.badge_color || '#002147';
            const icon = n.icon || '🔔';

            html += `
                <div class="mvc-tray-item-notice ${isUnread ? 'unread' : 'read'}" data-notif-id="${escapeHtml(n.id)}" data-target="${escapeHtml(n.target_section || '')}">
                    <div class="mvc-tray-item-icon" style="background:${escapeHtml(badgeBg)}18; border:1px solid ${escapeHtml(badgeBg)}40;">
                        ${icon}
                    </div>
                    <div class="mvc-tray-item-content">
                        <div class="mvc-tray-item-top">
                            <span class="mvc-tray-badge" style="background:${escapeHtml(badgeBg)}20; color:${escapeHtml(badgeBg)}; border:1px solid ${escapeHtml(badgeBg)}40;">
                                ${escapeHtml(n.badge || 'Alert')}
                            </span>
                            <span class="mvc-tray-time">${escapeHtml(n.time || 'Now')}</span>
                        </div>
                        <div class="mvc-tray-title">${escapeHtml(n.title)}</div>
                        <div class="mvc-tray-desc">${escapeHtml(n.desc || '')}</div>
                    </div>
                    ${isUnread ? '<span class="mvc-tray-dot" title="Unread alert"></span>' : '<span style="font-size:13px; color:var(--mvc-text-muted); margin-top:4px;">&rarr;</span>'}
                </div>
            `;
        });

        trayList.innerHTML = html;

        // Attach click handlers to notification items
        trayList.querySelectorAll('.mvc-tray-item-notice').forEach(item => {
            item.addEventListener('click', () => {
                const notifId = item.getAttribute('data-notif-id');
                const targetSec = item.getAttribute('data-target');

                // Mark read
                if (notifId && !state.readNotifs.includes(notifId)) {
                    state.readNotifs.push(notifId);
                    localStorage.setItem('mvc_read_notifs_v3', JSON.stringify(state.readNotifs));
                    renderNotificationTray();
                }

                // Close tray
                if (notifTray) notifTray.style.display = 'none';

                // Navigate to target section safely
                if (targetSec) {
                    let navBtn = document.querySelector(`[data-section="${targetSec}"]`);
                    if (!navBtn) {
                        const isHod = state.user.role === 'department_head' || state.user.role === 'department_editor';
                        if (isHod) {
                            navBtn = document.querySelector('[data-section="my-department"]') || document.querySelector('[data-section="my-submissions"]');
                        } else {
                            navBtn = document.querySelector('[data-section="overview"]');
                        }
                    }
                    if (navBtn) {
                        navBtn.click();
                    }
                }
            });
        });
    }

    // Sidebar & CMS Hub Real-time Filtering
    function initFilters() {
        // Sidebar item filter
        const sidebarInput = document.getElementById('mvcSidebarFilterInput');
        if (sidebarInput) {
            sidebarInput.addEventListener('input', () => {
                const term = sidebarInput.value.toLowerCase().trim();
                const navItems = document.querySelectorAll('.mvc-sidebar-scroll .mvc-cc-nav-item, .mvc-sidebar-scroll .mvc-nav-item-has-submenu');
                
                navItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (!term || text.includes(term)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Hide empty groups
                document.querySelectorAll('.mvc-nav-group').forEach(group => {
                    const visibleChildren = group.querySelectorAll('.mvc-cc-nav-item:not([style*="display: none"]), .mvc-nav-item-has-submenu:not([style*="display: none"])');
                    group.style.display = (visibleChildren.length === 0 && term) ? 'none' : '';
                });
            });
        }

        // CMS Hub Cards filter
        const hubInput = document.getElementById('mvcCmsHubFilter');
        if (hubInput) {
            hubInput.addEventListener('input', () => {
                const term = hubInput.value.toLowerCase().trim();
                const cards = document.querySelectorAll('#mvcCmsHubCardsContainer .mvc-cms-card');
                
                cards.forEach(card => {
                    const text = (card.textContent + ' ' + (card.getAttribute('data-keywords') || '')).toLowerCase();
                    if (!term || text.includes(term)) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    }

    // Navigation and Section Switcher
    function initNavigation() {
        const navItems = document.querySelectorAll('.mvc-cc-nav-item[data-section]');
        const app = document.getElementById('mvcCommandCenterApp');
        const closeMobileDrawer = () => {
            if (app && window.innerWidth <= 1024) {
                app.classList.remove('mvc-drawer-open');
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const section = item.getAttribute('data-section');
                const cpt = item.getAttribute('data-cpt');
                if (section === 'content-desk' && cpt) {
                    openContentDesk(cpt);
                } else if (section) {
                    switchSection(section);
                }
                closeMobileDrawer();
            });
        });

        // Also close drawer when clicking any link or add button inside sidebar on mobile
        const sidebarLinks = document.querySelectorAll('#mvcMasterSidebar a, #mvcMasterSidebar .mvc-nav-add-btn');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (app && window.innerWidth <= 1024) {
                    app.classList.remove('mvc-drawer-open');
                }
            });
        });

        // Open desk buttons in CMS Content Hub cards & snapshots
        document.querySelectorAll('.mvc-open-desk-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const cpt = btn.getAttribute('data-cpt');
                if (cpt) {
                    openContentDesk(cpt);
                }
            });
        });

        // Back to CMS Hub button from Content Desk
        const backBtn = document.getElementById('mvcContentDeskBackBtn');
        if (backBtn) {
            backBtn.addEventListener('click', () => {
                switchSection('cms-hub');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Initialize Universal Content Desk Controls
        initContentDeskControls();
    }

    function switchSection(sectionId) {
        state.activeSection = sectionId;

        // Update active class in sidebar
        document.querySelectorAll('.mvc-cc-nav-item[data-section]').forEach(el => {
            if (el.getAttribute('data-section') === sectionId) {
                if (sectionId === 'content-desk') {
                    if (el.getAttribute('data-cpt') === deskState.cpt) {
                        el.classList.add('active');
                    } else {
                        el.classList.remove('active');
                    }
                } else {
                    el.classList.add('active');
                }
            } else {
                el.classList.remove('active');
            }
        });

        // Hide all section panels, show active
        document.querySelectorAll('.mvc-section-pane').forEach(pane => {
            pane.style.display = 'none';
        });

        const activePane = document.getElementById('mvc-section-' + sectionId);
        if (activePane) {
            activePane.style.display = 'block';
            loadSectionData(sectionId);
        }

        // Update URL query string cleanly without reload
        const url = new URL(window.location);
        url.searchParams.set('section', sectionId);
        if (sectionId === 'content-desk') {
            url.searchParams.set('cpt', deskState.cpt);
        } else {
            url.searchParams.delete('cpt');
        }
        window.history.replaceState({}, '', url);
    }

    // Initial metrics rendering from server localization
    function renderInitialMetrics() {
        const rev = state.metrics.pending_reviews || 0;
        const grv = state.metrics.active_grievances || 0;
        const acc = state.metrics.pending_accounts || 0;

        updateMetricDisplay(rev, grv, acc);
    }

    function updateMetricDisplay(reviews, grievances, accounts) {
        // Overview card numbers
        const rEl = document.getElementById('mvcMetricReviewCount');
        const gEl = document.getElementById('mvcMetricGrievanceCount');
        const aEl = document.getElementById('mvcMetricPendingAccounts');

        if (rEl) rEl.innerText = reviews;
        if (gEl) gEl.innerText = grievances;
        if (aEl) aEl.innerText = accounts;

        // Sidebar badges
        const rBadge = document.getElementById('mvcNavReviewBadge');
        if (rBadge) {
            if (reviews > 0) {
                rBadge.innerText = reviews;
                rBadge.style.display = 'inline-block';
            } else {
                rBadge.style.display = 'none';
            }
        }

        const gBadge = document.getElementById('mvcNavGrievanceBadge');
        if (gBadge) {
            if (grievances > 0) {
                gBadge.innerText = grievances;
                gBadge.style.display = 'inline-block';
            } else {
                gBadge.style.display = 'none';
            }
        }

        const aBadge = document.getElementById('mvcNavAccountsBadge');
        if (aBadge) {
            if (accounts > 0) {
                aBadge.innerText = accounts;
                aBadge.style.display = 'inline-block';
            } else {
                aBadge.style.display = 'none';
            }
        }

        // Refresh notifications tray & badge with role-aware data
        if (typeof renderNotificationTray === 'function') {
            renderNotificationTray();
        }
    }

    // Dynamic Section Data Loaders
    function loadSectionData(sectionId) {
        if (sectionId === 'overview') {
            loadOverviewMetrics();
        } else if (sectionId === 'review-queue') {
            loadReviewQueue();
        } else if (sectionId === 'grievances') {
            loadGrievancesList();
        } else if (sectionId === 'accounts') {
            loadAccountsList();
        } else if (sectionId === 'audit-log') {
            loadAuditLogs();
        } else if (sectionId === 'my-department') {
            loadMyDepartmentData();
        } else if (sectionId === 'content-desk') {
            loadContentDeskData();
        }
    }

    // 1. Overview Metrics Load
    async function loadOverviewMetrics() {
        try {
            const rq = await apiFetch({ path: '/mvc/v1/review-queue?per_page=5' }).catch(() => []);
            const grvs = await apiFetch({ path: '/mvc/v1/grievances?per_page=10' }).catch(() => []);
            const accs = await apiFetch({ path: '/mvc/v1/accounts?status=pending&per_page=10' }).catch(() => []);

            updateMetricDisplay(
                Array.isArray(rq) ? rq.length : 0,
                Array.isArray(grvs) ? grvs.length : 0,
                Array.isArray(accs) ? accs.length : 0
            );
        } catch (e) {
            console.error('[MVC Overview Load Error]', e);
        }
    }

    // 2. Review Queue
    async function loadReviewQueue() {
        const container = document.getElementById('mvcReviewQueueList');
        if (!container) return;

        container.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:30px;">Loading review queue...</td></tr>';

        try {
            const items = await apiFetch({ path: '/mvc/v1/review-queue' });
            if (!items || items.length === 0) {
                container.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:40px; color:#64748B;">🎉 All submissions have been reviewed! Review queue is empty.</td></tr>';
                updateMetricDisplay(0, state.metrics.active_grievances || 0, state.metrics.pending_accounts || 0);
                return;
            }

            let html = '';
            items.forEach(item => {
                html += `
                    <tr>
                        <td data-col="Item Title"><strong>${escapeHtml(item.title)}</strong></td>
                        <td data-col="Content Type"><span class="mvc-badge mvc-badge-draft">${escapeHtml(item.post_type)}</span></td>
                        <td data-col="Department">${escapeHtml(item.department)}</td>
                        <td data-col="Author">${escapeHtml(item.author_name)}</td>
                        <td data-col="Editorial Actions" style="text-align:right;">
                            <div class="mvc-table-actions-row">
                                <button class="mvc-btn mvc-btn-primary mvc-action-approve" data-id="${item.id}" style="padding:6px 12px; font-size:12px;">Approve &amp; Publish</button>
                                <button class="mvc-btn mvc-btn-danger mvc-action-reject" data-id="${item.id}" style="padding:6px 12px; font-size:12px;">Reject...</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            container.innerHTML = html;

            container.querySelectorAll('.mvc-action-approve').forEach(btn => {
                btn.addEventListener('click', () => handleApprove(btn.getAttribute('data-id')));
            });
            container.querySelectorAll('.mvc-action-reject').forEach(btn => {
                btn.addEventListener('click', () => handleReject(btn.getAttribute('data-id')));
            });
        } catch (e) {
            container.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#EF4444;">Failed to load review queue: ${escapeHtml(e.message || 'Error')}</td></tr>`;
        }
    }

    async function handleApprove(id) {
        if (!confirm('Are you sure you want to approve and publish this submission?')) {
            return;
        }

        try {
            await apiFetch({
                path: `/mvc/v1/content/${id}/approve`,
                method: 'POST'
            });
            showToast('Item approved and certified for publication!');
            loadReviewQueue();
        } catch (e) {
            showToast('Error approving item: ' + (e.message || 'Validation failed'), 'danger');
        }
    }

    function handleReject(id) {
        const reason = prompt('Please provide a mandatory rejection explanation for required revisions (minimum 10 characters):');
        if (!reason) return;

        if (reason.trim().length < 10) {
            alert('Rejection reason must be at least 10 characters.');
            return;
        }

        apiFetch({
            path: `/mvc/v1/content/${id}/reject`,
            method: 'POST',
            data: { reason: reason.trim() }
        }).then(() => {
            showToast('Submission returned to author draft with feedback.');
            loadReviewQueue();
        }).catch(e => {
            showToast('Failed to reject item: ' + (e.message || 'Error'), 'danger');
        });
    }

    // 3. Quick Notice Publish
    const quickNoticeForm = document.getElementById('mvcQuickNoticeForm');
    if (quickNoticeForm) {
        quickNoticeForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = document.getElementById('mvcNoticeTitle').value;
            const memoNo = document.getElementById('mvcNoticeMemo').value;
            const audience = document.getElementById('mvcNoticeAudience').value;
            const content = document.getElementById('mvcNoticeContent').value;

            try {
                const res = await apiFetch({
                    path: '/mvc/v1/notices',
                    method: 'POST',
                    data: {
                        title: title,
                        memo_no: memoNo,
                        audience: audience,
                        content: content
                    }
                });
                showToast(`Notice published successfully (Status: ${res.status.toUpperCase()})`);
                quickNoticeForm.reset();
            } catch (err) {
                showToast('Failed to create notice: ' + (err.message || 'Validation error'), 'danger');
            }
        });
    }

    // 4. Grievance & Enquiries Desk
    let currentGrvStatus = 'all';
    let currentGrvDept   = '';
    let currentGrvSearch = '';
    let grvSearchTimer   = null;
    let activeGrvModalId = 0;

    async function loadGrievancesList() {
        const container = document.getElementById('mvcGrievancesList');
        if (!container) return;

        container.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:30px;">Loading grievances &amp; enquiries...</td></tr>';

        try {
            let query = `?status=${encodeURIComponent(currentGrvStatus)}`;
            if (currentGrvDept) query += `&dept_id=${encodeURIComponent(currentGrvDept)}`;
            if (currentGrvSearch) query += `&search=${encodeURIComponent(currentGrvSearch)}`;

            const items = await apiFetch({ path: `/mvc/v1/grievances${query}` });
            if (!items || items.length === 0) {
                container.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:30px; color:#64748B;">No grievances or enquiries found matching criteria.</td></tr>';
                return;
            }

            let html = '';
            items.forEach(g => {
                const badgeClass = g.is_overdue ? 'mvc-badge-overdue' : 'mvc-badge-' + g.status;
                const statusLabel = g.status.replace(/[-_]/g, ' ').toUpperCase();
                html += `
                    <tr>
                        <td data-col="Reference">
                            <code style="font-weight:700; color:#002147; background:#EFF6FF; padding:3px 8px; border-radius:4px; border:1px solid #BFDBFE;">${escapeHtml(g.ref)}</code>
                        </td>
                        <td data-col="Submitter">
                            <strong>${escapeHtml(g.submitter_name)}</strong>
                            ${g.submitter_email ? `<br><small style="color:#64748B;">✉ ${escapeHtml(g.submitter_email)}</small>` : ''}
                        </td>
                        <td data-col="Subject">
                            <strong>${escapeHtml(g.subject)}</strong>
                            <br><small style="color:#64748B;">${escapeHtml(g.form_title || g.category)}</small>
                        </td>
                        <td data-col="Department">
                            <span style="display:inline-block; background:#F8FAFC; color:#1E40AF; font-weight:700; font-size:11.5px; padding:2px 8px; border-radius:999px; border:1px solid #E2E8F0;">
                                🏛 ${escapeHtml(g.dept_name)}
                            </span>
                        </td>
                        <td data-col="Status">
                            <span class="mvc-badge ${badgeClass}">${escapeHtml(statusLabel)}</span>
                        </td>
                        <td data-col="SLA Target">
                            <small style="color:#1E293B; font-weight:600;">${escapeHtml(g.due_date)}</small>
                            ${g.is_overdue ? '<br><span style="color:#DC2626; font-size:11px; font-weight:800;">⚠️ Overdue</span>' : ''}
                        </td>
                        <td data-col="Actions" style="text-align:right;">
                            <button class="mvc-btn mvc-btn-primary mvc-action-view-grv" data-id="${g.id}" style="padding:5px 12px; font-size:12px; font-weight:700;">Manage Dossier &rarr;</button>
                        </td>
                    </tr>
                `;
            });
            container.innerHTML = html;

            container.querySelectorAll('.mvc-action-view-grv').forEach(btn => {
                btn.addEventListener('click', () => openGrievanceModal(btn.getAttribute('data-id')));
            });
        } catch (e) {
            container.innerHTML = `<tr><td colspan="7" style="color:#EF4444; text-align:center;">Failed to load grievances: ${escapeHtml(e.message || 'Error')}</td></tr>`;
        }
    }

    // Modern Grievance Investigation & Resolution Modal
    async function openGrievanceModal(id) {
        const backdrop = document.getElementById('mvcGrievanceBackdrop');
        if (!backdrop) return;

        activeGrvModalId = id;
        backdrop.style.display = 'flex';

        // Reset elements
        document.getElementById('mvcModalRef').textContent = 'Loading...';
        document.getElementById('mvcModalStatusBadge').textContent = '...';
        document.getElementById('mvcModalSubject').textContent = '';
        document.getElementById('mvcModalDescription').textContent = '';
        document.getElementById('mvcModalFieldsTable').innerHTML = '<div style="padding:14px; text-align:center;">Loading dossier...</div>';
        document.getElementById('mvcModalTimelineList').innerHTML = '<div style="padding:14px; text-align:center;">Loading timeline...</div>';

        // Switch to Dossier Tab
        switchGrvModalTab('dossier');

        try {
            const g = await apiFetch({ path: `/mvc/v1/grievances/${id}` });

            document.getElementById('mvcModalRef').textContent = g.ref;
            const badge = document.getElementById('mvcModalStatusBadge');
            badge.textContent = g.status.toUpperCase();
            badge.className = 'mvc-badge mvc-badge-' + g.status;

            document.getElementById('mvcModalSubmitterName').textContent = g.submitter_name || 'N/A';
            document.getElementById('mvcModalSubmitterEmail').textContent = g.submitter_email || 'N/A';
            document.getElementById('mvcModalSubmitterPhone').textContent = g.submitter_phone || 'N/A';
            document.getElementById('mvcModalDeptName').textContent = g.dept_name || 'Central / Unassigned';
            document.getElementById('mvcModalFormTitle').textContent = g.form_title || g.category;
            document.getElementById('mvcModalCreated').textContent = g.created_at;

            document.getElementById('mvcModalSubject').textContent = g.subject;
            document.getElementById('mvcModalDescription').textContent = g.description;

            // Form Fields Table
            const fieldsContainer = document.getElementById('mvcModalFieldsTable');
            if (g.fields_data && typeof g.fields_data === 'object' && Object.keys(g.fields_data).length > 0) {
                let fhtml = '<table style="width:100%; border-collapse:collapse; font-size:12.5px;"><tbody>';
                for (const key in g.fields_data) {
                    const f = g.fields_data[key];
                    const flbl = f.label || 'Field';
                    let fval = f.value || '';
                    if (Array.isArray(fval)) fval = fval.join(', ');
                    fhtml += `<tr><td style="padding:6px 10px; border-bottom:1px solid #E2E8F0; font-weight:700; color:#002147; width:35%; background:#F8FAFC;">${escapeHtml(flbl)}</td><td style="padding:6px 10px; border-bottom:1px solid #E2E8F0; color:#334155;">${escapeHtml(String(fval))}</td></tr>`;
                }
                fhtml += '</tbody></table>';
                fieldsContainer.innerHTML = fhtml;
            } else {
                fieldsContainer.innerHTML = '<div style="padding:10px; color:#64748B; font-size:12px;">No structured form fields recorded.</div>';
            }

            // Timeline Feed
            renderGrvTimeline(g.timeline);

            // Populate Action Desk
            const statusSelect = document.getElementById('mvcActionStatusSelect');
            if (statusSelect) statusSelect.value = g.status;

            const deptSelect = document.getElementById('mvcActionDeptSelect');
            if (deptSelect) deptSelect.value = g.dept_id || '0';

            const outcomeText = document.getElementById('mvcActionOutcomeText');
            if (outcomeText) outcomeText.value = g.outcome || '';

            const noteText = document.getElementById('mvcActionNoteText');
            if (noteText) noteText.value = '';

        } catch (e) {
            showToast('Failed to load case dossier: ' + (e.message || 'Error'), 'danger');
            backdrop.style.display = 'none';
        }
    }

    function renderGrvTimeline(timeline) {
        const tlist = document.getElementById('mvcModalTimelineList');
        if (!tlist) return;

        if (!timeline || timeline.length === 0) {
            tlist.innerHTML = '<div style="color:#64748B; font-size:13px; padding:10px;">No timeline events recorded yet.</div>';
            return;
        }

        let thtml = '';
        timeline.forEach(ev => {
            const isInternal = (ev.visibility === 'internal');
            const actor = ev.actor_name || 'Staff Member';
            const dotColor = isInternal ? '#DC2626' : '#16A34A';
            const tag = isInternal ? '<span style="color:#DC2626; font-weight:700;">[Internal Staff Note]</span>' : '<span style="color:#16A34A; font-weight:700;">[Public Update]</span>';

            thtml += `
                <div style="position:relative; padding-left:22px; margin-bottom:14px; border-left:2px solid #CBD5E1;">
                    <div style="position:absolute; left:-6px; top:3px; width:10px; height:10px; border-radius:50%; background:${dotColor};"></div>
                    <div style="font-size:11.5px; color:#64748B; margin-bottom:3px;">
                        <strong>${escapeHtml(actor)}</strong> &bull; ${escapeHtml(ev.created_at)} &bull; ${tag}
                    </div>
                    <div style="font-size:12.5px; color:#1E293B; background:#FAFBFD; border:1px solid #E2E8F0; border-radius:6px; padding:8px 12px; line-height:1.5;">
                        ${escapeHtml(ev.body)}
                    </div>
                </div>
            `;
        });
        tlist.innerHTML = thtml;
    }

    function switchGrvModalTab(tabKey) {
        document.querySelectorAll('#mvcGrievanceModal .mvc-tab-btn').forEach(btn => {
            const isTarget = (btn.getAttribute('data-tab') === tabKey);
            btn.classList.toggle('active', isTarget);
            btn.style.borderBottom = isTarget ? '2px solid #002147' : 'none';
            btn.style.color = isTarget ? '#002147' : '#64748B';
        });

        const dossier = document.getElementById('mvcTabContentDossier');
        const timeline = document.getElementById('mvcTabContentTimeline');
        const action = document.getElementById('mvcTabContentAction');

        if (dossier) dossier.style.display = (tabKey === 'dossier') ? 'block' : 'none';
        if (timeline) timeline.style.display = (tabKey === 'timeline') ? 'block' : 'none';
        if (action) action.style.display = (tabKey === 'action') ? 'block' : 'none';
    }

    // Submit Grievance Action
    async function submitGrievanceAction() {
        if (!activeGrvModalId) return;

        const btn = document.getElementById('mvcSubmitCaseActionBtn');
        const status = document.getElementById('mvcActionStatusSelect').value;
        const deptId = document.getElementById('mvcActionDeptSelect').value;
        const note = document.getElementById('mvcActionNoteText').value.trim();
        const outcome = document.getElementById('mvcActionOutcomeText').value.trim();
        const visRadio = document.querySelector('input[name="mvc_modal_vis"]:checked');
        const visibility = visRadio ? visRadio.value : 'public';

        if (status === 'resolved' && outcome.length < 10) {
            showToast('Please provide a certified resolution outcome statement of at least 10 characters.', 'danger');
            switchGrvModalTab('action');
            return;
        }

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '⏳ Saving Action &amp; Dispatching Notification...';
        }

        try {
            const res = await apiFetch({
                path: `/mvc/v1/grievances/${activeGrvModalId}/action`,
                method: 'POST',
                data: {
                    status: status,
                    dept_id: deptId,
                    note: note,
                    visibility: visibility,
                    outcome: outcome,
                }
            });

            showToast('Case dossier updated and notification dispatched successfully!', 'success');
            document.getElementById('mvcGrievanceBackdrop').style.display = 'none';
            loadGrievancesList();
        } catch (e) {
            showToast('Error saving action: ' + (e.message || 'Error'), 'danger');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '💾 Save Action &amp; Dispatch Submitter Notification';
            }
        }
    }

    // Bind Grievance Filters & Modal Controls
    function initGrievanceDeskEvents() {
        // Status pills
        document.querySelectorAll('.mvc-grv-filter-pill').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.mvc-grv-filter-pill').forEach(b => {
                    b.classList.remove('mvc-btn-primary', 'active');
                    b.classList.add('mvc-btn-secondary');
                });
                btn.classList.add('mvc-btn-primary', 'active');
                btn.classList.remove('mvc-btn-secondary');
                currentGrvStatus = btn.getAttribute('data-status') || 'all';
                loadGrievancesList();
            });
        });

        // Department filter
        const deptFilter = document.getElementById('mvcGrvDeptFilter');
        if (deptFilter) {
            deptFilter.addEventListener('change', () => {
                currentGrvDept = deptFilter.value;
                loadGrievancesList();
            });
        }

        // Search input
        const searchInput = document.getElementById('mvcGrvSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(grvSearchTimer);
                grvSearchTimer = setTimeout(() => {
                    currentGrvSearch = searchInput.value.trim();
                    loadGrievancesList();
                }, 350);
            });
        }

        // Modal close button & backdrop
        const closeBtn = document.getElementById('mvcCloseGrvModalBtn');
        const backdrop = document.getElementById('mvcGrievanceBackdrop');
        if (closeBtn && backdrop) {
            closeBtn.addEventListener('click', () => { backdrop.style.display = 'none'; });
            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) backdrop.style.display = 'none';
            });
        }

        // Modal Tab Buttons
        document.querySelectorAll('#mvcGrievanceModal .mvc-tab-btn').forEach(btn => {
            btn.addEventListener('click', () => switchGrvModalTab(btn.getAttribute('data-tab')));
        });

        // Submit Action Button
        const submitBtn = document.getElementById('mvcSubmitCaseActionBtn');
        if (submitBtn) {
            submitBtn.addEventListener('click', submitGrievanceAction);
        }
    }

    // 5. Scholar Accounts
    async function loadAccountsList() {
        const container = document.getElementById('mvcAccountsList');
        if (!container) return;

        container.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:30px;">Loading accounts...</td></tr>';

        try {
            const users = await apiFetch({ path: '/mvc/v1/accounts' });
            if (!users || users.length === 0) {
                container.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:30px; color:#64748B;">No accounts found.</td></tr>';
                return;
            }

            let html = '';
            users.forEach(u => {
                let actions = '';
                if (u.status === 'pending') {
                    actions += `<button class="mvc-btn mvc-btn-primary mvc-acc-approve" data-id="${u.id}" style="padding:3px 8px; font-size:11px;">Approve</button> `;
                    actions += `<button class="mvc-btn mvc-btn-danger mvc-acc-reject" data-id="${u.id}" style="padding:3px 8px; font-size:11px;">Reject</button>`;
                } else if (u.status === 'active') {
                    actions += `<button class="mvc-btn mvc-btn-danger mvc-acc-suspend" data-id="${u.id}" style="padding:3px 8px; font-size:11px;">Suspend</button>`;
                } else if (u.status === 'suspended') {
                    actions += `<button class="mvc-btn mvc-btn-primary mvc-acc-reactivate" data-id="${u.id}" style="padding:3px 8px; font-size:11px;">Reactivate</button>`;
                }

                html += `
                    <tr>
                        <td data-col="Scholar"><strong>${escapeHtml(u.name)}</strong><br><small style="color:#64748B;">${escapeHtml(u.email)}</small></td>
                        <td data-col="Username / Roll"><code>${escapeHtml(u.username)}</code></td>
                        <td data-col="Role"><span class="mvc-badge mvc-badge-draft">${escapeHtml(u.role)}</span></td>
                        <td data-col="Status"><span class="mvc-badge mvc-badge-${u.status}">${escapeHtml(u.status)}</span></td>
                        <td data-col="Actions" style="text-align:right;">
                            <div class="mvc-table-actions-row">${actions}</div>
                        </td>
                    </tr>
                `;
            });
            container.innerHTML = html;

            container.querySelectorAll('.mvc-acc-approve').forEach(b => b.addEventListener('click', () => handleAccountAction(b.getAttribute('data-id'), 'approve')));
            container.querySelectorAll('.mvc-acc-reject').forEach(b => b.addEventListener('click', () => handleAccountAction(b.getAttribute('data-id'), 'reject')));
            container.querySelectorAll('.mvc-acc-suspend').forEach(b => b.addEventListener('click', () => handleAccountAction(b.getAttribute('data-id'), 'suspend')));
            container.querySelectorAll('.mvc-acc-reactivate').forEach(b => b.addEventListener('click', () => handleAccountAction(b.getAttribute('data-id'), 'reactivate')));
        } catch (e) {
            container.innerHTML = `<tr><td colspan="5" style="color:#EF4444; text-align:center;">Failed to load accounts: ${escapeHtml(e.message || 'Error')}</td></tr>`;
        }
    }

    async function handleAccountAction(id, action) {
        if (!confirm(`Are you sure you want to ${action} this account?`)) return;

        try {
            await apiFetch({
                path: `/mvc/v1/accounts/${id}/${action}`,
                method: 'POST'
            });
            showToast(`Account successfully ${action}d.`);
            loadAccountsList();
        } catch (e) {
            showToast(`Action failed: ${e.message || 'Error'}`, 'danger');
        }
    }

    // 6. Audit Log (Universal Change Capture & 30-Day Retention Viewer)
    let auditListenersAttached = false;

    async function loadAuditLogs() {
        const container = document.getElementById('mvcAuditLogList');
        if (!container) return;

        const searchInput = document.getElementById('mvcAuditSearch');
        const filterSelect = document.getElementById('mvcAuditFilter');
        const searchVal = searchInput ? searchInput.value.trim() : '';
        const groupVal = filterSelect ? filterSelect.value : '';

        // Wire toolbar listeners once
        if (!auditListenersAttached) {
            auditListenersAttached = true;
            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => loadAuditLogs(), 400);
                });
            }
            if (filterSelect) {
                filterSelect.addEventListener('change', () => loadAuditLogs());
            }
            const refreshBtn = document.getElementById('mvcAuditRefreshBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => loadAuditLogs());
            }
            const purgeBtn = document.getElementById('mvcAuditPurgeBtn');
            if (purgeBtn) {
                purgeBtn.addEventListener('click', async () => {
                    if (!confirm('Run 30-Day Retention Purge now?\n\nThis will permanently delete all audit log records older than 30 days.')) {
                        return;
                    }
                    purgeBtn.disabled = true;
                    purgeBtn.innerText = 'Purging...';
                    try {
                        const res = await apiFetch({
                            path: '/mvc/v1/audit/purge',
                            method: 'POST'
                        });
                        showToast(`Purge complete: ${res.deleted_records || 0} records older than 30 days purged.`);
                        await loadAuditLogs();
                    } catch (err) {
                        showToast(`Purge failed: ${err.message || 'Error'}`, 'danger');
                    } finally {
                        purgeBtn.disabled = false;
                        purgeBtn.innerText = 'Purge > 30 Days 🧹';
                    }
                });
            }
        }

        container.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:30px;">Loading audit logs...</td></tr>';

        try {
            let path = '/mvc/v1/audit?per_page=50';
            if (searchVal) path += `&search=${encodeURIComponent(searchVal)}`;
            if (groupVal) path += `&action_group=${encodeURIComponent(groupVal)}`;

            const logs = await apiFetch({ path });
            if (!logs || logs.length === 0) {
                container.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:30px; color:var(--mvc-text-muted);">No audit events match current criteria.</td></tr>';
                return;
            }

            let html = '';
            logs.forEach(log => {
                let badgeClass = 'mvc-badge-draft';
                const act = (log.action || '').toLowerCase();
                if (act.includes('create') || act.includes('approved') || act.includes('restore') || act.includes('success')) {
                    badgeClass = 'mvc-badge-publish';
                } else if (act.includes('delete') || act.includes('trash') || act.includes('reject') || act.includes('fail') || act.includes('lockout') || act.includes('blocked') || act.includes('purged')) {
                    badgeClass = 'mvc-badge-rejected';
                } else if (act.includes('role') || act.includes('setting') || act.includes('assigned')) {
                    badgeClass = 'mvc-badge-gold';
                } else if (act.includes('status') || act.includes('update') || act.includes('profile')) {
                    badgeClass = 'mvc-badge-pending';
                }

                // Format action label
                const actionLabel = (log.action || '').replace(/_/g, ' ').toUpperCase();

                // Format details
                let detailsHtml = '-';
                if (log.details) {
                    try {
                        const parsed = typeof log.details === 'object' ? log.details : JSON.parse(log.details);
                        const parts = [];
                        for (const [k, v] of Object.entries(parsed)) {
                            parts.push(`<strong>${escapeHtml(k)}:</strong> ${escapeHtml(typeof v === 'object' ? JSON.stringify(v) : v)}`);
                        }
                        detailsHtml = parts.join(' • ');
                    } catch (e) {
                        detailsHtml = escapeHtml(log.details);
                    }
                }

                html += `
                    <tr>
                        <td data-col="Timestamp" style="white-space:nowrap;"><code style="font-size:12px;">${escapeHtml(log.created_at)}</code></td>
                        <td data-col="Action">
                            <span class="mvc-badge ${badgeClass}" style="font-size:11px; font-weight:700;">${escapeHtml(actionLabel)}</span>
                        </td>
                        <td data-col="Actor">
                            <strong>${escapeHtml(log.user_name || 'System')}</strong>
                            <div style="font-size:11px; color:#64748B;">Role: ${escapeHtml(log.user_role || 'system')} • IP: ${escapeHtml(log.ip || '-')}</div>
                        </td>
                        <td data-col="Target">
                            <code style="font-weight:600; color:#002147;">${escapeHtml(log.object_type || '-')}</code>
                            ${log.object_id ? `<span style="font-size:11px; color:#64748B;">#${log.object_id}</span>` : ''}
                        </td>
                        <td data-col="Details" style="max-width:340px; font-size:12px; color:#334155; line-height:1.4;">
                            ${detailsHtml}
                        </td>
                    </tr>
                `;
            });
            container.innerHTML = html;
        } catch (e) {
            container.innerHTML = `<tr><td colspan="5" style="color:#EF4444; text-align:center;">Failed to load audit logs: ${escapeHtml(e.message || 'Error')}</td></tr>`;
        }
    }

    // 7. Department Head Data
    async function loadMyDepartmentData() {
        const container = document.getElementById('mvcMyDeptSummary');
        if (!container) return;

        // If server already rendered rich departmental deck, preserve it
        if (container.classList.contains('mvc-hod-deck-rendered') || container.querySelector('.mvc-hod-dashboard-root')) {
            return;
        }

        container.innerHTML = '<p>Loading departmental statistics...</p>';
        try {
            const me = await apiFetch({ path: '/mvc/v1/me' });
            container.innerHTML = `
                <div style="background:var(--mvc-card-bg); border:1px solid var(--mvc-border); border-radius:8px; padding:24px;">
                    <h3 style="margin-top:0; color:var(--mvc-navy);">Department: ${escapeHtml(me.department || 'Not Assigned')}</h3>
                    <p style="color:var(--mvc-text-muted);">As Head of Department, all your submissions, faculty rosters, and study materials are automatically scoped to your designated department.</p>
                </div>
            `;
        } catch (e) {
            container.innerHTML = `<p style="color:#EF4444;">Failed to load departmental info: ${escapeHtml(e.message || 'Error')}</p>`;
        }
    }

    // Command Palette (Ctrl + K)
    function initCommandPalette() {
        const trigger = document.getElementById('mvcCommandPaletteTrigger');
        const backdrop = document.getElementById('mvcPaletteBackdrop');
        const input = document.getElementById('mvcPaletteInput');
        const results = document.getElementById('mvcPaletteResults');

        if (!backdrop || !input || !results) return;

        // Static quick actions list
        const staticActions = [
            { title: 'Executive Overview', type: 'Section', icon: '📊', action: () => switchSection('overview') },
            { title: 'CMS Content Hub (All Modules)', type: 'Section', icon: '🎨', action: () => switchSection('cms-hub') },
            { title: 'Quick Dispatch Notice', type: 'Action', icon: '⚡', action: () => switchSection('quick-publish') },
            { title: 'Editorial Review Queue', type: 'Section', icon: '📬', action: () => switchSection('review-queue') },
            { title: 'Grievance Redressal Desk', type: 'Section', icon: '⚖️', action: () => switchSection('grievances') },
            { title: 'Scholar Accounts Desk', type: 'Section', icon: '👥', action: () => switchSection('accounts') },
            { title: 'Academic Departments & HODs', type: 'Section', icon: '🏛️', action: () => switchSection('departments') },
            { title: 'Institutional Audit Log', type: 'Section', icon: '📜', action: () => switchSection('audit-log') },
            { title: 'NAAC Quality Suite', type: 'Link', icon: '🏆', url: state.urls.naac_suite },
            { title: 'Add New Institutional Notice', type: 'Action', icon: '📢', url: state.urls.notices_add },
            { title: 'Add New Campus Event', type: 'Action', icon: '📅', url: state.urls.events_add },
            { title: 'Add New Faculty Member', type: 'Action', icon: '👨‍🏫', url: state.urls.faculty_add },
            { title: 'Add New Hero Slide', type: 'Action', icon: '🖼️', url: state.urls.sliders_add },
            { title: 'Add New Leadership Profile', type: 'Action', icon: '🌟', url: state.urls.leadership_add },
            { title: 'Add New Academic Gazette', type: 'Action', icon: '📜', url: state.urls.gazettes_add },
            { title: 'Open Media Asset Vault', type: 'Link', icon: '📁', url: state.urls.media_all },
            { title: 'Open Public Website', type: 'Link', icon: '🌐', url: state.urls.site, target: '_blank' },
            { title: 'Switch to Classic WP Admin', type: 'System', icon: '🏛️', url: state.urls.legacy },
        ];

        function renderPaletteItems(items) {
            if (!items || items.length === 0) {
                results.innerHTML = '<div style="padding:16px; text-align:center; color:var(--mvc-text-muted); font-size:13px;">No matching results found.</div>';
                return;
            }

            let html = '';
            items.forEach((item, idx) => {
                html += `
                    <div class="mvc-palette-item ${idx === 0 ? 'active' : ''}" data-idx="${idx}">
                        <span class="mvc-palette-item-icon">${item.icon || '📄'}</span>
                        <div class="mvc-palette-item-text">
                            <strong>${escapeHtml(item.title)}</strong>
                            <span>${escapeHtml(item.type)}</span>
                        </div>
                    </div>
                `;
            });
            results.innerHTML = html;

            results.querySelectorAll('.mvc-palette-item').forEach(el => {
                el.addEventListener('click', () => {
                    const idx = parseInt(el.getAttribute('data-idx'), 10);
                    executePaletteItem(items[idx]);
                });
            });
        }

        function executePaletteItem(item) {
            closePalette();
            if (item.action) {
                item.action();
            } else if (item.url) {
                if (item.target === '_blank') {
                    window.open(item.url, '_blank');
                } else {
                    window.location.href = item.url;
                }
            }
        }

        function openPalette() {
            backdrop.style.display = 'flex';
            input.value = '';
            renderPaletteItems(staticActions);
            input.focus();
        }

        function closePalette() {
            backdrop.style.display = 'none';
        }

        if (trigger) trigger.addEventListener('click', openPalette);

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) closePalette();
        });

        window.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                if (backdrop.style.display === 'flex') {
                    closePalette();
                } else {
                    openPalette();
                }
            } else if (e.key === 'Escape' && backdrop.style.display === 'flex') {
                closePalette();
            }
        });

        // Search in palette
        let searchTimer = null;
        input.addEventListener('input', () => {
            clearTimeout(searchTimer);
            const q = input.value.trim().toLowerCase();
            if (!q) {
                renderPaletteItems(staticActions);
                return;
            }

            // Filter static actions first
            const matchedStatic = staticActions.filter(it => it.title.toLowerCase().includes(q) || it.type.toLowerCase().includes(q));

            // Also search backend via REST if query >= 2 chars
            if (q.length >= 2) {
                searchTimer = setTimeout(async () => {
                    try {
                        const backendResults = await apiFetch({ path: `/mvc/v1/search?q=${encodeURIComponent(q)}` }).catch(() => []);
                        const combined = [...matchedStatic];
                        if (Array.isArray(backendResults)) {
                            backendResults.forEach(r => {
                                combined.push({
                                    title: r.title,
                                    type: r.type,
                                    icon: '📄',
                                    url: r.edit_url
                                });
                            });
                        }
                        renderPaletteItems(combined);
                    } catch (err) {
                        renderPaletteItems(matchedStatic);
                    }
                }, 200);
            } else {
                renderPaletteItems(matchedStatic);
            }
        });
    }

    // =========================================================================
    // UNIVERSAL CONTENT DESK CONTROLLERS & ENGINE
    // =========================================================================
    function openContentDesk(cpt, page = 1, search = '', status = 'all') {
        deskState.cpt = cpt || 'notice';
        deskState.page = page;
        deskState.search = search;
        deskState.status = status;

        const meta = cptMeta[deskState.cpt] || {
            title: `📋 ${deskState.cpt.toUpperCase()} Desk`,
            desc: 'Browse, filter, view, and manage collegiate records in modern view.',
            addLabel: 'Item'
        };

        // Update Desk Header
        const titleEl = document.getElementById('mvcDeskCptTitle');
        const descEl = document.getElementById('mvcDeskCptDesc');
        const badgeEl = document.getElementById('mvcDeskCptSlugBadge');
        const addBtn = document.getElementById('mvcDeskAddBtn');
        const addLabel = document.getElementById('mvcDeskAddLabel');
        const searchInput = document.getElementById('mvcContentDeskSearch');

        if (titleEl) titleEl.innerText = meta.title;
        if (descEl) descEl.innerText = meta.desc;
        if (badgeEl) badgeEl.innerText = deskState.cpt;
        if (addLabel) addLabel.innerText = meta.addLabel;
        if (addBtn) {
            addBtn.href = `post-new.php?post_type=${encodeURIComponent(deskState.cpt)}`;
        }
        if (searchInput) {
            searchInput.value = deskState.search;
            searchInput.placeholder = `Search ${meta.addLabel.toLowerCase()} records, memo #, or keyword...`;
        }

        // Reset Filter Pills active class
        document.querySelectorAll('#mvcDeskStatusFilters .mvc-filter-pill').forEach(pill => {
            if (pill.getAttribute('data-status') === deskState.status) {
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
            }
        });

        switchSection('content-desk');
    }

    async function loadContentDeskData() {
        const tableBody = document.getElementById('mvcContentDeskTableBody');
        const pageInfo = document.getElementById('mvcContentDeskPageInfo');
        const pageBadge = document.getElementById('mvcContentDeskCurrentPage');
        const prevBtn = document.getElementById('mvcContentDeskPrevBtn');
        const nextBtn = document.getElementById('mvcContentDeskNextBtn');

        if (!tableBody) return;

        tableBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align:center; padding: 40px; color: var(--mvc-text-muted);">
                    <span class="dashicons dashicons-update mvc-spin" style="font-size:24px; width:24px; height:24px;"></span>
                    <div style="margin-top: 8px;">Loading ${escapeHtml(deskState.cpt)} records from modern repository...</div>
                </td>
            </tr>
        `;

        try {
            const queryParams = new URLSearchParams({
                type: deskState.cpt,
                paged: deskState.page,
                search: deskState.search,
                status: deskState.status
            });

            const res = await apiFetch({ path: `/mvc/v1/content-list?${queryParams.toString()}` });

            // Update Counts on Filter Pills
            if (res.counts) {
                const countAll = document.getElementById('mvcDeskCountAll');
                const countPub = document.getElementById('mvcDeskCountPublish');
                const countDraft = document.getElementById('mvcDeskCountDraft');
                const countPending = document.getElementById('mvcDeskCountPending');

                if (countAll) countAll.innerText = res.counts.all ?? 0;
                if (countPub) countPub.innerText = res.counts.publish ?? 0;
                if (countDraft) countDraft.innerText = res.counts.draft ?? 0;
                if (countPending) countPending.innerText = res.counts.pending ?? 0;
            }

            deskState.totalPages = res.total_pages || 1;
            deskState.totalRecords = res.total || 0;

            if (pageInfo) {
                const start = deskState.totalRecords === 0 ? 0 : (deskState.page - 1) * 20 + 1;
                const end = Math.min(deskState.page * 20, deskState.totalRecords);
                pageInfo.innerText = `Showing ${start}-${end} of ${deskState.totalRecords} records`;
            }

            if (pageBadge) {
                pageBadge.innerText = `Page ${res.paged} of ${deskState.totalPages}`;
            }

            if (prevBtn) prevBtn.disabled = (res.paged <= 1);
            if (nextBtn) nextBtn.disabled = (res.paged >= deskState.totalPages);

            if (!res.items || res.items.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 50px; color: var(--mvc-text-muted);">
                            <div style="font-size: 28px; margin-bottom: 8px;">📭</div>
                            <div style="font-weight: 600; font-size: 15px; color: var(--mvc-text);">No ${escapeHtml(deskState.cpt)} records found</div>
                            <div style="font-size: 13px; margin-top: 4px;">Try clearing filters or search keywords, or add a new entry.</div>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            res.items.forEach(item => {
                let statusBadge = '<span class="mvc-badge mvc-badge-publish">Published</span>';
                if (item.status === 'draft') {
                    statusBadge = '<span class="mvc-badge mvc-badge-draft">Draft</span>';
                } else if (item.status === 'pending') {
                    statusBadge = '<span class="mvc-badge mvc-badge-pending">Pending Review</span>';
                }

                let metaDetails = [];
                if (item.memo_no) {
                    metaDetails.push(`<span class="mvc-memo-badge">Memo: ${escapeHtml(item.memo_no)}</span>`);
                }
                if (item.subtitle) {
                    metaDetails.push(`<span style="font-size: 12px; color: var(--mvc-text-muted);">${escapeHtml(item.subtitle)}</span>`);
                }
                if (item.has_pdf) {
                    metaDetails.push(`<span class="mvc-pdf-chip">📄 Certified PDF</span>`);
                }

                let thumbHtml = '';
                if (item.thumb) {
                    thumbHtml = `<img src="${escapeHtml(item.thumb)}" alt="" style="width: 44px; height: 44px; min-width: 44px; object-fit: cover; border-radius: 6px; border: 1px solid var(--mvc-border); box-shadow: 0 1px 3px rgba(0,0,0,0.08);">`;
                }

                html += `
                    <tr>
                        <td data-col="Title &amp; Reference">
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                ${thumbHtml}
                                <div>
                                    <a href="${escapeHtml(item.view_url)}" target="_blank" rel="noopener" style="font-weight: 600; color: var(--mvc-text); text-decoration: none; font-size: 14px;">
                                        ${escapeHtml(item.title)}
                                    </a>
                                    ${metaDetails.length > 0 ? `<div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap; margin-top:5px;">${metaDetails.join('')}</div>` : ''}
                                </div>
                            </div>
                        </td>
                        <td data-col="Department">
                            <span style="font-size: 12.5px; font-weight: 500; color: var(--mvc-text);">${escapeHtml(item.department)}</span>
                        </td>
                        <td data-col="Status">${statusBadge}</td>
                        <td data-col="Date">
                            <span style="font-size: 12.5px; color: var(--mvc-text-muted);">${escapeHtml(item.date)}</span>
                        </td>
                        <td data-col="Actions" style="text-align: right;">
                            <div class="mvc-desk-actions">
                                <a href="${escapeHtml(item.view_url)}" target="_blank" rel="noopener" class="mvc-desk-btn-sm mvc-desk-btn-view" title="Open Public Page in New Tab">
                                    <span class="dashicons dashicons-external" style="font-size:12px; width:12px; height:12px;"></span>
                                    <span>View</span>
                                </a>
                                <a href="${escapeHtml(item.edit_url)}" class="mvc-desk-btn-sm mvc-desk-btn-edit" title="Edit Full Attributes">
                                    <span class="dashicons dashicons-edit" style="font-size:12px; width:12px; height:12px;"></span>
                                    <span>Edit</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tableBody.innerHTML = html;

        } catch (err) {
            console.error('[MVC Content Desk Load Error]', err);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center; padding: 40px; color: #EF4444;">
                        Failed to load records from repository. Please check permissions or network connection.
                    </td>
                </tr>
            `;
        }
    }

    function initContentDeskControls() {
        // Search Input Debounce
        let deskSearchTimer = null;
        const searchInput = document.getElementById('mvcContentDeskSearch');
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(deskSearchTimer);
                deskSearchTimer = setTimeout(() => {
                    deskState.search = searchInput.value.trim();
                    deskState.page = 1;
                    loadContentDeskData();
                }, 300);
            });
        }

        // Status Filter Pills
        document.querySelectorAll('#mvcDeskStatusFilters .mvc-filter-pill').forEach(pill => {
            pill.addEventListener('click', () => {
                document.querySelectorAll('#mvcDeskStatusFilters .mvc-filter-pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                deskState.status = pill.getAttribute('data-status') || 'all';
                deskState.page = 1;
                loadContentDeskData();
            });
        });

        // Pagination Controls
        const prevBtn = document.getElementById('mvcContentDeskPrevBtn');
        const nextBtn = document.getElementById('mvcContentDeskNextBtn');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (deskState.page > 1) {
                    deskState.page--;
                    loadContentDeskData();
                }
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (deskState.page < deskState.totalPages) {
                    deskState.page++;
                    loadContentDeskData();
                }
            });
        }

        // Refresh Button
        const refreshBtn = document.getElementById('mvcDeskRefreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                loadContentDeskData();
            });
        }
    }

    function initUpdateLockToggle() {
        const toggleCheckbox = document.getElementById('mvcUpdateLockCheckbox');
        const slider = document.getElementById('mvcUpdateLockSlider');
        const knob = document.getElementById('mvcUpdateLockKnob');
        const badge = document.getElementById('mvcUpdateLockBadge');

        if (!toggleCheckbox) return;

        toggleCheckbox.addEventListener('change', async (e) => {
            const isLocked = e.target.checked;

            // Optimistic UI update
            if (slider && knob && badge) {
                slider.style.backgroundColor = isLocked ? '#002147' : '#CBD5E1';
                knob.style.left = isLocked ? '28px' : '4px';
                badge.className = isLocked ? 'mvc-badge mvc-badge-publish' : 'mvc-badge mvc-badge-warning';
                badge.innerText = isLocked ? '🔒 Safe Mode (Updates Locked)' : '⚠️ Maintenance Mode (Updates Allowed)';
            }

            try {
                const res = await apiFetch({
                    path: '/mvc/v1/settings/toggle-update-lock',
                    method: 'POST',
                    data: { locked: isLocked }
                });

                if (res && res.success) {
                    showToast(res.message || (isLocked ? 'Theme updates safely locked.' : 'Maintenance Mode enabled.'));
                } else {
                    throw new Error(res?.message || 'Failed to update lock status.');
                }
            } catch (err) {
                console.error('Update lock toggle error:', err);
                showToast(err.message || 'Error updating lock setting', 'danger');
                // Revert
                toggleCheckbox.checked = !isLocked;
                if (slider && knob && badge) {
                    slider.style.backgroundColor = !isLocked ? '#002147' : '#CBD5E1';
                    knob.style.left = !isLocked ? '28px' : '4px';
                    badge.className = !isLocked ? 'mvc-badge mvc-badge-publish' : 'mvc-badge mvc-badge-warning';
                    badge.innerText = !isLocked ? '🔒 Safe Mode (Updates Locked)' : '⚠️ Maintenance Mode (Updates Allowed)';
                }
            }
        });
    }

    function initMaintenanceModeToggle() {
        const toggleCheckbox = document.getElementById('mvcMaintenanceModeCheckbox');
        const slider = document.getElementById('mvcMaintenanceModeSlider');
        const knob = document.getElementById('mvcMaintenanceModeKnob');
        const badge = document.getElementById('mvcMaintenanceModeBadge');

        if (!toggleCheckbox) return;

        toggleCheckbox.addEventListener('change', async (e) => {
            const isEnabled = e.target.checked;

            // Optimistic UI update
            if (slider && knob && badge) {
                slider.style.backgroundColor = isEnabled ? '#B45309' : '#CBD5E1';
                knob.style.left = isEnabled ? '28px' : '4px';
                badge.className = isEnabled ? 'mvc-badge mvc-badge-warning' : 'mvc-badge mvc-badge-publish';
                badge.innerText = isEnabled ? '⚠️ Maintenance Active (Home Page Only)' : '🟢 Site Live & Operational';
            }

            try {
                const res = await apiFetch({
                    path: '/mvc/v1/settings/toggle-maintenance-mode',
                    method: 'POST',
                    data: { enabled: isEnabled }
                });

                if (res && res.success) {
                    showToast(res.message || (isEnabled ? 'Maintenance Mode enabled.' : 'Maintenance Mode disabled.'));
                } else {
                    throw new Error(res?.message || 'Failed to update maintenance mode status.');
                }
            } catch (err) {
                console.error('Maintenance mode toggle error:', err);
                showToast(err.message || 'Error updating maintenance mode setting', 'danger');
                // Revert
                toggleCheckbox.checked = !isEnabled;
                if (slider && knob && badge) {
                    slider.style.backgroundColor = !isEnabled ? '#B45309' : '#CBD5E1';
                    knob.style.left = !isEnabled ? '28px' : '4px';
                    badge.className = !isEnabled ? 'mvc-badge mvc-badge-warning' : 'mvc-badge mvc-badge-publish';
                    badge.innerText = !isEnabled ? '⚠️ Maintenance Active (Home Page Only)' : '🟢 Site Live & Operational';
                }
            }
        });
    }

    function initGlobalListeners() {
        const testMailForm = document.getElementById('mvc_admin_test_mail_form');
        if (testMailForm) {
            testMailForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const recipient = document.getElementById('mvc_test_mail_recipient').value.trim();
                const btn = document.getElementById('mvc_test_mail_submit');
                const feedback = document.getElementById('mvc_test_mail_feedback');

                if (!recipient) return;

                btn.disabled = true;
                btn.innerText = 'Dispatching Test Email...';
                feedback.style.color = '#0369A1';
                feedback.innerText = 'Transmitting through outgoing mail gateway...';

                const data = new FormData();
                data.append('action', 'mvc_admin_send_test_email');
                data.append('security', (window.MVC_DATA && window.MVC_DATA.admin_nonce) ? window.MVC_DATA.admin_nonce : '');
                data.append('recipient', recipient);

                const ajaxEndpoint = (window.MVC_DATA && window.MVC_DATA.ajax_url) ? window.MVC_DATA.ajax_url : '/wp-admin/admin-ajax.php';

                fetch(ajaxEndpoint, {
                    method: 'POST',
                    body: data
                })
                .then(r => r.json())
                .then(res => {
                    btn.disabled = false;
                    btn.innerText = 'Send Live Test Email';
                    if (res.success) {
                        feedback.style.color = '#166534';
                        feedback.innerText = res.data.message;
                        showToast(res.data.message, 'success');
                    } else {
                        feedback.style.color = '#B91C1C';
                        feedback.innerText = res.data.message || 'Dispatch failed.';
                        showToast(res.data.message || 'Dispatch failed', 'danger');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerText = 'Send Live Test Email';
                    feedback.style.color = '#B91C1C';
                    feedback.innerText = 'Network error contacting test server.';
                });
            });
        }
    }

    function showToast(message, type = 'success') {
        let container = document.querySelector('.mvc-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'mvc-toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = 'mvc-toast';
        if (type === 'danger') {
            toast.style.borderLeftColor = '#EF4444';
        }
        toast.innerText = message;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
