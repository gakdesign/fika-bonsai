# Project Memory — Fika Exeter

> Session memory reference for AI assistants working on this project.
> Companion to llm-instructions.txt (code standards and behaviour) and DESIGN.md (visual design tokens).
> This file captures what is known about the project's current state, architecture decisions, and quirks.

---

## Project Overview

- **Client:** Fika Exeter — cookery school and artisan shop, Exeter, Devon, UK
- **Site purpose:** Book cookery classes, sell products via WooCommerce, sell gift vouchers
- **Named after:** The Swedish concept of a coffee break with something sweet
- **Live URL:** <!-- TODO: confirm with Ben -->
- **Staging URL:** <!-- TODO: confirm with Ben -->
- **Hosting:** <!-- TODO: confirm provider and PHP version with Ben -->
- **WordPress:** 6.0+ / PHP 8.0+
- **Theme slug:** fika-bonsai
- **Theme path:** /wp-content/themes/fika-bonsai
- **Text domain:** bonsai-base-theme
- **Repository:** <!-- TODO: confirm GitHub URL with Ben -->
- **Agency:** The Bonsai Digital Collective, Devon, UK
- **Lead developer:** Ben Ervine
- **Lead designer:** Chrissie (The Bonsai Digital Collective)
- **Status:** v1.0 — initial build complete, five modules in place

---

## Architecture Decisions

- **Modular loader pattern:** functions.php does nothing except iterate a $modules array and require_once each inc/{module}.php file. This keeps functions.php clean and makes it easy to disable individual features without hunting through a monolithic file.
- **ACF Flexible Content for all layouts:** All page content is built through the page_builder ACF field using Flexible Content layouts. There is no Gutenberg. This is intentional — Gutenberg is disabled via inc/gutenberg.php.
- **Child-aware page-builder dispatcher:** template-parts/modules/page-builder.php uses get_theme_file_path() and locate_template() so a child theme can override any module template or CSS without touching the parent. This is future-proofing for potential multi-site or white-label use.
- **On-demand module CSS:** Each module's CSS is only enqueued when that layout appears on a page. This is handled by page-builder.php, not by assets.php. Do not move module CSS into the global enqueue.
- **ACF JSON sync:** /acf-json/ is the single source of truth for all field group definitions. Always commit after field group changes. Always sync on new environments before expecting modules to work.
- **Humans.txt via ACF:** The /humans.txt file at the webroot is written by a WordPress hook (acf/save_post), not edited directly. Editing it on the server will be overwritten. Always edit via the Humans.txt options page in admin.
- **Posts renamed to News:** inc/post-labels.php renames the built-in Posts post type to News throughout the admin UI. This is cosmetic — the post_type slug remains 'post'.
- **SiteMinder IBE conditional load:** The booking widget script is enqueued only on is_singular('accommodation'). This implies an 'accommodation' custom post type is planned or in use. [ASSUMED — confirm with Ben whether the CPT exists yet]
- **Design token approach:** Fika brand colours are defined as CSS custom properties in module CSS. The base.css file contains legacy variables from the Bonsai Base Theme. New modules should only use the Fika design tokens (--accent, --surface, --ink, etc.), not the legacy --black, --orange, etc.

---

## Stack Summary

| Component | Detail |
|---|---|
| WordPress | 6.0+ |
| PHP | 8.0+ |
| ACF Pro | All custom fields — required |
| jQuery | Global, via wp_enqueue_script('jquery') |
| Bootstrap | v5, custom build (bootstrap-custom.min.css) |
| Slick | Carousel, enqueued globally |
| FontAwesome | v6 Free, self-hosted in assets/fonts/fontawesome/ |
| Google Fonts | Varela Round (headings) + Inter (body) via preconnect + wp_enqueue_style |
| WooCommerce | Required — shop and class booking |
| Cookiebot | Consent management — required |
| SiteMinder IBE | Booking widget — conditional on accommodation CPT |
| Build system | None — plain CSS and JS committed directly |

---

## Module Architecture

