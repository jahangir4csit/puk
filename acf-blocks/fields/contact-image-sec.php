<?php
/**
 * ACF Field Group: Contact Image Section
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_contact_image_sec',
    'title' => __( 'Contact Image Section Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_contact_img_heading',
            'label' => __( 'Section Heading', 'puk' ),
            'name' => 'section_heading',
            'type' => 'text',
            'instructions' => __( 'Enter the section heading', 'puk' ),
            'required' => 0,
            'default_value' => 'Our headquarter',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_contact_img_address',
            'label' => __( 'Address Text', 'puk' ),
            'name' => 'address_text',
            'type' => 'textarea',
            'instructions' => __( 'Enter address or description (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 5,
            'default_value' => 'PUK ITALIA GROUP srl
Via San Giorgio,
16 Lissone (MB) - ITALY',
            'placeholder' => __( 'Enter address...', 'puk' ),
        ),
        array(
            'key' => 'field_contact_img_image',
            'label' => __( 'Section Image', 'puk' ),
            'name' => 'section_image',
            'type' => 'image',
            'instructions' => __( 'Upload the section image', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact-image-sec',
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
    'description' => __( 'Fields for Contact Image Section block', 'puk' ),
) );
