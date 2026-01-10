<?php
/**
 * ACF Field Group: Download Products
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_download_products',
    'title' => __( 'Download Products Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_download_products_download_icon',
            'label' => __( 'Download Icon', 'puk' ),
            'name' => 'download_icon',
            'type' => 'image',
            'instructions' => __( 'Upload the download icon/SVG (shared for all products)', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
            'library' => 'all',
        ),
        array(
            'key' => 'field_download_products_product_sections',
            'label' => __( 'Product Sections', 'puk' ),
            'name' => 'product_sections',
            'type' => 'repeater',
            'instructions' => __( 'Add product sections (e.g., Products Literatures, Technical Tools, Other Brochure)', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Product Section', 'puk' ),
            'min' => 0,
            'max' => 10,
            'sub_fields' => array(
                array(
                    'key' => 'field_download_products_section_title',
                    'label' => __( 'Section Title', 'puk' ),
                    'name' => 'section_title',
                    'type' => 'text',
                    'required' => 1,
                    'placeholder' => __( 'Enter section title (e.g., Products Literatures)...', 'puk' ),
                ),
                array(
                    'key' => 'field_download_products_products',
                    'label' => __( 'Products', 'puk' ),
                    'name' => 'products',
                    'type' => 'repeater',
                    'instructions' => __( 'Add products for this section', 'puk' ),
                    'required' => 0,
                    'layout' => 'table',
                    'button_label' => __( 'Add Product', 'puk' ),
                    'min' => 0,
                    'max' => 50,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_download_products_product_image',
                            'label' => __( 'Product Image', 'puk' ),
                            'name' => 'product_image',
                            'type' => 'image',
                            'required' => 1,
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                        ),
                        array(
                            'key' => 'field_download_products_product_title',
                            'label' => __( 'Product Title', 'puk' ),
                            'name' => 'product_title',
                            'type' => 'text',
                            'required' => 1,
                            'placeholder' => __( 'Enter product title...', 'puk' ),
                        ),
                        array(
                            'key' => 'field_download_products_download_link',
                            'label' => __( 'Download Link', 'puk' ),
                            'name' => 'download_link',
                            'type' => 'text',
                            'required' => 0,
                            'placeholder' => __( 'https://...', 'puk' ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/download-products',
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
    'description' => __( 'Fields for Download Products block', 'puk' ),
) );
