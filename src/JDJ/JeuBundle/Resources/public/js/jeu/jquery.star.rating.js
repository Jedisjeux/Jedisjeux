(function($){

    $.fn.starRating=function(){
        var oldNote;
        var _this = $(this);


        // la note actuelle (si l'utilisateur a déjà noté l'élément)
        // se trouve dans la balise p
        oldNote = parseInt(_this.find('p').text());


        // pour chaque étoile, on vient affecter la classe la moitié gauche ou droite de l'étoile
        _this.find('li').each(function( index ) {
            if (index%2 == 0) {
                $(this).addClass("starLeft");

            }
            else {
                $(this).addClass("starRight");
            }
            changeNote($(this), oldNote);
        });

        // pour chaque étoile, on affecte une fonction sur le enter de la souris
        // et on change la note correspondante au contenu du span
        _this.find('li').mouseenter(function() {

            var newNote = parseInt($(this).find('span').text());
            var li = $(this);

            setTimeout(function(){
                changeNote(li, newNote);
            }, 10);


        });

        _this.mouseleave(function() {
            var li = $(this);
            setTimeout(function(){
                changeNote(li, oldNote);
            }, 10);
        });


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

    }

})(jQuery);