<?php
/**
 * Block Template: Story Gallery
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$gallery_title = get_field( 'gallery_title' );
$gallery_images = get_field( 'gallery_images' );

// Block preview placeholder in admin
if ( $is_preview && empty( $gallery_images ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Story Gallery Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Gallery Section -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> r_gallery_section sabbia-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ( $gallery_title ) : ?>
                    <h3 class="section-title"><?php echo esc_html( $gallery_title ); ?></h3>
                <?php endif; ?>

                <?php if ( $gallery_images ) : ?>
                    <div class="gallery-grid">
                        <?php foreach ( $gallery_images as $index => $image ) : ?>
                            <div class="gallery-item" data-index="<?php echo esc_attr( $index ); ?>">
                                <div class="image-container">
                                    <img
                                        src="<?php echo esc_url( $image['url'] ); ?>"
                                        alt="<?php echo esc_attr( $image['alt'] ?: sprintf( __( 'Gallery Image %d', 'puk' ), $index + 1 ) ); ?>"
                                    >
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


  <!-- Gallery Popup Slider -->
    <div class="gallery-popup" id="galleryPopup">
        <div class="popup-overlay"></div>
        <div class="popup-content">
            <button class="popup-close">&times;</button>
            <button class="popup-prev">&#8249;</button>
            <button class="popup-next">&#8250;</button>
            <div class="popup-image-container">
                <img src="" alt="Gallery Image" id="popupImage">
            </div>
            <div class="popup-counter">
                <span id="currentIndex">1</span> / <span id="totalImages">6</span>
            </div>
        </div>
    </div>