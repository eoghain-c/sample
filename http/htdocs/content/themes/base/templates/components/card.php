<?php
/**
 * Requires the following variables to render:
 *
 * $id						{ID}      // Post ID of a Custom Post that inherits Clone: Single Card [optional]
 * $card_media_sizes		{string}  // Array of Sizes for Media Overwrite [optional]
 * $card_media				{array}   // Clone: Media [optional]
 * $card_heading			{string}  // Custom card title [optional]
 * $card_content			{string}  // Custom card content [optional]
 */

// Card Scripts
if (!function_exists('cardScripts')) {
	function cardScripts() {
		wp_enqueue_style('card-css', get_template_directory_uri() . '/assets/css/card.css', ['common-css', 'remodal-css']);
		wp_enqueue_script('remodal-js');
	}
	add_action('wp_enqueue_scripts', 'cardScripts');
}

// Pre-Process Component
$id = !empty($id) ? $id : '';
$post_type = !empty($id) ? get_post_type($id) : '';

$card_stats = '';

// Custom Card
$card_media = !empty($card_media) ? $card_media : [];
$card_flag = !empty($card_flag) ? $card_flag : '';
$card_heading = !empty($card_heading) ? $card_heading : '';
$card_overline = !empty($card_overline) ? $card_overline : '';
$card_content = !empty($card_content) ? $card_content : '';
$card_content_content = !empty($card_content['content']) ? $card_content['content'] : '';
$card_content_alignment = !empty($card_content['content_alignment']) ? $card_content['content_alignment'] : 'left';
$card_link_array = !empty($card_content['link_array']) ? $card_content['link_array'] : [];

// Get Fields from ID and Overwrite Custom Block
if (!empty($id)) {
	// Get Fields
	$card = get_fields($id);
	$card_media = !empty($card['card_media']) ? $card['card_media'] : [];
	$card_flag = !empty($card['card_flag']) ? $card['card_flag'] : '';
	$card_heading = !empty($card['card_heading']) ? $card['card_heading'] : get_the_title($id);
	$card_overline = !empty($card['card_overline']) ? $card['card_overline'] : '';
	$card_content = !empty($card['card_content']) ? $card['card_content'] : '';
	$card_content_content = !empty($card_content['content']) ? $card_content['content'] : '';
	$card_content_alignment = !empty($card_content['content_alignment']) ? $card_content['content_alignment'] : 'left';
	$card_link_array = !empty($card_content['link_array']) ? $card_content['link_array'] : [];
}

$title_style = !empty($title_style) ? $title_style : 'default';

// Card Flag
$card_flag_html = !empty($card_flag) ? '<span class="card__flag">'.$card_flag.get_icon('flag-flare').'</span>' : '';

// Gallery
$card_gallery_target = 'card-gallery-'.$post_type.'-'.$id;
$gallery_btn_html = '';
$gallery_slides = array(
		'container_class' => "card__gallery-slider js-card-gallery-slider",
		'slider_content' => array(),
		'custom_js' => false,
		'default' => '{"type":"loop"}',
);
if (!empty($card['post_gallery'])) {
	$i = 0;
	$gallery_btn_html = '<button class="card__gallery-btn" data-remodal-target="'.$card_gallery_target.'">'.get_icon('gallery').'Gallery</button>';
	foreach($card['post_gallery'] as $slide) {
		$slide_html = '';
		$data = array(
				'sources'  => array(
						0    => $slide['url'],
				),
				'fallback' => $slide['url'],
				'alt_text' => $slide['alt'],
				'class'    => 'basic-picture basic-picture--contain basic-picture--card-gallery'
		);
		$slide_html .= compileTemplate('picture', $data, false, false);
		$gallery_slides['slider_content'][$i] = $slide_html;
		$i++;
	}
}

