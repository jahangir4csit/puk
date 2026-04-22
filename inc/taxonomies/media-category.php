<?php
/**
 * Media Category Taxonomy - Rewrite Slug Override
 *
 * Changes the URL structure from /media_category/ to /media/
 *
 * @package PUK
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Modify the media_category taxonomy rewrite slug
 * Changes: /media_category/on-demand/ → /media/on-demand/
 */
function puk_modify_media_category_rewrite($args, $taxonomy) {
    if ($taxonomy === 'media_category') {
        $args['rewrite'] = array(
            'slug'         => 'media',
            'with_front'   => false,
            'hierarchical' => true,
        );
    }
    return $args;
}
add_filter('register_taxonomy_args', 'puk_modify_media_category_rewrite', 10, 2);
