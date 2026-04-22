<?php
/**
 * Template part for displaying download section
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}

$pro_dwnld_ltd_files = get_field('pro_dwnld_ltd_files');
$pro_dwnld_instructions = get_field('pro_dwnld_instructions');
$pro_dwnld_revit = get_field('pro_dwnld_revit');
$pro_dwnld_3dbim = get_field('pro_dwnld_3dbim');
$pro_dwnld_photometric = get_field('pro_dwnld_photometric');
$pro_dwnld_provideo = get_field('pro_dwnld_provideo');
?>

<section class=" light-distribution-main download">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-box">
                    <h3>Download</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-2 col-lg-2"></div>
            <div class="col-sm-12 col-md-10 col-lg-10">
                <div class="download-grid-parent">

                    <!-- Generate Product Data Sheet PDF -->
                    <div class="single-download">
                        <a href="#" class="datasheet-pdf-btn" data-product-id="<?php echo get_the_ID(); ?>">
                            <div class="icon">
                                <img class="pdf-icon-default" src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg"
                                    alt="download.png" />
                                <span class="pdf-loading" style="display:none;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <style>
                                            .spinner { animation: rotate 1s linear infinite; transform-origin: center; }
                                            @keyframes rotate { 100% { transform: rotate(360deg); } }
                                        </style>
                                        <circle class="spinner" cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="31.4 31.4" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="text">Data Sheet</div>
                        </a>
                    </div>

                    <?php if (!empty($pro_dwnld_instructions)) : ?>
                    <div class="single-download">
                        <a href="<?php echo $pro_dwnld_instructions; ?>" download>
                            <div class="icon">
                                <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg"
                                    alt="download.png" />
                            </div>
                            <div class="text">Installation instructions</div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($pro_dwnld_ltd_files)) : ?>
                    <div class="single-download">
                        <a href="<?php echo $pro_dwnld_ltd_files; ?>" download>
                            <div class="icon">
                                <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg"
                                    alt="download.png" />
                            </div>
                            <div class="text">LTD file</div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($pro_dwnld_photometric)) : ?>
                    <div class="single-download">
                        <a href="<?php echo $pro_dwnld_photometric; ?>" download>
                            <div class="icon">
                                <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg"
                                    alt="download.png" />
                            </div>
                            <div class="text">Photometric Data</div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($pro_dwnld_3dbim)) : ?>
                    <div class="single-download">
                        <a href="<?php echo $pro_dwnld_3dbim; ?>" download>
                            <div class="icon">
                                <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg"
                                    alt="download.png" />
                            </div>
                            <div class="text">3D BIM</div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($pro_dwnld_provideo)) : ?>
                    <div class="single-download">
                        <a href="<?php echo $pro_dwnld_provideo; ?>">
                            <div class="icon">
                                <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_play.svg"
                                    alt="play.png" />
                            </div>
                            <div class="text">Product Video</div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($pro_dwnld_revit)) : ?>
                    <div class="single-download">
                        <a href="<?php echo $pro_dwnld_revit; ?>" download>
                            <div class="icon">
                                <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/iconoir_download.svg"
                                    alt="download.png" />
                            </div>
                            <div class="text">Revit</div>
                        </a>
                    </div>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
</section>
