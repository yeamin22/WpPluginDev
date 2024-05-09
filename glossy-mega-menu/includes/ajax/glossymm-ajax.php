<?php 

use GlossyMM\Utils;

/* All of ajax action */

function func_glossymm_saving_item_settings(){
    $res  = [];
    check_ajax_referer( 'security_nonce', 'security' );

    $formData = isset($_POST['formData']) ? $_POST['formData'] : "";
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : "";

    $res['formdata'] = $formData;
    $res['item_id'] = $item_id;
    Utils::save_option("item_settings_$item_id", $formData);
    wp_send_json($res);


}
add_action("wp_ajax_glossymm_saving_item_settings","func_glossymm_saving_item_settings");



function func_glossymm_get_item_settings(){
    $res  = [];
    check_ajax_referer( 'security_nonce', 'security' );
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : ""; 
    $saved_data = Utils::get_option("item_settings_$item_id");
    $res['saved_data'] = $saved_data;

    wp_send_json($res);

}

add_action("wp_ajax_glossymm_get_item_settings","func_glossymm_get_item_settings");


