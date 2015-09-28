$(function() {

    var $creditedFormGroup =  $('#jdj_comptabundle_book_entry_creditedAt').parent().parent();
    var $debitedFormGroup =  $('#jdj_comptabundle_book_entry_debitedAt').parent().parent();
    var $selectCreditOrDebit = $('#jdj_comptabundle_book_entry_creditedOrDebited');

    if ($selectCreditOrDebit.length > 0) {

        debitedOrCreditedHandler();
    }

    function debitedOrCreditedHandler() {

        debitedOrCreditedChange($selectCreditOrDebit.val());
        $selectCreditOrDebit.change(function() {
            debitedOrCreditedChange($(this).val());
        });
    }

    function debitedOrCreditedChange(value) {
        if ('debited' === value) {
            $debitedFormGroup.hide('slow');
            $creditedFormGroup.show('slow');

        } else {
            $creditedFormGroup.hide('slow');
            $debitedFormGroup.show('slow');
        }
    }

    /**
     * datePickers from the form
     */
    $('#datetimepicker11').datetimepicker({
        format: 'YYYY-MM-DD'
    });
});