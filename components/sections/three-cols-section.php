<?php
/**
 * Component description
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = get_sub_field('heading') ?: '';
$left = (get_sub_field('col_left')) ?: null;
$center = (get_sub_field('col_center') ?: null);
$right = (get_sub_field('col_right')) ?: null;

?>

<section class="section">
	<div class="max-content-width">
		<h2 class="mb-12 text-darkest"><?= esc_html($heading) ?></h2>
		<div class="grid md:grid-cols-3 grid-cols-1 gap-x-12 gap-y-8">
			<div>
				<h3 class="max-w-sm text-dark mb-2"><?= esc_html(($left['heading'] ?? '') ?: 'Chybí nadpis') ?></h3>
				<div class="section__format-text text-dark mb-4">
					<?= ac_rich($left['description'] ?? '') ?>
				</div>
			</div>
			<div>
				<h3 class="max-w-sm text-dark mb-2"><?= esc_html(($center['heading'] ?? '') ?: 'Chybí nadpis') ?></h3>
				<div class="section__format-text text-dark mb-4">
					<?= ac_rich($center['description'] ?? '') ?>
				</div>
			</div>
			<div>
				<h3 class="max-w-sm text-dark mb-2"><?= esc_html(($right['heading'] ?? '') ?: 'Chybí nadpis') ?></h3>
				<div class="section__format-text text-dark mb-4">
					<?= ac_rich($right['description'] ?? '') ?>
				</div>
			</div>

<!--			<div class="section__format-text">-->
<!--				--><?php //= $right ?>
<!--			</div>-->
		</div>
	</div>
</section>