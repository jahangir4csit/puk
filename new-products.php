<?php /* Template Name: New Product */ ?>
<?php get_header(); ?>

<main>
    <div class="tax_archive_wrap">
    <section class="product-page-main">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="product_title">
                        <h1> New Products </h1>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="product_categories">
                        <ul>
                            <li><a href="/product/"> View all products </a></li>
                            <li><a href="/new-products/" class="is_new"> NEW </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            
            
<?php

$subcategories = get_terms(array(
    'taxonomy'   => 'product-family',
    'hide_empty' => false,
    'orderby'    => 'term_id',
    'order'      => 'ASC'
));

$subcategories = puk_filter_valid_terms($subcategories);

// Filter: keep only terms that are exactly depth level 1 (Sub category)
// i.e. they have a parent, but their parent has no parent (parent_id = 0)
$subcategories = array_filter($subcategories, function($term) {
    if (empty($term->parent)) {
        return false; // This is a top-level (Main category), skip it
    }
    $parent = get_term($term->parent, 'product-family');
    if (is_wp_error($parent) || empty($parent)) {
        return false;
    }
    return empty($parent->parent); // Only true if parent is a root term
});


?>

<?php if (!empty($subcategories)) : ?>
    <?php foreach ($subcategories as $subcat) : ?>
    
     <?php
     
        $child_terms = get_terms(array(
            'taxonomy'        => 'product-family',
            'parent'          => $subcat->term_id,
            'hide_empty'      => false,
            'orderby'         => 'term_id',
            'order'           => 'ASC',
           
        ));
        
        // Filter: keep only child terms that have the 'new' feature code
        $filtered_child_terms = array();
        foreach ($child_terms as $child) :
            
            $tax_sub_family_features = get_field('tax_sub_family_features', $child);
            if (!empty($tax_sub_family_features)) :
                foreach ($tax_sub_family_features as $feature_id) :
                    $feature = get_term($feature_id, 'features'); // <-- replace with your actual taxonomy name
                    if ($feature && !is_wp_error($feature)) :
                        $code = get_field('tax_featured__code', 'features' . '_' . $feature->term_id); // <-- replace with your actual taxonomy name
                        if ($code === 'new') :
                            $filtered_child_terms[] = $child;
                            break; // No need to check other features once 'new' is found
                        endif;
                    endif;
                endforeach;
            endif;
        endforeach;
        
         $child_terms = puk_filter_valid_terms($filtered_child_terms, false, false);
        
       ?>
        <?php if (!empty($child_terms)) : ?>

        <div class="row product_rows">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="product_row_title">
                    <h4> <?php echo esc_html($subcat->name); ?>  </h4>
                </div>
            </div>
        </div>

        <div class="row product_row product_row_grid">

                <?php foreach ($child_terms as $child) : ?>   
                    <?php
                    $default_img_url = get_field('site_plachlder_img', 'option'); 
                    $image           = get_field('pf_fet_img', 'product-family_' . $child->term_id);
                    $img_url         = $image ?: $default_img_url;
                    $hover_img       = get_field('pf_hover_img', 'product-family_' . $child->term_id);
                    $hover_img_url   = $hover_img ? $hover_img : $default_img_url;
                    
                    $name_parts = array();
                    if ($subcat->name !== '_') {
                        $name_parts[] = $subcat->name;
                    }
                    if ($child->name !== '_') {
                        $name_parts[] = $child->name;
                    }
                    $display_name    = implode(' ', $name_parts);
                    $empty_img_class = !$image ? ' empty_img' : '';
                    ?>

                    <div class="product_col">
                        <div class="product_row_item">

                            <?php if ($hover_img_url) : ?>
                                <a class="product-image-wrap<?php echo $empty_img_class; ?>"
                                   href="<?php echo esc_url(get_term_link($child)); ?>">
                                    <img class="img-default" src="<?php echo esc_url($img_url); ?>"
                                         alt="<?php echo esc_attr($display_name); ?>">
                                    <img class="img-hover"   src="<?php echo esc_url($hover_img_url); ?>"
                                         alt="<?php echo esc_attr($display_name); ?>">
                                </a>
                            <?php else : ?>
                                <a class="product-image-wrap<?php echo $empty_img_class; ?>"
                                   href="<?php echo esc_url(get_term_link($child)); ?>">
                                    <img src="<?php echo esc_url($img_url); ?>"
                                         alt="<?php echo esc_attr($display_name); ?>">
                                </a>
                            <?php endif; ?>

                            <div class="r_pft_part">
                                <a href="<?php echo esc_url(get_term_link($child)); ?>" class="product_item_title">
                                    <?php echo esc_html($display_name); ?>
                                </a>
                                <?php
                                    $tax_sub_family_features = get_field('tax_sub_family_features', $child);
                                    $code = get_field('tax_featured__code', $taxonomy . '_' . $feature->term_id);
                                    echo get_feature_badge($tax_sub_family_features);
                                ?>
                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            </div><!-- END .product_row_grid -->

        
        <?php endif; ?>

    <?php endforeach; ?>
<?php endif; ?>

</div>
         
        
</section>
</div>
</main>

<?php

get_footer();
