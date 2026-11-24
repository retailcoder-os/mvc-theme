<?php
/**
 * Template Name: Campus Events & Activities Showcase
 * Description: Universal 10/10 Prestigious, Interactive Academic Seminars, Workshops & Cultural Events Showcase for M.V. College, Buxar
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
        } elseif (function_exists('mvc_render_events_page')) {
            mvc_render_events_page();
        } else {
            echo '<div style="padding:60px 20px; text-align:center;"><h2>Campus Events Showcase is loading...</h2></div>';
        }
        ?>
    </main>
</div>

<?php
get_footer();
