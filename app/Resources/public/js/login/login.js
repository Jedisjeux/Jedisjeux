$(document).ready(function () {

    /**
     * Handles click to display the modal
     */
    $("#submit-form-login-modal").click(function (e) {
        e.preventDefault();

        //authentification
        checkLogin($('#formLoginModal'));

        $('#login-form-modal').modal('hide');

    });


    /**
     * Handles click to display the modal
     */
    $("#submit-form-login").click(function (e) {
        e.preventDefault();

        //authentification
        checkLogin($('#formLogin'));

    });

    /**
     * checks for the authentification
     *
     * @returns {boolean}
     */
    function checkLogin($form) {

        var isLoggedIn = false;
        $.ajax({
            type        : $form.attr( 'method' ),
            url         : $form.attr( 'action' ),
            data        : $form.serialize(),
            dataType    : 'json',
            async       : false,
            success: function (data) {
                if (data.has_error == true) {
                    //Login KO
                    isLoggedIn = false;
                }
                else  {
                    //login OK
                    isLoggedIn = true;
                }
            }
        });

        console.log(isLoggedIn);

        notifyLogin(isLoggedIn);

    }


    function notifyLogin(isLoggedIn)
    {
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

    }

});