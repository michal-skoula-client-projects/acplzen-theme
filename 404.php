<?php
/**
 * 404 page template.
 *
 * @author Michal Škoula
 */

get_header();
?>

<section class="section">
    <div class="max-content-width">
        <div class="pt-28 pb-16">
            <img src="<?= get_template_directory_uri() . '/public/assets/mac_404.jpg' ?>" alt="" class="mx-auto mb-12">
            <h1 class="text-center mb-4 text-darkest">Error 404</h1>
            <div class="text-center">
                <p class="max-w-xl mx-auto text-lg text-dark mb-8">
                    Jejda! Tento odkaz jsme nenašli. Zkontrolujte, zda je zadaná adresa správně. Je-li toto chyba, můžete ji
                    <?= get_template_part('components/blocks/ac_link', args: [
                        'style' => 'dotted',
                        'color' => 'secondary',
                        'href' => ISSUE_REPORT_EMAIL,
                        'label' => 'nahlásit'
                    ]);?>.
                </p>
                <?= get_template_part('components/blocks/ac_button', args: [
                    'style' => 'primary',
                    'label' => 'Zpátky na homepage',
                    'href' => home_url()
                ])?>
            </div>

        </div>
    </div>
</section>

<?php
get_footer()
?>


