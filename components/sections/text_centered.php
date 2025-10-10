<?php
/**
 * Single column section with centered text
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_allow_safe(get_sub_field('heading'));
$text = esc_allow_safe(get_sub_field('text'));

?>

<section class="section">
    <div class="max-content-width">
        <h2 class="mb-6 text-center"><?= $heading ?></h2>
        <div class="max-w-screen-md mx-auto section__format-text">
            <?= $text ?>
        </div>
    </div>
</section>


