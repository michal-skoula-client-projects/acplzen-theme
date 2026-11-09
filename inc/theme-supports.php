<?php
add_action( 'wp_enqueue_scripts', function (): void
{
	wp_enqueue_style(
		'ac-theme',
		get_template_directory_uri() . '/assets/styles/output.css',
		[],
		APP_VERSION
	);

	wp_enqueue_script(
		'ac-app',
		get_template_directory_uri() . '/assets/js/app.js',
		[],
		APP_VERSION,
		[
			'strategy'  => 'defer',
			'in_footer' => true,
		]
	);
} );

add_action( 'after_setup_theme', function (): void
{
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	] );

	add_theme_support( 'editor-styles' );
	add_editor_style( [ 'assets/styles/output.css' ] );

	register_nav_menus( [
		'primary' => 'Hlavní menu',
		// 'footer' => 'Zápatí (footer)',
	] );
} );
