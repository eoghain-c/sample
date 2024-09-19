<?php
/**
 * Sitemap
 *
 * @param string $sitemap_heading
 * @param string $sitemap_heading_element
 */


if (!function_exists('sitemap_scripts')) {
	function sitemap_scripts()
	{
		wp_enqueue_style('sitemap-css', get_template_directory_uri() . '/assets/css/sitemap.css', ['common-css']);
	}

	add_action('wp_enqueue_scripts', 'sitemap_scripts');
}


$sitemap_heading = (!empty($sitemap_heading)) ?: get_field('sitemap_heading', 'option');
$sitemap_heading_element = (!empty($sitemap_heading_element)) ?: get_field('sitemap_heading_element', 'option');
$exclude = get_field('exclude_pages_from_sitemap', 'option') ?: [];

$sitemap_args = [
	'exclude'  => '',
	'title_li' => '',
];

if (!empty($exclude)) {
	foreach ($exclude as $page) {
		$sitemap_args['exclude'] .= $page . ',' . get_child_ids($page);
	}
	$sitemap_args['exclude'] = rtrim($sitemap_args['exclude'], ',');
}
?>


<article class="sitemap bottom-spacing side-spacing">
	<div class="sitemap__inner">
		<?php
		if (!empty($sitemap_heading) && !is_404() ) {
			echo "<{$sitemap_heading_element} class='sitemap__heading heading-2'>{$sitemap_heading}</{$sitemap_heading_element}>";
		} ?>

		<ul class="sitemap__list">
			<?php wp_list_pages($sitemap_args); ?>
		</ul>
	</div>
</article>