<?php
/**
 * ACF Field Group: Home Intro
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'    => 'group_home_intro',
    'title'  => __( 'Home Intro Block', 'puk' ),
    'fields' => array(

        // -------------------------
        // Intro Content
        // -------------------------
        array(
            'key'          => 'field_home_intro_content',
            'label'        => __( 'Intro Content', 'puk' ),
            'name'         => 'intro_content',
            'type'         => 'wysiwyg',
            'instructions' => __( 'Enter the introduction content (left side)', 'puk' ),
            'required'     => 1,
            'tabs'         => 'all',
            'toolbar'      => 'basic',
            'media_upload' => 0,
            'delay'        => 0,
        ),

        // -------------------------
        // Product Card
        // -------------------------
        array(
            'key'           => 'field_home_intro_product_card_media_type',
            'label'         => __( 'Product Card Media Type', 'puk' ),
            'name'          => 'product_card_media_type',
            'type'          => 'radio',
            'instructions'  => __( 'Choose whether to use an image or video for the product card', 'puk' ),
            'required'      => 0,
            'choices'       => array(
                'image' => __( 'Image', 'puk' ),
                'video' => __( 'Video', 'puk' ),
            ),
            'default_value' => 'image',
            'layout'        => 'horizontal',
        ),
        array(
            'key'               => 'field_home_intro_product_card_image',
            'label'             => __( 'Product Card Image', 'puk' ),
            'name'              => 'product_card_image',
            'type'              => 'image',
            'instructions'      => __( 'Upload product card image (left side)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_home_intro_product_card_media_type',
                        'operator' => '==',
                        'value'    => 'image',
                    ),
                ),
            ),
        ),
        array(
            'key'               => 'field_home_intro_product_card_video',
            'label'             => __( 'Product Card Video', 'puk' ),
            'name'              => 'product_card_video',
            'type'              => 'file',
            'instructions'      => __( 'Upload product card video (left side) — mp4 recommended', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'library'           => 'all',
            'mime_types'        => 'mp4,webm,ogg',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_home_intro_product_card_media_type',
                        'operator' => '==',
                        'value'    => 'video',
                    ),
                ),
            ),
        ),
        array(
            'key'          => 'field_home_intro_product_card_link_text',
            'label'        => __( 'Product Card Link Text', 'puk' ),
            'name'         => 'product_card_link_text',
            'type'         => 'text',
            'instructions' => __( 'Enter the link text for product card', 'puk' ),
            'required'     => 0,
            'default_value' => 'Explore Products',
            'placeholder'  => __( 'Explore Products', 'puk' ),
        ),
        array(
            'key'          => 'field_home_intro_product_card_link_url',
            'label'        => __( 'Product Card Link URL', 'puk' ),
            'name'         => 'product_card_link_url',
            'type'         => 'text',
            'instructions' => __( 'Enter the URL for product card', 'puk' ),
            'required'     => 0,
            'placeholder'  => __( 'https://...', 'puk' ),
        ),

        // -------------------------
        // Project Card
        // -------------------------
        array(
            'key'           => 'field_home_intro_project_card_media_type',
            'label'         => __( 'Project Card Media Type', 'puk' ),
            'name'          => 'project_card_media_type',
            'type'          => 'radio',
            'instructions'  => __( 'Choose whether to use an image or video for the project card', 'puk' ),
            'required'      => 0,
            'choices'       => array(
                'image' => __( 'Image', 'puk' ),
                'video' => __( 'Video', 'puk' ),
            ),
            'default_value' => 'image',
            'layout'        => 'horizontal',
        ),
        array(
            'key'               => 'field_home_intro_project_card_image',
            'label'             => __( 'Project Card Image', 'puk' ),
            'name'              => 'project_card_image',
            'type'              => 'image',
            'instructions'      => __( 'Upload project card image (right side)', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'preview_size'      => 'medium',
            'library'           => 'all',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_home_intro_project_card_media_type',
                        'operator' => '==',
                        'value'    => 'image',
                    ),
                ),
            ),
        ),
        array(
            'key'               => 'field_home_intro_project_card_video',
            'label'             => __( 'Project Card Video', 'puk' ),
            'name'              => 'project_card_video',
            'type'              => 'file',
            'instructions'      => __( 'Upload project card video (right side) — mp4 recommended', 'puk' ),
            'required'          => 0,
            'return_format'     => 'array',
            'library'           => 'all',
            'mime_types'        => 'mp4,webm,ogg',
            'conditional_logic' => array(
                array(
                    array(
                        'field'    => 'field_home_intro_project_card_media_type',
                        'operator' => '==',
                        'value'    => 'video',
                    ),
                ),
            ),
        ),
        array(
            'key'          => 'field_home_intro_project_card_link_text',
            'label'        => __( 'Project Card Link Text', 'puk' ),
            'name'         => 'project_card_link_text',
            'type'         => 'text',
            'instructions' => __( 'Enter the link text for project card', 'puk' ),
            'required'     => 0,
            'default_value' => 'Our Projects',
            'placeholder'  => __( 'Our Projects', 'puk' ),
        ),
        array(
            'key'          => 'field_home_intro_project_card_link_url',
            'label'        => __( 'Project Card Link URL', 'puk' ),
            'name'         => 'project_card_link_url',
            'type'         => 'text',
            'instructions' => __( 'Enter the URL for project card', 'puk' ),
            'required'     => 0,
            'placeholder'  => __( 'https://...', 'puk' ),
        ),

    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/home-intro',
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
    'description'         => __( 'Fields for Home Intro block', 'puk' ),
) );