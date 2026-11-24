<?php
/**
 * MV College Institutional Notifications Engine
 * 
 * Handles email notifications for content workflow, grievances, accounts, and SLA alerts.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dispatch an institutional email notification with HTML template and audit logging.
 *
 * @param string|array $to       Recipient email(s)
 * @param string       $subject  Email subject
 * @param string       $body     HTML body
 * @param string       $event    Event name for logging
 * @return bool
 */
function mvc_send_notification($to, $subject, $body, $event = 'notification') {
    $site_name = get_bloginfo('name') ?: 'M.V. College Buxar';
    $site_url  = home_url('/');

    // Wrapped in institutional Royal Navy & Metallic Gold letterhead container
    $html_message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . esc_html($subject) . '</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; color: #1e293b; }
            .mvc-mail-card { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-top: 4px solid #D4AF37; }
            .mvc-mail-header { background: #002147; padding: 24px; text-align: center; color: #ffffff; }
            .mvc-mail-crest { font-size: 28px; line-height: 1; margin-bottom: 8px; }
            .mvc-mail-college { font-size: 16px; font-weight: 700; letter-spacing: 0.5px; color: #ffffff; text-transform: uppercase; margin: 0; }
            .mvc-mail-tagline { font-size: 12px; color: #cbd5e1; margin-top: 4px; }
            .mvc-mail-body { padding: 32px 28px; line-height: 1.6; font-size: 14.5px; }
            .mvc-mail-footer { background: #f8fafc; padding: 20px 24px; font-size: 12px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; }
            .mvc-btn-link { display: inline-block; background: #002147; color: #ffffff !important; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 16px; }
        </style>
    </head>
    <body>
        <div class="mvc-mail-card">
            <div class="mvc-mail-header">
                <div class="mvc-mail-crest">🏛️</div>
                <div class="mvc-mail-college">Maharshi Vishwamitra Mahavidyalaya</div>
                <div class="mvc-mail-tagline">Buxar, Bihar • Constituent Unit of V.K.S.U. Ara (Estd. 1958)</div>
            </div>
            <div class="mvc-mail-body">
                ' . $body . '
            </div>
            <div class="mvc-mail-footer">
                <p>This is an automated institutional notification from M.V. College Buxar.</p>
                <p><a href="' . esc_url($site_url) . '" style="color:#002147; text-decoration:none;">' . esc_html($site_url) . '</a></p>
            </div>
        </div>
    </body>
    </html>';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . (isset($_SERVER['SERVER_NAME']) ? sanitize_text_field(wp_unslash($_SERVER['SERVER_NAME'])) : 'mvcollegebuxar.ac.in') . '>',
    );

    $sent = wp_mail($to, $subject, $html_message, $headers);

    if (!$sent) {
        mvc_audit('email_failed', 'system', 0, array(
            'event'   => $event,
            'subject' => $subject,
            'to'      => is_array($to) ? implode(', ', $to) : $to,
        ));
    }

    return $sent;
}

/**
 * Helper to get all Content Manager and Administrator emails.
 */
function mvc_get_staff_notification_emails($include_admin_only = false) {
    $roles = $include_admin_only ? array('administrator') : array('administrator', 'content_manager');
    $users = get_users(array('role__in' => $roles, 'fields' => array('user_email')));
    $emails = array();
    foreach ($users as $u) {
        if (!empty($u->user_email)) {
            $emails[] = $u->user_email;
        }
    }
    return array_unique($emails);
}

/* ========================================================
   WORKFLOW EVENT LISTENERS
   ======================================================== */
add_action('mvc_content_submitted', function($post_id, $post) {
    $emails = mvc_get_staff_notification_emails(false);
    if (!empty($emails)) {
        $subject = sprintf('[Review Required] New Submission: %s', $post->post_title);
        $body = '
            <h3 style="color:#002147; margin-top:0;">New Content Submitted for Review</h3>
            <p>A new item has been submitted to the editorial review queue:</p>
            <div style="background:#f1f5f9; padding:16px; border-radius:6px; margin:16px 0;">
                <strong>Title:</strong> ' . esc_html($post->post_title) . '<br>
                <strong>Type:</strong> ' . esc_html($post->post_type) . '<br>
                <strong>Author:</strong> ' . esc_html(get_the_author_meta('display_name', $post->post_author)) . '<br>
                <strong>Submitted At:</strong> ' . esc_html(current_time('mysql')) . '
            </div>
            <p><a href="' . esc_url(admin_url('admin.php?page=mvc-command-center&section=review-queue')) . '" class="mvc-btn-link">Open Review Queue &rarr;</a></p>
        ';
        mvc_send_notification($emails, $subject, $body, 'content_submitted');
    }
}, 10, 2);

add_action('mvc_content_approved', function($post_id, $post) {
    $author = get_userdata($post->post_author);
    if ($author && !empty($author->user_email)) {
        $subject = sprintf('[Published] Your Submission is Live: %s', $post->post_title);
        $body = '
            <h3 style="color:#002147; margin-top:0;">Content Approved & Published</h3>
            <p>Dear ' . esc_html($author->display_name) . ',</p>
            <p>Your submission <strong>' . esc_html($post->post_title) . '</strong> has been reviewed, approved, and officially published on the college portal.</p>
            <p><a href="' . esc_url(get_permalink($post_id)) . '" class="mvc-btn-link">View Published Item &rarr;</a></p>
        ';
        mvc_send_notification($author->user_email, $subject, $body, 'content_approved');
    }
}, 10, 2);

add_action('mvc_content_rejected', function($post_id, $post, $reason) {
    $author = get_userdata($post->post_author);
    if ($author && !empty($author->user_email)) {
        $subject = sprintf('[Revision Required] Submission Returned: %s', $post->post_title);
        $body = '
            <h3 style="color:#B71C1C; margin-top:0;">Submission Returned for Revision</h3>
            <p>Dear ' . esc_html($author->display_name) . ',</p>
            <p>Your submission <strong>' . esc_html($post->post_title) . '</strong> has been returned to draft status by the editorial review team with the following notes:</p>
            <div style="background:#FEF2F2; border-left:4px solid #EF4444; padding:16px; border-radius:4px; margin:16px 0; color:#991B1B;">
                <strong>Editorial Feedback:</strong><br>' . nl2br(esc_html($reason)) . '
            </div>
            <p>Please update the item according to the feedback and resubmit for approval.</p>
            <p><a href="' . esc_url(admin_url('admin.php?page=mvc-command-center&section=my-submissions')) . '" class="mvc-btn-link">Edit Submission &rarr;</a></p>
        ';
        mvc_send_notification($author->user_email, $subject, $body, 'content_rejected');
    }
}, 10, 3);

add_action('mvc_change_requested', function($post_id, $post, $notes) {
    $emails = mvc_get_staff_notification_emails(false);
    if (!empty($emails)) {
        $subject = sprintf('[Change Request] Edit Requested on Live Item: %s', $post->post_title);
        $body = '
            <h3 style="color:#002147; margin-top:0;">Change Requested on Published Item</h3>
            <p>A Department Head has requested modifications to a live publication:</p>
            <div style="background:#f1f5f9; padding:16px; border-radius:6px; margin:16px 0;">
                <strong>Item:</strong> ' . esc_html($post->post_title) . ' (' . esc_html($post->post_type) . ')<br>
                <strong>Requested Changes:</strong><br>' . nl2br(esc_html($notes)) . '
            </div>
            <p><a href="' . esc_url(admin_url('admin.php?page=mvc-command-center&section=content')) . '" class="mvc-btn-link">Review in Command Center &rarr;</a></p>
        ';
        mvc_send_notification($emails, $subject, $body, 'change_requested');
    }
}, 10, 3);

/* ========================================================
   GRIEVANCE EVENT LISTENERS (STRICT PRIVACY: REF ONLY)
   ======================================================== */
add_action('mvc_grievance_logged', function($grievance_id, $ref_no, $student_id, $is_restricted) {
    // 1. Send acknowledgement to student (Ref number + login link only)
    $student = get_userdata($student_id);
    if ($student && !empty($student->user_email)) {
        $subject = sprintf('[Grievance Cell] Acknowledgement: Reference %s', $ref_no);
        $body = '
            <h3 style="color:#002147; margin-top:0;">Grievance Submission Recorded</h3>
            <p>Dear Scholar,</p>
            <p>Your official grievance has been securely logged with the M.V. College Grievance Redressal Cell.</p>
            <div style="background:#F8FAFC; border:1px solid #E2E8F0; padding:16px; border-radius:6px; margin:16px 0;">
                <strong>Official Reference Number:</strong> <span style="font-family:monospace; font-size:16px; color:#002147;">' . esc_html($ref_no) . '</span><br>
                <strong>Target Resolution Period:</strong> 15 Working Days
            </div>
            <p>You can track updates and communication on this matter directly in My Portal.</p>
            <p><a href="' . esc_url(home_url('/portal/grievances/' . $ref_no . '/')) . '" class="mvc-btn-link">Track Grievance &rarr;</a></p>
        ';
        mvc_send_notification($student->user_email, $subject, $body, 'grievance_logged');
    }

    // 2. Alert staff (Administrators only if restricted; otherwise Content Managers too)
    $staff_emails = mvc_get_staff_notification_emails($is_restricted);
    if (!empty($staff_emails)) {
        $subject = sprintf('[Grievance Cell] New Grievance %s Logged', $ref_no);
        $body = '
            <h3 style="color:#002147; margin-top:0;">New Grievance Registered</h3>
            <p>A new student grievance has been registered in the system:</p>
            <div style="background:#F8FAFC; padding:16px; border-radius:6px; margin:16px 0;">
                <strong>Reference Number:</strong> ' . esc_html($ref_no) . '<br>
                <strong>Classification:</strong> ' . ($is_restricted ? '<span style="color:#B71C1C; font-weight:bold;">RESTRICTED (Administrative / Sensitive)</span>' : 'Standard') . '<br>
                <strong>Logged At:</strong> ' . esc_html(current_time('mysql')) . '
            </div>
            <p><a href="' . esc_url(admin_url('admin.php?page=mvc-command-center&section=grievances')) . '" class="mvc-btn-link">Open Grievance Desk &rarr;</a></p>
        ';
        mvc_send_notification($staff_emails, $subject, $body, 'grievance_logged_staff');
    }
}, 10, 4);

add_action('mvc_grievance_status_changed', function($grievance_id, $ref_no, $new_status, $student_id) {
    $student = get_userdata($student_id);
    if ($student && !empty($student->user_email)) {
        $subject = sprintf('[Grievance Cell] Status Update: Reference %s', $ref_no);
        $body = '
            <h3 style="color:#002147; margin-top:0;">Grievance Status Update</h3>
            <p>Dear Scholar,</p>
            <p>The status of your grievance <strong>' . esc_html($ref_no) . '</strong> has been updated to: <strong>' . esc_html(strtoupper($new_status)) . '</strong>.</p>
            <p>Please log in to your student portal to view authority notes and the certified resolution outcome.</p>
            <p><a href="' . esc_url(home_url('/portal/grievances/' . $ref_no . '/')) . '" class="mvc-btn-link">View Details in Portal &rarr;</a></p>
        ';
        mvc_send_notification($student->user_email, $subject, $body, 'grievance_status');
    }
}, 10, 4);

/* ========================================================
   ACCOUNT LIFECYCLE EVENT LISTENERS
   ======================================================== */
add_action('mvc_account_activated', function($user_id, $user) {
    $key = get_password_reset_key($user);
    if (!is_wp_error($key) && !empty($user->user_email)) {
        $reset_url = network_site_url("wp-login.php?action=rp&key={$key}&login=" . rawurlencode($user->user_login), 'login');

        $subject = '[M.V. College] Welcome! Activate Your Official Account';
        $body = '
            <h3 style="color:#002147; margin-top:0;">Official Account Activation</h3>
            <p>Dear ' . esc_html($user->display_name) . ',</p>
            <p>Your institutional portal account has been approved and activated. Please set your password using the link below to get started:</p>
            <p><a href="' . esc_url($reset_url) . '" class="mvc-btn-link">Set Your Password &rarr;</a></p>
            <p style="font-size:12.5px; color:#64748b; margin-top:16px;">This activation link is valid for 48 hours. After setting your password, you can sign in directly at the <a href="' . esc_url(home_url('/login/')) . '">College Login Gateway</a>.</p>
        ';
        mvc_send_notification($user->user_email, $subject, $body, 'account_activated');
    }
}, 10, 2);

/* ========================================================
   ROLE-AWARE NOTIFICATION DISPATCH ENGINE
   ======================================================== */
/**
 * Retrieve authentic, role-scoped institutional notifications for the Modern Command Center.
 *
 * @param int|null $user_id
 * @return array
 */
function mvc_get_role_notifications($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    $user = get_userdata($user_id);
    if (!$user) {
        return array();
    }

    $roles = (array) $user->roles;
    $is_admin = in_array('administrator', $roles, true);
    $is_hod   = in_array('department_head', $roles, true) || in_array('department_editor', $roles, true);
    $is_notice_mgr = in_array('notice_manager', $roles, true);
    $is_grievance_officer = in_array('grievance_officer', $roles, true);

    $notifications = array();

    if ($is_admin) {
        // 1. Pending Editorial Reviews
        $pending_q = new WP_Query(array(
            'post_status'    => 'pending',
            'post_type'      => array('notice', 'event', 'faculty', 'study_material', 'department'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ));
        $pending_count = (int) $pending_q->found_posts;
        if ($pending_count > 0) {
            $notifications[] = array(
                'id'             => 'admin_pending_reviews',
                'type'           => 'review',
                'badge'          => 'Editorial Review',
                'badge_color'    => '#F59E0B',
                'icon'           => '📬',
                'title'          => $pending_count . ' Submission' . ($pending_count === 1 ? '' : 's') . ' Awaiting Editorial Approval',
                'desc'           => 'Department circulars or faculty submissions require administrative sign-off.',
                'target_section' => 'review-queue',
                'time'           => 'Requires Action',
                'unread'         => true,
            );
        }

        // 2. Active Student Grievances
        $grv_q = new WP_Query(array(
            'post_type'      => 'grievance',
            'post_status'    => array('logged', 'in-review'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ));
        $grv_count = (int) $grv_q->found_posts;
        if ($grv_count > 0) {
            $notifications[] = array(
                'id'             => 'admin_active_grievances',
                'type'           => 'grievance',
                'badge'          => 'Statutory SLA',
                'badge_color'    => '#DC2626',
                'icon'           => '⚖️',
                'title'          => $grv_count . ' Active Student Grievance' . ($grv_count === 1 ? '' : 's'),
                'desc'           => 'Open student grievance dossiers undergoing 15-day institutional review.',
                'target_section' => 'grievances',
                'time'           => 'Under SLA',
                'unread'         => true,
            );
        }

        // 3. Pending Scholar Account Activations
        $pending_users = get_users(array(
            'meta_key'   => 'mvc_account_status',
            'meta_value' => 'pending',
            'fields'     => 'ID'
        ));
        $pending_users_count = is_array($pending_users) ? count($pending_users) : 0;
        if ($pending_users_count > 0) {
            $notifications[] = array(
                'id'             => 'admin_pending_accounts',
                'type'           => 'account',
                'badge'          => 'Access Matrix',
                'badge_color'    => '#3B82F6',
                'icon'           => '👤',
                'title'          => $pending_users_count . ' Scholar Account' . ($pending_users_count === 1 ? '' : 's') . ' Pending Approval',
                'desc'           => 'New portal registrations awaiting administrative identity verification.',
                'target_section' => 'accounts',
                'time'           => 'Pending Verification',
                'unread'         => true,
            );
        }

        // 4. Institutional StoreFront Theme Protection
        $lock_active = get_option('mvc_update_lock_active', true);
        if ($lock_active) {
            $notifications[] = array(
                'id'             => 'admin_theme_lock',
                'type'           => 'system',
                'badge'          => 'System Shield',
                'badge_color'    => '#10B981',
                'icon'           => '🛡️',
                'title'          => 'RetailCoder StoreFront Shield Active',
                'desc'           => 'Theme rebrand & upstream update lock enforced. Core collegiate assets secured.',
                'target_section' => 'settings',
                'time'           => 'Standing Guard',
                'unread'         => false,
            );
        }
    } elseif ($is_hod) {
        // HOD NOTIFICATIONS
        // Resolve HOD's department
        $hod_dept_term_id = (int) get_user_meta($user_id, 'mvc_dept_term_id', true);
        if (!$hod_dept_term_id) {
            $assigned_p = (int) get_user_meta($user_id, 'mvc_assigned_department', true);
            if ($assigned_p) {
                $terms = wp_get_object_terms($assigned_p, 'dept_link');
                if (!empty($terms) && !is_wp_error($terms)) {
                    $hod_dept_term_id = $terms[0]->term_id;
                }
            }
        }
        $dept_term = $hod_dept_term_id ? get_term($hod_dept_term_id, 'dept_link') : null;
        $dept_slug = ($dept_term && !is_wp_error($dept_term)) ? $dept_term->slug : 'physics';
        $dept_name = ($dept_term && !is_wp_error($dept_term)) ? $dept_term->name : 'Department of Physics';

        // 1. Authentic Department Circulars
        $dept_notices = get_posts(array(
            'post_type'      => 'notice',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'dept_link',
                    'field'    => 'slug',
                    'terms'    => $dept_slug,
                ),
            ),
        ));
        foreach ($dept_notices as $latest) {
            $notifications[] = array(
                'id'             => 'hod_dept_notice_' . $latest->ID,
                'type'           => 'notice',
                'badge'          => 'Dept Circular',
                'badge_color'    => '#800000',
                'icon'           => '📢',
                'title'          => esc_html($latest->post_title),
                'desc'           => 'Official circular issued under ' . esc_html($dept_name) . ' (' . get_the_date('j M Y', $latest->ID) . ').',
                'target_section' => 'my-department',
                'time'           => get_the_date('j M', $latest->ID),
                'unread'         => true,
            );
        }

        // 2. Authentic Faculty Scholars Roster Health
        $dept_faculty = get_posts(array(
            'post_type'      => 'faculty',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'dept_link',
                    'field'    => 'slug',
                    'terms'    => $dept_slug,
                ),
            ),
        ));
        $fac_count = count($dept_faculty);
        if ($fac_count > 0) {
            $notifications[] = array(
                'id'             => 'hod_faculty_roster_' . $dept_slug,
                'type'           => 'faculty',
                'badge'          => 'Faculty Roster',
                'badge_color'    => '#10B981',
                'icon'           => '👨‍🏫',
                'title'          => $fac_count . ' Active Faculty Scholars Verified',
                'desc'           => 'Educators assigned to ' . esc_html($dept_name) . '. Roster synchronized with public portal.',
                'target_section' => 'my-department',
                'time'           => 'Synchronized',
                'unread'         => false,
            );
        }

        // 3. NAAC Criterion II & Curriculum Compliance (Auditing Authentic ACF DB Fields)
        $dept_post = get_page_by_path($dept_slug, OBJECT, 'department');
        if ($dept_post) {
            $has_syl = !empty(get_field('syllabus', $dept_post->ID));
            $has_co  = !empty(get_field('outcomes', $dept_post->ID));
            if (!$has_syl || !$has_co) {
                $notifications[] = array(
                    'id'             => 'hod_naac_curriculum_action',
                    'type'           => 'compliance',
                    'badge'          => 'NAAC Criterion II',
                    'badge_color'    => '#DC2626',
                    'icon'           => '📖',
                    'title'          => 'NAAC Outcomes or Syllabus Pending',
                    'desc'           => 'Statutory documentation incomplete for ' . esc_html($dept_name) . '.',
                    'target_section' => 'my-department',
                    'time'           => 'Accreditation',
                    'unread'         => true,
                );
            } else {
                $notifications[] = array(
                    'id'             => 'hod_naac_curriculum_ok',
                    'type'           => 'compliance',
                    'badge'          => 'NAAC Aligned',
                    'badge_color'    => '#059669',
                    'icon'           => '📜',
                    'title'          => 'NEP-2020 & NAAC Compliance Verified',
                    'desc'           => 'Course syllabus and learning outcomes fully articulated for statutory inspection.',
                    'target_section' => 'my-department',
                    'time'           => 'Compliant',
                    'unread'         => false,
                );
            }
        }

        // 4. College-Wide Notice Broadcast (Only Authentic General Institutional Circulars, NOT specific to another department)
        $recent_college_notice = get_posts(array(
            'post_type'      => 'notice',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'dept_link',
                    'operator' => 'NOT EXISTS',
                ),
            ),
        ));
        if (!empty($recent_college_notice)) {
            $rn = $recent_college_notice[0];
            $notifications[] = array(
                'id'             => 'college_notice_' . $rn->ID,
                'type'           => 'general',
                'badge'          => 'Campus Bulletin',
                'badge_color'    => '#002147',
                'icon'           => '🏛️',
                'title'          => esc_html($rn->post_title),
                'desc'           => 'Institutional circular from Principal & Digital Governance Desk.',
                'target_section' => 'my-department',
                'time'           => get_the_date('j M Y', $rn->ID),
                'unread'         => false,
            );
        }
    } elseif ($is_notice_mgr) {
        // Notice Manager Notifications
        $pending_notices = get_posts(array(
            'post_type'      => 'notice',
            'post_status'    => 'pending',
            'posts_per_page' => 5,
        ));
        $count = count($pending_notices);
        if ($count > 0) {
            $notifications[] = array(
                'id'             => 'mgr_pending_notices',
                'type'           => 'notice',
                'badge'          => 'Notices Cell',
                'badge_color'    => '#F59E0B',
                'icon'           => '📢',
                'title'          => $count . ' Notice' . ($count === 1 ? '' : 's') . ' Pending Publication',
                'desc'           => 'Circulars awaiting editorial review before going live on the public site.',
                'target_section' => 'content-desk',
                'time'           => 'Requires Review',
                'unread'         => true,
            );
        }
    } elseif ($is_grievance_officer) {
        // Grievance Officer Notifications
        $grv_q = new WP_Query(array(
            'post_type'      => 'grievance',
            'post_status'    => array('logged', 'in-review'),
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ));
        $count = (int) $grv_q->found_posts;
        if ($count > 0) {
            $notifications[] = array(
                'id'             => 'officer_grievances',
                'type'           => 'grievance',
                'badge'          => '15-Day SLA',
                'badge_color'    => '#DC2626',
                'icon'           => '⚖️',
                'title'          => $count . ' Active Student Grievance' . ($count === 1 ? '' : 's'),
                'desc'           => 'Assigned grievances under active redressal procedure.',
                'target_section' => 'grievances',
                'time'           => 'Active SLA',
                'unread'         => true,
            );
        }
    }

    return $notifications;
}
