<?php
namespace ElementorFSWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FS_Bouton extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		// CSS
		wp_register_style( 'fs-bouton', ELEMENTOR_FS_WIDGET_URL.'/assets/css/fs-bouton.css' );
	}

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'fs-widget-bouton';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'FS Bouton', 'fs-widget-bouton' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
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
		return [ 'fs-bouton' ];
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
				'label' => __( 'Content', 'elementor-fs-bouton' ),
			]
		);


			$this->add_control(
			'type',
			[
				'label'   => __( 'Type', 'elementor-fs-bouton' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'lien'  => 'Lien',
					'media' => 'Media'
				],
				'default' => 'lien',
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'condition'   => [
					'type' => 'lien',
				],
			]
		);

		/*
			ALL FILES => '',
			IMAGES => 'image',
			VIDEOS => 'video',
			DOCUMENTS => 'application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-word.document.macroEnabled.12,application/vnd.ms-word.template.macroEnabled.12,application/vnd.oasis.opendocument.text,application/vnd.apple.pages,application/pdf,application/vnd.ms-xpsdocument,application/oxps,application/rtf,application/wordperfect,application/octet-stream'
		*/
		$authorized_files = apply_filters( 'fs_widget_fs_bouton_filter_media_authorized_types', 'application/pdf' );

		$this->add_control(
			'media',
			[
				'label'      => __( 'Media', 'elementor-fs-boutton' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => $authorized_files,
				'condition'  => [
					'type' => 'media',
				],
			]
		);

		$this->add_control(
			'libelle',
			[
				'label'   => __( 'Libellé', 'elementor-fs-boutton' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Cliquez ici','elementor-fs-boutton')
			]
		);

		$styles_btn = apply_filters('fs_widget_fs_bouton_filter_link_btn_styles',[
			'default' => 'Classique (défaut)',
			'fleche'  => 'Flèche'
		]);
		$this->add_control(
			'style-lien',
			[
				'label'   => __( 'Style Lien', 'elementor-fs-bouton' ),
				'type'    => Controls_Manager::SELECT,
				'options' => ( is_array($styles_btn) ? $styles_btn : [] ),
				'default' => 'default',
			]
		);

		$modes_media = apply_filters('fs_widget_fs_bouton_filter_media_modes',[
			'default'      => 'Lien simple (défaut)',
			'blank'        => 'Nouvel onglet',
			'downloadable' => 'Lien téléchargeable',
		]);
		$this->add_control(
			'media-mode',
			[
				'label'     => __( 'Mode', 'elementor-fs-boutton' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => ( is_array($modes_media) ? $modes_media : [] ),
				'default'   => 'default',
				'condition' => [
					'type' => 'media',
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
		// init vars
		$class = [];

		if( $settings['type'] == 'lien' ) {

			$class[] = 'lien';
			if ( !empty($settings['style-lien']) ){
				$class[] = $settings['style-lien'];
			}

			if ( !empty($settings['link']['url']) ){
				$this->add_link_attributes( 'link-attributes', $settings['link'] );
				$link_attribtues = $this->get_render_attribute_string( 'link-attributes' );
			}

		}
		else if ( $settings['type'] == 'media' ) {
			// Le lien n'est pas vide
			if ( !empty($settings['media']['url']) ) {
				$class[] = 'media';
				// On récupère l'extension
				$extension = explode( '.', $settings['media']['url'] );
				if ( is_array($extension) && !empty($extension) ){
					$extension 	= end($extension);
					$class[] = $extension;
				}

				if ( isset($settings['media-mode']) && $settings['media-mode'] == 'downloadable' ) {
					$settings['media']['custom_attributes'] = 'download|';
					$settings['media']['is_external'] = 'on';
				}
				else if ( isset($settings['media-mode']) && $settings['media-mode'] == 'blank' ) {
					$settings['media']['is_external'] = 'on';
				}

				if( !empty($settings['media']['url']) ){
					$this->add_link_attributes( 'link-attributes', $settings['media'] );
					$link_attribtues = $this->get_render_attribute_string( 'link-attributes' );
				}

			}
		}

		if( !empty($link_attribtues) ) :
			?>
			<div class="fs-bouton">
				<div class="<?php echo implode( ' ', $class ); ?>">
					<a <?php echo $link_attribtues; ?> >
						<span><?php echo ( !empty($settings['libelle']) ) ? $settings['libelle'] : ''; ?></span>
					</a>
				</div>
			</div>
			<?php
		else :
			?>
			<div><?php _e( "Aucun lien/média n'a été selectionné.", 'elementor-fs-bouton' ) ?></div>
			<?php
		endif;
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
	}
}
