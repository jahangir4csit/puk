<?php get_header(); ?>
<main>
    <section class="common-breadcrumb-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="common-breadcrumb-wrapper">

                        <?php
                          // Get current term
                          $terms = wp_get_post_terms(get_the_ID(), 'products-family');


                        //   echo '<pre>' ; 
                        //   print_r($terms) ; 


                          $current_term = $terms[0];
                          $term_id      = $current_term->term_id;
                          $taxonomy     = $current_term->taxonomy;
                          $ancestors = get_ancestors($term_id, $taxonomy);
                          $ancestors = array_reverse($ancestors);
                          echo '<ul>';
                          echo '<li><a href="' . home_url() . '">Home</a></li>';
                          if (!empty($ancestors)) {
                              foreach ($ancestors as $ancestor_id) {
                                  $ancestor = get_term($ancestor_id, $taxonomy);
                                  echo '<li><a href="' . get_term_link($ancestor) . '">' . esc_html($ancestor->name) . '</a></li>';
                              }
                          }
                          echo '<li><a href="' . get_term_link($current_term) . '">' . esc_html($current_term->name) . '</a></li>';
                          echo '<li>' . get_the_title() . '</li>';
                          echo '</ul>'; 
                        ?>


                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php 

      $parent_term_id = $ancestors[2] ; 
      $designed_by     =  get_field('pf_designed_by', 'products-family_' . $parent_term_id);
      $subfamily_desc  =  get_field('pf_subfam_desc', 'products-family_' . $parent_term_id);
      $subsub_family_gallary =  get_field('pf_subsub_fam_tch_feturs', 'products-family_' . $term_id); 
      $site_plachlder_img = get_field('site_plachlder_img','option') ;

    ?>

    <section class="pd-title-main">
        <div class="container-fluid px-0">
            <div class="row g-0">
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="title-box-flex">
                        <div class="title-box">
                            <h1 aria-label="product title"><?php echo esc_html($current_term->name); ?></h1>
                                <?php if ( $designed_by ) : ?>
                                    <h2 aria-label="product sub title">Designed by <br> <?php echo esc_html($designed_by); ?></h2>   
                                <?php endif; ?>
                        </div>
                        <div class="description-box">
                            <article>
                                <p>
                                    <?php echo $subfamily_desc; ?>
                                </p>
                            </article>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="project-slider-wrapper title-box-flex">
                        <div class="swiper project-slider">
                            <div class="swiper-wrapper">
                                <?php
                                    $pro_gallary = get_field('pro_gallary') ; 
                                    foreach($pro_gallary as $pro_image){ 
                                    ?> 
                                    <div class="swiper-slide">
                                        <div class="image-box">
                                            <img src="<?php echo $pro_image; ?>" alt="Wall Mounting 1" />
                                        </div>
                                    </div>
                                <?php } ?> 
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="pd-single-data">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12"></div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="pd-single-data-dtls">
                        <ul>
                            <?php 

                                $wattage       = get_field('pro_wattage');
                                $cct           = get_field('pro_cct');
                                $beam_angle    = get_field('pro_beam_angle');
                                $lumens        = get_field('pro_lumens');
                                $finish        = get_field('pro_finish');
                                $finish_color  = get_field('pro_finish_color');
                                $dimming       = get_field('pro_dimming'); 
                                $iprating =  get_field('pro_iprating'); 
                                $ikrating =  get_field('pro_ikrating'); 
                                $pro_material = get_field('pro_material'); 
                                $pro_coating = get_field('pro_coating'); 
                                $pro_light_source = get_field('pro_light_source'); 
                                $pro_screws = get_field('pro_screws'); 
                                $pro_transformer = get_field('pro_transformer'); 
                                $pro_gasket = get_field('pro_gasket'); 
                                $pro_glass = get_field('pro_glass'); 
                                $pro_cable_gland = get_field('pro_cable_gland'); 
                                $pro_pwr_cble = get_field('pro_pwr_cble'); 
                                $pro_grs_weight = get_field('pro_grs_weight'); 
                                $pro_mesr_img =  get_field('pf_subfam_tech_drawing', 'products-family_' . $parent_term_id);
                                
                                // print_r($ancestors) ; 
                        ?>
                            <li>
                                <span>Wattage</span>
                                <p> <?php echo $wattage; ?> </p>
                            </li>
                            <li>
                                <span>CCT</span>
                                <p> <?php echo $cct; ?> </p>
                            </li>
                            <li>
                                <span>Beam Angle</span>
                                <p> <?php echo $beam_angle; ?> </p>
                            </li>
                            <li>
                                <span>Lumens</span>
                                <p> <?php echo $lumens; ?> </p>
                            </li>

                            <li class="finish_li">
                                <span>Finish</span>
                                <p> <?php echo $finish; ?> </p>
                            </li>
                            <li>
                                <span>IP Rating </span>
                                <p> <?php echo $iprating; ?> </p>
                            </li>
                            <li>
                                <span> IK Rating </span>
                                <p> <?php echo $ikrating; ?> </p>
                            </li>
                            <li>
                                <span> Material </span>
                                <p> <?php echo $pro_material; ?> </p>
                            </li>
                            <li>
                                <span> Coating </span>
                                <p> <?php echo $pro_coating; ?> </p>
                            </li>
                            <li>
                                <span> Light source </span>
                                <p> <?php echo $pro_light_source; ?> </p>
                            </li>
                            <li>
                                <span> Screws </span>
                                <p> <?php echo $pro_screws; ?> </p>
                            </li>
                            <li>
                                <span> Transformer </span>
                                <p> <?php echo $pro_transformer; ?> </p>
                            </li>
                            <li> 
                                <span> Gasket </span>
                                <p>  <?php echo $pro_gasket; ?> </p>
                            </li>
                            <li>
                                <span> Glass </span> 
                                <p> <?php echo $pro_glass; ?> </p>
                            </li>
                            <li>
                                <span> Cable gland </span>
                                <p> <?php echo $pro_cable_gland; ?> </p>
                            </li>
                            <li>
                                <span> Power cable </span>
                                <p><?php echo $pro_pwr_cble; ?> </p>
                            </li>
                            <li>
                                <span> Gross weight </span>
                                <p> <?php echo $pro_grs_weight; ?></p>
                            </li> 
                        </ul>
                    </div>

                    <div class="pd-single-data-icns">
                        <?php foreach($subsub_family_gallary as $subsub_family_img){ ?>
                            <div class="icon_item">
                                <img src="<?php echo $subsub_family_img ; ?>" alt="technical features" />
                            </div>
                        <?php } ?>
                    </div>

                    <?php 
                      $pd_alavlbl = get_field('pd_alavlbl_select_product');  
                      $pro_sub_gallary = get_field('pro_sub_gallary');  
                      $pro_remote_drv_slctn = get_field('pro_remote_drv_slctn');   


                    //   echo '<pre>' ; 
                    //   print_r($pd_alavlbl) ; 

                    ?>
                    <!-- also available color  -->
                    <div class="pd-single-data-clr">
                        <div class="also-available-title">
                            ALSO AVAILABLE IN
                        </div>
                        <div class="available-colors-list">
                            <?php foreach($pd_alavlbl as $pd_alavaible){ 

                                
                                $product_id = $pd_alavaible ; 
                                $product_name = get_the_title($product_id);
                                $product_link = get_the_permalink($product_id);
                                $color = get_post_meta($product_id, 'pro_finish_color', true);
                                $code = get_post_meta($product_id, 'pro_finish', true);


                            ?>     
                            <!-- Single Item -->
                            <div class="color-item">
                                <span class="color-dot" style="background:<?php echo $color; ?>;"></span>
                                <span class="color-name"><?php echo $code; ?></span>
                                <span class="product-code"> <a href="<?php echo $product_link; ?>"><?php echo $product_name; ?></a> </span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- sub gallary  -->
                    <div class="pd-single-data-sbglry">
                        <?php foreach($pro_sub_gallary as $pro_sub_gallary_img){ ?>
                            <div class="single-sf">
                                
                                    <img src="<?php echo $pro_sub_gallary_img ; ?>" alt="sf1.jpg" />
                          
                            </div>
                        <?php } ?>
                    </div>

                    <!-- driver section  -->
                    <div class="pd-single-data-driver-selection">
                        <h3 class="ds-title">REMOTE DRIVER SELECTION</h3>
                        <div class="table-responsive">
                            <table class="table">

                                <tbody>

                                <?php foreach($pro_remote_drv_slctn as $pro_remote_slctn){  
                                    $pro_remote_meanwell = $pro_remote_slctn['pro_remote_meanwell'] ; 
                                    $pro_remote_lpv      = $pro_remote_slctn['pro_remote_lpv'] ; 
                                    $pro_remote_volt     = $pro_remote_slctn['pro_remote_volt'] ; 
                                    $pro_remote_watt     = $pro_remote_slctn['pro_remote_watt'] ; 
                                    $pro_remote_ip       = $pro_remote_slctn['pro_remote_ip'] ; 
                                    $pro_remote_min_max  = $pro_remote_slctn['pro_remote_min_max'] ; 
                                ?>
                                    <tr>
                                        <td><?php echo $pro_remote_meanwell; ?></td>
                                        <td><?php echo $pro_remote_lpv; ?></td>
                                        <td><?php echo $pro_remote_volt; ?></td>
                                        <td><?php echo $pro_remote_watt; ?></td>
                                        <td><?php echo $pro_remote_ip; ?></td>
                                        <td><?php echo $pro_remote_min_max; ?></td>
                                    </tr> 
                                 <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                    <div class="right-image-box single-techdraw">
                        <?php if($pro_mesr_img){ ?>
                            <img src="<?php echo $pro_mesr_img ;?>" alt="Wall Mounting 2" />
                      <?php  }else{ ?>
                            <img src="<?php echo $site_plachlder_img ;?>" alt="Wall Mounting 2" /> 
                       <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="integrated-accessories-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title">
                        <h3>Integrated Accessories</h3>
                    </div>
                </div>
            </div>
        </div>


        <?php 
            $pro_inte_access_stitle = get_field('pro_inte_access_stitle');  
            $pro_inte_access_sdesc = get_field('pro_inte_access_sdesc');  
            $pro_inte_access_rept = get_field('pro_inte_access_rept');   
        ?>


        <div class="accessories-bg" style="background: rgba(217, 217, 217, 0.25);">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="title-box">
                            <h3 style="color: rgba(0, 0, 0, 0.50);"><?php echo  $pro_inte_access_stitle; ?></h3>
                            <?php echo  $pro_inte_access_sdesc; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-2 col-lg-2"></div>
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="accessories-grid-parent">

                        <?php foreach($pro_inte_access_rept as $pro_inte_access_item){ 
                            $image    =  $pro_inte_access_item['pro_inte_access_rept_img'];
                            $title    =  $pro_inte_access_item['pro_inte_access_rept_title'];
                            $subtitle =  $pro_inte_access_item['pro_inte_access_rept_subtitle'];
                            $desc     =  $pro_inte_access_item['pro_inte_access_rept_desc']; 
                        ?> 
                            <div class="single-grid">
                                <div class="image-box">
                                    <img src="<?php echo $image; ?>" alt="" />
                                </div>
                                <div class="content-box">
                                    <h5><?php echo $title; ?> </h5>
                                    <h6><?php echo $subtitle; ?></h6>
                                    <p> <?php echo $desc; ?> </p>
                                </div>
                            </div>
                        <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="integrated-accessories-main">

    
        <?php 
            $pro_not_incld = get_field('pro_not_incld');   
        ?>

        <div class="accessories-bg" style="background: rgba(192, 186, 176, 0.40);">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="title-box">
                            <h3 style="color: #000;">Accessories not included</h3>
                            <p style="color: #000;">
                                Accessories to be ordered separately
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-2 col-lg-2"></div>
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="accessories-grid-parent">

                           <?php foreach($pro_not_incld as $pro_not_incld_item){ 
                            $image    =  $pro_not_incld_item['pro_not_incld_img'];
                            $title    =  $pro_not_incld_item['pro_not_incld_title'];
                            $subtitle =  $pro_not_incld_item['pro_not_incld_subtitle'];
                            $desc     =  $pro_not_incld_item['pro_not_incld_desc']; 
                        ?> 

                            <div class="single-grid">
                                <div class="image-box">
                                    <img src="<?php echo $image ; ?>" alt="Wall Mounting 3" />
                                </div>
                                <div class="content-box">
                                    <h5><?php echo $title ; ?></h5>
                                    <h6><?php echo $subtitle ; ?></h6>
                                    <p><?php echo $desc ; ?></p> 
                                </div>
                            </div>

                        <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- light distribution start -->
    <section class=" light-distribution-main d-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title-box">
                        <h3>Light Distribution</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-sm-12 col-md-10 col-lg-10">
                    <div class="light-grid-parent">

                    <?php 
                       $pro_lst_dstrbtn_glry = get_field('pro_lst_dstrbtn_glry');   
                       foreach($pro_lst_dstrbtn_glry as $pro_lst_img){ ?>
                       <div class="single-light">
                            <img src="<?php echo $pro_lst_img; ?>" alt="Wall Mounting 4" />
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- light distribution start -->

    <section class=" light-distribution-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title-box">
                        <h3>Download</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-sm-12 col-md-10 col-lg-10">
                    <div class="download-grid-parent">

                    <?php 

                        $pro_dwnld_ltd_files = get_field('pro_dwnld_ltd_files'); 
                        $pro_dwnld_instructions = get_field('pro_dwnld_instructions');  
                        $pro_dwnld_revit = get_field('pro_dwnld_revit');  
                        $pro_dwnld_3dbim = get_field('pro_dwnld_3dbim');  
                        $pro_dwnld_photometric = get_field('pro_dwnld_photometric');  
                        $pro_dwnld_provideo = get_field('pro_dwnld_provideo');    
                       
                    ?>

                        <div class="single-download">
                            <a href="<?php echo $pro_dwnld_ltd_files; ?>" download>
                                <div class="icon">
                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg" alt="download.png" />
                                </div>
                                <div class="text">Data Sheet</div>
                            </a>
                        </div>
                        <div class="single-download">
                            <a href="<?php echo $pro_dwnld_instructions; ?>" download>
                                <div class="icon">
                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg" alt="download.png" />
                                </div>
                                <div class="text">Installation instructions</div>
                            </a>
                        </div>
                        <div class="single-download">
                            <a href="<?php echo $pro_dwnld_photometric; ?>" download>
                                <div class="icon">
                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg" alt="download.png" />
                                </div>
                                <div class="text">Photometric Data</div>
                            </a>
                        </div>
                        <div class="single-download">
                            <a href="<?php echo $pro_dwnld_3dbim; ?>" download>
                                <div class="icon">
                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg" alt="download.png" />
                                </div>
                                <div class="text">3D BIM</div>
                            </a>
                        </div>
                        <div class="single-download">
                            <a href="<?php echo $pro_dwnld_provideo; ?>" >
                                <div class="icon">
                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_play.svg" alt="play.png" />
                                </div>
                                <div class="text">Product Video</div>
                            </a>
                        </div>
                        <div class="single-download">
                            <a href="<?php echo $pro_dwnld_revit; ?>" download>
                                <div class="icon">
                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg" alt="download.png" />
                                </div>
                                <div class="text">Revit</div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class=" light-distribution-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title-box">
                        <h3>Info</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-sm-12 col-md-10 col-lg-10">
                   
                
               <div class="inforzioni-box">
                        <p>Hai bisogno di ricevere maggiori informazioni su questo prodotto?</p>
                        <a href="#">
                            <div class="text">Richiesta informazioni</div>
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42" fill="none">
                                    <path d="M3.5 21C3.5 11.3348 11.3347 3.5 21 3.5C30.6652 3.5 38.5 11.3348 38.5 21C38.5 30.6652 30.6652 38.5 21 38.5C17.9812 38.5 15.1392 37.7352 12.6586 36.3877L5.45125 38.437C4.29712 38.7651 3.23137 37.6985 3.5595 36.5452L5.60875 29.337C4.21986 26.778 3.49483 23.9116 3.5 21ZM14 17.7188C14 18.3225 14.49 18.8125 15.0937 18.8125H26.9062C27.0499 18.8125 27.1921 18.7842 27.3248 18.7292C27.4575 18.6743 27.5781 18.5937 27.6796 18.4921C27.7812 18.3906 27.8618 18.27 27.9167 18.1373C27.9717 18.0046 28 17.8624 28 17.7188C28 17.5751 27.9717 17.4329 27.9167 17.3002C27.8618 17.1675 27.7812 17.0469 27.6796 16.9454C27.5781 16.8438 27.4575 16.7632 27.3248 16.7083C27.1921 16.6533 27.0499 16.625 26.9062 16.625H15.0937C14.49 16.625 14 17.115 14 17.7188ZM15.0937 23.1875C14.8037 23.1875 14.5255 23.3027 14.3203 23.5079C14.1152 23.713 14 23.9912 14 24.2812C14 24.5713 14.1152 24.8495 14.3203 25.0546C14.5255 25.2598 14.8037 25.375 15.0937 25.375H23.4062C23.6963 25.375 23.9745 25.2598 24.1796 25.0546C24.3848 24.8495 24.5 24.5713 24.5 24.2812C24.5 23.9912 24.3848 23.713 24.1796 23.5079C23.9745 23.3027 23.6963 23.1875 23.4062 23.1875H15.0937Z" fill="black" />
                                </svg>
                            </div>
                        </a>
                    </div>
                    <div class="toggle-form-box">
                        <?php  echo do_shortcode('[contact-form-7 id="d492052" title="Contact form For Product Details Page"]'); ?>
                    </div>



            </div>
        </div>
    </section>
    <section class=" light-distribution-main pb-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title-box">
                        <h3>Related Products</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-sm-12 col-md-10 col-lg-10">

                <!-- new code start  -->

                <div class="related-prod-wrapper pd-single-data-sbglry">
                    <div class="swiper related-product-slider">

                        <!-- related products with products logic  -->
                        <div class="swiper-wrapper">

                                  <?php
                                        $taxonomy = 'products-family';
                                        $product_terms = get_the_terms(get_the_ID(), $taxonomy);
                                        if (!empty($product_terms) && !is_wp_error($product_terms)) {

                                            $current_term = $product_terms[0];
                                            $level2_parent = get_term($current_term->parent);  //45 
                                            if ($level2_parent) {
                                                $root_parent_id = $level2_parent->parent; 
                                                $target_terms = get_terms([
                                                    'taxonomy'   => $taxonomy,
                                                    'hide_empty' => false,
                                                    'parent'     => $root_parent_id,
                                                ]);

                                                foreach ($target_terms as $term) {
                                                    if ($term->term_id != $level2_parent->term_id) {

                                                        // OUTPUT terms here
                                                        echo '<div class="swiper-slide">';
                                                        echo '<div class="single-rp">';
                                                        echo '<a href="' . get_term_link($term) . '" title="' . $term->name . '">';

                                                        // term image via ACF or WP term meta
                                                        $image =  get_field('pf_fet_img', 'products-family_' . $term->term_id);   
                                                        $image_url = $image ? $image : $site_plachlder_img;

                                                        echo '<div class="image-box">';
                                                        echo '<img src="'. $image_url .'" alt="'. $term->name .'" />';
                                                        echo '</div>';

                                                        echo '<h5>'. $term->name .'</h5>';
                                                        echo '</a>';
                                                        echo '</div>';
                                                        echo '</div>';

                                                    }
                                                }
                                            }
                                        }
                                ?>

                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>

                    </div>


                <!-- new code end  -->
  


                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var currentUrlField = document.getElementById('current_page_url');
    if(currentUrlField) {
        currentUrlField.value = window.location.href;
    }
});
</script>


<?php
get_footer();
