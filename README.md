# Elementor FS Widget (elementor-fs-widget) [![plugin version](https://img.shields.io/badge/version-v2.2.3-color.svg)](https://github.com/Faire-savoir/elementor-fs-widget/releases/latest)

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


## General Action/Filter

This snippet allows to hide widget/group in panel

```
add_filter('elementor/editor/localize_settings', 'fs_remove_some_widget');
function fs_remove_some_widget( $settings ){
  // to hide widgets
  $elementor_widget_blacklist = [
    'common',
    'shortcode'
  ];
  foreach ( $elementor_widget_blacklist as $widget ) {
    $settings['widgets'][$widget]['show_in_panel'] = false;
  }

  // to hide categories
  $elementor_category_blacklist = [
    'basic',
    'site'
  ];
  foreach ( $elementor_category_blacklist as $category ) {
    $settings['category'][$category]['show_in_panel'] = false;
  }

  return $settings;
}
```

To hide widget created by this plugin
```
add_filter( 'elementor-fs-widget_hide-custom-widget', 'hide_created_widget');
function hide_created_widget( $all_widgets ){
  unset($all_widget['fs-citation']);
  return $all_widgets;
}
```

Alter widget controls. For more information see https://code.elementor.com/php-hooks/.
Exemple :
```
add_action( 'elementor/element/after_section_start', function( $element, $section_id, $args ) {
  if ( 'fs-widget-sommaire' === $element->get_name() ) {
    $element->add_control(
      'type',
      [
        'label' => __( 'Type', 'elementor-fs-page' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          'widget-sommaire' => 'Style sommaire',
          'widget-mosaique-sommaire' => 'Style Mosaïque',
        ],
        'default' => 'widget-sommaire',
      ]
    );
  }
}, 10, 3 );
```


## Widgets

### FS Bouton (fs-bouton)

**Filters available :**

Style des boutons

```
add_filter('fs_widget_fs_bouton_filter_link_btn_styles', 'filter_link_btn_styles' );
function filter_link_btn_styles(){
  return [
    'default' => 'Classique (défaut)',
    'fleche' => 'Flèche'
  ];
}
```

Media Modes
```
add_filter('fs_widget_fs_bouton_filter_media_modes', 'filter_media_modes');
function filter_media_modes(){
  return [
    'default' => 'Lien simple (défaut)',
    'blank' => 'Nouvel onglet',
    'downloadable' => 'Lien téléchargeable',
  ];
}
```

Authorized Media
This filter allows you to modify the selectable media types.
```
add_filter('fs_widget_fs_bouton_filter_media_authorized_types','change_fs_bouton_media_authorized_types');
function change_fs_bouton_media_authorized_types( $authorized_types ){
  /*
      POSSIBLE VALUES :
      -----------------
      $authorized_types = '';           // ALL FILES (default)
      $authorized_types = 'image';      // IMAGES
      $authorized_types = 'video';      // VIDEOS
      $authorized_types = 'application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-word.document.macroEnabled.12,application/vnd.ms-word.template.macroEnabled.12,application/vnd.oasis.opendocument.text,application/vnd.apple.pages,application/pdf,application/vnd.ms-xpsdocument,application/oxps,application/rtf,application/wordperfect,application/octet-stream';      // DOCUMENTS
  */
  return $authorized_types;
}
```

### FS Chiffres Clés (fs-chiffres-cles)

This widget allows to create "FS Chiffres Clés". A simple repeater containing numbers to display.

### FS Citation (fs-citation)

This widget allows to create "FS Citation". A simple textarea to the quotation and a text field to mention the author.
No more, no less...

### FS Leaflet Map / FS Leaflet Map (TIS) (fs-leaflet-map / fs-leaflet-map-tis)

This widget allows you to add "Leaflet Map" element with many markers thanks to their lat/lon coordinates.
You can choose the style of the background map or markers style and many other things.

**Filters available :**

A filter is applicable to allow you to add marker styles.
It just adds a class to the marker to modify it via CSS.
```
add_filter( 'fs_leaflet_map_markers_styles', 'set_options_markers_styles');
function set_options_markers_styles($options){
  return array(
    'class_name'=>'Nom du style',
    'marker_class_1'=>'Marker style 1',
    'blue'=>'Blue',
  );
}
```
If the point choose the style "Marker style 1", marker will therefore have the "marker-marker_class_1" class in frontend.

### FS Mosaique Link (fs-mosaique-link)

A simple repeater containing many fields.

**Filters available :**

Set the template
```
add_filter( 'fs_mosaique_link-path_to_template', '_set_template' );
function _set_template(){
  return 'template-parts/widget/widget-mosaique-link';
}
```

### FS Playlist (fs-playlist)

This widget allows to add a list of posts (thanks to the syndicobjectid field) in a playlist like a carousel, a coverflow or a simple list.
You can choose the style of the list. You can add a map thanks to coordinates of the posts.

**Filters available :**

The "fs_playlist_allowed_styles" filter can remove many style from site by adding a snippet.
```
add_filter( 'fs_playlist_allowed_styles', 'fs_playlist_remove_styles_from_select' );
function fs_playlist_remove_styles_from_select( $all_styles ){
  unset( $all_styles['carousel'] );
  return $all_styles;
}
```

### FS Promotion Article (fs-promotion-article)

A simple repeater containing many fields.

```
add_filter( 'fs_promotion_article-path_to_template', 'set_template_promotion_article' );
function set_template_promotion_article(){
  return 'template-parts/widget/widget-promotion-article';
}
```

### FS Relation (fs-relation)

A widget to show selected post in a page.

**Filters available :**

Define args for the query of the allowed posts
```
add_filter('fs_relation-query_args',function(){
  return [
    'post_type' => 'musee',
    'posts_per_page' => -1,
  ];
});
```

Define the path of the render template
```
add_filters( 'fs_relation-path_to_template', function(){
  return 'template-parts/widget/widget-relation';
});
```

### FS Sommaire (fs-sommaire)

A widget to show childs of a page.

**Filters available :**

Classes pour le wrapper
```
add_filter( 'fs_widget_fs_sommaire_filter_wrapper_classes', 'set_wrapper_classes' );
function set_wrapper_classes(){
  return [
    'container',
    'row',
    'listing',
    'listing-sommaire'
  ];
}
```

Set the number of highlighted elements
```
add_filter( 'fs_widget_fs_sommaire_filter_nb_highlighted_elements', 'nb_highlighted_elements' );
function nb_highlighted_elements(){
  return 2;
}
```

Define the path of the rendered template
```
add_filter( 'fs_mosaique_link-path_to_template', 'define_path_to_fs_sommaire', 10, 2 );
function define_path_to_fs_sommaire($path, $settings){
  // $settings contains the configurations of the widget instance
  $path = 'template-parts/widget/widget-sommaire';
  return $path;
}
```

## Changelog

### [2.2.3] - (05/02/2021)

* Fix - FS_Sommaire : add the possibility to choose the template with a select 'type' (thanks to <code>elementor/element/after_section_start</code> see "General Action/Filter" in documentation).
* Add - Updated the documentation to add explanations on the available actions and filters.

### [2.2.2] - (04/12/2020)

* Fix - FS_Sommaire : add condition to prevent error when get_page_sommaire return empty array.

### [2.2.1] - (09/11/2020)

* Fix - FS_Promotion_Article widget

### [2.2.0] - (06/11/2020)

* Add - New FS_Relation widget
* Add - New FS_Relation_Multi widget

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
