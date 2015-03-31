$(document).ready(function () {

    /**
     * Handles click on owned
     */
    $(".owned").click(function (e) {
        e.preventDefault();

        if($(this).data("uid") <= 0) {
            $('#login-form-modal').modal('show');
        } else {
            handleUsergameAttribute($(this), 'owned');
        }
    });

    /**
     * Handles click on wanted
     */
    $(".wanted").click(function (e) {
        e.preventDefault();

        if($(this).data("uid") <= 0) {
            $('#login-form-modal').modal('show');
        } else {
            handleUsergameAttribute($(this), 'wanted');
        }
    });

    /**
     * Handles click on played
     */
    $(".played").click(function (e) {
        e.preventDefault();

        if($(this).data("uid") <= 0) {
            $('#login-form-modal').modal('show');
        } else {
            handleUsergameAttribute($(this), 'played');
        }
    });

    /**
     * Handles click on favorite
     */
    $(".favorite").click(function (e) {
        e.preventDefault();

        if($(this).data("uid") <= 0) {
            $('#login-form-modal').modal('show');
        } else {
            handleUsergameAttribute($(this), 'favorite');
        }
    });


    /**
     * handle the back end update and modify display
     *
     * @param element
     * @param classElement
     */
    function handleUsergameAttribute(element, classElement)
    {
        /**
         * set the user game attribute
         */
        var isset = setUserGameAttribute(
            element.data("jeu-id"),
            element.data("uid"),
            classElement
        );

        if (isset) {
            modifyDisplay("." + classElement);
        }
    }

    /**
     * change the display by adding a class
     *
     * @param classElement
     */
    function modifyDisplay(classElement)
    {
        notifySuccesUserGameAttribute(classElement);
        if ($(classElement).hasClass("btn-default")) {
            $(classElement).addClass("btn-yellow");
            $(classElement).removeClass("btn-default");
        } else {
            $(classElement).addClass("btn-default");
            $(classElement).removeClass("btn-yellow");
        }

    }


    /**
     * Set the usergameattribute
     *
     * @param jeuId
     * @param userId
     * @param attribute
     */
    function setUserGameAttribute(jeuId, userId, attribute) {
        var response = false;

        $.ajax({
            url: Routing.generate('usergameattribute_' + attribute, { jeu: jeuId, user: userId }),
            type: 'GET',
            async: false
        })
            .done(function () {
                response = true;

            })
            .fail(function () {
                alert("An error occured. Please try again later.");
            });
        return response;
    }

    /**
     * This function displays a success notification
     *
     * @param attribute
     */
    function notifySuccesUserGameAttribute(attribute)
    {
        var message = "";

        switch(attribute) {
            case ".owned":
                if ($(attribute).hasClass("btn-default")) {
                    message = "Le Jeu a été ajouté à votre ludothèque.";
                } else {
                    message = "Le Jeu a été supprimé de votre ludothèque.";
                }
                break;
            case ".wanted":
                if ($(attribute).hasClass("btn-default")) {
                    message = "Le Jeu a été ajouté à votre liste d'envie.";
                } else {
                    message = "Le Jeu a été supprimé de votre liste d'envie.";
                }
                break;
            case ".played":
                if ($(attribute).hasClass("btn-default")) {
                    message = "Le Jeu a été ajouté à la liste de jeu auxquels vous avez joué.";
                } else {
                    message = "Le Jeu a été supprimé de la liste de jeu auxquels vous avez joué.";
                }
                break;
            case ".favorite":
                if ($(attribute).hasClass("btn-default")) {
                    message = "Le Jeu a été ajouté à vos coup de coeur.";
                } else {
                    message = "Le Jeu a été supprimé de vos coup de coeur.";
                }
                break;
        }
        /*$('#notify')
            .removeClass('alert-danger')
            .addClass('alert-success')
            .show()
            .delay(5000)
            .fadeOut();

        $('#message-jeu').html(message);
        $('html, body').animate({ scrollTop: 0 }, 'fast');*/

        notifySuccess(message);

    }


});