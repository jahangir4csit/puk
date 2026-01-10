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
    'key' => 'group_home_intro',
    'title' => __( 'Home Intro Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_home_intro_content',
            'label' => __( 'Intro Content', 'puk' ),
            'name' => 'intro_content',
            'type' => 'wysiwyg',
            'instructions' => __( 'Enter the introduction content (left side)', 'puk' ),
            'required' => 1,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
        ),
        array(
            'key' => 'field_home_intro_product_card_image',
            'label' => __( 'Product Card Image', 'puk' ),
            'name' => 'product_card_image',
            'type' => 'image',
            'instructions' => __( 'Upload product card image (left side)', 'puk' ),
            'required' => 1,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_home_intro_product_card_link_text',
            'label' => __( 'Product Card Link Text', 'puk' ),
            'name' => 'product_card_link_text',
            'type' => 'text',
            'instructions' => __( 'Enter the link text for product card', 'puk' ),
            'required' => 0,
            'default_value' => 'Explore Products',
            'placeholder' => __( 'Explore Products', 'puk' ),
        ),
        array(
            'key' => 'field_home_intro_product_card_link_url',
            'label' => __( 'Product Card Link URL', 'puk' ),
            'name' => 'product_card_link_url',
            'type' => 'text',
            'instructions' => __( 'Enter the URL for product card', 'puk' ),
            'required' => 0,
            'placeholder' => __( 'https://...', 'puk' ),
        ),
        array(
            'key' => 'field_home_intro_project_card_image',
            'label' => __( 'Project Card Image', 'puk' ),
            'name' => 'project_card_image',
            'type' => 'image',
            'instructions' => __( 'Upload project card image (right side)', 'puk' ),
            'required' => 1,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_home_intro_project_card_link_text',
            'label' => __( 'Project Card Link Text', 'puk' ),
            'name' => 'project_card_link_text',
            'type' => 'text',
            'instructions' => __( 'Enter the link text for project card', 'puk' ),
            'required' => 0,
            'default_value' => 'Our Projects',
            'placeholder' => __( 'Our Projects', 'puk' ),
        ),
        array(
            'key' => 'field_home_intro_project_card_link_url',
            'label' => __( 'Project Card Link URL', 'puk' ),
            'name' => 'project_card_link_url',
            'type' => 'text',
            'instructions' => __( 'Enter the URL for project card', 'puk' ),
            'required' => 0,
            'placeholder' => __( 'https://...', 'puk' ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/home-intro',
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
    'description' => __( 'Fields for Home Intro block', 'puk' ),
) );
