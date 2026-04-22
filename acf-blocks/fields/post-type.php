<?php
/**
 * ACF Field Group: Post (Blog) Post Type Fields
 *
 * Custom fields for the default post type
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_post_type',
    'title' => __( 'Post Details', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_post_subtitle',
            'label' => __( 'Subtitle', 'puk' ),
            'name' => 'subtitle',
            'type' => 'textarea',
            'instructions' => __( 'Enter a subtitle for this post', 'puk' ),
            'required' => 0,
            'rows' => 3,
            'default_value' => '',
            'placeholder' => __( 'Enter subtitle...', 'puk' ),
            'maxlength' => '',
            'new_lines' => '', // No formatting
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
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
    'description' => __( 'Custom fields for post type', 'puk' ),
) );
