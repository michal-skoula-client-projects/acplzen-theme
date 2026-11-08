<?php
/**
 * Primary navigation.
 */

/*
 * wp_nav_menu() has no li_class or submenu_class option of its own -- that was
 * TailPress's MenuOptions feature, and ac_build_primary_menu() below passes
 * li_class on every call. Without these two filters the nav silently loses
 * ac-nav-li from every item, so the framework's behaviour is ported here
 * rather than dropped.
 */
add_filter( 'nav_menu_css_class', function ( $classes, $item, $args, $depth ) {
	foreach ( [ 'li_class', "li_class_$depth" ] as $option ) {
		if ( isset( $args->$option ) ) {
			$classes[] = $args->$option;
		}
	}

	return $classes;
}, 10, 4 );

add_filter( 'nav_menu_submenu_css_class', function ( $classes, $args, $depth ) {
	foreach ( [ 'submenu_class', "submenu_class_$depth" ] as $option ) {
		if ( isset( $args->$option ) ) {
			$classes[] = $args->$option;
		}
	}

	return $classes;
}, 10, 3 );

/**
 * Prints the primary menu, with an extra "edit menu" item for administrators.
 *
 * wp_nav_menu() emits the whole <ul>, so the closing tag is stripped, the
 * extra item appended, and the list closed by hand.
 */
function ac_build_primary_menu( bool $is_mobile = false ): void
{
	$menu = (string) wp_nav_menu( [
		'theme_location'  => 'primary',
		'container'       => '',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => $is_mobile ? 'ac-nav-sidebar-parent-menu' : 'ac-nav-parent-ul',
		'li_class'        => $is_mobile ? 'ac-nav-li ac-is-mobile' : 'ac-nav-li',
		'fallback_cb'     => false,
		'echo'            => false,
		'depth'           => $is_mobile ? 1 : 2,
	] );

	if ( $menu === '' ) {
		return;
	}

	/*
	 * rtrim()'s second argument is a set of characters, not a suffix, so
	 * rtrim($menu, '</ul>') also ate the '>' off the preceding '</li>' and
	 * emitted malformed HTML. Strip the actual closing tag instead.
	 */
	echo preg_replace( '#</ul>\s*$#', '', $menu );

	// Lets administrators jump straight to the menu editor.
	if ( current_user_can( 'edit_theme_options' ) ) {
		$menu_config_url = esc_url( admin_url( 'nav-menus.php' ) );
		$message = '<span class="text-brand-primary"><i class="fa-solid fa-pen-to-square fa-sm"></i> Upravit</span>';

		echo <<<BUTTON
		<li title="Upravit menu - toto vidí pouze administrátoři" class="ac-nav-li nav:flex hidden">
			<a href="$menu_config_url"> $message </a>
		</li>
		BUTTON;
	}

	echo '</ul>';
}
