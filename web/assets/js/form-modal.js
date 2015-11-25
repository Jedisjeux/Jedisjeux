$(function() {

    "use strict";

    $('.form-modal-confirm').click(function() {
        var button = this;
        var $form = $('form', $(button).parent().prev());
        $form.submit();
    });

});