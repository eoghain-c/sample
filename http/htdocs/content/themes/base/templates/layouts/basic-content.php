<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('basicContentScripts')) {
	function basicContentScripts()
	{
		wp_enqueue_style('basic-content-css', get_template_directory_uri() . '/assets/css/basic-content.css', ['common-css']);
	}

	add_action('wp_enqueue_scripts', 'basicContentScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'basic-content';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

?>
<section class="<?= $layout_name; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="<?= $layout_name; ?>__content wysiwyg">
			<?php compileTemplate('/templates/components/content.php', array(
					'content'           => !empty($content) ? $content : '',
					'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
					'link_array'        => !empty($link_array) ? $link_array : []
			));?>
		</div>
	</div>
</section>