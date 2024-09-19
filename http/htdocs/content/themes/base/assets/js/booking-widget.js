// Global Pickers array
let pickers = [];

$(function() {

	$('.booking-widget').each(function() {
		const bw = new BookingWidget($(this));
	});

	// Flyout
	let bookingWidget = $('.booking-widget--sticky');
	let toggleBookingBTN = $('.js-booking-btn');
	toggleBookingBTN.each(function(){
		$(this).on('click', function() {
			event.preventDefault();
			if (bookingWidget.hasClass('active')) {
				bookingWidget.removeClass('active');
			} else {
				bookingWidget.addClass('active');
			}
		});
	});

});





const BookingWidget = function(this$obj){
	if(!this$obj) { return false; }
	this.$obj = this$obj; // .booking-widget
	this.position = this.$obj.data('position');
	this.$all_booking_widgets = $('.booking-widget');

	this.init();
}

/* Init Booking Widget
   -------------------------------------------------------------------------- */
BookingWidget.prototype.init = function init() {
	const _this = this;

	// Variables
	this.$window = $(window);
	this.$easepick_button = this.$obj.find('.js-easepick-button');
	this.$easepick_container = this.$obj.find('.easepick-container');
	this.$guests_button = this.$obj.find('.js-guests-button');
	this.$guests_container = this.$obj.find('.js-guests-container');
	this.$numberpicker = this.$obj.find('.js-booking-numberpicker');
	// this.$promo_input = this.$obj.find('.js-promo-input');
	this.$submit_button = this.$obj.find('.js-submit-booking');
	this.$submission_error = this.$obj.find('.submission-error');
	this.$close_button = this.$obj.find('.js-booking-close');

	// Functions
	this.create_easepick();
	this.datepicker_containers();
	this.guest_containers();
	this.number_pickers();
	// this.promo_field();

	// Hero
	if (this.position === 'bw--hero') {
		this.close_on_click_outside();
	}

	// Close Booking Widget
	this.$close_button.on('click', function() {
		let button = $(this);
		if(button.data('location') != 'bw--hero' ){
			_this.$obj.removeClass('active');
		} else {
			_this.$obj.fadeOut(350);
		}
	});

	// Validate Form on Submit
	this.$submit_button.on('click', function(event) {
		if (!_this.valid_form()) {
			event.preventDefault();
		}
	});
};

/* Functions
   -------------------------------------------------------------------------- */
BookingWidget.prototype.create_easepick = function create_easepick() {
	let _this = this;

	let startDate = new Date();
	let endDate = new Date(startDate);
	endDate.setDate(endDate.getDate() + 1);

	const pickerTriggers = this.$obj.find('.easepick-container__bind');

	pickerTriggers.each(function (i) {

		let chevron = "<svg class=\"v-icon__svg v-icon__svg--arrow\" role=\"presentation\">" +
				"<use xlink:href=\"/content/themes/base/assets/img/icons/arrow.svg#arrow\"></use>" +
				"</svg>";

		let columns = (_this.position === 'bw--hero') ? 2 : 1;

		// Create Easepick Datepicker
		const easePicker = new easepick.create({
			element: pickerTriggers[i],
			css: [
				'/content/themes/base/assets/css/easepick.css',
				'/content/themes/base/assets/css/common.css',
				'/content/themes/base/assets/css/booking-widget.css',
				'/content/themes/base/assets/css/booking-widget-easepick.css'
			],
			plugins: ['KbdPlugin', 'RangePlugin'],
			locale: {
				"previousMonth": chevron,
				"nextMonth": chevron,
			},
			RangePlugin: {
				startDate: startDate,
				endDate: endDate,
				tooltipNumber(num) {
					return num - 1;
				},
				locale: {
					one: 'night',
					other: 'nights',
				},
			},
			firstDay: 0,
			calendars: columns,
			grid: columns,
			inline: true,
			autoApply: true,
			setup(picker) {
				let wrapper = picker.ui.container
				wrapper.classList.add('easepicker');

				if (_this.position === 'bw--sticky') {
					wrapper.classList.add('easepicker--sticky');
				}

				picker.on('select', (event) => {
					let {start, end} = event.detail;

					// Update error handler
					let error = _this.$obj.find('.submission-error');
					error.text('');

					// Close Dates
					_this.$easepick_button.removeClass('active');
					_this.$easepick_container.removeClass('show');
					_this.$obj.find('.easepick-wrapper').hide(350);

					// Sync fields
					_this.update_date_string(start, end);
					_this.sync_pickers(start, end);
				});
			}
		});

		// Update Number of Calendars
		if (_this.position === 'bw--sticky') {
			_this.update_num_calendars(easePicker);
		}

		// Update Dates
		_this.update_date_string(easePicker.getStartDate(), easePicker.getEndDate());

		// Push picker to global object
		pickers.push(easePicker);
	});
}

