<?php
/**
 * Bulk Image Assign Dashboard UI
 * 
 * Handles Admin Menu registration and the main dashboard view for image assignment.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Puk_Image_Assign_Admin {

    public function __construct() {
        // Admin Menu
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
    }

    /**
     * Registers the "Bulk Image Assign" submenu under "Products".
     */
    public function register_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=product',
            __( 'Bulk Image Assign', 'puk' ),
            __( 'Bulk Image Assign', 'puk' ),
            'manage_options',
            'puk-bulk-image-assign',
            [ $this, 'render_admin_page' ]
        );
    }

    /**
     * Renders the Bulk Image Assign Admin Page.
     */
    public function render_admin_page() {
        ?>
<div class="wrap">
    <h1><?php _e( 'Bulk Image Assign', 'puk' ); ?></h1>

    <style>
    .puk-image-assign-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .puk-image-assign-card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        padding: 20px;
    }

    .puk-image-assign-card h2 {
        margin-top: 0;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
    }

    @media screen and (max-width: 1024px) {
        .puk-image-assign-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <div class="puk-image-assign-grid">
        <!-- Main Image Assign Section -->
        <div class="puk-image-assign-card" style="grid-column: span 2;">
            <h2><?php _e( 'Bulk Image Scanner', 'puk' ); ?></h2>
            <p><?php _e( 'This tool will scan the folder <code>/wp-content/uploads/puk-import/</code> and automatically assign images to products or taxonomies based on their filenames.', 'puk' ); ?></p>
            
            <div class="puk-scanner-instructions">
                <h3><?php _e( 'Naming Convention:', 'puk' ); ?></h3>
                <ul>
                    <li><strong><?php _e( 'Sub Families (Hierarchical):', 'puk' ); ?></strong> <code>fam__[Family]__[SubFamily]__[Suffix].jpg</code></li>
                    <li><strong><?php _e( 'Sub Families (Code Match):', 'puk' ); ?></strong> <code>sf__[SubFamily]__[FamilyCode]__[Suffix].jpg</code> (e.g., <code>sf__micro-hp__101601__tech.webp</code>)</li>
                    <li><strong><?php _e( 'Galleries:', 'puk' ); ?></strong>
                        <ul style="margin-top: 5px; margin-left: 20px;">
                            <li><code>sf__[SubFamily]__[Code]__gallery-1.jpg</code> → Sub-family gallery (pf_subfam_product_image)</li>
                            <li><code>sf__[SubFamily]__[Code]__gallery2-1.jpg</code> → Product gallery (pro_gallary for ALL products in sub-family)</li>
                            <li><code>sf__[SubFamily]__[Code]__gallery3-1.jpg</code> → Product sub-gallery (pro_sub_gallary for ALL products in sub-family)</li>
                        </ul>
                    </li>
                    <li><strong><?php _e( 'Single Image Suffixes:', 'puk' ); ?></strong> <code>main</code>, <code>hover</code>, <code>tech</code>, <code>designer</code></li>
                    <li><strong><?php _e( 'Accessories:', 'puk' ); ?></strong> <code>acc__[AccessoryCode].jpg</code> (e.g., <code>acc__AC044.webp</code> → tax_acc_ft__img)</li>
                    <li><strong><?php _e( 'Products (by SKU):', 'puk' ); ?></strong> <code>[SKU]__[FieldSlug].jpg</code> or <code>[SKU].jpg</code></li>
                </ul>
            </div>

            <div id="puk-assign-progress-output" style="margin-bottom: 10px; padding: 10px; background: #f0f0f1; border-left: 4px solid #0073aa; display:none;"></div>
            <div class="puk-progress-bar" id="puk-assign-progress-wrap" style="display:none; background:#eee; border-radius:4px; height:20px; margin-bottom:10px;">
                <div id="puk-assign-progress" style="background:#0073aa; height:100%; width:0%; border-radius:4px; transition: width 0.3s;"></div>
            </div>

            <button type="button" class="button button-primary" id="puk-run-image-scan"><?php _e( 'Run Image Scan & Assign', 'puk' ); ?></button>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        const puk_ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
        const puk_image_nonce = '<?php echo wp_create_nonce("puk_image_assign_nonce"); ?>';

        $('#puk-run-image-scan').on('click', function() {
            if (!confirm('Are you sure you want to scan /wp-content/uploads/puk-import/ and assign images?')) return;

            const btn = $(this);
            const progressWrap = $('#puk-assign-progress-wrap');
            const progressBar = $('#puk-assign-progress');
            const output = $('#puk-assign-progress-output');

            btn.prop('disabled', true);
            progressWrap.show();
            output.show().html('Scanning folder...');

            $.post(puk_ajax_url, {
                action: 'puk_image_assign_scan',
                _ajax_nonce: puk_image_nonce
            }, function(response) {
                if (response.success) {
                    const files = response.data.files;
                    const total = files.length;
                    let index = 0;

                    if (total === 0) {
                        output.html('No images found in /wp-content/uploads/puk-import/');
                        btn.prop('disabled', false);
                        return;
                    }

                    function processNext() {
                        if (index >= total) {
                            output.append('<br><strong>Assignment Complete!</strong>');
                            btn.prop('disabled', false);
                            return;
                        }

                        const file = files[index];
                        output.html(`Processing ${index + 1} of ${total}: <code>${file}</code>...`);
                        
                        $.post(puk_ajax_url, {
                            action: 'puk_image_assign_process_file',
                            filename: file,
                            _ajax_nonce: puk_image_nonce
                        }, function(res) {
                            if (res.success) {
                                index++;
                                const percent = (index / total) * 100;
                                progressBar.css('width', percent + '%');
                                processNext();
                            } else {
                                output.append(`<br><span style="color:red">Error processing ${file}: ${res.data}</span>`);
                                index++;
                                processNext();
                            }
                        });
                    }

                    processNext();
                } else {
                    output.html('<span style="color:red">Error: ' + response.data + '</span>');
                    btn.prop('disabled', false);
                }
            });
        });
    });
    </script>
</div>
<?php
    }
}

// Initialize the dashboard
new Puk_Image_Assign_Admin();
