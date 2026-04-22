<?php
/**
 * Template part for displaying light distribution section
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}

$pro_lst_dstrbtn_glry = get_field('pro_lst_dstrbtn_glry');

?>

<!-- light distribution start -->
<section class="light-distribution-main d-none">
    <div class="container">   
        <div class="row">
            <div class="col-12">
                <div class="title-box">
                    <h3>Light Distribution</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-2 col-lg-2"></div>
            <div class="col-sm-12 col-md-10 col-lg-10">
                <div class="light-grid-parent">

                    <?php if (!empty($pro_lst_dstrbtn_glry)) :
                        foreach ($pro_lst_dstrbtn_glry as $pro_lst_img) { ?>
                    <div class="single-light">
                        <img src="<?php echo $pro_lst_img; ?>" alt="Wall Mounting 4" />
                    </div>
                    <?php }
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- light distribution end -->
