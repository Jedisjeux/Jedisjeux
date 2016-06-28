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
  });
});