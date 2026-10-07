# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- [inc/woocommerce.php, functions.php] WooCommerce integration module: theme support with gallery zoom/lightbox/slider, the Fika WC skin enqueued only on WC pages, and WC core CSS limited to WC pages. Includes workshop product helpers (`bonsai_is_workshop_product()`, `bonsai_wc_workshop_when()`), "X spots remaining" in place of WC's stock line, a "Book your place" button label, a "More workshops" related heading, and the workshop date on shop cards. The date/time and location are also carried into the basket, checkout and order line item meta
- [woocommerce.php] Root WooCommerce wrapper template, so the shop, archives and single products render (previously `index.php` would have routed them through page_builder and output nothing)
- [woocommerce/content-single-product.php, template-parts/woocommerce/workshop-product.php] Products in the `workshops` product category render a workshop layout styled like hero_split: content 2/3 left, featured image 1/3 right bleeding to the edge with the organic clip (image above and unclipped on mobile, no gallery/lightbox). Content: title, price, date/time/location, spots remaining and description (matching workshop_details_module), with the WC add-to-basket form and express payment buttons underneath, then optional page_builder modules and related workshops. Product JSON-LD is still output. Other products keep WC's standard layout
- [template-parts/woocommerce/workshop-product.php, assets/js/workshop-map.js, assets/css/woocommerce-workshop.css] The workshop location is now a "View map" trigger that opens a native `<dialog>` pop-up with a Google Maps embed (no API key) and an "Open in Google Maps" link. The iframe only loads on first open. Without JS or `<dialog>` support, the trigger links straight to Google Maps. The script is enqueued on workshop products only
- [inc/waiting-list.php, assets/js/workshop-waiting-list.js, functions.php] Waiting list for sold-out workshop products: an email field, a required consent tick box and a submit button, sent over AJAX with an admin-post.php fallback. Protected by a nonce, honeypot and per-IP rate limit. Each sign-up is stored on the product (`_bonsai_waiting_list`) and emails the Theme Settings address (Reply-To the visitor) with the workshop title, date/time, email and total. Adds a "Waiting list" box on the product edit screen (list, BCC-all link, clear option), GDPR personal data exporter/eraser, and suggested privacy policy text
- [acf-json/group_64525a8b8885f.json] Added "Workshop Waiting List Email" to Theme Settings (falls back to the admin email)
- [tests/manual/workshop-waiting-list.md] Manual acceptance checklist for the waiting list
- [acf-json/group_fika_market_workshop_details.json, group_fika_page_builder.json] Added location rule: `product` in product_cat `workshops`
- [assets/css/woocommerce.css, assets/css/woocommerce-workshop.css] Fika styling for the shop grid (matches class_grid cards), single product, notices, buttons, forms, basket, two-column checkout, payment methods, order received and My Account
- [template-parts/header/site-header.php, assets/css/core/header.css] Added a large centred-logo `.site-header-hero` block, shown only on the front page above the standard sticky nav — pulls the Theme Settings "Site Header Logo" image field, falls back to the text brand mark. Non-sticky, no JS: scrolls away naturally as the visitor scrolls, revealing the existing compact sticky header
- [acf-json/group_fika_page_builder.json] Added four new page_builder layouts: banner_module (background image + title + subheader + single CTA), media_module (full-width image or YouTube/Vimeo video via ACF oEmbed), event_details_module and workshop_details_module (auto-pull date/time/location/price/description/booking from the current Market/Workshop post's own fields, each with an optional no-API-key Google Map embed)
- [template-parts/modules/banner_module.php, media_module.php, event_details_module.php, workshop_details_module.php] Added matching PHP templates
- [assets/css/modules/banner_module.css, media_module.css, event_details_module.css, workshop_details_module.css] Added matching module styles
- [acf-json/group_fika_page_builder.json] Added `market` and `workshop` to the page_builder field group's location rules (previously `page` only) — Page Builder is now available when editing a Market or Workshop post

### Changed
- [template-parts/modules/event_details_module.php, assets/css/modules/event_details_module.css] Restyled event_details_module to match the workshop product layout: the heading is now a script kicker above the post title, followed by price, date/time, a location line with a pin icon, the description and the booking button. Content sits left at 2/3, with the image right at 1/3 using its own organic clip (`#organicEvent`). The image is the ACF `image` field, falling back to the featured image. On mobile the image stacks above the content with no clip. The optional map is now full width below the content instead of beside it
- [acf-json/group_fika_page_builder.json, inc/woocommerce.php, template-parts/modules/class_grid.php] class_grid can now also feature WooCommerce products. The picker only lists products in the `workshops` category (`bonsai_wc_class_grid_workshop_products_only()`). Product cards take their image, price, sold-out state and Book link (to the product page) from WooCommerce via `bonsai_wc_class_card_data()`, and their date/time/location from ACF
- [acf-json/group_fika_market_workshop_details.json, inc/helpers.php] `event_date` is no longer required, and a free-text "Date Note" field (`event_date_note`, e.g. "Date to be arranged") has been added next to it. The new `bonsai_get_event_date_display()` helper combines the two and is used everywhere the date shows: the workshop product page, basket/checkout/order meta, class_grid, event_details_module and workshop_details_module
- [acf-json/group_fika_market_workshop_details.json, inc/acf.php] `event_time` changed from a time picker to free text (e.g. "10am (4½ hours)"). Existing "H:i:s" values are reformatted on output as "10:00 am" by `bonsai_format_legacy_event_time()`, so they don't need re-entering
- [template-parts/content/content-page.php] Cart, Checkout and My Account pages now render their classic WooCommerce shortcode instead of page_builder (page_builder never outputs `the_content`, so these pages were blank)

### Fixed
- [template-parts/content/content-single.php, content-campaign.php] Fixed stale `page-builder.php` includes (old hyphenated filename) — corrected to `page_builder.php`

### Removed
- [template-parts/header/site-header.php] Removed a dead prototype `<header>` block nested inside `<main>` — a broken `<img>` pointing at a non-existent `img/header-banner.png`, an unstyled `.hamburger` button, and an unclosed `<div class="mobile-overflow">` that had `overflow: hidden` silently applied to the rest of every page since it was never closed anywhere in the theme. `main.js`'s matching `.hamburger`/`.mobile-overflow` jQuery handlers (targeting `#slide-div`/`#slide-room-bookings-canvas`, IDs that don't exist in this theme) are now fully dead code but were left in place — not in scope for this change

