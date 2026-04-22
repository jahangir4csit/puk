<?php
/**
 * ACF Field Group: Story Fourth Section Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_story_fourth_section',
    'title' => __( 'Story Fourth Section Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_fourth_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => __( 'Enter section description', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'placeholder' => __( 'Enter description...', 'puk' ),
        ),
        array(
            'key' => 'field_story_fourth_video_file',
            'label' => __( 'Video File', 'puk' ),
            'name' => 'video_file',
            'type' => 'file',
            'instructions' => __( 'Upload video file (MP4 or WebM)', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'library' => 'all',
            'mime_types' => 'mp4,webm',
        ),
        array(
            'key' => 'field_story_fourth_side_image',
            'label' => __( 'Side Image', 'puk' ),
            'name' => 'side_image',
            'type' => 'image',
            'instructions' => __( 'Upload side image', 'puk' ),
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
                'value' => 'acf/story-fourth-section',
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
    'description' => __( 'Fields for Story Fourth Section block', 'puk' ),
) );
