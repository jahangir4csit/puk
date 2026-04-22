<?php
/**
 * ACF Field Group: Home Stories
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_home_stories',
    'title' => __( 'Home Stories Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_home_stories_section_label',
            'label' => __( 'Section Label', 'puk' ),
            'name' => 'section_label',
            'type' => 'text',
            'instructions' => __( 'Enter the section label/heading', 'puk' ),
            'required' => 0,
            'default_value' => 'Latest Stories -',
            'placeholder' => __( 'Latest Stories -', 'puk' ),
        ),
        array(
            'key' => 'field_home_stories_posts_per_page',
            'label' => __( 'Number of Stories', 'puk' ),
            'name' => 'posts_per_page',
            'type' => 'number',
            'instructions' => __( 'How many stories to display', 'puk' ),
            'required' => 0,
            'default_value' => 4,
            'placeholder' => '4',
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        array(
            'key' => 'field_home_stories_story_tag_field',
            'label' => __( 'Story Tag ACF Field Name', 'puk' ),
            'name' => 'story_tag_field',
            'type' => 'text',
            'instructions' => __( 'Enter the ACF field name to use for the story tag (e.g., story_category). This field should be added to the stories post type.', 'puk' ),
            'required' => 0,
            'default_value' => 'story_category',
            'placeholder' => __( 'story_category', 'puk' ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/home-stories',
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
    'description' => __( 'Fields for Home Stories block', 'puk' ),
) );
