<?php

/***************************************************************
 * Layout automation for ACF's Flexible Content
 ***************************************************************/
require_once locate_template('lib/AcfLayout.php');


/***************************************************************
 * Includes
 ***************************************************************/
require_once locate_template('inc/image-sizes.php');
require_once locate_template('inc/custom-posts.php');
require_once locate_template('inc/tinymce-setting.php');
require_once locate_template('inc/scripts.php');
require_once locate_template('inc/helpers.php');
require_once locate_template('inc/shortcodes/icon-generator.php');
require_once locate_template('inc/listing.php');
require_once locate_template('inc/custom-functions.php');
require_once locate_template('inc/custom-login.php');
require_once locate_template('inc/revinate.php');
require_once locate_template('lib/AdvancedSearch.php');
require_once locate_template('lib/Events.php');


/***************************************************************
 * Content width
 ***************************************************************/
 //$content_width is a global variable used by WordPress for max image upload sizes and media embeds (in pixels).
if (!isset($content_width)) {
	$content_width = 2560;
}


/***************************************************************
 * Remove admin bar on live site to prevent being cached in cloudflare
 * This can be removed if you're not caching full html in cloudflare
 ***************************************************************/
//if( isset($_ENV) && isset($_ENV['VSRV_ENV']) && $_ENV['VSRV_ENV'] != 'DEV' ) {
//	show_admin_bar(false);
//}


/***************************************************************
 * Set default JPG image compression
 ***************************************************************/
function custom_jpg_compression($args) { return 80; }
add_filter('jpeg_quality', 'custom_jpg_compression');


/***************************************************************
 * Remove the main content from pages and posts because
 * we're using ACF
 ***************************************************************/
function ondesign_remove_editor() {
	remove_post_type_support( 'page', 'editor' );
	remove_post_type_support( 'post', 'editor' );
}
add_action('init', 'ondesign_remove_editor');


/***************************************************************
 * Register Menus
 ***************************************************************/
if ( function_exists('register_nav_menus') ) {
	function register_theme_menus() {
		register_nav_menus(
			array(
				'header_primary_nav' => __('Header Primary Nav'),
				'header_secondary_nav' => __('Header Secondary Nav'),
				'footer_primary_nav' => __( 'Footer Primary Nav' ),
				'footer_secondary_nav' => __( 'Footer Secondary Nav' ),
			)
		);
	}
	add_action( 'init', 'register_theme_menus' );
}


/***************************************************************
 * Disable tab index in gravity forms
 ***************************************************************/
add_filter( 'gform_tabindex', '__return_false' );


/***************************************************************
 * Removing unnecessary WP Head stuff
 * ref: https://bhoover.com/remove-unnecessary-code-from-your-wordpress-blog-header/
 ***************************************************************/
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_generator');


/***************************************************************
 * Removing emojis
 ***************************************************************/
function disable_wp_emojicons() {
	// all actions related to emojis
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	// filter to remove TinyMCE emojis
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );

	// Remove the DNS prefetch by returning false on filter emoji_svg_url
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}


/***************************************************************
* Disable WordPress Update Notifications and Plugin Update Notifications
***************************************************************/
//function remove_core_updates(){
//	global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
//}
//add_filter('pre_site_transient_update_core','remove_core_updates');
//add_filter('pre_site_transient_update_plugins','remove_core_updates');
//add_filter('pre_site_transient_update_themes','remove_core_updates');


/***************************************************************
* General Settings page
***************************************************************/
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' => 'General Settings Page',
		'menu_title' => 'General Settings',
		'menu_slug' => 'general-settings',
		'capability' => 'edit_posts',
		'redirect' => false
	));
}


/***************************************************************
* Add Options page to Admin bar
***************************************************************/
function ondesign_settings_menu_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->add_node(array(
		'id' => 'general-settings-options',
		'title' => 'General Settings',
		'href' => admin_url().'/admin.php?page=general-settings'
	));
}
add_action( 'wp_before_admin_bar_render', 'ondesign_settings_menu_admin_bar' );


