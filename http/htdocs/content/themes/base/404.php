<?php
if (!function_exists('notFoundScripts')) {
	function notFoundScripts()
	{
		wp_enqueue_style('not-found-css', get_template_directory_uri() . '/assets/css/not-found.css', ['common-css']);
	}

	add_action('wp_enqueue_scripts', 'notFoundScripts');
}


$message = get_field('404_message', 'option');
$link = get_field('404_link', 'option');
$include_sitemap = get_field('include_sitemap_on_404', 'option');


// Compile page
ob_start(); ?>
	<article class="not-found bottom-spacing side-spacing">
		<div class="not-found__inner">
			<?php

			if (!empty($message)) {
				echo $message;
			}

			if (!empty($link)) {
				compileTemplate('links.php', ['links_data' => $link, 'class' => 'not-found__link']);
			} ?>
		</div>
	</article>

<?php
if ($include_sitemap) {
	compileTemplate('sitemap.php');
}
$_404 = ob_get_clean();


// Output page
compileTemplate(get_template_directory() . '/index.php', array('page_content' => $_404), true);