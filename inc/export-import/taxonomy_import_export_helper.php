<?php
/**
 * Taxonomy Import/Export Helper
 * 
 * Handles CSV export and import for 'product-family' taxonomy with ACF fields.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Puk_Taxonomy_Importer_Exporter {

    private $taxonomy = 'product-family';
    private $acf_fields = [];

    public function __construct() {
        // Load ACF fields configuration for taxonomy
        $this->acf_fields = $this->get_taxonomy_acf_fields_config();
        
        // Debug: Log the loaded ACF fields
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'PUK Taxonomy Export/Import: Loaded ' . count( $this->acf_fields ) . ' ACF fields' );
            foreach ( $this->acf_fields as $field ) {
                error_log( 'PUK Taxonomy Export/Import: Field - ' . $field['label'] . ' (' . $field['name'] . ' - ' . $field['type'] . ')' );
            }
        }
        
        // Handle Actions
        add_action( 'admin_init', [ $this, 'handle_taxonomy_export_request' ] );
        add_action( 'admin_init', [ $this, 'handle_taxonomy_import_request' ] );

        // AJAX Batch Actions
        add_action( 'wp_ajax_puk_get_taxonomy_export_count', [ $this, 'ajax_get_taxonomy_export_count' ] );
        add_action( 'wp_ajax_puk_export_taxonomy_batch', [ $this, 'ajax_export_taxonomy_batch' ] );
        add_action( 'wp_ajax_puk_import_taxonomy_batch', [ $this, 'ajax_import_taxonomy_batch' ] );
        add_action( 'wp_ajax_puk_apply_taxonomy_order_from_csv', [ $this, 'ajax_apply_taxonomy_order_from_csv' ] );
    }

    /**
     * Capitalize term name - converts UPPERCASE or lowercase to Title Case
     * Example: "MINI" becomes "Mini", "hello world" becomes "Hello World"
     *
     * @param string $name The term name to capitalize
     * @return string The capitalized term name
     */
    private function capitalize_term_name( $name ) {
        if ( empty( $name ) ) {
            return $name;
        }

        // Convert to lowercase first, then capitalize each word
        // Using mb_convert_case for proper UTF-8 support
        return mb_convert_case( mb_strtolower( $name, 'UTF-8' ), MB_CASE_TITLE, 'UTF-8' );
    }

    /**
     * Load ACF fields configuration for taxonomy from acf_meta_fields_taxonomy.php
     */
    private function get_taxonomy_acf_fields_config() {
        $config_file = get_template_directory() . '/acf_meta_fields_taxonomy.php';
        
        if ( ! file_exists( $config_file ) ) {
            return [];
        }

        // Get the file content
        $content = file_get_contents( $config_file );
        
        // Parse JSON
        $json_data = json_decode( $content, true );
        
        if ( json_last_error() !== JSON_ERROR_NONE || ! isset( $json_data[0]['fields'] ) ) {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( 'PUK Taxonomy Export/Import: Failed to parse JSON from acf_meta_fields_taxonomy.php' );
            }
            return [];
        }
        
        $fields = [];
        
        // Process each field from the JSON
        foreach ( $json_data[0]['fields'] as $field ) {
            $field_data = [
                'label' => $field['label'],
                'name'  => $field['name'],
                'type'  => $field['type'],
                'sub_fields' => []
            ];
            
            $fields[] = $field_data;
        }
        
        return $fields;
    }

    /**
     * Handles the taxonomy CSV export generation.
     */
    public function handle_taxonomy_export_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'export_taxonomy' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_export_taxonomy'] ) || ! wp_verify_nonce( $_POST['_wpnonce_export_taxonomy'], 'puk_export_taxonomy_nonce' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Set headers for CSV download
        $filename = 'product-family-taxonomy-export-' . date( 'Y-m-d' ) . '.csv';
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );

        // Open output stream
        $output = fopen( 'php://output', 'w' );
        
        // Add BOM for Excel compatibility
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        // Define CSV Headers
        $headers = [
            'Family UID', // Level 1 UID (tax_family__uid)
            'Sub Family UID', // Level 2 UID (tax_family__uid)
            'Sub Sub Family UID', // Level 3 UID (tax_family__uid)
            'Family Code', // ACF field family_code — applies to deepest level in row
            'Main Category', // Level 0
            'Family', // Level 1
            'Sub Family', // Level 2
            'Sub Sub Family', // Level 3
            'Description',
            'Featured Subfamily', // Level 1+ - Image
            'Technical Drawing', // Level 1+
            'Gallery 1', // Level 1+ - Image
            'Gallery 2', // Level 1+ - Image
            'Gallery 3', // Level 1+ - Image
            'Gallery 4', // Level 1+ - Image
            'Designer', // Level 1+
            'Family Features', // Level 1+ - Taxonomy field (features taxonomy)
        ];

        // Add ACF field headers (skip specific fields that are already handled or not needed)
        $skip_acf_labels = [
            'Family UID',
            'Sub Family UID',
            'Sub Sub Family UID',
            'Family Code',
            'Sub Family Code',
            'Sub Sub Family Code',
            'Sub Sub Family Index number',
            'Sub Family Description',
            'Designed By',
            'Sub Family Technical Drawing',
            'Feature Image',
            'Featured Subfamily',
            'Features hover image',
            'Featured Subfamily Hover',
            'Sub Family Product Image',
            'Gallery 1'
        ];

        // Column name mapping for renaming ACF fields in CSV
        $column_name_mapping = [];

        foreach ( $this->acf_fields as $field ) {
            if ( ! in_array( $field['label'], $skip_acf_labels ) ) {
                // Apply column name mapping if exists
                $column_name = isset( $column_name_mapping[ $field['label'] ] ) ? $column_name_mapping[ $field['label'] ] : $field['label'];
                $headers[] = $column_name;
            }
        }

        fputcsv( $output, $headers );

        // Get all taxonomy terms
        $args = [
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'parent'     => 0, // Start with top-level terms
        ];
        $top_level_terms = get_terms( $args );

        // Process all terms recursively
        $this->export_terms_recursively( $top_level_terms, $output );

        fclose( $output );
        exit();
    }

    /**
     * Export terms recursively including children
     */
    private function export_terms_recursively( $terms, $output, $parent_chain = [] ) {
        foreach ( $terms as $term ) {
            if ( is_wp_error( $term ) ) {
                continue;
            }

            // Build hierarchy chain
            $current_chain = array_merge( $parent_chain, [ $term->name ] );
            $level = count( $current_chain ) - 1; // 0-based level

            // Build separate UID columns for Level 1, 2, 3
            $family_uid = '';      // Level 1 UID
            $sub_family_uid = '';  // Level 2 UID
            $sub_sub_family_uid = ''; // Level 3 UID

            // Get UIDs by traversing the hierarchy
            if ( $level >= 1 ) {
                // Build the ancestor chain to get UIDs at each level
                $ancestor_ids = [];
                $current_term_id = $term->term_id;

                // Collect all ancestor IDs including current term
                while ( $current_term_id ) {
                    array_unshift( $ancestor_ids, $current_term_id );
                    $parent_obj = get_term( $current_term_id, $this->taxonomy );
                    if ( $parent_obj && $parent_obj->parent ) {
                        $current_term_id = $parent_obj->parent;
                    } else {
                        break;
                    }
                }

                // ancestor_ids[0] = Level 0 (Main Category - no UID needed)
                // ancestor_ids[1] = Level 1 (Family)
                // ancestor_ids[2] = Level 2 (Sub Family)
                // ancestor_ids[3] = Level 3 (Sub Sub Family)

                if ( isset( $ancestor_ids[1] ) ) {
                    $family_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $ancestor_ids[1] );
                }
                if ( isset( $ancestor_ids[2] ) ) {
                    $sub_family_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $ancestor_ids[2] );
                }
                if ( isset( $ancestor_ids[3] ) ) {
                    $sub_sub_family_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $ancestor_ids[3] );
                }
            }

            // Determine Main Category, Family, Sub Family, Sub Sub Family based on level
            $main_category = isset( $current_chain[0] ) ? $current_chain[0] : '';
            $family = isset( $current_chain[1] ) ? $current_chain[1] : '';
            $sub_family = isset( $current_chain[2] ) ? $current_chain[2] : '';
            $sub_sub_family = isset( $current_chain[3] ) ? $current_chain[3] : '';

            // Get Family UID (tax_family__uid) - For current term only (to maintain existing data during import if needed)
            $family_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $term->term_id );

            // Get Family Code (family_code ACF field) - for all levels (Level 0+)
            $family_code = '';
            if ( $level >= 0 ) {
                $family_code = get_field( 'family_code', $this->taxonomy . '_' . $term->term_id );
            }

            // Get Family, Sub Family and Sub Sub Family specific fields - for Level 1, Level 2 and Level 3
            $featured_image = '';
            $technical_drawing = '';
            $gallery_1 = '';
            $gallery_2 = '';
            $gallery_3 = '';
            $gallery_4 = '';
            $designer = '';
            $family_features = '';
            if ( $level >= 1 ) {
                // Get featured image - export as URL
                $featured_img_data = get_field( 'pf_fet_img', $this->taxonomy . '_' . $term->term_id );
                if ( ! empty( $featured_img_data ) ) {
                    if ( is_array( $featured_img_data ) && isset( $featured_img_data['url'] ) ) {
                        $featured_image = $featured_img_data['url'];
                    } elseif ( is_numeric( $featured_img_data ) ) {
                        // If it's just an ID, get the URL
                        $url = wp_get_attachment_url( $featured_img_data );
                        if ( $url ) {
                            $featured_image = $url;
                        }
                    } elseif ( is_string( $featured_img_data ) && filter_var( $featured_img_data, FILTER_VALIDATE_URL ) ) {
                        // If it's already a URL string
                        $featured_image = $featured_img_data;
                    }
                }
                
                // Get technical drawing image - export as URL
                $tech_drawing_data = get_field( 'pf_subfam_tech_drawing', $this->taxonomy . '_' . $term->term_id );
                if ( ! empty( $tech_drawing_data ) ) {
                    if ( is_array( $tech_drawing_data ) && isset( $tech_drawing_data['url'] ) ) {
                        $technical_drawing = $tech_drawing_data['url'];
                    } elseif ( is_numeric( $tech_drawing_data ) ) {
                        // If it's just an ID, get the URL
                        $url = wp_get_attachment_url( $tech_drawing_data );
                        if ( $url ) {
                            $technical_drawing = $url;
                        }
                    } elseif ( is_string( $tech_drawing_data ) && filter_var( $tech_drawing_data, FILTER_VALIDATE_URL ) ) {
                        // If it's already a URL string
                        $technical_drawing = $tech_drawing_data;
                    }
                }
                
                // Get gallery images - export as individual URLs from 4 image fields
                $gallery_fields = array(
                    'prod_gallery_1' => 'gallery_1',
                    'prod_gallery_2' => 'gallery_2',
                    'prod_gallery_3' => 'gallery_3',
                    'prod_gallery_4' => 'gallery_4',
                );
                foreach ( $gallery_fields as $acf_field => $var_name ) {
                    $img = get_field( $acf_field, $this->taxonomy . '_' . $term->term_id );
                    if ( ! empty( $img ) ) {
                        if ( is_array( $img ) && isset( $img['url'] ) ) {
                            $$var_name = $img['url'];
                        } elseif ( is_numeric( $img ) ) {
                            $url = wp_get_attachment_url( $img );
                            if ( $url ) {
                                $$var_name = $url;
                            }
                        } elseif ( is_string( $img ) && filter_var( $img, FILTER_VALIDATE_URL ) ) {
                            $$var_name = $img;
                        }
                    }
                }
                
                $designer = get_field( 'pf_designed_by', $this->taxonomy . '_' . $term->term_id );

                // Get sub family features - taxonomy field (features taxonomy)
                // Export as comma-separated term slugs for portability
                $features_data = get_field( 'tax_sub_family_features', $this->taxonomy . '_' . $term->term_id );
                if ( ! empty( $features_data ) && is_array( $features_data ) ) {
                    $feature_slugs = [];
                    foreach ( $features_data as $feature_term_id ) {
                        $feature_term = get_term( $feature_term_id, 'features' );
                        if ( $feature_term && ! is_wp_error( $feature_term ) ) {
                            $feature_slugs[] = $feature_term->slug;
                        }
                    }
                    $family_features = implode( ',', $feature_slugs );
                }
            }
            
            // Basic Term Data
            $row = [
                $family_uid ?: '', // Family UID (Level 1)
                $sub_family_uid ?: '', // Sub Family UID (Level 2)
                $sub_sub_family_uid ?: '', // Sub Sub Family UID (Level 3)
                $level >= 1 ? $family_code : '', // Family Code — applies to deepest level in row
                $main_category,
                $family,
                $sub_family,
                $sub_sub_family,
                $level >= 1 ? $term->description : '', // Description only for Level 1+ (Family, Sub Family, Sub Sub Family)
                $featured_image ?: '', // Featured Subfamily - Level 1+
                $technical_drawing ?: '', // Technical Drawing - Level 1+
                $gallery_1 ?: '', // Gallery 1 - Level 1+
                $gallery_2 ?: '', // Gallery 2 - Level 1+
                $gallery_3 ?: '', // Gallery 3 - Level 1+
                $gallery_4 ?: '', // Gallery 4 - Level 1+
                $designer ?: '', // Designer - Level 1+
                $family_features ?: '', // Family Features - Level 1+
            ];
            
            // Add ACF field values (skip specific fields)
            $skip_acf_labels = [
                'Family UID',
                'Sub Family UID',
                'Sub Sub Family UID',
                'Family Code',
                'Sub Family Code',
                'Sub Sub Family Code',
                'Sub Sub Family Index number',
                'Sub Family Description',
                'Designed By',
                'Sub Family Technical Drawing',
                'Feature Image',
                'Featured Subfamily',
                'Features hover image',
                'Featured Subfamily Hover',
                'Sub Family Product Image',
                'Gallery 1'
            ];

            foreach ( $this->acf_fields as $field ) {
                if ( in_array( $field['label'], $skip_acf_labels ) ) {
                    continue; // Skip this field
                }
                
                $field_value = $this->get_taxonomy_acf_field_value( $term->term_id, $field );
                $row[] = $field_value;
                
                // Debug: Log if field value is empty
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG && empty( $field_value ) ) {
                    error_log( "PUK Taxonomy Export/Import: Empty value for field '{$field['name']}' in term {$term->term_id}" );
                }
            }

            fputcsv( $output, $row );

            // Get children and recurse
            $child_args = [
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'parent'     => $term->term_id,
            ];
            $children = get_terms( $child_args );
            
            if ( ! empty( $children ) && ! is_wp_error( $children ) ) {
                $this->export_terms_recursively( $children, $output, $current_chain );
            }
        }
    }

    /**
     * Get ACF field value for taxonomy term
     */
    private function get_taxonomy_acf_field_value( $term_id, $field ) {
        // For taxonomy terms, we need to add the taxonomy prefix to the field name
        $value = get_field( $field['name'], $this->taxonomy . '_' . $term_id );
        
        if ( empty( $value ) ) {
            return '';
        }
        
        switch ( $field['type'] ) {
            case 'repeater':
                // Return JSON-encoded repeater data
                if ( is_array( $value ) && ! empty( $value ) ) {
                    $repeater_data = [];
                    
                    foreach ( $value as $row ) {
                        $row_data = [];
                        
                        // Process all fields in the row
                        foreach ( $row as $key => $sub_value ) {
                            // Skip ACF internal fields
                            if ( strpos( $key, 'acf_fc_' ) === 0 ) {
                                continue;
                            }
                            
                            if ( ! empty( $sub_value ) ) {
                                // Handle arrays (images/files)
                                if ( is_array( $sub_value ) ) {
                                    if ( isset( $sub_value['ID'] ) ) {
                                        $row_data[ $key ] = $sub_value['ID'];
                                    } elseif ( isset( $sub_value['url'] ) ) {
                                        $row_data[ $key ] = $sub_value['url'];
                                    } else {
                                        $row_data[ $key ] = maybe_serialize( $sub_value );
                                    }
                                } else {
                                    $row_data[ $key ] = $sub_value;
                                }
                            }
                        }
                        
                        // Only add row if it has data
                        if ( ! empty( $row_data ) ) {
                            $repeater_data[] = $row_data;
                        }
                    }
                    
                    // Only return JSON if we have actual data
                    if ( ! empty( $repeater_data ) ) {
                        return json_encode( $repeater_data, JSON_UNESCAPED_UNICODE );
                    }
                }
                return '';
                
            case 'gallery':
                // Return comma-separated image IDs
                if ( is_array( $value ) ) {
                    $ids = array_map( function( $img ) {
                        return is_array( $img ) ? $img['ID'] : $img;
                    }, $value );
                    return implode( ',', $ids );
                }
                return '';
                
            case 'image':
                // Return image ID or URL
                if ( is_array( $value ) ) {
                    return isset( $value['ID'] ) ? $value['ID'] : ( isset( $value['url'] ) ? $value['url'] : '' );
                }
                return $value;
                
            case 'file':
                // Return file URL
                if ( is_array( $value ) ) {
                    return isset( $value['url'] ) ? $value['url'] : '';
                }
                return $value;
                
            case 'color_picker':
                // Return hex color value
                return $value;
                
            case 'wysiwyg':
                // Return HTML content as-is
                return $value;
                
            case 'select':
            case 'text':
            case 'textarea':
            default:
                // Return as-is for text fields
                return $value;
        }
    }

    /**
     * Handles the taxonomy import logic.
     */
    public function handle_taxonomy_import_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'import_taxonomy' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_import_taxonomy'] ) || ! wp_verify_nonce( $_POST['_wpnonce_import_taxonomy'], 'puk_import_taxonomy_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'puk' ) );
        }

        if ( empty( $_FILES['import_taxonomy_file']['tmp_name'] ) ) {
            wp_die( __( 'No file uploaded.', 'puk' ) );
        }

        $file_path = $_FILES['import_taxonomy_file']['tmp_name'];
        $handle = fopen( $file_path, 'r' );

        if ( $handle === false ) {
            wp_die( __( 'Could not open file.', 'puk' ) );
        }

        // Detect delimiter
        $delimiter = ',';
        $preview = fgets( $handle );
        // Simple heuristic: count occurrences
        if ( substr_count( $preview, ';' ) > substr_count( $preview, ',' ) ) {
            $delimiter = ';';
        }
        rewind( $handle );

        // Read and Sanitize Headers
        $headers = fgetcsv( $handle, 0, $delimiter );
        if ( $headers ) {
            // Sanitize headers: strip BOM, quotes and trim
            $headers = array_map( function( $header ) {
                $header = preg_replace( '/[\x{FEFF}\x{200B}\x{200C}\x{200D}]/u', '', $header ); // Remove BOM and hidden chars
                $header = trim( $header, " \t\n\r\0\x0B\"'" ); // Strip quotes and whitespace
                return strtolower( $header );
            }, $headers );
            
            error_log( "PUK Taxonomy Import: Sanitized Headers: " . print_r( $headers, true ) );
        }
        
        // Verify required columns exist
        $required_columns = ['main category', 'family', 'sub family', 'sub sub family'];
        $has_hierarchy = false;
        foreach ( $required_columns as $col ) {
            if ( in_array( $col, $headers ) ) {
                $has_hierarchy = true;
                break;
            }
        }

        if ( ! $has_hierarchy ) {
            $msg = "CRITICAL ERROR: At least one hierarchy column (Main Category, Family, Sub Family, or Sub Sub Family) must be present in CSV. Found headers: " . implode( ', ', $headers );
            error_log( $msg );
            add_action( 'admin_notices', function() use ( $msg ) {
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
            });
            fclose( $handle );
            return;
        }

        $imported_count = 0;
        $row_count = 0;
        $errors = [];
        $created_terms_cache = []; // Cache for created/found terms by name

        while ( ( $row = fgetcsv( $handle, 0, $delimiter ) ) !== false ) {
            $row_count++;
            $row = array_map( 'trim', $row );
            
            // Combine headers with row data
            if ( count( $headers ) !== count( $row ) ) {
                $msg = "Row $row_count: Column count mismatch. Headers: " . count($headers) . ", Row: " . count($row);
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }
            $item = array_combine( $headers, $row );

            // Prepare Term Data from hierarchy columns
            $family_uid = isset( $item['family uid'] ) ? trim( $item['family uid'] ) : ''; // Level 1 UID (tax_family__uid)
            $sub_family_uid = isset( $item['sub family uid'] ) ? trim( $item['sub family uid'] ) : ''; // Level 2 UID (tax_family__uid)
            $sub_sub_family_uid = isset( $item['sub sub family uid'] ) ? trim( $item['sub sub family uid'] ) : ''; // Level 3 UID (tax_family__uid)
            $family_code_val = isset( $item['family code'] ) ? trim( $item['family code'] ) : ''; // applies to deepest level in row
            $main_category = isset( $item['main category'] ) ? $this->capitalize_term_name( trim( $item['main category'] ) ) : '';
            $family = isset( $item['family'] ) ? $this->capitalize_term_name( trim( $item['family'] ) ) : '';
            $sub_family = isset( $item['sub family'] ) ? $this->capitalize_term_name( trim( $item['sub family'] ) ) : '';
            $sub_sub_family = isset( $item['sub sub family'] ) ? $this->capitalize_term_name( trim( $item['sub sub family'] ) ) : '';
            $term_description = isset( $item['description'] ) ? $item['description'] : '';

            // Level 1+ specific fields
            $featured_image = isset( $item['featured subfamily'] ) ? trim( $item['featured subfamily'] ) : '';
            $technical_drawing = isset( $item['technical drawing'] ) ? trim( $item['technical drawing'] ) : '';
            $gallery_1 = isset( $item['gallery 1'] ) ? trim( $item['gallery 1'] ) : '';
            $gallery_2 = isset( $item['gallery 2'] ) ? trim( $item['gallery 2'] ) : '';
            $gallery_3 = isset( $item['gallery 3'] ) ? trim( $item['gallery 3'] ) : '';
            $gallery_4 = isset( $item['gallery 4'] ) ? trim( $item['gallery 4'] ) : '';
            $designer = isset( $item['designer'] ) ? trim( $item['designer'] ) : '';
            $family_features = isset( $item['family features'] ) ? trim( $item['family features'] ) : '';

            // Determine which term to create/update based on hierarchy
            $term_name = '';
            $parent_term_id = 0;
            $level = -1;

            // 1. Resolve Level 0 (Main Category) - Always by Name
            if ( ! empty( $main_category ) ) {
                $main_cat_id = $this->find_or_create_term_by_name( $main_category, 0, $created_terms_cache );
                if ( ! $main_cat_id ) {
                    $msg = "Row $row_count: Failed to find/create Main Category '{$main_category}'";
                    error_log( $msg );
                    $errors[] = $msg;
                    continue;
                }
                
                $parent_term_id = $main_cat_id;
                $term_name = $main_category;
                $level = 0;
            } else {
                $msg = "Row $row_count: Main Category is required.";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            // 2. Resolve Level 1+ (Family, Sub Family, Sub Sub Family) using separate UID columns
            if ( ! empty( $family ) ) {
                // Level 1: Family - use family_uid if provided
                $family_id = $this->find_or_create_term_by_hybrid( $family, $main_cat_id, $family_uid, $created_terms_cache, 1 );
                $new_term_id = $family_id;
                $term_name = $family;
                $level = 1;

                if ( $family_id && ! empty( $sub_family ) ) {
                    // Level 2: Sub Family - use sub_family_uid if provided
                    $sub_family_id_result = $this->find_or_create_term_by_hybrid( $sub_family, $family_id, $sub_family_uid, $created_terms_cache, 2 );
                    $new_term_id = $sub_family_id_result;
                    $term_name = $sub_family;
                    $level = 2;

                    if ( $sub_family_id_result && ! empty( $sub_sub_family ) ) {
                        // Level 3: Sub Sub Family - use sub_sub_family_uid if provided
                        $sub_sub_family_id_result = $this->find_or_create_term_by_hybrid( $sub_sub_family, $sub_family_id_result, $sub_sub_family_uid, $created_terms_cache, 3 );
                        $new_term_id = $sub_sub_family_id_result;
                        $term_name = $sub_sub_family;
                        $level = 3;
                    }
                }
            } else {
                $new_term_id = $main_cat_id; // It's just Level 0
            }

            if ( ! $new_term_id ) {
                $msg = "Row $row_count: Failed to create/update term '{$term_name}'";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            if ( ! $new_term_id ) {
                $msg = "Row $row_count: Failed to create/update term '{$term_name}'";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            // Handle ACF fields for Level 1+ terms
            // tax_family__uid and family_code are now handled by the generic ACF loop below
            // No manual update_field calls needed here anymore

            // Handle Family, Sub Family and Sub Sub Family specific fields - for Level 1, Level 2 and Level 3 terms
            if ( $level >= 1 ) {
                // Update term description for Level 1+ only (not for Main Category)
                if ( ! empty( $term_description ) ) {
                    wp_update_term( $new_term_id, $this->taxonomy, [
                        'description' => $term_description
                    ]);
                }
                // Handle Featured Subfamily image
                if ( ! empty( $featured_image ) ) {
                    // Check if it's a URL or an ID
                    if ( filter_var( $featured_image, FILTER_VALIDATE_URL ) ) {
                        // It's a URL, download and create attachment
                        $attachment_id = $this->insert_attachment_from_url( $featured_image );
                        if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                            update_field( 'pf_fet_img', $attachment_id, $this->taxonomy . '_' . $new_term_id );
                        }
                    } elseif ( is_numeric( $featured_image ) ) {
                        // It's already an attachment ID
                        update_field( 'pf_fet_img', intval( $featured_image ), $this->taxonomy . '_' . $new_term_id );
                    }
                }
                
                // Handle Technical Drawing image
                if ( ! empty( $technical_drawing ) ) {
                    // Check if it's a URL or an ID
                    if ( filter_var( $technical_drawing, FILTER_VALIDATE_URL ) ) {
                        // It's a URL, download and create attachment
                        $attachment_id = $this->insert_attachment_from_url( $technical_drawing );
                        if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                            update_field( 'pf_subfam_tech_drawing', $attachment_id, $this->taxonomy . '_' . $new_term_id );
                        }
                    } elseif ( is_numeric( $technical_drawing ) ) {
                        // It's already an attachment ID
                        update_field( 'pf_subfam_tech_drawing', intval( $technical_drawing ), $this->taxonomy . '_' . $new_term_id );
                    }
                }
                
                // Handle Gallery images - import to 4 individual image fields
                $gallery_data = array(
                    'prod_gallery_1' => $gallery_1,
                    'prod_gallery_2' => $gallery_2,
                    'prod_gallery_3' => $gallery_3,
                    'prod_gallery_4' => $gallery_4,
                );

                foreach ( $gallery_data as $acf_field => $gallery_value ) {
                    if ( empty( $gallery_value ) ) {
                        continue;
                    }

                    $attachment_id = null;
                    if ( filter_var( $gallery_value, FILTER_VALIDATE_URL ) ) {
                        $attachment_id = $this->insert_attachment_from_url( $gallery_value );
                    } elseif ( is_numeric( $gallery_value ) ) {
                        $attachment_id = intval( $gallery_value );
                    }

                    if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                        update_field( $acf_field, $attachment_id, $this->taxonomy . '_' . $new_term_id );
                    }
                }

                // Save family_code — applies to whatever the deepest level in this row is
                if ( ! empty( $family_code_val ) ) {
                    update_field( 'family_code', $family_code_val, $this->taxonomy . '_' . $new_term_id );
                }

                if ( ! empty( $designer ) ) {
                    update_field( 'pf_designed_by', $designer, $this->taxonomy . '_' . $new_term_id );
                }

                // Handle Family Features - taxonomy field (features taxonomy)
                // Expecting comma-separated term slugs
                if ( ! empty( $family_features ) ) {
                    $feature_slugs = array_map( 'trim', explode( ',', $family_features ) );
                    $feature_term_ids = [];
                    foreach ( $feature_slugs as $slug ) {
                        if ( empty( $slug ) ) continue;

                        // Try to find the feature term by slug
                        $feature_term = get_term_by( 'slug', $slug, 'features' );
                        if ( $feature_term && ! is_wp_error( $feature_term ) ) {
                            $feature_term_ids[] = $feature_term->term_id;
                        } else {
                            // Log if term not found (don't create it, just skip)
                            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                                error_log( "PUK Taxonomy Import: Feature term not found with slug '{$slug}'" );
                            }
                        }
                    }
                    if ( ! empty( $feature_term_ids ) ) {
                        update_field( 'tax_sub_family_features', $feature_term_ids, $this->taxonomy . '_' . $new_term_id );
                    }
                }
            }

            // Handle ACF Fields
            $skip_acf_labels = [
                'Family UID', // Skip - handled separately via UID columns
                'Sub Family UID',
                'Sub Sub Family UID',
                'Family Code',
                'Sub Family Code',
                'Sub Sub Family Code',
                'Sub Sub Family Index number',
                'Sub Family Description',
                'Designed By',
                'Sub Family Technical Drawing',
                'Feature Image',
                'Featured Subfamily',
                'Features hover image',
                'Featured Subfamily Hover',
                'Sub Family Product Image',
                'Gallery 1'
            ];

            // Column name mapping for renaming ACF fields in CSV
            $column_name_mapping = [];

            // Create reverse mapping for import
            $reverse_mapping = array_flip( $column_name_mapping );

            foreach ( $this->acf_fields as $field ) {
                // Skip fields that are handled via manual methods (images/gallery) or level-based logic (family_code/uid)
                if ( in_array( $field['name'], ['family_code', 'pf_fet_img', 'pf_subfam_tech_drawing', 'pf_designed_by', 'tax_family__uid'] ) ) {
                    continue;
                }
                
                // Skip fields by label
                if ( in_array( $field['label'], $skip_acf_labels ) ) {
                    continue;
                }
                
                // Check both original field label and mapped column name
                $column_key = strtolower( $field['label'] );
                $mapped_column_key = isset( $column_name_mapping[ $field['label'] ] ) ? strtolower( $column_name_mapping[ $field['label'] ] ) : null;
                
                $value = null;
                if ( isset( $item[ $column_key ] ) && $item[ $column_key ] !== '' ) {
                    $value = $item[ $column_key ];
                } elseif ( $mapped_column_key && isset( $item[ $mapped_column_key ] ) && $item[ $mapped_column_key ] !== '' ) {
                    $value = $item[ $mapped_column_key ];
                }
                
                if ( $value !== null ) {
                    try {
                        $this->set_taxonomy_acf_field_value( $new_term_id, $field, $value );
                    } catch ( Exception $e ) {
                        error_log( 'ACF Taxonomy Import Error for field ' . $field['name'] . ': ' . $e->getMessage() );
                    }
                }
            }

            $imported_count++;
        }

        fclose( $handle );

        error_log( "Taxonomy Import completed: $imported_count terms imported out of $row_count rows processed" );

        // Fire action for rewrite rules flush
        do_action( 'puk_taxonomy_import_complete', $imported_count );

        add_action( 'admin_notices', function() use ( $imported_count, $errors ) {
            $class = $imported_count > 0 ? 'notice-success' : 'notice-warning';
            echo '<div class="notice ' . $class . ' is-dismissible"><p>' . sprintf( __( '%d taxonomy terms imported successfully.', 'puk' ), $imported_count ) . '</p></div>';
            
            if ( ! empty( $errors ) ) {
                echo '<div class="notice notice-error is-dismissible"><p><strong>Import Errors:</strong></p><ul>';
                foreach ( array_slice($errors, 0, 10) as $error ) {
                    echo '<li>' . esc_html( $error ) . '</li>';
                }
                if ( count($errors) > 10 ) {
                    echo '<li>... and ' . (count($errors) - 10) . ' more errors.</li>';
                }
                echo '</ul></div>';
            }
        });
    }

    /**
     * Find or create Level 0 term strictly by Name
     */
    private function find_or_create_term_by_name( $name, $parent_id, &$cache ) {
        $cache_key = 'name_' . $name . '_' . $parent_id;
        if ( isset( $cache[$cache_key] ) ) return $cache[$cache_key];

        $term = get_term_by( 'name', $name, $this->taxonomy );
        // Ensure it's level 0 (parent is 0)
        if ( $term && $term->parent != 0 ) {
             $term = null; // Don't match if it's not Level 0
        }

        if ( $term ) {
            $term_id = $term->term_id;
            // Ensure UID exists for Main Category even if matched by name
            $existing_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $term_id );
            if ( empty( $existing_uid ) && function_exists( 'puk_generate_unique_family_uid' ) ) {
                $new_uid = puk_generate_unique_family_uid();
                update_field( 'tax_family__uid', $new_uid, $this->taxonomy . '_' . $term_id );
            }
        } else {
            $result = wp_insert_term( $name, $this->taxonomy, [ 'parent' => 0 ] );
            if ( is_wp_error( $result ) ) return false;
            $term_id = $result['term_id'];
            
            // Generate UID for new Main Category
            if ( function_exists( 'puk_generate_unique_family_uid' ) ) {
                $new_uid = puk_generate_unique_family_uid();
                update_field( 'tax_family__uid', $new_uid, $this->taxonomy . '_' . $term_id );
            }
        }

        $cache[$cache_key] = $term_id;
        return $term_id;
    }

    /**
     * Find or create Level 1+ term strictly by UID
     */
    private function find_or_create_term_by_uid( $name, $parent_id, $uid, &$cache, $level ) {
        $cache_key = 'uid_' . $uid;
        if ( isset( $cache[$cache_key] ) ) return $cache[$cache_key];

        $terms = get_terms([
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'meta_query' => [
                [
                    'key'     => 'tax_family__uid',
                    'value'   => $uid,
                    'compare' => '='
                ]
            ]
        ]);

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $term = $terms[0];
            // Update name and parent if changed
            wp_update_term( $term->term_id, $this->taxonomy, [
                'name'   => $name,
                'parent' => $parent_id
            ]);
            $term_id = $term->term_id;
        } else {
            $result = wp_insert_term( $name, $this->taxonomy, [ 'parent' => $parent_id ] );
            if ( is_wp_error( $result ) ) return false;
            $term_id = $result['term_id'];
            
            // If UID is empty, generate one
            if ( empty( $uid ) && function_exists( 'puk_generate_unique_family_uid' ) ) {
                $uid = puk_generate_unique_family_uid();
            }
            
            if ( ! empty( $uid ) ) {
                update_field( 'tax_family__uid', $uid, $this->taxonomy . '_' . $term_id );
            }
        }

        $cache[$cache_key] = $term_id;
        return $term_id;
    }

    /**
     * Hybrid resolver for Level 1+ (tries UID if provided, falls back to Name+Parent)
     */
    private function find_or_create_term_by_hybrid( $name, $parent_id, $uid, &$cache, $level ) {
        if ( ! empty( $uid ) ) {
            return $this->find_or_create_term_by_uid( $name, $parent_id, $uid, $cache, $level );
        }

        $cache_key = 'hybrid_' . $name . '_' . $parent_id;
        if ( isset( $cache[$cache_key] ) ) return $cache[$cache_key];

        $term = get_term_by( 'name', $name, $this->taxonomy );
        // Verify parent match for safety
        if ( $term && $term->parent != $parent_id ) {
            $term = null;
        }

        if ( $term ) {
            $term_id = $term->term_id;
            // Ensure UID exists for hybrid matches
            $existing_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $term_id );
            if ( empty( $existing_uid ) && function_exists( 'puk_generate_unique_family_uid' ) ) {
                $new_uid = puk_generate_unique_family_uid();
                update_field( 'tax_family__uid', $new_uid, $this->taxonomy . '_' . $term_id );
            }
        } else {
            $result = wp_insert_term( $name, $this->taxonomy, [ 'parent' => $parent_id ] );
            if ( is_wp_error( $result ) ) return false;
            $term_id = $result['term_id'];
            
            // Generate UID for new term
            if ( function_exists( 'puk_generate_unique_family_uid' ) ) {
                $new_uid = puk_generate_unique_family_uid();
                update_field( 'tax_family__uid', $new_uid, $this->taxonomy . '_' . $term_id );
            }
        }

        $cache[$cache_key] = $term_id;
        return $term_id;
    }

    /**
     * Set ACF field value for taxonomy term
     */
    private function set_taxonomy_acf_field_value( $term_id, $field, $value ) {
        $value = trim( $value );
        
        if ( $value === '' ) {
            return;
        }
        
        switch ( $field['type'] ) {
            case 'repeater':
                // Expecting JSON-encoded repeater data
                $decoded = json_decode( $value, true );
                
                if ( is_array( $decoded ) && ! empty( $decoded ) ) {
                    $repeater_rows = [];
                    
                    foreach ( $decoded as $row_data ) {
                        $row = [];
                        
                        // Process all fields in the row data
                        foreach ( $row_data as $key => $sub_value ) {
                            if ( $sub_value !== '' ) {
                                // Try to determine field type based on key
                                $sub_field_type = 'text';
                                
                                // Check if this is an image field
                                if ( strpos( $key, 'img' ) !== false || strpos( $key, 'image' ) !== false ) {
                                    $sub_field_type = 'image';
                                }
                                // Check if this is a file field
                                elseif ( strpos( $key, 'file' ) !== false ) {
                                    $sub_field_type = 'file';
                                }
                                
                                switch ( $sub_field_type ) {
                                    case 'image':
                                        // Expecting image ID
                                        if ( is_numeric( $sub_value ) ) {
                                            $row[ $key ] = intval( $sub_value );
                                        }
                                        break;
                                        
                                    case 'file':
                                        // Can be file URL or ID
                                        if ( is_numeric( $sub_value ) ) {
                                            $row[ $key ] = intval( $sub_value );
                                        } elseif ( filter_var( $sub_value, FILTER_VALIDATE_URL ) ) {
                                            $file_id = $this->insert_attachment_from_url( $sub_value );
                                            if ( $file_id && ! is_wp_error( $file_id ) ) {
                                                $row[ $key ] = $file_id;
                                            }
                                        }
                                        break;
                                        
                                    case 'textarea':
                                    case 'text':
                                    default:
                                        $row[ $key ] = sanitize_text_field( $sub_value );
                                        break;
                                }
                            }
                        }
                        
                        $repeater_rows[] = $row;
                    }
                    
                    if ( ! empty( $repeater_rows ) ) {
                        update_field( $field['name'], $repeater_rows, $this->taxonomy . '_' . $term_id );
                    }
                }
                break;
                
                if ( ! empty( $image_ids ) ) {
                    update_field( $field['name'], $image_ids, $this->taxonomy . '_' . $term_id );
                } elseif ( ! empty( $items ) ) {
                    // Try to handle URLs if no numeric IDs found
                    foreach ( $items as $item ) {
                        if ( filter_var( $item, FILTER_VALIDATE_URL ) ) {
                            $attachment_id = $this->insert_attachment_from_url( $item );
                            if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                                $image_ids[] = $attachment_id;
                            }
                        }
                    }
                    if ( ! empty( $image_ids ) ) {
                        update_field( $field['name'], $image_ids, $this->taxonomy . '_' . $term_id );
                    }
                }
                break;
                
            case 'image':
                // Expecting image ID or URL
                if ( is_numeric( $value ) ) {
                    update_field( $field['name'], intval( $value ), $this->taxonomy . '_' . $term_id );
                } elseif ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
                    $attachment_id = $this->insert_attachment_from_url( $value );
                    if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                        update_field( $field['name'], $attachment_id, $this->taxonomy . '_' . $term_id );
                    }
                }
                break;
                
            case 'file':
                // Can be file URL or ID
                if ( is_numeric( $value ) ) {
                    update_field( $field['name'], intval( $value ), $this->taxonomy . '_' . $term_id );
                } elseif ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
                    $file_id = $this->insert_attachment_from_url( $value );
                    if ( $file_id && ! is_wp_error( $file_id ) ) {
                        update_field( $field['name'], $file_id, $this->taxonomy . '_' . $term_id );
                    }
                }
                break;
                
            case 'color_picker':
                // Validate hex color
                if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
                    update_field( $field['name'], $value, $this->taxonomy . '_' . $term_id );
                }
                break;
                
            case 'wysiwyg':
                // Store HTML content with sanitization
                update_field( $field['name'], wp_kses_post( $value ), $this->taxonomy . '_' . $term_id );
                break;
                
            case 'select':
                // Store select value as-is
                update_field( $field['name'], $value, $this->taxonomy . '_' . $term_id );
                break;
                
            case 'text':
            case 'textarea':
            default:
                update_field( $field['name'], sanitize_text_field( $value ), $this->taxonomy . '_' . $term_id );
                break;
        }
    }

    /**
     * Helper function to insert a file attachment from a URL
     */
    private function insert_attachment_from_url( $url ) {
        // Look for an existing attachment with the same filename
        $filename = basename( $url );
        $existing_attachments = get_posts([
            'post_type'      => 'attachment',
            'meta_key'       => '_wp_attached_file',
            'meta_value'     => $filename,
            'meta_compare'   => 'LIKE',
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);

        if ( ! empty( $existing_attachments ) ) {
            return $existing_attachments[0];
        }

        // Download the file
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $tmp = download_url( $url );
        
        if ( is_wp_error( $tmp ) ) {
            error_log( "File download failed for $url: " . $tmp->get_error_message() );
            return false;
        }

        $file_array = [
            'name'     => $filename,
            'tmp_name' => $tmp,
        ];

        // Insert the attachment
        $attachment_id = media_handle_sideload( $file_array );

        // Clean up temp file
        if ( file_exists( $tmp ) ) {
            @unlink( $tmp );
        }

        if ( is_wp_error( $attachment_id ) ) {
            error_log( "Attachment creation failed for $url: " . $attachment_id->get_error_message() );
            return false;
        }

        return $attachment_id;
    }

    /**
     * AJAX: Get total taxonomy term count for export
     */
    public function ajax_get_taxonomy_export_count() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) error_log( 'PUK AJAX: ajax_get_taxonomy_export_count initiated' );

        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce'] : ( isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '' );

        if ( ! wp_verify_nonce( $nonce, 'puk_taxonomy_export_nonce' ) ) {
            error_log( 'PUK AJAX: Nonce verification failed for taxonomy export count' );
            wp_send_json_error( 'Security check failed' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            error_log( 'PUK AJAX: Permission denied for taxonomy export count' );
            wp_send_json_error( 'Permission denied' );
        }

        // Count all terms
        $terms = get_terms([
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'fields'     => 'ids'
        ]);

        $total = is_array( $terms ) ? count( $terms ) : 0;

        // Get headers
        $headers = $this->get_taxonomy_export_headers();

        wp_send_json_success( [ 'total' => $total, 'headers' => $headers ] );
    }

    /**
     * AJAX: Get a batch of taxonomy terms for export
     */
    public function ajax_export_taxonomy_batch() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) error_log( 'PUK AJAX: ajax_export_taxonomy_batch initiated' );

        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce'] : ( isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '' );
        if ( ! wp_verify_nonce( $nonce, 'puk_taxonomy_export_nonce' ) ) {
            wp_send_json_error( 'Security check failed' );
        }

        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Permission denied' );

        $offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;
        $terms_per_page = 20;

        // Get all terms flattened (we need to maintain hierarchy order)
        $all_terms = $this->get_all_terms_flattened();

        // Slice for current batch
        $batch_terms = array_slice( $all_terms, $offset, $terms_per_page );

        $rows = [];
        foreach ( $batch_terms as $term_data ) {
            $rows[] = $this->get_term_export_row( $term_data['term'], $term_data['parent_chain'] );
        }

        wp_send_json_success( [ 'rows' => $rows, 'count' => count( $rows ) ] );
    }

    /**
     * AJAX: Batch import taxonomy terms from JSON payload
     */
    public function ajax_import_taxonomy_batch() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) error_log( 'PUK AJAX: ajax_import_taxonomy_batch initiated' );

        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce'] : ( isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '' );
        if ( ! wp_verify_nonce( $nonce, 'puk_taxonomy_import_nonce' ) ) {
            wp_send_json_error( 'Security check failed' );
        }

        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Permission denied' );

        $batch_data = isset( $_POST['batch_data'] ) ? $_POST['batch_data'] : [];
        if ( is_string( $batch_data ) ) {
            $batch_data = json_decode( stripslashes( $batch_data ), true );
        }

        if ( empty( $batch_data ) || ! is_array( $batch_data ) ) {
            wp_send_json_error( 'No valid batch data provided' );
        }

        $imported_count = 0;
        $errors = [];
        $created_terms_cache = [];

        foreach ( $batch_data as $index => $item ) {
            $row_num = isset( $_POST['start_row'] ) ? intval( $_POST['start_row'] ) + $index : $index + 1;
            $result = $this->process_taxonomy_import_row( $item, $row_num, $errors, $imported_count, $created_terms_cache );
        }

        // Fire action for rewrite rules flush (batch import)
        do_action( 'puk_taxonomy_import_batch_complete', $imported_count );

        wp_send_json_success( [
            'imported' => $imported_count,
            'errors' => $errors,
            'error_count' => count( $errors )
        ] );
    }

    /**
     * Get taxonomy export headers
     */
    private function get_taxonomy_export_headers() {
        $headers = [
            'Family UID',
            'Sub Family UID',
            'Sub Sub Family UID',
            'Family Code',
            'Main Category',
            'Family',
            'Sub Family',
            'Sub Sub Family',
            'Description',
            'Featured Subfamily',
            'Technical Drawing',
            'Gallery 1',
            'Gallery 2',
            'Gallery 3',
            'Gallery 4',
            'Designer',
            'Family Features',
        ];

        $skip_acf_labels = [
            'Family UID',
            'Sub Family UID',
            'Sub Sub Family UID',
            'Family Code',
            'Sub Family Code',
            'Sub Sub Family Code',
            'Sub Sub Family Index number',
            'Sub Family Description',
            'Designed By',
            'Sub Family Technical Drawing',
            'Feature Image',
            'Featured Subfamily',
            'Features hover image',
            'Featured Subfamily Hover',
            'Sub Family Product Image',
            'Gallery 1'
        ];

        foreach ( $this->acf_fields as $field ) {
            if ( ! in_array( $field['label'], $skip_acf_labels ) ) {
                $headers[] = $field['label'];
            }
        }

        return $headers;
    }

    /**
     * Get all terms flattened with parent chain info for batch export
     */
    private function get_all_terms_flattened() {
        $all_terms = [];

        $top_level_terms = get_terms([
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'parent'     => 0,
        ]);

        if ( ! is_wp_error( $top_level_terms ) ) {
            $this->flatten_terms_recursively( $top_level_terms, $all_terms, [] );
        }

        return $all_terms;
    }

    /**
     * Recursively flatten terms with parent chain
     */
    private function flatten_terms_recursively( $terms, &$all_terms, $parent_chain ) {
        foreach ( $terms as $term ) {
            if ( is_wp_error( $term ) ) continue;

            $current_chain = array_merge( $parent_chain, [ $term->name ] );

            $all_terms[] = [
                'term' => $term,
                'parent_chain' => $parent_chain
            ];

            $children = get_terms([
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'parent'     => $term->term_id,
            ]);

            if ( ! empty( $children ) && ! is_wp_error( $children ) ) {
                $this->flatten_terms_recursively( $children, $all_terms, $current_chain );
            }
        }
    }

    /**
     * Get a single term row for export
     */
    private function get_term_export_row( $term, $parent_chain ) {
        $current_chain = array_merge( $parent_chain, [ $term->name ] );
        $level = count( $current_chain ) - 1;

        // Build separate UID columns for Level 1, 2, 3
        $family_uid = '';      // Level 1 UID
        $sub_family_uid = '';  // Level 2 UID
        $sub_sub_family_uid = ''; // Level 3 UID

        // Get UIDs by traversing the hierarchy
        if ( $level >= 1 ) {
            // Build the ancestor chain to get UIDs at each level
            $ancestor_ids = [];
            $current_term_id = $term->term_id;

            // Collect all ancestor IDs including current term
            while ( $current_term_id ) {
                array_unshift( $ancestor_ids, $current_term_id );
                $parent_obj = get_term( $current_term_id, $this->taxonomy );
                if ( $parent_obj && $parent_obj->parent ) {
                    $current_term_id = $parent_obj->parent;
                } else {
                    break;
                }
            }

            // ancestor_ids[0] = Level 0 (Main Category - no UID needed)
            // ancestor_ids[1] = Level 1 (Family)
            // ancestor_ids[2] = Level 2 (Sub Family)
            // ancestor_ids[3] = Level 3 (Sub Sub Family)

            if ( isset( $ancestor_ids[1] ) ) {
                $family_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $ancestor_ids[1] );
            }
            if ( isset( $ancestor_ids[2] ) ) {
                $sub_family_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $ancestor_ids[2] );
            }
            if ( isset( $ancestor_ids[3] ) ) {
                $sub_sub_family_uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $ancestor_ids[3] );
            }
        }

        // Determine Main Category, Family, Sub Family, Sub Sub Family based on level
        $main_category = isset( $current_chain[0] ) ? $current_chain[0] : '';
        $family = isset( $current_chain[1] ) ? $current_chain[1] : '';
        $sub_family = isset( $current_chain[2] ) ? $current_chain[2] : '';
        $sub_sub_family = isset( $current_chain[3] ) ? $current_chain[3] : '';

        // Get Family Code
        $family_code = '';
        if ( $level >= 0 ) {
            $family_code = get_field( 'family_code', $this->taxonomy . '_' . $term->term_id );
        }

        // Get Level 1+ specific fields
        $featured_image = '';
        $technical_drawing = '';
        $gallery_1 = '';
        $gallery_2 = '';
        $gallery_3 = '';
        $gallery_4 = '';
        $designer = '';
        $family_features = '';

        if ( $level >= 1 ) {
            // Featured image
            $featured_img_data = get_field( 'pf_fet_img', $this->taxonomy . '_' . $term->term_id );
            if ( ! empty( $featured_img_data ) ) {
                if ( is_array( $featured_img_data ) && isset( $featured_img_data['url'] ) ) {
                    $featured_image = $featured_img_data['url'];
                } elseif ( is_numeric( $featured_img_data ) ) {
                    $url = wp_get_attachment_url( $featured_img_data );
                    if ( $url ) $featured_image = $url;
                } elseif ( is_string( $featured_img_data ) && filter_var( $featured_img_data, FILTER_VALIDATE_URL ) ) {
                    $featured_image = $featured_img_data;
                }
            }

            // Technical drawing
            $tech_drawing_data = get_field( 'pf_subfam_tech_drawing', $this->taxonomy . '_' . $term->term_id );
            if ( ! empty( $tech_drawing_data ) ) {
                if ( is_array( $tech_drawing_data ) && isset( $tech_drawing_data['url'] ) ) {
                    $technical_drawing = $tech_drawing_data['url'];
                } elseif ( is_numeric( $tech_drawing_data ) ) {
                    $url = wp_get_attachment_url( $tech_drawing_data );
                    if ( $url ) $technical_drawing = $url;
                } elseif ( is_string( $tech_drawing_data ) && filter_var( $tech_drawing_data, FILTER_VALIDATE_URL ) ) {
                    $technical_drawing = $tech_drawing_data;
                }
            }

            // Gallery images
            $gallery_fields = array(
                'prod_gallery_1' => 'gallery_1',
                'prod_gallery_2' => 'gallery_2',
                'prod_gallery_3' => 'gallery_3',
                'prod_gallery_4' => 'gallery_4',
            );
            foreach ( $gallery_fields as $acf_field => $var_name ) {
                $img = get_field( $acf_field, $this->taxonomy . '_' . $term->term_id );
                if ( ! empty( $img ) ) {
                    if ( is_array( $img ) && isset( $img['url'] ) ) {
                        $$var_name = $img['url'];
                    } elseif ( is_numeric( $img ) ) {
                        $url = wp_get_attachment_url( $img );
                        if ( $url ) $$var_name = $url;
                    } elseif ( is_string( $img ) && filter_var( $img, FILTER_VALIDATE_URL ) ) {
                        $$var_name = $img;
                    }
                }
            }

            $designer = get_field( 'pf_designed_by', $this->taxonomy . '_' . $term->term_id );

            // Features
            $features_data = get_field( 'tax_sub_family_features', $this->taxonomy . '_' . $term->term_id );
            if ( ! empty( $features_data ) && is_array( $features_data ) ) {
                $feature_slugs = [];
                foreach ( $features_data as $feature_term_id ) {
                    $feature_term = get_term( $feature_term_id, 'features' );
                    if ( $feature_term && ! is_wp_error( $feature_term ) ) {
                        $feature_slugs[] = $feature_term->slug;
                    }
                }
                $family_features = implode( ',', $feature_slugs );
            }
        }

        $row = [
            $family_uid ?: '', // Family UID (Level 1)
            $sub_family_uid ?: '', // Sub Family UID (Level 2)
            $sub_sub_family_uid ?: '', // Sub Sub Family UID (Level 3)
            $level >= 1 ? $family_code : '', // Family Code — applies to deepest level in row
            $main_category,
            $family,
            $sub_family,
            $sub_sub_family,
            $level >= 1 ? $term->description : '',
            $featured_image ?: '',
            $technical_drawing ?: '',
            $gallery_1 ?: '',
            $gallery_2 ?: '',
            $gallery_3 ?: '',
            $gallery_4 ?: '',
            $designer ?: '',
            $family_features ?: '',
        ];

        // Add ACF field values
        $skip_acf_labels = [
            'Family UID',
            'Sub Family UID',
            'Sub Sub Family UID',
            'Family Code',
            'Sub Family Code',
            'Sub Sub Family Code',
            'Sub Sub Family Index number',
            'Sub Family Description',
            'Designed By',
            'Sub Family Technical Drawing',
            'Feature Image',
            'Featured Subfamily',
            'Features hover image',
            'Featured Subfamily Hover',
            'Sub Family Product Image',
            'Gallery 1'
        ];

        foreach ( $this->acf_fields as $field ) {
            if ( in_array( $field['label'], $skip_acf_labels ) ) {
                continue;
            }
            $field_value = $this->get_taxonomy_acf_field_value( $term->term_id, $field );
            $row[] = $field_value;
        }

        return $row;
    }

    /**
     * Process a single row from the batch import
     */
    private function process_taxonomy_import_row( $item, $row_count, &$errors, &$imported_count, &$created_terms_cache ) {
        // Normalize keys to lowercase for consistent lookups (JS may send mixed case)
        $item = array_change_key_case( $item, CASE_LOWER );

        // Prepare Term Data from hierarchy columns
        $family_uid = isset( $item['family uid'] ) ? trim( $item['family uid'] ) : ''; // Level 1 UID
        $sub_family_uid = isset( $item['sub family uid'] ) ? trim( $item['sub family uid'] ) : ''; // Level 2 UID
        $sub_sub_family_uid = isset( $item['sub sub family uid'] ) ? trim( $item['sub sub family uid'] ) : ''; // Level 3 UID
        $family_code_val = isset( $item['family code'] ) ? trim( $item['family code'] ) : ''; // applies to deepest level in row
        $main_category = isset( $item['main category'] ) ? $this->capitalize_term_name( trim( $item['main category'] ) ) : '';
        $family = isset( $item['family'] ) ? $this->capitalize_term_name( trim( $item['family'] ) ) : '';
        $sub_family = isset( $item['sub family'] ) ? $this->capitalize_term_name( trim( $item['sub family'] ) ) : '';
        $sub_sub_family = isset( $item['sub sub family'] ) ? $this->capitalize_term_name( trim( $item['sub sub family'] ) ) : '';
        $term_description = isset( $item['description'] ) ? $item['description'] : '';

        // Level 1+ specific fields
        $featured_image = isset( $item['featured subfamily'] ) ? trim( $item['featured subfamily'] ) : '';
        $technical_drawing = isset( $item['technical drawing'] ) ? trim( $item['technical drawing'] ) : '';
        $gallery_1 = isset( $item['gallery 1'] ) ? trim( $item['gallery 1'] ) : '';
        $gallery_2 = isset( $item['gallery 2'] ) ? trim( $item['gallery 2'] ) : '';
        $gallery_3 = isset( $item['gallery 3'] ) ? trim( $item['gallery 3'] ) : '';
        $gallery_4 = isset( $item['gallery 4'] ) ? trim( $item['gallery 4'] ) : '';
        $designer = isset( $item['designer'] ) ? trim( $item['designer'] ) : '';
        $family_features = isset( $item['family features'] ) ? trim( $item['family features'] ) : '';

        // Determine which term to create/update based on hierarchy
        $term_name = '';
        $parent_term_id = 0;
        $level = -1;
        $new_term_id = 0;

        // 1. Resolve Level 0 (Main Category) - Always by Name
        if ( ! empty( $main_category ) ) {
            $main_cat_id = $this->find_or_create_term_by_name( $main_category, 0, $created_terms_cache );
            if ( ! $main_cat_id ) {
                $msg = "Row $row_count: Failed to find/create Main Category '{$main_category}'";
                error_log( $msg );
                $errors[] = $msg;
                return false;
            }

            $parent_term_id = $main_cat_id;
            $term_name = $main_category;
            $level = 0;
            $new_term_id = $main_cat_id;
        } else {
            $msg = "Row $row_count: Main Category is required.";
            error_log( $msg );
            $errors[] = $msg;
            return false;
        }

        // 2. Resolve Level 1+ (Family, Sub Family, Sub Sub Family) using separate UID columns
        if ( ! empty( $family ) ) {
            // Level 1: Family - use family_uid if provided
            $family_id = $this->find_or_create_term_by_hybrid( $family, $main_cat_id, $family_uid, $created_terms_cache, 1 );
            $new_term_id = $family_id;
            $term_name = $family;
            $level = 1;

            if ( $family_id && ! empty( $sub_family ) ) {
                // Level 2: Sub Family - use sub_family_uid if provided
                $sub_family_id_result = $this->find_or_create_term_by_hybrid( $sub_family, $family_id, $sub_family_uid, $created_terms_cache, 2 );
                $new_term_id = $sub_family_id_result;
                $term_name = $sub_family;
                $level = 2;

                if ( $sub_family_id_result && ! empty( $sub_sub_family ) ) {
                    // Level 3: Sub Sub Family - use sub_sub_family_uid if provided
                    $sub_sub_family_id_result = $this->find_or_create_term_by_hybrid( $sub_sub_family, $sub_family_id_result, $sub_sub_family_uid, $created_terms_cache, 3 );
                    $new_term_id = $sub_sub_family_id_result;
                    $term_name = $sub_sub_family;
                    $level = 3;
                }
            }
        }

        if ( ! $new_term_id ) {
            $msg = "Row $row_count: Failed to create/update term '{$term_name}'";
            error_log( $msg );
            $errors[] = $msg;
            return false;
        }

        // Handle ACF fields for Level 1+ terms
        if ( $level >= 1 ) {
            // Update term description
            if ( ! empty( $term_description ) ) {
                wp_update_term( $new_term_id, $this->taxonomy, [
                    'description' => $term_description
                ]);
            }

            // Handle Featured Subfamily image
            if ( ! empty( $featured_image ) ) {
                if ( filter_var( $featured_image, FILTER_VALIDATE_URL ) ) {
                    $attachment_id = $this->insert_attachment_from_url( $featured_image );
                    if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                        update_field( 'pf_fet_img', $attachment_id, $this->taxonomy . '_' . $new_term_id );
                    }
                } elseif ( is_numeric( $featured_image ) ) {
                    update_field( 'pf_fet_img', intval( $featured_image ), $this->taxonomy . '_' . $new_term_id );
                }
            }

            // Handle Technical Drawing image
            if ( ! empty( $technical_drawing ) ) {
                if ( filter_var( $technical_drawing, FILTER_VALIDATE_URL ) ) {
                    $attachment_id = $this->insert_attachment_from_url( $technical_drawing );
                    if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                        update_field( 'pf_subfam_tech_drawing', $attachment_id, $this->taxonomy . '_' . $new_term_id );
                    }
                } elseif ( is_numeric( $technical_drawing ) ) {
                    update_field( 'pf_subfam_tech_drawing', intval( $technical_drawing ), $this->taxonomy . '_' . $new_term_id );
                }
            }

            // Handle Gallery images
            $gallery_data = array(
                'prod_gallery_1' => $gallery_1,
                'prod_gallery_2' => $gallery_2,
                'prod_gallery_3' => $gallery_3,
                'prod_gallery_4' => $gallery_4,
            );

            foreach ( $gallery_data as $acf_field => $gallery_value ) {
                if ( empty( $gallery_value ) ) continue;

                $attachment_id = null;
                if ( filter_var( $gallery_value, FILTER_VALIDATE_URL ) ) {
                    $attachment_id = $this->insert_attachment_from_url( $gallery_value );
                } elseif ( is_numeric( $gallery_value ) ) {
                    $attachment_id = intval( $gallery_value );
                }

                if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                    update_field( $acf_field, $attachment_id, $this->taxonomy . '_' . $new_term_id );
                }
            }

            // Save family_code — applies to whatever the deepest level in this row is
            if ( ! empty( $family_code_val ) ) {
                update_field( 'family_code', $family_code_val, $this->taxonomy . '_' . $new_term_id );
            }

            if ( ! empty( $designer ) ) {
                update_field( 'pf_designed_by', $designer, $this->taxonomy . '_' . $new_term_id );
            }

            // Handle Family Features
            if ( ! empty( $family_features ) ) {
                $feature_slugs = array_map( 'trim', explode( ',', $family_features ) );
                $feature_term_ids = [];
                foreach ( $feature_slugs as $slug ) {
                    if ( empty( $slug ) ) continue;
                    $feature_term = get_term_by( 'slug', $slug, 'features' );
                    if ( $feature_term && ! is_wp_error( $feature_term ) ) {
                        $feature_term_ids[] = $feature_term->term_id;
                    }
                }
                if ( ! empty( $feature_term_ids ) ) {
                    update_field( 'tax_sub_family_features', $feature_term_ids, $this->taxonomy . '_' . $new_term_id );
                }
            }
        }

        // Handle ACF Fields
        $skip_acf_labels = [
            'Family UID', // Skip - handled separately via UID columns
            'Sub Family UID',
            'Sub Sub Family UID',
            'Family Code',
            'Sub Family Code',
            'Sub Sub Family Code',
            'Sub Sub Family Index number',
            'Sub Family Description',
            'Designed By',
            'Sub Family Technical Drawing',
            'Feature Image',
            'Featured Subfamily',
            'Features hover image',
            'Featured Subfamily Hover',
            'Sub Family Product Image',
            'Gallery 1'
        ];

        foreach ( $this->acf_fields as $field ) {
            if ( in_array( $field['name'], ['family_code', 'pf_fet_img', 'pf_subfam_tech_drawing', 'pf_designed_by', 'tax_family__uid'] ) ) {
                continue;
            }

            if ( in_array( $field['label'], $skip_acf_labels ) ) {
                continue;
            }

            $column_key = strtolower( $field['label'] );

            if ( isset( $item[ $column_key ] ) && $item[ $column_key ] !== '' ) {
                try {
                    $this->set_taxonomy_acf_field_value( $new_term_id, $field, $item[ $column_key ] );
                } catch ( Exception $e ) {
                    error_log( 'ACF Taxonomy Import Error for field ' . $field['name'] . ': ' . $e->getMessage() );
                }
            }
        }

        $imported_count++;
        return true;
    }

    /**
     * AJAX: Apply term order from CSV row sequence.
     * Reads 'Main Category', 'Family', 'Sub Family', 'Sub Sub Family' columns.
     * First appearance of a term within its parent group = position 1, 2, 3...
     * Only writes term meta 'order' — no term data is modified.
     */
    public function ajax_apply_taxonomy_order_from_csv() {
        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce'] : '';
        if ( ! wp_verify_nonce( $nonce, 'puk_taxonomy_order_from_csv_nonce' ) ) {
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
            wp_send_json_error( 'No valid data provided' );
        }

        // Pre-load all terms into a lookup: lowercase(name)_parentid => term_id
        $all_terms = get_terms( [
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'number'     => 0,
            'orderby'    => 'none',
        ] );

        $term_lookup = [];
        foreach ( $all_terms as $t ) {
            $key = mb_strtolower( trim( $t->name ), 'UTF-8' ) . '_' . $t->parent;
            $term_lookup[ $key ] = $t->term_id;
        }

        $seen             = [];
        $counters         = [];
        $order_assignments = [];

        foreach ( $batch_data as $row ) {
            $main_cat   = $this->capitalize_term_name( trim( $row['main category'] ?? '' ) );
            $family     = $this->capitalize_term_name( trim( $row['family'] ?? '' ) );
            $sub_family = $this->capitalize_term_name( trim( $row['sub family'] ?? '' ) );
            $sub_sub    = $this->capitalize_term_name( trim( $row['sub sub family'] ?? '' ) );

            // Level 0: Main Category (parent = 0)
            $main_id = null;
            if ( ! empty( $main_cat ) ) {
                $lk = mb_strtolower( $main_cat, 'UTF-8' ) . '_0';
                if ( ! isset( $seen[ 'l0_' . $lk ] ) ) {
                    $seen[ 'l0_' . $lk ] = true;
                    $counters['p0'] = ( $counters['p0'] ?? 0 ) + 1;
                    if ( isset( $term_lookup[ $lk ] ) ) {
                        $order_assignments[ $term_lookup[ $lk ] ] = $counters['p0'];
                    }
                }
                $main_id = $term_lookup[ $lk ] ?? null;
            }

            // Level 1: Family
            $fam_id = null;
            if ( $main_id && ! empty( $family ) ) {
                $lk = mb_strtolower( $family, 'UTF-8' ) . '_' . $main_id;
                if ( ! isset( $seen[ 'l1_' . $lk ] ) ) {
                    $seen[ 'l1_' . $lk ] = true;
                    $pk = 'p' . $main_id;
                    $counters[ $pk ] = ( $counters[ $pk ] ?? 0 ) + 1;
                    if ( isset( $term_lookup[ $lk ] ) ) {
                        $order_assignments[ $term_lookup[ $lk ] ] = $counters[ $pk ];
                    }
                }
                $fam_id = $term_lookup[ $lk ] ?? null;
            }

            // Level 2: Sub Family
            $sub_id = null;
            if ( $fam_id && ! empty( $sub_family ) ) {
                $lk = mb_strtolower( $sub_family, 'UTF-8' ) . '_' . $fam_id;
                if ( ! isset( $seen[ 'l2_' . $lk ] ) ) {
                    $seen[ 'l2_' . $lk ] = true;
                    $pk = 'p' . $fam_id;
                    $counters[ $pk ] = ( $counters[ $pk ] ?? 0 ) + 1;
                    if ( isset( $term_lookup[ $lk ] ) ) {
                        $order_assignments[ $term_lookup[ $lk ] ] = $counters[ $pk ];
                    }
                }
                $sub_id = $term_lookup[ $lk ] ?? null;
            }

            // Level 3: Sub Sub Family
            if ( $sub_id && ! empty( $sub_sub ) ) {
                $lk = mb_strtolower( $sub_sub, 'UTF-8' ) . '_' . $sub_id;
                if ( ! isset( $seen[ 'l3_' . $lk ] ) ) {
                    $seen[ 'l3_' . $lk ] = true;
                    $pk = 'p' . $sub_id;
                    $counters[ $pk ] = ( $counters[ $pk ] ?? 0 ) + 1;
                    if ( isset( $term_lookup[ $lk ] ) ) {
                        $order_assignments[ $term_lookup[ $lk ] ] = $counters[ $pk ];
                    }
                }
            }
        }

        foreach ( $order_assignments as $term_id => $position ) {
            update_term_meta( $term_id, 'order', $position );
        }

        wp_send_json_success( [
            'applied'    => count( $order_assignments ),
            'total_rows' => count( $batch_data ),
        ] );
    }
}

// Initialize the class
new Puk_Taxonomy_Importer_Exporter();