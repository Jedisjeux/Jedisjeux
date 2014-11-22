$(function() {

    $( "#main-search" ).imgcomplete({
        source: "/app_dev.php/recherche/autocomplete"/*Routing.generate('search_autocomplete')*/,
        minLength: 2,
        delay: 100,
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        select: function( event, ui ) {
            window.location = ui.item.href;
            // prevent value inserted on focus
            return false;
        }
    });

});