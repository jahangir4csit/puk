<?php
/**
 * Block Template: Story Third Section
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$description = get_field( 'description' );
$full_width_image = get_field( 'full_width_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $description ) && empty( $full_width_image ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Story Third Section Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> r_third_sd sabbia-section">
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
            <div class="col-12">
                <?php if ( $full_width_image ) : ?>
                    <div class="image-container">
                        <img
                            src="<?php echo esc_url( $full_width_image['url'] ); ?>"
                            alt="<?php echo esc_attr( $full_width_image['alt'] ?: __( 'Full Width Image', 'puk' ) ); ?>"
                        >
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
