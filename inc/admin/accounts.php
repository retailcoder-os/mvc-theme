<?php
/**
 * MV College Account Lifecycle & Verification Engine
 * 
 * Manages Student and Alumni onboarding, verification, profile attributes,
 * session revocation, and DPDP Act compliance.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Student Verifier Interface (Backlog B1 Extension Point).
 */
interface MVC_Student_Verifier {
    /**
     * Verify student claims.
     *
     * @param array $claims
     * @return true|WP_Error
     */
    public function verify(array $claims);
}

/**
 * Default v1 Verifier: Validates that the creating actor has mvc_manage_accounts capability.
 */
class MVC_Manual_Verifier implements MVC_Student_Verifier {
    public function verify(array $claims) {
        if (!current_user_can('mvc_manage_accounts')) {
            return new WP_Error('mvc_forbidden', __('You do not have permission to verify student accounts.', 'mvc-college'));
        }
        return true;
    }
}

/**
 * Check if an actor can manage a target user account.
 * 
 * Content Managers can ONLY act on student and alumni_member accounts.
 * They can NEVER edit administrators, content managers, or department heads.
 *
 * @param int $actor_id
 * @param int $target_user_id
 * @return bool
 */
function mvc_can_manage_user($actor_id, $target_user_id) {
    if (!$actor_id || !$target_user_id) {
        return false;
    }

    $actor = get_userdata($actor_id);
    $target = get_userdata($target_user_id);

    if (!$actor || !$target) {
        return false;
    }

    // Administrators can manage anyone
    if (in_array('administrator', $actor->roles, true)) {
        return true;
    }

    // Content Managers can only manage student and alumni_member roles
    if (user_can($actor, 'mvc_manage_accounts')) {
        $allowed_target_roles = array('student', 'alumni_member');
        $has_only_allowed_roles = !empty(array_intersect($target->roles, $allowed_target_roles)) &&
                                  empty(array_diff($target->roles, $allowed_target_roles));
        return $has_only_allowed_roles;
    }

    return false;
}

/**
 * Create a new student account (called by staff or bulk loop).
 *
 * @param array $data Form fields (name, email, reg_no, session, program, dept_term_id)
 * @return int|WP_Error User ID on success
 */
function mvc_create_student_account(array $data) {
    if (!current_user_can('mvc_manage_accounts')) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to create student accounts.', 'mvc-college'));
    }

    $email   = sanitize_email($data['email'] ?? '');
    $reg_no  = sanitize_text_field(trim($data['reg_no'] ?? ''));
    $name    = sanitize_text_field(trim($data['name'] ?? ''));
    $session = sanitize_text_field(trim($data['session'] ?? ''));
    $program = sanitize_text_field(trim($data['program'] ?? ''));
    $dept_id = (int) ($data['dept_term_id'] ?? 0);

    if (!is_email($email)) {
        return new WP_Error('mvc_invalid_email', __('A valid email address is required.', 'mvc-college'));
    }

    if (empty($reg_no)) {
        return new WP_Error('mvc_invalid_reg_no', __('Registration / Roll number is strictly required.', 'mvc-college'));
    }

    if (empty($name)) {
        return new WP_Error('mvc_invalid_name', __('Student full name is required.', 'mvc-college'));
    }

    // Check duplicate email
    $existing_user = get_user_by('email', $email);
    if ($existing_user) {
        $status = get_user_meta($existing_user->ID, 'mvc_account_status', true) ?: 'active';
        return new WP_Error(
            'mvc_duplicate_email',
            sprintf(__('An account with this email already exists (Status: %s).', 'mvc-college'), strtoupper($status))
        );
    }

    // Check duplicate registration number
    $existing_reg = get_users(array(
        'meta_key'   => 'mvc_reg_no',
        'meta_value' => $reg_no,
        'number'     => 1,
    ));
    if (!empty($existing_reg)) {
        $ex = reset($existing_reg);
        $status = get_user_meta($ex->ID, 'mvc_account_status', true) ?: 'active';
        return new WP_Error(
            'mvc_duplicate_reg',
            sprintf(__('An account with registration number "%s" already exists (Status: %s).', 'mvc-college'), $reg_no, strtoupper($status))
        );
    }

    // Run claims through active verifier
    $verifier = apply_filters('mvc_student_verifier', new MVC_Manual_Verifier());
    if ($verifier instanceof MVC_Student_Verifier) {
        $verification = $verifier->verify($data);
        if (is_wp_error($verification)) {
            return $verification;
        }
    }

    // Create user login based on reg_no or email prefix
    $user_login = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $reg_no));
    if (username_exists($user_login)) {
        $user_login .= '_' . wp_rand(100, 999);
    }

    $random_password = wp_generate_password(24, true);

    $user_id = wp_insert_user(array(
        'user_login'   => $user_login,
        'user_pass'    => $random_password,
        'user_email'   => $email,
        'display_name' => $name,
        'first_name'   => $name,
        'role'         => 'student',
    ));

    if (is_wp_error($user_id)) {
        return $user_id;
    }

    // Store metadata
    update_user_meta($user_id, 'mvc_account_status', 'active');
    update_user_meta($user_id, 'mvc_reg_no', $reg_no);
    update_user_meta($user_id, 'mvc_session', $session);
    update_user_meta($user_id, 'mvc_program', $program);
    update_user_meta($user_id, 'mvc_dept_term_id', $dept_id);

    // Consent records
    update_user_meta($user_id, 'mvc_consent_version', '1.0');
    update_user_meta($user_id, 'mvc_consent_at', current_time('mysql'));

    mvc_audit('account_created', 'user', $user_id, array(
        'role'   => 'student',
        'reg_no' => $reg_no,
        'email'  => $email,
    ));

    $user = get_userdata($user_id);
    do_action('mvc_account_activated', $user_id, $user);

    return $user_id;
}

