<?php
/**
 * Handle the custom scripts
 *
 * Each javascript library that isn't used globally requires a
 * function made that can be registered from the layouts.
 *
 * wp_enqueue_style($handle, $src, $deps, $ver, $media);
 * wp_register_script($handle, $src, $deps, $ver, $in_footer);
 */

function base_scripts()
{
	// Remove Gutenberg Styles
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');

	// Remove wp-embed
	wp_deregister_script('wp-embed');

	// Upgrade JQuery
	wp_deregister_script('jquery');
	wp_register_script('jquery', get_template_directory_uri() . '/assets/js/jquery.js');

	//	Common JS
	wp_register_script('ondesign-common-js', get_template_directory_uri() . '/assets/js/common.js', ['jquery']);
	wp_enqueue_script('ondesign-common-js', ['jquery']);

	// Common styles
	wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.css');
	wp_enqueue_style('common-css', get_template_directory_uri() . '/assets/css/common.css', ['bootstrap-css']);

	// splide.js
	wp_enqueue_style('splide-css', get_template_directory_uri() . '/assets/css/splide.css', ['common-css']);
	wp_register_script('splide-js', get_template_directory_uri() . '/assets/js/splide.js');

	// EasePick
	wp_enqueue_style('easepick-css', get_template_directory_uri() . '/assets/css/easepick.css', ['common-css']);
	wp_enqueue_script('easepick-js', get_template_directory_uri() . '/assets/js/easepick.js');

	// ReModal
	wp_enqueue_style('remodal-css', get_template_directory_uri() . '/assets/css/remodal.css', ['common-css']);
	wp_enqueue_script('remodal-js', get_template_directory_uri() . '/assets/js/remodal.js');

	//	Example
	// Todo: Replace
	// wp_register_script('above-fold', get_template_directory_uri() . '/assets/js/above-fold.js');
	// wp_enqueue_script('above-fold');
}

add_action('wp_enqueue_scripts', 'base_scripts', 100);


// Custom Admin Styles
function admin_scripts()
{
	wp_enqueue_style('ondesign-admin-css', get_template_directory_uri() . '/assets/css/ondesign-admin.css');

}
add_action('admin_head', 'admin_scripts');