- [acf-json/group_fika_page_builder.json] Consolidated all five module layouts (hero_split, services_row, class_grid, product_grid, story_block) into a single ACF Flexible Content field group assigned to post_type == page
- [template-parts/modules/slider_module.php] Added Slider module: fade transition, 800px max-height, repeater of slides (image, optional title/content/CTA) with a left-aligned 60%-width overlay (title top, content bottom)
- [assets/css/modules/slider_module.css] Added slider module styles, including mobile breakpoints
- [assets/js/main.js] Added Slick init for `.slider-module-track` (fade, autoplay 6s, arrows + dots)
- [template-parts/modules/contact_module.php] Added Contact module: full-width title, then 50/50 split of WYSIWYG content (left) and a form shortcode field (right)
- [assets/css/modules/contact_module.css] Added contact module styles — stacked on mobile, 50/50 from 900px, raised card styling on the form column
- [template-parts/modules/split_content_module.php] Added Split Content module: full-width title, then a left/right split where each side is independently Text+CTA, Image, or Video, with reverse-order and vertical-align (top/center) options
- [assets/css/modules/split_content_module.css] Added split content module styles, including reversed order and vertical-centering modifiers
- [acf-json/group_fika_market_workshop_details.json] Added "Market / Workshop Details" field group (post_type == market OR workshop): Date, Time, Price, Location, Book Link, Spots Remaining, Sold Out, Image, Description
- [template-parts/modules/faq_accordion.php] Added FAQ Accordion module — repeater of question/answer pairs using native `<details>`/`<summary>`, no JS
- [template-parts/modules/testimonials_carousel.php] Added Testimonials Carousel module — repeater of quotes rotated with a Slick fade carousel
- [assets/js/main.js] Added Slick init for `.testimonials-carousel-track` (fade, autoplay 7s, dots, adaptive height)
- [template-parts/modules/stats_strip.php] Added Stats Strip module — auto-fit row of 2–5 number/label stats
- [template-parts/modules/map_location.php] Added Map / Location module — address, phone, opening hours table, and a directions link (no embedded map, so no Cookiebot wrapping needed)
- [template-parts/modules/cta_banner.php] Added CTA Banner module — full-width heading + button on an accent-colour or image background
- [template-parts/modules/logo_strip.php] Added Logo Strip module — "as featured in" press logos row, greyscale until hover
- [assets/css/modules/] Added faq_accordion.css, testimonials_carousel.css, stats_strip.css, map_location.css, cta_banner.css, logo_strip.css
- [assets/fonts/glacial-indifference/] Added folder + README for self-hosting the licensed Glacial Indifference font files (not on Google Fonts)

