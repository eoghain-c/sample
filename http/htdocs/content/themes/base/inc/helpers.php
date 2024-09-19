<?php
/**
 * Helper Functions
 */


if (!function_exists('getLinkArrayData')) {
	/**
	 * Get the data from clone so you can output a proper link
	 *
	 * @param array $linkArrayClone
	 *
	 * @return array
	 */
	function getLinkArrayData($linkArrayClone)
	{
		//Check to see if there any data
		if (!empty($linkArrayClone)) {
			$return_data = array();
			for ($l = 0; $l < sizeof($linkArrayClone); $l++) {

				//Check if the button itself is not empty
				if (!empty($linkArrayClone[$l] && $linkArrayClone[$l]['link_type'] != 'none' && !empty($linkArrayClone[$l]['link_text']))) {

					$return_data[$l]['link_type'] = $linkArrayClone[$l]['link_type'];
					$return_data[$l]['text'] = $linkArrayClone[$l]['link_text'];
					$return_data[$l]['style'] = $linkArrayClone[$l]['link_style'];
					$return_data[$l]['target'] = '';
					$return_data[$l]['parameters'] = (!empty($linkArrayClone[$l]['link_parameters'])) ? ($linkArrayClone[$l]['link_parameters']) : '';
					$return_data[$l]['link'] = '';
					$return_data[$l]['file_size'] = '';

					switch ($linkArrayClone[$l]['link_type']) {
						case 'internal':
							$return_data[$l]['link'] = $linkArrayClone[$l]['link_page'];
							break;
						case 'external':
							$return_data[$l]['link'] = $linkArrayClone[$l]['link_custom_url'];
							$return_data[$l]['target'] = 'target="_blank" rel="noopener"';
							break;
						case 'file':
							$return_data[$l]['link'] = $linkArrayClone[$l]['link_file'];
							$return_data[$l]['target'] = 'target="_blank"';

							$site_url = get_home_url();
							$filename = '/htdocs' . str_replace($site_url, "", $return_data[$l]['link']);
							$return_data[$l]['file_size'] = (is_file($filename) && filesize($filename) > 1) ? filesize($filename) : '';
							break;
						case 'email':
							$return_data[$l]['link'] = (stripos($linkArrayClone[$l]['link_email'], 'mailto:') !== false) ? $linkArrayClone[$l]['link_email'] : 'mailto:' . $linkArrayClone[$l]['link_email'];
							break;
					}
				}

			}

			return $return_data;
		}
	}
}


if (!function_exists('outputLinkArray')) {
	/**
	 * Output a link returned from getLinkArrayData()
	 *
	 * @param array $link_data - in format returned from getLinkArrayData()
	 * @param string $additional_classes
	 */
	function outputLinkArray($link_array_data, $additional_classes = '')
	{
		if (!empty($link_array_data)) {
			for ($l = 0; $l < sizeof($link_array_data); $l++) {
				if (!empty($link_array_data[$l]) && !empty($link_array_data[$l]['link'])) {
					$link_data = $link_array_data[$l];

					if ($link_data['link_type'] == 'file') { ?>
						<a href="<?php echo $link_data['link']; ?><?php echo $link_data['parameters']; ?>"
						   class="<?php echo $link_data['style']; ?> <?php echo $additional_classes; ?>"
						   title="<?php echo $link_data['text']; ?>" <?php echo $link_data['target']; ?>><?php echo $link_data['text']; ?>
						</a>
					<?php } else { ?>
						<a href="<?php echo $link_data['link']; ?><?php echo $link_data['parameters']; ?>"
						   class="<?php echo $link_data['style']; ?> <?php echo $additional_classes; ?>"
						   title="<?php echo $link_data['text']; ?>" <?php echo $link_data['target']; ?>><?php echo $link_data['text']; ?>
						</a>
						<?php
					}
				}
			}
		}
	}
}


if (!function_exists('getFeaturedImageByPostId')) {
	/**
	 * Get crop sizes of featured image by post id
	 *
	 * @param int $post_ID
	 * @param array $image_sizes
	 *
	 * @return array
	 */
	function getFeaturedImageByPostId($post_ID, $image_sizes)
	{
		$return_data = array();

		// get img id
		$featured_img_id = get_post_thumbnail_id($post_ID);

		if ($featured_img_id) {
			// get alt text
			$return_data['alt'] = get_post_meta($featured_img_id, '_wp_attachment_image_alt', true);

			// get images
			foreach ($image_sizes as $image_size) {
				$image_data = wp_get_attachment_image_src($featured_img_id, $image_size);

				$return_data['sizes'][$image_size] = $image_data[0];
				$return_data['sizes'][$image_size . '-width'] = $image_data[1];
				$return_data['sizes'][$image_size . '-height'] = $image_data[2];

				//make path relative so images can work
				$return_data['sizes'][$image_size][0] = wp_make_link_relative($return_data['sizes'][$image_size][0]);
			}

			return $return_data;
		} else {
			$default_images = get_field('default_featured_image', 'options');

			if (!empty($default_images)) {
				// set alt text
				$return_data['alt'] = get_bloginfo('name');

				foreach ($image_sizes as $image_size) {
					$return_data['sizes'][$image_size][0] = $default_images['sizes'][$image_size];
					$return_data['sizes'][$image_size][0] = wp_make_link_relative($return_data['sizes'][$image_size][0]);
				}

				return $return_data;
			} else {
				return null;
			}
		}
	}
}


