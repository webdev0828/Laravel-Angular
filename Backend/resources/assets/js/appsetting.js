$( document ).ready(function() {
	if ($.isFunction($.fn.dataTable)) {
        $.extend( true, $.fn.dataTable.defaults, {
            "aoColumnDefs" : [ {
                "bSortable" : false,
                "aTargets" : [ "no-sort" ]
            } ]
        });
    }
});