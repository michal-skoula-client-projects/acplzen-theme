<?php
/**
 * Component description
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$images = get_field('photo_gallery', 'options') ?? null

?>

<section class="section">
	<div class="max-content-width">
		<?php if($images): ?>
			<div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
				<?php foreach ($images as $image):
                    $img_url = esc_url($image['sizes']['large']);
                    $img_desc = esc_html($image['title']);
				?>
					<div class="relative rounded-sm overflow-clip @container">
						<img src="<?= $img_url ?>" alt="<?= $img_desc ?>" class="h-full w-full object-cover aspect-4/3" loading="lazy">
                        <p class="absolute bottom-0 lg:p-3 p-2 @max-[22rem]:text-sm left-0 w-full bg-[#000]/40 backdrop-blur-sm text-light-elevated"><?= $img_desc ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<p class="text-dark">Nebyly nalezeny žádné fotky.</p>
		<?php endif; ?>
	</div>
</section>




