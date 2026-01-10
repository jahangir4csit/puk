<?php
/**
 * Register Taxonomy: product-family (for product)
 */
function puk_register_product_family_taxonomy() {

    $labels = array(
        'name'              => _x( 'Product Family', 'puk', 'puk' ),
        'singular_name'     => _x( 'Product Family', 'taxonomy singular name', 'puk' ),
        'search_items'      => __( 'Search Product Family', 'puk' ),
        'all_items'         => __( 'All Product Family', 'puk' ),
        'parent_item'       => __( 'Parent Product Family', 'puk' ),
        'parent_item_colon' => __( 'Parent Product Family:', 'puk' ),
        'edit_item'         => __( 'Edit Product Family', 'puk' ),
        'update_item'       => __( 'Update Product Family', 'puk' ),
        'add_new_item'      => __( 'Add New Product Family', 'puk' ),
        'new_item_name'     => __( 'New Product Family Name', 'puk' ),
        'menu_name'         => __( 'Products Family', 'puk' ),
    );

    $args = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => 'product-family',
        'rewrite'           => false, // We'll handle rewrite completely manually
        'show_in_rest'      => true,
    );

    register_taxonomy( 'product-family', array( 'product' ), $args );
}
add_action( 'init', 'puk_register_product_family_taxonomy' );

/**
 * Add custom query vars for taxonomy handling
 */
function puk_add_taxonomy_query_vars($query_vars) {
    $query_vars[] = 'puk_taxonomy_term';
    $query_vars[] = 'puk_taxonomy_parent';
    return $query_vars;
}
add_filter('query_vars', 'puk_add_taxonomy_query_vars');

/**
 * WooCommerce-style rewrite rules - no taxonomy base
 */
function puk_woocommerce_style_rewrite_rules() {
    // Get all taxonomy terms to create specific rules
    $terms = get_terms(array(
        'taxonomy' => 'product-family',
        'hide_empty' => false,
    ));
    
    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            // Build hierarchical path - get all ancestors
            $path = '';
            $ancestors = get_ancestors($term->term_id, 'product-family');
            if (!empty($ancestors)) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor_id) {
                    $ancestor = get_term($ancestor_id, 'product-family');
                    if ($ancestor && !is_wp_error($ancestor)) {
                        $path .= $ancestor->slug . '/';
                    }
                }
            }
            $path .= $term->slug;
           
            // Add rewrite rule for this specific term
            add_rewrite_rule(
                '^' . $path . '/?$',
                'index.php?puk_taxonomy_term=' . $term->slug . '&post_type=product',
                'top'
            );
           
            // Add pagination rule
            add_rewrite_rule(
                '^' . $path . '/page/([0-9]+)/?$',
                'index.php?puk_taxonomy_term=' . $term->slug . '&post_type=product&paged=$matches[1]',
                'top'
            );
        }
    }
}
add_action('init', 'puk_woocommerce_style_rewrite_rules');

/**
 * Handle the taxonomy like WooCommerce
 */
function puk_handle_taxonomy_request($wp) {
    if (isset($wp->query_vars['puk_taxonomy_term']) && !empty($wp->query_vars['puk_taxonomy_term'])) {
        $term_slug = $wp->query_vars['puk_taxonomy_term'];
        
        // Verify it's a valid term
        $term = get_term_by('slug', $term_slug, 'product-family');
        if ($term && !is_wp_error($term)) {
            // Set proper WordPress query vars
            $wp->query_vars['product-family'] = $term_slug;
            $wp->query_vars['taxonomy'] = 'product-family';
            $wp->query_vars['term'] = $term_slug;
            $wp->query_vars['post_type'] = 'product';
        }
    }
}
add_action('parse_request', 'puk_handle_taxonomy_request');

/**
 * Filter term link to remove taxonomy base completely
 */
