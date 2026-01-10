<?php
/**
 * ACF Block: Home News
 *
 * Latest News/Blog section with swiper slider
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create ID attribute for block wrapper
$block_id = isset($block_id) ? $block_id : 'home-news-' . uniqid();
$block_class = isset($block_class) ? $block_class : '';

// Get ACF fields
$section_label = get_field('section_label') ?: __('Latest BLOG -', 'puk');
$posts_per_page = get_field('posts_per_page') ?: 5;

// Query blog posts
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => intval($posts_per_page),
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$news_query = new WP_Query($args);

// Block preview placeholder in admin
if ( isset($is_preview) && $is_preview && !$news_query->have_posts() ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Home News Block - Add some blog posts first', 'puk' ) . '</p>';
    echo '</div>';
    wp_reset_postdata();
    return;
}
?>

<!-- Latest News Section -->
<section id="<?php echo esc_attr($block_id); ?>" class="latest-news-section <?php echo esc_attr($block_class); ?>">
    <div class="container">
        <h3 class="section-label gs-reveal"><?php echo esc_html($section_label); ?></h3>
        <div class="news-grid gs-news-reveal">
            <div class="swiper news-swiper">
                <div class="swiper-wrapper">
                    <?php if ($news_query->have_posts()) : ?>
                        <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                            <?php

                                $post_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                $post_excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 15, '...');
                                $post_subtitle = get_field('subtitle', get_the_ID());
                          
                            // Fallback image if no featured image
                            if (!$post_image) {
                                $post_image = get_template_directory_uri() . '/assets/images/default-news.jpg';
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="news-card">
                                    <a href="<?php the_permalink(); ?>" class="news-link">
                                        <div class="news-image">
                                            <img src="<?php echo esc_url($post_image); ?>"
                                                alt="<?php echo esc_attr(get_the_title()); ?>">
                                        </div>
                                        <div class="news-title-wrap">
                                            <h4 class="news-title"><?php the_title(); ?></h4>
                                        </div>
                                        <div class="news-content">
                                            <?php if ($post_subtitle) : ?>
                                                <p class="news-excerpt"><?php echo esc_html($post_subtitle); ?></p>
                                            <?php endif; ?>
                                            <span class="news-date"><?php echo get_the_date('j F Y'); ?></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <div class="swiper-slide">
                            <p><?php _e('No news found.', 'puk'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
