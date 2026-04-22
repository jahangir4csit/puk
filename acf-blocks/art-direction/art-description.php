<?php
/**
 * Block Template: Art Description
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_label      = get_field( 'section_label' );
$main_heading       = get_field( 'main_heading' );
$description_content = get_field( 'description_content' );
$side_image         = get_field( 'side_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $section_label ) && empty( $main_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Art Description Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- art direction description section start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> art-description-main">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="title-box">
                    <?php if ( $section_label ) : ?>
                        <h3><?php echo esc_html( $section_label ); ?></h3>
                    <?php endif; ?>
                    <?php if ( $main_heading ) : ?>
                        <h2><?php echo wp_kses_post( nl2br( $main_heading ) ); ?></h2>
                    <?php endif; ?>
                </div>
                <?php if ( $description_content ) : ?>
                    <div class="description-box">
                        <article role="article">
                            <?php echo $description_content; ?>
                        </article>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <?php if ( $side_image ) : ?>
                    <div class="image-box">
                        <img
                            src="<?php echo esc_url( $side_image['url'] ); ?>"
                            alt="<?php echo esc_attr( $side_image['alt'] ); ?>"
                        >
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- art direction description section ends  -->
