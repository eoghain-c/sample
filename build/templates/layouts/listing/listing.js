$(function(){
	$('.js-cards-listing').each(function(){
		const listing = new Listing($(this));
	});
});

const Listing = function($obj){
	if (!$obj) { return false; }

	// DOM -----
	this.$obj = $obj;
	this.$list = $obj.find('.js-listing__cards');
	this.$loader = $obj.find('.js-listing__loader');
	this.$filters = $obj.find('.js-listing-filter');
	this.$buttons_wrapper = $obj.find('.js-filter-btn-wrapper')
	this.$button_filters = $obj.find('.js-filter-btn');
	this.$meta_filters = $obj.find('.js-listing-meta');
	this.$more = $obj.find('.js-listing-more');
	this.$clear = $obj.find('.js-listing-clear');
	this.$current = $obj.find('.js-filter-count-current');
	this.$total = $obj.find('.js-filter-count-total');

	// Request -----
	this.request = {
		taxFilters: [],
		metaFilters: [],
		parent: this.$more.data('parent'),
		parentType: this.$more.data('parent-type'),
		exclude: this.$more.data('exclude'),
		offset: this.$more.data('perpage'),
		perpage: this.$more.data('perpage'),
		requesting: false,
		type: this.$more.data('type')
	}

	this.params = [];
	this.meta_params = [];
	this.init();
}

Listing.prototype.init = function init(){
	this.get_filter_params();
	this.more_listener();
	this.clear_listener();
	this.filter_listeners();
	this.pre_filtered();
	this.accommodation_select();
};

Listing.prototype.accommodation_select = function accommodation_select() {
	let _this = this;
	if(_this.request.type === 'accommodations' && _this.$button_filters.length > 0 && !(window.location.search)) {
		const $button = $(_this.$button_filters[0]);
		const taxonomy = _this.$buttons_wrapper.data('taxonomy');
		const child_id = $button.data('value');

		_this.request.taxFilters = [];
		_this.request.offset = 0;

		if (taxonomy && child_id) {
			_this.request.taxFilters.push([taxonomy, child_id]);
		}

		$button.addClass('active');
	}
}

Listing.prototype.more_listener = function more_listener(){
	const _this = this;

	this.$more.on('click', function() {
		_this.get_posts();
	});
}

Listing.prototype.clear_listener = function clear_listener(){
	const _this = this;

	this.$clear.on('click', function(){
		_this.$filters.find('option:selected').prop('selected', false);
		_this.$filters.find('.hidden').prop('selected', true);
		_this.$meta_filters.find('option:selected').prop('selected', false);
		_this.$meta_filters.find('.hidden').prop('selected', true);
		_this.request.taxFilters = [];
		_this.request.metaFilters = [];
		_this.request.offset = 0;

		_this.set_select_url_filters();
		_this.get_posts(true);
	});
}

Listing.prototype.filter_listeners = function filter_listeners(){
	const _this = this;

	this.$button_filters.each(function() {

		$(this).on('click', function() {
			const $button = $(this);
			const taxonomy = _this.$buttons_wrapper.data('taxonomy');
			const param = _this.$buttons_wrapper.data('param');
			const child_id = $button.data('value');
			const slug = $button.data('filter');

			_this.request.taxFilters = [];
			_this.request.offset = 0;

			if (taxonomy && child_id) {
				_this.request.taxFilters.push([taxonomy, child_id]);
			}

			_this.$button_filters.removeClass('active');
			$button.addClass('active');

			_this.set_url_filter(param, slug);
			_this.get_posts(true);
		});
	})

	this.$filters.each(function() {
		$(this).on('change', function() {
			_this.taxonomy_change();
		});
	});

	this.$meta_filters.each(function() {
		$(this).on('change', function() {
			_this.meta_change();
		});
	});
}

Listing.prototype.taxonomy_change = function taxonomy_change(){
	const _this = this;
	_this.request.taxFilters = [];
	_this.request.offset = 0;

	_this.$filters.each(function() {
		const $filter = $(this);
		const taxonomy = $filter.data('taxonomy');
		const id = $filter.val();
		if (taxonomy && id) {
			_this.request.taxFilters.push([taxonomy, id]);
		}
	});

	_this.set_select_url_filters();
	_this.get_posts(true);
}

Listing.prototype.meta_change = function meta_change(){
	const _this = this;
	_this.request.metaFilters = [];
	_this.request.offset = 0;

	_this.$meta_filters.each(function() {
		const $filter = $(this);
		const key = $filter.data('key');
		const value = $filter.val();
		const type = $filter.data('type');
		const compare = $filter.data('compare');
		if (key && value) {
			_this.request.metaFilters.push([key, value, type, compare]);
		}
	});

	_this.set_select_url_filters();
	_this.get_posts(true);
}

Listing.prototype.get_filter_params = function get_filter_params() {
	const _this = this;

	this.$filters.each(function() {
		_this.params.push($(this).data('param'));
	});

	this.$meta_filters.each(function() {
		_this.meta_params.push($(this).data('param'));
	});
}

