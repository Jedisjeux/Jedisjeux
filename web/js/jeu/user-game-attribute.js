$(document).ready(function () {

    /**
     * Handles click on owned
     */
    $(".owned").click(function (e) {
        e.preventDefault();

        handleUsergameAttribute($(this), 'owned');
    });

    /**
     * Handles click on wanted
     */
    $(".wanted").click(function (e) {
        e.preventDefault();

        handleUsergameAttribute($(this), 'wanted');
    });

    /**
     * Handles click on played
     */
    $(".played").click(function (e) {
        e.preventDefault();

        handleUsergameAttribute($(this), 'played');
    });

    /**
     * Handles click on favorite
     */
    $(".favorite").click(function (e) {
        e.preventDefault();

        handleUsergameAttribute($(this), 'favorite');
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


});