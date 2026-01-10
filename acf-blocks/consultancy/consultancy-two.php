<?php
/**
 * Block Template: Consultancy Two
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$left_image = get_field( 'left_image' );
$right_image = get_field( 'right_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $left_image ) && empty( $right_image ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Consultancy Two Block - Upload images in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- consultancy section two start  -->
<div id="<?php echo esc_attr( $block_id ); ?>" class="consultancy_page_block <?php echo esc_attr( $block_class ); ?> cnsltncy_pg_2">
    <div class="container-fluid">

        <?php if ( $left_image || $right_image ) : ?>
            <div class="cnsltncy_pg_2_img_bx">
                <?php if ( $left_image ) : ?>
                    <div class="cnsltncy_pg_2_img_lft">
                        <img 
                            src="<?php echo esc_url( $left_image['url'] ); ?>" 
                            alt="<?php echo esc_attr( $left_image['alt'] ?: __( 'Consultancy Image', 'puk' ) ); ?>"
                            width="<?php echo esc_attr( $left_image['width'] ); ?>"
                            height="<?php echo esc_attr( $left_image['height'] ); ?>"
                        >
                    </div>
                <?php endif; ?>

                <?php if ( $right_image ) : ?>
                    <div class="cnsltncy_pg_2_img_rght">
                        <img 
                            src="<?php echo esc_url( $right_image['url'] ); ?>" 
                            alt="<?php echo esc_attr( $right_image['alt'] ?: __( 'Consultancy Image', 'puk' ) ); ?>"
                            width="<?php echo esc_attr( $right_image['width'] ); ?>"
                            height="<?php echo esc_attr( $right_image['height'] ); ?>"
                        >
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
      
    </div>
</div>

<!-- consultancy section two end  -->
