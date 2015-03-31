$(document).ready(function () {

    /**
     * Handles click to display the modal
     */
    $("#submit-form-login-modal").click(function (e) {
        e.preventDefault();

        //authentification
        var isLoggedIn = checkLogin($('#form-login-modal'));


        $('#login-form-modal').modal('hide');
        if(isLoggedIn){
            //Notify login
            notifySuccess("Vous êtes connecté.");

            //reload page
            window.location
                .reload()
                .delay(3000)
                .fadeOut();

        } else {
            notifyError("Problème de connexion.");
        }
    });


    /**
     * Handles click to display the modal
     */
    $("#submit-form-login").click(function (e) {
        e.preventDefault();

        //authentification
        var isLoggedIn = checkLogin($('#form-login'));

        if(isLoggedIn){
            //Notify login
            notifySuccess("Vous êtes connecté.");

            //reload page
            window.location
                .reload()
                .delay(3000)
                .fadeOut();

        } else {
            notifyError("Problème de connexion.");
        }
    });

    /**
     * checks for the authentification
     *
     * @returns {boolean}
     */
    function checkLogin(form) {

        var response = false;
        $.ajax({
            type        : form.attr( 'method' ),
            url         : form.attr( 'action' ),
            data        : form.serialize(),
            dataType    : 'json',
            async       : false,
            success: function (data) {
                if (data.has_error == true) {
                    //Login KO
                    response = false;
                }
                else  {
                    //login OK
                    response = true;
                }
            }
        });

        return response;
    }


});