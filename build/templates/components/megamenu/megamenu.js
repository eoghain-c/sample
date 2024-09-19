$(function(){
	megaMenu();
});

function megaMenu() {
	let lastElem;
	const $header = $('.header');
	const $megamenu = $header.find('.js-megamenu');
	const $megamenuButtons = $megamenu.find('.js-megamenu-btn');
	const $megamenuPanels = $megamenu.find('.js-megamenu-panel');


	// Open the MegaMenu
	// MegaMenu Buttons
	$megamenuButtons.click(function() {
		const $button = $(this);
		const mmIndex = $button.attr('aria-controls');
		const mmPanel = $megamenu.find('#'+mmIndex);
		lastElem = $button;

		if ($button.hasClass('active')) {
			$header.removeClass('header__megamenu--active');
			$button.blur();
			clearMegaMenu();
		} else {
			$header.addClass('header__megamenu--active');
			if ($megamenu.hasClass('active')) {
				clearMegaMenu();

				setTimeout(function () {
					$button.addClass('active').attr('aria-expanded','true');
					mmPanel.addClass('active');
					$megamenu.addClass('active');
					setTimeout(function () {
						$button.addClass('visible');
						mmPanel.addClass('visible');
					}, 10);
				}, 260);
			} else {
				$button.addClass('active').attr('aria-expanded','true');
				mmPanel.addClass('active');
				$megamenu.addClass('active');
				setTimeout(function () {
					$button.addClass('visible');
					mmPanel.addClass('visible');
				}, 10);
			}
		}

	});


	// Close the MegaMenu
	function clearMegaMenu() {
		lastElem.focus();
		$megamenuButtons.attr('aria-expanded','false').removeClass('visible');
		$megamenuPanels.removeClass('visible');

		setTimeout(function () {
			$megamenuButtons.removeClass('active');
			$megamenuButtons.blur();
			$megamenuPanels.removeClass('active');
			$megamenu.removeClass('active');
		}, 250);
	}

	// MegaMenu Close Button
	$megamenu.find('.js-megamenu-close').click(function(){
		$header.removeClass('header__megamenu--active');
		clearMegaMenu();
	});


	// Tab Lock the MegaMenu
	$megamenuPanels.each(function(){
		const $panel = $(this);
		const $panelAnchors = $('a',$panel);
		const panelAnchorsLength = $panelAnchors.length;

		$panelAnchors.each(function(index, element){
			const $anchor = $(this);
			if (index === (panelAnchorsLength - 1)) {
				$anchor.addClass('last-anchor');
			}
		});

		// Hide menu on scroll
		$(window).scroll(function(){
			if ($(this).scrollTop() > 20) {
				if (!$megamenu.hasClass('active')) {
					return;
				}else {
					$megamenuButtons.attr('aria-expanded','false').removeClass('visible active');
					$megamenuButtons.blur();
					$megamenuPanels.removeClass('visible active');
					$megamenu.removeClass('active');
				}
			}
		});
	});

	$('body').keydown(function(e) {
		if (e.keyCode === 9 && !e.shiftKey) {
			if ($megamenu.hasClass('active')) {
				const pattern = /(?:^|\s)last-anchor(?:\s|$)/;
				if (document.activeElement.className.match(pattern)) {
					e.preventDefault();
					clearMegaMenu();
				}
			}
		} else if (e.keyCode === 27) {
			e.preventDefault();
			clearMegaMenu();
		}
	});
}