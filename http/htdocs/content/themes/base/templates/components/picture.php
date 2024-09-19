<?php
/**
 * A picture tag with the appropriate markup, and lazyload
 *
 * @param array  $sources     ex: array(768 => '/path/to/768.jpg', 320 => '/path/to/320.jpg 1x, /path/to/320@2x.jpg 2x');
 * @param string $fallback    ex: '/path/to/fallback.jpg'
 * @param string $alt_text    Description of the image
 * @param string $class       [optional] default '',  ex: 'header__logo'
 * @param string $focal_point [optional]'50%' or '50% 50%'
 */


$class = (isset($class)) ? $class : '';
$focal_point = (isset($focal_point)) ? $focal_point : '50%';
?>


<picture class="<?= $class; ?>">
	<?php foreach ($sources as $key => $source): ?>
		<source media="(min-width: <?= $key; ?>px)" srcset="<?= $source; ?>">
	<?php endforeach; ?>

	<img src="<?= $fallback; ?>"
	     alt="<?= $alt_text; ?>"
	     loading="lazy"
	     draggable="false"
	     style="object-position: <?= $focal_point; ?>">
</picture>