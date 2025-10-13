<?php
$opening_hours = esc_html(get_field('opening_hours', 'options') ?: '');
$email = get_field('email', 'options') ?: '';
$tel = get_field('tel', 'options') ?: '';
$socials = get_field('socials', 'options');
$navigation = get_field('cols', 'options') ?: null;
$map_url = get_field('maps_url', 'options') ?: null;
$address = get_field('contacts', 'options') ?: null;
?>

<footer class="section bg-darkest">
	<div class="max-content-width lg:pt-32 md:pt-28 pt-20">
		<!-- Footer title -->
		<p class="h2 text-light-elevated text-center mb-10"><?= get_bloginfo() ?></p>

		<!-- Contact pill -->
		<div class="bg-dark p-12 grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 max-md:justify-center gap-x-4 gap-y-4 rounded-md mb-16">
			<dl class="flex flex-col gap-2">
				<dt><p class="text-light-base">Otevírací doba</p></dt>
				<dd><p class="h5 text-light-elevated"><?= $opening_hours ?></p></dd>
			</dl>
			<dl class="flex flex-col gap-2">
				<dt><p class="text-light-base">Email</p></dt>
				<dd><a class="h5 text-light-elevated underline! decoration-2!" href="mailto:<?= $email ?>"><?= $email ?></a></dd>
			</dl>
			<dl class="flex flex-col gap-2">
				<dt><p class="text-light-base">Telefon</p></dt>
				<dd><a class="h5 text-light-elevated underline! decoration-2!" href="tel:<?= $tel ?>"><?= $tel ?></a></dd>
			</dl>
			<div class="flex flex-col gap-2">
				<p class="text-light-base">Sociální sítě</p>
				<?php if($socials): ?>
					<div class="flex flex-wrap gap-3">
						<?php foreach ($socials as $item): $social = $item['item']; ?>
							<a href="<?= esc_url($social['url' ?? '#']) ?>" target="_blank" rel="nofollow" class="block text-light-elevated">
								<i class="fa-brands fa-<?= esc_html(strtolower($social['title'] ?? 'wordpress-simple')) ?> fa-2xl"></i>
								<span class="sr-only"><?= $social['title'] ?? 'Neznámá síť' ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<p class="h5 text-light-elevated">Zatím tu žádné nejsou :(</p>
				<?php endif; ?>
			</div>
		</div>

		<!-- Navigation -->
		<nav class="flex gap-x-16 gap-y-8 flex-wrap w-max mb-10 max-w-full">
			<?php if($navigation): ?>
				<?php foreach($navigation as $column): ?>
					<div class="flex flex-col gap-2 max-sm:w-full">
						<strong class="h6 font-medium mb-3 text-light-elevated"><?= esc_html($column['heading'] ?? 'Chybí nadpis') ?></strong>
						<ul class="m-0">
							<?php if($column['urls']): ?>
								<?php foreach($column['urls'] as $row): $link = $row['url'];
									include __DIR__ . '/components/blocks/footer-link.php';
								endforeach; ?>
							<?php endif; ?>
						</ul>
					</div>
				<?php endforeach; ?>
                <?php if($address): ?>
                    <div class="flex flex-col gap-2 max-md:w-full max-w-sm">
                        <strong class="h6 font-medium mb-3 text-light-elevated">Kontaktní údaje</strong>
                        <div class="m-0 text-light-base section__format-text">
                            <?= esc_allow_safe($address) ?>
                        </div>
                    </div>
                <?php endif; ?>
			<?php else: ?>
				<p class="text-light-base">Zatím žádné sloupce navigace</p>
			<?php endif; ?>
		</nav>

		<!-- Google maps widget -->
        <?php if($map_url): ?>
		<iframe
            src="<?= esc_url($map_url) ?>"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            class="w-full h-full sm:aspect-16/9 aspect-4/3 rounded-sm"
        ></iframe>
        <?php endif; ?>
	</div>
</footer>
<div class="text-light-highlight bg-darkest-footer lg:px-16 md:px-12 px-5 py-4">
    <div class="max-content-width flex flex-wrap items-center justify-between gap-4">
        <div class="flex gap-5">
            <span>&copy; <?= get_field('copyright', 'options') ?: get_bloginfo() ?> <?= date('Y') ?>. <span class="md:inline hidden">Všechna práva vyhrazena.</span></span>
<!--            <a href="--><?php //= ISSUE_REPORT_EMAIL ?><!--" class="text-light-highlight underline! max-md:hidden">Nahlásit chybu</a>-->
        </div>
        <p>Web vytvořil <a href="https://skoula.com" class="text-light-elevated hover:underline!" rel="nofollow" target="_blank">Michal Škoula</a>.</p>
    </div>
    <div class="max-content-width flex flex-wrap gap-6 mt-3.5 text-light-highlight/80">
        <a href="<?= ISSUE_REPORT_EMAIL ?>" class=" underline!">Nahlásit chybu</a>
        <a href="<?= admin_url() ?>" class="underline!">Administrace webu</a>
    </div>

</div>

