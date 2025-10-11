<?php
/**
 * Sekce pro stažení letáčků
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_html(get_sub_field('heading'));
$files = get_sub_field('files');
// Field data retrieval happens here

?>

<section class="section">
	<div class="mx-auto max-w-screen-lg">
		<h2 class="text-darkest text-center mb-12"><?= $heading ?></h2>

		<ul class="m-0 p-0">
			<?php if($files): ?>
				<?php foreach ($files as $data):
                    $file = $data['file'] ?? null;
					$filesize_visual = null;
					$filesize = (int)$file['filesize'] ?? 0;

                    if($filesize === 0) $filesize_visual = '? bajtů';
					else if($filesize < 1000) $filesize_visual = "{$filesize} bajtů";
					else if($filesize < 1000000) $filesize_visual = round($filesize / 1000, precision: 1) . ' kB';
					else $filesize_visual = round($filesize / 1000000, precision: 1) . ' MB'
                ?>
                    <li class="flex justify-between p-4 border-b border-b-light-outline items-center">
                        <div>
                            <p class="text-dark text-sm"><?= $filesize_visual ?></p>
                            <p class="text-lg text-darker"><?= $data['title'] ?? 'Letáček bez názvu' ?></p>
                        </div>
                        <div>
                            <a href="<?= $file['url'] ?? '#' ?>" class="inline-flex text-light-elevated group" download>
                                <div class="bg-brand-secondary group-hover:bg-brand-secondary-focus group-focus:bg-brand-secondary-focus group-active:bg-brand-secondary-highlight duration-200 transition-colors rounded-full p-3 pl-5 py-2.5 pr-4">
                                    <span class="align-middle font-medium">Stáhnout</span>
                                </div>
                                <div class="bg-brand-secondary group-hover:bg-brand-secondary-focus group-focus:bg-brand-secondary-focus group-active:bg-brand-secondary-highlight duration-200 h-full inline-block aspect-1/1 rounded-full py-2.5 px-4 text-light-elevated -translate-x-2 group-hover:-translate-x-3.5 group-focus:-translate-x-3.5 group-active:-translate-x-3.5 transition-transform">
                                    <i class="fa fa-download align-middle"></i>
                                </div>
                            </a>
                        </div>
                    </li>


				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
</section>




