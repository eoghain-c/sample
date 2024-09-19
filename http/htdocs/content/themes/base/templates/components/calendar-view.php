<?php

if (empty($dates)) {
	return false;
}

// Vars base setup.
global $eventsList;
$count = 0;
$offset = $dates['off_set'];
$days = $dates['days'];
$maxDays = $dates['number_days'];
$day = 1;
$display = 3;
$weekday = 0;
$current = "{$dates['year']}-{$dates['month']}-";
$week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
?>
<div class="events-month">

	<div class="events__month-header">
		<div>
			<button aria-label="Previous Month" class="events__button events__prev" tabindex="n"
					data-date="<?= $dates['prev_month']; ?>">
				<?php display_icon('arrow'); ?>
			</button>
		</div>
		<h2 class="current-month"><?= $dates['month_year']; ?></h2>
		<div>
			<button aria-label="Next Month" class="events__button events__next" tabindex="n"
					data-date="<?= $dates['next_month']; ?>"><?php display_icon('arrow'); ?>
			</button>
		</div>
	</div>

	<div class="events__days-heading events__calendar-grid">
		<div><span class="day-name">Sunday</span><span class="day-ab">S</span></div>
		<div><span class="day-name">Monday</span><span class="day-ab">M</span></div>
		<div><span class="day-name">Tuesday</span><span class="day-ab">T</span></div>
		<div><span class="day-name">Wednesday</span><span class="day-ab">W</span></div>
		<div><span class="day-name">Thursday</span><span class="day-ab">T</span></div>
		<div><span class="day-name">Friday</span><span class="day-ab">F</span></div>
		<div><span class="day-name">Saturday</span><span class="day-ab">S</span></div>
	</div>

	<div class="events__days-wrapper">
		<div class="events__days events__calendar-grid">
			<?php
			//Output Calendar days
			while ($count < $days) {
				?>
				<div class="events__day <?= $week[$count % 7]; ?>" data-day="<?= $current . $day; ?>">
					<?php
					// Output day information after offset
					if ($count >= $offset && $day <= $maxDays) { ?>
						<div class="calendar_day-wrapper">
							<span class="calendar_day"><?= $day; ?></span>
						</div>
						<?php
						// Output the Day information
						if (!empty($events[$day])) {
							$total = count($events[$day]);
							$style = " mb-only";
							//Output the Events inside the container
							$totalEvents = 1;
							$all_events = '';
							?>
							<div class="events__daily-events js-daily-events">
								<?php
								$all_events = '<div class="events__today-date">' . $week[$count % 7] . ', ' . $dates['full_month'] . ' ' . $eventsList->get_date_oi($day) . '</div>';
								foreach ($events[$day] as $key => $value) {

									$data = $eventsList->get_data($value['ID']);
									$event_category_class = !empty($data['terms']) ? $data['terms'] : '';

									$event_location_list = get_the_terms($value['ID'], 'event_locations');
									$event_location_class = !empty($event_location_list) ? implode(' ', wp_list_pluck($event_location_list, 'slug')) : '';
									?>
									<div
										class="calendar-event js-calendar-event js-calendar-day-event <?= $totalEvents > $display ? ' hide' : ''; ?>">
										<div
											class="meta <?= $event_category_class ?><?= $event_location_class ?>"></div>
										<a class="calendar-event__title"
										   href="<?= !empty($data['url']) ? $data['url'] : '' ?>"><?= !empty($data['time']) ? $data['time'].' - ': ''?><?= !empty($data['title']) ? $data['title'] : '' ?></a>
									</div>
									<?php
									ob_start();
									?>
									<tr class="events__daily js-calendar-day-event">
										<td class="events__single">
											<div class="meta <?= !empty($data['terms']) ? $data['terms'] : '' ?>"></div>
											<?= !empty($data['time']) ? $data['time'] : '' ?>
										</td>
										<td>
											<a class="event__name"
											   href="<?= !empty($data['url']) ? $data['url'] : '' ?>" rel="noindex nofollow"><?= !empty($data['title']) ? $data['title'] : '' ?></a>
										</td>
									</tr>
									<?php
									$day_event = ob_get_clean();

									$all_events .= $day_event;
									$totalEvents++;
								}// end Foreach
								if ($weekday >= 6) {
									$weekday = 0;// weekday reset
								} else {
									$weekday++;
								}


								if (!empty($all_events)) { ?>
									<div class="events__view-all-wrap">
										<buton role="button" class="link link__text view-all" href="#">View<span class="view-all--all"> All</span></buton>
									</div>
									<div class="events__today" tabindex="-1">
										<div class="events__today-inner">
											<button class="events__today-close"><?php display_icon('close'); ?></button>
											<table class="events_all-events">
												<?= $all_events; ?>
											</table>
										</div>
									</div>
									<?php
								} // All events if
								?>
							</div>
							<?php
						} // end Day if
						$day++;
					}//end if
					?>
				</div>
				<?php
				$count++;
			} //end Calendar days While
			?>
		</div>
	</div>