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
	<?php
        wp_head();

        $address = esc_html(get_field('address', 'option') ?? 'Chybí adresa');
        $email =get_field('email', 'option');
        $tel = get_field('tel', 'option');
    ?>
</head>
<body class="bg-light-base">
<header id="header" class="sticky top-0 z-30">
    <!-- Tab navigation menu skip link-->
    <a  href="#content"
        id="skipNavigation"
        class="pointer-events-none absolute top-4 left-4 z-50 bg-light-elevated border border-light-outline rounded-sm p-3 opacity-0 focus:opacity-100 text-dark"
    >
        <p>Přeskočit na obsah</p>
    </a>
    <nav id="navbar-quick-action" class="bg-brand-secondary py-2 px-8">
        <div class="max-content-width flex flex-wrap items-center justify-end  gap-x-6 gap-y-2">
	        <?php if($address): ?>
                <div class="flex gap-2 items-center text-light-elevated relative">
                    <i class="fa-solid fa-location-dot fa-md"></i>
                    <p class="font-medium"><?= $address ?></p>
                </div>
	        <?php endif; ?>
	        <?php if($tel): ?>
                <div class="flex gap-2 items-center text-light-elevated group relative">
                    <i class="fa-solid fa-phone group-hover:text-brand-primary-highlight transition-colors duration-150 ease-in-out fa-md"></i>
                    <p class="group-hover:text-brand-primary-highlight font-medium transition-colors duration-150 ease-in-out hidden sm:block"><?= $tel ?></p>
                    <a href="tel:<?= $tel ?>" class="absolute inset-0" aria-label="Phone"></a>
                </div>
	        <?php endif; ?>
	        <?php if($email): ?>
                <div class="flex gap-2 items-center text-light-elevated group relative">
                    <i class="fa-solid fa-envelope group-hover:text-brand-primary-highlight transition-colors duration-150 ease-in-out fa-md"></i>
                    <p class="group-hover:text-brand-primary-highlight font-medium transition-colors duration-150 ease-in-out hidden sm:block"><?= $email ?></p>
                    <a href="mailto:<?= str_replace(' ', '', $email) ?>" class="absolute inset-0" aria-label="Email"></a>
                </div>
	        <?php endif; ?>
        </div>
    </nav>
    <nav
        id="navbar"
        class="ac-nav-navbar"
    >
        <div class="max-content-width flex items-center justify-between gap-8">
            <!-- Left side-->
            <div>
		        <?php if (has_custom_logo()): ?>
                    <div class="w-56">
				        <?php the_custom_logo(); ?>
                    </div>
		        <?php else: ?>
                    <div class="flex items-center gap-2">
                        <a href="<?= esc_url(home_url('/')); ?>" class="h5">
                            <?php bloginfo('name'); ?>
                        </a>
                    </div>
		        <?php endif; ?>
            </div>

            <!-- Right side-->
            <div class="flex gap-4 justify-self-end items-center">
		        <?php if(has_nav_menu('primary')): ?>

			        <?php ac_build_primary_menu(); ?>

		        <?php else: ?>
                    <p>Menu nenalezeno</p>

		        <?php endif; ?>


                <!--            <div class="nav:flex hidden gap-4">-->
                <!--                <a href="--><?php //= esc_url($header_button['url']) ?><!--" class="btn-primary">-->
                <!--                    --><?php //= esc_html($header_button['text']) ?>
                <!--                </a>-->
                <!--                <a href="mailto:infoac@gmail.com" class="btn-secondary">-->
                <!--                    Mám otázku-->
                <!--                </a>-->
                <!--            </div>-->




                <button
                    id="slideoverOpenBtn"
                    class="ac-nav-open-btn aspect-square w-12.5"
                    aria-label="Otevřít navigační lištu"
                    aria-expanded="false"
                    aria-controls="slideover"
                >
                    <i class="fa-solid fa-bars fa-lg"></i>
                </button>
            </div>
    </nav>
        </div>


    <nav
        inert
        id="slideover"
        class="ac-nav-slideover-menu"
    >
        <div class="w-full flex flex-col gap-y-6 pb-4">
            <div>
                <button
                    id="slideoverCloseBtn"
                    class="ac-nav-close-btn aspect-square w-12.5"
                    aria-label="Close navigation"
                    aria-controls="slideover"
                >
                    <i class="fa-solid fa-xmark fa-lg"></i>
                </button>
            </div>
<!--            <div class="flex gap-2 justify-end">-->
<!--                <a href="--><?php //= esc_url($header_button['url']) ?><!--" class="btn-primary h-[3.25rem]">-->
<!--                    --><?php //= esc_html($header_button['text']) ?>
<!--                </a>-->
<!--            </div>-->
        </div>

        <div class="h-full overflow-y-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
			<?php if(has_nav_menu('primary')): ?>

				<?php ac_build_primary_menu(is_mobile: true); ?>

			<?php else: ?>

                <p>Menu nenalezeno</p>

			<?php endif; ?>
        </div>


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