/**
 * Alumni Registration Hook on WPForms #821.
 */
add_action('wpforms_process_complete', 'mvc_handle_alumni_registration_submission', 10, 4);
function mvc_handle_alumni_registration_submission($fields, $entry, $form_data, $entry_id) {
    if ((int) $form_data['id'] !== 821) {
        return;
    }

    $email = '';
    $name  = '';
    $batch = '';
    $city  = '';
    $prof  = '';

    foreach ($fields as $field) {
        $val = trim($field['value'] ?? '');
        $lbl = strtolower($field['name'] ?? '');

        if (strpos($lbl, 'email') !== false || filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $email = sanitize_email($val);
        } elseif (strpos($lbl, 'name') !== false) {
            $name = sanitize_text_field($val);
        } elseif (strpos($lbl, 'batch') !== false || strpos($lbl, 'year') !== false) {
            $batch = sanitize_text_field($val);
        } elseif (strpos($lbl, 'city') !== false) {
            $city = sanitize_text_field($val);
        } elseif (strpos($lbl, 'profession') !== false || strpos($lbl, 'designation') !== false) {
            $prof = sanitize_text_field($val);
        }
    }

    if (!is_email($email) || empty($batch)) {
        return;
    }

    if (email_exists($email)) {
        return;
    }

    $username = 'alumni_' . sanitize_user(explode('@', $email)[0]);
    if (username_exists($username)) {
        $username .= '_' . wp_rand(10, 99);
    }

    $user_id = wp_insert_user(array(
        'user_login'   => $username,
        'user_pass'    => wp_generate_password(24, true),
        'user_email'   => $email,
        'display_name' => $name ?: $username,
        'role'         => 'alumni_member',
    ));

    if (!is_wp_error($user_id)) {
        update_user_meta($user_id, 'mvc_account_status', 'pending'); // Starts pending
        update_user_meta($user_id, 'mvc_batch_year', $batch);
        update_user_meta($user_id, 'mvc_city', $city);
        update_user_meta($user_id, 'mvc_profession', $prof);
        update_user_meta($user_id, 'mvc_directory_optin', array('name', 'batch'));
        update_user_meta($user_id, 'mvc_consent_version', '1.0');
        update_user_meta($user_id, 'mvc_consent_at', current_time('mysql'));

        mvc_audit('account_pending', 'user', $user_id, array('role' => 'alumni_member', 'batch' => $batch));

        // Alert staff
        $emails = mvc_get_staff_notification_emails(false);
        if (!empty($emails)) {
            mvc_send_notification(
                $emails,
                sprintf('[Alumni Desk] New Alumni Registration Pending: %s', $name ?: $email),
                '<p>A new alumnus has registered through Form #821 and awaits verification:</p>
                 <p><strong>Name:</strong> ' . esc_html($name) . '<br>
                 <strong>Email:</strong> ' . esc_html($email) . '<br>
                 <strong>Batch Year:</strong> ' . esc_html($batch) . '</p>
                 <p><a href="' . esc_url(admin_url('admin.php?page=mvc-command-center&section=accounts&filter=pending')) . '" class="mvc-btn-link">Review in Accounts Desk &rarr;</a></p>',
                'account_pending'
            );
        }
    }
}

