$( document ).ready(function() {
   var video_table = $('#video_table');

    video_table.dataTable({
        "sAjaxSource": "api/music-video",
        "order" : [[3, "desc"]],
        "oLanguage": {
        "sSearch": "Track Search: "
        },
        fnServerData: function (sSource, aoData, fnCallback)
        {
            // var status = $('.optradio').val();
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
        video_table.api().ajax.reload();
    });

    $('.music-video-custom-filter').on('change', function (){
        video_table.api().ajax.reload();
    });

    $('.search-tags').on('change', function (){
        video_table.api().ajax.reload();
    });



    $(document).on("click", ".videoApprove, .videoReject", function(){
        var classData = $(this);
        $.ajax({
            url: 'api/music-video/change-state',
            type: "post",
            data: {
                    'status':$(this).attr("data-cmd"),
                    'id':$(this).attr("data-id")
                },
            success: function(data, textStatus, jqXHR) {
                toastr.success('','Track has been '+data);
                // $(classData).closest("td").text(data);
                // $(classData).closest("div").remove();
                video_table.api().ajax.reload();

                var oriVal = $('.check-status .sub-menu .music-video').find('span').text();
                if (oriVal == 1) {
                    $('.check-status .sub-menu .music-video').find('span').remove();
                } else {
                    $('.check-status .sub-menu .music-video').find('span').text(oriVal - 1);
                }
            },
            error: function(request, status, error) {
                
            }
        });
    });


    // $(document).on("click", ".topvideo-btn", function(){
    //     var viewArtist = $('#topvideo-table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         bDestroy: true,
    //         "sAjaxSource": "api/video/approved-videos",
    //         "oLanguage": {
    //             "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
    //         }
    //     });
    //     setTimeout(function(){
    //         $('#viewParticipateModal').modal('show');
    //     }, 400);
    // });


    // $(document).on("click", ".top-video", function(){
    //     var classData = $(this);
    //     // $(this).removeClass("label label-default background-color").addClass("label label-info");
    //     $.ajax({
    //         url: 'api/video/add-top',
    //         type: "get",
    //         data: {
    //                 'type':$(this).attr("data-cmd"),
    //                 'trackId':$(this).attr("data-id"),
    //                 'top': $(this).attr("id")
    //             },
    //         success: function(data, textStatus, jqXHR) {
    //             toastr.success('',data);

    //             $(classData).removeClass("label label-default background-color").addClass("label label-info");
    //             (classData).attr("id","InTop");
    //             //$('.alert-danger').addClass("hidden");
    //         },
    //         error: function(request, status, error) {
    //             if(request.responseJSON[0] == 'track removed'){
    //                 $(classData).removeClass("label label-info").addClass("label label-default background-color");
    //                 $(classData).attr("id","OutTop");
    //                 toastr.success('','Track removed form toplist');
    //             }else{
    //                 toastr.error('',request.responseJSON[0]);
    //             }
                
    //         }
    //     });
    // });


});