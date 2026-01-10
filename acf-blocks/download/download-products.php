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
$product_sections = get_field( 'product_sections' );
$download_icon = get_field( 'download_icon' );

// Block preview placeholder in admin
if ( $is_preview && empty( $product_sections ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Download Products Block - Add product sections in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- download section three start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="download_page_block <?php echo esc_attr( $block_class ); ?> dwnld_sec_3">
    <div class="container-fluid">

        <?php if ( $product_sections ) : ?>
          <?php foreach ( $product_sections as $section ) :
              $section_title = $section['section_title'];
              $products = $section['products'];
          ?>
            <!-- Products Section start  -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <?php if ( $section_title ) : ?>
                      <div class="download_page_sec_title">
                          <h2><?php echo esc_html( $section_title ); ?></h2>
                      </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ( $products ) : ?>
              <div class="row dwnld_sec_2_box_sec">
                  <?php foreach ( $products as $product ) :
                      $image = $product['product_image'];
                      $title = $product['product_title'];
                      $download_link = $product['download_link'];
                  ?>
                    <!-- download box  -->
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                        <div class="dwnld_sec_3_box">
                             <?php if ( $image ) : ?>
                               <a href="<?php echo esc_url( $download_link ?: '#' ); ?>">
                                <img
                                  src="<?php echo esc_url( $image['url'] ); ?>"
                                  alt="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                                  width="<?php echo esc_attr( $image['width'] ); ?>"
                                  height="<?php echo esc_attr( $image['height'] ); ?>"
                                  alt="story-download" 
                                >
                               </a>
                             <?php endif; ?>

                             <?php if ( $title ) : ?>
                               <a href="<?php echo esc_url( $download_link ?: '#' ); ?>">
                                 <?php echo esc_html( $title ); ?>
                                 <?php if ( $download_icon ) : ?>
                                   <span>
                                     <img
                                       src="<?php echo esc_url( $download_icon['url'] ); ?>"
                                       alt="<?php echo esc_attr( $download_icon['alt'] ?: __( 'Download icon', 'puk' ) ); ?>"
                                     >
                                   </span>
                                 <?php endif; ?>
                               </a>
                             <?php endif; ?>
                        </div>
                    </div>
                  <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <!-- Products Section end  -->
          <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>

<!-- download section three end  -->
