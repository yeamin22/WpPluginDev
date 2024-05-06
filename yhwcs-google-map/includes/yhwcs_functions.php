<?php
/*
 * Functions For Easy Website Form Plugin
 * Ajax Request to Check API KEY AND SAVE IT
 */

if(!defined('ABSPATH') ) { die( "Don't try this" ); };

/**
 * @param $key
 * @return array|mixed|WP_Error
 */
function get_ewform_datas($key)
{
    $args = [
        'headers' => [
            "x-api-key" => $key,
        ],
    ];
    $response = wp_remote_get(EWFORM_API_URL . '/forms', $args);
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        $response['message'] = __("Something went wrong:","easywebsiteform") . $error_message;
        return $response;
    } else {
        wp_remote_retrieve_response_code($response);

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}

/**
 * @return void
 */
function ewform_save_api_key()
{
    $response = [];
    check_ajax_referer('security_nonce', 'security');    
    if(!current_user_can("manage_options")){
        $response['message'] = __("You are not allowed to save API Key, Please check permission","easywebsiteform");
        wp_send_json($response);
    }
    $key = $_POST['key'] ? $_POST['key'] : '';
    $formData = get_ewform_datas($key);

    if ($formData && $formData['data']) {
        if (get_option("ewform_key") != $key) {
            update_option("ewform_key", $key);
        }
        $response['data'] = $formData;
    } else {
        delete_option('ewform_key');
        $response['message'] = "Something went wrong";
    }
    wp_send_json($response);
}

add_action("wp_ajax_check_save_api", 'ewform_save_api_key'); // Ajax Saving Api Key

/**
 * @return void
 */
function ewform_reset_api_key_func()
{
    $res = [];
    check_ajax_referer('security_nonce', 'security');    
    if(!current_user_can("manage_options")){
        $res['message'] = __("You are not allowed to delete API key, Please check permission","easywebsiteform");
        wp_send_json($res);
    }
    delete_option("ewform_key");
    $res['message'] = __("Reset Successful","easywebsiteform");
    wp_send_json($res);
}

add_action("wp_ajax_reset_api_key", "ewform_reset_api_key_func"); // AJax Reseting Api Key

/**
 * @return void
 */
function ewform_hide_notice_for_a_day_func()
{
    check_ajax_referer('security_nonce', 'security');        
    set_transient("ewf_hide_notice", true, 60 * 60 * 24);
    wp_send_json_success(['success' => true]);
}

add_action("wp_ajax_hide_notice_for_a_day", "ewform_hide_notice_for_a_day_func"); // AJax Hide Notice For a time.

/**
 * @param $string
 * @return string|void
 */
function get_ewform_api_key_display($string){
    if (!empty($string)) {
        $first = substr($string, 0, 10);
        $last = substr($string, -10);
        $middle = str_repeat("*", 40);
        return $first . $middle . $last;
    }
}

/**
 * @return void
 */
function ewform_api_key_not_set()
{
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php echo wp_kses('Please activate your account with <b>Easy Website Form</b> api key! <a href="admin.php?page=ewfoption"><b>Click Here for API Setup</b></a>', "easywebsiteform",array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'b' => array(),
            'em' => array(),
            'strong' => array(),
        ) ); ?>
        </p>
    </div>
    <?php
}