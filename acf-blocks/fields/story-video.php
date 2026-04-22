<?php
/**
 * ACF Field Group: Story Video Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_story_video',
    'title' => __( 'Story Video Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_video_file',
            'label' => __( 'Video File', 'puk' ),
            'name' => 'video_file',
            'type' => 'file',
            'instructions' => __( 'Upload video file (MP4 format recommended)', 'puk' ),
            'required' => 1,
            'return_format' => 'array',
            'library' => 'all',
            'mime_types' => 'mp4,webm',
        ),
        array(
            'key' => 'field_story_fallback_image',
            'label' => __( 'Fallback Image (Optional)', 'puk' ),
            'name' => 'fallback_image',
            'type' => 'image',
            'instructions' => __( 'Optional fallback image if video cannot play', 'puk' ),
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
                'value' => 'acf/story-video',
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
    'description' => __( 'Fields for Story Video block', 'puk' ),
) );
