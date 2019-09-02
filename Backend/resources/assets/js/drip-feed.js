$( document ).ready(function() {
    
    $('#drip-feed-setting').on('click', function (){
        $.ajax({
                url: base_url+"/admin/dripfeed",
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#dripfeed_timer').val(data.dripfeed_timer);
                    $('#dripfeed-name').val(data.name);
                    $('#DripfeedModal').modal();
                },
                complete: function() {
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            });


        // $('#DripfeedModal').modal();
    });


    $("form.DripfeedForm").validate({
        rules: {
                dripfeed_timer: {required: true}
            },
        messages:{
                dripfeed_timer: {required: "Please enter Drip Feed timer"}
            }
    });

});