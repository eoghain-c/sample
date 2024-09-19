<?php
/**
 * MEDIA - media content output
 * @param string  $media_type         [required] Options: Image, Gallery, or Video
 * @param array   $image              [optional] Array of image data
 * @param array   $gallery            [optional] Nested Array of image data
 * @param string  $slider_defaults    [optional] Object string used to set default slider
 * @param array   $slider_breakpoints [optional] Array of string breakpoint change for the Splide wrapper
 * @param string  $video              [optional] Video URL
 * @param string  $video_class        [optional] Class for the video component
 * @param array   $fallback_image     [optional] Array of image data
 * @param array   $image_sizes        [optional] Array of image size overrides
 * @param string  $slider_default     [optional] default splide settings
 * @param string  $slider_arrows      [optional] svg icon name default is arrow
 * @param string  $slider_breakpoints [optional] splide breakpoint settings
 *  @uses picture Component
 *  @uses video Component
 *  @uses splide_wrapper Component
 *  @uses media Inc
 *  Ex. Image Sizes
 *  $media['image_sizes'] = [ '0'     => 'url'];
 */
if (!function_exists('mediaScripts')) {
	function mediaScripts()
	{
		wp_enqueue_style('media-css', get_template_directory_uri() . '/assets/css/media.css', ['common-css']);
	}

	add_action('wp_enqueue_scripts', 'mediaScripts');
}

$video_class = !empty($video_class) ? $video_class : '';

if (empty($image_sizes)) {
	$image_sizes = [
		'0'     => 'url' // default image size is full resolution
	];
}
if($media_type == 'picture' && !empty($image)) {
	$media = media_image($image, $image_sizes);
} elseif ($media_type == 'splide-wrapper' && !empty($gallery)) {
	if( count($gallery) <= 1 ) {
		$media_type = 'picture';
		$media = media_image($gallery[0], $image_sizes);
	} else {
		$media['arrow'] = $slider_arrows ?? 'arrow-splide';
		if(!empty($slider_breakpoints)){
			$media['breakpoints'] = $slider_breakpoints;
		}
		if(empty($slider_default)) { // Slider defaults if nothing passed in.
			$media['default'] = '{"type":"loop", "perPage": "1", "drag":"1", "perMove":1, "gap":"32px", "padding":{"left":"0","right":"0"}}';
		} else {
			$media['default'] = $slider_default;
		}
		foreach ($gallery as $image){
			$media['slider_content'][] = compileTemplate('picture', media_image($image, $image_sizes), false, false);
		}
	}
} elseif ($media_type == 'video' && !empty($video) && !empty($fallback_image)){
	$media = [
		'fallback' => $fallback_image,
		'url' => $video,
		'loopVideo' => $video_loop ?? true,
		'autoplay' => $autoplay ?? true,
		'class' => "media__video {$video_class}",
		'hide_mobile_video' => false
	];
}


?>

<article class="media">
	<?php
	if(!empty($media)) {
		compileTemplate("{$media_type}", $media);
	} else {
		echo "PLEASE SELECT MEDIA CONTENT";
	}
	?>
</article>