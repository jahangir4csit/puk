<?php
/**
 * Register Taxonomy: Finish Color
 */
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
        'meta_box_cb'  => false,
        'rewrite'           => array('slug' => 'finish-color'),
    );

    register_taxonomy('finish-color', array('product'), $args);
}
add_action('init', 'register_finish_color_taxonomy');