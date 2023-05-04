<?php
/**
 * Plugin Name:       Yh Migrate
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            YH Sajib
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       yhmigrate
 * Domain Path:       /languages
 */

/**
 * Exit if accessed directly
 */
defined( 'ABSPATH' ) || exit;

define( "TEXTDOMAIN", "yhmigrate" );

class YHmigrate {

    function __construct() {
        add_action( "plugin_loaded", array( $this, "init" ) );
    }
    // Class Init Action Method
    function init() {
        // Fire on plugins load and ready the textdomain for the plugin.
        $this->yhmigrate_load_textdomain();
        // Fire on active the plugin
        register_activation_hook( __FILE__, array( $this, "yhmigrate_activation_callback" ) );
        // Fire on deactive the plugin
        register_deactivation_hook( __FILE__, array( $this, "yhmigrate_deactivation_callback" ) );

        require_once "includes/YHOptions.php";
    }

    function yhmigrate_activation_callback() {

    }
    function yhmigrate_deactivation_callback() {

    }
    function yhmigrate_load_textdomain() {
        load_plugin_textdomain( TEXTDOMAIN, false, dirname( __FILE__ ) . "/languages" );
    }

}
// Instantiate Class
$yhmigrate = new YHmigrate();