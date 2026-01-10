# ACF Block Development - Quick Start Guide

## 🚀 Create a New Block in 3 Steps

### Step 1: Create Block Template
**File:** `acf-blocks/my-block.php`

```php
<?php
/**
 * Block Template: My Block
 * @package Puk
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Get fields
$heading = get_field( 'heading' );

// Preview
if ( $is_preview && empty( $heading ) ) {
    echo '<div style="padding:20px;background:#f0f0f0;">My Block Preview</div>';
    return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?>">
    <div class="container">
        <?php if ( $heading ) : ?>
            <h2><?php echo esc_html( $heading ); ?></h2>
        <?php endif; ?>
    </div>
</section>
```

### Step 2: Create ACF Fields
**File:** `acf-blocks/fields/my-block.php`

```php
<?php
/**
 * ACF Field Group: My Block
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

acf_add_local_field_group( array(
    'key' => 'group_my_block',
    'title' => __( 'My Block Fields', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_my_block_heading',
            'label' => __( 'Heading', 'puk' ),
            'name' => 'heading',
            'type' => 'text',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/my-block',
            ),
        ),
    ),
) );
```

### Step 3: Done! ✅
Your block is now available in the WordPress editor under **Puk** category.

---

## 📋 Common Field Types Cheatsheet

### Text
```php
array(
    'key' => 'field_text',
    'name' => 'text',
    'type' => 'text',
)
```

### Textarea
```php
array(
    'key' => 'field_textarea',
    'name' => 'textarea',
    'type' => 'textarea',
    'rows' => 4,
)
```

### Image
```php
array(
    'key' => 'field_image',
    'name' => 'image',
    'type' => 'image',
    'return_format' => 'array',
)
```

### Repeater
```php
array(
    'key' => 'field_items',
    'name' => 'items',
    'type' => 'repeater',
    'button_label' => 'Add Item',
    'sub_fields' => array(
        array(
            'key' => 'field_item_title',
            'name' => 'title',
            'type' => 'text',
        ),
    ),
)
```

### Link
```php
array(
    'key' => 'field_link',
    'name' => 'link',
    'type' => 'link',
    'return_format' => 'array',
)
```

### Select
```php
array(
    'key' => 'field_style',
    'name' => 'style',
    'type' => 'select',
    'choices' => array(
        'default' => 'Default',
        'dark' => 'Dark',
    ),
)
```

---

## 💡 Using Fields in Templates

### Text
```php
$text = get_field( 'text' );
echo esc_html( $text );
```

### Image
```php
$image = get_field( 'image' );
if ( $image ) {
    echo '<img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '">';
}
```

### Link
```php
$link = get_field( 'link' );
if ( $link ) {
    echo '<a href="' . esc_url( $link['url'] ) . '">' . esc_html( $link['title'] ) . '</a>';
}
```

### Repeater
```php
$items = get_field( 'items' );
if ( $items ) {
    foreach ( $items as $item ) {
        echo esc_html( $item['title'] );
    }
}
```

---

## 📁 File Structure

```
block-template/
├── fields/
│   └── my-block.php          ← ACF fields
├── my-block.php              ← Block template
└── README.md                 ← Full documentation
```

---

## ✅ Checklist for New Block

- [ ] Create `block-template/block-name.php`
- [ ] Create `block-template/fields/block-name.php`
- [ ] Match location value: `'value' => 'acf/block-name'`
- [ ] Use unique field keys: `field_blockname_fieldname`
- [ ] Add preview placeholder with `$is_preview`
- [ ] Escape all output: `esc_html()`, `esc_url()`, `esc_attr()`

---

## 🎯 Naming Convention

| Item | Format | Example |
|------|--------|---------|
| Block file | `block-name.php` | `hero-section.php` |
| Field file | `fields/block-name.php` | `fields/hero-section.php` |
| Block slug | `acf/block-name` | `acf/hero-section` |
| Field group key | `group_block_name` | `group_hero_section` |
| Field key | `field_block_field` | `field_hero_heading` |

---

**Category:** Puk  
**Textdomain:** puk

📖 See `README.md` for detailed documentation.
