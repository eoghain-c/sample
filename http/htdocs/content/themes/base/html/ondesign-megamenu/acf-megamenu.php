<?php
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_5d7bae7d5bea0',
		'title' => 'ONDESIGN Megamenu',
		'fields' => array(
			array(
				'key' => 'field_64ef9cc9ae90b',
				'label' => 'Menu Type',
				'name' => 'menu_type',
				'aria-label' => '',
				'type' => 'button_group',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'mega' => 'Mega Menu',
					'single' => 'Single Link',
				),
				'default_value' => '',
				'return_format' => 'value',
				'allow_null' => 0,
				'layout' => 'horizontal',
			),
			array(
				'key' => 'field_64f0e8d4224a5',
				'label' => 'Menu Link',
				'name' => 'menu_link',
				'aria-label' => '',
				'type' => 'page_link',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_64ef9cc9ae90b',
							'operator' => '==',
							'value' => 'single',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => '',
				'post_status' => array(
					0 => 'publish',
				),
				'taxonomy' => '',
				'allow_archives' => 1,
				'multiple' => 0,
				'allow_null' => 0,
			),
			array(
				'key' => 'field_64ef9d3eae90d',
				'label' => 'Menu Items',
				'name' => 'menu_items',
				'aria-label' => '',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_64ef9cc9ae90b',
							'operator' => '==',
							'value' => 'mega',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'block',
				'pagination' => 0,
				'min' => 0,
				'max' => 0,
				'collapsed' => '',
				'button_label' => 'Add Row',
				'rows_per_page' => 20,
				'sub_fields' => array(
					array(
						'key' => 'field_64ef9de5ae910',
						'label' => 'Item Type',
						'name' => 'item_type',
						'aria-label' => '',
						'type' => 'button_group',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'card' => 'Card',
							'list' => 'List',
						),
						'default_value' => '',
						'return_format' => 'value',
						'allow_null' => 0,
						'layout' => 'horizontal',
						'parent_repeater' => 'field_64ef9d3eae90d',
					),
					array(
						'key' => 'field_64ef9dc9ae90f',
						'label' => 'Label',
						'name' => 'label',
						'aria-label' => '',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_64ef9de5ae910',
									'operator' => '==',
									'value' => 'card',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_64ef9d3eae90d',
					),
					array(
						'key' => 'field_64ef9d77ae90e',
						'label' => 'Media',
						'name' => 'media',
						'aria-label' => '',
						'type' => 'clone',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_64ef9de5ae910',
									'operator' => '==',
									'value' => 'card',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'clone' => array(
							0 => 'field_64dd0b3dd9c78',
						),
						'display' => 'group',
						'layout' => 'block',
						'prefix_label' => 0,
						'prefix_name' => 0,
						'parent_repeater' => 'field_64ef9d3eae90d',
					),
					array(
						'key' => 'field_64ef9f03ae912',
						'label' => 'Item Link',
						'name' => 'item_link',
						'aria-label' => '',
						'type' => 'clone',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_64ef9de5ae910',
									'operator' => '==',
									'value' => 'card',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'clone' => array(
							0 => 'group_591ee5bb9e564',
						),
						'display' => 'group',
						'layout' => 'block',
						'prefix_label' => 0,
						'prefix_name' => 0,
						'parent_repeater' => 'field_64ef9d3eae90d',
					),
					array(
						'key' => 'field_64ef9eaaae911',
						'label' => 'Item Links',
						'name' => 'item_links',
						'aria-label' => '',
						'type' => 'clone',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_64ef9de5ae910',
									'operator' => '==',
									'value' => 'list',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'clone' => array(
							0 => 'field_5c2e353dfa702',
						),
						'display' => 'group',
						'layout' => 'block',
						'prefix_label' => 0,
						'prefix_name' => 0,
						'parent_repeater' => 'field_64ef9d3eae90d',
					),
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'megamenu',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;
