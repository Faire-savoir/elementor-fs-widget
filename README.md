# Elementor FS Widget (elementor-fs-widget) [![plugin version](https://img.shields.io/badge/version-v2.2.7-color.svg)](https://github.com/Faire-savoir/elementor-fs-widget/releases/latest)

This is a plugin to add custom widgets to [Elementor](https://github.com/pojome/elementor/)

Plugin Structure:
```
assets/
      /js
      /css

widgets/
      /widget_name.php

index.php
elementor-fs-widget.php
plugin.php
```

* `assets` directory - holds plugin JavaScript and CSS assets
  * `/js` directory - Holds plugin Javascript Files
  * `/css` directory - Holds plugin CSS Files
* `widgets` directory - Holds Plugin widgets
  * `/widget_name.php` - Widget class with declaration of controls etc
* `index.php`	- Prevent direct access to directories
* `elementor-fs-widget.php`	- Main plugin file, used as a loader if plugin minimum requirements are met.
* `plugin.php` - The actual Plugin file/Class.

For more documentation please see [Elementor Developers Resource](https://developers.elementor.com/creating-an-extension-for-elementor/).


## Widgets Documentation & Filters

You can find all the documentation on [Helper website / FS Custom Widget](helper.faire-savoir.com/wordpress/elementor-fs-custom-widget).

## Changelog

### [2.2.7] - (25/06/2021)

* Fix - Add conditions to prevent empty foreach warning.
* Fix - Revision of all widgets.

### [2.2.6] - (22/06/2021)

* Fix - Add default value for media in fs_bouton (pdf).

### [2.2.5] - (14/06/2021)

* Fix - Revision of FS_Playlist code.

### [2.2.4] - (19/04/2021)

* Dev - Standard Wordpress Coding.

### [2.2.3] - (05/02/2021)

* Fix - FS_Sommaire : add the possibility to choose the template with a select 'type' (thanks to <code>elementor/element/after_section_start</code> see "General Action/Filter" in documentation).
* Add - Updated the documentation to add explanations on the available actions and filters.
* Fix - Some array declarations in files.
* Dev - Exportation of documentation in [Helper](helper.faire-savoir.com/wordpress/elementor-fs-custom-widget).

### [2.2.2] - (04/12/2020)

* Fix - FS_Sommaire : add condition to prevent error when get_page_sommaire return empty array.

### [2.2.1] - (09/11/2020)

* Fix - FS_Promotion_Article widget.

### [2.2.0] - (06/11/2020)

* Add - New FS_Relation widget.
* Add - New FS_Relation_Multi widget.

### [2.1.3] - (02/11/2020)

* Dev - Try to fix update problem with name folder.

### [2.1.2] - (28/10/2020)

* Fix - FS_Leaflet_Map : test if FWP function exists.
* Fix - FS_Leaflet_Map_TIS : test if FWP function exists.

### [2.1.1] - (19/10/2020)

* Add - FS Chiffres Clés : add CSS file to init widget.
* Add - FS Bouton : add CSS file to init widget.

### [2.1.0] - (16/10/2020)

* Add - ALL : add many filters see plugins details.
* Add - FS Playlist : add filter<code>'fs_playlist_allowed_styles'</code>.

### [2.0.0] - (14/10/2020)

* Add - ALL : add filter <code>'elementor-fs-widget_hide-custom-widget'</code>.
* Add - Banner and icon to plugin.
* Add - Add files to Github to allow auto-updates.
* Add - README.md file.

### [1.0.0] - (10/10/2019)

* Dev - First version of the plugin.
* Add - readme.txt file.
