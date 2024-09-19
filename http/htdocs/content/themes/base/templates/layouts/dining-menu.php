<?php
if (!empty($layout_settings['hide_layout'])) { return false; }
if (!function_exists('diningMenuScripts')) {
	function diningMenuScripts()
	{
		wp_enqueue_style('dining-menu-css', get_stylesheet_directory_uri() . '/assets/css/dining-menu.css', ['common-css']);
		wp_enqueue_script('dining-menu-js', get_stylesheet_directory_uri() . '/assets/js/dining-menu.js', ['ondesign-common-js']);
	}

	add_action('wp_enqueue_scripts', 'diningMenuScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'dining-menu';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
$header_title = !empty($header_title) ? $header_title : '';
$menu_subtitle = !empty($menu_subtitle) ? $menu_subtitle : '';
$item_title = !empty($item_title) ? $item_title : '';
?>
<section class="<?= $layout_name; ?> js-dining-menu <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
	<?= $settings['background'] ?? ''; ?>
	<div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<?php if (!empty($menus)):?>
			<div class="<?= $layout_name; ?>__header">
				<div class="dm-title">
					<?= $header_title; ?>
				</div>
				<div class="dm-tabs">
					<?php
					$i = 1;
					foreach($menus as $menu):
						$menu_title = get_field('menu_title', $menu->ID);
						$fallback_title = get_the_title( $menu->ID ); ?>
						<div class="dm-tab js-menu-btn" data-btn="menu-<?= $i++; ?>">
							<?= !empty($menu_title) ? $menu_title : esc_html( $fallback_title ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="<?= $layout_name; ?>__menus">
				<?php
				$i = 1;
				foreach($menus as $menu):?>
					<div class="<?= $layout_name; ?>__menu-container js-menu" data-menu="menu-<?= $i++; ?>">
						<?php
						$menu_items = get_field('menu_items', $menu->ID);
						?>
						<div class="<?= $layout_name; ?>__menu-container__inner">
							<?php foreach($menu_items as $menu_item): ?>
								<div class="dm-menu-item">
									<?php if(!empty($menu_item['menu_subtitle'])) { ?>
										<div class="dm-submenu-title">
											<?= $menu_item['menu_subtitle']; ?>
										</div>
									<?php } ?>
									<?php if(!empty($menu_item['description'])) { ?>
										<div class="dm-submenu-desc">
											<?= $menu_item['description']; ?>
										</div>
									<?php } ?>
									<?php
									if ($menu_item['submenu_items']):
										foreach ($menu_item['submenu_items'] as $submenu_item):?>
											<div class="dm-submenu-item">
												<div class="dm-submenu-item__row">
													<?php if(!empty($submenu_item['item_title'])) { ?>
														<div class="dm-submenu-item__title">
															<?= $submenu_item['item_title']; ?>
														</div>
													<?php } ?>
													<?php if(!empty($submenu_item['item_price'])) { ?>
														<div class="dm-submenu-item__price">
															<?= $submenu_item['item_price']; ?>
														</div>
													<?php } ?>
												</div>

												<?php if ($submenu_item['include_add-ons']):
													if($submenu_item['add_ons']):?>
														<div class="dm-submenu-item__addons">
															<?php foreach($submenu_item['add_ons'] as $add_on):?>
																<div class="dm-submenu-item__addon">
																	<?php if(!empty($add_on['add-on_title'])){ ?><div class="addon-title"><?= $add_on['add-on_title'] ?></div><?php } ?>
																	<?php if(!empty($add_on['add-on_price'])){ ?><div class="addon-price"><?= $add_on['add-on_price'] ?></div><?php } ?>
																</div>
															<?php endforeach;?>
														</div>
													<?php endif;
												endif;
												if(!empty($submenu_item['item_description'])) { ?>
													<div class="dm-submenu-item__description">
														<?= $submenu_item['item_description']; ?>
													</div>
												<?php } ?>
												<?php if(!empty($submenu_item['item_footer'])) {?>
													<div class="dm-submenu-item__footer">
														<?= $submenu_item['item_footer']; ?>
													</div>
												<?php } ?>
											</div>
										<?php endforeach;
									endif;?>
								</div>
							<?php endforeach; ?>
						</div>

					</div>
				<?php endforeach; ?>
			</div>
			<div class="<?= $layout_name; ?>__footer">
				<?php compileTemplate('/templates/components/content.php', array(
						'content'           => !empty($content) ? $content : '',
						'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
						'link_array'        => !empty($link_array) ? $link_array : []
				)); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
