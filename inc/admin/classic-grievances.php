<?php
/**
 * MV College Grievances & Enquiries Classic Admin Subsystem
 *
 * Provides a stately, fully-featured administrative desk in wp-admin:
 * 1. Custom list table columns (Ref, Submitter, Form, Department, Status, SLA, Actions).
 * 2. Department & Status filter dropdowns with strict Department Head RBAC scoping.
 * 3. Comprehensive Institutional Case Dossier meta box on single case view.
 * 4. Interactive Action Desk: status updates, departmental re-routing, public/internal notes,
 *    and certified resolution outcomes.
 * 5. Automatic email dispatch to the raiser via the centralized Zoho SMTP fallback.
 *
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Register Custom Columns for Grievance CPT in wp-admin
 */
add_filter('manage_grievance_posts_columns', 'mvc_grievance_custom_columns');
function mvc_grievance_custom_columns($columns) {
    $new_cols = array();
    $new_cols['cb']          = $columns['cb'];
    $new_cols['grv_ref']     = __('Case Ref #', 'mvc-college');
    $new_cols['title']       = __('Subject / Title', 'mvc-college');
    $new_cols['submitter']   = __('Submitter / Raiser', 'mvc-college');
    $new_cols['department']  = __('Department', 'mvc-college');
    $new_cols['grv_status']  = __('Status', 'mvc-college');
    $new_cols['form_source'] = __('Source Portal', 'mvc-college');
    $new_cols['sla_due']     = __('SLA & Timeline', 'mvc-college');
    $new_cols['grv_action']  = __('Actions', 'mvc-college');
    return $new_cols;
}

/**
 * 2. Render Custom Columns Data
 */
