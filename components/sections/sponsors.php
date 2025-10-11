<?php
/**
 * Section showcasing sponsors and their logos
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = get_field('partners_heading', 'options');
$sponsors = get_field('partners', 'options');

?>

<section class="section">
	<div class="max-content-width p-12 rounded-md border border-light-outline bg-light-elevated">
		<div>
			<h2 class="text-center text-darker mb-12"><?= $heading ?></h2>

			<?php if($sponsors): ?>
				<div class="flex gap-8 items-center flex-wrap justify-center">
					<?php foreach ($sponsors as $sponsor):
						$logo = $sponsor['logo'];
						$name = esc_html($sponsor['label']);
						$link = esc_url($sponsor['url']);
                    ?>
						<div class="relative">
							<?php if($link): ?>
								<a href="<?= $link ?>" class="absolute z-10 inset-0" target="_blank"></a>
							<?php endif; ?>

							<?php if($logo): ?>
								<img
                                    src="<?= esc_url($logo['sizes']['medium']) ?>"
                                    alt="<?= $name ?>"
                                    class="max-h-24"
                                >
							<?php else: ?>
								<p><?= $name ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else: ?>

				<p>Zatím žádní sponzoři. Staňte se prvním!</p>

			<?php endif; ?>

		</div>
	</div>
</section>



