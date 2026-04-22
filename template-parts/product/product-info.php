<?php
/**
 * Template part for displaying product info/specifications section
 *
 * Required variables from parent template:
 * - $current_term
 * - $subsub_family_gallary
 * - $site_plachlder_img
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}


$terms = get_the_terms(get_the_ID(), 'product-family');

$parent_term_id = null;

if ($terms && !is_wp_error($terms)) {
    // Sort by term_id (ascending)
    usort($terms, function($a, $b) {
        return $a->term_id - $b->term_id;
    });
    
    // Get the last term after sorting
    $last_term = end($terms);
    $parent_term_id = $last_term->term_id;
}
$subsub_family_gallary = $parent_term_id ? get_field('tax_sub_family_features', 'product-family_' . $parent_term_id) : null;

?>

<section class="pd-single-data">
    <div class="container">
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
                            $iprating      =  get_field('pro_iprating');
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
                            $pro_mesr_img = get_field('pf_subfam_tech_drawing', 'product-family_' . $current_term->term_id);
                            if (empty($pro_mesr_img) && !empty($current_term->parent)) {
                                $pro_mesr_img = get_field('pf_subfam_tech_drawing', 'product-family_' . $current_term->parent);
                            }

                            // print_r($ancestors) ;
                    ?>
                        <?php if( !empty($wattage) ) : ?>
                        <li>
                            <span>Wattage</span>
                            <p> <?php echo $wattage; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($cct) ) : ?>
                        <li>
                            <span>CCT</span>
                            <p> <?php echo $cct; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($beam_angle) ) : ?>
                        <li>
                            <span>Beam Angle</span>
                            <p> <?php echo $beam_angle; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($lumens) ) : ?>
                        <li>
                            <span>Lumens</span>
                            <p> <?php echo $lumens; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($finish_color) ) : 
    $term = get_term($finish_color, 'finish-color');
    
    if ($term && !is_wp_error($term)) :
        $term_title = $term->name;
        $term_icon = get_field('tax_finish_color__img', 'finish-color_' . $finish_color);
?>
    <li class="finish_li">
        <span>Finish</span>
        <p>
            <?php if ($term_icon) : ?>
                <img style="    width: 16px;
    height: 16px;" src="<?php echo esc_url($term_icon); ?>" alt="<?php echo esc_attr($term_title); ?>">
            <?php endif; ?>
            <?php echo esc_html($term_title); ?>
        </p>
    </li>
<?php 
    endif;
endif; ?>

                        <?php if( !empty($iprating) ) : ?>
                        <li>
                            <span>IP Rating </span>
                            <p> <?php echo $iprating; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($ikrating) ) : ?>
                        <li>
                            <span> IK Rating </span>
                            <p> <?php echo $ikrating; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_material) ) : ?>
                        <li>
                            <span> Material </span>
                            <p> <?php echo $pro_material; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_coating) ) : ?>
                        <li>
                            <span> Coating </span>
                            <p> <?php echo $pro_coating; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_light_source) ) : ?>
                        <li>
                            <span> Light source </span>
                            <p> <?php echo $pro_light_source; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_screws) ) : ?>
                        <li>
                            <span> Screws </span>
                            <p> <?php echo $pro_screws; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_transformer) ) : ?>
                        <li>
                            <span> Transformer </span>
                            <p> <?php echo $pro_transformer; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_gasket) ) : ?>
                        <li>
                            <span> Gasket </span>
                            <p> <?php echo $pro_gasket; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_glass) ) : ?>
                        <li>
                            <span> Glass </span>
                            <p> <?php echo $pro_glass; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_cable_gland) ) : ?>
                        <li>
                            <span> Cable gland </span>
                            <p> <?php echo $pro_cable_gland; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_pwr_cble) ) : ?>
                        <li>
                            <span> Power cable </span>
                            <p><?php echo $pro_pwr_cble; ?> </p>
                        </li>
                        <?php endif; ?>

                        <?php if( !empty($pro_grs_weight) ) : ?>
                        <li>
                            <span> Gross weight </span>
                            <p> <?php echo $pro_grs_weight; ?></p>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php 
                  $pd_alavlbl = get_field('pd_alavlbl_select_product');
                if( !empty($subsub_family_gallary) ) : ?>
                <div class="pd-single-data-icns <?php echo empty($pd_alavlbl) ? 'mb-5' : ''; ?>">
                    <?php
                            foreach ( $subsub_family_gallary as $term_id ) {
                            $image = get_field( 'tax_featured__icon', 'features_' . $term_id );
                            if ( $image ) {
                            $image_alt = is_array($image) && !empty($image['alt']) ? $image['alt'] : 'technical features';
                            ?>
                    <div class="icon_item">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                    </div>
                    <?php
                            }
                        } ?>
                </div>
                <?php endif; ?>

            
            <!-- sub gallary  -->
            <?php
                // Get the current product ID
                global $post;
                $product_id = isset($post->ID) ? $post->ID : 0;
                
                // Get individual image fields
                $prod_gallery_11 = get_field('prod_gallery_11', $product_id);
                $prod_gallery_12 = get_field('prod_gallery_12', $product_id);
                $prod_gallery_13 = get_field('prod_gallery_13', $product_id);
                $prod_gallery_14 = get_field('prod_gallery_14', $product_id);
                $prod_gallery_15 = get_field('prod_gallery_15', $product_id);
                $prod_gallery_16 = get_field('prod_gallery_16', $product_id);
                $prod_gallery_17 = get_field('prod_gallery_17', $product_id);
                $prod_gallery_18 = get_field('prod_gallery_18', $product_id);
                $prod_gallery_19 = get_field('prod_gallery_19', $product_id);
                $prod_gallery_20 = get_field('prod_gallery_20', $product_id);
                
                
                // Combine all images into an array
                $sub_gallery_images = array();
                if (!empty($prod_gallery_11)) $sub_gallery_images[] = $prod_gallery_11;
                if (!empty($prod_gallery_12)) $sub_gallery_images[] = $prod_gallery_12;
                if (!empty($prod_gallery_13)) $sub_gallery_images[] = $prod_gallery_13;
                if (!empty($prod_gallery_14)) $sub_gallery_images[] = $prod_gallery_14;
                if (!empty($prod_gallery_15)) $sub_gallery_images[] = $prod_gallery_15;
                if (!empty($prod_gallery_16)) $sub_gallery_images[] = $prod_gallery_16;
                if (!empty($prod_gallery_17)) $sub_gallery_images[] = $prod_gallery_17;
                if (!empty($prod_gallery_18)) $sub_gallery_images[] = $prod_gallery_18;
                if (!empty($prod_gallery_19)) $sub_gallery_images[] = $prod_gallery_19;
                if (!empty($prod_gallery_20)) $sub_gallery_images[] = $prod_gallery_20;
                
              ?>
                
                
                <?php if (!empty($sub_gallery_images)) : ?>
                <div class="pd-single-data-sbglry">
                    <?php foreach($sub_gallery_images as $sub_gallery_img){ ?>
                    <div class="single-sf">
                        <a href="<?php echo $sub_gallery_img; ?>" 
                           data-fancybox="product-gallery"
                           data-caption="<?php echo get_the_title($product_id); ?>">
                          <img src="<?php echo $sub_gallery_img; ?>" alt="<?php echo get_the_title($product_id); ?>" />
                        </a>
                    </div>
                    <?php } ?>
                </div>
                 <script>
                    Fancybox.bind('[data-fancybox="product-gallery"]', { });
                </script>
                <?php endif; ?>


            </div>

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">

                <?php  if(!empty($pro_mesr_img) ) :  ?>
                <div class="right-image-box single-techdraw">
                    <?php if($pro_mesr_img){ ?>
                    <img src="<?php echo $pro_mesr_img ;?>" alt="Wall Mounting 2" />
                    <?php  }else{ ?>
                    <img src="<?php echo $site_plachlder_img ;?>" alt="Wall Mounting 2" />
                    <?php }?>
                </div>
                <?php endif; ?>
            </div>
                <?php 
                
                $pro_remote_drv_slctn = get_field('pro_remote_drv_slctn') ; 
                if(!empty($pro_remote_drv_slctn) ) :  ?>
                <!-- driver section  -->

            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
			</div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
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

                                            // Skip row if all values are only "-" or "_"
                                            $skip_values = ['-', '_'];
                                            $all_empty = in_array(trim($pro_remote_meanwell), $skip_values, true)
                                                      && in_array(trim($pro_remote_lpv), $skip_values, true)
                                                      && in_array(trim($pro_remote_volt), $skip_values, true)
                                                      && in_array(trim($pro_remote_watt), $skip_values, true)
                                                      && in_array(trim($pro_remote_ip), $skip_values, true)
                                                      && in_array(trim($pro_remote_min_max), $skip_values, true);

                                            if($all_empty) continue;
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
                <?php endif; ?>
        </div>
    </div>
</section>