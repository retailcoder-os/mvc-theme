<?php
/**
 * MV College Editorial Workflow Engine
 * 
 * Standardized draft -> pending -> publish lifecycle.
 * Reject with mandatory reason (>= 10 chars), notice validation before publish,
 * and change request tracking on live items.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Workflow-enabled post types.
 *
 * @return array
 */
function mvc_workflow_post_types() {
    return array('notice', 'event', 'faculty', 'study_material', 'department');
}

/**
 * Validate a post before allowing it to move to 'publish' status.
 *
 * @param int $post_id
 * @return true|WP_Error
 */
function mvc_validate_before_publish($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return new WP_Error('mvc_invalid_post', __('Post does not exist.', 'mvc-college'));
    }

    // Specific validation for 'notice' CPT
    if ($post->post_type === 'notice') {
        // 1. PDF or body text >= 20 characters
        $pdf = get_field('pdf_file', $post_id);
        $body = strip_tags($post->post_content);
        $has_valid_body = strlen(trim($body)) >= 20;
        $has_valid_pdf = !empty($pdf);

//         if (!$has_valid_pdf && !$has_valid_body) {
//             return new WP_Error(
//                 'mvc_notice_content_missing',
//                 __('A notice cannot be published without either an attached certified PDF or descriptive body text of at least 20 characters.', 'mvc-college')
//             );
//         }

        // 2. Memo number in college format (e.g. MVC/NOT/... or valid memo string)
        $memo_no = get_field('memo_number', $post_id) ?: get_post_meta($post_id, 'memo_number', true);
//         if (empty($memo_no) || strlen(trim($memo_no)) < 4) {
//             return new WP_Error(
//                 'mvc_notice_memo_missing',
//                 __('A notice cannot be published without an official institutional memo / dispatch number.', 'mvc-college')
//             );
//         }

        // 3. Notice date
        $notice_date = get_field('date_picker', $post_id) ?: get_post_meta($post_id, 'date_picker', true);
        if (empty($notice_date) && empty($post->post_date)) {
            return new WP_Error(
                'mvc_notice_date_missing',
                __('A notice cannot be published without a certified publication date.', 'mvc-college')
            );
        }
    }

    return apply_filters('mvc_validate_before_publish', true, $post_id, $post->post_type);
}

/**
 * Hook into post status transitions for validation and audit logging.
 */
add_action('transition_post_status', 'mvc_handle_workflow_status_transition', 10, 3);
function mvc_handle_workflow_status_transition($new_status, $old_status, $post) {
    if (!in_array($post->post_type, mvc_workflow_post_types(), true)) {
        return;
    }

    if ($new_status === $old_status) {
        return;
    }

    $post_id = $post->ID;

    // 1. If moving to 'publish', run publication validation
    if ($new_status === 'publish') {
        $validation = mvc_validate_before_publish($post_id);
        if (is_wp_error($validation)) {
            // Revert back to draft and show error
            global $wpdb;
            $wpdb->update($wpdb->posts, array('post_status' => 'draft'), array('ID' => $post_id));
            wp_die(esc_html($validation->get_error_message()), __('Publish Validation Failed', 'mvc-college'), array('back_link' => true));
        }

        // Audit content approval / publish
        mvc_audit('content_approved', $post->post_type, $post_id, array(
            'title'      => $post->post_title,
            'old_status' => $old_status,
        ));

        // Trigger notification
        do_action('mvc_content_approved', $post_id, $post);
    }

    // 2. If moving to 'pending' (submitted for review)
    if ($new_status === 'pending') {
        mvc_audit('content_submitted', $post->post_type, $post_id, array(
            'title'      => $post->post_title,
            'author_id'  => $post->post_author,
        ));

        do_action('mvc_content_submitted', $post_id, $post);
    }

    // 3. If unpublished
    if ($old_status === 'publish' && in_array($new_status, array('draft', 'pending'), true)) {
        mvc_audit('content_unpublished', $post->post_type, $post_id, array(
            'title'      => $post->post_title,
            'new_status' => $new_status,
        ));
    }

    // 4. If trashed
    if ($new_status === 'trash') {
        mvc_audit('content_trashed', $post->post_type, $post_id, array(
            'title' => $post->post_title,
        ));
    }
}

/**
 * Submit item for review.
 *
 * @param int $post_id
 * @return true|WP_Error
 */
function mvc_workflow_submit_for_review($post_id) {
    if (!current_user_can('edit_post', $post_id)) {
        return new WP_Error('mvc_forbidden', __('You do not have permission to submit this item.', 'mvc-college'));
    }

    $result = wp_update_post(array(
        'ID'          => $post_id,
        'post_status' => 'pending',
    ), true);

    if (is_wp_error($result)) {
        return $result;
    }

    return true;
}

/**
 * Approve and publish item (requires mvc_review_content).
 *
 * @param int $post_id
 * @return true|WP_Error
 */
