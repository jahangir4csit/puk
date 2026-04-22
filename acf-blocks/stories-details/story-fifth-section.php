<?php
/**
 * Block Template: Story Fifth Section
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$description = get_field( 'description' );
$large_image = get_field( 'large_image' );
$small_image = get_field( 'small_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $description ) && empty( $large_image ) && empty( $small_image ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Story Fifth Section Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> r_third_sd r_fifth_sd sabbia-section">
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
                <div class="images-section">
                    <?php if ( $large_image ) : ?>
                        <div class="image-container large-image">
                            <img
                                src="<?php echo esc_url( $large_image['url'] ); ?>"
                                alt="<?php echo esc_attr( $large_image['alt'] ?: __( 'Large Image', 'puk' ) ); ?>"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ( $small_image ) : ?>
                        <div class="image-container small-image">
                            <img
                                src="<?php echo esc_url( $small_image['url'] ); ?>"
                                alt="<?php echo esc_attr( $small_image['alt'] ?: __( 'Small Image', 'puk' ) ); ?>"
                            >
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
