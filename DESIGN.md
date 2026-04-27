---
version: "alpha"
name: "MOAH Editorial Commerce"
description: "A boutique fashion-commerce visual system that combines airy editorial presentation with dark, dense utility surfaces for management workflows."
colors:
  canvas: "#FFFFFF"
  canvas-subtle: "#F8F9FA"
  canvas-muted: "#F3F3F3"
  ink: "#000000"
  ink-soft: "#7F7F7F"
  ink-muted: "#A6A6A6"
  accent: "#DBCC8F"
  accent-strong: "#B5A567"
  accent-soft: "#EEE5BF"
  border: "#E6E6E6"
  border-strong: "#CFCFCF"
  footer: "#000000"
  footer-text: "#FFFFFF"
  panel-dark: "#121212"
  panel-dark-muted: "#1F1F1F"
  success: "#2F855A"
  warning: "#B7791F"
  danger: "#C53030"
  info: "#2B6CB0"
typography:
  display-xl:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "3.5rem"
    fontWeight: "700"
    lineHeight: "1.15"
    letterSpacing: "0.08em"
  display-lg:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "2.5rem"
    fontWeight: "700"
    lineHeight: "1.2"
    letterSpacing: "0.08em"
  heading-xl:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "2.5rem"
    fontWeight: "600"
    lineHeight: "1.25"
    letterSpacing: "0em"
  heading-lg:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "1.875rem"
    fontWeight: "600"
    lineHeight: "1.3"
    letterSpacing: "0em"
  heading-md:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "1.375rem"
    fontWeight: "600"
    lineHeight: "1.35"
    letterSpacing: "0em"
  body-lg:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "1rem"
    fontWeight: "400"
    lineHeight: "1.8"
    letterSpacing: "0em"
  body-md:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: "400"
    lineHeight: "1.8"
    letterSpacing: "0em"
  body-sm:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "0.875rem"
    fontWeight: "400"
    lineHeight: "1.7"
    letterSpacing: "0em"
  label-caps:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "0.75rem"
    fontWeight: "700"
    lineHeight: "1.4"
    letterSpacing: "0.16em"
  nav-caps:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "0.625rem"
    fontWeight: "400"
    lineHeight: "1.4"
    letterSpacing: "0.2em"
  button-sm:
    fontFamily: "Open Sans, Arial, sans-serif"
    fontSize: "0.75rem"
    fontWeight: "600"
    lineHeight: "1"
    letterSpacing: "0.08em"
  admin-ui:
    fontFamily: "Figtree, Open Sans, Arial, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: "500"
    lineHeight: "1.5"
    letterSpacing: "0em"
spacing:
  none: "0px"
  xxs: "4px"
  xs: "8px"
  sm: "12px"
  md: "16px"
  lg: "24px"
  xl: "32px"
  "2xl": "48px"
  "3xl": "64px"
  "4xl": "96px"
rounded:
  none: "0px"
  xs: "4px"
  sm: "8px"
  md: "12px"
  lg: "20px"
  pill: "30px"
  full: "9999px"
shadows:
  subtle: "0 0 10px rgba(0, 0, 0, 0.10)"
  button: "0 24px 36px -11px rgba(0, 0, 0, 0.09)"
  dropdown: "0 10px 34px -20px rgba(0, 0, 0, 0.41)"
  card: "0 7px 15px -5px rgba(0, 0, 0, 0.07)"
  modal: "0 24px 64px rgba(0, 0, 0, 0.24)"
  inset-product: "inset 0 0 101px 21px rgba(0, 0, 0, 0.09)"
elevation:
  level-0: "{shadows.subtle}"
  level-1: "{shadows.card}"
  level-2: "{shadows.button}"
  level-3: "{shadows.dropdown}"
  level-4: "{shadows.modal}"
motion:
  duration-fast: "150ms"
  duration-base: "300ms"
  duration-slow: "1000ms"
  duration-pulse: "2000ms"
  easing-standard: "ease"
  easing-emphasis: "ease-in-out"
  hover-scale: "1.0"
  image-zoom: "1.0 to 1.05"
borders:
  width-hairline: "1px"
  width-strong: "2px"
  style-default: "solid"
layout:
  container-sm: "540px"
  container-md: "720px"
  container-lg: "960px"
  container-xl: "1140px"
  hero-vertical-padding: "12rem"
  section-vertical-padding: "7rem"
  grid-gap: "{spacing.lg}"
iconography:
  shape-language: "circular badges, outline glyphs, and boutique retail pictograms"
  preferred-weight: "light to regular"
