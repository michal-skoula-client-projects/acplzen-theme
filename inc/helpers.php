<?php
/**
 * Parses an ACF date value. Null when empty or unparseable.
 * The '!' prefix zeroes fields the format omits instead of using the current time.
 */
function ac_parse_date( ?string $value, string $format = 'Y-m-d H:i:s' ): ?DateTimeImmutable
{
	if ( ! $value ) {
		return null;
	}

	if ( ! str_starts_with( $format, '!' ) ) {
		$format = '!' . $format;
	}

	$date = DateTimeImmutable::createFromFormat( $format, $value, wp_timezone() );

	return $date ?: null;
}

/** Formats an ACF date value with wp_date(). Empty string when unparseable. */
function ac_format_date( ?string $value, string $output, string $format = 'Y-m-d H:i:s' ): string
{
	$date = ac_parse_date( $value, $format );

	return $date ? wp_date( $output, $date->getTimestamp() ) : '';
}

function ac_in_production(): bool
{
	return ! in_array( wp_get_environment_type(), [ 'development', 'local' ], true );
}

function ac_404(): never
{
	global $wp_query;

	if ( $wp_query instanceof WP_Query ) {
		$wp_query->set_404();
	}

	status_header( 404 );
	nocache_headers();
	include get_404_template();
	exit;
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
