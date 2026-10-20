<?php
$paged = max(1, get_query_var('paged'));
$now = current_time('Y-m-d H:i:s');

$public_events = new WP_Query([
	'post_type'      => 'akce',
	'numberposts'    => 3,
	'meta_query'     => [
		[
			'key'     => 'event_type',
			'value'   => 'public',
			'compare' => '=',
		],
		[
			'key'     => 'datetime',
			'value'   => $now,
			'compare' => '>=',
			'type'    => 'DATETIME',
		],

	],
	'orderby'        => [
		'date' => 'DESC',
		'ID'   => 'DESC', // tie-breaker
	],
	'no_found_rows'  => true, // n/y for total pages
]);


$all_events = new WP_Query([
	'post_type'      => 'akce',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'meta_key'       => 'datetime',
    // ACF field name
	'orderby'        => [
		'meta_value' => 'DESC',
		'ID'         => 'DESC',
	],
	'meta_type'      => 'DATETIME',
	'meta_query'     => [
        [
			'key'     => 'datetime',
			'compare' => 'EXISTS',
		],
		[
			'key'     => 'datetime',
			'value'   => $now,
			'compare' => '<=',
			'type'    => 'DATETIME',
		],
    ],
]);

function create_pagination_button(callable $disabled, string $to_page_url, string $label, string $sr_only_label = ''): void
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
            href="$to_page_url" 
            class="$styles py-2 px-4 rounded-sm no-underline! border border-light-outline"
            title="$sr_only_label"
        >
            $label
        </a>
    HTML;
}

global $wp_query;

$current = (int)max( 1, (int) get_query_var('paged') );
$total   = $wp_query->max_num_pages;

$pg_first = $current > 1      ? get_pagenum_link( 1 ) : null;
$pg_last  = $current < $total ? get_pagenum_link( $total ) : null;
$pg_prev  = $current > 1      ? get_pagenum_link( $current - 1 ) : null;
$pg_next  = $current < $total ? get_pagenum_link( $current + 1 ) : null;

/**
 * @param  int  $month  Numeric representation of the month (1-12)
 *
 * @return string The shortcode, e.g. `LED` or `DUB`.
 */
function get_month_shortcode(int $month): string {
    return match($month) {
	    1 => 'LED', 2 => 'ÚNO',
	    3 => 'BŘE', 4 => 'DUB',
	    5 => 'KVĚ', 6 => 'ČVN',
	    7 => 'ČVC', 8 => 'SRP',
	    9 => 'ZÁŘ', 10 => 'ŘÍJ',
	    11 => 'LIS', 12 => 'PRO',
	    default => '???',
    };
}

function get_date_square(string $month_short, int $day, string $color = 'secondary'): string {
    return <<<HTML
		 <div class="bg-brand-$color flex flex-col rounded-sm items-center justify-center aspect-1/1 size-12" aria-hidden="true">
			<span class="text-sm text-light-highlight font-medium -mb-1 mt-1">$month_short</span>
			<span class="text-base text-light-elevated font-bold">$day</span>
		</div>
	HTML;
}
?>

<?php get_header() ?>

<?php get_template_part('components/sections/hero', args: [
	'heading' => 'Program',
	'style' => 'single',
	'description_left' => 'Pořádáme bezplatné akce pro školy i širokou veřejnost. Stačí si vybrat!',
	'bottom_gap' => 'small',
]) ?>


<?php if($public_events->have_posts()): ?>
    <section class="section">
        <div class="max-content-width">
            <h2 class="text-darkest mb-8">Nadcházející akce pro veřejnost</h2>

        	<div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
		        <?php while($public_events->have_posts()): $public_events->the_post();
			        $img = get_field('featured_img') ?: null;
			        $datetime = get_field('datetime') ?: null;
			        $description = get_field('description') ?: '';
				?>
                    <article class="sm:rounded-md rounded-sm border border-light-outline overflow-clip h-full">
                        <div class="relative">
                            <img src="<?= $img['sizes']['large'] ?? '' ?>" alt="<?= $img['caption'] ?: ' ' ?>" class="w-full aspect-16/10">
                            <a class="absolute inset-0" href="<?= get_permalink() ?>" tabindex="-1"></a>
                        </div>
                        <div class="sm:p-6 p-5">
                            <!-- Info -->
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-darker mb-0.5">
						        <?php if($datetime): ?>
                                    <div class="flex gap-2 items-center text-sm">
                                        <i class="fa-solid fa-calendar fa-sm"></i>
                                        <span>
									<time class="text-sm inline" datetime="<?= DateTime::createFromFormat('Y-m-d H:i:s', $datetime)->format('') ?>">
									<?= DateTime::createFromFormat('Y-m-d H:i:s', $datetime)->format('d. m. Y \o\d H:i') ?>
									</time>
                				</span>
                                    </div>
						        <?php endif; ?>
                            </div>
                            <!-- Content -->
                            <div class="mb-4">
                                <h3 class="h4 text-darkest mb-1"><?= get_the_title() ?></h3>
                                <p class="text-dark"><?= $description; ?></p>
                            </div>
                            <div>
						        <?= get_template_part('/components/blocks/ac_link', args: [
							        'style' => 'underline',
							        'color' => 'primary',
							        'label' => 'Přečíst článek',
							        'href' => get_permalink()
						        ]) ?>
                            </div>
                        </div>
                    </article>
		        <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>


