jQuery(document).ready(function ($) {
    "use strict";

    // Default Object From Backend
    let {
        glossymm_enabled_options_template,
        menuitem_edit_popup_template,
        ajaxurl,
        security_nonce,
        ajax_loader
    } = obj;

    // Prepend Template from backend to nav menu page
    $("#post-body-content").prepend(glossymm_enabled_options_template);
    $("#post-body-content").prepend(menuitem_edit_popup_template);

    if ($('#glossymm_megamenu_enabled').is(":checked")) {
        appendMegaMenuEditLinks();
    }

    $("#glossymm_megamenu_enabled").on("change", function () {
        handleMegaMenuToggle($(this));
    });

    $("#post-body-content").on("change", "#glossymm_megamenu_enabled", function () {
        toggleMegaMenuClass($(this));
        if ($(this).is(":checked")) {
            appendMegaMenuEditLinks();
        } else {
            removeMegaMenuEditLinks();
        }
    });

    $("#menu-to-edit").on("click", ".glossymm_megamenu_trigger", function (e) {
        handleMegaMenuEditClick(e, this);
    });

    $(".glossymm_popup_tabs ul li").on("click", function () {
        handlePopupTabClick($(this));
    });

    $(".glossymm-close-popup").on("click", function () {
        closePopup();
    });

   

    $("#glossymm-save-item").on("click", function (e) {
        saveMenuItemSettings(e);
    });

    // Close popup on outside click
    $(document).on("click", function (event) {
        if (!$(event.target).closest(".glossymm_adminmenu_popup, .glossymm_megamenu_trigger").length) {
            closePopup();
        }
    });

    // Function to append Mega Menu Edit Links
    function appendMegaMenuEditLinks() {
        $("#menu-to-edit li.menu-item.menu-item-depth-0").each(function () {
            var t = $(this);
            t.append("<a href='#' class='glossymm_megamenu_trigger'>Edit Mega Menu <div class='ajax-loader'><img src='" + ajax_loader + "' alt=''></div></a>");
        });
    }

    // Function to handle Mega Menu Toggle
    function handleMegaMenuToggle(element) {
        let menuId = element.data("menuid");
        let enabled = element.is(":checked") ? 1 : 0;

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function () {
                element.attr("disabled", "disabled");
                $(".ajax-loader").show();
            },
            data: {
                action: "glossymm_save_the_menuid",
                security: security_nonce,
                enabled: enabled,
                menuId: menuId
            },
            complete: function () {
                element.removeAttr("disabled");
                $(".ajax-loader").hide();
            },
            error: function (xhr) {
                $('.button-row-container').html('Error: ' + xhr.statusText);
            }
        });
    }

    // Function to toggle Mega Menu Class
    function toggleMegaMenuClass(element) {
        element.is(":checked") ? $("body").addClass("is_mega_enabled").removeClass("is_mega_disabled") : $("body").removeClass("is_mega_enabled").addClass("is_mega_disabled");
    }

    // Function to remove Mega Menu Edit Links
    function removeMegaMenuEditLinks() {
        $("#menu-to-edit li.menu-item.menu-item-depth-0").each(function () {
            $(this).children('a.glossymm_megamenu_trigger').remove();
        });
    }

    // Function to handle Mega Menu Edit Click
    function handleMegaMenuEditClick(e, parentThis) {
        e.preventDefault();
        let menu_id = parseInt($(e.target).parents("li.menu-item.menu-item-depth-0").attr("id").match(/[0-9]+/)[0], 10);
        $("#glossymm-item-form").attr("data-item", menu_id);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function () {
                $(parentThis).children(".ajax-loader").show();
                $(".ajax_preloader").show();
            },
            data: {
                action: "glossymm_get_item_settings",
                security: security_nonce,
                item_id: menu_id
            },
            success: function (res) {              
                console.log(res);
                $("#glossymm-tab-content").html(res['item_settings_withhtml']);

                $(".glossymm_popup_overlaping").show();
                $(".glossymm_adminmenu_popup").show();

                $("#glossymm-mmwidth").on("change", function () {
                    console.log($(this));
                    toggleCustomWidth($(this));
                });
            },
            complete: function () {
                $(parentThis).children(".ajax-loader").hide();
                $(".ajax_preloader").hide();
            },
            error: function (xhr) {
                $('.glossymm_popup_overlaping').html('Error: ' + xhr.statusText);
            }
        });
    }

    // Function to handle Popup Tab Click
    function handlePopupTabClick(element) {
        $(".glossymm_popup_tabs ul li").removeClass("active");
        element.addClass("active");
        let tabId = element.data("tab");
        $(".glossymm-tabpanel").hide();
        $("#" + tabId).show();
    }

    // Function to close Popup
    function closePopup() {
        $(".glossymm_popup_tabs li").removeClass("active");
        $("[data-tab='glossymm-pupup-content']").addClass("active");
        $(".glossymm_adminmenu_popup").hide();
        $(".glossymm_popup_overlaping").hide();
    }

    // Function to toggle Custom Width
    function toggleCustomWidth(element) {
        console.log(element);
        if (element.val() == "custom_width") {
            $(".mmcustom_width").show();
        } else {
            $(".mmcustom_width").hide();
        }
    }

    // Function to save Menu Item Settings
    function saveMenuItemSettings(e) {
        e.preventDefault();
        let item_id = $("#glossymm-item-form").data("item");


        let enabled = $("#glossymm_megamenu_item_enabled").is(":checked") ? 1 : 0;
        let formData = {
            item_is_enabled: enabled,
            glossymm_custom_width: $('input[name="glossymm_custom_width"]').val(),
            glossymm_mmwidth: $('select[name="glossymm-mmwidth"]').val(),
            glossymm_mmposition: $('select[name="glossymm-mmposition"]').val()
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function () {
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
            complete: function () {
                $(".ajax_preloader").hide();
            },
            error: function (xhr) {
                $('.glossymm_popup_overlaping').html('Error: ' + xhr.statusText);
            }
        });
    }



});
