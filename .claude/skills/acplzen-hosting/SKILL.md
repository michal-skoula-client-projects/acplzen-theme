---
name: acplzen-hosting
description: "Use before deploying, debugging production, or changing anything that affects what reaches the server for the Americké centrum Plzeň site (acplzen.zcu.cz). Covers the ZCU Kubernetes hosting and its hard limits — wp-admin is the only operator surface, there is no shell, no logs and no cluster access — how to tell a 503 from a WordPress fatal from maintenance mode, the recovery procedure when the theme takes the site down, and the WP Pusher branch rule. Load it whenever a production URL misbehaves or a change is about to be published."
---

# acplzen.zcu.cz hosting

## What this is

| | |
|---|---|
| Host | ZCU (Západočeská univerzita) Kubernetes, behind `svc1.kube.zcu.cz` |
| Image | bitnami/wordpress |
| PHP | 8.3.36 |
| Deploy | WP Pusher, pulling a git branch |
| Operator access | **wp-admin. That is all.** |

No SSH. No `wp` CLI. No file manager. No PHP error log. No database client. No
kubectl, no pod logs, no ability to restart anything. Nobody on this project can
read a stack trace off that server.

Everything below follows from that. **Plan for the failure you cannot debug.**

## The rule

**Never point WP Pusher at `main`.** It tracks `deploy`, which CI force-pushes with
`assets/styles/output.css` already built. `main` has no built CSS. WP Pusher's branch
picker defaults to the repository's default branch, so this is one careless dropdown
away at all times. A pulled `main` renders unstyled rather than fataling, but check the
branch anyway.

## Reading a broken production URL

Three failures look similar from outside. Tell them apart before touching anything.

**1. Uniform 503 — PHP is not running at all.**

```bash
curl -sS -o /dev/null -D- https://acplzen.zcu.cz/
curl -sS -o /dev/null -D- https://acplzen.zcu.cz/nonexistent-xyz123/
```

Signs: identical ~190-byte nginx body on *every* path, including `/wp-admin/` and
`/wp-login.php`; no `x-powered-by` header; a nonexistent URL returns **503, not 404**.

That is ingress-nginx with no healthy upstream — zero *Ready* pod endpoints. WordPress
never executes, so nothing in the theme or the database can fix it and nothing you
deploy will change it. The likely chain is that a 500 on the front page failed the
pod's readiness probe, which removed it from the Service's endpoints; this is
unconfirmed and unverifiable without cluster access. **Escalate to ZCU.**

**2. WordPress fatal — PHP runs, the theme breaks it.**

Signs: `x-powered-by: PHP/8.3.36` present, HTTP 500, and WordPress's own "There has
been a critical error on this website" page. `/wp-login.php` is usually broken too,
because `functions.php` is included on every request.

**3. Maintenance mode.** A `.maintenance` file, handled by `wp_maintenance()` at
`wp-settings.php:79` — before mu-plugins, plugins and the theme. Brief, and not
something the theme causes.

## Recovery when the theme fatals

If PHP runs but the theme kills every request, wp-admin included, there is exactly one
ask that works and it costs ZCU about thirty seconds:

> Please rename the directory `wp-content/themes/acplzen-theme` to
> `wp-content/themes/acplzen-theme-broken`.

WordPress then cannot find the active theme, falls back far enough to serve a page, and
`/wp-login.php` works again. Verified: this flips `/` from 500 to 200 and restores
admin. From there, fix the theme, redeploy, and switch the active theme back.

Note what does **not** happen: `validate_current_theme()`, the function that would
normally switch a broken theme back to a default, is called only from
`wp-admin/themes.php` and `WP_Customize_Manager`. A missing or fatal theme never
self-heals on a front-end request. Do not wait for it.

WordPress's recovery-mode email is worth checking if it arrived, but its link expires
after **1 day**, so it is usually stale by the time anyone looks.

## Before publishing anything

- `php -l` every changed PHP file.
- Confirm `functions.php` still contains nothing but constants and `require_once`.
- Check the theme renders on a checkout with no `node_modules` and no
  `assets/styles/output.css`: unstyled is acceptable, a fatal is not.
- Reproduce on the local site at PHP 8.3.36 (`local-dev-server` skill) rather than
  guessing from a diff.

## Credentials

**Never hard-code credentials, and never paste them into chat.** If this project grows
any, they go in `.secrets` at the theme root — gitignored and listed in `.gitignore`.
Prefer `curl --netrc-file` over `-u` so values do not sit in the process list, and write
netrc files to the scratchpad directory, never into the repo.