<section class="section" id="timeline">
    <div class="max-content-width">
    	<h2 class="text-darkest mb-8">Archiv proběhlých akcí</h2>
        <div>
	        <?php $prev_bucket = null; ?>

	        <?php if ($all_events->have_posts()): ?>
		        <?php while ($all_events->have_posts()):
			        $all_events->the_post();
			        $dt = DateTime::createFromFormat('Y-m-d H:i:s', get_field('datetime'), wp_timezone());
			        $bucket = $dt->format('Y-m');

			        $date_formatted = date_i18n('F Y', $dt->getTimestamp());
			        $month_short = get_month_shortcode($dt->format('n'));
			        $day = date_i18n('d', $dt->getTimestamp());
			        $event_type = get_field('event_type');
			        ?>
                    <div class="grid md:grid-cols-[2fr_3fr] grid-cols-1 mb-5 [&:has(h3):not(:first-of-type)]:mt-12">
				        <?php if($bucket !== $prev_bucket): $prev_bucket = $bucket ?>
                            <div>
                                <h3 class="text-darkest mb-5"><?= $date_formatted ?></h3>
                            </div>
				        <?php endif; ?>
                        <div class="md:col-start-2 @container">
					        <?php if($event_type === 'public'): ?>
                                <div class="bg-brand-primary/5 border border-light-outline rounded-md overflow-clip grid @xl:grid-cols-2 grid-cols-1 gap-4 @max-xl:row-start-2">
                                    <div class="p-card-sm flex flex-col">
                                        <div class="mb-2">
		                                    <?= get_date_square($month_short, $day, 'primary') ?>
                                            <p class="sr-only"><?= date_i18n('d. F', $dt->getTimestamp()) ?></p>
                                        </div>
                                        <p class="text-dark/85 text-sm">Akce pro veřejnost</p>
                                        <strong class="text-darker text-xl mb-2"><?= esc_html(get_the_title())?></strong>
                                        <p class="text-sm text-dark mb-4"><?= esc_allow_safe(get_field('description') ?: '') ?></p>
	                                    <?php get_template_part('/components/blocks/ac_link', args: [
		                                    'color' => 'primary',
		                                    'style' => 'underline',
		                                    'href' => get_post_permalink(),
		                                    'label' => 'Zjistit více',
                                            'class' => 'text-sm'
	                                    ]) ?>
                                    </div>
                                    <div class="@max-xl:row-start-1">
                                        <?php $img = get_field('featured_img') ?: null ?>
                                        <img
                                            class="h-full w-full object-cover aspect-16/10"
                                            src="<?= $img['sizes']['large'] ?? '' ?>"
                                            alt="<?= $img['captiom'] ?? '' ?>">
                                    </div>


                                </div>
					        <?php endif; ?>
					        <?php if($event_type === 'private'): ?>
                                <div class="p-card-sm bg-light-elevated border border-light-outline rounded-md flex gap-x-4 items-center">
                                    <div>
								        <?= get_date_square($month_short, $day, 'secondary') ?>
                                        <p class="sr-only"><?= date_i18n('d. F', $dt->getTimestamp()) ?></p>
                                    </div>
                                    <div class="items-center">
                                        <strong class="inline-block text-darker text-lg -mb-0.5"><?= esc_html(get_the_title())?></strong>
                                        <p class="text-dark text-sm"><?= esc_html(get_field('private_details') ?: '') ?></p>
                                    </div>

                                </div>
					        <?php endif; ?>
                        </div>
                    </div>

		        <?php endwhile; wp_reset_postdata(); ?>

		        <?php if( get_next_posts_link() || get_previous_posts_link() ): ?>
                    <div class="w-full grid grid-flow-col gap-4 grid-cols[1fr_10ch_1fr] items-center justify-center">
                        <div class="flex gap-1">
					        <?php
					        create_pagination_button(
						        disabled: fn() => ! $pg_first,
						        to_page_url: $pg_first ?? '',
						        label: '<i class="fa-solid fa-angles-left"></i>',
						        sr_only_label: 'První stránka'
					        );
					        create_pagination_button(
						        disabled: fn() => ! $pg_prev,
						        to_page_url: $pg_prev ?? '',
						        label: '<i class="fa-solid fa-chevron-left"></i>',
						        sr_only_label: 'Předchozí stránka'
					        );
					        ?>
                        </div>

                        <p class="text-dark"><?= 'Strana ' . $current . ' / ' . $total ; ?></p>

                        <div class="flex gap-1">
					        <?php
					        create_pagination_button(
						        disabled: fn() => ! $pg_next,
						        to_page_url: $pg_next ?? '',
						        label: '<i class="fa-solid fa-chevron-right"></i>',
						        sr_only_label: 'Další stránka'
					        );
					        create_pagination_button(
						        disabled: fn() => ! $pg_last,
						        to_page_url: $pg_last ?? '',
						        label: '<i class="fa-solid fa-angles-right"></i>',
						        sr_only_label: 'Poslední stránka'
					        );
					        ?>
                        </div>
                    </div>
		        <?php endif; ?>
	        <?php endif; ?>
        </div>
    </div>
</section>








<?php get_footer() ?>