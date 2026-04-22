<?php
/**
 * ACF Field Group: Story Sabbia Block
 *
 * @package Puk
 */


if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'    => 'group_story_sabbia',
    'title'  => __( 'Story Sabbia Block', 'puk' ),
    'fields' => array(

        // Section Title
        array(
            'key'         => 'field_story_sabbia_title',
            'label'       => __( 'Section Title', 'puk' ),
            'name'        => 'section_title',
            'type'        => 'text',
            'instructions'=> __( 'Enter the section title', 'puk' ),
            'required'    => 1,
            'placeholder' => __( 'e.g., SABBIA', 'puk' ),
        ),

        // Description
        array(
            'key'          => 'field_story_sabbia_description',
            'label'        => __( 'Description', 'puk' ),
            'name'         => 'description',
            'type'         => 'textarea',
            'instructions' => __( 'Enter section description', 'puk' ),
            'required'     => 0,
            'rows'         => 4,
        ),

        // Image Layout Selector (NEW)
        array(
            'key'           => 'field_story_sabbia_image_layout',
            'label'         => __( 'Image Layout', 'puk' ),
            'name'          => 'image_layout',
            'type'          => 'select',
            'instructions'  => __( 'Choose how many images to display', 'puk' ),
            'required'      => 1,
            'choices'       => array(
                '2_images' => __( '2 Images', 'puk' ),
                '4_images' => __( '4 Images', 'puk' ),
            ),
            'default_value' => '2_images',
            'allow_null'    => 0,
            'multiple'      => 0,
            'ui'            => 1,
            'return_format' => 'value',
        ),

        // --- 2-IMAGE LAYOUT ---

        // Left Image (2-image layout)
        array(
            'key'               => 'field_story_sabbia_image_left',
            'label'             => __( 'Left Image', 'puk' ),
            'name'              => 'image_left',
            'type'              => 'image',
            'instructions'      => __( 'Upload left side image (used in 2-image layout)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_story_sabbia_image_layout',
                        'operator' => '==',
                        'value'    => '2_images',
                    ),
                ),
            ),
        ),

        // Right Image (2-image layout)
        array(
            'key'               => 'field_story_sabbia_image_right',
            'label'             => __( 'Right Image', 'puk' ),
            'name'              => 'image_right',
            'type'              => 'image',
            'instructions'      => __( 'Upload right side image (used in 2-image layout)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_story_sabbia_image_layout',
                        'operator' => '==',
                        'value'    => '2_images',
                    ),
                ),
            ),
        ),

        // --- 4-IMAGE LAYOUT ---

        // Left Top Image
        array(
            'key'               => 'field_story_sabbia_image_left_top',
            'label'             => __( 'Left Side — Top Image', 'puk' ),
            'name'              => 'image_left_top',
            'type'              => 'image',
            'instructions'      => __( 'Upload left side top image (used in 4-image layout)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_story_sabbia_image_layout',
                        'operator' => '==',
                        'value'    => '4_images',
                    ),
                ),
            ),
        ),

        // Left Bottom Image
        array(
            'key'               => 'field_story_sabbia_image_left_bottom',
            'label'             => __( 'Left Side — Bottom Image', 'puk' ),
            'name'              => 'image_left_bottom',
            'type'              => 'image',
            'instructions'      => __( 'Upload left side bottom image (used in 4-image layout)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_story_sabbia_image_layout',
                        'operator' => '==',
                        'value'    => '4_images',
                    ),
                ),
            ),
        ),

        // Right Top Image
        array(
            'key'               => 'field_story_sabbia_image_right_top',
            'label'             => __( 'Right Side — Top Image', 'puk' ),
            'name'              => 'image_right_top',
            'type'              => 'image',
            'instructions'      => __( 'Upload right side top image (used in 4-image layout)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_story_sabbia_image_layout',
                        'operator' => '==',
                        'value'    => '4_images',
                    ),
                ),
            ),
        ),

        // Right Bottom Image
        array(
            'key'               => 'field_story_sabbia_image_right_bottom',
            'label'             => __( 'Right Side — Bottom Image', 'puk' ),
            'name'              => 'image_right_bottom',
            'type'              => 'image',
            'instructions'      => __( 'Upload right side bottom image (used in 4-image layout)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_story_sabbia_image_layout',
                        'operator' => '==',
                        'value'    => '4_images',
                    ),
                ),
            ),
        ),


        // Additional Text Content
        array(
            'key'           => 'field_story_sabbia_text_editor',
            'label'         => __( 'Bottom Additional Text Content', 'puk' ),
            'name'          => 'text_editor',
            'type'          => 'wysiwyg',
            'instructions'  => __( 'Enter additional text content', 'puk' ),
            'required'      => 0,
            'default_value' => '',
            'tabs'          => 'all',
            'toolbar'       => 'full',
            'media_upload'  => 1,
            'delay'         => 0,
        ),


    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/story-sabbia',
            ),
        ),
    ),
    'menu_order'          => 0,
    'position'            => 'normal',
    'style'               => 'default',
    'label_placement'     => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'      => '',
    'active'              => true,
    'description'         => __( 'Fields for Story Sabbia block', 'puk' ),
) 


);