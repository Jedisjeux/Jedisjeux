$(function() {

    $( ".mainSearch" ).imgcomplete({
        source: Routing.generate('app_api_search_autocomplete'),
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