<?php
/**
 * Plugin Name:       Widgets Design For Elementor
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            YH SAJIB
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       wdf-elementor
 * Domain Path:       /languages
 */

/**
 * Exit if accessed directly
 */
defined( 'ABSPATH' ) || exit;

if ( !defined( "WDFTEXTDOMAIN" ) ) {
    define( 'WDFTEXTDOMAIN', 'wdf-elementor' );
}
if ( !defined( "WDFE_PLUGIN_PATH" ) ) {
    define( 'WDFE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( "WDFE_PLUGIN_URI" ) ) {
    define( 'WDFE_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

if ( !class_exists( "WDF_Elementor" ) ) {
    class WDF_Elementor {

        private static $instance = null;

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        public function load() {
            add_action( "plugin_loaded", [$this, "init"] );
            add_action( "elementor/frontend/after_enqueue_scripts", [$this, "enqueue_custom_widget_script"] );
        }
        // Class Init Action Method
        function init() {
            // Fire on plugins load and ready the textdomain for the plugin.
            $this->wdfe_load_textdomain();
            // Fire on active the plugin
            register_activation_hook( __FILE__, [$this, "wdfe_activation_callback"] );
            // Fire on deactive the plugin
            register_deactivation_hook( __FILE__, [$this, "wdfe_deactivation_callback"] );

            require_once WDFE_PLUGIN_PATH . "/includes/wdfe_functions.php";
            require_once WDFE_PLUGIN_PATH . "/includes/WDFE_Widgets.php";

            new WDFE_Widgets();

        }

        function enqueue_custom_widget_script() {
        
                  //  wp_enqueue_script('at_animated_typed', WDFE_PLUGIN_URI .'/assets/js/lib/typed.umd.js', array('jquery'), '1.0', true);
                    //wp_enqueue_script('at_animated_typed_main', WDFE_PLUGIN_URI .'/assets/js/wdfe-main.js', array('jquery','at_animated_typed'), '1.0', true);
                
            
        }
    
        

        function wdfe_activation_callback() {

        }
        function wdfe_deactivation_callback() {

        }
        function wdfe_load_textdomain() {
            load_plugin_textdomain( WDFTEXTDOMAIN, false, dirname( __FILE__ ) . "/languages" );
        }

    }
}

function wdfe_load_plugin() {
    WDF_Elementor::get_instance()->load();
}

wdfe_load_plugin();