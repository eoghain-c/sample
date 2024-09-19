# Component: Splide Wrapper
PHP component for outputting and initializing a [Splide.js](https://splidejs.com/) slider containing slide data pre-built and passed to the component.

## Intended Functionality
* Splide Wrapper outputs markup for Splide [by data attribute](https://splidejs.com/guides/options/#by-data-attribute).
* Splide Wrapper initializes a default splide instance using the data attributes and slide markup passed to the component.
* May pass a `$custom_js` flag to the component if the intended functionality is more complex and the layout handles initializing the splide instead.

## Installation
In the case your project does not already have Splide added through base theme, you may need to follow these installation steps:
* Add your preferred version of [Splide.js](https://splidejs.com/) to `build/package.json` Example: 

```json
{
  "devDependencies": {
    "@splidejs/splide": "^3.6.12"
  }
}
```

* Run `npm install`
* Ensure splide.js is added to the `build/Grunt.js` **copy** task:

```javascript
grunt.initConfig({
  copy: {
    templates: {...},
    other: {
      files: [
        // splide.js
        {'../http/htdocs/content/themes/base/assets/js/splide.js': 'node_modules/@splidejs/splide/dist/js/splide.js'}
      ]
    }
  }
});

```
* Ensure `build/common/sass/splide.scss` or equivalent is present and has importing the `splide-core.min` file:
```scss
@import '../../node_modules/@splidejs/splide/dist/css/splide-core.min';
```

* Run `grunt build`
* Enqueue and register `splide.css` and `splide.js` in `base/inc/scripts.php`
```php
function base_scripts() 
{
    ...
    // splide.js
    wp_enqueue_style('splide-css', get_template_directory_uri() . '/assets/css/splide.css', ['common-css']);
    wp_register_script('splide-js', get_template_directory_uri() . '/assets/js/splide.js');
    ...
}

add_action('wp_enqueue_scripts', 'base_scripts', 100);
```
* Add `splide-wrapper.js` and `splide-wrapper.scss` files to the `build/templates/components/` folder
* Add `splide-wrapper.php` file to the `base/templates/components/` folder
* Update arrow icon name in `splide-wrapper.php` to the name of the icon you'd like to use for the splide controls `<?php display_icon('arrow'); ?>`

## Usage
Add `<?php compileTemplate('splide-wrapper', $slider_data); ?>` where you want to output the splide wrapper, passing it the required slide data:
```php
compileTemplate('splide-wrapper', array(
  'slider_content'  => [],   // required
  'breakpoints'     => [],   // optional
  'default'         => '',   // optional
  'container_class' => '',   // optional
  'arrow_class'     => '',   // optional
  'arrow'           => '',   // optional
  'custom_js'       => false // optional
));
```

### Examples
#### Scenario #1 - Basic Picture Gallery:
```php
$slider_content = [];

foreach ($gallery_images as $image) {
  
  $picture_data = [
    'sources' => [
      '1920'  => $image['sizes']['2560x1440'],
      '1280'  => $image['sizes']['1920x1080'],
      '768'   => $image['sizes']['1280x1024'],
      '0'     => $image['sizes']['768x720'],
    ],
    'fallback' => $image['url'],
    'alt_text' => $image['alt'],
    'class' => 'basic-picture',
  ];

  // Collect and push the markup for each picture to the $slider_content array
  // Note compileTemplate has 'false' set for the $print param in this case since the splide-wrapper will be outputting this markup later.
  $slider_content[] = compileTemplate('picture', $picture_data, false, false);
}

// Output the Splide Wrapper Component
compileTemplate('splide-wrapper', array(
  'slider_content' => $slider_content
));
```
&nbsp;
#### Scenario #2 - Card Slider with Peek:
```php
$slider_data = [];
$slider_data['slider_content'] = [];

foreach ($cards as $card) {
  // Collect and push the markup for each card to the $slider_data['slider_content'] array
  $slider_data['slider_content'][] = compileTemplate('card', $card, false, false);
}

// Adding optional slider data
$slide_count = count($slider_data['slider_content']);
$slider_data['arrow_class'] = 'splide__arrow--card-slider';
$slider_data['default'] = '{"type":"slide", "perPage":3, "perMove":1, "gap":"16px", "padding":{"left":"0","right":"0"}, "drag":'.($slide_count > 3 ? 'true' : 'false').' }';
$slider_data['breakpoints'] = array(
  '1919 => {"perPage":2, "padding":{"left":"0","right":"53px"}, "drag":true}',
  '1279 => {"perPage":1, "padding":{"left":"0","right":"157px"}, "drag":true}',
  '767  => {"perPage":1, "padding":{"left":"0","right":"0"}, "drag":true}'
);

// Output Splide Wrapper Component
compileTemplate('splide-wrapper', $slider_data);
```
&nbsp;
#### Scenario #3 - Gallery Cards returned through AJAX (Custom JS):
```php
function galleryListingScripts() {
  // Note the layout adds splide-css and splide-js as enqueue dependencies since it uses $custom_js
  wp_enqueue_style('gallery-listing-css', get_template_directory_uri() . '/assets/css/gallery-listing.css', ['common-css', 'splide-css']);
  wp_enqueue_script('gallery-listing-js', get_template_directory_uri() . '/assets/js/gallery-listing.js', ['verb-common-js', 'splide-js']);
}
add_action('wp_enqueue_scripts', 'galleryListingScripts');
	
$slider_content = [];

foreach ($gallery_cards as $card) {
  // Collect and push the markup for each gallery card to the $slider_content array
  $slider_content[] = compileTemplate('gallery-card', $card, false, false);
}

// Output Splide Wrapper Component
compileTemplate('splide-wrapper', array(
  'slider_content' => $slider_content,
  'custom_js'      => true // Init splide in gallery-listing.js instead
));
```

Initialize the Splide in the gallery-listing.js:
```javascript
$(function() {
  galleryCard();	  
  galleryCardRequest();
});

function galleryCard() {
  const container = $('.js-gallery-listing');
  const galleryCards = container.find('.js-gallery-card');

  galleryCards.each(function() {
    const card = $(this);
    const splideWrapper = card.find('.splide');

    // Initialize Splide by Javascript 
    let splide = new Splide(splideWrapper[0], {
      pagination: true,
      perMove: 1,
      perPage: 1,
      type: 'loop',
      updateOnMove: true
    });

    // Move the pagination inside the arrows
    splide.on( 'pagination:mounted', function( data ) {
      const $pagination = splideWrapper.find('.splide__pagination');
      if ($pagination.length) {
        const $first_arrow = splideWrapper.find('.splide__arrow--prev');
        $pagination.detach().insertAfter($first_arrow);
      }
    });

    splide.mount();
  });
}

function galleryCardRequest() {
  const container = $('.js-gallery-listing');

  $.ajax({
    type: 'post',
    url: '/wp/wp-admin/admin-ajax.php',
    data: {
      action: 'gallery_card_listing_call'
    },
    success: function (response) {
      // Append cards in the container
      container.append(response);

      // Re-init the gallery card functionality on the new cards
      galleryCard();
    }
  });
}
```

