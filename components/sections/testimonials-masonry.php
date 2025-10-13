<?php
/**
 * Testimoniály s rozdělením alá. masonry
 *
 * @author Michal Škoula
 * @since 1.0.0
 */
//TODO: finish

// Field data retrieval happens here

$items = []; // each item = ['html' => ..., 'weight' => int]
// Build from ACF repeater:
if ( have_rows( 'testimonials' ) ) {
	while ( have_rows( 'testimonials' ) ) {
		the_row();
		$text    = get_sub_field( 'text' ) ? : '';
		$html    = '<article class="rounded-2xl bg-white p-6 shadow">' . wp_kses_post( wpautop( $text ) ) . '</article>';
		$weight  = mb_strlen( wp_strip_all_tags( $text ) ); // crude height proxy
		$items[] = [ 'html' => $html, 'weight' => $weight ];
	}
}

// Greedy packing into 3 columns by cumulative weight
$cols = [ [], [], [] ];
$sum  = [ 0, 0, 0 ];
foreach ( $items as $it ) {
	$i            = array_search( min( $sum ), $sum, true );
	$cols[ $i ][] = $it['html'];
	$sum[ $i ]    += $it['weight'];
}
?>

<p>work in progress</p>



