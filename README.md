# Fika Exeter — WordPress Theme

**Version:** 1.0
**Theme slug:** `fika-bonsai`
**Author:** Ben Ervine / [The Bonsai Digital Collective](https://bonsaidigitalcollective.co.uk/)
**Base Theme:** Bonsai Base Theme
**Text Domain:** `bonsai-base-theme`

> A bespoke WordPress theme for Fika Exeter — a cookery school and artisan shop in Exeter, Devon.

---

## Project Overview

This repository contains the bespoke WordPress theme for **Fika Exeter**, built on top of the **Bonsai Base Theme** by [The Bonsai Digital Collective](https://bonsaidigitalcollective.co.uk/).

Fika Exeter is a cookery school and online shop named after the Swedish concept of taking a break with coffee and something sweet. The site supports three primary commercial journeys: booking cookery classes, purchasing products via WooCommerce, and buying gift vouchers.

The design language is Scandinavian-minimal — warm cinnamon tones, rounded typography (Poppins headings, self-hosted Glacial Indifference body, Caveat for script accents), generous whitespace, and an organic visual style with clipped image shapes and decorative leaf motifs.

This theme is intended for use exclusively on the **Fika Exeter** site, under Bonsai Digital Collective management.

See full release history in [CHANGELOG.md](CHANGELOG.md).
See the design system reference in [DESIGN.md](DESIGN.md).

---

## Developers

- **Lead Developer:** [Ben Ervine](https://bonsaidigitalcollective.co.uk/) — The Bonsai Digital Collective
- **Lead Designer:** Chrissie — The Bonsai Digital Collective
- **Agency:** [The Bonsai Digital Collective](https://bonsaidigitalcollective.co.uk/), Devon, UK

---

## Requirements

- **WordPress:** 6.0+
- **PHP:** 8.0+ (recommended)
- **Database:** MySQL 5.7+ or MariaDB equivalent
- **ACF Pro:** Required — all custom fields are ACF-dependent
- **WooCommerce:** Required — shop and class booking functionality

---

## Tech Stack

| Layer | Technology |
|---|---|
| CMS | WordPress 6.0+ |
| Custom fields | ACF Pro (Flexible Content, Repeaters, Options Pages) |
| CSS framework | Bootstrap 5 (custom build) |
| Carousel | Slick |
| JavaScript | jQuery (global) |
| Fonts | Poppins + Caveat (Google Fonts), Glacial Indifference (self-hosted) |
| Icons | FontAwesome 6 Free (self-hosted) |
| eCommerce | WooCommerce |
| Consent | Cookiebot |
| Booking widget | SiteMinder IBE (conditional — accommodation CPT only) |
| Build system | None — plain CSS and JS, committed directly |

---

## Theme Structure

```
fika-bonsai/
├── acf-json/                    ACF Local JSON field group definitions (version-controlled)
├── assets/
│   ├── css/
│   │   ├── core/                Global styles: base.css, header.css, footer.css, additions.css
│   │   └── modules/             Per-module CSS, loaded on-demand by page-builder.php
│   ├── fonts/                   FontAwesome 6 Free (self-hosted)
│   └── js/                      main.js, slick.js, bootstrap.bundle.min.js
├── inc/                         Modular PHP includes, all loaded by functions.php
│   ├── acf.php                  ACF options pages and humans.txt writer
│   ├── acf-json.php             ACF Local JSON save/load paths
│   ├── acf-defaults.php         Pre-populates page_builder field on new posts
│   ├── assets.php               Enqueue scripts and styles
│   ├── theme-setup.php          Theme supports and nav menu registration
│   ├── helpers.php              Helper functions including bonsai_kses_iframe()
│   ├── module-helpers.php       Icon and formatting helpers for modules
│   ├── security.php             Security hardening
│   ├── cleanup.php              Remove emojis and unused scripts
│   ├── gutenberg.php            Disables Gutenberg
│   ├── post-labels.php          Renames 'Posts' to 'News'
│   └── [others]                 accessibility, media, content, frontend, lazy-load, webp, rss, etc.
├── template-parts/
│   ├── header/site-header.php   Site header partial
│   ├── footer/site-footer.php   Site footer partial
│   ├── content/                 Page, single, 404, campaign content templates
│   ├── modules/                 ACF Flexible Content module templates
│   │   ├── page_builder.php     Child-aware dispatcher for all Flexible Content layouts
│   │   ├── hero_split.php               Hero: organic split, text + clipped image
│   │   ├── services_row.php             Three-column service blocks
│   │   ├── class_grid.php               Cookery class cards with booking CTA
│   │   ├── product_grid.php             WooCommerce product cards
│   │   ├── story_block.php              Blockquote / testimonial with decorative divider
│   │   ├── slider_module.php            Full-width fade slider with per-slide overlay
│   │   ├── contact_module.php           Title + rich content / form shortcode split
│   │   ├── split_content_module.php     Left/right split: text+CTA, image, or video per side
│   │   ├── faq_accordion.php            Question/answer repeater, native <details>/<summary>
│   │   ├── testimonials_carousel.php    Quote repeater in a Slick fade carousel
│   │   ├── stats_strip.php              Auto-fit row of number + label stats
│   │   ├── map_location.php             Address, phone, opening hours, directions link
│   │   ├── cta_banner.php               Full-width heading + button banner
│   │   └── logo_strip.php               "As featured in" logo row
│   └── snippets/                Reusable micro-partials (content-block-intro, etc.)
├── templates/                   Root-level page templates
│   ├── page.php
│   ├── single.php
│   └── single-campaign.php
├── functions.php                Modular loader — requires all inc/ files
├── header.php                   WordPress header wrapper
├── footer.php                   WordPress footer wrapper
├── index.php                    Fallback template
├── 404.php                      404 template
├── DESIGN.md                    Design system reference (do not edit without updating tokens)
├── llm-instructions.txt         AI context file for any LLM working on this theme
├── MEMORY.md                    Session memory reference for Claude Code
└── CHANGELOG.md                 Version history
```

---

## Getting Started

### 1. Clone the repository

```bash
git clone [repository-url]
```

Place the theme folder at:

```
/wp-content/themes/fika-bonsai/
```

### 2. Activate the theme

In WordPress admin: Appearance > Themes > Fika Bonsai > Activate.

### 3. Activate ACF Pro

Ensure ACF Pro is installed and licensed. Without it, all page builder layouts and options pages will fail silently.

### 4. Sync ACF field groups

Go to **Custom Fields > Field Groups**. If any groups show "Sync Available", click **Sync** to import from `/acf-json/`. Do this after every `git pull`.

### 5. Install required plugins

- ACF Pro (required)
- WooCommerce (required)
- Cookiebot or equivalent consent management (required)
- SEO plugin: Yoast or RankMath <!-- TODO: confirm which is in use -->

### 6. Configure Site Settings

Go to **Site Settings** in the WordPress admin sidebar. Fill in any sitewide content fields (logo, contact details, social links, etc.).

### 7. Set up menus

Go to **Appearance > Menus**. Assign menus to the four registered locations:
- Primary Navigation
- Mobile Navigation
- Footer Navigation
- Legal Navigation

### 8. Configure Cookiebot

Ensure Cookiebot is connected to the correct domain and all third-party scripts (Google Analytics, any embeds) are correctly categorised. Test consent flow before going live.

---

## Development Workflow

### Branch strategy

<!-- TODO: confirm branch strategy with Ben -->

Recommended:
- `main` — live site, never commit directly
- `develop` — staging, all dev work goes here
- Feature branches: `feature/module-name`, `fix/issue-description`

### CSS development

All CSS is plain CSS — no preprocessor. Edit files in `assets/css/core/` for global styles, or `assets/css/modules/{layout}.css` for module-specific styles. Module CSS is loaded on-demand by `page-builder.php` — only when that layout appears on a page.

Do not minify CSS by hand. Do not move module CSS into the global stylesheet.

### Adding a new module

1. Create the ACF layout in the `page_builder` field group. Use underscores in the layout name (e.g. `image_text_split`).
2. Create `template-parts/modules/{layout-slug}.php`.
3. Create `assets/css/modules/{layout-slug}.css`.
4. Export ACF field groups to `/acf-json/` and commit.
5. Test the layout appears and CSS loads correctly.
6. Update the module table in `MEMORY.md` with the new module's documentation.

### ACF JSON sync

After any field group change:
1. ACF will auto-save JSON to `/acf-json/` if the save path is configured (it is — via `inc/acf-json.php`).
2. Commit the updated `.json` files alongside any PHP or CSS changes.
3. On the destination environment, go to **Custom Fields > Field Groups** and sync.

### Humans.txt

The `/humans.txt` file at the webroot is managed via the **Humans.txt** options page in WordPress admin. Editing it directly on the server will be overwritten on the next save. Always edit via the admin interface.

---

## Key Conventions

### PHP

- All custom functions prefixed `bonsai_`
- Tab indentation, spaces inside parentheses: `if ( $x ) {`
- Yoda conditions: `if ( 'value' === $variable )`
- Escape all output — see `inc/helpers.php` for `bonsai_kses_iframe()` for embed fields
- ACF fields always checked for existence before output: `if ( get_field('field_name') )`
- Full files only — never deliver fragments unless a targeted edit is explicitly requested

### CSS

- Use Fika design tokens (`--accent`, `--surface`, `--ink`, etc.) for new work
- 14px border-radius throughout
- American spelling in CSS property values; UK spelling in comments
- No `!important` without a comment explaining why

### JavaScript

- jQuery only — wrap all code in `(function($) { ... })(jQuery);`
- Enqueue via `wp_enqueue_scripts` — no inline JS
- No `console.log` in committed code

### ACF field naming

- snake_case, descriptive: `hero_title`, `hero_background_image`, `service_link_url`
- Never abbreviate to the point of ambiguity

---

## Deployment Checklist

Before going live, run through the following:

**Functional**
- [ ] All five page builder modules render correctly
- [ ] WooCommerce shop: product listing and single product pages
- [ ] Class booking flow: end-to-end from selection to confirmation
- [ ] Gift voucher purchase flow
- [ ] Contact form delivers to correct inbox
- [ ] 404 page renders correctly
- [ ] All nav menus assigned and rendering correctly

**SEO & Meta**
- [ ] Title tags and meta descriptions on all key pages
- [ ] Single H1 per page, logical heading hierarchy
- [ ] Alt text on all images
- [ ] XML sitemap generated and submitted to Search Console
- [ ] robots.txt correct — not blocking indexable pages
- [ ] OG tags in place for social sharing

**Privacy & Consent**
- [ ] Cookiebot active and correctly configured
- [ ] No scripts fire before consent is given
- [ ] Privacy Policy and Cookie Policy pages live
- [ ] Contact form has privacy consent statement

**Accessibility**
- [ ] Lighthouse / axe accessibility audit run
- [ ] All images have alt text
- [ ] Focus states visible on all interactive elements
- [ ] Colour contrast passes WCAG 2.1 AA

**Performance**
- [ ] Hero image loads eagerly (loading="eager", fetchpriority="high")
- [ ] All other images lazy-loaded
- [ ] No render-blocking scripts

**Technical**
- [ ] ACF JSON synced on live environment
- [ ] Backup taken immediately before go-live
- [ ] No staging URLs in content or configuration
- [ ] WP_DEBUG disabled on live

---

## Contacts & Resources

- **Client:** Fika Exeter <!-- TODO: confirm client contact name and email with Ben -->
- **Lead Developer:** Ben Ervine — The Bonsai Digital Collective
- **Lead Designer:** Chrissie — The Bonsai Digital Collective
- **Staging URL:** <!-- TODO: confirm with Ben -->
- **Live URL:** <!-- TODO: confirm with Ben -->
- **Hosting:** <!-- TODO: confirm provider with Ben -->
- **Repository:** <!-- TODO: confirm GitHub URL with Ben -->

---

## Licence

**Private Licence — All Rights Reserved.**
Developed exclusively for Fika Exeter by The Bonsai Digital Collective.
Do not distribute or reuse without written permission.

---

© The Bonsai Digital Collective / Ben Ervine. All Rights Reserved.
