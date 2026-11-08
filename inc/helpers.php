<?php
/**
 * Small helpers shared by the templates.
 *
 * These exist to make the dangerous things safe by default: ACF date fields
 * that cannot be parsed, and WYSIWYG fields that must keep their markup.
 */

/**
 * Parses an ACF date or date-time string into an immutable date, or null.
 *
 * DateTime::createFromFormat() returns FALSE on failure, not null, so the
 * nullsafe operator (`?->`) does NOT protect a call site against a bad value
 * -- `false?->format()` is still a fatal. Normalising FALSE to null here is
 * what makes `?->` and `if (...)` actually safe everywhere else.
 *
 * The format is prefixed with '!' so any field the format does not mention
 * resets to midnight instead of inheriting the current time of day.
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

/**
 * Renders an ACF date field, localised and in the site's timezone.
 *
 * Returns an empty string when the value is missing or unparseable -- never a
 * fatal, never a silently wrong date.
 *
 * @param  string  $output  A date() format string, e.g. 'd.m.Y'.
 * @param  string  $format  The format the stored value is in.
 */
function ac_format_date( ?string $value, string $output, string $format = 'Y-m-d H:i:s' ): string
{
	$date = ac_parse_date( $value, $format );

	return $date ? wp_date( $output, $date->getTimestamp() ) : '';
}

/**
 * Output for a WYSIWYG / rich-text field: keeps the markup, drops anything
 * unsafe. Plain-text fields must use esc_html() instead -- this is not a
 * general-purpose escaper.
 */
function ac_rich( ?string $html ): string
{
	return wp_kses_post( (string) $html );
}
