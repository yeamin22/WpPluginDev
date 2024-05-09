<?php
/**
 * Plugin Name:       Glossy Mega Menu
 * Plugin URI:        https://www.glossyit.com/
 * Description:       Create sturning Mega menu using elementor
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            GlossyIt
 * Author URI:        https://www.glossyit.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       glossy-mega-menu
 * Domain Path:       /languages
 */

/**
 * Exit if accessed directly
 */

if ( !defined( 'ABSPATH' ) ) {die( "Don't try this" );}

if ( !class_exists( "GlossyMM" ) ) {

    final class GlossyMM {

        /**
         * Instance Of GlossyMM
         *
         * @var [instance]
         */
        private static $instance = null;

        /**
         * Construct of GlossyMM
         */
        function __construct() {
            add_action( "plugin_loaded", [$this, "init"] );

            if ( is_admin() ) {

                //  add_action("admin_footer",[$this,'megamenu_options_infooter']);
                add_action( "admin_enqueue_scripts", [$this, "glossymm_admin_assets"] );
            }

        }

        /**
         * Singleton Instance
         * @return $instance
         */
        public static function Instance() {
            if ( self::$instance == null ) {
                self::$instance = new GlossyMM();
            }
            return self::$instance;
        }

        /**
         * Initialization
         * @return void
         */
        public function init() {
            // Fire on plugins load and ready the textdomain for the plugin.
            $this->glossymm_load_textdomain();
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
         * Define Constant
         * @return void
         */

        public function defineConstants() {
            if ( !defined( "GLOSSYMM_URL" ) ) {
                define( "GLOSSYMM_URL", plugin_dir_url( __FILE__ ) );
            }
            if ( !defined( "GLOSSYMM_PATH" ) ) {
                define( "GLOSSYMM_PATH", plugin_dir_path( __FILE__ ) );
            }
            if ( !defined( "GLOSSYMM_ADMIN_ASSETS" ) ) {
                define( "GLOSSYMM_ADMIN_ASSETS", plugin_dir_url( __FILE__ ) . "/assets/admin" );
            }

        }

        /**
         * Included Required Files
         */

         public function includeFiles() {
      
            require_once GLOSSYMM_PATH. "/includes/helper-functions.php";
            require_once GLOSSYMM_PATH. "/includes/ajax/glossymm-ajax.php";
            if ( is_admin() ) {
                require_once GLOSSYMM_PATH . "/includes/utils.php";
                require_once GLOSSYMM_PATH . "/includes/options.php";
            }

        }
        /**
         * @return void
         */
        function glossymm_activation_callback() {
        }

        /**
         * @return void
         */
        function glossymm_deactivation_callback() {
        }

        /**
         * Plugin Language file
         * @return void
         */
        public function glossymm_load_textdomain() {
            load_plugin_textdomain( "glossy-mega-menu", false, dirname( __FILE__ ) . "/languages" );
        }

        /**
         * Admin Assets Enquque
         * @param $screen
         * @return void
         */
        public function glossymm_admin_assets( $screen ) {
            if ( "nav-menus.php" === $screen ) { // Only for nav-menus.php page
                //admin style enqueue
                wp_enqueue_style( "glossymm-admin-style", GLOSSYMM_ADMIN_ASSETS . "/css/glossymm-style.css" );
                //admin script enqueue
                wp_enqueue_script( "glossymm-admin-scripts", GLOSSYMM_ADMIN_ASSETS . "/js/admin-scripts.js", ['jquery'], time(), true );
                $ajaxurl = admin_url( "admin-ajax.php" );
                $security_nonce = wp_create_nonce( "security_nonce" );
                $enable_option = $this->megamenu_options_infooter();
                $menuitem_edit_popup = $this->glossymm_admin_popup_content();
                $datas = [
                    'ajaxurl'                           => $ajaxurl,
                    "security_nonce"                    => $security_nonce,
                    "glossymm_enabled_options_template" => $enable_option,
                    "menuitem_edit_popup_template"      => $menuitem_edit_popup,
                ];
                wp_localize_script( "glossymm-admin-scripts", "obj", $datas );
            }
        }

        public function megamenu_options_infooter() {
            $screen = get_current_screen();
            if ( $screen->base != 'nav-menus' ) {
                return;
            }
            $options = new GlossyMM\Options();
            $menu_id = $options->current_menu_id();
            $data = GlossyMM\Utils::get_option( "megamenu_settings" );
            $data = ( isset( $data['menu_location_' . $menu_id] ) ) ? $data['menu_location_' . $menu_id] : [];
            ob_start();
            ?>
                <div class="glossymm_enable_option_wrap">
                    <h5 class="enabled-groupname"><?php esc_html_e( 'Glossy Mega Menu', 'glossy-mega-menu' );?></h5>
                    <div class="menu-settings_switch">
                        <div class="button-row-container">
                            <div class="switch-container switch-ios">
                                <input type="checkbox" class="glossymm-toggle-btn" name="is_enabled" <?php checked( ( isset( $data['is_enabled'] ) ? $data['is_enabled'] : '' ), '1' );?> id="glossymm_megamenu_enabled" value="1"/>
                                <label for="glossymm_megamenu_enabled"></label>
                            </div>
                        </div>
                    </div>
                    <p class="notice notice-warning glossymm-notice"> <?php esc_html_e( 'Enable Megamenu for this menu', 'glossy-mega-menu' );?></p>
                </div>
                <?php
            return ob_get_clean();
           
        }

        /**
         * Menu Settings Popup
         *
         * @return void
         */
        private function glossymm_admin_popup_content() {
      
            ob_start();
            ?>
                <div class="glossymm_popup_overlaping"></div>
                <div class="glossymm_adminmenu_popup">
                    <div class="ajax_preloader"><img src="<?php echo admin_url("images/spinner-2x.gif"); ?>" alt="" srcset=""></div>
                        <button class="glossymm-close-popup" type="button" data-dismiss="modal">
							<svg width="14" height="14" viewBox="0 0 14 14" xmlns="https://www.w3.org/2000/svg">
								<line fill="none" stroke="#000" stroke-width="1.1" x1="1" y1="1" x2="13" y2="13"></line>
								<line fill="none" stroke="#000" stroke-width="1.1" x1="13" y1="1" x2="1" y2="13"></line>
							</svg>
						</button>
                    <div class="glossymm_popup_tabs round">
                        <ul>
                            <li data-tab="glossymm-pupup-settings"><?php esc_html_e("Settings",'glossy-mega-menu') ?></li>
                            <li data-tab="glossymm-pupup-icon"><?php esc_html_e("Icon",'glossy-mega-menu') ?></li>
                            <li class="active" data-tab="glossymm-pupup-content"><?php esc_html_e("Content",'glossy-mega-menu') ?> </li>
                        </ul>
                    </div>
                    <form id="glossymm-item-form"  method="post">
                        <div class="glossymm-tab-content">
                                <div class="glossymm-tabpanel active" id="glossymm-pupup-content">
                                    <?php                                       
                                        glossymm_get_view("popup-content/content");
                                    ?>
                                </div>
                                <div class="glossymm-tabpanel" id="glossymm-pupup-icon">
                                    <?php                             
                                        glossymm_get_view("popup-content/icon");
                                    ?>
                                </div>
                                <div class="glossymm-tabpanel" id="glossymm-pupup-settings">
                                    <?php                                 
                                        glossymm_get_view("popup-content/settings");
                                    ?>
                                </div>    
                        </div>
                    <div class="glossymm-popup-footer">
                        <div class="popup-footer-btn">                   
                            <a class="glossymm-popup-savebtn" type="submit" href="" id="glossymm-save-item">Save</a>
                        </div>
                    </div>
                    </form>
                </div>
                <?php
            return ob_get_clean();

        }

    }

}
// Instantiate Class
GlossyMM::Instance();