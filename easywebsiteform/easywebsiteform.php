<?php
/**
 * Plugin Name:       Easy Website Form
 * Plugin URI:        https://www.easywebsiteform.com/
 * Description:       Easy Website Form Companion Plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            SeoIdaho
 * Author URI:        https://www.seoidaho.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easywebsiteform
 * Domain Path:       /languages
 */

/**
* Exit if accessed directly
*/

if(!defined('ABSPATH') ) { die( "Don't try this" ); };

if(!class_exists("Ewform_Form")){
    class Ewform_Form {
        private static $instance = null;
        function __construct(){
            add_action("plugin_loaded", [$this, "init"]);
            add_action("admin_enqueue_scripts", [$this, "ewfoptions_admin_assets"]);  
            if (!get_transient('ewform_api_notice_show')) {
                add_action("ewform_notice_api", "ewform_api_key_not_set", 9999);
            }
        }
        /**
         * Singleton Instance
         * @return $instance
         */
        public static function Instance(){
            if (self::$instance == null) {
                self::$instance = new Ewform_Form();
            }
            return self::$instance;
        }

        /**
         * Initialization
         * @return void
         */
        function init()
        {
            // Fire on plugins load and ready the textdomain for the plugin.
            $this->ewform_load_textdomain();
            $this->defineConstants();
            // Fire on active the plugin
            register_activation_hook(__FILE__, [$this, "ewform_activation_callback"]);

            // Fire on deactivate the plugin
            register_deactivation_hook(__FILE__, [$this, "ewform_deactivation_callback"]);

            // Included Required Files
            require_once "admin/EWF_OptionPanel.php";
            require_once "includes/ewform_shortcode.php";
            require_once "includes/ewform_functions.php";
            require_once "includes/Forms_Tables.php";
            require_once "includes/elementor/EWF_Elementor.php";
        }

        /**
         * Define Constant
         * @return void
         */
        public function defineConstants(){      
                if(!defined("EWFORM_URL")){
                    define("EWFORM_URL", plugin_dir_url(__FILE__));
                }
                if(!defined("EWFORM_PATH")){
                    define("EWFORM_PATH", plugin_dir_path(__FILE__));
                }         
                if(!defined("EWFORM_API_URL")){                
                    define("EWFORM_API_URL", 'https://api.easywebsiteform.com/wp');
                }
                if(!defined("EWFORM_FRONTEND_URL")){
                    define("EWFORM_FRONTEND_URL", 'https://www.easywebsiteform.com');
                }
                if(!defined("EWFORM_APPS_URL")){
                    define("EWFORM_APPS_URL", 'https://apps.easywebsiteform.com');
                }
        }

        /**
         * @return void
         */
        function ewform_activation_callback()
        {
        }

        /**
         * @return void
         */
        function ewform_deactivation_callback()
        {
        }

        /**
         * Plugin Language file
         * @return void
         */
        function ewform_load_textdomain(){
            load_plugin_textdomain("easywebsiteform", false, dirname(__FILE__) . "/languages");
        }

        /**
         * Admin Assets Enquque
         * @param $screen
         * @return void
         */
        function ewfoptions_admin_assets($screen){
            if ("toplevel_page_ew_forms" == $screen || "easy-website-form_page_ewfoption" == $screen) {
                wp_enqueue_style("ewfoptions_style", EWFORM_URL . "assets/css/options-style.css", [], '1.0.0', "all");
                wp_enqueue_script("ewfoptions_script", EWFORM_URL . "assets/js/admin-js.js", ['jquery'], '1.0.0', true);
                $ajaxurl = admin_url("admin-ajax.php");
                $api_key = get_option('ewform_key') ? get_option('ewform_key') : '';
                $security_nonce = wp_create_nonce("security_nonce");
                $datas = [
                    'ajaxurl' => $ajaxurl, 
                    'api_key' => $api_key,
                    "security_nonce" => $security_nonce
                ];
                wp_localize_script("ewfoptions_script", "obj", $datas);
            }
        }  
    }
}
// Instantiate Class
$Ewform_Form =  Ewform_Form::Instance();