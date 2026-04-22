<?php
/**
 * Block Template: Contact Form
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$form_title = get_field( 'form_title' );
$form_shortcode = get_field( 'form_shortcode' );
$contact_info_boxes = get_field( 'contact_info_boxes' );

// Block preview placeholder in admin
if ( $is_preview && empty( $form_shortcode ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Contact Form Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Contact section two start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> cntct_pg_2 contact_page">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                <div class="cntct_pg_2_form">

                    <?php if ( $form_title ) : ?>
                        <h2><?php echo esc_html( $form_title ); ?></h2>
                    <?php endif; ?>

                    <?php if ( $form_shortcode ) : ?>
                        <?php echo do_shortcode( $form_shortcode ); ?>
                    <?php endif; ?>

                </div>
            </div>

            <?php if ( $contact_info_boxes ) : ?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                    <div class="cntct_pg_2_box">
                        <?php foreach ( $contact_info_boxes as $box ) : ?>
                            <?php if ( ! empty( $box['box_label'] ) || ! empty( $box['box_value'] ) ) : ?>
                                <div class="cntct_pg_2_right_box">
                                    <?php if ( ! empty( $box['box_label'] ) ) : ?>
                                        <span><?php echo esc_html( $box['box_label'] ); ?></span>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $box['box_value'] ) ) : ?>
                                        <p><?php echo esc_html( $box['box_value'] ); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
<!-- Contact section two end  -->