### Changed
- [assets/css/core/additions.css] Site-wide typography swap: `--font-heading` changed from Varela Round to Poppins (headings, nav, buttons), `--font-body` changed from Inter to Glacial Indifference (general site text, self-hosted `@font-face`, falls back to system-ui until font files are supplied)
- [assets/css/core/additions.css] `--ink-strong` (headings/buttons) changed from `#1A1A1A` to `rgba(0, 0, 0, 0.7)`; `--ink` (body text) changed from `#2B2B2B` to `#999999`
- [assets/css/core/additions.css] `.btn` font-family changed from `--font-body` to `--font-heading` (Poppins) per updated brand direction
- [inc/assets.php] Google Fonts enqueue updated to Poppins + Caveat (Varela Round and Inter removed)
- [DESIGN.md] Updated typography section and CSS variable reference to match

### Changed
- [acf-json/group_fika_page_builder.json] Class Grid's "Classes" field changed from a manual repeater to a Relationship field (max 3) selecting `market`/`workshop` posts
- [template-parts/modules/class_grid.php] Class Grid cards now pull title, image, date/time/price, location, book link, and sold-out state live from the related post instead of manual entry; sold-out posts render a disabled "Sold Out" label instead of a Book button
- [assets/css/modules/class_grid.css] Added sold-out card styling (greyscale image, disabled label) and a location line; card link/hover styles moved from `.class-card` to a new inner `.class-card-link` since the card is no longer always an anchor
- [inc/assets.php] Corrected Google Fonts URL from Cormorant Garamond to the Fika spec fonts: Varela Round + Inter (400, 500, 600)

### Fixed
- [inc/assets.php] Fixed FontAwesome stylesheet enqueue pointing at `fontawesome.min.cs` (truncated extension, 404) — corrected to `fontawesome.min.css`
- [README.md, MEMORY.md, llm-instructions.txt] Corrected stale references to the original five-module, hyphenated-name build state (`hero-split.php` etc.) and the old `page-builder.php` filename — docs now match the current 14 underscore-named modules living in `template-parts/modules/page_builder.php`
- [MEMORY.md] Corrected ACF Field Groups table — all 14 page_builder layouts live in one field group (`group_fika_page_builder.json`), not one file per module; added the Market/Workshop Details, Theme Settings, and Humans.txt groups that were missing from the table
- [README.md, MEMORY.md, llm-instructions.txt] Updated font references from the outdated Varela Round/Inter (and a stale Cormorant Garamond flag) to the current Poppins + self-hosted Glacial Indifference + Caveat setup

