<?php
add_action( 'wp_dashboard_setup', function (): void
{
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );   // Rychlý koncept
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );       // WP akce a novinky
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );    // Aktivity
	// remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // Aktuální přehled
} );

add_action( 'admin_menu', function (): void
{
	// Built-in posts are unused; everything is a custom post type.
	remove_menu_page( 'edit.php' );
}, 100 );