components:
  page:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink-soft}"
  page-section-alt:
    backgroundColor: "{colors.canvas-subtle}"
  hero:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    eyebrowStyle: "{typography.label-caps}"
    titleStyle: "{typography.display-lg}"
    verticalPadding: "{layout.hero-vertical-padding}"
  navbar:
    textColor: "{colors.ink}"
    mobileBackgroundColor: "{colors.ink}"
    itemStyle: "{typography.nav-caps}"
    activeAccent: "{colors.accent}"
  button-primary:
    backgroundColor: "{colors.accent}"
    borderColor: "{colors.accent}"
    textColor: "{colors.footer}"
    borderRadius: "{rounded.pill}"
    boxShadow: "{shadows.button}"
    textStyle: "{typography.button-sm}"
  button-secondary:
    backgroundColor: "{colors.footer}"
    borderColor: "{colors.footer}"
    textColor: "{colors.footer-text}"
    borderRadius: "{rounded.pill}"
    boxShadow: "{shadows.button}"
    textStyle: "{typography.button-sm}"
  button-ghost:
    backgroundColor: "{colors.canvas}"
    borderColor: "{colors.border-strong}"
    textColor: "{colors.ink}"
    borderRadius: "{rounded.pill}"
    textStyle: "{typography.button-sm}"
  product-card:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    borderRadius: "{rounded.none}"
    boxShadow: "{shadows.inset-product}"
    badgeColor: "{colors.accent}"
  promo-badge:
    backgroundColor: "{colors.accent}"
    textColor: "{colors.ink}"
    textStyle: "{typography.label-caps}"
  footer:
    backgroundColor: "{colors.footer}"
    textColor: "{colors.footer-text}"
    headingStyle: "{typography.label-caps}"
  form-field:
    backgroundColor: "{colors.canvas}"
    borderColor: "{colors.border}"
    textColor: "{colors.ink}"
    borderRadius: "{rounded.none}"
  dropdown:
    backgroundColor: "{colors.footer}"
    textColor: "{colors.footer-text}"
    borderRadius: "{rounded.none}"
    boxShadow: "{shadows.dropdown}"
  admin-shell:
    backgroundColor: "{colors.canvas-subtle}"
    textColor: "{colors.ink}"
    textStyle: "{typography.admin-ui}"
  admin-sidebar:
    backgroundColor: "{colors.panel-dark}"
    textColor: "{colors.footer-text}"
  admin-topbar:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    boxShadow: "{shadows.subtle}"
  admin-panel:
    backgroundColor: "{colors.canvas}"
    textColor: "{colors.ink}"
    borderRadius: "{rounded.md}"
    boxShadow: "{shadows.card}"
  admin-table-header:
    backgroundColor: "{colors.canvas-muted}"
    textColor: "{colors.ink}"
    textStyle: "{typography.label-caps}"
  admin-status-success:
    backgroundColor: "{colors.accent-soft}"
    textColor: "{colors.ink}"
    borderRadius: "{rounded.full}"
  admin-status-danger:
    backgroundColor: "{colors.danger}"
    textColor: "{colors.footer-text}"
    borderRadius: "{rounded.full}"
---

## Overview

This visual identity is a boutique editorial storefront with a restrained luxury tone. It relies on crisp black-and-white contrast, generous whitespace, pill-shaped calls to action, and a muted sand-gold accent used sparingly but consistently. The experience should feel like a premium seasonal catalog rather than a loud marketplace.

The customer-facing surfaces are intentionally airy and image-led. Large breathing room, uppercase micro-labels, and soft gold highlights create a sense of calm curation. The administrative surfaces should inherit that same palette and restraint, but shift toward tighter grids, stronger information hierarchy, and more obvious states so operational work feels efficient without becoming visually disconnected.

## Color Intent

The palette is built on three anchors:

- **Pure ink black** for structure, headings, navigation, and high-confidence actions.
- **Warm sand-gold** for emphasis, badges, highlighted calls to action, and hover moments.
- **Soft white and pale gray** for large fields of space that keep the interface calm and breathable.

Color should not become noisy. Accent gold is never the background of the entire page. It performs best as a contained emphasis color: buttons, circular overlays, sale ribbons, active accents, and selected navigation states.

For admin pages, the same palette should be used with a clearer hierarchy:

- Use `panel-dark` for side navigation and command-heavy chrome.
- Use `canvas` and `canvas-subtle` for the main work area.
- Reserve `accent` for one primary action per region.
- Keep destructive actions unmistakably red and isolated.

## Typography

Open Sans is the defining storefront voice. It gives the product a familiar, polished ecommerce tone with enough softness to support editorial layouts. The system depends on contrast between:

- **Large, calm display and heading sizes** for hero copy and section intros.
- **Small uppercase labels with heavy letter spacing** for navigation, breadcrumbs, metadata, and category markers.
- **Readable, lightly colored body text** with generous line-height for product descriptions and informational content.

