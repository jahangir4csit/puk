<?php
/**
 * ACF Field Group: Home Matter
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_home_matter',
    'title' => __( 'Home Matter Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_home_matter_heading',
            'label' => __( 'Heading', 'puk' ),
            'name' => 'heading',
            'type' => 'text',
            'instructions' => __( 'Enter the main heading', 'puk' ),
            'required' => 1,
            'default_value' => 'Made to matter',
            'placeholder' => __( 'Made to matter', 'puk' ),
        ),
        array(
            'key' => 'field_home_matter_main_content',
            'label' => __( 'Main Content', 'puk' ),
            'name' => 'main_content',
            'type' => 'wysiwyg',
            'instructions' => __( 'Enter the main content (left side)', 'puk' ),
            'required' => 1,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
        ),
        array(
            'key' => 'field_home_matter_content_items',
            'label' => __( 'Content Items', 'puk' ),
            'name' => 'content_items',
            'type' => 'repeater',
            'instructions' => __( 'Add content items for the right side (usually 2 items)', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Content Item', 'puk' ),
            'sub_fields' => array(
                array(
                    'key' => 'field_home_matter_item_text',
                    'label' => __( 'Text', 'puk' ),
                    'name' => 'text',
                    'type' => 'textarea',
                    'instructions' => __( 'Enter the text content. Use <br /> for line breaks.', 'puk' ),
                    'required' => 1,
                    'rows' => 6,
                    'placeholder' => __( 'Enter text...', 'puk' ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/home-matter',
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
    'description' => __( 'Fields for Home Matter block', 'puk' ),
) );
