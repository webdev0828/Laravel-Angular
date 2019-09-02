$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
});

$(function() {

    /* ~~~~~~~~~~ Animsition ~~~~~~~~~~ */

    $(".animsition").animsition({
        inClass: 'fade-in',
        outClass: 'fade-out',
        inDuration: 1500,
        outDuration: 800,
        linkElement: '.animsition-link',
        // e.g. linkElement: 'a:not([target="_blank"]):not([href^=#])'
        loading: true,
        loadingParentElement: 'body', //animsition wrapper element
        loadingClass: 'animsition-loading',
        loadingInner: '', // e.g '<img src="loading.svg" />'
        timeout: false,
        timeoutCountdown: 5000,
        onLoadEvent: true,
        browser: [ 'animation-duration', '-webkit-animation-duration'],
        // "browser" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
        // The default setting is to disable the "animsition" in a browser that does not support "animation-duration".
        overlay : false,
        overlayClass : 'animsition-overlay-slide',
        overlayParentElement : 'body',
        transition: function(url){ window.location.href = url; }
    });


    /* ~~~~~~~~~~ Smooth page scroll ~~~~~~~~~~ */

    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);

        if($(this).hasClass('about')) {
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top
            }, 1500, 'easeInOutExpo');
        } else if ($(this).hasClass('faq')) {
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top + 40
            }, 1500, 'easeInOutExpo');
        } else {
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top - 50
            }, 1500, 'easeInOutExpo');
        }

        event.preventDefault();
    });


    /* ~~~~~~~~~~ Parallax fix ~~~~~~~~~~ */

    $(window).on('scroll', function(e) {
        $(this).trigger('resize');
    });

    function checkHeightChange(sectionId){
        var $element = sectionId;
        var lastHeight = $element.css('height');


        function checkForChanges()
        {

            if ($element.css('height') != lastHeight)
            {
                lastHeight = $element.css('height');
                $(window).trigger('resize.px.parallax');
            }

            setTimeout(checkForChanges, 250);
        }

        checkForChanges();
    };

    checkHeightChange($("#faq"));
    checkHeightChange($("#about"));
    checkHeightChange($("#artists-say"));


    /* ~~~~~~~~~~ Counter ~~~~~~~~~~ */

    if($('#artists-say').length) {
        var waypoint = new Waypoint({
            element: document.getElementById('artists-say'),
            handler: function(direction) {
                $(".counter").counter({
                    autoStart: true,
                    duration: 5000,
                    countFrom: 0,
                    countTo: 3254,
                    runOnce: true,
                    placeholder: "",
                    easing: "easeOutCubic",
                });
            }
        })
    }

    if($('.admin').length) {
        $(".number").counter({
            autoStart: true,
            duration: 5000,
            countFrom: 0,
            countTo: 3254,
            runOnce: true,
            placeholder: "",
            easing: "easeOutCubic",
        });
    }


    /* ~~~~~~~~~~ Bootstrap Switch ~~~~~~~~~~~ */

    if($('#socials').length || $('#createModal').length) {
        $("[name='checkbox-social']").bootstrapSwitch();
    }


    /* ~~~~~~~~~~ Activate tooltip ~~~~~~~~~~ */

    $('[data-toggle="tooltip"]').tooltip();
    $(".dropdown-toggle").dropdown();



    /* ~~~~~~~~~~ OWL Carousel ~~~~~~~~~~ */

    $(document).ready(function() {

        $("#owl-demo").owlCarousel({
            items : 4, //10 items above 1000px browser width
            itemsDesktop : [1000,4], //5 items between 1000px and 901px
            itemsDesktopSmall : [900,4], // betweem 900px and 601px
            itemsTablet: [600,3], //2 items between 600 and 0
            itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
        });

        $("#profile-owl").owlCarousel({
            items : 3, //10 items above 1000px browser width
            itemsDesktop : [1000,3], //5 items between 1000px and 901px
            itemsDesktopSmall : [900,2], // betweem 900px and 601px
            itemsTablet: [600,2], //2 items between 600 and 0
            itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
        });

        // Custom Navigation Events
        $(".next").click(function(){
            owl.trigger('owl.next');
        })
        $(".prev").click(function(){
            owl.trigger('owl.prev');
        })
        $(".play").click(function(){
            owl.trigger('owl.play',1000); //owl.play event accept autoPlay speed as second parameter
        })
        $(".stop").click(function(){
            owl.trigger('owl.stop');
        })

    });


    /* ~~~~~~~~~~ Overlay search ~~~~~~~~~~ */

    if($('#overlay').length) {
        var $overlay = $('#overlay');
        $('#search').click(function(){
            if ( $overlay.is(':visible') ) {
                $overlay.fadeOut();
            } else {
                $overlay.fadeIn();
            }
        });

        $('#close').click(function(){
            $overlay.fadeOut();
        });
    }


    /* ~~~~~~~~~~ Inline SVG ~~~~~~~~~~ */

    jQuery('img.svg').each(function(){
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(imgURL, function(data) {
            var $svg = jQuery(data).find('svg');

            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }

            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }

            $svg = $svg.removeAttr('xmlns:a');
            $img.replaceWith($svg);

        }, 'xml');
    });


});