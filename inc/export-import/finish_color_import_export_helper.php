<?php
/**
 * Finish Color Taxonomy Import/Export Helper
 * 
 * Handles CSV export and import for 'finish-color' taxonomy with ACF fields.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Puk_Finish_Color_Importer_Exporter {

    private $taxonomy = 'finish-color';
    private $acf_fields = [];

    public function __construct() {
        // Load ACF fields configuration for taxonomy
        $this->acf_fields = $this->get_taxonomy_acf_fields_config();
        
        // Handle Actions
        add_action( 'admin_init', [ $this, 'handle_finish_color_export_request' ] );
        add_action( 'admin_init', [ $this, 'handle_finish_color_import_request' ] );
    }

    /**
     * Load ACF fields configuration for finish-color taxonomy
     */
    private function get_taxonomy_acf_fields_config() {
        // Check if there's a specific ACF config file for finish-color taxonomy
        $config_file = get_template_directory() . '/acf_finish_color_taxonomy.php';
        
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
     * Handles the finish-color taxonomy CSV export generation.
     */
    public function handle_finish_color_export_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'export_finish_color' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_export_finish_color'] ) || ! wp_verify_nonce( $_POST['_wpnonce_export_finish_color'], 'puk_export_finish_color_nonce' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Set headers for CSV download
        $filename = 'finish-color-taxonomy-export-' . date( 'Y-m-d' ) . '.csv';
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );

        // Open output stream
        $output = fopen( 'php://output', 'w' );
        
        // Add BOM for Excel compatibility
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        // Define CSV Headers - Specific columns only
        $headers = [
            'Color Code',
            'Color',
            'Description',
            'Color Image',
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
                // Get Color Code (tax_finish_color__code ACF field)
                $color_code = get_field( 'tax_finish_color__code', $this->taxonomy . '_' . $term->term_id );

                // Get Color Image (tax_finish_color__img ACF field) - export as URL
                $color_image = '';
                $color_image_data = get_field( 'tax_finish_color__img', $this->taxonomy . '_' . $term->term_id );
                if ( ! empty( $color_image_data ) ) {
                    if ( is_array( $color_image_data ) && isset( $color_image_data['url'] ) ) {
                        $color_image = $color_image_data['url'];
                    } elseif ( is_numeric( $color_image_data ) ) {
                        $url = wp_get_attachment_url( $color_image_data );
                        if ( $url ) {
                            $color_image = $url;
                        }
                    } elseif ( is_string( $color_image_data ) && filter_var( $color_image_data, FILTER_VALIDATE_URL ) ) {
                        $color_image = $color_image_data;
                    }
                }
                
                // Build row data
                $row = [
                    $color_code ?: '',
                    $term->name,
                    $term->description,
                    $color_image,
                ];

                fputcsv( $output, $row );
            }
        }

        fclose( $output );
        exit();
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
            case 'image':
                // Return image URL
                if ( is_array( $value ) && isset( $value['url'] ) ) {
                    return $value['url'];
                } elseif ( is_numeric( $value ) ) {
                    return wp_get_attachment_url( $value );
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
     * Handles the finish-color taxonomy import logic.
     */
    public function handle_finish_color_import_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'import_finish_color' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_import_finish_color'] ) || ! wp_verify_nonce( $_POST['_wpnonce_import_finish_color'], 'puk_import_finish_color_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'puk' ) );
        }

        if ( empty( $_FILES['import_finish_color_file']['tmp_name'] ) ) {
            wp_die( __( 'No file uploaded.', 'puk' ) );
        }

        $file_path = $_FILES['import_finish_color_file']['tmp_name'];
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
        
        // Verify required column exists
        if ( ! in_array( 'color', $headers ) ) {
            $msg = "CRITICAL ERROR: 'Color' column missing in CSV. Found headers: " . implode( ', ', $headers );
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
            
            // Combine headers with row data
            if ( count( $headers ) !== count( $row ) ) {
                $msg = "Row $row_count: Column count mismatch. Headers: " . count($headers) . ", Row: " . count($row);
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }
            $item = array_combine( $headers, $row );

            // Prepare Term Data
            $color_code = isset( $item['color code'] ) ? trim( $item['color code'] ) : '';
            $color_name = isset( $item['color'] ) ? trim( $item['color'] ) : '';
            $description = isset( $item['description'] ) ? $item['description'] : '';
            $color_image = isset( $item['color image'] ) ? trim( $item['color image'] ) : '';
            
            if ( empty( $color_name ) ) {
                $msg = "Row $row_count: Color name is empty.";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }
            
            // Create or update the term using color_code as unique identifier if provided
            $new_term_id = $this->find_or_create_term_by_code( $color_name, $description, $created_terms_cache, $color_code );
            
            if ( ! $new_term_id ) {
                $msg = "Row $row_count: Failed to create/update term '{$color_name}'";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            // Handle Color Code (tax_finish_color__code ACF field)
            if ( ! empty( $color_code ) ) {
                update_field( 'tax_finish_color__code', $color_code, $this->taxonomy . '_' . $new_term_id );
            }

            // Handle Color Image (tax_finish_color__img ACF field)
            if ( ! empty( $color_image ) ) {
                if ( filter_var( $color_image, FILTER_VALIDATE_URL ) ) {
                    // It's a URL, download and create attachment
                    $attachment_id = $this->insert_attachment_from_url( $color_image );
                    if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                        update_field( 'tax_finish_color__img', $attachment_id, $this->taxonomy . '_' . $new_term_id );
                    }
                } elseif ( is_numeric( $color_image ) ) {
                    // It's already an attachment ID
                    update_field( 'tax_finish_color__img', intval( $color_image ), $this->taxonomy . '_' . $new_term_id );
                }
            }

            $imported_count++;
        }

        fclose( $handle );
        
        error_log( "Finish Color Taxonomy Import completed: $imported_count terms imported out of $row_count rows processed" );

        add_action( 'admin_notices', function() use ( $imported_count, $errors ) {
            $class = $imported_count > 0 ? 'notice-success' : 'notice-warning';
            echo '<div class="notice ' . $class . ' is-dismissible"><p>' . sprintf( __( '%d finish-color terms imported successfully.', 'puk' ), $imported_count ) . '</p></div>';
            
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
     * Find or create a term with caching, using color_code as unique identifier
     */
    private function find_or_create_term_by_code( $term_name, $description = '', &$cache, $color_code = '' ) {
        // Create cache key
        $cache_key = ! empty( $color_code ) ? 'code_' . $color_code : 'name_' . $term_name;
        
        // Check cache first
        if ( isset( $cache[ $cache_key ] ) ) {
            return $cache[ $cache_key ];
        }
        
        $existing_term = null;
        
        // Try to find by color_code first if provided
        if ( ! empty( $color_code ) ) {
            $terms_with_code = get_terms([
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'     => 'tax_finish_color__code',
                        'value'   => $color_code,
                        'compare' => '='
                    ]
                ]
            ]);
            
            if ( ! empty( $terms_with_code ) && ! is_wp_error( $terms_with_code ) ) {
                $existing_term = $terms_with_code[0];
            }
        }
        
        // If not found by code, try to find by name
        if ( ! $existing_term ) {
            $terms = get_terms([
                'taxonomy'   => $this->taxonomy,
                'name'       => $term_name,
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
            $term_args = [];
            
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
            case 'image':
                // Expecting image URL or ID
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
new Puk_Finish_Color_Importer_Exporter();