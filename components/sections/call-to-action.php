<?php
/**
 * Call to action komponent
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_allow_safe(get_sub_field('heading'));
$description = esc_allow_safe(get_sub_field('description'));
$btn_primary = get_sub_field('btn_primary');
$btn_secondary = get_sub_field('btn_secondary')

?>

<section class="section">
	<div class="max-content-width rounded-md border border-light-outline bg-light-elevated p-12">
		<h2 class="text-darkest text-center mb-1"><?= $heading ?></h2>
		<p class="text-dark text-center mb-5"><?= $description ?></p>
		<div class="flex items-center justify-center gap-4 flex-wrap">
			<?php if($btn_primary): ?>
				<?php get_template_part('/components/blocks/ac_button', args: [
					'style' => 'primary',
					'href' => $btn_primary['url'] ?? '#',
					'label' => $btn_primary['title'] ?? 'Klikni mě',
					'target' => $btn_primary['target'] ?? '',
				]) ?>
			<?php endif; ?>
			<?php if($btn_secondary): ?>
				<?php get_template_part('/components/blocks/ac_link', args: [
					'color' => 'dark',
					'style' => 'dotted',
					'href' => $btn_secondary['url'] ?? '#',
					'label' => $btn_secondary['title'] ?? 'Klikni mě',
					'target' => $btn_secondary['target'] ?? '',
				]) ?>
			<?php endif; ?>
		</div>

	</div>
</section>



