---
name: migrate-acf-block
description: Port one ACF block from lc-tidy2026 (or a sibling ACF-based theme) into this theme's native block.json/edit.js/render.php format. Use when asked to migrate, port, or convert an ACF block to a native/JSX block in this theme.
---

# Migrate an ACF block to a native block

Invoke as `/migrate-acf-block <slug>` (e.g. `/migrate-acf-block lc-hero`). Migrate **one block at a time** — confirm it renders correctly (editor + front end) before starting the next. Don't batch multiple blocks into one pass; each one surfaces its own gaps.

## Prerequisite (do this once per project, not per block)

Before migrating *any* block, make sure the following are already in place — a block's own styling depends on them, and debugging a broken block when the real problem is a missing design token wastes a full cycle:

1. **Design tokens** (`src/css/tokens.css`) — the real project palette/type-scale ported in, not the skeleton placeholder (`white`/`black`/`grey-*`/`blue-700`). Regenerate `theme.json` after (`npm run generate-theme-json`).
2. **Typography** (`src/css/typography.css`) — `@font-face` declarations, font files copied into `fonts/` (auto-preloaded by `inc/head-tags.php`), heading-look utility classes (`.h1`-`.h4` etc. — block markup often uses these on non-heading elements).
3. **Header/footer** — real logo, nav branding, any project-specific `<head>` schema.
4. **Icons** — if the old theme used Font Awesome classes in block markup (`fa-solid fa-phone` etc.), decide up front whether to vendor Font Awesome locally (see `inc/enqueue.php`'s `lc_tidyjs2026_enqueue_vendor()`) or convert every icon reference to inline SVG. Don't decide this per-block — pick once, apply everywhere.
5. **Buttons/cards** — if the old theme has real button/card classes (`.button`, `.button--lg`, etc.), decide whether to extend this skeleton's bare `.btn` to match, or keep the old class names as project CSS. Again, once, not per block.

If any of these aren't done yet, stop and do that work first (or ask the user to confirm scope) rather than patching around gaps block-by-block.

## Per-block steps

1. **Locate source**: `lc-tidy2026/blocks/{slug}.php` (render template) + `lc-tidy2026/acf-json/group_{slug}.json` (field definitions) + its registration entry in `lc-tidy2026/inc/lc-blocks.php` (title/icon/category — use for `block.json` fidelity, not required).

2. **Map ACF field types → `add_block.sh` types**:
   - `text` → `text`, `textarea` → `textarea` (map ACF's `new_lines` to add_block.sh's paragraph/list/linebreak), `image` → `image`, `url`/`link`/`select`/`number`/`checkbox` → same, `wysiwyg` → `richtext`.
   - `repeater` → not supported by `add_block.sh`. Either use the **LCP Block Builder** plugin (`LamcatUK/lcp-block-builder`, wp-admin GUI, at `wp-content/plugins/lcp-block-builder/` when active), or hand-wire `blocks/_shared/RepeaterField.js` (see its contract in this theme's `CLAUDE.md`).
   - `post_object` → hand-wire `blocks/_shared/PostTypePicker.js`.
   - `message` fields (ACF section headers) → skip, no equivalent needed.
   - **A block with no real fields** (just a `message` field) needs no attributes at all — run `add_block.sh` with the field prompt left blank immediately. Check first whether the render logic already exists as a plain PHP utility function in `inc/utilities.php` (e.g. breadcrumbs) — if so, `render.php` may be a one-line call and `edit.js` just a static preview.

3. **Scaffold**: `./add_block.sh`, answering prompts per the field map above.
   - **Every field type `add_block.sh` covers on its own** (text/textarea/richtext/image/url/link/number/select/checkbox) — just use it, no sidecar step needed.
   - **Anything scaffolded or hand-edited beyond that** (repeater fields, color support added by hand, or any other manual `block.json`/`edit.js` change `add_block.sh` wouldn't produce on its own) — write a matching `.block-builder.json` sidecar in the block's directory, so **LCP Block Builder** can still open and edit it later. Don't hand-write the JSON — call the plugin's own encoder so the format is guaranteed to match what it'd generate itself:
     ```php
     // via `wp eval-file` — build $fields as lc_block_builder_normalise_field()'s
     // raw input shape (label, type, help, width, options, textarea_style,
     // link_target, sub_fields, repeater_layout, post_type_slug,
     // conditional_logic, allowed_extensions), sub_fields as
     // lc_block_builder_normalise_subfield()'s (label, type, mime_types,
     // link_target, options) — see inc/generator.php for both.
     $normalised = array_map( 'lc_block_builder_normalise_field', $fields );
     $json = lc_block_builder_build_sidecar_json( $title, $color_support, $normalised );
     file_put_contents( "blocks/{$slug}/.block-builder.json", $json );
     ```
     Verify the round-trip before moving on: `lc_block_builder_build_attributes_array()` on the same normalised fields should produce exactly the attribute keys already in the block's `block.json`. A mismatch means the sidecar lies about the block's real shape — fix the field config, not the block.json.
   - Only skip the sidecar for something genuinely bespoke the plugin has no field type for at all (nothing hit so far — every block so far fit its vocabulary).

4. **Port `render.php`**: rewrite `get_field('x')`/`the_field('x')` as `$attributes['x']`. Specifically:
   - `get_field('x', 'option')` / `'options'` → `lc_tidyjs2026_get_setting('x')` — check the key exists in `inc/options.php` first (names may differ, e.g. old `contact_phone` → new `phone`).
   - Shortcodes/helpers the old block calls (`whatsapp_link`, `contact_phone`, `contact_email`, etc.) — check `inc/utilities.php` first, they may already be ported (several were, during the CTA/header/footer pass). If not, port them there rather than reinventing.
   - Keep every Bootstrap-named CSS class verbatim (`.container`, `.row`, `.col-*`, etc.) — same naming convention in both themes.
   - **`g-{n}` (Bootstrap gutter) → `gap-{n}`** — this theme's `.row` doesn't support `.g-*`, gap is a generic spacing utility instead.
   - **Every `.col-*` needs an explicit base**: `class="col-md-8"` alone will NOT fill the row on mobile — this theme's `.row`/`.col-*` is real CSS Grid, not Bootstrap flexbox, and a grid item with no assigned `grid-column` just sizes to its content instead of spanning. Always write `class="col-12 col-md-8"` (or whatever the base span should be), never bare `col-md-8`. **This is the single most common bug found migrating blocks — check every column div, every time.**
   - Ignore any Yoast SEO integration in old block code (`yoast_breadcrumb()` etc.) — Yoast is being replaced; use the plain PHP fallback logic instead.

5. **Build**: `npm run blocks:build`.

6. **Verify in-browser, every time** (don't skip this even for a "simple" block):
   - Block appears in the inserter, fields editable and **persist on save + reload** (this is the exact bug native blocks exist to avoid — see this theme's `CLAUDE.md`).
   - Front end: check both desktop and a mobile viewport width. Watch specifically for horizontal overflow (the `.col-12` gotcha above) and for any element that's supposed to be hidden/shown responsively (`d-none`/`d-sm-*` etc.) actually toggling correctly — a specificity clash from ported CSS can silently override a visibility utility (happened once: a ported `.btn` mobile rule beat `.d-none` on specificity).
   - `window.getComputedStyle()` via the browser's JS tool is faster than eyeballing for exactly this class of bug — check `display`/`grid-template-columns` directly if something looks subtly off rather than guessing from a screenshot.

7. **Track migration progress**: `[block_usage_table]` (`inc/lc-block-usage.php`) lists every block slug against which published pages still carry the old `wp:acf/{slug}` form vs the new `wp:lc-tidyjs2026/{slug}` form — use it to confirm a block is *fully* migrated (ACF column empty) before considering it done, and to pick which block to migrate next by usage weight.

## Limitations

This process is generative, not diff-checked. Always confirm the rendered front end against the old block's actual output — don't assume a clean build means a correct migration.
