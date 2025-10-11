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
    <script src="https://kit.fontawesome.com/0e273c52d3.js" crossorigin="anonymous"></script>
	<?php wp_head(); ?>
</head>
<body class="bg-light-base">
<!--<header id="header" class="sticky top-0 z-30">-->
<!--    <nav-->
<!--        id="navbar"-->
<!--        class="ac-nav-navbar"-->
<!--    >-->
        <!-- Tab navigation menu skip link -->
<!--        <a  href="#content"-->
<!--            id="skipNavigation"-->
<!--            class="pointer-events-none absolute top-4 left-4 z-50 bg-primary-darker rounded-lg p-3 opacity-0  border border-primary-outline focus:opacity-100"-->
<!--        >-->
<!--            <p>Přeskočit na obsah</p>-->
<!--        </a>-->
<!---->
        <!-- Left side -->
<!--        <div>-->
<!--			--><?php //if (has_custom_logo()): ?>
<!--                <div>-->
<!--					--><?php //the_custom_logo(); ?>
<!--                </div>-->
<!--			--><?php //else: ?>
<!--                <div class="flex items-center gap-2">-->
<!--                    <a href="--><?php //echo esc_url(home_url('/')); ?><!--" class="h6">-->
<!--                        --><?php //bloginfo('name'); ?>
<!--                    </a>-->
<!--                </div>-->
<!--			--><?php //endif; ?>
<!--        </div>-->
<!---->
        <!-- Right side-->
<!--        <div class="flex gap-4 justify-self-end items-center">-->
<!--			--><?php //if(has_nav_menu('primary')): ?>
<!---->
<!--				--><?php //ac_render_primary_menu(); ?>
<!---->
<!--			--><?php //else: ?>
<!--                <p>Menu nenalezeno</p>-->
<!---->
<!--			--><?php //endif; ?>
<!---->
<!---->
<!--            <div class="nav:flex hidden gap-4">-->
<!--                <a href="--><?php //= esc_url($header_button['url']) ?><!--" class="btn-primary">-->
<!--                    --><?php //= esc_html($header_button['text']) ?>
<!--                </a>-->
<!--                <a href="mailto:infoac@gmail.com" class="btn-secondary">-->
<!--                    Mám otázku-->
<!--                </a>-->
<!--            </div>-->
<!---->
<!---->
<!---->
<!---->
<!--            <button-->
<!--                id="slideoverOpenBtn"-->
<!--                class="ac-nav-open-btn"-->
<!--                aria-label="Otevřít navigační lištu"-->
<!--                aria-expanded="false"-->
<!--                aria-controls="slideover"-->
<!--            >-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="size-7 text-white">-->
<!--                    <path d="M160-240q-17 0-28.5-11.5T120-280q0-17 11.5-28.5T160-320h640q17 0 28.5 11.5T840-280q0 17-11.5 28.5T800-240H160Zm0-200q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520h640q17 0 28.5 11.5T840-480q0 17-11.5 28.5T800-440H160Zm0-200q-17 0-28.5-11.5T120-680q0-17 11.5-28.5T160-720h640q17 0 28.5 11.5T840-680q0 17-11.5 28.5T800-640H160Z"/>-->
<!--                </svg>-->
<!--            </button>-->
<!--        </div>-->
<!--    </nav>-->
<!---->
<!--    <nav-->
<!--        inert-->
<!--        id="slideover"-->
<!--        class="ac-nav-slideover-menu"-->
<!--    >-->
<!--        <div class="w-full flex flex-col gap-y-6 pb-4">-->
<!--            <div>-->
<!--                <button-->
<!--                    id="slideoverCloseBtn"-->
<!--                    class="ac-nav-close-btn"-->
<!--                    aria-label="Close navigation"-->
<!--                    aria-controls="slideover"-->
<!--                >-->
<!--                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="size-7 text-white">-->
<!--                        <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>-->
<!--                    </svg>-->
<!--                </button>-->
<!--            </div>-->
<!--            <div class="flex gap-2 justify-end">-->
<!--                <a href="--><?php //= esc_url($header_button['url']) ?><!--" class="btn-primary h-[3.25rem]">-->
<!--                    --><?php //= esc_html($header_button['text']) ?>
<!--                </a>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="h-full overflow-y-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">-->
<!--			--><?php //if(has_nav_menu('primary')): ?>
<!---->
<!--				--><?php //ac_render_primary_menu(is_mobile_version: true); ?>
<!---->
<!--			--><?php //else: ?>
<!---->
<!--                <p>Menu nenalezeno</p>-->
<!---->
<!--			--><?php //endif; ?>
<!--        </div>-->
<!---->
<!---->
<!--    </nav>-->
<!--    <div-->
<!--        id="backdrop"-->
<!--        class="max-nav:bg-black [&.shown]:max-nav:opacity-70 fixed inset-0 z-40 opacity-0 transition-opacity duration-200 ease-in-out motion-reduce:transition-none"-->
<!--        inert-->
<!--        aria-hidden="true"-->
<!--    ></div>-->
<!--</header>-->

<div id="content">
    <main>
        <!-- Content goes here... -->
