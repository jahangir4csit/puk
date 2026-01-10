<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package redapple
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
get_header();
$container = get_theme_mod( 'understrap_container_type' ); ?>
<div class="wrapper" id="error-404-wrapper">
	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">
		<div class="row">
			<div class="col-md-12 content-area" id="primary">
				<main class="site-main" id="main">
					<section class="error-404 not-found">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'redapple' ); ?></h1>
						</header>
						<div class="page-content">
						   
						</div>
					</section>
				</main>
			</div>
		</div> 
	</div> 
</div> 
<?php
get_footer();
