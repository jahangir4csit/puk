<?php
/**
 * Block Template: Contact Top
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$page_title = get_field( 'page_title' );
$subtitle = get_field( 'subtitle' );
$intro_paragraph = get_field( 'intro_paragraph' );
$list_items = get_field( 'list_items' );
$closing_paragraph = get_field( 'closing_paragraph' );

// Block preview placeholder in admin
if ( $is_preview && empty( $page_title ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Contact Top Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Contact section one start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> cntct_pg_1 contact_page">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                <?php if ( $page_title ) : ?>
                    <div class="cntct_pg_title">
                        <h1><?php echo esc_html( $page_title ); ?></h1>
                    </div>
                <?php endif; ?>

                <div class="cntct_pg_desc">

                    <?php if ( $subtitle ) : ?>
                        <h3><?php echo esc_html( $subtitle ); ?></h3>
                    <?php endif; ?>

                    <?php if ( $intro_paragraph ) : ?>
                        <p><?php echo wp_kses_post( nl2br( $intro_paragraph ) ); ?></p>
                    <?php endif; ?>

                    <?php if ( $list_items ) : ?>
                        <ul>
                            <?php foreach ( $list_items as $item ) : ?>
                                <?php if ( ! empty( $item['list_item'] ) ) : ?>
                                    <li><?php echo esc_html( $item['list_item'] ); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ( $closing_paragraph ) : ?>
                        <p><?php echo wp_kses_post( nl2br( $closing_paragraph ) ); ?></p>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
</section>
<!-- Contact section one end  -->
