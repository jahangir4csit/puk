<?php
/**
 * ACF Field Group: About Bottom
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_about_bottom',
    'title' => __( 'About Bottom Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_abt_btm_left_content',
            'label' => __( 'Left Side Content', 'puk' ),
            'name' => 'left_content',
            'type' => 'textarea',
            'instructions' => __( 'Content for left side (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'placeholder' => __( 'Enter left content...', 'puk' ),
        ),
        array(
            'key' => 'field_abt_btm_right_boxes',
            'label' => __( 'Right Side Content Boxes', 'puk' ),
            'name' => 'right_boxes',
            'type' => 'repeater',
            'instructions' => __( 'Add content boxes for right side (recommended: 2)', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Box', 'puk' ),
            'min' => 0,
            'max' => 4,
            'sub_fields' => array(
                array(
                    'key' => 'field_abt_btm_box_content',
                    'label' => __( 'Box Content', 'puk' ),
                    'name' => 'box_content',
                    'type' => 'textarea',
                    'instructions' => __( 'Content for this box (line breaks supported)', 'puk' ),
                    'required' => 1,
                    'rows' => 4,
                    'placeholder' => __( 'Enter box content...', 'puk' ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/about-bottom',
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
    'description' => __( 'Fields for About Bottom block', 'puk' ),
) );
