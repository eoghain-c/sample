$(function(){
	splideWrapper($('.js-splide-wrapper-component'));
});

function splideWrapper (obj) {
	if(!obj){return false;}
	obj.each(function() {

		const splideWrapper = $(this).find('.splide');
		let breakpoints = splideWrapper.data('breakpoints');
		let splide = new Splide(splideWrapper[0], {
			pagination: false,
			updateOnMove: true,
			breakpoints : breaks(breakpoints)
		});
		splide.mount();
	});
}

function breaks(breakpointsString){
	if(!breakpointsString){return false;}

	breakpointsString = breakpointsString.split(";");

	let output = {};
	breakpointsString.forEach(function (item, index){
		if(item) {
			let point = item.split('=>');

			output[parseInt(point[0])] = JSON.parse(point[1]);
		}
	});
	return output;
}