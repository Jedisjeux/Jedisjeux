$(function () {
  "use strict";

  $('div[data-form-polycollection=item]').each(function() {
    var index = $(this).attr('data-form-polycollection-index');

    $('input, textarea', $(this)).each(function() {
      var name = $(this).attr('name');
      name = name.replace(/block_\w+/i, index);
      $(this).attr('name', name);
    });
  });

});