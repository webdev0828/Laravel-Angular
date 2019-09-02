$( document ).ready(function() {
    var currentUrl = $(location).attr('pathname').split('/')[2];
    var oriTitle = $('title').text();

    checkNewQueues();
    setInterval(checkNewQueues, 60000);
    function checkNewQueues() {
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: 'api/navigation/newqueue',
            /*data: {queue_type: currentUrl},*/
            success: function(data) {
                if (data['total'] > 0) {
                    $('title').text('(' + data['total'] +  ') ' + oriTitle);

                    $('.check-status').addClass('waiting-check');

                    if (data['quality-control'] > 0) {
                        $('.check-status .sub-menu .quality-control').find('span').addClass('badge badge-warning');
                        $('.check-status .sub-menu .quality-control').find('span').text(data['quality-control']);
                    } else {
                        $('.check-status .sub-menu .quality-control').find('span').empty();
                    }

                    if (data['discover'] > 0) {
                        $('.check-status .sub-menu .discover').find('span').addClass('badge badge-warning');
                        $('.check-status .sub-menu .discover').find('span').text(data['discover']);
                    } else {
                        $('.check-status .sub-menu .discover').find('span').empty();
                    }

                    if (data['remix'] > 0) {
                        $('.check-status .sub-menu .remix').find('span').addClass('badge badge-warning');
                        $('.check-status .sub-menu .remix').find('span').text(data['remix']);
                    } else {
                        $('.check-status .sub-menu .remix').find('span').empty();
                    }

                    if (data['music-video'] > 0) {
                        $('.check-status .sub-menu .music-video').find('span').addClass('badge badge-warning');
                        $('.check-status .sub-menu .music-video').find('span').text(data['music-video']);
                    } else {
                        $('.check-status .sub-menu .music-video').find('span').empty();
                    }

                    if (data['remix-competition'] > 0) {
                        $('.check-status .sub-menu .remix-competition').find('span').addClass('badge badge-warning');
                        $('.check-status .sub-menu .remix-competition').find('span').text(data['remix-competition']);
                    } else {
                        $('.check-status .sub-menu .remix-competition').find('span').empty();
                    }
                } else {
                    $('title').text(oriTitle);
                    $('.check-status').removeClass('waiting-check');
                }

            }
        });
    }

});