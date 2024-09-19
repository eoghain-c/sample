<?php
// Enqueue Mobile Menu Scripts
if (!function_exists('mobileMenuScripts')) {
	function mobileMenuScripts()
	{
		wp_enqueue_style('mobilemenu-css', get_template_directory_uri() . '/assets/css/mobilemenu.css', ['common-css']);
		wp_enqueue_script('mobilemenu-js', get_template_directory_uri() . '/assets/js/mobilemenu.js');
	}
	add_action('wp_enqueue_scripts', 'mobileMenuScripts');
}

// Mobile Menu Data
$args = array(
		'post_type'		=> 'megamenu',
		'fields'		=> 'ids',
		'numberposts'	=> -1
);
$posts = get_posts( $args );

$mobilemenuArray = array();
foreach ($posts as $i => $post) {
	$fields = !empty(get_fields($post)) ? get_fields($post) : '';
	$mobilemenuArray[$i]['menu_type'] 			= !empty($fields['menu_type']) ? $fields['menu_type'] : '';
	$mobilemenuArray[$i]['menu_link'] 			= !empty($fields['menu_link']) ? $fields['menu_link'] : '';
	$mobilemenuArray[$i]['title'] 			    = !empty(get_the_title($post)) ? get_the_title($post) : '';
	$mobilemenuArray[$i]['submenu'] 		    = !empty($fields['menu_items']) ? $fields['menu_items'] : '';
	$i++;
}

// Don't output the Mobile Menu if there are no items
if (empty($mobilemenuArray)) { return; }
?>

<div class="mobilemenu js-mobilemenu">
	<div class="mobilemenu__panel mobilemenu__panel--main">
		<?php // Main Panel ?>
		<div class="mobilemenu__nav mobilemenu__nav--main">
			<?php foreach ($mobilemenuArray as $mmIndex => $menuItem):
				if ($menuItem['menu_type'] == 'mega'):?>
					<button class="mobilemenu__btn mobilemenu__btn--main mobilemenu__btn--mega js-mobilemenu-btn-top" title="<?= $menuItem['title']; ?>" aria-expanded="false"
							aria-controls="mobilemenu-panel-<?= $mmIndex; ?>" aria-labelledby="mobilemenu-panel-<?= $mmIndex; ?>">
						<?= $menuItem['title']; ?>
					</button>

					<?php // Slide-in Panel ?>
					<div class="slide-in">
						<div class="slide-in__inner">
							<button class="mobilemenu__btn mobilemenu__btn--main mobilemenu__btn--mega js-mobilemenu-btn-inner" title="<?= $menuItem['title']; ?>" aria-expanded="false"
									aria-controls="mobilemenu-panel-<?= $mmIndex; ?>" aria-labelledby="mobilemenu-panel-<?= $mmIndex; ?>">
								<?= display_icon('arrow');?>
								<?= $menuItem['title']; ?>
							</button>
							<div class="slide-in__content">
								<?php // Sub-Panel Links ?>
								<?php foreach ($menuItem['submenu'] as $submenuItem) :
									if ($submenuItem['item_type'] == 'card'): ?>
										<?php if (!empty($submenuItem['item_link'])):
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
											} ?>
											<a class="mobilemenu__a" href="<?= $href; ?>" title="<?= $title; ?>"" <?php if (!empty($submenuItem['item_link']['link']['target'])): ?>target="_blank"<?php endif; ?>>
										<?php endif;?>
										<div class="mobilemenu__item">
											<?php $image_sizes = [
													'1280'  => '250x150',
													'768'   => '325x150',
													'0'     => '225x125'
											];
											$media_template = [
													'media_type' => 'picture',
													'image' => $submenuItem['media']['image'],
													'image_sizes' => $image_sizes
											];
											compileTemplate('media', $media_template); ?>
											<?php if (!empty($submenuItem['label'])): ?>
												<span class="mobilemenu__a"><?= $submenuItem['label']; ?></span>
												<?php if (empty($submenuItem['media'])) {?>
													<span class="mobilemenu__card-label-icon"><?php display_icon('arrow');?></span>
												<?php }?>
											<?php endif; ?>
										</div>
										<?php if (!empty($submenuItem['item_link'])):?>
											</a>
										<?php endif;?>
									<?php else: ?>
										<div class="mobilemenu__item mobilemenu__item--no-image">
											<?php if (!empty($submenuItem['item_links'])): ?>
												<?php foreach($submenuItem['item_links']['link_array'] as $link):

//													$link = !empty() ? $link['item_link'] : '';
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
													}



//													$linkType = !empty($link['link_type']) ? $link['link_type'] : '';
//													if ($linkType == 'phone'):
//														$href = 'tel:';
//													elseif ($linkType == 'email'):
//														$href = 'email:';
//													else:
//														$href = '';
//													endif;?>
													<a class="mobilemenu__a" href="<?= $href; ?>" title="<?= $title; ?>" <?php if (!empty($link['link']['target'])): ?>target="_blank"<?php endif; ?>>
														<?= $title ?>
														<span class="mobilemenu__card-label-icon"><?php display_icon('arrow');?></span>
													</a>
												<?php endforeach;?>
											<?php endif;?>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				<?php else: ?>
					<?php if (!empty($menuItem['menu_link'])):?>
						<a class="mobilemenu__btn mobilemenu__btn--main" href="<?= $menuItem['menu_link']; ?>" title="<?= $menuItem['title']; ?>"><?= $menuItem['title']; ?></a>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>

		</div>
	</div>
</div>
