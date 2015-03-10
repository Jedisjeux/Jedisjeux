$(function() {

    if ($('#user-review').length > 0) {
        $('html, body').animate({
            scrollTop:$("#user-review").offset().top
        }, 'fast');
    }

});