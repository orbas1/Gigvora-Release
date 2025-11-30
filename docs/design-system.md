# Gigvora Design System – Tokens & Theming

Last updated: 2025-11-29

This document describes the canonical Gigvora design tokens and how they are applied across the web (Laravel) and Flutter shells. It is the primary reference for colours, typography, spacing, radii, shadows, transitions, and focus states.

## 1. Token Source of Truth

- **Web tokens file**: `resources/css/gigvora/tokens.css`
- **Consumption in app CSS**: `resources/css/app.css` imports `./gigvora/tokens.css` and applies base body styles using token variables.
- **Flutter theme**: `App/lib/gigvora_theme.dart` exposes a `GigvoraTheme.light()` `ThemeData` that mirrors the web palette and typography.

### 1.1 Colour System

- **Primary palette** (`--gv-color-primary-50` → `--gv-color-primary-900`):
  - Base brand blue: `--gv-color-primary-600` (`#2563EB`) with darker `-700` and `-800` for emphasis.
- **Neutrals** (`--gv-color-neutral-50` → `--gv-color-neutral-900`):
  - Used for backgrounds, text, borders; default page background is `-50`, default body text is `-800`.
- **Semantic accents**:
  - `--gv-color-success` (`#16A34A`)
  - `--gv-color-warning` (`#D97706`)
  - `--gv-color-danger` (`#DC2626`)
  - `--gv-color-info` (`#0EA5E9`)
- **Surfaces & borders**:
  - `--gv-color-surface` (primary card surface, `#FFFFFF`)
  - `--gv-color-surface-alt` (subtle alternate background)
  - `--gv-color-border` (low-contrast border with neutral tint)

### 1.2 Typography

- **Families**:
  - `--gv-font-family-base` and `--gv-font-family-heading` both default to **Inter** with system fallbacks.
- **Scale**:
  - `--gv-font-size-xs` → `--gv-font-size-3xl` provide a consistent set for captions, body, and headings.
  - Line heights: `--gv-line-height-tight`, `--gv-line-height-base`, `--gv-line-height-relaxed`.
- **Helpers**:
  - `.gv-heading` – medium-weight headings with neutral-900.
  - `.gv-body` – default body stack and line-height; now applied at `<body>` level in `layouts/app.blade.php` and `layouts/guest.blade.php`.
  - `.gv-eyebrow` – uppercase eyebrow labels with tracking and primary-600 colour.

### 1.3 Spacing, Radius, Shadows, Transitions

- **Spacing tokens**: `--gv-space-1` (4px) up to `--gv-space-10` (40px), used throughout `.gv-*` utilities.
- **Radii**: `--gv-radius-xs` → `--gv-radius-lg` and `--gv-radius-full` for chips/pills.
- **Shadows**: `--gv-shadow-xs`/`sm`/`md`/`lg` tuned for cards, buttons, and elevated panels.
- **Transitions & focus**:
  - `--gv-transition-fast|normal|slow|base` for motion.
  - `--gv-focus-ring` used via `.gv-focus-ring` helper and global `:focus-visible` styles in `app.css`.

## 2. Core Components & Utilities

- **Surfaces & sections**
  - `.gv-surface`, `.gv-elevated`, `.gv-card`, `.gv-section` – standardised containers for cards, panels, and page sections with consistent padding, radii, and shadows.
- **Buttons**
  - `.gv-btn` – base button layout (inline-flex, spacing, radius).
  - `.gv-btn-primary` – gradient primary with white text and focus ring.
  - `.gv-btn-ghost` – subtle text-style button with primary hover background.
- **Pills & chips**
  - `.gv-pill`, `.gv-pill--success|warning|danger` – status badges.
  - `.gv-chip`, `.gv-chip--primary` – compact uppercase chips with borders.
- **Form elements**
  - `.gv-input`, `.gv-label` – tokenised inputs and labels (border, radius, focus, text).
- **Navigation**
  - `.gv-nav-link`, `.gv-nav-link--active` – header tabs (used conceptually by header nav components).
  - `.gv-responsive-link`, `.gv-responsive-link--active` – responsive drawer links.
  - `.gv-side-link`, `.gv-side-link--active` – side-rail navigation (feed/profile sidebars).
- **Misc**
  - `.gv-link`, `.gv-muted`, `.gv-divider`, `.gv-dropdown-link`, `.gv-skip-link` – link, muted text, dividers, menu items, and accessibility helpers.

## 3. Web Layout Integration

- **Host layouts**
  - `layouts/app.blade.php`:
    - `body` now includes `gv-body` and uses token-based background/text colours.
    - Header uses tokenised borders and background (`bg-white/90`, `border-[var(--gv-color-border)]`).
  - `layouts/guest.blade.php`:
    - `body` also uses `gv-body` with token-based colours, plus `gv-skip-link` for accessibility.
- **Navigation bar** (`layouts/navigation.blade.php`)
  - Background and border now use token variables: `bg-[var(--gv-color-surface)]`, `border-[var(--gv-color-border)]`.
  - Primary nav items inherit `.gv-body` and tokenised text colours.
  - Dropdown triggers and user menu use neutral token colours and the `.gv-focus-ring` helper for focus-visible outlines.

These changes ensure that the main shell (header, layout, and base typography) is driven by tokens, so downstream feature work and addon reskins inherit a consistent foundation.

## 4. Flutter Theme Alignment

- **File**: `App/lib/gigvora_theme.dart`
  - Defines `GigvoraTheme.light()` which:
    - Uses a `ColorScheme.fromSeed` seeded with the brand blue (`#2563EB`).
    - Sets scaffold background to the neutral-50 equivalent, surfaces to white, and errors to the danger red.
    - Configures a text theme with Inter-like weights/sizes matching the web scale.
    - Provides themed `AppBar`, `CardTheme`, `ElevatedButtonTheme`, `TextButtonTheme`, `InputDecorationTheme`, `ChipTheme`, `IconTheme`, and `DividerTheme` aligned with token values.
- **Accessor**: `GigvoraThemeData` in `addons_integration.dart`
  - Exposes `GigvoraThemeData.light()` as the canonical `ThemeData` for the host app and addons.

## 5. Usage Guidelines

- **When building new components (web)**:
  - Prefer `.gv-*` utilities and CSS variables over raw hex values or ad-hoc Tailwind colours.
  - Keep spacing and radii aligned with token scales; avoid arbitrary pixel values where possible.
- **When building new screens (Flutter)**:
  - Ensure the root `MaterialApp` or `CupertinoApp` uses `GigvoraThemeData.light()`.
  - Avoid overriding core theme colours/fonts inside addons; rely on the shared theme instead.
- **Governance**:
  - Any changes to token values or additions must be reflected in:
    - `resources/css/gigvora/tokens.css`
    - This document
    - Corresponding Flutter theme adjustments in `gigvora_theme.dart`


