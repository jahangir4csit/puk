<?php

/**
 * The template part for displaying a message that posts cannot be found
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package redapple
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<section class="no-results not-found">

	<header class="page-header">

		<h1 class="page-title"><?php esc_html_e('Non abbiamo trovato nulla', 'redapple'); ?></h1>

	</header><!-- .page-header -->

	<div class="page-content">

		<?php
		if (is_home() && current_user_can('publish_posts')) :

			$kses = array('a' => array('href' => array()));
			printf(
				/* translators: 1: Link to WP admin new post page. */
				'<p>' . wp_kses(__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'redapple'), $kses) . '</p>',
				esc_url(admin_url('post-new.php'))
			);

		elseif (is_search()) :

			printf(
				'<p>%s<p>',
				esc_html__('Siamo spiacenti, ma niente corrisponde ai termini di ricerca. Riprova con alcune parole chiave diverse.', 'redapple')
			);
			get_search_form();

		else :

			printf(
				'<p>%s<p>',
				esc_html__('Sembra che non riusciamo a trovare quello che stai cercando. Forse la ricerca può aiutare.', 'redapple')
			);
			get_search_form();

		endif;
		?>
	</div><!-- .page-content -->

</section><!-- .no-results -->