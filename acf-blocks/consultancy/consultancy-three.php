<?php
/**
 * Block Template: Consultancy Three
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$top_heading = get_field( 'top_heading' );
$top_content = get_field( 'top_content' );
$bottom_boxes = get_field( 'bottom_boxes' );

// Block preview placeholder in admin
if ( $is_preview && empty( $top_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Consultancy Three Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- consultancy section three start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="consultancy_page_block <?php echo esc_attr( $block_class ); ?> cnsltncy_pg_3"> 
    <div class="container-fluid">
        <!-- top box   -->
        <?php if ( $top_heading || $top_content ) : ?>
            <div class="cnsltncy_pg_3_top">
                <?php if ( $top_heading ) : ?>
                    <h3><?php echo esc_html( $top_heading ); ?></h3>
                <?php endif; ?>
                
                <?php if ( $top_content ) : ?>
                    <p><?php echo wp_kses_post( nl2br( $top_content ) ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Bottom box   -->
        <?php if ( $bottom_boxes ) : ?>
            <div class="cnsltncy_pg_3_bottom">

                <?php foreach ( $bottom_boxes as $box ) : ?>
                    <div class="cnsltncy_pg_3_bottom_box">
                        <?php if ( ! empty( $box['box_heading'] ) ) : ?>
                            <h3><?php echo esc_html( $box['box_heading'] ); ?></h3>
                        <?php endif; ?>

                        <?php if ( ! empty( $box['box_subheading'] ) ) : ?>
                            <h4><?php echo wp_kses_post( nl2br( $box['box_subheading'] ) ); ?></h4>
                        <?php endif; ?>

                        <?php if ( ! empty( $box['box_content'] ) ) : ?>
                            <p><?php echo wp_kses_post( nl2br( $box['box_content'] ) ); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </div>
</section> 
<!-- consultancy section three end  -->
