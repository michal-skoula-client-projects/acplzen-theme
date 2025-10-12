<?php
assert(isset($args));

$list_elems = $args['list_elems'] ?? ac_breadcrumbs();
$styles = 'text-brand-primary group-hover:text-brand-primary-focus group-focus:text-brand-primary-focus group-active:text-brand-primary-highlight underline!';
?>

<nav>
	<?php if($list_elems): ?>
		<ol id="breadcrumbs" class="flex flex-wrap gap-2 items-center">
			<?php foreach ($list_elems as $index => $list_elem): ?>
				<li class="flex items-center gap-2">
					<?php if ($index + 1 < count($list_elems)): ?>
						<a href="<?= esc_url($list_elem['url'] ?? '#') ?>" class="<?= $styles ?>"><?= esc_html($list_elem['label'] ?? 'Klikni mě') ?></a>
						<i class="fa-solid fa-arrow-right fa-2xs text-dark"></i>
					<?php else: ?>
                        <span class="text-darker"><?= esc_html($list_elem['label'] ?? 'Konec') ?></span>

					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php endif; ?>
</nav>