<?php
/**
 * MV College Public & Scholar Inquiry/Grievance Tracking Gateway
 *
 * Provides a beautiful two-way tracking screen:
 * 1. Shortcode [mvc_track_inquiry] & dedicated /track/ page.
 * 2. Visual 4-stage milestone progress bar (Logged -> Under Review -> In Progress -> Resolved).
 * 3. Complete case dossier inspection with public notes & timeline.
 * 4. Submitter follow-up response window, updating the case timeline and alerting authorities.
 *
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Automatically Ensure the /track/ Page Exists on Theme Setup
 */
add_action('init', 'mvc_ensure_tracking_page_exists');
function mvc_ensure_tracking_page_exists() {
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    $page = get_page_by_path('track');
    if (!$page) {
        wp_insert_post(array(
            'post_title'     => __('Track Grievance & Official Inquiry', 'mvc-college'),
            'post_name'      => 'track',
            'post_content'   => '[mvc_track_inquiry]',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
        ));
    }
}

/**
 * 2. Register Shortcode [mvc_track_inquiry]
 */
add_shortcode('mvc_track_inquiry', 'mvc_render_tracking_portal_shortcode');
function mvc_render_tracking_portal_shortcode() {
    ob_start();

    // Check if submitter submitted a clarification follow-up note
    $notice_msg = '';
    $notice_type = 'success';

    if (isset($_POST['mvc_submitter_followup_nonce']) && wp_verify_nonce($_POST['mvc_submitter_followup_nonce'], 'mvc_submitter_followup_action')) {
        $grv_id    = (int) ($_POST['mvc_grv_id'] ?? 0);
        $reply_ref = sanitize_text_field($_POST['mvc_reply_ref'] ?? '');
        $reply_msg = sanitize_textarea_field(trim($_POST['mvc_reply_message'] ?? ''));

        if ($grv_id && !empty($reply_msg)) {
            $post = get_post($grv_id);
            if ($post && $post->post_type === 'grievance') {
                $sub_email = get_post_meta($grv_id, '_mvc_grv_submitter_email', true);
                $sub_name  = get_post_meta($grv_id, '_mvc_grv_submitter_name', true) ?: 'Submitter';

                // Record event in timeline
                mvc_add_grievance_event(
                    $grv_id,
                    0,
                    'note',
                    'public',
                    sprintf("Submitter Clarification / Follow-up (%s):\n%s", $sub_name, $reply_msg)
                );

                // Alert administration
                $admin_email = get_option('admin_email') ?: 'mvcollegebuxar@gmail.com';
                $subject = sprintf('[Submitter Reply] Case %s: New follow-up received from %s', $reply_ref, $sub_name);
                $alert_body = sprintf(
                    "<p>A new clarification response has been posted by <strong>%s</strong> (%s) regarding case <strong>%s</strong>:</p><blockquote style='border-left:4px solid #002147; padding:8px 14px; background:#F8FAFC;'>%s</blockquote><p><a href='%s'>View Case in Admin &rarr;</a></p>",
                    esc_html($sub_name),
                    esc_html($sub_email),
                    esc_html($reply_ref),
                    nl2br(esc_html($reply_msg)),
                    esc_url(admin_url('post.php?post=' . $grv_id . '&action=edit'))
                );

                if (function_exists('mvc_dispatch_smtp_email')) {
                    mvc_dispatch_smtp_email($admin_email, $subject, $alert_body, array(), $sub_email);
                }

                $notice_msg = __('Your clarification response has been officially recorded and dispatched to the inquiry committee.', 'mvc-college');
            }
        }
    }

    $req_ref   = isset($_REQUEST['ref']) ? sanitize_text_field(trim($_REQUEST['ref'])) : '';
    $req_email = isset($_REQUEST['email']) ? sanitize_email(trim($_REQUEST['email'])) : '';

    $case_post = null;
    $error_msg = '';

    if (!empty($req_ref)) {
        // Query by Ref
        $posts = get_posts(array(
            'post_type'      => 'grievance',
            'meta_key'       => '_mvc_grv_ref',
            'meta_value'     => $req_ref,
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ));

        if (!empty($posts)) {
            $candidate = $posts[0];
            $saved_email = strtolower(get_post_meta($candidate->ID, '_mvc_grv_submitter_email', true));
            $is_admin = current_user_can('mvc_handle_grievances') || current_user_can('manage_options');
            $is_author = (get_current_user_id() && (int) $candidate->post_author === get_current_user_id());

            // Check email match or admin authentication
            if ($is_admin || $is_author || (empty($req_email) && empty($saved_email)) || (!empty($req_email) && strtolower($req_email) === $saved_email)) {
                $case_post = $candidate;
            } else {
                $error_msg = __('The email address provided does not match the registered records for this reference number. Please check and try again.', 'mvc-college');
            }
        } else {
            $error_msg = __('No case record found matching reference number: ' . esc_html($req_ref), 'mvc-college');
        }
    }
    ?>
    <div class="mvc-track-container">
        <style>
            .mvc-track-container { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 16px; color: #1E293B; line-height: 1.6; }
            .mvc-track-card { background: #ffffff; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 4px 24px rgba(0,33,71,0.07); overflow: hidden; margin-bottom: 24px; }
            .mvc-track-hero { background: linear-gradient(135deg, #002147 0%, #800000 100%); color: #ffffff; padding: 36px 30px; text-align: center; }
            .mvc-track-hero h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; }
            .mvc-track-hero p { margin: 8px 0 0; font-size: 14px; color: #D4AF37; }
            .mvc-track-search { padding: 26px 30px; background: #FAF9F6; border-bottom: 1px solid #E2E8F0; }
            .mvc-track-form { display: grid; grid-template-columns: 1fr 1fr auto; gap: 14px; align-items: flex-end; }
            @media (max-width: 700px) { .mvc-track-form { grid-template-columns: 1fr; } }
            .mvc-track-input { width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; background: #ffffff; }
            .mvc-track-input:focus { border-color: #002147; outline: none; }
            .mvc-track-btn { background: #002147; color: #ffffff; font-weight: 800; font-size: 14px; padding: 11px 24px; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; height: 44px; }
            .mvc-track-btn:hover { background: #800000; }
            .mvc-stepper { display: flex; justify-content: space-between; position: relative; margin: 30px 10px; }
            .mvc-stepper::before { content: ""; position: absolute; top: 18px; left: 5%; right: 5%; height: 3px; background: #E2E8F0; z-index: 1; }
            .mvc-stepper-progress { position: absolute; top: 18px; left: 5%; height: 3px; background: #16A34A; z-index: 2; transition: width 0.4s ease; }
            .mvc-step { position: relative; z-index: 3; text-align: center; flex: 1; }
            .mvc-step-circle { width: 38px; height: 38px; border-radius: 50%; background: #ffffff; border: 3px solid #CBD5E1; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; color: #64748B; transition: all 0.3s; }
            .mvc-step.active .mvc-step-circle { border-color: #002147; background: #002147; color: #ffffff; box-shadow: 0 0 0 4px rgba(0,33,71,0.15); }
            .mvc-step.completed .mvc-step-circle { border-color: #16A34A; background: #16A34A; color: #ffffff; }
            .mvc-step-label { font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; }
            .mvc-step.active .mvc-step-label { color: #002147; font-weight: 800; }
            .mvc-step.completed .mvc-step-label { color: #16A34A; }
            .mvc-dossier-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size: 13.5px; margin: 16px 0; }
            @media (max-width: 600px) { .mvc-dossier-grid { grid-template-columns: 1fr; } }
            .mvc-dossier-item { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px 14px; }
            .mvc-timeline-card { border-left: 3px solid #002147; padding: 14px 18px; margin-bottom: 14px; background: #FAFBFD; border-radius: 0 8px 8px 0; border: 1px solid #E2E8F0; border-left-width: 4px; }
            .mvc-timeline-card.outcome { border-left-color: #16A34A; background: #F0FDF4; }
            .mvc-alert { padding: 12px 18px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; }
            .mvc-alert-success { background: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
            .mvc-alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
        </style>

        <div class="mvc-track-card">
            <div class="mvc-track-hero">
                <h1>🏛️ Maharshi Vishwamitra Mahavidyalaya, Buxar</h1>
                <p>Public Grievance Redressal &amp; Institutional Inquiry Tracking Gateway</p>
            </div>

            <!-- Search Bar -->
            <div class="mvc-track-search">
                <form method="GET" class="mvc-track-form">
                    <div>
                        <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">
                            Official Reference Number:
                        </label>
                        <input type="text" name="ref" class="mvc-track-input" placeholder="e.g. MVC/GRV/2026/0001" value="<?php echo esc_attr($req_ref); ?>" required>
                    </div>
                    <div>
                        <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:4px;">
                            Registered Submitter Email:
                        </label>
                        <input type="email" name="email" class="mvc-track-input" placeholder="yourname@example.com" value="<?php echo esc_attr($req_email); ?>">
                    </div>
                    <div>
                        <button type="submit" class="mvc-track-btn">Track Case &rarr;</button>
                    </div>
                </form>
            </div>

            <div style="padding: 28px 30px;">
                <?php if (!empty($notice_msg)) : ?>
                    <div class="mvc-alert mvc-alert-success"><?php echo esc_html($notice_msg); ?></div>
                <?php endif; ?>

                <?php if (!empty($error_msg)) : ?>
                    <div class="mvc-alert mvc-alert-error"><?php echo esc_html($error_msg); ?></div>
                <?php endif; ?>

                <?php if ($case_post) : ?>
                    <?php
                    $cid         = $case_post->ID;
                    $ref         = get_post_meta($cid, '_mvc_grv_ref', true) ?: ('MVC-CASE-' . $cid);
                    $sub_name    = get_post_meta($cid, '_mvc_grv_submitter_name', true) ?: 'Scholar / Citizen';
                    $sub_email   = get_post_meta($cid, '_mvc_grv_submitter_email', true);
                    $form_title  = get_post_meta($cid, '_mvc_grv_form_title', true) ?: 'Collegiate Portal';
                    $category    = get_post_meta($cid, '_mvc_grv_category', true) ?: 'General';
                    $due_date    = get_post_meta($cid, '_mvc_grv_due', true);
                    $outcome     = get_post_meta($cid, '_mvc_grv_outcome', true);

                    $status_terms = wp_get_object_terms($cid, 'grievance-status');
                    $status       = !empty($status_terms) && !is_wp_error($status_terms) ? $status_terms[0]->slug : 'logged';

                    $dept_terms   = wp_get_object_terms($cid, 'dept_link');
                    $dept_name    = (!empty($dept_terms) && !is_wp_error($dept_terms)) ? $dept_terms[0]->name : 'Central Administration';

                    // Determine Stepper Active Step
                    // 1: Logged, 2: In-Review, 3: In Progress, 4: Resolved
                    $step_num = 1;
                    $progress_width = '0%';
                    if (in_array($status, array('in-review', 'under-review'), true)) {
                        $step_num = 2;
                        $progress_width = '33%';
                    } elseif (in_array($status, array('action_taken', 'action-taken'), true)) {
                        $step_num = 3;
                        $progress_width = '66%';
                    } elseif (in_array($status, array('resolved', 'closed'), true)) {
                        $step_num = 4;
                        $progress_width = '100%';
                    }

                    // Get public timeline
                    $timeline = function_exists('mvc_get_grievance_timeline') ? mvc_get_grievance_timeline($cid, 0) : array();
                    // Filter to public events only
                    $public_events = array_filter($timeline, function($e) {
                        return ($e['visibility'] ?? 'public') === 'public';
                    });
                    ?>

                    <!-- 4-Stage Visual Progress Tracker -->
                    <div class="mvc-stepper">
                        <div class="mvc-stepper-progress" style="width:<?php echo esc_attr($progress_width); ?>;"></div>
                        <div class="mvc-step <?php echo $step_num >= 1 ? ($step_num > 1 ? 'completed' : 'active') : ''; ?>">
                            <div class="mvc-step-circle"><?php echo $step_num > 1 ? '✓' : '1'; ?></div>
                            <div class="mvc-step-label">1. Logged</div>
                        </div>
                        <div class="mvc-step <?php echo $step_num >= 2 ? ($step_num > 2 ? 'completed' : 'active') : ''; ?>">
                            <div class="mvc-step-circle"><?php echo $step_num > 2 ? '✓' : '2'; ?></div>
                            <div class="mvc-step-label">2. Under Review</div>
                        </div>
                        <div class="mvc-step <?php echo $step_num >= 3 ? ($step_num > 3 ? 'completed' : 'active') : ''; ?>">
                            <div class="mvc-step-circle"><?php echo $step_num > 3 ? '✓' : '3'; ?></div>
                            <div class="mvc-step-label">3. In Progress</div>
                        </div>
                        <div class="mvc-step <?php echo $step_num >= 4 ? 'completed active' : ''; ?>">
                            <div class="mvc-step-circle"><?php echo $step_num >= 4 ? '✓' : '4'; ?></div>
                            <div class="mvc-step-label">4. Resolved</div>
                        </div>
                    </div>

                    <!-- Case Dossier Header -->
                    <div style="background:#FAF9F6; border:1px solid #D4AF37; border-radius:10px; padding:20px; margin:24px 0;">
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:14px; border-bottom:1px solid #CBD5E1; padding-bottom:10px;">
                            <div>
                                <span style="font-size:11.5px; font-weight:800; color:#800000; text-transform:uppercase; letter-spacing:0.5px;">Institutional Case Dossier</span>
                                <h2 style="margin:2px 0 0; font-size:20px; color:#002147; font-weight:800;">Ref: <?php echo esc_html($ref); ?></h2>
                            </div>
                            <div>
                                <span style="display:inline-block; background:#002147; color:#ffffff; font-weight:800; font-size:12px; padding:5px 14px; border-radius:999px; text-transform:uppercase;">
                                    Status: <?php echo esc_html(strtoupper(str_replace(array('-', '_'), ' ', $status))); ?>
                                </span>
                            </div>
                        </div>

                        <div class="mvc-dossier-grid">
                            <div class="mvc-dossier-item"><strong>Submitter Name:</strong> <?php echo esc_html($sub_name); ?></div>
                            <div class="mvc-dossier-item"><strong>Assigned Department:</strong> 🏛️ <?php echo esc_html($dept_name); ?></div>
                            <div class="mvc-dossier-item"><strong>Case Category:</strong> <?php echo esc_html($category); ?></div>
                            <div class="mvc-dossier-item"><strong>Source Portal:</strong> <?php echo esc_html($form_title); ?></div>
                            <div class="mvc-dossier-item"><strong>Date Registered:</strong> <?php echo esc_html(get_the_date('d M Y, h:i A', $cid)); ?></div>
                            <div class="mvc-dossier-item"><strong>SLA Target Date:</strong> <?php echo esc_html($due_date ? gmdate('d M Y', strtotime($due_date)) : '15 Working Days'); ?></div>
                        </div>

                        <div style="margin-top:14px; padding-top:12px; border-top:1px dashed #CBD5E1; font-size:13.5px;">
                            <strong style="color:#002147;">Subject / Matter:</strong> <?php echo esc_html($case_post->post_title); ?><br><br>
                            <strong style="color:#002147;">Submitted Representation:</strong><br>
                            <?php echo nl2br(esc_html($case_post->post_content)); ?>
                        </div>
                    </div>

                    <!-- Certified Redressal Outcome (if resolved) -->
                    <?php if (!empty($outcome)) : ?>
                        <div class="mvc-timeline-card outcome">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                                <span style="font-size:20px;">🏛️</span>
                                <h3 style="margin:0; font-size:16px; color:#166534; font-weight:800;">Official Certified Redressal Outcome</h3>
                            </div>
                            <div style="font-size:14px; color:#14532D; line-height:1.6;">
                                <?php echo nl2br(esc_html($outcome)); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Timeline of Public Updates & Authority Remarks -->
                    <h3 style="margin:28px 0 14px; color:#002147; font-size:17px; font-weight:800;">
                        📜 Official Case Milestones &amp; Authority Notes
                    </h3>

                    <?php if (empty($public_events)) : ?>
                        <p style="color:#64748B; font-size:13.5px;">Your case is registered and under initial administrative scrutiny. Updates posted by the inquiry committee will appear here in real-time.</p>
                    <?php else : ?>
                        <?php foreach ($public_events as $ev) : ?>
                            <?php
                            $actor_name = $ev['actor_name'] ?: 'Inquiry Authority';
                            $date_str   = gmdate('d M Y, h:i A', strtotime($ev['created_at']));
                            ?>
                            <div class="mvc-timeline-card">
                                <div style="display:flex; justify-content:space-between; font-size:12px; color:#64748B; margin-bottom:6px;">
                                    <strong>👤 <?php echo esc_html($actor_name); ?></strong>
                                    <span>🕒 <?php echo esc_html($date_str); ?></span>
                                </div>
                                <div style="font-size:13.5px; color:#1E293B; line-height:1.55;">
                                    <?php echo nl2br(esc_html($ev['body'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Submitter Follow-up Reply Window -->
                    <div style="background:#FAFBFD; border:1px solid #CBD5E1; border-radius:10px; padding:20px; margin-top:30px;">
                        <h4 style="margin:0 0 8px; color:#002147; font-size:15px; font-weight:800;">
                            💬 Submit Follow-up or Additional Clarification
                        </h4>
                        <p style="margin:0 0 14px; font-size:13px; color:#64748B;">
                            Have additional information, fee receipt references, or questions for the committee? Submit them directly into your case dossier below.
                        </p>
                        <form method="POST">
                            <?php wp_nonce_field('mvc_submitter_followup_action', 'mvc_submitter_followup_nonce'); ?>
                            <input type="hidden" name="mvc_grv_id" value="<?php echo esc_attr($cid); ?>">
                            <input type="hidden" name="mvc_reply_ref" value="<?php echo esc_attr($ref); ?>">
                            <input type="hidden" name="ref" value="<?php echo esc_attr($ref); ?>">
                            <input type="hidden" name="email" value="<?php echo esc_attr($req_email); ?>">

                            <textarea name="mvc_reply_message" rows="3" class="mvc-track-input" placeholder="Type your clarification or additional factual details here..." style="width:100%; margin-bottom:12px; font-family:inherit;" required></textarea>
                            <button type="submit" class="mvc-track-btn" style="background:#166534;">
                                📤 Submit Clarification to Committee &rarr;
                            </button>
                        </form>
                    </div>

                <?php else : ?>
                    <!-- Welcome / How It Works -->
                    <div style="text-align:center; padding:30px 10px;">
                        <span style="font-size:42px;">🔎</span>
                        <h3 style="margin:12px 0 6px; color:#002147; font-size:18px;">Track Any Submission in Real-Time</h3>
                        <p style="margin:0 auto; max-width:540px; color:#64748B; font-size:14px;">
                            Enter the Official Tracking Reference Number (e.g. <code>MVC/GRV/2026/0001</code>) and your registered email address received in your confirmation receipt to view live inquiry milestones, authority notes, and certified redressal outcomes.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

