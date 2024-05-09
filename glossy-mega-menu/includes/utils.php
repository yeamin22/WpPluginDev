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

}
