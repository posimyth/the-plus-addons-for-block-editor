---
name: typography-fix
description: Authoritative recipe for applying fontWeight and textDecoration on every Nexter Blocks typographic block. Read this BEFORE adding any block that needs a non-default font weight or any text decoration. Every typography-bearing add-tpgb-* ability now accepts fontWeight and textDecoration as top-level parameters — pass them directly. For multi-typo-group blocks (infobox, pricing-table, accordion, …) the top-level params apply to ALL typography groups; use the settings raw override or sprout/update-element only when you need different settings per group. Always pass the EXACT structure documented here — do not invent keys like `weight` or place fontWeight outside fontFamily.
---

# Typography — fontWeight & textDecoration

This guide is deterministic. Follow it whenever the user asks for a non-default font weight (anything other than 400 / Regular) or any text decoration on a Nexter Blocks block.

## Background

Each Nexter Blocks block stores typography under one or more per-element camelCase attribute keys: `tTypo`, `titleTypo`, `texTyp`, `contentTypo`, `numTypo`, `priceTypo`, etc. Inside that object:

- `fontFamily` is a nested object — **fontWeight lives inside fontFamily**, NOT as a sibling.
- `textDecoration` is a sibling of `fontFamily` directly under the typo key.

The single most common AI mistake is putting `weight` or `fontWeight` at the wrong level. The correct shape is fixed — see the reference below and never deviate.

---

## Decision tree — pick the lowest-numbered path that applies

### Path 1 (preferred, default) — top-level params on the block

**Every typography-bearing `add-tpgb-*` ability now accepts these as proper top-level parameters:**

| Parameter | Purpose |
|---|---|
| `fontFamily` | Google Fonts family name (or empty to inherit). |
| `fontType` | Google Fonts category — `sans-serif`, `serif`, `display`, `handwriting`, `monospace`. |
| `customFont` | Non-Google font name. Overrides fontFamily. |
| `fontWeight` | `"100"`–`"900"` as a string. Embedded inside every typo group's `fontFamily.fontWeight`. |
| `textDecoration` | `none` / `underline` / `overline` / `line-through`. Stamped onto every typo group as a sibling of `fontFamily`. |

For single-typo blocks (heading, button, pro-paragraph, etc.) this is the only path you need.

For multi-typo blocks (infobox = `titleTypo` + `descTypo` + `btnTypo`, pricing-table = 8 typos, etc.) the top-level value is applied to ALL groups uniformly. That's almost always what the user wants. Use Path 2 or Path 3 only when groups must differ.

**Example — heading bold + underline:**
```json
{
  "ability_name": "nexter-blocks/add-tpgb-heading",
  "parameters": {
    "post_id": 123,
    "title": "Our Mission",
    "tTag": "h1",
    "enableTypography": true,
    "fontFamily": "Poppins",
    "fontType": "sans-serif",
    "fontWeight": "700",
    "textDecoration": "underline"
  }
}
```

**Example — pricing-table all-groups bold:**
```json
{
  "ability_name": "nexter-blocks/add-tpgb-pricing-table",
  "parameters": {
    "post_id": 123,
    "fontFamily": "Inter",
    "fontType": "sans-serif",
    "fontWeight": "700"
  }
}
```
→ `titleTypo`, `priceTypo`, `descTypo`, `ctaTypo`, etc. all get Inter 700.

### Path 2 — `settings` raw override (different settings per group)

When a multi-typo block needs per-group differences, pass each typo object via the `settings` raw override. Keys are preserved in camelCase, so `settings.titleTypo` stays `titleTypo` and Nexter Blocks reads it.

Pass the **full** typo object — partial overrides leave defaults in place and may produce unpredictable output. Use the structure in the reference section below.

**Example — pricing-table title 700 + price 900:**
```json
{
  "ability_name": "nexter-blocks/add-tpgb-pricing-table",
  "parameters": {
    "post_id": 123,
    "fontFamily": "Poppins",
    "fontType": "sans-serif",
    "settings": {
      "titleTypo": {
        "openTypography": 1,
        "size": { "md": "", "unit": "px" }, "height": "", "spacing": "",
        "fontFamily": { "family": "Poppins", "type": "sans-serif", "customFont": "", "fontWeight": "700" }
      },
      "priceTypo": {
        "openTypography": 1,
        "size": { "md": "", "unit": "px" }, "height": "", "spacing": "",
        "fontFamily": { "family": "Poppins", "type": "sans-serif", "customFont": "", "fontWeight": "900" },
        "textDecoration": "underline"
      }
    }
  }
}
```

### Path 3 — `sprout/update-element` (patching after insertion)

Use this only when:
- You already inserted the block and want to patch typography without re-inserting.
- The block was added by another tool and you need to retro-fit weight/decoration.

Two calls — capture the `block_id` from the first, then patch:

```json
{ "ability_name": "sprout/update-element", "parameters": { "post_id": 123, "element_id": "abc123def456", "settings": { "titleTypo": { … } } } }
```

---

## tTypo structure — the only shape you should ever output

```json
{
  "<typoKey>": {
    "openTypography": 1,
    "size": { "md": "<size or ''>", "unit": "px" },
    "height": "",
    "spacing": "",
    "fontFamily": {
      "family": "<fontFamily value>",
      "type": "<sans-serif | serif | display | handwriting | monospace>",
      "customFont": "",
      "fontWeight": "<100..900 as string>"
    },
    "textDecoration": "<'' | none | underline | overline | line-through>"
  }
}
```

