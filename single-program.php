<?php
get_header();

$featured_image = get_field('featured_img');
$perex = get_field('description') ?: '';
$date = get_field('date') ?: null;

$location = get_field('location') ?: null;
$datetime = get_field('datetime') ?: null;
$audience = get_field('audience') ?: null;
$information = get_field('information') ?: null;
$calendar_btn_label = get_field('calendar_btn_label') ?: 'Přidat do kalendáře';
?>

<section class="section">
	<div class="max-content-width lg:pt-16 md:pt-14 pt-10">
        <div class="flex justify-center mb-8">
			<?php get_template_part('components/blocks/ac_breadcrumbs'); ?>
        </div>
        <div class="rounded-md border border-light-outline bg-light-elevated overflow-clip mb-16">
            <img
                class="w-full aspect-16/9 object-cover"
                src="<?= $featured_image['sizes']['large'] ?? '' ?>"
                alt="<?= $featured_image['caption'] ?? '' ?>"
            >
            <div class="pb-[calc(var(--section-padding)*2)] p-[var(--section-padding)]">
                <div class="flex flex-wrap justify-center items-center gap-x-4 gap-y-2 text-darker mb-1">
                    <?php if($date): ?>
                    <div class="flex gap-2 items-center text-sm">
                        <i class="fa-regular fa-calendar"></i>
                        <span>
                            <time class="text-sm inline" datetime="<?= DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d') ?>">
                            <?= DateTime::createFromFormat('d/m/Y', $date)->format('d.m.Y') ?>
                            </time>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <h1 class="text-darkest text-center mb-3"><?= get_the_title() ?: 'Chybí titulek' ?></h1>
                <p class="section__format-text mx-auto max-w-screen-md text-center text-lg text-dark">
		            <?= get_the_excerpt() ?>
                </p>
            </div>
        </div>
        <!-- Content and info -->
        <div class="grid lg:grid-cols-[20rem_1fr] grid-cols-1 gap-8">
            <div class="">
                <p class="h3 mb-3 text-darkest">Podrobnosti</p>
                <div class="section__format-text">
                    <hr class="max-lg:hidden mb-8!">
                </div>
                <div class="flex lg:flex-col gap-x-20 gap-y-2 flex-wrap ">
	                <?php if($datetime): ?>
                        <dl class="mb-4">
                            <dt class="flex gap-2 items-center font-medium mb-1">
                                <i class="fa-solid fa-calendar text-brand-secondary"></i>
                                <span class="text-darkest text-lg">Datum akce</span>
                            </dt>
                            <dd class="text-dark mb-4"><?= DateTime::createFromFormat('Y-m-d H:i:s', $datetime)->format('d.m.Y \o\d H:i') ?></dd>
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
	                <?php if($audience): ?>
                        <dl class="mb-4">
                            <dt class="flex gap-2 items-center font-medium mb-1">
                                <i class="fa-solid fa-user-group text-brand-secondary"></i>
                                <span class="text-darkest text-lg">Cílová skupina</span>
                            </dt>
                            <dd class="text-dark"><?= esc_html($audience) ?></dd>
                        </dl>
	                <?php endif; ?>
	                <?php if($information): ?>
                        <dl class="mb-4">
                            <dt class="flex gap-2 items-center font-medium mb-1">
                                <i class="fa-solid fa-circle-exclamation text-brand-secondary"></i>
                                <span class="text-darkest text-lg">Důležité informace</span>
                            </dt>
                            <dd class="text-dark"><?= esc_allow_safe($information) ?></dd>
                        </dl>
	                <?php endif; ?>
                </div>
                <div class="section__format-text">
                    <hr class="lg:hidden">
                </div>
            </div>
            <div class="text-dark max-w-screen-md w-full lg:mx-auto section__format-text mb-12">
                <p class="h3 text-darkest pb-0 mb-0">O akci</p>
                <hr class="max-lg:hidden">
                <?php the_content() ?>
            </div>
        </div>
        <div class="flex justify-center">
			<?php get_template_part('components/blocks/ac_button', args: [
				'label' => 'Zpátky na seznam akcí',
				'href' => get_post_type_archive_link('program'),
			]) ?>
        </div>
	</div>
</section>

<?php get_footer() ?>
