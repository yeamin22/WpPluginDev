jQuery(document).ready(function($){
    "use strict"; 

    /* Default Object From Backend */
    let {glossymm_enabled_options_template,menuitem_edit_popup_template,ajaxurl,security_nonce,ajax_loader} = obj;

    /* Prepend Template from backend to nav menu page */
    $("#post-body-content").prepend(glossymm_enabled_options_template);
    $("#post-body-content").prepend(menuitem_edit_popup_template);

    if($('#glossymm_megamenu_enabled').is(":checked")){
        $("#menu-to-edit li.menu-item.menu-item-depth-0").each(function () {
            var t = $(this);
            t.append("<a href='#' class='glossymm_megamenu_trigger'>Edit Mega Menu <div class='ajax-loader'><img  src='"+ajax_loader+"' alt=''></div></a> ");
        });
    }

    $("#glossymm_megamenu_enabled").on("change",function(){    
        let menuId = $(this).data("menuid");
        let enabled = null;
        if($('#glossymm_megamenu_enabled').is(":checked")){ 
             enabled = 1;
        }else{        
            enabled = 0;
        }
       
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function(){
                $("#glossymm_megamenu_enabled").attr("disabled","disabled");
                $(".ajax-loader").show();
            },
            data: {
                action: "glossymm_save_the_menuid",
                security: security_nonce,
                enabled: enabled,               
                menuId: menuId,               
            },
            success: function(response) {              
                
            },
            complete: function(){
                $("#glossymm_megamenu_enabled").removeAttr("disabled");
                $(".ajax-loader").hide();
            },
            error: function(xhr) {
                $('.button-row-container').html('Error: ' + xhr.statusText);
            }
        });
    });

    $("#post-body-content").on("change", "#glossymm_megamenu_enabled", function () {
        $(this).is(":checked") ? $("body").addClass("is_mega_enabled").removeClass("is_mega_disabled") : $("body").removeClass("is_mega_enabled").addClass("is_mega_disabled");
        if($(this).is(":checked")){
            $("#menu-to-edit li.menu-item.menu-item-depth-0").each(function () {
                var t = $(this);
                t.append("<a href='#' class='glossymm_megamenu_trigger'>Edit Mega Menu <div class='ajax-loader'><img  src='"+ajax_loader+"' alt=''></div></a> ");
            });
        }else{          
            $("#menu-to-edit li.menu-item.menu-item-depth-0").each(function () {      
                $(this).children('a.glossymm_megamenu_trigger').remove();
            });
        }
    }),

    $("#menu-to-edit").on("click", ".glossymm_megamenu_trigger", function (e) {        
        e.preventDefault();   
        let parentThis = this;  
        let menu_id = parseInt($(e.target).parents("li.menu-item.menu-item-depth-0").attr("id").match(/[0-9]+/)[0], 10);
        $("#glossymm-item-form").attr("data-item",menu_id);     
       // Saving Item data
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function(){
                $(parentThis).children(".ajax-loader").show();
                $(".ajax_preloader").show();
            },
            data: {
                action: "glossymm_get_item_settings",
                security: security_nonce,
                item_id: menu_id,               
            },
            success: function(response) {
               console.log(response);
               let savedDatas = response['saved_data'];

               $("#glossymm_megamenu_item_enabled");
               $(".glossymm_popup_overlaping").show();
               $(".glossymm_adminmenu_popup").show();    
            },
            complete: function(){
                $(parentThis).children(".ajax-loader").hide();
                $(".ajax_preloader").hide();
            },
            error: function(xhr) {
                $('.glossymm_popup_overlaping').html('Error: ' + xhr.statusText);
            }
        });
    });

    /* Admin Popup Tabs */
    $(".glossymm_popup_tabs ul li").on("click",function(){   
        $(".glossymm_popup_tabs ul li").removeClass("active");
        $(this).addClass("active");
        let tabId = $(this).data("tab");
        $(".glossymm-tabpanel").hide();
        $("#"+tabId).show();

    });

    /* Close popup */
    $(".glossymm-close-popup").on("click",function(){
        $(".glossymm_adminmenu_popup").hide();
        $(".glossymm_popup_overlaping").hide();
    });

    /* Tabs Settings Width On Change */
    $("#glossymm-mmwidth").on("change",function(){
        if($(this).val() == "custom_width"){
            $(".mmcustom_width").show();
        }else{
            $(".mmcustom_width").hide();
        }
    }); 

    /* 
    Saving Indivitual Menu Item settings
    */
    $("#glossymm-save-item").on("click",function(e){
        e.preventDefault();  
        let item_id = $("#glossymm-item-form").data("item");
        // Using the attribute selector to get values
        var item_is_enabled = $('input[name="item_is_enabled"]').val();
        var glossymm_custom_width = $('input[name="glossymm_custom_width"]').val();
        var glossymm_mmwidth = $('select[name="glossymm-mmwidth"]').val();
        var glossymm_mmposition = $('select[name="glossymm-mmposition"]').val();
        
        let formData =  {
            item_is_enabled: item_is_enabled,
            glossymm_custom_width: glossymm_custom_width,
            glossymm_mmwidth: glossymm_mmwidth,
            glossymm_mmposition: glossymm_mmposition,
        };
        console.log(formData);       
        // Saving Item data
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function(){
                $(".ajax_preloader").show();
            },
            data: {
                action: "glossymm_saving_item_settings",
                security: security_nonce,
                item_id: item_id,
                formData: formData
            },
            success: function(response) {
               console.log(response);
            },
            complete: function(){
                $(".ajax_preloader").hide();
            },
            error: function(xhr) {
                $('.glossymm_popup_overlaping').html('Error: ' + xhr.statusText);
            }
        });

    });

    //Ready Function End
});