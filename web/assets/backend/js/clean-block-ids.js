$(function () {
  "use strict";

  $('input, textarea', $('form[name=app_article]')).each(function () {
    var name = $(this).attr('name');
    name = name.replace(/block_\w+/i, "");
    $(this).attr('name', name);
  });

});