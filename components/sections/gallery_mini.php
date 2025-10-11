<?php
/**
 * Sekce mini galerie sloužící jako proklik na velkou galerii
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$btn = get_sub_field('btn_url');
$photos = get_sub_field('photos');

?>

<section class="section">
	<div class="max-content-width">
		<div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
			<?php if($photos): ?>
				<?php foreach ($photos as $photo): ?>
					<img src="<?= $photo['sizes']['medium_large'] ?>" alt="<?= $photo['title'] ?>" class="aspect-4/3 object-cover h-full rounded-sm">
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php if($btn): ?>
			<div class="flex justify-center mt-8">
				<?= get_template_part('components/blocks/ac_button', args: [
					'label' => $btn['title'] ?? 'Klikni mě',
					'href' => $btn['url'] ?? '#',
					'target' => '_self',
					'style' => 'secondary'
				]); ?>
			</div>
		<?php endif; ?>
	</div>
</section>



