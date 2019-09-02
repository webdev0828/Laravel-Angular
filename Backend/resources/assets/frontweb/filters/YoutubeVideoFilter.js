angular.module('app.filters')
.filter('youtubevideo',function($sce, $filter) {
    return function(url,height) {
        if(url){ 
            var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
            var textUrl =  (String(url).match(p)) ? 'https://www.youtube.com/embed/'+(RegExp.$1) : false;
            if(textUrl) { 
                url = $sce.trustAsResourceUrl(textUrl);
                var html = "<iframe height='"+height+"' type='video/youtube' width='100%' class='embed-responsive-item' src='"+url+"?rel=0&modestbranding=0&autohide=1&showinfo=0&controls=1' allowfullscreen></iframe>";
                return $sce.trustAsHtml(html);
            }
        }    
        return '';
    };
});