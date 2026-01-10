<?php

/**
 * Search results partial template
 *
 * @package redapple
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<div class="col-12">
			<div class="r_news_feature_post_section">
				<div class="r_news_feature_post_left" style="background: url(<?php the_post_thumbnail_url(); ?>) no-repeat scroll center center;background-size: cover;">
					<a class="r_news_feature_post_link" href="<?php the_permalink(); ?>"></a>
				</div>
				<div class="r_news_feature_post_right">
					<div class="r_news_feature_post_right_content">
						<h3 class="r_news_feature_post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="r_news_feature_post_excerpt">
							<p><?php echo wp_trim_words(get_the_content(), 30, true); ?></p>
						</div>
					</div>
					<div class="r_news_left_inner_btm inner_arrow_circle"><a href="<?php the_permalink(); ?>"><span>Leggi
								tutto</span> <span><i class="fas fa-chevron-right"></i></span></a>
					</div>
				</div>
			</div>
		</div>
	</header><!-- .entry-header -->

</article><!-- #post-## -->