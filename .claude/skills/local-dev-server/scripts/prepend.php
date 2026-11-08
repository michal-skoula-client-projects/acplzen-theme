<?php
/**
 * Prepended to every request by serve.sh: points WordPress at the host it's being served on,
 * so generated links stay on localhost instead of the Local domain stored in the DB.
 */
if ( PHP_SAPI !== 'cli-server' ) {
	return;
}

define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );

// URLs saved in post content still carry the Local domain; rewrite them in the response too.
ob_start( function ( string $buffer ): string {
	global $wpdb;

	if ( ! isset( $wpdb ) ) {
		return $buffer;
	}

	$stored_home = $wpdb->get_var( "SELECT option_value FROM {$wpdb->options} WHERE option_name = 'home'" );
	$stored_host = $stored_home ? parse_url( $stored_home, PHP_URL_HOST ) : null;

	if ( ! $stored_host ) {
		return $buffer;
	}

	return str_replace(
		[ "https://$stored_host", "http://$stored_host", "https:\\/\\/$stored_host", "http:\\/\\/$stored_host" ],
		[ WP_HOME, WP_HOME, str_replace( '/', '\\/', WP_HOME ), str_replace( '/', '\\/', WP_HOME ) ],
		$buffer
	);
} );
