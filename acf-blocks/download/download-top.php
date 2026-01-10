<?php
/**
 * Block Template: Download Top
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$page_title = get_field( 'page_title' );
$heading = get_field( 'heading' );
$description = get_field( 'description' );
$featured_image = get_field( 'featured_image' );
$content_title = get_field( 'content_title' );
$content_description = get_field( 'content_description' );
$download_icon = get_field( 'download_icon' );
$download_link = get_field( 'download_link' );
$download_button_text = get_field( 'download_button_text' ) ?: __( 'Download', 'puk' );

// Block preview placeholder in admin
if ( $is_preview && empty( $page_title ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Download Top Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- download section one start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="download_page_block <?php echo esc_attr( $block_class ); ?> dwnld_pg_1">
  <div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <?php if ( $page_title ) : ?>
            <div class="dwnld_pg_title">
              <h1><?php echo esc_html( $page_title ); ?></h1>
            </div>
          <?php endif; ?>

          <?php if ( $heading || $description ) : ?>
            <div class="dwnld_pg_desc">
                <?php if ( $heading ) : ?>
                  <h2><?php echo wp_kses_post( $heading ); ?></h2>
                <?php endif; ?>

                <?php if ( $description ) : ?>
                  <p><?php echo wp_kses_post( $description ); ?></p>
                <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="dwnld_pg_1_btm">
            <!-- left side  -->
            <?php if ( $featured_image ) : ?>
              <div class="dwnld_pg_1_lft_img">
                <a href="<?php echo esc_url( $download_link ?: '#' ); ?>">
                  <img
                    class="dwnld_sec_2_box_img"
                    src="<?php echo esc_url( $featured_image['url'] ); ?>"
                    alt="<?php echo esc_attr( $featured_image['alt'] ?: __( 'Download', 'puk' ) ); ?>"
                    width="<?php echo esc_attr( $featured_image['width'] ); ?>"
                    height="<?php echo esc_attr( $featured_image['height'] ); ?>"
                    alt="download" 
                  >
                </a>
              </div>
            <?php endif; ?>

            <!-- right side  -->
            <?php if ( $content_title || $content_description || $download_link ) : ?>
              <div class="dwnld_pg_1_rght">
                  <?php if ( $content_title ) : ?>
                    <h4><?php echo esc_html( $content_title ); ?></h4>
                  <?php endif; ?>

                  <?php if ( $content_description ) : ?>
                    <p><?php echo wp_kses_post( $content_description ); ?></p>
                  <?php endif; ?>

                  <?php if ( $download_link ) : ?>
                    <a href="<?php echo esc_url( $download_link ); ?>">
                      <?php if ( $download_icon ) : ?>
                        <span>
                          <img
                            src="<?php echo esc_url( $download_icon['url'] ); ?>"
                            alt="<?php echo esc_attr( $download_icon['alt'] ?: __( 'Download icon', 'puk' ) ); ?>"
                          >
                        </span>
                      <?php endif; ?>
                      <?php echo esc_html( $download_button_text ); ?>
                    </a>
                  <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
    </div>
  </div>
</section>
<!-- download section one end  -->
