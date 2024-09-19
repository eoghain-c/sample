<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('panelSliderScripts')) {
    function panelSliderScripts()
    {
        wp_enqueue_style('panel-slider-css', get_stylesheet_directory_uri() . '/assets/css/panel-slider.css', ['common-css']);
        wp_enqueue_script('panel-slider-js', get_stylesheet_directory_uri() . '/assets/js/panel-slider.js', ['ondesign-common-js']);
    }
    add_action('wp_enqueue_scripts', 'panelSliderScripts');
}

/* Layout Settings */
$layout_name = 'panel-slider';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$horizontal_position = !empty($content_horizontal_position) ? $content_horizontal_position : 'left';
$vertical_position = !empty($content_vertical_position) ? $content_vertical_position : 'bottom';
$main_content = !empty($main_content) ? $main_content : [];

$with_peek = !empty($include_peek);
$media_height = !empty($media_height) ? $media_height : 'tall';
$layout_style = $with_peek ? 'peek' : $media_height;

$gallery_type = !empty($gallery_type) ? $gallery_type : 'basic';
$gallery_post = !empty($gallery_post) ? $gallery_post : '';
$gallery = !empty($gallery) ? $gallery : [];

$slides = !empty($slides) ? $slides : [];

// Image Sizes
switch ($layout_style) {
	case 'tall':
		$sizes = [
			'1920'  => '2575x1200',
			'1280'  => '1925x1100',
			'768'   => '1300x875',
			'0'     => '775x725'
		];
		break;
	case 'medium':
		$sizes = [
			'1920'  => '2575x1100',
			'1280'  => '1925x975',
			'768'   => '1300x750',
			'0'     => '775x550'
		];
		break;
	default: // peek
		$sizes = [
			'1920'  => '2150x975',
			'1280'  => '1525x875',
			'768'   => '1100x700',
			'0'     => '575x550'
		];
}

// Media Slides
$media_slides = [];

switch ($gallery_type) {

	case 'custom':
	case 'mixed':
		foreach ($slides as $index => $slide) {
			$slide_media = $slide['slide_media'];
			if (count($slides) > 1 && $slide_media['media_type'] == 'video' && $slide_media['autoplay']) {
				$slide_media['video_class'] = 'autoplay';
				$slide_media['autoplay'] = false;
			}
			$slide_media['image_sizes'] = $sizes;
			$media_slides[] = compileTemplate('media', $slide_media, false, false);
		}
		break;
	case 'post':
		$gallery = !empty($gallery_post) ? get_field('gallery', $gallery_post) : [];
		foreach ($gallery as $image) {
			$slide_media['media_type'] = 'picture';
			$slide_media['image_sizes'] = $sizes;
			$slide_media['image'] = $image;
			$media_slides[] = compileTemplate('media', $slide_media, false, false);
		}
		break;
	default: //basic
		foreach ($gallery as $image) {
			$slide_media['media_type'] = 'picture';
			$slide_media['image_sizes'] = $sizes;
			$slide_media['image'] = $image;
			$media_slides[] = compileTemplate('media', $slide_media, false, false);
		}
}

// Content Slides
$content_slides = [];
switch ($gallery_type) {
	case 'custom':
		foreach($slides as $index => $slide) {
			$content_slides[] = compileTemplate('content', [
				'content'           => !empty($slide['slide_content']['content']) ? $slide['slide_content']['content'] : '',
				'content_alignment' => !empty($slide['slide_content']['content_alignment']) ? $slide['slide_content']['content_alignment'] : 'left',
				'link_array'        => !empty($slide['slide_content']['link_array']) ? $slide['slide_content']['link_array'] : []
			], false, false);
		}
		break;
	default: //basic
		if (!empty($main_content['content']) || !empty($main_content['link_array'])) {
			$content_slides[] = compileTemplate('content', [
					'content'           => !empty($main_content['content']) ? $main_content['content'] : '',
					'content_alignment' => !empty($main_content['content_alignment']) ? $main_content['content_alignment'] : 'left',
					'link_array'        => !empty($main_content['link_array']) ? $main_content['link_array'] : []
			], false, false);
		}
}

// Output Layout
if (!empty($media_slides)): ?>
<section class="<?= $layout_name; ?> <?= $layout_name; ?>--<?= $layout_style; ?> <?= $layout_name; ?>--gallery-<?= $gallery_type; ?> <?= $settings['background_class'] ?? ''; ?> js-<?= $layout_name; ?>""<?= $settings['custom_id'] ?? ''; ?>>
    <div class="<?= $layout_name; ?>__inner v--<?= $vertical_position; ?> section-spacer <?= $settings['spacer'] ?? ''; ?>">
	    <div class="<?= $layout_name; ?>__media<?= empty($content_slides) && empty($with_peek) ? " {$layout_name}__media--shift-arrows" : ''; ?>">
		    <?php
		    if (count($media_slides) > 1) {
				$peek_classes = $with_peek ? " v--{$vertical_position} js-with-peek" : '';
				compileTemplate('splide-wrapper', [
						'container_class' => "{$layout_name}__media-slider{$peek_classes} js-{$layout_name}-media-slider",
						'slider_content' => $media_slides,
						'custom_js' => true
				]);
		    } else {
				echo $media_slides[0];
		    } ?>
	    </div>

		<?php if (!empty($content_slides)): ?>
	    <div class="<?= $layout_name; ?>__content-wrapper h--<?= $horizontal_position; ?> v--<?= $vertical_position; ?><?= count($content_slides) == 1 ? ' splide-wrapper' : ''; ?> js-content-wrapper">
		    <?php
		    if (count($content_slides) > 1) {
				compileTemplate('splide-wrapper', [
						'container_class' => "{$layout_name}__content-slider js-{$layout_name}-content-slider",
						'slider_content' => $content_slides,
						'custom_js' => true
				]);
		    } else {
				echo $content_slides[0];
		    } ?>
	    </div>
		<?php endif; ?>
    </div>
</section>
<?php endif; ?>