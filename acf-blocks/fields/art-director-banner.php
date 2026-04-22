<?php
/**
 * ACF Field Group: Art Director Banner Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'    => 'group_art_director_banner',
    'title'  => __( 'Art Director Banner Block', 'puk' ),
    'fields' => array(

        // Banner Image
        array(
            'key'           => 'field_art_director_banner_image',
            'label'         => __( 'Banner Image', 'puk' ),
            'name'          => 'banner_image',
            'type'          => 'image',
            'instructions'  => __( 'Upload the banner feature image', 'puk' ),
            'required'      => 0,
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'library'       => 'all',
        ),

        // Banner Title
        array(
            'key'          => 'field_art_director_banner_title',
            'label'        => __( 'Banner Title', 'puk' ),
            'name'         => 'banner_title',
            'type'         => 'textarea',
            'instructions' => __( 'Enter the banner title. Use a new line for line breaks.', 'puk' ),
            'required'     => 0,
            'rows'         => 3,
            'new_lines'    => 'br',
        ),

    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/art-director-banner',
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
    'description'           => __( 'Fields for Art Director Banner block', 'puk' ),
) );
