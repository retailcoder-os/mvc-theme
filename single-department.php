<?php
/**
 * Single Template for 'department' Custom Post Type
 * Automatically uses the Universal Department Builder
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div id="primary" class="content-area mvc-full-width-content" style="width: 100%; margin: 0; padding: 0;">
    <main id="main" class="site-main">
        <?php
        if (function_exists('mvc_render_department_page')) {
            echo mvc_render_department_page(get_the_ID());
        }
        ?>
    </main>
</div>

<?php
get_footer();