Listing.prototype.get_posts = function get_posts(empty = false) {
	if (this.request.requesting) { return false; }
	const _this = this;

	// Safeguard to only process one request at a time
	this.request.requesting = true;

	$.ajax({
		type: 'post',
		url: '/wp/wp-admin/admin-ajax.php',
		data: {
			action: 'listing_call',
			taxFilters: _this.request.taxFilters,
			metaFilters: _this.request.metaFilters,
			parentType: _this.request.parentType,
			parent: _this.request.parent,
			exclude: _this.request.exclude,
			offset: _this.request.offset,
			perpage: _this.request.perpage,
			type: _this.request.type
		},
		beforeSend: function() {
			_this.$loader.show(250);
		},
		success: function(response) {
			if (empty) {
				_this.$list.empty().append(response.cards);
			} else {
				_this.$list.append(response.cards);
			}
			_this.request.offset = _this.request.offset + _this.request.perpage;
			_this.$current.html(_this.$list.children().length);
			_this.$total.html(response.total);

			if(_this.request.offset >= response.total) {
				_this.$more.addClass('hidden');
			} else {
				_this.$more.removeClass('hidden');
			}

			// Init Remodal on any new cards
			_this.init_card_galleries();
		},
		complete: function() {
			_this.$more.blur();
			_this.request.requesting = false;
			_this.$loader.hide(250);
		}
	});
};

Listing.prototype.pre_filtered = function pre_filtered() {
	const urlParams = new URLSearchParams(window.location.search);
	const _this = this;
	let sendRequest = false;

	if (_this.$filters.length) {
		// Taxonomy -----
		_this.params.forEach(function(param) {
			const slug = urlParams.get(param);

			if (slug && typeof slug == 'string') {
				_this.$filters.each(function() {
					const $filter = $(this);
					const filter_param = $filter.data('param');

					if (filter_param === param) {
						const $options = $filter.find('.js-filter-option');
						$options.each(function() {
							const $option = $(this);
							const option_filter = $option.data('filter');
							const option_val = $option.val();

							if (option_filter == slug) {
								$filter.val(option_val);
								sendRequest = true;
							}
						});
					}
				});
			}
		});
	} else if (_this.$button_filters.length) {
		const slug = urlParams.get(_this.$buttons_wrapper.data('param'));

		if (slug && typeof slug == 'string') {
			_this.$button_filters.each(function() {
				const $button = $(this);
				const filter = $button.data('filter');

				if (filter == slug) {
					$button.trigger('click');
					sendRequest = true;
				}
			});
		}
	}

	if (_this.$meta_filters.length) {
		// Meta -----
		_this.meta_params.forEach(function(param) {
			const slug = urlParams.get(param);

			if (slug && typeof slug == 'string') {
				_this.$meta_filters.each(function() {
					const $filter = $(this);
					const filter_param = $filter.data('param');

					if (filter_param === param) {
						const $options = $filter.find('.js-filter-option');
						$options.each(function() {
							const $option = $(this);
							const option_filter = $option.data('filter');
							const option_val = $option.val();

							if (option_filter == slug) {
								$filter.val(option_val);
								sendRequest = true;
							}
						});
					}
				});
			}
		});
	}

	// Send Request
	if (sendRequest) {
		// Trigger Change
		if (_this.$filters.length) {
			_this.taxonomy_change();
		} else if (_this.$meta_filters.length) {
			_this.meta_change();
		}

		// Offset the header, admin bar, and section spacer
		const sectionSpacer = parseInt(_this.$obj.find('.section-spacer').css('margin-top'));
		const headerHeight = $('.header').height();
		const adminHeight = $('body').hasClass('logged-in') ? $('#wpadminbar').height() : 0;

		// Scroll to the top of the layout
		$('html, body').animate({scrollTop: _this.$obj.offset().top - headerHeight - adminHeight - sectionSpacer}, 350);
		return true;
	}

	return false;
}

Listing.prototype.set_select_url_filters = function set_select_url_filters() {
	const _this = this;

	// Taxonomy -----
	this.params.forEach(function(param) {
		_this.$filters.each(function() {
			const $filter = $(this);
			if ($filter.data('param') === param) {
				const slug  = $filter.find(":selected").data('filter');
				_this.set_url_filter(param, slug);
			}
		});
	});

	// Meta -----
	this.meta_params.forEach(function(param) {
		_this.$meta_filters.each(function() {
			const $filter = $(this);
			if ($filter.data('param') === param) {
				const slug  = $filter.find(":selected").data('filter');
				_this.set_url_filter(param, slug);
			}
		});
	});
};

Listing.prototype.set_url_filter = function set_url_filter(param, slug = '') {
	let urlParams = new URLSearchParams(window.location.search);

	if (slug) {
		urlParams.set(param, slug);
	} else {
		urlParams.delete(param);
	}

	if (urlParams.toString()) {
		history.pushState({}, document.title, "?" + urlParams.toString());
	} else {
		history.pushState({}, document.title, "./");
	}
}

Listing.prototype.init_card_galleries = function init_card_galleries() {
	const _this = this;
	const $cards = _this.$obj.find('.card');

	$cards.each(function() {
		const $card = $(this);
		const $galleryBtn = $card.find('.card__gallery-btn');

		if ($galleryBtn.length) {
			const target = $galleryBtn.data('remodal-target');
			const $galleryModal = $card.find('[data-remodal-id='+ target +']');

			if (!$galleryModal.hasClass('.remodal-is-initialized')) {
				$galleryModal.remodal();
			}
		}
	});
}
