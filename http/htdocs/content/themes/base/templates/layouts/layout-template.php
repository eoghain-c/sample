<?php
if (!empty($layout_settings['hide_layout'])) { return false; }

// Replace layoutName and layout-name with your Layout Name
// You can remove the splide-css/splide-js if your layout doesn't use splide
// Remove the JS enqueue if it isn't needed
if (!function_exists('layoutNameScripts')) {
    function layoutNameScripts()
    {
        wp_enqueue_style('layout-name-css', get_stylesheet_directory_uri() . '/assets/css/layout-name.css', ['common-css', 'splide-css']);
        wp_enqueue_script('layout-name-js', get_stylesheet_directory_uri() . '/assets/js/layout-name.js', ['ondesign-common-js', 'splide-js']);
    }

    add_action('wp_enqueue_scripts', 'layoutNameScripts');
}

/* Custom Backgrounds and Spacing */
$layout_name = 'layout-template';
$settings = !empty($layout_settings) ? layoutCustomizing($layout_settings, $layout_name) : [];

/* Main Content Data */
//$var = !empty($var) ? $var : '';

?>
<section class="<?= $layout_name; ?> <?= $settings['background_class'] ?? ''; ?>"<?= $settings['custom_id'] ?? ''; ?>>
    <div class="<?= $layout_name; ?>__inner section-spacer <?= $settings['spacer'] ?? ''; ?>">
		Layout Template
    </div>
</section>