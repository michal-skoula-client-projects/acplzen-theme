---
name: local-dev-server
description: "Use whenever you need to open, preview, screenshot or run JS against the local Americké centrum Plzeň site in the browser pane — checking a frontend change, reproducing a layout bug or a PHP fatal, testing at a viewport width. Starts the theme's LocalWP site on http://localhost:8882 (no per-site approval prompts, unlike acplzen.local) and explains what to set up when LocalWP or the site is missing."
compatibility: "macOS with LocalWP. Uses Local's bundled PHP and the site's own MySQL; the site must be running in Local. The acplzen site is pinned to PHP 8.3.36 to match production."
---

# Local dev server (localhost)

The browser pane prompts for approval on every host except localhost, so **never browse
`acplzen.local` (or any `*.local` domain)**. Use this server instead.

## Use

1. `preview_start` with `name: "wp"` (defined in `.claude/launch.json`). It runs
   `scripts/serve.sh` and opens `http://localhost:8882/`.
2. Drive it with the browser tools (`javascript_tool`, `resize_window`, screenshots) as usual.
3. PHP errors and the startup line show up in `preview_logs`.

What the script does: it finds the LocalWP site whose `wp-content/themes/` links to this repo.
Then it runs `php -S` with Local's own PHP binary and that site's `php.ini`, which carries the
MySQL socket. The site's existing DB and uploads are used, so nothing is copied.
`prepend.php` sets `WP_HOME`/`WP_SITEURL` to the requested host. It also rewrites the stored
Local domain in each response, which covers URLs saved in post content. Together these keep
every link and asset on localhost. `router.php` stands in for the nginx permalink rewrites.

Caveats:
- You log in to wp-admin separately on localhost, because cookies are per host.
- `php -S` is slower than nginx. Expect slow first loads.
- Port: set `PORT` to change it. Keep `.claude/launch.json` in sync. 8882 is used here so it
  does not collide with the PRO Plzeň site's 8881.

## The PHP version matters here

The `acplzen` Local site is deliberately on **PHP 8.3.36**, the version production runs, because
this theme has produced fatals that 8.4 does not reproduce. `serve.sh` reads the version from
Local's `sites.json` and uses that exact binary, so a reproduction through this server is a
genuine 8.3.36 reproduction — don't "fix" a fatal by testing it on a newer PHP.

To see the fatal rather than a blank white page, the usual `WP_DEBUG` route is unavailable from
here (`wp-config.php` lives in the Local site, not the repo). Instead read `preview_logs`, which
carries PHP's stderr, or query the request directly:

```bash
curl -sS -o /dev/null -w '%{http_code}\n' http://localhost:8882/
```

A 500 with nothing on screen means the trace is in `preview_logs`.

## When it fails: `SETUP NEEDED: …`

`serve.sh` stops with one `SETUP NEEDED:` line saying what is missing. Tell the user that line
and the matching step below. These are user actions, so don't try to do them yourself.

| Message says | What the user does |
|---|---|
| LocalWP is not installed | Install LocalWP (https://localwp.com), then do the next row. |
| No LocalWP site links this theme | Follow **Fresh machine setup** below. |
| PHP … binary is missing | Open the site in Local once so it downloads that PHP version. |
| MySQL isn't running | Start the site in Local. |

### Fresh machine setup

1. In Local, create a site named `acplzen`. Choose **PHP 8.3.x**, nginx and MySQL 8.
2. Replace its theme folder with a symlink to this repo:
   `ln -s <this repo> "<site>/app/public/wp-content/themes/acplzen-theme"`.
   The symlink's basename must be `acplzen-theme` — that is the slug the DB has active.
3. Bring content and plugins over from production. ACF PRO needs its licence key entered in
   wp-admin, and `acf-json/` in this repo supplies the field groups.
4. In the repo, run `npm install && npm run build`. `dist/` is gitignored and must be built.
5. Start the site in Local, then `preview_start` `wp` again.
