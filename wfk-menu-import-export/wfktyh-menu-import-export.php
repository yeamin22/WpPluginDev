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
Text Domain: wfktyh-menu-import-export
Domain Path: /languages
*/


// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {  
    die("Don't try this");
}
/**
 * WFKTYH_MENU_IMPORT_EXPORT class
 *
 * @class WFKTYH_MENU_IMPORT_EXPORT The class that holds the entire WFKTYH_MENU_IMPORT_EXPORT plugin
 */
final class WFKTYH_MENU_IMPORT_EXPORT {

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
     * Constructor for the WFKTYH_MENU_IMPORT_EXPORT class
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
     * Initializes the WFKTYH_MENU_IMPORT_EXPORT() class
     *
     * Checks for an existing WFKTYH_MENU_IMPORT_EXPORT() instance
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
        define( 'WFKTYH_MENU_IMPORT_EXPORT_VERSION', $this->version );
        define( 'WFKTYH_MENU_IMPORT_EXPORT_FILE', __FILE__ );
        define( 'WFKTYH_MENU_IMPORT_EXPORT_PATH', dirname( WFKTYH_MENU_IMPORT_EXPORT_FILE ) );
        define( 'WFKTYH_MENU_IMPORT_EXPORT_INCLUDES', WFKTYH_MENU_IMPORT_EXPORT_PATH . '/includes' );
        define( 'WFKTYH_MENU_IMPORT_EXPORT_URL', plugins_url( '', WFKTYH_MENU_IMPORT_EXPORT_FILE ) );
        define( 'WFKTYH_MENU_IMPORT_EXPORT_ASSETS', WFKTYH_MENU_IMPORT_EXPORT_URL . '/assets' );
        define( 'WFKTYH_MENU_IMPORT_EXPORT_TEMPLATE', WFKTYH_MENU_IMPORT_EXPORT_PATH . '/templates' );
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
        $installed = get_option( 'wfktyh_menu_import_export' );

        if ( ! $installed ) {
            update_option( 'wfktyh_menu_import_export', time() );
        }

        update_option( 'wfktyh_menu_import_export_version', WFKTYH_MENU_IMPORT_EXPORT_VERSION );
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

        require_once WFKTYH_MENU_IMPORT_EXPORT_INCLUDES . '/Assets.php';

        require_once WFKTYH_MENU_IMPORT_EXPORT_INCLUDES . '/Wfktyh_Mie_Ajax.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once WFKTYH_MENU_IMPORT_EXPORT_INCLUDES . '/Admin.php';
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
            $this->container['admin'] = new WFKTYH_MIE\Admin();     
        }
        if ( $this->is_request( 'ajax' ) ) {
             $this->container['ajax'] =  new WFKTYH_MIE\Wfktyh_Mie_Ajax();
        }
        
        $this->container['assets'] = new WFKTYH_MIE\Assets();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wfktyh-menu-import-export', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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

} // WFKTYH_MENU_IMPORT_EXPORT

WFKTYH_MENU_IMPORT_EXPORT::init();
