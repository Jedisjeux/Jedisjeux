$(function() {
    if ($('#jdj_comptabundle_book_entry_creditedOrDebited').length > 0) {

        var $creditedFormGroup =  $('#jdj_comptabundle_book_entry_creditedAt').parent().parent();
        var $debitedFormGroup =  $('#jdj_comptabundle_book_entry_debitedAt').parent().parent();

        debitedOrCreditedHandler();
    }

    function debitedOrCreditedHandler() {

        debitedOrCreditedChange($(this).val());

        $('#jdj_comptabundle_book_entry_creditedOrDebited').change(function() {
            debitedOrCreditedChange($(this).val());
        });
    }

    function debitedOrCreditedChange(value) {
        if ('debited' === value) {
            $creditedFormGroup.show('slow');
            $debitedFormGroup.hide('slow')
        } else {
            $debitedFormGroup.show('slow');
            $creditedFormGroup.hide('slow')
        }
    }
});