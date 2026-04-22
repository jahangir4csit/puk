<?php
/**
 * Block Template: Story Fourth Section
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$description = get_field( 'description' );
$video_file = get_field( 'video_file' );
$side_image = get_field( 'side_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $description ) && empty( $video_file ) && empty( $side_image ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Story Fourth Section Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> r_third_sd r_forth_sd sabbia-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-12">
            </div>
            <div class="col-lg-5 col-md-5 col-12">
                <?php if ( $description ) : ?>
                    <div class="section-description">
                        <p><?php echo wp_kses_post( nl2br( $description ) ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-12 px-0">
                <?php if ( $video_file ) : ?>
                    <div class="video-container">
                        <video autoplay muted loop playsinline>
                            <source src="<?php echo esc_url( $video_file['url'] ); ?>" type="<?php echo esc_attr( $video_file['mime_type'] ); ?>">
                            <?php esc_html_e( 'Your browser does not support the video tag.', 'puk' ); ?>
                        </video>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-6 col-12 px-0">
                <?php if ( $side_image ) : ?>
                    <div class="image-container">
                        <img
                            src="<?php echo esc_url( $side_image['url'] ); ?>"
                            alt="<?php echo esc_attr( $side_image['alt'] ?: __( 'Side Image', 'puk' ) ); ?>"
                        >
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
