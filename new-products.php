<?php
/*
Template Name: New Product
*/
?>
<?php get_header(); ?>


<main>

<section class="product-page-main product-page-main-new">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="product_title">
                    <h1> <?php echo get_the_title(); ?> </h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
               <?php 
                    // get the running page dynamically 
                    $page = get_pages(array(
                        'meta_key'   => '_wp_page_template',
                        'meta_value' => 'product-page.php',
                        'number'     => 1
                    ));

                    if ( !empty($page) ) {
                        $page_url = get_permalink($page[0]->ID);
                    }


                    $new_page = get_pages(array(
                        'meta_key'   => '_wp_page_template',
                        'meta_value' => 'new-products.php'
                    )); 
                    if (!empty($new_page)) {
                        $new_page_url = get_permalink($new_page[0]->ID);
                    }

               ?>
                <div class="product_categories_new">
                    <ul>
                        <li><a href="<?php echo $page_url; ?>" class="active"> View all products </a></li>
                        <li><a href="<?php echo  $new_page_url; ?>" class="is_new"> NEW </a></li>  
                    </ul>
                </div>
            </div>
        </div>
     
        <!-- product row start  -->
        <div class="row product_row new_product_grid">



<?php
    $args = array(
        'post_type'      => 'products',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'is_new',
                'value'   => 'yes',
                'compare' => '='
            )
        )
    );

    $new_products = new WP_Query( $args );

    if ( $new_products->have_posts() ) :
        while ( $new_products->have_posts() ) : $new_products->the_post();

            // Image
            $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if ( !$image ) {
                $image = get_template_directory_uri() . '/assets/img/default.jpg';
            }

                // Title
                $title = get_the_title();
                $subtitle = get_field('subtitle'); 
                $feature_image = get_the_post_thumbnail_url(); ; 

            ?>
            
            <!-- product item  -->
                <div class="product_row_item">
                    <div class="product_img_wrap">
                        <img src="<?php echo $image; ?>" alt="">
                        <div class="product_hover_box">
                            <h4><?php the_title(); ?></h4>
                            <p><?php echo $subtitle; ?></p>
                        </div>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="product_item_title"><?php the_title(); ?></a>
                </div>


            <?php
        endwhile;
    endif;

    wp_reset_postdata();
?>


           

           

        </div>


    </div>
</section>



</main>


<?php
get_footer();
