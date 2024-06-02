<div class="glossymm-fieldset mmwidth">
    <label for="#">Menu Width:</label>
    <?php 
    
        $glossymm_item_settings_width_options = [
                'default_width' => esc_html__('Default Width',"glossy-mega-menu"),
                'full_width' => esc_html__('Full Width',"glossy-mega-menu"),
                'custom_width' => esc_html__('Custom Width',"glossy-mega-menu"),
        ];

        $glossymm_item_settings_position_options = [
            'default' => esc_html__('Default',"glossy-mega-menu"),
            'relative' => esc_html__('Relative',"glossy-mega-menu"),
        ];
    ?>
    <select name="glossymm-mmwidth" id="glossymm-mmwidth">
        <?php 
            $glossymm_mmwidth = isset($data['glossymm_mmwidth']) ? $data['glossymm_mmwidth'] : '';
            foreach($glossymm_item_settings_width_options as $key => $gmm_with_options){
                if($glossymm_mmwidth == $key){
                    printf('<option value="%s" selected>%s</option>',$key,$gmm_with_options);
                }else{
                    printf('<option value="%s">%s</option>',$key,$gmm_with_options);
                }              
            }
        ?>
    </select>
</div>
<?php 
    $custom_width = isset($data['glossymm_custom_width']) ? intval($data['glossymm_custom_width']) : '';
    $custom_width_class = $glossymm_mmwidth == "custom_width" ? 'glossymm-d-block' : 'glossymm-d-none';
?>
<div class="glossymm-fieldset mmcustom_width <?php esc_html_e($custom_width_class); ?>">
    <label for="#">Custom Width:</label>
    <input type="text" name="glossymm_custom_width" placeholder="<?php esc_html_e("700px"); ?>" value="<?php esc_html_e($custom_width); ?>" id="glossymm_custom_width">
</div>

<div class="glossymm-fieldset mmposition">
    <label for="#">Menu Position:</label>
    <select name="glossymm-mmposition" id="glossymm-mmposition">
    <?php 
            $glossymm_mmposition = isset($data['glossymm_mmposition']) ? $data['glossymm_mmposition'] : '';
            foreach($glossymm_item_settings_position_options as $key => $position_options){
                if($glossymm_mmposition == $key){
                    printf('<option value="%s" selected>%s</option>',$key,$position_options);
                }else{
                    printf('<option value="%s">%s</option>',$key,$position_options);
                }              
            }
        ?>     
    </select>
</div>

