<?php

/*
 * Footer configuration page
 */


add_action('acf/init', function()
{
	acf_add_options_page([
		'page_title' => 'Footer',
		'menu_slug' => 'footer',
		'menu_title' => 'Footer',
		'position' => 151,
		'redirect' => false,
		'description' => 'Nastavení políček na zápatí. Např. odkazů na sociální sítě, rozcestníků atd.',
		'menu_icon' => [
			'type' => 'dashicons',
			'value' => 'dashicons-admin-generic',
		],
		'update_button' => 'Aktualizovat',
		'updated_message' => 'Zápatí aktualizováno',
		'icon_url' => 'dashicons-admin-generic',
	]);


	acf_add_local_field_group([
		'key' => 'group_688cad2fa113e',
		'title' => 'Zápatí',
		'fields' => [
			[
				'key' => 'field_688cade1c86be',
				'label' => 'Sloupce rozcestníku',
				'name' => 'footer_navigation',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Přidat sloupec',
				'sub_fields' => [
					[
						'key' => 'field_688cb0af9a650',
						'label' => 'Název sloupce',
						'name' => 'heading',
						'type' => 'text',
					],
					[
						'key' => 'field_688cae02c86bf',
						'label' => 'Řádky',
						'name' => 'rows',
						'type' => 'repeater',
						'layout' => 'block',
						'button_label' => 'Přidat odkaz',
						'sub_fields' => [
							[
								'key' => 'field_688cae20c86c0',
								'label' => 'Odkaz',
								'name' => 'entry',
								'type' => 'link',
								'return_format' => 'array',
							],
						],
					],
				],
			],
			[
				'key' => 'field_688cc077b1bc4',
				'label' => 'Základní informace',
				'name' => 'footer_general',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'field_688cc9cab1bc6',
						'label' => 'Titulek',
						'name' => 'title',
						'type' => 'text',
						'default_value' => '',
					],
					[
						'key' => 'field_688cd215994af',
						'label' => 'Motto',
						'name' => 'motto',
						'type' => 'wysiwyg',
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'footer',
				],
			],
		],
	]);
});