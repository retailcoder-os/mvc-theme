<?php
/**
 * MV College Roles & Capabilities Architecture
 * 
 * Strict 5-role institutional model with granular CPT capability mappings.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Returns table of singular and plural capability pairs for all institutional CPTs.
 *
 * @return array
 */
function mvc_cpt_capability_pairs() {
    return array(
        'notice'             => array('singular' => 'notice',           'plural' => 'notices'),
        'event'              => array('singular' => 'event',            'plural' => 'events'),
        'faculty'            => array('singular' => 'faculty_member',   'plural' => 'faculty_members'),
        'department'         => array('singular' => 'department',       'plural' => 'departments'),
        'staff_member'       => array('singular' => 'staff_member',     'plural' => 'staff_members'),
        'gallery-cat'        => array('singular' => 'gallery',          'plural' => 'galleries'),
        'video'              => array('singular' => 'video',            'plural' => 'videos'),
        'press-media'        => array('singular' => 'press_item',       'plural' => 'press_items'),
        'slider'             => array('singular' => 'slider',           'plural' => 'sliders'),
        'leadership'         => array('singular' => 'leader',           'plural' => 'leaders'),
        'testimonial'        => array('singular' => 'testimonial',      'plural' => 'testimonials'),
        'committee'          => array('singular' => 'committee',        'plural' => 'committees'),
        'calendar_milestone' => array('singular' => 'milestone',        'plural' => 'milestones'),
        'calendar_gazette'   => array('singular' => 'gazette',          'plural' => 'gazettes'),
        'grievance'          => array('singular' => 'grievance',        'plural' => 'grievances'),
        'study_material'     => array('singular' => 'study_material',   'plural' => 'study_materials'),
        'research'           => array('singular' => 'research',         'plural' => 'researches'),
    );
}

/**
 * Generate FULL capability set for a plural CPT capability name.
 *
 * @param string $plural
 * @return array
 */
function mvc_get_full_cpt_caps($plural) {
    return array(
        "edit_{$plural}"            => true,
        "edit_others_{$plural}"     => true,
        "edit_published_{$plural}"  => true,
        "edit_private_{$plural}"    => true,
        "publish_{$plural}"         => true,
        "read_private_{$plural}"    => true,
        "delete_{$plural}"          => true,
        "delete_others_{$plural}"   => true,
        "delete_published_{$plural}"=> true,
        "delete_private_{$plural}"  => true,
    );
}

/**
 * Generate DEPT capability set for a plural CPT capability name.
 * (No publish and no edit of published items; edit_others is scoped to own dept).
 *
 * @param string $plural
 * @return array
 */
function mvc_get_dept_cpt_caps($plural) {
    return array(
        "edit_{$plural}"            => true,
        "edit_others_{$plural}"     => true,
        "delete_{$plural}"          => true,
        "edit_published_{$plural}"  => false,
        "publish_{$plural}"         => false,
        "delete_published_{$plural}"=> false,
    );
}

/**
 * Return all custom institutional capabilities.
 *
 * @return array
 */
function mvc_custom_capabilities_list() {
    return array(
        'mvc_access_admin',
        'mvc_review_content',
        'mvc_handle_grievances',
        'mvc_manage_accounts',
        'mvc_manage_roles',
        'mvc_manage_settings',
        'mvc_view_audit',
        'mvc_view_reports',
        'mvc_assign_dept',
        'mvc_dept_scope',
        'mvc_portal_access',
        'mvc_submit_grievance',
        'mvc_submit_reunion',
        'mvc_view_directory',
    );
}

/**
 * Single source of truth for the 5 institutional role definitions.
 *
 * @return array
 */
