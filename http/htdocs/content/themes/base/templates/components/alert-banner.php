<?php

if(!function_exists('alertBannerScripts')) {
	function alertBannerScripts()
	{
		wp_enqueue_style('alert-banner-css', get_template_directory_uri() . '/assets/css/alert-banner.css', ['common-css']);
		wp_enqueue_script('alert-banner-js', get_template_directory_uri() . '/assets/js/alert-banner.js', ['ondesign-common-js']);
	}

	add_action('wp_enqueue_scripts', 'alertBannerScripts');
}

// Banner Data
$alertBanners = get_posts( array(
	'post_type' => 'alert_banner',
	'post_status'    => 'publish'
));

$currentPageID = get_the_ID();

// Determine which Banners to show
$activeBanners = [];

foreach ( $alertBanners as $alert ) {
	$alertContent = get_field('content', $alert->ID) ?? '';
	$alertPage = get_field('page_application', $alert->ID);
	$alertSpecificPages = $alertPage == 'specific-pages' ? get_field('specific_pages', $alert->ID) : '';

	switch ($alertPage) {
		case 'front-page':
			$displayAlert = is_front_page();
			break;
		case 'subpages':
			$displayAlert = !is_front_page();
			break;
		case 'specific-pages':
			$displayAlert = in_array($currentPageID, $alertSpecificPages, true);
			break;
		case 'all-pages':
			$displayAlert = true;
			break;
		default:
			$displayAlert = false;
			break;
	}

	if ($displayAlert  && !empty($alertContent)) {
		$activeBanners[] = $alert;
	}
}

// Only add Banners if there are any to show
if (!empty($activeBanners)) :

	foreach ( $activeBanners as $alert ) :
		$alertContent = get_field('content', $alert->ID) ?? '';
		$alertColor = get_field('alert_color', $alert->ID) ?? 'green';
		$alertExpiry = get_field('expiry_days', $alert->ID)?? '7';
		$displayAlert = false;
		?>

		<article class="alert-banner alert-banner--<?= $alertColor; ?> js-alert-banner" id="alert-<?= $alert->ID; ?>" data-style="<?=$alertColor; ?>"
				 data-alert="<?= $alert->ID; ?>" data-days="<?= $alertExpiry; ?>">
			<div class="alert-banner__wrapper">
				<div class="alert-banner__inner">
					<aside class="wysiwyg alert-banner__content"><?= $alertContent; ?></aside>
				</div>
				<button class="alert-banner__close js-alert-banner-close" aria-label="Close">CLOSE</button>
			</div>
		</article>

	<?php endforeach;

endif; ?>
