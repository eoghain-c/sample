<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('amenitiesScripts')) {
	function amenitiesScripts()
	{
		wp_enqueue_style('amenities-css', get_stylesheet_directory_uri() . '/assets/css/amenities.css', ['common-css', 'splide-css']);
	}

	add_action('wp_enqueue_scripts', 'amenitiesScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'amenities';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$icons = $amenity_selection;
if(empty($icons)){ return false; }

?>
<section class="<?= $layout_name; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="<?= $layout_name; ?>__wrapper" >
		<?php // Content Area ?>
		<?php if(!empty($content) || !empty($link_array)) { ?>
			<div class="amenities__content-wrapper">
				<?php compileTemplate('content.php', array(
						'content'           => !empty($content) ? $content : '',
						'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
						'link_array'        => !empty($link_array) ? $link_array : []
				)); ?>
			</div>
		<?php } ?>

		<?php // Amenity Icons ?>
		<div class="amenities__icons">
			<?php
			foreach ($icons as $icon) {
				$icon_svg = !empty(get_field('icon', $icon)) ? get_field('icon', $icon) : '';
				?>
				<div class="amenities__icon-block">
					<?php if (!empty($icon_svg['icon']) && $icon_svg['icon'] != 'none') { ?>
						<div class="amenities__icon">
							<?php display_icon($icon_svg['icon']); ?>
						</div>
					<?php } ?>

					<div class="amenities__icon-content">
						<?php if (!empty($icon->name)) { ?>
							<div class="amenities__icon-name">
								<?php echo esc_html( $icon->name ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
		</div>
	</div>
</section>