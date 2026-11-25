<?php
/**
 * Custom Post Types & ACF Field Groups (100% ACF Free Compatible)
 * No Repeater or Gallery fields used - 100% Free ACF compatible!
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ========================================================
   1. REGISTER CPTs: 'slider' & 'leadership'
   ======================================================== */
function mvc_register_free_cpts() {
    // 1. Hero Slides CPT
    register_post_type('slider', array(
        'labels' => array(
            'name'               => __('Hero Slides', 'mvc-child'),
            'singular_name'      => __('Hero Slide', 'mvc-child'),
            'add_new'            => __('Add New Slide', 'mvc-child'),
            'add_new_item'       => __('Add New Hero Slide', 'mvc-child'),
            'edit_item'          => __('Edit Hero Slide', 'mvc-child'),
            'all_items'          => __('All Hero Slides', 'mvc-child'),
            'menu_name'          => __('Hero Slides', 'mvc-child')
        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-images-alt2',
        'menu_position'      => 20,
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'has_archive'        => false,
        'rewrite'            => false
    ));

    // 2. Leadership / Administration CPT
    register_post_type('leadership', array(
        'labels' => array(
            'name'               => __('Leadership', 'mvc-child'),
            'singular_name'      => __('Leader', 'mvc-child'),
            'add_new'            => __('Add New Leader', 'mvc-child'),
            'add_new_item'       => __('Add New Leader', 'mvc-child'),
            'edit_item'          => __('Edit Leader', 'mvc-child'),
            'all_items'          => __('All Leadership', 'mvc-child'),
            'menu_name'          => __('Leadership', 'mvc-child')
        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-businessman',
        'menu_position'      => 21,
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'has_archive'        => false,
        'rewrite'            => false
    ));

    // 3. Academic Milestones CPT (Configurable Timeline Milestones)
    register_post_type('calendar_milestone', array(
        'labels' => array(
            'name'               => __('Academic Milestones', 'mvc-child'),
            'singular_name'      => __('Academic Milestone', 'mvc-child'),
            'add_new'            => __('Add New Milestone', 'mvc-child'),
            'add_new_item'       => __('Add New Academic Milestone', 'mvc-child'),
            'edit_item'          => __('Edit Academic Milestone', 'mvc-child'),
            'all_items'          => __('All Milestones', 'mvc-child'),
            'menu_name'          => __('Academic Milestones', 'mvc-child')
        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'menu_position'      => 22,
        'supports'           => array('title', 'editor', 'page-attributes'),
        'has_archive'        => false,
        'rewrite'            => false
    ));

    // 4. Academic Gazettes CPT (Configurable Gazette Documents)
    register_post_type('calendar_gazette', array(
        'labels' => array(
            'name'               => __('Academic Gazettes', 'mvc-child'),
            'singular_name'      => __('Academic Gazette', 'mvc-child'),
            'add_new'            => __('Add New Gazette', 'mvc-child'),
            'add_new_item'       => __('Add New Academic Gazette', 'mvc-child'),
            'edit_item'          => __('Edit Academic Gazette', 'mvc-child'),
            'all_items'          => __('All Gazettes', 'mvc-child'),
            'menu_name'          => __('Academic Gazettes', 'mvc-child')
        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-media-document',
        'menu_position'      => 23,
        'supports'           => array('title', 'editor', 'page-attributes'),
        'has_archive'        => false,
        'rewrite'            => false
    ));

    // 5. Non-Teaching Staff CPT (Clerical, Technical & Support Cadres)
    register_post_type('staff_member', array(
        'labels' => array(
            'name'               => __('Non-Teaching Staff', 'mvc-child'),
            'singular_name'      => __('Staff Member', 'mvc-child'),
            'add_new'            => __('Add Staff Member', 'mvc-child'),
            'add_new_item'       => __('Add New Staff Member', 'mvc-child'),
            'edit_item'          => __('Edit Staff Member', 'mvc-child'),
            'all_items'          => __('All Staff Members', 'mvc-child'),
            'menu_name'          => __('Non-Teaching Staff', 'mvc-child')
        ),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 24,
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'has_archive'        => false,
        'rewrite'            => false
    ));

    // Register Staff Cadre taxonomy
    register_taxonomy('staff_cadre', 'staff_member', array(
        'labels' => array(
            'name'          => __('Staff Cadres', 'mvc-child'),
            'singular_name' => __('Staff Cadre', 'mvc-child'),
            'menu_name'     => __('Staff Cadres', 'mvc-child')
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => false
    ));
}
add_action('init', 'mvc_register_free_cpts');

/* ========================================================
   2. REGISTER ACF FREE FIELD GROUPS (Local JSON/PHP API)
   ======================================================== */
function mvc_register_free_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // A. Hero Slide Fields (on CPT: 'slider')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_slider',
        'title'  => 'Hero Slide Details',
        'fields' => array(
            array(
                'key'          => 'field_mvc_slide_tagline',
                'label'        => 'Tagline / Small Badge',
                'name'         => 'slide_tagline',
                'type'         => 'text',
                'instructions' => 'e.g., Welcome To Siddhashram, Academic Excellence',
            ),
            array(
                'key'          => 'field_mvc_slide_desc',
                'label'        => 'Description / Subtitle',
                'name'         => 'slide_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'instructions' => 'Short 1-2 sentence description over the banner image',
            ),
            array(
                'key'          => 'field_mvc_slide_btn_text',
                'label'        => 'Button Text',
                'name'         => 'button_text',
                'type'         => 'text',
                'default_value'=> 'Explore Campus',
            ),
            array(
                'key'          => 'field_mvc_slide_btn_url',
                'label'        => 'Button URL',
                'name'         => 'button_url',
                'type'         => 'url',
                'default_value'=> '/glance',
            ),
            array(
                'key'          => 'field_mvc_slide_image',
                'label'        => 'Custom Slide Image',
                'name'         => 'slide_image',
                'type'         => 'image',
                'instructions' => 'Upload 1920x600 banner image (or use Featured Image)',
                'return_format'=> 'array',
            )
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'slider',
                ),
            ),
        ),
    ));

    // B. Leadership Fields (on CPT: 'leadership')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_leadership',
        'title'  => 'Leader Details',
        'fields' => array(
            array(
                'key'          => 'field_mvc_lead_role',
                'label'        => 'Designation / Role',
                'name'         => 'designation_role',
                'type'         => 'text',
                'instructions' => 'e.g., Chancellor / Governor, Vice Chancellor, Principal',
                'required'     => 1,
            ),
            array(
                'key'          => 'field_mvc_lead_photo',
                'label'        => 'Portrait Photo',
                'name'         => 'profile_photo',
                'type'         => 'image',
                'instructions' => 'Square/circular portrait photo (or use Featured Image)',
                'return_format'=> 'array',
            )
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'leadership',
                ),
            ),
        ),
    ));

    // C. Committee Fields (on CPT: 'committee')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_committee',
        'title'  => 'Committee Details (ACF Free Compatible)',
        'fields' => array(
            array(
                'key'          => 'field_mvc_comm_hindi_title',
                'label'        => 'Hindi Title / हिंदी नाम',
                'name'         => 'committee_hindi_title',
                'type'         => 'text',
                'instructions' => 'Official Hindi translation of committee name',
            ),
            array(
                'key'          => 'field_mvc_comm_category',
                'label'        => 'Committee Classification',
                'name'         => 'committee_category',
                'type'         => 'select',
                'choices'      => array(
                    'academic'       => 'Academic & Quality Governance (IQAC, BOS, Exam)',
                    'welfare'        => 'Student Welfare & Statutory Redressal (Anti-Ragging, Grievance, ICC)',
                    'extension'      => 'Extension, Youth & Community (NSS, NCC, Sports, ELC)',
                    'support'        => 'Institutional Support & Outreach (Alumni, Library, Media, Career)'
                ),
                'default_value' => 'academic',
                'instructions' => 'Category classification for dynamic frontend filtering',
            ),
            array(
                'key'          => 'field_mvc_comm_head',
                'label'        => 'Convener / Committee Head',
                'name'         => 'committee_head',
                'type'         => 'text',
                'instructions' => 'Name & designation of convener / chairperson',
            ),
            array(
                'key'          => 'field_mvc_comm_email',
                'label'        => 'Official Committee Email',
                'name'         => 'committee_email',
                'type'         => 'email',
                'instructions' => 'Official email address for representations and communications',
            ),
            array(
                'key'          => 'field_mvc_comm_desc',
                'label'        => 'About / Statutory Objectives',
                'name'         => 'committee_description',
                'type'         => 'wysiwyg',
            ),
            array(
                'key'          => 'field_mvc_comm_members',
                'label'        => 'Members List / Structure',
                'name'         => 'members_details',
                'type'         => 'wysiwyg',
                'instructions' => 'Table or bullet list of committee members and roles',
            ),
            array(
                'key'          => 'field_mvc_comm_pdf',
                'label'        => 'Statutory Notification / Order PDF',
                'name'         => 'notification_pdf',
                'type'         => 'file',
                'return_format'=> 'array',
            ),
            array(
                'key'          => 'field_mvc_comm_minutes_pdf',
                'label'        => 'Meeting Minutes & Resolutions PDF',
                'name'         => 'meeting_minutes_pdf',
                'type'         => 'file',
                'return_format'=> 'array',
            ),
            array(
                'key'          => 'field_mvc_comm_atr_pdf',
                'label'        => 'Action Taken Report (ATR) PDF',
                'name'         => 'atr_pdf',
                'type'         => 'file',
                'return_format'=> 'array',
            )
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'committee',
                ),
            ),
        ),
    ));

    // D. Gallery Sub-photos Fields (on CPT: 'gallery-cat')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_gallery_cat',
        'title'  => 'Gallery Category Photos (ACF Free Slots)',
        'fields' => array(
            array(
                'key'          => 'field_mvc_gal_desc',
                'label'        => 'Event Description',
                'name'         => 'gallery_description',
                'type'         => 'textarea',
                'rows'         => 2,
            ),
            array(
                'key'          => 'field_mvc_gal_date',
                'label'        => 'Event Date',
                'name'         => 'gallery_event_date',
                'type'         => 'date_picker',
            ),
            array(
                'key'          => 'field_mvc_gal_img_1',
                'label'        => 'Sub-Gallery Photo 1',
                'name'         => 'sub_photo_1',
                'type'         => 'image',
                'return_format'=> 'array',
            ),
            array(
                'key'          => 'field_mvc_gal_img_2',
                'label'        => 'Sub-Gallery Photo 2',
                'name'         => 'sub_photo_2',
                'type'         => 'image',
                'return_format'=> 'array',
            ),
            array(
                'key'          => 'field_mvc_gal_img_3',
                'label'        => 'Sub-Gallery Photo 3',
                'name'         => 'sub_photo_3',
                'type'         => 'image',
                'return_format'=> 'array',
            ),
            array(
                'key'          => 'field_mvc_gal_img_4',
                'label'        => 'Sub-Gallery Photo 4',
                'name'         => 'sub_photo_4',
                'type'         => 'image',
                'return_format'=> 'array',
            )
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'gallery-cat',
                ),
            ),
        ),
    ));

    // E. Video Details (CPT: 'video') - 100% Admin Editable
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_video_admin',
        'title'  => 'YouTube Video Details',
        'fields' => array(
            array(
                'key'          => 'field_mvc_video_yt_url',
                'label'        => 'YouTube Video URL',
                'name'         => 'youtube_url',
                'type'         => 'url',
                'instructions' => 'Paste YouTube URL (e.g. https://www.youtube.com/watch?v=l0HpVZ33c_g or https://youtu.be/kFZ6vA0DiRc from MV College Channel)',
                'required'     => 1,
            ),
            array(
                'key'          => 'field_mvc_video_category',
                'label'        => 'Category / Event Type',
                'name'         => 'video_category',
                'type'         => 'text',
                'default_value'=> 'Campus Event',
                'instructions' => 'e.g. Sports & Athletics, Convocation, Cultural Fest, Academic Seminar',
            ),
            array(
                'key'          => 'field_mvc_video_is_featured',
                'label'        => 'Feature in Main Cinema Stage',
                'name'         => 'featured_video',
                'type'         => 'true_false',
                'ui'           => 1,
                'instructions' => 'If checked, this video will be featured in the primary theater screen on the homepage',
            )
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'video',
                ),
            ),
        ),
    ));

    // F. Testimonial Details (CPT: 'testimonial') - 100% Admin Editable
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_testi_admin',
        'title'  => 'Student Testimonial Details',
        'fields' => array(
            array(
                'key'          => 'field_mvc_testi_name',
                'label'        => 'Student / Scholar Full Name',
                'name'         => 'student_name',
                'type'         => 'text',
                'required'     => 1,
            ),
            array(
                'key'          => 'field_mvc_testi_course',
                'label'        => 'Department / Degree Program',
                'name'         => 'course',
                'type'         => 'text',
                'instructions' => 'e.g. M.A. Political Science, B.Sc. Chemistry, M.A. Hindi',
                'required'     => 1,
            ),
            array(
                'key'          => 'field_mvc_testi_quote',
                'label'        => 'Student Quote / Reflection',
                'name'         => 'quote',
                'type'         => 'textarea',
                'rows'         => 4,
                'required'     => 1,
            ),
            array(
                'key'          => 'field_mvc_testi_photo',
                'label'        => 'Student Portrait Photo',
                'name'         => 'photo',
                'type'         => 'image',
                'return_format'=> 'array',
                'instructions' => 'Square portrait photo of scholar (or use Featured Image)',
            ),
            array(
                'key'          => 'field_mvc_testi_stars',
                'label'        => 'Star Rating',
                'name'         => 'rating_stars',
                'type'         => 'number',
                'default_value'=> 5,
                'min'          => 1,
                'max'          => 5,
            )
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'testimonial',
                ),
            ),
        ),
    ));

    // G. Academic Milestones Fields (on CPT: 'calendar_milestone')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_milestone',
        'title'  => 'Academic Milestone Details',
        'fields' => array(
            array(
                'key'           => 'field_mvc_milestone_sem',
                'label'         => 'Semester Cycle',
                'name'          => 'milestone_semester',
                'type'          => 'select',
                'choices'       => array(
                    'odd'  => 'Odd Semester (July–Dec)',
                    'even' => 'Even Semester (Jan–June)',
                ),
                'default_value' => 'odd',
                'required'      => 1,
            ),
            array(
                'key'          => 'field_mvc_milestone_timeframe',
                'label'        => 'Timeline / Week Period',
                'name'         => 'milestone_timeframe',
                'type'         => 'text',
                'instructions' => 'e.g., July Week 1–2, Sept 15–22, Dec Week 2–4',
                'required'     => 1,
            ),
            array(
                'key'           => 'field_mvc_milestone_category',
                'label'         => 'Milestone Category',
                'name'          => 'milestone_category',
                'type'          => 'select',
                'choices'       => array(
                    'admissions'    => 'Admissions & Induction',
                    'instructional' => 'Instructional / Lectures',
                    'assessment'    => 'Continuous Internal Assessment (CIA)',
                    'practical'     => 'Laboratory Practical & Viva',
                    'exam'          => 'University Final Examinations',
                    'recess'        => 'Academic Recess & Vacation',
                ),
                'default_value' => 'instructional',
                'required'      => 1,
            ),
            array(
                'key'          => 'field_mvc_milestone_badge',
                'label'        => 'Custom Badge Label',
                'name'         => 'milestone_badge',
                'type'         => 'text',
                'instructions' => 'Optional badge override, e.g. Admissions, CIA-I, Finals, Recess',
            ),
            array(
                'key'          => 'field_mvc_milestone_sub',
                'label'        => 'Milestone Subtitle / Summary',
                'name'         => 'milestone_subtitle',
                'type'         => 'textarea',
                'rows'         => 3,
                'instructions' => 'Descriptive summary of the curricular activity or directive',
            ),
            array(
                'key'            => 'field_mvc_milestone_start',
                'label'          => 'Calendar Start Date',
                'name'           => 'milestone_date_start',
                'type'           => 'date_picker',
                'instructions'   => 'Target date for Google Calendar sync',
                'display_format' => 'd/m/Y',
                'return_format'  => 'Y-m-d',
            ),
            array(
                'key'            => 'field_mvc_milestone_end',
                'label'          => 'Calendar End Date',
                'name'           => 'milestone_date_end',
                'type'           => 'date_picker',
                'instructions'   => 'Optional end date for Google Calendar sync',
                'display_format' => 'd/m/Y',
                'return_format'  => 'Y-m-d',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'calendar_milestone',
                ),
            ),
        ),
    ));

    // H. Academic Calendar Page Settings (on Template 'template-academic-calendar.php' / Page 281)
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_academic_calendar',
        'title'  => 'Academic Calendar & Curricular Hub Settings',
        'fields' => array(
            // TAB 1: HERO & GENERAL SETTINGS
            array(
                'key'   => 'field_mvc_cal_tab_hero',
                'label' => 'Hero & Command Deck',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_cal_session',
                'label'        => 'Active Academic Session',
                'name'         => 'calendar_active_session',
                'type'         => 'text',
                'instructions' => 'e.g. 2024–2025, 2025–2026, or 2026–2027',
                'default_value'=> '2024–2025',
            ),
            array(
                'key'          => 'field_mvc_cal_status',
                'label'        => 'Session Status Badge',
                'name'         => 'calendar_session_status',
                'type'         => 'text',
                'instructions' => 'e.g. Active Session, Approved by VKSU',
                'default_value'=> 'Active Session',
            ),
            array(
                'key'          => 'field_mvc_cal_sub_en',
                'label'        => 'Hero Subtitle (English)',
                'name'         => 'calendar_hero_subtitle_en',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Approved institutional milestones, semester-wise instructional roadmaps, university examination schedules, and official gazettes under the statutory framework of Veer Kunwar Singh University (VKSU), Ara.',
            ),
            array(
                'key'          => 'field_mvc_cal_sub_hi',
                'label'        => 'Hero Subtitle (Hindi)',
                'name'         => 'calendar_hero_subtitle_hi',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'वीर कुंवर सिंह विश्वविद्यालय (VKSU), आरा के सांविधिक दिशा-निर्देशों के अंतर्गत सत्रवार शिक्षण कार्ययोजना, आंतरिक मूल्यांकन (CIA) एवं विश्वविद्यालय परीक्षा कैलेंडर।',
            ),
            array(
                'key'          => 'field_mvc_cal_mg_file',
                'label'        => 'Master Gazette PDF',
                'name'         => 'calendar_master_gazette_file',
                'type'         => 'file',
                'return_format'=> 'array',
                'instructions' => 'Primary approved gazette document downloaded by the Hero CTA button',
            ),
            array(
                'key'          => 'field_mvc_cal_mg_btn',
                'label'        => 'Master Gazette Button Label',
                'name'         => 'calendar_master_gazette_btn',
                'type'         => 'text',
                'default_value'=> 'Download Master Gazette (PDF)',
            ),

            // TAB 2: KPI COUNTER METRICS
            array(
                'key'   => 'field_mvc_cal_tab_kpis',
                'label' => 'KPI Counter Metrics',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_1_val',
                'label'        => 'Metric 1 Value',
                'name'         => 'calendar_kpi_1_val',
                'type'         => 'text',
                'default_value'=> '180+',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_1_lbl',
                'label'        => 'Metric 1 Label',
                'name'         => 'calendar_kpi_1_label',
                'type'         => 'text',
                'default_value'=> 'Teaching Days (UGC Mandated)',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_2_val',
                'label'        => 'Metric 2 Value',
                'name'         => 'calendar_kpi_2_val',
                'type'         => 'text',
                'default_value'=> '30+',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_2_lbl',
                'label'        => 'Metric 2 Label',
                'name'         => 'calendar_kpi_2_label',
                'type'         => 'text',
                'default_value'=> 'CIA Assessments Scheduled',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_3_val',
                'label'        => 'Metric 3 Value',
                'name'         => 'calendar_kpi_3_val',
                'type'         => 'text',
                'default_value'=> '15 Depts',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_3_lbl',
                'label'        => 'Metric 3 Label',
                'name'         => 'calendar_kpi_3_label',
                'type'         => 'text',
                'default_value'=> 'Curricular Synchronization',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_4_val',
                'label'        => 'Metric 4 Value',
                'name'         => 'calendar_kpi_4_val',
                'type'         => 'text',
                'default_value'=> '100%',
            ),
            array(
                'key'          => 'field_mvc_cal_kpi_4_lbl',
                'label'        => 'Metric 4 Label',
                'name'         => 'calendar_kpi_4_label',
                'type'         => 'text',
                'default_value'=> 'VKSU 4-Yr CBCS Framework',
            ),

            // TAB 3: OFFICIAL GAZETTES ARCHIVE (CONFIGURABLE SLOTS)
            array(
                'key'   => 'field_mvc_cal_tab_gazettes',
                'label' => 'Official Gazettes Archive',
                'type'  => 'tab',
            ),
            // Gazette 1
            array('key' => 'field_mvc_g1_title', 'label' => 'Gazette 1: Title', 'name' => 'gazette_1_title', 'type' => 'text'),
            array('key' => 'field_mvc_g1_sess', 'label' => 'Gazette 1: Session', 'name' => 'gazette_1_session', 'type' => 'text'),
            array('key' => 'field_mvc_g1_memo', 'label' => 'Gazette 1: Memo No.', 'name' => 'gazette_1_memo', 'type' => 'text'),
            array('key' => 'field_mvc_g1_date', 'label' => 'Gazette 1: Issue Date', 'name' => 'gazette_1_date', 'type' => 'text'),
            array('key' => 'field_mvc_g1_auth', 'label' => 'Gazette 1: Authority', 'name' => 'gazette_1_authority', 'type' => 'text'),
            array('key' => 'field_mvc_g1_pdf', 'label' => 'Gazette 1: PDF Document', 'name' => 'gazette_1_pdf', 'type' => 'file', 'return_format' => 'array'),
            array('key' => 'field_mvc_g1_sum', 'label' => 'Gazette 1: Summary Extract', 'name' => 'gazette_1_summary', 'type' => 'textarea', 'rows' => 2),

            // Gazette 2
            array('key' => 'field_mvc_g2_title', 'label' => 'Gazette 2: Title', 'name' => 'gazette_2_title', 'type' => 'text'),
            array('key' => 'field_mvc_g2_sess', 'label' => 'Gazette 2: Session', 'name' => 'gazette_2_session', 'type' => 'text'),
            array('key' => 'field_mvc_g2_memo', 'label' => 'Gazette 2: Memo No.', 'name' => 'gazette_2_memo', 'type' => 'text'),
            array('key' => 'field_mvc_g2_date', 'label' => 'Gazette 2: Issue Date', 'name' => 'gazette_2_date', 'type' => 'text'),
            array('key' => 'field_mvc_g2_auth', 'label' => 'Gazette 2: Authority', 'name' => 'gazette_2_authority', 'type' => 'text'),
            array('key' => 'field_mvc_g2_pdf', 'label' => 'Gazette 2: PDF Document', 'name' => 'gazette_2_pdf', 'type' => 'file', 'return_format' => 'array'),
            array('key' => 'field_mvc_g2_sum', 'label' => 'Gazette 2: Summary Extract', 'name' => 'gazette_2_summary', 'type' => 'textarea', 'rows' => 2),

            // Gazette 3
            array('key' => 'field_mvc_g3_title', 'label' => 'Gazette 3: Title', 'name' => 'gazette_3_title', 'type' => 'text'),
            array('key' => 'field_mvc_g3_sess', 'label' => 'Gazette 3: Session', 'name' => 'gazette_3_session', 'type' => 'text'),
            array('key' => 'field_mvc_g3_memo', 'label' => 'Gazette 3: Memo No.', 'name' => 'gazette_3_memo', 'type' => 'text'),
            array('key' => 'field_mvc_g3_date', 'label' => 'Gazette 3: Issue Date', 'name' => 'gazette_3_date', 'type' => 'text'),
            array('key' => 'field_mvc_g3_auth', 'label' => 'Gazette 3: Authority', 'name' => 'gazette_3_authority', 'type' => 'text'),
            array('key' => 'field_mvc_g3_pdf', 'label' => 'Gazette 3: PDF Document', 'name' => 'gazette_3_pdf', 'type' => 'file', 'return_format' => 'array'),
            array('key' => 'field_mvc_g3_sum', 'label' => 'Gazette 3: Summary Extract', 'name' => 'gazette_3_summary', 'type' => 'textarea', 'rows' => 2),

            // Gazette 4
            array('key' => 'field_mvc_g4_title', 'label' => 'Gazette 4: Title', 'name' => 'gazette_4_title', 'type' => 'text'),
            array('key' => 'field_mvc_g4_sess', 'label' => 'Gazette 4: Session', 'name' => 'gazette_4_session', 'type' => 'text'),
            array('key' => 'field_mvc_g4_memo', 'label' => 'Gazette 4: Memo No.', 'name' => 'gazette_4_memo', 'type' => 'text'),
            array('key' => 'field_mvc_g4_date', 'label' => 'Gazette 4: Issue Date', 'name' => 'gazette_4_date', 'type' => 'text'),
            array('key' => 'field_mvc_g4_auth', 'label' => 'Gazette 4: Authority', 'name' => 'gazette_4_authority', 'type' => 'text'),
            array('key' => 'field_mvc_g4_pdf', 'label' => 'Gazette 4: PDF Document', 'name' => 'gazette_4_pdf', 'type' => 'file', 'return_format' => 'array'),
            array('key' => 'field_mvc_g4_sum', 'label' => 'Gazette 4: Summary Extract', 'name' => 'gazette_4_summary', 'type' => 'textarea', 'rows' => 2),

            // TAB 4: UGC 180 DAYS FACTSHEET
            array(
                'key'   => 'field_mvc_cal_tab_factsheet',
                'label' => 'UGC 180 Days Factsheet',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_cal_fs_mode',
                'label'        => 'Factsheet Mode',
                'name'         => 'calendar_factsheet_mode',
                'type'         => 'select',
                'choices'      => array(
                    'standard' => 'Standard UGC 180-Day 12-Month Table',
                    'custom'   => 'Custom WYSIWYG Content / Table',
                ),
                'default_value'=> 'standard',
            ),
            array(
                'key'          => 'field_mvc_cal_fs_custom',
                'label'        => 'Custom Factsheet Content',
                'name'         => 'calendar_factsheet_wysiwyg',
                'type'         => 'wysiwyg',
                'instructions' => 'Displayed when Custom mode is selected above.',
            ),
            array(
                'key'          => 'field_mvc_cal_fs_notes',
                'label'        => 'Statutory Notes / Remarks',
                'name'         => 'calendar_factsheet_notes',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Instructional days strictly comply with University Grants Commission (UGC) Degree Regulations mandating a minimum of 180 active teaching days per academic year. Summer recess and notified state holidays are factored into this statutory allocation.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-academic-calendar.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '281',
                ),
            ),
        ),
    ));

    // I. Academic Gazettes Fields (on CPT: 'calendar_gazette')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_gazette',
        'title'  => 'Academic Gazette Details',
        'fields' => array(
            array(
                'key'          => 'field_mvc_gazette_session',
                'label'        => 'Academic Session',
                'name'         => 'gazette_session',
                'type'         => 'text',
                'instructions' => 'e.g. 2024–2025, 2021–2022',
                'required'     => 1,
            ),
            array(
                'key'          => 'field_mvc_gazette_memo',
                'label'        => 'Official Reference Memo No.',
                'name'         => 'gazette_memo_no',
                'type'         => 'text',
                'instructions' => 'e.g. MVC/ADMIN/CAL-2021/412',
            ),
            array(
                'key'          => 'field_mvc_gazette_date',
                'label'        => 'Notification Issue Date',
                'name'         => 'gazette_notif_date',
                'type'         => 'text',
                'instructions' => 'e.g. 10 July 2021',
            ),
            array(
                'key'          => 'field_mvc_gazette_signatory',
                'label'        => 'Signatory Authority',
                'name'         => 'gazette_signatory',
                'type'         => 'text',
                'instructions' => 'e.g. Principal, M.V. College, Buxar',
            ),
            array(
                'key'          => 'field_mvc_gazette_pdf',
                'label'        => 'Gazette PDF Document',
                'name'         => 'gazette_pdf_file',
                'type'         => 'file',
                'return_format'=> 'array',
                'required'     => 1,
            ),
            array(
                'key'          => 'field_mvc_gazette_is_current',
                'label'        => 'Current Session Gazette',
                'name'         => 'gazette_is_current',
                'type'         => 'true_false',
                'message'      => 'Highlight as Active Academic Gazette for Current Session',
                'default_value'=> 0,
            ),
            array(
                'key'          => 'field_mvc_gazette_summary',
                'label'        => 'Executive Summary Extract',
                'name'         => 'gazette_summary',
                'type'         => 'textarea',
                'rows'         => 3,
                'instructions' => 'Brief note summarizing the circular contents',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'calendar_gazette',
                ),
            ),
        ),
    ));

    // I. Non-Teaching Staff Fields (on CPT: 'staff_member')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_staff_member',
        'title'  => 'Staff Member Details',
        'fields' => array(
            array(
                'key'          => 'field_mvc_staff_desig',
                'label'        => 'Official Designation',
                'name'         => 'staff_designation',
                'type'         => 'text',
                'instructions' => 'e.g., Routine Clerk, Routine Assistant, L.D.C., Store Keeper, Peon',
            ),
            array(
                'key'          => 'field_mvc_staff_wing',
                'label'        => 'Administrative Wing / Office Section',
                'name'         => 'staff_wing',
                'type'         => 'text',
                'default_value'=> 'Administrative Office / Support Wing',
                'instructions' => 'e.g., Accounts Section, Examination Wing, Principal Office, Library',
            ),
            array(
                'key'          => 'field_mvc_staff_qual',
                'label'        => 'Educational Qualification',
                'name'         => 'staff_qualification',
                'type'         => 'text',
                'default_value'=> 'Higher Secondary / Graduate',
            ),
            array(
                'key'          => 'field_mvc_staff_contact',
                'label'        => 'Official Contact / Extension',
                'name'         => 'staff_contact',
                'type'         => 'text',
                'instructions' => 'Phone or Email (optional)',
            ),
            array(
                'key'          => 'field_mvc_staff_cadre_badge',
                'label'        => 'Cadre Classification',
                'name'         => 'staff_cadre_badge',
                'type'         => 'select',
                'choices'      => array(
                    'clerical'    => 'Ministerial & Clerical Cadre',
                    'technical'   => 'Laboratory & Technical Cadre',
                    'subordinate' => 'General Support & Subordinate Cadre',
                ),
                'default_value'=> 'clerical',
            ),
            array(
                'key'          => 'field_mvc_staff_nature',
                'label'        => 'Employment Classification (Tenure)',
                'name'         => 'staff_nature',
                'type'         => 'select',
                'choices'      => array(
                    'permanent'   => 'Permanent',
                    'contractual' => 'Contractual',
                ),
                'default_value'=> 'permanent',
                'instructions' => 'Select employment classification (Permanent / Contractual)',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'staff_member',
                ),
            ),
        ),
    ));

    // J. Faculty Tenure & Nature (CPT: 'faculty')
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_faculty_tenure',
        'title'  => 'Faculty Cadre & Employment Classification',
        'fields' => array(
            array(
                'key'           => 'field_mvc_faculty_nature',
                'label'         => 'Faculty Cadre / Employment Nature',
                'name'          => 'faculty_nature',
                'type'          => 'select',
                'choices'       => array(
                    'permanent'     => 'Permanent',
                    'guest-faculty' => 'Guest Faculty',
                    'contractual'   => 'Contractual'
                ),
                'default_value' => 'permanent',
                'allow_null'    => 0,
                'ui'            => 1,
                'instructions'  => 'Select faculty employment classification (Permanent / Guest Faculty / Contractual)',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'faculty',
                ),
            ),
        ),
    ));

    // L. Principal Desk Page Fields (on Template: 'template-principal-desk.php' or Page ID: 256)
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_principal_desk',
        'title'  => 'Principal Desk & Leadership Dossier Settings',
        'fields' => array(
            // TAB 1: Profile & Secretariat
            array(
                'key'   => 'field_mvc_pdesk_tab_profile',
                'label' => '👤 Incumbent Profile & Secretariat',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_pdesk_name',
                'label'        => 'Principal Full Name',
                'name'         => 'principal_name',
                'type'         => 'text',
                'default_value'=> 'Prof. (Dr.) Krishna Kant Singh',
                'instructions' => 'Full name of the incumbent Principal & Head of Institution',
            ),
            array(
                'key'          => 'field_mvc_pdesk_desig',
                'label'        => 'Official Title / Designation',
                'name'         => 'principal_designation',
                'type'         => 'text',
                'default_value'=> 'Principal & Chief Executive Officer',
                'instructions' => 'Official administrative designation',
            ),
            array(
                'key'          => 'field_mvc_pdesk_credentials',
                'label'        => 'Academic Qualifications & Fellowship',
                'name'         => 'principal_credentials',
                'type'         => 'text',
                'default_value'=> 'M.Sc., Ph.D., Fellow & Senior Academician',
                'instructions' => 'Degrees, research specializations, and academic honors',
            ),
            array(
                'key'          => 'field_mvc_pdesk_incumbent_tag',
                'label'        => 'Incumbent Status Tag',
                'name'         => 'principal_incumbent_tag',
                'type'         => 'text',
                'default_value'=> 'Current Incumbent',
                'instructions' => 'Status badge displayed over portrait (e.g. Current Incumbent)',
            ),
            array(
                'key'          => 'field_mvc_pdesk_photo',
                'label'        => 'Official Portrait Photo',
                'name'         => 'principal_photo',
                'type'         => 'image',
                'return_format'=> 'url',
                'instructions' => 'High-resolution official portrait photo of the Principal',
            ),
            array(
                'key'          => 'field_mvc_pdesk_phone',
                'label'        => 'Secretariat Phone / EPABX',
                'name'         => 'principal_phone',
                'type'         => 'text',
                'default_value'=> '06183-295055',
                'instructions' => 'Principal Office EPABX / Direct Phone number',
            ),
            array(
                'key'          => 'field_mvc_pdesk_email',
                'label'        => 'Official Email Address',
                'name'         => 'principal_email',
                'type'         => 'text',
                'default_value'=> mvc_get_principal_email(),
                'instructions' => 'Official email for administrative correspondence',
            ),
            array(
                'key'          => 'field_mvc_pdesk_office_address',
                'label'        => 'Chamber Office Address',
                'name'         => 'principal_office_address',
                'type'         => 'textarea',
                'rows'         => 2,
                'default_value'=> 'Principal\'s Chamber, Ground Floor, Administrative Block, M.V. College Campus, Charitravan, Buxar (Bihar) – 802101',
                'instructions' => 'Physical room and block location inside campus',
            ),
            array(
                'key'          => 'field_mvc_pdesk_succession_url',
                'label'        => 'Succession List Page URL',
                'name'         => 'principal_succession_url',
                'type'         => 'text',
                'default_value'=> '/succession-list/',
                'instructions' => 'URL to the Principal\'s Succession List page',
            ),

            // TAB 2: Hero Section & Apex Beacon
            array(
                'key'   => 'field_mvc_pdesk_tab_hero',
                'label' => '🏛️ Hero & Apex Beacon',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_pdesk_hero_beacon',
                'label'        => 'Apex Beacon Badge Text',
                'name'         => 'principal_hero_beacon',
                'type'         => 'text',
                'default_value'=> 'Apex Institutional Executive Authority • M.V. College, Buxar',
                'instructions' => 'Pulsing status badge text at top of hero',
            ),
            array(
                'key'          => 'field_mvc_pdesk_hero_title',
                'label'        => 'Hero Title (H1)',
                'name'         => 'principal_hero_title',
                'type'         => 'text',
                'default_value'=> 'From the Desk of the Principal',
                'instructions' => 'Primary H1 heading for the page',
            ),
            array(
                'key'          => 'field_mvc_pdesk_heading_hi',
                'label'        => 'Devanagari Subtitle (Hindi)',
                'name'         => 'principal_welcome_heading_hi',
                'type'         => 'text',
                'default_value'=> 'महर्षि विश्वामित्र की ज्ञानभूमि पर नवप्रवेशी विद्यार्थियों का हार्दिक अभिनंदन',
                'instructions' => 'Devanagari subtitle for bilingual prestige',
            ),
            array(
                'key'          => 'field_mvc_pdesk_hero_desc',
                'label'        => 'Hero Executive Summary',
                'name'         => 'principal_hero_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Official vision address, academic development roadmap, student welfare policies, and institutional administration under the statutory framework of Veer Kunwar Singh University (VKSU), Ara & UGC Regulations.',
                'instructions' => 'Introductory summary below page title',
            ),

            // TAB 3: Welcome Address & Vision
            array(
                'key'   => 'field_mvc_pdesk_tab_address',
                'label' => '📜 Welcome Address & Vision',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_pdesk_session_tag',
                'label'        => 'Academic Session Tag',
                'name'         => 'principal_session_tag',
                'type'         => 'text',
                'default_value'=> 'Academic Session 2024–2025',
                'instructions' => 'Letterhead session tag on top right of address card',
            ),
            array(
                'key'          => 'field_mvc_pdesk_heading_en',
                'label'        => 'Address Headline (English)',
                'name'         => 'principal_welcome_heading_en',
                'type'         => 'text',
                'default_value'=> 'Welcoming Aspiring Minds to the Sacred Seat of Learning',
                'instructions' => 'Primary headline for the Principal\'s Welcome Address',
            ),
            array(
                'key'          => 'field_mvc_pdesk_lead_quote',
                'label'        => 'Lead Highlighted Quote',
                'name'         => 'principal_lead_quote',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'We are committed to see that our college emerges as the best college on the educational map of India. In order to achieve our goal we are working hard to see that the problems of the students must not go unredressed.',
                'instructions' => 'Prominent callout quote with gold heraldic quotation mark',
            ),
            array(
                'key'          => 'field_mvc_pdesk_message',
                'label'        => 'Full Welcome Address & Vision Manifesto',
                'name'         => 'principal_welcome_message',
                'type'         => 'wysiwyg',
                'instructions' => 'Comprehensive message from the Principal\'s desk (paragraphs, formatting, lists, etc.)',
            ),
            array(
                'key'          => 'field_mvc_pdesk_seal_text',
                'label'        => 'Institutional Seal Stamp Text',
                'name'         => 'principal_seal_text',
                'type'         => 'text',
                'default_value'=> 'Estd. 1958 • VKSU Ara',
                'instructions' => 'Text inside the official embossed seal badge',
            ),

            // TAB 4: KPI Metrics
            array(
                'key'   => 'field_mvc_pdesk_tab_kpis',
                'label' => '📊 Institutional KPI Metrics',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_1_val',
                'label'        => 'KPI 1 Metric Value',
                'name'         => 'principal_kpi_1_val',
                'type'         => 'text',
                'default_value'=> '1958',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_1_lbl',
                'label'        => 'KPI 1 Label',
                'name'         => 'principal_kpi_1_lbl',
                'type'         => 'text',
                'default_value'=> 'Founding Legacy (68+ Years)',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_2_val',
                'label'        => 'KPI 2 Metric Value',
                'name'         => 'principal_kpi_2_val',
                'type'         => 'text',
                'default_value'=> '15',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_2_lbl',
                'label'        => 'KPI 2 Label',
                'name'         => 'principal_kpi_2_lbl',
                'type'         => 'text',
                'default_value'=> 'Academic Departments',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_3_val',
                'label'        => 'KPI 3 Metric Value',
                'name'         => 'principal_kpi_3_val',
                'type'         => 'text',
                'default_value'=> '5,000+',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_3_lbl',
                'label'        => 'KPI 3 Label',
                'name'         => 'principal_kpi_3_lbl',
                'type'         => 'text',
                'default_value'=> 'Undergraduate & PG Scholars',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_4_val',
                'label'        => 'KPI 4 Metric Value',
                'name'         => 'principal_kpi_4_val',
                'type'         => 'text',
                'default_value'=> 'UGC 2(f) & 12(B)',
            ),
            array(
                'key'          => 'field_mvc_pdesk_kpi_4_lbl',
                'label'        => 'KPI 4 Label',
                'name'         => 'principal_kpi_4_lbl',
                'type'         => 'text',
                'default_value'=> 'Statutory Accreditation',
            ),

            // TAB 5: Strategic Pillars
            array(
                'key'   => 'field_mvc_pdesk_tab_pillars',
                'label' => '🎯 4 Strategic Pillars',
                'type'  => 'tab',
            ),
            // Pillar 1
            array(
                'key'          => 'field_mvc_pdesk_pillar_1_icon',
                'label'        => 'Pillar 1 Icon / Emoji',
                'name'         => 'principal_pillar_1_icon',
                'type'         => 'text',
                'default_value'=> '🎓',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_1_title',
                'label'        => 'Pillar 1 Title',
                'name'         => 'principal_pillar_1_title',
                'type'         => 'text',
                'default_value'=> 'Academic Rigor & NEP 2020 CBCS',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_1_desc',
                'label'        => 'Pillar 1 Description',
                'name'         => 'principal_pillar_1_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Full alignment with Veer Kunwar Singh University’s 4-Year Undergraduate (FYUGP) CBCS framework, ensuring credit mobility, interdisciplinary electives, and continuous internal evaluations.',
            ),
            // Pillar 2
            array(
                'key'          => 'field_mvc_pdesk_pillar_2_icon',
                'label'        => 'Pillar 2 Icon / Emoji',
                'name'         => 'principal_pillar_2_icon',
                'type'         => 'text',
                'default_value'=> '🛡️',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_2_title',
                'label'        => 'Pillar 2 Title',
                'name'         => 'principal_pillar_2_title',
                'type'         => 'text',
                'default_value'=> 'Zero-Grievance Student Welfare',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_2_desc',
                'label'        => 'Pillar 2 Description',
                'name'         => 'principal_pillar_2_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Transparent student support mechanisms including an active Internal Complaints Committee (ICC), Anti-Ragging Cell, Merit Scholarship assistance, and confidential grievance redressal.',
            ),
            // Pillar 3
            array(
                'key'          => 'field_mvc_pdesk_pillar_3_icon',
                'label'        => 'Pillar 3 Icon / Emoji',
                'name'         => 'principal_pillar_3_icon',
                'type'         => 'text',
                'default_value'=> '💻',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_3_title',
                'label'        => 'Pillar 3 Title',
                'name'         => 'principal_pillar_3_title',
                'type'         => 'text',
                'default_value'=> 'Modern Science & Digital Pedagogy',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_3_desc',
                'label'        => 'Pillar 3 Description',
                'name'         => 'principal_pillar_3_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Upgraded physics, chemistry, botany, zoology laboratories, computer science centers, smart audio-visual classrooms, and full integration with national e-Resource repositories.',
            ),
            // Pillar 4
            array(
                'key'          => 'field_mvc_pdesk_pillar_4_icon',
                'label'        => 'Pillar 4 Icon / Emoji',
                'name'         => 'principal_pillar_4_icon',
                'type'         => 'text',
                'default_value'=> '🤝',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_4_title',
                'label'        => 'Pillar 4 Title',
                'name'         => 'principal_pillar_4_title',
                'type'         => 'text',
                'default_value'=> 'NSS, NCC & Community Service',
            ),
            array(
                'key'          => 'field_mvc_pdesk_pillar_4_desc',
                'label'        => 'Pillar 4 Description',
                'name'         => 'principal_pillar_4_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Fostering patriotic service, disaster relief training, environmental stewardship, clean Ganga campaigns, and athletic leadership through our celebrated NCC and NSS units.',
            ),

            // TAB 6: Statutory Credentials Table
            array(
                'key'   => 'field_mvc_pdesk_tab_statutory',
                'label' => '⚖️ Statutory Credentials',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_pdesk_statutory_univ',
                'label'        => 'Governing University',
                'name'         => 'principal_statutory_univ',
                'type'         => 'text',
                'default_value'=> 'Veer Kunwar Singh University (VKSU), Ara, Bihar',
            ),
            array(
                'key'          => 'field_mvc_pdesk_statutory_status',
                'label'        => 'Constitutional Status',
                'name'         => 'principal_statutory_status',
                'type'         => 'text',
                'default_value'=> 'Constituent Unit under Bihar State Universities Act, 1976',
            ),
            array(
                'key'          => 'field_mvc_pdesk_statutory_ugc',
                'label'        => 'UGC Recognition Status',
                'name'         => 'principal_statutory_ugc',
                'type'         => 'text',
                'default_value'=> 'Recognized under Section 2(f) and Section 12(B) of the UGC Act, 1956',
            ),
            array(
                'key'          => 'field_mvc_pdesk_statutory_curricula',
                'label'        => 'Statutory Curricula',
                'name'         => 'principal_statutory_curricula',
                'type'         => 'text',
                'default_value'=> 'NEP 2020 4-Year CBCS Degree Programs • B.A., B.Sc., B.Com., BCA, BBM',
            ),
            array(
                'key'          => 'field_mvc_pdesk_statutory_rti',
                'label'        => 'RTI Compliance Officer',
                'name'         => 'principal_statutory_rti',
                'type'         => 'text',
                'default_value'=> 'Public Information Officer (PIO) / Bursar, M.V. College, Charitravan, Buxar',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-principal-desk.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '256',
                ),
            ),
        ),
    ));

    // M. Principal's Succession List Page Fields (Template: 'template-succession-list.php' or Page ID: 257)
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_succession_list',
        'title'  => 'Principal\'s Succession List & Leadership Roll Settings',
        'fields' => array(
            // TAB 1: Hero & Apex Beacon
            array(
                'key'   => 'field_mvc_sc_tab_hero',
                'label' => '🏛️ Hero & Apex Beacon',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_sc_beacon',
                'label'        => 'Apex Beacon Badge Text',
                'name'         => 'succession_hero_beacon',
                'type'         => 'text',
                'default_value'=> 'Official Historical Leadership Register • 1958 to Present',
                'instructions' => 'Pulsing status badge text at top of hero',
            ),
            array(
                'key'          => 'field_mvc_sc_title',
                'label'        => 'Hero Title (H1)',
                'name'         => 'succession_hero_title',
                'type'         => 'text',
                'default_value'=> 'Principal\'s Succession List & Institutional Leadership Roll',
                'instructions' => 'Primary H1 heading for the page',
            ),
            array(
                'key'          => 'field_mvc_sc_heading_hi',
                'label'        => 'Devanagari Subtitle (Hindi)',
                'name'         => 'succession_heading_hi',
                'type'         => 'text',
                'default_value'=> 'महर्षि विश्वामित्र महाविद्यालय के प्राचार्य एवं प्रभारी प्राचार्यों की ऐतिहासिक उत्तराधिकार नामावली',
                'instructions' => 'Devanagari subtitle celebrating the institutional heritage',
            ),
            array(
                'key'          => 'field_mvc_sc_desc',
                'label'        => 'Hero Executive Summary',
                'name'         => 'succession_hero_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'The official chronological register of Principals and Professors-In-Charge who have led Maharshi Vishwamitra Mahavidyalaya since its inception in 1958, steering academic growth, UGC compliance, and societal transformation under Veer Kunwar Singh University (VKSU), Ara.',
                'instructions' => 'Introductory summary below page title',
            ),

            // TAB 2: KPI Metrics
            array(
                'key'   => 'field_mvc_sc_tab_kpis',
                'label' => '📊 Institutional Leadership KPIs',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_1_val',
                'label'        => 'KPI 1 Metric Value',
                'name'         => 'succession_kpi_1_val',
                'type'         => 'text',
                'default_value'=> '28',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_1_lbl',
                'label'        => 'KPI 1 Label',
                'name'         => 'succession_kpi_1_lbl',
                'type'         => 'text',
                'default_value'=> 'Total Leadership Tenures',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_2_val',
                'label'        => 'KPI 2 Metric Value',
                'name'         => 'succession_kpi_2_val',
                'type'         => 'text',
                'default_value'=> '10',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_2_lbl',
                'label'        => 'KPI 2 Label',
                'name'         => 'succession_kpi_2_lbl',
                'type'         => 'text',
                'default_value'=> 'Regular Principals Appointed',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_3_val',
                'label'        => 'KPI 3 Metric Value',
                'name'         => 'succession_kpi_3_val',
                'type'         => 'text',
                'default_value'=> '18',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_3_lbl',
                'label'        => 'KPI 3 Label',
                'name'         => 'succession_kpi_3_lbl',
                'type'         => 'text',
                'default_value'=> 'Distinguished Profs.-In-Charge',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_4_val',
                'label'        => 'KPI 4 Metric Value',
                'name'         => 'succession_kpi_4_val',
                'type'         => 'text',
                'default_value'=> '68+ Years',
            ),
            array(
                'key'          => 'field_mvc_sc_kpi_4_lbl',
                'label'        => 'KPI 4 Label',
                'name'         => 'succession_kpi_4_lbl',
                'type'         => 'text',
                'default_value'=> 'Unbroken Administrative Legacy',
            ),

            // TAB 3: Advanced Succession Roll Customization
            array(
                'key'   => 'field_mvc_sc_tab_records',
                'label' => '📜 Advanced Records Override (JSON)',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_mvc_sc_records_json',
                'label'        => 'Custom Succession Records JSON Override',
                'name'         => 'succession_records_json',
                'type'         => 'textarea',
                'rows'         => 8,
                'instructions' => 'Optional: Paste a valid JSON array of succession records to customize or append to the register. Leave empty to use the verified 28 historical records.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-succession-list.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '257',
                ),
            ),
        ),
    ));

    // 17. About Us Suite Settings (Glance, Vision-Mission, Rules, Conduct, Values, Master Plan)
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_about_pages',
        'title'  => '🏛️ About Us & Institutional Identity Suite Settings',
        'fields' => array(
            // TAB 1: HERO & APEX BEACON
            array(
                'key'   => 'field_about_tab_hero',
                'label' => '🏛️ Hero & Apex Beacon',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_about_hero_beacon',
                'label'        => 'Apex Beacon Status Text',
                'name'         => 'about_hero_beacon',
                'type'         => 'text',
                'instructions' => 'Top pulsing beacon badge. Leave blank for authentic institutional default.',
            ),
            array(
                'key'          => 'field_about_hero_title',
                'label'        => 'Primary Page Title (H1)',
                'name'         => 'about_hero_title',
                'type'         => 'text',
                'instructions' => 'Main title displayed in the Apex Hero.',
            ),
            array(
                'key'          => 'field_about_heading_hi',
                'label'        => 'Devanagari Subtitle (Hindi)',
                'name'         => 'about_heading_hi',
                'type'         => 'text',
                'instructions' => 'Bilingual Hindi heading honoring institutional heritage.',
            ),
            array(
                'key'          => 'field_about_hero_desc',
                'label'        => 'Hero Executive Summary',
                'name'         => 'about_hero_desc',
                'type'         => 'textarea',
                'rows'         => 3,
                'instructions' => 'Concise descriptive paragraph under the main heading.',
            ),

            // TAB 2: METRICS & KPIS
            array(
                'key'   => 'field_about_tab_kpis',
                'label' => '📊 Strategic KPIs',
                'type'  => 'tab',
            ),
            array(
                'key'   => 'field_about_kpi_1_val',
                'label' => 'KPI 1 Metric Value',
                'name'  => 'about_kpi_1_val',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_about_kpi_1_lbl',
                'label' => 'KPI 1 Label',
                'name'  => 'about_kpi_1_lbl',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_about_kpi_2_val',
                'label' => 'KPI 2 Metric Value',
                'name'  => 'about_kpi_2_val',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_about_kpi_2_lbl',
                'label' => 'KPI 2 Label',
                'name'  => 'about_kpi_2_lbl',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_about_kpi_3_val',
                'label' => 'KPI 3 Metric Value',
                'name'  => 'about_kpi_3_val',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_about_kpi_3_lbl',
                'label' => 'KPI 3 Label',
                'name'  => 'about_kpi_3_lbl',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_about_kpi_4_val',
                'label' => 'KPI 4 Metric Value',
                'name'  => 'about_kpi_4_val',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_about_kpi_4_lbl',
                'label' => 'KPI 4 Label',
                'name'  => 'about_kpi_4_lbl',
                'type'  => 'text',
            ),

            // TAB 3: CUSTOM PROSE OVERRIDE
            array(
                'key'   => 'field_about_tab_custom',
                'label' => '📜 Custom Administrative Notes',
                'type'  => 'tab',
            ),
            array(
                'key'          => 'field_about_custom_prose',
                'label'        => 'Custom Administrative Notice / Circular Notes',
                'name'         => 'about_custom_prose',
                'type'         => 'textarea',
                'rows'         => 6,
                'instructions' => 'Optional: Add custom institutional notes, university circular references, or notices to display on this page.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-glance.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-vision-mission.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-rules-regulations.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-code-of-conduct.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-core-values.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-master-plan.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '248',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '249',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '250',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '251',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '252',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '253',
                ),
            ),
        ),
    ));

    // U. NAAC Quality & Accreditation Suite Dedicated Page Image Uploads
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_naac_dedicated_images',
        'title'  => 'NAAC Page Leadership & Personnel Dedicated Image Uploads',
        'fields' => array(
            array(
                'key'          => 'field_mvc_naac_patron_photo',
                'label'        => 'Head of Institution / Principal Photo (Upload / Override)',
                'name'         => 'naac_patron_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for the Principal / Head of Institution on this page (Takes priority over Faculty post photo).',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
            array(
                'key'          => 'field_mvc_naac_chairperson_photo',
                'label'        => 'Chairperson / Senior Institutional Advisor Photo (Upload / Override)',
                'name'         => 'naac_chairperson_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for the Steering Chairperson / Senior Advisor on this page.',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
            array(
                'key'          => 'field_mvc_naac_coordinator_photo',
                'label'        => 'IQAC / SSR / Nodal Coordinator Photo (Upload / Override)',
                'name'         => 'naac_coordinator_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for the Quality / Accreditation Coordinator on this page.',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
            array(
                'key'          => 'field_mvc_naac_ciqa_photo',
                'label'        => 'CIQA Coordinator / Documentation Officer Photo (Upload / Override)',
                'name'         => 'naac_ciqa_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for the CIQA Coordinator / Documentation Officer on this page.',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
            array(
                'key'          => 'field_mvc_naac_member1_photo',
                'label'        => 'Executive Council / Committee Member 1 Photo (Upload / Override)',
                'name'         => 'naac_member1_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for Council Member 1 (e.g., Prof. Dr. Rajesh Kumar).',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
            array(
                'key'          => 'field_mvc_naac_member2_photo',
                'label'        => 'Executive Council / Committee Member 2 Photo (Upload / Override)',
                'name'         => 'naac_member2_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for Council Member 2 (e.g., Dr. Mahendra Pratap Singh).',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
            array(
                'key'          => 'field_mvc_naac_member3_photo',
                'label'        => 'Executive Council / Committee Member 3 Photo (Upload / Override)',
                'name'         => 'naac_member3_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for Council Member 3 (e.g., Dr. Saikat Debnath).',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
            array(
                'key'          => 'field_mvc_naac_member4_photo',
                'label'        => 'Executive Council / Committee Member 4 Photo (Upload / Override)',
                'name'         => 'naac_member4_photo',
                'type'         => 'image',
                'instructions' => 'Upload a dedicated portrait for Council Member 4 (e.g., Dr. Sujit Kumar Yadav).',
                'return_format'=> 'array',
                'preview_size' => 'thumbnail',
                'library'      => 'all',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'template-naac-suite.php',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '260',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '261',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '262',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '263',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '264',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '265',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '774',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '267',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '268',
                ),
            ),
            array(
                array(
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => '269',
                ),
            ),
        ),
        'menu_order'            => 10,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
    ));

    // Options Page: College Global Settings & SEO Canonical Domain
    acf_add_local_field_group(array(
        'key'    => 'group_mvc_global_settings',
        'title'  => 'College Global Institutional & SEO Settings',
        'fields' => array(
            array(
                'key'          => 'field_mvc_college_name',
                'label'        => 'College Name (English)',
                'name'         => 'college_name',
                'type'         => 'text',
                'default_value'=> 'Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)',
            ),
            array(
                'key'          => 'field_mvc_college_name_hi',
                'label'        => 'College Name (Hindi)',
                'name'         => 'college_name_hi',
                'type'         => 'text',
                'default_value'=> 'महर्षि विश्वामित्र महाविद्यालय, बक्सर',
            ),
            array(
                'key'          => 'field_mvc_canonical_site_url',
                'label'        => 'Canonical Production Site URL',
                'name'         => 'canonical_site_url',
                'type'         => 'url',
                'instructions' => 'Official live production URL for Schema.org, Knowledge Graph, sitemaps, and search engines (e.g. https://www.mvcollegebuxar.ac.in).',
                'default_value'=> 'https://www.mvcollegebuxar.ac.in',
            ),
            array(
                'key'          => 'field_mvc_college_email',
                'label'        => 'Official Institutional Email',
                'name'         => 'college_email',
                'type'         => 'email',
                'default_value'=> 'mvcollegebuxar@gmail.com',
            ),
            array(
                'key'          => 'field_mvc_college_phone',
                'label'        => 'Official Contact Phone',
                'name'         => 'college_phone',
                'type'         => 'text',
                'default_value'=> '06183-222035',
            ),
            array(
                'key'          => 'field_mvc_campus_address',
                'label'        => 'Campus Postal Address',
                'name'         => 'campus_address',
                'type'         => 'text',
                'default_value'=> 'Charitravan, River Ganga Front, Buxar - 802101 (Bihar)',
            ),
            array(
                'key'          => 'field_mvc_maintenance_tab',
                'label'        => '🛠️ Maintenance Mode Switch',
                'type'         => 'tab',
            ),
            array(
                'key'          => 'field_mvc_maintenance_mode_enabled',
                'label'        => 'Enable Public Maintenance Mode',
                'name'         => 'maintenance_mode_enabled',
                'type'         => 'true_false',
                'instructions' => 'When active, ONLY the home page is available to the public. All other pages automatically redirect to the home page with a prominent maintenance notice displayed on top.',
                'ui'           => 1,
                'default_value'=> 0,
            ),
            array(
                'key'          => 'field_mvc_maintenance_notice_title',
                'label'        => 'Maintenance Notice Heading',
                'name'         => 'maintenance_notice_title',
                'type'         => 'text',
                'default_value'=> 'Scheduled Website Maintenance in Progress',
            ),
            array(
                'key'          => 'field_mvc_maintenance_notice_message',
                'label'        => 'Maintenance Notice Text',
                'name'         => 'maintenance_notice_message',
                'type'         => 'textarea',
                'rows'         => 3,
                'default_value'=> 'Maharshi Vishwamitra Mahavidyalaya portal is currently undergoing scheduled system updates and maintenance. During this window, only the home page is available for public viewing. All academic departments, notices, student portal, and online services will resume shortly. We apologize for any inconvenience caused.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'college-global-settings',
                ),
            ),
        ),
        'menu_order' => 1,
    ));
}
add_action('acf/init', 'mvc_register_free_acf_fields');

// Synchronize ACF Options with Core Maintenance Mode Engine
add_action('acf/save_post', function($post_id) {
    if ($post_id === 'options' && function_exists('mvc_set_maintenance_mode') && function_exists('get_field')) {
        $enabled = get_field('maintenance_mode_enabled', 'option');
        $title   = get_field('maintenance_notice_title', 'option');
        $message = get_field('maintenance_notice_message', 'option');
        if ($enabled !== null) {
            mvc_set_maintenance_mode((bool)$enabled, array(
                'notice_title'   => $title,
                'notice_message' => $message,
            ));
        }
    }
}, 20);

add_filter('acf/load_value/key=field_mvc_maintenance_mode_enabled', function($value, $post_id, $field) {
    if ($post_id === 'options' && function_exists('mvc_is_maintenance_mode_active')) {
        return mvc_is_maintenance_mode_active() ? 1 : 0;
    }
    return $value;
}, 10, 3);

/* ========================================================
   3. ENFORCE CREATIVE DASHICONS FOR ALL CUSTOM POST TYPES
   ======================================================== */
function mvc_custom_cpt_menu_icons($args, $post_type) {
    $icons = array(
        'slider'             => 'dashicons-images-alt2',           // Photo stack / slider
        'leadership'         => 'dashicons-businessman',           // Administrator portrait
        'calendar_milestone' => 'dashicons-calendar-alt',          // Academic Milestones
        'calendar_gazette'   => 'dashicons-media-document',        // Academic Gazettes
        'staff_member'       => 'dashicons-groups',                // Non-Teaching Staff
        'notice'             => 'dashicons-megaphone',             // Megaphone announcement
        'event'              => 'dashicons-calendar-alt',          // Calendar event
        'faculty'            => 'dashicons-welcome-learn-status',   // Scholar / Graduation cap
        'department'         => 'dashicons-bank',                  // Academic institute building
        'gallery-cat'        => 'dashicons-format-gallery',        // Photo gallery album
        'video'              => 'dashicons-video-alt3',            // Video camera / film player
        'press-media'        => 'dashicons-media-document',        // Newspaper / press clipping
        'testimonial'        => 'dashicons-format-quote',          // Speech quotation
        'committee'          => 'dashicons-groups',                // Working committee council
        'grievance'          => 'dashicons-shield',                // Protection / grievance redressal
        'research'           => 'dashicons-welcome-learn-more',    // Scholarly Research & Publications
    );

    if (isset($icons[$post_type])) {
        $args['menu_icon'] = $icons[$post_type];
    }
    return $args;
}
add_filter('register_post_type_args', 'mvc_custom_cpt_menu_icons', 20, 2);
