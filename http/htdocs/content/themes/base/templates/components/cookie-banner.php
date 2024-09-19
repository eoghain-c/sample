<?php

if (!function_exists('cookieBannerScripts')) {
	function cookieBannerScripts()
	{
		wp_enqueue_style('cookie-banner-css', get_template_directory_uri() . '/assets/css/cookie-banner.css', ['common-css']);
		wp_enqueue_script('cookie-banner-js', get_template_directory_uri() . '/assets/js/cookie-banner.js', ['ondesign-common-js']);
	}

	add_action('wp_enqueue_scripts', 'cookieBannerScripts');
}

$cookie = get_field('site_cookie', 'option') ?? '';

$cookieColor = $cookie['cookie_color'] ?? 'green';
$cookieTime = $cookie['cookie_expiry'] ?? '7';
$cookieAgree = !empty($cookie['cookie_accept_message']) ? $cookie['cookie_accept_message'] : 'I Accept';
$cookieContent = $cookie['cookie_message'] ?? '';

?>
<article class="cookie-banner cookie-banner--<?= $cookieColor; ?>" id="banner-cookie" data-timer="<?= $cookieTime; ?>">
	<div class="cookie-banner__wrapper">
		<div class="cookie-banner__content">
			<div class="wysiwyg">
				<?= $cookieContent; ?>
			</div>
			<button id="js-cookie-banner-agree" class="cookie-banner__agree" data-id="cookie_policy" data-banner="cookie"
					aria-label="<?= $cookieAgree; ?>">
				<?= $cookieAgree; ?>
			</button>
		</div>
		<button class="cookie-banner__close" id="js-cookie-banner-close" aria-label="Close">CLOSE</button>
	</div>
</article>
