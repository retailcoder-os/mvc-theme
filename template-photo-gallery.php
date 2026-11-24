<?php
/**
 * Template Name: Photo Gallery Template
 * Description: 10/10 Prestigious, Mobile-First Photo Gallery Suite for M.V. College
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

global $post;
        $content = $post ? trim($post->post_content) : '';
        if (!empty($content) && (strpos($content, '[mvc_') !== false || strpos($content, '[') !== false)) {
            $clean_content = preg_replace('/<!--(.*?)-->/s', '', $content);
            echo do_shortcode(trim($clean_content));
        } elseif (function_exists('mvc_render_photo_gallery_page')) {
            mvc_render_photo_gallery_page();
        } else {
    echo '<div style="padding:60px 20px; text-align:center;"><h2>Photo Gallery Builder is loading...</h2></div>';
}

get_footer();
