<!DOCTYPE html>
<html lang="en">
   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <title>SoreThumbMedia</title>
     <script type="text/javascript">
        function redirectBack() {
            // body...
            var hash = window.location.hash.substring(1);
            var regexp = /=(.*)/g;
            var token = regexp.exec(hash);
            window.setTimeout(opener.instagramApp.connectCallback(token[1]), 1);
            self.close();
        }
     </script>
   </head>
    <body onload="redirectBack()">
        <!-- <b style="width: 100%; text-align: center;">This popup should automatically close in a few seconds</b> -->
    </body>
</html>