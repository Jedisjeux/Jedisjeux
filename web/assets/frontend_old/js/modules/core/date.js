$(function() {

    "use strict";

    /**
     * datePickers for the form
     */
    $('.date').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $('.time').datetimepicker({
        format: 'LT'
    })
});