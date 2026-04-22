<?php
/*
Template Name: On Demand
*/
?>
<?php get_header(); ?>

<main>
    <div class="puk_container">
        <div class="container-fluid">
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
                            <div class="puk-posts-list">
                                <?php get_template_part( 'template-parts/components/post', 'od'); ?>
                                <?php get_template_part( 'template-parts/components/post', 'od2'); ?>
                                <?php get_template_part( 'template-parts/components/post', 'od3'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>


<?php
get_footer();
