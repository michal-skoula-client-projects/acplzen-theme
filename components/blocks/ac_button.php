<?php
assert(isset($args));

$style = in_array($args['style'] ?? '', ['primary', 'secondary'])
    ? $args['style']
    : 'primary';
$label = esc_html($args['label'] ?? 'Klikni mě');
$href = esc_url($args['href'] ?? '#');
$target = esc_html($args['target'] ?? '');
$class = esc_html($args['class'] ?? '');

$colors = match($style) {
    default => 'bg-brand-primary group-hover:bg-brand-primary-focus group-focus:bg-brand-primary-focus group-active:bg-brand-primary-highlight',
    'secondary' => 'bg-brand-secondary group-hover:bg-brand-secondary-focus group-focus:bg-brand-secondary-focus group-active:bg-brand-secondary-highlight'
}
?>

<a href="<?= $href ?>" target="<?= $target ?>" class="inline-flex text-light-elevated group <?= $class ?>">
    <div class="<?= $colors ?> duration-200 transition-colors rounded-full p-3 pl-5 py-2.5 pr-4">
        <span class="align-middle font-medium"><?=$label?></span>
    </div>
    <div class="<?= $colors ?> duration-200 h-full inline-block aspect-1/1 rounded-full py-2.5 px-4 text-light-elevated -translate-x-2 group-hover:-translate-x-3.5 group-focus:-translate-x-3.5 group-active:-translate-x-3.5 transition-transform">
        <i class="fa fa-arrow-right align-middle group-hover:rotate-[-45deg] group-focus:rotate-[-45deg] group-active:rotate-[-45deg] duration-200 transition-transform"></i>
    </div>
</a>