### fontWeight values
| Value | Meaning |
|---|---|
| `"100"` | Thin |
| `"200"` | Extra Light |
| `"300"` | Light |
| `"400"` | Regular (default — omit if 400) |
| `"500"` | Medium |
| `"600"` | Semi Bold |
| `"700"` | Bold |
| `"800"` | Extra Bold |
| `"900"` | Black |

Always pass `fontWeight` as a **string** inside `fontFamily`. Do not put it at the `tTypo` level. Do not name the key `weight` or `font-weight` — only `fontWeight`.

### textDecoration values
`""` (inherit), `"none"`, `"underline"`, `"overline"`, `"line-through"`.

`textDecoration` is a sibling of `fontFamily` inside the typo key. Never nest it inside `fontFamily`.

---

## Per-block typo key reference (for Path 2 / Path 3 only)

| Block ability | Typo key(s) |
|---|---|
| `add-tpgb-heading` | `tTypo` |
| `add-tpgb-heading-title` | `titleTypo` (main), `subTitleTypo` (sub), `extraTitleTypo` (extra) |
| `add-tpgb-pro-paragraph` | `textTypo`, `titleTypo`, `dcapTypo` (drop cap) |
| `add-tpgb-button` | `texTyp` |
| `add-tpgb-button-core` | `bTypo` |
| `add-tpgb-blockquote` | `typography`, `authorTypo` |
| `add-tpgb-breadcrumbs` | `bredTypo` |
| `add-tpgb-countdown` | `counterTypo`, `labelTypo`, `expiryMsgTypo` |
| `add-tpgb-dark-mode` | `beforeTypo`, `afterTypo` |
| `add-tpgb-data-table` | `ThTypo`, `TBTypo`, `BtnTypo` |
| `add-tpgb-flipbox` | `titleTypo`, `descTypo`, `backBtnTypo` |
| `add-tpgb-form-block` | `lblTypo`, `inpTypo`, `btnTypo`, `chkRadTxtTypo`, `selTypo`, `descTypo`, `sucTypo` |
| `add-tpgb-image` | `icapTypo` |
| `add-tpgb-infobox` | `titleTypo`, `descTypo`, `textIconTypo`, `pinTextTypo`, `cBtnTypo` |
| `add-tpgb-interactive-circle-info` | `iTitleTypo`, `cTitleTypo`, `cDescTypo`, `btnTypo` |
| `add-tpgb-messagebox` | `titleTypo`, `descTypo` |
| `add-tpgb-navigation-builder` | `menuTypo`, `submenuTypo` |
| `add-tpgb-number-counter` | `titleTypo`, `digitTypo`, `symbolTypo` |
| `add-tpgb-post-author` | `nameTypo`, `roleTypo`, `bioTypo` |
| `add-tpgb-post-comment` | `commTypo`, `userTypo`, `metaTypo`, `replyTypo`, `fieldTypo`, `btnTypo` |
| `add-tpgb-post-content` | `contentTypo` |
| `add-tpgb-post-listing` | `titleTypo`, `excerptTypo`, `postMetaTypo`, `postCategoryTypo`, `butTypo` |
| `add-tpgb-post-meta` | `metaTypo`, `labelTypo` |
| `add-tpgb-post-title` | `titleTypo`, `prePostTypo` |
| `add-tpgb-pricing-list` | `titleTypo`, `tagTypo`, `priceTypo`, `descTypo` |
| `add-tpgb-pricing-table` | `ctaTypo`, `titleTypo`, `subTitleTypo`, `priceTypo`, `postfixTypo`, `prevPriceTypo`, `wysiwygTypo`, `listContentTypo` |
| `add-tpgb-progress-bar` | `titleTypo`, `subTitleTypo`, `numTypo`, `numPrePostTypo` |
| `add-tpgb-progress-tracker` | `texTypo`, `pinTypo` |
| `add-tpgb-search-bar` | `inputTypo`, `labelTypo`, `btnTypo`, `titleTypo`, `contentTypo`, `errorTypo` |
| `add-tpgb-social-icons` | `titleTypo` |
| `add-tpgb-stylist-list` | `toggleTypo`, `textTypo`, `pinTypo` |
| `add-tpgb-tabs-tours` | `titleTypo`, `descTypo` |
| `add-tpgb-team-listing` | `nameTypo`, `roleTypo`, `descTypo`, `socialTypo` |
| `add-tpgb-testimonials` | `contentTypo`, `nameTypo`, `roleTypo` |

If unsure for a block, run `sprout/extract-content` after Path 1 and inspect the saved attribute structure for keys ending in `Typo` / `typography` / `texTyp` / `bTypo`.

---

## Common AI mistakes — do not do any of these

```json
// ❌ weight outside fontFamily
"tTypo": { "fontFamily": {...}, "weight": "700" }

// ❌ wrong key name
"tTypo": { "fontFamily": {...}, "font-weight": "700" }

// ❌ fontWeight at the wrong level inside tTypo
"tTypo": { "fontFamily": {...}, "fontWeight": "700" }

// ❌ textDecoration inside fontFamily
"tTypo": { "fontFamily": { ..., "textDecoration": "underline" } }

// ❌ partial typo override (missing openTypography, size, etc.)
"tTypo": { "fontFamily": { "fontWeight": "700" } }
```

The only correct shape is the one in the "tTypo structure" reference above.

---

## When to apply this skill

- The user asks for a specific font weight (e.g. "bold", "300", "700").
- The user asks for text decoration (underline, strikethrough, overline).
- You are building from an image / URL and the design uses non-default weights.
- You need to change typography on a block that is already on the page (Path 3).

## When this skill is NOT needed

- The user only specified `fontFamily` and the default 400 Regular weight is fine — pass `fontFamily` alone.
- The block already uses a global typography preset (`typoGlobalPreset`) — the preset owns weight & decoration.
