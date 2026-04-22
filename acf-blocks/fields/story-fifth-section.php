<?php
/**
 * ACF Field Group: Story Fifth Section Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_story_fifth_section',
    'title' => __( 'Story Fifth Section Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_fifth_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => __( 'Enter section description', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'placeholder' => __( 'Enter description...', 'puk' ),
        ),
        array(
            'key' => 'field_story_fifth_large_image',
            'label' => __( 'Large Image', 'puk' ),
            'name' => 'large_image',
            'type' => 'image',
            'instructions' => __( 'Upload large image', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_story_fifth_small_image',
            'label' => __( 'Small Image', 'puk' ),
            'name' => 'small_image',
            'type' => 'image',
            'instructions' => __( 'Upload small image', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/story-fifth-section',
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
    'description' => __( 'Fields for Story Fifth Section block', 'puk' ),
) );
