<?php
/**
 * Block Template: Example Block
 * 
 * This is an example block template showing the standard structure
 * 
 * Available Variables:
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during AJAX preview.
 * @var int|string $post_id The post ID this block is saved to.
 * @var array $fields The ACF fields for this block.
 * @var string $block_id Unique block ID.
 * @var string $block_class Block classes including custom class names.
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields (already available as $fields variable)
$heading = get_field( 'heading' ) ?: __( 'Default Heading', 'puk' );
$description = get_field( 'description' );
$image = get_field( 'image' );
$items = get_field( 'items' );

// Block preview placeholder in admin
if ( $is_preview && empty( $heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Example Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?>">
    <div class="container">
        
        <?php if ( $heading ) : ?>
            <h2 class="block-heading"><?php echo esc_html( $heading ); ?></h2>
        <?php endif; ?>
        
        <?php if ( $description ) : ?>
            <div class="block-description">
                <?php echo wp_kses_post( wpautop( $description ) ); ?>
            </div>
        <?php endif; ?>
        
        <?php if ( $image ) : ?>
            <div class="block-image">
                <img 
                    src="<?php echo esc_url( $image['url'] ); ?>" 
                    alt="<?php echo esc_attr( $image['alt'] ?: $heading ); ?>"
                    width="<?php echo esc_attr( $image['width'] ); ?>"
                    height="<?php echo esc_attr( $image['height'] ); ?>"
                />
            </div>
        <?php endif; ?>
        
        <?php if ( $items ) : ?>
            <div class="block-items">
                <?php foreach ( $items as $item ) : ?>
                    <div class="block-item">
                        
                        <?php if ( ! empty( $item['icon'] ) ) : ?>
                            <div class="item-icon">
                                <img 
                                    src="<?php echo esc_url( $item['icon']['url'] ); ?>" 
                                    alt="<?php echo esc_attr( $item['icon']['alt'] ?: $item['title'] ); ?>"
                                />
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $item['title'] ) ) : ?>
                            <h3 class="item-title"><?php echo esc_html( $item['title'] ); ?></h3>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $item['description'] ) ) : ?>
                            <div class="item-description">
                                <?php echo wp_kses_post( wpautop( $item['description'] ) ); ?>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>
