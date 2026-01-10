<?php
/**
 * Custom Post Types and Taxonomies
 * 
 * This file serves as the entry point for all custom post types and taxonomies.
 * The actual implementations are modularized in separate files for better organization.
 * 
 * Structure:
 * - post-types/   : Contains all custom post type registrations
 * - taxonomies/   : Contains all custom taxonomy registrations
 * - loader.php    : Autoloader that loads all modules
 * 
 * @package PUK
 */

// Load the autoloader
require_once __DIR__ . '/loader.php';