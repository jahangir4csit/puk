<?php
/**
 * ACF Field Group: Contact Bottom (Newsletter)
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_contact_bottom',
    'title' => __( 'Contact Bottom Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_contact_bottom_heading',
            'label' => __( 'Section Heading', 'puk' ),
            'name' => 'section_heading',
            'type' => 'textarea',
            'instructions' => __( 'Enter the section heading (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 4,
            'default_value' => 'Subscribe to our newsletter to discover the latest news in outdoor lighting design.
Product news, case studies, and inspiration for your outdoor projects.',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_contact_bottom_shortcode',
            'label' => __( 'Newsletter Form Shortcode', 'puk' ),
            'name' => 'form_shortcode',
            'type' => 'text',
            'instructions' => __( 'Paste the Contact Form 7 shortcode here (e.g., [contact-form-7 id="456" title="Newsletter Form"])', 'puk' ),
            'required' => 1,
            'placeholder' => '[contact-form-7 id="456" title="Newsletter Form"]',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact-bottom',
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
    'description' => __( 'Fields for Contact Bottom block', 'puk' ),
) );
