<?php

/** Cache buster for output.css and app.js. Bump it when either changes. */
const APP_VERSION = '1.0.1';

const ISSUE_REPORT_EMAIL = 'mailto:michal@skoula.com?subject=Chyba%20na%20webu%20acplzen.zcu.cz';

const PAGINATION_INTERVAL = 12;

/**
 * Enable / Disable author names toggle across the website.
 */
const SHOW_AUTHOR_NAMES = false;

require_once __DIR__ . '/inc/acf-notice.php';
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/theme-supports.php';
require_once __DIR__ . '/inc/menus.php';
require_once __DIR__ . '/inc/post-types.php';
require_once __DIR__ . '/inc/admin-cleanup.php';
require_once __DIR__ . '/inc/disable-comments.php';
require_once __DIR__ . '/inc/debugging.php';
