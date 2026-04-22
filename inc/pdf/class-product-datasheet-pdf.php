<?php
/**
 * Product Datasheet PDF Generator
 *
 * Generates PDF data sheets using DOMPDF.
 *
 * @package PUK
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include DOMPDF autoloader.
require_once get_template_directory() . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Class PUK_Product_Datasheet_PDF
 *
 * Handles PDF generation for product data sheets.
 */
class PUK_Product_Datasheet_PDF {

    /**
     * DOMPDF instance
     *
     * @var Dompdf
     */
    private $dompdf;

    /**
     * Product data
     *
     * @var array
     */
    private $data;

    /**
     * PDF options
     *
     * @var array
     */
    private $pdf_options = array(
        'paper_size'        => 'A4',
        'orientation'       => 'portrait',
        'enable_remote'     => true,
        'enable_html5'      => true,
        'default_font'      => 'helvetica',
        'dpi'               => 150,
        'enable_php'        => false,
        'enable_javascript' => false,
    );

    /**
     * Constructor
     *
     * @param array $data Product data from collector.
     * @param array $options Optional PDF options.
     */
    public function __construct( $data, $options = array() ) {
        $this->data = $data;
        $this->pdf_options = wp_parse_args( $options, $this->pdf_options );
        $this->init_dompdf();
    }

    /**
     * Initialize DOMPDF with options
     */
    private function init_dompdf() {
        $options = new Options();

        $options->set( 'defaultFont', $this->pdf_options['default_font'] );
        $options->set( 'isRemoteEnabled', $this->pdf_options['enable_remote'] );
        $options->set( 'isHtml5ParserEnabled', $this->pdf_options['enable_html5'] );
        $options->set( 'dpi', $this->pdf_options['dpi'] );
        $options->set( 'isPhpEnabled', $this->pdf_options['enable_php'] );
        $options->set( 'isJavascriptEnabled', $this->pdf_options['enable_javascript'] );

        // Set temp and font directories
        $upload_dir = wp_upload_dir();
        $temp_dir = $upload_dir['basedir'] . '/puk-pdf-temp';

        if ( ! file_exists( $temp_dir ) ) {
            wp_mkdir_p( $temp_dir );
        }

        $options->set( 'tempDir', $temp_dir );

        $this->dompdf = new Dompdf( $options );
        $this->dompdf->setPaper( $this->pdf_options['paper_size'], $this->pdf_options['orientation'] );
    }

    /**
     * Generate PDF
     *
     * @return bool True on success.
     */
    public function generate() {
        // Get HTML content from template
        $html = $this->render_template();

        if ( empty( $html ) ) {
            return false;
        }

        // Load HTML into DOMPDF
        $this->dompdf->loadHtml( $html );

        // Render PDF
        $this->dompdf->render();

        return true;
    }

    /**
     * Render HTML template with data
     *
     * @return string HTML content.
     */
    private function render_template() {
        $data = $this->data;

        // Add logo
        $data['logo_base64'] = PUK_PDF_Image_Handler::get_logo_base64();

        // Add generation date
        $data['generated_date'] = current_time( 'F j, Y' );
        $data['generated_time'] = current_time( 'H:i' );

        // Add site info
        $data['site_name'] = get_bloginfo( 'name' );
        $data['site_url'] = home_url();

        // Start output buffering
        ob_start();

        // Include template file
        $template_path = get_template_directory() . '/inc/pdf/templates/datasheet-template.php';

        if ( file_exists( $template_path ) ) {
            include $template_path;
        } else {
            // Fallback to basic template
            $this->render_basic_template( $data );
        }

        return ob_get_clean();
    }

