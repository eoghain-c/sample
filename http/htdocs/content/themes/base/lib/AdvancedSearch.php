<?php

/**
 * Modified version of this: {@link https://gist.github.com/charleslouis/5924863}
 */
class AdvancedSearch
{
	/**
	 * The number of results from a search query
	 *
	 * @var int
	 */
	private $foundPosts;

	/**
	 * The value from the pages query string parameter
	 *
	 * @var int
	 */
	private $paged;

	/**
	 * Array of post types to be searched
	 *
	 * @var array
	 */
	private $postTypeSlugs;

	/**
	 * Array of 'post_type => post_title' as 'key => value'
	 *
	 * @var array
	 */
	private $postTypes;

	/**
	 * Array of WP_Post objects from a search query
	 *
	 * @var array
	 */
	private $posts;

	/**
	 * The value from the q query string parameter
	 *
	 * @var string
	 */
	private $searchTerm;

	/**
	 * The entire results from a search query
	 *
	 * @var WP_Query
	 */
	private $wpQuery;


	/**
	 * AdvancedSearch constructor.
	 */
	public function __construct()
	{
		// Add posts search filter
		add_filter('posts_search', array($this, 'advanced_custom_search'), 500, 2);
	}


	/**
	 * Search that encompasses ACF/advanced custom fields and taxonomies and split expression before request
	 * see https://vzurczak.wordpress.com/2013/06/15/extend-the-default-wordpress-search/
	 * credits to Vincent Zurczak for the base query structure/spliting tags section
	 *
	 * @param string $where    The initial "where" part of the search query
	 * @param object $wp_query
	 *
	 * @return string The "where" part of the search query after we customized it
	 */
	public function advanced_custom_search($where, $wp_query)
	{
		if (empty($where)) {
			return $where;
		}

		// Get search expression
		if (is_admin()) {
			$terms = $wp_query->query_vars['s'];
		} else {
			$terms = $keyword = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
			$terms = str_replace("&#39;","''",$terms);
		}

		// Explode search expression to get search terms
		$exploded = explode(' ', $terms);
		if ($exploded === false || count($exploded) == 0)
			$exploded = array(0 => $terms);

		// Reset search in order to rebuilt it as we which
		$where = '';

		// Get searcheable_acf, a list of advanced custom fields to search content in
		$list_searcheable_acf = $this->list_searcheable_acf();

		foreach ($exploded as $tag) {
			$where .= " 
          AND (
            (ondesign_posts.post_title LIKE '%$tag%')
            OR (ondesign_posts.post_content LIKE '%$tag%')";

			if (!empty($list_searcheable_acf)) {
				$where .= "  OR EXISTS (
              SELECT * FROM ondesign_postmeta
	              WHERE post_id = ondesign_posts.ID
	                AND (";

				foreach ($list_searcheable_acf as $searcheable_acf) {

					if ($searcheable_acf == $list_searcheable_acf[0]) {
						$where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
					} else {
						$where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
					}

				}
				$where .= ")
            )";
			}

			$where .= " )";
		}

		return $where;
	}


	/**
	 * @return int
	 */
	public function getFoundPosts()
	{
		return $this->foundPosts;
	}


	/**
	 * @return int
	 */
	public function getPaged()
	{
		return $this->paged;
	}


	/**
	 * Returns the results of paginate_links().  Default $data can be overwritten or added to by passing an array with the same keys or
	 * new keys. prev_text and next_text are common use cases.
	 *
	 * @param array $overrideData
	 *
	 * @return array|string|void
	 */
	public function getPagination($overrideData = array())
	{
		$data = array(
			'total' => $this->getWpQuery()->max_num_pages,
			'base' => add_query_arg('paged', '%#%'),
			'format' => '?paged=%#%',
			'current' => max(1, $this->getPaged()),
			'prev_text' => __('<i class="icon-arrow" aria-hidden="true"></i> Previous'),
			'next_text' => __('Next <i class="icon-arrow" aria-hidden="true"></i>'),
			'show_all' => false,
			'mid_size' => 2,
			'type' => 'array'
		);

		// Combine arrays to allow overriding default data
		foreach ($overrideData as $key => $value) {
			$data[$key] = $value;
		}

		return paginate_links($data);
	}


	/**
	 * @return array
	 */
	public function getPostTypes()
	{
		return $this->postTypes;
	}


	/**
	 * @return array
	 */
	public function getPosts()
	{
		return $this->posts;
	}


	/**
	 * Return a generic title based on query results.  No override functionality
	 *
	 * @return string
	 */
	public function getSearchBarTitle()
	{
		if ($this->getFoundPosts() && $this->getSearchTerm()) {
			return 'Search Results';
		} elseif (!$this->getSearchTerm()) {
			return 'Search by keyword(s)';
		} else {
			return $this->getSearchTerm() . '&rsquo; has no results';
		}
	}


	/**
	 * @return string
	 */
	public function getSearchTerm()
	{
		return $this->searchTerm;
	}


	/**
	 * @return WP_Query
	 */
	public function getWpQuery()
	{
		return $this->wpQuery;
	}


	/**
	 * Required to run before runSearchQuery().  Gets query string data.  Gets and processes acf field data.
	 * Sets a lot of the class variables.
	 */
	public function initialize()
	{
		// Save search query string data
		$this->searchTerm = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
		$this->paged = filter_input(INPUT_GET, 'paged', FILTER_SANITIZE_STRING);

		// Get acf field data
		$acfPagesTitle = get_field('title_for_pages', "options");
		$acfPostsTitle = get_field('title_for_posts', "options");
		$acfPostTypes = get_field("post_types", "options");

		// Pages and Posts are added by default to search
		$postTypesExpanded = [
			[
				"post_type" => "page",
				"post_title" => $acfPagesTitle
			],
			[
				"post_type" => "post",
				"post_title" => $acfPostsTitle
			]
		];

		// Merge the default array with the CPT Searchables
		if (!empty($acfPostTypes)) {
			$postTypesExpanded = array_merge($postTypesExpanded, $acfPostTypes);
		}

		$this->postTypeSlugs = array_column($postTypesExpanded, "post_type"); // Put all post_type values into their own array
		$postTypeTitles = array_column($postTypesExpanded, "post_title"); // Put all post_title values into their own array
		$this->postTypes = array_combine($this->postTypeSlugs, $postTypeTitles); // Make 'post_type => post_title' as 'key => value' array
	}


	/**
	 * Run the search query and save the returned data in class variables.
	 *
	 * @param int $postsPerPage Number of results to display on a page
	 */
	public function runSearchQuery($postsPerPage = 6)
	{
		// $wp_query because it sets a tag to let wordpress know its a search page;
		$this->wpQuery = new WP_Query(array(
			'post_type' => $this->postTypeSlugs,
			's' => $this->searchTerm,
			'post_status' => 'publish',
			'posts_per_page' => $postsPerPage,
			'paged' => $this->paged,
		));

		$this->wpQuery->is_search = true;
		$this->posts = $this->wpQuery->posts;
		$this->foundPosts = $this->wpQuery->found_posts;
	}


	/**
	 * List all the custom fields we want to include in our search query
	 *
	 * @return array list of custom fields
	 */
	private function list_searcheable_acf()
	{
		$items = explode("\r\n", get_field('search_items', 'options'));
		$items = array_filter($items, function ($value) {
			return !empty($value);
		}); // Removes all empty arrays

		return $items;
	}
}

$AdvancedSearch = new AdvancedSearch;