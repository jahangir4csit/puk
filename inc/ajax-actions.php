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
            'post_type'      => 'product',
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
            'post_type'      => 'product',
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
        echo "<p class='no_pro_foound'>No products found</p>";
    endif;

    wp_die();
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

function load_more_posts() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) + 1 : 1;
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $post_query = new WP_Query($args);

    if ($post_query->have_posts()) :
        while ($post_query->have_posts()) : $post_query->the_post();
            ?>
            <div class="puk-post-card">
                <a class="puk-post-card__link" href="<?php echo esc_url(get_permalink()); ?>">
                    <div class="puk-post-card__body">
                        <div class="puk-post-card__date">
                            <h5><?php echo get_the_date('d.m.y'); ?></h5>
                        </div>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="puk-post-card__media">
                                <?php 
                                the_post_thumbnail('full', array(
                                    'class' => 'puk-post-card__img',
                                    'alt'   => get_the_title()
                                )); 
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="puk-post-card__content">
                            <h3 class="puk-post-card__title"><?php echo esc_html(get_the_title()); ?></h3>
                            
                            <article class="puk-post-card__excerpt">
                                <?php 
                                if (has_excerpt()) {
                                    the_excerpt();
                                } else {
                                    echo '<p>' . wp_trim_words(get_the_content(), 55, '...') . '</p>';
                                }
                                ?>
                            </article>
                            
                            <div class="puk-post-card__arrow">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.0449 10.3453L17.5805 9.88006L13.6999 6.00291L12.7907 6.93425L15.5608 9.69994L2.58988 9.70572L2.59045 10.9987L15.5608 10.9929L12.7938 13.7611L13.7044 14.6916L17.5816 10.811L18.0449 10.3453Z" fill="black" fill-opacity="0.5"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        endwhile;
    endif;

    wp_reset_postdata();
    wp_die();
}


add_action('wp_ajax_load_more_projects', 'load_more_projects');
add_action('wp_ajax_nopriv_load_more_projects', 'load_more_projects');

function load_more_projects() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) + 1 : 1;
    $category_id = isset($_POST['category']) ? intval($_POST['category']) : 0;

    $args = array(
        'post_type'      => 'project',
        'posts_per_page' => 6,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    );

    // Filter by category if provided
    if ( $category_id > 0 ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'project_category',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        );
    }

    $projects_query = new WP_Query($args);

    if ($projects_query->have_posts()) :
        while ($projects_query->have_posts()) : $projects_query->the_post();
            $project_id = get_the_ID();
            $project_title = get_the_title();
            $project_link = get_permalink();
            $featured_image_url = get_the_post_thumbnail_url($project_id, 'large');
            $place = get_field('place', $project_id);
            ?>
            <!-- project box  -->
            <div class="prjct_pg_1_img_bx">
                <a href="<?php echo esc_url($project_link); ?>">
                    <div class="prjct_pg_1_img_bx_img">
                        <?php if ($featured_image_url) : ?>
                            <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($project_title); ?>">
                        <?php else : ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.jpg'); ?>" alt="<?php echo esc_attr($project_title); ?>">
                        <?php endif; ?>
                    </div>
                </a>
                <a href="<?php echo esc_url($project_link); ?>"><?php echo esc_html($project_title); ?></a>
                <?php if ($place) : ?>
                    <p><?php echo esc_html($place); ?></p>
                <?php endif; ?>
            </div>
            <?php
        endwhile;
    endif;

    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_load_more_media', 'load_more_media');
add_action('wp_ajax_nopriv_load_more_media', 'load_more_media');

