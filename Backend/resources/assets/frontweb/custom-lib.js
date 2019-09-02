/*function t(t) { console.log('test');console.log(t);
    var i = t,
        n = i.css("height");
    function e() {
        i.css("height") != n && (n = i.css("height"), 
        $(window).trigger("resize.px.parallax")), 
        setTimeout(e, 250)
    }
    
    e();
}*/
if(/Edge\/\d./i.test(navigator.userAgent)){
     window.dispatchEvent(new Event( 'resize', true, true ));
}



window.open = function (open) {
    return function (url, name, features) {

        if(url.indexOf('facebook.com') == -1) {
            return open.call(window, url, name, features);
        }

        var w = 475;
        var h = 183;
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var override_features = 'width=475,height=200,left=' + left + ',top=' + top + ',scrollbars=1,location=1,toolbar=0';

        // set name if missing here
        //name = name || "default_window_name";
        return open.call(window, url, name, override_features);
    };
}(window.open);
    
var customLib = {
    getColumn : function(arr , name) {
        var result = [];
        if(arr) {
            for(i =0;i< arr.length; i++) {
                result.push(arr[i][name]);
            }
        }
        return result;
    },

    typeCastJson : function(obj) {
        if(obj) {
            $.each(obj, function(i,o){
                if(typeof o === 'object'){
                    customLib.typeCastJson(o);
                } else {
                    if(o && !isNaN(o)) {
                        obj[i] = Number(o);
                    }
                }
            });
        }
    },

    shuffleArray : function(array) {      
        var m = array.length, t, i;
        while (m) {
            i = Math.floor(Math.random() * m--);
            t = array[m];
            array[m] = array[i];
            array[i] = t;
        }
        return array;
    },

    getIndexByColumn: function(arr , obj , col) {
        if(arr) {
            for(var i = 0; i < arr.length; i++){ 
                if(obj[col] === arr[i][col]) {
                    return i;
                }
            }
        }
        return -1;
    },

    getIndexByName: function(arr , obj , col, type) {
        if(arr) {
            for(var i = 0; i < arr.length; i++){ 
                if(obj[col] === arr[i][col] && obj[type] === arr[i][type]) {
                    return i;
                }
            }
        }
        return -1;
    },

    setWindowTitle : function(win, title) {
        // if(win.document) { // if loaded
        //     win.document.title = title; // set title
        // } else { // if not loaded yet
        //     setTimeout(check, 10); // check in another 10ms
        // }
    }

}

function setWindowTitle(win, title) {
        // if(win.document) { // if loaded
        //     win.document.title = title; // set title
        // } else { // if not loaded yet
        //     setTimeout(check, 10); // check in another 10ms
        // }
    }

function jqueryViewReady() { 

    


    
    toastr.options.closeButton =true;
    new WOW().init();
    $( window ).resize();

    if($("#faq").length || $("#about").length || $("#artists-say").length) {
        function t(t) {
            function e() {
                i.css("height") != n && (n = i.css("height"), $(window).trigger("resize.px.parallax")), setTimeout(e,10)
            }
            var i = t,
                n = i.css("height");
            e()
        }

        t($("#faq")), 
        t($("#about")), 
        t($("#artists-say")); 
    }
    
    if ($("a.page-scroll").bind("click", function(t) {  
            var e = $(this);
            $('.navbar-ex1-collapse ul li').removeClass('active');
            $(this).hasClass("about") ? $("html, body").stop().animate({
                scrollTop: $(e.attr("href")).offset().top
            }, 1500, "easeInOutExpo") : $(this).hasClass("faq") ? $("html, body").stop().animate({
                scrollTop: $(e.attr("href")).offset().top -55
            }, 1500, "easeInOutExpo") : $("html, body").stop().animate({
                scrollTop: $(e.attr("href")).offset().top -50
            }, 1500, "easeInOutExpo"), t.preventDefault()
        }), 

    $("#artists-say").length) {}
   
    var e = $("#overlay");
    $("#search").click(function(){
        e.is(":visible") ? e.fadeOut() : e.fadeIn();
    });
    $("#close").click(function() {
        e.fadeOut();
    });

    $('#carousel-artists-say').carousel({
         interval: 10000
    });

    if($('#carousel-artists-say').length) {
        setTimeout(function(){
            $('.carousel-control.right').trigger('click');
        },10000);
    }

    // $('#carousel-twitter').carousel({
    //      interval: 10000
    // });

    if($('#carousel-twitter')) {
        setTimeout(function(){
            $('.controls-wrapper .fa-angle-right').trigger('click');
            $('#carousel-twitter').carousel({
                 interval: 10000
            });
        },10000);
    }


    $(document).on('click','.genre-label',function(e){
        e.stopPropagation();
    });
    
    //  Set
    $(window).resize(function(){
        setTimeout(function() {
            var height = document.getElementById('main').offsetHeight;
            var window_height = $(window).height();
            $('.footer-height').css('height', $('footer').outerHeight(true));

            $("#main").css('min-height',window_height);
        }, 500);
    });

    setTimeout(function() {
        var height = document.getElementById('main').offsetHeight;
        var window_height = $(window).height();
        
        $('.footer-height').css('height', $('footer').outerHeight(true));
        $("#main").css('min-height',window_height);
    }, 500);

    //  For Vimeo banners videos 
    // var container = document.getElementById('video_embed_container');
    // var video = document.getElementById('video_dashboard');
    // var ratio = 9/16; //this is why the 56.25% padding hack exists
    // if(container && video){
    //     function resizer() {
    //         var width = parseInt(window.getComputedStyle(container)['width'], 10);
    //         var height = (width * ratio);
    //         video.style.width = width + 'px';
    //         video.style.height = height + 'px';
    //     }
    //     window.addEventListener('resize', resizer, false);
    //     resizer();
    // }   

    (function(doc) {

      var root = doc.documentElement,
          scrollbarWidth, scrollEvent;

      // Not ideal, but better than UA sniffing.
      if ("MozAppearance" in root.style) {

        $(document).scroll(function(event) {
        // console.log(event);
            if($(".parallax_scroll").length)
            { 
                console.log(event.target);
                
            }
        });

        // determine the vertical scrollbar width
        scrollbarWidth = root.clientWidth;
        root.style.overflow = "scroll";
        scrollbarWidth -= root.clientWidth;
        root.style.overflow = "";

        // create a synthetic scroll event
        scrollEvent = doc.createEvent("UIEvent")
        setTimeout(function() {
            scrollEvent.initEvent("scroll", true, true);
        }, 0);
        

        // scrollEvent = $(window).scroll();

        // event dispatcher
        function scrollHandler() {
          doc.dispatchEvent(scrollEvent);
          // $(window).scroll();
        }

        // override mouse wheel behaviour.
        doc.addEventListener("DOMMouseScroll", function(e) {
          // Don't disable hot key behaviours
          if (!e.ctrlKey && !e.shiftKey) {
            root.scrollTop += e.detail * 10;
            // $(window).resize();
            scrollHandler.call(this, e);
            e.preventDefault()
          }
        }, true)
    }
    })(document); 
}