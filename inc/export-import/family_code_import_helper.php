<?php
/**
 * Family Code Import Helper
 *
 * Dedicated importer that updates ONLY the family_code ACF field.
 * Never creates, renames, or re-parents any taxonomy term.
 *
 * CSV column order (extra columns are ignored):
 *   Family Code, Main Category, Family, Family UID,
 *   Sub Family, Sub Family UID, Sub Sub Family, Sub Sub Family UID
 *
 * Lookup strategy per row:
 *   1. Find by deepest UID present  (Sub Sub Family UID > Sub Family UID > Family UID)
 *   2. Fallback: walk hierarchy by Name + Parent  (requires Main Category)
 *
 * Special values:
 *   '_' in any name column is treated as an empty placeholder (UID lookup still applies).
 *
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Puk_Family_Code_Importer {

    private $taxonomy = 'product-family';

    public function __construct() {
        add_action( 'wp_ajax_puk_import_family_code_only', [ $this, 'ajax_import_family_code_only' ] );
    }

    /**
     * AJAX batch handler.
     *
     * POST params:
     *   nonce       — puk_taxonomy_import_nonce
     *   batch_data  — JSON-encoded array of row objects (keys = lowercase column names)
     *   start_row   — (int) row offset for error messages
     */
    public function ajax_import_family_code_only() {
        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce']
               : ( isset( $_REQUEST['nonce'] )     ? $_REQUEST['nonce'] : '' );

        if ( ! wp_verify_nonce( $nonce, 'puk_taxonomy_import_nonce' ) ) {
            wp_send_json_error( 'Security check failed' );
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Permission denied' );
        }

        $batch_data = isset( $_POST['batch_data'] ) ? $_POST['batch_data'] : [];
        if ( is_string( $batch_data ) ) {
            $batch_data = json_decode( stripslashes( $batch_data ), true );
        }
        if ( empty( $batch_data ) || ! is_array( $batch_data ) ) {
            wp_send_json_error( 'No valid batch data provided' );
        }

        $updated = 0;
        $skipped = 0;
        $errors  = [];

        foreach ( $batch_data as $index => $item ) {
            $item    = array_change_key_case( $item, CASE_LOWER );
            $row_num = isset( $_POST['start_row'] ) ? intval( $_POST['start_row'] ) + $index : $index + 1;

            $family_code = isset( $item['family code'] ) ? trim( $item['family code'] ) : '';

            // Nothing to update — skip silently
            if ( $family_code === '' ) {
                $skipped++;
                continue;
            }

            // --- Read UIDs (primary lookup keys) ---
            $family_uid         = isset( $item['family uid'] )         ? trim( $item['family uid'] )         : '';
            $sub_family_uid     = isset( $item['sub family uid'] )     ? trim( $item['sub family uid'] )     : '';
            $sub_sub_family_uid = isset( $item['sub sub family uid'] ) ? trim( $item['sub sub family uid'] ) : '';

            // --- Read names (kept as-is, '_' is a real term name) ---
            $main_category  = isset( $item['main category'] )  ? $this->title_case( trim( $item['main category'] ) )  : '';
            $family         = isset( $item['family'] )         ? $this->title_case( trim( $item['family'] ) )         : '';
            $sub_family     = isset( $item['sub family'] )     ? $this->title_case( trim( $item['sub family'] ) )     : '';
            $sub_sub_family = isset( $item['sub sub family'] ) ? $this->title_case( trim( $item['sub sub family'] ) ) : '';

            $target_id    = null;
            $target_label = '';

            // -------------------------------------------------------
            // Strategy 1: Find by UID — priority: Sub Family > Family > Sub Sub Family
            //
            // Sub Family UID   → 1st choice (code belongs to Sub Family level)
            // Family UID       → 2nd choice (no Sub Family present)
            // Sub Sub Family   → last resort (no Family or Sub Family UID)
            // -------------------------------------------------------
            if ( ! empty( $sub_family_uid ) ) {
                $target_id    = $this->find_by_uid( $sub_family_uid );
                $target_label = ! empty( $sub_family ) ? $sub_family : "UID:{$sub_family_uid}";

            } elseif ( ! empty( $family_uid ) ) {
                $target_id    = $this->find_by_uid( $family_uid );
                $target_label = ! empty( $family ) ? $family : "UID:{$family_uid}";

            } elseif ( ! empty( $sub_sub_family_uid ) ) {
                $target_id    = $this->find_by_uid( $sub_sub_family_uid );
                $target_label = ! empty( $sub_sub_family ) ? $sub_sub_family : "UID:{$sub_sub_family_uid}";
            }

            // -------------------------------------------------------
            // Strategy 2: Fallback — walk hierarchy by Name + Parent
            // Same priority: Sub Family > Family > Sub Sub Family
            // -------------------------------------------------------
            if ( ! $target_id ) {
                $main_id = $this->find_by_name( $main_category, 0 );

                if ( ! $main_id ) {
                    $errors[] = "Row {$row_num}: No UID found and Main Category '{$main_category}' not found — skipped.";
                    $skipped++;
                    continue;
                }

                if ( ! empty( $sub_family ) ) {
                    $fam_id    = $this->find_by_name( $family, $main_id );
                    $target_id = $fam_id ? $this->find_by_name( $sub_family, $fam_id ) : null;
                    $target_label = $sub_family;

                } elseif ( ! empty( $family ) ) {
                    $target_id    = $this->find_by_name( $family, $main_id );
                    $target_label = $family;

                } elseif ( ! empty( $sub_sub_family ) ) {
                    $fam_id    = $this->find_by_name( $family,     $main_id );
                    $sub_id    = $fam_id ? $this->find_by_name( $sub_family, $fam_id ) : null;
                    $target_id = $sub_id ? $this->find_by_name( $sub_sub_family, $sub_id ) : null;
                    $target_label = $sub_sub_family;

                } else {
                    $errors[] = "Row {$row_num}: No UID and no Family name — skipped.";
                    $skipped++;
                    continue;
                }
            }

            if ( ! $target_id ) {
                $errors[] = "Row {$row_num}: Term '{$target_label}' not found — skipped.";
                $skipped++;
                continue;
            }

            // Update ONLY family_code — nothing else is touched
            update_term_meta( $target_id, 'family_code', $family_code );
            $updated++;
        }

        wp_send_json_success( [
            'updated'     => $updated,
            'skipped'     => $skipped,
            'errors'      => $errors,
            'error_count' => count( $errors ),
        ] );
    }

    /**
     * Find a term by ACF tax_family__uid meta value.
     * Pure read — never creates or modifies anything.
     *
     * @param  string   $uid
     * @return int|false
     */
    private function find_by_uid( $uid ) {
        if ( empty( $uid ) ) {
            return false;
        }
        $terms = get_terms( [
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'number'     => 1,
            'meta_query' => [ [
                'key'     => 'tax_family__uid',
                'value'   => $uid,
                'compare' => '=',
            ] ],
        ] );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            return (int) $terms[0]->term_id;
        }
        return false;
    }

    /**
     * Find a term by exact name + parent ID.
     * Pure read — never creates or modifies anything.
     *
     * @param  string   $name
     * @param  int      $parent_id
     * @return int|false
     */
    private function find_by_name( $name, $parent_id ) {
        if ( empty( $name ) ) {
            return false;
        }
        $term = get_term_by( 'name', $name, $this->taxonomy );
        if ( $term && ! is_wp_error( $term ) && (int) $term->parent === (int) $parent_id ) {
            return (int) $term->term_id;
        }
        return false;
    }

    /**
     * Convert a string to Title Case (UTF-8 safe).
     *
     * @param  string $str
     * @return string
     */
    private function title_case( $str ) {
        if ( empty( $str ) ) {
            return $str;
        }
        return mb_convert_case( mb_strtolower( $str, 'UTF-8' ), MB_CASE_TITLE, 'UTF-8' );
    }
}

new Puk_Family_Code_Importer();
