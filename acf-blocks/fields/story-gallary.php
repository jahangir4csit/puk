<?php
/**
 * ACF Field Group: Story Gallery Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_story_gallary',
    'title' => __( 'Story Gallery Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_gallary_title',
            'label' => __( 'Gallery Title', 'puk' ),
            'name' => 'gallery_title',
            'type' => 'text',
            'instructions' => __( 'Enter the gallery section title', 'puk' ),
            'required' => 0,
            'default_value' => 'Index',
            'placeholder' => __( 'e.g., Index', 'puk' ),
        ),
        array(
            'key' => 'field_story_gallary_images',
            'label' => __( 'Gallery Images', 'puk' ),
            'name' => 'gallery_images',
            'type' => 'gallery',
            'instructions' => __( 'Upload gallery images (unlimited)', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'insert' => 'append',
            'library' => 'all',
            'min' => 0,
            'max' => '',
            'mime_types' => 'jpg,jpeg,png,webp',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/story-gallary',
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
    'description' => __( 'Fields for Story Gallery block', 'puk' ),
) );
