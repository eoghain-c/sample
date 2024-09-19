## Basic Video Component
This component allows the addition of a video to any layout with controls for accessibility and various options for playback.

If required, the included ACF field provides a clone that can be used as a starting point for ease of setup.

### Usage
Poster Image: This is a single source image used to display as the video thumbnail before play starts. It is not responsive, so choose an appropriate size for the largest area that will be required.

Fallback Image: This can be a single image or picture element that will display below 768px instead of the video. Source(s) should be chosen to display an appropriately sized image in the space provided.

Controls: This options allows for advanced controls to be added to the video. These controls include play/pause, timeline scrubber, fullscreen and volume controls. By default, a play/pause button is added for accessibility and this will only be needed when an extra level of control is required.

Muted, Loop, Autoplay: Basic options for video playback. The video will always be muted when autoplaying for accessibility reasons.

### Example
Adding the clone as a group will provide a preformatted array that can be passed through to the component with minimal processing.

```
$video = get_field( 'video' );

if( !empty( $video[ 'poster_image' ] ) ) {
    $video[ 'poster' ] = $video[ 'poster_image' ][ 'sizes' ][ '940x600' ];
}

if( !empty( $video[ 'fallback_image' ] ) ) {
    $fallback = array(
        'fallback'  =>  $video[ 'fallback_image' ][ 'sizes' ][ '768x420' ],
        'alt_text'  =>  $video[ 'fallback_image' ][ 'alt' ],
        'sources'   =>  array(
            '576'   =>  $video[ 'fallback_image' ][ 'sizes' ][ '768x420' ],
            '0'     =>  $video[ 'fallback_image' ][ 'sizes' ][ '560x310' ]
        )
    );
    $video[ 'fallback' ] = compileTemplate( 'picture.php', $fallback, false, false );
}

compileTemplate( 'video.php', $video );
```