<?php
if(!function_exists('header_scripts')) {
	function header_scripts()
	{
		wp_enqueue_style('header-css', get_template_directory_uri() . '/assets/css/header.css', ['common-css']);

		wp_register_script('header-js', get_template_directory_uri() . '/assets/js/header.js', ['jquery']);
		wp_enqueue_script('header-js');
	}

	add_action('wp_enqueue_scripts', 'header_scripts');
}
// Booking Link
$booking_text = !empty($settings['booking_text']) ? $settings['booking_text'] : 'Book Today';
$booking_url = !empty($settings['booking_url']) ? $settings['booking_url'] : 'https://res.windsurfercrs.com/ibe/index.aspx';
// Date - * Required *
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('tomorrow'));
$booking_url .= ('?checkin=' . $today);
$booking_url .= ('&checkout=' . $tomorrow);
?>


<header class="header">
	<?php compileTemplate('alert-banner'); ?>
	<a href="#main-content" class="sr-only sr-only-focusable">Skip to main content</a>
	<div class="header__inner">
		<div class="logo-container">
			<?php
			if (!is_front_page()) {
				echo '<a class="header__logo-a" href="/" title="' . __('Return to Homepage') . '">';
			}
			display_icon('logo-greenbrier');
			if (!is_front_page()) {
				echo '</a>';
			}
			?>
		</div>
		<div class="menu-container">
			<div class="menu-container__primary">
				<nav class="primary-menu">
					<?php
					/* === RENDER MEGAMENU === */
					if (is_plugin_active('ondesign-megamenu/ondesign-megamenu.php')) {
						echo do_shortcode('[ondesignmegamenu]');
					} ?>
				</nav>
<!--				--><?php
//				$contact_information = get_field('contact_information', 'options');
				$reserve_url = !empty(get_field('header_reserve_link', 'option')) ? get_field('header_reserve_link', 'option') : '';
//				?>
				<div class="header-cta">
					<?php // Mobile Menu Button ?>
					<button class="header__toggle header__toggle--menu js-toggle-mobilemenu"
							aria-label="<?= __('Toggle Mobile Menu') ?>" aria-expanded="false" name="Mobile Menu">
						<span class="header__toggle-icon header__toggle-icon--menu"><?php display_icon('mobile-menu'); ?></span><?= __('MENU'); ?>
					</button>

					<?php
					// Header Booking Widget Button
					if (locate_template('templates/components/booking-widget.php')) { ?>
						<button class="link__btn link__btn--white link__btn--medium reserve-link js-booking-btn"><?= __('Reserve Today'); ?></button>
					<?php } ?>


				</div>
			</div>
		</div>
	</div>


	<?php
	/* === RENDER BOOKING WIDGET - STICKY === */
	if (locate_template('templates/components/booking-widget.php')) {
		compileTemplate('booking-widget', ['position' => 'bw--sticky']);
	}
	?>

	<?php
	/* === RENDER MOBILEMENU === */
	if (is_plugin_active('ondesign-megamenu/ondesign-megamenu.php')) {
		echo do_shortcode('[ondesignmegamenu layout="mobilemenu"]');
	} ?>



</header>

<main id="main-content">

<?php
/* === RENDER HERO === */
$header_items = get_field('hero_items');
if (function_exists('show_hero')){
	show_hero();
}
?>

	<?php
	$page_background = !empty(get_field('page_background')) ? get_field('page_background') : '0';
	?>
	<div class="after-hero after-hero--<?= $page_background; ?>">