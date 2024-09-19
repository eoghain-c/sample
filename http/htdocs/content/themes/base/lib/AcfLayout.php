<?php

/**
 * ACF Layout
 *
 * Logical layout automation for Advanced Custom Fields Flexible Content Field.
 * {@link http://www.advancedcustomfields.com/}
 *
 * Based on {@link https://gist.github.com/beaucharman/7181406}
 */
class AcfLayout
{
	/**
	 * Path of where the layout templates are found,
	 * relative to the theme template directory.
	 */
	const LAYOUT_DIRECTORY = '/templates/layouts/';


	/**
	 * An instance of $data that is accessible in the layout
	 *
	 * @example print_r(ACFLayout::$layoutData);
	 * @var array
	 */
	public static array $layoutData;


	/**
	 * Get Layout
	 *
	 * @param string     $layout
	 * @param array|null $data
	 *
	 * @return string
	 */
	static function get_layout(string $layout, array $data = null)
	{
		$full_layout_directory = get_template_directory() . self::LAYOUT_DIRECTORY;
		$layout_file = '{{layout}}.php';
		$find = array('{{layout}}', '_');
		$replace = array($layout, '-');

		// Save $data to the class
		self::$layoutData = $data;

		// Find a file that matches this_format
		$new_layout_file = str_replace($find[0], $replace[0], $layout_file);

		// compileTemplate() uses extract() to convert $data['row'] into a variable for each field
		// in the layout
		if (file_exists($full_layout_directory . $new_layout_file)) {
			return compileTemplate($full_layout_directory . $new_layout_file, $data['row'], true, false);
		} else {
			// Find a file that matches this-format
			$new_layout_file = str_replace($find, $replace, $layout_file);

			if (file_exists($full_layout_directory . $new_layout_file)) {
				return compileTemplate($full_layout_directory . $new_layout_file, $data['row'], true, false);
			}
		}

		// If no files can be matched, and WP DEBUG is true: show a warning.
		if (WP_DEBUG) {
			echo "<pre>ACF_Layout: No layout template found for $layout.</pre>";
		}

		return false;
	}


	/**
	 * Render
	 *
	 * @param string     $layout
	 * @param array|null $data
	 *
	 * @return string
	 */
	static function render(string $layout, array $data = null)
	{
		return self::get_layout($layout, $data);
	}
}