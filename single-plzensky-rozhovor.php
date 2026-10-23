<?php get_header();

$date = get_field('date') ?: null;
$location = get_field('location') ?: null
?>

<section class="section-pt section-px sm:section-pb section-pb-sm">
	<div class="max-content-width text-dark grid lg:grid-cols-2 grid-cols-1 gap-12">
        <div class="">
	        <?= get_template_part('/components/blocks/ac_breadcrumbs', args: ['list_elems' => ac_breadcrumbs()])?>
            <h1 class="text-darkest mb-6 mt-2 font-bold tracking-tight leading-[120%]"><?= esc_allow_safe(get_the_title()) ?></h1>
            <p class="max-w-screen-md section__format-text">
		        <?= esc_allow_safe(get_the_excerpt()) ?>
            </p>
        </div>
        <div class="">
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
        <!-- Content and info -->
        <div class="grid lg:grid-cols-[20rem_1fr] grid-cols-1 gap-8">
            <div class="">
                <p class="h3 mb-3 text-darkest">Podrobnosti</p>
                <div class="section__format-text">
                    <hr class="max-lg:hidden mb-8!">
                </div>
                <div class="flex lg:flex-col gap-x-20 gap-y-2 flex-wrap ">
				    <?php if($date): ?>
                        <dl class="mb-4">
                            <dt class="flex gap-2 items-center font-medium mb-1">
                                <i class="fa-solid fa-calendar text-brand-secondary"></i>
                                <span class="text-darkest text-lg">Datum akce</span>
                            </dt>
                            <dd class="text-dark mb-4"><?= DateTime::createFromFormat('Y-m-d', $date)->format('d.m.Y') ?></dd>
                        </dl>

				    <?php endif; ?>
				    <?php if($location): ?>
                        <dl class="mb-4">
                            <dt class="flex gap-2 items-center font-medium mb-1">
                                <i class="fa-solid fa-location-dot text-brand-secondary"></i>
                                <span class="text-darkest text-lg">Místo konání</span>
                            </dt>
                            <dd class="text-dark"><?= esc_html($location) ?></dd>
                        </dl>
				    <?php endif; ?>
                </div>
                <div class="section__format-text">
                    <hr class="lg:hidden">
                </div>
            </div>
            <div class="text-dark max-w-screen-md w-full lg:mx-auto section__format-text mb-12">
                <p class="h3 text-darkest pb-0 mb-0">O tomto ročníku</p>
                <hr class="max-lg:hidden">
			    <?php the_content() ?>
            </div>
        </div>
        <div class="flex justify-center">
	        <?php get_template_part('components/blocks/ac_button', args: [
		        'label' => 'Zpátky na seznam rozhovorů',
		        'href' => get_post_type_archive_link('plzensky-rozhovor'),
	        ]) ?>
        </div>
    </div>
</section>

<?php get_footer() ?>