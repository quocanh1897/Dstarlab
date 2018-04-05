(function (e) {
    "use strict";
    var n = window.Thememattic_JS || {};
    n.stickyMenu = function () {
        e(window).scrollTop() > 350 ? e("#masthead").addClass("nav-affix") : e("#masthead").removeClass("nav-affix")
    },
        n.mobileMenu = {
            init: function () {
                this.toggleMenu(), this.menuMobile(), this.menuArrow()
            },
            toggleMenu: function () {
                e('#masthead').on('click', '.toggle-menu', function (event) {
                    var ethis = e('.main-navigation .menu .menu-mobile');
                    if (ethis.css('display') == 'block') {
                        ethis.slideUp('300');
                    } else {
                        ethis.slideDown('300');
                    }
                    e('.ham').toggleClass('exit');
                });
                e('#masthead .main-navigation ').on('click', '.menu-mobile a i', function (event) {
                    event.preventDefault();
                    var ethis = e(this),
                        eparent = ethis.closest('li'),
                        esub_menu = eparent.find('> .sub-menu');
                    if (esub_menu.css('display') == 'none') {
                        esub_menu.slideDown('300');
                        ethis.addClass('active');
                    } else {
                        esub_menu.slideUp('300');
                        ethis.removeClass('active');
                    }
                    return false;
                });
            },
            menuMobile: function () {
                if (e('.main-navigation .menu > ul').length) {
                    var ethis = e('.main-navigation .menu > ul'),
                        eparent = ethis.closest('.main-navigation'),
                        pointbreak = eparent.data('epointbreak'),
                        window_width = window.innerWidth;
                    if (typeof pointbreak == 'undefined') {
                        pointbreak = 991;
                    }
                    if (pointbreak >= window_width) {
                        ethis.addClass('menu-mobile').removeClass('menu-desktop');
                        e('.main-navigation .toggle-menu').css('display', 'block');
                    } else {
                        ethis.addClass('menu-desktop').removeClass('menu-mobile').css('display', '');
                        e('.main-navigation .toggle-menu').css('display', '');
                    }
                }
            },
            menuArrow: function () {
                if (e('#masthead .main-navigation div.menu > ul').length) {
                    e('#masthead .main-navigation div.menu > ul .sub-menu').parent('li').find('> a').append('<i class="fa fa-angle-down">');
                }
            }
        },

        n.DataBackground = function () {
            var pageSection = e(".data-bg");
            pageSection.each(function (indx) {

                if (e(this).attr("data-background")) {
                    e(this).css("background-image", "url(" + e(this).data("background") + ")");
                }
            });

            e('.bg-image').each(function () {
                var src = e(this).children('img').attr('src');
                e(this).css('background-image', 'url(' + src + ')').children('img').hide();
            });
        },

        n.SearchReveal = function () {
            e('.icon-search').on('click', function (event) {
                e('body').toggleClass('reveal-search');
            });
            e('.close-popup').on('click', function (event) {
                e('body').removeClass('reveal-search');
            });
        },

        /* Slick Slider */
        n.SlickCarousel = function () {
            e(".mainbanner-jumbotron").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                autoplay: true,
                autoplaySpeed: 8000,
                infinite: true,
                dots: false,
                nextArrow: '<i class="Thememattic-icon slide-icon slide-next fa fa-angle-right"></i>',
                prevArrow: '<i class="Thememattic-icon slide-icon slide-prev fa fa-angle-left"></i>',
            });

            e(".tm-news").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                autoplay: true,
                autoplaySpeed: 8000,
                infinite: true,
                dots: true,
                arrows: false
            });


            e('.featured-course').slick({
                slidesToShow: 3,
                slidesToScroll: 3,
                nextArrow: '<i class="Thememattic-icon slide-icon slide-next fa fa-angle-right"></i>',
                prevArrow: '<i class="Thememattic-icon slide-icon slide-prev fa fa-angle-left"></i>',
                focusOnSelect: true,
                dots: true,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            e('.tm-team').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                focusOnSelect: true,
                dots: true,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });

            e('.testmonial-slides').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                nextArrow: '<i class="Thememattic-icon slide-icon slide-next fa fa-angle-right"></i>',
                prevArrow: '<i class="Thememattic-icon slide-icon slide-prev fa fa-angle-left"></i>',
                focusOnSelect: true,
                dots: true
            });

            e(".gallery-columns-1").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                autoplay: true,
                autoplaySpeed: 8000,
                infinite: true,
                dots: false,
                nextArrow: '<i class="Thememattic-icon slide-icon slide-next fa fa-angle-right"></i>',
                prevArrow: '<i class="Thememattic-icon slide-icon slide-prev fa fa-angle-left"></i>',
            });
        },


        n.Thememattic_preloader = function () {
            e(window).load(function () {
                e("body").addClass("page-loaded");
            });
        },

        n.parellex = function () {
            e.stellar({
                horizontalScrolling: false,
                verticalOffset: 0,
                responsive: true
            });
        },

        n.scroll_up = function () {
            e("#scroll-up").on("click", function () {
                e("html, body").animate({
                    scrollTop: 0
                }, 800);
                return false;
            });

            e('.smoothscroll').on('click', function () {
                event.preventDefault();
                var target = this.hash;

                e('html, body').stop().animate({
                    'scrollTop': e(target).offset().top - 0
                }, 1200);
            });
        },

        e(document).ready(function () {
            n.mobileMenu.init(), n.DataBackground(), n.SearchReveal(), n.SlickCarousel(), n.Thememattic_preloader(), n.parellex(), n.scroll_up();
        }), e(window).scroll(function () {
        n.stickyMenu();
    }), e(window).resize(function () {
        n.mobileMenu.menuMobile();
    })
})(jQuery);

