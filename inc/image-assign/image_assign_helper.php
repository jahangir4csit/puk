<?php
/**
 * Bulk Image Assign Helper Functions
 * 
 * Handles the logic for bulk assigning images to taxonomy terms and product fields
 * by scanning a specific folder in the uploads directory.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Puk_Image_Assign_Helper {

    private $import_dir = 'puk-import';
    private $taxonomy = 'product-family';

    public function __construct() {
        add_action( 'wp_ajax_puk_image_assign_scan', [ $this, 'ajax_scan_folder' ] );
        add_action( 'wp_ajax_puk_image_assign_process_file', [ $this, 'ajax_process_file' ] );
    }

    /**
     * Scans the puk-import folder and returns list of image files.
     */
    public function ajax_scan_folder() {
        check_ajax_referer( 'puk_image_assign_nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Permission denied' );
        }

        $upload_dir = wp_upload_dir();
        $base_path = trailingslashit( $upload_dir['basedir'] ) . $this->import_dir;

        if ( ! is_dir( $base_path ) ) {
            wp_send_json_error( 'Folder /wp-content/uploads/puk-import/ does not exist. Please create it and upload your images.' );
        }

        $files = array_diff( scandir( $base_path ), [ '.', '..' ] );
        $image_files = [];

        foreach ( $files as $file ) {
            $ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
            if ( in_array( $ext, [ 'jpg', 'jpeg', 'png', 'webp', 'gif' ] ) ) {
                $image_files[] = $file;
            }
        }

        wp_send_json_success( [ 'files' => $image_files ] );
    }

    /**
     * Processes a single image file and assigns it.
     */
    public function ajax_process_file() {
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
            wp_send_json_error( "File $filename not found in import folder." );
        }

        // Parse filename logic
        $name_only = pathinfo( $filename, PATHINFO_FILENAME );

        if ( strpos( $name_only, 'fam__' ) === 0 ) {
            // Taxonomy Logic (Stable Hierarchical Approach)
            $result = $this->handle_taxonomy_assignment( $name_only, $file_path );
        } elseif ( strpos( $name_only, 'sf__' ) === 0 ) {
            // Sub Family Logic (Stable Family Code Approach)
            $result = $this->handle_sub_family_assignment( $name_only, $file_path );
        } elseif ( strpos( $name_only, 'acc__' ) === 0 ) {
            // Accessories Taxonomy Logic
            $result = $this->handle_accessories_assignment( $name_only, $file_path );
        } else {
            // Product Logic
            $result = $this->handle_product_assignment( $name_only, $file_path );
        }

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( $result->get_error_message() );
        }

        wp_send_json_success( $result );
    }

    /**
     * Handles Taxonomy Image Assignment with Hierarchical Parsing
     * Format: fam__[FamilyName]__[SubFamilyName]__[Suffix].extension
     * Example: fam__qubo__micro-hp__main.webp
     */
    private function handle_taxonomy_assignment( $name_only, $file_path ) {
        $parts = explode( '__', $name_only );
        
        if ( count( $parts ) < 4 ) {
            return new WP_Error( 'invalid_name', 'Hierarchical filename must be fam__[FamilyName]__[SubFamilyName]__[Suffix].ext (e.g., fam__qubo__micro-hp__main.webp)' );
        }

        $family_name = $parts[1];
        $subfamily_name = $parts[2];
        $suffix = $parts[3];

        // Map suffixes to ACF fields
        $suffix_map = [
            'main'     => 'pf_fet_img',
            'hover'    => 'pf_hover_img',
            'tech'     => 'pf_subfam_tech_drawing',
            'gallery'  => 'pf_subfam_product_image',
            'designer' => 'pf_designed_by'
        ];

        $field_slug = isset( $suffix_map[$suffix] ) ? $suffix_map[$suffix] : $suffix;

        // 1. Find the Family term (Level 1)
        // Convert slugs or simple names back to searchable terms
        $family_terms = get_terms( [
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'name'       => str_replace( '-', ' ', $family_name ), // Try replacing hyphen with space for name
        ] );

        if ( empty( $family_terms ) || is_wp_error( $family_terms ) ) {
             $family_terms = get_terms( [
                'taxonomy'   => $this->taxonomy,
                'slug'       => $family_name,
                'hide_empty' => false,
            ] );
        }

        if ( empty( $family_terms ) || is_wp_error( $family_terms ) ) {
            return new WP_Error( 'not_found', "Family term '$family_name' not found." );
        }

        // 2. Find the Sub Family term (Level 2) under the Family
        $sub_family_id = 0;
        foreach ( $family_terms as $f_term ) {
            // Try searching by name (with space) or slug
            $search_names = [ $subfamily_name, str_replace( '-', ' ', $subfamily_name ) ];
            
            foreach ( $search_names as $s_name ) {
                $sub_terms = get_terms( [
                    'taxonomy'   => $this->taxonomy,
                    'name'       => $s_name,
                    'parent'     => $f_term->term_id,
                    'hide_empty' => false
                ] );

                if ( empty( $sub_terms ) ) {
                     $sub_terms = get_terms( [
                        'taxonomy'   => $this->taxonomy,
                        'slug'       => $s_name,
                        'parent'     => $f_term->term_id,
                        'hide_empty' => false
                    ] );
                }

                if ( ! empty( $sub_terms ) && ! is_wp_error( $sub_terms ) ) {
                    $sub_term = $sub_terms[0];
                    $sub_family_id = $sub_term->term_id;
                    $target_term_name = $sub_term->name;
                    break 2; // Found it
                }
            }
        }

        if ( ! $sub_family_id ) {
            return new WP_Error( 'not_found', "Sub Family '$subfamily_name' not found under Family '$family_name'." );
        }

        // 3. Sideload and Assign
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        update_field( $field_slug, $attachment_id, $this->taxonomy . '_' . $sub_family_id );
        return "Assigned '$field_slug' to Sub Family '$target_term_name' (under $family_name)";
    }

    /**
     * Handles Sub Family Image Assignment with Family Code Matching (Level 2+)
     * Format: sf__[SubFamilyName]__[FamilyCode]__[Suffix].extension
     *
     * Examples:
     * - sf__micro-hp__101601__tech.webp (Single image field - taxonomy)
     * - sf__micro-hp__101601__main.webp (Single image field - taxonomy)
     * - sf__micro-hp__101601__gallery-1.webp (Sub-family gallery - pf_subfam_product_image)
     * - sf__micro-hp__101601__gallery-2.webp (Sub-family gallery - pf_subfam_product_image)
     * - sf__micro-hp__101601__gallery2-1.webp (Product gallery - pro_gallary for all products in sub-family)
     * - sf__micro-hp__101601__gallery3-1.webp (Product sub-gallery - pro_sub_gallary for all products in sub-family)
     *
     * Gallery types:
     * - gallery = Sub-family taxonomy field (pf_subfam_product_image)
     * - gallery2 = Product gallery field (pro_gallary - applies to all products in the sub-family)
     * - gallery3 = Product sub-gallery field (pro_sub_gallary - applies to all products in the sub-family)
     *
     * This assigns images to any sub-level term (Level 2 or deeper) that matches
     * the name and family_code. Skips Level 1 (top-level) terms only.
     * Gallery images are appended to existing gallery array.
     * Hierarchy: Parent (Level 1) -> Sub Family (Level 2) -> Sub Sub Family (Level 3) -> etc.
     */
    private function handle_sub_family_assignment( $name_only, $file_path ) {
        $parts = explode( '__', $name_only );

        if ( count( $parts ) < 4 ) {
            return new WP_Error( 'invalid_name', 'Sub Family filename must be sf__[Name]__[Code]__[Suffix].ext (e.g., sf__micro-hp__101601__tech.webp)' );
        }

        $sf_name = $parts[1];
        $family_code = $parts[2];
        $suffix = $parts[3];

        // Map suffixes to ACF fields
        $suffix_map = [
            'main'     => 'pf_fet_img',
            'hover'    => 'pf_hover_img',
            'tech'     => 'pf_subfam_tech_drawing',
            'designer' => 'pf_designed_by'
        ];

        // Check if this is a product gallery (gallery2-*, gallery3-*)
        if ( strpos( $suffix, 'gallery2-' ) === 0 ) {
            // This is a product gallery2 - route to product handler
            return $this->handle_product_gallery_by_subfamily( $sf_name, $family_code, $file_path, 'gallery2' );
        } elseif ( strpos( $suffix, 'gallery3-' ) === 0 ) {
            // This is a product gallery3 - route to product handler
            return $this->handle_product_gallery_by_subfamily( $sf_name, $family_code, $file_path, 'gallery3' );
        }

        // Check if this is a gallery field for sub-family (gallery, gallery-1, gallery-2, etc.)
        $is_gallery = false;
        $field_slug = null;

        if ( strpos( $suffix, 'gallery' ) === 0 && strpos( $suffix, 'gallery2' ) !== 0 && strpos( $suffix, 'gallery3' ) !== 0 ) {
            // This is 'gallery' or 'gallery-1', 'gallery-2', etc. (but NOT gallery2 or gallery3)
            $field_slug = 'pf_subfam_product_image';
            $is_gallery = true;
        } else {
            // Single field mapping
            $field_slug = isset( $suffix_map[$suffix] ) ? $suffix_map[$suffix] : $suffix;
        }

        // Search for Level 2 terms matching the name/slug and meta field family_code
        // We look for name or slug matching sf_name (with or without hyphens)
        $search_names = [ $sf_name, str_replace( '-', ' ', $sf_name ) ];
        $target_term_id = 0;
        $target_term_name = '';
        $parent_term_name = '';

        foreach ( $search_names as $name_query ) {
            // First try by name with family_code meta
            $terms = get_terms( [
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'name'       => $name_query,
                'meta_query' => [
                    [
                        'key'     => 'family_code',
                        'value'   => $family_code,
                        'compare' => '='
                    ]
                ]
            ] );

            // Fallback to slug search
            if ( empty( $terms ) ) {
                $terms = get_terms( [
                    'taxonomy'   => $this->taxonomy,
                    'hide_empty' => false,
                    'slug'       => $name_query,
                    'meta_query' => [
                        [
                            'key'     => 'family_code',
                            'value'   => $family_code,
                            'compare' => '='
                        ]
                    ]
                ] );
            }

            // Find matching terms (Level 2 or deeper)
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    // Check if this term has a parent (not a top-level term)
                    if ( $term->parent > 0 ) {
                        $target_term_id = $term->term_id;
                        $target_term_name = $term->name;

                        // Get the immediate parent for context
                        $parent_term = get_term( $term->parent, $this->taxonomy );
                        $parent_term_name = $parent_term ? $parent_term->name : '';

                        break 2; // Found matching term, exit both loops
                    }
                }
            }
        }

        if ( ! $target_term_id ) {
            return new WP_Error( 'not_found', "Sub Family '$sf_name' with Family Code '$family_code' not found. Make sure the term exists and is not a top-level (Level 1) term." );
        }

        // Sideload and Assign
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        $term_id_for_acf = $this->taxonomy . '_' . $target_term_id;

        // Handle gallery field differently (append instead of replace)
        if ( $is_gallery ) {
            // Get existing gallery (returns false if empty)
            $existing_gallery = get_field( $field_slug, $term_id_for_acf, false );

            // Initialize as empty array if needed
            if ( ! $existing_gallery || ! is_array( $existing_gallery ) ) {
                $existing_gallery = [];
            }

            // Check if this attachment ID is already in the gallery
            if ( ! in_array( $attachment_id, $existing_gallery ) ) {
                // Add new image to gallery array
                $existing_gallery[] = $attachment_id;

                // Update the field with the new array
                $result = update_field( $field_slug, $existing_gallery, $term_id_for_acf );

                // Log for debugging
                error_log( "Gallery Update - Term ID: $target_term_id, Field: $field_slug, Attachment ID: $attachment_id, Total: " . count($existing_gallery) . ", Update Result: " . ($result ? 'success' : 'failed') );
            }

            $gallery_count = count( $existing_gallery );
            return "Added image (ID: $attachment_id) to gallery '$field_slug' for Sub Family '$target_term_name' under '$parent_term_name' (Code: $family_code). Total images: $gallery_count";
        } else {
            // Single image field - just replace
            update_field( $field_slug, $attachment_id, $term_id_for_acf );
            return "Assigned '$field_slug' to Sub Family '$target_term_name' under '$parent_term_name' (Code: $family_code)";
        }
    }

    /**
     * Handles Product Gallery Assignment by Sub-Family
     * Format: sf__[SubFamilyName]__[FamilyCode]__gallery2-1.extension
     * Example: sf__micro-hp__101601__gallery2-1.webp
     *
     * Finds all products belonging to the specified sub-family and adds the image
     * to their product gallery field.
     */
    private function handle_product_gallery_by_subfamily( $sf_name, $family_code, $file_path, $gallery_type ) {
        // Map gallery types to ACF fields
        $gallery_field_map = [
            'gallery2' => 'pro_gallary',
            'gallery3' => 'pro_sub_gallary',
        ];

        $field_slug = isset( $gallery_field_map[$gallery_type] ) ? $gallery_field_map[$gallery_type] : 'pro_gallary';

        // Find the sub-family term first
        $search_names = [ $sf_name, str_replace( '-', ' ', $sf_name ) ];
        $target_term_id = 0;
        $target_term_name = '';

        foreach ( $search_names as $name_query ) {
            $terms = get_terms( [
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'name'       => $name_query,
                'meta_query' => [
                    [
                        'key'     => 'family_code',
                        'value'   => $family_code,
                        'compare' => '='
                    ]
                ]
            ] );

            if ( empty( $terms ) ) {
                $terms = get_terms( [
                    'taxonomy'   => $this->taxonomy,
                    'hide_empty' => false,
                    'slug'       => $name_query,
                    'meta_query' => [
                        [
                            'key'     => 'family_code',
                            'value'   => $family_code,
                            'compare' => '='
                        ]
                    ]
                ] );
            }

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    if ( $term->parent > 0 ) {
                        $target_term_id = $term->term_id;
                        $target_term_name = $term->name;
                        break 2;
                    }
                }
            }
        }

        if ( ! $target_term_id ) {
            return new WP_Error( 'not_found', "Sub Family '$sf_name' with Family Code '$family_code' not found for product gallery assignment." );
        }

        // Find all products that belong to this sub-family
        $products = get_posts( [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => $this->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $target_term_id,
                ]
            ]
        ] );

        if ( empty( $products ) ) {
            return new WP_Error( 'no_products', "No products found for Sub Family '$target_term_name' (Code: $family_code)." );
        }

        // Sideload the image once
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        $updated_count = 0;
        $product_titles = [];

        // Add image to each product's gallery
        foreach ( $products as $product ) {
            $existing_gallery = get_field( $field_slug, $product->ID, false );

            if ( ! $existing_gallery || ! is_array( $existing_gallery ) ) {
                $existing_gallery = [];
            }

            // Add if not already in gallery
            if ( ! in_array( $attachment_id, $existing_gallery ) ) {
                $existing_gallery[] = $attachment_id;
                update_field( $field_slug, $existing_gallery, $product->ID );
                $updated_count++;
                $product_titles[] = $product->post_title;
            }
        }

        $total_products = count( $products );
        return "Added image (ID: $attachment_id) to $gallery_type field '$field_slug' for $updated_count of $total_products products in Sub Family '$target_term_name' (Code: $family_code).";
    }

    /**
     * Handles Accessories Taxonomy Image Assignment
     * Format: acc__[AccessoryCode].extension
     * Example: acc__AC044.webp
     *
     * Finds accessories term by matching tax_acc__code field and assigns image
     * to the tax_acc_ft__img field.
     */
    private function handle_accessories_assignment( $name_only, $file_path ) {
        $parts = explode( '__', $name_only );

        if ( count( $parts ) < 2 ) {
            return new WP_Error( 'invalid_name', 'Accessories filename must be acc__[Code].ext (e.g., acc__AC044.webp)' );
        }

        $accessory_code = $parts[1];
        $field_slug = 'tax_acc_ft__img';

        // Find accessories term by tax_acc__code meta field
        $terms = get_terms( [
            'taxonomy'   => 'accessories',
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
            return new WP_Error( 'not_found', "Accessories term with code '$accessory_code' not found." );
        }

        $term = $terms[0];
        $term_id = $term->term_id;
        $term_name = $term->name;

        // Sideload and Assign
        $attachment_id = $this->sideload_image( $file_path );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        $term_id_for_acf = 'accessories_' . $term_id;
        update_field( $field_slug, $attachment_id, $term_id_for_acf );

        return "Assigned '$field_slug' to Accessory '$term_name' (Code: $accessory_code, ID: $attachment_id)";
    }

    /**
     * Handles Product Image Assignment
     */
    private function handle_product_assignment( $name_only, $file_path ) {
        $parts = explode( '__', $name_only );
        // [SKU]__[FIELD] -> [0]SKU, [1]FIELD
        $sku = $parts[0];
        $field_slug = isset( $parts[1] ) ? $parts[1] : '_thumbnail_id'; // Default to Featured Image

        // Find Product by SKU
        // First try the custom SKU field 'prod__sku' which seems to be used in the theme
        $products = get_posts( [
            'post_type'  => 'product',
            'meta_query' => [
                [
                    'key'     => 'prod__sku',
                    'value'   => $sku,
                    'compare' => '='
                ]
            ],
            'posts_per_page' => 1
        ] );

        if ( empty( $products ) ) {
            return new WP_Error( 'not_found', "Product with SKU $sku not found." );
        }

        $product_id = $products[0]->ID;
        $attachment_id = $this->sideload_image( $file_path );

        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        if ( $field_slug === '_thumbnail_id' ) {
            set_post_thumbnail( $product_id, $attachment_id );
        } else {
            update_field( $field_slug, $attachment_id, $product_id );
        }

        return "Assigned $field_slug to Product SKU '$sku'";
    }

    /**
     * Sideloads an image from the server local path to Media Library.
     */
    private function sideload_image( $file_path ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $filename = basename( $file_path );

        // Check if image already exists in media library by exact filename match
        global $wpdb;

        // Get all attachments with similar filenames
        $possible_attachments = $wpdb->get_results( $wpdb->prepare(
            "SELECT post_id, meta_value FROM $wpdb->postmeta
             WHERE meta_key = '_wp_attached_file'
             AND meta_value LIKE %s",
            '%' . $wpdb->esc_like( $filename )
        ) );

        // Check for exact filename match (not just partial)
        if ( ! empty( $possible_attachments ) ) {
            foreach ( $possible_attachments as $attachment ) {
                $stored_filename = basename( $attachment->meta_value );
                if ( $stored_filename === $filename ) {
                    return $attachment->post_id;
                }
            }
        }

        // Copy file to a temp location for media_handle_sideload
        $tmp = download_url( str_replace( ABSPATH, site_url( '/' ), $file_path ) );
        if ( is_wp_error( $tmp ) ) {
            // Fallback: manually copy if download_url fails on local path
            $tmp = wp_tempnam( $filename );
            copy( $file_path, $tmp );
        }

        $file_array = [
            'name'     => $filename,
            'tmp_name' => $tmp
        ];

        $id = media_handle_sideload( $file_array, 0 );

        if ( is_wp_error( $id ) ) {
            @unlink( $file_array['tmp_name'] );
            return $id;
        }

        return $id;
    }
}

new Puk_Image_Assign_Helper();
