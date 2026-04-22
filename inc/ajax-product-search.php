<?php
/**
 * AJAX Product Search Handler
 * Two modes: text search (family names) and code search (family_code + prod__sku)
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

add_action('wp_ajax_puk_product_search', 'puk_product_search_handler');
add_action('wp_ajax_nopriv_puk_product_search', 'puk_product_search_handler');

function puk_product_search_handler() {
    $search_term = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    if (empty($search_term) || strlen($search_term) < 2) {
        echo json_encode(array('success' => false, 'message' => 'Search term too short'));
        wp_die();
    }

    // Detect mode: code search if input contains a dot and first segment is numeric
    $parts = explode('.', $search_term);
    $is_code_search = is_numeric($search_term) || (strpos($search_term, '.') !== false && is_numeric($parts[0]));

    if ($is_code_search) {
        $prefix = $parts[0]; // e.g. "101401"
        $data = puk_search_by_code($prefix, $search_term);
    } else {
        $data = puk_search_by_text($search_term, 8);
    }

    echo json_encode(array('success' => true, 'data' => $data));
    wp_die();
}

/**
 * Returns all descendant term IDs (flat, deduped) for a set of term IDs.
 * Uses get_term_children() which returns ALL descendants, not just direct children.
 */
function puk_get_all_descendants(array $term_ids): array {
    $all = [];
    foreach ($term_ids as $id) {
        $children = get_term_children((int) $id, 'product-family');
        if (!is_wp_error($children)) {
            foreach ($children as $child) {
                $child = (int) $child;
                if (!in_array($child, $all)) {
                    $all[] = $child;
                }
            }
        }
    }
    return $all;
}

/**
 * Multi-word cascade search.
 * Seeds pool from first word, then for each subsequent word:
 * expands pool to descendants → filters by that word name.
 * Returns matched term IDs (before descendant expansion for final output).
 */
function puk_match_multi_word(array $words, int $limit): array {
    $pool = get_terms([
        'taxonomy'   => 'product-family',
        'hide_empty' => false,
        'name__like' => $words[0],
        'fields'     => 'ids',
        'number'     => $limit * 5,
    ]);
    if (is_wp_error($pool) || empty($pool)) {
        return [];
    }
    $pool = array_map('intval', $pool);

    for ($i = 1; $i < count($words); $i++) {
        $descendants = puk_get_all_descendants($pool);
        if (empty($descendants)) {
            return [];
        }
        $matched = get_terms([
            'taxonomy'   => 'product-family',
            'hide_empty' => false,
            'name__like' => $words[$i],
            'include'    => $descendants,
            'fields'     => 'ids',
            'number'     => $limit * 5,
        ]);
        if (is_wp_error($matched) || empty($matched)) {
            return [];
        }
        $pool = array_map('intval', $matched);
    }

    return $pool;
}

/**
 * Mode 1 — Text search
 * Single word: searches by term name + family_code LIKE, excludes root.
 * Multi-word: cascades through hierarchy levels (Plan B).
 * Both paths expand matched terms to include all descendants.
 */
function puk_search_by_text($search_term, $limit) {
    global $wpdb;

    $words = array_values(array_filter(array_map('trim', explode(' ', $search_term))));

    // --- Step 1: Get matched term IDs ---
    if (count($words) > 1) {
        // Multi-word: cascade through hierarchy levels
        $matched_term_ids = puk_match_multi_word($words, $limit);
    } else {
        // Single word: search by name + family_code, exclude root
        $matched_term_ids = [];

        $name_terms = get_terms([
            'taxonomy'   => 'product-family',
            'hide_empty' => false,
            'name__like' => $search_term,
            'fields'     => 'ids',
            'number'     => $limit,
        ]);
        if (!is_wp_error($name_terms)) {
            $matched_term_ids = array_map('intval', $name_terms);
        }

        $code_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT term_id FROM {$wpdb->termmeta}
             WHERE meta_key = 'family_code' AND meta_value LIKE %s LIMIT %d",
            '%' . $wpdb->esc_like($search_term) . '%',
            $limit
        ));
        foreach ($code_ids as $tid) {
            $tid = (int) $tid;
            if (!in_array($tid, $matched_term_ids)) {
                $matched_term_ids[] = $tid;
            }
        }

        // Exclude root (level 0)
        $matched_term_ids = array_values(array_filter($matched_term_ids, function ($id) {
            $t = get_term($id, 'product-family');
            return $t && !is_wp_error($t) && $t->parent != 0;
        }));
    }

    // --- Step 2: Expand matched terms to include all their descendants ---
    $all_term_ids = [];
    foreach ($matched_term_ids as $term_id) {
        if (!in_array($term_id, $all_term_ids)) {
            $all_term_ids[] = $term_id;
        }
        $children = get_term_children($term_id, 'product-family');
        if (!is_wp_error($children)) {
            foreach ($children as $child_id) {
                $child_id = (int) $child_id;
                if (!in_array($child_id, $all_term_ids)) {
                    $all_term_ids[] = $child_id;
                }
            }
        }
    }
    $all_term_ids = array_slice($all_term_ids, 0, $limit);

    // --- Step 3: Build items ---
    $items = [];
    foreach ($all_term_ids as $term_id) {
        $term = get_term($term_id, 'product-family');
        if (!$term || is_wp_error($term) || $term->parent == 0) {
            continue;
        }
        $term_link   = get_term_link($term);
        $family_code = get_term_meta($term_id, 'family_code', true);
        $items[] = [
            'title'       => puk_get_term_display_name($term),
            'permalink'   => !is_wp_error($term_link) ? $term_link : '',
            'family_code' => $family_code ? $family_code : '',
            'depth'       => count(get_ancestors($term_id, 'product-family')), // 1=Family, 2=SubFamily, 3=SubSubFamily
        ];
    }

    return ['mode' => 'text', 'items' => $items];
}

