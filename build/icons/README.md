## Icons
### Inline svg
To add a new icon: Add the SVG file to ```build/icons/``` then run grunt:watch, or grunt:build, etc.  The svg will be cleaned up, have classes added to it and be saved in ```http/htdocs/content/themes/base/assets/img/icons```.

To use an icon, two functions are present in helpers.php: ```get_icon($name, $label = '')``` and ```display_icon($name, $label = '')```

get_icon() returns the value and display_icon() echos it.

If no name is passed, or, the name is for a file that doesn’t exist the function will throw a warning.

**Styling:**

Each svg has two classes added to it: ```.v-icon__icon``` and ```.v-icon__icon--name```.  These generally shouldn't need to be used.

The helper functions also add two classes: ```.v-icon__svg``` and ```.v-icon__svg--name```.  These are the recommended classes to use.

For example:  

```css
.v-icon__svg--search {
    color: red;
}
```

### :before :after
A before or after class must be added to ```build/sass/04-components/_icons.scss``` for each icon.  Example provided in _icons.scss.

A filter property is required to color the svg.  
Open ```build\icons\hex-to-css-filter.html``` in a browser to generate the filter values.  The values should be added to ```build\sass\01-settings\_variables.scss``` for reuse.  An example is provided in _variables.scss

### Examples
```html
<p class="v-icon">With function <?php display_icon('search'); ?></p>
<p class="v-icon v-icon--before v-icon--b-search">Before Button</p>
<p class="v-icon v-icon--after v-icon--a-search">After Button</p>
```