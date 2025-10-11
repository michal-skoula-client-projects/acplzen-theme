<?php
/**
 * Sekce se dvěma sloupci, kde v levém je text, dobrovolně tlačítko a v pravém obrázek. Bez speciálního pozadí.
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$content = get_sub_field('content');
$image = get_sub_field('img');

$heading = $content['heading'] ?? 'Chybí nadpis';
$description = $content['description'] ?? '';
$btn = $content['url'];

?>

<section class="section">
	<div class="max-content-width">
		<div class="grid md:grid-cols-2 grid-cols-1 gap-x-16 gap-y-4">
			<div class="md:order-1 order-2">
				<h2 class="mb-6 text-darkest"><?= $heading ?></h2>
				<div class="section__format-text text-dark">
					<?= $description ?>
				</div>
				<?php if($btn): ?>
					<?php get_template_part('/components/blocks/ac_button', args: [
						'href' => $btn['url'] ?? '#',
						'label' => $btn['title'] ?? 'Klikni mě',
						'target' => $btn['target'] ?? '',
					]) ?>
				<?php endif; ?>
			</div>
			<div class="md:order-2 order-1">
				<?php if($image): ?>
					<img
						src="<?= esc_url($image['sizes']['large']) ?>"
						alt="<?= esc_html($image['title']) ?>"
						class="rounded-sm">
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>




