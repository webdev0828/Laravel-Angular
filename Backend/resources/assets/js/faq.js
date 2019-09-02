$( document ).ready(function() {
	$("form.faqsForm").validate({
        rules: {
                title: {required : true}
            },
        messages:{
                title: {required : "Please enter title"}
            },
            submitHandler: function (form) {
                this.submitButton.disabled = true;
                $("body").addClass('loading');
                form.submit();
            }
    });
});