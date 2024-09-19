<?php
if(empty($id)) return;
/**
 * Requires the following variables to render:
 * $id                  {ID}    => Post ID
 * Optional
 * $picture_override   {array}  => Allows the card image to be overwritten in case addition crop sizes are needed [optional]
 */

$data = get_fields($id);
// Card Scripts
if (!function_exists('eventCardScripts')) {
	function eventCardScripts()
	{
		wp_enqueue_style('event-card-css', get_template_directory_uri() . '/assets/css/event-card.css', ['common-css']);
	}
	
	add_action('wp_enqueue_scripts', 'eventCardScripts');
}

$data['media']['image_sizes'] = [
	'1280'     => '425x500',
	'768'     => '600x300',
	'0'     => '725x275'
];

if(!empty($card_data_id)) {
	$Events = new Events();
	$event_date = $Events->get_card_date($card_data_id);
}

?>
<article class="event-card">
	<div class="event-card__wrapper">
		<?php
		if (!empty($meta)) { ?>
			<div class="meta <?= $meta; ?>"></div>
			<?php
		}
		?>
		<div class="event-card__media">
			<?php compileTemplate('media', $data['media']); ?>
		</div>
		<div class="event-card__content-wrapper">
					<h3 class="event-card__title title"><?= !empty($data['title'])? $data['title'] : get_the_title($id); ?></h3>
					<div class="event-card__amenities">
						<?php
						// Event Date
						if ($event_date['event_start_date']): ?>
							<div class="event-card__amenity event-card__amenity--date">
								<div class="event-card__amenity-label"><?php display_icon('icon-calendar-3');?><?= $event_date['event_start_date'] ?><?= !empty($event_date['event_end_date']) ? ' - '.$event_date['event_end_date'] : '';?></div>
							</div>
						<?php endif;

						// Event Time
						if (!empty($data['hours'])): ?>
							<div class="event-card__amenity event-card__amenity--hours">
								<div class="event-card__amenity-label"><?php display_icon('icon-clock-2');?><?= $data['hours']; ?></div>
							</div>
						<?php endif;

						// Event Location
						if (!empty($data['cost'])): ?>
							<div class="event-card__amenity event-card__amenity--cost">
								<div class="event-card__amenity-label"><?php display_icon('icon-cost');?><?= $data['cost']; ?></div>
							</div>
						<?php endif; ?>
					</div>
				<?php
				if(!empty($data['content'])){ ?>
					<div class="event-card__content wysiwyg"><?= $data['content']; ?></div>
				<?php } ?>
				<?php
				if (!empty($data['link_array'])): ?>
					<div class="event-card__buttons">
						<?php
						compileTemplate('/templates/components/links.php', array(
							'class' => 'content__links',
							'links_data' => $data['link_array'],
						));
						?>
					</div>
				<?php endif; ?>
			</div>
	</div>
</article>