<?php
/**
 * LINKS
 *
 * @param array  $links_data      Data from 'Clone: Link Single' or 'Clone: Link Array'
 * @param string $class           [optional] Add a class to the wrapper div
 * @param bool   $include_wrapper [optional] Default: true.  Remove wrapper div if set to false
 *
 * HOW TO USE:
 * compileTemplate('links', ['links_data' => $link_array])
 * OR
 * compileTemplate('links', ['links_data' => $link_array, 'class' => 'footer__links'])
 */

if ( empty($links_data) ) return;

/* COMPILE DATA */

// Set default for $include_wrapper
if ( !isset($include_wrapper) ) $include_wrapper = true;

// If 'Clone: Link Single's' acf field group data is passed, wrap in an array to match the 'Clone: Link Array' data structure
if ( !isset($links_data[0]) ) {
	$tempLinksData = $links_data;
	$links_data = [];
	$links_data[0] = $tempLinksData;
}

// Convert field data to link parameter values
$links = [];

foreach ( $links_data as $i => $link ) {

	switch ( $link['link_type'] ) {

		case 'link' :
			if ( !empty($link['link']['title']) && !empty($link['link']['url']) ) {
				$links[$i]['link_text'] = $link['link']['title'];
				$links[$i]['url'] = $link['link']['url'];
				$links[$i]['link_style'] = ( !empty($link['link_style']) ) ? 'class="' . $link['link_style'] . '"' : '';
				$relString = ( !empty($link['link']['target']) ) ? 'noopener ' : '';
				$relString .= !empty($no_index) ? 'noindex ' : '';
				$relString .= !empty($no_follow) ? 'nofollow' : '';
				$links[$i]['target'] = ( !empty($link['link']['target']) ) ? 'target="' . $link['link']['target'] . '" rel="'.$relString.'"' : 'target="_self"'.((!empty($relString)) ? ' rel="'.$relString.'"' : '');
			}
			break;

		case 'file' :
			if ( !empty($link['link_text']) && !empty($link['link_file']) ) {
				$links[$i]['link_text'] = $link['link_text'];
				$links[$i]['url'] = $link['link_file'];
				$links[$i]['link_style'] = ( !empty($link['link_style']) ) ? 'class="' . $link['link_style'] . '"' : '';
				$links[$i]['target'] = 'target="_blank"';
			}
			break;

		case 'email' :
			if ( !empty($link['link_text']) && !empty($link['link_email']) ) {
				$links[$i]['link_text'] = $link['link_text'];
				$links[$i]['url'] = 'mailto:' . $link['link_email'];
				$links[$i]['link_style'] = ( !empty($link['link_style']) ) ? 'class="' . $link['link_style'] . '"' : '';
				$links[$i]['target'] = 'target="_self"';
			}
			break;

		case 'phone' :
			if ( !empty($link['link_text']) && !empty($link['link_phone']) ) {
				$links[$i]['icon'] = strpos($link['link_style'], 'link__text') !== false ? 'phone' : '';
				$links[$i]['link_text'] = $link['link_text'];
				$links[$i]['url'] = 'tel:' . $link['link_phone'];
				$links[$i]['link_style'] = ( !empty($link['link_style']) ) ? 'class="' . $link['link_style'] . (!empty($links[$i]['icon']) ? ' link__with-icon' : '') . '"' : '';
				$links[$i]['target'] = 'target="_self"';
			}
			break;

		case 'booking' :
			if ( !empty($link['link_style']) && !empty($link['link_style']) ) {
				$links[$i]['link_type'] = 'booking';
				$links[$i]['link_text'] = !empty($link['link_text']) ? $link['link_text'] : 'Reserve Now';
				$links[$i]['link_style'] = !empty($link['link_style']) ? 'class="js-booking-btn ' . $link['link_style'] . '"' : 'class="js-booking-btn link__btn link__btn--white"';
			}
			break;

	}
}

/* OUTPUT DATA */

if ( !empty($links) ) { ?>

	<?php if ( $include_wrapper == true ) { ?>
		<div class="links <?= $class ?>">
	<?php } ?>

	<?php foreach ( $links as $link ) { ?>

		<?php if ( !empty($link['url']) && !empty($link['link_text']) ) { ?>
			<a href="<?= $link['url'] ?>" <?= $link['link_style'] ?> title="<?= $link['link_text'] ?>" <?= $link['target'] ?>><span><?php if (!empty($link['icon'])) { display_icon($link['icon']); } ?><?= $link['link_text'] ?></span></a>
		<?php } ?>

		<?php if (!empty($link['link_type']) && $link['link_type']=='booking') { ?>
			<button <?= $link['link_style'] ?> title="<?= $link['link_text'] ?>"> <span><?= $link['link_text'] ?></span></button>
		<?php } ?>

	<?php } ?>

	<?php if ( $include_wrapper == true ) { ?>
		</div>
	<?php } ?>

<?php } ?>