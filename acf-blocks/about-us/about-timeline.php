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

// Block preview placeholder in admin
if ( $is_preview && empty( $section_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'About Timeline Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}

$slides_count = is_array( $timeline_slides ) ? count( $timeline_slides ) : 0;
?>

<!-- About Us Timeline section start -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> abt_us_2 about_us_page">

    <div class="container">

        <?php if ( $section_heading ) : ?>
            <div class="abt_us_2_top">
                <h4><?php echo esc_html( $section_heading ); ?></h4>
            </div>
        <?php endif; ?>

        <?php if ( $timeline_slides ) : ?>

            <div class="timeline_main_wrapper">

            <!-- TOP SWIPER: content (year + description) -->
            <div class="abt_us_2_content_wrap">
                <div class="swiper mySwiper_timeline_top">
                    <div class="swiper-wrapper">

                        <?php foreach ( $timeline_slides as $index => $slide ) : ?>
                            <div class="swiper-slide">
                                <div class="abt_us_2_bx_slide">

                                    <!-- Left: year + title -->
                                    <div class="abt_us_2_bx_lft">
                                        <?php if ( ! empty( $slide['year'] ) ) : ?>
                                            <span><?php echo esc_html( $slide['year'] ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $slide['title'] ) ) : ?>
                                            <p><?php echo esc_html( $slide['title'] ); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Right: description + milestones -->
                                    <div class="abt_us_2_bx_rhgt">
                                        <?php if ( ! empty( $slide['description'] ) ) : ?>
                                            <p><?php echo wp_kses_post( nl2br( $slide['description'] ) ); ?></p>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>

            <!-- BOTTOM SWIPER: navigation — each slide shows prev/next context for that position -->
            <div class="abt_us_2_nav_wrap">
                <div class="swiper mySwiper_timeline_nav">
                    <div class="swiper-wrapper">

                        <?php foreach ( $timeline_slides as $index => $slide ) :
                            $prev_slide = ( $index > 0 ) ? $timeline_slides[ $index - 1 ] : null;
                            $next_slide = ( $index < $slides_count - 1 ) ? $timeline_slides[ $index + 1 ] : null;
                        ?>
                            <div class="swiper-slide">
                                <div class="abt_us_2_nav_inner">

                                    <!-- Prev nav item -->
                                    <div class="abt_us_2_nav_prev<?php echo $prev_slide ? '' : ' is-hidden'; ?>">
                                        <?php if ( $prev_slide ) : ?>
                                            <div class="d-flex align-items-end abt_us_2_nav_content_wrap justify-content-start">
                                                <div class="abt_us_2_nav_prev_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                                        <path d="M2.65181 10.3519L3.11619 10.8172L6.99679 14.6944L7.90597 13.763L5.13587 10.9973L18.1068 10.9915L18.1063 9.69859L5.13594 9.70436L7.90293 6.9362L6.99228 6.00567L3.11513 9.88628L2.65181 10.3519Z" fill="black" fill-opacity="0.5"/>
                                                    </svg>
                                                </div>
                                                <div class="abt_us_2_nav__content">
                                                    <span class="nav-label"><?php echo esc_html( $prev_slide['title'] ); ?></span>
                                                    <span class="nav-year"><?php echo esc_html( $prev_slide['year'] ); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Next nav item -->
                                    <div class="abt_us_2_nav_next<?php echo $next_slide ? '' : ' is-hidden'; ?>">
                                        <?php if ( $next_slide ) : ?>
                                            <div class="d-flex align-items-end abt_us_2_nav_content_wrap justify-content-end">
                                                <div class="abt_us_2_nav__content">
                                                    <span class="nav-label"><?php echo esc_html( $next_slide['title'] ); ?></span>
                                                    <span class="nav-year"><?php echo esc_html( $next_slide['year'] ); ?></span>
                                                </div>
                                                                                                <div class="abt_us_2_nav_next_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
<path d="M18.0449 10.3448L17.5805 9.87957L13.6999 6.00242L12.7907 6.93376L15.5608 9.69946L2.58988 9.70523L2.59045 10.9982L15.5608 10.9924L12.7938 13.7606L13.7044 14.6911L17.5816 10.8105L18.0449 10.3448Z" fill="black" fill-opacity="0.5"/>
</svg>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
            </div>

        <?php endif; ?>

    </div>

</section>
<!-- About Us Timeline section end -->
