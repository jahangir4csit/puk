<?php
/**
 * Block Template: Art Origin
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$section_title = get_field( 'section_title' );
$groups        = get_field( 'groups' );

// Block preview placeholder in admin
if ( $is_preview && empty( $section_title ) && empty( $groups ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'Art Origin Block - Configure fields in the sidebar', 'puk' ) . '</p>';
    echo '</div>';
    return;
}

// Collect all items across all groups (for the image panel on the right)
$all_items = array();
if ( $groups ) {
    foreach ( $groups as $group ) {
        if ( ! empty( $group['items'] ) ) {
            foreach ( $group['items'] as $item ) {
                $all_items[] = $item;
            }
        }
    }
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?> origin-form-main">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-5 col-lg-4">
                <div class="title-box">
                    <?php if ( $section_title ) : ?>
                        <h3><?php echo esc_html( $section_title ); ?></h3>
                    <?php endif; ?>
                </div>

                <?php if ( $groups ) : ?>
                    <?php foreach ( $groups as $group ) : ?>
                        <div class="ul-group-parent">
                            <?php if ( ! empty( $group['group_title'] ) ) : ?>
                                <div class="grouptitle"><?php echo esc_html( $group['group_title'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $group['items'] ) ) : ?>
                                <ul>
                                    <?php foreach ( $group['items'] as $item ) : ?>
                                        <li>
                                            <a href="" id="<?php echo esc_attr( $item['item_id'] ); ?>">
                                                <?php echo esc_html( $item['item_label'] ); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-6 col-md-7 col-lg-8">
                <?php foreach ( $all_items as $item ) : ?>
                    <?php if ( ! empty( $item['item_image'] ) ) : ?>
                        <div class="image-box" id="<?php echo esc_attr( $item['item_id'] ); ?>-img">
                            <img
                                src="<?php echo esc_url( $item['item_image']['url'] ); ?>"
                                alt="<?php echo esc_attr( $item['item_image']['alt'] ?: $item['item_label'] ); ?>"
                            >
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
