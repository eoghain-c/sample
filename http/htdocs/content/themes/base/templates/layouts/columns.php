<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('columnsScripts')) {
    function layoutNameScripts()
    {
        wp_enqueue_style('columns-css', get_stylesheet_directory_uri() . '/assets/css/columns.css', ['common-css']);
    }

    add_action('wp_enqueue_scripts', 'layoutNameScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'columns';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

if (empty($columns)) return;
?>
<section class="<?= $layout_name; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
    <div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">

	    <?php // Content Area ?>
	    <?php if(!empty($content) || !empty($link_array)) { ?>
	    <div class="<?= $layout_name; ?>__content-wrapper">
		    <?php
		    compileTemplate('content', array(
			    'content'           => !empty($content) ? $content : '',
			    'content_alignment' => !empty($content_alignment) ? $content_alignment : 'left',
			    'link_array'        => !empty($link_array) ? $link_array : []
		    ));
		    ?>
	    </div>
	    <?php } ?>

	    <?php // Columns ?>
	    <div class="<?= $layout_name; ?>__columns-wrapper">
		    <div class="<?= $layout_name; ?>__columns">
			    <?php foreach($columns as $column) { ?>
			        <?php if (!empty($column['value']) || !empty($column['description'])) { ?>
						<div class="<?= $layout_name; ?>__column">
							<?php if (!empty($column['icon']) && $column['icon'] !== 'none'){ ?>
								<div class="<?= $layout_name; ?>__column-icon <?= $layout_name; ?>__column-icon--<?= $column['icon']; ?>">
									<?php display_icon($column['icon']); ?>
								</div>
							<?php } ?>
							<?php if (!empty($column['value'])) { ?>
								<div class="<?= $layout_name; ?>__column-value">
									<?= $column['value']; ?>
								</div>
							<?php } ?>

							<?php if (!empty($column['description'])) { ?>
								<div class="<?= $layout_name; ?>__column-description">
									<?= $column['description']; ?>
								</div>
							<?php } ?>
						</div>
				    <?php } ?>
			    <?php } ?>
		    </div>
	    </div>
    </div>
</section>