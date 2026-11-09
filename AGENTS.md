# AGENTS.md — Americké centrum Plzeň theme

Classic PHP WordPress theme (`AC Plzeň`, text domain `acplzen`) for a Czech-language
site. No framework, no Composer, no bundler: the Tailwind CLI compiles one stylesheet.

Production: <https://acplzen.zcu.cz/>. Read `.claude/skills/acplzen-hosting/SKILL.md`
**before** touching anything that affects what gets deployed — the hosting has no
operator access beyond wp-admin, which makes a fatal on this theme unusually expensive.

## Foundational context

| Package   | Version                                           |
|-----------|---------------------------------------------------|
| WordPress | bitnami/wordpress on ZCU Kubernetes               |
| PHP       | 8.3.36 in production; `php >= 8.2` syntax is safe |
| ACF Pro   | required — templates do not render without it     |
| Tailwind  | v4, via `@tailwindcss/cli`                        |

Layout:

```
functions.php          constants + require_once only. Nothing else.
inc/                   hooks and helpers, one concern per file
  acf-notice.php       admin notice when ACF Pro is off
  helpers.php          ac_parse_date, ac_format_date, ac_404, ac_breadcrumbs
  theme-supports.php   enqueues, theme support flags, nav menu locations
  menus.php            ac_build_primary_menu
  post-types.php       editors, reachable templates, admin columns
  admin-cleanup.php    dashboard widgets, unused menus
  disable-comments.php comments off site-wide
  debugging.php        dump()/rdump(), returns early in production
assets/styles/         global.css (entry) + theme.css, navbar.css, fonts.css
assets/styles/output.css  BUILT. gitignored. never commit it
assets/js/app.js       navbar behaviour
assets/fonts/          Montserrat woff2
assets/images/
components/sections/   builder sections, rendered via components/builder.php
components/blocks/     small reusable partials
acf-json/              ACF field groups, synced from wp-admin
```

Templates live at the theme root (`header.php`, `front-page.php`, `page.php`,
`single-*.php`, `archive-*.php`, `404.php`, `footer.php`). Add new files within this
layout — don't introduce a new architecture.

## Rules

**`functions.php` holds constants and `require_once` lines only.** It runs on every
request, wp-admin and wp-login.php included, so a fatal or `wp_die()` there locks out the
only access we have. Inside `inc/`, register hooks; don't run work at file scope. Warn
with `admin_notices`, never a hard stop.

**Bump `APP_VERSION` when `assets/styles/*` or `assets/js/*` change.** It is the only
cache buster.

## Escaping

There is no general-purpose escaper. Pick by what the field is:

| Output                                   | Use                                     |
|------------------------------------------|-----------------------------------------|
| ACF `text` field, post title, excerpt    | `esc_html()`                            |
| ACF `wysiwyg`, or a `textarea` editors write HTML into (`description`, `about`, `recenze`, `information`, `text`, `contacts`, `answer`) | `wp_kses_post()` |
| `href`, `src`, `action`                  | `esc_url()`                             |
| any other attribute                      | `esc_attr()`                            |

Escape at the point of output, not at assignment, unless the whole file reads better the
other way — and then do it consistently within that file.

## Dates

ACF date values go through `ac_parse_date()` / `ac_format_date()` in
`inc/helpers.php`. Never call `DateTime::createFromFormat()` in a template.

It returns `false` on bad input, so `?->` doesn't protect you. The helpers return `null`.

Use `wp_date()` over `date()` and `date_i18n()` for anything a visitor sees: site
timezone, and Czech month names.

## Code style

Match the surrounding file. Across the codebase:

- **Arrays:** short form `[]` only, never `array()`.
- **Indentation:** tabs.
- **Templates:** alternative syntax (`if ( … ) : … endif;`, `while … endwhile;`).
  Business logic can use braces.
- **Echo in templates:** `<?= … ?>`, never `<?php echo … ?>`.
- **Return types:** on every function you write, and added to any you touch without one.
- **PHPDoc:** where it adds clarity — array shapes, hook callbacks, ACF return values.
  Say *why*, not *what*.
- **Null handling:** `get_field()` and `get_sub_field()` return `false` when empty, so
  use `?:`, not `??`.
- **i18n:** UI labels are hard-coded in Czech.
- **CSS:** reuse the custom properties in `assets/styles/theme.css`. The palette is a
  full reset (`--color-*: initial`), so Tailwind's default colours — `bg-white`,
  `bg-black` — do not exist. Same for `--radius-*`.

Do not create documentation or markdown files unless asked.

## Build and local dev

```bash
npm install
npm run build    # one-shot, minified
npm run watch    # rebuild on change
```

For anything in the browser pane, don't open `acplzen.local` — use the
`local-dev-server` skill (`preview_start` → `wp`, <http://localhost:8882>) so JS and
navigation run without per-site approval prompts.

## Skills

Skills live in `.claude/skills/`. **Activate the relevant one as soon as you start work
in its domain — don't wait until you're stuck.**

- `acplzen-hosting` — **read before anything that reaches production.** ZCU
  Kubernetes, no cluster access, how to read a 503 vs a WP fatal, the recovery
  procedure, and why WP Pusher must never point at `main`.
- `local-dev-server` — serves the LocalWP site on <http://localhost:8882> for the
  browser pane.
