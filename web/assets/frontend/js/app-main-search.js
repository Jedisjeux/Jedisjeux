$(function() {

    // $( ".mainSearch" ).imgcomplete({
    //     source: function( request, response ) {
    //         var term = request.term;
    //
    //         $.getJSON( Routing.generate('app_api_search_autocomplete'), request, function( data, status, xhr ) {
    //             var items = [];
    //
    //             $.each(data.items, function(index, item) {
    //                 items.push({
    //                     "value": item.type,
    //                     "label": item.name,
    //                     "image": item.image ? item.image.path : null,
    //                     "href": getUrl(item)
    //                 });
    //             });
    //
    //             response( items );
    //         });
    //     },
    //     minLength: 2,
    //     delay: 100,
    //     focus: function() {
    //         // prevent value inserted on focus
    //         return false;
    //     },
    //     select: function( event, ui ) {
    //         window.location = ui.item.href;
    //         // prevent value inserted on focus
    //         return false;
    //     }
    // });

    function getUrl(item) {
        switch (item.type) {
            case 'article':
                return "/article/" + item.article.slug;
            case 'person':
                return "/ludographie/" + item.person.slug;
            case 'product':
                return "/jeu-de-societe/" + item.product.slug;
            case 'topic':
                return "/topics/" + item.topic.id + '/posts/';
            default:
                return '';
        }
    }

    $( ".mainSearch" ).select2({
        theme: "bootstrap",
        placeholder: "Recherche Jeu, auteur, éditeur",
        allowClear: true,
        minimumInputLength: 1,
        ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
            url: Routing.generate('app_api_search_autocomplete'),
            dataType: 'json',
            quietMillis: 250,
            processResults: function (data, page) { // parse the results into the format expected by Select2.
                var items = [];

                $.each(data.items, function(index, item) {
                    items.push({
                        "id": item.type,
                        "text": item.name,
                        "image": item.image ? item.image.path : "//ssl.gstatic.com/accounts/ui/avatar_2x.png",
                        "href": getUrl(item)
                    });
                });

                // since we are using custom formatting functions we do not need to alter the remote JSON data
                return { results: items };
            },
            cache: true
        },
        templateResult: function (data) {
            if (!data.image) {
                return data.text;
            }

            return $(
                '<span><img src="' + data.image + '" class="img-flag" /> ' + data.text + '</span>'
            );
        }
    }).on('select2:select', function (event) {
        var data = event.params.data;

        location.href = data.href;
    });

});