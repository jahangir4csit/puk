<?php
/**
 * ACF Field Group: Single Project
 *
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_single_project',
    'title' => __( 'Project Details', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_project_opera',
            'label' => __( 'Opera', 'puk' ),
            'name' => 'opera',
            'type' => 'text',
            'instructions' => __( 'Enter the opera/category type', 'puk' ),
            'required' => 0,
            'placeholder' => __( 'e.g., Urban Greenery', 'puk' ),
        ),
        array(
            'key' => 'field_project_place',
            'label' => __( 'Place', 'puk' ),
            'name' => 'place',
            'type' => 'text',
            'instructions' => __( 'Enter the project location', 'puk' ),
            'required' => 0,
            'placeholder' => __( 'e.g., Muscat, Oman', 'puk' ),
        ),
        array(
            'key' => 'field_project_year',
            'label' => __( 'Year', 'puk' ),
            'name' => 'year',
            'type' => 'text',
            'instructions' => __( 'Enter the project year', 'puk' ),
            'required' => 0,
            'placeholder' => __( 'e.g., 2023', 'puk' ),
        ),
        array(
            'key' => 'field_project_architects',
            'label' => __( 'Architects', 'puk' ),
            'name' => 'architects',
            'type' => 'text',
            'instructions' => __( 'Enter the architects name', 'puk' ),
            'required' => 0,
            'placeholder' => __( 'Enter architects name...', 'puk' ),
        ),
        array(
            'key' => 'field_project_description',
            'label' => __( 'Project Description', 'puk' ),
            'name' => 'project_description',
            'type' => 'wysiwyg',
            'instructions' => __( 'Enter additional project description/details', 'puk' ),
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_project_gallery',
            'label' => __( 'Project Gallery', 'puk' ),
            'name' => 'project_gallery',
            'type' => 'gallery',
            'instructions' => __( 'Upload project images for the gallery section', 'puk' ),
            'required' => 0,
            'return_format' => 'array',
            'preview_size' => 'medium',
            'insert' => 'append',
            'library' => 'all',
            'min' => 0,
            'max' => '',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => 'jpg,jpeg,png,webp',
        ),
        array(
            'key'          => 'field_project_media_gallery',
            'label'        => __( 'Image and Video Gallery', 'puk' ),
            'name'         => 'image_video_gallery',
            'type'         => 'repeater',
            'instructions' => __( 'Add images or videos (uploaded, YouTube, or Vimeo)', 'puk' ),
            'required'     => 0,
            'min'          => 0,
            'max'          => '',
            'layout'       => 'block',
            'button_label' => __( 'Add Media Item', 'puk' ),
            'sub_fields'   => array(

                // ── STEP 1: Radio — Image or Video ──────────────────────────
                array(
                    'key'           => 'field_media_type',
                    'label'         => __( 'Media Type', 'puk' ),
                    'name'          => 'media_type',
                    'type'          => 'radio',
                    'instructions'  => __( 'Choose whether this item is an image or a video', 'puk' ),
                    'required'      => 1,
                    'choices'       => array(
                        'image' => __( 'Image', 'puk' ),
                        'video' => __( 'Video', 'puk' ),
                    ),
                    'default_value' => 'image',
                    'layout'        => 'horizontal',
                    'return_format' => 'value',
                ),

                // ── STEP 2: Video Source — only when Video is selected ──────
                array(
                    'key'               => 'field_media_video_source',
                    'label'             => __( 'Video Source', 'puk' ),
                    'name'              => 'media_video_source',
                    'type'              => 'select',
                    'instructions'      => __( 'Choose the video source', 'puk' ),
                    'required'          => 0,
                    'choices'           => array(
                        'video_youtube' => __( 'YouTube', 'puk' ),
                        'video_vimeo'   => __( 'Vimeo', 'puk' ),
                        'video_upload'  => __( 'Local / Internal', 'puk' ),
                    ),
                    'default_value'     => 'video_youtube',
                    'ui'                => 1,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field'    => 'field_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ),
                        ),
                    ),
                ),

                // ── Image field ─────────────────────────────────────────────
                array(
                    'key'               => 'field_media_image',
                    'label'             => __( 'Image', 'puk' ),
                    'name'              => 'media_image',
                    'type'              => 'image',
                    'instructions'      => __( 'Upload an image', 'puk' ),
                    'required'          => 0,
                    'return_format'     => 'array',
                    'preview_size'      => 'medium',
                    'library'           => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field'    => 'field_media_type',
                                'operator' => '==',
                                'value'    => 'image',
                            ),
                        ),
                    ),
                ),

                // ── Local video file ─────────────────────────────────────────
                array(
                    'key'               => 'field_media_video_upload',
                    'label'             => __( 'Video File', 'puk' ),
                    'name'              => 'media_video_upload',
                    'type'              => 'file',
                    'instructions'      => __( 'Upload a video file (mp4, webm, etc.)', 'puk' ),
                    'required'          => 0,
                    'return_format'     => 'array',
                    'library'           => 'all',
                    'mime_types'        => 'mp4,webm,ogg,mov',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field'    => 'field_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ),
                            array(
                                'field'    => 'field_media_video_source',
                                'operator' => '==',
                                'value'    => 'video_upload',
                            ),
                        ),
                    ),
                ),

                // ── Video thumbnail (for all video types) ────────────────────
                array(
                    'key'               => 'field_media_video_thumbnail',
                    'label'             => __( 'Video Thumbnail Image', 'puk' ),
                    'name'              => 'media_video_thumbnail',
                    'type'              => 'image',
                    'instructions'      => __( 'Upload a thumbnail image to display for this video in the gallery', 'puk' ),
                    'required'          => 0,
                    'return_format'     => 'array',
                    'preview_size'      => 'medium',
                    'library'           => 'all',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field'    => 'field_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ),
                        ),
                    ),
                ),

                // ── YouTube / Vimeo URL ──────────────────────────────────────
                array(
                    'key'               => 'field_media_video_url',
                    'label'             => __( 'Video URL', 'puk' ),
                    'name'              => 'media_video_url',
                    'type'              => 'url',
                    'instructions'      => __( 'Paste the YouTube or Vimeo video URL', 'puk' ),
                    'required'          => 0,
                    'placeholder'       => __( 'https://www.youtube.com/watch?v=... or https://vimeo.com/...', 'puk' ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field'    => 'field_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ),
                            array(
                                'field'    => 'field_media_video_source',
                                'operator' => '==',
                                'value'    => 'video_youtube',
                            ),
                        ),
                        array(
                            array(
                                'field'    => 'field_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ),
                            array(
                                'field'    => 'field_media_video_source',
                                'operator' => '==',
                                'value'    => 'video_vimeo',
                            ),
                        ),
                    ),
                ),

            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'project',
            ),
        ),
    ),
    'menu_order'          => 0,
    'position'            => 'normal',
    'style'               => 'default',
    'label_placement'     => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'      => '',
    'active'              => true,
    'description'         => __( 'Fields for Project post type', 'puk' ),
) );