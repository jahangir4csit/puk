<?php
/**
 * ACF Field Group: All Stories Block
 *
 * This file registers ACF fields for the all-stories block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_all_stories_block',
    'title' => __( 'All Stories Block Settings', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_all_stories_posts_per_page',
            'label' => __( 'Number of Stories to Display', 'puk' ),
            'name' => 'posts_per_page',
            'type' => 'number',
            'instructions' => __( 'Enter the number of stories to display. Leave empty or -1 to show all stories.', 'puk' ),
            'required' => 0,
            'default_value' => -1,
            'placeholder' => '-1',
            'min' => -1,
            'step' => 1,
        ),
        array(
            'key' => 'field_all_stories_order',
            'label' => __( 'Order', 'puk' ),
            'name' => 'order',
            'type' => 'select',
            'instructions' => __( 'Select the order of stories', 'puk' ),
            'required' => 0,
            'choices' => array(
                'DESC' => __( 'Newest First (DESC)', 'puk' ),
                'ASC' => __( 'Oldest First (ASC)', 'puk' ),
            ),
            'default_value' => 'DESC',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
        ),
        array(
            'key' => 'field_all_stories_orderby',
            'label' => __( 'Order By', 'puk' ),
            'name' => 'orderby',
            'type' => 'select',
            'instructions' => __( 'Select what to order stories by', 'puk' ),
            'required' => 0,
            'choices' => array(
                'date' => __( 'Date Published', 'puk' ),
                'title' => __( 'Title', 'puk' ),
                'modified' => __( 'Date Modified', 'puk' ),
                'rand' => __( 'Random', 'puk' ),
                'menu_order' => __( 'Menu Order', 'puk' ),
            ),
            'default_value' => 'date',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'value',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/all-stories',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => __( 'Settings for All Stories Block', 'puk' ),
) );
