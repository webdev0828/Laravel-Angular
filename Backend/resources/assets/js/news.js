$( document ).ready(function() {
	$("form.newsForm").validate({
        errorPlacement: function(error, element) {
                if (element.hasClass('upload')) {
                    error.insertAfter($(element).parent('.fileUpload'));
                } else {
                    error.insertAfter(element);
                }
            },
        rules: {
                category_id: {required : true},
                title: {required : true},
                tags: {required : true},
                image_name: {required : true}
            },
        messages:{
                category_id: {required : "Please select news category"},
                title: {required : "Please enter title"},
                tags 	: {required : "Please enter tags"},
                image_name: {required : "Please select image"} 
            },
        submitHandler: function(form) {
            if(!$('.TokensContainer .Token').length)
            {
                var tagParent = $(".TokensContainer").parent();
                $( '<label class="error" for="tag">Please enter tag</label>').appendTo( tagParent );
                return false;    
            }
            else{
                if($('#image-valid').val() == 1)
                {
                    this.submitButton.disabled = true;
                    $("body").addClass('loading');
                    form.submit();
                }
                else
                {
                    $( '<label class="error" for="image_name">Please enter tag</label>').appendTo('.upload-error');
                }
                
            }          
        }
    });

    $("form.newsEditForm").validate({
        errorPlacement: function(error, element) {
                if (element.hasClass('upload')) {
                    error.insertAfter($(element).parent('.fileUpload'));
                } else {
                    error.insertAfter(element);
                }
            },
        rules: {
                category_id: {required : true},
                title: {required : true},
                tags: {required : true}
            },
        messages:{
                category_id: {required : "Please select news category"},
                title: {required : "Please enter title"},
                tags    : {required : "Please enter tags"} 
            },
        submitHandler: function(form) {
            if(!$('.TokensContainer .Token').length)
            {
                var tagParent = $(".TokensContainer").parent();
                $( '<label class="error" for="tag">Please select image from 1200x600px and more.</label>').appendTo( tagParent );
                return false;    
            }
            else{
                this.submitButton.disabled = true;
                $("body").addClass('loading');
                form.submit();
            }          
        }
    });





    $(document).on('change', "#newsForm #newsImage", function() {
        readURL(this, '#newsPhoto');
    });

    function readURL(input, destination) {
        $('.upload-error').empty();
        $('#image-valid').val(1);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var img = new Image;
            var path = '';
            reader.onload = function(e) {
                path = e;
                img.src=reader.result;
            }
            

            img.onload = function(e) {
                if(img.width >= 1200 && img.height >=600){
                    $(destination).show();
                    $(destination).attr('src', path.target.result);
                    
                }else{
                    $('#image-valid').val(0);
                    $('.upload-error').append('<label class="error" for="tag">Please select image from 1200x600px and more.</label>');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
});