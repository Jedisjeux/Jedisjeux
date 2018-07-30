$.widget( "custom.imgcomplete", $.ui.autocomplete, {
    _renderItem: function( ul, item ) {
        return $( "<li>" )
            .css('clear', 'both')
            .append( $('<a>')
                .attr("href", item.href)
                .append($('<img>')
                    .addClass('img-responsive')
                    .addClass('img-thumbnail')
                    .attr('src', item.image)
                )
                .append($('<span>')
                    .html(item.label)
                )
            )
            .appendTo( ul );
    }
});