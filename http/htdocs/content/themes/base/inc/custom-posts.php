<?php

/***************************************************************
 * Accommodations Post Type
 ***************************************************************/
function register_accommodations() {
	$plural = "Accommodations";
	$singular = "Accommodation";
	
	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','thumbnail','revisions','custom_fields'),
		'rewrite'             => array('slug' => 'accommodations', 'with_front' => false),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-building',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true
	);
	register_post_type( 'accommodations' , $args );
}
add_action('init', 'register_accommodations');

/***************************************************************
 * Accommodations Taxonomy - Accommodation Type
 ***************************************************************/
function create_accommodations_type() {
	$plural = "Accommodation Types";
	$singular = "Accommodation Type";
	
	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('accommodations_type', 'accommodations', $args);
}
add_action( 'init', 'create_accommodations_type', 0 );

/***************************************************************
 * Accommodations Taxonomy - Amenity
 ***************************************************************/
function create_room_amenity_taxonomy() {
	$plural = "Amenities";
	$singular = "Amenity";

	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('accommodations_amenity', 'accommodations', $args);
}
add_action( 'init', 'create_room_amenity_taxonomy', 0 );

/***************************************************************
 * Accordion Post Type
 ***************************************************************/
function register_accordion_post_type() {
	$plural = "Accordions";
	$singular = "Accordion";

	$labels = array(
		'name'               => __( "{$plural}" ),
		'menu_name'          => __( "{$plural}" ),
		'singular_name'      => __( "{$singular}" ),
		'add_new_item'       => __( "Add New {$singular}" ),
		'edit_item'          => __( "Edit {$singular}" ),
		'new_item'           => __( "New {$singular}" ),
		'view_item'          => __( "View {$singular}" ),
		'search_items'       => __( "Search {$plural}" ),
		'not_found'          => __( "No {$plural} found" ),
		'not_found_in_trash' => __( "No {$plural} found in Trash" ),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions' ),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-format-status',
		'hierarchical'        => false,
		'public'              => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => false
	);
	register_post_type('accordion', $args);
}
add_action('init', 'register_accordion_post_type');

/***************************************************************
 * Accordion Category
 ***************************************************************/
function create_accordion_category() {
	$plural = "Categories";
	$singular = "Category";
	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('accordion_category', 'accordion', $args);
}
add_action( 'init', 'create_accordion_category', 0 );




/***************************************************************
 * Activities Post Type
 ***************************************************************/
function register_activities() {
	$plural = "Activities";
	$singular = "Activity";

	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','thumbnail','revisions','custom_fields'),
		'rewrite'             => array('slug' => 'activities', 'with_front' => false),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-car',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true
	);
	register_post_type( 'activities' , $args );
}
add_action('init', 'register_activities');

/***************************************************************
 * Activities Taxonomy - Category
 ***************************************************************/
function create_activities_category() {
	$plural = "Categories";
	$singular = "Category";

	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('activities_category', 'activities', $args);
}
add_action( 'init', 'create_activities_category', 0 );


/***************************************************************
 * Courses Post Type
 ***************************************************************/
function register_courses() {
	$plural = "Courses";
	$singular = "Course";

	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','thumbnail','revisions','custom_fields'),
		'rewrite'             => array('slug' => 'golf/courses-at-the-greenbrier', 'with_front' => false),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-palmtree',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true
	);
	register_post_type( 'courses' , $args );
}
add_action('init', 'register_courses');


/***************************************************************
 * Alert Banner Post Type
 ***************************************************************/
function register_alert_banner() {
	$plural = "Alert Banners";
	$singular = "Alert Banner";

	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','revisions','custom_fields'),
		'rewrite'             => array( 'slug' => 'alerts', 'with_front' => false ),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-megaphone', // http://calebserna.com/dashicons-cheatsheet/
		'hierarchical'        => false,
		'public'              => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true,
	);
	register_post_type('alert_banner', $args);
}
add_action( 'init', 'register_alert_banner', 0 );

/***************************************************************
 * Map Marker Post Type
 ***************************************************************/
function register_map_marker() {
	$plural = "Map Markers";
	$singular = "Map Marker";

	$labels = array(
		'name'               => __( "{$plural}" ),
		'menu_name'          => __( "{$plural}" ),
		'singular_name'      => __( "{$singular}" ),
		'add_new_item'       => __( "Add New {$singular}" ),
		'edit_item'          => __( "Edit {$singular}" ),
		'new_item'           => __( "New {$singular}" ),
		'view_item'          => __( "View {$singular}" ),
		'search_items'       => __( "Search {$plural}" ),
		'not_found'          => __( "No {$plural} found" ),
		'not_found_in_trash' => __( "No {$plural} found in Trash" ),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title','revisions', 'custom_fields' ),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-admin-site-alt', // https://developer.wordpress.org/resource/dashicons/
		'hierarchical'        => false,
		'public'              => false,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => false,
	);
	register_post_type('map_marker', $args);
}
add_action('init', 'register_map_marker');

/***************************************************************
 * Map Marker Category
 ***************************************************************/
