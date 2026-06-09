---
name: performance
description: Performance-first workflow for every Nexter Blocks page build. Trigger BEFORE any other `nexter-blocks/*` ability — including `nexter-blocks/get-image-to-page-skill` and `nexter-blocks/inspect-page` — whenever the user asks to create, edit, recreate, or refine a Gutenberg page using Nexter Blocks. Enforces an image-weight budget, a font-family cap, animation limits, lazy-loading defaults, and Core Web Vitals (LCP, CLS, INP) discipline.
---

# Performance-first Nexter Blocks workflow

You are about to build or modify a WordPress page with Nexter Blocks abilities. **Performance is a first-class requirement, not a polish step.** A page that "looks right" but ships 8 MB of images, four font families, and scroll animations on every block is broken — even if the screenshot matched.

**Order of operations in every session:**

1. `nexter-blocks/get-performance-skill` (this guide). Call it first.
2. `nexter-blocks/inspect-page` — only if the user gave a URL.
3. `nexter-blocks/get-image-to-page-skill` — only if the user gave an image/mockup.
4. `nexter-blocks/add-tpgb-*` — only after Phase 1 and Phase 2 below pass.

If you skip step 1 you will hit problems users do not see in the editor: poor LCP on the hero, layout shift while web fonts load, slow INP when too many blocks have animations, and oversized payloads from ad-hoc image URLs.

---

## Phase 1 — Set the budget BEFORE the build

Pick the page type and pin a budget. Do not guess attributes until this is written down.

| Budget | Marketing/landing | Blog post | Docs/internal |
|---|---|---|---|
| Total image weight on initial paint | ≤ 800 KB | ≤ 400 KB | ≤ 250 KB |
| Hero / LCP image weight | ≤ 200 KB | ≤ 150 KB | ≤ 100 KB |
| Distinct font families | **≤ 2** | ≤ 2 | 1 |
| Distinct font weights per family | ≤ 3 | ≤ 3 | ≤ 2 |
| Blocks with `scrollAnimation` | ≤ 6 (only above-the-fold or near-fold) | ≤ 3 | 0 |
| Blocks with continuous animation (loops, tickers, counters that re-fire) | ≤ 2 | 0–1 | 0 |
| Embedded videos auto-playing | 0 unless muted + `loading="lazy"` placeholder | 0 | 0 |
| Embedded maps | ≤ 1, lazy | 0 | 0 |
| Embedded social/iframe widgets | ≤ 2 | ≤ 1 | 0 |
| Custom fonts (non-Google) | 0 unless user owns the file | 0 | 0 |

**State the budget back to the user in your Phase-1 confirmation message.** They get one chance to relax or tighten it before the build starts.

### Page-type detector

If the user did not say, infer from the request:
- Mentions "landing", "marketing", "homepage", "hero", "pricing", "features" → **marketing**.
- Mentions "blog", "article", "post" → **blog**.
- Mentions "docs", "internal", "knowledge base", "admin" → **docs**.

---

## Phase 2 — Plan with performance in mind

Before any `add-tpgb-*` call, write a one-screen plan that already accounts for the budget.

### 2.1 Pick fonts ONCE

- Cap at **2 families**: one display/heading + one body. Use one if you can.
- Prefer the families already returned by `nexter-blocks/inspect-page`. Do not invent a third "for variety".
- Pass `fontFamily` and `fontType` on every block that has typography. Empty `fontType` is allowed but specifying it improves Google Fonts URL accuracy.
- Use `customFont` **only** when the user has confirmed they have the file installed.
- Reuse the same family across heading-title, heading, pro-paragraph, button, infobox, pricing-table, testimonials, accordion, etc. Do not re-pick per block.

### 2.2 Plan images BEFORE you place them

- Hero / first viewport image: ≤ 200 KB. If the source is bigger, tell the user and ask them to resize, OR use a placeholder (`https://placehold.co/{w}x{h}`) at the actual rendered dimensions.
- All in-flow images below the fold: WordPress native `loading="lazy"` is on by default — keep it. Don't disable it.
- Always pass intrinsic dimensions to image abilities when known (`imageWidth`, `imageHeight`, or the size key like `medium_large`) so the browser reserves space → CLS stays at 0.
- Do **not** place 3000-pixel hero photos. If the only URL the user has is huge, ask first.
- Background images on `container`: use sparingly. If the same image is also used as a hero `<img>`, drop the background and keep the `<img>`.
- For decorative shapes (SVG dividers, gradients, patterns): prefer container background gradients over uploaded PNG/JPG.

### 2.3 Plan animations like you plan ad budget

- Scroll/entrance animations: **at most one per visible row**, and only for marketing pages.
- Continuous animations (counters that re-trigger, tickers, draw-svg loops, flipboxes auto-flipping): cap at 2 on the whole page.
- `nexter-blocks/add-tpgb-stylist-list` (animated text loop), `add-tpgb-draw-svg`, `add-tpgb-countdown`: each one of these counts as a continuous animation.
- Set `animDuration` to `normal` (not `slow`); set `animEasing` to `ease-out` or `ease`. Avoid `ease-in-out` for entrances — it delays perceived paint.
- Below-the-fold blocks: no `scrollAnimation` unless the section is genuinely persuasive (testimonial reveal, pricing emphasis). Do not animate footers, FAQs, or boilerplate.

### 2.4 Plan embeds like cold-start cost

