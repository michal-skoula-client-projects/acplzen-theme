<?php
/**
 * Sekce "co nabízíme"
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = get_sub_field('heading') ?: '';
$blocks = get_sub_field('cards') ?? null;


?>

<section class="section">
    <div class="mx-auto max-w-[900px]">
        <h2 class="text-darkest text-center mb-12"><?= $heading ?></h2>

        <?php if($blocks): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            <?php foreach ($blocks as $block):
	            $icon = esc_html($block['icon'] ?? 'fa-solid fa-border-none');
                $title = esc_html($block['title'] ?? 'Chybí název služby');
                $description = esc_html($block['description'] ?? '');
                $link = $block['url'] ?? null
            ?>

            <div class="text-center mx-auto max-w-md">
                <i class="<?= $icon ?> fa-2xl text-brand-secondary"></i>
                <h3 class="h4 text-darkest mt-2.5 mb-2"><?= $title ?></h3>
                <p class="text-dark text-lg mb-4"><?= $description ?></p>
                <?php if($link): ?>

                    <?= get_template_part('/components/blocks/ac_link', args: [
                        'label' => $link['title'] ?? 'Klikni mě',
                        'href' => $link['url'] ?? '#',
                        'class' => 'text-lg'
                    ]) ?>
                <?php endif; ?>
            </div>

            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p>Žádná služba nenalezena.</p>
        <?php endif; ?>
    </div>
</section>




