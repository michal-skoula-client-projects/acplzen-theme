<article class="rounded-md border border-light-outline overflow-clip">
	<div>
		<img src="<?= get_the_post_thumbnail_url(size: 'medium') ?>" alt="" class="w-full aspect-16/10">
	</div>
	<div class="p-6">
		<!-- Article info -->
		<div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-darker mb-0.5">
			<?php if(get_the_author()): ?>
				<div class="flex gap-1 items-center text-sm">
					<i class="fa-regular fa-user"></i>
					<span><?= get_the_author() ?></span>
				</div>
			<?php endif; ?>

			<?php if(the_date(display: false)): ?>
				<div class="flex gap-2 items-center text-sm">
					<i class="fa-regular fa-calendar"></i>
					<span>
						<time class="text-sm inline" datetime="<?= get_the_date('Y-m-d h:m') ?>">
						<?= get_the_date('d. m. Y') ?>
						</time>
					</span>
				</div>
			<?php endif; ?>
		</div>
		<!-- Content -->
		<div class="mb-4">
			<h3 class="h4 text-darkest mb-1"><?php the_title() ?></h3>
			<p class="text-dark"><?php the_excerpt(); ?></p>
		</div>
		<div>
			<?= get_template_part('/components/blocks/ac_link', args: [
				'style' => 'underline',
				'color' => 'primary',
				'label' => 'Přečíst článek',
				'href' => get_permalink()
			]) ?>
		</div>
	</div>
</article>