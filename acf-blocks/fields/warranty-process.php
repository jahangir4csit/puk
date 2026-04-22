<?php
/**
 * ACF Field Group: Warranty Process
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_warranty_process',
    'title' => __( 'Warranty Process Block', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_wrnt_proc_left_heading',
            'label' => __( 'Left Section Heading', 'puk' ),
            'name' => 'left_heading',
            'type' => 'text',
            'instructions' => __( 'Enter the heading for left section', 'puk' ),
            'required' => 1,
            'default_value' => 'Powder coating process',
            'placeholder' => __( 'Enter heading...', 'puk' ),
        ),
        array(
            'key' => 'field_wrnt_proc_left_content',
            'label' => __( 'Left Section Content', 'puk' ),
            'name' => 'left_content',
            'type' => 'wysiwyg',
            'instructions' => __( 'Content for left section (supports multiple paragraphs)', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'default_value' => 'Resistance to chemical and atmospherica gents.Before painting,
all PUK products undergo a chromic passivation treatment using 
trivalent chromium.

This cycle allows to favor the anchoring of the next layer of paint, 
to greatly improve the corrosion resistance and to preserve the 
electrical properties of the part.

Subsequently, epoxy primers and anticorrosive primers are 
applied to the part, to increase its resistance to chemicals and 
give excellent paint adhesion and corrosion protection properties.',
        ),
        array(
            'key' => 'field_wrnt_proc_middle_content',
            'label' => __( 'Middle Section Content', 'puk' ),
            'name' => 'middle_content',
            'type' => 'wysiwyg',
            'instructions' => __( 'Content for middle section (supports multiple paragraphs)', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'default_value' => 'The final coating is made 
with powder coating, 
which is better than liquid 
paint in terms of hardness.

The components are 
coated with thermosetting 
powder based on synthetic 
resins (epoxy powder as a 
first base coat and 
polyester as a finish), 
which adheres by electrostatic effect, 
and then passed 
into an oven where, thanks 
to the temperature of 200 
degrees, the paint first
melts and then it cures to 
form an adherent layer.',
        ),
        array(
            'key' => 'field_wrnt_proc_right_content',
            'label' => __( 'Right Section Content', 'puk' ),
            'name' => 'right_content',
            'type' => 'wysiwyg',
            'instructions' => __( 'Content for right section (supports multiple paragraphs)', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'delay' => 0,
            'default_value' => 'The preparation process for 
painting takes place in 
multiple stages designed 
for the best resistance of 
die-cast aluminum to 
atmospheric agents.
The innovative treatment
with specific 
nanotechnological 
compounds allows to 
increase the resistance to 
humidity and corrosion by 
aggressive, chemical and 
atmospheric agents by 
10-15%.
Corrosion test in artificial 
atmospheres: all PUK 
products pass the salt 
spray test at 1500 hours.',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/warranty-process',
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
    'description' => __( 'Fields for Warranty Process block', 'puk' ),
) );
