$(document).ready(function () {


    /**
     * Handles click to display to change the game status
     */
    $(".acceptStatus").click(function (e) {
        e.preventDefault();
        changeGameStatus($(this).data('jeu-id'), $(this).data('status'), $(this).data('action'), "");
    });

    /**
     * Handles click to display to change the game status
     */
    $(".refuseStatus").click(function (e) {
        e.preventDefault();
        changeGameStatus($(this).data('jeu-id'), $(this).data('status'), $(this).data('action'), $("#inputCommentRefuse").val());
    });

    /**
     * Call the change status service
     *
     * @param jeuId
     * @param status
     * @param action
     * @param comment
     */
    function changeGameStatus(jeuId, status, action, comment) {

        $.ajax({
            url: Routing.generate('change_status', {jeu: jeuId, status: status, action: action}),
            type: 'POST',
            data: {comment: comment},
            async: false
        })
            .done(function (data) {
                $('#refuseStatusModal').modal('hide');
                $('.changeStatus').hide();
                $('.changeStatusContainer').hide();

                notifySuccess(data.message);

                //Reload the page
                window.location
                    .reload()
                    .delay(5000)
                    .fadeOut();


            })
            .fail(function (xhr) {
                var data = eval("(" + xhr.responseText + ")");
                notifyError(data.message);
            });
    }


});