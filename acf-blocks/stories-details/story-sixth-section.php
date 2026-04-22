<?php
/**
 * Block Template: Story Sixth Section
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$description = get_field( 'description' );
$gallery_images = get_field( 'gallery_images' );

// Block preview placeholder in admin
if ( $is_preview && empty( $description ) && empty( $gallery_images ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Story Sixth Section Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> r_third_sd r_sixth_sd sabbia-section">
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
        <!-- Images -->
        <?php if ( $gallery_images ) : ?>
            <div class="row g-4 r_six_sd_images">
                <?php foreach ( $gallery_images as $image ) : ?>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="image-container">
                            <img
                                src="<?php echo esc_url( $image['url'] ); ?>"
                                alt="<?php echo esc_attr( $image['alt'] ?: __( 'Gallery Image', 'puk' ) ); ?>"
                            >
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
