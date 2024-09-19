<?php
// Enqueue Mega Menu Scripts
if (!function_exists('megamenuScripts')) {
	function megamenuScripts()
	{
		wp_enqueue_style('megamenu-css', get_template_directory_uri() . '/assets/css/megamenu.css', ['common-css']);
		wp_enqueue_script('megamenu-js', get_template_directory_uri() . '/assets/js/megamenu.js');
	}
	add_action('wp_enqueue_scripts', 'megamenuScripts');
}

//function checkLink($link, $label, $linkType){
//	switch ($linkType) {
//		case ('email'):
//			$href = 'mailto:' . $link['link_email'];
//			$title = $link['link_text'];
//			break;
//		case ('phone'):
//			$href = 'tel:' . $link['link_phone'];
//			$title = $link['link_text'];
//			break;
//		default:
//			$href = $link['link']['url'];
//			$title = $label;
//	} // End of Switch
//}

$args = array(
		'post_type'		=> 'megamenu',
		'fields'		=> 'ids',
		'numberposts'	=> -1
);
$posts = get_posts( $args );

$menuArray = array();
foreach ($posts as $i => $post) {
	$fields = !empty($post) ? get_fields($post) : '';
	$menuArray[$i]['menu_type'] = !empty($fields['menu_type']) ? $fields['menu_type'] : '';
	$menuArray[$i]['menu_link'] = !empty($fields['menu_link']) ? $fields['menu_link'] : '';
	$menuArray[$i]['submenu'] = !empty($fields['menu_items']) ? $fields['menu_items'] : '';
	$menuArray[$i]['title'] = !empty(get_the_title($post)) ? get_the_title($post) : '';
}

// Don't output the Megamenu if there are no items
if (!empty($menuArray)): ?>
	<div class="megamenu js-megamenu">
		<ul class="megamenu__menu">

			<?php foreach ($menuArray as $mmIndex => $menuItem): ?>
				<li class="megamenu__item">
					<?php
					if ($menuItem['menu_type'] == 'mega'):?>
						<button class="megamenu__btn js-megamenu-btn"
								title="<?= $menuItem['title']; ?>" aria-expanded="false" aria-controls="megamenu-panel-<?= $mmIndex; ?>">
							<?= $menuItem['title']; ?>
						</button>
						<div class="megamenu__submenu-panel megamenu__submenu-panel--col-<?= count($menuItem['submenu']); ?> js-megamenu-panel"
							 id="megamenu-panel-<?= $mmIndex; ?>" data-mmindex="<?= $mmIndex; ?>">
							<button class="megamenu__close-btn js-megamenu-close" aria-label="Close"><?php display_icon('close'); ?></button>
							<div class="megamenu__submenu-panel-inner">
								<ul class="megamenu__submenu">
									<?php foreach ($menuItem['submenu'] as $submenuItem): ?>

									<?php if ($submenuItem['item_type'] == 'card'): ?>
										<li class="megamenu__card">
											<?php
											if (!empty($submenuItem['item_link'])):
											$link = $submenuItem['item_link'];
											$label = $submenuItem['label'];
											$linkType = $submenuItem['item_link']['link_type'];
											$href = '';
											$title = '';
											switch ($linkType) {
												case ('email'):
													$href = 'mailto:' . $link['link_email'];
													$title = $link['link_text'];
													break;
												case ('phone'):
													$href = 'tel:' . $link['item_link']['link_phone'];
													$title = $link['link_text'];
													break;
												default:
													$href = !empty($link['link']['url']) ? $link['link']['url'] : '';
													$title = !empty($label) ? $label : '';
											} // End of Switch
											?>
											<a class="megamenu__card-a" href="<?= $href; ?>" title="<?= $title; ?>" <?php if (!empty($submenuItem['item_link']['link']['target'])): ?>target="_blank"<?php endif; ?>>
												<?php endif;

												$image_sizes = [
														'1280'  => '250x150',
														'768'   => '325x150',
														'0'     => '225x125'
												];
												$media_template = [
														'media_type' => 'picture',
														'image' => $submenuItem['media']['image'],
														'image_sizes' => $image_sizes,
														'class' => 'megamenu__card-picture'
												];
												compileTemplate('media', $media_template);

												?>

												<?php if (!empty($submenuItem['label'])): ?>
													<span class="megamenu__card-label"><?= $submenuItem['label']; ?></span>
													<?php if (empty($submenuItem['media'])) {?>
														<span class="megamenu__card-label-icon"><?php display_icon('arrow');?></span>
													<?php }?>
												<?php endif;

												if (!empty($submenuItem['item_link'])):?>
											</a>
										<?php endif;?>
										</li>
									<?php else: ?>
									<li class="megamenu__card megamenu__card--no-image text-flex">
										<?php if (!empty($submenuItem['item_links'])): ?>
											<?php foreach($submenuItem['item_links']['link_array'] as $link):
												$label = !empty($link['link']['title']) ? $link['link']['title'] : '';
												$linkType = !empty($link['link_type']) ? $link['link_type'] : '';
												$href = '';
												$title = '';
												switch ($linkType) {
													case ('email'):
														$href = 'mailto:' . $link['link_email'];
														$title = $link['link_text'];
														break;
													case ('phone'):
														$href = 'tel:' . $link['link_phone'];
														$title = $link['link_text'];
														break;
													default:
														$href = !empty($link['link']['url']) ? $link['link']['url'] : '';
														$title = !empty($label) ? $label : '';
												} // End of Switch
//												checkLink($link, $label, $linkType);
												?>
												<a class="megamenu__card-a" href="<?= $href; ?>" title="<?= $title; ?>" <?php if (!empty($link['link']['target'])): ?>target="_blank"<?php endif; ?>>
													<?= $title; ?>
													<span class="megamenu__card-label-icon"><?php display_icon('arrow');?></span>
												</a>
											<?php endforeach;?>
										<?php endif;?>
										<?php endif; ?>
										<?php endforeach; ?>

								</ul>
							</div>
						</div>
					<?php else:
						if (!empty($menuItem['menu_link'])):?>
							<a class="megamenu__link" href="<?= $menuItem['menu_link']; ?>"><?= $menuItem['title']; ?></a>
						<?php endif; ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
