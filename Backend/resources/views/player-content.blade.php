<!DOCTYPE html>
<html>
<body>

<style type="text/css">
video {  
   width:100%; 
   max-width:480px; 
   max-height:480px;
   height:auto; 
}
</style>

<video width="100%" controls poster="{!! $coverImage !!}">
  <source src="{!! $fileUrl !!}" type="{!! $fileType !!}">
	Your browser does not support video
</video>

</body>
</html>

