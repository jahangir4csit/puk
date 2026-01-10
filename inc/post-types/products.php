<?php
/**
 * Register Custom Post Type: product
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
        'rewrite'               => false, // We'll handle rewrite manually
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-products',
        'supports'              => array( 'title', 'thumbnail'),
        'show_in_rest'          => true,
    );

    register_post_type( 'product', $args );
}
add_action( 'init', 'puk_register_products_post_type' );

/**
 * Custom rewrite rules for products with taxonomy hierarchy
 */
function puk_product_rewrite_rules() {
    // Get all products to create specific rules
    $products = get_posts(array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish'
    ));
    
    if (!empty($products)) {
        foreach ($products as $product) {
            // Get the primary taxonomy term for this product
            $terms = wp_get_post_terms($product->ID, 'product-family');
           
            if (!empty($terms) && !is_wp_error($terms)) {
                $primary_term = $terms[0]; // Use first term as primary
                
                // Build hierarchical path - get all ancestors
                $path = '';
                $ancestors = get_ancestors($primary_term->term_id, 'product-family');
                if (!empty($ancestors)) {
                    $ancestors = array_reverse($ancestors);
                    foreach ($ancestors as $ancestor_id) {
                        $ancestor = get_term($ancestor_id, 'product-family');
                        if ($ancestor && !is_wp_error($ancestor)) {
                            $path .= $ancestor->slug . '/';
                        }
                    }
                }
                $path .= $primary_term->slug . '/' . $product->post_name;
               
                // Add rewrite rule for this specific product
                add_rewrite_rule(
                    '^' . $path . '/?$',
                    'index.php?product=' . $product->post_name,
                    'top'
                );
            }
        }
    }
}
add_action('init', 'puk_product_rewrite_rules');

/**
 * Filter product permalink to include taxonomy hierarchy
 */
function puk_product_permalink($permalink, $post) {
    if ($post->post_type === 'product' && $post->post_status === 'publish') {
        // Get the primary taxonomy term for this product
        $terms = wp_get_post_terms($post->ID, 'product-family');
        
        if (!empty($terms) && !is_wp_error($terms)) {
            $primary_term = $terms[0]; // Use first term as primary
            
            // Build hierarchical path - get all ancestors
            $path = '';
            $ancestors = get_ancestors($primary_term->term_id, 'product-family');
            if (!empty($ancestors)) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor_id) {
                    $ancestor = get_term($ancestor_id, 'product-family');
                    if ($ancestor && !is_wp_error($ancestor)) {
                        $path .= $ancestor->slug . '/';
                    }
                }
            }
            $path .= $primary_term->slug . '/' . $post->post_name;
            
            // Create new permalink
            $permalink = home_url('/' . $path . '/');
        }
    }
    
    return $permalink;
}
add_filter('post_type_link', 'puk_product_permalink', 10, 2);