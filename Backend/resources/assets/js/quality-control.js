$( document ).ready(function() {
    var quality_control_table = $('#quality_control_table');

    quality_control_table.dataTable({
        serverSide:true,
        "sAjaxSource": "api/quality-control",
        "order" : [[4, "desc"]],
        "oLanguage": {
        "sSearch": "Track Search: "
        },
        fnServerData: function (sSource, aoData, fnCallback)
        {
            var status = $("input[type='radio'].radio-filter:checked").val();
            var artist = $("#artist_name").val();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            Array.prototype.push.apply(aoData, [
                {"name": "status", "value": status},
                {"name": "artist", "value": artist},
                {"name": "start_date", "value": start_date},
                {"name": "end_date", "value": end_date}
            ]);
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: sSource,
                data: aoData,
                success: fnCallback
                // beforeSend: function(request) {
                //   return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                // }
            });
        }
    });

    $('.radio-filter').on('click', function (){
        quality_control_table.api().ajax.reload();
    });

    $('.custom-filter').on('change', function (){
        quality_control_table.api().ajax.reload();
    });
    
    $(document).on('click', '.open-soundcloud', function() {
        var newWindow = window.open("","_blank");
        $.ajax({
            url: 'api/sc-url',
            type: 'get',
            data: {'user_id':$(this).attr("data-id")},
            success: function(data) {
                var scUrl = data['soundcloud_url'] + '/tracks';
                newWindow.location.href = scUrl;
            }
        });
    });

    $(document).on("click", ".trackApprove-quality, .trackReject-quality", function(){
        var classData = $(this);
        $.ajax({
            url: 'api/quality-control/change-state',
            type: "post",
            data: {
                    'status':$(this).attr("data-cmd"),
                    'id':$(this).attr("data-id")
                },
            success: function(data, textStatus, jqXHR) {

                if(data.errorMsg){
                    toastr.error('', data.errorMsg);
                }else{
                    if(data.status == "approved")
                        toastr.success('', 'Track is approved and reposted');
                }

                quality_control_table.api().ajax.reload();

                var oriVal = $('.check-status .sub-menu .quality-control').find('span').text();
                if (oriVal == 1) {
                    $('.check-status .sub-menu .quality-control').find('span').remove();
                } else {
                    $('.check-status .sub-menu .quality-control').find('span').text(oriVal - 1);
                }

            },
            error: function(request, status, error) {
                
            }
        });
    });

});