Admin screens may use the `admin-ui` style for denser controls, but they should still sit comfortably beside the Open Sans-led brand language. That means avoiding overly technical mono-heavy patterns, compressed spacing, or bright dashboard colors that would fracture the identity.

## Layout and Spacing

The layout language is spacious and cinematic on the storefront:

- Hero regions use very large vertical padding.
- Sections should feel separated by wide gutters rather than thick borders.
- Product grids should breathe, with each card occupying clear white space.
- Content blocks often stack image, micro-label, headline, copy, then action.

The admin area should compress this rhythm without abandoning it. Use the same spacing scale, but bias toward `md`, `lg`, and `xl` rather than `2xl` and `3xl`. Dense surfaces still need clear grouping, especially for tables, filters, metrics, and side panels.

## Shape Language

The shape system mixes two ideas:

- **Hard-edged panels and cards** for product frames, dropdowns, and utility surfaces.
- **Soft pills and circles** for buttons, social icons, accent overlays, badges, and featured emphasis.

This contrast is important. If everything becomes fully rounded, the design loses its boutique sharpness. If everything becomes square, it loses warmth and approachability.

For admin pages:

- Keep cards gently rounded with `rounded.md`.
- Keep primary buttons pill-shaped.
- Keep data tables mostly rectilinear and clean.
- Use full-round badges for statuses and counts.

## Motion and Interaction

Motion is subtle, retail, and tactile rather than flashy:

- Standard state changes happen quickly and quietly.
- Hover states should feel polished, often through color inversion, subtle image scaling, or accent reveals.
- Emphasis animations can pulse when drawing attention to a single focal interaction.

Avoid over-animating admin pages. Motion there should communicate clarity:

- filters opening,
- rows highlighting,
- panels appearing,
- confirmations settling in.

Anything more theatrical than that will feel out of place.

## Storefront Component Guidance

### Navigation

Navigation is uppercase, narrow, and lightly tracked. It should feel refined, almost label-like. The desktop bar should appear confident and minimal. On smaller screens it can collapse into a darker container, but the typography and spacing should remain tidy and restrained.

### Hero Areas

Hero sections should present:

1. an eyebrow or breadcrumb,
2. a strong headline,
3. compact explanatory copy,
4. one dominant action and one supporting action.

Do not clutter hero regions with many competing cards or metrics unless the page is explicitly hybrid commerce plus analytics.

### Product Cards

Product cards are white stages for imagery. The image area should feel premium and slightly sculpted through inset shadowing. Sale markers and overlays should use accent gold as a deliberate focal point, not as decoration everywhere. Product metadata should remain disciplined: category, product name, price, and one clear next action.

### Buttons

Buttons are one of the strongest brand signals. Primary buttons are sand-gold with dark text or dark backgrounds with white text, always pill-shaped, always compact, and never oversized. Hover states may invert toward transparent or alternate-brand treatments, but should remain elegant and not become neon or harsh.

## Admin Management Guidance

The admin interface should feel like the operational counterpart of the storefront, not a separate product. The goal is “quiet luxury operations”:

- a dark left rail,
- light working canvas,
- compact topbar,
- white content panels with subtle depth,
- restrained accent use,
- strong table readability.

### Admin Information Hierarchy

Use this stack consistently:

1. page title and one-sentence context,
2. top-row metrics or key actions,
3. filters and search,
4. main table or management grid,
5. secondary drawers, forms, or side details.

### Admin Tables

Tables should be crisp and businesslike:

- pale header row,
- uppercase label styling for column headers,
- generous row height,
- soft separators,
- one accent action per row cluster,
- badges for statuses instead of verbose colored text.

### Admin Forms

Forms should keep the storefront’s clarity but reduce ornament:

- white or pale-gray surfaces,
- clear labels,
- mostly square inputs,
- strong alignment,
- one primary submit button,
- secondary actions visually quieter.

### Admin Cards and Metrics

Metric cards should not look like consumer promotions. Keep them minimal:

- bold numeric value,
- uppercase micro-label,
- optional delta or status chip,
- restrained use of accent or semantic color.

## What To Preserve

Preserve these traits whenever new pages are built:

- black and white as the structural base,
- sand-gold as the defining accent,
- uppercase tracked labels,
- spacious composition,
- pill actions,
- strong contrast between soft editorial marketing and clean operational utility.

## What To Avoid

Avoid introducing:

- purple-led palettes,
- glassmorphism overlays,
- overly playful gradients,
- excessive card rounding,
- dense enterprise-blue dashboards,
- heavy shadows on every panel,
- tiny cramped admin tables,
- multiple competing accent colors on the same screen.

The brand is most successful when it feels curated, premium, and calm.
