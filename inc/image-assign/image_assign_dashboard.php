<?php
/**
 * Bulk Image Assign Dashboard UI - Folder Pattern
 *
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Puk_Image_Assign_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
    }

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

    public function render_admin_page() {
        ?>
<div class="wrap">
    <h1><?php _e( 'Bulk Image Assign', 'puk' ); ?></h1>

    <style>
    .puk-card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin-top: 20px;
        max-width: 1000px;
    }
    .puk-card h2 {
        margin-top: 0;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
    }
    .puk-folder-box {
        background: #f6f7f7;
        padding: 15px;
        border-radius: 4px;
        font-family: monospace;
        margin: 10px 0;
        font-size: 13px;
    }
    .puk-folder-box .folder { color: #0073aa; font-weight: bold; }
    .puk-folder-box .file { color: #23282d; }
    .puk-folder-box .comment { color: #666; font-style: italic; }
    .puk-folder-box .code { color: #d63638; font-weight: bold; }
    .puk-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin: 15px 0; }
    .puk-grid-item { background: #fafafa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 15px; }
    .puk-grid-item h4 { margin: 0 0 10px; color: #0073aa; }
    @media (max-width: 1200px) { .puk-grid { grid-template-columns: 1fr; } }
    .puk-table td, .puk-table th { padding: 6px 10px; text-align: left; border-bottom: 1px solid #e5e5e5; }
    .puk-table th { background: #f6f7f7; }
    </style>

    <div class="puk-card">
        <h2>Folder Pattern Image Scanner</h2>
        <p>Scans <code>/wp-content/uploads/puk-import/</code> and assigns images based on folder structure.</p>

        <div class="puk-grid">
            <!-- Product Family -->
            <div class="puk-grid-item">
                <h4>1. Product Family Taxonomy</h4>
                <div class="puk-folder-box">
<span class="folder">Floodlights/</span> <span class="comment">← Level 0</span>
  └─ <span class="folder">Qubo/</span> <span class="comment">← Level 1</span>
      ├─ <span class="file">main.jpg</span>
      └─ <span class="folder">Micro/</span> <span class="comment">← Level 2</span>
          └─ <span class="folder code">101601/</span> <span class="comment">← family_code</span>
              └─ <span class="file">main.jpg</span>
                </div>
                <table class="puk-table" style="width:100%">
                    <tr><th>File</th><th>Field</th></tr>
                    <tr><td>main</td><td>pf_fet_img</td></tr>
                    <tr><td>hover</td><td>pf_hover_img</td></tr>
                    <tr><td>tech</td><td>pf_subfam_tech_drawing</td></tr>
                    <tr><td>gallery-*</td><td>pf_subfam_product_image</td></tr>
                    <tr><td>designer</td><td>pf_designed_by</td></tr>
                </table>
            </div>

            <!-- Accessories -->
            <div class="puk-grid-item">
                <h4>2. Accessories Taxonomy</h4>
                <div class="puk-folder-box">
<span class="folder">Accessories/</span>
  ├─ <span class="file code">AC044</span><span class="file">.jpg</span> <span class="comment">← tax_acc__code</span>
  ├─ <span class="file code">AC075</span><span class="file">.jpg</span>
  └─ <span class="file code">AC076</span><span class="file">.jpg</span>
                </div>
                <p style="margin-top:10px;font-size:12px;color:#666;">
                    <strong>Filename</strong> = Accessory code (tax_acc__code)<br>
                    <strong>Assigns to:</strong> tax_acc_ft__img field
                </p>
            </div>

            <!-- Products -->
            <div class="puk-grid-item">
                <h4>3. Products (by SKU)</h4>
                <div class="puk-folder-box">
<span class="folder">Products/</span>
  └─ <span class="folder code">SKU123/</span> <span class="comment">← prod__sku</span>
      ├─ <span class="file">main.jpg</span> <span class="comment">← Featured</span>
      └─ <span class="file">gallery-1.jpg</span>
                </div>
                <table class="puk-table" style="width:100%">
                    <tr><th>File</th><th>Field</th></tr>
                    <tr><td>main / featured</td><td>Featured Image</td></tr>
                    <tr><td>gallery-*</td><td>pro_gallary</td></tr>
                    <tr><td>gallery2-*</td><td>pro_sub_gallary</td></tr>
                </table>
            </div>
        </div>

        <div id="puk-output" style="margin: 15px 0; padding: 10px; background: #f0f0f1; border-left: 4px solid #0073aa; display:none;"></div>
        <div id="puk-progress-wrap" style="display:none; background:#eee; border-radius:4px; height:20px; margin-bottom:10px;">
            <div id="puk-progress" style="background:#0073aa; height:100%; width:0%; border-radius:4px; transition: width 0.3s;"></div>
        </div>

        <button type="button" class="button button-primary" id="puk-run-scan">Run Image Scan & Assign</button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        const ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';
        const nonce = '<?php echo wp_create_nonce("puk_image_assign_nonce"); ?>';

        $('#puk-run-scan').on('click', function() {
            if (!confirm('Scan puk-import folder and assign images?')) return;

            const btn = $(this).prop('disabled', true);
            const output = $('#puk-output').show().html('Scanning...');
            const progressWrap = $('#puk-progress-wrap').show();
            const progress = $('#puk-progress').css('width', '0%');

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                timeout: 60000,
                data: { action: 'puk_image_assign_scan', _ajax_nonce: nonce },
                success: function(res) {
                    if (!res.success) {
                        output.html('<span style="color:red">' + res.data + '</span>');
                        btn.prop('disabled', false);
                        return;
                    }

                    const files = res.data.files;
                    const total = files.length;
                    let i = 0, results = [], retryCount = 0;

                    if (!total) {
                        output.html('No images found.');
                        btn.prop('disabled', false);
                        return;
                    }

                    output.html('Found ' + total + ' images. Processing...');

                    function processNext() {
                        if (i >= total) {
                            let ok = results.filter(r => r.ok).length;
                            let fail = results.filter(r => !r.ok).length;
                            let html = '<strong>Done!</strong> ' + ok + ' succeeded, ' + fail + ' failed.';
                            // Only show failed items
                            let failed = results.filter(r => !r.ok);
                            if (failed.length > 0) {
                                html += '<br><br><strong>Failed:</strong><br>';
                                failed.forEach(r => {
                                    html += '<span style="color:red">✗</span> ' + r.file + ': ' + r.msg + '<br>';
                                });
                            }
                            output.html(html);
                            btn.prop('disabled', false);
                            return;
                        }

                        let statusMsg = 'Processing ' + (i+1) + '/' + total + ': ' + files[i];
                        if (retryCount > 0) {
                            statusMsg += ' <span style="color:orange">(retry ' + retryCount + ')</span>';
                        }
                        output.html(statusMsg);

                        $.ajax({
                            url: ajaxUrl,
                            type: 'POST',
                            timeout: 120000, // 2 minute timeout per image
                            data: { action: 'puk_image_assign_process_file', filename: files[i], _ajax_nonce: nonce },
                            success: function(r) {
                                retryCount = 0;
                                // Server responded - record result and move to next (don't retry server errors)
                                results.push({ file: files[i], ok: r.success, msg: r.data });
                                progress.css('width', (++i / total * 100) + '%');
                                setTimeout(processNext, 300);
                            },
                            error: function(xhr, status, error) {
                                // Network/timeout error - retry indefinitely
                                retryCount++;
                                console.log('Image assign AJAX error:', files[i], status, error, 'Retry:', retryCount);
                                const delay = Math.min(2000 * retryCount, 20000);
                                output.html('<span style="color:orange">Network error (' + status + '), retrying in ' + (delay/1000) + 's... (attempt ' + retryCount + ')</span>');
                                setTimeout(processNext, delay);
                            }
                        });
                    }
                    processNext();
                },
                error: function() {
                    output.html('<span style="color:red">Scan request failed</span>');
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

new Puk_Image_Assign_Admin();
