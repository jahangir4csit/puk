<?php
/**
 * ACF Block: Home Stories
 *
 * Latest Stories section displaying stories from stories post type
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create ID attribute for block wrapper
$block_id = isset($block_id) ? $block_id : 'home-stories-' . uniqid();
$block_class = isset($block_class) ? $block_class : '';

// Get ACF fields
$section_label = get_field('section_label') ?: __('Latest Stories -', 'puk');
$posts_per_page = get_field('posts_per_page') ?: 4;

// Query stories post type
$args = array(
    'post_type'      => 'stories',
    'posts_per_page' => intval($posts_per_page),
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$stories_query = new WP_Query($args);

// Block preview placeholder in admin
if ( isset($is_preview) && $is_preview && !$stories_query->have_posts() ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Home Stories Block - Add some stories first', 'puk' ) . '</p>';
    echo '</div>';
    wp_reset_postdata();
    return;
}
?>

<!-- Latest Stories Section -->
<section id="<?php echo esc_attr($block_id); ?>" class="latest-stories-section <?php echo esc_attr($block_class); ?>">
    <div class="container">
        <h3 class="section-label gs-reveal"><?php echo esc_html($section_label); ?></h3>
        <div class="hstories-grid gs-stagger-container">
            <?php if ($stories_query->have_posts()) : ?>
                <?php
                $counter = 0;
                while ($stories_query->have_posts()) : $stories_query->the_post();
                    $counter++;
                    $is_even = ($counter % 2 == 0) ? 'even' : '';
                    $story_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    $story_subtitle = get_field('field_story_subtitle', get_the_ID());

                    // Fallback image if no featured image
                    if (!$story_image) {
                        $story_image = get_template_directory_uri() . '/assets/images/default-story.jpg';
                    }
                ?>
                    <div class="hstory-card <?php echo esc_attr($is_even); ?> gs-stagger-item">
                        <a href="<?php the_permalink(); ?>" class="story-link">
                            <div class="story-image gs-scale-image-wrap">
                                <img src="<?php echo esc_url($story_image); ?>"
                                    alt="<?php echo esc_attr(get_the_title()); ?>"
                                    class="gs-scale-image">
                            </div>
                            <div class="story-content">
                                <h3 class="story-tag"><?php the_title(); ?></h3>
                                <?php if ($story_subtitle) : ?>
                                <h4 class="story-title"><?php echo $story_subtitle ; ?></h4>
                                  <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php _e('No stories found.', 'puk'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
