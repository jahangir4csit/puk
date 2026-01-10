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
                            <ul class="blog_sidebar_cats">
                                <li><a href="#">Blog</a> </li>
                                <li><a href="#">Events</a></li>
                                <li class="active"><a href="#">On demand</a></li>
                                <li><a href="#">Video</a></li>
                            </ul>
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
