/* Start Menu */
(function ($) {
    var index = 0;
    $.fn.menumaker = function (options) {
        var mainmenu = jQuery(this),
            settings = jQuery.extend({
                title: "",
                breakpoint: 1024,
                format: "dropdown",
                sticky: false
            }, options);
        return this.each(function () {
            mainmenu.prepend('<div id="menu-button" class="fa fa-bars" aria-hidden="true">' + settings.title + '</div>');
            jQuery(this).find("#menu-button").on('click', function () {
                jQuery(this).toggleClass('menu-opened');
                var mainmenu = jQuery(this).next('ul');
                if (mainmenu.hasClass('open')) {
                    mainmenu.slideToggle().removeClass('open');
                } else {
                    jQuery('ul.mobile-menu').slideToggle().addClass('open');
                    if (settings.format === "dropdown") {
                        mainmenu.find('ul').show();
                    }
                }
            });
            mainmenu.find('li ul').parent().addClass('has-sub');
            mainmenu.find('li ul').addClass('sub-menu');
            multiTg = function () {
                mainmenu.find(".has-sub").prepend('<span class="submenu-button fa fa-plus"></span>');
                mainmenu.find('.submenu-button').on('click', function () {
                    jQuery(this).toggleClass('submenu-opened');
                    if (jQuery(this).siblings('ul').hasClass('open')) {
                        jQuery(this).siblings('ul').slideToggle().removeClass('open');
                    } else {
                        jQuery(this).siblings('ul').slideToggle().addClass('open');
                    }
                });
            };
            if (settings.format === 'multitoggle') multiTg();
            else mainmenu.addClass('dropdown');
            if (settings.sticky === true) mainmenu.css('position', 'fixed');
            resizeFix = function () {
                if (jQuery(window).width() > 1024) {
                    mainmenu.find('ul').show();

                }
                if (jQuery(window).width() <= 1024) {
                    mainmenu.find('#menu-button').removeClass('menu-opened');
                    mainmenu.find('ul').hide().removeClass('open');
                }
            };
            resizeFix();
            return jQuery(window).on('resize', resizeFix);
        });
    };
})(jQuery);
(function ($) {
    jQuery(document).ready(function () {
        jQuery(document).ready(function () {
            jQuery("#mainmenu").menumaker({
                title: "",
                format: "multitoggle"
            });
            var foundActive = false,
                activeElement, linePosition = 0,
                width = 0,
                menuLine = jQuery("#mainmenu #menu-line"),
                lineWidth, defaultPosition, defaultWidth;
            jQuery("#mainmenu > ul > li").each(function () {
                if (jQuery(this).hasClass('current-menu-item')) {
                    activeElement = jQuery(this);
                    foundActive = true;
                }
            });
            if (foundActive != true) {
                activeElement = jQuery("#mainmenu > ul > li").first();
            }
            if (foundActive == true) {
                activeElement = jQuery("#mainmenu > ul > li").first();
            }
            defaultWidth = lineWidth = activeElement.width();
            defaultPosition = linePosition = activeElement.position().left;
            menuLine.css("width", lineWidth);
            menuLine.css("left", linePosition);
            jQuery("#mainmenu > ul > li").hover(function () {
                    activeElement = $(this);
                    lineWidth = activeElement.width();
                    linePosition = activeElement.position().left;
                    menuLine.css("width", lineWidth);
                    menuLine.css("left", linePosition);
                },
                function () {
                    menuLine.css("left", defaultPosition);
                    menuLine.css("width", defaultWidth);
                });
        });
        /** Set Position of Sub-Menu **/
        var wapoMainWindowWidth = jQuery(window).width();
        jQuery('#mainmenu ul ul li').mouseenter(function () {
            var subMenuExist = jQuery(this).find('.sub-menu').length;
            if (subMenuExist > 0) {
                var subMenuWidth = jQuery(this).find('.sub-menu').width();
                var subMenuOffset = jQuery(this).find('.sub-menu').parent().offset().left + subMenuWidth;
                if ((subMenuWidth + subMenuOffset) > wapoMainWindowWidth) {
                    jQuery(this).find('.sub-menu').removeClass('submenu-left');
                    jQuery(this).find('.sub-menu').addClass('submenu-right');
                } else {
                    jQuery(this).find('.sub-menu').removeClass('submenu-right');
                    jQuery(this).find('.sub-menu').addClass('submenu-left');
                }
            }
        });
    });
})(jQuery);

/*Mobile Nav*/
function resize() {
    if (jQuery(window).width() <= 1024) {
        jQuery('#mainmenu > ul').addClass('mobile-menu');
    } else {
        jQuery('#mainmenu > ul').removeClass('mobile-menu');
    }
}
jQuery(document).ready(function () {
    jQuery(window).resize(resize);
    resize();
});
jQuery(document).ready(function(e) {
    
    jQuery('.owl-carousel-home').owlCarousel({
        loop:true,
        autoplay:true,
        animateOut: 'fadeOut',
        margin:10,
        nav:false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
});

(function($) {
    "use strict";
    // Author code here  
        jQuery('.current-menu-item,.current_page_item').addClass('active');
        /*-----------sidebar----------*/
        jQuery('.about-us-blog ul').addClass('post-cat-list');
   
})(jQuery);
