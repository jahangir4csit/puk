<?php
/**
 * AJAX Handler for PDF Generation
 *
 * Handles AJAX requests to generate product data sheet PDFs.
 *
 * @package PUK
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class PUK_PDF_Ajax_Handler
 *
 * Handles AJAX requests for PDF generation.
 */
class PUK_PDF_Ajax_Handler {

    /**
     * Instance
     *
     * @var PUK_PDF_Ajax_Handler
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return PUK_PDF_Ajax_Handler
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // AJAX actions for logged in users
        add_action( 'wp_ajax_puk_generate_product_pdf', array( $this, 'generate_pdf' ) );

        // AJAX actions for non-logged in users
        add_action( 'wp_ajax_nopriv_puk_generate_product_pdf', array( $this, 'generate_pdf' ) );

        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only on single product pages
        if ( ! is_singular( 'product' ) && ! is_tax( 'product-family' ) ) {
            return;
        }

        wp_enqueue_script(
            'puk-pdf-generator',
            get_template_directory_uri() . '/assets/js/pdf-generator.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );

        wp_localize_script(
            'puk-pdf-generator',
            'pukPdfGenerator',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'puk_generate_pdf_nonce' ),
                'i18n'    => array(
                    'generating' => __( 'Generating...', 'puk' ),
                    'download'   => __( 'Data Sheet', 'puk' ),
                    'error'      => __( 'Error generating PDF. Please try again.', 'puk' ),
                ),
            )
        );
    }

    /**
     * Generate PDF AJAX handler
     */
    public function generate_pdf() {
        // Verify nonce
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'puk_generate_pdf_nonce' ) ) {
            wp_send_json_error( array(
                'message' => __( 'Security check failed.', 'puk' ),
            ) );
        }

        // Get product ID
        $product_id = isset( $_REQUEST['product_id'] ) ? absint( $_REQUEST['product_id'] ) : 0;

        if ( ! $product_id ) {
            wp_send_json_error( array(
                'message' => __( 'Invalid product ID.', 'puk' ),
            ) );
        }

        // Verify product exists
        $product = get_post( $product_id );

        if ( ! $product || 'product' !== $product->post_type ) {
            wp_send_json_error( array(
                'message' => __( 'Product not found.', 'puk' ),
            ) );
        }

        try {
            // Collect product data
            $collector = new PUK_Product_Data_Collector( $product_id );
            $data = $collector->collect_all();

            if ( empty( $data ) ) {
                wp_send_json_error( array(
                    'message' => __( 'Could not collect product data.', 'puk' ),
                ) );
            }

            // Process images (convert to base64)
            $data = PUK_PDF_Image_Handler::process_product_images( $data );

            // Generate PDF
            $pdf = new PUK_Product_Datasheet_PDF( $data );

            if ( ! $pdf->generate() ) {
                wp_send_json_error( array(
                    'message' => __( 'Could not generate PDF.', 'puk' ),
                ) );
            }

            // Get filename
            $filename = $this->get_filename( $data );

            // Clear output buffer
            if ( ob_get_level() ) {
                ob_end_clean();
            }

            // Output PDF
            $pdf->download( $filename );
            exit;

        } catch ( Exception $e ) {
            wp_send_json_error( array(
                'message' => $e->getMessage(),
            ) );
        }
    }

    /**
     * Generate filename for PDF
     *
     * @param array $data Product data.
     * @return string Filename.
     */
    private function get_filename( $data ) {
        $sku = $data['basic']['sku'] ?? '';
        $title = $data['basic']['title'] ?? 'product';

        if ( ! empty( $sku ) ) {
            return sanitize_file_name( $sku . '-datasheet.pdf' );
        }

        $product_id = $data['basic']['id'] ?? 0;
        if ( $product_id ) {
            return sanitize_file_name( 'product-' . $product_id . '-datasheet.pdf' );
        }

        return sanitize_file_name( $title . '-datasheet.pdf' );
    }
}

// Initialize
PUK_PDF_Ajax_Handler::get_instance();
