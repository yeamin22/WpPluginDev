<?php
namespace WFKTYH_MIE;

/**
 * Admin Pages Handler
 */
class Admin {

    public function __construct() {
        add_action( 'admin_menu', [$this, 'admin_menu'] );
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu() {     

        $capability = 'manage_options';
        $slug = 'wfktyh-menu-import-export';

            $hook = add_submenu_page("themes.php",__( 'Menu Import/Export', 'wfktyh-menu-import-export' ), __( 'Menu Import/Export', 'wfktyh-menu-import-export' ), $capability, $slug, [$this, 'plugin_page']);
            add_action( 'load-' . $hook, [$this, 'init_hooks'] );
          
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'wfp-mie-style' );
        wp_enqueue_script( 'wfp-mie-main-admin' );
       $nonce = wp_create_nonce("security");
        wp_localize_script('wfp-mie-main-admin','obj',['ajax_url'=> admin_url("admin-ajax.php"),'security' =>  $nonce,'msg' => $this->TranlateAbleMessage()]);
    }

    /**
     * Render our admin page
     *
     * @return void
     */
    public function plugin_page() {        
        include_once WFKTYH_MENU_IMPORT_EXPORT_TEMPLATE . "/page-wfktyh-menu-import-export.php";
    }

    public function TranlateAbleMessage(){

        $msg = [
            'fileMissing' => __("Please select a menu file!",  'wfktyh-menu-import-export' ),
            'missingMenu' => __("Please give a menu name!",  'wfktyh-menu-import-export' ),
            'selectMenu' => __("Please select menu!",  'wfktyh-menu-import-export' ),
            'menuNameInput' => __("Enter your menu name.",  'wfktyh-menu-import-export' ),
        ];

        return $msg;

    }
}