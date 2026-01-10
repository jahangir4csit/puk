<?php
/**
 * Block Template: Warranty Process
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$left_heading = get_field( 'left_heading' );
$left_content = get_field( 'left_content' );
$middle_content = get_field( 'middle_content' );
$right_content = get_field( 'right_content' );

// Block preview placeholder in admin
if ( $is_preview && empty( $left_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Warranty Process Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- warranty section three start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="warranty_page_block <?php echo esc_attr( $block_class ); ?> wrnty_pg_3"> 
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="wrnty_pg_3_left"> 
                    <?php if ( $left_heading ) : ?>
                        <h3><?php echo esc_html( $left_heading ); ?></h3>
                    <?php endif; ?>

                    <?php if ( $left_content ) : ?>
                        <?php echo wp_kses_post( wpautop( $left_content ) ); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="wrnty_pg_3_rght_bx">

                    <?php if ( $middle_content ) : ?>
                        <div class="wrnty_pg_3_mid">
                            <?php echo wp_kses_post( wpautop( $middle_content ) ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $right_content ) : ?>
                        <div class="wrnty_pg_3_right">
                            <?php echo wp_kses_post( wpautop( $right_content ) ); ?>
                        </div>
                    <?php endif; ?>

                </div>
                
            </div>


        </div>
    </div>
</section> 
<!-- warranty section three end  -->
