---
name: nexter-doc-post-creator
description: >
  Use this skill whenever you need to create a new Nexter Blocks documentation post in WordPress.
  Triggers on any request like "create a doc post for [block name]", "write a Nexter doc page",
  "build a documentation post", "create a tutorial post for [block]", or any request to publish
  a structured how-to guide for a Nexter Blocks feature. This skill defines the full workflow:
  research the block via the Nexter Blocks MCP abilities → plan content → create the post →
  build every section in the correct order using ONLY Nexter Blocks abilities (never Elementor
  or other plugin blocks) → leave image placeholders ready for manual upload. Never skip or
  reorder sections.
---

# Nexter Doc Post Creator

A skill for building fully structured Nexter Blocks documentation posts in WordPress.
Every post must follow the canonical section order, use only Nexter Blocks abilities,
and never include header/footer (those come from the theme builder site-wide).

> **Tool-name convention.** Every block-creation call goes through a real
> WP Abilities API ability registered by this plugin. Canonical names look
> like `nexter-blocks/add-tpgb-<block>`. MCP clients see them surfaced as
> `nexter-blocks-add-tpgb-<block>` (the slash is rewritten on the bridge).
> Both forms refer to the same ability — pick whichever your MCP client
> shows in its tool catalogue.

---

## Inputs

| Input | Required? | Behaviour if missing |
|---|---|---|
| `block_name` | ✅ Yes | Ask the user before doing anything else. Do not proceed without it |
| `live_block_url` | Optional | If provided → add CTA button (Section 5). If not provided → skip Section 5 entirely |
| `youtube_url` | Optional | If provided → add video block (Section 7). If not provided → skip Section 7 entirely |
| `post_status` | Optional | Default: `draft`. Only set to `publish` if user explicitly says so |
| `extra_context` | Optional | Any specific points or features the user wants highlighted |

---

## Phase 1 — Research the Block via the Nexter Blocks Abilities

**This phase is mandatory. Never write any content before completing it.**

### Step 1.1 — Read the target block's ability schema

Every Nexter Blocks block ships with a real MCP ability — its `input_schema`
already documents every setting the block supports. Read that schema instead
of guessing.

```
tool: sprout-wordpress → sprout-bridge-inspect-tool
  tool_name: "nexter-blocks-add-tpgb-[block_slug]"
```

`[block_slug]` is the block's filename minus the `add-tpgb-` prefix
(e.g. `accordion`, `pro-paragraph`, `heading-title`, `messagebox`,
`testimonials`). If you are not sure which slug to use, list all
Nexter Blocks abilities first:

```
tool: sprout-wordpress → sprout-bridge-discover-tools
  category: "nexter-blocks"
```

Read the full ability description and `input_schema`. Extract and note:
- Core purpose of the block (from the `description` field)
- Every input the ability exposes, grouped by tab where the editor uses tabs (Content, Style, Advanced)
- Sub-sections or feature groups within those tabs
- PRO-only features (mark these clearly with PRO label)
- Special toggles, selectors, column options, dynamic content abilities

> If the schema is thin, also fetch the live block's rendered markup from a
> staging page via `nexter-blocks/inspect-page` to see how the settings
> surface in the DOM.

### Step 1.2 — Plan the Dynamic Content Sections

Based on what you learned, decide the **dynamic middle sections** for this specific block. These sit between Required Setup (Section 6) and the Styling section (Section 11). Examples:

- "How to Use the [Block Name] Block?" — general usage walkthrough
- Named sections per major tab or feature group (e.g. "Content Settings", "Layout Options", "Title")
- Any block-specific feature rich enough to deserve its own section

Write your planned section list before moving to Phase 2.

---

## Phase 2 — Create the WordPress Post Shell

```
tool: sprout-wordpress → sprout-create-post
  title:   "How to add [block name] in WordPress?"
  status:  draft   ← always draft unless user explicitly says publish
  content: ""      ← empty, all content is added block by block in Phase 3
```

Save the returned `post_id`. Every ability call in Phase 3 uses this ID.

---

## Phase 3 — Build the Post Section by Section

Add every section **in the exact order listed below**.
Mandatory sections are always added. Optional sections (5 and 7) are only added when the relevant input was provided by the user.

---

### SECTION 1 — Breadcrumb *(mandatory)*

Add a Nexter Blocks Pro Paragraph as the breadcrumb navigation line.

```
tool: nexter-blocks/add-tpgb-pro-paragraph
  post_id:   [post_id]
  showTitle: false
  content:   "Home / Documents / Nexter Blocks / [Block Name]"
  alignment: "left"
```

