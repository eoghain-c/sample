$(function () {
	$('.events').each(function () {
		new EventCalendar($(this));
	});
});

const EventCalendar = function ($obj) {
	if (!$obj)
		return false;

	this.$obj = $obj;
	this.$filters_wrapper = $obj.find('.js-filters-wrapper');
	this.$btn_mobile_filters = $obj.find('.js-mobile-filters-btn');
	this.$btn_close_filters = $obj.find('.js-filters-close');
	this.$filter_tag = $obj.find('.js-tag');
	this.$filter_tag_location = $obj.find('.js-location-tag');
	this.$filter_tag_category = $obj.find('.js-category-tag');
	this.$filter_tag_date = $obj.find('.js-date-tag');
	this.$btn_clear_filters = $obj.find('.js-clear-filters-btn');
	this.$btn_filters_title = $obj.find('.js-filters-title');
	this.$btn_date = $obj.find('.js-datepicker-show');
	this.$view = $obj.find('.js-events-view');
	this.$view_month = $obj.find('.js-events-view--month');
	this.$view_calendar_list = $obj.find('.js-events-view--calendar-list');
	this.$view_list = $obj.find('.js-events-view--list');
	this.$filter_select = $obj.find('.js-filter-select');
	this.$category = $obj.find('.js-events-category');
	this.$location = $obj.find('.js-events-location');
	this.$datepicker_wrapper = $obj.find('.js-datepicker-wrapper');
	this.datepicker = '';
	this.$loader = $obj.find('.events__loading');
	this.$load_more = $obj.find('.js-events-load-more');
	this.$views = $obj.find('.js-view');
	this.$list = $obj.find('.js-events-list');
	this.$calendarList = $obj.find('.js-events-calendar-list');
	this.$listCards = $obj.find('.js-events-list-cards');
	this.$calendarListCards = $obj.find('.js-events-calendar-list-cards');
	this.$calendar = $obj.find('.js-events-calendar');
	this.$day = $obj.find('.js-events-day');
	this.inview = 'events-list';
	this.paramCategory = false;
	this.paramLocation = false;
	this.paramView = false;
	this.urlParams = new URLSearchParams(window.location.search);
	this.currentPage = 1;
	this.totalPosts = $obj.find('.js-count-events').data('total');

	this.init();
};

EventCalendar.prototype.init =
	function init() {

		this.view_listener();
		this.category_listener();
		this.location_listener();
		this.filters_checks();
		this.datepicker_show();

		this.listen();
		this.clear_filters();
		this.load_more();
		this.load_more_show_hide();

		// Needed for Calendar.
		this.flag_today();
		this.prev_next_update();
		this.create_easepick();
		this.pop_up();
	};

EventCalendar.prototype.listen =
	function listen() {
		const _this = this;

		// Toggle Filters Flyout -----
		this.$btn_mobile_filters.on('click', function () {
			_this.$filters_wrapper.toggleClass('show');
		});

		this.$btn_close_filters.on('click', function () {
			_this.$filters_wrapper.removeClass('show');
		});

		this.$filter_tag.click(function () {
			let tag = $(this);

			_this.remove_tag(tag);
			_this.$filter_select.change();
		});
	}

EventCalendar.prototype.update_params =
	function update_params(param, value) {
		this.urlParams.set(param, value);
		window.history.replaceState({}, document.title, '?' + this.urlParams.toString());
	}

EventCalendar.prototype.filters_checks =
	function filters_checks() {
		let view = this.urlParams.get('view');

		let category = this.urlParams.get('category');
		let location = this.urlParams.get('location');
		let _this = this;
		if (category) {
			this.paramCategory = true;
			this.$category.find('option').each(function () {
				if ($(this).val() === category) {
					_this.$category.val(category);
					_this.$category.trigger('change');
				}
			});
		}

		if (location) {
			this.paramLocation = true;
			this.$location.find('option').each(function () {
				if ($(this).val() === location) {
					_this.$location.val(location);
					_this.$location.trigger('change');
				}
			});
		}
		if (view) {
			this.paramView = true;
		}

		if (view && this.inview !== view) {
			this.$view.find('option').each(function () {
				if ($(this).val() === view) {
					_this.$view.val(view);
					_this.view_change(view);
				}
			});
		}
	};

