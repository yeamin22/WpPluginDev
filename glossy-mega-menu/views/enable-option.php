<?php 
    ob_start();
?>
<div class="glossymm_enable_option_wrap">
    <h5 class="enabled-groupname">Glossy Megamenu</h5>
    <div class="menu-settings_switch">
        <div class="button-row-container">
            <div class="switch-container switch-ios">
                <input type="checkbox" name="ios" id="ios" />
                <label for="ios"> Mega Menu</label>
            </div>
        </div>
    </div>
</div>

<?php 

return ob_get_clean();
?>