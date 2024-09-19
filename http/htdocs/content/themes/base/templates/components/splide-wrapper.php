<?php
/**
 * Splide Wrapper Component:
 *
 * $slider_content  {array}   // Array of html markup for each slide [required]
 * $container_class {string}  // Container class added to splide-wrapper [optional]
 * $arrow_class     {string}  // Arrow class added to each splide__arrow [optional]
 * $arrow           {string}  // Icon name for the arrow used in display_icon($arrow) [optional]
 * $default         {string}  // Splide data attributes Ex. '{"type":"slide", "perPage":1, "perMove": 1}' [optional]
 * $breakpoints     {array}   // Array of additional Splide data attributes for each breakpoint Ex. '1919 => {"perPage":2, "gap":"24px"}' [optional]
 * $custom_js       {bool}    // Determines if the component will initialize splide in the js. Set to 'true' if the splide instance will be initialized elsewhere [optional]
 */

// Layouts that specify custom js will instead init their own splide
if (!function_exists('splideWrapper')) {
	function splideWrapper()
	{
		wp_enqueue_style('splide-wrapper-css', get_stylesheet_directory_uri() . '/assets/css/splide-wrapper.css', ['common-css', 'splide-css']);
		wp_enqueue_script('splide-wrapper-js', get_stylesheet_directory_uri() . '/assets/js/splide-wrapper.js', ['ondesign-common-js', 'splide-js']);

	}
	add_action('wp_enqueue_scripts', 'splideWrapper');
}

if (!empty($slider_content)):

	$container_class = !empty($container_class) ? $container_class : '';
	$arrow_class = !empty($arrow_class) ? $arrow_class : '';
	$arrow = $arrow ?? 'arrow-splide';

	$default = !empty($default) ? $default : '{"type":"slide", "perPage":1, "perMove": 1}';

	if (!empty($breakpoints)) {
		$break = '';
		foreach ($breakpoints as $point) {
			$break .= $point . ";";
		}
	}
	?>
	<article class="splide-wrapper<?= empty($custom_js) ? ' js-splide-wrapper-component ' : ''; ?> <?= $container_class ?>">
		<?php
		// Don't add attributes if there is custom.js
		if (empty($custom_js)) : ?>
		<div class="splide-wrapper__splide splide" data-splide='<?= $default ?? ''; ?>' data-breakpoints='<?= $break ?? ''; ?>'>
			<?php else: ?>
			<div class="splide-wrapper__splide splide">
				<?php endif; // empty($custom_js) ?>
				<div class="splide__arrows">
					<button class="splide__arrow splide__arrow--prev scale <?= $arrow_class ?>" aria-label="previous slide">
						<div class="splide__arrow-icon"><?php display_icon( $arrow ); ?></div>
					</button>
					<button class="splide__arrow splide__arrow--next scale <?= $arrow_class ?>" aria-label="next slide">
						<div class="splide__arrow-icon splide__arrow-icon--next"><?php display_icon( $arrow ); ?></div>
					</button>
				</div>
				<div class="splide-wrapper__track splide__track">
					<ul class="splide-wrapper__cards splide__list">
						<?php foreach ($slider_content as $content): ?>
							<li class="splide-wrapper__card splide__slide">
								<?php print($content); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
	</article>
<?php endif; ?>