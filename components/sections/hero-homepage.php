<?php

/**
 * Component description
 *
 * @author Michal Škoula
 * @since 1.0.0
 **/

$heading     = get_field('hero_heading') ?? 'American center Plzeň';
$description = get_field('hero_description') ?? '';

$posts = get_posts([
	'post_type'     => 'aktualita',
	'orderby'       => 'date',
	'order'         => 'DESC',
	'numberposts'   => 3,
]);

?>

<section class="section">
	<div class="max-content-width">
        <div class="text-center mb-16">
            <h1 class="text-balance text-darkest mb-6 text-8xl font-bold tracking-tight leading-[108%]"><?= esc_allow_safe($heading) ?></h1>
            <p class="text-balance text-dark text-lg"><?= esc_html($description) ?></p>
        </div>


        <div class="bg-light-elevated p-8 rounded-md border-1 border-light-outline">
            <h2 class="text-darker text-center">Aktuality</h2>





			<?php if($posts): global $post; ?>
                <div class="mt-8 grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8 mx-auto">
					<?php foreach($posts as $post): setup_postdata($post); ?>

                        <?= get_template_part('components/blocks/ac_post_card') ?>

					<?php endforeach; wp_reset_postdata(); ?>

					<?php  ?>
                </div>
			<?php else: ?>
                <p>Žádné články nenalezeny.</p>
			<?php endif; ?>
            <div class="flex justify-center">
	            <?php get_template_part('/components/blocks/ac_button', 'ac_button', args: [
		            'style' => 'primary',
		            'label' => 'Všechny aktuality',
		            'href'   => get_post_type_archive_link('aktualita'),
		            'target' => '_self',
		            'class' => 'mx-auto mt-8'
	            ]) ?>
            </div>

        </div>
	</div>
</section>




