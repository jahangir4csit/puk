<?php
/**
 * Register Taxonomy: Features
 */
function register_features_taxonomy() {

    $labels = array(
        'name'              => _x('Features', 'puk'),
        'singular_name'     => _x('Feature', 'taxonomy singular name'),
        'search_items'      => __('Search Features'),
        'all_items'         => __('All Features'),
        'parent_item'       => __('Parent Feature'),
        'parent_item_colon' => __('Parent Feature:'),
        'edit_item'         => __('Edit Feature'),
        'update_item'       => __('Update Feature'),
        'add_new_item'      => __('Add New Feature'),
        'new_item_name'     => __('New Feature Name'),
        'menu_name'         => __('Features'),
    );

    $args = array(
        'hierarchical'      => true, // true = categories, false = tags
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'meta_box_cb'  => false,
        'rewrite'           => array('slug' => 'features'),
    );

    register_taxonomy('features', array('product'), $args);
}
add_action('init', 'register_features_taxonomy');