<?php
/**
 * Search Form Component
 *
 * @param $searchTerm string The search query
 * @param $position   string Optional, default: 'body'.  Must be 'header' or 'body'.
 *                           Where on the page the form is loaded
 */


function searchFormScripts(){
	wp_enqueue_style('search-form-css', get_template_directory_uri() . '/assets/css/search-form.css', ['common-css']);
}
add_action('wp_enqueue_scripts', 'searchFormScripts');


$position = (isset($position)) ? $position : 'body';
$modifierClasses = (isset($position) and $position == 'header') ? 'search-form--header' : '';
?>

<form role="search" method="get" class="search-form <?php echo $modifierClasses; ?>" action="<?php echo esc_url(home_url('/search/')); ?>">
	<label for="<?php echo $position; ?>-q" class="sr-only"><?php echo _x('Search for:', 'label'); ?></label>
	<input id="<?php echo $position; ?>-q" type="search" class="search-form__input" autocomplete="off" name="q" placeholder="Search" value="<?php echo $searchTerm; ?>"/>

	<button type="submit" class="search-form__btn">
		<?php display_icon('search'); ?>
		<?php echo _x('Search', 'submit button'); ?>
	</button>
</form>