<?php
/**
 * Component description
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$images = get_field('photo_gallery', 'options')

?>

<section class="section">
	<div class="max-content-width">
		<?php if($images): ?>
			<div class="grid grid-cols-3 gap-8">
				<?php foreach ($images as $image):

					$img_url = esc_url($image['sizes']['large']);
					$img_desc = esc_html($image['title']);

					$image_card = <<<HTML
					<div class="relative rounded-sm overflow-clip">
						<img src="$img_url" alt="" class="h-full w-full object-cover aspect-4/3" loading="lazy">
						<a href="$img_url" class="absolute z-10 inset-0">
							<p class="sr-only">$img_desc</p>
						</a>
					</div>
					HTML;

					if(function_exists('slb_activate')) {
						// Activates the lightbox if plugin exists
						$image_card = slb_activate( $image_card );
					}
					echo $image_card;

				endforeach; ?>
			</div>
		<?php else: ?>
			<p class="text-dark">Nebyly nalezeny žádné fotky.</p>
		<?php endif; ?>
	</div>
</section>