### Removed
- [acf-json/] Deleted four separate ACF field group files (group_fika_services_row, group_fika_class_grid, group_fika_product_grid, group_fika_story_block) — layouts now live inside group_fika_page_builder
- [MODULES.md, HEADER-FOOTER-INTEGRATION.md] Removed — stale one-off build handoff notes from the theme's initial build session, describing an early five-module state with an enqueue method the theme no longer uses. Current content lives in README.md and MEMORY.md.

### Security
-

---

## [1.0.0] - 14-05-2026

### Added
- [functions.php] Modular loader architecture — all theme functionality split into discrete inc/ files
- [inc/theme-setup.php] Theme supports registered: title-tag, post-thumbnails, automatic-feed-links, responsive-embeds, html5
- [inc/theme-setup.php] Four nav menus registered: Primary, Mobile, Footer, Legal
- [inc/assets.php] Enqueue pipeline for Bootstrap 5 (custom build), Slick carousel, FontAwesome 6 Free, Google Fonts (Varela Round + Inter), and main.js
- [inc/assets.php] Conditional SiteMinder IBE booking widget enqueue — loads only on is_singular('accommodation'), deferred
- [inc/acf.php] ACF options pages: Site Settings and Humans.txt Editor
- [inc/acf.php] Humans.txt writer — acf/save_post hook writes 'humans_txt_content' field value to /humans.txt on options save
- [inc/acf-json.php] ACF Local JSON save and load paths configured to /acf-json/
- [inc/acf-defaults.php] Auto-population of page_builder field on new posts
- [inc/helpers.php] bonsai_kses_iframe() helper for safe iframe/embed output from ACF fields
- [inc/helpers.php] bonsai_get_trimmed_excerpt() and bonsai_get_trimmed_content() helper functions
- [inc/gutenberg.php] Gutenberg block editor disabled sitewide
- [inc/post-labels.php] 'Posts' post type renamed to 'News' throughout the admin
- [inc/security.php] Security hardening: version string removal, feed protection, and related cleanup
- [inc/cleanup.php] Emoji scripts and unused WordPress head items removed
- [inc/lazy-load.php] Lazy loading attribute added automatically to images
- [inc/webp.php] WebP image upload support added
- [template-parts/modules/page-builder.php] Child-aware ACF Flexible Content dispatcher — loads module CSS and PHP child-first, falls back to parent
- [template-parts/modules/hero-split.php] Hero Split module — organic split layout, text left, clipped image right, optional feature cards
- [template-parts/modules/services-row.php] Services Row module — three-column service blocks for Classes, Shop, and Gift Vouchers
- [template-parts/modules/class-grid.php] Class Grid module — cookery class cards with booking CTA
- [template-parts/modules/product-grid.php] Product Grid module — featured WooCommerce product cards
- [template-parts/modules/story-block.php] Story Block module — blockquote/testimonial with decorative divider, variable background style
- [acf-json/] Five ACF field groups added for all Flexible Content layouts: hero_split, services_row, class_grid, product_grid, story_block
- [assets/css/core/] Global stylesheet suite: base.css (reset, CSS variables, base elements), header.css, footer.css, additions.css
- [assets/css/modules/] Per-module CSS files for all five layouts, loaded on-demand by page-builder.php
- [assets/css/] Bootstrap 5 custom build (bootstrap-custom.min.css) and Slick carousel stylesheet
- [templates/] Root-level templates: page.php, single.php, single-campaign.php
- [template-parts/] Header, footer, content, and snippet partials
- Fika Exeter design system implemented: cinnamon accent (#6E4518), warm surface (#F5F3F2), Varela Round headings, Inter body, 14px border-radius

### Changed
- N/A — first release.

### Fixed
- N/A — first release.

### Removed
- N/A — first release.

### Security
- All template output escaped using esc_html(), esc_attr(), esc_url(), wp_kses_post(), or bonsai_kses_iframe() as appropriate
- No unescaped ACF field output in any module template
