<?php
/**
 * Block Template: About Bottom
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$left_content = get_field( 'left_content' );
$right_boxes = get_field( 'right_boxes' );

// Block preview placeholder in admin
if ( $is_preview && empty( $left_content ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'About Bottom Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- About Us section four start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> abt_us_4"> 
    <div class="container-fluid">

        <div class="abt_us_4_bx">
            <!-- left side  -->
            <?php if ( $left_content ) : ?>
                <div class="abt_us_4_bx_lft">
                    <p><?php echo wp_kses_post( nl2br( $left_content ) ); ?></p>
                </div>
            <?php endif; ?>

            <!-- right side  -->
            <?php if ( $right_boxes ) : ?>
                <div class="abt_us_4_bx_rhgt">
                    <?php foreach ( $right_boxes as $box ) : ?>
                        <?php if ( ! empty( $box['box_content'] ) ) : ?>
                            <div class="abt_us_4_bx_rhgt_bx">
                                <p><?php echo wp_kses_post( nl2br( $box['box_content'] ) ); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section> 
<!-- About Us section four end  -->
