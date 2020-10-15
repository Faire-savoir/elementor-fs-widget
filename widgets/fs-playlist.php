<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class FS_Playlist extends Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);
    // CSS
    wp_register_style( 'style-flipster', plugins_url( '../assets/css/jquery.flipster.min.css', __FILE__ ));
    wp_register_style( 'fs-widget-leaflet-map-css', plugins_url( '../assets/css/fs-widget-leaflet-map.css', __FILE__ ));
    wp_register_style( 'fs-widget-playlist-css', plugins_url( '../assets/css/fs-widget-playlist.css', __FILE__ ));
    wp_register_style( 'leafletcss', '//unpkg.com/leaflet@'.LEAFLET_VERSION.'/dist/leaflet.css');
    wp_register_style( 'leaflet-gesture-handling-css', '//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css');
    wp_register_style( 'style-owl', plugins_url( '../assets/css/owl.carousel.min.css', __FILE__ ));
    //JS
    wp_register_script( 'script-flipster', plugins_url( '../assets/js/jquery.flipster.min.js', __FILE__ ));
    wp_register_script( 'leafletjs', '//unpkg.com/leaflet@'.LEAFLET_VERSION.'/dist/leaflet.js');
    wp_register_script( 'leaflet-gesture-handling-js', '//unpkg.com/leaflet-gesture-handling');
    wp_register_script( 'googlemap', FACETWP_LEAFLET_MAP_URL.'/assets/js/leaflet-google-correct-v1.js');
    wp_register_script( 'fs-playlist-map', plugins_url( '../assets/js/fs-playlist.js', __FILE__ ), [ 'jquery','elementor-frontend' ],
      false, true );
    wp_register_script( 'script-owl', plugins_url( '../assets/js/owl.carousel.js', __FILE__ ));
  }

  /**
   * Retrieve the widget name.
   *
   * @since 1.0.0
   *
   * @access public
   *
   * @return string Widget name.
   */
  public function get_name() {
    return 'fs-widget-playlist';
  }

  /**
   * Retrieve the widget title.
   *
   * @since 1.0.0
   *
   * @access public
   *
   * @return string Widget title.
   */
  public function get_title() {
    return __( 'FS Playlist', 'fs-widget-playlist' );
  }

  /**
   * Retrieve the widget icon.
   *
   * @since 1.0.0
   *
   * @access public
   *
   * @return string Widget icon.
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
   *
   * @since 1.0.0
   *
   * @access public
   *
   * @return array Widget categories.
   */
  public function get_categories() {
    return [ 'fs-elements' ];
  }

  /**
   * Retrieve the list of scripts the widget depended on.
   *
   * Used to set scripts dependencies required to run the widget.
   *
   * @since 1.0.0
   *
   * @access public
   *
   * @return array Widget scripts dependencies.
   */
  public function get_script_depends() {
    return [ 'script-flipster', 'script-owl', 'fs-playlist-map', 'leafletjs', 'leaflet-gesture-handling-js','googlemap' ];
  }

   /**
   * Retrieve the list of styles the widget depended on.
   *
   * Used to set styles dependencies required to run the widget.
   *
   * @since 1.0.0
   *
   * @access public
   *
   * @return array Widget styles dependencies.
   */
   public function get_style_depends() {
    return [ 'style-flipster', 'style-owl', 'fs-widget-playlist-css', 'fs-widget-leaflet-map-css', 'leafletcss', 'leaflet-gesture-handling-css' ];
   }

   public function get_playlist_appearance() {
    $designs = [
      'list'=>__( 'Liste', 'fs-widget-playlist-list' ),
      'map'=>__( 'Carte', 'fs-widget-playlist-list-map' ),
      'list_and_map'=>__( 'Liste & Carte', 'fs-widget-playlist-list-map' ),
    ];

    return $designs;
   }

   public function get_playlist_map_style() {
    $designs = [
      'map_top' => __('Map au-dessus'),
      'map_right'=>__( 'Carte à droite', 'fs-widget-playlist-list-map-right' ),
      'map_top'=>__( 'Carte au-dessus', 'fs-widget-playlist-map-top' ),
    ];

    return $designs;
   }

  public function get_playlist_list_style() {
    $designs = [
      'list'=>__( 'Liste simple', 'fs-widget-playlist-list' ),
      'carrousel'=>__( 'Carrousel', 'fs-widget-playlist-list-map-right' ),
      'coverflow'=>__( 'Coverflow', 'fs-widget-playlist-map-top' ),
      'carrousel_hg' => ('Carrousel - Première Image Fixe'),
    ];

    return $designs;
   }

  /**
   * Register the widget controls.
   *
   * Adds different input fields to allow the user to change and customize the widget settings.
   *
   * @since 1.0.0
   *
   * @access protected
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
        'type' => Controls_Manager::TEXT,
      ]
    );

    $this->add_control(
      'oids',
      [
        'label' => __( 'Liste OIs', 'fs-widget-playlist-liste-ois' ),
        'type' => Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [
          [],
        ],
        'title_field' => '{{{oid}}}',
      ]
    );

    $this->add_control(
      'apparence',
      [
        'label' => __( 'Apparence', 'fs-widget-leaflet-map' ),
        'type' => Controls_Manager::SELECT,
        'options' => $this->get_playlist_appearance(),
        'default' => 'list',
      ]
    );

    $this->add_control(
      'style_list',
      [
        'label' => __( 'Style Liste', 'fs-widget-leaflet-map' ),
        'type' => Controls_Manager::SELECT,
        'options' => $this->get_playlist_list_style(),
        'default' => 'list',
        'condition' => [
          'apparence' => ['list','list_and_map'],
        ],
      ]
    );

    $this->add_control(
      'style_map',
      [
        'label' => __( 'Style Map', 'fs-widget-leaflet-map' ),
        'type' => Controls_Manager::SELECT,
        'options' => $this->get_playlist_map_style(),
        'default' => 'map_top',
        'condition' => [
          'apparence' => ['map','list_and_map'],
        ],
      ]
    );

    $this->end_controls_section();

  }

  /**
   * Render the widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since 1.0.0
   *
   * @access protected
   */
  protected function render() {
    $settings = $this->get_settings_for_display();
    echo '<div class="playlist_list">';
      switch($settings['apparence']){
        case 'list':
          echo '<div class="list">';
            $this->render_list($settings);
          echo '</div>';
          break;
        case 'map':
          $this->render_map($settings);
          break;
        case 'list_and_map':
          echo '<div class="map_side '.$settings['style_map'].'">';
            $this->render_map($settings);
          echo '</div>';
          echo '<div class="list">';
            $this->render_list($settings);
          echo '</div>';
          break;

      }
    echo '</div>';
  }

  /**
   * Render the widget output in the editor.
   *
   * Written as a Backbone JavaScript template and used to generate the live preview.
   *
   * @since 1.0.0
   *
   * @access protected
   */
  protected function _content_template() {

  }

  public function render_map($settings){
    global $post;
    $settings = $this->get_settings_for_display();
    foreach($settings['oids'] as $oid){
      $oids[] = $oid['oid'];
    }

    $args = array(
      'post_type' => 'any',
      'post_status' => 'publish',
      'meta_key' => 'syndicobjectid',
      'meta_value' => $oids,
    );

    $leaflet_settings = array(
      'id'=> 'leaflet-map-'.mt_rand(),
      'data-map-design'=> 'osm',
    );

    $query = new \WP_Query($args);

    global $order_oids;
    $order_oids = $oids;
    usort( $query->posts, [$this,'change_order_by_oids'] );

    $this->add_render_attribute('leaflet_map_settings', $leaflet_settings);
    ?>
    <div style="height:500px" class="fs-widget-leaflet-map-playlist" <?php echo $this->get_render_attribute_string('leaflet_map_settings'); ?>>
      <?php
      if($query->have_posts()){
        $key = 0;
        while ( $query->have_posts() ) {
          $query->the_post();
          $latitude = get_field('gmaplatitude',$query->post->ID);
          $longitude = get_field('gmaplongitude',$query->post->ID);
          if ( $latitude && $longitude ){
            $point_settings = [
              'data-id'=> $key+1,
              'data-lat'=> $latitude,
              'data-lon'=> $longitude,
            ];

            $this->add_render_attribute('point-'.$post->syndicobjectid, $point_settings);
            ?>
              <div style="display:none" class="point" <?php echo $this->get_render_attribute_string('point-'.$post->syndicobjectid);?>>
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

  public function render_list_coverflow($settings){
    $oids = [];
    $rand = rand(0,1000);
    foreach($settings['oids'] as $oid){
      $oids[] = $oid['oid'];
    }
    ?>
    <div id="coverflow-list-offres-<?php echo $rand; ?>">
      <div class="coverflow">
        <?php
        $this->get_offer_list($oids, 'coverflow');
        ?>
      </div>
    </div>
    <?php if(count($oids) > 2) : ?>
    <script>
      jQuery(function($){
        $(document).ready(function(){
          var carousel = $("#coverflow-list-offres-<?php echo $rand; ?>").flipster({
            style: 'carousel',
            spacing: -0.6,
            nav: false,
            fadeIn: 400,
            buttons: true,
            loop: true,
            itemContainer: '.coverflow',
            itemSelector: '.row_offer',
            start: 'center',
            pauseOnHover: false,
            autoplay: false,
            touch: true,
            scrollwheel: false,
            keyboard: true,
          });
        });
      })
    </script>
    <?php endif; ?>
    <?php
  }

  public function render_simple_list($settings){
    foreach($settings['oids'] as $oid){
      $oids[] = $oid['oid'];
    }
    $this->get_offer_list($oids, 'list');
  }

  public function get_offer_list($oids, $mode = 'coverflow'){
    $args = array(
      'post_type' => 'any',
      'post_status' => 'publish',
      'meta_key' => 'syndicobjectid',
      'meta_value' => $oids,
    );

    $query = new \WP_Query($args);

    // Gestion du tri des oids comme l'ordre de la conf Elementor
    // Voir : https://kuttler.eu/code/order-posts-in-a-wp_query-manually/
    global $order_oids;
    $order_oids = $oids;
    usort( $query->posts, [$this,'change_order_by_oids'] );

    if($query->have_posts()){
      $key = 0;
      while ( $query->have_posts() ) {
        $query->the_post();
        $post_type = get_post_type( $query->post->ID );
        if($mode == 'coverflow'){
          echo '<div class="row_offer">';
          get_template_part( 'template-parts/block/block-coverflow', $post_type );
          echo '</div>';
        }
        else if($mode == 'carrousel'){
          get_template_part( 'template-parts/block/block-carrousel', $post_type );
        }
        else if($mode == 'list'){
          if ($key == 4){ // from the 5th
            echo '<div class="more_offers" style="display:none">'; // Open div.more_offers
          }
          
          echo '<div class="el-list">';
          get_template_part( 'template-parts/block/block', $post_type );
          echo '</div>';
        }
        ++$key;
      }

      if ( $mode == 'list' ){
        if($key > 4){
          echo '</div>'; // Close div.more_offers
          echo '<div class="seemore"><input type="button" class="see_more_offers btn btn_grey" value="'.__('J\'en veux plus').'"/></div>';
        }
      }
    }

    wp_reset_postdata();
  }

  public function get_first_offer(&$oids){
    $args = array(
      'post_type' => 'any',
      'post_status' => 'publish',
      'meta_key' => 'syndicobjectid',
      'meta_value' => $oids,
    );

    $query = new \WP_Query($args);

    global $order_oids;
    $order_oids = $oids;
    usort( $query->posts, [$this,'change_order_by_oids'] );

    if($query->have_posts()){
      while ( $query->have_posts() ) {
        $query->the_post();
        if ( isset($query->post->ID) && !empty($query->post->ID) ){
          $post_type = get_post_type( $query->post->ID );
          $post_oid = get_field( 'syndicobjectid', $query->post->ID );
          get_template_part( 'template-parts/block/block', $post_type );
          unset($oids[array_search($post_oid, $oids)]);
          break;
        }
      }
    }

    wp_reset_postdata();
  }

  public function render_list($settings){
    if (wonderplugin_is_device('Mobile')){
      $this->render_list_carrousel($settings);
    }
    else {
      switch($settings['style_list']){
        case 'list' :
          $this->render_simple_list($settings);
          break;
        case 'carrousel' :
          $this->render_list_carrousel($settings);
          break;
        case 'coverflow' :
          $this->render_list_coverflow($settings);
          break;
        case 'carrousel_hg' :
          $this->render_list_carrousel_hg($settings);
          break;
      }
    }
  }

  public function render_list_carrousel($settings){
    $oids = [];
    foreach($settings['oids'] as $oid){
      $oids[] = $oid['oid'];
    }
    $rand = rand(0,1000);
    ?>
    <div class="owl-carousel owl-carousel-list-offres owl-theme" id="owl-carrousel-<?php echo $rand; ?>">
        <?php $this->get_offer_list($oids, 'carrousel'); ?>
    </div>
    <script>
      jQuery(function($){
        $(document).ready(function(){
          $('#owl-carrousel-<?php echo $rand; ?>').owlCarousel({
            items:2,
            slideBy: 2,
            slideSpeed:900,
            autoplay:false,
            autoplayTimeout:3500,
            autoplayHoverPause:true,
            addClassActive:true,
            nav:true,
            loop:true,
            margin:0,
            mouseDrag:true,
            touchDrag:true,
            center: false,
            responsive : {
              // breakpoint from 0 up
              0 : {
                items: 1,
              },
              // breakpoint from 768 up
              991 : {
                items: 3,
              }
            }
          });
        });
      })
    </script>
    <?php
  }

  public function render_list_carrousel_hg($settings){
    $oids = [];
    foreach($settings['oids'] as $oid){
      $oids[] = $oid['oid'];
    }
    $this->get_first_offer($oids);
    $rand = rand(0,1000);
    ?>
    <div class="owl-carousel owl-carousel-list-offres owl-theme" id="owl-carrousel-<?php echo $rand; ?>">
        <?php $this->get_offer_list($oids, 'carrousel'); ?>
    </div>
    <script>
      jQuery(function($){
        $(document).ready(function(){
          $('#owl-carrousel-<?php echo $rand; ?>').owlCarousel({
            items:2,
            slideBy: 2,
            slideSpeed:900,
            autoplay:false,
            autoplayTimeout:3500,
            autoplayHoverPause:true,
            addClassActive:true,
            nav:true,
            loop:true,
            margin:0,
            mouseDrag:true,
            touchDrag:true,
            center:false,
            responsive : {
              // breakpoint from 0 up
              0 : {
                items: 1,
              },
              // breakpoint from 768 up
              991 : {
                items: 2,
              }
            }
          });
        });
      })
    </script>
    <?php
  }

  public function change_order_by_oids( $post_a, $post_b ) {
    global $order_oids;
    $position_a = array_search( get_field('syndicobjectid',$post_a->ID), $order_oids );
    $position_b = array_search( get_field('syndicobjectid',$post_b->ID), $order_oids );
    return ( $position_a < $position_b ) ? -1 : 1;
  }
}
