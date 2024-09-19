<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('introBlockScripts')) {
    function introBlockScripts() {
        wp_enqueue_style('intro-block-css', get_stylesheet_directory_uri() . '/assets/css/intro-block.css', ['common-css']);
    }
    add_action('wp_enqueue_scripts', 'introBlockScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'intro-block';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$has_sidebar_class = !empty($sidebar_content) ? $layout_name.'--hasSidebar' : '';
$related_post = !empty($related_post) ? $related_post : '';
?>
<section class="<?= $layout_name; ?> <?= $has_sidebar_class; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
    <div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<div class="<?= $layout_name; ?>__container">

			<div class="<?= $layout_name; ?>__content">
				<?php compileTemplate('/templates/components/content.php', array(
					'content'           => !empty($content) ? $content : '',
					'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
					'link_array'        => !empty($link_array) ? $link_array : []
				)); ?>

				<?php if (!empty($related_post)): ?>
				<div class="<?= $layout_name; ?>__post-info"><?php outputPostInfo($related_post); ?></div>
				<?php endif; ?>
			</div>

			<?php
			if (!empty($sidebar_content)) { ?>
				<div class="<?= $layout_name; ?>__sidebar">
				<?= $sidebar_content; ?>
				</div>
			<?php } ?>

		</div>
    </div>
</section>