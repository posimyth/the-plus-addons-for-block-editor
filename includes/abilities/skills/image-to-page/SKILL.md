---
name: image-to-page
description: Convert a layout image (and optional text instructions) into a Nexter Blocks Gutenberg page by orchestrating the plugin's MCP abilities. Trigger when the user provides a screenshot/mockup of a webpage and asks to "build", "recreate", "match", "convert", or "make this in WordPress" — and the Nexter Blocks MCP server (`nexter-blocks/*` abilities) is connected.
---

# Image-to-page workflow

You are reproducing a design from an image into a WordPress post using Nexter Blocks abilities (the `nexter-blocks/*` MCP tools). Fidelity matters more than speed. Skipping the analysis phase is the #1 cause of poor matches — don't.

---

## Phase 0 — If a URL was given, the inspector is your ground truth

**When the user's prompt contains a URL, call `nexter-blocks/inspect-page` with that URL before anything else.** Its output is not a "hint" or a "seed" — it is the source of truth for everything it covers, and you must pass its values verbatim into the blocks you build. This is the single biggest cause of the page you generate looking nothing like the URL: skipping this call, or calling it and then "rounding" the values to something familiar.

What the inspector returns and how it must be used:

| Inspector field | Becomes | Verbatim? |
|---|---|---|
| `fonts[].family` / `.weights` | `fontFamily` and weight attrs on every typographic block | **Yes — exact string** |
| `colors[]` (hex / rgb, most-frequent first) | bg / text / accent / border on the matching blocks | **Yes — exact code** |
| `headings.h1` / `h2` / `h3` | `title` / `content` on heading blocks, in order | **Yes — exact text** |
| `images[]` | `imageUrl` / `mediaUrl` on image blocks | **Yes — full absolute URL** |
| `title` / `description` / `ogImage` | metadata, fallback hero copy | **Yes** |

Do not approximate. If `colors[0]` is `#0B1320`, write `#0B1320` — not `#0F172A` because it "looks dark navy." If `fonts[0]` is `Manrope` weight `600`, write `Manrope` and `600` — not `Inter` weight `700` because it's "close." Substitution to a familiar value is the failure mode this skill exists to prevent.

When a URL was given, Phase 1's spec is filled **from the inspector**. If the user also gave a screenshot, use the screenshot **only** for things the inspector cannot return — section layout, column counts, spacing rhythm, border radius, shadow strength. Never let the screenshot override a colour, font, weight, or heading string the inspector already provided.

If `inspect-page` errors or returns empty fields, tell the user explicitly before falling back to image-only estimation. Don't silently invent.

---

## Phase 1 — Build the style spec (no tool calls beyond Phase 0)

**Source-of-truth order, per field: inspector output → screenshot reading → ask the user. Never invent.**

This means:

- **URL was given (Phase 0 ran):** palette, fonts, weights, heading text, and image URLs come from the inspector verbatim. The screenshot fills *only* what the inspector cannot return — section list, column counts, spacing/radii/shadow estimates, body copy, button labels. Do not re-derive colours or fonts from the screenshot when the inspector already gave them to you.
- **No URL (image only):** every field is read from the screenshot. Be specific and use real hex / px values; "dark blue" and "around 50px" are not specific enough.
- **Either way:** if a field has no source and you can't read it confidently, leave it blank in the spec and ask the user. Do not paper over gaps with plausible-looking defaults.

Then do the breakdown below. Items 1, 4, 5, 7 always use the screenshot. Items 2, 3, 6 use the inspector when a URL was given, otherwise the screenshot.

