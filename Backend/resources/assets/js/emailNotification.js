$( document ).ready(function() {
    $('.email-notification').change(function() {
        var isChecked = $('.email-notification').prop('checked') ? 1 : 0;
        $.ajax({
            dataType: 'json', 
            type: 'GET',
            url: 'api/email-notification/change-status',
            data: {email_send: isChecked},
            success: function() {}
        });

    })
});