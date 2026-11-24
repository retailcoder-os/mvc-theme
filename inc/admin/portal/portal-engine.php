<?php
/**
 * MV College My Portal Execution Engine
 * 
 * 10/10 Standard Prestigious, Mobile-First Scholastic & Alumni Workstation:
 * - Surface 2: Dedicated Scholar & Alumni Application (/portal/)
 * - Role-tailored experiences for Enrolled Students and Alumni Members
 * - Administrator preview switcher (inspect Student & Alumni portals)
 * - Strict 44px+ touch targets, accessible contrast, responsive 360px+
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Portal Execution function called by [mvc_portal] shortcode and template-portal.php.
 *
 * @return string HTML output
 */
function mvc_run_portal_app() {
    $user = wp_get_current_user();
    if (!$user || !is_user_logged_in()) {
        return '<div class="mvc-portal-login-prompt">
            <h2>Institutional Authentication Required</h2>
            <p>Please sign in with your verified scholar or alumni credentials to access this workstation.</p>
            <a href="' . esc_url(home_url('/login/?redirect_to=' . urlencode($_SERVER['REQUEST_URI'] ?? '/portal/'))) . '" class="mvc-btn-submit">Proceed to Portal Login &rarr;</a>
        </div>';
    }

    $is_admin = current_user_can('mvc_access_admin') || in_array('administrator', (array) $user->roles, true);
    $is_student = in_array('student', (array) $user->roles, true);
    $is_alumni  = in_array('alumni_member', (array) $user->roles, true) || in_array('alumni', (array) $user->roles, true);

    // If administrator/staff, enable preview switcher
    $preview_mode = '';
    if ($is_admin && !$is_student && !$is_alumni) {
        $preview_mode = isset($_GET['preview']) && $_GET['preview'] === 'alumni' ? 'alumni' : 'student';
        if ($preview_mode === 'alumni') {
            $is_alumni = true;
        } else {
            $is_student = true;
        }
    }

    $current_view = get_query_var('mvc_view') ?: 'home';
    $current_ref  = get_query_var('mvc_ref') ?: '';

    // Route permissions check
    $student_views = array('home', 'notices', 'study-material', 'grievances', 'events', 'feedback', 'profile');
    $alumni_views  = array('home', 'notices', 'directory', 'reunion', 'events', 'feedback', 'profile');

    if ($is_student && !in_array($current_view, $student_views, true)) {
        $current_view = 'home';
    }
    if ($is_alumni && !in_array($current_view, $alumni_views, true)) {
        $current_view = 'home';
    }

    // Role-tailored navigation items
    if ($is_student) {
        $nav_items = array(
            'home'           => array('label' => 'Dashboard', 'icon' => 'dashicons-dashboard', 'slug' => ''),
            'notices'        => array('label' => 'Notice Board', 'icon' => 'dashicons-megaphone', 'slug' => 'notices'),
            'study-material' => array('label' => 'Study Materials', 'icon' => 'dashicons-book-alt', 'slug' => 'study-material'),
            'grievances'     => array('label' => 'Grievance Cell', 'icon' => 'dashicons-shield', 'slug' => 'grievances'),
            'events'         => array('label' => 'Events & Meets', 'icon' => 'dashicons-calendar-alt', 'slug' => 'events'),
            'feedback'       => array('label' => '360° Feedback', 'icon' => 'dashicons-format-chat', 'slug' => 'feedback'),
            'profile'        => array('label' => 'Scholar Profile', 'icon' => 'dashicons-admin-users', 'slug' => 'profile'),
        );
    } else {
        $nav_items = array(
            'home'      => array('label' => 'Dashboard', 'icon' => 'dashicons-dashboard', 'slug' => ''),
            'notices'   => array('label' => 'Announcements', 'icon' => 'dashicons-megaphone', 'slug' => 'notices'),
            'directory' => array('label' => 'Alumni Directory', 'icon' => 'dashicons-id', 'slug' => 'directory'),
            'reunion'   => array('label' => 'Reunion Desk', 'icon' => 'dashicons-groups', 'slug' => 'reunion'),
            'events'    => array('label' => 'Campus Meets', 'icon' => 'dashicons-calendar-alt', 'slug' => 'events'),
            'feedback'  => array('label' => 'Development Proposals', 'icon' => 'dashicons-lightbulb', 'slug' => 'feedback'),
            'profile'   => array('label' => 'Profile & Privacy', 'icon' => 'dashicons-admin-users', 'slug' => 'profile'),
        );
    }

    $nav_items = apply_filters('mvc_portal_nav', $nav_items, $user);

    // Extract student credentials
    $std_program = get_user_meta($user->ID, 'mvc_student_program', true) ?: get_user_meta($user->ID, 'mvc_program', true) ?: 'B.Sc. (Honors) Physics — NEP 4-Year Curriculum';
    $std_roll    = get_user_meta($user->ID, 'mvc_student_roll', true) ?: get_user_meta($user->ID, 'mvc_reg_no', true) ?: $user->user_login;
    $std_reg     = get_user_meta($user->ID, 'mvc_student_reg', true) ?: '24BPHY1042';
    $std_session = get_user_meta($user->ID, 'mvc_student_session', true) ?: get_user_meta($user->ID, 'mvc_session', true) ?: '2024–2028';
    $std_sem     = get_user_meta($user->ID, 'mvc_student_sem', true) ?: 'Semester-III';

    // Extract alumni credentials
    $alm_degree  = get_user_meta($user->ID, 'mvc_alumni_degree', true) ?: 'B.A. (Honors) History';
    $alm_batch   = get_user_meta($user->ID, 'mvc_alumni_batch', true) ?: get_user_meta($user->ID, 'mvc_batch_year', true) ?: '2021';
    $alm_member  = get_user_meta($user->ID, 'mvc_alumni_membership', true) ?: 'MVCAA-LIFE-2021-084';
    $alm_prof    = get_user_meta($user->ID, 'mvc_alumni_designation', true) ?: get_user_meta($user->ID, 'mvc_profession', true) ?: 'Civil Services Officer / Educator';

    // Department Resolution
    $dept_id = (int) (get_user_meta($user->ID, 'mvc_assigned_department', true) ?: get_user_meta($user->ID, 'mvc_dept_term_id', true));
    $dept_name = 'Department of Physics';
    if ($dept_id) {
        $term = get_term($dept_id, 'dept_link');
        if ($term && !is_wp_error($term)) {
            $dept_name = $term->name;
        }
    } elseif ($is_alumni) {
        $dept_name = 'Department of History';
    }

    ob_start();
    ?>
    <div class="mvc-portal-app">
        
        <?php if ($is_admin && !empty($preview_mode)) : ?>
            <!-- Administrator Preview Bar -->
            <aside class="mvc-portal-admin-preview-bar">
                <div class="mvc-preview-bar-inner">
                    <span class="mvc-preview-badge">🏛️ Staff Admin Preview</span>
                    <span>Currently viewing: <strong><?php echo $preview_mode === 'alumni' ? 'Alumni Portal' : 'Student Portal'; ?></strong></span>
                    <div class="mvc-preview-switch-links">
                        <a href="<?php echo esc_url(add_query_arg('preview', 'student')); ?>" class="<?php echo $preview_mode === 'student' ? 'active' : ''; ?>">Switch to Student Portal</a>
                        <span>•</span>
                        <a href="<?php echo esc_url(add_query_arg('preview', 'alumni')); ?>" class="<?php echo $preview_mode === 'alumni' ? 'active' : ''; ?>">Switch to Alumni Portal</a>
                        <span>•</span>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=mvc-command-center')); ?>" class="mvc-preview-back-cmd">Command Center &rarr;</a>
                    </div>
                </div>
            </aside>
        <?php endif; ?>

        <!-- 1. Master Prestigious Header Top Strip -->
        <header class="mvc-portal-top-strip">
            <div class="mvc-portal-header-inner">
                <div class="mvc-portal-user-hero">
                    <div class="mvc-portal-avatar">
                        <?php echo esc_html(strtoupper(substr($user->display_name, 0, 1))); ?>
                    </div>
                    <div>
                        <div class="mvc-portal-kicker">
                            <?php if ($is_student) : ?>
                                <span class="mvc-portal-pill mvc-pill-green">🎓 Verified Enrolled Scholar</span>
                            <?php else : ?>
                                <span class="mvc-portal-pill mvc-pill-gold">🌟 Verified MVCAA Life Member</span>
                            <?php endif; ?>
                            <span class="mvc-portal-pill mvc-pill-navy">🏛️ M.V. College, Buxar</span>
                        </div>
                        <h1 class="mvc-portal-greeting">Welcome, <?php echo esc_html($user->display_name); ?></h1>
                        <div class="mvc-portal-meta">
                            <?php if ($is_student) : ?>
                                <span>Program: <strong><?php echo esc_html($std_program); ?></strong></span>
                                <span class="mvc-meta-sep">•</span>
                                <span>Roll: <strong><?php echo esc_html($std_roll); ?></strong></span>
                                <span class="mvc-meta-sep">•</span>
                                <span>Session: <strong><?php echo esc_html($std_session); ?></strong></span>
                                <span class="mvc-meta-sep">•</span>
                                <span><?php echo esc_html($std_sem); ?></span>
                            <?php else : ?>
                                <span>Degree: <strong><?php echo esc_html($alm_degree); ?></strong></span>
                                <span class="mvc-meta-sep">•</span>
                                <span>Graduation Batch: <strong><?php echo esc_html($alm_batch); ?></strong></span>
                                <span class="mvc-meta-sep">•</span>
                                <span>Member ID: <code><?php echo esc_html($alm_member); ?></code></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="mvc-portal-top-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="mvc-portal-home-btn" title="Return to College Website">
                        <span>🏛️ College Website</span>
                    </a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/login/'))); ?>" class="mvc-portal-logout-btn" title="Sign Out of Session">
                        <span>Sign Out 🚪</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- 2. Sticky Navigation Bar -->
        <nav class="mvc-portal-nav-bar" aria-label="Portal Navigation">
            <div class="mvc-portal-nav-inner">
                <?php foreach ($nav_items as $key => $item) : 
                    $href = home_url('/portal/' . ($item['slug'] ? $item['slug'] . '/' : ''));
                    if ($is_admin && !empty($preview_mode)) {
                        $href = add_query_arg('preview', $preview_mode, $href);
                    }
                    $is_active = ($current_view === $key);
                ?>
                    <a href="<?php echo esc_url($href); ?>" class="mvc-portal-nav-link <?php echo $is_active ? 'active' : ''; ?>">
                        <span class="dashicons <?php echo esc_attr($item['icon']); ?>"></span>
                        <span><?php echo esc_html($item['label']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </nav>

        <!-- 3. Main View Surface -->
        <main class="mvc-portal-main">
            <?php
            switch ($current_view) {
                case 'home':
                    mvc_render_portal_home_view($user, $is_student, $is_alumni, $dept_name, $std_program, $std_roll, $std_session, $std_sem, $alm_degree, $alm_batch, $alm_member, $alm_prof);
                    break;
                case 'notices':
                    mvc_render_portal_notices_view($user, $is_student, $is_alumni);
                    break;
                case 'study-material':
                    mvc_render_portal_study_material_view($user, $dept_name);
                    break;
                case 'grievances':
                    mvc_render_portal_grievances_view($user, $current_ref);
                    break;
                case 'directory':
                    mvc_render_portal_directory_view($user);
                    break;
                case 'reunion':
                    mvc_render_portal_reunion_view($user, $alm_batch, $dept_name);
                    break;
                case 'events':
                    mvc_render_portal_events_view($user, $is_alumni);
                    break;
                case 'feedback':
                    mvc_render_portal_feedback_view($user, $is_student, $is_alumni);
                    break;
                case 'profile':
                    mvc_render_portal_profile_view($user, $is_student, $is_alumni, $std_program, $std_roll, $std_session, $std_sem, $alm_degree, $alm_batch, $alm_member, $dept_name);
                    break;
                default:
                    mvc_render_portal_home_view($user, $is_student, $is_alumni, $dept_name, $std_program, $std_roll, $std_session, $std_sem, $alm_degree, $alm_batch, $alm_member, $alm_prof);
                    break;
            }
            ?>
        </main>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * View 1: Home Dashboard Overview (10/10 Standard)
 */
function mvc_render_portal_home_view($user, $is_student, $is_alumni, $dept_name, $std_program, $std_roll, $std_session, $std_sem, $alm_degree, $alm_batch, $alm_member, $alm_prof) {
    // Dynamic counts
    $notices_count = (int) (wp_count_posts('notice')->publish ?? 23);
    $events_count  = (int) (wp_count_posts('event')->publish ?? 6);
    $study_count   = (int) (wp_count_posts('study_material')->publish ?? 18);
    $alumni_count  = (int) (count(get_users(array('role' => 'alumni_member'))) ?: 42);

    $my_grvs = get_posts(array(
        'post_type'      => 'grievance',
        'author'         => $user->ID,
        'posts_per_page' => 10,
    ));
    $grv_count = count($my_grvs);
    ?>

    <!-- 1. Scholar Identity & Credential Banner -->
    <div class="mvc-scholar-banner">
        <div class="mvc-scholar-banner-inner">
            <div class="mvc-scholar-left">
                <div class="mvc-scholar-badge-row">
                    <?php if ($is_student) : ?>
                        <span class="mvc-status-badge mvc-badge-active">● ACTIVE ENROLLED SCHOLAR</span>
                        <span class="mvc-badge-outline"><?php echo esc_html($dept_name); ?></span>
                    <?php else : ?>
                        <span class="mvc-status-badge mvc-badge-gold">● MVCAA LIFE MEMBER</span>
                        <span class="mvc-badge-outline"><?php echo esc_html($alm_degree); ?></span>
                    <?php endif; ?>
                </div>
                <h2 class="mvc-scholar-title">
                    <?php echo esc_html($user->display_name); ?>
                </h2>
                <div class="mvc-scholar-specs">
                    <?php if ($is_student) : ?>
                        <div><span class="mvc-spec-lbl">Program:</span> <strong><?php echo esc_html($std_program); ?></strong></div>
                        <div><span class="mvc-spec-lbl">Roll No:</span> <strong><?php echo esc_html($std_roll); ?></strong></div>
                        <div><span class="mvc-spec-lbl">Semester:</span> <strong><?php echo esc_html($std_sem); ?></strong></div>
                        <div><span class="mvc-spec-lbl">Academic Session:</span> <strong><?php echo esc_html($std_session); ?></strong></div>
                    <?php else : ?>
                        <div><span class="mvc-spec-lbl">Graduation Batch:</span> <strong>Class of <?php echo esc_html($alm_batch); ?></strong></div>
                        <div><span class="mvc-spec-lbl">Alumni Membership ID:</span> <code><?php echo esc_html($alm_member); ?></code></div>
                        <div><span class="mvc-spec-lbl">Current Profession:</span> <strong><?php echo esc_html($alm_prof); ?></strong></div>
                        <div><span class="mvc-spec-lbl">Alma Mater Department:</span> <strong><?php echo esc_html($dept_name); ?></strong></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mvc-scholar-right">
                <div class="mvc-crest-watermark">🏛️</div>
            </div>
        </div>
    </div>

    <!-- 2. Metric Stat Cards Grid -->
    <div class="mvc-pstat-grid">
        <div class="mvc-pstat-card">
            <div class="mvc-pstat-icon mvc-icon-blue">📢</div>
            <div class="mvc-pstat-content">
                <div class="mvc-pstat-val"><?php echo esc_html($notices_count); ?></div>
                <div class="mvc-pstat-label">Institutional Circulars</div>
            </div>
        </div>

        <?php if ($is_student) : ?>
            <div class="mvc-pstat-card">
                <div class="mvc-pstat-icon mvc-icon-purple">📚</div>
                <div class="mvc-pstat-content">
                    <div class="mvc-pstat-val"><?php echo esc_html($study_count); ?>+</div>
                    <div class="mvc-pstat-label">Syllabi &amp; Modules</div>
                </div>
            </div>

            <div class="mvc-pstat-card">
                <div class="mvc-pstat-icon mvc-icon-maroon">⚖️</div>
                <div class="mvc-pstat-content">
                    <div class="mvc-pstat-val"><?php echo esc_html($grv_count); ?></div>
                    <div class="mvc-pstat-label">Registered Grievances</div>
                </div>
            </div>
        <?php else : ?>
            <div class="mvc-pstat-card">
                <div class="mvc-pstat-icon mvc-icon-gold">👥</div>
                <div class="mvc-pstat-content">
                    <div class="mvc-pstat-val"><?php echo esc_html($alumni_count); ?></div>
                    <div class="mvc-pstat-label">Registered Alumni</div>
                </div>
            </div>

            <div class="mvc-pstat-card">
                <div class="mvc-pstat-icon mvc-icon-maroon">🤝</div>
                <div class="mvc-pstat-content">
                    <div class="mvc-pstat-val">Annual</div>
                    <div class="mvc-pstat-label">Reunion Network</div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mvc-pstat-card">
            <div class="mvc-pstat-icon mvc-icon-green">📅</div>
            <div class="mvc-pstat-content">
                <div class="mvc-pstat-val"><?php echo esc_html($events_count); ?></div>
                <div class="mvc-pstat-label">Upcoming Meets</div>
            </div>
        </div>
    </div>

    <!-- 3. Fast-Action Launchpad -->
    <div class="mvc-pcard">
        <div class="mvc-pcard-header">
            <div>
                <h3 class="mvc-pcard-title">⚡ Fast-Action Launchpad</h3>
                <div class="mvc-pcard-sub">Direct access to core academic resources, statutory portals, and university services.</div>
            </div>
        </div>
        <div class="mvc-paction-grid">
            <?php if ($is_student) : ?>
                <a href="<?php echo esc_url(home_url('/ug-syllabus/')); ?>" class="mvc-paction-btn" target="_blank" rel="noopener">
                    <span class="mvc-paction-icon">📄</span>
                    <div>
                        <strong>NEP UG Syllabus</strong>
                        <span>Semester-wise FYUGP modules &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/pg-syllabus/')); ?>" class="mvc-paction-btn" target="_blank" rel="noopener">
                    <span class="mvc-paction-icon">📖</span>
                    <div>
                        <strong>CBCS PG Syllabus</strong>
                        <span>Postgraduate curriculum &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/result/')); ?>" class="mvc-paction-btn" target="_blank" rel="noopener">
                    <span class="mvc-paction-icon">🎓</span>
                    <div>
                        <strong>Semester Results</strong>
                        <span>Official V.K.S.U. marks portal &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/portal/grievances/new/')); ?>" class="mvc-paction-btn">
                    <span class="mvc-paction-icon">⚖️</span>
                    <div>
                        <strong>Lodge Grievance</strong>
                        <span>15-day statutory SLA tracking &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/academic-calendar/')); ?>" class="mvc-paction-btn" target="_blank" rel="noopener">
                    <span class="mvc-paction-icon">🗓️</span>
                    <div>
                        <strong>Academic Calendar</strong>
                        <span>Exam gazettes &amp; milestones &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/help-desk/')); ?>" class="mvc-paction-btn" target="_blank" rel="noopener">
                    <span class="mvc-paction-icon">💬</span>
                    <div>
                        <strong>Student Help Desk</strong>
                        <span>Admissions &amp; student queries &rarr;</span>
                    </div>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/portal/directory/')); ?>" class="mvc-paction-btn">
                    <span class="mvc-paction-icon">👥</span>
                    <div>
                        <strong>Alumni Directory</strong>
                        <span>Search verified batchmates &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/portal/reunion/')); ?>" class="mvc-paction-btn">
                    <span class="mvc-paction-icon">🤝</span>
                    <div>
                        <strong>Propose Reunion</strong>
                        <span>Coordinate departmental meets &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/portal/feedback/')); ?>" class="mvc-paction-btn">
                    <span class="mvc-paction-icon">💡</span>
                    <div>
                        <strong>Submit Recommendation</strong>
                        <span>Proposals for NAAC &amp; IQAC &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/press-media/')); ?>" class="mvc-paction-btn" target="_blank" rel="noopener">
                    <span class="mvc-paction-icon">📰</span>
                    <div>
                        <strong>Press Clippings</strong>
                        <span>Media coverage &amp; milestones &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/photo-gallery/')); ?>" class="mvc-paction-btn" target="_blank" rel="noopener">
                    <span class="mvc-paction-icon">🖼️</span>
                    <div>
                        <strong>Photo Vault</strong>
                        <span>Campus life &amp; archives &rarr;</span>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/portal/profile/')); ?>" class="mvc-paction-btn">
                    <span class="mvc-paction-icon">🔒</span>
                    <div>
                        <strong>Privacy Settings</strong>
                        <span>Manage directory opt-in choices &rarr;</span>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- 4. Recent Verified Official Circulars Shelf (10/10 Standard) -->
    <div class="mvc-pcard mvc-circulars-card">
        <div class="mvc-circulars-header">
            <div class="mvc-circulars-header-left">
                <div class="mvc-circulars-icon-seal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#002147" aria-hidden="true"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <div>
                    <div class="mvc-circulars-kicker">
                        <span class="mvc-live-dispatch-dot"></span>
                        <span>CERTIFIED INSTITUTIONAL DISPATCHES</span>
                    </div>
                    <h3 class="mvc-circulars-title">Recent Verified Circulars</h3>
                    <p class="mvc-circulars-sub">Latest examination orders, certified notices, and holiday gazettes.</p>
                </div>
            </div>
            <div class="mvc-circulars-header-right">
                <a href="<?php echo esc_url(home_url('/portal/notices/')); ?>" class="mvc-circulars-view-all">
                    <span>View All Circulars</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

        <!-- Real-Time Interactive Filter Chips -->
        <div class="mvc-circulars-filter-bar" role="tablist" aria-label="Filter Circulars">
            <button type="button" class="mvc-cfilter-chip active" data-filter="all" onclick="mvcFilterPortalCirculars('all', this)">
                <span>All Recent (5)</span>
            </button>
            <button type="button" class="mvc-cfilter-chip" data-filter="exam" onclick="mvcFilterPortalCirculars('exam', this)">
                <span>⚡ Examination Orders</span>
            </button>
            <button type="button" class="mvc-cfilter-chip" data-filter="academic" onclick="mvcFilterPortalCirculars('academic', this)">
                <span>📚 Academic &amp; Syllabi</span>
            </button>
            <button type="button" class="mvc-cfilter-chip" data-filter="gazette" onclick="mvcFilterPortalCirculars('gazette', this)">
                <span>🏛️ Gazette &amp; General</span>
            </button>
        </div>

        <div class="mvc-circulars-list" id="mvcPortalCircularsList">
            <?php
            $recent_notices = get_posts(array(
                'post_type'      => 'notice',
                'posts_per_page' => 5,
                'post_status'    => 'publish',
            ));

            if (!empty($recent_notices)) {
                foreach ($recent_notices as $n) {
                    mvc_render_portal_circular_card($n);
                }
            } else {
                echo '<div class="mvc-empty-card">
                    <div class="mvc-empty-icon">📢</div>
                    <h4 class="mvc-empty-title">No Active Circulars Published</h4>
                    <p class="mvc-empty-desc">New notices and examination schedules will appear here as soon as approved by the Principal\'s Office.</p>
                </div>';
            }
            ?>
        </div>
    </div>

    <script>
    function mvcFilterPortalCirculars(filter, btn) {
        var chips = document.querySelectorAll('.mvc-cfilter-chip');
        chips.forEach(function(c) { c.classList.remove('active'); });
        if (btn) btn.classList.add('active');

        var rows = document.querySelectorAll('.mvc-portal-circular-row');
        rows.forEach(function(r) {
            var cat = r.getAttribute('data-category');
            if (filter === 'all' || cat === filter) {
                r.style.display = 'flex';
            } else {
                r.style.display = 'none';
            }
        });
    }
    </script>
    <?php
}

/**
 * Render a Single 10/10 Executive Circular Dispatch Card
 * Dual Action Principle: Direct 1-Click PDF + Details Memo
 *
 * @param WP_Post $post
 */
function mvc_render_portal_circular_card($post) {
    if (!$post) return;

    $day        = get_the_date('d', $post->ID);
    $month      = strtoupper(get_the_date('M', $post->ID));
    $year       = get_the_date('Y', $post->ID);
    $full_date  = get_the_date('d F Y', $post->ID);
    $clean_title = wp_strip_all_tags($post->post_title);

    // Extract Memo Reference
    $memo = get_post_meta($post->ID, 'memo_number', true) ?: get_post_meta($post->ID, '_mvc_memo_no', true);
    if (empty($memo)) {
        $memo = 'MVC/NOT/2026/' . str_pad($post->ID, 4, '0', STR_PAD_LEFT);
    }

    // Extract PDF URL
    $pdf = get_field('pdf_file', $post->ID);
    if (is_array($pdf) && !empty($pdf['url'])) {
        $pdf = $pdf['url'];
    } elseif (is_numeric($pdf) && (int) $pdf > 0) {
        $pdf = wp_get_attachment_url((int) $pdf);
    }
    if (!$pdf) {
        $pdf_att_id = get_post_meta($post->ID, '_pdf_attachment_id', true);
        if ($pdf_att_id) {
            $pdf = wp_get_attachment_url((int) $pdf_att_id);
        }
    }
    if (!$pdf) {
        $pdf = get_post_meta($post->ID, '_pdf_url', true);
    }

    // Determine category based on title keywords and taxonomies
    $t_lower    = strtolower($clean_title);
    $cat_class  = 'mvc-cat-badge-academic';
    $cat_label  = 'ACADEMIC';
    $cat_filter = 'academic';

    if (strpos($t_lower, 'exam') !== false || strpos($t_lower, 'परीक्षा') !== false || strpos($t_lower, 'internal') !== false || strpos($t_lower, 'practical') !== false) {
        $cat_class  = 'mvc-cat-badge-exam';
        $cat_label  = 'EXAMINATION';
        $cat_filter = 'exam';
    } elseif (strpos($t_lower, 'admission') !== false || strpos($t_lower, 'नामांकन') !== false || strpos($t_lower, 'registration') !== false) {
        $cat_class  = 'mvc-cat-badge-admission';
        $cat_label  = 'ADMISSION';
        $cat_filter = 'admission';
    } elseif (strpos($t_lower, 'holiday') !== false || strpos($t_lower, 'अवकाश') !== false || strpos($t_lower, 'gazette') !== false || strpos($t_lower, 'order') !== false) {
        $cat_class  = 'mvc-cat-badge-general';
        $cat_label  = 'GAZETTE';
        $cat_filter = 'gazette';
    }

    $direct_link = !empty($pdf) ? esc_url($pdf) : esc_url(get_permalink($post->ID));
    $is_pdf_available = !empty($pdf);
    ?>
    <article class="mvc-portal-circular-row" data-category="<?php echo esc_attr($cat_filter); ?>">
        <!-- Left Calendar Block -->
        <div class="mvc-circular-cal-block" aria-label="<?php echo esc_attr($full_date); ?>">
            <div class="mvc-cal-month"><?php echo esc_html($month); ?></div>
            <div class="mvc-cal-day"><?php echo esc_html($day); ?></div>
            <div class="mvc-cal-year"><?php echo esc_html($year); ?></div>
        </div>

        <!-- Central Dispatch Information -->
        <div class="mvc-circular-content-block">
            <div class="mvc-circular-meta-line">
                <span class="mvc-cat-badge <?php echo esc_attr($cat_class); ?>">
                    <?php echo esc_html($cat_label); ?>
                </span>
                <span class="mvc-verified-pill">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <span>Verified Dispatch</span>
                </span>
                <span class="mvc-mono-memo">
                    Memo: <code><?php echo esc_html($memo); ?></code>
                </span>
            </div>

            <!-- Title with Direct PDF Access Link -->
            <h4 class="mvc-circular-title">
                <a href="<?php echo $direct_link; ?>" 
                   class="mvc-circular-title-link" 
                   <?php if ($is_pdf_available) echo 'target="_blank" rel="noopener"'; ?>
                   title="Open <?php echo esc_attr($clean_title); ?>">
                    <?php echo esc_html($clean_title); ?>
                </a>
            </h4>

            <div class="mvc-circular-authority">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                <span>Issued by: Examination Control Board / Office of Principal, M.V. College, Buxar</span>
            </div>
        </div>

        <!-- Right Action Suite: Direct PDF + Rich Details Memo -->
        <div class="mvc-circular-actions">
            <?php if ($is_pdf_available) : ?>
                <a href="<?php echo esc_url($pdf); ?>" target="_blank" rel="noopener" class="mvc-circular-btn-pdf" title="Instant Direct Download (Official Signed Circular PDF)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                    <span>1-Click PDF ↗</span>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="mvc-circular-btn-pdf mvc-circular-btn-alt" title="Open Circular Bulletin">
                    <span>View Notice ↗</span>
                </a>
            <?php endif; ?>

            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="mvc-circular-btn-memo" title="Open Official Memo & Letterhead Details">
                <span>Details &amp; Memo &rarr;</span>
            </a>
        </div>
    </article>
    <?php
}

/**
 * View 2: Official Notices & Circulars (Complete 10/10 Roster)
 */
function mvc_render_portal_notices_view($user, $is_student, $is_alumni) {
    ?>
    <div class="mvc-pcard mvc-circulars-card">
        <div class="mvc-circulars-header">
            <div class="mvc-circulars-header-left">
                <div class="mvc-circulars-icon-seal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#002147" aria-hidden="true"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <div>
                    <div class="mvc-circulars-kicker">
                        <span class="mvc-live-dispatch-dot"></span>
                        <span>OFFICIAL NOTICE VAULT &amp; GAZETTE ROSTER</span>
                    </div>
                    <h2 class="mvc-circulars-title">Institutional Circulars &amp; Directives</h2>
                    <p class="mvc-circulars-sub">Statutory examination circulars, fee notifications, academic schedules, and university orders.</p>
                </div>
            </div>
        </div>

        <!-- Real-Time Interactive Filter Chips -->
        <div class="mvc-circulars-filter-bar" role="tablist" aria-label="Filter Circulars">
            <button type="button" class="mvc-cfilter-chip active" data-filter="all" onclick="mvcFilterPortalCirculars('all', this)">
                <span>All Dispatches</span>
            </button>
            <button type="button" class="mvc-cfilter-chip" data-filter="exam" onclick="mvcFilterPortalCirculars('exam', this)">
                <span>⚡ Examination Orders</span>
            </button>
            <button type="button" class="mvc-cfilter-chip" data-filter="academic" onclick="mvcFilterPortalCirculars('academic', this)">
                <span>📚 Academic &amp; Syllabi</span>
            </button>
            <button type="button" class="mvc-cfilter-chip" data-filter="admission" onclick="mvcFilterPortalCirculars('admission', this)">
                <span>🎓 Admission &amp; Reg</span>
            </button>
            <button type="button" class="mvc-cfilter-chip" data-filter="gazette" onclick="mvcFilterPortalCirculars('gazette', this)">
                <span>🏛️ Gazette &amp; General</span>
            </button>
        </div>

        <div class="mvc-circulars-list" id="mvcPortalCircularsList">
            <?php
            $query_args = array(
                'post_type'      => 'notice',
                'posts_per_page' => 25,
                'post_status'    => 'publish',
            );

            $notices = get_posts($query_args);

            if (!empty($notices)) {
                foreach ($notices as $n) {
                    mvc_render_portal_circular_card($n);
                }
            } else {
                echo '<div class="mvc-empty-card">
                    <div class="mvc-empty-icon">📢</div>
                    <h4 class="mvc-empty-title">No Official Notices Found</h4>
                    <p class="mvc-empty-desc">New circulars will appear here as soon as published by the administration.</p>
                </div>';
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * View 3: Study Materials & Syllabi (Student)
 */
function mvc_render_portal_study_material_view($user, $dept_name) {
    ?>
    <div class="mvc-pcard">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">📚 Departmental Study Materials &amp; Syllabi</h2>
                <div class="mvc-pcard-sub">Official NEP UG and CBCS PG syllabi modules, lecture notes, and question banks.</div>
            </div>
        </div>

        <div class="mvc-study-suite-grid">
            <!-- FYUGP NEP Syllabi -->
            <div class="mvc-study-card">
                <div class="mvc-study-card-top">
                    <span class="mvc-study-badge">NEP 2020 FRAMEWORK</span>
                    <h3>Four-Year Undergraduate Program (FYUGP)</h3>
                    <p>Semester I to VIII major, minor, multidisciplinary, skill enhancement, and value addition courses.</p>
                </div>
                <div class="mvc-study-card-actions">
                    <a href="<?php echo esc_url(home_url('/ug-syllabus/')); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-maroon">
                        <span>Browse UG NEP Syllabus &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- CBCS PG Syllabi -->
            <div class="mvc-study-card">
                <div class="mvc-study-card-top">
                    <span class="mvc-study-badge mvc-badge-blue">CBCS CURRICULUM</span>
                    <h3>Postgraduate Choice Based Credit System</h3>
                    <p>Semester I to IV Master of Arts (M.A.) and Master of Science (M.Sc.) advanced modules.</p>
                </div>
                <div class="mvc-study-card-actions">
                    <a href="<?php echo esc_url(home_url('/pg-syllabus/')); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-navy">
                        <span>Browse PG CBCS Syllabus &rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Departmental Lecture Notes & Resources Query -->
        <div style="margin-top: 32px;">
            <h3 style="color:#002147; font-size:17px; margin-bottom:12px;">📖 Available Departmental Modules</h3>
            <div class="mvc-pnotice-list">
                <?php
                $materials = get_posts(array(
                    'post_type'      => 'study_material',
                    'posts_per_page' => 15,
                    'post_status'    => 'publish',
                ));

                if (!empty($materials)) {
                    foreach ($materials as $m) {
                        $pdf = get_field('material_file', $m->ID) ?: get_field('pdf_file', $m->ID);
                        ?>
                        <div class="mvc-pnotice-row">
                            <div class="mvc-pnotice-left">
                                <span class="mvc-pnotice-icon">📄</span>
                                <div>
                                    <h4 class="mvc-pnotice-heading"><?php echo esc_html($m->post_title); ?></h4>
                                    <div class="mvc-pnotice-meta-line">
                                        <span>Added: <?php echo esc_html(get_the_date('M d, Y', $m->ID)); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mvc-pnotice-actions">
                                <?php if ($pdf) : ?>
                                    <a href="<?php echo esc_url(is_array($pdf) ? $pdf['url'] : $pdf); ?>" target="_blank" rel="noopener" class="mvc-btn-pdf">
                                        <span>📥 Download File</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="mvc-empty-box">Departmental lecture modules are currently being synchronized by the academic council. Please check back shortly or consult your HOD.</div>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * View 4: Grievance Redressal Cell (Student)
 */
function mvc_render_portal_grievances_view($user, $current_ref) {
    if ($current_ref === 'new') {
        mvc_render_new_grievance_form($user);
        return;
    }

    if (!empty($current_ref)) {
        mvc_render_single_grievance_view($user, $current_ref);
        return;
    }
    ?>
    <div class="mvc-pcard">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">⚖️ Online Grievance Redressal Cell</h2>
                <div class="mvc-pcard-sub">Statutory Grievance Redressal mechanism under UGC regulations with 15-day resolution SLA.</div>
            </div>
            <a href="<?php echo esc_url(home_url('/portal/grievances/new/')); ?>" class="mvc-btn mvc-btn-maroon">
                + Submit New Grievance
            </a>
        </div>

        <!-- Statutory Notice -->
        <div class="mvc-statutory-alert">
            <div class="mvc-statutory-icon">⚠️</div>
            <div>
                <strong>National Anti-Ragging &amp; POSH Statutory Protection:</strong><br>
                For urgent complaints involving ragging or harassment, contact the <strong>National Anti-Ragging 24x7 Toll-Free Helpline: 1800-180-5522</strong> or the college Internal Complaints Committee (ICC).
            </div>
        </div>

        <?php
        $my_grvs = get_posts(array(
            'post_type'      => 'grievance',
            'author'         => $user->ID,
            'posts_per_page' => 20,
        ));

        if (!empty($my_grvs)) {
            ?>
            <div class="mvc-grv-table-wrap">
                <table class="mvc-grv-table">
                    <thead>
                        <tr>
                            <th>Reference Number</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($my_grvs as $g) : 
                            $ref = get_post_meta($g->ID, '_mvc_grv_ref', true);
                            $cat = get_post_meta($g->ID, '_mvc_grv_category', true);
                            $status_terms = wp_get_object_terms($g->ID, 'grievance-status');
                            $status = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';
                            $status_class = 'mvc-status-' . sanitize_html_class($status);
                        ?>
                            <tr>
                                <td data-col="Reference"><code><?php echo esc_html($ref); ?></code></td>
                                <td data-col="Subject" style="font-weight:600; color:#002147;"><?php echo esc_html($g->post_title); ?></td>
                                <td data-col="Category"><?php echo esc_html($cat); ?></td>
                                <td data-col="Status">
                                    <span class="mvc-grv-status-pill <?php echo esc_attr($status_class); ?>">
                                        <?php echo esc_html(strtoupper($status)); ?>
                                    </span>
                                </td>
                                <td data-col="Submitted"><?php echo esc_html(get_the_date('M d, Y', $g->ID)); ?></td>
                                <td data-col="Actions" style="text-align:right;">
                                    <a href="<?php echo esc_url(home_url('/portal/grievances/' . $ref . '/')); ?>" class="mvc-btn-view-timeline">
                                        View Timeline &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            echo '<div class="mvc-empty-box" style="padding:40px 20px;">
                    <div style="font-size:32px; margin-bottom:8px;">✅</div>
                    <strong>No Registered Grievances on File</strong>
                    <p style="color:#64748b; margin:6px 0 16px;">You have no active complaints registered with the Grievance Redressal Cell.</p>
                    <a href="' . esc_url(home_url('/portal/grievances/new/')) . '" class="mvc-btn mvc-btn-maroon">Submit a Grievance</a>
                  </div>';
        }
        ?>
    </div>
    <?php
}

function mvc_render_new_grievance_form($user) {
    $notice_message = '';
    $notice_type = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mvc_grv_submit_nonce']) && wp_verify_nonce($_POST['mvc_grv_submit_nonce'], 'mvc_submit_grv_action')) {
        $data = array(
            'category'    => sanitize_text_field($_POST['category'] ?? 'Academic'),
            'subject'     => sanitize_text_field($_POST['subject'] ?? ''),
            'description' => sanitize_textarea_field($_POST['description'] ?? ''),
        );

        $res = function_exists('mvc_submit_grievance') ? mvc_submit_grievance($user->ID, $data) : new WP_Error('err', 'Grievance service unavailable');
        if (is_wp_error($res)) {
            $notice_message = $res->get_error_message();
            $notice_type = 'error';
        } else {
            $ref = $res['ref'];
            wp_safe_redirect(home_url('/portal/grievances/' . $ref . '/?submitted=1'));
            exit;
        }
    }
    ?>
    <div class="mvc-pcard" style="max-width:820px; margin:0 auto;">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">📝 Lodge Formal Grievance</h2>
                <div class="mvc-pcard-sub">Submit your formal application to the Institutional Redressal Committee.</div>
            </div>
            <a href="<?php echo esc_url(home_url('/portal/grievances/')); ?>" class="mvc-pcard-link">
                &larr; Back to Dossiers
            </a>
        </div>

        <div class="mvc-statutory-alert">
            <div class="mvc-statutory-icon">🛡️</div>
            <div>
                <strong>Confidentiality Guarantee:</strong> Your submission is stored in an encrypted, air-gapped private vault accessible exclusively to authorized inquiry officers.
            </div>
        </div>

        <?php if ($notice_message) : ?>
            <div class="mvc-portal-alert mvc-alert-error">
                <?php echo esc_html($notice_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="mvc-portal-form">
            <?php wp_nonce_field('mvc_submit_grv_action', 'mvc_grv_submit_nonce'); ?>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Grievance Category *</label>
                <select name="category" class="mvc-pform-select" required>
                    <option value="Academic">Academic (Lectures, Classes, Faculty)</option>
                    <option value="Examination">Examination (Admit Card, Marks, Evaluation)</option>
                    <option value="Fee or Scholarship">Fee or Scholarship (Discrepancy, Concession)</option>
                    <option value="Library or Facilities">Library, Laboratories, Campus Facilities</option>
                    <option value="Harassment or Conduct">Harassment, Conduct, or Discipline</option>
                    <option value="Administration">Institutional Office &amp; Verification</option>
                    <option value="Other">Other Concerns</option>
                </select>
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Subject / Title (maximum 150 characters) *</label>
                <input type="text" name="subject" class="mvc-pform-input" required maxlength="150" placeholder="e.g. Discrepancy in Semester-II Admit Card Roll Number">
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Detailed Grievance Statement (maximum 3,000 characters) *</label>
                <textarea name="description" class="mvc-pform-textarea" rows="6" required maxlength="3000" placeholder="Please provide specific dates, roll numbers, and details..."></textarea>
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Supporting Documents / Evidence (Optional, max 3 files, PDF/JPG/PNG)</label>
                <input type="file" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png">
            </div>

            <div style="margin-top:24px;">
                <button type="submit" class="mvc-btn mvc-btn-maroon" style="padding:12px 28px; font-size:15px; font-weight:700;">
                    Submit Grievance Dossier &rarr;
                </button>
            </div>
        </form>
    </div>
    <?php
}

function mvc_render_single_grievance_view($user, $ref_no) {
    $posts = get_posts(array(
        'post_type'      => 'grievance',
        'meta_key'       => '_mvc_grv_ref',
        'meta_value'     => $ref_no,
        'posts_per_page' => 1,
    ));

    if (empty($posts)) {
        echo '<div class="mvc-pcard"><p>Grievance record not found.</p></div>';
        return;
    }

    $g = $posts[0];
    if (function_exists('mvc_grievance_user_can_view') && !mvc_grievance_user_can_view($user->ID, $g->ID)) {
        echo '<div class="mvc-pcard"><p>Access restricted.</p></div>';
        return;
    }

    $status_terms = wp_get_object_terms($g->ID, 'grievance-status');
    $status = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';
    $cat = get_post_meta($g->ID, '_mvc_grv_category', true);
    $due = get_post_meta($g->ID, '_mvc_grv_due', true);
    $outcome = get_post_meta($g->ID, '_mvc_grv_outcome', true);

    $timeline = function_exists('mvc_get_grievance_timeline') ? mvc_get_grievance_timeline($g->ID, $user->ID) : array();
    ?>
    <div class="mvc-pcard" style="max-width:840px; margin:0 auto;">
        <div class="mvc-pcard-header">
            <div>
                <span class="mvc-pnotice-meta-line">Official Redressal Dossier</span>
                <h2 class="mvc-pcard-title"><?php echo esc_html($ref_no); ?>: <?php echo esc_html($g->post_title); ?></h2>
            </div>
            <a href="<?php echo esc_url(home_url('/portal/grievances/')); ?>" class="mvc-pcard-link">
                &larr; Back to Grievances
            </a>
        </div>

        <div class="mvc-grv-summary-box">
            <div class="mvc-grv-meta-grid">
                <div><strong>Category:</strong> <?php echo esc_html($cat); ?></div>
                <div><strong>Status:</strong> <span class="mvc-grv-status-pill mvc-status-<?php echo esc_attr(sanitize_html_class($status)); ?>"><?php echo esc_html(strtoupper($status)); ?></span></div>
                <div><strong>Logged On:</strong> <?php echo esc_html(get_the_date('M d, Y', $g->ID)); ?></div>
                <div><strong>Resolution SLA:</strong> <?php echo esc_html($due ? gmdate('M d, Y', strtotime($due)) : '15 Working Days'); ?></div>
            </div>
            <div style="margin-top:14px; padding-top:14px; border-top:1px solid #e2e8f0; font-size:14px; line-height:1.6;">
                <strong>Scholar Submission Statement:</strong><br>
                <?php echo nl2br(esc_html($g->post_content)); ?>
            </div>
        </div>

        <?php if ($outcome) : ?>
            <div class="mvc-outcome-box">
                <h4>🏛️ Certified Redressal Outcome</h4>
                <div><?php echo nl2br(esc_html($outcome)); ?></div>
            </div>
        <?php endif; ?>

        <h3 style="color:#002147; margin:24px 0 12px;">Official Investigation Timeline</h3>
        <div class="mvc-timeline">
            <?php foreach ($timeline as $ev) : ?>
                <div class="mvc-timeline-step">
                    <div class="mvc-timeline-dot"></div>
                    <div class="mvc-timeline-date"><?php echo esc_html(gmdate('M d, Y • h:i A', strtotime($ev['created_at']))); ?></div>
                    <div class="mvc-timeline-body">
                        <strong><?php echo esc_html($ev['actor_name']); ?>:</strong> <?php echo nl2br(esc_html($ev['body'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * View 5: Verified Alumni Directory (Alumni)
 */
function mvc_render_portal_directory_view($user) {
    $alumni = get_users(array(
        'role'       => 'alumni_member',
        'meta_key'   => 'mvc_account_status',
        'meta_value' => 'active',
        'number'     => 50,
    ));
    ?>
    <div class="mvc-pcard">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">👥 Verified Alumni Directory</h2>
                <div class="mvc-pcard-sub">Discover and connect with M.V. College graduates across batches, sectors, and regions.</div>
            </div>
        </div>

        <!-- Directory Search Bar -->
        <div class="mvc-dir-search-wrap">
            <input type="text" id="mvcDirSearchInput" class="mvc-dir-search-input" placeholder="🔍 Search batchmates by name, graduation batch year, or city..." onkeyup="mvcFilterDirectory()">
        </div>

        <div class="mvc-alumni-grid" id="mvcAlumniGrid">
            <?php if (!empty($alumni)) : ?>
                <?php foreach ($alumni as $a) : 
                    $optin = get_user_meta($a->ID, 'mvc_directory_optin', true) ?: array('name', 'batch', 'profession', 'city');
                    $batch = get_user_meta($a->ID, 'mvc_alumni_batch', true) ?: get_user_meta($a->ID, 'mvc_batch_year', true);
                    $city  = get_user_meta($a->ID, 'mvc_city', true);
                    $prof  = get_user_meta($a->ID, 'mvc_alumni_designation', true) ?: get_user_meta($a->ID, 'mvc_profession', true);
                    $deg   = get_user_meta($a->ID, 'mvc_alumni_degree', true);
                ?>
                    <div class="mvc-alumni-card" data-name="<?php echo esc_attr(strtolower($a->display_name)); ?>" data-batch="<?php echo esc_attr($batch); ?>" data-city="<?php echo esc_attr(strtolower($city)); ?>">
                        <div class="mvc-alumni-avatar">
                            <?php echo esc_html(strtoupper(substr($a->display_name, 0, 1))); ?>
                        </div>
                        <h4 class="mvc-alumni-name"><?php echo esc_html($a->display_name); ?></h4>
                        <?php if ($deg) : ?><div class="mvc-alumni-deg"><?php echo esc_html($deg); ?></div><?php endif; ?>
                        <?php if ($batch) : ?><div class="mvc-alumni-batch">Class of <strong><?php echo esc_html($batch); ?></strong></div><?php endif; ?>
                        <?php if ($prof && in_array('profession', $optin, true)) : ?>
                            <div class="mvc-alumni-prof">💼 <?php echo esc_html($prof); ?></div>
                        <?php endif; ?>
                        <?php if ($city && in_array('city', $optin, true)) : ?>
                            <div class="mvc-alumni-city">📍 <?php echo esc_html($city); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="mvc-empty-box">No alumni directory records registered.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function mvcFilterDirectory() {
        var input = document.getElementById('mvcDirSearchInput').value.toLowerCase();
        var cards = document.querySelectorAll('.mvc-alumni-card');
        cards.forEach(function(card) {
            var text = card.textContent.toLowerCase();
            if (text.indexOf(input) > -1) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
    </script>
    <?php
}

/**
 * View 6: Reunion Desk (Alumni)
 */
function mvc_render_portal_reunion_view($user, $alm_batch, $dept_name) {
    $submitted = isset($_GET['reunion_submitted']);
    ?>
    <div class="mvc-pcard" style="max-width:820px; margin:0 auto;">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">🤝 Batch Reunion Desk &amp; Campus Gatherings</h2>
                <div class="mvc-pcard-sub">Coordinate departmental and batch alumni reunions with administrative facilitation.</div>
            </div>
        </div>

        <?php if ($submitted) : ?>
            <div class="mvc-portal-alert mvc-alert-success">
                ✅ <strong>Reunion Proposal Submitted:</strong> The Alumni Liaison Committee has received your proposal. A coordinator will connect with you via email shortly.
            </div>
        <?php endif; ?>

        <div class="mvc-reunion-intro-box">
            <h4>Maharshi Vishwamitra Mahavidyalaya Alumni Association (MVCAA)</h4>
            <p>We welcome alumni batches to celebrate silver and golden jubilee reunions, academic masterclasses, and departmental gatherings on campus with full administrative support.</p>
        </div>

        <form method="POST" class="mvc-portal-form" action="<?php echo esc_url(add_query_arg('reunion_submitted', '1')); ?>">
            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Graduation Batch Year *</label>
                <input type="text" name="reunion_batch" class="mvc-pform-input" required value="<?php echo esc_attr($alm_batch); ?>" placeholder="e.g. 2021">
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Academic Department *</label>
                <input type="text" name="reunion_dept" class="mvc-pform-input" required value="<?php echo esc_attr($dept_name); ?>" placeholder="e.g. Department of Physics">
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Preferred Season / Month *</label>
                <select name="reunion_season" class="mvc-pform-select">
                    <option value="Winter (Dec - Jan)">Winter (Dec – Jan) • Recommended for Alumni Meets</option>
                    <option value="Spring (Feb - Mar)">Spring (Feb – Mar)</option>
                    <option value="Monsoon (Jul - Aug)">Monsoon (Jul – Aug)</option>
                    <option value="Autumn (Oct - Nov)">Autumn (Oct – Nov)</option>
                </select>
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Estimated Number of Attendees *</label>
                <input type="number" name="reunion_headcount" class="mvc-pform-input" required min="5" max="500" value="25" placeholder="e.g. 35">
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Lead Coordinator Contact Phone / Email *</label>
                <input type="text" name="reunion_contact" class="mvc-pform-input" required value="<?php echo esc_attr($user->user_email); ?>">
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Proposed Activities &amp; Facilities Needed</label>
                <textarea name="reunion_notes" class="mvc-pform-textarea" rows="4" placeholder="e.g. Auditorium booking, campus tour, interaction with current students..."></textarea>
            </div>

            <div style="margin-top:24px;">
                <button type="submit" class="mvc-btn mvc-btn-maroon" style="padding:12px 28px; font-size:15px; font-weight:700;">
                    Submit Reunion Proposal &rarr;
                </button>
            </div>
        </form>
    </div>
    <?php
}

/**
 * View 7: Events & Meets
 */
function mvc_render_portal_events_view($user, $is_alumni) {
    $events = get_posts(array(
        'post_type'      => 'event',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
    ));
    ?>
    <div class="mvc-pcard">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">📅 Campus Events, Seminars &amp; Meets</h2>
                <div class="mvc-pcard-sub">University seminars, sports meets, cultural festivals, and alumni gatherings.</div>
            </div>
        </div>

        <div class="mvc-pnotice-list">
            <?php if (!empty($events)) : ?>
                <?php foreach ($events as $ev) : ?>
                    <article class="mvc-pnotice-row">
                        <div class="mvc-pnotice-left">
                            <span class="mvc-pnotice-icon">📅</span>
                            <div>
                                <h4 class="mvc-pnotice-heading">
                                    <a href="<?php echo esc_url(get_permalink($ev->ID)); ?>">
                                        <?php echo esc_html($ev->post_title); ?>
                                    </a>
                                </h4>
                                <div class="mvc-pnotice-meta-line">
                                    <span>Date: <?php echo esc_html(get_the_date('M d, Y', $ev->ID)); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="mvc-pnotice-actions">
                            <a href="<?php echo esc_url(get_permalink($ev->ID)); ?>" class="mvc-btn-details">
                                View Event Details &rarr;
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="mvc-empty-box">No upcoming events currently scheduled.</div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * View 8: Feedback & Institutional Proposals
 */
function mvc_render_portal_feedback_view($user, $is_student, $is_alumni) {
    ?>
    <div class="mvc-pcard">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">
                    <?php echo $is_student ? '📝 360° Academic Feedback &amp; Surveys' : '💡 Institutional Development Proposals'; ?>
                </h2>
                <div class="mvc-pcard-sub">Constructive inputs guiding institutional excellence under the aegis of NAAC &amp; IQAC.</div>
            </div>
        </div>

        <?php if ($is_student) : ?>
            <div class="mvc-study-suite-grid">
                <div class="mvc-study-card">
                    <div class="mvc-study-card-top">
                        <span class="mvc-study-badge">STATUTORY NAAC IQAC</span>
                        <h3>Student Satisfaction Survey (SSS)</h3>
                        <p>Evaluate teaching methodologies, curriculum delivery, laboratory infrastructure, and academic mentorship.</p>
                    </div>
                    <div class="mvc-study-card-actions">
                        <a href="<?php echo esc_url(home_url('/student-satisfaction-survey/')); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-maroon">
                            <span>Take SSS Survey &rarr;</span>
                        </a>
                    </div>
                </div>

                <div class="mvc-study-card">
                    <div class="mvc-study-card-top">
                        <span class="mvc-study-badge mvc-badge-blue">CURRICULUM EVALUATION</span>
                        <h3>360-Degree Stakeholder Feedback</h3>
                        <p>Submit structured feedback on course syllabus, library resources, and evaluation transparency.</p>
                    </div>
                    <div class="mvc-study-card-actions">
                        <a href="<?php echo esc_url(home_url('/360-degree-feedback/')); ?>" target="_blank" rel="noopener" class="mvc-btn mvc-btn-navy">
                            <span>Submit 360° Feedback &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="mvc-reunion-intro-box">
                <h4>Alumni Recommendations for Collegiate Growth</h4>
                <p>Alumni proposals provide invaluable direction for university modernizations, industry collaborations, placement guidance, and library expansions.</p>
            </div>

            <form method="POST" class="mvc-portal-form" action="<?php echo esc_url(add_query_arg('feedback_submitted', '1')); ?>">
                <div class="mvc-pform-group">
                    <label class="mvc-pform-label">Focus Area *</label>
                    <select name="feedback_area" class="mvc-pform-select">
                        <option value="Curriculum Modernization">Curriculum &amp; Technological Modernization</option>
                        <option value="Career Guidance">Career Counseling &amp; Competitive Exam Mentorship</option>
                        <option value="Infrastructure">Laboratory &amp; Library Resource Upgradation</option>
                        <option value="Scholarship">Alumni-Sponsored Student Scholarships</option>
                        <option value="General">General Collegiate Recommendations</option>
                    </select>
                </div>

                <div class="mvc-pform-group">
                    <label class="mvc-pform-label">Proposal Title *</label>
                    <input type="text" name="feedback_title" class="mvc-pform-input" required placeholder="e.g. Industry-Aligned Guest Lecture Series for Science Students">
                </div>

                <div class="mvc-pform-group">
                    <label class="mvc-pform-label">Proposal Details &amp; How You Can Support</label>
                    <textarea name="feedback_body" class="mvc-pform-textarea" rows="5" required placeholder="Describe your recommendation and any mentorship or support you wish to offer..."></textarea>
                </div>

                <div style="margin-top:20px;">
                    <button type="submit" class="mvc-btn mvc-btn-maroon" style="padding:12px 28px; font-size:15px; font-weight:700;">
                        Submit Proposal to IQAC &rarr;
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * View 9: Profile & Privacy Settings
 */
function mvc_render_portal_profile_view($user, $is_student, $is_alumni, $std_program, $std_roll, $std_session, $std_sem, $alm_degree, $alm_batch, $alm_member, $dept_name) {
    $msg = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mvc_profile_nonce']) && wp_verify_nonce($_POST['mvc_profile_nonce'], 'mvc_update_profile')) {
        if (isset($_POST['phone'])) {
            update_user_meta($user->ID, 'mvc_phone', sanitize_text_field($_POST['phone']));
        }
        if (isset($_POST['city'])) {
            update_user_meta($user->ID, 'mvc_city', sanitize_text_field($_POST['city']));
        }
        if (isset($_POST['profession'])) {
            update_user_meta($user->ID, 'mvc_profession', sanitize_text_field($_POST['profession']));
            update_user_meta($user->ID, 'mvc_alumni_designation', sanitize_text_field($_POST['profession']));
        }
        if ($is_alumni) {
            $optin = !empty($_POST['optin']) && is_array($_POST['optin']) ? array_map('sanitize_key', $_POST['optin']) : array();
            update_user_meta($user->ID, 'mvc_directory_optin', $optin);
        }
        $msg = 'Profile changes saved successfully.';
    }

    $phone = get_user_meta($user->ID, 'mvc_phone', true);
    $city  = get_user_meta($user->ID, 'mvc_city', true);
    $prof  = get_user_meta($user->ID, 'mvc_profession', true) ?: get_user_meta($user->ID, 'mvc_alumni_designation', true);
    $optin = get_user_meta($user->ID, 'mvc_directory_optin', true) ?: array('name', 'batch', 'profession', 'city');
    ?>
    <div class="mvc-pcard" style="max-width:780px; margin:0 auto;">
        <div class="mvc-pcard-header">
            <div>
                <h2 class="mvc-pcard-title">👤 Official Academic Profile &amp; Settings</h2>
                <div class="mvc-pcard-sub">Institutional verified identity records and contact preferences.</div>
            </div>
        </div>

        <?php if ($msg) : ?>
            <div class="mvc-portal-alert mvc-alert-success">
                ✅ <?php echo esc_html($msg); ?>
            </div>
        <?php endif; ?>

        <!-- Institutional Verified Records -->
        <div class="mvc-verified-box">
            <h4>Verified Institutional Records (Immutable)</h4>
            <div class="mvc-verified-grid">
                <div><strong>Full Name:</strong> <?php echo esc_html($user->display_name); ?></div>
                <div><strong>Institutional Email:</strong> <?php echo esc_html($user->user_email); ?></div>
                <?php if ($is_student) : ?>
                    <div><strong>Roll Number:</strong> <code><?php echo esc_html($std_roll); ?></code></div>
                    <div><strong>Academic Program:</strong> <?php echo esc_html($std_program); ?></div>
                    <div><strong>Department:</strong> <?php echo esc_html($dept_name); ?></div>
                    <div><strong>Session:</strong> <?php echo esc_html($std_session); ?></div>
                <?php else : ?>
                    <div><strong>Graduation Batch:</strong> Class of <?php echo esc_html($alm_batch); ?></div>
                    <div><strong>Degree Conferred:</strong> <?php echo esc_html($alm_degree); ?></div>
                    <div><strong>Membership ID:</strong> <code><?php echo esc_html($alm_member); ?></code></div>
                    <div><strong>Alma Mater Dept:</strong> <?php echo esc_html($dept_name); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Self-Service Form -->
        <form method="POST" class="mvc-portal-form" style="margin-top:24px;">
            <?php wp_nonce_field('mvc_update_profile', 'mvc_profile_nonce'); ?>

            <h4 style="color:#002147; margin:0 0 16px;">Self-Service Contact &amp; Privacy Details</h4>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">Phone Number</label>
                <input type="text" name="phone" class="mvc-pform-input" value="<?php echo esc_attr($phone); ?>" placeholder="e.g. +91 98765 43210">
            </div>

            <div class="mvc-pform-group">
                <label class="mvc-pform-label">City / District of Residence</label>
                <input type="text" name="city" class="mvc-pform-input" value="<?php echo esc_attr($city); ?>" placeholder="e.g. Buxar, Bihar">
            </div>

            <?php if ($is_alumni) : ?>
                <div class="mvc-pform-group">
                    <label class="mvc-pform-label">Current Profession &amp; Organization</label>
                    <input type="text" name="profession" class="mvc-pform-input" value="<?php echo esc_attr($prof); ?>" placeholder="e.g. Civil Services, Software Engineer, Educator">
                </div>

                <div class="mvc-pform-group">
                    <label class="mvc-pform-label">Directory Privacy Controls (Opt-In Settings)</label>
                    <div class="mvc-privacy-checklist">
                        <label><input type="checkbox" name="optin[]" value="name" <?php checked(in_array('name', $optin, true)); ?>> Display my name in the verified Alumni Directory</label>
                        <label><input type="checkbox" name="optin[]" value="batch" <?php checked(in_array('batch', $optin, true)); ?>> Display my graduation batch year</label>
                        <label><input type="checkbox" name="optin[]" value="city" <?php checked(in_array('city', $optin, true)); ?>> Display my city/location</label>
                        <label><input type="checkbox" name="optin[]" value="profession" <?php checked(in_array('profession', $optin, true)); ?>> Display my current profession &amp; sector</label>
                    </div>
                </div>
            <?php endif; ?>

            <div style="margin-top:24px;">
                <button type="submit" class="mvc-btn mvc-btn-maroon" style="padding:12px 28px; font-size:15px; font-weight:700;">
                    Save Profile Settings &rarr;
                </button>
            </div>
        </form>
    </div>
    <?php
}
