<?php
/**
 * Header template
 *
 * @author Michal Škoula
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body>
<header id="header" class="fixed w-full z-30 md:py-4 md:px-4 px-4 py-4">
    <nav
        id="navbar"
        class="kvitek-nav-navbar shadow-sm max-w-screen-lg mx-auto"
    >
        <!-- Tab navigation menu skip link -->
        <a
            href="#content"
            id="skipNavigation"
            class="pointer-events-none absolute top-4 left-4 z-50 bg-primary-darker rounded-lg p-3 opacity-0  border border-primary-outline focus:opacity-100"
        ><p>Přeskočit na obsah</p></a
        >

        <div class="max-w-screen-lg mx-auto flex justify-between items-center sm:p-8 p-5 gap-6">
            <!-- Left side -->
            <div>
		        <?php if (has_custom_logo()): ?>
                    <div class="sm:**:w-28 **:w-24">
				        <?php the_custom_logo(); ?>
                    </div>
		        <?php else: ?>
                    <div class="flex items-center gap-2">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="h6">
                            <?php bloginfo('name'); ?>
                        </a>
                    </div>
		        <?php endif; ?>
            </div>

            <!-- Right side-->
            <div class="flex gap-4 justify-self-end items-center">
		        <?php if(has_nav_menu('primary')): ?>

			        <?php kvitek_build_primary_menu(is_mobile: false); ?>

		        <?php else: ?>
                    <p>Menu nenalezeno</p>

		        <?php endif; ?>

                <button
                    id="slideoverOpenBtn"
                    class="kvitek-nav-open-btn"
                    aria-label="Otevřít navigační lištu"
                    aria-expanded="false"
                    aria-controls="slideover"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="sm:size-7 size-6 text-light-soft">
                        <path d="M160-240q-17 0-28.5-11.5T120-280q0-17 11.5-28.5T160-320h640q17 0 28.5 11.5T840-280q0 17-11.5 28.5T800-240H160Zm0-200q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520h640q17 0 28.5 11.5T840-480q0 17-11.5 28.5T800-440H160Zm0-200q-17 0-28.5-11.5T120-680q0-17 11.5-28.5T160-720h640q17 0 28.5 11.5T840-680q0 17-11.5 28.5T800-640H160Z"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <nav
        inert
        id="slideover"
        class="kvitek-nav-slideover-menu"
    >
        <div class="w-full flex justify-end items-center">
            <button
                id="slideoverCloseBtn"
                class="kvitek-nav-close-btn"
                aria-label="Close navigation"
                aria-controls="slideover"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="sm:size-7 size-6 text-light-soft">
                    <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
                </svg>
            </button>
        </div>


		<?php if(has_nav_menu('primary')): ?>

			<?php kvitek_build_primary_menu(is_mobile: true); ?>

		<?php else: ?>

            <p>Menu nenalezeno</p>

		<?php endif; ?>

    </nav>
    <div
        id="backdrop"
        class="max-nav:bg-black [&.shown]:max-nav:opacity-70 fixed inset-0 z-40 opacity-0 transition-opacity duration-200 ease-in-out motion-reduce:transition-none"
        inert
        aria-hidden="true"
    ></div>
</header>

<div id="content">
    <main>
        <!-- Content goes here... -->
