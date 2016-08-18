$(function () {
  'use strict';

  $(document).ready(function () {
    $(".draggable.revertible").draggable({
      revert: true,
      data: {
        'template': 'test'
      }
    });

    $(".droppable").droppable({
      drop: function (event, ui) {
        var $template = $( "#" + ui.draggable.data('template') );
        $(this).prev().append($template.html());
      }
    });

  });

});