EventCalendar.prototype.view_change =
	function view_change(view) {
		this.inview = view;

		this.$view.removeClass('is-active');
		this.$views.hide();
		this.$loader.show();


		if (view === 'events-month') {
			this.$btn_clear_filters.click();
			this.$obj.find('.events__date-container').addClass('hide');
			this.$calendar.fadeIn();
			this.load_calendar();
			this.$view_month.addClass('is-active');
		} else if (view === 'events-calendar-list') {
			this.$btn_clear_filters.click();
			let cat = this.$category.val();
			let loc = this.$location.val();
			if(cat === 'all' || loc === 'all') {
				this.load_date();
			} else if(cat !== ('') || loc !== ('')) {
				this.load_date();
			}
			this.$obj.find('.events__date-container').addClass('hide');
			this.check_taxonomies();
			this.$calendarList.fadeIn();
			this.$view_calendar_list.addClass('is-active');
		} else if (view === 'events-day-list') {
			this.$obj.find('.events__date-container').removeClass('hide');
			this.$day.css("display", "flex").hide().fadeIn();
			this.$view_list.addClass('is-active');
		} else {
			this.$btn_clear_filters.click();
			this.$obj.find('.events__date-container').removeClass('hide');
			this.check_taxonomies();
			this.$list.fadeIn();
			this.$view_list.addClass('is-active');
		}
	};

EventCalendar.prototype.tags_update =
	function tags_update(tag, filter) {
		let _this = this;
		let tax_text;
		let tax_val;

		if (typeof filter !== "string") {
			tax_text = filter.find('option:selected').text();
			tax_val = filter.val();
		} else {
			// Date
			tax_text = filter;
			tax_val = filter;
		}

		if (tax_val !== 'all') {
			tag.addClass('active');
			tag.html(tax_text);

			_this.$btn_clear_filters.prop('disabled', false);
			_this.$btn_filters_title.addClass('show');
		} else {
			_this.remove_tag(tag);
		}
	};

EventCalendar.prototype.remove_tag =
	function remove_tag(tag) {
		let _this = this;

		let type = tag.data('type');

		if (type !== 'date') {
			// reset the filter
			_this.$obj.find('.js-filter-select[data-type="' + type + '"]').val('all');

		} else {
			_this.view_change('events-list');

			// Reset the datepicker
			_this.datepicker1.clear();
			_this.datepicker2.clear();
		}

		tag.removeClass('active').html("");

		let filter_tag_active = _this.$obj.find('.js-tag.active');

		// Disables Clear Filters button when there are no more tags
		if (filter_tag_active.length === 0) {
			_this.$btn_clear_filters.prop('disabled', true);
			_this.$btn_filters_title.removeClass('show');
		}
	}

EventCalendar.prototype.clear_filters =
	function clear_filters() {
		let _this = this;

		this.$btn_clear_filters.click(function () {
			_this.$filter_select.val('all');

			if(_this.inview === 'events-day-list') {
				_this.view_change('events-list');
				if (_this.paramView) {
					_this.update_params('view', 'events-list');
				}
			} else if (_this.inview === 'events-calendar-list') {
				let cat = _this.$category.val();
				let loc = _this.$location.val();
				if(cat === 'all' || loc === 'all') {
					_this.load_date();
				} else if(cat !== ('') || loc !== ('')) {
					_this.load_date();
				}
			}

			_this.$filter_tag.removeClass('active').html("");

			// Reset the datepicker
			_this.datepicker1.clear();
			_this.datepicker2.clear();

			_this.$btn_clear_filters.prop('disabled', true);
			_this.$btn_filters_title.removeClass('show');

			_this.check_taxonomies();
		});
	};

EventCalendar.prototype.category_listener =
	function category_listener() {
		let _this = this;
		this.$category.change(function () {
			_this.check_taxonomies();
			if (_this.paramCategory) {
				_this.update_params('category', _this.$category.val());
			}

			// Update tag
			_this.tags_update(_this.$filter_tag_category, _this.$category);
			_this.$load_more.hide();
		});
	};

