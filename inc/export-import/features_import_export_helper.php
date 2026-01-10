<?php
/**
 * Features Taxonomy Import/Export Helper
 *
 * Handles CSV export and import for 'features' taxonomy with ACF fields.
 *
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Puk_Features_Importer_Exporter {

    private $taxonomy = 'features';
    private $acf_fields = [];

    public function __construct() {
        // Load ACF fields configuration for taxonomy
        $this->acf_fields = $this->get_taxonomy_acf_fields_config();

        // Handle Actions
        add_action( 'admin_init', [ $this, 'handle_features_export_request' ] );
        add_action( 'admin_init', [ $this, 'handle_features_import_request' ] );
    }

    /**
     * Load ACF fields configuration for features taxonomy
     */
    private function get_taxonomy_acf_fields_config() {
        // Check if there's a specific ACF config file for features taxonomy
        $config_file = get_template_directory() . '/acf_features_taxonomy.php';

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
     * Handles the features taxonomy CSV export generation.
     */
    public function handle_features_export_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'export_features' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_export_features'] ) || ! wp_verify_nonce( $_POST['_wpnonce_export_features'], 'puk_export_features_nonce' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Set headers for CSV download
        $filename = 'features-taxonomy-export-' . date( 'Y-m-d' ) . '.csv';
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
            'Type',
            'Icon',
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
                // Get Code (tax_featured__code ACF field)
                $code = get_field( 'tax_featured__code', $this->taxonomy . '_' . $term->term_id );

                // Get Type (tax_featured__type ACF field)
                $type = get_field( 'tax_featured__type', $this->taxonomy . '_' . $term->term_id );

                // Get Icon (tax_featured__icon ACF field) - export as URL
                $icon = '';
                $icon_data = get_field( 'tax_featured__icon', $this->taxonomy . '_' . $term->term_id );
                if ( ! empty( $icon_data ) ) {
                    if ( is_array( $icon_data ) && isset( $icon_data['url'] ) ) {
                        $icon = $icon_data['url'];
                    } elseif ( is_numeric( $icon_data ) ) {
                        $url = wp_get_attachment_url( $icon_data );
                        if ( $url ) {
                            $icon = $url;
                        }
                    } elseif ( is_string( $icon_data ) && filter_var( $icon_data, FILTER_VALIDATE_URL ) ) {
                        $icon = $icon_data;
                    }
                }

                // Build row data
                $row = [
                    $code ?: '',
                    $term->name,
                    $type ?: '',
                    $icon,
                ];

                fputcsv( $output, $row );
            }
        }

        fclose( $output );
        exit();
    }

    /**
     * Handles the features taxonomy import logic.
     */
    public function handle_features_import_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'import_features' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_import_features'] ) || ! wp_verify_nonce( $_POST['_wpnonce_import_features'], 'puk_import_features_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'puk' ) );
        }

        if ( empty( $_FILES['import_features_file']['tmp_name'] ) ) {
            wp_die( __( 'No file uploaded.', 'puk' ) );
        }

        $file_path = $_FILES['import_features_file']['tmp_name'];
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

            // Combine headers with row data
            if ( count( $headers ) !== count( $row ) ) {
                $msg = "Row $row_count: Column count mismatch. Headers: " . count($headers) . ", Row: " . count($row);
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }
            $item = array_combine( $headers, $row );

            // Prepare Term Data
            $code = isset( $item['code'] ) ? trim( $item['code'] ) : '';
            $name = isset( $item['name'] ) ? trim( $item['name'] ) : '';
            $type = isset( $item['type'] ) ? trim( $item['type'] ) : '';
            $icon = isset( $item['icon'] ) ? trim( $item['icon'] ) : '';

            if ( empty( $name ) ) {
                $msg = "Row $row_count: Name is empty.";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            // Create or update the term using code as unique identifier if provided
            $new_term_id = $this->find_or_create_term_by_code( $name, '', $created_terms_cache, $code );

            if ( ! $new_term_id ) {
                $msg = "Row $row_count: Failed to create/update term '{$name}'";
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }

            // Handle Code (tax_featured__code ACF field)
            if ( ! empty( $code ) ) {
                update_field( 'tax_featured__code', $code, $this->taxonomy . '_' . $new_term_id );
            }

            // Handle Type (tax_featured__type ACF field)
            if ( ! empty( $type ) ) {
                update_field( 'tax_featured__type', $type, $this->taxonomy . '_' . $new_term_id );
            }

            // Handle Icon (tax_featured__icon ACF field)
            if ( ! empty( $icon ) ) {
                if ( filter_var( $icon, FILTER_VALIDATE_URL ) ) {
                    // It's a URL, download and create attachment
                    $attachment_id = $this->insert_attachment_from_url( $icon );
                    if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
                        update_field( 'tax_featured__icon', $attachment_id, $this->taxonomy . '_' . $new_term_id );
                    }
                } elseif ( is_numeric( $icon ) ) {
                    // It's already an attachment ID
                    update_field( 'tax_featured__icon', intval( $icon ), $this->taxonomy . '_' . $new_term_id );
                }
            }

            $imported_count++;
        }

        fclose( $handle );

        error_log( "Features Taxonomy Import completed: $imported_count terms imported out of $row_count rows processed" );

        add_action( 'admin_notices', function() use ( $imported_count, $errors ) {
            $class = $imported_count > 0 ? 'notice-success' : 'notice-warning';
            echo '<div class="notice ' . $class . ' is-dismissible"><p>' . sprintf( __( '%d features terms imported successfully.', 'puk' ), $imported_count ) . '</p></div>';

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
     * Find or create a term with caching, using code as unique identifier
     */
    private function find_or_create_term_by_code( $term_name, $description = '', &$cache, $code = '' ) {
        // Create cache key
        $cache_key = ! empty( $code ) ? 'code_' . $code : 'name_' . $term_name;

        // Check cache first
        if ( isset( $cache[ $cache_key ] ) ) {
            return $cache[ $cache_key ];
        }

        $existing_term = null;

        // Try to find by code first if provided
        if ( ! empty( $code ) ) {
            $terms_with_code = get_terms([
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'     => 'tax_featured__code',
                        'value'   => $code,
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
new Puk_Features_Importer_Exporter();