add_action('manage_grievance_posts_custom_column', 'mvc_grievance_custom_columns_data', 10, 2);
function mvc_grievance_custom_columns_data($column, $post_id) {
    switch ($column) {
        case 'grv_ref':
            $ref = get_post_meta($post_id, '_mvc_grv_ref', true) ?: ('MVC-CASE-' . $post_id);
            echo '<strong style="color:#002147; font-family:monospace; font-size:13px; background:#F1F5F9; padding:4px 8px; border-radius:4px; border:1px solid #CBD5E1; display:inline-block;">' . esc_html($ref) . '</strong>';
            break;

        case 'submitter':
            $name  = get_post_meta($post_id, '_mvc_grv_submitter_name', true);
            $email = get_post_meta($post_id, '_mvc_grv_submitter_email', true);
            $phone = get_post_meta($post_id, '_mvc_grv_submitter_phone', true);

            if (empty($name) && empty($email)) {
                $post = get_post($post_id);
                $author = $post ? get_userdata($post->post_author) : null;
                $name = $author ? $author->display_name : 'Citizen / Scholar';
                $email = $author ? $author->user_email : '';
            }

            echo '<strong style="color:#1E293B; font-size:13px;">' . esc_html($name ?: 'Anonymous / Citizen') . '</strong>';
            if (!empty($email)) {
                echo '<br><a href="mailto:' . esc_attr($email) . '" style="color:#2563EB; font-size:12px; text-decoration:none;">✉ ' . esc_html($email) . '</a>';
            }
            if (!empty($phone)) {
                echo '<br><span style="color:#64748B; font-size:11.5px;">📞 ' . esc_html($phone) . '</span>';
            }
            break;

        case 'department':
            $terms = wp_get_object_terms($post_id, 'dept_link');
            if (!empty($terms) && !is_wp_error($terms)) {
                $dept_names = wp_list_pluck($terms, 'name');
                echo '<span style="display:inline-block; background:#EFF6FF; color:#1D4ED8; font-weight:700; font-size:11.5px; padding:3px 9px; border-radius:999px; border:1px solid #BFDBFE;">🏛 ' . esc_html(implode(', ', $dept_names)) . '</span>';
            } else {
                $cat = get_post_meta($post_id, '_mvc_grv_category', true);
                if (!empty($cat)) {
                    echo '<span style="color:#64748B; font-size:12px;">' . esc_html($cat) . '</span>';
                } else {
                    echo '<span style="color:#94A3B8; font-style:italic; font-size:12px;">General / Central</span>';
                }
            }
            break;

        case 'grv_status':
            $status_terms = wp_get_object_terms($post_id, 'grievance-status');
            $status = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';

            $colors = array(
                'logged'       => array('bg' => '#FEF3C7', 'color' => '#92400E', 'border' => '#FDE68A', 'label' => 'Logged'),
                'in-review'    => array('bg' => '#E0F2FE', 'color' => '#0369A1', 'border' => '#BAE6FD', 'label' => 'Under Review'),
                'under-review' => array('bg' => '#E0F2FE', 'color' => '#0369A1', 'border' => '#BAE6FD', 'label' => 'Under Review'),
                'action_taken' => array('bg' => '#F3E8FF', 'color' => '#6B21A8', 'border' => '#E9D5FF', 'label' => 'In Progress'),
                'action-taken' => array('bg' => '#F3E8FF', 'color' => '#6B21A8', 'border' => '#E9D5FF', 'label' => 'In Progress'),
                'resolved'     => array('bg' => '#DCFCE7', 'color' => '#166534', 'border' => '#86EFAC', 'label' => 'Resolved'),
                'closed'       => array('bg' => '#F1F5F9', 'color' => '#475569', 'border' => '#CBD5E1', 'label' => 'Closed'),
            );

            $c = $colors[$status] ?? array('bg' => '#F1F5F9', 'color' => '#475569', 'border' => '#CBD5E1', 'label' => strtoupper($status));
            echo '<span style="display:inline-block; background:' . esc_attr($c['bg']) . '; color:' . esc_attr($c['color']) . '; border:1px solid ' . esc_attr($c['border']) . '; font-weight:800; font-size:11px; padding:3px 10px; border-radius:999px; text-transform:uppercase; letter-spacing:0.5px;">' . esc_html($c['label']) . '</span>';
            break;

        case 'form_source':
            $form_title = get_post_meta($post_id, '_mvc_grv_form_title', true);
            if (!empty($form_title)) {
                echo '<span style="color:#475569; font-size:12px; font-weight:600;">' . esc_html($form_title) . '</span>';
            } else {
                echo '<span style="color:#94A3B8; font-size:12px;">Portal Direct</span>';
            }
            break;

        case 'sla_due':
            $due = get_post_meta($post_id, '_mvc_grv_due', true);
            $status_terms = wp_get_object_terms($post_id, 'grievance-status');
            $status = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';
            $is_resolved = in_array($status, array('resolved', 'closed'), true);

            if ($due) {
                $due_ts = strtotime($due);
                $now = time();
                if (!$is_resolved && $due_ts < $now) {
                    echo '<span style="color:#DC2626; font-weight:800; font-size:11.5px; background:#FEE2E2; padding:2px 7px; border-radius:4px; border:1px solid #FCA5A5;">⚠️ Overdue</span>';
                } else {
                    $days_left = max(0, ceil(($due_ts - $now) / DAY_IN_SECONDS));
                    echo '<span style="color:#166534; font-size:12px; font-weight:600;">' . esc_html($days_left) . ' days remaining</span>';
                }
                echo '<br><span style="color:#64748B; font-size:11px;">Target: ' . esc_html(gmdate('d M Y', $due_ts)) . '</span>';
            } else {
                echo '<span style="color:#94A3B8; font-size:12px;">15 Working Days</span>';
            }
            break;

        case 'grv_action':
            $edit_url = get_edit_post_link($post_id);
            echo '<a href="' . esc_url($edit_url) . '" class="button button-small button-primary" style="font-weight:700;">Manage Dossier &rarr;</a>';
            break;
    }
}

/**
 * 3. Admin Filters for Status and Department in Grievance List Table
 */
add_action('restrict_manage_posts', 'mvc_grievance_filter_dropdowns');
function mvc_grievance_filter_dropdowns() {
    global $typenow;
    if ($typenow !== 'grievance') {
        return;
    }

    $current_user = wp_get_current_user();
    $is_dept_scoped = function_exists('mvc_is_user_dept_scoped') && mvc_is_user_dept_scoped($current_user);

    // Status Filter Dropdown
    $selected_status = isset($_GET['grv_status_filter']) ? sanitize_key($_GET['grv_status_filter']) : '';
    $statuses = array(
        'logged'       => 'Logged',
        'in-review'    => 'Under Review',
        'action-taken' => 'Action In Progress',
        'resolved'     => 'Resolved',
        'closed'       => 'Closed',
    );

    echo '<select name="grv_status_filter" id="grv_status_filter">';
    echo '<option value="">' . __('All Case Statuses', 'mvc-college') . '</option>';
    foreach ($statuses as $val => $lbl) {
        printf('<option value="%s"%s>%s</option>', esc_attr($val), selected($selected_status, $val, false), esc_html($lbl));
    }
    echo '</select>';

    // Department Filter Dropdown (Super Admin / Content Manager see all; HOD sees assigned only)
    if (!$is_dept_scoped) {
        $selected_dept = isset($_GET['dept_link_filter']) ? (int) $_GET['dept_link_filter'] : 0;
        $departments = get_terms(array(
            'taxonomy'   => 'dept_link',
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ));

        if (!empty($departments) && !is_wp_error($departments)) {
            echo '<select name="dept_link_filter" id="dept_link_filter">';
            echo '<option value="">' . __('All Departments', 'mvc-college') . '</option>';
            foreach ($departments as $dept) {
                printf('<option value="%d"%s>%s</option>', (int) $dept->term_id, selected($selected_dept, $dept->term_id, false), esc_html($dept->name));
            }
            echo '</select>';
        }
    }
}

