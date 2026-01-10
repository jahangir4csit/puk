<?php
/**
 * ACF Field Group: About Timeline
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_about_timeline',
    'title' => __( 'About Timeline Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_abt_timeline_heading',
            'label' => __( 'Section Heading', 'puk' ),
            'name' => 'section_heading',
            'type' => 'text',
            'instructions' => __( 'Enter the main section heading', 'puk' ),
            'required' => 1,
            'default_value' => 'THE JOURNEY OF LIGHT -',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_abt_timeline_slides',
            'label' => __( 'Timeline Slides', 'puk' ),
            'name' => 'timeline_slides',
            'type' => 'repeater',
            'instructions' => __( 'Add timeline slides (each slide represents a period)', 'puk' ),
            'required' => 0,
            'layout' => 'block',
            'button_label' => __( 'Add Slide', 'puk' ),
            'min' => 0,
            'max' => 20,
            'sub_fields' => array(
                array(
                    'key' => 'field_abt_timeline_year',
                    'label' => __( 'Year', 'puk' ),
                    'name' => 'year',
                    'type' => 'text',
                    'instructions' => __( 'Main year for this timeline period', 'puk' ),
                    'required' => 1,
                    'placeholder' => __( 'e.g., 1986', 'puk' ),
                    'wrapper' => array(
                        'width' => '30',
                    ),
                ),
                array(
                    'key' => 'field_abt_timeline_title',
                    'label' => __( 'Title', 'puk' ),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => __( 'Title for this period', 'puk' ),
                    'required' => 1,
                    'placeholder' => __( 'e.g., THE TURNING POINT', 'puk' ),
                    'wrapper' => array(
                        'width' => '70',
                    ),
                ),
                array(
                    'key' => 'field_abt_timeline_description',
                    'label' => __( 'Description', 'puk' ),
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => __( 'Description text for this period (line breaks supported)', 'puk' ),
                    'required' => 0,
                    'rows' => 4,
                    'placeholder' => __( 'Enter description...', 'puk' ),
                ),
                array(
                    'key' => 'field_abt_timeline_milestones',
                    'label' => __( 'Milestones', 'puk' ),
                    'name' => 'milestones',
                    'type' => 'repeater',
                    'instructions' => __( 'Add milestone boxes (recommended: 2 per slide)', 'puk' ),
                    'required' => 0,
                    'layout' => 'table',
                    'button_label' => __( 'Add Milestone', 'puk' ),
                    'min' => 0,
                    'max' => 4,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_abt_timeline_milestone_title',
                            'label' => __( 'Milestone Title', 'puk' ),
                            'name' => 'milestone_title',
                            'type' => 'text',
                            'required' => 1,
                            'placeholder' => __( 'e.g., WE STARTED', 'puk' ),
                        ),
                        array(
                            'key' => 'field_abt_timeline_milestone_year',
                            'label' => __( 'Milestone Year', 'puk' ),
                            'name' => 'milestone_year',
                            'type' => 'text',
                            'required' => 1,
                            'placeholder' => __( 'e.g., 1967', 'puk' ),
                        ),
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_abt_timeline_next_arrow',
            'label' => __( 'Next Arrow Image', 'puk' ),
            'name' => 'next_arrow_image',
            'type' => 'image',
            'instructions' => __( 'Upload navigation arrow for next button', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
            'library' => 'all',
        ),
        array(
            'key' => 'field_abt_timeline_prev_arrow',
            'label' => __( 'Previous Arrow Image', 'puk' ),
            'name' => 'prev_arrow_image',
            'type' => 'image',
            'instructions' => __( 'Upload navigation arrow for previous button', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
            'library' => 'all',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/about-timeline',
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
    'description' => __( 'Fields for About Timeline block with Swiper slider', 'puk' ),
) );
