<?php
/**
 * SUPER FILTER: Ensure 'tax_acc__code' is shown instead of name in all admin contexts.
 * This covers selection boxes, initial labels, and list tables.
 */

// 1. Global Term Filter (Admin/AJAX/REST)
function puk_accessory_term_name_global( $_term ) {
    if ( ! is_object($_term) || ! isset($_term->taxonomy) || $_term->taxonomy !== 'accessories' ) {
        return $_term;
    }

    // Only apply in admin context (including AJAX and REST)
    if ( is_admin() || (defined('DOING_AJAX') && DOING_AJAX) || (defined('REST_REQUEST') && REST_REQUEST) ) {
        
        // Avoid overriding during actual term edit save/load
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( $screen && $screen->base === 'term' && $screen->taxonomy === 'accessories' ) {
            return $_term;
        }

        $code = get_term_meta( $_term->term_id, 'tax_acc__code', true );
        if ( $code ) {
            $_term->name = $code;
        }
    }
    return $_term;
}
add_filter( 'get_term', 'puk_accessory_term_name_global', 999, 1 );

// 2. Collection Filter (for get_the_terms used by ACF)
function puk_accessory_terms_collection( $terms ) {
    if ( ! is_array($terms) || empty($terms) ) return $terms;

    foreach ( $terms as &$term ) {
        if ( is_object($term) && $term->taxonomy === 'accessories' ) {
            $code = get_term_meta( $term->term_id, 'tax_acc__code', true );
            if ( $code ) {
                $term->name = $code;
            }
        }
    }
    return $terms;
}
add_filter( 'get_the_terms', 'puk_accessory_terms_collection', 999 );

// 3. Specific ACF Taxonomy Result Filter (for AJAX results)
function puk_acf_taxonomy_result_v3( $text, $term, $field, $post_id ) {
    if ( is_object($term) && $term->taxonomy === 'accessories' ) {
        $code = get_term_meta( $term->term_id, 'tax_acc__code', true );
        if ( $code ) {
            return esc_html( $code );
        }
    }
    return $text;
}
add_filter( 'acf/fields/taxonomy/result', 'puk_acf_taxonomy_result_v3', 999, 4 );

// 4. ACF Prepare Field (for non-AJAX choice lists)
function puk_acf_prepare_accessory_field( $field ) {
    $target_fields = ['prod_acc_in__terms', 'prod_acc_not_in__terms'];
    if ( in_array($field['name'], $target_fields) && ! empty($field['choices']) ) {
        foreach ( $field['choices'] as $id => $label ) {
            $code = get_term_meta( $id, 'tax_acc__code', true );
            if ( $code ) {
                $field['choices'][$id] = $code;
            }
        }
    }
    return $field;
}
add_filter( 'acf/prepare_field', 'puk_acf_prepare_accessory_field', 999 );
