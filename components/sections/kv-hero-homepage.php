<?php
/**
 * Hero homepage component
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$title = esc_allow_safe(get_field('title'));
$description = esc_allow_safe(get_field('description'));
$button = get_field('button');
$image = get_field('image');

?>

<section class="w-full h-[100lvh] min-h-[30rem] max-h-[50rem] relative bg-primary-base" id="hero">
    <!-- Background image -->
    <div class="section__background w-full h-full">
        <?php if(isset($image)): ?>
            <img src="<?= esc_html($image['url']) ?>" alt="" class="w-full h-full object-cover">
        <?php endif; ?>
    </div>

    <!-- Overlay -->
    <div class="text-brand-main section__overlay top-0 left-0 absolute z-10 w-full h-full bg-linear-to-t from-[#000]/70 to-[#000]/25">

    </div>

    <!-- Content -->
    <div class="section__content absolute inset-0 z-20 w-full h-full section max-content-width  flex items-center justify-center">
        <div class="relative text-center">
            <h1 class="text-light-base font-bold mb-4 leading-[110%]"><?= $title ?></h1>
            <p class="text-light-soft text-balance mb-6 mx-auto max-w-2xl"><?= $description ?></p>
            <?php if($button): ?>
                <a href="<?= esc_url($button['url']) ?>" class="button__primary py-4">
                    <?= esc_html($button['title']) ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</section>




