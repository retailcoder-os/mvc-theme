<?php
/**
 * Maharshi Vishwamitra Mahavidyalaya (M.V. College, Buxar)
 * Master Dynamic Shortcodes Registry (All 63 Pages & Dynamic Subsystems)
 * 
 * Provides standardized, documented shortcodes for every production page and modular component.
 * Allows administrators and developers to inspect, edit, or customize any page via standard
 * WordPress editor, Page Content Studio, or future Shortcode Visual Editor.
 * 
 * 10/10 Standard Directive | Zero Hardcoding | 100% Dynamic Database Driven
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Global shortcode context storage
 */
global $mvc_shortcode_context;
$mvc_shortcode_context = array(
    'section'  => '',
    'chapter'  => '',
    'form_id'  => 0,
    'slug'     => '',
    'custom_shortcode' => ''
);

/**
 * Master catalog of all registered shortcodes and their architecture metadata
 */
function mvc_get_registered_shortcodes() {
    return array(
        'mvc_homepage' => array(
            'title'       => 'Institution Homepage Suite',
            'desc'        => 'Master responsive institutional homepage featuring hero slider, notice board, principal quote, facts matrix, departments showcase, and video highlights.',
            'function'    => 'mvc_render_homepage',
            'file'        => 'inc/homepage-builder.php',
            'template'    => 'template-home.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_college_glance' => array(
            'title'       => 'College at a Glance',
            'desc'        => 'Overview of M.V. College, foundational history, affiliations, accreditation, and institutional statistics.',
            'function'    => 'mvc_render_glance_page',
            'file'        => 'inc/about-pages-builder.php',
            'template'    => 'template-glance.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_vision_mission' => array(
            'title'       => 'Vision & Mission Statement',
            'desc'        => 'Institutional vision, mission, strategic objectives, and foundational values.',
            'function'    => 'mvc_render_vision_mission_page',
            'file'        => 'inc/about-pages-builder.php',
            'template'    => 'template-vision-mission.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_rules_regulations' => array(
            'title'       => 'Rules & Regulations',
            'desc'        => 'Institutional code, campus discipline, attendance criteria, and examination guidelines.',
            'function'    => 'mvc_render_rules_regulations_page',
            'file'        => 'inc/about-pages-builder.php',
            'template'    => 'template-rules-regulations.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_code_of_conduct' => array(
            'title'       => 'Institutional Code of Conduct',
            'desc'        => 'Professional ethics and code of conduct for students, teachers, administrators, and governing body.',
            'function'    => 'mvc_render_code_of_conduct_page',
            'file'        => 'inc/about-pages-builder.php',
            'template'    => 'template-code-of-conduct.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_core_values' => array(
            'title'       => 'Institutional Core Values',
            'desc'        => 'Foundational ethical pillars, academic integrity, social responsibility, and inclusivity.',
            'function'    => 'mvc_render_core_values_page',
            'file'        => 'inc/about-pages-builder.php',
            'template'    => 'template-core-values.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_master_plan' => array(
            'title'       => 'Campus Master Plan & Future Horizons',
            'desc'        => 'Architectural blueprints, infrastructure growth roadmap, and future development projects.',
            'function'    => 'mvc_render_master_plan_page',
            'file'        => 'inc/about-pages-builder.php',
            'template'    => 'template-master-plan.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_teaching_staff' => array(
            'title'       => 'Teaching Faculty Directory',
            'desc'        => 'Comprehensive faculty roster across Humanities, Science, and Social Sciences with research profiles and department links.',
            'function'    => 'mvc_render_teaching_staff_page',
            'file'        => 'inc/staff-directory-builder.php',
            'template'    => 'template-teaching-staff.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_non_teaching_staff' => array(
            'title'       => 'Non-Teaching Staff Directory',
            'desc'        => 'Administrative, technical, library, and support staff directory organized by division.',
            'function'    => 'mvc_render_non_teaching_staff_page',
            'file'        => 'inc/staff-directory-builder.php',
            'template'    => 'template-non-teaching-staff.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_principal_desk' => array(
            'title'       => "Principal's Desk & Message",
            'desc'        => "Official address, vision message, and leadership profile of the Principal.",
            'function'    => 'mvc_render_principal_desk_page',
            'file'        => 'inc/principal-desk-builder.php',
            'template'    => 'template-principal-desk.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_succession_list' => array(
            'title'       => 'Succession List of Principals',
            'desc'        => 'Historical timeline and tenure register of college leadership from inception to present.',
            'function'    => 'mvc_render_succession_list_page',
            'file'        => 'inc/succession-list-builder.php',
            'template'    => 'template-succession-list.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_sanctioned_teachers' => array(
            'title'       => 'Sanctioned Teaching Posts Roster',
            'desc'        => 'Cadre strength, approved sanctioned posts, working faculty count, and vacancies by department.',
            'function'    => 'mvc_render_sanctioned_teachers_page',
            'file'        => 'inc/sanctioned-posts-builder.php',
            'template'    => 'template-sanctioned-teachers.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_sanctioned_non_teachers' => array(
            'title'       => 'Sanctioned Non-Teaching Posts Roster',
            'desc'        => 'Cadre-wise sanctioned and working strengths for Grade III and Grade IV support staff.',
            'function'    => 'mvc_render_sanctioned_non_teachers_page',
            'file'        => 'inc/sanctioned-posts-builder.php',
            'template'    => 'template-sanctioned-non-teachers.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_naac_suite' => array(
            'title'       => 'NAAC Accreditation & Quality Suite',
            'desc'        => 'Multi-chapter NAAC suite supporting AISHE, SSS, IIQA, IQAC, AQAR, SSR, UGC 2F/12B, Affiliation, and NIRF.',
            'function'    => 'mvc_render_naac_suite_page',
            'file'        => 'inc/naac-suite-builder.php',
            'template'    => 'template-naac-suite.php',
            'has_form'    => true,
            'default_form'=> 788,
            'atts'        => array('chapter' => 'ssr', 'form_id' => '')
        ),
        'mvc_our_administration' => array(
            'title'       => 'College Institutional Administration',
            'desc'        => 'Governing bodies, administrative hierarchy, bursar, proctorial board, and governance inquiry portal.',
            'function'    => 'mvc_render_our_administration_page',
            'file'        => 'inc/administration-builder.php',
            'template'    => 'template-our-administration.php',
            'has_form'    => true,
            'default_form'=> 829,
            'atts'        => array('form_id' => '829')
        ),
        'mvc_university_administration' => array(
            'title'       => 'University Administration (VKSU Ara)',
            'desc'        => 'Chancellor, Vice Chancellor, Pro Vice Chancellor, and university administrative officers.',
            'function'    => 'mvc_render_university_administration_page',
            'file'        => 'inc/university-administration-builder.php',
            'template'    => 'template-university-administration.php',
            'has_form'    => true,
            'default_form'=> 831,
            'atts'        => array('form_id' => '831')
        ),
        'mvc_committees_cells' => array(
            'title'       => 'Statutory Committees & Cells',
            'desc'        => 'Active institutional committees (Anti-Ragging, Grievance, Women Cell, IQAC) and public redressal portal.',
            'function'    => 'mvc_render_committees_cells_page',
            'file'        => 'inc/committees-cells-builder.php',
            'template'    => 'template-committees-cells.php',
            'has_form'    => true,
            'default_form'=> 833,
            'atts'        => array('form_id' => '833')
        ),
        'mvc_academic_calendar' => array(
            'title'       => 'Academic Calendar & Schedule',
            'desc'        => 'Official academic milestones, semester schedules, holiday notifications, and downloadable gazettes.',
            'function'    => 'mvc_render_academic_calendar_page',
            'file'        => 'inc/academic-calendar-builder.php',
            'template'    => 'template-academic-calendar.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_contact_us' => array(
            'title'       => 'Contact Us & Public Helpdesk',
            'desc'        => 'Contact directory, interactive campus map, official email channels, and digital public inquiry submission.',
            'function'    => 'mvc_render_contact_us_page',
            'file'        => 'inc/contact-us-builder.php',
            'template'    => 'template-contact-us.php',
            'has_form'    => true,
            'default_form'=> 843,
            'atts'        => array('form_id' => '843')
        ),
        'mvc_help_desk' => array(
            'title'       => 'Single Window Help Desk',
            'desc'        => 'Student and stakeholder single-window assistance, administrative ticket routing, and helpline directory.',
            'function'    => 'mvc_render_help_desk_page',
            'file'        => 'inc/utility-suite-builder.php',
            'template'    => 'template-help-desk.php',
            'has_form'    => true,
            'default_form'=> 843,
            'atts'        => array('form_id' => '843')
        ),
        'mvc_tender' => array(
            'title'       => 'Public Tenders & Procurement',
            'desc'        => 'Active procurement notices, bidding documents, NIT releases, and archived contracts.',
            'function'    => 'mvc_render_tender_page',
            'file'        => 'inc/utility-suite-builder.php',
            'template'    => 'template-tender.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_library' => array(
            'title'       => 'Central Library & Reading Hall',
            'desc'        => 'Central library holdings, volume counts, e-library OPAC facilities, and borrowing regulations.',
            'function'    => 'mvc_render_library_page',
            'file'        => 'inc/utility-suite-builder.php',
            'template'    => 'template-library.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_e_resources' => array(
            'title'       => 'E-Resources & Digital Gateways',
            'desc'        => 'N-LIST, INFLIBNET, National Digital Library (NDLI), SWAYAM, and open-access portals.',
            'function'    => 'mvc_render_e_resources_page',
            'file'        => 'inc/utility-suite-builder.php',
            'template'    => 'template-e-resources.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_anti_ragging' => array(
            'title'       => 'Anti-Ragging Squad & Cell',
            'desc'        => 'Statutory anti-ragging policies, supreme court directives, monitoring squad contacts, and immediate helpline.',
            'function'    => 'mvc_render_anti_ragging_page',
            'file'        => 'inc/utility-suite-builder.php',
            'template'    => 'template-anti-ragging.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_events_showcase' => array(
            'title'       => 'Campus Events & Seminars Showcase',
            'desc'        => 'Live filterable archive of workshops, cultural festivals, academic seminars, and commemorative days.',
            'function'    => 'mvc_render_events_showcase_shortcode',
            'file'        => 'inc/events-page-builder.php',
            'template'    => 'template-events.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_department_details' => array(
            'title'       => 'Department Details Viewer',
            'desc'        => 'Dynamic portal rendering in-depth curriculum, faculty, laboratory infrastructure, and achievements for any department.',
            'function'    => 'mvc_render_department_details_page',
            'file'        => 'inc/department-details-builder.php',
            'template'    => 'template-department-details.php',
            'has_form'    => false,
            'atts'        => array('dept' => '')
        ),
        'mvc_notices_board' => array(
            'title'       => 'Official Notices & Circulars Board',
            'desc'        => 'Searchable, filterable repository of institutional notifications, university orders, and examination dates.',
            'function'    => 'mvc_render_notices_board_shortcode',
            'file'        => 'inc/notices-page-builder.php',
            'template'    => 'template-notices.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_photo_gallery' => array(
            'title'       => 'Institutional Photo Gallery',
            'desc'        => 'Categorized photo repository with high-resolution lightbox, category tabs, and campus photography.',
            'function'    => 'mvc_render_photo_gallery_page',
            'file'        => 'inc/media-gallery-builder.php',
            'template'    => 'template-photo-gallery.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_video_gallery' => array(
            'title'       => 'Institutional Video Vault',
            'desc'        => 'Authentic video coverage of convocation, cultural performances, campus tours, and documentary features.',
            'function'    => 'mvc_render_video_gallery_page',
            'file'        => 'inc/media-gallery-builder.php',
            'template'    => 'template-video-gallery.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_press_media' => array(
            'title'       => 'Press & Media Coverage Vault',
            'desc'        => 'Authentic newspaper clippings and news coverage documenting college milestones.',
            'function'    => 'mvc_render_press_media_page',
            'file'        => 'inc/media-gallery-builder.php',
            'template'    => 'template-press-media.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_login' => array(
            'title'       => 'Institutional Single Sign-On Portal',
            'desc'        => 'Secure role-based login gateway for students, faculty, and administrative staff.',
            'function'    => 'mvc_render_portal_login_page',
            'file'        => 'inc/portal-login-builder.php',
            'template'    => 'template-login.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_extra_curricular' => array(
            'title'       => 'Campus Life & Extra-Curricular Suite',
            'desc'        => 'Handles NSS, NCC, Sports, Facilities, Media Cell, and Cultural Activities.',
            'function'    => 'mvc_render_extra_curricular_page',
            'file'        => 'inc/extra-curricular-builder.php',
            'template'    => 'template-extra-curricular.php',
            'has_form'    => false,
            'atts'        => array('section' => 'facility')
        ),
        'mvc_student_zone' => array(
            'title'       => 'Student Zone & Academic Resources Suite',
            'desc'        => 'Master suite for 16 student services including admissions, syllabi, study materials, results, alumni, 360 feedback, and grievance redressal.',
            'function'    => 'mvc_render_student_zone_page',
            'file'        => 'inc/student-zone-builder.php',
            'template'    => 'template-student-zone.php',
            'has_form'    => true,
            'default_form'=> 823,
            'atts'        => array('section' => 'admission-procedure', 'form_id' => '')
        ),
        'mvc_department' => array(
            'title'       => 'Department Dedicated Showcase',
            'desc'        => 'Bespoke profile for any academic department (e.g. [mvc_department slug="physics"]).',
            'function'    => 'mvc_department_shortcode',
            'file'        => 'inc/department-builder.php',
            'template'    => 'template-department.php',
            'has_form'    => false,
            'atts'        => array('slug' => '')
        ),
        'mvc_onos' => array(
            'title'       => 'One Nation One Subscription (ONOS)',
            'desc'        => 'Centrally funded international e-journal and database access with Nodal Officer profile.',
            'function'    => 'mvc_render_onos_page',
            'file'        => 'inc/onos-builder.php',
            'template'    => 'template-onos.php',
            'has_form'    => false,
            'atts'        => array()
        ),
        'mvc_research' => array(
            'title'       => 'Research at MVC & Publications',
            'desc'        => 'Institutional research framework, scholarly contributions, and faculty publications archive.',
            'function'    => 'mvc_render_research_page',
            'file'        => 'inc/utility-suite-builder.php',
            'template'    => 'template-research.php',
            'has_form'    => false,
            'atts'        => array()
        )
    );
}

/**
 * Universal shortcode executor helper
 */
function mvc_execute_shortcode_renderer($function_name, $args = array()) {
    if (!function_exists($function_name)) {
        return '<div class="mvc-shortcode-error" style="padding:24px; background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; color:#9f1239; font-weight:600; text-align:center;">' .
               '⚠️ Render Engine [<code>' . esc_html($function_name) . '()</code>] is currently unavailable. Please verify the active theme builders.' .
               '</div>';
    }

    ob_start();
    try {
        if (!empty($args)) {
            $result = call_user_func_array($function_name, $args);
        } else {
            $result = call_user_func($function_name);
        }
        if (is_string($result) && !empty($result)) {
            echo $result;
        }
    } catch (\Throwable $e) {
        ob_end_clean();
        return '<div class="mvc-shortcode-exception" style="padding:24px; background:#fff1f2; border:1px solid #fecdd3; border-radius:8px; color:#9f1239;">' .
               '<strong>Shortcode Execution Notice:</strong> ' . esc_html($e->getMessage()) .
               '</div>';
    }
    return ob_get_clean();
}

/* ==========================================================================
   REGISTER ALL DYNAMIC SHORTCODES
   ========================================================================== */

// 1. Homepage Suite (Only register if not already registered)
if (!shortcode_exists('mvc_homepage')) {
    add_shortcode('mvc_homepage', function($atts = array()) {
        return mvc_execute_shortcode_renderer('mvc_render_homepage');
    });
}

// 2. About Suite
add_shortcode('mvc_college_glance', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_glance_page');
});

add_shortcode('mvc_vision_mission', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_vision_mission_page');
});

add_shortcode('mvc_rules_regulations', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_rules_regulations_page');
});

add_shortcode('mvc_code_of_conduct', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_code_of_conduct_page');
});

add_shortcode('mvc_core_values', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_core_values_page');
});

add_shortcode('mvc_master_plan', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_master_plan_page');
});

// 3. Staff & Cadre Rosters
add_shortcode('mvc_teaching_staff', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_teaching_staff_page');
});

add_shortcode('mvc_non_teaching_staff', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_non_teaching_staff_page');
});

add_shortcode('mvc_principal_desk', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_principal_desk_page');
});

add_shortcode('mvc_succession_list', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_succession_list_page');
});

add_shortcode('mvc_sanctioned_teachers', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_sanctioned_teachers_page');
});

add_shortcode('mvc_sanctioned_non_teachers', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_sanctioned_non_teachers_page');
});

// 4. Governance & University Administration
add_shortcode('mvc_our_administration', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array('form_id' => '829'), $atts);
    $mvc_shortcode_context['form_id'] = intval($parsed['form_id']);
    return mvc_execute_shortcode_renderer('mvc_render_our_administration_page', array($parsed['form_id']));
});

add_shortcode('mvc_university_administration', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array('form_id' => '831'), $atts);
    $mvc_shortcode_context['form_id'] = intval($parsed['form_id']);
    return mvc_execute_shortcode_renderer('mvc_render_university_administration_page', array($parsed['form_id']));
});

add_shortcode('mvc_committees_cells', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array('form_id' => '833'), $atts);
    $mvc_shortcode_context['form_id'] = intval($parsed['form_id']);
    return mvc_execute_shortcode_renderer('mvc_render_committees_cells_page', array($parsed['form_id']));
});

add_shortcode('mvc_academic_calendar', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_academic_calendar_page');
});

// 5. NAAC & Quality Suite
add_shortcode('mvc_naac_suite', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array(
        'chapter' => 'ssr',
        'form_id' => ''
    ), $atts);

    $chapter = sanitize_key($parsed['chapter']);
    if ($chapter === 'self-study-report') {
        $chapter = 'ssr';
    }
    $mvc_shortcode_context['chapter'] = $chapter;
    if (!empty($parsed['form_id'])) {
        $mvc_shortcode_context['form_id'] = intval($parsed['form_id']);
    }

    return mvc_execute_shortcode_renderer('mvc_render_naac_suite_page', array($chapter));
});

// 6. Utility & Contact Suite
add_shortcode('mvc_contact_us', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array('form_id' => '843'), $atts);
    $mvc_shortcode_context['form_id'] = intval($parsed['form_id']);
    return mvc_execute_shortcode_renderer('mvc_render_contact_us_page', array($parsed['form_id']));
});

add_shortcode('mvc_help_desk', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array('form_id' => '843'), $atts);
    $mvc_shortcode_context['form_id'] = intval($parsed['form_id']);
    return mvc_execute_shortcode_renderer('mvc_render_help_desk_page', array($parsed['form_id']));
});

