<?php
/**
 * ACF Field Group: Download Top
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_download_top',
    'title' => __( 'Download Top Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_download_top_page_title',
            'label' => __( 'Page Title', 'puk' ),
            'name' => 'page_title',
            'type' => 'text',
            'instructions' => __( 'Enter the page title', 'puk' ),
            'required' => 1,
            'default_value' => 'Download',
            'placeholder' => __( 'Enter page title...', 'puk' ),
        ),
        array(
            'key' => 'field_download_top_heading',
            'label' => __( 'Heading', 'puk' ),
            'name' => 'heading',
            'type' => 'textarea',
            'instructions' => __( 'Enter the main heading', 'puk' ),
            'required' => 0,
            'rows' => 2,
            'default_value' => 'Light transforms every space, and every emotion.',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_download_top_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'wysiwyg',
            'instructions' => __( 'Enter the description content', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
        ),
        array(
            'key' => 'field_download_top_featured_image',
            'label' => __( 'Featured Image', 'puk' ),
            'name' => 'featured_image',
            'type' => 'image',
            'instructions' => __( 'Upload the featured image (left side)', 'puk' ),
            'required' => 1,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_download_top_content_title',
            'label' => __( 'Content Title', 'puk' ),
            'name' => 'content_title',
            'type' => 'text',
            'instructions' => __( 'Enter the content title (right side)', 'puk' ),
            'required' => 0,
            'default_value' => 'Collection Book 2025',
            'placeholder' => __( 'Enter content title...', 'puk' ),
        ),
        array(
            'key' => 'field_download_top_content_description',
            'label' => __( 'Content Description', 'puk' ),
            'name' => 'content_description',
            'type' => 'textarea',
            'instructions' => __( 'Enter the content description (right side)', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'placeholder' => __( 'Enter content description...', 'puk' ),
        ),
        array(
            'key' => 'field_download_top_download_icon',
            'label' => __( 'Download Icon', 'puk' ),
            'name' => 'download_icon',
            'type' => 'image',
            'instructions' => __( 'Upload the download icon/SVG', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
            'library' => 'all',
        ),
        array(
            'key' => 'field_download_top_download_link',
            'label' => __( 'Download Link', 'puk' ),
            'name' => 'download_link',
            'type' => 'text',
            'instructions' => __( 'Enter the download file URL', 'puk' ),
            'required' => 0,
            'placeholder' => __( 'https://...', 'puk' ),
        ),
        array(
            'key' => 'field_download_top_download_button_text',
            'label' => __( 'Download Button Text', 'puk' ),
            'name' => 'download_button_text',
            'type' => 'text',
            'instructions' => __( 'Enter the button text', 'puk' ),
            'required' => 0,
            'default_value' => 'Download',
            'placeholder' => __( 'Download', 'puk' ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/download-top',
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
    'description' => __( 'Fields for Download Top block', 'puk' ),
) );
