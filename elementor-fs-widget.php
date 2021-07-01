<?php
/**
 * Plugin Name: Elementor FS Widget
 * Description: Elementor addon by Faire Savoir.
 * Plugin URI:  https://elementor.com/
 * Version:     2.2.8
 * Author:      Faire Savoir
 * Author URI:  http://www.faire-savoir.com/
 * Text Domain: elementor-fs-widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Elementor_FS_Widget {

	const VERSION                   = '2.2.8';
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	const MINIMUM_PHP_VERSION       = '7.0';

	public function __construct() {

		$path = plugin_dir_path( __FILE__ );
		define( 'ELEMENTOR_FS_WIDGET_PATH', $path );

		$url = plugins_url( '/', __FILE__ );
		define( 'ELEMENTOR_FS_WIDGET_URL', $url );

		// Check Updates for plugin
		add_action( 'admin_init', [$this, 'check_updates'] );

		// Load translation
		add_action( 'init', [ $this, 'i18n' ] );

		// Init Plugin
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Check plugin updates thanks to github repository link.
	 */
	public function check_updates(){
		$plugins_dir = plugin_dir_path( __DIR__ );
		if ( file_exists($plugins_dir.'plugin-update-checker/plugin-update-checker.php') ) {

			require $plugins_dir.'plugin-update-checker/plugin-update-checker.php';
			$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
				'https://github.com/Faire-savoir/elementor-fs-widget',
				__FILE__,
				'elementor-fs-widget'
			);
			$myUpdateChecker->getVcsApi()->enableReleaseAssets();

			add_filter( 'puc_request_info_result-elementor-fs-widget' ,[ $this,'puc_modify_plugin_render' ]);
			add_filter( 'puc_view_details_link_position-elementor-fs-widget' ,[ $this,'puc_modify_link_position' ]);

		}
	}

	/**
	 * Modifies the appearance of the plugin as in the detail page or during updates.
	 */
	public function puc_modify_plugin_render( $result ){
		$result->banners = [
			'high' =>	'http://faire-savoir.com/sites/default/files/fs-banniere.jpg',
		];
		$result->icons = [
			'2x' => 'http://faire-savoir.com/sites/default/files/fs-icon.jpg',
		];
		return $result;
	}
	/**
	 * Changes the position of the link in the plugin list page.
	 */
  	public function puc_modify_link_position( $position ){
		$position = 'append';
		return $position;
	}

	/**
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 */
	public function i18n() {
		load_plugin_textdomain( 'elementor-fs-widget' );
	}

	/**
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 */
	public function init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
	}

	/**
	 * Warning when the site doesn't have Elementor installed or activated.
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-fs-widget' ),
			'<strong>' . esc_html__( 'Elementor FS Widget', 'elementor-fs-widget' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-fs-widget' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Warning when the site doesn't have a minimum required Elementor version.
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-fs-widget' ),
			'<strong>' . esc_html__( 'Elementor FS Widget', 'elementor-fs-widget' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-fs-widget' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Warning when the site doesn't have a minimum required PHP version.
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-fs-widget' ),
			'<strong>' . esc_html__( 'Elementor FS Widget', 'elementor-fs-widget' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-fs-widget' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate Elementor_FS_Widget.
new Elementor_FS_Widget();
