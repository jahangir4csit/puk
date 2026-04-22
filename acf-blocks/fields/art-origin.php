<?php
/**
 * ACF Field Group: Art Origin Block
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'    => 'group_art_origin',
    'title'  => __( 'Art Origin Block', 'puk' ),
    'fields' => array(

        // Section Title
        array(
            'key'          => 'field_art_origin_section_title',
            'label'        => __( 'Section Title', 'puk' ),
            'name'         => 'section_title',
            'type'         => 'text',
            'instructions' => __( 'e.g. THE ORIGIN OF FORM -', 'puk' ),
            'required'     => 0,
            'placeholder'  => 'THE ORIGIN OF FORM -',
        ),

        // Groups (Repeater)
        array(
            'key'          => 'field_art_origin_groups',
            'label'        => __( 'Groups', 'puk' ),
            'name'         => 'groups',
            'type'         => 'repeater',
            'instructions' => __( 'Add groups (e.g. Product Sketches, FIERE)', 'puk' ),
            'required'     => 0,
            'min'          => 0,
            'max'          => 0,
            'layout'       => 'block',
            'button_label' => __( 'Add Group', 'puk' ),
            'sub_fields'   => array(

                // Group Title
                array(
                    'key'             => 'field_art_origin_group_title',
                    'label'           => __( 'Group Title', 'puk' ),
                    'name'            => 'group_title',
                    'type'            => 'text',
                    'instructions'    => __( 'e.g. Product Sketches', 'puk' ),
                    'required'        => 1,
                    'placeholder'     => 'Group Title',
                    'parent_repeater' => 'field_art_origin_groups',
                ),

                // Items (Repeater inside Groups)
                array(
                    'key'             => 'field_art_origin_items',
                    'label'           => __( 'Items', 'puk' ),
                    'name'            => 'items',
                    'type'            => 'repeater',
                    'instructions'    => __( 'Add items for this group', 'puk' ),
                    'required'        => 0,
                    'min'             => 0,
                    'max'             => 0,
                    'layout'          => 'table',
                    'button_label'    => __( 'Add Item', 'puk' ),
                    'parent_repeater' => 'field_art_origin_groups',
                    'sub_fields'      => array(

                        // Item Label
                        array(
                            'key'             => 'field_art_origin_item_label',
                            'label'           => __( 'Label', 'puk' ),
                            'name'            => 'item_label',
                            'type'            => 'text',
                            'instructions'    => __( 'e.g. Alder', 'puk' ),
                            'required'        => 1,
                            'placeholder'     => 'Item Name',
                            'parent_repeater' => 'field_art_origin_items',
                        ),

                        // Item ID (slug for JS targeting)
                        array(
                            'key'             => 'field_art_origin_item_id',
                            'label'           => __( 'ID / Slug', 'puk' ),
                            'name'            => 'item_id',
                            'type'            => 'text',
                            'instructions'    => __( 'Unique slug used for JS toggling (e.g. alder). No spaces.', 'puk' ),
                            'required'        => 1,
                            'placeholder'     => 'alder',
                            'parent_repeater' => 'field_art_origin_items',
                        ),

                        // Item Image
                        array(
                            'key'             => 'field_art_origin_item_image',
                            'label'           => __( 'Image', 'puk' ),
                            'name'            => 'item_image',
                            'type'            => 'image',
                            'instructions'    => __( 'Upload the image shown when this item is active', 'puk' ),
                            'required'        => 0,
                            'return_format'   => 'array',
                            'preview_size'    => 'thumbnail',
                            'library'         => 'all',
                            'parent_repeater' => 'field_art_origin_items',
                        ),

                    ),
                ),

            ),
        ),

    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/art-origin',
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
    'description'           => __( 'Fields for Art Origin block', 'puk' ),
) );
