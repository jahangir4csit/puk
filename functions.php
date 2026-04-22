<?php
add_filter('acf/format_value/type=text', 'do_shortcode');

include_once("inc/acf-theme-options.php");
include_once("inc/acf-blocks-builder.php");
include_once("inc/class-wp-bootstrap-navwalker.php");
include_once("inc/enqueue.php");
include_once("inc/hero-slider-enqueue.php");
include_once("inc/custom-post-taxonomy.php"); 
include_once("inc/ajax-actions.php"); 
include_once("inc/ajax-product-search.php");
include_once("inc/custom-functions.php"); 
include_once("inc/export-import/export-import-init.php");
include_once("inc/acf-filters.php");


// PDF Data Sheet Generator
include_once("inc/pdf/class-product-data-collector.php");
include_once("inc/pdf/class-pdf-image-handler.php");
include_once("inc/pdf/class-product-datasheet-pdf.php");
include_once("inc/pdf/ajax-generate-pdf.php");



add_theme_support( 'post-thumbnails' );

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Load WooCommerce functions if WooCommerce is activated.
if (class_exists('WooCommerce')) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if (class_exists('Jetpack')) {
	$understrap_includes[] = '/jetpack.php';
}


// Add custom column
add_filter( 'manage_edit-features_columns', 'add_features_icon_column' );
function add_features_icon_column( $columns ) {
    $columns['feature_icon'] = __( 'Icon', 'textdomain' );
    return $columns;
}

add_filter( 'manage_features_custom_column', 'render_features_icon_column', 10, 3 );
function render_features_icon_column( $content, $column_name, $term_id ) {
    if ( $column_name === 'feature_icon' ) {
        // ACF taxonomy field format: taxonomy_termID
        $image = get_field( 'tax_featured__icon', 'features_' . $term_id );
        if ( $image ) {
            $image_url = $image;
            return '<img src="' . esc_url( $image_url ) . '" style="width:40px;height:auto;" />';
        }
        return '—';
    }         
    return $content;
}

add_filter( 'manage_edit-features_sortable_columns', function( $columns ) {
    $columns['feature_icon'] = 'feature_icon';
    return $columns;
});

add_action( 'admin_head', function () {
    $screen = get_current_screen();

    // Target: Product → Features taxonomy page
    if ( $screen && $screen->taxonomy === 'features' ) {
        ?>
        <style>
            .feature_icon.column-feature_icon img {
                width: 50px !important;
                height: 50px !important;
                object-fit: contain;
            }
        </style>
        <?php
    }
});



function shm_add_journal_to_post_permalink( $permalink, $post ) {

    if ( $post->post_type === 'post' && $post->post_status === 'publish' ) {
        return home_url( '/journal/' . $post->post_name . '/' );
    }

    return $permalink;
}
add_filter( 'post_link', 'shm_add_journal_to_post_permalink', 10, 2 );


function shm_journal_rewrite_rules() {
    add_rewrite_rule(
        '^journal/([^/]+)/?$',
        'index.php?name=$matches[1]',
        'top'
    );
}
add_action( 'init', 'shm_journal_rewrite_rules' );


// add family UID in the column of product family 

function add_product_family_uid_column($columns) {
    $columns['family_uid'] = 'Family UID';
    return $columns;
}
add_filter('manage_edit-product-family_columns', 'add_product_family_uid_column');

function show_product_family_uid_column($content, $column_name, $term_id) {

    if ($column_name === 'family_uid') {
        $uid = get_field('tax_family__uid', 'product-family_' . $term_id);
        if ($uid) {
            $content = esc_html($uid);
        } else {
            $content = '-';
        }
    }
    return $content;
}
add_filter('manage_product-family_custom_column', 'show_product_family_uid_column', 10, 3);



// Add category hierarchy display on product-family edit page

add_action( 'product-family_edit_form_fields', 'show_category_hierarchy_admin', 10, 2 );
function show_category_hierarchy_admin( $term, $taxonomy ) {
    if ( ! $term && isset($_GET['tag_ID']) ) {
        $term_id = intval($_GET['tag_ID']);
        $term = get_term( $term_id, $taxonomy );
    }
    if ( $term && ! is_wp_error( $term ) ) {

        $parents = array();
        $current = $term;

        // Loop to get all parents
        while ( $current->parent != 0 ) {
            $parent = get_term( $current->parent, $taxonomy );
            if ( is_wp_error( $parent ) ) break;
            array_unshift( $parents, $parent->name ); // add at start
            $current = $parent;
        }

        // Add current term at end
        $parents[] = $term->name;

        // Display hierarchy
        $hierarchy = implode( ' > ', $parents );

        echo '<tr class="form-field term-hierarchy-wrap">';
        echo '<th scope="row"><label>Category Hierarchy</label></th>';
        echo '<td><strong>' . esc_html( $hierarchy ) . '</strong></td>';
        echo '</tr>';
    }
}
