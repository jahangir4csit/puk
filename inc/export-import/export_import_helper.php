<?php
/**
 * Product Import/Export Helper
 * 
 * Handles CSV export and import for 'products' CPT and 'product-family' taxonomy.
 * 
 * @package puk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Puk_Product_Importer_Exporter {

    private $post_type = 'product';
    private $taxonomy  = 'product-family';
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
        
        // Handle Actions
        add_action( 'admin_init', [ $this, 'handle_export_request' ] );
        add_action( 'admin_init', [ $this, 'handle_import_request' ] );

        // AJAX Batch Actions
        add_action( 'wp_ajax_puk_get_export_count', [ $this, 'ajax_get_export_count' ] );
        add_action( 'wp_ajax_puk_export_products_batch', [ $this, 'ajax_export_products_batch' ] );
        add_action( 'wp_ajax_puk_import_products_batch', [ $this, 'ajax_import_products_batch' ] );
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
        $filename = 'product-export-' . date( 'Y-m-d' ) . '.csv';
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );

        // Open output stream
        $output = fopen( 'php://output', 'w' );
        
        // Add BOM for Excel compatibility
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        // Define CSV Headers in specific order
        $headers = [
            'SKU', // Unique identifier for import/update
            'Product Title',
            'Main category', // Level 0
            'Family', // Level 1
            'Family UID', // UID for Family (Level 1)
            'Sub Family', // Level 2
            'Sub Family UID', // UID for Sub Family (Level 2)
            'Sub Sub Family', // Level 3
            'Sub Sub Family UID', // UID for Sub Sub Family (Level 3)
            'Related Family', // Multiple family UIDs comma separated
        ];

        // Define field order for remaining ACF fields
        $ordered_field_names = [
            'pro_wattage',
            'pro_cct',
            'pro_beam_angle',
            'pro_lumens',
            'pro_finish_color',
            'pro_dimming',
            'pro_iprating',
            'pro_ikrating',
            'pro_material',
            'pro_coating',
            'pro_light_source',
            'pro_screws',
            'pro_transformer',
            'pro_gasket',
            'pro_glass',
            'pro_cable_gland',
            'pro_pwr_cble',
            'pro_grs_weight',
            'pro_mesr_img',
            'prod_acc_in__terms',
            'pro_remote_drv_slctn',
            'prod_gallery_5',
            'prod_gallery_6',
            'prod_gallery_7',
            'prod_gallery_8',
            'prod_gallery_9',
            'prod_gallery_10',
            'prod_gallery_11',
            'prod_gallery_12',
            'prod_gallery_13',
            'prod_gallery_14',
            'prod_gallery_15',
            'prod_gallery_16',
            'prod_gallery_17',
            'prod_gallery_18',
            'prod_gallery_19',
            'prod_gallery_20',
            'pd_alavlbl_select_product',
            'pro_dwnld_ltd_files',
            'pro_dwnld_instructions',
            'pro_dwnld_revit',
            'pro_dwnld_3dbim',
            'pro_dwnld_photometric',
            'pro_dwnld_provideo',
        ];

        // Add ACF field headers in the specified order
        foreach ( $ordered_field_names as $field_name ) {
            foreach ( $this->acf_fields as $field ) {
                if ( $field['name'] === $field_name && empty( $field['parent_repeater'] ) ) {
                    $headers[] = $field['label'];
                    break;
                }
            }
        }

        // Add Status as the last column
        $headers[] = 'Status';

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
                $row = $this->get_product_export_row( $post_id, $ordered_field_names );
                fputcsv( $output, $row );
            }
        }

        fclose( $output );
        exit();
    }

    /**
     * AJAX: Get total product count for export
     */
    public function ajax_get_export_count() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) error_log( 'PUK AJAX: ajax_get_export_count initiated' );
        
        // Try multiple nonce keys for robustness
        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce'] : ( isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '' );
        
        if ( ! wp_verify_nonce( $nonce, 'puk_export_nonce' ) ) {
            error_log( 'PUK AJAX: Nonce verification failed for export count (Nonce: ' . $nonce . ')' );
            wp_send_json_error( 'Security check failed' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            error_log( 'PUK AJAX: Permission denied for export count' );
            wp_send_json_error( 'Permission denied' );
        }

        $count = wp_count_posts( $this->post_type );
        $total = intval( $count->publish ) + intval( $count->draft ) + intval( $count->private ) + intval( $count->pending ) + intval( $count->future );
        
        $headers = [
            'SKU', 'Product Title',
            'Main category', 'Family', 'Family UID',
            'Sub Family', 'Sub Family UID',
            'Sub Sub Family', 'Sub Sub Family UID',
            'Related Family'
        ];
        $ordered_field_names = $this->get_ordered_field_names();
        foreach ( $ordered_field_names as $field_name ) {
            foreach ( $this->acf_fields as $field ) {
                if ( $field['name'] === $field_name && empty( $field['parent_repeater'] ) ) {
                    $headers[] = $field['label'];
                    break;
                }
            }
        }
        $headers[] = 'Status';

        wp_send_json_success( [ 'total' => $total, 'headers' => $headers ] );
    }

    /**
     * AJAX: Get a batch of products for export
     */
    public function ajax_export_products_batch() {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) error_log( 'PUK AJAX: ajax_export_products_batch initiated' );

        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce'] : ( isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '' );
        if ( ! wp_verify_nonce( $nonce, 'puk_export_nonce' ) ) {
            wp_send_json_error( 'Security check failed' );
        }
        
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Permission denied' );

        $offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;
        $posts_per_page = 20;

        $args = [
            'post_type'      => $this->post_type,
            'posts_per_page' => $posts_per_page,
            'offset'         => $offset,
            'post_status'    => 'any',
            'orderby'        => 'ID',
            'order'          => 'ASC'
        ];
        $query = new WP_Query( $args );
        $rows = [];
        $ordered_field_names = $this->get_ordered_field_names();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $rows[] = $this->get_product_export_row( get_the_ID(), $ordered_field_names );
            }
        }

        wp_send_json_success( [ 'rows' => $rows, 'count' => count( $rows ) ] );
    }

    /**
     * Get a single product row for export
     */
    private function get_product_export_row( $post_id, $ordered_field_names ) {
        $taxonomy_levels = $this->get_taxonomy_levels( $post_id );
        $row = [
            get_field( 'prod__sku', $post_id ), // SKU
            html_entity_decode( get_the_title( $post_id ) ), // Product Title
            $taxonomy_levels['main_category'], // Main category (Level 0)
            $taxonomy_levels['family'], // Family (Level 1)
            $taxonomy_levels['family_uid'], // Family UID (Level 1)
            $taxonomy_levels['sub_family'], // Sub Family (Level 2)
            $taxonomy_levels['sub_family_uid'], // Sub Family UID (Level 2)
            $taxonomy_levels['sub_sub_family'], // Sub Sub Family (Level 3)
            $taxonomy_levels['sub_sub_family_uid'], // Sub Sub Family UID (Level 3)
            $this->get_related_family_uids( $post_id ), // Related Family UIDs
        ];

        foreach ( $ordered_field_names as $field_name ) {
            foreach ( $this->acf_fields as $field ) {
                if ( $field['name'] === $field_name && empty( $field['parent_repeater'] ) ) {
                    $field_value = $this->get_acf_field_value( $post_id, $field );
                    if ( is_array( $field_value ) ) {
                        $field_value = json_encode( $field_value, JSON_UNESCAPED_UNICODE );
                    }
                    $row[] = $field_value;
                    break;
                }
            }
        }
        $row[] = get_post_status( $post_id );
        return $row;
    }

    /**
     * Get ordered field names constant list
     */
    private function get_ordered_field_names() {
        return [
            'pro_wattage', 'pro_cct', 'pro_beam_angle', 'pro_lumens', 'pro_finish_color',
            'pro_dimming', 'pro_iprating', 'pro_ikrating', 'pro_material', 'pro_coating',
            'pro_light_source', 'pro_screws', 'pro_transformer', 'pro_gasket', 'pro_glass',
            'pro_cable_gland', 'pro_pwr_cble', 'pro_grs_weight', 'pro_mesr_img',
            'prod_acc_in__terms',
            'pro_remote_drv_slctn',
            'prod_gallery_5', 'prod_gallery_6', 'prod_gallery_7', 'prod_gallery_8', 'prod_gallery_9',
            'prod_gallery_10', 'prod_gallery_11', 'prod_gallery_12', 'prod_gallery_13', 'prod_gallery_14', 'prod_gallery_15',
            'prod_gallery_16', 'prod_gallery_17', 'prod_gallery_18', 'prod_gallery_19', 'prod_gallery_20',
            'pd_alavlbl_select_product',
            'pro_dwnld_ltd_files', 'pro_dwnld_instructions', 'pro_dwnld_revit', 'pro_dwnld_3dbim',
            'pro_dwnld_photometric', 'pro_dwnld_provideo',
        ];
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
                // For prod_gallery_* fields, return URL instead of ID
                if ( strpos( $field['name'], 'prod_gallery_' ) === 0 ) {
                    if ( is_array( $value ) ) {
                        return isset( $value['url'] ) ? $value['url'] : '';
                    }
                    // If it's just an ID, get the URL
                    if ( is_numeric( $value ) ) {
                        return wp_get_attachment_url( $value );
                    }
                    return $value;
                }
                // Return image ID for other image fields
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

            case 'taxonomy':
                // Handle taxonomy fields - convert term ID to term name
                // Determine taxonomy based on field name
                $taxonomy = '';
                if ( $field['name'] === 'pro_finish_color' ) {
                    $taxonomy = 'finish-color';
                } elseif ( $field['name'] === 'pro_dimming' ) {
                    $taxonomy = 'features';
                } elseif ( $field['name'] === 'prod_acc_in__terms' ) {
                    $taxonomy = 'accessories';
                }

                if ( ! empty( $taxonomy ) ) {
                    // Value can be term ID or array of term IDs
                    if ( is_array( $value ) ) {
                        $term_values = [];
                        foreach ( $value as $term_id ) {
                            if ( is_numeric($term_id) ) {
                                $term = get_term( $term_id, $taxonomy );
                                if ( $term && ! is_wp_error( $term ) ) {
                                    // If it's accessories taxonomy, try to get the code
                                    $code = ($taxonomy === 'accessories') ? get_term_meta( $term->term_id, 'tax_acc__code', true ) : '';
                                    $term_values[] = $code ? $code : $term->name;
                                }
                            } else {
                                $term_values[] = $term_id;
                            }
                        }
                        return ! empty( $term_values ) ? implode( ', ', $term_values ) : '';
                    } elseif ( is_numeric($value) ) {
                        // Single term ID
                        $term = get_term( $value, $taxonomy );
                        if ( $term && ! is_wp_error( $term ) ) {
                            $code = ($taxonomy === 'accessories') ? get_term_meta( $term->term_id, 'tax_acc__code', true ) : '';
                            return $code ? $code : $term->name;
                        }
                    }
                }
                return $value;

            case 'true_false':
                // Return 1 for true, 0 for false
                return $value ? '1' : '0';

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
     * Get taxonomy levels as separate values (Main category, Family, Sub Family, Sub Sub Family)
     */
    private function get_taxonomy_levels( $post_id ) {
        $terms = get_the_terms( $post_id, $this->taxonomy );

        $result = [
            'main_category' => '',
            'family' => '',
            'family_uid' => '',
            'sub_family' => '',
            'sub_family_uid' => '',
            'sub_sub_family' => '',
            'sub_sub_family_uid' => '',
        ];

        if ( ! $terms || is_wp_error( $terms ) ) {
            return $result;
        }

        // Get the deepest term (most specific category)
        $deepest_term = null;
        $max_depth = -1;

        foreach ( $terms as $term ) {
            $depth = $this->get_term_depth( $term );
            if ( $depth > $max_depth ) {
                $max_depth = $depth;
                $deepest_term = $term;
            }
        }

        if ( ! $deepest_term ) {
            return $result;
        }

        // Build hierarchy array from deepest term to root
        $hierarchy = [];
        $hierarchy_terms = []; // Store term objects
        $current_term = $deepest_term;

        while ( $current_term ) {
            array_unshift( $hierarchy, $current_term->name );
            array_unshift( $hierarchy_terms, $current_term );

            if ( $current_term->parent ) {
                $current_term = get_term( $current_term->parent, $this->taxonomy );
            } else {
                break;
            }
        }

        // Assign to levels: 0 = Main category, 1 = Family, 2 = Sub Family, 3 = Sub Sub Family
        if ( isset( $hierarchy[0] ) ) {
            $result['main_category'] = $hierarchy[0];
        }
        if ( isset( $hierarchy[1] ) ) {
            $result['family'] = $hierarchy[1];
            if ( isset( $hierarchy_terms[1] ) ) {
                $result['family_uid'] = get_field( 'tax_family__uid', $this->taxonomy . '_' . $hierarchy_terms[1]->term_id ) ?: '';
            }
        }
        if ( isset( $hierarchy[2] ) ) {
            $result['sub_family'] = $hierarchy[2];
            if ( isset( $hierarchy_terms[2] ) ) {
                $result['sub_family_uid'] = get_field( 'tax_family__uid', $this->taxonomy . '_' . $hierarchy_terms[2]->term_id ) ?: '';
            }
        }
        if ( isset( $hierarchy[3] ) ) {
            $result['sub_sub_family'] = $hierarchy[3];
            if ( isset( $hierarchy_terms[3] ) ) {
                $result['sub_sub_family_uid'] = get_field( 'tax_family__uid', $this->taxonomy . '_' . $hierarchy_terms[3]->term_id ) ?: '';
            }
        }

        return $result;
    }

    /**
     * Get the depth level of a term (0 = root, 1 = child, 2 = grandchild)
     */
    private function get_term_depth( $term ) {
        $depth = 0;
        $current_term = $term;

        while ( $current_term && $current_term->parent ) {
            $depth++;
            $current_term = get_term( $current_term->parent, $this->taxonomy );
        }

        return $depth;
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
        
        // Verify 'product title' column exists immediately
        if ( ! in_array( 'product title', $headers ) && ! in_array( 'title', $headers ) ) {
            $msg = "CRITICAL ERROR: 'Product Title' column missing in CSV. Found headers: " . implode( ', ', $headers );
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
            
            // Combine headers with row data
            if ( count( $headers ) !== count( $row ) ) {
                $row = (count($row) < count($headers)) ? array_pad($row, count($headers), '') : array_slice($row, 0, count($headers));
            }
            $item = array_combine( $headers, $row );

            $this->process_import_row( $item, $row_count, $errors, $imported_count );
        }

        fclose( $handle );

        error_log( "Import completed: $imported_count products imported out of $row_count rows processed" );

        // Fire action for rewrite rules flush
        do_action( 'puk_product_import_complete', $imported_count );

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
     * AJAX: Batch import products from JSON payload
     */
    public function ajax_import_products_batch() {
        // Extend execution time for imports (especially with image downloads)
        @set_time_limit( 300 ); // 5 minutes per batch
        @ini_set( 'max_execution_time', 300 );

        // Increase memory limit if possible
        @ini_set( 'memory_limit', '512M' );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) error_log( 'PUK AJAX: ajax_import_products_batch initiated' );

        $nonce = isset( $_REQUEST['_ajax_nonce'] ) ? $_REQUEST['_ajax_nonce'] : ( isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '' );
        if ( ! wp_verify_nonce( $nonce, 'puk_import_nonce' ) ) {
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

        foreach ( $batch_data as $index => $item ) {
            $row_num = isset( $_POST['start_row'] ) ? intval( $_POST['start_row'] ) + $index : $index + 1;
            $this->process_import_row( $item, $row_num, $errors, $imported_count );
        }

        // Fire action for rewrite rules flush (batch import)
        do_action( 'puk_product_import_batch_complete', $imported_count );

        wp_send_json_success( [
            'imported' => $imported_count,
            'errors' => $errors,
            'error_count' => count( $errors )
        ] );
    }

    /**
     * Process a single row from the CSV/JSON import
     */
    private function process_import_row( $item, $row_count, &$errors, &$imported_count ) {
        // Prepare Post Data
        $sku = isset( $item['sku'] ) ? trim( $item['sku'] ) : ( isset( $item['prod__sku'] ) ? trim( $item['prod__sku'] ) : '' );
        $post_title = isset( $item['product title'] ) ? $item['product title'] : ( isset( $item['title'] ) ? $item['title'] : '' );

        // Check if post exists by SKU first
        $existing_post_id = 0;
        if ( ! empty( $sku ) ) {
            $existing_post_id = $this->get_post_by_sku( $sku );
        }

        // If updating existing product by SKU and no title provided, use existing title
        if ( $existing_post_id > 0 && $post_title === '' ) {
            $post_title = get_the_title( $existing_post_id );
            error_log( "Row $row_count: Using existing title '$post_title' for SKU '$sku'" );
        }

        // Allow "0" as a title, so use strict check for empty string
        // Only require title for NEW products
        if ( $post_title === '' && $existing_post_id === 0 ) {
            $msg = "Row $row_count: Product Title is required for new products (SKU: '$sku').";
            error_log( $msg );
            $errors[] = $msg;
            return false;
        }

        // If we have an existing post, just update ACF fields without touching post data
        if ( $existing_post_id > 0 ) {
            $new_post_id = $existing_post_id;

            // Only update post if title or status is explicitly provided
            if ( isset( $item['product title'] ) || isset( $item['title'] ) || isset( $item['status'] ) ) {
                $post_args = [
                    'ID'           => $existing_post_id,
                    'post_type'    => $this->post_type,
                ];

                if ( $post_title !== '' ) {
                    $post_args['post_title'] = $post_title;
                }
                if ( isset( $item['status'] ) && ! empty( $item['status'] ) ) {
                    $post_args['post_status'] = $item['status'];
                }

                wp_update_post( $post_args, true );
            }

            error_log( "Row $row_count: Updating existing product ID $existing_post_id (SKU: '$sku')" );
        } else {
            // Insert new post
            $post_args = [
                'post_type'    => $this->post_type,
                'post_status'  => isset( $item['status'] ) ? $item['status'] : 'publish',
                'post_title'   => $post_title,
            ];
            $new_post_id = wp_insert_post( $post_args, true );
        }

        if ( is_wp_error( $new_post_id ) ) {
            $msg = "Row $row_count: Post creation failed - " . $new_post_id->get_error_message();
            error_log( $msg );
            $errors[] = $msg;
            return false;
        }
        
        // Log if SKU was provided
        if ( ! empty( $sku ) ) {
            update_field( 'prod__sku', $sku, $new_post_id );
        }

        // --- Handle Taxonomy: Product Family (Hierarchical - 4 levels) ---
        $main_category = isset( $item['main category'] ) ? trim( $item['main category'] ) : '';
        $family = isset( $item['family'] ) ? trim( $item['family'] ) : '';
        $family_uid = isset( $item['family uid'] ) ? trim( $item['family uid'] ) : '';
        $sub_family = isset( $item['sub family'] ) ? trim( $item['sub family'] ) : '';
        $sub_family_uid = isset( $item['sub family uid'] ) ? trim( $item['sub family uid'] ) : '';
        $sub_sub_family = isset( $item['sub sub family'] ) ? trim( $item['sub sub family'] ) : '';
        $sub_sub_family_uid = isset( $item['sub sub family uid'] ) ? trim( $item['sub sub family uid'] ) : '';
        $related_family = isset( $item['related family'] ) ? trim( $item['related family'] ) : '';

        if ( ! empty( $family_uid ) || ! empty( $sub_family_uid ) || ! empty( $sub_sub_family_uid ) || ! empty( $main_category ) || ! empty( $family ) || ! empty( $sub_family ) || ! empty( $sub_sub_family ) ) {
            $this->set_taxonomy_by_levels( $new_post_id, $main_category, $family, $family_uid, $sub_family, $sub_family_uid, $sub_sub_family, $sub_sub_family_uid, $this->taxonomy );
        } elseif ( ! empty( $item['product family'] ) ) {
            $this->set_hierarchical_terms( $new_post_id, $item['product family'], $this->taxonomy );
        }

        // --- Handle Related Family ---
        if ( isset( $item['related family'] ) ) {
            $this->set_related_family_terms( $new_post_id, $related_family );
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
            if ( isset( $item[ $column_key ] ) ) {
                $this->set_acf_field_value( $new_post_id, $field, $item[ $column_key ] );
            }
        }

        $imported_count++;
        return true;
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
                // For prod_gallery_* fields, handle URLs
                if ( strpos( $field['name'], 'prod_gallery_' ) === 0 ) {
                    if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
                        // It's a URL - download or find existing
                        $image_id = $this->insert_image_from_url( $value, $post_id );
                        if ( $image_id && ! is_wp_error( $image_id ) ) {
                            update_field( $field['name'], $image_id, $post_id );
                        }
                    } elseif ( is_numeric( $value ) ) {
                        update_field( $field['name'], intval( $value ), $post_id );
                    }
                } elseif ( is_numeric( $value ) ) {
                    // Other image fields - expecting image ID
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

            case 'taxonomy':
                // Handle taxonomy fields - convert term name to term ID
                // Determine taxonomy based on field name
                $taxonomy = '';
                if ( $field['name'] === 'pro_finish_color' ) {
                    $taxonomy = 'finish-color';
                } elseif ( $field['name'] === 'pro_dimming' ) {
                    $taxonomy = 'features';
                } elseif ( $field['name'] === 'prod_acc_in__terms' ) {
                    $taxonomy = 'accessories';
                }

                if ( ! empty( $taxonomy ) ) {
                    // Value can be term name or comma-separated term names
                    $term_names = array_map( 'trim', explode( ',', $value ) );
                    $term_ids = [];

                    foreach ( $term_names as $term_name ) {
                        if ( empty( $term_name ) ) continue;

                        // Try to find term by name
                        $term = get_term_by( 'name', $term_name, $taxonomy );

                        // If not found by name, try slug
                        if ( ! $term ) {
                            $term = get_term_by( 'slug', sanitize_title( $term_name ), $taxonomy );
                        }

                        // If not found by name, try finding by code (tax_acc__code) for accessories
                        if ( ! $term && $taxonomy === 'accessories' ) {
                            $term = $this->get_term_by_code( $term_name, $taxonomy );
                        }

                        // If still not found, create the term (for finish-color and features taxonomies)
                        if ( ! $term && in_array( $taxonomy, [ 'finish-color', 'features' ] ) ) {
                            $new_term = wp_insert_term( $term_name, $taxonomy );
                            if ( ! is_wp_error( $new_term ) ) {
                                $term = get_term( $new_term['term_id'], $taxonomy );
                                error_log( "Taxonomy import: Created new term '$term_name' (ID: {$term->term_id}) in taxonomy '$taxonomy'" );
                            } else {
                                error_log( "Taxonomy import: Failed to create term '$term_name' in taxonomy '$taxonomy': " . $new_term->get_error_message() );
                            }
                        }

                        if ( $term ) {
                            $term_ids[] = $term->term_id;
                        } else {
                            error_log( "Taxonomy import: Term '$term_name' not found in taxonomy '$taxonomy' for field '{$field['name']}'" );
                        }
                    }

                    if ( ! empty( $term_ids ) ) {
                        // Store single ID if only one, otherwise array
                        $value_to_store = count( $term_ids ) === 1 ? $term_ids[0] : $term_ids;

                        // Use update_field for ACF
                        $result = update_field( $field['name'], $value_to_store, $post_id );

                        if ( $result ) {
                            error_log( "Taxonomy import: Successfully imported term(s) for '{$field['name']}' on post $post_id: " . print_r( $value_to_store, true ) );
                        } else {
                            // Fallback: try updating post meta directly
                            update_post_meta( $post_id, $field['name'], $value_to_store );
                            error_log( "Taxonomy import: Used fallback update_post_meta for '{$field['name']}' on post $post_id: " . print_r( $value_to_store, true ) );
                        }

                        // Also set the actual taxonomy terms on the post for finish-color
                        if ( $taxonomy === 'finish-color' ) {
                            wp_set_object_terms( $post_id, $term_ids, $taxonomy, false );
                            error_log( "Taxonomy import: Also assigned terms to post taxonomy for '$taxonomy' on post $post_id" );
                        }
                    }
                }
                break;

            case 'true_false':
                // Handle true/false values - accept 1/0, true/false, yes/no
                $bool_value = false;
                if ( in_array( strtolower( $value ), [ '1', 'true', 'yes' ] ) ) {
                    $bool_value = true;
                }
                update_field( $field['name'], $bool_value, $post_id );
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
     * Set taxonomy terms by individual levels (Main category, Family, Sub Family, Sub Sub Family)
     * Uses the deepest non-empty UID for direct assignment, falls back to name-based matching
     */
    private function set_taxonomy_by_levels( $post_id, $main_category, $family, $family_uid, $sub_family, $sub_family_uid, $sub_sub_family, $sub_sub_family_uid, $taxonomy ) {
        $parent_id = 0;
        $final_term_id = 0;

        // 1. Resolve Level 0 (Main Category) - Strictly by Name
        if ( ! empty( $main_category ) ) {
            $term_l0 = $this->get_or_create_term_by_name( $main_category, $taxonomy, 0 );
            if ( $term_l0 ) {
                $parent_id = $term_l0->term_id;
                $final_term_id = $term_l0->term_id;
            }
        }

        if ( $parent_id === 0 ) {
            error_log( "Import Error: Main Category is required to assign product to family." );
            return;
        }

        // 2. Determine the deepest UID to use (priority: sub_sub_family_uid > sub_family_uid > family_uid)
        $target_uid = '';
        if ( ! empty( $sub_sub_family_uid ) ) {
            $target_uid = $sub_sub_family_uid;
        } elseif ( ! empty( $sub_family_uid ) ) {
            $target_uid = $sub_family_uid;
        } elseif ( ! empty( $family_uid ) ) {
            $target_uid = $family_uid;
        }

        // 3. If we have a target UID, find term directly by UID and assign
        if ( ! empty( $target_uid ) ) {
            $term_by_uid = $this->get_term_by_uid( $target_uid, $taxonomy );
            if ( $term_by_uid ) {
                $final_term_id = $term_by_uid->term_id;
                wp_set_object_terms( $post_id, [ $final_term_id ], $taxonomy );
                return;
            }
            // UID not found - fall through to name-based matching
            error_log( "Import Warning: UID '$target_uid' not found, falling back to name-based matching for post $post_id" );
        }

        // 4. FALLBACK: Name-based matching level by level with individual UIDs
        // Level 1: Family
        if ( ! empty( $family ) ) {
            $term_l1 = $this->get_or_create_term_hybrid( $family, $taxonomy, $parent_id, $family_uid );
            if ( $term_l1 ) {
                $parent_id = $term_l1->term_id;
                $final_term_id = $term_l1->term_id;

                // Level 2: Sub Family
                if ( ! empty( $sub_family ) ) {
                    $term_l2 = $this->get_or_create_term_hybrid( $sub_family, $taxonomy, $parent_id, $sub_family_uid );
                    if ( $term_l2 ) {
                        $parent_id = $term_l2->term_id;
                        $final_term_id = $term_l2->term_id;

                        // Level 3: Sub Sub Family
                        if ( ! empty( $sub_sub_family ) ) {
                            $term_l3 = $this->get_or_create_term_hybrid( $sub_sub_family, $taxonomy, $parent_id, $sub_sub_family_uid );
                            if ( $term_l3 ) {
                                $final_term_id = $term_l3->term_id;
                            }
                        }
                    }
                }
            }
        }

        // Assign the deepest term to the post
        if ( $final_term_id > 0 ) {
            wp_set_object_terms( $post_id, [ $final_term_id ], $taxonomy );
        }
    }

    /**
     * Get term by UID (tax_family__uid)
     */
    private function get_term_by_uid( $uid, $taxonomy ) {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
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
            return $terms[0];
        }

        return null;
    }

    /**
     * Resolve Level 0 term by Name
     */
    private function get_or_create_term_by_name( $name, $taxonomy, $parent_id ) {
        $term = get_term_by( 'name', $name, $taxonomy );
        if ( $term && ( $term->parent != 0 ) ) $term = null;

        if ( $term ) {
            // Ensure UID exists for Main Category even if matched by name
            $existing_uid = get_field( 'tax_family__uid', $taxonomy . '_' . $term->term_id );
            if ( empty( $existing_uid ) && function_exists( 'puk_generate_unique_family_uid' ) ) {
                $new_uid = puk_generate_unique_family_uid();
                update_field( 'tax_family__uid', $new_uid, $taxonomy . '_' . $term->term_id );
            }
            return $term;
        }

        $result = wp_insert_term( $name, $taxonomy, [ 'parent' => 0 ] );
        if ( is_wp_error( $result ) ) return null;
        $term_id = $result['term_id'];

        // Generate UID for new Main Category
        if ( function_exists( 'puk_generate_unique_family_uid' ) ) {
            $new_uid = puk_generate_unique_family_uid();
            update_field( 'tax_family__uid', $new_uid, $taxonomy . '_' . $term_id );
        }

        return get_term( $term_id, $taxonomy );
    }

    /**
     * Resolve Level 1+ term by UID
     */
    private function get_or_create_term_by_uid( $name, $taxonomy, $parent_id, $uid ) {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
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
            wp_update_term( $term->term_id, $taxonomy, [ 'name' => $name, 'parent' => $parent_id ] );
            return $term;
        }

        $result = wp_insert_term( $name, $taxonomy, [ 'parent' => $parent_id, 'slug' => sanitize_title( $name . '-' . ( ! empty( $uid ) ? $uid : time() ) ) ] );
        if ( is_wp_error( $result ) ) return null;
        $term_id = $result['term_id'];

        // If UID is empty, generate one
        if ( empty( $uid ) && function_exists( 'puk_generate_unique_family_uid' ) ) {
            $uid = puk_generate_unique_family_uid();
        }

        if ( ! empty( $uid ) ) {
            update_field( 'tax_family__uid', $uid, $taxonomy . '_' . $term_id );
        }

        return get_term( $term_id, $taxonomy );
    }

    /**
     * Hybrid resolver for Level 1+
     */
    private function get_or_create_term_hybrid( $name, $taxonomy, $parent_id, $uid ) {
        if ( ! empty( $uid ) ) return $this->get_or_create_term_by_uid( $name, $taxonomy, $parent_id, $uid );

        $term = get_term_by( 'name', $name, $taxonomy );
        if ( $term && $term->parent != $parent_id ) $term = null;

        if ( $term ) {
            // Ensure UID exists for hybrid matches
            $existing_uid = get_field( 'tax_family__uid', $taxonomy . '_' . $term->term_id );
            if ( empty( $existing_uid ) && function_exists( 'puk_generate_unique_family_uid' ) ) {
                $new_uid = puk_generate_unique_family_uid();
                update_field( 'tax_family__uid', $new_uid, $taxonomy . '_' . $term->term_id );
            }
            return $term;
        }

        $result = wp_insert_term( $name, $taxonomy, [ 'parent' => $parent_id ] );
        if ( is_wp_error( $result ) ) return null;
        $term_id = $result['term_id'];

        // Generate UID for new term
        if ( function_exists( 'puk_generate_unique_family_uid' ) ) {
            $new_uid = puk_generate_unique_family_uid();
            update_field( 'tax_family__uid', $new_uid, $taxonomy . '_' . $term_id );
        }

        return get_term( $term_id, $taxonomy );
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
     * Get post ID by prod__sku ACF field
     */
    private function get_post_by_sku( $sku ) {
        $args = [
            'post_type'      => $this->post_type,
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'meta_query'     => [
                [
                    'key'     => 'prod__sku',
                    'value'   => $sku,
                    'compare' => '='
                ]
            ],
            'fields'         => 'ids',
        ];

        $posts = get_posts( $args );
        return ! empty( $posts ) ? $posts[0] : 0;
    }

    /**
     * Get comma-separated UIDs of related families for export
     */
    private function get_related_family_uids( $post_id ) {
        $terms = get_field( 'prod_related_fam__terms', $post_id );
        if ( ! is_array( $terms ) || empty( $terms ) ) {
            return '';
        }

        $uids = [];
        foreach ( $terms as $term_id ) {
            $uid = get_field( 'tax_family__uid', $this->taxonomy . '_' . $term_id );
            if ( $uid ) {
                $uids[] = $uid;
            }
        }

        return implode( ',', $uids );
    }

    /**
     * Set related family terms from comma-separated UIDs during import
     */
    private function set_related_family_terms( $post_id, $uid_string ) {
        if ( empty( $uid_string ) ) {
            update_field( 'prod_related_fam__terms', [], $post_id );
            return;
        }

        $uids = array_map( 'trim', explode( ',', $uid_string ) );
        $term_ids = [];

        foreach ( $uids as $uid ) {
            if ( empty( $uid ) ) continue;

            $terms = get_terms([
                'taxonomy'   => $this->taxonomy,
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'     => 'tax_family__uid',
                        'value'   => $uid,
                        'compare' => '='
                    ]
                ],
                'fields' => 'ids'
            ]);

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $term_ids[] = intval( $terms[0] );
            } else {
                error_log( "Import: Related Family UID '$uid' not found in taxonomy '{$this->taxonomy}'" );
            }
        }

        update_field( 'prod_related_fam__terms', $term_ids, $post_id );
        if ( ! empty( $term_ids ) ) {
            error_log( "Import: Set " . count( $term_ids ) . " related family terms for post $post_id" );
        }
    }

    /**
     * Get term by tax_acc__code meta field
     */
    private function get_term_by_code( $code, $taxonomy ) {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'meta_query' => [
                [
                    'key'     => 'tax_acc__code',
                    'value'   => $code,
                    'compare' => '='
                ]
            ]
        ]);

        return ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0] : false;
    }

}

// Initialize the class
new Puk_Product_Importer_Exporter();