<?php
/**
 * Block Template: Contact Image Section
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_heading = get_field( 'section_heading' );
$address_text = get_field( 'address_text' );
$section_image = get_field( 'section_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $section_heading ) && empty( $section_image ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Contact Image Section Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Contact section three start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> cntct_pg_3 contact_page">
    <div class="container">
        <div class="row">
            <div class="cntct_pg_3_flex">

                <?php if ( $section_heading || $address_text ) : ?>
                    <div class="cntct_pg_3_left">
                        <?php if ( $section_heading ) : ?>
                            <h3><?php echo esc_html( $section_heading ); ?></h3>
                        <?php endif; ?>

                        <?php if ( $address_text ) : ?>
                            <p><?php echo wp_kses_post( nl2br( $address_text ) ); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $section_image ) : ?>
                    <div class="cntct_pg_3_right">
                        <img
                            src="<?php echo esc_url( $section_image['url'] ); ?>"
                            alt="<?php echo esc_attr( $section_image['alt'] ?: __( 'Contact Image', 'puk' ) ); ?>"
                            <?php if ( ! empty( $section_image['width'] ) ) : ?>
                                width="<?php echo esc_attr( $section_image['width'] ); ?>"
                            <?php endif; ?>
                            <?php if ( ! empty( $section_image['height'] ) ) : ?>
                                height="<?php echo esc_attr( $section_image['height'] ); ?>"
                            <?php endif; ?>
                        >
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
<!-- Contact section three end  -->
