<?php
/**
 * Register Custom Post Type: products
 */
function puk_register_products_post_type() {

    $labels = array(
        'name'                  => _x( 'Products', 'Post type general name', 'puk' ),
        'singular_name'         => _x( 'Product', 'Post type singular name', 'puk' ),
        'menu_name'             => _x( 'Products', 'Admin Menu text', 'puk' ),
        'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'puk' ),
        'add_new'               => __( 'Add New Product', 'puk' ),
        'add_new_item'          => __( 'Add New Product', 'puk' ),
        'new_item'              => __( 'New Product', 'puk' ),
        'edit_item'             => __( 'Edit Product', 'puk' ),
        'view_item'             => __( 'View Product', 'puk' ),
        'all_items'             => __( 'All Products', 'puk' ),
        'search_items'          => __( 'Search Products', 'puk' ),
        'not_found'             => __( 'No Products found.', 'puk' ),
        'not_found_in_trash'    => __( 'No Products found in Trash.', 'puk' ),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'products' ),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-products',
        'supports'              => array( 'title', 'thumbnail'),
        'show_in_rest'          => true,
    );

    register_post_type( 'products', $args );
}
add_action( 'init', 'puk_register_products_post_type' );



/**
 * Register Taxonomy: products-family (for products)
 */
function puk_register_products_family_taxonomy() {

    $labels = array(
        'name'              => _x( 'Product Families', 'puk', 'puk' ),
        'singular_name'     => _x( 'Product Family', 'taxonomy singular name', 'puk' ),
        'search_items'      => __( 'Search Product Families', 'puk' ),
        'all_items'         => __( 'All Product Families', 'puk' ),
        'parent_item'       => __( 'Parent Product Family', 'puk' ),
        'parent_item_colon' => __( 'Parent Product Family:', 'puk' ),
        'edit_item'         => __( 'Edit Product Family', 'puk' ),
        'update_item'       => __( 'Update Product Family', 'puk' ),
        'add_new_item'      => __( 'Add New Product Family', 'puk' ),
        'new_item_name'     => __( 'New Product Family Name', 'puk' ),
        'menu_name'         => __( 'Products Family', 'puk' ),
    );

    $args = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
                                    // 'slug' => '',
                                     'hierarchical'      => true,
                                ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'products-family', array( 'products' ), $args );
}
add_action( 'init', 'puk_register_products_family_taxonomy' );


// Register Taxonomy: Finish Color
function register_finish_color_taxonomy() {

    $labels = array(
        'name'              => _x('Finish Colors', 'puk'),
        'singular_name'     => _x('Finish Color', 'taxonomy singular name'),
        'search_items'      => __('Search Finish Colors'),
        'all_items'         => __('All Finish Colors'),
        'parent_item'       => __('Parent Finish Color'),
        'parent_item_colon' => __('Parent Finish Color:'),
        'edit_item'         => __('Edit Finish Color'),
        'update_item'       => __('Update Finish Color'),
        'add_new_item'      => __('Add New Finish Color'),
        'new_item_name'     => __('New Finish Color Name'),
        'menu_name'         => __('Finish Color'),
    );

    $args = array(
        'hierarchical'      => true, // true = categories, false = tags
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'finish-color'),
    );

    register_taxonomy('finish-color', array('products'), $args);
}
add_action('init', 'register_finish_color_taxonomy');