1. **Sections, top to bottom.** List them by name (header, hero, features, testimonials, pricing, FAQ, CTA, footer, etc.).
2. **Palette.** Hex codes for: page background, primary text, secondary text, accent, button bg, button text, borders. From `inspector.colors[]` when available — pass them through unchanged. Otherwise estimate from the screenshot, using real hex (`#0F172A`), not "dark blue".
3. **Typography.** Family + weights from `inspector.fonts[]` when available — pass through unchanged. Sizes / line-heights estimated from the screenshot per section: heading tag (h1/h2/h3), size in px, weight, line height. Body text size + line height.
4. **Spacing.** Section vertical padding (e.g. 80px). Gap between siblings (e.g. 24px). Container max-width.
5. **Radii & shadows.** Border radius in px. Shadow strength (none / soft / strong; estimate offset/blur).
6. **Real text content.** Headings from `inspector.headings.h1/h2/h3` verbatim, in order. Body copy and button labels read from the screenshot. Do not invent or paraphrase either source.
7. **Assets.** Note any photos, icons, illustrations, logos. When a URL was given, map them to entries in `inspector.images[]` by reading the screenshot.

### Keep an internal style spec (for your own reference, not the user's)

After the breakdown, write down a compact JSON spec **for yourself** so you don't drift on numbers later. Don't show this JSON to the user. The shape is below — fill every slot with a value that traces to a concrete source (annotated in `« »`). The placeholder strings show *where each value comes from*, not what to write — replace them with the actual hex / px / family / weights from your source. Omit fields that don't apply.

```json
{
  "palette": {
    "bg":            "« inspector.colors[0] verbatim, OR estimated hex from screenshot »",
    "textPrimary":   "« inspector verbatim or screenshot estimate »",
    "textSecondary": "« inspector verbatim or screenshot estimate »",
    "accent":        "« inspector verbatim or screenshot estimate »",
    "buttonBg":      "« inspector verbatim or screenshot estimate »",
    "buttonText":    "« inspector verbatim or screenshot estimate »",
    "border":        "« inspector verbatim or screenshot estimate »"
  },
  "typography": {
    "primaryFont":   { "family": "« inspector.fonts[0].family verbatim »", "weights": "« inspector.fonts[0].weights verbatim »" },
    "h1":   { "size": "« px from screenshot »", "weight": "« from inspector.fonts weights, else screenshot »", "lineHeight": "« screenshot estimate »" },
    "h2":   { "size": "« px from screenshot »", "weight": "« from inspector or screenshot »",                "lineHeight": "« screenshot estimate »" },
    "h3":   { "size": "« px from screenshot »", "weight": "« from inspector or screenshot »",                "lineHeight": "« screenshot estimate »" },
    "body": { "size": "« px from screenshot »", "weight": "« from inspector or screenshot »",                "lineHeight": "« screenshot estimate »" }
  },
  "spacing":  { "sectionPadY": "« px from screenshot »", "elementGap": "« px »", "containerMaxWidth": "« px »" },
  "radius":   { "card": "« px »", "button": "« px »" },
  "shadow":   "« none | soft | strong »",
  "sections": ["« list of section names read from screenshot, top to bottom »"],
  "headings": { "h1": "« inspector.headings.h1 verbatim »", "h2": "« ... »", "h3": "« ... »" },
  "images":   ["« inspector.images[*] verbatim absolute URLs »"]
}
```

**The `« »` placeholders are *not* values to copy.** They tell you which source to read. If the inspector returned `colors[0] = "#0B1320"`, your spec's `palette.bg` is the literal string `"#0B1320"` — never `"#0F172A"`, never "navy", never anything else.

### Checkpoint 1 — confirm the style (in plain English, not JSON)

Tell the user what you saw, in normal language. **Never paste the JSON spec at them.** Example wording:

> Here's what I picked up from the image:
>
> - **Sections (top to bottom):** « list the section names from the screenshot ».
> - **Colors:** « describe the actual palette from the inspector / screenshot — name the role and quote the real hex code, e.g. "background `#0B1320` with white headings and a peach accent `#F58F75` for buttons" ».
> - **Typography:** « name the actual font family from `inspect-page` (or your closest match from the screenshot if no URL was given) and the heading sizes you read ».
> - **Layout feel:** « describe the spacing / max-width / shadow / radius you read from the screenshot in real numbers ».
>
> Does this match what you want, or do you want me to tweak any of it (colors, font, sizes, spacing, corners)?
>
> Also two things I need from you before I start:
> 1. **Which post should I write to?** (post ID)
> 2. **Do you have URLs for the images** in the design (hero photo, product shots, etc.), or should I drop in placeholders that you can swap later?

**Wait for the user's response. Do not move to Phase 2 until:**
- The user confirms the style (or gives edits — apply them and re-confirm in plain language).
- A `post_id` has been provided.
- The image-asset question is resolved (real URLs, placeholders, or skip).

If the user gives edits, restate the final picture in plain English so they can catch mistakes before you plan the structure.

---

## Phase 2 — Plan the block tree

Map every visual section to a `nexter-blocks/*` ability. Write the plan as a tree before building anything.

### Container rule (this is not optional)

**Every section starts with a `nexter-blocks/add-tpgb-container`.** No exceptions, even for a single-column full-width section — the container is the only block that gives you section padding, background color/image, and content max-width. Never put a `heading`, `image`, `pro-paragraph`, etc. directly at the page root.

**For multi-column layouts inside a section**, you need a second layer: `nexter-blocks/add-tpgb-container-inner`, one per column. The shape is always:

```
container               ← the section (bg, padding, max-width)
  ├─ container-inner    ← column 1
  │    └─ heading / image / icon-box / etc.
  ├─ container-inner    ← column 2
  │    └─ ...
  └─ container-inner    ← column 3
       └─ ...
```

Single-column sections skip `container-inner` and put content directly in the outer container.

A header with logo on the left + nav on the right? That's two `container-inner`s. A 3-up feature grid? Three `container-inner`s. A footer with 4 link columns? Four `container-inner`s. Don't try to skip the inner containers and "rely on flex" — without them, the columns will not exist as separate blocks and you can't style them independently.

### Block selection guide

| What you see | Ability |
|---|---|
| Full-width section / row wrapper | `nexter-blocks/add-tpgb-container` |
| Column inside a container | `nexter-blocks/add-tpgb-container-inner` |
| Styled heading | `nexter-blocks/add-tpgb-heading` |
| Heading with separator/sub-heading layout | `nexter-blocks/add-tpgb-heading-title` |
| Multi-paragraph rich body text | `nexter-blocks/add-tpgb-pro-paragraph` |
| Pull-quote | `nexter-blocks/add-tpgb-blockquote` |
| Single CTA button | `nexter-blocks/add-tpgb-button` |
| Two or more buttons grouped | `nexter-blocks/add-tpgb-button-core` |
| Photo / illustration | `nexter-blocks/add-tpgb-image` |
| Photo with hover/caption effect | `nexter-blocks/add-tpgb-creative-image` |
| Icon + heading + text card | `nexter-blocks/add-tpgb-icon-box` |
| Icon + text feature row | `nexter-blocks/add-tpgb-infobox` |
| Stat / counter ("10K+ users") | `nexter-blocks/add-tpgb-number-counter` |
| Pricing card | `nexter-blocks/add-tpgb-pricing-table` |
| Pricing feature list | `nexter-blocks/add-tpgb-pricing-list` |
| Testimonial with photo + quote | `nexter-blocks/add-tpgb-testimonials` |
| Team member grid | `nexter-blocks/add-tpgb-team-listing` |
| Tabs | `nexter-blocks/add-tpgb-tabs-tours` |
| Accordion / FAQ | `nexter-blocks/add-tpgb-accordion` |
| Annual/monthly toggle | `nexter-blocks/add-tpgb-switcher` |
| Flippable card | `nexter-blocks/add-tpgb-flipbox` |
| Form | `nexter-blocks/add-tpgb-form-block` + `add-tpgb-form-*-field` |
| Embedded video | `nexter-blocks/add-tpgb-video` |
| Map | `nexter-blocks/add-tpgb-google-map` |
| Social icons row | `nexter-blocks/add-tpgb-social-icons` |
| Embedded social post | `nexter-blocks/add-tpgb-social-embed` |
| Countdown | `nexter-blocks/add-tpgb-countdown` |
| Progress bar | `nexter-blocks/add-tpgb-progress-bar` |
| Step / progress tracker | `nexter-blocks/add-tpgb-progress-tracker` |
| Animated SVG | `nexter-blocks/add-tpgb-draw-svg` |
| Code snippet | `nexter-blocks/add-tpgb-code-highlighter` |
| Data table | `nexter-blocks/add-tpgb-data-table` |
| Hover card / preview tooltip | `nexter-blocks/add-tpgb-hovercard` |
| Alert / message banner | `nexter-blocks/add-tpgb-messagebox` |
| Animated text loop | `nexter-blocks/add-tpgb-stylist-list` |
| Search bar | `nexter-blocks/add-tpgb-search-bar` |
| Logo | `nexter-blocks/add-tpgb-site-logo` |
| Breadcrumbs | `nexter-blocks/add-tpgb-breadcrumbs` |
| Dark mode toggle | `nexter-blocks/add-tpgb-dark-mode` |
| Interactive circular menu | `nexter-blocks/add-tpgb-interactive-circle-info` |
| Navigation menu | `nexter-blocks/add-tpgb-navigation-builder` |
| Post grid / blog listing | `nexter-blocks/add-tpgb-post-listing` |

