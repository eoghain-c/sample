<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

if (!function_exists('formScripts')) {
    function formScripts() {
        wp_enqueue_style('form-css', get_stylesheet_directory_uri() . '/assets/css/form.css', ['common-css', 'splide-css']);
    }
    add_action('wp_enqueue_scripts', 'formScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'form';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

if (!empty($form_id)):  ?>
<section class="<?= $layout_name; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
    <div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		<?= do_shortcode('[gravityform id="'.$form_id.'" title="false" description="false" ajax="true"]'); ?>
    </div>
</section>
<?php endif; ?>