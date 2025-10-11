<?php
assert(isset($args));

$style = in_array($args['style'] ?? '', ['underline', 'dotted'])
	? $args['style']
	: 'underline';
$color = in_array($args['color'] ?? '', ['primary', 'secondary'])
	? $args['color']
	: 'primary';
$label = esc_html($args['label'] ?? 'Klikni mě');
$href = esc_url($args['href'] ?? '#');
$target = esc_html($args['target'] ?? '_self');
$class = esc_html($args['class'] ?? '');

$colors = match($color) {
	default => 'text-brand-primary group-hover:text-brand-primary-focus group-focus:text-brand-primary-focus group-active:text-brand-primary-highlight',
	'secondary' => 'text-brand-secondary group-hover:text-brand-secondary-focus group-focus:text-brand-secondary-focus group-active:text-brand-secondary-highlight'
};

$underline = match($style) {
	default => 'underline',
	'dotted' => 'dotted'
};
?>
<a href="<?= $href ?>" target="<?= $target ?>" class="group">
	<span class="<?= "$underline $colors" ?>"><?=$label?></span>
	<?php if($style !== 'dotted'): ?>
		<i class="<?= $colors ?> fa-solid fa-arrow-right fa-2xs group-hover:pl-1 group-focus:pl-1 group-active:pl-1 duration-150 ease-in-out transition-all"></i>
	<?php endif; ?>
</a>