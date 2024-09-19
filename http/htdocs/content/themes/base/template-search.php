<?php
/*
Template Name: Search Page
*/


function searchPageScripts(){
	wp_enqueue_style('search-page-styles', get_template_directory_uri() . '/assets/css/search.css', ['common-css']);
}
add_action('wp_enqueue_scripts', 'searchPageScripts');


global $AdvancedSearch;
$AdvancedSearch->initialize();
$AdvancedSearch->runSearchQuery(); // Query results saved in object

$pagination = $AdvancedSearch->getPagination(); // Get pagination

// Start output buffering
ob_start();
?>

<main id="#content" tabindex="-1">
	<?php // Search Bar ?>
	<section class="s-bar s-bar--narrow container-fluid">
		<h1 class="s-bar__title"><?php echo $AdvancedSearch->getSearchBarTitle(); ?></h1>

		<?php
		$searchFormData = array('searchTerm' => $AdvancedSearch->getSearchTerm());
		includeWithVariables(get_template_directory() . '/templates/components/search-form.php', $searchFormData); ?>
	</section>

	<?php // Search Results ?>
	<?php if ($AdvancedSearch->getFoundPosts() && !empty($AdvancedSearch->getSearchTerm())) : ?>
		<section class="s-results container-fluid">
			<h2 class="s-results__heading">
				<strong><?php echo $AdvancedSearch->getFoundPosts(); ?></strong> results for <strong>'<?php echo $AdvancedSearch->getSearchTerm(); ?>'</strong>
			</h2>

			<ul class="s-results__list">
				<?php foreach ($AdvancedSearch->getPosts() as $post):
					$description = get_post_meta($post->ID, '_aioseop_description', $post->ID);
					$postTypeTitle = $AdvancedSearch->getPostTypes()[$post->post_type]; ?>

					<li class="s-results__item">
						<?php if (!empty($post->post_title)): ?>
							<a href="<?php echo get_permalink($post->ID); ?>" class="s-results__link"><?php echo $post->post_title; ?></a>
						<?php endif; ?>

						<span class="s-results__cpt"><?php echo $postTypeTitle; ?></span>

						<?php if (!empty($description)): ?>
							<p class="s-results__description"><?php echo $description; ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if (!empty($pagination)): ?>
				<nav class="sr-pagination" aria-label="Pagination">
					<ul class="sr-pagination__items">
						<?php foreach ($pagination as $key => $page_link):
							$currentClass = (strpos($page_link, 'current') !== false) ? 'active' : '';
							?>
							<li class="sr-pagination__item <?php echo $currentClass; ?>"><?php echo $page_link; ?></li>
						<?php endforeach ?>
					</ul>
				</nav>

				<nav class="sr-pagination sr-pagination--mobile">
					<ul class="sr-pagination__items">
						<li class="sr-pagination__item"><?php echo $pagination[0]; ?></li>
						<li class="sr-pagination__info"><?php echo 'Page <strong>' . ($AdvancedSearch->getPaged() ?: '1') . '</strong> of <strong>' . $AdvancedSearch->getWpQuery()->max_num_pages . '</strong>'; ?></li>
						<li class="sr-pagination__item"><?php echo end($pagination); ?></li>
					</ul>
				</nav>
			<?php endif; ?>
		</section>
	<?php endif; ?>
</main>

<?php
// End buffering and return its contents
$output = ob_get_clean();

get_header();
echo $output;
get_footer();