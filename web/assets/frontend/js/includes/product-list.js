$(function () {
  'use strict';

  $(document).ready(function () {

    var successMessageForAdd = "le jeu a bien été ajouté à votre liste.";
    var successMessageForRemove = "le jeu a bien été supprimé de votre liste.";
    var $newProductListForm = $('#newProductListForm');
    var $productList = $('.productList');

    selectListHandler();
    createNewListHandler();

    function createNewListHandler() {
      $newProductListForm.submit(function (event) {
        event.preventDefault();

        var name = $('input[name=name]', $newProductListForm).val();
        var productId = $('input[name=productId]', $newProductListForm).val();

        $.ajax({
          type: 'POST',
          url: Routing.generate('app_api_product_list_create'),
          data: {
            'name': name
          },
          success: function (list) {
            var code = list.code;
            addOrRemoveProductFromList('POST', code, productId);
            addNewListOnDom(code, name, productId);
          },
          error: function (xhr, textStatus, errorThrown) {
            if (xhr.status === 401) {
              //handle error
              window.location.replace('/login');
            }
          }
        });

      });
    }

    function addNewListOnDom(code, name, productId) {
      $('form', $productList).append(
        $('<div>')
          .attr('class', 'list')
          .append(
            $('<input>')
              .attr('id', code)
              .attr('type', 'checkbox')
              .attr('data-product-id', productId)
              .attr('data-code', code)
          )
          .append(' ')
          .append(
            $('<label>')
              .attr('for', code)
              .html(name)
          )
      );
    }

    function selectListHandler() {
      $('input', $productList).change(function () {
        var code = $(this).data('code');
        var productId = $(this).data('product-id');
        var method = $(this).prop('checked') ? 'POST' : 'DELETE';

        addOrRemoveProductFromList(method, code, productId);
      });
    }

    function addOrRemoveProductFromList(method, code, productId) {
      $.ajax({
        type: method,
        url: Routing.generate('app_api_product_list_add_product', {
          'code': code, 'productId': productId
        }),
        success: function (results) {
          var message = method === 'POST' ? successMessageForAdd : successMessageForRemove;

          appendFlash(message);
        },
        error: function (xhr, textStatus, errorThrown) {
          if (xhr.status === 401) {
            //handle error
            window.location.replace('/login');
          }
        }
      });
    }

    function appendFlash(successMessage, messageHolderSelector) {
      messageHolderSelector = messageHolderSelector ? messageHolderSelector : '#flashes';

      $(messageHolderSelector).html('<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">×</a>' + successMessage + '</div>');
    }

  });
});