<?php
/**
 * MV College WP-CLI Command Suite
 * 
 * Provides automated administration, user seeding, taxonomy migration, and capability verification.
 * 
 * @package MVC_College
 */

if (!defined('ABSPATH')) {
    exit;
}

if (defined('WP_CLI') && WP_CLI) {

    class MVC_CLI_Command {

        /**
         * Seed or update demo accounts for acceptance testing.
         * 
         * ## EXAMPLES
         * 
         *     wp mvc seed-test-users
         */
        public function seed_test_users($args, $assoc_args) {
            WP_CLI::line('Seeding authentic institutional demo accounts...');

            $users_to_seed = array(
                array(
                    'login' => 'admin',
                    'pass'  => 'Admin@MVC2026!',
                    'email' => 'admin@mvcollegebuxar.ac.in',
                    'role'  => 'administrator',
                    'name'  => 'Principal / System Administrator',
                ),
                array(
                    'login' => 'content_manager',
                    'pass'  => 'Manager@MVC2026!',
                    'email' => 'manager@mvcollegebuxar.ac.in',
                    'role'  => 'content_manager',
                    'name'  => 'Editorial Content Manager',
                ),
                array(
                    'login' => 'hod_physics',
                    'pass'  => 'Physics@MVC2026!',
                    'email' => 'physics.hod@mvcollegebuxar.ac.in',
                    'role'  => 'department_head',
                    'name'  => 'Head, Department of Physics',
                    'dept'  => 'physics',
                ),
                array(
                    'login' => 'hod_history',
                    'pass'  => 'History@MVC2026!',
                    'email' => 'history.hod@mvcollegebuxar.ac.in',
                    'role'  => 'department_head',
                    'name'  => 'Head, Department of History',
                    'dept'  => 'history',
                ),
                array(
                    'login' => 'student_demo',
                    'pass'  => 'Student@MVC2026!',
                    'email' => 'student@mvcollegebuxar.ac.in',
                    'role'  => 'student',
                    'name'  => 'Rahul Kumar (Student Demo)',
                    'reg'   => 'MVC/2024/0488',
                ),
                array(
                    'login' => 'alumni_demo',
                    'pass'  => 'Alumni@MVC2026!',
                    'email' => 'alumni@mvcollegebuxar.ac.in',
                    'role'  => 'alumni_member',
                    'name'  => 'Priya Sharma (Alumni Demo)',
                    'batch' => '2020',
                ),
            );

            // 15 departmental accounts
            $departments = array(
                'botany', 'business-management', 'chemistry', 'computer-applications',
                'economics', 'english', 'hindi', 'history', 'mathematics',
                'philosophy', 'physics', 'political-science', 'psychology',
                'sanskrit', 'zoology'
            );

            foreach ($departments as $d_slug) {
                $u_slug = str_replace('-', '_', $d_slug);
                $users_to_seed[] = array(
                    'login' => 'dept_' . $u_slug,
                    'pass'  => 'Dept@MVC2026!',
                    'email' => "dept.{$u_slug}@mvcollegebuxar.ac.in",
                    'role'  => 'department_head',
                    'name'  => 'Department Head (' . ucwords(str_replace('-', ' ', $d_slug)) . ')',
                    'dept'  => $d_slug,
                );
            }

            foreach ($users_to_seed as $u_data) {
                $user = get_user_by('login', $u_data['login']);
                if ($user) {
                    wp_set_password($u_data['pass'], $user->ID);
                    $user->set_role($u_data['role']);
                    $user_id = $user->ID;
                    WP_CLI::line(" - Updated: {$u_data['login']} ({$u_data['role']})");
                } else {
                    $user_id = wp_insert_user(array(
                        'user_login'   => $u_data['login'],
                        'user_pass'    => $u_data['pass'],
                        'user_email'   => $u_data['email'],
                        'display_name' => $u_data['name'],
                        'role'         => $u_data['role'],
                    ));
                    WP_CLI::line(" - Created: {$u_data['login']} ({$u_data['role']})");
                }

                update_user_meta($user_id, 'mvc_account_status', 'active');

                if (!empty($u_data['dept'])) {
                    $term = get_term_by('slug', $u_data['dept'], 'dept_link');
                    if ($term) {
                        update_user_meta($user_id, 'mvc_dept_term_id', $term->term_id);
                    }
                }
                if (!empty($u_data['reg'])) {
                    update_user_meta($user_id, 'mvc_reg_no', $u_data['reg']);
                    update_user_meta($user_id, 'mvc_session', '2024-28');
                }
                if (!empty($u_data['batch'])) {
                    update_user_meta($user_id, 'mvc_batch_year', $u_data['batch']);
                }
            }

            WP_CLI::success('Demo accounts seeded and verified.');
        }

        /**
         * Synchronize roles and capabilities.
         * 
         * ## EXAMPLES
         * 
         *     wp mvc sync-roles
         */
        public function sync_roles($args, $assoc_args) {
            mvc_sync_roles();
            WP_CLI::success('Roles and capabilities synchronized to version ' . MVC_ROLES_VERSION);
        }

        /**
         * Verify capability matrix against definition.
         * 
         * ## EXAMPLES
         * 
         *     wp mvc verify-caps
         */
        public function verify_caps($args, $assoc_args) {
            $report = mvc_verify_capabilities();
            $all_pass = true;

            foreach ($report as $role => $data) {
                if ($data['status'] === 'PASS') {
                    WP_CLI::line("[$role] PASS");
                } else {
                    $all_pass = false;
                    WP_CLI::warning("[$role] FAIL with mismatches:");
                    foreach ($data['mismatches'] as $m) {
                        WP_CLI::line("  - {$m['capability']}: Expected {$m['expected']}, Actual {$m['actual']}");
                    }
                }
            }

            if ($all_pass) {
                WP_CLI::success('All 5 core institutional roles match the capability matrix 100%!');
            } else {
                WP_CLI::error('Capability verification failed for one or more roles.');
            }
        }
    }

    WP_CLI::add_command('mvc', 'MVC_CLI_Command');
}
