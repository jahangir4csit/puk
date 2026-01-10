<?php
/**
 * Block Template: Consultancy Four
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$image_gallery = get_field( 'image_gallery' );

// Block preview placeholder in admin
if ( $is_preview && empty( $image_gallery ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Consultancy Four Block - Upload gallery images in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- consultancy section four start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="consultancy_page_block <?php echo esc_attr( $block_class ); ?> cnsltncy_pg_4">  

    <div class="container-fluid">      
        <!-- Image grid   -->
        <?php if ( $image_gallery ) : ?>
            <div class="cnsltncy_pg_img_grid"> 
                <?php foreach ( $image_gallery as $image ) : ?>
                    <div class="zoom_imggrid">
                        <img 
                            src="<?php echo esc_url( $image['url'] ); ?>" 
                            alt="<?php echo esc_attr( $image['alt'] ?: __( 'Consultancy image', 'puk' ) ); ?>"
                            width="<?php echo esc_attr( $image['width'] ); ?>"
                            height="<?php echo esc_attr( $image['height'] ); ?>"
                        >
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section> 

<!-- consultancy section four end  -->
