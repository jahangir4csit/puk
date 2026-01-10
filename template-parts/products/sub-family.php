<?php
/**
 * Template Name: Sub Family
 * 
 * Template parts for products sub-family
 */

// Get current term context
$current_term = get_queried_object();
$term_id      = $current_term->term_id;
$taxonomy     = $current_term->taxonomy;

$args_context = [
    'current_term' => $current_term,
    'term_id'      => $term_id,
    'taxonomy'     => $taxonomy
];

// Include Breadcrumb
get_template_part('template-parts/products/components/family-tax-breadcrumb', null, $args_context);

// Include Gallery
get_template_part('template-parts/products/components/family-product-gallery', null, $args_context);

// Include Accordion
get_template_part('template-parts/products/components/family-accordion', null, $args_context);
?>