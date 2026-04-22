<?php
/**
 * Block Template: Art Creation
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_title    = get_field( 'section_title' );
$product_families = get_field( 'product_families' ); // array of WP_Term objects

// Block preview placeholder in admin
if ( $is_preview && empty( $section_title ) && empty( $product_families ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Art Creation Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- art product creation section start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> product-creation-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="creation-flex">
                    <?php if ( $section_title ) : ?>
                        <div class="title">
                            <?php echo esc_html( $section_title ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( $product_families ) : ?>
                        <div class="creation-links">
                            <ul>
                                <?php foreach ( $product_families as $term ) :
                                    $term_url = get_term_link( $term );
                                    if ( is_wp_error( $term_url ) ) {
                                        $term_url = '#';
                                    }
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url( $term_url ); ?>">
                                            <?php echo esc_html( strtoupper( $term->name ) ); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- art product creation section ends -->
