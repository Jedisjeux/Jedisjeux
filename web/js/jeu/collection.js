$(document).ready(function () {


    initializeForms();


    /**
     * Handles the radio button to choose the method to add a game to a list
     */
    $('input[type=radio][name=choose-list]').change(function () {
        if ($("#radio-existing-list").is(':checked') == true) {
            $("#add-in-list").show();
            $("#create-list").hide();
        } else {
            $("#add-in-list").hide();
            $("#create-list").show();
        }
    });


    /**
     * Handles click to add a new collection
     */
    $("#add-list-collection").click(function (e) {
        e.preventDefault();

        if ($('#input-list-name').val() !== "") {
            createCollection($(this).data('jeu-id'), $(this).data('uid'), $('#input-list-name').val(), $('#input-list-description').val());
        } else {
            $('#form-group-name-list').addClass('has-error');
        }
    });


    /**
     * Handles click to add a new collection
     */
    $("#add-game-collection").click(function (e) {
        e.preventDefault();

        if(Array.isArray($("#input-existing-collection").val()))
        {
            var jeuId = $(this).data('jeu-id');
            $.each($("#input-existing-collection").val(), function( index, value ) {
                addGameCollection(jeuId, value);
            });
        }
        $('#collection-modal').modal('hide');
        initializeForms();
        notifySucces("Le jeu a été rajoutée à mes listes.");


    });


    /**
     * create a new list with the game
     *
     * @param jeuId
     * @param userId
     * @param name
     * @param description
     * @returns {boolean}
     */
    function createCollection(jeuId, userId, name, description) {
        var response = false;

        $.ajax({
            url: Routing.generate('create_collection', {jeu: jeuId, user: userId}),
            type: 'POST',
            data: {name: name, description: description},
            async: false
        })
            .done(function () {
                response = true;

                $('#collection-modal').modal('hide');
                notifySucces("La liste a été créé.");
                /** if no list yet reload page */
                if ($('#add-in-list').length == 0) {

                    window.location
                        .reload()
                        .delay(3000)
                        .fadeOut();
                }

                reloadUserLists(userId);
                initializeForms();


            })
            .fail(function () {
                notifyError("Une erreur s'est produite. Merci de réessayer plus tard.");
                $('#collection-modal').modal('hide')
            });
        return response;
    }


    /**
     * Add a game to an existing collection
     *
     * @param jeuId
     * @param collectionId
     * @returns {boolean}
     */
    function addGameCollection(jeuId, collectionId) {
        var response = false;

        $.ajax({
            url: Routing.generate('add_game_collection', {jeu: jeuId, collection: collectionId}),
            type: 'GET',
            async: false
        })
            .done(function () {
                response = true;
            })
            .fail(function () {
                notifyError("Une erreur s'est produite. Merci de réessayer plus tard.");
                $('#collection-modal').modal('hide');
            });
        return response;
    }


    /**
     * reload the user lists
     *
     * @param userId
     * @returns {boolean}
     */
    function reloadUserLists(userId) {

        $.ajax({
            url: Routing.generate('user_list', {user: userId}),
            type: 'GET',
            async: false
        })
            .done(function (data) {
                var tabCollection = data.tabCollection;
                reloadSelectDOM(tabCollection)
            })
            .fail(function () {
                notifyError("Une erreur s'est produite. Merci de réessayer plus tard.");
            });

    }

    /**
     * This function reload the select in the dom with the user lists
     *
     * @param tabCollection
     */
    function reloadSelectDOM(tabCollection) {

        selectCollection = "";
        tabCollection.forEach(function (collection, index) {
            selectCollection += '<option value="' + collection.id + '">' + collection.name + '</option>';
        });

        $("#input-existing-collection").html(selectCollection);
    }

    /**
     * This function initialize the forms in the modals
     */
    function initializeForms(){
        /** initialize */

        $('#input-existing-collection').multipleSelect({
            filter: true,
            isOpen: true,
            keepOpen: true
        });

        $("#radio-existing-list").prop("checked", true)
        $("#add-in-list").show();
        if ($('#add-in-list').length > 0) {
            $("#create-list").hide();
        }
        $('#input-list-name').val("");
        $('#input-list-description').val("");
        $('#form-group-name-list').removeClass('has-error');
    }

    /**
     * This function displays a success notification
     *
     * @param message
     */
    function notifySucces(message)
    {
        $('#notify-jeu')
            .removeClass('alert-error')
            .addClass('alert-success')
            .show()
            .delay(5000)
            .fadeOut();

        $('#message-jeu').html(message);
        $('html, body').animate({ scrollTop: 0 }, 'fast');

    }

    /**
     * This function displays an error notification
     *
     * @param message
     */
    function notifyError(message)
    {
        $('#notify-jeu')
            .removeClass('alert-success')
            .addClass('alert-error')
            .show()
            .delay(5000)
            .fadeOut();

        $('#message-jeu').html(message);
        $('html, body').animate({ scrollTop: 0 }, 'fast');

    }

});