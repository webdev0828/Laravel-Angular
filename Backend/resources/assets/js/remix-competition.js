$( document ).ready(function() {

    var remix_competition_table = $('#remix-competition-table');
    remix_competition_table.dataTable({
        processing: true,
        serverSide:true,
        searchDelay: 1000,
        "sAjaxSource": "api/remix-competition",
        "order" : [[0, "desc"]],
        "bAutoWidth": false,
        "columnDefs": [
            { width: 200, targets: 1 }
        ],
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>",
            "sSearch": "Track Search: "
        },
        fnServerData: function (sSource, aoData, fnCallback)
        {
            var competitionId = $("#remix_competition_name").val();
            var status = $("input[type='radio'].radio-filter:checked").val();


            Array.prototype.push.apply(aoData, [
                {"name": "competition_id", "value": competitionId},
                {"name": "status", "value": status},
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

    $(document).on("click", ".submit-btn", function() {
        $.ajax({
            url: 'api/remix-competition/change-state',
            type: "post",
            data: $('.competitionForm').serialize(),
            success: function(data) {
                /*remix_competition_table.api().ajax.reload();*/

                var oriVal = $('.check-status .sub-menu .remix-competition').find('span').text();
                if (oriVal == 1 || oriVal == data['count']) {
                    $('.check-status .sub-menu .remix-competition').find('span').remove();
                } else {
                    $('.check-status .sub-menu .remix-competition').find('span').text(oriVal - data['count']);
                }
            },
            error: function() {

            }
        });
    });

    // remix_competition_table.api().ajax.reload(function(){
    //     $('#remix-competition-table tbody tr:first input[type="radio"]').prop("checked", true);
    //     // $('input[type="radio"]:checked').prop("checked", true);
    // });

    $('.competition-name-filter').on('change', function (){
        remix_competition_table.api().ajax.reload();
    });
    $('.radio-filter').on('click', function (){
        remix_competition_table.api().ajax.reload();
    });



    var competitionName = $("#remix_competition_name").val();
    if(competitionName){
        $(".submit-btn").show();
    }

    $('.competition-name-filter').on("select2:unselect", function(e){
        $(".submit-btn").hide();
    });

    $('.competition-name-filter').on("select2:select", function(e){
        $(".submit-btn").show();
    });



    $("#remix_competition_name").select2({
        maximumSelectionLength: 1,
        width: '100%',
        minimumInputLength: 1,
        placeholder: 'Competition Name',
        ajax: {
            url: base_url+'/admin/get-competition-name',
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

                    results: $.map(data.competitionList, function (item) {
                        return {
                            text: item.track_name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });


    // $(document).on("click", ".radio-status", function(){
    //     var status = $(this).val();
    //     var id = $(this).attr('data-id');
    //     $.ajax({
    //         url: 'api/remix-competition/change-state',
    //         type: "post",
    //         data: {
    //                 'status': status,
    //                 'id': id
    //             },
    //         success: function(data, textStatus, jqXHR) {
    //             if(data.msg == undefined){
    //                 toastr.error('',''+data);
    //             }
    //             remix_competition_table.api().ajax.reload();

    //         },
    //         error: function(request, status, error) {
                
    //         }
    //     });
    // });


});