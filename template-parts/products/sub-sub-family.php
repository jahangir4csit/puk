<main>
    <section class="common-breadcrumb-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="common-breadcrumb-wrapper">
                        <?php
                          // Get current term
                          $current_term = get_queried_object();

                          // print_r($current_term->parent) ;


                          $term_id      = $current_term->parent;
                          $taxonomy     = $current_term->taxonomy; 
                          $ancestors = get_ancestors($term_id, $taxonomy);
                          $ancestors = array_reverse($ancestors);
                          echo '<ul class="breadcrumb">';
                          echo '<li><a href="' . home_url() . '">Home</a></li>';
                          if (!empty($ancestors)) {
                              foreach ($ancestors as $ancestor_id) {
                                  $ancestor = get_term($ancestor_id, $taxonomy);
                                  echo '<li><a href="' . get_term_link($ancestor) . '">' . esc_html($ancestor->name) . '</a></li>';
                              }
                          }
                          echo '<li>' . esc_html($current_term->name) . '</li>';
                          echo '</ul>'; 
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
      
      $designed_by =  get_field('pf_designed_by', 'products-family_' . $term_id);
      $subfamily_desc =  get_field('pf_subfam_desc', 'products-family_' . $term_id);
      $subfamily_pro_img =  get_field('pf_subfam_product_image', 'products-family_' . $term_id);
      $subfamily_tech_draw =  get_field('pf_subfam_tech_drawing', 'products-family_' . $term_id);

    ?>

    <section class="pf-gallary-main">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">           
                    <div class="title-box-flex">
                        <div class="title-box">
                            <h1 aria-label="product title"> <?php echo esc_html($ancestor->name); ?> </h1>
                            <h2 aria-label="product sub title"> Designed by <br> <?php echo $designed_by;?></h2>
                        </div>
                        <div class="description-box">
                            <article>
                                <p><?php echo $subfamily_desc; ?></p>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="pf-grallary-grid">

                        <div class="single-grid">
                            <div class="swiper pf-display-slider">
                                <div class="swiper-wrapper">
                                <?php 
                                foreach($subfamily_pro_img as $subfamily_img){ ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo $subfamily_img ; ?>"alt="pf-g1.png">
                                </div>
                                <?php } ?>
                                </div>
                                <p>Product IMAGE</p>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>



                        <div class="single-grid">
                            
                                <img src="<?php echo $subfamily_tech_draw; ?>"
                                    alt="Technical Drawing">
                            
                            <p>technical drawing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pf-accordion-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="custom-accordion-wrapper">
                       
                    <?php 
                      // Get only child terms of THIS subcategory
                      // $sub_sub_term_terms = get_terms(array(
                      //     'taxonomy'   => 'products-family',
                      //     'parent'     => $term_id,
                      //     'hide_empty' => false,
                      //     'orderby'    => 'term_id',
                      //     'order'      => 'ASC'
                      // ));


                      $sub_sub_term = get_queried_object() ; 


                    // foreach ($sub_sub_term_terms as $sub_sub_term) : 
                       
                        $index_number =  get_field('pf_subsub_fam_indx', 'products-family_' . $sub_sub_term->term_id);
                        $subsub_family_gallary =  get_field('pf_subsub_fam_tch_feturs', 'products-family_' . $sub_sub_term->term_id);
                        
                    ?>

                       <!-- single accordion start  -->
                        <div class="single-accordion">
                            <div class="accordion-header">
                                <div class="left-box">
                                    <div class="number"><?php echo $index_number ; ?></div>
                                    <div class="text"><?php echo esc_attr($sub_sub_term->name); ?></div>
                                </div>
                                <div class="right-box">
                                    <span class="accordion-text-closed"><strong>view</strong> <span class="hide_mobile">code and download </span></span>
                                    <span class="accordion-text-open" style="display: none;">Close</span>
                                </div>
                            </div>
                            <div class="accordion-content">
                                <div class="technical-feature-bg">
                                    <div class="technical-feature">Technical features</div>
                                    <ul>
                                       <?php foreach($subsub_family_gallary as $subsub_family_img){ ?>
                                        <li>
                                            <img src="<?php echo $subsub_family_img ; ?>" alt="technical features">
                                        </li>
                                      <?php } ?>
                                    </ul>
                                </div>
                                <div class="accordion-table-wrapper ">
                                    <div class="accordion-table">
                                        <div class="table-responsive">
                                            <table class="table">

                                            <?php 
                                               $args = array(
                                                    'post_type'      => 'products',
                                                    'posts_per_page' => -1,
                                                    'tax_query'      => array(
                                                        array(
                                                            'taxonomy' => 'products-family',
                                                            'field'    => 'term_id',
                                                            'terms'    => $sub_sub_term->term_id,   // your selected taxonomy ID
                                                        )
                                                    ),
                                                    'orderby' => 'menu_order',
                                                    'order'   => 'ASC',
                                                );   
                                                $products = new WP_Query($args);

                                                $unique_watts        = [];   
                                                $unique_cct          = [];
                                                $unique_beam_angle   = [];
                                                $unique_lumens       = [];
                                                $unique_finish       = [];
                                                $unique_dimming      = [];

                                                if ($products->have_posts()) :
                                            ?>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Product code</th>
                                                        <th scope="col">watt</th>
                                                        <th scope="col">light source</th>
                                                        <th scope="col">Beam angle</th>
                                                        <th scope="col">LUMENS</th>
                                                        <th scope="col">finish </th>
                                                        <th scope="col">Dimming</th>
                                                        <th scope="col"> </th>
                                                        <th scope="col"> </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="show_product_table">

                                                <?php 
                                                    while ($products->have_posts()) : $products->the_post();

                                                        // ACF FIELDS
                                                        $title         = get_field('pro_title');
                                                        $wattage       = get_field('pro_wattage');
                                                        $cct           = get_field('pro_cct');
                                                        $beam_angle    = get_field('pro_beam_angle');
                                                        $lumens        = get_field('pro_lumens');
                                                        $finish        = get_field('pro_finish');
                                                        $finish_color  = get_field('pro_finish_color');
                                                        $dimming       = get_field('pro_dimming'); 

                                                        // wattage  
                                                        if (!empty($wattage)) {
                                                            if (!is_array($wattage)) {
                                                                $unique_watts[] = $wattage;
                                                            } else {
                                                                foreach ($wattage as $wat) {
                                                                    $unique_watts[] = $wat;
                                                                }
                                                            }
                                                        }
                                                        
                                                        // unique_cct  
                                                         if (!empty($cct)) {
                                                            if (!is_array($cct)) {
                                                                $unique_cct[] = $cct;
                                                            } else {
                                                                foreach ($cct as $ccts) {
                                                                    $unique_cct[] = $ccts;
                                                                }
                                                            }
                                                        }

                                                         // unique_beam_angle  
                                                         if (!empty($beam_angle)) {
                                                            if (!is_array($beam_angle)) {
                                                                $unique_beam_angle[] = $beam_angle;
                                                            } else {
                                                                foreach ($beam_angle as $beam_angles) {
                                                                    $unique_beam_angle[] = $beam_angles;
                                                                }
                                                            }
                                                        }


                                                        //    $unique_lumens   
                                                         if (!empty($lumens)) {
                                                            if (!is_array($lumens)) {
                                                                $unique_lumens[] = $lumens;
                                                            } else {
                                                                foreach ($lumens as $lumen) {
                                                                    $unique_lumens[] = $lumen;
                                                                }
                                                            }
                                                        }
 

                                                        // $unique_finish   
                                                        if (!empty($finish)) {
                                                            if (!is_array($finish)) {
                                                                $unique_finish[] = $finish;
                                                            } else {
                                                                foreach ($finish as $finish) {
                                                                    $unique_finish[] = $finish;
                                                                }
                                                            }
                                                        }


                                                         // $unique_dimming   
                                                        if (!empty($dimming)) {
                                                            if (!is_array($dimming)) {
                                                                $unique_dimming[] = $dimming;
                                                            } else {
                                                                foreach ($dimming as $dimming) {
                                                                    $unique_dimming[] = $dimming;
                                                                }
                                                            }
                                                        }



                                                        // $control      = get_field('pro_control');
                                                        // $image        = get_field('pro_image');
                                                        // $ltd_file     = get_field('pro_download_ltd');
                                                        // $instruction  = get_field('pro_download_instruction');
                                                        // $revit_file   = get_field('pro_download_revit');
                                                ?>

                                                      <tr>
                                                        <td><a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a>  </td> 
                                                        <td> <?php echo $wattage; ?></td> 
                                                        <td> <?php echo $cct; ?> </td> 
                                                        <td> <?php echo $beam_angle; ?> </td>
                                                        <td> <?php echo $lumens; ?> </td>
                                                        <td>
                                                            <span class="circle" style="background: <?php echo $finish_color; ?>;"></span> 
                                                            <?php echo $finish; ?> 
                                                        </td> 
                                                        <td><?php echo $dimming ; ?></td>
                                                        <td> 
                                                            <button class="btn accordion-data-btn">
                                                            <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/material-symbols-light_menu-open.svg"
                                                                alt="t6.png">
                                                            </button>
                                                        </td>
                                                        <td> 
                                                            <a href="<?php the_permalink(); ?>">
                                                              <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_arrow-right-circle.svg" alt="t7.png">
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9">
                                                            <div class="td-inner-box">

                                                                <?php 
                                                                  $ltd_file = get_field('pro_dwnld_ltd_files');
                                                                  $instructions = get_field('pro_dwnld_instructions');
                                                                  $revit_file = get_field('pro_dwnld_revit');
                                                                ?>
                                                                <a href="<?php echo $ltd_file; ?>" download class="single-download">
                                                                    <span class="download-text">ltd file</span>
                                                                    <span class="download-icon">
                                                                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2025/12/ion_download-sharp.svg"
                                                                            alt="download-icon.png">

                                                                    </span>
                                                                </a>
                                                                <a href="<?php echo $instructions; ?>" download class="single-download">
                                                                    <span class="download-text">instructions</span>
                                                                    <span class="download-icon">
                                                                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2025/12/ion_download-sharp.svg"
                                                                            alt="download-icon.png">

                                                                    </span>
                                                                </a>
                                                                <a href="<?php echo $revit_file; ?>" download class="single-download">
                                                                    <span class="download-text">revit file</span>
                                                                    <span class="download-icon">
                                                                        <img src="<?php echo home_url(); ?>/wp-content/uploads/2025/12/ion_download-sharp.svg"
                                                                            alt="download-icon.png"> 
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                <?php
                                                    endwhile;

                                                    $unique_watts = array_unique($unique_watts);
                                                    sort($unique_watts); 

                                                    $unique_cct = array_unique($unique_cct);
                                                    sort($unique_cct); 

                                                    $unique_beam_angle = array_unique($unique_beam_angle);
                                                    sort($unique_beam_angle); 

                                                     $unique_lumens = array_unique($unique_lumens);
                                                    sort($unique_lumens); 

                                                    $unique_finish = array_unique($unique_finish);
                                                    sort($unique_finish); 

                                                    $unique_dimming = array_unique($unique_dimming);
                                                    sort($unique_dimming); 

                                                    wp_reset_postdata();
                                                    endif;
                                                ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="accordion-filter filter_category_item">
                                        <div class="filter-title">
                                            <div class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16"
                                                    viewBox="0 0 17 16" fill="none">
                                                    <path
                                                        d="M0 1.0056C0 0.738895 0.0790175 0.483118 0.21967 0.294532C0.360322 0.105946 0.551088 0 0.75 0H15.75C15.9489 0 16.1397 0.105946 16.2803 0.294532C16.421 0.483118 16.5 0.738895 16.5 1.0056C16.5 1.2723 16.421 1.52807 16.2803 1.71666C16.1397 1.90524 15.9489 2.01119 15.75 2.01119H0.75C0.551088 2.01119 0.360322 1.90524 0.21967 1.71666C0.0790175 1.52807 0 1.2723 0 1.0056ZM2.5 7.70956C2.5 7.44286 2.57902 7.18709 2.71967 6.9985C2.86032 6.80991 3.05109 6.70397 3.25 6.70397H13.25C13.4489 6.70397 13.6397 6.80991 13.7803 6.9985C13.921 7.18709 14 7.44286 14 7.70956C14 7.97626 13.921 8.23204 13.7803 8.42062C13.6397 8.60921 13.4489 8.71516 13.25 8.71516H3.25C3.05109 8.71516 2.86032 8.60921 2.71967 8.42062C2.57902 8.23204 2.5 7.97626 2.5 7.70956ZM5.5 14.4135C5.5 14.1468 5.57902 13.8911 5.71967 13.7025C5.86032 13.5139 6.05109 13.4079 6.25 13.4079H10.25C10.4489 13.4079 10.6397 13.5139 10.7803 13.7025C10.921 13.8911 11 14.1468 11 14.4135C11 14.6802 10.921 14.936 10.7803 15.1246C10.6397 15.3132 10.4489 15.4191 10.25 15.4191H6.25C6.05109 15.4191 5.86032 15.3132 5.71967 15.1246C5.57902 14.936 5.5 14.6802 5.5 14.4135Z"
                                                        fill="black" fill-opacity="0.5" />
                                                </svg>
                                            </div>
                                            <div class="text">FILTER</div>
                                        </div>
                                        <div class="filter-accrodion-parent">
                                            <div class="single-filter-accordion">
                                                <?php if (!empty($unique_watts)) : ?>
                                                    <div class="filter-acc-title">Watt</div>
                                                    <div class="filter-acc-content">

                                                        <?php foreach ($unique_watts as $watt) : 
                                                            $id = 'watt-' . $watt;
                                                        ?>
                                                            <div class="form-check">
                                                                <label class="form-check-label" for="<?php echo $id; ?>">
                                                                    <?php echo esc_html($watt); ?>
                                                                </label>
                                                                <input class="form-check-input filter_input " 
                                                                    type="checkbox" 
                                                                    value="<?php echo esc_attr($watt); ?>" 
                                                                    id="<?php echo $id; ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                                
                                            </div>

                                            <div class="single-filter-accordion">
                                                 <?php if (!empty($unique_cct)) : ?>
                                                    <div class="filter-acc-title">Light Source</div>
                                                    <div class="filter-acc-content">

                                                        <?php foreach ($unique_cct as $cct) : 
                                                            $id = 'cct-' . $cct;
                                                        ?>
                                                            <div class="form-check">
                                                                <label class="form-check-label" for="<?php echo $id; ?>">
                                                                    <?php echo esc_html($cct); ?>
                                                                </label>
                                                                <input class="form-check-input filter_input" 
                                                                    type="checkbox" 
                                                                    value="<?php echo esc_attr($cct); ?>" 
                                                                    id="<?php echo $id; ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                            

                                            <div class="single-filter-accordion">   
                                                <?php if (!empty($unique_beam_angle)) : ?>
                                                    <div class="filter-acc-title">Beam Angle</div>
                                                    <div class="filter-acc-content">
                                                        <?php foreach ($unique_beam_angle as $unique_beam) : 
                                                            $id = 'unique_beam-' . $unique_beam;
                                                         ?> 
                                                            <div class="form-check">
                                                                <label class="form-check-label" for="<?php echo $id; ?>">
                                                                    <?php echo esc_html($unique_beam); ?>
                                                                </label>
                                                                <input class="form-check-input filter_input" 
                                                                    type="checkbox" 
                                                                    value="<?php echo esc_attr($unique_beam); ?>" 
                                                                    id="<?php echo $id; ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                            <div class="single-filter-accordion">
                                                
                                                <?php if (!empty($unique_lumens)) : ?>
                                                    <div class="filter-acc-title">Lumens</div>
                                                    <div class="filter-acc-content">
                                                        <?php foreach ($unique_lumens as $unique_lumen) : 
                                                            $id = 'unique_lumens-' . $unique_lumen;
                                                         ?> 
                                                            <div class="form-check">
                                                                <label class="form-check-label" for="<?php echo $id; ?>">
                                                                    <?php echo esc_html($unique_lumen); ?>
                                                                </label>
                                                                <input class="form-check-input filter_input" 
                                                                    type="checkbox" 
                                                                    value="<?php echo esc_attr($unique_lumen); ?>" 
                                                                    id="<?php echo $id; ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="single-filter-accordion">
                                                

                                                 <?php if (!empty($unique_finish)) : ?>
                                                    <div class="filter-acc-title">Finish</div>
                                                    <div class="filter-acc-content">
                                                        <?php foreach ($unique_finish as $unique_fin) : 
                                                            $id = 'unique_finish-' . $unique_fin;
                                                         ?> 
                                                            <div class="form-check">
                                                                <label class="form-check-label" for="<?php echo $id; ?>">
                                                                    <?php echo esc_html($unique_fin); ?>
                                                                </label>
                                                                <input class="form-check-input filter_input" 
                                                                    type="checkbox" 
                                                                    value="<?php echo esc_attr($unique_fin); ?>" 
                                                                    id="<?php echo $id; ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                            <div class="single-filter-accordion">

                                               <?php if (!empty($unique_dimming)) : ?>
                                                    <div class="filter-acc-title">Dimming</div>
                                                    <div class="filter-acc-content">
                                                        <?php foreach ($unique_dimming as $unique_dimm) : 
                                                            $id = 'unique_dimming-' . $unique_dimm;
                                                         ?> 
                                                            <div class="form-check">
                                                                <label class="form-check-label" for="<?php echo $id; ?>">
                                                                    <?php echo esc_html($unique_dimm); ?>
                                                                </label>
                                                                <input class="form-check-input filter_input" 
                                                                    type="checkbox" 
                                                                    value="<?php echo esc_attr($unique_dimm); ?>" 
                                                                    id="<?php echo $id; ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>



                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- single accordion end  -->

                  

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
