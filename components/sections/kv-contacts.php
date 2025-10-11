<?php
/**
 * Contacts section displaying contacts defined in the "Kontakty" CPT
 *
 * @author Michal Škoula
 * @since 1.0.0
 */

$heading = esc_allow_safe(get_sub_field('heading'));
$contacts_posts = get_posts([
    'post_type' => 'kontakt'
]);

if ($contacts_posts) {
	update_postmeta_cache(wp_list_pluck($contacts_posts, 'ID'));

	$contacts = array_map(fn(WP_Post $p) => (array) $p, $contacts_posts);
}
?>

<section class="section">
    <div class="max-content-width">
        <div>
            <h2 class="mb-12 text-center"><?= $heading ?></h2>

            <?php if(isset($contacts)): ?>
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-8">
	                <?php foreach($contacts as $contact): $id = $contact['ID'];

                        $name = esc_html($contact['post_title']);
                        $role = esc_html(get_field('role', $id));
		                $photo = (array)get_field('image', $id);
		                $contact_methods = (array)get_field('contact_options', $id);
                    ?>
                        <div class="grid sm:grid-cols-[10rem_1fr] grid-cols-1 rounded-lg overflow-clip bg-light-base shadow-lg p-3">
                            <div>
                                <?php if(isset($photo)): ?>
                                    <img src="<?= esc_url($photo['sizes']['medium']); ?>" alt="" class="h-full w-full object-cover rounded-sm">
                                <?php endif; ?>
                            </div>
                            <div class="p-8">
                                <h3 class="h4 mb-1"><?= $name ?></h3>
                                <p class="mb-2"><?= $role ?></p>
                                <?php if($contact_methods): ?>
                                    <div class="flex flex-col gap-0.5">
	                                    <?php foreach($contact_methods as $method):
		                                    $label = esc_html($method['label']);
		                                    $url = esc_url($method['url']);

		                                    if(str_starts_with($url, 'mailto:')) $style = 'link__icon link__icon--mail';
		                                    else if(str_starts_with($url, 'tel:')) $style = 'link__icon link__icon--tel';

		                                    ?>
                                            <a href="<?= esc_url($url) ?>" class="underline! <?= $style ?? '' ?> ">
                                                <?= esc_html( $label) ?>
                                            </a>
	                                    <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
	                <?php endforeach; ?>
                </div>

            <?php else: ?>
                <p>Nebyly zveřejněny žádné kontakty</p>
            <?php endif; ?>
        </div>
    </div>
</section>

