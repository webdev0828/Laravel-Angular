$( document ).ready(function() {

    var genres_table = $('#genres_table').dataTable({
        processing: true,
        serverSide:true,
        "sAjaxSource": "api/genres",
        "order": [[ 2, "desc" ]],
        "columnDefs": [{ "visible": false, "targets": 2 }],
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    $(document).on("click", "a.genre-delete", function(event){
        var id = $(this).attr('data-id');
        var url = $(this).attr('id');
        $.ajax({
                url: '/admin/api/genre-exist',
                type: 'post',
                data: {id:id},
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
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

    $(document).on("click", "a.sub-genre-delete", function(event){
        var id = $(this).attr('data-id');
        var parent_id = $(this).attr('data-parent-id');
        var url = $(this).attr('id');
        $.ajax({
                url: '/admin/api/sub-genre-exist',
                type: 'post',
                data: {id:id,parent_id:parent_id},
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
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

    $(document).on("click", "#addgenres", function(event){
        $.ajax({
                url: base_url+'/admin/genres/create',
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#genresModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    genres_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
        }); 
    });


    $(document).on("click", "a.genresEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#genresModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    genres_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
        }); 
    });


    function genres_validate(){
        $("form#genresForm").validate({
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
    }






    // ---------- Sub Genres ----------

    var generId = $("#generId").val();   

    var sub_genres_table = $('#sub_genres_table').dataTable({
        processing: true,
        serverSide:true,
        "sAjaxSource": "/admin/api/genres/"+generId+"/sub-genres",
        "order": [[ 2, "desc" ]],
        "columnDefs": [{ "visible": false, "targets": 2 }],
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });


    $(document).on("click", "a.subGenresEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $("#name-input").val(data.genres.name);
                    $("#desc-input").val(data.genres.description);
                    $("#subGenreId").val(data.genres.id)
                    $('#subGenresModal').modal();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
        }); 
    });
    
    $("form#subGenresForm").validate({
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

    // $('#save-sub-genre').click(function(){
    //     if(!($('input[name=name]').val() && $('textarea[name=description]').val())) {
    //         if($('input[name=name]').val() == ""){
    //             $('.name-error').html("Please enter genre");
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

    $(document).on("hidden.bs.modal", '#subGenresModal', function () {
        $('#subGenresForm')[0].reset();
        $(".name-error").empty();
        $(".description-error").empty();
    });
});