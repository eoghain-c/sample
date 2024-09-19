# Version 0.0.1

## Media Component
The media component allows the addition of video/picture/ or slide show to any layout with controls without having to program the functions.

Required components:
- Picture
- Video
- Splide Wrapper

the included ACF field provides a clone that can be used as a starting point for ease of setup.

### Setup
Add the Clone to ACF.
Move media.php to components directory
Add media_image function to helpers.php See Functions
Create a Layout and include the Clone: Media as set Display as "Group (display selected fields in a group within this file)"
Pass the grouped value into media.php

### Example

Layout.php
(group name $media)
````
    compileTemplate('media', $media);
````
### Defaults
```
image_size = 'url' // Default files size. 

Image size overwriting for custom sizing: 
    $media['image_sizes'] = [
        '0'     => '250x250',
        '1024'   => '550x625',
    ];


$slider_default = '{"type":"loop", "perPage": "2", "drag":"1", "perMove":1, "gap":"32px", "padding":{"left":"0","right":"0"}}';

Note: Can cantain all options used by splide
$slider_breakpoints = ['1279 => {"perPage":2}', '767  => {"perPage":1}'];
```
#### Functions
```
if (!function_exists('media_image')) {
	/**
	 * Output a formatted array image sizes
	 *
	 * @param $image [required] acf imgage array
	 * @param $sizes [required] array of image sizes
	 *  ex. $sizes = [
		'0'     => '775x775',
		'768'   => '1025x1000',
		'1280'   => '1450x1000',
		'1440'   => '1925x1200',
		'1920'   => '2550x1200',
	];
	 */
	function media_image($image, $sizes){
		if( empty($image) || empty($sizes)) { return false;}
		$sources = [];
		foreach ( $sizes as $key => $value ) {
			if($value == 'url') {
				$sources[$key] = $image['url'];
			} else {
				$sources[$key] = $image['sizes'][$value];
			}
		}
		krsort($sources);
		return [
			'sources' => $sources,
			'fallback' => $image['url'],
			'alt_text' => $image['alt'],
			'class' => " basic-picture media__picture"
		];
	}
}
```
