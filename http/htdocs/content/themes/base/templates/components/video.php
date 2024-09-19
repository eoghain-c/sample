<?php
/**
 * Template for adding a video element
 *
 * @param string $url              The src value for the video source
 * @param string $videoId          [optional] Specifies an id for the video element
 * @param array  $fallback         [optional] A fallback image array if the video is not intended to be played on mobile
 * @param array  $imgSizes         [optional] Array of crop sizes to use for poster and fallback
 * @param string $class            [optional] A class that will be added to the basic-video wrapper element
 * @param bool   $loopVideo        [optional] Whether the video will play in a loop or not. Default false
 * @param bool   $autoplay         [optional] Whether the video will autoplay or not. Default false
 * @param bool   $isMuted          [optional] Used to mute the video. Default false. Default For videos that autoplay, muted is required and will be added by default
 * @param bool   $advancedControls [optional] Used to add default browser controls. Basic custom controls will be added for accessibility if false
 * @param bool   $loadOnMobile     [optional] Used to determine if the video should be loaded on mobile. Default false - fallback image loaded instead
 */


add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style('basic-video', get_template_directory_uri() . '/assets/css/basic-video.css', array('common-css'), null);
	wp_enqueue_script('basic-video', get_template_directory_uri() . '/assets/js/basic-video.js', array(), null, true);
});


// Force a bool value
$autoplay = (isset($autoplay) && $autoplay === true);
$isMuted = (isset($isMuted) && $isMuted === true);
$loopVideo = (isset($loopVideo) && $loopVideo === true);
$advancedControls = (isset($advancedControls) && $advancedControls === true);
$loadOnMobile = (isset($loadOnMobile) && $loadOnMobile === true);

// Force an array value
$fallback = (isset($fallback) && is_array($fallback)) ? $fallback : [];
$imgSizes = (isset($imgSizes) && is_array($imgSizes)) ? $imgSizes : $image_sizes = $image_sizes = [ '0'     => 'url'];
$videoId = (isset($videoId)) ? $videoId : uniqid('video-');
$url = (isset($url)) ? $url : '';
$class = (isset($class)) ? $class : '';
$fallbackClass = (!empty($fallback)) ? 'basic-video--fallback' : '';
$playing = ($autoplay) ? 'true' : 'false'; // needs to be a string, not bool
$attributes = array();

$label = 'Play the video';

// Populate the attributes array
if ($autoplay) {
	$attributes[] = 'autoplay';
	$label = 'Pause the video';
}

if ($autoplay || $isMuted) {
	$attributes[] = 'muted="true"';
}

if ($advancedControls) {
	$attributes[] = 'controls';
}

if ($loopVideo) {
	$attributes[] = 'loop';
}

if ($loadOnMobile) {
	$attributes[] = 'mobile-loaded';
}
?>

<!-- Check if there is a fallback image set before displaying the component-->
<?php if (!empty($fallback)) { ?>

	<div class="basic-video js-video <?= $fallbackClass; ?> <?= $class; ?>" data-video="<?= $videoId; ?>">
		<video id="<?= $videoId; ?>" data-video-id="<?= $videoId; ?>" playsinline <?= implode(' ', $attributes); ?>>
			<source data-src="<?= $url; ?>" type="video/mp4; codecs=avc1.42E01E,mp4a.40.2">
		</video>
	
		<?php if (!empty($imgSizes)) { ?>
			
			<div class="basic-video__fallback basic-picture">
				<?php
				$sources = [];
				foreach ($imgSizes as $key => $value) {
					if($value == 'url') {
						$sources[$key] = $fallback[$value];
					} else {
						$sources[$key] = $fallback['sizes'][$value];
					}
				}
	
				$data = [
					'sources'  => $sources,
					'fallback' => $fallback['url'],
					'alt_text' => $fallback['alt'],
					'class'    => 'basic-video__fallback-img basic-picture'
				];
				compileTemplate('picture', $data);
				?>
			</div>
		<?php } ?>
	
		<?php if (!$advancedControls) { ?>
			<button class="basic-video__control js-video-control" aria-label="<?= $label; ?>" aria-live="polite" data-playing="<?= $playing; ?>">
				<span class="basic-video__icon"></span>
			</button>
		<?php } ?>
	
		<?php if (!empty($fallback) && !empty($imgSizes)) { ?>
			<button class="basic-video__poster-button js-video-poster-button" aria-label="Play Video">
				<div class="basic-video__poster basic-picture">
					<?php
					$sources = [];
					foreach ($imgSizes as $key => $value) {
						if($value == 'url') {
							$sources[$key] = $fallback[$value];
						} else {
							$sources[$key] = $fallback['sizes'][$value];
						}
					}
	
					$data = [
						'sources'  => $sources,
						'fallback' => $fallback['url'],
						'alt_text' => $fallback['alt'],
						'class'    => 'basic-video__poster-img basic-picture'
					];
					compileTemplate('picture', $data);
					?>
				</div>
				<div class="basic-video__poster-play">
					<?php display_icon('play'); ?>
				</div>
			</button>
		<?php } ?>
	</div>

<?php } ?>
