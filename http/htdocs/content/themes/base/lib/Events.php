<?php

use JetBrains\PhpStorm\NoReturn;

class Events
{
	public function events_filter_query($dates = '', $limit = 999, $calendar = false): false|array
	{
		if (empty($dates)) {
			return false;
		}

		global $EventsManager;
		$start = date('Y-m-d H:i:s', strtotime($dates['first_day']));
		$end = date('Y-m-d 23:59:59', strtotime($dates['last_day']));
		$events = array();
		
		// Select all of the events within the specified date range
		if($calendar) {
			$eventOccurrences = $EventsManager->getEventsByDate($start, $end, $limit);
		} else {
			$eventOccurrences = $EventsManager->getUniqueEventsByDate($start, $end, $limit);
		}
		
		if (!empty($eventOccurrences)) {
			foreach ($eventOccurrences as $eventOccurrence) {
				// ID array for the events
				$events[] = [
					'ID' => $eventOccurrence->post_id, 'nextDate' => $this->get_next_date($eventOccurrence->event_date),
					'eventTime' => $this->get_time($eventOccurrence->event_date)
				];
			}
		}
		return $events;
	}
	
	public function get_categories(): array // return the categories list
	{
		return get_categories(array('taxonomy' => 'event_types'));
	}
	
	public function get_month_data($now = ''): false|array
	{
		if (empty($now)) {
			return false;
		}
		$first_day_of_month = date("Y-m-01", strtotime($now));
		$last_day_of_month = date("Y-m-t", strtotime($now));
		$number_day = date("t", strtotime($now));
		$off_set = date('w', strtotime($first_day_of_month));
		$rows = ceil(($off_set + $number_day) / 7);
		$month_year = date('F Y', strtotime($now));
		$full_month = date('F', strtotime($now));
		$month = date('m', strtotime($now));
		$other = $this->prev_next_month($now);
		return [
			'selected' => $now, // 2021-02-06
			'first_day' => $first_day_of_month, //2021-02-01
			'last_day' => $last_day_of_month, //2021-02-28
			'off_set' => $off_set, //1 (First day offset from Sunday)
			'number_days' => $number_day, // 28 (total number of days in the month)
			'rows' => $rows, // 5 (number of calendar rows needed)
			'days' => $rows * 7, // 35 (number of calendar days needed)
			'month_year' => $month_year, //February 2021
			'full_month' => $full_month, // February
			'month' => $month, // 02
			'year' => $other['year'], // 2021
			'next_month' => $other['next'], // 2021-3-01
			'prev_month' => $other['prev'] // 2021-1-01
		];
	}
	
	public function prev_next_month($date = ''): false|array
	{
		if (empty($date)) {
			return false;
		}
		$date = strtotime($date);
		$year = date('Y', $date);
		$next = date('m', $date) + 1;
		
		if ($next > 12) {
			$next = '01';
			$nextYear = $year + 1;
		} else {
			$nextYear = $year;
		}
		$prev = date('m', $date) - 1;
		if ($prev < 1) {
			$prevYear = $year - 1;
			$prev = 12;
		} else {
			$prevYear = $year;
		}
		$day = '01';
		
		return [
			'next' => "{$nextYear}-{$next}-{$day}",
			'prev' => "{$prevYear}-{$prev}-{$day}",
			'year' => $year
		];
	}
	
	public function event_by_day($results): array
	{
		$days = [];
		foreach ($results as $key => $day) {
			$today = date('j', strtotime($day['nextDate']));
			if (array_key_exists($today, $days)) {
				$days[$today][] = $day;
			} else {
				$days[$today] = [$day];
			}
		}
		return $days;
	}
	
	public function get_daily_header($date = ''): false|string
	{
		if (empty($date)) {
			return false;
		}
		$month = date("F", strtotime($date));
		$day = date("l", strtotime($date));
		$dayNum = $this->get_date_oi(date("j", strtotime($date)));
		return "{$day}, {$month} {$dayNum}";
	}
	
	public function get_data($value): false|array
	{
		if (empty($value)) {
			return false;
		}
		return $this->set_data($value);
	}
	
	private function set_data($value): false|array
	{
		if (empty($value)) {
			return false;
		}
		
		$id = $value['ID'] ?? $value;
		$time = get_field('event_schedule', $id);
		$post = get_post($id);
		//return $this->set_terms(get_the_terms($id, 'event_types'));
		
		$timeString = date('g:ia', strtotime($time[0]['start_date']));
		
		return [
			'id' => $id,
			'next_date' => $time['nextDate'] ?? '',
			'terms' => $this->get_terms($id),
			'time' => $timeString,
			'title' => $post->post_title ?? '',
			'url' => get_permalink($id) ?? '',
		];
	}
	
