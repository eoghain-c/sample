$(function () {
	$('.cookie-banner').each(function () {
		let cb = new CookieBanner($(this));
	});
});

const CookieBanner = function ($obj) {
	if (!$obj) return false;

	this.$obj = $obj;
	this.$cookieID = this.$obj.find('#js-cookie-banner-agree').data('id');

	this.init();
};

CookieBanner.prototype.init = function init() {
	this.manageCookies();
};

CookieBanner.prototype.manageCookies = function manageCookies() {
	let _this = this;

	// Check Local Storage
	if (localStorage.getItem(_this.$cookieID)) {
		let expiryTime = new Date(localStorage.getItem(_this.$cookieID));
		let currentTime = new Date();

		if (currentTime >= expiryTime) {
			openCookieBanner(_this);
		}
	} else {
		openCookieBanner(_this);
	}

	// Close Button
	_this.$obj.find('#js-cookie-banner-close').on('click',function() {
		setLocalStorage(_this);
	});

	// Accept Button
	_this.$obj.find('#js-cookie-banner-agree').on('click',function() {
		setLocalStorage(_this);
	});
}

function setLocalStorage(_this) {
	const days = parseInt(_this.$obj.data('timer'));
	const expires = new Date(new Date().getTime() + days*24*60*60*1000);
	localStorage.setItem(_this.$cookieID, expires.toString());

	closeCookieBanner(_this);
}

function openCookieBanner(_this) {
	_this.$obj.show().delay(200).animate({'bottom': '0'}, 1000);
}

function closeCookieBanner(_this) {
	_this.$obj.delay(200).animate({'bottom': '-100px'}, 350, function () {
		_this.$obj.hide();
	});
}
