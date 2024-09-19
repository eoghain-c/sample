<?php

if (!function_exists('layoutCustomizing')) {
	/**
	 * Custom function to connect layouts with the Clone: Layout Settings ACF
	 *
	 */
	function layoutCustomizing($args = '', $name = '')
	{
		if (empty($name)) {
			return false;
		}

		// Custom Layout ID
		$data['custom_id'] = !empty($args['layout_id']) ? ' id="' . $args['layout_id'] . '"' : '';

		// Section Spacer
		if ($args['layout_spacing'] == 'preset') {
			$data['spacer'] = "section-spacer--top-{$args['top_spacing']} section-spacer--bottom-{$args['bottom_spacing']}";
		} else {
			$data['spacer'] = "section-spacer--top-none section-spacer--bottom-none";
		}

		// Backgrounds
		$data['background_class'] = '';

		if (!empty($args['background_gradient']) && $args['background_gradient'] !== 'none') {
			$data['background_class'] = 'background background-gradient--' . $args['background_gradient'];
		}

		return $data;
	}
}

if (!function_exists('generateCPTLink')) {
	/**
	 * Generate an internal link that matches our ACF links for a post using get_the_permalink.
	 * For use with links.php component.
	 *
	 * @param $post
	 * @param string $link_text custom link text, default: 'View Details'
	 * @param string $link_style custom classes to be added to the link, default: 'link link__btn link__btn--white'
	 * @return array
	 */
	function generateCPTLink($post, string $link_text = 'View Details', string $link_style = 'link link__btn link__btn--white'): array
	{
		return array(
			'link_type' => 'link',
			'link_style' => $link_style,
			'link' => array(
				'url' => get_the_permalink($post),
				'target' => '',
				'title' => $link_text
			),
		);
	}
}

if (!function_exists('outputPostInfo')) {
	/**
	 * Generate post info block for outputting in layouts such as Intro Block
	 *
	 * @param $id
	 * @return string
	 */
	function outputPostInfo($id, $print = true): string {
		$post_type = !empty($id) ? get_post_type($id) : '';
		$card = get_fields($id);

		$card_stats = '';
		switch ($post_type) {
			case 'accommodations':
				$sqft = !empty($card['sqft']) ? $card['sqft'] : '';
				$beds = !empty($card['beds']) ? $card['beds'] : '';
				$capacity = !empty($card['capacity']) ? $card['capacity'] : '';
				if (!empty($sqft)||!empty($beds)||!empty($capacity)) {
					$card_stats =  '<ul class="post-info">';
					if (!empty($sqft)) { $card_stats .= '<li class="post-info__stat">'.get_icon('sqft').$sqft.' SQ/FT</li>'; }
					if (!empty($beds)) { $card_stats .= '<li class="post-info__stat">'.get_icon('bed').$beds.'</li>'; }
					if (!empty($capacity)) { $card_stats .= '<li class="post-info__stat">'.get_icon('guests').$capacity.'</li>'; }
					$card_stats .= '</ul>';
				}
				break;
			case 'activities':
			case 'courses':
				$hours = !empty($card['hours']) ? $card['hours'] : '';
				$cost = !empty($card['cost']) ? $card['cost'] : '';
				if (!empty($hours)||!empty($cost)) {
					$card_stats =  '<ul class="post-info">';
					if (!empty($hours)) { $card_stats .= '<li class="post-info__stat">'.get_icon('clock').$hours.'</li>'; }
					if (!empty($cost)) { $card_stats .= '<li class="post-info__stat">'.get_icon('cost').$cost.'</li>'; }
					$card_stats .= '</ul>';
				}
				break;
			case 'restaurants':
				$hours = !empty($card['hours']) ? $card['hours'] : '';
				$disclaimer = !empty($card['hours_disclaimer_text']) ? $card['hours_disclaimer_text'] : '';
				if (!empty($hours)||!empty($disclaimer)) {
					$card_stats =  '<ul class="post-info">';
					if (!empty($hours)) { $card_stats .= '<li class="post-info__stat">'.get_icon('clock').$hours.'</li>'; }
					$card_stats .= '</ul>';
					if (!empty($disclaimer)) { $card_stats .= '<div class="post-info__disclaimer">'.$disclaimer.'</div>'; }
				}
				break;
			case 'venues':
				$sqft = !empty($card['sqft']) ? $card['sqft'] : '';
				$dimensions = !empty($card['dimensions']) ? $card['dimensions'] : '';
				$capacity = !empty($card['capacity']) ? $card['capacity'] : '';
				if (!empty($sqft)||!empty($capacity)) {
					$card_stats =  '<ul class="post-info">';
					if (!empty($sqft)) { $card_stats .= '<li class="post-info__stat">'.get_icon('sqft').$sqft.' SQ/FT</li>'; }
					if (!empty($dimensions)) { $card_stats .= '<li class="post-info__stat">'.get_icon('dimensions').$dimensions.'</li>'; }
					if (!empty($capacity)) { $card_stats .= '<li class="post-info__stat">'.get_icon('guests').'Up to '.$capacity.'</li>'; }
					$card_stats .= '</ul>';
				}
				break;
			case 'events':
				$hours = !empty($card['hours']) ? $card['hours'] : '';
				$event_date = !empty($card['event_date']) ? $card['event_date'] : '';
				$cost = !empty($card['cost']) ? $card['cost'] : '';
				if (!empty($hours)||!empty($event_date)||!empty($cost)) {
					$card_stats =  '<ul class="post-info">';
					if (!empty($hours)) { $card_stats .= '<li class="post-info__stat">'.get_icon('clock').$hours.'</li>'; }
					if (!empty($event_date)) { $card_stats .= '<li class="post-info__stat">'.get_icon('calendar').$event_date.'</li>'; }
					if (!empty($cost)) { $card_stats .= '<li class="post-info__stat">'.get_icon('cost').$cost.'</li>'; }
					$card_stats .= '</ul>';
				}
			default:
				break;
		}

		if (!empty($print)) {
			print_r($card_stats);
			return '';
		}

		return $card_stats;
	}
}