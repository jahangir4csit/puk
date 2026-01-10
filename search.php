<?php

/**
 * The template for displaying search results pages
 *
 * @package redapple
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

$container = get_theme_mod('understrap_container_type');

?>

<div class="octgna_search_page">
	<div class="container">
		<div class="row">
			<?php if (have_posts()) : ?>

				<header class="page-header">

					<h1 class="page-title">
						<?php
						printf(
							/* translators: %s: query term */
							esc_html__('RISULTATI RICERCA: %s', 'redapple'),
							'<span>' . get_search_query() . '</span>'
						);
						?>
					</h1>

				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php
				while (have_posts()) :
					the_post();

					/*
	 * Run the loop for the search to output the results.
	 * If you want to overload this in a child theme then include a file
	 * called content-search.php and that will be used instead.
	 */
					get_template_part('loop-templates/content', 'search');
				endwhile;
				?>

			<?php else : ?>

				<?php get_template_part('loop-templates/content', 'none'); ?>

			<?php endif; ?>
		</div>
	</div>
</div>
<?php
get_footer();
