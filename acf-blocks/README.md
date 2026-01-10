# ACF Block Development - Standard Structure

## Overview
This directory contains ACF (Advanced Custom Fields) block templates for the Puk theme.

**Block Category:** Puk  
**Textdomain:** puk

---

## 📁 Folder Structure

```
block-template/
├── fields/                          # ACF field group registration files
│   ├── example-block.php           # Example field group
│   └── your-block-name.php         # Your field groups
├── previews/                        # Block preview images (optional)
│   └── your-block-name.jpg
├── example-block.php               # Example block template
├── your-block-name.php             # Your block templates
└── README.md                       # This file
```

---

## 📝 Naming Conventions

### Raw HTML Files (Source)
- **Location:** `wp-content/themes/puk/block-template/`
- **Format:** `.html` or `.php` files with static HTML
- **Examples:**
  - `hero-section.html`
  - `consultancy_integrate.php`

### Block Template Files
- **Location:** `wp-content/themes/puk/acf-blocks/`
- **Format:** `your-block-name.php` (lowercase with hyphens or underscores)
- **Examples:**
  - `hero-section.php`
  - `testimonial_slider.php`

### ACF Field Group Files
- **Location:** `wp-content/themes/puk/acf-blocks/fields/`
- **Format:** Same name as block template file
- **Examples:**
  - `hero-section.php` → `fields/hero-section.php`
  - `testimonial_slider.php` → `fields/testimonial_slider.php`

### Block Registration Names
- Automatically generated from filename
- Hyphens/underscores converted to hyphens in block name
- **Example:** `consultancy_integrate.php` → Block name: `acf/consultancy-integrate`

---

## 🚀 How to Create a New Block

### Step 1: Create Block Template File
Create a new PHP file in `acf-blocks/` directory:

**File:** `acf-blocks/my-custom-block.php`

```php
<?php
/**
 * Block Template: My Custom Block
 * 
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$heading = get_field( 'heading' );
$description = get_field( 'description' );

// Preview placeholder
if ( $is_preview && empty( $heading ) ) {
    echo '<div style="padding: 20px; background: #f0f0f0;">';
    echo '<p>My Custom Block - Add content in sidebar</p>';
    echo '</div>';
    return;
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?>">
    <div class="container">
        <?php if ( $heading ) : ?>
            <h2><?php echo esc_html( $heading ); ?></h2>
        <?php endif; ?>
        
        <?php if ( $description ) : ?>
            <p><?php echo esc_html( $description ); ?></p>
        <?php endif; ?>
    </div>
</section>
```

### Step 2: Create ACF Field Group File
Create matching field file in `acf-blocks/fields/` directory:

**File:** `acf-blocks/fields/my-custom-block.php`

```php
<?php
/**
 * ACF Field Group: My Custom Block
 * 
 * @package Puk
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key' => 'group_my_custom_block',
    'title' => __( 'My Custom Block Fields', 'puk' ),
    'fields' => array(
        array(
            'key' => 'field_my_custom_heading',
            'label' => __( 'Heading', 'puk' ),
            'name' => 'heading',
            'type' => 'text',
            'required' => 0,
        ),
        array(
            'key' => 'field_my_custom_description',
            'label' => __( 'Description', 'puk' ),
            'name' => 'description',
            'type' => 'textarea',
            'required' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/my-custom-block',
            ),
        ),
    ),
) );
```

### Step 3: Done!
The block will be automatically registered and available in the WordPress block editor under the **Puk** category.

---

## 🔧 Available Variables in Block Templates

Each block template has access to these variables:

| Variable | Type | Description |
|----------|------|-------------|
| `$block` | array | Block settings and attributes |
| `$content` | string | Block inner HTML (usually empty) |
| `$is_preview` | bool | True during AJAX preview in editor |
| `$post_id` | int/string | The post ID |
| `$fields` | array | All ACF fields for the block |
| `$block_id` | string | Unique block ID (e.g., `block-123`) |
| `$block_class` | string | Block CSS classes including custom classes |

---

## 📋 Common ACF Field Types

### Text Field
```php
array(
    'key' => 'field_text',
    'label' => __( 'Text', 'puk' ),
    'name' => 'text',
    'type' => 'text',
    'placeholder' => __( 'Enter text...', 'puk' ),
)
```

### Textarea
```php
array(
    'key' => 'field_description',
    'label' => __( 'Description', 'puk' ),
    'name' => 'description',
    'type' => 'textarea',
    'rows' => 4,
)
```

