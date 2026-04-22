<?php
/**
 * Smart Rewrite Rules Flush Handler
 *
 * Only flushes rewrite rules when necessary, avoiding performance issues
 * from flushing on every page load.
 *
 * Triggers flush on:
 * - Theme activation/switch
 * - Product create/update/delete
 * - Product-family term create/update/delete
 * - Data import completion
 * - Manual admin action
 * - Site migration detection
 *
 * @package PUK
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Schedule a rewrite rules flush for the next page load
 * Uses a transient to avoid flushing multiple times
 */
function puk_schedule_flush_rewrite_rules() {
    set_transient('puk_flush_rewrite_rules', true, HOUR_IN_SECONDS);
}

/**
 * Execute the flush if scheduled
 * Runs on init with high priority to ensure CPT/taxonomies are registered first
 */
function puk_maybe_flush_rewrite_rules() {
    if (get_transient('puk_flush_rewrite_rules')) {
        // Delete transient first to prevent infinite loops
        delete_transient('puk_flush_rewrite_rules');

        // Ensure custom rewrite rules are added
        if (function_exists('puk_woocommerce_style_rewrite_rules')) {
            puk_woocommerce_style_rewrite_rules();
        }
        if (function_exists('puk_product_rewrite_rules')) {
            puk_product_rewrite_rules();
        }

        flush_rewrite_rules();

        // Log for debugging (optional - can be removed in production)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('PUK: Rewrite rules flushed at ' . current_time('mysql'));
        }
    }
}
add_action('init', 'puk_maybe_flush_rewrite_rules', 9999);

/**
 * ========================================
 * TRIGGER: Theme Activation/Switch
 * ========================================
 */
function puk_flush_on_theme_switch() {
    puk_schedule_flush_rewrite_rules();
}
add_action('after_switch_theme', 'puk_flush_on_theme_switch');

/**
 * ========================================
 * TRIGGER: Product Changes
 * ========================================
 */
function puk_flush_on_product_change($post_id, $post = null, $update = false) {
    // Get post type
    $post_type = get_post_type($post_id);

    // Only trigger for 'product' post type
    if ($post_type !== 'product') {
        return;
    }

    // Don't trigger on autosave or revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id)) {
        return;
    }

    puk_schedule_flush_rewrite_rules();
}
add_action('save_post', 'puk_flush_on_product_change', 10, 3);
add_action('delete_post', 'puk_flush_on_product_change');
add_action('trash_post', 'puk_flush_on_product_change');
add_action('untrash_post', 'puk_flush_on_product_change');

/**
 * ========================================
 * TRIGGER: Taxonomy Term Changes
 * ========================================
 */
function puk_flush_on_term_change($term_id, $tt_id = 0, $taxonomy = '') {
    // Only trigger for 'product-family' taxonomy
    if ($taxonomy !== 'product-family') {
        return;
    }

    puk_schedule_flush_rewrite_rules();
}
add_action('created_term', 'puk_flush_on_term_change', 10, 3);
add_action('edited_term', 'puk_flush_on_term_change', 10, 3);
add_action('delete_term', 'puk_flush_on_term_change', 10, 3);

/**
 * ========================================
 * TRIGGER: PUK Custom Import Completion
 * ========================================
 */

/**
 * Hook into PUK Product Import AJAX batch completion
 * Fires after each successful batch import
 */
function puk_flush_after_product_import_batch() {
    // Only schedule once per import session using a short-lived transient
    if (!get_transient('puk_product_import_flush_scheduled')) {
        puk_schedule_flush_rewrite_rules();
        set_transient('puk_product_import_flush_scheduled', true, 5 * MINUTE_IN_SECONDS);
    }
}

/**
 * Hook into PUK Taxonomy Import AJAX batch completion
 * Fires after each successful batch import
 */
function puk_flush_after_taxonomy_import_batch() {
    // Only schedule once per import session using a short-lived transient
    if (!get_transient('puk_taxonomy_import_flush_scheduled')) {
        puk_schedule_flush_rewrite_rules();
        set_transient('puk_taxonomy_import_flush_scheduled', true, 5 * MINUTE_IN_SECONDS);
    }
}

// Hook into custom PUK import actions (fired from import helpers)
add_action('puk_product_import_complete', 'puk_schedule_flush_rewrite_rules');
add_action('puk_taxonomy_import_complete', 'puk_schedule_flush_rewrite_rules');
add_action('puk_product_import_batch_complete', 'puk_flush_after_product_import_batch');
add_action('puk_taxonomy_import_batch_complete', 'puk_flush_after_taxonomy_import_batch');