// CUSTOM POST TYPE - SPECIAL CONTENT (Stat Block, Closure Notice, Gallery)
switch ($post_type) {
	case 'page':
		break;
	case 'post':
		break;
	case 'accommodations':
		$sqft = !empty($card['sqft']) ? $card['sqft'] : '';
		$beds = !empty($card['beds']) ? $card['beds'] : '';
		$capacity = !empty($card['capacity']) ? $card['capacity'] : '';
		if (!empty($sqft)||!empty($beds)||!empty($capacity)) {
			$card_stats =  '<ul class="card_stats">';
			if (!empty($sqft)) { $card_stats .= '<li class="card_stat">'.get_icon('sqft').$sqft.' SQ/FT</li>'; }
			if (!empty($beds)) { $card_stats .= '<li class="card_stat">'.get_icon('bed').$beds.'</li>'; }
			if (!empty($capacity)) { $card_stats .= '<li class="card_stat">'.get_icon('guests').$capacity.'</li>'; }
			$card_stats .= '</ul>';
		}
		if(!empty($card['room_id'])) {
			array_unshift($card_link_array, generateBookingLink($card['room_id']));
		}
		break;
	case 'activities':
		$session_length = !empty($card['session_length']) ? $card['session_length'] : '';
		if (!empty($session_length)) {
			$card_stats =  '<ul class="card_stats">';
			if (!empty($session_length)) { $card_stats .= '<li class="card_stat">'.get_icon('clock').$session_length.'</li>'; }
			$card_stats .= '</ul>';
		}
		break;
	case 'courses':
		$hours = !empty($card['hours']) ? $card['hours'] : '';
		$cost = !empty($card['cost']) ? $card['cost'] : '';
		if (!empty($hours)||!empty($cost)) {
			$card_stats =  '<ul class="card_stats">';
			if (!empty($hours)) { $card_stats .= '<li class="card_stat">'.get_icon('clock').$hours.'</li>'; }
			if (!empty($cost)) { $card_stats .= '<li class="card_stat">'.get_icon('cost').$cost.'</li>'; }
			$card_stats .= '</ul>';
		}
		break;
	case 'offers':
		// <code>
		break;
	case 'restaurants':
		$closure_notice = !empty($card['closure_notice']) ? $card['closure_notice'] : '';
		$card_flag_html = !empty($closure_notice) ? '<span class="card__flag card__flag--notice">'.$closure_notice.get_icon('flag-flare').'</span>' : '';
		break;
	case 'shops':
		// <code>
		break;
	case 'venues':
		$sqft = !empty($card['sqft']) ? $card['sqft'] : '';
		$capacity = !empty($card['capacity']) ? $card['capacity'] : '';
		if (!empty($sqft)||!empty($capacity)) {
			$card_stats =  '<ul class="card_stats">';
			if (!empty($sqft)) { $card_stats .= '<li class="card_stat">'.get_icon('sqft').$sqft.' SQ/FT</li>'; }
			if (!empty($capacity)) { $card_stats .= '<li class="card_stat">'.get_icon('guests').'Up to '.$capacity.'</li>'; }
			$card_stats .= '</ul>';
		}
		break;
	case 'events':
		$hours = !empty($card['hours']) ? $card['hours'] : '';
		$event_date = !empty($card['event_date']) ? $card['event_date'] : '';
		$cost = !empty($card['cost']) ? $card['cost'] : '';
		if (!empty($hours)||!empty($event_date)||!empty($cost)) {
			$card_stats =  '<ul class="card_stats">';
			if (!empty($hours)) { $card_stats .= '<li class="card_stat">'.get_icon('clock').$hours.'</li>'; }
			if (!empty($event_date)) { $card_stats .= '<li class="card_stat">'.get_icon('calendar').$event_date.'</li>'; }
			if (!empty($cost)) { $card_stats .= '<li class="card_stat">'.get_icon('cost').$cost.'</li>'; }
			$card_stats .= '</ul>';
		}
}

// Card Sizes / Image Sizes
if (!empty($card_media_sizes)) {
	foreach ( $card_media_sizes as $key => $value ) {
		$card_media['image_sizes'][$key] = $value;
	}
} else {
	$card_media['image_sizes'] = array(
			'1920' => '888x480',
			'1280' => '888x365',
			'768'  => '592x282',
			'0'    => '720x255',
	);
}

?>

<article class="card <?= !empty($post_type) ? "card--{$post_type}" : 'card--custom'; ?>">
	<?php if (!empty($card_media)) { ?>
		<div class="card__media">
			<?php compileTemplate('media', $card_media); ?>
			<?= $card_flag_html; ?>
			<?= $gallery_btn_html; ?>

			<?php if($title_style === 'on-image' && !empty($card_heading)) {?>
				<div class="media-gradient"></div>
				<h3 class="title card-title<?= !empty($gallery_btn_html) ? ' card-title--gallery-btn' : '';?>"><?= $card_heading; ?></h3>
			<?php } ?>
		</div>
	<?php } // End Card Media ?>
	<?php
	if (!empty($card_heading) || !empty($card_content_content) || !empty($card_link_array)) {
		// Prefix the Stat Block
		$card_content_content = !empty($card_stats) ? $card_stats.$card_content_content : $card_content_content;
		// Prefix the Title
		if($title_style === 'default') {
			$card_content_content = !empty($card_heading) ? '<h3 class="title card-title">'.$card_heading.'</h3>'.$card_content_content : $card_content_content;
		}
		// Prefix the Overline
		$card_content_content = !empty($card_overline) ? '<span class="overline">'.$card_overline.'</span>'.$card_content_content : $card_content_content;
		?>
		<div class="card__content">
			<?php compileTemplate('content', array('content_alignment' => $card_content_alignment, 'content' => $card_content_content, 'link_array' => $card_link_array)); ?>
		</div>
	<?php } // End Card Content ?>

	<?php if (!empty($card['post_gallery'])) { ?>
		<div class="card__gallery remodal" data-remodal-id="<?= $card_gallery_target; ?>">
			<button data-remodal-action="close" class="remodal-close"><?php display_icon('close') ?></button>
			<?php compileTemplate('splide-wrapper', $gallery_slides); ?>
		</div>
	<?php } ?>

</article>