$(function(){
	alertBanners();
});

function alertBanners() {
	const alertBanners = $('.js-alert-banner');

	alertBanners.each(function() {
		const banner = $(this);
		const closeButton = banner.find('.js-alert-banner-close');

		// Set Local Storage
		let alert_ID = 'alert-'+banner.data('alert');
		let days = parseInt(banner.data('days'));
		let expires = new Date(new Date().getTime() + days*24*60*60*1000);

		closeButton.click(function() {
			localStorage.setItem(alert_ID, expires);
			$('#' + alert_ID).slideUp(350);
		});

		// Get Local Storage
		let currentTime = new Date();
		let expiryDate = localStorage.getItem(alert_ID);
		if (isNaN(Date.parse(expiryDate)) || Date.parse(expiryDate) < Date.parse(currentTime)) {
			$('#' + alert_ID).delay(200).slideDown(350);
		} else {
			$('#' + alert_ID).hide();
		}
	});
}