<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('interactiveMapScripts')) {
	function interactiveMapScripts()
	{
		wp_register_style('mapboxgl-css', get_template_directory_uri() . '/assets/css/mapbox-gl.css');
		wp_enqueue_style('interactive-map-css', get_stylesheet_directory_uri() . '/assets/css/interactive-map.css', ['common-css', 'mapboxgl-css', 'splide-css']);
		wp_register_script('mapboxgl-js', get_template_directory_uri() . '/assets/js/mapbox-gl.js');
		wp_enqueue_script('interactive-map-js', get_stylesheet_directory_uri() . '/assets/js/interactive-map.js', ['ondesign-common-js', 'mapboxgl-js', 'splide-js']);
	}
	add_action('wp_enqueue_scripts', 'interactiveMapScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'interactive-map';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
// Marker Data
$args = array(
		'post_type'       => 'map_marker',
		'post_status'     => 'publish',
		'posts_per_page'  => 250,
		'fields'          => 'ids',
);
$markers = new WP_Query($args);

// Marker Category Data
$categories = get_terms('map_marker_category', array(
		'hide_empty' => true
));

// Map Settings
$zoom_level = !empty(get_field('starting_zoom_level', 'options')) ? get_field('starting_zoom_level', 'options') : '';
$mobile_zoom_level = !empty(get_field('mobile_zoom_level', 'options')) ? get_field('mobile_zoom_level', 'options') : $zoom_level;
$map_pitch = !empty(get_field('map_pitch', 'options')) ? get_field('map_pitch', 'options') : '';
$map_bearing = !empty(get_field('map_bearing', 'options')) ? get_field('map_bearing', 'options') : '';
$center_latitude = !empty(get_field('center_latitude', 'options')) ? get_field('center_latitude', 'options') : '';
$center_longitude = !empty(get_field('center_longitude', 'options')) ? get_field('center_longitude', 'options') : '';

// Menu Title
$title = !empty($title_override) ? $title_override : get_field('interactive_map_title', 'options');
// Set Default Filter
$filter = !empty($default_filter->slug) ? $default_filter->slug : 'all';
console_log($filter);
?>
<section class="<?= $layout_name; ?> js-interactive-map"<?= $settings['custom_id'] ?? ''; ?> data-default-filter="<?php echo $filter; ?>">
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<?php // Map Menu Overlay ?>
		<div class="interactive-map__overlay js-map-overlay">
			<div class="interactive-map__overlay-content-wrapper">
				<?php // Menu Title ?>
				<?php if (!empty($title)): ?>
					<h2 class="title interactive-map__overlay-title"><?= $title; ?></h2>
				<?php endif;
				if (!empty($content)): ?>
					<?php compileTemplate('/templates/components/content.php', array(
							'content'           => !empty($content) ? $content : '',
							'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
							'link_array'        => !empty($link_array) ? $link_array : []
					));?>
				<?php endif; ?>
				<?php // Map Category Filters ?>
				<div class="interactive-map__marker-categories">
					<?php // Mobile Category Dropdown Button ?>
					<button class="interactive-map__dropdown js-map-dropdown-toggle" aria-expanded="false">Filter Map<?php display_icon('map-filter-arrow'); ?></button>

					<?php // Category List ?>
					<ul class="interactive-map__category-list js-map-category-list">
						<li class="interactive-map__category">
							<button class="interactive-map__category-select js-select-all" data-category="all" aria-label="Show All Categories">
								<?php display_icon('map-view-all'); ?>
								<span class="interactive-map__category-select-text">View All Categories</span>
							</button>
						</li>
						<?php
						foreach ($categories as $category) {
							if($category->slug != 'greenbrier') {
								?>
								<li class="interactive-map__category">
									<button class="interactive-map__category-select js-category-select" data-category="<?php echo $category->slug; ?>"
											aria-label="Show <?php echo $category->name; ?>">
										<?php if (file_exists(dirname(__DIR__) . '/../assets/img/icons/map-' . esc_html($category->slug) . '.svg')) {
											display_icon('map-'.$category->slug);
										} ?>
										<span class="interactive-map__category-select-text"><?= $category->name; ?></span>
									</button>
								</li>
							<?php }
						} ?>
					</ul>
				</div>
			</div>
		</div>

		<?php // MapBox Container ?>
		<div id="js-interactive-map-mapbox" class="interactive-map__map-container" data-pitch="<?= $map_pitch; ?>" data-bearing="<?= $map_bearing; ?>"
			 data-zoom="<?= $zoom_level; ?>" data-mobile-zoom="<?= $mobile_zoom_level; ?>" data-lat="<?= $center_latitude; ?>" data-long="<?= $center_longitude; ?>"></div>

		<?php // Marker Popups ?>
		<div class="interactive-map__popups">
			<?php
			foreach ($markers->posts as $index => $marker) {

				// Pin Data
				$latitude = !empty(get_field('latitude', $marker)) ? get_field('latitude', $marker) : ''; // Req
				$longitude = !empty(get_field('longitude', $marker)) ? get_field('longitude', $marker) : ''; // Req
				$categories = get_the_terms($marker, 'map_marker_category');
				$pin_category = 'default';
				$filterCategories = '';

				if (!empty($categories)) {
					// Check for Category Icons
					foreach ($categories as $category) {
						if (file_exists(dirname(__DIR__) . '/../assets/img/map/pin-' . esc_html($category->slug) . '.svg')) {
							$pin_category = $category->slug;
							break;
						} elseif (file_exists(dirname(__DIR__) . '/../assets/img/map/pin-' . esc_html($category->slug) . '.png')) {
							$pin_category = $category->slug;
							break;
						}
					}

					// Create Filter Categories
					foreach ($categories as $key => $category) {
						$filterCategories .= $key != 0 ? ','.$category->slug : $category->slug;
					}
				}

				// Modal Data
				$image = !empty(get_field('featured_image', $marker)) ? get_field('featured_image', $marker) : '';
				$title = get_the_title($marker);
				$content = !empty(get_field('content', $marker)) ? get_field('content', $marker) : '';
				$link = !empty(get_field('link', $marker)) ? get_field('link', $marker) : '';

				if (!empty($image)) {
					$picture_data = [
							'sources' => [
									'1920' => $image['sizes']['400x300'],
									'0'    => $image['sizes']['325x225'],
							],
							'fallback' => $image['sizes']['400x300'],
							'alt_text' => $image['alt'],
							'class' => 'basic-picture interactive-map__popup-image',
					];
				}
				?>

				<article id="map-popup-<?= $marker; ?>" class="interactive-map__popup js-interactive-map-popup"
						 data-lat="<?= $latitude; ?>" data-long="<?= $longitude; ?>" data-id="<?= $marker; ?>"
						 data-name="<?= $title; ?>" data-pin-category="<?= $pin_category; ?>" data-filter-categories="<?= $filterCategories; ?>">

					<div class="interactive-map__popup-wrapper interactive-map__popup--no-image">
<!--						--><?php //// Popup Image
						if (!empty($image)) {
							compileTemplate('/templates/components/picture.php', $picture_data);
						}
//						?>

						<?php // Popup Content ?>
						<div class="interactive-map__popup-content-wrapper">
							<h3 class="interactive-map__popup-title"><?= $title; ?></h3>

							<?php if (!empty($content)) { ?>
								<div class="wysiwyg interactive-map__popup-content"><?= $content; ?></div>
							<?php } ?>
						</div>

						<?php
						if (!empty($link) && !empty($link['title']) && !empty($link['url'])) {
							$link_data = array(
									'link_type' => 'link',
									'link_style' => 'link link__btn link__btn--bluish interactive-map__popup-link',
									'link' => $link
							);

							compileTemplate('/templates/components/links.php', array(
									'class' => 'interactive-map__popup-links',
									'links_data' => array($link_data)
							));
						}
						?>
					</div>
				</article>
			<?php } ?>
		</div>
	</div>
</section>
