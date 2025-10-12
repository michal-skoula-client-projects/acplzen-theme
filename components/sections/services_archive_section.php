<?php
/**
 * Sekce pro archiv Poradíme - two col s službami
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$services = get_posts([
	'post_type'     => 'sluzba',
	'orderby'       => 'date',
	'order'         => 'ASC',
	'numberposts'   => 99,
]);

?>

<section class="section">
	<div class="max-content-width">
		<?php if($services): global $post; ?>
			<div class="mt-8 grid md:grid-cols-2 grid-cols-1 gap-8 mx-auto">
				<?php foreach($services as $post): setup_postdata($post); ?>
					<div class="rounded-md border border-light-outline bg-light-elevated p-8">
						<h2 class="h3 text-darkest mb-3"><?= esc_allow_safe(get_the_title() ?? 'Neznámá služba') ?></h2>
						<p class="text-dark mb-5"><?= esc_allow_safe(get_field('about')) ?></p>
						<?php get_template_part('/components/blocks/ac_link', args: [
							'color' => 'primary',
							'style' => 'underline',
							'href' => get_post_permalink(),
							'label' => 'Zjistit více',
						]) ?>
					</div>
				<?php endforeach; wp_reset_postdata(); ?>
			</div>
		<?php else: ?>
			<p class="text-dark">Aktuálně nenabízíme žádné konzultace.</p>
		<?php endif; ?>
	</div>
</section>





