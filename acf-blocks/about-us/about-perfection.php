<?php
/**
 * Block Template: About Perfection
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_heading = get_field( 'section_heading' );
$paragraph_1 = get_field( 'paragraph_1' );
$paragraph_2 = get_field( 'paragraph_2' );
$bottom_heading = get_field( 'bottom_heading' );
$bottom_image = get_field( 'bottom_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $section_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'About Perfection Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- About Us section three start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> abt_us_3"> 
    <div class="container-fluid">

        <?php if ( $section_heading ) : ?>
            <div class="abt_us_3_top">
                <h4><?php echo esc_html( $section_heading ); ?></h4> 
            </div>
        <?php endif; ?>

        <div class="abt_us_3_bx">
            <!-- left side  -->
            <div class="abt_us_2_bx_lft">
                
            </div>

            <!-- Right side  -->
            <div class="abt_us_3_bx_rhgt">

                <?php if ( $paragraph_1 ) : ?>
                    <p><?php echo wp_kses_post( nl2br( $paragraph_1 ) ); ?></p>
                <?php endif; ?>

                <?php if ( $paragraph_2 ) : ?>
                    <p><?php echo wp_kses_post( nl2br( $paragraph_2 ) ); ?></p>
                <?php endif; ?>

            </div>

            <?php if ( $bottom_heading || $bottom_image ) : ?>
                <div class="abt_us_3_bx_rhgt_btm">
                    <?php if ( $bottom_heading ) : ?>
                        <h2><?php echo wp_kses_post( nl2br( $bottom_heading ) ); ?></h2>
                    <?php endif; ?>

                    <?php if ( $bottom_image ) : ?>
                        <img 
                            src="<?php echo esc_url( $bottom_image['url'] ); ?>" 
                            alt="<?php echo esc_attr( $bottom_image['alt'] ?: __( 'About Right Bottom', 'puk' ) ); ?>"
                            width="<?php echo esc_attr( $bottom_image['width'] ); ?>"
                            height="<?php echo esc_attr( $bottom_image['height'] ); ?>"
                        >
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section> 
<!-- About Us section three end  -->
