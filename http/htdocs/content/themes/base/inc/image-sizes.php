<?php
/**
 * Featured images and custom image sizes
 *
 * Naming convention: <width>x<height>
 *
 * In layouts add a comment to the image/gallery field that lists what
 * crop sizes are used.
 *
 * add_image_size( $name, $width, $height, $crop );
*/

function ondesign_setup() {
	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 250, 200, true );

	add_image_size('225x125', 225, 125, true);
	add_image_size('250x150', 250, 150, true);
	add_image_size('325x150', 325, 150, true);
	add_image_size('325x225', 325, 225, true);
	add_image_size('325x425', 325, 425, true);
	add_image_size('350x375', 350, 375, true);
	add_image_size('350x425', 350, 425, true);
	add_image_size('375x275', 375, 275, true);
	add_image_size('400x200', 400, 200, true);
	add_image_size('400x300', 400, 300, true);
	add_image_size('400x500', 400, 500, true);
	add_image_size('425x500', 425, 500, true);
	add_image_size('450x525', 450, 525, true);
	add_image_size('500x650', 500, 650, true);
	add_image_size('575x275', 575, 275, true);
	add_image_size('575x375', 575, 375, true);
	add_image_size('575x550', 575, 550, true);
	add_image_size('575x800', 575, 800, true);
	add_image_size('600x300', 600, 300, true);
	add_image_size('600x500', 600, 500, true);
	add_image_size('675x550', 675, 550, true);
	add_image_size('700x650', 700, 650, true);
	add_image_size('725x275', 725, 275, true);
	add_image_size('725x425', 725, 425, true);
	add_image_size('725x500', 725, 500, true);
	add_image_size('725x550', 725, 550, true);
	add_image_size('775x375', 775, 375, true);
	add_image_size('775x550', 775, 550, true);
	add_image_size('775x725', 775, 725, true);
	add_image_size('800x600', 800, 600, true);
	add_image_size('900x375', 900, 375, true);
	add_image_size('975x425', 975, 425, true);
	add_image_size('1075x350', 1075, 350, true);
	add_image_size('1100x500', 1100, 500, true);
	add_image_size('1150x525', 1150, 525, true);
	add_image_size('1100x700', 1100, 700, true);
	add_image_size('1100x750', 1100, 750, true);
	add_image_size('1250x750', 1250, 750, true);
	add_image_size('1300x600', 1300, 600, true);
	add_image_size('1300x750', 1300, 750, true);
	add_image_size('1300x875', 1300, 875, true);
	add_image_size('1350x750', 1350, 750, true);
	add_image_size('1475x750', 1475, 750, true);
	add_image_size('1525x875', 1525, 875, true);
	add_image_size('1550x700', 1550, 700, true);
	add_image_size('1900x800', 1900, 800, true);
	add_image_size('1925x975', 1925, 975, true);
	add_image_size('1925x1100', 1925, 1100, true);
	add_image_size('2150x975', 2150, 975, true);
	add_image_size('2575x1100', 2575, 1100, true);
	add_image_size('2575x1200', 2575, 1200, true);


	// Cards (Wide enough to accommodate 2 columns, some expectations of 3)
	// Card (Default: Sized Medium [covers small])
	add_image_size('720x255', 720, 255, true);
	add_image_size('592x282', 592, 282, true);
	add_image_size('888x365', 888, 365, true);
	add_image_size('888x480', 888, 480, true);
	// Card (Tall)
	add_image_size('720x334', 720, 334, true);
	add_image_size('592x346', 592, 346, true);
	add_image_size('888x541', 888, 541, true);
	add_image_size('888x778', 888, 778, true);


}
add_action( 'after_setup_theme', 'ondesign_setup' );