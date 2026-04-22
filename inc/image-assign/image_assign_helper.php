<?php
/**
 * Bulk Image Assign Helper Functions - Folder Pattern
 *
 * Handles the logic for bulk assigning images to taxonomy terms and products
 * by scanning folder hierarchies in the uploads directory.
 *
 * Folder Patterns:
 * 1. Product Family: Floodlights/Qubo/Micro/103/tech.webp (103 = tax_family__uid of sub-sub-family)
 * 2. Accessories: Accessories/AC044.jpg (finds by tax_acc__code)
 * 3. Products: Products/SKU123/main.jpg (finds by prod__sku)
 *
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Puk_Image_Assign_Helper {

    private $import_dir = 'puk-import';
    private $family_taxonomy = 'product-family';
    private $accessories_taxonomy = 'accessories';

    public function __construct() {
        add_action( 'wp_ajax_puk_image_assign_scan', [ $this, 'ajax_scan_folder' ] );
        add_action( 'wp_ajax_puk_image_assign_process_file', [ $this, 'ajax_process_file' ] );
    }

    /**
     * Scans the puk-import folder and returns a flat list of all image files.
     */
    public function ajax_scan_folder() {
        check_ajax_referer( 'puk_image_assign_nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Permission denied' );
        }

        $upload_dir = wp_upload_dir();
        $base_path = trailingslashit( $upload_dir['basedir'] ) . $this->import_dir;

        if ( ! is_dir( $base_path ) ) {
            wp_send_json_error( 'Folder /wp-content/uploads/puk-import/ does not exist.' );
        }

        $files = $this->scan_all_images( $base_path );

        if ( empty( $files ) ) {
            wp_send_json_error( 'No images found in puk-import folder.' );
        }

        wp_send_json_success( [ 'files' => $files ] );
    }

    /**
     * Recursively scans directory and returns flat list of all image files.
     */
    private function scan_all_images( $dir_path, $relative_path = '' ) {
        if ( ! is_dir( $dir_path ) ) {
            return [];
        }

        $items = array_diff( scandir( $dir_path ), [ '.', '..' ] );
        $files = [];

        foreach ( $items as $item ) {
            $item_full_path = trailingslashit( $dir_path ) . $item;
            $item_relative = $relative_path ? $relative_path . '/' . $item : $item;

            if ( is_dir( $item_full_path ) ) {
                $files = array_merge( $files, $this->scan_all_images( $item_full_path, $item_relative ) );
            } elseif ( $this->is_image_file( $item ) ) {
                $files[] = $item_relative;
            }
        }

        return $files;
    }

    /**
     * Checks if a file is an image.
     */
    private function is_image_file( $filename ) {
        $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
        return in_array( $ext, [ 'jpg', 'jpeg', 'png', 'webp', 'gif' ] );
    }

    /**
     * Processes a single image file based on its folder path.
     */
    public function ajax_process_file() {
        // Extend execution time for image processing
        @set_time_limit( 120 ); // 2 minutes per image
        @ini_set( 'max_execution_time', 120 );
        @ini_set( 'memory_limit', '512M' );

        check_ajax_referer( 'puk_image_assign_nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Permission denied' );
        }

        $filename = isset( $_POST['filename'] ) ? sanitize_text_field( $_POST['filename'] ) : '';
        if ( empty( $filename ) ) {
            wp_send_json_error( 'No filename provided.' );
        }

        $upload_dir = wp_upload_dir();
        $file_path = trailingslashit( $upload_dir['basedir'] ) . $this->import_dir . '/' . $filename;

        if ( ! file_exists( $file_path ) ) {
            wp_send_json_error( "File $filename not found." );
        }

        // Parse path to determine type
        $path_parts = explode( '/', $filename );
        $first_folder = strtolower( $path_parts[0] );

        // Route to appropriate handler based on first folder
        if ( $first_folder === 'accessories' ) {
            $result = $this->handle_accessories( $file_path, $path_parts );
        } elseif ( $first_folder === 'products' ) {
            $result = $this->handle_products( $file_path, $path_parts );
        } else {
            // Default: Product Family taxonomy
            $result = $this->handle_product_family( $file_path, $path_parts );
        }

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( $result->get_error_message() );
        }

        wp_send_json_success( $result );
    }

    /**
     * Handles Product Family taxonomy assignments.
     *
     * Structure: Family/SubFamily/SubSubFamily/Code/image.jpg
     * Example: Floodlights/Qubo/Micro/103/main.jpg
     *
     * gallery-1 to gallery-4: assigned to taxonomy term
     * gallery-5 to gallery-15: assigned to all products under the term
     *
     * Cascade feature: If image is in a sub-family folder that contains
     * sub-sub-family UID folders (e.g., PF018, PF016), the image is also
     * assigned to those child terms.
     */
    private function handle_product_family( $file_path, $path_parts ) {
        $image_name = array_pop( $path_parts );

        $family_name = isset( $path_parts[0] ) ? $path_parts[0] : null;
        $subfamily_name = isset( $path_parts[1] ) ? $path_parts[1] : null;
        $subsubfamily_name = isset( $path_parts[2] ) ? $path_parts[2] : null;
        $level3_code = isset( $path_parts[3] ) ? $path_parts[3] : null;

        if ( ! $family_name ) {
            return new WP_Error( 'invalid_path', 'Image must be inside a Family folder.' );
        }

        // Determine target term
        if ( $level3_code ) {
            $target_term = $this->find_term_by_code( $level3_code, $family_name, $subfamily_name, $subsubfamily_name );
            $term_path = "$family_name > $subfamily_name > $subsubfamily_name > $level3_code";
        } elseif ( $subsubfamily_name ) {
            $target_term = $this->find_family_term_by_hierarchy( $family_name, $subfamily_name, $subsubfamily_name );
            $term_path = "$family_name > $subfamily_name > $subsubfamily_name";
        } elseif ( $subfamily_name ) {
            $target_term = $this->find_family_term_by_hierarchy( $family_name, $subfamily_name );
            $term_path = "$family_name > $subfamily_name";
        } else {
            $target_term = $this->find_family_term_by_hierarchy( $family_name );
            $term_path = $family_name;
        }

        if ( is_wp_error( $target_term ) ) {
            return $target_term;
        }

        // Map filename to field
        $name_only = pathinfo( $image_name, PATHINFO_FILENAME );
        $field_slug = $this->map_family_filename_to_field( $name_only );

        // Check if this is a product gallery field (gallery-5 to gallery-15)
        if ( preg_match( '/^gallery-(\d+)$/', strtolower( $name_only ), $matches ) ) {
            $num = intval( $matches[1] );
            if ( $num >= 5 && $num <= 15 ) {
                return $this->assign_image_to_term_products( $file_path, $target_term, $name_only, $term_path );
            }
        }

        // Sideload image
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        // Assign to term
        $term_id_for_acf = $this->family_taxonomy . '_' . $target_term->term_id;

        // gallery-1 through gallery-4 are single image fields, not gallery/repeater fields
        if ( $field_slug === 'pf_subfam_product_image' ) {
            // This is an actual gallery field - append to array
            $existing = get_field( $field_slug, $term_id_for_acf, false );
            if ( ! is_array( $existing ) ) $existing = [];
            if ( ! in_array( $attachment_id, $existing ) ) {
                $existing[] = $attachment_id;
                update_field( $field_slug, $existing, $term_id_for_acf );
            }
        } else {
            // Single image fields (including gallery-1 to gallery-4)
            update_field( $field_slug, $attachment_id, $term_id_for_acf );
        }

        $result_message = "Assigned '$field_slug' (ID: $attachment_id) to '{$target_term->name}' ($term_path)";

        // Cascade: Also assign to child sub-sub-family terms if UID folders exist
        $child_results = $this->cascade_to_child_uid_terms( $file_path, $path_parts, $target_term, $attachment_id, $field_slug );
        if ( ! empty( $child_results ) ) {
            $result_message .= ' | Cascaded to: ' . implode( ', ', $child_results );
        }

        return $result_message;
    }

    /**
     * Cascades image assignment to child terms found by UID folders.
     *
     * Scans the image's parent folder for subdirectories that match
     * sub-sub-family UIDs (e.g., PF018, PF016) and assigns the same
     * image to those terms.
     *
     * @param string   $file_path     Full path to the image file.
     * @param array    $path_parts    Folder path parts (without image name).
     * @param WP_Term  $parent_term   The parent term the image was assigned to.
     * @param int      $attachment_id The sideloaded attachment ID.
     * @param string   $field_slug    The ACF field slug to assign.
     * @return array   Array of child term names that were assigned.
     */
    private function cascade_to_child_uid_terms( $file_path, $path_parts, $parent_term, $attachment_id, $field_slug ) {
        $assigned_children = [];

        // Get the folder containing the image
        $image_folder = dirname( $file_path );

        if ( ! is_dir( $image_folder ) ) {
            return $assigned_children;
        }

        // Scan for subdirectories (potential UID folders)
        $items = array_diff( scandir( $image_folder ), [ '.', '..' ] );
        $uid_folders = [];

        foreach ( $items as $item ) {
            $item_path = trailingslashit( $image_folder ) . $item;
            if ( is_dir( $item_path ) ) {
                $uid_folders[] = $item;
            }
        }

        if ( empty( $uid_folders ) ) {
            return $assigned_children;
        }

        // Find child terms by UID and assign the image
        foreach ( $uid_folders as $uid ) {
            $child_terms = get_terms( [
                'taxonomy'   => $this->family_taxonomy,
                'hide_empty' => false,
                'parent'     => $parent_term->term_id,
                'meta_query' => [
                    [
                        'key'     => 'tax_family__uid',
                        'value'   => $uid,
                        'compare' => '='
                    ]
                ]
            ] );

            if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
                $child_term = $child_terms[0];
                $child_term_id_for_acf = $this->family_taxonomy . '_' . $child_term->term_id;

                // Assign image using same logic as parent
                if ( $field_slug === 'pf_subfam_product_image' ) {
                    $existing = get_field( $field_slug, $child_term_id_for_acf, false );
                    if ( ! is_array( $existing ) ) $existing = [];
                    if ( ! in_array( $attachment_id, $existing ) ) {
                        $existing[] = $attachment_id;
                        update_field( $field_slug, $existing, $child_term_id_for_acf );
                    }
                } else {
                    update_field( $field_slug, $attachment_id, $child_term_id_for_acf );
                }

                $assigned_children[] = $child_term->name . " ($uid)";
            }
        }

        return $assigned_children;
    }

    /**
     * Assigns an image to all products under a taxonomy term.
     *
     * Used for gallery-5 through gallery-15 images.
     */
    private function assign_image_to_term_products( $file_path, $term, $filename, $term_path ) {
        // Get all products under this term
        $products = get_posts( [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => $this->family_taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ]
            ]
        ] );

        if ( empty( $products ) ) {
            return new WP_Error( 'no_products', "No products found under '{$term->name}' ($term_path)." );
        }

        // Sideload image once
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        // Map filename to product field
        $field_slug = $this->map_product_filename_to_field( strtolower( $filename ) );

        // Assign to all products
        $count = 0;
        foreach ( $products as $product ) {
            update_field( $field_slug, $attachment_id, $product->ID );
            $count++;
        }

        return "Assigned '$field_slug' (ID: $attachment_id) to $count products under '{$term->name}' ($term_path)";
    }

    /**
     * Handles Accessories taxonomy assignments.
     *
     * Structure: Accessories/AC044.jpg
     * Finds term by tax_acc__code field, assigns to tax_acc_ft__img
     */
    private function handle_accessories( $file_path, $path_parts ) {
        $image_name = array_pop( $path_parts );
        $name_only = pathinfo( $image_name, PATHINFO_FILENAME );

        // The filename IS the accessory code (e.g., AC044)
        $accessory_code = $name_only;

        // Find term by tax_acc__code
        $terms = get_terms( [
            'taxonomy'   => $this->accessories_taxonomy,
            'hide_empty' => false,
            'meta_query' => [
                [
                    'key'     => 'tax_acc__code',
                    'value'   => $accessory_code,
                    'compare' => '='
                ]
            ]
        ] );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return new WP_Error( 'not_found', "Accessory with code '$accessory_code' not found." );
        }

        $term = $terms[0];

        // Sideload image
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        // Assign to tax_acc_ft__img field
        $term_id_for_acf = $this->accessories_taxonomy . '_' . $term->term_id;
        update_field( 'tax_acc_ft__img', $attachment_id, $term_id_for_acf );

        return "Assigned image (ID: $attachment_id) to Accessory '{$term->name}' (Code: $accessory_code)";
    }

    /**
     * Handles Product post type assignments.
     *
     * Structure: Products/SKU123/main.jpg or Products/SKU123.jpg
     * Finds product by prod__sku field
     */
    private function handle_products( $file_path, $path_parts ) {
        $image_name = array_pop( $path_parts );
        $name_only = pathinfo( $image_name, PATHINFO_FILENAME );

        // Check if SKU is folder or filename
        // Products/SKU123/main.jpg -> SKU is folder, main is field
        // Products/SKU123.jpg -> SKU is filename, default to featured image
        if ( count( $path_parts ) >= 2 ) {
            $sku = $path_parts[1];
            $field_key = $name_only;
        } else {
            $sku = $name_only;
            $field_key = 'featured';
        }

        // Find product by SKU
        $products = get_posts( [
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'     => 'prod__sku',
                    'value'   => $sku,
                    'compare' => '='
                ]
            ]
        ] );

        if ( empty( $products ) ) {
            return new WP_Error( 'not_found', "Product with SKU '$sku' not found." );
        }

        $product = $products[0];

        // Sideload image
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        // Map field and assign
        $field_slug = $this->map_product_filename_to_field( $field_key );

        if ( $field_slug === '_thumbnail_id' ) {
            set_post_thumbnail( $product->ID, $attachment_id );
        } elseif ( strpos( $field_slug, 'gallery' ) !== false ) {
            $existing = get_field( $field_slug, $product->ID, false );
            if ( ! is_array( $existing ) ) $existing = [];
            if ( ! in_array( $attachment_id, $existing ) ) {
                $existing[] = $attachment_id;
                update_field( $field_slug, $existing, $product->ID );
            }
        } else {
            update_field( $field_slug, $attachment_id, $product->ID );
        }

        return "Assigned '$field_slug' (ID: $attachment_id) to Product '{$product->post_title}' (SKU: $sku)";
    }

    /**
     * Finds a Level 4 term (sub-sub-family) by tax_family__uid meta field.
     */
    private function find_term_by_code( $code, $family_name, $subfamily_name, $subsubfamily_name ) {
        $parent_term = $this->find_family_term_by_hierarchy( $family_name, $subfamily_name, $subsubfamily_name );

        if ( is_wp_error( $parent_term ) ) {
            return $parent_term;
        }

        $terms = get_terms( [
            'taxonomy'   => $this->family_taxonomy,
            'hide_empty' => false,
            'parent'     => $parent_term->term_id,
            'meta_query' => [
                [
                    'key'     => 'tax_family__uid',
                    'value'   => $code,
                    'compare' => '='
                ]
            ]
        ] );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            return $terms[0];
        }

        // Fallback: try by name
        $term = $this->find_term_by_name( $this->family_taxonomy, $code, $parent_term->term_id );
        if ( ! is_wp_error( $term ) ) {
            return $term;
        }

        return new WP_Error( 'term_not_found', "Term with tax_family__uid '$code' not found under '$family_name > $subfamily_name > $subsubfamily_name'." );
    }

    /**
     * Finds a product-family term by hierarchy.
     */
    private function find_family_term_by_hierarchy( $family_name, $subfamily_name = null, $subsubfamily_name = null ) {
        $family_term = $this->find_term_by_name( $this->family_taxonomy, $family_name, 0 );

        if ( is_wp_error( $family_term ) ) {
            return new WP_Error( 'family_not_found', "Family term '$family_name' not found." );
        }

        if ( ! $subfamily_name ) {
            return $family_term;
        }

        $subfamily_term = $this->find_term_by_name( $this->family_taxonomy, $subfamily_name, $family_term->term_id );

        if ( is_wp_error( $subfamily_term ) ) {
            return new WP_Error( 'subfamily_not_found', "Sub Family '$subfamily_name' not found under '$family_name'." );
        }

        if ( ! $subsubfamily_name ) {
            return $subfamily_term;
        }

        $subsubfamily_term = $this->find_term_by_name( $this->family_taxonomy, $subsubfamily_name, $subfamily_term->term_id );

        if ( is_wp_error( $subsubfamily_term ) ) {
            return new WP_Error( 'subsubfamily_not_found', "Sub Sub Family '$subsubfamily_name' not found under '$family_name > $subfamily_name'." );
        }

        return $subsubfamily_term;
    }

    /**
     * Finds a term by name/slug (case-insensitive).
     */
    private function find_term_by_name( $taxonomy, $term_name, $parent_id = 0 ) {
        $all_terms = get_terms( [
            'taxonomy'   => $taxonomy,
            'parent'     => $parent_id,
            'hide_empty' => false,
        ] );

        if ( empty( $all_terms ) || is_wp_error( $all_terms ) ) {
            return new WP_Error( 'term_not_found', "Term '$term_name' not found." );
        }

        $search_name = strtolower( trim( $term_name ) );
        $search_name_no_hyphen = strtolower( str_replace( '-', ' ', $term_name ) );

        foreach ( $all_terms as $term ) {
            $term_name_lower = strtolower( $term->name );
            $term_slug_lower = strtolower( $term->slug );

            if ( $term_name_lower === $search_name ||
                 $term_slug_lower === $search_name ||
                 $term_name_lower === $search_name_no_hyphen ||
                 $term_slug_lower === str_replace( ' ', '-', $search_name ) ) {
                return $term;
            }
        }

        return new WP_Error( 'term_not_found', "Term '$term_name' not found." );
    }

    /**
     * Maps filename to ACF field for product-family taxonomy.
     */
    private function map_family_filename_to_field( $filename ) {
        $base_name = strtolower( $filename );

        // Handle gallery-1 through gallery-4 for taxonomy (maps to prod_gallery_1, etc.)
        if ( preg_match( '/^gallery-(\d+)$/', $base_name, $matches ) ) {
            $num = intval( $matches[1] );
            if ( $num >= 1 && $num <= 4 ) {
                return 'prod_gallery_' . $num;
            }
        }

        $map = [
            'main'     => 'pf_fet_img',
            'hover'    => 'pf_hover_img',
            'tech'     => 'pf_subfam_tech_drawing',
            'gallery'  => 'pf_subfam_product_image',
            'designer' => 'pf_designed_by'
        ];

        return isset( $map[ $base_name ] ) ? $map[ $base_name ] : $filename;
    }

    /**
     * Maps filename to ACF field for products.
     */
    private function map_product_filename_to_field( $filename ) {
        $base_name = strtolower( $filename );

        // Handle gallery-5 through gallery-15 for products
        if ( preg_match( '/^gallery-(\d+)$/', $base_name, $matches ) ) {
            $num = intval( $matches[1] );
            if ( $num >= 5 && $num <= 15 ) {
                return 'prod_gallery_' . $num;
            }
        }

        $map = [
            'main'     => '_thumbnail_id',
            'featured' => '_thumbnail_id',
            'gallery'  => 'pro_gallary',
            'gallery2' => 'pro_sub_gallary',
        ];

        return isset( $map[ $base_name ] ) ? $map[ $base_name ] : $filename;
    }

    /**
     * Sideloads an image to Media Library.
     *
     * Checks for true duplicates by comparing file hash (MD5), not just filename.
     * This ensures images with the same name but different content are uploaded separately.
     */
    private function sideload_image( $file_path ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $filename = basename( $file_path );
        $source_hash = md5_file( $file_path );

        if ( $source_hash === false ) {
            return new WP_Error( 'hash_failed', "Could not read file: $filename" );
        }

        // Check for existing attachment with same filename
        global $wpdb;
        $possible = $wpdb->get_results( $wpdb->prepare(
            "SELECT post_id, meta_value FROM $wpdb->postmeta
             WHERE meta_key = '_wp_attached_file'
             AND meta_value LIKE %s",
            '%' . $wpdb->esc_like( $filename )
        ) );

        if ( ! empty( $possible ) ) {
            $upload_dir = wp_upload_dir();
            foreach ( $possible as $att ) {
                if ( basename( $att->meta_value ) === $filename ) {
                    // Compare file content hash to ensure it's truly the same image
                    $existing_path = trailingslashit( $upload_dir['basedir'] ) . $att->meta_value;
                    if ( file_exists( $existing_path ) ) {
                        $existing_hash = md5_file( $existing_path );
                        if ( $existing_hash === $source_hash ) {
                            // Truly the same image - reuse it
                            return $att->post_id;
                        }
                    }
                }
            }
        }

        // Different image or no match found - upload new one
        $tmp = wp_tempnam( $filename );
        copy( $file_path, $tmp );

        $file_array = [
            'name'     => $filename,
            'tmp_name' => $tmp
        ];

        $id = media_handle_sideload( $file_array, 0 );

        if ( is_wp_error( $id ) ) {
            @unlink( $tmp );
            return $id;
        }

        return $id;
    }
}

new Puk_Image_Assign_Helper();