	public function get_terms($id): false|string
	{
		if (empty($id))
			return false;
		
		$category_terms = !empty(get_the_terms($id, 'event_types')) ? get_the_terms($id, 'event_types') : [];
		$location_terms = !empty(get_the_terms($id, 'event_locations')) ? get_the_terms($id, 'event_locations') : [];
		
		return $this->set_terms(array_merge($location_terms, $category_terms));
		
	}
	
	private function set_terms($terms): false|string
	{
		if (empty($terms)) {
			return false;
		}
		
		$str = '';
		foreach ($terms as $term) {
			if (!empty($term->slug)) {
				$str .= "{$term->slug} ";
			}
		}
		return $str;
	}
	
	public function get_date_oi($day)
	{
		$ab = 'th';
		if ($day == '1' || $day == '21' || $day == '31') {
			$ab = 'st';
		} else if ($day == '2' || $day == '22') {
			$ab = 'nd';
		} else if ($day == '3' || $day == '23') {
			$ab = 'rd';
		}
		return "$day$ab";
	}
	
	public function get_next_date($schedule): false|string
	{
		if (!$schedule) {
			return false;
		}
		$startDate = strtotime($schedule);
		return date('F j, Y', $startDate);
	}
	
	public function get_time($event): false|string
	{
		if (!$event) {
			return false;
		}
		$startDate = strtotime($event);
		return date('H:i', $startDate);
	}
	
	public function get_event_list($date, $limit = 999): array
	{
		global $EventsManager;
		$end = date('Y-m-d', strtotime('+1 year'));

		$events = array();
		
		// Select all the events within the specified date range
		$eventOccurrences = $EventsManager->getUniqueEventsByDate($date, $end, $limit);
		
		if (!empty($eventOccurrences)) {
			foreach ($eventOccurrences as $eventOccurrence) {
				if (empty($events[$eventOccurrence->post_id]) && !empty($eventOccurrence->post_id)) {
					// ID array for the events
					$events[$eventOccurrence->post_id] = [
						'ID' => $eventOccurrence->post_id,
						'nextDate' => $this->get_next_date($eventOccurrence->event_date),
						'eventTime' => $this->get_time($eventOccurrence->event_date)
					];
				}
			}
		}
		
		return $events;
	}

	public function get_featured_date($data_id): string
	{
		if (empty($data_id['ID'])) { return ''; }
		$next_event_string = '';

		global $EventsManager;
		$eventOccurrences = new ArrayIterator($EventsManager->getNextEventOccurrences($data_id['ID']));

		if (!empty($eventOccurrences->current()->event_date)) {
			$next_event_string = date('F jS, Y', strtotime($eventOccurrences->current()->event_date));
		}

		return $next_event_string;
	}
	
	public function get_card_date($data_id): array
	{
		// Get Fields
		$event_schedule = get_field('event_schedule', $data_id['ID']);
		
		// Get Start and End Dates
		$start_date = date('M j', strtotime($event_schedule[0]['start_date']));
		$not_empty_end_date = !empty($event_schedule[0]['end_date']);
		$end_date = $not_empty_end_date ? date('M j', strtotime($event_schedule[0]['end_date'])) : '';
		$next_date_start = strtotime($data_id['nextDate']);
		$event_duration = strtotime($end_date) - strtotime($start_date);
		$end_not_equal_to_start = $not_empty_end_date && ($start_date < $end_date);
		
		// Card Date Fields
		$event_start_date = date('F j', $next_date_start);
		$event_end_date = $end_not_equal_to_start ? date('F j', $next_date_start + $event_duration) : '';
		
		return array(
			'event_start_date' => $event_start_date,
			'event_end_date' => $event_end_date,
		);
	}
	
	public function display_card($data_ids, $style = 'default'): void
	{
		foreach ($data_ids as $id) {
			if (!empty($id['ID'])) {

				if ($this->get_terms($id['ID'])) {
					$category_terms = !empty(get_the_terms($id['ID'], 'event_types')) ? get_the_terms($id['ID'], 'event_types') : [];
					$time_terms = !empty(get_the_terms($id['ID'], 'event_locations')) ? get_the_terms($id['ID'], 'event_locations') : [];

					$terms_merged = array_merge($time_terms, $category_terms);

					$meta = implode(' ', wp_list_pluck($terms_merged, 'slug'));

				} else {
					$meta = 'no-cat';
				}

				if($style == 'horizontal') {
					compileTemplate('card-event-daily', array('id' => $id['ID'], 'meta' => $meta,'card_data_id' => $id));
				}else {
					compileTemplate('card-event', array('id' => $id['ID'], 'meta' => $meta,'card_data_id' => $id));
				}
			}
		}
	}
	
	public function display_calendar($dates, $events): void
	{
		compileTemplate('/templates/components/calendar-view.php', array(
			'events' => $events,
			'dates' => $dates,
		));
	}
}

