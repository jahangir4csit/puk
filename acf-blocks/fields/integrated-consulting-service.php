<?php
/**
 * ACF Field Group: Integrated Consulting Service
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_integrated_consulting_service',
    'title' => __( 'Integrated Consulting Service Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_ics_main_heading',
            'label' => __( 'Main Heading', 'puk' ),
            'name' => 'main_heading',
            'type' => 'textarea',
            'instructions' => __( 'Enter the main heading (supports line breaks)', 'puk' ),
            'required' => 1,
            'rows' => 2,
            'default_value' => 'Integrated consulting  
service',
            'placeholder' => __( 'Enter main heading...', 'puk' ),
        ),
        array(
            'key' => 'field_ics_right_image',
            'label' => __( 'Right Side Image', 'puk' ),
            'name' => 'right_image',
            'type' => 'image',
            'instructions' => __( 'Upload the main image for right side', 'puk' ),
            'required' => 1,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_ics_section_1_heading',
            'label' => __( 'Section 1 Heading', 'puk' ),
            'name' => 'section_1_heading',
            'type' => 'text',
            'instructions' => __( 'First section heading', 'puk' ),
            'required' => 0,
            'default_value' => 'Lighting design according to PUK',
            'placeholder' => __( 'Enter section heading...', 'puk' ),
        ),
        array(
            'key' => 'field_ics_section_1_content',
            'label' => __( 'Section 1 Content', 'puk' ),
            'name' => 'section_1_content',
            'type' => 'wysiwyg',
            'instructions' => __( 'First section content (supports multiple paragraphs)', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
        ),
        array(
            'key' => 'field_ics_section_2_heading',
            'label' => __( 'Section 2 Heading', 'puk' ),
            'name' => 'section_2_heading',
            'type' => 'text',
            'instructions' => __( 'Second section heading', 'puk' ),
            'required' => 0,
            'default_value' => 'Step by step, towards the solution',
            'placeholder' => __( 'Enter section heading...', 'puk' ),
        ),
        array(
            'key' => 'field_ics_section_2_content',
            'label' => __( 'Section 2 Content', 'puk' ),
            'name' => 'section_2_content',
            'type' => 'wysiwyg',
            'instructions' => __( 'Second section content (supports multiple paragraphs)', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
        ),
        array(
            'key' => 'field_ics_steps',
            'label' => __( 'Steps', 'puk' ),
            'name' => 'steps',
            'type' => 'repeater',
            'instructions' => __( 'Add steps (numbers will be auto-generated)', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Step', 'puk' ),
            'min' => 0,
            'max' => 10,
            'sub_fields' => array(
                array(
                    'key' => 'field_ics_step_title',
                    'label' => __( 'Step Title', 'puk' ),
                    'name' => 'step_title',
                    'type' => 'text',
                    'required' => 1,
                    'placeholder' => __( 'Enter step title...', 'puk' ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/integrated-consulting-service',
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
    'description' => __( 'Fields for Integrated Consulting Service block', 'puk' ),
) );
