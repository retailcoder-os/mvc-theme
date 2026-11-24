<?php
/**
 * Template Name: Teaching Staff Directory
 * Description: Universal 10/10 Master Academic Faculty Directory with Dual Views, Real Scanned Photos, Search & Filter System
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div id="primary" class="content-area mvc-full-width-content" style="width: 100%; margin: 0; padding: 0;">
    <main id="main" class="site-main">
        <?php
        global $post;
        $content = $post ? trim($post->post_content) : '';
        if (!empty($content) && (strpos($content, '[mvc_') !== false || strpos($content, '[') !== false)) {
            $clean_content = preg_replace('/<!--(.*?)-->/s', '', $content);
            echo do_shortcode(trim($clean_content));
        } elseif (function_exists('mvc_render_teaching_staff_page')) {
            echo mvc_render_teaching_staff_page();
        }
        ?>
    </main>
</div>

<?php
get_footer();
