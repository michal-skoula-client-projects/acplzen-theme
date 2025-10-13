<?php
/**
 * Sekce se dvěma sloupci textu, kde pravý lze vypnout.
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_html(get_sub_field('heading') ?: 'Chybí nadpis');
$blocks = get_sub_field('blocks') ?: null;

?>

<section class="section">
	<div class="mx-auto max-w-screen-lg">
        <h2 class="text-darkest text-center mb-8"><?= $heading ?></h2>
            <?php if($blocks): ?>
                <div class="grid md:grid-cols-2 grid-cols-1 gap-6">
                    <?php foreach($blocks as $block): ?>
                        <div class="text-center text-dark p-[var(--section-padding)] rounded-md bg-light-elevated border border-light-outline">
                            <?= esc_allow_safe($block['text'] ?? '') ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-dark">Zatím žádné bloky.</p>
            <?php endif; ?>
	</div>
</section>




