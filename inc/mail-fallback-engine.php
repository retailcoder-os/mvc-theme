<?php
/**
 * RetailCoder StoreFront [RC:StoreFront] Universal Mail & Centralized SMTP Fallback Engine
 *
 * Provides a single centralized SMTP fallback configuration and dispatcher for the entire site.
 * Ensures:
 * 1. Centralized SMTP configuration (smtp.zoho.in:587) in ONE single place.
 * 2. Automatic global fallback on wp_mail_failed for all outgoing notifications to authorities.
 * 3. Automatic institutional acknowledgement & confirmation emails to the raiser (submitter)
 *    for all form submissions across all collegiate portals (Contact, Grievance, Alumni, NAAC, etc.).
 * 4. Reply-To dynamically routed to the official Administrator email.
 *
 * @package Astra_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Centralized Outgoing SMTP Configuration
 * Updating credentials here automatically updates OTP engine, authority notifications,
 * raiser acknowledgements, and command center dispatches across the entire system.
 */
function mvc_get_centralized_smtp_config() {
    $admin_email = get_option('admin_email');
    if (empty($admin_email) || !is_email($admin_email)) {
        $admin_email = function_exists('mvc_get_principal_email') ? mvc_get_principal_email() : 'mvcollegebuxar@gmail.com';
    }

    return array(
        'host'       => defined('MVC_SMTP_HOST') ? MVC_SMTP_HOST : 'smtp.zoho.in',
        'port'       => defined('MVC_SMTP_PORT') ? (int) MVC_SMTP_PORT : 587,
        'encryption' => 'tls', // STARTTLS
        'username'   => defined('MVC_SMTP_USER') ? MVC_SMTP_USER : 'notification@retailcoder.com',
        'password'   => defined('MVC_SMTP_PASS') ? MVC_SMTP_PASS : get_option('mvc_smtp_pass', ''),
        'from_email' => defined('MVC_SMTP_FROM_EMAIL') ? MVC_SMTP_FROM_EMAIL : 'notification@retailcoder.com',
        'from_name'  => defined('MVC_SMTP_FROM_NAME') ? MVC_SMTP_FROM_NAME : 'RC:Echo Via MV College',
        'reply_to'   => $admin_email,
        'reply_name' => 'M.V. College Administration',
    );
}

/**
 * 2. Universal Authenticated SMTP Dispatcher
 * Directly sends any email through the centralized Zoho SMTP gateway using PHPMailer.
 *
 * @param string|array $to Single email, comma-separated list, or array of recipients.
 * @param string $subject Email subject line.
 * @param string $message_html HTML email body.
 * @param array|string $headers Optional headers or list of headers.
 * @param string|null $reply_to Optional custom reply-to. Defaults to admin email.
 * @return bool True on success, false on failure.
 */
function mvc_dispatch_smtp_email($to, $subject, $message_html, $headers = array(), $reply_to = null) {
    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
    }

    $config = mvc_get_centralized_smtp_config();
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // SMTP Server Setup
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $config['port'];
        $mail->Timeout    = 15;
        $mail->CharSet    = 'UTF-8';

        // Sender
        $mail->setFrom($config['from_email'], $config['from_name']);

        // Reply-To (Always routes to Administrator email)
        $target_reply = (!empty($reply_to) && is_email($reply_to)) ? sanitize_email($reply_to) : $config['reply_to'];
        $mail->addReplyTo($target_reply, $config['reply_name']);

        // Parse Recipients ($to can be string, comma-separated, or array)
        $recipients = array();
        if (is_array($to)) {
            $recipients = $to;
        } else {
            $recipients = explode(',', (string) $to);
        }

        $valid_recipient_count = 0;
        foreach ($recipients as $recipient) {
            $cleaned = trim($recipient);
            // Handle "Name <email@domain.com>" format
            if (preg_match('/<([^>]+)>/', $cleaned, $matches)) {
                $cleaned = $matches[1];
            }
            if (is_email($cleaned)) {
                $mail->addAddress($cleaned);
                $valid_recipient_count++;
            }
        }

        if ($valid_recipient_count === 0) {
            error_log('[SMTP DISPATCH ERROR] No valid recipient addresses found in: ' . print_r($to, true));
            return false;
        }

        // Parse additional headers (CC, BCC) if provided
        if (!empty($headers)) {
            $header_lines = is_array($headers) ? $headers : explode("\n", (string) $headers);
            foreach ($header_lines as $line) {
                $line = trim($line);
                if (stripos($line, 'cc:') === 0) {
                    $cc = trim(substr($line, 3));
                    if (is_email($cc)) {
                        $mail->addCC($cc);
                    }
                } elseif (stripos($line, 'bcc:') === 0) {
                    $bcc = trim(substr($line, 4));
                    if (is_email($bcc)) {
                        $mail->addBCC($bcc);
                    }
                }
            }
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message_html;
        $mail->AltBody = strip_tags(str_replace(array('<br>', '<br/>', '<br />', '</p>'), "\n", $message_html));

        $mail->send();
        error_log(sprintf(
            '[SMTP DISPATCH SUCCESS] Dispatched to %d recipient(s) [%s] via %s:%d (Sender: %s, Reply-To: %s)',
            $valid_recipient_count,
            implode(', ', array_map('trim', (array) $recipients)),
            $config['host'],
            $config['port'],
            $config['from_name'],
            $target_reply
        ));
        return true;
    } catch (\Throwable $e) {
        error_log('[SMTP DISPATCH ERROR] Failed sending to ' . print_r($to, true) . ': ' . $e->getMessage());
        return false;
    }
}