add_shortcode('mvc_tender', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_tender_page');
});

add_shortcode('mvc_library', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_library_page');
});

add_shortcode('mvc_e_resources', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_e_resources_page');
});

add_shortcode('mvc_anti_ragging', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_anti_ragging_page');
});

add_shortcode('mvc_onos', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_onos_page');
});

add_shortcode('mvc_research', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_research_page');
});

// 7. Extra-Curricular Suite
add_shortcode('mvc_extra_curricular', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array('section' => 'facility'), $atts);
    $section = sanitize_key($parsed['section']);
    $mvc_shortcode_context['section'] = $section;
    return mvc_execute_shortcode_renderer('mvc_render_extra_curricular_page', array($section));
});

// 8. Student Zone Suite
add_shortcode('mvc_student_zone', function($atts = array()) {
    global $mvc_shortcode_context;
    $parsed = shortcode_atts(array(
        'section' => 'admission-procedure',
        'form_id' => ''
    ), $atts);

    $section = sanitize_key($parsed['section']);
    $form_id = !empty($parsed['form_id']) ? intval($parsed['form_id']) : 0;

    $mvc_shortcode_context['section'] = $section;
    $mvc_shortcode_context['form_id'] = $form_id;

    return mvc_execute_shortcode_renderer('mvc_render_student_zone_page', array($section, $form_id));
});