All page content is built via ACF Flexible Content in the `page_builder` field. The dispatcher is `template-parts/modules/page_builder.php`. All 14 layouts live inside the single `page_builder` flexible content field — there is one ACF JSON file for the whole set (`group_fika_page_builder.json`), not one file per module.

| Layout slug | PHP template | CSS | Purpose |
|---|---|---|---|
| hero_split | template-parts/modules/hero_split.php | assets/css/modules/hero_split.css | Organic split: text left, clipped image right |
| services_row | template-parts/modules/services_row.php | assets/css/modules/services_row.css | Three-column service blocks |
| class_grid | template-parts/modules/class_grid.php | assets/css/modules/class_grid.css | Cookery class cards with booking CTA |
| product_grid | template-parts/modules/product_grid.php | assets/css/modules/product_grid.css | Featured WooCommerce product cards |
| story_block | template-parts/modules/story_block.php | assets/css/modules/story_block.css | Blockquote / testimonial, variable background |
| slider_module | template-parts/modules/slider_module.php | assets/css/modules/slider_module.css | Full-width fade slider with per-slide overlay |
| contact_module | template-parts/modules/contact_module.php | assets/css/modules/contact_module.css | Title + rich content / form shortcode split |
| split_content_module | template-parts/modules/split_content_module.php | assets/css/modules/split_content_module.css | Left/right split: text+CTA, image, or video per side |
| faq_accordion | template-parts/modules/faq_accordion.php | assets/css/modules/faq_accordion.css | Q&A repeater, native `<details>`/`<summary>`, no JS |
| testimonials_carousel | template-parts/modules/testimonials_carousel.php | assets/css/modules/testimonials_carousel.css | Quote repeater in a Slick fade carousel |
| stats_strip | template-parts/modules/stats_strip.php | assets/css/modules/stats_strip.css | Auto-fit row of number + label stats (2–5 items) |
| map_location | template-parts/modules/map_location.php | assets/css/modules/map_location.css | Address, phone, opening hours, directions link |
| cta_banner | template-parts/modules/cta_banner.php | assets/css/modules/cta_banner.css | Full-width heading + button banner |
| logo_strip | template-parts/modules/logo_strip.php | assets/css/modules/logo_strip.css | "As featured in" logo row, greyscale until hover |

`BLANK.php` / `BLANK.css` are the scaffold pair to copy when adding a new module.

To add a new module: create the ACF layout inside `page_builder` (underscores in slug), create the PHP template and CSS file, export JSON, commit everything.

---

## ACF Field Groups

All field groups live in /acf-json/. Always commit this folder after changes.

| File | Contains | Notes |
|---|---|---|
| group_fika_page_builder.json | All 14 `page_builder` Flexible Content layouts (see Module Architecture above) | Single field group for the whole page builder |
| group_fika_market_workshop_details.json | Market / Workshop Details field group | Feeds the Market/Workshop ↔ class relationship used by class_grid |
| group_64525a8b8885f.json | Theme Settings | Options page: sitewide content (logo, contact, social, etc.) |
| group_6881f94ae87b3.json | Humans.txt | Options page: writes /humans.txt on save |

ACF Options Pages:
- Site Settings (slug: theme-general-settings) — sitewide content
- Humans.txt Editor (slug: site-text-files) — field: humans_txt_content (textarea)

---

## Key File Locations

| File | Purpose |
|---|---|
| functions.php | Modular loader — add new inc/ files to the $modules array here |
| inc/acf.php | Options pages + humans.txt writer hook |
| inc/assets.php | All script and style enqueues including SiteMinder conditional |
| inc/helpers.php | bonsai_kses_iframe(), bonsai_get_trimmed_excerpt(), bonsai_get_trimmed_content() |
| inc/module-helpers.php | bonsai_get_feature_icon() and other module formatting helpers |
| inc/post-labels.php | Posts > News rename |
| template-parts/modules/page_builder.php | Flexible Content dispatcher (do not edit without understanding child-aware logic) |
| assets/css/core/base.css | Global reset, CSS variables, base element styles |
| assets/css/core/additions.css | Global overrides and additions beyond base |
| acf-json/ | Source of truth for all ACF field group definitions |