/**
 * 4. Apply Filters to Admin Query (pre_get_posts)
 */
add_action('pre_get_posts', 'mvc_grievance_apply_admin_filters');
function mvc_grievance_apply_admin_filters($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'grievance') {
        return;
    }

    $tax_query = (array) $query->get('tax_query');

    // Filter by Status
    if (!empty($_GET['grv_status_filter'])) {
        $status_slug = sanitize_key($_GET['grv_status_filter']);
        $tax_query[] = array(
            'taxonomy' => 'grievance-status',
            'field'    => 'slug',
            'terms'    => array($status_slug),
        );
    }

    // Filter by Department (if selected by Admin)
    if (!empty($_GET['dept_link_filter'])) {
        $dept_id = (int) $_GET['dept_link_filter'];
        $tax_query[] = array(
            'taxonomy' => 'dept_link',
            'field'    => 'term_id',
            'terms'    => array($dept_id),
        );
    }

    if (!empty($tax_query)) {
        $query->set('tax_query', $tax_query);
    }
}

/**
 * 5. Register Comprehensive Case Dossier & Action Meta Box on Single Grievance View
 */
add_action('add_meta_boxes', 'mvc_register_grievance_meta_boxes');
function mvc_register_grievance_meta_boxes() {
    add_meta_box(
        'mvc_grievance_dossier_box',
        __('🏛️ Institutional Case Dossier & Authority Action Desk', 'mvc-college'),
        'mvc_render_grievance_dossier_box',
        'grievance',
        'normal',
        'high'
    );
}

/**
 * 6. Render Case Dossier & Action Desk Meta Box
 */
