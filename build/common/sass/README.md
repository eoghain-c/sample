Folder structure is loosely based on [ITCSS](https://www.silverstripe.org/blog/better-css-putting-it-together-with-atomic-itcss-and-bem/).

BEM is used as much as possible within reason.  Nesting is still used but as little as possible.  _components.scss is the best example of BEM and scss usage.

Prefix classes used specifically for javascript with js- (if possible).

## 01-settings
Variables.

## 02-tools
Mixins.

## 03-base
This is supposed to be element only styles.  Ex: forms, h1-h6 etc.  This is being broken in the template file, but it might be best to consider that a reset file.  It's also broken in the case of "h1, .h1" but that makes sense.

## 04-components
This should be where most of the style are.  If you need to include a plugin's styles, put them here.  Everything from here on is classes only (except for things like overriding gravity forms, which is awful).  BEM should be followed as closely as possible here.  I found including the BEM tree in a comment makes it easier to see what the code is doing.

## 05-layouts
These files follow the one acf file, one scss file structure (also header, footer and templates, single-pages, etc).  If the components were done well, these files should be very light and mostly include layout and override styles.