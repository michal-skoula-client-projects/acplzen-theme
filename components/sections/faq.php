<?php
/**
 * Sekce FAQ s rozklikávatelnými otázkami
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_html(get_sub_field('heading') ?: 'Chybí nadpis');
$description = esc_html(get_sub_field('description') ?: '');
$faq_blocks = get_sub_field('questions') ?? null;

$tel = get_field('tel', 'options') ?: null;
$email = get_field('email', 'options') ?: null;

?>

<section class="section max-sm:bg-brand-secondary max-sm:section-pt">
	<div class="max-content-width sm:bg-brand-secondary text-light-highlight sm:p-card-xl sm:rounded-md grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-8">
		<!-- Left side -->
		<div>
			<h2 class="text-light-elevated mb-2"><?= $heading ?></h2>
			<div>
				<p class="text-lg mb-4"><?= $description ?></p>
				<?php if($tel): ?>
					<div class="flex gap-2 items-center mb-1">
						<i class="fa-solid fa-envelope text-light-elevated"></i>
						<a
							class="text-lg underline! hover:text-light-elevated focus:text-light-elevated transition-colors duration-150"
							href="<?= $tel ?>"
						>
							<?= $tel ?>
						</a>
					</div>
				<?php endif; ?>
				<?php if($email): ?>
					<div class="flex gap-2 items-center">
						<i class="fa-solid fa-phone text-light-elevated"></i>
						<a
							class="text-lg underline! hover:text-light-elevated focus:text-light-elevated transition-colors duration-150"
							href="<?= $email ?>"
						>
							<?= $email ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>

        <!-- Right side -->
        <div class="">
			<?php if($faq_blocks): $id = 0; ?>
				<?php foreach ($faq_blocks as $faq_block): $id++ ?>

                    <div class="last-of-type:mb-0 first-of-type:pt-0! shadow-xs border-b border-light-elevated [&:has(:checked)_i]:rotate-0">
                        <input type="checkbox" id="faq<?= $id ?>" class="peer hidden"/>
                        <div class="flex items-center justify-between relative">
                            <h3 class="relative z-20 h5 w-full text-light-elevated block">
                                <!-- TODO: fix keyboard navigation for these + finish -->
                                <label
                                    tabindex="0"
                                    for="faq<?= $id ?>"
                                    class="block relative cursor-pointer font-medium bg-opacity-45 pr-8 px-5 py-5"
                                    id="faq-checkbox-<?= $id ?>"
                                    faq-checkbox

                                >
                                    <span class="font-medium mb-0"><?= $faq_block['question'] ?></span>
                                </label>
                            </h3>
                            <i class="absolute right-5 z-10 top-1/2 -translate-y-1/2 fa-solid fa-chevron-down fa-md peer-checked:bg-brand-primary -rotate-90 group-checked:rotate-0 transition-transform duration-300"></i>
                        </div>


                        <div class="hidden peer-checked:block bg-opacity-30" id="faq-answer-<?= $id ?>">
                            <div class="px-5 pb-5 section__format-text darkbg">
								<?= $faq_block['answer'] ?>
                            </div>
                        </div>
                    </div>

				<?php endforeach; ?>
			<?php else: ?>
                <p>Žádné otázky</p>
			<?php endif; ?>
        </div>

	</div>
</section>