Styling: small font (12–13px), muted grey colour. Purely navigational text, no bold.

---

### SECTION 2 — Key Takeaways *(mandatory)*

Add a Nexter Blocks Message Box with exactly 3 bullet takeaways based on your Phase 1 research.

```
tool: nexter-blocks/add-tpgb-messagebox
  post_id:           [post_id]
  title:             "Key Takeaways"
  enableDescription: true
  description:       "• [Takeaway 1]\n• [Takeaway 2]\n• [Takeaway 3]"
  showIcon:          true
  iconClass:         "fas fa-info-circle"
  showDismiss:       false
  showArrow:         false
```

Rules for writing takeaways:
- Each = one concrete thing the user can DO with this block
- Written as action-oriented sentences ("Nexter Blocks enables you to…")
- No repeating the title. No filler.

---

### SECTION 3 — Table of Contents *(mandatory)*

> ⚠️ **Known gap.** Nexter Blocks does not yet expose a Table of Contents
> ability through the MCP layer. Insert the placeholder paragraph below so
> the section's position in the document is preserved, then add the real
> `tpgb/tp-table-of-content` block manually in the WP block editor after
> the post is created. Report this in the final summary so the user knows
> a manual step is needed.

```
tool: nexter-blocks/add-tpgb-pro-paragraph
  post_id:   [post_id]
  showTitle: true
  title:     "Table of Contents"
  titleTag:  "h2"
  content:   "[TOC placeholder — replace this paragraph with the Nexter Blocks Table of Contents block manually in the editor; H2 source: page headings]"
  alignment: "left"
```

---

### SECTION 4 — Intro Paragraphs *(mandatory)*

Add two short paragraphs using the Nexter Blocks Pro Paragraph block.

- **Paragraph 1:** What the default WordPress block does and its limitations.
- **Paragraph 2:** How the Nexter Blocks version solves those limitations.

```
tool: nexter-blocks/add-tpgb-pro-paragraph
  post_id:   [post_id]
  showTitle: false
  content:   "[Paragraph text]"
```

Call this ability twice — once per paragraph.

---

### SECTION 5 — Live Block Link CTA Button *(optional)*

**Skip this section entirely if `live_block_url` was not provided. Do not add any placeholder.**

If `live_block_url` was provided, add a centred CTA button:

```
tool: nexter-blocks/add-tpgb-button
  post_id:    [post_id]
  styleType:  "style-1"
  btnText:    "LIVE BLOCK LINK"
  linkUrl:    [live_block_url]
  linkTarget: "_blank"
  alignment:  "center"
```

Brand style — must always match exactly. Pass these via the matching
style attributes documented in the ability's `input_schema`:
- Background: `var(--nxt-global-color-1)` — primary brand colour
- Text colour: `var(--nxt-global-color-8)` — white
- Border radius: `5px`
- Width: `75%`
- Alignment: centre
- Opens in new tab (`_blank`)

---

### SECTION 6 — Required Setup *(mandatory)*

```
tool: nexter-blocks/add-tpgb-heading
  post_id: [post_id]
  title:   "Required Setup"
  tTag:    "h2"
```

```
tool: nexter-blocks/add-tpgb-stylist-list
  post_id: [post_id]
  items:
    - text: "Make sure the default WordPress Block editor is active."
      iconType: "fontawesome"
      icon:     "fas fa-check"
    - text: "You need to have the Nexter Blocks plugin installed and activated."
      iconType: "fontawesome"
      icon:     "fas fa-check"
    - text: "Make sure the [Block Name] block is activated — go to Nexter → Blocks → search for [Block Name] and turn on the toggle."
      iconType: "fontawesome"
      icon:     "fas fa-check"
```

Always exactly 3 items. Never add more, never remove one.

---

### SECTION 7 — Learn via Video Tutorial *(optional)*

**Skip this section entirely if `youtube_url` was not provided. Do not add any placeholder.**

If `youtube_url` was provided:

```
tool: nexter-blocks/add-tpgb-heading
  post_id: [post_id]
  title:   "Learn via Video Tutorial"
  tTag:    "h2"
```

```
tool: nexter-blocks/add-tpgb-video
  post_id:   [post_id]
  videoType: "youtube"
  youtubeId: "[extract the 11-char video ID from youtube_url]"
  autoPlay:  false
  loop:      false
  muted:     false
  controls:  true
```

---

### SECTION 8 — How to Activate the Block *(mandatory)*

