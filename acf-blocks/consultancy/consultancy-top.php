<?php
/**
 * Block Template: Consultancy Top
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$main_heading = get_field( 'main_heading' );
$right_image = get_field( 'right_image' );
$section_1_heading = get_field( 'section_1_heading' );
$section_1_content = get_field( 'section_1_content' );
$section_2_heading = get_field( 'section_2_heading' );
$section_2_content = get_field( 'section_2_content' );
$steps = get_field( 'steps' );

// Block preview placeholder in admin
if ( $is_preview && empty( $main_heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Consultancy Top Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- consultancy section one start  -->
<section id="<?php echo esc_attr( $block_id ); ?>" class="consultancy_page_block <?php echo esc_attr( $block_class ); ?> cnsltncy_pg_1"> 
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 cnsltncy_pg_1_left_row">
                <div class="cnsltncy_pg_1_left">
                    <div class="cnsltncy_pg_1_left_box">
                        <?php if ( $main_heading ) : ?>
                            <h1><?php echo wp_kses_post( nl2br( $main_heading ) ); ?></h1>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
                <div class="cnsltncy_pg_1_right">
        
                    <?php if ( $right_image ) : ?>
                        <div class="img_top">
                            <img 
                                src="<?php echo esc_url( $right_image['url'] ); ?>" 
                                alt="<?php echo esc_attr( $right_image['alt'] ?: __( 'Consultancy right image', 'puk' ) ); ?>"
                                width="<?php echo esc_attr( $right_image['width'] ); ?>"
                                height="<?php echo esc_attr( $right_image['height'] ); ?>"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ( $section_1_heading || $section_1_content ) : ?>
                        <div class="middle_box">
                            <?php if ( $section_1_heading ) : ?>
                                <h3><?php echo esc_html( $section_1_heading ); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ( $section_1_content ) : ?>
                                <?php echo wp_kses_post( wpautop( $section_1_content ) ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $section_2_heading || $section_2_content ) : ?>
                        <div class="middle_box">
                            <?php if ( $section_2_heading ) : ?>
                                <h3><?php echo esc_html( $section_2_heading ); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ( $section_2_content ) : ?>
                                <?php echo wp_kses_post( wpautop( $section_2_content ) ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $steps ) : ?>
                        <div class="step_box">
                            <?php 
                            $step_number = 1;
                            foreach ( $steps as $step ) : 
                            ?>
                                <div class="step_box_item">
                                    <span><?php echo esc_html( $step_number ); ?>. </span>
                                    <?php if ( ! empty( $step['step_title'] ) ) : ?>
                                        <h3><?php echo esc_html( $step['step_title'] ); ?></h3>
                                    <?php endif; ?>
                                </div>
                            <?php 
                            $step_number++;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</section> 
<!-- consultancy section one end  -->
