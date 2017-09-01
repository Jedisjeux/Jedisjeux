(function ($) {
    'use strict';

    $.fn.extend({
        moveBlocks: function () {
            var $element = $(this);

            $element.on('click', '.app-move-up', function (event) {
                event.preventDefault();
                var $block = $(this).closest('[data-form-collection=item]');
                moveUp($block);
            });

            $element.on('click', '.app-move-down', function (event) {
                event.preventDefault();
                var $block = $(this).closest('[data-form-collection=item]');
                moveDown($block);
            });
        }
    });

    /**
     * @param {jQuery} $block
     */
    function moveUp($block) {
        var $previousBlock = $block.prev();

        if ($previousBlock.length === 0) {
            return;
        }

        $block.hide('slow', function () {
            $previousBlock.insertAfter($block);
            $('html, body').animate({
                scrollTop: $previousBlock.offset().top
            }, 1000);
            $block.show('slow');
            recalculatePositions($block.closest('[data-form-collection=list]'));
        });
    }

    /**
     * @param {jQuery} $block
     */
    function moveDown($block) {
        var $nextBlock = $block.next();

        if ($nextBlock.length === 0) {
            return;
        }

        $block.hide('slow', function () {
            $block.insertAfter($nextBlock);
            $('html, body').animate({
                scrollTop: $nextBlock.offset().top
            }, 1000);
            $block.show('slow');
            recalculatePositions($block.closest('[data-form-collection=list]'));
        });
    }

    /**
     * @param {jQuery} $list
     */
    function recalculatePositions($list) {
        $('[data-form-collection=item]', $list).each(function (key) {
            var $item = $(this);

            $('.position', $item).val(key);
        });
    }

})(jQuery);