```
tool: nexter-blocks/add-tpgb-heading
  post_id: [post_id]
  title:   "How to Activate the [Block Name] Block?"
  tTag:    "h2"
```

```
tool: nexter-blocks/add-tpgb-stylist-list
  post_id: [post_id]
  items:
    - text: "Go to Nexter → Blocks"
      iconType: "fontawesome"
      icon:     "fas fa-arrow-right"
    - text: "Search the block name and turn on the toggle."
      iconType: "fontawesome"
      icon:     "fas fa-arrow-right"
```

Add an image placeholder — screenshot is uploaded manually after post creation:

```
tool: nexter-blocks/add-tpgb-image
  post_id:       [post_id]
  imageUrl:      ""
  imageId:       0
  imageSize:     "large"
  captionType:   "custom"
  customCaption: "[Block Name] activation screenshot"
  alignment:     "center"
```

---

### SECTION 9 — Key Features *(mandatory)*

```
tool: nexter-blocks/add-tpgb-heading
  post_id: [post_id]
  title:   "Key Features"
  tTag:    "h2"
```

```
tool: nexter-blocks/add-tpgb-stylist-list
  post_id: [post_id]
  items:
    - text: "[Feature Name] – [One sentence: what it does]"
      iconType: "fontawesome"
      icon:     "fas fa-star"
    # ... 3–6 items total, sourced from the Phase 1 schema research
```

Format per item: **Feature Name** – one sentence describing what it enables.
Base this entirely on what the ability schema returned. Never invent features.

---

### SECTION 10 — Dynamic Content Sections *(mandatory, minimum 2 sections)*

This is the block-specific body. Use the section plan from Phase 1 Step 1.2.
Repeat the pattern below for each planned section:

**1. H2 heading**
```
tool: nexter-blocks/add-tpgb-heading
  post_id: [post_id]
  title:   "[Section Title]"
  tTag:    "h2"
```

**2. Body paragraphs** — one Pro Paragraph per paragraph
```
tool: nexter-blocks/add-tpgb-pro-paragraph
  post_id:   [post_id]
  showTitle: false
  content:   "[Paragraph text — HTML allowed for <strong>, <a>, <code>]"
```

Writing rules for body text:
- Bold every UI term: setting names, toggle names, tab names, button labels
- Introduce with em dash: **Drop Cap** – enables you to set…
- Language pattern: "From the **X** section, you can…" / "By enabling the **Y** toggle, you can…"
- Keep paragraphs to 1–3 sentences
- PRO-only features: end the sentence with `(PRO)`

**3. Image placeholder** — add after any section covering UI settings
```
tool: nexter-blocks/add-tpgb-image
  post_id:       [post_id]
  imageUrl:      ""
  imageId:       0
  imageSize:     "full"
  captionType:   "custom"
  customCaption: "[Section name] settings screenshot"
  alignment:     "center"
```

**4. Accordion** — use instead of separate paragraphs when a feature has multiple distinct sub-options
```
tool: nexter-blocks/add-tpgb-accordion
  post_id: [post_id]
  accordion_items:
    - title:       "[Sub-feature name]"
      contentType: "content"
      desc:        "[Description HTML]"
```

---

### SECTION 11 — How to Style the Block *(mandatory)*

```
tool: nexter-blocks/add-tpgb-heading
  post_id: [post_id]
  title:   "How to Style [Block Name] Block in WordPress?"
  tTag:    "h2"
```

Add intro paragraph:
```
tool: nexter-blocks/add-tpgb-pro-paragraph
  post_id:   [post_id]
  showTitle: false
  content:   "You'll find all the styling options for the [Block Name] block under the Style tab."
```

For each style group the block has (Typography, Text Colour, Spacing, Shadow, etc.) add one short paragraph with the group name bolded:
```
tool: nexter-blocks/add-tpgb-pro-paragraph
  post_id:   [post_id]
  showTitle: false
  content:   "<strong>[Group Name]</strong> – From here you can manage [what you can control]."
```

Add one image placeholder for the Style tab:
```
tool: nexter-blocks/add-tpgb-image
  post_id:       [post_id]
  imageUrl:      ""
  imageId:       0
  imageSize:     "full"
  captionType:   "custom"
  customCaption: "[Block Name] style tab screenshot"
  alignment:     "center"
```

Always end the post with this fixed closing line:
```
tool: nexter-blocks/add-tpgb-pro-paragraph
  post_id:   [post_id]
  showTitle: false
  content:   'Advanced options remain common for all our blocks. <a href="https://nexterwp.com/docs/add-advanced-tab-in-wordpress/" target="_blank">View Advanced tab tutorial.</a>'
```

