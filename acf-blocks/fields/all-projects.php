<?php
/**
 * ACF Field Group: All Projects
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_all_projects',
    'title' => __( 'All Projects Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_all_projects_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => __( 'Enter the description text for the left sidebar (line breaks supported)', 'puk' ),
            'required' => 0,
            'rows' => 6,
            'default_value' => 'Every project is a story, every light is a signature.
In our work, light is not just functional: it is language, atmosphere, identity.
This is our approach to design: each product is conceived as an integrated element within the space, enhancing volumes, perspectives, and landscapes.',
            'placeholder' => __( 'Enter description...', 'puk' ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/all-projects',
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
    'description' => __( 'Fields for All Projects block', 'puk' ),
) );
