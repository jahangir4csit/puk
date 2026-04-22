<?php
/**
 * ACF Field Group: Contact Top
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_contact_top',
    'title' => __( 'Contact Top Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_contact_top_page_title',
            'label' => __( 'Page Title', 'puk' ),
            'name' => 'page_title',
            'type' => 'text',
            'instructions' => __( 'Enter the main page title', 'puk' ),
            'required' => 1,
            'default_value' => 'Contact',
            'placeholder' => __( 'Enter title...', 'puk' ),
        ),
        array(
            'key' => 'field_contact_top_subtitle',
            'label' => __( 'Subtitle', 'puk' ),
            'name' => 'subtitle',
            'type' => 'text',
            'instructions' => __( 'Enter the subtitle heading', 'puk' ),
            'required' => 0,
            'default_value' => 'We\'d love to hear from you.',
            'placeholder' => __( 'Enter subtitle...', 'puk' ),
        ),
        array(
            'key' => 'field_contact_top_intro_paragraph',
            'label' => __( 'Introduction Paragraph', 'puk' ),
            'name' => 'intro_paragraph',
            'type' => 'textarea',
            'instructions' => __( 'Introduction text (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'default_value' => 'Every project begins with a spark.
An idea, a vision, a question. And often... it all begins with a simple message.',
            'placeholder' => __( 'Enter introduction text...', 'puk' ),
        ),
        array(
            'key' => 'field_contact_top_list_items',
            'label' => __( 'List Items', 'puk' ),
            'name' => 'list_items',
            'type' => 'repeater',
            'instructions' => __( 'Add list items', 'puk' ),
            'required' => 0,
            'min' => 0,
            'max' => 10,
            'layout' => 'table',
            'button_label' => __( 'Add Item', 'puk' ),
            'sub_fields' => array(
                array(
                    'key' => 'field_contact_top_list_item',
                    'label' => __( 'List Item', 'puk' ),
                    'name' => 'list_item',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'placeholder' => __( 'Enter list item...', 'puk' ),
                ),
            ),
        ),
        array(
            'key' => 'field_contact_top_closing_paragraph',
            'label' => __( 'Closing Paragraph', 'puk' ),
            'name' => 'closing_paragraph',
            'type' => 'textarea',
            'instructions' => __( 'Closing text (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'default_value' => 'We\'re here to listen, advise and, if you wish, support you step by step in your projects.
Or rather: light after light.',
            'placeholder' => __( 'Enter closing text...', 'puk' ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact-top',
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
    'description' => __( 'Fields for Contact Top block', 'puk' ),
) );
