<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

// Replace layoutName and slider with your Layout Name
// You can remove the splide-css/splide-js if your layout doesn't use splide
// Remove the JS enqueue if it isn't needed
if (!function_exists('sliderScripts')) {
	function sliderScripts() {
		wp_enqueue_style('slider-css', get_stylesheet_directory_uri() . '/assets/css/slider.css', ['common-css', 'splide-css']);
	}
	add_action('wp_enqueue_scripts', 'sliderScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'slider';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$slider_size = !empty($slider_size) ? $slider_size : 'medium';
$slider_size_class = "slider--{$slider_size}";
$slider_columns = !empty($slider_columns) ? $slider_columns : 3;
$slider_columns_class = 'slider--col'.$slider_columns;
$title_style = !empty($title_style) ? $title_style : 'default';

if ($list_type == 'latest' && !empty($post_type)) {
	$numItems = !empty($latest) ? $latest : 3; // 3 by default
	$args = array(
			'post_type'		=> $post_type,
			'post_status'	=> 'publish',
			'numberposts'	=> $numItems,
			'fields'		=> 'ids'
	);
	$posts= get_posts( $args );
} elseif ($list_type === 'custom') {
	$posts = $cards;
}

if ($slider_columns==4) {
	$slider_breakpoints = array(
			'99999 => {"type":"loop", "perPage":4, "gap":"48px", "drag":'.(count($posts) <= 4 ? 'false' : 'true').'}',
			'1919 => {"type":"loop", "perPage":3, "gap":"16px", "drag":'.(count($posts) <= 3 ? 'false' : 'true').'}',
			'767  => {"type":"loop", "perPage":1, "gap":"0px"}'
	);
} elseif ($slider_columns==3) {
	$slider_breakpoints = array(
			'99999 => {"type":"loop","perPage":3, "gap":"34px", "drag":'.(count($posts) <= 3 ? 'false' : 'true').'}',
			'1919 => {"type":"loop", "perPage":3, "gap":"16px", "drag":'.(count($posts) <= 3 ? 'false' : 'true').'}',
			'767  => {"type":"loop", "perPage":1, "gap":"0px"}'
	);
} else { // 1
	$slider_breakpoints = array(
			'99999 => {"type":"loop", "perPage":1, "gap":"0px"}',
			'1919 => {"type":"loop", "perPage":1, "gap":"0px"}',
			'767  => {"type":"loop", "perPage":1, "gap":"0px"}'
	);
}

if ($slider_size === 'tall') {
	$image_sizes = [
			'1920'  => '888x778',
			'1280'  => '888x541',
			'768'   => '592x346',
			'0'     => '720x334'
	];
} else {
	$image_sizes = [
			'1920'  => '888x480',
			'1280'  => '888x365',
			'768'   => '592x282',
			'0'     => '720x255'
	];
}


?>
<section class="<?= $layout_name; ?> <?= $slider_columns_class; ?> <?= $slider_size_class; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="slider__heading">
			<?php compileTemplate('/templates/components/content.php', array(
					'content'           => !empty($content) ? $content : '',
					'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
					'link_array'        => !empty($link_array) ? $link_array : []
			));?>
		</div>
		<div class="<?= $layout_name; ?>__container">
			<?php
			$card_slides = array(
					'container_class' => "js-slider slider__slides",
					'slider_content' => array(),
					'custom_js' => false,
					'default' => '{"type":"slide"}',
					'breakpoints' => $slider_breakpoints,
			);

			// SELECTED POSTS
			if ($list_type == 'select' && !empty($posts)) {
				$i = 0;
				foreach ($posts as $card) {
					$card_slides['slider_content'][$i] = compileTemplate('card', array('id' => $card, 'title_style' => $title_style), false, false);
					$i++;
				}
			}
			// LATEST POSTS
			if ($list_type == 'latest' && !empty($posts)) {
				$i = 0;
				foreach ($posts as $card) {
					$card_slides['slider_content'][$i] = compileTemplate('card', array('id' => $card, 'card_media_sizes' => $image_sizes, 'title_style' => $title_style), false, false);
					$i++;
				}
			}
			// CUSTOM
			if ($list_type == 'custom') {
				$i = 0;
				foreach ($cards as $card) {
					$card_data = array(
							'card_media'		=> $card['card_media'],
							'card_heading'		=> $card['card_heading'],
							'card_overline'		=> $card['card_overline'],
							'card_flag'			=> $card['card_flag'],
							'card_content'		=> $card['card_content'],
							'card_media_sizes' => $image_sizes,
							'title_style' => $title_style
					);
					$card_slides['slider_content'][$i] = compileTemplate('card', $card_data, false, false);
					$i++;
				}
			}
			// Print Splider
			$card_slides['container_class'] .= (' slider__slides--' . count($card_slides['slider_content']));
			compileTemplate('splide-wrapper', $card_slides);
			?>
		</div>
	</div>
</section>