function register_map_marker_category() {
	$plural = "Marker Types";
	$singular = "Marker Type";
	$labels = array(
		'name'              => _x( "{$plural}", 'taxonomy general name' ),
		'singular_name'     => _x( "{$singular}", 'taxonomy singular name' ),
		'search_items'      => __( "Search {$plural}" ),
		'all_items'         => __( "All {$plural}" ),
		'parent_item'       => __( "Parent {$singular}" ),
		'parent_item_colon' => __( "Parent {$singular}:" ),
		'edit_item'         => __( "Edit {$singular}" ),
		'update_item'       => __( "Update {$singular}" ),
		'add_new_item'      => __( "Add New {$singular}" ),
		'new_item_name'     => __( "New {$singular} Name" ),
		'menu_name'         => __( "{$plural}" ),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('map_marker_category', 'map_marker', $args );
}
add_action('init', 'register_map_marker_category', 0);


/***************************************************************
 * Offers Post Type
 ***************************************************************/
function register_offers() {
	$plural = "Offers";
	$singular = "Offer";
	
	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','thumbnail','revisions','custom_fields'),
		'rewrite'             => array('slug' => 'offers', 'with_front' => false),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-tickets-alt',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true
	);
	register_post_type( 'offers' , $args );
}
add_action('init', 'register_offers');

/***************************************************************
 * Offers Taxonomy - Offer Type
 ***************************************************************/
function create_offers_type_taxonomy() {
	$plural = "Offer Types";
	$singular = "Offer Type";
	
	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('offers_type', 'offers', $args);
}
add_action( 'init', 'create_offers_type_taxonomy', 0 );

/***************************************************************
 * Galleries Post Type
 ***************************************************************/
function register_galleries() {
	$plural = "Galleries";
	$singular = "Gallery";

	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','revisions','custom_fields'),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-images-alt',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => array('slug' => 'galleries', 'with_front' => true),
	);
	register_post_type( 'galleries' , $args );
}
add_action('init', 'register_galleries');


/***************************************************************
 * Restaurants Post Type
 ***************************************************************/
function register_restaurants() {
	$plural = "Restaurants";
	$singular = "Restaurant";

	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','thumbnail','revisions','custom_fields'),
		'rewrite'             => array('slug' => 'dining', 'with_front' => false),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-food',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true
	);
	register_post_type( 'restaurants' , $args );
}
add_action('init', 'register_restaurants');

/***************************************************************
 * Restaurant Taxonomy - Time of Day
 ***************************************************************/
function create_restaurants_time_of_day_taxonomy() {
	$plural = "Times of Day";
	$singular = "Time of Day";

	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('restaurants_time_of_day', 'restaurants', $args);
}
add_action( 'init', 'create_restaurants_time_of_day_taxonomy', 0 );

/***************************************************************
 * Shops Post Type
 ***************************************************************/
function register_shops() {
	$plural = "Shops";
	$singular = "Shop";

	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','thumbnail','revisions','custom_fields'),
		'rewrite'             => array('slug' => 'shopping', 'with_front' => false),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-products',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true
	);
	register_post_type( 'shops' , $args );
}
add_action('init', 'register_shops');

/***************************************************************
 * Shops Taxonomy - Audience
 ***************************************************************/
function create_shops_audience_taxonomy() {
	$plural = "Audiences";
	$singular = "Audience";

	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('shops_audience', 'shops', $args);
}
add_action( 'init', 'create_shops_audience_taxonomy', 0 );

/***************************************************************
 * Venues Post Type
 ***************************************************************/
function register_venues() {
	$plural = "Venues";
	$singular = "Venue";

	$labels = array(
		'name'               => __("{$plural}"),
		'menu_name'          => __("{$plural}"),
		'singular_name'      => __("{$singular}"),
		'add_new_item'       => __("Add New {$singular}"),
		'edit_item'          => __("Edit {$singular}"),
		'new_item'           => __("New {$singular}"),
		'view_item'          => __("View {$singular}"),
		'search_items'       => __("Search {$plural}"),
		'not_found'          => __("No {$plural} found"),
		'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
		'labels'              => $labels,
		'supports'            => array('title','thumbnail','revisions','custom_fields'),
		'rewrite'             => array('slug' => 'venues', 'with_front' => false),
		'capability_type'     => 'post',
		'menu_position'       => 20, // after Pages
		'menu_icon'           => 'dashicons-store',
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'can_export'          => true
	);
	register_post_type( 'venues' , $args );
}
add_action('init', 'register_venues');

/***************************************************************
 * Venues Taxonomy - Venue Type
 ***************************************************************/
function create_venues_type_taxonomy() {
	$plural = "Venue Types";
	$singular = "Venue Type";

	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('venues_type', 'venues', $args);
}
add_action( 'init', 'create_venues_type_taxonomy', 0 );

/***************************************************************
 * Venues Taxonomy - Location
 ***************************************************************/
