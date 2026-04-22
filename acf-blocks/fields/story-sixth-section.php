<?php
/**
 * ACF Field Group: Story Sixth Section Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_story_sixth_section',
    'title' => __( 'Story Sixth Section Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_sixth_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => __( 'Enter section description', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'placeholder' => __( 'Enter description...', 'puk' ),
        ),
        array(
            'key' => 'field_story_sixth_gallery_images',
            'label' => __( 'Gallery Images', 'puk' ),
            'name' => 'gallery_images',
            'type' => 'gallery',
            'instructions' => __( 'Upload gallery images (4 images)', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'insert' => 'append',
            'library' => 'all',
            'min' => 0,
            'max' => 4,
            'mime_types' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/story-sixth-section',
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
    'description' => __( 'Fields for Story Sixth Section block', 'puk' ),
) );
