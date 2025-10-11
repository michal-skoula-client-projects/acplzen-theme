<?php
/**
 * Builds the sections defined in the flexible content field page builder
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

require __DIR__ . '/exceptions/InvalidSectionException.php';

/**
 * @param  string  $filepath full file path
 *
 * @return void
 * @throws InvalidSectionException
 */
function include_section_if_exists(string $filepath): void
{
	if(file_exists($filepath)) {
		include $filepath;
	}
	else if(! ac_in_production()) {
		throw new InvalidSectionException("Missing template file for: $filepath");
	}
}

if(have_rows('sections')):
	while(have_rows('sections')): the_row();

		$section_slug = get_row_layout();

		if(! $section_slug):
			if(! ac_in_production()) {
				throw new InvalidSectionException("Missing slug in the layout row");
			}
			continue;
		endif;

		$section_file = match($section_slug) {
			'services' => '/sections/services.php',
			'faq' => '/sections/faq.php',
			'sponsors' => '/sections/sponsors.php',
			default => null,
		};

		if (! $section_file && ! ac_in_production()) {
			throw new InvalidSectionException("Unknown section: $section_slug");
		}

		include_section_if_exists(__DIR__ . $section_file);

	endwhile;
endif;