/**
 * Fallback: Detect import completion via AJAX response interception
 * This hooks into the AJAX handlers to detect when imports finish
 */
function puk_detect_import_ajax_completion() {
    // Check if this is a PUK import AJAX request that just completed
    if (defined('DOING_AJAX') && DOING_AJAX) {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

        $import_actions = [
            'puk_import_products_batch',
            'puk_import_taxonomy_batch',
        ];

        if (in_array($action, $import_actions)) {
            // Schedule flush (transient prevents multiple flushes)
            puk_schedule_flush_rewrite_rules();
        }
    }
}
// Run late on shutdown to catch AJAX imports
add_action('shutdown', 'puk_detect_import_ajax_completion');

/**
 * ========================================
 * TRIGGER: Site Migration Detection
 * ========================================
 */
function puk_detect_site_migration() {
    $stored_url = get_option('puk_site_url');
    $current_url = home_url();

    if ($stored_url !== $current_url) {
        update_option('puk_site_url', $current_url, false);
        puk_schedule_flush_rewrite_rules();
    }
}
add_action('admin_init', 'puk_detect_site_migration');

/**
 * ========================================
 * TRIGGER: Manual Admin Flush Button
 * ========================================
 */
function puk_add_flush_rewrite_button() {
    add_submenu_page(
        null, // Hidden from menu
        'Flush Rewrite Rules',
        'Flush Rewrite Rules',
        'manage_options',
        'puk-flush-rewrites',
        'puk_handle_manual_flush'
    );
}
add_action('admin_menu', 'puk_add_flush_rewrite_button');

function puk_handle_manual_flush() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permission to do this.', 'puk'));
    }

    // Verify nonce
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'puk_flush_rewrites')) {
        wp_die(__('Security check failed.', 'puk'));
    }

    // Schedule flush
    puk_schedule_flush_rewrite_rules();

    // Redirect back with success message
    $redirect_url = add_query_arg('puk_flushed', '1', admin_url('options-permalink.php'));
    wp_safe_redirect($redirect_url);
    exit;
}

/**
 * Add flush button to Permalink Settings page
 */
function puk_add_flush_button_to_permalinks() {
    // Show success message
    if (isset($_GET['puk_flushed']) && $_GET['puk_flushed'] === '1') {
        echo '<div class="notice notice-success is-dismissible"><p><strong>PUK:</strong> Rewrite rules will be flushed on next page load.</p></div>';
    }

    $flush_url = wp_nonce_url(
        admin_url('admin.php?page=puk-flush-rewrites'),
        'puk_flush_rewrites'
    );
    ?>
    <div class="notice notice-info">
        <p>
            <strong>PUK Theme:</strong>
            If product or category pages show 404 errors,
            <a href="<?php echo esc_url($flush_url); ?>" class="button button-secondary">
                Flush Product Rewrite Rules
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', function() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'options-permalink') {
        puk_add_flush_button_to_permalinks();
    }
});

/**
 * ========================================
 * TRIGGER: WP-CLI Support
 * ========================================
 */
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('puk flush-rewrites', function() {
        puk_schedule_flush_rewrite_rules();
        WP_CLI::success('Rewrite rules flush scheduled. Will execute on next page load.');
    });
}

/**
 * ========================================
 * TRIGGER: REST API Endpoint for External Tools
 * ========================================
 */
function puk_register_flush_endpoint() {
    register_rest_route('puk/v1', '/flush-rewrites', array(
        'methods' => 'POST',
        'callback' => function($request) {
            puk_schedule_flush_rewrite_rules();
            return new WP_REST_Response(array(
                'success' => true,
                'message' => 'Rewrite rules flush scheduled'
            ), 200);
        },
        'permission_callback' => function() {
            return current_user_can('manage_options');
        }
    ));
}
add_action('rest_api_init', 'puk_register_flush_endpoint');

/**
 * ========================================
 * INITIAL SETUP: First-time flush after installation
 * ========================================
 */
function puk_initial_flush_check() {
    if (!get_option('puk_initial_flush_done')) {
        puk_schedule_flush_rewrite_rules();
        update_option('puk_initial_flush_done', true, false);
    }
}
add_action('admin_init', 'puk_initial_flush_check');
