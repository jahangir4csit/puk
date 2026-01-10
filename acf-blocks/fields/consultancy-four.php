<?php
/**
 * ACF Field Group: Consultancy Four
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_consultancy_four',
    'title' => __( 'Consultancy Four Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_cons_four_gallery',
            'label' => __( 'Image Gallery', 'puk' ),
            'name' => 'image_gallery',
            'type' => 'gallery',
            'instructions' => __( 'Upload images for the gallery', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'insert' => 'append',
            'library' => 'all',
            'min' => 0,
            'max' => 50,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/consultancy-four',
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
    'description' => __( 'Fields for Consultancy Four block - Image gallery', 'puk' ),
) );
