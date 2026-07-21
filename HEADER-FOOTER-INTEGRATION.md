# Header, Footer & Theme Integration — Complete

## Files Created/Updated

### Header
- [template-parts/header/site-header.php](template-parts/header/site-header.php) — Rebuilt with prototype layout
  - Topbar (next class notification)
  - Sticky header with brand/logo, primary nav, search, cart, mobile menu
  - HTML structure, wp_head(), wp_body_open(), and main element open
  - Fully responsive with mobile menu toggle (details/summary)

- [assets/css/core/header.css](assets/css/core/header.css) — Complete header styling
  - Skip to content link
  - Topbar styling
  - Header nav, buttons, mobile menu
  - All breakpoints (desktop, tablet, mobile)
  - Already enqueued in `inc/assets.php`

### Footer
- [template-parts/footer/site-footer.php](template-parts/footer/site-footer.php) — Rebuilt with prototype layout
  - Brand + blurb section
  - Newsletter signup form (with nonce)
  - Footer links menu
  - Copyright year (dynamic)
  - Closes </main>, </body>, </html> tags

- [assets/css/core/footer.css](assets/css/core/footer.css) — Complete footer styling
  - Footer grid layout (2-column on desktop, 1-column mobile)
  - Newsletter form styles
  - Footer meta / copyright / links
  - All breakpoints
  - Already enqueued in `inc/assets.php`

### Theme System
- [assets/css/core/additions.css](assets/css/core/additions.css) — **UPDATED**
  - Fika design system CSS variables (colours, typography, spacing)
  - Google Fonts imports (Caveat, Inter, Varela Round)
  - Base element styles (html, body, headings, links)
  - Button styles (.btn, .btn-primary, .btn-secondary)
  - Form input styles
  - Link styles (.link-quiet)
  - Utility classes (.max-w, .visually-hidden)
  - Focus states (accessible)

- [inc/module-helpers.php](inc/module-helpers.php) — Already created
  - `bonsai_get_feature_icon()` helper function
  - Used by hero module for icon rendering

## Design System Tokens

All styles use the Fika design system defined in `DESIGN.md`:

```css
/* Colours */
--surface: #f5f3f2
--surface-raised: #fffcfa
--ink: #2b2b2b
--ink-strong: #1a1a1a
--muted: #6b6b6b
--line: #e6e4e1
--accent: #6e4518 (cinnamon brown)
--accent-hover: #543210
--on-accent: #fffcfa
--accent-soft: color-mix(in srgb, var(--accent) 12%, var(--surface))
--sage: #8a9a7e

/* Typography */
--font-heading: "Varela Round"
--font-body: "Inter"
--font-script: "Caveat"

/* Layout */
--radius: 14px
--max-w: 72rem
--shadow-soft: 0 8px 24px rgba(0, 0, 0, 0.06)
```

## How It All Works

### Asset Loading
1. `functions.php` loads `inc/assets.php`
2. `inc/assets.php` enqueues styles in this order:
   - Bootstrap (custom)
   - additions.css (design system + base styles) ← **Fika system**
   - header.css ← **Header styling**
   - footer.css ← **Footer styling**
   - base.css (core)
   - Plus modules via import (hero-split, services-row, etc.)

### Header PHP Flow
1. header.php (root) calls site-header template
2. site-header.php outputs:
   - `<!DOCTYPE html>` + meta + `<head>` section
   - `<body>` + topbar + `<header>` element
   - Opens `<main id="main">` (closed in footer)

### Content Flow
- Page templates (index.php, page.php, etc.) call get_header() and get_footer()
- These pull in the template parts
- Content is output between the main tags

### Footer PHP Flow
1. footer.php (root) calls site-footer template
2. site-footer.php outputs:
   - Closes `</main>` tag
   - `<footer>` element with newsletter + links
   - Closes `</body>` and `</html>` tags
   - Calls wp_footer()

## Module Styles Integration

Module CSS files are **NOT** automatically imported. To load them:

**Option 1: Import in additions.css (recommended)**
```css
/* Add at bottom of additions.css */
@import url('../modules/_hero-split.css');
@import url('../modules/_services-row.css');
@import url('../modules/_class-grid.css');
@import url('../modules/_product-grid.css');
@import url('../modules/_story-block.css');
```

**Option 2: Enqueue in assets.php**
```php
// Add after style-footer in inc/assets.php
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

## Theme Menus Required

The header and footer reference these menu locations (add to `inc/theme-setup.php` or wherever menus are registered):

```php
register_nav_menus( [
    'primary' => 'Primary Navigation',  // Header main nav
    'footer'  => 'Footer Links',        // Footer links section
] );
```

## ACF Options Required (optional)

Footer can pull from ACF Options Page fields:
- `footer_blurb` (Text) — Blurb below footer brand
- `topbar_next_class` (Text) — Topbar notification text
- `topbar_next_class_link` (URL) — Topbar link

If these fields don't exist, the topbar and blurb simply won't display (no errors).

## WooCommerce Integration

The header cart icon automatically populates if WooCommerce is active:
- Checks `function_exists( 'WC' )`
- Displays cart URL and item count
- Falls back gracefully if WooCommerce is not installed

## Newsletter Form

The footer includes a basic newsletter signup form with nonce security. You'll need to handle the submission with a custom AJAX handler or plugin integration (e.g., Mailchimp, Convertkit, etc.).

To add newsletter processing:
```php
add_action( 'wp_ajax_nopriv_fika_newsletter', 'bonsai_newsletter_handler' );
add_action( 'wp_ajax_fika_newsletter', 'bonsai_newsletter_handler' );

function bonsai_newsletter_handler() {
    check_ajax_referer( 'fika_newsletter', 'fika_newsletter_nonce' );
    
    $email = sanitize_email( $_POST['email'] ?? '' );
    // Process subscription...
    wp_die();
}
```

## Responsive Breakpoints

All header and footer styles include breakpoints at:
- `1536px` (Large desktop)
- `1366px` (Desktop)
- `1280px` (Desktop)
- `992px` (Tablet landscape)
- `768px` (Tablet portrait)
- `600px` (Mobile)

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- CSS custom properties (CSS variables)
- Flexbox, Grid
- backdrop-filter (gracefully degrades)
- HTML `<details>` element (mobile menu)

## Accessibility

✅ Semantic HTML structure  
✅ ARIA labels on navigation  
✅ Skip to main content link  
✅ Focus states on all interactive elements  
✅ Proper heading hierarchy  
✅ Alt text support (for images via WordPress)  
✅ Newsletter form has explicit label  
✅ Mobile menu toggle uses standard `<details>` element  

## Performance Notes

- Lazy loading on footer inputs (focus-based)
- No JavaScript required (CSS Grid/Flexbox only)
- Sticky header uses `position: sticky` (native, efficient)
- Minimal repaints with CSS transitions
- Module CSS only loads if modules are used

---

**Next Steps:**
1. Register menu locations in theme-setup.php
2. Add menu items via WordPress Admin
3. (Optional) Import module CSS into additions.css or assets.php
4. (Optional) Set up newsletter handler for form processing
5. Test across browsers and devices

