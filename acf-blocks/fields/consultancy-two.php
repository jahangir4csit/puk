<?php
/**
 * ACF Field Group: Consultancy Two
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_consultancy_two',
    'title' => __( 'Consultancy Two Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_cons_two_left_image',
            'label' => __( 'Left Image', 'puk' ),
            'name' => 'left_image',
            'type' => 'image',
            'instructions' => __( 'Upload the left side image', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_cons_two_right_image',
            'label' => __( 'Right Image', 'puk' ),
            'name' => 'right_image',
            'type' => 'image',
            'instructions' => __( 'Upload the right side image', 'puk' ),
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
                'value' => 'acf/consultancy-two',
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
    'description' => __( 'Fields for Consultancy Two block - Image gallery', 'puk' ),
) );
