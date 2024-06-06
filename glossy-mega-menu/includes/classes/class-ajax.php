<?php
namespace GlossyMM;
defined( 'ABSPATH' ) || exit;
use GlossyMM\Utils;

class Ajax {

    public function __construct() {
        add_action( "wp_ajax_glossymm_saving_item_settings", [$this, "func_glossymm_saving_item_settings"] );
        add_action( "wp_ajax_glossymm_get_item_settings", [$this, "func_glossymm_get_item_settings"] );
        add_action( "wp_ajax_glossymm_save_the_menuid", [$this, "func_glossymm_save_the_menuid"] );

    }

    public function func_glossymm_saving_item_settings() {
        $res = [];
        check_ajax_referer( 'security_nonce', 'security' );

        $formData = isset( $_POST['formData'] ) ? $_POST['formData'] : "";
        $item_id = isset( $_POST['item_id'] ) ? intval( $_POST['item_id'] ) : "";

        $res['formdata'] = $formData;
        $res['item_id'] = $item_id;
        Utils::save_option( "item_settings_$item_id", $formData );
        wp_send_json( $res );

    }

    public function func_glossymm_get_item_settings() {
        $res = [];
        check_ajax_referer( 'security_nonce', 'security' );
        $item_id = isset( $_POST['item_id'] ) ? intval( $_POST['item_id'] ) : "";
        $data = Utils::get_option( "item_settings_$item_id" );
        ob_start();
        echo '<div class="glossymm-tabpanel active" id="glossymm-pupup-content">';
        glossymm_get_view( "popup-content/content", $data );
        echo '</div>';
        echo '<div class="glossymm-tabpanel" id="glossymm-pupup-icon">';
        glossymm_get_view( "popup-content/icon" );
        echo '</div>';
        echo '<div class="glossymm-tabpanel" id="glossymm-pupup-settings">';
        glossymm_get_view( "popup-content/settings", $data );
        echo '</div>';
        $res['item_settings_withhtml'] = ob_get_clean();

        $res['data'] = $data;

        wp_send_json( $res );

    }
    public function func_glossymm_save_the_menuid() {
        check_ajax_referer( 'security_nonce', 'security' );
        $enabled = isset( $_POST['enabled'] ) ? intval( $_POST['enabled'] ) : "";
        $menuId = isset( $_POST['menuId'] ) ? intval( $_POST['menuId'] ) : "";
        if ( empty( $menuId ) ) {
            wp_send_json_error( ['msg' => "Semething is wrong, please check the menuid"] );
        }
        $data = Utils::get_option( "megamenu_settings", [] );
        $data['menu_location_' . $menuId] = [ 'is_enabled' => $enabled ];
        Utils::save_option( "megamenu_settings", $data );
        wp_send_json_success( ['msg' => "Menu Updated"] );

    }

}
