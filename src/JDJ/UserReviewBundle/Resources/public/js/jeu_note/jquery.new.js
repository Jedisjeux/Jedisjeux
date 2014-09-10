/**
 * Created by loic_425 on 22/06/2014.
 */
$(function(){

    if ($(".starRating").length > 0) {
        starRatingHandler();
    }

    function starRatingHandler()
    {
        $(".starRating").starRating();

        $(".starRating a").click(function(e){
            e.preventDefault();

            var that = this;
            var href = $(".starRating").data("href");
            var note_id  = $(that).data("note-id");
            var jeu_id = $(".starRating").data("jeu-id");

            /**
             * Ajax Calling of the JeuNote:createAction
             */
            $.post( href, {
                idNote: note_id,
                idJeu: jeu_id
            } )
                .done(function(data) {
                    $(".starRating p").html(data.valeur);
                    changeNote($(that).parent(), data.valeur);

                    /**
                     * TODO
                     * pnotify install
                     */
                    $("#alert-box-success").html("Votre note a bien été prise en compte");
                    $("#alert-box-success").show();
                });

        })
    }

    /**
     * FIX
     * Can we call the included changeNote function of the starRating function ?
     */
    function changeNote(li, note) {

        var starValue = 0;

        li.parent().find('li').each(function( ) {

            starValue = parseInt($(this).find('span').text());

            $(this).removeClass("selected");
            if (starValue <= note) {

                $(this).addClass("selected");
            }
        });
    }

});