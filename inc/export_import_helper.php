<?php
/**
 * Product Import/Export Helper
 * 
 * Handles CSV export and import for 'products' CPT and 'products-family' taxonomy.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Puk_Product_Importer_Exporter {

    private $post_type = 'products';
    private $taxonomy  = 'products-family';
    private $acf_fields = [];

    public function __construct() {
        // Load ACF fields configuration
        $this->acf_fields = $this->get_acf_fields_config();
        
        // Debug: Log the loaded ACF fields
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'PUK Export/Import: Loaded ' . count( $this->acf_fields ) . ' ACF fields' );
            foreach ( $this->acf_fields as $field ) {
                error_log( 'PUK Export/Import: Field - ' . $field['label'] . ' (' . $field['name'] . ' - ' . $field['type'] . ')' );
            }
        }
        
        // Admin Menu
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );

        // Handle Actions
        add_action( 'admin_init', [ $this, 'handle_export_request' ] );
        add_action( 'admin_init', [ $this, 'handle_import_request' ] );
    }

    /**
     * Load ACF fields configuration from acf_meta_fields.php
     */
    private function get_acf_fields_config() {
        $config_file = get_template_directory() . '/acf_meta_fields.php';
        
        if ( ! file_exists( $config_file ) ) {
            return [];
        }

        // Include the file to get the field definitions
        include $config_file;
        
        // If the file defines a variable with field data, use it
        if ( isset( $acf_field_definitions ) && is_array( $acf_field_definitions ) ) {
            $fields = [];
            $current_repeater = null;
            
            foreach ( $acf_field_definitions as $field_def ) {
                $field_data = [
                    'label' => $field_def[0],
                    'name'  => $field_def[1],
                    'type'  => $field_def[2],
                    'sub_fields' => []
                ];
                
                if ( $field_def[2] === 'repeater' ) {
                    $current_repeater = $field_def[1];
                    $field_data['parent_repeater'] = null;
                    $fields[] = $field_data;
                } elseif ( $current_repeater !== null &&
                          // Check if this field is actually a sub-field of the current repeater
                          // by checking if the field name starts with the repeater name
                          strpos( $field_def[1], $current_repeater ) === 0 ) {
                    // Inside a repeater group, only fields with names starting with repeater name are sub-fields
                    $field_data['parent_repeater'] = $current_repeater;
                    
                    // Add to parent
                    foreach ( $fields as &$parent_field ) {
                        if ( $parent_field['name'] === $current_repeater ) {
                            $parent_field['sub_fields'][] = $field_data;
                            break;
                        }
                    }
                    unset( $parent_field );
                } else {
                    // Reset current_repeater if this field doesn't belong to it
                    if ( $current_repeater !== null && strpos( $field_def[1], $current_repeater ) !== 0 ) {
                        $current_repeater = null;
                    }
                    $field_data['parent_repeater'] = null;
                    $fields[] = $field_data;
                }
            }
            
            return $fields;
        }
        
        // Fallback to the original parsing method if the variable is not defined
        $content = file_get_contents( $config_file );
        $fields = [];
        
        // Simple state machine to extract top-level groups [ ... ]
        $groups = [];
        $depth = 0;
        $buffer = '';
        $in_string = false;
        $string_char = '';
        
        for ( $i = 0; $i < strlen( $content ); $i++ ) {
            $char = $content[$i];
            
            // Handle strings to ignore brackets inside them
            if ( $in_string ) {
                $buffer .= $char;
                if ( $char === $string_char && $content[$i-1] !== '\\' ) {
                    $in_string = false;
                }
                continue;
            }
            
            if ( $char === '"' || $char === "'" ) {
                $in_string = true;
                $string_char = $char;
                $buffer .= $char;
                continue;
            }
            
            if ( $char === '[' ) {
                if ( $depth === 0 ) {
                    $buffer = ''; // Start capturing new group
                }
                $depth++;
                $buffer .= $char;
            } elseif ( $char === ']' ) {
                $depth--;
                $buffer .= $char;
                if ( $depth === 0 ) {
                    $groups[] = $buffer; // End of group
                    $buffer = '';
                }
            } elseif ( $depth > 0 ) {
                $buffer .= $char;
            }
        }
        
        // Process each group
        foreach ( $groups as $group_content ) {
            // Parse fields within this group
            preg_match_all('/\["([^"]+)",\s*"([^"]+)",\s*"([^"]+)"\]/', $group_content, $field_matches, PREG_SET_ORDER);
            
            $current_repeater = null; // Reset context for each new group
            
            foreach ( $field_matches as $field ) {
                $field_data = [
                    'label' => $field[1],
                    'name'  => $field[2],
                    'type'  => $field[3],
                    'sub_fields' => []
                ];
                
                if ( $field[3] === 'repeater' ) {
                    $current_repeater = $field[2];
                    $field_data['parent_repeater'] = null;
                    $fields[] = $field_data;
                } elseif ( $current_repeater !== null &&
                          // Check if this field is actually a sub-field of current repeater
                          // by checking if field name starts with repeater name
                          strpos( $field[2], $current_repeater ) === 0 ) {
                    // Inside a repeater group, only fields with names starting with repeater name are sub-fields
                    $field_data['parent_repeater'] = $current_repeater;
                    
                    // Add to parent
                    foreach ( $fields as &$parent_field ) {
                        if ( $parent_field['name'] === $current_repeater ) {
                            $parent_field['sub_fields'][] = $field_data;
                            break;
                        }
                    }
                    unset( $parent_field );
                } else {
                    // Reset current_repeater if this field doesn't belong to it
                    if ( $current_repeater !== null && strpos( $field[2], $current_repeater ) !== 0 ) {
                        $current_repeater = null;
                    }
                    $field_data['parent_repeater'] = null;
                    $fields[] = $field_data;
                }
            }
        }
        
        return $fields;
    }

    /**
     * Registers the "Import/Export" submenu under "Products".
     */
    public function register_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=' . $this->post_type,
            __( 'Import/Export Products', 'puk' ),
            __( 'Import/Export', 'puk' ),
            'manage_options',
            'puk-product-import-export',
            [ $this, 'render_admin_page' ]
        );
    }

    /**
     * Renders the unified Import/Export Admin Page.
     */
    public function render_admin_page() {
        ?>
<div class="wrap">
    <h1><?php _e( 'Import/Export Products', 'puk' ); ?></h1>

    <style>
    .puk-import-export-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .puk-import-export-card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        padding: 20px;
    }

    .puk-import-export-card h2 {
        margin-top: 0;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
    }

    @media screen and (max-width: 1024px) {
        .puk-import-export-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <div class="puk-import-export-grid">
        <!-- Products Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Products', 'puk' ); ?></h2>

            <!-- Export Products -->
            <h3><?php _e( 'Export Products', 'puk' ); ?></h3>
            <p><?php _e( 'Download all products and their metadata as a CSV file.', 'puk' ); ?></p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_products">
                <?php wp_nonce_field( 'puk_export_nonce', '_wpnonce_export' ); ?>
                <?php submit_button( __( 'Export All Products', 'puk' ), 'primary', 'submit_export_products' ); ?>
            </form>

            <!-- Import Products -->
            <h3><?php _e( 'Import Products', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import products. Ensure headers match exactly.', 'puk' ); ?></p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_products">
                <?php wp_nonce_field( 'puk_import_nonce', '_wpnonce_import' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="import_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_file" id="import_file" accept=".csv" required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_products' ); ?>
            </form>
        </div>

        <!-- Products Family Taxonomy Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Products Family Taxonomy', 'puk' ); ?></h2>

            <!-- Export Taxonomy -->
            <h3><?php _e( 'Export Taxonomy', 'puk' ); ?></h3>
            <p><?php _e( 'Download all products-family taxonomy terms and their ACF fields as a CSV file.', 'puk' ); ?>
            </p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_taxonomy">
                <?php wp_nonce_field( 'puk_export_taxonomy_nonce', '_wpnonce_export_taxonomy' ); ?>
                <?php submit_button( __( 'Export Taxonomy', 'puk' ), 'primary', 'submit_export_taxonomy' ); ?>
            </form>

            <!-- Import Taxonomy -->
            <h3><?php _e( 'Import Taxonomy', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import products-family taxonomy terms. Ensure headers match exactly.', 'puk' ); ?>
            </p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_taxonomy">
                <?php wp_nonce_field( 'puk_import_taxonomy_nonce', '_wpnonce_import_taxonomy' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label
                                for="import_taxonomy_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_taxonomy_file" id="import_taxonomy_file" accept=".csv"
                                required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_taxonomy' ); ?>
            </form>
        </div>

        <!-- Finish Color Taxonomy Section -->
        <div class="puk-import-export-card">
            <h2><?php _e( 'Finish Color Taxonomy', 'puk' ); ?></h2>

            <!-- Export Finish Color -->
            <h3><?php _e( 'Export Finish Color', 'puk' ); ?></h3>
            <p><?php _e( 'Download all finish-color taxonomy terms and their ACF fields as a CSV file.', 'puk' ); ?></p>
            <form method="post" action="">
                <input type="hidden" name="puk_action" value="export_finish_color">
                <?php wp_nonce_field( 'puk_export_finish_color_nonce', '_wpnonce_export_finish_color' ); ?>
                <?php submit_button( __( 'Export Finish Color', 'puk' ), 'primary', 'submit_export_finish_color' ); ?>
            </form>

            <!-- Import Finish Color -->
            <h3><?php _e( 'Import Finish Color', 'puk' ); ?></h3>
            <p><?php _e( 'Upload a CSV file to import finish-color taxonomy terms. Ensure headers match exactly.', 'puk' ); ?>
            </p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="puk_action" value="import_finish_color">
                <?php wp_nonce_field( 'puk_import_finish_color_nonce', '_wpnonce_import_finish_color' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label
                                for="import_finish_color_file"><?php _e( 'Choose CSV File', 'puk' ); ?></label></th>
                        <td><input type="file" name="import_finish_color_file" id="import_finish_color_file"
                                accept=".csv" required></td>
                    </tr>
                </table>

                <?php submit_button( __( 'Run Import', 'puk' ), 'secondary', 'submit_import_finish_color' ); ?>
            </form>
        </div>
    </div>
</div>
<?php
    }

    /**
     * Handles the CSV export generation.
     */
    public function handle_export_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'export_products' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_export'] ) || ! wp_verify_nonce( $_POST['_wpnonce_export'], 'puk_export_nonce' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Set headers for CSV download
        $filename = 'products-export-' . date( 'Y-m-d' ) . '.csv';
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );

        // Open output stream
        $output = fopen( 'php://output', 'w' );
        
        // Add BOM for Excel compatibility
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        // Define CSV Headers
        $headers = [
            'Post ID',
            'Import UID', // Unique identifier for import/update
            'Title',
            'Content',
            'Status',
            'Products Family', // Taxonomy
        ];
        
        // Add ACF field headers
        foreach ( $this->acf_fields as $field ) {
            // Skip sub-fields in headers - only parent fields should be exported
            if ( empty( $field['parent_repeater'] ) ) {
                $headers[] = $field['label'];
            }
        }

        fputcsv( $output, $headers );

        // Query Products
        $args = [
            'post_type'      => $this->post_type,
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ];
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id = get_the_ID();

                // Basic Post Data
                $row = [
                    $post_id,
                    get_field( '_import_uid', $post_id ), // Unique identifier
                    html_entity_decode( get_the_title() ),
                    get_the_content(), // Raw HTML
                    get_post_status(),
                    $this->get_taxonomy_string( $post_id ),
                ];
                
                // Add ACF field values
                foreach ( $this->acf_fields as $field ) {
                    $field_value = $this->get_acf_field_value( $post_id, $field );
                    // Ensure field_value is a string for CSV export
                    if (is_array($field_value)) {
                        $field_value = json_encode($field_value);
                    }
                    $row[] = $field_value;
                    
                    // Debug: Log if field value is empty
                    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && empty( $field_value ) ) {
                        error_log( "PUK Export/Import: Empty value for field '{$field['name']}' in post {$post_id}" );
                    }
                }

                fputcsv( $output, $row );
            }
        }

        fclose( $output );
        exit();
    }

    /**
     * Get ACF field value formatted for CSV export based on field type
     */
    private function get_acf_field_value( $post_id, $field ) {
        $value = get_field( $field['name'], $post_id );
        
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
                
            case 'relationship':
                // Return JSON array of Post Titles (IDs are not portable)
                if ( is_array( $value ) && ! empty( $value ) ) {
                    $titles = [];
                    foreach ( $value as $post ) {
                        // $post can be an object or ID depending on ACF return format
                        if ( is_object( $post ) ) {
                            $titles[] = $post->post_title; // Use raw title, will be JSON encoded
                        } elseif ( is_numeric( $post ) ) {
                            $titles[] = get_the_title( $post );
                        } elseif ( is_array( $post ) && isset( $post['post_title'] ) ) {
                             $titles[] = $post['post_title'];
                        }
                    }
                    // Filter empty titles
                    $titles = array_filter( $titles );
                    
                    if ( ! empty( $titles ) ) {
                        return json_encode( $titles, JSON_UNESCAPED_UNICODE );
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
                // Return image ID
                if ( is_array( $value ) ) {
                    return isset( $value['ID'] ) ? $value['ID'] : '';
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
     * Helper to get taxonomy terms as a pipe-separated string with full hierarchy.
     */
    private function get_taxonomy_string( $post_id ) {
        $terms = get_the_terms( $post_id, $this->taxonomy );
        if ( ! $terms || is_wp_error( $terms ) ) {
            return '';
        }

        $hierarchical_paths = [];

        foreach ( $terms as $term ) {
            // Build the full path for this term (Parent > Child > Grandchild)
            $path = $this->get_term_hierarchy_path( $term );
            $hierarchical_paths[] = $path;
        }

        return implode( ' | ', $hierarchical_paths );
    }

    /**
     * Get the full hierarchical path for a term (e.g., "Parent > Child > Grandchild")
     */
    private function get_term_hierarchy_path( $term ) {
        $path = [];
        $current_term = $term;

        // Traverse up the hierarchy
        while ( $current_term ) {
            array_unshift( $path, $current_term->name );
            
            if ( $current_term->parent ) {
                $current_term = get_term( $current_term->parent, $this->taxonomy );
            } else {
                break;
            }
        }

        return implode( ' > ', $path );
    }

    /**
     * Handles the Import Logic.
     */
    public function handle_import_request() {
        if ( ! isset( $_POST['puk_action'] ) || $_POST['puk_action'] !== 'import_products' ) {
            return;
        }

        if ( ! isset( $_POST['_wpnonce_import'] ) || ! wp_verify_nonce( $_POST['_wpnonce_import'], 'puk_import_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'puk' ) );
        }

        if ( empty( $_FILES['import_file']['tmp_name'] ) ) {
            wp_die( __( 'No file uploaded.', 'puk' ) );
        }

        $file_path = $_FILES['import_file']['tmp_name'];
        $handle = fopen( $file_path, 'r' );

        if ( $handle === false ) {
            wp_die( __( 'Could not open file.', 'puk' ) );
        }

        // Read the first line to detect delimiter
        $first_line = fgets($handle);
        rewind($handle);
        
        // Enhanced delimiter detection
        $delimiters = [',', ';', "\t", '|'];
        $delimiter_counts = [];
        
        foreach ($delimiters as $delim) {
            $delimiter_counts[$delim] = substr_count($first_line, $delim);
        }
        
        // Choose the delimiter with the highest count (but not zero)
        $delimiter = ',';
        $max_count = 0;
        foreach ($delimiter_counts as $delim => $count) {
            if ($count > $max_count) {
                $max_count = $count;
                $delimiter = $delim;
            }
        }
        
        // If max count is 0, try to detect by reading the first row with each delimiter
        if ($max_count == 0) {
            foreach ($delimiters as $test_delimiter) {
                rewind($handle);
                $test_headers = fgetcsv($handle, 0, $test_delimiter);
                
                if (count($test_headers) > 5) { // Expecting at least 6 columns
                    $delimiter = $test_delimiter;
                    $max_count = count($test_headers);
                    error_log("PUK Import: Fallback detection using delimiter '$delimiter' with " . count($test_headers) . " columns");
                    break;
                }
            }
        }
        
        // Debug logging
        error_log("PUK Import: First line: " . trim($first_line));
        error_log("PUK Import: Detected delimiter '$delimiter' with count $max_count");
        error_log("PUK Import: Delimiter counts - " . print_r($delimiter_counts, true));

        // Read Headers with detected delimiter
        rewind($handle);
        $headers = fgetcsv( $handle, 0, $delimiter );
        
        // Remove BOM from first header if present
        if ( isset( $headers[0] ) ) {
            $headers[0] = preg_replace( '/^\xEF\xBB\xBF/', '', $headers[0] );
        }

        $headers = array_map( 'trim', $headers );
        $headers = array_map( 'strtolower', $headers );
        
        // Deduplicate headers to prevent overwriting
        // e.g. "title", "title" becomes "title", "title_2"
        $header_counts = [];
        foreach ( $headers as $i => $header ) {
            if ( isset( $header_counts[ $header ] ) ) {
                $header_counts[ $header ]++;
                $headers[ $i ] = $header . '_' . $header_counts[ $header ];
            } else {
                $header_counts[ $header ] = 1;
            }
        }
        
        // Verify 'title' column exists immediately
        if ( ! in_array( 'title', $headers ) ) {
            $msg = "CRITICAL ERROR: 'Title' column missing in CSV. Found headers: " . implode( ', ', $headers );
            error_log( $msg );
            // Show this error immediately and stop
            add_action( 'admin_notices', function() use ( $msg ) {
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
            });
            fclose( $handle );
            return;
        }

        $imported_count = 0;
        $row_count = 0;
        $errors = [];

        while ( ( $row = fgetcsv( $handle, 0, $delimiter ) ) !== false ) {
            $row_count++;
            $row = array_map( 'trim', $row );
            
            // Debug: Log raw row data for first few rows
            if ($row_count <= 3) {
                error_log("PUK Import: Row $row_count raw data - " . print_r($row, true));
                error_log("PUK Import: Row $row_count column count = " . count($row));
            }
            
            // Combine headers with row data
            if ( count( $headers ) !== count( $row ) ) {
                $msg = "Row $row_count: Column count mismatch. Headers: " . count($headers) . ", Row: " . count($row);
                
                // Add more debug info for the first few errors
                if (count($errors) < 3) {
                    $msg .= " | Headers: " . implode(', ', array_slice($headers, 0, 5)) . "...";
                    $msg .= " | Row data: " . implode(', ', array_slice($row, 0, 3)) . "...";
                }
                
                error_log( $msg );
                $errors[] = $msg;
                
                // Try to fix the row if it has fewer columns
                if (count($row) < count($headers)) {
                    // Pad the row with empty values
                    $row = array_pad($row, count($headers), '');
                    error_log("PUK Import: Padded row $row_count to match header count");
                } elseif (count($row) > count($headers)) {
                    // Truncate the row if it has too many columns
                    $row = array_slice($row, 0, count($headers));
                    error_log("PUK Import: Truncated row $row_count to match header count");
                } else {
                    continue; // Skip only if we couldn't fix it
                }
            }
            $item = array_combine( $headers, $row );

            // Prepare Post Data
            $import_uid = isset( $item['import uid'] ) ? trim( $item['import uid'] ) : '';
            $post_title = isset( $item['title'] ) ? $item['title'] : '';
            
            // Allow "0" as a title, so use strict check for empty string
            if ( $post_title === '' ) {
                // Check if 'title' column even exists
                if ( ! in_array( 'title', $headers ) ) {
                    $msg = "Row $row_count: 'title' column missing. Available columns: " . implode( ', ', $headers );
                } else {
                    $msg = "Row $row_count: Title value is empty.";
                    // Debug: Log the actual item to see what's in it
                    error_log( "Row $row_count Data Dump: " . print_r( $item, true ) );
                }
                error_log( $msg );
                $errors[] = $msg;
                continue; // Skip if no title
            }

            // Check if post exists by _import_uid
            $existing_post_id = 0;
            if ( ! empty( $import_uid ) ) {
                $existing_post_id = $this->get_post_by_import_uid( $import_uid );
            }

            $post_args = [
                'post_type'    => $this->post_type,
                'post_status'  => isset( $item['status'] ) ? $item['status'] : 'publish',
                'post_title'   => $post_title,
                'post_content' => isset( $item['content'] ) ? wp_kses_post( $item['content'] ) : '',
            ];

            // Insert or Update Post
            if ( $existing_post_id > 0 ) {
                // Update existing post
                $post_args['ID'] = $existing_post_id;
                $new_post_id = wp_update_post( $post_args, true );
            } else {
                // Create new post
                $new_post_id = wp_insert_post( $post_args, true );
            }

            if ( is_wp_error( $new_post_id ) ) {
                $msg = "Row $row_count: Post creation failed - " . $new_post_id->get_error_message();
                error_log( $msg );
                $errors[] = $msg;
                continue;
            }
            
            error_log( "Row $row_count: Post created/updated successfully. ID: $new_post_id" );

            // Update or set the _import_uid
            if ( ! empty( $import_uid ) ) {
                update_field( '_import_uid', $import_uid, $new_post_id );
            }

            // --- Handle Taxonomy: Products Family (Hierarchical) ---
            if ( ! empty( $item['products family'] ) ) {
                $this->set_hierarchical_terms( $new_post_id, $item['products family'], $this->taxonomy );
            }

            // --- Handle Featured Image ---
            if ( ! empty( $item['featured image url'] ) ) {
                $image_url = $item['featured image url'];
                $image_id = $this->insert_image_from_url( $image_url, $new_post_id );
                if ( $image_id ) {
                    set_post_thumbnail( $new_post_id, $image_id );
                }
            }

            // --- Handle ACF Fields ---
            foreach ( $this->acf_fields as $field ) {
                $column_key = strtolower( $field['label'] );
                
                // Try multiple variations of the column name
                $possible_keys = [
                    $column_key,
                    str_replace(' ', '_', $column_key),
                    str_replace([' ', '-'], '_', $column_key),
                    strtolower($field['name']),
                    $field['name']
                ];
                
                // Special handling for Installation vs Integrated accessories
                if (strpos($field['label'], 'Installation') !== false) {
                    // Also try with "Integrated" in case CSV was exported with new field names
                    $possible_keys[] = str_replace('Installation', 'Integrated', $column_key);
                    $possible_keys[] = str_replace('installation', 'integrated', $column_key);
                }
                
                $field_value = '';
                $found_key = '';
                
                // Find the value in any of the possible keys
                foreach ($possible_keys as $key) {
                    if (isset($item[$key]) && $item[$key] !== '') {
                        $field_value = $item[$key];
                        $found_key = $key;
                        break;
                    }
                }
                
                if ($field_value !== '') {
                    try {
                        $this->set_acf_field_value( $new_post_id, $field, $field_value );
                        error_log("PUK Import: Successfully imported field '{$field['name']}' from column '$found_key'");
                    } catch ( Exception $e ) {
                        // Continue import even if one field fails
                        error_log( 'ACF Import Error for field ' . $field['name'] . ': ' . $e->getMessage() );
                    }
                } else {
                    // Log missing fields for debugging
                    error_log("PUK Import: Field '{$field['name']}' (label: '{$field['label']}') not found or empty in row $row_count");
                }
            }

            $imported_count++;
        }

        fclose( $handle );
        
        error_log( "Import completed: $imported_count products imported out of $row_count rows processed" );

        add_action( 'admin_notices', function() use ( $imported_count, $errors ) {
            $class = $imported_count > 0 ? 'notice-success' : 'notice-warning';
            echo '<div class="notice ' . $class . ' is-dismissible"><p>' . sprintf( __( '%d products imported successfully.', 'puk' ), $imported_count ) . '</p></div>';
            
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
     * Set ACF field value based on field type during import
     */
    private function set_acf_field_value( $post_id, $field, $value ) {
        $value = trim( $value );
        
        if ( $value === '' ) {
            return;
        }
        
        switch ( $field['type'] ) {
            case 'repeater':
                // Expecting JSON-encoded repeater data
                $value = stripslashes($value); // Handle potential double-escaping in CSV
                $decoded = json_decode( $value, true );
                
                if ( is_array( $decoded ) && ! empty( $decoded ) ) {
                    $repeater_rows = [];
                    
                    // Build a map of sub-field names to types for this repeater
                    $sub_field_map = [];
                    if ( ! empty( $field['sub_fields'] ) ) {
                        foreach ( $field['sub_fields'] as $sub_field ) {
                            $sub_field_map[ $sub_field['name'] ] = $sub_field['type'];
                        }
                    }
                    
                    foreach ( $decoded as $row_data ) {
                        $row = [];
                        
                        // Process all fields in the row data
                        foreach ( $row_data as $key => $sub_value ) {
                            // Skip if empty, unless it's a specific type that needs processing? (Usually empty is fine to skip)
                            if ( $sub_value === '' ) {
                                $row[ $key ] = '';
                                continue;
                            }

                            // Determine field type from schema, fallback to text
                            $sub_field_type = isset( $sub_field_map[ $key ] ) ? $sub_field_map[ $key ] : 'text';
                            
                            // Fallback guessing if schema is missing (legacy support)
                            if ( empty( $sub_field_map ) ) {
                                if ( strpos( $key, 'img' ) !== false || strpos( $key, 'image' ) !== false ) {
                                    $sub_field_type = 'image';
                                } elseif ( strpos( $key, 'file' ) !== false ) {
                                    $sub_field_type = 'file';
                                }
                            }
                            
                            switch ( $sub_field_type ) {
                                case 'image':
                                    // Expecting image ID or URL
                                    if ( is_numeric( $sub_value ) ) {
                                        $row[ $key ] = intval( $sub_value );
                                    } elseif ( filter_var( $sub_value, FILTER_VALIDATE_URL ) ) {
                                        // Handle URL for image in repeater
                                        $image_id = $this->insert_image_from_url( $sub_value, $post_id );
                                        if ( $image_id && ! is_wp_error( $image_id ) ) {
                                            $row[ $key ] = $image_id;
                                        } else {
                                            // Fallback to original value if download fails
                                            $row[ $key ] = $sub_value; 
                                        }
                                    }
                                    break;
                                    
                                case 'file':
                                    // Can be file URL or ID
                                    if ( is_numeric( $sub_value ) ) {
                                        $row[ $key ] = intval( $sub_value );
                                    } elseif ( filter_var( $sub_value, FILTER_VALIDATE_URL ) ) {
                                        $file_id = $this->insert_attachment_from_url( $sub_value, $post_id );
                                        if ( $file_id && ! is_wp_error( $file_id ) ) {
                                            $row[ $key ] = $file_id;
                                        } else {
                                            $row[ $key ] = $sub_value;
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
                        
                        $repeater_rows[] = $row;
                    }
                    
                    if ( ! empty( $repeater_rows ) ) {
                        update_field( $field['name'], $repeater_rows, $post_id );
                        error_log("PUK Import: Successfully imported repeater '{$field['name']}' with " . count($repeater_rows) . " rows");
                    } else {
                        error_log("PUK Import: No data found for repeater '{$field['name']}'");
                    }
                } else {
                    error_log("PUK Import: Invalid JSON data for repeater '{$field['name']}': $value");
                }
                break;
                
            case 'relationship':
                // Use field key for specific fields if required
                $selector = $field['name'];
                if ( $field['name'] === 'pd_alavlbl_select_product' ) {
                    $selector = 'field_6931191868540';
                }

                $items = [];
                // Try JSON decode first (e.g. '["Title 1", "Title 2"]' or encoded IDs)
                $decoded = json_decode( $value, true );
                if ( is_array( $decoded ) ) {
                    $items = $decoded;
                } else {
                    // Fallback to comma-separated string
                    $items = explode( ',', $value );
                }
                
                $items = array_map( 'trim', $items );
                $valid_ids = [];
                
                foreach ( $items as $item ) {
                    if ( empty( $item ) ) continue;
                    
                    if ( is_numeric( $item ) ) {
                        // Handle ID
                        $post_id_int = intval( $item );
                        if ( get_post_status( $post_id_int ) ) {
                            $valid_ids[] = $post_id_int;
                        } else {
                            // Try to look up by ID as title? Unlikely but possible if title is a number
                            $post_by_title = get_page_by_title( $item, OBJECT, $this->post_type );
                            if ( $post_by_title ) {
                                $valid_ids[] = $post_by_title->ID;
                            } else {
                                error_log( "Relationship field '{$field['name']}': Post ID $post_id_int not found." );
                            }
                        }
                    } else {
                        // Handle Title
                        // decode html entities just in case
                        $title = html_entity_decode( $item );
                        $post_by_title = get_page_by_title( $title, OBJECT, $this->post_type );
                        
                        if ( $post_by_title ) {
                            $valid_ids[] = $post_by_title->ID;
                        } else {
                            // Try deeper search if get_page_by_title fails (sometimes issues with exact match)
                            $found_post = get_posts([
                                'post_type' => $this->post_type,
                                'title' => $title,
                                'posts_per_page' => 1,
                                'post_status' => 'any',
                                'fields' => 'ids'
                            ]);
                            
                            if ( ! empty( $found_post ) ) {
                                $valid_ids[] = $found_post[0];
                            } else {
                                error_log( "Relationship field '{$field['name']}': Post with title '$title' not found." );
                            }
                        }
                    }
                }
                
                if ( ! empty( $valid_ids ) ) {
                    update_field( $selector, $valid_ids, $post_id );
                    error_log( "Relationship field '{$field['name']}': Successfully imported " . count($valid_ids) . " related posts" );
                } else {
                    error_log( "Relationship field '{$field['name']}': No valid related posts found for value: " . print_r($value, true) );
                }
                break;
                
            case 'gallery':
                // Handle comma-separated image IDs or URLs
                $items = array_map( 'trim', explode( ',', $value ) );
                $image_ids = [];
                
                foreach ( $items as $item ) {
                    if ( empty( $item ) ) continue;
                    
                    if ( is_numeric( $item ) ) {
                        // It's an ID
                        $image_ids[] = intval( $item );
                    } elseif ( filter_var( $item, FILTER_VALIDATE_URL ) ) {
                        // It's a URL - download or find existing
                        $image_id = $this->insert_image_from_url( $item, $post_id );
                        if ( $image_id && ! is_wp_error( $image_id ) ) {
                            $image_ids[] = $image_id;
                        }
                    }
                }
                
                if ( ! empty( $image_ids ) ) {
                    update_field( $field['name'], $image_ids, $post_id );
                }
                break;
                
            case 'image':
                // Expecting image ID
                if ( is_numeric( $value ) ) {
                    update_field( $field['name'], intval( $value ), $post_id );
                }
                break;
                
            case 'file':
                // Can be file URL or ID
                if ( is_numeric( $value ) ) {
                    update_field( $field['name'], intval( $value ), $post_id );
                } elseif ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
                    // If it's a URL, try to find or upload the file
                    $file_id = $this->insert_attachment_from_url( $value, $post_id );
                    if ( $file_id && ! is_wp_error( $file_id ) ) {
                        update_field( $field['name'], $file_id, $post_id );
                    }
                }
                break;
                
            case 'color_picker':
                // Validate hex color
                if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
                    update_field( $field['name'], $value, $post_id );
                }
                break;
                
            case 'wysiwyg':
                // Store HTML content with sanitization
                update_field( $field['name'], wp_kses_post( $value ), $post_id );
                break;
                
            case 'select':
                // Store select value as-is
                update_field( $field['name'], $value, $post_id );
                break;
                
            case 'text':
            case 'textarea':
            default:
                // Fix scientific notation for text fields
                $value = $this->fix_scientific_notation( $value );
                update_field( $field['name'], sanitize_text_field( $value ), $post_id );
                break;
        }
    }

    /**
     * Helper to handle hierarchical terms (Parent > Child).
     */
    private function set_hierarchical_terms( $post_id, $term_string, $taxonomy ) {
        $groups = explode( '|', $term_string );
        $term_ids = [];

        foreach ( $groups as $group ) {
            $levels = array_map( 'trim', explode( '>', $group ) );
            $parent_id = 0;
            $last_term_id = 0;

            foreach ( $levels as $level_name ) {
                if ( empty( $level_name ) ) continue;

                $term_id = 0;
                
                // Try to find the term with matching parent (matching working code in existing_import_export.php)
                $existing_term = get_term_by( 'name', $level_name, $taxonomy );

                if ( $existing_term && ( $parent_id === 0 || $existing_term->parent == $parent_id ) ) {
                    $term_id = $existing_term->term_id;
                } else {
                    // Create new term under current parent
                    $new_term = wp_insert_term( $level_name, $taxonomy, [ 'parent' => $parent_id ] );
                    if ( is_wp_error( $new_term ) ) {
                        error_log( "Import Error: Failed to create term '$level_name': " . $new_term->get_error_message() );
                    } else {
                        $term_id = $new_term['term_id'];
                    }
                }

                if ( $term_id ) {
                    $parent_id = $term_id;
                    $last_term_id = $term_id; // Track the last (deepest) term
                }
            }
            
            // Only assign the last (deepest) child in the hierarchy
            if ( $last_term_id ) {
                $term_ids[] = $last_term_id;
            }
        }
        
        $term_ids = array_unique( $term_ids );

        if ( ! empty( $term_ids ) ) {
            wp_set_object_terms( $post_id, $term_ids, $taxonomy );
        }
    }

    /**
     * Cleans the input string to preserve leading zeros and fix scientific notation.
     */
    private function fix_scientific_notation( $value ) {
        // 1. Remove the Excel formatting used to force text (e.g., from ="06875")
        $value = str_replace( [ '="', '"' ], '', $value );
        $value = trim( $value );

        // 2. Check if the value is in scientific notation (e.g., "6.875E+03")
        if ( is_numeric( $value ) && strpos( strtolower( $value ), 'e' ) !== false ) {
            return (string) sprintf( '%.20F', $value );
        }

        return $value;
    }

    /**
     * Helper function to insert an image from a URL.
     */
    private function insert_image_from_url( $url, $parent_post_id ) {
        // Look for an existing image with the same URL
        $existing_images = get_posts([
            'post_type'      => 'attachment',
            'meta_key'       => '_wp_attached_file',
            'meta_value'     => basename( $url ),
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);

        if ( ! empty( $existing_images ) ) {
            return $existing_images[0];
        }

        // If not found, download and attach
        // require_once( ABSPATH . 'wp-admin/includes/media.php' );
        // require_once( ABSPATH . 'wp-admin/includes/file.php' );
        // require_once( ABSPATH . 'wp-admin/includes/image.php' );

        return media_sideload_image( $url, $parent_post_id, null, 'id' );
    }

    /**
     * Helper function to insert a file attachment from a URL (for PDFs, documents, etc.)
     */
    private function insert_attachment_from_url( $url, $parent_post_id ) {
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
        $attachment_id = media_handle_sideload( $file_array, $parent_post_id );

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
     * Get post ID by _import_uid ACF field
     */
    private function get_post_by_import_uid( $import_uid ) {
        $args = [
            'post_type'      => $this->post_type,
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'meta_query'     => [
                [
                    'key'     => '_import_uid',
                    'value'   => $import_uid,
                    'compare' => '='
                ]
            ],
            'fields'         => 'ids',
        ];

        $posts = get_posts( $args );
        return ! empty( $posts ) ? $posts[0] : 0;
    }

}

// Initialize the class
new Puk_Product_Importer_Exporter();