<?php
/**
 * Product Data Collector for PDF Generation
 *
 * Collects all product data from ACF fields and WordPress
 * for use in PDF data sheet generation.
 *
 * @package PUK
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class PUK_Product_Data_Collector
 *
 * Handles collection of all product data for PDF generation.
 */
class PUK_Product_Data_Collector {

    /**
     * Product ID
     *
     * @var int
     */
    private $product_id;

    /**
     * Collected product data
     *
     * @var array
     */
    private $data = array();

    /**
     * Constructor
     *
     * @param int $product_id The product post ID.
     */
    public function __construct( $product_id ) {
        $this->product_id = absint( $product_id );
    }

    /**
     * Collect all product data
     *
     * @return array Complete product data array.
     */
    public function collect_all() {
        if ( ! $this->product_id || get_post_type( $this->product_id ) !== 'product' ) {
            return array();
        }

        $this->data = array(
            'basic'          => $this->get_basic_info(),
            'specifications' => $this->get_specifications(),
            'images'         => $this->get_images(),
            'features'       => $this->get_features(),
            'accessories'    => $this->get_accessories(),
            'remote_driver'  => $this->get_remote_driver_selection(),
            'also_available' => $this->get_also_available(),
        );

        return $this->data;
    }

    /**
     * Get basic product information
     *
     * @return array Basic product info.
     */
    public function get_basic_info() {
        $product_id = $this->product_id;

        // Get product family terms
        $terms = get_the_terms( $product_id, 'product-family' );
        $family_hierarchy = $this->get_family_hierarchy( $terms );

        // Get SKU
        $sku = get_field( 'prod__sku', $product_id );
        if ( empty( $sku ) ) {
            $sku = get_post_meta( $product_id, 'prod__sku', true );
        }

        // Get family code from parent term
        $family_code = '';
        if ( ! empty( $family_hierarchy['current_term'] ) ) {
            $family_code = get_field( 'family_code', 'product-family_' . $family_hierarchy['current_term']->term_id );
        }

        // Get designer info from ancestors[2] (family level) - matching product-title.php logic
        $designed_by = '';
        $ancestors = $family_hierarchy['ancestors'];

        // Try ancestors[2] first (family level)
        if ( ! empty( $ancestors[2] ) ) {
            $designed_by = get_field( 'pf_designed_by', 'product-family_' . $ancestors[2] );
        }
        // Fallback to ancestors[1] if not found
        if ( empty( $designed_by ) && ! empty( $ancestors[1] ) ) {
            $designed_by = get_field( 'pf_designed_by', 'product-family_' . $ancestors[1] );
        }
        // Fallback to ancestors[0] if still not found
        if ( empty( $designed_by ) && ! empty( $ancestors[0] ) ) {
            $designed_by = get_field( 'pf_designed_by', 'product-family_' . $ancestors[0] );
        }

        // Get description
        $description = '';
        if ( ! empty( $family_hierarchy['current_term'] ) ) {
            $description = $family_hierarchy['current_term']->description;
        }

        return array(
            'id'               => $product_id,
            'title'            => get_the_title( $product_id ),
            'sku'              => $sku,
            'family_code'      => $family_code,
            'designed_by'      => $designed_by,
            'description'      => $description,
            'permalink'        => get_permalink( $product_id ),
            'main_category'    => ! empty( $family_hierarchy['main_term'] ) ? $family_hierarchy['main_term']->name : '',
            'parent_family'    => ! empty( $family_hierarchy['parent_term'] ) ? $family_hierarchy['parent_term']->name : '',
            'current_family'   => ! empty( $family_hierarchy['current_term'] ) ? $family_hierarchy['current_term']->name : '',
            'family_hierarchy' => $family_hierarchy,
        );
    }

    /**
     * Get family hierarchy from terms
     *
     * @param array $terms Product family terms.
     * @return array Family hierarchy data.
     */
    private function get_family_hierarchy( $terms ) {
        $hierarchy = array(
            'main_term'       => null,
            'parent_term'     => null,
            'current_term'    => null,
            'parent_term_id'  => null,
            'ancestors'       => array(),
        );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return $hierarchy;
        }

