<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Citation extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'fs-widget-citation';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'FS Citation', 'fs-widget-citation' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
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
		return [ 'elementor-fs-citation' ];
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
				'label' 		=> 		__( 'Content', 'elementor-fs-citation' ),
			]
		);

		$this->add_control(
			'citation',
			[
				'label' 		=> 		__( 'Citation', 'elementor-fs-citation' ),
				'type' 			=> 		Controls_Manager::TEXTAREA,
				'default' 		=> 		__( 'Citation', 'elementor-fs-citation' ),
			]
		);

		$this->add_control(
			'auteur',
			[
				'label' 		=> 		__( 'Auteur', 'elementor-fs-citation' ),
				'type' 			=> 		Controls_Manager::TEXT,
				'default' 		=> 		__( 'Auteur', 'elementor-fs-citation' ),
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

		echo '
			<div class="elementor_citation">
				<section>
					<blockquote>
						<p class="content">'.$settings['citation'].'</p>
						<footer>
							<cite>'.$settings['auteur'].'</cite>
						</footer>
					</blockquote>
				</section>
			</div>
		';
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
?>
		<div class="elementor_citation">
			<section>
				<blockquote>
					<p class="content">{{{ settings.citation }}}</p>
					<footer>
						<cite>{{{ settings.auteur }}}</cite>
					</footer>
				</blockquote>
			</section>
		</div>

<?php
	}
}
