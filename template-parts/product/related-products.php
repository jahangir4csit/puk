<?php
/**
 * Template part for displaying related products section
 *
 * Required variables from parent template:
 * - $site_plachlder_img
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}
?>

<?php

$terms = get_the_terms(get_the_ID(), 'product-family');

$current_term = !empty($terms) && !is_wp_error($terms) ? $terms[0] : null;
$term_id  = $current_term->term_id ?? null;
$ancestors = get_ancestors($term_id, 'product-family');
$ancestors = array_reverse($ancestors);

// Level 1 term (Family Level)
$children_term = get_term($ancestors[1], 'product-family');
$child_image      = get_field('pf_fet_img', 'product-family_' . $children_term->term_id);
$child_term_title = $children_term->name ;
$child_term_link  = get_term_link($children_term);

// Fallback: if Level 1 image is empty, use first Sub Family (Level 2) child image
if (empty($child_image)) {
    $sub_family_terms = get_terms([
        'taxonomy'   => 'product-family',
        'parent'     => $children_term->term_id,
        'number'     => 1,
        'hide_empty' => false,
    ]);
    if (!empty($sub_family_terms) && !is_wp_error($sub_family_terms)) {
        $child_image = get_field('pf_fet_img', 'product-family_' . $sub_family_terms[0]->term_id);
    }
}

// ACF field returning array of term IDs - from Level 1 (Family Level) term
$pf_related_products = get_field('pf_related_products', 'product-family_' . $children_term->term_id); 
  ?>

<section class=" light-distribution-main pb-100 related">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-box">
                    <h3>Related Products</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-2 col-lg-2"></div>
            <div class="col-sm-12 col-md-10 col-lg-10">

                <!-- new code start  -->
                
                <div class="related-prod-wrapper pd-single-data-sbglry">
                    <div class="swiper related-product-slider">

                        <!-- related products with products logic  -->
                        <div class="swiper-wrapper">
                            
                          <!--family product data -->
                            <div class="swiper-slide">
                                <div class="product_col">
                                    <a class="rp_card" href="<?php echo $child_term_link; ?>"
                                       style="background-image: url('<?php echo $child_image; ?>');">
                                        <div class="rp_card_overlay">
                                            <span class="rp_view_all">View all</span>
                                            <span class="rp_card_title"><?php echo $child_term_title; ?> Series</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                                
                          <?php

                              if (!empty($pf_related_products) && is_array($pf_related_products)) {
                              foreach ($pf_related_products as $related_term_id) {

                                // Get term data from term ID
                                $related_term = get_term($related_term_id, 'product-family');
                                if (!$related_term || is_wp_error($related_term)) {
                                    continue;
                                }

                                $related_block_title = $related_term->name;
                                $related_block_img   = get_field('pf_fet_img', 'product-family_' . $related_term_id);
                                $related_block_link  = get_term_link($related_term);

                                // Fallback: if related term image is empty, use first Sub Family (Level 2) child image
                                if (empty($related_block_img)) {
                                    $related_sub_terms = get_terms([
                                        'taxonomy'   => 'product-family',
                                        'parent'     => $related_term_id,
                                        'number'     => 1,
                                        'hide_empty' => false,
                                    ]);
                                    if (!empty($related_sub_terms) && !is_wp_error($related_sub_terms)) {
                                        $related_block_img = get_field('pf_fet_img', 'product-family_' . $related_sub_terms[0]->term_id);
                                    }
                                }

                                // Handle ACF image field returning array or plain URL
                                $img_url = '';
                                if (!empty($related_block_img)) {
                                    $img_url = is_array($related_block_img) ? esc_url($related_block_img['url']) : esc_url($related_block_img);
                                } else {
                                    $img_url = esc_url($site_plachlder_img);
                                } ?>

                                <!-- product col (from pf_related_products term IDs) -->
                                <div class="swiper-slide">
                                    <div class="product_col">
                                        <a class="rp_card" href="<?php echo esc_url($related_block_link); ?>"
                                           style="background-image: url('<?php echo $img_url; ?>');">
                                            <div class="rp_card_overlay">
                                                <span class="rp_view_all">View all</span>
                                                <span class="rp_card_title"><?php echo esc_html($related_block_title); ?> Series</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            <?php } ?>
                        <?php } ?>

                        </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                    </div>

                    <!-- new code end  -->

                </div>
            </div>
        </div>
    </div>
</section>