<?php get_header(); ?>

<section class="section lg:pt-32 md:pt-28 pt-20">
	<div class="max-content-width text-dark grid lg:grid-cols-2 grid-cols-1 gap-12">
		<div>
			<?= get_template_part('/components/blocks/ac_breadcrumbs', args: ['list_elems' => ac_breadcrumbs()]) ?>
			<h1 class="text-darkest mb-6 mt-2 font-bold tracking-tight leading-[120%]">
				<?= esc_allow_safe(get_the_title()) ?>
			</h1>
			<p class="max-w-screen-md section__format-text">
				<?= esc_allow_safe(get_field('about' ?? '')) ?>
			</p>
		</div>
		<div>
			<img
				src="<?= get_the_post_thumbnail_url(size: 'large') ?>"
				alt="<?= get_the_post_thumbnail_caption() ?>"
				class="w-full h-full aspect-16/10 rounded-sm object-cover"
			>
		</div>
	</div>
</section>

<section class="section">
	<div class="max-content-width">
		<div class="text-dark max-w-screen-md section__format-text mb-12">
			<?php the_content() ?>
		</div>
		<div class="flex justify-center">
			<?php get_template_part('components/blocks/ac_button', args: [
				'label' => 'Zpátky do nabídky',
				'href' => get_post_type_archive_link('sluzba'),
			]) ?>
		</div>
	</div>
</section>


<?php get_footer() ?>