<?php
/**
 * Template Name: Sanctioned Non-Teaching Staff Cadres
 * Description: Universal 10/10 Master Staff Cadre Strength & Sanctioned Posts Gazette Roster
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
        } elseif (function_exists('mvc_render_sanctioned_non_teachers_page')) {
            echo mvc_render_sanctioned_non_teachers_page();
        }
        ?>
    </main>
</div>

<?php
get_footer();