/**
 * Mode 2 — Code search
 * $prefix: first segment before dot (e.g. "101401") — matched exactly against family_code
 * $full: full search term (e.g. "101401.27.55.03") — matched LIKE against prod__sku
 */
function puk_search_by_code($prefix, $full) {
    global $wpdb;

    // Find family term(s) with exact family_code = prefix
    $family_term_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT term_id FROM {$wpdb->termmeta}
         WHERE meta_key = 'family_code'
         AND meta_value = %s
         LIMIT 5",
        $prefix
    ));

    // Find products (post_type=product) whose prod__sku LIKE %full%
    $post_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT DISTINCT pm.post_id FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = 'prod__sku'
         AND pm.meta_value LIKE %s
         AND p.post_type = 'product'
         AND p.post_status = 'publish'
         LIMIT 8",
        '%' . $wpdb->esc_like($full) . '%'
    ));

    $products = array();
    foreach ($post_ids as $post_id) {
        $sku = get_post_meta((int) $post_id, 'prod__sku', true);
        $products[] = array(
            'sku'       => $sku ? $sku : '',
            'permalink' => get_permalink((int) $post_id),
        );
    }

    // Dot mode: both columns link to the first matched product
    // Numeric mode: each family links to its own term page
    $is_dot_mode       = strpos($full, '.') !== false;
    $first_product_url = !empty($products) ? $products[0]['permalink'] : '';

    $families = array();
    foreach ($family_term_ids as $term_id) {
        $term = get_term((int) $term_id, 'product-family');
        if (!$term || is_wp_error($term)) {
            continue;
        }
        if ($is_dot_mode) {
            $permalink = $first_product_url;
        } else {
            $term_link = get_term_link($term);
            $permalink = !is_wp_error($term_link) ? $term_link : '';
        }
        $family_code = get_term_meta((int) $term_id, 'family_code', true);
        $depth       = count(get_ancestors((int) $term_id, 'product-family'));
        $order       = (int) get_term_meta((int) $term_id, 'order', true);
        $families[]  = array(
            'title'       => puk_get_term_display_name($term),
            'permalink'   => $permalink,
            'family_code' => $family_code ? $family_code : '',
            '_depth'      => $depth,
            '_order'      => $order,
        );
    }

    // Sort: shallower terms first, then by sibling order
    usort($families, function ($a, $b) {
        if ($a['_depth'] !== $b['_depth']) return $a['_depth'] - $b['_depth'];
        return $a['_order'] - $b['_order'];
    });

    foreach ($families as &$f) {
        unset($f['_depth'], $f['_order']);
    }
    unset($f);

    return array(
        'mode'     => 'code',
        'submode'  => strpos($full, '.') !== false ? 'dot' : 'numeric',
        'families' => $families,
        'products' => $products,
    );
}

/**
 * Build display name by joining ancestor names (excluding level 0) with the term's own name.
 * Example: Downlights > Ring > Mini > Hp → "Ring Mini Hp"
 */
function puk_get_term_display_name($term) {
    if ($term->parent == 0) {
        // Term is root itself — return its name as-is (with potential underscore stripping if needed, but per logic we just return it)
        $name = trim(explode('_', $term->name)[0]);
        return $name ? $name : $term->name;
    }

    // get_ancestors returns IDs from nearest to root: [Mini_id, Ring_id, Downlights_id]
    $ancestor_ids = get_ancestors($term->term_id, 'product-family');

    // Reverse so order is root → current: [Downlights, Ring, Mini]
    $ancestor_ids = array_reverse($ancestor_ids);

    $name_parts = array();
    foreach ($ancestor_ids as $ancestor_id) {
        $ancestor = get_term((int) $ancestor_id, 'product-family');
        if (!$ancestor || is_wp_error($ancestor)) {
            continue;
        }
        // Skip level 0 (root/main category)
        if ($ancestor->parent == 0) {
            continue;
        }
        
        // Hide part from _ onwards
        $name = trim(explode('_', $ancestor->name)[0]);
        if (!empty($name)) {
            $name_parts[] = $name;
        }
    }

    // Append the term's own name last, hiding part from _ onwards
    $term_name = trim(explode('_', $term->name)[0]);
    if (!empty($term_name)) {
        $name_parts[] = $term_name;
    }

    return implode(' ', $name_parts);
}

/**
 * Get root parent term (level 0) for a given term
 */
function puk_get_root_term($term) {
    if ($term->parent == 0) {
        return $term;
    }

    $ancestors = get_ancestors($term->term_id, 'product-family');
    if (!empty($ancestors)) {
        return get_term(end($ancestors), 'product-family');
    }

    return $term;
}
