<?php
/**
 * Warns in wp-admin when ACF Pro is missing.
 *
 * Front-end templates call get_field() unguarded and will fatal without the
 * plugin. That is acceptable: wp-admin never runs them, so the operator can
 * always get back in and re-enable it. What is NOT acceptable is this file
 * trying to be clever about it — nothing here may wp_die(), send a 5xx or
 * throw, because functions.php is included on EVERY request, wp-login.php
 * included, and a fatal here locks the only operator out of the site.
 */

add_action( 'admin_notices', function (): void
{
	if ( class_exists( 'ACF' ) ) {
		return;
	}

	echo '<div class="notice notice-error"><p><strong>ACF Pro není aktivní.</strong> '
		. 'Dokud se znovu nezapne, stránky na webu se nebudou zobrazovat.</p></div>';
} );
