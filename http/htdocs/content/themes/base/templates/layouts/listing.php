<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('listingScripts')) {
	function listingScripts()
	{
		wp_enqueue_style('listing-css', get_stylesheet_directory_uri() . '/assets/css/listing.css', ['common-css']);
		wp_enqueue_script('listing-js', get_stylesheet_directory_uri() . '/assets/js/listing.js', ['ondesign-common-js']);
	}
	add_action('wp_enqueue_scripts', 'listingScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'listing';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

$list_type = !empty($list_type) ? $list_type : 'select';
$columns = !empty($columns) ? $columns : 'two';
$listing_class = "listing--{$columns}-column";
$title_style = !empty($title_style) ? $title_style : 'default';

if ($list_type == 'all' || $list_type == 'latest') {

	$listing_class .= (' listing--' . $custom_post_type);
	$post_count = ($list_type == 'all') ? $post_perpage : $latest;
	$exclude = get_the_ID();

	if ($custom_post_type === 'accommodations' && !empty($parent_category)) {
		$listing_class .= ' listing--parent-taxonomy';
		$categories = get_category_list($custom_post_type, $parent_category);
		$parent_term = get_term($parent_category);
		$parent_taxonomy = $parent_term->taxonomy;

		if(count($categories['term_children']) > 0) {
			$results = get_ids($custom_post_type, $post_count, array($exclude), $parent_category, $parent_taxonomy, 0, array(array($categories['term_children'][0]->taxonomy, $categories['term_children'][0]->term_id)));
		} else {
			$results = get_ids($custom_post_type, $post_count, array($exclude), $parent_category, $parent_taxonomy); //if no categories it needs to get all and be able to load more
		}

	} else {
		$parent_category = '';
		$categories = get_category_list($custom_post_type, $parent_category);
		$results = get_ids($custom_post_type, $post_count, array($exclude));
	}

	if ($list_type == 'all') {
		$listing_class .= ' js-cards-listing';
	}

	$posts = $results['posts'];

	
} else if ($list_type == 'custom') {
	$posts = !empty($custom_cards) ? $custom_cards : [];
	$listing_class .= ' listing--custom';
} else {
	$posts = !empty($posts) ? $posts : [];
}

?>
<section class="listing <?= $listing_class; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="listing__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<?php if ($list_type == 'all' && count($categories) >= 1):
			/* Parent Category Filter */
			if (!empty($parent_category)): ?>
			<h2 class="listing__filters-title title">View Our <?= $categories['term']->name; ?></h2>
				<?php if (!empty($categories['term_children'])): ?>
					<div class="listing__filters listing__filters--buttons">
						<div class="listing__filters-buttons js-filter-btn-wrapper" data-taxonomy="<?= $categories['taxonomy']->name; ?>" data-param="<?= $categories['param']; ?>" aria-label="Filter by <?= $categories['taxonomy']->labels->singular_name; ?>">
							<?php foreach($categories['term_children'] as $term): ?>
								<button class="listing__filter-btn link link__btn js-filter-btn" data-value="<?= $term->term_id; ?>" data-filter="<?= $term->slug; ?>"><?= $term->name; ?></button>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php else: ?>
			<div class="listing__filters<?= !empty($filter_background) ? '' : ' listing__filters--no-background'?>">
				<div class="listing__filters-bar">
					<h2 class="listing__filters-title title">Filter By: </h2>
					<div class="listing__filters-select js-filters-area">
						<?php
						/* Taxonomy Filter */
						foreach ($categories as $category):
							if(count($category['terms']) > 0): ?>
							<select class="listing__filter js-listing-filter" data-taxonomy="<?= $category['taxonomy']->name; ?>" data-param="<?= $category['param']; ?>" aria-label="Filter by <?= $category['taxonomy']->labels->singular_name; ?>">
								<option class="listing__filter-option hidden" selected disabled hidden><?= $category['taxonomy']->labels->singular_name; ?></option>
								<option class="listing__filter-option js-filter-option" value="" data-filter="">All <?= $category['taxonomy']->labels->name; ?></option>
								<?php foreach ($category['terms'] as $term): ?>
									<option class="listing__filter-option js-filter-option" value="<?= $term->term_id; ?>" data-filter="<?= $term->slug ?>"><?= $term->name; ?></option>
								<?php endforeach; ?>
							</select>
						<?php endif;
						endforeach;

						/* Meta Filter */
						if ($custom_post_type === 'venues'): ?>
							<select class="listing__filter js-listing-meta" data-key="capacity" data-param="capacity" data-type="numeric" data-compare="<=" aria-label="Filter by Capacity">
								<option class="listing__filter-option hidden" selected disabled hidden>Capacity</option>
								<option class="listing__filter-option js-filter-option" value="" data-filter="">All Capacities</option>
								<option class="listing__filter-option js-filter-option" value="100" data-filter="100">Up to 100</option>
								<option class="listing__filter-option js-filter-option" value="500" data-filter="500">Up to 500</option>
								<option class="listing__filter-option js-filter-option" value="1000" data-filter="1000">Up to 1000</option>
								<option class="listing__filter-option js-filter-option" value="2000" data-filter="2000">Up to 2000</option>
							</select>
						<?php endif; ?>
					</div>
					<button class="listing__filters-clear link link__text js-listing-clear"><span>Clear Filters</span></button>
				</div>
				<div class="listing__filters-count js-filter-count">
					<span class="js-filter-count-current"><?= $post_perpage; ?></span> of <span class="js-filter-count-total"><?= $results['total']; ?></span> Results Showing
				</div>
			</div>
			<?php endif; ?>
	    <?php endif; ?>
		<div class="listing__cards-wrapper">
			<?php if ($list_type == 'all'): ?>
			<div class="js-listing__loader listing__loader"><span class="loader"></span></div>
			<?php endif; ?>
			<div class="js-listing__cards listing__cards">
			<?php

			// Card Image Crop Sizes
			if ($columns === 'four') {
				$card_media_sizes = array(
						'1920' => '600x500',
						'1280' => '575x275',
						'768'  => '400x200',
						'0'    => '725x275',
				);
			} else if ($columns === 'three') {
				$card_media_sizes = array(
						'1920' => '725x500',
						'1280' => '575x375',
						'768'  => '600x300',
						'0'    => '725x275',
				);
			} else {
				$card_media_sizes = array(
						'1920' => '1100x500',
						'1280' => '900x375',
						'768'  => '600x300',
						'0'    => '725x275',
				);
			}

			if ($list_type !== 'custom') {
				foreach ($posts as $id) {
					compileTemplate('card', array(
						'id' => $id,
						'card_media_sizes' => $card_media_sizes,
						'title_style' => $title_style
					));
				}
			} else {
				$custom_card['card_media_sizes'] = $card_media_sizes;
				foreach ($posts as $custom_card) {
					$custom_card['title_style'] = $title_style;
					compileTemplate('card', $custom_card);
				}
			} ?>
			</div>
			<?php if ($list_type == 'all'): ?>
			<button class="js-listing-more listing__more link link__btn link__btn--green<?= $results['total'] <= $post_perpage ? ' hidden' : ''; ?>" data-type="<?= $custom_post_type; ?>" data-perpage="<?= $post_perpage; ?>" data-exclude="<?= $exclude; ?>" data-parent="<?= $parent_category; ?>" data-parent-type="<?= $parent_taxonomy; ?>">Load More</button>
			<?php endif; ?>
		</div>
	</div>
</section>
