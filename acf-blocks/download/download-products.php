<?php
/**
 * Block Template: Download Products
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_title = get_field( 'section_title' );
$products = get_field( 'products' );
$download_icon = get_field( 'download_icon' );
$enable_shadow = get_field( 'enable_shadow' );

// Block preview placeholder in admin
if ( $is_preview && empty( $products ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Download Products Block - Add products in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- download section three start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="download_page_block <?php echo esc_attr( $block_class ); ?> dwnld_sec_3">
    <div class="container">

        <!-- Products Section start  -->
        <?php if ( $section_title ) : ?>
          <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="download_page_sec_title">
                      <h2><?php echo esc_html( $section_title ); ?></h2>
                  </div>
              </div>
          </div>
        <?php endif; ?>

        <?php if ( $products ) : ?>
          <div class="download-grid dwnld_sec_2_box_sec">
              <?php foreach ( $products as $product ) :
                  $image = $product['product_image'];
                  $title = $product['product_title'];
                  $download_link = $product['download_link'];
              ?>
                <!-- download item  -->
                <div class="download-item">
                    <a href="<?php echo esc_url( $download_link ?: '#' ); ?>" class="dwnld_sec_3_box" download>
                         <?php if ( $image ) : ?>
                           <div class="download_img_holder<?php echo $enable_shadow ? ' shadow_item' : ''; ?>">
                            <img
                              src="<?php echo esc_url( $image['url'] ); ?>"
                              alt="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                              width="<?php echo esc_attr( $image['width'] ); ?>"
                              height="<?php echo esc_attr( $image['height'] ); ?>"
                              alt="story-download"
                            >
                           </div>
                         <?php endif; ?>

                         <?php if ( $title ) : ?>
                          <div class="download_title_wrap">
                            <h3><?php echo esc_html( $title ); ?></h3>
                            <?php if ( $download_icon ) : ?>
                               <span>
                                 <img
                                   src="<?php echo esc_url( $download_icon['url'] ); ?>"
                                   alt="<?php echo esc_attr( $download_icon['alt'] ?: __( 'Download icon', 'puk' ) ); ?>"
                                 >
                               </span>
                             <?php endif; ?>
                          </div>
                         <?php endif; ?>
                    </a>
                </div>
              <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <!-- Products Section end  -->

    </div>
</section>
<!-- download section three end  -->
