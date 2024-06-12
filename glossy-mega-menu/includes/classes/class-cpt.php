<?php 
namespace GlossyMM;

defined( 'ABSPATH' ) || exit;

class Cpt {

	public function __construct() {
		add_action("init", [$this , "post_type"]);  
	}

	public function post_type() {
		
		$labels  = array(
			'name'                  => _x( 'GlossyMM items', 'Post Type General Name', 'glossy-mega-menu' ),
			'singular_name'         => _x( 'GlossyMM item', 'Post Type Singular Name', 'glossy-mega-menu' ),	

		);
		$rewrite = array(
			'slug'       => 'glossymm-content',
			'with_front' => true,
			'pages'      => false,
			'feeds'      => false,
		);
		$args    = array(
			'label'               => esc_html__( 'GlossyMM item', 'glossy-mega-menu' ),
			'description'         => esc_html__( 'glossymm_content', 'glossy-mega-menu' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'elementor', 'permalink' ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'query_var'           => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
			'rest_base'           => 'glossymm-content',
		);
		register_post_type( 'glossymm_content', $args );
	}

	public function flush_rewrites() {
		$this->post_type();
		flush_rewrite_rules();
	}
}

new Cpt();
