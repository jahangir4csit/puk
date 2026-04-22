<?php
/**
 * ACF Field Group: About Perfection
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_about_perfection',
    'title' => __( 'About Perfection Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_abt_perf_heading',
            'label' => __( 'Section Heading', 'puk' ),
            'name' => 'section_heading',
            'type' => 'text',
            'instructions' => __( 'Enter the main section heading', 'puk' ),
            'required' => 1,
            'default_value' => 'ITALIAN ARCHITECTURAL LIGHT_',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_abt_perf_paragraph_1',
            'label' => __( 'First Paragraph', 'puk' ),
            'name' => 'paragraph_1',
            'type' => 'textarea',
            'instructions' => __( 'First paragraph content (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 5,
            'placeholder' => __( 'Enter first paragraph...', 'puk' ),
        ),
        array(
            'key' => 'field_abt_perf_paragraph_2',
            'label' => __( 'Second Paragraph', 'puk' ),
            'name' => 'paragraph_2',
            'type' => 'textarea',
            'instructions' => __( 'Second paragraph content (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 5,
            'placeholder' => __( 'Enter second paragraph...', 'puk' ),
        ),
        array(
            'key' => 'field_abt_perf_bottom_heading',
            'label' => __( 'Bottom Section Heading', 'puk' ),
            'name' => 'bottom_heading',
            'type' => 'textarea',
            'instructions' => __( 'Bottom heading (line breaks will be preserved)', 'puk' ),
            'required' => 0,
            'rows' => 3,
            'default_value' => 'We are ready
to create
the perfect light',
            'placeholder' => __( 'Enter bottom heading...', 'puk' ),
        ),
        array(
            'key' => 'field_abt_perf_bottom_image',
            'label' => __( 'Bottom Section Image', 'puk' ),
            'name' => 'bottom_image',
            'type' => 'image',
            'instructions' => __( 'Upload image for bottom section', 'puk' ),
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
                'value' => 'acf/about-perfection',
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
    'description' => __( 'Fields for About Perfection block', 'puk' ),
) );
