<?php
/**
 * ACF Field Group: Example Block
 * 
 * This file registers ACF fields for the example-block
 * Copy this file and rename it to match your block name
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_example_block',
    'title' => __( 'Example Block Fields', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_example_heading',
            'label' => __( 'Heading', 'puk' ),
            'name' => 'heading',
            'type' => 'text',
            'instructions' => __( 'Enter the main heading for this section', 'puk' ),
            'required' => 0,
            'default_value' => '',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_example_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => __( 'Enter the description text', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'default_value' => '',
            'placeholder' => __( 'Enter description...', 'puk' ),
        ),
        array(
            'key' => 'field_example_image',
            'label' => __( 'Image', 'puk' ),
            'name' => 'image',
            'type' => 'image',
            'instructions' => __( 'Select an image for this section', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_example_items',
            'label' => __( 'Items', 'puk' ),
            'name' => 'items',
            'type' => 'repeater',
            'instructions' => __( 'Add items to display', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Item', 'puk' ),
            'sub_fields' => array(
                array(
                    'key' => 'field_example_item_title',
                    'label' => __( 'Title', 'puk' ),
                    'name' => 'title',
                    'type' => 'text',
                    'required' => 0,
                    'placeholder' => __( 'Enter item title...', 'puk' ),
                ),
                array(
                    'key' => 'field_example_item_description',
                    'label' => __( 'Description', 'puk' ),
                    'name' => 'description',
                    'type' => 'textarea',
                    'required' => 0,
                    'rows' => 3,
                    'placeholder' => __( 'Enter item description...', 'puk' ),
                ),
                array(
                    'key' => 'field_example_item_icon',
                    'label' => __( 'Icon', 'puk' ),
                    'name' => 'icon',
                    'type' => 'image',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/example-block',
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
    'description' => __( 'Fields for Example Block', 'puk' ),
) );
