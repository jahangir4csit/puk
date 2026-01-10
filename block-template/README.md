# Block Template - Raw HTML Storage

## 📁 Purpose

This directory stores **raw HTML sections** that will be converted to ACF blocks.

- Keep your static HTML files here
- Name files descriptively (e.g., `consultancy_integrate.php`, `hero-section.html`)
- These are **source files only** - not actual WordPress blocks

---

## 🔄 Workflow

### 1. Add Raw HTML Here
Place your HTML sections in this directory:
```
block-template/
├── consultancy_integrate.php    # Raw HTML
├── hero-section.html             # Raw HTML
└── README.md                     # This file
```

### 2. Convert to ACF Block
When ready, the HTML will be converted to:
```
acf-blocks/
├── fields/
│   └── your-block.php           # ACF fields
└── your-block.php               # Dynamic block template
```

### 3. Block Auto-Registers
Once in `acf-blocks/`, the block appears automatically in WordPress editor under **Puk** category.

---

## 📝 Example

**Raw HTML File:** `block-template/testimonial.html`
```html
<section class="testimonials">
    <h2>Customer Reviews</h2>
    <div class="testimonial-item">
        <p class="quote">"Great service!"</p>
        <span class="author">John Doe</span>
    </div>
</section>
```

**After Conversion:**
- `acf-blocks/testimonial.php` - Dynamic template
- `acf-blocks/fields/testimonial.php` - ACF fields (heading, quote, author)

---

## 🚀 To Convert Your HTML

1. **Provide the HTML** from this directory
2. **Specify dynamic parts** (what should be editable)
3. **Get ACF block** ready to use in WordPress

---

## 📚 Documentation

Full ACF block development documentation:
- [`acf-blocks/README.md`](../acf-blocks/README.md) - Complete guide
- [`acf-blocks/QUICK-START.md`](../acf-blocks/QUICK-START.md) - Quick reference
- [`acf-blocks/STRUCTURE.md`](../acf-blocks/STRUCTURE.md) - Structure overview

---

**Note:** Files in this directory are **NOT** registered as blocks. They are source material only.