BookingWidget.prototype.update_date_string = function update_date_string(start, end) {
	let days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	let weekdayStart = start.getDay();
	let weekdayEnd = end.getDay();

	let arrival_value = start.format('YYYY-MM-DD');
	let departure_value = end.format('YYYY-MM-DD');
	let arrival_string = days[weekdayStart] + start.format(', MMM D');
	let departure_string = days[weekdayEnd] + end.format(', MMM D');

	this.sync_date_values(arrival_value, departure_value, arrival_string, departure_string);
}

BookingWidget.prototype.update_num_calendars = function update_num_calendars(easePicker) {
	const _this = this;

	const debounceDatepicker = function(n) {
		easePicker.options.calendars = n;
		easePicker.options.grid = n;
		easePicker.renderAll();
	}

	let debounce;
	this.$window.resize(function () {
		clearTimeout(debounce);
		if (_this.$window.width() < 768 || _this.$window.width() > 1279) {
			// 1 Calendar on Mobile and Desktop
			debounce = setTimeout(function () {
				debounceDatepicker(1);
			}, 250);
		} else {
			// 2 Calendars on Tablet and Laptop
			debounce = setTimeout(function () {
				debounceDatepicker(2)
			}, 250);
		}
	}).trigger('resize');
}

BookingWidget.prototype.sync_date_values = function sync_date_values(arrival_value, departure_value, arrival_string, departure_string) {
	const _this = this;

	// For all Booking Widgets
	let $input_checkin = _this.$all_booking_widgets.find('input[name="checkin"]');
	let $input_checkout = _this.$all_booking_widgets.find('input[name="checkout"]');
	let $arrival_text = _this.$all_booking_widgets.find('.js-arrival-date');
	let $departure_text = _this.$all_booking_widgets.find('.js-departure-date');

	// Update the checkin and checkout inputs, and change the text
	$input_checkin.val(arrival_value);
	$input_checkout.val(departure_value);
	$arrival_text.text(arrival_string);
	$departure_text.text(departure_string);
}

BookingWidget.prototype.sync_pickers = function sync_pickers(start, end) {
	pickers.forEach(function(picker) {
		picker.setDateRange(start, end);
		picker.gotoDate(start);
	});
}

BookingWidget.prototype.datepicker_containers = function datepicker_containers() {
	const _this = this;

	const closeDates = function() {
		_this.$easepick_button.removeClass('active');
		_this.$easepick_container.removeClass('show');
		_this.$obj.find('.easepick-wrapper').hide(350);
	}

	const openDates = function() {
		_this.$easepick_button.addClass('active');
		_this.$easepick_container.addClass('show');
		_this.$obj.find('.easepick-wrapper').show();
	}

	this.$easepick_button.on('click', function() {
		if (_this.$easepick_container.hasClass('show')) {
			closeDates();
		} else {
			openDates();
		}
	});

	this.$guests_button.on('click', closeDates);
	this.$guests_button.on('focus', closeDates);
	this.$close_button.on('click', closeDates);
	// this.$promo_input.on('focus', closeDates);
	this.$submit_button.on('click', closeDates);
}

BookingWidget.prototype.guest_containers = function guest_containers() {
	const _this = this;

	const closeGuests = function() {
		_this.$guests_button.removeClass('active');
		_this.$guests_container.removeClass('show');
	}

	const openGuests = function() {
		_this.$guests_button.addClass('active');
		_this.$guests_container.addClass('show');
	}

	this.$guests_button.on('click', function() {
		if (_this.$guests_container.hasClass('show')) {
			closeGuests();
		} else {
			openGuests();
		}
	});

	this.$easepick_button.on('click', closeGuests);
	this.$easepick_button.on('focus', closeGuests);
	this.$close_button.on('click', closeGuests);
	// this.$promo_input.on('focus', closeGuests);
	this.$submit_button.on('click', closeGuests);
}

