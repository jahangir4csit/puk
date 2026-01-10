<?php
/**
 * ACF Block: Home Slider
 *
 * Hero slider with video and image slides
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create ID attribute for block wrapper
$block_id = isset($block_id) ? $block_id : 'home-slider-' . uniqid();
$block_class = isset($block_class) ? $block_class : '';

// Get ACF fields
$slides = get_field('slides');

// Block preview placeholder in admin
if ( isset($is_preview) && $is_preview && empty($slides) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Home Slider Block - Add slides in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Hero Slider Section -->
<section id="<?php echo esc_attr($block_id); ?>" class="hero-slider-section <?php echo esc_attr($block_class); ?>">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">

            <?php if ($slides) : ?>
                <?php foreach ($slides as $slide) : ?>
                    <?php
                    $slide_type = $slide['slide_type'];
                    $video_file = $slide['video_file'];
                    $image = $slide['image'];
                    ?>

                    <div class="swiper-slide">
                        <?php if ($slide_type === 'video' && $video_file) : ?>
                            <!-- Video Slide -->
                            <div class="hero-slide-video">
                                <video class="hero-video" autoplay muted loop playsinline>
                                    <source src="<?php echo esc_url($video_file['url']); ?>" type="<?php echo esc_attr($video_file['mime_type']); ?>">
                                    <?php _e('Your browser does not support the video tag.', 'puk'); ?>
                                </video>
                            </div>
                        <?php elseif ($slide_type === 'image' && $image) : ?>
                            <!-- Image Slide -->
                            <div class="hero-slide-image" style="background-image: url('<?php echo esc_url($image['url']); ?>');">
                            </div>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>
