angular.module('app.directives')
    .directive('parallaxWindow', function ($timeout, SharedData) {
        return {
            restrict: 'C',
            link: function (scope, element, attr) {
                $(element).parallax({imageSrc: element.attr('src')});

                function changeImage(newValue) {
                    if ($(window).width() < 460 && $(element).css('background-image') != 'none') {
                        $(element).css('background-image', "url('" + newValue + "')");
                    }
                    setTimeout(function () {
                        $(element).parallax('destroy');
                        $(element).parallax({imageSrc: newValue});
                        var loaderDiv = '<img src="' + SharedData.loadingBannerImage + '" alt="Loading" class="images_loader_parallax">';
                        var findParallax = angular.element('.fb_reset .parallax-slider');

                        var findgradient = angular.element('.black-gradient');

                        var findLoader = angular.element('.images_loader_parallax');

                        if (findParallax.length) {
                            // findParallax.after(loaderDiv);
                            findgradient.after(loaderDiv);
                            findParallax.on('load', function () {
                                // $(this).siblings('.images_loader_parallax').remove();
                                findgradient.siblings('.images_loader_parallax').remove();
                            });
                        }
                    }, 10);
                }

                scope.$watch(function () {
                    jQuery(window).trigger('resize').trigger('scroll');
                    return element.attr('src');
                }, function (newValue, oldValue) {
                    if (newValue) {
                        changeImage(newValue);
                    } else {
                    }
                });
            }
        }
    });

// angular.module('app.directives')
// .directive('parallaxWindow2', function($timeout) {
//   return {
//     restrict : 'C',
//   link: function(scope, element, attr) { 
//       scope.$watch(function() {
//           jQuery(window).trigger('resize').trigger('scroll'); 
//           return element.attr('value'); 
//        }, function(newValue,oldValue){ 
//         if(newValue == oldValue){ 
//            $(element).parallax({ imageSrc: newValue });
//            angular.element(document.querySelector('.parallax-slider')).attr('onerror','this.src = "'+$(element).attr('onerror')+'"');
//         } else { 
//           // angular.element(document.querySelector('.parallax-slider')).attr('src',newValue);
//           if(angular.element(document.querySelector('.parallax-slider')).length) {
//             angular.element(document.querySelector('.parallax-slider')).attr('src', newValue);
//           } else {

//             if($(window).width() < 460 && $(element).css('background-image') != 'none') {
//               $(element).css('background-image', "url('"+newValue+"')");
//               console.log('test');
//             } else {
//               $(element).parallax({ imageSrc: newValue });
//               angular.element(document.querySelector('.parallax-slider')).attr('onerror','this.src = "'+$(element).attr('onerror')+'"');
//             }
//           }
//         }
//       });
//     }
//   }
// });