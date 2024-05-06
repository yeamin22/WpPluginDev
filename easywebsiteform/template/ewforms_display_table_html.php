<?php
/**
 *  EWForm All Forms Page
 */
if(!defined('ABSPATH') ) { die( "Don't try this" ); };
?>
<section id="ewf_option">
    <div class="ew_forms_wrapper">
        <header class="ewf_header">
            <div class="logo">
                <img src="<?php echo esc_url(EWFORM_URL); ?>assets/img/logo.png" width="400px" alt="">
            </div>
        </header>
        <?php
        $api_key = get_option('ewform_key') ? get_option('ewform_key') : '';
        if ($api_key == '' || empty($api_key)) {
            do_action("ewform_notice_api");
            return;
        }
        ?>
        <div class="ewf_form_table">
            <div class="ewf-api-info" id="forms_data">
                <div class="table_header">
                    <h2><?php echo esc_html__("All Forms",'easywebsiteform'); ?></h2>
                </div>
                <?php
                $form_tables = new Ewform_Tables();
                $form_tables->prepare_items();
                $form_tables->display();
                ?>
            </div>
        </div>
    </div>
</section>