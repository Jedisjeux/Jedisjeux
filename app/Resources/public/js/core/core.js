$(document).ready(function () {

    /**
     * This function displays a success notification
     *
     * @param message
     */
    window.notifySuccess = function(message) {

        $('.message-notify', '#notify').html(message);

        $('#notify')
            .removeClass('alert-danger')
            .addClass('alert-success')
            .show()
            .delay(5000)
            .fadeOut();

        $('html, body').animate({ scrollTop: 0 }, 'fast');


    }


    /**
     * This function displays an error notification
     *
     * @param message
     */
    window.notifyError = function(message) {

        $('.message-notify', '#notify').html(message);

        $('#notify')
            .removeClass('alert-success')
            .addClass('alert-danger')
            .show()
            .delay(5000)
            .fadeOut();

        $('html, body').animate({ scrollTop: 0 }, 'fast');
    }
});