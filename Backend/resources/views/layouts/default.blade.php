<!DOCTYPE html>
<html class="no-js" lang="" ng-app="app">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sore Thumb Media - Download Electronic Music, Music Download Free Online </title>

        <meta name="title" content="Sore Thumb Media - Download Electronic Music, Music Download Free Online">
        <meta name=”description” content="Sorethumbmedia.co.uk is a promotional gating and follow to download online platform. We help artists build bigger and better fan communities around their music.">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        @if(isset($meta))
        <meta name="og:image" content="{!! url($meta['image']) !!}">
        <meta name="og:title" content="{!! $meta['title'] !!}">
        <meta name="og:description" content="{!! $meta['description'] !!}">
        @endif

        <base href="/"> 

        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="icon" href="frontweb/assets/images/favicon.jpg" type="image/gif" sizes="16x16">

        <!-- Place favicon.ico in the root directory -->
        <link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,300' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Raleway:500,300,400,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:800,700,400' rel='stylesheet' type='text/css'>


	



        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={!! env('GOOGLE_API_KEY') !!}&libraries=places&region=UK" defer></script>

        <link rel="stylesheet" href="frontweb/css/stm-frontweb.css?v={!! uniqid() !!}">
        <link rel="stylesheet" href="frontweb/css/stm-dev.css?v={!! uniqid() !!}">
        <link rel="stylesheet" href="frontweb/css/all-ie-only.css">
        <link rel="stylesheet" href="frontweb/css/mithun.css">
        <link rel="stylesheet" href="frontweb/css/custom.css">
        <script type="text/javascript" defer>
        var globalObj = {
                            user_type : "{!! ($user = \Auth::user()) && in_array($user->user_type, ['artist','stm_user']) ? $user->user_type : false !!}",
                            _token : '{!! csrf_token() !!}',
                            baseUrl : '{!! url('/') !!}',
                            appName : '{!!  config('constants.app_name') !!}',
                            socialLinks : {!!  json_encode(config('constants.stm_social_links')) !!},
                            app_env  : "{!! env('APP_DEV') !!}",
                            intercom_id : "{!! env('INTERCOM_ID') !!}",
                            'sc_key' : '{!! env("SOUNDCLOUD_KEY") !!}',
                            'spotify_client_id' : '{!! env("SPOTIFY_CLIENT_ID") !!}',
                            'stm_artist_link' :  '{!! env("STM_ARTIST_ACCOUNT") !!}',
                            'google_key' : "{!! env('GOOGLE_API_KEY') !!}",
                            facebook : "{!! env('FACEBOOK_CLIENT_ID') !!}",
                            welcomePopup : "{!! env('WELCOME_POPUP') !!}"
                        };


        </script>
    <!-- Facebook Pixel Code -->

        <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1727307600932366'); // Insert your pixel ID here.
        fbq('track', 'PageView');
        </script>

        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1727307600932366&ev=PageView&noscript=1" /></noscript>
        <!-- DO NOT MODIFY -->
        <!-- End Facebook Pixel Code -->


    </head>
    <body data-spy="scroll" data-target=".navbar-fixed-top" id="page-top" ng-cloak ng-style="pagebackground && {'background': 'url('+pagebackground+') no-repeat center center fixed'}" data-offset="100">
        <div id="fb-root"></div>
        <script defer>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

        <div class="loading" ng-init="showLoading = 'false'" ng-if="showLoading"></div>
        <div ui-view id="main" class="animsition" autoscroll="false"></div>
        <div class="animsition-loading"></div>

        <!-- Player directive and template -->

        <div ng-controller="AppCtrl" ng-if="noPlayerStates.indexOf(pageStatee.name) == -1" >
            <div  checkuser="user" favtrack="_favTrackIds" favcampaign="_favCampaignIds" favremix="_favRemixIds" playlisttrack="_playlistTrackIds" playlistcampaign="_playlistCampaignIds" playlistremix="_playlistRemixIds" imagedefault="commomnImage" class='speaker' data-audio="audio" data-autoplay="true" data-pauseothers="true" jplayer></div>
        </div>


        <!-- <script src="https://connect.soundcloud.com/sdk/sdk-3.1.2.js"></script>
        <script src="https://connect.facebook.net/en_US/sdk.js"></script> -->
        <!-- <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script> -->
        <!-- <script src="https://apis.google.com/js/platform.js"></script>  -->

        


        <script>
            $(function() {
                @if(Session::has('error'))
                    toastr.error('', "{!! Session::get('error') !!}");
                @endif
                @if(Session::has('success'))
                    toastr.success('', "{!! Session::get('success') !!}");
                @endif

                @if(Session::has('error_message'))
                    toastr.error('', "{!! Session::get('error_message') !!}");
                @endif
                @if(Session::has('email_update'))
                    swal("Your email updated successfully.");
                @endif
            });

            // remove hash character from url when login with facebook
            if (window.location.hash && (window.location.hash == '#_=_' || window.location.hash == '#')) {
                if (window.history && history.pushState) {
                    window.history.pushState("", document.title, window.location.pathname);
                } else {
                    // Prevent scrolling by storing the page's current scroll offset
                    var scroll = {
                        top: document.body.scrollTop,
                        left: document.body.scrollLeft
                    };
                    window.location.hash = '';
                    // Restore the scroll offset, should be flicker free
                    document.body.scrollTop = scroll.top;
                    document.body.scrollLeft = scroll.left;
                }
            }
        </script>
                <?php
/*
$cookie_name = "TestCookie";
$cookie_value = "STM";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

if(!isset($_COOKIE[$cookie_name])) {
    echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
    echo "Cookie '" . $cookie_name . "' is set!<br>";
    echo "Value is: " . $_COOKIE[$cookie_name];
}
*/

?>

<script>
if(navigator.userAgent.indexOf("Speed Insights") == -1) {
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-90677314-1', 'auto');
            ga('send', 'pageview');

}
</script>
        
    </body>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.8/angular.min.js"></script>
	 {!! HTML::script('frontweb/js/stm-frontweb-plugins.js') !!} 
	
         {!! HTML::script('frontweb/js/stm-frontweb.js?v='.uniqid()) !!} 

	
</html>