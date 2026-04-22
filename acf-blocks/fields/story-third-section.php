<?php
/**
 * ACF Field Group: Story Third Section Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_story_third_section',
    'title' => __( 'Story Third Section Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_third_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => __( 'Enter section description', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'placeholder' => __( 'Enter description...', 'puk' ),
        ),
        array(
            'key' => 'field_story_third_full_width_image',
            'label' => __( 'Full Width Image', 'puk' ),
            'name' => 'full_width_image',
            'type' => 'image',
            'instructions' => __( 'Upload full width image', 'puk' ),
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
                'value' => 'acf/story-third-section',
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
    'description' => __( 'Fields for Story Third Section block', 'puk' ),
) );
