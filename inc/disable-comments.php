<?php
add_filter( 'comments_open', fn() => false, 20, 2 );
add_filter( 'pings_open', fn() => false, 20, 2 );
add_filter( 'comments_array', fn() => [], 10, 2 );

add_action( 'admin_menu', function (): void
{
	remove_menu_page( 'edit-comments.php' );
}, 100 );

add_action( 'wp_before_admin_bar_render', function (): void
{
	global $wp_admin_bar;

	if ( $wp_admin_bar instanceof WP_Admin_Bar ) {
		$wp_admin_bar->remove_menu( 'comments' );
	}
} );
