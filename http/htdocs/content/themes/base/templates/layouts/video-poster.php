<?php
if( $hide_layout ) return;
if (!function_exists('videoPosterScripts')) {
	function videoPosterScripts()
	{
		wp_enqueue_style('video-poster-css', get_stylesheet_directory_uri() . '/assets/css/video-poster.css', ['common-css']);
	}
	add_action('wp_enqueue_scripts', 'videoPosterScripts');
}

/* Custom Backgrounds and Spacing */

$layout_name = 'video-poster';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];
$size = !empty($video_width) ? $video_width : 'large';

if(empty($video_id)) {return;}

?>

<section class="<?= $layout_name; ?> <?= $layout_name; ?>--<?= $size; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<article class="video-poster__media">
			<div class="video-poster__media-content">
				<?php
				$id = $video_id; ?>
				<iframe aria-hidden="true" id="video-hub-header-video" width="100%" height="100%" src="//www.youtube.com/embed/<?php echo $id; ?>?rel=0&color=white" frameborder="0" allowfullscreen></iframe>
			</div>
		</article>
	</div>
</section>