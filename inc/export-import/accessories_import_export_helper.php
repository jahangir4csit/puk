<?php
/**
 * Accessories Taxonomy Import/Export Helper
 *
 * Handles CSV export and import for 'accessories' taxonomy with ACF fields.
 *
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Puk_Accessories_Importer_Exporter {

    private $taxonomy = 'accessories';
    private $acf_fields = [];

    public function __construct() {
        // Load ACF fields configuration for taxonomy
        $this->acf_fields = $this->get_taxonomy_acf_fields_config();

        // Handle Actions
        add_action( 'admin_init', [ $this, 'handle_accessories_export_request' ] );
        add_action( 'admin_init', [ $this, 'handle_accessories_import_request' ] );
    }

    /**
     * Load ACF fields configuration for accessories taxonomy
     */
    private function get_taxonomy_acf_fields_config() {
        // Check if there's a specific ACF config file for accessories taxonomy
        $config_file = get_template_directory() . '/acf_accessories_taxonomy.php';

        if ( ! file_exists( $config_file ) ) {
            // No specific config, return empty array
            return [];
        }

        // Get the file content
        $content = file_get_contents( $config_file );

        // Parse JSON
        $json_data = json_decode( $content, true );

        if ( json_last_error() !== JSON_ERROR_NONE || ! isset( $json_data[0]['fields'] ) ) {
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
     * Handles the accessories taxonomy CSV export generation.
     */
    public function handle_accessories_export_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'export_accessories' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_export_accessories'] ) || ! wp_verify_nonce( $_POST['_wpnonce_export_accessories'], 'puk_export_accessories_nonce' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Set headers for CSV download
        $filename = 'accessories-taxonomy-export-' . date( 'Y-m-d' ) . '.csv';
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );

        // Open output stream
        $output = fopen( 'php://output', 'w' );

        // Add BOM for Excel compatibility
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        // Define CSV Headers
        $headers = [
            'Code',
            'Name',
            'Included',
            'Integrated label',
            'Description',
            'Image',
        ];

        fputcsv( $output, $headers );

        // Get all taxonomy terms
        $args = [
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ];
        $terms = get_terms( $args );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                // Get Code (tax_acc__code ACF field)
                $code = get_field( 'tax_acc__code', $this->taxonomy . '_' . $term->term_id );

                // Get Included (tax_acc_ft__type ACF field)
                $included = get_field( 'tax_acc_ft__type', $this->taxonomy . '_' . $term->term_id );

                // Get Integrated label (tax_acc_integ__label ACF field)
                $integrated_label = get_field( 'tax_acc_integ__label', $this->taxonomy . '_' . $term->term_id );

                // Get Image (tax_acc_ft__img ACF field) - export as URL
                $image = '';
                $image_data = get_field( 'tax_acc_ft__img', $this->taxonomy . '_' . $term->term_id );
                if ( ! empty( $image_data ) ) {
                    if ( is_array( $image_data ) && isset( $image_data['url'] ) ) {
                        $image = $image_data['url'];
                    } elseif ( is_numeric( $image_data ) ) {
                        $url = wp_get_attachment_url( $image_data );
                        if ( $url ) {
                            $image = $url;
                        }
                    } elseif ( is_string( $image_data ) && filter_var( $image_data, FILTER_VALIDATE_URL ) ) {
                        $image = $image_data;
                    }
                }

                // Build row data
                $row = [
                    $code ?: '',
                    $term->name,
                    $included ?: '',
                    $integrated_label ?: '',
                    $term->description,
                    $image,
                ];

                fputcsv( $output, $row );
            }
        }

        fclose( $output );
        exit();
    }

    /**
     * Handles the accessories taxonomy import logic.
     */
    public function handle_accessories_import_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'import_accessories' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_import_accessories'] ) || ! wp_verify_nonce( $_POST['_wpnonce_import_accessories'], 'puk_import_accessories_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'puk' ) );
        }

        if ( empty( $_FILES['import_accessories_file']['tmp_name'] ) ) {
            wp_die( __( 'No file uploaded.', 'puk' ) );
        }

        $file_path = $_FILES['import_accessories_file']['tmp_name'];
        $handle = fopen( $file_path, 'r' );

        if ( $handle === false ) {
            wp_die( __( 'Could not open file.', 'puk' ) );
        }

        // Detect delimiter
        $delimiter = ',';
        $preview = fgets( $handle );
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
        // Normalize whitespace in headers (replace multiple spaces with single space)
        $headers = array_map( function( $h ) {
            return preg_replace( '/\s+/', ' ', $h );
        }, $headers );

        // Debug: Log the headers found
        error_log( 'Accessories Import - CSV Headers found: ' . implode( ', ', $headers ) );
        error_log( 'Accessories Import - Header count: ' . count( $headers ) );

        // Expected headers from export
        $expected_headers = [ 'code', 'name', 'included', 'integrated label', 'description', 'image' ];

        // Check if first row looks like data instead of headers (no 'name' header found)
        // This handles CSVs without header rows
        if ( ! in_array( 'name', $headers ) ) {
            // Check if it might be using the expected column order without headers
            // If the first "header" looks like actual data, use default headers
            $first_value = $headers[0] ?? '';

            // If first value is numeric or looks like a code, assume no header row
            if ( preg_match( '/^[A-Z0-9\-_]+$/i', $first_value ) && count( $headers ) === count( $expected_headers ) ) {
                error_log( 'Accessories Import - No header row detected, using expected column order' );
                // Rewind and treat first row as data
                rewind( $handle );
                // Skip BOM if present
                $bom = fread( $handle, 3 );
                if ( $bom !== "\xEF\xBB\xBF" ) {
                    rewind( $handle );
                }
                $headers = $expected_headers;
            }
        }

        // Verify required column exists
        if ( ! in_array( 'name', $headers ) ) {
            $msg = "CRITICAL ERROR: 'Name' column missing in CSV. Found headers: " . implode( ', ', $headers );
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
        $created_terms_cache = [];

        while ( ( $row = fgetcsv( $handle, 0, $delimiter ) ) !== false ) {
            $row_count++;
            $row = array_map( 'trim', $row );
            
            // Skip empty rows
            if ( empty( $row ) || ( count( $row ) === 1 && $row[0] === '' ) ) {
                continue;
            }

            // Combine headers with row data - be more flexible with column counts
            $row_count_actual = count( $row );
            $header_count = count( $headers );

            if ( $row_count_actual < $header_count ) {
                // Pad with empty values if row is too short
                $row = array_pad( $row, $header_count, '' );
            } elseif ( $row_count_actual > $header_count ) {
                // Slice if row is too long
                $row = array_slice( $row, 0, $header_count );
            }
            
            $item = array_combine( $headers, $row );

            // Debug: Log first row data mapping
            if ( $row_count === 1 ) {
                error_log( 'Accessories Import - First row data: ' . print_r( $item, true ) );
            }

            // Prepare Term Data - handle various possible header names
            $code = $this->get_csv_value( $item, [ 'code', 'tax_acc__code', 'accessory_code', 'acc_code' ] );
            $name = $this->get_csv_value( $item, [ 'name', 'title', 'accessory_name', 'term_name' ] );
            $included = $this->get_csv_value( $item, [ 'included', 'type', 'tax_acc_ft__type', 'accessory_type' ] );
            $integrated_label = $this->get_csv_value( $item, [ 'integrated label', 'integrated_label', 'integ_label', 'tax_acc_integ__label' ] );
            $description = $this->get_csv_value( $item, [ 'description', 'desc' ] );
            $image = $this->get_csv_value( $item, [ 'image', 'img', 'tax_acc_ft__img', 'image_url' ] );

            // Debug: Log what we're getting for all fields
            error_log( "Accessories Import - Row $row_count: code='$code', name='$name', included='$included', integrated_label='$integrated_label'" );

            // Validate: Code is required as unique identifier
            // Note: Use strlen check instead of empty() because '0' is a valid code
            if ( $code === '' || $code === null ) {
                $msg = "Row $row_count: Code is empty. Code is required as unique identifier.";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            // For new terms, name is required. For existing terms (found by code), we can keep existing name
            if ( empty( $name ) ) {
                // Try to find existing term by code to get its name
                $existing_by_code = get_terms([
                    'taxonomy'   => $this->taxonomy,
                    'hide_empty' => false,
                    'meta_query' => [
                        [
                            'key'     => 'tax_acc__code',
                            'value'   => $code,
                            'compare' => '='
                        ]
                    ]
                ]);

                if ( ! empty( $existing_by_code ) && ! is_wp_error( $existing_by_code ) ) {
                    // Use existing term's name
                    $name = $existing_by_code[0]->name;
                    error_log( "Accessories Import - Row $row_count: Name empty, using existing name '$name' for code '$code'" );
                } else {
                    $msg = "Row $row_count: Name is empty and no existing term found for code '$code'. Skipping row.";
                    error_log( "Accessories Import - CRITICAL ERROR: " . $msg );
                    $errors[] = $msg;
                    continue;
                }
            }

            // Sanity check: warn if name looks like a code (e.g., alphanumeric pattern)
            if ( preg_match( '/^[A-Z]{2,4}[\-_]?\d+$/i', $name ) ) {
                error_log( "Accessories Import - Warning Row $row_count: Name '$name' looks like a code pattern. Please verify CSV column mapping." );
            }

            // Create or update the term using code as unique identifier if provided
            $new_term_id = $this->find_or_create_term_by_code( $name, $description, $created_terms_cache, $code );

            if ( ! $new_term_id ) {
                $msg = "Row $row_count: Failed to create/update term '{$name}'";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            // Handle Code (tax_acc__code ACF field) - always update since code is required
            // Note: '0' is a valid code value
            update_field( 'tax_acc__code', $code, $this->taxonomy . '_' . $new_term_id );
            if ( $row_count <= 5 ) {
                error_log( "Accessories Import - Row $row_count: Updated code='$code' for term_id=$new_term_id" );
            }

            // Handle Included (tax_acc_ft__type ACF field)
            // Note: '0' and '1' are valid values, use strict comparison
            if ( $included !== '' && $included !== null ) {
                update_field( 'tax_acc_ft__type', $included, $this->taxonomy . '_' . $new_term_id );
                if ( $row_count <= 5 ) {
                    error_log( "Accessories Import - Row $row_count: Updated included='$included' for term_id=$new_term_id" );
                }
            }

            // Handle Integrated label (tax_acc_integ__label ACF field)
            // Note: Values like '.HC' starting with dot are valid
            if ( $integrated_label !== '' && $integrated_label !== null ) {
                update_field( 'tax_acc_integ__label', $integrated_label, $this->taxonomy . '_' . $new_term_id );
                if ( $row_count <= 5 ) {
                    error_log( "Accessories Import - Row $row_count: Updated integrated_label='$integrated_label' for term_id=$new_term_id" );
                }
            }

            // Handle Image (tax_acc_ft__img ACF field)
            if ( ! empty( $image ) ) {
                if ( filter_var( $image, FILTER_VALIDATE_URL ) ) {
                    // It's a URL, download and create attachment
                    $attachment_id = $this->insert_attachment_from_url( $image );
                    if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                        update_field( 'tax_acc_ft__img', $attachment_id, $this->taxonomy . '_' . $new_term_id );
                    }
                } elseif ( is_numeric( $image ) ) {
                    // It's already an attachment ID
                    update_field( 'tax_acc_ft__img', intval( $image ), $this->taxonomy . '_' . $new_term_id );
                }
            }

            $imported_count++;
            error_log( "Accessories Import - Row $row_count: SUCCESS - term_id=$new_term_id for code='$code'" );
        }

        fclose( $handle );

        error_log( "Accessories Taxonomy Import completed: $imported_count terms imported out of $row_count rows processed" );

        // Store results for admin notice
        $skipped_count = $row_count - $imported_count;

        add_action( 'admin_notices', function() use ( $imported_count, $row_count, $skipped_count, $errors ) {
            $class = $imported_count > 0 ? 'notice-success' : 'notice-warning';
            echo '<div class="notice ' . $class . ' is-dismissible">';
            echo '<p>' . sprintf( __( '%d accessories terms imported successfully out of %d rows.', 'puk' ), $imported_count, $row_count ) . '</p>';
            if ( $skipped_count > 0 ) {
                echo '<p><strong>Skipped: ' . $skipped_count . ' rows</strong></p>';
            }
            echo '</div>';

            if ( ! empty( $errors ) ) {
                echo '<div class="notice notice-error is-dismissible"><p><strong>Import Errors (' . count($errors) . '):</strong></p><ul>';
                foreach ( $errors as $error ) {
                    echo '<li>' . esc_html( $error ) . '</li>';
                }
                echo '</ul></div>';
            }
        });
    }

    /**
     * Get a value from CSV item using multiple possible column names
     */
    private function get_csv_value( $item, $possible_keys ) {
        foreach ( $possible_keys as $key ) {
            // Try exact match first
            if ( isset( $item[ $key ] ) && $item[ $key ] !== '' ) {
                return trim( $item[ $key ] );
            }
            // Try with normalized key (spaces to underscores)
            $normalized = str_replace( ' ', '_', $key );
            if ( isset( $item[ $normalized ] ) && $item[ $normalized ] !== '' ) {
                return trim( $item[ $normalized ] );
            }
            // Try with underscores to spaces
            $with_spaces = str_replace( '_', ' ', $key );
            if ( isset( $item[ $with_spaces ] ) && $item[ $with_spaces ] !== '' ) {
                return trim( $item[ $with_spaces ] );
            }
        }
        return '';
    }

    /**
     * Find or create a term with caching, using code as PRIMARY unique identifier
     */
    private function find_or_create_term_by_code( $term_name, $description = '', &$cache, $code = '' ) {
        // Helper to check if code has a value (including '0')
        $has_code = ( $code !== '' && $code !== null );

        // Code is the primary identifier - must be provided
        if ( ! $has_code ) {
            error_log( "Accessories Import - Warning: No code provided for term '$term_name'. Code is required as unique identifier." );
        }

        // Create cache key - prioritize code
        $cache_key = $has_code ? 'code_' . $code : 'name_' . sanitize_title( $term_name );

        // Check cache first
        if ( isset( $cache[ $cache_key ] ) ) {
            return $cache[ $cache_key ];
        }

        $existing_term = null;
        $found_by = '';

        // ALWAYS try to find by code first if provided (code is the unique key)
        if ( $has_code ) {
            $terms_with_code = get_terms([
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'     => 'tax_acc__code',
                        'value'   => $code,
                        'compare' => '='
                    ]
                ]
            ]);

            if ( ! empty( $terms_with_code ) && ! is_wp_error( $terms_with_code ) ) {
                $existing_term = $terms_with_code[0];
                $found_by = 'code';
                error_log( "Accessories Import - Found existing term by code '$code': term_id={$existing_term->term_id}, current_name='{$existing_term->name}'" );
            }
        }

        // If not found by code, try to find by exact name match (fallback for legacy data)
        if ( ! $existing_term && ! empty( $term_name ) ) {
            $terms = get_terms([
                'taxonomy'   => $this->taxonomy,
                'name'       => $term_name,
                'hide_empty' => false,
            ]);

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                // Found term(s) by name - check if any of them ALREADY has a code
                foreach ( $terms as $term ) {
                    $existing_code = get_field( 'tax_acc__code', $this->taxonomy . '_' . $term->term_id );
                    
                    // If it has no code, we can safely use it and assign the new code
                    if ( empty( $existing_code ) ) {
                        $existing_term = $term;
                        $found_by = 'name (no code)';
                        error_log( "Accessories Import - Found existing term by name '$term_name' with no code. Will assign code '$code'." );
                        break;
                    } 
                    
                    // If it has the SAME code, we should have found it by code already, 
                    // but just in case, we can use it.
                    if ( $existing_code === $code ) {
                        $existing_term = $term;
                        $found_by = 'name (same code)';
                        break;
                    }
                }
                
                if ( ! $existing_term ) {
                    error_log( "Accessories Import - Found term(s) with name '$term_name' but they have different codes. Will create a new unique term." );
                }
            }
        }

        // Update or create term
        if ( $existing_term && ! is_wp_error( $existing_term ) ) {
            // Update existing term - only update name if we have a valid name
            $term_args = [];

            // Only update name if it's not empty and different from current
            if ( ! empty( $term_name ) && $term_name !== $existing_term->name ) {
                $term_args['name'] = $term_name;
                error_log( "Accessories Import - Updating term name from '{$existing_term->name}' to '$term_name'" );
            }

            if ( ! empty( $description ) ) {
                $term_args['description'] = $description;
            }

            // Only call update if we have something to update
            if ( ! empty( $term_args ) ) {
                $result = wp_update_term( $existing_term->term_id, $this->taxonomy, $term_args );

                if ( is_wp_error( $result ) ) {
                    error_log( 'Term update failed for ' . $term_name . ': ' . $result->get_error_message() );
                    return false;
                }

                $term_id = $result['term_id'];
            } else {
                $term_id = $existing_term->term_id;
            }
        } else {
            // Create new term - name is required for new terms
            if ( empty( $term_name ) ) {
                error_log( "Accessories Import - Cannot create term: name is empty (code: $code)" );
                return false;
            }

            $term_args = [];

            if ( ! empty( $description ) ) {
                $term_args['description'] = $description;
            }

            // If term name conflict exists, try to make it unique by appending code
            $final_term_name = $term_name;
            $result = wp_insert_term( $final_term_name, $this->taxonomy, $term_args );

            if ( is_wp_error( $result ) ) {
                // If term exists with same slug/name, check if we should still try to make it unique
                if ( $result->get_error_code() === 'term_exists' ) {
                    // If we reach here, it means find_or_create_term_by_code found a name conflict 
                    // that wasn't resolved (different code).
                    // We try once more with the code in the name to ensure uniqueness.
                    $final_term_name = $term_name . ' (' . $code . ')';
                    error_log( "Accessories Import - Term name conflict for '$term_name'. Retrying with unique name '$final_term_name'" );
                    $result = wp_insert_term( $final_term_name, $this->taxonomy, $term_args );
                }
            }

            if ( is_wp_error( $result ) ) {
                // If term exists with same slug, get that term
                if ( $result->get_error_code() === 'term_exists' ) {
                    $term_id = $result->get_error_data();
                    error_log( "Accessories Import - Term '$term_name' already exists with term_id=$term_id" );
                } else {
                    error_log( 'Term creation failed for ' . $term_name . ': ' . $result->get_error_message() );
                    return false;
                }
            } else {
                $term_id = $result['term_id'];
                error_log( "Accessories Import - Created new term '$term_name' with term_id=$term_id" );
            }
        }

        // Cache the result
        $cache[ $cache_key ] = $term_id;

        return $term_id;
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
new Puk_Accessories_Importer_Exporter();