    /**
     * Render basic fallback template
     *
     * @param array $data Product data.
     */
    private function render_basic_template( $data ) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php echo esc_html( $data['basic']['title'] ?? 'Product Datasheet' ); ?></title>
            <style>
                @page {
                    size: A4 portrait;
                    margin: 15mm;
                }
                * {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                }
                body {
                    font-family: Helvetica, Arial, sans-serif;
                    font-size: 10pt;
                    line-height: 1.4;
                    color: #1a1a1a;
                }
                .header {
                    display: table;
                    width: 100%;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #1a1a1a;
                    padding-bottom: 15px;
                }
                .header-left {
                    display: table-cell;
                    width: 30%;
                    vertical-align: middle;
                }
                .header-center {
                    display: table-cell;
                    width: 40%;
                    vertical-align: middle;
                    text-align: center;
                }
                .header-right {
                    display: table-cell;
                    width: 30%;
                    vertical-align: middle;
                    text-align: right;
                }
                .header img {
                    max-height: 50px;
                    max-width: 150px;
                }
                .product-title {
                    font-size: 16pt;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .product-sku {
                    font-size: 12pt;
                    color: #666;
                }
                .content {
                    display: table;
                    width: 100%;
                }
                .col-left {
                    display: table-cell;
                    width: 45%;
                    vertical-align: top;
                    padding-right: 15px;
                }
                .col-right {
                    display: table-cell;
                    width: 55%;
                    vertical-align: top;
                }
                .product-image {
                    text-align: center;
                    margin-bottom: 15px;
                }
                .product-image img {
                    max-width: 100%;
                    max-height: 200px;
                }
                .section-title {
                    font-size: 12pt;
                    font-weight: bold;
                    margin: 15px 0 10px 0;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #ddd;
                }
                .specs-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .specs-table td {
                    padding: 5px 8px;
                    border-bottom: 1px solid #eee;
                    vertical-align: top;
                }
                .specs-table .label {
                    width: 40%;
                    font-weight: bold;
                    color: #666;
                }
                .specs-table .value {
                    width: 60%;
                }
                .footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 30px;
                    text-align: center;
                    font-size: 8pt;
                    color: #999;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
                .page-number:after {
                    content: counter(page);
                }
            </style>
        </head>
        <body>
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <?php if ( ! empty( $data['logo_base64'] ) ) : ?>
                        <img src="<?php echo esc_attr( $data['logo_base64'] ); ?>" alt="Logo">
                    <?php endif; ?>
                </div>
                <div class="header-center">
                    <div class="product-title">
                        <?php echo esc_html( $data['basic']['title'] ?? '' ); ?>
                    </div>
                    <?php if ( ! empty( $data['basic']['current_family'] ) ) : ?>
                        <div style="font-size: 9pt; color: #666;">
                            <?php echo esc_html( $data['basic']['main_category'] ?? '' ); ?> /
                            <?php echo esc_html( $data['basic']['parent_family'] ?? '' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="header-right">
                    <?php if ( ! empty( $data['basic']['sku'] ) ) : ?>
                        <div class="product-sku"><?php echo esc_html( $data['basic']['sku'] ); ?></div>
                    <?php endif; ?>
                    <div style="font-size: 8pt; color: #999; margin-top: 5px;">
                        <?php echo esc_html( $data['generated_date'] ); ?>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="col-left">
                    <!-- Product Image -->
                    <?php if ( ! empty( $data['images']['main_image_base64'] ) ) : ?>
                        <div class="product-image">
                            <img src="<?php echo esc_attr( $data['images']['main_image_base64'] ); ?>" alt="Product">
                        </div>
                    <?php endif; ?>

                    <!-- Description -->
                    <?php if ( ! empty( $data['basic']['description'] ) ) : ?>
                        <div class="section-title">Description</div>
                        <p style="font-size: 9pt; color: #444;">
                            <?php echo esc_html( $data['basic']['description'] ); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="col-right">
                    <!-- Specifications -->
                    <?php if ( ! empty( $data['specifications'] ) ) : ?>
                        <div class="section-title">Specifications</div>
                        <table class="specs-table">
                            <?php foreach ( $data['specifications'] as $key => $spec ) : ?>
                                <?php if ( ! empty( $spec['value'] ) ) : ?>
                                    <tr>
                                        <td class="label"><?php echo esc_html( $spec['label'] ); ?></td>
                                        <td class="value"><?php echo esc_html( $spec['value'] ); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Technical Drawing -->
            <?php if ( ! empty( $data['images']['tech_drawing_base64'] ) ) : ?>
                <div style="margin-top: 20px; page-break-inside: avoid;">
                    <div class="section-title">Technical Drawing</div>
                    <div style="text-align: center;">
                        <img src="<?php echo esc_attr( $data['images']['tech_drawing_base64'] ); ?>"
                             alt="Technical Drawing"
                             style="max-width: 100%; max-height: 250px;">
                    </div>
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="footer">
                <?php echo esc_html( $data['site_name'] ); ?> | <?php echo esc_html( $data['site_url'] ); ?> | Page <span class="page-number"></span>
            </div>
        </body>
        </html>
        <?php
    }

    /**
     * Output PDF to browser for download
     *
     * @param string $filename Filename for download.
     */
    public function download( $filename = '' ) {
        if ( empty( $filename ) ) {
            $filename = $this->generate_filename();
        }

        // Ensure .pdf extension
        if ( substr( $filename, -4 ) !== '.pdf' ) {
            $filename .= '.pdf';
        }

        // Stream PDF
        $this->dompdf->stream( $filename, array(
            'Attachment' => true,
        ) );
    }

    /**
     * Output PDF inline (in browser)
     *
     * @param string $filename Filename.
     */
    public function inline( $filename = '' ) {
        if ( empty( $filename ) ) {
            $filename = $this->generate_filename();
        }

        if ( substr( $filename, -4 ) !== '.pdf' ) {
            $filename .= '.pdf';
        }

        $this->dompdf->stream( $filename, array(
            'Attachment' => false,
        ) );
    }

    /**
     * Get PDF as string
     *
     * @return string PDF content.
     */
    public function get_output() {
        return $this->dompdf->output();
    }

    /**
     * Save PDF to file
     *
     * @param string $file_path Path to save PDF.
     * @return bool True on success.
     */
    public function save( $file_path ) {
        $output = $this->dompdf->output();

        if ( empty( $output ) ) {
            return false;
        }

        return (bool) file_put_contents( $file_path, $output );
    }

    /**
     * Generate filename for PDF
     *
     * @return string Filename.
     */
    private function generate_filename() {
        $sku = $this->data['basic']['sku'] ?? '';
        $title = $this->data['basic']['title'] ?? 'product';

        if ( ! empty( $sku ) ) {
            return sanitize_file_name( $sku . '-datasheet' );
        }

        return sanitize_file_name( $title . '-datasheet' );
    }

    /**
     * Get DOMPDF instance
     *
     * @return Dompdf
     */
    public function get_dompdf() {
        return $this->dompdf;
    }
}
