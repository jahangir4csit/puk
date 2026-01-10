<?php
/**
 * Autoloader for Custom Post Types and Taxonomies
 * 
 * This file automatically loads all custom post types and taxonomies
 * from their respective directories, providing a clean modular structure.
 * 
 * @package PUK
 */

/**
 * Load all PHP files from a directory
 * 
 * @param string $directory Directory path relative to this file
 * @return void
 */
function puk_autoload_files($directory) {
    $dir_path = __DIR__ . '/' . $directory;
    
    // Check if directory exists
    if (!is_dir($dir_path)) {
        return;
    }
    
    // Get all PHP files in the directory
    $files = glob($dir_path . '/*.php');
    
    if (!empty($files)) {
        foreach ($files as $file) {
            require_once $file;
        }
    }
}

// Load all custom post types
puk_autoload_files('post-types');

// Load all custom taxonomies
puk_autoload_files('taxonomies');

/**
 * Flush rewrite rules on theme activation or when files are updated
 */
function puk_flush_rewrite_rules() {
    // Ensure all custom post types and taxonomies are registered
    if (function_exists('puk_woocommerce_style_rewrite_rules')) {
        puk_woocommerce_style_rewrite_rules();
    }
    if (function_exists('puk_product_rewrite_rules')) {
        puk_product_rewrite_rules();
    }
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'puk_flush_rewrite_rules');

// Uncomment the line below once to flush rewrite rules, then comment it back
add_action('init', 'puk_flush_rewrite_rules', 999);