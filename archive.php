<?php

/**
 * The template for displaying archive pages
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package redapple
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

$container = get_theme_mod('understrap_container_type');
?>


<div class="r_news_pagination">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="r_news_pagination_content">
					<a href="index.html">Home</a> / <span>News</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End redapple News Pagination  -->








<div class="r_news_main_content_area">
	<div class="container">

		<div class="row">
			<?php
			if (have_posts()) {
			?>
				<header class="page-header mb-5">
					<?php
					the_archive_title('<h1 class="page-title">', '</h1>');
					the_archive_description('<div class="taxonomy-description">', '</div>');
					?>
				</header><!-- .page-header -->
			<?php
				// Start the loop.
				while (have_posts()) {
					the_post();

					/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
					get_template_part('loop-templates/content', get_post_format());
				}
			}
			?>
			<?php
			// Display the pagination component.
			understrap_pagination();
			// Do the right sidebar check.
			?>

		</div>
	</div>
</div>

<?php
get_footer();
