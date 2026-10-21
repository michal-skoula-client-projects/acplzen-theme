<?php
get_header();

$id = get_the_ID();
$author = get_the_author() === ''
	? get_the_author_meta('display_name', get_post_field('post_author', $id))
	: get_the_author()
?>

<section class="section">
	<div class="max-content-width lg:pt-16 md:pt-14 pt-10">
        <div class="flex justify-center mb-8">
			<?php get_template_part('components/blocks/ac_breadcrumbs'); ?>
        </div>
        <div class="rounded-md border border-light-outline bg-light-elevated overflow-clip mb-16">
            <img
                class="w-full aspect-16/9 object-cover"
                src="<?= get_the_post_thumbnail_url(size: 'large') ?: '' ?>"
                alt="<?= get_the_post_thumbnail_caption() ?: '' ?>"
            >
            <div class="p-card-xl">
                <div class="flex flex-wrap justify-center items-center gap-x-4 gap-y-2 text-darker mb-1">
                    <div class="flex gap-1 items-center text-sm">
                        <i class="fa-regular fa-user"></i>
                        <span><?= $author ?></span>
                    </div>

                    <div class="flex gap-2 items-center text-sm">
                        <i class="fa-regular fa-calendar"></i>
                        <span>
                    <time class="text-sm inline" datetime="<?= get_the_date('Y-m-d h:m', $id) ?>">
                    <?= get_the_date('d. m. Y', $id) ?>
                    </time>
                </span>
                    </div>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl text-darkest text-center mb-3"><?= get_the_title() ?: 'Chybí titulek' ?></h1>
                <p class="section__format-text mx-auto max-w-screen-md text-center text-base sm:text-lg text-dark">
		            <?= get_the_excerpt() ?>
                </p>
            </div>
        </div>

        <div class="text-dark max-w-screen-md mx-auto section__format-text mb-12">
            <?php the_content() ?>
        </div>
        <div class="flex justify-center">
            <?php get_template_part('components/blocks/ac_button', args: [
                'label' => 'Zpátky na aktuality',
                'href' => get_post_type_archive_link('aktualita'),
            ]) ?>
        </div>
	</div>
</section>

<?php get_footer() ?>
