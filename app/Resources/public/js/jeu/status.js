$(document).ready(function () {


    /**
     * Handles click to display to change the game status
     */
    $(".changeStatus").click(function (e) {
        e.preventDefault();
        changeGameStatus($(this).data('jeu-id'), $(this).data('status'));
    });


    /**
     * call the change status service
     *
     * @param jeuId
     * @param status
     */
    function changeGameStatus(jeuId, status) {

        $.ajax({
            url: Routing.generate('change_status', {jeu: jeuId, status: status}),
            type: 'POST',
            data: {},
            async: false
        })
            .done(function (data) {

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