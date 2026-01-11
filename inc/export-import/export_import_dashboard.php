<?php
/**
 * Export/Import Dashboard UI
 * 
 * Handles Admin Menu registration and the main dashboard view.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Puk_Export_Import_Admin {

    public function __construct() {
        // Admin Menu
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
    }

    /**
     * Registers the "Import/Export" submenu under "Products".
     */
    public function register_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=product',
            __( 'Import/Export Product', 'puk' ),
            __( 'Import/Export', 'puk' ),
            'manage_options',
            'puk-product-import-export',
            [ $this, 'render_admin_page' ]
        );
    }

    /**
     * Renders the unified Import/Export Admin Page.
     */
    public function render_admin_page() {
        ?>
<div class="wrap">
    <h1><?php _e( 'Import/Export Products', 'puk' ); ?></h1>

    <style>
    .puk-import-export-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .puk-import-export-card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        padding: 20px;
    }

    .puk-import-export-card h2 {
        margin-top: 0;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
    }

    @media screen and (max-width: 1024px) {
        .puk-import-export-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <div class="puk-import-export-grid">
        <!-- Products Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Products', 'puk' ); ?></h2>

            <!-- Export Products -->
            <h3><?php _e( 'Export Products', 'puk' ); ?></h3>
            <p><?php _e( 'Download all products and their metadata as a CSV file.', 'puk' ); ?></p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_products">
                <?php wp_nonce_field( 'puk_export_nonce', '_wpnonce_export' ); ?>
                <?php submit_button( __( 'Export All Products', 'puk' ), 'primary', 'submit_export_products' ); ?>
            </form>

            <!-- Import Products -->
            <h3><?php _e( 'Import Products', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import products. Ensure headers match exactly.', 'puk' ); ?></p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_products">
                <?php wp_nonce_field( 'puk_import_nonce', '_wpnonce_import' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="import_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_file" id="import_file" accept=".csv" required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_products' ); ?>
            </form>
        </div>

        <!-- Products Family Taxonomy Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Product Family Taxonomy', 'puk' ); ?></h2>

            <!-- Export Taxonomy -->
            <h3><?php _e( 'Export Taxonomy', 'puk' ); ?></h3>
            <p><?php _e( 'Download all product-family taxonomy terms and their ACF fields as a CSV file.', 'puk' ); ?>
            </p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_taxonomy">
                <?php wp_nonce_field( 'puk_export_taxonomy_nonce', '_wpnonce_export_taxonomy' ); ?>
                <?php submit_button( __( 'Export Taxonomy', 'puk' ), 'primary', 'submit_export_taxonomy' ); ?>
            </form>

            <!-- Import Taxonomy -->
            <h3><?php _e( 'Import Taxonomy', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import product-family taxonomy terms. Ensure headers match exactly.', 'puk' ); ?>
            </p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_taxonomy">
                <?php wp_nonce_field( 'puk_import_taxonomy_nonce', '_wpnonce_import_taxonomy' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label
                                for="import_taxonomy_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_taxonomy_file" id="import_taxonomy_file" accept=".csv"
                                required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_taxonomy' ); ?>
            </form>
        </div>

        <!-- Finish Color Taxonomy Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Finish Color Taxonomy', 'puk' ); ?></h2>

            <!-- Export Finish Color -->
            <h3><?php _e( 'Export Finish Color', 'puk' ); ?></h3>
            <p><?php _e( 'Download all finish-color taxonomy terms and their ACF fields as a CSV file.', 'puk' ); ?></p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_finish_color">
                <?php wp_nonce_field( 'puk_export_finish_color_nonce', '_wpnonce_export_finish_color' ); ?>
                <?php submit_button( __( 'Export Finish Color', 'puk' ), 'primary', 'submit_export_finish_color' ); ?>
            </form>

            <!-- Import Finish Color -->
            <h3><?php _e( 'Import Finish Color', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import finish-color taxonomy terms. Ensure headers match exactly.', 'puk' ); ?>
            </p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_finish_color">
                <?php wp_nonce_field( 'puk_import_finish_color_nonce', '_wpnonce_import_finish_color' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label
                                for="import_finish_color_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_finish_color_file" id="import_finish_color_file"
                                accept=".csv" required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_finish_color' ); ?>
            </form>
        </div>

        <!-- Accessories Taxonomy Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Accessories Taxonomy', 'puk' ); ?></h2>

            <!-- Export Accessories -->
            <h3><?php _e( 'Export Accessories', 'puk' ); ?></h3>
            <p><?php _e( 'Download all accessories taxonomy terms and their ACF fields as a CSV file.', 'puk' ); ?></p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_accessories">
                <?php wp_nonce_field( 'puk_export_accessories_nonce', '_wpnonce_export_accessories' ); ?>
                <?php submit_button( __( 'Export Accessories', 'puk' ), 'primary', 'submit_export_accessories' ); ?>
            </form>

            <!-- Import Accessories -->
            <h3><?php _e( 'Import Accessories', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import accessories taxonomy terms. Ensure headers match exactly.', 'puk' ); ?>
            </p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_accessories">
                <?php wp_nonce_field( 'puk_import_accessories_nonce', '_wpnonce_import_accessories' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label
                                for="import_accessories_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_accessories_file" id="import_accessories_file"
                                accept=".csv" required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_accessories' ); ?>
            </form>
        </div>

        <!-- Features Taxonomy Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Features Taxonomy', 'puk' ); ?></h2>

            <!-- Export Features -->
            <h3><?php _e( 'Export Features', 'puk' ); ?></h3>
            <p><?php _e( 'Download all features taxonomy terms and their ACF fields as a CSV file.', 'puk' ); ?></p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_features">
                <?php wp_nonce_field( 'puk_export_features_nonce', '_wpnonce_export_features' ); ?>
                <?php submit_button( __( 'Export Features', 'puk' ), 'primary', 'submit_export_features' ); ?>
            </form>

            <!-- Import Features -->
            <h3><?php _e( 'Import Features', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import features taxonomy terms. Ensure headers match exactly.', 'puk' ); ?>
            </p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_features">
                <?php wp_nonce_field( 'puk_import_features_nonce', '_wpnonce_import_features' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label
                                for="import_features_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_features_file" id="import_features_file"
                                accept=".csv" required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_features' ); ?>
            </form>
        </div>
    </div>
</div>
<?php
    }
}

// Initialize the dashboard
new Puk_Export_Import_Admin();
