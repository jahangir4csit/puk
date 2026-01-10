# ACF Block Development - Structure Overview

## 📁 Complete Directory Structure

```
wp-content/themes/puk/
├── inc/
│   └── acf-blocks-builder.php          # Auto-registers all blocks
│
├── block-template/                     # Raw HTML source files (not blocks)
│   ├── consultancy_integrate.php      # Raw HTML sections
│   └── README.md                      # Purpose and workflow
│
└── acf-blocks/                        # Actual ACF blocks directory
    ├── fields/                        # ACF field group registrations
    │   ├── example-block.php         # Example field group
    │   └── your-block-name.php       # Your field groups here
    │
    ├── previews/                      # Block preview images (optional)
    │
    ├── example-block.php             # Example block template
    ├── your-block-name.php           # Your block templates here
    │
    ├── README.md                     # Full documentation
    ├── QUICK-START.md                # Quick reference guide
    └── STRUCTURE.md                  # This file
```

---

## 🔄 How It Works

### 1. Raw HTML Storage
[`block-template/`](../block-template/README.md)
- Stores raw HTML sections
- Source files for conversion
- Not registered as blocks

### 2. Auto-Registration
[`inc/acf-blocks-builder.php`](../inc/acf-blocks-builder.php:1)
- Scans `acf-blocks/` directory
- Registers all `.php` files as ACF blocks
- Assigns them to **Puk** category
- Loads field groups from `acf-blocks/fields/`

### 3. Block Templates
`acf-blocks/*.php`
- Each file = one block
- Filename becomes block slug
- Contains HTML structure with ACF field integration

### 4. Field Groups
`acf-blocks/fields/*.php`
- Each file defines ACF fields for one block
- Must match block template filename
- Uses `acf_add_local_field_group()`

---

## 🎯 File Naming Pattern

**For a block called "Hero Section":**

| File Type | Path | Filename |
|-----------|------|----------|
| Raw HTML | `block-template/` | `hero-section.html` or `.php` |
| Block Template | `acf-blocks/` | `hero-section.php` |
| Field Group | `acf-blocks/fields/` | `hero-section.php` |
| Preview Image | `acf-blocks/previews/` | `hero-section.jpg` |

**Block becomes:** `acf/hero-section` in WordPress

---

## 📝 Creating Your First Block

### 1. Copy Example Template
```bash
cp acf-blocks/example-block.php acf-blocks/my-block.php
```

### 2. Copy Example Fields
```bash
cp acf-blocks/fields/example-block.php acf-blocks/fields/my-block.php
```

### 3. Edit Both Files
- Update field keys to be unique
- Update location rule: `'value' => 'acf/my-block'`
- Modify HTML structure as needed
- Add/remove fields as required

### 4. Block Auto-Appears
- Refresh WordPress admin
- Block appears under **Puk** category
- Ready to use in editor!

---

## 🔑 Key Configuration

### Block Category
**Set in:** [`inc/acf-blocks-builder.php`](../inc/acf-blocks-builder.php:14)
```php
'slug'  => 'puk',
'title' => __( 'Puk', 'puk' ),
```

### Textdomain
**Used throughout:** `'puk'`
```php
__( 'Text', 'puk' )
```

### Block Directory
**Set in:** [`inc/acf-blocks-builder.php`](../inc/acf-blocks-builder.php:37)
```php
$block_dir = get_template_directory() . '/acf-blocks/';
```

### Fields Directory
**Set in:** [`inc/acf-blocks-builder.php`](../inc/acf-blocks-builder.php:107)
```php
$fields_dir = get_template_directory() . '/acf-blocks/fields/';
```

---

## 🛠️ Available in Block Templates

### Auto-Injected Variables
```php
$block          // Block settings and attributes
$content        // Block inner HTML
$is_preview     // True in editor preview
$post_id        // Current post ID
$fields         // All ACF fields array
$block_id       // Unique ID (block-123)
$block_class    // CSS classes string
```

### Helper Functions
```php
get_field( 'field_name' )              // Get field value
get_sub_field( 'field_name' )          // Get sub field (in repeater)
have_rows( 'repeater_name' )           // Check repeater has rows
the_row()                              // Loop repeater rows
```

---

## ✅ Quality Checklist

When creating a new block, ensure:

- [ ] Raw HTML saved in `block-template/` (optional)
- [ ] Block file created in `acf-blocks/`
- [ ] Field file created in `acf-blocks/fields/`
- [ ] Filenames match exactly (except directory)
- [ ] All field keys are unique (`field_blockname_fieldname`)
- [ ] Location rule matches block: `'value' => 'acf/block-name'`
- [ ] Preview placeholder added for `$is_preview`
- [ ] All output is escaped properly
- [ ] Textdomain is `'puk'` throughout
- [ ] Comments added for clarity

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| [`README.md`](./README.md) | Complete documentation with examples |
| [`QUICK-START.md`](./QUICK-START.md) | Quick reference for common tasks |
| [`STRUCTURE.md`](./STRUCTURE.md) | This file - structure overview |
| [`example-block.php`](./example-block.php) | Example block template |
| [`fields/example-block.php`](./fields/example-block.php) | Example field group |

---

## 🚀 Next Steps

1. **Review Examples**
   - Study [`example-block.php`](./example-block.php)
   - Check [`fields/example-block.php`](./fields/example-block.php)

2. **Read Quick Start**
   - See [`QUICK-START.md`](./QUICK-START.md) for fast setup

3. **Create Your Block**
   - Follow the 3-step process
   - Test in WordPress editor

4. **Add More Blocks**
   - Repeat the pattern
   - Builds your block library

---

**Theme:** Puk  
**Category:** Puk  
**Textdomain:** puk  
**Auto-Registration:** Enabled ✅
