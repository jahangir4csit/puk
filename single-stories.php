<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package redapple
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get background color from ACF field
$page_bg_color = get_field( 'page_bg_color' );

get_header(); ?>

<div class="story_wrap"<?php echo $page_bg_color ? ' style="background-color: ' . esc_attr( $page_bg_color ) . ';"' : ''; ?>>
    <?php the_content(); ?>
</div>

<?php
get_footer();