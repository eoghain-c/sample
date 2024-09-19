<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('scatteredImagesScripts')) {
	function scatteredImagesScripts() {
		wp_enqueue_style('scattered-images-css', get_stylesheet_directory_uri() . '/assets/css/scattered-images.css', ['common-css']);
	}
	add_action('wp_enqueue_scripts', 'scatteredImagesScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'scattered-images';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$main_media_size = !empty($main_media_size) ? $main_media_size : 'default';
$content_pos_class = !empty($content_position) ? $layout_name.'--content-left' : $layout_name.'--content-right';
$content_style = !empty($content_style) ? $layout_name.'--content-wide' : $layout_name.'--content-split';
$extra_images_position = !empty($extra_images_position) ? $extra_images_position : 'off';
$layout_class = !empty($extra_images_position) && $extra_images_position != 'off' && $content_style == $layout_name.'--content-wide' ? $layout_name.'--extra-images-'.$extra_images_position : '';
?>
<section class="<?= $layout_name; ?> <?= $layout_class; ?> <?= $content_pos_class; ?> <?= $content_style; ?> <?= $settings['background_class'] ?? ''; ?>" <?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="<?= $layout_name; ?>__container">

			<div class="<?= $layout_name; ?>__content <?= $layout_name; ?>__content--media-<?= $main_media_size; ?>">

				<div class="<?= $layout_name; ?>__main-image <?= $layout_name; ?>__main-image--<?= $main_media_size; ?>">
					<?php
					$image_sizes = [
						'1920'  => $main_media_size === 'large' ? '1475x750' : '1300x600',
						'768'   => $main_media_size === 'large' ? '1150x525' : '975x425',
						'0'     => $main_media_size === 'large' ? '725x425' : '725x275'
					];
					$main_media['image_sizes'] = $image_sizes;
					compileTemplate('media', $main_media);
					?>
				</div>
				<?php if($content_style == $layout_name.'--content-wide'):  ?>
				<?php compileTemplate('content', array(
					'content'           => !empty($content) ? $content : '',
					'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
					'link_array'        => !empty($link_array) ? $link_array : []
				)); ?>
				<?php endif; ?>
				<?php if ($extra_images_position !== 'off' && $content_style == $layout_name.'--content-split' ): ?>
					<div class="<?= $layout_name; ?>__extra-images <?= $layout_name; ?>__extra-images--tablet">

						<?php if (!empty($extra_image_1)): ?>
							<div class="<?= $layout_name; ?>__extra-image <?= $layout_name; ?>__extra-image-1">
								<?php
								compileTemplate('picture', array(
									'sources'  => array(
										1920 => $extra_image_1['sizes']['1550x700'],
										768  => $extra_image_1['sizes']['1075x350'],
										0    => $extra_image_1['sizes']['725x275'],
									),
									'fallback' => $extra_image_1['url'],
									'alt_text' => $extra_image_1['alt'],
									'class'    => 'basic-picture basic-picture--scattered-images'
								));
								?>
							</div>
						<?php endif; ?>

						<?php if (!empty($extra_image_2)): ?>
							<div class="<?= $layout_name; ?>__extra-image <?= $layout_name; ?>__extra-image-2">
								<?php
								compileTemplate('picture', array(
									'sources'  => array(
										1920 => $extra_image_2['sizes']['675x550'],
										0    => $extra_image_2['sizes']['725x275'],
									),
									'fallback' => $extra_image_2['url'],
									'alt_text' => $extra_image_2['alt'],
									'class'    => 'basic-picture basic-picture--scattered-images'
								));
								?>
							</div>
						<?php endif; ?>

					</div>
				<?php endif; ?>

			</div>

			<?php if($content_style == $layout_name.'--content-split'):  ?>
				<?php compileTemplate('content', array(
					'content'           => !empty($content) ? $content : '',
					'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
					'link_array'        => !empty($link_array) ? $link_array : []
				)); ?>
			<?php endif; ?>

			<?php if ($extra_images_position !== 'off' && $content_style == $layout_name.'--content-split' ): ?>
				<div class="<?= $layout_name; ?>__extra-images <?= $layout_name; ?>__extra-images--mobile">

					<?php if (!empty($extra_image_1)): ?>
						<div class="<?= $layout_name; ?>__extra-image <?= $layout_name; ?>__extra-image-1">
							<?php
							compileTemplate('picture', array(
								'sources'  => array(
									1920 => $extra_image_1['sizes']['1550x700'],
									768  => $extra_image_1['sizes']['1075x350'],
									0    => $extra_image_1['sizes']['725x275'],
								),
								'fallback' => $extra_image_1['url'],
								'alt_text' => $extra_image_1['alt'],
								'class'    => 'basic-picture basic-picture--scattered-images'
							));
							?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ($extra_images_position !== 'off' && $content_style == $layout_name.'--content-wide' ): ?>
			<div class="<?= $layout_name; ?>__extra-images <?= $layout_name; ?>__extra-images--<?= $extra_images_position; ?>">

				<?php if (!empty($extra_image_1)): ?>
				<div class="<?= $layout_name; ?>__extra-image <?= $layout_name; ?>__extra-image-1">
					<?php
					compileTemplate('picture', array(
						'sources'  => array(
							1920 => $extra_image_1['sizes']['1550x700'],
							768  => $extra_image_1['sizes']['1075x350'],
							0    => $extra_image_1['sizes']['725x275'],
						),
						'fallback' => $extra_image_1['url'],
						'alt_text' => $extra_image_1['alt'],
						'class'    => 'basic-picture basic-picture--scattered-images'
					));
					?>
				</div>
				<?php endif; ?>

				<?php if (!empty($extra_image_2)): ?>
				<div class="<?= $layout_name; ?>__extra-image <?= $layout_name; ?>__extra-image-2">
					<?php
					compileTemplate('picture', array(
						'sources'  => array(
							1920 => $extra_image_2['sizes']['675x550'],
							0    => $extra_image_2['sizes']['725x275'],
						),
						'fallback' => $extra_image_2['url'],
						'alt_text' => $extra_image_2['alt'],
						'class'    => 'basic-picture basic-picture--scattered-images'
					));
					?>
				</div>
				<?php endif; ?>

			</div>
			<?php endif; ?>

		</div>
	</div>
</section>