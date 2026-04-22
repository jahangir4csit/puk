<?php
/**
 * Single Product Template
 *
 * Template parts used:
 * - product-breadcrumb.php (sets: $product_id, $current_term, $term_id, $taxonomy, $ancestors, $product_sku, $family_code)
 * - product-title.php (sets: $parent_term_id, $designed_by, $subfamily_desc, $site_plachlder_img, $parent_term, $main_term, $subsub_family_gallary)
 * - product-info.php
 * - product-accessories.php
 * - light-distribution.php
 * - product-download.php
 * - contact-info.php
 * - related-products.php
 */

get_header();
?>

<main>
    <div class="tax_archive_wrap">
        <?php
        // Breadcrumb
        include get_template_directory() . '/template-parts/product/product-breadcrumb.php';

        // Product Title & Gallery
        include get_template_directory() . '/template-parts/product/product-title.php';

        // Product Info/Specifications
        include get_template_directory() . '/template-parts/product/product-info.php';

        // Product Accessories (conditional)
        $pro_inte_access_rept = get_field('prod_acc_in__terms');
        if (!empty($pro_inte_access_rept)) {
            include get_template_directory() . '/template-parts/product/product-accessories.php';
        }

        // Light Distribution
        include get_template_directory() . '/template-parts/product/light-distribution.php';

        // Downloads
        include get_template_directory() . '/template-parts/product/product-download.php';

        // Contact Info
        include get_template_directory() . '/template-parts/product/contact-info.php';
        
        // Product Variants
        include get_template_directory() . '/template-parts/product/product-variants.php';

        // Related Products
        include get_template_directory() . '/template-parts/product/related-products.php';
        
        
        
        
        ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var currentUrlField = document.getElementById('current_page_url');
    if (currentUrlField) {
        currentUrlField.value = window.location.href;
    }
});
</script>

<?php get_footer(); ?>