---

## Known Quirks & Issues

- **base.css legacy variables:** The base.css file still contains Bonsai Base Theme colour variables (--black: #0C1526, --orange: #c47714, etc.) that are not part of the Fika brand. These exist for compatibility with Bootstrap component styling. Do not use them for new module work — use the Fika design tokens instead.
- **~~Google Fonts comment in assets.php~~ (resolved):** Fonts are now Poppins (headings, Google Fonts) + Glacial Indifference (body, self-hosted, see `assets/fonts/glacial-indifference/README.md`) + Caveat (script accents, Google Fonts) — set in `inc/assets.php` and `assets/css/core/additions.css`. `--font-body` falls back to `system-ui, sans-serif` until the licensed Glacial Indifference font files are dropped into that folder.
- **~~fontawesome.min.cs typo~~ (fixed):** `inc/assets.php` was enqueueing FontAwesome from `fontawesome.min.cs` (missing the final `s`) — the style tag pointed at a 404 while the cache-busting `filemtime()` call happened to reference the real `.css` file, so the bug was easy to miss. Fixed to `fontawesome.min.css`.
- **SiteMinder CPT:** The assets.php file conditionally enqueues the SiteMinder IBE widget on is_singular('accommodation'). Whether the 'accommodation' CPT exists or is registered elsewhere is not confirmed from the available files. Confirm with Ben before building any template for it.
- **AOS (Animate On Scroll):** Both the AOS CSS and JS enqueues are commented out in assets.php. Do not uncomment without confirming the animation library is needed and that Cookiebot consent implications have been considered.
- **hero-split icon output:** The hero-split.php module calls bonsai_get_feature_icon() without escaping the return value. Confirm that bonsai_get_feature_icon() returns sanitised HTML (it likely does via wp_kses or similar — verify in inc/module-helpers.php before flagging).
- **story-block background_style:** The story-block module appends a CSS class based on the background_style ACF field value. The field is escaped with esc_attr() inline. Confirm the select field options match the expected CSS class names.

---

## Ongoing Work

- [ ] Confirm whether the 'accommodation' CPT exists and what template it uses
- [ ] Confirm SEO plugin in use (Yoast or RankMath)
- [ ] Confirm staging and live URLs
- [ ] Confirm hosting provider and PHP version
- [ ] Confirm GitHub repository URL
- [ ] Confirm class-grid and product-grid ACF field structures with Ben

---

## Past Decisions

- **Gutenberg disabled:** The project uses ACF Flexible Content for all page layouts. Gutenberg adds unnecessary complexity and potential conflicts with the module approach. Disabled via inc/gutenberg.php.
- **Comments disabled:** Fika is a cookery school / ecommerce site, not a blog. Comments are not needed and add security surface. Disabled via inc/comments.php.
- **Bootstrap 5 chosen over Tailwind:** The Bonsai Base Theme uses Bootstrap. Consistency with the agency's stack and existing tooling was the deciding factor. [ASSUMED — confirm with Ben if this was deliberate for Fika specifically]
- **Plain CSS, no preprocessor:** Keeps the build pipeline simple for a site of this complexity. No npm, no Webpack, no compilation step required to work on the project.
- **jQuery retained:** WooCommerce, Slick, and Bootstrap all depend on or work well with jQuery. No reason to introduce a second JS framework.

---

## Client Notes

- **Contact:** <!-- TODO: confirm with Ben -->
- **Communication preferences:** <!-- TODO: confirm with Ben -->
- **Retainer / billing status:** <!-- TODO: confirm with Ben -->
- **Known preferences:** Brand is named after the Swedish fika tradition — the warmth and unhurriedness of that concept should inform all copy and design decisions.

---

## Session History

<!-- Maintained by Claude Code at the end of working sessions. Do not edit manually. -->
<!-- Ask Claude to update this block with: "Update MEMORY.md Session History with what we completed today." -->
