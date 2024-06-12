; (function ($) {
    var WidgetNavMenu = function ($scope, $) {

        let breakPoint  = $scope.find(".glossymm-menu-wrapper").data("responsive-breakpoint");
        let container  = $scope.find(".glossymm-menu-container");
        if ($(window).width() < breakPoint) {
            $('.glossymm-menu-hamburger').css('display', 'block');
            $(container).addClass("glossymm-megamenu-offcanvas");
        } else {
            $('.glossymm-menu-hamburger').css('display', 'none');
            $(container).removeClass("glossymm-megamenu-offcanvas");
        }
       $(window).resize(function() {
            if ($(window).width() < breakPoint) {
                $('.glossymm-menu-hamburger').css('display', 'block');
                $(container).addClass("glossymm-megamenu-offcanvas");
            } else {
                $('.glossymm-menu-hamburger').css('display', 'none');
                $(container).removeClass("glossymm-megamenu-offcanvas");
            }
       });




        let clickAbleElm = $scope.find(".glossymm-nav-dropdown-click .glossymm-megamenu-has, .glossymm-nav-dropdown-click .glossymm-dropdown-has");
        $(clickAbleElm).on("dblclick", function (e) {
            e.preventDefault();
            let link = $(e.currentTarget).children('a').attr("href");        
            window.location.href  = link;         
        });

        $(clickAbleElm).on("click", function (e) {
            e.preventDefault();

            let clickedElement = $(e.currentTarget);
            let glossymm_megamenu_panel = clickedElement.find(".glossymm-megamenu-panel");
            let glossymm_dropdown_panel = clickedElement.find(".glossymm-dropdown");
            // Determine if the clicked element is already shown
            let isCurrentlyShown = glossymm_megamenu_panel.hasClass("showmenu") || glossymm_dropdown_panel.hasClass("showmenu");
            // Hide all dropdowns and megamenus
            $(".glossymm-megamenu-panel.showmenu, .glossymm-dropdown.showmenu").removeClass("showmenu");
            // Toggle the current dropdown based on its previous state
            if (glossymm_megamenu_panel.length > 0) {
                if (!isCurrentlyShown) {
                    glossymm_megamenu_panel.addClass("showmenu");
                }
            }
            if (glossymm_dropdown_panel.length > 0) {
                if (!isCurrentlyShown) {
                    glossymm_dropdown_panel.addClass("showmenu");
                }
            }
        });
        // Stop event propagation for clicks within the dropdown panel
        $scope.find(".glossymm-megamenu-panel, .glossymm-dropdown").on("click", function (e) {
            e.stopPropagation();
        });

    // Hide dropdowns on outside click
        $(document).on("click", function(e) {
            if (!$(e.target).closest(".glossymm-nav-dropdown-click, .glossymm-megamenu-panel, .glossymm-dropdown").length) {
                $(".glossymm-megamenu-panel.showmenu, .glossymm-dropdown.showmenu").removeClass("showmenu");
            }
        });

    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/glossymm_nav_menu.default', WidgetNavMenu);
    });


})(jQuery);