BookingWidget.prototype.number_pickers = function number_pickers() {
	const _this = this;

	this.$numberpicker.each(function() {
		let $numberpicker = $(this);

		let name = $numberpicker.data('input-name');
		let $input = _this.$obj.find('input[name="'+ name +'"]');
		let max_val = $input.attr('max');
		let min_val = $input.attr('min');

		let $picker_value = $numberpicker.find('.js-numberpicker-value');
		let $sub = $numberpicker.find('.js-numberpicker-sub');
		let $add = $numberpicker.find('.js-numberpicker-add');

		const updateGuests = function(value) {
			// Update Numberpicker and Input Value
			$input.val(value);
			$picker_value.text(value);

			// Update Guests Value
			let $input_adults = _this.$obj.find('input[name="adults"]');
			let $input_children = _this.$obj.find('input[name="kids"]');
			let $input_child1 = _this.$obj.find('input[name="child1"]');
			let $input_child2 = _this.$obj.find('input[name="child2"]');
			let $input_child3 = _this.$obj.find('input[name="child3"]');

			let adults_value = $input_adults.val();
			//let children_value = $input_children.val();
			let child1_value = $input_child1.val()*1;
			let child2_value = $input_child2.val()*1;
			let child3_value = $input_child3.val()*1;
			let children_value = ($input_child1.val()*1)+($input_child2.val()*1)+($input_child3.val()*1);

			// Build Guest String
			let guest_string = adults_value + ((adults_value > 1) ? ' Adults' : ' Adult');
			if (children_value >= 1) {
				guest_string += ', ' + children_value + ((children_value > 1) ? ' Children' : ' Child');
			}

			// Sync Guests
			_this.sync_guests(adults_value, child1_value, child2_value, child3_value, guest_string);
		}

		$add.on('click', function () {
			let value = parseInt($picker_value.text());
			if (value + 1 <= max_val) {
				updateGuests(value + 1); // Increase
			}
		});

		$sub.on('click', function () {
			let value = parseInt($picker_value.text());
			if (value - 1 >= min_val) {
				updateGuests(value - 1); // Decrease
			}
		});
	});
}

BookingWidget.prototype.sync_guests = function sync_guests(adults_value, child1_value, child2_value, child3_value, guest_string) {
	const _this = this;

	// For all Booking Widgets
	let $input_adults = _this.$all_booking_widgets.find('input[name="adults"]');
	// let $input_children = _this.$all_booking_widgets.find('input[name="kids"]');
	let $input_child1 = _this.$all_booking_widgets.find('input[name="child1"]');
	let $input_child2 = _this.$all_booking_widgets.find('input[name="child2"]');
	let $input_child3 = _this.$all_booking_widgets.find('input[name="child3"]');

	let $guests_text = _this.$all_booking_widgets.find('.js-guests-text');

	// Update the adults and children inputs, and change the text
	$input_adults.val(adults_value);
	// $input_children.val(children_value);
	$input_child1.val(child1_value);
	$input_child2.val(child2_value);
	$input_child3.val(child3_value);

	$guests_text.text(guest_string);
}

// BookingWidget.prototype.promo_field = function promo_field() {
// 	const _this = this;
// 	const $rate_inputs = _this.$all_booking_widgets.find('input[name="rate"]');
// 	const $promo_inputs = _this.$all_booking_widgets.find('.js-promo-input');
//
// 	const syncPromo = function() {
// 		// Sync with all booking widgets
// 		$rate_inputs.val(_this.$promo_input.val());
// 		$promo_inputs.val(_this.$promo_input.val());
// 	}
//
// 	// Sync the promo fields after the user stops typing for 500ms
// 	const delaySync = function(fn, ms) {
// 		let timer = 0
// 		return function(...args) {
// 			clearTimeout(timer)
// 			timer = setTimeout(fn.bind(this, ...args), ms || 0)
// 		}
// 	}
//
// 	// Listen for input
// 	this.$promo_input.on('input', delaySync(syncPromo, 500));
// }

