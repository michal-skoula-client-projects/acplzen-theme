<?php
/**
 * `php -S` router: serves real files directly and sends everything else through WordPress,
 * standing in for the nginx rewrite rules Local uses.
 */
if ( PHP_SAPI !== 'cli-server' ) {
	return;
}

$root = $_SERVER['DOCUMENT_ROOT'];
$path = rawurldecode( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );

if ( is_file( $root . $path ) ) {
	return false;
}

$dir_index = rtrim( $root . $path, '/' ) . '/index.php';
$script    = is_file( $dir_index ) ? $dir_index : $root . '/index.php';

$_SERVER['SCRIPT_NAME']     = substr( $script, strlen( $root ) );
$_SERVER['SCRIPT_FILENAME'] = $script;
$_SERVER['PHP_SELF']        = $_SERVER['SCRIPT_NAME'];

chdir( dirname( $script ) );
require $script;
