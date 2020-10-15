# Elementor FS Widget (elementor-fs-widget) [![plugin version](https://img.shields.io/badge/version-v1.0.0-color.svg)](https://github.com/Faire-savoir/elementor-fs-widget/releases/latest)

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


## Changelog

### [Unreleased]

### [1.0.0] - (10/10/2019)

* Dev - First version of the plugin.
* Add - readme.txt file.
