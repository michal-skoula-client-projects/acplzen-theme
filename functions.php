<?php

if (is_file( __DIR__ . '/vendor/autoload_packages.php'))
{
    require_once __DIR__.'/vendor/autoload_packages.php';
}

use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use TailPress\Framework\Assets\Compiler;
use TailPress\Framework\Assets\ViteCompiler;
use TailPress\Framework\Features\MenuOptions;
use TailPress\Framework\Theme;

const ISSUE_REPORT_EMAIL = 'mailto:michal@skoula.com?subject=Chyba%20na%20webu%20kvitekplzen.cz';

function ac_in_production(): bool
{
	return !in_array(wp_get_environment_type(), ['development', 'local']);
}

if( ! ac_in_production() )
{
	include_once __DIR__ . '/src/Helpers/debugging.php';
}

require_once __DIR__ . '/src/menus.php';

function tailpress(): Theme
{
    return Theme::instance()
        ->assets(fn($manager) => $manager
            ->withCompiler(new ViteCompiler, fn( Compiler $compiler) => $compiler
                ->registerAsset('resources/css/app.css')
                ->registerAsset('resources/js/app.js')
                ->editorStyleFile('resources/css/editor-style.css')
            )
            ->enqueueAssets()
        )
        ->features(fn($manager) => $manager->add(MenuOptions::class))
        ->menus(fn($manager) => $manager->add('primary', __( 'Primary Menu', 'tailpress')))
        ->themeSupport(fn($manager) => $manager->add([
            'title-tag',
            'custom-logo',
            'post-thumbnails',
            'align-wide',
            'wp-block-styles',
            'responsive-embeds',
            'html5' => [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            ]
        ]));
}

tailpress();

function ac_enqueue_fonts(): void
{
	wp_enqueue_style('kvitek-fonts', get_template_directory_uri() . '/public/fonts/fonts.css');
}

add_action('init', 'ac_enqueue_fonts');

function ac_hide_entry_from_sidebar(...$entries): void
{
	if ( $entries ) {
		foreach ( $entries as $entry ) {
			add_action( 'admin_menu', function () use ( $entry ) {
				remove_menu_page( $entry );
			}, priority: 100 );
		}
	}
}

//ac_hide_entry_from_sidebar('edit.php', 'edit-comments.php');


function ac_remove_support_flag(string $post_type, string $flag): void
{
	add_action('init', function () use ($post_type, $flag) {
		remove_post_type_support($post_type, $flag);
	});
}

ac_remove_support_flag('page', 'editor');

#[NoReturn]
function ac_404(): void
{
	global $wp_query;
	$wp_query->set_404();
	status_header(404);
	nocache_headers();
	include( get_404_template());
	exit();
}

function ac_disable_single_template_view(string ...$slugs): void
{
	add_action('template_redirect', function () use ($slugs) {
			if (is_singular($slugs)) {
				ac_404();
			}
	});
}

ac_disable_single_template_view('kontakt', 'tabor');


function ac_disable_gutenberg(...$post_types): void
{
	if($post_types) {
		foreach ( $post_types as $post_type ) {
			add_filter('use_block_editor_for_post_type', function ($use_block_editor, $type) use ($post_type) {
				return $type === $post_type ? false : $use_block_editor;
			}, 10, 2);
		}
	}
}

ac_disable_gutenberg('program', 'akce');
/*
 *
 * Loading acf-json
 *
 */


/**
 * Register a custom column for a post type that shows a custom field.
 *
 * @param  string  $post_type  The post type slug (e.g. 'book').
 * @param  string  $field_name The custom field/meta key (e.g. 'author_name').
 * @param  bool  $sortable   Whether the column should be sortable.
 */
function kvitek_add_column_to_post_type_table( string $post_type, string $field_name, bool $sortable = false): void
{
	// Try to get the ACF field object to fetch label + choices
	$field_object = function_exists('acf_get_field') ? acf_get_field($field_name) : null;
	$column_label = $field_object['label'] ?? ucwords(str_replace('_', ' ', $field_name));
	$column_key   = sanitize_key($field_name);

	// 1. Add column header
	add_filter("manage_{$post_type}_posts_columns", function ($columns) use ($column_key, $column_label) {
		$columns[$column_key] = $column_label;
		return $columns;
	});

	// 2. Output value
	add_action("manage_{$post_type}_posts_custom_column", function ($column, $post_id) use ($column_key, $field_name, $field_object) {
		if ($column === $column_key) {
			// Get the raw value
			$value = get_post_meta($post_id, $field_name, true);

			// If field has choices (button group, select, radio), map to label
			if ($field_object && !empty($field_object['choices']) && isset($field_object['choices'][$value])) {
				$value = $field_object['choices'][$value];
			}

			echo esc_html($value ?: '—');
		}
	}, 10, 2);

	// 3. Make sortable if requested
	if ($sortable) {
		add_filter("manage_edit-{$post_type}_sortable_columns", function ($columns) use ($column_key, $field_name) {
			$columns[$column_key] = $field_name;
			return $columns;
		});

		add_action('pre_get_posts', function (WP_Query $query) use ($post_type, $field_name) {
			if (!is_admin() || !$query->is_main_query()) {
				return;
			}
			if ($query->get('post_type') === $post_type && $query->get('orderby') === $field_name) {
				$query->set('meta_key', $field_name);
				$query->set('orderby', 'meta_value');
			}
		});
	}
}

kvitek_add_column_to_post_type_table('tabor', 'availability', true);
kvitek_add_column_to_post_type_table('album','event_date',true);

function esc_allow_safe(string $text): string
{
	$config = new HtmlSanitizerConfig()
		->allowSafeElements()
		->allowAttribute('class', '*');

	return new HtmlSanitizer($config)->sanitize($text);
}