<?php
if (!function_exists('breadcrumbsScripts')) {
	function breadcrumbsScripts()
	{
		wp_enqueue_style('breadcrumbs-css', get_template_directory_uri() . '/assets/css/breadcrumbs.css', ['common-css']);
	}

	add_action('wp_enqueue_scripts', 'breadcrumbsScripts');
}


$pageID = get_the_ID();
$post_type = get_post_type();
$breadcrumbs = get_field('breadcrumbs_exclude', 'options');

if (empty($breadcrumbs)) {
	$breadcrumbs = array();
}


// Do not show breadcrumbs if on home page or if they have been excluded from page
if (!is_front_page() && !empty($post_type) && !in_array($pageID, $breadcrumbs)) { ?>
	<nav class="breadcrumbs" aria-label="Breadcrumbs">
		<div class="breadcrumbs__inner">
			<?php
			$home = 'Home';
			$ancestors = get_ancestors($pageID, $post_type);
			$ancestors = array_reverse($ancestors);
			?>

			<ol class="breadcrumbs__list">
				<li class="breadcrumbs__item">
					<?php // Link to homepage ?>
					<a class="breadcrumbs__link" href="<?= get_home_url(); ?>" title="Return to Homepage"><?= $home; ?></a>
				</li>

				<?php
				if ($post_type != 'page' && $post_type != 'post') {

					$post_type_data = get_post_type_object($post_type);
					$post_type_slug = $post_type_data->rewrite['slug'];

					$post_type_slug_array = explode('/', $post_type_slug);

					$recombineSlug = '';
					foreach ($post_type_slug_array as $item) {
						$recombineSlug .= $item . '/';
						$post_type_page = get_page_by_path($recombineSlug);

						if (!empty($post_type_page)) { ?>
							<li class="breadcrumbs__item">
								<a class="breadcrumbs__link" href="<?= get_permalink($post_type_page->ID); ?>"><?= get_the_title($post_type_page->ID); ?></a>
							</li>
							<?php
						}
					}
				}

				foreach ($ancestors as $ancestor) { ?>
					<li class="breadcrumbs__item">
						<a class="breadcrumbs__link" href="<?= get_permalink($ancestor); ?>"><?= get_the_title($ancestor); ?></a>
					</li>
				<?php } ?>

				<li class="breadcrumbs__item">
					<a href="<?= get_permalink($pageID); ?>" class="breadcrumbs__link breadcrumbs__link--current" aria-current="page"><?= get_the_title($pageID); ?></a>
				</li>
			</ol>
		</div>
	</nav>
<?php } ?>
