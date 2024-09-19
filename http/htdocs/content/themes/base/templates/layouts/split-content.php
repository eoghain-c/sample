<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('splitContentScripts')) {
    function splitContentScripts()
    {
        wp_enqueue_style('split-content-css', get_stylesheet_directory_uri() . '/assets/css/split-content.css', ['common-css']);
    }

    add_action('wp_enqueue_scripts', 'splitContentScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'split-content';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$content_position = !empty($content_position) ? $content_position : 'right';
$display_content = !empty($display) ? $display : 'stacked';
$split_content_row = !empty($split_content_row) ? $split_content_row : [];
$image_sizes = [
	'1920'  => '1900x800',
	'1280'  => '1250x750',
	'768'   => '725x550',
	'0'     => '775x375'
];

if (!empty($split_content_row)): ?>
<section class="<?= $layout_name; ?> <?= $layout_name; ?>--<?= $content_position; ?> <?= $display_content === 'stacked' ? $layout_name.'--stacked' : $layout_name.'--slider'; ?>
 <?= $display_content === 'slider' && empty($slider_peek) ? $layout_name.'--no-peek' : ''; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
    <div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">

		<?php foreach ($split_content_row as $row): ?>
			<?php if ($display_content === 'slider') { ob_start(); }?>
			<div class="<?= $layout_name; ?>__row">

				<div class="<?= $layout_name; ?>__media-wrapper">
					<?php // Event
					if ($row['row_type'] === 'event' && !empty($row['featured_event'])) {
						$media = get_field('media', $row['featured_event']);
					} else { // Custom
						$media = $row['row_media'];
					}

					$media['image_sizes'] = $image_sizes;
					compileTemplate('media', $media); ?>
				</div>

				<div class="<?= $layout_name; ?>__content-wrapper">
					<?php // Event
					if ($row['row_type'] === 'event' && !empty($row['featured_event'])) {
						$featured_event_content = get_field('featured_event_content', $row['featured_event']);

						if (!empty($featured_event_content)) {
							$content = get_field('featured_content', $row['featured_event']);
						} else {
							$links = get_field('links', $row['featured_event']);
							$content['content'] = get_field('content', $row['featured_event']);
							$content['content_alignment'] = 'left';
							$content['link_array'] = !empty($links['link_array']) ? $links['link_array'] : [];
						}

						$title = !empty(get_field('title', $row['featured_event'])) ? get_field('title', $row['featured_event']) : get_the_title($row['featured_event']);
						$event_hours = get_field('hours', $row['featured_event']);
						$reservation = get_field('reservation', $row['featured_event']);
						$Events = new Events();
						$event_date = $Events->get_featured_date(array('ID' => $row['featured_event']));
						?>
						<span class="<?= $layout_name; ?>__event-overline overline"><?= $display_content === 'stacked' ? 'Featured Event' : 'Signature Event'; ?> </span>
						<h2 class="<?= $layout_name; ?>__event-title title"><?= $title; ?></h2>
						<div class="<?= $layout_name; ?>__event-info">
							<?php if($display_content === 'stacked') { ?>
								<?php if (!empty($event_hours)): ?>
							<div class="<?= $layout_name; ?>__event-hours"><?php display_icon('icon-clock-3'); ?><span><?= $event_hours; ?></span></div>
							<?php endif; ?>
							<?php if (!empty($event_hours) && !empty($event_date)): ?>
							<div class="<?= $layout_name; ?>__event-info-separator">|</div>
							<?php endif; ?>
							<?php if (!empty($event_date)): ?>
							<div class="<?= $layout_name; ?>__event-date"><?php display_icon('icon-calendar-3'); ?><span><?= $event_date; ?></span></div>
							<?php endif; ?>
							<?php } else { ?>
								<?php if (!empty($event_date)): ?>
									<div class="<?= $layout_name; ?>__event-date"><span>When: </span><?= $event_date; ?></div>
								<?php endif; ?>
								<?php if (!empty($event_hours)): ?>
									<div class="<?= $layout_name; ?>__event-hours"><span>Time: </span><?= $event_hours; ?></div>
								<?php endif; ?>
								<?php if (!empty($reservation)): ?>
									<div class="<?= $layout_name; ?>__event-reservation"><span>Reservation Required</span></div>
								<?php endif; ?>
							<?php } ?>
						</div>
					<?php
					} else { // Custom
						$content = $row['row_content'];
					}

					compileTemplate('content', array(
							'content'           => !empty($content['content']) ? $content['content'] : '',
							'content_alignment' => !empty($content['content_alignment']) ? $content['content_alignment'] : 'left',
							'link_array'        => !empty($content['link_array']) ? $content['link_array'] : []
					)); ?>
				</div>
			</div>
			<?php if($display_content === 'slider') {
				$slider_data['slider_content'][] = ob_get_clean();
				} ?>
	    <?php endforeach; ?>

	    <?php if($display_content === 'slider') {
			if(!empty($slider_peek)) {
				$slider_data['default'] = '{"type":"loop", "perPage":1, "perMove": 1, "pagination":false, "trimSpace":false, "focus" : "right", "gap":"10px", "padding":{"left" : "0px", "right" : "546px"}}';
				$slider_data['breakpoints'] = array(
					'1919 => {"perPage":"1","gap":"0px", "focus" : "right", "padding":{"left" : "0px", "right" : "200px"} }',
					'1279 => { "perPage":"1","gap":"0px", "focus":"center", "padding": {"left" : "0px", "right" : "0px"}}'
				);
			} else {
				$slider_data['default'] = '{"type":"loop", "perPage":1, "perMove": 1, "pagination":false, "trimSpace":false, "focus" : "right", "gap":"10px", "padding":{"left" : "0px", "right" : "0px"}}';
				$slider_data['breakpoints'] =  array(
					'1919 => {"perPage":"1","gap":"0px", "focus" : "right", "padding":{"left" : "0px", "right" : "0px"} }',
					'1279 => { "perPage":"1","gap":"0px", "focus":"center", "padding": {"left" : "0px", "right" : "0px"}}'
				);
			}
		    compileTemplate('splide-wrapper', $slider_data);
	    } ?>

    </div>
</section>
<?php endif; ?>