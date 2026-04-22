<?php
/**
 * ACF Field Group: Warranty Top
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_warranty_top',
    'title' => __( 'Warranty Top Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_wrnt_top_heading',
            'label' => __( 'Heading', 'puk' ),
            'name' => 'heading',
            'type' => 'textarea',
            'instructions' => __( 'Enter the main heading (supports line breaks)', 'puk' ),
            'required' => 1,
            'rows' => 2,
            'default_value' => 'Light that
challenges time',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_wrnt_top_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => __( 'Content below the heading (supports line breaks)', 'puk' ),
            'required' => 0,
            'rows' => 7,
            'default_value' => 'Excellence is the common thread in every one
of our creations.
Our primary goalis to ensure high-quality
standards in every aspect of the product,
from the body to the light optics,
and up to the lighting efficiency.',
            'placeholder' => __( 'Enter description...', 'puk' ),
        ),
        array(
            'key' => 'field_wrnt_top_right_image',
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
                'value' => 'acf/warranty-top',
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
    'description' => __( 'Fields for Warranty Top block', 'puk' ),
) );
