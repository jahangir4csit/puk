<?php
/**
 * Block Template: Story Sabbia
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_title = get_field( 'section_title' );
$description = get_field( 'description' );

// Fetch all fields
$image_layout       = get_field( 'image_layout' );       // '2_images' or '4_images'
$image_left         = get_field( 'image_left' );
$image_right        = get_field( 'image_right' );
$image_left_top     = get_field( 'image_left_top' );
$image_left_bottom  = get_field( 'image_left_bottom' );
$image_right_top    = get_field( 'image_right_top' );
$image_right_bottom = get_field( 'image_right_bottom' );

// Block preview placeholder in admin
if ( $is_preview && empty( $section_title ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Story Sabbia Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}


?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> sabbia-section">
    <div class="container">
        <!-- Title -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-12">
            </div>
            <div class="col-lg-8 col-md-8 col-12">
                <?php if ( $section_title ) : ?>
                    <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                    <div class="section-description">
                        <p><?php echo wp_kses_post( nl2br( $description ) ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

      
        
<?php if ( $image_layout === '2_images' ) : ?>

    <!-- 2-Image Layout -->
    <div class="row g-4">
        <div class="col-lg-6 col-md-6 col-12">
            <?php if ( $image_left ) : ?>
                <div class="image-container">
                    <img
                        src="<?php echo esc_url( $image_left['url'] ); ?>"
                        alt="<?php echo esc_attr( $image_left['alt'] ?: __( 'Left Image', 'puk' ) ); ?>"
                        class="img-fluid"
                    >
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <?php if ( $image_right ) : ?>
                <div class="image-container">
                    <img
                        src="<?php echo esc_url( $image_right['url'] ); ?>"
                        alt="<?php echo esc_attr( $image_right['alt'] ?: __( 'Right Image', 'puk' ) ); ?>"
                        class="img-fluid"
                    >
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php elseif ( $image_layout === '4_images' ) : ?>

    <!-- 4-Image Layout -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 left_gal_main_box">
            <div class="left_gal_img_pos">
                <?php if ( $image_left_top ) : ?>
                    <div class="campaign_single_gallery image-container camp_full_width reveal active">
                        <img
                            decoding="async"
                            src="<?php echo esc_url( $image_left_top['url'] ); ?>"
                            alt="<?php echo esc_attr( $image_left_top['alt'] ?: __( 'Left Top Image', 'puk' ) ); ?>"
                            class="img-fluid"
                        >
                    </div>
                <?php endif; ?>
                <?php if ( $image_left_bottom ) : ?>
                    <div class="campaign_single_gallery image-container camp_half_width reveal active">
                        <img
                            decoding="async"
                            src="<?php echo esc_url( $image_left_bottom['url'] ); ?>"
                            alt="<?php echo esc_attr( $image_left_bottom['alt'] ?: __( 'Left Bottom Image', 'puk' ) ); ?>"
                            class="img-fluid"
                        >
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 right_gal_main_box">
            <div class="right_gal_img_pos">
                <?php if ( $image_right_top ) : ?>
                    <div class="campaign_single_gallery image-container camp_half_width reveal active">
                        <img
                            decoding="async"
                            src="<?php echo esc_url( $image_right_top['url'] ); ?>"
                            alt="<?php echo esc_attr( $image_right_top['alt'] ?: __( 'Right Top Image', 'puk' ) ); ?>"
                            class="img-fluid"
                        >
                    </div>
                <?php endif; ?>
                <?php if ( $image_right_bottom ) : ?>
                    <div class="campaign_single_gallery image-container camp_full_width reveal active">
                        <img
                            decoding="async"
                            src="<?php echo esc_url( $image_right_bottom['url'] ); ?>"
                            alt="<?php echo esc_attr( $image_right_bottom['alt'] ?: __( 'Right Bottom Image', 'puk' ) ); ?>"
                            class="img-fluid"
                        >
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php endif; ?>

</div>

<?php $text_editor = get_field( 'text_editor' ); ?>
<?php if ( $text_editor ) : ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="section-description-2">
                    <?php echo $text_editor; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</section>