EventCalendar.prototype.location_listener =
	function location_listener() {
		let _this = this;
		this.$location.change(function () {
			_this.check_taxonomies();
			if (_this.paramLocation) {
				_this.update_params('location', _this.$location.val());
			}

			// Update tag
			_this.tags_update(_this.$filter_tag_location, _this.$location);

			_this.$load_more.hide();
		});
	};

EventCalendar.prototype.check_taxonomies =
	function () {
		let _this = this;

		let cat = this.$category.val();

		let loc = this.$location.val();

		switch (this.inview) {
			case 'events-list':
				if(cat === 'all' || loc === 'all') {
					this.load_date();
				} else if(cat !== ('') || loc !== ('')) {
					this.load_date();
				}
				break;
			case 'events-month':
				// Check against those on the page
				const displayMax = 3;

				this.$calendar.find('.js-daily-events').each(function () {
					let displayTotal = 0;
					const $viewAllButton = $(this).find('.view-all');
					const $viewAllWrap = $(this).find('.events__view-all-wrap');

					$(this).find('.meta').each(function () {
						let current = $(this);
						let parent = current.parents('.js-calendar-day-event');

						$selected_category = cat === 'all' ? 'all' : current.hasClass(cat);
						$selected_location = loc === 'all' ? 'all' : current.hasClass(loc);

						if ((!parent.hasClass('js-calendar-event') ||
								displayTotal < displayMax) &&
							((current.hasClass(cat) && current.hasClass(loc)) ||
								(cat === 'all' && loc === 'all') ||
								(cat === 'all' && current.hasClass(loc)) ||
								(current.hasClass(cat) && loc === 'all')
							)) {
							parent.removeClass('hide').fadeIn(250);
							if (parent.hasClass('js-calendar-event')) {
								displayTotal++;
							}
						} else {
							parent.fadeOut(250, function () {
								$(this).addClass('hide');
							});
						}
					});

					if (displayTotal === 0) {
						$viewAllButton.fadeOut(250);
						$viewAllWrap.addClass('hide');
					} else {
						$viewAllButton.fadeIn(250);
						$viewAllWrap.removeClass('hide');
					}
				});
				break;
			case 'events-day-list':
			case 'events-calendar-list':
				// Check against those on the page
				let totalHidden = 0;
				let $dayMeta;
				let $eventNum;
				let $noResults;

				if(_this.inview === 'events-calendar-list') {
					$dayMeta = this.$calendarList.find('.meta');
					$eventNum = this.$calendarList.find('.event-card');
					$noResults = this.$calendarList.find('.js-events-no-results');
				} else {
					$dayMeta = this.$day.find('.meta');
					$eventNum = this.$day.find('.event-card');
					$noResults = this.$day.find('.js-events-no-results');
				}

				$noResults.hide();

				$dayMeta.each(function () {
					const this_meta = $(this);
					let current_card = this_meta.closest('.event-card');

					if ((this_meta.hasClass(cat) && this_meta.hasClass(loc)) ||
						(cat === 'all' && loc === 'all') ||
						(this_meta.hasClass(loc) && cat === 'all') ||
						(this_meta.hasClass(cat) && loc === 'all')) {
						current_card.fadeIn(250);
					} else {
						current_card.fadeOut(250);
						totalHidden++;
					}
				});

				$new_count = ($eventNum.length - totalHidden);
				if(_this.inview === 'events-calendar-list') {
					_this.$calendarList.find('.events__count-events').remove();
					_this.$calendarList.find('.cards-listing-grid').append('<span class="events__count-events js-count-events" data-total="' + $new_count +'">Showing <span class="events__count-number">'+ $new_count + '</span> of <span class="events__count-number">'+ $new_count +'</span> Results</span>')
				} else {
					_this.$day.find('.events__count-events').remove();
					_this.$day.find('.cards-listing-grid').append('<span class="events__count-events js-count-events" data-total="' + $new_count +'">Showing <span class="events__count-number">'+ $new_count + '</span> of <span class="events__count-number">'+ $new_count +'</span> Results</span>')
				}

				if (($dayMeta.length === 0 || $dayMeta.length === totalHidden) || $eventNum.length === 0) {
					$noResults.fadeIn();
				}

				break;
		}
	};

