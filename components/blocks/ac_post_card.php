<?php
assert(isset($args));

$id = $args['id'] ?? 0;
$author = get_the_author() === ''
    ? get_the_author_meta('display_name', get_post_field('post_author', $id))
    : get_the_author()


?>

<article class="sm:rounded-md rounded-sm border border-light-outline overflow-clip h-full">
	<div class="relative">
		<img src="<?= get_the_post_thumbnail_url($id, size: 'large') ?>" alt="" class="w-full aspect-16/10" style="object-fit:cover;">
        <a class="absolute inset-0" href="<?= get_permalink($id) ?>" tabindex="-1"></a>
	</div>
	<div class="sm:p-6 p-5">
		<!-- Article info -->
		<div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-darker mb-0.5">
            <div class="flex gap-1 items-center text-sm">
                <i class="fa-regular fa-user"></i>
                <span><?= $author ?></span>
            </div>

            <div class="flex gap-2 items-center text-sm">
                <i class="fa-regular fa-calendar"></i>
                <span>
                    <time class="text-sm inline" datetime="<?= get_the_date('Y-m-d h:m', $id) ?>">
                    <?= get_the_date('d. m. Y', $id) ?>
                    </time>
                </span>
            </div>
		</div>
		<!-- Content -->
		<div class="mb-4">
			<h3 class="h4 text-darkest mb-1"><?= get_the_title($id) ?></h3>
			<p class="text-dark"><?= get_the_excerpt($id); ?></p>
		</div>
		<div>
			<?= get_template_part('/components/blocks/ac_link', args: [
				'style' => 'underline',
				'color' => 'primary',
				'label' => 'Přečíst článek',
				'href' => get_permalink($id)
			]) ?>
		</div>
	</div>
</article>