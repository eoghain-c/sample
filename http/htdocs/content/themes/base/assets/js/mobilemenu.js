$(function(){
	$('.js-mobilemenu').each(function () {
		const mm = new MobileMenu($(this));
	});
});

/* Init Mobile Menu
   -------------------------------------------------------------------------- */

const MobileMenu = function MobileMenu(this$obj){
	this.$obj = this$obj; // .js-mobilemenu
	this.init();
};
MobileMenu.prototype.init = function init(){
	// const $window = $(window);
	// const $header = $('.header');
	// const $mobileMenuToggle = $header.find('.js-toggle-mobilemenu');
	// const $mobileMenuText = $mobileMenuToggle.find('.btn-text');
	// const $mobileMenu = $header.find('.js-mobilemenu');
	// const $mobileMenuPanels = $mobileMenu.find('.mobilemenu__panel');
	// const $mainPanel = $mobileMenu.find('.mobilemenu__panel--main');
	// const $wpAdminBar = $('#wpadminbar');

	this.toggleMenu();
	this.toggleSlidePanel();
};

MobileMenu.prototype.toggleMenu = function toggleMenu(){
	let _this = this;
	const $window = $(window);
	const $header = $('.header');
	const $mobileMenuToggle = $header.find('.js-toggle-mobilemenu');
	// Mobile Menu Toggle Button
	$mobileMenuToggle.on('click', function() {
		if ($(this).hasClass('active')) {
			_this.closeMenu();
		} else {
			_this.openMenu();
		}
	})
};
MobileMenu.prototype.openMenu = function openMenu(){
	let _this = this;
	const $window = $(window);
	const $header = $('.header');
	const $mobileMenuToggle = $header.find('.js-toggle-mobilemenu');
	const $mobileMenuText = $mobileMenuToggle.find('.btn-text');
	const $mobileMenu = $header.find('.js-mobilemenu');
	// Open mobile menu
	$mobileMenuToggle.attr('aria-expanded','true');
	$mobileMenuToggle.addClass('active');
	$mobileMenuText.html('Close');
	$header.addClass('header__mobilemenu--active');
	$mobileMenu.addClass('mobilemenu--active');

	// Resize Function
	$window.on('resize', _this.closeOnResize());
	$window.resize(function() {
		if ($window.width() > 1279){
			_this.closeMenu();
		}
	});
}
MobileMenu.prototype.closeMenu = function closeMenu(){
	let _this = this;
	const $window = $(window);
	const $header = $('.header');
	const $mobileMenuToggle = $header.find('.js-toggle-mobilemenu');
	const $mobileMenuText = $mobileMenuToggle.find('.btn-text');
	const $mobileMenu = $header.find('.js-mobilemenu');


	const $slideInToggle = this.$obj.find('.mobilemenu__btn--mega');
	_this.slideOut($slideInToggle);

	// Close Mobile Menu
	$mobileMenu.removeClass('mobilemenu--active');
	$header.removeClass('header__mobilemenu--active');
	$mobileMenuToggle.attr('aria-expanded','false');
	$mobileMenuToggle.removeClass('active');
	$mobileMenuText.html('Menu');


	// Resize Function
	$window.off('resize', _this.closeOnResize());
};
MobileMenu.prototype.closeOnResize = function closeOnResize(){
	let _this = this;
	const $window = $(window);
	const $header = $('.header');
	const $mobileMenu = $header.find('.js-mobilemenu');
	if ( $mobileMenu.hasClass('mobilemenu--active') && $window.width() > 1279 ) {
		_this.closeMenu();
	}

};
MobileMenu.prototype.toggleSlidePanel = function toggleSlidePanel(){
	let _this = this;
	const $slideInToggle = this.$obj.find('.js-mobilemenu-btn-top');
// Mobile Menu Toggle Button
	$slideInToggle.each(function () {
		let $toggle = $(this);
		let $innerToggle = $toggle.next('.slide-in').find('.js-mobilemenu-btn-inner');

		$toggle.on('click', function() {
			_this.slideIn($toggle);
		});

		$innerToggle.on('click', function() {
			_this.slideOut($toggle);
		});
	});

}
MobileMenu.prototype.slideIn = function slideIn($slideInToggle){
	$slideInToggle.addClass('active');
	$slideInToggle.next('.slide-in').addClass('active');
};
MobileMenu.prototype.slideOut = function slideOut($slideInToggle){
	$slideInToggle.removeClass('active');
	$slideInToggle.next('.slide-in').removeClass('active');
};
