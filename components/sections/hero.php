<?php
/**
 * Hero sekce pro všechny stránky kromě homepage
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$bottom_gap = $args['bottom_gap'] ?? get_field('bottom_gap') ?: 'small';
$heading = $args['heading'] ?? get_field('heading') ?: 'Chybí nadpis';
$style = $args['style'] ?? get_field('description_style') ?: 'none';
$call_to_action = get_field('call_to_action') ?: 'ne';
$url = get_field('url') ?: null;
$description_left = $args['description_left'] ?? get_field('description_left') ?: '';
$description_right = $args['description_right'] ?? get_field('description_right') ?: '';

?>

<section class="section-px section-pt section-pb <?= $bottom_gap === 'small' ? 'section-pb-sm' : 'section-pb'?>">
	<div class="max-content-width">
		<div class="text-dark">
			<?= get_template_part('/components/blocks/ac_breadcrumbs', args: ['list_elems' => ac_breadcrumbs()])?>
			<h1 class="text-darkest mb-6 font-bold tracking-tight leading-[120%]"><?= esc_allow_safe($heading) ?></h1>

			<?php if($style === 'single'): ?>
				<p class="max-w-screen-md section__format-text">
					<?= esc_allow_safe($description_left) ?>
				</p>
			<?php endif; ?>

			<?php if($style === 'side-by-side'): ?>
				<div class="grid grid-cols-1 md:grid-cols-2 gap-x-20 gap-y-4 section__format-text">
					<p><?= esc_allow_safe($description_left) ?></p>
					<p><?= esc_allow_safe($description_right) ?></p>
				</div>
			<?php endif; ?>

            <?php if($call_to_action === 'ano' && $url): ?>
            	<?= get_template_part('components/blocks/ac_link', args: [
					'label' => $url['title'] ?? 'Klikni mě',
                    'href' => $url['url'] ?? '',
                    'target' => $url['target'] ?? '_self',
                    'class' => 'inline-block mt-4',
                    'color' => 'secondary',
                    'style' => 'dotted',
                ]) ?>
    		<?php endif; ?>
		</div>
	</div>
</section>




