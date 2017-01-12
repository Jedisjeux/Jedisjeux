$(function () {
  'use strict';

  var successMessage = "Votre article a bien été enregistrée.";

  $(document).ready(function () {

    var $form = $("form[name=app_article]");
    var $articleContent = $("#articleContent");
    var editorId = 0;

    initDraggableEvents();
    initDroppableEvents();
    submitFormHandler();

    /**
     * Call Api on submit button click
     */
    function submitFormHandler() {
      $form.submit(function (event) {
        event.preventDefault();

        $.ajax({
          type: "POST",
          url: Routing.generate('app_api_article_create'),
          data: getArticleData(),
          success: function () {
            appendFlash(successMessage);
          },
          error: function (xhr, textStatus, errorThrown) {
            if (xhr.status === 401) {
              //handle error
              window.location.replace(Routing.generate('sylius_user_security_login'));
            } else {
              var message = xhr.responseJSON.error.exception[0].message;
              appendFlash(message, null, 'danger');
            }
          }
        });

      });
    }

    /**
     * Init draggable events
     */
    function initDraggableEvents() {
      $(".draggable.revertible").draggable({
        revert: true,
        data: {
          'template': 'test'
        }
      });
    }

    /**
     * Init droppable events
     */
    function initDroppableEvents() {
      $(".droppable").droppable({
        drop: function (event, ui) {
          var $template = $("#" + ui.draggable.data('template'));
          var $block = $('.block-editing', $template).clone();
          editorId = editorId + 1;
          $block
            .attr('contenteditable', 'true')
            .attr('id', 'editor' + editorId);

          $articleContent.append($block);
          CKEDITOR.inline('editor' + editorId);
        }
      });
    }

    /**
     * Get article serialized data
     */
    function getArticleData() {
      var title = $('#app_article_document_title').val().trim();
      var children = [];

      $articleContent.children().each(function (key) {
        children.push(getBlockData($(this), key, title));
      });

      return {
        status: $('#app_article_status').val(),
        document: {
          name: slugMe(title),
          title: title,
          children: children
        }
      };
    }

    /**
     * Get serialized data of a block
     */
    function getBlockData($block, key, title) {
      var blockTitle = $('h4 a', $block).html() ? $('h4 a', $block).html().trim() : null;

      var type = $block.attr('data-type');
      var name = slugMe(title + ' ' + key);
      var body = $('.block-body', $block).html().trim();

      if ('single_image' === type) {

        var data = new FormData();

        return {
          name: name,
          title: blockTitle,
          body: body,
          imagePosition: $block.attr('data-image-position'),
          children: [{
            name: 'image' + key/*,
             image: {
             ContentUrl: 'https://placeholdit.imgix.net/~text?txtsize=150&txt=Image...&w=1600&h=900'
             }*/
          }],
          class: null,
          _type: $block.attr('data-type')
        };
      } else {
        return {
          name: name,
          body: body,
          _type: $block.attr('data-type')
        };
      }

    }

    /** use npm slug instead
     * @see https://www.npmjs.com/package/slug
     **/

    function slugMe(value) {
      var rExps = [
        {re: /[\xC0-\xC6]/g, ch: 'A'},
        {re: /[\xE0-\xE6]/g, ch: 'a'},
        {re: /[\xC8-\xCB]/g, ch: 'E'},
        {re: /[\xE8-\xEB]/g, ch: 'e'},
        {re: /[\xCC-\xCF]/g, ch: 'I'},
        {re: /[\xEC-\xEF]/g, ch: 'i'},
        {re: /[\xD2-\xD6]/g, ch: 'O'},
        {re: /[\xF2-\xF6]/g, ch: 'o'},
        {re: /[\xD9-\xDC]/g, ch: 'U'},
        {re: /[\xF9-\xFC]/g, ch: 'u'},
        {re: /[\xC7-\xE7]/g, ch: 'c'},
        {re: /[\xD1]/g, ch: 'N'},
        {re: /[\xF1]/g, ch: 'n'}];

      // convertit les caractères accentués en leurs équivalent alpha
      for (var i = 0, len = rExps.length; i < len; i++)
        value = value.replace(rExps[i].re, rExps[i].ch);

      // 1) met en bas de casse
      // 2) remplace les espaces par des tirets
      // 3) enleve tout les caractères non alphanumériques
      // 4) enlève les doubles tirets
      return value.toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9-]/g, '')
        .replace(/\-{2,}/g, '-');
    }

    function appendFlash(successMessage, messageHolderSelector, type) {
      type = type ? type : 'success';
      $("html, body").animate({scrollTop: 0}, "slow");
      messageHolderSelector = messageHolderSelector ? messageHolderSelector : '#flashes';
      $(messageHolderSelector).html('<div class="alert alert-' + type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + successMessage + '</div>');
    }

  });
});
