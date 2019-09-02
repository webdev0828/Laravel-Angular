$( document ).ready(function() {

	var video_template_table = $('#video_template_table');

    video_template_table.dataTable({
        processing: true,
        serverSide:true,
        'sAjaxSource':'api/video_template',
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    $(document).on("click", "#addVideoTemplate", function(event){
        $.ajax({
                url: base_url+'/admin/video_template/create',
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#templatesModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    video_template_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
        }); 
    });


    $(document).on("click", "a.videoTemplateEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#templatesModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    video_template_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
        }); 
    });


    function video_template_validate(){
        $("form#videoTemplatesForm").validate({
            rules: {
                        template_name: {required : true, maxlength: 25},
                        template_url:{required:true, url: true},
                    },
                messages:{
                        template_name: {required:"Please enter template name"},
                        template_url: {required:"Please enter template url"},
                    }
        });
    }
});