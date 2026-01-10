<?php
/**
 * ACF Field Group: Home News
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_home_news',
    'title' => __( 'Home News Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_home_news_section_label',
            'label' => __( 'Section Label', 'puk' ),
            'name' => 'section_label',
            'type' => 'text',
            'instructions' => __( 'Enter the section label/heading', 'puk' ),
            'required' => 0,
            'default_value' => 'Latest BLOG -',
            'placeholder' => __( 'Latest BLOG -', 'puk' ),
        ),
        array(
            'key' => 'field_home_news_posts_per_page',
            'label' => __( 'Number of Posts', 'puk' ),
            'name' => 'posts_per_page',
            'type' => 'number',
            'instructions' => __( 'How many blog posts to display in the slider', 'puk' ),
            'required' => 0,
            'default_value' => 5,
            'placeholder' => '5',
            'min' => 1,
            'max' => 20,
            'step' => 1,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/home-news',
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
    'description' => __( 'Fields for Home News block', 'puk' ),
) );
