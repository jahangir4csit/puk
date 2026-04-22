<?php
/**
 * ACF Field Group: Story Gallery Popup Block
 *
 * Note: This block is a static popup structure that works with gallery blocks.
 * No fields are required as it's triggered by JavaScript.
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_story_gallary_popup',
    'title' => __( 'Story Gallery Popup Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_story_popup_note',
            'label' => __( 'Note', 'puk' ),
            'name' => 'popup_note',
            'type' => 'message',
            'message' => __( 'This block provides the popup/lightbox structure for gallery images. It is triggered automatically by JavaScript when gallery images are clicked. No configuration needed.', 'puk' ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/story-gallary-popup',
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
    'description' => __( 'Popup structure for gallery images (no fields required)', 'puk' ),
) );
