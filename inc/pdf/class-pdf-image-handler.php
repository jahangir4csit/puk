<?php
/**
 * PDF Image Handler
 *
 * Handles image conversion to base64 for reliable PDF embedding.
 *
 * @package PUK
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class PUK_PDF_Image_Handler
 *
 * Converts image URLs to base64 data URIs for DOMPDF.
 */
class PUK_PDF_Image_Handler {

    /**
     * Cache for converted images
     *
     * @var array
     */
    private static $cache = array();

    /**
     * Convert image URL to base64 data URI
     *
     * @param string $url Image URL.
     * @param int    $max_width Optional max width for resizing.
     * @param int    $max_height Optional max height for resizing.
     * @return string Base64 data URI or empty string on failure.
     */
    public static function url_to_base64( $url, $max_width = 0, $max_height = 0 ) {
        if ( empty( $url ) ) {
            return '';
        }

        // Check cache
        $cache_key = md5( $url . $max_width . $max_height );
        if ( isset( self::$cache[ $cache_key ] ) ) {
            return self::$cache[ $cache_key ];
        }

        // Try to get local file path first
        $local_path = self::url_to_local_path( $url );

        if ( $local_path && file_exists( $local_path ) ) {
            $base64 = self::file_to_base64( $local_path, $max_width, $max_height );
        } else {
            // Fallback to remote fetch
            $base64 = self::fetch_remote_image( $url, $max_width, $max_height );
        }

        // Cache the result
        self::$cache[ $cache_key ] = $base64;

        return $base64;
    }

    /**
     * Convert local file to base64
     *
     * @param string $file_path Local file path.
     * @param int    $max_width Optional max width.
     * @param int    $max_height Optional max height.
     * @return string Base64 data URI.
     */
    public static function file_to_base64( $file_path, $max_width = 0, $max_height = 0 ) {
        if ( ! file_exists( $file_path ) ) {
            return '';
        }

        // Get mime type
        $mime_type = self::get_mime_type( $file_path );

        if ( ! $mime_type ) {
            return '';
        }

        if ( $mime_type === 'image/svg+xml' ) {
            $svg_content = file_get_contents( $file_path );
            if ( $svg_content ) {
                // Check for embedded base64 image (common in exported SVGs that are actually rasters)
                if ( preg_match( '/xlink:href="data:(image\/[^;]+);base64,([^"]+)"/', $svg_content, $matches ) || 
                     preg_match( '/href="data:(image\/[^;]+);base64,([^"]+)"/', $svg_content, $matches ) ) {
                    return 'data:' . $matches[1] . ';base64,' . $matches[2];
                }
            }
            $image_data = $svg_content;
        } else {
            // Resize if needed
            if ( $max_width > 0 || $max_height > 0 ) {
                $resized = self::resize_image( $file_path, $max_width, $max_height );
                if ( $resized ) {
                    $image_data = $resized;
                } else {
                    $image_data = file_get_contents( $file_path );
                }
            } else {
                $image_data = file_get_contents( $file_path );
            }
        }

        if ( ! $image_data ) {
            return '';
        }

        return 'data:' . $mime_type . ';base64,' . base64_encode( $image_data );
    }

    /**
     * Fetch remote image and convert to base64
     *
     * @param string $url Remote image URL.
     * @param int    $max_width Optional max width.
     * @param int    $max_height Optional max height.
     * @return string Base64 data URI.
     */
    private static function fetch_remote_image( $url, $max_width = 0, $max_height = 0 ) {
        // Use WordPress HTTP API
        $response = wp_remote_get( $url, array(
            'timeout'   => 30,
            'sslverify' => false,
        ) );

        if ( is_wp_error( $response ) ) {
            return '';
        }

        $body = wp_remote_retrieve_body( $response );
        $content_type = wp_remote_retrieve_header( $response, 'content-type' );

        if ( empty( $body ) ) {
            return '';
        }

        // Determine mime type
        if ( strpos( $content_type, 'image/' ) !== false ) {
            $mime_type = $content_type;
        } else {
            // Try to detect from content
            $finfo = new finfo( FILEINFO_MIME_TYPE );
            $mime_type = $finfo->buffer( $body );
        }

        if ( ! $mime_type || strpos( $mime_type, 'image/' ) === false ) {
            return '';
        }

        // Resize if needed
        if ( $max_width > 0 || $max_height > 0 ) {
            $resized = self::resize_image_from_string( $body, $max_width, $max_height );
            if ( $resized ) {
                $body = $resized;
            }
        }

        return 'data:' . $mime_type . ';base64,' . base64_encode( $body );
    }

