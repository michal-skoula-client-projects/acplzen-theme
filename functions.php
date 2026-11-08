<?php
/**
 * Theme bootstrap.
 *
 * wp-settings.php includes this file on EVERY request -- the front end,
 * wp-admin and wp-login.php alike. Anything that throws here takes the whole
 * site down, the operator's own way back in included, and nothing in core
 * repairs it: validate_current_theme() only runs from wp-admin/themes.php and
 * the Customizer, neither of which you can reach once this file fatals.
 *
 * So nothing lives here but constants and require_once lines. No logic, no
 * wp_die(), no 5xx, no code that can fail.
 *
 * @author Michal Škoula
 */

/**
 * Cache-busting version for assets/styles/output.css and assets/js/app.js.
 *
 * Bump it in the same commit as any change under assets/styles or assets/js,
 * or browsers keep serving the file they already have. CI fails the build if
 * you forget.
 */
const APP_VERSION = '1.0.0';

const ISSUE_REPORT_EMAIL = 'mailto:michal@skoula.com?subject=Chyba%20na%20webu%20acplzen.ujp.zcu.cz';
const PAGINATION_INTERVAL = 12;

/**
 * The client wants author names hidden while there is only one author. Here
 * so it is easy to turn back on later.
 */
const SHOW_AUTHOR_NAMES = false;

/** Warns in wp-admin when ACF Pro is not active. */
require_once __DIR__ . '/inc/acf-notice.php';

/** Date parsing, rich-text output, ac_404() and breadcrumbs. */
require_once __DIR__ . '/inc/helpers.php';

/** Asset enqueues, theme support flags, nav menu locations. */
require_once __DIR__ . '/inc/theme-supports.php';

/** The primary nav menu, and wp_nav_menu()'s li_class/submenu_class options. */
require_once __DIR__ . '/inc/menus.php';

/** Which editor each post type gets, which templates are reachable, admin columns. */
require_once __DIR__ . '/inc/post-types.php';

/** Removes dashboard widgets and sidebar entries the client does not use. */
require_once __DIR__ . '/inc/admin-cleanup.php';

/** Comments are off site-wide. */
require_once __DIR__ . '/inc/disable-comments.php';

/** dump()/rdump(); the file returns early in production. */
require_once __DIR__ . '/inc/debugging.php';

// ACF JSON for the footer only needs importing once.
// require __DIR__ . '/inc/footer-fields.php';
