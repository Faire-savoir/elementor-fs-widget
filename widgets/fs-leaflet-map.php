<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Leaflet_Map extends Widget_Base {

	public function __construct($data = [], $args = null) {

		parent::__construct($data, $args);
		
		$leaflet_version = ( defined( 'LEAFLET_VERSION' ) ) ? '@'.LEAFLET_VERSION : '' ;
		// CSS
			wp_register_style( 'leafletcss', '//unpkg.com/leaflet'.$leaflet_version.'/dist/leaflet.css' );
			wp_register_style( 'fs-widget-leaflet-map-css', ELEMENTOR_FS_WIDGET_URL.'/assets/css/fs-widget-leaflet-map.css' );
			wp_register_style( 'leaflet-gesture-handling-css', '//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css' );
		// JS
			wp_register_script( 'leafletjs', '//unpkg.com/leaflet'.$leaflet_version.'/dist/leaflet.js' );
			wp_register_script( 'leaflet-gesture-handling-js', '//unpkg.com/leaflet-gesture-handling' );
			wp_register_script( 'gmaps', $this->get_maps_url() );
			if ( defined( 'FACETWP_LEAFLET_MAP_URL' ) ){
				wp_register_script( 'googlemap', FACETWP_LEAFLET_MAP_URL.'/assets/js/leaflet-google-correct-v1.js' );
			}
			wp_register_script( 'fs-widget-leaflet-map', ELEMENTOR_FS_WIDGET_URL.'/assets/js/fs-widget-leaflet-map.js', [ 'jquery','elementor-frontend' ], false, true );
	}

	function get_maps_url() {
		// hard-coded
		$api_key = defined( 'GMAPS_API_KEY' ) ? GMAPS_API_KEY : '';
		// admin ui
		$tmp_key = ( function_exists( 'FWP' ) ) ? FWP()->helper->get_setting( 'gmaps_api_key' ) : '';
		$api_key = empty( $tmp_key ) ? $api_key : $tmp_key;
		return '//maps.googleapis.com/maps/api/js?libraries=places&key=' . $api_key;
	}

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'fs-widget-leaflet-map';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'FS Leaflet Map', 'fs-widget-leaflet-map' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-hotspot';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 */
	public function get_categories() {
		return [ 'fs-elements' ];
	}

		/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 */
	public function get_keywords() {
		return [ 
			'leaflet', 
			'map', 
			'location', 
		];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 */
	public function get_script_depends() {
		return [ 
			'fs-widget-leaflet-map', 
			'leafletjs', 
			'leaflet-gesture-handling-js', 
			'gmaps', 
			'googlemap',
		];
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 */
	public function get_style_depends() {
		return [ 
			'fs-widget-leaflet-map-css', 
			'leafletcss', 
			'leaflet-gesture-handling-css',
		];
	}

	public function get_map_design( $slug = null ) {
		$designs = [
			'osm'              => __( 'OpenStreetMap', 'fs-widget-leaflet-map' ),
			'mapbox-street'    => __( 'Mapbox Street', 'fs-widget-leaflet-map' ),
			'mapbox-satellite' => __( 'Mapbox Satellite', 'fs-widget-leaflet-map' ),
			'google-roadmap'   => __( 'Google Roadmap*', 'fs-widget-leaflet-map' ),
			'google-satellite' => __( 'Google Satellite*', 'fs-widget-leaflet-map' ),
			'google-terrain'   => __( 'Google Terrain*', 'fs-widget-leaflet-map' ),
			'google-hybrid'    => __( 'Google Hybrid*', 'fs-widget-leaflet-map' ),
		];

		return isset( $designs[ $slug ] ) ? $designs[ $slug ] : $designs;
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {
		// TAB CONTENU
		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'Map', 'fs-widget-leaflet-map' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'map_design',
			[
				'label'   => __( 'Map design', 'fs-widget-leaflet-map' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_map_design(),
				'default' => 'osm',
			]
		);

		$this->add_control(
			'map_latitude',
			[
				'label'       => __( 'Latitude', 'fs-widget-leaflet-map' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Latitude', 'fs-widget-leaflet-map' ),
				'default'     => '50.6333',
			]
		);
		$this->add_control(
			'map_longitude',
			[
				'label'       => __( 'Longitude', 'fs-widget-leaflet-map' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Longitude', 'fs-widget-leaflet-map' ),
				'default'     => '3.0619',
			]
		);

		$this->add_control(
			'zoom',
			[
				'label'     => __( 'Zoom', 'fs-widget-leaflet-map' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
					'unit' => '',
				],
				'separator' => 'before',
				'range'     => [
					'' => [
						'min'  => 1,
						'max'  => 20,
						'step' => 1,
					],
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'     => __( 'Height', 'fs-widget-leaflet-map' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
				],
				'default'   => [
					'size' => 360,
				],
				'selectors' => [
					'{{WRAPPER}} .fs-widget-leaflet-map' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'fs-widget-leaflet-map' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text',
			[
				'label' => __( 'Text', 'fs-widget-leaflet-map' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'section_text_display',
			[
				'label'        => __( 'Display the text', 'fs-widget-leaflet-map' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'fs-widget-leaflet-map' ),
				'label_off'    => __( 'No', 'fs-widget-leaflet-map' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'section_text_reverse',
			[
				'label'        => __( 'Reverse Text/Map', 'fs-widget-leaflet-map' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'fs-widget-leaflet-map' ),
				'label_off'    => __( 'No', 'fs-widget-leaflet-map' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'section_text_legend',
			[
				'label'        => __( 'Show legend of markers', 'fs-widget-leaflet-map' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'fs-widget-leaflet-map' ),
				'label_off'    => __( 'No', 'fs-widget-leaflet-map' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => __( 'Title', 'fs-widget-leaflet-map' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'content_text',
			[
				'label' => __( 'Content', 'fs-widget-leaflet-map' ),
				'type'  => Controls_Manager::WYSIWYG,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_markers',
			[
				'label' => __( 'Markers', 'fs-widget-leaflet-map' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'fit_bounds',
			[
				'label'        => __( 'Fit Bounds', 'fs-widget-leaflet-map' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'fs-widget-leaflet-map' ),
				'label_off'    => __( 'No', 'fs-widget-leaflet-map' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'marker_numbering',
			[
				'label'        => __( 'Marker numbering', 'fs-widget-leaflet-map' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'fs-widget-leaflet-map' ),
				'label_off'    => __( 'No', 'fs-widget-leaflet-map' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'fs-widget-leaflet-map' ),
				'type'  => Controls_Manager::HIDDEN,
			]
		);

		$repeater->add_control(
			'latitude',
			[
				'label' => __( 'Latitude', 'fs-widget-leaflet-map' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'longitude',
			[
				'label' => __( 'Longitude', 'fs-widget-leaflet-map' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'popup',
			[
				'label' => __( 'Popup', 'fs-widget-leaflet-map' ),
				'type'  => Controls_Manager::WYSIWYG,
			]
		);

		$options_markers_styles = array_merge( ['' => 'Aucun'],apply_filters( 'fs_leaflet_map_markers_styles', [] ));
		$repeater->add_control(
			'marker',
			[
				'label'   => __( 'Marker style', 'fs-widget-leaflet-map' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $options_markers_styles,
			]
		);

		$this->add_control(
			'points_list',
			[
				'label'       => __( 'List', 'fs-widget-leaflet-map' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{title}}}',
				'default'     => [
					[],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$leaflet_settings = [
			'id'                    => 'leaflet-map-'.mt_rand(),
			'data-map-design'       => $settings['map_design'],
			'data-map-lat'          => $settings['map_latitude'],
			'data-map-lon'          => $settings['map_longitude'],
			'data-zoom'             => $settings['zoom']['size'],
			'data-fit-bounds'       => $settings['fit_bounds'],
			'data-marker-numbering' => $settings['marker_numbering'],

		];
		$this->add_render_attribute( 'leaflet_map_settings', $leaflet_settings );
		
		if( $settings['section_text_display'] == 'yes' ):
			// START ROW 
			$class_to_add = ($settings['section_text_reverse'] == 'yes') ? ' flex-row-reverse' : '' ; // reverse mode or not
			?>
			<div class="row align-self-stretch<?php echo $class_to_add; ?>">
			<?php
			$col_md = 'col-md-7';
		else:
			$col_md = 'col-md-12';
		endif;?>
		<div class="fs-widget-leaflet-map col-12 <?php echo $col_md; ?>" 
				<?php echo $this->get_render_attribute_string( 'leaflet_map_settings' ); ?>>
			<?php
				$points_list = $settings['points_list'];
				foreach ( $points_list as $key => $point ):
					$point_settings = [
						'data-id'     => $key+1,
						'data-lat'    => $point['latitude'],
						'data-lon'    => $point['longitude'],
						'data-marker' => $point['marker'],
					];
					$this->add_render_attribute( 'point-'.$point['_id'], $point_settings );
					?>
					<div style="display:none" class="point" <?php echo $this->get_render_attribute_string('point-'.$point['_id']); ?>>
						<?php 
							echo $point['popup']; 
						?>
					</div>
					<?php
				endforeach; 
			?>
		</div>
		<?php if( $settings['section_text_display'] == 'yes' ): ?>
			<div class="col-12 col-md-5">
				<h2 class="fs-widget-leaflet-map__title"><?php echo $settings['title_text']; ?></h2>
				<div class="fs-widget-leaflet-map__content"><?php echo $settings['content_text']; ?></div>
					<?php
					if( $settings['section_text_legend'] == 'yes' ):
						$points_list = $settings['points_list'];
						if( !empty($points_list) ):
							?>
							<ul class="fs-widget-leaflet-map__list<?php echo ($settings['marker_numbering'])?' markers_numbering':''; ?>">
								<?php
									foreach( $points_list as $key => $point ):
										echo '<li>'.$point['popup'].'</li>';
									endforeach;
								?>
							</ul>
							<?php
						endif;
					endif; 
					?>
			</div>
			<?php // END ROW ?>
			</div> 
		<?php endif;
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {}
}