---

## Phase 3.5 — Save / Publish the Post in the Editor *(mandatory, never skip)*

**As soon as Phase 3 is complete and the design is built, the post MUST be saved by triggering the editor's Save/Publish button. Block additions are not considered persisted until this step runs.**

Trigger the appropriate save action on the `post_id` from Phase 2:

- If `post_status` is `draft` (default) → click **Save Draft** (i.e. call the update-post / save ability so WordPress writes the latest block markup to the database).
- If `post_status` is `publish` → click **Publish** (i.e. transition the post to `publish` via the publish/update ability).
- If the post is already published and the abilities only added new blocks → click **Update** so the new blocks become live.

Use the available Nexter / Sprout post abilities to perform this action — for example:

```
tool: sprout-wordpress → sprout-update-post
  post_id: [post_id]
  status:  [draft | publish]   ← same value used in Phase 2 unless user upgraded it
```

Rules:
- Run this step **exactly once**, after every block in Phase 3 has been added.
- Never end the workflow with unsaved changes sitting in the editor.
- If the save/publish call returns an error, retry once; if it still fails, surface the error in the final report so the user can click Save/Publish manually in the WP editor.
- The same rule applies to every other workflow in this plugin that uses Nexter abilities to build a design: as soon as the design phase ends, the Save / Publish button of the post or page editor must be triggered so the changes are committed.

---

## Phase 4 — Final Checks & Report

Verify before reporting done:

- [ ] Breadcrumb is the very first block
- [ ] Key Takeaways is second
- [ ] Table of Contents placeholder is third (and flagged in the report for manual replacement)
- [ ] Two intro paragraphs present
- [ ] CTA button present only if `live_block_url` was provided; fully absent if not
- [ ] Required Setup has exactly 3 bullet items
- [ ] Video block present only if `youtube_url` was provided; fully absent if not
- [ ] Activation section has an image placeholder
- [ ] Key Features has 3–6 items
- [ ] At least 2 dynamic content sections present
- [ ] Every content section covering UI settings has an image placeholder
- [ ] Style section present and ends with the Advanced tab link
- [ ] Every block-creation call used a real `nexter-blocks/add-tpgb-*` ability (no invented tool names)
- [ ] No Elementor (`tpae-*`) abilities used anywhere
- [ ] No header or footer blocks added
- [ ] Phase 3.5 ran — the editor's Save / Publish (or Update) button was triggered after the design was finished, and the save call returned success

**Report back to the user with:**
- Post ID and WordPress admin edit URL
- Manual TODO: replace the Section 3 placeholder paragraph with the real Nexter Blocks Table of Contents block in the editor
- Which optional sections were included (CTA / video) and which were skipped
- Full list of every image placeholder that needs a manual screenshot uploaded
- Any PRO-only features mentioned in the content

---

## Hard Rules — Never Break

| Rule | Detail |
|---|---|
| **Only Nexter Blocks abilities** | Use only `nexter-blocks/add-tpgb-*` abilities (or their bridged `nexter-blocks-add-tpgb-*` form). Never use `tpae-*` Elementor abilities, never invent `sprout-add-*` tool names |
| **No header / footer** | Theme builder handles these site-wide. Never add them inside a post |
| **Fixed section order** | Breadcrumb → Key Takeaways → TOC → Intro → CTA (if url) → Required Setup → Video (if url) → Activation → Key Features → Dynamic Sections → Styling |
| **Research first** | Always complete Phase 1 by reading the target ability's `input_schema` via `sprout-bridge-inspect-tool` before writing anything. Never invent block features |
| **Optional sections** | CTA and Video are added ONLY when the URL is provided. Never substitute a placeholder |
| **Image placeholders** | Always add an empty image block wherever a screenshot belongs. Never skip these |
| **H2 for all sections** | Section headings are always H2 (`tTag: "h2"`). Use H3 only for sub-topics within a section |
| **Bold all UI terms** | Every setting name, toggle, tab name, button label in copy must be bold |
| **Brand CTA style** | `--nxt-global-color-1` bg, `--nxt-global-color-8` text, 75% width, 5px radius, centre-aligned |
| **Draft by default** | Always `draft` unless user explicitly requests `publish` |
| **Save after design** | After every Nexter abilities design run, the editor's Save / Publish (or Update) button MUST be triggered (Phase 3.5). Never leave the post with unsaved block changes |