function load_more_media() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) + 1 : 1;
    $category_id = isset($_POST['category']) ? intval($_POST['category']) : 0;
    
    $args = array(
        'post_type'      => 'mediapress',
        'posts_per_page' => 6,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'media_category',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
    );

    $media_query = new WP_Query($args);
    $term = get_term($category_id, 'media_category');

    if ($media_query->have_posts()) :
        while ($media_query->have_posts()) : $media_query->the_post();
            ?>
            <div class="puk-post-card">
                <div class="puk-post-card__link">
                    <div class="puk-post-card__body">
                        <div class="puk-post-card__date">
                            <h5><?php echo get_the_date('d.m.y'); ?></h5>
                        </div>
                        <?php
                        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $bg_image = $featured_image ? $featured_image : get_template_directory_uri() . '/assets/images/ondemand.jpg';
                        $video_icon_color = get_field('video_icon_color');
                        ?>
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="puk-post-card__media video <?php if ($video_icon_color == 'white') { echo 'dark'; } ?> position-relative" style="background-image: url('<?php echo esc_url($bg_image); ?>');">
                            <?php if ($term && isset($term->slug) && in_array($term->slug, array('on-demand', 'video'))) { ?>
                                <div class="puk-post-card__play">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="39" height="39" viewBox="0 0 39 39" fill="none">
                                        <path d="M19.25 0C24.3554 0 29.2517 2.02812 32.8618 5.63819C36.4719 9.24827 38.5 14.1446 38.5 19.25C38.5 24.3554 36.4719 29.2517 32.8618 32.8618C29.2517 36.4719 24.3554 38.5 19.25 38.5C14.1446 38.5 9.24827 36.4719 5.63819 32.8618C2.02812 29.2517 0 24.3554 0 19.25C0 14.1446 2.02812 9.24827 5.63819 5.63819C9.24827 2.02812 14.1446 0 19.25 0ZM19.25 35.75C23.6261 35.75 27.8229 34.0116 30.9173 30.9173C34.0116 27.8229 35.75 23.6261 35.75 19.25C35.75 14.8739 34.0116 10.6771 30.9173 7.58274C27.8229 4.48839 23.6261 2.75 19.25 2.75C14.8739 2.75 10.6771 4.48839 7.58274 7.58274C4.48839 10.6771 2.75 14.8739 2.75 19.25C2.75 23.6261 4.48839 27.8229 7.58274 30.9173C10.6771 34.0116 14.8739 35.75 19.25 35.75ZM17.1875 25.1102L25.9792 19.25L17.1875 13.3897V25.1102ZM17.644 10.3868L28.3635 17.534C28.646 17.7224 28.8776 17.9775 29.0378 18.2769C29.198 18.5762 29.2818 18.9105 29.2818 19.25C29.2818 19.5895 29.198 19.9238 29.0378 20.2231C28.8776 20.5225 28.646 20.7776 28.3635 20.966L17.644 28.1132C17.3334 28.3203 16.9724 28.4392 16.5996 28.4572C16.2267 28.4752 15.8559 28.3917 15.5268 28.2156C15.1977 28.0394 14.9225 27.7773 14.7307 27.4571C14.5389 27.1368 14.4375 26.7705 14.4375 26.3972V12.1C14.4375 11.7267 14.5389 11.3604 14.7307 11.0402C14.9225 10.72 15.1977 10.4578 15.5268 10.2817C15.8559 10.1055 16.2267 10.022 16.5996 10.0401C16.9724 10.0581 17.3334 10.1769 17.644 10.384V10.3868Z" fill="black" />
                                    </svg>
                                </div>
                                <div class="puk-post-card__watermark"><?php echo esc_html(get_the_title()); ?></div>
                            <?php } ?>
                        </a>
                        <div class="puk-post-card__content">
                            <h3 class="puk-post-card__title">
                                <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?><?php if ($term && isset($term->slug) && in_array($term->slug, array('on-demand', 'video'))) { ?>_<?php } ?> </a>
                            </h3>
                            <?php
                            $media_sub_title = get_field('media_sub_title');
                            if ($media_sub_title) {
                                echo '<h4 class="mp_sub"><a href="' . esc_url(get_permalink()) . '">' . esc_html($media_sub_title) . '</a></h4>';
                            }
                            ?>
                            <article class="puk-post-card__excerpt">
                                <p><?php echo wp_trim_words(get_the_content(), 45, '...'); ?></p>
                            </article>
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="puk-post-card__arrow">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.0449 10.3453L17.5805 9.88006L13.6999 6.00291L12.7907 6.93425L15.5608 9.69994L2.58988 9.70572L2.59045 10.9987L15.5608 10.9929L12.7938 13.7611L13.7044 14.6916L17.5816 10.811L18.0449 10.3453Z" fill="black" fill-opacity="0.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
    endif;

    wp_reset_postdata();
    wp_die();
}