if (!function_exists('includeWithVariables')) {
	/**
	 * Use to pass data to a template file.  Pass variables as a key => value array.  It will output or return the code.<br>
	 * https://stackoverflow.com/a/45563622
	 *
	 * @deprecated Replaced by {@see compileTemplate()}
	 * @param string $filePath
	 * @param array $variables
	 * @param bool $print
	 *
	 * @return false|string|null
	 */
	function includeWithVariables($filePath, $variables = array(), $print = true)
	{
		$output = NULL;
		if (file_exists($filePath)) {
			// Extract the variables to a local namespace
			extract($variables);

			// Start output buffering
			ob_start();

			// Include the template file
			include $filePath;

			// End buffering and return its contents
			$output = ob_get_clean();
		}
		if ($print) {
			print $output;
		}

		return $output;
	}
}


if (!function_exists('compileTemplate')) {
	/**
	 * Compiles a template file.  Pass variables as a key => value array.  It will output or return the code.
	 * There is basic error handling if the template file is not found.<br>
	 * Modified from: https://stackoverflow.com/a/45563622
	 *
	 * @param string $filePath   If a single filename is passed, look in 'theme'/templates/components/, else look in
	 *                           'theme'/templates/layouts/.<br>
	 *                           '.php' can be excluded. ex: 'breadcrumbs.php' vs 'breadcrumbs'
	 * @param array  $variables  [optional] key => value array
	 * @param bool   $customPath [optional] true: do not modify $filepath at all
	 * @param bool   $print      [optional] true: output the template
	 *
	 * @return string|null Returns null, the compiled template, or an error.
	 */
	function compileTemplate(string $filePath, array $variables = array(), bool $customPath = false, bool $print = true): ?string
	{
		$output = null;

		// Check if file extension (.php) is part of $filePath.  If not, add it
		if (strpos($filePath, '.php') == false) {
			$filePath .= '.php';
		}

		if ($customPath == false) {
			if (strpos($filePath, '/') !== false) { // if not just a filename
				$filePath = get_template_directory() . $filePath;
			} else { // see if file is a component or layout
				if (file_exists(get_template_directory() . '/templates/components/' . $filePath)) {
					$filePath = get_template_directory() . '/templates/components/' . $filePath;
				} elseif (file_exists(get_template_directory() . '/templates/layouts/' . $filePath)) {
					$filePath = get_template_directory() . '/templates/layouts/' . $filePath;
				}
			}
		}

		if (file_exists($filePath)) {
			// Extract the variables to a local namespace
			extract($variables);

			// Start output buffering
			ob_start();

			// Include the template file
			include $filePath;

			// End buffering and return its contents
			$output = ob_get_clean();

		} elseif (WP_DEBUG) {
			$output = 'Error: ' . $filePath . ' could not be found';
		}

		if ($print) {
			print $output;
		}

		return $output;
	}
}


if (!function_exists('get_icon')) {
	/**
	 * Return an svg icon
	 * For decorative icons the $label param should be left blank.  If the icon is not decorative, pass the label param for a11y.
	 *
	 * @param string $name  The filename (excluding extension) of the icon
	 * @param string $label An aria-label
	 *
	 * @return bool|string
	 */
	function get_icon($name, $label = '') {
		if (!file_exists(dirname(__DIR__) . '/assets/img/icons/' . esc_html($name) . '.svg')) {
			trigger_error('The icon "' . esc_html($name) . '" does not exist.', E_USER_WARNING);
			return false;
		}

		// Cache bust icons
		$filename = dirname(__DIR__) . '/assets/img/icons/' . $name . '.svg';
		$cacheBust = '';

		if (file_exists($filename)) {
			$cacheBust = "?v=" . filemtime($filename);
		}

		return '<svg class="v-icon__svg v-icon__svg--' . esc_attr(sanitize_title($name)) . '"' . (trim($label) ? ' role="img" aria-label="' . esc_attr($label) . '"' : ' role="presentation"') . '><use xlink:href="/content/themes/base/assets/img/icons/' . esc_attr($name) . '.svg' . $cacheBust . '#' . esc_attr($name) . '"></use></svg>';
	}
}


if (!function_exists('display_icon')) {
	/**
	 * Output an svg icon
	 * For decorative icons the $label param should be left blank.  If the icon is not decorative, pass the label param for a11y.
	 *
	 * @param string $name  The filename (excluding extension) of the icon
	 * @param string $label An aria-label
	 */
	function display_icon($name, $label = '') {
		echo get_icon($name, $label);
	}
}


if (!function_exists('print_pre')) {
	/**
	 * Output a formatted array for debugging
	 *
	 * @param $data
	 */
	function print_pre($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
}

if (!function_exists('console_log')) {
	/**
	 * Output a formatted array for debugging
	 *
	 * @param $data
	 */
	function console_log($data)
	{
		echo '<script>console.log(' .json_encode($data) .')</script>';
	}
}

if (!function_exists('get_child_ids')) {
	/**
	 * Recursively returns child page IDs
	 *
	 * @param integer $post_parent ID of post
	 *
	 * @return string
	 */
	function get_child_ids($post_parent)
	{
		$child_ids = '';

		$pages = new WP_Query([
			'post_type' => 'page',
			'post_parent' => $post_parent,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'fields' => 'ids'
		]);

		if ($pages->have_posts()) {
			foreach ($pages->get_posts() as $page_id) {
				$child_ids .= $page_id . ',' . get_child_ids($page_id);
			}
		}

		return $child_ids;
	}
}

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

if (!function_exists('generateBookingLink')) {
	/**
	 * Generate a booking link with the room ID
	 * @param $id
	 * @return array
	 */
	function generateBookingLink($id): array
	{
		return array(
				'link_type' => 'link',
				'link_style' => 'link link__btn',
				'link' => array(
						'url' => 'https://res.windsurfercrs.com/ibe/details.aspx?propertyID=16583&rmID='.$id,
						'target' => '_blank',
						'title' => 'Book Today'
				),
		);
	}
}