EventCalendar.prototype.load_date =
	function load_date(startDate, endDate) {
		let _this = this;

		$.ajax({
			type: 'post',
			url: '/wp/wp-admin/admin-ajax.php',
			data: {
				action: 'events_day_call',
				start_date: startDate,
				end_date: endDate,
				style: _this.inview === 'events-calendar-list' ? 'horizontal' : '',
			},
			success: function (response) {
				const $noResults = _this.$day.find('.js-events-no-results');

				if(_this.inview === 'events-calendar-list'){
					_this.$calendarListCards.html(response['cards']).prepend($noResults);
					_this.$calendarListCards.append(response['count']);
					_this.check_taxonomies();
				} else {
					_this.$day.find('.events__single-day').html(response['cards']).prepend($noResults);
					_this.$day.find('.events__single-day').append(response['count']);
					_this.check_taxonomies();
					_this.view_change('events-day-list');
				}
				_this.$loader.fadeOut(750);
			}
		});
	}

EventCalendar.prototype.datepicker_show =
	function datepicker_show() {
		let _this = this;

		$(document).click(function (e) {
			if (_this.$btn_date.is(e.target)) {
				_this.$datepicker_wrapper.fadeToggle();
			} else if (_this.$datepicker_wrapper[0].contains(e.target)) {
			} else {
				_this.$datepicker_wrapper.fadeOut();
			}
		});
	}

EventCalendar.prototype.view_listener =
	function view_listener() {
		let _this = this;
		this.$view.click(function () {
			let active_view = $(this);

			_this.view_change(active_view.val());
			if (_this.paramView) {
				_this.update_params('view', $(this).val());
			}

			// Reset the datepicker
			_this.datepicker1.clear();
			_this.datepicker2.clear();
		});
	};

EventCalendar.prototype.create_easepick = function create_easepick() {
	let _this = this;

	let startDate = new Date();
	let chevron = "<svg class=\"v-icon__svg v-icon__svg--arrow\" role=\"presentation\">" +
			"<use xlink:href=\"/content/themes/base/assets/img/icons/arrow.svg#arrow\"></use>" +
			"</svg>";

	const pickerTriggers = this.$obj.find('.easepick-container__bind');

	pickerTriggers.each(function (i) {

		// Create Easepick Datepicker
		let datepicker = new easepick.create({
					element: pickerTriggers[i],
					css: [
						'/content/themes/base/assets/css/easepick.css',
						'/content/themes/base/assets/css/common.css',
						'/content/themes/base/assets/css/calendar.css'
					],
					plugins: ['KbdPlugin', 'LockPlugin', 'RangePlugin'],
					locale: {
						"previousMonth": chevron,
						"nextMonth": chevron,
						cancel: "",
						apply: "Apply Dates",
					},
					LockPlugin: {
						minDate: startDate,
						minDays: 1,
					},
					autoApply: true,
					firstDay: 0,
					calendars: 1,
					grid: 1,
					inline: true,
					zIndex: 2,
					setup(picker) {
						let wrapper = picker.ui.container
						wrapper.classList.add('easepicker');

						if ($(picker.options.element).hasClass('easepick-container__inline')) {
							wrapper.classList.add('easepicker-inline');
						}

						picker.on('select', (event) => {
							let {start, end} = event.detail;

							let startDate = start.format('MMM DD');
							let endDate = end.format('MMM DD');

							_this.load_date(start.format('MMM DD YYYY'), end.format('MMM DD YYYY'));
							if (_this.inview === 'events-calendar-list') {
								_this.inview = 'events-calendar-list';
							} else {
								_this.inview = 'events-day-list';
							}

							let tagEndDate;
							if (startDate === endDate) {
								tagEndDate = '';
							} else if (start.format('MMM') === end.format('MMM')) {
								tagEndDate = '-' + end.format('DD');
							} else {
								tagEndDate = '-' + endDate;
							}

							// Close Picker
							_this.$datepicker_wrapper.fadeOut();

							// Update tag
							_this.tags_update(_this.$filter_tag_date, startDate + tagEndDate);
						});
					}
				}
		);

		if($(pickerTriggers[i]).hasClass('easepick-container__bind-list')) {
			_this.datepicker1 = datepicker;
		} else {
			_this.datepicker2 = datepicker;
		}
	});
}

