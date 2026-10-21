<?php
/**
 * Component description
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_html(get_sub_field('heading') ?: 'Chybí nadpis');
$description = esc_allow_safe(get_sub_field('description') ?: '');
$col_1 = get_sub_field('col_1') ?: null;
$col_2 = get_sub_field('col_2') ?: null;
$col_3 = get_sub_field('col_3') ?: null;

?>
<style>
	.review-col strong {
        text-decoration: underline;
        text-decoration-skip-ink: none;
		text-decoration-thickness: 10px;
		text-underline-offset: -4px;
		text-decoration-color: color-mix(in oklch, var(--color-brand-primary), transparent 50%);
		color: var(--color-darkest);


    }
</style>
<section class="section-dark bg-brand-secondary">
	<div class="max-content-width">
		<h2 class="text-light-base mb-2.5"><?= $heading ?></h2>
		<p class="text-light-highlight mb-8 max-w-screen-md"><?= $description ?></p>
		<div class="grid lg:grid-cols-3 grid-cols-1 gap-6">
			<div class="review-col flex flex-col gap-y-6">
				<?php if($col_1): ?>
					<?php foreach($col_1 as $entry): ?>
						<div class="bg-light-elevated sm:rounded-md rounded-sm p-card p-5 pt-4 last:h-full text-darker">
                            <i class="fa-solid fa-quote-left fa-xl text-brand-primary w-full mb-5"></i>
							<?= esc_allow_safe($entry['recenze']) ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
            <div class="review-col flex flex-col gap-y-6">
				<?php if($col_2): ?>
					<?php foreach($col_2 as $entry): ?>
                        <div class="bg-light-elevated sm:rounded-md rounded-sm p-card p-5 pt-4 last:h-full text-darker">
                            <i class="fa-solid fa-quote-left fa-xl text-brand-primary w-full mb-5"></i>
							<?= esc_allow_safe($entry['recenze']) ?>
                        </div>
					<?php endforeach; ?>
				<?php endif; ?>
            </div>
            <div class="review-col flex flex-col gap-y-6">
				<?php if($col_3): ?>
					<?php foreach($col_3 as $entry): ?>
                        <div class="bg-light-elevated sm:rounded-md rounded-sm p-card p-5 pt-4 last:h-full text-darker">
                            <i class="fa-solid fa-quote-left fa-xl text-brand-primary w-full mb-5"></i>
							<?= esc_allow_safe($entry['recenze']) ?>
                        </div>
					<?php endforeach; ?>
				<?php endif; ?>
            </div>
		</div>
	</div>
</section>



