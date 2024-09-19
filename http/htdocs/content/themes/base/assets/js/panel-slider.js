$(function() {
	panelSlider();
});

const panelSlider = function() {
	$('.js-panel-slider').each(function() {
		const $layout = $(this);
		const $mediaSlider = $layout.find('.js-panel-slider-media-slider > .splide');
		const $contentWrapper = $layout.find('.js-content-wrapper');
		const $contentSlider = $layout.find('.js-panel-slider-content-slider > .splide');
		const $videos = $mediaSlider.find('.js-video');

		// Create the Splide Sliders
		if ($mediaSlider.length) {
			const withPeek = $mediaSlider.parent().hasClass('js-with-peek');

			const mediaSplide = new Splide($mediaSlider.get(0), {
				type: "loop",
				gap: withPeek ? 26 : 0,
				mediaQuery: 'min',
				pagination: false,
				updateOnMove: true,
				breakpoints: withPeek ? {
					768: {
						gap: 59,
					},
					1280: {
						gap: 48,
					}
				} : {}
			});

			let contentSplide;
			if ($contentSlider.length) {
				contentSplide = new Splide($contentSlider.get(0), {
					type: "loop",
					pagination: false,
					updateOnMove: true,
				});
			}

			// Handle Videos
			if ($videos.length) {
				let dragging = false;

				const playSlideVideo = function(index) {
					const $slides = $mediaSlider.find('.splide__slide:not(.splide__slide--clone)');
					const $nextSlide = $slides.eq(index);
					const $nextVideo = $nextSlide.find('.js-video');

					// Play next video if autoplay
					if ($nextVideo.length && $nextVideo.hasClass('autoplay')) {
						const $playPause = $nextSlide.find('.js-video-control');

						if ($nextSlide.find('.js-video > video').attr('src')) {
							$nextSlide.find('video').get(0).play();
							$playPause.get(0).setAttribute( 'data-playing', 'true' );
							$playPause.get(0).setAttribute( 'aria-label', 'Pause the video' );
						} else {
							const video = $nextSlide.find('video').get(0);
							const source = $nextSlide.find('source').get(0).getAttribute('data-src');
							const playPause = $playPause.get(0);
							const posterButton = $nextSlide.find('.js-video-poster-button').get(0);
							video.muted = true;
							playVideo( video, source, playPause, posterButton );

							// Prep Cloned Video
							$mediaSlider.find('[data-video=' + $nextVideo.data( 'video' ) + ']').each(function() {
								const $slide = $(this).parents('.splide__slide');

								if ($slide.hasClass('splide__slide--clone')) {
									const cloneVideo = $slide.find('video').get(0);
									const cloneSource = $slide.find('source').get(0).getAttribute('data-src');
									const clonePlayPause = $slide.find('.js-video-control').get(0);
									const clonePosterButton = $slide.find('.js-video-poster-button').get(0);

									cloneVideo.setAttribute('src', cloneSource );
									cloneVideo.muted = true;
									clonePosterButton.classList.toggle('basic-video__poster-button--hiding');
									clonePosterButton.classList.toggle('basic-video__poster-button--hidden');

									clonePlayPause.addEventListener( 'click', function(e) {

										if (clonePlayPause.getAttribute( 'data-playing' ) === 'true') {
											cloneVideo.pause();
											clonePlayPause.setAttribute( 'data-playing', 'false' );
											clonePlayPause.setAttribute( 'aria-label', 'Play the video' );
										}

										else {
											cloneVideo.play();
											clonePlayPause.setAttribute( 'data-playing', 'true' );
											clonePlayPause.setAttribute( 'aria-label', 'Pause the video' );
										}

										e.stopImmediatePropagation();
									});
								}
							});
						}
					}
				}

				const pauseSlideVideos = function() {
					const $playPause = $mediaSlider.find( '.js-video-control' );

					$playPause.each(function() {
						const $button = $(this);

						if ($button.attr('data-playing') === 'true') {
							const $slide = $button.parents('.splide__slide');
							const video = $slide.find('video').get(0);
							video.pause();
							$button.get(0).setAttribute( 'data-playing', 'false' );
							$button.get(0).setAttribute( 'aria-label', 'Play the video' );

							// Update Clone Slide Progress
							$mediaSlider.find('[data-video=' + $slide.find('.js-video').data( 'video' ) + ']').each(function() {
								const $parent = $(this).parents('.splide__slide');

								if (!$parent.is($slide)) {
									$parent.find('video').get(0).currentTime = video.currentTime;
								}
							});
						}
					});
				}

				// Play Video on Mount
				mediaSplide.on('mounted', function () {
					playSlideVideo(mediaSplide.index);
				});

				// Pause Active Video on Drag
				mediaSplide.on('drag', function () {
					dragging = true;
					pauseSlideVideos();
				});

				// Pause Active Video on Move
				mediaSplide.on('move', function (newIndex, prevIndex) {
					if (!dragging) {
						pauseSlideVideos();
					}
				});

				// Play Video on Active Slide
				mediaSplide.on('moved', function (newIndex) {
					dragging = false;
					playSlideVideo(newIndex);
				});
			}

			// Sync and Init the Sliders
			if (contentSplide) {
				mediaSplide.sync(contentSplide);
				mediaSplide.mount();
				contentSplide.mount();
			} else {
				mediaSplide.mount();
			}

			// Reposition the Arrows
			if (!$layout.hasClass('panel-slider--gallery-custom') && !$layout.hasClass('panel-slider--peek') && $contentWrapper.length) {
				$mediaSlider.find('.splide__arrows').appendTo($contentWrapper);
			}
		}
	});
}