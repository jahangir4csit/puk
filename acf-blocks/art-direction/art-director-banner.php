<?php
/**
 * Block Template: Art Director Banner
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$banner_image = get_field( 'banner_image' );
$banner_title = get_field( 'banner_title' );

// Block preview placeholder in admin
if ( $is_preview && empty( $banner_image ) && empty( $banner_title ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Art Director Banner Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- art-direction banner section one start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> prjct_pg_dtls_1 art-direction-main">
    <div class="container">
        <div class="r_pd_title_section">
            <div class="row">
                <!-- project details section one start -->
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                    <div class="prjct_pg_dtls_1_right">
                        <?php if ( $banner_image ) : ?>
                            <div class="prjct_pg_dtls_1_right_img">
                                <img
                                    src="<?php echo esc_url( $banner_image['url'] ); ?>"
                                    alt="<?php echo esc_attr( $banner_image['alt'] ?: __( 'Art Direction Feature Image', 'puk' ) ); ?>"
                                >
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                    <div class="prjct_pg_dtls_1_lft">
                        <?php if ( $banner_title ) : ?>
                            <h1><?php echo wp_kses_post( nl2br( $banner_title ) ); ?></h1>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- art-direction banner section one end  -->
