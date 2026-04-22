<?php
/**
 * Template part for displaying product title section
 *
 * Required variables from parent template:
 * - $ancestors
 * - $current_term
 * - $family_code
 *
 * Sets the following variables for use in parent template:
 * - $parent_term_id
 * - $designed_by
 * - $subfamily_desc
 * - $site_plachlder_img
 * - $parent_term
 * - $main_cat_term_id
 * - $main_term
 * - $subsub_family_gallary
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}

$parent_term_id = !empty($ancestors[2]) ? $ancestors[2] : null;
// $designed_by = $parent_term_id ? get_field('pf_designed_by', 'product-family_' . $parent_term_id) : null;
$subfamily_desc = $current_term ? $current_term->description : null;
$site_plachlder_img = get_field('site_plachlder_img', 'option') ?: null;
$parent_term = get_term($parent_term_id, $current_term->taxonomy);
$main_cat_term_id = $parent_term->parent;
$main_term = get_term($main_cat_term_id, $parent_term->taxonomy);

if (empty($subfamily_desc) && !empty($parent_term) && !is_wp_error($parent_term)) {
    $subfamily_desc = $parent_term->description;
}
if (empty($subfamily_desc) && !empty($main_term) && !is_wp_error($main_term)) {
    $subfamily_desc = $main_term->description;
}

$designed_by = $main_cat_term_id ? get_field('pf_designed_by', 'product-family_' . $main_cat_term_id) : null;

// Get individual image fields from the product post (not taxonomy)
$product_gallery = array();

// Get the current product ID from the global post object
global $post;
$product_id = isset($post->ID) ? $post->ID : 0;

if ($product_id) {
    $prod_gallery_5 = get_field('prod_gallery_5', $product_id);
    $prod_gallery_6 = get_field('prod_gallery_6', $product_id);
    $prod_gallery_7 = get_field('prod_gallery_7', $product_id);
    $prod_gallery_8 = get_field('prod_gallery_8', $product_id);
    $prod_gallery_9 = get_field('prod_gallery_9', $product_id);
    $prod_gallery_10 = get_field('prod_gallery_10', $product_id);
    
    // Combine the individual image fields into an array for the slider
    if (!empty($prod_gallery_5)) $product_gallery[] = $prod_gallery_5;
    if (!empty($prod_gallery_6)) $product_gallery[] = $prod_gallery_6;
    if (!empty($prod_gallery_7)) $product_gallery[] = $prod_gallery_7;
    if (!empty($prod_gallery_8)) $product_gallery[] = $prod_gallery_8;
    if (!empty($prod_gallery_9)) $product_gallery[] = $prod_gallery_9;
    if (!empty($prod_gallery_10)) $product_gallery[] = $prod_gallery_10;
}
?>

<section class="pd-title-main">
    <div class="container px-0">
        <div class="row g-0">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="title-box-flex">
                    <div class="title-box">
                        <?php if (!empty($current_term)) : ?>
                        <h1 aria-label="product title"><?php
                            // Build title parts - skip '_' placeholder names
                            $title_parts = array();
                            if ( $main_term && $main_term->name !== '_' ) {
                                $title_parts[] = esc_html($main_term->name);
                            }
                            if ( $parent_term && $parent_term->name !== '_' ) {
                                $title_parts[] = esc_html($parent_term->name);
                            }
                            if ( $current_term->name !== '_' ) {
                                $title_parts[] = esc_html($current_term->name);
                            }
                            echo implode('<br/> ', $title_parts);
                            ?>
                        </h1>
                        <?php endif; ?>
                        
                        <?php $product_code = single_product_code( $product_id ); ?>
                        <?php if ( $product_code ) { ?>
                        <span> <?php echo esc_html( $product_code ); ?> </span>
                        <?php } ?>

                        <?php if ($designed_by) : ?>
                        <h2 aria-label="product sub title">Designed by <br>
                            <?php echo esc_html($designed_by); ?></h2>
                        <?php endif; ?>
                    </div>
                    <div class="description-box">
                        <article>
                            <p>
                                <?php echo $subfamily_desc; ?>
                            </p>
                        </article>
                    </div>
                </div>
            </div>

            <?php
            // Use only the combined gallery from individual image fields
            if (!empty($product_gallery)) { ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="project-slider-wrapper title-box-flex">
                    <div class="swiper project-slider">
                        <div class="swiper-wrapper">
                            <?php

                                foreach ($product_gallery as $prod_gallery_img) {
                                ?>
                            <div class="swiper-slide">
                                <div class="image-box">
                                    <img src="<?php echo $prod_gallery_img; ?>"
                                        alt="<?php echo get_the_title($product_id); ?>" />
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</section>