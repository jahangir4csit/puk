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
$term_id      = $args['term_id'];
?>
<section class="pf-accordion-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="custom-accordion-wrapper">
                    <?php 
                    // Get only child terms of THIS subcategory
                    $child_terms = get_terms(array(
                        'taxonomy'   => 'product-family',
                        'parent'     => $term_id,
                        'hide_empty' => false,
                        'orderby'    => 'term_id',
                        'order'      => 'ASC'
                    ));

                    foreach ($child_terms as $child) : 
                        $key_number = $child->term_id ; 
                        $index_number =  get_field('pf_subsub_fam_indx', 'product-family_' . $child->term_id);
                        $subsub_family_gallary =  get_field('pf_subsub_fam_tch_feturs', 'product-family_' . $child->term_id);
                    ?>
                        <!-- single accordion start  -->
                        <div class="single-accordion">
                            <div class="accordion-header">
                                <div class="left-box">
                                    <div class="number"><?php echo $index_number ; ?></div>
                                    <div class="text"><?php echo esc_attr($child->name); ?></div>
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
                                    <ul>
                                        <?php if (!empty($subsub_family_gallary)) :
                                            foreach($subsub_family_gallary as $subsub_family_img) : ?>
                                            <li>
                                                <img src="<?php echo $subsub_family_img ; ?>" alt="technical features">
                                            </li>
                                            <?php endforeach;
                                        endif; ?>
                                    </ul>
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
                                                        if (!empty($finish)) {
                                                            if (!is_array($finish)) {
                                                                $unique_finish[] = $finish;
                                                            } else {
                                                                foreach ($finish as $fin) {
                                                                    $unique_finish[] = $fin;
                                                                }
                                                            }
                                                        }

                                                        // unique_dimming   
                                                        if (!empty($dimming)) {
                                                            if (!is_array($dimming)) {
                                                                $unique_dimming[] = $dimming;
                                                            } else {
                                                                foreach ($dimming as $dim) {
                                                                    $unique_dimming[] = $dim;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a> </td>
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
                                                                    <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/material-symbols-light_menu-open.svg" alt="t6.png">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <path d="M0 1.0056C0 0.738895 0.0790175 0.483118 0.21967 0.294532C0.360322 0.105946 0.551088 0Syncing term context with components, while keeping specific field logic inside." fill-opacity="0.5" />
                                                </svg>
                                            </div>
                                            <div class="text">FILTER</div>
                                        </div>
                                        <div class="filter-accrodion-parent">
                                            <div class="single-filter-accordion">
                                                <?php 
                                                $unique_watts = array_unique($unique_watts);
                                                sort($unique_watts);
                                                if (!empty($unique_watts)) : ?>
                                                <div class="filter-acc-title">Watt</div>
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

                                            <div class="single-filter-accordion">
                                                <?php 
                                                $unique_cct = array_unique($unique_cct);
                                                sort($unique_cct);
                                                if (!empty($unique_cct)) : ?>
                                                <div class="filter-acc-title">Light Source</div>
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

                                            <div class="single-filter-accordion">
                                                <?php 
                                                $unique_beam_angle = array_unique($unique_beam_angle);
                                                sort($unique_beam_angle);
                                                if (!empty($unique_beam_angle)) : ?>
                                                <div class="filter-acc-title">Beam Angle</div>
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

                                            <div class="single-filter-accordion">
                                                <?php 
                                                $unique_lumens = array_unique($unique_lumens);
                                                sort($unique_lumens);
                                                if (!empty($unique_lumens)) : ?>
                                                <div class="filter-acc-title">Lumens</div>
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

                                            <div class="single-filter-accordion">
                                                <?php 
                                                $unique_finish = array_unique($unique_finish);
                                                sort($unique_finish);
                                                if (!empty($unique_finish)) : ?>
                                                <div class="filter-acc-title">Finish</div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_finish as $unique_fin) : 
                                                            $id = 'unique_finish-' . $unique_fin;
                                                         ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($unique_fin); ?>
                                                        </label>
                                                        <input class="form-check-input filter_input" type="checkbox" value="<?php echo esc_attr($unique_fin); ?>" id="<?php echo $id; ?>">
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="single-filter-accordion">
                                                <?php 
                                                $unique_dimming = array_unique($unique_dimming);
                                                sort($unique_dimming);
                                                if (!empty($unique_dimming)) : ?>
                                                <div class="filter-acc-title">Dimming</div>
                                                <div class="filter-acc-content">
                                                    <?php foreach ($unique_dimming as $unique_dimm) : 
                                                            $id = 'unique_dimming-' . $unique_dimm;
                                                         ?>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="<?php echo $id; ?>">
                                                            <?php echo esc_html($unique_dimm); ?>
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section><!-- end family accordion -->