If something doesn't match cleanly, compose it from `container` + `heading` + `pro-paragraph` + `image` + `button`.

### Plan format (write before building)

Show the tree with one node per line. Mark every section's container, and inside any multi-column section, mark each `container-inner` explicitly. Every value in parentheses must come from your spec — colours from the spec's palette, sizes from its typography, padding from its spacing, text strings from `inspector.headings` or the screenshot. The example below uses `« source »` placeholders to make this explicit; in your actual plan, replace them with concrete values.

```
hero (container, bg « palette.bg », padding « spacing.sectionPadY »/0/« spacing.sectionPadY »/0px, max-width « spacing.containerMaxWidth », single column)
  ├─ heading « inspector.headings.h1[0] verbatim » (h1, « typography.h1.size »px, weight « typography.h1.weight », color « palette.textPrimary », align center)
  ├─ pro-paragraph « body copy from screenshot » (« typography.body.size »px, color « palette.textSecondary », align center)
  └─ button-core (gap « spacing.elementGap »px, justify center)
       ├─ button « button label from screenshot » (bg « palette.buttonBg », color « palette.buttonText », radius « radius.button »)
       └─ button « button label from screenshot » (bg transparent, border « palette.textPrimary », color « palette.textPrimary »)

features (container, bg « palette.* », padding « spacing.sectionPadY »/0/« spacing.sectionPadY »/0px, max-width « spacing.containerMaxWidth », three columns, gap « spacing.elementGap »)
  ├─ container-inner (column 1)
  │    └─ icon-box (icon « name », title « heading from screenshot », text « ... », color « palette.accent »)
  ├─ container-inner (column 2)
  │    └─ icon-box (icon « name », title « ... », text « ... », color « palette.accent »)
  └─ container-inner (column 3)
       └─ icon-box (icon « name », title « ... », text « ... », color « palette.accent »)

footer (container, bg « palette.bg », padding « ... », four columns)
  ├─ container-inner — logo + tagline
  ├─ container-inner — product links
  ├─ container-inner — company links
  └─ container-inner — social icons
```

### Checkpoint 2 — confirm the structure (in plain English) before building

Once the plan is written, describe it back to the user in normal language and ask whether to go with it. **Don't paste the tree-art or block names** — translate it. Example:

> Here's how I'd build the page, section by section:
>
> 1. **Header** — logo on the left, navigation menu on the right (two columns).
> 2. **Hero** — full-width dark band with a big headline, short paragraph, and two buttons stacked in the middle.
> 3. **Features** — white band with three side-by-side cards, each with an icon, a small heading, and a sentence of description.
> 4. **Testimonials** — a single quote with the customer's photo and name.
> 5. **Pricing** — three pricing cards in a row.
> 6. **FAQ** — accordion with the questions from the design.
> 7. **Footer** — four columns: logo + tagline, product links, company links, social icons.
>
> Want me to go with this structure, or change anything — split a section, merge two, drop one, swap a block type?

Wait for the user's confirmation (or edits) before calling any abilities. If they request changes, update the plan and re-describe in plain English before building.

---

## Phase 3 — Build

For each node in the plan, call its ability. Build **outer to inner, top to bottom**.

Rules:

1. **Always include `post_id`** in every call.
2. **Pass values verbatim from the source.** Every colour, font family, weight, heading string, and image URL in your block call must trace back to either (a) `inspect-page` output, (b) the screenshot, or (c) something the user typed. **Never fill block attrs from your training data.** If the inspector returned `#0B1320`, that exact string goes into `normalBgColor` — not `#0F172A`, not "navy". If it returned `Manrope` weight `600`, those go into `fontFamily` and the weight attr — not `Inter` weight `700`. Do not leave style attrs blank when you have a concrete value for them.
3. **Always pass `fontFamily`** on every block that has typography (heading, pro-paragraph, infobox, button, pricing-table, testimonials, accordion, etc.).
   - **URL given:** family and weights come from `inspect-page` *verbatim* — pass `inspector.fonts[0].family` as `fontFamily` and `inspector.fonts[0].weights` for the weight attrs. Do not substitute a "similar" Google Font.
   - **No URL:** pick the closest Google Fonts match by reading the screenshot (Inter, Roboto, Poppins, Playfair Display, Merriweather, Oswald, Montserrat, Lato, Open Sans, Lora, Raleway, etc.).
   - Also pass `fontType` (sans-serif / serif / display / handwriting / monospace) — empty is allowed but specifying it improves Google-Fonts URL accuracy.
   - Use `customFont` only for non-Google fonts the user has confirmed they have installed.
4. **Containers first, always.** Every section starts with a `nexter-blocks/add-tpgb-container` call — capture the returned `block_id` and pass it as `parent_block_id` on every nested call. For multi-column sections, the next calls are one `nexter-blocks/add-tpgb-container-inner` per column (each also returns a `block_id` you'll use as `parent_block_id` for that column's contents). Never call `add-tpgb-heading`, `add-tpgb-image`, `add-tpgb-pro-paragraph`, etc. without a parent container — root-level content blocks have nowhere to inherit padding, bg, or max-width from.
5. **For text content:** pass the real text you read from the image (`title`, `content`, button label). Don't keep the ability's defaults.
6. **One block per call.** Don't try to batch.
7. **On `WP_Error`:** read the message, fix the input once, retry. Don't loop.

Don't narrate every step to the user during the build. Build silently, then report.

### Hard rule — NEVER embed HTML in text content fields

Text fields (`title`, `content`, `description`, `subTitle`, button labels, table cells, list items, etc.) are **plain text only**. The server strips all HTML tags before saving — any `<span>`, `<b>`, inline `style="..."`, `<br>`, or HTML entity you put inside a content field will be removed or rendered as broken text.

If the image has mixed styling inside what looks like one element — e.g. a price `₹4,895` followed by smaller `+ ₹490 taxes & fees` text — **do not** try to encode it as `"₹4,895<span style='font-size:14px'>+ ₹490 taxes &amp; fees</span>"`. Instead:

