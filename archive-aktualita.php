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

	$posts = get_posts([
		'post_type'     => 'aktualita',
		'orderby'       => 'date',
		'order'         => 'DESC',
		'numberposts'   => -1,
	]);

    if($posts)
    {
        $ids = get_posts_ids($posts);
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
 * @param array<WP_Post> $posts
 *
 * @return array<int>
 */
function get_posts_ids(array $posts): array
{
	update_postmeta_cache(wp_list_pluck($posts, 'ID'));

	$albums = array_map(fn(WP_Post $p) => (array) $p, $posts);

	// Remove unknown dates from showing
	$albums = array_filter($albums, fn ($a) => get_the_date(post:  $a));

	// Newest first
	usort($albums, function($a, $b) {
		$date_a = get_the_date('Y-m-d H:i:s', $a['ID']);
		$date_b = get_the_date('Y-m-d H:i:s', $b['ID']);
		return strtotime($date_a) < strtotime($date_b);
	});

    // Array of IDs
	return array_column($albums, 'ID');
}

function set_pagination_page(int $page_number): void
{
	header("Location: ?page=$page_number");
	exit();
}


function get_pages_count(int $total_albums_count, int $pagination_interval): int
{
    return ceil($total_albums_count / $pagination_interval);
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
            href="?page=$to_page#kvitek-gallery" 
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
    'heading' => 'Aktuality',
    'style' => 'none',
])
?>

<section class="section">
    <div class="max-content-width">
        <div class="grid grid-cols-3 gap-12 mb-12">
	        <?php if($paginated_ids): ?>
		        <?php foreach($paginated_ids as $post_id)
                {
			        get_template_part('components/blocks/ac_post_card', args: ['id' => $post_id]);
                }
                ?>
	        <?php else: ?>
                <p class="text-dark p-8 text-center">Zatím nebyly vydány žádné aktuality.</p>
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

                <p><?= 'Strana ' . $current_page . ' / ' . $total_pages ; ?></p>

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
