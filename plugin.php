<?php
namespace ElementorFSWidget;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {

	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/fs-citation.php' );
		//require_once( __DIR__ . '/widgets/fs-leaflet-map.php' );
		//require_once( __DIR__ . '/widgets/inline-editing.php' );
		require_once( __DIR__ . '/widgets/fs-playlist.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\FS_Citation() );
		//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\FS_Leaflet_Map() );
		//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Inline_Editing() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\FS_Playlist() );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		// TEST
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

		// Hide widget in panel
		add_filter('elementor/editor/localize_settings', function($settings){
/*
			$elementor_widget_blacklist = array(
				'common'
				//,'image'
				//,'text-editor'
				,'video'
				//,'button'
				,'divider'
				//,'spacer'
				,'image-box'
				,'google_maps'
				,'icon'
				,'icon-box'
				,'image-gallery'
				,'image-carousel'
				,'icon-list'
				,'counter'
				,'progress'
				,'testimonial'
				,'tabs'
				,'accordion'
				,'toggle'
				,'social-icons'
				,'alert'
				,'audio'
				,'shortcode'
				,'star-rating'
				,'reviews'
				,'html'
				,'menu-anchor'
				,'sidebar'

				// general ------------- //
				,'read-more'

				// pro ----------------- //
				,'posts'
				,'portfolio'
				,'slides'
				,'form'
				,'login'
				,'media-carousel'
				,'testimonial-carousel'
				,'nav-menu'
				,'pricing'
				,'facebook-comment'
				,'nav-menu'
				,'animated-headline'
				,'price-list'
				,'price-table'
				,'facebook-button'
				,'facebook-comments'
				,'facebook-embed'
				,'facebook-page'
				,'add-to-cart'
				,'categories'
				,'elements'
				,'products'
				,'flip-box'
				,'carousel'
				,'countdown'
				,'share-buttons'
				,'author-box'
				,'breadcrumbs'
				,'search-form'
				,'post-navigation'
				,'post-comments'
				,'theme-elements'
				,'blockquote'
				,'template'
				,'wp-widget-audio'
				,'woocommerce'
				,'social'
				,'library'
				,'call-to-action'

				// wp widgets ----------------- //
				,'wp-widget-pages'
				,'wp-widget-archives'
				,'wp-widget-media_audio'
				,'wp-widget-media_image'
				,'wp-widget-media_gallery'
				,'wp-widget-media_video'
				,'wp-widget-meta'
				,'wp-widget-search'
				,'wp-widget-text'
				,'wp-widget-categories'
				,'wp-widget-recent-posts'
				,'wp-widget-recent-comments'
				,'wp-widget-rss'
				,'wp-widget-tag_cloud'
				,'wp-widget-nav_menu'
				,'wp-widget-custom_html'
				,'wp-widget-polylang'
				,'wp-widget-calendar'
				,'wp-widget-elementor-library'

				// other ------------------- //
			);

			foreach($elementor_widget_blacklist as $widget){
				$settings['widgets'][$widget]['show_in_panel'] = false;
			}
			$settings['category']['basic']['show_in_panel'] = false;
			$settings['category']['site']['show_in_panel'] = false;
*/
			return $settings;
		});
	}

}

// Instantiate Plugin Class
Plugin::instance();
