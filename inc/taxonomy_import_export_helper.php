<?php
/**
 * Taxonomy Import/Export Helper
 * 
 * Handles CSV export and import for 'products-family' taxonomy with ACF fields.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Puk_Taxonomy_Importer_Exporter {

    private $taxonomy = 'products-family';
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
        $filename = 'products-family-taxonomy-export-' . date( 'Y-m-d' ) . '.csv';
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );

        // Open output stream
        $output = fopen( 'php://output', 'w' );
        
        // Add BOM for Excel compatibility
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        // Define CSV Headers
        $headers = [
            'Family Code', // First column - unique identifier for Family (Level 1)
            'Sub Family Code', // Unique identifier for Sub Family (Level 2)
            'Main Category', // Level 0
            'Family', // Level 1
            'Sub Family', // Level 2
            'Description',
            'Featured Subfamily', // Sub Family only (Level 2) - Image
            'Technical Drawing', // Sub Family only (Level 2)
            'Designer', // Sub Family only (Level 2)
        ];
        
        // Add ACF field headers (skip specific fields that are already handled or not needed)
        $skip_acf_labels = [
            'Sub Sub Family Index number',
            'Sub Family Description',
            'Designed By',
            'Sub Family Technical Drawing',
            'Feature Image',
            'Featured Subfamily',
            'Features hover image',
            'Featured Subfamily Hover',
            'Sub Family Product Image'
        ];
        
        // Column name mapping for renaming ACF fields in CSV
        $column_name_mapping = [
            'Gallery 1' => 'Subfamily Gallery'
        ];
        
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
            
            // Determine Main Category, Family, Sub Family based on level
            $main_category = isset( $current_chain[0] ) ? $current_chain[0] : '';
            $family = isset( $current_chain[1] ) ? $current_chain[1] : '';
            $sub_family = isset( $current_chain[2] ) ? $current_chain[2] : '';
            
            // Get family_code ACF field value - for Level 1 (Family) and Level 2 (Sub Family)
            $family_code = '';
            $subfamily_code = '';
            
            if ( $level === 1 ) {
                // For Family (Level 1), family_code goes in Family Code column
                $family_code = get_field( 'family_code', $this->taxonomy . '_' . $term->term_id );
            } elseif ( $level === 2 ) {
                // For Sub Family (Level 2), family_code goes in Sub Family Code column
                $subfamily_code = get_field( 'family_code', $this->taxonomy . '_' . $term->term_id );
            }
            
            // Get Sub Family specific fields - ONLY for Level 2
            $featured_image = '';
            $technical_drawing = '';
            $designer = '';
            if ( $level === 2 ) {
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
                
                $designer = get_field( 'pf_designed_by', $this->taxonomy . '_' . $term->term_id );
            }
            
            // Basic Term Data
            $row = [
                $family_code ?: '', // Family Code - only populated for Level 1 (Family) from family_code field
                $subfamily_code ?: '', // Sub Family Code - only populated for Level 2 (Sub Family) from family_code field
                $main_category,
                $family,
                $sub_family,
                $term->description,
                $featured_image ?: '', // Featured Subfamily - only for Level 2 (Sub Family)
                $technical_drawing ?: '', // Technical Drawing - only for Level 2 (Sub Family)
                $designer ?: '', // Designer - only for Level 2 (Sub Family)
            ];
            
            // Add ACF field values (skip specific fields)
            $skip_acf_labels = [
                'Sub Sub Family Index number',
                'Sub Family Description',
                'Designed By',
                'Sub Family Technical Drawing',
                'Feature Image',
                'Featured Subfamily',
                'Features hover image',
                'Featured Subfamily Hover',
                'Sub Family Product Image'
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

        // Read Headers
        $headers = fgetcsv( $handle, 0, $delimiter );
        
        // Remove BOM from first header if present
        if ( isset( $headers[0] ) ) {
            $headers[0] = preg_replace( '/^\xEF\xBB\xBF/', '', $headers[0] );
        }

        $headers = array_map( 'trim', $headers );
        $headers = array_map( 'strtolower', $headers );
        
        // Verify required columns exist
        $required_columns = ['main category', 'family', 'sub family'];
        $has_hierarchy = false;
        foreach ( $required_columns as $col ) {
            if ( in_array( $col, $headers ) ) {
                $has_hierarchy = true;
                break;
            }
        }
        
        if ( ! $has_hierarchy ) {
            $msg = "CRITICAL ERROR: At least one hierarchy column (Main Category, Family, or Sub Family) must be present in CSV. Found headers: " . implode( ', ', $headers );
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
            $family_code = isset( $item['family code'] ) ? trim( $item['family code'] ) : '';
            $subfamily_code = isset( $item['sub family code'] ) ? trim( $item['sub family code'] ) : '';
            $main_category = isset( $item['main category'] ) ? trim( $item['main category'] ) : '';
            $family = isset( $item['family'] ) ? trim( $item['family'] ) : '';
            $sub_family = isset( $item['sub family'] ) ? trim( $item['sub family'] ) : '';
            $term_description = isset( $item['description'] ) ? $item['description'] : '';
            
            // Sub Family specific fields
            $featured_image = isset( $item['featured subfamily'] ) ? trim( $item['featured subfamily'] ) : '';
            $technical_drawing = isset( $item['technical drawing'] ) ? trim( $item['technical drawing'] ) : '';
            $designer = isset( $item['designer'] ) ? trim( $item['designer'] ) : '';
            
            // Determine which term to create/update based on hierarchy
            $term_name = '';
            $parent_name = '';
            $level = -1;
            
            if ( ! empty( $sub_family ) ) {
                // Level 2: Sub Family is the term, Family is the parent
                $term_name = $sub_family;
                $parent_name = $family;
                $level = 2;
            } elseif ( ! empty( $family ) ) {
                // Level 1: Family is the term, Main Category is the parent
                $term_name = $family;
                $parent_name = $main_category;
                $level = 1;
            } elseif ( ! empty( $main_category ) ) {
                // Level 0: Main Category is the term, no parent
                $term_name = $main_category;
                $parent_name = '';
                $level = 0;
            }
            
            if ( empty( $term_name ) ) {
                $msg = "Row $row_count: All hierarchy columns are empty.";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }
            
            // First, ensure parent exists if needed
            $parent_term_id = 0;
            if ( ! empty( $parent_name ) ) {
                // For level 2, we need to ensure both Main Category and Family exist
                if ( $level === 2 && ! empty( $main_category ) ) {
                    // Ensure Main Category exists (level 0)
                    $main_cat_id = $this->find_or_create_term( $main_category, 0, '', $created_terms_cache );
                    if ( ! $main_cat_id ) {
                        $msg = "Row $row_count: Failed to find/create Main Category '{$main_category}'";
                        error_log( $msg );
                        $errors[] = $msg;
                        continue;
                    }
                    
                    // Ensure Family exists (level 1, child of Main Category)
                    // For Level 2 terms, pass the family_code to the Family (parent) term
                    $parent_term_id = $this->find_or_create_term( $parent_name, $main_cat_id, '', $created_terms_cache, $family_code, 1 );
                    
                    // Save family_code to the Family term if provided
                    if ( ! empty( $family_code ) && $parent_term_id ) {
                        update_field( 'family_code', $family_code, $this->taxonomy . '_' . $parent_term_id );
                    }
                } else {
                    // For level 1, just need to ensure Main Category exists (Level 0)
                    $parent_term_id = $this->find_or_create_term( $parent_name, 0, '', $created_terms_cache, '', 0 );
                }
                
                if ( ! $parent_term_id ) {
                    $msg = "Row $row_count: Failed to find/create parent term '{$parent_name}'";
                    error_log( $msg );
                    $errors[] = $msg;
                    continue;
                }
            }
            
            // Now create or update the actual term
            // Pass the appropriate unique code based on level
            $unique_code = '';
            if ( $level === 1 ) {
                $unique_code = $family_code; // For Family terms, use family_code
            } elseif ( $level === 2 ) {
                $unique_code = $subfamily_code; // For Sub Family terms, use subfamily_code
            }
            $new_term_id = $this->find_or_create_term( $term_name, $parent_term_id, $term_description, $created_terms_cache, $unique_code, $level );
            
            if ( ! $new_term_id ) {
                $msg = "Row $row_count: Failed to create/update term '{$term_name}'";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }
            
            // Handle family_code field - for Level 1 (Family) and Level 2 (Sub Family) terms
            if ( $level === 1 && ! empty( $family_code ) ) {
                // For Family (Level 1), save from Family Code column
                update_field( 'family_code', $family_code, $this->taxonomy . '_' . $new_term_id );
            } elseif ( $level === 2 && ! empty( $subfamily_code ) ) {
                // For Sub Family (Level 2), save from Sub Family Code column
                update_field( 'family_code', $subfamily_code, $this->taxonomy . '_' . $new_term_id );
            }
            
            // Handle Sub Family specific fields - ONLY for Level 2 (Sub Family) terms
            if ( $level === 2 ) {
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
                if ( ! empty( $designer ) ) {
                    update_field( 'pf_designed_by', $designer, $this->taxonomy . '_' . $new_term_id );
                }
            }

            // Handle ACF Fields
            $skip_acf_labels = [
                'Sub Sub Family Index number',
                'Sub Family Description',
                'Designed By',
                'Sub Family Technical Drawing',
                'Feature Image',
                'Featured Subfamily',
                'Features hover image',
                'Featured Subfamily Hover',
                'Sub Family Product Image'
            ];
            
            // Column name mapping for renaming ACF fields in CSV
            $column_name_mapping = [
                'Gallery 1' => 'Subfamily Gallery 1'
            ];
            
            // Create reverse mapping for import
            $reverse_mapping = array_flip( $column_name_mapping );
            
            foreach ( $this->acf_fields as $field ) {
                // Skip fields that are already handled above or in the skip list
                if ( in_array( $field['name'], ['family_code', 'pf_fet_img', 'pf_subfam_tech_drawing', 'pf_designed_by'] ) ) {
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
     * Find or create a term with caching
     *
     * @param string $term_name Term name
     * @param int $parent_id Parent term ID (0 for top level)
     * @param string $description Term description
     * @param array &$cache Reference to cache array
     * @param string $unique_code Optional unique code (uses family_code ACF field for both Level 1 and Level 2)
     * @param int $level Term level (0=Main Category, 1=Family, 2=Sub Family)
     * @return int|false Term ID on success, false on failure
     */
    private function find_or_create_term( $term_name, $parent_id, $description = '', &$cache, $unique_code = '', $level = -1 ) {
        // Create cache key based on name and parent
        $cache_key = $term_name . '_' . $parent_id;
        
        // Check cache first
        if ( isset( $cache[ $cache_key ] ) ) {
            return $cache[ $cache_key ];
        }
        
        $existing_term = null;
        
        // Try to find by unique code first if provided
        // For both Level 1 (Family) and Level 2 (Sub Family), use family_code field
        if ( ! empty( $unique_code ) && ( $level === 1 || $level === 2 ) ) {
            $terms_with_code = get_terms([
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'     => 'family_code',
                        'value'   => $unique_code,
                        'compare' => '='
                    ]
                ]
            ]);
            
            if ( ! empty( $terms_with_code ) && ! is_wp_error( $terms_with_code ) ) {
                $existing_term = $terms_with_code[0];
            }
        }
        
        // If not found by family_code, try to find by name and parent
        if ( ! $existing_term ) {
            $terms = get_terms([
                'taxonomy'   => $this->taxonomy,
                'name'       => $term_name,
                'parent'     => $parent_id,
                'hide_empty' => false,
            ]);
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $existing_term = $terms[0];
            }
        }
        
        // Update or create term
        if ( $existing_term && ! is_wp_error( $existing_term ) ) {
            // Update existing term
            $term_args = [
                'name'        => $term_name,
                'parent'      => $parent_id,
            ];
            
            if ( ! empty( $description ) ) {
                $term_args['description'] = $description;
            }
            
            $result = wp_update_term( $existing_term->term_id, $this->taxonomy, $term_args );
            
            if ( is_wp_error( $result ) ) {
                error_log( 'Term update failed for ' . $term_name . ': ' . $result->get_error_message() );
                return false;
            }
            
            $term_id = $result['term_id'];
        } else {
            // Create new term
            $term_args = [
                'parent' => $parent_id,
            ];
            
            if ( ! empty( $description ) ) {
                $term_args['description'] = $description;
            }
            
            $result = wp_insert_term( $term_name, $this->taxonomy, $term_args );
            
            if ( is_wp_error( $result ) ) {
                error_log( 'Term creation failed for ' . $term_name . ': ' . $result->get_error_message() );
                return false;
            }
            
            $term_id = $result['term_id'];
        }
        
        // Cache the result
        $cache[ $cache_key ] = $term_id;
        
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
                
            case 'gallery':
                // Handle comma-separated image IDs
                $items = array_map( 'trim', explode( ',', $value ) );
                $image_ids = [];
                
                foreach ( $items as $item ) {
                    if ( empty( $item ) ) continue;
                    
                    if ( is_numeric( $item ) ) {
                        $image_ids[] = intval( $item );
                    }
                }
                
                if ( ! empty( $image_ids ) ) {
                    update_field( $field['name'], $image_ids, $this->taxonomy . '_' . $term_id );
                }
                break;
                
            case 'image':
                // Expecting image ID
                if ( is_numeric( $value ) ) {
                    update_field( $field['name'], intval( $value ), $this->taxonomy . '_' . $term_id );
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
}

// Initialize the class
new Puk_Taxonomy_Importer_Exporter();