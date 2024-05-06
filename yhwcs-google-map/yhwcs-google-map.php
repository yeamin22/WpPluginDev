<?php
/**
 * Plugin Name:       WCS Google Map
 * Plugin URI:        https://www.easywebsiteform.com/
 * Description:       Window Cleaning Service 
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            SeoIdaho
 * Author URI:        https://www.seoidaho.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       yhwcs-google-map
 * Domain Path:       /languages
 */

/**
* Exit if accessed directly
*/

if(!defined('ABSPATH') ) { die( "Don't try this" ); };

if ( !class_exists( "WcsGoogleMap" ) ) {

    final class WcsGoogleMap {

        /**
         * Instance Of WcsGoogleMap
         *
         * @var [instance]
         */
        private static $instance = null;

        /**
         * Construct of WcsGoogleMap
         */
        function __construct() {
            add_action( "plugin_loaded", [$this, "init"] );                
                add_action( "wp_enqueue_scripts", [$this, "yhwcs_assets"] );
        

        }

        /**
         * Singleton Instance
         * @return $instance
         */
        public static function Instance() {
            if ( self::$instance == null ) {
                self::$instance = new WcsGoogleMap();
            }
            return self::$instance;
        }

        /**
         * Initialization
         * @return void
         */
        public function init() {
            // Fire on plugins load and ready the textdomain for the plugin.
            $this->yhwcs_load_textdomain();
            //define constants for plugin
            $this->defineConstants();
            // Fire on active the plugin
            register_activation_hook( __FILE__, [$this, "glossymm_activation_callback"] );
            // Fire on deactivate the plugin
            register_deactivation_hook( __FILE__, [$this, "glossymm_deactivation_callback"] );
            $this->includeFiles();
            //new GlossyMM\Options();

        }

        /**
         * Included Required Files
         */

        public function includeFiles() {
            require_once "includes/elementor/yhwcs_elementor.php";           
        }

        /**
         * Define Constant
         * @return void
         */

        public function defineConstants() {
            if ( !defined( "WCS_GLMAP_URL" ) ) {
                define( "WCS_GLMAP_URL", plugin_dir_url( __FILE__ ) );
            }
            if ( !defined( "WCS_GLMAP_PATH" ) ) {
                define( "WCS_GLMAP_PATH", plugin_dir_path( __FILE__ ) );
            }
            if ( !defined( "WCS_GLMAP_ASSETS" ) ) {
                define( "WCS_GLMAP_ASSETS", plugin_dir_url( __FILE__ ) . "/assets" );
            }

        }

       
        /**
         * Plugin Language file
         * @return void
         */
        public function yhwcs_load_textdomain() {
            load_plugin_textdomain( "glossy-mega-menu", false, dirname( __FILE__ ) . "/languages" );
        }

        /**
         * Admin Assets Enquque
         * @param $screen
         * @return void
         */
        public function yhwcs_assets( $screen ) {            
                //admin style enqueue
                wp_enqueue_style( "yhwcs-style", WCS_GLMAP_ASSETS . "/css/yhwcs-style.css" );
                //admin script enqueue
                wp_enqueue_script( "yhwcs-googlemap-api","https://maps.googleapis.com/maps/api/js?key=AIzaSyAmuR-dmVslgyf3WQek4k1Oee2NCAn4IfQ&loading=async", [], time(), true );                  
        }
    }

}
// Instantiate Class
WcsGoogleMap::Instance();

//AIzaSyBHIv43CaPMWPzi5IFLg-QMBQgNjVOzAOk