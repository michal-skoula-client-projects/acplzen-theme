#!/usr/bin/env bash
# Serves this theme's LocalWP site on http://localhost:$PORT (default 8882) with Local's own PHP,
# so the Claude browser pane can drive it without per-site approval prompts.
# Every failure prints a "SETUP NEEDED:" line that SKILL.md maps to setup steps.
set -euo pipefail

scripts_dir="$(cd "$(dirname "$(realpath "${BASH_SOURCE[0]}")")" && pwd -P)"
theme_dir="$(cd "$scripts_dir/../../../.." && pwd -P)"
port="${PORT:-8882}"
local_dir="$HOME/Library/Application Support/Local"

fail() {
	echo "SETUP NEEDED: $1" >&2
	exit 1
}

[[ -d "$local_dir/run" && -f "$local_dir/sites.json" ]] \
	|| fail "LocalWP is not installed (no '$local_dir'). Install it from https://localwp.com and create the site."

# Find the Local site whose wp-content/themes contains an entry resolving to this theme.
# Local writes either conf/nginx/site.conf (root ...;) or conf/apache/site.conf (DocumentRoot "...")
# depending on the web server the site was created with, so check both.
run_dir=""
docroot=""
for conf in "$local_dir"/run/*/conf/nginx/site.conf "$local_dir"/run/*/conf/apache/site.conf; do
	[[ -f "$conf" ]] || continue
	root="$(sed -nE 's/^[[:space:]]*root[[:space:]]+"?([^";]+)"?;.*/\1/p;s/^[[:space:]]*DocumentRoot[[:space:]]+"?([^"]+)"?[[:space:]]*$/\1/p' "$conf" | head -1)"
	[[ -d "$root/wp-content/themes" ]] || continue
	for theme in "$root"/wp-content/themes/*; do
		if [[ "$(realpath "$theme" 2>/dev/null)" == "$theme_dir" ]]; then
			run_dir="$(cd "$(dirname "$conf")/../.." && pwd -P)"
			docroot="$root"
			break 2
		fi
	done
done

[[ -n "$run_dir" ]] \
	|| fail "No LocalWP site links this theme. Symlink '$theme_dir' into <site>/app/public/wp-content/themes/$(basename "$theme_dir") and start the site in Local."

site_id="$(basename "$run_dir")"
php_version="$(python3 -c 'import json,sys; print(json.load(open(sys.argv[1]))[sys.argv[2]]["services"]["php"]["version"])' "$local_dir/sites.json" "$site_id")"

php_bin=""
for candidate in "$local_dir"/lightning-services/php-"$php_version"+*/bin/*/bin/php; do
	[[ -x "$candidate" ]] && php_bin="$candidate" && break
done
[[ -n "$php_bin" ]] || fail "LocalWP's PHP $php_version binary is missing. Open the site in Local once so it downloads it."

ini="$run_dir/conf/php/php.ini"
socket="$(sed -nE 's/^mysqli\.default_socket[[:space:]]*=[[:space:]]*"?([^"]+)"?/\1/p' "$ini" | head -1)"
[[ -S "$socket" ]] || fail "The site's MySQL isn't running. Start the site in Local, then retry."

echo "Serving $docroot on http://localhost:$port (PHP $php_version, Local site $site_id)"
cd "$docroot"
PHP_CLI_SERVER_WORKERS=4 exec "$php_bin" -c "$ini" \
	-d auto_prepend_file="$scripts_dir/prepend.php" \
	-S "localhost:$port" -t "$docroot" "$scripts_dir/router.php"
