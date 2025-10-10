<?php
/**
 * Builds the sections defined in the flexible content field page builder
 *
 * @author Michal Škoula
 * @since 1.0.0
 */


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
			'about' => __DIR__ . '/sections/about.php',
			'camps_list' => __DIR__ . '/sections/camps_list.php',
			'2col_text_img' => __DIR__ . '/sections/two_col_text_img.php',
			'2col_text_text' => __DIR__ . '/sections/two_col_text_text.php',
			'text_centered' => __DIR__ . '/sections/text_centered.php',
			'contacts' => __DIR__ . '/sections/contacts.php',
			'sponsors' => __DIR__ . '/sections/sponsors.php',
			default => null,
		};

		if (! $section_file && ! ac_in_production()) {
			throw new InvalidSectionException("Unknown section: $section_slug");
		}

		include_section_if_exists($section_file);

	endwhile;

endif;



