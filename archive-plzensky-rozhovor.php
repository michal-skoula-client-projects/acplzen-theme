<?php

$ids = null;
$paginated_ids            = null;
$pagination_interval      = PAGINATION_INTERVAL;
$is_paginated             = false;
$current_page = 1;

if(! isset($_GET['page'])) {
    set_pagination_page(1);
}
else {
    $current_page = (int) $_GET['page'];

    if($current_page < 1) {
	    set_pagination_page(1);
    }

	$talks = get_posts([
		'post_type'     => 'plzensky-rozhovor',
		'orderby'       => 'date',
		'order'         => 'DESC',
		'numberposts'   => -1,
	]);

    if($talks)
    {
        $ids = get_talks_ids($talks);
        $min = $pagination_interval * ($current_page - 1);
        $paginated_ids = array_slice($ids, $min, $pagination_interval);

        if(count($ids) > $pagination_interval) {
            $is_paginated = true;
        }

	    // Out of bounds
	    if(! $paginated_ids && $is_paginated) {
            ac_404();
        }
    }
}
/**
 * @param array<WP_Post> $talks
 *
 * @return array<int>
 */
function get_talks_ids(array $talks): array
{
	update_postmeta_cache(wp_list_pluck($talks, 'ID'));

	$timestamps = [];

	foreach ($talks as $talk) {
		$timestamp = get_the_date('U', $talk);

		// Skip posts whose publication date cannot be read
		if (! is_numeric($timestamp)) {
			continue;
		}

		$timestamps[(int) $talk->ID] = (int) $timestamp;
	}

	// Newest first
	arsort($timestamps);

    // Array of IDs
	return array_keys($timestamps);
}

function set_pagination_page(int $page_number): void
{
	header("Location: ?page=$page_number");
	exit();
}


function get_pages_count(int $total_talks_count, int $pagination_interval): int
{
    return ceil( $total_talks_count / $pagination_interval);
}

/**
 * @param  callable():bool  $disabled
 * @param  int  $to_page  Which page the pagination link should link to
 * @param  string  $label
 * @param  string  $sr_only_label Label for screen readers
 *
 * @return void
 */
function create_pagination_button(callable $disabled, int $to_page, string $label, string $sr_only_label = ''): void
{
    $is_disabled = (bool)$disabled();
    $is_disabled_js = !$is_disabled ?: 'pagination-button-disabled';
    $tab_navigatable = !$is_disabled ?: 'tabindex="-1"';
    $inline_styles = !$is_disabled ?: 'style="cursor: not-allowed;"';

    $styles = $is_disabled
        ? 'pointer-not-allowed! bg-light-highlight/50 text-darkest/30'
        : 'pointer-click! bg-light-base text-darkest';


    echo <<<HTML
        <a 
            $is_disabled_js
            $inline_styles
            $tab_navigatable
            href="?page=$to_page" 
            class="$styles py-2 px-4 rounded-sm no-underline! border border-light-outline"
        >
            $label
            <p class="sr-only">$sr_only_label</p>
        </a>
    HTML;
}
?>

<?php get_header(); ?>


<?php
get_template_part('components/sections/hero', args: [
    'heading' => 'Plzeňské rozhovory',
    'style' => 'single',
    'description_left' => 'Plzeňské rozhovory (Pilsen Talks) je každoroční pravidelná akce, která umožňuje setkání s odborníky z různých oblastí vzdělávání, vědy, výzkumu, kultury a politiky. Při výběru témat a odborníků se zaměřujeme na aktuální fenomény, které hýbou současnou společností.',
    'bottom_gap' => 'large',
])
?>

<section class="section" id="#obsah">
    <div class="max-content-width">
        <div class="flex flex-col gap-12 mb-12">
	        <?php if($paginated_ids): ?>
		        <?php foreach($paginated_ids as $post_id):
                    $date = get_field('date', $post_id) ?: null;
                    $location = esc_html(get_field('location', $post_id) ?: '');
                ?>
		            <article class="bg-light-elevated border border-light-outline rounded-md grid lg:grid-cols-2 grid-cols-1 gap-x-12 overflow-clip">
                        <div class="lg:order-1 order-2 p-card-xl">
                            <p class="text-lg text-brand-primary mb-1">Ročník <?= $date ? DateTime::createFromFormat('Y-m-d', $date)?->format('Y') : '?' ?></p>
                            <h2 class="h3 text-darkest mb-2.5"><?= esc_allow_safe(get_the_title($post_id)) ?></h2>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-6 text-darker">
                                <?php if($location): ?>
                                <div class="flex gap-2 items-center">
                                    <i class="text-brand-secondary fa-solid fa-location-dot fa-md"></i>
                                    <span><?= $location ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($date): ?>
                                <div class="flex gap-2 items-center">
                                    <i class="text-brand-secondary fa-solid fa-calendar fa-md"></i>
                                    <span><?= DateTime::createFromFormat('Y-m-d', $date)?->format('d.m.Y') ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-dark mb-8"><?= esc_allow_safe(get_the_excerpt($post_id)) ?></p>
                            <?php get_template_part('/components/blocks/ac_button', args: [
                                'label' => 'Zjistit více',
                                'href' => get_permalink($post_id),
                            ]) ?>
                        </div>
                        <div class="lg:order-2 order-1">
                            <img src="<?= get_the_post_thumbnail_url($post_id) ?? '' ?>" alt="<?= esc_html(get_the_post_thumbnail_caption()) ?>" class="aspect-16/10 w-full h-full object-cover">
                        </div>

                    </article>
		        <?php endforeach; ?>
	        <?php else: ?>
                <p class="text-dark p-8">Zatím nebyly vydány žádné Plzeňské rozhovory.</p>
	        <?php endif; ?>
        </div>
	    <?php if($is_paginated): $total_pages = get_pages_count(count($ids), $pagination_interval); ?>
            <div class="w-full grid grid-flow-col gap-4 grid-cols[1fr_10ch_1fr] items-center justify-center">
                <div class="flex gap-1">
				    <?php
				    create_pagination_button(
					    disabled: fn() => $current_page === 1,
					    to_page: 1,
					    label: '<i class="fa-solid fa-angles-left"></i>',
					    sr_only_label: 'První stránka'
				    );
				    create_pagination_button(
					    disabled: fn() => $current_page === 1,
					    to_page: $current_page - 1,
					    label: '<i class="fa-solid fa-chevron-left"></i>',
					    sr_only_label: 'Předchozí stránka'
				    );
				    ?>
                </div>

                <p class="text-dark"><?= 'Strana ' . $current_page . ' / ' . $total_pages ; ?></p>

                <div class="flex gap-1">
				    <?php
				    create_pagination_button(
					    disabled: fn() => $current_page === $total_pages,
					    to_page: $current_page + 1,
					    label: '<i class="fa-solid fa-chevron-right"></i>',
					    sr_only_label: 'Další stránka'
				    );
				    create_pagination_button(
					    disabled: fn() => $current_page === $total_pages,
					    to_page: $total_pages,
					    label: '<i class="fa-solid fa-angles-right"></i>',
					    sr_only_label: 'Poslední stránka'
				    );
				    ?>
                </div>
            </div>


	    <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
