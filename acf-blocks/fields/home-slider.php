<?php
/**
 * ACF Field Group: Home Slider
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_home_slider',
    'title' => __( 'Home Slider Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_home_slider_slides',
            'label' => __( 'Slides', 'puk' ),
            'name' => 'slides',
            'type' => 'repeater',
            'instructions' => __( 'Add slides (video or image)', 'puk' ),
            'required' => 1,
            'layout' => 'block',
            'button_label' => __( 'Add Slide', 'puk' ),
            'sub_fields' => array(
                array(
                    'key' => 'field_home_slider_slide_type',
                    'label' => __( 'Slide Type', 'puk' ),
                    'name' => 'slide_type',
                    'type' => 'select',
                    'instructions' => __( 'Select whether this slide is a video or image', 'puk' ),
                    'required' => 1,
                    'choices' => array(
                        'video' => __( 'Video', 'puk' ),
                        'image' => __( 'Image', 'puk' ),
                    ),
                    'default_value' => 'image',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 1,
                    'return_format' => 'value',
                ),
                array(
                    'key' => 'field_home_slider_video_file',
                    'label' => __( 'Video File', 'puk' ),
                    'name' => 'video_file',
                    'type' => 'file',
                    'instructions' => __( 'Upload video file (MP4 recommended)', 'puk' ),
                    'required' => 0,
                    'return_format' => 'array',
                    'library' => 'all',
                    'mime_types' => 'mp4,webm,mov',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_home_slider_slide_type',
                                'operator' => '==',
                                'value' => 'video',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_home_slider_image',
                    'label' => __( 'Image', 'puk' ),
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => __( 'Upload background image for this slide', 'puk' ),
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_home_slider_slide_type',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/home-slider',
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
    'description' => __( 'Fields for Home Slider block', 'puk' ),
) );
