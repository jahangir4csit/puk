<?php
/**
 * Template part for displaying product breadcrumb
 *
 * Sets the following variables for use in parent template:
 * - $product_id
 * - $current_term
 * - $term_id
 * - $taxonomy
 * - $ancestors
 * - $product_sku
 * - $family_code
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}

// Get current term
$product_id = get_the_ID();
$terms = wp_get_post_terms(get_the_ID(), 'product-family');
$current_term = !empty($terms) && !is_wp_error($terms) ? $terms[0] : null;
$term_id  = $current_term->term_id ?? null;
$taxonomy = $current_term->taxonomy ?? null;
$ancestors = get_ancestors($term_id, $taxonomy);
$ancestors = array_reverse($ancestors);
$product_sku = $product_id ? get_post_meta($product_id, 'prod__sku', true) : null;
// $family_code = get_field('family_code', 'product-family_' . $term_id);
$family_code = get_product_family_code( $current_term, 2 );
?>

<section class="common-breadcrumb-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="common-breadcrumb-wrapper">

                    <?php
                    echo '<ul>';
                    echo '<li><a href="' . home_url() . '">Home</a></li>';
                    if (!empty($ancestors)) {
                        foreach ($ancestors as $ancestor_id) {
                            $ancestor = get_term($ancestor_id, $taxonomy);
                            // Skip '_' placeholder terms in breadcrumb
                            if ( $ancestor->name === '_' ) {
                                continue;
                            }
                            echo '<li><a href="' . get_term_link($ancestor) . '">' . esc_html($ancestor->name) . '</a></li>';
                        }
                    }
                    // Only show current term if not '_'
                    if (!empty($current_term) && $current_term->name !== '_') {
                        echo '<li><a href="' . get_term_link($current_term) . '">' . esc_html($current_term->name) . '</a></li>';
                    }
                    echo '<li>' . $product_sku . '</li>';
                    echo '</ul>';
                    ?>


                </div>
            </div>
        </div>
    </div>
</section>
