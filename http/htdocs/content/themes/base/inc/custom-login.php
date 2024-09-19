<?php
// custom login stylesheet
function ondesign_custom_login()
{
	echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/custom-login.css" />';
}

add_action('login_head', 'ondesign_custom_login');

//customize login logo link
function ondesign_login_logo_url()
{
	return get_bloginfo('url');
}

add_filter('login_headerurl', 'ondesign_login_logo_url');

// customize login logo title text
function ondesign_login_logo_url_title()
{
	return 'Greenbrier Logo';
}

add_filter('login_headertitle', 'ondesign_login_logo_url_title');

// Load
function ondesign_admin_scripts()
{
	wp_enqueue_style('custom-login', get_template_directory_uri() . '/assets/css/custom-login.css');
}

add_action('admin_enqueue_scripts', 'ondesign_admin_scripts');
