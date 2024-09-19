<?php
/**
 * Uses the following variables to render:
 * @param array $cards
 * @param string $style
 * @param string $type
 * @param int $card_count
 * @param int $found_cards_count
 * @param bool $called_from_ajax
 */
if (!function_exists('cardsListingGridScripts')) {
	function cardsListingGridScripts() {
		wp_enqueue_style('cards-listing-grid-css', get_template_directory_uri() . '/assets/css/cards-listing-grid.css', ['common-css']);
	}
	add_action('wp_enqueue_scripts', 'cardsListingGridScripts');
}

// Pre-Process Component
$cards = !empty($cards) ? $cards : [];
$style = !empty($style) ? $style : '';
$type = !empty($type) && !empty($style) ? "{$type}-{$style}" : (!empty($type) ? $type : '');
$called_from_ajax = !empty($called_from_ajax) ? $called_from_ajax : false;
$card_count = !empty($card_count) ? $card_count : 0;
$found_cards_count = !empty($found_cards_count) ? $found_cards_count : 0;
$listing_class = !empty($type) ? (' cards-listing-grid--' . $type) : '';

if (empty($called_from_ajax)): ?>
	<div class="cards-listing-grid<?= $listing_class; ?> js-cards-list">
		<?php compileListingCards($type, $cards, $card_count, $found_cards_count, $style); ?>
	</div>
<?php else:
	compileListingCards($type, $cards, $card_count, $found_cards_count, $style);
endif;