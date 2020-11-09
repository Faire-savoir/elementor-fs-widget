<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Promotion_Article extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'fs-widget-promotion-article';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'FS Promotion Article', 'fs-widget-promotion-article' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-meta-data';
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
				'label' => __( 'Content', 'elementor-fs-promotion-article' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'elementor-fs-type' ),
				'type' => Controls_Manager::SELECT,
	            'options' => $this->get_type_article(),
	            'default' => 0,
			]
		);

		$this->add_control(
			'categorie',
			[
				'label' => __( 'Categorie', 'elementor-fs-categorie' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_terms_taxo('categories_articles'),
				'default' => 0,
			]
		);


		$this->add_control(
			'theme',
			[
				'label' => __( 'Thème', 'elementor-fs-theme' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_terms_taxo('theme_article'),
				'default' => 0,
			]
		);

		$this->add_control(
			'nombre',
			[
				'label' => __( 'Nombre d\'articles', 'elementor-fs-nombre' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
				'max' => 3,
			]
		);

		$this->add_control(
			'mise_en_avant',
			[
				'label' => __( 'Mise en avant HP ?', 'elementor-fs-theme' ),
				'type' => Controls_Manager::SWITCHER,
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

		$args = array(
			'post_type' => 'post',
		    'orderby' => 'date',
		);

		if($settings['type'] != '0') {
		    $args['meta_query'] = array(
		        array(
		            'key'     => 'type_page',
		            'value'   => $settings['type'],
		        )
		    );
		}

		$tax_query = [];
		if($settings['categorie'] != '0') {
			$tax_query[] =
				array(
		            'taxonomy' => 'categories_articles',
		            'field'    => 'slug',
		            'terms'    => $settings['categorie'],
		        );
		}
		if($settings['theme'] != '0') {
			$tax_query[] =
				array(
		            'taxonomy' => 'theme_article',
		            'field'    => 'slug',
		            'terms'    => $settings['theme'],
		        );
		}

		if(!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		if(isset($settings['nombre'])) {
			$args['posts_per_page'] = $settings['nombre'];
		}
		else {
			unset($settings,$args,$tax_query);
			return FALSE;
		}

		if($settings['mise_en_avant'] == 'yes') {
			$sticky_posts = get_option( 'sticky_posts' );
			if(!empty($sticky_posts)) {
				$args['post__in'] = $sticky_posts;
			}
			else {
				$args['post__in'] = [ 0 ];
			}
		}

		$path_to_template = apply_filters( 'fs_promotion_article-path_to_template', 'template-parts/widget/widget-promotion-article' );

		$the_query = new \WP_Query( $args );
		if ( $the_query->have_posts() ) {
		    while ( $the_query->have_posts() ) {
		        $the_query->the_post();
		    	get_template_part($path_to_template);
		    }
		}
		wp_reset_postdata();

		unset($settings,$args,$tax_query,$sticky_posts,$the_query);

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
	}
	/**
	 * Get value of type for article
	 */

	private function get_type_article() {
		$types = [ 0 => ' - Aucun - '];
		global $wpdb;
		$resultats = $wpdb->get_results("SELECT DISTINCT(meta_value) FROM {$wpdb->prefix}postmeta WHERE meta_key = 'type_page';") ;
		foreach ($resultats as $res) {
			$types[$res->meta_value] = $res->meta_value;
		}
		unset($resultats,$res);
		return $types;
	}

	/**
	 * Get term of a taxonomy
	 */

	private function get_terms_taxo($taxonomy) {
		$res = [ 0 => ' - Aucun - '];
		$terms = get_terms( array(
		    'taxonomy' => $taxonomy,
		    'hide_empty' => false,
		) );
		foreach ($terms as $term) {
			if ( isset($term->slug) && !empty($term->slug) && isset($term->name) && !empty($term->name) ){
				$res[$term->slug] = $term->name;
			}
		}
		unset($terms,$taxonomy,$term);
		return $res;
	}
}

