<?php
/**
 * Template part for displaying Product Variants section
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
  
   $taxonomy = 'product-family';
   $product_terms = get_the_terms(get_the_ID(), $taxonomy);
   $target_terms = [];
   $current_term = null;

   // Get the deepest term as the current term
   if (!empty($product_terms) && !is_wp_error($product_terms)) {
       $max_depth = -1;
       foreach ($product_terms as $term) {
           $depth = 0;
           $temp_term = $term;
           while ($temp_term->parent) {
               $depth++;
               $temp_term = get_term($temp_term->parent, $taxonomy);
           }
           if ($depth > $max_depth) {
               $max_depth = $depth;
               $current_term = $term;
           }
       }
   }

   // 1. Try manual related families from ACF field first
   $related_fam_ids = get_field('prod_related_fam__terms', get_the_ID());
   
   if (!empty($related_fam_ids) && is_array($related_fam_ids)) {
       $target_terms = get_terms([
           'taxonomy'   => $taxonomy,
           'include'    => $related_fam_ids,
           'hide_empty' => false,
           'orderby'    => 'include' // Keep the order specified in ACF
       ]);
   } 
   // 2. Fallback to default sibling logic if field is empty
   elseif ($current_term) {
       $target_terms = get_terms([
           'taxonomy'   => $taxonomy,
           'hide_empty' => false,
           'parent'     => $current_term->parent,
       ]);
   }

   // Filter out placeholder terms (name '_')
   $target_terms = puk_filter_valid_terms( $target_terms );

    // Display section condition
    $show_section = false;
    if (!empty($target_terms)) {
        if (!empty($related_fam_ids)) {
            $show_section = true;
        } elseif (count($target_terms) > (isset($current_term) && in_array($current_term->term_id, wp_list_pluck($target_terms, 'term_id')) ? 1 : 0)) {
            $show_section = true;
        }
    }

    if($show_section){  ?>

<section class=" light-distribution-main related">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-box">
                    <h3>Product Variants</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-2 col-lg-2"></div>
            <div class="col-sm-12 col-md-10 col-lg-10">

                <!-- new code start  -->

                <div class="related-prod-wrapper pd-single-data-sbglry">
                    <div class="swiper related-product-slider">

                        <!-- Product Variants with products logic  -->
                        <div class="swiper-wrapper">
                            
                        <?php   
                             foreach ($target_terms as $term) {
                                // Skip the current product's category ONLY if we are in fallback sibling mode.
                                // If manually selected in ACF, respect the user's choice and show it.
                                if (empty($related_fam_ids) && isset($current_term) && $term->term_id == $current_term->term_id) {
                                    continue;
                                }
                            
                                // Term image via ACF or WP term meta
                                $image           = get_field('pf_fet_img', 'product-family_' . $term->term_id);
                                $hover_image     = get_field('pf_hover_img', 'product-family_' . $term->term_id);
                                $image_url       = $image ? $image : $site_plachlder_img;
                                $hover_image_url = $hover_image ? $hover_image : $site_plachlder_img;
                                $empty_class     = $image ? '' : ' empty_img';
                                $term_link       = get_term_link($term);
                                $term_name       = esc_html($term->name);
                                ?>
                            
                                <!-- product col -->
                                <div class="swiper-slide">
                                    <div class="product_col">
                                        <div class="product_row_item">
                                            <a class="product-image-wrap<?php echo $empty_class; ?>" href="<?php echo esc_url($term_link); ?>">
                                                <img class="img-default" src="<?php echo esc_url($image_url); ?>" alt="<?php echo $term_name; ?>">
                                                <img class="img-hover" src="<?php echo esc_url($hover_image_url); ?>" alt="<?php echo $term_name; ?>">
                                            </a>
                                            <div class="r_pft_part">
                                               <a href="<?php echo esc_url($term_link); ?>" class="product_item_title">
                                                <?php
                                                    $title_parts = array();
                                                    $ancestor_ids = array_reverse( get_ancestors( $term->term_id, 'product-family', 'taxonomy' ) );
                                                    $ancestor_terms = array_slice( $ancestor_ids, 1 ); // skip Main category
                                                    foreach ( $ancestor_terms as $anc_id ) {
                                                        $anc_term = get_term( $anc_id, 'product-family' );
                                                        if ( $anc_term && ! is_wp_error( $anc_term ) && $anc_term->name !== '_' ) {
                                                            $title_parts[] = esc_html( $anc_term->name );
                                                        }
                                                    }
                                            
                                                    $title_parts[] = $term_name;
                                                    echo implode( ' ', $title_parts );
                                                ?>
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
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

<?php } ?>
