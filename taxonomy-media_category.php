<?php get_header(); ?>

<main>
    <div class="container puk_container">
            <div class="blog_layout">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                        <div class="blog_sidebar">
                            <?php
                            if (has_nav_menu('bs_menu')) {
                                wp_nav_menu(
                                    array(
                                        'theme_location'  => 'bs_menu',
                                        'container_class'  => 'blog_sidebar',
                                        'menu_class'      => 'blog_sidebar_cats',
                                    )
                                );
                            } else {
                                ?>
                                <p>There is not active menu for this location. Please setup from the menu option</p>
                                <?php
                            } 
                            ?>  
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                        <div class="blog_content">
                            <?php 
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $term = get_queried_object();
                            $args = array(
                                'post_type' => 'mediapress',
                                'posts_per_page' => 6,
                                'paged' => $paged,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'media_category',
                                        'field'    => 'term_id',
                                        'terms'    => $term->term_id,
                                    ),
                                ),
                            );
                            $media_query = new WP_Query($args);
                            ?>

                            <div id="puk-media-container" class="puk-posts-list">
                                <?php if ( $media_query->have_posts() ) : ?>
                                    <?php while ( $media_query->have_posts() ) : $media_query->the_post(); ?>
                                        <div class="puk-post-card">
                                            <div class="puk-post-card__link">
                                                <div class="puk-post-card__body">
                                                    <div class="puk-post-card__date">
                                                        <h5><?php echo get_the_date('d.m.y'); ?></h5>
                                                    </div>
                                                    <?php
                                                    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                                    $bg_image = $featured_image ? $featured_image : get_template_directory_uri() . '/assets/images/ondemand.jpg';
                                                    $video_icon_color = get_field('video_icon_color');
                                                    ?>
                                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="puk-post-card__media video <?php if ($video_icon_color == 'white') { echo 'dark'; } ?> position-relative" style="background-image: url('<?php echo esc_url($bg_image); ?>');">
                                                        <?php if (isset($term->slug) && in_array($term->slug, array('on-demand', 'video'))) { ?>
                                                            <div class="puk-post-card__play">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="39" height="39" viewBox="0 0 39 39" fill="none">
                                                                    <path d="M19.25 0C24.3554 0 29.2517 2.02812 32.8618 5.63819C36.4719 9.24827 38.5 14.1446 38.5 19.25C38.5 24.3554 36.4719 29.2517 32.8618 32.8618C29.2517 36.4719 24.3554 38.5 19.25 38.5C14.1446 38.5 9.24827 36.4719 5.63819 32.8618C2.02812 29.2517 0 24.3554 0 19.25C0 14.1446 2.02812 9.24827 5.63819 5.63819C9.24827 2.02812 14.1446 0 19.25 0ZM19.25 35.75C23.6261 35.75 27.8229 34.0116 30.9173 30.9173C34.0116 27.8229 35.75 23.6261 35.75 19.25C35.75 14.8739 34.0116 10.6771 30.9173 7.58274C27.8229 4.48839 23.6261 2.75 19.25 2.75C14.8739 2.75 10.6771 4.48839 7.58274 7.58274C4.48839 10.6771 2.75 14.8739 2.75 19.25C2.75 23.6261 4.48839 27.8229 7.58274 30.9173C10.6771 34.0116 14.8739 35.75 19.25 35.75ZM17.1875 25.1102L25.9792 19.25L17.1875 13.3897V25.1102ZM17.644 10.3868L28.3635 17.534C28.646 17.7224 28.8776 17.9775 29.0378 18.2769C29.198 18.5762 29.2818 18.9105 29.2818 19.25C29.2818 19.5895 29.198 19.9238 29.0378 20.2231C28.8776 20.5225 28.646 20.7776 28.3635 20.966L17.644 28.1132C17.3334 28.3203 16.9724 28.4392 16.5996 28.4572C16.2267 28.4752 15.8559 28.3917 15.5268 28.2156C15.1977 28.0394 14.9225 27.7773 14.7307 27.4571C14.5389 27.1368 14.4375 26.7705 14.4375 26.3972V12.1C14.4375 11.7267 14.5389 11.3604 14.7307 11.0402C14.9225 10.72 15.1977 10.4578 15.5268 10.2817C15.8559 10.1055 16.2267 10.022 16.5996 10.0401C16.9724 10.0581 17.3334 10.1769 17.644 10.384V10.3868Z" fill="black" />
                                                                </svg>
                                                            </div>
                                                            <div class="puk-post-card__watermark"><?php echo esc_html(get_the_title()); ?></div>
                                                        <?php } ?>
                                                    </a>
                                                    <div class="puk-post-card__content">
                                                        <h3 class="puk-post-card__title">
                                                            <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?><?php if (isset($term->slug) && in_array($term->slug, array('on-demand', 'video'))) { ?>_<?php } ?> </a>
                                                        </h3>
                                                        <?php
                                                        $media_sub_title = get_field('media_sub_title');
                                                        if ($media_sub_title) {
                                                            echo '<h4 class="mp_sub"><a href="' . esc_url(get_permalink()) . '">' . esc_html($media_sub_title) . '</a></h4>';
                                                        }
                                                        ?>
                                                        <article class="puk-post-card__excerpt">
                                                            <p><?php echo wp_trim_words(get_the_content(), 45, '...'); ?></p>
                                                        </article>
                                                        <a href="<?php echo esc_url(get_permalink()); ?>" class="puk-post-card__arrow">
                                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M18.0449 10.3453L17.5805 9.88006L13.6999 6.00291L12.7907 6.93425L15.5608 9.69994L2.58988 9.70572L2.59045 10.9987L15.5608 10.9929L12.7938 13.7611L13.7044 14.6916L17.5816 10.811L18.0449 10.3453Z" fill="black" fill-opacity="0.5" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>

                            <?php if ($media_query->max_num_pages > 1) : ?>
                                <div class="puk-load-more-wrapper" style="text-align: center; margin-top: 40px;">
                                    <button id="puk-load-more-media" class="btn btn-primary" 
                                            data-page="1" 
                                            data-max-pages="<?php echo $media_query->max_num_pages; ?>"
                                            data-action="load_more_media"
                                            data-container="#puk-media-container"
                                            data-category="<?php echo $term->term_id; ?>">
                                        <?php esc_html_e('Load More', 'puk'); ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</main>


<?php
get_footer();
