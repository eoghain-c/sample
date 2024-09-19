/* Ready Function */
$(function(){
	$('.hero').each(function(){
		let hero = new HeroWidget($(this));
	});
});
var HeroWidget = function(this$obj){
	this.$obj = this$obj;
	this.init();
}
HeroWidget.prototype.init = function init(){
	this.checkVideoState();
	this.mediaControls();
};

HeroWidget.prototype.checkVideoState = function checkVideoState() {
	let intervalRunning = false;
	let heroVideo = this.$obj.find('video');
	let media_controls = this.$obj.find('.hero__media-controls');
	//preload video
	heroVideo.attr('preload', 'auto');

	// Wait for the video to be ready, then remove the preloader
	if (typeof this.readyState != 'undefined') {

		// Check video state every x seconds until it's ready to play
		if (intervalRunning) return;
		intervalRunning = true;
		let duration_counter = 0;
		let check_interval = 50;
		let max_wait = 30 * 1000;
		let splideItem = this.$obj.find('#hero .splide__list > li');

		let video_check = setInterval(function () {

			// At readyState 4 the video is ready to play. From here we'll animate the preloader out of view
			// Only reveal if it's been at least the min_wait time
			if (heroVideo.readyState === 4) {

				if (splideItem.eq(hero_splide.index).find('video').length) {
					splideItem.eq(hero_splide.index).find('video')[0].play();
				}

				// Reveal video
				heroVideo.show();
				heroVideo.removeClass('hidden');
				//media_controls.show();

				// Clear the interval since the video is now ready
				clearInterval(video_check);
			}

			// Has the video been loading longer than the alloted max wait? If so, show fallback image
			// Only reveal if it's been at least the min_wait time
			if (duration_counter > max_wait) {

				let fallback_img_src = heroVideo.closest('.item').data('image-src');
				heroVideo.closest('.item').css('background-image', "url('" + fallback_img_src + "')");

				// Remove video as it could not be played
				media_controls.hide();
				heroVideo.hide();
				heroVideo.addClass('hidden');

				// Clear the interval since the video wasn't able to load
				clearInterval(video_check);
				intervalRunning = false;
			} else {
				duration_counter += check_interval;
			}
		}, check_interval);
	}
}

HeroWidget.prototype.mediaControls = function mediaControls(){
	let _this = this;
	let pauseBTN = this.$obj.find('.js-hero-pause');
	let playBTN = this.$obj.find('.js-hero-play');
	let heroVideo = this.$obj.find('video');
	let media_controls = this.$obj.find('.hero__media-controls');
	pauseBTN.click(function () {
		heroVideo.trigger('pause');
		$(this).parent(media_controls).find(playBTN).show(); // In case there are multiple video slides
		$(this).hide();
	});

	playBTN.click(function () {
		if (typeof heroVideo[0].readyState !== 'undefined' && heroVideo[0].readyState !== 4) {
			// Make sure the video can play on mobile
			_this.checkVideoState();
		} else {
			heroVideo.trigger('play');
			$(this).parent(media_controls).find(pauseBTN).show();
			$(this).hide();
		}
	});
}