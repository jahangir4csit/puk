<?php
/**
 * Block Template: Warranty Top
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$heading = get_field( 'heading' );
$description = get_field( 'description' );
$right_image = get_field( 'right_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Warranty Top Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- warranty section one start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="warranty_page_block <?php echo esc_attr( $block_class ); ?> wrnty_pg_1"> 
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="wrnty_pg_1_left">
                    <div class="wrnty_pg_1_left_box">

                        <?php if ( $heading ) : ?>
                            <h1><?php echo wp_kses_post( nl2br( $heading ) ); ?></h1>
                        <?php endif; ?>

                        <?php if ( $description ) : ?>
                            <p><?php echo wp_kses_post( nl2br( $description ) ); ?></p>
                        <?php endif; ?>

                    </div>
                    
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="wrnty_pg_1_right_top">
                    <?php if ( $right_image ) : ?>
                        <img 
                            src="<?php echo esc_url( $right_image['url'] ); ?>" 
                            alt="<?php echo esc_attr( $right_image['alt'] ?: __( 'Warranty right image', 'puk' ) ); ?>"
                            width="<?php echo esc_attr( $right_image['width'] ); ?>"
                            height="<?php echo esc_attr( $right_image['height'] ); ?>"
                        >
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section> 
<!-- warranty section one end  -->
