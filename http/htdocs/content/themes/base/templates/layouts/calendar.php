<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('calendarScripts')) {
	function calendarScripts()
	{
		wp_enqueue_style('calendar-css', get_stylesheet_directory_uri() . '/assets/css/calendar.css', ['common-css', 'easepick-css']);
		wp_enqueue_style('cards-listing-grid-css', get_template_directory_uri() . '/assets/css/cards-listing-grid.css', ['common-css']);
		wp_enqueue_script('calendar-js', get_stylesheet_directory_uri() . '/assets/js/calendar.js', ['ondesign-common-js', 'easepick-js']);
	}
	add_action('wp_enqueue_scripts', 'calendarScripts');
}

$layout_name = 'events';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$first_load_limit = 6;
$iteration_limit = 6;
$show_filters = !empty($show_filters);
$show_month_calendar = $show_filters && !empty($show_month_calendar);

/* Events */
$Events = new Events();
?>
<section class="events <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="events__loading">
		<div class="events__loading-background"></div>
		<div class="loader"></div>
	</div>
	<div class="events__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<?php // View
		if ($show_filters) { ?>
			<div class="events__filters-mobile-btn-container">
				<div class="events__filters-mobile-btn js-mobile-filters-btn" aria-label="Open Filters Popup">
					<h3 class="events__filters-mobile-title">Filter By</h3>
					<?php display_icon('arrow'); ?>
				</div>
			</div>
			<div class="events__filters-container js-filters-wrapper">
				<div class="events__filters-wrapper">
					<button class="events__filters-close js-filters-close" aria-label="Close Filters">
						<?php display_icon('close', 'Close'); ?>
					</button>
					<h2 class="title events__filter-title">Filter By:</h2>
					<?php // Date ?>
					<div class="events__date-container">
						<button class="events__filter events__filter--date js-datepicker-show"
						        title="Show Datepicker">
							Event Date <?php display_icon('arrow'); ?>
						</button>

						<?php // Easepick ?>
						<div class="events__datepicker_wrapper events__single-day js-datepicker-wrapper easepick-container">
							<div class="easepick-container__inner">
								<div class="easepick-container__bind easepick-container__bind-list  js-events-dates"></div>
							</div>
						</div>
					</div>
					<?php // Type ?>
					<div class="events__filter events__category-container">
						<select class="events__filter-select js-filter-select js-events-category"
						        data-type="category"
						        aria-label="Event Type">
							<option selected value="all">Event Type</option><?php
							$categories = $Events->get_categories();
							foreach ($categories as $category) {
								?>
								<option value="<?= $category->slug; ?>"><?= $category->name; ?></option>
							<?php } ?>
						</select>
						<?php display_icon('arrow'); ?>
					</div>
					<?php // Location ?>
					<div class="events__filter events__location-container">
						<select class="events__filter-select js-filter-select js-events-location"
								data-type="location"
								aria-label="Event Location">
							<option selected value="all">Event Times</option>
							<?php
							$categories = get_categories(array('taxonomy' => 'event_locations'));
							foreach ($categories as $category) {
								?>
								<option value="<?= $category->slug; ?>"><?= $category->name; ?></option>
							<?php } ?>
						</select>
						<?php display_icon('arrow'); ?>
					</div>
					<?php // View
					if ($show_month_calendar) {
						?>
						<div class="events__view-btn_wrapper">
							<button
								class="events__view-btn events__view-btn--list js-events-view js-events-view--list is-active"
								value="events-list" title="Events List">
								<?php display_icon('icon-grid'); ?>Card
							</button>
							<button
								class="events__view-btn events__view-btn--month js-events-view js-events-view--month"
								value="events-month" title="Month">
								<?php display_icon('icon-calendar-2'); ?>Calendar
							</button>
							<button
									class="events__view-btn events__view-btn--calendar-list js-events-view js-events-view--calendar-list"
									value="events-calendar-list" title="Calendar List">
								<?php display_icon('icon-calendar-list'); ?>List
							</button>
						</div>
					<?php } ?>
					<button class="events__filters-clear-btn js-clear-filters-btn" aria-label="Clear Filters"
					        tabindex="0" disabled>Clear Filters</button>
				</div>
				<div class="events__tags-clear-container">
					<div class="events__tags-title js-filters-title">Selected Filters:</div>
					<div class="events__filter-tag-wrap js-filter-tag-wrap">
						<button class="events_filter-tag js-tag js-date-tag"
								data-type="date" tabindex="0"></button>
						<button class="events_filter-tag js-tag js-category-tag"
								data-type="category" tabindex="0"></button>
						<button class="events_filter-tag js-tag js-location-tag"
								data-type="location" tabindex="0"></button>
					</div>
				</div>
			</div>
		<?php } if ($show_filters) ?>
		<span class="events__event-counter"></span>
		<div class="events__wrapper">
			<div class="events__list js-events-list js-view">
				<div class="events__list-cards cards-listing-grid cards-listing-grid--events js-events-list-cards"
					 data-limit="<?= $iteration_limit; ?>">
					<?php
					events_day(); //todo: make sure that on load you get the eevents count & then make sure everything else works and
					//todo: is good to push up :)
					?>
				</div>
				<button class="link link__btn link__btn--bluish events__load-more js-events-load-more">
					Load More
				</button>
			</div>

			<div class="events__calendar-list js-events-calendar-list js-view">
				<?php
				/*
					All events load here.
					Order is based off of ICPO
					Comment out the HTML block if the List is not required.
					Updated the JS to call the new default view.
				*/
				?>
				<div class="events__calendar-list-inner">
					<div class="events__calendar-list-cards-wrapper">
						<div class="events__calendar-list-cards cards-listing-grid cards-listing-grid--events js-events-calendar-list-cards"
							 data-limit="<?= $iteration_limit; ?>">
						</div>
						<button class="link link__btn link__btn--bluish events__load-more js-events-load-more">
							Load More
						</button>
					</div>

					<div class="easepick-container">
						<div class="easepick-container__inner">
							<div class="easepick-container__bind easepick-container__bind-day-list easepick-container__inline js-events-dates"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="events__day-list js-events-day js-view">
				<?php
				/*
					Daily calendar loads area
					Comment out the HTML block if the daily is not required.
				*/
				?>
				<div class="cards-listing-grid cards-listing-grid--events events__single-day">
					<div class="wysiwyg events__single-day-no-results js-events-no-results">
						<?= !empty($no_results_message) ? $no_results_message : 'Sorry, there are no events for your selection.'; ?>
					</div>
				</div>
			</div>
			<?php // View
			if ($show_month_calendar) {
				?>
				<div class="events__calendar js-events-calendar js-view">
					<?php
					/*
						Monthly calendar load area
						Comment out the HTML block if the calendar is not required.
					*/
						events_calendar();
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>