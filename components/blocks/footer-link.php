<li class="list-none m-0 mb-0.5">
	<a
		class="block p-1 hover:text-primary-highlight no-underline! transition-colors duration-300 ease-in-out"
		href="<?= esc_url($link['url'] ?? '#'); ?>"
		target="<?= $link['target'] ?? '' ?>"
	>
		<?= esc_html($link['title'] ?? 'Chybí titulek'); ?>
	</a>
</li>