function puk_remove_taxonomy_base_completely($url, $term, $taxonomy) {
    if ($taxonomy === 'product-family') {
        // Remove any taxonomy base
        $url = str_replace('/product-family/', '/', $url);
        
        // Build hierarchical path - get all ancestors
        $path = '';
        $ancestors = get_ancestors($term->term_id, $taxonomy);
        if (!empty($ancestors)) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_term($ancestor_id, $taxonomy);
                if ($ancestor && !is_wp_error($ancestor)) {
                    $path .= $ancestor->slug . '/';
                }
            }
        }
        $path .= $term->slug;
        
        // Create clean URL
        $url = home_url('/' . $path . '/');
    }
    return $url;
}
add_filter('term_link', 'puk_remove_taxonomy_base_completely', 10, 3);

/**
 * Use specific template for product-family taxonomy
 */
function puk_taxonomy_template($template) {
    if (is_tax('product-family')) {
        // Look for taxonomy-product-family.php first, then fall back to archive-product.php
        $specific_template = locate_template(array('taxonomy-product-family.php', 'archive-product.php'));
        if ($specific_template) {
            return $specific_template;
        }
    }
    return $template;
}
add_filter('template_include', 'puk_taxonomy_template');

/**
 * Convert product-family taxonomy description field to WYSIWYG editor
 */

// Enqueue scripts for WYSIWYG editor in taxonomy pages
add_action('admin_enqueue_scripts', 'puk_enqueue_taxonomy_wysiwyg_scripts');
function puk_enqueue_taxonomy_wysiwyg_scripts($hook) {
    if ('edit-tags.php' !== $hook && 'term.php' !== $hook) {
        return;
    }
    
    $screen = get_current_screen();
    if ($screen && $screen->taxonomy === 'product-family') {
        wp_enqueue_editor();
        wp_enqueue_media();
    }
}

// Remove default description field on Add New Term page
add_action('product-family_add_form_fields', 'puk_products_family_add_wysiwyg_description', 10);
function puk_products_family_add_wysiwyg_description() {
    ?>
<div class="form-field term-description-wrap puk-wysiwyg-description">
    <label for="puk_description"><?php _e('Description'); ?></label>
    <?php
        $settings = array(
            'textarea_name' => 'description',
            'textarea_rows' => 10,
            'quicktags'     => true,
            'media_buttons' => true,
            'teeny'         => false,
            'editor_class'  => 'puk-description-editor'
        );
        wp_editor('', 'puk_description', $settings);
        ?>
    <p><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></p>
</div>
<?php
}

// Remove default description field and add WYSIWYG on Edit Term page
add_action('product-family_edit_form_fields', 'puk_products_family_edit_wysiwyg_description', 10);
function puk_products_family_edit_wysiwyg_description($term) {
    ?>
<tr class="form-field term-description-wrap puk-wysiwyg-description">
    <th scope="row"><label for="puk_description"><?php _e('Description'); ?></label></th>
    <td>
        <?php
            $settings = array(
                'textarea_name' => 'description',
                'textarea_rows' => 10,
                'quicktags'     => true,
                'media_buttons' => true,
                'teeny'         => false,
                'tinymce'       => true,
                'wpautop'       => true,
                'editor_class'  => 'puk-description-editor'
            );
            // Decode HTML entities for proper display
            $description = html_entity_decode($term->description, ENT_QUOTES, 'UTF-8');
            wp_editor($description, 'puk_description', $settings);
            ?>
        <p><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></p>
    </td>
</tr>
<?php
}

// Remove the default description textarea using JavaScript
add_action('admin_head-edit-tags.php', 'puk_remove_default_description_field');
add_action('admin_head-term.php', 'puk_remove_default_description_field');
function puk_remove_default_description_field() {
    $screen = get_current_screen();
    if ($screen && $screen->taxonomy === 'product-family') {
        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    // Remove only the default WordPress description textarea, not our WYSIWYG editor
    $('.form-field.term-description-wrap').not('.puk-wysiwyg-description').remove();
    $('tr.form-field.term-description-wrap').not('.puk-wysiwyg-description').remove();
});
</script>
<?php
    }
}

// Remove paragraph tags filter from term description to preserve WYSIWYG formatting
remove_filter('term_description', 'wpautop');