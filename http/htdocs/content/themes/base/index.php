<?php
$pageOutput = '';
$data = array();
$data['layout_num'] = 0;

// If $pageContent is not set from compileTemplate
if (empty($page_content)) {
	// Check if ACF is installed and loop through custom flex layouts
	if (function_exists('has_sub_field')) {
		while (have_rows('page_layout')) { // Loop through Flexible Content
			the_row();

			// Send $data as variable so we can use each field as a variable immediately.
			$data['row'] = get_row(true);
			$data['layout_num']++;
			$pageOutput .= ACFLayout::render(get_row_layout(), $data);
		}
	}
} else { // If $pageContent is set, add it to output
	$pageOutput .= $page_content;
}

// Compile the header layouts
ob_start();
include 'templates/layouts/header.php';
$header = ob_get_clean();

// Compile the footer layouts
ob_start();
include 'templates/layouts/footer.php';
$footer = ob_get_clean();


// Output the page
get_header();
echo $header;
echo $pageOutput;
echo $footer;
get_footer();