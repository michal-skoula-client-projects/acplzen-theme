<?php
/**
 * Sekce se dvěma sloupci textu, kde pravý lze vypnout.
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_html(get_sub_field('heading') ?? '');
$only_left_col = get_sub_field('only_left_col');
$left = get_sub_field('description_left');
$right = get_sub_field('description_right');

?>

<section class="section">
	<div class="max-content-width">
		<?php if($heading): ?>
            <h2 class="mb-6 text-darkest"><?= $heading ?></h2>
		<?php endif; ?>
		<div class="grid md:grid-cols-2 grid-cols-1 gap-x-16 gap-y-4">
            <div class="section__format-text text-dark">
                <?= $left ?>
            </div>

            <?php if($only_left_col === 'ne'): ?>
            <div class="section__format-text text-dark">
                <?= $right ?>
            </div>
            <?php endif; ?>
		</div>
	</div>
</section>