/**
 * 3. Global wp_mail_failed Interceptor & Fallback
 * Catches ANY failed email across WordPress (WPForms notifications to authorities,
 * core password resets, system alerts) and automatically resends it through our
 * centralized Zoho SMTP fallback.
 */
add_action('wp_mail_failed', 'mvc_global_mail_fallback_handler', 10, 1);
function mvc_global_mail_fallback_handler($wp_error) {
    static $is_processing = false;
    if ($is_processing) {
        return; // Prevent recursion
    }

    if (!is_wp_error($wp_error)) {
        return;
    }

    $mail_data = $wp_error->get_error_data('wp_mail_failed');
    if (empty($mail_data) || empty($mail_data['to'])) {
        return;
    }

    $is_processing = true;
    error_log('[GLOBAL FALLBACK TRIGGERED] wp_mail failed: ' . $wp_error->get_error_message() . '. Rerouting through Zoho SMTP fallback...');

    $to          = $mail_data['to'];
    $subject     = !empty($mail_data['subject']) ? $mail_data['subject'] : 'Institutional Notification - M.V. College';
    $message     = !empty($mail_data['message']) ? $mail_data['message'] : '';
    $headers     = !empty($mail_data['headers']) ? $mail_data['headers'] : array();

    mvc_dispatch_smtp_email($to, $subject, $message, $headers);

    $is_processing = false;
}

/**
 * 4. Automatic Submitter Acknowledgement on All Form Submissions
 * When ANY form is submitted via WPForms, this automatically extracts the submitter's
 * name and email, generates an official reference number, and dispatches a stately
 * institutional confirmation email to the raiser with a full copy of their submission.
 */
