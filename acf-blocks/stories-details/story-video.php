<?php
/**
 * Block Template: Story Video
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$video_file = get_field( 'video_file' );
$fallback_image = get_field( 'fallback_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $video_file ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Story Video Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> r_main_banner_section">
    <?php if ( $video_file ) : ?>
        <div class="r_video_banner">
            <video autoplay muted loop playsinline>
                <source src="<?php echo esc_url( $video_file['url'] ); ?>" type="<?php echo esc_attr( $video_file['mime_type'] ); ?>">
                Your browser does not support the video tag.
            </video>
        </div>
    <?php endif; ?>

    <?php if ( $fallback_image ) : ?>
        <div class="r_sd_image_banner">
            <img src="<?php echo esc_url( $fallback_image['url'] ); ?>" alt="<?php echo esc_attr( $fallback_image['alt'] ?: 'Banner Image' ); ?>">
        </div>
    <?php endif; ?>
</div>
