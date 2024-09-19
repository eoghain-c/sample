<?php


// Want to change this file?
// Create the files in [theme]/html/ondesign-wordpress-hero/admin/acf/fields-settings.php
// then copy the contents of this file in there and make your changed!



if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_59485d0a1cd6e',
		'title' => 'Hero Settings',
		'fields' => array (

			array (
				'key' => 'hero_mode',
				'label' => 'Hero Mode',
				'name' => 'hero_mode',
				'instructions' => 'With headless mode it will dumbdown some of the hero ACF fields to allow it to work with GraphQL.',
				'show_in_graphql' => 0,
				'type' => 'select',
				'choices' => [
					'regular' => 'Regular',
					'headless' => 'Headless'
				],
				'default_value' => 'regular',
				'wrapper' => array(
					'width' => 100
				),
			),
			array (
				'key' => 'field_59485c8d24768',
				'label' => 'Supported Post Types',
				'name' => 'supported_post_types',
				'type' => 'post_type_checkboxes',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'default' => 'page',
				'wrapper' => array (
					'width' => '25',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'supported_taxonomies',
				'label' => 'Supported Taxonomies',
				'name' => 'supported_taxonomies',
				'type' => 'taxonomy_checkboxes',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'default' => 'page',
				'wrapper' => array (
					'width' => '25',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'blog_settings',
				'label' => 'Blog Settings',
				'name' => 'blog_settings',
				'type' => 'checkbox',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				// 'default' => '',
				'choices' => array (
					'apply_to_archives' => 'Apply posts page hero to the blog archives',
					'apply_to_singles' => 'Apply posts page hero to individual post pages'
				),
				'wrapper' => array (
					'width' => '25',
					'class' => '',
					'id' => '',
				),
			),
			array(
				'key' => 'field_608bf9cc77aa3',
				'label' => 'Custom Blog Page',
				'name' => 'hero_blog_page',
				'type' => 'post_object',
				'instructions' => 'If you use a normal WP page for the blog, select it here. This is where the blog heros will pull from.',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'blog_settings',
							'operator' => '!=empty',
						)
					)
				),
				'wrapper' => array(
					'width' => '25',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'page',
				),
				'taxonomy' => '',
				'allow_null' => 1,
				'multiple' => 0,
				'return_format' => 'id',
				'ui' => 1,
			),
			array (
				'key' => 'preloader_colour',
				'label' => 'Preloader Colour',
				'name' => 'preloader_colour',
				'type' => 'color_picker',
				'instructions' => 'The colour that will display while the video is buffering for initial playback.',
				'required' => 0,
				'conditional_logic' => 0,
				'default' => '',
				'wrapper' => array (
					'width' => '40',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'preloader_opacity',
				'label' => 'Preloader Opacity',
				'name' => 'preloader_opacity',
				'min' => 0,
				'max' => 100,
				'type' => 'number',
				'instructions' => 'Opacity of the preload spinner.',
				'required' => 0,
				'conditional_logic' => 0,
				'default' => '',
				'wrapper' => array (
					'width' => '20',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'auto_slide_video',
				'label' => 'Auto Slide Video',
				'name' => 'auto_slide_video',
				'type' => 'true_false',
				'message' => 'Automatically go to next slider after video plays',
				'required' => 0,
				'conditional_logic' => 0,
				'default' => '',
				'wrapper' => array (
					'width' => '100',
					'class' => '',
					'id' => '',
				),
			),
			array (
				'key' => 'hero_content_position_enabled',
				'label' => 'Content Position Field',
				'name' => 'hero_content_position_enabled',
				'type' => 'true_false',
				'message' => 'Enabled',
				'required' => 0,
				'conditional_logic' => 0,
				'default' => '',
				'wrapper' => array (
					'width' => '50',
					'class' => '',
					'id' => '',
				),
			),
			array(
				'key' => 'hero_enable_mobile_video',
				'label' => 'Enable Mobile Video',
				'name' => 'enable_mobile_video',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'hero-settings',
				),
			),
		),
		'menu_order' => 2,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));




	acf_add_local_field_group(array (
		'key' => 'sizes_and_breakpoints',
		'title' => 'Images Sizes and Breakpoints',
		'fields' => array (
			array(
				'min' => 0,
				'max' => 5,
				'layout' => 'block',
				'button_label' => 'Add Size',
				'collapsed' => '',
				'key' => 'field_59485d87c727a',
				'label' => 'Hero Sizes',
				'name' => 'hero_sizes',
				'type' => 'repeater',
				'instructions' => 'Using the settings in this field, a new image size will be added for each breakpoint, and each size you add will be a size option when adding heros on pages. A full screen option will be added by default.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'sub_fields' => array (
					array (
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => 'Small',
						'prepend' => '',
						'append' => '',
						'key' => 'field_59485da5c727b',
						'label' => 'Size Name',
						'name' => 'size_name',
						'type' => 'text',
						'instructions' => 'The thumbnail size AND CSS class will become the size name with dashes instead of spaces.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '100',
							'class' => '',
							'id' => '',
						),
					),
					array (
						'min' => 0,
						'max' => 5,
						'layout' => 'block',
						'button_label' => 'Add Breakpoint',
						'collapsed' => '',
						'key' => 'breakpoints',
						'label' => 'Breakpoints',
						'name' => 'breakpoints',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'sub_fields' => array (
							array (
								'default_value' => '',
								'maxlength' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => 'px',
								'key' => 'min_width',
								'label' => 'Min Width',
								'name' => 'min_width',
								'type' => 'number',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '15',
									'class' => '',
									'id' => '',
								),
							),
							array (
								'default_value' => '',
								'min' => 0,
								'max' => '',
								'maxlength' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => 'px',
								'key' => 'image_width',
								'label' => 'Image Width',
								'name' => 'image_width',
								'type' => 'number',
								'message' => 'Yes',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '15',
									'class' => '',
									'id' => '',
								),
							),
							array (
								'default_value' => '',
								'min' => 0,
								'max' => '',
								'step' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => 'px',
								'key' => 'image_height',
								'label' => 'Image Height',
								'name' => 'image_height',
								'type' => 'number',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '15',
									'class' => '',
									'id' => '',
								)
							)
						),
					),
				),
			),
			array (
				'min' => 0,
				'max' => 5,
				'layout' => 'block',
				'button_label' => 'Add Breakpoint',
				'collapsed' => '',
				'key' => 'fullscreen_breakpoints',
				'label' => 'Full Screen Breakpoints',
				'name' => 'fullscreen_breakpoints',
				'type' => 'repeater',
				'instructions' => 'Breakpoints for the fullscreen size.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'sub_fields' => array (
					array (
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => 'px',
						'key' => 'min_width',
						'label' => 'Min Width',
						'name' => 'min_width',
						'type' => 'number',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '15',
							'class' => '',
							'id' => '',
						),
					),
					array (
						'default_value' => '',
						'min' => 0,
						'max' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => 'px',
						'key' => 'image_width',
						'label' => 'Image Width',
						'name' => 'image_width',
						'type' => 'number',
						'message' => 'Yes',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '15',
							'class' => '',
							'id' => '',
						),
					),
					array (
						'default_value' => '',
						'min' => 0,
						'max' => '',
						'step' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => 'px',
						'key' => 'image_height',
						'label' => 'Image Height',
						'name' => 'image_height',
						'type' => 'number',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '15',
							'class' => '',
							'id' => '',
						)
					)
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'hero-settings',
				),
			),
		),
		'menu_order' => 2,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));




	acf_add_local_field_group(array (
		'key' => 'default_images',
		'title' => 'Image Defaults',
		'fields' => array (
			array (
				'key' => 'allow_empty_heros',
				'label' => '',
				'name' => 'allow_empty_heros',
				'type' => 'true_false',
				'message' => 'Allow empty heroes',
				'instructions' => 'If checked, pages will be able to have no hero. Otherwise, it will fall back to the default image configured below.',
				'default_value' => true,
				'wrapper' => array(
					'width' => 100
				),
			),
			array (
				'key' => 'default_hero_size_class',
				'label' => 'Default Hero Size',
				'name' => 'default_hero_size_class',
				'instructions' => 'This should be an exact match of one of the hero sizes defined above.',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Large',
				'required' => 1
			),
			array (
				'key' => 'hero_default_image',
				'label' => 'Default Image',
				'name' => 'ondesign_hero_default_image',
				'type' => 'image',
				'instructions' => 'Will display if no heroes are set on the individual page.',
				'required' => 1,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'allow_empty_heros',
							'operator' => '!=',
							'value' => '1',
						)
					)
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'hero-settings',
				),
			),
		),
		'menu_order' => 5,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));


	acf_add_local_field_group(array (
		'key' => 'page_not_found_hero_group',
		'title' => '404 Page Hero',
		'fields' => array (
			array (
				'key' => 'page_not_found_hero',
				'label' => '404 Page Hero',
				'name' => 'page_not_found_hero',
				'type' => 'clone',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'clone' => array (
					0 => 'hero_fields',
				),
				'display' => 'group',
				'layout' => 'block',
				'prefix_label' => 1,
				'prefix_name' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'hero-settings',
				),
			),
		),
		'menu_order' => 3,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

	acf_add_local_field_group(array (
		'key' => 'search_hero',
		'title' => 'Search Page Hero',
		'fields' => array (
			array (
				'key' => 'field_5de71ad42b6b9',
				'label' => 'Search Page Hero',
				'name' => 'search_page_hero',
				'type' => 'clone',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'clone' => array (
					0 => 'hero_fields',
				),
				'display' => 'group',
				'layout' => 'block',
				'prefix_label' => 1,
				'prefix_name' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'hero-settings',
				),
			),
		),
		'menu_order' => 4,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;
