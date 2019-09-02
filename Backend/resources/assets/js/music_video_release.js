$( document ).ready(function() {

    var releases_video_table = $('#releases_video_table');
    releases_video_table.dataTable({
        "processing": true,
        "serverSide":true,
        "stateSave": true,
        "paging": true,
        "sAjaxSource": "api/music_video_release",
        "order" : [[3, "desc"]],
        "columnDefs": [
            { width: 200, targets: 4 }
        ],
        "oLanguage": {
           "sSearch": "Track Search: "
        },
        fnServerData: function (sSource, aoData, fnCallback)
        {
            var artist = $("#artistId").val();
            var genre = $("#genre_name").val();

            Array.prototype.push.apply(aoData, [
                {"name": "artist", "value": artist},
                {"name": "genre", "value": genre},
            ]);
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: sSource,
                data: aoData,
                success: fnCallback
              
            });
        }
    });

    $('.video-custom-filter').on('change', function (){
        releases_video_table.api().ajax.reload();
    });


    // console.log($("#sub-genres-block option[value=all]:selected").length > 0);
    // $('form#newReleaseAddForm #sub-genres-block').hide();
    // $('form#newReleaseEditForm #sub-genres-block').hide();


    // $('.Dropdown').slimScroll({
    //     width : '100%',
    //     height : '150px'
    // });


    $(document).on("click", ".top-video", function(){
        var classData = $(this);
        $.ajax({
            url: 'api/music_video_release/add-top',
            type: "get",
            data: {
                    'type':$(this).attr("data-cmd"),
                    'trackId':$(this).attr("data-id"),
                    'top': $(this).attr("id")
                },
            success: function(data, textStatus, jqXHR) {
                if(data.msg == undefined){
                    toastr.error('',''+data);
                }else{
                    toastr.success('','Track has been '+data.msg);
                    if(data.status == 'removed'){
                        classData.removeClass("label label-info").addClass("label label-default background-color");
                        classData.attr("id","OutTop").css('border','0px');                    
                    }else{
                        classData.removeClass("label label-default background-color").addClass("label label-info");
                        classData.attr("id","InTop");
                    }
                }
            },
            error: function(request, status, error) {
                               
            }
        });
    });


    $(document).on("click", ".videoPlay", function(){
        
    });

    $("form.releases-form").validate({
        rules: {
                track_id: {required : true},
                url: {required : true,url: true},
                artist_id: {required : true},
                download_link: {required : true}
                // artwork_file: {required : true},
                // background_file: {required : true}
            },
        messages:{
                track_id: {required:"Please select track"},
                url: {required:"Please enter url",url: "Please enter a valid URL.(eg. http://stm.com)"},
                artist_id: {required:"Please select artist"},
                download_link: {required:"Please enter download link"}
                // artwork_file: {required:"Please select artwork file"},
                // background_file: {required:"Please select background file"}
            },
            errorPlacement: function(error, element) {
                // console.log(element)
                if (element.context.type == 'file' || element.context.type == 'select-multiple') {
                    // error.appendTo(element.parent().parent());
                    error.insertAfter(element.parent());
                }
                else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                this.submitButton.disabled = true;
                $("body").addClass('loading');
                form.submit();
            }

    });

    $("form.edit-releases-form").validate({
        rules: {
                track_name: {required : true},
                url: {required : true,url: true},
                artist_id: {required : true},
                download_link: {required : true}
            },
        messages:{
                track_name: {required:"Please enter track name"},
                url: {required:"Please enter url",url: "Please enter a valid URL.(eg. http://stm.com)"},
                artist_id: {required:"Please select artist"},
                download_link: {required:"Please enter download link"}
            },
            submitHandler: function (form) {
                this.submitButton.disabled = true;
                $("body").addClass('loading');
                form.submit();
            }
    });

    $("#videoRelease").select2({
      maximumSelectionLength: 1,
      width: '100%',
      minimumInputLength: 3,
      placeholder: 'Select artist',


        ajax: {
            url: base_url+'/admin/get-artist',
            type: "GET",
            delay: 100,
            data: function (params) {

                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {

                    results: $.map(data.artistList, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });

    $("#track-data").select2({
        maximumSelectionLength: 1,
        width: '100%',
        minimumInputLength: 1,
        placeholder: 'Select track',


        ajax: {
            url: base_url+'/admin/api/music_video_release/get-artist-tracks',
            type: "GET",
            delay: 100,
            data: function (params) {

                var queryParameters = {
                    term: params.term,
                    artistId : $("#videoRelease").val()[0]
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {

                    results: $.map(data.trackList, function (item) {
                        return {
                            text: item.track_name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });


    $('#videoRelease').on('change', function (evt) {
        $('#track-data').empty();
    });


});