jQuery(document).ready(function ($) {
    /* Save API Key in the database */
    $("#save_key").on("click", function (event) {      
        event.preventDefault();
        let apiKey = $("#ewf_apikey").val();
        if (apiKey === "" || apiKey === null) {
            $(".ewf_alert").text("Must Not Be Empty");
        } else {
            $(".ewf_alert").text("");
            $.ajax({
                url: obj.ajaxurl,
                type: "POST",
                data: { action: "check_save_api", key: apiKey, security: obj.security_nonce},
                dataType: "json",
                beforeSend: () => {
                    $("#save_key").text("Saving...");
                },
                success: ({ data, message }) => {
                    console.log(data);
                    if (data === null) {
                        $(".ewf_alert").html(message);
                        $("#ewf_apikey").val("");
                    } else {
                        $(".ewf_alert").html("Api Key Saved");
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    }
                },
                complete: () => {
                    $("#save_key").text("Save");
                },
                error: () => {
                    $(".ewf_alert").html("Error occurred while loading data.");
                },
            });
        }
    });
    /* Copy shortcode of forms from table */
    $(".copiable_wrap").each(function (index, element) {    
        $(this).on("click", function (event) {
            let shortcode = $(this).children(".copiable_input").val();
            navigator.clipboard.writeText(shortcode);
            $(".tooltip").each((i, elm) => {
                $(elm).text("Copied");
            });
        });
    });
    /* Reset Api Key */
    $("#reset_key").on("click", function (event) {    
        event.preventDefault();
        let apiKey = $("#ewf_apikey").val();
        if (confirm("Are you Sure?")) {
            if (apiKey === "") {
                $(".ewf_alert").text("Nothing to Reset");
            } else {
                $.ajax({
                    url: obj.ajaxurl,
                    type: "POST",
                    data: { action: "reset_api_key", security: obj.security_nonce },
                    dataType: "json",
                    beforeSend: () => {
                        $(this).text("Resetting...");
                    },
                    success: (response) => {
                        $(".ewf_alert").text(response.message);
                        $("#ewf_apikey").val("");
                        $(".activated_text").text("Deactivated");
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    },
                    complete: () => {
                        $(this).text("Reset");
                    },
                    error: () => {
                        $(".ewf_alert").html("Error occurred while loading data.");
                    },
                });
            }
        }
    });
    /* Copy Text Tooltip */
    $(".copiable_input").each(function (index, element) {
        // Tooltip
        $(element).on("mouseover", function () {
            let tooltip = $(element).siblings()[0];
            $(tooltip).css("display", "block");
        });

        $(element).on("mouseout", function () {
            let tooltip = $(element).siblings()[0];
            $(tooltip).css("display", "none");
            $(tooltip).text("Click To Copy");
        });
    });

});
