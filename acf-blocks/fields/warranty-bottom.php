<?php
/**
 * ACF Field Group: Warranty Bottom
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_warranty_bottom',
    'title' => __( 'Warranty Bottom Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_wrnt_btm_boxes',
            'label' => __( 'Boxes', 'puk' ),
            'name' => 'boxes',
            'type' => 'repeater',
            'instructions' => __( 'Add information boxes (typically 2 boxes)', 'puk' ),
            'required' => 1,
            'layout' => 'block',
            'button_label' => __( 'Add Box', 'puk' ),
            'min' => 1,
            'max' => 10,
            'sub_fields' => array(
                array(
                    'key' => 'field_wrnt_btm_box_icon',
                    'label' => __( 'Icon', 'puk' ),
                    'name' => 'icon',
                    'type' => 'image',
                    'instructions' => __( 'Upload box icon image', 'puk' ),
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_wrnt_btm_box_heading',
                    'label' => __( 'Heading', 'puk' ),
                    'name' => 'heading',
                    'type' => 'textarea',
                    'instructions' => __( 'Box heading (supports line breaks)', 'puk' ),
                    'required' => 1,
                    'rows' => 2,
                    'placeholder' => __( 'Enter box heading...', 'puk' ),
                ),
                array(
                    'key' => 'field_wrnt_btm_box_content',
                    'label' => __( 'Content', 'puk' ),
                    'name' => 'content',
                    'type' => 'wysiwyg',
                    'instructions' => __( 'Box content (supports multiple paragraphs)', 'puk' ),
                    'required' => 0,
                    'tabs' => 'all',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 0,
                ),
            ),
        ),
        array(
            'key' => 'field_wrnt_btm_right_image',
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
                'value' => 'acf/warranty-bottom',
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
    'description' => __( 'Fields for Warranty Bottom block', 'puk' ),
) );
