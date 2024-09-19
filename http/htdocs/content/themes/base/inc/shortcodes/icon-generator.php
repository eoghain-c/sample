<?php
// Add TinyMCE button and plugin filters
function tinymce_icon_generator() {
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		add_filter( 'mce_buttons', 'register_tinymce_icon_generator' );
		add_filter( 'mce_external_plugins', 'tinymce_icon_generator_script' );
	}
}
add_action( 'admin_init', 'tinymce_icon_generator' );

// Add TinyMCE buttons onto the button array
function register_tinymce_icon_generator( $buttons ) {
	array_push( $buttons, 'icon_button' );
	return $buttons;
}

// Add TinyMCE button script to the plugins array
function tinymce_icon_generator_script( $plugin_array ) {
	$plugin_array['icon_button_script'] = get_stylesheet_directory_uri() . '/inc/shortcodes/js/icon-generator.js.php';  // Change this to reflect the path/filename to your js file
	return $plugin_array;
}

// Style the button with a dashicon icon instead of an image
function icon_tinymce_button_dashicon() {
	?>
	<style>
	.mce-i-icon_button:before {
		content: '\f502';
		display: inline-block;
		-webkit-font-smoothing: antialiased;
		font: normal 16px/1 'dashicons';
		vertical-align: top;
	}
    .mce-window .mce-window-head {
        color:#fff;
        background:#252525;
    }
	</style>
	<?php
}
add_action( 'admin_head', 'icon_tinymce_button_dashicon' );

// Register Shortcode
add_shortcode('icon', 'generate_icons');

// Customize Display of shortcode
function generate_icons($atts, $content){
	if($atts['colour'] == 'green'){
		$btnClass = 'link link__text--no-line link__text--green';
	}else{
		$btnClass = 'link link__text--no-line';
	}

	if(!empty($atts['style']) && $atts['style'] == 'normal'){
		$styleClass = 'generated-icon--normal';
	}else{
		$styleClass = 'generated-icon--bold';
	}

    if (!empty($atts['icon'])) {
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/content/themes/base/assets/img/icons/'. $atts['icon'] .'.svg')){ ?>
<!--				<div class="generated-icon">-->
<!--					<div class="icon-container">-->
<!--						<img class="generated-icon__svg --><?//= $atts['colour'] ?><!-- lazyload" src="--><?//= $_SERVER['DOCUMENT_ROOT'] . '/content/themes/base/assets/img/icons/'. $atts['icon'] .'.svg'?><!--" alt="--><?//= $atts['alt'] ?><!--">-->
<!--						<div class="generated-icon__content">--><?//= $atts['content'] ?><!--</div>-->
<!--					</div>-->
<!--				</div>-->
<!---->
	        <?php if(!empty($atts['link'])) { ?>
           <?php return '
            <div class="generated-icon '. $styleClass .'">
                <div class="icon-container">
                	<a href="'. $atts['link'] .'" class="generated-icon__link ' . $btnClass .'" title="' . $atts['content'] . '">
                    <img class="generated-icon__svg ' . $atts['colour'] .' lazyload" src="'. get_stylesheet_directory_uri() .'/assets/img/icons/' . $atts['icon'] . '.svg" alt="' . $atts['alt'] . '">
                    <div class="generated-icon__content ' . $atts['colour'] .'">' . $atts['content'] . '</div>
                    </a>
                </div>
            </div>';
	          } else {
		        return '
            <div class="generated-icon '. $styleClass .'">
                <div class="icon-container">
	                <div class="generated-icon__link">
	                  <img class="generated-icon__svg ' . $atts['colour'] .' lazyload" src="'. get_stylesheet_directory_uri() .'/assets/img/icons/' . $atts['icon'] . '.svg" alt="' . $atts['alt'] . '">
	                  <div class="generated-icon__content ' . $atts['colour'] .'">' . $atts['content'] . '</div>
	                </div>
                </div>
            </div>';
	        }
       }
    }
}