$eventsList = new Events();

function events_day(): void
{
	global $eventsList;

	
	$style = !empty($_POST['style']) ? $_POST['style'] : '';
	$start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
	$end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
	
	ob_start();
	$events_selected = $eventsList->events_filter_query(array('first_day' => $start_date, 'last_day' => $end_date));
	$eventsList->display_card($events_selected, $style);
	$new_cards = ob_get_clean();
	$count = count($events_selected);
	
	
	$results['count'] = '<span class="events__count-events js-count-events" data-total="' . $count . '">Showing <span class="events__count-number">'.$count.'</span> of <span class="events__count-number">'.$count.'</span> Results</span>';
	$results['cards'] = $new_cards;

	if(wp_doing_ajax()) {
		wp_send_json($results);
		wp_die();
	} else {
		$eventsList->display_card($events_selected, $style);
		echo $results['count'];
	}
}

function events_calendar(): void
{
	global $eventsList;
	$selected = date('Y-m-d');
	if (!empty($_POST['calendar_date'])) {
		$selected = $_POST['calendar_date'];
	}

	$dates = $eventsList->get_month_data($selected);
	
	$events = $eventsList->event_by_day($eventsList->events_filter_query($dates, 999, true));

	$eventsList->display_calendar($dates, $events);
	
	if (wp_doing_ajax()) wp_die();
}

function events_list($limit = 999): void
{
	global $eventsList;
	
	$post_limit = !empty($_POST['limit']) ? $_POST['limit'] : $limit;
	$category = !empty($_POST['category']) ? $_POST['category'] : 'all';
	$location = !empty($_POST['location']) ? $_POST['location'] : 'all';
	$offset = !empty($_POST['offset']) ? $_POST['offset'] : 0;
	$style = !empty($_POST['style']) ? $_POST['style'] : '';
	
	$events = $eventsList->get_event_list(date("Y-m-d"));
	$events_offset = [];
	
	$i = 0;
	foreach ($events as $event) {
		
		if (!empty($category) && $category !== 'all' && !has_term($category, 'event_types', $event['ID'])) continue;
		
		if (!empty($location) && $location !== 'all' && !has_term($location, 'event_locations', $event['ID']))	continue;
		
		if ($i++ < $offset) continue;
		if ($i > $offset + $post_limit) break;
		
		$events_offset[] = $event;
	}
	
	if($category !== 'all' || $location !== 'all') {
		$events_total = count($events_offset);
	} else {
		$events_total = count($events);
	}
	
	$events_shown = $offset + count($events_offset);
	
	$eventsList->display_card($events_offset, $style);
	
	// Adds a hidden element for the event count
	echo '<span class="events__count-events js-count-events" data-total="' . $events_total . '">Showing <span class="events__count-number">'.$events_shown.'</span> of <span class="events__count-number">'.$events_total.'</span> Results</span>';
	
	if (wp_doing_ajax()) wp_die();
}

function events_calendar_list($limit = 999): void
{
	global $eventsList;
	
	$post_limit = !empty($_POST['limit']) ? $_POST['limit'] : $limit;
	$category = !empty($_POST['category']) ? $_POST['category'] : 'all';
	$location = !empty($_POST['location']) ? $_POST['location'] : 'all';
	$offset = !empty($_POST['offset']) ? $_POST['offset'] : 0;
	
	$events = $eventsList->get_event_list(date("Y-m-d"));
	$events_offset = [];
	
	$i = 0;
	foreach ($events as $event) {

		if (!empty($category) && $category !== 'all' && !has_term($category, 'event_types', $event['ID'])) continue;
		
		if (!empty($location) && $location !== 'all' && !has_term($location, 'event_locations', $event['ID']))	continue;
		
		if ($i++ < $offset) continue;
		if ($i > $offset + $post_limit) break;
		
		$events_offset[] = $event;
	}
	
	if($category !== 'all' || $location !== 'all') {
		$events_total = count($events_offset);
	} else {
		$events_total = count($events);
	}
	
	$events_shown = $offset + count($events_offset);
	
	$eventsList->display_card($events_offset);
	
	// Adds a hidden element for the event count
	echo '<span class="events__count-events js-count-events" data-total="' . $events_total . '">Showing <span class="events__count-number">'.$events_shown.'</span> of <span class="events__count-number">'.$events_total.'</span> Results</span>';
	
	if (wp_doing_ajax()) wp_die();
}

add_action('wp_ajax_events_list_call', 'events_list');
add_action('wp_ajax_nopriv_events_list_call', 'events_list');

add_action('wp_ajax_events_calendar_call', 'events_calendar');
add_action('wp_ajax_nopriv_events_calendar_call', 'events_calendar');

add_action('wp_ajax_events_day_call', 'events_day');
add_action('wp_ajax_nopriv_events_day_call', 'events_day');