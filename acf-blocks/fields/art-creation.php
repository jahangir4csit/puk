<?php
/**
 * ACF Field Group: Art Creation Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'    => 'group_art_creation',
    'title'  => __( 'Art Creation Block', 'puk' ),
    'fields' => array(

        // Section Title
        array(
            'key'          => 'field_art_creation_section_title',
            'label'        => __( 'Section Title', 'puk' ),
            'name'         => 'section_title',
            'type'         => 'text',
            'instructions' => __( 'e.g. products creations -', 'puk' ),
            'required'     => 0,
            'placeholder'  => 'products creations -',
        ),

        // Product Families (taxonomy select2)
        array(
            'key'           => 'field_art_creation_product_families',
            'label'         => __( 'Product Families', 'puk' ),
            'name'          => 'product_families',
            'type'          => 'taxonomy',
            'instructions'  => __( 'Select one or more product families to display as links', 'puk' ),
            'required'      => 0,
            'taxonomy'      => 'product-family',
            'field_type'    => 'multi_select',  // renders as Select2
            'allow_null'    => 1,
            'add_term'      => 0,
            'save_terms'    => 0,
            'load_terms'    => 0,
            'return_format' => 'object',        // returns full WP_Term objects
            'multiple'      => 1,
        ),

    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/art-creation',
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
    'description'           => __( 'Fields for Art Creation block', 'puk' ),
) );
