<?php 

// header class 
function header_class_setup($class)
{
   if (is_front_page()) {
	} else {
		$class = '';
	}
	return $class;
}
add_filter('class_change_as_page', 'header_class_setup');
function register_main_menu() {
    register_nav_menu('main_menu', __('Main Menu'));
}
add_action('after_setup_theme', 'register_main_menu');
function register_footer_menu() {
    register_nav_menu('footer_menu', __('Footer Menu'));
}
add_action('after_setup_theme', 'register_footer_menu');
function register_bs_menu() {
    register_nav_menu('bs_menu', __('Blog Sidebar Menu'));
}
add_action('after_setup_theme', 'register_bs_menu');



// Allow SVG upload
function allow_svg_upload( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'allow_svg_upload' );

// Fix SVG preview in media library
function fix_svg_display() {
    echo '<style>
        .attachment-266x266, .thumbnail img {
            width: 100% !important;
            height: auto !important;
        }
        img[src$=".svg"] {
            width: 100% !important;
            height: auto !important;
        }
    </style>';
}
add_action('admin_head', 'fix_svg_display');



/**
 * Check if ACF repeater field is empty or not
 *
 * @param string $field_name The name of the ACF repeater field
 * @param int $post_id Optional. The post ID to check against. Defaults to current post.
 * @return bool True if field has values, false if empty
 */
function is_array_empty($field_name, $post_id = null) {
    // If no post ID provided, use current post
    if ($post_id === null) {
        $post_id = get_the_ID();
    }
    
    // Check if the repeater field exists and has rows
    if (have_rows($field_name, $post_id)) {
        return false; // Not empty - has rows
    }
    return true; // Empty - no rows
}

/*
USAGE EXAMPLES:

1. Basic usage with current post:
   if (!is_array_empty('team_members')) {
       echo '<div class="team-section">';
       while (have_rows('team_members')) {
           the_row();
       }
       echo '</div>';
   }

2. Usage with specific post ID:
   if (!is_array_empty('gallery_images', 123)) {
       // Display gallery for post ID 123
       while (have_rows('gallery_images', 123)) {
           the_row();
       }
   }

3. Conditional display with fallback:
   if (!is_array_empty('testimonials')) {
       echo '<section class="testimonials">';
       while (have_rows('testimonials')) {
           the_row();
       }
       echo '</section>';
   } 

4. Multiple repeater checks:
   $has_services = !is_array_empty('services');
   $has_portfolio = !is_array_empty('portfolio_items');
   
   if ($has_services || $has_portfolio) {
       echo '<div class="content-wrapper">';
   }
*/


/**
 * Filter product-family terms to remove invalid entries
 *
 * Removes terms that:
 * - Have name equal to '_' (placeholder terms)
 * - Have zero post count (empty terms) - optional
 *
 * @param array $terms         Array of WP_Term objects
 * @param bool  $skip_placeholder Whether to skip terms named '_'. Default true.
 * @param bool  $skip_empty       Whether to skip terms with count <= 0. Default false.
 * @return array Filtered array of valid terms
 */
function puk_filter_valid_terms( $terms, $skip_placeholder = true, $skip_empty = false ) {
    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return array();
    }

    $valid_terms = array();
    foreach ( $terms as $term ) {
        // Skip placeholder terms named '_'
        if ( $skip_placeholder && $term->name === '_' ) {
            continue;
        }
        // Skip empty terms if enabled
        if ( $skip_empty && $term->count <= 0 ) {
            continue;
        }
        $valid_terms[] = $term;
    }

    return $valid_terms;
}


/**
 * Get all finish-color taxonomy terms that are used by at least one product.
 *
 * Returns an associative array keyed by term_id => term name, ordered by name.
 * Result is cached per request via a static variable.
 *
 * @return array [ term_id => name ]
 */
/**
 * Append scalar or array ACF field value(s) into a flat collection array.
 * Eliminates the repeated if/is_array/foreach pattern across accordion templates.
 *
 * @param mixed $value   Scalar or array field value.
 * @param array &$target The array to append into (passed by reference).
 */
function puk_collect_field_values( $value, array &$target ) {
    if ( empty( $value ) ) {
        return;
    }
    if ( is_array( $value ) ) {
        foreach ( $value as $v ) {
            $target[] = $v;
        }
    } else {
        $target[] = $value;
    }
}


/**
 * Render a single filter accordion group (title + checkboxes).
 * Handles both flat value arrays and keyed [ id => name ] arrays.
 *
 * @param string $type       data-filter-type value (e.g. 'watt').
 * @param int    $key_number Accordion term ID — scopes checkbox IDs to prevent duplicates.
 * @param string $label      Display title for the filter group.
 * @param array  $items      Flat array of values, or [ id => name ] for keyed items.
 * @param bool   $keyed      True when $items is [ id => name ] (e.g. Finish, Dimming).
 */
function puk_render_filter_group( $type, $key_number, $label, array $items, $keyed = false ) {
    if ( empty( $items ) ) {
        return;
    }
    echo '<div class="single-filter-accordion" data-filter-type="' . esc_attr( $type ) . '">';
    echo '<div class="filter-acc-title">' . esc_html( $label ) . '</div>';
    echo '<div class="filter-acc-content">';
    foreach ( $items as $key => $val ) {
        $value   = $keyed ? $key : $val;
        $display = $val; // both keyed and flat: $val is always the display label
        $id      = esc_attr( $type . '-' . $key_number . '-' . $value );
        echo '<div class="form-check">';
        echo '<label class="form-check-label" for="' . $id . '">' . esc_html( $display ) . '</label>';
        echo '<input class="form-check-input filter_input" type="checkbox" value="' . esc_attr( $value ) . '" id="' . $id . '">';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}


function puk_get_all_finish_colors() {
    static $cache = null;
    if ( $cache !== null ) {
        return $cache;
    }

    $terms = get_terms( array(
        'taxonomy'   => 'finish-color',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'object_type' => array( 'product' ),
    ) );

    $cache = array();
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $cache[ $term->term_id ] = $term->name;
        }
    }

    return $cache;
}


