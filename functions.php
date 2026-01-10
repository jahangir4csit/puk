<?php
add_filter('acf/format_value/type=text', 'do_shortcode');

include_once("inc/acf-theme-options.php");
include_once("inc/acf-blocks-builder.php");
include_once("inc/class-wp-bootstrap-navwalker.php");
include_once("inc/enqueue.php");
include_once("inc/hero-slider-enqueue.php");
include_once("inc/custom-post-taxonomy.php"); 
include_once("inc/ajax-actions.php"); 
include_once("inc/custom-functions.php"); 
include_once("inc/export-import/export_import_helper.php");
include_once("inc/export-import/taxonomy_import_export_helper.php");
include_once("inc/export-import/finish_color_import_export_helper.php");
include_once("inc/export-import/accessories_import_export_helper.php");
include_once("inc/export-import/features_import_export_helper.php");



add_theme_support( 'post-thumbnails' );

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Load WooCommerce functions if WooCommerce is activated.
if (class_exists('WooCommerce')) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if (class_exists('Jetpack')) {
	$understrap_includes[] = '/jetpack.php';
}