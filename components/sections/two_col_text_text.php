<?php
/**
 * Two column text side by side section
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_allow_safe(get_sub_field('heading'));
$text_left = esc_allow_safe(get_sub_field('text_col_1'));
$text_right = esc_allow_safe(get_sub_field('text_col_2'));

?>

<section class="section">
    <div class="max-content-width">
        <h2 class="mb-6"><?= $heading ?></h2>
        <div class="grid md:grid-cols-2 grid-cols-1 gap-x-16 gap-y-4">
            <div class="section__format-text">
                <?= $text_left ?>
            </div>
            <div class="section__format-text">
		        <?= $text_right ?>
            </div>
        </div>
    </div>
</section>


