<?php
/**
 * Pilulka s kontakty z options page kontakty
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$opening_hours = esc_html(get_field('opening_hours', 'options') ?: '');
$email = get_field('email', 'options') ?: '';
$tel = get_field('tel', 'options') ?: '';
$socials = get_field('socials', 'options') ?? null;

?>

<section class="section">
	<div class="max-content-width bg-light-elevated border border-light-outline p-12 grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 max-md:justify-center gap-x-4 gap-y-4 rounded-md">
		<dl class="flex flex-col gap-2">
			<dt><p class="text-dark">Otevírací doba</p></dt>
			<dd><p class="h5 text-darkest"><?= $opening_hours ?></p></dd>
		</dl>
		<dl class="flex flex-col gap-2">
			<dt><p class="text-dark">Email</p></dt>
			<dd><a class="h5 text-darkest underline! decoration-2!" href="mailto:<?= $email ?>"><?= $email ?></a></dd>
		</dl>
		<dl class="flex flex-col gap-2">
			<dt><p class="text-dark">Telefon</p></dt>
			<dd><a class="h5 text-darkest underline! decoration-2!" href="tel:<?= $tel ?>"><?= $tel ?></a></dd>
		</dl>
		<div class="flex flex-col gap-2">
			<p class="text-dark">Sociální sítě</p>
			<?php if($socials): ?>
				<div class="flex flex-wrap gap-3">
					<?php foreach ($socials as $item): $social = $item['item']; ?>
						<a href="<?= esc_url($social['url' ?? '#']) ?>" target="_blank" rel="nofollow" class="block text-darkest">
							<i class="fa-brands fa-<?= esc_html(strtolower($social['title'] ?? 'wordpress-simple')) ?> fa-2xl"></i>
							<span class="sr-only"><?= $social['title'] ?? 'Neznámá síť' ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<p class="h5 text-darkest">Zatím tu žádné nejsou :(</p>
			<?php endif; ?>
		</div>

	</div>
</section>


