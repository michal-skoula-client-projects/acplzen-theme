<?php
/**
 * Per-post-type tweaks: which editor a type gets, which templates are
 * reachable, and which custom fields show as admin columns.
 *
 * Every registration below runs inside after_setup_theme rather than at file
 * scope. That keeps functions.php free of anything that executes, and all of
 * these hooks (init, template_redirect, admin columns) still fire afterwards.
 */

/** Removes a post type support flag, e.g. the editor on pages. */
function ac_remove_support_flag( string $post_type, string $flag ): void
{
	add_action( 'init', function () use ( $post_type, $flag ) {
		remove_post_type_support( $post_type, $flag );
	} );
}

/** Serves the 404 template for the given single post types. */
function ac_disable_single_template_view( string ...$slugs ): void
{
	add_action( 'template_redirect', function () use ( $slugs ) {
		if ( is_singular( $slugs ) ) {
			ac_404();
		}
	} );
}

/** Serves the 404 template for the given post type archives. */
function ac_disable_archive_template_view( string ...$slugs ): void
{
	add_action( 'template_redirect', function () use ( $slugs ) {
		if ( is_archive( $slugs ) ) {
			ac_404();
		}
	} );
}

/** Forces the classic editor for the given post types. */
function ac_disable_gutenberg( string ...$post_types ): void
{
	foreach ( $post_types as $post_type ) {
		add_filter( 'use_block_editor_for_post_type', function ( $use_block_editor, $type ) use ( $post_type ) {
			return $type === $post_type ? false : $use_block_editor;
		}, 10, 2 );
	}
}

/**
 * Shows a custom field as a column in a post type's admin list table.
 *
 * The ACF field object is resolved lazily inside the callbacks: ACF is not
 * loaded while the theme's functions.php runs, so asking it for a field at
 * registration time returned nothing and the column fell back to a
 * prettified field name. Values are read with get_post_meta(), so the column
 * still renders if ACF is deactivated.
 *
 * @param  string  $post_type   Post type slug, e.g. 'akce'.
 * @param  string  $field_name  Meta key / ACF field name, e.g. 'datetime'.
 * @param  bool    $sortable    Whether the column should be sortable.
 */
function ac_add_column_to_post_type_table( string $post_type, string $field_name, bool $sortable = false ): void
{
	$column_key = sanitize_key( $field_name );

	$get_field_object = static fn (): ?array => function_exists( 'acf_get_field' )
		? ( acf_get_field( $field_name ) ?: null )
		: null;

	// 1. Column header
	add_filter( "manage_{$post_type}_posts_columns", function ( $columns ) use ( $column_key, $field_name, $get_field_object ) {
		$columns[ $column_key ] = $get_field_object()['label']
			?? ucwords( str_replace( '_', ' ', $field_name ) );

		return $columns;
	} );

	// 2. Column value
	add_action( "manage_{$post_type}_posts_custom_column", function ( $column, $post_id ) use ( $column_key, $field_name, $get_field_object ) {
		if ( $column !== $column_key ) {
			return;
		}

		$value = get_post_meta( $post_id, $field_name, true );

		// Button groups, selects and radios store the key -- show the label.
		$choices = $get_field_object()['choices'] ?? null;
		if ( $choices && isset( $choices[ $value ] ) ) {
			$value = $choices[ $value ];
		}

		echo esc_html( $value ?: '—' );
	}, 10, 2 );

	// 3. Sorting
	if ( ! $sortable ) {
		return;
	}

	add_filter( "manage_edit-{$post_type}_sortable_columns", function ( $columns ) use ( $column_key, $field_name ) {
		$columns[ $column_key ] = $field_name;

		return $columns;
	} );

	add_action( 'pre_get_posts', function ( WP_Query $query ) use ( $post_type, $field_name ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( $query->get( 'post_type' ) === $post_type && $query->get( 'orderby' ) === $field_name ) {
			$query->set( 'meta_key', $field_name );
			$query->set( 'orderby', 'meta_value' );
		}
	} );
}

add_action( 'after_setup_theme', function (): void
{
	// Pages are built with the section builder, not the editor.
	ac_remove_support_flag( 'page', 'editor' );

	ac_disable_gutenberg( 'program', 'akce' );

	// 'program' entries are listed on the akce archive; they have no page.
	ac_disable_single_template_view( 'program' );
	// ac_disable_archive_template_view( 'sluzba' );

	ac_add_column_to_post_type_table( 'akce', 'event_type', sortable: true );
	ac_add_column_to_post_type_table( 'akce', 'datetime', sortable: true );
} );
