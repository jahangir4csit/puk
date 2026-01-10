<?php
/**
 * Family Product Gallery Component
 * 
 * @var array $args {
 *     @var WP_Term $current_term
 *     @var int     $term_id
 * }
 */
$current_term        = $args['current_term'];
$term_id             = $args['term_id'];

// Fetch ACF logic inside the component based on passed term_id
$designed_by         = get_field('pf_designed_by', 'product-family_' . $term_id);
$subfamily_desc      = get_field('pf_subfam_desc', 'product-family_' . $term_id);
$subfamily_pro_img   = get_field('pf_subfam_product_image', 'product-family_' . $term_id);
$subfamily_tech_draw = get_field('pf_subfam_tech_drawing', 'product-family_' . $term_id);
?>
<section class="pf-gallary-main">
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="title-box-flex">
                    <div class="title-box">
                        <h1 aria-label="product title"> <?php echo esc_html($current_term->name); ?> </h1>
                        <h2 aria-label="product sub title"> Designed by <br> <?php echo $designed_by;?></h2>
                    </div>
                    <div class="description-box">
                        <article>
                            <p><?php echo $subfamily_desc; ?></p>
                        </article>
                    </div>
                </div>
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
                            <p>Product IMAGE</p>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <?php else : ?>
                        <div class="empty_img">
                            <img src="<?php echo get_template_directory_uri() . '/assets/images/empty_image.svg'; ?>"
                                alt="Product Image">
                        </div>
                        < <p>Product IMAGE</p>
                            <?php endif; ?>
                    </div>
                    <div class="single-grid">
                        <?php if (!empty($subfamily_tech_draw)) : ?>
                        <img src="<?php echo esc_url($subfamily_tech_draw); ?>" alt="Technical Drawing">
                        <?php else : ?>
                        <div class="empty_img">
                            <img src="<?php echo get_template_directory_uri() . '/assets/images/empty_image.svg'; ?>"
                                alt="Technical Drawing">
                        </div>
                        <?php endif; ?>
                        <p>technical drawing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- end family product gallery -->