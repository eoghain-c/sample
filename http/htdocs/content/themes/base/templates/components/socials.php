<?php
/**
 * Template for adding a video element
 *
 * @param string $additional_classes	[optional] A string of additional classes to add to the component container.
 */

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style('socials-css', get_template_directory_uri() . '/assets/css/socials.css', array('common-css'));
});

$company_info = get_field('company_info', 'option');
$socials = $company_info['social_links'];
$additional_classes = !empty($additional_classes) ? $additional_classes : '';
?>

<?php if (!empty($socials)) { ?>
	<ul class="socials <?= $additional_classes; ?>">
		<?php foreach($socials as $social) { ?>
			<?php if (!empty($social['social_network_link']) && file_exists(dirname(__DIR__) . '/../assets/img/icons/' . $social['social_network']['value'] . '.svg')) { ?>
				<li class="social">
					<a href="<?= $social['social_network_link']?>" class="social__link" target="_blank" title="Link to <?= $social['social_network']['label']; ?>">
						<?php display_icon($social['social_network']['value']); ?>
					</a>
				</li>
			<?php } ?>
		<?php } ?>
	</ul>
<?php } ?>