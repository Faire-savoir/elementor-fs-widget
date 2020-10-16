<?php
namespace ElementorFSWidget;

class Plugin {

	/**
	 * Instance
	 */
	private static $_instance = null;
	private static $all_widgets = [
		// 'widget_file_name' => 'Class_Name'
		'fs-bouton' => 'FS_Bouton',
		'fs-chiffres-cles' => 'FS_Chiffres_Cles',
		'fs-citation' => 'FS_Citation',
		'fs-leaflet-map' => 'FS_Leaflet_Map',
		'fs-leaflet-map-tis' => 'FS_Leaflet_Map_TIS',
		'fs-mosaique-link' => 'FS_Mosaique_Link',
		'fs-playlist' => 'FS_Playlist',
		'fs-promotion-article' => 'FS_Promotion_Article',
		'fs-sommaire' => 'FS_Sommaire',
	];

	/**
	 * Ensures only one instance of the class is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Load required plugin core files.
	 */
	public function widget_scripts() {

	}

	/**
	 * Get all widgets from plugin.
	 */
	private function get_all_widgets() {
		$all_widgets = self::$all_widgets;
		return apply_filters( 'elementor-fs-widget_hide-custom-widget', $all_widgets );
	}

	/**
	 * Load widgets files
	 */
	private function include_widgets_files() {
		$all_widgets = $this->get_all_widgets();
		foreach($all_widgets as $file_name => $class_name){
			require_once( ELEMENTOR_FS_WIDGET_PATH."widgets/".$file_name.'.php' );	
		}
	}

	/**
	 * Register new Elementor widgets.
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		$all_widgets = $this->get_all_widgets();
		foreach($all_widgets as $file_name => $class_name){
			if ( isset($class_name) && !empty($class_name) && is_string($class_name) ){
				$the_class = __NAMESPACE__."\\Widgets\\".$class_name;
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $the_class() );
			}
		}
	}

	/**
	 * Register plugin action hooks and filters
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		// Add the "Faire-Savoir" widget group
		add_action( 'elementor/init', function() {
			\Elementor\Plugin::$instance->elements_manager->add_category(
				'fs-elements',
				[
					'title' => __( 'Faire-Savoir', 'fs-domain' ),
					'icon' => 'fa fa-plug', //default icon
				],
				2 // position
			);
		});
	}

}

// Instantiate Plugin Class
Plugin::instance();
