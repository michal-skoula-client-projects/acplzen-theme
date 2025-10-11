<article class="rounded-md border border-light-outline overflow-clip">
	<div class="relative">
		<img src="<?= get_the_post_thumbnail_url(size: 'medium') ?>" alt="" class="w-full aspect-16/10">
        <a class="absolute inset-0" href="<?= get_permalink() ?>"></a>
	</div>
	<div class="p-6">
		<!-- Article info -->
		<div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-darker mb-0.5">
            <div class="flex gap-1 items-center text-sm">
                <i class="fa-regular fa-user"></i>
                <span><?= get_the_author() ?></span>
            </div>

            <div class="flex gap-2 items-center text-sm">
                <i class="fa-regular fa-calendar"></i>
                <span>
                    <time class="text-sm inline" datetime="<?= get_the_date('Y-m-d h:m') ?>">
                    <?= get_the_date('d. m. Y') ?>
                    </time>
                </span>
            </div>
		</div>
		<!-- Content -->
		<div class="mb-4">
			<h3 class="h4 text-darkest mb-1"><?= get_the_title() ?></h3>
			<p class="text-dark"><?= get_the_excerpt(); ?></p>
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