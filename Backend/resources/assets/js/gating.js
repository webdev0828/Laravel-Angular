
$(document).ready(function(){
	$(".checker").removeClass("checker");

	if(jQuery.fn.bootstrapSwitch) {
	    $('[name="soundcloud-gating"]').bootstrapSwitch({
	    	onSwitchChange: function(event, state) {
			    // Return false to prevent the toggle from switching.
			    if (state) {
		           	window.location.href = 'connect-soundcloud';
		        } else {
		          	var provider = 'soundcloud';
			        $.ajax({
			            url: 'socialite/remove/'+provider,
			            type: "get",
			            success: function(data, textStatus, jqXHR) {
			                state = false;
			            },
			            error: function(request, status, error) {
			            }
			        });
		        }
		  	}
	    });
	}
});

