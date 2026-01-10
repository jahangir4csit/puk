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

<main class="product-page-main">
    <div class="container-fluid">

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
                    $default_image = get_template_directory_uri() . '/assets/images/product/default.png';
                    $img_url = $image ? esc_url($image) : $default_image;
                    ?>

            <div class="single-grid" data-name="<?php echo esc_attr($term->name); ?>">
                <div class="image-box">
                    <a href="<?php echo esc_url(get_term_link($term)); ?>"
                        aria-label="<?php echo esc_attr(sprintf(__('View %s products', 'puk'), $term->name)); ?>">
                        <img src="<?php echo $img_url; ?>" alt="<?php echo esc_attr($term->name); ?>" loading="lazy" />
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

</main>

<style>
/* Empty State Styles */
.product-empty-state {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
    padding: 60px 20px;
}

.empty-state-content {
    text-align: center;
    max-width: 500px;
}

.empty-state-content svg {
    color: #ccc;
    margin-bottom: 20px;
}

.empty-state-content h2 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.empty-state-content p {
    font-size: 1rem;
    color: #666;
    margin-bottom: 25px;
    line-height: 1.6;
}

.empty-state-content .btn {
    display: inline-block;
    padding: 12px 30px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.empty-state-content .btn:hover {
    background-color: #0056b3;
}
</style>

<?php
get_footer();