- **Split into two blocks** — a `heading` for the price and a `pro-paragraph` (or another `heading` with smaller `tTypoSize`) for the secondary line, both inside a shared `container`.
- Or use the block's **dedicated secondary fields** if it has them (e.g. `pro-paragraph` exposes `extraTitle` / `extraContent` for highlighted spans, `heading-title` exposes `subTitle`).

Plain ampersands, em-dashes, currency symbols, and Unicode characters are fine — pass them as the literal character (`&`, `—`, `₹`, `'`), not as HTML entities (`&amp;`, `&mdash;`, `&#8377;`).

### Images — what to do when you don't have an URL

The image abilities (`add-tpgb-image`, `add-tpgb-creative-image`, `add-tpgb-post-image`, etc.) need a real image URL or attachment ID. The screenshot you were shown is **not** a usable asset — you cannot reference pixels in a screenshot as a media library entry.

When the design has photos / illustrations / icons that need real assets:

1. **Do not silently skip them.** Skipping leaves the layout broken (missing hero image, missing product photo).
2. **Ask the user upfront**, in the Phase 1 checkpoint message: *"The design has N images (hero photo, product shots, icons). Do you have URLs for them, or should I use placeholder URLs (e.g. `https://placehold.co/800x600`) so the layout is correct and you can swap the assets later?"*
3. **For brand logos and icons** that the user has not provided: prefer the icon-box / social-icons abilities with built-in icon sets, rather than image abilities, when possible.
4. **If the user agrees to placeholders:** use `https://placehold.co/{width}x{height}` URLs sized to match the image's actual proportions. Note in your final summary that placeholders were used and where.

---

## Phase 4 — Verify and refine

When done:

1. Give the user a short summary: section names, count of blocks, the post edit URL.
2. Ask: **"Open the editor or preview — what's off vs. the image?"**
3. When the user reports a discrepancy, identify the wrong `block_id` and (since most blocks don't yet have update abilities) ask the user to delete it manually, then re-create with corrected attrs.

---

## Common mistakes to avoid

- **Skipping `inspect-page`** when the user gave a URL, then guessing colours / fonts / headings from the screenshot (or from training data). The inspector exists exactly so you don't have to guess — call it first, every time.
- **"Approximating" inspector values** because they look close to a familiar default. If the inspector returned `Manrope` and `#0B1320`, you do not write `Inter` and `#0F172A`. Verbatim only.
- **Re-deriving palette / fonts from the screenshot when the inspector already gave them.** When a URL was given, the screenshot is for layout/spacing only.
- **Treating the placeholder strings (`« inspector.colors[0] verbatim »` etc.) in the JSON example as values to copy.** They are source labels; replace each one with the actual value from the labelled source.
- **Skipping Phase 1** because the image "looks simple". Always analyze first.
- **Showing the user the JSON spec** at the Phase 1 checkpoint. Confirm in plain English; the JSON is for your internal reference only.
- **Skipping Checkpoint 2** and going straight from style approval to building. Always confirm the section-by-section structure in plain English first.
- **Skipping the section container.** Don't put a `heading` or `image` directly at page root — wrap every section in `add-tpgb-container` first, even single-column ones.
- **Skipping `container-inner` for multi-column sections.** Three icon-boxes inside one outer container is *not* a 3-column grid — it's a vertical stack. Each column needs its own `add-tpgb-container-inner` parent.
- **Using `pricing-table` for a generic card.** Use `container` + `container-inner` (if multi-column) + `heading` + `pro-paragraph` + `button`.
- **Using `heading-title` when plain `heading` is enough.** `heading-title` carries layout/separator overhead.
- **Forgetting `parent_block_id`** — blocks pile at the root with no nesting.
- **Ignoring spacing** — if the image has air, set `globalPadding` / `globalMargin`.
- **Sending the long default `pro-paragraph` content** — always pass the real text from the image as `content`.
- **Inventing block names** not in the table above. If you're unsure, list available abilities first.
- **Building before asking for `post_id`.** It's required.