function mvc_render_grievance_dossier_box($post) {
    wp_nonce_field('mvc_save_grievance_action_nonce', 'mvc_grievance_nonce');

    $post_id     = $post->ID;
    $ref_no      = get_post_meta($post_id, '_mvc_grv_ref', true) ?: ('MVC-CASE-' . $post_id);
    $sub_name    = get_post_meta($post_id, '_mvc_grv_submitter_name', true);
    $sub_email   = get_post_meta($post_id, '_mvc_grv_submitter_email', true);
    $sub_phone   = get_post_meta($post_id, '_mvc_grv_submitter_phone', true);
    $form_title  = get_post_meta($post_id, '_mvc_grv_form_title', true) ?: 'Collegiate Portal';
    $form_id     = (int) get_post_meta($post_id, '_mvc_grv_form_id', true);
    $due_date    = get_post_meta($post_id, '_mvc_grv_due', true);
    $outcome     = get_post_meta($post_id, '_mvc_grv_outcome', true);
    $fields_json = get_post_meta($post_id, '_mvc_grv_fields_data', true);
    $fields_data = !empty($fields_json) ? json_decode($fields_json, true) : array();

    // Current Status
    $status_terms   = wp_get_object_terms($post_id, 'grievance-status');
    $current_status = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';

    // Current Department
    $dept_terms  = wp_get_object_terms($post_id, 'dept_link');
    $current_dept_id = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? (int) $dept_terms[0]->term_id : 0;

    // Timeline Events
    $timeline = function_exists('mvc_get_grievance_timeline') ? mvc_get_grievance_timeline($post_id, get_current_user_id()) : array();

    $current_user = wp_get_current_user();
    $is_dept_scoped = function_exists('mvc_is_user_dept_scoped') && mvc_is_user_dept_scoped($current_user);

    $tracking_url = home_url('/track/?ref=' . rawurlencode($ref_no) . '&email=' . rawurlencode($sub_email));
    ?>
    <style>
        .mvc-dossier-wrap { display:grid; grid-template-columns: 1.15fr 0.85fr; gap:24px; font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        @media (max-width: 900px) { .mvc-dossier-wrap { grid-template-columns: 1fr; } }
        .mvc-dossier-card { background:#ffffff; border:1px solid #E2E8F0; border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .mvc-dossier-header { display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #002147; padding-bottom:12px; margin-bottom:16px; }
        .mvc-dossier-title { margin:0; font-size:16px; font-weight:800; color:#002147; display:flex; align-items:center; gap:8px; }
        .mvc-sub-table { width:100%; border-collapse:collapse; margin-top:10px; font-size:13px; }
        .mvc-sub-table th, .mvc-sub-table td { padding:8px 12px; border:1px solid #E2E8F0; text-align:left; vertical-align:top; }
        .mvc-sub-table th { background:#F8FAFC; color:#002147; font-weight:700; width:35%; }
        .mvc-timeline-box { max-height:360px; overflow-y:auto; padding-right:8px; margin:14px 0; border:1px solid #E2E8F0; border-radius:8px; padding:14px; background:#FAFBFD; }
        .mvc-tl-item { position:relative; padding-left:24px; margin-bottom:14px; border-left:2px solid #CBD5E1; }
        .mvc-tl-dot { position:absolute; left:-6px; top:2px; width:10px; height:10px; border-radius:50%; background:#002147; }
        .mvc-tl-dot.internal { background:#DC2626; }
        .mvc-tl-dot.public { background:#16A34A; }
        .mvc-tl-meta { font-size:11.5px; color:#64748B; font-weight:600; margin-bottom:3px; }
        .mvc-tl-body { font-size:12.5px; color:#1E293B; background:#ffffff; border:1px solid #E2E8F0; border-radius:6px; padding:8px 12px; }
        .mvc-action-panel { background:#FAF9F6; border:1px solid #D4AF37; border-radius:8px; padding:16px; margin-top:16px; }
    </style>

    <div class="mvc-dossier-wrap">
        <!-- LEFT COLUMN: Case Dossier & Submitted Representation -->
        <div class="mvc-dossier-card">
            <div class="mvc-dossier-header">
                <h3 class="mvc-dossier-title">📋 Case Dossier Representation</h3>
                <span style="font-family:monospace; font-size:14px; font-weight:800; color:#002147; background:#EFF6FF; padding:4px 10px; border-radius:4px; border:1px solid #BFDBFE;">
                    <?php echo esc_html($ref_no); ?>
                </span>
            </div>

            <!-- Submitter Information Header -->
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:8px; padding:14px; margin-bottom:16px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:13px;">
                    <div><strong>Raiser Name:</strong> <?php echo esc_html($sub_name ?: 'Anonymous / Citizen'); ?></div>
                    <div><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($sub_email); ?>" style="color:#2563EB; font-weight:600;"><?php echo esc_html($sub_email ?: 'N/A'); ?></a></div>
                    <div><strong>Contact Phone:</strong> <?php echo esc_html($sub_phone ?: 'Not provided'); ?></div>
                    <div><strong>Submission Portal:</strong> <?php echo esc_html($form_title); ?> (ID: <?php echo esc_html($form_id); ?>)</div>
                    <div><strong>Date Logged:</strong> <?php echo esc_html(get_the_date('d M Y, h:i A', $post_id)); ?></div>
                    <div><strong>SLA Target:</strong> <?php echo esc_html($due_date ? gmdate('d M Y', strtotime($due_date)) : '15 Working Days'); ?></div>
                </div>
                <div style="margin-top:12px; padding-top:10px; border-top:1px dashed #CBD5E1; font-size:12px;">
                    <strong>Live Raiser Tracking URL:</strong>
                    <a href="<?php echo esc_url($tracking_url); ?>" target="_blank" style="color:#002147; font-weight:700; text-decoration:none; margin-left:6px;">
                        <?php echo esc_html($tracking_url); ?> &nearr;
                    </a>
                </div>
            </div>

            <!-- Subject & Summary -->
            <h4 style="margin:14px 0 6px 0; color:#002147; font-size:14px;">Representation Summary:</h4>
            <div style="background:#ffffff; border:1px solid #CBD5E1; border-radius:6px; padding:12px 14px; font-size:13.5px; line-height:1.6; color:#334155; margin-bottom:16px;">
                <strong>Subject:</strong> <?php echo esc_html($post->post_title); ?><br><br>
                <?php echo nl2br(esc_html($post->post_content)); ?>
            </div>

            <!-- Full Field Data Table -->
            <?php if (!empty($fields_data)) : ?>
                <h4 style="margin:14px 0 6px 0; color:#002147; font-size:14px;">Full Submitted Form Fields:</h4>
                <table class="mvc-sub-table">
                    <tbody>
                        <?php foreach ($fields_data as $f) : ?>
                            <?php
                            $label = $f['label'] ?? 'Field';
                            $val   = $f['value'] ?? '';
                            if (is_array($val)) {
                                $val = implode(', ', array_filter($val));
                            }
                            ?>
                            <tr>
                                <th><?php echo esc_html($label); ?></th>
                                <td><?php echo nl2br(esc_html($val)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- RIGHT COLUMN: Authority Action Desk & Investigation Timeline -->
        <div class="mvc-dossier-card">
            <div class="mvc-dossier-header">
                <h3 class="mvc-dossier-title">⚖️ Investigation &amp; Resolution Desk</h3>
                <span class="mvc-badge" style="font-weight:700; font-size:12px;">
                    Role: <?php echo esc_html(strtoupper(reset($current_user->roles))); ?>
                </span>
            </div>

            <!-- Timeline of Events -->
            <h4 style="margin:0 0 8px 0; color:#002147; font-size:13.5px;">Investigation Timeline &amp; Events:</h4>
            <div class="mvc-timeline-box">
                <?php if (empty($timeline)) : ?>
                    <p style="color:#64748B; font-size:12.5px; margin:0;">No timeline events recorded yet.</p>
                <?php else : ?>
                    <?php foreach ($timeline as $ev) : ?>
                        <?php
                        $is_internal = ($ev['visibility'] === 'internal');
                        $actor_name  = $ev['actor_name'] ?: 'Staff Member';
                        $date_str    = gmdate('d M Y, h:i A', strtotime($ev['created_at']));
                        ?>
                        <div class="mvc-tl-item">
                            <div class="mvc-tl-dot <?php echo $is_internal ? 'internal' : 'public'; ?>"></div>
                            <div class="mvc-tl-meta">
                                <strong><?php echo esc_html($actor_name); ?></strong> &bull; <?php echo esc_html($date_str); ?> &bull;
                                <?php if ($is_internal) : ?>
                                    <span style="color:#DC2626; font-weight:700;">[Internal Note]</span>
                                <?php else : ?>
                                    <span style="color:#16A34A; font-weight:700;">[Public &bull; Raiser Visible]</span>
                                <?php endif; ?>
                            </div>
                            <div class="mvc-tl-body">
                                <?php echo nl2br(esc_html($ev['body'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Current Resolution Outcome (if any) -->
            <?php if (!empty($outcome)) : ?>
                <div style="background:#F0FDF4; border:1px solid #86EFAC; border-radius:8px; padding:12px; margin-bottom:14px;">
                    <strong style="color:#166534; font-size:13px;">🏛️ Certified Redressal Outcome:</strong>
                    <p style="margin:4px 0 0 0; color:#14532D; font-size:12.5px; line-height:1.5;">
                        <?php echo nl2br(esc_html($outcome)); ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Action Controls Form -->
            <div class="mvc-action-panel">
                <h4 style="margin:0 0 12px 0; color:#002147; font-size:14px; border-bottom:1px solid #D4AF37; padding-bottom:6px;">
                    ✍️ Take Official Action / Update Case
                </h4>

                <!-- Status Selector -->
                <div style="margin-bottom:12px;">
                    <label for="mvc_status_select" style="font-weight:700; font-size:12.5px; display:block; margin-bottom:4px; color:#1E293B;">
                        Update Case Status:
                    </label>
                    <select name="mvc_action_status" id="mvc_status_select" style="width:100%; max-width:100%; font-size:13px; padding:6px;">
                        <option value="logged" <?php selected($current_status, 'logged'); ?>>LOGGED (Fresh Submission)</option>
                        <option value="in-review" <?php selected(in_array($current_status, array('in-review', 'under-review'), true), true); ?>>UNDER REVIEW (Assigned to Committee / Dept)</option>
                        <option value="action_taken" <?php selected(in_array($current_status, array('action_taken', 'action-taken'), true), true); ?>>IN PROGRESS (Action Initiated)</option>
                        <option value="resolved" <?php selected($current_status, 'resolved'); ?>>RESOLVED (Resolution Certified)</option>
                        <option value="closed" <?php selected($current_status, 'closed'); ?>>CLOSED (Dossier Archived)</option>
                    </select>
                </div>

                <!-- Department Assignment -->
                <div style="margin-bottom:12px;">
                    <label for="mvc_dept_select" style="font-weight:700; font-size:12.5px; display:block; margin-bottom:4px; color:#1E293B;">
                        Assigned Academic / Administrative Department:
                    </label>
                    <?php if ($is_dept_scoped) : ?>
                        <?php
                        $user_dept_term_id = (int) get_user_meta($current_user->ID, 'mvc_dept_term_id', true);
                        $dept_obj = $user_dept_term_id ? get_term($user_dept_term_id, 'dept_link') : null;
                        ?>
                        <input type="hidden" name="mvc_action_dept_id" value="<?php echo esc_attr($user_dept_term_id); ?>">
                        <input type="text" readonly disabled value="<?php echo esc_attr($dept_obj ? $dept_obj->name : 'Your Department'); ?>" style="width:100%; background:#F1F5F9; color:#475569; font-size:13px; padding:6px;">
                        <small style="color:#64748B; font-size:11px;">Department Head accounts are locked to their own department.</small>
                    <?php else : ?>
                        <select name="mvc_action_dept_id" id="mvc_dept_select" style="width:100%; max-width:100%; font-size:13px; padding:6px;">
                            <option value="0">-- General / Central Administration --</option>
                            <?php
                            $all_depts = get_terms(array('taxonomy' => 'dept_link', 'hide_empty' => false, 'orderby' => 'name'));
                            if (!empty($all_depts) && !is_wp_error($all_depts)) {
                                foreach ($all_depts as $d) {
                                    printf(
                                        '<option value="%d"%s>%s</option>',
                                        (int) $d->term_id,
                                        selected($current_dept_id, $d->term_id, false),
                                        esc_html($d->name)
                                    );
                                }
                            }
                            ?>
                        </select>
                    <?php endif; ?>
                </div>

                <!-- Note Composer -->
                <div style="margin-bottom:12px;">
                    <label for="mvc_action_note" style="font-weight:700; font-size:12.5px; display:block; margin-bottom:4px; color:#1E293B;">
                        Add Official Comment / Case Note:
                    </label>
                    <textarea name="mvc_action_note" id="mvc_action_note" rows="3" style="width:100%; font-size:12.5px; line-height:1.4; padding:8px;" placeholder="Enter official committee findings, inquiry queries, or updates..."></textarea>
                    
                    <div style="margin-top:6px; font-size:12px; display:flex; gap:16px;">
                        <label style="cursor:pointer; color:#166534; font-weight:700;">
                            <input type="radio" name="mvc_note_visibility" value="public" checked>
                            🟢 Public Update (Emailed to Raiser &amp; Visible Online)
                        </label>
                        <label style="cursor:pointer; color:#B91C1C; font-weight:700;">
                            <input type="radio" name="mvc_note_visibility" value="internal">
                            🔒 Internal Staff Note (Confidential)
                        </label>
                    </div>
                </div>

                <!-- Certified Outcome (if marking resolved) -->
                <div style="margin-bottom:14px;">
                    <label for="mvc_action_outcome" style="font-weight:700; font-size:12.5px; display:block; margin-bottom:4px; color:#1E293B;">
                        Certified Redressal Outcome (Mandatory if marking Resolved):
                    </label>
                    <textarea name="mvc_action_outcome" id="mvc_action_outcome" rows="3" style="width:100%; font-size:12.5px; line-height:1.4; padding:8px;" placeholder="Provide the certified redressal outcome statement approved by the Principal / Inquiry Committee..."><?php echo esc_textarea($outcome); ?></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="button button-primary button-large" style="width:100%; font-weight:800; font-size:13px; height:auto; padding:8px 16px;">
                    💾 Save Action &amp; Dispatch Submitter Notification &rarr;
                </button>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 7. Handle Grievance Action Save & Dispatch Outgoing Email via Zoho SMTP Fallback
 */
add_action('save_post_grievance', 'mvc_handle_classic_grievance_save', 20, 2);
function mvc_handle_classic_grievance_save($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!isset($_POST['mvc_grievance_nonce']) || !wp_verify_nonce($_POST['mvc_grievance_nonce'], 'mvc_save_grievance_action_nonce')) {
        return;
    }

    $current_user_id = get_current_user_id();
    if (!function_exists('mvc_grievance_user_can_manage') || !mvc_grievance_user_can_manage($current_user_id, $post_id)) {
        return;
    }

    $ref_no      = get_post_meta($post_id, '_mvc_grv_ref', true) ?: ('MVC-CASE-' . $post_id);
    $sub_name    = get_post_meta($post_id, '_mvc_grv_submitter_name', true) ?: 'Scholar / Citizen';
    $sub_email   = get_post_meta($post_id, '_mvc_grv_submitter_email', true);
    $form_title  = get_post_meta($post_id, '_mvc_grv_form_title', true) ?: 'Collegiate Portal';
    $target_mail = !empty($sub_email) ? $sub_email : ($post->post_author ? get_userdata($post->post_author)->user_email : '');

    $has_changes_for_submitter = false;
    $notification_notes = array();

    // 1. Process Status Update
    if (!empty($_POST['mvc_action_status'])) {
        $target_status = sanitize_key($_POST['mvc_action_status']);
        $current_terms = wp_get_object_terms($post_id, 'grievance-status');
        $old_status    = (!empty($current_terms) && !is_wp_error($current_terms)) ? $current_terms[0]->slug : '';

        if ($target_status !== $old_status) {
            wp_set_object_terms($post_id, array($target_status), 'grievance-status', false);
            mvc_add_grievance_event(
                $post_id,
                $current_user_id,
                'status',
                'public',
                sprintf('Official status updated from %s to %s.', strtoupper($old_status ?: 'LOGGED'), strtoupper($target_status))
            );
            $has_changes_for_submitter = true;
            $notification_notes[] = sprintf('Status updated to: <strong>%s</strong>', strtoupper($target_status));
        }
    }

    // 2. Process Department Re-assignment
    if (isset($_POST['mvc_action_dept_id'])) {
        $dept_term_id = (int) $_POST['mvc_action_dept_id'];
        $is_dept_scoped = function_exists('mvc_is_user_dept_scoped') && mvc_is_user_dept_scoped(wp_get_current_user());

        if (!$is_dept_scoped) {
            if ($dept_term_id > 0) {
                wp_set_object_terms($post_id, array($dept_term_id), 'dept_link', false);
                $dterm = get_term($dept_term_id, 'dept_link');
                if ($dterm && !is_wp_error($dterm)) {
                    update_post_meta($post_id, '_mvc_grv_department', $dterm->name);
                    mvc_add_grievance_event(
                        $post_id,
                        $current_user_id,
                        'assigned',
                        'public',
                        sprintf('Case officially assigned to the Department of %s.', $dterm->name)
                    );
                    $notification_notes[] = sprintf('Case routed to: <strong>Department of %s</strong>', esc_html($dterm->name));
                    $has_changes_for_submitter = true;
                }
            } else {
                wp_set_object_terms($post_id, array(), 'dept_link', false);
                delete_post_meta($post_id, '_mvc_grv_department');
            }
        }
    }

    // 3. Process Case Note
    if (!empty($_POST['mvc_action_note'])) {
        $note_text = sanitize_textarea_field(trim($_POST['mvc_action_note']));
        $vis       = (isset($_POST['mvc_note_visibility']) && $_POST['mvc_note_visibility'] === 'internal') ? 'internal' : 'public';

        if (!empty($note_text)) {
            mvc_add_grievance_event(
                $post_id,
                $current_user_id,
                'note',
                $vis,
                $note_text
            );

            if ($vis === 'public') {
                $has_changes_for_submitter = true;
                $notification_notes[] = '<strong>Official Committee Remarks:</strong><br>' . nl2br(esc_html($note_text));
            }
        }
    }

    // 4. Process Certified Outcome
    if (!empty($_POST['mvc_action_outcome'])) {
        $outcome_text = sanitize_textarea_field(trim($_POST['mvc_action_outcome']));
        $old_outcome  = get_post_meta($post_id, '_mvc_grv_outcome', true);

        if (!empty($outcome_text) && $outcome_text !== $old_outcome) {
            update_post_meta($post_id, '_mvc_grv_outcome', $outcome_text);
            wp_set_object_terms($post_id, array('resolved'), 'grievance-status', false);

            mvc_add_grievance_event(
                $post_id,
                $current_user_id,
                'outcome',
                'public',
                sprintf("Certified Redressal Outcome recorded:\n%s", $outcome_text)
            );

            $has_changes_for_submitter = true;
            $notification_notes[] = '<strong>🏛️ Certified Resolution Outcome:</strong><br>' . nl2br(esc_html($outcome_text));
        }
    }

    // 5. Dispatch Email to Submitter via Centralized Zoho SMTP Fallback
    if ($has_changes_for_submitter && !empty($target_mail) && is_email($target_mail)) {
        $tracking_url = home_url('/track/?ref=' . rawurlencode($ref_no) . '&email=' . rawurlencode($target_mail));
        $admin_email  = get_option('admin_email') ?: 'mvcollegebuxar@gmail.com';
        $college_name = function_exists('mvc_get_global_setting') ? mvc_get_global_setting('college_name', 'Maharshi Vishwamitra Mahavidyalaya, Buxar') : 'Maharshi Vishwamitra Mahavidyalaya, Buxar';

        $subject = sprintf('[%s] Official Case Update — Ref: %s', 'M.V. College, Buxar', $ref_no);
        $notes_html = implode('<hr style="border:0; border-top:1px dashed #CBD5E1; margin:14px 0;">', $notification_notes);

        $body_html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . esc_html($subject) . '</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 24px; color: #1E293B; line-height: 1.6; }
                .card { max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,33,71,0.08); border: 1px solid #E2E8F0; }
                .header { background: linear-gradient(135deg, #002147 0%, #800000 100%); padding: 26px 24px; text-align: center; color: #ffffff; }
                .header h1 { margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 0.5px; }
                .header p { margin: 6px 0 0; font-size: 13px; color: #D4AF37; }
                .content { padding: 30px 26px; }
                .ref-badge { display: inline-block; background: #EFF6FF; color: #1E40AF; font-weight: 800; font-size: 13px; padding: 6px 14px; border-radius: 6px; border: 1px solid #BFDBFE; font-family: monospace; }
                .notes-card { background: #FAF9F6; border-left: 4px solid #D4AF37; padding: 18px 20px; border-radius: 0 8px 8px 0; font-size: 14px; color: #1E293B; margin: 20px 0; line-height: 1.6; }
                .btn { display: inline-block; background: linear-gradient(135deg, #002147 0%, #800000 100%); color: #ffffff !important; font-weight: 800; font-size: 14px; text-decoration: none; padding: 12px 28px; border-radius: 8px; margin: 20px 0 10px; }
                .footer { padding: 18px 24px; background: #F8FAFC; text-align: center; font-size: 12px; color: #94A3B8; border-top: 1px solid #E2E8F0; }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="header">
                    <h1>🏛️ ' . esc_html($college_name) . '</h1>
                    <p>Official Grievance Redressal &amp; Inquiry Operations Cell</p>
                </div>
                <div class="content">
                    <div style="margin-bottom:14px;">
                        <span class="ref-badge">Case Reference: ' . esc_html($ref_no) . '</span>
                    </div>
                    <h2 style="margin:0 0 12px 0; color:#002147; font-size:18px;">Official Status &amp; Investigation Update</h2>
                    <p style="margin:0 0 14px 0; font-size:14px; color:#334155;">
                        Dear <strong>' . esc_html($sub_name) . '</strong>,
                    </p>
                    <p style="margin:0 0 16px 0; font-size:14px; color:#475569;">
                        An official action or resolution update has been recorded regarding your submission to the <strong>' . esc_html($form_title) . '</strong> by the institutional authority committee.
                    </p>

                    <div class="notes-card">
                        ' . $notes_html . '
                    </div>

                    <div style="text-align:center;">
                        <a href="' . esc_url($tracking_url) . '" class="btn">
                            🔎 View Live Dossier &amp; Full Timeline &rarr;
                        </a>
                    </div>

                    <p style="margin:20px 0 0 0; font-size:13px; color:#64748B;">
                        You can reply directly to this email or visit your online tracking portal above to review all historical milestones and provide additional information to the committee.
                    </p>
                </div>
                <div class="footer">
                    <strong>Maharshi Vishwamitra Mahavidyalaya, Buxar</strong><br>
                    Secretariat of Grievance Redressal &amp; Student Inquiries &bull; Helpline: 06183-796317<br>
                    Official Admin Contact: ' . esc_html($admin_email) . '
                </div>
            </div>
        </body>
        </html>';

        if (function_exists('mvc_dispatch_smtp_email')) {
            mvc_dispatch_smtp_email($target_mail, $subject, $body_html, array(), $admin_email);
        }
    }
}

