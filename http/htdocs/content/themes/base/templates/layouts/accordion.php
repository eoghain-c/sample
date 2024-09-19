<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('accordionScripts')) {
	function accordionScripts()
	{
		wp_enqueue_style('accordion-css', get_stylesheet_directory_uri() . '/assets/css/accordion.css', ['common-css']);
		wp_enqueue_script('accordion-js', get_stylesheet_directory_uri() . '/assets/js/accordion.js', ['ondesign-common-js']);
	}

	add_action('wp_enqueue_scripts', 'accordionScripts');
}
/* Custom Backgrounds and Spacing */
$layout_name = 'accordion';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

switch($show):
	case 'custom' :
		$posts = $custom_accordion;
		break;
	case 'category' :
		$ids = $accordion_category;
		break;
	default:
		$args = array(
			'post_type'       => 'accordion',
			'post_status'     => 'publish',
			'posts_per_page'  => ($show == 'latest' && !empty($count)) ? $count : -1,
			'fields'          => 'ids',
		);
		$ids = new WP_Query($args);
		$ids = $ids->posts;
endswitch;

if(empty($posts) && !empty($ids)){
	$posts = [];
	foreach ($ids as $id){
		$field = get_fields($id);
		$posts [] = [
			'question' => $field['question'],
			'answer' => $field['answer']
		];
	}
}

?>
<section class="<?= $layout_name; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__wrapper section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="accordion__heading accordion__heading--<?= !empty($heading_alignment) ? $heading_alignment : 'left'; ?> accordion__heading--<?= !empty($heading_color) ? $heading_color : 'black'; ?>">
			<?php if(!empty($heading)) {
				echo "<{$heading_element} class='title'>{$heading}</{$heading_element}>";
			} ?>
			<?php compileTemplate('links.php', ['links_data' => $link_array]); ?>
		</div>
		<div class="accordion__inner">
			<?php foreach($posts as $post){ ?>
				<div class="accordion__option">
					<button class="accordion__title" name="Accordion"><?= __($post['question']);?>
						<span class="accordion__chevron"><?php display_icon('arrow-splide'); ?></span>
					</button>
					<div class="accordion__container">
						<div class="accordion__container-inner">
							<div class="accordion__container-content wysiwyg wysiwyg__content">
								<?= wpautop($post['answer']); ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>