### WYSIWYG Editor
```php
array(
    'key' => 'field_content',
    'label' => __( 'Content', 'puk' ),
    'name' => 'content',
    'type' => 'wysiwyg',
    'tabs' => 'all',
    'toolbar' => 'full',
    'media_upload' => 1,
)
```

### Image
```php
array(
    'key' => 'field_image',
    'label' => __( 'Image', 'puk' ),
    'name' => 'image',
    'type' => 'image',
    'return_format' => 'array',
    'preview_size' => 'medium',
)
```

### URL/Link
```php
array(
    'key' => 'field_link',
    'label' => __( 'Link', 'puk' ),
    'name' => 'link',
    'type' => 'link',
    'return_format' => 'array',
)
```

### Repeater
```php
array(
    'key' => 'field_items',
    'label' => __( 'Items', 'puk' ),
    'name' => 'items',
    'type' => 'repeater',
    'layout' => 'block',
    'button_label' => __( 'Add Item', 'puk' ),
    'sub_fields' => array(
        array(
            'key' => 'field_item_title',
            'label' => __( 'Title', 'puk' ),
            'name' => 'title',
            'type' => 'text',
        ),
    ),
)
```

### Select Dropdown
```php
array(
    'key' => 'field_style',
    'label' => __( 'Style', 'puk' ),
    'name' => 'style',
    'type' => 'select',
    'choices' => array(
        'default' => __( 'Default', 'puk' ),
        'dark' => __( 'Dark', 'puk' ),
        'light' => __( 'Light', 'puk' ),
    ),
    'default_value' => 'default',
)
```

### True/False Toggle
```php
array(
    'key' => 'field_show_button',
    'label' => __( 'Show Button', 'puk' ),
    'name' => 'show_button',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
)
```

---

## 🎨 Using Fields in Block Templates

### Simple Field
```php
$heading = get_field( 'heading' );
if ( $heading ) {
    echo '<h2>' . esc_html( $heading ) . '</h2>';
}
```

### Image Field
```php
$image = get_field( 'image' );
if ( $image ) {
    echo '<img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '">';
}
```

### Link Field
```php
$link = get_field( 'link' );
if ( $link ) {
    echo '<a href="' . esc_url( $link['url'] ) . '" target="' . esc_attr( $link['target'] ) . '">';
    echo esc_html( $link['title'] );
    echo '</a>';
}
```

### Repeater Field
```php
$items = get_field( 'items' );
if ( $items ) {
    foreach ( $items as $item ) {
        echo '<div>';
        echo '<h3>' . esc_html( $item['title'] ) . '</h3>';
        echo '<p>' . esc_html( $item['description'] ) . '</p>';
        echo '</div>';
    }
}
```

---

## ✅ Best Practices

1. **Always escape output:**
   - Use `esc_html()` for text
   - Use `esc_url()` for URLs
   - Use `esc_attr()` for attributes
   - Use `wp_kses_post()` for HTML content

2. **Check if field has value:**
   ```php
   if ( get_field( 'heading' ) ) {
       // Display field
   }
   ```

3. **Provide preview placeholder:**
   ```php
   if ( $is_preview && empty( $heading ) ) {
       echo '<div>Block Name - Add content</div>';
       return;
   }
   ```

4. **Use unique field keys:**
   - Format: `field_{blockname}_{fieldname}`
   - Example: `field_hero_heading`

5. **Use consistent naming:**
   - Block file: `hero-section.php`
   - Field file: `fields/hero-section.php`
   - Field group key: `group_hero_section`

---

## 🔍 Troubleshooting

### Block not appearing in editor?
- Check ACF Pro is installed and activated
- Verify block file is in `block-template/` directory
- Check file has `.php` extension

### Fields not showing?
- Verify field file is in `block-template/fields/` directory
- Check field group location rule matches block name
- Ensure `'value' => 'acf/your-block-name'` matches your block

### Block name format
- File: `my-block.php` → Block: `acf/my-block`
- File: `my_block.php` → Block: `acf/my-block`

---

## 📚 Additional Resources

- [ACF Documentation](https://www.advancedcustomfields.com/resources/)
- [ACF Block Registration](https://www.advancedcustomfields.com/resources/acf_register_block_type/)
- [ACF Field Types](https://www.advancedcustomfields.com/resources/#field-types)

---

**Last Updated:** 2025-12-27  
**Theme:** Puk  
**Textdomain:** puk
