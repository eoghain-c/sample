
$(function(){
	$('.js-dining-menu').each(function () {
		const pc = new DiningMenu($(this));
	});
});

/* Init Poster Content
   -------------------------------------------------------------------------- */

const DiningMenu = function DiningMenu(this$obj){
	this.$obj = this$obj; // .js-poster-content
	this.init();
};
DiningMenu.prototype.init = function init(){
	this.menuFader();
};

/* Functions
   -------------------------------------------------------------------------- */
DiningMenu.prototype.menuFader = function menuFader() {
	let _this = this;
	let menu_btn = this.$obj.find('.js-menu-btn');
	let menu = this.$obj.find('.js-menu');
	menu_btn.first().addClass('active');
	menu.first().fadeIn();
	this.$obj.find('.js-menu-btn').each(function() {
		$(this).on('click', function() {
			let faderbtn = $(this);
			menu_btn.removeClass('active');
			faderbtn.addClass('active');
			_this.checkFaders(faderbtn, menu);
		});
	});
};
DiningMenu.prototype.checkFaders = function checkFaders(faderbtn, menu){
	this.$obj.find('.js-menu').each(function() {
		let fader = $(this).data('menu');
		let btn_data = $(faderbtn).data('btn');
		let target = $(this);
		if(fader !== btn_data){
			target.fadeOut(500);
		}else{
			target.delay(500).fadeIn(500);
		}
	});
};