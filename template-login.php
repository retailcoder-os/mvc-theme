<?php
/**
 * Template Name: Institutional Portal Login & Gateway
 * Description: Universal 10/10 Prestigious, Mobile-First Authentication Portal for M.V. College, Buxar
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div id="primary" class="content-area mvc-full-width-content" style="width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important;">
    <main id="main" class="site-main" style="width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important;">
        <?php
        global $post;
        $content = $post ? trim($post->post_content) : '';
        if (!empty($content) && (strpos($content, '[mvc_') !== false || strpos($content, '[') !== false)) {
            $clean_content = preg_replace('/<!--(.*?)-->/s', '', $content);
            echo do_shortcode(trim($clean_content));
        } elseif (function_exists('mvc_render_portal_login_page')) {
            mvc_render_portal_login_page();
        } else {
            echo '<div style="padding:60px 20px; text-align:center;"><h2>Portal Authentication Gateway is loading...</h2></div>';
        }
        ?>
    </main>
</div>

<?php
get_footer();
