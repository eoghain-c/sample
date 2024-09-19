<?php
function footerScripts()
{
	wp_enqueue_style('footer-css', get_template_directory_uri() . '/assets/css/footer.css');
}
add_action('wp_enqueue_scripts', 'footerScripts');

$footer_settings = get_field('footer_settings', 'option');
$secondary_logos = $footer_settings['secondary_logos'];
$copyright_text = $footer_settings['copyright_text'];
$company_info = get_field('company_info', 'option');
$address = $company_info['address'];
$socials = $company_info['social_links'];
?>

  </div> <!-- Close 'after-hero' -->
</main>


<footer class="footer">
	<?php compileTemplate('cookie-banner'); ?>
	<?php if (is_plugin_active('gravityforms/gravityforms.php')) {
		$form_content = $footer_settings['email_signup_content'];
		?>
		<div class="footer__email-signup">
			<?php if (!empty($form_content['content']) || !empty($form_content['link_array'])) { ?>
				<div class="footer__email-signup-content">
					<?php
					$content_data = array(
						'content'           => !empty($form_content['content']) ? $form_content['content'] : '',
						'content_alignment' => !empty($form_content['content_alignment']) ? $form_content['content_alignment'] : '',
						'link_array'        => !empty($form_content['link_array']) ? $form_content['link_array'] : ''
					);
					compileTemplate('content', $content_data);
					?>
				</div>
			<?php } ?>

			<?= do_shortcode('[gravityform id="1" title="false" description="false" ajax="true"]'); ?>
		</div>
	<?php } ?>

	<div class="footer__main">
		<div class="footer__main-inner">
			<div class="footer__top">
				<div class="footer__logo-wrapper">
					<?php display_icon('logo-greenbrier', 'Greenbrier Logo');?>
				</div>

				<?php if (!empty($address)) { ?>
					<div class="footer__address">
						<?= $address; ?>
					</div>
				<?php } ?>

				<?php
				wp_nav_menu(array(
					'container' => false,
					'menu_class' => 'footer__primary-nav',
					'menu' => 'Footer Primary Nav',
					'items_wrap' => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>'
				));

				wp_nav_menu(array(
					'container' => false,
					'menu_class' => 'footer__secondary-nav',
					'menu' => 'Footer Secondary Nav',
					'items_wrap' => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>'
				));
				?>

				<?php compileTemplate('socials.php', ['additional_classes' => 'socials--footer']); ?>

			</div>


			<?php if (!empty($secondary_logos) || !empty($copyright_text)) { ?>
			<div class="footer__bottom">
				<?php if (!empty($secondary_logos)) { ?>
					<div class="footer__secondary-logos">
						<?php
						foreach($secondary_logos as $logo) {
							$pictureHTML = compileTemplate('picture', array(
								'sources' => [
									'0' => $logo['logo']['url'],
								],
								'fallback' => $logo['logo']['url'],
								'alt_text' => $logo['logo']['alt'],
								'class' => 'basic-picture footer__secondary-logo-picture',
							), false, false);

							if (!empty($logo['link']) && !empty($logo['link']['url'])) {
								$target = !empty($logo['link']['target']) ? ' target="'.$logo['link']['target'].'"' : '';
								$title = !empty($logo['link']['title']) ? ' title="'.$logo['link']['title'].'"' : '';
								?>
								<a href="<?= $logo['link']['url']; ?>" class="footer__secondary-logo footer__secondary-logo--link"<?= $target; ?><?= $title; ?>>
									<?= $pictureHTML; ?>
								</a>
							<?php } else { ?>
								<div class="footer__secondary-logo">
									<?= $pictureHTML; ?>
								</div>
							<?php }
						} ?>
					</div>
				<?php } ?>

				<?php if (!empty($copyright_text)) { ?>
					<p class="footer__copyright">
						<?= $copyright_text; ?>
					</p>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div>
</footer>
<?php compileTemplate('breadcrumbs'); ?>
<script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=haafijhdnyodc5u18i53og" async="true"></script>
