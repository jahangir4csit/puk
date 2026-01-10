<?php

get_header(); 

function get_taxonomy_term_depth($term_id, $taxonomy) {
    $depth = 0;
    $parent = get_term($term_id, $taxonomy)->parent;
    while ($parent != 0) {
        $depth++;
        $parent = get_term($parent, $taxonomy)->parent;
    }
    return $depth;
}


$term_id = get_queried_object_id();
$depth = get_taxonomy_term_depth($term_id, 'product-family');



?>

<main>
    <?php
    switch ($depth) {
    case 0:
    get_template_part('template-parts/products/main-category');
    break;

    case 1:
    get_template_part('template-parts/products/family');
    break;

    case 2:
    get_template_part('template-parts/products/sub-family');
    break;

    case 3:
    get_template_part('template-parts/products/sub-sub-family');
    break;
    }

    ?>
</main>
<?php get_footer(); ?>