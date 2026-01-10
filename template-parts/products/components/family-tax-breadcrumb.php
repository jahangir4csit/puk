<?php
/**
 * Family Taxonomy Breadcrumb Component
 * 
 * @var array $args {
 *     @var WP_Term $current_term
 *     @var int     $term_id
 *     @var string  $taxonomy
 * }
 */
$current_term = $args['current_term'];
$term_id      = $args['term_id'];
$taxonomy     = $args['taxonomy'];
?>
<section class="common-breadcrumb-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="common-breadcrumb-wrapper">
                    <?php
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
</section><!-- end family taxonomy breadcrumb -->
