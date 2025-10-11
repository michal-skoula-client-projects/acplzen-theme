<?php
/**
 * Two column section with an image and text. Layout can be flipped.
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_allow_safe(get_sub_field('heading'));
$text = esc_allow_safe(get_sub_field('text'));
$flipped = (bool)get_sub_field('flipped');
$img = get_sub_field('obrazek')

?>

<section class="section">
    <div class="max-content-width">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-x-16 gap-y-4">
            <div class="<?= $flipped ? 'md:order-2' : 'md:order-1' ?> order-2">
                <h2 class="mb-6"><?= $heading ?></h2>
                <div class="section__format-text">
                    <?= $text ?>
                </div>
            </div>
            <div class="<?= $flipped ? 'md:order-1' : 'md:order-2' ?> order-1">
                <?php if(isset($img)): ?>
                    <img
                        src="<?= esc_url($img['sizes']['large']) ?>"
                        alt="<?= esc_html($img['alt']) ?>"
                        class="rounded-2xl">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

