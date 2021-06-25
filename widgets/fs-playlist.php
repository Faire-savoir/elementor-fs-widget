<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Playlist extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		
		$leaflet_version = ( defined( 'LEAFLET_VERSION' ) ) ? '@'.LEAFLET_VERSION : '' ;
		// CSS
			wp_register_style( 'leafletcss', '//unpkg.com/leaflet'.$leaflet_version.'/dist/leaflet.css' );
			wp_register_style( 'fs-widget-leaflet-map-css', ELEMENTOR_FS_WIDGET_URL.'/assets/css/fs-widget-leaflet-map.css' );
			wp_register_style( 'leaflet-gesture-handling-css', '//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css' );
			
			wp_register_style( 'style-flipster', ELEMENTOR_FS_WIDGET_URL.'/assets/css/jquery.flipster.min.css' );
			wp_register_style( 'fs-widget-playlist-css', ELEMENTOR_FS_WIDGET_URL.'/assets/css/fs-widget-playlist.css' );
			wp_register_style( 'style-owl', ELEMENTOR_FS_WIDGET_URL.'/assets/css/owl.carousel.min.css' );
		// JS
			wp_register_script( 'leafletjs', '//unpkg.com/leaflet'.$leaflet_version.'/dist/leaflet.js' );
			wp_register_script( 'leaflet-gesture-handling-js', '//unpkg.com/leaflet-gesture-handling' );
			if ( defined( 'FACETWP_LEAFLET_MAP_URL' ) ){
				wp_register_script( 'googlemap', FACETWP_LEAFLET_MAP_URL.'/assets/js/leaflet-google-correct-v1.js' );
			}
			wp_register_script( 'script-flipster', ELEMENTOR_FS_WIDGET_URL.'/assets/js/jquery.flipster.min.js' );
			wp_register_script( 'fs-playlist-map', ELEMENTOR_FS_WIDGET_URL.'/assets/js/fs-playlist.js', [ 'jquery','elementor-frontend' ], false, true );
			wp_register_script( 'script-owl', ELEMENTOR_FS_WIDGET_URL.'/assets/js/owl.carousel.js' );
	}

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'fs-widget-playlist';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'FS Playlist', 'fs-widget-playlist' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
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
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 */
	public function get_script_depends() {
		return [ 
			'script-flipster', 
			'script-owl', 
			'fs-playlist-map', 
			'leafletjs', 
			'leaflet-gesture-handling-js',
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
			'style-flipster', 
			'style-owl', 
			'fs-widget-playlist-css', 
			'fs-widget-leaflet-map-css', 
			'leafletcss', 
			'leaflet-gesture-handling-css', 
		];
	 }

	 public function get_playlist_appearance() {
		$designs = [
			'list'         => __( 'Liste', 'fs-widget-playlist' ),
			'list_and_map' => __( 'Liste & Carte', 'fs-widget-playlist' ),
		];

		return $designs;
	 }

	 public function get_playlist_map_style() {
		$designs = [
			'map_top'   => __('Map au-dessus', 'fs-widget-playlist' ),
			'map_right' => __( 'Carte à droite', 'fs-widget-playlist' ),
			'map_top'   => __( 'Carte au-dessus', 'fs-widget-playlist' ),
		];

		return $designs;
	 }

	public function get_playlist_list_style() {
		$designs = [
			'list'                       => __( 'Liste simple', 'fs-widget-playlist' ),
			'carrousel'                  => __( 'Carrousel', 'fs-widget-playlist' ),
			'carrousel_hover'            => __( 'Carrousel Hover', 'fs-widget-playlist' ),
			'coverflow'                  => __( 'Coverflow', 'fs-widget-playlist' ),
			'carrousel_first_img_bigger' => __( 'Carrousel - Première Image Fixe', 'fs-widget-playlist' ),
		];

		$designs = apply_filters( 'fs_playlist_allowed_styles', $designs );

		return $designs;
	 }

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'elementor-fs-playlist' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'oid',
			[
				'label' => __( 'Offre associée', 'fs-playlist-oid' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'oids',
			[
				'label'       => __( 'Liste OIs', 'fs-widget-playlist-liste-ois' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[],
				],
				'title_field' => '{{{oid}}}',
			]
		);

		$this->add_control(
			'apparence',
			[
				'label'   => __( 'Apparence', 'fs-widget-leaflet-map' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_playlist_appearance(),
				'default' => 'list',
			]
		);

		$this->add_control(
			'style_list',
			[
				'label'     => __( 'Style Liste', 'fs-widget-leaflet-map' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_playlist_list_style(),
				'default'   => 'list',
				'condition' => [
					'apparence' => [ 'list', 'list_and_map' ],
				],
			]
		);

		$this->add_control(
			'style_map',
			[
				'label'     => __( 'Style Map', 'fs-widget-leaflet-map' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_playlist_map_style(),
				'default'   => 'map_top',
				'condition' => [
					'apparence' => ['list_and_map'],
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
		echo '<div class="playlist_list">';
			switch( $settings['apparence'] ){
				case 'list':
					echo '<div class="list">';
						$this->render_list( $settings );
					echo '</div>';
					break;
				case 'list_and_map':
					echo '<div class="map_side '.$settings['style_map'].'">';
						$this->render_map( $settings );
					echo '</div>';
					echo '<div class="list">';
						$this->render_list( $settings );
					echo '</div>';
					break;
			}
		echo '</div>';
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {}


	/**
	 * 
	 *   UTILS FUNCTIONS
	 * 
	 */

	public function render_list( $settings ){
		if ( wp_is_mobile() ){
			$this->render_list_carrousel( $settings );
		}
		else {
			switch( $settings['style_list'] ){
				case 'list' :
					$this->render_simple_list( $settings );
					break;
				case 'carrousel' :
					$this->render_list_carrousel( $settings );
					break;
				case 'coverflow' :
					$this->render_list_coverflow( $settings );
					break;
				case 'carrousel_first_img_bigger' :
					$this->render_list_carrousel_first_img_bigger( $settings );
					break;
				case 'carrousel_hover' :
					$this->render_list_carrousel_hover( $settings );
					break;
			}
		}
	}

	public function render_simple_list( $settings ){
		foreach( $settings['oids'] as $oid ){
			$oids[] = $oid['oid'];
		}
		$this->get_offer_list( $oids, 'list' );
	}

	public function render_list_coverflow( $settings ){
		$oids = [];
		$rand = rand( 0, 1000 );
		foreach( $settings['oids'] as $oid ){
			$oids[] = $oid['oid'];
		}
		?>
		<div id="coverflow-list-offres-<?php echo $rand; ?>">
			<div class="coverflow">
				<?php
					$this->get_offer_list( $oids, 'coverflow' );
				?>
			</div>
		</div>
		<?php if( count($oids) > 2 ) : ?>
			<script>
				jQuery(function($){
					$(document).ready(function(){
						var carousel = $("#coverflow-list-offres-<?php echo $rand; ?>").flipster({
							style:         'carousel',
							spacing:       -0.6,
							nav:           false,
							fadeIn:        400,
							buttons:       true,
							loop:          true,
							itemContainer: '.coverflow',
							itemSelector:  '.row_offer',
							start:         'center',
							pauseOnHover:  false,
							autoplay:      false,
							touch:         true,
							scrollwheel:   false,
							keyboard:      true,
						});
					});
				})
			</script>
		<?php endif; ?>
		<?php
	}

	public function get_offer_list( $oids, $mode = 'coverflow' ){

		switch ( $mode ){
			case 'list':
				$nb_item_visible_list = intval( apply_filters( 'fs_playlist-list-nb_items_visible', 4 ) );
				$path_to_template = apply_filters( 'fs_playlist-list-path_to_template','template-parts/block/block' );
				break;
			/*case 'carrousel':
				$path_to_template = apply_filters( 'fs_playlist-carrousel-path_to_template','template-parts/block/block-carrousel' );
				break;
			case 'coverflow':
				$path_to_template = apply_filters( 'fs_playlist-coverflow-path_to_template','template-parts/block/block-coverflow' );
				break;
			case 'carrousel_hover':
				$path_to_template = apply_filters( 'fs_playlist-carrousel_hover-path_to_template','template-parts/block/block-carrousel_hover' );
				break;*/
			default :
				$path_to_template = apply_filters( 'fs_playlist-'.$mode.'-path_to_template','template-parts/block/block-'.$mode, $mode );
				break;
		}

		$args = [
			'post_type'      => 'any',
			'post_status'    => 'publish',
			'meta_key'       => 'syndicobjectid',
			'meta_value'     => $oids,
			'posts_per_page' => -1,
		];

		$query = new \WP_Query($args);

		// Gestion du tri des oids comme l'ordre de la conf Elementor
		// Voir : https://kuttler.eu/code/order-posts-in-a-wp_query-manually/
		global $order_oids;
		$order_oids = $oids;
		usort( $query->posts, [$this,'change_order_by_oids'] );

		if( $query->have_posts() ){
			$key = 0;
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_type = get_post_type( $query->post->ID );
				if( $mode == 'coverflow' ){
					echo '<div class="row_offer">';
					get_template_part( $path_to_template, $post_type );
					echo '</div>';
				}
				else if( $mode == 'carrousel' ){
					get_template_part( $path_to_template, $post_type );
				}
				else if( $mode == 'carrousel_hover' ){
					echo '<li>';
					get_template_part( $path_to_template, $post_type );
					echo '</li>';
				}
				else if( $mode == 'list' ){
					if ( $key == $nb_item_visible_list ){ // from the 5th
						echo '<div class="more_offers" style="display:none">'; // Open div.more_offers
					}
					
					echo '<div class="el-list">';
					get_template_part( $path_to_template, $post_type );
					echo '</div>';
				}
				++$key;
			}

			if ( $mode == 'list' ){
				if( $key > $nb_item_visible_list ){
					echo '</div>'; // Close div.more_offers
					echo '<div class="seemore"><input type="button" class="see_more_offers btn btn_grey" value="'.__('J\'en veux plus').'"/></div>';
				}
			}
		}

		wp_reset_postdata();
	}

	public function render_list_carrousel( $settings ){

		$item_to_show = intval( apply_filters( 'fs_playlist-carousel-item_to_show', 2 ) );
		$item_to_show_mobile = intval( apply_filters( 'fs_playlist-carousel-item_to_show_mobile', 1 ) );
		$item_to_show_desktop = intval( apply_filters( 'fs_playlist-carousel-item_to_show_tablet', 3 ) );
		$item_to_slide = intval( apply_filters( 'fs_playlist-carousel-item_to_slide', 2 ) );

		$oids = [];
		foreach( $settings['oids'] as $oid ){
			$oids[] = $oid['oid'];
		}
		$total_item = sizeof( $oids );
		$rand = rand( 0, 1000 );
		?>
		<div class="owl-carousel owl-carousel-list-offres owl-theme" id="owl-carrousel-<?php echo $rand; ?>">
			<?php $this->get_offer_list( $oids, 'carrousel' ); ?>
		</div>
		<script>
			jQuery(function($){
				$(document).ready(function(){
					$('#owl-carrousel-<?php echo $rand; ?>').owlCarousel({
						items:              <?php echo $item_to_show; ?>,
						slideBy:            <?php echo $item_to_slide; ?>,
						slideSpeed:         900,
						autoplay:           false,
						autoplayTimeout:    3500,
						autoplayHoverPause: true,
						addClassActive:     true,
						nav:                true,
						loop:               <?php echo ( $total_item > $item_to_show ) ? 'true' : 'false' ; ?>,
						margin:             0,
						mouseDrag:          true,
						touchDrag:          true,
						center:             false,
						responsive :        {
							0 : { // breakpoint from 0 up
								items: <?php echo $item_to_show_mobile; ?>,
							},
							991 : { // breakpoint from 768 up
								items: <?php echo $item_to_show_desktop; ?>,
							}
						}
					});
				});
			})
		</script>
		<?php
	}

	public function get_first_offer( &$oids ){
		$args = [
			'post_type'      => 'any',
			'post_status'    => 'publish',
			'meta_key'       => 'syndicobjectid',
			'meta_value'     => $oids,
			'posts_per_page' => -1,
		];

		$query = new \WP_Query($args);

		global $order_oids;
		$order_oids = $oids;
		usort( $query->posts, [$this,'change_order_by_oids'] );

		if( $query->have_posts() ){
			while ( $query->have_posts() ) {
				$query->the_post();
				if ( !empty($query->post->ID) ){
					$post_type = get_post_type( $query->post->ID );
					$post_oid = get_field( 'syndicobjectid', $query->post->ID );
					get_template_part( 'template-parts/block/block', $post_type );
					unset( $oids[ array_search( $post_oid, $oids ) ] );
					break;
				}
			}
		}

		wp_reset_postdata();
	}

	public function render_list_carrousel_first_img_bigger( $settings ){

		$item_to_show         = intval( apply_filters( 'fs_playlist-carousel_first_img_bigger-item_to_show', 2 ) );
		$item_to_show_mobile  = intval( apply_filters( 'fs_playlist-carousel_first_img_bigger-item_to_show_mobile', 1 ) );
		$item_to_show_desktop = intval( apply_filters( 'fs_playlist-carousel_first_img_bigger-item_to_show_tablet', 2 ) );
		$item_to_slide        = intval( apply_filters( 'fs_playlist-carousel_first_img_bigger-item_to_slide',2 ) );

		$oids = [];
		foreach( $settings['oids'] as $oid ){
			$oids[] = $oid['oid'];
		}

		$this->get_first_offer( $oids );
		
		$total_item = sizeof( $oids );
		$rand = rand( 0, 1000 );
		?>
		<div class="owl-carousel owl-carousel-list-offres owl-theme" id="owl-carrousel-<?php echo $rand; ?>">
				<?php $this->get_offer_list( $oids, 'carrousel' ); ?>
		</div>
		<script>
			jQuery(function($){
				$(document).ready(function(){
					$('#owl-carrousel-<?php echo $rand; ?>').owlCarousel({
						items:              <?php echo $item_to_show; ?>,
						slideBy:            <?php echo $item_to_slide; ?>,
						slideSpeed:         900,
						autoplay:           false,
						autoplayTimeout:    3500,
						autoplayHoverPause: true,
						addClassActive:     true,
						nav:                true,
						loop:               <?php echo ( $total_item > $item_to_show ) ? 'true' : 'false' ; ?>,
						margin:             0,
						mouseDrag:          true,
						touchDrag:          true,
						center:             false,
						responsive :        {
							0 : { // breakpoint from 0 up
								items: <?php echo $item_to_show_mobile; ?>,
							},
							991 : { // breakpoint from 768 up
								items: <?php echo $item_to_show_desktop; ?>,
							}
						}
					});
				});
			})
		</script>
		<?php
	}

	public function render_list_carrousel_hover( $settings ){
		$oids = [];
		foreach( $settings['oids'] as $oid ){
			$oids[] = $oid['oid'];
		}
		$rand = rand( 0, 1000 );
		?>
		<div class="carrousel-hover hover-<?php echo $rand; ?>">
			<div class="indicator"></div>
			<div class="wrap">
				<ul>
					<?php $this->get_offer_list( $oids, 'carrousel_hover' ); ?>
				</ul>
			</div>
		</div>

		<script>
			jQuery(function($){
				$(document).ready(function(){
					"use strict";
					var bindToClass      = 'hover-<?php echo $rand; ?>',
						containerWidth   = 0,
						scrollWidth      = 0,
						posFromLeft      = 0,    // Stripe position from the left of the screen
						stripePos        = 0,    // When relative mouse position inside the thumbs stripe
						animated         = null,
						$indicator, $carousel, el, $el, ratio, scrollPos, nextMore, prevMore, pos, padding;

					// calculate the thumbs container width
					function calc(e){
						$el = $(this).find(' .wrap');
						el  = $el[0];
						$carousel = $el.parent();
						$indicator = $el.prev('.indicator');

						nextMore = prevMore  = false; // reset

						containerWidth       = el.clientWidth;
						scrollWidth          = el.scrollWidth; // the "<ul>"" width
						padding              = 0.2 * containerWidth; // padding in percentage of the area which the mouse movement affects
						posFromLeft          = $el.offset().left;
						stripePos            = e.pageX - padding - posFromLeft;
						pos                  = stripePos / (containerWidth - padding*2);
						scrollPos            = (scrollWidth - containerWidth ) * pos;

						if( scrollPos < 0 )
						scrollPos = 0;
						if( scrollPos > (scrollWidth - containerWidth) )
						scrollPos = scrollWidth - containerWidth;

						$el.animate({scrollLeft:scrollPos}, 200, 'swing');

						if( $indicator.length )
							$indicator.css({
								width: (containerWidth / scrollWidth) * 100 + '%',
								left: (scrollPos / scrollWidth ) * 100 + '%'
							});

						clearTimeout(animated);
						animated = setTimeout(function(){
							animated = null;
						}, 200);

						return this;
					}

					// move the stripe left or right according to mouse position
					function move(e){
						// don't move anything until inital movement on 'mouseenter' has finished
						if( animated ) return;

						ratio     = scrollWidth / containerWidth;
						stripePos = e.pageX - padding - posFromLeft; // the mouse X position, "normalized" to the carousel position

						if( stripePos < 0)
							stripePos = 0;

						pos = stripePos / (containerWidth - padding*2); // calculated position between 0 to 1
						// calculate the percentage of the mouse position within the carousel
						scrollPos = (scrollWidth - containerWidth ) * pos;

						el.scrollLeft = scrollPos;
						if( $indicator[0] && scrollPos < (scrollWidth - containerWidth) )
						$indicator[0].style.left = (scrollPos / scrollWidth ) * 100 + '%';

						// check if element has reached an edge
						prevMore = el.scrollLeft > 0;
						nextMore = el.scrollLeft < (scrollWidth - containerWidth);

						$carousel.toggleClass('left', prevMore);
						$carousel.toggleClass('right', nextMore);
					}

					$.fn.carousel = function(options){
						$(document)
						.on('mouseenter.carousel', '.' + bindToClass, calc)
						.on('mousemove.carousel', '.' + bindToClass, move);
					};

					// automatic binding to all elements which have the class that is assigned to "bindToClass"
					$.fn.carousel();
				});
			});
		</script>
		<?php
  	}

	public function render_map($settings){
		global $post;
		$settings = $this->get_settings_for_display();
		foreach( $settings['oids'] as $oid ){
			$oids[] = $oid['oid'];
		}

		$args = [
			'post_type'      => 'any',
			'post_status'    => 'publish',
			'meta_key'       => 'syndicobjectid',
			'meta_value'     => $oids,
			'posts_per_page' => -1,
		];

		$leaflet_settings = [
			'id'              => 'leaflet-map-'.mt_rand(),
			'data-map-design' => 'osm',
		];

		$query = new \WP_Query($args);

		global $order_oids;
		$order_oids = $oids;
		usort( $query->posts, [$this,'change_order_by_oids'] );

		$this->add_render_attribute( 'leaflet_map_settings', $leaflet_settings );
		?>
		<div style="height:500px" class="fs-widget-leaflet-map-playlist" <?php echo $this->get_render_attribute_string('leaflet_map_settings'); ?>>
			<?php
			if( $query->have_posts() ){
				$key = 0;
				while ( $query->have_posts() ) {
					$query->the_post();
					get_fields( $query->post->ID );
					$latitude = get_field( 'gmaplatitude', $query->post->ID );
					$longitude = get_field( 'gmaplongitude', $query->post->ID );
					if ( $latitude && $longitude ){
						$point_settings = [
							'data-id'  => $key+1,
							'data-lat' => $latitude,
							'data-lon' => $longitude,
						];

						$this->add_render_attribute( 'point-'.$post->syndicobjectid, $point_settings );
						?>
							<div style="display:none" class="point" <?php echo $this->get_render_attribute_string( 'point-'.$post->syndicobjectid ); ?>>
								<?php
									$post_type = get_post_type( $query->post->ID );
									get_template_part( 'template-parts/map/map', $post_type );
								?>
							</div>
						<?php
						++$key;
					}
				}
			}
			wp_reset_postdata();
			?>
		</div>
		<?php
	}

	public function change_order_by_oids( $post_a, $post_b ) {
		global $order_oids;
		$position_a = array_search( get_field( 'syndicobjectid', $post_a->ID ), $order_oids );
		$position_b = array_search( get_field( 'syndicobjectid', $post_b->ID ), $order_oids );
		return ( $position_a < $position_b ) ? -1 : 1;
	}
}
