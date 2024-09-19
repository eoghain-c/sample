$(function() {
	interactiveMap();
});

function interactiveMap() {
	const $window = $(window);
	const $document = $(document);
	const $header = $('.header');
	const $wpAdminBar = $('#wpadminbar');
	const $interactiveMapLayout = $('.js-interactive-map');
	const defaultFilter = $interactiveMapLayout.data('default-filter');
	const $mapBoxContainer = $interactiveMapLayout.find('#js-interactive-map-mapbox');
	const $categorySelectButtons = $interactiveMapLayout.find('.js-category-select');
	const $allCategoriesButton = $interactiveMapLayout.find('.js-select-all');
	const $markerPopups = $interactiveMapLayout.find('.js-interactive-map-popup');
	const $mobileCategoryToggle = $interactiveMapLayout.find('.js-map-dropdown-toggle');
	const $categoryList = $interactiveMapLayout.find('.js-map-category-list');
	const zoomLevel = parseFloat($mapBoxContainer.data('zoom'));
	const mobileZoomLevel = parseFloat($mapBoxContainer.data('mobile-zoom'));
	const centerLong = parseFloat($mapBoxContainer.data('long'));
	const centerLat = parseFloat($mapBoxContainer.data('lat'));
	const mapPitch = parseFloat($mapBoxContainer.data('pitch'));
	const mapBearing = parseFloat($mapBoxContainer.data('bearing'));
	let startingZoomLevel = 13;
	let zoomUserChanged = false;
	let resizeDebounce;
	let markers = [];
	let markersNoCluster = [];
	let activeMarkerId;
	let activeClusterId;
	let activePop;

	const mapCenter = [
		!isNaN(centerLong) ? centerLong : -80.3105347,
		!isNaN(centerLat) ? centerLat : 37.7860374
	];


	// Update height css variables
	const setHeight = function() {
		// Set --vh variable
		const vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', vh + 'px');

		// Header Height
		const headerHeight = $header.height();
		document.documentElement.style.setProperty('--header-height', headerHeight+'px');

		// For WP Admin Bar
		const $adminHeight = $wpAdminBar.length ? $wpAdminBar.height() + 'px' : '0px';
		document.documentElement.style.setProperty('--admin-height', $adminHeight);
	}
	setHeight();


	// Set Starting Zoom
	if ($window.width() < 768 && !isNaN(mobileZoomLevel)) {
		startingZoomLevel = mobileZoomLevel;
	} else {
		startingZoomLevel = !isNaN(zoomLevel) ? zoomLevel : startingZoomLevel;
	}


	// Create the MapBox Map
	mapboxgl.accessToken = 'pk.eyJ1IjoidmVyYi1tYXBib3giLCJhIjoiY2syNTFvNTh4MWN2ZTNjbjBudzM5anIzciJ9.P-cQaIS4JOPCSRQYEShQAQ';
	const map = new mapboxgl.Map({
		center:         mapCenter,
		pitch:          !isNaN(mapPitch) ? mapPitch : 5,
		bearing:        !isNaN(mapBearing) ? mapBearing : -29.60,
		container:      $mapBoxContainer[0],
		dragRotate:     false,
		logoPosition:   'bottom-right',
		maxZoom:        18,
		minZoom:        10,
		scrollZoom:     false,
		style:          'mapbox://styles/verb-mapbox/clgb25a2g001x01nupap2rl0m',
		zoom:           startingZoomLevel
	});


	// Add MapBox zoom controls and disable rotate
	map.addControl( new mapboxgl.NavigationControl({ showCompass: false }), 'bottom-right' );
	map.dragRotate.disable();
	map.touchZoomRotate.disableRotation();


	// Set MapBox padding (to offset the center for overlays)
	const setMapPadding = function() {
		if ($window.width() >= 1280) {
			map.easeTo({
				padding: { 'left': $window.width() >= 1920 ? 571 : 382 },
				duration: 0
			});
		}
	}
	setMapPadding();


	// Set MapBox zoom level
	const setMapZoom = function() {
		if ($window.width() < 768 && !isNaN(mobileZoomLevel)) {
			map.zoomTo(mobileZoomLevel);
		} else {
			map.zoomTo(!isNaN(zoomLevel) ? zoomLevel : startingZoomLevel);
		}
	}


	// Handle Resize
	const windowResize = function() {
		setHeight();
		setMapPadding();

		if (!zoomUserChanged) {
			setMapZoom();
		}
	}

	$window.on('resize', function() {
		clearTimeout(resizeDebounce);
		resizeDebounce = setTimeout(windowResize, 100);
	});


	// Map Markers
	// Create Map Marker Data from the Popups
	$markerPopups.each( function() {
		if (!isNaN(parseFloat(this.dataset.long)) && !isNaN(parseFloat(this.dataset.lat))) {
			const data = {
				type: 'Feature',
				geometry: {
					type: 'Point',
					coordinates: [this.dataset.long, this.dataset.lat]
				},
				properties: {
					id: this.dataset.id,
					name: this.dataset.name,
					category: this.dataset.pinCategory,
					categories: this.dataset.filterCategories,
				}
			}

			if (this.dataset.pinCategory === 'greenbrier') {
				markersNoCluster.push(data);
			} else {
				markers.push(data);
			}
		}
	});

	const geojson = {
		type: 'FeatureCollection',
		features: markers
	};

	const geojsonNoCluster = {
		type: 'FeatureCollection',
		features: markersNoCluster
	};

	// Popup Settings
	const popupDistance = 15;
	const popupSettings = {
		className: 'interactive-map__mapbox-popup js-mapboxgl-popup',
		focusAfterOpen: false,
		maxWidth: '300px',
		offset: {
			'top': [ 0, popupDistance ],
			'top-left': [ 0, popupDistance ],
			'top-right': [ 0, popupDistance ],
			'bottom': [ 0, -popupDistance ],
			'bottom-left': [ 0, -popupDistance ],
			'bottom-right': [ 0, -popupDistance ],
			'left': [ popupDistance, 0 ],
			'right': [ -popupDistance, 0 ]
		}
	}

	// Paint for marker Icons
	const markerPaintInactive = {
		'icon-opacity': [
			'case',
			[ 'boolean',
				[ 'feature-state', 'active' ],
				false
			],
			0,
			1,
		],
	};

	const markerPaintActive = {
		'icon-opacity': [
			'case',
			[ 'boolean',
				[ 'feature-state', 'active' ],
				false
			],
			1,
			0,
		],
	};


	// MapBox On Load
	map.on('load', function() {
		// Load all marker images
		const images = [
			{ url:'/content/themes/base/assets/img/map/pin-greenbrier.png',id:'pin-greenbrier' },
			{ url:'/content/themes/base/assets/img/map/pin-greenbrier.png',id:'pin-greenbrier-selected' }
		];

		Promise.all(
			images.map(img => new Promise((resolve, reject) => {

				map.loadImage(img.url, function (error, res) {
					if (error) throw error;
					map.addImage(img.id, res)
					resolve();
				})
			}))
		).then(function() {
			// Add Markers Source
			map.addSource( 'markers', {
				type: 'geojson',
				data: geojson,
				cluster: true,
				clusterRadius: 40,
				generateId: true
			});

			map.addSource( 'markersNoCluster', {
				type: 'geojson',
				data: geojsonNoCluster,
				generateId: true
			});

			// Main Markers
			map.addLayer({
				id: 'points',
				type: 'circle',
				source: 'markers',
				filter: [ 'all',
					[ '!has', 'point_count' ],
					[ '!=', 'category', 'greenbrier' ]
				],
				paint: {
					'circle-opacity': 0,
					'circle-stroke-color': '#003349',
					'circle-stroke-width': 1,
					'circle-radius': 14
				}
			});

			map.addLayer({
				id: 'points-inner',
				type: 'circle',
				source: 'markers',
				filter: [ 'all',
					[ '!has', 'point_count' ],
					[ '!=', 'category', 'greenbrier' ]
				],
				paint: {
					'circle-color': [
						'case',
						[ 'boolean',
							[ 'feature-state', 'active' ],
							false
						],
						'#00635B', // Active
						'#003349', // Inactive
					],
					'circle-radius': 11.25
				}
			});

			map.addLayer({
				id: 'clusters',
				type: 'circle',
				source: 'markers',
				filter: [ 'has', 'point_count' ],
				paint: {
					'circle-color': [
						'case',
						[ 'boolean',
							[ 'feature-state', 'active' ],
							false
						],
						'#00635B', // Active
						'#003349', // Inactive
					],
					'circle-radius': 14
				}
			});

			map.addLayer({
				id: 'clusters-count',
				type: 'symbol',
				source: 'markers',
				filter: [ 'has', 'point_count' ],
				layout: {
					'text-allow-overlap': true,
					'text-field': '{point_count_abbreviated}',
					'text-offset': [0,-0.05],
					'text-size': 16,
				},
				paint: {
					'text-color': '#ffffff'
				}
			});

			// greenbrier Pin
			map.addLayer({
				id: 'points-hotel',
				type: 'symbol',
				source: 'markersNoCluster',
				filter: [ 'all',
					[ '!has', 'point_count' ],
					[ '==', 'category', 'greenbrier' ]
				],
				layout: {
					'icon-image': 'pin-{category}',
					'icon-anchor': 'bottom',
					'icon-allow-overlap': true
				},
				paint: markerPaintInactive
			});

			map.addLayer({
				id: 'points-active',
				type: 'symbol',
				source: 'markersNoCluster',
				filter: [ 'all',
					[ '!has', 'point_count' ],
					[ '==', 'category', 'greenbrier' ]
				],
				layout: {
					'icon-image': 'pin-{category}-selected',
					'icon-anchor': 'bottom',
					'icon-allow-overlap': true
				},
				paint: markerPaintActive
			});

			// Set Default Filter on Page Load
			if (defaultFilter !== 'all') {
				const $defaultFilterButton = $interactiveMapLayout.find(`.js-category-select[data-category="${defaultFilter}"]`);
				// $defaultFilterButton.addClass('active');
				$defaultFilterButton.click();
				// Filter the Markers based on the default category
				const filterMarkers = geojson.features.filter(marker => marker.properties.categories.split(',').includes(defaultFilter));
				const filterGeojson = {
					type: 'FeatureCollection',
					features: filterMarkers
				};

				map.getSource('markers').setData(filterGeojson);
			} else {
				$allCategoriesButton.click();
			}


			// Toggle Popups and Active States for Main Markers
			map.on( 'click', ['points', 'points-hotel'], function( e ) {
				activeMarkerId = e.features[0].id;
				const $popupID =  $interactiveMapLayout.find( '#map-popup-' + e.features[0].properties.id );

				map.setFeatureState({
					source: 'markers',
					id: activeMarkerId
				}, {
					active: true
				});

				let markerPopupSettings = popupSettings;

				if (e.features[0].properties.category === 'greenbrier') {
					markerPopupSettings = { ...popupSettings }; // Shallow clone
					markerPopupSettings.offset = {
						'top': [ 0, 2 ],
						'top-left': [ 0, 2 ],
						'top-right': [ 0, 2 ],
						'bottom': [ 0, -50 ],
						'bottom-left': [ 0, -102 ],
						'bottom-right': [ 0, -102 ],
						'left': [ 37, -50 ],
						'right': [ -37, -50 ]
					}
				}

				const popup = new mapboxgl.Popup(markerPopupSettings).setLngLat([ $popupID.data('long'), $popupID.data('lat') ]).setHTML($popupID.html());
				activePop = popup;

				$(popup).attr('data-id', activeMarkerId);
				popup.on( 'open', function() {
					const $popupElement = $(popup.getElement());
					const $closeBTN = $popupElement.find('.mapboxgl-popup-close-button')
					$popupElement.find('.mapboxgl-popup-content').prepend($closeBTN);
					$closeBTN.focus();
				});
				popup.on( 'close', function() {
					activePop = null;
					activeMarkerId = null;
					map.setFeatureState({
						source: 'markers',
						id: $(popup).attr('data-id')
					}, {
						active: false
					});
				});
				centerPopupPin(popup, map.project([ $popupID.data('long'), $popupID.data('lat') ]));
			});

			// Toggle Popups and Active States for Cluster Markers
			map.on( 'click', 'clusters', function( e ) {
				const coordinates = e.features[0].geometry.coordinates;
				const features = map.queryRenderedFeatures( e.point, {
					layers: [ 'clusters' ]
				});

				activeClusterId = features[0].properties.cluster_id;
				const count = features[0].properties.point_count;
				const source = map.getSource('markers');

				map.setFeatureState({
					source: 'markers',
					id: activeClusterId
				}, {
					active: true
				});

				source.getClusterLeaves( activeClusterId, count, 0, function( err, features ) {
					let popupHTML = '<div class="splide js-map-popup-slider">';
					popupHTML += '<div class="splide__track">';
					popupHTML += '<ul class="interactive-map__cluster-list splide__list">';

					$(features).each(function() {
						const $popupID =  $interactiveMapLayout.find('#map-popup-' + this.properties.id);
						popupHTML += '<li class="splide__slide">';
						popupHTML += $popupID.html();
						popupHTML += '</li>';
					});

					popupHTML += '</ul>'; // splide__list
					popupHTML += '</div>'; // splide__track
					popupHTML += '<div class="splide__arrows">';
					popupHTML += '<button class="splide__arrow splide__arrow--prev" aria-label="View the previous slide" data-glide-dir="<"></button>';
					popupHTML += '<span class="js-map-current-activity">1</span> / <span>' + features.length + '</span>';
					popupHTML += '<button class="splide__arrow splide__arrow--next" aria-label="View the next slide" data-glide-dir=">"></button>';
					popupHTML += '</div>'; // splide__arrows
					popupHTML += '</div>'; // splide

					const popup = new mapboxgl.Popup(popupSettings).setLngLat(coordinates).setHTML(popupHTML);
					activePop = popup;

					$(popup).attr('data-id', activeClusterId);
					popup.on( 'open', function() {
						const $popupElement = $(popup.getElement());
						const $closeBTN = $popupElement.find('.mapboxgl-popup-close-button')
						$popupElement.find('.mapboxgl-popup-content').prepend($closeBTN);
						$closeBTN.focus();

						// Create the slider
						const $slider = $popupElement.find('.js-map-popup-slider');
						const slider = new Splide($slider[0], {
							type: 'fade',
							pagination: false,
							perMove: 1,
							perPage: 1,
							updateOnMove: true
						});

						slider.mount();

						slider.on('moved', function(newIndex) {
							$slider.find( '.js-map-current-activity' ).text( newIndex + 1 );
						});
					});
					popup.on( 'close', function() {
						activePop = null;
						activeClusterId = null;
						map.setFeatureState({
							source: 'markers',
							id: $(popup).attr('data-id')
						}, {
							active: false
						});
					});
					centerPopupPin(popup, map.project(coordinates));
				});
			});


			// Change the cursor for map markers
			map.on( 'mousemove', 'clusters', function() {
				map.getCanvas().style.cursor = 'pointer';
			});

			map.on( 'mouseleave', 'clusters', function() {
				map.getCanvas().style.cursor = '';
			});

			map.on( 'mousemove', ['points', 'points-hotel'], function() {
				map.getCanvas().style.cursor = 'pointer';
			});

			map.on( 'mouseleave', ['points', 'points-hotel'], function() {
				map.getCanvas().style.cursor = '';
			});


			// Reset map State on Zoom
			map.on('zoomend', function() {
				if (!zoomUserChanged) {
					if ($window.width() < 768 && !isNaN(mobileZoomLevel)) {
						zoomUserChanged = map.getZoom() !== mobileZoomLevel;
					} else if (!isNaN(zoomLevel)) {
						zoomUserChanged = map.getZoom() !== zoomLevel;
					}
				}

				resetActiveFeature();
			});
		});
	});


	// Reset active state of marker icons
	const resetActiveFeature = function() {
		// Remove the currently active popup if there is one
		if (activePop) {
			activePop.remove();
			activePop = null;
		}

		// Reset Default Markers
		if (activeMarkerId) {
			map.setFeatureState({
				source: 'markers',
				id: activeMarkerId
			}, {
				active: false
			});

			activeMarkerId = null;
		}

		// Reset Cluster Markers
		if (activeClusterId) {
			map.setFeatureState({
				source: 'markers',
				id: activeClusterId
			}, {
				active: false
			});

			activeClusterId = null;
		}
	}


	// Close Marker Popup on Esc keydown
	$document.on('keydown', function (event) {
		if (event.key === 'Escape' && activePop) {
			resetActiveFeature();
		}
	});

	const centerPopupPin = function(popup = null, pinCoordinate = null) {
		if (!popup || !pinCoordinate) { return false; }

		const mapCenter = map.project(map.getCenter());

		if ($window.width() >= 1280) {
			console.log('b' + pinCoordinate.y);
			pinCoordinate.y = pinCoordinate.y - 200;
			console.log('a' + pinCoordinate.y);
		} else if ($window.width() >= 768) {
			console.log('b' + pinCoordinate.y);
			pinCoordinate.y = pinCoordinate.y + 75;
			console.log('a' + pinCoordinate.y);
		}
		const latDiff = Math.round(pinCoordinate.y) - Math.round(mapCenter.y);

		if (latDiff > 20 || latDiff < -20) {
			setTimeout(function() {
				popup.addTo(map);
			}, 1000);
		} else {
			popup.addTo(map);
		}

		map.easeTo({
			center: map.unproject(pinCoordinate),
			duration: 1000
		});
	}


	// Category Marker Filtering
	$categorySelectButtons.on( 'click', function() {
		const $categoryButton = $(this);
		const category = $categoryButton.data('category');

		// Close the Mobile DropDown
		if ( $mobileCategoryToggle.css('display') !== 'none' ) {
			toggleDropdown();
		}

		// Toggle Current Active Marker Off
		resetActiveFeature();

		// Set Category to Active in the List
		$allCategoriesButton.removeClass( 'active' );
		$categorySelectButtons.removeClass( 'active' );
		$categoryButton.addClass( 'active' );

		// Filter the Markers
		let filterMarkers = [];
		geojson.features.forEach( function( marker ) {
			if (marker.properties.categories.split(',').includes(category)) {
				filterMarkers.push(marker);
			}
		});

		const filterGeojson = {
			type: 'FeatureCollection',
			features: filterMarkers
		};

		map.getSource( 'markers' ).setData( filterGeojson );

		// Filter the No Cluster Markers
		let filterMarkersNoCluster = [];
		geojsonNoCluster.features.forEach( function( marker ) {
			if (marker.properties.categories.split(',').includes(category)) {
				filterMarkersNoCluster.push(marker);
			}
		});

		const filterGeojsonNoCLuster = {
			type: 'FeatureCollection',
			features: filterMarkersNoCluster
		};

		map.getSource( 'markersNoCluster' ).setData( filterGeojsonNoCLuster );
	});

	$allCategoriesButton.on( 'click', function() {
		$categorySelectButtons.removeClass( 'active' );
		$(this).addClass('active');

		// Close the Mobile DropDown
		if ( $mobileCategoryToggle.css('display') !== 'none' ) {
			toggleDropdown();
		}

		// Toggle Current Active Marker Off
		resetActiveFeature();

		map.getSource( 'markers' ).setData( geojson );
		map.getSource( 'markersNoCluster' ).setData( geojsonNoCluster );
	});


	// Open Category Select on Mobile
	$mobileCategoryToggle.on( 'click', function() {
		if ( !$(this).hasClass('active') ) {
			$(this).addClass('active');
			$categoryList.slideToggle();
			resetActiveFeature();
		} else {
			toggleDropdown();
		}
	});

	// Close Category Select Dropdown
	const toggleDropdown = function() {
		if ( $mobileCategoryToggle.hasClass('active') ) {
			$mobileCategoryToggle.removeClass('active');
			$categoryList.slideToggle();
		}
	}
}