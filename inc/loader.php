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

// Load family UID generator
puk_autoload_files('family_uid_generate');

// Load smart rewrite rules flush handler
require_once __DIR__ . '/rewrite-flush-handler.php';