add_action('wpforms_process_complete', 'mvc_send_form_acknowledgement_to_raiser', 10, 4);
function mvc_send_form_acknowledgement_to_raiser($fields, $entry, $form_data, $entry_id) {
    if (empty($fields) || empty($form_data)) {
        return;
    }

    // 1. Locate Submitter Email, Name, Phone, and Subject
    $raiser_email = '';
    $raiser_name  = '';
    $raiser_phone = '';
    $custom_subject = '';
    $custom_narrative = '';

    foreach ($fields as $field_id => $field) {
        $type  = $field['type'] ?? '';
        $label = strtolower($field['label'] ?? '');
        $val   = $field['value'] ?? '';

        // Email detection
        if (empty($raiser_email)) {
            if ($type === 'email' && is_email($val)) {
                $raiser_email = sanitize_email($val);
            } elseif (strpos($label, 'email') !== false && is_email($val)) {
                $raiser_email = sanitize_email($val);
            }
        }

        // Name detection
        if (empty($raiser_name)) {
            if ($type === 'name' && !empty($val)) {
                $raiser_name = sanitize_text_field(is_array($val) ? implode(' ', $val) : $val);
            } elseif (strpos($label, 'name') !== false && !empty($val) && !is_email($val)) {
                $raiser_name = sanitize_text_field(is_array($val) ? implode(' ', $val) : $val);
            }
        }

        // Phone detection
        if (empty($raiser_phone)) {
            if ((strpos($label, 'phone') !== false || strpos($label, 'mobile') !== false || strpos($label, 'contact') !== false) && !empty($val) && !is_email($val)) {
                $raiser_phone = sanitize_text_field(is_array($val) ? implode(' ', $val) : $val);
            }
        }

        // Subject / Title detection
        if (empty($custom_subject)) {
            if ((strpos($label, 'subject') !== false || strpos($label, 'title') !== false || strpos($label, 'summary') !== false) && !empty($val)) {
                $custom_subject = sanitize_text_field(is_array($val) ? implode(' ', $val) : $val);
            }
        }

        // Narrative / Description detection
        if (empty($custom_narrative)) {
            if ((strpos($label, 'narrative') !== false || strpos($label, 'description') !== false || strpos($label, 'message') !== false || strpos($label, 'details') !== false || strpos($label, 'representation') !== false) && !empty($val)) {
                $custom_narrative = sanitize_textarea_field(is_array($val) ? implode("\n", $val) : $val);
            }
        }
    }

    if (empty($raiser_email) || !is_email($raiser_email)) {
        error_log('[ACK SKIP] No valid submitter email found in form submission #' . ($form_data['id'] ?? 'unknown'));
        return;
    }

    if (empty($raiser_name)) {
        $raiser_name = 'Respected Citizen / Scholar';
    }

    // 2. Build Submission Reference & Metadata
    $form_title   = $form_data['settings']['form_title'] ?? 'Collegiate Portal Submission';
    $form_id      = (int) ($form_data['id'] ?? 0);
    $is_grievance = (strpos(strtolower($form_title), 'grievance') !== false || $form_id === 823);
    $ref_prefix   = $is_grievance ? 'GRV' : 'ENQ';

    $tracking_ref = function_exists('mvc_generate_grievance_ref') ? mvc_generate_grievance_ref($ref_prefix) : sprintf('MVC/%s/%s/%04d', $ref_prefix, date('Y'), (int) $entry_id ?: wp_rand(1000, 9999));
    if (is_wp_error($tracking_ref) || empty($tracking_ref)) {
        $tracking_ref = sprintf('MVC/%s/%s/%04d', $ref_prefix, date('Y'), (int) $entry_id ?: wp_rand(1000, 9999));
    }

    $timestamp    = current_time('d M Y, h:i A') . ' IST';
    $college_name = function_exists('mvc_get_global_setting') ? mvc_get_global_setting('college_name', 'Maharshi Vishwamitra Mahavidyalaya, Buxar') : 'Maharshi Vishwamitra Mahavidyalaya, Buxar';
    $admin_email  = get_option('admin_email') ?: 'mvcollegebuxar@gmail.com';
    $helpline     = '06183-796317';
    $sla_days     = 15;
    $due_date     = gmdate('Y-m-d H:i:s', strtotime("+{$sla_days} days"));

    // 3. Detect Department Affiliation from Fields
    $matched_dept_term_id = 0;
    $matched_dept_name    = '';
    $all_dept_terms = get_terms(array('taxonomy' => 'dept_link', 'hide_empty' => false));
    if (!empty($all_dept_terms) && !is_wp_error($all_dept_terms)) {
        foreach ($fields as $field) {
            $flabel = strtolower($field['label'] ?? '');
            $fval_raw = is_array($field['value'] ?? '') ? implode(' ', $field['value']) : ($field['value'] ?? '');
            $fval = strtolower($fval_raw);

            foreach ($all_dept_terms as $dt) {
                $dt_slug = strtolower($dt->slug);
                $dt_name = strtolower($dt->name);

                if (strpos($fval, $dt_slug) !== false || strpos($fval, $dt_name) !== false) {
                    $matched_dept_term_id = $dt->term_id;
                    $matched_dept_name    = $dt->name;
                    break 2;
                }
                // Vocational / Department aliases
                if (($dt_slug === 'computer-applications' || $dt_slug === 'bca') && strpos($fval, 'bca') !== false) {
                    $matched_dept_term_id = $dt->term_id;
                    $matched_dept_name    = $dt->name;
                    break 2;
                }
                if (($dt_slug === 'business-management' || $dt_slug === 'bba') && strpos($fval, 'bba') !== false) {
                    $matched_dept_term_id = $dt->term_id;
                    $matched_dept_name    = $dt->name;
                    break 2;
                }
            }
        }
    }

    // 4. Create Permanent Grievance / Enquiry Record in Database
    $post_title = !empty($custom_subject) ? $custom_subject : sprintf('%s from %s', $form_title, $raiser_name);
    if (empty($custom_narrative)) {
        $summary_parts = array();
        foreach ($fields as $field) {
            $lbl = $field['label'] ?? 'Field';
            $val = is_array($field['value'] ?? '') ? implode(', ', $field['value']) : ($field['value'] ?? '');
            if (!empty($val)) {
                $summary_parts[] = sprintf("%s: %s", $lbl, $val);
            }
        }
        $custom_narrative = implode("\n", $summary_parts);
    }

    $existing_user = get_user_by('email', $raiser_email);
    $author_id = $existing_user ? $existing_user->ID : 1;

    $post_id = wp_insert_post(array(
        'post_type'    => 'grievance',
        'post_title'   => $post_title,
        'post_content' => $custom_narrative,
        'post_status'  => 'publish',
        'post_author'  => $author_id,
    ), true);

    if (!is_wp_error($post_id) && $post_id) {
        update_post_meta($post_id, '_mvc_grv_ref', $tracking_ref);
        update_post_meta($post_id, '_mvc_grv_submitter_name', $raiser_name);
        update_post_meta($post_id, '_mvc_grv_submitter_email', $raiser_email);
        update_post_meta($post_id, '_mvc_grv_submitter_phone', $raiser_phone);
        update_post_meta($post_id, '_mvc_grv_form_id', $form_id);
        update_post_meta($post_id, '_mvc_grv_form_title', $form_title);
        update_post_meta($post_id, '_mvc_grv_category', $is_grievance ? 'Grievance' : ($matched_dept_name ? $matched_dept_name : 'Inquiry'));
        update_post_meta($post_id, '_mvc_grv_due', $due_date);
        update_post_meta($post_id, '_mvc_grv_fields_data', wp_json_encode($fields));
        update_post_meta($post_id, '_mvc_grv_source', 'wpforms');

        // Status taxonomy: logged
        wp_set_object_terms($post_id, 'logged', 'grievance-status', false);

        // Assign department if matched
        if ($matched_dept_term_id) {
            wp_set_object_terms($post_id, array($matched_dept_term_id), 'dept_link', false);
            update_post_meta($post_id, '_mvc_grv_department', $matched_dept_name);
        }

        // Insert initial logged event into timeline
        if (function_exists('mvc_add_grievance_event')) {
            mvc_add_grievance_event(
                $post_id,
                0,
                'logged',
                'public',
                sprintf('Official representation registered via "%s" by %s (%s). Reference: %s.', $form_title, $raiser_name, $raiser_email, $tracking_ref)
            );
        }
    }

    $tracking_url = home_url('/track/?ref=' . rawurlencode($tracking_ref) . '&email=' . rawurlencode($raiser_email));

    // 3. Build Submission Details Table
    $rows_html = '';
    foreach ($fields as $field) {
        $flabel = esc_html($field['label'] ?? 'Field');
        $fval   = $field['value'] ?? '';
        if (is_array($fval)) {
            $fval = implode(', ', array_filter($fval));
        }
        $fval = nl2br(esc_html($fval));

        if (!empty($fval)) {
            $rows_html .= '
            <tr>
                <td style="padding:10px 14px; border-bottom:1px solid #E2E8F0; font-weight:700; color:#002147; width:35%; vertical-align:top; font-size:13px; background:#F8FAFC;">' . $flabel . '</td>
                <td style="padding:10px 14px; border-bottom:1px solid #E2E8F0; color:#334155; font-size:13px; vertical-align:top;">' . $fval . '</td>
            </tr>';
        }
    }

    // 4. Construct Stately Institutional Acknowledgement HTML
    $subject = sprintf('[%s] Official Submission Registered — Ref: %s', 'M.V. College, Buxar', $tracking_ref);

    $html_body = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>' . esc_html($subject) . '</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 24px; color: #1E293B; line-height: 1.6; }
            .card { max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,33,71,0.08); border: 1px solid #E2E8F0; }
            .header { background: linear-gradient(135deg, #002147 0%, #800000 100%); padding: 30px 24px; text-align: center; color: #ffffff; }
            .header h1 { margin: 0; font-size: 21px; font-weight: 800; letter-spacing: 0.5px; }
            .header p { margin: 6px 0 0; font-size: 13px; color: #D4AF37; }
            .content { padding: 32px 28px; }
            .badge-registered { display: inline-block; background: #DCFCE7; color: #166534; font-weight: 800; font-size: 12px; padding: 5px 14px; border-radius: 999px; margin-bottom: 18px; border: 1px solid #86EFAC; text-transform: uppercase; letter-spacing: 0.5px; }
            .ref-box { background: #FAF9F6; border: 2px dashed #D4AF37; border-radius: 8px; padding: 14px 20px; margin: 18px 0; text-align: center; }
            .ref-num { font-size: 18px; font-weight: 800; color: #002147; font-family: "Courier New", Courier, monospace; letter-spacing: 1px; }
            .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; }
            .notice-box { background: #EFF6FF; border-left: 4px solid #3B82F6; padding: 14px 18px; border-radius: 0 8px 8px 0; font-size: 13px; color: #1E40AF; margin: 20px 0; line-height: 1.55; }
            .footer { padding: 20px 24px; background: #F8FAFC; text-align: center; font-size: 12px; color: #94A3B8; border-top: 1px solid #E2E8F0; line-height: 1.6; }
        </style>
    </head>
    <body>
        <div class="card">
            <div class="header">
                <h1>🏛️ ' . esc_html($college_name) . '</h1>
                <p>Constituent Unit of Veer Kunwar Singh University, Ara &bull; Estd. 1958</p>
            </div>
            <div class="content">
                <span class="badge-registered">✓ Submission Officially Registered</span>
                <h2 style="margin:0 0 12px 0; color:#002147; font-size:19px;">Official Acknowledgement &amp; Reference Receipt</h2>
                <p style="margin:0 0 14px 0; font-size:14.5px; color:#334155;">
                    Dear <strong>' . esc_html($raiser_name) . '</strong>,
                </p>
                <p style="margin:0 0 16px 0; font-size:14px; color:#475569;">
                    Thank you for contacting Maharshi Vishwamitra Mahavidyalaya, Buxar. Your official submission via the <strong>' . esc_html($form_title) . '</strong> has been authenticated and entered into our institutional records.
                </p>

                <div class="ref-box">
                    <div style="font-size:12px; color:#64748B; font-weight:700; text-transform:uppercase; margin-bottom:4px;">Official Tracking Reference Number</div>
                    <div class="ref-num">' . esc_html($tracking_ref) . '</div>
                    <div style="font-size:11.5px; color:#64748B; margin-top:4px;">Timestamp: ' . esc_html($timestamp) . '</div>
                </div>

                <div style="text-align:center; margin:16px 0 20px;">
                    <a href="' . esc_url($tracking_url) . '" style="display:inline-block; background:linear-gradient(135deg, #002147 0%, #800000 100%); color:#ffffff !important; font-weight:800; font-size:13.5px; text-decoration:none; padding:12px 24px; border-radius:8px; box-shadow:0 3px 12px rgba(0,33,71,0.2);">
                        🔎 Track Live Status &amp; Authority Responses &rarr;
                    </a>
                </div>

                <div class="notice-box">
                    <strong>📋 What Happens Next?</strong><br>
                    Your submission has been dispatched directly to the concerned departmental authorities and institutional administration for review and necessary action. You will receive further official correspondence regarding this matter at this email address.
                </div>

                <h3 style="font-size:15px; color:#002147; margin:24px 0 10px 0; border-bottom:2px solid #D4AF37; padding-bottom:6px;">Summary of Submitted Representation</h3>
                <table class="details-table">
                    <tbody>
                        ' . $rows_html . '
                    </tbody>
                </table>

                <p style="margin:20px 0 0 0; font-size:13px; color:#64748B;">
                    If you need to provide additional documentation or have urgent queries regarding this matter, please reply directly to this email or quote your reference number <strong>' . esc_html($tracking_ref) . '</strong> when calling the campus central helpline at <strong>' . esc_html($helpline) . '</strong>.
                </p>
            </div>
            <div class="footer">
                <strong>Maharshi Vishwamitra Mahavidyalaya, Buxar</strong><br>
                Official Institutional Portal Notification &bull; Administrative Operations Secretariat<br>
                Official Inquiries: <a href="mailto:' . esc_attr($admin_email) . '" style="color:#002147; font-weight:700; text-decoration:none;">' . esc_html($admin_email) . '</a> | Helpline: ' . esc_html($helpline) . '<br>
                &copy; ' . date('Y') . ' M.V. College, Buxar. All Rights Reserved.
            </div>
        </div>
    </body>
    </html>';

    // 5. Dispatch Acknowledgement via Centralized SMTP Fallback (with Reply-To set to Admin Email)
    $dispatched = mvc_dispatch_smtp_email($raiser_email, $subject, $html_body, array(), $admin_email);

    if ($dispatched) {
        error_log(sprintf(
            '[ACK DISPATCHED] Successfully sent official acknowledgement for form #%d (Ref: %s) to %s',
            $form_id,
            $tracking_ref,
            $raiser_email
        ));
    } else {
        error_log(sprintf(
            '[ACK DISPATCH FAILED] Could not dispatch acknowledgement for form #%d (Ref: %s) to %s',
            $form_id,
            $tracking_ref,
            $raiser_email
        ));
    }
}

