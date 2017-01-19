$(function () {
  'use strict';

  $(document).ready(function () {

    var successMessageForAdd = "le jeu a bien été ajouté à votre liste.";
    var successMessageForRemove = "le jeu a bien été supprimé de votre liste.";
    var $productListForm = $('#productListForm');
    var $newProductListForm = $('#newProductListForm');
    var productId = $('input[name=productId]', $productListForm).val();

    initLists();
    selectListHandler();
    createNewListHandler();

    function initLists() {
      $.ajax({
        type: 'GET',
        url: Routing.generate('app_api_product_list_index'),
        success: function (lists) {
          $.each(lists, function (index, list) {
            addNewListOnDom(list.code, list.name)
          });

          getListsByProducts();
        },
        error: function (xhr, textStatus, errorThrown) {
          if (xhr.status === 401) {
            //handle error
            window.location.replace('/login');
          }
        }
      });
    }

    function getListsByProducts() {
      $.ajax({
        type: 'GET',
        url: Routing.generate('app_api_product_list_by_product', {'productId': productId}),
        success: function (lists) {
          $.each(lists, function (index, list) {
            selectList(list.code)
          });
        },
        error: function (xhr, textStatus, errorThrown) {
          if (xhr.status === 401) {
            //handle error
            window.location.replace('/login');
          }
        }
      });
    }

    function createNewListHandler() {
      $newProductListForm.submit(function (event) {
        event.preventDefault();

        var name = $('input[name=name]', $newProductListForm).val();

        $.ajax({
          type: 'POST',
          url: Routing.generate('app_api_product_list_create'),
          data: {
            'name': name
          },
          success: function (list) {
            var code = list.code;
            addOrRemoveProductFromList('POST', code);
            addNewListOnDom(code, name);
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

    function addNewListOnDom(code, name) {
      if (isListOnDom(code)) {
        return;
      }

      $productListForm.append(
        $('<div>')
          .attr('class', 'list')
          .append(
            $('<input>')
              .attr('id', code)
              .attr('type', 'checkbox')
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

    function selectList(code) {
      $('input', $productListForm).each(function () {
        if (code === $(this).attr('data-code')) {
          $(this).prop('checked', 'checked');
        }
      });
    }

    function isListOnDom(code) {
      var present = false;

      $('input', $productListForm).each(function () {
        if (code === $(this).attr('data-code')) {
          present = true;
        }
      });

      return present;
    }



    function selectListHandler() {
      $('input', $productListForm).change(function () {
        var code = $(this).data('code');
        var method = $(this).prop('checked') ? 'POST' : 'DELETE';

        addOrRemoveProductFromList(method, code);
      });
    }

    function addOrRemoveProductFromList(method, code) {
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