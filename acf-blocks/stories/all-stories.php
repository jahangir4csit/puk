<?php
/**
 * ACF Block: All Stories
 *
 * Displays all stories from the stories post type
 * Shows: Title, Featured Image, and Permalink
 *
 * @package Puk
 */

// Create ID attribute for block wrapper
$block_id = isset($block_id) ? $block_id : 'all-stories-' . uniqid();
$block_class = isset($block_class) ? $block_class : '';

// Get ACF field values with defaults
$posts_per_page = get_field('posts_per_page') ?: -1;
$order = get_field('order') ?: 'DESC';
$orderby = get_field('orderby') ?: 'date';

// Query stories post type
$args = array(
    'post_type'      => 'stories',
    'posts_per_page' => intval($posts_per_page),
    'post_status'    => 'publish',
    'orderby'        => $orderby,
    'order'          => $order,
);

$stories_query = new WP_Query($args);
?>

<!-- stories section one start  -->
<section id="<?php echo esc_attr($block_id); ?>" class="stories_page stry_pg_1 <?php echo esc_attr($block_class); ?>">
    <div class="container-fluid">
        <div class="row">

            <div class="story-grid">
                <?php if ($stories_query->have_posts()) : ?>
                    <?php while ($stories_query->have_posts()) : $stories_query->the_post(); ?>
                        <?php
                        $story_permalink = get_permalink();
                        $story_title = get_the_title();
                        $story_image = get_the_post_thumbnail_url(get_the_ID(), 'full');

                        // Fallback image if no featured image is set
                        if (!$story_image) {
                            $story_image = get_template_directory_uri() . '/assets/images/default-story.jpg';
                        }
                        ?>

                        <a href="<?php echo esc_url($story_permalink); ?>"
                           class="story-card"
                           style="background-image:url('<?php echo esc_url($story_image); ?>');">
                            <span><?php echo esc_html($story_title); ?></span>
                        </a>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="no-stories-found"><?php _e('No stories found.', 'puk'); ?></p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
<!-- stories section one end  -->