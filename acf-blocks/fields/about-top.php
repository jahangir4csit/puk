<?php
/**
 * ACF Field Group: About Top
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_about_top',
    'title' => __( 'About Top Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_abt_main_heading',
            'label' => __( 'Main Heading', 'puk' ),
            'name' => 'main_heading',
            'type' => 'textarea',
            'instructions' => __( 'Enter the main heading (supports line breaks)', 'puk' ),
            'required' => 1,
            'rows' => 3,
            'default_value' => 'Adding brilliance to your project by combining creativity, precision, and a passion for excellence.',
            'placeholder' => __( 'Enter main heading...', 'puk' ),
        ),
        array(
            'key' => 'field_abt_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'wysiwyg',
            'instructions' => __( 'Content below the heading (supports multiple paragraphs)', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'default_value' => 'Lighting has been our world since 1995.
We specialise in the study and creation
of lighting solutions for architectural
and outdoor applications.',
        ),
        array(
            'key' => 'field_abt_right_image',
            'label' => __( 'Right Side Image', 'puk' ),
            'name' => 'right_image',
            'type' => 'image',
            'instructions' => __( 'Upload the featured image for right side', 'puk' ),
            'required' => 1,
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
                'value' => 'acf/about-top',
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
    'description' => __( 'Fields for About Top block', 'puk' ),
) );
