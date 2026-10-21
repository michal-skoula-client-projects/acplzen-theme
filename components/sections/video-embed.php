<?php
/**
 * Sekce videonávod s embedem z youtubu
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = get_sub_field('heading') ?: '';
$yt_embed_url = esc_url(get_sub_field('yt_embed_url')
	?:  'https://www.youtube.com/embed/dQw4w9WgXcQ?si=s2DeZ-z6yQQU7j4P'); // wink wink ;)

?>

<section class="section">
    <div class="mx-auto max-w-screen-lg">
        <h2 class="text-darkest mb-8 text-center"><?= $heading ?></h2>
        <div class="relative w-full max-w-screen-xl mx-auto" style="padding-top:56.25%;">
            <iframe
                class="absolute inset-0 w-full h-full block rounded-sm"
                src="<?= $yt_embed_url ?>"
                title="Přehrávač videa Youtube"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                frameborder="0"
                referrerpolicy="strict-origin-when-cross-origin"
                loading="lazy"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</section>





