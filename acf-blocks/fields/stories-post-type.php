<?php
/**
 * ACF Field Group: Stories Post Type Fields
 *
 * Custom fields for the stories post type
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_stories_post_type',
    'title' => __( 'Story Details', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_subtitle',
            'label' => __( 'Subtitle', 'puk' ),
            'name' => 'subtitle',
            'type' => 'textarea',
            'instructions' => __( 'Enter a subtitle for this story', 'puk' ),
            'required' => 0,
            'default_value' => '',
            'placeholder' => __( 'Enter subtitle...', 'puk' ),
            'maxlength' => '',
        ),
        array(
            'key' => 'field_story_category',
            'label' => __( 'Story Category/Tag', 'puk' ),
            'name' => 'story_category',
            'type' => 'textarea',
            'instructions' => __( 'Enter a category or tag for this story (e.g., TROPICAL, SABBIA, ITALIAN GARDEN)', 'puk' ),
            'required' => 0,
            'default_value' => '',
            'placeholder' => __( 'e.g., TROPICAL', 'puk' ),
            'maxlength' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'stories',
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
    'description' => __( 'Custom fields for stories post type', 'puk' ),
) );