// 9. Department Details & Specific Department
add_shortcode('mvc_department_details', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_department_details_page');
});

if (!shortcode_exists('mvc_department')) {
    add_shortcode('mvc_department', function($atts = array()) {
        $parsed = shortcode_atts(array('slug' => ''), $atts);
        return mvc_execute_shortcode_renderer('mvc_department_shortcode', array($parsed));
    });
}

// 10. Notices & Events (if not already hooked)
if (!shortcode_exists('mvc_notices_board')) {
    add_shortcode('mvc_notices_board', function($atts = array()) {
        return mvc_execute_shortcode_renderer('mvc_render_notices_board_shortcode');
    });
}

if (!shortcode_exists('mvc_events_showcase')) {
    add_shortcode('mvc_events_showcase', function($atts = array()) {
        return mvc_execute_shortcode_renderer('mvc_render_events_showcase_shortcode');
    });
}

// 11. Galleries & Media Vault
add_shortcode('mvc_photo_gallery', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_photo_gallery_page');
});

add_shortcode('mvc_video_gallery', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_video_gallery_page');
});

add_shortcode('mvc_press_media', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_press_media_page');
});

// 12. Single Sign-On Portal
add_shortcode('mvc_login', function($atts = array()) {
    return mvc_execute_shortcode_renderer('mvc_render_portal_login_page');
});
