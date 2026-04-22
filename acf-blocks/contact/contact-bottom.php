<?php
/**
 * Block Template: Contact Bottom (Newsletter)
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_heading = get_field( 'section_heading' );
$form_shortcode = get_field( 'form_shortcode' );

// Block preview placeholder in admin
if ( $is_preview && empty( $form_shortcode ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Contact Bottom Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Contact section four start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> cntct_pg_4 contact_page">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                <div class="cntct_pg_4_form">
                    <?php if ( $section_heading ) : ?>
                        <h3><?php echo wp_kses_post( nl2br( $section_heading ) ); ?></h3>
                    <?php endif; ?>
                    <?php if ( $form_shortcode ) : ?>
                        <?php echo do_shortcode( $form_shortcode ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact section four end  -->
