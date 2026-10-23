<?php

if (is_file( __DIR__ . '/vendor/autoload_packages.php')) {
    require_once __DIR__.'/vendor/autoload_packages.php';
}

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use TailPress\Framework\Assets\Compiler;
use TailPress\Framework\Assets\ViteCompiler;
use TailPress\Framework\Features\MenuOptions;
use TailPress\Framework\Theme;

# Check if ACF is installed. The theme cannot render without it.
if (! class_exists('ACF')) {
    $ac_acf_recoverable = is_admin()
        || wp_doing_cron()
        || (defined('WP_CLI') && WP_CLI)
        || (defined('REST_REQUEST') && REST_REQUEST)
        || (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php');

    if ($ac_acf_recoverable) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>ACF Pro is not enabled.</strong> Install or enable the plugin for the theme to load properly.</p></div>';
        });
    } else {
        wp_die(
            'The site is temporarily unavailable.',
            'Site unavailable',
            ['response' => 503]
        );
    }
}

const ISSUE_REPORT_EMAIL = 'mailto:michal@skoula.com?subject=Chyba%20na%20webu%20acplzen.ujp.zcu.cz';
const PAGINATION_INTERVAL = 12;

/** Client wants to hide author names since there's only one author
 * for now. This is to make it easy to re-enable later
 */
const SHOW_AUTHOR_NAMES = false;

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
            )
            ->enqueueAssets()
        )
        ->features(fn($manager) => $manager->add(MenuOptions::class))
        ->menus(fn($manager) => $manager
	        ->add('primary', 'Hlavní menu')
//	        ->add('footer', 'Zápatí (footer)')
	    )
        ->themeSupport(fn($manager) => $manager->add([
            'title-tag',
            'custom-logo',
            'post-thumbnails',
//            'align-wide',
//            'wp-block-styles',
//            'responsive-embeds',
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
	wp_enqueue_style('ac-fonts', get_template_directory_uri() . '/public/fonts/fonts.css');
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

ac_hide_entry_from_sidebar('edit.php', 'edit-comments.php');


function ac_remove_support_flag(string $post_type, string $flag): void
{
	add_action('init', function () use ($post_type, $flag) {
		remove_post_type_support($post_type, $flag);
	});
}

ac_remove_support_flag('page', 'editor');


function ac_404(): never
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

ac_disable_single_template_view('program');

function ac_disable_archive_template_view(string ...$slugs): void
{
	add_action('template_redirect', function () use ($slugs) {
		if (is_archive($slugs)) {
			ac_404();
		}
	});
}

//ac_disable_archive_template_view('sluzba');

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
function ac_add_column_to_post_type_table( string $post_type, string $field_name, bool $sortable = false): void
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

ac_add_column_to_post_type_table('akce', 'event_type',  sortable: true);
ac_add_column_to_post_type_table('akce', 'datetime', sortable: true);


function esc_allow_safe(string $text): string
{
	$config = (new HtmlSanitizerConfig())
		->allowSafeElements()
		->allowAttribute('class', '*');

	return (new HtmlSanitizer($config))->sanitize($text);
}

/**
 * Build a breadcrumb trail as an ordered array of ['label' => ..., 'url' => ...].
 * Handles: front page, blog index, singular (pages, posts, CPT), 404.
 *
 * @return array<array<string,string>>
 */
function ac_breadcrumbs(): array {
	global $post;

	$trail = [];

	// 0) Home
	$trail[] = [ 'label' => 'Domů', 'url' => home_url( '/' ) ];

	// FRONT PAGE
	if ( is_front_page() ) {
		return $trail; // just Home
	}

	// BLOG INDEX (when a static page is used for posts)
	$page_for_posts = (int) get_option( 'page_for_posts' );

	// ARCHIVES (post type)
	if ( is_post_type_archive() ) {
		$pto = get_post_type_object( get_query_var( 'post_type' ) );
		if ( $pto && ! is_wp_error( $pto ) ) {
			$trail[] = [ 'label' => $pto->labels->name, 'url' => get_post_type_archive_link( $pto->name ) ];
		}

		return $trail;
	}

	// SINGULAR (posts, pages, CPT)
	if (is_singular()) {
		$qid = get_queried_object_id();
		$ptype = get_post_type($qid);

		// Blog index if needed
		$page_for_posts = (int) get_option('page_for_posts');
		if ($ptype === 'post' && $page_for_posts) {
			$trail[] = ['label' => get_the_title($page_for_posts), 'url' => get_permalink($page_for_posts)];
		}

		// CPT archive
		if ($ptype !== 'post') {
			$pto = get_post_type_object($ptype);
			if ($pto && $pto->has_archive) {
				$trail[] = ['label' => $pto->labels->name, 'url' => get_post_type_archive_link($ptype)];
			}
		}

		// Ancestors (works for hierarchical types like 'page')
		if (is_post_type_hierarchical($ptype)) {
			$ancestors = array_reverse(get_post_ancestors($qid));
			foreach ($ancestors as $ancestor_id) {
				$trail[] = [
					'label' => get_the_title($ancestor_id),
					'url'   => get_permalink($ancestor_id),
				];
			}
		}

		// Current item
		$trail[] = ['label' => get_the_title($qid), 'url' => get_permalink($qid)];
		return $trail;
	}

	return $trail;
}

// Removing stupid widgets
add_action('wp_dashboard_setup', function() {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');    // Rychlý koncept
    remove_meta_box('dashboard_primary', 'dashboard', 'side');        // WP akce a novinky
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');     // Aktivity
    //remove_meta_box('dashboard_right_now', 'dashboard', 'normal');    // Aktuální přehled
});

// Disabling comments
add_action('admin_init', function() {
    remove_menu_page('edit-comments.php');
    
    add_action('wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });
});

add_filter('comments_open', fn() => false, 20, 2);
add_filter('pings_open', fn() => false, 20, 2);
add_filter('comments_array', fn() => [], 10, 2);

add_action('admin_menu', fn() => remove_menu_page('edit-comments.php'));


//ACF JSON for the footer, only needs to be imported once.
//require __DIR__ . '/src/footer-fields.php';