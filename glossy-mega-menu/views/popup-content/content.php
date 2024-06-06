
<div class="glossymm-settings-wrapper">  
    <div class="glossymm-d-flex glossymm-jstcont-between glossymm-alignitem-middle">
        <div class="menu-settings_switch">
            <div class="button-row-container">
                <div class="switch-container switch-ios">            
                    <input type="checkbox" class="glossymm-toggle-btn" <?php checked( ( isset( $data['item_is_enabled'] ) ? $data['item_is_enabled'] : '' ), '1' ); ?> name="item_is_enabled" id="glossymm_megamenu_item_enabled" value="1" />
                    <label for="glossymm_megamenu_item_enabled"></label>
                </div>
            </div>
            <span>Enabled Megamenu</span>
        </div>
        <div class="glossymm-edit-content">
           <div class="glossymm-edit-item">
                <a href="#" id="glossymm-builder-open"> <img src="<?php echo esc_url(GLOSSYMM_ADMIN_ASSETS . "/img/elementor-icon.png"); ?>" alt="" srcset=""> <span>Edit Content</span></a>
           </div> 
        </div>

    </div>


</div>

<?php /* checked( ( isset( $data['item_is_enabled'] ) ? $data['item_is_enabled'] : '' ), '1' ); */ ?>