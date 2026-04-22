<?php
/**
 * Product Datasheet PDF Template
 * Using pixel-based layout for better DOMPDF compatibility
 *
 * @package PUK
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Convert social icons to base64
$social_icons_path = get_template_directory() . '/assets/images/social/';
$social_icons = array(
    'instagram' => PUK_PDF_Image_Handler::file_to_base64( $social_icons_path . 'instagram.svg' ),
    'linkedin'  => PUK_PDF_Image_Handler::file_to_base64( $social_icons_path . 'linkedin.svg' ),
    'facebook'  => PUK_PDF_Image_Handler::file_to_base64( $social_icons_path . 'facebook.svg' ),
    'youtube'   => PUK_PDF_Image_Handler::file_to_base64( $social_icons_path . 'youtube.svg' ),
);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo esc_html( $data['basic']['title'] ?? 'Product Datasheet' ); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Rethink+Sans:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 40px 30px 40px 30px;
        }

        body {
            font-family: "Rethink Sans", "Helvetica", sans-serif;
            font-size: 8px;
            line-height: 1.4;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .wrapper {
            width: 100%;
        }

        /* HEADER */
        .header-logo {
            margin-bottom: 25px;
        }
        .logo-text {
            font-family: inherit;
            font-size: 40px;
            font-weight: 300;
            letter-spacing: -1px;
            color: #1a1a1a;
            display: block;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        /* IMAGE ROW */
        .image-row {
            width: 100%;
            margin-bottom: 32px;
            overflow: hidden;
        }
        .image-row::after {
            content: "";
            display: table;
            clear: both;
        }
        .img-col {
            float: left;
            height: 265px;
            background: #f0f0f0;
            overflow: hidden;
            position: relative;
        }
        .img-col-left {
            width: 59.5%;
            margin-right: 0.5%;
        }
        .img-col-right {
            width: 38%;
        }
        .img-container {
            width: 100%;
            height: 100%;
            display: table;
        }
        .img-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .img-col img {
            max-width: 100%;
            max-height: 100%;
        }

        /* INFO ROW */
        .info-row {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-row::after {
            content: "";
            display: table;
            clear: both;
        }
        .info-col-left {
            float: left;
            width: auto;
            margin-right: 35px;
            max-width: 15%;
        }
        .info-col-right {
            float: left;
            width: 45%;
        }
        .product-sku {
            font-size: 15px;
            font-weight: 500;
            color: #000;
            line-height: 120%; /* 10.8px */
            letter-spacing: -0.18px;
            margin-bottom: 5px;
        }
        .product-name {
            color: #000;
            font-size: 24px;
            font-weight: 600;
            font-style: normal;
            line-height: 110%; /* 16.5px */
            letter-spacing: -0.15px;
             margin-bottom: 20px;
        }
        .designed-label,
        .designer-name 
        {
            font-size: 11px;
            color: #8A8A8A;
            line-height:1px;
        }
        .description {
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 120%; /* 9.6px */
            letter-spacing: -0.08px;
            color: #8A8A8A;
        }

        /* TECH DRAWING */
        .tech-drawing {
            text-align: center;
            margin: 80px auto 80px 13%;
            width: 200px;
        }
        .tech-drawing img {
            max-width: 100%;
            height: auto;
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            width: 100%;
            height: 40px;
            padding-left: 30px;
        }
        .footer-inner {
            width: 100%;
            padding: 0;
            white-space: nowrap;
        }
        .footer-col {
            display: inline-block;
            vertical-align: bottom;
            font-size: 11px;
            color: #000000;
            line-height: 1.25;
            letter-spacing: -0.1px;
            margin-right: 30px;
            white-space: normal;
        }
        .footer-left {
            width: auto;
            padding-left: 40px;
        }
        .footer-center {
            width: auto;
        }
        .footer-right {
            width: auto;
            margin-right: 0;
        }
        .social-icon-img {
            width: 14px;
            height: 14px;
            margin-right: 6px;
            vertical-align: middle;
        }
        .footer-company-name {
            display: block;
        }

        /* PAGE 2 */
        .page-break {
            page-break-before: always;
        }
        .tech_data_wrap{
            max-width: 60%;
            margin: 50px auto;
        }
        .section-title {
            font-size: 28px;
            font-weight: 400;
            color: #000;
            line-height: .7;
            margin-bottom: 32px;
            text-transform: capitalize;

        }
        .section-title span {
            display: block;
        }
        .specs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 100px;
        }
        .specs-table td {
            padding: 4px 0;
            font-size: 14px;
            color: #000;
            vertical-align: top;
        }
        .specs-table .label {
            width: 18%;
            font-weight: 600;
            font-size: 14px;
        }
        .specs-table .value {
            font-weight: 400;
        }
        .specs-table .color-icon {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 6px;
            vertical-align: middle;
            border-radius: 50%;
            overflow: hidden;
        }
        .specs-table .color-icon img {
            width: 100%;
            height: 100%;
        }

        /* TECHNICAL FEATURES */
        .features-section {
            margin-bottom: 100px;
        }
        .features-row {
            overflow: hidden;
        }
        .features-row::after {
            content: "";
            display: table;
            clear: both;
        }
        .feature-icon {
            float: left;
            width: 40px;
            height: 40px;
            margin-right: 10px;
            text-align: center;
        }
        .feature-icon img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        /* ALSO AVAILABLE IN */
        .also-available-section {
            margin-bottom: 40px;
        }
        .also-available-list {
            margin-top: 15px;
        }
        .also-available-item {
            margin-bottom: 8px;
            font-size: 10px;
        }

        /* ACCESSORIES PAGE (PAGE 3) */
        .accessories-page {
            width: 100%;
        }
        .accessory-section {
            margin-bottom: 0;
            width: 100%;
        }
        .accessory-section.integrated {
            padding: 30px;
            margin: -40px -30px 40px -30px; /* Offset page margin to fill width */
            width: 100%;
        }
        .accessory-section.not-included {
            padding: 30px;
            margin: -40px -30px 40px -30px; /* Offset page margin to fill width */
            width: 100%;
        }
        .accessory-title-box {
            padding-bottom: 40px;
            border-bottom: 1px solid #0000004d;
        }
        .accessory-section.not-included .accessory-title-box {
            padding-bottom: 25px;
        }
        .accessory-main-title {
            font-size: 28px;
            font-weight: 400;
            color: #000;
        }
        .accessory-sku-title {
            font-size: 18px;
            font-weight: 500;
            line-height: 120%;
        }
        .integrated .accessory-sku-title {
            color: #00000080;
        }
        .accessory-subtitle {
            font-size: 14px;
            color: #000;
            margin-bottom: 20px;
        }
        .accessory-grid {
            width: 100%;
        }
        .accessory-item {
            width: 100%;
            overflow: hidden;
            padding: 10px 0;
            border-bottom: 1px solid #0000004d;
        }
        .accessory-item::after {
            content: "";
            display: table;
            clear: both;
        }
        .accessory-img-box {
            float: left;
            width: 82px;
            height: 82px;
            margin-right: 24px;
            background: #f5f5f5;
            text-align: center;
            overflow: hidden;
        }
        .accessory-img-box img {
            width: 82px;
            height: 82px;
            display: block;
            object-fit: contain;
        }
        .accessory-content {
            float: left;
            width: 450px;
        }
        .accessory-content h5 {
            font-size: 24px;
            font-weight: 500;
            margin: 0 0 14px 0;
            text-transform: uppercase;
            color: #00000080;
            line-height: 120%;
            letter-spacing: -0.3px;

        }
        .accessory-content h6 {
            font-size: 18px;
            font-weight: 400;
            margin: 0 0 4px 0;
            line-height: 120%;
        }
        .accessory-content p {
            font-size: 14px;
            color: #000;
            margin: 0;
            line-height: 1.4;
        }
        .color-circle {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
            vertical-align: middle;
            overflow: hidden;
        }
        .color-circle img {
            width: 100%;
            height: 100%;
        }
        
        .color-name {
            display: inline-block;
            width: 150px;
            vertical-align: middle;
            font-size: 11px;
            color: #000;
            font-weight: 400;
        }
        .color-sku {
            color: #000;
            display: inline-block;
            vertical-align: middle;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
        }

        /* GALLERY PAGE */
        .gallery-page {
            width: 100%;
        }
        .gallery-grid {
            max-width: 60%;
            margin: 50px auto;
        }
        .gallery-row {
            width: 100%;
            margin-bottom: 24px;
            clear: both;
        }
        .gallery-row::after {
            content: "";
            display: table;
            clear: both;
        }
        .gallery-item {
            float: left;
            width: 203px;
            height: 204px;
            background: #f0f0f0;
            margin-right: 32px;
            overflow: hidden;
            position: relative;
        }
        .gallery-item:last-child {
            margin-right: 0;
        }
        .gallery-img-container {
            width: 100%;
            height: 100%;
            display: table;
        }
        .gallery-img-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }

        /* FOOTER DATE */
        .footer-date {
            position: fixed;
            bottom: 10px;
            right: 30px;
            font-size: 8px;
            color: #8A8A8A;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- LOGO -->
    <div class="header-logo">
        <?php if ( ! empty( $data['logo_base64'] ) ) : ?>
            <img src="<?php echo esc_attr( $data['logo_base64'] ); ?>" alt="Logo">
        <?php else : ?>
            <span class="logo-text">puk</span>
        <?php endif; ?>
    </div>

    <div class="image-row">
        <div class="img-col img-col-left">
            <div class="img-container">
                <div class="img-cell">
                    <?php
                    $main_img = $data['images']['main_image_base64'] ?? ($data['images']['main_gallery_base64'][0] ?? '');
                    if ( $main_img ) : ?>
                        <img src="<?php echo esc_attr( $main_img ); ?>" alt="Product">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="img-col img-col-right">
            <div class="img-container">
                <div class="img-cell">
                    <?php
                    $app_img = $data['images']['main_gallery_base64'][1] ?? ($data['images']['sub_gallery_base64'][0] ?? '');
                    if ( $app_img ) : ?>
                        <img src="<?php echo esc_attr( $app_img ); ?>" alt="Application">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- INFO ROW -->
    <div class="info-row">
        <div class="info-col-left">
            <?php if ( ! empty( $data['basic']['sku'] ) ) : ?>
                <div class="product-sku"><?php echo esc_html( $data['basic']['sku'] ); ?></div>
            <?php endif; ?>
            <div class="product-name"><?php echo esc_html( $data['basic']['title'] ?? '' ); ?></div>
            <?php if ( ! empty( $data['basic']['designed_by'] ) ) : ?>
                <div class="designed-label">Designed by</div>
                <div class="designer-name"><?php echo esc_html( $data['basic']['designed_by'] ); ?></div>
            <?php endif; ?>
        </div>
        <div class="info-col-right">
            <div class="description"><?php echo esc_html( $data['basic']['description'] ?? '' ); ?></div>
        </div>
    </div>

    <!-- TECH DRAWING -->
    <?php if ( ! empty( $data['images']['tech_drawing_base64'] ) ) : ?>
        <div class="tech-drawing">
            <img src="<?php echo esc_attr( $data['images']['tech_drawing_base64'] ); ?>" alt="Technical Drawing">
        </div>
    <?php endif; ?>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-col footer-left">
                <div class="social-icons">
                    <?php if ( ! empty( $social_icons['instagram'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['instagram'] ); ?>" alt="Instagram" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['linkedin'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['linkedin'] ); ?>" alt="LinkedIn" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['facebook'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['facebook'] ); ?>" alt="Facebook" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['youtube'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['youtube'] ); ?>" alt="YouTube" class="social-icon-img">
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-col footer-center">
                <span class="footer-company-name">PUK ITALIA GROUP srl</span>
                Via San Giorgio 16<br>
                20851 Lissone (MB) – ITALY
            </div>
            <div class="footer-col footer-right">
                <span>T.</span> +39 039 24.57.920<br>
                <span>F.</span> +39 039 24.57.924<br>
                puk@puk.it – puk.it
            </div>
        </div>
    </div>

</div>

<!-- PAGE 2 -->
<?php
$has_specs = ! empty( $data['specifications'] );
$has_features = ! empty( $data['features'] );
$has_also_available = ! empty( $data['also_available'] );

if ( $has_specs || $has_features || $has_also_available ) :
?>
<div class="page-break"></div>
<div class="wrapper">

    <!-- LOGO -->
    <div class="header-logo">
        <?php if ( ! empty( $data['logo_base64'] ) ) : ?>
            <img src="<?php echo esc_attr( $data['logo_base64'] ); ?>" alt="Logo">
        <?php else : ?>
            <span class="logo-text">puk</span>
        <?php endif; ?>
    </div>

    <div class="tech_data_wrap">
    <!-- LIGHT SOURCE TECHNICAL DATA -->
    <?php if ( $has_specs ) : ?>
        <div class="section-title">
            <span>Light Source</span>
            <span>Technical Data</span>
        </div>
        <table class="specs-table">
            <?php foreach ( $data['specifications'] as $key => $spec ) : ?>
                <?php if ( ! empty( $spec['value'] ) ) : ?>
                    <tr>
                        <td class="label"><?php echo esc_html( $spec['label'] ); ?></td>
                        <td class="value">
                            <?php if ( $key === 'finish_color' && ! empty( $spec['icon_base64'] ) ) : ?>
                                <span class="color-icon"><img src="<?php echo esc_attr( $spec['icon_base64'] ); ?>" alt=""></span>
                            <?php endif; ?>
                            <?php echo esc_html( $spec['value'] ); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- TECHNICAL FEATURE -->
    <?php if ( $has_features ) : ?>
        <div class="features-section">
            <div class="section-title">Technical Feature</div>
            <div class="features-row">
                <?php foreach ( $data['features'] as $feature ) : ?>
                    <?php
                    // Use pre-processed base64 from image handler, fallback to url conversion
                    $feature_icon = ! empty( $feature['icon_base64'] )
                        ? $feature['icon_base64']
                        : ( ! empty( $feature['icon_url'] ) ? PUK_PDF_Image_Handler::url_to_base64( $feature['icon_url'] ) : '' );

                    if ( $feature_icon ) : ?>
                        <div class="feature-icon">
                            <img src="<?php echo esc_attr( $feature_icon ); ?>" alt="<?php echo esc_attr( $feature['name'] ); ?>">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- ALSO AVAILABLE IN -->
    <?php if ( $has_also_available ) : ?>
        <div class="also-available-section">
            <div class="section-title">
                <span>Also</span>
                <span>Available In</span>
            </div>
            <div class="also-available-list">
                <?php foreach ( $data['also_available'] as $variant ) : ?>
                    <div class="also-available-item">
                        <span class="color-circle">
                            <?php if ( ! empty( $variant['color_img_base64'] ) ) : ?>
                                <img src="<?php echo esc_attr( $variant['color_img_base64'] ); ?>" alt="">
                            <?php endif; ?>
                        </span>
                        <span class="color-name"><?php echo esc_html( $variant['color_name'] ); ?></span>
                        <span class="color-sku"><?php echo esc_html( $variant['sku'] ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-col footer-left">
                <div class="social-icons">
                    <?php if ( ! empty( $social_icons['instagram'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['instagram'] ); ?>" alt="Instagram" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['linkedin'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['linkedin'] ); ?>" alt="LinkedIn" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['facebook'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['facebook'] ); ?>" alt="Facebook" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['youtube'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['youtube'] ); ?>" alt="YouTube" class="social-icon-img">
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-col footer-center">
                <span class="footer-company-name">PUK ITALIA GROUP srl</span>
                Via San Giorgio 16<br>
                20851 Lissone (MB) – ITALY
            </div>
            <div class="footer-col footer-right">
                <span>T.</span> +39 039 24.57.920<br>
                <span>F.</span> +39 039 24.57.924<br>
                puk@puk.it – puk.it
            </div>
        </div>
    </div>

    <!-- FOOTER DATE -->
    <div class="footer-date">
        ultimo aggiornamento <?php echo date( 'd/m/Y' ); ?>
    </div>

</div>
<?php endif; ?>

<?php
$has_included = ! empty( $data['accessories']['included'] );
$has_not_included = ! empty( $data['accessories']['not_included'] );

if ( $has_included || $has_not_included ) :
?>
<div class="page-break"></div>
<div class="wrapper accessories-page">
    <!-- LOGO -->
    <div class="header-logo">
        <?php if ( ! empty( $data['logo_base64'] ) ) : ?>
            <img src="<?php echo esc_attr( $data['logo_base64'] ); ?>" alt="Logo">
        <?php else : ?>
            <span class="logo-text">puk</span>
        <?php endif; ?>
    </div>

    <div class="tech_data_wrap">
        <?php if ( $has_included ) : ?>
            <div class="accessory-section integrated">
                <div class="accessory-main-title">Integrated Accessories</div>
                <div class="accessory-title-box">
                    <div class="accessory-subtitle">
                        Built-in accessories to be requested when ordering<br>
                        example <?php echo esc_html( $data['basic']['sku'] ); ?><strong>.HC</strong>
                    </div>
                    <div class="accessory-sku-title"><?php echo esc_html( $data['basic']['sku'] ); ?>. _ _</div>
                </div>

                <div class="accessory-grid">
                    <?php foreach ( $data['accessories']['included'] as $accessory ) : ?>
                        <?php
                        $acc_icon = ! empty( $accessory['image_base64'] )
                            ? $accessory['image_base64']
                            : ( ! empty( $accessory['image_url'] ) ? PUK_PDF_Image_Handler::url_to_base64( $accessory['image_url'] ) : '' );
                        ?>
                        <div class="accessory-item">
                            <div class="accessory-img-box">
                                <?php if ( $acc_icon ) : ?>
                                    <img src="<?php echo esc_attr( $acc_icon ); ?>" alt="">
                                <?php endif; ?>
                            </div>
                            <div class="accessory-content">
                                <?php if ( ! empty( $accessory['label'] ) ) : ?><h5><?php echo esc_html( $accessory['label'] ); ?></h5><?php endif; ?>
                                <?php if ( ! empty( $accessory['title'] ) ) : ?><h6><?php echo esc_html( $accessory['title'] ); ?></h6><?php endif; ?>
                                <?php if ( ! empty( $accessory['description'] ) ) : ?><p><?php echo esc_html( $accessory['description'] ); ?></p><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( $has_not_included ) : ?>
            <div class="accessory-section not-included">
                <div class="accessory-title-box">
                    <div class="accessory-main-title">Accessories not included</div>
                    <div class="accessory-subtitle">
                        Accessories to be ordered separately
                    </div>
                </div>

                <div class="accessory-grid">
                    <?php foreach ( $data['accessories']['not_included'] as $accessory ) : ?>
                        <?php
                        $acc_icon = ! empty( $accessory['image_base64'] )
                            ? $accessory['image_base64']
                            : ( ! empty( $accessory['image_url'] ) ? PUK_PDF_Image_Handler::url_to_base64( $accessory['image_url'] ) : '' );
                        ?>
                        <div class="accessory-item">
                            <div class="accessory-img-box">
                                <?php if ( $acc_icon ) : ?>
                                    <img src="<?php echo esc_attr( $acc_icon ); ?>" alt="">
                                <?php endif; ?>
                            </div>
                            <div class="accessory-content">
                                <?php if ( ! empty( $accessory['code'] ) ) : ?><h5><?php echo esc_html( $accessory['code'] ); ?></h5><?php endif; ?>
                                <?php if ( ! empty( $accessory['title'] ) ) : ?><h6><?php echo esc_html( $accessory['title'] ); ?></h6><?php endif; ?>
                                <?php if ( ! empty( $accessory['description'] ) ) : ?><p><?php echo esc_html( $accessory['description'] ); ?></p><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-col footer-left">
                <div class="social-icons">
                    <?php if ( ! empty( $social_icons['instagram'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['instagram'] ); ?>" alt="Instagram" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['linkedin'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['linkedin'] ); ?>" alt="LinkedIn" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['facebook'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['facebook'] ); ?>" alt="Facebook" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['youtube'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['youtube'] ); ?>" alt="YouTube" class="social-icon-img">
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-col footer-center">
                <span class="footer-company-name">PUK ITALIA GROUP srl</span>
                Via San Giorgio 16<br>
                20851 Lissone (MB) – ITALY
            </div>
            <div class="footer-col footer-right">
                <span>T.</span> +39 039 24.57.920<br>
                <span>F.</span> +39 039 24.57.924<br>
                puk@puk.it – puk.it
            </div>
        </div>
    </div>

    <!-- FOOTER DATE -->
    <div class="footer-date">
        ultimo aggiornamento <?php echo date( 'd/m/Y' ); ?>
    </div>
</div>
<?php endif; ?>

<?php
$has_gallery = ! empty( $data['images']['sub_gallery_base64'] );

if ( $has_gallery ) :
?>
<div class="page-break"></div>
<div class="wrapper gallery-page">
    <!-- LOGO -->
    <div class="header-logo">
        <?php if ( ! empty( $data['logo_base64'] ) ) : ?>
            <img src="<?php echo esc_attr( $data['logo_base64'] ); ?>" alt="Logo">
        <?php else : ?>
            <span class="logo-text">puk</span>
        <?php endif; ?>
    </div>

    <div class="gallery-grid">
        <div class="section-title">
            <span>Gallery</span>
        </div>
        <?php
        $gallery_chunks = array_chunk( $data['images']['sub_gallery_base64'], 2 );
        foreach ( $gallery_chunks as $row ) :
            ?>
            <div class="gallery-row">
                <?php foreach ( $row as $img_base64 ) : ?>
                    <div class="gallery-item">
                        <div class="gallery-img-container">
                            <div class="gallery-img-cell">
                                <img src="<?php echo esc_attr( $img_base64 ); ?>" alt="Gallery Image">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-col footer-left">
                <div class="social-icons">
                    <?php if ( ! empty( $social_icons['instagram'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['instagram'] ); ?>" alt="Instagram" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['linkedin'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['linkedin'] ); ?>" alt="LinkedIn" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['facebook'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['facebook'] ); ?>" alt="Facebook" class="social-icon-img">
                    <?php endif; ?>
                    <?php if ( ! empty( $social_icons['youtube'] ) ) : ?>
                        <img src="<?php echo esc_attr( $social_icons['youtube'] ); ?>" alt="YouTube" class="social-icon-img">
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-col footer-center">
                <span class="footer-company-name">PUK ITALIA GROUP srl</span>
                Via San Giorgio 16<br>
                20851 Lissone (MB) – ITALY
            </div>
            <div class="footer-col footer-right">
                <span>T.</span> +39 039 24.57.920<br>
                <span>F.</span> +39 039 24.57.924<br>
                puk@puk.it – puk.it
            </div>
        </div>
    </div>

    <!-- FOOTER DATE -->
    <div class="footer-date">
        ultimo aggiornamento <?php echo date( 'd/m/Y' ); ?>
    </div>
</div>
<?php endif; ?>

</body>
</html>
