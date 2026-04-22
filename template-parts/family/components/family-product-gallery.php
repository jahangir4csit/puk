<?php
/**
 * Family Product Gallery Component
 * 
 * @var array $args {
 *     @var WP_Term $current_term
 *     @var int     $term_id
 * }
 */
$current_term        = get_queried_object();
$term_id             = $args['term_id'];
$parent_term_id = $current_term->parent; 
$parent_term = get_term( $parent_term_id, $current_term->taxonomy );
// print_r($current_term) ; 

// Fetch ACF logic inside the component based on passed term_id
$designed_by         = get_field('pf_designed_by', 'product-family_' . $parent_term_id);
// $designed_by         = get_field('pf_designed_by', 'product-family_' . $term_id);
$subfamily_desc      = $current_term->description;
$subfamily_tech_draw = get_field('pf_subfam_tech_drawing', 'product-family_' . $term_id);
if (empty($subfamily_tech_draw) && !empty($parent_term_id)) {
    $subfamily_tech_draw = get_field('pf_subfam_tech_drawing', 'product-family_' . $parent_term_id);
}

// Product gallery images (4 individual ACF image fields)
$prod_gallery_1 = get_field('prod_gallery_1', 'product-family_' . $term_id);
$prod_gallery_2 = get_field('prod_gallery_2', 'product-family_' . $term_id);
$prod_gallery_3 = get_field('prod_gallery_3', 'product-family_' . $term_id);
$prod_gallery_4 = get_field('prod_gallery_4', 'product-family_' . $term_id);

// Build array of non-empty gallery images
$subfamily_pro_img = array();
if (!empty($prod_gallery_1)) {
    $subfamily_pro_img[] = $prod_gallery_1;
}
if (!empty($prod_gallery_2)) {
    $subfamily_pro_img[] = $prod_gallery_2;
}
if (!empty($prod_gallery_3)) {
    $subfamily_pro_img[] = $prod_gallery_3;
}
if (!empty($prod_gallery_4)) {
    $subfamily_pro_img[] = $prod_gallery_4;
}


//Show the title from main family+ sub family+sub sub family

$title_parts = array();
$term = $current_term;
// Collect all terms up the chain
while ( $term && ! is_wp_error( $term ) ) {
    if ( $term->name !== '_' ) {
        $title_parts[] = esc_html( $term->name );
    }
    if ( $term->parent == 0 ) {
        break;
    }
    $term = get_term( $term->parent, $term->taxonomy );
}

$title_parts = array_reverse( $title_parts );
array_shift( $title_parts );

?>
<section class="pf-gallary-main">
    <div class="container px-0">
        <div class="title-box-flex d-flex align-items-center flex-wrap tax_header_info">
            <div class="title-box">
                <h1 aria-label="product title">
                    <?php echo implode('<br />', $title_parts); ?>
                </h1>
                <?php if(!empty($designed_by)){ ?>
                <h2 aria-label="product sub title"> Designed by <br> <?php echo $designed_by;?></h2>
                <?php }?>
            </div>
            <div class="description-box">
                <article>
                    <p><?php echo $subfamily_desc; ?></p>
                </article>
            </div>
        </div>
        <div class="row g-0">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="pf-grallary-grid">
                    <div class="single-grid">
                        <?php if (!empty($subfamily_pro_img)) : ?>
                        <div class="swiper pf-display-slider">
                            <div class="swiper-wrapper">
                                <?php foreach($subfamily_pro_img as $subfamily_img) : ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo esc_url($subfamily_img); ?>" alt="Product Image">
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="single-grid--title">
                            <p>Product IMAGE</p>
                        </div>
                        <?php else : ?>
                        <div class="empty_img">
                            <img src="<?php echo get_template_directory_uri() . '/assets/images/empty_image.svg'; ?>"
                                alt="Product Image">
                        </div>
                        <div class="single-grid--title">
                        <p>Product IMAGE</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="single-grid">
                        <?php if (!empty($subfamily_tech_draw)) : ?>
                            <div class="single-grid-tech-img">
                                <img src="<?php echo esc_url($subfamily_tech_draw); ?>" alt="Technical Drawing">
                            </div>
                        <?php else : ?>
                        <div class="empty_img">
                            <img src="<?php echo get_template_directory_uri() . '/assets/images/empty_image.svg'; ?>"
                                alt="Technical Drawing">
                        </div>
                        <?php endif; ?>
                        <div class="single-grid--title">
                             <p>Technical drawing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- end family product gallery -->