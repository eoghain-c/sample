<?php
function get_ids($type, $perpage = 6, $exclude = [], $parent = '', $parent_type = '', $offset = 0, $tax_filters = [], $meta_filters = []) {
	if (empty($type)) { return false; }

	// Filtering -----
	$tax_query = [];
	$meta_query = [];

	$parentArray = [$parent_type, $parent];

	// Taxonomy -----
	if (!empty($tax_filters) || !empty($parent)) {
		$tax_query = array(
			'relation' => 'AND',
		);

		if (!empty($parentArray) && empty($tax_filters)) {
			$tax_query[] = array(
				'taxonomy' => $parentArray[0],
				'field' => 'id',
				'terms' => $parentArray[1],
			);
		} else {
			foreach ($tax_filters as $filter) {

				$terms = [];
				$terms[] = $filter[1];

				$tax_query[] = array(
					'taxonomy' => $filter[0],
					'field' => 'id',
					'terms' => $terms,
				);
			}
		}
	}

	// Meta -----
	if (!empty($meta_filters)) {
		$meta_query = array(
			'relation' => 'AND',
		);

		foreach ($meta_filters as $filter) {
			$meta_query[] = array(
				'key' => $filter[0],
				'value' => $filter[1],
				'type' => $filter[2],
				'compare' => $filter[3]
			);
		}
	}

	$args = array(
		'post_type' => $type,
		'tax_query' => $tax_query,
		'meta_query' => $meta_query,
		'offset' => $offset,
		'post_status' => 'publish',
		'fields' => 'ids',
		'posts_per_page' => $perpage,
		'post__not_in' => $exclude
	);

	$results = new WP_Query( $args );

	return [
		'posts' => $results->posts,
		'total' => $results->found_posts
	 ];
}

function get_category_list($type, $parent_category = ''){

	$category_list = [];

	if (!empty($parent_category)) {

		$category_list['term'] = get_term($parent_category);
		$category_list['taxonomy'] = get_taxonomy($category_list['term']->taxonomy);
		$category_list['param'] = str_replace('_', '-', str_replace("{$type}_", '', $category_list['term']->taxonomy));
		$category_list['term_children'] = [];

		// Instead of using get_term_children (which returns children terms in ascending order of their term IDs),
		// changed to using get_terms to retrieve term children so I can set the order
		// This way, it respects the order set by the Intuitive Custom Post Order plugin

		$args = array(
			'taxonomy' => $category_list['term']->taxonomy,
			'child_of' => $parent_category,
			'hide_empty' => true,
			'orderby' => 'menu_order'
		);
		$term_children = get_terms($args);

		$category_list['term_children'] = $term_children;

	} else {

		$taxonomies = get_object_taxonomies($type);
		foreach ($taxonomies as $taxonomy) {
			$category['taxonomy'] = get_taxonomy($taxonomy);
			$category['param'] = str_replace('_', '-', str_replace("{$type}_", '', $taxonomy));
			$category['terms'] = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => true));
			$category_list[] = $category;
		}

	}

	return $category_list;
}

function listing_call (){
	$type = !empty($_POST['type']) ? $_POST['type'] : '';
	$perpage = !empty($_POST['perpage']) ? $_POST['perpage'] : 6;
	$exclude = !empty($_POST['exclude']) ? array($_POST['exclude']) : [];
	$parent = !empty($_POST['parent']) ? array($_POST['parent']) : '';
	$parent_type = !empty($_POST['parentType']) ? array($_POST['parentType']) : '';
	$offset = !empty($_POST['offset']) ? $_POST['offset'] : 0;
	$tax_filters = !empty($_POST['taxFilters']) ? $_POST['taxFilters'] : [];
	$meta_filters = !empty($_POST['metaFilters']) ? $_POST['metaFilters'] : [];

	ob_start();
	$results = get_ids($type, $perpage, $exclude, $parent[0], $parent_type[0], $offset, $tax_filters, $meta_filters);
	$ids = $results['posts'];

	foreach ($ids as $id) {
		compileTemplate('card', array('id' => $id));
	}

	$new_cards = ob_get_clean();
	$results['cards'] = $new_cards;
	$results['post_total'] = $results['total'];
	wp_send_json($results);

	if (wp_doing_ajax()) wp_die();
}


add_action('wp_ajax_listing_call', 'listing_call');
add_action('wp_ajax_nopriv_listing_call', 'listing_call');