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

<section class="section">
	<div class="max-content-width bg-brand-secondary text-light-highlight p-12 rounded-md grid grid-cols-1 md:grid-cols-[1fr_2fr]">
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
        <div>
			<?php if($faq_blocks): $id = 0; ?>
				<?php foreach ($faq_blocks as $faq_block): $id++ ?>

                    <div class="mb-4 last-of-type:mb-0 rounded-lg shadow-xs">
                        <input type="checkbox" id="faq<?= $id ?>" class="peer hidden"/>
                        <h3 class="h4 text-light-elevated">
                            <!-- TODO: fix keyboard navigation for these + finish -->
                            <label
                                tabindex="0"
                                for="faq<?= $id ?>"
                                class="icon-faq block relative cursor-pointer font-medium bg-opacity-45 py-4 pl-4 pr-8 peer-checked:rounded-t-lg peer-checked:rounded-b-none rounded-lg mb-0"
                            >
                                <span class="font-medium mb-0"><?= $faq_block['question'] ?></span>
                            </label>
                        </h3>

                        <div class="p-4 hidden peer-checked:block bg-opacity-30 rounded-b-lg">
                            <div class="">
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




