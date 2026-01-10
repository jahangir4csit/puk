<?php
/**
 * Block Template: Warranty Bottom
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$boxes = get_field( 'boxes' );
$right_image = get_field( 'right_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $boxes ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Warranty Bottom Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- warranty section four start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="warranty_page_block <?php echo esc_attr( $block_class ); ?> wrnty_pg_4"> 
    <div class="container-fluid">

        <div class="wrnty_pg_r_bx">
            <!-- left side  -->
            <div class="wrnty_pg_4_left_box"> 

                <?php if ( $boxes ) : ?>
                    <?php foreach ( $boxes as $box ) : ?>
                        <!-- box  -->
                        <div class="wrnty_pg_4_box">
                            <?php if ( ! empty( $box['icon'] ) ) : ?>
                                <img 
                                    src="<?php echo esc_url( $box['icon']['url'] ); ?>" 
                                    alt="<?php echo esc_attr( $box['icon']['alt'] ?: __( 'Icon', 'puk' ) ); ?>"
                                    width="<?php echo esc_attr( $box['icon']['width'] ); ?>"
                                    height="<?php echo esc_attr( $box['icon']['height'] ); ?>"
                                >
                            <?php endif; ?>

                            <?php if ( ! empty( $box['heading'] ) ) : ?>
                                <h3><?php echo wp_kses_post( nl2br( $box['heading'] ) ); ?></h3>
                            <?php endif; ?>

                            <?php if ( ! empty( $box['content'] ) ) : ?>
                                <?php echo wp_kses_post( wpautop( $box['content'] ) ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <!-- right side  -->
            <div class="wrnty_pg_1_right">
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
</section> 
<!-- warranty section four end  -->
