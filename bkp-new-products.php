<?php /* Template Name: BKP New Product */ ?>
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
                <div>
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
    'taxonomy'   => 'product-family',
    'hide_empty' => false,
);
$terms = get_terms($args);

if ( !empty($terms) && !is_wp_error($terms) ) {

    foreach ( $terms as $term ) {
        // taxonomy ACF fields
        $subtitle = get_field('pro_subtitle', $term);
        $image = get_field('pf_fet_img', $term);
        $tax_sub_family_features = get_field('tax_sub_family_features', $term);
        
        $show_term = false;
        if( !empty($tax_sub_family_features) ){
            foreach($tax_sub_family_features as $feature_id){
                $feature = get_term($feature_id, 'features');
                if( !is_wp_error($feature) && $feature->slug == 'new' ){
                    $show_term = true;
                }
            }
        }

        // image fallback
        if( !$image ){
            $image = get_template_directory_uri() . '/assets/img/default.jpg';
        }
        $term_link = get_term_link($term);
        if($show_term){
        
        ?>

        <!-- product item -->
        <div class="product_row_item">
            <div class="product_img_wrap">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($term->name); ?>">
                <a href="<?php echo $term_link; ?>">
                  <div class="product_hover_box">
                        <h4><?php echo esc_html($term->name); ?></h4>
                        <p><?php echo esc_html($subtitle); ?></p>
                    </div>
                </a>
            </div>

            <a href="<?php echo esc_url($term_link); ?>" class="product_item_title">
                <?php echo esc_html($term->name); ?>
            </a>
        </div>

        <?php
      }
   }
}
?>


           

           

        </div>


    </div>
</section>



</main>


<?php
get_footer();
