<!DOCTYPE HTML>
<html>
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    	<title>Instagram Authentication</title>
    	<script type="text/javascript">
    		//using php to look for the error parameter in the URL
    		if(<?php echo intval(isset($_GET['error'])); ?>) {
				self.close();
			}
    	</script>
    </head>
    <body onload="window.setTimeout(opener.SC.connectCallback, 1);">
     <b style="width: 100%; text-align: center;">This popup should automatically close in a few seconds</b>
   </body>
</html>