BookingWidget.prototype.valid_form = function valid_form() {
	const _this = this;
	let error_free = true;
	let error_message = '';

	const validateCheckin = function() {
		// <input type="hidden" name="checkin" value="" required />
		let checkin = _this.$obj.find('input[name="checkin"]');
		let valid = Date.parse(checkin.val());

		if (!valid) {
			error_message = 'Invalid Arrival Date.';
			error_free = false;
		}
	}
	validateCheckin();

	const validateCheckout = function() {
		// <input type="hidden" name="checkout" value="" required />
		let checkout = _this.$obj.find('input[name="checkout"]');
		let valid = Date.parse(checkout.val());

		if (!valid) {
			error_message = 'Invalid Departure Date.';
			error_free = false;
		}
	}
	validateCheckout();

	const validateAdults = function() {
		// <input type="hidden" name="adults" value="2" min="1" max="9" />
		let adults = _this.$obj.find('input[name="adults"]');
		let valid = adults.val() >= 1 && adults.val() <= 9;

		if (!valid) {
			error_message = 'Number of Adults must be between 1 and 9.';
			error_free = false;
		}
	}
	validateAdults();

	// const validateKids = function() {
	// 	// <input type="hidden" name="kids" value="2" min="0" max="4" />
	// 	let kids = _this.$obj.find('input[name="kids"]');
	// 	let valid = kids.val() >= 0 && kids.val() <= 4;
	//
	// 	if (!valid) {
	// 		error_message = 'Number of Children must be between 0 and 4.';
	// 		error_free = false;
	// 	}
	// }
	// validateKids();

	const validateChild1 = function() {
		// <input type="hidden" name="child1" value="0" min="0" max="9" />
		let child1 = _this.$obj.find('input[name="child1"]');
		let valid = child1.val() >= 0 && child1.val() <= 9;

		if (!valid) {
			error_message = 'Number of children (0 - 3 yrs) must be between 0 and 9.';
			error_free = false;
		}
	}
	validateChild1();

	const validateChild2 = function() {
		// <input type="hidden" name="child2" value="0" min="0" max="9" />
		let child2 = _this.$obj.find('input[name="child2"]');
		let valid = child2.val() >= 0 && child2.val() <= 9;

		if (!valid) {
			error_message = 'Number of children (4 - 12 yrs)must be between 0 and 9.';
			error_free = false;
		}
	}
	validateChild2();

	const validateChild3 = function() {
		// <input type="hidden" name="child3" value="0" min="0" max="9" />
		let child3 = _this.$obj.find('input[name="child3"]');
		let valid = child3.val() >= 0 && child3.val() <= 9;

		if (!valid) {
			error_message = 'Number of children (13 - 17) must be between 0 and 9.';
			error_free = false;
		}
	}
	validateChild3();



	_this.$submission_error.text(error_message);
	return error_free;
}

BookingWidget.prototype.close_on_click_outside = function close_on_click_outside() {
	const _this = this;

	this.$window.on('click', function(e) {
		if (_this.$easepick_container.hasClass('show')) {
			if (!_this.$obj.get(0).contains(e.target) && e.target != '') {
				// Close Easepick Container
				_this.$easepick_button.removeClass('active');
				_this.$easepick_container.removeClass('show');
				_this.$obj.find('.easepick-wrapper').hide(350);
			}
		}

		if (_this.$guests_container.hasClass('show')) {
			if (!_this.$obj.get(0).contains(e.target) && e.target != '') {
				// Close Guests Container
				_this.$guests_button.removeClass('active');
				_this.$guests_container.removeClass('show');
			}
		}
	});
}





function bw_bind_buttons() {
	var buttons = $('button[data-remodal-target="booking-widget--modal"]');
	buttons.each(function(){
		$(this).click($.proxy(function() {
			var bw_input_buildingCode = $('.js-bwm-input-buildingCode');
			var bw_input_roomType = $('.js-bwm-input-roomType');
			var bw_input_rateCode = $('.js-bwm-input-rateCode');

			var new_val_buildingCode = $(this).data('buildingcode');
			if (new_val_buildingCode=='') { new_val_buildingCode = 'any'; }
			var new_val_roomType = $(this).data('roomtype');
			if (new_val_roomType=='') { new_val_roomType = 'any'; }
			var new_val_rateCode = $(this).data('ratecode');

			bw_input_buildingCode.val(new_val_buildingCode);
			bw_input_roomType.val(new_val_roomType);
			bw_input_rateCode.val(new_val_rateCode);
		},this));
	});
}