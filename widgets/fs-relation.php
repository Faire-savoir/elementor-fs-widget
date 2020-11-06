<?php

namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

Class FS_Relation extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'fs-widget-relation';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return __( 'FS Relation', 'fs-widget-relation' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-post';
	}

	/**
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 */
	public function get_categories() {
		return [ 'fs-elements' ];
	}

	/**
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Contenu', 'fs-widget-relation' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$options = [ __( 'Sélectionnez une publication', 'fs-widget-relation' ) ];

		$args = [
			'post_type' => 'musee',
			'posts_per_page' => -1,
		];
		$args = apply_filters('fs_relation-query_args',$args);
		$wp_query = new \WP_Query( $args );
		
		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();
				$value = get_the_ID();
				$label = get_the_title();
				$options[$value] = $label;
			}
			wp_reset_postdata();
		}
		
		wp_reset_query();

		$this->add_control(
			'publication',
			[
				'label' => __( 'Publication', 'fs-widget-relation' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => false,
				'label_block' => true,
				'options' => $options,
				'default' => 0,
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();

		$path_to_template = 'template-parts/widget/widget-relation';
		$path_to_template = apply_filters( 'fs_relation-path_to_template', $path_to_template );

		if ( locate_template( $path_to_template.'.php' ) ) {
			get_template_part( $path_to_template, null, [ 'publication' => $settings['publication'] ] );
		}
		else{
			if ( is_admin() ){
				echo "<p style=\"text-align:center\">".
					sprintf(__("Le chemin du template (%s) n'est pas valide..."),"<code>\"$path_to_template\"</code>")."<br>".
					sprintf(__("Pour choisir le template utilisez %s"),"<code>add_filter('fs_relation-path_to_template')</code>").".".
				"</p>";
			}
		}
		
	}

}
