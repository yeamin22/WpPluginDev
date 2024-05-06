<?php
/*
 * Plugin Option Panel
 */
if ( !defined( 'ABSPATH' ) ) {die( "Don't try this" );};
if ( !class_exists( "Ewform_OptionPanel" ) ) {
    class Ewform_OptionPanel {
        function __construct() {
            add_action( "admin_menu", [$this, "ewf_display_option_panel"] );
            add_action( 'admin_menu', [$this, "modify_parent_submenu_text"] );
        }
        /**
         * @return void
         */
        function ewf_display_option_panel() {
            add_menu_page(
                __( 'Easy Website Form', 'easywebsiteform' ),
                __( 'Easy Website Form', 'easywebsiteform' ),
                'manage_options',
                'ew_forms',
                [$this, 'ewf_display_forms_callback'],
                EWFORM_URL . 'assets/img/favicon.png',
                6
            );
            add_submenu_page(
                'ew_forms',
                __( 'API Setup', 'easywebsiteform' ),
                __( 'API Setup', 'easywebsiteform' ),
                'manage_options',
                'ewfoption',
                [$this, 'ewf_display_callback_func']
            );
        }
        /**
         * @return void
         */
        function ewf_display_callback_func() {
            require_once EWFORM_PATH . "/template/ewform_display_setup_html.php";
        }

        /**
         * @return void
         */
        function ewf_display_forms_callback() {
            require_once EWFORM_PATH . "/template/ewforms_display_table_html.php";
        }

        /**
         * @return void
         */
        function modify_parent_submenu_text() {
            global $submenu;
            $submenu['ew_forms'][0][0] = __( 'All Forms', 'easywebsiteform' );
        }
    }
}
new Ewform_OptionPanel;