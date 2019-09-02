$( document ).ready(function() {

    var genres_table = $('#moods_table').dataTable({
        processing: true,
        serverSide:true,
        "sAjaxSource": "api/moods",
        "order": [[ 2, "desc" ]],
        "columnDefs": [{ "visible": false, "targets": 2 }],
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });


    




    // // ---------- Sub Genres ----------

    
    $(document).on("click", "a.vibe-delete", function(event){
        var id = $(this).attr('data-id');
        var url = $(this).attr('id');
        $.ajax({
                url: '/admin/api/mood-exist',
                type: 'post',
                data: {id:id},
                success: function(data, textStatus, jqXHR) {
                    if(data == 'notExist'){
                        if (confirm("Are you sure, you want to delete record?") == true) {
                            $.ajax({
                                url: url,
                                type: 'post',
                                data: {_method: 'DELETE' },
                                success: function(data, textStatus, jqXHR) {
                                    window.location.href = location.href;
                                },
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                }
                            }); 
                        }
                        else
                        {
                            return false;
                        } 
                    }
                    else{
                        window.location.href = location.href;
                    }
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            });
    });

    $(document).on("click", "a.moodsEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $("#name-input").val(data.moods.name);
                    $("#desc-input").val(data.moods.description);
                    $("#moodId").val(data.moods.id)
                    $('#moodModal').modal();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
        }); 
    });

    $("form#moodForm").validate({
        rules: {
                    name: {required : true, maxlength: 25},
                    description:{required:true},
                },
            messages:{
                    name: {required:"Please enter name"},
                    description: {required:"Please enter description"},
                },
                submitHandler: function (form) {
                    this.submitButton.disabled = true;
                    $("body").addClass('loading');
                    form.submit();
                }
    });


    // $('#save-mood').click(function(){
    //     if(!($('input[name=name]').val() && $('textarea[name=description]').val())) {
    //         if($('input[name=name]').val() == ""){
    //             $('.name-error').html("Please enter Mood");
    //         }else{
    //             $('.name-error').html("");
    //         }

    //         if($('textarea[name=description]').val() == ""){
    //             $('.description-error').html("Please enter description");
    //         }else{
    //             $('.description-error').html("");
    //         }
    //         return false;    
    //     }
    // });

    $(document).on("hidden.bs.modal", '#moodModal', function () {
        $('#moodForm')[0].reset();
        $(".name-error").empty();
        $(".description-error").empty();
    });
});