    /**
     * Convert URL to local file path
     *
     * @param string $url Image URL.
     * @return string|false Local path or false.
     */
    public static function url_to_local_path( $url ) {
        if ( empty( $url ) ) {
            return false;
        }

        // Decode URL to handle encoded characters
        $url = rawurldecode( $url );

        // Get upload directory info
        $upload_dir = wp_upload_dir();

        // Normalize protocols for comparison (remove http: and https:)
        $normalized_url = preg_replace( '/^https?:/', '', $url );
        $normalized_baseurl = preg_replace( '/^https?:/', '', $upload_dir['baseurl'] );
        $normalized_siteurl = preg_replace( '/^https?:/', '', site_url() );

        // 1. Check if URL is from this site's uploads
        if ( strpos( $normalized_url, $normalized_baseurl ) !== false ) {
            $path = str_replace( $normalized_baseurl, $upload_dir['basedir'], $normalized_url );
            $path = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $path );
            if ( file_exists( $path ) ) {
                return $path;
            }
        }

        // 2. Check if URL is from site URL
        if ( strpos( $normalized_url, $normalized_siteurl ) !== false ) {
            $site_path = rtrim( ABSPATH, '/\\' );
            $path = str_replace( $normalized_siteurl, $site_path, $normalized_url );
            $path = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $path );
            if ( file_exists( $path ) ) {
                return $path;
            }
        }

        if ( strpos( $url, '/wp-content/' ) !== false ) {
            $parts = explode( '/wp-content/', $url );
            if ( isset( $parts[1] ) ) {
                $path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $parts[1] );
                if ( file_exists( $path ) ) {
                    return $path;
                }
            }
        }

        // 4. Try absolute path if it looks like one already
        if ( @file_exists( $url ) ) {
            return $url;
        }

        return false;
    }

    /**
     * Get mime type of file
     *
     * @param string $file_path File path.
     * @return string|false Mime type or false.
     */
    private static function get_mime_type( $file_path ) {
        $extension = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );

        $mime_types = array(
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'svg'  => 'image/svg+xml',
        );

        if ( isset( $mime_types[ $extension ] ) ) {
            return $mime_types[ $extension ];
        }

        // Fallback to finfo
        if ( function_exists( 'finfo_open' ) ) {
            $finfo = finfo_open( FILEINFO_MIME_TYPE );
            $mime = finfo_file( $finfo, $file_path );
            finfo_close( $finfo );
            return $mime;
        }

        return false;
    }

    /**
     * Resize image from file
     *
     * @param string $file_path File path.
     * @param int    $max_width Max width.
     * @param int    $max_height Max height.
     * @return string|false Resized image data or false.
     */
    private static function resize_image( $file_path, $max_width, $max_height ) {
        if ( ! function_exists( 'imagecreatefromstring' ) ) {
            return false;
        }

        $image_data = file_get_contents( $file_path );
        return self::resize_image_from_string( $image_data, $max_width, $max_height );
    }

    /**
     * Resize image from string data
     *
     * @param string $image_data Image binary data.
     * @param int    $max_width Max width.
     * @param int    $max_height Max height.
     * @return string|false Resized image data or false.
     */
    private static function resize_image_from_string( $image_data, $max_width, $max_height ) {
        if ( ! function_exists( 'imagecreatefromstring' ) ) {
            return false;
        }

        $source = @imagecreatefromstring( $image_data );

        if ( ! $source ) {
            return false;
        }

        $orig_width = imagesx( $source );
        $orig_height = imagesy( $source );

        // Calculate new dimensions
        $ratio = 1;

        if ( $max_width > 0 && $orig_width > $max_width ) {
            $ratio = min( $ratio, $max_width / $orig_width );
        }

        if ( $max_height > 0 && $orig_height > $max_height ) {
            $ratio = min( $ratio, $max_height / $orig_height );
        }

        // No resize needed
        if ( $ratio >= 1 ) {
            imagedestroy( $source );
            return false;
        }

        $new_width = (int) ( $orig_width * $ratio );
        $new_height = (int) ( $orig_height * $ratio );

        // Create resized image
        $resized = imagecreatetruecolor( $new_width, $new_height );

        // Preserve transparency for PNG
        imagealphablending( $resized, false );
        imagesavealpha( $resized, true );

        imagecopyresampled(
            $resized,
            $source,
            0, 0, 0, 0,
            $new_width, $new_height,
            $orig_width, $orig_height
        );

        // Output to string
        ob_start();
        imagepng( $resized, null, 6 );
        $output = ob_get_clean();

        imagedestroy( $source );
        imagedestroy( $resized );

        return $output;
    }

    /**
     * Process all images in product data array
     *
     * @param array $data Product data array.
     * @return array Product data with base64 images.
     */
    public static function process_product_images( $data ) {
        // Process main gallery
        if ( ! empty( $data['images']['main_gallery'] ) ) {
            foreach ( $data['images']['main_gallery'] as $key => $url ) {
                $data['images']['main_gallery_base64'][ $key ] = self::url_to_base64( $url, 800, 800 );
            }
        }

        // Process main image
        if ( ! empty( $data['images']['main_image'] ) ) {
            $data['images']['main_image_base64'] = self::url_to_base64( $data['images']['main_image'], 600, 600 );
        }

        // Process tech drawing
        if ( ! empty( $data['images']['tech_drawing'] ) ) {
            $data['images']['tech_drawing_base64'] = self::url_to_base64( $data['images']['tech_drawing'], 1000, 800 );
        }

        // Process sub gallery
        if ( ! empty( $data['images']['sub_gallery'] ) ) {
            foreach ( $data['images']['sub_gallery'] as $key => $url ) {
                $data['images']['sub_gallery_base64'][ $key ] = self::url_to_base64( $url, 400, 400 );
            }
        }

        // Process light distribution
        if ( ! empty( $data['images']['light_distribution'] ) ) {
            foreach ( $data['images']['light_distribution'] as $key => $url ) {
                $data['images']['light_distribution_base64'][ $key ] = self::url_to_base64( $url, 300, 300 );
            }
        }

        // Process feature icons
        if ( ! empty( $data['features'] ) ) {
            foreach ( $data['features'] as $key => $feature ) {
                if ( ! empty( $feature['icon_url'] ) ) {
                    $data['features'][ $key ]['icon_base64'] = self::url_to_base64( $feature['icon_url'], 100, 100 );
                }
            }
        }

        // Process accessory images
        foreach ( array( 'included', 'not_included' ) as $type ) {
            if ( ! empty( $data['accessories'][ $type ] ) ) {
                foreach ( $data['accessories'][ $type ] as $key => $accessory ) {
                    if ( ! empty( $accessory['image_url'] ) ) {
                        $data['accessories'][ $type ][ $key ]['image_base64'] = self::url_to_base64( $accessory['image_url'], 200, 200 );
                    }
                }
            }
        }

        // Process finish color icon
        if ( ! empty( $data['specifications']['finish_color']['icon_url'] ) ) {
            $data['specifications']['finish_color']['icon_base64'] = self::url_to_base64(
                $data['specifications']['finish_color']['icon_url'],
                50,
                50
            );
        }

        // Process also available color images
        if ( ! empty( $data['also_available'] ) ) {
            foreach ( $data['also_available'] as $key => $product ) {
                if ( ! empty( $product['color_img'] ) ) {
                    $data['also_available'][ $key ]['color_img_base64'] = self::url_to_base64( $product['color_img'], 50, 50 );
                }
            }
        }

        return $data;
    }

    /**
     * Get logo as base64
     *
     * @return string Base64 logo or empty string.
     */
    public static function get_logo_base64() {
        // Try to get custom logo
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        if ( $custom_logo_id ) {
            $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
            if ( $logo_url ) {
                return self::url_to_base64( $logo_url, 300, 100 );
            }
        }

        // Fallback to theme assets logo
        $theme_logo = get_template_directory() . '/assets/images/logo.png';
        if ( file_exists( $theme_logo ) ) {
            return self::file_to_base64( $theme_logo, 300, 100 );
        }

        return '';
    }

    /**
     * Clear image cache
     */
    public static function clear_cache() {
        self::$cache = array();
    }
}
