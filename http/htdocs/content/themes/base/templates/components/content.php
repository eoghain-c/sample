<?php
/**
 * Uses the following variables to render:
 * @param string  $content_alignment   String for the alignment of the content and links
 * @param string  $content             The WYSIWYG content
 * @param array   $link_array          Clone: Link Array (array of Clone: Link Single objects)
 */

if (!function_exists('contentScripts')) {
	function contentScripts()
	{
		wp_enqueue_style('content-css', get_template_directory_uri() . '/assets/css/content.css', ['common-css']);
	}
	add_action('wp_enqueue_scripts', 'contentScripts');
}

?>
<div class="content content--<?= $content_alignment ?? 'left'; ?>">
	<?php if (!empty($content)) { ?>
		<div class="wysiwyg wysiwyg__content"><?= $content; ?></div>
	<?php } ?>

	<?php
	if (!empty($link_array)) {
		compileTemplate('/templates/components/links.php', array(
			'class' => 'content__links',
			'links_data' => $link_array
		));
	} ?>
</div>