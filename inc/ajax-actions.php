<?php 
add_action('wp_ajax_filter_products_by_metadata', 'filter_products_by_metadata');
add_action('wp_ajax_nopriv_filter_products_by_metadata', 'filter_products_by_metadata');

function filter_products_by_metadata() { 

    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    $watt_val    = [];
    $cct_val     = [];
    $beam_val    = [];
    $lumens_val  = [];
    $finish_val  = [];
    $dimming_val = [];

    foreach ($filters as $item) {
    list($key, $value) = explode('-', $item, 2);
    switch ($key) {

        case 'watt':
            $watt_val[] = $value;
            break;

        case 'cct':
            $cct_val[] = $value;
            break;

        case 'unique_beam':
            $beam_val[] = $value;
            break;

        case 'unique_lumens':
            $lumens_val[] = $value;
            break;

        case 'unique_finish':
            $finish_val[] = $value;
            break;

        case 'unique_dimming':
            $dimming_val[] = $value;
            break;
        }
    }

    // print_r($cct_val) ; 
    // exit ; 

    // If no filter selected ? show all products
   if (empty($filters)) {

        $args = array(
            'post_type'      => 'products',
            'posts_per_page' => -1
        );

    } else {

        // Build clean meta_query
        $meta_query = array('relation' => 'AND');

        if (!empty($watt_val)) {
            $meta_query[] = array(
                'key'     => 'pro_wattage',
                'value'   => $watt_val,
                'compare' => 'IN'
            );
        }

        if (!empty($cct_val)) {
            $meta_query[] = array(
                'key'     => 'pro_cct',
                'value'   => $cct_val,
                'compare' => 'IN'
            );
        }

        if (!empty($beam_val)) {
            $meta_query[] = array(
                'key'     => 'pro_beam_angle',
                'value'   => $beam_val,
                'compare' => 'IN'
            );
        }

        if (!empty($lumens_val)) {
            $meta_query[] = array(
                'key'     => 'pro_lumens',
                'value'   => $lumens_val,
                'compare' => 'IN'
            );
        }

        if (!empty($finish_val)) {
            $meta_query[] = array(
                'key'     => 'pro_finish',
                'value'   => $finish_val,
                'compare' => 'IN'
            );
        }

        if (!empty($dimming_val)) {
            $meta_query[] = array(
                'key'     => 'pro_dimming',
                'value'   => $dimming_val,
                'compare' => 'IN'
            );
        }

        $args = array(
            'post_type'      => 'products',
            'posts_per_page' => -1,
            'meta_query'     => $meta_query
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
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



            ?>

            <!-- product item -->
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
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-family/t6.png"
                        alt="t6.png">
                    </button>
                </td>
                <td> 
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-family/t7.png" alt="t7.png">
                    </a>
                </td>
            </tr>
            <tr style="display: none;">
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
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-family/download-icon.png"
                                    alt="download-icon.png">
                            </span>
                        </a>
                        <a href="<?php echo $instructions; ?>" download class="single-download">
                            <span class="download-text">instructions</span>
                            <span class="download-icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-family/download-icon.png"
                                    alt="download-icon.png">

                            </span>
                        </a>
                        <a href="<?php echo $revit_file; ?>" download class="single-download">
                            <span class="download-text">revit file</span>
                            <span class="download-icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-family/download-icon.png"
                                    alt="download-icon.png">

                            </span>
                        </a>
                    </div>
                </td>
            </tr>

        <?php
        endwhile;
    else :
        echo "<p>No products found</p>";
    endif;

    wp_die();
}
