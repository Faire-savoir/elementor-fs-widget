<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use ElementorPro\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Relation_Multi extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'fs-widget-relation-multi';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'FS Relation Multi', 'fs-widget-relation-multi' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 */
	public function get_categories() {
		return [ 'fs-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 */
	public function get_script_depends() {
		return [];
	}

	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'elementor-fs-relation-multi' ),
			]
		);
		
		$repeater = new Repeater();
		$query_args = apply_filters( 'fs_relation_multi-query_args', [] );
		$repeater->add_control(
			'post',
			[
				'type' => Module::QUERY_CONTROL_ID,
				'label_block' => true,
				'autocomplete' => [
					'object' => 'post',
					'display' => 'detailed',
					'query' => $query_args,
				],
			]
		);

		$this->add_control(
			'repeater_posts',
			[
					'label' => __( 'Posts', 'elementor-fs-relation-multi' ),
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[],
					],
					'title_field' => '{{{post}}}',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$path_to_template = 'components/widget-relation-multi';
		$path_to_template = apply_filters( 'fs_relation_multi-path_to_template', $path_to_template );

		if ( locate_template( $path_to_template.'.php' ) ) {
			echo '<div class="elementor_relation_multi">';
				if ( isset($settings['repeater_posts']) && !empty($settings['repeater_posts']) ) {
					foreach ( $settings['repeater_posts'] as $repeater_item ) :
						if ( isset($repeater_item['post']) && !empty($repeater_item['post']) ){
							get_template_part( $path_to_template, null, [ 'publication' => $repeater_item['post'] ] );
						}
					endforeach;
				}
			echo '</div>';
		}
		else{
			if ( is_admin() ){
				echo "<p style=\"text-align:center\">".
					sprintf(__("Le chemin du template (\"%s\") n'est pas valide..."),"<code>$path_to_template</code>")."<br>".
					sprintf(__("Pour choisir le template, utilisez le filtre : %s"),"<code>add_filter('fs_relation_multi-path_to_template')</code>").".".
				"</p>";
			}
		}
	}
}
