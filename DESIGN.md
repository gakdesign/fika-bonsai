# Fika Exeter — Web design specification

**Project:** Ecommerce + cookery class booking  
**Direction:** Minimal structure, cosy Scandinavian flair — informed by [Nordic Kitchen Stories](https://www.nordickitchenstories.co.uk/) editorial warmth and restraint  
**Reference capture:** `bonsai_base_theme/design.md` (NKS audit)  
**Brand assets:** Logo and cinnamon-roll mark (use high-resolution exports in the theme; source files may live under project `assets/` after import)

---

## 1. Positioning

Fika sits between **quiet premium** and **approachable kitchen-table** — not a glossy high-street chain, not a cluttered recipe blog. The UI should feel calm enough to browse slowly (true to *fika*), while ecommerce and booking flows stay **obvious and trustworthy**.

| NKS pattern | Fika adaptation |
|-------------|-----------------|
| Text-first CTAs | Keep **soft** CTAs on editorial blocks; use **one clear primary button** per viewport for shop and booking (conversion need). |
| Imagery carries colour | Same: neutrals in the chrome; warmth from photography and the logo mark. |
| Generous whitespace | Same; avoid dense product grids — favour fewer, larger tiles. |
| Personal tone | Copy in first person where it fits; booking confirmations friendly and plain. |

---

## 2. Colour system

Ground the site in the logo’s monochrome base, then add **one** restrained accent so interactive states are visible without loud UI colour.

### Accent decision: cinnamon

**Chosen accent:** warm **cinnamon brown** — matches the kanelbulle mark, reads “bakery warm” without competing with food photography, and differentiates Fika from NKS’s forest-green icon while keeping the same minimal layout discipline.

Use **only** this family for interactive emphasis (links, focus, primary buttons, active step, calendar “today”, selected chips). Decorative greens in photography or illustration are fine; do not add a second UI accent colour.

| Token | Role | Value |
|-------|------|--------|
| `--surface` | Page background | `#F5F3F2` (warm off-white; adjust to pure `#FAFAF8` if photography skews yellow) |
| `--surface-raised` | Cards, modals, sticky bars | `#FFFFFF` at 85–100% opacity, or solid `#FFFCFA` |
| `--ink` | Body text | `#2B2B2B` |
| `--ink-strong` | Headings, prices | `#1A1A1A` |
| `--muted` | Meta, captions, disabled | `#6B6B6B` |
| `--line` | Dividers, input borders | `#E6E4E1` |
| `--accent` | Primary links, focus ring, icon emphasis | `#6E4518` |
| `--accent-hover` | Link hover, button hover, pressed | `#543210` |
| `--on-accent` | Text and icons on solid accent fills | `#FFFCFA` |
| `--accent-soft` | Hover row backgrounds, subtle chips, “selected” wash | `color-mix(in srgb, var(--accent) 12%, var(--surface))` |

**Rules**

- Do not introduce a second bright accent for marketing badges; use typography and spacing instead.
- **Primary buttons:** solid `--accent` background, `--on-accent` label; hover shifts to `--accent-hover` (keep label `--on-accent`). Verify contrast in QA; if any label weight/size falls below AA, darken `--accent` slightly rather than adding a new colour.
- Product and class imagery should be slightly warm in grade; avoid heavy saturation in the UI.

---

## 3. Typography

Mirror the Scandinavian pairing from the NKS reference: **rounded friendly display + neutral workhorse body**.

| Role | Font | Notes |
|------|------|--------|
| Display / H1–H3 | **Varela Round** | Matches the soft, lowercase “fika” logo personality. No real bold — use size and letter-spacing (`0.02em` on large headings) for hierarchy. |
| Body, UI, nav, forms | **Inter** | 400 body; 500–600 for buttons and active nav. |

**Google Fonts embed**

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Varela+Round&display=swap" rel="stylesheet">
```

**Scale (desktop baseline)**

- H1: clamp(2rem, 4vw, 2.75rem), line-height ~1.15  
- H2: clamp(1.5rem, 2.5vw, 2rem)  
- Body: 16–17px, line-height 1.65–1.75  
- Small / labels: 13–14px, `--muted`  

**“Exeter” and locality** — set in Inter, uppercase optional for tiny labels only; default **sentence case** for a softer feel.

---

## 4. Layout & rhythm

- **Max width:** 1100–1200px for reading and catalogues; checkout can narrow to ~640px for focus.  
- **Spacing scale:** 4 / 8 / 12 / 16 / 24 / 32 / 48 / 64px — prefer 24+ between major sections.  
- **Corners:** 12–16px on cards and inputs; pills for tags (e.g. “Beginner”, “Half day”).  
- **Shadows:** almost none; if needed, `0 8px 24px rgba(0,0,0,0.06)` on elevated panels only.  
- **Organic flair (optional):** one hero or homepage block may use a large-radius curve or soft mask on imagery (see coffee-shop mood reference) — use **once** per page so it stays minimal.

---

## 5. Header & navigation

Three layers (NKS-inspired, simplified for commerce):

1. **Optional slim bar** — email signup one-liner or “Next class: [date]” text, Inter small.  
2. **Main bar** — wordmark + “exeter” lockup (SVG), primary nav: **Classes**, **Shop**, **Gift vouchers** (if applicable), **About**, **Contact**. Cart icon + count (discrete, not loud).  
3. **Mobile** — hamburger with labelled “Menu”; cart remains visible if possible.

Search: icon in header opening a minimal full-width or overlay field — Inter, large hit area.

---

## 6. Homepage (suggested blocks)

1. **Hero** — warm lifestyle or kitchen shot; headline + one line of promise; **primary CTA** “Browse classes” + text link “Shop pantry picks”.  
2. **Trust strip** — three short items (small icons or words only): e.g. small groups, dietary notes, location.  
3. **Featured classes** — 2–3 large cards (image, title, date range, price from, **Book** as filled accent button).  
4. **Shop spotlight** — 3–4 products, square crops, “View all” as text link.  
5. **Story** — short personal block (blockquote styling in Inter italic optional).  
6. **Instagram / gallery** — optional; lazy-load; same neutral frame as NKS.  
7. **Footer** — logo, short blurb, newsletter, policies, payment icons if needed.

---

## 7. Cookery booking UX

- **Archive:** filter chips (Inter, pill) for month, skill level, theme — no heavy faceted UI.  
- **Class single:** title (Varela Round), date/time/place (Inter), capacity indicator, what’s included, cancellation policy in a collapsible “Details”. **Sticky booking panel** on desktop: date selector if recurring sessions, quantity, total, primary **Add to basket** or **Book now**.  
- **Calendar views:** prefer list + month toggle; keep borders `--line`, today ring `--accent`.  
- **Empty states:** illustration optional (cinnamon mark at low opacity); copy warm and short.

---

## 8. Ecommerce patterns

- **PLP:** generous grid (max 3 columns desktop); quick add only if it doesn’t clutter — otherwise clear product cards linking to PDP.  
- **PDP:** large image, minimal tabs; accordion for ingredients/allergens/shipping. Primary **Add to basket** full-width on mobile.  
- **Basket / checkout:** stepped indicator (Cart → Details → Payment); no dark patterns; guest checkout prominent.  
- **Buttons:** filled `--accent` for primary; ghost or text for secondary — still calmer than retail neon, but clearer than NKS’s link-only approach where money moves.

---

## 9. Imagery

- Natural light, wood/linen/ceramic props, overhead and 3/4 angles — same language as NKS.  
- **Classes:** real students’ hands and finished dishes where policy allows; avoid stock “smiling chef” clichés.  
- **Crop ratios:** 1:1 for grids; 4:5 or 3:2 for hero.  
- **Alt text:** descriptive, not keyword-stuffed.

---

## 10. Iconography & illustration

- Thin stroke icons (cart, calendar, clock, map pin) — single weight, rounded caps.  
- **Cinnamon-roll mark:** favicon, loading state, empty cart; keep usage sparse so it stays special.

---

## 11. Motion

- 150–220ms ease on hovers; respect `prefers-reduced-motion`.  
- No auto-playing video with sound; subtle parallax only if performance allows.

---

## 12. Accessibility

- Visible focus rings (2px `outline` using `--accent`, or `outline-offset: 2px` on dark thumbnails).  
- Contrast: body text on `--surface` meets WCAG AA; **filled** accent controls use `--on-accent` on `--accent` / `--accent-hover` — re-check after any photography-led tweak to surface cream.  
- Skip link, logical heading order, form errors with text (not colour alone).

---

## 13. CSS variable starter

```css
:root {
  --surface: #F5F3F2;
  --surface-raised: #FFFCFA;
  --ink: #2B2B2B;
  --ink-strong: #1A1A1A;
  --muted: #6B6B6B;
  --line: #E6E4E1;
  /* Accent: cinnamon — single UI emphasis family */
  --accent: #6e4518;
  --accent-hover: #543210;
  --on-accent: #fffcfa;
  --accent-soft: color-mix(in srgb, var(--accent) 12%, var(--surface));
  --font-heading: 'Varela Round', sans-serif;
  --font-body: 'Inter', sans-serif;
  --radius: 14px;
  --max-w: 72rem;
}
```

---

## 14. Handoff checklist

- [ ] Logo SVG (light and dark if header ever inverts)  
- [ ] Favicon from cinnamon-roll mark  
- [ ] Typography embedded or self-hosted with correct weights  
- [ ] `Event` / `Product` / `LocalBusiness` JSON-LD for classes and location  
- [ ] OG images per template  
- [ ] Newsletter and booking confirmation copy in brand voice  

---

*Specification for Fika Exeter — Bonsai / design workspace. NKS structural and typographic notes derive from the theme design reference dated 13/05/2026.*
