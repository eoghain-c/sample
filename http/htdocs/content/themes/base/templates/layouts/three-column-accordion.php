<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('threeColumnAccordionScripts')) {
	function threeColumnAccordionScripts()
	{
		wp_enqueue_style('three-column-accordion-css', get_stylesheet_directory_uri() . '/assets/css/three-column-accordion.css', ['common-css']);
		wp_enqueue_style('accordion-css', get_stylesheet_directory_uri() . '/assets/css/accordion.css', ['common-css']);
		wp_enqueue_script('accordion-js', get_stylesheet_directory_uri() . '/assets/js/accordion.js', ['ondesign-common-js']);
	}

	add_action('wp_enqueue_scripts', 'threeColumnAccordionScripts');
}
/* Custom Backgrounds and Spacing */
$layout_name = 'three-column-accordion';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

$posts = $custom_accordions;

?>
<section class="<?= $layout_name; ?> accordion <?= $settings['background_class'] ?? ''; ?><?= !empty($accordion_row_gap) ? ' accordion--row-gap' : ' accordion--no-gap'?>"<?= $settings['custom_id'] ?? ''; ?>>
	<div class="<?= $layout_name; ?>__wrapper section-spacer <?= $settings['spacer'] ?? ''; ?>">
			<?php foreach($posts as $post){ ?>
				<div class="accordion__accordion-wrapper">
				<div class="accordion__heading<?= !empty($post['heading_alignment']) ? ' accordion__heading--'.$post['heading_alignment'] : ' accordion__heading--left'?>">
					<?php if(!empty($post['heading'])) {
						echo "<{$post['heading_element']} class='title'>{$post['heading']}</{$post['heading_element']}>";
					} ?>
				</div>
				<div class="accordion__inner">
					<?php foreach($post['custom_accordion'] as $accordion) {?>
						<div class="accordion__option">
						<button class="accordion__title" name="Accordion">
							<span class="accordion__title-wrapper">
								<?php foreach ($accordion['question'] as $question_col){ ?>
								<span class="accordion__col">
								<?php if(!empty($question_col['icon']) && $question_col['icon'] != 'none') {?>
										<span class="accordion__col-icon"><?php display_icon($question_col['icon']); ?></span>
									<?php } ?>
									<span class="accordion__col-text"><?= $question_col['text'] ?></span>
								</span>
								<?php } ?>
						</span>
							<span class="accordion__chevron"><?php display_icon('arrow-splide'); ?></span>
						</button>
						<div class="accordion__container">
							<div class="accordion__container-inner">
								<div class="accordion__container-content">
									<?php foreach($accordion['answers'] as $answer) {?>
										<?php $style = !empty($answer['style']) ? $answer['style'] : 'columns'; ?>
										<?php if($style === 'columns') {?>
											<div class="accordion__container-answer">
											<?php foreach($answer['answer'] as $answer_col) {?>
												<div class="accordion__col">
													<?php if(!empty($answer_col['icon']) && $answer_col['icon'] !== 'none') {?>
														<div class="accordion__col-icon"><?php display_icon($answer_col['icon']); ?></div>
													<?php } ?>
													<div class="accordion__col-text"><?= $answer_col['text'] ?></div>
												</div>
											<?php }?>
											</div>
										<?php }else {?>
											<div class="accordion__container-answer">
												<?= wpautop($answer['answer_full_width']); ?>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
				</div>
		<?php } ?>
		</div>
	</div>
</section>