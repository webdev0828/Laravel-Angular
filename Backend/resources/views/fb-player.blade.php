<!DOCTYPE html>
<html>
<head>
<meta property="og:type" content="movie" />
<meta property="og:video:height" content="210" />
<meta property="og:video:width" content="420" />
<meta property="og:video:type" content="application/x-shockwave-flash" />
<meta property="og:title" content="{!! $og_title !!}" />
<meta property="og:description" content="{!! $og_description !!}" />
<meta property="og:image" content="{!! $og_image !!}" />
<meta property="og:video" content="{!! secure_url('player.swf') !!}?file={!! $og_video_secure_url !!}&image={!!  $og_image !!}&autostart=true" />



<!-- <meta prop<! erty="og:title"       content="{!! $og_title !!}" />
<meta property="og:type"        content="video" />
<meta property="og:image" 		content="https://app.sorethumbmedia.co.uk/images/bgd_hero.jpg" />
<meta property="og:description" content="{!! $og_description !!}" />
<meta property="og:video:url" 	content="{!! 'http://app.sorethumbmedia.co.uk/player/jwplayer/jwplayer.flash.swf?url='.$og_video_url !!}">
<meta property="og:video:secure_url" content="{!!  'https://app.sorethumbmedia.co.uk/player/jwplayer/jwplayer.flash.swf?url='.$og_video_secure_url  !!}">

<meta property="og:video:type" content="application/x-shockwave-flash" /> --> 
<meta property="fb:app_id"       content="{!! $fb_app_id !!}" />
</script>
</head>

<body>

</body>
</html>

