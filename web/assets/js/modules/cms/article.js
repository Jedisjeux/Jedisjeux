$(function() {
    // Document is ready

    "use strict";

    if ($("#articleEditing").length > 0) {

        var blocks = [];
        cloneBlocks();
        initModalEvents();
        initButtonEvents();
        initSortable();
    }

    function cloneBlocks() {
        $('.block', '.tab-content').each(function() {
            blocks.push($(this).clone());
        });
    }

    function initModalEvents() {
        $('#articleModal').on('show.bs.modal', function () {
            $('.tab-pane', '.tab-content').each(function(index) {

                var block = blocks[index].clone();
                block.attr('contenteditable', true);
                $(this).html(block);
            });
        });
    }

    function initButtonEvents() {

        $('.adding-block').click(function() {
            var $newBlock = $('.block', '.tab-content .active').clone();
            $newBlock.appendTo('#articleContainer');
        });
    }

    function initSortable() {
        $( "#articleContainer" )
            .sortable()
            .disableSelection();
    }
});