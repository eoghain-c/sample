$(function(){
	hoverCard();
	galleryCard();
});

/* Functions
   -------------------------------------------------------------------------- */
function hoverCard() {
	let hoverCards = $('.js-hover-card');

	hoverCards.each(function() {
		const card = $(this);
		const imageLink = card.find('.js-card-image-link');
		let initialized = card.data('initialized');

		if (imageLink && !initialized) {
			let firstLink = card.find('.js-card-buttons').find('.link').first();

			const imageLinkHover = function() {
				// Add Button Hover
				firstLink.addClass('active');
			}

			const imageLinkHoverOff = function() {
				// Remove Button Hover
				firstLink.removeClass('active');
			}

			// Hovering / Tabbing the Image Link also triggers hover on the first link in the Card Buttons
			imageLink.bind('mouseenter focusin', function() {
				imageLinkHover();
			});

			imageLink.bind('mouseleave focusout', function() {
				imageLinkHoverOff();
			});

			// Update initialized data attribute
			card.data('initialized', true);
		}
	});
}

function galleryCard() {
	let galleryCards = $('.js-gallery-card');

	galleryCards.each(function() {
		const card = $(this);
		const splideWrapper = card.find('.splide');
		let initialized = card.data('initialized');

		if (splideWrapper.length && !initialized) {
			let splide = new Splide(splideWrapper[0], {
				pagination: false,
				type: 'loop',
				updateOnMove: true
			});

			splide.mount();

			// Update initialized data attribute
			card.data('initialized', true);
		}
	});
}