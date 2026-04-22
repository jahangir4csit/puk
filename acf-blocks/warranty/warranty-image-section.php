<?php
/**
 * Block Template: Warranty Image Section
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_image = get_field( 'section_image' );

// Block preview placeholder in admin
if ( $is_preview && empty( $section_image ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Warranty Image Section Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- warranty section two start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="warranty_page_block <?php echo esc_attr( $block_class ); ?> wrnty_pg_2"> 
    <?php if ( $section_image ) : ?>
        <div class="wrnty_pg_2_right">
            <img 
                src="<?php echo esc_url( $section_image['url'] ); ?>" 
                alt="<?php echo esc_attr( $section_image['alt'] ?: __( 'Warranty section image', 'puk' ) ); ?>"
                width="<?php echo esc_attr( $section_image['width'] ); ?>"
                height="<?php echo esc_attr( $section_image['height'] ); ?>"
            >
        </div>
    <?php endif; ?>
</section> 
<!-- warranty section two end  -->