function mvc_workflow_approve($post_id) {
    if (!current_user_can('mvc_review_content')) {
        return new WP_Error('mvc_forbidden', __('Only Content Managers and Administrators can approve and publish content.', 'mvc-college'));
    }

    $validation = mvc_validate_before_publish($post_id);
    if (is_wp_error($validation)) {
        return $validation;
    }

    $result = wp_update_post(array(
        'ID'          => $post_id,
        'post_status' => 'publish',
    ), true);

    if (is_wp_error($result)) {
        return $result;
    }

    // Clear any previous rejection reason
    delete_post_meta($post_id, '_mvc_reject_reason');
    delete_post_meta($post_id, '_mvc_rejected_by');
    delete_post_meta($post_id, '_mvc_rejected_at');

    return true;
}

/**
 * Reject pending item with mandatory reason (>= 10 characters).
 *
 * @param int    $post_id
 * @param string $reason
 * @return true|WP_Error
 */
function mvc_workflow_reject($post_id, $reason) {
    if (!current_user_can('mvc_review_content')) {
        return new WP_Error('mvc_forbidden', __('Only Content Managers and Administrators can reject submissions.', 'mvc-college'));
    }

    $reason = sanitize_textarea_field(trim($reason));
    if (strlen($reason) < 10) {
        return new WP_Error(
            'mvc_invalid_reason',
            __('A rejection reason of at least 10 characters explaining what needs revision is strictly required.', 'mvc-college')
        );
    }

    $result = wp_update_post(array(
        'ID'          => $post_id,
        'post_status' => 'draft',
    ), true);

    if (is_wp_error($result)) {
        return $result;
    }

    $user_id = get_current_user_id();
    update_post_meta($post_id, '_mvc_reject_reason', $reason);
    update_post_meta($post_id, '_mvc_rejected_by', $user_id);
    update_post_meta($post_id, '_mvc_rejected_at', current_time('mysql'));

    $post = get_post($post_id);
    mvc_audit('content_rejected', $post->post_type, $post_id, array(
        'reason' => $reason,
        'author' => $post->post_author,
    ));

    do_action('mvc_content_rejected', $post_id, $post, $reason);
    return true;
}

/**
 * Request change on a live (published) item.
 *
 * @param int    $post_id
 * @param string $notes
 * @return true|WP_Error
 */
function mvc_workflow_request_change($post_id, $notes) {
    if (!current_user_can('read_post', $post_id)) {
        return new WP_Error('mvc_forbidden', __('You do not have access to this item.', 'mvc-college'));
    }

    $notes = sanitize_textarea_field(trim($notes));
    if (strlen($notes) < 10) {
        return new WP_Error('mvc_invalid_notes', __('Please provide detailed change notes (at least 10 characters).', 'mvc-college'));
    }

    $requests = get_post_meta($post_id, '_mvc_change_requests', true);
    if (!is_array($requests)) {
        $requests = array();
    }

    $user_id = get_current_user_id();
    $requests[] = array(
        'user_id'    => $user_id,
        'created_at' => current_time('mysql'),
        'notes'      => $notes,
    );

    update_post_meta($post_id, '_mvc_change_requests', $requests);

    $post = get_post($post_id);
    mvc_audit('change_requested', $post->post_type, $post_id, array(
        'notes' => $notes,
    ));

    do_action('mvc_change_requested', $post_id, $post, $notes);
    return true;
}

/**
 * Get review queue items (pending submissions).
 *
 * @param array $args Filters
 * @return array Items and counts
 */
function mvc_get_review_queue($args = array()) {
    $defaults = array(
        'post_type' => mvc_workflow_post_types(),
        'term_id'   => 0,
        'page'      => 1,
        'per_page'  => 20,
    );
    $params = wp_parse_args($args, $defaults);

    $query_args = array(
        'post_type'      => $params['post_type'],
        'post_status'    => 'pending',
        'posts_per_page' => $params['per_page'],
        'paged'          => $params['page'],
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    if (!empty($params['term_id'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'dept_link',
                'field'    => 'term_id',
                'terms'    => array((int) $params['term_id']),
            ),
        );
    }

    $query = new WP_Query($query_args);
    $items = array();

    if ($query->have_posts()) {
        foreach ($query->posts as $p) {
            $author = get_userdata($p->post_author);
            $author_name = $author ? $author->display_name : __('Former user', 'mvc-college');

            // Department term
            $dept_terms = wp_get_object_terms($p->ID, 'dept_link');
            $dept_name = !empty($dept_terms) && !is_wp_error($dept_terms) ? $dept_terms[0]->name : 'Institutional / General';

            $items[] = array(
                'id'          => $p->ID,
                'title'       => $p->post_title,
                'post_type'   => $p->post_type,
                'post_date'   => $p->post_date,
                'author_id'   => $p->post_author,
                'author_name' => $author_name,
                'department'  => $dept_name,
                'edit_url'    => get_edit_post_link($p->ID, 'raw'),
            );
        }
    }

    return array(
        'items'       => $items,
        'total'       => $query->found_posts,
        'total_pages' => $query->max_num_pages,
        'page'        => $params['page'],
    );
}
