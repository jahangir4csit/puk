<?php
/**
 * Family Accordion Component
 * 
 * @var array $args {
 *     @var WP_Term $current_term
 *     @var int     $term_id
 * }
 */
$current_term = $args['current_term'];
$term_id      = $current_term->term_id;
$taxonomy     = $args['taxonomy'];

// Note: '_' placeholder terms are now allowed to display

// Get only child terms of THIS subcategory
$child_terms = get_terms(array(
    'taxonomy'   => 'product-family',
    'parent'     => $term_id,
    'hide_empty' => false,
    'orderby'    => 'term_id',
    'order'      => 'ASC'
));

if ( ! empty( $child_terms ) ) {
    // Put current term at the beginning
    array_unshift( $child_terms, $current_term );
} else {
    // No child terms → only current term
    $child_terms = array( $current_term );
}

// Filter valid terms: skip '_' and terms with zero count
$valid_terms = puk_filter_valid_terms( $child_terms, false, false );

// If no valid terms, skip the entire section
if ( empty( $valid_terms ) ) {
    return;
}

?>
<section class="pf-accordion-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="custom-accordion-wrapper">
                <?php 
                
                
                    foreach ($valid_terms as $child) : 

                        $key_number = $child->term_id ; 
                        // $index_number = get_field('family_code', $child);
                        // $index_number = get_product_family_code( $child, 2 );
                        $index_number = tax_product_code( $child );
                        
                        if($child->count > 0 ){
                        $parent_term_id = $current_term->parent;
                        $parent_term    = get_term( $parent_term_id, 'product-family' );

                        // If current_term is '_' placeholder, bubble up: use parent as effective current
                        if ( $current_term->name === '_' && $parent_term && ! is_wp_error( $parent_term ) ) {
                            $effective_current = $parent_term;
                            $effective_parent  = $parent_term->parent
                                ? get_term( $parent_term->parent, 'product-family' )
                                : null;
                        } else {
                            $effective_current = $current_term;
                            $effective_parent  = $parent_term;
                        }

                        // Show: family parent + sub-family name (skip root; hide '_')
                        $name_parts = array();
                        if ( $effective_parent && ! is_wp_error( $effective_parent ) && $effective_parent->name !== '_' ) {
                            $name_parts[] = $effective_parent->name;
                        }
                        if ( $effective_current->name !== '_' ) {
                            $name_parts[] = $effective_current->name;
                        }
                        if ( $child->name !== '_' && $child->name !== $effective_current->name ) {
                            $name_parts[] = $child->name;
                        }
                    
                        $accordion_display_name = implode( ' ', $name_parts );

                    ?>
                        <!-- single accordion start  -->
                        <div class="single-accordion">
                            <div class="accordion-header">
                                <div class="left-box">
                                    <div class="number"> <?php echo $index_number ; ?> </div>
                                    <div class="text">   <?php echo esc_html( $accordion_display_name ); ?></div>
                                </div>
                                <div class="right-box">
                                    <span class="accordion-text-closed"><strong>view</strong> <span
                                            class="hide_mobile">code and download </span></span>
                                    <span class="accordion-text-open" style="display: none;">Close</span>
                                </div>
                            </div>
                            <div class="accordion-content">
                                <div class="technical-feature-bg">
                                    <div class="technical-feature">Technical features</div>   
                                    <?php 

                                      $child_term_id = $child->term_id ; 
                                      $tax_sub_family_features = get_field('tax_sub_family_features', 'features_' . $child_term_id);

                                    ?>
<?php 
  if ($tax_sub_family_features && is_array($tax_sub_family_features)) {
    echo '<ul>';
    foreach ($tax_sub_family_features as $term_id) {
        $icon_image = get_field('tax_featured__icon', 'features_' . $term_id);
        if ($icon_image && $term && !is_wp_error($term)) {
            echo '<li>';
            echo '<img src="' . esc_url($icon_image) . '" alt="' . esc_attr($term->name) . '">';
            echo '</li>';
        }
    }
    
    echo '</ul>';
}
                                       ?>
                                                
                

                                </div>
                                <div class="accordion-table-wrapper ">
                                    <div class="accordion-table">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <?php 
                                                $args_query = array(
                                                    'post_type'      => 'product',
                                                    'posts_per_page' => -1,
                                                    'tax_query'      => array(
                                                        array(
                                                            'taxonomy' => 'product-family',
                                                            'field'    => 'term_id',
                                                            'terms'    => $child->term_id,
                                                        )
                                                    ),
                                                    'orderby' => 'menu_order',
                                                    'order'   => 'ASC',
                                                );   
                                                $products = new WP_Query($args_query);

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
                                                <tbody class="show_product_table_<?php echo $key_number; ?>">
                                                    <?php
                                                      while ($products->have_posts()) : $products->the_post();

                                                        $product_id = get_the_ID() ;
                                                        // ACF FIELDS
                                                        $title         = get_field('pro_title');
                                                        $wattage       = get_field('pro_wattage');
                                                        $cct           = get_field('pro_cct');
                                                        $beam_angle    = get_field('pro_beam_angle');
                                                        $lumens        = get_field('pro_lumens');
                                                        $dimming_id    = get_field('pro_dimming');
                                                        if( !empty($dimming_id) ) {
                                                            $dimming_term = get_term($dimming_id, 'features');

                                                            if( !is_wp_error($dimming_term) && $dimming_term ) {
                                                                $dimming = $dimming_term->name;
                                                            }
                                                        }else{
                                                                $dimming = '' ;
                                                        }

                                                        $product_sku   = get_field('prod__sku');

                                                        $color_id = get_post_meta($product_id, 'pro_finish_color', true);
                                                        $color_term = get_term( $color_id, 'finish-color' );
                                                        $finish_color = $color_term->name ;

                                                        $color_img = get_field('tax_finish_color__img','finish-color_' . $color_id );

                                                        // Normalise scalar values for data attributes
                                                        $row_watt   = is_array($wattage)    ? implode(',', $wattage)    : (string) $wattage;
                                                        $row_cct    = is_array($cct)         ? implode(',', $cct)         : (string) $cct;
                                                        $row_beam   = is_array($beam_angle)  ? implode(',', $beam_angle)  : (string) $beam_angle;
                                                        $row_lumens = is_array($lumens)      ? implode(',', $lumens)      : (string) $lumens;

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

                                                        // unique_lumens   
                                                        if (!empty($lumens)) {
                                                            if (!is_array($lumens)) {
                                                                $unique_lumens[] = $lumens;
                                                            } else {
                                                                foreach ($lumens as $lumen) {
                                                                    $unique_lumens[] = $lumen;
                                                                }
                                                            }
                                                        }

                                                        // unique_finish   
                                                        if (!empty($color_id)) {
                                                            if (!is_array($color_id)) {
                                                                $unique_finish[] = $color_id;
                                                            } else {
                                                                foreach ($color_id as $fin) {
                                                                    $unique_finish[] = $fin;
                                                                }
                                                            }
                                                        }

                                                        // unique_dimming   
                                                        if (!empty($dimming_id)) {
                                                            if (!is_array($dimming_id)) {
                                                                $unique_dimming[] = $dimming_id;
                                                            } else {
                                                                foreach ($dimming_id as $dim) {
                                                                    $unique_dimming[] = $dim;
                                                                }
                                                            }
                                                        }
                                                        
                                                    ?>
                                                        <tr class="product-row"
                                                            data-watt="<?php echo esc_attr($row_watt); ?>"
                                                            data-cct="<?php echo esc_attr($row_cct); ?>"
                                                            data-beam="<?php echo esc_attr($row_beam); ?>"
                                                            data-lumens="<?php echo esc_attr($row_lumens); ?>"
                                                            data-finish="<?php echo esc_attr($color_id); ?>"
                                                            data-dimming="<?php echo esc_attr($dimming_id); ?>">
                                                            <td><a href="<?php the_permalink(); ?>"> <?php echo $product_sku; ?> </a> </td>
                                                            <td> <?php echo $wattage; ?></td>
                                                            <td> <?php echo $cct; ?> </td>
                                                            <td> <?php echo $beam_angle; ?> </td>
                                                            <td> <?php echo $lumens; ?> </td>
                                                            <td>
                                                                <?php if($color_img){ ?>
                                                                    <span><img src="<?php echo $color_img; ?>" alt="finish-color"></span>
                                                                <?php }else{ ?>
                                                                   <span> </span>
                                                                 <?php  }?>
                                                                   <?php echo $finish_color; ?>
                                                            </td>
                                                            <td><?php echo $dimming ; ?></td>
                                                            <td>
                                                                <button class="btn accordion-data-btn"></button>
                                                            </td>
                                                            <td>
                                                                <a href="<?php the_permalink(); ?>">
                                                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_arrow-right-circle.svg" alt="t7.png">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr class="download-row">
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
                                                                            <img src="<?php echo home_url(); ?>/wp-content/uploads/2025/12/ion_download-sharp.svg" alt="download-icon.png">
                                                                        </span>
                                                                    </a>
                                                                    <a href="<?php echo $instructions; ?>" download class="single-download">
                                                                        <span class="download-text">instructions</span>
                                                                        <span class="download-icon">
                                                                            <img src="<?php echo home_url(); ?>/wp-content/uploads/2025/12/ion_download-sharp.svg" alt="download-icon.png">
                                                                        </span>
                                                                    </a>
                                                                    <a href="<?php echo $revit_file; ?>" download class="single-download">
                                                                        <span class="download-text">revit file</span>
                                                                        <span class="download-icon">
                                                                            <img src="<?php echo home_url(); ?>/wp-content/uploads/2025/12/ion_download-sharp.svg" alt="download-icon.png">
                                                                        </span>
                                                                    </a>

                                                                    <a href="#" class="single-download download-data-sheet datasheet-pdf-btn" data-product-id="<?php echo get_the_ID(); ?>">
                                                                        <span class="download-text">Data Sheet</span>
                                                                        <span class="download-icon">
                                                                            <img class="pdf-icon-default" src="<?php echo home_url(); ?>/wp-content/uploads/2025/12/ion_download-sharp.svg" alt="download-icon.png">
                                                                            <span class="pdf-loading" style="display:none;">
                                                                                <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                                    <style>.acc-spinner{animation:rotate 1s linear infinite;transform-origin:center;}@keyframes rotate{100%{transform:rotate(360deg);}}</style>
                                                                                    <circle class="acc-spinner" cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="31.4 31.4" stroke-linecap="round"/>
                                                                                </svg>
                                                                            </span>
                                                                        </span>
                                                                    </a>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile;
                                                    wp_reset_postdata();
                                                    endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="accordion-filter filter_category_item_<?php echo $key_number; ?>">
                                        <div class="filter-title">
                                            <div class="icon">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon/vector.svg">
                                            </div>
                                            <div class="text">FILTER</div>
                                        </div>
                                        <div class="filter-accrodion-parent">
                                            <div class="single-filter-accordion" data-filter-type="watt">
                                                <?php

                                                $unique_watts = array_unique($unique_watts);
                                                sort($unique_watts);
                                                if (!empty($unique_watts)) : ?>
                                                <div class="filter-acc-title"><span class="filter-acc-title-text">Watt</span><span class="filter-acc-arrow"></span></div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_watts as $watt) : 
                                                            $id = 'watt-' . $watt;
                                                        ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($watt); ?>
                                                        </label>
                                                        <input class="form-check-input filter_input " type="checkbox" value="<?php echo esc_attr($watt); ?>" id="<?php echo $id; ?>">
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="single-filter-accordion" data-filter-type="cct">
                                                <?php
                                                $unique_cct = array_unique($unique_cct);
                                                sort($unique_cct);
                                                if (!empty($unique_cct)) : ?>
                                                <div class="filter-acc-title"><span class="filter-acc-title-text">Light Source</span><span class="filter-acc-arrow"></span></div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_cct as $cct_val) : 
                                                            $id = 'cct-' . $cct_val;
                                                        ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($cct_val); ?>
                                                        </label>
                                                        <input class="form-check-input filter_input" type="checkbox" value="<?php echo esc_attr($cct_val); ?>" id="<?php echo $id; ?>">
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="single-filter-accordion" data-filter-type="beam">
                                                <?php
                                                $unique_beam_angle = array_unique($unique_beam_angle);
                                                sort($unique_beam_angle);
                                                if (!empty($unique_beam_angle)) : ?>
                                                <div class="filter-acc-title"><span class="filter-acc-title-text">Beam Angle</span><span class="filter-acc-arrow"></span></div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_beam_angle as $unique_beam) : 
                                                            $id = 'unique_beam-' . $unique_beam;
                                                         ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($unique_beam); ?>
                                                        </label>
                                                        <input class="form-check-input filter_input" type="checkbox" value="<?php echo esc_attr($unique_beam); ?>" id="<?php echo $id; ?>">
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="single-filter-accordion" data-filter-type="lumens">
                                                <?php
                                                $unique_lumens = array_unique($unique_lumens);
                                                sort($unique_lumens);
                                                if (!empty($unique_lumens)) : ?>
                                                <div class="filter-acc-title"><span class="filter-acc-title-text">Lumens</span><span class="filter-acc-arrow"></span></div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_lumens as $unique_lumen) : 
                                                            $id = 'unique_lumens-' . $unique_lumen;
                                                         ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($unique_lumen); ?>
                                                        </label>
                                                        <input class="form-check-input filter_input" type="checkbox" value="<?php echo esc_attr($unique_lumen); ?>" id="<?php echo $id; ?>">
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="single-filter-accordion" data-filter-type="finish">
                                                <?php
                                                $unique_finish = array_unique($unique_finish);
                                                sort($unique_finish);
                                                if (!empty($unique_finish)) : ?>
                                                <div class="filter-acc-title"><span class="filter-acc-title-text">Finish</span><span class="filter-acc-arrow"></span></div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_finish as $unique_fin) :
                                                            $id = 'unique_finish-' . $unique_fin;
                                                            $fin_term = get_term( $unique_fin, 'finish-color' );
                                                            $fin_label = ( $fin_term && ! is_wp_error( $fin_term ) ) ? $fin_term->name : $unique_fin;
                                                         ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($fin_label); ?>
                                                        </label>
                                                        <input class="form-check-input filter_input" type="checkbox" value="<?php echo esc_attr($unique_fin); ?>" id="<?php echo $id; ?>">
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="single-filter-accordion" data-filter-type="dimming">
                                                <?php
                                                $unique_dimming = array_unique($unique_dimming);
                                                sort($unique_dimming);
                                                if (!empty($unique_dimming)) : ?>
                                                <div class="filter-acc-title"><span class="filter-acc-title-text">Dimming</span><span class="filter-acc-arrow"></span></div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_dimming as $unique_dimm) :
                                                            $id = 'unique_dimming-' . $unique_dimm;
                                                            $dimm_term = get_term( $unique_dimm, 'features' );
                                                            $dimm_label = ( $dimm_term && ! is_wp_error( $dimm_term ) ) ? $dimm_term->name : $unique_dimm;
                                                         ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($dimm_label); ?>
                                                        </label>
                                                        <input class="form-check-input filter_input" type="checkbox" value="<?php echo esc_attr($unique_dimm); ?>" id="<?php echo $id; ?>">
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

                    <?php } endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section><!-- end family accordion -->

