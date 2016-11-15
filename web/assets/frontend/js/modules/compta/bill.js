$(function() {

    "use strict";

    var $form = $('form[name=jdj_comptabundle_bill_payment]');

    if ($form.length > 0) {
        /**
         * datePickers from the form
         */
        $('.billPaymentPaidAt').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    }

});