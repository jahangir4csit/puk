<?php
/**
 * Template Name: Product Page
 * 
 * Template for displaying all product categories
 *
 * @package puk
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

<main>
    <div class="tax_archive_wrap">
        <section class="product-page-main">
            <div class="container">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="product_title">
                            <h1><?php _e('Products', 'puk'); ?></h1>
                        </div>
                    </div>
                </div>

                <?php
                // Get product family terms
                $terms = get_terms(array(
                    'taxonomy'   => 'product-family',
                    'parent'     => 0,
                    'hide_empty' => false,
                    'orderby'    => 'term_id',
                    'order'      => 'ASC',
                ));

                // Check if we have terms and no errors
                $has_terms = !empty($terms) && !is_wp_error($terms);
                ?>

                <!-- Category Navigation -->
                <?php if ($has_terms) : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="product_categories">
                            <ul>
                                <?php
                                    // Get current page URL (product-page.php)
                                    $current_page = get_pages(array(
                                        'meta_key'   => '_wp_page_template',
                                        'meta_value' => 'product-page.php',
                                        'number'     => 1
                                    ));
                                    $current_page_url = !empty($current_page) ? get_permalink($current_page[0]->ID) : '#';
                                    ?>

                                <!-- View All Link -->
                                <li>
                                    <a href="<?php echo esc_url($current_page_url); ?>" class="active">
                                        <?php _e('View All', 'puk'); ?>
                                    </a>
                                </li>

                                <!-- Category Links -->
                                <?php foreach ($terms as $term) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>

                                <?php
                                    // Get "New Products" page if exists
                                    $new_page = get_pages(array(
                                        'meta_key'   => '_wp_page_template',
                                        'meta_value' => 'new-products.php',
                                        'number'     => 1
                                    ));
                                    $new_page_url = !empty($new_page) ? get_permalink($new_page[0]->ID) : '';
                                    ?>

                                <!-- New Products Link -->
                                <?php if ($new_page_url) : ?>
                                <li>
                                    <a href="<?php echo esc_url($new_page_url); ?>" class="is_new">
                                        <?php _e('New', 'puk'); ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Product Grid -->
                <?php if ($has_terms) : ?>
                <div class="product-grid-parent">
                    <?php foreach ($terms as $term) : ?>
                    <?php
                        // Get ACF image field from taxonomy
                        $image = get_field('pf_fet_img', 'product-family_' . $term->term_id);
                        $default_image = get_field('site_plachlder_img','option') ;
                        $img_url = $image ?: $default_image;

                        // Get hover image
                        $hover_img = get_field('pf_hover_img', 'product-family_' . $term->term_id);
                        $hover_img_url = $hover_img ?: $default_image;

                        $empty_img_class = !$image ? ' empty_img' : '';
                    ?>

                    <div class="single-grid" data-name="<?php echo esc_attr($term->name); ?>">
                        <div class="image-box h-full">
                            <a href="<?php echo esc_url(get_term_link($term)); ?>" class="product-image-wrap<?php echo $empty_img_class; ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('View %s products', 'puk'), $term->name)); ?>">
                                <img class="img-default" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($term->name); ?>" loading="lazy" />
                                <img class="img-hover" src="<?php echo esc_url($hover_img_url); ?>" alt="<?php echo esc_attr($term->name); ?>" loading="lazy" />
                            </a>
                        </div>
                        <h3>
                            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        </h3>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else : ?>
                <!-- Empty State Message -->
                <div class="product-empty-state">
                    <div class="empty-state-content">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 7h-9"></path>
                            <path d="M14 17H5"></path>
                            <circle cx="17" cy="17" r="3"></circle>
                            <circle cx="7" cy="7" r="3"></circle>
                        </svg>
                        <h2><?php _e('No Product Categories Found', 'puk'); ?></h2>
                        <p><?php _e('There are currently no product categories available. Please check back later.', 'puk'); ?>
                        </p>
                        <?php if (current_user_can('manage_options')) : ?>
                        <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=product-family&post_type=product')); ?>"
                            class="btn btn-primary">
                            <?php _e('Add Product Categories', 'puk'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </section>
    </div>
</main>

<?php
get_footer();