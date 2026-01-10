<?php
/**
 * Block Template: Download Stories
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_title = get_field( 'section_title' );
$download_box = get_field( 'download_box' );

// Block preview placeholder in admin
if ( $is_preview && empty( $download_box ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Download Stories Block - Add download boxes in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- download section two start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="download_page_block <?php echo esc_attr( $block_class ); ?> dwnld_sec_2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                 <?php if ( $section_title ) : ?>
                   <div class="download_page_sec_title">
                      <h2><?php echo esc_html( $section_title ); ?></h2>
                  </div>
                 <?php endif; ?>
            </div>
        </div>

        <?php if ( $download_box ) : ?>
          <div class="row dwnld_sec_2_box_sec">
              <?php foreach ( $download_box as $box ) :
                  $image = $box['image'];
                  $title = $box['title'];
                  $description = $box['description'];
                  $download_url = $box['download_url'];
              ?>
                <!-- download box  -->
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                     <div class="dwnld_sec_2_box">
                         <?php if ( $image && $download_url ) : ?>
                           <a href="<?php echo esc_url( $download_url ); ?>">
                              <img 
                                class="dwnld_sec_2_box_img"
                                src="<?php echo esc_url( $image['url'] ); ?>"
                                alt="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                                width="<?php echo esc_attr( $image['width'] ); ?>"
                                height="<?php echo esc_attr( $image['height'] ); ?>"
                                alt="story-download"
                              >
                              <?php if ( $title ) : ?>
                                <h2><?php echo esc_html( $title ); ?></h2>
                              <?php endif; ?>
                           </a>
                         <?php elseif ( $image ) : ?>
                           <img
                              src="<?php echo esc_url( $image['url'] ); ?>"
                              alt="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                              width="<?php echo esc_attr( $image['width'] ); ?>"
                              height="<?php echo esc_attr( $image['height'] ); ?>"
                              alt="story-download"
                           >
                           <?php if ( $title ) : ?>     
                             <h2><?php echo esc_html( $title ); ?></h2>
                           <?php endif; ?>
                         <?php endif; ?>

                          <?php if ( $description ) : ?>
                            <p><?php echo wp_kses_post( $description ); ?></p>
                          <?php endif; ?>

                          <?php if ( $download_url ) : ?>
                            <a href="<?php echo esc_url( $download_url ); ?>">
                              <span><img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download-1.svg" alt="download-svg"></span>
                              Download
                            </a>
                          <?php endif; ?>
                    </div>
                </div>
              <?php endforeach; ?>
          </div>
        <?php endif; ?>
    </div>
</section>

<!-- download section two end  -->
