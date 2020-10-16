<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Mosaique_Link extends Widget_Base {

  /**
   * Retrieve the widget name.
   */
  public function get_name() {
    return 'fs-widget-mosaique-link';
  }

  /**
   * Retrieve the widget title.
   */
  public function get_title() {
    return __( 'FS Mosaique Link', 'fs-widget-mosaique-link' );
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
    return [];
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
        'label' => __( 'Content', 'elementor-fs-mosaique-link' ),
      ]
    );

    $repeater = new Repeater();

    $option_page = $this->get_page_site();

    $repeater->add_control(
      'libelle',
        [
          'label' => __( 'Libellé', 'elementor-fs-mosaique-link' ),
          'type' => Controls_Manager::TEXT,
        ]
    );

    $repeater->add_control(
      'image',
      [
        'label' => __( 'Choisir image', 'elementor-fs-mosaique-link' ),
        'type' => Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ]
      ]
    );

    $repeater->add_control(
      'libelle_cta',
        [
          'label' => __( 'Libellé CTA', 'elementor-fs-mosaique-link' ),
          'type' => Controls_Manager::TEXT,
        ]
    );

    $repeater->add_control(
      'link',
      [
        'label' => __( 'URL du lien', 'elementor' ),
        'type' => Controls_Manager::URL,
        'placeholder' => __( 'https://your-link.com', 'elementor' ),
      ]
    );

    $this->add_control(
      'mosaique_link',
      [
          'label' => __( 'Mosaïque Link', 'elementor-fs-chiffres-cles' ),
          'type' => Controls_Manager::REPEATER,
          'fields' => $repeater->get_controls(),
          'default' => [
            [],
          ],
          'title_field' => '{{{libelle}}}',
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
    global $post;
    $settings = $this->get_settings_for_display();
    $path_to_template = apply_filters( 'fs_mosaique_link-path_to_template', 'template-parts/widget/widget-mosaique-link' );
    set_query_var( 'mosaique_link', $settings['mosaique_link'] );
    get_template_part( $path_to_template );
  }

  /**
   * Render the widget output in the editor.
   *
   * Written as a Backbone JavaScript template and used to generate the live preview.
   */
  protected function _content_template() {}

  /**
   * Get all item menu who have childrens
   */
  private function get_page_site() {    
    $pages = get_pages(array('post_status' => 'publish'));
    foreach($pages as $page) {
      $res[$page->ID] = $page->post_title;
    }
    return $res;
  }
}


