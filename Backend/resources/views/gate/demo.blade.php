<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <title>SoreThumbMedia</title>

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

  <script type="text/javascript">
    // var accessToken = null; //the access token is required to make any endpoint calls, http://instagram.com/developer/endpoints/
    // var authenticateInstagram = function(instagramClientId, instagramRedirectUri, callback) {
    //   //the pop-up window size, change if you want
    //   var popupWidth = 700,
    //     popupHeight = 500,
    //     popupLeft = (window.screen.width - popupWidth) / 2,
    //     popupTop = (window.screen.height - popupHeight) / 2;
    //   //the url needs to point to instagram_auth.php
    //   var popup = window.open('http://localhost:96/instagram/load', '', 'width='+popupWidth+',height='+popupHeight+',left='+popupLeft+',top='+popupTop+'');

    //   popup.onload = function() {
    //     //open authorize url in pop-up
    //     if(window.location.hash.length == 0) {
    //       popup.open('https://instagram.com/oauth/authorize/?client_id='+instagramClientId+'&redirect_uri='+instagramRedirectUri+'&response_type=token', '_self');
    //     }
    //     //an interval runs to get the access token from the pop-up
    //     var interval = setInterval(function(data) {
    //       console.log(data);
    //       try {
    //         //check if hash exists
    //         if(popup.location.hash.length) {
    //           console.log(popup);
    //           //hash found, that includes the access token
    //           clearInterval(interval);
    //           accessToken = popup.location.hash.slice(14); //slice #access_token= from string
    //           popup.close();
    //           if(callback != undefined && typeof callback == 'function') callback();
    //         }
    //       }
    //       catch(evt) {
    //         //permission denied
    //       }
    //     }, 100);
    //   }
    // };
    // function login_callback() {
    //   alert("You are successfully logged in! Access Token: "+accessToken);
    // }

    /*Set the following variables*/
    var instaUserName       = "stmdeveloper";
    var instaUserID         = "3611068242";    //Lookup your numeric user id here: http://jelled.com/instagram/lookup-user-id
    var instaClientID       = "dc872bfafb6d4533b8f950cb04f0c182";          //Obtain an instagram client ID from http://instagram.com/developer/
    var instaRedirectURI    = "http://localhost:96/instagram/process";       //Should point to the location of "instagram-follow-redirect-uri.html"
    window.instagramApp =   {
                                user_name : "stmdeveloper",
                                user_id : "3611068242",
                                client_id : "dc872bfafb6d4533b8f950cb04f0c182",
                                redirect_uri : "http://localhost:96/instagram/process",
                                connectCallback : connectCallback
                            };

    $(function(){
      $("#instaFollow").click(function(){instaFollowClick(instaClientID, instaRedirectURI);});
    });

    function instaFollowClick(id, uri){
        window.open ("https://instagram.com/oauth/authorize/?client_id="+id+"&redirect_uri="+uri+"&response_type=token&scope=relationships","mywindow","menubar=1,resizable=1,width=500,height=400");
    }

    function connectCallback(token){
        alert(token);
        // if(window.location.hash) {
            // $('#response').html('<h4>You are now following '+username+'!</h4>');
            // var hash = window.location.hash.substring(1);
            // var regexp = /=(.*)/g;
            // var token = regexp.exec(hash);
            $.post("https://api.instagram.com/v1/users/"+instaClientID+"/relationship?callback=?",{action: 'follow',access_token:token}, "json").success(function(r){
                console.log(r);
            });
        // }
    }

</script>

</head>
<body>
   <button id="instaFollow">Follow Me!</button>
</body>

</html>