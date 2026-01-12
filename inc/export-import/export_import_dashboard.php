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
            <p><?php _e( 'Download all products and their metadata as a CSV file (processed in batches).', 'puk' ); ?></p>
            <div id="puk-export-progress-output" style="margin-bottom: 10px;"></div>
            <div class="puk-progress-bar" id="puk-export-progress-wrap" style="display:none; background:#eee; border-radius:4px; height:20px; margin-bottom:10px;">
                <div id="puk-export-progress" style="background:#0073aa; height:100%; width:0%; border-radius:4px; transition: width 0.3s;"></div>
            </div>
            <button type="button" class="button button-primary" id="puk-run-export"><?php _e( 'Export All Products', 'puk' ); ?></button>

            <!-- Import Products -->
            <h3><?php _e( 'Import Products', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import products. (processed in batches of 30).', 'puk' ); ?></p>
            <div id="puk-import-progress-output" style="margin-bottom: 10px;"></div>
            <div class="puk-progress-bar" id="puk-import-progress-wrap" style="display:none; background:#eee; border-radius:4px; height:20px; margin-bottom:10px;">
                <div id="puk-import-progress" style="background:#0073aa; height:100%; width:0%; border-radius:4px; transition: width 0.3s;"></div>
            </div>
            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="import_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                    <td><input type="file" id="puk-import-file" accept=".csv"></td>
                </tr>
            </table>
            <button type="button" class="button button-secondary" id="puk-run-import"><?php _e( 'Run Batch Import', 'puk' ); ?></button>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js"></script>
            <script>
            jQuery(document).ready(function($) {
                // Ensure ajaxurl is defined correctly
                const puk_ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
                const puk_export_nonce = '<?php echo wp_create_nonce("puk_export_nonce"); ?>';
                const puk_import_nonce = '<?php echo wp_create_nonce("puk_import_nonce"); ?>';

                // Export Logic
                $('#puk-run-export').on('click', function() {
                    const btn = $(this);
                    const progressWrap = $('#puk-export-progress-wrap');
                    const progressBar = $('#puk-export-progress');
                    const output = $('#puk-export-progress-output');
                    
                    btn.prop('disabled', true);
                    progressWrap.show();
                    progressBar.css('width', '0%');
                    output.html('Starting export...');

                    $.post(puk_ajax_url, {
                        action: 'puk_get_export_count',
                        _ajax_nonce: puk_export_nonce
                    }, function(response) {
                        if (response.success) {
                            const total = response.data.total;
                            const headers = response.data.headers;
                            let offset = 0;
                            let allRows = [];

                            function fetchBatch() {
                                output.html(`Fetching products ${offset} to ${Math.min(offset + 20, total)} of ${total}...`);
                                $.post(puk_ajax_url, {
                                    action: 'puk_export_products_batch',
                                    offset: offset,
                                    _ajax_nonce: puk_export_nonce
                                }, function(res) {
                                    if (res.success) {
                                        allRows = allRows.concat(res.data.rows);
                                        offset += 20;
                                        const percent = Math.min((offset / total) * 100, 100);
                                        progressBar.css('width', percent + '%');

                                        if (offset < total) {
                                            fetchBatch();
                                        } else {
                                            output.html('Generating CSV...');
                                            const csv = Papa.unparse({
                                                fields: headers,
                                                data: allRows
                                            });
                                            const blob = new Blob(["\ufeff" + csv], { type: 'text/csv;charset=utf-8;' });
                                            const link = document.createElement("a");
                                            const url = URL.createObjectURL(blob);
                                            link.setAttribute("href", url);
                                            link.setAttribute("download", `product-export-${new Date().toISOString().split('T')[0]}.csv`);
                                            document.body.appendChild(link);
                                            link.click();
                                            document.body.removeChild(link);
                                            output.html('Export complete!');
                                            btn.prop('disabled', false);
                                        }
                                    } else {
                                        output.html('<span style="color:red">Error: ' + res.data + '</span>');
                                        btn.prop('disabled', false);
                                    }
                                });
                            }
                            fetchBatch();
                        } else {
                            output.html('<span style="color:red">Error: ' + response.data + '</span>');
                            btn.prop('disabled', false);
                        }
                    });
                });

                // Import Logic
                $('#puk-run-import').on('click', function() {
                    const fileInput = $('#puk-import-file')[0];
                    if (!fileInput.files.length) {
                        alert('Please select a CSV file.');
                        return;
                    }

                    const btn = $(this);
                    const progressWrap = $('#puk-import-progress-wrap');
                    const progressBar = $('#puk-import-progress');
                    const output = $('#puk-import-progress-output');
                    
                    btn.prop('disabled', true);
                    progressWrap.show();
                    progressBar.css('width', '0%');
                    output.html('Parsing CSV...');

                    Papa.parse(fileInput.files[0], {
                        header: true,
                        skipEmptyLines: true,
                        transformHeader: function(h) {
                            return h.trim().toLowerCase();
                        },
                        complete: function(results) {
                            const data = results.data;
                            const total = data.length;
                            let index = 0;
                            const batchSize = 20;

                            function sendBatch() {
                                const chunk = data.slice(index, index + batchSize);
                                output.html(`Importing rows ${index + 1} to ${Math.min(index + batchSize, total)} of ${total}...`);
                                
                                $.post(puk_ajax_url, {
                                    action: 'puk_import_products_batch',
                                    batch_data: JSON.stringify(chunk),
                                    start_row: index + 1,
                                    _ajax_nonce: puk_import_nonce
                                }, function(res) {
                                    if (res.success) {
                                        index += batchSize;
                                        const percent = Math.min((index / total) * 100, 100);
                                        progressBar.css('width', percent + '%');

                                        if (index < total) {
                                            sendBatch();
                                        } else {
                                            output.html('Import complete!');
                                            btn.prop('disabled', false);
                                            alert('Import finished successfully.');
                                        }
                                    } else {
                                        output.html('<span style="color:red">Error: ' + res.data + '</span>');
                                        btn.prop('disabled', false);
                                    }
                                });
                            }
                            sendBatch();
                        },
                        error: function(err) {
                            output.html('<span style="color:red">CSV Parse Error: ' + err.message + '</span>');
                            btn.prop('disabled', false);
                        }
                    });
                });
            });
            </script>
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
