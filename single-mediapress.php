<?php get_header();?>
<main>
    <div class="container puk_container">
        <div class="blog_layout ondemand_layout">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                    <div class="blog_sidebar">
                        <ul class="blog_sidebar_cats">
                            <?php
                            // For a custom post type with custom taxonomy
                            $terms = get_the_terms( get_the_ID(), 'media_category' ); // Change 'product_category' to your taxonomy
                            
                            if ( $terms && ! is_wp_error( $terms ) ) {
                                $term = reset( $terms );
                                ?>
                                <li class="active"><a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                                    <?php echo esc_html( $term->name ); ?>
                                </a></li>
                                <?php
                            }
                            ?>
                        </ul>
                        <div class="blog_back_link">
                            <?php
                            if ( $terms && ! is_wp_error( $terms ) ) {
                                $term = reset( $terms );
                                ?>
                                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                                    <span>Back</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M2.98867 11.6712L3.51225 12.1958L7.88751 16.5672L8.91259 15.5171L5.78938 12.3989L20.4138 12.3923L20.4131 10.9346L5.78946 10.9411L8.90916 7.82006L7.88242 6.77092L3.51105 11.1462L2.98867 11.6712Z" fill="black"/>
                                    </svg>
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                    <div class="blog_content">
                        <div class="blog_content_details">
                            <div class="blog_details_title_wrap">
                                <div class="puk-post-card__date">
                                    <h5><?php echo get_the_date('d.m.y'); ?></h5>
                                </div>
                                <h1><?php the_title();?></h1>
                            </div>
                            <div class="puk-post-card__body">
                                <div class="puk-post-card__space"></div>
                                <div class="puk-post-card__content">
                                    <?php if( get_field('video_link') ){ ?>
                                    <div class="puk-post-card_video_media">
                                        <?php the_field('video_link'); ?>
                                    </div>
                                    <?php } else { ?>
                                    <div class="puk-post-card__media">
                                        <?php 
                                        the_post_thumbnail('full', array(
                                            'class' => 'puk-post-card__img',
                                            'alt'   => get_the_title()
                                        )); 
                                        ?>
                                    </div>
                                    <?php } ?>
                                    <article class="puk-post-card__excerpt">
                                        <?php if( get_field('media_sub_title') ){ ?>
                                            <h4><?php the_field('media_sub_title'); ?></h4>
                                        <?php } ?>
                                        <?php 
                                        if (have_posts()) : while (have_posts()) : the_post(); 
                                            ?>
                                            <?php the_content(); ?>
                                            <?php 
                                        endwhile; 
                                        else: 
                                            ?>
                                            <p>Sorry, no posts matched your criteria.</p>
                                            <?php 
                                        endif; 
                                        ?>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer();?>
