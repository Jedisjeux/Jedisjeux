/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author Arnaud Langlade <arn0d.dev@gmail.com>
 */
!function($){

  "use strict";

  /**
   * Collection Form plugin
   *
   * @param element
   * @constructor
   */
  var PolycollectionForm = function (element) {
    this.$element = $(element);
    this.$list = this.$element.find('[data-form-polycollection="list"]:first');
    this.count = this.$list.children().length;
    this.lastChoice = null;

    this.$element.on(
      'click',
      '[data-form-polycollection="add"]',
      $.proxy(this.addItem, this)
    );

    this.$element.on(
      'click',
      '[data-form-polycollection="delete"]',
      $.proxy(this.deleteItem, this)
    );

    this.$element.on(
      'change',
      '[data-form-polycollection="update"]',
      $.proxy(this.updateItem, this)
    );

    $(document).on(
      'change',
      '[data-form-prototype="update"]',
      $.proxy(this.updatePrototype, this)
    );
  }
  PolycollectionForm.prototype = {
    constructor : PolycollectionForm,

    /**
     * Add a item to the collection.
     * @param event
     */
    addItem: function (event) {
      event.preventDefault();

      //var prototype = this.$element.data('prototype');
      var prototype = $(event.currentTarget).data('prototype');

      prototype = prototype.replace(
        /__name__/g,
        this.count
      );

      this.$list.append(prototype);
      this.count = this.count + 1;

      $(document).trigger('polycollection-form-add', [this.$list.children().last()]);
    },

    /**
     * Update item from the collection
     */
    updateItem: function (event) {
      event.preventDefault();
      var $element = $(event.currentTarget),
        url = $element.data('form-url'),
        value = $element.val(),
        $container = $element.closest('[data-form-polycollection="item"]'),
        index = $container.data('form-polycollection-index'),
        position = $container.data('form-polycollection-index');
      if (url) {
        $container.load(url, {'id' : value, 'position' : position});
      } else {
        var prototype = this.$element.find('[data-form-prototype="'+ value +'"]').val();

        prototype = prototype.replace(
          /__name__/g,
          index
        );

        $container.replaceWith(prototype);
      }
      $(document).trigger('polycollection-form-update', [$(event.currentTarget)]);
    },

    /**
     * Delete item from the collection
     * @param event
     */
    deleteItem: function (event) {
      event.preventDefault();

      $(event.currentTarget)
        .closest('[data-form-polycollection="item"]')
        .remove();

      $(document).trigger('polycollection-form-delete', [$(event.currentTarget)]);
    },

    /**
     * Update the prototype
     * @param event
     */
    updatePrototype: function (event) {
      var $target = $(event.currentTarget);
      var prototypeName = $target.val();

      if (undefined !== $target.data('form-prototype-prefix')) {
        prototypeName = $target.data('form-prototype-prefix') + prototypeName;
      }

      if (null !== this.lastChoice && this.lastChoice !== prototypeName) {
        this.$list.html('');
      }

      this.lastChoice = prototypeName;

      this.$element.data(
        'prototype',
        this.$element.find('[data-form-prototype="'+ prototypeName +'"]').val()
      );
    }
  };

  /*
   * Plugin definition
   */

  $.fn.PolycollectionForm = function (option) {
    return this.each(function () {
      var $this = $(this);
      var data = $this.data('PolycollectionForm');
      var options = typeof option == 'object' && option;

      if (!data) {
        $this.data(
          'PolycollectionForm',
          (data = new PolycollectionForm(this, options))
        )
      }
    })
  };

  $.fn.PolycollectionForm.Constructor = PolycollectionForm;

  /*
   * Apply to standard PolycollectionForm elements
   */

  $(document).on('polycollection-form-add', function(e, addedElement) {
    $(addedElement).find('[data-form-type="polycollection"]').PolycollectionForm();
    $(document).trigger('dom-node-inserted', [$(addedElement)]);
  });

  $(document).ready(function () {
    $('[data-form-type="polycollection"]').PolycollectionForm();
  });
}(jQuery);
