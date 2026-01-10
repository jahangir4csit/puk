    <section class="product-page-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="product_title">
                        <h1> Products </h1>
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
                    if (!empty($page)) {
                        $page_url = get_permalink($page[0]->ID);
                    }
                    $terms = get_terms(array(
                        'taxonomy'   => 'product-family',
                        'parent'     => 0,
                        'hide_empty' => false,
                        'orderby'    => 'term_id',
                        'order'      => 'ASC',
                    ));

                    // Get current taxonomy term ID
                    $current_term_id = get_queried_object_id();
                    ?>
                    <div class="product_categories">
                        <ul>
                            <li><a href="<?php echo $page_url; ?>">View All</a></li>
                            <?php if (! empty($terms) && ! is_wp_error($terms)) : ?>
                            <?php foreach ($terms as $term) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_term_link($term)); ?>"
                                    class="<?php echo ($term->term_id == $current_term_id) ? 'active' : ''; ?>">
                                    <?php echo esc_html($term->name); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <li><a href="#" class="is_new">New</a></li>
                        </ul>
                    </div>

                    <!-- product subcategories  -->
                    <?php

                    // Get direct child terms of the current term
                    $child_terms = get_terms(array(
                        'taxonomy'   => 'product-family',
                        'parent'     => $current_term_id,
                        'hide_empty' => false,
                        'orderby'    => 'name',
                        'order'      => 'ASC'
                    ));
                    ?>

                    <?php if (! empty($child_terms) && ! is_wp_error($child_terms)) : ?>
                    <div class="product_subcategories">
                        <ul>
                            <?php foreach ($child_terms as $child) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_term_link($child)); ?>"
                                    class="<?php echo ($child->term_id == $current_term_id) ? 'is_active' : ''; ?>">
                                    <?php echo esc_html($child->name); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php else : ?>
                    <p class="empty_message">No family found.</p>
                    <?php endif; ?>


                </div>
            </div>

            <?php
            // Current taxonomy term
            $term_id = get_queried_object_id();
            $current_term = get_term($term_id, 'product-family');


            if ($current_term->parent == 0) {
                $main_term_id = $current_term->term_id;
            } else {
                // If child page opened, find top-level parent
                $ancestors = get_ancestors($term_id, 'product-family');
                $main_term_id = end($ancestors);  // Top-most parent
            }

            $subcategories = get_terms(array(
                'taxonomy'   => 'product-family',
                'parent'     => $main_term_id,
                'hide_empty' => false,
                'orderby'    => 'term_id',
                'order'      => 'ASC'
            ));
            ?>

            <?php
            if (!empty($subcategories)) :
                foreach ($subcategories as $subcat) :

                    // Title of product row (Subcategory)
                    echo '<div class="row product_rows">';

                    echo '
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="product_row_title">
                        <h4>' . esc_html($subcat->name) . '</h4>
                    </div>
                </div>';

                    $child_terms = get_terms(array(
                        'taxonomy'   => 'product-family',
                        'parent'     => $subcat->term_id,
                        'hide_empty' => false,
                        'orderby'    => 'term_id',
                        'order'      => 'ASC'
                    ));

                    if (!empty($child_terms)) :
                        foreach ($child_terms as $child) :

                            $default_img_url = get_field('site_plachlder_img','option') ;
                            // ACF IMAGE
                            $image = get_field('pf_fet_img', 'product-family_' . $child->term_id);
                            $img_url = $image ?: $default_img_url;

                            $hover_img = get_field('pf_hover_img', 'product-family_' . $child->term_id);
                            $hover_img_url = $hover_img ? $hover_img : $default_img_url; 

            ?>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-2">
                <div class="product_row_item">

                    <?php
                    $empty_img_class = !$image ? ' empty_img' : '';
                    if($hover_img_url){ ?>
                    <a class="product-image-wrap<?php echo $empty_img_class; ?>"
                        href="<?php echo esc_url(get_term_link($child)); ?>">
                        <img class="img-default" src="<?php echo esc_url($img_url); ?>"
                            alt="<?php echo esc_attr($child->name); ?>">
                        <img class="img-hover" src="<?php echo esc_url($hover_img_url); ?>"
                            alt="<?php echo esc_attr($child->name); ?>">
                    </a>
                    <?php }else{ ?>
                    <a class="product-image-wrap<?php echo $empty_img_class; ?>"
                        href="<?php echo esc_url(get_term_link($child)); ?>">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($child->name); ?>">
                    </a>
                    <?php  } ?>

                    <a href="<?php echo esc_url(get_term_link($child)); ?>" class="product_item_title">
                        <?php echo esc_html($child->name); ?>
                    </a>
                </div>
            </div>

            <?php
                        endforeach;
                    else :
                        echo '<p class="empty_message">No sub-family found.</p>';
                    endif;

                    echo '</div>'; // END product_row

                endforeach;
            endif;
            ?>




        </div>
    </section>