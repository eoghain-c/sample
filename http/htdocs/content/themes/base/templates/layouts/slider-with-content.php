<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('sliderWithContentScripts')) {
    function sliderWithContentScripts()
    {
        wp_enqueue_style('slider-with-content-css', get_stylesheet_directory_uri() . '/assets/css/slider-with-content.css', ['common-css', 'splide-css']);
    }
    add_action('wp_enqueue_scripts', 'sliderWithContentScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'swc';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$content_location 	= !empty($content_location) ? $content_location : '';
$slider_type 		= !empty($slider_type) ? $slider_type : '';
$card_slides 		= !empty($card_slides) ? $card_slides : [];
$custom_cards 		= !empty($custom_cards) ? $custom_cards : [];
$custom_slides 		= !empty($custom_slides) ? $custom_slides : '';
$d_position 		= !empty($content_location_desktop) ? $content_location_desktop : '';
$m_position 		= !empty($content_location_mobile) ? $content_location_mobile : '';
$media 				= [];
// Determine if we will be using the single or double slider and set image sizes accordingly
if ($slider_type == 'card-single' || $slider_type == 'custom-single-card' || $slider_type == 'custom-single'){
	//Set Image Sizes
	$image_sizes = [
		'1920'  => '1350x750',
		'1280'  => '800x600',
		'768'   => '450x525',
		'0'     => '350x425'
	];

} else {
	// Set Image Sizes
	$image_sizes = [
			'1920'  => '700x650',
			'1280'  => '400x500',
			'768'   => '325x425',
			'0'     => '350x425'
	];
}
$slider_data = [];
$slider_data['slider_content'] = [];
switch ($slider_type){
	case 'card-single':
	case 'custom-single-card':
		$slider_data['default'] = '{"type":"loop", "gap":"48px", "pagination":false, "trimSpace":false, "focus" : "center", "padding": {"left" : "309px", "right" : "0"}}';
		$slider_data['breakpoints'] =  array(
				'1919 => {"padding":{"left" : "155px", "right" : "0px"}}',
				'1279 => {"gap":"16px", "focus" : "center", "padding":{"left" : "41px", "right" : "0px"} }',
				'767 => {"gap":"24px", "focus":"center", "padding": {"left" : "24px", "right" : "24px"}}'
		);
		break;
	case 'custom-single':
		$slider_defaults = '{"type":"loop", "gap":"48px", "pagination":false, "trimSpace":false, "focus" : "center", "padding": {"left" : "309px", "right" : "0"}}';
		$slider_breakpoints =  array(
				'1919 => {"gap":"24px","padding":{"left" : "155px", "right" : "0px"}}',
				'1279 => {"gap":"17px", "focus" : "right", "padding":{"left" : "41px", "right" : "0px"} }',
				'767 => {"gap":"24px", "focus":"center", "padding": {"left" : "24px", "right" : "24px"}}'
		);
		break;
	case 'card-double':
	case 'custom-double-card':
		$slider_data['default'] = '{"type":"loop", "perPage":2, "perMove": 1, "gap":"48px", "pagination":false, "trimSpace":false, "focus" : "right", "padding":{"left" : "0px", "right" : "240px"}}';
		$slider_data['breakpoints'] =  array(
				'1919 => {"gap" : "24px", "padding":{"left" : "0px", "right" : "157px"}}',
				'1279 => {"perPage":"1","gap":"16px", "focus" : "right", "padding":{"left" : "0", "right" : "157px"} }',
				'767 => {"perPage":"1","gap":"24px", "focus":"center", "padding": {"left" : "24px", "right" : "24px"}}'
		);
		break;
	case 'custom-double':
		$slider_defaults = '{"type":"loop", "perPage":2, "perMove": 1, "gap":"48px", "pagination":false, "trimSpace":false, "focus" : "right", "padding":{"left" : "0px", "right" : "240px"}}';
		$slider_breakpoints =  array(
				'1919 => {"gap" : "24px", "padding":{"left" : "0px", "right" : "157px"}}',
				'1279 => {"perPage":"1","gap":"16px", "focus" : "right", "padding":{"left" : "0", "right" : "157px"} }',
				'767 => {"perPage":"1","gap":"24px", "focus":"center", "padding": {"left" : "24px", "right" : "24px"}}'
		);
		break;
}
?>
<section class="<?= $layout_name; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
    <div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="<?= $layout_name; ?>__position <?= $layout_name; ?>__position--<?= $d_position; ?>-<?= $m_position; ?> <?= $slider_type; ?>">
			<div class="<?= $layout_name; ?>__content-container">
				<?php compileTemplate('/templates/components/content.php', array(
						'content'           => !empty($content) ? $content : '',
						'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
						'link_array'        => !empty($link_array) ? $link_array : []
				));?>
			</div>
			<div class="<?= $layout_name; ?>__slider-container <?= $layout_name; ?>__slider-container--<?= $slider_type; ?>">
				<?php
				if ($slider_type == 'card-single' || $slider_type == 'card-double'):
					foreach( $card_slides as $card_slide ):
						$slider_data['slider_content'][] = compileTemplate('card', ['id' => $card_slide->ID], false, false);
					endforeach;
					compileTemplate('splide-wrapper', $slider_data);
				elseif ($slider_type == 'custom-single-card' || $slider_type == 'custom-double-card'):
					foreach( $custom_cards as $custom_card ):
						$card = $custom_card['custom_card'];
						$card['card_media']['image_sizes'] = $image_sizes;
						$slider_data['slider_content'][] = compileTemplate('card', $card, false, false);
					endforeach;
					compileTemplate('splide-wrapper', $slider_data);
				else:
					$media = [
							'media_type' => 'splide-wrapper',
							'gallery' => $custom_slides,
							'image_sizes' => $image_sizes,
							'class' => 'swc-picture',
							'slider_default' => $slider_defaults,
							'slider_breakpoints' => $slider_breakpoints
					];
					compileTemplate('media', $media);
				endif;
				?>
			</div>
		</div>
    </div>
</section>