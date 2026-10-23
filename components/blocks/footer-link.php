<li class="list-none m-0 sm:mb-4 mb-2.5">
	<a
		class="block text-light-highlight hover:text-light-elevated no-underline! transition-colors duration-150 ease-in-out"
		href="<?= esc_url($link['url'] ?? '#'); ?>"
		target="<?= $link['target'] ?? '' ?>"
	>
		<?= esc_html($link['title'] ?? 'Chybí titulek'); ?>
	</a>
</li>