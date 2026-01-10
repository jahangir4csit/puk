<?php
/**
 * ACF Blocks Registration
 *
 * Automatically registers all blocks from the block-template directory
 * Block Category: Puk
 * Textdomain: puk
 *
 * @package Puk
 */

/**
 * Register custom block category
 */
function puk_register_block_category( $categories ) {
    return array_merge(
        array(
            array(
                'slug'  => 'puk',
                'title' => __( 'Puk', 'puk' ),
                'icon'  => 'admin-site',
            ),
        ),
        $categories
    );
}
add_filter( 'block_categories_all', 'puk_register_block_category', 10, 1 );

/**
 * Recursively get all PHP block files from directory, excluding certain folders
 *
 * @param string $dir Directory to scan
 * @param array $exclude Directories to exclude
 * @return array Array of PHP file paths
 */
function puk_get_block_files( $dir, $exclude = array( 'fields' ) ) {
    $files = array();
    
    if ( ! is_dir( $dir ) ) {
        return $files;
    }
    
    $items = scandir( $dir );
    
    foreach ( $items as $item ) {
        if ( $item === '.' || $item === '..' ) {
            continue;
        }
        
        $path = $dir . '/' . $item;
        
        // Skip excluded directories
        if ( is_dir( $path ) && in_array( $item, $exclude ) ) {
            continue;
        }
        
        // Recursively scan subdirectories
        if ( is_dir( $path ) ) {
            $files = array_merge( $files, puk_get_block_files( $path, $exclude ) );
        }
        // Add PHP files (excluding README, STRUCTURE, QUICK-START files)
        elseif ( pathinfo( $path, PATHINFO_EXTENSION ) === 'php' ) {
            $files[] = $path;
        }
    }
    
    return $files;
}

/**
 * Initialize and register all ACF blocks
 */
function puk_acf_blocks_init() {
    // Check if ACF function exists
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    // Get all block files from the acf-blocks directory (including subdirectories)
    $block_dir = get_template_directory() . '/acf-blocks';
    $block_files = puk_get_block_files( $block_dir );

    if ( empty( $block_files ) ) {
        return;
    }

    foreach ( $block_files as $file ) {
        $block_name = basename( $file, '.php' );
        $block_title = ucwords( str_replace( array( '-', '_' ), ' ', $block_name ) );
        
        // Load the SVG icon content
        $svg_icon_path = get_template_directory() . '/assets/images/block-icon.svg';
        $block_icon = file_exists( $svg_icon_path )
            ? file_get_contents( $svg_icon_path )
            : '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="24" rx="4" fill="#0073aa"/></svg>';

        $acf_block = array(
            'name'              => $block_name,
            'title'             => $block_title,
            'description'       => sprintf( __( '%s block', 'puk' ), $block_title ),
            'category'          => 'puk',
            'icon'              => $block_icon,
            'keywords'          => array( 'puk', $block_name ),
            'mode'              => 'edit',
            'align'             => 'wide',
            'supports'          => array(
                'anchor'        => true,
                'jsx'           => true,
                'mode'          => false,
                'align'         => false,
                'customClassName' => true,
            ),
            'example'           => array(
                'attributes' => array(
                    'mode' => 'preview',
                    'data' => array(
                        'is_preview' => true
                    ),
                )
            ),
            'render_callback'   => function ( $block, $content = '', $is_preview = false, $post_id = 0 ) use ( $file ) {
                // Make variables available to block template
                $fields = get_fields();
                $block_id = 'block-' . $block['id'];
                $block_class = 'acf-block acf-block-' . str_replace( '_', '-', $block['name'] );
                
                if ( ! empty( $block['className'] ) ) {
                    $block_class .= ' ' . $block['className'];
                }
                
                if ( ! empty( $block['align'] ) ) {
                    $block_class .= ' align' . $block['align'];
                }
                
                // Include the block template
                include $file;
            },
        );

        acf_register_block_type( $acf_block );
    }
}
add_action( 'acf/init', 'puk_acf_blocks_init' );

/**
 * Load ACF field groups from block-template/fields directory
 */
function puk_acf_load_field_groups() {
    $fields_dir = get_template_directory() . '/acf-blocks/fields/';
    
    if ( is_dir( $fields_dir ) ) {
        $field_files = glob( $fields_dir . '*.php' );
        
        foreach ( $field_files as $field_file ) {
            include_once $field_file;
        }
    }
}
add_action( 'acf/init', 'puk_acf_load_field_groups' );
