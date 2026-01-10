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
    ["Gallery 1", "pro_gallary", "gallery"],
    ["Gallery 2", "pro_sub_gallary", "gallery"],
    ["Product Remote Driver Selection", "pro_remote_drv_slctn", "repeater"],
    
    // Product Specifications
    ["Wattage", "pro_wattage", "text"],
    ["CCT", "pro_cct", "text"],
    ["Beam Angle", "pro_beam_angle", "text"],
    ["Lumens", "pro_lumens", "text"],
    ["Finish", "pro_finish", "text"],
    ["Finish Color", "pro_finish_color", "text"],
    ["Dimming", "pro_dimming", "text"],
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
    
    // Product Accessories
    ["Product Available", "pd_alavlbl_select_product", "relationship"],
    
    // Product Installation Accessories
    ["Integrated Accessories Title", "pro_inte_access_stitle", "text"],
    ["Integrated Accessories Description", "pro_inte_access_sdesc", "wysiwyg"],
    ["Integrated Accessories Repeater", "pro_inte_access_rept", "repeater"],
    ["Integrated Accessories Image", "pro_inte_access_img", "image"],
    ["Integrated Accessories Name", "pro_inte_access_name", "text"],
    ["Integrated Accessories Code", "pro_inte_access_code", "text"],
    
    // Product Not Included
    ["Accessories Sec Title", "pro_acs_incld_stitle", "text"],
    ["Accessories Sec Sub Title", "pro_acs_incld_stitlesub", "text"],
    ["Product Not Included", "pro_not_incld", "repeater"],
    ["Product Not Included Image", "pro_not_incld_img", "image"],
    ["Product Not Included Title", "pro_not_incld_title", "text"],
    ["Product Not Included Subtitle", "pro_not_incld_subtitle", "text"],
    ["Product Not Included Description", "pro_not_incld_desc", "text"],
    ["Product Not Included Name", "pro_not_incld_name", "text"],
    ["Product Not Included Code", "pro_not_incld_code", "text"],
    
    // Product Downloads
    ["LTD Files", "pro_dwnld_ltd_files", "file"],
    ["Instructions", "pro_dwnld_instructions", "file"],
    ["Revit File", "pro_dwnld_revit", "file"],
    ["3D BIM", "pro_dwnld_3dbim", "file"],
    ["Photometric", "pro_dwnld_photometric", "file"],
    ["Product Video", "pro_dwnld_provideo", "file"],
];