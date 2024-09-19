/**
 * Todo:  Uncomment http/htdocs/content/themes/base/inc/scripts.php:26 if you add anything to this file
 */

/* Run functions
   -------------------------------------------------------------------------- */
$(function(){


	let gf_datepickers = $('.gform_fields .easepick input');
	let chevron = "<svg class=\"v-icon__svg v-icon__svg--arrow\" role=\"presentation\">" +
		"<use xlink:href=\"/content/themes/base/assets/img/icons/arrow.svg#arrow\"></use>" +
		"</svg>";
	gf_datepickers.each(function(){
		let this_datepicker = this;
		const gf_datepickers = new easepick.create({
			element: this_datepicker,
			css: [
				'/content/themes/base/assets/css/easepick.css',
				'/content/themes/base/assets/css/common.css',
			],
			plugins: ['KbdPlugin'],
			locale: {
				"previousMonth": chevron,
				"nextMonth": chevron,
			},
			firstDay: 0
		});
	});

	// Set the '--vh' css variable.
	window.addEventListener('resize', setViewportHeight);
	setViewportHeight();

});


/* Functions
   -------------------------------------------------------------------------- */

// 100vh setter for mobile devices. Recalculates to include screen loss due to browser bars.
function setViewportHeight() {
	const doc = document.documentElement
	doc.style.setProperty('--vh', (window.innerHeight*.01) + 'px');
}


/* Navis Phone Number Script
   -------------------------------------------------------------------------- */
$(function(){
	navisNumbers();
});

const navisNumbers = function() {
	// Add the Navis script
	const scriptTag = document.createElement("script");
	scriptTag.setAttribute("src", "https://www.navistechnologies.info/JavascriptPhoneNumber/js.aspx?account=14627&jspass=3yfd7odadnc5qsh77nbw&dflt=8554534858");
	document.head.appendChild(scriptTag);
	// Update phone numbers if the Navis script loaded
	scriptTag.addEventListener("load", () => {
		// Navis function to check url param and set a cookie for which number to use
		ProcessNavisNCKeyword();
		// Get the phone number from Navis and format it (functions provided by the Navis script)
		const numberFromNavis = NavisConvertTagToPhoneNumberBasic(ReadNavisTagCookie());
		// If we get a number from Navis, format and update the site phone numbers
		if (numberFromNavis) {
			const telFormat = FormatPhone(numberFromNavis, "+1##########");
			const linkFormat = FormatPhone(numberFromNavis, "(###) ###-####");
			// Loop through phone numbers and update witht he Navis number
			$('.js-navis-number').each(function() {
				const $numberElement = $(this);
				$numberElement.text(linkFormat);
				// Update tel links href
				if ($numberElement.is('a')) {
					$numberElement.attr('href', 'tel:'+telFormat);
				}
			});
		}
	});
}
