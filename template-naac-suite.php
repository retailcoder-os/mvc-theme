<?php
/**
 * Template Name: NAAC Quality & Accreditation Suite Template
 * Description: 10/10 Prestigious, Mobile-First NAAC & Quality Assurance Suite for M.V. College, Buxar
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
        } elseif (function_exists('mvc_render_naac_suite_page')) {
            mvc_render_naac_suite_page();
        } else {
    echo '<div style="padding:60px 20px; text-align:center;"><h2>NAAC Accreditation Suite is loading...</h2></div>';
}

get_footer();
