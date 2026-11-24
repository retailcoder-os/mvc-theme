<?php
/**
 * Template Name: Research & Publications Suite
 * Description: Dedicated Research at MVC and Publications portal for Maharshi Vishwamitra Mahavidyalaya
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
        } elseif (function_exists('mvc_render_research_page')) {
            mvc_render_research_page();
        } else {
            echo '<div style="padding:60px 20px; text-align:center;"><h2>Research Suite is loading...</h2></div>';
        }
        ?>
    </main>
</div>

<?php
get_footer();
