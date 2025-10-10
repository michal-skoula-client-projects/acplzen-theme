<?php
/**
 * Section showcasing sponsors and their logos
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = get_sub_field('heading');
$description = get_sub_field('description');
$sponsors = get_sub_field('sponsors');

?>

<section class="section">
	<div class="max-content-width">
		<div>
			<h2 class="text-center mb-2"><?= $heading ?></h2>
            <p class="text-center mb-8"><?= $description ?></p>

			<?php if(isset($sponsors)): ?>
				<div class="flex gap-8 items-center flex-wrap justify-center">
					<?php foreach ($sponsors as $sponsor):
						$name = esc_html($sponsor['name']);
						$link = esc_url($sponsor['url']);
						$logo = $sponsor['logo'];
                    ?>
						<div class="relative">
							<?php if($link): ?>
								<a href="<?= $link ?>" class="absolute z-10 inset-0 bg-light-base/15" target="_blank"></a>
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



