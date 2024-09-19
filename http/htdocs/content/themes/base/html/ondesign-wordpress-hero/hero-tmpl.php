<?php


// Want to change this file?
// Create a file in [theme]/html/ondesign-wordpress-hero/hero-tmpl.php
// then copy the contents of this file there, and make your customizations there


/**
 * Gets all of the hero items with the image sources
 *
 * This also includes setting up the fallback image for
 * videos (this function will determine if the item type is
 * a video, and set the background image as fallback image)
 */
list($hero_items, $hero_size, $is_custom_sizes, $preloader_colour) = get_hero_items();

if( !empty($hero_items) ):

	/**
	 * Base theme 2.0 update
	 *
	 * @link https://jira.ondesigninteractive.com/browse/VWP000-7
	 * @since 2.2.0
	 */

	$enable_mobile = get_field( 'enable_mobile_video', 'option' );

	if (!function_exists('heroScripts')) {
		function heroScripts($enable_mobile)
		{
			wp_enqueue_style('ondesign-hero', plugins_url() . '/ondesign-wordpress-hero/assets/dist/css/ondesign-hero.min.css', ['splide-css'], ONDESIGN_HERO_VERSION);
			wp_enqueue_script('ondesign-hero', plugins_url() . '/ondesign-wordpress-hero/assets/dist/js/ondesign-hero.js', ['splide-js'], ONDESIGN_HERO_VERSION);

			wp_localize_script( 'ondesign-hero', 'hero_localization', array(
				'enable_mobile' =>  $enable_mobile
			));
			// Your overrides
			// wp_enqueue_style('ondesign-hero-override', get_template_directory_uri() . '/assets/css/ondesign-hero-override.css', ['ondesign-hero']);
			wp_enqueue_script('hero-js', get_template_directory_uri() . '/assets/js/hero.js');


			// If uncommented, this will become the arguments for the hero splide object
//       wp_localize_script('ondesign-hero', 'vwh_config', [
			//   'autoplay' => 1
//       ]);
		}

		add_action('wp_enqueue_scripts', function() use ($enable_mobile) { heroScripts($enable_mobile); });
		//      add_action('wp_enqueue_scripts', 'heroScripts');
	}

	// Booking Widget
	$hero_widget = get_field('hero_widget');
	$has_widget = !empty($hero_widget) && $hero_widget != 'none';
	$has_booking = ($hero_widget == 'booking') ? 1 : 0;

	//	Icon
	$hero_icon = get_field('hero_icon');
	$has_icon = !empty($hero_icon) && $hero_icon != 'none';

	$preload_opacity = get_field('preloader_opacity', 'options');
	$video_autoslide = get_field('auto_slide_video', 'options');

	if ( $preload_opacity ) {
		$preload_opacity = $preload_opacity / 100;
	}
	else {
		$preload_opacity = 1;
	}

	$has_booking_class = !empty($has_booking) ? 'hero--has-booking' : '';

	?>




	<section id="hero" class="hero <?= $has_booking_class; ?> <?php print strtolower($hero_size); ?><?= !empty($has_widget) ? ' hero--has-widget' : ''; ?> <?= !empty($has_icon) ? ' hero--has-icon' : ''; ?>" data-video-autoslide="<?php echo $video_autoslide; ?>">

		<div
			class="hero-splide splide"
		>
			<div class="splide__track" data-splide-el="track">
				<ul class="splide__list">
					<?php foreach( $hero_items as $i => $hero ):

						$background_type 	= $hero['background_type'];
						$image_center 		= !empty($hero['hero_image_center_point']) ? $hero['hero_image_center_point'] : 50;

						// Array of CSS classes that will be applied to the item div
						$css_classes = array(
							// Default classes
							'splide__slide',
							'item',

							// In ondesign-hero.php, the inline css that includes the
							// background images will only apply to elements with .image-active.
							// That way we can toggle image-active with javascript to
							// dynamically show the image on mobile devices
							$background_type == 'video' ? 'item-video' : 'image-active',

							// item type
							sprintf('item-%s', $background_type),

							// The index of the hero item, used for css selecting
							sprintf('item-%d', $i)
						);

						if ( !empty($hero['content_position']) ) {
							$css_classes[] =  $hero['content_position'];
						}

						?>

						<!-- Slider item -->
						<li class="<?php print implode(' ', $css_classes); ?>">
							<?= get_field('gradient_overlay') == 'top' ? '<div class="gradient-top"></div>' : '' ?>
							<?= get_field('gradient_overlay') == 'bottom' ? '<div class="gradient-bottom"></div>' : '' ?>
							<?= get_field('gradient_overlay') == 'both' ? '<div class="gradient-top"></div> <div class="gradient-bottom"></div>' : '' ?>

							<?php
							$breakpoints            = get_breakpoints();
							$this_size_breakpoints  = [];

							// This also acts as the fallback image for videos that can't play
							printf('<picture class="item-background" style="margin-left: %d%%">'.PHP_EOL, $image_center);
							if ( !empty($breakpoints) && is_array($breakpoints) ) {

								$breakpoints            = array_reverse($breakpoints);
								$biggest                = $breakpoints[0];

								foreach( $breakpoints as $breakpoint ) {
									if ( $breakpoint->size_name == $hero_size ) {
										$this_size_breakpoints[] = $breakpoint;
										printf(
											'<source %ssrcset="%s" media="(min-width: %dpx)">'.PHP_EOL,
											$i > 0 ? 'data-' : '',
											$hero['bg_image_srcs'][$breakpoint->thumbnail_name]->src,
											$breakpoint->min_width
										);
									}
								}

							}

							if ( !empty($this_size_breakpoints[0]) ) $biggest = $this_size_breakpoints[0];

							printf(
								'<img class="item-background__img" src="%s" loading="lazy" alt="%s" width="%d" height="%d" style="transform: translateX(-%d%%);" />'.PHP_EOL,
								$hero['bg_image_srcs'][$biggest->thumbnail_name]->src,
								$hero['bg_image_srcs'][$biggest->thumbnail_name]->alt,
								$hero['bg_image_srcs'][$biggest->thumbnail_name]->width,
								$hero['bg_image_srcs'][$biggest->thumbnail_name]->height,
								$image_center
							);
							printf('</picture>'.PHP_EOL);
							?>

							<?php if ( $background_type == 'video' ):
								$show_controls 	= $hero['show_controls']; ?>
								<video <?php if ( !$video_autoslide || count($hero_items) == 1 ) echo ' loop=""'; ?> playsinline preload="none" muted="" width="1600" height="900" style="visibility: hidden;" poster="<?php if( isset($hero['poster']) ) print $hero['poster']; ?>">
									<source src="<?php print $hero['background_video_mp4']['url']; ?>" type="video/mp4; codecs=avc1.42E01E,mp4a.40.2">
								</video>
								<?php if ($show_controls):
								$dir = plugins_url() . '/ondesign-wordpress-hero/assets/icons/'; ?>
								<div class="preloader" style="background-color: <?php print $preloader_colour; ?>; opacity: <?php echo $preload_opacity; ?>"><div class="loader"></div></div>
								<div class="hero__media-controls" >
									<button class="hero__pause-button js-hero-pause" aria-label="Pause"><img src="<?php echo $dir; ?>pause.svg" class="v-icon__svg" alt="pause hero video"></button>
									<button class="hero__play-button js-hero-play" aria-label="Play"><img src="<?php echo $dir; ?>play.svg" class="v-icon__svg" alt="play hero video"></button>
								</div>
							<?php endif;?>
							<?php endif; ?>

							<!-- Inner, hero content -->
							<div class="hero-content">
								<?php print wpautop($hero['item_content']); ?>
							</div>

						</li>

					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<?php // Sticky Booking Widget Button
		if (!empty($has_booking) && locate_template('templates/components/booking-widget.php')) { ?>
			<button class="link link__btn hero-booking-btn js-booking-btn"><?= __('Book Today'); ?></button>
		<?php } ?>
		<?php
		/* === RENDER BOOKING HERO === */
		if (!empty($has_booking) && locate_template('templates/components/booking-widget.php')) {
			compileTemplate('booking-widget', ['position' => 'bw--hero']);
		}

		/* === RENDER HERO ICON === */
		if (!empty($has_icon)) { ?>
			<div class="hero__icon">
			 <?php display_icon('gazebo'); ?>
			</div>
		<?php
		}
		?>

	</section>
<?php endif; ?>
