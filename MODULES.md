# Fika Module Integration Guide

## Overview

Five new ACF-compatible modules have been created from the design prototype:

| Module | File | Purpose |
|--------|------|---------|
| **Hero Split** | `hero-split.php` | Organic split layout: headline + lead on left, clipped image on right |
| **Services Row** | `services-row.php` | Three text blocks (Classes, Shop, Vouchers) |
| **Class Grid** | `class-grid.php` | Featured classes with images, dates, prices, and booking CTA |
| **Product Grid** | `product-grid.php` | Featured shop products with prices |
| **Story Block** | `story-block.php` | Blockquote / testimonial with decorative divider |

## Setup Steps

### 1. Import ACF Field Groups

The ACF JSON files are in `acf-json/`:
- `group_fika_hero_split.json`
- `group_fika_services_row.json`
- `group_fika_class_grid.json`
- `group_fika_product_grid.json`
- `group_fika_story_block.json`

**In WordPress Admin:**
- Go to **ACF → Sync Available**
- Check all five field groups
- Click **Sync**

This will automatically create the layouts and fields in your ACF Flexible Content.

### 2. Enqueue Module Styles

Add the following to `inc/assets.php` (in the `wp_enqueue_style()` section):

```php
// Enqueue module styles
$modules = [
    'hero-split',
    'services-row',
    'class-grid',
    'product-grid',
    'story-block',
];

foreach ( $modules as $module ) {
    wp_enqueue_style(
        "bonsai-module-{$module}",
        get_template_directory_uri() . "/assets/css/modules/_{$module}.css",
        [],
        filemtime( get_template_directory() . "/assets/css/modules/_{$module}.css" )
    );
}
```

Or, if you prefer a single import, add to your main stylesheet (`style.css` or `assets/css/core/additions.css`):

```css
@import url('../modules/_hero-split.css');
@import url('../modules/_services-row.css');
@import url('../modules/_class-grid.css');
@import url('../modules/_product-grid.css');
@import url('../modules/_story-block.css');
```

### 3. Add to Page Builder

In the page builder (Flexible Content field):
1. Add a new layout
2. Choose **Hero Split**, **Services Row**, **Class Grid**, **Product Grid**, or **Story Block**
3. Fill in the fields
4. Publish

## Module Field Reference

### Hero Split

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Kicker | Text | No | Small text above heading (e.g. "made with care") |
| Main Heading | Text | **Yes** | Primary headline |
| Accent Word | Text | No | Part of heading to highlight in brown |
| Lead Paragraph | Textarea | **Yes** | Supporting text (allows basic HTML) |
| Primary CTA Text | Text | **Yes** | Button label |
| Primary CTA Link | URL | **Yes** | Where button points |
| Secondary CTA Text | Text | No | Soft text link (e.g. "Watch our story") |
| Secondary CTA Link | URL | No | Where soft link points |
| Feature Cards | Repeater | No | 1–3 small feature boxes (icon, title, description) |
| Hero Image | Image | **Yes** | Landscape image; will be clipped with organic shape |

### Services Row

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Services (Repeater) | — | **Yes** | 1–3 blocks |
| — Title | Text | — | e.g. "Classes" |
| — Description | Textarea | — | Supporting text |
| — Link Text | Text | — | e.g. "See all workshops" |
| — Link URL | URL | — | — |

### Class Grid

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Section Title | Text | No | e.g. "Upcoming classes" |
| Section Intro | Textarea | No | e.g. "A few seats left..." |
| Classes (Repeater) | — | **Yes** | 2–3 class cards |
| — Image | Image | **Yes** | Square or landscape |
| — Title | Text | — | e.g. "Nordic enriched dough" |
| — Meta | Text | — | e.g. "Sat 14 June · 10:00–15:00 · £95" |
| — CTA Text | Text | — | Defaults to "Book" |
| — Class Link | URL | **Yes** | Link to booking/detail page |
| View All Link | URL | No | Link to full calendar |
| View All Text | Text | No | Defaults to "View full calendar" |

