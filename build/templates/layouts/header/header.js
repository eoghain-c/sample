/* Run functions
   -------------------------------------------------------------------------- */
$(function () {
	header();
	smoothScroll();
});

/* Functions
   -------------------------------------------------------------------------- */
function header() {
	const $window = $(window);
	const $header = $('.header');
	let scrollTop = $window.scrollTop();

	// Add class for pages with no hero
	if ($("#hero").length < 1) {
		$header.addClass('header--no-hero');
		$('body').addClass('no-hero');
	}

	if (scrollTop > 0) {
		$header.addClass('header--sticky');
	}


	if($header.hasClass('header--no-hero')) {
		$header.addClass('header--sticky');
	} else {
		$window.scroll(function () {
			scrollTop = $window.scrollTop();
			if (scrollTop > 0) {
				$header.addClass('header--sticky');
				setTimeout(function () {
					setHeightProperty();
				}, 400);
			} else {
				$header.removeClass('header--sticky');
				setTimeout(function () {
					setHeightProperty();
				}, 400);
			}
		});
	}

	setHeightProperty();
}

function smoothScroll() {
	// Select all links with hashes
	$('a[href*="#"]')
		// Remove links that don't actually link to anything
		.not('[href="#"]')
		.not('[href="#0"]')
		// Remove Skip to Main Content link
		.not('[href="#main-content"]')
		.click(function (event) {
			// On-page links
			if (
				location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
				&&
				location.hostname == this.hostname
			) {

				// For WP Admin Bar
				let adminHeight = 0;
				if ($('body').hasClass('logged-in')) {
					adminHeight = $('#wpadminbar').height();
				}
				// For Header
				let headerHeight = $('.header').height();

				// Figure out element to scroll to
				var target = $(this.hash);

				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				// Does a scroll target exist?
				if (target.length) {
					// Only prevent default if animation is actually gonna happen
					event.preventDefault();
					$('html, body').animate({
						scrollTop: target.offset().top - adminHeight - headerHeight
					}, 400, function () {
						// Callback after animation
						// Must change focus!
						var $target = $(target);
						$target.focus();
						if ($target.is(":focus")) { // Checking if the target was focused
							return false;
						} else {
							$target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
							$target.focus(); // Set focus again
						}

					});
				}
			}
		});
}

// Scroll Position
function setHeightProperty() {

	// For WP Admin Bar
	let adminHeight = 0;
	document.documentElement.style.setProperty('--admin-height', '0px');

	if ($('body').hasClass('logged-in')) {
		adminHeight = $('#wpadminbar').height();
		document.documentElement.style.setProperty('--admin-height', adminHeight + 'px');
	}

	// For Header
	let headerHeight = $('.header').height();
	document.documentElement.style.setProperty('--header-height', headerHeight + 'px');
}

// Maintain the var(--header-height)
let resizeThrottled = false;

function setHeaderHeight() {

	if (!resizeThrottled) {
		// Set the variables
		setHeightProperty();
		// Throttle for use with window resize
		resizeThrottled = true;
	} else {
		// Throttle for use with window resize
		setTimeout(function () {
			resizeThrottled = false;
		}, 500);
	}
}

window.addEventListener("resize", setHeaderHeight);
