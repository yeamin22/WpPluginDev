<?php 
namespace GlossyMM;

if ( !defined( 'ABSPATH' ) ) {die( "Don't try this" );}

class Utils {

	public static $instance = null;
	private static $key     = 'glossymm_options';

    public static function instance() {
		if ( is_null( self::$instance ) ) {
			// Fire the class instance
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public static function  get_option( $key, $default = '' ) {
		$data_all = get_option( self::$key );
		return ( isset( $data_all[ $key ] ) && $data_all[ $key ] != '' ) ? $data_all[ $key ] : $default;
	}

	public static function save_option( $key, $value = '' ) {
		$data_all         = get_option( self::$key );
		$data_all[ $key ] = $value;
		update_option( 'glossymm_options', $data_all );
	}

	public function get_settings( $key, $default = '' ) {
		$data_all = $this->get_option( 'settings', array() );
		return ( isset( $data_all[ $key ] ) && $data_all[ $key ] != '' ) ? $data_all[ $key ] : $default;
	}

	public function save_settings( $new_data = '' ) {
		$data_old = $this->get_option( 'settings', array() );
		$data     = array_merge( $data_old, $new_data );
		$this->save_option( 'settings', $data );
	}	

	public static function strify( $str ) {
		return strtolower( preg_replace( '/[^A-Za-z0-9]/', '__', $str ) );
	}

	public static function get_page_by_title($page_title, $post_type = "page"){
		$query = new \WP_Query(
			array(
				'post_type' => $post_type,
				'title' => $page_title,
			)
		);
		if (!empty($query->post)) {
			$page_got_by_title = $query->post;
		} else {
			$page_got_by_title = null;
		}

		return $page_got_by_title;

	}


	public static function get_attachment_image_html( $settings, $image_key, $image_size_key = null, $image_attr = array() ) {
		if ( ! $image_key ) {
			$image_key = $image_size_key;
		}

		$image = $settings[ $image_key ];

		$size = $image_size_key;

		$html = '';
		if ( ! empty( $image['id'] ) && $image['id'] != '-1' ) {
			$html .= wp_get_attachment_image( $image['id'], $size, false, $image_attr );
		} else {
			$html .= sprintf( '<img src="%s" title="%s" alt="%s" />', esc_attr( $image['url'] ), \Elementor\Control_Media::get_image_title( $image ), \Elementor\Control_Media::get_image_alt( $image ) );
		}

		$html = preg_replace( array( '/max-width:[^"]*;/', '/width:[^"]*;/', '/height:[^"]*;/' ), '', $html );

		return $html;
	}

	public static function img_meta( $id ) {
		$attachment = get_post( $id );
		if ( $attachment == null || $attachment->post_type != 'attachment' ) {
			return null;
		}
		return array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'src'         => $attachment->guid,
			'title'       => $attachment->post_title,
		);
	}

}
