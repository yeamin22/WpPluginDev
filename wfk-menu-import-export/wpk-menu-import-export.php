<?php 
/*
Plugin Name: WFK Menu Import/Export
Plugin URI: https://wfk.shanto.studio/plugins/wfk-menu-import-export.zip
Description: Using this plugin you can export all of menu items and improt it easily
Version: 1.0.0
Author: YH SAJIB
Author URI: https://wfk.shanto.studio
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wfk-menu-import-export
Domain Path: /languages
*/


// don't call the file directly
if ( !defined( 'ABSPATH' ) ) {  
    die("Don't try this");
}
/**
 * WPK_MENU_IMPORT_EXPORT class
 *
 * @class WPK_MENU_IMPORT_EXPORT The class that holds the entire WPK_MENU_IMPORT_EXPORT plugin
 */
final class WPK_MENU_IMPORT_EXPORT {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.1.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the WPK_MENU_IMPORT_EXPORT class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        $this->define_constants();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes the WPK_MENU_IMPORT_EXPORT() class
     *
     * Checks for an existing WPK_MENU_IMPORT_EXPORT() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;
        if ( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WPK_MENU_IMPORT_EXPORT_VERSION', $this->version );
        define( 'WPK_MENU_IMPORT_EXPORT_FILE', __FILE__ );
        define( 'WPK_MENU_IMPORT_EXPORT_PATH', dirname( WPK_MENU_IMPORT_EXPORT_FILE ) );
        define( 'WPK_MENU_IMPORT_EXPORT_INCLUDES', WPK_MENU_IMPORT_EXPORT_PATH . '/includes' );
        define( 'WPK_MENU_IMPORT_EXPORT_URL', plugins_url( '', WPK_MENU_IMPORT_EXPORT_FILE ) );
        define( 'WPK_MENU_IMPORT_EXPORT_ASSETS', WPK_MENU_IMPORT_EXPORT_URL . '/assets' );
        define( 'WPK_MENU_IMPORT_EXPORT_TEMPLATE', WPK_MENU_IMPORT_EXPORT_PATH . '/templates' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {
        $installed = get_option( 'wfk_menu_import_export' );

        if ( ! $installed ) {
            update_option( 'wfk_menu_import_export', time() );
        }

        update_option( 'wfk_menu_import_export_version', WPK_MENU_IMPORT_EXPORT_VERSION );
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once WPK_MENU_IMPORT_EXPORT_INCLUDES . '/Assets.php';

        require_once WPK_MENU_IMPORT_EXPORT_INCLUDES . '/WFK_Mie_Ajax.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once WPK_MENU_IMPORT_EXPORT_INCLUDES . '/Admin.php';
        }
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        add_action( 'init', array( $this, 'init_classes' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {

        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new WFK_MIE\Admin();     
        }
        if ( $this->is_request( 'ajax' ) ) {
             $this->container['ajax'] =  new WFK_MIE\WFK_Mie_Ajax();
        }
        
        $this->container['assets'] = new WFK_MIE\Assets();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wfk-menu-import-export', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'ajax' :
                return defined( 'DOING_AJAX' );

            case 'rest' :
                return defined( 'REST_REQUEST' );

            case 'cron' :
                return defined( 'DOING_CRON' );

            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

} // WPK_MENU_IMPORT_EXPORT

WPK_MENU_IMPORT_EXPORT::init();