/***************************************************************
* Remove Ancient Custom Fields metabox
* From : https://9seeds.com/2016/08/17/wordpress-admin-post-editor-performance/
***************************************************************/
function ondesign_remove_post_custom_fields_metabox() {
	foreach ( get_post_types( '', 'names' ) as $post_type ) {
		remove_meta_box( 'postcustom' , $post_type , 'normal' );
	}
}
add_action( 'admin_menu' , 'ondesign_remove_post_custom_fields_metabox' );


/***************************************************************
 * Disable aio seo plugin from adding schema data to the site
 * From : https://wordpress.org/support/topic/remove-schema-4/
 ***************************************************************/
add_filter( 'aioseo_schema_disable', 'aioseo_disable_schema' );
function aioseo_disable_schema( $disabled ) {
	return true;
}


/***************************************************************
 * CDN Cache Control
 ***************************************************************/
function cdn_cache_control() {
	$s_maxage = '1801'; // Adjust as needed
	$max_age = '907'; // Adjust as needed
	if (is_admin_bar_showing()) { // Don't Cache the Admin Bar
		header("Cache-control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
		header("Expires: Wed, 21 Oct 2015 07:28:00 GMT"); // Previous Date to Prompt Cache Expiration
		header("Pragma: no-cache");
	} else {
		header("Cache-control: public, s-maxage=".$s_maxage.", max-age=".$max_age);
		header("Expires: ".gmdate(DATE_RFC7231, strtotime("+ ".$max_age." seconds")));
	}
}
add_action('wp_headers', 'cdn_cache_control');


/***************************************************************
 * Add type module to js module imports
 * Done by giving the script a handle that includes keyword "-module" at the end
 * From : https://stackoverflow.com/questions/58931144/enqueue-javascript-with-type-module
 ***************************************************************/
function add_type_attribute($tag, $handle, $src) {
	// if not your script, do nothing and return original $tag
	if ( !strpos( $handle, '-module' ) ) {
		return $tag;
	}
	// change the script tag by adding type="module" and return it.
	$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
	return $tag;
}

add_filter('script_loader_tag', 'add_type_attribute', 10, 3);


/***************************************************************
 * Position All in One SEO meta box to the bottom
 ***************************************************************/
function aioseo_filter_metabox_priority($priority) {
	return 'low';
}
add_filter('aioseo_post_metabox_priority', 'aioseo_filter_metabox_priority');


/***************************************************************
 * Cache bust thumbnail crops made by the Crop Thumbnails plugin
 ***************************************************************/
add_filter( 'crop_thumbnails_filename', 'ondesign_crop_thumbnails_cache_bust', 10, 6 );
function ondesign_crop_thumbnails_cache_bust ( $destfilename, $file, $w, $h, $crop, $imageMetadata ) {
	$arr_destfilename = explode('.', $destfilename);
	$file_extension = array_pop($arr_destfilename);
	$new_destfilename = implode('.', $arr_destfilename);
	$new_destfilename .= '-' . time() . '.' . $file_extension;
	return $new_destfilename;
}


/***************************************************************
 * Remove featured image from pages
 ***************************************************************/
//function remove_thumbnail_box()
//{
//	remove_meta_box('postimagediv', 'page', 'side');
//}
//
//add_action('do_meta_boxes', 'remove_thumbnail_box');


/***************************************************************
 * Hide Site Health Widget
 ***************************************************************/
add_action('wp_dashboard_setup', 'remove_site_health_dashboard_widget' );
function remove_site_health_dashboard_widget() {
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
}

/***************************************************************
 * REQUIRED FOR ADVANCED SEARCH PHP
 * Uncomment if you are using using the Base Theme Site Search
 ***************************************************************/
// add_filter( 'redirect_canonical', 'custom_disable_redirect_canonical' );
// function custom_disable_redirect_canonical( $redirect_url ) {
//     if ( is_paged() && is_singular() ) $redirect_url = false;
//     return $redirect_url;
// }


/* ------ Booking Widget Shortcode ------ */
function booking_widget_shortcode($atts, $content): string
{
	$output = '';
	if (locate_template('templates/components/booking-widget.php')) {
		$output .= compileTemplate('booking-widget', array('position' => 'shortcode'), false, false);
	}
	return $output;
}

add_shortcode('booking-widget', 'booking_widget_shortcode');