/**
 * Get the hierarchy level of a product-family term
 *
 * Product Family Hierarchy:
 * - Level 1 (Main Category): e.g., "Floodlights" - parent = 0
 * - Level 2 (Family): e.g., "QUBO" - has family_code ACF field
 * - Level 3 (Sub-Family): e.g., "COB" - child of Family
 *
 * @param WP_Term|int $term The term object or term ID
 * @param string $taxonomy Optional. The taxonomy name. Default 'product-family'.
 * @return int The level (1, 2, 3, etc.) or 0 if invalid
 */
function get_product_family_level( $term, $taxonomy = 'product-family' ) {
    if ( is_numeric( $term ) ) {
        $term = get_term( $term, $taxonomy );
    }

    if ( ! $term || is_wp_error( $term ) ) {
        return 0;
    }

    $level = 1;
    $current = $term;

    while ( $current->parent > 0 ) {
        $level++;
        $current = get_term( $current->parent, $taxonomy );
        if ( ! $current || is_wp_error( $current ) ) {
            break;
        }
    }

    return $level;
}

/**
 * Get Family Code from product-family taxonomy term at a specific level
 *
 * Product Family Hierarchy:
 * - Level 1 (Main Category): e.g., "Floodlights"
 * - Level 2 (Family): e.g., "QUBO" - typically has family_code ACF field
 * - Level 3 (Sub-Family): e.g., "COB"
 *
 * @param WP_Term|int $term The term object or term ID
 * @param int $target_level The level to get family_code from. Default 2 (Family level).
 * @param string $taxonomy Optional. The taxonomy name. Default 'product-family'.
 * @return string|false The family_code value or false if not found
 */
function get_product_family_code( $term, $target_level = 2, $taxonomy = 'product-family' ) {
    // If term_id is passed, get the term object
    if ( is_numeric( $term ) ) {
        $term = get_term( $term, $taxonomy );
    }

    // Validate term
    if ( ! $term || is_wp_error( $term ) ) {
        return false;
    }

    // Get current term's level
    $current_level = get_product_family_level( $term, $taxonomy );

    // If current level is less than target, we can't go deeper
    if ( $current_level < $target_level ) {
        return false;
    }

    // Navigate up to the target level
    $target_term = $term;
    while ( $current_level > $target_level && $target_term->parent > 0 ) {
        $target_term = get_term( $target_term->parent, $taxonomy );
        if ( ! $target_term || is_wp_error( $target_term ) ) {
            return false;
        }
        $current_level--;
    }

    // Get family_code ACF field from the target level term
    // ACF stores taxonomy fields with prefix: taxonomy_termid
    $family_code = get_field( 'family_code', $taxonomy . '_' . $target_term->term_id );

    return $family_code ? $family_code : false;
}



/**
 * Get product code prefix from the first product in a taxonomy term.
 *
 * Fetches the first product assigned to the given term, reads the ACF field
 * `prod__sku` (e.g. "101605.RW.25.01.X"), and returns the part before the
 * first dot (e.g. "101605").
 *
 * @param WP_Term|int $term      The term object or term ID.
 * @param string      $taxonomy  Optional. Taxonomy name. Default 'product-family'.
 * @return string|false The numeric prefix or false if not found.
 */
function tax_product_code( $term, $taxonomy = 'product-family' ) {
    if ( is_numeric( $term ) ) {
        $term = get_term( $term, $taxonomy );
    }

    if ( ! $term || is_wp_error( $term ) ) {
        return false;
    }

    $query = new WP_Query( array(
        'post_type'      => 'product',
        'posts_per_page' => 1,
        'tax_query'      => array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ),
        ),
        'orderby' => 'menu_order',
        'order'   => 'ASC',
        'fields'  => 'ids',
    ) );

    if ( empty( $query->posts ) ) {
        return false;
    }

    $sku = get_field( 'prod__sku', $query->posts[0] );

    if ( empty( $sku ) ) {
        return false;
    }

    $parts = explode( '.', $sku );

    return $parts[0];
}


/**
 * Get the product code prefix from the current single product's SKU.
 *
 * Reads the ACF field `prod__sku` from the given (or global) product post
 * and returns the part before the first dot (e.g. "101605" from "101605.RW.25.01.X").
 *
 * @param int|null $post_id Optional. Post ID. Defaults to current global post.
 * @return string|false The numeric prefix or false if not found.
 */
function single_product_code( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    if ( ! $post_id ) {
        return false;
    }

    $sku = get_field( 'prod__sku', $post_id );

    if ( empty( $sku ) ) {
        return false;
    }

    $parts = explode( '.', $sku );

    return $parts[0];
}


// get new feature badge in subfamily page 
function get_feature_badge($feature_ids, $taxonomy = 'features') {
    if (empty($feature_ids) || !is_array($feature_ids)) {
        return '';
    }
    foreach ($feature_ids as $feature_id) {
        $feature = get_term($feature_id, $taxonomy);

        if (!is_wp_error($feature)) {
            $code = get_field('tax_featured__code', $taxonomy . '_' . $feature->term_id);

            if ($code === 'new') {
                return '<span class="badge-new">New</span>';
            }
        }
    }
    return '';
}

function remove_duplicate_words($string) {
    $words  = explode(' ', $string);         // split into words
    $unique = array_unique($words);          // remove duplicate words
    return implode(' ', $unique);            // join back
}

