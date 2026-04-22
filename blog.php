<?php
/*
Template Name: Blog
*/
?>
<?php get_header(); ?>

<main>
    <div class="container puk_container">
       
            <div class="blog_layout">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
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
                            <!-- <ul class="blog_sidebar_cats">
                                <li class="active"><a href="#">Blog</a> </li>
                                <li><a href="#">Events</a></li>
                                <li><a href="#">On demand</a></li>
                                <li><a href="#">Video</a></li>
                            </ul> -->
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
                        <div class="blog_content">
                            <?php
                            /**
                             * Post Listing Repeater Section
                             * Displays posts with custom card layout - no limit
                             */

                            // Query all posts with pagination
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $args = array(
                                'post_type'      => 'post',
                                'posts_per_page' => 6,
                                'paged'          => $paged,
                                'post_status'    => 'publish',
                                'orderby'        => 'date',
                                'order'          => 'DESC'
                            );

                            $post_query = new WP_Query($args);

                            if ($post_query->have_posts()) : ?>
                                <div id="puk-posts-container" class="puk-posts-list">
                                    <?php while ($post_query->have_posts()) : $post_query->the_post(); ?>
                                        
                                        <div class="puk-post-card">
                                            <a class="puk-post-card__link" href="<?php echo esc_url(get_permalink()); ?>">
                                                <div class="puk-post-card__body">
                                                    <div class="puk-post-card__date">
                                                        <h5><?php echo get_the_date('d.m.y'); ?></h5>
                                                    </div>
                                                    
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <div class="puk-post-card__media">
                                                            <?php 
                                                            the_post_thumbnail('full', array(
                                                                'class' => 'puk-post-card__img',
                                                                'alt'   => get_the_title()
                                                            )); 
                                                            ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="puk-post-card__content">
                                                        <h3 class="puk-post-card__title"><?php echo esc_html(get_the_title()); ?></h3>
                                                        
                                                        <article class="puk-post-card__excerpt">
                                                            <?php 
                                                            if (has_excerpt()) {
                                                                the_excerpt();
                                                            } else {
                                                                echo '<p>' . wp_trim_words(get_the_content(), 55, '...') . '</p>';
                                                            }
                                                            ?>
                                                        </article>
                                                        
                                                        <div class="puk-post-card__arrow">
                                                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M18.0449 10.3453L17.5805 9.88006L13.6999 6.00291L12.7907 6.93425L15.5608 9.69994L2.58988 9.70572L2.59045 10.9987L15.5608 10.9929L12.7938 13.7611L13.7044 14.6916L17.5816 10.811L18.0449 10.3453Z" fill="black" fill-opacity="0.5"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        
                                    <?php endwhile; ?>
                                </div>

                                <?php if ($post_query->max_num_pages > 1) : ?>
                                    <div class="puk-load-more-wrapper" style="text-align: center; margin-top: 40px;">
                                        <button id="puk-load-more" class="btn btn-primary" 
                                                data-page="1" 
                                                data-max-pages="<?php echo $post_query->max_num_pages; ?>">
                                            <?php esc_html_e('Load More', 'puk'); ?>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <?php wp_reset_postdata(); ?>
                            <?php else : ?>
                                <p><?php esc_html_e('No posts found.', 'puk'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>

</main>


<?php
get_footer();