function mvc_role_definitions() {
    $cpt_pairs = mvc_cpt_capability_pairs();

    // 1. CONTENT MANAGER
    $cm_caps = array(
        'read'         => true,
        'upload_files' => true,
    );
    // FULL on: notices, events, faculty, study_material, researches, galleries, videos, press, slider, testimonials, gazettes, milestones
    $cm_full_cpts = array('notice', 'event', 'faculty', 'study_material', 'research', 'gallery-cat', 'video', 'press-media', 'slider', 'testimonial', 'calendar_gazette', 'calendar_milestone');
    foreach ($cm_full_cpts as $cpt) {
        $cm_caps = array_merge($cm_caps, mvc_get_full_cpt_caps($cpt_pairs[$cpt]['plural']));
    }
    // Departments: edit, edit others, edit published (no delete)
    $dept_plural = $cpt_pairs['department']['plural'];
    $cm_caps["edit_{$dept_plural}"]           = true;
    $cm_caps["edit_others_{$dept_plural}"]    = true;
    $cm_caps["edit_published_{$dept_plural}"] = true;
    $cm_caps["delete_{$dept_plural}"]         = false;
    $cm_caps["delete_others_{$dept_plural}"]  = false;
    $cm_caps["delete_published_{$dept_plural}"]= false;
    $cm_caps["publish_{$dept_plural}"]        = false;

    // Leadership, staff_members, committees: edit and publish, no delete
    $cm_publish_cpts = array('leadership', 'staff_member', 'committee');
    foreach ($cm_publish_cpts as $cpt) {
        $p = $cpt_pairs[$cpt]['plural'];
        $cm_caps["edit_{$p}"]            = true;
        $cm_caps["edit_others_{$p}"]     = true;
        $cm_caps["edit_published_{$p}"]  = true;
        $cm_caps["publish_{$p}"]         = true;
        $cm_caps["delete_{$p}"]          = false;
        $cm_caps["delete_others_{$p}"]   = false;
        $cm_caps["delete_published_{$p}"]= false;
    }

    // Pages (the 63 system pages): edit, edit others, edit published, publish; no delete
    $cm_caps['edit_pages']           = true;
    $cm_caps['edit_others_pages']    = true;
    $cm_caps['edit_published_pages'] = true;
    $cm_caps['publish_pages']        = true;
    $cm_caps['delete_pages']         = false;
    $cm_caps['delete_others_pages']  = false;
    $cm_caps['delete_published_pages']= false;

    // Grievances: full review & handling authority across all departments
    $cm_caps['edit_grievances']           = true;
    $cm_caps['edit_others_grievances']    = true;
    $cm_caps['edit_published_grievances'] = true;
    $cm_caps['publish_grievances']        = true;
    $cm_caps['read_private_grievances']   = true;

    // Custom Capabilities for Content Manager
    $cm_caps['mvc_access_admin']      = true;
    $cm_caps['mvc_review_content']    = true;
    $cm_caps['mvc_handle_grievances'] = true;
    $cm_caps['mvc_manage_accounts']   = true;
    $cm_caps['mvc_view_reports']      = true;
    $cm_caps['mvc_assign_dept']       = true;

    // Explicitly deny sensitive administrative capabilities
    $cm_caps['manage_options']    = false;
    $cm_caps['edit_users']        = false;
    $cm_caps['create_users']      = false;
    $cm_caps['delete_users']      = false;
    $cm_caps['promote_users']     = false;
    $cm_caps['list_users']        = false;
    $cm_caps['mvc_manage_roles']  = false;
    $cm_caps['mvc_manage_settings']= false;
    $cm_caps['mvc_view_audit']    = false;
    $cm_caps['unfiltered_html']   = false;

    // 2. DEPARTMENT HEAD
    $dh_caps = array(
        'read'         => true,
        'upload_files' => true,
    );
    // DEPT on: notice, event, faculty, study_material
    $dh_dept_cpts = array('notice', 'event', 'faculty', 'study_material');
    foreach ($dh_dept_cpts as $cpt) {
        $dh_caps = array_merge($dh_caps, mvc_get_dept_cpt_caps($cpt_pairs[$cpt]['plural']));
    }

    // Researches & Publications: HODs can edit and PUBLISH scholarly research for their department
    $res_plural = $cpt_pairs['research']['plural'];
    $dh_caps["edit_{$res_plural}"]            = true;
    $dh_caps["edit_others_{$res_plural}"]     = true;
    $dh_caps["edit_published_{$res_plural}"]  = true;
    $dh_caps["publish_{$res_plural}"]         = true;
    $dh_caps["delete_{$res_plural}"]          = true;
    $dh_caps["delete_published_{$res_plural}"]= false;
    // Departments (Own department: edit, edit others, edit published - A1)
    $dh_caps["edit_{$dept_plural}"]           = true;
    $dh_caps["edit_others_{$dept_plural}"]    = true;
    $dh_caps["edit_published_{$dept_plural}"] = true;
    $dh_caps["publish_{$dept_plural}"]        = false;
    $dh_caps["delete_{$dept_plural}"]         = false;

    // Custom capabilities for Department Head
    $dh_caps['mvc_access_admin'] = true;
    $dh_caps['mvc_dept_scope']   = true;

    // Explicitly deny everything else
    $dh_caps['mvc_review_content']    = false;
    // Grievances: Department Head can review and resolve enquiries assigned to their department
    $dh_caps['edit_grievances']           = true;
    $dh_caps['edit_others_grievances']    = true;
    $dh_caps['edit_published_grievances'] = true;
    $dh_caps['read_private_grievances']   = true;
    $dh_caps['mvc_handle_grievances']     = true;
    $dh_caps['mvc_manage_accounts']   = false;
    $dh_caps['mvc_manage_roles']      = false;
    $dh_caps['mvc_manage_settings']   = false;
    $dh_caps['mvc_view_audit']        = false;
    $dh_caps['manage_options']        = false;
    $dh_caps['edit_pages']            = false;
    $dh_caps['edit_users']            = false;

    // 3. STUDENT
    $student_caps = array(
        'read'                  => true,
        'upload_files'          => false,
        'edit_posts'            => false,
        'publish_posts'         => false,
        'delete_posts'          => false,
        'mvc_portal_access'     => true,
        'mvc_submit_grievance'  => true,
        'mvc_access_admin'      => false,
    );

    // 4. ALUMNI MEMBER
    $alumni_caps = array(
        'read'                  => true,
        'upload_files'          => false,
        'edit_posts'            => false,
        'publish_posts'         => false,
        'delete_posts'          => false,
        'mvc_portal_access'     => true,
        'mvc_submit_reunion'    => true,
        'mvc_view_directory'    => true,
        'mvc_access_admin'      => false,
    );

    $roles = array(
        'content_manager' => array(
            'display_name' => __('Content Manager', 'mvc-college'),
            'capabilities' => $cm_caps,
        ),
        'department_head' => array(
            'display_name' => __('Department Head', 'mvc-college'),
            'capabilities' => $dh_caps,
        ),
        'student' => array(
            'display_name' => __('Student', 'mvc-college'),
            'capabilities' => $student_caps,
        ),
        'alumni_member' => array(
            'display_name' => __('Alumni Member', 'mvc-college'),
            'capabilities' => $alumni_caps,
        ),
    );

    return apply_filters('mvc_role_definitions', $roles);
}

