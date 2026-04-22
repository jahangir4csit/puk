<?php
/**
 * ACF Field Group: Contact Form
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_contact_form',
    'title' => __( 'Contact Form Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_contact_form_title',
            'label' => __( 'Form Title', 'puk' ),
            'name' => 'form_title',
            'type' => 'text',
            'instructions' => __( 'Enter the form section title', 'puk' ),
            'required' => 0,
            'default_value' => 'Contact us for...',
            'placeholder' => __( 'Enter title...', 'puk' ),
        ),
        array(
            'key' => 'field_contact_form_shortcode',
            'label' => __( 'Contact Form 7 Shortcode', 'puk' ),
            'name' => 'form_shortcode',
            'type' => 'text',
            'instructions' => __( 'Paste the Contact Form 7 shortcode here (e.g., [contact-form-7 id="123" title="Contact Form"])', 'puk' ),
            'required' => 1,
            'placeholder' => '[contact-form-7 id="123" title="Contact Form"]',
        ),
        array(
            'key' => 'field_contact_form_info_boxes',
            'label' => __( 'Contact Info Boxes', 'puk' ),
            'name' => 'contact_info_boxes',
            'type' => 'repeater',
            'instructions' => __( 'Add contact information boxes (phone, email, etc.)', 'puk' ),
            'required' => 0,
            'min' => 0,
            'max' => 5,
            'layout' => 'block',
            'button_label' => __( 'Add Info Box', 'puk' ),
            'sub_fields' => array(
                array(
                    'key' => 'field_contact_form_box_label',
                    'label' => __( 'Label', 'puk' ),
                    'name' => 'box_label',
                    'type' => 'text',
                    'instructions' => __( 'e.g., "Call us", "Email us"', 'puk' ),
                    'required' => 0,
                    'placeholder' => __( 'Enter label...', 'puk' ),
                ),
                array(
                    'key' => 'field_contact_form_box_value',
                    'label' => __( 'Value', 'puk' ),
                    'name' => 'box_value',
                    'type' => 'text',
                    'instructions' => __( 'e.g., phone number, email address', 'puk' ),
                    'required' => 0,
                    'placeholder' => __( 'Enter value...', 'puk' ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact-form',
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
    'description' => __( 'Fields for Contact Form block', 'puk' ),
) );
