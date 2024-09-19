$(window).on('load', function() {
	initialize();
});

function initialize() {
	
	let wrappers = document.querySelectorAll( '.js-video' );

	wrappers.forEach(wrapper => {

		// Set required variables based on video attributes and parameters
		let video = wrapper.querySelector( '[data-video-id=' + wrapper.getAttribute( 'data-video' ) + ']' );
		let source = wrapper.querySelectorAll('source')[0].getAttribute('data-src');
		let playPause = wrapper.querySelector( '.js-video-control' );
		let posterButton = wrapper.querySelector( '.js-video-poster-button' );
		let autoPlay = ( video.getAttribute('autoplay') !== null );
		let loadOnMobile = ( video.getAttribute('mobile-loaded') !== null );
		
		// If the 'loadOnMobile' parameter is true OR if the screen size is larger than 768px, display the video
		if ( loadOnMobile || ( window.matchMedia("(min-width: 768px)").matches ) ) {
			
			// If the 'autoplay' parameter is true, play the video right away
			if ( autoPlay ) {
				playVideo( video, source, playPause, posterButton );
			}
			
			// Confirm the poster exists, then add listener for playing the video
			if ( posterButton ) {
				
				posterButton.addEventListener('click', () => {
					playVideo( video, source, playPause, posterButton );
				});
			}
		}
		
		// Hide the video and display the fallback/poster image
		else {
			wrapper.classList.add('video-hidden');
		}
	});
}

function playVideo ( video, source, playPause, posterButton ) {

	// Retrieve the video url from the data-source attribute and use it to set the video source
	video.setAttribute('src', source );
	
	// Play the video and hide the fallback/poster image
	video.play().then( () => {
		
		posterButton.classList.toggle('basic-video__poster-button--hiding');
		posterButton.classList.toggle('basic-video__poster-button--hidden');

		if ( playPause ) {
			
			// Set initial attributes for the play/pause control
			playPause.setAttribute( 'data-playing', ( 'true' ));
			playPause.setAttribute( 'aria-label', 'Pause the video' );
			
			// When the play/pause button is clicked, toggle the video and update the play/pause control attributes
			playPause.addEventListener( 'click', function(e) {

				if (playPause.getAttribute( 'data-playing' ) === 'true') {
					video.pause();
					playPause.setAttribute( 'data-playing', 'false' );
					playPause.setAttribute( 'aria-label', 'Play the video' );
				}

				else {
					video.play();
					playPause.setAttribute( 'data-playing', 'true' );
					playPause.setAttribute( 'aria-label', 'Pause the video' );
				}
				
				e.stopImmediatePropagation();
			});

			// When the video ends, update the data-playing attribute and display the fallback/poster image
			video.addEventListener( 'ended', function() {
				
				playPause.setAttribute( 'data-playing', ( 'false' ));
				playPause.setAttribute( 'aria-label', 'Play the video' );
				posterButton.classList.toggle('basic-video__poster-button--hiding');
				posterButton.classList.toggle('basic-video__poster-button--hidden');
				
			});
		}
	});
}