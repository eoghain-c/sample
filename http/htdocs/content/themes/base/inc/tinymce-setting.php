<?php

/***************************************************************
 * TinyMCE Custom Stylesheet
 ***************************************************************/
function tinyMCEStyle()
{
	add_editor_style('assets/css/tinymce.css');
}

add_action('after_setup_theme', 'tinyMCEStyle');


/***************************************************************
 * CUSTOM MCE SETTINGS - Custom Classes for Wordpress WYSIWYG
 ***************************************************************/
function wpb_mce_buttons_2($buttons)
{
	array_unshift($buttons, 'styleselect');

	return $buttons;
}

add_filter('mce_buttons_2', 'wpb_mce_buttons_2');


function mce_additional_formats($init_array)
{
	$style_formats = array(
		array(
			'title' => 'Headings',
			'items' => array(
				array(
					'title' => 'Heading 1',
					'selector' => '*',
					'classes' => 'heading-1',
				),
				array(
					'title' => 'Heading 2',
					'selector' => '*',
					'classes' => 'heading-2',
				),
				array(
					'title' => 'Heading 3',
					'selector' => '*',
					'classes' => 'heading-3',
				),
				array(
					'title' => 'Heading 4',
					'selector' => '*',
					'classes' => 'heading-4',
				),
				array(
					'title' => 'Title',
					'selector' => '*',
					'classes' => 'title',
				),
				array(
					'title' => 'Overline',
					'selector' => '*',
					'classes' => 'overline',
				),
			),
		),
		array(
			'title' => 'Links',
			'items' => array(
				array(
					'title' => 'Button (White)',
					'selector' => 'a',
					'classes' => 'link link__btn link__btn--white',
				),
				array(
					'title' => 'Button (Green)',
					'selector' => 'a',
					'classes' => 'link link__btn link__btn--green',
				),
				array(
					'title' => 'Text Link (Green)',
					'selector' => 'a',
					'classes' => 'link link__text link__text--green',
					'wrapper' => true, // Enable wrapping the link text
					'exact' => false, // Don't require exact match of selector
					'inline' => 'span', // Wrap with <span> tag
				),
				array(
					'title' => 'Text Link (Green - No Underline)',
					'selector' => 'a',
					'classes' => 'link link__text--noline link__text--green',
					'wrapper' => true, // Enable wrapping the link text
					'exact' => false, // Don't require exact match of selector
					'inline' => 'span', // Wrap with <span> tag
				),
				array(
					'title' => 'Booking',
					'selector' => 'a, button',
					'classes' => 'link link__btn js-booking-btn',
				),
				array(
					'title' => 'Revinate',
					'selector' => 'a',
					'classes' => 'link link__text--noline js-navis-number',
				),
			),
		),
		array(
			'title' => 'Paragraphs',
			'items' => array(
				array(
					'title' => 'Intro Bold',
					'selector' => 'p',
					'classes' => 'intro-bold',
				),
				array(
					'title' => 'Intro',
					'selector' => 'p',
					'classes' => 'intro',
				),
				array(
					'title' => 'Body 1',
					'selector' => 'p',
					'classes' => 'body-1',
				),
			),
		),

	);
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode($style_formats);


	/* Colors Menu
	***************************************************************/
	$custom_colours = '
		"585858", "Default Text",
        "FFFFFF", "White",
        "231F20", "Black",
        "004438", "Green",
    ';

	// build colour grid default+custom colors
	$init_array['textcolor_map'] = '[' . $custom_colours . ']';

	// change the number of rows in the grid if the number of colors changes
	// 8 swatches per row
	$init_array['textcolor_rows'] = 1;


	return $init_array;
}

// Attach callback to 'tiny_mce_before_init'
add_filter('tiny_mce_before_init', 'mce_additional_formats');