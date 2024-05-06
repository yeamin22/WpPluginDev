<?php
/**
 *  EWForm Setup Page
 */
if(!defined('ABSPATH') ) { die( "Don't try this" ); };
$api_key = get_option("ewform_key") ? get_option("ewform_key") : '';
$activated = ($api_key != '' && !empty($api_key)) ? "Activated" : "Deactivated";
$activeClass = ($activated == 'Deactivated') ? "deactivated" : '';
?>
<section id="ewf_option">
    <header class="ewf_header">
        <div class="logo">
            <img src="<?php echo esc_url(EWFORM_URL); ?>assets/img/logo.png" width="400px" alt="">
        </div>
    </header>
    <div class="option_body">
        <div class="ewf-main-column">
            <div class="ewf_alert"></div>
            <form action="#" method="post">
                <div class="input-group">
                    <label for="ewf_apikey"><?php echo esc_html__("API Key", "easywebsiteform"); ?></label>            
                    <input value="<?php echo ewform_get_ewform_api_key_display($api_key); ?>" <?php echo !empty($api_key) ? "readonly" : ''; ?>
                        class="form-control" type="text" name="ewf_apikey" id="ewf_apikey" required="required"
                        placeholder="Enter API Key">
                </div>
                <div class="footer_btn">
                    <div class="activated_status">
                        <button disabled class="activated_text <?php echo esc_attr($activeClass); ?>">
                            <?php  printf("%s",esc_html__($activated,"easywebsiteform")); ?>
                        </button>
                    </div>
                    <?php
                    if (!empty($api_key)) {
                        ?>
                    <div class="btn-reset">
                        <button id="reset_key"><?php echo esc_html__("Reset", "easywebsiteform"); ?></button>
                    </div>
                    <?php
                    } else {
                        ?>
                    <div class="btn-submit">
                        <button id="save_key"><?php echo esc_html__("Save", "easywebsiteform"); ?></button>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </form>
        </div>
        <div class="instruction_api_key">
            <p>
                <?php printf("<a href='%s/api-key' target='_blank' > %s </a> %s <b>%s</b>  %s",
                        esc_url(EWFORM_APPS_URL),
                        esc_html__("Click Here","easywebsiteform"),
                        esc_html__("to get your API Key from","easywebsiteform"),
                        esc_html__("Easy Website Form","easywebsiteform"),
                        esc_html__("after login.","easywebsiteform"),                    
                    ); 
                    ?>               
            </p>
            <div class="img_wrapper">
                <img src="<?php echo esc_url(EWFORM_URL); ?>assets/img/API_key.png" height="300px" alt="">
            </div>
        </div>
    </div>
</section>