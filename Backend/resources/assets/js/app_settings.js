$( document ).ready(function() {
	if ($.isFunction($.fn.dataTable)) {
        $.extend( true, $.fn.dataTable.defaults, {
            "fnDrawCallback": function() {
                var pageCount = Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength);
                if (pageCount > 1)  {
                     $('.dataTables_paginate').show();
                } else {
                     $('.dataTables_paginate').hide();
                }
            }
        });
    }
});