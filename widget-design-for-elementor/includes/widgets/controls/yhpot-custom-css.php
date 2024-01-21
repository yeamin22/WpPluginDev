<?php

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Core\Files\CSS\Post;
use Elementor\Core\DynamicTags\Dynamic_CSS;

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
if(defined('ELEMENTOR_PRO_VERSION')){
	return;
}

class YHPOT_Custom_Css {
	/**
	 * Add Action hook
	 */
	public function __construct() {
		add_action('elementor/element/after_section_end', [__CLASS__, 'add_controls_section'], 10, 3);
		add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);	
		add_action( 'elementor/css-file/post/parse', [ $this, 'add_page_settings_css' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts']);
	}

	/**
	 * Replace Pro Custom CSS Control
	 */
	public static function add_controls_section($element, $section_id, $args) {
		if ($section_id == 'section_custom_css_pro') {

			$element->remove_control('section_custom_css_pro');
			$element->start_controls_section(
				'section_custom_css',
				[
					'label' => __( 'YHPOT Custom CSS', YHPOTCORE_TEXDOMAIN ),
					'tab' => Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_control(
				'custom_css_title',
				[
					'raw' => __( 'Add your own custom CSS here', YHPOTCORE_TEXDOMAIN ),
					'type' => Controls_Manager::RAW_HTML,
				]
			);

			$element->add_control(
				'custom_css',
				[
					'type' => Controls_Manager::CODE,
					'label' => __( 'Custom CSS', YHPOTCORE_TEXDOMAIN ),
					'language' => 'css',
					'render_type' => 'ui',
					'show_label' => false,
					'separator' => 'none',
				]
			);

			$element->add_control(
				'custom_css_description',
				[
					'raw' => __( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', YHPOTCORE_TEXDOMAIN ),
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'yhpot_core elementor-descriptor',
				]
			);

			$element->end_controls_section();
		}
	}

	/**
	 * @param $post_css Post
	 * @param $element  Element_Base
	 */
	public function add_post_css($post_css, $element) {
		if ($post_css instanceof Dynamic_CSS) {
			return;
		}

		$element_settings = $element->get_settings();

		if (empty($element_settings['custom_css'])) {
			return;
		}

		$css = trim($element_settings['custom_css']);

		if (empty($css)) {
			return;
		}
		$css = str_replace('selector', $post_css->get_element_unique_selector($element), $css);

		// Add a css comment
		$css = sprintf('/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector()) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css($css);
	}

	/**
	 * @param $post_css Post
	 */
	public function add_page_settings_css( $post_css ) {
		$document = \Elementor\Plugin::$instance->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'custom_css' );
		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	/**
	 * Enqueue Editor Script
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script('yhpot-editor_support', YHPOTCORE_ASSETS . "/js/elementor/yhpot-elementor.js", array('jquery'), '', true);
	}

}
new YHPOT_Custom_Css();
