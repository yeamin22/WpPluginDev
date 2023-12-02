
jQuery(document).ready(function(){ 
    let {ajax_url,msg,security} = obj;
    // Tab Functionality
    jQuery(".tabs-btn li a").each(function(){
        jQuery(this).click(function (evt){
            let contentTabId = jQuery(this).data("tab");
            var tabs = document.getElementsByClassName('mfk-mie_tabcontent');
            for (var i = 0; i < tabs.length; i++) {
              tabs[i].style.display = 'none';
            }
            var tabButtons = document.getElementsByClassName('tab-button');  
            for (var i = 0; i < tabButtons.length; i++) {
              tabButtons[i].classList.remove('active');
            }
            document.getElementById(contentTabId).style.display = 'block';         
            jQuery(this).parent().addClass("active");            
            evt.preventDefault();
        });

    });
    //get menus by ajax
    jQuery("#select-menu-import-export").change(function(){ 
        jQuery("#wfk_menu_export_btn").removeAttr("disabled");
        jQuery("#wfk_menu_export_btn").attr("data-menu_id",jQuery(this).val());
        let menuName = (jQuery.trim(jQuery(this).find("option:selected").text()).toLowerCase()).replace(/ /g, '-');       
        jQuery("#wfk_menu_export_btn").attr("data-menuname",menuName);
        jQuery.ajax({
            type: "POST",
            url: ajax_url,
            data: {
                action: "select_menu_import_export",
                menuId: jQuery(this).val()
            },
            success: function({data}){
                jQuery("#export-menu_item").html(data);               
            },
            error: function(err){
                console.log(err);
            }
            
        });
    })

    /* Download Menu Export File  */
    jQuery("#wfk_menu_export_btn").click(function(event){
         if(jQuery("#select-menu-import-export").val() == "" || jQuery("#select-menu-import-export").val()== null ){
            alert(msg.selectMenu);
         }else{
            let menuName = jQuery("#wfk_menu_export_btn").data("menuname");
            jQuery.ajax({
                type: "POST",
                url: ajax_url,
                data: {
                    action: "wfk_download_export_file",                 
                    menu_id: jQuery(this).data("menu_id")
                },
                success: function(response){
                    // Create a Blob with the JSON data
                    let time = new Date().getTime();
                    let newVal = JSON.stringify(response);
                    var blob = new Blob([newVal], { type: 'application/json' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);

                    link.download = `wp_menus_${menuName}_${time}.json`;
                    link.click();                   
                },
                error: function(err){
                    console.log(err);
                }
                })
            }
        event.preventDefault();
    });

    /* MIE Import Functionality */

        // Trigger file input box
        jQuery(".chooseMenuFile").click(function(e){ 
            e.preventDefault(); 
            jQuery("#menuJsonFile").trigger("click");
        });    
        //File uploading changes
        jQuery("#menuJsonFile").change(function(e){            
            var fileName = e.target.files[0].name;
            jQuery("#fileHeaderText h4").text(fileName);
            jQuery(".chooseMenuFile").hide();
            let filedata = `<input type="text" id="wfk_mie_menuname" name="wfk_mie_menuname" placeholder="Enter your menu name">`;
            jQuery(".menu_name_input").html(filedata);
        });    


    // Submit functionality
    jQuery("#wfk_mie_import_form").submit(function(e){
        e.preventDefault();      
     
        var file = document.getElementById("menuJsonFile");
        var menuName = document.getElementById("wfk_mie_menuname");   
        if(file.files[0] == undefined){
            alert(msg.fileMissing);
            return;
        }
        if( undefined != menuName){
            if('' == menuName.value){
                alert(msg.missingMenu);
                return;
            }
        }
        var formData = new FormData();
        formData.append('action', 'wfk_import_file');
        formData.append('menu_file', file.files[0]);
        formData.append('menuName', menuName.value);
         /* Ajax Called */
         jQuery.ajax({
            type: "POST",
            url: obj.ajax_url,
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(res){
                if(res.status == false){
                    jQuery(".warning").text(res.data);
                }
                if(res.success == true){                    
                    window.location.href = res.data;
                }
            },
            error: function(jqXHR, textStatus, errorThrown){

                console.log(jqXHR);
                console.log(textStatus);
                console.log(jqXHR.responseText);
            }
         });  
         //Ajax End
    });
    

});