/**
 * Idempotently synchronize all roles and capabilities.
 * 
 * Ensures Administrator has all custom capabilities.
 * Ensures custom roles match definition exactly without deleting existing user accounts.
 */
function mvc_sync_roles() {
    $definitions = mvc_role_definitions();
    $all_custom_caps = mvc_custom_capabilities_list();

    // 1. Take snapshot on first run for rollback support
    if (!get_option('mvc_roles_snapshot')) {
        $wp_roles = wp_roles();
        if ($wp_roles && !empty($wp_roles->roles)) {
            update_option('mvc_roles_snapshot', $wp_roles->roles, false);
        }
    }

    // 2. Grant ALL capabilities (custom + CPT) to administrator (except restrictive department scope)
    $admin_role = get_role('administrator');
    if ($admin_role) {
        foreach ($all_custom_caps as $cap) {
            // Administrator and Principal must NEVER be restricted by department scope
            if ($cap === 'mvc_dept_scope') {
                $admin_role->remove_cap($cap);
                continue;
            }
            $admin_role->add_cap($cap);
        }
        foreach (mvc_cpt_capability_pairs() as $cpt => $pair) {
            foreach (mvc_get_full_cpt_caps($pair['plural']) as $c => $granted) {
                $admin_role->add_cap($c);
            }
        }
    }

    // 3. Sync each institutional role
    foreach ($definitions as $role_key => $def) {
        $role_obj = get_role($role_key);
        if (!$role_obj) {
            add_role($role_key, $def['display_name'], $def['capabilities']);
            $role_obj = get_role($role_key);
        }

        if ($role_obj) {
            // Add or remove capabilities so role exactly matches definition
            foreach ($def['capabilities'] as $cap => $grant) {
                if ($grant) {
                    $role_obj->add_cap($cap);
                } else {
                    $role_obj->remove_cap($cap);
                }
            }
        }
    }

    update_option('mvc_roles_version', MVC_ROLES_VERSION);
    mvc_audit('roles_synchronized', 'system', 0, array('version' => MVC_ROLES_VERSION));
}

/**
 * Hook role sync on init and after_switch_theme.
 */
add_action('init', function() {
    if (get_option('mvc_roles_version') !== MVC_ROLES_VERSION) {
        mvc_sync_roles();
    }
}, 1);
add_action('after_switch_theme', 'mvc_sync_roles');

/**
 * Register CPTs with explicit capability pairs and map_meta_cap => true.
 * Registers new 'study_material' CPT.
 */
