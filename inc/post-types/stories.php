<?php

/**
 * Register Custom Post Type: stories
 */
function puk_register_stories_post_type() {

    $labels = array(
        'name'                  => _x( 'Stories', 'Post type general name', 'puk' ),
        'singular_name'         => _x( 'Story', 'Post type singular name', 'puk' ),
        'menu_name'             => _x( 'Stories', 'Admin Menu text', 'puk' ),
        'name_admin_bar'        => _x( 'Story', 'Add New on Toolbar', 'puk' ),
        'add_new'               => __( 'Add New Story', 'puk' ),
        'add_new_item'          => __( 'Add New Story', 'puk' ),
        'new_item'              => __( 'New Story', 'puk' ),
        'edit_item'             => __( 'Edit Story', 'puk' ),
        'view_item'             => __( 'View Story', 'puk' ),
        'all_items'             => __( 'All Stories', 'puk' ),
        'search_items'          => __( 'Search Stories', 'puk' ),
        'parent_item_colon'     => __( 'Parent Stories:', 'puk' ),
        'not_found'             => __( 'No Stories found.', 'puk' ),
        'not_found_in_trash'    => __( 'No Stories found in Trash.', 'puk' ),
        'featured_image'        => _x( 'Story Featured Image', 'Overrides the "Featured Image" phrase', 'puk' ),
        'set_featured_image'    => _x( 'Set featured image', 'Overrides the "Set featured image" phrase', 'puk' ),
        'remove_featured_image' => _x( 'Remove featured image', 'Overrides the "Remove featured image" phrase', 'puk' ),
        'use_featured_image'    => _x( 'Use as featured image', 'Overrides the "Use as featured image" phrase', 'puk' ),
        'archives'              => _x( 'Story archives', 'The post type archive label', 'puk' ),
        'insert_into_item'      => _x( 'Insert into story', 'Overrides the "Insert into post" phrase', 'puk' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this story', 'Overrides the "Uploaded to this post" phrase', 'puk' ),
        'filter_items_list'     => _x( 'Filter stories list', 'Screen reader text for the filter links', 'puk' ),
        'items_list_navigation' => _x( 'Stories list navigation', 'Screen reader text for the pagination', 'puk' ),
        'items_list'            => _x( 'Stories list', 'Screen reader text for the items list', 'puk' ),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'stories' ),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 7,
        'menu_icon'             => 'dashicons-book',
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'          => true,
    );

    register_post_type( 'stories', $args );
}
add_action( 'init', 'puk_register_stories_post_type' );