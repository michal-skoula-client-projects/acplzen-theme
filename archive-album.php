<?php
const PAGINATION_INTERVAL = 10;

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

	$gallery = get_posts([
		'post_type' => 'album',
        'posts_per_page' => -1
	]);

    if($gallery)
    {
        $ids = get_album_ids($gallery);
        $min = $pagination_interval * ($current_page - 1);
        $paginated_ids = array_slice($ids, $min, $pagination_interval);

        if(count($ids) > $pagination_interval) {
            $is_paginated = true;
        }

	    // Out of bounds
	    if(! $paginated_ids) {
            ac_404();
        }
    }
}
/**
 * @param array<WP_Post> $gallery
 *
 * @return array<int>
 */
function get_album_ids(array $gallery): array
{
	update_postmeta_cache(wp_list_pluck($gallery, 'ID'));

	$albums = array_map(fn(WP_Post $p) => (array) $p, $gallery);

	// Remove unknown dates from showing
	$albums = array_filter($albums, fn ($a) => get_field('event_date', $a['ID']));

	// Newest first
	usort($albums, function($a, $b) {
		$date_a = get_field('event_date', $a['ID']);
		$date_b = get_field('event_date', $b['ID']);
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
        ? 'pointer-not-allowed! bg-primary-muted text-light-soft'
        : 'pointer-click! bg-primary-base text-light-base';


    echo <<<HTML
        <a 
            $is_disabled_js
            $inline_styles
            $tab_navigatable
            href="?page=$to_page#kvitek-gallery" 
            class="$styles py-2 px-3 rounded-lg no-underline! "
        >
            $label
            <p class="sr-only">$sr_only_label</p>
        </a>
    HTML;
}

?>

<?php get_header(); ?>

<?php

get_template_part('components/sections/hero-pages', args: [
    'tagline' => 'Fotogalerie',
    'title' => 'Co jsme zažili',
    'description' => 'Archiv fotografií ze všech akcí Kvítku',
])
?>

<section class="section pointer" id="kvitek-gallery">
    <div class="max-w-screen-lg mx-auto grid gap-12">
        <?php if($paginated_ids): ?>
            <?php foreach($paginated_ids as $album_id):
                $album_name = esc_allow_safe(get_the_title($album_id));
                $album_description = esc_allow_safe(get_field('album_description', $album_id));

                $location = esc_html(get_field('location', $album_id));
		        $featured_image = get_field('featured_image', $album_id);
		        $date = esc_html(get_field('event_date', $album_id));

                $images = get_field('images', $album_id);

            ?>

                <article id="<?= "album-$album_id" ?>" class="grid md:grid-cols-[2fr_3fr] grid-cols-1 gap-12">
                    <!-- Album cover -->
                    <div class="flex place-items-center justify-center rounded-xl overflow-clip bg-light-faint min-h-[15rem]">
                        <?php if($featured_image): //Has featured image ?>

                            <img
                                src="<?= esc_url( $featured_image['sizes']['large']); ?>"
                                alt="<?= esc_html($featured_image['alt']); ?>"
                                class="w-full h-full object-cover aspect-4/3"
                                loading="lazy"
                            >

                        <?php elseif($images): //No featured image but has content ?>

                            <img src="<?= esc_url($images[0]['sizes']['large']); ?>"
                                 alt="<?= esc_html($images[0]['alt']); ?>"
                                 class="w-full h-full object-cover aspect-4/3"
                                 loading="lazy"
                            >


                        <?php else: ?>

                            <p>Chybí náhled</p>

                        <?php endif; ?>
                    </div>

                    <!-- Album info -->
                    <div>
                        <h2 class="h3 mb-2"><?= $album_name ?></h2>

                        <div class="flex gap-x-4 gap-y-2 flex-wrap text-dark-faint mb-3">
	                        <?php if($date): ?>
                                <div class="flex gap-0.5 items-center">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <mask id="mask0_343_202" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                            <rect width="20" height="20" fill="currentColor"/>
                                        </mask>
                                        <g mask="url(#mask0_343_202)">
                                            <path d="M4.42321 17.9167C4.00223 17.9167 3.64591 17.7708 3.35425 17.4792C3.06258 17.1875 2.91675 16.8312 2.91675 16.4102V5.25647C2.91675 4.8355 3.06258 4.47918 3.35425 4.18751C3.64591 3.89585 4.00223 3.75001 4.42321 3.75001H5.57696V1.9873H6.85904V3.75001H13.1732V1.9873H14.4232V3.75001H15.577C15.9979 3.75001 16.3542 3.89585 16.6459 4.18751C16.9376 4.47918 17.0834 4.8355 17.0834 5.25647V16.4102C17.0834 16.8312 16.9376 17.1875 16.6459 17.4792C16.3542 17.7708 15.9979 17.9167 15.577 17.9167H4.42321ZM4.42321 16.6667H15.577C15.6411 16.6667 15.6999 16.6399 15.7532 16.5865C15.8067 16.5331 15.8334 16.4744 15.8334 16.4102V8.5898H4.16675V16.4102C4.16675 16.4744 4.19348 16.5331 4.24696 16.5865C4.30029 16.6399 4.35904 16.6667 4.42321 16.6667ZM4.16675 7.3398H15.8334V5.25647C15.8334 5.1923 15.8067 5.13355 15.7532 5.08022C15.6999 5.02675 15.6411 5.00001 15.577 5.00001H4.42321C4.35904 5.00001 4.30029 5.02675 4.24696 5.08022C4.19348 5.13355 4.16675 5.1923 4.16675 5.25647V7.3398Z" fill="currentColor"/>
                                        </g>
                                    </svg>

                                    <p class="text-sm">
                                        <?= DateTime::createFromFormat('Y-m-d', $date)->format('j.n.Y') ?>
                                    </p>
                                </div>
	                        <?php endif; ?>

	                        <?php if($location): ?>
                                <div class="flex gap-0.5 items-center">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <mask id="mask0_343_212" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                            <rect width="20" height="20" fill="currentColor"/>
                                        </mask>
                                        <g mask="url(#mask0_343_212)">
                                            <path d="M10.0014 9.88787C10.416 9.88787 10.7704 9.74023 11.0648 9.44495C11.3592 9.14968 11.5064 8.79475 11.5064 8.38016C11.5064 7.96558 11.3588 7.61107 11.0635 7.31662C10.7682 7.02232 10.4132 6.87516 9.9985 6.87516C9.58392 6.87516 9.22948 7.0228 8.93517 7.31808C8.64073 7.61336 8.4935 7.96836 8.4935 8.38308C8.4935 8.79766 8.64114 9.15211 8.93642 9.44641C9.2317 9.74072 9.5867 9.88787 10.0014 9.88787ZM9.99996 16.2614C11.6302 14.802 12.8778 13.4021 13.7427 12.0618C14.6075 10.7216 15.04 9.54766 15.04 8.54016C15.04 7.021 14.5573 5.77211 13.592 4.7935C12.6268 3.81489 11.4294 3.32558 9.99996 3.32558C8.57052 3.32558 7.37316 3.81489 6.40788 4.7935C5.4426 5.77211 4.95996 7.021 4.95996 8.54016C4.95996 9.54766 5.39239 10.7216 6.25725 12.0618C7.12211 13.4021 8.36968 14.802 9.99996 16.2614ZM9.99996 17.9247C7.90274 16.1075 6.3301 14.4163 5.28204 12.8512C4.23399 11.2859 3.70996 9.84891 3.70996 8.54016C3.70996 6.61711 4.33197 5.06023 5.576 3.86954C6.82017 2.67884 8.29482 2.0835 9.99996 2.0835C11.7051 2.0835 13.1798 2.67884 14.4239 3.86954C15.6679 5.06023 16.29 6.61711 16.29 8.54016C16.29 9.84891 15.7659 11.2859 14.7179 12.8512C13.6698 14.4163 12.0972 16.1075 9.99996 17.9247Z" fill="currentColor"/>
                                        </g>
                                    </svg>
                                    <p class="text-sm"><?= $location ?></p>
                                </div>
	                        <?php endif; ?>

	                        <?php if(true): ?>
                                <div class="flex gap-0.5 items-center">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <mask id="mask0_343_219" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                            <rect width="20" height="20" fill="currentColor"/>
                                        </mask>
                                        <g mask="url(#mask0_343_219)">
                                            <path d="M7.56425 11.5387H15.3526L12.7661 8.14121L10.7853 10.6733L9.47758 9.05475L7.56425 11.5387ZM6.71487 14.5835C6.2939 14.5835 5.93758 14.4377 5.64591 14.146C5.35425 13.8543 5.20841 13.498 5.20841 13.077V3.58995C5.20841 3.16898 5.35425 2.81266 5.64591 2.521C5.93758 2.22933 6.2939 2.0835 6.71487 2.0835H16.202C16.6229 2.0835 16.9792 2.22933 17.2709 2.521C17.5626 2.81266 17.7084 3.16898 17.7084 3.58995V13.077C17.7084 13.498 17.5626 13.8543 17.2709 14.146C16.9792 14.4377 16.6229 14.5835 16.202 14.5835H6.71487ZM6.71487 13.3335H16.202C16.2661 13.3335 16.3249 13.3068 16.3782 13.2533C16.4317 13.2 16.4584 13.1412 16.4584 13.077V3.58995C16.4584 3.52579 16.4317 3.46704 16.3782 3.4137C16.3249 3.36023 16.2661 3.3335 16.202 3.3335H6.71487C6.65071 3.3335 6.59196 3.36023 6.53862 3.4137C6.48515 3.46704 6.45841 3.52579 6.45841 3.58995V13.077C6.45841 13.1412 6.48515 13.2 6.53862 13.2533C6.59196 13.3068 6.65071 13.3335 6.71487 13.3335ZM3.79821 17.5002C3.37723 17.5002 3.02091 17.3543 2.72925 17.0627C2.43758 16.771 2.29175 16.4147 2.29175 15.9937V5.25662H3.54175V15.9937C3.54175 16.0579 3.56848 16.1166 3.62196 16.17C3.67529 16.2234 3.73404 16.2502 3.79821 16.2502H14.5353V17.5002H3.79821Z" fill="currentColor"/>
                                        </g>
                                    </svg>

                                    <p class="text-sm"><?= ($images ? count($images) : 0) . ' fotek' ?></p>
                                </div>
	                        <?php endif; ?>

                        </div>

                        <div class="mb-6">
                            <?= $album_description ?>
                        </div>

                        <div>
                            <a href="<?= get_post_permalink($album_id) . "?gp=$current_page" // gp handles correct gallery page return ?>"
                               class="button__primary">
                                Zobrazit album
                            </a>
                        </div>

                    </div>
                </article>
            <?php endforeach; ?>

            <?php if($is_paginated):
                $total_pages = get_pages_count(count($ids), $pagination_interval);
            ?>
                <div class="w-full grid grid-flow-col gap-4 grid-cols[1fr_10ch_1fr] items-center justify-center">
                    <div class="flex gap-1">
                        <?php
                            create_pagination_button(
                                disabled: fn() => $current_page === 1,
                                to_page: $current_page - 1,
                                label: '‹',
                                sr_only_label: 'Předchozí stránka'
                            );
                            create_pagination_button(
                                disabled: fn() => $current_page === 1,
                                to_page: 1,
                                label: 'První'
                            );
                        ?>
                    </div>

                    <p><?= 'Strana ' . $current_page . ' / ' . $total_pages ; ?></p>

                    <div class="flex gap-1">
		                <?php
                            create_pagination_button(
                                disabled: fn() => $current_page === $total_pages,
                                to_page: $total_pages,
                                label: 'Poslední',
                            );
                            create_pagination_button(
                                disabled: fn() => $current_page === $total_pages,
                                to_page: $current_page + 1,
                                label: '›',
	                            sr_only_label: 'Další stránka'

                            );
		                ?>
                    </div>
                </div>


            <?php endif; ?>

        <?php else: ?>
            <p>Zatím nebyla přidána žádná alba.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
