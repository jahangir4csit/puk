<?php
/**
 * ACF Field Group: Download Stories
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_download_stories',
    'title' => __( 'Download Stories Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_download_stories_section_title',
            'label' => __( 'Section Title', 'puk' ),
            'name' => 'section_title',
            'type' => 'text',
            'instructions' => __( 'Enter the section title', 'puk' ),
            'required' => 0,
            'default_value' => 'Stories',
            'placeholder' => __( 'Enter section title...', 'puk' ),
        ),
        array(
            'key' => 'field_download_stories_download_box',
            'label' => __( 'Download Box', 'puk' ),
            'name' => 'download_box',
            'type' => 'repeater',
            'instructions' => __( 'Add download boxes with stories', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Download Box', 'puk' ),
            'min' => 0,
            'max' => 20,
            'sub_fields' => array(
                array(
                    'key' => 'field_download_box_image',
                    'label' => __( 'Image', 'puk' ),
                    'name' => 'image',
                    'type' => 'image',
                    'required' => 1,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_download_box_title',
                    'label' => __( 'Title', 'puk' ),
                    'name' => 'title',
                    'type' => 'text',
                    'required' => 1,
                    'placeholder' => __( 'Enter title...', 'puk' ),
                ),
                array(
                    'key' => 'field_download_box_description',
                    'label' => __( 'Description', 'puk' ),
                    'name' => 'description',
                    'type' => 'textarea',
                    'required' => 0,
                    'rows' => 4,
                    'placeholder' => __( 'Enter description...', 'puk' ),
                ),
                array(
                    'key' => 'field_download_box_download_url',
                    'label' => __( 'Download URL', 'puk' ),
                    'name' => 'download_url',
                    'type' => 'text',
                    'required' => 0,
                    'placeholder' => __( 'https://...', 'puk' ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/download-stories',
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
    'description' => __( 'Fields for Download Stories block', 'puk' ),
) );
