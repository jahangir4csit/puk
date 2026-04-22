<?php
/**
 * Template part for displaying product accessories sections
 *
 * Required variables:
 * - $pro_inte_access_rept
 *
 * Displays accessories in two sections based on tax_acc_ft__type:
 * - 1: Integrated Accessories (included)
 * - 0: Accessories not included
 */

// Exit if accessed directly or if required data is missing
if (!defined('ABSPATH') || empty($pro_inte_access_rept)) {
    return;
}

$pro_inte_access_stitle = get_field('prod__sku');

// Separate accessories into included (1) and not included (0)
$included_accessories = [];
$not_included_accessories = [];

foreach ($pro_inte_access_rept as $term_id) {
    $term = get_term($term_id, 'accessories');
    if (!$term || is_wp_error($term)) {
        continue;
    }

    $is_featured = get_field('tax_acc_ft__type', 'accessories_' . $term_id);

    $accessory_data = [
        'term_id' => $term_id,
        'term'    => $term,
        'code'    => get_field('tax_acc__code', 'accessories_' . $term_id),
        'label'   => get_field('tax_acc_integ__label', 'accessories_' . $term_id),
        'image'   => get_field('tax_acc_ft__img', 'accessories_' . $term_id),
        'title'   => $term->name,
        'desc'    => $term->description,
    ];

    if ($is_featured == 1) {
        $included_accessories[] = $accessory_data;
    } else {
        $not_included_accessories[] = $accessory_data;
    }
}
?>

<?php if (!empty($included_accessories)) : ?>
<section class="integrated-accessories-main include">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title">
                    <h3>Integrated Accessories</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="accessories-bg" style="background: rgba(217, 217, 217, 0.25);">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="title-box">
                        <h3 style="color: rgba(0, 0, 0, 0.50);"><?php echo $pro_inte_access_stitle; ?>. _ _</h3>
                        <p style="color: rgba(0, 0, 0, 0.50);">Built-in accessories to be requested when ordering <br>
                            example <?php echo $pro_inte_access_stitle; ?><strong>.HC</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-sm-12 col-md-10 col-lg-10">
                    <div class="accessories-grid-parent">

                        <?php foreach ($included_accessories as $accessory) : ?>
                        <div class="single-grid">
                            <div class="image-box">
                                <?php if (!empty($accessory['image'])) : ?>
                                <img src="<?php echo $accessory['image']; ?>" alt="Wall Mounting 3" />
                                <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-details/ia1.png"
                                    alt="">
                                <?php endif; ?>
                            </div>
                            <div class="content-box">
                                <?php if (!empty($accessory['label'])) : ?><h5><?php echo $accessory['label']; ?></h5>
                                <?php endif; ?>
                                <?php if (!empty($accessory['title'])) : ?><h6><?php echo $accessory['title']; ?></h6>
                                <?php endif; ?>
                                <?php if (!empty($accessory['desc'])) : ?><p><?php echo $accessory['desc']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($not_included_accessories)) : ?>
<section class="integrated-accessories-main not-include">
    <div class="accessories-bg" style="background:rgba(230, 227, 223, 1);">
        <div class="container">
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

                        <?php foreach ($not_included_accessories as $accessory) : ?>
                        <div class="single-grid">
                            <div class="image-box">
                                <?php if (!empty($accessory['image'])) : ?>
                                <img src="<?php echo $accessory['image']; ?>" alt="Wall Mounting 3" />
                                <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-details/ia1.png"
                                    alt="">
                                <?php endif; ?>
                            </div>
                            <div class="content-box">
                                <?php if (!empty($accessory['code'])) : ?><h5><?php echo $accessory['code']; ?></h5>
                                <?php endif; ?>
                                <?php if (!empty($accessory['title'])) : ?><h6><?php echo $accessory['title']; ?></h6>
                                <?php endif; ?>
                                <?php if (!empty($accessory['desc'])) : ?><p><?php echo $accessory['desc']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>