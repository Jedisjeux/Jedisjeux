$(function () {
  'use strict';

  $(document).ready(function () {
    $(".show-more a").on("click", function (event) {
      event.preventDefault();

      var $this = $(this);
      var $content = $this.parent().prev("div.content");

      if ($this.attr('data-closed') === '1') {
        $content.switchClass("hideContent", "showContent", 400);
        $this.text($this.attr('data-opened-label'));
        $this.attr('data-closed', '0');

      } else {
        $content.switchClass("showContent", "hideContent", 400);
        $this.text($this.attr('data-closed-label'));
        $this.attr('data-closed', '1');
      }
    });

    $("a.show-more-2").on("click", function (event) {
      event.preventDefault();

      var $this = $(this);
      var $content = $this.parent();

      if ($this.attr('data-closed')) {
        $('li', $content).show('slow');
        $this.removeAttr('data-closed');
        $this.html('<span class="fa fa-chevron-up"></span> ' + $this.attr('data-close-label'));
      } else {
        $('li.closed', $content).hide('slow');
        $this.attr('data-closed', '1');
        $this.html('<span class="fa fa-chevron-down"></span> ' + $this.attr('data-open-label'));
      }
    });

  });
});