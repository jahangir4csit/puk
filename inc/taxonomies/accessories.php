<?php
/**
 * Register Taxonomy: Accessories
 */
function register_accessories_taxonomy() {

    $labels = array(
        'name'              => _x('Accessories', 'puk'),
        'singular_name'     => _x('Accessory', 'taxonomy singular name'),
        'search_items'      => __('Search Accessories'),
        'all_items'         => __('All Accessories'),
        'parent_item'       => __('Parent Accessory'),
        'parent_item_colon' => __('Parent Accessory:'),
        'edit_item'         => __('Edit Accessory'),
        'update_item'       => __('Update Accessory'),
        'add_new_item'      => __('Add New Accessory'),
        'new_item_name'     => __('New Accessory Name'),
        'menu_name'         => __('Accessories'),
    );

    $args = array(
        'hierarchical'      => true, // true = categories, false = tags
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'meta_box_cb'  => false,
        'rewrite'           => array('slug' => 'accessories'),
    );

    register_taxonomy('accessories', array('product'), $args);
}
add_action('init', 'register_accessories_taxonomy');