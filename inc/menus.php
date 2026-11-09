<?php

add_filter( 'nav_menu_css_class', function ( array $classes, $item, $args ): array {
	if ( isset( $args->li_class ) ) {
		$classes[] = $args->li_class;
	}

	return $classes;
}, 10, 3 );

/** Prints the primary menu, plus an "Upravit" item for administrators. */
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

	echo preg_replace( '#</ul>\s*$#', '', $menu );

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
