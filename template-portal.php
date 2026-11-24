<?php
/**
 * Template Name: Institutional Scholar & Alumni Portal
 * Description: Dedicated 10/10 Prestigious, Mobile-First Scholar & Alumni Workstation for M.V. College, Buxar
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div id="primary" class="content-area mvc-full-width-content" style="width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; background: #F8FAFC;">
    <main id="main" class="site-main" style="width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important;">
        <?php
        if (function_exists('mvc_render_portal_shortcode')) {
            echo mvc_render_portal_shortcode();
        } else {
            the_content();
        }
        ?>
    </main>
</div>

<?php
get_footer();
