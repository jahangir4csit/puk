<?php
/**
 * ACF Fields Configuration for Products Export/Import
 *
 * This file defines the ACF fields for the 'products' post type
 * that should be included in the export/import process.
 *
 * Format: ["Field Label", "field_name", "field_type"]
 *
 * @package puk
 */

// Define the field configurations array
$acf_field_definitions = [
    // Product Basic Information Fields
    ["Product Title", "pro_title", "text"],
    ["Product Remote Driver Selection", "pro_remote_drv_slctn", "repeater"],

    // Product Gallery Images (single image fields)
    ["Gallery 5", "prod_gallery_5", "image"],
    ["Gallery 6", "prod_gallery_6", "image"],
    ["Gallery 7", "prod_gallery_7", "image"],
    ["Gallery 8", "prod_gallery_8", "image"],
    ["Gallery 9", "prod_gallery_9", "image"],
    ["Gallery 10", "prod_gallery_10", "image"],
    ["Gallery 11", "prod_gallery_11", "image"],
    ["Gallery 12", "prod_gallery_12", "image"],
    ["Gallery 13", "prod_gallery_13", "image"],
    ["Gallery 14", "prod_gallery_14", "image"],
    ["Gallery 15", "prod_gallery_15", "image"],
    ["Gallery 16", "prod_gallery_16", "image"],
    ["Gallery 17", "prod_gallery_17", "image"],
    ["Gallery 18", "prod_gallery_18", "image"],
    ["Gallery 19", "prod_gallery_19", "image"],
    ["Gallery 20", "prod_gallery_20", "image"],

    // Product Specifications
    ["Wattage", "pro_wattage", "text"],
    ["CCT", "pro_cct", "text"],
    ["Beam Angle", "pro_beam_angle", "text"],
    ["Lumens", "pro_lumens", "text"],
    ["Finish Color", "pro_finish_color", "taxonomy"],
    ["Dimming", "pro_dimming", "taxonomy"],
    ["IP Rating", "pro_iprating", "text"],
    ["IK Rating", "pro_ikrating", "text"],
    ["Material", "pro_material", "text"],
    ["Coating", "pro_coating", "text"],
    ["Light Source", "pro_light_source", "text"],
    ["Screws", "pro_screws", "text"],
    ["Transformer", "pro_transformer", "text"],
    ["Gasket", "pro_gasket", "text"],
    ["Glass", "pro_glass", "text"],
    ["Cable Gland", "pro_cable_gland", "text"],
    ["Power Cable", "pro_pwr_cble", "text"],
    ["Gross Weight", "pro_grs_weight", "text"],
    ["Measurement Image", "pro_mesr_img", "image"],
    ["New", "prod_is__new", "true_false"],

    // Product Accessories
    ["Product Available", "pd_alavlbl_select_product", "relationship"],
    ["Accessories", "prod_acc_in__terms", "taxonomy"],

    // Product Downloads
    ["LTD Files", "pro_dwnld_ltd_files", "file"],
    ["Instructions", "pro_dwnld_instructions", "file"],
    ["Revit File", "pro_dwnld_revit", "file"],
    ["3D BIM", "pro_dwnld_3dbim", "file"],
    ["Photometric", "pro_dwnld_photometric", "file"],
    ["Product Video", "pro_dwnld_provideo", "file"],
];