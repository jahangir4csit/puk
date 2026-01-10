<?php
/**
 * ACF Block: Home Intro
 *
 * Introduction section with content and two cards
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create ID attribute for block wrapper
$block_id = isset($block_id) ? $block_id : 'home-intro-' . uniqid();
$block_class = isset($block_class) ? $block_class : '';

// Get ACF fields
$intro_content = get_field('intro_content');
$product_card_image = get_field('product_card_image');
$product_card_link_text = get_field('product_card_link_text') ?: __('Explore Products', 'puk');
$product_card_link_url = get_field('product_card_link_url');
$project_card_image = get_field('project_card_image');
$project_card_link_text = get_field('project_card_link_text') ?: __('Our Projects', 'puk');
$project_card_link_url = get_field('project_card_link_url');

// Block preview placeholder in admin
if ( isset($is_preview) && $is_preview && empty($intro_content) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Home Intro Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<!-- Introduction Section -->
<section id="<?php echo esc_attr($block_id); ?>" class="intro-section <?php echo esc_attr($block_class); ?>">
    <div class="container">
        <div class="intro-grid">

            <div class="intro-grid-left gs-reveal-left">
                <?php if ($intro_content) : ?>
                    <article class="intro-content gs-text-reveal">
                        <?php echo wp_kses_post($intro_content); ?>
                    </article>
                <?php endif; ?>

                <?php if ($product_card_image) : ?>
                    <div class="intro-product-card gs-scale-image-wrap">
                        <div class="gs-split-reveal-wrap">
                            <img src="<?php echo esc_url($product_card_image['url']); ?>"
                                alt="<?php echo esc_attr($product_card_image['alt'] ?: __('Product Spotlight', 'puk')); ?>"
                                class="gs-scale-image">
                        </div>
                        <?php if ($product_card_link_url) : ?>
                            <a href="<?php echo esc_url($product_card_link_url); ?>"
                                class="intro-card-link d-inline-flex align-items-center justify-content-between gs-link-reveal">
                                <?php echo esc_html($product_card_link_text); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                                    <path d="M20.0719 9.18568L19.4146 9.18477L13.929 9.18477L13.9441 10.4862L17.8585 10.4848L8.68663 19.6567L9.60089 20.571L18.7723 11.3996L18.7714 15.3135L20.0733 15.3281L20.0733 9.84258L20.0719 9.18568Z" fill="black" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="intro-grid-right gs-reveal-right">
                <?php if ($project_card_image) : ?>
                    <div class="intro-project-card gs-scale-image-wrap">
                        <div class="gs-split-reveal-wrap">
                            <img src="<?php echo esc_url($project_card_image['url']); ?>"
                                alt="<?php echo esc_attr($project_card_image['alt'] ?: __('Our Projects', 'puk')); ?>"
                                class="gs-scale-image">
                        </div>
                        <?php if ($project_card_link_url) : ?>
                            <a href="<?php echo esc_url($project_card_link_url); ?>"
                                class="intro-card-link d-inline-flex align-items-center justify-content-between gs-link-reveal">
                                <?php echo esc_html($project_card_link_text); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                                    <path d="M20.0719 9.18568L19.4146 9.18477L13.929 9.18477L13.9441 10.4862L17.8585 10.4848L8.68663 19.6567L9.60089 20.571L18.7723 11.3996L18.7714 15.3135L20.0733 15.3281L20.0733 9.84258L20.0719 9.18568Z" fill="black" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
