$(document).ready(function () {
    //add on 13-11-2021
    // $('.owl-carousel')[0].slick.refresh()
    //end

    $(document).on('click', '.item-added', function () {
        $('.item-add').addClass('item-added-popup');
    });
    $(document).on('click', '.item-close-btn', function () {
        $('.item-add').removeClass('item-added-popup');
    });


    /*====================== js for sticky and mobile menu ====================== */
    var min_height = $(window).height() - ($(".custom-header").height() + $(".custom-footer").height());
    $(".main_page").css('min-height', min_height + 'px');
    $(window).resize(function () {
        var min_height = $(window).height() - ($(".custom-header").height() + $(".custom-footer").height());
        $(".main_page").css('min-height', min_height + 'px');
    });

    $(window).scroll(function () {
        var navHeight = $('#custom-header').height();
        var sticky = $('#custom-header');
        if ($(window).scrollTop() > 0) {
            sticky.addClass("sticky")
            $('#dashboard-page').css('padding-top', navHeight + "px");
        } else {
            sticky.removeClass("sticky");
            $('#dashboard-page').css('padding-top', 0);
        }
    });
    $('.jb_front_nav_close button').on('click', function () {
        $('.navbar-collapse').collapse('hide');
    });
    /*====================== js for sticky and mobile menu ====================== */

    /*====================== js for scrollTop ====================== */
    var btn = $('#button');

    $(window).scroll(function () {
        if ($(window).scrollTop() > 500) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });

    btn.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, '500');
    });
    /*====================== js for scrollTop ====================== */

    $('.food-slide').owlCarousel({
        loop: true,
        nav: false,
        margin: 10,
        dot: false,
        center: true,
        responsive: {
            0: {
                items: 4
            },
            600: {
                items: 6
            },
            1000: {
                items: 12
            }
        }
    });


    $('.recommend-slide').owlCarousel({
        loop: true,
        nav: false,
        margin: 15,
        dot: false,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 6
            },
            1000: {
                items: 6
            }
        }
    });

    // $('.show-more-content').hide();

    // $('.show-more').click(function(){
    //     $('.show-more-content').show(300);
    //     $('.show-less').show();
    //     $('.show-more').hide();
    // });

    // $('.show-less').click(function(){
    //     $('.show-more-content').hide(300);
    //     $('.show-more').show();
    //     $(this).hide();
    // });

    $('#qty_input').prop('disabled', true);
    $('#plus-btn').click(function () {
        $('#qty_input').val(parseInt($('#qty_input').val()) + 1);
    });
    $('#minus-btn').click(function () {
        $('#qty_input').val(parseInt($('#qty_input').val()) - 1);
        if ($('#qty_input').val() == 0) {
            $('#qty_input').val(1);
        }

    });
    /*====================== web slider ====================== */
    $('#home-slider').owlCarousel({
        loop: true,
        center: true,
        items: 1,
        autoplay: true,
        dots: true,
        nav: true,
        autoplayTimeout: 4000,
        smartSpeed: 450,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 1
            },
            1170: {
                items: 1
            }
        }
    });

    $('#team-slider').owlCarousel({
        loop: true,
        center: true,
        items: 1,
        autoplay: false,
        dots: false,
        nav: true,
        animateOut: 'fadeOut',
        autoplayTimeout: 1000,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    });
    /*====================== web slider ====================== */



    /*====================== custom video ====================== */
    // video icon poster frame click event
    $(document).on('click', '.js-videoPoster', function (ev) {
        ev.preventDefault();
        var $poster = $(this);
        var $wrapper = $poster.closest('.js-videoWrapper');
        videoPlay($wrapper);
    });

    // play the targeted video (and hide the poster frame)
    function videoPlay($wrapper) {
        var $iframe = $wrapper.find('.js-videoIframe');
        var src = $iframe.data('src');
        // hide poster
        $wrapper.addClass('videoWrapperActive');
        // add iframe src in, starting the video
        $iframe.attr('src', src);
    }

    // stop the targeted/all videos (and re-instate the poster frames)
    function videoStop($wrapper) {
        // if we're stopping all videos on page
        if (!$wrapper) {
            var $wrapper = $('.js-videoWrapper');
            var $iframe = $('.js-videoIframe');
            // if we're stopping a particular video
        } else {
            var $iframe = $wrapper.find('.js-videoIframe');
        }
        // reveal poster
        $wrapper.removeClass('videoWrapperActive');
        // remove youtube link, stopping the video from playing in the background
        $iframe.attr('src', '');
    }
    /*====================== custom video ====================== */

});/*====================== all js end ====================== */

