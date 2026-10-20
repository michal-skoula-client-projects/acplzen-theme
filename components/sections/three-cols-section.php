<?php
/**
 * Component description
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_allow_safe(get_sub_field('heading') ?: null);
$left = (get_sub_field('col_left')) ?: null;
$center = (get_sub_field('col_center') ?: null);
$right = (get_sub_field('col_right')) ?: null;

?>

<section class="section">
	<div class="max-content-width">
		<h2 class="mb-12 text-darkest"><?= $heading ?></h2>
		<div class="grid md:grid-cols-3 grid-cols-1 gap-x-12 gap-y-8">
			<div>
				<h3 class="max-w-sm text-dark mb-2"><?= $left['heading'] ?: 'Chybí nadpis' ?></h3>
				<div class="section__format-text text-dark mb-4">
					<?= $left['description'] ?: '' ?>
				</div>
			</div>
			<div>
				<h3 class="max-w-sm text-dark mb-2"><?= $center['heading'] ?: 'Chybí nadpis' ?></h3>
				<div class="section__format-text text-dark mb-4">
					<?= $center['description'] ?: '' ?>
				</div>
			</div>
			<div>
				<h3 class="max-w-sm text-dark mb-2"><?= $right['heading'] ?: 'Chybí nadpis' ?></h3>
				<div class="section__format-text text-dark mb-4">
					<?= $right['description'] ?: '' ?>
				</div>
			</div>

<!--			<div class="section__format-text">-->
<!--				--><?php //= $right ?>
<!--			</div>-->
		</div>
	</div>
</section>