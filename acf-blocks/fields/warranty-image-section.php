<?php
/**
 * ACF Field Group: Warranty Image Section
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_warranty_image_section',
    'title' => __( 'Warranty Image Section Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_wrnt_img_section_image',
            'label' => __( 'Section Image', 'puk' ),
            'name' => 'section_image',
            'type' => 'image',
            'instructions' => __( 'Upload the full-width section image', 'puk' ),
            'required' => 1,
            'return_format' => 'array',
            'preview_size' => 'large',
            'library' => 'all',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/warranty-image-section',
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
    'description' => __( 'Fields for Warranty Image Section block', 'puk' ),
) );
