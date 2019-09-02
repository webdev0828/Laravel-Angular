$( document ).ready(function() {

    $('form#competitionForm #competition-sub-genres-block').hide();

    var competition_table = $('#competition_table');
    competition_table.dataTable({
        processing: true,
        serverSide: true,
        "order" : [[3, "desc"]],
        "sAjaxSource": "api/competition",
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    // $('.announcement_date').datepicker({
    //     orientation: "top auto",
    //     autoclose: true
    // })


    // $('.searchable').multiSelect({
    //     keepOrder: true,
    //     selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='search name'>",
    //     selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='search name'>",
    //     afterInit: function(ms){
    //         var that = this,
    //             $selectableSearch = that.$selectableUl.prev(),
    //             $selectionSearch = that.$selectionUl.prev(),
    //             selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
    //             selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

    //         that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
    //         .on('keydown', function(e){
    //             if (e.which === 40){
    //                 that.$selectableUl.focus();
    //             return false;
    //             }
    //         });

    //         that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
    //         .on('keydown', function(e){
    //             if (e.which == 40){
    //                 that.$selectionUl.focus();
    //             return false;
    //         }
    //         });
    //     },
    //     afterSelect: function(){
    //         this.qs1.cache();
    //         this.qs2.cache();
    //     },
    //     afterDeselect: function(){
    //         this.qs1.cache();
    //         this.qs2.cache();
    //     }
    // });


    // $("#competitionForm").submit(function() {
    //     var formId = $('#formId').val();
    //     if(formId){
    //         var originalTrack = $('#originalTrack').val();
    //         var stemFile = $('#stem_file').val();

    //         if(!(originalTrack && stemFile)) {
    //             if(originalTrack == ""){
    //                 $('.track-error').html("The original track field is required");
    //             }else{
    //                 $('.subject').html("");
    //             }
    //             if(stemFile == ""){
    //                 $('.stem-error').html("The stem file field is required");
    //             }else{
    //                 $('.stem-error').html("");
    //             }
    //             return false;    
    //         }
    //     }
    //     else{
    //         return true;
    //     }
    // });



    // $(document).on("click", ".remove-cover-image", function(){
    //     var id = $(this).attr("data-id");
    //     var removCover = confirm('Are you sure, do you want to delete this cover image?');
    //     if(removCover){
    //         $.ajax({
    //             url: base_url+'/admin/api/competition/remove-file',
    //             type: "delete",
    //             data: {
    //                     'id':id
    //                 },
    //             success: function(data, textStatus, jqXHR) {
    //                 toastr.success('',data);
    //                 location.reload();
    //             },
    //             error: function(request, status, error) {
                   
    //             }
    //         }); 
    //     }
    // });

    // $(document).on("click", ".choose-winner", function(){
    //     var id = $(this).attr("data-id");
    //     // $('#competitionId').val(id);
        
    //     // $.get('api/competition/'+id+'/artist-data', function(data) {
    //     //     $('.searchable').empty();
    //     //     $.each(data.artistData, function(key, val){
    //     //         $('.searchable').append('<option value="' + key + '">' + val + '</option>');
    //     //     })
    //     //     $(data.winnerIds).each(function(i, item) {
    //     //         $('.searchable option[value=' + item + ']').attr('selected', true);
    //     //     });
    //     //     $('.searchable').multiSelect('refresh');
    //     //     $('#chooseWinnerModal').modal('show');
            
    //     // }).error(function(error){
    //     //     toastr.error('',error.responseJSON[0]);
    //     // });
    //     var viewArtist = $('#view-participates').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         bDestroy: true,
    //         "sAjaxSource": "api/competition/"+id+"/artist-data",
    //         "oLanguage": {
    //             "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
    //         }
    //     });
    //     setTimeout(function(){
    //         $('#chooseWinnerModal').modal('show');
    //     }, 500);
    // });


    // $('.save-winner').click(function(){
    //     var listData = [];
    //     if($('#1').data('id')){
    //         listData.push($('#1').data('id'));
    //     }
    //     if($('#2').data('id')){
    //         listData.push($('#2').data('id'));   
    //     }
    //     if($('#3').data('id')){
    //         listData.push($('#3').data('id'));   
    //     }

    //     if($('#1').data('id') && listData.length >= 1){
    //         if(($('#1').data('id') && $('#2').data('id') && listData.length >= 2) || ($('#1').data('id') && listData.length == 1)){
    //             var compId = $('#1').data('compid');
    //             var submitForm = confirm('Are you sure, do you want to close this competition?');
    //             if(submitForm){
    //                 $.ajax({
    //                     url: 'api/competition/select-winner',
    //                     type: "post",
    //                     data: {
    //                             'winnerlist':{
    //                                 0:$('#1').data('id'),
    //                                 1:$('#2').data('id'),
    //                                 2:$('#3').data('id')
    //                             },
    //                             'cometitionId': compId
    //                         },
    //                     success: function(data, textStatus, jqXHR) {
    //                         competition_table.api().ajax.reload();
    //                         $('#chooseWinnerModal').modal('hide');
    //                         toastr.success('','Winner has been selected.');
    //                     },
    //                     error: function(request, status, error) {
    //                        toastr.success('','Winner has been selected.');
    //                     }
    //                 });
    //             }
    //         }else{
    //             toastr.error('','Please select 2st winner.');    
    //         }
    //     }else{
    //         toastr.error('','Please select 1st winner.');
    //     }
    // });

    // $(document).on("change", ".winner-select", function(){
    //     var prevValue = $(this).data('previous');
    //         $('.winner-select').not(this).find('option[value="'+prevValue+'"]').attr("disabled",false);    
    //         var value = $(this).val();
    //     if(value !== ''){
    //         $(this).attr('id', $(this).val());
    //         $(this).data('previous',value);
    //         $('.winner-select').not(this).find('option[value="'+value+'"]').attr("disabled","disabled");
    //     }else{
    //         $(this).removeAttr('id');
    //     }
    // });

    // $(document).on("click", "#view-artist", function(){
    //     var id = $(this).attr("data-id");
    //     var viewArtist = $('#view-artist-list').DataTable({
    //         destroy: true,
    //         processing: true,
    //         serverSide: true,
    //         "sAjaxSource": "api/competition/"+id+"/participates",
    //         "order" : [[1, "desc"]],
    //         "oLanguage": {
    //             "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
    //         }
    //     });
    //     setTimeout(function(){
    //         $('#viewParticipateModal').modal('show');
    //     }, 500);
    // });

    // $(document).on("click", ".view-winner", function(){
    //     var id = $(this).attr("data-id");
    //     var viewArtist = $('#winner-table').DataTable({
    //         destroy: true,
    //         processing: true,
    //         serverSide: true,
    //         "sort": false,
    //         "order":[[1, "asc"]],
    //         "searching": false,
    //         "paging": false,
    //         "sAjaxSource": "api/competition/"+id+"/winner-data",
    //         "oLanguage": {
    //             "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
    //         }
    //     });
    //     setTimeout(function(){
    //         $('#viewWinnerModal').modal('show');
    //     }, 500);
    // });

    // if($('.competition-genre').val()){
    //     $('#competition-sub-genres-block').show();
    // }

    // $(document).on("change", ".competition-genre", function(){
    //     var values = $('.competition-genre').val();
    //     if(values){
    //         loadRemoveSubGenres(values);
    //         $('#competition-sub-genres-block').show();
    //     }
    //     else{
    //         $('select[name="sub_genre"]').prop('selectedIndex', 0);
    //         $('#competition-sub-genres-block').hide();
    //     }
    // });

    // function loadRemoveSubGenres(values){

    //     var html = '';
    //     html = '<option value="">- Please select -</option>';
    //     $.ajax({
    //         url: base_url+'/admin/api/competition/sub_genres',
    //         data: {genreId : values},
    //         type: 'post',
    //         success: function(data) {

    //             $.each( data, function( key, value ) {
    //                 html += '<option value="'+key+'">'+value+'</option>';
    //             });
    //             $('#competition_sub_genre').html(html);
    //         },
    //         error: function() { // fires after the ajax is fully complete
    //             console.log('Something wrong');
    //         }
    //     });

    // }

    $("form.competitionForm").validate({
        errorPlacement: function(error, element) {
                if (element.hasClass('upload')) {
                    error.insertAfter($(element).parent());
                } else {
                    error.insertAfter(element);
                }
            },
        rules: {
                // cover_image: {required : true},
                // name: {required : true},
                // track_name: {required : true},
                track_id: {required : true},
                start_date: {required : true},
                end_date: {required : true},
                announcement_date: {required: true},
                price_1: {required: true},
                price_2: {required: true},
                price_3: {required: true},
                // original_track: {required: true},
                stem_file: {required: true},
            },
        messages:{
                // cover_image: {required : "Please select cover image"},
                // name: {required : "Please enter competition name"},
                // track_name: {required : "Please enter track name"},
                track_id: {required : "Please select track"},
                start_date: {required : "Please select start date"},
                end_date: {required : "Please select end date"},
                announcement_date: {required: "Please select announcement date"},
                price_1: {required: "Please enter first price"},
                price_2: {required: "Please enter second price"},
                price_3: {required: "Please enter third price"},
                // original_track: {required: "Please select original track"},
                stem_file: {required: "Please select steam file"}, 
            },
            errorPlacement: function(error, element) {
                if (element.context.type == 'file' || element.context.type == 'select-multiple') {
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

    $("form.competitionEditForm").validate({
        rules: {
                // name: {required : true},
                // track_name: {required : true},
                start_date: {required : true},
                end_date: {required : true},
                announcement_date: {required: true},
                price_1: {required: true},
                price_2: {required: true},
                price_3: {required: true}
            },
        messages:{
                // name: {required : "Please enter competition name"},
                // track_name: {required : "Please enter track name"},
                start_date: {required : "Please select start date"},
                end_date: {required : "Please select end date"},
                announcement_date: {required: "Please select announcement date"},
                price_1: {required: "Please enter first price"},
                price_2: {required: "Please enter second price"},
                price_3: {required: "Please enter third price"}
            },
            submitHandler: function (form) {
                this.submitButton.disabled = true;
                $("body").addClass('loading');
                form.submit();
            }
    });


    $("#competition-track").select2({
      maximumSelectionLength: 1,
      width: '100%',
      minimumInputLength: 2,
        ajax: {
            url: base_url+'/admin/api/get-stm-artist-tracks',
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

                    results: $.map(data.tracks, function (item) {
                        return {
                            text: item.track_name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });

    var winnerCampaignIds = $('#winnerCampaignIds').val();
    if(winnerCampaignIds){
        var trackIds = winnerCampaignIds.split(",");
    }else{
        trackIds = [];
    }

    
    $('.stm-artist-track').on("select2:unselecting", function(e){
        var unselected_value = $(this).val();
        index = trackIds.indexOf(unselected_value[0]);
        trackIds.splice(index, 1);
    });

    $('.stm-artist-track').on("select2:select", function(e){
        var selected_value = $(this).val();
        index = trackIds.indexOf(selected_value[0]);
        trackIds.push(selected_value[0]);
    });

    $(".stm-artist-track").select2({
      maximumSelectionLength: 1,
      width: '100%',
      placeholder: "Select track",
      minimumInputLength: 2,
        ajax: {
            url: base_url+'/admin/api/get-stm-artist-remix-tracks',
            type: "GET",
            delay: 100,
            data: function (params) {

                var queryParameters = {
                    term: params.term,
                    ids: trackIds
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {

                    results: $.map(data.tracks, function (item) {
                        return {
                            text: item.track_name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });




    var winnerPositionsInfo = $('#winnerPositionsInfo').val();
    if(winnerPositionsInfo){
        var positions = winnerPositionsInfo.split(",");
        $(window).load(function() {
            for (var i = positions.length - 1; i >= 0; i--) {
                $('.positionSelector').change();
            }
        });
    }else{
        positions = [];
    }



    // $('.positionSelector').on('change', function (){
    //     selected_position = $(this).val();
    //     if(selected_position){
    //         winnerPositionsInfo.push(selected_position);
    //     }
        
    // });


$(document).on('change', '.positionSelector', function(){
    positionDisabled();
});

function positionDisabled() {
    var componentArr = [];
    $(".positionSelector").each(function() {
        $('option', this).each(function() {
            var val = $(this).val();
            if (val && checkSelected($(this).val())) {
                componentArr.push(val);
                //$('.component_data option[value="' + $(this).val() + '"]').attr('disabled', true);
            } else {
                $('.positionSelector option[value="' + $(this).val() + '"]').attr('disabled', false);
            }
        });
    });
    $(componentArr).each(function(i, item) {
        $('.positionSelector option[value=' + item + ']').attr('disabled', true);
    });
}

function checkSelected(val) {
    var ret = false;
    $(".positionSelector").each(function() {
        if ($(this).val() === val) {
            ret = true;
        }
    });
    return ret;
}

$(".music-videos").select2({
      maximumSelectionLength: 1,
      width: '100%',
      placeholder: "Select video",
      minimumInputLength: 2,
        ajax: {
            url: base_url+'/admin/api/get-music-videos',
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

                    results: $.map(data.videos, function (item) {
                        return {
                            text: item.track_name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });


    $("form").submit(function() {
        $(".positionSelector :disabled").removeAttr('disabled');


            // start check position first select or not and required validation for music-video
            var isFirstPosition = 'false';
            $(".positionSelector").each(function() {
                val = $(this).val();
                if(val == 1){
                    isFirstPosition = "true";
                    $('.error_message').hide();
                }
            });

            $(".music-videos").attr('required', isFirstPosition == "true" ? true : false);

            // end check position first select or not and required validation for music-video


    });




    $("form.winnerSelectionForm").validate({
       
        ignore: [],
        rules: {
                "remix_tracks[]" : {required: true},
                "positions[]" : {required: true},
                // 'music_video' :  { required: true},
            },
        messages:{
                'remix_tracks[]': {required: "Please select track"},
                'positions[]'   : {required: "Please select position"},
                'music_video'   : {required: "Please select video"}
            },
        errorPlacement: function(error, element) {
                if (element.context.type == 'select-multiple') {
                    error.insertAfter(element.parent());
                }
                else {
                    error.insertAfter(element);
                }
            },

        submitHandler: function (form, event) {
            
            if($(".published_date").is(':checked')){
                $(".positionSelector").each(function() {
                        var val = $(this).val();
                            if(val == 1){
                                $('.error_message').hide();
                                $("body").addClass('loading');
                                form.submit();
                                this.submitButton.disabled = true;  
                            }
                });
                $('.error_message').show();
                positionDisabled();
                return false;  
                event.preventDefault();
            }else{
                form.submit();
            }

        },
        invalidHandler: function(form) {
            positionDisabled();
        }
            
    });


    



});