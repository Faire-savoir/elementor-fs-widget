<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Sommaire extends Widget_Base {

  /**
   * Retrieve the widget name.
   */
  public function get_name() {
    return 'fs-widget-sommaire';
  }

  /**
   * Retrieve the widget title.
   */
  public function get_title() {
    return __( 'FS Sommaire', 'fs-widget-sommaire' );
  }

  /**
   * Retrieve the widget icon.
   */
  public function get_icon() {
    return 'eicon-sitemap';
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
        'label' => __( 'Content', 'elementor-fs-sommaire' ),
      ]
    );

    $option_page = $this->get_page_sommaire();
    $key = (isset($option_page) && !empty($option_page))?array_key_first($option_page):null;

    $this->add_control(
      'page',
      [
        'label' => __( 'Entrée d\'arbo', 'elementor-fs-page' ),
        'type' => Controls_Manager::SELECT,
              'options' => $option_page,
              'default' => $key,
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

    $classes =  apply_filters('fs_widget_fs_sommaire_filter_wrapper_classes',[
      'container',
      'row',
      'listing',
      'listing-sommaire'
    ]);
    $nb_highlighted_elements = apply_filters('fs_widget_fs_sommaire_filter_nb_highlighted_elements',2);

    $post = get_post($settings['page']);
    $mypages = get_pages([
      'child_of' => $post->ID,
      'parent' => $post->ID,
      'sort_column' => 'menu_order'
    ]);

    if ( is_array($classes) && is_numeric($nb_highlighted_elements) ) :
      ?>
      <div class="<?php echo implode(' ',$classes); ?>">
        <?php
          $i = 0;
          foreach( $mypages as $post ) {
            setup_postdata($post);
            if($i < $nb_highlighted_elements) {
              get_template_part( 'template-parts/widget/widget-sommaire','big' );
            }
            else {
              get_template_part( 'template-parts/widget/widget-sommaire' );
            }
            ++$i;
          }
        ?>
      </div>
      <?php
    endif;
    wp_reset_postdata();
  }

  /**
   * Render the widget output in the editor.
   *
   * Written as a Backbone JavaScript template and used to generate the live preview.
   */
  protected function _content_template() {

  }

  /**
   * Get all item menu who have childrens
   */

  private function get_page_sommaire() {

    $item_menu = wp_get_nav_menu_items('menu-principal');
    $parent = [];
    foreach ($item_menu as $item) {
      if(isset($item->post_parent) && !empty($item->post_parent)) {
        $parent[$item->post_parent] = get_the_title($item->post_parent);
      }
    }

    $pages = get_pages();
    $parent = [];
    foreach($pages as $page) {
      if($page->post_parent !== 0) {
        $parent[$page->post_parent] = get_the_title($page->post_parent);
      }
    }

    return $parent;
  }
}

