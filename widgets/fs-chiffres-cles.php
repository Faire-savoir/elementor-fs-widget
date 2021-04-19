<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Chiffres_Cles extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args); 
		// CSS
		wp_register_style( 'fs-chiffres-cles', ELEMENTOR_FS_WIDGET_URL.'/assets/css/fs-chiffres-cles.css');
	}

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'fs-widget-chiffres-cles';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'FS Chiffres clés', 'fs-widget-chiffres-cles' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-number-field';
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
	 * Retrieve the list of styles the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 */
	public function get_style_depends() {
		return [ 'fs-chiffres-cles' ];
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
				'label' 			=> __( 'Content', 'elementor-fs-chiffres-cles' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label' 			=> __( 'Title', 'elementor-fs-chiffres-cles' ),
				'type' 				=> Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'prefix',
			[
				'label' 			=> __( 'Prefix', 'elementor-fs-chiffres-cles' ),
				'type' 				=> Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'nombre',
			[
				'label' 			=> __( 'Chiffre', 'elementor-fs-chiffres-cles' ),
				'type' 				=> Controls_Manager::NUMBER,
				'default' 			=> 1,
				'min' 				=> 0,
			]
		);

		$repeater->add_control(
			'suffix',
			[
				'label' 			=> __( 'Suffix', 'elementor-fs-chiffres-cles' ),
				'type' 				=> Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'chiffres_cles',
			[
					'label' 		=> __( 'Chiffres clés', 'elementor-fs-chiffres-cles' ),
					'type' 			=> Controls_Manager::REPEATER,
					'fields' 		=> $repeater->get_controls(),
					'default' 		=> [
						[],
					],
					'title_field' 	=> '{{{title}}}',
			]
		);

		$this->add_control(
			'position',
			[
				'label' 			=> __( 'Position', 'elementor-fs-chiffres-cles' ),
				'type' 				=> Controls_Manager::SELECT,
				'options' 			=> [
					'before' 	=> 'Titre avant le chiffre',
					'after' 	=> 'Titre après le chiffre'
				],
				'default' 			=> 'after',
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

		if(!empty($settings['chiffres_cles'])) {
			// CHIFFRES CLÉ
			?>
			<div class="chiffres_cles">
				<?php
				foreach ($settings['chiffres_cles'] as $chiffre_cle) {

					// UNE OCCURRENCE
					?>
					<div class="chiffre_cle"><?php
					if( isset($settings['position']) && $settings['position'] == 'before' ) {
						// IF TEXTE BEFORE
						if ( isset($chiffre_cle['title']) && !empty($chiffre_cle['title']) ) {
							?>
							<div class="title"><?php echo $chiffre_cle['title'] ?></div>
							<?php
						}
					}

					// BULLE CHIFFRE
					?>
					<div class="zone_chiffre">
						<?php
					if( isset($chiffre_cle['prefix']) && !empty($chiffre_cle['prefix']) ) {
						?>
						<span class="chiffre_txt prefix"><?php echo $chiffre_cle['prefix'] ?></span>
						<?php
					}
					if( isset($chiffre_cle['nombre']) && !empty($chiffre_cle['nombre']) ) {
						?>
						<span class="chiffre"><?php echo $chiffre_cle['nombre']?></span>
						<?php
					}
					if( isset($chiffre_cle['suffix']) && !empty($chiffre_cle['suffix']) ) {
						?>
						<span class="chiffre_txt suffix"><?php echo $chiffre_cle['suffix']?></span>
						<?php
					}
					?>
					</div>
					<?php

					if( isset($settings['position']) && $settings['position'] == 'after' ) {
						// IF TEXTE AFTER
						if ( isset($chiffre_cle['title']) && !empty($chiffre_cle['title']) ) {
							?>
							<div class="title"><?php echo $chiffre_cle['title'] ?></div>
							<?php
						}
					}
					?>
					</div>
					<?php

				}
				?>
			</div>
			<?php
		}
		unset( $settings,$chiffre_cle );

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
	}
}
