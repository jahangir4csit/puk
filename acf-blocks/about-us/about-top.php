<?php
/**
 * Block Template: About Top
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$main_heading = get_field( 'main_heading' );
$description = get_field( 'description' );
$right_image = get_field( 'right_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $main_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'About Top Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- About Us section one start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> abt_us_1">
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 abt_us_1_lft_row">
                <div class="abt_us_1_lft">
                    <?php if ( $main_heading ) : ?>
                        <h1><?php echo wp_kses_post( nl2br( $main_heading ) ); ?></h1>
                    <?php endif; ?>

                    <?php if ( $description ) : ?>
                        <?php echo wp_kses_post( wpautop( $description ) ); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12">
                <div class="abt_us_1_right">
                    <?php if ( $right_image ) : ?>
                        <!-- image  -->
                        <div class="abt_us_1_right_img">
                            <img
                                src="<?php echo esc_url( $right_image['url'] ); ?>"
                                alt="<?php echo esc_attr( $right_image['alt'] ?: __( 'About Image', 'puk' ) ); ?>"
                                width="<?php echo esc_attr( $right_image['width'] ); ?>"
                                height="<?php echo esc_attr( $right_image['height'] ); ?>"
                            >
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- About Us section one end  -->
