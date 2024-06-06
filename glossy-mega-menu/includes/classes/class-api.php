<?php
namespace GlossyMM;
defined( 'ABSPATH' ) || exit;

use GlossyMM\Utils;

class Api {

	public $prefix  = '';
	public $param  = '/(?P<key>\w+(|[-]\w+))/';
	public $request = null;

	public function __construct() {
		$this->config();
		$this->init();
	}

	public function config() { 
		$this->prefix = 'megamenu';
	}

	public function init() {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					untrailingslashit( 'glossymm/v1/' . $this->prefix ),
					'/(?P<action>\w+)/' . ltrim( $this->param, '/' ),
					array(
						'methods'             => \WP_REST_Server::ALLMETHODS,
						'callback'            => array( $this, 'callback' ),
						'permission_callback' => '__return_true', 
					)
				);
			}
		);
	}

	public function callback( $request ) {
		$this->request = $request;
		$action = $request['action'];
		error_log("API: Request: ". print_r($request, true));
		
		// Call the appropriate method based on the action
		if (method_exists($this, 'get_' . $action)) {
			$method = 'get_' . $action;
			return $this->$method();
		} else {
			return new \WP_Error('no_action', __('No such action found'), array('status' => 404));
		}
	}

	public function get_save_menuitem_settings() {
		if (!current_user_can('manage_options')) {
			return new \WP_Error('permission_denied', __('You do not have permission to perform this action'), array('status' => 403));
		}
		$menu_item_id = $this->request['settings']['menu_id'];
		$menu_item_settings = wp_json_encode($this->request['settings'], JSON_UNESCAPED_UNICODE);
		//update_post_meta($menu_item_id, Init::$menuitem_settings_key, $menu_item_settings);

		return array(
			'saved' => 1,
			'message' => esc_html__('Saved', 'elementskit-lite'),
		);
	}

	public function get_get_menuitem_settings() {
		if (!current_user_can('manage_options')) {
			return new \WP_Error('permission_denied', __('You do not have permission to perform this action'), array('status' => 403));
		}
		$menu_item_id = $this->request['menu_id'];

		$data = ''; //get_post_meta($menu_item_id, Init::$menuitem_settings_key, true);
		return (array)json_decode($data);
	}

	public function get_content_editor() {
		$content_key  = $this->request['key'];		
		$builder_post_title = 'glossymm-content-' . $content_key;
		$builder_post_id    = Utils::get_page_by_title( $builder_post_title, 'glossymm_content' );
 		if ( is_null( $builder_post_id ) ) {
			$defaults        = array(
				'post_content' => '',
				'post_title'   => $builder_post_title,
				'post_status'  => 'publish',
				'post_type'    => 'glossymm_content',
			);
			$builder_post_id = wp_insert_post( $defaults );
			update_post_meta( $builder_post_id, '_wp_page_template', 'elementor_canvas' );
		} else {
			$builder_post_id = $builder_post_id->ID;
		} 	
		$url = admin_url( 'post.php?post=' . $builder_post_id . '&action=elementor' );
		wp_safe_redirect( $url );
		exit;
	}


	public function get_megamenu_content() {
		$menu_item_id = intval($this->request['id']);
		print_r($this->request['key']);

		if (!get_post_status($menu_item_id) || post_password_required($menu_item_id)) {
			return new \WP_Error('invalid_id', __('Invalid menu item ID'), array('status' => 404));
		}

		$elementor = \Elementor\Plugin::instance();
		$output = $elementor->frontend->get_builder_content_for_display($menu_item_id);

		return $output;
	}
}

new Api();
