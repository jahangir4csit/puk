<?php
/**
 * ACF Field Group: Art Description Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'    => 'group_art_description',
    'title'  => __( 'Art Description Block', 'puk' ),
    'fields' => array(

        // Section Label
        array(
            'key'          => 'field_art_description_section_label',
            'label'        => __( 'Section Label', 'puk' ),
            'name'         => 'section_label',
            'type'         => 'text',
            'instructions' => __( 'e.g. ART DIRECTION -', 'puk' ),
            'required'     => 0,
            'placeholder'  => 'ART DIRECTION -',
        ),

        // Main Heading
        array(
            'key'          => 'field_art_description_main_heading',
            'label'        => __( 'Main Heading', 'puk' ),
            'name'         => 'main_heading',
            'type'         => 'textarea',
            'instructions' => __( 'Enter the main heading text', 'puk' ),
            'required'     => 0,
            'rows'         => 4,
            'new_lines'    => 'br',
        ),

        // Description Content
        array(
            'key'          => 'field_art_description_content',
            'label'        => __( 'Description Content', 'puk' ),
            'name'         => 'description_content',
            'type'         => 'wysiwyg',
            'instructions' => __( 'Enter the description article content', 'puk' ),
            'required'     => 0,
            'tabs'         => 'all',
            'toolbar'      => 'full',
            'media_upload' => 1,
            'delay'        => 0,
        ),

        // Side Image
        array(
            'key'           => 'field_art_description_side_image',
            'label'         => __( 'Side Image', 'puk' ),
            'name'          => 'side_image',
            'type'          => 'image',
            'instructions'  => __( 'Upload the right-side image', 'puk' ),
            'required'      => 0,
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'library'       => 'all',
        ),

    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/art-description',
            ),
        ),
    ),
    'menu_order'            => 0,
    'position'              => 'normal',
    'style'                 => 'default',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'        => '',
    'active'                => true,
    'description'           => __( 'Fields for Art Description block', 'puk' ),
) );