### Product Grid

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Section Title | Text | No | e.g. "From the shop" |
| Section Intro | Textarea | No | e.g. "Pantry picks..." |
| Background Style | Select | No | "Default" or "Raised" (with border) |
| Products (Repeater) | — | **Yes** | 2–3 product cards |
| — Image | Image | **Yes** | Square or landscape |
| — Title | Text | — | e.g. "Local wildflower honey" |
| — Price | Text | — | e.g. "£8.50" |
| — Product Link | URL | **Yes** | Link to product page or WooCommerce |
| View All Link | URL | No | Link to shop archive |
| View All Text | Text | No | Defaults to "View all products" |

### Story Block

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Quote Text | Textarea | **Yes** | Main blockquote text |
| Background Style | Select | No | "Default" (surface) or "Raised" |

## Design System Reference

All modules use the Fika design tokens defined in `DESIGN.md`:

**Colours:**
- `--accent`: `#6E4518` (cinnamon brown)
- `--ink-strong`: `#1A1A1A` (headings)
- `--ink`: `#2B2B2B` (body text)
- `--muted`: `#6B6B6B` (meta, captions)
- `--line`: `#E6E4E1` (dividers)

**Typography:**
- Display: **Varela Round** (headings)
- Body: **Inter** (text, buttons)

**Spacing:**
- Desktop padding: `4rem`
- Tablet/mobile: `3rem` / `2.5rem`

## Responsive Breakpoints

All modules include responsive styles at these breakpoints:
- `1536px` (large desktop)
- `1366px` (desktop)
- `1280px` (desktop)
- `992px` (tablet landscape)
- `768px` (tablet)
- `600px` (mobile)

Each CSS file includes tailored styles for smaller viewports.

## Helper Functions

**`bonsai_get_feature_icon( $icon_name )`**

Used by the Hero Split module to render feature icons. Available icons:
- `'chart'` – Analytics / data visualization
- `'calendar'` – Calendar / dates
- `'location'` – Location pin

Defined in `inc/module-helpers.php`.

## Image Dimensions

Recommended image sizes for best results:

| Module | Field | Recommended Size | Notes |
|--------|-------|------------------|-------|
| Hero Split | Hero Image | 1400×1050 px (4:3) | Landscape; will be clipped organically |
| Class Grid | Image | 800×800 px (1:1) | Square for consistency |
| Product Grid | Image | 800×800 px (1:1) | Square; prices visible over image |

Use **Featured Image → Crop** in WordPress Admin to ensure proper aspect ratios.

## Common Customizations

### Change the Cinnamon Accent Colour

Edit `DESIGN.md` and update `--accent`:
```css
--accent: #your-colour;
```

This will cascade to all modules.

### Adjust Section Padding

Edit the `.section` class in `_class-grid.css`, `_product-grid.css`, etc.:
```css
.section {
    padding: 5rem 0; /* was 4rem 0 */
}
```

### Limit Class/Product Grid Items

Edit the ACF repeater fields in the JSON:
- Change `"max": 0` to `"max": 3` to limit to 3 items per module.

### Add a Testimonials Module

Duplicate `story-block.php` and `_story-block.css` to create a multi-item testimonials slider — just extend the repeater in the ACF JSON.

## Accessibility

All modules include:
- Semantic HTML (`<section>`, `<article>`, `<figure>`, etc.)
- Proper heading hierarchy (H1 → H2 → H3)
- `aria-labelledby` on sections
- `aria-hidden="true"` on decorative SVGs
- Focus states on all interactive elements
- Alt text support via WordPress image attachment fields

No inline colour-only indicators (all visual info has text/icon backup).

## Performance Notes

- Images use `loading="lazy"` by default (except Hero image, which is `loading="eager"`)
- CSS is modular and only loaded if modules are used
- No JavaScript required (CSS Grid / Flexbox layout)
- Organic clip SVG defs are minimal (single path per hero)

---

**Questions or issues?** Check `llm-instructions.txt` or the DESIGN.md specification file.