function create_venues_location_taxonomy() {
	$plural = "Locations";
	$singular = "Location";

	$labels = array(
		'name'              => _x("{$plural}", 'taxonomy general name'),
		'singular_name'     => _x("{$singular}", 'taxonomy singular name'),
		'search_items'      => __("Search {$plural}"),
		'all_items'         => __("All {$plural}"),
		'parent_item'       => __("Parent {$singular}"),
		'parent_item_colon' => __("Parent {$singular}:"),
		'edit_item'         => __("Edit {$singular}"),
		'update_item'       => __("Update {$singular}"),
		'add_new_item'      => __("Add New {$singular}"),
		'new_item_name'     => __("New {$singular} Name"),
		'menu_name'         => __("{$plural}"),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => false
	);
	register_taxonomy('venues_location', 'venues', $args);
}
add_action( 'init', 'create_venues_location_taxonomy', 0 );

/***************************************************************
 * Event Types Taxonomy
 ***************************************************************/
function register_event_types() {
	$plural = "Event Types";
	$singular = "Event Type";
	$labels = array(
		'name'              => _x( "{$plural}", 'taxonomy general name' ),
		'singular_name'     => _x( "{$singular}", 'taxonomy singular name' ),
		'search_items'      => __( "Search {$plural}" ),
		'all_items'         => __( "All {$plural}" ),
		'parent_item'       => __( "Parent {$singular}" ),
		'parent_item_colon' => __( "Parent {$singular}:" ),
		'edit_item'         => __( "Edit {$singular}" ),
		'update_item'       => __( "Update {$singular}" ),
		'add_new_item'      => __( "Add New {$singular}" ),
		'new_item_name'     => __( "New {$singular} Name" ),
		'menu_name'         => __( "{$plural}" ),
	);
	
	register_taxonomy('event_types', array( 'events' ), array(
		'public' => true,
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => false,
		'capabilities'      => array(
			'assign_terms' => 'manage_options',
			'manage_terms' => 'administrator',
		)
	));
}
add_action( 'init', 'register_event_types', 0 );

/***************************************************************
 * Event Locations Taxonomy
 ***************************************************************/
function register_event_locations() {
	$plural = "Event Times";
	$singular = "Event Time";
	$labels = array(
		'name'              => _x( "{$plural}", 'taxonomy general name' ),
		'singular_name'     => _x( "{$singular}", 'taxonomy singular name' ),
		'search_items'      => __( "Search {$plural}" ),
		'all_items'         => __( "All {$plural}" ),
		'parent_item'       => __( "Parent {$singular}" ),
		'parent_item_colon' => __( "Parent {$singular}:" ),
		'edit_item'         => __( "Edit {$singular}" ),
		'update_item'       => __( "Update {$singular}" ),
		'add_new_item'      => __( "Add New {$singular}" ),
		'new_item_name'     => __( "New {$singular} Name" ),
		'menu_name'         => __( "{$plural}" ),
	);
	
	register_taxonomy('event_locations', array( 'events' ), array(
		'public' => true,
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => false,
		'capabilities'      => array(
			'assign_terms' => 'manage_options',
			'manage_terms' => 'administrator',
		)
	));
}
add_action( 'init', 'register_event_locations', 0 );

/*****************************************************
 * Menus
 ****************************************************/
function register_menus(){

	$title = "Menus";
	$plural = "Menus";
	$singular = "Menu";

	$labels = array(
			'name'               => __("{$title}"),
			'menu_name'          => __("{$title}"),
			'singular_name'      => __("{$title}"),
			'add_new_item'       => __("Add New {$singular}"),
			'edit_item'          => __("Edit {$singular}"),
			'new_item'           => __("New {$singular}"),
			'view_item'          => __("View {$singular}"),
			'search_items'       => __("Search {$plural}"),
			'not_found'          => __("No {$plural} found"),
			'not_found_in_trash' => __("No {$plural} found in Trash"),
	);
	$args = array(
			'labels' => $labels,
			'supports' => array('title', 'thumbnail'),
			'rewrite' => array('slug' => 'menu', 'with_front' => false),
			'capability_type' => 'post',
			'menu_position' => 20, // after Pages
			'menu_icon' => 'dashicons-clipboard',
			'hierarchical' => false,
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'can_export' => true,
	);
	register_post_type('menus', $args);
}
add_action('init', 'register_menus');

function register_events()
{

	$labels = array(
			'name'               => __('Events'),
			'menu_name'          => __('Events'),
			'singular_name'      => __('Event'),
			'add_new_item'       => __('Add New Event'),
			'edit_item'          => __('Edit Event'),
			'new_item'           => __('New Event'),
			'view_item'          => __('View Event'),
			'search_items'       => __('Search Events'),
			'not_found'          => __('No Events found'),
			'not_found_in_trash' => __('No Events found in Trash'),
	);
	$args = array(
			'labels'              => $labels,
			'supports'            => array('title', 'thumbnail', 'revisions'),
			'rewrite'             => array('slug' => 'event', 'with_front' => false),
			'capability_type'     => 'post',
			'menu_position'       => 20, // after Pages
			'menu_icon'           => 'dashicons-id', // http://calebserna.com/dashicons-cheatsheet/
			'hierarchical'        => false,
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'query_var'           => true,
			'can_export'          => true,
	);
	register_post_type('events', $args);
}
add_action('init', 'register_events');