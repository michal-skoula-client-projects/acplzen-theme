# AGENTS.md — Americké centrum Plzeň theme

Classic PHP WordPress theme (`AC Plzeň`, text domain `acplzen`) for a Czech-language
site. No framework, no Composer, no bundler: Tailwind's own CLI compiles one stylesheet
and that is the whole build. Keep it that way unless asked.

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
inc/                   everything functions.php used to do
  acf-notice.php       admin notice when ACF Pro is off
  helpers.php          ac_parse_date, ac_format_date, ac_rich, ac_404, ac_breadcrumbs
  theme-supports.php   enqueues, theme support flags, nav menu locations
  menus.php            ac_build_primary_menu + wp_nav_menu's li_class/submenu_class
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

## The two rules that exist because of an outage

**1. `functions.php` must never contain anything that can fail.**

`wp-settings.php` includes the active theme's `functions.php` on *every* request: the
front end, `/wp-admin/`, and `wp-login.php`. A fatal or a `wp_die()` there locks the
operator out of the only surface they have, and nothing in core repairs it —
`validate_current_theme()` runs only from `wp-admin/themes.php` and the Customizer,
neither of which you can reach. This theme has already taken the site down that way
once, by calling a framework bootstrap at file scope on a checkout with no `vendor/`.

So: constants and `require_once` lines only. No `wp_die()`, no 5xx, no `new`, no
function calls that execute work. Inside `inc/`, register hooks — don't run things at
file scope. Degrade in wp-admin with an `admin_notices` warning, never a hard stop.

**2. Bump `APP_VERSION` in the same commit that changes `assets/styles/*` or
`assets/js/*`.**

Nothing hashes filenames any more, so `APP_VERSION` in `functions.php` is the only cache
buster. CI fails a push that moves asset sources without moving it.

## Escaping

There is no general-purpose escaper. Pick by what the field is:

| Output                                   | Use                                     |
|------------------------------------------|-----------------------------------------|
| ACF `text` field, post title, excerpt    | `esc_html()`                            |
| ACF `wysiwyg`, or a `textarea` editors write HTML into (`description`, `about`, `recenze`, `information`, `text`, `contacts`, `answer`) | `ac_rich()` |
| `href`, `src`, `action`                  | `esc_url()`                             |
| any other attribute                      | `esc_attr()`                            |

Escape at the point of output, not at assignment, unless the whole file reads better the
other way — and then do it consistently within that file.

## Dates

ACF date values go through `ac_parse_date()` / `ac_format_date()` in
`inc/helpers.php`. Never call `DateTime::createFromFormat()` in a template.

It returns **`false`** on a value it cannot read, not `null`, so `?->` does not protect
you — `false?->format()` is a fatal just the same. An akce saved with an empty
`datetime` took the whole `/akce/` archive down this way. The helpers normalise `false`
to `null`, pin the site timezone, and reset unspecified fields to midnight instead of
inheriting the current time of day.

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
- **Null handling:** `get_field()` and `get_sub_field()` return `false` when empty, not
  `null`. Use `?:`, not `??`. `??` on them is a no-op and has been a source of bugs.
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
