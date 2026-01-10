<?php
/**
 * ACF Field Group: Consultancy Three
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_consultancy_three',
    'title' => __( 'Consultancy Three Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_cons_three_top_heading',
            'label' => __( 'Top Section Heading', 'puk' ),
            'name' => 'top_heading',
            'type' => 'text',
            'instructions' => __( 'Enter the top section heading', 'puk' ),
            'required' => 0,
            'default_value' => '3D Rendering: a crystal clear vision',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_cons_three_top_content',
            'label' => __( 'Top Section Content', 'puk' ),
            'name' => 'top_content',
            'type' => 'textarea',
            'instructions' => __( 'Enter the top section content (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'placeholder' => __( 'Enter content...', 'puk' ),
        ),
        array(
            'key' => 'field_cons_three_boxes',
            'label' => __( 'Bottom Content Boxes', 'puk' ),
            'name' => 'bottom_boxes',
            'type' => 'repeater',
            'instructions' => __( 'Add content boxes for bottom section (recommended: 3)', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Box', 'puk' ),
            'min' => 0,
            'max' => 6,
            'sub_fields' => array(
                array(
                    'key' => 'field_cons_three_box_heading',
                    'label' => __( 'Box Heading', 'puk' ),
                    'name' => 'box_heading',
                    'type' => 'text',
                    'instructions' => __( 'Heading for this box (optional)', 'puk' ),
                    'required' => 0,
                    'placeholder' => __( 'Enter heading...', 'puk' ),
                ),
                array(
                    'key' => 'field_cons_three_box_subheading',
                    'label' => __( 'Box Subheading', 'puk' ),
                    'name' => 'box_subheading',
                    'type' => 'textarea',
                    'instructions' => __( 'Subheading/content for this box (line breaks supported)', 'puk' ),
                    'required' => 0,
                    'rows' => 3,
                    'placeholder' => __( 'Enter subheading...', 'puk' ),
                ),
                array(
                    'key' => 'field_cons_three_box_content',
                    'label' => __( 'Box Content', 'puk' ),
                    'name' => 'box_content',
                    'type' => 'textarea',
                    'instructions' => __( 'Main content for this box (line breaks supported)', 'puk' ),
                    'required' => 0,
                    'rows' => 4,
                    'placeholder' => __( 'Enter content...', 'puk' ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/consultancy-three',
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
    'description' => __( 'Fields for Consultancy Three block', 'puk' ),
) );
