<?php
/**
 * Auto-generate unique UID for product-family taxonomy terms
 *
 * Generates a unique identifier in format: PF-XXXXXXXX (8 alphanumeric characters)
 * This UID is stored in the ACF field 'tax_family__uid'
 *
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Generate a unique UID that doesn't exist in the database
 *
 * @return string Unique identifier in format PF001, PF002, etc.
 */
function puk_generate_unique_family_uid() {
    // Get all existing UIDs to find the highest number
    $terms = get_terms([
        'taxonomy'   => 'product-family',
        'hide_empty' => false,
        'meta_query' => [
            [
                'key'     => 'tax_family__uid',
                'compare' => 'EXISTS'
            ]
        ]
    ]);

    $highest_number = 0;

    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $uid = get_field( 'tax_family__uid', 'product-family_' . $term->term_id );

            // Extract number from UID (handles both PF001 and PF-001 formats)
            if ( preg_match( '/^PF-?(\d+)$/', $uid, $matches ) ) {
                $number = intval( $matches[1] );
                if ( $number > $highest_number ) {
                    $highest_number = $number;
                }
            }
        }
    }

    // Increment to get the next number
    $next_number = $highest_number + 1;

    // Format with leading zeros (minimum 3 digits: PF001, PF002, ..., PF999, PF1000)
    $uid = 'PF' . str_pad( $next_number, 3, '0', STR_PAD_LEFT );

    return $uid;
}

/**
 * Assign unique UID when a new product-family term is created
 *
 * @param int $term_id Term ID
 * @param int $tt_id   Term taxonomy ID
 */
function puk_assign_family_uid_on_create( $term_id, $tt_id ) {
    // Check if UID already exists for this term
    $existing_uid = get_field( 'tax_family__uid', 'product-family_' . $term_id );

    if ( empty( $existing_uid ) ) {
        // Generate and save new unique UID
        $new_uid = puk_generate_unique_family_uid();
        update_field( 'tax_family__uid', $new_uid, 'product-family_' . $term_id );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( "PUK: Generated UID '$new_uid' for new product-family term ID $term_id" );
        }
    }
}
add_action( 'created_product-family', 'puk_assign_family_uid_on_create', 10, 2 );

/**
 * Ensure UID exists when term is edited (for existing terms without UID)
 *
 * @param int $term_id Term ID
 * @param int $tt_id   Term taxonomy ID
 */
function puk_ensure_family_uid_on_edit( $term_id, $tt_id ) {
    $existing_uid = get_field( 'tax_family__uid', 'product-family_' . $term_id );

    if ( empty( $existing_uid ) ) {
        $new_uid = puk_generate_unique_family_uid();
        update_field( 'tax_family__uid', $new_uid, 'product-family_' . $term_id );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( "PUK: Generated UID '$new_uid' for existing product-family term ID $term_id (on edit)" );
        }
    }
}
add_action( 'edited_product-family', 'puk_ensure_family_uid_on_edit', 10, 2 );

/**
 * Bulk assign UIDs to all existing terms that don't have one
 *
 * Can be triggered via: do_action('puk_bulk_assign_family_uids');
 * Or via admin tools
 *
 * @return int Number of terms updated
 */
function puk_bulk_assign_family_uids() {
    $terms = get_terms([
        'taxonomy'   => 'product-family',
        'hide_empty' => false,
    ]);

    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return 0;
    }

    $count = 0;
    foreach ( $terms as $term ) {
        $existing_uid = get_field( 'tax_family__uid', 'product-family_' . $term->term_id );

        if ( empty( $existing_uid ) ) {
            $new_uid = puk_generate_unique_family_uid();
            update_field( 'tax_family__uid', $new_uid, 'product-family_' . $term->term_id );
            $count++;

            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( "PUK Bulk: Generated UID '$new_uid' for term '{$term->name}' (ID: {$term->term_id})" );
            }
        }
    }

    return $count;
}
add_action( 'puk_bulk_assign_family_uids', 'puk_bulk_assign_family_uids' );

/**
 * Admin tool to bulk assign UIDs via query parameter
 *
 * Usage: /wp-admin/?puk_assign_family_uids=1
 */
function puk_admin_bulk_assign_uids() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_GET['puk_assign_family_uids'] ) && $_GET['puk_assign_family_uids'] === '1' ) {
        $count = puk_bulk_assign_family_uids();

        add_action( 'admin_notices', function() use ( $count ) {
            echo '<div class="notice notice-success is-dismissible"><p>';
            echo sprintf( __( 'Assigned unique UIDs to %d product-family terms.', 'puk' ), $count );
            echo '</p></div>';
        });
    }
}
add_action( 'admin_init', 'puk_admin_bulk_assign_uids' );
