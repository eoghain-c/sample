<?php
if (!function_exists('bookingWidgetScripts')) {
	function bookingWidgetScripts() {
		wp_enqueue_style('booking-widget-css', get_stylesheet_directory_uri() . '/assets/css/booking-widget.css', ['common-css']);
		wp_enqueue_script('booking-widget-js', get_stylesheet_directory_uri() . '/assets/js/booking-widget.js', ['ondesign-common-js']);
	}
	add_action('wp_enqueue_scripts', 'bookingWidgetScripts');
}

// General Settings - Booking Link
$settings = get_fields('options');
$booking_text = !empty($settings['booking_text']) ? $settings['booking_text'] : 'Book Today';
$booking_source = !empty($settings['booking_source']) ? $settings['booking_source'] : '';

// Booking Widget Position
$position = !empty($position) ? $position : '';
$classes = '';

if ($position == 'bw--hero') {
	$classes = ' booking-widget--hero';
} elseif ($position == 'bw--sticky') {
	$classes = ' booking-widget--sticky';
} else {
	$position = 'bw--default';
	$classes = ' booking-widget--default';
}

// Accommodations & Offers
$rate_code = !empty($rate_code) ? $rate_code : 'Standard';
$room_code = !empty($room_code) ? $room_code : '';

?>
<article class="booking-widget<?= $classes; ?>" data-position="<?= $position; ?>">

	<div class="booking-widget__inner">


			<button class="booking-widget__close js-booking-close" aria-label="Close Booking Widget" data-location="<?= $position; ?>"><?php display_icon('close'); ?>Close</button>
		<?php if ($position == 'bw--hero') { ?>
			<span class="submission-error"></span>
		<?php } ?>

		<form action="https://res.windsurfercrs.com/ibe/index.aspx" method="GET" target="_blank" autocomplete="off" class="booking-widget__form js-booking-form" name="bookingWidget">
			<input type="hidden" name="propertyID" value="16583" />
			<input type="hidden" name="checkin" value="" required />
			<input type="hidden" name="checkout" value="" required />
			<input type="hidden" name="adults" value="2" min="1" max="9" />
			<input type="hidden" name="child1" value="0" min="0" max="9" />
			<input type="hidden" name="child2" value="0" min="0" max="9" />
			<input type="hidden" name="child3" value="0" min="0" max="9" />

			<div class="booking-widget__fields">

				<?php if ($position != 'bw--hero') { ?>
					<span class="submission-error"></span>
				<?php } ?>

				<?php // Dates
				if ($position == 'bw--hero'): ?>
					<div class="booking-widget__select booking-widget__select--dates">
						<span class="booking-widget__label">Arrival & Departure</span>

						<button class="booking-widget__select-area js-easepick-button" type="button" aria-label="Select Date">
							<?php display_icon('calendar'); ?>
							<span class="booking-widget__select-text js-arrival-date">Select Date</span>
							<span class="booking-widget__select-text booking-widget__select-text--separator"><?php display_icon('arrow-booking-widget'); ?></span>
							<span class="booking-widget__select-text js-departure-date">Select Date</span>
							<?php //display_icon('arrow'); ?>
						</button>

						<?php // Easepick ?>
						<div class="easepick-container">
							<div class="easepick-container__inner">
								<div class="easepick-container__bind"></div>
							</div>
						</div>
					</div>
				<?php else: ?>
					<div class="booking-widget__select booking-widget__select--arrival">
						<span class="booking-widget__label">Arrival</span>
						<button class="booking-widget__select-area js-easepick-button" type="button" aria-label="Select Arrival Date">
							<?php display_icon('calendar'); ?>
							<span class="booking-widget__select-text js-arrival-date">Select Date</span>
							<?php //display_icon('arrow'); ?>
						</button>
					</div>

					<div class="booking-widget__select booking-widget__select--departure">
						<span class="booking-widget__label">Departure</span>
						<button class="booking-widget__select-area js-easepick-button" type="button" aria-label="Select Departure Date">
							<?php display_icon('calendar'); ?>
							<span class="booking-widget__select-text js-departure-date">Select Date</span>
							<?php //display_icon('arrow'); ?>
						</button>

						<?php // Easepick ?>
						<div class="easepick-container">
							<div class="easepick-container__inner">
								<div class="easepick-container__bind"></div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php // Guests ?>
				<div class="booking-widget__select booking-widget__select--guests">
					<span class="booking-widget__label">Guests</span>
					<button class="booking-widget__select-area js-guests-button"
							type="button" rel="booking-widget__guests" aria-label="Add Guests"
							data-id="booking-widget__guests" >
						<?php display_icon('guests'); ?>
						<span class="booking-widget__select-text js-guests-text">2 Adults</span>
					</button>

					<div class="booking-widget__guests js-guests-container">
						<div class="booking-widget__guests-inner">

							<div class="booking-widget__occupancy booking-widget__occupancy--adults">
								<span class="booking-widget__guests-label">Adults</span>
								<div class="booking-widget__numberpicker js-booking-numberpicker" data-input-name="adults">
									<button class="booking-widget__numberpicker-btn js-numberpicker-sub"
											type="button" aria-label="Decrease adults">
										<?php display_icon('minus'); ?>
									</button>
									<div class="booking-widget__numberpicker-value js-numberpicker-value">2</div>
									<button class="booking-widget__numberpicker-btn js-numberpicker-add"
											type="button" aria-label="Increase adults">
										<?php display_icon('plus'); ?>
									</button>
								</div>
							</div>

							<div class="booking-widget__occupancy booking-widget__occupancy--children">
								<span class="booking-widget__guests-label">Children (0 - 3 yrs)</span>
								<div class="booking-widget__numberpicker js-booking-numberpicker" data-input-name="child1">
									<button class="booking-widget__numberpicker-btn js-numberpicker-sub"
											type="button" aria-label="Decrease children (0 - 3 yrs)">
										<?php display_icon('minus'); ?>
									</button>
									<div class="booking-widget__numberpicker-value js-numberpicker-value">0</div>
									<button class="booking-widget__numberpicker-btn js-numberpicker-add"
											type="button" aria-label="Increase children (0 - 3 yrs)">
										<?php display_icon('plus'); ?>
									</button>
								</div>
							</div>

							<div class="booking-widget__occupancy booking-widget__occupancy--children">
								<span class="booking-widget__guests-label">Children (4 - 12 yrs)</span>
								<div class="booking-widget__numberpicker js-booking-numberpicker" data-input-name="child2">
									<button class="booking-widget__numberpicker-btn js-numberpicker-sub"
											type="button" aria-label="Decrease children (4 - 12 yrs)">
										<?php display_icon('minus'); ?>
									</button>
									<div class="booking-widget__numberpicker-value js-numberpicker-value">0</div>
									<button class="booking-widget__numberpicker-btn js-numberpicker-add"
											type="button" aria-label="Increase children (4 - 12 yrs)">
										<?php display_icon('plus'); ?>
									</button>
								</div>
							</div>

							<div class="booking-widget__occupancy booking-widget__occupancy--children">
								<span class="booking-widget__guests-label">Children (13 - 17 yrs)</span>
								<div class="booking-widget__numberpicker js-booking-numberpicker" data-input-name="child3">
									<button class="booking-widget__numberpicker-btn js-numberpicker-sub"
											type="button" aria-label="Decrease children (13 - 17 yrs)">
										<?php display_icon('minus'); ?>
									</button>
									<div class="booking-widget__numberpicker-value js-numberpicker-value">0</div>
									<button class="booking-widget__numberpicker-btn js-numberpicker-add"
											type="button" aria-label="Increase children (13 - 17 yrs)">
										<?php display_icon('plus'); ?>
									</button>
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>
			<button class="booking-widget__submit js-submit-booking link link__btn" type="submit"><?= $booking_text; ?></button>
		</form>
	</div>
</article>