EventCalendar.prototype.load_calendar =
	function load_calendar(selectedDate) {
		if (!selectedDate) {
			selectedDate = ''
		}
		let _this = this;
		$.ajax({
			type: 'post',
			url: '/wp/wp-admin/admin-ajax.php',
			data: {
				action: 'events_calendar_call',
				calendar_date: selectedDate
			},
			success: function (response) {
				_this.$calendar.html(response);
				_this.flag_today();
				_this.prev_next_update();
				_this.pop_up();
				_this.$loader.fadeOut(750);
			}
		});
	}

EventCalendar.prototype.prev_next_update =
	function prev_next_update() {
		let _this = this;
		this.$calendar.find('.events__button').on('click', function () {
			_this.load_calendar($(this).data('date'));
		});
	};

EventCalendar.prototype.flag_today =
	function flag_today() {
		let _this = this;
		this.$calendar.find('.events__day').each(function () {
			if ($(this).data('day') === _this.set_date(new Date())) {
				$(this).addClass('today');
			}
		});
	};

EventCalendar.prototype.pop_up =
	function pop_up() {
		let eventMonthView = this.$calendar.find('.events-month');
		let viewAll = eventMonthView.find('.view-all');
		let eventsToday = eventMonthView.find('.events__today');
		let closeBtn = eventMonthView.find('.events__today-close');

		$(eventsToday).on('blur focusout', function (e) {
			let this_close = $(this);

			if(!this_close[0].contains(e.relatedTarget) && !this_close.is(e.relatedTarget)) {
				this_close.parents('.events__day').removeClass('events__selected');
				this_close.fadeOut();
			}
		});

		viewAll.click(function (e) {
			let this_view = $(this);
			e.preventDefault();
			eventMonthView.find('.events__day').removeClass('events__selected');
			this_view.parents('.events__day').addClass('events__selected');

			eventsToday.fadeOut();
			this_view.parents('.events__day').find('.events__today').fadeIn();
			eventsToday.focus();
		});

		closeBtn.click(function () {
			let this_close = $(this);

			this_close.parents('.events__day').removeClass('events__selected');
			this_close.parents('.events__today').fadeOut();
		});
	}

EventCalendar.prototype.set_date =
	function set_date(date) {
		if (!date) {
			return false;
		}
		const monthNames = ["01", "02", "03", "04", "05", "06",
			"07", "08", "09", "10", "11", "12"
		];
		return date.getFullYear() + '-' + monthNames[date.getMonth()] + '-' + date.getDate();
	};

EventCalendar.prototype.load_more =
	function load_more() {
		let _this = this;

		let offset = 0;
		let limit = this.$listCards.data('limit');

		this.$load_more.click(function () {
			offset = _this.$obj.find('.event-card:visible').length;
			_this.$obj.find('.js-count-events').remove();
			_this.update_events(offset, true, limit);

		});
	}

EventCalendar.prototype.update_events =
	function update_events(offset = 0,
	                       append = true,
	                       limit = 999,
	                       startDate, endDate) {
		let _this = this;

		$.ajax({
			type: 'post',
			url: '/wp/wp-admin/admin-ajax.php',
			data: {
				action: 'events_list_call',
				style: _this.inview === 'events-calendar-list' ? 'horizontal' : '',
				limit: limit,
				offset: offset,
				category: _this.$category.val(),
				location: _this.$location.val(),
			},
			beforeSend: function () {
				_this.$loader.fadeIn();
			},
			success: function (response) {
				if (append) {
					if(_this.inview === 'events-calendar-list'){
						_this.$calendarListCards.append(response);
					} else {
						_this.$listCards.append(response);
					}
				} else {
					if(_this.inview === 'events-calendar-list'){
						_this.$calendarListCards.html(response);
					} else {
						_this.$listCards.html(response);
					}
				}
			},
			complete: function () {
				_this.$loader.fadeOut(750);
				_this.load_more_show_hide();
			}
		});
	}

EventCalendar.prototype.load_more_show_hide = function load_more_show_hide() {
	let _this = this;

	let cards_visible = _this.$obj.find('.event-card:visible').length;
	let cards_total = _this.$obj.find('.js-count-events').data('total');

	if (cards_visible >= cards_total) {
		_this.$load_more.hide();
	} else {
		_this.$load_more.css('display', 'block');
	}
};
