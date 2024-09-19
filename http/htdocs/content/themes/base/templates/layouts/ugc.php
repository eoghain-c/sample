<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('ugcScripts')) {
	function ugcScripts() {
		wp_enqueue_style('ugc-css', get_template_directory_uri() . '/assets/css/ugc.css', ['common-css']);
	}
	add_action('wp_enqueue_scripts', 'ugcScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'ugc';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

$company_info = get_field('company_info', 'option');
$socials = $company_info['social_links'];

$ugc = get_field('ugc', 'option');
$gs_images = $ugc['custom_images'];
$ugc_images = [];
$display_type = !empty($display_type) ? $display_type : 'default';
switch ($display_type) {
	case 'gallery':
		if (!empty($gallery_id)) {
			$images = get_field('gallery', $gallery_id);
			foreach($images as $image) {
				if (count($ugc_images)<8) {
					array_push($ugc_images, $image);
				}
			}
			foreach($gs_images as $image) {
				if (count($ugc_images)<8) {
					array_push($ugc_images, $image);
				}
			}
		} else { return; }
		break;
	case 'custom':
		$images = $custom_images;
		foreach($images as $image) {
			if (count($ugc_images)<8) {
				array_push($ugc_images, $image);
			}
		}
		foreach($gs_images as $image) {
			if (count($ugc_images)<8) {
				array_push($ugc_images, $image);
			}
		}
		break;
	default: // General Settings
		foreach($gs_images as $image) {
			if (count($ugc_images)<8) {
				array_push($ugc_images, $image);
			}
		}
}

?>
<section class="<?= $layout_name; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="<?= $layout_name; ?>-gallery">

			<?php
			$block_count = 0;
			foreach($ugc_images as $ugc_image) {
				$block_count++;

				// Print Image Block
				echo '<div class="'.$layout_name.'-image '.$layout_name.'-image--'.$block_count.'">';
				$data = array(
					'sources'  => array(
						1920 => $ugc_image['sizes']['575x550'], // Largest seen on 1920 (574x534)
						768  => $ugc_image['sizes']['775x375'], // Estimated 33% of 1920 (634x270)
						0    => $ugc_image['sizes']['775x375'], // Estimated 60% of 768 (460x180)
					),
					'fallback' => $ugc_image['url'],
					'alt_text' => $ugc_image['alt'],
					'class'    => 'basic-picture basic-picture--ugc'
				);
				compileTemplate('picture', $data);
				echo '</div>';

				// Print Content Block after 4
				if ($block_count==4) {
					$gs_tag = $ugc['tag_overwrite'];
					$gs_tag_url = $ugc['tag_url'];
					$tag = !empty($tag_overwrite) ? $tag_overwrite : $gs_tag;
					$tag_url = !empty($tag_url) ? $tag_url : $gs_tag_url;
					echo '<div class="'.$layout_name.'-content">';
					echo '  <span class="'.$layout_name.'-content__text">Stay & Share at ';
					echo '	<a class="'.$layout_name.'-content__tag" href="'.$tag_url.'" title="Stay & Share at '.$tag_url.'" target="_blank" rel="noopener">'.$tag.'</a>';
					echo '	</span>';
					echo '  <div class="'.$layout_name.'-content__socials">';
					compileTemplate('socials.php', ['additional_classes' => 'socials--ugc']);
					echo '  </div>';
					echo '</div>';
				}
			}
			?>

		</div>
	</div>
</section>