- `add-tpgb-video`: prefer `videoPopup=true` so the iframe only loads on click. If autoplay is required, set `muted=true` and `loading=lazy` patterns; provide a fallback poster image.
- `add-tpgb-google-map`: only one per page, only if the user actually has an API key configured.
- `add-tpgb-social-embed`: third-party iframes are heavy; cap at 2 per page.
- `add-tpgb-form-block` with reCAPTCHA / external service: include the cost in the budget.

### 2.5 Avoid block bloat

- Don't wrap a single heading in a container just to have a container. If only the heading exists, the heading's own `globalMargin` is enough.
- Don't use `heading-title` when plain `heading` works — `heading-title` carries separator/sub-heading layout overhead.
- Reuse a button preset (`add-tpgb-button-preset-crud`) instead of stamping per-block colors on 12 buttons. Presets keep CSS slim and let the user re-skin in one place.
- For FAQ-like content: use `accordion` (one block, multiple items) instead of 8 stacked `infobox` blocks.

### 2.6 Mobile-first sizing

- Pass `tTypoSize.md` (desktop) but also let the editor's responsive defaults apply for `sm`/`xs`. Don't force giant heading sizes on small screens.
- For containers with `globalPadding`, scope big values to `md` only when possible — leave `sm`/`xs` defaulted so mobile doesn't get desktop-scale air.
- For grids: pick column counts that already work on tablet/mobile (`columnsDesktop=3, columnsTablet=6, columnsMobile=12` style). Don't ship a 4-column grid that smashes on mobile.

---

## Phase 3 — Build with the budget on screen

When you call `add-tpgb-*` abilities, keep the budget visible to yourself. Three running tallies as you build:

- **Image bytes** — sum of every `imageUrl` weight you've placed (estimate when unknown, ask when uncertain).
- **Animation count** — increment on every block where you set `scrollAnimation` or a continuous animation attribute.
- **Font families** — list of distinct `fontFamily` values you've passed.

If the next block would push any tally past the budget:

1. Stop.
2. Ask the user one short question: *"Adding `<block>` will push us over the `<budget>` budget for this `<page-type>` page. Do you want to (a) drop it, (b) drop a heavier earlier block, or (c) raise the budget?"*
3. Wait for the answer. Don't quietly raise the budget yourself.

### Hard rules during build

1. **Always pass `post_id`.** Required.
2. **Always pass `fontFamily`** on typography blocks — empty leads to FOUT and inconsistent fallback chains.
3. **Don't pass `loop=true` + `autoPlay=true`** on `add-tpgb-video` unless `muted=true`. Browsers block it and the user sees a frozen frame.
4. **Don't enable `messy columns`, `swiperEffect`, and `scrollAnimation` together** on the same block — they all schedule layout work in the LCP window.
5. **Don't stack three CTAs** (`add-tpgb-button-core` with 3+ buttons) above the fold. Two max.
6. **Don't set custom fonts** (`customFont`) without user confirmation. The font file may not exist on the site.
7. **Don't import the same Google Font twice.** Reusing the family name across blocks is free; re-declaring weights elsewhere is wasted.

---

## Phase 4 — Verify against Core Web Vitals

After the build, before declaring "done":

1. **LCP** — what's the largest above-the-fold element? If it's an `add-tpgb-image` or a container with `globalBg` image: confirm the asset weight is ≤ the budget for the page type. If the LCP element has a `scrollAnimation`: remove it. LCP must paint, not animate in.
2. **CLS** — every image, video, embed needs reserved space. If you couldn't pass dimensions, note it in the summary so the user can fix. No `align=full` images without a fixed aspect ratio.
3. **INP** — count the blocks with continuous animation, swiper, accordion auto-open, countdown, draw-svg. If more than 3 are above the fold, recommend collapsing.
4. **Web fonts** — confirm only the planned families are referenced. If `inspect-page` showed three families on the source URL, you should still cap your output at two unless the user explicitly opted in.
5. **Third-party iframes** — confirm none auto-load above the fold (video popup, map lazy, social embed lazy).

### Final report to the user

End with a one-screen summary in this shape:

> **Built with these performance choices:**
> - Page type: `<marketing|blog|docs>`
> - Fonts: `<Family1>` (display) + `<Family2>` (body) — 2 families, 5 weights total
> - Hero image: `<filename>` — ~`<size>` KB
> - Animations: `<n>` scroll, `<m>` continuous
> - Lazy-loaded: `<videos|maps|embeds you deferred>`
> **Budget status:** within budget on images / fonts / animations.
> **Open the editor and tell me anything off vs. the design — I'll trade something out instead of stacking more on top.**

---

## Common performance mistakes to avoid

- **Picking a third font** because "the headings need more character". Two is the cap.
- **Setting `autoPlay=true` + `controls=true` + `loop=true`** on a 4-MB MP4 hero. Use a poster + popup instead.
- **Animating the LCP heading.** It delays paint by the duration of the animation.
- **Using `globalBgHover` on every container** with image fades. Each one re-decodes the image on hover.
- **Adding `dark-mode` toggle "for completeness"** when the user didn't ask. It ships extra CSS + JS.
- **Stamping per-block button colors** instead of one preset. Bigger CSS, no central edit.
- **Embedding a Google Map "to look complete"** when the section is just an address. A heading + paragraph is cheaper.
- **Passing oversized image URLs** the user gave you, without asking. If it's > 500 KB, confirm before placing.
- **Forgetting that `inspect-page` already returned the right fonts** and re-deciding from the screenshot. Trust the inspector.
- **Skipping this skill** because "the page is small". Small pages hit the budget too — they just have less margin.
