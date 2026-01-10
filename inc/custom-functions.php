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

