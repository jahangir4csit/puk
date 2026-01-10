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


/* Remove taxonomy slug from term links for taxonomy: products-family */
add_filter('term_link', 'puk_remove_tax_slug', 10, 3);
function puk_remove_tax_slug($url, $term, $taxonomy) {

    if ($taxonomy !== 'products-family') {
        return $url;
    }

    $slug = $term->slug;
    $taxonomy = $term->taxonomy;

    // Build nested parent URL
    $parents = [];
    $parent_id = $term->parent;

    while ($parent_id) {
        $parent = get_term($parent_id, $taxonomy);
        if (is_wp_error($parent)) break;
        $parents[] = $parent->slug;
        $parent_id = $parent->parent;
    }

    $parents = array_reverse($parents);

    // Final URL without taxonomy base
    $structure = home_url('/' . implode('/', $parents) . '/' . $slug . '/');
    $structure = str_replace('//', '/', $structure);
    $structure = str_replace(':/', '://', $structure);

    return $structure;
}

/* Add rewrite rules to match new URLs */
add_action('init', 'puk_add_rewrite_rules');
function puk_add_rewrite_rules() {

    $taxonomy = 'products-family';

    // 3-level
    add_rewrite_rule(
        '([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?' . $taxonomy . '=$matches[3]',
        'top'
    );

    // 2-level
    add_rewrite_rule(
        '([^/]+)/([^/]+)/?$',
        'index.php?' . $taxonomy . '=$matches[2]',
        'top'
    );

    // 1-level 
    add_rewrite_rule(
        '([^/]+)/?$',
        'index.php?' . $taxonomy . '=$matches[1]',
        'top'
    );
}



// 1) Add a rewrite tag and permastruct for products
add_action( 'init', 'puk_products_permastruct', 20 );
function puk_products_permastruct() {
    // Rewrite tag that will hold the taxonomy path (may contain slashes)
    add_rewrite_tag( '%products_family%', '([^/]+(?:/[^/]+)*)' );
    // Note: the permastruct key for post types is the post type slug
    add_permastruct( 'products', '%products_family%/%products%', array(
        'with_front' => false,
        'ep_mask'    => EP_PERMALINK,
    ) );
}

// 2) Replace the placeholder with the actual term path when building permalinks
add_filter( 'post_type_link', 'puk_products_post_type_link', 10, 4 );
function puk_products_post_type_link( $post_link, $post, $leavename, $sample ) {
    if ( $post->post_type !== 'products' ) {
        return $post_link;
    }

    // Get assigned terms for this product (use primary/first term)
    $terms = wp_get_post_terms( $post->ID, 'products-family' );
     if ( is_wp_error( $terms ) || empty( $terms ) ) {
        $term_path = 'products';
     } else {
        $term = $terms[0];
        // Gather slugs from this term up to top
        $slugs = array();
        $parent_id = $term->term_id;
        while ( $parent_id ) {
            $parent = get_term( $parent_id, 'products-family' );
            if ( is_wp_error( $parent ) || ! $parent ) break;
            $slugs[] = $parent->slug;
            $parent_id = $parent->parent;
        }
        $slugs = array_reverse( $slugs );
        $term_path = implode( '/', $slugs );
    }

    // Replace placeholder
    $post_link = str_replace( '%products_family%', $term_path, $post_link );
    return $post_link;
}

// 3) Add a flexible rewrite rule that treats the last segment as the product slug  and the preceding segments as the taxonomy path.
add_action( 'init', 'puk_products_rewrite_rules', 30 );
function puk_products_rewrite_rules() { 
    add_rewrite_rule(
        '^(.+?)/([^/]+)/?$',
        'index.php?post_type=products&name=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        '^([^/]+)/?$',
        'index.php?post_type=products&name=$matches[1]',
        'top'
    );
}

// // for post type custom link structure  

