# Image Assignment Naming Guide

## Overview

This guide explains the naming conventions for bulk image assignment in the Puk theme. The system supports multiple formats for different use cases.

---

## Format 1: Hierarchical Taxonomy Assignment (fam__)

Assigns images to sub-family taxonomy terms based on parent-child relationships.

### Basic Format (4-part)
```
fam__[FamilyName]__[SubFamilyName]__[Suffix].extension
```

**Example:**
```
fam__qubo__micro-hp__main.webp
```

**Breakdown:**
- `fam__` = Prefix for hierarchical taxonomy assignment
- `qubo` = Parent family name (Level 1)
- `micro-hp` = Sub-family name (Level 2)
- `main` = Field suffix (maps to `pf_fet_img`)

---

### Group Pattern Format (5-part) ⭐ NEW

Dynamically finds and assigns to all sub-families matching a pattern.

```
fam__[FamilyName]__group__[Pattern]__[Suffix].extension
```

**Example:**
```
fam__qubo__group__maxi__main.webp
```

**Breakdown:**
- `fam__` = Prefix for hierarchical taxonomy assignment
- `qubo` = Parent family name (Level 1)
- `group__` = Keyword indicating pattern matching
- `maxi` = Pattern to match (finds all sub-families starting with "maxi")
- `main` = Field suffix

**Pattern Matching Rules:**
- ✅ **Case-insensitive**: `maxi`, `Maxi`, `MAXI` all match
- ✅ **Delimiter normalization**: Converts spaces/hyphens to consistent format
- ✅ **Prefix matching**: Pattern "maxi" matches "maxi-hp", "MAXI COB", "Maxi DMX RGBW"
- ❌ **Not substring matching**: Pattern "maxi" does NOT match "ultra-maxi"

**What it matches:**
- `maxi-hp` ✅
- `MAXI COB` ✅
- `Maxi DMX RGBW` ✅
- `ultra-maxi` ❌
- `medium-hp` ❌

---

### Pipe Separator (Multiple Specific Sub-Families)

```
fam__qubo__maxi-hp|maxi-cob|maxi-dmx-rgbw__main.webp
```

Assigns to multiple specific sub-families separated by pipe (`|`).

---

## Format 2: Sub-Family Assignment by Family Code (sf__)

Assigns images to sub-family terms by matching the `family_code` meta field.

### Basic Format (4-part)
```
sf__[SubFamilyName]__[FamilyCode]__[Suffix].extension
```

**Example:**
```
sf__micro-hp__101601__tech.webp
```

**Breakdown:**
- `sf__` = Prefix for sub-family assignment by family code
- `micro-hp` = Sub-family name
- `101601` = Family code (numeric code stored in `family_code` meta field)
- `tech` = Field suffix (maps to `pf_subfam_tech_drawing`)

---

### With Family Context (5-part)
```
sf__[FamilyName]__[SubFamilyName]__[FamilyCode]__[Suffix].extension
```

**Example:**
```
sf__qubo__micro-hp__101601__tech.webp
```

Provides additional context by specifying the parent family.

---

### Group Pattern Format (6-part) ⭐ NEW

```
sf__[FamilyName]__group__[Pattern]__[FamilyCode]__[Suffix].extension
```

**Example:**
```
sf__qubo__group__maxi__101601__tech.webp
```

**Breakdown:**
- `sf__` = Prefix for sub-family assignment
- `qubo` = Parent family name
- `group__` = Keyword indicating pattern matching
- `maxi` = Pattern to match
- `101601` = Family code (must match the `family_code` meta field)
- `tech` = Field suffix

Finds all sub-families under "qubo" matching pattern "maxi" with family_code "101601".

---

## Field Suffix Mapping

Both `fam__` and `sf__` formats support these suffixes:

| Suffix | ACF Field | Description |
|--------|-----------|-------------|
| `main` | `pf_fet_img` | Main/Featured image |
| `hover` | `pf_hover_img` | Hover state image |
| `tech` | `pf_subfam_tech_drawing` | Technical drawing |
| `designer` | `pf_designed_by` | Designer image |
| `gallery` or `gallery-1` | `pf_subfam_product_image` | Gallery images (appended) |

---

## Product Gallery Assignment

### Gallery for Sub-Family Products

Assigns images to product gallery fields for all products in a sub-family.

**Format:**
```
sf__[SubFamilyName]__[FamilyCode]__gallery2-[N].extension
sf__[SubFamilyName]__[FamilyCode]__gallery3-[N].extension
```

**Examples:**
```
sf__micro-hp__101601__gallery2-1.webp  → Adds to pro_gallary
sf__micro-hp__101601__gallery3-1.webp  → Adds to pro_sub_gallary
```

**With Group Pattern:**
```
sf__qubo__group__maxi__101601__gallery2-1.webp
sf__qubo__group__maxi__101601__gallery3-1.webp
```

Assigns to all products in all sub-families matching the pattern.

---

## Format 3: Accessories Assignment (acc__)

Assigns images to accessories taxonomy terms by matching the `tax_acc__code` field.

**Format:**
```
acc__[AccessoryCode].extension
```

**Example:**
```
acc__AC044.webp
```

Assigns to the `tax_acc_ft__img` field of the accessory with code "AC044".

---

## Format 4: Product Assignment (by SKU)

Assigns images to product fields by matching the `prod__sku` meta field.

**Format:**
```
[SKU]__[FieldSlug].extension
```

**Example:**
```
SKU123__pro_main_img.webp
```

If no field slug is provided, assigns to the featured image (`_thumbnail_id`).

---

## Quick Decision Tree

### 1. What are you assigning to?

- **Taxonomy (Sub-Family)** → Use `fam__` or `sf__`
- **Accessories** → Use `acc__`
- **Product** → Use SKU format

### 2. Do you know the parent-child relationship?

- **YES** → Use `fam__[family]__[subfamily]__[suffix]`
- **NO, but I have family code** → Use `sf__[subfamily]__[code]__[suffix]`

### 3. Do you want to assign to multiple sub-families?

- **YES, all matching a pattern** → Use `fam__[family]__group__[pattern]__[suffix]`
- **YES, specific ones** → Use pipe separator `fam__[family]__sf1|sf2|sf3__[suffix]`
- **NO, just one** → Use basic format

---

## Pattern Matching Examples

### Example 1: Assign to all "maxi" sub-families under "qubo"

**Filename:**
```
fam__qubo__group__maxi__main.webp
```

**Matches:**
- Qubo → MAXI HP
- Qubo → Maxi COB
- Qubo → maxi-dmx-rgbw

### Example 2: Assign tech drawing to all "micro" sub-families with code 101601

**Filename:**
```
sf__qubo__group__micro__101601__tech.webp
```

**Matches:**
- All sub-families under "qubo" starting with "micro" that have family_code = "101601"

### Example 3: Assign product gallery to all products in "maxi" group

**Filename:**
```
sf__qubo__group__maxi__101601__gallery2-1.webp
```

**Result:**
- Finds all "maxi*" sub-families under "qubo" with family_code "101601"
- Adds image to `pro_gallary` field of all products in those sub-families

---

## Debugging Tips

### If pattern matching returns "No matches found":

1. **Check parent family exists**: Verify "qubo" (or your family name) exists in taxonomy
2. **Check sub-family names**: Go to WordPress Admin → Products → Product Family
3. **Verify pattern**: Ensure sub-families actually start with your pattern
4. **Check family code**: If using `sf__` format, verify the `family_code` meta field matches

### Common Issues:

- **Case sensitivity**: Don't worry, pattern matching is case-insensitive
- **Delimiters**: Spaces and hyphens are normalized automatically
- **Prefix vs substring**: Pattern "maxi" won't match "ultra-maxi" (prefix only)