        // Sort by term_id (ascending)
        usort( $terms, function( $a, $b ) {
            return $a->term_id - $b->term_id;
        });

        // Get the last term (most specific)
        $current_term = end( $terms );
        $hierarchy['current_term'] = $current_term;

        // Get ancestors
        $ancestors = get_ancestors( $current_term->term_id, 'product-family', 'taxonomy' );
        $hierarchy['ancestors'] = $ancestors;

        // Get parent term (level 2)
        if ( ! empty( $ancestors[0] ) ) {
            $hierarchy['parent_term_id'] = $ancestors[0];
            $hierarchy['parent_term'] = get_term( $ancestors[0], 'product-family' );
        }

        // Get main term (level 0 - root)
        if ( ! empty( $ancestors ) ) {
            $root_id = end( $ancestors );
            $hierarchy['main_term'] = get_term( $root_id, 'product-family' );
        }

        return $hierarchy;
    }

    /**
     * Get product specifications
     *
     * @return array Product specifications.
     */
    public function get_specifications() {
        $product_id = $this->product_id;

        $specs = array(
            'wattage'      => array(
                'label' => 'Wattage',
                'value' => get_field( 'pro_wattage', $product_id ),
            ),
            'cct'          => array(
                'label' => 'CCT',
                'value' => get_field( 'pro_cct', $product_id ),
            ),
            'beam_angle'   => array(
                'label' => 'Beam Angle',
                'value' => get_field( 'pro_beam_angle', $product_id ),
            ),
            'lumens'       => array(
                'label' => 'Lumens',
                'value' => get_field( 'pro_lumens', $product_id ),
            ),
            'finish_color' => $this->get_finish_color_data( $product_id ),
            'dimming'      => array(
                'label' => 'Dimming',
                'value' => get_field( 'pro_dimming', $product_id ),
            ),
            'ip_rating'    => array(
                'label' => 'IP Rating',
                'value' => get_field( 'pro_iprating', $product_id ),
            ),
            'ik_rating'    => array(
                'label' => 'IK Rating',
                'value' => get_field( 'pro_ikrating', $product_id ),
            ),
            'material'     => array(
                'label' => 'Material',
                'value' => get_field( 'pro_material', $product_id ),
            ),
            'coating'      => array(
                'label' => 'Coating',
                'value' => get_field( 'pro_coating', $product_id ),
            ),
            'light_source' => array(
                'label' => 'Light Source',
                'value' => get_field( 'pro_light_source', $product_id ),
            ),
            'screws'       => array(
                'label' => 'Screws',
                'value' => get_field( 'pro_screws', $product_id ),
            ),
            'transformer'  => array(
                'label' => 'Transformer',
                'value' => get_field( 'pro_transformer', $product_id ),
            ),
            'gasket'       => array(
                'label' => 'Gasket',
                'value' => get_field( 'pro_gasket', $product_id ),
            ),
            'glass'        => array(
                'label' => 'Glass',
                'value' => get_field( 'pro_glass', $product_id ),
            ),
            'cable_gland'  => array(
                'label' => 'Cable Gland',
                'value' => get_field( 'pro_cable_gland', $product_id ),
            ),
            'power_cable'  => array(
                'label' => 'Power Cable',
                'value' => get_field( 'pro_pwr_cble', $product_id ),
            ),
            'gross_weight' => array(
                'label' => 'Gross Weight',
                'value' => get_field( 'pro_grs_weight', $product_id ),
            ),
        );

        // Filter out empty values
        $filtered_specs = array();
        foreach ( $specs as $key => $spec ) {
            if ( ! empty( $spec['value'] ) ) {
                $filtered_specs[ $key ] = $spec;
            }
        }

        return $filtered_specs;
    }

    /**
     * Get finish color data
     *
     * @param int $product_id Product ID.
     * @return array Finish color data.
     */
    private function get_finish_color_data( $product_id ) {
        $finish_color_id = get_field( 'pro_finish_color', $product_id );

        if ( empty( $finish_color_id ) ) {
            return array(
                'label' => 'Finish',
                'value' => '',
            );
        }

        $term = get_term( $finish_color_id, 'finish-color' );

        if ( ! $term || is_wp_error( $term ) ) {
            return array(
                'label' => 'Finish',
                'value' => '',
            );
        }

        return array(
            'label'    => 'Finish',
            'value'    => $term->name,
            'icon_url' => $this->get_image_url_from_acf( $term_icon ),
        );
    }

    /**
     * Get image URL from ACF image field (robust handling)
     *
     * @param mixed $value ACF field value.
     * @return string Image URL.
     */
    private function get_image_url_from_acf( $value ) {
        if ( empty( $value ) ) {
            return '';
        }

        if ( is_array( $value ) && isset( $value['url'] ) ) {
            return $value['url'];
        }

        if ( is_numeric( $value ) ) {
            return wp_get_attachment_url( $value ) ?: '';
        }

        if ( is_string( $value ) ) {
            return $value;
        }

        return '';
    }

    /**
     * Get product images
     *
     * @return array Product images data.
     */
    public function get_images() {
        $product_id = $this->product_id;

        // Main gallery images (5-10)
        $main_gallery = array();
        for ( $i = 5; $i <= 10; $i++ ) {
            $image = get_field( 'prod_gallery_' . $i, $product_id );
            $url   = $this->get_image_url_from_acf( $image );
            if ( ! empty( $url ) ) {
                $main_gallery[] = $url;
            }
        }

        // Sub gallery images (11-20)
        $sub_gallery = array();
        for ( $i = 11; $i <= 20; $i++ ) {
            $image = get_field( 'prod_gallery_' . $i, $product_id );
            $url   = $this->get_image_url_from_acf( $image );
            if ( ! empty( $url ) ) {
                $sub_gallery[] = $url;
            }
        }

        // Get technical drawing from parent term
        $terms = get_the_terms( $product_id, 'product-family' );
        $tech_drawing = '';

        if ( $terms && ! is_wp_error( $terms ) ) {
            usort( $terms, function( $a, $b ) {
                return $a->term_id - $b->term_id;
            });
            $current_term = end( $terms );
            $tech_drawing = get_field( 'pf_subfam_tech_drawing', 'product-family_' . $current_term->term_id );
        }

        // Light distribution gallery
        $light_distribution = get_field( 'pro_lst_dstrbtn_glry', $product_id );

        return array(
            'main_image'         => ! empty( $main_gallery[0] ) ? $main_gallery[0] : '',
            'main_gallery'       => $main_gallery,
            'sub_gallery'        => $sub_gallery,
            'tech_drawing'       => $tech_drawing,
            'light_distribution' => $light_distribution ? $light_distribution : array(),
            'featured_image'     => get_the_post_thumbnail_url( $product_id, 'full' ),
        );
    }

    /**
     * Get product features
     *
     * @return array Features data.
     */
    public function get_features() {
        $product_id = $this->product_id;
        $features = array();

        // Get features from parent term
        $terms = get_the_terms( $product_id, 'product-family' );

        if ( $terms && ! is_wp_error( $terms ) ) {
            usort( $terms, function( $a, $b ) {
                return $a->term_id - $b->term_id;
            });
            $last_term = end( $terms );
            $parent_term_id = $last_term->term_id;

            $feature_ids = get_field( 'tax_sub_family_features', 'product-family_' . $parent_term_id );

            if ( ! empty( $feature_ids ) ) {
                foreach ( $feature_ids as $term_id ) {
                    $icon = get_field( 'tax_featured__icon', 'features_' . $term_id );
                    $term = get_term( $term_id, 'features' );

                    if ( $term && ! is_wp_error( $term ) ) {
                        $features[] = array(
                            'id'       => $term_id,
                            'name'     => $term->name,
                            'icon_url' => $this->get_image_url_from_acf( $icon ),
                        );
                    }
                }
            }
        }

        return $features;
    }

    /**
     * Get product accessories
     *
     * @return array Accessories data.
     */
    public function get_accessories() {
        $product_id = $this->product_id;
        $accessory_term_ids = get_field( 'prod_acc_in__terms', $product_id );

        $included = array();
        $not_included = array();

        if ( empty( $accessory_term_ids ) ) {
            return array(
                'included'     => $included,
                'not_included' => $not_included,
                'sku'          => get_field( 'prod__sku', $product_id ),
            );
        }

        foreach ( $accessory_term_ids as $term_id ) {
            $term = get_term( $term_id, 'accessories' );

            if ( ! $term || is_wp_error( $term ) ) {
                continue;
            }

            $is_featured = get_field( 'tax_acc_ft__type', 'accessories_' . $term_id );

            $image_url = $this->get_image_url_from_acf( get_field( 'tax_acc_ft__img', 'accessories_' . $term_id ) );

            $accessory_data = array(
                'term_id'      => $term_id,
                'title'        => $term->name,
                'description'  => $term->description,
                'code'         => get_field( 'tax_acc__code', 'accessories_' . $term_id ),
                'label'        => get_field( 'tax_acc_integ__label', 'accessories_' . $term_id ),
                'image_url'    => $image_url,
            );

            if ( $is_featured == 1 ) {
                $included[] = $accessory_data;
            } else {
                $not_included[] = $accessory_data;
            }
        }

        return array(
            'included'     => $included,
            'not_included' => $not_included,
            'sku'          => get_field( 'prod__sku', $product_id ),
        );
    }

    /**
     * Get remote driver selection data
     *
     * @return array Remote driver selection data.
     */
    public function get_remote_driver_selection() {
        $product_id = $this->product_id;
        $drivers = array();

        $remote_driver_data = get_field( 'pro_remote_drv_slctn', $product_id );

        if ( empty( $remote_driver_data ) ) {
            return $drivers;
        }

        foreach ( $remote_driver_data as $driver ) {
            $drivers[] = array(
                'meanwell' => isset( $driver['pro_remote_meanwell'] ) ? $driver['pro_remote_meanwell'] : '',
                'lpv'      => isset( $driver['pro_remote_lpv'] ) ? $driver['pro_remote_lpv'] : '',
                'volt'     => isset( $driver['pro_remote_volt'] ) ? $driver['pro_remote_volt'] : '',
                'watt'     => isset( $driver['pro_remote_watt'] ) ? $driver['pro_remote_watt'] : '',
                'ip'       => isset( $driver['pro_remote_ip'] ) ? $driver['pro_remote_ip'] : '',
                'min_max'  => isset( $driver['pro_remote_min_max'] ) ? $driver['pro_remote_min_max'] : '',
            );
        }

        return $drivers;
    }

    /**
     * Get also available products
     *
     * @return array Also available products data.
     */
    public function get_also_available() {
        $product_id = $this->product_id;
        $also_available = array();

        $product_ids = get_field( 'pd_alavlbl_select_product', $product_id );

        if ( empty( $product_ids ) ) {
            return $also_available;
        }

        foreach ( $product_ids as $related_product_id ) {
            $color_id = get_post_meta( $related_product_id, 'pro_finish_color', true );
            $color_term = null;
            $color_name = '';
            $color_img = '';

            if ( $color_id ) {
                $color_term = get_term( $color_id, 'finish-color' );
                if ( $color_term && ! is_wp_error( $color_term ) ) {
                    $color_name = $color_term->name;
                    $color_img = get_field( 'tax_finish_color__img', 'finish-color_' . $color_id );
                }
            }

            $also_available[] = array(
                'id'         => $related_product_id,
                'title'      => get_the_title( $related_product_id ),
                'sku'        => get_post_meta( $related_product_id, 'prod__sku', true ),
                'permalink'  => get_permalink( $related_product_id ),
                'color_name' => $color_name,
                'color_img'  => $this->get_image_url_from_acf( $color_img ),
            );
        }

        return $also_available;
    }

    /**
     * Get single data section
     *
     * @param string $section Section name.
     * @return array|null Section data or null.
     */
    public function get_section( $section ) {
        $method = 'get_' . $section;

        if ( method_exists( $this, $method ) ) {
            return $this->$method();
        }

        return null;
    }
}
