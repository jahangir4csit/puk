<?php
/**
 * ACF Fields Configuration for Products Family Taxonomy Export/Import
 * 
 * This file defines the ACF fields for the 'products-family' taxonomy
 * that should be included in the export/import process.
 * 
 * Format: ["Field Label", "field_name", "field_type"]
 * 
 * @package puk
 */

// Define the field configurations array
$taxonomy_acf_field_definitions = [
    // Family Basic Information
    ["Designed By", "pf_designed_by", "text"],
    ["Subfamily Description", "pf_subfam_desc", "textarea"],
    ["Subfamily Product Image", "pf_subfam_product_image", "image"],
    ["Subfamily Technical Drawing", "pf_subfam_tech_drawing", "image"],
    ["Subfamily Index", "pf_subsub_fam_indx", "text"],
    
    // Family Technical Features
    ["Subfamily Technical Features", "pf_subsub_fam_tch_feturs", "gallery"],
    
    // Family Images
    ["Feature Image", "pf_fet_img", "image"],
    ["Hover Image", "pf_hover_img", "image"],
];