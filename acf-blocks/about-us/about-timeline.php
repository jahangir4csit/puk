<?php
/**
 * Block Template: About Timeline
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_heading = get_field( 'section_heading' );
$timeline_slides = get_field( 'timeline_slides' );
$next_arrow_image = get_field( 'next_arrow_image' );
$prev_arrow_image = get_field( 'prev_arrow_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $section_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'About Timeline Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- About Us section two start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> abt_us_2"> 

    <div class="container-fluid">

        <?php if ( $section_heading ) : ?>
            <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                <div class="abt_us_2_top">
                    <h4><?php echo esc_html( $section_heading ); ?></h4> 
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $timeline_slides ) : ?>
            <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                <div class="abt_us_2_bx">
                    <!-- Right side  -->
                    <div class="abt_us_2_bx_rhgt">

                        <!-- swiper container start -->
                        <div class="swiper mySwiper_about_us">
                            
                            <div class="swiper-wrapper">

                                <?php foreach ( $timeline_slides as $slide ) : ?>
                                    <!-- slider item  -->
                                    <div class="swiper-slide">
                                        <div class="abt_us_2_bx_slide">
                                            <!-- left side  -->
                                            <div class="abt_us_2_bx_lft">
                                                <?php if ( ! empty( $slide['year'] ) ) : ?>
                                                    <span><?php echo esc_html( $slide['year'] ); ?></span>
                                                <?php endif; ?>
                                                
                                                <?php if ( ! empty( $slide['title'] ) ) : ?>
                                                    <p><?php echo esc_html( $slide['title'] ); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- right side  -->
                                            <div class="abt_us_2_bx_rhgt">
                                                <?php if ( ! empty( $slide['description'] ) ) : ?>
                                                    <p><?php echo wp_kses_post( nl2br( $slide['description'] ) ); ?></p>
                                                <?php endif; ?>
                                                
                                                <?php if ( ! empty( $slide['milestones'] ) ) : ?>
                                                    <div class="abt_us_2_bx_rhgt_flx">
                                                        <?php foreach ( $slide['milestones'] as $milestone ) : ?>
                                                            <div class="abt_us_2_bx_rhgt_flx_bx">
                                                                <?php if ( ! empty( $milestone['milestone_title'] ) ) : ?>
                                                                    <h4><?php echo esc_html( $milestone['milestone_title'] ); ?></h4>
                                                                <?php endif; ?>
                                                                
                                                                <?php if ( ! empty( $milestone['milestone_year'] ) ) : ?>
                                                                    <p><?php echo esc_html( $milestone['milestone_year'] ); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                        </div>

                        <!-- Navigation buttons -->
                        <?php if ( $next_arrow_image ) : ?>
                            <div class="swiper-button-next">
                                <img 
                                    src="<?php echo esc_url( $next_arrow_image['url'] ); ?>" 
                                    alt="<?php echo esc_attr( $next_arrow_image['alt'] ?: __( 'Next', 'puk' ) ); ?>"
                                >
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $prev_arrow_image ) : ?>
                            <div class="swiper-button-prev">
                                <img 
                                    src="<?php echo esc_url( $prev_arrow_image['url'] ); ?>" 
                                    alt="<?php echo esc_attr( $prev_arrow_image['alt'] ?: __( 'Previous', 'puk' ) ); ?>"
                                >
                            </div>
                        <?php endif; ?>

                        <!-- swiper container end  -->    
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section> 
<!-- About Us section two end  -->
