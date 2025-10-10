<?php
/**
 * Camps list section
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_allow_safe(get_sub_field('heading'));

$limit = (int)get_sub_field('limit');
$is_limited = (bool)get_sub_field('is_limited');
$is_rounded = (bool)get_sub_field('is_rounded');

// global camp settings
$camp_capacity_status = (array)get_field('camp_availability_status', 'options');
$registration_allowed = (bool)get_field('signup_allowed', 'options');

$availability_label_styling = match($camp_capacity_status['value']) {
	'info' => 'bg-state-info/20 text-state-info',
	'success' => 'bg-state-success/20 text-success-info',
	'alert' => 'bg-state-warning/15 text-state-warning',
	'danger' => 'bg-state-danger/20 text-state-danger',

	default => function () {
		if ( ! ac_in_production() ) {
			throw new InvalidSectionException( 'Availability label has an unknown style' );
		} else {
			return 'bg-dark-faint/20 text-dark-soft';
		}
	}
};

/**
 * @var $camps_posts WP_Post[]
 */
$camps_posts = get_posts([
	'post_type'      => 'tabor',
	'posts_per_page' => $is_limited ? $limit : -1,
]);

if ($camps_posts) {
	update_postmeta_cache(wp_list_pluck($camps_posts, 'ID'));

	$camps = array_map(fn(WP_Post $p) => (array) $p, $camps_posts);

	$camps = array_filter($camps, function ($p) {
		return get_field('availability', $p['ID']) === 'current';
	});

	usort($camps, fn($a, $b) => strtotime($a['post_date']) <=> strtotime($b['post_date']));
}

?>

<section class="section">
    <div class="max-content-width">
        <p class="badge text-sm py-1 px-3 inline-block rounded-full mb-2 <?= $availability_label_styling ?>"><?= $camp_capacity_status['label'] ?></p>
        <h2 class="mb-8"><?= $heading ?></h2>

        <div class="grid gap-12">
            <?php if(! empty($camps)): ?>

                <?php foreach($camps as $camp): $id = $camp['ID'] ?>

                    <div id="<?= "tabor-$id" ?>" class="grid lg:grid-cols-[1fr_2fr] grid-cols-1 gap-x-12 gap-y-4">
                        <div>
                            <?php
                            $camp_image_url = esc_url(get_the_post_thumbnail_url($id, 'large'));
                            $camp_image_alt = esc_html("Plakát {$camp['post_title']}");
                            ?>

                            <!-- Featured image -->
                            <img src="<?= esc_url($camp_image_url) ?>" alt="<?= esc_html($camp_image_alt) ?>" class="w-full h-full object-cover aspect-18/25 rounded-2xl">
                        </div>
                        <div>
                            <!-- Camp name -->
                            <h3 class="mb-3"><?= esc_html($camp['post_title']) ?></h3>

                            <!-- Informational pills -->
                            <?php $pills = get_field('pills', $id); if($pills): ?>
                                <div class="flex gap-2 flex-wrap mb-4">
	                                <?php foreach($pills as $pill): ?>
                                        <div class="text-sm inline-block px-2 py-1 rounded-full text-dark-faint bg-primary-highlight/50">
                                            <p><?= esc_html($pill['label']) ?></p>
                                        </div>
	                                <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Description -->
                            <div class="section__format-text mb-8">
                                <?= get_the_content(post: $id) ?>
                            </div>

                            <!-- Signup Button -->
                            <?php if($registration_allowed): $signup_url = get_field('button_primary', $id) ?>

                                <?php if($signup_url): ?>
                                    <div>
                                        <a href="<?= esc_url( $signup_url); ?>" class="button__primary" target="_blank">
                                            Podat přihlášku
                                        </a>
                                    </div>
                                <?php endif; ?>

                            <?php elseif(current_user_can('editor') || current_user_can('administrator')): ?>
                                <p class="text-dark-subtle text-sm">
                                    Přihlašování je vypnuto. Zapnout jej můžete
                                    <a  style="text-decoration:
                                        underline !important;"
                                        target="_blank"
                                        href="/wp-admin/edit.php?post_type=tabor&page=camp_availability"
                                    >zde</a>. Tuto zprávu vidí pouze administrátoři.
                                </p>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; wp_reset_postdata(); ?>

            <?php else: ?>
                <p>Brzy zveřejníme letošní tábory!</p>
            <?php endif; ?>
        </div>



    </div>
</section>


