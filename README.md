[![Github Last Commit](https://img.shields.io/github/last-commit/colinduwe/cdbootstrap)](https://github.com/colinduwe/cdbootstrap/commits/master) 
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0)

# CDBootstrap WordPress Theme Framework

Original Project: [https://understrap.com](https://understrap.com)

## About

I like Understrap but I have my own opinions. I've forked that great project here and swapped out the old build process for Laraval-Mix and just refined how packages are included. This is intended as a start theme rather than a parent theme.

## License
- CDBootstrap Wordpress Theme, Copyright 2020 Colin Duwe
- UnderStrap WordPress Theme, Copyright 2013-2018 Holger Koenemann
- CDBootstrap is distributed under the terms of the GNU GPL version 2

http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

## Changelog
See [changelog](CHANGELOG.md)


## Basic Features

- Combines Underscore’s PHP/JS files and Bootstrap’s HTML/CSS/JS.
- Comes with Bootstrap (v4) Sass source files and additional .scss files. Nicely sorted and ready to add your own variables and customize the Bootstrap variables.
- Uses a single minified CSS file for all the basic stuff.
- [Font Awesome Pro](http://fortawesome.github.io/Font-Awesome/) integration (v4.7.0) (You will need a pro license to run npm install)
- Jetpack ready.
- WooCommerce support.
- Contact Form 7 support.
- Translation ready.

## Starter Theme + HTML Framework = WordPress Theme Framework

The _s theme is a good starting point to develop a WordPress theme. But it is “just” a raw starter theme. That means it outputs all the WordPress stuff correctly but without any layout or design.
Why not add a well known and supported layout framework to have a solid, clean and responsive foundation? That’s where Bootstrap comes in.

## Confused by All the CSS and Sass Files?

Some basics about the Sass and CSS files that come with CDBootstrap:
- The theme itself uses the `/style.css`file only to identify the theme inside of WordPress. The file is not loaded by the theme and does not include any styles.
- The `/dist/css/app.css` file provides all styles. It is composed of five different SCSS sets and one variable file at `/src/scss/app.scss`:

 ```@import "variables";  // 1. Add your variables into this file. Also add variables to overwrite Bootstrap or CDBootstrap variables here
 @import "~bootstrap/scss/bootstrap";  // 2. All the Bootstrap stuff - Don´t edit this!
 @import "understrap/understrap"; // 3. Some basic WordPress stylings and needed styles to combine Boostrap and Underscores
 @import "~@fortawesome/fontawesome-pro/scss/fontawesome"; // 4. Font Awesome Pro Icon styles
 // Any additional imported files //
 @import "components/galleries";  // 5. Add additional style files here
 ```

- Don’t edit the number 2-4 files/filesets listed above or you won’t be able to update CDBootstrap without overwriting your own work!

## Installation
- In your terminal, navigate to your wp-content/themes directory and clone this project

## Developing With npm, Gulp and SASS and [Browser Sync][1]

### Installing Dependencies
- Make sure you have installed Node.js and Browser-Sync (optional) on your computer globally
- Then open your terminal and browse to the location of your CDBootstrap copy
- Run: `$ npm install`

- Change the browser-sync options to reflect your environment in the file `/webpack.mix.js` in the beginning of the file:
```
	.browserSync({
		proxy: 'https://test.local',
		files: [
            'src/scss/*',
            'src/js/*',
            '*.php',
            '*/*.php',
        ]
	});
```

### Running
To work with and compile your Sass files on the fly start:
- run: `$ npm run watch`

## How to Use the Built-In Widget Slider

The front-page slider is widget driven. Simply add more than one widget to widget position “Hero”.
- Click on Appearance → Widgets.
- Add two, or more, widgets of any kind to widget area “Hero”.
- That’s it.

## RTL styles?
Add a new file to the themes root folder called rtl.css. Add all alignments to this file according to this description:
https://codex.wordpress.org/Right_to_Left_Language_Support

## Page Templates
CDBootstrap includes several different page template files: (1) blank template, (2) empty template, and (3) full width template.

### Blank Template

The `blank.php` template is useful when working with various page builders and can be used as a starting blank canvas.

### Empty Template

The `empty.php` template displays a header and a footer only. A good starting point for landing pages.

### Full Width Template

The `fullwidthpage.php` template has full width layout without a sidebar.

## Footnotes

[1] Visit [http://browsersync.io](http://browsersync.io) for more information on Browser Sync

Licenses & Credits
=
- Font Awesome: http://fontawesome.io/license (Font: SIL OFL 1.1, CSS: MIT License)
- Bootstrap: http://getbootstrap.com | https://github.com/twbs/bootstrap/blob/master/LICENSE (Code licensed under MIT documentation under CC BY 3.0.)
and of course
- jQuery: https://jquery.org | (Code licensed under MIT)
- WP Bootstrap Navwalker by Edward McIntyre: https://github.com/twittem/wp-bootstrap-navwalker | GNU GPL
- Bootstrap Gallery Script based on Roots Sage Gallery: https://github.com/roots/sage/blob/5b9786b8ceecfe717db55666efe5bcf0c9e1801c/lib/gallery.php
