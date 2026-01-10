<?php
/**
 * ACF Block: Home Matter
 *
 * Made to Matter section with heading and content columns
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create ID attribute for block wrapper
$block_id = isset($block_id) ? $block_id : 'home-matter-' . uniqid();
$block_class = isset($block_class) ? $block_class : '';

// Get ACF fields
$heading = get_field('heading');
$main_content = get_field('main_content');
$content_items = get_field('content_items');

// Block preview placeholder in admin
if ( isset($is_preview) && $is_preview && empty($heading) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Home Matter Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Made to Matter Section -->
<section id="<?php echo esc_attr($block_id); ?>" class="made-to-matter-section <?php echo esc_attr($block_class); ?>">
    <div class="container">
        <?php if ($heading) : ?>
            <h2 class="gs-reveal"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>
        <div class="matter-row row justify-content-md-between">
            <div class="col-lg-12 col-xl-6 gs-reveal-left">
                <div class="matter-column matter-text">
                    <?php if ($main_content) : ?>
                        <article class="matter-text">
                            <?php echo wp_kses_post($main_content); ?>
                        </article>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-12 col-xl-4">
                <div class="matter-grid gs-stagger-reveal-up">
                    <?php if ($content_items) : ?>
                        <?php foreach ($content_items as $item) : ?>
                            <div class="matter-column">
                                <div class="gs-split-reveal-wrap">
                                    <?php if ($item['text']) : ?>
                                        <p class="matter-text-sm"><?php echo wp_kses_post($item['text']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
