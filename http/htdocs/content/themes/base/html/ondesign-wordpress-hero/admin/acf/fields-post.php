<?php
/**
 * Adds the hero fields on the hero post type
 */
add_action( 'init', function() {

	// Format the location array based on supported post types
	$supported_pts = get_field('supported_post_types', 'options');
	$supported_taxes = get_field('supported_taxonomies', 'options');
	$location = array();

	// Add fields to supported post types
	if ( !empty($supported_pts) ) {
		foreach( $supported_pts as $pt ) {
			$location[] = array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => $pt
				)
			);
		}
	}

	// Add fields to supported taxonomies
	if ( !empty($supported_taxes) ) {
		foreach( $supported_taxes as $tax ) {
			$location[] = array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => $tax
				)
			);
		}
	}

	// If location still empty, add fields to just the page post type by default
	if ( empty($location) ) {
		$location[] = array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page'
			)
		);
	}

	// Get the hero size options
	$hero_sizes           = get_field('hero_sizes', 'options');
	$content_pos_enabled  = get_field('hero_content_position_enabled', 'options');
	$content_pos_field    = null;

	// Get how to display the content position field
	// @since 2.4.0
	if ( get_field('hero_mode', 'options') == 'headless' ) {

		// Option to omit the content position field
		// @since 3.0.0
		if ( $content_pos_enabled ) {
			$content_pos_field = [
				'key' => 'content_position',
				'label' => 'Content Position',
				'name' => 'content_position',
				'graphql_field_name' => 'contentPosition',
				'instructions' => 'Select where the content should be positioned over the hero',
				'show_in_graphql' => 1,
				'type' => 'select',
				'choices' => [
					'top-left' => 'Top Left',
					'top-center' => 'Top Center',
					'top-right' => 'Top Right',
					'middle-left' => 'Middle Left',
					'middle-center' => 'Middle Center',
					'middle-right' => 'Middle Right',
					'bottom-left' => 'Bottom Left',
					'bottom-middle' => 'Bottom Middle',
					'bottom-right' => 'Bottom Right'
				],
				'default_value' => 'middle-center',
				'wrapper' => array(
					'width' => 50
				),
			];
		}

		$content_field = array(
			'key' => 'field_60a2a0beda647',
			'label' => 'Hero Content',
			'name' => 'hero_content',
			'graphql_field_name' => 'content',
			'show_in_graphql' => 1,
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => $content_pos_enabled ? '50' : '100',
				'class' => '',
				'id' => '',
			),
			'show_in_graphql' => 1,
			'layout' => 'block',
			'sub_fields' => array(
				array(
					'key' => 'field_60a2a0d1da648',
					'label' => 'Title',
					'name' => 'title',
					'graphql_field_name' => 'title',
					'show_in_graphql' => 1,
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'show_in_graphql' => 1,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_60a2a0d5da649',
					'label' => 'Sub Title',
					'name' => 'sub_title',
					'graphql_field_name' => 'subTitle',
					'show_in_graphql' => 1,
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'show_in_graphql' => 1,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_60a2a8b89d4ea',
					'label' => 'Button',
					'name' => 'button',
					'show_in_graphql' => 1,
					'type' => 'clone',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'clone' => array(
						0 => 'group_5d39b2a03d810',
					),
					'display' => 'seamless',
					'layout' => 'block',
					'prefix_label' => 0,
					'prefix_name' => 0,
				),
			),
		);

	} else {

		// Option to omit the content position field
		// @since 3.0.0
		if ( $content_pos_enabled ) {
			$content_pos_field = [
				'key' => 'content_postition',
				'label' => 'Content Position',
				'name' => 'content_position',
				'graphql_field_name' => 'contentPosition',
				'show_in_graphql' => 0,
				'type' => 'content_position',
				'position_type' => 'content',
				'instructions' => 'Click the mini box below where you want to position the content.',
				'wrapper' => array(
					'width' => 40
				)
			];
		}

		$content_field = [
			'key' => 'item_content',
			'label' => 'Item Content',
			'name' => 'item_content',
			'graphql_field_name' => 'content',
			'show_in_graphql' => 1,
			'instructions' => 'Be mindful of how much text is added here, there is only room for a small amount on mobile devices. suggested maximum 40 characters',
			'type' => 'wysiwyg',
			'media_upload' => false,
			// 'toolbar' => 'basic',
			'required' => false,
			// 'tabs' => 'visual',
			'wrapper' => array(
				'width' => $content_pos_enabled ? 60 : 100
			)
		];

	}


	if ( !empty($hero_sizes) && is_array($hero_sizes) ) {

		// Create the select choices based the settings
		$hero_select_choices = array();
		foreach( $hero_sizes as $size ) {
			$hero_select_choices[strtolower(str_replace( ' ', '-', $size['size_name']))] = $size['size_name'];
		}
		$hero_select_choices['full'] = 'Full Screen';

	}
	else {

		// default choices
		$hero_select_choices = array(
			'medium' => 'Medium',
			'large' => 'Large',
			'full' => 'Full Screen'
		);

	}

	$hero_items_fg = array (
		'key' => 'hero_fields',
		'title' => 'Hero Items',
		'menu_order' => -1,
		'location' => $location,
		'show_in_graphql' => 1,
		'graphql_field_name' => 'hero',
		'map_graphql_types_from_location_rules' => 0,
		'graphql_types' => '',
		'fields' => array(
			array (
				'key' => 'hero_size',
				'label' => 'Hero Size',
				'name' => 'hero_size',
				'show_in_graphql' => 1,
				'type' => 'select',
				'choices' => $hero_select_choices,
				'default_value' => 'hero-large',
				'wrapper' => array(
					'width' => 25
				),
			),
			array (
				'key' => 'hero_widget',
				'label' => 'Hero Widget',
				'name' => 'hero_widget',
				'type' => 'button_group',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => 25,
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'choices' => array(
					'none' => 'None',
					'booking' => 'Booking',
				),
				'default_value' => 'none',
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array (
				'key' => 'hero_icon',
				'label' => 'Hero Icon',
				'name' => 'hero_icon',
				'type' => 'button_group',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => 25,
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'choices' => array(
					'none' => 'None',
					'icon' => 'Icon',
				),
				'default_value' => 'none',
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array (
				'key' => 'gradient_overlay',
				'label' => 'Gradient Overlay',
				'name' => 'gradient_overlay',
				'type' => 'button_group',
				'wrapper' => array(
					'width' => 25
				),
				'choices' => array(
					'top' => 'Top',
					'bottom' => 'Bottom',
					'both' => 'Both',
					'none' => 'None'
				),
				'default_value' => "both",
			),
			array(
				'key' => 'hero_items',
				'button_label' => 'Add Item',
				'label' => 'Hero Items',
				'name' => 'hero_items',
				'show_in_graphql' => 1,
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'layout' => 'block',
				'sub_fields' => array (
					array (
						'key' => 'background_type',
						'label' => 'Background Type',
						'name' => 'background_type',
						'graphql_field_name' => 'backgroundType',
						'show_in_graphql' => 1,
						'type' => 'select',
						'choices' => array(
							'image' => 'Image',
							'video' => 'Video'
						),
						'wrapper' => array(
							'width' => ''
						)
					),
					array (
						'label' => 'Video (mp4)',
						'key' => 'background_video_mp4',
						'name' => 'background_video_mp4',
						'graphql_field_name' => 'videoUrl',
						'show_in_graphql' => 1,
						'type' => 'file',
						'required' => true,
						'placeholder' => '',
						'wrapper' => array(
							'width' => 33
						),
						'conditional_logic' => array (
							array (
								array (
									'field' => 'background_type',
									'operator' => '==',
									'value' => 'video',
								)
							)
						)
					),
					array (
						'key' => 'video_fallback_image',
						'label' => 'Video Fallback Image',
						'name' => 'video_fallback_image',
						'graphql_field_name' => 'videoFallbackImage',
						'show_in_graphql' => 1,
						'type' => 'image',
						'required' => true,
						'return_format' => 'array',
						'instructions' => 'If the video is unable to play, this image will display instead of the video.',
						'wrapper' => array(
							'width' => 33
						),
						'conditional_logic' => array (
							array (
								array (
									'field' => 'background_type',
									'operator' => '==',
									'value' => 'video',
								)
							)
						)
					),
					array(
						'key' => 'show_controls',
						'label' => 'Show Video Controls',
						'name' => 'show_controls',
						'aria-label' => '',
						'type' => 'true_false',
						'instructions' => 'Select whether to display the video media controls',
						'required' => 0,
						'wrapper' => array(
							'width' => 33,
						),
						'message' => '',
						'default_value' => 0,
						'ui_on_text' => '',
						'ui_off_text' => '',
						'ui' => 1,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'background_type',
									'operator' => '==',
									'value' => 'video',
								)
							)
						)
					),
					array (
						'key' => 'hero_image',
						'label' => 'Hero Image',
						'name' => 'hero_image',
						'show_in_graphql' => 1,
						'graphql_field_name' => 'heroImage',
						'type' => 'image',
						'required' => false,
						'return_format' => 'array',
						'instructions' => 'The Featured Image will be used if this image if is left blank.',
						'wrapper' => array (
							'width' => '35',
							'class' => '',
							'id' => '',
						),
						'conditional_logic' => array (
							array (
								array (
									'field' => 'background_type',
									'operator' => '==',
									'value' => 'image',
								)
							)
						)
					),
					array (
						'key' => 'hero_image_center_point',
						'label' => 'Hero Image Center Point',
						'name' => 'hero_image_center_point',
						'graphql_field_name' => 'imageCenterPoint',
						'show_in_graphql' => 1,
						'type' => 'range',
						'instructions' => 'Set the horizontal focus point on the image.',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'background_type',
									'operator' => '==',
									'value' => 'image',
								)
							)
						),
						'wrapper' => array (
							'width' => '65',
							'class' => '',
							'id' => '',
						),
						'default_value' => 50,
						'min' => '',
						'max' => '',
						'step' => '',
						'prepend' => '',
						'append' => '',
					),
					$content_field,
					$content_pos_field
				)
			)
		)
	);

	// Remove the show overlay optin if there is no overlay image set in the options
	if ( ! get_field('enable_overlay_images', 'options') ) {
		for( $i = 0; $i < count($hero_items_fg['fields']); $i++ ) {
			if ( $hero_items_fg['fields'][$i]['name'] == 'show_overlay_image' ) {
				unset($hero_items_fg['fields'][$i]);
			}
		}
	}

	/**
	 * Ability to customize custom fields on the front end via action hooks
	 * This way you can add custom fields without needing to modify the plugin
	 *
	 * @since 1.0.1
	 */
	$hero_items_fg = apply_filters( 'ondesign_hero_fields', $hero_items_fg );


	acf_add_local_field_group($hero_items_fg);


});
