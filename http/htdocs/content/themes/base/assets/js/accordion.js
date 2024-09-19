/*
* To Setup Accordion
* Wrapper Class - accordion
* Accordion Trigger - accordion__title
* Accordion Expansion Wrapper - accordion__container
* Accordion Expansion - accordion__container-inner
* */
$(function () {
	accordion();
});

function accordion() {
	let _this = $('.accordion');

	_this.each(function (){
		$(this).find('.accordion__title').on('click',function(){
			let _this = $(this);
			if(_this.hasClass('open')){
				_this.removeClass('open').next('.accordion__container').find('.accordion__container-inner').animate({
					'height' : 0
				});
				_this.parent('.faq__option').removeClass('option-open');
			} else {
				$('.open').removeClass('open').next('.accordion__container').find('.accordion__container-inner').animate({
					'height' : 0
				});
				$('.option-open').removeClass('option-open');
				// find open and remove class
				let heightNeeded  = _this.next('.accordion__container').find('.accordion__container-content').innerHeight();
				_this.addClass('open').next('.accordion__container').find('.accordion__container-inner').animate({
					'height' : heightNeeded+'px' // Will need to get inner html for this
				});
				_this.parent('.faq__option').addClass('option-open');

			}
		});
	});
	_this.find('.accordion__title').first().trigger('click');

}
