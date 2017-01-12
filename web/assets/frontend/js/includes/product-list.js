$(function () {
  'use strict';

  $(document).ready(function () {

    var successMessageForAdd = "le jeu a bien été ajouté à votre liste.";
    var successMessageForRemove = "le jeu a bien été supprimé de votre liste.";

    $('input', '.productList').change(function() {
        console.log($(this));

        var code = $(this).data('code');
        var productId = $(this).data('product-id');
        var method = $(this).prop('checked') ? 'POST' : 'DELETE';

        $.ajax({
          type: method,
          url: Routing.generate('app_api_product_list_add_product', {
            'code': code, 'productId': productId
          }),
          success: function (results) {
            var message = method === 'POST' ? successMessageForAdd : successMessageForRemove;

            appendFlash(message);
          },
          error: function(xhr, textStatus, errorThrown) {
            if(xhr.status === 401) {
              //handle error
              window.location.replace('/login');
            }
          }
        });
    });

    function appendFlash(successMessage, messageHolderSelector) {
      messageHolderSelector = messageHolderSelector ? messageHolderSelector : '#flashes';

      $(messageHolderSelector).html('<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">×</a>' + successMessage + '</div>');
    }

  });
});