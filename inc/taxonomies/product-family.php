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



/**
 * Fix slug containing underscore - replace "_" with Family UID after term creation
 * This handles new term creation
 * Priority 20 to run AFTER UID generation (which runs at priority 10)
 */
add_action('created_product-family', 'puk_fix_underscore_slug_on_create', 20, 2);
function puk_fix_underscore_slug_on_create($term_id, $tt_id) {
    $term = get_term($term_id, 'product-family');
    if (!$term || is_wp_error($term)) {
        return;
    }

    // Check if slug contains underscore
    if (strpos($term->slug, '_') !== false) {
        // Get the Family UID from ACF field
        $family_uid = get_field('tax_family__uid', 'product-family_' . $term_id);

        // If no UID exists yet, fall back to term ID
        if (empty($family_uid)) {
            $family_uid = $term_id;
        }

        // Make UID lowercase for URL-friendly slug
        $family_uid = strtolower($family_uid);

        // Replace all underscores with the Family UID
        $new_slug = str_replace('_', $family_uid, $term->slug);

        // Update the term slug
        wp_update_term($term_id, 'product-family', array('slug' => $new_slug));

        // Flush rewrite rules to update permalinks
        flush_rewrite_rules();
    }
}

/**
 * Admin page for bulk fixing underscore slugs
 */
add_action('admin_menu', 'puk_add_slug_fix_admin_page');
function puk_add_slug_fix_admin_page() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Fix Underscore Slugs',
        'Fix Underscore Slugs',
        'manage_options',
        'puk-fix-underscore-slugs',
        'puk_fix_underscore_slugs_page'
    );
}

/**
 * Admin page callback for bulk slug fixing
 */
function puk_fix_underscore_slugs_page() {
    // Handle form submission
    if (isset($_POST['puk_fix_slugs']) && check_admin_referer('puk_fix_slugs_nonce')) {
        $results = puk_bulk_fix_underscore_slugs();
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Slug Fix Complete!</strong></p>
            <p>Fixed <?php echo count($results['fixed']); ?> term(s).</p>
            <?php if (!empty($results['fixed'])): ?>
                <ul>
                    <?php foreach ($results['fixed'] as $fix): ?>
                        <li>"<?php echo esc_html($fix['name']); ?>": <code><?php echo esc_html($fix['old_slug']); ?></code> → <code><?php echo esc_html($fix['new_slug']); ?></code></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }

    // Get terms with underscore in slug
    $terms_with_underscore = puk_get_terms_with_underscore_slug();

    ?>
    <div class="wrap">
        <h1>Fix Underscore Slugs in Product Family</h1>
        <p>This tool finds Product Family terms with underscores (_) in their slugs and replaces them with the Family UID for better SEO.</p>

        <h2>Terms with Underscore in Slug</h2>
        <?php if (empty($terms_with_underscore)): ?>
            <p style="color: green;"><strong>✓ No terms found with underscore in slug. All good!</strong></p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Term ID</th>
                        <th>Family UID</th>
                        <th>Name</th>
                        <th>Current Slug</th>
                        <th>New Slug (Preview)</th>
                        <th>Current URL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($terms_with_underscore as $term):
                        $family_uid = get_field('tax_family__uid', 'product-family_' . $term->term_id);
                        $uid_for_slug = !empty($family_uid) ? strtolower($family_uid) : $term->term_id;
                        $new_slug = str_replace('_', $uid_for_slug, $term->slug);
                    ?>
                        <tr>
                            <td><?php echo esc_html($term->term_id); ?></td>
                            <td><code><?php echo esc_html($family_uid ?: 'N/A'); ?></code></td>
                            <td><?php echo esc_html($term->name); ?></td>
                            <td><code><?php echo esc_html($term->slug); ?></code></td>
                            <td><code><?php echo esc_html($new_slug); ?></code></td>
                            <td><a href="<?php echo esc_url(get_term_link($term)); ?>" target="_blank"><?php echo esc_url(get_term_link($term)); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('puk_fix_slugs_nonce'); ?>
                <p>
                    <input type="submit" name="puk_fix_slugs" class="button button-primary" value="Fix All Underscore Slugs" onclick="return confirm('Are you sure you want to fix <?php echo count($terms_with_underscore); ?> slug(s)? This will change URLs.');">
                </p>
            </form>

            <div class="notice notice-warning inline" style="margin-top: 15px;">
                <p><strong>Important:</strong> After fixing slugs, you may need to set up 301 redirects from old URLs to new URLs for SEO purposes.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Get all product-family terms with underscore in slug
 */
function puk_get_terms_with_underscore_slug() {
    $all_terms = get_terms(array(
        'taxonomy' => 'product-family',
        'hide_empty' => false,
    ));

    if (is_wp_error($all_terms)) {
        return array();
    }

    $terms_with_underscore = array();
    foreach ($all_terms as $term) {
        if (strpos($term->slug, '_') !== false) {
            $terms_with_underscore[] = $term;
        }
    }

    return $terms_with_underscore;
}

/**
 * Bulk fix all terms with underscore in slug
 * Replaces "_" with Family UID (or term ID as fallback)
 */
function puk_bulk_fix_underscore_slugs() {
    $terms = puk_get_terms_with_underscore_slug();
    $results = array('fixed' => array(), 'errors' => array());

    foreach ($terms as $term) {
        $old_slug = $term->slug;

        // Get the Family UID from ACF field
        $family_uid = get_field('tax_family__uid', 'product-family_' . $term->term_id);

        // If no UID exists, fall back to term ID
        if (empty($family_uid)) {
            $uid_for_slug = $term->term_id;
        } else {
            // Make UID lowercase for URL-friendly slug
            $uid_for_slug = strtolower($family_uid);
        }

        $new_slug = str_replace('_', $uid_for_slug, $old_slug);

        $result = wp_update_term($term->term_id, 'product-family', array('slug' => $new_slug));

        if (is_wp_error($result)) {
            $results['errors'][] = array(
                'name' => $term->name,
                'error' => $result->get_error_message()
            );
        } else {
            $results['fixed'][] = array(
                'name' => $term->name,
                'old_slug' => $old_slug,
                'new_slug' => $new_slug
            );
        }
    }

    // Flush rewrite rules after all fixes
    if (!empty($results['fixed'])) {
        flush_rewrite_rules();
    }

    return $results;
}