function mvc_register_cpts_with_capabilities() {
    $cpt_pairs = mvc_cpt_capability_pairs();

    // 1. Register study_material CPT
    register_post_type('study_material', array(
        'labels' => array(
            'name'               => __('Study Material', 'mvc-college'),
            'singular_name'      => __('Study Material', 'mvc-college'),
            'add_new'            => __('Add New Material', 'mvc-college'),
            'add_new_item'       => __('Add New Study Material', 'mvc-college'),
            'edit_item'          => __('Edit Study Material', 'mvc-college'),
            'all_items'          => __('All Study Material', 'mvc-college'),
            'menu_name'          => __('Study Material', 'mvc-college'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'author', 'custom-fields'),
        'capability_type'    => array($cpt_pairs['study_material']['singular'], $cpt_pairs['study_material']['plural']),
        'map_meta_cap'       => true,
        'has_archive'        => false,
        'rewrite'            => array('slug' => 'study-material'),
    ));

    // 2. Register / configure grievance CPT for Classic & Modern Admin
    register_post_type('grievance', array(
        'labels' => array(
            'name'               => __('Grievances & Enquiries', 'mvc-college'),
            'singular_name'      => __('Grievance / Enquiry', 'mvc-college'),
            'menu_name'          => __('Grievances & Enquiries', 'mvc-college'),
            'all_items'          => __('All Enquiries & Grievances', 'mvc-college'),
            'add_new'            => __('Add New Case', 'mvc-college'),
            'add_new_item'       => __('Add New Enquiry / Grievance', 'mvc-college'),
            'edit_item'          => __('Manage Case Dossier', 'mvc-college'),
            'view_item'          => __('View Case Dossier', 'mvc-college'),
            'search_items'       => __('Search Enquiries & Grievances', 'mvc-college'),
            'not_found'          => __('No enquiries or grievances found', 'mvc-college'),
            'not_found_in_trash' => __('No records found in Trash', 'mvc-college'),
        ),
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-shield-alt',
        'menu_position'       => 28,
        'exclude_from_search' => true,
        'supports'            => array('title', 'editor', 'author', 'custom-fields'),
        'capability_type'     => array($cpt_pairs['grievance']['singular'], $cpt_pairs['grievance']['plural']),
        'map_meta_cap'        => true,
        'has_archive'         => false,
        'rewrite'             => false,
    ));

    // Register grievance-status taxonomy
    register_taxonomy('grievance-status', array('grievance'), array(
        'labels' => array(
            'name'              => __('Case Statuses', 'mvc-college'),
            'singular_name'     => __('Case Status', 'mvc-college'),
            'search_items'      => __('Search Statuses', 'mvc-college'),
            'all_items'         => __('All Statuses', 'mvc-college'),
            'edit_item'         => __('Edit Status', 'mvc-college'),
            'update_item'       => __('Update Status', 'mvc-college'),
            'add_new_item'      => __('Add New Status', 'mvc-college'),
            'new_item_name'     => __('New Status Name', 'mvc-college'),
            'menu_name'         => __('Statuses', 'mvc-college'),
        ),
        'hierarchical'      => true,
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => false,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'capabilities'      => array(
            'manage_terms' => 'mvc_access_admin',
            'edit_terms'   => 'mvc_access_admin',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'mvc_access_admin',
        ),
        'rewrite'           => false,
    ));

    // 3. Register research CPT (Research & Publications)
    register_post_type('research', array(
        'labels' => array(
            'name'               => __('Researches & Publications', 'mvc-college'),
            'singular_name'      => __('Research Work', 'mvc-college'),
            'add_new'            => __('Add New Research', 'mvc-college'),
            'add_new_item'       => __('Add New Research / Publication', 'mvc-college'),
            'edit_item'          => __('Edit Research Publication', 'mvc-college'),
            'all_items'          => __('All Researches & Publications', 'mvc-college'),
            'menu_name'          => __('Research & Pubs', 'mvc-college'),
            'search_items'       => __('Search Research Works', 'mvc-college'),
            'not_found'          => __('No research publications found', 'mvc-college'),
            'not_found_in_trash' => __('No research publications found in Trash', 'mvc-college'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'menu_position'      => 25,
        'supports'           => array('title', 'editor', 'thumbnail', 'author', 'excerpt', 'custom-fields'),
        'capability_type'    => array($cpt_pairs['research']['singular'], $cpt_pairs['research']['plural']),
        'map_meta_cap'       => true,
        'has_archive'        => false,
        'rewrite'            => array('slug' => 'research-item'),
    ));

    // Register research_type taxonomy
    register_taxonomy('research_type', array('research'), array(
        'labels' => array(
            'name'              => __('Research Categories', 'mvc-college'),
            'singular_name'     => __('Research Category', 'mvc-college'),
            'search_items'      => __('Search Categories', 'mvc-college'),
            'all_items'         => __('All Categories', 'mvc-college'),
            'edit_item'         => __('Edit Category', 'mvc-college'),
            'update_item'       => __('Update Category', 'mvc-college'),
            'add_new_item'      => __('Add New Category', 'mvc-college'),
            'new_item_name'     => __('New Category Name', 'mvc-college'),
            'menu_name'         => __('Research Categories', 'mvc-college'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'capabilities'      => array(
            'manage_terms' => 'mvc_access_admin',
            'edit_terms'   => 'mvc_access_admin',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'mvc_access_admin',
        ),
        'rewrite'           => array('slug' => 'research-category'),
    ));

    // 4. Register dept_link taxonomy (attached to content post types, research, and grievances)
    register_taxonomy('dept_link', array('faculty', 'notice', 'event', 'study_material', 'grievance', 'research'), array(
        'labels' => array(
            'name'              => __('Academic Departments', 'mvc-college'),
            'singular_name'     => __('Academic Department', 'mvc-college'),
            'search_items'      => __('Search Departments', 'mvc-college'),
            'all_items'         => __('All Departments', 'mvc-college'),
            'parent_item'       => __('Parent Department', 'mvc-college'),
            'parent_item_colon' => __('Parent Department:', 'mvc-college'),
            'edit_item'         => __('Edit Department', 'mvc-college'),
            'update_item'       => __('Update Department', 'mvc-college'),
            'add_new_item'      => __('Add New Department', 'mvc-college'),
            'new_item_name'     => __('New Department Name', 'mvc-college'),
            'menu_name'         => __('Departments', 'mvc-college'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => false,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'capabilities'      => array(
            'manage_terms' => 'manage_options',
            'edit_terms'   => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'mvc_assign_dept',
        ),
        'rewrite'           => array('slug' => 'dept-link'),
    ));

    // 4. Attach audience taxonomy with edit_notices cap
    if (taxonomy_exists('audience')) {
        // Ensure terms exist
        $audience_terms = array('Students', 'Officials', 'General', 'Alumni', 'Faculty', 'Staff', 'Examination');
        foreach ($audience_terms as $term_name) {
            if (!term_exists($term_name, 'audience')) {
                wp_insert_term($term_name, 'audience', array('slug' => sanitize_title($term_name)));
            }
        }
    }
}
add_action('init', 'mvc_register_cpts_with_capabilities', 5);

/**
 * Filter existing CPT registrations to enforce their capability_type and map_meta_cap => true.
 */
add_action('init', function() {
    global $wp_post_types;
    $cpt_pairs = mvc_cpt_capability_pairs();

    foreach ($cpt_pairs as $post_type => $pair) {
        if (isset($wp_post_types[$post_type])) {
            $wp_post_types[$post_type]->capability_type = array($pair['singular'], $pair['plural']);
            $wp_post_types[$post_type]->map_meta_cap    = true;
            $wp_post_types[$post_type]->cap             = get_post_type_capabilities((object) array(
                'capability_type' => array($pair['singular'], $pair['plural']),
                'map_meta_cap'    => true,
                'capabilities'    => array(),
            ));
        }
    }
}, 99);

/**
 * Verification helper for acceptance testing.
 *
 * @return array Role capability verification report
 */
function mvc_verify_capabilities() {
    $definitions = mvc_role_definitions();
    $results = array();

    foreach ($definitions as $role_key => $def) {
        $role_obj = get_role($role_key);
        if (!$role_obj) {
            $results[$role_key] = array('status' => 'MISSING_ROLE', 'mismatches' => array());
            continue;
        }

        $mismatches = array();
        foreach ($def['capabilities'] as $cap => $expected) {
            $actual = !empty($role_obj->capabilities[$cap]);
            if ($actual !== $expected) {
                $mismatches[] = array(
                    'capability' => $cap,
                    'expected'   => $expected ? 'GRANTED' : 'DENIED',
                    'actual'     => $actual ? 'GRANTED' : 'DENIED',
                );
            }
        }

        $results[$role_key] = array(
            'status'     => empty($mismatches) ? 'PASS' : 'FAIL',
            'mismatches' => $mismatches,
        );
    }

    return $results;
}