/**
 * Approve pending alumni account.
 *
 * @param int $user_id
 * @return true|WP_Error
 */
function mvc_approve_account($user_id) {
    $actor_id = get_current_user_id();
    if (!mvc_can_manage_user($actor_id, $user_id)) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to approve this account.', 'mvc-college'));
    }

    update_user_meta($user_id, 'mvc_account_status', 'active');
    mvc_audit('account_approved', 'user', $user_id);

    $user = get_userdata($user_id);
    do_action('mvc_account_activated', $user_id, $user);

    return true;
}

/**
 * Suspend account and terminate all active sessions.
 *
 * @param int $user_id
 * @return true|WP_Error
 */
function mvc_suspend_account($user_id) {
    $actor_id = get_current_user_id();
    if (!mvc_can_manage_user($actor_id, $user_id)) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to suspend this account.', 'mvc-college'));
    }

    update_user_meta($user_id, 'mvc_account_status', 'suspended');

    // Destroy all active sessions immediately
    mvc_destroy_user_sessions($user_id);

    mvc_audit('account_suspended', 'user', $user_id);
    return true;
}

/**
 * Reactivate suspended account.
 *
 * @param int $user_id
 * @return true|WP_Error
 */
function mvc_reactivate_account($user_id) {
    $actor_id = get_current_user_id();
    if (!mvc_can_manage_user($actor_id, $user_id)) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to reactivate this account.', 'mvc-college'));
    }

    update_user_meta($user_id, 'mvc_account_status', 'active');
    mvc_audit('account_reactivated', 'user', $user_id);
    return true;
}

/**
 * Reject pending account.
 *
 * @param int $user_id
 * @return true|WP_Error
 */
function mvc_reject_account($user_id) {
    $actor_id = get_current_user_id();
    if (!mvc_can_manage_user($actor_id, $user_id)) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to reject this account.', 'mvc-college'));
    }

    update_user_meta($user_id, 'mvc_account_status', 'rejected');
    mvc_destroy_user_sessions($user_id);

    mvc_audit('account_rejected', 'user', $user_id);

    $user = get_userdata($user_id);
    if ($user && !empty($user->user_email)) {
        mvc_send_notification(
            $user->user_email,
            '[M.V. College] Alumni Registration Update',
            '<p>Dear ' . esc_html($user->display_name) . ',</p>
             <p>Your registration could not be verified against the college graduation records at this time. Please contact the college administrative office for assistance.</p>',
            'account_rejected'
        );
    }

    return true;
}

/**
 * Daily cron to purge rejected alumni accounts older than 30 days.
 */
function mvc_purge_rejected_accounts() {
    $rejected_users = get_users(array(
        'role'       => 'alumni_member',
        'meta_key'   => 'mvc_account_status',
        'meta_value' => 'rejected',
    ));

    $cutoff = strtotime('-30 days');
    require_once ABSPATH . 'wp-admin/includes/user.php';

    foreach ($rejected_users as $u) {
        $registered = strtotime($u->user_registered);
        if ($registered < $cutoff) {
            wp_delete_user($u->ID);
            mvc_audit('account_purged', 'user', $u->ID, array('email' => $u->user_email));
        }
    }
}
add_action('mvc_daily', 'mvc_purge_rejected_accounts');
